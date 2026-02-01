<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Contract_import extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_contract_import', 'M_hris', 'M_kontrak'));
        $this->load->helper(array('form', 'url'));
    }

    /**
     * Display the upload form for contract import
     */
    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Clear any existing session data to ensure clean state
        $this->session->unset_userdata('contract_import_data');
        $this->session->unset_userdata('contract_import_file');
        $this->session->unset_userdata('contract_import_results');

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('contract/import_form', $data);
        $this->load->view('layout/a_footer');
    }

    /**
     * Handle file upload for contract import
     */
    public function upload()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Clear any existing session data to prevent conflicts with previous imports
        $this->session->unset_userdata('contract_import_data');
        $this->session->unset_userdata('contract_import_file');
        $this->session->unset_userdata('contract_import_results');

        // Configuration for file upload
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = 10240; // 10MB
        $config['overwrite'] = TRUE;
        $config['file_name'] = 'contract_import_' . time();

        // Create uploads directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('contract_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Upload failed: ' . $error);
            redirect('Contract_import');
        } else {
            $upload_data = $this->upload->data();
            $file_path = $upload_data['full_path'];

            // Parse the Excel file for contract data
            $parse_result = $this->M_contract_import->parse_contract_excel($file_path);

            if (!$parse_result['success']) {
                $this->session->set_flashdata('error', $parse_result['message']);
                unlink($file_path); // Delete uploaded file
                redirect('Contract_import');
            }

            // Store parsed data in session for preview
            $this->session->set_userdata('contract_import_data', $parse_result['data']);
            $this->session->set_userdata('contract_import_file', $file_path);

            // Redirect to preview page
            redirect('Contract_import/preview');
        }
    }

    /**
     * Parse Excel file for contract import
     */
    private function parse_contract_excel($file_path)
    {
        try {
            $inputFileType = IOFactory::identify($file_path);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file_path);

            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Detect header structure - look for merged cells pattern
            $header_row_1 = 1; // Title row
            $header_row_2 = 2; // Empty row
            $header_row_3 = 3; // Main headers (NIK, KONTRAK)
            $header_row_4 = 4; // Contract numbers (1, 2, 3, ...)
            $header_row_5 = 5; // Sub headers (AWAL/AKHIR)
            
            // Check if we have the expected structure
            $nik_header = trim(strtoupper($worksheet->getCell('A3')->getValue() ?? ''));
            $kontrak_header = trim(strtoupper($worksheet->getCell('B3')->getValue() ?? ''));
            
            if ($nik_header !== 'NIK' || $kontrak_header !== 'KONTRAK') {
                return [
                    'success' => false,
                    'message' => 'Invalid header format. Expected: A3=NIK, B3=KONTRAK'
                ];
            }

            // Process data rows (starting from row 6)
            $data = [];
            $first_data_row = 6;

            for ($row = $first_data_row; $row <= $highestRow; $row++) {
                $rowData = [];
                $hasData = false;

                // Get NIK (column A)
                $nik_cell = $worksheet->getCell('A' . $row);
                $nik_value = trim((string)$nik_cell->getValue());
                
                // Skip rows that contain instruction text
                if (stripos($nik_value, 'instruksi') !== false || 
                    stripos($nik_value, 'kolom') !== false ||
                    stripos($nik_value, 'wajib') !== false ||
                    stripos($nik_value, 'isi tanggal') !== false ||
                    stripos($nik_value, 'pasangan awal') !== false ||
                    preg_match('/^\d+\.$/', trim($nik_value)) || // Matches "1.", "2.", "3.", etc.
                    preg_match('/^\d+$/', trim($nik_value)) || // Matches pure numbers like "2", "3"
                    empty($nik_value)) {
                    continue;
                }
                
                if ($nik_value !== '') {
                    $hasData = true;
                    $rowData['NIK'] = $nik_value;
                }

                // Process contract pairs starting from column B
                $contract_index = 1;
                $col_index = 2; // Start from column B
                
                while ($col_index <= $highestColumnIndex) {
                    // Get AWAL (current column)
                    $awal_cell = $worksheet->getCellByColumnAndRow($col_index, $row);
                    $awal_value = $awal_cell->getValue();
                    
                    // Get AKHIR (next column)
                    $akhir_cell = $worksheet->getCellByColumnAndRow($col_index + 1, $row);
                    $akhir_value = $akhir_cell->getValue();
                    
                    // Convert Excel date serial numbers to formatted dates
                    if (is_numeric($awal_value) && $awal_value > 1) {
                        // Excel date serial number - convert to date
                        $awal_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($awal_value);
                        $awal_value = $awal_date->format('d-M-y');
                    } else {
                        $awal_value = trim((string)$awal_value);
                    }
                    
                    if (is_numeric($akhir_value) && $akhir_value > 1) {
                        // Excel date serial number - convert to date
                        $akhir_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($akhir_value);
                        $akhir_value = $akhir_date->format('d-M-y');
                    } else {
                        $akhir_value = trim((string)$akhir_value);
                    }
                    
                    // If either value exists, store the pair
                    if ($awal_value !== '' || $akhir_value !== '') {
                        $rowData['AWAL_' . $contract_index] = $awal_value;
                        $rowData['AKHIR_' . $contract_index] = $akhir_value;
                        $hasData = true;
                    }
                    
                    $contract_index++;
                    $col_index += 2; // Move to next pair (skip 2 columns)
                }

                // Only add row if it has data
                if ($hasData && !empty($rowData['NIK'])) {
                    $data[] = $rowData;
                }
            }

            if (empty($data)) {
                return [
                    'success' => false,
                    'message' => 'No valid data found in the file'
                ];
            }

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error parsing Excel file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Preview the imported contract data
     */
    public function preview()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $contract_data = $this->session->userdata('contract_import_data');

        if (empty($contract_data)) {
            $this->session->set_flashdata('error', 'No data to preview. Please upload a file first.');
            redirect('Contract_import');
            return;
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['preview_data'] = $contract_data;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('contract/import_preview', $data);
        $this->load->view('layout/a_footer');
    }

    /**
     * Process the contract data (validation and preparation)
     */
    public function process_data()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $contract_data = $this->session->userdata('contract_import_data');

        if (empty($contract_data)) {
            $this->session->set_flashdata('error', 'No data to process. Please upload a file first.');
            redirect('Contract_import');
            return;
        }

        // Process the data (without validation for now)
        $processing_results = $this->M_contract_import->process_contract_data($contract_data);

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['processing_results'] = $processing_results;

        // Show loading view instead of directly importing
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('contract/import_loading');
        $this->load->view('layout/a_footer');
    }

    /**
     * Process contract data for validation and preparation
     */
    private function process_contract_data($contract_data)
    {
        $results = [
            'total_records' => count($contract_data),
            'processed_records' => 0,
            'successful_imports' => 0,
            'failed_imports' => 0,
            'errors' => [],
            'warnings' => []
        ];

        foreach ($contract_data as $index => $contract) {
            $row_number = $index + 2; // +2 because data starts from row 2 (row 1 is header)
            
            // Validate required fields
            if (empty($contract['NIK'])) {
                $results['errors'][] = [
                    'row' => $row_number,
                    'field' => 'NIK',
                    'message' => 'NIK is required'
                ];
                $results['failed_imports']++;
                continue;
            }

            // Check if employee exists
            $employee = $this->db->get_where('karyawan', array('nik' => $contract['NIK']))->row();
            if (!$employee) {
                $results['errors'][] = [
                    'row' => $row_number,
                    'field' => 'NIK',
                    'message' => 'Employee with NIK ' . $contract['NIK'] . ' does not exist'
                ];
                $results['failed_imports']++;
                continue;
            }

            // Validate contract dates
            $contract_pairs = [];
            for ($i = 1; $i <= 44; $i++) { // Assuming up to 44 contract pairs like in the template
                $awal_key = 'AWAL_' . $i;
                $akhir_key = 'AKHIR_' . $i;
                
                $awal_value = isset($contract[$awal_key]) ? trim($contract[$awal_key]) : '';
                $akhir_value = isset($contract[$akhir_key]) ? trim($contract[$akhir_key]) : '';

                if ($awal_value !== '' || $akhir_value !== '') {
                    // Validate date format
                    if ($awal_value !== '') {
                        $awal_date = DateTime::createFromFormat('Y-m-d', $awal_value) ?: DateTime::createFromFormat('d/m/Y', $awal_value) ?: DateTime::createFromFormat('d-m-Y', $awal_value);
                        if (!$awal_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $awal_key,
                                'message' => 'Invalid date format for ' . $awal_key . '. Expected YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY'
                            ];
                            continue;
                        }
                    }

                    if ($akhir_value !== '') {
                        $akhir_date = DateTime::createFromFormat('Y-m-d', $akhir_value) ?: DateTime::createFromFormat('d/m/Y', $akhir_value) ?: DateTime::createFromFormat('d-m-Y', $akhir_value);
                        if (!$akhir_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $akhir_key,
                                'message' => 'Invalid date format for ' . $akhir_key . '. Expected YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY'
                            ];
                            continue;
                        }
                    }

                    if ($awal_value !== '' && $akhir_value !== '') {
                        $awal_date = DateTime::createFromFormat('Y-m-d', $awal_value) ?: DateTime::createFromFormat('d/m/Y', $awal_value) ?: DateTime::createFromFormat('d-m-Y', $awal_value);
                        $akhir_date = DateTime::createFromFormat('Y-m-d', $akhir_value) ?: DateTime::createFromFormat('d/m/Y', $akhir_value) ?: DateTime::createFromFormat('d-m-Y', $akhir_value);
                        
                        if ($awal_date && $akhir_date && $awal_date > $akhir_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $awal_key . '/' . $akhir_key,
                                'message' => 'Start date cannot be after end date'
                            ];
                            continue;
                        }
                    }

                    $contract_pairs[] = [
                        'awal' => $awal_value,
                        'akhir' => $akhir_value,
                        'employee_id' => $employee->recid_karyawan
                    ];
                }
            }

            // If we have valid contract pairs, increment successful count
            if (!empty($contract_pairs)) {
                $results['successful_imports']++;
            } else {
                $results['warnings'][] = [
                    'row' => $row_number,
                    'message' => 'No valid contract dates found for this employee'
                ];
            }

            $results['processed_records']++;
        }

        return $results;
    }

    /**
     * Execute the import process
     */
    public function do_import()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Check if we're coming from pagination (import results in session) or fresh import
        $import_results = $this->session->userdata('contract_import_results');
        
        if (empty($import_results)) {
            $contract_data = $this->session->userdata('contract_import_data');
            
            if (empty($contract_data)) {
                $this->session->set_flashdata('error', 'No data to import. Please upload a file first.');
                redirect('Contract_import');
                return;
            }

            // Perform the actual import
            $import_results = $this->perform_import($contract_data);

            // Store results in session
            $this->session->set_userdata('contract_import_results', $import_results);
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['import_results'] = $import_results;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('contract/import_complete', $data);
        $this->load->view('layout/a_footer');
    }

    /**
     * Perform the actual contract import
     */
    private function perform_import($contract_data)
    {
        $results = [
            'total_records' => count($contract_data),
            'inserted_records' => 0,
            'updated_records' => 0,
            'failed_imports' => 0,
            'errors' => [],
            'inserted_details' => [],
            'updated_details' => []
        ];

        foreach ($contract_data as $index => $contract) {
            $row_number = $index + 2; // +2 because data starts from row 2 (row 1 is header)
            
            // Check if employee exists
            $employee = $this->db->get_where('karyawan', array('nik' => $contract['NIK']))->row();
            if (!$employee) {
                $results['errors'][] = [
                    'row' => $row_number,
                    'message' => 'Employee with NIK ' . $contract['NIK'] . ' does not exist'
                ];
                $results['failed_imports']++;
                continue;
            }

            // Process contract dates
            $contracts_added = 0;
            for ($i = 1; $i <= 44; $i++) { // Assuming up to 44 contract pairs like in the template
                $awal_key = 'AWAL_' . $i;
                $akhir_key = 'AKHIR_' . $i;
                
                $awal_value = isset($contract[$awal_key]) ? trim($contract[$awal_key]) : '';
                $akhir_value = isset($contract[$akhir_key]) ? trim($contract[$akhir_key]) : '';

                if ($awal_value !== '' && $akhir_value !== '') {
                    // Validate date format
                    $awal_date = DateTime::createFromFormat('d-M-y', $awal_value) ?: 
                               DateTime::createFromFormat('d-M-Y', $awal_value) ?: 
                               DateTime::createFromFormat('Y-m-d', $awal_value) ?: 
                               DateTime::createFromFormat('d/m/Y', $awal_value) ?: 
                               DateTime::createFromFormat('d-m-Y', $awal_value);
                    $akhir_date = DateTime::createFromFormat('d-M-y', $akhir_value) ?: 
                                DateTime::createFromFormat('d-M-Y', $akhir_value) ?: 
                                DateTime::createFromFormat('Y-m-d', $akhir_value) ?: 
                                DateTime::createFromFormat('d/m/Y', $akhir_value) ?: 
                                DateTime::createFromFormat('d-m-Y', $akhir_value);

                    if ($awal_date && $akhir_date) {
                        // Format dates to Y-m-d for database
                        $formatted_awal = $awal_date->format('Y-m-d');
                        $formatted_akhir = $akhir_date->format('Y-m-d');

                        // Check if contract already exists for this employee and date range
                        $existing_contract = $this->db->get_where('karyawan_kontrak', array(
                            'recid_karyawan' => $employee->recid_karyawan,
                            'tgl_mulai' => $formatted_awal,
                            'tgl_akhir' => $formatted_akhir
                        ))->row();

                        if (!$existing_contract) {
                            // Insert new contract (only using existing columns)
                            $contract_data = array(
                                'recid_karyawan' => $employee->recid_karyawan,
                                'tgl_mulai' => $formatted_awal,
                                'tgl_akhir' => $formatted_akhir,
                                'status_kontrak' => 'aktif',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );

                            if ($this->db->insert('karyawan_kontrak', $contract_data)) {
                                $results['inserted_records']++;
                                $contracts_added++;
                                
                                // Track inserted record details
                                $results['inserted_details'][] = [
                                    'NIK' => $contract['NIK'],
                                    'NAMA' => $employee->nama_karyawan,
                                    'tgl_mulai' => $formatted_awal,
                                    'tgl_akhir' => $formatted_akhir
                                ];
                            } else {
                                $db_error = $this->db->error();
                                log_message('error', 'Contract controller insert failed for NIK ' . $contract['NIK'] . ': ' . $db_error['message']);
                                $results['errors'][] = [
                                    'row' => $row_number,
                                    'message' => 'Failed to insert contract for NIK: ' . $contract['NIK'] . ' - ' . $db_error['message']
                                ];
                                $results['failed_imports']++;
                            }
                        } else {
                            // Contract already exists
                            $results['updated_records']++;
                            $results['updated_details'][] = [
                                'NIK' => $contract['NIK'],
                                'NAMA' => $employee->nama_karyawan,
                                'tgl_mulai' => $formatted_awal,
                                'tgl_akhir' => $formatted_akhir,
                                'message' => 'Contract already exists, skipping duplicate'
                            ];
                        }
                    } else {
                        $results['errors'][] = [
                            'row' => $row_number,
                            'message' => 'Invalid date format in contract data for NIK: ' . $contract['NIK'] . ' (both AWAL and AKHIR dates required)'
                        ];
                        $results['failed_imports']++;
                    }
                }
            }

            if ($contracts_added === 0 && !empty($results['errors']) && end($results['errors'])['row'] !== $row_number) {
                $results['warnings'][] = [
                    'row' => $row_number,
                    'message' => 'No valid contracts found for NIK: ' . $contract['NIK']
                ];
            }
        }

        return $results;
    }

    /**
     * Download sample template for contract import
     */
    public function download_template()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', 'Template Import Kontrak Karyawan');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        
        // Create the header structure (merged cells format)
        // Row 3: Headers
        $sheet->setCellValue('A3', 'NIK');
        $sheet->setCellValue('B3', 'KONTRAK');
        
        // Merge NIK cell vertically across all header rows (rows 3-5)
        $sheet->mergeCells('A3:A5');
        
        // Merge KONTRAK cell to span all contract columns
        $sheet->mergeCells('B3:G3'); // Assuming up to 3 contract pairs (6 columns)
        
        // Row 4: Numbered contract periods (1, 2, 3)
        $contract_num_col = 2; // Start from column B
        for ($i = 1; $i <= 3; $i++) { // 3 contract periods
            $start_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($contract_num_col);
            $end_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($contract_num_col + 1);
            $sheet->mergeCells($start_col . '4:' . $end_col . '4');
            $sheet->setCellValueByColumnAndRow($contract_num_col, 4, $i);
            $contract_num_col += 2;
        }
        
        // Row 5: Sub-headers (AWAL/AKHIR pairs)
        $sub_header_col = 2; // Start from column B
        for ($i = 1; $i <= 3; $i++) { // 3 contract pairs
            $sheet->setCellValueByColumnAndRow($sub_header_col, 5, 'AWAL');
            $sheet->setCellValueByColumnAndRow($sub_header_col + 1, 5, 'AKHIR');
            $sub_header_col += 2;
        }
        
        // Style headers
        $sheet->getStyle('A3:A5')->getAlignment()->setVertical('center')->setHorizontal('center');
        $sheet->getStyle('A3:G5')->getFont()->setBold(true);
        $sheet->getStyle('A3:G5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6E6');
        $sheet->getStyle('A3:G5')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Add sample data (row 6)
        $sheet->setCellValue('A6', '123456789');
        $sheet->setCellValue('B6', '30-Jun-25');
        $sheet->setCellValue('C6', '19-Sep-25');
        $sheet->setCellValue('D6', '20-Sep-25');
        $sheet->setCellValue('E6', '19-Dec-25');
        // Leave F6 and G6 empty for the third contract period
                
        // Add borders to sample data row
        $sheet->getStyle('A6:G6')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Add instructions below the table (row 8 onwards)
        $sheet->setCellValue('A8', 'Instruksi:');
        $sheet->setCellValue('A9', '1. Kolom NIK wajib diisi');
        $sheet->setCellValue('A10', '2. Isi tanggal kontrak dalam format DD-MMM-YY (misal: 30-Jun-25)');
        $sheet->setCellValue('A11', '3. Setiap pasangan AWAL dan AKHIR merepresentasikan satu periode kontrak');
        $sheet->setCellValue('A12', '4. Kosongkan kolom jika tidak ada data');
        
        // Style instructions
        $sheet->getStyle('A8')->getFont()->setBold(true);
        
        // Auto size columns
        for ($col = 'A'; $col <= 'G'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set the header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_kontrak.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * AJAX endpoint for importing contract data with progress updates
     */
    public function ajax_import()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
            return;
        }

        $contract_data = $this->session->userdata('contract_import_data');
        
        if (empty($contract_data)) {
            echo json_encode(['status' => 'error', 'message' => 'No data to import']);
            return;
        }

        // Perform the import
        $import_results = $this->perform_import($contract_data);

        // Store results in session
        $this->session->set_userdata('contract_import_results', $import_results);

        echo json_encode([
            'status' => 'success',
            'message' => 'Import completed successfully',
            'redirect_url' => base_url('Contract_import/results')
        ]);
    }

    /**
     * Show import results
     */
    public function results()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Get import results from session
        $import_results = $this->session->userdata('contract_import_results');
        
        if (empty($import_results)) {
            $this->session->set_flashdata('error', 'No import results found.');
            redirect('Contract_import');
            return;
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['import_summary'] = $import_results;
        $data['results'] = $this->session->userdata('contract_import_data'); // Pass original data for sample display

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('contract/import_complete', $data);
        $this->load->view('layout/a_footer');
    }
}
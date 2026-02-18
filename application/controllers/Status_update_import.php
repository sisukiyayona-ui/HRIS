<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status_update_import extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_employee_import');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $this->load->model('M_hris');
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/status_update_import_form');
        $this->load->view('layout/a_footer');
    }

    public function download_template()
    {
        // Create a new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define column headers
        $headers = [
            'NIK',
            'STATUS_KARYAWAN', 
            'SK_KARY_TETAP_NOMOR',
            'SK_KARY_TETAP_TANGGAL'
        ];

        // Write headers to the first row
        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header);
            $column++;
        }

        // Style for header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Apply header style
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Add sample data row to demonstrate proper format (using the requested format)
        $sampleData = [
            'C2-4034',                   // NIK
            'KARYAWAN TETAP',            // STATUS_KARYAWAN
            'No: 745/SK/PRES/TSGI/07/91', // SK_KARY_TETAP_NOMOR
            '21-Jul-91'                  // SK_KARY_TETAP_TANGGAL (DD-Mon-YY format)
        ];

        // Write sample data to the second row
        $column = 1;
        foreach ($sampleData as $data) {
            $sheet->setCellValueByColumnAndRow($column, 2, $data);
            $column++;
        }

        // Add another sample row with different date format
        $sampleData2 = [
            'C3-4035',                   // NIK
            'TETAP',                     // STATUS_KARYAWAN
            'No: 746/SK/PRES/TSGI/08/92', // SK_KARY_TETAP_NOMOR
            '22-Aug-1992'               // SK_KARY_TETAP_TANGGAL (DD-Mon-YYYY format)
        ];

        // Write second sample data to the third row
        $column = 1;
        foreach ($sampleData2 as $data) {
            $sheet->setCellValueByColumnAndRow($column, 3, $data);
            $column++;
        }

        // Add another sample row with DD/MM/YY format
        $sampleData3 = [
            'C4-4036',                   // NIK
            'KARYAWAN TETAP',            // STATUS_KARYAWAN
            'No: 747/SK/PRES/TSGI/09/93', // SK_KARY_TETAP_NOMOR
            '23/09/93'                  // SK_KARY_TETAP_TANGGAL (DD/MM/YY format)
        ];

        // Write third sample data to the fourth row
        $column = 1;
        foreach ($sampleData3 as $data) {
            $sheet->setCellValueByColumnAndRow($column, 4, $data);
            $column++;
        }

        // Apply border style to sample data
        $dataBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Apply border to all data rows
        $sheet->getStyle('A1:D4')->applyFromArray($dataBorderStyle);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);

        // Set alignment
        $sheet->getStyle('A1:D4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // Freeze the header row
        $sheet->freezePane('A2');

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("HRIS System")
            ->setTitle("Template Perubahan Status Karyawan")
            ->setSubject("Template Perubahan Status Karyawan")
            ->setDescription("Template untuk import perubahan status karyawan menjadi tetap");

        // Set sheet title
        $sheet->setTitle('Template Status Tetap');

        // Create Excel file
        $filename = 'Template_Perubahan_Status_Karyawan.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function upload()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $this->load->model('M_hris');
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv|xls|xlsx';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('status_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect('Status_update_import');
            return;
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];

        // Parse the uploaded file
        $parsed_data = $this->parse_excel_file($file_path);
        
        if ($parsed_data === false) {
            $this->session->set_flashdata('error', 'Gagal membaca file. Pastikan format file benar.');
            redirect('Status_update_import');
            return;
        }

        // Store parsed data in session
        $this->session->set_userdata('status_update_data', $parsed_data);
        
        // Prepare preview data (first 10 rows)
        $preview_data = array_slice($parsed_data, 0, 10);
        $data['preview_data'] = $preview_data;
        $data['total_rows'] = count($parsed_data);

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/status_update_import_preview');
        $this->load->view('layout/a_footer');
    }

    private function parse_excel_file($file_path)
    {
        // Don't load the library here as it's already available through the autoloader
        // Just use the class directly
        
        // Temporarily suppress deprecation warnings for PHP 8.2+ compatibility
        $previousErrorReporting = error_reporting();
        error_reporting($previousErrorReporting & ~E_DEPRECATED);
        
        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $rows = [];
            $header = [];
            $startRow = 1;
            
            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $rowData = [];
                $colIndex = 0;
                
                foreach ($cellIterator as $cell) {
                    $value = $cell !== null ? $cell->getValue() : '';
                    
                    if ($rowIndex == $startRow) {
                        // This is header row
                        $header[] = trim(strtoupper(str_replace(' ', '_', $value)));
                    } else {
                        // This is data row
                        $columnName = isset($header[$colIndex]) ? $header[$colIndex] : $colIndex;
                        $rowData[$columnName] = $value;
                    }
                    $colIndex++;
                }
                
                if ($rowIndex > $startRow && !empty(array_filter($rowData))) {
                    // Validate required fields
                    if (!isset($rowData['NIK']) || empty($rowData['NIK'])) {
                        continue; // Skip rows without NIK
                    }
                    $rows[] = $rowData;
                }
            }
            
            return $rows;
        } catch (Exception $e) {
            log_message('error', 'Error parsing Excel file: ' . $e->getMessage());
            return false;
        } finally {
            // Restore original error reporting level
            error_reporting($previousErrorReporting);
        }
    }

    public function validate_data()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $raw_data = $this->session->userdata('status_update_data');
        if (!$raw_data) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk divalidasi. Silakan upload file terlebih dahulu.');
            redirect('Status_update_import');
            return;
        }

        $this->load->model('M_hris');
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $validation_results = [
            'valid_records' => [],
            'invalid_records' => [],
            'total_valid' => 0,
            'total_invalid' => 0
        ];

        foreach ($raw_data as $index => $row) {
            $errors = [];
            
            // Validate NIK
            if (!isset($row['NIK']) || empty(trim($row['NIK']))) {
                $errors[] = 'NIK wajib diisi';
            }
            
            // Validate STATUS_KARYAWAN
            if (!isset($row['STATUS_KARYAWAN']) || empty(trim($row['STATUS_KARYAWAN']))) {
                $errors[] = 'Status Karyawan wajib diisi';
            } else {
                $valid_statuses = ['KARYAWAN TETAP', 'TETAP'];
                if (!in_array(strtoupper(trim($row['STATUS_KARYAWAN'])), $valid_statuses)) {
                    $errors[] = 'Status Karyawan harus "KARYAWAN TETAP" atau "TETAP"';
                }
            }
            
            // Validate SK_KARY_TETAP_NOMOR
            if (!isset($row['SK_KARY_TETAP_NOMOR']) || empty(trim($row['SK_KARY_TETAP_NOMOR']))) {
                $errors[] = 'Nomor SK Karyawan Tetap wajib diisi';
            }
            
            // Validate SK_KARY_TETAP_TANGGAL
            if (!isset($row['SK_KARY_TETAP_TANGGAL']) || empty(trim($row['SK_KARY_TETAP_TANGGAL']))) {
                $errors[] = 'Tanggal SK Karyawan Tetap wajib diisi';
            } else {
                // Check if date is valid - trying multiple possible formats
                $date_input = trim($row['SK_KARY_TETAP_TANGGAL']);
                $valid_date = false;
                
                // Try different date formats
                $date_formats = [
                    'Y-m-d',        // YYYY-MM-DD
                    'd/m/Y',        // DD/MM/YYYY
                    'd-m-Y',        // DD-MM-YYYY
                    'm/d/Y',        // MM/DD/YYYY
                    'm-d-Y',        // MM-DD-YYYY
                    'd/M/y',        // DD/Mon/YY (e.g., 21-Jul-91)
                    'd-M-y',        // DD-Mon-YY (e.g., 21-Jul-91)
                    'd/M/Y',        // DD/Mon/YYYY (e.g., 21-Jul-1991)
                    'd-M-Y',        // DD-Mon-YYYY (e.g., 21-Jul-1991)
                    'd/m/y',        // DD/MM/YY
                    'd-m-y',        // DD-MM-YY
                    'm/d/y',        // MM/DD/YY
                    'm-d-y'         // MM-DD-YY
                ];
                
                foreach ($date_formats as $format) {
                    $date = date_parse_from_format($format, $date_input);
                    if ($date['error_count'] == 0 && $date['year'] > 0) {
                        $valid_date = true;
                        break;
                    }
                }
                
                if (!$valid_date) {
                    $errors[] = 'Format tanggal tidak valid. Gunakan format seperti: YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY, DD/MM/YY, DD-MM-YY, DD-Mon-YY, DD/Mon/YY, DD-Mon-YYYY, DD/Mon/YYYY';
                }
            }
            
            // Check if employee exists in database
            if (isset($row['NIK']) && !empty(trim($row['NIK']))) {
                $this->db->select('nik');
                $this->db->from('karyawan');
                $this->db->where('nik', $row['NIK']);
                $exists = $this->db->get()->num_rows() > 0;
                
                if (!$exists) {
                    $errors[] = 'NIK tidak ditemukan di database';
                }
            }
            
            if (empty($errors)) {
                // Convert date to standard format if needed
                if (isset($row['SK_KARY_TETAP_TANGGAL'])) {
                    $date_input = trim($row['SK_KARY_TETAP_TANGGAL']);
                    
                    // Temporarily suppress deprecation warnings for PHP 8.2+ compatibility
                    $previousErrorReporting = error_reporting();
                    error_reporting($previousErrorReporting & ~E_DEPRECATED);
                    
                    try {
                        // Try different date formats to parse the input
                        $date_formats = [
                            'Y-m-d',        // YYYY-MM-DD
                            'd/m/Y',        // DD/MM/YYYY
                            'd-m-Y',        // DD-MM-YYYY
                            'm/d/Y',        // MM/DD/YYYY
                            'm-d-Y',        // MM-DD-YYYY
                            'd/M/y',        // DD/Mon/YY (e.g., 21-Jul-91)
                            'd-M-y',        // DD-Mon-YY (e.g., 21-Jul-91)
                            'd/M/Y',        // DD/Mon/YYYY (e.g., 21-Jul-1991)
                            'd-M-Y',        // DD-Mon-YYYY (e.g., 21-Jul-1991)
                            'd/m/y',        // DD/MM/YY
                            'd-m-y',        // DD-MM-YY
                            'm/d/y',        // MM/DD/YY
                            'm-d-y'         // MM-DD-YY
                        ];
                        
                        $parsed_date = false;
                        foreach ($date_formats as $format) {
                            $date = date_parse_from_format($format, $date_input);
                            if ($date['error_count'] == 0 && $date['year'] > 0) {
                                // Handle two-digit year (assume 00-29 is 20xx and 30-99 is 19xx based on common practice)
                                $year = $date['year'];
                                if ($year < 100) {
                                    if ($year <= 29) {
                                        $year += 2000;  // 00-29 becomes 2000-2029
                                    } else {
                                        $year += 1900;  // 30-99 becomes 1930-1999
                                    }
                                }
                                
                                $row['SK_KARY_TETAP_TANGGAL'] = sprintf('%04d-%02d-%02d', $year, $date['month'], $date['day']);
                                $parsed_date = true;
                                break;
                            }
                        }
                        
                        // If we couldn't parse the date, keep the original value but this shouldn't happen since we validated it
                        if (!$parsed_date) {
                            $row['SK_KARY_TETAP_TANGGAL'] = date('Y-m-d'); // fallback to current date
                        }
                    } finally {
                        // Restore original error reporting level
                        error_reporting($previousErrorReporting);
                    }
                }
                
                $validation_results['valid_records'][] = $row;
                $validation_results['total_valid']++;
            } else {
                $validation_results['invalid_records'][] = [
                    'NIK' => isset($row['NIK']) ? $row['NIK'] : '',
                    'NAMA' => isset($row['NAMA']) ? $row['NAMA'] : '', // Might not be in the template but for consistency
                    'errors' => $errors
                ];
                $validation_results['total_invalid']++;
            }
        }

        $data['validation_results'] = $validation_results;
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/status_update_import_validation');
        $this->load->view('layout/a_footer');
    }

    public function do_import()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $this->load->model('M_hris');
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $raw_data = $this->session->userdata('status_update_data');
        if (!$raw_data) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diproses. Silakan upload file terlebih dahulu.');
            redirect('Status_update_import');
            return;
        }

        // Perform validation first to get only valid records
        $valid_records = [];
        
        foreach ($raw_data as $row) {
            $errors = [];
            
            // Validate NIK
            if (!isset($row['NIK']) || empty(trim($row['NIK']))) {
                continue; // Skip invalid rows
            }
            
            // Validate STATUS_KARYAWAN
            if (!isset($row['STATUS_KARYAWAN']) || empty(trim($row['STATUS_KARYAWAN']))) {
                continue; // Skip invalid rows
            }
            
            // Validate SK_KARY_TETAP_NOMOR
            if (!isset($row['SK_KARY_TETAP_NOMOR']) || empty(trim($row['SK_KARY_TETAP_NOMOR']))) {
                continue; // Skip invalid rows
            }
            
            // Validate SK_KARY_TETAP_TANGGAL
            if (!isset($row['SK_KARY_TETAP_TANGGAL']) || empty(trim($row['SK_KARY_TETAP_TANGGAL']))) {
                continue; // Skip invalid rows
            }
            
            // Check if employee exists in database
            $this->db->select('nik');
            $this->db->from('karyawan');
            $this->db->where('nik', $row['NIK']);
            $exists = $this->db->get()->num_rows() > 0;
            
            if (!$exists) {
                continue; // Skip if employee doesn't exist
            }
            
            // Convert date to standard format if needed
            if (isset($row['SK_KARY_TETAP_TANGGAL'])) {
                $date_input = trim($row['SK_KARY_TETAP_TANGGAL']);
                
                // Temporarily suppress deprecation warnings for PHP 8.2+ compatibility
                $previousErrorReporting = error_reporting();
                error_reporting($previousErrorReporting & ~E_DEPRECATED);
                
                try {
                    // Try different date formats to parse the input
                    $date_formats = [
                        'Y-m-d',        // YYYY-MM-DD
                        'd/m/Y',        // DD/MM/YYYY
                        'd-m-Y',        // DD-MM-YYYY
                        'm/d/Y',        // MM/DD/YYYY
                        'm-d-Y',        // MM-DD-YYYY
                        'd/M/y',        // DD/Mon/YY (e.g., 21-Jul-91)
                        'd-M-y',        // DD-Mon-YY (e.g., 21-Jul-91)
                        'd/M/Y',        // DD/Mon/YYYY (e.g., 21-Jul-1991)
                        'd-M-Y',        // DD-Mon-YYYY (e.g., 21-Jul-1991)
                        'd/m/y',        // DD/MM/YY
                        'd-m-y',        // DD-MM-YY
                        'm/d/y',        // MM/DD/YY
                        'm-d-y'         // MM-DD-YY
                    ];
                    
                    $parsed_date = false;
                    foreach ($date_formats as $format) {
                        $date = date_parse_from_format($format, $date_input);
                        if ($date['error_count'] == 0 && $date['year'] > 0) {
                            // Handle two-digit year (assume 00-29 is 20xx and 30-99 is 19xx based on common practice)
                            $year = $date['year'];
                            if ($year < 100) {
                                if ($year <= 29) {
                                    $year += 2000;  // 00-29 becomes 2000-2029
                                } else {
                                    $year += 1900;  // 30-99 becomes 1930-1999
                                }
                            }
                            
                            $row['SK_KARY_TETAP_TANGGAL'] = sprintf('%04d-%02d-%02d', $year, $date['month'], $date['day']);
                            $parsed_date = true;
                            break;
                        }
                    }
                    
                    // If we couldn't parse the date, use current date as fallback
                    if (!$parsed_date) {
                        $row['SK_KARY_TETAP_TANGGAL'] = date('Y-m-d');
                    }
                } finally {
                    // Restore original error reporting level
                    error_reporting($previousErrorReporting);
                }
            }
            
            $valid_records[] = $row;
        }

        // Process the valid records
        $import_summary = [
            'total_records' => count($raw_data),
            'successful_imports' => 0,
            'inserted_records' => 0,
            'updated_records' => 0,
            'failed_imports' => 0,
            'errors' => [],
            'inserted_details' => [],
            'updated_details' => []
        ];

        foreach ($valid_records as $row) {
            $nik = $row['NIK'];
            $status_karyawan = $row['STATUS_KARYAWAN'];
            $sk_kary_tetap_nomor = $row['SK_KARY_TETAP_NOMOR'];
            $sk_kary_tetap_tanggal = $row['SK_KARY_TETAP_TANGGAL'];

            // Update the employee record
            $update_data = [
                'kontrak' => 'Tidak', // Set to "Tidak" to indicate permanent status
                'tgl_akhir_kontrak' => null, // Clear the contract end date
                'sts_aktif' => $status_karyawan, // Use existing column 'sts_aktif' instead of 'status_karyawan'
                'sk_kary_tetap_nomor' => $sk_kary_tetap_nomor,
                'sk_kary_tetap_tanggal' => $sk_kary_tetap_tanggal
            ];

            $this->db->where('NIK', $nik);
            $result = $this->db->update('karyawan', $update_data);

            if ($result) {
                $import_summary['updated_records']++;
                $import_summary['updated_details'][] = [
                    'NIK' => $nik,
                    'NAMA' => $this->get_employee_name($nik),
                    'STATUS' => 'UPDATED'
                ];
            } else {
                $import_summary['failed_imports']++;
                $import_summary['errors'][] = [
                    'NIK' => $nik,
                    'NAMA' => $this->get_employee_name($nik),
                    'error_message' => 'Gagal memperbarui data karyawan'
                ];
            }
        }

        // Calculate totals
        $import_summary['successful_imports'] = $import_summary['updated_records'];
        $import_summary['failed_imports'] = $import_summary['total_records'] - $import_summary['successful_imports'];

        $data['import_summary'] = $import_summary;
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/status_update_import_complete');
        $this->load->view('layout/a_footer');
    }

    private function get_employee_name($nik)
    {
        $this->db->select('nama_karyawan');
        $this->db->from('karyawan');
        $this->db->where('nik', $nik);
        $result = $this->db->get()->row();
        
        return $result ? $result->nama_karyawan : 'Nama Tidak Ditemukan';
    }
}
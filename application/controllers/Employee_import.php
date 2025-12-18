<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Employee_import extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_employee_import', 'M_hris'));
        $this->load->helper(array('form', 'url'));
    }

    /**
     * Display the upload form for employee import
     */
    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Clear any existing session data to ensure clean state
        $this->session->unset_userdata('employee_import_data');
        $this->session->unset_userdata('employee_import_file');
        $this->session->unset_userdata('employee_import_results');

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/import_form');
        $this->load->view('layout/a_footer');
    }

    /**
     * Handle file upload and initial parsing
     */
    public function upload()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Clear any existing session data to prevent conflicts with previous imports
        $this->session->unset_userdata('employee_import_data');
        $this->session->unset_userdata('employee_import_file');
        $this->session->unset_userdata('employee_import_results');

        // Configuration for file upload
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = 10240; // 10MB
        $config['overwrite'] = TRUE;
        $config['file_name'] = 'employee_import_' . time();

        // Create uploads directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('employee_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Upload failed: ' . $error);
            redirect('Employee_import');
        } else {
            $upload_data = $this->upload->data();
            $file_path = $upload_data['full_path'];

            // Parse the Excel file
            $parse_result = $this->M_employee_import->parse_employee_excel($file_path);

            if (!$parse_result['success']) {
                $this->session->set_flashdata('error', $parse_result['message']);
                unlink($file_path); // Delete uploaded file
                redirect('Employee_import');
            }

            // Store parsed data in session for preview
            $this->session->set_userdata('employee_import_data', $parse_result['data']);
            $this->session->set_userdata('employee_import_file', $file_path);

            // Redirect to preview page
            redirect('Employee_import/preview');
        }
    }

    /**
     * Show preview of data to be imported
     */
    public function preview()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $employee_data = $this->session->userdata('employee_import_data');
        
        if (empty($employee_data)) {
            $this->session->set_flashdata('error', 'No data to preview. Please upload a file first.');
            redirect('Employee_import');
            return;
        }

        // Get first 10 rows for preview (as per user preference)
        $preview_data = array_slice($employee_data, 0, 10);
        $total_rows = count($employee_data);

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['preview_data'] = $preview_data;
        $data['total_rows'] = $total_rows;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/import_preview', $data);
        $this->load->view('layout/a_footer');
    }

    /**
     * Process data (without validation) and show results
     */
    public function process_data()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $employee_data = $this->session->userdata('employee_import_data');
        
        if (empty($employee_data)) {
            $this->session->set_flashdata('error', 'No data to process. Please upload a file first.');
            redirect('Employee_import');
            return;
        }

        // Process the data (without validation)
        $processing_results = $this->M_employee_import->process_employee_data($employee_data);

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['processing_results'] = $processing_results;

        // Show loading view instead of directly importing
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/import_loading');
        $this->load->view('layout/a_footer');
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
        $import_results = $this->session->userdata('employee_import_results');
        
        if (empty($import_results)) {
            $employee_data = $this->session->userdata('employee_import_data');
            
            if (empty($employee_data)) {
                $this->session->set_flashdata('error', 'No data to import. Please upload a file first.');
                redirect('Employee_import');
                return;
            }

            // Perform the import (without validation)
            $import_results = $this->M_employee_import->import_employee_data($employee_data);
            
            // Store import results in session for pagination
            $this->session->set_userdata('employee_import_results', $import_results);
            
            // Clean up original session data
            $this->session->unset_userdata('employee_import_data');
            $this->session->unset_userdata('employee_import_file');
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['import_summary'] = $import_results;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/import_complete', $data);
        $this->load->view('layout/a_footer');
    }
    
    /**
     * Handle pagination for import results
     */
    public function paginate_results()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Get import results from session
        $import_results = $this->session->userdata('employee_import_results');
        
        if (empty($import_results)) {
            $this->session->set_flashdata('error', 'No import results found.');
            redirect('Employee_import');
            return;
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        $data['import_summary'] = $import_results;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('employee/import_complete', $data);
        $this->load->view('layout/a_footer');
    }

    /**
     * AJAX endpoint for importing employee data with progress updates
     */
    public function ajax_import()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
            return;
        }

        $employee_data = $this->session->userdata('employee_import_data');
        
        if (empty($employee_data)) {
            echo json_encode(['status' => 'error', 'message' => 'No data to import']);
            return;
        }

        // Get the current batch index (for progress tracking)
        $batch_index = $this->input->post('batch_index') ? (int)$this->input->post('batch_index') : 0;
        $batch_size = 10; // Process 10 records per batch
        
        // Split data into batches
        $batches = array_chunk($employee_data, $batch_size);
        $total_batches = count($batches);
        
        // Check if we're done
        if ($batch_index >= $total_batches) {
            // Import complete, store results in session
            $import_results = $this->session->userdata('employee_import_results');
            
            // If we haven't processed any batches yet, do the full import
            if (empty($import_results)) {
                $import_results = $this->M_employee_import->import_employee_data($employee_data);
                $this->session->set_userdata('employee_import_results', $import_results);
            }
            
            $this->session->unset_userdata('employee_import_data');
            $this->session->unset_userdata('employee_import_file');
            
            echo json_encode([
                'status' => 'complete',
                'message' => 'Import completed successfully',
                'progress' => 100,
                'redirect_url' => base_url('Employee_import/do_import')
            ]);
            return;
        }
        
        // Process current batch
        $current_batch = $batches[$batch_index];
        $processed_count = ($batch_index + 1) * $batch_size;
        $total_count = count($employee_data);
        
        // Limit processed count to total
        if ($processed_count > $total_count) {
            $processed_count = $total_count;
        }
        
        // Calculate progress percentage
        $progress = ($processed_count / $total_count) * 100;
        
        // Send progress update
        echo json_encode([
            'status' => 'progress',
            'message' => "Processing records {$processed_count}/{$total_count}",
            'progress' => $progress,
            'next_batch' => $batch_index + 1,
            'total_batches' => $total_batches
        ]);
    }

    /**
     * AJAX endpoint for performing the actual import
     */
    public function do_import_ajax()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
            return;
        }

        $employee_data = $this->session->userdata('employee_import_data');
        
        if (empty($employee_data)) {
            echo json_encode(['status' => 'error', 'message' => 'No data to import']);
            return;
        }

        // Perform the import (without validation)
        $import_results = $this->M_employee_import->import_employee_data($employee_data);
        
        // Store import results in session for pagination
        $this->session->set_userdata('employee_import_results', $import_results);
        
        // Clean up original session data
        $this->session->unset_userdata('employee_import_data');
        $this->session->unset_userdata('employee_import_file');
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Import completed successfully'
        ]);
    }

    /**
     * Download template Excel file for employee import
     */
    public function download_template()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define column headers - corrected order to match the actual data structure
        $headers = [
            'NIK',
            'NAMA',
            'ALAMAT E-MAIL PRIBADI',
            'JABATAN',
            'BAGIAN',
            'SUB.BAGIAN',
            'DEPARTEMEN',
            'STATUS KARYAWAN',
            'TGL. MASUK',
            'TGL. KELUAR',
            'TGL.JEDA',
            'MASA KERJA',
            'SK. KARY TETAP',
            'BPJS   NO.KPJ',
            'NO. KARTU TRIMAS',
            'STATUS PERNIKAHAN',
            'TEMPAT LAHIR',
            'TGL LAHIR',
            'USIA',
            'ALAMAT KTP',
            'ALAMAT TINGGAL SEKARANG',
            'JENIS KELAMIN',
            'AGAMA',
            'PENDIDIKAN TERAKHIR',
            'NO. TELEPON',
            'NO. KK',
            'NO. KTP',
            'GOL DARAH',
            'NAMA ORANG TUA',
            'NAMA SUAMI / ISTRI',
            'JUMLAH ANAK',
            'NAMA ANAK',
            'KONTRAK AKHIR',
            'NO.REKENING',
            'TIPE PTKP',
            'ALASAN KELUAR',
            'KETERANGAN',
            'LEVEL',
            'DL/IDL'
        ];

        // Write headers to the first row
        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header);
            $column++;
        }

        // Add sample data row to demonstrate proper format
        $sampleData = [
            'E3-2000',                           // NIK
            'AJI RAHMAT',                        // NAMA
            'aji.rahmat@example.com',            // ALAMAT E-MAIL PRIBADI
            'STAFF',                             // JABATAN
            'PRODUKSI',                          // BAGIAN
            '',                                  // SUB.BAGIAN
            '',                                  // DEPARTEMEN
            'TETAP',                             // STATUS KARYAWAN
            '01-Jan-20',                         // TGL. MASUK
            '',                                  // TGL. KELUAR
            '',                                  // TGL.JEDA
            '113.7',                             // MASA KERJA
            'No : 746/SK/TSGI/06/12',            // SK. KARY TETAP
            '1234567890',                        // BPJS   NO.KPJ
            '0987654321',                        // NO. KARTU TRIMAS
            'KAWIN',                             // STATUS PERNIKAHAN
            'JAKARTA',                           // TEMPAT LAHIR
            '01-Sep-67',                         // TGL LAHIR
            '30',                                // USIA
            'Jl. Merdeka No. 1',                 // ALAMAT KTP
            'Jl. Merdeka No. 1',                 // ALAMAT TINGGAL SEKARANG
            'L',                                 // JENIS KELAMIN
            'ISLAM',                             // AGAMA
            'S1',                                // PENDIDIKAN TERAKHIR
            '08123456789',                       // NO. TELEPON
            '1234567890123456',                  // NO. KK
            '1234567890123456',                  // NO. KTP
            'O',                                 // GOL DARAH
            'RAHMAT',                            // NAMA ORANG TUA
            'SITI',                              // NAMA SUAMI / ISTRI
            '2',                                 // JUMLAH ANAK
            'ANI, BUDI',                         // NAMA ANAK
            '31-Dec-23',                         // KONTRAK AKHIR
            '1234567890',                        // NO.REKENING
            'TK',                                // TIPE PTKP
            'PINDAH KE JAKARTA',                 // ALASAN KELUAR
            '',                                  // KETERANGAN
            'STAFF',                             // LEVEL
            'IDL'                                // DL/IDL
        ];

        // Write sample data to the second row
        $column = 1;
        foreach ($sampleData as $data) {
            $sheet->setCellValueByColumnAndRow($column, 2, $data);
            $column++;
        }

        // Set column widths
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // Freeze the header row
        $sheet->freezePane('A2');

        // Create Excel file
        $filename = 'employee_import_template.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class M_employee_import extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_hris');
    }

    /**
     * Parse Excel file for employee data without validation
     * 
     * @param string $file_path Path to uploaded Excel file
     * @return array Array containing parsed data
     */
    public function parse_employee_excel($file_path)
    {
        $result = [
            'success' => false,
            'message' => '',
            'data' => [],
            'total_rows' => 0,
            'errors' => []
        ];

        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get highest row and column
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            
            // Define expected column mappings (based on user's sample data)
            $expected_columns = [
                'NIK' => 1,
                'NAMA' => 2,
                'ALAMAT E-MAIL PRIBADI' => 3,
                'JABATAN' => 4,
                'BAGIAN' => 5,
                'SUB.BAGIAN' => 6,
                'DEPARTEMEN' => 7,
                'STATUS KARYAWAN' => 8,
                'TGL. MASUK' => 9,
                'TGL. KELUAR' => 10,
                'TGL.JEDA' => 11,
                'SEJAK AWAL' => 12,
                'NOMOR SK' => 13,
                'TGL.DIANGKAT' => 14,
                'BPJS NO.KPJ' => 15,
                'NO. KARTU TRIMAS' => 16,
                'STATUS PERNIKAHAN' => 17,
                'TEMPAT LAHIR' => 18,
                'TGL LAHIR' => 19,
                'BULAN LAHIR' => 20,
                'USIA' => 21,
                'ALAMAT KTP' => 22,
                'ALAMAT TINGGAL SEKARANG' => 23,
                'JENIS KELAMIN' => 24,
                'AGAMA' => 25,
                'PENDIDIKAN TERAKHIR' => 26,
                'NO. TELEPON' => 27,
                'NO. KK' => 28,
                'NO. KTP' => 29,
                'NAMA ORANG TUA' => 30,
                'NAMA SUAMI / ISTRI' => 31,
                'JUMLAH ANAK' => 32,
                'NAMA ANAK' => 33
            ];
            
            // Read header row to verify column positions
            $header_row = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $header_row[$col] = trim($cellValue);
            }
            
            // Match headers with expected columns
            foreach ($expected_columns as $columnName => $expectedPosition) {
                $found = false;
                foreach ($header_row as $actualPosition => $actualColumnName) {
                    if (strtoupper(trim($actualColumnName)) === strtoupper($columnName)) {
                        $expected_columns[$columnName] = $actualPosition;
                        $found = true;
                        break;
                    }
                }
                // If not found, we'll just leave it as is and handle missing data as empty
            }
            
            // Parse data rows
            $parsed_data = [];
            
            // Process rows (starting from row 2, skipping header)
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                $hasData = false;
                
                // Extract data for each expected column
                foreach ($expected_columns as $columnName => $columnIndex) {
                    $cellValue = $worksheet->getCellByColumnAndRow($columnIndex, $row)->getValue();
                    // Handle date cells properly
                    if ($worksheet->getCellByColumnAndRow($columnIndex, $row)->getDataType() == 'd') {
                        $cellValue = $worksheet->getCellByColumnAndRow($columnIndex, $row)->getFormattedValue();
                    }
                    
                    $rowData[strtoupper(str_replace([' ', '.', '/'], '_', $columnName))] = $cellValue;
                    
                    // Check if any cell in this row has data
                    if (!empty($cellValue)) {
                        $hasData = true;
                    }
                }
                
                // Only add rows with data
                if ($hasData) {
                    $parsed_data[] = $rowData;
                }
            }
            
            $result['success'] = true;
            $result['message'] = 'Successfully parsed ' . count($parsed_data) . ' employee records';
            $result['data'] = $parsed_data;
            $result['total_rows'] = count($parsed_data);
            
        } catch (Exception $e) {
            $result['message'] = 'Error parsing Excel file: ' . $e->getMessage();
        }
        
        return $result;
    }

    /**
     * Process employee data without validation (accept as-is)
     * 
     * @param array $employee_data Array of employee data
     * @return array Processing results
     */
    public function process_employee_data($employee_data)
    {
        $processing_results = [
            'processed_records' => $employee_data,
            'total_records' => count($employee_data)
        ];
        
        return $processing_results;
    }

    /**
     * Import employee data into database (no validation)
     * 
     * @param array $employee_data Employee data
     * @param int $batch_size Number of records to process in each batch
     * @return array Import results
     */
    public function import_employee_data($employee_data, $batch_size = 100)
    {
        $import_results = [
            'success' => true,
            'message' => '',
            'total_records' => 0,
            'successful_imports' => 0,
            'failed_imports' => 0,
            'errors' => []
        ];
        
        $total_records = count($employee_data);
        $import_results['total_records'] = $total_records;
        
        // Process in batches to avoid memory issues
        $batches = array_chunk($employee_data, $batch_size);
        
        foreach ($batches as $batch_index => $batch) {
            foreach ($batch as $employee) {
                try {
                    // Map Excel data to database fields
                    $employee_data = $this->map_employee_data($employee);
                    
                    // Insert into karyawan table
                    $this->M_hris->karyawan_pinsert($employee_data);
                    $import_results['successful_imports']++;
                    
                } catch (Exception $e) {
                    $import_results['failed_imports']++;
                    $import_results['errors'][] = [
                        'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                        'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                        'error_message' => $e->getMessage()
                    ];
                }
            }
            
            // Clear memory
            unset($batch);
        }
        
        $import_results['message'] = "Import completed. Success: {$import_results['successful_imports']}, Failed: {$import_results['failed_imports']}";
        
        if ($import_results['failed_imports'] > 0) {
            $import_results['success'] = false;
        }
        
        return $import_results;
    }

    /**
     * Map Excel data fields to database fields
     * 
     * @param array $employee Excel data
     * @return array Mapped database data
     */
    private function map_employee_data($employee)
    {
        // Default values for required fields
        $mapped_data = [
            'crt_by' => 1, // Default creator ID
            'crt_date' => date('Y-m-d H:i:s'),
            'sts_aktif' => 'Aktif',
            'spm' => 'Tidak',
            'cci' => 'Tidak',
            'tc' => '0'
        ];
        
        // Map fields from Excel to database
        if (isset($employee['NIK'])) {
            $mapped_data['nik'] = $employee['NIK'];
        }
        
        if (isset($employee['NAMA'])) {
            $mapped_data['nama_karyawan'] = $employee['NAMA'];
        }
        
        if (isset($employee['ALAMAT_E_MAIL_PRIBADI'])) {
            $mapped_data['email_pribadi'] = $employee['ALAMAT_E_MAIL_PRIBADI'];
        }
        
        if (isset($employee['TEMPAT_LAHIR'])) {
            $mapped_data['tmp_lahir'] = $employee['TEMPAT_LAHIR'];
        }
        
        if (isset($employee['TGL_LAHIR'])) {
            $mapped_data['tgl_lahir'] = $this->format_date($employee['TGL_LAHIR']);
        }
        
        if (isset($employee['JENIS_KELAMIN'])) {
            $mapped_data['jenkel'] = $employee['JENIS_KELAMIN'];
        }
        
        if (isset($employee['AGAMA'])) {
            $mapped_data['agama'] = $employee['AGAMA'];
        }
        
        if (isset($employee['PENDIDIKAN_TERAKHIR'])) {
            $mapped_data['pendidikan'] = $employee['PENDIDIKAN_TERAKHIR'];
        }
        
        if (isset($employee['NO_TELEPON'])) {
            $mapped_data['telp1'] = $employee['NO_TELEPON'];
        }
        
        if (isset($employee['NO_KTP'])) {
            $mapped_data['no_ktp'] = $employee['NO_KTP'];
        }
        
        if (isset($employee['ALAMAT_KTP'])) {
            $mapped_data['alamat_ktp'] = $employee['ALAMAT_KTP'];
        }
        
        if (isset($employee['ALAMAT_TINGGAL_SEKARANG'])) {
            $mapped_data['alamat_skrg'] = $employee['ALAMAT_TINGGAL_SEKARANG'];
        }
        
        if (isset($employee['STATUS_PERNIKAHAN'])) {
            $mapped_data['sts_nikah'] = $employee['STATUS_PERNIKAHAN'];
        }
        
        if (isset($employee['TGL_MASUK'])) {
            $mapped_data['tgl_m_kerja'] = $this->format_date($employee['TGL_MASUK']);
        }
        
        // Set default values for fields not in Excel
        $mapped_data['sts_aktif'] = 'Aktif'; // Default to active
        
        return $mapped_data;
    }

    /**
     * Format date from various formats to MySQL date format
     * 
     * @param string $date_string Date string from Excel
     * @return string Formatted date YYYY-MM-DD
     */
    private function format_date($date_string)
    {
        if (empty($date_string)) {
            return null;
        }
        
        // Try to parse the date
        $timestamp = strtotime($date_string);
        
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
    }
}
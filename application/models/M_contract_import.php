<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class M_contract_import extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_hris');
    }

    /**
     * Parse Excel file containing contract data
     * @param string $file_path Path to the uploaded Excel file
     * @return array Result with success status and data
     */
    public function parse_contract_excel($file_path)
    {
        try {
            $inputFileType = IOFactory::identify($file_path);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file_path);

            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Check header structure
            $nik_header = trim(strtoupper($worksheet->getCell('A3')->getValue() ?? ''));
            $status_header = trim(strtoupper($worksheet->getCell('B3')->getValue() ?? ''));
            $kontrak_header = trim(strtoupper($worksheet->getCell('C3')->getValue() ?? ''));
            
            if ($nik_header !== 'NIK' || $status_header !== 'STATUS KARYAWAN' || $kontrak_header !== 'KONTRAK') {
                return [
                    'success' => false,
                    'message' => 'Invalid header format. Expected: A3=NIK, B3=STATUS KARYAWAN, C3=KONTRAK'
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
                
                // Get STATUS KARYAWAN (column B)
                $status_cell = $worksheet->getCell('B' . $row);
                $status_value = trim((string)$status_cell->getValue());
                
                // Skip rows that contain instruction text
                if (stripos($nik_value, 'instruksi') !== false || 
                    stripos($nik_value, 'kolom') !== false ||
                    stripos($nik_value, 'wajib') !== false ||
                    stripos($nik_value, 'isi tanggal') !== false ||
                    stripos($nik_value, 'pasangan awal') !== false ||
                    preg_match('/^\d+\./', trim($nik_value)) || // Matches lines starting with number and period like "1.", "2.", "3. Hanya karyawan..."
                    preg_match('/^\d+$/', trim($nik_value)) || // Matches pure numbers like "2", "3"
                    empty($nik_value)) {
                    continue;
                }
                
                if ($nik_value !== '') {
                    $hasData = true;
                    $rowData['NIK'] = $nik_value;
                    $rowData['STATUS_KARYAWAN'] = $status_value;
                }

                // Process contract pairs starting from column C (since B is now STATUS KARYAWAN)
                $contract_index = 1;
                $col_index = 3; // Start from column C
                
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
            log_message('error', 'Contract import parse error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error parsing Excel file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process contract data for validation and preparation
     */
    public function process_contract_data($contract_data)
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
                    'message' => 'NIK is required',
                    'NIK' => $contract['NIK'] ?? '',
                    'NAMA' => $contract['NIK'] ?? ''
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
                    'message' => 'Employee with NIK ' . $contract['NIK'] . ' does not exist',
                    'NIK' => $contract['NIK'],
                    'NAMA' => $contract['NIK'] // Nama karyawan tidak bisa diambil karena karyawan tidak ditemukan
                ];
                $results['failed_imports']++;
                continue;
            }

            // Validate employee contract status - only employees with "Ya" in kontrak field can have contracts
            $employee_status = isset($contract['STATUS_KARYAWAN']) ? trim(strtoupper($contract['STATUS_KARYAWAN'])) : '';
            $actual_contract_status = trim(strtoupper($employee->kontrak)); // Use 'kontrak' field instead of 'sts_aktif'
            
            // Map the database kontrak value to meaningful status
            $mapped_actual_status = $actual_contract_status === 'YA' ? 'KONTRAK' : 'TETAP';
            
            // If status is provided in import file, validate it matches database
            if (!empty($employee_status)) {
                // Determine expected database value based on import file
                $expected_db_value = '';
                if ($employee_status === 'KONTRAK' || $employee_status === 'YA') {
                    $expected_db_value = 'YA';
                } elseif ($employee_status === 'TETAP' || $employee_status === 'TIDAK') {
                    $expected_db_value = 'TIDAK';
                }
                
                if ($expected_db_value !== '' && $actual_contract_status !== $expected_db_value) {
                    $mapped_expected_status = $expected_db_value === 'YA' ? 'KONTRAK' : 'TETAP';
                    $mapped_actual_status_display = $actual_contract_status === 'YA' ? 'KONTRAK' : 'TETAP';
                    
                    $results['errors'][] = [
                        'row' => $row_number,
                        'field' => 'STATUS_KARYAWAN',
                        'message' => 'Status mismatch: Import file shows "' . $employee_status . '" but database shows "' . $mapped_actual_status_display . '" for NIK ' . $contract['NIK'],
                        'NIK' => $contract['NIK'],
                        'NAMA' => $employee->nama_karyawan
                    ];
                    $results['failed_imports']++;
                    continue;
                }
            }
            
            // Check if employee status allows contract operations
            if ($actual_contract_status === 'TIDAK') { // If kontrak is 'TIDAK', employee cannot have contracts
                $results['errors'][] = [
                    'row' => $row_number,
                    'field' => 'STATUS_KARYAWAN',
                    'message' => 'Cannot create/update contract for employee without contract permission (NIK: ' . $contract['NIK'] . '). Only employees with contract permission ("Ya") can have contracts.',
                    'NIK' => $contract['NIK'],
                    'NAMA' => $employee->nama_karyawan
                ];
                $results['failed_imports']++;
                continue;
            }
            
            // If status is not TIDAK, it means kontrak is 'YA', which is valid for contracts
            if ($actual_contract_status !== 'YA') {
                $results['warnings'][] = [
                    'row' => $row_number,
                    'message' => 'Employee contract permission is "' . $actual_contract_status . '" (NIK: ' . $contract['NIK'] . '). This may not be a standard contract status.',
                    'NIK' => $contract['NIK'],
                    'NAMA' => $employee->nama_karyawan
                ];
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
                        $awal_date = DateTime::createFromFormat('d-M-y', $awal_value) ?: 
                                   DateTime::createFromFormat('d-M-Y', $awal_value) ?: 
                                   DateTime::createFromFormat('Y-m-d', $awal_value) ?: 
                                   DateTime::createFromFormat('d/m/Y', $awal_value) ?: 
                                   DateTime::createFromFormat('d-m-Y', $awal_value);
                        if (!$awal_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $awal_key,
                                'message' => 'Invalid date format for ' . $awal_key . '. Expected DD-MMM-YY, DD-MMM-YYYY, YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY',
                                'NIK' => $contract['NIK'],
                                'NAMA' => $employee->nama_karyawan
                            ];
                            continue;
                        }
                    }

                    if ($akhir_value !== '') {
                        $akhir_date = DateTime::createFromFormat('d-M-y', $akhir_value) ?: 
                                    DateTime::createFromFormat('d-M-Y', $akhir_value) ?: 
                                    DateTime::createFromFormat('Y-m-d', $akhir_value) ?: 
                                    DateTime::createFromFormat('d/m/Y', $akhir_value) ?: 
                                    DateTime::createFromFormat('d-m-Y', $akhir_value);
                        if (!$akhir_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $akhir_key,
                                'message' => 'Invalid date format for ' . $akhir_key . '. Expected DD-MMM-YY, DD-MMM-YYYY, YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY',
                                'NIK' => $contract['NIK'],
                                'NAMA' => $employee->nama_karyawan
                            ];
                            continue;
                        }
                    }

                    if ($awal_value !== '' && $akhir_value !== '') {
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
                        
                        if ($awal_date && $akhir_date && $awal_date > $akhir_date) {
                            $results['errors'][] = [
                                'row' => $row_number,
                                'field' => $awal_key . '/' . $akhir_key,
                                'message' => 'Start date cannot be after end date',
                                'NIK' => $contract['NIK'],
                                'NAMA' => $employee->nama_karyawan
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
     * Perform the actual contract import - simplified approach like Employee_import
     */
    public function perform_import($contract_data)
    {
        $results = [
            'total_records' => count($contract_data),
            'inserted_records' => 0,
            'updated_records' => 0,
            'successful_imports' => 0,
            'failed_imports' => 0,
            'errors' => [],
            'inserted_details' => [],
            'updated_details' => []
        ];

        // Process each contract record individually (like Employee_import)
        foreach ($contract_data as $index => $contract) {
            $row_number = $index + 2; // +2 because data starts from row 2 (row 1 is header)
            
            log_message('debug', 'Processing contract for NIK: ' . $contract['NIK']);
            
            // Check if employee exists
            $employee = $this->db->get_where('karyawan', array('nik' => $contract['NIK']))->row();
            if (!$employee) {
                log_message('debug', 'Employee with NIK ' . $contract['NIK'] . ' does not exist in database');
                $results['errors'][] = [
                    'row' => $row_number,
                    'message' => 'Employee with NIK ' . $contract['NIK'] . ' does not exist',
                    'NIK' => $contract['NIK'],
                    'NAMA' => $contract['NIK'] // Nama karyawan tidak bisa diambil karena karyawan tidak ditemukan
                ];
                $results['failed_imports']++;
                continue;
            } else {
                log_message('debug', 'Employee found: ' . $employee->nama_karyawan . ' (ID: ' . $employee->recid_karyawan . ')');
                
                // Validate employee contract status - only employees with "Ya" in kontrak field can have contracts
                $employee_status = isset($contract['STATUS_KARYAWAN']) ? trim(strtoupper($contract['STATUS_KARYAWAN'])) : '';
                $actual_contract_status = trim(strtoupper($employee->kontrak)); // Use 'kontrak' field instead of 'sts_aktif'
                
                // Map the database kontrak value to meaningful status
                $mapped_actual_status = $actual_contract_status === 'YA' ? 'KONTRAK' : 'TETAP';
                
                // If status is provided in import file, validate it matches database
                if (!empty($employee_status)) {
                    // Determine expected database value based on import file
                    $expected_db_value = '';
                    if ($employee_status === 'KONTRAK' || $employee_status === 'YA') {
                        $expected_db_value = 'YA';
                    } elseif ($employee_status === 'TETAP' || $employee_status === 'TIDAK') {
                        $expected_db_value = 'TIDAK';
                    }
                    
                    if ($expected_db_value !== '' && $actual_contract_status !== $expected_db_value) {
                        $mapped_expected_status = $expected_db_value === 'YA' ? 'KONTRAK' : 'TETAP';
                        $mapped_actual_status_display = $actual_contract_status === 'YA' ? 'KONTRAK' : 'TETAP';
                        
                        log_message('debug', 'Status mismatch for NIK ' . $contract['NIK'] . ': Import file shows "' . $employee_status . '" but database shows "' . $mapped_actual_status_display . '"');
                        $results['errors'][] = [
                            'row' => $row_number,
                            'message' => 'Status mismatch: Import file shows "' . $employee_status . '" but database shows "' . $mapped_actual_status_display . '" for NIK ' . $contract['NIK'],
                            'NIK' => $contract['NIK'],
                            'NAMA' => $employee->nama_karyawan
                        ];
                        $results['failed_imports']++;
                        continue;
                    }
                }
                
                // Check if employee status allows contract operations
                if ($actual_contract_status === 'TIDAK') { // If kontrak is 'TIDAK', employee cannot have contracts
                    log_message('debug', 'Employee without contract permission cannot have contract: ' . $contract['NIK']);
                    $results['errors'][] = [
                        'row' => $row_number,
                        'message' => 'Cannot create contract for employee without contract permission (NIK: ' . $contract['NIK'] . '). Only employees with contract permission ("Ya") can have contracts.',
                        'NIK' => $contract['NIK'],
                        'NAMA' => $employee->nama_karyawan
                    ];
                    $results['failed_imports']++;
                    continue;
                }
                
                // If status is not TIDAK, it means kontrak is 'YA', which is valid for contracts
                if ($actual_contract_status !== 'YA') {
                    log_message('debug', 'Non-standard employee contract permission: ' . $actual_contract_status . ' for NIK: ' . $contract['NIK']);
                    $results['warnings'][] = [
                        'row' => $row_number,
                        'message' => 'Employee contract permission is "' . $actual_contract_status . '" (NIK: ' . $contract['NIK'] . '). This may not be a standard contract status.',
                        'NIK' => $contract['NIK'],
                        'NAMA' => $employee->nama_karyawan
                    ];
                }
            }

            // Process contract dates - simple approach like Employee_import
            $contracts_added = 0;
            for ($i = 1; $i <= 44; $i++) { // Assuming up to 44 contract pairs like in the template
                $awal_key = 'AWAL_' . $i;
                $akhir_key = 'AKHIR_' . $i;
                
                $awal_value = isset($contract[$awal_key]) ? trim($contract[$awal_key]) : '';
                $akhir_value = isset($contract[$akhir_key]) ? trim($contract[$akhir_key]) : '';

                if ($awal_value !== '' && $akhir_value !== '') {
                    log_message('debug', 'Processing dates for NIK ' . $contract['NIK'] . ': AWAL=' . $awal_value . ', AKHIR=' . $akhir_value);
                    
                    // Validate date format (simple approach)
                    $awal_date = $this->parse_date($awal_value);
                    $akhir_date = $this->parse_date($akhir_value);
                
                    if (!$awal_date || !$akhir_date) {
                        log_message('debug', 'Date parsing failed for NIK ' . $contract['NIK'] . ': AWAL=' . $awal_value . ', AKHIR=' . $akhir_value);
                        $results['errors'][] = [
                            'row' => $row_number,
                            'message' => 'Invalid date format in contract data for NIK: ' . $contract['NIK'] . ' (both AWAL and AKHIR dates required)',
                            'NIK' => $contract['NIK'],
                            'NAMA' => $employee->nama_karyawan
                        ];
                        $results['failed_imports']++;
                        continue;
                    }
                
                    // Format dates for database
                    $formatted_awal = $awal_date->format('Y-m-d');
                    $formatted_akhir = $akhir_date->format('Y-m-d');
                    
                    log_message('debug', 'Successfully parsed dates for NIK ' . $contract['NIK'] . ': ' . $formatted_awal . ' to ' . $formatted_akhir);

                    // Prepare data for insert (always insert new contract, regardless of existing contracts)
                    // Note: tgl_mulai and tgl_akhir are NOT NULL, so we must provide values
                    $contract_insert_data = [
                        'recid_karyawan' => $employee->recid_karyawan,
                        'status_kontrak' => 'aktif',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Add dates - both are required (NOT NULL)
                    $contract_insert_data['tgl_mulai'] = $formatted_awal ?: '1900-01-01';  // Default if null
                    $contract_insert_data['tgl_akhir'] = $formatted_akhir ?: '1900-01-01'; // Default if null

                    log_message('debug', 'Attempting to insert contract for employee ID: ' . $employee->recid_karyawan . ', dates: ' . $formatted_awal . ' to ' . $formatted_akhir);
                    
                    // Simple insert like Employee_import (always insert, don't check for duplicates)
                    if ($this->db->insert('karyawan_kontrak', $contract_insert_data)) {
                        log_message('debug', 'Successfully inserted contract for employee ID: ' . $employee->recid_karyawan);
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
                        log_message('error', 'Contract insert failed for NIK ' . $contract['NIK'] . ': ' . $db_error['message']);
                        $results['errors'][] = [
                            'row' => $row_number,
                            'message' => 'Failed to insert contract for NIK: ' . $contract['NIK'] . ' - ' . $db_error['message'],
                            'NIK' => $contract['NIK'],
                            'NAMA' => $employee->nama_karyawan
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
     * Parse date string to DateTime object
     * Simple date parsing like Employee_import
     */
    private function parse_date($date_string)
    {
        if (empty($date_string)) {
            return null;
        }
        
        // Try multiple date formats
        $formats = [
            'd-M-y',
            'd-M-Y',
            'Y-m-d',
            'd/m/Y',
            'd-m-Y',
            'd.m.Y'
        ];
        
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $date_string);
            if ($date) {
                // For 2-digit year formats like 'd-M-y', PHP should handle them correctly
                // But let's ensure we interpret years like '25' as '2025' rather than '1925'
                if ($format === 'd-M-y') {
                    $parsed_year = $date->format('Y');
                    $original_2digit = intval(substr($date_string, -2));
                    
                    // If the parsed year is too far in the past (like 1925 instead of 2025)
                    if ($parsed_year < 2000) {
                        // Adjust for years that should be in the future
                        $adjusted_year = 2000 + $original_2digit;
                        $date->setDate($adjusted_year, $date->format('n'), $date->format('j'));
                    }
                }
                return $date;
            }
        }
        
        return null;
    }
}
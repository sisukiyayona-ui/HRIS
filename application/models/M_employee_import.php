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
        $this->load->database();
    }
    
    /**
     * Convert Excel date serial number to formatted date string
     * 
     * @param float $excelDate Excel date serial number
     * @return string Formatted date string
     */
    private function convertExcelDate($excelDate)
    {
        // Handle special case: 0 should be treated as "00-Jan-00"
        if ($excelDate == 0) {
            return '00-Jan-00';
        }
        
        try {
            // Use PhpOffice\PhpSpreadsheet's built-in date conversion
            // Suppress deprecation warnings from PhpSpreadsheet library
            $previousErrorReporting = error_reporting();
            error_reporting($previousErrorReporting & ~E_DEPRECATED);
            $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate);
            // Restore error reporting
            error_reporting($previousErrorReporting);
            return $dateObj->format('d-M-y');
        } catch (Exception $e) {
            // If conversion fails, return the original value
            return $excelDate;
        }
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
            // Suppress deprecation warnings from PhpSpreadsheet library
            $previousErrorReporting = error_reporting();
            error_reporting($previousErrorReporting & ~E_DEPRECATED);
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();
            // Restore error reporting
            error_reporting($previousErrorReporting);
            
            // Get highest row and column
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            
            // Define expected column mappings (based on corrected template)
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
                'MASA KERJA' => 12,
                'SK. KARY TETAP' => 13,
                'BPJS   NO.KPJ' => 14,
                'NO. KARTU TRIMAS' => 15,
                'STATUS PERNIKAHAN' => 16,
                'TEMPAT LAHIR' => 17,
                'TGL LAHIR' => 18, // Full birth date
                'TGL LAHIR HARI' => 19, // Day number only
                'BULAN LAHIR' => 20, // Month name only
                'USIA' => 21,
                'ALAMAT KTP' => 22,
                'ALAMAT TINGGAL SEKARANG' => 23,
                'JENIS KELAMIN' => 24,
                'AGAMA' => 25,
                'PENDIDIKAN TERAKHIR' => 26,
                'NO. TELEPON' => 27,
                'NO. KK' => 28,
                'NO. KTP' => 29,
                'GOL DARAH' => 30,
                'NAMA ORANG TUA' => 31,
                'NAMA SUAMI / ISTRI' => 32,
                'JUMLAH ANAK' => 33,
                'NAMA ANAK' => 34,
                // Contract columns - based on corrected template
                'KONTRAK AKHIR' => 123,
                'NO.REKENING' => 124,
                'TIPE PTKP' => 125,
                'ALASAN KELUAR' => 126,
                'KETERANGAN' => 127,
                'LEVEL' => 128,
                'DL/IDL' => 129
            ];
            
            // Read header rows to verify column positions (handle duplicate/skipped headers)
            $header_rows = [];
            // Check first few rows for headers (in case of duplicate headers)
            $max_header_rows = min(10, $highestRow); // Check up to 10 rows for headers
            for ($row = 1; $row <= $max_header_rows; $row++) {
                $header_row = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $header_row[$col] = is_null($cellValue) ? '' : trim($cellValue);
                }
                $header_rows[$row] = $header_row;
            }
            
            // Handle multi-row KONTRAK headers (AI to DR columns)
            // According to requirements, rows 1-5 are headers
            // Row 1: KONTRAK repeated or spanning
            // Row 2: AWAL, AKHIR pairs
            $kontrak_columns = [];
            $kontrak_start_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('AI'); // Column 35
            $kontrak_end_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('DR');   // Column 122
            
            // Explicitly define the AWAL/AKHIR pairs based on the expected structure
            // Columns AI(35) and AJ(36) = Pair 1: AWAL, AKHIR
            // Columns AK(37) and AL(38) = Pair 2: AWAL, AKHIR
            // And so on...
            
            // Check if we have header rows to work with
            if (isset($header_rows[2])) {
                // Look for AWAL/AKHIR patterns in row 2 within the KONTRAK range
                // Process pairs of columns (even columns = AWAL, odd columns = AKHIR)
                for ($col = $kontrak_start_col; $col <= $kontrak_end_col; $col += 2) {
                    // Check the first column of the pair (should be AWAL)
                    if (isset($header_rows[2][$col])) {
                        $sub_header = strtoupper(trim($header_rows[2][$col]));
                        if (strpos($sub_header, 'AWAL') !== false || strpos($sub_header, 'MULAI') !== false) {
                            $kontrak_columns[$col] = 'AWAL';
                        }
                    }
                    
                    // Check the second column of the pair (should be AKHIR)
                    if (isset($header_rows[2][$col + 1])) {
                        $sub_header = strtoupper(trim($header_rows[2][$col + 1]));
                        if (strpos($sub_header, 'AKHIR') !== false || strpos($sub_header, 'SELESAI') !== false || strpos($sub_header, 'END') !== false) {
                            $kontrak_columns[$col + 1] = 'AKHIR';
                        }
                    }
                }
            }
            
            // Fallback: if no headers detected, assume standard pattern
            if (empty($kontrak_columns)) {
                // Assume standard pattern: even columns = AWAL, odd columns = AKHIR
                for ($col = $kontrak_start_col; $col <= $kontrak_end_col; $col += 2) {
                    $kontrak_columns[$col] = 'AWAL';       // Even columns
                    $kontrak_columns[$col + 1] = 'AKHIR';  // Odd columns
                }
            }
            
            // Temporary debug output - REMOVE AFTER TESTING
            // This will help us see if KONTRAK columns are being detected
            if (!empty($kontrak_columns)) {
                //echo "KONTRAK columns detected: ";
                //print_r($kontrak_columns);
            } else {
                //echo "No KONTRAK columns detected";
            }
            
            // Match headers with expected columns - check all header rows
            foreach ($expected_columns as $columnName => $expectedPosition) {
                $found = false;
                // Check each header row
                foreach ($header_rows as $rowIndex => $header_row) {
                    foreach ($header_row as $actualPosition => $actualColumnName) {
                        $trimmedActualColumnName = is_null($actualColumnName) ? '' : trim($actualColumnName);
                        // Check for exact match first
                        if (strtoupper($trimmedActualColumnName) === strtoupper($columnName)) {
                            $expected_columns[$columnName] = $actualPosition;
                            $found = true;
                            break 2; // Break out of both loops
                        }
                        // Check for partial match (in case of extra spaces or formatting)
                        if (!$found && stripos($trimmedActualColumnName, $columnName) !== false) {
                            $expected_columns[$columnName] = $actualPosition;
                            $found = true;
                            // Continue searching for exact match
                        }
                        // Special handling for BPJS columns (which might have extra spaces)
                        if (!$found && (
                            (stripos($columnName, 'BPJS') !== false && stripos($trimmedActualColumnName, 'BPJS') !== false) ||
                            (stripos($columnName, 'NO.KPJ') !== false && stripos($trimmedActualColumnName, 'NO.KPJ') !== false) ||
                            (stripos($columnName, 'KARTU TRIMAS') !== false && stripos($trimmedActualColumnName, 'KARTU TRIMAS') !== false)
                        )) {
                            $expected_columns[$columnName] = $actualPosition;
                            $found = true;
                        }
                    }
                }
                // If not found, we'll just leave it as is and handle missing data as empty
            }
            
            // Determine the first data row (skip header rows)
            $first_data_row = $this->findFirstDataRow($worksheet, $highestRow, $highestColumnIndex);
            
            // Parse data rows
            $parsed_data = [];
            
            // Process rows (starting from the determined first data row)
            for ($row = $first_data_row; $row <= $highestRow; $row++) {
                $rowData = [];
                $hasData = false;
                
                // Extract KONTRAK data from multi-row headers (AI to DR columns) FIRST
                // This ensures KONTRAK data can contribute to $hasData flag
                $kontrak_data = [];
                foreach ($kontrak_columns as $col => $type) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $cellValue = $cell->getValue();
                    
                    // Format date values properly
                    if (!empty($cellValue)) {
                        try {
                            // Suppress deprecation warnings from PhpSpreadsheet library
                            $previousErrorReporting = error_reporting();
                            error_reporting($previousErrorReporting & ~E_DEPRECATED);
                            
                            // Check if this is a date cell
                            if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                                // For date cells, get the formatted value
                                $formattedValue = $cell->getFormattedValue();
                                
                                // If formatted value is different from raw value, use it
                                if ($formattedValue != $cellValue) {
                                    $cellValue = $formattedValue;
                                } else {
                                    // If it's a numeric value, try to convert Excel date serial number to date
                                    if (is_numeric($cellValue) && $cellValue > 0 && $cellValue < 100000) {
                                        $cellValue = $this->convertExcelDate($cellValue);
                                    } else {
                                        // Try to get the formatted value with date format
                                        $cellValue = $cell->getFormattedValue();
                                    }
                                }
                            } else {
                                // For non-date cells, just get the formatted value
                                $cellValue = $cell->getFormattedValue();
                            }
                            
                            // Restore error reporting
                            error_reporting($previousErrorReporting);
                        } catch (Exception $e) {
                            // If formatting fails, keep the original value
                        }
                        
                        // Check if any cell in this row has data (including KONTRAK data)
                        if (!empty($cellValue)) {
                            $hasData = true;
                        }
                    }
                    
                    // Store the KONTRAK data with a special key
                    $kontrak_data_key = 'KONTRAK_' . $type . '_' . $col;
                    $rowData[$kontrak_data_key] = is_null($cellValue) ? '' : $cellValue;
                }
                
                // Extract data for each expected column
                foreach ($expected_columns as $columnName => $columnIndex) {
                    $cell = $worksheet->getCellByColumnAndRow($columnIndex, $row);
                    
                    // Special handling for BULAN LAHIR - get the text value from column T
                    if (strtoupper($columnName) === 'BULAN LAHIR') {
                        // For BULAN LAHIR, we want the exact text as it appears in Excel column T
                        // Try to get the formatted value to get the text representation
                        // Suppress all errors including deprecation warnings
                        $previousErrorReporting = error_reporting();
                        error_reporting(0); // Turn off all error reporting temporarily
                        try {
                            $cellValue = $cell->getFormattedValue();
                        } catch (Exception $e) {
                            // If formatted value fails, fall back to raw value
                            $cellValue = $cell->getValue();
                        }
                        // Restore error reporting
                        error_reporting($previousErrorReporting);
                        
                        // Ensure it's treated as string
                        if (!is_null($cellValue)) {
                            $cellValue = (string)$cellValue;
                        } else {
                            $cellValue = '';
                        }
                    } else {
                        // For all other columns, use normal processing
                        $cellValue = $cell->getValue();
                    }
                    
                    // Handle date cells properly
                    // But exclude BULAN LAHIR as it should be treated as text
                    if (in_array(strtoupper($columnName), ['TGL. MASUK', 'TGL. KELUAR', 'TGL.JEDA', 'TGL LAHIR', 'TGL.DIANGKAT', 'SEJAK AWAL', 'KONTRAK', 'KONTRAK AKHIR']) && strtoupper($columnName) !== 'BULAN LAHIR') {
                        // For date columns, try to format them properly
                        try {
                            // Special handling for TGL LAHIR - preserve full date
                            if (strtoupper($columnName) === 'TGL LAHIR') {
                                // For birth date, preserve the full date
                                if (is_numeric($cellValue)) {
                                    if ($cellValue == 0) {
                                        $cellValue = '';
                                    } else {
                                        // Try to convert to date and preserve full date
                                        // Suppress deprecation warnings from PhpSpreadsheet library
                                        $previousErrorReporting = error_reporting();
                                        error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                        $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                                        // Restore error reporting
                                        error_reporting($previousErrorReporting);
                                        $cellValue = $dateObj->format('d-M-y'); // Preserve full date format
                                    }
                                } else {
                                    // For non-numeric values, preserve the date string as is
                                    if (empty($cellValue) || $cellValue === '00-Jan-00') {
                                        $cellValue = '';
                                    }
                                    // Otherwise keep the original value
                                }
                                
                                // Handle two-digit year format (e.g., convert 67 to 1967)
                                if (!empty($cellValue) && preg_match('/^\d{1,2}-[A-Za-z]{3}-\d{2}$/', $cellValue)) {
                                    $date_parts = explode('-', $cellValue);
                                    if (count($date_parts) == 3) {
                                        $day = $date_parts[0];
                                        $month = $date_parts[1];
                                        $year = $date_parts[2];
                                        
                                        // Handle two-digit year (assume 00-29 is 20xx and 30-99 is 19xx)
                                        if (strlen($year) == 2) {
                                            $year_num = intval($year);
                                            if ($year_num >= 30) {
                                                $full_year = "19" . $year;
                                            } else {
                                                $full_year = "20" . $year;
                                            }
                                            $cellValue = $day . '-' . $month . '-' . $full_year;
                                        }
                                    }
                                }
                            }
                            // Special handling for TGL LAHIR HARI - only show day number
                            elseif (strtoupper($columnName) === 'TGL LAHIR HARI') {
                                // For birth day, extract just the day number
                                if (is_numeric($cellValue)) {
                                    if ($cellValue == 0) {
                                        $cellValue = '';
                                    } else {
                                        // Try to convert to date and extract day
                                        // Suppress deprecation warnings from PhpSpreadsheet library
                                        $previousErrorReporting = error_reporting();
                                        error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                        $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                                        // Restore error reporting
                                        error_reporting($previousErrorReporting);
                                        $cellValue = $dateObj->format('j'); // 'j' gives day without leading zeros
                                    }
                                } else {
                                    // For non-numeric values, try to extract day from date string
                                    if (!empty($cellValue) && $cellValue !== '00-Jan-00') {
                                        // Try to parse the date and extract day
                                        $timestamp = strtotime($cellValue);
                                        if ($timestamp !== false) {
                                            $cellValue = date('j', $timestamp);
                                        }
                                    } else {
                                        $cellValue = '';
                                    }
                                }
                            } else {
                                // Handle other date columns normally
                                // Suppress deprecation warnings from PhpSpreadsheet library
                                $previousErrorReporting = error_reporting();
                                error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                $formattedValue = $cell->getFormattedValue();
                                // Restore error reporting
                                error_reporting($previousErrorReporting);
                                
                                // If formatted value is different from raw value, use it
                                if ($formattedValue != $cellValue) {
                                    $cellValue = $formattedValue;
                                } else {
                                    // If it's a numeric value, try to convert Excel date serial number to date
                                    if (is_numeric($cellValue) && $cellValue > 0 && $cellValue < 100000) {
                                        // Suppress deprecation warnings from PhpSpreadsheet library
                                        $previousErrorReporting = error_reporting();
                                        error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                        $cellValue = $this->convertExcelDate($cellValue);
                                        // Restore error reporting
                                        error_reporting($previousErrorReporting);
                                    } else {
                                        // Try to get the formatted value with date format
                                        // Suppress deprecation warnings from PhpSpreadsheet library
                                        $previousErrorReporting = error_reporting();
                                        error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                        $cellValue = $cell->getFormattedValue();
                                        // Restore error reporting
                                        error_reporting($previousErrorReporting);
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            // If formatting fails, keep the original value
                        }
                    }
                    
                    // Handle formula cells for USIA column and other formula-based cells
                    // But exclude BULAN LAHIR column as it should preserve text values
                    if ($cell->isFormula() && strtoupper($columnName) !== 'BULAN LAHIR') {
                        try {
                            // Get the calculated value instead of the formula
                            $cellValue = $cell->getCalculatedValue();
                        } catch (Exception $e) {
                            // If calculation fails, try to extract numeric value from formula
                            if (is_string($cellValue) && preg_match('/[\d\.]+/', $cellValue, $matches)) {
                                $cellValue = $matches[0];
                            } else {
                                $cellValue = '';
                            }
                        }
                    } else if (is_string($cellValue) && strpos($cellValue, '=') === 0 && strtoupper($columnName) !== 'BULAN LAHIR') {
                        // If it's a string that starts with =, it's likely a formula that wasn't processed
                        // Try to extract numeric value
                        if (preg_match('/[\d\.]+/', $cellValue, $matches)) {
                            $cellValue = $matches[0];
                        } else {
                            $cellValue = '';
                        }
                    }
                    
                    // Normalize column name: replace spaces, dots, slashes, and hyphens with single underscore
                    $normalizedColumnName = preg_replace('/[\s\.\/\-]+/', '_', $columnName);
                    $rowData[strtoupper($normalizedColumnName)] = is_null($cellValue) ? '' : $cellValue;
                    
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
     * Find the first data row by analyzing the content of rows
     * 
     * @param Worksheet $worksheet The worksheet object
     * @param int $highestRow The highest row number
     * @param int $highestColumnIndex The highest column index
     * @return int The first data row number
     */
    private function findFirstDataRow($worksheet, $highestRow, $highestColumnIndex) {
        // According to requirements, rows 1-5 are headers, so start from row 6
        $first_data_row = 6;
        
        // Ensure first_data_row doesn't exceed highestRow
        if ($first_data_row > $highestRow) {
            $first_data_row = $highestRow;
        }
        
        return $first_data_row;
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
            'updated_records' => 0,
            'inserted_records' => 0,
            'inserted_details' => [], // Track inserted records
            'updated_details' => [],  // Track updated records
            'errors' => []            // Track failed records
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
                    
                    // Check if employee already exists based on NIK
                    $existing_employee = null;
                    if (isset($employee['NIK']) && !empty($employee['NIK'])) {
                        $existing_employees = $this->M_hris->karyawan_by_nik($employee['NIK']);
                        if ($existing_employees && $existing_employees->num_rows() > 0) {
                            $existing_employee = $existing_employees->row(); // Get the first match
                        }
                    }
                    
                    if ($existing_employee && isset($existing_employee->recid_karyawan)) {
                        // Update existing employee
                        $update_result = $this->M_hris->karyawan_update($employee_data, $existing_employee->recid_karyawan);
                        if ($update_result) {
                            $import_results['updated_records']++;
                            
                            // Track updated record details
                            $import_results['updated_details'][] = [
                                'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                                'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                                'recid_karyawan' => $existing_employee->recid_karyawan
                            ];
                        } else {
                            // If update failed, treat as error
                            $import_results['failed_imports']++;
                            $import_results['errors'][] = [
                                'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                                'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                                'error_message' => 'Failed to update existing employee'
                            ];
                            continue; // Skip to next record
                        }
                        
                        // Handle contract data for existing employee
                        $this->handle_contract_data($employee, $existing_employee->recid_karyawan);
                    } else {
                        // Insert new employee
                        $employee_id = $this->M_hris->karyawan_pinsert($employee_data);
                        if ($employee_id) {
                            $import_results['inserted_records']++;
                            
                            // Handle contract data for newly inserted employee
                            $this->handle_contract_data($employee, $employee_id);
                            
                            // Track inserted record details
                            $import_results['inserted_details'][] = [
                                'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                                'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                                'recid_karyawan' => $employee_id
                            ];
                        } else {
                            // If insert failed, treat as error
                            $import_results['failed_imports']++;
                            $import_results['errors'][] = [
                                'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                                'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                                'error_message' => 'Failed to insert new employee'
                            ];
                        }
                    }
                    
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
        
        $import_results['message'] = "Import completed. Success: {$import_results['successful_imports']}, Failed: {$import_results['failed_imports']}, Inserted: {$import_results['inserted_records']}, Updated: {$import_results['updated_records']}";
        
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
            'sts_aktif' => 'Aktif'
        ];
        
        // Map fields from Excel to database
        if (isset($employee['NIK'])) {
            $mapped_data['nik'] = $employee['NIK'];
        }
        
        if (isset($employee['NAMA'])) {
            $mapped_data['nama_karyawan'] = $employee['NAMA'];
        }
        
        if (isset($employee['ALAMAT_E_MAIL_PRIBADI'])) {
            $mapped_data['email'] = $employee['ALAMAT_E_MAIL_PRIBADI'];
        }
        
        if (isset($employee['TEMPAT_LAHIR'])) {
            $mapped_data['tmp_lahir'] = $employee['TEMPAT_LAHIR'];
        }
        
        // Handle TGL LAHIR (complete birth date)
        if (isset($employee['TGL_LAHIR'])) {
            $mapped_data['tgl_lahir'] = $this->format_date($employee['TGL_LAHIR']);
        }
            
        // Handle USIA field - calculate age if not provided or invalid
        if (isset($employee['USIA']) && is_numeric($employee['USIA']) && $employee['USIA'] > 0) {
            // Use provided age if it's a valid number
            $mapped_data['usia'] = $employee['USIA'];
        } else {
            // Calculate age based on birth date if available
            if (isset($employee['TGL_LAHIR']) && !empty($employee['TGL_LAHIR'])) {
                $calculated_age = $this->calculate_age($employee['TGL_LAHIR']);
                if ($calculated_age > 0) {
                    $mapped_data['usia'] = $calculated_age;
                }
            }
        }
        
        // Handle BULAN LAHIR field
        if (isset($employee['BULAN_LAHIR'])) {
            $mapped_data['bulan_lahir'] = $employee['BULAN_LAHIR'];
        }
        
        if (isset($employee['JENIS_KELAMIN'])) {
            // Map gender values: L -> Laki-laki, P -> Perempuan
            $gender = $employee['JENIS_KELAMIN'];
            if (strtoupper($gender) === 'L') {
                $mapped_data['jenkel'] = 'L';
            } elseif (strtoupper($gender) === 'P') {
                $mapped_data['jenkel'] = 'P';
            } else {
                $mapped_data['jenkel'] = $gender;
            }
        }
        
        if (isset($employee['AGAMA'])) {
            // Ensure agama values match database enum values
            $religion = $employee['AGAMA'];
            $validReligions = ['ISLAM', 'KRISTEN PROTESTAN', 'KRISTEN KATHOLIK', 'HINDU', 'BUDHA', 'KONGHUCU'];
            
            // Convert to proper case if it's a valid religion
            $upperReligion = strtoupper($religion);
            if (in_array($upperReligion, $validReligions)) {
                $mapped_data['agama'] = $upperReligion;
            } else {
                // Default to ISLAM if not valid
                $mapped_data['agama'] = 'ISLAM';
            }
        }
        
        if (isset($employee['PENDIDIKAN_TERAKHIR'])) {
            // Ensure pendidikan values match database enum values
            $education = $employee['PENDIDIKAN_TERAKHIR'];
            $validEducation = ['SD', 'SMP', 'SMA', 'D-3', 'S-1', 'S-2', 'S-3'];
            
            // Convert to proper case if it's a valid education level
            $upperEducation = strtoupper($education);
            if (in_array($upperEducation, $validEducation)) {
                $mapped_data['pendidikan'] = $upperEducation;
            } else {
                // Default to SMA if not valid
                $mapped_data['pendidikan'] = 'SMA';
            }
        }
        
        if (isset($employee['NO_TELEPON'])) {
            $mapped_data['telp1'] = $employee['NO_TELEPON'];
        }
        
        if (isset($employee['NO_KTP'])) {
            $mapped_data['no_ktp'] = $employee['NO_KTP'];
        }
        
        if (isset($employee['NO_KK'])) {
            $mapped_data['no_kk'] = $employee['NO_KK'];
        }
        
        if (isset($employee['ALAMAT_KTP'])) {
            $mapped_data['alamat_ktp'] = $employee['ALAMAT_KTP'];
        }
        
        if (isset($employee['ALAMAT_TINGGAL_SEKARANG'])) {
            $mapped_data['alamat_skrg'] = $employee['ALAMAT_TINGGAL_SEKARANG'];
        }
        
        if (isset($employee['STATUS_PERNIKAHAN'])) {
            // Ensure status pernikahan values match database enum values
            $maritalStatus = $employee['STATUS_PERNIKAHAN'];
            $validStatus = ['KAWIN', 'BELUM KAWIN', 'CERAI HIDUP', 'CERAI MATI'];
            
            // Convert to proper case if it's a valid marital status
            $upperStatus = strtoupper($maritalStatus);
            if (in_array($upperStatus, $validStatus)) {
                $mapped_data['sts_nikah'] = $upperStatus;
            } else {
                // Default to BELUM KAWIN if not valid
                $mapped_data['sts_nikah'] = 'BELUM KAWIN';
            }
        }
        
        // Map STATUS KARYAWAN to status_karyawan field
        if (isset($employee['STATUS_KARYAWAN'])) {
            $mapped_data['sts_aktif'] = $employee['STATUS_KARYAWAN'];
        }
        
        if (isset($employee['NAMA_ORANG_TUA'])) {
            $mapped_data['nama_orang_tua'] = $employee['NAMA_ORANG_TUA'];
        }
        
        if (isset($employee['NAMA_SUAMI_ISTRI'])) {
            $mapped_data['nama_pasangan'] = $employee['NAMA_SUAMI_ISTRI'];
        }
        
        if (isset($employee['JUMLAH_ANAK'])) {
            $mapped_data['jumlah_anak'] = $employee['JUMLAH_ANAK'];
        }
        
        if (isset($employee['NAMA_ANAK'])) {
            $mapped_data['nama_anak'] = $employee['NAMA_ANAK'];
        }
        
        if (isset($employee['TGL_MASUK'])) {
            $mapped_data['tgl_m_kerja'] = $this->format_date($employee['TGL_MASUK']);
        }
        
        if (isset($employee['TGL_KELUAR'])) {
            $mapped_data['tgl_keluar'] = $this->format_date($employee['TGL_KELUAR']);
        }
        
        if (isset($employee['TGL_JEDA'])) {
            $mapped_data['tgl_jeda'] = $this->format_date($employee['TGL_JEDA']);
        }
        
        if (isset($employee['MASA_KERJA'])) {
            $mapped_data['masa_kerja'] = $employee['MASA_KERJA'];
        }
        
        // Set sts_aktif based on TGL_KELUAR
        if (isset($employee['TGL_KELUAR']) && !empty($employee['TGL_KELUAR']) && $employee['TGL_KELUAR'] != '00-Jan-00') {
            // If there's a TGL_KELUAR value, set status to Resign
            $mapped_data['sts_aktif'] = 'Resign';
        } else {
            // If no TGL_KELUAR or it's empty, set status to Aktif
            $mapped_data['sts_aktif'] = 'Aktif';
        }
        
        if (isset($employee['SK_KARY_TETAP'])) {
            // Handle SK_KARY_TETAP field which contains both the number and date
            $sk_value = $employee['SK_KARY_TETAP'];
            
            // Try to extract date and number from the field
            // The format seems to be: "SEJAK AWAL NOMOR SK TGL.DIANGKAT"
            // But in the data it's just the value
            $mapped_data['sk_kary_tetap_nomor'] = $sk_value;
            // We don't have a separate date field for SK, so we won't set sk_kary_tetap_tanggal
        }
        
        // Handle SEJAK AWAL field separately if it exists
        if (isset($employee['SEJAK_AWAL'])) {
            $mapped_data['masa_kerja'] = $employee['SEJAK_AWAL'];
        }
        
        // Handle NOMOR SK field separately if it exists
        if (isset($employee['NOMOR_SK'])) {
            $mapped_data['sk_kary_tetap_nomor'] = $employee['NOMOR_SK'];
        }
        
        // Handle TGL.DIANGKAT field separately if it exists
        if (isset($employee['TGL_DIANGKAT'])) {
            $mapped_data['sk_kary_tetap_tanggal'] = $this->format_date($employee['TGL_DIANGKAT']);
        }
        
        // Map BPJS fields
        if (isset($employee['BPJS_NO_KPJ'])) {
            $mapped_data['no_bpjs_tk'] = $employee['BPJS_NO_KPJ'];
        }
        
        if (isset($employee['NO_KARTU_TRIMAS'])) {
            $mapped_data['no_bpjs_kes'] = $employee['NO_KARTU_TRIMAS'];
        }
        
        // Map sts_penunjang field (replacing tipe_ptkp)
        if (isset($employee['STS_PENUNJANG'])) {
            $stsPenunjang = strtoupper($employee['STS_PENUNJANG']);
            // Convert L0 to TK
            if ($stsPenunjang === 'L0') {
                $stsPenunjang = 'TK';
            }
            // Validate against allowed enum values
            $validStsPenunjang = ['TK', 'K0', 'K1', 'K2', 'K3', 'TK1', 'TK2', 'TK3'];
            if (in_array($stsPenunjang, $validStsPenunjang)) {
                $mapped_data['sts_penunjang'] = $stsPenunjang;
            } else {
                // Default to TK if not valid
                $mapped_data['sts_penunjang'] = 'TK';
            }
        }
        
        // Map LEVEL field
        if (isset($employee['LEVEL'])) {
            $mapped_data['level'] = $employee['LEVEL'];
        }
        
        // Map DL/IDL field
        if (isset($employee['DL_IDL'])) {
            $mapped_data['dl_idl'] = $employee['DL_IDL'];
        }
        
        // Map ALASAN KELUAR field
        if (isset($employee['ALASAN_KELUAR'])) {
            $mapped_data['alasan_keluar'] = $employee['ALASAN_KELUAR'];
        }
        
        // Map KETERANGAN field
        if (isset($employee['KETERANGAN'])) {
            $mapped_data['keterangan'] = $employee['KETERANGAN'];
        }
        
        // Map jabatan name to recid_jbtn and jabatan text field
        if (isset($employee['JABATAN']) && !empty($employee['JABATAN'])) {
            $jabatan_name = is_null($employee['JABATAN']) ? '' : trim($employee['JABATAN']);
            $jabatan = $this->jabatan_by_name($jabatan_name);
            
            if ($jabatan && isset($jabatan->recid_jbtn)) {
                $mapped_data['recid_jbtn'] = (int)$jabatan->recid_jbtn;
            }
            
            // No need to store jabatan name text, we already have recid_jbtn
        }
        
        // Map bagian name to recid_bag and bagian text field
        if (isset($employee['BAGIAN']) && !empty($employee['BAGIAN'])) {
            $bagian_name = is_null($employee['BAGIAN']) ? '' : trim($employee['BAGIAN']);
            $bagian = $this->bagian_by_name($bagian_name);
            
            if ($bagian && isset($bagian->recid_bag)) {
                $mapped_data['recid_bag'] = (int)$bagian->recid_bag;
            }
            
            // No need to store bagian name text, we already have recid_bag
        }
        
        // Map sub bagian name to recid_subbag and sub_bagian text field
        if (isset($employee['SUB_BAGIAN']) && !empty($employee['SUB_BAGIAN'])) {
            $sub_bagian_name = is_null($employee['SUB_BAGIAN']) ? '' : trim($employee['SUB_BAGIAN']);
            $sub_bagian = $this->sub_bagian_by_name($sub_bagian_name);
            
            if ($sub_bagian && isset($sub_bagian->recid_subbag)) {
                $mapped_data['recid_subbag'] = (int)$sub_bagian->recid_subbag;
            }
            
            // No need to store sub_bagian name text, we already have recid_subbag
        }
        
        // Departemen is not stored as a separate field, it's linked through bagian
        
        // Set default values for fields not in Excel
        // sts_aktif is already handled above
        // Other default values are set at the beginning of the function
        
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
        
        // Handle two-digit year format (e.g., 01-Sep-67)
        // For two-digit years, we need to handle the century correctly
        $corrected_date_string = $date_string;
        
        // Check if the date string matches the pattern DD-MMM-YY
        if (preg_match('/^\d{1,2}-[A-Za-z]{3}-\d{2}$/', $date_string)) {
            $date_parts = explode('-', $date_string);
            if (count($date_parts) == 3) {
                $day = $date_parts[0];
                $month = $date_parts[1];
                $year = $date_parts[2];
                
                // Handle two-digit year (assume 00-29 is 20xx and 30-99 is 19xx)
                if (strlen($year) == 2) {
                    $year_num = intval($year);
                    if ($year_num >= 30) {
                        $full_year = "19" . $year;
                    } else {
                        $full_year = "20" . $year;
                    }
                    $corrected_date_string = $day . '-' . $month . '-' . $full_year;
                }
            }
        }
        
        // Try to parse the date
        $timestamp = strtotime($corrected_date_string);
        
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
    }
    
    /**
     * Calculate age based on birth date
     * 
     * @param string $birth_date Birth date string
     * @return int Age in years
     */
    private function calculate_age($birth_date)
    {
        if (empty($birth_date) || $birth_date === '00-Jan-00') {
            return 0;
        }
        
        // Handle two-digit year format (e.g., 01-Sep-67)
        // For two-digit years, we need to handle the century correctly
        $corrected_birth_date = $birth_date;
        
        // Check if the date string matches the pattern DD-MMM-YY
        if (preg_match('/^\d{1,2}-[A-Za-z]{3}-\d{2}$/', $birth_date)) {
            $date_parts = explode('-', $birth_date);
            if (count($date_parts) == 3) {
                $day = $date_parts[0];
                $month = $date_parts[1];
                $year = $date_parts[2];
                
                // Handle two-digit year (assume 00-29 is 20xx and 30-99 is 19xx)
                if (strlen($year) == 2) {
                    $year_num = intval($year);
                    if ($year_num >= 30) {
                        $full_year = "19" . $year;
                    } else {
                        $full_year = "20" . $year;
                    }
                    $corrected_birth_date = $day . '-' . $month . '-' . $full_year;
                }
            }
        }
        
        // Try to parse the birth date
        $timestamp = strtotime($corrected_birth_date);
        
        if ($timestamp !== false) {
            $birth_date_obj = new DateTime(date('Y-m-d', $timestamp));
            $current_date_obj = new DateTime();
            $interval = $current_date_obj->diff($birth_date_obj);
            return $interval->y;
        }
        
        return 0;
    }
    
    /**
     * Handle contract data for an employee
     * 
     * @param array $employee Employee data from Excel
     * @param int $employee_id Employee ID in database
     */
    private function handle_contract_data($employee, $employee_id)
    {
        // Handle multi-row KONTRAK headers (AI to DR columns) only
        // Collect all KONTRAK data from the employee record
        $kontrak_entries = [];
        
        // Look for KONTRAK data keys in the employee data (only from AI to DR columns)
        foreach ($employee as $key => $value) {
            if (strpos($key, 'KONTRAK_AWAL_') === 0 || strpos($key, 'KONTRAK_AKHIR_') === 0) {
                // Extract contract type and column number from key
                // Format: KONTRAK_{TYPE}_{COLUMN_NUMBER}
                $parts = explode('_', $key);
                $type = $parts[1]; // AWAL or AKHIR
                $column = (int)$parts[2]; // Column number
                
                // Only process columns in the AI to DR range (35 to 122)
                if ($column >= 35 && $column <= 122) {
                    // Initialize entry for this column if not exists
                    if (!isset($kontrak_entries[$column])) {
                        $kontrak_entries[$column] = [
                            'AWAL' => null,
                            'AKHIR' => null
                        ];
                    }
                    
                    // Store the value
                    $kontrak_entries[$column][$type] = $value;
                }
            }
        }
        
        // Process contract periods - group AWAL and AKHIR pairs together
        // Each pair represents one contract period
        $contract_periods = [];
        
        // Group consecutive AWAL/AKHIR columns into periods
        // Columns 35(AWAL) and 36(AKHIR) = Period 1
        // Columns 37(AWAL) and 38(AKHIR) = Period 2
        // etc.
        for ($col = 35; $col <= 122; $col += 2) {
            $period = [
                'AWAL' => null,
                'AKHIR' => null
            ];
            
            // Check if we have AWAL data for this period
            if (isset($kontrak_entries[$col]['AWAL'])) {
                $period['AWAL'] = $kontrak_entries[$col]['AWAL'];
            }
            
            // Check if we have AKHIR data for this period
            if (isset($kontrak_entries[$col + 1]['AKHIR'])) {
                $period['AKHIR'] = $kontrak_entries[$col + 1]['AKHIR'];
            }
            
            // Only add period if we have at least one date
            if ($period['AWAL'] || $period['AKHIR']) {
                $contract_periods[] = $period;
            }
        }
        
        // Insert each contract period as a single record
        foreach ($contract_periods as $period) {
            $contract_start = null;
            $contract_end = null;
            
            // Handle contract start date (AWAL)
            if (isset($period['AWAL']) && !empty($period['AWAL'])) {
                $contract_start = $this->format_date($period['AWAL']);
            }
            
            // Handle contract end date (AKHIR)
            if (isset($period['AKHIR']) && !empty($period['AKHIR'])) {
                $contract_end = $this->format_date($period['AKHIR']);
            }
            
            // If we have contract data, insert it into karyawan_kontrak table
            if ($contract_start || $contract_end) {
                $contract_data = [
                    'recid_karyawan' => $employee_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Only add dates if they exist and are valid
                if ($contract_start) {
                    $contract_data['tgl_mulai'] = $contract_start;
                }
                
                if ($contract_end) {
                    $contract_data['tgl_akhir'] = $contract_end;
                }
                
                // Insert contract data
                $this->db->insert('karyawan_kontrak', $contract_data);
            }
        }
    }

    // ################################################### LOOKUP METHODS FOR IMPORT ###################################################################
    /**
     * Lookup jabatan by name
     * 
     * @param string $nama_jbtn Name of the jabatan
     * @return object|null Jabatan object or null if not found
     */
    public function jabatan_by_name($nama_jbtn)
    {
        $query = $this->db->query("SELECT * FROM jabatan WHERE nama_jbtn = ? AND is_delete = '0' LIMIT 1", array($nama_jbtn));
        $result = $query->row();
        return $result;
    }

    /**
     * Lookup bagian by name
     * 
     * @param string $nama_bag Name of the bagian
     * @return object|null Bagian object or null if not found
     */
    public function bagian_by_name($nama_bag)
    {
        $query = $this->db->query("SELECT * FROM bagian WHERE nama_bag = ? AND is_delete = '0' LIMIT 1", array($nama_bag));
        $result = $query->row();
        return $result;
    }

    /**
     * Lookup sub bagian by name
     * 
     * @param string $sub_bag Name of the sub bagian
     * @return object|null Sub bagian object or null if not found
     */
    public function sub_bagian_by_name($sub_bag)
    {
        $query = $this->db->query("SELECT * FROM bagian_sub WHERE sub_bag = ? AND is_delete = '0' LIMIT 1", array($sub_bag));
        $result = $query->row();
        return $result;
    }
}
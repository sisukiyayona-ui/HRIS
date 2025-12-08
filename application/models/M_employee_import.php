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
                    $cell = $worksheet->getCellByColumnAndRow($columnIndex, $row);
                    $cellValue = $cell->getValue();
                    
                    // Handle date cells properly
                    if (in_array(strtoupper($columnName), ['TGL. MASUK', 'TGL. KELUAR', 'TGL.JEDA', 'TGL LAHIR', 'TGL.DIANGKAT', 'SEJAK AWAL'])) {
                        // For date columns, try to format them properly
                        try {
                            // Special handling for TGL LAHIR - only show day number
                            if (strtoupper($columnName) === 'TGL LAHIR') {
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
                            } 
                            // Special handling for BULAN LAHIR - only show month name
                            elseif (strtoupper($columnName) === 'BULAN LAHIR') {
                                // For birth month, extract just the month name
                                if (is_numeric($cellValue)) {
                                    if ($cellValue == 0) {
                                        $cellValue = '';
                                    } else {
                                        // Try to convert to date and extract month
                                        // Suppress deprecation warnings from PhpSpreadsheet library
                                        $previousErrorReporting = error_reporting();
                                        error_reporting($previousErrorReporting & ~E_DEPRECATED);
                                        $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                                        // Restore error reporting
                                        error_reporting($previousErrorReporting);
                                        $cellValue = $dateObj->format('F'); // 'F' gives full month name
                                    }
                                } else {
                                    // For non-numeric values, try to extract month from date string
                                    if (!empty($cellValue) && $cellValue !== '00-Jan-00') {
                                        // Try to parse the date and extract month
                                        $timestamp = strtotime($cellValue);
                                        if ($timestamp !== false) {
                                            $cellValue = date('F', $timestamp);
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
                    
                    // Normalize column name: replace spaces, dots, slashes, and hyphens with single underscore
                    $normalizedColumnName = preg_replace('/[\s\.\/\-]+/', '_', $columnName);
                    $rowData[strtoupper($normalizedColumnName)] = $cellValue;
                    
                    // Debug: Log the transformation for troubleshooting
                    // error_log("Column '{$columnName}' normalized to '" . strtoupper($normalizedColumnName) . "'");
                    
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
                        if (!empty($existing_employees) && is_array($existing_employees)) {
                            $existing_employee = $existing_employees[0]; // Get the first match
                        }
                    }
                    
                    if ($existing_employee && isset($existing_employee->recid_karyawan)) {
                        // Update existing employee
                        $this->M_hris->karyawan_update($employee_data, $existing_employee->recid_karyawan);
                        $import_results['updated_records']++;
                        
                        // Track updated record details
                        $import_results['updated_details'][] = [
                            'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                            'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : '',
                            'recid_karyawan' => $existing_employee->recid_karyawan
                        ];
                    } else {
                        // Insert new employee
                        $this->M_hris->karyawan_pinsert($employee_data);
                        $import_results['inserted_records']++;
                        
                        // Track inserted record details
                        $import_results['inserted_details'][] = [
                            'NIK' => isset($employee['NIK']) ? $employee['NIK'] : '',
                            'NAMA' => isset($employee['NAMA']) ? $employee['NAMA'] : ''
                        ];
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
            $mapped_data['email'] = $employee['ALAMAT_E_MAIL_PRIBADI'];
        }
        
        if (isset($employee['TEMPAT_LAHIR'])) {
            $mapped_data['tmp_lahir'] = $employee['TEMPAT_LAHIR'];
        }
        
        if (isset($employee['TGL_LAHIR'])) {
            $mapped_data['tgl_lahir'] = $this->format_date($employee['TGL_LAHIR']);
        }
        
        if (isset($employee['USIA'])) {
            $mapped_data['usia'] = $employee['USIA'];
        }
        
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
        
        if (isset($employee['NOMOR_SK'])) {
            $mapped_data['sk_kary_tetap_nomor'] = $employee['NOMOR_SK'];
        }
        
        if (isset($employee['TGL_DIANGKAT'])) {
            $mapped_data['sk_kary_tetap_tanggal'] = $this->format_date($employee['TGL_DIANGKAT']);
        }
        
        if (isset($employee['SEJAK_AWAL'])) {
            $mapped_data['masa_kerja'] = $employee['SEJAK_AWAL'];
        }
        
        // Set sts_aktif based on TGL_KELUAR
        if (isset($employee['TGL_KELUAR']) && !empty($employee['TGL_KELUAR']) && $employee['TGL_KELUAR'] != '00-Jan-00') {
            // If there's a TGL_KELUAR value, set status to Resign
            $mapped_data['sts_aktif'] = 'Resign';
        } else {
            // If no TGL_KELUAR or it's empty, set status to Aktif
            $mapped_data['sts_aktif'] = 'Aktif';
        }
        
        // Map BPJS fields
        if (isset($employee['BPJS_NO_KPJ'])) {
            $mapped_data['no_kpj'] = $employee['BPJS_NO_KPJ'];
        }
        
        if (isset($employee['NO_KARTU_TRIMAS'])) {
            $mapped_data['no_kartu_trimas'] = $employee['NO_KARTU_TRIMAS'];
        }
        
        // Map jabatan name to recid_jbtn and jabatan text field
        if (isset($employee['JABATAN']) && !empty($employee['JABATAN'])) {
            $jabatan_name = trim($employee['JABATAN']);
            $jabatan = $this->jabatan_by_name($jabatan_name);
            
            if ($jabatan && isset($jabatan->recid_jbtn)) {
                $mapped_data['recid_jbtn'] = (int)$jabatan->recid_jbtn;
            }
            
            // No need to store jabatan name text, we already have recid_jbtn
        }
        
        // Map bagian name to recid_bag and bagian text field
        if (isset($employee['BAGIAN']) && !empty($employee['BAGIAN'])) {
            $bagian_name = trim($employee['BAGIAN']);
            $bagian = $this->bagian_by_name($bagian_name);
            
            if ($bagian && isset($bagian->recid_bag)) {
                $mapped_data['recid_bag'] = (int)$bagian->recid_bag;
            }
            
            // No need to store bagian name text, we already have recid_bag
        }
        
        // Map sub bagian name to recid_subbag and sub_bagian text field
        if (isset($employee['SUB_BAGIAN']) && !empty($employee['SUB_BAGIAN'])) {
            $sub_bagian_name = trim($employee['SUB_BAGIAN']);
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
        
        // Try to parse the date
        $timestamp = strtotime($date_string);
        
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
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
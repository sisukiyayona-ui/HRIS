<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_absen');
        $this->load->model('M_hris');
        $this->load->model('M_Reports');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        
        // Load PHPExcel library for Excel export
        require_once APPPATH . 'libraries/PHPExcel.php';
    }

    public function index()
    {
        // Default reports page
        $this->load->view('templates/header');
        $this->load->view('reports/index');
        $this->load->view('templates/footer');
    }

    public function daily_attendance_report()
    {
        $data['title'] = 'Laporan Kehadiran Harian';
        
        // Load the view for the daily attendance report with proper layout
        $this->load->view('layout/a_header');
        $usr = $this->session->userdata('kar_id');
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        }
        $this->load->view('layout/menu_super', $data);
        $this->load->view('reports/daily_attendance_report_view', $data);
        $this->load->view('layout/a_footer');
    }

    public function get_daily_attendance_data()
    {
        $date = $this->input->post('date');
        
        if (!$date) {
            $date = date('Y-m-d'); // Default to today's date
        }

        try {
            // Get aggregated data by department
            $report_data = $this->M_Reports->get_aggregated_attendance_report_data($date);
            
            // Get department budget
            $budget_data = $this->M_Reports->get_department_budget($date);
            
            // Create budget array for easy lookup
            $budgets = [];
            foreach ($budget_data as $budget) {
                $budgets[$budget['departemen']] = $budget['budget'];
            }
            
            // Process and group data by department
            $processed_data = $this->process_aggregated_attendance_data($report_data, $budgets, $date);
            
            // Calculate totals
            $totals = $this->calculate_daily_attendance_totals($processed_data);
            
            $response = array(
                'status' => 'success',
                'data' => $processed_data,
                'totals' => $totals,
                'date' => $date
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function process_aggregated_attendance_data($report_data, $budgets, $date)
    {
        $processed = array();
        
        // Group aggregated data by department
        foreach ($report_data as $dept_data) {
            $dept = $dept_data['departemen'] ?? 'Tidak Ada Bagian';
            
            if (!isset($processed[$dept])) {
                // Get budget for this department
                $budget = isset($budgets[$dept]) ? $budgets[$dept] : 0;
                
                $processed[$dept] = array(
                    'dept_name' => $dept,
                    'budget' => $budget,
                    'total' => $dept_data['total'] ?? 0,
                    'tetap' => array(
                        'l' => $dept_data['tetap_l_count'] ?? 0, 
                        'p' => $dept_data['tetap_p_count'] ?? 0, 
                        'jml' => $dept_data['tetap_jml_count'] ?? 0
                    ),
                    'kontrak' => array(
                        'l' => $dept_data['kontrak_l_count'] ?? 0, 
                        'p' => $dept_data['kontrak_p_count'] ?? 0, 
                        'jml' => $dept_data['kontrak_jml_count'] ?? 0
                    ),
                    'hadir' => $dept_data['hadir_count'] ?? 0,
                    'tidak_hadir' => $dept_data['alpa_count'] ?? 0,
                    'keterangan' => array(
                        's' => $dept_data['sakit_count'] ?? 0,
                        'm' => 0, // Not in database query
                        'i' => $dept_data['izin_count_detail'] ?? 0
                    ),
                    'jum_kar' => $dept_data['izin_count'] ?? 0,
                    'cuti' => array(
                        'cm' => $dept_data['cuti_melahirkan_count'] ?? 0,
                        'ck' => $dept_data['cuti_khusus_count'] ?? 0,
                        'cn' => $dept_data['cuti_nikah_count'] ?? 0,
                        'ct' => $dept_data['cuti_tahunan_count'] ?? 0,
                        'cs' => $dept_data['cuti_haid_count'] ?? 0
                    ),
                    'karyawan' => array('baru' => 0, 'keluar' => 0),
                    'outsource_skill' => array('baru' => 0, 'keluar' => 0),
                    'outsource_nonskill' => array('baru' => 0, 'keluar' => 0),
                    'karyawan_off' => $dept_data['karyawan_off_count'] ?? 0,
                    'dl_idl' => $dept_data['dl_idl'] ?? ''
                );
            }
        }
        
        // Calculate percentages for each department
        foreach ($processed as $dept => $dept_data) {
            // Determine DL/IDL based on department name if not from database
            if (empty($dept_data['dl_idl'])) {
                $processed[$dept]['dl_idl'] = $this->get_dl_idl_from_department($dept);
            }
            
            // Calculate present and absent percentages
            if ($dept_data['total'] > 0) {
                $present_pct = ($dept_data['hadir'] / $dept_data['total']) * 100;
                $absent_pct = (($dept_data['tidak_hadir'] + $dept_data['karyawan_off']) / $dept_data['total']) * 100;
                
                $processed[$dept]['present_pct'] = number_format($present_pct, 2) . '%';
                $processed[$dept]['absent_pct'] = number_format($absent_pct, 2) . '%';
            } else {
                $processed[$dept]['present_pct'] = '0.00%';
                $processed[$dept]['absent_pct'] = '0.00%';
            }
        }
        
        // Group departments by first letter
        $grouped_data = array();
        foreach ($processed as $dept => $dept_data) {
            $first_letter = strtoupper(substr($dept, 0, 1));
            if (!isset($grouped_data[$first_letter])) {
                $grouped_data[$first_letter] = array();
            }
            $grouped_data[$first_letter][$dept] = $dept_data;
        }
        
        // Sort departments alphabetically within each letter group
        foreach ($grouped_data as $letter => $departments) {
            ksort($grouped_data[$letter]);
        }
        
        return $grouped_data;
    }
    
    private function get_dl_idl_from_department($dept_name) {
        $dept_name = strtoupper(trim($dept_name));
        
        // Define mapping of department names to DL/IDL values
        $mapping = [
            'CUTTING' => 'DL NON SEWING',
            'PRESS' => 'DL NON SEWING',
            'KENSA' => 'DL NON SEWING',
            'PACKING' => 'DL NON SEWING',
            'SEWING' => 'DL SEWING',
            'BERUTO' => 'DL SEWING',
            'LINE 1' => 'DL SEWING',
            'LINE 2' => 'DL SEWING',
            'LINE 3' => 'DL SEWING',
            'LINE 4' => 'DL SEWING',
            'LINE 5' => 'DL SEWING',
            'LINE 6' => 'DL SEWING',
            'LINE 7' => 'DL SEWING',
            'LINE CHIEF' => 'DL SEWING',
            'PERSIAPAN' => 'DL SEWING',
            'POCKET' => 'DL SEWING',
            'RUPU' => 'DL SEWING',
            'SAMPLE' => 'DL SEWING',
            'SEWING LINE' => 'DL SEWING',
            'SEWING LINE 1' => 'DL SEWING',
            'SEWING LINE 2' => 'DL SEWING',
            'SEWING LINE 3' => 'DL SEWING',
            'SEWING LINE 4' => 'DL SEWING',
            'SEWING LINE 5' => 'DL SEWING',
            'SEWING LINE 6' => 'DL SEWING',
            'TEKFIT' => 'DL SEWING',
            'TANOKOU' => 'DL SEWING',
            'TSUKAN KENZA' => 'DL SEWING',
            'QC INLINE' => 'DL SEWING',
            'PRODUKSI' => 'IDL PRODUKSI',
            'ADM PROD' => 'IDL PRODUKSI',
            'ADM MTC' => 'IDL PRODUKSI',
            'ADMINISTRASI' => 'IDL PRODUKSI',
            'MANAGER' => 'IDL PRODUKSI',
            'STAFF' => 'IDL PRODUKSI',
            'SMV' => 'IDL PRODUKSI',
            'IE' => 'IDL PRODUKSI',
            'R&D' => 'IDL PRODUKSI',
            'MAINTENANCE' => 'IDL PRODUKSI',
            'PPIC' => 'IDL NON PRODUKSI',
            'SALES' => 'IDL NON PRODUKSI',
            'MERCHANDISER' => 'IDL NON PRODUKSI',
            'FICO' => 'G & A',
            'HC' => 'G & A',
            'FINANCE' => 'G & A',
            'ACCOUNTING' => 'G & A',
            'RECEPTIONIST' => 'G & A',
            'UMUM' => 'G & A',
            'DRIVER' => 'G & A',
            'KURIR' => 'G & A',
            'SUSTER' => 'G & A',
            'BOD' => 'BOD',
            'PRESDIR' => 'BOD',
            'MD' => 'BOD',
        ];
        
        // Check for exact matches or partial matches
        foreach ($mapping as $dept => $dl_idl) {
            if (stripos($dept_name, $dept) !== false) {
                return $dl_idl;
            }
        }
        
        // Default value if no match found
        return 'IDL PRODUKSI';
    }

    private function calculate_daily_attendance_totals($processed_data)
    {
        $totals = array(
            'budget' => 0,
            'total' => 0,
            'tetap' => array('l' => 0, 'p' => 0, 'jml' => 0),
            'kontrak' => array('l' => 0, 'p' => 0, 'jml' => 0),
            'hadir' => 0,
            'tidak_hadir' => 0,
            'keterangan' => array('s' => 0, 'm' => 0, 'i' => 0),
            'jum_kar' => 0,
            'cuti' => array('cm' => 0, 'ck' => 0, 'cn' => 0, 'ct' => 0, 'cs' => 0),
            'karyawan' => array('baru' => 0, 'keluar' => 0),
            'outsource_skill' => array('baru' => 0, 'keluar' => 0),
            'outsource_nonskill' => array('baru' => 0, 'keluar' => 0),
            'karyawan_off' => 0
        );
        
        // Process the grouped data structure
        foreach ($processed_data as $letter => $departments) {
            foreach ($departments as $dept_data) {
                $totals['budget'] += $dept_data['budget'] ?? 0;
                $totals['total'] += $dept_data['total'] ?? 0;
                $totals['tetap']['l'] += $dept_data['tetap']['l'] ?? 0;
                $totals['tetap']['p'] += $dept_data['tetap']['p'] ?? 0;
                $totals['tetap']['jml'] += $dept_data['tetap']['jml'] ?? 0;
                $totals['kontrak']['l'] += $dept_data['kontrak']['l'] ?? 0;
                $totals['kontrak']['p'] += $dept_data['kontrak']['p'] ?? 0;
                $totals['kontrak']['jml'] += $dept_data['kontrak']['jml'] ?? 0;
                $totals['hadir'] += $dept_data['hadir'] ?? 0;
                $totals['tidak_hadir'] += $dept_data['tidak_hadir'] ?? 0;
                $totals['keterangan']['s'] += $dept_data['keterangan']['s'] ?? 0;
                $totals['keterangan']['m'] += $dept_data['keterangan']['m'] ?? 0;
                $totals['keterangan']['i'] += $dept_data['keterangan']['i'] ?? 0;
                $totals['jum_kar'] += $dept_data['jum_kar'] ?? 0;
                $totals['cuti']['cm'] += $dept_data['cuti']['cm'] ?? 0;
                $totals['cuti']['ck'] += $dept_data['cuti']['ck'] ?? 0;
                $totals['cuti']['cn'] += $dept_data['cuti']['cn'] ?? 0;
                $totals['cuti']['ct'] += $dept_data['cuti']['ct'] ?? 0;
                $totals['cuti']['cs'] += $dept_data['cuti']['cs'] ?? 0;
                $totals['karyawan']['baru'] += $dept_data['karyawan']['baru'] ?? 0;
                $totals['karyawan']['keluar'] += $dept_data['karyawan']['keluar'] ?? 0;
                $totals['outsource_skill']['baru'] += $dept_data['outsource_skill']['baru'] ?? 0;
                $totals['outsource_skill']['keluar'] += $dept_data['outsource_skill']['keluar'] ?? 0;
                $totals['outsource_nonskill']['baru'] += $dept_data['outsource_nonskill']['baru'] ?? 0;
                $totals['outsource_nonskill']['keluar'] += $dept_data['outsource_nonskill']['keluar'] ?? 0;
                $totals['karyawan_off'] += $dept_data['karyawan_off'] ?? 0;
            }
        }
        
        // Calculate percentages
        if ($totals['total'] > 0) {
            $present_pct = ($totals['hadir'] / $totals['total']) * 100;
            $absent_pct = (($totals['tidak_hadir'] + $totals['karyawan_off']) / $totals['total']) * 100;
            
            $totals['present_pct'] = number_format($present_pct, 2) . '%';
            $totals['absent_pct'] = number_format($absent_pct, 2) . '%';
        } else {
            $totals['present_pct'] = '0.00%';
            $totals['absent_pct'] = '0.00%';
        }
        
        return $totals;
    }

    public function export_daily_attendance()
    {
        $date = $this->input->get('date');
        
        if (!$date) {
            $date = date('Y-m-d'); // Default to today's date
        }

        // Get aggregated data by department
        $report_data = $this->M_Reports->get_aggregated_attendance_report_data($date);
        
        // Get department budget
        $budget_data = $this->M_Reports->get_department_budget($date);
        
        // Create budget array for easy lookup
        $budgets = [];
        foreach ($budget_data as $budget) {
            $budgets[$budget['departemen']] = $budget['budget'];
        }
        
        // Process and group data by department
        $processed_data = $this->process_aggregated_attendance_data($report_data, $budgets, $date);
        
        // Calculate totals
        $totals = $this->calculate_daily_attendance_totals($processed_data);

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()
            ->setCreator("HRIS System")
            ->setTitle("Daily Attendance Report")
            ->setSubject("Daily Attendance Report")
            ->setDescription("Daily attendance report by department");

        // Create the worksheet
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Daily Attendance Report');

        // Set column widths
        $column_widths = [
            'A' => 5, 'B' => 5, 'C' => 25, 'D' => 15, 'E' => 8, 'F' => 8,
            'G' => 6, 'H' => 6, 'I' => 6, 'J' => 6, 'K' => 6, 'L' => 6,
            'M' => 8, 'N' => 8, 'O' => 8, 'P' => 6, 'Q' => 6, 'R' => 6,
            'S' => 6, 'T' => 8, 'U' => 8, 'V' => 6, 'W' => 6, 'X' => 6,
            'Y' => 6, 'Z' => 6, 'AA' => 8, 'AB' => 8, 'AC' => 8, 'AD' => 8,
            'AE' => 8, 'AF' => 8, 'AG' => 8
        ];
        
        foreach ($column_widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Merge and set title
        $sheet->mergeCells('A1:AG3');
        $sheet->setCellValue('A1', "LAPORAN KEHADIRAN HARIAN");
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $sheet->mergeCells('A3:AG3');
        $sheet->setCellValue('A3', "Tanggal: " . date('d/m/Y', strtotime($date)));
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set headers
        $headers = [
            'A' => 'No.',
            'B' => 'No',
            'C' => 'DEPARTEMEN',
            'D' => 'DL/IDL',
            'E' => 'Budget',
            'F' => 'Total',
            'G' => 'TETAP L',
            'H' => 'TETAP P',
            'I' => 'TETAP JML',
            'J' => 'KONTRAK L',
            'K' => 'KONTRAK P',
            'L' => 'KONTRAK JML',
            'M' => 'Hadir',
            'N' => 'Line Hadir',
            'O' => 'Tidak Hadir',
            'P' => 'Keterangan S',
            'Q' => 'Keterangan M',
            'R' => 'Keterangan I',
            'S' => 'Bag',
            'T' => 'Present %',
            'U' => 'Absent %',
            'V' => 'CM',
            'W' => 'CK',
            'X' => 'CN',
            'Y' => 'CT',
            'Z' => 'CS',
            'AA' => 'Karyawan Baru',
            'AB' => 'Karyawan Keluar',
            'AC' => 'Outsource Skill Baru',
            'AD' => 'Outsource Skill Keluar',
            'AE' => 'Outsource Non Skill Baru',
            'AF' => 'Outsource Non Skill Keluar',
            'AG' => 'Krywn Off'
        ];
        
        // Set main headers
        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . '5', $header);
            $sheet->getStyle($col . '5')->getFont()->setBold(true);
            $sheet->getStyle($col . '5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . '5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }

        // Add data rows with hierarchical structure
        $row = 6;
        $current_dept_index = 1;
        
        // Sort the letters alphabetically
        ksort($processed_data);
        
        foreach ($processed_data as $letter => $departments) {
            // Add letter header row
            $sheet->setCellValue('A' . $row, $letter);
            $sheet->mergeCells('A' . $row . ':AG' . $row);
            
            // Style letter header row
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A' . $row . ':AG' . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3');
            
            // Apply borders to the row
            for ($col = 'A'; $col <= 'AG'; $col++) {
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
            
            $row++;
            
            // Add department rows under this letter
            $dept_sub_index = 1;
            foreach ($departments as $dept_name => $dept_data) {
                // Department row
                $sheet->setCellValue('A' . $row, '');
                $sheet->setCellValue('B' . $row, $dept_sub_index);
                $sheet->setCellValue('C' . $row, $dept_name);
                $sheet->setCellValue('D' . $row, $dept_data['dl_idl']);
                $sheet->setCellValue('E' . $row, $dept_data['budget']);
                $sheet->setCellValue('F' . $row, $dept_data['total']);
                $sheet->setCellValue('G' . $row, $dept_data['tetap']['l']);
                $sheet->setCellValue('H' . $row, $dept_data['tetap']['p']);
                $sheet->setCellValue('I' . $row, $dept_data['tetap']['jml']);
                $sheet->setCellValue('J' . $row, $dept_data['kontrak']['l']);
                $sheet->setCellValue('K' . $row, $dept_data['kontrak']['p']);
                $sheet->setCellValue('L' . $row, $dept_data['kontrak']['jml']);
                $sheet->setCellValue('M' . $row, $dept_data['hadir']);
                $sheet->setCellValue('N' . $row, $dept_data['hadir']); // Line Hadir = Hadir count
                $sheet->setCellValue('O' . $row, $dept_data['tidak_hadir']);
                $sheet->setCellValue('P' . $row, $dept_data['keterangan']['s']);
                $sheet->setCellValue('Q' . $row, $dept_data['keterangan']['m']);
                $sheet->setCellValue('R' . $row, $dept_data['keterangan']['i']);
                $sheet->setCellValue('S' . $row, ''); // Bag - empty
                $sheet->setCellValue('T' . $row, $dept_data['present_pct']);
                $sheet->setCellValue('U' . $row, $dept_data['absent_pct']);
                $sheet->setCellValue('V' . $row, $dept_data['cuti']['cm']);
                $sheet->setCellValue('W' . $row, $dept_data['cuti']['ck']);
                $sheet->setCellValue('X' . $row, $dept_data['cuti']['cn']);
                $sheet->setCellValue('Y' . $row, $dept_data['cuti']['ct']);
                $sheet->setCellValue('Z' . $row, $dept_data['cuti']['cs']);
                $sheet->setCellValue('AA' . $row, $dept_data['karyawan']['baru']);
                $sheet->setCellValue('AB' . $row, $dept_data['karyawan']['keluar']);
                $sheet->setCellValue('AC' . $row, $dept_data['outsource_skill']['baru']);
                $sheet->setCellValue('AD' . $row, $dept_data['outsource_skill']['keluar']);
                $sheet->setCellValue('AE' . $row, $dept_data['outsource_nonskill']['baru']);
                $sheet->setCellValue('AF' . $row, $dept_data['outsource_nonskill']['keluar']);
                $sheet->setCellValue('AG' . $row, $dept_data['karyawan_off']);
                
                // Apply borders to the row
                for ($col = 'A'; $col <= 'AG'; $col++) {
                    $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                }
                
                $row++;
                $dept_sub_index++;
            }
        }
        
        // Add total row
        $sheet->setCellValue('B' . $row, 'JUMLAH TOTAL');
        $sheet->mergeCells('B' . $row . ':D' . $row);
        $sheet->setCellValue('E' . $row, $totals['budget']);
        $sheet->setCellValue('F' . $row, $totals['total']);
        $sheet->setCellValue('G' . $row, $totals['tetap']['l']);
        $sheet->setCellValue('H' . $row, $totals['tetap']['p']);
        $sheet->setCellValue('I' . $row, $totals['tetap']['jml']);
        $sheet->setCellValue('J' . $row, $totals['kontrak']['l']);
        $sheet->setCellValue('K' . $row, $totals['kontrak']['p']);
        $sheet->setCellValue('L' . $row, $totals['kontrak']['jml']);
        $sheet->setCellValue('M' . $row, $totals['hadir']);
        $sheet->setCellValue('N' . $row, $totals['hadir']); // Line Hadir = Hadir count
        $sheet->setCellValue('O' . $row, $totals['tidak_hadir']);
        $sheet->setCellValue('P' . $row, $totals['keterangan']['s']);
        $sheet->setCellValue('Q' . $row, $totals['keterangan']['m']);
        $sheet->setCellValue('R' . $row, $totals['keterangan']['i']);
        $sheet->setCellValue('S' . $row, ''); // Bag - empty
        $sheet->setCellValue('T' . $row, $totals['present_pct']);
        $sheet->setCellValue('U' . $row, $totals['absent_pct']);
        $sheet->setCellValue('V' . $row, $totals['cuti']['cm']);
        $sheet->setCellValue('W' . $row, $totals['cuti']['ck']);
        $sheet->setCellValue('X' . $row, $totals['cuti']['cn']);
        $sheet->setCellValue('Y' . $row, $totals['cuti']['ct']);
        $sheet->setCellValue('Z' . $row, $totals['cuti']['cs']);
        $sheet->setCellValue('AA' . $row, $totals['karyawan']['baru']);
        $sheet->setCellValue('AB' . $row, $totals['karyawan']['keluar']);
        $sheet->setCellValue('AC' . $row, $totals['outsource_skill']['baru']);
        $sheet->setCellValue('AD' . $row, $totals['outsource_skill']['keluar']);
        $sheet->setCellValue('AE' . $row, $totals['outsource_nonskill']['baru']);
        $sheet->setCellValue('AF' . $row, $totals['outsource_nonskill']['keluar']);
        $sheet->setCellValue('AG' . $row, $totals['karyawan_off']);
        
        // Style total row
        for ($col = 'A'; $col <= 'AG'; $col++) {
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6E6');
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }

        // Set alignment for all cells
        $last_row = $row;
        for ($r = 5; $r <= $last_row; $r++) {
            for ($col = 'A'; $col <= 'AG'; $col++) {
                if (in_array($col, ['A', 'B', 'C', 'D'])) { // Left align for text columns
                    $sheet->getStyle($col . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                } else { // Center align for number columns
                    $sheet->getStyle($col . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
            }
        }

        // Set file name and headers for download
        $filename = 'Laporan_Kehadiran_Harian_' . date('Y-m-d', strtotime($date)) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    public function monthly_percentage_attendance_report()
    {
        $data['title'] = 'REKAP PERSENTASE ABSENSI HARIAN';
        
        // Load the view for the monthly percentage attendance report with proper layout
        $this->load->view('layout/a_header');
        $usr = $this->session->userdata('kar_id');
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $data['cek_usr'] = $this->M_hris->cek_usr($usr);
        }
        $this->load->view('layout/menu_super', $data);
        $this->load->view('reports/monthly_percentage_attendance_report_view', $data);
        $this->load->view('layout/a_footer');
    }

    public function get_monthly_percentage_attendance_data()
    {
        $month = $this->input->post('month');
        
        if (!$month) {
            $month = date('Y-m'); // Default to current month
        }

        try {
            // Get aggregated data for the selected month
            $report_data = $this->M_Reports->get_monthly_percentage_attendance_report_data($month);
            
            $response = array(
                'status' => 'success',
                'data' => $report_data,
                'month' => $month
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function export_monthly_percentage_attendance()
    {
        $month = $this->input->get('month');
        
        if (!$month) {
            $month = date('Y-m'); // Default to current month
        }

        // Get aggregated data for the selected month
        $report_data = $this->M_Reports->get_monthly_percentage_attendance_report_data($month);

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()
            ->setCreator("HRIS System")
            ->setTitle("Monthly Percentage Attendance Report")
            ->setSubject("Monthly Percentage Attendance Report")
            ->setDescription("Monthly percentage attendance report by category");
        
        // Create the worksheet
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Monthly Percentage Attendance Report');

        // Set column widths
        $column_widths = [
            'A' => 25, 'B' => 10, 'C' => 10
        ];
        
        foreach ($column_widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Set title
        $sheet->setCellValue('A1', "REKAP PERSENTASE ABSENSI HARIAN BULAN " . strtoupper(date('F Y', strtotime($month))));
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set month info
        $sheet->setCellValue('A2', $month);
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Set headers
        $headers = [
            'A' => 'Keterangan',
            'B' => 'Org',
            'C' => '%'
        ];
        
        $row = 3;
        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
        
        $row++; // Start data from row 4

        // Add data rows
        if ($report_data) {
            // Absensi section
            $sheet->setCellValue('A' . $row, 'Sakit');
            $sheet->setCellValue('B' . $row, $report_data['absensi']['sakit']);
            $sheet->setCellValue('C' . $row, $report_data['absensi']['sakit_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Ijin');
            $sheet->setCellValue('B' . $row, $report_data['absensi']['izin']);
            $sheet->setCellValue('C' . $row, $report_data['absensi']['izin_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Mangkir');
            $sheet->setCellValue('B' . $row, $report_data['absensi']['mangkir']);
            $sheet->setCellValue('C' . $row, $report_data['absensi']['mangkir_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            // Subtotal absensi
            $sheet->setCellValue('A' . $row, 'Subtotal');
            $sheet->setCellValue('B' . $row, $report_data['subtotal_absensi']['total']);
            $sheet->setCellValue('C' . $row, $report_data['subtotal_absensi']['pct']);
            $this->styleSubtotalCell($sheet, $row);
            $row++;
            
            // Cuti section
            $sheet->setCellValue('A' . $row, 'Cuti Khusus');
            $sheet->setCellValue('B' . $row, $report_data['cuti']['khusus']);
            $sheet->setCellValue('C' . $row, $report_data['cuti']['khusus_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Cuti Tahunan');
            $sheet->setCellValue('B' . $row, $report_data['cuti']['tahunan']);
            $sheet->setCellValue('C' . $row, $report_data['cuti']['tahunan_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Cuti Nikah');
            $sheet->setCellValue('B' . $row, $report_data['cuti']['nikah']);
            $sheet->setCellValue('C' . $row, $report_data['cuti']['nikah_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Cuti Sakit');
            $sheet->setCellValue('B' . $row, $report_data['cuti']['sakit']);
            $sheet->setCellValue('C' . $row, $report_data['cuti']['sakit_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Cuti Melahirkan');
            $sheet->setCellValue('B' . $row, $report_data['cuti']['melahirkan']);
            $sheet->setCellValue('C' . $row, $report_data['cuti']['melahirkan_pct']);
            $this->styleCell($sheet, $row);
            $row++;
            
            // Subtotal cuti
            $sheet->setCellValue('A' . $row, 'Subtotal');
            $sheet->setCellValue('B' . $row, $report_data['subtotal_cuti']['total']);
            $sheet->setCellValue('C' . $row, $report_data['subtotal_cuti']['pct']);
            $this->styleSubtotalCell($sheet, $row);
            $row++;
            
            // Total absensi
            $sheet->setCellValue('A' . $row, 'TOTAL ABSENSI');
            $sheet->setCellValue('B' . $row, $report_data['total_absensi']['total']);
            $sheet->setCellValue('C' . $row, $report_data['total_absensi']['pct']);
            $this->styleTotalCell($sheet, $row);
            $row++;
            
            // Hadir section
            $sheet->setCellValue('A' . $row, 'TOTAL HADIR');
            $sheet->setCellValue('B' . $row, $report_data['hadir']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Sewing Hadir');
            $sheet->setCellValue('B' . $row, $report_data['hadir']['sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Non Sew Hadir');
            $sheet->setCellValue('B' . $row, $report_data['hadir']['non_sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            // Total karyawan
            $sheet->setCellValue('A' . $row, 'TOTAL KARYAWAN');
            $sheet->setCellValue('B' . $row, $report_data['karyawan']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Sewing');
            $sheet->setCellValue('B' . $row, $report_data['karyawan']['sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Non Sewing');
            $sheet->setCellValue('B' . $row, $report_data['karyawan']['non_sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            // Resign section
            $sheet->setCellValue('A' . $row, 'KARYAWAN RESIGN');
            $sheet->setCellValue('B' . $row, $report_data['resign']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Sewing');
            $sheet->setCellValue('B' . $row, $report_data['resign']['sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Non Sewing');
            $sheet->setCellValue('B' . $row, $report_data['resign']['non_sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            // Baru section
            $sheet->setCellValue('A' . $row, 'KARYAWAN BARU');
            $sheet->setCellValue('B' . $row, $report_data['baru']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Sewing');
            $sheet->setCellValue('B' . $row, $report_data['baru']['sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Non Sewing');
            $sheet->setCellValue('B' . $row, $report_data['baru']['non_sewing']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            // OS Skill section
            $sheet->setCellValue('A' . $row, 'OS SKILL');
            $sheet->setCellValue('B' . $row, $report_data['os_skill']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Resign');
            $sheet->setCellValue('B' . $row, $report_data['os_skill']['resign']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Baru');
            $sheet->setCellValue('B' . $row, $report_data['os_skill']['baru']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            // OS Non Skill section
            $sheet->setCellValue('A' . $row, 'OS NON SKILL');
            $sheet->setCellValue('B' . $row, $report_data['os_non_skill']['total']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Resign');
            $sheet->setCellValue('B' . $row, $report_data['os_non_skill']['resign']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
            
            $sheet->setCellValue('A' . $row, 'Baru');
            $sheet->setCellValue('B' . $row, $report_data['os_non_skill']['baru']);
            $sheet->setCellValue('C' . $row, '');
            $this->styleCell($sheet, $row);
            $row++;
        }

        // Set alignment for all cells
        $last_row = $row - 1;
        for ($r = 1; $r <= $last_row; $r++) {
            for ($col = 'A'; $col <= 'C'; $col++) {
                if ($col == 'A') { // Left align for text columns
                    $sheet->getStyle($col . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                } else { // Center align for number columns
                    $sheet->getStyle($col . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
            }
        }

        // Set file name and headers for download
        $filename = 'Rekap_Percentage_Absensi_Harian_' . $month . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    public function get_monthly_chart_data()
    {
        $month = $this->input->post('month');
        
        if (!$month) {
            $month = date('Y-m'); // Default to current month
        }

        try {
            // Get aggregated data for the selected month
            $report_data = $this->M_Reports->get_monthly_percentage_attendance_report_data($month);
            
            // Format data for charts
            $chart_data = array(
                'attendance' => array(
                    'labels' => ['Hadir', 'Sakit', 'Ijin', 'Mangkir', 'Cuti'],
                    'data' => [
                        $report_data['hadir']['total'] ?? 0,
                        $report_data['absensi']['sakit'] ?? 0,
                        $report_data['absensi']['izin'] ?? 0,
                        $report_data['absensi']['mangkir'] ?? 0,
                        $report_data['subtotal_cuti']['total'] ?? 0
                    ],
                    'colors' => ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF']
                ),
                'employee' => array(
                    'labels' => ['Total', 'Sewing', 'Non Sewing'],
                    'datasets' => array(
                        array(
                            'label' => 'Karyawan',
                            'data' => [
                                $report_data['karyawan']['total'] ?? 0,
                                $report_data['karyawan']['sewing'] ?? 0,
                                $report_data['karyawan']['non_sewing'] ?? 0
                            ]
                        ),
                        array(
                            'label' => 'Resign',
                            'data' => [
                                $report_data['resign']['total'] ?? 0,
                                $report_data['resign']['sewing'] ?? 0,
                                $report_data['resign']['non_sewing'] ?? 0
                            ]
                        )
                    )
                )
            );
            
            $response = array(
                'status' => 'success',
                'chart_data' => $chart_data,
                'report_data' => $report_data,
                'month' => $month
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    private function styleCell($sheet, $row) {
        for ($col = 'A'; $col <= 'C'; $col++) {
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
    }
    
    private function styleSubtotalCell($sheet, $row) {
        for ($col = 'A'; $col <= 'C'; $col++) {
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f0f0f0');
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
    }
    
    private function styleTotalCell($sheet, $row) {
        for ($col = 'A'; $col <= 'C'; $col++) {
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('e0e0e0');
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Rekap extends CI_Model {

    protected $db2;

    public function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('absen2', TRUE);
    }

    /**
     * Get user data by recid_karyawan
     */
    public function get_user_data($kar_id)
    {
        return $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                        ->from('karyawan k')
                        ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                        ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                        ->where('k.recid_karyawan', $kar_id)
                        ->get()
                        ->result();
    }

    /**
     * Get all bagian (departments) for dropdown
     */
    public function get_bagian_list()
    {
        return $this->db->select('recid_bag, nama_bag')
                        ->where('is_delete', '0')
                        ->order_by('nama_bag', 'ASC')
                        ->get('bagian')
                        ->result();
    }

    /**
     * Save attendance data to database
     */
    public function insert_absensi($data)
    {
        return $this->db2->insert('absensi', $data);
    }

    /**
     * Check if attendance record exists
     */
    public function check_absensi_exists($uid, $nik, $waktu)
    {
        return $this->db2->where('uid', $uid)
                         ->where('nik', $nik)
                         ->where('waktu', $waktu)
                         ->get('absensi')
                         ->num_rows();
    }

    /**
     * Get mapped attendance data with karyawan info
     */
    public function get_mapped_attendance($filters = [])
    {
        $this->db2->select('a.id, a.uid, a.nik as userid_mesin, a.status, a.waktu, a.tipe, a.ip_mesin, k.nama_karyawan, k.nik as nik_karyawan, pm.pin');
        $this->db2->from('absensi a');
        $this->db2->join('(SELECT DISTINCT pin, recid_karyawan FROM hris.karyawan_pin_map) pm', 'a.nik = pm.pin', 'left');
        $this->db2->join('hris.karyawan k', 'pm.recid_karyawan = k.recid_karyawan', 'left');
        
        if (isset($filters['tanggal'])) {
            $this->db2->where('DATE(a.waktu)', $filters['tanggal']);
        }
        
        if (isset($filters['nik'])) {
            $this->db2->where('a.nik', $filters['nik']);
        }
        
        if (isset($filters['status']) && $filters['status'] !== null && $filters['status'] !== '') {
            $this->db2->where('a.status', $filters['status']);
        }
        
        if (isset($filters['kosong']) && $filters['kosong'] == '1') {
            $this->db2->where('k.nama_karyawan IS NULL');
        }
        
        $this->db2->order_by('a.waktu', 'DESC');
        return $this->db2->get()->result_array();
    }

    /**
     * Get karyawan with PIN for sync
     */
    public function get_karyawan_with_pin()
    {
        return $this->db->select('nik, nama_karyawan, pin')
                        ->from('karyawan')
                        ->where('pin IS NOT NULL')
                        ->where('pin !=', '')
                        ->limit(20)
                        ->get()
                        ->result_array();
    }

    /**
     * Get sample absensi data
     */
    public function get_sample_absensi()
    {
        return $this->db2->select('nik, uid, MAX(id) as id')
                         ->from('absensi')
                         ->group_by('nik, uid')
                         ->limit(20)
                         ->get()
                         ->result_array();
    }

    /**
     * Update karyawan PIN by nama
     */
    public function update_karyawan_pin_by_name($name, $pin)
    {
        $karyawan = $this->db->where('UPPER(TRIM(nama_karyawan))', strtoupper($name))
                             ->get('karyawan')
                             ->row_array();
        
        if ($karyawan) {
            $this->db->where('recid_karyawan', $karyawan['recid_karyawan'])
                     ->update('karyawan', ['pin' => $pin]);
            return $karyawan;
        }
        
        return null;
    }

    /**
     * Get all active karyawan
     */
    public function get_active_karyawan($bagian = null)
    {
        $this->db->select('recid_karyawan, nik, nama_karyawan, recid_bag')
                 ->where('sts_aktif', 'AKTIF');
        
        if (!empty($bagian)) {
            $this->db->where('recid_bag', $bagian);
        }
        
        return $this->db->order_by('nama_karyawan', 'ASC')
                        ->get('karyawan')
                        ->result_array();
    }

    /**
     * Get all active karyawan with bagian and jabatan
     */
    public function get_active_karyawan_with_details($bagian = null)
    {
        $this->db->select('k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, b.nama_bag as nama_bag, j.nama_jbtn as nama_jabatan')
                 ->from('karyawan k')
                 ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                 ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                 ->where('k.sts_aktif', 'AKTIF');
        
        if (!empty($bagian)) {
            $this->db->where('k.recid_bag', $bagian);
        }
        
        return $this->db->order_by('k.nama_karyawan', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Get daily attendance data by date
     */
    public function get_daily_attendance($tanggal)
    {
        $query = "
            SELECT 
                k.nik,
                k.nama_karyawan,
                k.recid_karyawan,
                MIN(TIME(a.waktu)) as jam_masuk,
                CASE 
                    WHEN COUNT(a.id) > 1 
                         AND MIN(TIME(a.waktu)) != MAX(TIME(a.waktu))
                         AND TIMESTAMPDIFF(HOUR, MIN(a.waktu), MAX(a.waktu)) >= 4
                    THEN TIME(MAX(a.waktu))
                    ELSE NULL
                END as jam_pulang,
                COUNT(a.id) as total_tap,
                GROUP_CONCAT(DISTINCT pm.pin ORDER BY pm.pin SEPARATOR ', ') as mapped_pins,
                COUNT(DISTINCT pm.pin) as total_pins,
                TIMESTAMPDIFF(HOUR, MIN(a.waktu), MAX(a.waktu)) as selisih_jam
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm 
                ON k.recid_karyawan = pm.recid_karyawan
            LEFT JOIN master_finger.absensi a 
                ON pm.pin = a.nik 
                AND DATE(a.waktu) = ?
            WHERE k.sts_aktif = 'AKTIF'
            GROUP BY k.nik, k.nama_karyawan, k.recid_karyawan
            ORDER BY k.nama_karyawan ASC
        ";
        
        return $this->db->query($query, [$tanggal])->result();
    }

    /**
     * Get attendance data for date range
     */
    public function get_attendance_by_date_range($start_date, $end_date)
    {
        $query = "
            SELECT 
                k.recid_karyawan,
                k.nik,
                k.nama_karyawan,
                DATE(a.waktu) as tanggal_absen,
                COUNT(a.id) as total_tap,
                MIN(TIME(a.waktu)) as jam_masuk,
                MAX(TIME(a.waktu)) as jam_pulang
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
            LEFT JOIN master_finger.absensi a ON pm.pin = a.nik 
                AND DATE(a.waktu) BETWEEN ? AND ?
            WHERE k.sts_aktif = 'AKTIF'
            GROUP BY k.recid_karyawan, k.nik, k.nama_karyawan, DATE(a.waktu)
            HAVING tanggal_absen IS NOT NULL
            ORDER BY k.nama_karyawan ASC
        ";
        
        return $this->db->query($query, [$start_date, $end_date])->result_array();
    }

    /**
     * Get attendance summary for manual entry
     */
    public function get_attendance_summary($tanggal)
    {
        $query = "
            SELECT 
                k.recid_karyawan,
                k.nik,
                MIN(TIME(a.waktu)) as jam_masuk,
                MAX(TIME(a.waktu)) as jam_pulang,
                COUNT(a.id) as total_tap
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
            LEFT JOIN master_finger.absensi a ON pm.pin = a.nik AND DATE(a.waktu) = ?
            WHERE k.sts_aktif = 'AKTIF'
            GROUP BY k.recid_karyawan, k.nik
            HAVING COUNT(a.id) > 0
        ";
        
        return $this->db->query($query, [$tanggal])->result_array();
    }

    /**
     * Get karyawan dinas (business trip) by date
     */
    public function get_karyawan_dinas($tanggal)
    {
        $query = "
            SELECT 
                dk.recid_karyawan,
                dk.recid_dinas,
                dk.tanggal_mulai,
                dk.tanggal_selesai,
                dk.keterangan_dinas,
                dk.lokasi_dinas,
                dk.nomor_surat,
                dk.status_dinas
            FROM master_finger.dinas_karyawan dk
            WHERE dk.is_delete = '0'
              AND dk.status_dinas = 'Disetujui'
              AND ? BETWEEN dk.tanggal_mulai AND dk.tanggal_selesai
        ";
        
        return $this->db2->query($query, [$tanggal])->result_array();
    }

    /**
     * Get karyawan by recid
     */
    public function get_karyawan_by_recid($recid_karyawan)
    {
        return $this->db->select('nik, nama_karyawan')
                        ->where('recid_karyawan', $recid_karyawan)
                        ->get('karyawan')
                        ->row();
    }

    /**
     * Get PIN mapping for karyawan
     */
    public function get_pin_by_recid($recid_karyawan)
    {
        return $this->db->select('pin')
                        ->where('recid_karyawan', $recid_karyawan)
                        ->get('karyawan_pin_map')
                        ->row();
    }

    /**
     * Check if attendance exists for date
     */
    public function check_attendance_exists($pin, $tanggal)
    {
        $query = "SELECT COUNT(*) as total FROM absensi WHERE nik = ? AND DATE(waktu) = ?";
        return $this->db2->query($query, [$pin, $tanggal])->row();
    }

    /**
     * Insert manual attendance
     */
    public function insert_manual_attendance($data)
    {
        return $this->db2->insert('absensi', $data);
    }

    /**
     * Get all active karyawan for mapping
     */
    public function get_karyawan_for_mapping()
    {
        return $this->db->select('recid_karyawan, nik, nama_karyawan')
                        ->where('sts_aktif', 'AKTIF')
                        ->get('karyawan')
                        ->result_array();
    }

    /**
     * Check if PIN mapping exists
     */
    public function check_pin_mapping_exists($pin, $recid_karyawan, $device_sn = null)
    {
        $where = [
            'pin' => $pin,
            'recid_karyawan' => $recid_karyawan
        ];
        
        if (!empty($device_sn)) {
            $where['device_sn'] = $device_sn;
        }
        
        return $this->db->where($where)->get('karyawan_pin_map')->num_rows();
    }

    /**
     * Insert PIN mapping
     */
    public function insert_pin_mapping($data)
    {
        return $this->db->insert('karyawan_pin_map', $data);
    }

    /**
     * Get mapping status summary
     */
    public function get_mapping_status()
    {
        // Total karyawan aktif
        $total_karyawan = $this->db->where('sts_aktif', 'AKTIF')->count_all_results('karyawan');
        
        // Total karyawan yang sudah dimapping
        $query = "
            SELECT COUNT(DISTINCT k.recid_karyawan) as total_mapped
            FROM hris.karyawan k
            INNER JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
            WHERE k.sts_aktif = 'AKTIF'
        ";
        $total_mapped = $this->db->query($query)->row()->total_mapped;
        
        // Total PIN di table mapping
        $total_pins = $this->db->count_all_results('karyawan_pin_map');
        
        return [
            'total_karyawan_aktif' => $total_karyawan,
            'total_mapped' => $total_mapped,
            'total_unmapped' => $total_karyawan - $total_mapped,
            'total_pins' => $total_pins
        ];
    }

    /**
     * Get unmapped karyawan list
     */
    public function get_unmapped_karyawan()
    {
        $query = "
            SELECT k.recid_karyawan, k.nik, k.nama_karyawan
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
            WHERE k.sts_aktif = 'AKTIF'
            AND pm.pin IS NULL
            ORDER BY k.nama_karyawan ASC
        ";
        
        return $this->db->query($query)->result_array();
    }

    /**
     * Get karyawan by NIK
     */
    public function get_karyawan_by_nik($nik)
    {
        return $this->db->select('recid_karyawan, nama_karyawan')
                        ->from('karyawan')
                        ->where('nik', $nik)
                        ->get()
                        ->row();
    }

    /**
     * Get PIN mappings for karyawan
     */
    public function get_pin_mappings($limit = null, $offset = 0)
    {
        $this->db->select('pm.*, k.nama_karyawan, k.nik');
        $this->db->from('karyawan_pin_map pm');
        $this->db->join('karyawan k', 'pm.recid_karyawan = k.recid_karyawan', 'left');
        $this->db->order_by('pm.recid_karyawan', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get tap records by PIN and date
     */
    public function get_taps_by_pin_date($pin_list, $tanggal)
    {
        if (empty($pin_list)) {
            return [];
        }
        
        return $this->db2->select('id, nik, waktu, uid')
                         ->from('absensi')
                         ->where('DATE(waktu)', $tanggal)
                         ->where_in('nik', $pin_list)
                         ->order_by('waktu', 'ASC')
                         ->get()
                         ->result();
    }

    /**
     * Get bagian by recid
     */
    public function get_bagian_by_recid($recid_bag)
    {
        return $this->db->select('nama_bag')
                        ->where('recid_bag', $recid_bag)
                        ->get('bagian')
                        ->row();
    }

    /**
     * Get karyawan with PIN (for sync)
     */
    public function get_karyawan_with_pin_for_sync()
    {
        return $this->db->select('recid_karyawan, nik, nama_karyawan, pin')
                        ->where('sts_aktif', 'AKTIF')
                        ->get('karyawan')
                        ->result_array();
    }

    /**
     * Get rekap bulanan (dari 20 bulan sebelumnya s/d 19 bulan selanjutnya)
     * OPTIMIZED: Single query instead of N+1 queries
     */
    public function get_rekap_bulanan($start_date, $end_date)
    {
        $query = "
            SELECT 
                k.recid_karyawan,
                k.nik,
                k.nama_karyawan,
                COUNT(CASE WHEN TIME(a.waktu) <= '08:00:00' THEN 1 END) as tepat_waktu,
                COUNT(CASE WHEN TIME(a.waktu) > '08:00:00' AND TIME(a.waktu) <= '09:00:00' THEN 1 END) as terlambat,
                COUNT(DISTINCT DATE(a.waktu)) as hari_masuk
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
            LEFT JOIN master_finger.absensi a ON pm.pin = a.nik 
                AND DATE(a.waktu) BETWEEN ? AND ?
            WHERE k.sts_aktif = 'AKTIF'
            GROUP BY k.recid_karyawan, k.nik, k.nama_karyawan
            ORDER BY k.nama_karyawan ASC
        ";
        
        $result = $this->db->query($query, [$start_date, $end_date])->result_array();
        
        // Calculate business days once (more efficient)
        $periode_days = $this->_count_business_days($start_date, $end_date);
        
        // Process results
        $rekap = [];
        foreach ($result as $row) {
            $hari_masuk = (int)$row['hari_masuk'];
            $tidak_masuk = max(0, $periode_days - $hari_masuk);
            
            $rekap[] = [
                'nik' => $row['nik'],
                'nama_karyawan' => $row['nama_karyawan'],
                'tepat_waktu' => (int)$row['tepat_waktu'],
                'terlambat' => (int)$row['terlambat'],
                'tidak_masuk' => $tidak_masuk,
                'ijin_cuti' => 0,
                'keterangan' => '-'
            ];
        }
        
        return $rekap;
    }

    /**
     * Count business days (exclude weekends)
     */
    private function _count_business_days($start_date, $end_date)
    {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('+1 day');
        
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        
        $count = 0;
        foreach ($period as $date) {
            // 0 = Sunday, 6 = Saturday
            if ($date->format('w') != 0 && $date->format('w') != 6) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get history absensi per baris per karyawan (Masuk & Pulang terpisah)
     * OPTIMIZED dengan single query
     */
    public function get_history_absensi($start_date, $end_date)
    {
        $query = "
            SELECT DISTINCT
                k.nik,
                k.nama_karyawan,
                DATE(a.waktu) as tanggal,
                TIME(a.waktu) as jam,
                CASE 
                    WHEN HOUR(a.waktu) >= 12 THEN 'PM'
                    ELSE 'AM'
                END as am_pm,
                CASE 
                    WHEN TIME(a.waktu) >= '12:00:00' THEN 'C/Out'
                    ELSE 'C/In'
                END as state
            FROM hris.karyawan k
            INNER JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan AND pm.pin IS NOT NULL
            INNER JOIN master_finger.absensi a ON pm.pin = a.nik
            WHERE k.sts_aktif = 'AKTIF'
                AND DATE(a.waktu) BETWEEN ? AND ?
            ORDER BY k.nama_karyawan ASC, DATE(a.waktu) ASC, TIME(a.waktu) ASC
            LIMIT 10000
        ";
        
        return $this->db->query($query, [$start_date, $end_date])->result_array();
    }
}

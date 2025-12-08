<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
require_once APPPATH . 'third_party/zklib/zklib.php';

class Rekap extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('M_Rekap');
        $this->db2 = $this->load->database('absen2', TRUE); // master_finger
    }

    public function tarik_ui()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/tarik_data_view', $data);
        $this->load->view('layout/a_footer');
    }
    
    public function tarik()
    {
        header('Content-Type: application/json');
        
        $mesin_id = $this->input->get('mesin') ?: $this->input->post('mesin') ?: '1';
        
        // Daftar IP mesin
        $mesin_list = [
            '1' => ['ip' => '192.168.9.201', 'port' => 4370, 'nama' => 'Mesin 1'],
            '2' => ['ip' => '192.168.9.202', 'port' => 4370, 'nama' => 'Mesin 2'],
            '3' => ['ip' => '192.168.9.203', 'port' => 4370, 'nama' => 'Mesin 3'],
            '4' => ['ip' => '192.168.9.204', 'port' => 4370, 'nama' => 'Mesin 4'],
            '5' => ['ip' => '192.168.9.205', 'port' => 4370, 'nama' => 'Mesin 5'],
            '6' => ['ip' => '192.168.9.206', 'port' => 4370, 'nama' => 'Mesin 6'],
            '7' => ['ip' => '192.168.9.207', 'port' => 4370, 'nama' => 'Mesin 7'],
            '8' => ['ip' => '192.168.9.208', 'port' => 4370, 'nama' => 'Mesin 8'],
            '9' => ['ip' => '192.168.9.209', 'port' => 4370, 'nama' => 'Mesin 9'],
        ];
        
        if (!isset($mesin_list[$mesin_id])) {
            echo json_encode(['success' => false, 'message' => 'Mesin tidak ditemukan']);
            return;
        }
        
        $mesin = $mesin_list[$mesin_id];
        $ip = $mesin['ip'];
        $port = $mesin['port'];
        
        $result = [
            'success' => false,
            'message' => '',
            'data' => [],
            'info' => []
        ];
        
        try {
            if (!function_exists('socket_create')) {
                $result['message'] = 'PHP Socket extension tidak aktif';
                echo json_encode($result, JSON_PRETTY_PRINT);
                return;
            }
            
            $zk = new ZKLib($ip, $port);
            $connect = $zk->connect();
            
            if ($connect) {
                $result['message'] = 'Berhasil terhubung ke ' . $mesin['nama'];
                $result['info'] = [
                    'nama_mesin' => $mesin['nama'],
                    'ip' => $ip,
                    'port' => $port,
                    'device_name' => $zk->deviceName(),
                    'serial_number' => $zk->serialNumber(),
                    'platform' => $zk->platform(),
                    'firmware_version' => $zk->fmVersion(),
                    'device_time' => $zk->getTime()
                ];
                
                $zk->disableDevice();
                $attendance = $zk->getAttendance();
                $zk->enableDevice();
                $zk->disconnect();
                
                $result['success'] = true;
                $result['data'] = $attendance;
                $result['total'] = count($attendance);
                
                $saved = $this->simpan_ke_db($attendance, $ip);
                $result['saved'] = $saved['inserted'];
                $result['duplicate'] = $saved['duplicate'];
                
            } else {
                $result['message'] = 'Gagal terhubung ke mesin fingerprint';
            }
            
        } catch (Exception $e) {
            $result['message'] = 'Error: ' . $e->getMessage();
        }
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    private function simpan_ke_db($data, $ip)
    {
        $inserted = 0;
        $duplicate = 0;
        
        foreach ($data as $row) {
            $exists = $this->db2->where('uid', $row['uid'])
                                 ->where('nik', $row['id'])
                                 ->where('waktu', $row['timestamp'])
                                 ->get('absensi')
                                 ->num_rows();
            
            if ($exists == 0) {
                $insert_data = [
                    'uid' => $row['uid'],
                    'nik' => $row['id'],
                    'status' => $row['state'],
                    'waktu' => $row['timestamp'],
                    'tipe' => isset($row['type']) ? $row['type'] : 0,
                    'ip_mesin' => $ip
                ];
                
                if ($this->db2->insert('absensi', $insert_data)) {
                    $inserted++;
                }
            } else {
                $duplicate++;
            }
        }
        
        return [
            'inserted' => $inserted,
            'duplicate' => $duplicate
        ];
    }
    
    // Mapping data absensi dengan karyawan (filter: tanggal, nik, status, kosong)
    public function mapping()
    {
        header('Content-Type: application/json');
        
        $result = [
            'success' => false,
            'message' => '',
            'data' => []
        ];
        
        try {
            $this->db2->select('a.id, a.uid, a.nik as userid_mesin, a.status, a.waktu, a.tipe, a.ip_mesin, k.nama_karyawan, k.nik as nik_karyawan, pm.pin');
            $this->db2->from('absensi a');
            $this->db2->join('(SELECT DISTINCT pin, recid_karyawan FROM hris.karyawan_pin_map) pm', 'a.nik = pm.pin', 'left');
            $this->db2->join('hris.karyawan k', 'pm.recid_karyawan = k.recid_karyawan', 'left');
            
            if ($this->input->get('tanggal')) {
                $this->db2->where('DATE(a.waktu)', $this->input->get('tanggal'));
            }
            
            if ($this->input->get('nik')) {
                $this->db2->where('a.nik', $this->input->get('nik'));
            }
            
            if ($this->input->get('status') !== null && $this->input->get('status') !== '') {
                $this->db2->where('a.status', $this->input->get('status'));
            }
            
            if ($this->input->get('kosong') == '1') {
                $this->db2->where('k.nama_karyawan IS NULL');
            }
            
            $this->db2->order_by('a.waktu', 'DESC');
            $query = $this->db2->get();
            
            if ($query->num_rows() > 0) {
                $data = $query->result_array();
                $total = count($data);
                $ada_nama = 0;
                $tidak_ada_nama = 0;
                $nik_tidak_ditemukan = [];
                
                foreach ($data as $row) {
                    if (!empty($row['nama_karyawan'])) {
                        $ada_nama++;
                    } else {
                        $tidak_ada_nama++;
                        if (!in_array($row['userid_mesin'], $nik_tidak_ditemukan)) {
                            $nik_tidak_ditemukan[] = $row['userid_mesin'];
                        }
                    }
                }
                
                $result['success'] = true;
                $result['message'] = 'Data berhasil dimapping';
                $result['data'] = $data;
                $result['statistik'] = [
                    'total' => $total,
                    'ada_nama' => $ada_nama,
                    'tidak_ada_nama' => $tidak_ada_nama,
                    'persentase_match' => $total > 0 ? round(($ada_nama / $total) * 100, 2) . '%' : '0%',
                    'nik_tidak_ditemukan' => $nik_tidak_ditemukan
                ];
            } else {
                $result['message'] = 'Tidak ada data absensi';
            }
            
        } catch (Exception $e) {
            $result['message'] = 'Error: ' . $e->getMessage();
        }
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    public function cek()
    {
        header('Content-Type: application/json');
        
        $result = [
            'success' => false,
            'message' => '',
            'data' => []
        ];
        
        try {
            $fields = $this->db->list_fields('karyawan');
            $karyawan = $this->db->select('nik, nama_karyawan, pin')->from('karyawan')->where('pin IS NOT NULL')->where('pin !=', '')->limit(20)->get()->result_array();
            $absensi = $this->db2->select('nik, uid, MAX(id) as id')->from('absensi')->group_by('nik, uid')->limit(20)->get()->result_array();
            
            $ip = '192.168.9.201';
            $port = 4370;
            $users_mesin = [];
            
            try {
                $zk = new ZKLib($ip, $port);
                if ($zk->connect()) {
                    $zk->disableDevice();
                    $users_mesin = $zk->getUser();
                    $zk->enableDevice();
                    $zk->disconnect();
                }
            } catch (Exception $e) {
            }
            
            $result['success'] = true;
            $result['message'] = 'Data struktur berhasil diambil';
            $result['data'] = [
                'kolom_karyawan' => $fields,
                'sample_karyawan' => $karyawan,
                'sample_nik_absensi' => $absensi,
                'sample_users_mesin' => array_slice($users_mesin, 0, 10)
            ];
            
        } catch (Exception $e) {
            $result['message'] = 'Error: ' . $e->getMessage();
        }
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    // Sync userid dari mesin ke kolom PIN table karyawan (mapping berdasarkan nama)
    public function sync()
    {
        header('Content-Type: application/json');
        
        $result = [
            'success' => false,
            'message' => '',
            'data' => []
        ];
        
        try {
            $ip = '192.168.9.201';
            $port = 4370;
            $zk = new ZKLib($ip, $port);
            
            if (!$zk->connect()) {
                $result['message'] = 'Gagal terhubung ke mesin fingerprint';
                echo json_encode($result, JSON_PRETTY_PRINT);
                return;
            }
            
            $zk->disableDevice();
            $users = $zk->getUser();
            $zk->enableDevice();
            $zk->disconnect();
            
            $updated = 0;
            $not_found = 0;
            $detail = [];
            
            foreach ($users as $user) {
                $userid = $user['userid'];
                $name = trim($user['name']);
                
                $karyawan = $this->db->where('UPPER(TRIM(nama_karyawan))', strtoupper($name))->get('karyawan')->row_array();
                
                if ($karyawan) {
                    $this->db->where('recid_karyawan', $karyawan['recid_karyawan'])->update('karyawan', ['pin' => $userid]);
                    $updated++;
                    $detail[] = [
                        'userid' => $userid,
                        'nama_mesin' => $name,
                        'nik' => $karyawan['nik'],
                        'nama_karyawan' => $karyawan['nama_karyawan'],
                        'status' => 'Updated'
                    ];
                } else {
                    $not_found++;
                    $detail[] = [
                        'userid' => $userid,
                        'nama_mesin' => $name,
                        'status' => 'Not Found'
                    ];
                }
            }
            
            $result['success'] = true;
            $result['message'] = "Sync selesai. $updated data berhasil di-update, $not_found tidak ditemukan.";
            $result['statistik'] = [
                'total_user_mesin' => count($users),
                'updated' => $updated,
                'not_found' => $not_found
            ];
            $result['detail'] = $detail;
            
        } catch (Exception $e) {
            $result['message'] = 'Error: ' . $e->getMessage();
        }
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    
    // Tarik data dari 9 mesin sekaligus
    public function tarik_semua()
    {
        header('Content-Type: application/json');
        set_time_limit(600); // 10 menit untuk 9 mesin
        
        // Daftar 9 mesin fingerprint (sesuaikan dengan IP mesin Anda)
        $mesin = [
            ['ip' => '192.168.9.201', 'port' => 4370, 'nama' => 'Mesin 1'],
            ['ip' => '192.168.9.202', 'port' => 4370, 'nama' => 'Mesin 2'],
            ['ip' => '192.168.9.203', 'port' => 4370, 'nama' => 'Mesin 3'],
            ['ip' => '192.168.9.204', 'port' => 4370, 'nama' => 'Mesin 4'],
            ['ip' => '192.168.9.205', 'port' => 4370, 'nama' => 'Mesin 5'],
            ['ip' => '192.168.9.206', 'port' => 4370, 'nama' => 'Mesin 6'],
            ['ip' => '192.168.9.207', 'port' => 4370, 'nama' => 'Mesin 7'],
            ['ip' => '192.168.9.208', 'port' => 4370, 'nama' => 'Mesin 8'],
            ['ip' => '192.168.9.209', 'port' => 4370, 'nama' => 'Mesin 9'],
        ];
        
        $results = [];
        $total_start = microtime(true);
        $total_saved = 0;
        $total_data = 0;
        $success_count = 0;
        $failed_count = 0;
        
        foreach ($mesin as $m) {
            $start = microtime(true);
            
            try {
                $zk = new ZKLib($m['ip'], $m['port']);
                
                if ($zk->connect()) {
                    $zk->disableDevice();
                    $attendance = $zk->getAttendance();
                    $zk->enableDevice();
                    $zk->disconnect();
                    
                    $saved = $this->simpan_ke_db($attendance, $m['ip']);
                    
                    $results[] = [
                        'success' => true,
                        'nama' => $m['nama'],
                        'ip' => $m['ip'],
                        'total_data' => count($attendance),
                        'saved' => $saved['inserted'],
                        'duplicate' => $saved['duplicate'],
                        'waktu' => round((microtime(true) - $start) * 1000, 2) . ' ms'
                    ];
                    
                    $total_saved += $saved['inserted'];
                    $total_data += count($attendance);
                    $success_count++;
                } else {
                    $results[] = [
                        'success' => false,
                        'nama' => $m['nama'],
                        'ip' => $m['ip'],
                        'message' => 'Gagal koneksi',
                        'waktu' => round((microtime(true) - $start) * 1000, 2) . ' ms'
                    ];
                    $failed_count++;
                }
            } catch (Exception $e) {
                $results[] = [
                    'success' => false,
                    'nama' => $m['nama'],
                    'ip' => $m['ip'],
                    'message' => 'Error: ' . $e->getMessage(),
                    'waktu' => round((microtime(true) - $start) * 1000, 2) . ' ms'
                ];
                $failed_count++;
            }
        }
        
        $total_time = round((microtime(true) - $total_start) * 1000, 2);
        
        echo json_encode([
            'success' => true,
            'message' => "Selesai tarik data dari 9 mesin",
            'summary' => [
                'total_mesin' => count($mesin),
                'berhasil' => $success_count,
                'gagal' => $failed_count,
                'total_data' => $total_data,
                'total_saved' => $total_saved,
                'total_waktu' => $total_time . ' ms',
                'total_waktu_menit' => round($total_time / 1000 / 60, 2) . ' menit'
            ],
            'detail' => $results
        ], JSON_PRETTY_PRINT);
    }
    
    // Halaman UI untuk rebuild mapping karyawan_pin_map
    public function mapping_ui()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/mapping_view', $data);
        $this->load->view('layout/a_footer');
    }
    
    public function rebuild_mapping()
    {
        header('Content-Type: application/json');
        set_time_limit(120);
        
        $mesin_id = $this->input->post('mesin_id') ?: '1';
        
        // Daftar 9 mesin fingerprint
        $mesin_list = [
            '1' => ['ip' => '192.168.9.201', 'port' => 4370, 'nama' => 'Mesin 1'],
            '2' => ['ip' => '192.168.9.202', 'port' => 4370, 'nama' => 'Mesin 2'],
            '3' => ['ip' => '192.168.9.203', 'port' => 4370, 'nama' => 'Mesin 3'],
            '4' => ['ip' => '192.168.9.204', 'port' => 4370, 'nama' => 'Mesin 4'],
            '5' => ['ip' => '192.168.9.205', 'port' => 4370, 'nama' => 'Mesin 5'],
            '6' => ['ip' => '192.168.9.206', 'port' => 4370, 'nama' => 'Mesin 6'],
            '7' => ['ip' => '192.168.9.207', 'port' => 4370, 'nama' => 'Mesin 7'],
            '8' => ['ip' => '192.168.9.208', 'port' => 4370, 'nama' => 'Mesin 8'],
            '9' => ['ip' => '192.168.9.209', 'port' => 4370, 'nama' => 'Mesin 9'],
        ];
        
        if (!isset($mesin_list[$mesin_id])) {
            echo json_encode(['success' => false, 'message' => 'Mesin tidak ditemukan']);
            return;
        }
        
        $mesin = $mesin_list[$mesin_id];
        
        try {
            // Step 1: Ambil semua karyawan aktif dari tabel karyawan
            log_message('debug', 'Starting rebuild mapping process');
            $karyawan_list = $this->db->select('recid_karyawan, nik, nama_karyawan')
                                      ->where('sts_aktif', 'AKTIF')
                                      ->get('karyawan')
                                      ->result_array();
            log_message('debug', 'Retrieved ' . count($karyawan_list) . ' active karyawan records');
            
            // Buat mapping karyawan berdasarkan NIK
            $karyawan_by_nik = [];
            
            log_message('debug', 'Total karyawan loaded: ' . count($karyawan_list));
            
            foreach ($karyawan_list as $k) {
                // Validasi recid_karyawan
                if (!is_numeric($k['recid_karyawan'])) {
                    continue;
                }
                
                // Map berdasarkan NIK
                if (!empty($k['nik'])) {
                    $nik_clean = strtoupper(trim($k['nik']));
                    $karyawan_by_nik[$nik_clean] = $k;
                }
            }
            
            log_message('debug', 'Total indexed by NIK: ' . count($karyawan_by_nik));
            
            // Step 2: Tarik user dari mesin fingerprint
            log_message('debug', 'Connecting to fingerprint machine: ' . $mesin['ip'] . ':' . $mesin['port']);
            $zk = new ZKLib($mesin['ip'], $mesin['port']);
            
            if (!$zk->connect()) {
                log_message('error', 'Failed to connect to fingerprint machine: ' . $mesin['ip'] . ':' . $mesin['port']);
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal terhubung ke ' . $mesin['nama']
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $zk->disableDevice();
            $users = $zk->getUser();
            $device_sn = $zk->serialNumber();
            $zk->enableDevice();
            $zk->disconnect();
            log_message('debug', 'Retrieved ' . count($users) . ' users from fingerprint machine');
            log_message('debug', 'Device serial number: ' . $device_sn);
            
            // Step 3: Koneksi ke database SQL Server DBfinger
            $dbfinger = null;
            try {
                log_message('debug', 'Attempting to connect to DBfinger database');
                // Load the database connection for DBfinger (SQLSRV)
                $dbfinger = $this->load->database('DBfinger', TRUE);
                
                // Test if the connection is working
                if ($dbfinger) {
                    log_message('debug', 'DBfinger connection successful');
                }
            } catch (Exception $e) {
                log_message('error', 'DBfinger connection error: ' . $e->getMessage());
                $dbfinger = null;
            } catch (Error $e) {
                log_message('error', 'DBfinger connection error (Error): ' . $e->getMessage());
                $dbfinger = null;
            }
            
            // Step 4: Cocokkan antara user mesin dengan tabel karyawan dan DBfinger
            $total_mapped = 0;
            $total_unmapped = 0;
            $total_duplicate = 0;
            $mapped_by_pin_ssn = 0;
            $mapped_by_cardno_nik = 0;
            $mapped_by_nama = 0;
            $unmapped_users = [];
            
            log_message('debug', 'Starting user matching process for ' . count($users) . ' users');
            
            foreach ($users as $user) {
                $pin_dari_mesin = $user['userid']; // PIN dari mesin
                $nama_mesin = trim($user['name']);
                $cardno = !empty($user['cardno']) ? trim($user['cardno']) : ''; // Card No (NIK) dari mesin
                
                // Step 5: Cocokkan PIN dari mesin dengan BADGENUMBER di DBfinger
                // dan ambil SSN serta NAME dari tabel USERINFO
                $userinfo = [];
                if ($dbfinger) {
                    try {
                        $userinfo_query = $dbfinger->query("SELECT SSN, NAME FROM USERINFO WHERE BADGENUMBER = ?", [$pin_dari_mesin]);
                        
                        if ($userinfo_query && $userinfo_query->num_rows() > 0) {
                            $userinfo = $userinfo_query->row_array();
                            log_message('debug', 'Found USERINFO for PIN ' . $pin_dari_mesin . ' - SSN: ' . (isset($userinfo['SSN']) ? $userinfo['SSN'] : 'N/A') . ', Name: ' . (isset($userinfo['NAME']) ? $userinfo['NAME'] : 'N/A'));
                        } else {
                            log_message('debug', 'No USERINFO found for PIN ' . $pin_dari_mesin);
                        }
                    } catch (Exception $e) {
                        log_message('error', 'Error querying USERINFO table for PIN ' . $pin_dari_mesin . ': ' . $e->getMessage());
                        $userinfo = [];
                    } catch (Error $e) {
                        log_message('error', 'Error querying USERINFO table for PIN ' . $pin_dari_mesin . ' (Error): ' . $e->getMessage());
                        $userinfo = [];
                    }
                }
                
                $karyawan = null;
                $match_method = '';
                
                // PRIORITAS 1: Matching berdasarkan PIN/BADGENUMBER (dari mesin ke DBfinger)
                // Jika berhasil dapat data dari USERINFO, coba cocokkan SSN dengan NIK karyawan
                if (!empty($userinfo) && !empty($userinfo['SSN'])) {
                    $ssn_clean = strtoupper(trim($userinfo['SSN']));
                    log_message('debug', 'Trying to match SSN: ' . $ssn_clean . ' for PIN: ' . $pin_dari_mesin);
                    
                    if (isset($karyawan_by_nik[$ssn_clean])) {
                        $karyawan = $karyawan_by_nik[$ssn_clean];
                        $match_method = 'pin_ssn';
                        $mapped_by_pin_ssn++;
                        log_message('debug', 'Matched by PIN/SSN - PIN: ' . $pin_dari_mesin . ', SSN: ' . $ssn_clean . ', Name: ' . $nama_mesin . ', RecID: ' . $karyawan['recid_karyawan']);
                    } else {
                        log_message('debug', 'No match found for SSN: ' . $ssn_clean . ' in karyawan_by_nik');
                    }
                }
                
                // PRIORITAS 2: Matching berdasarkan NIK (Card No dari mesin)
                if (!$karyawan && !empty($cardno)) {
                    $cardno_clean = strtoupper(trim($cardno));
                    log_message('debug', 'Trying to match CardNo: ' . $cardno_clean . ' for PIN: ' . $pin_dari_mesin);
                    if (isset($karyawan_by_nik[$cardno_clean])) {
                        $karyawan = $karyawan_by_nik[$cardno_clean];
                        $match_method = 'nik';
                        $mapped_by_cardno_nik++;
                        log_message('debug', 'Matched by CardNo/NIK - PIN: ' . $pin_dari_mesin . ', CardNo: ' . $cardno_clean . ', Name: ' . $nama_mesin . ', RecID: ' . $karyawan['recid_karyawan']);
                    } else {
                        log_message('debug', 'No match found for CardNo: ' . $cardno_clean . ' in karyawan_by_nik');
                    }
                }
                
                // PRIORITAS 3: Fallback ke matching nama (jika PIN dan NIK tidak cocok)
                if (!$karyawan) {
                    $nama_normalized = strtoupper(trim(preg_replace('/\s+/', ' ', $nama_mesin)));
                    // Buat juga mapping karyawan berdasarkan nama untuk fallback
                    $karyawan_by_nama = [];
                    foreach ($karyawan_list as $k) {
                        $k_nama_normalized = strtoupper(trim(preg_replace('/\s+/', ' ', $k['nama_karyawan'])));
                        $karyawan_by_nama[$k_nama_normalized] = $k;
                    }
                    
                    if (isset($karyawan_by_nama[$nama_normalized])) {
                        $karyawan = $karyawan_by_nama[$nama_normalized];
                        $match_method = 'nama';
                        $mapped_by_nama++;
                        log_message('debug', 'Matched by Name - PIN: ' . $pin_dari_mesin . ', Name: ' . $nama_mesin . ', Normalized: ' . $nama_normalized . ', RecID: ' . $karyawan['recid_karyawan']);
                    }
                }
                
                // Step 6: Jika ada karyawan yang cocok, insert ke karyawan_pin_map
                if ($karyawan) {
                    // Validasi recid_karyawan
                    if (!is_numeric($karyawan['recid_karyawan']) || $karyawan['recid_karyawan'] <= 0) {
                        $total_unmapped++;
                        $unmapped_users[] = [
                            'pin' => $pin_dari_mesin,
                            'nama_mesin' => $nama_mesin,
                            'cardno' => $cardno,
                            'error' => 'Invalid recid_karyawan: ' . $karyawan['recid_karyawan']
                        ];
                        continue;
                    }
                    
                    // Validasi karyawan ada di tabel karyawan
                    $karyawan_exists = $this->db->where('recid_karyawan', $karyawan['recid_karyawan'])
                                                ->where('sts_aktif', 'AKTIF')
                                                ->get('karyawan')
                                                ->num_rows();
                    
                    if ($karyawan_exists == 0) {
                        $total_unmapped++;
                        $unmapped_users[] = [
                            'pin' => $pin_dari_mesin,
                            'nama_mesin' => $nama_mesin,
                            'cardno' => $cardno,
                            'error' => 'Karyawan tidak ditemukan di database: ' . $karyawan['recid_karyawan']
                        ];
                        continue;
                    }
                    
                    // Cek apakah sudah ada mapping untuk PIN ini
                    $exists_query = $this->db->where('pin', $pin_dari_mesin)
                                             ->where('recid_karyawan', $karyawan['recid_karyawan'])
                                             ->where('device_sn', $device_sn)
                                             ->get('karyawan_pin_map');
                    $exists = $exists_query->num_rows();
                    log_message('debug', 'Checking for existing mapping - PIN: ' . $pin_dari_mesin . ', RecID: ' . $karyawan['recid_karyawan'] . ', Device SN: ' . $device_sn . ', Exists: ' . $exists);
                    
                    if ($exists == 0) {
                        // Insert data mapping
                        $insert_data = [
                            'pin' => $pin_dari_mesin,
                            'recid_karyawan' => (int)$karyawan['recid_karyawan'],
                            'device_sn' => $device_sn,
                            'nama_di_mesin' => !empty($userinfo['NAME']) ? $userinfo['NAME'] : $nama_mesin, // Ambil nama dari USERINFO jika ada
                            'created_date' => date('Y-m-d')
                        ];
                        
                        log_message('debug', 'Attempting to insert mapping - PIN: ' . $pin_dari_mesin . ', RecID: ' . $karyawan['recid_karyawan'] . ', Device SN: ' . $device_sn);
                        
                        // Handle potential duplicate entry errors
                        try {
                            $this->db->insert('karyawan_pin_map', $insert_data);
                            if ($this->db->affected_rows() > 0) {
                                log_message('debug', 'Successfully inserted mapping for PIN: ' . $pin_dari_mesin);
                                $total_mapped++;
                            } else {
                                log_message('debug', 'No rows affected when inserting mapping for PIN: ' . $pin_dari_mesin . '. Error: ' . $this->db->error()['message']);
                            }
                        } catch (Exception $e) {
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                                log_message('debug', 'Duplicate entry ignored for PIN: ' . $pin_dari_mesin . '. Message: ' . $e->getMessage());
                                $total_duplicate++;
                            } else {
                                log_message('debug', 'Error inserting mapping for PIN: ' . $pin_dari_mesin . '. Error: ' . $e->getMessage());
                            }
                        }
                    } else {
                        $total_duplicate++;
                    }
                } else {
                    $total_unmapped++;
                    
                    // Create detailed error information
                    $error_info = [
                        'pin' => $pin_dari_mesin,
                        'nama_mesin' => $nama_mesin,
                        'cardno' => $cardno
                    ];
                    
                    // Add DBfinger data if available
                    if (!empty($userinfo)) {
                        $error_info['dbfinger_ssn'] = isset($userinfo['SSN']) ? $userinfo['SSN'] : '';
                        $error_info['dbfinger_name'] = isset($userinfo['NAME']) ? $userinfo['NAME'] : '';
                    }
                    
                    // Add matching attempt details
                    if (!empty($userinfo) && !empty($userinfo['SSN'])) {
                        $error_info['attempted_match_ssn'] = strtoupper(trim($userinfo['SSN']));
                    }
                    
                    if (!empty($cardno)) {
                        $error_info['attempted_match_cardno'] = strtoupper(trim($cardno));
                    }
                    
                    $unmapped_users[] = $error_info;
                }
            }
            
            // Step 7: Response
            log_message('debug', 'Mapping process completed - Total: ' . count($users) . ', Mapped: ' . $total_mapped . ', Unmapped: ' . $total_unmapped);
            echo json_encode([
                'success' => true,
                'message' => 'Mapping dari ' . $mesin['nama'] . ' selesai',
                'mesin' => [
                    'nama' => $mesin['nama'],
                    'ip' => $mesin['ip'],
                    'device_sn' => $device_sn
                ],
                'summary' => [
                    'total_users' => count($users),
                    'total_mapped' => $total_mapped,
                    'mapped_by_nik' => $mapped_by_pin_ssn + $mapped_by_cardno_nik, // Combined PIN-based and CardNo-based matching
                    'mapped_by_nama' => $mapped_by_nama,
                    'total_duplicate' => $total_duplicate,
                    'total_unmapped' => $total_unmapped,
                    'persentase_mapped' => count($users) > 0 ? round((($total_mapped + $total_duplicate) / count($users)) * 100, 2) . '%' : '0%'
                ],
                'unmapped_users' => $unmapped_users
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            log_message('error', 'rebuild_mapping error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // API: Lihat status mapping saat ini
    public function status_mapping()
    {
        header('Content-Type: application/json');
        
        try {
            // Total karyawan aktif
            $total_karyawan = $this->db->where('sts_aktif', 'AKTIF')->count_all_results('karyawan');
            
            // Total karyawan yang sudah dimapping
            $query = "
                SELECT COUNT(DISTINCT k.recid_karyawan) as total_mapped
                FROM hris.karyawan k
                INNER JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
                WHERE k.sts_aktif = 'AKTIF'
            ";
            $result = $this->db->query($query)->row();
            $total_mapped = $result ? $result->total_mapped : 0;
            
            // Total karyawan belum dimapping
            $total_unmapped = $total_karyawan - $total_mapped;
            
            // Total PIN di table mapping
            $total_pins = $this->db->count_all_results('karyawan_pin_map');
            
            // Karyawan belum mapping (detail)
            $query_unmapped = "
                SELECT k.recid_karyawan, k.nik, k.nama_karyawan
                FROM hris.karyawan k
                LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
                WHERE k.sts_aktif = 'AKTIF'
                AND pm.pin IS NULL
                ORDER BY k.nama_karyawan ASC
            ";
            $karyawan_unmapped = $this->db->query($query_unmapped)->result_array();
            
            echo json_encode([
                'success' => true,
                'summary' => [
                    'total_karyawan_aktif' => $total_karyawan,
                    'total_mapped' => $total_mapped,
                    'total_unmapped' => $total_unmapped,
                    'total_pins' => $total_pins,
                    'persentase_mapped' => $total_karyawan > 0 ? round(($total_mapped / $total_karyawan) * 100, 2) . '%' : '0%'
                ],
                'karyawan_unmapped' => $karyawan_unmapped
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // Cleanup function to remove invalid mappings
    public function cleanup_invalid_mappings()
    {
        header('Content-Type: application/json');
        
        try {
            // Remove mappings with non-existent karyawan
            $this->db->query("
                DELETE kpm FROM karyawan_pin_map kpm
                LEFT JOIN karyawan k ON kpm.recid_karyawan = k.recid_karyawan
                WHERE k.recid_karyawan IS NULL
            ");
            
            // Remove mappings with inactive karyawan
            $this->db->query("
                DELETE kpm FROM karyawan_pin_map kpm
                LEFT JOIN karyawan k ON kpm.recid_karyawan = k.recid_karyawan
                WHERE k.sts_aktif != 'AKTIF'
            ");
            
            // Remove duplicate mappings, keeping only the first one
            $this->db->query("
                DELETE kpm1 FROM karyawan_pin_map kpm1
                INNER JOIN karyawan_pin_map kpm2
                WHERE kpm1.id > kpm2.id
                AND kpm1.pin = kpm2.pin
                AND kpm1.recid_karyawan = kpm2.recid_karyawan
            ");
            
            echo json_encode([
                'success' => true,
                'message' => 'Invalid mappings cleaned up successfully'
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // Test function to verify mapping integrity
    public function test_mapping_integrity()
    {
        header('Content-Type: application/json');
        
        try {
            // Check for invalid recid_karyawan values
            $invalid_mappings = $this->db->query("
                SELECT kpm.id, kpm.pin, kpm.recid_karyawan, k.nama_karyawan
                FROM karyawan_pin_map kpm
                LEFT JOIN karyawan k ON kpm.recid_karyawan = k.recid_karyawan
                WHERE kpm.recid_karyawan IS NULL OR kpm.recid_karyawan <= 0
            ")->result_array();
            
            // Check for mappings with non-existent karyawan
            $orphaned_mappings = $this->db->query("
                SELECT kpm.id, kpm.pin, kpm.recid_karyawan
                FROM karyawan_pin_map kpm
                LEFT JOIN karyawan k ON kpm.recid_karyawan = k.recid_karyawan
                WHERE k.recid_karyawan IS NULL
            ")->result_array();
            
            // Check for mappings with inactive karyawan
            $inactive_mappings = $this->db->query("
                SELECT kpm.id, kpm.pin, kpm.recid_karyawan, k.nama_karyawan, k.sts_aktif
                FROM karyawan_pin_map kpm
                LEFT JOIN karyawan k ON kpm.recid_karyawan = k.recid_karyawan
                WHERE k.sts_aktif != 'AKTIF'
            ")->result_array();
            
            // Check for duplicate mappings
            $duplicate_mappings = $this->db->query("
                SELECT pin, recid_karyawan, COUNT(*) as count
                FROM karyawan_pin_map
                GROUP BY pin, recid_karyawan
                HAVING COUNT(*) > 1
            ")->result_array();
            
            echo json_encode([
                'success' => true,
                'invalid_mappings' => $invalid_mappings,
                'orphaned_mappings' => $orphaned_mappings,
                'inactive_mappings' => $inactive_mappings,
                'duplicate_mappings' => $duplicate_mappings
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    public function absensi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        
        $query = "
            SELECT 
                k.nik,
                k.nama_karyawan,
                k.recid_karyawan,
                b.nama_bag AS nama_bagian,
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
                TIMESTAMPDIFF(HOUR, MIN(a.waktu), MAX(a.waktu)) as selisih_jam,
                ja.jam_in AS jam_in_shift,
                ja.jam_out AS jam_out_shift,
                ja.keterangan AS nama_shift
            FROM hris.karyawan k
            LEFT JOIN hris.karyawan_pin_map pm 
                ON k.recid_karyawan = pm.recid_karyawan
            LEFT JOIN hris.bagian b
                ON k.recid_bag = b.recid_bag
            LEFT JOIN master_finger.absensi a 
                ON pm.pin = a.nik 
                AND DATE(a.waktu) = ?
            LEFT JOIN master_absen.jadwal_shift s
                ON s.recid_karyawan = k.recid_karyawan AND s.tgl_kerja = ? AND s.is_delete = '0'
            LEFT JOIN master_absen.jenis_absen ja
                ON ja.recid_jenisabsen = s.recid_jenisabsen
            WHERE k.sts_aktif = 'AKTIF'
            GROUP BY k.nik, k.nama_karyawan, k.recid_karyawan, b.nama_bag, ja.jam_in, ja.jam_out, ja.keterangan
            ORDER BY k.nama_karyawan ASC
        ";
        
        $all_data = $this->db->query($query, [$tanggal, $tanggal])->result();
        $data['tanggal'] = $tanggal;
        $data['jam_masuk_std'] = '07:30:00';
        
        // Pisahkan data: yang sudah absen vs belum absen
        $data['absensi'] = []; // Untuk table utama (yang sudah absen)
        $data['belum_absen'] = []; // Untuk modal (yang belum absen)
        
        $tepat_waktu = 0;
        $terlambat = 0;

        // Ambil daftar karyawan yang sudah memiliki izin pada tanggal tsb (master_finger)
        // sehingga TIDAK ditampilkan pada daftar "Belum Absen"
        $izin_map = [];
        try {
            $izin_rows = $this->db2->select('recid_karyawan, jenis')
                                    ->from('izin_absen')
                                    ->where('tgl_mulai', $tanggal)
                                    ->where('is_delete', '0')
                                    ->get()->result_array();
            foreach ($izin_rows as $iz) {
                $izin_map[(int)$iz['recid_karyawan']] = $iz['jenis'];
            }
        } catch (Exception $e) {
            // jika table belum ada, abaikan agar tidak memutus halaman
            log_message('error', 'absensi(): gagal ambil izin_absen - ' . $e->getMessage());
        }
        
        foreach ($all_data as $row) {
            if (empty($row->jam_masuk) && empty($row->jam_pulang)) {
                // Jika sudah ada izin untuk tanggal ini, JANGAN tampilkan di "Belum Absen"
                $rid = isset($row->recid_karyawan) ? (int)$row->recid_karyawan : 0;
                if (!isset($izin_map[$rid])) {
                    $data['belum_absen'][] = $row;
                }
            } else if (!empty($row->jam_masuk)) {
                // Masukkan ke array absensi (table utama)
                $data['absensi'][] = $row;
                
                // Bandingkan ke jam masuk sesuai jadwal shift (jika ada), jika tidak ada gunakan default 07:30
                $patokan = !empty($row->jam_in_shift) ? $row->jam_in_shift : $data['jam_masuk_std'];
                if ($row->jam_masuk > $patokan) {
                    $terlambat++;
                } else {
                    $tepat_waktu++;
                }
            }
        }
        
        $data['statistik'] = [
            'tepat_waktu' => $tepat_waktu,
            'terlambat' => $terlambat,
            'belum_absen' => count($data['belum_absen']),
            'total' => count($all_data)
        ];
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/absensi_view', $data);
        $this->load->view('layout/a_footer');
    }

    // View: Jadwal Shift (Absen Finger) - H-1 scheduler
    public function jadwal_shift()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();

        // List Bagian
        $data['bagian_list'] = $this->db->select('recid_bag, nama_bag')
                                         ->where('is_delete', '0')
                                         ->order_by('nama_bag', 'ASC')
                                         ->get('bagian')
                                         ->result();

        // List Shift (non-overnight recommended). We include all; UI will show jam_in/out for reference
        $db_absen = $this->load->database('absen', TRUE);
        $data['shift_list'] = $db_absen->select('recid_jenisabsen, jenis, keterangan, jam_in, jam_out')
                                       ->order_by('keterangan', 'ASC')
                                       ->get('jenis_absen')
                                       ->result();

        $data['default_tanggal'] = date('Y-m-d', strtotime('+1 day'));

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/jadwal_shift_view', $data);
        $this->load->view('layout/a_footer');
    }

    // API: Ambil karyawan aktif (optional filter by bagian)
    public function get_karyawan_aktif()
    {
        header('Content-Type: application/json');
        $bagian = $this->input->get('bagian');
        // Include nama bagian so UI table can display it
        $this->db->select('k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, b.nama_bag')
                 ->from('karyawan k')
                 ->join('bagian b', 'b.recid_bag = k.recid_bag', 'left')
                 ->where('sts_aktif', 'AKTIF');
        if (!empty($bagian)) {
            $this->db->where('k.recid_bag', $bagian);
        }
        $rows = $this->db->order_by('nama_karyawan','ASC')->get()->result_array();
        echo json_encode(['success'=>true,'data'=>$rows]);
    }

    // API: Get jadwal shift by date (and optional bagian)
    public function get_jadwal_shift()
    {
        header('Content-Type: application/json');
        $tanggal = $this->input->get('tanggal');
        $bagian = $this->input->get('bagian');
        if (empty($tanggal)) {
            echo json_encode(['success'=>false,'message'=>'Parameter tanggal wajib']);
            return;
        }
        $db_absen = $this->load->database('absen', TRUE);
        $db_absen->select('s.recid_jadwal, s.tgl_kerja, s.recid_karyawan, k.nik, k.nama_karyawan, b.nama_bag, ja.keterangan as nama_shift, ja.jam_in, ja.jam_out')
                 ->from('jadwal_shift s')
                 ->join('hris.karyawan k','k.recid_karyawan = s.recid_karyawan','left')
                 ->join('hris.bagian b','b.recid_bag = k.recid_bag','left')
                 ->join('jenis_absen ja','ja.recid_jenisabsen = s.recid_jenisabsen','left')
                 ->where('s.is_delete','0')
                 ->where('s.tgl_kerja', $tanggal);
        if (!empty($bagian)) {
            $db_absen->where('k.recid_bag', $bagian);
        }
        $rows = $db_absen->order_by('b.nama_bag ASC, k.nama_karyawan ASC')->get()->result_array();
        echo json_encode(['success'=>true,'data'=>$rows]);
    }

    // API: Simpan (upsert) jadwal shift H-1 untuk banyak karyawan
    public function simpan_jadwal_shift()
    {
        header('Content-Type: application/json');
        $tanggal = $this->input->post('tanggal');
        $shift_id = $this->input->post('shift_id');
        $karyawan_ids = $this->input->post('karyawan_ids'); // array of recid_karyawan
        if (empty($tanggal) || empty($shift_id) || empty($karyawan_ids) || !is_array($karyawan_ids)) {
            echo json_encode(['success'=>false,'message'=>'Tanggal, Shift, dan daftar karyawan wajib diisi']);
            return;
        }
        $db_absen = $this->load->database('absen', TRUE);
        $created = 0; $updated = 0;
        foreach ($karyawan_ids as $rid) {
            $rid = intval($rid);
            if ($rid <= 0) continue;
            // check exists
            $exist = $db_absen->select('recid_jadwal')
                              ->from('jadwal_shift')
                              ->where('recid_karyawan',$rid)
                              ->where('tgl_kerja',$tanggal)
                              ->get()->row_array();
            if ($exist) {
                $db_absen->where('recid_jadwal', $exist['recid_jadwal'])
                         ->update('jadwal_shift', [
                            'recid_jenisabsen' => $shift_id,
                            'is_delete' => '0',
                            'mdf_date' => date('Y-m-d H:i:s')
                         ]);
                $updated++;
            } else {
                $db_absen->insert('jadwal_shift', [
                    'recid_karyawan' => $rid,
                    'tgl_kerja' => $tanggal,
                    'recid_jenisabsen' => $shift_id,
                    'is_delete' => '0',
                    'crt_date' => date('Y-m-d H:i:s')
                ]);
                $created++;
            }
        }
        echo json_encode(['success'=>true,'message'=>'Tersimpan','created'=>$created,'updated'=>$updated]);
    }

    // API: Hapus 1 jadwal shift (soft delete)
    public function hapus_jadwal_shift()
    {
        header('Content-Type: application/json');
        $id = intval($this->input->post('id'));
        if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'ID invalid']); return; }
        $db_absen = $this->load->database('absen', TRUE);
        $db_absen->where('recid_jadwal', $id)->update('jadwal_shift', [
            'is_delete' => '1',
            'mdf_date' => date('Y-m-d H:i:s')
        ]);
        echo json_encode(['success'=>true]);
    }

    // API: List shift types (from master_absen.jenis_absen)
    public function get_shift_list()
    {
        header('Content-Type: application/json');
        $db_absen = $this->load->database('absen', TRUE);
        $rows = $db_absen->select('recid_jenisabsen, jenis, keterangan, jam_in, jam_out')
                         ->order_by('keterangan','ASC')
                         ->get('jenis_absen')->result_array();
        echo json_encode(['success'=>true,'data'=>$rows]);
    }

    // API: Create a custom shift (requires title and in/out times)
    public function create_custom_shift()
    {
        header('Content-Type: application/json');
        $title = trim($this->input->post('title') ?: '');
        $jam_in = trim($this->input->post('jam_in') ?: '');
        $jam_out = trim($this->input->post('jam_out') ?: '');

        if ($title === '' || $jam_in === '' || $jam_out === '') {
            echo json_encode(['success'=>false,'message'=>'Judul, Jam In, dan Jam Out wajib diisi']);
            return;
        }

        // Normalize time to HH:MM:SS
        $norm = function($t){
            if (preg_match('/^\d{2}:\d{2}$/', $t)) return $t.':00';
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) return $t;
            return '';
        };
        $jin = $norm($jam_in);
        $jout = $norm($jam_out);
        if ($jin === '' || $jout === '') {
            echo json_encode(['success'=>false,'message'=>'Format jam harus HH:MM atau HH:MM:SS']);
            return;
        }

        // Basic non-overnight rule
        if ($jin >= $jout) {
            echo json_encode(['success'=>false,'message'=>'Jam Out harus lebih besar dari Jam In (overnight belum didukung)']);
            return;
        }

        $db_absen = $this->load->database('absen', TRUE);

        // Check duplicate by (keterangan, jam_in, jam_out)
        $exist = $db_absen->select('recid_jenisabsen')
                          ->from('jenis_absen')
                          ->where('keterangan', $title)
                          ->where('jam_in', $jin)
                          ->where('jam_out', $jout)
                          ->get()->row_array();
        if ($exist) {
            echo json_encode(['success'=>true,'exists'=>true,'id'=>$exist['recid_jenisabsen']]);
            return;
        }

        $data = [
            'jenis' => 'SHIFT-CUSTOM',
            'keterangan' => $title,
            'jam_in' => $jin,
            'jam_out' => $jout
        ];
        $db_absen->insert('jenis_absen', $data);
        $new_id = $db_absen->insert_id();
        echo json_encode(['success'=>true,'id'=>$new_id]);
    }
    
    // View: Kehadiran Bulanan (Calendar style)
    public function kehadiran_bulanan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        // Debug: Check if data loaded
        if (empty($data['cek_usr'])) {
            log_message('error', 'kehadiran_bulanan: User data not found for kar_id=' . $usr);
        }
        
        // Load list bagian for dropdown filter
        $data['bagian_list'] = $this->db->select('recid_bag, nama_bag')
                                        ->where('is_delete', '0')
                                        ->order_by('nama_bag', 'ASC')
                                        ->get('bagian')
                                        ->result();
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/kehadiran_bulanan_view', $data);
        $this->load->view('layout/a_footer');
    }
    
    // API: Get data kehadiran bulanan
    public function get_kehadiran_bulanan()
    {
        header('Content-Type: application/json');
        
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $bagian = $this->input->post('bagian'); // Filter bagian (optional)
        
        if (empty($bulan) || empty($tahun)) {
            echo json_encode(['success' => false, 'message' => 'Bulan dan tahun harus diisi']);
            return;
        }
        
        try {
            // Get total days in month
            $total_days = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            
            // Generate dates array
            $dates = [];
            $hari_kerja = 0;
            $day_names = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            
            for ($i = 1; $i <= $total_days; $i++) {
                $date = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $day_num = date('w', strtotime($date));
                $day_name = $day_names[$day_num];
                
                $dates[] = [
                    'tanggal' => $i,
                    'full_date' => $date,
                    'day_name' => $day_name,
                    'is_weekend' => ($day_num == 0 || $day_num == 6)
                ];
                
                // Count working days (exclude weekends)
                if ($day_num != 0 && $day_num != 6) {
                    $hari_kerja++;
                }
            }
            
            // Get all active employees with optional bagian filter
            $this->db->select('recid_karyawan, nik, nama_karyawan, recid_bag')
                     ->where('sts_aktif', 'AKTIF');
            
            // Apply bagian filter if specified
            if (!empty($bagian)) {
                $this->db->where('recid_bag', $bagian);
            }
            
            $karyawan_list = $this->db->order_by('nama_karyawan', 'ASC')
                                      ->get('karyawan')
                                      ->result_array();
            
            // Get attendance data for the month
            $start_date = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-01';
            $end_date = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($total_days, 2, '0', STR_PAD_LEFT);
            
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
            
            $absensi_data = $this->db->query($query, [$start_date, $end_date])->result_array();
            
            // Map attendance data
            $attendance_map = [];
            foreach ($absensi_data as $row) {
                $recid = $row['recid_karyawan'];
                $tanggal = $row['tanggal_absen'];
                
                if (!isset($attendance_map[$recid])) {
                    $attendance_map[$recid] = [];
                }
                
                $attendance_map[$recid][$tanggal] = 'hadir';
            }
            
            // Build final data structure
            $data = [];
            $total_hadir_global = 0;
            $total_absen_global = 0;
            
            foreach ($karyawan_list as $karyawan) {
                $recid = $karyawan['recid_karyawan'];
                $kehadiran = [];
                
                foreach ($dates as $date) {
                    $full_date = $date['full_date'];
                    $is_weekend = $date['is_weekend'];
                    
                    if ($is_weekend) {
                        $kehadiran[$full_date] = 'libur';
                    } else {
                        if (isset($attendance_map[$recid][$full_date])) {
                            $kehadiran[$full_date] = 'hadir';
                            $total_hadir_global++;
                        } else {
                            $kehadiran[$full_date] = 'absen';
                            $total_absen_global++;
                        }
                    }
                }
                
                $data[] = [
                    'nik' => $karyawan['nik'],
                    'nama_karyawan' => $karyawan['nama_karyawan'],
                    'kehadiran' => $kehadiran
                ];
            }
            
            // Calculate summary
            $total_karyawan = count($karyawan_list);
            $total_hari_kerja_semua = $total_karyawan * $hari_kerja;
            $avg_kehadiran = $total_hari_kerja_semua > 0 ? 
                             round(($total_hadir_global / $total_hari_kerja_semua) * 100, 1) : 0;
            
            // Get bagian name if filtered
            $nama_bag = 'Semua Bagian';
            if (!empty($bagian)) {
                $bag_data = $this->db->select('nama_bag')
                                     ->where('recid_bag', $bagian)
                                     ->get('bagian')
                                     ->row();
                if ($bag_data) {
                    $nama_bag = $bag_data->nama_bag;
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $data,
                'dates' => $dates,
                'summary' => [
                    'total_karyawan' => $total_karyawan,
                    'hari_kerja' => $hari_kerja,
                    'total_hadir' => $total_hadir_global,
                    'total_absen' => $total_absen_global,
                    'avg_kehadiran' => $avg_kehadiran,
                    'nama_bag' => $nama_bag
                ],
                'debug' => [
                    'periode' => $start_date . ' s/d ' . $end_date,
                    'bagian' => $nama_bag,
                    'total_records' => count($absensi_data),
                    'query_executed' => 'OK'
                ]
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], JSON_PRETTY_PRINT);
        }
    }

    // Export XLSX for Kehadiran Bulanan (calendar-style grid)
    public function export_kehadiran_bulanan()
    {
        try {
            $bulan = $this->input->get('bulan') ?: date('m');
            $tahun = $this->input->get('tahun') ?: date('Y');
            $bagian = $this->input->get('bagian'); // optional

            // Build dates and working days
            $total_days = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));
            $dates = [];
            $hari_kerja = 0;
            $day_names = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            for ($i = 1; $i <= $total_days; $i++) {
                $date = $tahun.'-'.str_pad($bulan,2,'0',STR_PAD_LEFT).'-'.str_pad($i,2,'0',STR_PAD_LEFT);
                $day_num = date('w', strtotime($date));
                $dates[] = [
                    'tanggal' => $i,
                    'full_date' => $date,
                    'day_name' => $day_names[$day_num],
                    'is_weekend' => ($day_num==0 || $day_num==6)
                ];
                if ($day_num != 0 && $day_num != 6) $hari_kerja++;
            }

            // Employees (filtered)
            $this->db->select('recid_karyawan, nik, nama_karyawan, recid_bag')->where('sts_aktif','AKTIF');
            if (!empty($bagian)) $this->db->where('recid_bag', $bagian);
            $karyawan_list = $this->db->order_by('nama_karyawan','ASC')->get('karyawan')->result_array();

            // Attendance map for the month
            $start_date = $tahun.'-'.str_pad($bulan,2,'0',STR_PAD_LEFT).'-01';
            $end_date = $tahun.'-'.str_pad($bulan,2,'0',STR_PAD_LEFT).'-'.str_pad($total_days,2,'0',STR_PAD_LEFT);
            $sql = "
                SELECT k.recid_karyawan, DATE(a.waktu) AS tanggal_absen
                FROM hris.karyawan k
                LEFT JOIN hris.karyawan_pin_map pm ON k.recid_karyawan = pm.recid_karyawan
                LEFT JOIN master_finger.absensi a ON pm.pin = a.nik AND DATE(a.waktu) BETWEEN ? AND ?
                WHERE k.sts_aktif='AKTIF'
                GROUP BY k.recid_karyawan, DATE(a.waktu)
                HAVING tanggal_absen IS NOT NULL
            ";
            $absensi_rows = $this->db->query($sql, [$start_date, $end_date])->result_array();
            $att_map = [];
            foreach ($absensi_rows as $r) { $att_map[$r['recid_karyawan']][$r['tanggal_absen']] = true; }

            // Prepare Excel
            if (ob_get_level() > 0) { @ob_end_clean(); }
            @ob_start();
            require_once APPPATH.'../vendor/autoload.php';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Kehadiran Bulanan');

            $nama_bag = 'Semua Bagian';
            if (!empty($bagian)) {
                $b = $this->db->select('nama_bag')->where('recid_bag',$bagian)->get('bagian')->row();
                if ($b) $nama_bag = $b->nama_bag;
            }

            // Title
            $sheet->setCellValue('A1', 'Rekap Kehadiran Bulanan');
            $sheet->setCellValue('A2', 'Periode: '.date('F Y', strtotime($tahun.'-'.$bulan.'-01')).' | Bagian: '.$nama_bag);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            // Header
            $row = 4; $col = 1;
            $headers = ['No','NIK','Nama Karyawan'];
            foreach ($headers as $h) { $sheet->setCellValueByColumnAndRow($col++, $row, $h); }
            foreach ($dates as $d) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $d['tanggal'].'\n'.substr($d['day_name'],0,3));
            }
            $sheet->setCellValueByColumnAndRow($col++, $row, 'Total Hadir');
            $sheet->setCellValueByColumnAndRow($col++, $row, 'Total Absen');
            $sheet->setCellValueByColumnAndRow($col++, $row, 'Tidak Hadir');
            $sheet->setCellValueByColumnAndRow($col++, $row, '% Hadir');
            $sheet->getStyle('A'.$row.':'.$sheet->getCellByColumnAndRow($col-1,$row)->getColumn().$row)->getFont()->setBold(true);
            $sheet->freezePane('A'.($row+1));

            // Body rows
            $row++;
            $no = 1;
            foreach ($karyawan_list as $k) {
                $c = 1;
                $sheet->setCellValueByColumnAndRow($c++, $row, $no++);
                $sheet->setCellValueByColumnAndRow($c++, $row, $k['nik']);
                $sheet->setCellValueByColumnAndRow($c++, $row, $k['nama_karyawan']);
                $hadir = 0; $absen = 0; $tidak_hadir = 0;
                foreach ($dates as $d) {
                    if ($d['is_weekend']) {
                        $sheet->setCellValueByColumnAndRow($c++, $row, '-');
                        $tidak_hadir++; // Count weekend as tidak hadir
                    } else {
                        if (!empty($att_map[$k['recid_karyawan']][$d['full_date']])) {
                            $sheet->setCellValueByColumnAndRow($c++, $row, '✓'); $hadir++;
                        } else {
                            $sheet->setCellValueByColumnAndRow($c++, $row, '✗'); $absen++;
                        }
                    }
                }
                $sheet->setCellValueByColumnAndRow($c++, $row, $hadir);
                $sheet->setCellValueByColumnAndRow($c++, $row, $absen);
                $sheet->setCellValueByColumnAndRow($c++, $row, $tidak_hadir);
                $percent = $hari_kerja>0 ? round(($hadir/$hari_kerja)*100, 1) : 0;
                $sheet->setCellValueByColumnAndRow($c++, $row, $percent.'%');
                $row++;
            }

            // Auto-size columns (limit for performance)
            foreach (range('A','C') as $colL) { $sheet->getColumnDimension($colL)->setAutoSize(true); }

            $filename = 'kehadiran_bulanan_'.$tahun.str_pad($bulan,2,'0',STR_PAD_LEFT).'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            @ob_end_flush();
            exit;
        } catch (Exception $e) {
            if (!headers_sent()) header('Content-Type: application/json');
            echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
        }
    }
    
    // Upload CSV untuk bulk mapping
    public function upload_mapping_csv()
    {
        header('Content-Type: application/json');
        set_time_limit(300);
        
        try {
            if (!isset($_FILES['csv_file'])) {
                echo json_encode(['success' => false, 'message' => 'File CSV tidak ditemukan']);
                return;
            }
            
            $file = $_FILES['csv_file'];
            $device_sn = $this->input->post('device_sn') ?: '';
            
            // Validasi file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Error upload file']);
                return;
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (strtolower($ext) !== 'csv') {
                echo json_encode(['success' => false, 'message' => 'File harus berformat CSV']);
                return;
            }
            
            // Parse CSV - support both comma and semicolon delimiter
            $csv_data = [];
            $handle = fopen($file['tmp_name'], 'r');
            
            // Read header to detect delimiter
            $header = fgets($handle);
            $delimiter = (strpos($header, ';') !== false) ? ';' : ',';
            
            // Reset pointer and skip header row
            rewind($handle);
            fgetcsv($handle, 0, $delimiter);
            
            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if (count($row) >= 3) {
                    $csv_data[] = [
                        'pin' => trim($row[0]),      // Kolom 1: No. (PIN)
                        'ac_no' => trim($row[1]),    // Kolom 2: AC No. (NIK)
                        'name' => trim($row[2])      // Kolom 3: Name
                    ];
                }
            }
            fclose($handle);
            
            if (empty($csv_data)) {
                echo json_encode(['success' => false, 'message' => 'File CSV kosong atau format tidak sesuai']);
                return;
            }
            
            // Ambil semua karyawan aktif untuk mapping
            $karyawan_list = $this->db->select('recid_karyawan, nik, nama_karyawan, pin')
                                      ->where('sts_aktif', 'AKTIF')
                                      ->get('karyawan')
                                      ->result_array();
            
            // Build map berdasarkan PIN, NIK, dan Nama
            $karyawan_by_pin = [];
            $karyawan_by_nik = [];
            $karyawan_by_nama = [];
            
            foreach ($karyawan_list as $k) {
                if (!empty($k['pin'])) {
                    $karyawan_by_pin[trim($k['pin'])] = $k;
                }
                if (!empty($k['nik'])) {
                    $karyawan_by_nik[strtoupper(trim($k['nik']))] = $k;
                }
                $nama_normalized = strtoupper(trim(preg_replace('/\s+/', ' ', $k['nama_karyawan'])));
                $karyawan_by_nama[$nama_normalized] = $k;
            }
            
            // Process mapping
            $total_mapped = 0;
            $total_unmapped = 0;
            $total_duplicate = 0;
            $mapped_by_pin = 0;
            $mapped_by_nik = 0;
            $mapped_by_nama = 0;
            $unmapped_users = [];
            
            foreach ($csv_data as $row) {
                $pin = $row['pin'];
                $ac_no = $row['ac_no'];
                $name = $row['name'];
                
                $karyawan = null;
                $match_method = '';
                
                // PRIORITAS 1: Matching by PIN
                if (!empty($pin) && isset($karyawan_by_pin[trim($pin)])) {
                    $karyawan = $karyawan_by_pin[trim($pin)];
                    $match_method = 'pin';
                    $mapped_by_pin++;
                }
                
                // PRIORITAS 2: Matching by NIK (AC No)
                if (!$karyawan && !empty($ac_no)) {
                    $ac_no_clean = strtoupper(trim($ac_no));
                    if (isset($karyawan_by_nik[$ac_no_clean])) {
                        $karyawan = $karyawan_by_nik[$ac_no_clean];
                        $match_method = 'nik';
                        $mapped_by_nik++;
                    }
                }
                
                // PRIORITAS 3: Matching by Nama
                if (!$karyawan && !empty($name)) {
                    $nama_normalized = strtoupper(trim(preg_replace('/\s+/', ' ', $name)));
                    if (isset($karyawan_by_nama[$nama_normalized])) {
                        $karyawan = $karyawan_by_nama[$nama_normalized];
                        $match_method = 'nama';
                        $mapped_by_nama++;
                    }
                }
                
                // Insert mapping jika ada match
                if ($karyawan) {
                    // Validasi tambahan untuk memastikan recid_karyawan valid
                    if (!is_numeric($karyawan['recid_karyawan']) || $karyawan['recid_karyawan'] <= 0) {
                        $total_unmapped++;
                        $unmapped_users[] = [
                            'pin' => $pin,
                            'nama' => $name,
                            'ac_no' => $ac_no,
                            'error' => 'Invalid recid_karyawan: ' . $karyawan['recid_karyawan']
                        ];
                        continue;
                    }
                    
                    // Tambahan validasi: Pastikan karyawan benar-benar ada di tabel karyawan
                    $karyawan_exists = $this->db->where('recid_karyawan', $karyawan['recid_karyawan'])
                                                ->where('sts_aktif', 'AKTIF')
                                                ->get('karyawan')
                                                ->num_rows();
                    
                    if ($karyawan_exists == 0) {
                        $total_unmapped++;
                        $unmapped_users[] = [
                            'pin' => $pin,
                            'nama' => $name,
                            'ac_no' => $ac_no,
                            'error' => 'Karyawan tidak ditemukan di database: ' . $karyawan['recid_karyawan']
                        ];
                        continue;
                    }
                    
                    // Cek apakah sudah ada mapping untuk PIN ini
                    $where = [
                        'pin' => $pin,
                        'recid_karyawan' => (int)$karyawan['recid_karyawan'] // Pastikan integer
                    ];
                    
                    if (!empty($device_sn)) {
                        $where['device_sn'] = $device_sn;
                    }
                    
                    $exists = $this->db->where($where)->get('karyawan_pin_map')->num_rows();
                    
                    if ($exists == 0) {
                        $insert_data = [
                            'pin' => $pin,
                            'recid_karyawan' => (int)$karyawan['recid_karyawan'], // Pastikan integer
                            'nama_di_mesin' => $name,
                            'created_date' => date('Y-m-d')
                        ];
                        
                        if (!empty($device_sn)) {
                            $insert_data['device_sn'] = $device_sn;
                        }
                        
                        $this->db->insert('karyawan_pin_map', $insert_data);
                        $total_mapped++;
                    } else {
                        $total_duplicate++;
                    }
                } else {
                    $total_unmapped++;
                    $unmapped_users[] = [
                        'pin' => $pin,
                        'nama' => $name,
                        'ac_no' => $ac_no
                    ];
                }
            }
            
            // Response
            echo json_encode([
                'success' => true,
                'message' => 'Mapping dari CSV berhasil diproses',
                'summary' => [
                    'total_rows' => count($csv_data),
                    'total_mapped' => $total_mapped,
                    'mapped_by_pin' => $mapped_by_pin,
                    'mapped_by_nik' => $mapped_by_nik,
                    'mapped_by_nama' => $mapped_by_nama,
                    'total_duplicate' => $total_duplicate,
                    'total_unmapped' => $total_unmapped
                ],
                'unmapped_users' => $unmapped_users
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // View: History Absensi Karyawan
    public function history_absensi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/history_absensi_view', $data);
        $this->load->view('layout/a_footer');
    }
    
    // API: Get history absensi by bulan
    public function get_history_by_month()
    {
        header('Content-Type: application/json');
        
            // Terima parameter dari GET atau POST (AJAX kita menggunakan GET)
        $bulan = intval($this->input->get('bulan') ?: $this->input->post('bulan') ?: date('m'));
        $tahun = intval($this->input->get('tahun') ?: $this->input->post('tahun') ?: date('Y'));
        
        try {
            // Hitung tanggal awal (19 bulan sebelumnya)
            $date_mulai = new DateTime($tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-19');
            $date_mulai->modify('-1 month');
            $tgl_mulai = $date_mulai->format('Y-m-d');
            
            // Hitung tanggal akhir (20 bulan ini)
            $tgl_akhir = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-20';
            
            // Opsional filter berdasarkan nama/nik/q (export per nama)
            $q = trim($this->input->get('q') ?: '');
            $nama = trim($this->input->get('nama') ?: '');
            $nikFilter = trim($this->input->get('nik') ?: '');

            $baseQuery = "
                SELECT 
                    a.id,
                    a.nik as pin,
                    a.uid,
                    a.waktu,
                    k.nama_karyawan,
                    COALESCE(k.nik, a.nik) as nik_karyawan
                FROM absensi a
                LEFT JOIN hris.karyawan_pin_map pm ON a.nik = pm.pin
                LEFT JOIN hris.karyawan k ON pm.recid_karyawan = k.recid_karyawan
                WHERE DATE(a.waktu) BETWEEN ? AND ?
                AND pm.recid_karyawan IS NOT NULL
                AND k.sts_aktif = 'Aktif'
            ";
            $params = [$tgl_mulai, $tgl_akhir];
            if ($nikFilter !== '') {
                $baseQuery .= " AND k.nik = ?";
                $params[] = $nikFilter;
            } elseif ($nama !== '') {
                $baseQuery .= " AND k.nama_karyawan LIKE ?";
                $params[] = '%' . $nama . '%';
            } elseif ($q !== '') {
                $baseQuery .= " AND (k.nama_karyawan LIKE ? OR k.nik LIKE ?)";
                $params[] = '%' . $q . '%';
                $params[] = '%' . $q . '%';
            }
            $baseQuery .= " ORDER BY a.waktu ASC";
            $rows = $this->db2->query($baseQuery, $params)->result_array();

            // Kelompokkan per karyawan per tanggal, lalu ambil min/max (CI/CO)
            $groups = [];
            foreach ($rows as $r) {
                $tanggal = date('d-m-Y', strtotime($r['waktu']));
                $key = $r['nik_karyawan'] . '|' . $tanggal;
                if (!isset($groups[$key])) {
                    $groups[$key] = [
                        'nik_karyawan' => $r['nik_karyawan'],
                        'nama_karyawan' => $r['nama_karyawan'] ?: 'Tidak Teridentifikasi',
                        'tanggal' => $tanggal,
                        'min_waktu' => $r['waktu'],
                        'max_waktu' => $r['waktu']
                    ];
                } else {
                    if (strtotime($r['waktu']) < strtotime($groups[$key]['min_waktu'])) {
                        $groups[$key]['min_waktu'] = $r['waktu'];
                    }
                    if (strtotime($r['waktu']) > strtotime($groups[$key]['max_waktu'])) {
                        $groups[$key]['max_waktu'] = $r['waktu'];
                    }
                }
            }

            // Bentuk baris pasangan: Check-in (min) lalu Check-out (max jika berbeda)
            $paired = [];
            foreach ($groups as $g) {
                // Row Check-in
                $jam_ci = date('H:i:s', strtotime($g['min_waktu']));
                $ampm_ci = (intval(date('H', strtotime($g['min_waktu']))) < 12) ? 'AM' : 'PM';
                $paired[] = [
                    'id' => null,
                    'nik' => $g['nik_karyawan'],
                    'nama_karyawan' => $g['nama_karyawan'],
                    'tanggal' => $g['tanggal'],
                    'jam' => $jam_ci,
                    'am_pm' => $ampm_ci,
                    'state' => 'Check-in'
                ];
                // Row Check-out (jika berbeda jam)
                if ($g['max_waktu'] !== $g['min_waktu']) {
                    $jam_co = date('H:i:s', strtotime($g['max_waktu']));
                    $ampm_co = (intval(date('H', strtotime($g['max_waktu']))) < 12) ? 'AM' : 'PM';
                    $paired[] = [
                        'id' => null,
                        'nik' => $g['nik_karyawan'],
                        'nama_karyawan' => $g['nama_karyawan'],
                        'tanggal' => $g['tanggal'],
                        'jam' => $jam_co,
                        'am_pm' => $ampm_co,
                        'state' => 'Check-out'
                    ];
                }
            }

            // Urutkan: nama ASC, tanggal ASC, lalu Check-in sebelum Check-out
            usort($paired, function($a, $b) {
                $nama = strcmp($a['nama_karyawan'], $b['nama_karyawan']);
                if ($nama === 0) {
                    $ta = DateTime::createFromFormat('d-m-Y', $a['tanggal'])->getTimestamp();
                    $tb = DateTime::createFromFormat('d-m-Y', $b['tanggal'])->getTimestamp();
                    if ($ta === $tb) {
                        $order = ['Check-in' => 0, 'Check-out' => 1];
                        return ($order[$a['state']] ?? 2) - ($order[$b['state']] ?? 2);
                    }
                    return $ta - $tb;
                }
                return $nama;
            });
            
            $total = count($paired);
            
            echo json_encode([
                'success' => true,
                'data' => $paired,
                'total' => $total,
                'periode' => date('d/m/Y', strtotime($tgl_mulai)) . ' - ' . date('d/m/Y', strtotime($tgl_akhir)),
                'tanggal_mulai' => $tgl_mulai,
                'tanggal_akhir' => $tgl_akhir,
                'bulan_selected' => $bulan,
                'tahun_selected' => $tahun,
                'message' => $total > 0 ? 'Data ditemukan: ' . $total . ' records' : 'Tidak ada data untuk periode ini'
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    // Export Excel: History Absensi Bulanan (periode 19 bulan sebelumnya s/d 20 bulan terpilih)
    public function export_history_bulanan()
    {
        // Ambil parameter dari GET
        $bulan = intval($this->input->get('bulan') ?: date('m'));
        $tahun = intval($this->input->get('tahun') ?: date('Y'));

        // Hindari output tambahan yang merusak file biner
        if (ob_get_level() > 0) {
            @ob_end_clean();
        }
        @ob_start();

        // Hitung periode
        $date_mulai = new DateTime($tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-19');
        $date_mulai->modify('-1 month');
        $tgl_mulai = $date_mulai->format('Y-m-d');
        $tgl_akhir = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-20';

        // Opsional filter per nama/nik/q
        $q = trim($this->input->get('q') ?: '');
        $nama = trim($this->input->get('nama') ?: '');
        $nikFilter = trim($this->input->get('nik') ?: '');

        $query = "
            SELECT 
                a.id,
                a.nik as pin,
                a.uid,
                a.waktu,
                k.nama_karyawan,
                COALESCE(k.nik, a.nik) as nik_karyawan
            FROM absensi a
            LEFT JOIN hris.karyawan_pin_map pm ON a.nik = pm.pin
            LEFT JOIN hris.karyawan k ON pm.recid_karyawan = k.recid_karyawan
            WHERE DATE(a.waktu) BETWEEN ? AND ?
            AND pm.recid_karyawan IS NOT NULL
            AND k.sts_aktif = 'Aktif'
        ";
        $params = [$tgl_mulai, $tgl_akhir];
        if ($nikFilter !== '') {
            $query .= " AND k.nik = ?";
            $params[] = $nikFilter;
        } elseif ($nama !== '') {
            $query .= " AND k.nama_karyawan LIKE ?";
            $params[] = '%' . $nama . '%';
        } elseif ($q !== '') {
            $query .= " AND (k.nama_karyawan LIKE ? OR k.nik LIKE ?)";
            $params[] = '%' . $q . '%';
            $params[] = '%' . $q . '%';
        }
        $query .= " ORDER BY a.waktu ASC";
        $rows = $this->db2->query($query, $params)->result_array();

        // Grouping & pairing
        $groups = [];
        foreach ($rows as $r) {
            $tanggal = date('d-m-Y', strtotime($r['waktu']));
            $key = $r['nik_karyawan'] . '|' . $tanggal;
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'nik_karyawan' => $r['nik_karyawan'],
                    'nama_karyawan' => $r['nama_karyawan'] ?: 'Tidak Teridentifikasi',
                    'tanggal' => $tanggal,
                    'min_waktu' => $r['waktu'],
                    'max_waktu' => $r['waktu']
                ];
            } else {
                if (strtotime($r['waktu']) < strtotime($groups[$key]['min_waktu'])) {
                    $groups[$key]['min_waktu'] = $r['waktu'];
                }
                if (strtotime($r['waktu']) > strtotime($groups[$key]['max_waktu'])) {
                    $groups[$key]['max_waktu'] = $r['waktu'];
                }
            }
        }

        $data = [];
        foreach ($groups as $g) {
            $jam_ci = date('H:i:s', strtotime($g['min_waktu']));
            $ampm_ci = (intval(date('H', strtotime($g['min_waktu']))) < 12) ? 'AM' : 'PM';
            $data[] = [
                'nik' => $g['nik_karyawan'],
                'nama_karyawan' => $g['nama_karyawan'],
                'tanggal' => $g['tanggal'],
                'jam' => $jam_ci,
                'am_pm' => $ampm_ci,
                'state' => 'Check-in'
            ];
            if ($g['max_waktu'] !== $g['min_waktu']) {
                $jam_co = date('H:i:s', strtotime($g['max_waktu']));
                $ampm_co = (intval(date('H', strtotime($g['max_waktu']))) < 12) ? 'AM' : 'PM';
                $data[] = [
                    'nik' => $g['nik_karyawan'],
                    'nama_karyawan' => $g['nama_karyawan'],
                    'tanggal' => $g['tanggal'],
                    'jam' => $jam_co,
                    'am_pm' => $ampm_co,
                    'state' => 'Check-out'
                ];
            }
        }

        // Sorting
        usort($data, function($a, $b) {
            $nama = strcmp($a['nama_karyawan'], $b['nama_karyawan']);
            if ($nama === 0) {
                $ta = DateTime::createFromFormat('d-m-Y', $a['tanggal'])->getTimestamp();
                $tb = DateTime::createFromFormat('d-m-Y', $b['tanggal'])->getTimestamp();
                if ($ta === $tb) {
                    $order = ['Check-in' => 0, 'Check-out' => 1];
                    return ($order[$a['state']] ?? 2) - ($order[$b['state']] ?? 2);
                }
                return $ta - $tb;
            }
            return $nama;
        });

        // Build Excel using PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('History Absensi');

        // Header
        $headers = ['No', 'NIK', 'Nama Karyawan', 'Tanggal', 'Jam', 'AM/PM', 'State'];
        $col = 1;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($col, 1, $h);
            $col++;
        }
        // Bold header and freeze
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->freezePane('A2');

        // Data
        $rowNum = 2;
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValueByColumnAndRow(1, $rowNum, $no);
            $sheet->setCellValueByColumnAndRow(2, $rowNum, $row['nik']);
            $sheet->setCellValueByColumnAndRow(3, $rowNum, $row['nama_karyawan']);
            $sheet->setCellValueByColumnAndRow(4, $rowNum, $row['tanggal']);
            $sheet->setCellValueByColumnAndRow(5, $rowNum, $row['jam']);
            $sheet->setCellValueByColumnAndRow(6, $rowNum, $row['am_pm']);
            $sheet->setCellValueByColumnAndRow(7, $rowNum, $row['state']);
            $rowNum++; $no++;
        }

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'history_absensi_' . $tahun . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        @ob_end_flush();
        exit;
    }
    
    public function debug_tap()
    {
        $nik = $this->input->get('nik');
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        
        if (!$nik) {
            echo json_encode(['error' => 'NIK required']);
            return;
        }
        
        // Ambil recid_karyawan dari NIK
        $karyawan = $this->db->select('recid_karyawan, nama_karyawan')
                             ->from('karyawan')
                             ->where('nik', $nik)
                             ->get()
                             ->row();
        
        if (!$karyawan) {
            echo json_encode(['error' => 'Karyawan tidak ditemukan']);
            return;
        }
        
        // Ambil semua PIN yang ter-mapping ke karyawan ini
        $pins = $this->db->select('pin, device_sn')
                         ->from('karyawan_pin_map')
                         ->where('recid_karyawan', $karyawan->recid_karyawan)
                         ->get()
                         ->result();
        
        // Ambil semua tap dari semua PIN tersebut
        $pin_list = array_column($pins, 'pin');
        
        // Switch ke database master_finger (menggunakan group 'absen2')
        $db_finger = $this->load->database('absen2', TRUE);
        
        if (!empty($pin_list)) {
            // Query dengan kolom yang pasti ada (id, nik, waktu, uid)
            $taps = $db_finger->select('id, nik, waktu, uid')
                              ->from('absensi')
                              ->where('DATE(waktu)', $tanggal)
                              ->where_in('nik', $pin_list)
                              ->order_by('waktu', 'ASC')
                              ->get()
                              ->result();
        } else {
            $taps = [];
        }
        
        echo json_encode([
            'nik' => $nik,
            'nama' => $karyawan->nama_karyawan,
            'tanggal' => $tanggal,
            'mapped_pins' => $pins,
            'total_taps' => count($taps),
            'taps' => $taps
        ], JSON_PRETTY_PRINT);
    }
    
    // Halaman absen manual
    public function absen_manual()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
        
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();
        
        // Load list bagian for dropdown filter
        $data['bagian_list'] = $this->db->select('recid_bag, nama_bag')
                                        ->where('is_delete', '0')
                                        ->order_by('nama_bag', 'ASC')
                                        ->get('bagian')
                                        ->result();
        
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/absen_manual_view', $data);
        $this->load->view('layout/a_footer');
    }
    
    // API: Get data untuk absen manual
    public function get_data_absen_manual()
    {
        header('Content-Type: application/json');
        
        $tanggal = $this->input->post('tanggal');
        $bagian = $this->input->post('bagian');
        
        if (empty($tanggal)) {
            echo json_encode(['success' => false, 'message' => 'Tanggal harus diisi']);
            return;
        }
        
        try {
            // Get all active employees with optional bagian filter
            // Pastikan alias kolom sesuai dengan yang dipakai di view (nama_bagian)
            $this->db->select('k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, b.nama_bag as nama_bagian, j.nama_jbtn as nama_jabatan')
                     ->from('karyawan k')
                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                     ->where('k.sts_aktif', 'AKTIF');
            
            if (!empty($bagian)) {
                $this->db->where('k.recid_bag', $bagian);
            }
            
            $karyawan_list = $this->db->order_by('k.nama_karyawan', 'ASC')
                                      ->get()
                                      ->result_array();
            
            // Get attendance data for the date
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
            
            $absensi_data = $this->db->query($query, [$tanggal])->result_array();
            
            // Map attendance by recid_karyawan
            $attendance_map = [];
            foreach ($absensi_data as $row) {
                $attendance_map[$row['recid_karyawan']] = [
                    'jam_masuk' => $row['jam_masuk'],
                    'jam_pulang' => $row['jam_pulang'],
                    'total_tap' => $row['total_tap']
                ];
            }
            
            // Get karyawan yang sedang dinas keluar dari tabel dinas_karyawan
            $dinas_query = "
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
                  AND '$tanggal' BETWEEN dk.tanggal_mulai AND dk.tanggal_selesai
            ";
            
            $dinas_result = $this->db2->query($dinas_query)->result_array();
            
            // Create map of karyawan dinas
            $dinas_map = [];
            foreach ($dinas_result as $dinas) {
                $dinas_map[$dinas['recid_karyawan']] = $dinas;
            }
            
            // Separate into sudah absen, belum absen, and sedang dinas
            $sudah_absen = [];
            $belum_absen = [];
            $sedang_dinas = [];
            
            foreach ($karyawan_list as $karyawan) {
                $recid = $karyawan['recid_karyawan'];
                
                // Prioritas: cek dulu apakah sedang dinas
                if (isset($dinas_map[$recid])) {
                    $sedang_dinas[] = array_merge($karyawan, [
                        'recid_dinas' => $dinas_map[$recid]['recid_dinas'],
                        'keterangan_dinas' => $dinas_map[$recid]['keterangan_dinas'],
                        'lokasi_dinas' => $dinas_map[$recid]['lokasi_dinas'],
                        'tanggal_mulai' => $dinas_map[$recid]['tanggal_mulai'],
                        'tanggal_selesai' => $dinas_map[$recid]['tanggal_selesai'],
                        'nomor_surat' => $dinas_map[$recid]['nomor_surat'],
                        'status_dinas' => $dinas_map[$recid]['status_dinas']
                    ]);
                } elseif (isset($attendance_map[$recid])) {
                    $sudah_absen[] = array_merge($karyawan, $attendance_map[$recid]);
                } else {
                    $belum_absen[] = $karyawan;
                }
            }
            
            // Calculate summary (karyawan dinas dianggap sudah hadir)
            $total_karyawan = count($karyawan_list);
            $total_sudah = count($sudah_absen);
            $total_belum = count($belum_absen);
            $total_dinas = count($sedang_dinas);
            $total_hadir = $total_sudah + $total_dinas; // Dinas dihitung sebagai hadir
            $persen = $total_karyawan > 0 ? round(($total_hadir / $total_karyawan) * 100, 1) : 0;
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'sudah_absen' => $sudah_absen,
                    'belum_absen' => $belum_absen,
                    'sedang_dinas' => $sedang_dinas
                ],
                'summary' => [
                    'total_karyawan' => $total_karyawan,
                    'sudah_absen' => $total_sudah,
                    'belum_absen' => $total_belum,
                    'sedang_dinas' => $total_dinas,
                    'persen_kehadiran' => $persen
                ]
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // API: Submit absen manual
    public function submit_absen_manual()
    {
        header('Content-Type: application/json');
        
        $recid_karyawan = $this->input->post('recid_karyawan');
        $tanggal = $this->input->post('tanggal');
        $jam_masuk = $this->input->post('jam_masuk');
        $keterangan = $this->input->post('keterangan');
        
        if (empty($recid_karyawan) || empty($tanggal) || empty($jam_masuk)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }
        
        try {
            // Get karyawan data
            $karyawan = $this->db->select('nik, nama_karyawan')
                                 ->where('recid_karyawan', $recid_karyawan)
                                 ->get('karyawan')
                                 ->row();
            
            if (!$karyawan) {
                echo json_encode(['success' => false, 'message' => 'Karyawan tidak ditemukan']);
                return;
            }
            
            // Get PIN from mapping
            $pin_map = $this->db->select('pin')
                                ->where('recid_karyawan', $recid_karyawan)
                                ->get('karyawan_pin_map')
                                ->row();
            
            if (!$pin_map) {
                echo json_encode(['success' => false, 'message' => 'PIN karyawan belum di-mapping']);
                return;
            }
            
            $pin = $pin_map->pin;
            
            // Load db2 connection for master_finger database (should already be loaded in constructor)
            if (!isset($this->db2)) {
                $this->db2 = $this->load->database('absen2', TRUE);
            }
            
            // Check if already has attendance on this date
            $check_query = "SELECT COUNT(*) as total FROM absensi WHERE nik = ? AND DATE(waktu) = ?";
            $check = $this->db2->query($check_query, [$pin, $tanggal])->row();
            
            if ($check && $check->total > 0) {
                echo json_encode(['success' => false, 'message' => 'Karyawan sudah memiliki data absen pada tanggal ini'], JSON_PRETTY_PRINT);
                return;
            }
            
            // Insert absen manual to master_finger.absensi
            $waktu_masuk = $tanggal . ' ' . $jam_masuk . ':00';
            
            $data_absen = [
                'nik' => $pin,
                'waktu' => $waktu_masuk,
                'status' => 1, // Status 1 = check-in
                'tipe' => 15, // Manual entry type
                'ip_mesin' => '0.0.0.0' // Manual entry indicator
            ];
            
            $inserted = $this->db2->insert('absensi', $data_absen);
            
            if (!$inserted) {
                $db_error = $this->db2->error();
                echo json_encode([
                    'success' => false, 
                    'message' => 'Gagal menyimpan data absen: ' . $db_error['message']
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            // Log activity
            $user_id = $this->session->userdata('kar_id');
            log_message('info', 'Absen manual: ' . $karyawan->nama_karyawan . ' (' . $karyawan->nik . ') - ' . $tanggal . ' ' . $jam_masuk . ' by user_id: ' . $user_id);
            
            echo json_encode([
                'success' => true,
                'message' => 'Absen manual berhasil dicatat untuk ' . $karyawan->nama_karyawan . ' pada jam ' . $jam_masuk
            ], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            log_message('error', 'submit_absen_manual error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'error_detail' => ENVIRONMENT === 'development' ? $e->getTraceAsString() : null
            ], JSON_PRETTY_PRINT);
        }
    }

    // API: Simpan izin untuk karyawan yang belum absen (master_finger)
    public function simpan_izin_belum_absen()
    {
        header('Content-Type: application/json');

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            echo json_encode(['success' => false, 'message' => 'Sesi habis. Silakan login ulang.']);
            return;
        }

        $recid_karyawan = (int) $this->input->post('recid_karyawan');
        $tanggal        = $this->input->post('tanggal');
        $jenis          = strtoupper(trim($this->input->post('jenis')));
        $keterangan     = trim($this->input->post('keterangan'));

        // Validasi input dasar
        if (empty($recid_karyawan) || empty($tanggal) || empty($jenis)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Whitelist jenis izin
        $allowed = ['SAKIT','MANGKIR','TANPA KETERANGAN','CM','CT','CK','CS','CN'];
        if (!in_array($jenis, $allowed, true)) {
            echo json_encode(['success' => false, 'message' => 'Jenis izin tidak dikenali']);
            return;
        }

        try {
            // Pastikan koneksi master_finger
            if (!isset($this->db2)) {
                $this->db2 = $this->load->database('absen2', TRUE);
            }

            // Cek duplikasi per-hari per-jenis
            $dup = $this->db2->select('izin_recid')
                              ->from('izin_absen')
                              ->where('recid_karyawan', $recid_karyawan)
                              ->where('tgl_mulai', $tanggal)
                              ->where('jenis', $jenis)
                              ->where('is_delete', '0')
                              ->get()->num_rows();

            if ($dup > 0) {
                echo json_encode(['success' => false, 'message' => 'Izin sudah tercatat untuk tanggal dan jenis ini']);
                return;
            }

            $user_id = (int) $this->session->userdata('kar_id');
            $data = [
                'recid_karyawan' => $recid_karyawan,
                'tgl_mulai'      => $tanggal,
                'tgl_selesai'    => NULL,
                'jenis'          => $jenis,
                'keterangan'     => $keterangan ?: NULL,
                'perlu_validasi' => 0,
                'validated_by'   => $user_id ?: NULL,
                'validated_date' => date('Y-m-d H:i:s'),
                'is_delete'      => 0,
                'crt_by'         => $user_id ?: NULL,
                'crt_date'       => date('Y-m-d H:i:s')
            ];

            $ok = $this->db2->insert('izin_absen', $data);
            if (!$ok) {
                $err = $this->db2->error();
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan izin: ' . ($err['message'] ?? 'unknown')]);
                return;
            }

            echo json_encode(['success' => true, 'message' => 'Izin berhasil disimpan']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // View: Log/Daftar Izin (master_finger.izin_absen)
    public function izin_log()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();

        // Default filter range: 7 hari terakhir
        $data['start_date'] = date('Y-m-d', strtotime('-7 days'));
        $data['end_date'] = date('Y-m-d');

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/izin_log_view', $data);
        $this->load->view('layout/a_footer');
    }

    // API: Ambil data daftar izin dengan filter tanggal/q/bagian
    public function get_izin_log()
    {
        header('Content-Type: application/json');

        $start = $this->input->get('start_date') ?: $this->input->post('start_date') ?: date('Y-m-d', strtotime('-7 days'));
        $end   = $this->input->get('end_date') ?: $this->input->post('end_date') ?: date('Y-m-d');
        $q     = trim($this->input->get('q') ?: $this->input->post('q') ?: '');
        $bag   = trim($this->input->get('bagian') ?: $this->input->post('bagian') ?: '');

        try {
            $params = [$start, $end];
            $whereExtra = '';
            if ($q !== '') {
                $whereExtra .= " AND (k.nama_karyawan LIKE ? OR k.nik LIKE ? OR ia.jenis LIKE ? OR ia.keterangan LIKE ?)";
                $params[] = '%' . $q . '%';
                $params[] = '%' . $q . '%';
                $params[] = '%' . $q . '%';
                $params[] = '%' . $q . '%';
            }
            if ($bag !== '') {
                $whereExtra .= " AND k.recid_bag = ?";
                $params[] = $bag;
            }

            // Query cross-db: master_finger.izin_absen join hris.karyawan & bagian
            $sql = "
                SELECT 
                    ia.izin_recid,
                    ia.recid_karyawan,
                    k.nik,
                    k.nama_karyawan,
                    b.nama_bag AS nama_bagian,
                    ia.tgl_mulai,
                    ia.tgl_selesai,
                    ia.jenis,
                    ia.keterangan,
                    ia.perlu_validasi,
                    ia.validated_by,
                    kv.nama_karyawan AS validated_nama,
                    ia.validated_date,
                    ia.is_delete,
                    ia.crt_by,
                    kc.nama_karyawan AS crt_nama,
                    ia.crt_date
                FROM master_finger.izin_absen ia
                LEFT JOIN hris.karyawan k ON ia.recid_karyawan = k.recid_karyawan
                LEFT JOIN hris.bagian b ON k.recid_bag = b.recid_bag
                LEFT JOIN hris.karyawan kv ON ia.validated_by = kv.recid_karyawan
                LEFT JOIN hris.karyawan kc ON ia.crt_by = kc.recid_karyawan
                WHERE ia.is_delete = '0'
                  AND ia.tgl_mulai BETWEEN ? AND ?
                $whereExtra
                ORDER BY ia.tgl_mulai DESC, k.nama_karyawan ASC, ia.izin_recid DESC
            ";

            $rows = $this->db->query($sql, $params)->result_array();

            echo json_encode([
                'success' => true,
                'data' => $rows,
                'total' => count($rows),
                'periode' => $start . ' s/d ' . $end
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    // View: Rekap Statistik per Bagian (harian)
    public function statistik_bagian()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }

        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->db->select('k.*, b.nama_bag as indeks_hr, j.nama_jbtn as indeks_jabatan, j.tingkatan, k.recid_bag as recid_struktur')
                                     ->from('karyawan k')
                                     ->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
                                     ->join('jabatan j', 'k.recid_jbtn = j.recid_jbtn', 'left')
                                     ->where('k.recid_karyawan', $usr)
                                     ->get()
                                     ->result();

        $data['tanggal'] = $tanggal;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('rekap/statistik_bagian_view', $data);
        $this->load->view('layout/a_footer');
    }

    // API: Get rekap statistik per-bagian untuk satu tanggal
    public function get_statistik_bagian()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal') ?: $this->input->post('tanggal') ?: date('Y-m-d');

                try {
                                                // Optimized: pre-aggregate hadir & non-hadir with time range (no DATE() on waktu)
                                                $start = $tanggal.' 00:00:00';
                                                $end   = date('Y-m-d 00:00:00', strtotime($tanggal.' +1 day'));

                                                $sub_aktif = "SELECT recid_karyawan, recid_bag FROM hris.karyawan WHERE UPPER(sts_aktif)='AKTIF'";
                                                $sub_hadir_kar = "
                                                        SELECT DISTINCT pm.recid_karyawan
                                                        FROM master_finger.absensi a
                                                        JOIN hris.karyawan_pin_map pm ON pm.pin = a.nik
                                                        WHERE a.waktu >= ? AND a.waktu < ?
                                                ";
                                                $sub_hadir_bag = "
                                                        SELECT ak.recid_bag, COUNT(DISTINCT ak.recid_karyawan) AS hadir
                                                        FROM ( $sub_aktif ) ak
                                                        JOIN ( $sub_hadir_kar ) hk ON hk.recid_karyawan = ak.recid_karyawan
                                                        GROUP BY ak.recid_bag
                                                ";
                                                $sub_non_hadir = "
                                                        SELECT ak.recid_karyawan, ak.recid_bag
                                                        FROM ( $sub_aktif ) ak
                                                        LEFT JOIN ( $sub_hadir_kar ) hk ON hk.recid_karyawan = ak.recid_karyawan
                                                        WHERE hk.recid_karyawan IS NULL
                                                ";
                                                $sub_sakit_bag = "
                                                        SELECT nh.recid_bag, COUNT(DISTINCT nh.recid_karyawan) AS s
                                                        FROM ( $sub_non_hadir ) nh
                                                        JOIN master_finger.izin_absen ia ON ia.recid_karyawan = nh.recid_karyawan
                                                        WHERE ia.is_delete='0' AND ia.jenis='SAKIT'
                                                            AND ia.tgl_mulai <= ? AND (ia.tgl_selesai IS NULL OR ? <= ia.tgl_selesai)
                                                        GROUP BY nh.recid_bag
                                                ";
                                                $sub_izin_bag = "
                                                        SELECT nh.recid_bag, COUNT(DISTINCT nh.recid_karyawan) AS i
                                                        FROM ( $sub_non_hadir ) nh
                                                        JOIN master_finger.izin_absen ia ON ia.recid_karyawan = nh.recid_karyawan
                                                        WHERE ia.is_delete='0' AND ia.jenis IN ('CM','CT','CK','CS','CN')
                                                            AND ia.tgl_mulai <= ? AND (ia.tgl_selesai IS NULL OR ? <= ia.tgl_selesai)
                                                        GROUP BY nh.recid_bag
                                                ";

                                                $sql = "
                                                        SELECT 
                                                            b.recid_bag,
                                                            b.nama_bag AS nama_bagian,
                                                            COUNT(ak.recid_karyawan) AS total,
                                                            COALESCE(hb.hadir,0) AS hadir,
                                                            COALESCE(sb.s,0) AS s,
                                                            COALESCE(ib.i,0) AS i,
                                                            GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0) - COALESCE(sb.s,0) - COALESCE(ib.i,0), 0) AS m,
                                                            GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0), 0) AS tidak_hadir,
                                                            ROUND((GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0), 0) * 100.0) / NULLIF(COUNT(ak.recid_karyawan),0), 2) AS persen_tidak_hadir
                                                        FROM ( $sub_aktif ) ak
                                                        JOIN hris.bagian b ON b.recid_bag = ak.recid_bag
                                                        LEFT JOIN ( $sub_hadir_bag ) hb ON hb.recid_bag = b.recid_bag
                                                        LEFT JOIN ( $sub_sakit_bag ) sb ON sb.recid_bag = b.recid_bag
                                                        LEFT JOIN ( $sub_izin_bag ) ib ON ib.recid_bag = b.recid_bag
                                                        GROUP BY b.recid_bag, b.nama_bag
                                                        ORDER BY b.nama_bag ASC
                                                ";

                                                // Placeholders order: hadir_bag(start,end) + sakit_bag(nh start,end + range) + izin_bag(nh start,end + range)
                                                $params = [
                                                        $start, $end,        // hadir_bag -> sub_hadir_kar
                                                        $start, $end,        // non_hadir inside sakit_bag -> sub_hadir_kar
                                                        $tanggal, $tanggal,  // sakit range
                                                        $start, $end,        // non_hadir inside izin_bag -> sub_hadir_kar
                                                        $tanggal, $tanggal   // izin range
                                                ];
                                                $rows = $this->db->query($sql, $params)->result_array();

            // Hitung total footer
            $footer = [
                'nama_bagian' => 'TOTAL',
                'total' => 0, 'hadir' => 0, 's' => 0, 'i' => 0, 'm' => 0, 'tidak_hadir' => 0,
                'persen_tidak_hadir' => 0
            ];
            foreach ($rows as $r) {
                $footer['total'] += (int)$r['total'];
                $footer['hadir'] += (int)$r['hadir'];
                $footer['s'] += (int)$r['s'];
                $footer['i'] += (int)$r['i'];
                $footer['m'] += (int)$r['m'];
                $footer['tidak_hadir'] += (int)$r['tidak_hadir'];
            }
            $footer['persen_tidak_hadir'] = $footer['total'] > 0 ? round(($footer['tidak_hadir'] / $footer['total']) * 100, 2) : 0;

            echo json_encode([
                'success' => true,
                'tanggal' => $tanggal,
                'data' => $rows,
                'footer' => $footer
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    // Export XLSX: Rekap Statistik Per Bagian (harian)
    public function export_statistik_bagian()
    {
        try {
            $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

            // Build same dataset as get_statistik_bagian()
            $start = $tanggal . ' 00:00:00';
            $end   = date('Y-m-d 00:00:00', strtotime($tanggal.' +1 day'));

            $sub_aktif = "SELECT recid_karyawan, recid_bag FROM hris.karyawan WHERE UPPER(sts_aktif)='AKTIF'";
            $sub_hadir_kar = "
                SELECT DISTINCT pm.recid_karyawan
                FROM master_finger.absensi a
                JOIN hris.karyawan_pin_map pm ON pm.pin = a.nik
                WHERE a.waktu >= ? AND a.waktu < ?
            ";
            $sub_hadir_bag = "
                SELECT ak.recid_bag, COUNT(DISTINCT ak.recid_karyawan) AS hadir
                FROM ( $sub_aktif ) ak
                JOIN ( $sub_hadir_kar ) hk ON hk.recid_karyawan = ak.recid_karyawan
                GROUP BY ak.recid_bag
            ";
            $sub_non_hadir = "
                SELECT ak.recid_karyawan, ak.recid_bag
                FROM ( $sub_aktif ) ak
                LEFT JOIN ( $sub_hadir_kar ) hk ON hk.recid_karyawan = ak.recid_karyawan
                WHERE hk.recid_karyawan IS NULL
            ";
            $sub_sakit_bag = "
                SELECT nh.recid_bag, COUNT(DISTINCT nh.recid_karyawan) AS s
                FROM ( $sub_non_hadir ) nh
                JOIN master_finger.izin_absen ia ON ia.recid_karyawan = nh.recid_karyawan
                WHERE ia.is_delete='0' AND ia.jenis='SAKIT'
                  AND ia.tgl_mulai <= ? AND (ia.tgl_selesai IS NULL OR ? <= ia.tgl_selesai)
                GROUP BY nh.recid_bag
            ";
            $sub_izin_bag = "
                SELECT nh.recid_bag, COUNT(DISTINCT nh.recid_karyawan) AS i
                FROM ( $sub_non_hadir ) nh
                JOIN master_finger.izin_absen ia ON ia.recid_karyawan = nh.recid_karyawan
                WHERE ia.is_delete='0' AND ia.jenis IN ('CM','CT','CK','CS','CN')
                  AND ia.tgl_mulai <= ? AND (ia.tgl_selesai IS NULL OR ? <= ia.tgl_selesai)
                GROUP BY nh.recid_bag
            ";

            $sql = "
                SELECT 
                  b.recid_bag,
                  b.nama_bag AS nama_bagian,
                  COUNT(ak.recid_karyawan) AS total,
                  COALESCE(hb.hadir,0) AS hadir,
                  COALESCE(sb.s,0) AS s,
                  COALESCE(ib.i,0) AS i,
                  GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0) - COALESCE(sb.s,0) - COALESCE(ib.i,0), 0) AS m,
                  GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0), 0) AS tidak_hadir,
                  ROUND((GREATEST(COUNT(ak.recid_karyawan) - COALESCE(hb.hadir,0), 0) * 100.0) / NULLIF(COUNT(ak.recid_karyawan),0), 2) AS persen_tidak_hadir
                FROM ( $sub_aktif ) ak
                JOIN hris.bagian b ON b.recid_bag = ak.recid_bag
                LEFT JOIN ( $sub_hadir_bag ) hb ON hb.recid_bag = b.recid_bag
                LEFT JOIN ( $sub_sakit_bag ) sb ON sb.recid_bag = b.recid_bag
                LEFT JOIN ( $sub_izin_bag ) ib ON ib.recid_bag = b.recid_bag
                GROUP BY b.recid_bag, b.nama_bag
                ORDER BY b.nama_bag ASC
            ";
            $params = [
                $start, $end,
                $start, $end,
                $tanggal, $tanggal,
                $start, $end,
                $tanggal, $tanggal
            ];
            $rows = $this->db->query($sql, $params)->result_array();

            // Footer totals
            $footer = [
                'total' => 0, 'hadir' => 0, 's' => 0, 'i' => 0, 'm' => 0, 'tidak_hadir' => 0
            ];
            foreach ($rows as $r) {
                $footer['total'] += (int)$r['total'];
                $footer['hadir'] += (int)$r['hadir'];
                $footer['s'] += (int)$r['s'];
                $footer['i'] += (int)$r['i'];
                $footer['m'] += (int)$r['m'];
                $footer['tidak_hadir'] += (int)$r['tidak_hadir'];
            }
            $footer['persen'] = $footer['total'] > 0 ? round(($footer['tidak_hadir'] / $footer['total']) * 100, 2) : 0;

            // Build XLSX using PhpSpreadsheet
            if (ob_get_level() > 0) { @ob_end_clean(); }
            @ob_start();

            require_once APPPATH . '../vendor/autoload.php';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Statistik Bagian');

            // Title row
            $sheet->setCellValue('A1', 'Rekap Statistik Per Bagian');
            $sheet->setCellValue('A2', 'Tanggal: ' . date('d/m/Y', strtotime($tanggal)));
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            
            // Header row
            $headers = ['Bagian', 'Total', 'Hadir', 'S (Sakit)', 'I (Izin)', 'M (Mangkir)', 'Tidak Hadir', '% Tidak Hadir'];
            $row = 4; $col = 1;
            foreach ($headers as $h) {
                $sheet->setCellValueByColumnAndRow($col, $row, $h);
                $col++;
            }
            $sheet->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);
            $sheet->freezePane('A'.($row+1));

            // Data rows
            $row++;
            foreach ($rows as $r) {
                $col = 1;
                $sheet->setCellValueByColumnAndRow($col++, $row, $r['nama_bagian']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['total']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['hadir']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['s']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['i']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['m']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (int)$r['tidak_hadir']);
                $sheet->setCellValueByColumnAndRow($col++, $row, (float)$r['persen_tidak_hadir']);
                $row++;
            }

            // Footer totals row
            $sheet->setCellValue('A'.$row, 'TOTAL');
            $sheet->setCellValue('B'.$row, $footer['total']);
            $sheet->setCellValue('C'.$row, $footer['hadir']);
            $sheet->setCellValue('D'.$row, $footer['s']);
            $sheet->setCellValue('E'.$row, $footer['i']);
            $sheet->setCellValue('F'.$row, $footer['m']);
            $sheet->setCellValue('G'.$row, $footer['tidak_hadir']);
            $sheet->setCellValue('H'.$row, $footer['persen']);
            $sheet->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);

            // Auto-size columns
            foreach (range('A', 'H') as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }

            $filename = 'statistik_bagian_'.date('Ymd', strtotime($tanggal)).'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            @ob_end_flush();
            exit;
        } catch (Exception $e) {
            // Fallback JSON error if headers already sent
            if (!headers_sent()) header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // API: Debug statistik bagian untuk melacak sumber data Hadir/Mapping/Absensi
    public function debug_statistik_bagian()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

        try {
            // Pastikan koneksi db2 (master_finger)
            if (!isset($this->db2)) {
                $this->db2 = $this->load->database('absen2', TRUE);
            }

            // 1) Ringkasan absensi di master_finger hari itu
            $tot_abs_q = $this->db2->select('COUNT(*) AS c')->where('DATE(waktu)', $tanggal)->get('absensi')->row_array();
            $tot_abs = (int)($tot_abs_q['c'] ?? 0);
            $tot_pin_q = $this->db2->select('COUNT(DISTINCT nik) AS c')->where('DATE(waktu)', $tanggal)->get('absensi')->row_array();
            $tot_pin = (int)($tot_pin_q['c'] ?? 0);

            // 2) Distinct karyawan (via mapping) yang tap pada tanggal tsb
            $mapped_karyawan_q = $this->db->query(
                "SELECT COUNT(DISTINCT pm.recid_karyawan) AS c
                 FROM hris.karyawan_pin_map pm
                 INNER JOIN master_finger.absensi a ON pm.pin = a.nik AND DATE(a.waktu) = ?",
                [$tanggal]
            )->row_array();
            $mapped_karyawan = (int)($mapped_karyawan_q['c'] ?? 0);

            // 3) Top pins yang tap tapi tidak ada di mapping
            $unmapped_pins = $this->db->query(
                "SELECT a.nik, COUNT(*) AS cnt
                   FROM master_finger.absensi a
              LEFT JOIN hris.karyawan_pin_map pm ON pm.pin = a.nik
                  WHERE DATE(a.waktu) = ? AND pm.recid_karyawan IS NULL
               GROUP BY a.nik
               ORDER BY cnt DESC
                  LIMIT 20",
                [$tanggal]
            )->result_array();

            // 4) Contoh karyawan aktif yang belum memiliki pin mapping
            $no_pin_karyawan = $this->db->query(
                "SELECT k.recid_karyawan, k.nik, k.nama_karyawan, b.nama_bag
                   FROM hris.karyawan k
              LEFT JOIN hris.karyawan_pin_map pm ON pm.recid_karyawan = k.recid_karyawan
              LEFT JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                  WHERE UPPER(k.sts_aktif) = 'AKTIF' AND pm.pin IS NULL
                  LIMIT 20"
            )->result_array();

            // 5) Hadir by bagian (versi alternatif langsung dari absensi -> pin_map -> karyawan)
            $alt_hadir = $this->db->query(
                "SELECT b.recid_bag, b.nama_bag, COUNT(DISTINCT k.recid_karyawan) AS hadir
                   FROM master_finger.absensi a
                   JOIN hris.karyawan_pin_map pm ON pm.pin = a.nik
                   JOIN hris.karyawan k ON k.recid_karyawan = pm.recid_karyawan AND UPPER(k.sts_aktif)='AKTIF'
                   JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                  WHERE DATE(a.waktu) = ?
               GROUP BY b.recid_bag, b.nama_bag
               ORDER BY b.nama_bag",
                [$tanggal]
            )->result_array();

            // 6) Sample mapping rows untuk inspeksi
            $sample = $this->db->query(
                "SELECT a.nik AS pin, a.waktu,
                        pm.recid_karyawan, k.nik AS nik_karyawan, k.nama_karyawan, b.nama_bag
                   FROM master_finger.absensi a
              LEFT JOIN hris.karyawan_pin_map pm ON pm.pin = a.nik
              LEFT JOIN hris.karyawan k ON k.recid_karyawan = pm.recid_karyawan
              LEFT JOIN hris.bagian b ON b.recid_bag = k.recid_bag
                  WHERE DATE(a.waktu) = ?
               ORDER BY a.waktu ASC
                  LIMIT 30",
                [$tanggal]
            )->result_array();

            echo json_encode([
                'success' => true,
                'tanggal' => $tanggal,
                'absensi_summary' => [
                    'total_rows' => $tot_abs,
                    'distinct_pins' => $tot_pin,
                    'distinct_karyawan_mapped' => $mapped_karyawan
                ],
                'alt_hadir_by_bagian' => $alt_hadir,
                'unmapped_pins' => $unmapped_pins,
                'active_karyawan_no_pin_map_sample' => $no_pin_karyawan,
                'sample_mapped_rows' => $sample
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

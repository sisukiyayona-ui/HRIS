<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use ZK\ZKLib;

class M_finger extends CI_Model
{
    /** @var CI_DB_query_builder */
    protected $db_finger;
    protected $timeout = 3;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // pastikan group 'master_finger' ada di application/config/database.php
        $this->db_finger = $this->load->database('master_finger', true);
    }

    public function pull_from_ip(string $ip, int $port = 4370): array
    {
        $zk = new ZKLib($ip, $port);
        if (!$zk->connect()) {
            return ['ok'=>false, 'ip'=>$ip, 'err'=>'connect failed'];
        }

        $zk->disableDevice();
        // Tergantung lib-nya, biasanya namanya getAttendance()
        // Cek struktur arraynya di var_dump saat pertama kali jalan
        $logs = $zk->getAttendance();
        $zk->enableDevice();
        $zk->disconnect();

        foreach ($logs as $r) {
            $this->db_finger->insert('raw_log', [
                'ip'        => $ip,
                'pin'       => $r['uid'] ?? ($r['pin'] ?? null),
                'status'    => $r['state'] ?? null,
                'datetime'  => isset($r['timestamp']) ? date('Y-m-d H:i:s', $r['timestamp']) : ($r['datetime'] ?? null),
                'created_at'=> date('Y-m-d H:i:s'),
            ]);
        }

        return ['ok'=>true, 'ip'=>$ip, 'count'=>count($logs)];
    }
}

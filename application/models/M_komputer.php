<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_komputer extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil semua record komputer.
     * Jika tabel 'komputer' tidak ada, kembalikan contoh data sebagai fallback.
     * @return array
     */
    public function get_all()
    {
        if ($this->db->table_exists('komputer')) {
            return $this->db->select('*')->from('komputer')->where('is_delete', '0')->order_by('nama_user', 'asc')->get()->result_array();
        }

        // Fallback sample data (untuk dokumentasi / demo lokal tanpa DB)
        return [
            [
                'nama_user' => 'Budi Santoso',
                'ip_komputer' => '192.168.1.10',
                'bagian' => 'IT',
                'password_pc' => 'pass1234',
                'email' => 'budi.santoso@example.com',
                'parent_email' => 'parent@example.com',
                'password_email' => 'emailpass'
            ],
            [
                'nama_user' => 'Siti Aminah',
                'ip_komputer' => '192.168.1.11',
                'bagian' => 'Admin',
                'password_pc' => 'siti2020',
                'email' => 'siti.aminah@example.com',
                'parent_email' => '',
                'password_email' => 'sitipass'
            ]
        ];
    }
}

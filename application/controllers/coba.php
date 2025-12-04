<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

    public function index()
    {
        $this->load->database();

        if ($this->db->conn_id) {
            echo "✅ Koneksi ke database BERHASIL!";
        } else {
            echo "❌ Gagal konek ke database.";
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_kontrak');
        // Check if user is logged in
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
        }
    }

    /**
     * Create a new contract
     */
    public function create()
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $recid_karyawan = $this->input->post('recid_karyawan');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');

        // Validate input
        if (empty($recid_karyawan) || empty($tgl_mulai) || empty($tgl_akhir)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi']);
            return;
        }

        // Prepare data
        $data = array(
            'recid_karyawan' => $recid_karyawan,
            'tgl_mulai' => $tgl_mulai,
            'tgl_akhir' => $tgl_akhir,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Insert data
        if ($this->M_kontrak->insert_kontrak($data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    /**
     * Update an existing contract
     * @param int $recid_kontrak Contract ID
     */
    public function update($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');

        // Validate input
        if (empty($tgl_mulai) || empty($tgl_akhir)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi']);
            return;
        }

        // Prepare data
        $data = array(
            'tgl_mulai' => $tgl_mulai,
            'tgl_akhir' => $tgl_akhir,
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Update data
        if ($this->M_kontrak->update_kontrak($recid_kontrak, $data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    /**
     * Delete a contract
     * @param int $recid_kontrak Contract ID
     */
    public function delete($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        // Delete data
        if ($this->M_kontrak->delete_kontrak($recid_kontrak)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
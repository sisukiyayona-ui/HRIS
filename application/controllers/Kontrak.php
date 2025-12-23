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
     * Check if a contract is the only one for an employee
     * @param int $recid_kontrak Contract ID
     */
    public function check_single_contract($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        // Get contract details to get employee ID
        $this->load->model('M_kontrak');
        $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
        if (!$contract) {
            echo json_encode(['error' => 'Kontrak tidak ditemukan']);
            return;
        }

        // Count total contracts for this employee
        $total_contracts = $this->M_kontrak->get_total_contracts_by_karyawan($contract->recid_karyawan);
        $is_single_contract = ($total_contracts == 1);

        echo json_encode(['is_single_contract' => $is_single_contract]);
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

        // Get contract details to check if it's the only contract and if status is 'diputus'
        $this->load->model('M_kontrak');
        $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
        if (!$contract) {
            echo json_encode(['status' => 'error', 'message' => 'Kontrak tidak ditemukan']);
            return;
        }

        $reset_employee = $this->input->post('reset_employee');
        $total_contracts = $this->M_kontrak->get_total_contracts_by_karyawan($contract->recid_karyawan);
        $is_single_contract = $total_contracts == 1;
        
        // Determine if we should reset employee data
        $should_reset_employee = false;
        if ($contract->status_kontrak == 'diputus' && $is_single_contract) {
            $should_reset_employee = true;
        }

        // Delete the contract
        if ($this->M_kontrak->delete_kontrak($recid_kontrak)) {
            // If this was the only contract and it had status 'diputus', reset employee status
            if ($should_reset_employee) {
                $this->load->model('M_hris');
                $employee_data = array(
                    'sts_aktif' => 'Aktif',
                    'tgl_keluar' => NULL,
                    'alasan_keluar' => NULL,
                    'mdf_date' => date('Y-m-d H:i:s')
                );
                $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus kontrak']);
        }
    }

    /**
     * End/Resign a contract
     * @param int $recid_kontrak Contract ID
     */
    public function end_contract($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $tgl_resign = $this->input->post('tgl_resign');
        $alasan_resign = $this->input->post('alasan_resign');

        // Validate input
        if (empty($tgl_resign) || empty($alasan_resign)) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal resign dan alasan harus diisi']);
            return;
        }

        // Get contract details to get employee ID
        $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
        if (!$contract) {
            echo json_encode(['status' => 'error', 'message' => 'Kontrak tidak ditemukan']);
            return;
        }

        // Update contract status to 'diputus'
        if ($this->M_kontrak->update_status_diputus($recid_kontrak, $tgl_resign, $alasan_resign)) {
            // Update employee status in karyawan table
            $this->load->model('M_hris');
            $employee_data = array(
                'sts_aktif' => 'Resign',
                'tgl_keluar' => $tgl_resign,
                'alasan_keluar' => $alasan_resign,
                'mdf_date' => date('Y-m-d H:i:s')
            );
            $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    /**
     * Complete a contract (normal end)
     * @param int $recid_kontrak Contract ID
     */
    public function complete_contract($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        // Update contract status to 'selesai'
        if ($this->M_kontrak->update_status_selesai($recid_kontrak)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
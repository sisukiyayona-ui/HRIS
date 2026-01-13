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
            'status_kontrak' => 'aktif', // Set as active when creating
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Insert data
        if ($this->M_kontrak->insert_kontrak($data)) {
            // Update employee status to 'Aktif' and clear termination fields
            $this->load->model('M_hris');
            $employee_data = array(
                'sts_aktif' => 'Aktif',
                'tgl_keluar' => NULL, // Clear termination date
                'tgl_akhir_kontrak' => $tgl_akhir, // Update contract end date
                'mdf_date' => date('Y-m-d H:i:s')
            );
            $this->M_hris->update_karyawan($recid_karyawan, $employee_data);
            
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
            // Get contract details to update employee status if this is an active contract
            $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
            if ($contract && $contract->status_kontrak == 'aktif') {
                $this->load->model('M_hris');
                $employee_data = array(
                    'sts_aktif' => 'Aktif',
                    'tgl_akhir_kontrak' => $tgl_akhir, // Update contract end date
                    'mdf_date' => date('Y-m-d H:i:s')
                );
                $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
            }
            
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
        
        // Delete the contract
        if ($this->M_kontrak->delete_kontrak($recid_kontrak)) {
            // After deletion, check remaining contracts and update employee status accordingly
            $remaining_contracts = $this->M_kontrak->get_kontrak_by_karyawan($contract->recid_karyawan);
            
            $this->load->model('M_hris');
            
            // If no contracts remain, set employee status to 'Aktif'
            if (empty($remaining_contracts)) {
                $employee_data = array(
                    'sts_aktif' => 'Aktif',
                    'tgl_keluar' => NULL,
                    'alasan_keluar' => NULL,
                    'tgl_akhir_kontrak' => NULL,
                    'mdf_date' => date('Y-m-d H:i:s')
                );
                $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
            } else {
                // Check if there are any active contracts remaining
                $has_active_contract = false;
                $latest_end_date = null;
                
                foreach ($remaining_contracts as $rem_contract) {
                    if ($rem_contract->status_kontrak == 'aktif') {
                        $has_active_contract = true;
                        break;
                    }
                    // Track the latest end date among non-active contracts
                    if (!$latest_end_date || $rem_contract->tgl_akhir > $latest_end_date) {
                        $latest_end_date = $rem_contract->tgl_akhir;
                    }
                }
                
                if ($has_active_contract) {
                    // If there's an active contract, employee remains active
                    $latest_active_end_date = null;
                    foreach ($remaining_contracts as $rem_contract) {
                        if ($rem_contract->status_kontrak == 'aktif' && (!$latest_active_end_date || $rem_contract->tgl_akhir > $latest_active_end_date)) {
                            $latest_active_end_date = $rem_contract->tgl_akhir;
                        }
                    }
                    
                    $employee_data = array(
                        'sts_aktif' => 'Aktif',
                        'tgl_akhir_kontrak' => $latest_active_end_date,
                        'mdf_date' => date('Y-m-d H:i:s')
                    );
                    $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
                } else {
                    // If no active contracts remain, employee becomes 'Tidak Aktif'
                    $employee_data = array(
                        'sts_aktif' => 'Tidak Aktif',
                        'tgl_akhir_kontrak' => $latest_end_date, // Use the latest end date from remaining contracts
                        'mdf_date' => date('Y-m-d H:i:s')
                    );
                    $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
                }
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
        if ($this->M_kontrak->update_status_diputus($recid_kontrak, $tgl_resign, $alasan_resign, 'Resign')) {
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
            // After completing the contract, check remaining contracts and update employee status
            $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
            if ($contract) {
                $remaining_contracts = $this->M_kontrak->get_kontrak_by_karyawan($contract->recid_karyawan);
                $this->load->model('M_hris');
                
                // If no contracts remain, set employee status to 'Aktif'
                if (empty($remaining_contracts)) {
                    $employee_data = array(
                        'sts_aktif' => 'Aktif',
                        'tgl_keluar' => NULL,
                        'alasan_keluar' => NULL,
                        'tgl_akhir_kontrak' => NULL,
                        'mdf_date' => date('Y-m-d H:i:s')
                    );
                    $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
                } else {
                    // Check if there are any active contracts remaining
                    $has_active_contract = false;
                    $latest_end_date = null;
                    
                    foreach ($remaining_contracts as $rem_contract) {
                        if ($rem_contract->status_kontrak == 'aktif') {
                            $has_active_contract = true;
                            break;
                        }
                        // Track the latest end date among non-active contracts
                        if (!$latest_end_date || $rem_contract->tgl_akhir > $latest_end_date) {
                            $latest_end_date = $rem_contract->tgl_akhir;
                        }
                    }
                    
                    if ($has_active_contract) {
                        // If there's an active contract, employee remains active
                        $latest_active_end_date = null;
                        foreach ($remaining_contracts as $rem_contract) {
                            if ($rem_contract->status_kontrak == 'aktif' && (!$latest_active_end_date || $rem_contract->tgl_akhir > $latest_active_end_date)) {
                                $latest_active_end_date = $rem_contract->tgl_akhir;
                            }
                        }
                        
                        $employee_data = array(
                            'sts_aktif' => 'Aktif',
                            'tgl_akhir_kontrak' => $latest_active_end_date,
                            'mdf_date' => date('Y-m-d H:i:s')
                        );
                        $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
                    } else {
                        // If no active contracts remain, employee becomes 'Tidak Aktif'
                        $employee_data = array(
                            'sts_aktif' => 'Tidak Aktif',
                            'tgl_akhir_kontrak' => $latest_end_date, // Use the latest end date from remaining contracts
                            'mdf_date' => date('Y-m-d H:i:s')
                        );
                        $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
                    }
                }
            }
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    /**
     * Set employee to non-aktif status
     * @param int $recid_kontrak Contract ID
     */
    public function non_aktif($recid_kontrak)
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $jenis_non_aktif = $this->input->post('jenis_non_aktif');
        $tgl_non_aktif = $this->input->post('tgl_non_aktif');
        $keterangan_non_aktif = $this->input->post('keterangan_non_aktif');

        // Validate input
        if (empty($jenis_non_aktif) || empty($tgl_non_aktif) || empty($keterangan_non_aktif)) {
            echo json_encode(['status' => 'error', 'message' => 'Jenis non aktif, tanggal non aktif, dan keterangan harus diisi']);
            return;
        }

        // Get contract details to get employee ID
        $contract = $this->M_kontrak->get_kontrak_by_id($recid_kontrak);
        if (!$contract) {
            echo json_encode(['status' => 'error', 'message' => 'Kontrak tidak ditemukan']);
            return;
        }

        // Update contract status to 'diputus'
        if ($this->M_kontrak->update_status_diputus($recid_kontrak, $tgl_non_aktif, $keterangan_non_aktif, $jenis_non_aktif)) {
            // Update employee status in karyawan table
            $this->load->model('M_hris');
            $employee_data = array(
                'sts_aktif' => 'Tidak Aktif', // Changed from 'Resign' to 'Tidak Aktif'
                'tgl_keluar' => $tgl_non_aktif,
                'alasan_keluar' => $jenis_non_aktif . ': ' . $keterangan_non_aktif,
                'mdf_date' => date('Y-m-d H:i:s')
            );
            $this->M_hris->update_karyawan($contract->recid_karyawan, $employee_data);
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
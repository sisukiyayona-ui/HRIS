<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kontrak extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all contracts for a specific employee
     * @param int $recid_karyawan Employee ID
     * @return array Contracts data
     */
    public function get_kontrak_by_karyawan($recid_karyawan)
    {
        $query = $this->db->query("SELECT * FROM karyawan_kontrak WHERE recid_karyawan = ? ORDER BY tgl_mulai ASC", array($recid_karyawan));
        return $query->result();
    }

    /**
     * Get a specific contract by ID
     * @param int $recid_kontrak Contract ID
     * @return object Contract data
     */
    public function get_kontrak_by_id($recid_kontrak)
    {
        $query = $this->db->query("SELECT * FROM karyawan_kontrak WHERE recid_kontrak = ?", array($recid_kontrak));
        return $query->row();
    }

    /**
     * Insert a new contract
     * @param array $data Contract data
     * @return bool Insert result
     */
    public function insert_kontrak($data)
    {
        return $this->db->insert('karyawan_kontrak', $data);
    }

    /**
     * Update a contract
     * @param int $recid_kontrak Contract ID
     * @param array $data Updated contract data
     * @return bool Update result
     */
    public function update_kontrak($recid_kontrak, $data)
    {
        $this->db->where('recid_kontrak', $recid_kontrak);
        return $this->db->update('karyawan_kontrak', $data);
    }

    /**
     * Delete a contract
     * @param int $recid_kontrak Contract ID
     * @return bool Delete result
     */
    public function delete_kontrak($recid_kontrak)
    {
        return $this->db->delete('karyawan_kontrak', array('recid_kontrak' => $recid_kontrak));
    }

    /**
     * Get all contracts ordered by employee ID
     * @return array All contracts data
     */
    public function get_all_kontrak()
    {
        $query = $this->db->query("SELECT * FROM karyawan_kontrak ORDER BY recid_karyawan ASC");
        return $query->result();
    }
    
    /**
     * Get contract history for an employee
     * @param int $recid_karyawan Employee ID
     * @return array Contract history data
     */
    public function get_kontrak_history($recid_karyawan)
    {
        $query = $this->db->query("SELECT * FROM karyawan_kontrak WHERE recid_karyawan = ? ORDER BY tgl_mulai DESC", array($recid_karyawan));
        return $query->result();
    }
    
    /**
     * Update contract status to 'diputus' (terminated)
     * @param int $recid_kontrak Contract ID
     * @param string $tgl_resign Resignation date
     * @param string $alasan_resign Reason for resignation
     * @return bool Update result
     */
    public function update_status_diputus($recid_kontrak, $tgl_resign, $alasan_resign)
    {
        $data = array(
            'status_kontrak' => 'diputus',
            'tgl_resign' => $tgl_resign,
            'alasan_resign' => $alasan_resign,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('recid_kontrak', $recid_kontrak);
        return $this->db->update('karyawan_kontrak', $data);
    }

    /**
     * Update contract status to 'selesai' (completed)
     * @param int $recid_kontrak Contract ID
     * @return bool Update result
     */
    public function update_status_selesai($recid_kontrak)
    {
        $data = array(
            'status_kontrak' => 'selesai',
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('recid_kontrak', $recid_kontrak);
        return $this->db->update('karyawan_kontrak', $data);
    }

    /**
     * Get contracts that have ended today or in the past (for automatic status update)
     * @return array Expired contracts data
     */
    public function get_expired_contracts()
    {
        $query = $this->db->query("SELECT * FROM karyawan_kontrak WHERE tgl_akhir <= CURDATE() AND status_kontrak = 'aktif'");
        return $query->result();
    }
    
    /**
     * Get total number of contracts for a specific employee
     * @param int $recid_karyawan Employee ID
     * @return int Total number of contracts
     */
    public function get_total_contracts_by_karyawan($recid_karyawan)
    {
        $this->db->where('recid_karyawan', $recid_karyawan);
        $query = $this->db->get('karyawan_kontrak');
        return $query->num_rows();
    }
}
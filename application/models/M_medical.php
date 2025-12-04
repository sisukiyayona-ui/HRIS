<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_medical extends CI_Model{

	public function plafon($id = null)
	{
		if($id === null)
		{
			$query = $this->db->query("SELECT p.*, k.nama_karyawan, k.nik, b.nama_bag, j.nama_jbtn from plafon p join karyawan k on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn ");
			return $query;
		}
		else
		{
			$query = $this->db->query("SELECT p.*, k.nama_karyawan, k.nik, b.nama_bag, j.nama_jbtn from plafon p join karyawan k on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn  where recid_plafon = '$id'");
			return $query;
		}
	}
	public function plafon_emp($id)
	{
		$query = $this->db->query("SELECT p.*, k.nama_karyawan, k.nik, b.nama_bag, j.nama_jbtn from plafon p join karyawan k on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn  where p.recid_karyawan = '$id'");
		return $query;
	}

	public function cek_double($tahun, $recid_karyawan)
	{
		$query = $this->db->query("SELECT * from plafon where tahun = '$tahun' and recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function save_data($tabel, $data)
	{
		$this->db->insert($tabel, $data);
	}

	public function edit_plafon($id, $data)
	{
		$this->db->where('plafon_recid',$id);
		$this->db->update('plafon', $data);
	}

	public function pengajuan($id = null)
	{
		if($id === null)
		{
			$query = $this->db->query("SELECT p.*, k.nama_karyawan, k.nik, b.nama_bag, j.nama_jbtn from medical p join karyawan k on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn ");
			return $query;
		}
		else
		{
			$query = $this->db->query("SELECT p.*, k.nama_karyawan,  k.nik, b.nama_bag, j.nama_jbtn from medical p join karyawan k on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where medical_recid = '$id'");
			return $query;
		}

	}

	public function edit_pengajuan($id, $data)
	{
		$this->db->where('medical_recid',$id);
		$this->db->update('medical', $data);
	}

	public function cek_tanggungan($id)
	{
		$query = $this->db->query("SELECT * FROM tunjangan where recid_karyawan = '$id' and tunjangan_delete = '0' and sts_tunjangan = 'Yes' order by hub_keluarga asc");
		return $query;
	}

	public function realisasi_emp($id)
	{
		$query = $this->db->query("SELECT sum(nilai_ganti) as tot_realisasi from medical m join karyawan k on m.recid_karyawan = k.recid_karyawan where status = 'Realisasi' and YEAR(tgl_kuitansi)= YEAR(current_date()) and m.recid_karyawan = '$id'");
		return $query;
	}

	public function sisa_plafon()
	{
		$query = $this->db->query("SELECT k.nama_karyawan, p.tahun, p.recid_karyawan, p.jumlah_plafon, sum(nilai_ganti) as tot_realisasi from medical m join karyawan k on m.recid_karyawan = k.recid_karyawan right join plafon p on p.recid_karyawan = k.recid_karyawan where status = 'Realisasi' and YEAR(tgl_kuitansi)= p.tahun group by recid_karyawan");
		return $query;
	}

}
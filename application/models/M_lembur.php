<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_lembur extends CI_Model
{

	// ################################################### CUT OFF LEMBUR ###################################################################

	public function cutoff_insert($data)
	{
		$this->db->insert('cutoff_lembur', $data);
	}

	public function cutoff_update($data, $id)
	{
		$this->db->where('recid_clembur', $id);
		$this->db->update('cutoff_lembur', $data);
	}

	public function cutoff_lembur()
	{
		$query = $this->db->query("SELECT * From cutoff_lembur order by Tahun desc")->result();
		return $query;
	}

	public function cutoff_lembur_by_id($id)
	{
		$query = $this->db->query("SELECT * From cutoff_lembur where recid_clembur ='$id'")->result();
		return $query;
	}

	public function cutoff_by_tahun($tahun)
	{
		$query = $this->db->query("SELECT * From cutoff_lembur where tahun = '$tahun'")->result();
		return $query;
	}

	public function cek_cutoff($tgl)
	{
		$query = $this->db->query("SELECT * FROM cutoff_lembur WHERE periode_awal <= '$tgl' and periode_akhir >= '$tgl'");
		return $query;
	}

	public function cutoff_thn_bln($tahun, $bulan)
	{
		$query = $this->db->query("SELECT * From cutoff_lembur where tahun = '$tahun' and bulan = '$bulan'");
		return $query;
	}

	// ################################################### PEKERJAAN ################################################################

	public function pekerjaan_insert($data)
	{
		$this->db->insert('pekerjaan', $data);
	}

	public function pekerjaan_view()
	{
		$query = $this->db->query("SELECT * FROM pekerjaan")->result();
		return $query;
	}


	// ################################################### MASTER BUDGET ############################################################

	public function masterbudget_insert($data)
	{
		$this->db->insert('master_budget', $data);
	}

	public function master_budget_group()
	{
		$query = $this->db->query("SELECT mb.*, sum(mb.jml_jam) as total, b.nama_bag, b.indeks_hr from master_budget mb join bagian b on mb.recid_bag = b.recid_bag group by mb.recid_bag, mb.tahun order by tahun desc")->result();
		return $query;
	}

	public function master_budget_tahun()
	{
		$query = $this->db->query("SELECT distinct(tahun) from master_budget where tahun >= 2023")->result();
		return $query;
	}

	public function masterbudget_detail($bag, $tahun)
	{
		$query = $this->db->query("SELECT * from master_budget where recid_bag = '$bag' and tahun = '$tahun'")->result();
		return $query;
	}

	public function budget_bag_bln_tahun($bag, $bulan, $tahun)
	{
		$query = $this->db->query("SELECT * from master_budget where recid_bag = '$bag' and tahun = '$tahun' and bulan = '$bulan'");
		return $query;
	}

	public function masterbudget($bag, $recid_clembur)
	{
		$query = $this->db->query("SELECT * from master_budget where recid_bag = '$bag' and recid_clembur = '$recid_clembur'");
		return $query;
	}

	public function jam_kuartal($bag, $kuartal, $tahun)
	{
		$query = $this->db->query("SELECT  * from master_budget where recid_bag = '$bag' and kuartal = '$kuartal' and tahun = '$tahun'");
		return $query;
	}

	public function total_jamkuartal($bag, $kuartal, $tahun)
	{
		$query = $this->db->query("SELECT sum(jml_jam) as jumlah from master_budget where recid_bag = '$bag' and kuartal = '$kuartal' and tahun = '$tahun'");
		return $query;
	}

	public function masterbudget_total($bag, $tahun)
	{
		$query = $this->db->query("SELECT mb.*, sum(jml_jam) as total, b.nama_bag from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where mb.recid_bag = '$bag' and tahun = '$tahun'")->result();
		return $query;
	}

	public function masterbudget_update($data, $id)
	{
		$this->db->where('recid_mbl', $id);
		$this->db->update('master_budget', $data);
	}

	public function cek_masterbudget($tahun, $bagian)
	{
		$query = $this->db->query("SELECT * from master_budget where tahun = '$tahun' and recid_bag = '$bagian'");
		return $query;
	}

	public function stkl_id($recid_stkl)
	{
		$query = $this->db->query("SELECT s.*, sk.recid_kategori, sk.kategori, b.recid_bag, b.indeks_hr, d.dept_group, b.recid_struktur, s.jam_selesai as jam_sls from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on b.recid_department = d.recid_department
				where recid_stkl = $recid_stkl");
		return $query;
	}

	public function stkl_periode($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, sr.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_pending()
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, sr.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and status != 'selesai' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_pending_admbagian($bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and status != 'selesai'
				and  ($bagian) order by tgl_lembur desc");
		return $query;
	}

	public function stkl_pending_managerbagian($bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and  (approval =  'Belum Acc Manager' or  approval = 'Realisasi Bagian') and  ($bagian) order by tgl_lembur desc");
		return $query;
	}

	public function stkl_pending_direksi($dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and  (status =  'Pengajuan' and  approval = 'Acc Manager')
				and  d.nama_department = '$dept' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_pending_deptgroup($dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and (status =  'Pengajuan' and (approval = 'Belum Acc Manager' or approval = 'Acc Manager')) or (status = 'Realisasi' and approval = 'Realisasi Bagian')
				and  d.nama_department = '$dept' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_bulkhc($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, sr.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' 
				and (((sr.recid_struktur = 11 and approval = 'Belum Acc Manager') or (sr.recid_struktur = 11 and approval = 'Realisasi Bagian')) or  approval = 'Acc Direksi'  or (d.recid_department != 6 and approval = 'Acc Manager'))
				order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_bulkdir($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, sr.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' 
				and (d.recid_department = 6 and approval = 'Acc Manager')
				order by tgl_lembur desc");
		return $query;
	}

	public function stkl_del_periode($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, sr.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr , b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and approval = '$status' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status_bulkhc($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr , b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and approval = '$status' 
				 order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status_bulkhc2($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr , b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and (approval = '$status' and sr.recid_struktur = 11)
				 order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status_bulkhc3($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr , b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join struktur sr on sr.recid_struktur = b.recid_struktur
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and (approval = '$status' and sr.recid_struktur = 11) or (d.recid_department != 6 and approval = 'Acc manager')
				 order by tgl_lembur desc");
		return $query;
	}

	public function stkl_del_periode_status($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr , b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on b.recid_department = d.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and approval = '$status' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_flag($tgl_mulai, $tgl_akhir, $flag)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and flag_holiday = '$flag' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_flag_karyawan($recid_karyawan, $tgl_mulai, $tgl_akhir, $flag)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, sd.*, b.indeks_hr from stkl s join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join stkl_detail sd on sd.recid_stkl = s.recid_stkl join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and sd.recid_karyawan = $recid_karyawan and flag_holiday = '$flag' and status = 'Selesai' order by tgl_lembur desc;");
		return $query;
	}

	public function stkl_periode_hol($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and flag_holiday > 0 order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_hol_karyawan($recid_karyawan, $tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, sd.*, b.indeks_hr from stkl s join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join stkl_detail sd on sd.recid_stkl = s.recid_stkl join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and sd.recid_karyawan = $recid_karyawan and flag_holiday > 0 and status = 'Selesai' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_admbagian($tgl_mulai, $tgl_akhir, $bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and  ($bagian) order by tgl_lembur desc");
		return $query;
	}

	public function stkl_del_periode_admbagian($tgl_mulai, $tgl_akhir, $bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and  ($bagian) order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status_admbagian($tgl_mulai, $tgl_akhir, $status, $bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and approval = '$status'
				and  ($bagian)order by tgl_lembur desc ");
		return $query;
	}

	public function stkl_del_periode_status_admbagian($tgl_mulai, $tgl_akhir, $status, $bagian)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and approval = '$status'
				and  ($bagian)order by tgl_lembur desc ");
		return $query;
	}

	public function stkl_periode_deptgroup($tgl_mulai, $tgl_akhir, $dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and  d.nama_department = '$dept' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_del_periode_deptgroup($tgl_mulai, $tgl_akhir, $dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and  d.nama_department = '$dept' order by tgl_lembur desc");
		return $query;
	}

	public function stkl_periode_status_deptgroup($tgl_mulai, $tgl_akhir, $status, $dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and approval = '$status'
				and  d.nama_department = '$dept' order by tgl_lembur desc ");
		return $query;
	}

	public function stkl_del_periode_status_deptgroup($tgl_mulai, $tgl_akhir, $status, $dept)
	{
		$query = $this->db->query("SELECT s.*, sk.kategori, b.indeks_hr, b.recid_struktur, d.dept_group from stkl s 
				join stkl_kategori sk on sk.recid_kategori = s.recid_kategori 
				join master_budget mb on mb.recid_mbl = s.recid_mbl
				join bagian b on b.recid_bag = mb.recid_bag
				join department d on d.recid_department = b.recid_department
				where s.is_delete = '1' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
				and approval = '$status'
				and  d.nama_department = '$dept' order by tgl_lembur desc ");
		return $query;
	}

	public function stkl_karyawan_periode($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, k.recid_karyawan, k.nik, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.jam_selesai FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag where sd.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function hitung_real_jam($recid_stkl)
	{
		$query = $this->db->query("SELECT sum(durasi_lembur) as total FROM stkl_detail where recid_stkl = $recid_stkl and is_delete = '0';");
		return $query;
	}


	public function kategori_lembur_aktif()
	{
		$query = $this->db->query("SELECT * from stkl_kategori where is_active = '1'");
		return $query;
	}

	public function stkl_insert($data)
	{
		$this->db->insert('stkl', $data);
	}

	public function stkl_update($data, $id)
	{
		$this->db->where('recid_stkl', $id);
		$this->db->update('stkl', $data);
	}

	public function last_stkl()
	{
		$query = $this->db->query("SELECT * from stkl order by recid_stkl desc limit 1");
		return $query;
	}

	public function stkl_detail_insert($data)
	{
		$this->db->insert('stkl_detail', $data);
	}

	public function stkl_detail_update($data, $id)
	{
		$this->db->where('recid_detstkl', $id);
		$this->db->update('stkl_detail', $data);
	}

	public function sd_update_by_stkl($data, $id)
	{
		$this->db->where('recid_stkl', $id);
		$this->db->update('stkl_detail', $data);
	}

	public function dlembur_insert($data)
	{
		$this->db->insert('detail_lembur', $data);
	}

	public function dlembur_update($data, $id)
	{
		$this->db->where('recid_detlembur', $id);
		$this->db->update('detail_lembur', $data);
	}

	public function get_det_lembur($recid_stkl)
	{
		$query = $this->db->query("SELECT * from detail_lembur where recid_plembur = '$recid_stkl' and is_delete = '0'");
		return $query;
	}

	public function karyawan_lembur($recid_stkl)
	{
		$query = $this->db->query("SELECT d.*, d.jam_selesai as jam_sls, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan from stkl_detail d join karyawan k on k.recid_karyawan = d.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where recid_stkl = '$recid_stkl' and d.is_delete = '0'");
		return $query;
	}

	public function karyawan_lembur_stkl($recid_stkl)
	{
		$query = $this->db->query("SELECT d.*, d.jam_selesai as jam_sls, s.tgl_lembur, s.jam_mulai, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.jam_mulai, s.jam_selesai
			from stkl s join stkl_detail d on s.recid_stkl = d.recid_stkl 
			join hris.karyawan k on k.recid_karyawan = d.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where s.is_delete = '0' and s.recid_stkl = $recid_stkl and d.is_delete = '0'");
		return $query;
	}

	public function pekerjaan($recid_stkl)
	{
		$query = $this->db->query("SELECT * from detail_lembur where recid_plembur = '$recid_stkl' and is_delete = '0'");
		return $query;
	}

	public function stkl_kuartal($bag, $kuartal, $tahun)
	{
		$query = $this->db->query("SELECT * FROM `master_budget` where recid_bag = $bag AND kuartal = '$kuartal' and tahun = $tahun;");
		return $query;
	}

	public function pengurang_kuartal($mbl)
	{
		$query = $this->db->query("SELECT * FROM `stkl` where recid_mbl = $mbl;");
		return $query;
	}

	public function jumlah_jam_lembur($stkl)
	{
		$query = $this->db->query("SELECT SEC_TO_TIME(sum(TIME_TO_SEC(timediff(d.jam_selesai, (select s.jam_mulai from stkl s where s.recid_stkl = $stkl))))) as total_jam from stkl_detail d where d.recid_stkl = $stkl and d.is_delete = '0';");
		return $query;
	}

	// public function pengurang_kuartal($mbl)
	// {
	// 	$query = $this->db->query("SELECT s.*, mb.jml_jam, d.recid_karyawan, d.jam_selesai as jam_lembur ,timediff(d.jam_selesai, s.jam_mulai) as lama from stkl s join master_budget mb on s.recid_mbl = mb.recid_mbl join stkl_detail d on d.recid_stkl = s.recid_stkl where mb.recid_mbl = $mbl;");
	// 	return $query;
	// }

	public function cek_kehadiran($tgl_lembur, $recid_karyawan)
	{
		$query = $this->db->query("SELECT h.*, ja.jam_in from master_absen.hadir_barcode h join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where tanggal = '$tgl_lembur' and recid_karyawan = $recid_karyawan;");
		return $query;
	}

	public function cek_holiday($tgl_lembur)
	{
		$query = $this->db->query("SELECT * from master_absen.cuti_bersama where tanggal = '$tgl_lembur' and is_delete = '0'");
		return $query;
	}

	public function pic_bagian($bagian)
	{
		$query = $this->db->query("SELECT k.email_cint, k.nama_karyawan FROM bagian b join karyawan k on k.recid_karyawan = b.pic_bagian where b.recid_bag = $bagian;");
		return $query;
	}

	public function pic_struktur($recid_str)
	{
		$query = $this->db->query("SELECT k.email_cint, k.nama_karyawan FROM struktur s join bagian b on b.recid_struktur = s.recid_struktur join karyawan k on k.recid_karyawan = s.pic_struktur where s.recid_struktur = $recid_str group by email_cint;;");
		return $query;
	}

	public function pic_lindep($recid_stkl)
	{
		$query = $this->db->query("SELECT distinct(k2.email_cint) FROM `stkl_detail` sd
		join karyawan k on k.recid_karyawan = sd.recid_karyawan
		join bagian b on b.recid_bag = k.recid_bag
		join struktur s on s.recid_struktur = b.recid_struktur
		join karyawan k2 on k2.recid_karyawan = s.pic_struktur
		where sd.recid_stkl = $recid_stkl and sd.is_delete = '0';");
		return $query;
	}

	public function det_stkl_prd($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, k.recid_karyawan, k.nik, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.jam_selesai,sd.durasi_lembur, sd.lembur1, sd.lembur2, sd.lembur3 FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag where sd.is_delete = '0' and s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function det_stkl_prd_status($tgl_mulai, $tgl_akhir, $status)
	{
		$query = $this->db->query("SELECT s.*, k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.jam_selesai, sd.durasi_lembur FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag where sd.is_delete = '0' and s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and status = '$status' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function det_stkl_prd_deptgroup($tgl_mulai, $tgl_akhir, $dept)
	{
		$query = $this->db->query("SELECT s.*, k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.durasi_lembur, sd.jam_selesai, sd.lembur1, sd.lembur2, sd.lembur3 FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department where sd.is_delete = '0'and s.is_delete = '0' and d.nama_department = '$dept' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function det_stkl_prd_bagian($tgl_mulai, $tgl_akhir, $bagian)
	{
		$query = $this->db->query("SELECT s.*, k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.durasi_lembur, sd.jam_selesai, sd.lembur1, sd.lembur2, sd.lembur3 FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department where sd.is_delete = '0'and s.is_delete = '0' and ($bagian) and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function det_stkl_prd_status_deptgroup($tgl_mulai, $tgl_akhir, $dept, $status)
	{
		$query = $this->db->query("SELECT s.*, k.nik, k.nama_karyawan, b.indeks_hr as bag_lembur, b2.indeks_hr as bag_kry, sk.kategori, sd.jam_selesai FROM stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join hris.karyawan k on k.recid_karyawan = sd.recid_karyawan join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join bagian b2 on b2.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department where sd.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir' and status = '$status' and d.nama_department = '$dept' order by tgl_lembur, recid_stkl desc");
		return $query;
	}

	public function cek_pulang($recid_stkl, $recid_karyawan)
	{
		$query = $this->db->query("SELECT * from stkl_detail where recid_stkl = $recid_stkl and recid_karyawan = $recid_karyawan");
		return $query;
	}

	public function karyawan_offdown_lembur($tgl_lembur)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.recid_karyawan NOT IN (SELECT sd.recid_karyawan from stkl_detail sd join stkl ss on ss.recid_stkl = sd.recid_stkl where ss.tgl_lembur = '$tgl_lembur' and sd.is_delete = '0' and ss.is_delete = '0' and ss.status ='pengajuan') and k.sts_aktif='Aktif' and j.tingkatan < 6 order by nama_karyawan asc;")->result();
		return $query;
	}

	public function karyawan_offdown_lembur2($tgl_lembur)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and j.tingkatan < 6 order by nama_karyawan asc;")->result();
		return $query;
	}

	public function cek_dup_emp($tgl_lembur, $recid_karyawan)
	{
		$query = $this->db->query("SELECT * from stkl_detail sd join stkl s on sd.recid_stkl = s.recid_stkl join karyawan k on k.recid_karyawan = sd.recid_karyawan where s.tgl_lembur = '$tgl_lembur' and sd.recid_karyawan = $recid_karyawan and s.is_delete = '0' and sd.is_delete = '0'");
		return $query;
	}

	/* ----------------------- Dashboard ---------------- */
	public function jml_budget_tahun($tahun)
	{
		$query = $this->db->query("SELECT sum(jml_jam) as budgets FROM `master_budget` where tahun = $tahun;");
		return $query;
	}

	public function jml_budget_tahun_dept($tahun, $dept)
	{
		$query = $this->db->query("SELECT sum(jml_jam) as budgets FROM master_budget m join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where tahun = $tahun and d.dept_group = '$dept';");
		return $query;
	}

	public function jml_real_tahun($tahun)
	{
		$query = $this->db->query("SELECT sum(totjam_real) as realisasi FROM stkl s join master_budget m on m.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where  year(tgl_lembur) = $tahun and status = 'Selesai' and s.is_delete = '0'");
		return $query;
	}

	public function jml_real_tahun_dept($tahun, $dept)
	{
		$query = $this->db->query("SELECT sum(totjam_real) as realisasi FROM stkl s join master_budget m on m.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where year(tgl_lembur) = $tahun  and d.dept_group = '$dept' and status = 'Selesai' and s.is_delete = '0'");
		return $query;
	}

	public function jml_budget_bulan($tahun, $bulan)
	{
		$query = $this->db->query("SELECT sum(jml_jam) as budgets FROM `master_budget` where tahun = $tahun and bulan = '$bulan';");
		return $query;
	}

	public function jml_real_bulan($tahun, $bulan)
	{
		$query = $this->db->query("SELECT sum(totjam_real) as realisasi FROM stkl s join master_budget m on m.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where  year(tgl_lembur) = $tahun and month(tgl_lembur) = '$bulan'  and status = 'Selesai' and s.is_delete = '0'");
		return $query;
	}

	public function jml_budget_bulan_dept($tahun, $bulan, $dept)
	{
		$query = $this->db->query("SELECT sum(jml_jam) as budgets FROM master_budget m join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where tahun = $tahun and bulan = '$bulan' and d.dept_group = '$dept'");
		return $query;
	}

	public function jml_real_bulan_dept($tahun, $bulan, $dept)
	{
		$query = $this->db->query("SELECT sum(totjam_real) as realisasi FROM stkl s join master_budget m on m.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where year(tgl_lembur) = $tahun and month(tgl_lembur) = '$bulan'  and d.dept_group = '$dept' and status = 'Selesai' and s.is_delete = '0'");
		return $query;
	}

	public function det_real_tahun($tahun)
	{
		$query = $this->db->query("SELECT * from stkl s join master_budget m on m.recid_mbl = s.recid_mbl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join bagian b on b.recid_bag = m.recid_bag where year(tgl_lembur) = '$tahun' and s.is_delete = '0' and status = 'Selesai'");
		return $query;
	}

	public function det_real_tahun_dept($tahun, $dept)
	{
		$query = $this->db->query("SELECT * from stkl s join master_budget m on m.recid_mbl = s.recid_mbl join stkl_kategori sk on sk.recid_kategori = s.recid_kategori join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where year(tgl_lembur) = '$tahun' and s.is_delete = '0' and status = 'Selesai' and dept_group = '$dept'");
		return $query;
	}

	public function det_budget_tahun($tahun)
	{
		$query = $this->db->query("SELECT m.tahun, b.indeks_hr, d.dept_group, sum(jml_jam) as mb from master_budget m join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where tahun = '$tahun' group by b.recid_bag;");
		return $query;
	}

	public function det_budget_tahun_dept($tahun, $dept)
	{
		$query = $this->db->query("SELECT m.tahun, b.indeks_hr, d.dept_group, sum(jml_jam) as mb from master_budget m join bagian b on b.recid_bag = m.recid_bag join department d on d.recid_department = b.recid_department where tahun = '$tahun' and dept_group = '$dept' group by b.recid_bag;");
		return $query;
	}

	public function wf_insert($data)
	{
		$this->db->insert('stkl_wf', $data);
	}

	public function get_workflow($recid_stkl)
	{
		$query = $this->db->query("SELECT *, w.crt_date as tgl_wf, w.approval as sts_wf, k.nama_karyawan as nama_pic, d.dept_group from stkl_wf w join stkl s on s.recid_stkl = w.recid_stkl join karyawan k on k.recid_karyawan = w.pic_wf join master_budget mb on mb.recid_mbl = s.recid_mbl
		join bagian b on b.recid_bag = mb.recid_bag
		join department d on d.recid_department = b.recid_department where w.recid_stkl = $recid_stkl;");
		return $query;
	}

	public function get_workflow_periode($tgl_mulai, $tgl_akhir)
	{
		$query = $this->db->query("SELECT s.*, k.nama_karyawan, w.status as status_wf, w.approval as approval_wf, w.crt_date as tgl_wf, b.indeks_hr FROM stkl s join stkl_wf w on s.recid_stkl = w.recid_stkl join karyawan k on k.recid_karyawan = w.pic_wf join master_budget mb on mb.recid_mbl = s.recid_mbl
		join bagian b on b.recid_bag = mb.recid_bag where s.is_delete = '0' and s.tgl_lembur between '$tgl_mulai' and '$tgl_akhir' order by s.recid_stkl desc;");
		return $query;
	}

	public function get_workflow_deptgroup($tgl_mulai, $tgl_akhir, $dept)
	{
		$query = $this->db->query("SELECT s.*, k.nama_karyawan, w.status as status_wf, w.approval as approval_wf, w.crt_date as tgl_wf FROM stkl s
		join master_budget mb on mb.recid_mbl = s.recid_mbl
		join bagian b on b.recid_bag = mb.recid_bag
		join department d on d.recid_department = b.recid_department
		join stkl_wf w on s.recid_stkl = w.recid_stkl 
		join karyawan k on k.recid_karyawan = w.pic_wf
		where s.is_delete = '0' and tgl_lembur between '$tgl_mulai' and '$tgl_akhir'
		and  d.nama_department = '$dept' order by tgl_lembur desc");
		return $query;
	}

	public function struktur_mb_report($tahun, $pay_group)
	{
		$query = $this->db->query("SELECT DISTINCT(s.recid_struktur), nama_struktur, nama_department, pay_group from master_budget mb join bagian b on b.recid_bag = mb.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join department d on b.recid_department = d.recid_department where tahun = '$tahun' and b.pay_group = '$pay_group' order by pay_group,nama_department, indeks_hr;");
		return $query;
	}

	public function bagian_mb_report($tahun, $recid_struktur)
	{
		$query = $this->db->query("SELECT DISTINCT(mb.recid_bag) as recid_bag, b.indeks_hr, nama_struktur, nama_department, pay_group from master_budget mb join bagian b on b.recid_bag = mb.recid_bag
		join struktur s on s.recid_struktur = b.recid_struktur
		join department d on b.recid_department = d.recid_department
		where tahun = '$tahun' and s.recid_struktur = '$recid_struktur' order by nama_department, indeks_hr;");
		return $query;
	}

	public function karyawan_nonstaff_bagian($recid_bagian)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where j.tingkatan <= 5 and b.recid_bag = $recid_bagian and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0'");
		return $query;
	}

	public function karyawan_mb_report($tgl_awal, $tgl_akhir, $recid_bag)
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.gapok, k.t_jabatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja,  sum(sd.durasi_lembur) as total_jam, sum(sd.lembur1) as lembur1, sum(sd.lembur2) as lembur2, sum(sd.lembur3) as lembur3, k.nama_karyawan from stkl s join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join stkl_detail sd on s.recid_stkl = sd.recid_stkl join karyawan k on k.recid_karyawan = sd.recid_karyawan join bagian b1 on b1.recid_bag = k.recid_bag where s.tgl_lembur between '$tgl_awal' and '$tgl_akhir' and s.is_delete = '0' and sd.is_delete = '0' and b1.recid_bag = $recid_bag group by k.recid_karyawan;");
		return $query;
	}

	public function karyawan_mb_report2($tgl_awal, $tgl_akhir, $recid_bag)
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.gapok, k.t_jabatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja,  sum(sd.durasi_lembur) as total_jam, sum(sd.lembur1) as lembur1, sum(sd.lembur2) as lembur2, sum(sd.lembur3) as lembur3, k.nama_karyawan from stkl s join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join stkl_detail sd on s.recid_stkl = sd.recid_stkl join karyawan k on k.recid_karyawan = sd.recid_karyawan  where s.tgl_lembur between '$tgl_awal' and '$tgl_akhir' and s.is_delete = '0' and sd.is_delete = '0' and b.recid_bag = $recid_bag group by k.recid_karyawan;");
		return $query;
	}

	public function karyawan_lembur_report($tgl_awal, $tgl_akhir, $recid_karyawan)
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.gapok, k.t_jabatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja,  sum(sd.durasi_lembur) as total_jam, sum(sd.lembur1) as lembur1, sum(sd.lembur2) as lembur2, sum(sd.lembur3) as lembur3, k.nama_karyawan from stkl s join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join stkl_detail sd on s.recid_stkl = sd.recid_stkl join karyawan k on k.recid_karyawan = sd.recid_karyawan join bagian b1 on b1.recid_bag = k.recid_bag where s.tgl_lembur between '$tgl_awal' and '$tgl_akhir' and s.is_delete = '0' and sd.is_delete = '0' and k.recid_karyawan = $recid_karyawan and status = 'selesai';");
		return $query;
	}


	public function co_tahun_report()
	{
		$query = $this->db->query("SELECT DISTINCT(tahun) from master_budget where tahun > 2022");
		return $query;
	}

	public function transport_lembur_karyawan($mulai, $selesai, $recid_karyawan)
	{
		//jemputan 1 = uang
		// jemputan 0 = fasilitas
		$query = $this->db->query("SELECT * from stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl where tgl_lembur between '$mulai' and '$selesai' and s.is_delete = '0' and sd.is_delete = '0' and sd.recid_karyawan = $recid_karyawan and s.flag_holiday = '0' and s.jemputan = '1' ");
		return $query;
	}

	public function transport_lembur_karyawan1($mulai, $selesai, $recid_karyawan)
	{
		//jemputan 1 = uang
		// jemputan 0 = fasilitas
		$query = $this->db->query("SELECT * from stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl where tgl_lembur between '$mulai' and '$selesai' and s.is_delete = '0' and sd.is_delete = '0' and sd.recid_karyawan = $recid_karyawan and s.flag_holiday != '0' and s.jemputan = '1' ");
		return $query;
	}

	public function transport_shift($mulai, $selesai, $recid_karyawan)
	{
		//jemputan 1 = uang
		// jemputan 0 = fasilitas
		$query = $this->db->query("SELECT * from stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl where tgl_lembur between '$mulai' and '$selesai' and s.is_delete = '0' and sd.is_delete = '0' and sd.recid_karyawan = $recid_karyawan and s.jemputan = '1' ");
		return $query;
	}

	public function makan_lembur($mulai, $selesai, $recid_karyawan)
	{
		// makan 1 = uang
		//makan 0 = catering
		$query = $this->db->query("SELECT * from stkl s join stkl_detail sd on s.recid_stkl = sd.recid_stkl where tgl_lembur between '$mulai' and '$selesai' and s.is_delete = '0' and sd.is_delete = '0' and sd.recid_karyawan = $recid_karyawan and s.makan = '1' ");
		return $query;
	}

	public function kategori_adjust()
	{
		$query = $this->db->query("SELECT * from adjust_kat_upah where is_delete = '0' ");
		return $query;
	}

	public function adjust_upah($data)
	{
		$this->db->insert('adjustment_upah', $data);
	}

	public function adjust_upah_edit($data, $id)
	{
		$this->db->where('recid_auph', $id);
		$this->db->update('adjustment_upah', $data);
	}

	public function cek_duplikat_adjust($recid_karyawan, $periode_awal, $periode_akhir)
	{
		$query = $this->db->query("SELECT * from adjustment_upah a join adjust_kat_upah ak on a.kategori_adjust = ak.recid_akatuph where a.recid_karyawan = $recid_karyawan and a.periode_awal = '$periode_awal' and a.periode_akhir = '$periode_akhir' and  a.is_delete = '0' and a.is_delete  = '0' ");
		return $query;
	}

	public function adjust_view()
	{
		$query = $this->db->query("SELECT a.*, b.indeks_hr, k.nik, k.nama_karyawan from adjustment_upah a join karyawan k on k.recid_karyawan = a.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where a.is_delete = '0' and a.is_delete  = '0' ");
		return $query;
	}

	public function adjust_periode($tgl_awal, $tgl_akhir)
	{
		$query = $this->db->query("SELECT a.*, b.indeks_hr, k.nik, k.nama_karyawan from adjustment_upah a join karyawan k on k.recid_karyawan = a.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where tanggal between '$tgl_awal' and '$tgl_akhir' and a.is_delete = '0' and a.is_delete = '0';");
		return $query;
	}

	public function adjust_periode_karyawan($tgl_awal, $tgl_akhir, $recid_karyawan)
	{
		$query = $this->db->query("SELECT a.*, b.indeks_hr, k.nik, k.nama_karyawan from adjustment_upah a join karyawan k on k.recid_karyawan = a.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where tanggal between '$tgl_awal' and '$tgl_akhir' and k.recid_karyawan = $recid_karyawan and a.is_delete = '0' and a.is_delete = '0';");
		return $query;
	}

	public function adjustment_by_id($id)
	{
		$query = $this->db->query("SELECT a.*,  b.indeks_hr, k.nik, k.nama_karyawan from adjustment_upah a join karyawan k on k.recid_karyawan = a.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where a.recid_auph = $id ");
		return $query;
	}

	public function cek_adjust($awal, $akhir, $recid_karyawan)
	{
		$query = $this->db->query("SELECT a.*, b.indeks_hr, k.nik, k.nama_karyawan from adjustment_upah a  join karyawan k on k.recid_karyawan = a.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where periode_awal = '$awal' and periode_akhir = '$akhir' and a.recid_karyawan = $recid_karyawan and a.is_delete = '0' and a.is_delete  = '0'");
		return $query;
	}

	public function realisasi_sameday($tgl_lembur, $recid_karyawan)
	{
		$query = $this->db->query("SELECT * FROM `stkl_detail` sd join stkl s on s.recid_stkl = sd.recid_stkl where recid_karyawan = $recid_karyawan and tgl_lembur = '$tgl_lembur' and lembur1 > 0 and s.is_delete = '0' and sd.is_delete='0';");
		return $query;
	}

	/* SELECT k.recid_karyawan, k.gapok, k.t_jabatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja, s.recid_stkl, s.tgl_lembur, s.flag_holiday, s.jam_mulai, s.jam_selesai, sd.durasi_lembur as total_jam, sd.lembur1 as lembur1, sd.lembur2 as lembur2, sd.lembur3 as lembur3, k.nama_karyawan from stkl s join master_budget mb on mb.recid_mbl = s.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join stkl_detail sd on s.recid_stkl = sd.recid_stkl join karyawan k on k.recid_karyawan = sd.recid_karyawan join bagian b1 on b1.recid_bag = k.recid_bag where s.tgl_lembur between '2023-05-19' and '2023-06-18' and s.is_delete = '0' and sd.is_delete = '0' and k.recid_karyawan = 211; */


	public function insert_upah($data)
	{
		$this->db->insert('upah', $data);
	}

	public function insert_upahlog($data)
	{
		$this->db->insert('upah_log', $data);
	}

	public function kosongkan_upah()
	{
		$query = $this->db->query("DELETE from upah");
		return $query;
	}

	public function last_upahid()
	{
		$query = $this->db->query("SELECT recid_upah from upah order by recid_upah desc limit 1");
		return $query;
	}

	public function cek_print()
	{
		$query = $this->db->query("SELECT u.*, k.nik, k.nama_karyawan, b.indeks_hr from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where u.recid_bag = 17");
		return $query;
	}

	public function print_struk($karyawan)
	{
		$query = $this->db->query("SELECT u.*, k.nik, k.nama_karyawan, b.indeks_hr, j.tingkatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja, k.sts_penunjang, k.penempatan, j.sts_jabatan, j.note as sts_jbtn from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where u.recid_karyawan = $karyawan");
		return $query;
	}

	public function periode_upah()
	{
		$query = $this->db->query("SELECT DISTINCT(bulan) as bulan, tahun, periode_awal, periode_akhir from upah");
		return $query;
	}

	public function data_upah()
	{
		$query = $this->db->query("SELECT u.*, k.nama_karyawan, k.nik, b.indeks_hr, b.pay_group, b.indeks_hr, b.gl_acc, b.cost_center from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on b.recid_bag = u.recid_bag");
		return $query;
	}



	public function insert_thr($data)
	{
		$this->db->insert('upah_thr', $data);
	}

	public function insert_thrlog($data)
	{
		$this->db->insert('upah_thr_log', $data);
	}

	public function kosongkan_thr()
	{
		$query = $this->db->query("DELETE from upah_thr");
		return $query;
	}

	public function last_thrid()
	{
		$query = $this->db->query("SELECT recid_upah from upah_thr order by recid_upah desc limit 1");
		return $query;
	}

	public function data_thr()
	{
		$query = $this->db->query("SELECT u.*, k.nama_karyawan, k.nik, b.indeks_hr, b.pay_group, b.indeks_hr, b.gl_acc, b.cost_center from upah_thr u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on b.recid_bag = u.recid_bag");
		return $query;
	}

	public function print_struk_thr($karyawan)
	{
		$query = $this->db->query("SELECT u.*, k.nik, k.nama_karyawan, b.indeks_hr, j.tingkatan, k.t_prestasi, k.t_jen_pek, k.tgl_m_kerja, k.sts_penunjang, j.note as sts_jbtn from upah_thr u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where u.recid_karyawan = $karyawan");
		return $query;
	}


	public function potongan_kopkar($recid_karyawan, $tahun, $bulan)
	{
		$query = $this->db->query("SELECT * from koprasi where recid_karyawan = $recid_karyawan and tahun = $tahun and bulan = $bulan");
		return $query;
	}

	public function potkop_periode($tahun, $bulan)
	{
		$query = $this->db->query("SELECT * from koprasi k join karyawan e on k.recid_karyawan = e.recid_karyawan join bagian b on b.recid_bag = e.recid_bag where tahun = $tahun and bulan = $bulan order by b.indeks_hr, e.nama_karyawan");
		return $query;
	}

	public function tahun_potkop()
	{
		$query = $this->db->query("SELECT distinct(tahun) as tahun from koprasi");
		return $query;
	}

	public function insert_potkop($data)
	{
		$this->db->insert('koprasi', $data);
	}

	public function update_potkop($data, $id)
	{
		$this->db->where('recid', $id);
		$this->db->update('koprasi', $data);
	}

	public function cek_lebaran($fil)
	{
		$data = $this->db->query("SELECT * from master_absen.param_cint where ket_param = '$fil'");
		return $data;
	}
}

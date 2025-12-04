<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_absen extends CI_Model
{

	public function generate_absen($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert('hadir_hris', $data);
	}

	public function cek_hadir_bulan_tahun($bulan, $tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from hadir_barcode
			where month(tanggal) = '$bulan' and year(tanggal) = $tahun");
		return $query;
	}

	public function hadir_today($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan, k.penempatan from hadir_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status
			join hris.golongan g on g.recid_golongan = k.recid_golongan 
			where tanggal = '$tgl' order by b.indeks_hr, k.nama_karyawan asc");
		return $query;
	}

	public function cek_closing_kehadiran($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(is_closed) from hadir_hris a 
			join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			where tanggal = '$tgl'");
		return $query;
	}

	public function closing_process($tgl, $clos)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("UPDATE hadir_hris a
			join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			SET a.is_closed = '$clos'
			WHERE a.tanggal = '$tgl'");
		return $query;
	}

	public function rekap_closing_admin($tgl, $close)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT b1.indeks_hr,  k1.nama_karyawan
			from admin_bagian ab
			join hris.karyawan k1 on k1.recid_karyawan = ab.recid_karyawan
			join hris.bagian b1 on b1.recid_bag = ab.recid_bag
			where ab.recid_bag in (SELECT b.recid_bag from hadir_hris a
				join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
				join hris.bagian b on b.recid_bag = k.recid_bag 
				join hris.department d on d.recid_department  = b.recid_department
				join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
				join hris.struktur s on s.recid_struktur = b.recid_struktur 
				join jenis_absen ja on ja.recid_jenisabsen = a.status 
				WHERE a.tanggal = '$tgl'
				AND a.is_closed = '$close')
			order by b1.indeks_hr asc");
		return $query;
	}

	public function hadir_today_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.recid_bag, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.recid_jbtn, ja.jenis, ja.keterangan, g.nama_golongan, k.penempatan from hadir_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join hris.golongan g on g.recid_golongan = k.recid_golongan
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			where tanggal = '$tgl' and $bagian order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function cek_closing_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(is_closed) from hadir_hris a 
			join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			where tanggal = '$tgl' and $bagian order by b.indeks_hr, k.nama_karyawan asc");
		return $query;
	}

	public function closing_process_bagian($tgl, $bagian, $clos)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("UPDATE hadir_hris a
			join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.department d on d.recid_department  = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
			join hris.struktur s on s.recid_struktur = b.recid_struktur 
			join jenis_absen ja on ja.recid_jenisabsen = a.status 
			SET a.is_closed = '$clos'
			WHERE a.tanggal = '$tgl' and $bagian");
		return $query;
	}

	public function cek_hadir_today($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from hadir_hris where tanggal = '$tgl'");
		return $query;
	}

	public function cek_hadir_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * FROM hadir_hris h 
			left join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			where h.tanggal = '$tgl' and b.indeks_hr = '$bagian'");
		return $query;
	}

	public function hadir_periode($tgl_mulai, $tgl_akhir)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from hadir_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.status where a.tanggal between '$tgl_mulai' AND '$tgl_akhir' order by k.recid_karyawan asc");
		return $query;
	}

	public function hadir_by_recid($id)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from hadir_hris where recid_absen = '$id'");
		return $query;
	}

	public function edit_hadir($id, $tgl, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_karyawan', $id);
		$db2->where('tanggal', $tgl);
		$db2->update('hadir_hris', $data);
	}

	public function edit_hadir2($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_absen', $id);
		$db2->update('hadir_hris', $data);
	}

	public function absen_periode($tgl_mulai, $tgl_akhir)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.tanggal between '$tgl_mulai' AND '$tgl_akhir' order by k.recid_karyawan asc");
		return $query;
	}

	public function absen_periode_jenis($tgl_mulai, $tgl_akhir, $jenis)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.tanggal between '$tgl_mulai' AND '$tgl_akhir' and a.is_delete = '0' and $jenis order by k.recid_karyawan asc");
		return $query;
	}

	public function absen_periode_jenis_bagian($tgl_mulai, $tgl_akhir, $jenis, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where (a.tanggal between '$tgl_mulai' AND '$tgl_akhir') and a.is_delete = '0' and ($jenis) and ($bagian) order by k.recid_karyawan asc");
		return $query;
	}

	public function absen_periode_jenis_baros($tgl_mulai, $tgl_akhir, $jenis)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where (a.tanggal between '$tgl_mulai' AND '$tgl_akhir') and a.is_delete = '0' and ($jenis) and k.penempatan = 'Baros' order by k.recid_karyawan asc");
		return $query;
	}

	public function jenis_absen()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from jenis_absen");
		return $query;
	}

	public function jenis_norma($norma)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from jenis_absen where jenis_norma = '$norma' ");
		return $query;
	}

	public function jenis_masuk()
	{
		$db2 = $this->load->database('absen', TRUE);
		/*$query = $db2->query("SELECT * from jenis_absen where recid_jenisabsen = 1 or recid_jenisabsen = 4 or (recid_jenisabsen >= 14)");*/
		$query = $db2->query("SELECT * from jenis_absen where absen_group = 'Hadir'");
		return $query;
	}

	public function jenis_tidak_masuk()
	{
		$db2 = $this->load->database('absen', TRUE);
		/*$query = $db2->query("SELECT * from jenis_absen where recid_jenisabsen = 2 or recid_jenisabsen = 3 or recid_jenisabsen = 8 or (recid_jenisabsen >= 5 and recid_jenisabsen <=7) or (recid_jenisabsen >= 9 and recid_jenisabsen <=13)");*/
		$query = $db2->query("SELECT * from jenis_absen where absen_group = 'Tidak Hadir'");
		return $query;
	}

	public function jenis_tidak_absen()
	{

		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * FROM `jenis_absen` where absen_group = 'Tidak Hadir' or recid_jenisabsen = 4 or recid_jenisabsen = 13 or recid_jenisabsen = 24");
		return $query;
	}

	public function jenis_absen_id($id)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from jenis_absen where recid_jenisabsen = $id");
		return $query;
	}

	public function jenis_absen_status($status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from jenis_absen where $status");
		return $query;
	}

	public function save_data($table, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert($table, $data);
	}

	public function cek_absensi($recid_karyawan, $tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query =  $db2->query("SELECT * FROM absensi_hris where recid_karyawan = '$recid_karyawan' and tanggal = '$tgl' and is_delete = '0'");
		return $query;
	}

	public function absensi_by_recidabsen($recid_absen)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query =  $db2->query("SELECT * FROM absensi_hris where absensi_recid = '$recid_absen'");
		return $query;
	}

	public function absen_view()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query =  $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen order by a.absensi_recid desc");
		return $query;
	}

	public function edit_absensi($id, $tgl, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_karyawan', $id);
		$db2->where('tanggal', $tgl);
		$db2->update('absensi_hris', $data);
	}

	public function edit_absensi_recid($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('absensi_recid', $id);
		$db2->update('absensi_hris', $data);
	}

	public function save_absensi($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert('absensi_hris', $data);
	}

	public function cek_emp_hadir($recid_karyawan, $tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query =  $db2->query("SELECT h.*, ja.absen_group, ja.keterangan FROM hadir_barcode h join jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = '$recid_karyawan' and tanggal = '$tgl'");
		return $query;
	}

	public function cek_emp_hadir2($recid_karyawan, $tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query =  $db2->query("SELECT h.*, ja.absen_group, ja.keterangan, ja.jam_in, ja.jam_out FROM hadir_barcode h join jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = '$recid_karyawan' and tanggal = '$tgl' and is_closed = '0'");
		return $query;
	}

	public function cek_absen_today($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.tanggal = '$tgl' and a.is_delete = '0' and ja.absen_group = 'Tidak Hadir' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function cek_absen_today_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where (a.tanggal = '$tgl') and ($bagian) and a.is_delete = '0' and ja.absen_group = 'Tidak Hadir' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function cek_absen_today_baros($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where (a.tanggal = '$tgl') and k.penempatan = 'Baros' and a.is_delete = '0' and ja.absen_group = 'Tidak Hadir' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function absen_by_recid($id)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn join hris.struktur s on s.recid_struktur = b.recid_struktur join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.absensi_recid = '$id'");
		return $query;
	}

	public function tgl_sid_emp($bulan, $thn, $recid_karyawan, $kategori, $diagnosa)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.jenis_absen = '2' and a.recid_karyawan = $recid_karyawan and month(a.tanggal) = $bulan and year(a.tanggal) = $thn and a.kategori = '$kategori' and a.diagnosa = '$diagnosa'");
		return $query;
	}

	public function tgl_sid_emp2($bulan, $thn, $recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen where a.jenis_absen = '2' and a.recid_karyawan = $recid_karyawan and month(a.tanggal) = $bulan and year(a.tanggal) = $thn and (kategori is null or diagnosa is null)");
		return $query;
	}

	public function hk_view($tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from hari_kerja where tahun = $tahun order by bulan asc");
		return $query;
	}

	public function hk_by_bulan($tahun, $bulan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from hari_kerja where tahun = $tahun and bulan = $bulan");
		return $query;
	}

	public function hk_bulan_keamanan($tahun, $bulan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT h.* FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where b.recid_bag = 24 and month(h.tanggal) = $bulan and year(h.tanggal) = $tahun and status != 24;");
		return $query;
	}

	public function hk_by_tahun($tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT sum(jml_hk) as jml_hk from hari_kerja where tahun = $tahun");
		return $query;
	}

	public function tahun_hk()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(tahun) as tahun from hari_kerja order by tahun desc");
		return $query;
	}

	public function insert_hk($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert_batch('hari_kerja', $data);
	}

	public function edit_hk($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_hk', $id);
		$db2->update('hari_kerja', $data);
	}

	public function hk_by_periode($where)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT sum(jml_hk) as jml_hk from hari_kerja where $where");
		return $query;
	}

	public function gh_view()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from ganti_hari where is_delete = '0' order by year(tanggal) desc, month(tanggal) asc, date(tanggal) asc");
		return $query;
	}

	public function edit_gh($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('gh_recid', $id);
		$db2->update('ganti_hari', $data);
	}

	public function gh_by_date($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from ganti_hari where tanggal = '$tgl' and is_delete = '0'");
		return $query;
	}

	public function gh_by_tanggal($mulai, $sampai)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from ganti_hari where tanggal between '$mulai' and '$sampai'");
		return $query;
	}

	public function cek_duplikat_izin($tgl_izin, $recid_karyawan, $kategori)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin where tgl_izin  = '$tgl_izin' and recid_karyawan = $recid_karyawan and jenis = '$kategori'");
		return $query;
	}

	public function cek_duplikat_telat($tgl_izin, $recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin where tgl_izin  = '$tgl_izin' and recid_karyawan = $recid_karyawan and (jenis = 'Terlambat' or jenis = 'Terlambat Terencana' or jenis = 'Terlambat Tidak Terencana')");
		return $query;
	}

	public function cek_duplikat_keluar($tgl_izin, $recid_karyawan, $kategori)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin where tgl_izin  = '$tgl_izin' and recid_karyawan = $recid_karyawan and jenis = 'Keluar' and kat_keluar = '$kategori'");
		return $query;
	}

	public function izin_ongoing_tgl($tgl_izin, $recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin where tgl_izin  = '$tgl_izin' and recid_karyawan = $recid_karyawan and jenis = 'Keluar' and jam_in = '' ");
		return $query;
	}

	public function edit_izin($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('izin_recid', $id);
		$db2->update('izin', $data);
	}

	public function edit_izin2($data, $recid_karyawan, $tanggal, $jenis_izin)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_karyawan', $recid_karyawan);
		$db2->where('tgl_izin', $tanggal);
		$db2->where('jenis', $jenis_izin);
		$db2->update('izin', $data);
	}

	public function libur_by_date($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from master_absen.cuti_bersama where tanggal = '$tgl'");
		return $query;
	}

	public function cek_cuti_idkar_sisa($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * FROM cuti_karyawan where recid_karyawan = $recid_karyawan and hangus = '0' and jml_cuti > 0 order by cuti_thn_ke asc limit 1;");
		return $query;
	}

	public function sisa_cuti_active($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * FROM cuti_karyawan where recid_karyawan = $recid_karyawan and hangus = '0'  order by cuti_thn_ke asc limit 1;");
		return $query;
	}

	public function cek_sisa_cuti_kar($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.recid_karyawan, k.nama_karyawan, sum(c.jml_cuti) as jml_cuti FROM cuti_karyawan c join hris.karyawan k on k.recid_karyawan = c.recid_karyawan where k.recid_karyawan = $recid_karyawan and hangus = '0'");
		return $query;
	}

	public function check_cuti_daily()
	{
		$query = $this->db->query("SELECT * FROM hris.karyawan where day(tgl_m_kerja) = date('d') and month(tgl_m_kerja) = date('m') and sts_aktif = 'Aktif' and spm ='Tidak' and cci = 'Tidak';");
		return $query;
	}

	public function check_cuti_tgl($tgl)
	{
		$query = $this->db->query("SELECT * FROM hris.karyawan where day(tgl_m_kerja) = day('$tgl') and month(tgl_m_kerja) = month('$tgl') and sts_aktif = 'Aktif' and spm ='Tidak' and cci = 'Tidak';");
		return $query;
	}

	public function rekap_kehadiran($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT b.indeks_hr, s.nama_struktur, d.nama_department,count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 1, 1, NULL)) AS K,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 4, 1, NULL)) AS D, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 7, 1, NULL)) AS H2, 
			COUNT(IF(recid_jenisabsen = 8, 1, NULL)) AS WFH,
			COUNT(IF(recid_jenisabsen = 9, 1, NULL)) AS MS,
			COUNT(IF(recid_jenisabsen = 10, 1, NULL)) AS A,
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M, 
			COUNT(IF(recid_jenisabsen = 12, 1, NULL)) AS P4, 
			COUNT(IF(recid_jenisabsen = 13, 1, NULL)) AS TOS, 
			COUNT(IF(recid_jenisabsen = 14, 1, NULL)) AS S1, 
			COUNT(IF(recid_jenisabsen = 15, 1, NULL)) AS S2, 
			COUNT(IF(recid_jenisabsen = 16, 1, NULL)) AS S3,
			COUNT(IF(recid_jenisabsen = 17, 1, NULL)) AS LS1,
			COUNT(IF(recid_jenisabsen = 18, 1, NULL)) AS LS2,
			COUNT(IF(recid_jenisabsen = 19, 1, NULL)) AS SS1, 
			COUNT(IF(recid_jenisabsen = 20, 1, NULL)) AS SS2, 
			COUNT(IF(recid_jenisabsen = 21, 1, NULL)) AS SS3, 
			COUNT(IF(recid_jenisabsen = 22, 1, NULL)) AS SPM1, 
			COUNT(IF(recid_jenisabsen = 23, 1, NULL)) AS SPM2, 
			COUNT(IF(recid_jenisabsen = 24, 1, NULL)) AS L,
			COUNT(IF(recid_jenisabsen = 25, 1, NULL)) AS KK
			FROM master_absen.hadir_hris h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where TANGGAL = '$tgl' group by b.indeks_hr");
		return $query;
	}

	public function rekap_kehadiran_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT b.indeks_hr, s.nama_struktur, d.nama_department, count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 1, 1, NULL)) AS K,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 4, 1, NULL)) AS D, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 7, 1, NULL)) AS H2, 
			COUNT(IF(recid_jenisabsen = 8, 1, NULL)) AS WFH,
			COUNT(IF(recid_jenisabsen = 9, 1, NULL)) AS MS,
			COUNT(IF(recid_jenisabsen = 10, 1, NULL)) AS A,
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M, 
			COUNT(IF(recid_jenisabsen = 12, 1, NULL)) AS P4, 
			COUNT(IF(recid_jenisabsen = 13, 1, NULL)) AS TOS, 
			COUNT(IF(recid_jenisabsen = 14, 1, NULL)) AS S1, 
			COUNT(IF(recid_jenisabsen = 15, 1, NULL)) AS S2, 
			COUNT(IF(recid_jenisabsen = 16, 1, NULL)) AS S3,
			COUNT(IF(recid_jenisabsen = 17, 1, NULL)) AS LS1,
			COUNT(IF(recid_jenisabsen = 18, 1, NULL)) AS LS2,
			COUNT(IF(recid_jenisabsen = 19, 1, NULL)) AS SS1, 
			COUNT(IF(recid_jenisabsen = 20, 1, NULL)) AS SS2, 
			COUNT(IF(recid_jenisabsen = 21, 1, NULL)) AS SS3, 
			COUNT(IF(recid_jenisabsen = 22, 1, NULL)) AS SPM1, 
			COUNT(IF(recid_jenisabsen = 23, 1, NULL)) AS SPM2, 
			COUNT(IF(recid_jenisabsen = 24, 1, NULL)) AS L,
			COUNT(IF(recid_jenisabsen = 25, 1, NULL)) AS KK,
			COUNT(IF(recid_jenisabsen = 26, 1, NULL)) AS OB,
			COUNT(IF(recid_jenisabsen = 27, 1, NULL)) AS S4
			FROM master_absen.hadir_hris h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where TANGGAL = '$tgl' and $bagian group by b.indeks_hr");
		return $query;
	}

	public function rekap_kehadiran_dept_group($tgl, $dept_group)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT b.indeks_hr, s.nama_struktur, d.nama_department,count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 1, 1, NULL)) AS K,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 4, 1, NULL)) AS D, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 7, 1, NULL)) AS H2, 
			COUNT(IF(recid_jenisabsen = 8, 1, NULL)) AS WFH,
			COUNT(IF(recid_jenisabsen = 9, 1, NULL)) AS MS,
			COUNT(IF(recid_jenisabsen = 10, 1, NULL)) AS A,
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M, 
			COUNT(IF(recid_jenisabsen = 12, 1, NULL)) AS P4, 
			COUNT(IF(recid_jenisabsen = 13, 1, NULL)) AS TOS, 
			COUNT(IF(recid_jenisabsen = 14, 1, NULL)) AS S1, 
			COUNT(IF(recid_jenisabsen = 15, 1, NULL)) AS S2, 
			COUNT(IF(recid_jenisabsen = 16, 1, NULL)) AS S3,
			COUNT(IF(recid_jenisabsen = 17, 1, NULL)) AS LS1,
			COUNT(IF(recid_jenisabsen = 18, 1, NULL)) AS LS2,
			COUNT(IF(recid_jenisabsen = 19, 1, NULL)) AS SS1, 
			COUNT(IF(recid_jenisabsen = 20, 1, NULL)) AS SS2, 
			COUNT(IF(recid_jenisabsen = 21, 1, NULL)) AS SS3, 
			COUNT(IF(recid_jenisabsen = 22, 1, NULL)) AS SPM1, 
			COUNT(IF(recid_jenisabsen = 23, 1, NULL)) AS SPM2, 
			COUNT(IF(recid_jenisabsen = 24, 1, NULL)) AS L,
			COUNT(IF(recid_jenisabsen = 25, 1, NULL)) AS KK
			FROM master_absen.hadir_hris h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where TANGGAL = '$tgl' and d.dept_group = '$dept_group' group by b.indeks_hr");
		return $query;
	}

	public function admin_view()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT b.recid_bag, b.indeks_hr, s.nama_struktur, d.nama_department, a.recid_karyawan, k.nama_karyawan from hris.bagian b 
			join hris.struktur s on b.recid_struktur = s.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			left join admin_bagian a on a.recid_bag = b.recid_bag
			left join hris.karyawan k on k.recid_karyawan = a.recid_karyawan
			where b.is_delete = '0' and b.indeks_hr != ''
			order by b.indeks_hr asc;")->result();
		return $query;
	}

	public function cek_admin_bagian($bag)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from admin_bagian where recid_bag = '$bag'");
		return $query;
	}

	public function bagian_by_admin($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, b.indeks_hr, b.nama_bag from admin_bagian a join hris.bagian b on b.recid_bag = a.recid_bag where recid_karyawan = '$recid_karyawan' and b.is_delete = '0'");
		return $query;
	}

	public function admin_by_bagian($recid_bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, b.indeks_hr, b.nama_bag from admin_bagian a join hris.bagian b on b.recid_bag = a.recid_bag where b.recid_bag = '$recid_bagian'");
		return $query;
	}

	public function bagian_by_dept_group($dept_group)
	{

		$query = $this->db->query("SELECT distinct(k.recid_bag), b.indeks_hr from karyawan k join bagian b on b.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department where d.dept_group = '$dept_group'");
		return $query;
	}

	public function bagian_spm()
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag), b.indeks_hr from karyawan k join bagian b on b.recid_bag = k.recid_bag where k.spm = 'Ya'");
		return $query;
	}

	public function dept_group_admin($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(dept_group) as dept_group from admin_bagian a JOIN hris.bagian b on b.recid_bag = a.recid_bag join hris.department d on d.recid_department = b.recid_department where recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function dept_group_spm()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(dept_group) as dept_group from admin_bagian a JOIN hris.bagian b on b.recid_bag = a.recid_bag join hris.department d on d.recid_department = b.recid_department join hris.karyawan k on b.recid_bag = k.recid_bag where k.spm = 'Ya'");
		return $query;
	}

	public function dept_group_user($recid_karyawan)
	{
		$query = $this->db->query("SELECT distinct(dept_group) as dept_group from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function dept_user($recid_karyawan)
	{
		$query = $this->db->query("SELECT distinct(nama_department) as department, d.recid_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function dept_admin($recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(nama_department) as department, d.recid_department from admin_bagian a JOIN hris.bagian b on b.recid_bag = a.recid_bag join hris.department d on d.recid_department = b.recid_department where recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function dept_spm()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT distinct(nama_department) as department, d.recid_department from admin_bagian a JOIN hris.bagian b on b.recid_bag = a.recid_bag join hris.department d on d.recid_department = b.recid_department join hris.karyawan k on k.recid_bag = b.recid_bag where k.spm = 'Ya' ");
		return $query;
	}

	public function dept_by_dept_group($dept_group)
	{
		$query = $this->db->query("SELECT distinct(nama_department) as department, d.recid_department from bagian b JOIN department d  on b.recid_department = d.recid_department  join hris.karyawan k on k.recid_bag = b.recid_bag where d.dept_group = '$dept_group' ");
		return $query;
	}

	public function update_admin($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_data', $id);
		$db2->update('admin_bagian', $data);
	}

	public function save_admin($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert('admin_bagian', $data);
	}

	public function jml_all($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and k.tc = '0'");
		return $query;
	}

	public function jml_spm($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Ya'");
		return $query;
	}

	public function jml_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and $bagian");
		return $query;
	}

	public function jml_karyawan_baros($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and k.penempatan = 'Baros'");
		return $query;
	}

	public function jml_bagian_spm($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Ya' and $bagian");
		return $query;
	}

	public function jml_all_bagian($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and $bagian");
		return $query;
	}

	public function jml_all_dept_group($tgl, $dept_group)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and d.dept_group = '$dept_group'");
		return $query;
	}

	public function jml_all_bagian_spm($tgl, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where tanggal = '$tgl' and k.cci = 'Tidak' and k.spm = 'Ya' and $bagian");
		return $query;
	}

	public function jml_by_status($tgl, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and (k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0') and ($status)");
		return $query;
	}

	public function jml_by_status_spm($tgl, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and k.cci = 'Tidak' and k.spm = 'Ya' and ($status)");
		return $query;
	}

	public function jml_by_status_baros($tgl, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and k.cci = 'Tidak' and penempatan = 'Baros' and k.spm = 'Tidak' and ($status)");
		return $query;
	}

	public function jml_by_status_bagian($tgl, $status, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and ($status) and ($bagian)");
		return $query;
	}

	public function jml_by_status_dept_group($tgl, $status, $dept_group)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis, k.penempatan FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and ($status) and (d.dept_group = '$dept_group')");
		return $query;
	}

	public function jml_by_status_bagian_spm($tgl, $status, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*, ja.keterangan, ja.jenis FROM hadir_hris h 
			join jenis_absen ja on ja.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal = '$tgl') and k.cci = 'Tidak' and k.spm = 'Ya' and ($status) and ($bagian)");
		return $query;
	}

	public function izin_by_status($tgl, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*FROM izin h 
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_izin = '$tgl') and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and ($status)");
		return $query;
	}

	public function izin_by_status_bagian($tgl, $status, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*FROM izin h 
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_izin = '$tgl') and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and ($status) and ($bagian)");
		return $query;
	}

	public function izin_by_status_baros($tgl, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.*FROM izin h 
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tgl_izin = '$tgl') and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc = '0' and ($status) and k.penempatan = 'Baros'");
		return $query;
	}

	public function count_norma_nonnorma($tgl_mulai, $tgl_akhir, $status)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, d.nama_department, s.nama_struktur, j.indeks_jabatan, count(h.recid_absen) as jml_absen FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.department d on d.recid_department = b.recid_department join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where (tanggal between '$tgl_mulai' and '$tgl_akhir') and $status group by k.recid_karyawan;");
		return $query;
	}

	public function rekap_sid_bulan($bulan, $tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.recid_karyawan, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, a.tanggal, a.kategori, a.diagnosa, count(a.tanggal) as lama FROM absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.struktur s on s.recid_struktur = b.recid_struktur join hris.department d on d.recid_department = b.recid_department join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where jenis_absen = 2 and month(a.tanggal) = $bulan and Year(a.tanggal) = $tahun and a.is_delete = '0' group by k.recid_karyawan, kategori, diagnosa order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan;");
		return $query;
	}

	public function all_cuti()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, d.nama_department, sum(jml_cuti) as sisa_cuti, max(expired) as expired FROM cuti_karyawan c 
			join hris.karyawan k on k.recid_karyawan = c.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where hangus = '0'
			group by k.recid_karyawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan;");
		return $query;
	}

	public function all_cuti2()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, d.nama_department, c.cuti_thn_ke, jml_cuti as sisa_cuti, c.expired, c.recid_cuti FROM cuti_karyawan c 
			join hris.karyawan k on k.recid_karyawan = c.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where hangus = '0'
			order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan;");
		return $query;
	}

	public function all_cuti2_bagian($bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, d.nama_department, c.cuti_thn_ke, jml_cuti as sisa_cuti, c.expired, c.recid_cuti FROM cuti_karyawan c 
			join hris.karyawan k on k.recid_karyawan = c.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where hangus = '0' and ($bagian) and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak'
			order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan;");
		return $query;
	}

	public function all_cuti2_baros()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, d.nama_department, c.cuti_thn_ke, jml_cuti as sisa_cuti, c.expired, c.recid_cuti FROM cuti_karyawan c 
			join hris.karyawan k on k.recid_karyawan = c.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where hangus = '0' and k.penempatan = 'Baros' and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak'
			order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan;");
		return $query;
	}

	/*-------------------------------------------------------------------------------*/

	/*public function mangkir_all($mulai, $henti)
	{
		$query = $this->db->query("SELECT DISTINCT(m.nik) as nik, k.nama_karyawan, b.nama_bag from master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on k.recid_bag = b.recid_bag where k.sts_aktif = 'Aktif' and TANGGAL between '$mulai' and '$henti' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function mangkir_dept($dept, $mulai, $henti)
	{
		$query = $this->db->query("SELECT DISTINCT(m.nik) as nik, k.nama_karyawan, b.nama_bag from master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on k.recid_bag = b.recid_bag where  department='$dept' and k.sts_aktif = 'Aktif' and TANGGAL between '$mulai' and '$henti' order by b.department,  b.nama_bag asc")->result();
		return $query;
	}

	public function mangkir_str($str, $mulai, $henti)
	{
		$query = $this->db->query("SELECT DISTINCT(m.nik) as nik, k.nama_karyawan, b.nama_bag from master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on k.recid_bag = b.recid_bag where b.recid_struktur ='$str' and k.sts_aktif = 'Aktif' and TANGGAL between '$mulai' and '$henti' order by b.department,  b.nama_bag asc")->result();
		return $query;
	}

	public function mangkir_bag($bag, $mulai, $henti)
	{
		$query = $this->db->query("SELECT DISTINCT(m.nik) as nik, k.nama_karyawan, b.nama_bag from master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on k.recid_bag = b.recid_bag where k.recid_bag ='$bag' and k.sts_aktif = 'Aktif' and TANGGAL between '$mulai' and '$henti' order by b.department,  b.nama_bag asc")->result();
		return $query;
	}
*/
	public function izin_view()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and tgl_izin = current_date() order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_by_id($id)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and izin_recid = $id order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_view_bagian($bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and ($bagian) and tgl_izin = current_date() order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_periode($tgl_mulai, $tgl_akhir)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and i.tgl_izin between '$tgl_mulai' AND '$tgl_akhir' order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_periode_bagian($tgl_mulai, $tgl_akhir, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and i.tgl_izin between '$tgl_mulai' AND '$tgl_akhir' and $bagian order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_periode_baros($tgl_mulai, $tgl_akhir)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and i.tgl_izin between '$tgl_mulai' AND '$tgl_akhir' and k.penempatan = 'Baros'  order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_periode_jenis($tgl_mulai, $tgl_akhir, $jenis)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and i.jenis = '$jenis' and i.tgl_izin between '$tgl_mulai' AND '$tgl_akhir' order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	public function izin_periode_jenis_bagian($tgl_mulai, $tgl_akhir, $jenis, $bagian)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on j.recid_jbtn = k.recid_jbtn where i.is_delete = '0' and (i.jenis = '$jenis') and (i.tgl_izin between '$tgl_mulai' AND '$tgl_akhir') and ($bagian) order by b.indeks_hr, j.indeks_jabatan asc");
		return $query;
	}

	// public function allabsen_semua()
	// {
	// 	$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department,  b.nama_bag asc")->result();
	// 	return $query;
	// }	
	public function allabsen_semua()
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn where k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_department($dept)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_struktur($str)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn where b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_bagian($bag)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn where k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function allaccess_semua()
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag, j.nama_jbtn FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on k.recid_jbtn = j.recid_jbtn and k.sts_aktif='Aktif' and spm = 'Tidak' group by a.ssn")->result();
		return $query;
	}

	public function allaccess_struktur($str)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag, j.nama_jbtn FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on k.recid_jbtn = j.recid_jbtn where b.recid_struktur = '$str' and k.sts_aktif='Aktif' and spm = 'Tidak' group by a.ssn")->result();
		return $query;
	}

	public function allaccess_bagian($bag)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag, j.nama_jbtn FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag join  hris.jabatan j on k.recid_jbtn = j.recid_jbtn where k.recid_bag = '$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' group by a.ssn")->result();
		return $query;
	}

	public function allaccess_department($dept)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag, j.nama_jbtn FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag join hris.jabatan j on k.recid_jbtn = j.recid_jbtn where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' group by a.ssn")->result();
		return $query;
	}

	/*public function allmangkir($date_work)
	{
		$query = $this->db->query("SELECT m.* from hris.karyawan k join hris.bagian b on k.recid_bag = b.recid_bag join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department,  b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_department($dept, $date_work)
	{
		$query = $this->db->query("SELECT m.* from hris.karyawan k join hris.bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department,  b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_bagian($bag, $date_work)
	{
		$query = $this->db->query("SELECT m.* from karyawan k join bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department,  b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_struktur($str, $date_work)
	{
		$query = $this->db->query("SELECT m.* from karyawan k join bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.department,  b.nama_bag asc")->result();
		return $query;
	}*/

	public function department_semua()
	{
		$query = $this->db->query("SELECT distinct(b.department) as department from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_semua()
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_department($dept)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_struktur($str)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag where b.recid_struktur ='$str'  and k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_bagian($bag)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag where b.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' order by b.department, b.nama_bag asc")->result();
		return $query;
	}

	public function karyawan_view()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.* from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur where k.sts_aktif='Aktif' order by nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_by_nik($nik)
	{
		$query = $this->db->query("SELECT * from karyawan  where nik = '$nik'")->result();
		return $query;
	}

	public function karyawan_by_nik2($nik)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.*, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join department d on d.recid_department = b.recid_department left join karyawan sa on s.pic_struktur = sa.recid_karyawan where k.recid_karyawan = '$nik'")->result();
		return $query;
	}

	public function cek_absen($nik, $tgl)
	{
		$query = $this->db->query("SELECT m.*, k.recid_bag From master_absen.absen m join hris.karyawan k on m.nik = k.nik where m.nik = '$nik' and m.date_work = '$tgl' and k.sts_aktif = 'Aktif'");
		return $query;
	}

	/*public function cek_mangkir($nik, $tgl)
	{
		$query = $this->db->query("SELECT m.* From master_absen.mangkir m join hris.karyawan k on m.nik = k.nik where m.nik = '$nik' and m.tanggal = '$tgl' and k.sts_aktif = 'Aktif'");
		return $query;
	}*/

	public function cek_shift($nik, $tgl)
	{
		$query = $this->db->query("SELECT * FROM master_absen.shift where nik = '$nik' and TGL_SHIFT = '$tgl'");
		return $query;
	}

	public function hadir($nik, $awal, $akhir)
	{
		$hadir =  $this->db->query("SELECT count(nik) as kerja from master_absen.absen where nik = '$nik' and time_in != '' and time_out !='' and DATE_WORK between '$awal' and '$akhir'")->result();
		return $hadir;
	}

	/*public function cuti($nik, $awal, $akhir)
	{
		$cuti =  $this->db->query("SELECT count(nik) as cuti from master_absen.mangkir where nik = 'nik' and tanggal between '$awal' and '$akhir' and (code = 'T')")->result();
		return $cuti;
	}

	public function sid($nik, $awal, $akhir)
	{
		 $sid =  $this->db->query("SELECT count(nik) as sid from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'S1D')")->result();
		 return $sid;
	}

	public function h1($nik, $awal, $akhir)
	{
		 $h1 =  $this->db->query("SELECT count(nik) as h1 from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'H1')")->result();
		 return $h1;
	}

	public function h2($nik, $awal, $akhir)
	{
		 $h2 =  $this->db->query("SELECT count(nik) as h2 from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'H2')")->result();
		 return $h2;
	}

	public function p1($nik, $awal, $akhir)
	{
		 $p1 =  $this->db->query("SELECT count(nik) as p1 from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'P1')")->result();
		 return $p1;
	}
	public function p3($nik, $awal, $akhir)
	{
		 $p3 =  $this->db->query("SELECT count(nik) as p3 from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'P3')")->result();
		 return $p3;
	}
	public function p4($nik, $awal, $akhir)
	{
		 $p4 =  $this->db->query("SELECT count(nik) as p4 from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'P4')")->result();
		 return $p4;
	}
	public function mangkir($nik, $awal, $akhir)
	{
		 $mangkir =  $this->db->query("SELECT count(nik) as mangkir from master_absen.mangkir where nik = '$nik' and tanggal between '$awal' and '$akhir' and (code = 'MA')")->result();
		 return $mangkir;
	}*/

	// public function count_allactive($bulan, $tahun)
	// {
	// 	$query = $this->db->query("SELECT nama_karyawan, tgl_a_kerja, month(tgl_a_kerja) as usi, year(tgl_a_kerja) as th FROM `karyawan` having (usi > '$bulan' or usi = 0 or usi is null) and (th >= '$tahun' or th = '0' or th is null )");
	// 	return $query;
	// }

	public function count_active_wanita($bulan, $tahun, $bag)
	{
		$query = $this->db->query("SELECT nik, nama_karyawan, tgl_a_kerja, month(tgl_a_kerja) as usi, year(tgl_a_kerja) as th FROM karyawan join bagian b on b.recid_bag = karyawan.recid_bag where b.recid_bag = '$bag' and jenkel = 'Wanita' having ((usi >= '$bulan' or usi = 0 or usi is null) and (th >= '$tahun' or th = '0' or th is null )) or ((usi >= '1' or usi = 0 or usi is null) and (th > '$tahun' or th = '0' or th is null ))  ");
		return $query;
	}

	public function count_active_pria($bulan, $tahun, $bag)
	{
		$query = $this->db->query("SELECT nik, nama_karyawan, tgl_a_kerja, month(tgl_a_kerja) as usi, year(tgl_a_kerja) as th FROM karyawan join bagian b on b.recid_bag = karyawan.recid_bag where b.recid_bag = '$bag' and jenkel = 'Pria' having ((usi >= '$bulan' or usi = 0 or usi is null) and (th >= '$tahun' or th = '0' or th is null )) or ((usi >= '1' or usi = 0 or usi is null) and (th > '$tahun' or th = '0' or th is null ))  ");
		return $query;
	}

	/*public function count_absen($bulan, $tahun, $bag)
	{
		$query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, count(m.nik) as jml from karyawan k join master_absen.mangkir m on k.nik = m.nik join bagian b on k.recid_bag = b.recid_bag where (month(TANGGAL) = '$bulan' and YEAR(TANGGAL) = '$tahun') and b.recid_bag = $bag and CODE != 'T' group by k.nik");
		return $query;
	}*/

	public function save_closing($data)
	{
		$this->db->insert('closing_karyawan', $data);
	}

	public function tahun_closing()
	{
		$query = $this->db->query("SELECT DISTINCT(tahun)as tahun FROM closing_karyawan");
		return $query;
	}

	public function max_tahun()
	{
		$query = $this->db->query("SELECT (max(tahun) + 1) as thnn from closing_karyawan");
		return $query;
	}

	public function cuber_view()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from cuti_bersama where is_delete = '0' order by year(tanggal) desc, month(tanggal) asc, date(tanggal) asc");
		return $query;
	}

	public function cuber_tahun($year)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from cuti_bersama where year(tanggal) = $year and jenis = 'Cuti Bersama' and is_delete = '0'");
		return $query;
	}

	public function cuber_by_tanggal($mulai, $sampai)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from cuti_bersama where tanggal  between '$mulai' and '$sampai'");
		return $query;
	}

	public function cuber_by_tanggal2($mulai, $sampai)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT *, DAYNAME(tanggal) as hari from cuti_bersama where tanggal between '$mulai' and '$sampai' and (dayname(tanggal) != 'Saturday' and dayname(tanggal) != 'Sunday')");
		return $query;
	}

	public function cek_cuber($tanggal)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from cuti_bersama where tanggal = '$tanggal' and is_delete = '0'");
		return $query;
	}

	public function edit_cuber($id, $data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('cuber_recid', $id);
		$db2->update('cuti_bersama', $data);
	}

	public function save_cuti_karyawan($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert('cuti_karyawan', $data);
	}

	public function edit_cukar($data, $id)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->where('recid_cuti', $id);
		$db2->update('cuti_karyawan', $data);
	}

	public function cuti_karyawan_expired($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from cuti_karyawan where expired = '$tgl'");
		return $query;
	}

	public function cuti_by_id($recid_cuti)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, d.nama_department, c.*  FROM cuti_karyawan c 
			join hris.karyawan k on k.recid_karyawan = c.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			where recid_cuti = '$recid_cuti'");
		return $query;
	}

	public function p1_by_emp($recid_karyawan, $tgl_mulai, $tgl_akhir)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT * from absensi_hris where jenis_absen = '5' and recid_karyawan = $recid_karyawan and tanggal between 'tgl_mulai' and '$tgl_akhir'");
		return $query;
	}

	public function save_log_daily($data)
	{
		$db2 = $this->load->database('absen', TRUE);
		$db2->insert('log_daily', $data);
	}

	public function histori_cuti($cuti_ke, $recid_karyawan)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT a.*, a.keterangan as ket, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan 
		from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan 
		join hris.bagian b on b.recid_bag = k.recid_bag 
		join hris.jabatan j on j.recid_jbtn = k.recid_jbtn 
		join hris.struktur s on s.recid_struktur = b.recid_struktur 
		join jenis_absen ja on ja.recid_jenisabsen = a.jenis_absen 
		where k.recid_karyawan = $recid_karyawan and a.cuti_ke = $cuti_ke and a.is_delete = '0' and ja.recid_jenisabsen = 3 order by tanggal asc;");
		return $query;
	}

	/**
	 * Get karyawan yang terlambat untuk hari tertentu
	 * Terlambat = jam_masuk > 07:00 dan status = 1 (Kerja)
	 */
	public function get_terlambat($tgl)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("
			SELECT h.*, k.nik, k.nama_karyawan, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, ja.jenis, ja.keterangan
			FROM hadir_hris h
			JOIN hris.karyawan k ON k.recid_karyawan = h.recid_karyawan
			JOIN hris.bagian b ON b.recid_bag = k.recid_bag
			JOIN hris.struktur s ON s.recid_struktur = b.recid_struktur
			JOIN hris.jabatan j ON j.recid_jbtn = k.recid_jbtn
			JOIN jenis_absen ja ON ja.recid_jenisabsen = h.status
			WHERE h.tanggal = '$tgl' 
			AND h.status = 1 
			AND h.jam_masuk IS NOT NULL
			AND TIME(h.jam_masuk) > '07:00:00'
			AND k.sts_aktif = 1
			ORDER BY h.jam_masuk DESC
		");
		return $query;
	}

	/**
	 * Get semua izin (validated late) - stored in hadir_hris at master_finger database
	 */
	public function get_izin($tgl = null)
	{
		$db2 = $this->load->database('absen', TRUE); // Use master_absen database
		
		$query_str = "
			SELECT 
				i.izin_recid,
				i.recid_karyawan,
				i.tgl_izin,
				i.jenis,
				i.kat_keluar,
				i.jam_in,
				i.jam_out,
				i.keterangan,
				i.deskripsi,
				i.perlu_validasi,
				i.validated_by,
				i.validated_date,
				i.crt_by,
				i.crt_date,
				k.nik,
				k.nama_karyawan,
				b.recid_bag,
				b.nama_bag,
				j.recid_jbtn,
				j.nama_jbtn as indeks_jabatan,
				s.recid_struktur,
				s.nama_struktur
			FROM master_absen.izin i
			JOIN hris.karyawan k ON k.recid_karyawan = i.recid_karyawan
			JOIN hris.bagian b ON b.recid_bag = k.recid_bag
			JOIN hris.struktur s ON s.recid_struktur = b.recid_struktur
			JOIN hris.jabatan j ON j.recid_jbtn = k.recid_jbtn
			WHERE i.is_delete = '0'
		";
		
		// Add date filter jika ada
		if ($tgl) {
			$query_str .= " AND i.tgl_izin = '$tgl'";
		}
		
		$query_str .= " ORDER BY i.tgl_izin DESC, i.crt_date DESC";
		
		$query = $db2->query($query_str);
		return $query;
	}

	/**
	 * Validasi terlambat - set is_validated = 1 in master_finger.hadir_hris
	 */
	public function validasi_terlambat($recid_hadir, $recid_karyawan, $tgl)
	{
		$db2 = $this->load->database('absen2', TRUE);
		$update_data = array(
			'is_validated' => 1,
			'validated_by' => $this->session->userdata('kar_id'),
			'validated_date' => date('Y-m-d H:i:s')
		);
		$db2->where('recid_hadir', $recid_hadir);
		$db2->where('recid_karyawan', $recid_karyawan);
		$db2->where('tanggal', $tgl);
		return $db2->update('hadir_hris', $update_data);
	}
}

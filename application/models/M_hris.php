<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_hris extends CI_Model
{

	// ################################################### USER ###################################################################
	public function user_view()
	{
		$query = $this->db->query("SELECT l.recid_login, l.is_delete, l.recid_karyawan, l.recid_role, l.username, l.password, r.nama_role, l.note, k.nama_karyawan, k.nik, b.indeks_hr, j.indeks_jabatan, g.nama_golongan, k.penempatan from login l join karyawan k on k.recid_karyawan = l.recid_karyawan join role r on r.recid_role = l.recid_role join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan")->result();
		return $query;
	}

	public function user_pinsert($data)
	{
		$this->db->insert('login', $data);
	}

	public function user_linsert($data)
	{
		$this->db->insert('log_login', $data);
	}

	public function insert_histori_pwd($data)
	{
		$this->db->insert('pwd_histori', $data);
	}

	public function histori_pwd_user($recid_login, $pass)
	{
		$query = $this->db->query("SELECT * from pwd_histori where recid_login = ? and password = ? and is_delete = '0'", array($recid_login, $pass));
		return $query;
	}

	public function reaktif_pwd()
	{
		$query = $this->db->query("UPDATE pwd_histori set is_delete = '1' where datediff(current_date(), tgl_ubah) >= 365;");
		return $query;
	}

	public function user_update($data, $id)
	{
		$this->db->where('recid_login', $id);
		$this->db->update('login', $data);
	}

	public function user_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from login where recid_login = '$recid'")->result();
		return $query;
	}

	public function user_by_username($username)
	{
		$query = $this->db->query("SELECT * from login where username = '$username'");
		return $query;
	}

	public function cek_uname($uname)
	{
		$query = $this->db->query("SELECT * FROM login where username = '$uname'");
		if ($query->num_rows() > 0) {
			return 1; //-------------------- ada
		} else {
			return 0;
		}
	}

	public function list_user()
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where recid_karyawan not in (select recid_karyawan from login) and sts_aktif = 'Aktif'")->result();
		return $query;
	}

	public function cek_usr($user)
	{
		$query = $this->db->query("SELECT u.username, k.nama_karyawan, b.indeks_hr, b.recid_bag, b.recid_struktur, j.indeks_jabatan, j.tingkatan, d.nama_department, d.recid_department, d.dept_group FROM login u 
			join karyawan k on k.recid_karyawan = u.recid_karyawan
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			join department d on d.recid_department = b.recid_department
			where k.recid_karyawan = ?", array($user))->result();
		return $query;
	}

	public function cek_kantin($user)
	{
		$query = $this->db->query("SELECT * from tamu
			where alias = '$user'")->result();
		return $query;
	}

	public function list_jenkel()
	{
		$query = $this->db->query("SELECT distinct(jenkel) FROM karyawan");
		return $query;
	}

	public function list_sts_aktif()
	{
		$query = $this->db->query("SELECT DISTINCT(sts_aktif) FROM karyawan order by sts_aktif asc");
		return $query;
	}

	public function list_dept_group()
	{
		// $query = $this->db->query("SELECT DISTINCT(dept_group) FROM department order by dept_group asc");
		$query = $this->db->query("SELECT DISTINCT(d.dept_group) from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by d.dept_group asc;");
		return $query;
	}

	public function list_department()
	{
		$query = $this->db->query("SELECT DISTINCT(d.recid_department) as recid_department, d.nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by d.nama_department asc");
		return $query;
	}

	public function list_bagian()
	{
		$query = $this->db->query("SELECT DISTINCT(b.recid_bag) as recid_bag, b.indeks_hr from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by b.indeks_hr asc;");
		return $query;
	}

	public function list_karyawan()
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.nama_karyawan from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc;");
		return $query;
	}

	public function list_pendidikan()
	{
		$query = $this->db->query("SELECT distinct(pendidikan) FROM karyawan order by pendidikan asc");
		return $query;
	}

	public function list_agama()
	{
		$query = $this->db->query("SELECT distinct(agama) FROM karyawan order by agama asc");
		return $query;
	}

	public function list_disc()
	{
		$query = $this->db->query("SELECT distinct(profile_disc) FROM karyawan");
		return $query;
	}

	public function list_disc_type()
	{
		$query = $this->db->query("SELECT distinct(profile_type) FROM karyawan");
		return $query;
	}

	// ################################################### ROLE ###################################################################
	public function role_view()
	{
		$query = $this->db->query("SELECT * from role where is_delete = '0'")->result();
		return $query;
	}

	public function role_pinsert($data)
	{
		$this->db->insert('role', $data);
	}

	public function role_linsert($data)
	{
		$this->db->insert('log_role', $data);
	}

	public function role_update($data, $id)
	{
		$this->db->where('recid_role', $id);
		$this->db->update('role', $data);
	}

	public function role_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from role where recid_role = '$recid'")->result();
		return $query;
	}

	// ################################################### BAGIAN ###################################################################
	public function bagian_view()
	{
		$query = $this->db->query("SELECT b.*, d.nama_department, d.dept_group, s.nama_struktur, da.nama_karyawan as atasan from bagian b left join struktur s on b.recid_struktur = s.recid_struktur left join department d on d.recid_department = b.recid_department left join karyawan da on da.recid_karyawan = b.pic_bagian where b.is_delete = '0' and b.indeks_hr != ''   order by indeks_hr asc")->result();
		return $query;
	}

	public function sub_bagian_view()
	{
		$query = $this->db->query("SELECT * from bagian_sub where is_delete = '0'")->result();
		return $query;
	}

	public function all_bagian()
	{
		$query = $this->db->query("SELECT b.*, d.nama_department, d.dept_group, s.nama_struktur, da.nama_karyawan as atasan from bagian b left join struktur s on b.recid_struktur = s.recid_struktur left join department d on d.recid_department = b.recid_department left join karyawan da on da.recid_karyawan = b.pic_bagian order by if(b.indeks_hr = '',1,0), is_delete, b.indeks_hr asc;")->result();
		return $query;
	}

	public function get_indeks_hr()
	{
		$query = $this->db->query("SELECT distinct(indeks_hr) from bagian b join karyawan k on k.recid_bag = b.recid_bag where k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and k.tc= '0' order by indeks_hr asc")->result();
		return $query;
	}

	public function bagian_by_role()
	{
		$query = $this->db->query("SELECT * from bagian b left join struktur on b.recid_struktur = struktur.recid_struktur where b.is_delete = '0' and b.recid_struktur != '0'  order by indeks_hr asc ")->result();
		return $query;
	}

	public function bagian_by_role_dept($bagian)
	{
		$query = $this->db->query("SELECT * from bagian b left join struktur on b.recid_struktur = struktur.recid_struktur join department d on d.recid_department = b.recid_department where b.is_delete = '0' and b.recid_struktur != '0' and d.nama_department = '$bagian' order by indeks_hr asc ")->result();
		return $query;
	}

	public function bagian_by_str($bagian)
	{
		$query = $this->db->query("SELECT * from bagian b left join struktur on b.recid_struktur = struktur.recid_struktur where b.is_delete = '0' and b.recid_struktur != '0' and b.recid_struktur = '$bagian' order by indeks_hr asc ")->result();
		return $query;
	}

	public function bagian_by_deptgroup($dept_group)
	{
		$query = $this->db->query("SELECT * from bagian b left join struktur on b.recid_struktur = struktur.recid_struktur join department d on d.recid_department = b.recid_department where b.is_delete = '0' and b.recid_struktur != '0' and d.dept_group = '$dept_group' order by indeks_hr asc ")->result();
		return $query;
	}

	public function bagian_by_karyawan($recid_karyawan)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag where k.recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function bagian_sales()
	{
		$query = $this->db->query("SELECT * from bagian b left join struktur on b.recid_struktur = struktur.recid_struktur where b.is_delete = '0' and b.recid_struktur != '0' and (b.recid_struktur = '20' or b.recid_struktur = '22') order by indeks_hr asc ")->result();
		return $query;
	}

	public function bagian_pinsert($data)
	{
		$this->db->insert('bagian', $data);
	}

	public function bagian_linsert($data)
	{
		$this->db->insert('log_bagian', $data);
	}

	public function bagian_update($data, $id)
	{
		$this->db->where('recid_bag', $id);
		$this->db->update('bagian', $data);
	}

	public function bagian_by_recid($recid_bag)
	{
		$query = $this->db->query("SELECT * FROM bagian left join struktur on bagian.recid_struktur = struktur.recid_struktur where recid_bag = '$recid_bag' and bagian.is_delete = 0")->result();
		return $query;
	}

	public function bagian_di_baros()
	{
		$query = $this->db->query("SELECT DISTINCT(k.recid_bag), b.indeks_hr FROM karyawan k join bagian b on b.recid_bag = k.recid_bag where k.penempatan = 'Baros' and k.sts_aktif = 'Aktif' and k.cci = 'Tidak';");
		return $query;
	}

	public function bagian_by_recid2($recid_bag)
	{
		$query = $this->db->query("SELECT b.*, s.* FROM bagian b left join struktur s on b.recid_struktur = s.recid_struktur where recid_bag = '$recid_bag' and b.is_delete = 0");
		return $query;
	}

	public function dept_by_divisi($divisi)
	{
		/*$query = $this->db->query("SELECT distinct d.nama_department From department d join bagian b on b.recid_department = d.recid_department where d.dept_group = '$divisi' and b.is_delete = '0' and recid_struktur != '0' ");
		return $query;*/
		$query = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where $divisi and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and j.tingkatan < 6 order by d.nama_department asc");

		// $query = $this->db->query("SELECT distinct d.nama_department From department d join bagian b on b.recid_department = d.recid_department where $divisi and b.is_delete = '0' and recid_struktur != '0' ");
		return $query;
	}

	public function dept_by_paygroup($paygroup)
	{

		$query = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0'  $paygroup  order by d.nama_department asc");
		return $query;
	}

	public function dept_by_paygroup2($paygroup, $fdept)
	{

		$query = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup' $fdept and j.tingkatan < 6 order by d.nama_department asc");
		return $query;
	}

	public function dept_by_divisi_my($divisi)
	{
		/*$query = $this->db->query("SELECT distinct d.nama_department From department d join bagian b on b.recid_department = d.recid_department where d.dept_group = '$divisi' and b.is_delete = '0' and recid_struktur != '0' ");
		return $query;*/
		$query = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where $divisi and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by d.nama_department asc");

		// $query = $this->db->query("SELECT distinct d.nama_department From department d join bagian b on b.recid_department = d.recid_department where $divisi and b.is_delete = '0' and recid_struktur != '0' ");
		return $query;
	}

	public function bagian_by_department($department, $divisi)
	{
		/*$query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag From bagian b join department d on b.recid_department = d.recid_department where d.nama_department = '$department' and d.dept_group = '$divisi' and b.is_delete = 0 order by indeks_hr asc");*/
		// $query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag From bagian b join department d on b.recid_department = d.recid_department where ($department) and ($divisi) and b.is_delete = 0 order by indeks_hr asc");
		$query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where ($department) and ($divisi) and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' order by b.indeks_hr asc");
		return $query;
	}

	public function bagian_by_department2($paygroup, $divisi)
	{

		$query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where ($paygroup) and ($divisi) and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and j.tingkatan < 6 order by b.indeks_hr asc");
		return $query;
	}

	public function bagian_by_department3($paygroup, $divisi, $fbagian)
	{

		$query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where ($paygroup) and ($divisi) $fbagian and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and j.tingkatan < 6 order by b.indeks_hr asc");
		return $query;
	}

	public function bagian_by_admin($bagian)
	{
		/*$query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag From bagian b join department d on b.recid_department = d.recid_department where d.nama_department = '$department' and d.dept_group = '$divisi' and b.is_delete = 0 order by indeks_hr asc");*/
		// $query = $this->db->query("SELECT distinct(b.indeks_hr), d.nama_department as department, b.recid_bag, b.nama_bag From bagian b join department d on b.recid_department = d.recid_department where ($department) and ($divisi) and b.is_delete = 0 order by indeks_hr asc");
		$query = $this->db->query("SELECT * from bagian b where $bagian");
		return $query;
	}

	public function bagian_by_pic_str($recid_karyawan)
	{
		$query = $this->db->query("SELECT * from bagian b join struktur s on s.recid_struktur = b.recid_struktur and s.pic_struktur = $recid_karyawan and b.is_delete = '0' order by indeks_hr;");
		return $query;
	}

	public function karyawan_by_bagian($bagian)
	{

		$query = $this->db->query("SELECT * From karyawan k left join bagian b on b.recid_bag = k.recid_bag join department d on b.recid_department = d.recid_department where ($bagian) and k.sts_aktif = 'Aktif' and k.spm='Tidak' and k.cci='Tidak' and k.tc='0' order by indeks_hr, nama_karyawan asc");
		return $query;
	}

	public function karyawanoffdown_by_bagian($bagian)
	{

		$query = $this->db->query("SELECT * From karyawan k left join bagian b on b.recid_bag = k.recid_bag join department d on b.recid_department = d.recid_department join jabatan j on j.recid_jbtn = k.recid_jbtn where ($bagian) and j.tingkatan < 6 and k.sts_aktif = 'Aktif' and k.spm='Tidak' and k.cci='Tidak' and k.tc='0' order by indeks_hr, nama_karyawan asc");
		return $query;
	}

	public function karyawan_spm($bagian)
	{

		$query = $this->db->query("SELECT * From karyawan k left join bagian b on b.recid_bag = k.recid_bag join department d on b.recid_department = d.recid_department where ($bagian) and k.sts_aktif = 'Aktif' and k.spm='Ya' and k.cci='Tidak' and k.tc='0' order by indeks_hr, nama_karyawan asc");
		return $query;
	}

	public function karyawan_by_bagian_active_my($bagian, $bulan, $tahun)
	{

		// $query = $this->db->query("SELECT * From karyawan k left join bagian b on b.recid_bag = k.recid_bag join department d on b.recid_department = d.recid_department join karir c on c.recid_karyawan = k.recid_karyawan where ($bagian) and k.sts_aktif = 'Aktif' and k.spm='Tidak' and k.cci='Tidak' and k.tc='0' and month(c.tgl_m_karir) = $bulan and year(c.tgl_m_karir) = $tahun  order by indeks_hr, nama_karyawan asc");
		$query = $this->db->query("SELECT k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan from karyawan k left join bagian b on b.recid_bag = k.recid_bag left join struktur s on s.recid_struktur = b.recid_struktur left join department d on d.recid_department = b.recid_department left join jabatan j on j.recid_jbtn = k.recid_jbtn left join karir c on c.recid_karyawan = k.recid_karyawan where ($bagian) and (((month(tgl_a_karir) <= '2' or month(tgl_m_karir >= $bulan)) and (year(tgl_a_karir) = '$tahun') or year(tgl_m_karir) = '2022') OR month(tgl_a_karir) <= 'month(current_date())' and year(tgl_a_karir) = '$tahun') group by k.recid_karyawan order by k.nama_karyawan, k.recid_karyawan asc");
		return $query;
	}

	public function dept_by_user($user)
	{
		$query = $this->db->query("SELECT * from bagian b join karyawan k on k.recid_bag = b.recid_bag where k.recid_karyawan = '$user'");
		return $query;
	}

	// 
	public function struktur_view()
	{
		$query = $this->db->query("SELECT s.*, k.nama_karyawan from struktur s left join karyawan k on k.recid_karyawan = s.pic_struktur where sis_delete = '0' order by nama_struktur asc")->result();
		return $query;
	}

	public function all_struktur()
	{
		$query = $this->db->query("SELECT s.*, k.nama_karyawan from struktur s left join karyawan k on k.recid_karyawan = s.pic_struktur order by nama_struktur asc")->result();
		return $query;
	}

	public function struktur_pinsert($data)
	{
		$this->db->insert('struktur', $data);
	}

	public function struktur_update($data, $id)
	{
		$this->db->where('recid_struktur', $id);
		$this->db->update('struktur', $data);
	}

	public function struktur_by_recid($recid_str)
	{
		$query = $this->db->query("SELECT * FROM struktur where recid_struktur = '$recid_str'")->result();
		return $query;
	}

	public function department_view()
	{
		$query = $this->db->query("SELECT d.*, k.nama_karyawan from department d left join karyawan k on k.recid_karyawan = d.pic_dept where is_delete = '0' ")->result();
		return $query;
	}

	public function all_department()
	{
		$query = $this->db->query("SELECT d.*, k.nama_karyawan from department d left join karyawan k on k.recid_karyawan = d.pic_dept ")->result();
		return $query;
	}

	public function dept_pinsert($data)
	{
		$this->db->insert('department', $data);
	}

	public function department_update($data, $id)
	{
		$this->db->where('recid_department', $id);
		$this->db->update('department', $data);
	}

	public function department_by_recid($recid_str)
	{
		$query = $this->db->query("SELECT * FROM department where recid_department = '$recid_str'")->result();
		return $query;
	}


	// ################################################### JABATAN ###################################################################
	public function jabatan_view()
	{
		$query = $this->db->query("SELECT * from jabatan where is_delete = '0'")->result();
		return $query;
	}

	public function all_jabatan()
	{
		$query = $this->db->query("SELECT * from jabatan")->result();
		return $query;
	}


	public function jabatan_pinsert($data)
	{
		$this->db->insert('jabatan', $data);
	}

	public function jabatan_linsert($data)
	{
		$this->db->insert('log_jbtn', $data);
	}

	public function jabatan_update($data, $id)
	{
		$this->db->where('recid_jbtn', $id);
		$this->db->update('jabatan', $data);
	}

	public function jabatan_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from jabatan where recid_jbtn = '$recid'")->result();
		return $query;
	}

	public function golongan_view()
	{
		$query = $this->db->query("SELECT * from golongan where is_delete = '0'");
		return $query;
	}

	public function all_golongan()
	{
		$query = $this->db->query("SELECT * from golongan");
		return $query;
	}

	public function golongan_pinsert($data)
	{
		$this->db->insert('golongan', $data);
	}

	public function golongan_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from golongan where recid_golongan = '$recid'")->result();
		return $query;
	}

	public function golongan_update($data, $id)
	{
		$this->db->where('recid_golongan', $id);
		$this->db->update('golongan', $data);
	}

	// ################################################### KARYAWAN ###################################################################
	public function karyawan_view()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, bs.sub_bag, j.*, d.*, ba.nama_karyawan as atasan2, g.* from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department  where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_view2()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1, g.* from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department  where k.sts_aktif='Aktif' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_chitose()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, g.* from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and SPM = 'Tidak' and k.cci = 'Tidak' order by recid_karyawan asc")->result();
		return $query;
	}

	public function karyawan_chitose_spm()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and k.cci = 'Tidak' order by k.nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_nontc_nonspm()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan  where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' and k.tc='0' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_offup()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and j.tingkatan >= 6 order by nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_offdown()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and j.tingkatan < 6 order by nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_offdown_dept($dept)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*,bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where d.recid_department = $dept and k.sts_aktif='Aktif' and j.tingkatan < 6 order by nama_karyawan asc")->result();
		return $query;
	}

	public function spm_view()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and k.spm = 'Ya' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function cci_view()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, g.*, ba.nama_karyawan as atasan2 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department left join golongan g on g.recid_golongan = k.recid_golongan where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Ya' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}
	//all data
	public function karyawan_views()
	{
		$query = $this->db->query("SELECT * from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join golongan g on g.recid_golongan = k.recid_golongan left join bagian_sub bs on bs.recid_subbag = k.recid_subbag order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function temp_karyawan_views()
	{
		$query = $this->db->query("SELECT * from temp_karyawan order by nik asc");
		return $query;
	}

	public function karyawan_view_by_id($id)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag  left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.recid_karyawan = $id");
		return $query;
	}

	public function karyawan_view_by_bagian($bagian)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag,  j.*, d.*, g.*,  ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (b.indeks_hr = $bagian) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc")->result();
		return $query;
	}

	public function karyawan_view_by_dept_group($dept_group)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*,  ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (d.dept_group = '$dept_group') order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function all_karyawan_view_by_bagian($bagian)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.cci = 'Tidak' and (b.indeks_hr = '$bagian') order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function karyawan_view_by_atasan($recid_karyawan)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function bagian_view_by_atasan($recid_karyawan)
	{
		$query = $this->db->query("SELECT distinct(b.recid_bag), b.recid_bag, b.indeks_hr from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function prd_view_by_atasan($recid_karyawan)
	{
		$query = $this->db->query("SELECT distinct(b.recid_bag), b.recid_bag, b.indeks_hr from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) or k.recid_karyawan = $recid_karyawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function prd_karyawan_by_atasan($recid_karyawan)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag,  j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and (sa.recid_karyawan = $recid_karyawan or ba.recid_karyawan = $recid_karyawan) or k.recid_karyawan = $recid_karyawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function temp_karyawan_view_by_id($id)
	{
		$query = $this->db->query("SELECT k.* from temp_karyawan k where recid_karyawan = $id")->result();
		return $query;
	}

	public function karyawan_anactive()
	{
		$query = $this->db->query("SELECT * from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join golongan g on g.recid_golongan = k.recid_golongan where sts_aktif != 'Aktif'")->result();
		return $query;
	}

	public function karyawan_ucopy($recid_karyawan, $crt_by, $crt_date)
	{
		$query = $this->db->query("INSERT INTO karyawan (crt_by, crt_date, nama_karyawan, tmp_lahir, tgl_lahir, jenkel, gol_darah, no_ktp, no_npwp, agama, pendidikan, jurusan, nik, tgl_m_kerja, tgl_a_kerja, recid_bag, recid_jbtn, no_jamsos, sts_nikah, alamat_ktp, alamat_skrg, sts_aktif, spm, tmp_toko, tmp_kota, foto, scan_bpjs_kes, scan_bpjs_tk, mdf_date, note)
			SELECT '$crt_by', '$crt_date', nama_karyawan, tmp_lahir, tgl_lahir, jenkel, gol_darah, no_ktp, no_npwp, agama, pendidikan, jurusan, '', '', '', '', '', no_jamsos, sts_nikah, alamat_ktp, alamat_skrg, 'Aktif', spm, tmp_toko, tmp_kota, foto, scan_bpjs_kes, scan_bpjs_tk, mdf_date, note 
			FROM karyawan
			WHERE recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function karyawan_pinsert($data)
	{
		$this->db->insert('karyawan', $data);
	}

	public function temp_karyawan_pinsert($data)
	{
		$this->db->insert('temp_karyawan', $data);
	}

	public function karyawan_update($data, $id)
	{
		$this->db->where('recid_karyawan', $id);
		$this->db->update('karyawan', $data);
	}

	public function temp_karyawan_pupdate($data, $id)
	{
		$this->db->where('recid_karyawan', $id);
		$this->db->update('temp_karyawan', $data);
	}

	public function temp_karyawan_delete($recid)
	{
		$this->db->query("DELETE from temp_karyawan where recid_karyawan = $recid");
	}

	public function karyawan_linsert($data)
	{
		$this->db->insert('log_karyawan', $data);
	}

	public function v_karyawan_bagian($bag)
	{
		$query = $this->db->query("SELECT * From karyawan where recid_bag = '$bag' and sts_aktif='Aktif'");
		return $query;
	}

	public function nik_karyawan($id)
	{
		$query = $this->db->query("SELECT nik from karyawan where recid_karyawan = '$id'")->result();
		return $query;
	}

	public function karyawan_by_recid($recid)
	{
		$query = $this->db->query("SELECT p.*, k.recid_bag as bag, k.recid_jbtn as jbtn from karyawan p left join karir k on p.recid_karyawan = k.recid_karyawan where  p.recid_karyawan = '$recid' group by p.recid_karyawan, k.recid_bag, k.recid_jbtn")->result();
		return $query;
	}

	public function karyawan_by_recid2($paygroup, $fdept, $fbagian, $fkaryawan)
	{
		$query = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join department d on d.recid_department = b.recid_department join struktur s on s.recid_struktur = b.recid_struktur where b.pay_group = '$paygroup' and d.nama_department = '$fdept' and b.indeks_hr='$fbagian' $fkaryawan and j.tingkatan < 6")->result();
		return $query;
	}

	public function karyawan_by_bagian2($paygroup, $fdept, $fbagian)
	{
		$query = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join department d on d.recid_department = b.recid_department join struktur s on s.recid_struktur = b.recid_struktur where b.pay_group = '$paygroup' and d.nama_department = '$fdept' $fbagian and j.tingkatan < 6 and sts_aktif = 'Aktif' order by j.indeks_jabatan, k.nama_karyawan")->result();
		return $query;
	}

	public function karyawan_current_karir($recid)
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, bs.sub_bag, d.nama_department, j.indeks_jabatan, sa.nama_karyawan as atasan1,sa.email_cint as email1, ba.nama_karyawan as atasan2, ba.email_cint as email2, g.* from karyawan k join bagian b on b.recid_bag = k.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag join jabatan j on j.recid_jbtn = k.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan join department d on d.recid_department = b.recid_department where k.recid_karyawan = $recid ")->result();
		return $query;
	}

	public function karyawan_detail($recid)
	{
		// $query = $this->db->query("SELECT p.*, k.sts_jbtn, k.recid_bag as bag, k.recid_jbtn as jbtn, k.sts_jbtn
		// from karyawan p
		// left join karir k on p.recid_karyawan = k.recid_karyawan where  p.recid_karyawan = '$recid' ")->result();
		$query = $this->db->query("SELECT p.*, j.sts_jabatan, j.tingkatan from karyawan p left join jabatan j on j.recid_jbtn = p.recid_jbtn where p.recid_karyawan = '$recid' ")->result();
		return $query;
	}

	public function karyawan_resume($recid)
	{
		$query = $this->db->query("SELECT p.*, k.sts_jbtn, k.recid_bag as bag, k.recid_jbtn as jbtn, k.sts_jbtn, b.nama_bag, j.nama_jbtn, g.*, bs.sub_bag
		from karyawan p
		join bagian b on b.recid_bag = p.recid_bag
		left join bagian_sub bs on bs.recid_subbag = k.recid_subbag
		join jabatan j on j.recid_jbtn = p.recid_jbtn
		join golongan g on g.recid_golongan = k.recid_golongan
		left join karir k on p.recid_karyawan = k.recid_karyawan where  p.recid_karyawan = '$recid' ")->result();
		return $query;
	}

	public function karyawan_by_nik($nik)
	{
		$query = $this->db->query("SELECT * from karyawan  where nik = '$nik'")->result();
		return $query;
	}

	public function karyawan_by_nik2($nik)
	{
		$query = $this->db->query("SELECT k.*, b.nama_bag, j.nama_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn  where nik = '$nik' and k.sts_aktif = 'Aktif' and k.spm='Tidak' and k.cci = 'Tidak'")->result();
		return $query;
	}

	public function karyawan_by_nik3($nik)
	{
		$query = $this->db->query("SELECT * from karyawan  where nik = '$nik'");
		return $query;
	}

	public function vemp_by_divcode($div_code)
	{
		$query = $this->db->query("SELECT * from karyawan  where recid_bag = '$div_code' and sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'")->result();
		return $query;
	}

	public function last_nik()
	{
		$query = $this->db->query("SELECT * FROM karyawan order by recid_karyawan desc limit 1");
		return $query;
	}

	public function last_nik2()
	{
		// $query = $this->db->query("SELECT MAX(CAST(SUBSTRING(nik, 9,3) AS UNSIGNED)) as nik FROM karyawan  where (SUBSTRING(nik, 1,4)) = (select  MAX(CAST(SUBSTRING(nik, 1,4) AS UNSIGNED)) from karyawan where spm = 'Tidak' and cci = 'Tidak')")->result();
		// return $query;

		$query = $this->db->query("select SUBSTRING(nik, 9,3) as nik from karyawan where nik != '' and spm = 'Tidak' and cci = 'Tidak' order by recid_karyawan desc limit 1;")->result();
		return $query;
	}

	public function last_nik3()
	{
		// $query = $this->db->query("SELECT MAX(CAST(SUBSTRING(nik, 10,3) AS UNSIGNED)) as nik FROM karyawan  where (SUBSTRING(nik, 1,5)) = (select  MAX(CAST(SUBSTRING(nik, 1,5) AS UNSIGNED)) from karyawan where spm = 'Ya')")->result();
		// return $query;
		$query = $this->db->query("select SUBSTRING(nik, 9,3) as nik from karyawan where nik != '' and spm = 'Ya' order by recid_karyawan desc limit 1;")->result;
		return $query;
	}

	public function last_hp_kosong()
	{
		$query = $this->db->query("SELECT * from karyawan where telp1 like '%111%' order by telp1 desc limit 1");
	}


	// ################################################### TUNJANGAN ###################################################################
	public function tunjangan_view()
	{
		$query = $this->db->query("SELECT distinct k.nama_karyawan, t.nama_tunjangan, t.recid_tunjangan, t.hub_keluarga, t.sts_tunjangan from tunjangan t, karyawan k  where k.recid_karyawan = t.recid_karyawan and tunjangan_delete = '0' and spm = 'Tidak' and k.cci = 'Tidak'")->result();
		return $query;
	}

	public function dinamis_tunjangan()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, t.* 
			from tunjangan t join karyawan k on k.recid_karyawan = t.recid_karyawan 
			join bagian b on b.recid_bag = k.recid_bag 
			join jabatan j on j.recid_jbtn = k.recid_jbtn 
			join struktur s on s.recid_struktur = b.recid_struktur 
			where tunjangan_delete = '0' and spm = 'Tidak' and k.cci = 'Tidak'")->result();
		return $query;
	}

	public function tunjangan_detail($recid)
	{
		$query = $this->db->query("SELECT t.*, k.*, t.pendidikan as panak from tunjangan t join karyawan k on t.recid_karyawan = k.recid_karyawan where t.recid_tunjangan = '$recid'")->result();
		return $query;
	}

	public function tunjangan_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from tunjangan where recid_tunjangan = '$recid'")->result();
		return $query;
	}

	public function tunjangan_history($nik)
	{
		$query = $this->db->query("SELECT * FROM `tunjangan` where recid_karyawan = '$nik' and tunjangan_delete = '0' order by hub_keluarga asc")->result();
		return $query;
	}

	public function jml_psg($nik)
	{
		$query = $this->db->query("SELECT count(t.recid_tunjangan) as pasang from tunjangan t, karyawan k where t.hub_keluarga = 'Pasangan' and t.sts_tunjangan = 'Yes' and tunjangan_delete = '0' and k.recid_karyawan = '$nik' and k.recid_karyawan = t.recid_karyawan")->result();
		return $query;
	}

	public function jml_anak($nik)
	{
		$query = $this->db->query("SELECT count(t.recid_tunjangan) as anak from tunjangan t, karyawan k where t.hub_keluarga = 'Anak' and t.sts_tunjangan = 'Yes' and tunjangan_delete = '0' and k.recid_karyawan = '$nik' and k.recid_karyawan = t.recid_karyawan")->result();
		return $query;
	}

	public function jml_psg2($nik)
	{
		$query = $this->db->query("SELECT * from tunjangan t, karyawan k where t.hub_keluarga = 'Pasangan' and tunjangan_delete = '0' and k.recid_karyawan = '$nik' and k.recid_karyawan = t.recid_karyawan");
		return $query;
	}

	public function jml_anak2($nik)
	{
		$query = $this->db->query("SELECT count(t.recid_tunjangan) as anak from tunjangan t, karyawan k where t.hub_keluarga = 'Anak' and tunjangan_delete = '0' and k.recid_karyawan = '$nik' and k.recid_karyawan = t.recid_karyawan")->result();
		return $query;
	}

	public function tunjangan_pinsert($data)
	{
		$this->db->insert('tunjangan', $data);
	}

	public function tunjangan_linsert($data)
	{
		$this->db->insert('log_tunjangan', $data);
	}

	public function tunjangan_update($data, $id)
	{
		$this->db->where('recid_tunjangan', $id);
		$this->db->update('tunjangan', $data);
	}

	// ################################################### KARIR ###################################################################
	public function karir_view()
	{
		$query = $this->db->query("SELECT * FROM `karir` k join karyawan p on k.recid_karyawan = p.recid_karyawan join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join legal l on k.recid_legal = l.recid_legal join golongan g on g.recid_golongan = k.recid_golongan where l.legal_delete = '0' and spm = 'Tidak' and p.cci = 'Tidak' ")->result();
		return $query;
	}

	public function karir_history($nik)
	{
		$query = $this->db->query("SELECT k.*, p.nama_karyawan, b.nama_bag, b.indeks_hr, j.nama_jbtn, j.indeks_jabatan, j.sts_jabatan, l.no_perjanjian, l.scan_perjanjian, g.nama_golongan, bs.sub_bag from karir k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join legal l on k.recid_legal = l.recid_legal join karyawan p on p.recid_karyawan = k.recid_karyawan left join golongan g on g.recid_golongan = k.recid_golongan left join bagian_sub bs on bs.recid_subbag = k.recid_subbag where k.recid_karyawan = '$nik' and legal_delete = '0' and (kategori != 'Training' and kategori != 'Sanksi')  order by tgl_m_karir desc")->result();
		return $query;
	}

	public function sanksi_history($nik)
	{
		$query = $this->db->query("SELECT k.*, p.nama_karyawan, b.nama_bag, b.indeks_hr, j.nama_jbtn, j.indeks_jabatan, j.sts_jabatan, l.no_perjanjian, l.scan_perjanjian, g.recid_golongan from karir k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join legal l on k.recid_legal = l.recid_legal join karyawan p on p.recid_karyawan = k.recid_karyawan left join golongan g on g.recid_golongan = k.recid_golongan where k.recid_karyawan = '$nik' and legal_delete = '0' and kategori = 'Sanksi' order by tgl_m_karir desc")->result();
		return $query;
	}

	public function karir_history2($nik)
	{
		$query = $this->db->query("SELECT k.*, b.nama_bag, b.indeks_hr, j.nama_jbtn, j.indeks_jabatan, j.sts_jabatan, l.no_perjanjian, l.scan_perjanjian from karir k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join legal l on k.recid_legal = l.recid_legal where recid_karyawan = '$nik' and legal_delete = '0' and (kategori != 'Training' and kategori != 'Sanksi')  order by tgl_m_karir desc");
		return $query;
	}

	public function sanksi_history2($nik)
	{
		$query = $this->db->query("SELECT k.*, b.nama_bag, b.indeks_hr, j.nama_jbtn, j.indeks_jabatan, j.sts_jabatan, l.no_perjanjian, l.scan_perjanjian from karir k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join legal l on k.recid_legal = l.recid_legal where recid_karyawan = '$nik' and legal_delete = '0' and kategori = 'Sanksi' order by tgl_m_karir desc");
		return $query;
	}

	public function training_history($nik)
	{
		$query = $this->db->query("SELECT t.*, k.*, p.nik, p.recid_karyawan, p.nama_karyawan, p.recid_bag, p.recid_jbtn, b.indeks_hr, j.indeks_jabatan, t.note as ket, tk.kompetensi, tp.sertifikat, tp.poin_plus
			from training t left join karir k on k.recid_legal = t.recid_legal 
			left join train_participant tp on tp.recid_training = t.recid_training
			left join train_kompetensi tk on tk.recid_komp = t.recid_komp
			left join karyawan p on k.recid_karyawan = p.recid_karyawan 
			left join bagian b on b.recid_bag = p.recid_bag 
			left join jabatan j on p.recid_jbtn = j.recid_jbtn 
			left join legal l on l.recid_legal = t.recid_legal
			where p.recid_karyawan = tp.recid_karyawan 
            and p.recid_karyawan = '$nik' order by t.no_form")->result();
		return $query;
	}

	public function training_history2($nik)
	{
		$query = $this->db->query("SELECT k.*, l.no_perjanjian, l.scan_perjanjian, t.* from karir k join legal l on k.recid_legal = l.recid_legal join training t on l.recid_legal = t.recid_legal where recid_karyawan = '$nik' order by recid_karir desc");
		return $query;
	}

	public function karirs_history($nik)
	{
		$query = $this->db->query("SELECT k.*, b.nama_bag, j.nama_jbtn from karir k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn where recid_karyawan = '$nik' order by tgl_m_karir desc")->result();
		return $query;
	}

	public function karir_pinsert($data)
	{
		$this->db->insert('karir', $data);
	}

	public function karir_linsert($data)
	{
		$this->db->insert('log_karir', $data);
	}

	public function karir_update($data, $id)
	{
		$this->db->where('recid_karir', $id);
		$this->db->update('karir', $data);
	}

	public function karir_update2($data, $id)
	{
		$this->db->where('recid_karir', $id);
		$this->db->update('karir', $data);
	}

	public function max_tgl($recid)
	{
		$query = $this->db->query("SELECT * From karir where recid_karyawan = '$recid' order by tgl_m_karir desc limit 1");
		return $query;
	}

	public function all_karir($recid)
	{
		$query = $this->db->query("SELECT * From karir where recid_karyawan = '$recid'");
		return $query;
	}

	public function karir_by_recid($recid)
	{
		$query = $this->db->query("SELECT k.*, p.*, k.bulanan as bulan from karir k left join karyawan p on k.recid_karyawan = p.recid_karyawan join legal l on k.recid_legal = l.recid_legal join jabatan j on j.recid_jbtn = p.recid_jbtn where recid_karir = '$recid'")->result();
		return $query;
	}

	public function karir($id)
	{
		$query = $this->db->query("SELECT * FROM `karir` WHERE recid_karir = '$id'");
		return $query;
	}

	public function karir_pcurrent($nik)
	{
		//result di controller
		$query = $this->db->query("SELECT * FROM `karir` WHERE recid_karyawan = '$nik'");
		return $query;
	}

	public function get_max_tgl($recid_karyawan)
	{
		$query = $this->db->query("SELECT * FROM karir where tgl_m_karir = (select max(tgl_m_karir) from karir where recid_karyawan = $recid_karyawan)");
		return $query;
	}

	public function karir_lcurrent($nik, $tgl)
	{
		//result di controller
		$query = $this->db->query("SELECT * FROM `karir` WHERE recid_karyawan = '$nik' and tgl_m_karir < '$tgl' order by tgl_m_karir desc limit 1");
		return $query;
	}

	public function karir_terdekat($recid_karyawan, $tgl)
	{
		$query = $this->db->query("SELECT * from karir where recid_karyawan = $recid_karyawan and tgl_m_karir < '$tgl' order by tgl_m_karir desc limit 1");
		return $query;
	}

	public function karir_terdekat_up($recid_karyawan, $tgl)
	{
		$query = $this->db->query("SELECT * from karir where recid_karyawan = $recid_karyawan and tgl_m_karir > '$tgl' order by tgl_m_karir limit 1");
		return $query;
	}

	public function karir_ucopy($recid_karir, $crt_by, $crt_date, $posisi_aktif)
	{
		$query = $this->db->query("INSERT INTO karir (nik, crt_by, crt_date, recid_legal, tgl_m_karir, tgl_a_karir, recid_bag, recid_jbtn, sts_jbtn, posisi_aktif, mdf_by, mdf_date, note)
			SELECT nik, '$crt_by', '$crt_date', recid_legal, tgl_m_karir, tgl_a_karir, recid_bag, recid_jbtn, sts_jbtn, '$posisi_aktif', mdf_by, mdf_date, note 
			FROM karir
			WHERE recid_karir = '$recid_karir'");
		return $query;
	}

	public function get_data($recid)
	{
		$query = $this->db->query("SELECT * from karyawan where recid_karyawan = '$recid'")->result();
		return $query;
	}

	public function expedisi_karir()
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where ((b.recid_bag = 45 and j.tingkatan = 8) or (b.recid_bag = 161 and j.tingkatan = 7)) and k.sts_aktif = 'aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and email_cint != '' ");
		return $query;
	}


	public function notif_karir()
	{
		$tgl = date('Y-m-d');
		// $tgl = "2022-10-20";
		$query = $this->db->query("SELECT p.nik, p.nama_karyawan, b.indeks_hr, b.recid_bag, j.indeks_jabatan, k.kategori, k.tgl_m_karir, g.nama_golongan FROM karir k
			join karyawan p on k.recid_karyawan = p.recid_karyawan
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			join golongan g on g.recid_golongan = k.recid_golongan
			where k.crt_date like '%" . $tgl . "%'");
		return $query;
	}

	public function cek_bag_cis($bagian)
	{
		$query = $this->db->query("SELECT * from bagian_cis where recid_bag = '$bagian'");
		return $query;
	}

	public function notif_karir_tgl($tgl)
	{
		$query = $this->db->query("SELECT p.nik, p.nama_karyawan, b.indeks_hr, j.indeks_jabatan, k.kategori, k.tgl_m_karir, l.scan_perjanjian, g.nama_golongan FROM karir k 
			join legal l on k.recid_legal = l.recid_legal
			join karyawan p on k.recid_karyawan = p.recid_karyawan
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where l.legal_delete = '0' and k.crt_date like '%" . $tgl . "%'");
		return $query;
	}

	public function report_sanksi()
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, ba.nama_karyawan as atasan2, kr.*, kr.note as keterangan
			from karyawan k 
			left join bagian b on k.recid_bag = b.recid_bag 
			left join jabatan j on k.recid_jbtn = j.recid_jbtn 
			left join struktur s on s.recid_struktur = b.recid_struktur 
			left join karyawan sa on s.pic_struktur = sa.recid_karyawan 
			left join karyawan ba on b.pic_bagian = ba.recid_karyawan  
			left join department d on d.recid_department = b.recid_department  
			left join karir kr on kr.recid_karyawan = k.recid_karyawan
			where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' 
			and kr.kategori = 'Sanksi'
			order by k.nama_karyawan asc");
		return $query;
	}

	public function report_sanksi_bagian($bagian)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, d.*, ba.nama_karyawan as atasan2, kr.*, kr.note as keterangan
			from karyawan k 
			left join bagian b on k.recid_bag = b.recid_bag 
			left join jabatan j on k.recid_jbtn = j.recid_jbtn 
			left join struktur s on s.recid_struktur = b.recid_struktur 
			left join karyawan sa on s.pic_struktur = sa.recid_karyawan 
			left join karyawan ba on b.pic_bagian = ba.recid_karyawan  
			left join department d on d.recid_department = b.recid_department  
			left join karir kr on kr.recid_karyawan = k.recid_karyawan
			where k.sts_aktif='Aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' 
			and kr.kategori = 'Sanksi' and b.indeks_hr = '$bagian'
			order by k.nama_karyawan asc");
		return $query;
	}
	// ################################################### LEGAL ###################################################################
	public function legal_view()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian != 'Karyawan' and legal_delete = '0'")->result();
		return $query;
	}

	public function perizinan_view()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian = 'Perizinan' and legal_delete = '0'")->result();
		return $query;
	}

	public function perjanjian_view()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian = 'Perjanjian' and legal_delete = '0'")->result();
		return $query;
	}

	public function hki_view()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian = 'HKI' and legal_delete = '0'")->result();
		return $query;
	}

	public function legal_views()
	{
		$query = $this->db->query("SELECT * from legal where legal_delete = '0'")->result();
		return $query;
	}

	public function legal_open()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian != 'Karyawan' and legal_delete = '0' and sts_legal != 'Closed'")->result();
		return $query;
	}

	public function perjanjian_open()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian = 'Perjanjian' and legal_delete = '0' and sts_legal != 'Closed'")->result();
		return $query;
	}

	public function perizinan_open()
	{
		$query = $this->db->query("SELECT * from legal where jenis_perjanjian = 'Perizinan' and legal_delete = '0' and sts_legal != 'Closed'")->result();
		return $query;
	}

	public function legal_pinsert($data)
	{
		$this->db->insert('legal', $data);
	}

	public function legal_linsert($data)
	{
		$this->db->insert('log_legal', $data);
	}

	public function legal_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from legal  where recid_legal = '$recid' ")->result();
		return $query;
	}

	public function legal_last()
	{
		$query = $this->db->query("SELECT recid_legal from legal order by recid_legal desc limit 1")->result();
		return $query;
	}

	public function legal_update($data, $id)
	{
		$this->db->where('recid_legal', $id);
		$this->db->update('legal', $data);
	}

	public function legal_lastid($no_perjanjian)
	{
		$query = $this->db->query("SELECT * from legal  where no_perjanjian = '$no_perjanjian' and ")->result();
		return $query;
	}

	public function legal_karir_update($data, $id)
	{
		$this->db->where('recid_legal', $id);
		$this->db->update('karir', $data);
	}

	// ################################################### ABSEN ###################################################################

	public function dash_absen1($bulan, $tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M
			FROM master_absen.hadir_hris h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where month(tanggal) = $bulan and year(tanggal) = $tahun
            group by month(tanggal);");
		return $query;
	}

	public function dash_absen1_filter($bulan, $tahun, $filter)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT count(k.recid_karyawan) as jml_karyawan,
			COUNT(IF(recid_jenisabsen = 2, 1, NULL)) AS SID,
			COUNT(IF(recid_jenisabsen = 3, 1, NULL)) AS C, 
			COUNT(IF(recid_jenisabsen = 5, 1, NULL)) AS P1, 
			COUNT(IF(recid_jenisabsen = 6, 1, NULL)) AS H1, 
			COUNT(IF(recid_jenisabsen = 11, 1, NULL)) AS M
			FROM master_absen.hadir_hris h 
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			where month(tanggal) = $bulan and year(tanggal) = $tahun and d.dept_group = '$filter'
            group by month(tanggal);");
		return $query;
	}

	public function rekap_dash1_absen($jenis_absen, $bulan, $tahun)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.tanggal, count(h.tanggal) as lama, g.nama_golongan
			FROM master_absen.hadir_hris h
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			join hris.golongan g on g.recid_golongan = k.recid_golongan
			where jenis = '$jenis_absen' and month(tanggal) = $bulan and year(tanggal) = $tahun group by k.recid_karyawan");
		return $query;
	}

	public function rekap_dash1_absen_filter($jenis_absen, $bulan, $tahun, $filter)
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT k.nik, k.nama_karyawan, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, h.tanggal, count(h.tanggal) as lama, g.nama_golongan
			FROM master_absen.hadir_hris h
			join jenis_absen j on j.recid_jenisabsen = h.status
			join hris.karyawan k on k.recid_karyawan = h.recid_karyawan
			join hris.bagian b on b.recid_bag = k.recid_bag 
			join hris.struktur s on s.recid_struktur = b.recid_struktur
			join hris.department d on d.recid_department = b.recid_department
			join hris.jabatan j on j.recid_jbtn = k.recid_jbtn
			join hris.golongan g on g.recid_golongan = k.recid_golongan
			where jenis = '$jenis_absen' and month(tanggal) = $bulan and year(tanggal) = $tahun and d.dept_group = '$filter' group by k.recid_karyawan");
		return $query;
	}
	/*public function absen_view()
	{
		$query = $this->db->query("SELECT * from mabsen join karyawan on mabsen.nik = karyawan.nik where tgl_work = CURRENT_DATE()")->result();
		return $query;
	}

	public function save_absen($data)
	{
		$this->db->insert('absen', $data);
	}

	public function save_mabsen($data)
	{
		$this->db->insert('mabsen', $data);
	}

	public function mabsen_linsert($data)
	{
		$this->db->insert('log_absen', $data);
	}

	public function absen_clear()
	{
		$query = $this->db->query("TRUNCATE absen");
		return $query;
	}

	public function absen_duo($nik, $tgl_work)
	{
		// echo "$nik - $tgl_work";
		$query = $this->db->query("SELECT * from mabsen where nik = '$nik' and tgl_work = '$tgl_work'");
		return $query;
	}

	public function mabsen_pupdate( $nik, $tgl_work,  $time_in, $time_out, $note)
	{
		// echo "$nik - $tgl_work <br>";
		$query = $this->db->query("update mabsen set time_in = '$time_in', time_out = '$time_out', note = '$note' where nik = '$nik' and tgl_work = '$tgl_work'");
		return $query;
	}

	public function mabsen_ppulangcepat( $nik, $tgl_work, $time_out, $jenis_absen, $note)
	{
		// echo "$nik - $tgl_work <br>";
		$query = $this->db->query("update mabsen set time_out = '$time_out', jenis_absen = '$jenis_absen', note = '$note' where nik = '$nik' and tgl_work = '$tgl_work'");
		return $query;
	}

	public function mabsen_pupdate2( $nik, $tgl_work, $jenis_absen, $note)
	{
		// echo "$nik - $tgl_work <br>";
		$query = $this->db->query("update mabsen set jenis_absen = '$jenis_absen', note = '$note' where nik = '$nik' and tgl_work = '$tgl_work'");
		return $query;
	}

	public function absen_by_tgwork($nik, $tgl_work)
	{
		$query = $this->db->query("SELECT * from mabsen m
			join karyawan k on m.nik =k.nik
			join bagian b on k.recid_bag = b.recid_bag
			where k.nik = '$nik' and tgl_work = '$tgl_work'")->result();
		return $query;
	}

	public function absen_by_tgwork2($nik, $tgl_work)
	{
		$query = $this->db->query("SELECT * from mabsen m
			join karyawan k on m.nik =k.nik
			join bagian b on k.recid_bag = b.recid_bag
			where k.nik = '$nik' and tgl_work = '$tgl_work'");
		return $query;
	}

	public function absen_by_recid($recid)
	{
		$query = $this->db->query("SELECT * from mabsen where recid_absen = '$recid'")->result();
		return $query;
	}

	public function absen_ganda($nik, $tgl)
	{
		$query = $this->db->query("SELECT * FROM mabsen where nik = '$nik' and tgl_work = '$tgl'");
		if ($query->num_rows() > 0) 
		{
			return 1; //-------------------- ada
		}else
		{
			return 0;
		}
	}*/

	// ################################################### TRAINING ###################################################################



	// ################################################### PELAMAR  ###################################################################
	public function pelamar_view()
	{
		$query = $this->db->query("SELECT *, p.crt_date as tgl_daftar from pelamar p left join kota k1 on k1.kota_id = p.dom_kota where nik = '' or nik is null order by p.crt_date desc")->result();
		return $query;
	}

	public function pelamar_view2()
	{
		$query = $this->db->query("SELECT * from pelamar p left join kota k1 on k1.kota_id = p.dom_kota where nik = '' or nik is null order by p.crt_date desc");
		return $query;
	}

	public function pelamar_view_periode($mulai, $sampai)
	{
		$query = $this->db->query("SELECT *, p.crt_date as tgl_regis from pelamar p left join kota k1 on k1.kota_id = p.dom_kota where p.crt_date between '$mulai' and '$sampai' and nik = '' or nik is null order by p.crt_date desc");
		return $query;
	}


	public function pelamar_jenkel()
	{
		$query = $this->db->query("SELECT distinct(pjenkel) from pelamar order by pjenkel desc");
		return $query;
	}

	public function pelamar_stskawin()
	{
		$query = $this->db->query("SELECT distinct(psts_kawin) from pelamar order by psts_kawin desc");
		return $query;
	}

	public function pelamar_pendidikan()
	{
		$query = $this->db->query("SELECT distinct(ppendidikan) from pelamar order by ppendidikan desc");
		return $query;
	}

	public function pelamar_agama()
	{
		$query = $this->db->query("SELECT distinct(pagama) from pelamar order by pagama desc");
		return $query;
	}

	public function pelamar_job()
	{
		$query = $this->db->query("SELECT distinct(kategori_posisi), recid_posisi from pelamar_katpos");
		return $query;
	}

	public function pelamar_domkota()
	{
		$query = $this->db->query("SELECT distinct(k.kota_name), k.kota_id from pelamar p join kota k on k.kota_id = p.dom_kota");
		return $query;
	}

	public function pelamar_by_loker($id)
	{
		$query = $this->db->query("SELECT * from pelamar_apply pa join pelamar p on p.recid_pelamar = pa.recid_pelamar where recid_recruitment = $id ")->result();
		return $query;
	}

	public function candidate_match($where)
	{
		$query = $this->db->query("SELECT * FROM pelamar p 
			left join pelamar_pengalaman pp on p.recid_pelamar = pp.recid_pelamar
			left join pelamar_katpos pk on pk.recid_posisi = pp.kategori_posisi
			left join kota k on k.kota_id = p.dom_kota
			where nik = '' and p.actived = '1' $where");
		return $query;
	}

	public function biodata_pelamar_foto($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar as a
			left join pelamar_upload as b
			on b.recid_pelamar = a.recid_pelamar
			where
			( b.recid_pelamar is not null or
			not exists (select recid_pelamar from pelamar_upload))
			and a.recid_pelamar = $id
			and b.jenis_berkas = 'Pas Foto'")->result();
		return $query;
	}

	public function pelamar_view_byrecid($recid)
	{
		$query = $this->db->query("SELECT *, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur  from pelamar where recid_pelamar = '$recid'")->result();
		return $query;
	}


	public function cek_foto($id)
	{
		$query = $this->db->query("SELECT * from pelamar_upload where recid_pelamar = '$id' and jenis_berkas = 'Pas Foto'");
		return $query;
	}

	public function cek_ktp($no_ktp)
	{
		$query = $this->db->query("SELECT * from pelamar where no_ktp = '$no_ktp'");
		return $query;
	}

	public function pelamar_insert($data)
	{
		$this->db->insert('pelamar', $data);
	}

	public function pelamar_apply_insert($data)
	{
		$this->db->insert('pelamar_apply', $data);
	}

	public function pelamar_pupdate($data, $id)
	{
		$this->db->where('recid_pelamar', $id);
		$this->db->update('pelamar', $data);
	}

	public function cek_generate($nama)
	{
		$query = $this->db->query("SELECT * from pelamar where nama_pelamar like '%$nama%' and (nik is null or nik = '')");
		return  $query;
	}

	public function keluarga($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar_kel 
			where
			recid_pelamar = $id");
		return $query;
	}

	public function pengalaman($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar_pengalaman 
			where 	recid_pelamar = $id 
			order by thn_mulai desc");
		return $query;
	}

	public function pernyataan($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar_pernyataan 
			where 	recid_pelamar = $id ");
		return $query;
	}

	public function berkas($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar_upload 
			where 	recid_pelamar = $id ");
		return $query;
	}

	public function ijazah($id)
	{
		$query = $this->db->query("SELECT *
			from pelamar_upload 
			where 	recid_pelamar = $id and jenis_berkas = 'Ijazah'");
		return $query;
	}

	public function pelamar_by_vacancy($recid_recruitment)
	{
		$query = $this->db->query("SELECT * from pelamar p LEFT join pelamar_apply ap on p.recid_pelamar = ap.recid_pelamar left join recruitment r on r.recid_recruitment = ap.recid_recruitment left join kota k1 on k1.kota_id = p.dom_kota where ap.recid_recruitment = $recid_recruitment and ( nik = '' or nik is null)");
		return $query->result();
	}

	public function pelamar_apply_to($recid_pelamar)
	{
		$query = $this->db->query("SELECT *, p.crt_by as tgl_regis from pelamar p 
			left join pelamar_apply ap on ap.recid_pelamar = p.recid_pelamar 
			left join recruitment r on r.recid_recruitment = ap.recid_recruitment
			where p.recid_pelamar = $recid_pelamar");
		return $query;
	}

	public function result_disc_pelamar()
	{
		$query = $this->db->query("SELECT DISTINCT d.*, p.recid_pelamar, p.nama_pelamar, p.no_ktp, p.pjenkel, p.profile_disc, p.pattern_type, p.profile_type FROM disc.disc d join pelamar p on p.recid_pelamar = d.recid_karyawan;");
		return $query;
	}

	public function result_disc_pelamar_periode($mulai, $akhir)
	{
		$query = $this->db->query("SELECT DISTINCT d.*, p.crt_date as tgl_regis, p.recid_pelamar, p.nama_pelamar, p.no_ktp, p.pjenkel, p.profile_disc, p.pattern_type, p.profile_type FROM disc.disc d join pelamar p on p.recid_pelamar = d.recid_karyawan and p.crt_date between '$mulai' and '$akhir';");
		return $query;
	}

	public function result_disc_periode($mulai, $akhir)
	{
		$query = $this->db->query("SELECT DISTINCT d.*, p.recid_pelamar, p.nama_pelamar, p.no_ktp, p.pjenkel, p.profile_disc, p.pattern_type, p.profile_type FROM disc.disc d join pelamar p on p.recid_pelamar = d.recid_karyawan where p.crt_date between '$mulai' and '$akhir';");
		return $query;
	}

	function disc_by_pelamar($id)
	{
		$query = $this->db->query("SELECT p.recid_pelamar, p.nama_pelamar, p.no_ktp, p.pjenkel, p.ptmp_lahir, p.tgl_lahir, p.profile_disc, p.pattern_type, p.profile_type, d.crt_date as tgl_test FROM disc.disc d join hris.pelamar p ON d.recid_karyawan = p.recid_pelamar  where d.recid_karyawan = $id group by p.recid_pelamar, p.nama_pelamar, p.no_ktp, p.pjenkel, p.ptmp_lahir, p.tgl_lahir, p.profile_disc, p.pattern_type, p.profile_type, d.crt_date");
		return $query;
	}

	function hitung_jawaban_tinggi($recid_karyawan, $opsi)
	{
		$query = $this->db->query("SELECT * FROM disc.disc where tinggi = '$opsi' and recid_karyawan = $recid_karyawan;");
		return $query;
	}

	function hitung_jawaban_rendah($recid_karyawan, $opsi)
	{
		$query = $this->db->query("SELECT * FROM disc.disc where rendah = '$opsi' and recid_karyawan = $recid_karyawan;");
		return $query;
	}

	function total_jawaban_rendah($recid_karyawan)
	{
		$query = $this->db->query("SELECT rendah FROM disc.disc where recid_karyawan = $recid_karyawan;");
		return $query;
	}

	function total_jawaban_tinggi($recid_karyawan)
	{
		$query = $this->db->query("SELECT tinggi FROM disc.disc where recid_karyawan = $recid_karyawan;");
		return $query;
	}

	function get_jawaban($id_karyawan)
	{
		$query = $this->db->query("SELECT * FROM disc d join soal s on where recid_karyawan = $id_karyawan;");
		return $query;
	}

	public function update_disc_pelamar($data, $id)
	{
		$this->db->where('recid_pelamar', $id);
		$this->db->update('pelamar', $data);
	}



	// ################################################### FPTK ###################################################################
	public function fptk_view()
	{
		$query = $this->db->query("SELECT * from fptk f join karyawan k on k.recid_karyawan = f.recid_karyawan")->result();
		return $query;
	}

	public function fptk_pinsert($data)
	{
		$this->db->insert('fptk', $data);
	}

	public function fptk_by_recid($recid)
	{
		$query = $this->db->query("SELECT f.*, k.nama_karyawan, f.note as ket, k.recid_jbtn, k.recid_bag, 
			bs.indeks_hr as nama_bags, js.indeks_jabatan as nama_jbtns, 
			b.indeks_hr as nama_bag, j.indeks_jabatan as nama_jbtn 
			from fptk f 
			join karyawan k on k.recid_karyawan = f.recid_karyawan
			join bagian bs on bs.recid_bag = f.recid_bags
			join jabatan js on js.recid_jbtn = f.recid_jbtns
			join bagian b on b.recid_bag = f.recid_bag
			join jabatan j on j.recid_jbtn = f.recid_jbtn
			where recid_fptk = '$recid'")->result();
		return $query;
	}

	public function fptk_pupdate($data, $id)
	{
		$this->db->where('recid_fptk', $id);
		$this->db->update('fptk', $data);
	}

	// ################################################### KFPTK ###################################################################
	public function kfptk_view()
	{
		$query = $this->db->query("SELECT f.*, k.*, p.nama_karyawan from kfptk k join fptk f on k.recid_fptk = f.recid_fptk join karyawan p on f.recid_karyawan = p.recid_karyawan")->result();
		return $query;
	}

	public function kfptk_recruitment()
	{
		$query = $this->db->query("SELECT f.*, k.*, p.nama_karyawan from kfptk k join fptk f on k.recid_fptk = f.recid_fptk join karyawan p on f.recid_karyawan = p.recid_karyawan where k.status_acc = 'Disetujui' and k.recruitment = '0'")->result();
		return $query;
	}

	public function kfptk_by_fptk($recid)
	{
		$query = $this->db->query("SELECT k.*, k.note as ket from kfptk k join fptk f on k.recid_fptk = f.recid_fptk where f.recid_fptk = '$recid'")->result();
		return $query;
	}

	public function recid_fptk($recid)
	{
		$query = $this->db->query("SELECT f.recid_fptk from kfptk k join fptk f on k.recid_fptk = f.recid_fptk where k.recid_kfptk = '$recid'")->result();
		return $query;
	}

	public function kfptk_pinsert($data)
	{
		$this->db->insert('kfptk', $data);
	}

	public function cek_relasi($recid)
	{
		$query = $this->db->query("SELECT * FROM kfptk where recid_fptk = '$recid'");
		if ($query->num_rows() > 0) {
			return 1; //-------------------- ada
		} else {
			return 0;
		}
	}

	public function kfptk_pupdate($data, $id)
	{
		$this->db->where('recid_kfptk', $id);
		$this->db->update('kfptk', $data);
	}

	public function kfptk_status($recid)
	{
		$this->db->query("UPDATE kfptk set recruitment = '1' where recid_kfptk = '$recid'");
	}

	public function kfptk_by_recid($recid)
	{
		$query = $this->db->query("SELECT f.*, k.nama_karyawan, f.note as ket, k.recid_jbtn, k.recid_bag, kf.*,
			bs.indeks_hr as nama_bags, js.indeks_jabatan as nama_jbtns, 
			b.indeks_hr as nama_bag, j.indeks_jabatan as nama_jbtn 
			from fptk f 
			join kfptk kf on kf.recid_fptk = f.recid_fptk
			join karyawan k on k.recid_karyawan = f.recid_karyawan
			join bagian bs on bs.recid_bag = f.recid_bags
			join jabatan js on js.recid_jbtn = f.recid_jbtns
			join bagian b on b.recid_bag = f.recid_bag
			join jabatan j on j.recid_jbtn = f.recid_jbtn
			where recid_kfptk = '$recid'")->result();
		return $query;
	}

	// ################################################### RECRUITMENT ###################################################################
	public function recruitment_view()
	{
		$query = $this->db->query("SELECT f.*, kf.*, r.*,
			b.indeks_hr as nama_bag, j.indeks_jabatan as nama_jbtn 
			from fptk f 
			join kfptk kf on kf.recid_fptk = f.recid_fptk
			join recruitment r on r.recid_kfptk = kf.recid_kfptk
			join bagian b on b.recid_bag = f.recid_bag
			join jabatan j on j.recid_jbtn = f.recid_jbtn")->result();
		return $query;
	}

	public function recruitment_pinsert($data)
	{
		$this->db->insert('recruitment', $data);
	}

	public function recruitment_by_recid($recid)
	{
		$query = $this->db->query("SELECT f.*, kf.*, r.*,
			k.nama_karyawan as pengaju,
			b.indeks_hr as nama_bag, j.indeks_jabatan as nama_jbtn 
			from fptk f 
			join kfptk kf on kf.recid_fptk = f.recid_fptk
			join recruitment r on r.recid_kfptk = kf.recid_kfptk
			join bagian b on b.recid_bag = f.recid_bag
			join jabatan j on j.recid_jbtn = f.recid_jbtn
            join karyawan k on k.recid_karyawan = f.recid_karyawan
			where r.recid_recruitment = '$recid'")->result();
		return $query;
	}

	public function recruitment_pupdate($data, $id)
	{
		$this->db->where('recid_recruitment', $id);
		$this->db->update('recruitment', $data);
	}

	public function detail_hire()
	{
		$query = $this->db->query(
			"SELECT f.tgl_pengajuan as tgl_fptk, f.jml_kebutuhan, f.tgl_efektif as efektif_by_fptk,
			r.judul_recruitment, r.recid_recruitment, 
			e.tgl_m_kerja, datediff(tgl_m_kerja, tgl_pengajuan) as leadtime,
			datediff(tgl_efektif, tgl_pengajuan) as leadtime_user
			from fptk f 
			join kfptk k on f.recid_fptk = k.recid_fptk
			join recruitment r on r.recid_kfptk = k.recid_kfptk
			join test t on t.recid_recruitment =  r.recid_recruitment
			join seleksi s on s.recid_test = t.recid_test
			join pelamar p on p.recid_pelamar = s.recid_pelamar
			join karyawan e on e.nik = p.nik
			where s.status = 'Terima'
			group by f.recid_fptk
			order by f.tgl_pengajuan desc"
		);
		return $query;
	}

	public function jumlah_hire()
	{
		$query = $this->db->query("SELECT count(p2.recid_pelamar) as jml_hire, r2.recid_recruitment from pelamar p2 join seleksi s2 on p2.recid_pelamar = s2.recid_pelamar join test t2 on t2.recid_test = s2.recid_test join recruitment r2 on r2.recid_recruitment = t2.recid_recruitment where s2.status = 'Terima' group by r2.recid_recruitment");
		return $query;
	}

	public function detail_hire_by_id($recid_recruitment)
	{
		$query = $this->db->query(
			"SELECT f.tgl_pengajuan as tgl_fptk, f.jml_kebutuhan, f.tgl_efektif as efektif_by_fptk,
			r.judul_recruitment, r.recid_recruitment, 
			e.tgl_m_kerja, e.nama_karyawan, datediff(tgl_m_kerja, tgl_pengajuan) as leadtime,
			datediff(tgl_efektif, tgl_pengajuan) as leadtime_user
			from fptk f 
			join kfptk k on f.recid_fptk = k.recid_fptk
			join recruitment r on r.recid_kfptk = k.recid_kfptk
			join test t on t.recid_recruitment =  r.recid_recruitment
			join seleksi s on s.recid_test = t.recid_test
			join pelamar p on p.recid_pelamar = s.recid_pelamar
			join karyawan e on e.nik = p.nik
			where s.status = 'Terima'
            and r.recid_recruitment = '$recid_recruitment'"
		);
		return $query;
	}

	public function recruitment_per_bulan($month, $year)
	{
		$query = $this->db->query("SELECT f.tgl_pengajuan as tgl_fptk, f.jml_kebutuhan, f.tgl_efektif as efektif_by_fptk,
			r.judul_recruitment, r.recid_recruitment, 
			e.tgl_m_kerja, datediff(tgl_m_kerja, tgl_pengajuan) as leadtime,
			datediff(tgl_efektif, tgl_pengajuan) as leadtime_user
			from fptk f 
			join kfptk k on f.recid_fptk = k.recid_fptk
			join recruitment r on r.recid_kfptk = k.recid_kfptk
			join test t on t.recid_recruitment =  r.recid_recruitment
			join seleksi s on s.recid_test = t.recid_test
			join pelamar p on p.recid_pelamar = s.recid_pelamar
			join karyawan e on e.nik = p.nik
			where s.status = 'Terima'
            and month(tgl_efektif) = '$month' and YEAr(tgl_efektif) = '$year'
			group by f.recid_fptk
			order by f.tgl_pengajuan desc");
		return $query;
	}

	public function leadtime_terpenuhi($month, $year)
	{
		$query = $this->db->query("SELECT f.tgl_pengajuan as tgl_fptk, f.jml_kebutuhan, f.tgl_efektif as efektif_by_fptk,
			r.judul_recruitment, r.recid_recruitment, 
			e.tgl_m_kerja, datediff(tgl_m_kerja, tgl_pengajuan) as leadtime,
			datediff(tgl_efektif, tgl_pengajuan) as leadtime_user
			from fptk f 
			join kfptk k on f.recid_fptk = k.recid_fptk
			join recruitment r on r.recid_kfptk = k.recid_kfptk
			join test t on t.recid_recruitment =  r.recid_recruitment
			join seleksi s on s.recid_test = t.recid_test
			join pelamar p on p.recid_pelamar = s.recid_pelamar
			join karyawan e on e.nik = p.nik
			where s.status = 'Terima'
            and month(tgl_m_kerja) = '$month' and YEAr(tgl_m_kerja) = '$year'
            and datediff(tgl_m_kerja, tgl_pengajuan) <= 49
			group by f.recid_fptk
			order by f.tgl_pengajuan desc");
		return $query;
	}
	// ################################################### TEST ###################################################################

	public function test_insert($data)
	{
		$this->db->insert('test', $data);
	}

	public function seleksi_insert($data)
	{
		$this->db->insert('seleksi', $data);
	}

	public function test_by_recruitment($recid)
	{
		$query = $this->db->query("SELECT * FROM test where recid_recruitment = '$recid'")->result();
		return $query;
	}

	public function lastest()
	{
		$query = $this->db->query("SELECT * from test order by recid_test desc limit 1")->result();
		return $query;
	}

	public function seleksi_update($data, $id)
	{
		$this->db->where('recid_seleksi', $id);
		$this->db->update('seleksi', $data);
	}

	public function seleksi_by_test($recid)
	{
		$query = $this->db->query("SELECT * FROM test t join seleksi s on s.recid_test = t.recid_test  where recid_seleksi = '$recid'")->result();
		return $query;
	}

	public function pengumuman_update($data, $id_pelamar, $recid_recruitment)
	{
		$this->db->where('recid_pelamar', $id_pelamar);
		$this->db->where('recid_recruitment', $recid_recruitment);
		$this->db->update('pelamar_apply', $data);
	}


	// ################################################### PENGAJUAN LEMBUR #########################################################

	public function plembur_view()
	{
		$query = $this->db->query("SELECT *, b.nama_bag from pengajuan_lembur rl join master_budget mbl on rl.recid_mbl = mbl.recid_mbl join bagian b on b.recid_bag = mbl.recid_bag ")->result();
		return $query;
	}

	public function plembur_by_recid($recid)
	{
		$query = $this->db->query("SELECT *, b.nama_bag from pengajuan_lembur rl join master_budget mbl on rl.recid_mbl = mbl.recid_mbl join bagian b on b.recid_bag = mbl.recid_bag where recid_plembur = '$recid'")->result();
		return $query;
	}

	public function plembur_by_id($recid)
	{
		$query = $this->db->query("SELECT * from pengajuan_lembur where recid_plembur = '$recid'")->result();
		return $query;
	}

	public function plembur_by_role($awal, $akhir)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag  where tgl_lembur between '$awal' and '$akhir' and  pl.recid_mbl != '0' and pl.plembur_delete = '0'")->result();
		return $query;
	}

	public function plembur_by_dept($dept, $awal, $akhir)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag join department d on d.recid_department = b.recid_department  where d.nama_department = '$dept' and tgl_lembur between '$awal' and '$akhir' and  pl.recid_mbl != '0' and pl.plembur_delete = '0'")->result();
		return $query;
	}

	public function plembur_by_str($str, $awal, $akhir)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag  where b.recid_struktur = '$str' and tgl_lembur between '$awal' and '$akhir' and  pl.recid_mbl != '0' and pl.plembur_delete = '0'")->result();
		return $query;
	}

	public function plembur_by_sales($awal, $akhir)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag  where (b.recid_struktur = '20' or b.recid_struktur = '22') and tgl_lembur between '$awal' and '$akhir' and  pl.recid_mbl != '0' and pl.plembur_delete = '0'")->result();
		return $query;
	}

	public function plembur_by_roles()
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null order by tgl_lembur desc")->result();
		return $query;
	}

	public function plembur_by_roles_str($bagian)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where b.recid_struktur = '$bagian' and pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null order by tgl_lembur desc")->result();
		return $query;
	}

	public function plembur_by_roles_sales()
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where (b.recid_struktur = '20' or b.recid_struktur = '22') and pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null order by tgl_lembur desc")->result();
		return $query;
	}

	public function plembur_by_roles_dept($bagian)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag join department d on d.recid_department = b.recid_department where d.nama_department = '$bagian' and pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null order by tgl_lembur desc")->result();
		return $query;
	}

	/*public function plembur_by_roles_str($str)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr, rl.recid_lembur FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag $bagian and recid_lembur is null order by tgl_lembur desc")->result();
		return $query;
	}*/

	public function cek_totalembur($kuartal, $recid_bag, $tahun)
	{
		$query = $this->db->query("SELECT sum(realisasi_jam) as total FROM realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl   where recid_bag = '$recid_bag' and kuartal = '$kuartal' and plembur_delete = '0' and tahun = '$tahun'")->result();
		return $query;
	}

	public function cek_kuartal($mbl, $tahun)
	{
		$query = $this->db->query("SELECT recid_bag, kuartal from master_budget where recid_mbl = '$mbl'");
		return $query;
	}

	public function cek_budget($mbl)
	{
		$query = $this->db->query("SELECT * from master_budget where recid_mbl = '$mbl'")->result();
		return $query;
	}

	public function plembur_pinsert($data)
	{
		$this->db->insert('pengajuan_lembur', $data);
	}

	public function dlembur_insert($data)
	{
		$this->db->insert('detail_lembur', $data);
	}

	public function dlembur_pupdate($data, $id)
	{
		$this->db->where('recid_detlembur', $id);
		$this->db->update('detail_lembur', $data);
	}

	public function dlembur_view($recid_plembur)
	{
		$query = $this->db->query("SELECT * from detail_lembur where recid_plembur = '$recid_plembur' and is_delete = '0'")->result();
		return $query;
	}


	public function dlembur_view2($recid_plembur)
	{
		$query = $this->db->query("SELECT * from detail_lembur where recid_plembur = '$recid_plembur'  and is_delete = '0'");
		return $query;
	}

	public function plembur_pupdate($data, $id)
	{
		$this->db->where('recid_plembur', $id);
		$this->db->update('pengajuan_lembur', $data);
	}

	public function plembur_crash()
	{
		$query = $this->db->query("SELECT * from pengajuan_lembur where recid_mbl = '0' and plembur_delete = '0'");
		return $query;
	}

	// ################################################### REALISASI ###################################################################

	public function realisasi_pinsert($data)
	{
		$this->db->insert('realisasi_lembur', $data);
	}

	public function belum_realisasi()
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function belum_realisasi_role()
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function belum_realisasi_dept($bagian)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag join department d on d.recid_department = b.recid_department where pl.recid_mbl != '0' and d.nama_department = '$bagian' and pl.plembur_delete = '0' and recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function belum_realisasi_str($bagian)
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and b.recid_struktur = '$bagian' and pl.plembur_delete = '0' and recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function belum_realisasi_sales()
	{
		$query = $this->db->query("SELECT pl.*, b.nama_bag, b.indeks_hr FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0' and (b.recid_struktur = '20' or b.recid_struktur = '22') order by tgl_lembur desc")->result();
		return $query;
	}

	public function jml_unreal($bagian)
	{
		$query = $this->db->query("SELECT count(pl.recid_plembur) as unreal FROM pengajuan_lembur pl LEFT JOIN realisasi_lembur rl ON pl.recid_plembur = rl.recid_plembur left join master_budget mb on pl.recid_mbl = mb.recid_mbl left join bagian b on b.recid_bag = mb.recid_bag where recid_struktur = ? and recid_lembur is null and plembur_delete = '0' and pl.recid_mbl != '0'", array($bagian))->result();
		return $query;
	}

	public function jml_unreal_payroll()
	{
		$query = $this->db->query("SELECT count(pl.recid_plembur) as unreal from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where realisasi_jam is null and  pl.recid_mbl != '0' and pl.plembur_delete = '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_view()
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where tgl_lembur >= now()-interval 3 month order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_by_recid($recid)
	{
		$query = $this->db->query("SELECT rl.*, pl.*, mb.recid_bag, b.nama_bag, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where recid_lembur = '$recid'")->result();
		return $query;
	}

	public function realisasi_by_role()
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, b.indeks_hr, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and tgl_lembur >= now()-interval 3 month order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_by_dept($bagian)
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, b.indeks_hr, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag join department d on d.recid_department = b.recid_department where pl.recid_mbl != '0' and pl.plembur_delete = '0' and d.nama_department = '$bagian' and tgl_lembur >= now()-interval 3 month order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_by_str($bagian)
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, b.indeks_hr, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and b.recid_struktur = '$bagian' and tgl_lembur >= now()-interval 3 month order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_by_sales()
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, b.indeks_hr, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where pl.recid_mbl != '0' and pl.plembur_delete = '0' and (b.recid_struktur = '20' or b.recid_struktur = '22') and tgl_lembur >= now()-interval 3 month order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_by_payroll()
	{
		$query = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.kategori, pl.keterangan, b.nama_bag, rl.note as notes from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where realisasi_jam is null and  pl.recid_mbl != '0' and pl.plembur_delete = '0' order by tgl_lembur desc")->result();
		return $query;
	}

	public function realisasi_pupdate($data, $id)
	{
		$this->db->where('recid_lembur', $id);
		$this->db->update('realisasi_lembur', $data);
	}

	public function get_plembur()
	{
		$query = $this->db->query("SELECT * From pengajuan_lembur order by recid_plembur desc limit 1")->result();
		return $query;
	}

	public function new_realisasi($tgl_awal, $tgl_akhir)
	{
		$query = $this->db->query("SELECT pl.recid_plembur, b.nama_bag, b.recid_bag, pl.tgl_lembur, pl.kategori, pl.alasan_over, pl.total_jam, rl.realisasi_jam, pl.jml_orang, rl.realisasi_orang, dl.pekerjaan, dl.target_kerja, dl.hasil FROM pengajuan_lembur pl join detail_lembur dl on pl.recid_plembur = dl.recid_plembur join realisasi_lembur rl on rl.recid_plembur = pl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where rl.realisasi_jam is not null and tgl_lembur between '$tgl_awal' and '$tgl_akhir'");
		return $query;
	}

	public function new_realisasi_header()
	{
		$query = $this->db->query("SELECT pl.recid_plembur, b.nama_bag, pl.tgl_lembur, pl.kategori, pl.alasan_over, pl.total_jam, rl.realisasi_jam, pl.jml_orang, rl.realisasi_orang FROM pengajuan_lembur pl join realisasi_lembur rl on rl.recid_plembur = pl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl join bagian b on b.recid_bag = mb.recid_bag where pl.recid_plembur = 5967");
		return $query;
	}

	// ################################################### REPORT ###################################################################
	// *********************************** KARYAWAN ***********************************
	public function jml_karyawan()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as total FROM karyawan  where sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc = '0'")->result();
		return $query;
	}

	public function jml_spm()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as total FROM karyawan  where sts_aktif='Aktif' and spm = 'Ya'")->result();
		return $query;
	}

	public function jml_cci()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as total FROM karyawan  where sts_aktif='Aktif' and cci = 'Ya'")->result();
		return $query;
	}

	public function jml_p()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as p FROM karyawan  where jenkel = 'Perempuan' and  sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc = '0'")->result();
		return $query;
	}

	public function gender($gender)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, g.*, bs.sub_bag, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2, d.nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag  left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.jenkel = '$gender' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc='0' order by nama_karyawan asc")->result();
		return $query;
	}

	public function gender_chart()
	{
		$query = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'perempuan' FROM (select nik, nama_karyawan, jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and spm = 'tidak' and cci = 'Tidak' and tc= '0') as dummy_table")->result();
		return $query;
	}

	public function gender_chart_by_dept($tipe)
	{
		$query = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'perempuan' FROM (select nik, nama_karyawan, jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'tidak' and cci = 'Tidak' and tc= '0' and d.dept_group='$tipe') as dummy_table")->result();
		return $query;
	}

	public function jml_l()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as l FROM karyawan where jenkel = 'Laki - laki' and  sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function blm_sk()
	{
		$query = $this->db->query("SELECT count(l.recid_legal) as blm_sk from legal l join karir k on l.recid_legal = k.recid_legal join karyawan p on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = p.recid_bag join jabatan j on j.recid_jbtn = p.recid_jbtn where jenis_perjanjian = 'Karyawan' and no_perjanjian = '' and legal_delete = '0' and sts_legal != 'Closed'  ")->result();
		return $query;
	}

	public function list_blm_sk()
	{
		$query = $this->db->query("SELECT l.*, b.indeks_hr, j.indeks_jabatan, b.recid_bag, j.recid_jbtn, p.* from legal l join karir k on l.recid_legal = k.recid_legal join karyawan p on k.recid_karyawan = p.recid_karyawan join bagian b on b.recid_bag = p.recid_bag join jabatan j on j.recid_jbtn = p.recid_jbtn where jenis_perjanjian = 'Karyawan' and no_perjanjian = '' and legal_delete = '0' and sts_legal != 'Closed' ")->result();
		return $query;
	}

	public function umur($nik)
	{
		$query = $this->db->query("SELECT nik, nama_karyawan, tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur FROM karyawan where recid_karyawan = '$nik'")->result();
		return $query;
	}

	public function usia($batas)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) " . $batas . " and sts_aktif = 'Aktif'")->result();
		return $query;
	}

	public function masker()
	{
		$query = $this->db->query("SELECT nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS masker FROM karyawan")->result();
		return $query;
	}

	public function range_umur()
	{
		$query = $this->db->query("SELECT COUNT(IF(umur <= 30,1,NULL)) AS 'kurang30', COUNT(IF(umur BETWEEN 31 and 40,1,NULL)) AS 'u3140', COUNT(IF(umur BETWEEN 41 and 50,1,NULL)) AS 'u4150', COUNT(IF(umur > 50,1,NULL)) AS 'lebih50' FROM (select nik, nama_karyawan, tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where sts_aktif = 'Aktif' and spm = 'Tidak' and k.cci = 'Tidak' and k.tc='0')as dummy_table")->result();
		return $query;
	}

	public function range_masker()
	{
		$query = $this->db->query("SELECT COUNT(IF(usia < 1,1,NULL)) AS 'kurang1', COUNT(IF(usia BETWEEN 1 and 5,1,NULL)) AS 'u5', COUNT(IF(usia BETWEEN 6 and 10,1,NULL)) AS 'u10', COUNT(IF(usia BETWEEN 11 and 20,1,NULL)) AS 'u20', COUNT(IF(usia > 20,1,NULL)) AS 'lebih20' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc = '0') as dummy_table")->result();
		return $query;
	}

	public function karyawan_by_status()
	{
		$query = $this->db->query("SELECT COUNT(IF(sts_jabatan = 'Kontrak' or sts_jabatan = 'Advisor' or sts_jabatan = 'Project',1,NULL)) AS 'kontrak', COUNT(IF(sts_jabatan = 'Tetap',1,NULL)) AS 'tetap' FROM (SELECT k.*, d.nama_department, b.indeks_hr, bs.sub_bag, j.indeks_jabatan, s.nama_struktur, j.sts_jabatan FROM karyawan k join jabatan j on j.recid_jbtn = k.recid_jbtn join bagian b on b.recid_bag = k.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag join department d on d.recid_department = b.recid_department join struktur s on s.recid_struktur = b.recid_struktur where  k.sts_aktif = 'aktif' and k.spm = 'tidak' and k.cci = 'Tidak' and k.tc='0') as dummy_table")->result();
		return $query;
	}

	public function karyawan_tetap()
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, bs.sub_bag, s.nama_struktur, d.nama_department, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan,  ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department  join jabatan j on j.recid_jbtn = k.recid_jbtn left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where sts_jabatan = 'Tetap' and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc= '0'")->result();
		return $query;
	}

	public function karyawan_kontrak()
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, bs.sub_bag, s.nama_struktur, d.nama_department, j.indeks_jabatan, j.sts_jabatan, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag  left join bagian_sub bs on bs.recid_subbag = k.recid_subbag join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan join department d on d.recid_department = b.recid_department  join jabatan j on j.recid_jbtn = k.recid_jbtn left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan where (sts_jabatan = 'kontrak' or sts_jabatan = 'Advisor' or sts_jabatan = 'Project') and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc= '0'")->result();
		return $query;
	}

	public function totkar_bag()
	{
		$query = $this->db->query("SELECT b.indeks_hr, d.nama_department, count(recid_karyawan) as total FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc='0' group by b.indeks_hr, d.nama_department")->result();
		return $query;
	}

	public function totkar_bag_male()
	{
		$query = $this->db->query("SELECT b.indeks_hr, d.nama_department, count(recid_karyawan) as total FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where jenkel = 'Laki - laki' and  sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0' group by b.indeks_hr, d.nama_department")->result();
		return $query;
	}

	public function totkar_bag_female()
	{
		$query = $this->db->query("SELECT b.indeks_hr, d.nama_department, count(recid_karyawan) as total FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where jenkel = 'Perempuan' and  sts_aktif='Aktif' and spm = 'Tidak'  and k.cci = 'Tidak' and tc=  '0' group by b.indeks_hr, d.nama_department")->result();
		return $query;
	}

	public function vaksin_covid()
	{
		$query = $this->db->query("SELECT COUNT(IF(vaksin_covid = '1',1,NULL)) AS 'sudah_v', COUNT(IF(vaksin_covid = '0',1,NULL)) AS 'belum_v' FROM (SELECT k.*, d.nama_department, b.indeks_hr, j.indeks_jabatan, s.nama_struktur, j.sts_jabatan FROM karyawan k join jabatan j on j.recid_jbtn = k.recid_jbtn join bagian b on b.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department join struktur s on s.recid_struktur = b.recid_struktur where  k.sts_aktif = 'aktif' and k.spm = 'tidak' and k.cci = 'Tidak' and k.tc= '0') as dummy_table")->result();
		return $query;
	}

	public function report_vaksin($status)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, sa.nama_karyawan as atasan1, b.*, j.*, g.*, d.*, ba.nama_karyawan as atasan2 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc='0' and k.vaksin_covid = '$status' order by nama_karyawan asc")->result();
		return $query;
	}

	public function r_hc($where)
	{
		$query = "SELECT p.*, p.recid_karyawan as rekar,
			k.*, TIMESTAMPDIFF(YEAR, p.tgl_lahir, CURDATE()) AS umur ,TIMESTAMPDIFF(YEAR, p.tgl_m_kerja, CURDATE()) AS masker, b.nama_bag, b.indeks_hr, j.indeks_jabatan, j.sts_jabatan, b.indeks_hr, bs.sub_bag, s.nama_struktur, d.nama_department, sa.nama_karyawan as atasan1,ba.nama_karyawan as atasan2, g.nama_golongan
			from karyawan p
			left join karir k on p.recid_karyawan = p.recid_karyawan
			left join bagian b on b.recid_bag = p.recid_bag
			left join bagian_sub bs on bs.recid_subbag = k.recid_subbag
			left join jabatan j on j.recid_jbtn = p.recid_jbtn
			left join tunjangan t on t.recid_karyawan = p.recid_karyawan
			left join department d on d.recid_department = b.recid_department
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join karyawan sa on s.pic_struktur = sa.recid_karyawan 
			left join karyawan ba on b.pic_bagian = ba.recid_karyawan 
			left join golongan g on g.recid_golongan = k.recid_golongan
			where p.nik != '' $where group by p.nik";
		$sql = $this->db->query($query)->result();
		return $sql;
	}

	public function department()
	{
		$query = $this->db->query("SELECT department From bagian where is_delete = '0' group by department order by department asc")->result();
		return $query;
	}

	public function direktorat()
	{
		$query = $this->db->query("SELECT * from department where is_delete = '0' order by recid_department asc")->result();
		return $query;
	}

	public function bagian_list()
	{
		$query = $this->db->query("SELECT * from bagian where is_delete = 0 and indeks_hr != '' order by pay_group, indeks_hr asc")->result();
		return $query;
	}

	public function paygroup()
	{
		$query = $this->db->query("SELECT DISTINCT(pay_group) from bagian where is_delete = 0 and indeks_hr != '' order by pay_group, indeks_hr asc;");
		return $query;
	}

	public function paygroup_emp()
	{
		$query = $this->db->query("SELECT distinct(pay_group) FROM `bagian` where pay_group != 'BOD' order by pay_group asc;");
		return $query;
	}

	public function bagian_by_paygroup($pg)
	{
		$query = $this->db->query("SELECT * FROM bagian where pay_group = '$pg' and indeks_hr != '' and is_delete = '0' order by indeks_hr asc");
		return $query;
	}

	public function bagian_by_paygroup2($pg, $dept, $bag)
	{
		$query = $this->db->query("SELECT * FROM bagian b join department d on b.recid_department = d.recid_department where  pay_group = '$pg' $dept $bag order by indeks_hr asc");
		return $query;
	}

	public function bagian_offdown($pg)
	{
		$query = $this->db->query("SELECT * FROM bagian where indeks_hr != '' and is_delete = '0' order by pay_group, indeks_hr asc");
		return $query;
	}

	public function offdown_by_bagian2($recid_bag)
	{
		$query = $this->db->query("SELECT * From karyawan k left join bagian b on b.recid_bag = k.recid_bag join department d on b.recid_department = d.recid_department join jabatan j on j.recid_jbtn = k.recid_jbtn where j.tingkatan < 6 and b.recid_bag = $recid_bag and k.sts_aktif = 'Aktif' and k.spm='Tidak' and k.cci='Tidak' and k.tc='0' order by indeks_hr, nama_karyawan asc");
		return $query;
	}

	public function bagian_by_dept($dept)
	{
		$query = $this->db->query("SELECT nama_bagian From bagian where department = '$dept'")->result();
		return $query;
	}


	public function masuk()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as masuk FROM karyawan WHERE MONTH(tgl_m_kerja) = MONTH(CURRENT_DATE()) AND YEAR(tgl_m_kerja) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}

	public function masuk2($bulan, $tahun)
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as masuk FROM karyawan WHERE MONTH(tgl_m_kerja) = '$bulan' AND YEAR(tgl_m_kerja) = '$tahun'")->result();
		return $query;
	}

	public function detail_masuk($bulan, $tahun)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department   where  MONTH(k.tgl_m_kerja) = '$bulan' AND YEAR(k.tgl_m_kerja) = '$tahun'order by nama_karyawan asc")->result();
		return $query;
	}

	public function mutasi_by_month($month, $year)
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, p.nama_karyawan, p.nik, p.recid_karyawan, d.nama_department, s.nama_struktur, g.nama_golongan from karir  k 
			join legal l on l.recid_legal = k.recid_legal
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			join karyawan p on p.recid_karyawan = k.recid_karyawan
			join department d on d.recid_department = b.recid_department
			join struktur s on s.recid_struktur = b.recid_struktur
			join golongan g on g.recid_golongan = k.recid_golongan
			where kategori ='mutasi' and month(tgl_m_karir) = '$month' and year(tgl_m_karir) = '$year' and legal_delete = '0'");
		return $query;
	}

	public function rotasi_by_month($month, $year)
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, p.nama_karyawan, p.nik, p.recid_karyawan, d.nama_department, s.nama_struktur, g.nama_golongan from karir  k 
			join legal l on l.recid_legal = k.recid_legal
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			join karyawan p on p.recid_karyawan = k.recid_karyawan
			left join golongan g on g.recid_golongan = p.recid_golongan
			join department d on d.recid_department = b.recid_department
			join struktur s on s.recid_struktur = b.recid_struktur
			where kategori ='rotasi' and month(tgl_m_karir) = '$month' and year(tgl_m_karir) = '$year' and legal_delete = '0'");
		return $query;
	}

	public function promosi_by_month($month, $year)
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, p.nama_karyawan, p.nik, p.recid_karyawan, d.nama_department, s.nama_struktur, g.nama_golongan from karir  k
		join legal l on l.recid_legal = k.recid_legal 
			join bagian b on b.recid_bag = k.recid_bag
			join jabatan j on j.recid_jbtn = k.recid_jbtn
			join karyawan p on p.recid_karyawan = k.recid_karyawan
			join department d on d.recid_department = b.recid_department
			join struktur s on s.recid_struktur = b.recid_struktur
			left join golongan g on g.recid_golongan = p.recid_golongan
			where kategori ='promosi' and month(tgl_m_karir) = '$month' and year(tgl_m_karir) = '$year' and legal_delete = '0'");
		return $query;
	}

	public function turnover_under1($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'Resign' or sts_aktif = 'Pensiun Dini') and (month(tgl_a_kerja) <= '$month' and year(tgl_a_kerja) = '$year') and datediff(tgl_a_kerja, tgl_m_kerja) <365");
		return $query;
	}

	public function turnover_under1_bulan($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'Resign' or sts_aktif = 'Pensiun Dini') and (month(tgl_a_kerja) = '$month' and year(tgl_a_kerja) = '$year') and datediff(tgl_a_kerja, tgl_m_kerja) <365");
		return $query;
	}

	public function turnover_norm($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'Resign' or sts_aktif = 'Pensiun Dini') and (month(tgl_a_kerja) <= '$month' and year(tgl_a_kerja) = '$year')");
		return $query;
	}

	public function turnover_norm_bulan($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'Resign' or sts_aktif = 'Pensiun Dini') and (month(tgl_a_kerja) = '$month' and year(tgl_a_kerja) = '$year')");
		return $query;
	}

	public function turnover_nonnorm($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'PHK' or sts_aktif = 'Pensiun') and (month(tgl_a_kerja) <= '$month' and year(tgl_a_kerja) = '$year')");
		return $query;
	}

	public function turnover_nonnorm_bulan($month, $year)
	{
		$query = $this->db->query("SELECT  k.*, b.indeks_hr, s.nama_struktur, d.nama_department, j.indeks_jabatan, g.nama_golongan from karyawan k
			left join bagian b on b.recid_bag = k.recid_bag
			left join struktur s on s.recid_struktur = b.recid_struktur
			left join department d on d.recid_department = b.recid_department
			left join jabatan j on j.recid_jbtn = k.recid_jbtn
			left join golongan g on g.recid_golongan = k.recid_golongan
			where (sts_aktif = 'PHK' or sts_aktif = 'Pensiun') and (month(tgl_a_kerja) = '$month' and year(tgl_a_kerja) = '$year')");
		return $query;
	}

	public function karyawan_awal_tahun($tahun)
	{
		$query = $this->db->query("SELECT * from karyawan 
			where
			tgl_m_kerja <= '$tahun' and
			(tgl_a_kerja > '$tahun' or tgl_a_kerja IS NULL or CAST(tgl_a_kerja AS CHAR) = '0000-00-00')
			and spm = 'Tidak' and cci = 'Tidak' and tc= '0'");
		return $query;
	}

	public function detail_usia($range)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*,d.*, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department  where  $range and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' order by nama_karyawan asc")->result();
		return $query;
	}

	public function detail_masker($range)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*,d.*, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1  from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where  $range and k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' order by nama_karyawan asc")->result();
		return $query;
	}

	public function keluar()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as keluar FROM karyawan WHERE MONTH(tgl_a_kerja) = MONTH(CURRENT_DATE()) AND YEAR(tgl_a_kerja) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}

	public function keluar2($bulan, $tahun)
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as keluar FROM karyawan WHERE MONTH(tgl_a_kerja) = '$bulan' AND YEAR(tgl_a_kerja) = '$tahun' and sts_aktif != 'Aktif'")->result();
		return $query;
	}

	public function detail_keluar($bulan, $tahun)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.nama_golongan, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department   where  MONTH(k.tgl_a_kerja) = '$bulan' AND YEAR(k.tgl_a_kerja) = '$tahun' and k.sts_aktif != 'Aktif' order by nama_karyawan asc")->result();
		return $query;
	}

	public function min_tahun()
	{
		$query = $this->db->query("SELECT min(YEAR(tgl_m_kerja)) as min_tahun from karyawan where tgl_m_kerja IS NOT NULL and CAST(tgl_m_kerja AS CHAR) != '0000-00-00' and CAST(tgl_m_kerja AS CHAR) != '1111-11-11'")->result();
		return $query;
	}

	public function max_tahun()
	{
		$query = $this->db->query("SELECT max(YEAR(tgl_a_kerja)) as max_tahun from karyawan ")->result();
		return $query;
	}

	public function karyawan_upah()
	{
		$query = $this->db->query("SELECT k.*, b.nama_bag, b.indeks_hr, d.nama_department, j.nama_jbtn, j.indeks_jabatan, g.nama_golongan, (gapok + t_jabatan + t_prestasi + t_jen_pek) as gaji, s.nama_struktur from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join golongan g on g.recid_golongan = k.recid_golongan left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'")->result();
		return $query;
	}

	public function blm_lengkap()
	{
		$query = $this->db->query("SELECT * FROM `karyawan` k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join department d on b.recid_department = d.recid_department  where ( nama_karyawan = '' or
			(tmp_lahir = '' or tmp_lahir = '-') or
			(tgl_lahir IS NULL or CAST(tgl_lahir AS CHAR) = '0000-00-00' ) or
			jenkel = '' OR
			(no_ktp = '' or no_ktp = '0' or no_ktp = '00') or 
			-- (no_npwp = '' or no_npwp = '0' or no_npwp = '00') or 
			agama = '' or
			pendidikan = '' OR
			(tgl_m_kerja IS NULL or CAST(tgl_m_kerja AS CHAR) = '0000-00-00' or CAST(tgl_m_kerja AS CHAR) = '1111-11-11') or
			k.recid_bag = '' OR
			k.recid_jbtn = '' or
			(no_bpjs_kes = '' or no_bpjs_kes = '0' or no_bpjs_kes = '00') or
			(no_bpjs_tk = '' or no_bpjs_tk = '0' or no_bpjs_tk = '00') OR
			sts_nikah = '' or
			(alamat_ktp = '' or alamat_ktp = '-') or
			alamat_skrg = '' OR
			(telp1 = '' or telp1 = '0') or
			(k.mdf_date IS NULL or CAST(k.mdf_date AS CHAR) = '0000-00-00')
		) and sts_aktif = 'Aktif'")->result();
		return $query;
	}

	public function jml_blm_lengkap()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as blm_lengkap FROM `karyawan` where ( nama_karyawan = '' or
			(tmp_lahir = '' or tmp_lahir = '-') or
			(tgl_lahir IS NULL or CAST(tgl_lahir AS CHAR) = '0000-00-00' )
			 or
			jenkel = '' OR
			(no_ktp = '' or no_ktp = '0' or no_ktp = '00' ) or 
			-- (no_npwp = '' or no_npwp = '0' or no_npwp = '00') or 
			agama = '' or
			pendidikan = '' OR
			(tgl_m_kerja IS NULL or CAST(tgl_m_kerja AS CHAR) = '0000-00-00' or CAST(tgl_m_kerja AS CHAR) = '1111-11-11') or
			recid_bag = '' OR
			recid_jbtn = '' or
			(no_bpjs_kes = '' or no_bpjs_kes = '0' or no_bpjs_kes = '00') or
			(no_bpjs_tk = '' or no_bpjs_tk = '0' or no_bpjs_tk = '00') OR
			sts_nikah = '' or
			(alamat_ktp = '' or alamat_ktp = '-') or
			alamat_skrg = '' OR
			(telp1 = '' or telp1 = '0') or
			(mdf_date IS NULL or CAST(mdf_date AS CHAR) = '0000-00-00')
		) and sts_aktif = 'Aktif'")->result();
		return $query;
	}

	// *********************************** RECRUITMENT ***********************************

	public function totopen_recruitment()
	{
		$query = $this->db->query("SELECT count(recid_recruitment) as rec FROM recruitment where status = 'Open'")->result();
		return $query;
	}

	public function open_recruitment()
	{
		$query = $this->db->query("SELECT f.*, kf.*, r.*,
			b.indeks_hr as nama_bag, j.indeks_jabatan as nama_jbtn 
			from fptk f 
			join kfptk kf on kf.recid_fptk = f.recid_fptk
			join recruitment r on r.recid_kfptk = kf.recid_kfptk
			join bagian b on b.recid_bag = f.recid_bag
			join jabatan j on j.recid_jbtn = f.recid_jbtn
			where r.status = 'Open'")->result();
		return $query;
	}

	public function chart_info()
	{
		$query = $this->db->query("SELECT COUNT(IF(info_dari = 'Online',1,NULL)) AS 'Online', COUNT(IF(info_dari = 'Offline',1,NULL)) AS 'Offline' FROM (select p.recid_pelamar, p.nama_pelamar, pp.info_dari from pelamar p join pelamar_pernyataan pp on pp.recid_pelamar = p.recid_pelamar) as dummy_table");
		return $query;
	}


	public function pendidikan_kandidat()
	{
		$query = $this->db->query("SELECT COUNT(IF(ppendidikan = 'SD',1,NULL)) AS 'SD', COUNT(IF(ppendidikan = 'SMP',1,NULL)) AS 'SMP', COUNT(IF(ppendidikan = 'SMA',1,NULL)) AS 'SMA', COUNT(IF(ppendidikan = 'D3',1,NULL)) AS 'D3', COUNT(IF(ppendidikan = 'S1',1,NULL)) AS 'S1', COUNT(IF(ppendidikan = 'S2',1,NULL)) AS 'S2' FROM (SELECT recid_pelamar, nama_pelamar, ppendidikan FROM pelamar where year(crt_date) = Year(CURRENT_DATE))as dummy_table");
		return $query;
	}

	public function source_info($source)
	{
		$query = $this->db->query("SELECT p.*, pp.info_dari FROM pelamar p join pelamar_pernyataan pp on pp.recid_pelamar = p.recid_pelamar WHERE info_dari = '$source' and Year(p.crt_date) = Year(CURRENT_DATE)");
		return $query;
	}

	public function kandidat_by_pendidikan($pend)
	{
		$query = $this->db->query("SELECT p.* FROM pelamar p WHERE ppendidikan = '$pend' and Year(p.crt_date) = Year(CURRENT_DATE)");
		return $query;
	}



	// *********************************** TRAINING ***********************************

	public function total_training()
	{
		$query = $this->db->query("SELECT count(recid_training) as train FROM training")->result();
		return $query;
	}

	// *********************************** LEGAL ***********************************
	public function jml_legal()
	{
		$query = $this->db->query("SELECT count(recid_legal) as legal from legal where sts_legal != 'Closed' and legal_delete = '0' and jenis_perjanjian != 'Karyawan' ")->result();
		return $query;
	}

	public function totexp_legal()
	{
		// $query = $this->db->query("SELECT count(recid_legal) as exp  FROM legal where tgl_a_legal != '0000-00-00' and tgl_a_legal < CURRENT_DATE and sts_legal != 'Closed' and legal_delete = '0' and jenis_perjanjian != 'Karyawan'")->result();
		// return $query;
		$query = $this->db->query("SELECT count(recid_legal) as exp  FROM legal where tgl_a_legal != null and tgl_a_legal < CURRENT_DATE and sts_legal != 'Closed' and legal_delete = '0' and jenis_perjanjian != 'Karyawan'")->result();
		return $query;
	}

	public function exp_legal()
	{
		$query = $this->db->query("SELECT * FROM legal where tgl_a_legal IS NOT NULL and CAST(tgl_a_legal AS CHAR) != '0000-00-00' and tgl_a_legal < CURRENT_DATE and sts_legal != 'Closed' and jenis_perjanjian != 'Karyawan' and legal_delete = '0' ")->result();
		return $query;
	}

	public function totjanji_legal()
	{
		$query = $this->db->query("SELECT count(recid_legal) as janji from legal where jenis_perjanjian = 'Perjanjian' and sts_legal != 'Closed' and legal_delete = '0'")->result();
		return $query;
	}

	public function totizin_legal()
	{
		$query = $this->db->query("SELECT count(recid_legal) as izin from legal where jenis_perjanjian = 'Perizinan' and sts_legal != 'Closed' and legal_delete = '0'")->result();
		return $query;
	}

	public function notif_today($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal = CURRENT_DATE and sts_legal !='Closed' and legal_delete = '0' $kategori");
		return $query;
	}

	public function notif_sehari($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 1 DAY) and sts_legal !='Closed' and legal_delete = '0'  $kategori");
		return $query;
	}

	public function notif_tigahari($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 3 DAY) and sts_legal !='Closed' and legal_delete = '0'  $kategori");
		return $query;
	}

	public function notif_seminggu($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 1 Week) and sts_legal !='Closed' and legal_delete = '0' $kategori");
		return $query;
	}

	public function notif_sebulan($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 1 Month) and sts_legal !='Closed' and legal_delete = '0' $kategori");
		return $query;
	}

	public function notif_empatlima($kategori)
	{
		$query = $this->db->query("SELECT * FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 45 DAY) and sts_legal !='Closed' and legal_delete = '0' $kategori");
		return $query;
	}

	public function notif_enampuluh($kategori)
	{
		$query = $this->db->query("SELECT l.*, p.nama_karyawan FROM legal l left join karir k on k.recid_legal = l.recid_legal left join karyawan p on p.recid_karyawan = k.recid_karyawan where tgl_a_legal between CURRENT_DATE and DATE_ADD(CURDATE(), INTERVAL 2 Month) and sts_legal !='Closed' and legal_delete = '0' $kategori");
		return $query;
	}

	public function legal_jenis($awal, $akhir, $jenis)
	{
		$query = $this->db->query("SELECT * FROM legal where   tgl_m_legal between '$awal' and '$akhir' and sts_legal = '$jenis' and legal_delete = '0'");
		return $query;
	}

	// *********************************** PENDIDIKAN ***********************************
	public function sd()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as sd FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'SD' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function smp()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as smp FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'SMP' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function sma()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as sma FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'SMA' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function d3()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as d3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'D3' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function s1()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as s1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'S1' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function s2()
	{
		$query = $this->db->query("SELECT count(recid_karyawan) as s2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'S2' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0'")->result();
		return $query;
	}

	public function pendidikan($pendidikan)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, bs.sub_bag, j.*, d.*, g.*, ba.nama_karyawan as atasan2, sa.nama_karyawan as atasan1 from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.pendidikan = '$pendidikan' order by nama_karyawan asc")->result();
		return $query;
	}

	// *********************************** ABSEN ***********************************
	public function department_semua()
	{
		$query = $this->db->query("SELECT distinct(d.nama_department) as department from karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' order by d.nama_department asc")->result();
		return $query;
	}

	public function bagian_semua()
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak' order by d.nama_department,b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_department($dept)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' order by d.nama_department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_struktur($str)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where b.recid_struktur ='$str'  and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' order by d.nama_department, b.nama_bag asc")->result();
		return $query;
	}

	public function bagian_bagian($bag)
	{
		$query = $this->db->query("SELECT distinct(k.recid_bag) as recid_bag, b.nama_bag, b.shift from karyawan k join bagian b on k.recid_bag = b.recid_bag join department d on d.recid_department = b.recid_department where b.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' order by d.nama_department, b.nama_bag asc")->result();
		return $query;
	}


	// public function allabsen_semua()
	// {
	// 	$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.recid_bag ='24' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.nama_bag asc")->result();
	// 	return $query;
	// }

	public function allabsen_semua()
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' order by b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_department($dept)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_struktur($str)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allabsen_bagian($bag)
	{
		$query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag where k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allaccess_semua()
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag  and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' group by a.ssn, a.attdate, k.nama_karyawan, b.nama_bag")->result();
		return $query;
	}

	public function allaccess_struktur($str)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag  where b.recid_struktur = '$str' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' group by a.ssn, a.attdate, k.nama_karyawan, b.nama_bag")->result();
		return $query;
	}

	public function allaccess_bagian($bag)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag  where k.recid_bag = '$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' group by a.ssn, a.attdate, k.nama_karyawan, b.nama_bag")->result();
		return $query;
	}

	public function allaccess_department($dept)
	{
		$query = $this->db->query("SELECT a.ssn, a.attdate, k.nama_karyawan, b.nama_bag FROM master_absen.attandance a join hris.karyawan k on a.ssn = k.nik join hris.bagian b on b.recid_bag = k.recid_bag where department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak' group by a.ssn, a.attdate, k.nama_karyawan, b.nama_bag")->result();
		return $query;
	}

	public function allmangkir($date_work)
	{
		$query = $this->db->query("SELECT m.* from hris.karyawan k join hris.bagian b on k.recid_bag = b.recid_bag join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_department($dept, $date_work)
	{
		$query = $this->db->query("SELECT m.* from hris.karyawan k join hris.bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_bagian($bag, $date_work)
	{
		$query = $this->db->query("SELECT m.* from karyawan k join bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function allmangkir_struktur($str, $date_work)
	{
		$query = $this->db->query("SELECT m.* from karyawan k join bagian b on k.recid_bag = b.recid_bag 
						join master_absen.mangkir m on k.nik = m.nik where m.tanggal = '$date_work'
						and b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'  order by b.nama_bag asc")->result();
		return $query;
	}

	public function notfull_semua($sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.*, k.nama_karyawan
			from master_absen.absen a
			join hris.karyawan k on a.nik = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where (a.DATE_WORK between '$sejak' and '$sampai') and (a.TIME_IN = '' or a.TIME_OUT = '')
			and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by DATE_WORK asc")->result();
		return $query;
	}

	public function notfull_department($dept, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.*, k.nama_karyawan
			from master_absen.absen a
			join hris.karyawan k on a.nik = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where (a.DATE_WORK between '$sejak' and '$sampai') and (a.TIME_IN = '' or a.TIME_OUT = '')
			and department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by DATE_WORK asc")->result();
		return $query;
	}

	public function notfull_bagian($bag, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.*, k.nama_karyawan
			from master_absen.absen a
			join hris.karyawan k on a.nik = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where (a.DATE_WORK between '$sejak' and '$sampai') and (a.TIME_IN = '' or a.TIME_OUT = '')
			and k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by DATE_WORK asc")->result();
		return $query;
	}

	public function notfull_struktur($str, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.*, k.nama_karyawan
			from master_absen.absen a
			join hris.karyawan k on a.nik = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where (a.DATE_WORK between '$sejak' and '$sampai') and (a.TIME_IN = '' or a.TIME_OUT = '')
			and b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by DATE_WORK asc")->result();
		return $query;
	}

	public function notfulla_semua($sejak, $sampai)
	{
		$query = $this->db->query("SELECT  a.ATTDATE as DATE_WORK, a.SSN as NIK, a.CHECKIN1 as TIME_IN, a.CHECKOUT1 as TIME_OUT, k.nama_karyawan
			from master_absen.attandance a
			join hris.karyawan k on a.ssn = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where ((DATE_FORMAT(STR_TO_DATE(ATTDATE, '%d/%m/%Y'), '%Y-%m-%d')) between '$sejak' and '$sampai')
            and (a.CHECKIN1 = '' or a.CHECKOUT1 = '' )
			and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by ATTDATE asc")->result();
		return $query;
	}

	public function notfulla_department($dept, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.ATTDATE as DATE_WORK, a.SSN as NIK, a.CHECKIN1 as TIME_IN, a.CHECKOUT1 as TIME_OUT, k.nama_karyawan
			from master_absen.attandance a
			join hris.karyawan k on a.ssn = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where((DATE_FORMAT(STR_TO_DATE(ATTDATE, '%d/%m/%Y'), '%Y-%m-%d')) between '$sejak' and '$sampai')
			and (a.CHECKIN1 = '' or a.CHECKOUT1 = '' )
			and department='$dept' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by ATTDATE asc")->result();
		return $query;
	}

	public function notfulla_bagian($bag, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.ATTDATE as DATE_WORK, a.SSN as NIK, a.CHECKIN1 as TIME_IN, a.CHECKOUT1 as TIME_OUT, k.nama_karyawan
			from master_absen.attandance a
			join hris.karyawan k on a.ssn = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where((DATE_FORMAT(STR_TO_DATE(ATTDATE, '%d/%m/%Y'), '%Y-%m-%d')) between '$sejak' and '$sampai')
			and (a.CHECKIN1 = '' or a.CHECKOUT1 = '' )
			and k.recid_bag ='$bag' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by ATTDATE asc")->result();
		return $query;
	}

	public function notfulla_struktur($str, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT a.ATTDATE as DATE_WORK, a.SSN as NIK, a.CHECKIN1 as TIME_IN, a.CHECKOUT1 as TIME_OUT, k.nama_karyawan
			from master_absen.attandance a
			join hris.karyawan k on a.ssn = k.nik
			join hris.bagian b on k.recid_bag = b.recid_bag
			where((DATE_FORMAT(STR_TO_DATE(ATTDATE, '%d/%m/%Y'), '%Y-%m-%d')) between '$sejak' and '$sampai')
			and (a.CHECKIN1 = '' or a.CHECKOUT1 = '' )
			and b.recid_struktur ='$str' and k.sts_aktif='Aktif' and spm = 'Tidak' and k.cci = 'Tidak'
			order by ATTDATE asc")->result();
		return $query;
	}

	public function jmangkir_all($sejak, $sampai)
	{
		$query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL BETWEEN '$sejak' and '$sampai' and k.sts_aktif = 'Aktif' Group by k.nik, k.nama_karyawan, b.nama_bag");
		return $query;
	}

	public function jmangkir_dept($dept, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and department = '$dept' and k.sts_aktif = 'Aktif' Group by k.nik, k.nama_karyawan, b.nama_bag");
		return $query;
	}

	public function jmangkir_str($str, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and recid_struktur = '$str' and k.sts_aktif = 'Aktif' Group by k.nik, k.nama_karyawan, b.nama_bag");
		return $query;
	}

	public function jmangkir_bag($bag, $sejak, $sampai)
	{
		$query = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and b.recid_bag = '$bag' and k.sts_aktif = 'Aktif' Group by k.nik, k.nama_karyawan, b.nama_bag");
		return $query;
	}

	public function telat()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT count(recid_absen) as telat from hadir_hris h join hris.karyawan k on h.recid_karyawan = k.recid_karyawan where jam_masuk > '07:30' and  MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}

	public function list_telat()
	{
		$db2 = $this->load->database('absen', TRUE);
		$query = $db2->query("SELECT *  from hadir_hris h join hris.karyawan k on h.recid_karyawan = k.recid_karyawan join bagian on karyawan.recid_bag = bagian.recid_bag where time_in > '07:30' and  MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}

	/*	public function telat()
	{
		$query = $this->db->query("SELECT count(m.NIK) as telat from master_absen.absen m join karyawan k on m.nik = k.nik where m.TIME_IN > '07:30' and  MONTH(DATE_WORK) = MONTH(CURRENT_DATE()) AND YEAR(DATE_WORK) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}

	public function list_telat()
	{
		$query = $this->db->query("SELECT *  from master_absen.absen m join karyawan k on m.NIK = k.nik join bagian b on k.recid_bag = b.recid_bag where TIME_IN > '07:30' and  MONTH(DATE_WORK) = MONTH(CURRENT_DATE()) AND YEAR(DATE_WORK) = YEAR(CURRENT_DATE())")->result();
		return $query;
	}*/

	public function raw_absen($tgl)
	{
		$query = $this->db->query("SELECT * from master_absen.absen a join hris.karyawan k on k.nik = a.nik join hris.bagian b on k.recid_bag = b.recid_bag where  date_work = '$tgl'");
		return $query;
	}

	public function raw_attandance($tgl)
	{
		$query = $this->db->query("SELECT * from master_absen.attandance a join hris.karyawan k on k.nik = a.SSN join hris.bagian b on k.recid_bag = b.recid_bag where  ATTDATE = '$tgl'");
		return $query;
	}

	public function absen_tahun()
	{
		$query = $this->db->query("SELECT bulan, sum(hk) as total_hadir FROM closing_karyawan where tahun = '2019' Group by bulan");
		return $query;
	}
	/*
	public function jenis_absen()
	{
		$query = $this->db->query("SELECT b.department, month(TANGGAL) as bulan, COUNT(m.nik) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where YEAR(TANGGAL) = '2019' group by department, month(TANGGAL)");
		return $query;
	}*/

	// *********************************** BUDGET LEMBUR ***********************************

	public function jml_pengajuan($bagian)
	{
		$query = $this->db->query("SELECT count(recid_plembur) as pengajuan from pengajuan_lembur pl join master_budget mbl on pl.recid_mbl = mbl.recid_mbl join bagian b on b.recid_bag = mbl.recid_bag  where recid_struktur = '$bagian' and year(tgl_lembur) = year(CURRENT_DATE)")->result();
		return $query;
	}

	// ***** COVID ******
	public function gen_user_covid()
	{
		$query = $this->db->query("SELECT * from karyawan where sts_aktif = 'aktif' and covid_uname = ''");
		return $query;
	}

	public function docsecre_pinsert($data)
	{
		$this->db->insert('doc_secre', $data);
	}

	public function docsecre_view()
	{
		$query = $this->db->query("SELECT * from doc_secre ds 
		join karyawan k on k.recid_karyawan = ds.recid_karyawan 
		join struktur d on d.recid_struktur = ds.recid_struktur
		");
		return $query;
	}

	public function doc_by_id($recid_doc)
	{
		$query = $this->db->query("SELECT * from doc_secre ds 
		join karyawan k on k.recid_karyawan = ds.recid_karyawan 
		join struktur d on d.recid_struktur = ds.recid_struktur
		where recid_doc = $recid_doc
		");
		return $query;
	}

	public function struktur_by_karyawan($recid_karyawan)
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.nama_karyawan, b.recid_bag, s.recid_struktur , s.nama_struktur FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.recid_karyawan = '$recid_karyawan'");
		return $query;
	}

	public function karyawan_by_struktur($recid_str)
	{
		$query = $this->db->query("SELECT k.recid_karyawan, k.nama_karyawan, b.recid_bag, b.indeks_hr, s.recid_struktur , s.nama_struktur FROM karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where s.recid_struktur = '$recid_str' and k.sts_aktif = 'aktif' and k.SPM = 'Tidak' and k.cci = 'Tidak' order by sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
		return $query;
	}

	public function docsecre_tahun($tahun)
	{
		$query = $this->db->query("SELECT * from doc_secre where year(tanggal) = $tahun");
		return $query;
	}

	public function docsecre_pupdate($data, $id)
	{
		$this->db->where('recid_doc', $id);
		$this->db->update('doc_secre', $data);
	}

	public function data_bod()
	{
		$query = $this->db->query("SELECT k.*, b.indeks_hr, j.indeks_jabatan, g.nama_golongan from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan where j.tingkatan > 10 and k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0'");
		return $query;
	}

	public function dominan_disc()
	{
		$query = $this->db->query("SELECT COUNT(IF(profile_disc = 'D',1,NULL)) AS 'D', COUNT(IF(profile_disc = 'I',1,NULL)) AS 'I', COUNT(IF(profile_disc = 'S',1,NULL)) AS 'S',  COUNT(IF(profile_disc = 'C',1,NULL)) AS 'C' FROM (select nik, nama_karyawan, profile_disc from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and spm = 'tidak' and cci = 'Tidak' and tc= '0') as dummy_table")->result();
		return $query;
	}

	public function dominan_disc_by_dept($tipe)
	{
		$query = $this->db->query("SELECT COUNT(IF(profile_disc = 'D',1,NULL)) AS 'D', COUNT(IF(profile_disc = 'I',1,NULL)) AS 'I', COUNT(IF(profile_disc = 'S',1,NULL)) AS 'S',  COUNT(IF(profile_disc = 'C',1,NULL)) AS 'C' FROM (select nik, nama_karyawan, profile_disc from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'tidak' and cci = 'Tidak' and tc= '0' and d.dept_group='$tipe') as dummy_table")->result();
		return $query;
	}

	public function dominan_disc_by_type($type)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, g.*, bs.sub_bag, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2, d.nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join bagian_sub bs on bs.recid_subbag = k.recid_subbag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.profile_disc = '$type' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc='0' order by nama_karyawan asc")->result();
		return $query;
	}

	public function profile_type()
	{
		$query = $this->db->query("SELECT profile_type, count(profile_type) as jml_type FROM karyawan where profile_type != '' and sts_aktif = 'Aktif' and spm = 'Tidak' and cci = 'Tidak' and tc= '0' group by profile_type;");
		return $query;
	}

	public function profile_by_type($type)
	{
		$query = $this->db->query("SELECT k.*, s.nama_struktur, b.*, j.*, g.*, sa.nama_karyawan as atasan1, ba.nama_karyawan as atasan2, d.nama_department from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn left join struktur s on s.recid_struktur = b.recid_struktur left join golongan g on g.recid_golongan = k.recid_golongan left join karyawan sa on s.pic_struktur = sa.recid_karyawan left join karyawan ba on b.pic_bagian = ba.recid_karyawan  left join department d on d.recid_department = b.recid_department where k.sts_aktif='Aktif' and k.profile_type = '$type' and k.spm = 'Tidak' and k.cci = 'Tidak' and k.tc='0' order by nama_karyawan asc")->result();
		return $query;
	}

	public function param_upah_id($id) // current
	{
		$query = $this->db->query("SELECT * from param_upah where recid_prmuph = $id");
		return $query;
	}

	public function param_upah_tmp_id($id) // histori
	{
		$query = $this->db->query("SELECT * from param_upah_tmp where recid_prmuph = $id");
		return $query;
	}

	public function gapok_masker($tingkatan, $masker, $tahun) // gapok by tingkatan + masker
	{
		$query = $this->db->query("SELECT * FROM `param_upah2` where tingkat_jbtn = $tingkatan and ($masker between range_masker1 and range_masker2) and tahun = $tahun and tipe = 'gapok';");
		return $query;
	}

	public function gapok_masker_jkt($tingkatan, $masker, $tahun) // gapok by tingkatan + masker
	{
		$query = $this->db->query("SELECT * FROM `param_upah2` where tingkat_jbtn = $tingkatan and ($masker between range_masker1 and range_masker2) and tahun = $tahun and tipe = 'Gapok_jkt';");
		return $query;
	}

	public function tahun_upah() // gapok by tingkatan + masker
	{
		$query = $this->db->query("SELECT * FROM `param_upah2` where tipe = 'gapok' order by recid_param2 desc limit 1;");
		return $query;
	}

	public function gapok($tingkatan, $tahun) // gapok by tingkatan
	{
		$query = $this->db->query("SELECT * FROM `param_upah2` where tingkat_jbtn = $tingkatan and tahun = $tahun and tipe = 'gapok';");
		return $query;
	}

	public function tjabatan($tingkatan, $tahun) // tunjangan jabatan
	{
		$query = $this->db->query("SELECT * FROM `param_upah2` where tingkat_jbtn = $tingkatan and tahun = $tahun and tipe = 'tunjab';");
		return $query;
	}

	public function tjenpek($recid_bag, $tahun) // tunjangan jenpek
	{
		$query = $this->db->query("SELECT * FROM `param_upah3` where recid_bag = $recid_bag and tahun = $tahun");
		return $query;
	}

	// ################################# PINDAH BAGIAN (BULK UPDATE) #####################################
	
	/**
	 * Get karyawan by bagian dan sub bagian
	 */
	public function get_karyawan_by_bagian($recid_bag = null, $recid_subbag = null, $search = '', $limit = 100)
	{
		$query = "SELECT k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, k.recid_subbag, 
		                 b.indeks_hr as bagian, sb.sub_bag as sub_bagian
		          FROM karyawan k
		          LEFT JOIN bagian b ON k.recid_bag = b.recid_bag
		          LEFT JOIN bagian_sub sb ON k.recid_subbag = sb.recid_subbag
		          WHERE k.sts_aktif = 'AKTIF'";
		
		if ($recid_bag) {
			$query .= " AND k.recid_bag = " . (int)$recid_bag;
		}
		
		if ($recid_subbag) {
			$query .= " AND k.recid_subbag = " . (int)$recid_subbag;
		}
		
		if ($search) {
			$search = $this->db->escape_like_str($search);
			$query .= " AND (k.nik LIKE '%$search%' OR k.nama_karyawan LIKE '%$search%')";
		}
		
		$query .= " ORDER BY k.nama_karyawan ASC";
		
		// Add limit untuk performance optimization
		if ($limit) {
			$query .= " LIMIT " . (int)$limit;
		}
		
		return $this->db->query($query)->result_array();
	}

	/**
	 * Bulk update pindah bagian
	 */
	public function bulk_pindah_bagian($data_arr, $recid_bagian_baru, $recid_subbag_baru, $tanggal_efektif, $catatan, $user_approve)
	{
		if (empty($data_arr) || count($data_arr) == 0) {
			return false;
		}

		$this->db->trans_start();

		foreach ($data_arr as $item) {
			// Get current karyawan data
			$karyawan = $this->db->select('recid_karyawan, nik, nama_karyawan, recid_bag, recid_subbag')
								  ->from('karyawan')
								  ->where('recid_karyawan', $item)
								  ->get()
								  ->row_array();

			if ($karyawan) {
				// Tentukan sub_bagian baru (jika kosong, gunakan nilai lama)
				$recid_subbag_baru_final = $recid_subbag_baru ?: $karyawan['recid_subbag'];

				// Insert log_edit record
				$log_data = array(
					'recid_karyawan' => $karyawan['recid_karyawan'],
					'nik' => $karyawan['nik'],
					'nama_karyawan' => $karyawan['nama_karyawan'],
					'recid_bagian_lama' => $karyawan['recid_bag'],
					'recid_subbag_lama' => $karyawan['recid_subbag'],
					'recid_bagian_baru' => $recid_bagian_baru,
					'recid_subbag_baru' => $recid_subbag_baru_final,
					'tanggal_efektif' => $tanggal_efektif,
					'tanggal_proses' => date('Y-m-d H:i:s'),
					'user_approve' => $user_approve,
					'catatan' => $catatan
				);
				$this->db->insert('log_edit', $log_data);

				// Update karyawan bagian
				$update_data = array(
					'recid_bag' => $recid_bagian_baru,
					'recid_subbag' => $recid_subbag_baru_final
				);
				$this->db->where('recid_karyawan', $item);
				$this->db->update('karyawan', $update_data);
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			return false;
		}

		return true;
	}

	/**
	 * Get riwayat perubahan bagian dengan filter
	 */
	public function get_riwayat_perubahan_bagian($from_date = null, $to_date = null, $recid_bagian_lama = null, $recid_bagian_baru = null, $limit = 100, $offset = 0)
	{
		$query = "SELECT le.*, 
		                 b_lama.indeks_hr as bagian_lama, sb_lama.sub_bag as sub_lama,
		                 b_baru.indeks_hr as bagian_baru, sb_baru.sub_bag as sub_baru
		          FROM log_edit le
		          LEFT JOIN bagian b_lama ON le.recid_bagian_lama = b_lama.recid_bag
		          LEFT JOIN bagian_sub sb_lama ON le.recid_subbag_lama = sb_lama.recid_subbag
		          LEFT JOIN bagian b_baru ON le.recid_bagian_baru = b_baru.recid_bag
		          LEFT JOIN bagian_sub sb_baru ON le.recid_subbag_baru = sb_baru.recid_subbag
		          WHERE 1=1";

		if ($from_date) {
			$from_date = $this->db->escape_str($from_date);
			$query .= " AND DATE(le.tanggal_efektif) >= '$from_date'";
		}

		if ($to_date) {
			$to_date = $this->db->escape_str($to_date);
			$query .= " AND DATE(le.tanggal_efektif) <= '$to_date'";
		}

		if ($recid_bagian_lama) {
			$query .= " AND le.recid_bagian_lama = " . (int)$recid_bagian_lama;
		}

		if ($recid_bagian_baru) {
			$query .= " AND le.recid_bagian_baru = " . (int)$recid_bagian_baru;
		}

		$query .= " ORDER BY le.tanggal_proses DESC LIMIT $limit OFFSET $offset";

		return $this->db->query($query)->result_array();
	}

	/**
	 * Get count riwayat perubahan bagian untuk pagination
	 */
	public function count_riwayat_perubahan_bagian($from_date = null, $to_date = null, $recid_bagian_lama = null, $recid_bagian_baru = null)
	{
		$query = "SELECT COUNT(*) as total FROM log_edit WHERE 1=1";

		if ($from_date) {
			$from_date = $this->db->escape_str($from_date);
			$query .= " AND DATE(tanggal_efektif) >= '$from_date'";
		}

		if ($to_date) {
			$to_date = $this->db->escape_str($to_date);
			$query .= " AND DATE(tanggal_efektif) <= '$to_date'";
		}

		if ($recid_bagian_lama) {
			$query .= " AND recid_bagian_lama = " . (int)$recid_bagian_lama;
		}

		if ($recid_bagian_baru) {
			$query .= " AND recid_bagian_baru = " . (int)$recid_bagian_baru;
		}

		$result = $this->db->query($query)->row_array();
		return $result['total'];
	}

	/**
	 * Get detail perubahan bagian by id
	 */
	public function get_detail_perubahan($id)
	{
		$query = "SELECT le.*, 
		                 b_lama.indeks_hr as bagian_lama, sb_lama.sub_bag as sub_lama,
		                 b_baru.indeks_hr as bagian_baru, sb_baru.sub_bag as sub_baru
		          FROM log_edit le
		          LEFT JOIN bagian b_lama ON le.recid_bagian_lama = b_lama.recid_bag
		          LEFT JOIN bagian_sub sb_lama ON le.recid_subbag_lama = sb_lama.recid_subbag
		          LEFT JOIN bagian b_baru ON le.recid_bagian_baru = b_baru.recid_bag
		          LEFT JOIN bagian_sub sb_baru ON le.recid_subbag_baru = sb_baru.recid_subbag
		          WHERE le.id = " . (int)$id;

		return $this->db->query($query)->row_array();
	}

	/**
	 * Get karyawan for setup dengan fuzzy search by NIK or Nama
	 */
	public function get_karyawan_for_setup($search = '', $limit = 100)
	{
		$this->db->select('k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, k.recid_subbag, b.indeks_hr, bs.sub_bag')
				 ->from('karyawan as k')
				 ->join('bagian as b', 'k.recid_bag = b.recid_bag', 'left')
				 ->join('bagian_sub as bs', 'k.recid_subbag = bs.recid_subbag', 'left')
				 ->where('k.sts_aktif', 1);

		if (!empty($search)) {
			$this->db->where("(k.nik LIKE '%" . $this->db->escape_like_str($search) . "%' OR k.nama_karyawan LIKE '%" . $this->db->escape_like_str($search) . "%')");
		}

		$this->db->order_by('k.nama_karyawan', 'ASC');
		$this->db->limit($limit);

		return $this->db->get()->result_array();
	}

	/**
	 * Bulk setup bagian - Direct update, NO log creation
	 */
	public function bulk_setup_bagian($recid_arr, $recid_bagian_baru, $recid_subbag_baru = null)
	{
		if (empty($recid_arr)) {
			return false;
		}

		// Persiapkan data update
		$update_data = [
			'recid_bag' => $recid_bagian_baru
		];

		if (!empty($recid_subbag_baru)) {
			$update_data['recid_subbag'] = $recid_subbag_baru;
		}

		// Update semua karyawan yang dipilih - TANPA LOG
		foreach ($recid_arr as $recid_karyawan) {
			$this->db->where('recid_karyawan', $recid_karyawan);
			$this->db->update('karyawan', $update_data);
		}

		return $this->db->affected_rows() > 0 ? true : false;
	}
}

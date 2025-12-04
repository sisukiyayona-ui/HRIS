<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_training extends CI_Model{

	public function kompetensi_insert($data)
	{
		$this->db->insert('train_kompetensi', $data);
	}

	public function kompetensi_aktif()
	{
		$query = $this->db->query("SELECT * from train_kompetensi where is_active = '1'");
		return $query;
	}

	public function participant_insert($data)
	{
		$this->db->insert('train_participant', $data);
	}

	public function training_pinsert($data)
	{
		$this->db->insert('training', $data);
	}

	public function training_view()
	{
	      $query = $this->db->query("SELECT * from training t join legal l on t.recid_legal = l.recid_legal")->result();
		return $query;
	}

	public function train_aju()
	{
	      $query = $this->db->query("SELECT * from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn order by tgl_m_training desc");
		return $query;
	}

	public function train_aju_by_karyawan($id)
	{
	      $query = $this->db->query("SELECT * from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where recid_karyawan = $id")->result();
		return $query;
	}

	public function train_aju_by_struktur($id)
	{
	      $query = $this->db->query("SELECT * from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where recid_struktur = $id")->result();
		return $query;
	}

	public function train_aju_by_department($id)
	{
	      $query = $this->db->query("SELECT * from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where department = '$id'")->result();
		return $query;
	}

	public function train_aju_by_bagian($bagian)
	{
	      $query = $this->db->query("SELECT * from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where indeks_hr = '$bagian' order by tgl_m_training desc");
		return $query;
	}

	public function train_ajuan($id)
	{
	      $query = $this->db->query("SELECT k.*, t.*, t.crt_by as pengaju, k.nama_karyawan, t.status, b.indeks_hr, j.indeks_jabatan, d.nama_department,  t.note as ket, p1.nama_karyawan as nama_atasan from training t join karyawan k on k.recid_karyawan = t.crt_by join bagian b on b.recid_bag = k.recid_bag join department d on d.recid_department = b.recid_department join jabatan j on j.recid_jbtn = k.recid_jbtn left join karyawan p1 on p1.recid_karyawan = t.atasan where recid_training = $id")->result();
		return $query;
	}

	public function train_detail($id)
	{
		$query = $this->db->query("SELECT k.nama_karyawan, t.*, b.indeks_hr, j.indeks_jabatan, t.note as ket, l.no_perjanjian, l.scan_perjanjian, td.kompetensi as komp 
			from training t 
			left join karyawan k on k.recid_karyawan = t.crt_by 
			left join bagian b on b.recid_bag = k.recid_bag 
			left join jabatan j on j.recid_jbtn = k.recid_jbtn 
			left join legal l on l.recid_legal = t.recid_legal 
			left join train_kompetensi td on td.recid_komp = t.recid_komp 
			where recid_training = '$id'")->result();
		return $query;
	}

	public function train_pst($id)
	{
	      $query = $this->db->query("SELECT * from training t join train_tmp tm on t.recid_training = tm.recid_training join karyawan k on k.recid_karyawan = tm.recid_karyawan join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where t.recid_training = $id");
		return $query;
	}

	public function pst_delete($recid)
	{
		$this->db->query("DELETE from train_tmp where recid_training = $recid");
	}

	public function training_master()
	{
	      $query = $this->db->query("SELECT * from training_master ")->result();
		return $query;
	}

	public function tmaster_insert($data)
	{
		$this->db->insert('training_master', $data);
	}
	
	public function cek_level($master)
	{
		$query = $this->db->query("SELECT count(recid_mtlevel) as counting from training_level where recid_mt = '$master' ")->result();
		return $query;
	}

	public function level_master($master)
	{
		$query = $this->db->query("SELECT *  from training_level where recid_mt = '$master' ")->result();
		return $query;
	}

	public function tmasterlevel_insert($data)
	{
		$this->db->insert('training_level', $data);
	}

	public function training_karyawan_detail($recid_training)
	{
	      $query = $this->db->query("SELECT t.*, k.*, p.*, b.indeks_hr, j.indeks_jabatan, t.note as ket, l.* from training t left join karir k on k.recid_legal = t.recid_legal left join karyawan p on k.recid_karyawan = p.recid_karyawan left join bagian b on b.recid_bag = p.recid_bag left join jabatan j on p.recid_jbtn = j.recid_jbtn left join legal l on l.recid_legal = t.recid_legal where t.recid_training = '$recid_training'");
		return $query;
	}

	public function training_pupdate($id,$data)
	{
		$this->db->where('recid_training',$id);
		$this->db->update('training', $data);
	}

	public function training_dkaryawan($recid)
	{
		$this->db->query("DELETE from train_tmp where recid_tmp = $recid");
	}

	public function training_dkkaryawan($recid)
	{
		$this->db->query("DELETE from karir where recid_karir = $recid");
	}

	public function karir_pupdatetraining($recid, $data)
	{
		$this->db->where('recid_legal',$recid);
		$this->db->update('karir', $data);
	}

	public function get_traininglevel($recid_mtlevel)
	{
		$query = $this->db->query("SELECT * From training_level where recid_mtlevel = '$recid_mtlevel'")->result();
		return $query;
	}

	public function get_levelprev($recid_mt, $level)
	{
		$query = $this->db->query("SELECT tl.*, tm.nama_training From training_level tl join training_master tm on tl.recid_mt = tm.recid_mt  where tl.recid_mt = '$recid_mt' and level = '$level'")->result();
		return $query;
	}

	public function jml_level($recid_mt, $level)
	{
		$query = $this->db->query("SELECT count(recid_mtlevel) as jml_level FROM `training_level` where recid_mt = '$recid_mt' and level < '$level'")->result();
		return $query;
	}

	public function training_legal($recid_mtlevel)
	{
		$query = $this->db->query("SELECT * from training where recid_mtlevel = '$recid_mtlevel'");
		return $query;
	}

	public function cek_training($r_legal)
	{
		$query = $this->db->query("SELECT * from karir where recid_legal = '$r_legal'")->result();
		return $query;
	}

	public function training_last()
	{
		$query = $this->db->query("SELECT * from training order by recid_training desc limit 1")->result();
		return $query;
	}

	public function traintmp_insert($data)
	{
		$this->db->insert('train_tmp', $data);
	}

	public function count_training()
	{
		$query = $this->db->query("SELECT * from training where YEAR(tgl_pengajuan) = YEAR(CURRENT_DATE) ");
		return $query;
	}

	public function training_dinamis()
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
			and l.legal_delete = '0'
			order by t.tgl_m_training desc");
		return $query;
	}

	public function evaluasi_insert($id_training, $id_karyawan, $data)
	{
		$this->db->where('recid_training',$id_training);
		$this->db->where('recid_karyawan',$id_karyawan);
		$this->db->update('train_participant', $data);
	}

	public function train_evaluasi($recid_training, $recid_karyawan)
	{
		$query = $this->db->query("SELECT * from train_participant where recid_training = '$recid_training' and recid_karyawan = '$recid_karyawan'")->result();
		return $query;
	}

	public function rekapitulasi_training()
	{
		$query = $this->db->query("SELECT tp.recid_training, count(tp.recid_participant) as jml, tk.kompetensi as komp, t.* from train_participant tp
			join training t on t.recid_training = tp.recid_training
			left join train_kompetensi tk on tk.recid_komp = t.recid_komp
			group by t.recid_training ")->result();
		return $query;
	}

	public function training_hour($thn)
	{
		$query = $this->db->query("SELECT SUM(IF(umur = 1,jml_jam,0)) AS 'jan', SUM(IF(umur = 2,jml_jam,0)) AS 'feb', SUM(IF(umur = 3,jml_jam,0)) AS 'mar', SUM(IF(umur = 4,jml_jam,0)) AS 'apr', SuM(IF(umur = 5,jml_jam,0)) AS 'mei', SuM(IF(umur = 6,jml_jam,0)) AS 'jun', SuM(IF(umur = 7,jml_jam,0)) AS 'jul', SuM(IF(umur = 8,jml_jam,0)) AS 'agu', SuM(IF(umur = 9,jml_jam,0)) AS 'sep', SuM(IF(umur = 10,jml_jam,0)) AS 'okt', SuM(IF(umur = 11,jml_jam,0)) AS 'nov', SuM(IF(umur = 12,jml_jam,0)) AS 'des' FROM (select month(tgl_m_training) AS umur, jml_jam from training where year(tgl_m_training) = '$thn')as dummy_table");
		return $query;
	}

	public function train_comp($thn)
	{
		$query = $this->db->query("SELECT tk.kompetensi, count(t.recid_training) as jml
			FROM training t
			join train_kompetensi tk on tk.recid_komp = t.recid_komp 
			where year(tgl_m_training) = '$thn' 
			group by tk.recid_komp")->result();
		return $query;
	}

	public function rekap_training($bulan, $tahun)
	{
		$query = $this->db->query("SELECT tp.recid_training, count(tp.recid_participant) as jml, tk.kompetensi as komp, t.* from train_participant tp
			join training t on t.recid_training = tp.recid_training
			left join train_kompetensi tk on tk.recid_komp = t.recid_komp
			where month(t.tgl_m_training) = '$bulan' and year(t.tgl_m_training) = '$tahun'
			group by t.recid_training ")->result();
		return $query;
	}

	public function rekap_training_kompetensi($tahun, $kompetensi)
	{
		$query = $this->db->query("SELECT tp.recid_training, count(tp.recid_participant) as jml, tk.kompetensi as komp, t.* from train_participant tp
			join training t on t.recid_training = tp.recid_training
			left join train_kompetensi tk on tk.recid_komp = t.recid_komp
			where year(t.tgl_m_training) = '$tahun'
			and tk.kompetensi = '$kompetensi'
			group by t.recid_training ")->result();
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


}
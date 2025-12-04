<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Down_ms extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_hris', 'm_training'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
	}

	public function index()
	{
		$this->load->view('layout/a_header');
		$this->load->view('login');
	}


	public function download_training_ms()
	{

		$recid_training = $this->uri->segment(3);
		// $recid_training = 4;
		$pengaju = $this->m_training->train_ajuan($recid_training);
		foreach ($pengaju as $aju) {
			$tgl_aju = date("d M Y", strtotime($aju->tgl_pengajuan));
			$bag = $aju->indeks_hr;
			if ($bag != "") {
				$bag = str_replace("&", "-", $bag);
			}
			$department = $aju->nama_department;
			if ($department != "") {
				$department = str_replace("&", "-", $department);
			}
			$acc_hc = $aju->acc_hc;
			$berbayar = $aju->berbayar;
		}
		$training = $this->m_training->train_ajuan($recid_training);
		foreach ($training as $t) {
			$tgl_m_training = date("d M Y", strtotime($t->tgl_m_training));
			$tgl_a_training = date("d M Y", strtotime($t->tgl_a_training));
			$judul_training = $t->judul_training;
		}

		require 'vendor/autoload.php';
		if ($berbayar == "Ya") {
			$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Training Berbayar.docx');
		} else {
			$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Training Free.docx');
		}

		$templateProcessor->setValues([
			'no_form'	=> $aju->no_form,
			'nama' => $aju->nama_karyawan,
			'indeks_bag' =>  $bag,
			'indeks_jabatan' => $aju->indeks_jabatan,
			'departement' => $aju->nama_department,
			'tgl' => $tgl_aju,
			'jenis' => $t->jenis_training,
			'topik' => $t->judul_training,
			'pelatih' => $t->trainer,
			'tgl_mulai' => $tgl_m_training,
			'tgl_selesai' => $tgl_a_training,
			'tempat' =>  $t->tempat_training,
			'waktu' => $t->jml_jam,
			'penyelenggara' => $t->lembaga,
			'biaya' => number_format($t->biaya),
			'alasan' => $t->alasan_pengajuan,
		]);

		if ($acc_hc == null) {
			$isi = $this->m_training->train_pst($recid_training);
		} else {
			$isi = $this->m_training->training_karyawan_detail($recid_training);
		}
		$cnt = $isi->num_rows();
		$templateProcessor->cloneRow('no', $cnt); // search no,  sebanyak no yg ada di array

		$number = 0;
		$no = array();
		$nik = array();
		$nama_karyawan = array();
		$indeks_hr = array();
		$indeks_jbtn = array();
		foreach ($isi->result() as $key) {
			$number = $number + 1;
			array_push($no, $number);
			array_push($nik, $key->nik);
			array_push($nama_karyawan, $key->nama_karyawan);
			$bagian = $key->indeks_hr;
			if ($bagian != "") {
				$bagian = str_replace("&", "-", $bagian);
			}
			array_push($indeks_hr, $bagian);
			array_push($indeks_jbtn, $key->indeks_jabatan);
		}

		for ($i = 0; $i < $cnt; $i++) {
			$templateProcessor->setValue('no#' . $no[$i], $no[$i]);
			$templateProcessor->setValue('nik#' . $no[$i], $nik[$i]);
			$templateProcessor->setValue('nama_karyawan#' . $no[$i], $nama_karyawan[$i]);
			$templateProcessor->setValue('indeks_hr#' . $no[$i], $indeks_hr[$i]);
			$templateProcessor->setValue('indeks_jbtn#' . $no[$i], $indeks_jbtn[$i]);
		}

		$files = "F-UP " . $judul_training;
		header("Content-Disposition: attachment; filename=" . $files . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_resume_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Resume.docx');

		$id = $this->uri->segment(3);
		$news = $this->m_hris->karyawan_detail($id);
		foreach ($news as $n) {
			$nama = $n->nama_karyawan;
			if ($n->sts_penunjang == 'TK') {
				$sts_penunjang = "Tidak Kawin";
			} else if ($n->sts_penunjang == 'K0') {
				$sts_penunjang = "Kawin, Anak 0";
			} else if ($n->sts_penunjang == 'K1') {
				$sts_penunjang = "Kawin, Anak 1";
			} else if ($n->sts_penunjang == 'K2') {
				$sts_penunjang = "Kawin, Anak 2";
			} else {
				$sts_penunjang = "Kawin, Anak 3";
			}

			$tgl_lahir = date("d M Y", strtotime($n->tgl_lahir));

			if ($n->no_bpjs_kes == '') {
				$no_bpjs_kes =  "-";
			} else {
				$no_bpjs_kes =  $n->no_bpjs_kes;
			}

			if ($n->no_bpjs_tk == '') {
				$no_bpjs_tk = "-";
			} else {
				$no_bpjs_tk = $n->no_bpjs_tk;
			}

			if ($n->no_askes == '') {
				$no_askes = "-";
			} else {
				$no_askes = $n->no_askes;
			}

			if ($n->tgl_trisula == null) {
				$tgl_trisula =  "-";
			} else if ($n->tgl_trisula == "0000-00-00") {
				$tgl_trisula =  "-";
			} else {
				$tgl_trisula =  date("d M Y", strtotime($n->tgl_trisula));
			}

			if ($n->tgl_m_kerja == null) {
				$tgl_m_kerja = "-";
			} else if ($n->tgl_m_kerja == "0000-00-00") {
				$tgl_m_kerja = "-";
			} else {
				$tgl_m_kerja =  date("d M Y", strtotime($n->tgl_m_kerja));
				$diff  = date_diff(date_create($n->tgl_m_kerja), date_create());
				$lama =  $diff->format(' ( %Y tahun %m bulan %d hari )');
			}

			if ($n->tgl_a_kerja == null) {
				$tgl_a_kerja = "-";
			} else if ($n->tgl_a_kerja == "0000-00-00") {
				$tgl_a_kerja = "-";
			} else if ($n->tgl_a_kerja == "9999-12-31") {
				$tgl_a_kerja = "-";
			} else {
				$tgl_a_kerja = date("d M Y", strtotime($n->tgl_a_kerja));
			}

			if ($n->profile_disc == '') {
				$profile_disc = "-";
			} else {
				$profile_disc = $n->profile_disc;
			}

			if ($n->profile_type == '') {
				$profile_type = "-";
			} else {
				$profile_type = $n->profile_type;
			}

			$nama = strtolower($nama);
			$nama = ucfirst($nama);
			$usia = $this->m_hris->umur($n->recid_karyawan);
			foreach ($usia as $umur) {
			}
			$targetFile = base_url() . 'images/foto/';
			$default_foto = "./images/foto/user.png";
			$filename = 'Resume ' . $nama . '.docx';
			$tgl_print = date("Y-m-d");
			echo $tgl_print;
			$tgl_print = date("d M Y", strtotime($tgl_print));

			$templateProcessor->setImageValue('foto', $targetFile . $n->foto);
			$templateProcessor->setValues([
				'nama_karyawan'	=> $nama,
				'nik' => $n->nik,
				'tmp_lahir' =>  $n->tmp_lahir,
				'tgl_lahir' => $tgl_lahir,
				'umur' => $umur->umur,
				'jenkel' => $n->jenkel,
				'goldar' => $n->gol_darah,
				'agama' => $n->agama,
				'status_kawin' => $n->sts_nikah,
				'status_penunjang' => $sts_penunjang,
				'no_ktp' => $n->no_ktp,
				'no_kk' =>  $n->no_kk,
				'npwp' => $n->no_npwp,
				'bpjs_kes' => $no_bpjs_kes,
				'bpjs_tk' => $no_bpjs_tk,
				'no_aia' => $n->no_aia,
				'no_asuransi' => $no_askes,
				'pendidikan' => $n->pendidikan,
				'jurusan' => $n->jurusan,
				'alamat_ktp' => $n->alamat_ktp,
				'alamat_skrg' => $n->alamat_skrg,
				'telp1' => $n->telp1,
				'telp2' => $n->telp2,
				'sts_aktif' => $n->sts_aktif,
				'spm' => $n->spm,
				'tgl_trisula' => $tgl_trisula,
				'tgl_m_kerja' => $tgl_m_kerja,
				'lama' => $lama,
				'tgl_a_kerja' => $tgl_a_kerja,
				'profile_disc' => $profile_disc,
				'profile_type' => $profile_type,
				'tgl_print'	=> $tgl_print,
			]);
		}

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_tanggungan_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Tanggungan.docx');

		$recid_karyawan = $this->uri->segment(3);
		$karyawan = $this->m_hris->karyawan_detail($recid_karyawan);
		$tunjangan = $this->m_hris->tunjangan_history($recid_karyawan);

		$no = array();
		$nama = array();
		$hubungan = array();
		$nik = array();
		$bpjs = array();
		$status = array();
		$x = 0;
		foreach ($karyawan as $kary) {
			$x++;
			$nama_k = $kary->nama_karyawan;
			$nama_k = strtolower($nama_k);
			$nama_k = ucfirst($nama_k);
			$status_k = "Ditanggung";
			$hub_keluarga = "Karyawan";

			array_push($no, $x);
			array_push($nama, $nama_k);
			array_push($hubungan, $hub_keluarga);
			array_push($nik, $kary->no_ktp);
			array_push($bpjs, $kary->no_bpjs_kes);
			array_push($status, $status_k);
		}


		$i = 1;
		foreach ($tunjangan as $data) {
			$i++;
			$nama_t = $data->nama_tunjangan;
			$nama_t = strtolower($nama_t);
			$nama_t = ucfirst($nama_t);

			if ($data->sts_tunjangan == "Yes") {
				$status_t = "Ditanggung";
			} else {
				$status_t = "Tidak Ditanggung";
			}

			array_push($no, $i);
			array_push($nama, $nama_t);
			array_push($hubungan, $hub_keluarga);
			array_push($nik, $data->no_id);
			array_push($bpjs, $data->no_bpjs);
			array_push($status, $status_t);
		}

		$cnt = count($no);
		$templateProcessor->cloneRow('no', $cnt);
		for ($m = 0; $m < $cnt; $m++) {

			$templateProcessor->setValue('no#' . $no[$m], $no[$m]);
			$templateProcessor->setValue('nama#' . $no[$m], $nama[$m]);
			$templateProcessor->setValue('hubungan#' . $no[$m], $hubungan[$m]);
			$templateProcessor->setValue('no_nik#' . $no[$m], $nik[$m]);
			$templateProcessor->setValue('no_bpjs#' . $no[$m], $bpjs[$m]);
			$templateProcessor->setValue('status#' . $no[$m], $status[$m]);
		}

		$filename = 'Tanggungan ' . $nama_k . '.docx';
		$tgl_print = date("Y-m-d");
		$tgl_print = date("d M Y", strtotime($tgl_print));

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_detail_tanggungan_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Detail Tanggungan.docx');

		$recid_karyawan = $this->uri->segment(3);
		$tunjangan = $this->m_hris->tunjangan_detail($recid_karyawan);
		foreach ($tunjangan as $data) {
			$nama = $data->nama_karyawan;
			$nama = strtolower($nama);
			$nama = ucfirst($nama);
			$nama_t = $data->nama_tunjangan;
			$nama_t = strtolower($nama_t);
			$nama_t = ucfirst($nama_t);
			if ($data->sts_tunjangan == "Yes") {
				$status = "Ditanggung";
			} else {
				$status = "Tidak Ditanggung";
			}
			$tgl_lahir = $data->tgl_tlahir;
			$tgl_lahir  = date("d M Y", strtotime($tgl_lahir));
			$diff  = date_diff(date_create($data->tgl_tlahir), date_create());
			$usia =  $diff->format(' ( %Y tahun %m bulan %d hari )');

			$tgl_print = date("Y-m-d");
			$tgl_print = date("d M Y", strtotime($tgl_print));

			$templateProcessor->setValues([
				'nama_karyawan'	=> $nama,
				'nama' => $nama_t,
				'hubungan'	=> $data->hub_keluarga,
				'tmp_lahir'	=> $data->tmp_lahir,
				'tgl_lahir'	=> $tgl_lahir,
				'no_id'	=> $data->no_id,
				'agama'	=> $data->agama,
				'pendidikan'	=> $data->panak,
				'pekerjaan'	=> $data->pekerjaan,
				'no_bpjs'	=> $data->no_bpjs,
				'status'	=> $status,
				'usia'	=> $usia,
				'tgl_print'	=> $tgl_print,
			]);
		}

		$filename = 'Detail Tanggungan ' . $nama_t . '.docx';


		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_karir_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Karir.docx');

		$recid_karyawan = $this->uri->segment(3);
		$karyawan = $this->m_hris->karyawan_detail($recid_karyawan);
		foreach ($karyawan as $key) {
			$nama_karyawan = $key->nama_karyawan;
		}
		$templateProcessor->setValue('nama_karyawan', $nama_karyawan);
		$karir = $this->m_hris->karir_history($recid_karyawan);
		$karirs = $this->m_hris->karir_history2($recid_karyawan);

		$no = array();
		$tgl_m = array();
		$tgl_a = array();
		$no_sk = array();
		$jenis = array();
		$bagian = array();
		$jabatan = array();
		$keterangan = array();
		$i = 0;
		foreach ($karir as $k) {
			$i = $i + 1;
			$tgl_m_karir = date("d-M-Y", strtotime($k->tgl_m_karir));
			$tgl_a_karir = "";
			if ($k->tgl_a_karir == '' || $k->tgl_a_karir == '0000-00-00') {
				$tgl_a_karir = "-";
			} else {
				$tgl_a_karir = date("d-M-Y", strtotime($k->tgl_a_karir));
			}


			if ($k->no_perjanjian == '') {
				$no_s = "Tidak Ada no SK";
			} else {
				$no_s = $k->no_perjanjian;
			}

			$bag = $k->indeks_hr;
			$bag = str_replace("&", "-", $bag);
			array_push($no, $i);
			array_push($tgl_m, $tgl_m_karir);
			array_push($tgl_a, $tgl_a_karir);
			array_push($no_sk, $no_s);
			array_push($jenis, $k->kategori);
			array_push($bagian, $bag);
			array_push($jabatan, $k->indeks_jabatan);
			array_push($keterangan, $k->note);
		}


		$cnt = $karirs->num_rows();
		$templateProcessor->cloneRow('no', $cnt);
		$y = 0;
		for ($y = 0; $y < $cnt; $y++) {
			$templateProcessor->setValue('no#' . $no[$y], $no[$y]);
			$templateProcessor->setValue('tgl_m#' . $no[$y], $tgl_m[$y]);
			$templateProcessor->setValue('tgl_a#' . $no[$y], $tgl_a[$y]);
			$templateProcessor->setValue('no_sk#' . $no[$y], $no_sk[$y]);
			$templateProcessor->setValue('jenis#' . $no[$y], $jenis[$y]);
			$templateProcessor->setValue('bagian#' . $no[$y], $bagian[$y]);
			$templateProcessor->setValue('jabatan#' . $no[$y], $jabatan[$y]);
			$templateProcessor->setValue('keterangan#' . $no[$y], $keterangan[$y]);
			// echo $y." - ".$no[$y]."<br>";
		}

		$filename = 'Histori Karir ' . $nama_karyawan . '.docx';
		$tgl_print = date("Y-m-d");
		$tgl_print = date("d M Y", strtotime($tgl_print));
		$templateProcessor->setValue('tgl_print', $tgl_print);

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_sanksi_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Sanksi.docx');

		$recid_karyawan = $this->uri->segment(3);
		$karyawan = $this->m_hris->karyawan_detail($recid_karyawan);
		foreach ($karyawan as $key) {
			$nama_karyawan = $key->nama_karyawan;
		}
		$templateProcessor->setValue('nama_karyawan', $nama_karyawan);
		$karir = $this->m_hris->sanksi_history($recid_karyawan);
		$karirs = $this->m_hris->sanksi_history2($recid_karyawan);

		$no = array();
		$tgl_m = array();
		$tgl_a = array();
		$no_sk = array();
		$jenis_sanksi = array();
		$jenis = array();
		$bagian = array();
		$jabatan = array();
		$keterangan = array();
		$i = 0;
		foreach ($karir as $k) {
			$i = $i + 1;
			$tgl_m_karir = date("d-M-Y", strtotime($k->tgl_m_karir));
			$tgl_a_karir = "";
			if ($k->tgl_a_karir == '' || $k->tgl_a_karir == '0000-00-00') {
				$tgl_a_karir = "-";
			} else {
				$tgl_a_karir = date("d-M-Y", strtotime($k->tgl_a_karir));
			}


			if ($k->no_perjanjian == '') {
				$no_s = "Tidak Ada no SK";
			} else {
				$no_s = $k->no_perjanjian;
			}

			$bag = $k->indeks_hr;
			$bag = str_replace("&", "-", $bag);
			array_push($no, $i);
			array_push($tgl_m, $tgl_m_karir);
			array_push($tgl_a, $tgl_a_karir);
			array_push($no_sk, $no_s);
			array_push($jenis, $k->kategori);
			array_push($jenis_sanksi, $k->jenis_sanksi);
			array_push($bagian, $bag);
			array_push($jabatan, $k->indeks_jabatan);
			array_push($keterangan, $k->note);
		}


		$cnt = $karirs->num_rows();
		$templateProcessor->cloneRow('no', $cnt);
		$y = 0;
		for ($y = 0; $y < $cnt; $y++) {
			$templateProcessor->setValue('no#' . $no[$y], $no[$y]);
			$templateProcessor->setValue('tgl_m#' . $no[$y], $tgl_m[$y]);
			$templateProcessor->setValue('tgl_a#' . $no[$y], $tgl_a[$y]);
			$templateProcessor->setValue('no_sk#' . $no[$y], $no_sk[$y]);
			$templateProcessor->setValue('jenis#' . $no[$y], $jenis[$y]);
			$templateProcessor->setValue('bagian#' . $no[$y], $bagian[$y]);
			$templateProcessor->setValue('jabatan#' . $no[$y], $jabatan[$y]);
			$templateProcessor->setValue('jenis_sanksi#' . $no[$y], $jenis_sanksi[$y]);
			$templateProcessor->setValue('keterangan#' . $no[$y], $keterangan[$y]);
			// echo $y." - ".$no[$y]."<br>";
		}

		$filename = 'Histori Sanksi ' . $nama_karyawan . '.docx';
		$tgl_print = date("Y-m-d");
		$tgl_print = date("d M Y", strtotime($tgl_print));
		$templateProcessor->setValue('tgl_print', $tgl_print);

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_histori_training_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Training.docx');

		$recid_karyawan = $this->uri->segment(3);
		$karyawan = $this->m_hris->karyawan_detail($recid_karyawan);
		foreach ($karyawan as $key) {
			$nama_karyawan = $key->nama_karyawan;
		}
		$templateProcessor->setValue('nama_karyawan', $nama_karyawan);
		$training = $this->m_training->training_history($recid_karyawan);
		$trainings = $this->m_training->training_history2($recid_karyawan);

		$no = array();
		$tgl_m = array();
		$tgl_a = array();
		$no_sk = array();
		$jenis = array();
		$topik = array();
		$penyelenggara = array();
		$jam = array();
		$i = 0;
		foreach ($training as $k) {
			$i = $i + 1;
			$tgl_m_karir = date("d-M-Y", strtotime($k->tgl_m_karir));
			$tgl_a_karir = "";
			if ($k->tgl_a_karir == '' || $k->tgl_a_karir == '0000-00-00') {
				$tgl_a_karir = "-";
			} else {
				$tgl_a_karir = date("d-M-Y", strtotime($k->tgl_a_karir));
			}


			/*if($k->no_perjanjian == '')
			{
				$no_s = "Tidak Ada no SK";
			}else
			{ 
				$no_s = $k->no_perjanjian; 
			}	*/

			$judul = ucwords($k->judul_training);
			$lembaga = ucwords($k->lembaga);
			array_push($no, $i);
			array_push($tgl_m, $tgl_m_karir);
			array_push($tgl_a, $tgl_a_karir);
			// array_push($no_sk, $no_s);
			array_push($jenis, $k->jenis_training);
			array_push($topik, $judul);
			array_push($penyelenggara, $lembaga);
			array_push($jam, $k->jml_jam);
		}


		$cnt = $trainings->num_rows();
		$templateProcessor->cloneRow('no', $cnt);
		$y = 0;
		for ($y = 0; $y < $cnt; $y++) {
			$templateProcessor->setValue('no#' . $no[$y], $no[$y]);
			$templateProcessor->setValue('tgl_m#' . $no[$y], $tgl_m[$y]);
			$templateProcessor->setValue('tgl_a#' . $no[$y], $tgl_a[$y]);
			// $templateProcessor->setValue('no_sk#'.$no[$y], $no_sk[$y]);
			$templateProcessor->setValue('jenis#' . $no[$y], $jenis[$y]);
			$templateProcessor->setValue('topik#' . $no[$y], $topik[$y]);
			$templateProcessor->setValue('penyelenggara#' . $no[$y], $penyelenggara[$y]);
			$templateProcessor->setValue('jam#' . $no[$y], $jam[$y]);
			// echo $y." - ".$no[$y]."<br>";
		}

		$filename = 'Histori Training ' . $nama_karyawan . '.docx';
		$tgl_print = date("Y-m-d");
		$tgl_print = date("d M Y", strtotime($tgl_print));
		$templateProcessor->setValue('tgl_print', $tgl_print);

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}

	public function download_resume_pelamar_ms()
	{
		require 'vendor/autoload.php';
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Resume_Pelamar.docx');

		$id = $this->uri->segment(3);
		$news = $this->m_hris->pelamar_view_byrecid($id);
		foreach ($news as $n) {
			$nama = $n->nama_pelamar;
			$tgl_lahir = date("d M Y", strtotime($n->tgl_lahir));

			if ($n->profile_disc == '') {
				$profile_disc = "-";
			} else {
				$profile_disc = $n->profile_disc;
			}

			if ($n->profile_type == '') {
				$profile_type = "-";
			} else {
				$profile_type = $n->profile_type;
			}

			if ($n->pattern_type == '') {
				$pattern_type = "-";
			} else {
				$pattern_type = $n->pattern_type;
			}

			$hobi = $n->phobi;
			if ($hobi != "") {
				$hobi = str_replace("&", "dan", $hobi);
			}

			$nama = strtolower($nama);
			$nama = ucfirst($nama);

			$foto = $this->m_hris->cek_foto($id);
			if ($foto->num_rows() > 0) {
				foreach ($foto->result() as $f) {
					$foto_pel = $f->berkas;
				}
			} else {
				$foto_pel = "./images/foto/user.png";
			}


			$targetFile = 'http://192.168.10.10/Karir/asset/berkas/';
			$filename = 'Resume ' . $nama . '.docx';
			$tgl_print = date('Y-m-d');
			$tgl_print = date("d M Y", strtotime($tgl_print));

			$pengalaman = $this->m_hris->pengalaman($id);
			$cnt_p = $pengalaman->num_rows();
			$pernyataan = $this->m_hris->pernyataan($id);
			$keluarga = $this->m_hris->keluarga($id);
			$cnt_k = $keluarga->num_rows();


			$templateProcessor->setImageValue('foto', $targetFile . $foto_pel);
			$templateProcessor->setValues([
				'nama_pelamar'	=> $nama,
				'no_ktp' => $n->no_ktp,
				'tmp_lahir' =>  $n->ptmp_lahir,
				'tgl_lahir' => $tgl_lahir,
				'umur' => $n->umur,
				'jenkel' => $n->pjenkel,
				'goldar' => $n->pgoldar,
				'agama' => $n->pagama,
				'status_kawin' => $n->psts_kawin,
				'pendidikan' => $n->ppendidikan,
				'instansi' => $n->pinstansi,
				'jurusan' => $n->pjurusan,
				'thn_lulus' => $n->pthn_lulus,
				'alamat_ktp' => $n->palamat_ktp,
				'alamat_skrg' => $n->alamat,
				'telp1' => $n->no_telp,
				'telp2' => $n->ptelp_alt,
				'email' => $n->email,
				'hobi' => $hobi,
				'profile_disc' => $profile_disc,
				'pattern_type' => $pattern_type,
				'profile_type' => $profile_type,
				'tgl_print'	=> $tgl_print,
			]);

			$no_p = array();
			$thn_mulai = array();
			$thn_akhir = array();
			$nama_perusahaan = array();
			$alamat_perusahaan = array();
			$telp_perusahaan = array();
			$pendapatan = array();
			$posisi = array();
			$job_desc = array();
			$fasilitas = array();
			$alasan_keluar = array();
			$ip = 0;
			foreach ($pengalaman->result() as $p) {
				$jobdesc = $p->job_desc;
				if ($jobdesc != "") {
					$jobdesc = str_replace("&", "dan", $jobdesc);
				}
				$fas = $p->fasilitas;
				$fas = str_replace("&", "dan", $fas);
				$alasan_kel = $p->alasan_keluar;
				$alasan_kel = str_replace("&", "dan", $alasan_kel);
				$posisi_s = $p->posisi;
				$posisi_s = str_replace("&", "dan", $posisi_s);
				$ip = $ip + 1;
				array_push($no_p, $ip);
				array_push($thn_mulai, $p->thn_mulai);
				array_push($thn_akhir, $p->thn_akhir);
				array_push($nama_perusahaan, $p->nama_perusahaan);
				array_push($alamat_perusahaan, $p->alamat_perusahaan);
				array_push($telp_perusahaan, $p->telp_perusahaan);
				array_push($posisi, $posisi_s);
				array_push($pendapatan, $p->pendapatan);
				array_push($job_desc, $jobdesc);
				array_push($fasilitas, $fas);
				array_push($alasan_keluar, $alasan_kel);
			}

			$templateProcessor->cloneRow('no_p', $cnt_p);
			$y = 0;
			for ($y = 0; $y < $cnt_p; $y++) {
				// echo 'nama_perusahaan#'.$no_p[$y], $nama_perusahaan[$y];
				$templateProcessor->setValue('no_p#' . $no_p[$y], $no_p[$y]);
				$templateProcessor->setValue('nama_perusahaan#' . $no_p[$y], $nama_perusahaan[$y]);
				$templateProcessor->setValue('thn_mulai#' . $no_p[$y], $thn_mulai[$y]);
				$templateProcessor->setValue('thn_akhir#' . $no_p[$y], $thn_akhir[$y]);
				$templateProcessor->setValue('alamat_perusahaan#' . $no_p[$y], $alamat_perusahaan[$y]);
				$templateProcessor->setValue('telp_perusahaan#' . $no_p[$y], $telp_perusahaan[$y]);
				$templateProcessor->setValue('posisi#' . $no_p[$y], $posisi[$y]);
				$templateProcessor->setValue('pendapatan#' . $no_p[$y], $pendapatan[$y]);
				$templateProcessor->setValue('job_desc#' . $no_p[$y], $job_desc[$y]);
				$templateProcessor->setValue('fasilitas#' . $no_p[$y], $fasilitas[$y]);
				$templateProcessor->setValue('alasan_keluar#' . $no_p[$y], $alasan_keluar[$y]);
			}


			foreach ($pernyataan->result() as $pr) {
				$ppfas = $pr->ppfasilitas;
				$ppfas = str_replace("&", "dan", $ppfas);
				$sakit = $pr->ppsakit;
				$sakit = str_replace("&", "dan", $sakit);
				$lebih = $pr->kelebihan;
				$lebih = str_replace("&", "dan", $lebih);
				$kurang = $pr->kekurangan;
				$kurang = str_replace("&", "dan", $kurang);
			}
			$templateProcessor->setValues([
				'ppgaji'	=> $pr->ppgaji,
				'ppfasilitas' => $ppfas,
				'tgl_join' =>  $pr->tgl_join,
				'sakit_keras' =>  $pr->sakit_keras,
				'ppsakit' =>  $sakit,
				'dinas_luar' =>  $pr->dinas_luar,
				'part_time' =>  $pr->part_time,
				'referensi' =>  $pr->referensi,
				'hub_referensi' =>  $pr->hub_referensi,
				'kelebihan' =>  $lebih,
				'kekurangan' =>  $kurang,
				'info_dari' =>  $pr->info_dari,
			]);

			$no_k = array();
			$pknama = array();
			$pkhub = array();
			$pktmp_lahir = array();
			$pktgl_lahir = array();
			$pkjenkel = array();
			$pkpendidikan = array();
			$pkpekerjaan = array();
			$ik = 0;
			foreach ($keluarga->result() as $k) {
				$ik = $ik + 1;
				array_push($no_k, $ik);
				array_push($pknama, $k->pknama);
				array_push($pkhub, $k->pkhub);
				array_push($pktmp_lahir, $k->pktmp_lahir);
				array_push($pktgl_lahir, $k->pktgl_lahir);
				array_push($pkjenkel, $k->pkjenkel);
				array_push($pkpendidikan, $k->pkpendidikan);
				array_push($pkpekerjaan, $k->pkpekerjaan);
			}

			// print_r($pknama);

			$templateProcessor->cloneRow('no_k', $cnt_k);
			$yk = 0;
			for ($yk = 0; $yk < $cnt_k; $yk++) {
				// echo 'pknama#'.$pknama[$yk];
				$templateProcessor->setValue('no_k#' . $no_k[$yk], $no_k[$yk]);
				$templateProcessor->setValue('pknama#' . $no_k[$yk], $pknama[$yk]);
				$templateProcessor->setValue('pkhub#' . $no_k[$yk], $pkhub[$yk]);
				$templateProcessor->setValue('pktmp_lahir#' . $no_k[$yk], $pktmp_lahir[$yk]);
				$templateProcessor->setValue('pktgl_lahir#' . $no_k[$yk], $pktgl_lahir[$yk]);
				$templateProcessor->setValue('pkjenkel#' . $no_k[$yk], $pkjenkel[$yk]);
				$templateProcessor->setValue('pkpendidikan#' . $no_k[$yk], $pkpendidikan[$yk]);
				$templateProcessor->setValue('pkpekerjaan#' . $no_k[$yk], $pkpekerjaan[$yk]);
			}
		}

		header("Content-Disposition: attachment; filename=" . $filename . ".docx");
		$templateProcessor->saveAs('php://output');
	}
}

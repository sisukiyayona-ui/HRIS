<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Absen extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_absen', 'm_hris', 'm_absenbarcode'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
	}

	public function dashboard()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$data['bagian'] = $this->m_hris->bagian_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}
			// $data['closed'] = $this->m_absen->rekap_closing_admin($tgl, '1');
			// $data['open'] = $this->m_absen->rekap_closing_admin($tgl, '0');
			$non_shift = "(status = '1')";
			$hadir_baros = "(status = '1' or status = '14' or status = '15' or status= '16' )";
			$wfh = "(status = '8')";
			$shift = "(status between 14 and 23)";
			$cuti = "(status ='3' or status = '7' or status = '12')";
			$sakit = "(status = '2')";
			$lainnya = "(status between 4 and 6) or (status between 9 and 11) or (status = 13) or (status = 24) or (status = 25)";
			$hadir_spm = "(status = '22' or status = '23')";
			$tidak_hadir_spm = "(status != '22' and status != '23')";

			if ($role == '1' or $role == '2' or $role == '3' or $role == '5'  or $role == '24' or $role == '25' or $role == '34') {
				$data['totkar'] = $this->m_absen->jml_all($tgl);
				$data['non_shift'] = $this->m_absen->jml_by_status($tgl, $non_shift);
				$data['wfh'] = $this->m_absen->jml_by_status($tgl, $wfh);
				$data['shift'] = $this->m_absen->jml_by_status($tgl, $shift);
				$data['cuti'] = $this->m_absen->jml_by_status($tgl, $cuti);
				$data['sakit'] = $this->m_absen->jml_by_status($tgl, $sakit);
				$data['lainnya'] = $this->m_absen->jml_by_status($tgl, $lainnya);
				$data['baros'] = $this->m_absen->jml_by_status_baros($tgl, $hadir_baros);

				$data['totkar_spm'] = $this->m_absen->jml_spm($tgl);
				$data['hadir_spm'] = $this->m_absen->jml_by_status_spm($tgl, $hadir_spm);
				$data['wfh_spm'] = $this->m_absen->jml_by_status_spm($tgl, $wfh);
				$data['tidak_hadir_spm'] = $this->m_absen->jml_by_status_spm($tgl, $tidak_hadir_spm);
			} else if ($role == '23') {
				$recid_karyawan = $this->session->userdata('kar_id');
				$bagian = "(b.indeks_hr =";
				$bag = array();
				if ($tingkatan > '7') {
					$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$bagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$bagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
					// echo $bagian;
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					$cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
				$bagian .= ")";

				$data['totkar'] = $this->m_absen->jml_bagian($tgl, $bagian);
				$data['non_shift'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				$data['wfh'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				$data['shift'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				$data['cuti'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				$data['sakit'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				$data['lainnya'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);

				$data['totkar_spm'] = $this->m_absen->jml_bagian_spm($tgl, $bagian);
				$data['hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
				$data['wfh_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
				$data['tidak_hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, 	$bagian);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['totkar'] = $this->m_absen->jml_karyawan_baros($tgl);
				$data['non_shift'] = $this->m_absen->jml_by_status_baros($tgl, $non_shift);
				$data['wfh'] = $this->m_absen->jml_by_status_baros($tgl, $wfh);
				$data['shift'] = $this->m_absen->jml_by_status_baros($tgl, $shift);
				$data['cuti'] = $this->m_absen->jml_by_status_baros($tgl, $cuti);
				$data['sakit'] = $this->m_absen->jml_by_status_baros($tgl, $sakit);
				$data['lainnya'] = $this->m_absen->jml_by_status_baros($tgl, $lainnya);

				$data['totkar_spm'] = $this->m_absen->jml_bagian_spm($tgl, $bagian);
				$data['hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
				$data['wfh_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
				$data['tidak_hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['totkar'] = $this->m_absen->jml_bagian($tgl, $bagian);
				$data['totkar'] = $this->m_absen->jml_bagian($tgl, $bagian);
				$data['non_shift'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				$data['wfh'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				$data['shift'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				$data['cuti'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				$data['sakit'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				$data['lainnya'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);

				$data['totkar_spm'] = $this->m_absen->jml_bagian_spm($tgl, $bagian);
				$data['hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
				$data['wfh_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
				$data['tidak_hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
			} else if ($role == '37') {
				$data['totkar'] = $this->m_absen->jml_all_dept_group($tgl, $dept_group);
				$data['non_shift'] = $this->m_absen->jml_by_status_dept_group($tgl, $non_shift, $dept_group);
				$data['wfh'] = $this->m_absen->jml_by_status_dept_group($tgl, $wfh, $dept_group);
				$data['shift'] = $this->m_absen->jml_by_status_dept_group($tgl, $shift, $dept_group);
				$data['cuti'] = $this->m_absen->jml_by_status_dept_group($tgl, $cuti, $dept_group);
				$data['sakit'] = $this->m_absen->jml_by_status_dept_group($tgl, $sakit, $dept_group);
				$data['lainnya'] = $this->m_absen->jml_by_status_dept_group($tgl, $lainnya, $dept_group);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['totkar'] = $this->m_absen->jml_bagian($tgl, $bagian);
				$data['non_shift'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				$data['wfh'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				$data['shift'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				$data['cuti'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				$data['sakit'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				$data['lainnya'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);

				$data['totkar_spm'] = $this->m_absen->jml_bagian_spm($tgl, $bagian);
				$data['hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
				$data['wfh_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
				$data['tidak_hadir_spm'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
			}

			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/dashboard_absen', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_closing()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$tgl = date('Y-m-d');
			$data['closed'] = $this->m_absen->rekap_closing_admin($tgl, '1');
			$data['open'] = $this->m_absen->rekap_closing_admin($tgl, '0');
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/cek_closing', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function hari_kerja()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$thn = date('Y');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['hk'] = $this->m_absen->hk_view($thn);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/ganti_hari/hk_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function hk_insert()
	{
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$tgl_awal = $this->input->post('tgl_awal');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jml_hk = $this->input->post('jml_hk');
		$data_all = array();
		for ($i = 0; $i < count($jml_hk); $i++) {
			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('Y-m-d h:i:s'),
				'tahun'				=> $tahun,
				'bulan'				=> $bulan[$i],
				'tgl_awal'				=> $tgl_awal[$i],
				'tgl_akhir'				=> $tgl_akhir[$i],
				'jml_hk'			=> $jml_hk[$i],
			);
			array_push($data_all, $data);
		}
		$result = $this->m_absen->insert_hk($data_all);
		redirect('Absen/hari_kerja');
	}

	public function hk_update()
	{
		$recid_hk = $this->input->post('hk_recid');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$tgl_awal = $this->input->post('tgl_awal');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jml_hk = $this->input->post('jml_hk');
		$data = array(
			'crt_by'			=> $this->session->userdata('kar_id'),
			'crt_date'			=> date('Y-m-d h:i:s'),
			'tahun'				=> $tahun,
			'bulan'				=> $bulan,
			'tgl_awal'				=> $tgl_awal,
			'tgl_akhir'				=> $tgl_akhir,
			'jml_hk'			=> $jml_hk,
		);
		$result = $this->m_absen->edit_hk($recid_hk, $data);
		redirect('Absen/hari_kerja');
	}

	public function ganti_hari()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['gh'] = $this->m_absen->gh_view();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/ganti_hari/gh_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_gh()
	{
		$tanggal = $this->input->post('tanggal');
		$cek = $this->m_absen->cek_gh($tanggal);
		if ($cek->num_rows() > 0) {
			$hsl = "1";
		} else {
			$hsl = "0";
		}
		echo json_encode($hsl);
	}

	public function gh_insert()
	{
		$tanggal = $this->input->post('tanggal');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'crt_by'			=> $this->session->userdata('kar_id'),
			'crt_date'			=> date("Y-m-d h:i:s"),
			'is_delete'			=> '0',
			'tanggal'			=> $tanggal,
			'keterangan'		=> $keterangan,
		);
		$this->m_absen->save_data('ganti_hari', $data);
		redirect('Absen/ganti_hari');
	}

	public function gh_update()
	{
		$gh_recid = $this->input->post('gh_recid');
		$tanggal = $this->input->post('tanggal');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'tanggal'			=> $tanggal,
			'keterangan'		=> $keterangan,
			'mdf_by'			=> $this->session->userdata('kar_id'),
			'mdf_date'			=> date("Y-m-d h:i:s"),
		);
		$this->m_absen->edit_gh($gh_recid, $data);
		redirect('Absen/ganti_hari');
	}

	public function gh_delete()
	{
		$gh_recid = $this->uri->segment('3');
		$tanggal = $this->input->post('tanggal');
		$keterangan = $this->input->post('keterangan');
		$data = array(
			'is_delete'			=> '1',
			'mdf_by'			=> $this->session->userdata('kar_id'),
			'mdf_date'			=> date("Y-m-d h:i:s"),
		);
		$this->m_absen->edit_gh($gh_recid, $data);
		redirect('Absen/ganti_hari');
	}


	/*__________________ GENERATE ABSEN _________________________________*/
	public function generate_absen()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$today = $this->input->post('tgl_kehadiran');
			$bagian = $this->input->post('bagian');
			for ($i = 0; $i < count($bagian); $i++) {
				$cek = $this->m_absen->cek_hadir_bagian($today, $bagian[$i]);
				if ($cek->num_rows() > 0) {
					// $this->session->set_flashdata('warning','Absen Hari Ini Sudah Digenerate');
					// redirect('Absen/hadir_today');
				} else {
					$karyawan = $this->m_hris->all_karyawan_view_by_bagian($bagian[$i]);
					$data_all = array();
					foreach ($karyawan->result() as $k) {
						$data = array(
							'crt_by'			=> $this->session->userdata('kar_id'),
							'crt_date'			=> date('Y-m-d h:i:s'),
							'recid_karyawan'	=> $k->recid_karyawan,
							'tanggal'			=> $today,
							'jam_masuk'			=> "07:20:00",
							'jam_keluar'		=> "17:00:00",
							'status'			=> 1,
						);
						$result = $this->m_absen->generate_absen($data);
					}
				}
			}
			$tgl = $today;
			$cek_absensi = $this->m_absen->cek_absen_today($tgl);
			if ($cek_absensi->num_rows() > 0) {
				foreach ($cek_absensi->result() as $a) {
					$edit_hadir = array(
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $a->recid_karyawan,
						'tanggal'			=> $tgl,
						'jam_masuk'			=> null,
						'jam_keluar'		=> null,
						'status'			=> $a->jenis_absen,
					);
					$this->m_absen->edit_hadir($a->recid_karyawan, $tgl, $edit_hadir);
				}
			}
			if ($result) {
				$this->session->set_flashdata('sukses', 'Absen Berhasil Digenerate');
				redirect('Absen/hadir_today');
			} else {
				$this->session->set_flashdata('eror', 'Absen Gagal Digenerate');
				redirect('Absen/hadir_today');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function hadir_today()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$data['jenis'] = $this->m_absen->jenis_absen();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bagian'] = $this->m_hris->bagian_view();

			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$data['absen'] = $this->m_absen->hadir_today($tgl);
				$close = $this->m_absen->cek_closing_kehadiran($tgl);
				if ($close->num_rows() > 0) {
					foreach ($close->result() as $cl) {
						$closing = $cl->is_closed;
					}
				} else {
					$closing = '0';
				}

				$data['closing']  = $closing;
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['absen'] = $this->m_absen->hadir_today_bagian($tgl, $bagian);
				$close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
				if ($close->num_rows() > 0) {
					foreach ($close->result() as $cl) {
						$closing = $cl->is_closed;
					}
				} else {
					$closing = "0";
				}

				$data['closing']  = $closing;
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['absen'] = $this->m_absen->hadir_today_bagian($tgl, $bagian);
				$close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
				if ($close->num_rows() > 0) {
					foreach ($close->result() as $cl) {
						$closing = $cl->is_closed;
					}
				} else {
					$closing = "0";
				}

				$data['closing']  = $closing;
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['absen'] = $this->m_absen->hadir_today_bagian($tgl, $bagian);
				$close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
				if ($close->num_rows() > 0) {
					foreach ($close->result() as $cl) {
						$closing = $cl->is_closed;
					}
				} else {
					$closing = "0";
				}

				$data['closing']  = $closing;
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/hadir_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function closing_kehadiran()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$clos = '1';
				$closed = $this->m_absen->closing_process($tgl, $clos);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '1';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '1';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '1';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			}
			// redirect("Absen/hadir_today");
			echo json_encode("ok");
		} else {
			redirect('Auth/keluar');
		}
	}

	public function open_kehadiran()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$clos = '0';
				$closed = $this->m_absen->closing_process($tgl, $clos);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '0';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '0';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$clos = '0';
				$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
			}
			// redirect("Absen/hadir_today");
			echo json_encode("ok");
		} else {
			redirect('Auth/keluar');
		}
	}

	public function closing_kehadiran_bagian()
	{
		$indeks_hr = $this->input->post('bagian');
		$tgl = $this->input->post('tanggal');

		$bagian = "(b.indeks_hr = '$indeks_hr')";
		$clos = '1';
		$closed = $this->m_absen->closing_process_bagian($tgl, $bagian, $clos);
		echo json_encode("ok");
		exit();
	}

	public function get_hadir_periode()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = $this->input->post('tgl_mulai');
			$role = $this->session->userdata('role_id');
			$data['jenis'] = $this->m_absen->jenis_absen();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bagian'] = $this->m_hris->bagian_view();

			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$absen = $this->m_absen->hadir_today($tgl);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$absen = $this->m_absen->hadir_today_bagian($tgl, $bagian);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$absen = $this->m_absen->hadir_today_bagian($tgl, $bagian);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$absen = $this->m_absen->hadir_today_bagian($tgl, $bagian);
			}
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));

			$data = [];
			$no = 0;
			foreach ($absen->result() as $r) {
				$bagian = $r->indeks_hr;
				$bagian = substr($bagian, strpos($bagian, ". ") + 1);

				$jabatan = $r->indeks_jabatan;
				$jabatan = substr($jabatan, strpos($jabatan, ". ") + 1);

				$golongan = $r->nama_golongan;
				$golongan = substr($golongan, strpos($golongan, ". ") + 1);

				$struktur = $r->nama_struktur;
				$struktur = substr($struktur, strpos($struktur, ". ") + 1);

				if ($r->recid_bag == 0) {
					$bagian = "-";
				} else {
					$bagian = $bagian;
				}

				if ($r->recid_jbtn == 0) {
					$jabatan = "-";
				} else {
					$jabatan = $jabatan;
				}
				$data[] = array(
					$no = $no + 1,
					$r->nik,
					$r->nama_karyawan,
					$bagian,
					$jabatan,
					$golongan,
					$r->penempatan,
					$r->tanggal,
					$r->jam_masuk,
					$r->jam_keluar,
					$r->keterangan . " - " . $r->jenis,
				);
			}

			$result = array(
				"draw" => $draw,
				"recordsTotal" => $absen->num_rows(),
				"recordsFiltered" => $absen->num_rows(),
				"data" => $data
			);

			echo json_encode($result);
			exit();
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_periode()
	{
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		$role = $this->session->userdata('role_id');
		// $jenis = array('2', '3', '5');
		$jenis_absen = "(";
		for ($i = 0; $i < count($jenis); $i++) {
			if ($i < (count($jenis) - 1)) {
				$jenis_absen .= "a.jenis_absen = '" . $jenis[$i] . "' or ";
			} else {
				$jenis_absen .= "a.jenis_absen = '" . $jenis[$i] . "'";
			}
		}
		$jenis_absen .= ")";
		if ($role == '32') {
			$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
			$bagian = "(b.indeks_hr =";
			for ($i = 0; $i < count($bag); $i++) {
				$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
				foreach ($iindex as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			$bagian .= ")";
			$query2 = $this->m_absen->absen_periode_jenis_baros($tgl_awal, $tgl_akhir, $jenis_absen);
		} else {
			$query2 = $this->m_absen->absen_periode_jenis($tgl_awal, $tgl_akhir, $jenis_absen);
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$data[] = array(
				$no = $no + 1,
				$r->nik,
				$r->nama_karyawan,
				$r->indeks_hr,
				$r->indeks_jabatan,
				$r->tanggal,
				$r->jenis . " - " . $r->keterangan,
				$r->diagnosa,
				$r->kategori,
				$r->ket_sakit,
				$r->ket,
				($r->validasi_cuti == '0') ? "Belum Validasi" : '',
				"<td><center><a href='" . base_url() . "Absen/absen_update/" . $r->absensi_recid . "'><button type='button' class='btn btn-xs btn-info'><span class='fa fa-edit'></span></button></a><a href='" . base_url() . "Absen/absen_delete/" . $r->absensi_recid . "'><button type='button' class='btn btn-xs btn-danger'><span class='fa fa-trash'></span></button></a></center></td>",
			);
		}

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query2->num_rows(),
			"recordsFiltered" => $query2->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}

	public function absen_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_absen = $this->uri->segment(3);
			/*------------ kalo cuti dan dihapus ------------------------*/
			$cek_cuti = $this->m_absen->absensi_by_recidabsen($recid_absen);
			foreach ($cek_cuti->result() as $ck) {
				$bef_jenis_absen = $ck->jenis_absen;
				$recid_karyawan = $ck->recid_karyawan;
				$tanggal = $ck->tanggal;
			}
			if ($bef_jenis_absen == '3') {
				//kembalikan cuti 1 hari
				$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
				if ($cek_cuti->num_rows() > 0) {
					foreach ($cek_cuti->result() as $c) {
						$recid_cuti = $c->recid_cuti;
						$sisa = $c->jml_cuti + 1;
						$data_cuti = array(
							'jml_cuti'		=> $sisa,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('Y-m-d h:i:s'),
						);
						$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
					}
				} else {
				}
			}

			$data_absen = array(
				'is_delete'		=> '1',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('Y-m-d h:i:s'),
			);
			$this->m_absen->edit_absensi_recid($recid_absen, $data_absen);
			// EDIT KEHADIRAN
			$edit_hadir = array(
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('Y-m-d h:i:s'),
				'recid_karyawan'	=> $recid_karyawan,
				'tanggal'			=> $tanggal,
				'jam_masuk'			=> "07:00",
				'jam_keluar'		=> "16:00",
				'status'			=> 1,
			);
			$this->m_absen->edit_hadir($recid_karyawan, $tanggal, $edit_hadir);
			redirect('Absen/absen_view');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function absen_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['absen'] = $this->m_absen->cek_absen_today_bagian($tgl, $bagian);
				$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			} else {
				$data['absen'] = $this->m_absen->cek_absen_today($tgl);
				$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			}

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function histori_cuti()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$id_cuti = $this->uri->segment(3);
			$det_cut = $this->m_absen->cuti_by_id($id_cuti);
			foreach ($det_cut->result() as $dc) {
				$recid_karyawan = $dc->recid_karyawan;
				$cuti_thn_ke = $dc->cuti_thn_ke;
			}
			$data['absen'] = $this->m_absen->histori_cuti($cuti_thn_ke, $recid_karyawan);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_histori', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_absen()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['emp'] = $this->m_absen->karyawan_view();
			$data['jenis'] = $this->m_absen->jenis_absen();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_absensi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	/* public function absen_pabsen()
{
	$logged_in = $this->session->userdata('logged_in');
	if($logged_in == 1)
	{
		$today = date('Y-m-d');
		$cek = $this->m_absen->cek_hadir_today($today);
		if($cek->num_rows() < 1)
		{
			$this->session->set_flashdata('warning','Absen Hari Ini Belum Digenerate');
			redirect('Absen/hadir_today');
		}else{
			$recid_karyawan = $this->input->post('recid_karyawan');
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_selesai = $this->input->post('tgl_selesai');
			$jenis_absen = $this->input->post('jenis_absen');
			$diagnosa = $this->input->post('diagnosa');
			$kategori = $this->input->post('kategori');
			$ket_sakit = $this->input->post('ket_sakit');
			$note = $this->input->post('note');
			
			// echo "$nik - $tgl_mulai - $tgl_selesai - $jenis_absen - $note<br>";
			
			while($tgl_mulai <= $tgl_selesai)
			{
				$nameOfDay = date('D', strtotime($tgl_mulai));
				if($nameOfDay == 'Sat' or $nameOfDay == 'Sun'){
					echo "$tgl_mulai : weekend skip<br>";
					// cek ganti hari
					$gh = $this->m_absen->gh_by_date($tgl_mulai);
					if($gh->num_rows()>=1){
						//input data
						echo "input data";
					}
				}else{
					$query = $this->m_absen->cek_cuber($tgl_mulai);;
					if($query->num_rows() > 0){
						echo "Holiday<br>";
					}else{
						echo "$tgl_mulai : save absen<br>";
						$cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_mulai);
						if($cek->num_rows() > 0){
							$tgl_work = $tgl_mulai;
							echo "data sudah ada jadi edit absen ".$tgl_mulai."<br>";
							if($jenis_absen == '1' or $jenis_absen == '4')	// Kerja atau Dinas Luar
							{
								echo "Jika sebelumnya sudah ada data absensi, dan yang barunya di edit jadi Kerja atau dinas luar, maka data absen sebelumnya di delete<br>";
								foreach ($cek->result() as $c) {
									$data_absen = array(
										'is_delete'		=> '1',
										'mdf_by'		=> $this->session->userdata('kar_id'),
										'mdf_date'		=> date('Y-m-d h:i:s'),
									); 
									$this->m_absen->edit_absensi_recid($c->absensi_recid, $data_absen);
								}
							}else if($jenis_absen != '2') // Bukan SID
							{
								echo "jika jenis absennya bukan SID, field untuk keterangan SID dikosongkan <br>";
								foreach ($cek->result() as $c) {
									$jenis_absen_lama = $c->jenis_absen;
									$valid_lama = $c->validasi_cuti;
								}
								
								$diagnosa = '';
								$kategori = '';
								$ket_sakit = '';
								echo $jenis_absen_lama;
								echo $valid_lama;
								if($jenis_absen_lama == '3' && $valid_lama == '1') // Cuti dan Sudah Validasi
								{
									echo "jika absen lama = Cuti dan validasi lama = 1, maka valiadasi tetap 1";
									$validasi_cuti = '1';
								}else if($jenis_absen == '3' and ($validasi_cuti == '0' or $validasi_cuti == null)) // cuti dan belum validasi
								{
									echo "jika cuti dan blm validasi, maka validasi jadi 1";
									$validasi_cuti = '1';
									// POTONG SISA CUTI
									$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
									if($cek_cuti->num_rows() > 0)
									{
										foreach ($cek_cuti->result() as $c) {
											$recid_cuti = $c->recid_cuti;
											echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
											$sisa = $c->jml_cuti - 1;
											echo "dikurang 1 jadi sisa : ".$sisa."<br><br>";
											
											$data_cuti = array(
												'jml_cuti'		=> $sisa,
												'mdf_by'		=> $this->session->userdata('kar_id'),
												'mdf_date'		=> date('Y-m-d h:i:s'),
											); 
											$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
										}
									}else
									{
										$jenis_absen = '5';	//P1
									}
								}else{
									$validasi_cuti = '';
								}
								$data_edit = array(
									'jenis_absen'	=> $jenis_absen,
									'diagnosa'		=> $diagnosa,
									'kategori'		=> $kategori,
									'ket_sakit'		=> $ket_sakit,
									'keterangan'	=> $note,
									'validasi_cuti'	=> $validasi_cuti,
									'mdf_by'		=> $this->session->userdata('kar_id'),
									'mdf_date'		=> date('Y-m-d h:i:s'),
								);
								$this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
							}else{
								$validasi_cuti = '';
								$data_edit = array(
									'jenis_absen'	=> $jenis_absen,
									'diagnosa'		=> $diagnosa,
									'kategori'		=> $kategori,
									'ket_sakit'		=> $ket_sakit,
									'keterangan'	=> $note,
									'validasi_cuti'	=> $validasi_cuti,
									'mdf_by'		=> $this->session->userdata('kar_id'),
									'mdf_date'		=> date('Y-m-d h:i:s'),
								);
								$this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
							}
							
						}else{
							if($jenis_absen != '2')
							{
								$diagnosa = '';
								$kategori = '';
								$ket_sakit = '';
								if($jenis_absen == '3' and ($validasi_cuti == '0' or $validasi_cuti == null)){
									$validasi_cuti = '1';
									// POTONG SISA CUTI
									$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
									if($cek_cuti->num_rows() > 0)
									{
										foreach ($cek_cuti->result() as $c) {
											$recid_cuti = $c->recid_cuti;
											// echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
											$sisa = $c->jml_cuti - 1;
											// echo "dikurang 1 jadi sisa : ".$sisa."<br><br>";
											
											$data_cuti = array(
												'jml_cuti'		=> $sisa,
												'mdf_by'		=> $this->session->userdata('kar_id'),
												'mdf_date'		=> date('Y-m-d h:i:s'),
											); 
											$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
										}
									}else
									{
										$jenis_absen = '5';	
									}
								}else{
									$validasi_cuti = '';
								}
							}else{
								$validasi_cuti = '';
							}
							$data_absen = array(
								'recid_karyawan'	=> $recid_karyawan,
								'tanggal'		=> $tgl_mulai,
								'jenis_absen'	=> $jenis_absen,
								'diagnosa'		=> $diagnosa,
								'kategori'		=> $kategori,
								'ket_sakit'		=> $ket_sakit,
								'keterangan'	=> $note,
								'validasi_cuti'	=> $validasi_cuti,
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('Y-m-d h:i:s'),
							);
							$this->m_absen->save_absensi($data_absen);
						}
					}
				}
				// EDIT KEHADIRAN
				$edit_hadir = array(
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('Y-m-d h:i:s'),
					'recid_karyawan'	=> $recid_karyawan,
					'tanggal'			=> $tgl_mulai,
					'jam_masuk'			=> null,
					'jam_keluar'		=> null,
					'status'			=> $jenis_absen,
				);
				$this->m_absen->edit_hadir($recid_karyawan, $tgl_mulai, $edit_hadir);
				$tgl_mulai= date('Y-m-d', strtotime('+1 days', strtotime($tgl_mulai))); // counter while 	
			}
			if($jenis_absen == 3)
			{
				$sisa_cuti = $this->m_absen->cek_sisa_cuti_kar($recid_karyawan);
				foreach ($sisa_cuti->result() as $sc) {
					$nama = $sc->nama_karyawan;
					$jml_cuti = $sc->jml_cuti;
				}
				$this->session->set_flashdata('sukses','Sisa Cuti '.$nama." : ".$jml_cuti);
			}
			
			redirect('Absen/absen_view');	
		}
	}
	else
	{
		redirect('Auth/keluar');
		
	}
} */

	public function absen_pabsen()
	{
		//(1) cek sudah generate absen atau belum
		$today = date('Y-m-d');
		$cek = $this->m_absen->cek_hadir_today($today);
		//jika belum
		if ($cek->num_rows() < 1) {
			//(1a) minta generate absen
			$this->session->set_flashdata('warning', 'Absen Hari Ini Belum Digenerate');
			redirect('Absen/hadir_today');
		} else {
			//(1b) lakukan proses absensi
			$recid_karyawan = $this->input->post('recid_karyawan');
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_selesai = $this->input->post('tgl_selesai');
			$jenis_absen = $this->input->post('jenis_absen');
			$diagnosa = $this->input->post('diagnosa');
			$kategori = $this->input->post('kategori');
			$ket_sakit = $this->input->post('ket_sakit');
			$note = $this->input->post('note');
			$cuti_thn_ke = $this->input->post('cuti_thn_ke');

			//(2) while (tgl_mulai <= tgl_selesai)
			while ($tgl_mulai <= $tgl_selesai) {
				//(3) cek weekend
				$nameOfDay = date('D', strtotime($tgl_mulai));

				//jika weekend
				if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
					//(3a) cek ganti hari
					$gh = $this->m_absen->gh_by_date($tgl_mulai);
					//jika ganti hari
					if ($gh->num_rows() >= 1) {
						$this->input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_thn_ke);
					}
					//jika bukan ganti hari /normal libur
					else {
						//skip
						echo "lanjut tanggal berikutnya";
					}
				}
				//jika bukan weekend
				else {
					//(3b) cek cuti bersama / holiday
					$query = $this->m_absen->cek_cuber($tgl_mulai);
					//jika libur 
					if ($query->num_rows() > 0) {
						//skip
					}
					//jika bukan hari libur
					else {
						$this->input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_thn_ke);
					}
				}
				//(4) Edit Kehadirannya
				$edit_hadir = array(
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('Y-m-d h:i:s'),
					'recid_karyawan'	=> $recid_karyawan,
					'tanggal'			=> $tgl_mulai,
					'jam_masuk'			=> null,
					'jam_keluar'		=> null,
					'status'			=> $jenis_absen,
				);
				$this->m_absen->edit_hadir($recid_karyawan, $tgl_mulai, $edit_hadir);
				//tgl_mulai = tgl_mulai + 1;
				$tgl_mulai = date('Y-m-d', strtotime('+1 days', strtotime($tgl_mulai))); // counter while
			}
			//(5) jika jenisnya cuti, tampilkan sisa cuti
			if ($jenis_absen == 3) {
				$sisa_cuti = $this->m_absen->cek_sisa_cuti_kar($recid_karyawan);
				foreach ($sisa_cuti->result() as $sc) {
					$nama = $sc->nama_karyawan;
					$jml_cuti = $sc->jml_cuti;
				}
				$this->session->set_flashdata('sukses', 'Sisa Cuti ' . $nama . " : " . $jml_cuti);
			}
			redirect('Absen/absen_view');
		}
	}

	public function input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_th_ke)
	{
		$cuti_thn_ke = 0;
		//(1) cek datanya udah ada atau belum
		$cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_mulai);
		//jika data sudah ada
		if ($cek->num_rows() > 0)
		// -------------- BLOK EDIT DATA -----------------
		{
			$tgl_work = $tgl_mulai;
			//(2) jika data barunya Kerja atau Dinas Luar
			if ($jenis_absen == '1' or $jenis_absen == '4') {
				$is_delete = '1';
				$validasi_cuti = '';
				//(3) Delete Absensi, karena dianggap hadir
				foreach ($cek->result() as $c) {
					$data_absen = array(
						'is_delete'		=> $is_delete,
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('Y-m-d h:i:s'),
					);
					$this->m_absen->edit_absensi_recid($c->absensi_recid, $data_absen);
				}
				//(4) kembalikan sisa cuti
				$cek_cuti = $this->m_absen->sisa_cuti_active($recid_karyawan);
				//(6) Kembali cuti 1
				foreach ($cek_cuti->result() as $c) {
					$recid_cuti = $c->recid_cuti;
					$jml_cuti = $c->jml_cuti;
					// echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
					$sisa = $jml_cuti + 1;
					echo "ditambah 1 jadi sisa : " . $sisa . "<br><br>";

					$data_cuti = array(
						'jml_cuti'		=> $sisa,
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('Y-m-d h:i:s'),
					);
					$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
				}
			}
			//(2) Atau jika data barunya bukan SID
			else if ($jenis_absen != '2') {
				$is_delete = '0';
				//(3) Field untuk detail SID di kosongkan
				foreach ($cek->result() as $c) {
					$jenis_absen_lama = $c->jenis_absen;
					$valid_lama = $c->validasi_cuti;
				}

				$diagnosa = '';
				$kategori = '';
				$ket_sakit = '';

				//(4) Jika absen lamanya cuti dan sudah divalidasi
				if ($jenis_absen_lama == '3' && $valid_lama == '1') {
					//(5) flag sudah validasi, tidak potong cuti
					$validasi_cuti = '1';
				}
				//(4) Jika absen lamanya cuti dan belum divaliadsi
				else if ($jenis_absen == '3' and ($valid_lama == '0' or $valid_lama == null)) {
					//(5)flag sudah validasi
					$validasi_cuti = '1';
					//(6) cek cuti karyawan
					$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
					//jika masih ada cuti
					if ($cek_cuti->num_rows() > 0) {
						//(7) potong cuti karyawan
						foreach ($cek_cuti->result() as $c) {
							$recid_cuti = $c->recid_cuti;
							echo "jumlah cuti sebelumnya : " . $c->jml_cuti . "<br>";
							$sisa = $c->jml_cuti - 1;
							echo "dikurang 1 jadi sisa : " . $sisa . "<br><br>";

							$data_cuti = array(
								'jml_cuti'		=> $sisa,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('Y-m-d h:i:s'),
							);
							$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
						}
					}
					//jika cuti habis
					else {
						//(7) edit absen sebagai P1
						$jenis_absen = '5';	//P1
					}
				}
				//(4) Atau edit absen normal, validasi = ''
				else {
					$validasi_cuti = '';
				}
			}
			//(2) Atau edit absen normal, validasi = ''
			else {
				$validasi_cuti = '';
				$is_delete = '0';
			}

			//(...)array edit absensi
			$data_edit = array(
				'jenis_absen'	=> $jenis_absen,
				'diagnosa'		=> $diagnosa,
				'kategori'		=> $kategori,
				'ket_sakit'		=> $ket_sakit,
				'keterangan'	=> $note,
				'cuti_ke'		=> $cuti_thn_ke,
				'validasi_cuti'	=> $validasi_cuti,
				'is_delete'	=> $is_delete,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('Y-m-d h:i:s'),
			);
			$this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
		}
		//jika data belum ada
		else
		// ------------- BLOK SIMPAN DATA NORMAL -----------
		{
			//(2) jika bukan SID
			if ($jenis_absen != '2') {
				//Field untuk detail SID di kosongkan
				$diagnosa = '';
				$kategori = '';
				$ket_sakit = '';

				//(3) Jika absennya Cuti 
				if ($jenis_absen == '3') {
					//(4) flag sudah validasi
					$validasi_cuti = '1';
					//(5) cek cuti karyawan
					$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
					//jika cuti masih ada
					if ($cek_cuti->num_rows() > 0) {
						//(6) Potong cuti
						foreach ($cek_cuti->result() as $c) {
							$recid_cuti = $c->recid_cuti;
							$jml_cuti = $c->jml_cuti;
							// echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
							$sisa = $jml_cuti - 1;
							echo "dikurang 1 jadi sisa : " . $sisa . "<br><br>";

							$data_cuti = array(
								'jml_cuti'		=> $sisa,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('Y-m-d h:i:s'),
							);
							$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
						}
					}
					//jika cuti habis
					else {
						//(6) jenis cuti = p1
						$jenis_absen = '5';
					}
				}	// tidak ada pasangan if else
			}
			//jika SID
			else {
				//Field SID sesuai inputan
				$diagnosa = $this->input->post('diagnosa');
				$kategori = $this->input->post('kategori');
				$ket_sakit = $this->input->post('ket_sakit');
				$validasi_cuti = '1';
			}
			//(...) Simpan Data Cuti
			$data_absen = array(
				'recid_karyawan'	=> $recid_karyawan,
				'tanggal'		=> $tgl_mulai,
				'jenis_absen'	=> $jenis_absen,
				'diagnosa'		=> $diagnosa,
				'kategori'		=> $kategori,
				'ket_sakit'		=> $ket_sakit,
				'keterangan'	=> $note,
				'cuti_ke'		=> $cuti_thn_ke,
				'validasi_cuti'	=> $validasi_cuti,
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('Y-m-d h:i:s'),
			);
			$this->m_absen->save_absensi($data_absen);
		}
	}

	public function absen_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_absen = $this->uri->segment(3);
			$det_absen = $this->m_absen->absen_by_recid($recid_absen);
			foreach ($det_absen->result() as $d) {
				$recid_karyawan = $d->recid_karyawan;
			}
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['absensi'] = $this->m_absen->absen_by_recid($recid_absen);
			$data['jenis'] = $this->m_absen->jenis_absen();
			$sisa_cuti = $this->m_absen->cek_sisa_cuti_kar($recid_karyawan);
			foreach ($sisa_cuti->result() as $sc) {
				$nama = $sc->nama_karyawan;
				$jml_cuti = $sc->jml_cuti;
			}
			$data['sisa_cuti']	= $jml_cuti;
			$cuti_thn_ke = $this->m_absen->sisa_cuti_active($recid_karyawan);
			if ($cuti_thn_ke->num_rows() > 0) {
				foreach ($cuti_thn_ke->result() as $ct) {
					$cuti_thn_ke =  $ct->cuti_thn_ke;
				}
			} else {
				$cuti_thn_ke =  0;
			}
			$data['cuti_thn_ke']	= $cuti_thn_ke;

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$absensi_recid = $this->input->post('absensi_recid');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$tgl_work = $this->input->post('tgl_mulai');
			$jenis_absen = $this->input->post('jenis_absen');
			$note = $this->input->post('note');
			$diagnosa = $this->input->post('diagnosa');
			$kategori = $this->input->post('kategori');
			$ket_sakit = $this->input->post('ket_sakit');
			$recid_absen = $this->input->post('recid_absen');
			$validasi_cuti = $this->input->post('validasi_cuti');
			$cuti_thn_ke = $this->input->post('cuti_thn_ke');
			// $cek = $this->m_absen->absen_by_recid($recid_absen);

			$shift = $this->m_absen->jenis_absen_id($jenis_absen);
			foreach ($shift->result() as $s) {
				$jam_masuk = $s->jam_in;
				$jam_keluar = $s->jam_out;
				$status = $s->recid_jenisabsen;
			}

			$cek_cuti = $this->m_absen->absensi_by_recidabsen($absensi_recid);
			foreach ($cek_cuti->result() as $ck) {
				$bef_jenis_absen = $ck->jenis_absen;
				$bef_tgl = $ck->tanggal;
			}

			if ($jenis_absen == '1' or $jenis_absen == '4' or ($jenis_absen >= 14 and $jenis_absen <= 23) or $jenis_absen == '26' or $jenis_absen == '27') {
				if ($bef_jenis_absen == '3') {
					//kembalikan cuti 1 hari
					$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
					if ($cek_cuti->num_rows() > 0) {
						foreach ($cek_cuti->result() as $c) {
							$recid_cuti = $c->recid_cuti;
							$sisa = $c->jml_cuti + 1;
							$data_cuti = array(
								'jml_cuti'		=> $sisa,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('Y-m-d h:i:s'),
							);
							$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
						}
					} else {
					}
				}
				$data_absen = array(
					'is_delete'		=> '1',
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('Y-m-d h:i:s'),
				);
				$this->m_absen->edit_absensi_recid($absensi_recid, $data_absen);
				$cek_dbl = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_work);
				if ($cek_dbl->num_rows() > 0) {
					// EDIT KEHADIRAN
					$edit_hadir = array(
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $recid_karyawan,
						'tanggal'			=> $tgl_work,
						'tgl_masuk'			=> $tgl_work,
						'tgl_pulang'		=> $tgl_work,
						'jam_masuk'			=> $jam_masuk,
						'jam_keluar'		=> $jam_keluar,
						'status'			=> $status,
						'perlu_validasi'	=> '0'
					);
					$this->m_absenbarcode->update_hadir2($recid_karyawan, $tgl_work, $edit_hadir);
				} else {
					// Simpan kehadiran
					$simpan_hadir = array(
						'crt_by'			=> $this->session->userdata('kar_id'),
						'crt_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $recid_karyawan,
						'tanggal'			=> $tgl_work,
						'tgl_masuk'			=> $tgl_work,
						'tgl_pulang'		=> $tgl_work,
						'jam_masuk'			=> $jam_masuk,
						'jam_keluar'		=> $jam_keluar,
						'status'			=> $status,
						'tmp_status'		=> $status,
						'lokasi_masuk'		=> 'Industri',
						'lokasi_pulang'		=> 'Industri',
						'perlu_validasi'	=> '0',
						'ket_validasi'		=> '',
						'flag_premi'		=> '',
						'is_closed'			=> '0'
					);
					$this->m_absenbarcode->save_absen_masuk($simpan_hadir);
				}

				if ($bef_tgl != $tgl_work) {
					// hapus data lama
					$cek_dbl = $this->m_absenbarcode->cek_double($recid_karyawan, $bef_tgl);
					if ($cek_dbl->num_rows() > 0) {
						// EDIT KEHADIRAN
						$edit_hadir = array(
							'mdf_by'			=> $this->session->userdata('kar_id'),
							'mdf_date'			=> date('Y-m-d h:i:s'),
							'is_closed'			=> '1'
						);
						$this->m_absenbarcode->update_hadir2($recid_karyawan, $bef_tgl, $edit_hadir);
					}
				}
			} else {
				/*----------------------- kalo diedit jadi cuti -------------------------------------*/
				if ($jenis_absen == '3') {
					if ($validasi_cuti != '1') {
						// POTONG SISA CUTI
						$cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
						if ($cek_cuti->num_rows() > 0) {
							foreach ($cek_cuti->result() as $c) {
								$recid_cuti = $c->recid_cuti;
								$sisa = $c->jml_cuti - 1;
								$data_cuti = array(
									'jml_cuti'		=> $sisa,
									'mdf_by'		=> $this->session->userdata('kar_id'),
									'mdf_date'		=> date('Y-m-d h:i:s'),
								);
								$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
							}
						} else {
							$jenis_absen = '5';
						}
					}
					$validasi_cuti = '1';
				} else {
					$validasi_cuti = '';
				}
				$data_absen = array(
					'tanggal'		=> $tgl_work,
					'jenis_absen'	=> $jenis_absen,
					'diagnosa'		=> $diagnosa,
					'kategori'		=> $kategori,
					'ket_sakit'		=> $ket_sakit,
					'keterangan'	=> $note,
					'cuti_ke'		=> $cuti_thn_ke,
					'validasi_cuti'	=> $validasi_cuti,
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('Y-m-d h:i:s'),
				);
				$this->m_absen->edit_absensi_recid($absensi_recid, $data_absen);


				$cek_dbl = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_work);
				if ($cek_dbl->num_rows() > 0) {
					// EDIT KEHADIRAN
					$edit_hadir = array(
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $recid_karyawan,
						'tanggal'			=> $tgl_work,
						'tgl_masuk'			=> $tgl_work,
						'tgl_pulang'		=> $tgl_work,
						'jam_masuk'			=> $jam_masuk,
						'jam_keluar'		=> $jam_keluar,
						'status'			=> $status,
						'perlu_validasi'	=> '0'
					);
					$this->m_absenbarcode->update_hadir2($recid_karyawan, $tgl_work, $edit_hadir);
				} else {
					// Simpan kehadiran
					$simpan_hadir = array(
						'crt_by'			=> $this->session->userdata('kar_id'),
						'crt_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $recid_karyawan,
						'tanggal'			=> $tgl_work,
						'tgl_masuk'			=> $tgl_work,
						'tgl_pulang'		=> $tgl_work,
						'jam_masuk'			=> $jam_masuk,
						'jam_keluar'		=> $jam_keluar,
						'status'			=> $status,
						'tmp_status'		=> $status,
						'lokasi_masuk'		=> 'Industri',
						'lokasi_pulang'		=> 'Industri',
						'perlu_validasi'	=> '0',
						'ket_validasi'		=> '',
						'flag_premi'		=> '',
						'is_closed'			=> '0'
					);
					$this->m_absenbarcode->save_absen_masuk($simpan_hadir);
				}

				if ($bef_tgl != $tgl_work) {
					// hapus data lama
					$cek_dbl = $this->m_absenbarcode->cek_double($recid_karyawan, $bef_tgl);
					if ($cek_dbl->num_rows() > 0) {
						if ($bef_tgl != $tgl_work) {
							// hapus data lama
							$cek_dbl = $this->m_absenbarcode->cek_double($recid_karyawan, $bef_tgl);
							if ($cek_dbl->num_rows() > 0) {
								// EDIT KEHADIRAN
								$edit_hadir = array(
									'mdf_by'			=> $this->session->userdata('kar_id'),
									'mdf_date'			=> date('Y-m-d h:i:s'),
									'is_closed'			=> '1'
								);
								$this->m_absenbarcode->update_hadir2($recid_karyawan, $bef_tgl, $edit_hadir);
							}
						}
					}
				}
			}


			if ($jenis_absen == 3) {
				$sisa_cuti = $this->m_absen->cek_sisa_cuti_kar($recid_karyawan);
				foreach ($sisa_cuti->result() as $sc) {
					$nama = $sc->nama_karyawan;
					$jml_cuti = $sc->jml_cuti;
				}
				$this->session->set_flashdata('sukses', 'Sisa Cuti ' . $nama . " : " . $jml_cuti);
			}
			redirect('Absen/absen_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function update_absen()	// dari selectbox admin
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hadir_recid = $this->input->post('hadir_recid');
			$tgl_work = date('Y-m-d');
			$jenis_absen = $this->input->post('jenis_absen');
			$kar = $this->m_absen->hadir_by_recid($hadir_recid);
			foreach ($kar->result() as $k) {
				$recid_karyawan = $k->recid_karyawan;
			}
			$jam = $this->m_absen->jenis_absen_id($jenis_absen);
			foreach ($jam->result() as $j) {
				if ($j->jam_in == "00:00:00") {
					$jam_masuk = null;
				} else {
					$jam_masuk = $j->jam_in;
				}

				if ($j->jam_out == "00:00:00") {
					$jam_keluar = null;
				} else {
					$jam_keluar = $j->jam_out;
				}
			}
			if ($jenis_absen == 1 or $jenis_absen == 4  or ($jenis_absen >= 14 and $jenis_absen <= 23)) /*masuk kerja*/ {
			} else {
				$cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_work);
				if ($cek->num_rows() > 0) {	/* update data yg udah ada*/
					if ($jenis_absen != '2') {
						$diagnosa = '';
						$kategori = '';
						$ket_sakit = '';
						if ($jenis_absen == '3' or $jenis_absen == '10') {
							$validasi_cuti = '0'; 	/*belum di validasi kiki*/
						} else {
							$validasi_cuti = '';
						}
					} else {
						if ($jenis_absen == '2') {
							$validasi_cuti = '0';
						} else {
							$validasi_cuti = '';
						}
					}
					$data_edit = array(
						'validasi_cuti'	=> $validasi_cuti,
						'jenis_absen'	=> $jenis_absen,
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('Y-m-d h:i:s'),
						'is_delete'		=> '0',
					);
					$this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
				} else {							/*isi baru data */
					if ($jenis_absen != '2') {
						$diagnosa = '';
						$kategori = '';
						$ket_sakit = '';
						if ($jenis_absen == '3' or $jenis_absen == '10') {
							$validasi_cuti = '0'; 	/*belum di validasi kiki*/
						} else {
							$validasi_cuti = '';
						}
					} else {
						if ($jenis_absen == '2') {
							$validasi_cuti = '0';
						} else {
							$validasi_cuti = '';
						}
					}

					$data_absen = array(
						'validasi_cuti'	=> $validasi_cuti,
						'recid_karyawan'	=> $recid_karyawan,
						'tanggal'		=> $tgl_work,
						'jenis_absen'	=> $jenis_absen,
						'crt_by'		=> $this->session->userdata('kar_id'),
						'crt_date'		=> date('Y-m-d h:i:s'),
					);
					$this->m_absen->save_absensi($data_absen);
				}
			}
			// EDIT KEHADIRAN
			$edit_hadir = array(
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('Y-m-d h:i:s'),
				'tanggal'			=> $tgl_work,
				'jam_masuk'			=> $jam_masuk,
				'jam_keluar'		=> $jam_keluar,
				'status'			=> $jenis_absen,
			);
			$result = $this->m_absen->edit_hadir2($hadir_recid, $edit_hadir);
			// redirect('Absen/absen_view');
			echo json_encode($result);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function admin_bagian()
	{
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['bagian'] = $this->m_absen->admin_view();
		$data['karyawan'] = $this->m_absen->karyawan_view();
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('role/admin_bagian');
		$this->load->view('layout/a_footer');
	}

	public function update_admin_bagian()
	{
		$recid_bag = $this->input->post('recid_bag');
		$recid_karyawan = $this->input->post('recid_karyawan');
		$cek_admin = $this->m_absen->cek_admin_bagian($recid_bag);
		if ($cek_admin->num_rows() > 0) {
			//update
			foreach ($cek_admin->result() as $c) {
				$data = array(
					'mdf_by'		=> $this->session->userdata('recid_karyawan'),
					'mdf_date'		=> date('Y-m-d h:i:s'),
					'recid_bag'		=> $recid_bag,
					'recid_karyawan'		=> $recid_karyawan,
				);
				$this->m_absen->update_admin($c->recid_data, $data);
			}
		} else {
			//save
			$data = array(
				'crt_by'		=> $this->session->userdata('recid_karyawan'),
				'crt_date'		=> date('Y-m-d h:i:s'),
				'recid_bag'		=> $recid_bag,
				'recid_karyawan' => $recid_karyawan,
			);
			$this->m_absen->save_admin($data);
		}

		echo json_encode("ok");
		exit();
	}

	public function rekap_admin_closing()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			// $tgl = '2022-01-24';
			$clos = '0';
			$data['menu'] = "Bagian Belum Closing Absensi $tgl";
			$data['boo'] = "Belum";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['rekap'] = $this->m_absen->rekap_closing_admin($tgl, $clos);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_admin_closing', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_admin_open()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$clos = '1';
			$data['menu'] = "Bagian Sudah Closing Absensi $tgl";
			$data['boo'] = "Sudah";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['rekap'] = $this->m_absen->rekap_closing_admin($tgl, $clos);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_admin_closing', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_closing()
	{
		$tgl = $this->input->post('tgl_mulai');
		$clos = '0';
		$query2 = $this->m_absen->rekap_closing_admin($tgl, $clos);
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$i = 0;
		foreach ($query2->result() as $r) {

			$data[] = array(
				$i = $i + 1,
				$r->indeks_hr,
				$r->nama_karyawan . "<input type='hidden' id='bagian$i' value='$r->indeks_hr'>",
				"<button class='btn btn-success' value='Close' onclick='getData($i)'>Close Absen</button>",
			);
		}

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query2->num_rows(),
			"recordsFiltered" => $query2->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}

	public function get_open()
	{
		$tgl = $this->input->post('tgl_mulai');
		$clos = '1';
		$query2 = $this->m_absen->rekap_closing_admin($tgl, $clos);
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$i = 0;
		foreach ($query2->result() as $r) {

			$data[] = array(
				$i = $i + 1,
				$r->indeks_hr,
				$r->nama_karyawan . "<input type='hidden' id='bagian$i' value='$r->indeks_hr'>",
			);
		}

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query2->num_rows(),
			"recordsFiltered" => $query2->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}

	public function detail_kehadiran()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = $this->uri->segment(3);
			$status = $this->uri->segment(4);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['status'] = $status;

			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}
			$non_shift = "(status = '1')";
			$hadir_baros = "(status = '1' or status = '14' or status = '15' or status= '16')";
			$wfh = "(status = '8')";
			$shift = "(status between 14 and 23)";
			$cuti = "(status ='3' or status = '7' or status = '12')";
			$sakit = "(status = '2')";
			$lainnya = "(status between 4 and 6) or (status between 9 and 11) or (status = 13) or (status = 24 or status = 25)";
			$hadir_spm = "(status = '22' or status = '23')";
			$tidak_hadir_spm = "(status != '22' and status != '23')";
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all($tgl);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $wfh);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $shift);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $cuti);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $sakit);
				} else if ($status == "baros") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $hadir_baros);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $lainnya);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status($tgl, $non_shift);
				}
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_karyawan_baros($tgl);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $wfh);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $shift);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $cuti);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $sakit);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $lainnya);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_baros($tgl, $non_shift);
				}
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all_bagian($tgl, $bagian);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				}
			} else if ($role == '23') {
				$recid_karyawan = $this->session->userdata('kar_id');
				$bagian = "(b.indeks_hr =";
				$bag = array();
				if ($tingkatan > '7') {
					$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$bagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$bagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
					// echo $bagian;
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					$cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all_bagian($tgl, $bagian);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				}
			} else if ($role == '37') {
				$recid_karyawan = $this->session->userdata('kar_id');
				/* $bagian = "(b.indeks_hr =";
				$bag = array();
				$bgn = $this->m_hris->karyawan_view_by_dept_group($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for($b=0;$b<count($bag);$b++)
					{
						$no = $no + 1;
						if ($no < count($bag)) {
								$bagian .= "b.indeks_hr = '$bag[$b]' or ";
							} else {
								$bagian .= "b.indeks_hr = '$bag[$b]'";
							}
					}
					// echo $bagian;
				$bagian .= ")"; */
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all_dept_group($tgl, $dept_group);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $wfh, $dept_group);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $shift, $dept_group);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $cuti, $dept_group);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $sakit, $dept_group);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $lainnya, $dept_group);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_dept_group($tgl, $non_shift, $dept_group);
				}
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all_bagian($tgl, $bagian);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $wfh, $bagian);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $shift, $bagian);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $cuti, $bagian);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $sakit, $bagian);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $lainnya, $bagian);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_bagian($tgl, $non_shift, $bagian);
				}
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/detail_dashboard', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function detail_kehadiran_spm()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = $this->uri->segment(3);
			$status = $this->uri->segment(4);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['status'] = $status;

			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$non_shift = "(status = '1')";
			$wfh = "(status = '8')";
			$shift = "(status between 14 and 23)";
			$cuti = "(status ='3' or status = '7' or status = '12')";
			$sakit = "(status = '2')";
			$lainnya = "(status between 4 and 6) or (status between 9 and 11) or (status = 13) or (status = 24)";
			$hadir_spm = "(status = '22' or status = '23')";
			$tidak_hadir_spm = "(status != '22' and status != '23')";
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_spm($tgl);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $wfh);
				} else if ($status == "hadir_spm") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $hadir_spm);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $tidak_hadir_spm);
				}
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_spm($tgl);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $wfh);
				} else if ($status == "hadir_spm") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $hadir_spm);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $tidak_hadir_spm);
				}
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_all_bagian_spm($tgl, $bagian);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
				} else if ($status == "shift") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $shift, $bagian);
				} else if ($status == "cuti") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $cuti, $bagian);
				} else if ($status == "sakit") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $sakit, $bagian);
				} else if ($status == "lainnya") {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $lainnya, $bagian);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_bagian_spm($tgl, $non_shift, $bagian);
				}
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'All') {
					$data['absen'] = $this->m_absen->jml_spm($tgl);
				} else if ($status == "wfh") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $wfh);
				} else if ($status == "hadir_spm") {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $hadir_spm);
				} else {
					$data['absen'] = $this->m_absen->jml_by_status_spm($tgl, $tidak_hadir_spm);
				}
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/detail_dashboard', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	function upload_izin()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/izin/upload_izin', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	function import_izin()
	{

		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if (isset($_FILES['file_exc']['name']) && in_array($_FILES['file_exc']['type'], $file_mimes)) {
			$arr_file = explode('.', $_FILES['file_exc']['name']);
			$extension = end($arr_file);
			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} elseif ('xls' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($_FILES['file_exc']['tmp_name']);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$jml_data = count($sheetData);
			$jml_data = $jml_data - 1;
			echo "jumlah data = " . $jml_data . "<br>";
			if (!empty($sheetData)) {
				for ($i = 1; $i < count($sheetData); $i++) {
					$nik = $sheetData[$i][1];
					$cek = $this->m_hris->karyawan_by_nik($nik);
					foreach ($cek as $k) {
						$recid_karyawan = $k->recid_karyawan;
					}
					$tanggal = $sheetData[$i][3];
					$jenis_izin = $sheetData[$i][4];
					$jam_masuk = $sheetData[$i][5];
					$jam_keluar = $sheetData[$i][6];
					$keterangan = $sheetData[$i][7];
					echo "emp : $recid_karyawan <br> tanggal :  $tanggal <br> jenis : $jenis_izin <br> jam_msk : $jam_masuk <br> jam_keluar : $jam_keluar <br> ket : $keterangan <br><br><br>";

					$izin = $this->m_absen->cek_duplikat_izin($tanggal, $recid_karyawan, $jenis_izin);
					if ($izin->num_rows() < 1) {
						if ($jenis_izin == 'Keluar') {
							$jam_in = $jam_masuk;
							$jam_out = $jam_keluar;
							$cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $recid_karyawan and tanggal = '$tanggal'");
							foreach ($cek->result() as $c) {
								$status = $c->jenis . " - " . $c->keterangan;
								$jam_msk = $c->jam_in;
							}
							$jm = intval(substr($jam_msk, 0, 2));
							$mnt = substr($jam_msk, 2, 3);
							$jam_awal = ($jm + 4) . $mnt;
							$jam_akhir = ($jm + 5) . $mnt;

							if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_awal);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = ($selisih->format('%h')) - 1;
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
								$over_durasi = "0.0";
							} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
								//jam_masuk - jam keluar (12:30)
								$out = new DateTime("12:30:00");
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							}
						} else {
							$over_durasi = "";
						}
						$data_izin = array(
							'crt_date'          => date('Y-m-d H:i:s'),
							'crt_by'            => $this->session->userdata('kar_id'),
							'tgl_izin'          => $tanggal,
							'recid_karyawan'    => $recid_karyawan,
							'jenis'             => $jenis_izin,
							'jam_in'            => $jam_masuk,
							'jam_out'           => $jam_keluar,
							'over_durasi'		=> $over_durasi,
							'keterangan'        => $keterangan
						);
						$this->m_absen->save_data('izin', $data_izin);
					} else {
						if ($jenis_izin == 'Keluar') {
							$jam_in = $jam_masuk;
							$jam_out = $jam_keluar;
							$cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $nik and tanggal = '$tanggal'");
							foreach ($cek->result() as $c) {
								$status = $c->jenis . " - " . $c->keterangan;
								$jam_msk = $c->jam_in;
							}
							$jm = intval(substr($jam_msk, 0, 2));
							$mnt = substr($jam_msk, 2, 3);
							$jam_awal = ($jm + 4) . $mnt;
							$jam_akhir = ($jm + 5) . $mnt;

							if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit; //
							} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_awal);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = ($selisih->format('%h')) - 1;
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
								$over_durasi = "0.0";
							} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
								//jam_masuk - jam keluar (12:30)
								$out = new DateTime("12:30:00");
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							} else {
								$out = new DateTime($jam_out);
								$in = new DateTime($jam_in);
								$selisih = $in->diff($out);
								$jam = $selisih->format('%h');
								$menit = $selisih->format('%i');
								if ($menit >= 0 && $menit <= 9) {
									$menit = "0" . $menit;
								}
								$hasil = $jam . " jam " . $menit . ' menit';
								$over_durasi = $jam . '.' . $menit;
							}
						} else {
							$over_durasi = "";
						}

						$data_izin = array(
							'mdf_date'          => date('Y-m-d H:i:s'),
							'mdf_by'            => $this->session->userdata('kar_id'),
							'tgl_izin'          => $tanggal,
							'recid_karyawan'    => $recid_karyawan,
							'jenis'             => $jenis_izin,
							'jam_in'            => $jam_masuk,
							'jam_out'            => $jam_keluar,
							'over_durasi'        => $over_durasi,
							'keterangan'        => $keterangan
						);
						$this->m_absen->edit_izin2($data_izin, $recid_karyawan, $tanggal, $jenis_izin);
					}
				}
			}
		}
		redirect('Absen/izin');
	}

	public function detail_izin()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = $this->uri->segment(3);
			$status = $this->uri->segment(4);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['status'] = $status;

			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$bagian = $user->indeks_hr;
			}
			$terlambat = "(jenis = 'Terlambat')";
			$keluar = "(jenis = 'Keluar')";
			$pulang = "(jenis = 'Pulang')";
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				if ($status == 'terlambat') {
					$data['absen'] = $this->m_absen->izin_by_status($tgl, $terlambat);
				} else if ($status == "keluar") {
					$data['absen'] = $this->m_absen->izin_by_status($tgl, $keluar);
				} else {
					$data['absen'] = $this->m_absen->izin_by_status($tgl, $pulang);
				}
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'terlambat') {
					$data['absen'] = $this->m_absen->izin_by_status_baros($tgl, $terlambat);
				} else if ($status == "keluar") {
					$data['absen'] = $this->m_absen->izin_by_status_baros($tgl, $keluar);
				} else {
					$data['absen'] = $this->m_absen->izin_by_status_baros($tgl, $pulang);
				}
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'terlambat') {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $terlambat, $bagian);
				} else if ($status == "keluar") {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $keluar, $bagian);
				} else {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $pulang, $bagian);
				}
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				if ($status == 'terlambat') {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $terlambat, $bagian);
				} else if ($status == "keluar") {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $keluar, $bagian);
				} else {
					$data['absen'] = $this->m_absen->izin_by_status_bagian($tgl, $pulang, $bagian);
				}
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/detail_dashboard2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function r_bulan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			if ($role == '30' or $role == '26') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_bulan_adm";
			} else if ($role == '23' or $role == '37') {
				$data['dept_group'] = $this->m_absen->dept_group_user($usr);
				$data['department'] = $this->m_absen->dept_user($usr);
				$content = "absen/new_report/r_bulan_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_bulan";
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			$data['tahun'] = $this->m_absen->tahun_hk();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function persentase()
	{

		$usr = $this->session->userdata('kar_id');
		$month_name = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		$cek = $this->m_absen->cek_hadir_bulan_tahun($bulan, $tahun);
		if ($cek->num_rows() > 0) {
			// $norma = $this->input->post('norma');
			$norma = array(3, 12, 25);
			$jenis = $this->m_absen->jenis_tidak_masuk();
			$jenis_absen = array();
			foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
				if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
					array_push($jenis_absen, $j->recid_jenisabsen);
				}
			}
			$non_norma = array_diff($jenis_absen, $norma);
			$non_norma = array_values($non_norma);
			$hk = $this->m_absen->hk_by_bulan($tahun, $bulan);
			foreach ($hk->result() as $hk) {
				$jml_hk = $hk->jml_hk;
			}

			$jml_norma = "( status =";
			$jenis_ab = "( recid_jenisabsen = ";
			for ($n = 0; $n < count($norma); $n++) {
				if ($n < (count($norma) - 1)) {
					$jml_norma .= "'" . $norma[$n] . "' or status = ";
					$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
				} else {
					$jml_norma .= "'" . $norma[$n] . "'";
					$jenis_ab .= "'" . $norma[$n] . "'";
				}
			}
			$jml_norma .= " )";
			$jenis_ab .= " )";

			$detail_norma = "Norma : ";
			$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
			$cdnorma = $dnorma->num_rows();
			$no = 0;
			foreach ($dnorma->result() as $dn) {
				$no = $no + 1;
				if ($no < $cdnorma) {
					$detail_norma .= $dn->jenis . ", ";
				} else {
					$detail_norma .= $dn->jenis;
				}
			}

			$jml_nonnorma = "( status =";
			$jenis_abn = "( recid_jenisabsen = ";

			for ($nn = 0; $nn < count($non_norma); $nn++) {
				if ($nn < (count($non_norma) - 1)) {
					$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
					$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
				} else {
					$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
					$jenis_abn .= "'" . $non_norma[$nn] . "'";
				}
			}
			$jml_nonnorma .= " )";
			$jenis_abn .= " )";

			$detail_nnorma = "Non Norma : ";
			$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
			$cdnnorma = $dnnorma->num_rows();
			$no = 0;
			foreach ($dnnorma->result() as $dnn) {
				$no = $no + 1;
				if ($no < $cdnnorma) {
					$detail_nnorma .= $dnn->jenis . ", ";
				} else {
					$detail_nnorma .= $dnn->jenis;
				}
			}

			$fdivisi = array();
			$divisi = $this->input->post('divisi');
			if (!empty($divisi)) {
				for ($i = 0; $i < count($divisi); $i++) {
					array_push($fdivisi, $divisi[$i]);
				}
			} else {
				$divisi = $this->m_hris->list_dept_group();
				foreach ($divisi->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			}


			$fdepartment = "";
			$department = $this->input->post('departement');
			if (!empty($department)) {
				$cnt = count($department);
				$fdepartment .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fdepartment .= "d.nama_department = '$department[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fdepartment .= "d.nama_department = '$department[$i]' or ";
						} else {
							$fdepartment .= "d.nama_department = '$department[$i]'";
						}
					}
				}
				$fdepartment .= ")";
			} else {
				// $fdepartment = $fdepartment;
				$department = $this->m_hris->list_department();
				$fdepartment .= " and (";
				$no = 0;
				$cnt = $department->num_rows();
				foreach ($department->result() as $d) {
					$no = $no + 1;
					if ($cnt == 1) {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					} else {
						if ($no + 1 <= $cnt) {
							$fdepartment .= "d.nama_department = '$d->nama_department' or ";
						} else {
							$fdepartment .= "d.nama_department = '$d->nama_department'";
						}
					}
				}
				$fdepartment .= ")";
			}


			$fbagian = "";
			$bagian = $this->input->post('bagian10');
			if (!empty($bagian)) {
				$cnt = count($bagian);
				$fbagian .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fbagian .= "b.recid_bag = '$bagian[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
						} else {
							$fbagian .= "b.recid_bag = '$bagian[$i]'";
						}
					}
				}
				$fbagian .= ")";
			} else {
				$fbagian = $fbagian;
				// $bagian = $this->m_hris->list_bagian();
				// $fbagian .= " and (";
				// $no = 0;
				// $cnt = $bagian->num_rows();
				// foreach ($bagian->result() as $d) {
				// 	$no = $no + 1;
				// 	if ($cnt == 1) {
				// 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
				// 	} else {
				// 		if ($no + 1 <= $cnt) 
				// 		{
				// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
				// 		} else {
				// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
				// 		}
				// 	}
				// }
				// $fbagian .= ")";
			}
			// echo $fbagian;

			$fkaryawan = "";
			$karyawan = $this->input->post('karyawan');
			if (!empty($karyawan)) {
				$cnt = count($karyawan);
				$fkaryawan .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
						} else {
							$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
						}
					}
				}
				$fkaryawan .= ")";
			} else {
				$fkaryawan = $fkaryawan;
			}
			// echo $fkaryawan;

			$data['norma'] = $jml_norma;
			$data['non_norma'] = $jml_nonnorma;
			// $data['dept'] = $this->m_hris->list_dept_group();
			$data['fdivisi'] = $fdivisi;
			$data['divisi'] = $divisi;
			$data['fdepartment'] = $fdepartment;
			$data['fbagian'] = $fbagian;
			$data['fkaryawan'] = $fkaryawan;
			$data['jml_hkn'] = $jml_hk;
			$data['nama_bulan'] = $month_name[$bulan - 1];
			$data['tahun'] = $this->input->post('tahun');
			$data['bulan'] = $this->input->post('bulan');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['detail_norma'] = $detail_norma;
			$data['detail_nnorma'] = $detail_nnorma;


			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_persentase_bulanan', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Data Untuk Bulan Ini Belum Ada";
		}
	}

	public function persentase_adm()
	{

		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$month_name = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$dept_group = $user->dept_group;
		}
		$cek = $this->m_absen->cek_hadir_bulan_tahun($bulan, $tahun);
		if ($cek->num_rows() > 0) {
			// $norma = $this->input->post('norma');
			$norma = array(3, 12, 25);
			$jenis = $this->m_absen->jenis_tidak_masuk();
			$jenis_absen = array();
			foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
				if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
					array_push($jenis_absen, $j->recid_jenisabsen);
				}
			}
			$non_norma = array_diff($jenis_absen, $norma);
			$non_norma = array_values($non_norma);
			$hk = $this->m_absen->hk_by_bulan($tahun, $bulan);
			foreach ($hk->result() as $hk) {
				$jml_hk = $hk->jml_hk;
			}

			$jml_norma = "( status =";
			$jenis_ab = "( recid_jenisabsen = ";
			for ($n = 0; $n < count($norma); $n++) {
				if ($n < (count($norma) - 1)) {
					$jml_norma .= "'" . $norma[$n] . "' or status = ";
					$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
				} else {
					$jml_norma .= "'" . $norma[$n] . "'";
					$jenis_ab .= "'" . $norma[$n] . "'";
				}
			}
			$jml_norma .= " )";
			$jenis_ab .= " )";

			$detail_norma = "Norma : ";
			$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
			$cdnorma = $dnorma->num_rows();
			$no = 0;
			foreach ($dnorma->result() as $dn) {
				$no = $no + 1;
				if ($no < $cdnorma) {
					$detail_norma .= $dn->jenis . ", ";
				} else {
					$detail_norma .= $dn->jenis;
				}
			}

			$jml_nonnorma = "( status =";
			$jenis_abn = "( recid_jenisabsen = ";

			for ($nn = 0; $nn < count($non_norma); $nn++) {
				if ($nn < (count($non_norma) - 1)) {
					$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
					$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
				} else {
					$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
					$jenis_abn .= "'" . $non_norma[$nn] . "'";
				}
			}
			$jml_nonnorma .= " )";
			$jenis_abn .= " )";

			$detail_nnorma = "Non Norma : ";
			$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
			$cdnnorma = $dnnorma->num_rows();
			$no = 0;
			foreach ($dnnorma->result() as $dnn) {
				$no = $no + 1;
				if ($no < $cdnnorma) {
					$detail_nnorma .= $dnn->jenis . ", ";
				} else {
					$detail_nnorma .= $dnn->jenis;
				}
			}


			$fdivisi = array();
			if (!empty($divisi)) {
				for ($i = 0; $i < count($divisi); $i++) {
					array_push($fdivisi, $divisi[$i]);
				}
			} else {
				if ($role == "23") {
					$divisi = $this->m_absen->dept_group_user($usr);
					foreach ($divisi->result() as $dv) {
						array_push($fdivisi, $dv->dept_group);
					}
				} else if ($role == '37') {
					$divisi = $this->m_absen->dept_group_user($usr);
					array_push($fdivisi, $dept_group);
				} else {
					$divisi = $this->m_absen->dept_group_admin($usr);
					foreach ($divisi->result() as $dv) {
						array_push($fdivisi, $dv->dept_group);
					}
				}
			}



			$fdepartment = "";
			$department = $this->input->post('departement');
			if (!empty($department)) {
				$cnt = count($department);
				$fdepartment .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fdepartment .= "d.nama_department = '$department[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fdepartment .= "d.nama_department = '$department[$i]' or ";
						} else {
							$fdepartment .= "d.nama_department = '$department[$i]'";
						}
					}
				}
				$fdepartment .= ")";
			} else {
				if ($role == "23") {
					$department = $this->m_absen->dept_user($usr);
				} else if ($role == '37') {
					$department = $this->m_absen->dept_by_dept_group($dept_group);
				} else {
					$department = $this->m_absen->dept_admin($usr);
				}
				$cnt = $department->num_rows();
				$fdepartment .= " and (";
				foreach ($department->result() as $dept) {
					if ($cnt == 1) {
						$fdepartment .= "d.nama_department = '$dept->department'";
					} else {
						if ($i + 1 < $cnt) {
							$fdepartment .= "d.nama_department = '$dept->department' or ";
						} else {
							$fdepartment .= "d.nama_department = '$dept->department'";
						}
					}
				}
				$fdepartment .= ")";
			}


			$fbagian = "";
			$bagian = $this->input->post('bagian10');
			if (!empty($bagian)) {
				$cnt = count($bagian);
				$fbagian .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fbagian .= "b.recid_bag = '$bagian[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
						} else {
							$fbagian .= "b.recid_bag = '$bagian[$i]'";
						}
					}
				}
				$fbagian .= ")";
			} else {
				// $fbagian = $fbagian;
				$fbagian .= " and (";
				$bag = array();
				if ($role == '23') {
					if ($tingkatan > 7) {
						$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
						$no = 0;
						foreach ($bgn->result() as $bg) {
							if (array_key_exists($bg->indeks_hr, $bag)) {
								// echo "Key exists!";
							} else {
								array_push($bag, $bg->indeks_hr);
							}
						}
						// print_r($bag);
						for ($b = 0; $b < count($bag); $b++) {
							$no = $no + 1;
							if ($no < count($bag)) {
								$fbagian .= "b.indeks_hr = '$bag[$b]' or ";
							} else {
								$fbagian .= "b.indeks_hr = '$bag[$b]'";
							}
						}
					} else {
						$bgn = $this->m_hris->karyawan_view_by_id($usr);
						$no = 0;
						foreach ($bgn->result() as $bg) {
							$no = $no + 1;
							if (array_key_exists($bg->recid_bag, $bag)) {
								// echo "Key exists!";
							} else {
								array_push($bag, $bg->recid_bag);
								if ($no < $cnt) {
									$fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
								} else {
									$fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
								}
							}
						}
					}
				} else if ($role == '37') {
					$bgn = $this->m_hris->karyawan_view_by_dept_group($dept_group);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$fbagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$fbagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
				$fbagian .= ")";
			}
			// echo $fbagian;

			$fkaryawan = "";
			$karyawan = $this->input->post('karyawan');
			if (!empty($karyawan)) {
				$cnt = count($karyawan);
				$fkaryawan .= " and (";
				for ($i = 0; $i < $cnt; $i++) {
					if ($cnt == 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
					} else {
						if ($i + 1 < $cnt) {
							$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
						} else {
							$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
						}
					}
				}
				$fkaryawan .= ")";
			} else {
				$fkaryawan = $fkaryawan;
			}
			// echo $fkaryawan;

			$data['norma'] = $jml_norma;
			$data['non_norma'] = $jml_nonnorma;
			// $data['dept'] = $this->m_hris->list_dept_group();
			$data['fdivisi'] = $fdivisi;
			$data['divisi'] = $divisi;
			$data['fdepartment'] = $fdepartment;
			$data['fbagian'] = $fbagian;
			$data['fkaryawan'] = $fkaryawan;
			$data['jml_hkn'] = $jml_hk;
			$data['nama_bulan'] = $month_name[$bulan - 1];
			$data['tahun'] = $this->input->post('tahun');
			$data['bulan'] = $this->input->post('bulan');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['detail_norma'] = $detail_norma;
			$data['detail_nnorma'] = $detail_nnorma;


			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_persentase_bulanan', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Data Untuk Bulan Ini Belum Ada";
		}
	}

	public function r_minggu()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			$data['tahun'] = $this->m_absen->tahun_hk();
			if ($role == '30' or $role == '26') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_minggu_adm";
			} else if ($role == '23' or $role == '37') {
				$data['dept_group'] = $this->m_absen->dept_group_user($usr);
				$data['department'] = $this->m_absen->dept_user($usr);
				$content = "absen/new_report/r_minggu_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_minggu";
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function persentase_mingguan()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai =  $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_t = $mulai;
		$sampai_t = $sampai;
		// $mulai_t = new DateTime($mulai);
		// $sampai_t = new DateTime($sampai);
		// $jml_hari = $sampai_t->diff($mulai_t)->days + 1;

		// echo $jml_hari;
		/* 
	$plushk = $this->m_absen->gh_by_tanggal($mulai, $sampai);
	$gh = $plushk->num_rows();
	
	$libur = $this->m_absen->cuber_by_tanggal2($mulai, $sampai);
	$minhk = $libur->num_rows();
		$date_start = $mulai;
		$date_end = $sampai;

		$selisih = '86400'; //selisih strtotime untuk satu hari

		$time_start = strtotime($date_start);
		$time_end = strtotime($date_end);
		$sm = 0;
		for ($i = $time_start; $i <= $time_end; $i = $i + $selisih) {
			//pengecekan apakah tanggal tersebut hari sabtu atau minggu 
			if ((date('D', $i) == 'Sun') or (date('D', $i) == 'Sat')) {
				$sm = $sm+1;
				//$data[] = date('Y-m-d', $i); //hasil dalam bentuk array
			}
		}

	$jml_hk = $jml_hari - $sm - $minhk + $gh; */
		$jml_hari = 0;
		while ($mulai_t <= $sampai_t) {
			$hari = date('l', strtotime($mulai_t));
			if ($hari == 'Saturday' or $hari == 'Sunday') {
				// cek weekend ganti hari ot not
				$ganti_hari = $this->m_absen->gh_by_date($mulai_t);
				if ($ganti_hari->num_rows() == 1) {
					$jml_hari = $jml_hari + 1;
					// echo "Ganti Hari Di ". $hari . "<br>";
				} else {
					// echo $hari."<br>";s
				}
			} else {
				$libur = $this->m_absen->libur_by_date($mulai_t);
				if ($libur->num_rows() == 1) {
					// echo "libur tgl ".$mulai_t."<br>";
				} else {
					$jml_hari = $jml_hari + 1;
					// echo $mulai_t . "<br>";
				}
			}
			$mulai_t = date('Y-m-d', strtotime('+1 days', strtotime($mulai_t)));
		}
		// echo $jml_hari;
		$jml_hk = $jml_hari;

		// $norma = $this->input->post('norma');
		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			$divisi = $this->m_hris->list_dept_group();
			foreach ($divisi->result() as $dv) {
				array_push($fdivisi, $dv->dept_group);
			}
		}


		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			// $fdepartment = $fdepartment;
			$department = $this->m_hris->list_department();
			$fdepartment .= " and (";
			$no = 0;
			$cnt = $department->num_rows();
			foreach ($department->result() as $d) {
				$no = $no + 1;
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$d->nama_department'";
				} else {
					if ($no + 1 <= $cnt) {
						$fdepartment .= "d.nama_department = '$d->nama_department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
			// $bagian = $this->m_hris->list_bagian();
			// $fbagian .= " and (";
			// $no = 0;
			// $cnt = $bagian->num_rows();
			// foreach ($bagian->result() as $d) {
			// 	$no = $no + 1;
			// 	if ($cnt == 1) {
			// 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 	} else {
			// 		if ($no + 1 <= $cnt) 
			// 		{
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
			// 		} else {
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 		}
			// 	}
			// }
			// $fbagian .= ")";
		}
		// echo $fbagian;


		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			// $text = "$text and b.recid_bag = '$bagian'";
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			if ($cnt == 1) {
				$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['mulai'] = $mulai;
		$data['sampai'] = $sampai;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persen_absen_mingguan', $data);
		$this->load->view('layout/a_footer');
	}

	public function persentase_mingguan_adm()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai =  $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_t = $mulai;
		$sampai_t = $sampai;
		// $jml_hari = $sampai_t->diff($mulai_t)->days + 1;
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$dept_group = $user->dept_group;
		}
		// echo $jml_hari;
		$jml_hari = 0;
		while ($mulai_t <= $sampai_t) {
			$hari = date('l', strtotime($mulai_t));
			if ($hari == 'Saturday' or $hari == 'Sunday') {
				// cek weekend ganti hari ot not
				$ganti_hari = $this->m_absen->gh_by_date($mulai_t);
				if ($ganti_hari->num_rows() == 1) {
					$jml_hari = $jml_hari + 1;
					// echo "Ganti Hari Di ". $hari . "<br>";
				} else {
					// echo $hari."<br>";s
				}
			} else {
				$libur = $this->m_absen->libur_by_date($mulai_t);
				if ($libur->num_rows() == 1) {
					// echo "libur tgl ".$mulai_t."<br>";
				} else {
					$jml_hari = $jml_hari + 1;
					// echo $mulai_t . "<br>";
				}
			}
			$mulai_t = date('Y-m-d', strtotime('+1 days', strtotime($mulai_t)));
		}
		// echo $jml_hari;
		$jml_hk = $jml_hari;


		// $norma = $this->input->post('norma');
		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}


		$fdivisi = array();
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			if ($role == "23") {
				$divisi = $this->m_absen->dept_group_user($usr);
				foreach ($divisi->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			} else if ($role == '37') {
				$divisi = $this->m_absen->dept_group_user($usr);
				array_push($fdivisi, $dept_group);
			} else {
				$divisi = $this->m_absen->dept_group_admin($usr);
				foreach ($divisi->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			}
		}



		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			if ($role == "23") {
				$department = $this->m_absen->dept_user($usr);
			} else if ($role == '37') {
				$department = $this->m_absen->dept_by_dept_group($dept_group);
			} else {
				$department = $this->m_absen->dept_admin($usr);
			}
			$cnt = $department->num_rows();
			$fdepartment .= " and (";
			foreach ($department->result() as $dept) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$dept->department'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$dept->department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$dept->department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			// $fbagian = $fbagian;
			$fbagian .= " and (";
			$bag = array();
			if ($role == '23') {
				if ($tingkatan > '7') {
					$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$fbagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$fbagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
			} else if ($role == '37') {
				$bgn = $this->m_hris->karyawan_view_by_dept_group($dept_group);
				$no = 0;
				foreach ($bgn->result() as $bg) {
					if (array_key_exists($bg->indeks_hr, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->indeks_hr);
					}
				}
				// print_r($bag);
				for ($b = 0; $b < count($bag); $b++) {
					$no = $no + 1;
					if ($no < count($bag)) {
						$fbagian .= "b.indeks_hr = '$bag[$b]' or ";
					} else {
						$fbagian .= "b.indeks_hr = '$bag[$b]'";
					}
				}
			} else {
				$bgn = $this->m_hris->karyawan_view_by_id($usr);
				$no = 0;
				foreach ($bgn->result() as $bg) {
					$no = $no + 1;
					if (array_key_exists($bg->recid_bag, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->recid_bag);
						if ($no < $cnt) {
							$fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
						} else {
							$fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
						}
					}
				}
			}
			$fbagian .= ")";
		}
		// echo $fbagian;

		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}
		// echo $fkaryawan;

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['mulai'] = $mulai;
		$data['sampai'] = $sampai;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persen_absen_mingguan', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_semester()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			$data['tahun'] = $this->m_absen->tahun_hk();
			if ($role == '30') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_semester_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_semester";
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function persentase_semester()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai = $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_tb = explode(" ", $mulai);
		$mulai_t = $mulai_tb[1];
		$mulai_b = $mulai_tb[0];
		$mulai_b = date("m", strtotime($mulai));
		$sampai_tb = explode(" ", $sampai);
		$sampai_t = $sampai_tb[1];
		$sampai_b = $sampai_tb[0];
		$sampai_b = date("m", strtotime($sampai));
		// echo $mulai_t." - ".$sampai_t;

		$where = "";
		if ($mulai_t == $sampai_t) {
			$where = "tahun = $mulai_t and bulan between $mulai_b and $sampai_b";
		} else {
			$where = "(tahun = $mulai_t and bulan between $mulai_b and 12) or (tahun = $sampai_t and bulan between 1 and $sampai_b)";
		}

		// echo $where;
		$jml_hk = $this->m_absen->hk_by_periode($where);
		foreach ($jml_hk->result() as $hk) {
			$jml_hk = $hk->jml_hk;
		}
		// echo $jml_hk;

		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			$divisi = $this->m_hris->list_dept_group();
			foreach ($divisi->result() as $dv) {
				array_push($fdivisi, $dv->dept_group);
			}
		}


		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			// $fdepartment = $fdepartment;
			$department = $this->m_hris->list_department();
			$fdepartment .= " and (";
			$no = 0;
			$cnt = $department->num_rows();
			foreach ($department->result() as $d) {
				$no = $no + 1;
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$d->nama_department'";
				} else {
					if ($no + 1 <= $cnt) {
						$fdepartment .= "d.nama_department = '$d->nama_department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
			// $bagian = $this->m_hris->list_bagian();
			// $fbagian .= " and (";
			// $no = 0;
			// $cnt = $bagian->num_rows();
			// foreach ($bagian->result() as $d) {
			// 	$no = $no + 1;
			// 	if ($cnt == 1) {
			// 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 	} else {
			// 		if ($no + 1 <= $cnt) 
			// 		{
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
			// 		} else {
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 		}
			// 	}
			// }
			// $fbagian .= ")";
		}
		// echo $fbagian;


		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			// $text = "$text and b.recid_bag = '$bagian'";
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			if ($cnt == 1) {
				$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['mulai_t'] = $mulai_t;
		$data['sampai_t'] = $sampai_t;
		$data['mulai_b'] = $mulai_b;
		$data['sampai_b'] = $sampai_b;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persen_absen_semester', $data);
		$this->load->view('layout/a_footer');
	}

	public function persentase_semester_adm()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai = $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_tb = explode(" ", $mulai);
		$mulai_t = $mulai_tb[1];
		$mulai_b = $mulai_tb[0];
		$mulai_b = date("m", strtotime($mulai));
		$sampai_tb = explode(" ", $sampai);
		$sampai_t = $sampai_tb[1];
		$sampai_b = $sampai_tb[0];
		$sampai_b = date("m", strtotime($sampai));
		// echo $mulai_t." - ".$sampai_t;

		$where = "";
		if ($mulai_t == $sampai_t) {
			$where = "tahun = $mulai_t and bulan between $mulai_b and $sampai_b";
		} else {
			$where = "(tahun = $mulai_t and bulan between $mulai_b and 12) or (tahun = $sampai_t and bulan between 1 and $sampai_b)";
		}

		// echo $where;
		$jml_hk = $this->m_absen->hk_by_periode($where);
		foreach ($jml_hk->result() as $hk) {
			$jml_hk = $hk->jml_hk;
		}
		// echo $jml_hk;

		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->m_absen->dept_group_admin($usr);
		foreach ($divisi->result() as $dv) {
			array_push($fdivisi, $dv->dept_group);
		}


		$fdepartment = "";
		$department = $this->m_absen->dept_admin($usr);
		$cnt = $department->num_rows();
		$fdepartment .= " and (";
		$i = 0;
		foreach ($department->result() as $dept) {
			if ($cnt == 1) {
				$fdepartment .= "d.nama_department = '$dept->department'";
			} else {
				if ($i + 1 < $cnt) {
					$fdepartment .= "d.nama_department = '$dept->department' or ";
				} else {
					$fdepartment .= "d.nama_department = '$dept->department'";
				}
			}
		}
		$fdepartment .= ")";


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
		}
		// echo $fbagian;

		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}
		// echo $fkaryawan;

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['mulai_t'] = $mulai_t;
		$data['sampai_t'] = $sampai_t;
		$data['mulai_b'] = $mulai_b;
		$data['sampai_b'] = $sampai_b;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persen_absen_semester', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_tahun()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			$data['tahun'] = $this->m_absen->tahun_hk();
			if ($role == '30') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_tahun_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_tahun";
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function persentase_tahun()
	{

		$usr = $this->session->userdata('kar_id');
		$tahun = $this->input->post('tahun');

		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);
		$hk = $this->m_absen->hk_by_tahun($tahun);
		foreach ($hk->result() as $hk) {
			$jml_hk = $hk->jml_hk;
		}

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			$divisi = $this->m_hris->list_dept_group();
			foreach ($divisi->result() as $dv) {
				array_push($fdivisi, $dv->dept_group);
			}
		}


		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			// $fdepartment = $fdepartment;
			$department = $this->m_hris->list_department();
			$fdepartment .= " and (";
			$no = 0;
			$cnt = $department->num_rows();
			foreach ($department->result() as $d) {
				$no = $no + 1;
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$d->nama_department'";
				} else {
					if ($no + 1 <= $cnt) {
						$fdepartment .= "d.nama_department = '$d->nama_department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
			// $bagian = $this->m_hris->list_bagian();
			// $fbagian .= " and (";
			// $no = 0;
			// $cnt = $bagian->num_rows();
			// foreach ($bagian->result() as $d) {
			// 	$no = $no + 1;
			// 	if ($cnt == 1) {
			// 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 	} else {
			// 		if ($no + 1 <= $cnt) 
			// 		{
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
			// 		} else {
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 		}
			// 	}
			// }
			// $fbagian .= ")";
		}
		// echo $fbagian;


		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			// $text = "$text and b.recid_bag = '$bagian'";
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			if ($cnt == 1) {
				$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}


		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['tahun'] = $this->input->post('tahun');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;


		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persentase_tahun', $data);
		$this->load->view('layout/a_footer');
	}

	public function persentase_tahun_adm()
	{

		$usr = $this->session->userdata('kar_id');
		$tahun = $this->input->post('tahun');

		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);
		$hk = $this->m_absen->hk_by_tahun($tahun);
		foreach ($hk->result() as $hk) {
			$jml_hk = $hk->jml_hk;
		}

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->m_absen->dept_group_admin($usr);
		foreach ($divisi->result() as $dv) {
			array_push($fdivisi, $dv->dept_group);
		}


		$fdepartment = "";
		$department = $this->m_absen->dept_admin($usr);
		$cnt = $department->num_rows();
		$fdepartment .= " and (";
		$i = 0;
		foreach ($department->result() as $dept) {
			if ($cnt == 1) {
				$fdepartment .= "d.nama_department = '$dept->department'";
			} else {
				if ($i + 1 < $cnt) {
					$fdepartment .= "d.nama_department = '$dept->department' or ";
				} else {
					$fdepartment .= "d.nama_department = '$dept->department'";
				}
			}
		}
		$fdepartment .= ")";


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
		}
		// echo $fbagian;

		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['jml_hkn'] = $jml_hk;
		$data['tahun'] = $this->input->post('tahun');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;


		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persentase_tahun', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_sid()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['tahun'] = $this->m_absen->tahun_hk();
			$data['menu'] = "Laporan SID Bulanan";
			$bulan = date('m');
			$tahun = date('Y');
			$data['rekap'] = $this->m_absen->rekap_sid_bulan($bulan, $tahun);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_sid');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_rekap_sid()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		// $bulan = '12';
		// $tahun = '2022';
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$query = $this->m_absen->rekap_sid_bulan($bulan, $tahun);
		$data = [];
		$no = 0;
		foreach ($query->result() as $r) {
			$tgl = "";
			if (is_null($r->kategori) or is_null($r->diagnosa)) {
				$data_tgl = $this->m_absen->tgl_sid_emp2($bulan, $tahun, $r->recid_karyawan);
			} else {
				$data_tgl = $this->m_absen->tgl_sid_emp($bulan, $tahun, $r->recid_karyawan, $r->kategori, $r->diagnosa);
			}
			$cnt_tgl = $data_tgl->num_rows();
			if ($cnt_tgl > 0) {
				$c = 0;
				foreach ($data_tgl->result() as $t) {
					$c = $c + 1;
					if ($c < $cnt_tgl) {
						$tgl .= date("d", strtotime($t->tanggal)) . ", ";
					} else {
						$tgl .= date("d", strtotime($t->tanggal));
					}
				}
			} else {
				$tgl = $tgl;
			}
			$data[] = array(
				$no = $no + 1,
				$r->nik,
				$r->nama_karyawan,
				$r->indeks_hr,
				$r->indeks_jabatan,
				$r->kategori,
				$r->diagnosa,
				$r->lama . " hari",
				$tgl
			);
		}

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query->num_rows(),
			"recordsFiltered" => $query->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}

	public function saldo_cuti()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['tahun'] = $this->m_absen->tahun_hk();
			$data['menu'] = "Sisa Cuti Karyawan";
			$role = $this->session->userdata('role_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$bags = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}
			// $data['rekap'] = $this->m_absen->all_cuti2();
			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$data['rekap'] = $this->m_absen->all_cuti2();
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->all_cuti2_baros();
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->all_cuti2_bagian($bagian);
			} else if ($role == '23' or $role == '34') {
				$bagian = "(";
				$admin = $this->m_absen->admin_by_bagian($recid_bag);
				foreach ($admin->result() as $adm) {
					$adminnya = $adm->recid_karyawan;
				}

				$bgn = $this->m_absen->bagian_by_admin($adminnya); //admin bagian produksi
				$bag = array();
				$no = 0;
				foreach ($bgn->result() as $bg) {
					if (array_key_exists($bg->indeks_hr, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->indeks_hr);
					}
				}
				// print_r($bag);
				for ($b = 0; $b < count($bag); $b++) {
					$no = $no + 1;
					if ($no < count($bag)) {
						$bagian .= "b.indeks_hr = '$bag[$b]' or ";
					} else {
						$bagian .= "b.indeks_hr = '$bag[$b]'";
					}
				}
				$bagian .= ")";
				// echo $bagian;
				$data['rekap'] = $this->m_absen->all_cuti2_bagian($bagian);
				// echo $bagian;
			} else if ($role == '41' or $role == '29') {
				$recid_karyawan = $this->session->userdata('kar_id');
				$bagian = "(b.indeks_hr =";
				$bag = array();
				if ($tingkatan > '7') {
					// if ($role == '41') {
					// 	//custom role for all produksi = pic produksi (pa dadan 920)
					// 	$bgn = $this->m_hris->karyawan_view_by_atasan('920');
					// } else {
					// 	$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					// }
					$bgn = $this->m_absen->bagian_by_admin(1061); //admin bagian produksi
					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$bagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$bagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
					// echo $bagian;
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					$cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->all_cuti2_bagian($bagian);
				// echo $bagian;
			} else if ($role == '37') { // GM DEP GROUP
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_dept_group($dept_group);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}


				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->all_cuti2_bagian($bagian);
				// echo $bagian;
			} else { // all bagian
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				// $recid_karyawan = $this->session->userdata('kar_id');
				// $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$admin = $this->m_absen->admin_by_bagian($bags);
				foreach ($admin->result() as $adm) {
					$adminnya = $adm->recid_karyawan;
				}
				// $bgn = $this->m_absen->bagian_by_admin($adminnya);
				$bgn = $this->m_absen->bagian_by_admin($usr);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}

				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->all_cuti2_bagian($bagian);
				// echo $bagian;
			}

			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_saldo_cuti');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	/*----------------------------------------------------------------------------------------------------------*/
	public function cutoff_hk()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$cf = $this->m_absen->hk_by_bulan($tahun, $bulan);
			$result = array();
			foreach ($cf->result() as $c) {
				array_push($result, $c->tgl_awal);
				array_push($result, $c->tgl_akhir);
			}
			echo json_encode($result);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_persentase2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_persen2');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function persentase2()
	{
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['sejak'] = $this->input->post('sejak');
		$data['sampai'] = $this->input->post('sampai');
		$mulai = $this->input->post('sejak');
		$henti = $this->input->post('sampai');
		$filter1 = $this->input->post('filter1');
		$filter2 = $this->input->post('filter2');
		$tipe = $this->input->post('tipe');

		if ($filter1 == "Semua") {
			// echo "load semua data";
			$data['nama'] = $this->m_absen->allabsen_semua();
			// $this->excel();
		} else if ($filter1 == "Department") {
			// echo "load data department by filter2";
			$data['nama'] = $this->m_absen->allabsen_department($filter2);
		} else if ($filter1 == "Struktur") {
			// echo "load data department by filter2";
			$data['nama'] = $this->m_absen->allabsen_struktur($filter2);
		} else {
			// echo "looad data bagian by filter2";
			$data['nama'] = $this->m_absen->allabsen_bagian($filter2);
		}
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/report/r_absenpersen', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_diagnosa()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_diagnosa');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function diagnosa()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['sejak'] = $this->input->post('sejak');
			$data['sampai'] = $this->input->post('sampai');
			$mulai = $this->input->post('sejak');
			$henti = $this->input->post('sampai');
			$filter1 = $this->input->post('filter1');
			$filter2 = $this->input->post('filter2');
			$tipe = $this->input->post('tipe');

			if ($filter1 == "Semua") {
				// echo "load semua data";
				$data['nama'] = $this->m_absen->mangkir_all($mulai, $henti);
				// $this->excel();
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$data['nama'] = $this->m_absen->mangkir_dept($filter2, $mulai, $henti);
			} else if ($filter1 == "Struktur") {
				// echo "load data department by filter2";
				$data['nama'] = $this->m_absen->mangkir_str($filter2, $mulai, $henti);
			} else {
				// echo "looad data bagian by filter2";
				$data['nama'] = $this->m_absen->mangkir_bag($filter2, $mulai, $henti);
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_hdiagnosa', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function izin()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['izin'] = $this->m_absen->izin_view_bagian($bagian);
			} else {
				$data['izin'] = $this->m_absen->izin_view();
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['emp'] = $this->m_absen->karyawan_view();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/izin/izin_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function izin_periode()
	{
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		$role = $this->session->userdata('role_id');
		if ($role == "32") {
			$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
			$bagian = "(b.indeks_hr =";
			for ($i = 0; $i < count($bag); $i++) {
				$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
				foreach ($iindex as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			$bagian .= ")";
		}

		if ($jenis == "All") {
			if ($role == '32') {
				$query2 = $this->m_absen->izin_periode_baros($tgl_awal, $tgl_akhir);
			} else {
				$query2 = $this->m_absen->izin_periode($tgl_awal, $tgl_akhir);
			}
		} else {
			if ($role == "32") {
				$query2 = $this->m_absen->izin_periode_jenis_bagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
			} else {
				$query2 = $this->m_absen->izin_periode_jenis($tgl_awal, $tgl_akhir, $jenis);
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$i = 0;

		if ($role != '2') {
			$status = "";
			foreach ($query2->result() as $r) {
				if ($role == '1' or $role == '3' or $role == '5') {
					$aksi = '<a href="' . base_url() . 'Absen/izin_edit/' . $r->izin_recid . '"><button class="btn btn-info btn-xs"><span class="fa fa-edit"></button></a><a href="' . base_url() . 'Absen/izin_hapus/' . $r->izin_recid . '"><button class="btn btn-danger btn-xs"><span class="fa fa-trash"></button></a></center>';
				} else {
					$aksi = "";
				}
				$cek = $this->db->query("SELECT ja.recid_jenisabsen, ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $r->recid_karyawan and tanggal = '$r->tgl_izin'");
				foreach ($cek->result() as $c) {
					$status = $c->jenis . " - " . $c->keterangan;
					$jam_msk = $c->jam_in;
					if ($c->recid_jenisabsen == '29') {
						$jam_msk = "07:30";
					}
				}

				$validasi = ($r->perlu_validasi == '1') ? "Belum Validasi" : '';
				$jam_out = $r->jam_out;
				$jam_in = $r->jam_in;

				if ($r->over_durasi != "") {
					$dur = explode(".", $r->over_durasi);
					$jam = $dur[0];
					$menit = $dur[1];

					$hasil = $jam . " jam " . $menit . " menit";
				} else {
					$hasil = "";
				}

				// if ($r->jenis == "Keluar") {
				// 	$jm = intval(substr($jam_msk, 0, 2));
				// 	$mnt = substr($jam_msk, 2, 3);
				// 	$jam_awal = ($jm + 4) . $mnt;
				// 	$jam_akhir = ($jm + 5) . $mnt;
				// 	if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
				// 		$out = new DateTime($jam_out);
				// 		$in = new DateTime($jam_in);
				// 		$selisih = $in->diff($out);
				// 		$jam = $selisih->format('%h');
				// 		$menit = $selisih->format('%i');
				// 		if ($menit >= 0 && $menit <= 9) {
				// 			$menit = "0" . $menit;
				// 		}
				// 		$hasil = $jam . " jam " . $menit . ' menit';
				// 	} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
				// 		$out = new DateTime($jam_out);
				// 		$in = new DateTime($jam_awal);
				// 		$selisih = $in->diff($out);
				// 		$jam = $selisih->format('%h');
				// 		$menit = $selisih->format('%i');
				// 		if ($menit >= 0 && $menit <= 9) {
				// 			$menit = "0" . $menit;
				// 		}
				// 		$hasil = $jam . " jam " . $menit . ' menit';
				// 	} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
				// 		$out = new DateTime($jam_out);
				// 		$in = new DateTime($jam_in);
				// 		$selisih = $in->diff($out);
				// 		$jam = ($selisih->format('%h')) - 1;
				// 		$menit = $selisih->format('%i');
				// 		if ($menit >= 0 && $menit <= 9) {
				// 			$menit = "0" . $menit;
				// 		}
				// 		$hasil = $jam . " jam " . $menit . ' menit';
				// 	} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
				// 	} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
				// 		//jam_masuk - jam keluar (12:30)
				// 		$out = new DateTime("12:30:00");
				// 		$in = new DateTime($jam_in);
				// 		$selisih = $in->diff($out);
				// 		$jam = $selisih->format('%h');
				// 		$menit = $selisih->format('%i');
				// 		if ($menit >= 0 && $menit <= 9) {
				// 			$menit = "0" . $menit;
				// 		}
				// 		$hasil = $jam . " jam " . $menit . ' menit';
				// 	} else {
				// 		$out = new DateTime($jam_out);
				// 		$in = new DateTime($jam_in);
				// 		$selisih = $in->diff($out);
				// 		$jam = $selisih->format('%h');
				// 		$menit = $selisih->format('%i');
				// 		if ($menit >= 0 && $menit <= 9) {
				// 			$menit = "0" . $menit;
				// 		}
				// 		$hasil = $jam . " jam " . $menit . ' menit';
				// 	}
				// } else {
				// 	$hasil = "";
				// }

				if ($role == 1) {
					$data[] = array(
						$i = $i + 1,
						$r->tgl_izin,
						$r->nik,
						$r->nama_karyawan,
						$r->indeks_hr,
						$r->indeks_jabatan,
						$r->jenis,
						$r->jam_in,
						$r->jam_out,
						$hasil,
						$r->over_durasi,
						$status,
						$r->keterangan,
						$validasi,
						$aksi
					);
				} else {
					if ($role == '1' or $role == '3' or $role == '5') {
						$aksi = '<a href="' . base_url() . 'Absen/izin_edit/' . $r->izin_recid . '"><button class="btn btn-info btn-xs"><span class="fa fa-edit"></button></a><a href="' . base_url() . 'Absen/izin_hapus/' . $r->izin_recid . '"><button class="btn btn-danger btn-xs"><span class="fa fa-trash"></button></a></center>';
					} else {
						$aksi = "";
					}
					$data[] = array(
						$i = $i + 1,
						$r->tgl_izin,
						$r->nik,
						$r->nama_karyawan,
						$r->indeks_hr,
						$r->indeks_jabatan,
						$r->jenis,
						$r->jam_in,
						$r->jam_out,
						$hasil,
						$status,
						$r->keterangan,
						$validasi,
						$aksi
					);
				}
			}
		} else {
			foreach ($query2->result() as $r) {
				$cek = $this->db->query("SELECT ja.recid_jenisabsen, ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $r->recid_karyawan and tanggal = '$r->tgl_izin'");
				foreach ($cek->result() as $c) {
					$status = $c->jenis . " - " . $c->keterangan;
					$jam_msk = $c->jam_in;
					if ($c->recid_jenisabsen == '29') {
						$jam_msk = "07:30";
					}
				}

				$jam_out = $r->jam_out;
				$jam_in = $r->jam_in;
				if ($r->jenis == "Keluar") {
					$jm = intval(substr($jam_msk, 0, 2));
					$mnt = substr($jam_msk, 2, 3);
					$jam_awal = ($jm + 4) . $mnt;
					$jam_akhir = ($jm + 5) . $mnt;
					if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
					} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_awal);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
					} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = ($selisih->format('%h')) - 1;
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
					} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
					} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
						//jam_masuk - jam keluar (12:30)
						$out = new DateTime("12:30:00");
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
					} else {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
					}
				} else {
					$hasil = "";
				}

				$data[] = array(
					$i = $i + 1,
					$r->tgl_izin,
					$r->nik,
					$r->nama_karyawan,
					$r->indeks_hr,
					$r->indeks_jabatan,
					$r->jenis,
					$r->jam_in,
					$r->jam_out,
					$hasil,
					$status,
					$r->keterangan,
				);
			}
		}

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query2->num_rows(),
			"recordsFiltered" => $query2->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}

	public function karyawan_detail()
	{
		$nik = $this->input->post('nik');
		$emp = $this->m_absen->karyawan_by_nik2($nik);
		$data = array();
		foreach ($emp as $emp) {
			$a = array($emp->nama_karyawan, $emp->indeks_hr, $emp->indeks_jabatan, $emp->recid_karyawan, $emp->nik);
			array_push($data, $a);
		}
		$cuti = $this->m_absen->cek_sisa_cuti_kar($nik);
		foreach ($cuti->result() as $c) {
			array_push($data, $c->jml_cuti);
		}
		$cuti_thn_ke = $this->m_absen->sisa_cuti_active($nik);
		if ($cuti_thn_ke->num_rows() > 0) {
			foreach ($cuti_thn_ke->result() as $ct) {
				array_push($data, $ct->cuti_thn_ke);
			}
		} else {
			array_push($data, 0);
		}

		echo json_encode($data);
	}

	public function izin_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');	// sama dengan recid_karyawan
			$tanggal = $this->input->post('tanggal');
			$jenis = $this->input->post('jenis');
			$keterangan = $this->input->post('keterangan');
			$over_durasi = 0;

			if ($jenis == "Terlambat" || $jenis == "Terlambat Terencana" || $jenis == "Terlambat Tidak Terencana") {
				$cek_ja = $this->m_absen->cek_emp_hadir2($nik, $tanggal);
				foreach ($cek_ja->result() as $pn) {
					$masuk_normal = new DateTime($pn->jam_in);
					$jam_masuk = new DateTime($pn->jam_masuk); // jam scan
					$selisih = $masuk_normal->diff($jam_masuk);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$selisih_masuk = $jam . '.' . $menit;
				}
				$over_durasi = number_format(($over_durasi + $selisih_masuk), 2, '.', '');
				$jam_in = $this->input->post('jam_in');
				if ($jam_in != "") {
					$jam_in = str_replace(" ", "", $jam_in);
				}
				$jam_out = null;
				$jam_pulang = "16:00:00";
				$edit_hadir = array(
					'tanggal'		=> $tanggal,
					'jam_masuk'		=> $jam_in,
					// 'jam_keluar'	=> $jam_pulang,
					'status'		=> '1',
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('Y-m-d h:i:s'),
				);
				$this->m_absen->edit_hadir($nik, $tanggal, $edit_hadir);
			} else if ($jenis == "Pulang") {
				$over_durasi = "";
				$jam_in = null;
				$jam_out = $this->input->post('jam_out');
				if ($jam_out != "") {
					$jam_out = str_replace(" ", "", $jam_out);
				}
				$edit_hadir = array(
					'tanggal'		=> $tanggal,
					'jam_keluar'	=> $jam_out,
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('Y-m-d h:i:s'),
				);
				$this->m_absen->edit_hadir($nik, $tanggal, $edit_hadir);
			} else {
				$jam_in = $this->input->post('jam_in');
				$jam_in = str_replace(" ", "", $jam_in);
				if ($jam_in != "") {
					$jam_in = str_replace(" ", "", $jam_in);
				}
				$jam_out = $this->input->post('jam_out');
				if ($jam_out != "") {
					$jam_out = str_replace(" ", "", $jam_out);
				}
				// $jam_out = str_replace(" ", "", $jam_out);
				$cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $nik and tanggal = '$tanggal'");
				foreach ($cek->result() as $c) {
					$status = $c->jenis . " - " . $c->keterangan;
					$jam_msk = $c->jam_in;
				}
				$jm = intval(substr($jam_msk, 0, 2));
				$mnt = substr($jam_msk, 2, 3);
				$jam_awal = ($jm + 4) . $mnt;
				$jam_akhir = ($jm + 5) . $mnt;

				if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_awal);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = ($selisih->format('%h')) - 1;
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
					$over_durasi = "0.0";
				} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
					//jam_masuk - jam keluar (12:30)
					$out = new DateTime("12:30:00");
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				}
			}

			// echo $jam_in;
			$data = array(
				'crt_by'	 => $this->session->userdata('kar_id'),
				'recid_karyawan' => $nik,
				'tgl_izin'	 => $tanggal,
				'jenis'		 => $jenis,
				'jam_in'	 => $jam_in,
				'jam_out'	 => $jam_out,
				'keterangan' => $keterangan,
				'over_durasi' => $over_durasi,
				'perlu_validasi' => '0',
			);
			$this->m_absen->save_data('izin', $data);


			$flag_premi = $jenis;

			if ($jenis == "Pulang" or $jenis == "Terlambat") {
				$cek_hadir = $this->m_absenbarcode->cek_double($nik, $tanggal);
				if ($cek_hadir->num_rows() < 1) {
					$shift = $this->m_absenbarcode->cek_shift_karyawan($nik, $tanggal);
					if ($shift->num_rows() > 0) {
						foreach ($shift->result() as $s) {
							$status = $s->recid_jenisabsen;
						}
					} else {
						$status = '1';
					}
					if ($jenis == 'Terlambat') {
						$data_h = array(
							'crt_date'          => date('Y-m-d H:i:s'),
							'crt_by'            => $this->session->userdata('kar_id'),
							'recid_karyawan'    => $nik,
							'tanggal'           => $tanggal,
							'tgl_masuk'          => $tanggal,
							'jam_masuk'         => $jam_in,
							'status'            => $status,
							'lokasi_masuk'      => 'Industri',
							'tmp_status'        => $status,
							'perlu_validasi'    => 0,
							'ket_validasi'     => "Lupa Absen Masuk",
						);
						$this->m_absenbarcode->save_absen_masuk($data_h);
					} else {
						$data_h = array(
							'crt_date'          => date('Y-m-d H:i:s'),
							'crt_by'            => $this->session->userdata('kar_id'),
							'recid_karyawan'    => $nik,
							'tanggal'           => $tanggal,
							'tgl_pulang'        => $tanggal,
							'jam_keluar'         => $jam_out,
							'status'            => $status,
							'lokasi_masuk'      => 'Industri',
							'tmp_status'        => $status,
							'perlu_validasi'    => 0,
							'ket_validasi'     => "Lupa Absen Pulang",
						);
						$this->m_absenbarcode->save_absen_masuk($data_h);
					}
				} else {
					// update hadir
					foreach ($cek_hadir->result() as $h) {
						$id = $h->recid_absen;
						$jam_masuk = $h->jam_masuk;
						$jam_keluar = $h->jam_keluar;
						// echo "id absen : ".$id;
						if ($jenis == "Terlambat Terencana" or $jenis == "Terlambat Tidak Terencana") {
							$data = array(
								'mdf_date'          => date('Y-m-d H:i:s'),
								'mdf_by'            => $this->session->userdata('kar_id'),
								'perlu_validasi'    => '0',
								'jam_masuk'			=> $jam_in,
								'ket_validasi'     => "Lupa Absen Masuk",
								'flag_premi'     	=> $flag_premi,
							);
						} else {
							$data = array(
								'mdf_date'          => date('Y-m-d H:i:s'),
								'mdf_by'            => $this->session->userdata('kar_id'),
								'perlu_validasi'    => '0',
								'jam_keluar'		=> $jam_out,
								'ket_validasi'     => "Lupa Absen Pulang",
								'flag_premi'     	=> $flag_premi,
							);
						}
						$this->m_absenbarcode->update_hadir($data, $id);
					}
				}
			} else {
				$data_hd = array(
					'mdf_date'          => date('Y-m-d H:i:s'),
					'mdf_by'            => $this->session->userdata('kar_id'),
					'flag_premi'     	=> $flag_premi,
				);
				$this->m_absenbarcode->update_hadir2($nik, $tanggal, $data_hd);
			}

			// $cek = $this->m_absen->cek_absensi($nik, $tanggal);
			// if($cek->num_rows() > 0){
			// 	foreach ($cek->result() as $c) {
			// 		$data_absen = array(
			// 			'is_delete'		=> '1',
			// 			'mdf_by'		=> $this->session->userdata('kar_id'),
			// 			'mdf_date'		=> date('Y-m-d h:i:s'),
			// 		); 
			// 		$this->m_absen->edit_absensi_recid($c->absensi_recid, $data_absen);
			// 	}
			// }

			redirect('Absen/izin');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function izin_edit()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_izin = $this->uri->segment(3);
			$data['izin'] = $this->m_absen->izin_by_id($recid_izin);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/izin/izin_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function izin_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$izin_recid = $this->input->post('izin_recid');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$tanggal = $this->input->post('tanggal2');
			$jenis = $this->input->post('jenis2');
			$jam_in = $this->input->post('jam_in');
			if ($jam_in == '') {
				$jam_in = null;
			} else {
				$jam_in = str_replace(" ", "", $jam_in);
			}
			$jam_out = $this->input->post('jam_out');
			if ($jam_out == '') {
				$jam_out = null;
			} else {
				$jam_out = str_replace(" ", "", $jam_out);
			}
			$keterangan = $this->input->post('keterangan2');

			if ($jenis == "Keluar") {
				$cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $recid_karyawan and tanggal = '$tanggal'");
				foreach ($cek->result() as $c) {
					$status = $c->jenis . " - " . $c->keterangan;
					$jam_msk = $c->jam_in;
				}
				$jm = intval(substr($jam_msk, 0, 2));
				$mnt = substr($jam_msk, 2, 3);
				$jam_awal = ($jm + 4) . $mnt;
				$jam_akhir = ($jm + 5) . $mnt;

				if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_awal);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = ($selisih->format('%h')) - 1;
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
					$over_durasi = "0.0";
				} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
					//jam_masuk - jam keluar (12:30)
					$out = new DateTime("12:30:00");
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				} else {
					$out = new DateTime($jam_out);
					$in = new DateTime($jam_in);
					$selisih = $in->diff($out);
					$jam = $selisih->format('%h');
					$menit = $selisih->format('%i');
					if ($menit >= 0 && $menit <= 9) {
						$menit = "0" . $menit;
					}
					$hasil = $jam . " jam " . $menit . ' menit';
					$over_durasi = $jam . '.' . $menit;
				}
			} else if ($jenis == "Pulang") {
				$cek_jenis = $this->m_absenbarcode->cek_ja_hadir($recid_karyawan, $tanggal);
				if ($cek_jenis->num_rows() > 0) {
					foreach ($cek_jenis->result() as $k) {
						$jam_keluar1 = $k->jam_keluar;
						$status = $k->status;

						if ($status == 1) // kerja normal (11:30 - 12:30)
						{
							if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
								$jam_keluar = new DateTime("12:31"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 26) { /* Middle Shift 1 (11:00 - 12:00)*/
							if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
								$jam_keluar = new DateTime("12:01"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 27) {/* Middle Shift 2 (13:00 - 14:00)*/
							if ($jam_keluar1 > "13:00" and $jam_keluar1 < "14:01") {
								$jam_keluar = new DateTime("14:01"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 29) {/* Kerja Non Shift Ramadhan (11:00 - 12:00)*/
							if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
								$jam_keluar = new DateTime("12:01"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 30) {/* Middle Shift 3 (12:00 - 13.00)*/
							if ($jam_keluar1 > "12:00" and $jam_keluar1 < "13:01") {
								$jam_keluar = new DateTime("13:01"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 35) {/* Ganti Hari Kerja (11:30 - 12:30)*/
							if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
								$jam_keluar = new DateTime("12:31"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else if ($status == 37) {/* Middle Shift 4 (14:30 - 15:30) */
							if ($jam_keluar1 > "14:30" and $jam_keluar1 < "15:31") {
								$jam_keluar = new DateTime("15:31"); // jam scan
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
						} else {
							$jam_keluar = new DateTime($k->jam_keluar); // jam scan
						}
						$pulang_normal = new DateTime($k->jam_out);
						$selisih = $pulang_normal->diff($jam_keluar);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 60) {
							$jam = $jam + 1;
							$menit = $menit - 60;
						}
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$durasi = $jam . ' jam ' . $menit . " menit";
						$over_durasi = $jam . "." . $menit;
					}
				} else {
					$pulang_normal = "00:00:00";
					$jam_masuk = "00:00:00";
					$jam_pulang = "00:00:00";
					$durasi = "00:00:00";
					$over_durasi = 0;
				}
			} else {
				// terlambat
				$cek_jenis = $this->m_absenbarcode->cek_ja_hadir($recid_karyawan, $tanggal);
				$durasi = 0;
				$over_durasi = 0;
				if ($cek_jenis->num_rows() > 0) {
					foreach ($cek_jenis->result() as $k) {
						$jam_masuk_normal  = new DateTime($k->jam_in);
						$jam_scan_masuk = new DateTime($k->jam_masuk);
						$status = $k->status;

						$selisih = $jam_scan_masuk->diff($jam_masuk_normal);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 60) {
							$jam = $jam + 1;
							$menit = $menit - 60;
						}
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$durasi = $jam . ' jam ' . $menit . " menit";
						$over_durasi = $jam . "." . $menit;
					}
				}
			}

			$data = array(
				'mdf_by'	 => $this->session->userdata('kar_id'),
				'mdf_date'	 => $this->session->userdata('kar_id'),
				'recid_karyawan' => $recid_karyawan,
				'tgl_izin'	 => $tanggal,
				'jenis'		 => $jenis,
				'jam_in'	 => $jam_in,
				'jam_out'	 => $jam_out,
				'keterangan' => $keterangan,
				'over_durasi' => $over_durasi,
				'perlu_validasi' => '0',
			);
			$this->m_absen->edit_izin($izin_recid, $data);

			$edit_hadir = array(
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('Y-m-d h:i:s'),
				'flag_premi'    	=> $jenis
			);
			$this->m_absenbarcode->update_hadir2($recid_karyawan, $tanggal, $edit_hadir);

			// if($jam_in == null)
			// {
			// 	$jam_in = "07:00";
			// }else{
			// 	$jam_in = str_replace (" ", "", $jam_in);
			// }
			// $jam_out = $this->input->post('jam_out');
			// if($jam_out == null)
			// {
			// 	$jam_out = "16:00";
			// }else{
			// 	$jam_out = str_replace (" ", "", $jam_out);
			// }
			// $edit_hadir = array(
			// 	'tanggal'		=> $tanggal,
			// 	'jam_masuk'	    => $jam_in,
			// 	'jam_keluar'	=> $jam_out,
			// 	'mdf_by'		=> $this->session->userdata('kar_id'),
			// 	'mdf_date'		=> date('Y-m-d h:i:s'),
			// );
			// $this->m_absen->edit_hadir($recid_karyawan, $tanggal, $edit_hadir);
			redirect('Absen/izin');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function izin_hapus()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$izin_recid = $this->uri->segment(3);
			$data = array(
				'mdf_by'	 => $this->session->userdata('kar_id'),
				'mdf_date'	 => $this->session->userdata('kar_id'),
				'is_delete'  => '1',
			);
			$this->m_absen->edit_izin($izin_recid, $data);
			redirect('Absen/izin');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function libur()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['cuber'] = $this->m_absen->cuber_view();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('cuti/cuber_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cuber_insert()
	{
		$tanggal = $this->input->post('tanggal');
		$keterangan = $this->input->post('keterangan');
		$jenis = $this->input->post('jenis');
		$data = array(
			'crt_by'		=> $this->session->userdata('kar_id'),
			'tanggal'		=> $tanggal,
			'jenis'		=> $jenis,
			'keterangan'	=> $keterangan,
		);
		$this->m_absen->save_data('cuti_bersama', $data);
		redirect('Absen/libur');
	}

	public function cuber_update()
	{
		$cuber_recid = $this->input->post('cuber_recid');
		$tanggal = $this->input->post('tanggal');
		$keterangan = $this->input->post('keterangan');
		$jenis = $this->input->post('jenis');

		$data = array(
			'tanggal'		=> $tanggal,
			'keterangan'	=> $keterangan,
			'jenis'	=> $jenis,
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('Y-m-d h:i:s'),
		);
		$this->m_absen->edit_cuber($cuber_recid, $data);
		redirect('Absen/libur');
	}

	public function cuber_hapus()
	{
		$cuber_recid = $this->uri->segment(3);
		$data = array(
			'is_delete'		=> '1',
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('Y-m-d h:i:s'),
		);
		$this->m_absen->edit_cuber($cuber_recid, $data);
		redirect('Absen/libur');
	}

	public function check_cuti_daily()
	{
		$cuber = $this->m_absen->cuber_tahun(date('Y'));
		$cuber = $cuber->num_rows();
		$emp = $this->m_absen->check_cuti_daily();

		//add cuti baru
		if ($emp->num_rows() > 0) {
			foreach ($emp->result() as $e) {
				$cukar = 12 - $cuber;
				// echo "normal saldo cuti : $cukar <br>";
				$thn_kerja = $e->tgl_m_kerja;
				$thn_kerja = substr($thn_kerja, 0, 4);
				if ($thn_kerja == date('Y')) {
					//skip
				} else {
					$cuti_th_ke = date('Y');
					$cuti_th_ke = ($cuti_th_ke - $thn_kerja); // rumus tahun cuti {updt : 20/06/2022 sesuai tahun masuk kerja}

					if ($cuti_th_ke < 5) {
						$cukar = $cukar;
					} else if ($cuti_th_ke >= 5 and $cuti_th_ke <= 10) {
						$cukar = $cukar + 1;
					} else if ($cuti_th_ke >= 11 and $cuti_th_ke <= 17) {
						$cukar = $cukar + 2;
					} else if ($cuti_th_ke >= 18 and $cuti_th_ke <= 25) {
						$cukar = $cukar + 3;
					} else {
						$cukar = $cukar + 4;
					}
					$tgl_mulai_sebelumnya = date('Y-m-d', strtotime('-1 year', strtotime(date('Y-m-d'))));
					$tgl_exp_sebelumnya = date('Y-m-d');
					$p1 = $this->m_absen->p1_by_emp($e->recid_karyawan, $tgl_mulai_sebelumnya, $tgl_exp_sebelumnya);
					$jml_p1 = $p1->num_rows();
					$cukar = $cukar - $jml_p1;
					// echo "nama_karyawan = $e->nama_karyawan <br>";
					// echo "cuti tahun ke : $cuti_th_ke <br>";
					// echo "jumlah dapet cuti : $cukar <br><br>";
					$data = array(
						'crt_by'			=> 0,
						'crt_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $e->recid_karyawan,
						'cuti_thn_ke'		=> $cuti_th_ke,
						'tgl_mulai'			=> date('Y-m-d'),
						'jml_cuti'			=> $cukar,
						'expired'			=> date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d'))))
					);
					$this->m_absen->save_cuti_karyawan($data);
				}
			}
			//hanguskan cuti
			$h_emp = $this->m_absen->cuti_karyawan_expired(date('Y-m-d'));
			if ($h_emp->num_rows() > 0) {
				foreach ($h_emp->result() as $he) {
					$recid_cuti = $he->recid_cuti;
					$data2 = array(
						'mdf_by'	=> 0,
						'mdf_date'	=> date('Y-m-d h:i:s'),
						'hangus'	=> '1',
					);
					$this->m_absen->edit_cukar($data2, $recid_cuti);
				}
			}
		} else {
			//tidak ada karyawan
		}

		$cek_puasa = $this->m_absenbarcode->cek_puasa();
		$skrg = date('Y-m-d');
		$day = date("D", strtotime($skrg));
		//  echo $day;
		if ($day != 'Sat' and $day != 'Sun') {
			$libur = $this->m_absen->libur_by_date($skrg);
			if ($libur->num_rows() == 0) {
				if ($cek_puasa->num_rows() > 0) {
					$jam_ker  = $this->m_absen->jenis_absen_id(29);
				} else {
					$jam_ker  = $this->m_absen->jenis_absen_id(1);
				}
				foreach ($jam_ker->result() as $jk) {
					$jam_masuk = $jk->jam_in;
					$jam_keluar = $jk->jam_out;
					$status  = $jk->recid_jenisabsen;
				}

				$get_bod = $this->m_hris->data_bod();
				echo "test";
				foreach ($get_bod->result() as $gb) {
					$data = array(
						'crt_date'          => date('Y-m-d H:i:s'),
						'crt_by'            => 1189,
						'recid_karyawan'    => $gb->recid_karyawan,
						'tanggal'           => date('Y-m-d'),
						'tgl_masuk'          => date('Y-m-d'),
						'jam_masuk'         => $jam_masuk,
						'status'            => $status,
						'lokasi_masuk'      => 'Industri',
						'jam_keluar'        => $jam_keluar,
						'lokasi_pulang'     => 'Industri',
						'tmp_status'        => $status,
						'perlu_validasi'    => '0',
						'ket_validasi'    => 'By Admin',
					);
					$this->m_absenbarcode->save_absen_masuk($data);
				}
			}
		}

		$data_log = array(
			'tgl_cron'			=> date('Y-m-d h:i:s'),
			'deskripsi'			=> "check cuti dan absen bod finished",
		);
		$this->m_absen->save_log_daily($data_log);

		//renew password histori
		$this->m_hris->reaktif_pwd();

		// redirect('Auth/keluar');
	}

	public function daily_cuti_adjust()
	{
		$cuber = $this->m_absen->cuber_tahun(date('Y'));
		$cuber = $cuber->num_rows();
		$tgl_mulai = "2023-03-24";
		$tgl_selesai = "2023-03-27";

		while ($tgl_mulai <= $tgl_selesai) {
			$emp = $this->m_absen->check_cuti_tgl($tgl_mulai);
			//add cuti baru
			if ($emp->num_rows() > 0) {
				foreach ($emp->result() as $e) {
					echo $tgl_mulai . ": " . $e->recid_karyawan . "<br>";
					echo "expired : " . date('Y-m-d', strtotime('+1 year', strtotime($tgl_mulai))) . "<br>";
					$cukar = 12 - $cuber;
					// echo "normal saldo cuti : $cukar <br>";
					$thn_kerja = $e->tgl_m_kerja;
					$thn_kerja = substr($thn_kerja, 0, 4);
					$cuti_th_ke = date('Y');
					$cuti_th_ke = ($cuti_th_ke - $thn_kerja); // rumus tahun cuti {updt : 20/06/2022 sesuai tahun masuk kerja}

					if ($cuti_th_ke < 5) {
						$cukar = $cukar;
					} else if ($cuti_th_ke >= 5 and $cuti_th_ke <= 10) {
						$cukar = $cukar + 1;
					} else if ($cuti_th_ke >= 11 and $cuti_th_ke <= 17) {
						$cukar = $cukar + 2;
					} else if ($cuti_th_ke >= 18 and $cuti_th_ke <= 25) {
						$cukar = $cukar + 3;
					} else {
						$cukar = $cukar + 4;
					}
					$tgl_mulai_sebelumnya = date('Y-m-d', strtotime('-1 year', strtotime($tgl_mulai)));
					$tgl_exp_sebelumnya = $tgl_mulai;
					$p1 = $this->m_absen->p1_by_emp($e->recid_karyawan, $tgl_mulai_sebelumnya, $tgl_exp_sebelumnya);
					$jml_p1 = $p1->num_rows();
					$cukar = $cukar - $jml_p1;
					// echo "nama_karyawan = $e->nama_karyawan <br>";
					// echo "cuti tahun ke : $cuti_th_ke <br>";
					// echo "jumlah dapet cuti : $cukar <br><br>";
					$data = array(
						'crt_by'			=> 1189,
						'crt_date'			=> date('Y-m-d h:i:s'),
						'recid_karyawan'	=> $e->recid_karyawan,
						'cuti_thn_ke'		=> $cuti_th_ke,
						'tgl_mulai'			=> $tgl_mulai,
						'jml_cuti'			=> $cukar,
						'expired'			=> date('Y-m-d', strtotime('+1 year', strtotime($tgl_mulai)))
					);
					$this->m_absen->save_cuti_karyawan($data);
				}
				//hanguskan cuti
				$h_emp = $this->m_absen->cuti_karyawan_expired(date('Y-m-d'));
				foreach ($h_emp->result() as $he) {
					$recid_cuti = $he->recid_cuti;
					$data2 = array(
						'mdf_by'	=> 1189,
						'mdf_date'	=> date('Y-m-d h:i:s'),
						'hangus'	=> '1',
					);
					$this->m_absen->edit_cukar($data2, $recid_cuti);
				}
			} else {
			}
			$tgl_mulai = date('Y-m-d', strtotime('+1 days', strtotime($tgl_mulai)));
		}
		// 	 $cek_puasa = $this->m_absenbarcode->cek_puasa();
		//     if($cek_puasa->num_rows() > 0)
		//     {
		//         $jam_ker  = $this->m_absen->jenis_absen_id(29);
		//     }else{
		//         $jam_ker  = $this->m_absen->jenis_absen_id(1);
		//     }
		//     foreach ($jam_ker->result() as $jk) {
		//             $jam_masuk = $jk->jam_in;
		//             $jam_keluar = $jk->jam_out;
		//            $status  = $jk->recid_jenisabsen;
		//     }

		// $get_bod = $this->m_hris->data_bod();
		// foreach ($get_bod->result() as $gb) {
		// 	 $data = array(
		//         'crt_date'          => date('Y-m-d H:i:s'),
		//         'crt_by'            => 1189,
		//         'recid_karyawan'    => $gb->recid_karyawan,
		//         'tanggal'           => date('Y-m-d'),
		//         'tgl_masuk'          => date('Y-m-d'),
		//         'jam_masuk'         => $jam_masuk,
		//         'status'            => $status,
		//         'lokasi_masuk'      => 'Industri',
		//         'jam_keluar'        => $jam_keluar,
		//         'lokasi_pulang'     => 'Industri',
		//         'tmp_status'        => $status,
		//         'perlu_validasi'    => '0', 
		//         'ket_validasi'    => 'By Admin', 
		//      );
		//     $this->m_absenbarcode->save_absen_masuk($data);
		// }
		// $data_log = array(
		// 	'tgl_cron'			=> date('Y-m-d h:i:s'),
		// 	'deskripsi'			=> "check cuti dan absen bod finished",
		// );
		// $this->m_absen->save_log_daily($data_log);
		// redirect('Auth/keluar');
	}

	public function cukar_input()
	{
		$data['karyawan'] = $this->m_hris->karyawan_view();
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('cuti/cukar_input', $data);
		$this->load->view('layout/a_footer');
	}

	public function cuti_pinsert()
	{
		$data_cuti = array(
			'recid_karyawan' => $this->input->post('recid_karyawan'),
			'cuti_thn_ke'	=> $this->input->post('cuti_thn_ke'),
			'tgl_mulai'		=> $this->input->post('tgl_mulai'),
			'expired'		=> $this->input->post('expired'),
			'jml_cuti'		=> $this->input->post('jml_cuti'),
			'hangus'		=> $this->input->post('hangus'),
			'crt_by'		=> $this->session->userdata('kar_id'),
			'crt_date'		=> date('Y-m-d h:i:s'),
		);
		$this->m_absen->save_cuti_karyawan($data_cuti);
		redirect("Absen/saldo_cuti");
	}

	public function adjust_cuti()
	{
		$recid_cuti = $this->uri->segment(3);
		$data['cuti'] = $this->m_absen->cuti_by_id($recid_cuti);
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('cuti/cukar_update', $data);
		$this->load->view('layout/a_footer');
	}

	public function cuti_update()
	{
		$recid_cuti = $this->input->post('recid_cuti');
		$data_cuti = array(
			'cuti_thn_ke'	=> $this->input->post('cuti_thn_ke'),
			'tgl_mulai'		=> $this->input->post('tgl_mulai'),
			'expired'		=> $this->input->post('expired'),
			'jml_cuti'		=> $this->input->post('jml_cuti'),
			'hangus'		=> $this->input->post('hangus'),
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('Y-m-d h:i:s'),
		);
		$this->m_absen->edit_cukar($data_cuti, $recid_cuti);
		redirect("Absen/saldo_cuti");
	}

	public function rekap_kehadiran()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$data['jenis'] = $this->m_absen->jenis_absen();
			$data['menu'] = "Rekap Kahadiran Semua Karyawan";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bags = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}

			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
				$data['rekap'] = $this->m_absen->rekap_kehadiran($tgl);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$data['rekap'] = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else if ($role == "23" or $role == "41" or $role == '29') {	// user - off up
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				// $bgn = $this->m_hris->bagian_by_karyawan($recid_karyawan);
				$bag = array();
				if ($tingkatan > '7') {
					//custom role for all produksi = pic produksi (pa dadan 920)
					if ($role == '41') {
						$bgn = $this->m_hris->karyawan_view_by_atasan('920');
					} else {
						$admin = $this->m_absen->admin_by_bagian($recid_bags);
						foreach ($admin->result() as $adm) {
							$adminnya = $adm->recid_karyawan;
						}
						$bgn = $this->m_absen->bagian_by_admin($adminnya);
						// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					}
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
						}
					}
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					foreach ($bgn as $bg) {
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
						}
					}
				}
				// foreach ($bgn->result() as $bg) {
				// 	array_push($bag, $bg->recid_bag);
				// }
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				// echo $bagian;
				$data['rekap'] = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else if ($role == '37') {
				$data['rekap'] = $this->m_absen->rekap_kehadiran_dept_group($tgl, $dept_group);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				// echo $bagian;
				$data['rekap'] = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			}

			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_kehadiran');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_rekap_kehadiran()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl = $this->input->post('tgl_mulai');
			// $tgl = "2022-11-01";
			$role = $this->session->userdata('role_id');
			$data['jenis'] = $this->m_absen->jenis_absen();
			$data['menu'] = "Rekap Kahadiran Semua Karyawan";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bags = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}

			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25' or $role == '32') {
				$rekap = $this->m_absen->rekap_kehadiran($tgl);
			} else if ($role == '32') {
				$bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$rekap = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else if ($role == '31') {	// mega - keamanan {24}
				$bag = ['24'];
				$bagian = "(b.indeks_hr =";
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$rekap = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else if ($role == "23" or $role == '41' or $role == '29') {
				$bagian = "(b.indeks_hr =";
				$bag = array();
				if ($tingkatan > '7') {
					//custom role for all produksi = pic produksi (pa dadan 920)
					if ($role == '41') {
						$bgn = $this->m_hris->karyawan_view_by_atasan(920);
					} else {
						$admin = $this->m_absen->admin_by_bagian($recid_bags);
						foreach ($admin->result() as $adm) {
							$adminnya = $adm->recid_karyawan;
						}
						$bgn = $this->m_absen->bagian_by_admin($adminnya);
						// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
					}

					$no = 0;
					foreach ($bgn->result() as $bg) {
						if (array_key_exists($bg->indeks_hr, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->indeks_hr);
						}
					}
					// print_r($bag);
					for ($b = 0; $b < count($bag); $b++) {
						$no = $no + 1;
						if ($no < count($bag)) {
							$bagian .= "b.indeks_hr = '$bag[$b]' or ";
						} else {
							$bagian .= "b.indeks_hr = '$bag[$b]'";
						}
					}
					// echo $bagian;
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$no = 0;
					$cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
					foreach ($bgn->result() as $bg) {
						$no = $no + 1;
						if (array_key_exists($bg->recid_bag, $bag)) {
							// echo "Key exists!";
						} else {
							array_push($bag, $bg->recid_bag);
							if ($no < $cnt) {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
							} else {
								$bagian .= "b.indeks_hr = '$bg->indeks_hr'";
							}
						}
					}
				}
				$bagian .= ")";
				$rekap = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			} else {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "(b.indeks_hr =";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					$iindex = $this->m_hris->bagian_by_recid($bag[$i]);
					foreach ($iindex as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$bagian .= ")";
				$rekap = $this->m_absen->rekap_kehadiran_bagian($tgl, $bagian);
			}
			// echo $bagian;
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));

			$data = [];
			$no = 0;
			foreach ($rekap->result() as $r) {
				$data[] = array(
					$r->indeks_hr,
					$r->nama_struktur,
					$r->nama_department,
					$r->jml_karyawan,
					$r->K,
					$r->SID,
					$r->C,
					$r->D,
					$r->P1,
					$r->H1,
					$r->H2,
					$r->WFH,
					$r->MS,
					$r->A,
					$r->M,
					$r->P4,
					$r->TOS,
					$r->S1,
					$r->S2,
					$r->S3,
					$r->LS1,
					$r->LS2,
					$r->SS1,
					$r->SS2,
					$r->SS3,
					$r->SPM1,
					$r->SPM2,
					$r->L,
					$r->KK,
					$r->OB,
					$r->S4,
				);
			}

			$result = array(
				"draw" => $draw,
				"recordsTotal" => $rekap->num_rows(),
				"recordsFiltered" => $rekap->num_rows(),
				"data" => $data
			);

			echo json_encode($result);
			exit();
		} else {
			redirect('Auth/keluar');
		}
	}

	/*------------------- PINDAHAN DARI CONTROLLER KARYAWAN ----------------------*/
	public function r_absensi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_absensi');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_pabsensi2()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start = $time;
		// $data['sejak'] = $this->input->post('sejak');
		// $data['sampai']= $this->input->post('sampai');
		// $sejak = $this->input->post('sejak');
		// $sampai= $this->input->post('sampai');
		$mulai = $this->input->post('sejak');
		$selesai = $this->input->post('sampai');
		// $filter1 = $this->input->post('filter1');
		$filter2 = $this->input->post('filter2');
		$filter1 = "Semua";
		// $tipe = $this->input->post('tipe');
		$tipe = "Barcode";
		if ($tipe == 'Barcode') {
			if ($filter1 == "Semua") {
				// echo "load semua data";
				$nama = $this->m_absen->allabsen_semua();
				//$this->excel();
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$nama = $this->m_absen->allabsen_department($filter2);
			} else if ($filter1 == "Struktur") {
				// echo "load data department by filter2";
				$nama = $this->m_absen->allabsen_struktur($filter2);
			} else {
				// echo "looad data bagian by filter2";
				$nama = $this->m_absen->allabsen_bagian($filter2);
			}
		} else {
			if ($filter1 == "Semua") {
				// echo "load semua data";
				$nama = $this->m_absen->allaccess_semua();
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$nama = $this->m_absen->allaccess_department($filter2);
			} else if ($filter1 == "Struktur") {
				// echo "load data department by filter2";
				$nama = $this->m_absen->allaccess_struktur($filter2);
			} else {
				// echo "looad data bagian by filter2";
				$nama = $this->m_absen->allaccess_bagian($filter2);
			}
		}


		//data
		$nilai = array();
		foreach ($nama as $nama) {
			$detail = array();
			$sejak =  $this->input->post('sejak');
			$sampai = $this->input->post('sampai');
			$mulai =  $this->input->post('sejak');
			$selesai = $this->input->post('sampai');
			$nik = $nama->nik;
			$nama_kry = $nama->nama_karyawan;
			$bag = $nama->nama_bag;
			$recid_bag = $nama->recid_bag;
			$terlambat = 0;
			// echo "$nik | $nama_kry | $bag |";
			array_push($detail, $nik);
			array_push($detail, $nama_kry);
			array_push($detail, $bag);
			while ($sejak <= $sampai) {
				$work = "-";
				$cek_absen = $this->m_absen->cek_absen($nik, $sejak);
				if ($cek_absen->num_rows() == 0) {
					// echo "data kosong"; 
					$cek_mangkir = $this->m_absen->cek_mangkir($nik, $sejak);
					if ($cek_mangkir->num_rows() == 0) {
						$work = "-";
					} else {
						foreach ($cek_mangkir->result() as $mangkir) {
							$work = $mangkir->CODE;
						}
					}
				} else {
					foreach ($cek_absen->result() as $absen) {
						$cek_shift = $this->m_absen->cek_shift($nik, $sejak);
						if ($cek_shift->num_rows() > 0) {
							if ($recid_bag == '24') /*KEAMANAN*/ {
								foreach ($cek_shift->result() as $lmb) {
									$shift = $lmb->SHIFT_CODE;
								}
								if ($shift == '1') {
									if ($absen->TIME_IN >= '07:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else if ($shift == '2') {
									if ($absen->TIME_IN >= '15:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								}
							} else if ($bag == '3' or $bag == '62') /*CHROME*/ {
								foreach ($lambat->result() as $lmb) {
									$shift = $lmb->SHIFT_CODE;
								}
								if ($shift == '1') {
									if ($absen->TIME_IN >= '06:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else if ($shift == '2') {
									if ($absen->TIME_IN >= '14:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else {
									if ($absen->TIME_IN >= '22:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								}
							} else /*NORMAL*/ {
								if ($absen->TIME_IN >= '07:31') {
									$terlambat = $terlambat + 1;
								} else {
									$terlambat = $terlambat;
								}
							}
							$work = $absen->DATE_WORK;
							$in = $absen->TIME_IN;
							$out = $absen->TIME_OUT;
							$ins = substr($in, 0, 5);
							$outs = substr($out, 0, 5);
							if ($ins != null and $outs != null) {
								$work = "K";
							} else {
								$work = $in . " - " . $out;
							}
						} else /*BUKAN SHIFT*/ {
							if ($absen->TIME_IN >= '07:31') {
								$terlambat = $terlambat + 1;
							} else {
								$terlambat = $terlambat;
							}
							$work = $absen->DATE_WORK;
							$in = $absen->TIME_IN;
							$out = $absen->TIME_OUT;
							$ins = substr($in, 0, 5);
							$outs = substr($out, 0, 5);
							if ($ins != null and $outs != null) {
								$work =  "K";
							} else {
								$work = $in . " - " . $out;
							}
						}
					}
				}
				// echo " $work | ";
				array_push($detail, $work);
				$sejak = date('Y-m-d', strtotime('+1 days', strtotime($sejak)));
			}
			// echo "<br>";
			$hadir = $this->m_absen->hadir($nik, $mulai, $selesai);
			foreach ($hadir as $hadir) {
				$kerja = $hadir->kerja;
			}
			array_push($detail, $kerja);
			$cuti = $this->m_absen->cuti($nik, $mulai, $selesai);
			foreach ($cuti as $cuti) {
				$cuti = $cuti->cuti;
			}
			array_push($detail, $cuti);
			$sid = $this->m_absen->sid($nik, $mulai, $selesai);
			foreach ($sid as $sid) {
				$sid = $sid->sid;
			}
			array_push($detail, $sid);
			$h1 = $this->m_absen->h1($nik, $mulai, $selesai);
			foreach ($h1 as $h1) {
				$h1 = $h1->h1;
			}
			array_push($detail, $h1);
			$h2 = $this->m_absen->h2($nik, $mulai, $selesai);
			foreach ($h2 as $h2) {
				$h2 = $h2->h2;
			}
			array_push($detail, $h2);
			$p1 = $this->m_absen->p1($nik, $mulai, $selesai);
			foreach ($p1 as $p1) {
				$p1 = $p1->p1;
			}
			array_push($detail, $p1);
			$p3 = $this->m_absen->p3($nik, $mulai, $selesai);
			foreach ($p3 as $p3) {
				$p3 = $p3->p3;
			}
			array_push($detail, $p3);
			$p4 = $this->m_absen->p4($nik, $mulai, $selesai);
			foreach ($p4 as $p4) {
				$p4 = $p4->p4;
			}
			array_push($detail, $p4);
			$mangkir = $this->m_absen->mangkir($nik, $mulai, $selesai);
			foreach ($mangkir as $mangkir) {
				$mangkir = $mangkir->mangkir;
			}
			array_push($detail, $mangkir);
			array_push($nilai, $detail);
		}
		// var_dump($nilai);
		$data['nilai'] = $nilai;
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
		$data['waktu'] = $total_time;
		// echo 'Page generated in '.$total_time.' seconds.';
		$this->load->view('absen/report/tes', $data);
	}

	public function r_pabsensi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;
			$data['sejak'] = $this->input->post('sejak');
			$data['sampai'] = $this->input->post('sampai');
			$filter1 = $this->input->post('filter1');
			$filter2 = $this->input->post('filter2');
			$tipe = $this->input->post('tipe');
			if ($tipe == 'Barcode') {
				if ($filter1 == "Semua") {
					// echo "load semua data";
					// $data['nama'] = $this->m_hris->allabsen_semua();
					$this->excel();
				} else if ($filter1 == "Department") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_absen->allabsen_department($filter2);
				} else if ($filter1 == "Struktur") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_absen->allabsen_struktur($filter2);
				} else {
					// echo "looad data bagian by filter2";
					$data['nama'] = $this->m_absen->allabsen_bagian($filter2);
				}

				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				$finish = $time;
				$total_time = round(($finish - $start), 4);
				// echo 'Page generated in '.$total_time.' seconds.';
				$data['waktu'] = $total_time;
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('absen/report/r_allabsen', $data);
				$this->load->view('layout/a_footer');
			} else {
				if ($filter1 == "Semua") {
					// echo "load semua data";
					$data['nama'] = $this->m_absen->allaccess_semua();
				} else if ($filter1 == "Department") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_absen->allaccess_department($filter2);
				} else if ($filter1 == "Struktur") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_absen->allaccess_struktur($filter2);
				} else {
					// echo "looad data bagian by filter2";
					$data['nama'] = $this->m_absen->allaccess_bagian($filter2);
				}
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('absen/report/r_allaccess', $data);
				$this->load->view('layout/a_footer');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function excel()
	{

		$absen = $this->m_absen->allabsen_semua();
		$sejak = $this->input->post('sejak');;
		$sampai = $this->input->post('sampai');
		$tgl_m = strtotime($sejak);
		$tgl_a = strtotime($sampai);
		$diff = $tgl_a - $tgl_m;
		$selisih = (floor($diff / (60 * 60 * 24))) + 1;
		$i = 1; //baris
		$x = 3; //kolom

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Nik');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Bagian');
		// $sheet->setCellValueByColumnAndRow(4,1,"tes");

		$c = $sejak;
		$d = $sampai;
		while ($c <= $d) {
			$x = $x + 1;
			$sheet->setCellValueByColumnAndRow($x, $i, $c);
			$datetime = new datetime($c);
			$c = date('Y-m-d', strtotime('+1 days', strtotime($c)));
		}

		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "Hadir");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "Telat");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "Cuti");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "S1D");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "H1");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "H2");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "P1");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "P3");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "P4");
		$x = $x + 1;
		$sheet->setCellValueByColumnAndRow($x, $i, "Mangkir");


		/*+++++++++ BODY ++++++++++++++++*/
		$i2 = 1; //baris
		$j = 1;
		$k = 2;
		$l = 3;

		foreach ($absen as $nama) {
			$i2 = $i2 + 1;
			$x2 = 3; // kolom
			$sheet->setCellValueByColumnAndRow($j, $i2, $nama->nik);
			$sheet->setCellValueByColumnAndRow($k, $i2, $nama->nama_karyawan);
			$sheet->setCellValueByColumnAndRow($l, $i2, $nama->nama_bag);
			// $sheet->setCellValueByColumnAndRow($x2+1, $i2, "Jam");
			$awal = $this->input->post('sejak');
			$akhir = $this->input->post('sampai');
			$mulai = $this->input->post('sejak');
			$selesai = $this->input->post('sampai');
			while ($awal <= $akhir) {
				$x2 = $x2 + 1;
				$work = "";
				$terlambat = 0;
				/*	------------------------------------------------------------------------------------------------------------------------------------*/

				$cek_absen = $this->db->query("SELECT m.*, k.recid_bag From master_absen.absen m join hris.karyawan k on m.nik = k.nik where m.nik = '$nama->nik' and m.date_work = '$awal' and k.sts_aktif = 'Aktif'");
				if ($cek_absen->num_rows() == 0) {
					//tidak absen
					$cek_mangkir = $this->db->query("SELECT m.* From master_absen.mangkir m join hris.karyawan k on m.nik = k.nik where m.nik = '$nama->nik' and m.tanggal = '$awal' and k.sts_aktif = 'Aktif'");
					if ($cek_mangkir->num_rows() == 0) {
						//tidak ada keterangan
						$work = " - ";
					} else {
						foreach ($cek_mangkir->result() as $mangkir) {
							// tidak hadir ada keterangan
							$work = $mangkir->CODE;
						}
					}
				} else {
					foreach ($cek_absen->result() as $absen) {
						//cek shift
						$lambat = $this->db->query("SELECT * FROM master_absen.shift where nik = '$nama->nik' and TGL_SHIFT = '$awal'");
						if ($lambat->num_rows() > 0) {
							// shift
							$bag = $absen->recid_bag;
							// keamanam
							if ($bag == '24') {
								foreach ($lambat->result() as $lmb) {
									$shift = $lmb->SHIFT_CODE;
								}
								if ($shift == '1') {
									if ($absen->TIME_IN >= '07:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else if ($shift == '2') {
									if ($absen->TIME_IN >= '15:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else {
									if ($absen->TIME_IN >= '23:05') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								}
							} // end keamanan
							else if ($bag == '3' or $bag == '62') { //chrome
								foreach ($lambat->result() as $lmb) {
									$shift = $lmb->SHIFT_CODE;
								}
								if ($shift == '1') {
									if ($absen->TIME_IN >= '06:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else if ($shift == '2') {
									if ($absen->TIME_IN >= '14:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								} else {
									if ($absen->TIME_IN >= '22:01') {
										$terlambat = $terlambat + 1;
									} else {
										$terlambat = $terlambat;
									}
								}
							} else { // normal
								if ($absen->TIME_IN >= '07:31') {
									$terlambat = $terlambat + 1;
								} else {
									$terlambat = $terlambat;
								}
							}
							$work = $absen->DATE_WORK;
							$in = $absen->TIME_IN;
							$out = $absen->TIME_OUT;
							$ins = substr($in, 0, 5);
							$outs = substr($out, 0, 5);
							if ($ins != null and $outs != null) {
								$work = "K";
							} else {
								$work = $in . " - " . $out;
							}
						} else {
							if ($absen->TIME_IN >= '07:31') {
								$terlambat = $terlambat + 1;
							} else {
								$terlambat = $terlambat;
							}
							$work = $absen->DATE_WORK;
							$in = $absen->TIME_IN;
							$out = $absen->TIME_OUT;
							$ins = substr($in, 0, 5);
							$outs = substr($out, 0, 5);
							if ($ins != null and $outs != null) {
								$work =  "K";
							} else {
								$work = $in . " - " . $out;
							}
						}
					}
				}

				/*	------------------------------------------------------------------------------------------------------------------------------------*/
				$sheet->setCellValueByColumnAndRow($x2, $i2, "$work");
				$datetime = new datetime($awal);
				$awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));
			}
			/*	------------------------------------------------------------------------------------------------------------------------------------*/

			$hadir =  $this->db->query("SELECT count(nik) as kerja from master_absen.absen where nik = '$nama->nik' and time_in != '' and time_out !='' and DATE_WORK between '$mulai' and '$selesai'")->result();
			foreach ($hadir as $hadir) {
				$kerja = $hadir->kerja;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $kerja);
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $terlambat);
			$cuti =  $this->db->query("SELECT count(nik) as cuti from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'T')")->result();
			foreach ($cuti as $cuti) {
				$cuti = $cuti->cuti;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $cuti);
			$sid =  $this->db->query("SELECT count(nik) as sid from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'S1D')")->result();
			foreach ($sid as $sid) {
				$sid = $sid->sid;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $sid);
			$h1 =  $this->db->query("SELECT count(nik) as h1 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'H1')")->result();
			foreach ($h1 as $h1) {
				$h1 = $h1->h1;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $h1);
			$h2 =  $this->db->query("SELECT count(nik) as h2 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'H2')")->result();
			foreach ($h2 as $h2) {
				$h2 = $h2->h2;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $h2);
			$p1 =  $this->db->query("SELECT count(nik) as p1 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'P1')")->result();
			foreach ($p1 as $p1) {
				$p1 = $p1->p1;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $p1);
			$p3 =  $this->db->query("SELECT count(nik) as p3 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'P3')")->result();
			foreach ($p3 as $p3) {
				$p3 = $p3->p3;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $p3);
			$p4 =  $this->db->query("SELECT count(nik) as p4 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'P4')")->result();
			foreach ($p4 as $p4) {
				$p4 = $p4->p4;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $p4);
			$mangkir =  $this->db->query("SELECT count(nik) as mangkir from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$mulai' and '$selesai' and (code = 'MA')")->result();
			foreach ($mangkir as $mangkir) {
				$mangkir = $mangkir->mangkir;
			}
			$x2 = $x2 + 1;
			$sheet->setCellValueByColumnAndRow($x2, $i2, $mangkir);
			/*	---------------------------------------------------------------------------------------------------------------------------*/
		}

		$writer = new Xlsx($spreadsheet);

		$filename = 'Rekap Absesnsi Karyawan ' . $mulai . " - " . $selesai;

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function report_grafik_hadir($sejak, $sampai)
	{
		$sejak = $sejak;
		$sampai = $sampai;
		$dept = $this->db->query("SELECT distinct department from bagian");
		foreach ($dept->result() as $dept) {
			$hk_dept = 0;
			// $absen_dept = 0;
			$nama = $this->db->query("SELECT k.*, b.* from karyawan k join bagian b on k.recid_bag = b.recid_bag and b.department = '$dept->department' and k.sts_aktif='Aktif' and spm = 'Tidak' ");
			foreach ($nama->result() as $nama) {
				$start = date($sejak);
				$end = date($sampai);
				$hk = 0;
				// echo "$nama->nama_karyawan | $dept->department =  " ;
				while ($start <= $end) {
					// echo $start;
					if ($nama->shift == 'Shift') {
						$hk2 = $this->db->query("SELECT count(nik) as hk from master_absen.shift where nik = '$nama->nik' and TGL_SHIFT BETWEEN '$sejak' and '$sampai' and SHIFT_CODE != 0");
						foreach ($hk2->result() as $da) {
							$hk = $da->hk;
						}
						if ($hk == 0) {
							$hk = 0;
							$awal = $sejak;
							$akhir = $sampai;
							while ($awal <= $akhir) {
								// cek weekend
								$datetime = new datetime($awal);
								$dt = $datetime->format('D');
								if ($dt == "Sun" or $dt == "Sat") {
									// cek ganti hari perusahaan
									$gh = $this->m_absen->gh_by_date($awal);
									if ($gh->num_rows() > 0) {
										$hk = $hk + 1;
									} else {
										$hk = $hk;
									}
								} else {
									// cek hari libur
									$libur = $this->m_absen->libur_by_date($awal);
									if ($libur->num_rows() > 0) {
										$hk = $hk;
									} else {
										$hk = $hk + 1;
									}
								}
								$awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));
							} // while 2
						}
					} else {
						// cek weekend
						$datetime = new datetime($start);
						$dt = $datetime->format('D');
						if ($dt == "Sun" or $dt == "Sat") {
							// cek ganti hari perusahaan
							$gh = $this->m_absen->gh_by_date($start);
							if ($gh->num_rows() > 0) {
								$hk = $hk + 1;
							} else {
								$hk = $hk;
							}
						} else {
							// cek hari libur
							$libur = $this->m_absen->libur_by_date($start);
							if ($libur->num_rows() > 0) {
								$hk = $hk;
							} else {
								$hk = $hk + 1;
							}
						}
					}
					$start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
				} //while atas

				/*hitung norma - non norma*/

				$absen = 0;
				$absen = $this->db->query("SELECT count(m.nik) as non from master_absen.mangkir m
			join hris.karyawan k on k.nik = m.nik
			join hris.bagian b on b.recid_bag = k.recid_bag
			where b.department = '$dept->department' and TANGGAL BETWEEN '$sejak' and '$sampai' ");
				foreach ($absen->result() as $non) {
					$absen = $non->non;
				}
				$hk_dept = $hk_dept + $hk;
				$efek = $hk_dept - $absen;
				$persen = round($efek / $hk_dept * 100);
			}
			echo $dept->department . ":" . $hk_dept . " | Absen : " . $absen . "| Efektifitas : $efek | persentase : $persen<br>";
		}
	}



	public function count_hk()
	{
		$bln_skrg = date('m');
		$thn_skrg = date('Y');
		// echo $thn_skrg;
		for ($i = 1; $i <= $bln_skrg; $i++) {
			$bln = $i;
			echo "Bulan = " . $bln . "<br>";

			$months = $thn_skrg . "-" . $bln . "-01";
			$days = date("t", strtotime($months));
			$months2 = $thn_skrg . "-" . $bln . "-" . $days;
			$this->report_grafik_hadir($months, $months2);
			// echo "Normal Hari : ".$days."<br>";

			// $count = 0; // keep a count of Sats & Suns
			// $start = date("Y-m-d", strtotime($months));
			// $end = date("Y-m-d", strtotime($months2));
			// while($start <= $end)
			// {
			// 	$datetime = new datetime($start);
			// 	$dt = $datetime->format('D');
			// 	if($dt == "Sun" or $dt == "Sat" )
			// 	{
			// 		$count++;
			// 	}
			// 	$start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
			// }
			// echo "Weekend = $count<br>";

			// $lbr = 0;
			// $clibur = $this->db->query("SELECT * from master_absen.libur where month(tgl) = $bln and year(tgl) = $thn_skrg");
			// $lbr = $clibur->num_rows();
			// echo "Libur = $lbr<br>";

			// $shift = 0;
			// $cshift = $this->db->query("SELECT * from master_absen.shift where nik = '20140707277' and month(TGL_SHIFT) = $bln and year(TGL_SHIFT) = $thn_skrg");
			// $shift = $cshift->num_rows();
			// echo "Shift = ".$shift."<br>";


			echo "<br>";
		}
	}

	public function tahun_closing()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tthn = $this->m_absen->tahun_closing();
			$tahun = array();
			foreach ($tthn->result() as $thn) {
				$a = array($thn->tahun);
				array_push($tahun, $a);
			}
			$max = $this->m_absen->max_tahun();
			foreach ($max->result() as $mmax) {
				$b = array($mmax->thnn);
				array_push($tahun, $b);
			}
			// var_dump($tahun);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['tahun']	= $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/tahun_closing', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function closing()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$tahun = $this->input->post('tahun');
			$data['tahun'] = $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/closing', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function test_hk()
	{
		$thn_skrg = date('Y');
		$moon = $this->uri->segment(3); // ambil dari button
		$bln =  date('m', strtotime($moon));		// set angka bulan
		$months = $thn_skrg . "-" . $bln . "-01";		// set tgl awal bulan
		$days = date("t", strtotime($months));	// get tgl akhir bulan
		$months2 = $thn_skrg . "-" . $bln . "-" . $days;	// set tgl akhir bulan

		echo $moon . "- $thn_skrg <br>";

		// count total karyawan all active
		/*$total = 0;
			$dept = $this->db->query("SELECT distinct(department) from bagian where department = 'LAIN-LAIN'");
			foreach ($dept->result() as $dept) {
				$all_active = $this->m_absen->count_active($bln, $thn_skrg, $dept->department);
				$jml_aktif = $all_active->num_rows();
				echo "$dept->department :  $jml_aktif <br>";
				// echo $jml_aktif;
				$total = $total + $jml_aktif;
			}
			echo $total;*/
		// count total karyawan by keu & adm

		// count total karyawan by marketing
		// count total karyawan by produksi


		//count HK Looping
		// //dont forget total hk by keu & adm, total hk marketing, total hk produksi
		$dept = $this->db->query("SELECT distinct(department) from bagian where department = 'LAIN-LAIN'");
		foreach ($dept->result() as $dept) {
			echo "---------------------------------------- $dept->department ------------------------------------------<br>";
			$hk_dept = 0;
			$bagian = $this->db->query("SELECT * from bagian where department = '$dept->department' and is_delete = 0 order by nama_bag asc");
			foreach ($bagian->result() as $bagian) {
				$hk_bag = 0;
				$karyawan = $this->db->query("SELECT * from karyawan where recid_bag = '$bagian->recid_bag' and sts_aktif = 'Aktif' and spm = 'Tidak'");
				// echo "----------------------------------$bagian->nama_bag <br>";
				foreach ($karyawan->result() as $nama) {
					$start = date($months);
					$end = date($months2);
					$sejak = date($months);
					$sampai = date($months2);
					$hk = 0;
					while ($start <= $end) {
						// echo $start;
						if ($bagian->shift == 'Shift') {
							$hk2 = $this->db->query("SELECT count(nik) as hk from master_absen.shift where nik = '$nama->nik' and TGL_SHIFT BETWEEN '$sejak' and '$sampai' and SHIFT_CODE != 0");
							foreach ($hk2->result() as $da) {
								$hk = $da->hk;
							}
							if ($hk == 0) {
								$hk = 0;
								$awal = $sejak;
								$akhir = $sampai;
								while ($awal <= $akhir) {
									// cek weekend
									$datetime = new datetime($awal);
									$dt = $datetime->format('D');
									if ($dt == "Sun" or $dt == "Sat") {
										// cek ganti hari perusahaan
										$gh = $this->m_absen->gh_by_date($awal);
										if ($gh->num_rows() > 0) {
											$hk = $hk + 1;
										} else {
											$hk = $hk;
										}
									} else {
										// cek hari libur
										$libur = $this->m_absen->libur_by_date($awal);
										if ($libur->num_rows() > 0) {
											$hk = $hk;
										} else {
											$hk = $hk + 1;
										}
									}
									$awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));
								} // while 2
							}
						} else {
							// cek weekend
							$datetime = new datetime($start);
							$dt = $datetime->format('D');
							if ($dt == "Sun" or $dt == "Sat") {
								// cek ganti hari perusahaan
								$gh = $this->m_absen->gh_by_date($start);
								if ($gh->num_rows() > 0) {
									$hk = $hk + 1;
								} else {
									$hk = $hk;
								}
							} else {
								// cek hari libur
								$libur = $this->m_absen->libur_by_date($start);
								if ($libur->num_rows() > 0) {
									$hk = $hk;
								} else {
									$hk = $hk + 1;
								}
							}
						}
						$start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
					} // while atas
					echo "$nama->nama_karyawan : $hk <br>";
					$hk_bag = $hk_bag + $hk;
					$hk_dept = $hk_dept + $hk;
				}
				echo "------------------------------------------------$bagian->nama_bag : $hk_bag<br>";
			}
			echo "------------------------------------------------$bagian->department : $hk_dept <br>";
		}
		//count total absen by keu & adm, total absen marketing, total absen produksi, total keseluruhan tinggal di jumlah
	}

	public function closing_process()
	{

		$thn_skrg = date('Y');
		$moon = $this->uri->segment(3); // ambil dari button
		if ($moon == "February") {
			$bln = '02';
		} else {
			$bln =  date('m', strtotime($moon));		// set angka bulan
		}
		$months = $thn_skrg . "-" . $bln . "-01";		// set tgl awal bulan
		$days = date("t", strtotime($months));	// get tgl akhir bulan
		$months2 = $thn_skrg . "-" . $bln . "-" . $days;	// set tgl akhir bulan
		echo $moon . "- $thn_skrg <br>";

		$total = 0;
		$dept = $this->db->query("SELECT distinct(department) from bagian");
		foreach ($dept->result() as $dept) {
			// detail karyawan
			echo "---------------------------------------- $dept->department ------------------------------------------<br>";
			$hk_dept = 0;
			$bagian = $this->db->query("SELECT * from bagian where department = '$dept->department' and is_delete = 0 order by nama_bag asc");
			$jml_aktif_w = 0;
			$jml_aktif_p = 0;
			foreach ($bagian->result() as $bagian) {
				$all_active_w = $this->m_absen->count_active_wanita($bln, $thn_skrg, $bagian->recid_bag);
				$jml_aktif_w = $all_active_w->num_rows();
				$all_active_p = $this->m_absen->count_active_pria($bln, $thn_skrg, $bagian->recid_bag);
				$jml_aktif_p = $all_active_p->num_rows();
				$jml_aktif = $jml_aktif_p + $jml_aktif_w;
				echo "-------------------------------------Aktif - $dept->department :  $jml_aktif (W = $jml_aktif_w - L = $jml_aktif_p) <br>";
				$all_absen = $this->m_absen->count_absen($bln, $thn_skrg, $bagian->recid_bag);
				$jml_absen = $all_absen->num_rows();
				echo "-------------------------------------Absen - $bagian->nama_bag :  $jml_absen <br>";
				$hk_bag = 0;
				$karyawan = $this->db->query("SELECT * from karyawan where recid_bag = '$bagian->recid_bag' and sts_aktif = 'Aktif' and spm = 'Tidak'");
				// echo "----------------------------------$bagian->nama_bag <br>";
				foreach ($karyawan->result() as $nama) {
					$start = date($months);
					$end = date($months2);
					$sejak = date($months);
					$sampai = date($months2);
					$hk = 0;
					while ($start <= $end) {
						// echo $start;
						if ($bagian->shift == 'Shift') {
							$hk2 = $this->db->query("SELECT count(nik) as hk from master_absen.shift where nik = '$nama->nik' and TGL_SHIFT BETWEEN '$sejak' and '$sampai' and SHIFT_CODE != 0");
							foreach ($hk2->result() as $da) {
								$hk = $da->hk;
							}
							if ($hk == 0) {
								$hk = 0;
								$awal = $sejak;
								$akhir = $sampai;
								while ($awal <= $akhir) {
									// cek weekend
									$datetime = new datetime($awal);
									$dt = $datetime->format('D');
									if ($dt == "Sun" or $dt == "Sat") {
										// cek ganti hari perusahaan
										$gh = $this->m_absen->gh_by_date($awal);
										if ($gh->num_rows() > 0) {
											$hk = $hk + 1;
										} else {
											$hk = $hk;
										}
									} else {
										// cek hari libur
										$libur = $this->m_absen->libur_by_date($awal);
										if ($libur->num_rows() > 0) {
											$hk = $hk;
										} else {
											$hk = $hk + 1;
										}
									}
									$awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));
								} // while 2
							}
						} else {
							// cek weekend
							$datetime = new datetime($start);
							$dt = $datetime->format('D');
							if ($dt == "Sun" or $dt == "Sat") {
								// cek ganti hari perusahaan
								$gh = $this->m_absen->gh_by_date($start);
								if ($gh->num_rows() > 0) {
									$hk = $hk + 1;
								} else {
									$hk = $hk;
								}
							} else {
								// cek hari libur
								$libur = $this->m_absen->libur_by_date($start);
								if ($libur->num_rows() > 0) {
									$hk = $hk;
								} else {
									$hk = $hk + 1;
								}
							}
						}
						$start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
					} // while atas
					echo "$nama->nama_karyawan : $hk <br>";
					$hk_bag = $hk_bag + $hk;
					$hk_dept = $hk_dept + $hk;
				}
				echo "-----------------------------------------HK BAGIAN  $bagian->nama_bag : $hk_bag<br>";
				$data = array(
					// "crt_by"		=> $this->session->userdata('kar_id'),
					"bulan"			=> $bln,
					"tahun"			=> $thn_skrg,
					"recid_bag"		=> $bagian->recid_bag,
					"department"	=> $bagian->department,
					"recid_struktur" => $bagian->recid_struktur,
					"female"        => $jml_aktif_w,
					"male"        	=> $jml_aktif_p,
					"hk"        	=> $hk_bag,
					"absen"        	=> $jml_absen,
				);
				$this->m_absen->save_closing($data);
			}
			echo "-------------------------------------TOTAL HK  $bagian->department : $hk_dept <br>";
			// echo $jml_aktif;
			// $total = $total + $jml_aktif;
		}
		redirect("Absen/closing");
	}

	function upload_updateabsensi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$thn = date('Y');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/upload_absen', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	function import_updateabsensi()
	{

		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		if (isset($_FILES['file_exc']['name']) && in_array($_FILES['file_exc']['type'], $file_mimes)) {
			$arr_file = explode('.', $_FILES['file_exc']['name']);
			$extension = end($arr_file);
			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} elseif ('xls' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($_FILES['file_exc']['tmp_name']);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			if (!empty($sheetData)) {
				for ($i = 1; $i < count($sheetData); $i++) {
					$nik = $sheetData[$i][1];
					$cek = $this->m_hris->karyawan_by_nik($nik);
					foreach ($cek as $k) {
						$recid_karyawan = $k->recid_karyawan;
					}
					$tanggal = $sheetData[$i][3];
					$jenis_absen = $sheetData[$i][4];
					$cuti_thn_ke = $sheetData[$i][5];
					$diagnosa = $sheetData[$i][6];
					$kategori = $sheetData[$i][7];
					$ket_sakit = $sheetData[$i][8];
					$keterangan = $sheetData[$i][9];
					$validasi_cuti = $sheetData[$i][10];
					echo "emp : $recid_karyawan <br> tanggal :  $tanggal <br> status : $jenis_absen <br> dgs : $diagnosa <br> kat : $kategori <br> ket_skt : $ket_sakit <br> ket : $keterangan <br> val : $validasi_cuti<br><br>";
					if ($jenis_absen == '2' or $jenis_absen == '3' or ($jenis_absen >= 5 and $jenis_absen <= 13) or $jenis_absen == '24' or $jenis_absen == '25') {
						$cek_abs = $this->m_absen->cek_absensi($recid_karyawan, $tanggal);
						if ($cek_abs->num_rows() > 0) {
							$data = array(
								'jenis_absen'	=> $jenis_absen,
								'cuti_ke'		=> $cuti_thn_ke,
								'diagnosa'		=> $diagnosa,
								'kategori'		=> $kategori,
								'ket_sakit'		=> $ket_sakit,
								'keterangan'	=> $keterangan,
								'validasi_cuti'	=> $validasi_cuti,
								'mdf_by'			=> $this->session->userdata('kar_id'),
								'mdf_date'			=> date("Y-m-d h:i:s"),
							);
							$this->m_absen->edit_absensi($recid_karyawan, $tanggal, $data);
						} else {
							$data = array(
								'recid_karyawan'	=> $recid_karyawan,
								'tanggal'			=> $tanggal,
								'jenis_absen'	=> $jenis_absen,
								'cuti_ke'		=> $cuti_thn_ke,
								'diagnosa'		=> $diagnosa,
								'kategori'		=> $kategori,
								'ket_sakit'		=> $ket_sakit,
								'keterangan'	=> $keterangan,
								'validasi_cuti'	=> $validasi_cuti,
								'crt_by'			=> $this->session->userdata('kar_id'),
								'crt_date'			=> date("Y-m-d h:i:s"),
							);
							$this->m_absen->save_absensi($data);
						}
					}

					$jenis_absen = $sheetData[$i][4];
					$ja = $this->m_absen->jenis_absen_id($jenis_absen);
					foreach ($ja->result() as $j) {
						$jam_in = $j->jam_in;
						$jam_out = $j->jam_out;
					}
					// $data2 = array(
					// 	'status'			=> $jenis_absen,
					// 	'jam_masuk'			=> $jam_in,
					// 	'jam_keluar'		=> $jam_out,
					// 	'mdf_by'			=> $this->session->userdata('kar_id'),
					// 	'mdf_date'			=> date("Y-m-d h:i:s"), 
					// );

					// $this->m_absenbarcode->update_hadir2($recid_karyawan, $tanggal, $data2);

					$cek_kehadirann = $this->m_absenbarcode->cek_double($recid_karyawan, $tanggal);
					// echo $cek_kehadiran->num_rows();
					if ($cek_kehadirann->num_rows() > 0) {
						$edit_hadir = array(
							'mdf_by'			=> $this->session->userdata('kar_id'),
							'mdf_date'			=> date('Y-m-d h:i:s'),
							'recid_karyawan'	=> $recid_karyawan,
							'tanggal'			=> $tanggal,
							'jam_masuk'			=> $jam_in,
							'jam_keluar'		=> $jam_out,
							'status'			=> $jenis_absen,
						);
						$this->m_absenbarcode->update_hadir2($recid_karyawan, $tanggal, $edit_hadir);
					} else {
						$data = array(
							'crt_date'          => date('Y-m-d H:i:s'),
							'crt_by'            => $this->session->userdata('kar_id'),
							'recid_karyawan'    => $recid_karyawan,
							'tanggal'           => $tanggal,
							'tgl_masuk'          => $tanggal,
							'jam_masuk'         => $jam_in,
							'jam_keluar'        => $jam_out,
							'status'            => $jenis_absen,
							'lokasi_masuk'      => 'Industri',
							'lokasi_pulang'      => 'Industri',
							'tmp_status'        => $jenis_absen,
							'perlu_validasi'    => '0',
							'ket_validasi'      => 'By Admin',
						);
						$this->m_absenbarcode->save_absen_masuk($data);
					}
				}
			}
		}
		redirect('Absen/Absen_view');
	}

	public function r_detail_hadir()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$role = $this->session->userdata('role_id');
			if ($role == '30') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_hadir_adm";
			} else if ($role == '26') {
				$data['dept_group'] = $this->m_absen->dept_group_spm($usr);
				$data['department'] = $this->m_absen->dept_spm($usr);
				$content = "absen/new_report/r_hadir_adm";
			} else if ($role == '23' or $role == '37') {
				$data['dept_group'] = $this->m_absen->dept_group_user($usr);
				$data['department'] = $this->m_absen->dept_user($usr);
				$content = "absen/new_report/r_hadir_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_hadir";
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function report_detail_hadir()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai =  $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_t = new DateTime($mulai);
		$sampai_t = new DateTime($sampai);

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			$divisi = $this->m_hris->list_dept_group();
			foreach ($divisi->result() as $dv) {
				array_push($fdivisi, $dv->dept_group);
			}
		}

		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			// $fdepartment = $fdepartment;
			$department = $this->m_hris->list_department();
			$fdepartment .= " and (";
			$no = 0;
			$cnt = $department->num_rows();
			foreach ($department->result() as $d) {
				$no = $no + 1;
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$d->nama_department'";
				} else {
					if ($no + 1 <= $cnt) {
						$fdepartment .= "d.nama_department = '$d->nama_department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
		}


		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			// $text = "$text and b.recid_bag = '$bagian'";
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			if ($cnt == 1) {
				$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['mulai'] = $mulai;
		$data['sampai'] = $sampai;
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/report_absen', $data);
		$this->load->view('layout/a_footer');
	}

	public function report_detail_hadir_adm()
	{

		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$mulai =  $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_t = new DateTime($mulai);
		$sampai_t = new DateTime($sampai);
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$dept_group = $user->dept_group;
		}

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			// $divisi = $this->m_hris->list_dept_group();
			// foreach ($divisi->result() as $dv) {
			// 	array_push($fdivisi, $dv->dept_group);
			if ($role == "23") {
				$dept_group = $this->m_absen->dept_group_user($usr);
				foreach ($dept_group->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			} else if ($role == '37') {
				array_push($fdivisi, $dept_group);
			} else if ($role == "26") {
				$dept_group = $this->m_absen->dept_group_spm();
				foreach ($dept_group->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			} else {
				$dept_group = $this->m_absen->dept_group_admin($usr);
				foreach ($dept_group->result() as $dv) {
					array_push($fdivisi, $dv->dept_group);
				}
			}
		}

		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			$where = "and (d.recid_department =";
			$recid_karyawan = $this->session->userdata('kar_id');
			if ($role == "23") {
				$dpt = $this->m_absen->dept_user($recid_karyawan);
			} else if ($role == '37') {
				$dpt = $this->m_absen->dept_by_dept_group($dept_group);
			} else if ($role == "26") {
				$dpt = $this->m_absen->dept_spm();
			} else {
				$dpt = $this->m_absen->dept_admin($recid_karyawan);
			}

			$dept = array();
			foreach ($dpt->result() as $bg) {
				array_push($dept, $bg->recid_department);
			}
			for ($i = 0; $i < count($dept); $i++) {
				if ($i < (count($dept) - 1)) {
					$where .= "'" . $dept[$i] . "' or d.recid_department = ";
				} else {
					$where .= "'" . $dept[$i] . "'";
				}
			}
			$where .= ")";
			$fdepartment = $where;
			// $dept = $this->m_hris->dept_by_divisi_my($where)->result();
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			// $fbagian = $fbagian;
			$fbagian .= " and (";
			$bag = array();
			if ($tingkatan > '7') {
				$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
				$no = 0;
				foreach ($bgn->result() as $bg) {
					if (array_key_exists($bg->indeks_hr, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->indeks_hr);
					}
				}
				// print_r($bag);
				for ($b = 0; $b < count($bag); $b++) {
					$no = $no + 1;
					if ($no < count($bag)) {
						$fbagian .= "b.indeks_hr = '$bag[$b]' or ";
					} else {
						$fbagian .= "b.indeks_hr = '$bag[$b]'";
					}
				}
			} else {
				if ($role == "26") {
					$bgn = $this->m_absen->bagian_spm();
					$cnt = $this->m_absen->bagian_spm($usr)->num_rows();
				} else if ($role == '37') {
					$bgn = $this->m_hris->karyawan_view_by_dept_group($dept_group);
				} else {
					$bgn = $this->m_hris->karyawan_view_by_id($usr);
					$cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
				}
				$no = 0;
				foreach ($bgn->result() as $bg) {
					$no = $no + 1;
					if (array_key_exists($bg->recid_bag, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->recid_bag);
						if ($no < $cnt) {
							$fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
						} else {
							$fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
						}
					}
				}
			}
			$fbagian .= ")";
		}


		$fkaryawan = "";
		$karyawan = $this->input->post('karyawan');
		if (!empty($karyawan)) {
			// $text = "$text and b.recid_bag = '$bagian'";
			$cnt = count($karyawan);
			$fkaryawan .= " and (";
			if ($cnt == 1) {
				$fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
					} else {
						$fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
					}
				}
			}
			$fkaryawan .= ")";
		} else {
			$fkaryawan = $fkaryawan;
		}

		// echo $fbagian;
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['fkaryawan'] = $fkaryawan;
		$data['mulai'] = $mulai;
		$data['sampai'] = $sampai;
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['dept_group'] = $dept_group;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/report_absen', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_bagian()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['jenis'] = $this->m_absen->jenis_tidak_masuk();
			$data['tahun'] = $this->m_absen->tahun_hk();
			if ($role == '30') {
				$data['dept_group'] = $this->m_absen->dept_group_admin($usr);
				$data['department'] = $this->m_absen->dept_admin($usr);
				$content = "absen/new_report/r_bagian_adm";
			} else {
				$data['dept_group'] = $this->m_hris->list_dept_group();
				$data['department'] = $this->m_hris->department_view();
				$content = "absen/new_report/r_bagian";
			}
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view($content);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function persentase_bagian()
	{

		$usr = $this->session->userdata('kar_id');
		$mulai =  $this->input->post('sejak');
		$sampai = $this->input->post('sampai');
		$mulai_t = new DateTime($mulai);
		$sampai_t = new DateTime($sampai);
		$jml_hari = $sampai_t->diff($mulai_t)->days + 1;

		// echo $jml_hari;

		$plushk = $this->m_absen->gh_by_tanggal($mulai, $sampai);
		$gh = $plushk->num_rows();

		$libur = $this->m_absen->cuber_by_tanggal2($mulai, $sampai);
		$minhk = $libur->num_rows();
		$date_start = $mulai;
		$date_end = $sampai;

		$selisih = '86400'; //selisih strtotime untuk satu hari

		$time_start = strtotime($date_start);
		$time_end = strtotime($date_end);
		$sm = 0;
		for ($i = $time_start; $i <= $time_end; $i = $i + $selisih) {
			//pengecekan apakah tanggal tersebut hari sabtu atau minggu 
			if ((date('D', $i) == 'Sun') or (date('D', $i) == 'Sat')) {
				$sm = $sm + 1;
				//$data[] = date('Y-m-d', $i); //hasil dalam bentuk array
			}
		}

		$jml_hk = $jml_hari - $sm - $minhk + $gh;

		// $norma = $this->input->post('norma');
		$norma = array(3, 12, 25);
		$jenis = $this->m_absen->jenis_tidak_masuk();
		$jenis_absen = array();
		foreach ($jenis->result() as $j) { // kecuali wfh, masuk siang, alpha, tugas organisasi
			if ($j->recid_jenisabsen != '8' and $j->recid_jenisabsen != '9' and $j->recid_jenisabsen != '10' and $j->recid_jenisabsen != '13') {
				array_push($jenis_absen, $j->recid_jenisabsen);
			}
		}
		$non_norma = array_diff($jenis_absen, $norma);
		$non_norma = array_values($non_norma);

		$jml_norma = "( status =";
		$jenis_ab = "( recid_jenisabsen = ";
		for ($n = 0; $n < count($norma); $n++) {
			if ($n < (count($norma) - 1)) {
				$jml_norma .= "'" . $norma[$n] . "' or status = ";
				$jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
			} else {
				$jml_norma .= "'" . $norma[$n] . "'";
				$jenis_ab .= "'" . $norma[$n] . "'";
			}
		}
		$jml_norma .= " )";
		$jenis_ab .= " )";

		$detail_norma = "Norma : ";
		$dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
		$cdnorma = $dnorma->num_rows();
		$no = 0;
		foreach ($dnorma->result() as $dn) {
			$no = $no + 1;
			if ($no < $cdnorma) {
				$detail_norma .= $dn->jenis . ", ";
			} else {
				$detail_norma .= $dn->jenis;
			}
		}

		$jml_nonnorma = "( status =";
		$jenis_abn = "( recid_jenisabsen = ";

		for ($nn = 0; $nn < count($non_norma); $nn++) {
			if ($nn < (count($non_norma) - 1)) {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
				$jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
			} else {
				$jml_nonnorma .= "'" . $non_norma[$nn] . "'";
				$jenis_abn .= "'" . $non_norma[$nn] . "'";
			}
		}
		$jml_nonnorma .= " )";
		$jenis_abn .= " )";

		$detail_nnorma = "Non Norma : ";
		$dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
		$cdnnorma = $dnnorma->num_rows();
		$no = 0;
		foreach ($dnnorma->result() as $dnn) {
			$no = $no + 1;
			if ($no < $cdnnorma) {
				$detail_nnorma .= $dnn->jenis . ", ";
			} else {
				$detail_nnorma .= $dnn->jenis;
			}
		}

		$fdivisi = array();
		$divisi = $this->input->post('divisi');
		if (!empty($divisi)) {
			for ($i = 0; $i < count($divisi); $i++) {
				array_push($fdivisi, $divisi[$i]);
			}
		} else {
			$divisi = $this->m_hris->list_dept_group();
			foreach ($divisi->result() as $dv) {
				array_push($fdivisi, $dv->dept_group);
			}
		}


		$fdepartment = "";
		$department = $this->input->post('departement');
		if (!empty($department)) {
			$cnt = count($department);
			$fdepartment .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$department[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fdepartment .= "d.nama_department = '$department[$i]' or ";
					} else {
						$fdepartment .= "d.nama_department = '$department[$i]'";
					}
				}
			}
			$fdepartment .= ")";
		} else {
			// $fdepartment = $fdepartment;
			$department = $this->m_hris->list_department();
			$fdepartment .= " and (";
			$no = 0;
			$cnt = $department->num_rows();
			foreach ($department->result() as $d) {
				$no = $no + 1;
				if ($cnt == 1) {
					$fdepartment .= "d.nama_department = '$d->nama_department'";
				} else {
					if ($no + 1 <= $cnt) {
						$fdepartment .= "d.nama_department = '$d->nama_department' or ";
					} else {
						$fdepartment .= "d.nama_department = '$d->nama_department'";
					}
				}
			}
			$fdepartment .= ")";
		}


		$fbagian = "";
		$bagian = $this->input->post('bagian10');
		if (!empty($bagian)) {
			$cnt = count($bagian);
			$fbagian .= " and (";
			for ($i = 0; $i < $cnt; $i++) {
				if ($cnt == 1) {
					$fbagian .= "b.recid_bag = '$bagian[0]'";
				} else {
					if ($i + 1 < $cnt) {
						$fbagian .= "b.recid_bag = '$bagian[$i]' or ";
					} else {
						$fbagian .= "b.recid_bag = '$bagian[$i]'";
					}
				}
			}
			$fbagian .= ")";
		} else {
			$fbagian = $fbagian;
			// $bagian = $this->m_hris->list_bagian();
			// $fbagian .= " and (";
			// $no = 0;
			// $cnt = $bagian->num_rows();
			// foreach ($bagian->result() as $d) {
			// 	$no = $no + 1;
			// 	if ($cnt == 1) {
			// 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 	} else {
			// 		if ($no + 1 <= $cnt) 
			// 		{
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
			// 		} else {
			// 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
			// 		}
			// 	}
			// }
			// $fbagian .= ")";
		}
		// echo $fbagian;

		$data['norma'] = $jml_norma;
		$data['non_norma'] = $jml_nonnorma;
		// $data['dept'] = $this->m_hris->list_dept_group();
		$data['fdivisi'] = $fdivisi;
		$data['divisi'] = $divisi;
		$data['fdepartment'] = $fdepartment;
		$data['fbagian'] = $fbagian;
		$data['jml_hkn'] = $jml_hk;
		$data['mulai'] = $mulai;
		$data['sampai'] = $sampai;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['detail_norma'] = $detail_norma;
		$data['detail_nnorma'] = $detail_nnorma;

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/new_report/rekap_persen_absen_bagian', $data);
		$this->load->view('layout/a_footer');
	}

	public function generate_absen_adjust()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$mulai = "2022-10-12";
			$sampai = "2022-10-27";
			while ($mulai <= $sampai) {
				$data = array(
					'crt_by'			=> $this->session->userdata('kar_id'),
					'crt_date'			=> date('Y-m-d h:i:s'),
					'recid_karyawan'	=> 1599,
					'tanggal'			=> $mulai,
					'jam_masuk'			=> "07:00:00",
					'jam_keluar'		=> "16:00:00",
					'status'			=> 1,
				);
				$result = $this->m_absen->generate_absen($data);
				$mulai = date('Y-m-d', strtotime('+1 days', strtotime($mulai)));
			}
			echo $mulai;
		} else {
			// redirect('Auth/keluar');
		}
	}

	function adjust_durasi()
	{
		$awal = '2024-08-01';
		$akhir = '2024-08-31';

		$izin = $this->m_absenbarcode->adjust_durasi($awal, $akhir);
		if ($izin->num_rows() > 0) {
			foreach ($izin->result() as $i) {
				if ($i->jenis == "Pulang") {
					$cek_jenis = $this->m_absenbarcode->cek_ja_hadir($i->recid_karyawan, $i->tgl_izin);
					if ($cek_jenis->num_rows() > 0) {
						foreach ($cek_jenis->result() as $k) {
							$jam_keluar1 = $k->jam_keluar;
							$status = $k->status;

							if ($status == 1) // kerja normal (11:30 - 12:30)
							{
								if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
									$jam_keluar = new DateTime("12:31"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 26) { /* Middle Shift 1 (11:00 - 12:00)*/
								if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
									$jam_keluar = new DateTime("12:01"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 27) {/* Middle Shift 2 (13:00 - 14:00)*/
								if ($jam_keluar1 > "13:00" and $jam_keluar1 < "14:01") {
									$jam_keluar = new DateTime("14:01"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 29) {/* Kerja Non Shift Ramadhan (11:00 - 12:00)*/
								if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
									$jam_keluar = new DateTime("12:01"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 30) {/* Middle Shift 3 (12:00 - 13.00)*/
								if ($jam_keluar1 > "12:00" and $jam_keluar1 < "13:01") {
									$jam_keluar = new DateTime("13:01"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 35) {/* Ganti Hari Kerja (11:30 - 12:30)*/
								if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
									$jam_keluar = new DateTime("12:31"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else if ($status == 37) {/* Middle Shift 4 (14:30 - 15:30) */
								if ($jam_keluar1 > "14:30" and $jam_keluar1 < "15:31") {
									$jam_keluar = new DateTime("15:31"); // jam scan
								} else {
									$jam_keluar = new DateTime($k->jam_keluar); // jam scan
								}
							} else {
								$jam_keluar = new DateTime($k->jam_keluar); // jam scan
							}
							$pulang_normal = new DateTime($k->jam_out);
							$selisih = $pulang_normal->diff($jam_keluar);
							$jam = $selisih->format('%h');
							$menit = $selisih->format('%i');
							if ($menit >= 60) {
								$jam = $jam + 1;
								$menit = $menit - 60;
							}
							if ($menit >= 0 && $menit <= 9) {
								$menit = "0" . $menit;
							}
							$durasi = $jam . ' jam ' . $menit . " menit";
							$over_durasi = $jam . "." . $menit;
						}
					} else {
						$pulang_normal = "00:00:00";
						$jam_masuk = "00:00:00";
						$jam_pulang = "00:00:00";
						$durasi = "00:00:00";
						$over_durasi = 0;
					}
				} else if ($i->jenis == "Keluar") {
					$jam_in = $i->jam_in;
					if ($jam_in == '') {
						$jam_in = null;
					} else {
						$jam_in = str_replace(" ", "", $jam_in);
					}

					$jam_out = $i->jam_out;
					if ($jam_out == '') {
						$jam_out = null;
					} else {
						$jam_out = str_replace(" ", "", $jam_out);
					}

					$cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $i->recid_karyawan and tanggal = '$i->tgl_izin'");
					foreach ($cek->result() as $c) {
						$status = $c->jenis . " - " . $c->keterangan;
						$jam_msk = $c->jam_in;
					}
					$jm = intval(substr($jam_msk, 0, 2));
					$mnt = substr($jam_msk, 2, 3);
					$jam_awal = ($jm + 4) . $mnt;
					$jam_akhir = ($jm + 5) . $mnt;

					if ($jam_out < $jam_awal and $jam_in < $jam_awal) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
						$over_durasi = $jam . '.' . $menit;
					} else if ($jam_out < $jam_awal and ($jam_in >= $jam_awal and $jam_in <= $jam_akhir)) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_awal);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
						$over_durasi = $jam . '.' . $menit;
					} else if ($jam_out < $jam_awal and $jam_in >= $jam_akhir) {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = ($selisih->format('%h')) - 1;
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
						$over_durasi = $jam . '.' . $menit;
					} else if ($jam_out >= $jam_awal and $jam_in <= $jam_akhir) {
						$over_durasi = "0.0";
					} elseif (($jam_out >= $jam_awal and $jam_out <= $jam_akhir) and $jam_in >= $jam_akhir) {
						//jam_masuk - jam keluar (12:30)
						$out = new DateTime("12:30:00");
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
						$over_durasi = $jam . '.' . $menit;
					} else {
						$out = new DateTime($jam_out);
						$in = new DateTime($jam_in);
						$selisih = $in->diff($out);
						$jam = $selisih->format('%h');
						$menit = $selisih->format('%i');
						if ($menit >= 0 && $menit <= 9) {
							$menit = "0" . $menit;
						}
						$hasil = $jam . " jam " . $menit . ' menit';
						$over_durasi = $jam . '.' . $menit;
					}
				} else {
					// terlambat
					$cek_jenis = $this->m_absenbarcode->cek_ja_hadir($i->recid_karyawan, $i->tgl_izin);
					$durasi = 0;
					$over_durasi = 0;
					if ($cek_jenis->num_rows() > 0) {
						foreach ($cek_jenis->result() as $k) {
							$jam_masuk_normal  = new DateTime($k->jam_in);
							$jam_scan_masuk = new DateTime($k->jam_masuk);
							$status = $k->status;

							$selisih = $jam_scan_masuk->diff($jam_masuk_normal);
							$jam = $selisih->format('%h');
							$menit = $selisih->format('%i');
							if ($menit >= 60) {
								$jam = $jam + 1;
								$menit = $menit - 60;
							}
							if ($menit >= 0 && $menit <= 9) {
								$menit = "0" . $menit;
							}
							$durasi = $jam . ' jam ' . $menit . " menit";
							$over_durasi = $jam . "." . $menit;
						}
					}
				}

				$data = array(
					'mdf_by'	 => $this->session->userdata('kar_id'),
					'mdf_date'	 => $this->session->userdata('kar_id'),
					'over_durasi' => $over_durasi,
				);
				$this->m_absen->edit_izin($i->izin_recid, $data);
			}
		} else {
			echo "tidak ada adjustment";
		}
	}
}
 
 
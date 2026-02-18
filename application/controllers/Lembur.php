<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lembur extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_absen', 'm_hris', 'm_lembur', 'm_absenbarcode'));
		// ini_set('max_execution_time', 600);
		ini_set('memory_limit', "1024M");
		ob_start(); # add this
	}

	/* CUT OFF LEMBUR */
	public function cutoff_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['cutlembur'] = $this->m_lembur->cutoff_lembur();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/cutoff/cutoff_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cutoff_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/cutoff/cutoff_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cutoff_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$tgl_dari = $this->input->post('tgl_dari');
			$tgl_sampai = $this->input->post('tgl_sampai');
			$note = $this->input->post('note');
			for ($i = 0; $i < count($this->input->post('bulan')); $i++) // looping sebanyak multi select
			{

				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d H:i:s'),
					'tahun'					=> $tahun,
					'bulan'					=> $bulan[$i],
					'periode_awal'				=> $tgl_dari[$i],
					'periode_akhir'			=> $tgl_sampai[$i],
					'note'					=> $note,
				);
				$this->m_lembur->cutoff_insert($data);
			}
			redirect('Lembur/cutoff_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cutoff_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$id = $this->uri->segment(3);
			$data['cutoff'] = $this->m_lembur->cutoff_lembur_by_id($id);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/cutoff/cutoff_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cutoff_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_clembur = $this->input->post('recid_clembur');
			$tahun = $this->input->post('tahun');
			$bulan = $this->input->post('bulan');
			$tgl_dari = $this->input->post('tgl_dari');
			$tgl_sampai = $this->input->post('tgl_sampai');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'tahun'					=> $tahun,
				'bulan'					=> $bulan,
				'periode_awal'			=> $tgl_dari,
				'periode_akhir'			=> $tgl_sampai,
				'note'					=> $note,
			);
			$this->m_lembur->cutoff_update($data, $recid_clembur);
			redirect('Lembur/cutoff_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_cutoff()
	{
		$tahun = $this->input->post('tahun');
		$cutoff = $this->m_lembur->cutoff_by_tahun($tahun);
		$data = array();
		foreach ($cutoff as $datas) {
			$a = array($datas->recid_clembur, $datas->bulan);
			array_push($data, $a);
		}
		echo json_encode($data);
	}

	/* END CUT OFF LEMBUR */

	/* MASTER BUDGET */
	public function masterbudget_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['budget'] = $this->m_lembur->master_budget_group();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/master/masterbudget_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_tahun()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['tahun'] = $this->m_lembur->master_budget_tahun();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/new_report/tahun_report', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_cek()
	{
		$tahun = $this->input->post('tahun');
		$bagian = $this->input->post('bagian');
		$mbl = $this->m_lembur->cek_masterbudget($tahun, $bagian);
		$cek = "";
		if ($mbl->num_rows() > 0) {
			$cek = "Ada";
		} else {
			$cek = "Tidak ada";
		}
		echo json_encode($cek);
	}

	public function masterbudget_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/master/masterbudget_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tahun = $this->input->post('tahun');
			echo $tahun;
			$recid_bag = $this->input->post('recid_bag');
			$recid_clembur = $this->input->post('recid_clembur');
			$bulan = $this->input->post('bulan');
			$jml_jam = $this->input->post('jml_jam');
			$note = $this->input->post('note');
			// echo $recid_bag;
			for ($i = 0; $i < count($this->input->post('bulan')); $i++) // looping sebanyak multi select
			{
				if ($bulan[$i] == 'Januari' || $bulan[$i] == 'Februari' || $bulan[$i] == 'Maret') {
					$kuartal = 'I';
				} else if ($bulan[$i] == 'April' || $bulan[$i] == 'Mei' || $bulan[$i] == 'Juni') {
					$kuartal = 'II';
				} else if ($bulan[$i] == 'Juli' || $bulan[$i] == 'Agustus' || $bulan[$i] == 'September') {
					$kuartal = 'III';
				} else {
					$kuartal = 'IV';
				}
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d H:i:s'),
					'recid_bag'				=> $recid_bag,
					'recid_clembur'			=> $recid_clembur[$i],
					'tahun'					=> $tahun,
					'bulan'					=> $bulan[$i],
					'kuartal'				=> $kuartal,
					'jml_jam'				=> $jml_jam[$i],
					'note'					=> $note,
				);
				$this->m_lembur->masterbudget_insert($data);
			}
			redirect('Lembur/masterbudget_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bagian = $this->uri->segment(3);
			$tahun = $this->uri->segment(4);
			$data['master'] = $this->m_lembur->masterbudget_total($bagian, $tahun);
			$data['budget'] = $this->m_lembur->masterbudget_detail($bagian, $tahun);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/master/masterbudget_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bagian = $this->uri->segment(3);
			$tahun = $this->uri->segment(4);
			$data['budget'] = $this->m_lembur->masterbudget_detail($bagian, $tahun);
			$data['bagian'] = $this->m_hris->bagian_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/master/masterbudget_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masterbudget_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tahun = $this->input->post('tahun');
			$recid_bag = $this->input->post('recid_bag');
			$bulan = $this->input->post('bulan');
			$jml_jam = $this->input->post('jml_jam');
			$recid_mbl = $this->input->post('recid_mbl');
			$note = $this->input->post('note');
			for ($i = 0; $i < count($this->input->post('bulan')); $i++) // looping sebanyak multi select
			{
				if ($bulan[$i] == 'Januari' || $bulan[$i] == 'Februari' || $bulan[$i] == 'Maret') {
					$kuartal = 'I';
				} else if ($bulan[$i] == 'April' || $bulan[$i] == 'Mei' || $bulan[$i] == 'Juni') {
					$kuartal = 'II';
				} else if ($bulan[$i] == 'Juli' || $bulan[$i] == 'Agustus' || $bulan[$i] == 'September') {
					$kuartal = 'III';
				} else {
					$kuartal = 'IV';
				}
				$data = array(
					'recid_bag'				=> $recid_bag,
					'tahun'					=> $tahun,
					'bulan'					=> $bulan[$i],
					'kuartal'				=> $kuartal,
					'jml_jam'				=> $jml_jam[$i],
					'note'					=> $note,
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d H:i:s'),
				);
				$this->m_lembur->masterbudget_update($data, $recid_mbl[$i]);
			}
			redirect('Lembur/masterbudget_view');
		} else {
			redirect('Auth/keluar');
		}
	}
	/* END MASTER BUDGET */

	// #################################### PENGAJUAN LEMBUR #######################################

	public function stkl_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hari_ini = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function stkl_view_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hari_ini = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_delete', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function stkl_periode()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		// $usr = 1090;
		// $role = 23;
		$cek_usr = $this->m_hris->cek_usr($usr);
		// Initialize department variable to prevent undefined variable error
		$department = '';
		$dept_group = '';
		$nama = '';
		$bagian = '';
		$recid_bag = '';
		$jabatan = '';
		$tingkatan = '';
		$recid_struktur = '';
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$recid_struktur = $user->recid_struktur;
			$department = $user->nama_department;
			$dept_group = $user->dept_group;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25') {
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->stkl_periode($tgl_awal, $tgl_akhir);
			} else {
				$query2 = $this->m_lembur->stkl_periode_status($tgl_awal, $tgl_akhir, $jenis);
			}
		} else if ($role == '23') {
			// echo $usr;
			$bag = array();
			$bagian = "b.indeks_hr = ";
			// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
			$bgn = $this->m_hris->bagian_view_by_atasan($usr);

			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}

			for ($i = 0; $i < count($bag); $i++) {
				// echo $bag[$i] . "<br>";
				$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
				foreach ($iindex->result() as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			// echo $bagian;
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->stkl_periode_admbagian($tgl_awal, $tgl_akhir, $bagian);
			} else {
				$query2 = $this->m_lembur->stkl_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
			}
		}
		// else if($role == '26'){ // spm
		// 	// echo $usr;
		// 	$bag = array();
		// 	$bagian = "b.indeks_hr = ";
		// 	// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
		// 	$bgn = $this->m_hris->bagian_by_recid2('140');

		// 	$iindex = $this->m_hris->bagian_by_recid2('140');
		//     foreach ($iindex->result() as $s) {
		//         $indeks_hr = $s->indeks_hr;
		// 		$bagian .= "'" . $indeks_hr . "'";
		//     }
		// 	// echo $bagian;
		// 	if($jenis == 'Semua')
		// 	{
		// 		$query2 = $this->m_lembur->stkl_periode_admbagian($tgl_awal, $tgl_akhir,$bagian);
		// 	}else{
		// 		$query2 = $this->m_lembur->stkl_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
		// 	}
		// }
		else if ($role == '41') {
			// echo $usr;
			$bag = array();
			$bagian = "b.indeks_hr = ";
			// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
			$bgn = $this->m_hris->prd_view_by_atasan('920');

			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}

			for ($i = 0; $i < count($bag); $i++) {
				// echo $bag[$i]."<br>";
				$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
				foreach ($iindex->result() as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			// echo $bagian;
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->stkl_periode_admbagian($tgl_awal, $tgl_akhir, $bagian);
			} else {
				$query2 = $this->m_lembur->stkl_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
			}
		} else {
			if ($role == '30' or $role == '26' or $role == '35') {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "b.indeks_hr = ";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					// echo $bag[$i]."<br>";
					$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
					foreach ($iindex->result() as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				// echo $bagian;
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_periode_admbagian($tgl_awal, $tgl_akhir, $bagian);
				} else {
					$query2 = $this->m_lembur->stkl_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
				}
			} else {
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_periode_deptgroup($tgl_awal, $tgl_akhir, $department);
				} else {
					$query2 = $this->m_lembur->stkl_periode_status_deptgroup($tgl_awal, $tgl_akhir, $jenis, $department);
				}
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			$tmb = "";

			if ($role == '1'  or $role == '3' or $role == '5') {
				// $tmb .= "<button type='button' class='btn btn-danger btn-xs' onclick='confirmDelete(".$r->recid_stkl.")'><span class='fa fa-trash'></span></button>";
				$tmb .= "<button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
			}

			if ($r->approval == "Belum Acc Manager") {
				if ($role == '1' or ($tingkatan >= 8 and $tingkatan < 10) and $recid_struktur != 11) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($recid_struktur == 11 and $r->recid_struktur == 11 and ($tingkatan >= 8 and $tingkatan < 10)) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($tingkatan >= 10 and $recid_struktur == '33') {
					// PPIC langsung approval GM
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					if ($tingkatan < 8 and ($role != '1' and $role != '3' and $role != '5')) {
						$tmb .= "<a href='" . base_url() . "Lembur/stkl_edit/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Edit'><button class='btn btn-info btn-xs'><span class='fa fa-pencil'></span></button></a>
			<button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
					} else {
						// Tidak aksi apapun
					}
				}
			} else if ($r->approval == "Acc Manager") {
				if ($role == '1' or ($recid_struktur == '11' and $tingkatan >= 8) or $tingkatan >= 10) {
					if ($r->dept_group == 'Middle Office') {
						//Acc Pa Ade / Bu Anita
						if ($tingkatan >= 10) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Direksi'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// bukan GM ke atas tidak ada aksi
						}
					} else {
						// Bukan Middle langsung Acc HC
						if ($recid_struktur == '11' and $tingkatan >= 8) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval $r->dept_group'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// Bukan akun Asman Up HC Tidak ada aksi apapun
						}
					}
				} else {
					// Bukan HC dan Pa Ade, Tidak ada tombol aksi apapun
				}
			} else if ($r->approval == "Tidak Acc Manager" or $r->approval == "Tidak Acc HC" or $r->approval == "Tolak Direksi" or $r->approval == "Tidak Acc Realisasi") {
				//tidak ada tombol aksi apapun
			} else if ($r->approval == "Acc Direksi") {
				//Acc HC Setelah Acc Direksi
				if ($recid_struktur == '11' and $tingkatan >= 8) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval HC'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					// Bukan akun Asman Up HC Tidak ada aksi apapun
				}
			} else if ($r->approval == "Acc HC") {
				if (($role == '1' or $tingkatan < 8) and $role != '5' and $role != '3' and $role != '25') {
					//tombol realisasi bagian
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($r->approval == "Realisasi Bagian") {
				if ($role == '1' or $tingkatan >= 8) {
					//tombol realiasi acc realisasi manager
					$tmb .= "<a href='" . base_url() . "Lembur/acc_realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Realisasi'><button class='btn btn-info btn-xs'><span class='fa fa-check-square'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($role == '1'  or $role == '3' or $r->approval == 'Acc Realisasi Manager') {
				if ($role == '1' or $role == '3' or ($recid_struktur == '11' and $tingkatan >= 7) and $role != '25') {
					//realisasi mega
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// bukan mega tidak ada aksi
				}
			} else {
				// Tidak ada tombol apapun
			}


			$tmb .= "<button class='btn btn-secondary btn-xs' data-toggle='modal' data-target='#myModal' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Karyawan Lembur'><span class='fa fa-user'></span></button>";

			$cek_wf = $this->m_lembur->get_workflow($r->recid_stkl);
			if ($cek_wf->num_rows() > 0) {
				$tmb .= "<button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal2' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Work Flow'><span class='fa fa-map-signs'></span></button>";
			}

			if ($r->status == 'realisasi' or $r->status == 'selesai') {
				$tot_jam = $r->totjam_real;
			} else {
				$tot_jam = $r->total_jam;
			}

			if ($r->jemputan == '0') {
				$jemputan = 'Jemputan';
			} else if ($r->jemputan == '1') {
				$jemputan = 'Uang Transport';
			} else {
				$jemputan = $r->jemputan;
			}

			if ($r->makan == '0') {
				$makan = 'Catering';
			} else if ($r->makan == '1') {
				$makan = 'Uang Makan';
			} else {
				$makan = $r->makan;
			}

			if ($r->flag_holiday == '0') {
				$flag_holiday = 'Hari Biasa';
			} else if ($r->flag_holiday == '1') {
				$flag_holiday = 'Akhir Pekan';
			} else if ($r->flag_holiday == '2') {
				$flag_holiday = 'Libur Nasional';
			} else if ($r->flag_holiday == '3') {
				$flag_holiday = 'Libur Perusahaan';
			} else {
				$flag_holiday = $r->flag_holiday;
			}

			if ($r->status == 'pengajuan') {
				$status = "<span class='badge progress-bar-danger'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Belum Acc Manager") {
					$approval = "<span class='badge progress-bar-danger'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Manager') {
					$approval = "<span class='badge progress-bar-warning'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc HC') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Direksi') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				}
			} else if ($r->status == 'realisasi') {
				$status = "<span class='badge progress-bar-warning'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Realisasi Bagian") {
					$approval = "<span class='badge progress-bar-info'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Realisasi Manager') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Tidak Acc Realisasi') {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				}
			} else {
				$status = "<span class='badge progress-bar-success'>" . ucfirst($r->status) . "</span>";
				$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
			}



			$get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
			$pekerjaan = "";
			$jml_kerja = $get_pekerjaan->num_rows();
			if ($jml_kerja > 0) {
				$i = 0;
				foreach ($get_pekerjaan->result() as $p) {
					$i = $i + 1;
					if ($i < $jml_kerja) {
						$pekerjaan .= $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= $p->pekerjaan;
					}
				}
			}

			// $no = 0;
			if ($role == 1 or $role == 3 or $role == 5) {
				$data[] = array(
					$no = $no + 1,
					$r->recid_stkl,
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
				);
			} else {
				$data[] = array(
					$no = $no + 1,
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
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

	public function stkl_approve()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hari_ini = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_approve', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function stkl_periode_app()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		// Initialize department variable to prevent undefined variable error
		$department = '';
		$dept_group = '';
		$nama = '';
		$bagian = '';
		$recid_bag = '';
		$jabatan = '';
		$tingkatan = '';
		$recid_struktur = '';
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$recid_struktur = $user->recid_struktur;
			$department = $user->nama_department;
			$dept_group = $user->dept_group;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25' or $role == '') {
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->stkl_periode_bulkhc($tgl_awal, $tgl_akhir);
			} else {
				if ($jenis == 'Realisasi Bagian') {
					$query2 = $this->m_lembur->stkl_periode_status_bulkhc2($tgl_awal, $tgl_akhir, $jenis);
				} else if ($jenis == 'Belum Acc Manager') {
					$query2 = $this->m_lembur->stkl_periode_status_bulkhc3($tgl_awal, $tgl_akhir, $jenis);
				} else {
					$query2 = $this->m_lembur->stkl_periode_status_bulkhc($tgl_awal, $tgl_akhir, $jenis);
				}
			}
		} else if ($role == '24') {
			$query2 = $this->m_lembur->stkl_periode_bulkdir($tgl_awal, $tgl_akhir);
		} else {
			if ($role == '30' or $role == '26' or $role == '35') {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "b.indeks_hr = ";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					// echo $bag[$i]."<br>";
					$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
					foreach ($iindex->result() as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				// echo $bagian;
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_periode_admbagian($tgl_awal, $tgl_akhir, $bagian);
				} else {
					$query2 = $this->m_lembur->stkl_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
				}
			} else {
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_periode_deptgroup($tgl_awal, $tgl_akhir, $department);
				} else {
					$query2 = $this->m_lembur->stkl_periode_status_deptgroup($tgl_awal, $tgl_akhir, $jenis, $department);
				}
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			$tmb = "";

			if ($role == '1' or $role == '5') {
				// $tmb .= "<button type='button' class='btn btn-danger btn-xs' onclick='confirmDelete(".$r->recid_stkl.")'><span class='fa fa-trash'></span></button>";
				$tmb .= "<button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
			}

			if ($r->approval == "Belum Acc Manager") {
				if ($role == '1' or ($tingkatan >= 8 and $tingkatan < 10) and $recid_struktur != 11) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($recid_struktur == 11 and $r->recid_struktur == 11 and ($tingkatan >= 8 and $tingkatan < 10)) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($tingkatan >= 10 and $recid_struktur == '33') {
					// PPIC langsung approval GM
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					if ($tingkatan < 8 and ($role != '1' and $role != '3' and $role != '5')) {
						$tmb .= "<a href='" . base_url() . "Lembur/stkl_edit/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Edit'><button type='button' class='btn btn-info btn-xs'><span class='fa fa-pencil'></span></button></a>
			<button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
					} else {
						// Tidak aksi apapun
					}
				}
			} else if ($r->approval == "Acc Manager") {
				if ($role == '1' or ($recid_struktur == '11' and $tingkatan >= 8) or $tingkatan >= 10) {
					if ($r->dept_group == 'Middle Office') {
						//Acc Pa Ade / Bu Anita
						if ($tingkatan >= 10) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Direksi'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// bukan GM ke atas tidak ada aksi
						}
					} else {
						// Bukan Middle langsung Acc HC
						if ($recid_struktur == '11' and $tingkatan >= 8) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval $r->dept_group'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// Bukan akun Asman Up HC Tidak ada aksi apapun
						}
					}
				} else {
					// Bukan HC dan Pa Ade, Tidak ada tombol aksi apapun
				}
			} else if ($r->approval == "Tidak Acc Manager" or $r->approval == "Tidak Acc HC" or $r->approval == "Tolak Direksi" or $r->approval == "Tidak Acc Realisasi") {
				//tidak ada tombol aksi apapun
			} else if ($r->approval == "Acc Direksi") {
				//Acc HC Setelah Acc Direksi
				if ($recid_struktur == '11' and $tingkatan >= 8) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval HC'><button type='button' class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					// Bukan akun Asman Up HC Tidak ada aksi apapun
				}
			} else if ($r->approval == "Acc HC") {
				if (($role == '1' or $tingkatan < 8) and $role != '5' and $role != '25') {
					//tombol realisasi bagian
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button type='button' class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($r->approval == "Realisasi Bagian") {
				if ($role == '1' or $tingkatan >= 8) {
					//tombol realiasi acc realisasi manager
					$tmb .= "<a href='" . base_url() . "Lembur/acc_realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Realisasi'><button type='button' class='btn btn-info btn-xs'><span class='fa fa-check-square'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($role == '1' or $r->approval == 'Acc Realisasi Manager') {
				if ($role == '1' or ($recid_struktur == '11' and $tingkatan >= 7) and $role != '25') {
					//realisasi mega
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button type='button' class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// bukan mega tidak ada kasi
				}
			} else {
				// Tidak ada tombol apapun
			}


			$tmb .= "<button type='button' class='btn btn-secondary btn-xs' data-toggle='modal' data-target='#myModal' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Karyawan Lembur'><span class='fa fa-user'></span></button>";

			$cek_wf = $this->m_lembur->get_workflow($r->recid_stkl);
			if ($cek_wf->num_rows() > 0) {
				$tmb .= "<button type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal2' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Work Flow'><span class='fa fa-map-signs'></span></button>";
			}

			if ($r->status == 'realisasi' or $r->status == 'selesai') {
				$tot_jam = $r->totjam_real;
			} else {
				$tot_jam = $r->total_jam;
			}

			if ($r->jemputan == '0') {
				$jemputan = 'Tidak';
			} else if ($r->jemputan == '1') {
				$jemputan = 'Ada';
			} else {
				$jemputan = $r->jemputan;
			}

			if ($r->makan == '0') {
				$makan = 'Tidak';
			} else if ($r->makan == '1') {
				$makan = 'Ada';
			} else {
				$makan = $r->makan;
			}

			if ($r->flag_holiday == '0') {
				$flag_holiday = 'Hari Biasa';
			} else if ($r->flag_holiday == '1') {
				$flag_holiday = 'Akhir Pekan';
			} else if ($r->flag_holiday == '2') {
				$flag_holiday = 'Libur Nasional';
			} else if ($r->flag_holiday == '3') {
				$flag_holiday = 'Libur Perusahaan';
			} else {
				$flag_holiday = $r->flag_holiday;
			}

			if ($r->status == 'pengajuan') {
				$status = "<span class='badge progress-bar-danger'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Belum Acc Manager") {
					$approval = "<span class='badge progress-bar-danger'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Manager') {
					$approval = "<span class='badge progress-bar-warning'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc HC') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Direksi') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				}
			} else if ($r->status == 'realisasi') {
				$status = "<span class='badge progress-bar-warning'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Realisasi Bagian") {
					$approval = "<span class='badge progress-bar-info'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Realisasi Manager') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Tidak Acc Realisasi') {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				}
			} else {
				$status = "<span class='badge progress-bar-success'>" . ucfirst($r->status) . "</span>";
				$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
			}



			$get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
			$pekerjaan = "";
			$jml_kerja = $get_pekerjaan->num_rows();
			if ($jml_kerja > 0) {
				$i = 0;
				foreach ($get_pekerjaan->result() as $p) {
					$i = $i + 1;
					if ($i < $jml_kerja) {
						$pekerjaan .= $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= $p->pekerjaan;
					}
				}
			}

			// $no = 0;
			if ($role == 1) {
				$data[] = array(
					$no = $no + 1,
					$r->recid_stkl,
					"<input type='checkbox' name='cek[]' value=" . $r->recid_stkl . ">",
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
				);
			} else {
				$data[] = array(
					$no = $no + 1,
					"<input type='checkbox' name='cek[]' value=" . $r->recid_stkl . ">",
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
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

	public function bulk_approve()
	{
		$role = $this->session->userdata('role_id');
		$checkbox = $this->input->post('cek');
		echo $role . "<br>";
		for (!$i = 0; $i < count($checkbox); $i++) {
			$recid_stkl = $checkbox[$i];
			$stkl = $this->m_lembur->stkl_id($recid_stkl);
			foreach ($stkl->result() as $s) {
				$approval = $s->approval;
				$status = $s->status;
				if ($role == '25') {
					if ($approval == "Belum Acc Manager") {
						$approval = "Acc Hc";
						$status = "pengajuan";
					} else if ($approval == "Acc Direksi") {
						$approval = "Acc Hc";
						$status = "pengajuan";
					} else if ($approval == "Acc Manager") {
						$approval = "Acc Hc";
						$status = "pengajuan";
					} else if ($approval == "Realisasi Bagian") {
						$approval = "Acc Realisasi Manager";
						$status = "realisasi";
					} else {
						$approval = $approval;
						$status = $status;
					}
				} else if ($role == '24') {
					$approval = "Acc Direksi";
					$status == 'pengajuan';
				} else {
					$approval = $approval;
					$status = $status;
				}

				$data = array(
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d H:i:s'),
					'status'				=> $status,
					'approval'				=> $approval,
				);
				$this->m_lembur->stkl_update($data, $recid_stkl);
				// collect workflow
				$data_wf = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d H:i:s'),
					'recid_stkl'			=> $recid_stkl,
					'status'				=> $status,
					'approval'				=> $approval,
					'pic_wf'				=> $this->session->userdata('kar_id'),
				);
				$this->m_lembur->wf_insert($data_wf);

				// $this->email_pengajuan($recid_stkl);
			}
		}
		redirect('Lembur/stkl_approve');
	}

	public function stkl_periode_delete()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		// Initialize department variable to prevent undefined variable error
		$department = '';
		$dept_group = '';
		$nama = '';
		$bagian = '';
		$recid_bag = '';
		$jabatan = '';
		$tingkatan = '';
		$recid_struktur = '';
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$recid_struktur = $user->recid_struktur;
			$department = $user->nama_department;
			$dept_group = $user->dept_group;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25') {
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->stkl_del_periode($tgl_awal, $tgl_akhir);
			} else {
				$query2 = $this->m_lembur->stkl_del_periode_status($tgl_awal, $tgl_akhir, $jenis);
			}
		} else {
			if ($role == '30' or $role == '26' or $role == '35') {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					// echo $bag[$i]."<br>";
					$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
					foreach ($iindex->result() as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				// echo $bagian;
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_del_periode_admbagian($tgl_awal, $tgl_akhir, $bagian);
				} else {
					$query2 = $this->m_lembur->stkl_del_periode_status_admbagian($tgl_awal, $tgl_akhir, $jenis, $bagian);
				}
			} else {
				if ($jenis == 'Semua') {
					$query2 = $this->m_lembur->stkl_del_periode_deptgroup($tgl_awal, $tgl_akhir, $department);
				} else {
					$query2 = $this->m_lembur->stkl_del_periode_status_deptgroup($tgl_awal, $tgl_akhir, $jenis, $department);
				}
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			$tmb = "";
			$tmb .= "<button class='btn btn-secondary btn-xs' data-toggle='modal' data-target='#myModal' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Karyawan Lembur'><span class='fa fa-user'></span></button>";

			$cek_wf = $this->m_lembur->get_workflow($r->recid_stkl);
			if ($cek_wf->num_rows() > 0) {
				$tmb .= "<button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal2' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Work Flow'><span class='fa fa-map-signs'></span></button>";
			}

			if ($r->status == 'realisasi' or $r->status == 'selesai') {
				$tot_jam = $r->totjam_real;
			} else {
				$tot_jam = $r->total_jam;
			}

			if ($r->jemputan == '0') {
				$jemputan = 'Tidak';
			} else if ($r->jemputan == '1') {
				$jemputan = 'Ada';
			} else {
				$jemputan = $r->jemputan;
			}

			if ($r->makan == '0') {
				$makan = 'Tidak';
			} else if ($r->makan == '1') {
				$makan = 'Ada';
			} else {
				$makan = $r->makan;
			}

			if ($r->flag_holiday == '0') {
				$flag_holiday = 'Hari Biasa';
			} else if ($r->flag_holiday == '1') {
				$flag_holiday = 'Akhir Pekan';
			} else if ($r->flag_holiday == '2') {
				$flag_holiday = 'Libur Nasional';
			} else if ($r->flag_holiday == '3') {
				$flag_holiday = 'Libur Perusahaan';
			} else {
				$flag_holiday = $r->flag_holiday;
			}

			if ($r->status == 'pengajuan') {
				$status = "<span class='badge progress-bar-danger'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Belum Acc Manager") {
					$approval = "<span class='badge progress-bar-danger'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Manager') {
					$approval = "<span class='badge progress-bar-warning'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc HC') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Direksi') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				}
			} else if ($r->status == 'realisasi') {
				$status = "<span class='badge progress-bar-warning'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Realisasi Bagian") {
					$approval = "<span class='badge progress-bar-info'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Realisasi Manager') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Tidak Acc Realisasi') {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				}
			} else {
				$status = "<span class='badge progress-bar-success'>" . ucfirst($r->status) . "</span>";
				$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
			}



			$get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
			$pekerjaan = "";
			$jml_kerja = $get_pekerjaan->num_rows();
			if ($jml_kerja > 0) {
				$i = 0;
				foreach ($get_pekerjaan->result() as $p) {
					$i = $i + 1;
					if ($i < $jml_kerja) {
						$pekerjaan .= $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= $p->pekerjaan;
					}
				}
			}

			// $no = 0;
			if ($role == 1) {
				$data[] = array(
					$no = $no + 1,
					$r->recid_stkl,
					$tmb,
					$r->alasan_hapus,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
				);
			} else {
				$data[] = array(
					$no = $no + 1,
					$tmb,
					$r->alasan_hapus,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
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

	public function karyawan_lembur()
	{
		$recid_stkl = $this->input->post('recid_stkl');
		$query2 = $this->m_lembur->karyawan_lembur_stkl($recid_stkl);
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d-M-Y", strtotime($r->tgl_lembur));
			$jam_barcode = $this->m_lembur->cek_kehadiran($r->tgl_lembur, $r->recid_karyawan);
			$dup = $this->m_lembur->cek_dup_emp($r->tgl_lembur, $r->recid_karyawan);
			if ($dup->num_rows() > 1) {
				$fdup = 1;
			} else {
				$fdup = 0;
			}
			if ($jam_barcode->num_rows() > 0) {
				foreach ($jam_barcode->result() as $jb) {
					$jam_masuk = $jb->jam_masuk;
					$jam_masuk2 = $jb->jam_masuk;
					$jam_pulang = $jb->jam_keluar;
					$tgl_pulang = $jb->tgl_pulang;
					$tgl_masuk = $jb->tgl_masuk;
					$jam_in = $jb->jam_in;
					$jam_in2 = $jb->jam_in;
					$jam_in = date_create($jam_in);
					$cjam_in = date_add($jam_in, date_interval_create_from_date_string('1 minutes'));
					$jam_in = date_format($jam_in, 'H:i:s');
					if ($jam_masuk > $jam_in2  and $jam_masuk < $jam_in) {
						$jam_masuk = $jam_in2;
						// echo "jadi normal $jam_in2";
					}
				}
			} else {
				$jam_masuk = "-";
				$jam_pulang = "-";
				$jam_masuk2 = "-";
				$tgl_pulang = $r->tgl_lembur;
				$tgl_masuk = $r->tgl_lembur;
			}

			$cek_holiday = $this->m_lembur->cek_holiday($r->tgl_lembur);
			if ($cek_holiday->num_rows() > 0) {
				$fholi = "1";
			} else {
				//cek ganti hari
				$cek_gh = $this->m_absen->gh_by_date($r->tgl_lembur);
				if ($cek_gh->num_rows() > 0) {
					$fholi = "0";	//weekday OT
				} else {
					$nameOfDay = date('D', strtotime($r->tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$fholi = "1";	//weekend
					} else {
						$fholi = "0";	//weekday OT
					}
				}
			}

			$krg_jam = 0;
			$cnt_jam = 0;

			if ($fholi == 1) {
				// jam barcode masuk - jam barcode pulang
				if (($r->jam_mulai == "06:00:00" && $r->jam_sls == "14:00:00") || ($r->jam_mulai == "14:00:00" && $r->jam_sls == "22:00:00") || ($r->jam_mulai == "22:00:00" && $r->jam_sls == "06:00:00")) {
					if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
						$lamlem = "0 jam";
					} else if ($jam_masuk <= $r->jam_mulai) {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $r->jam_mulai);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							$lamlem = "$jam Jam $menit Menit";
						}
					} else {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							if ($jam < 0) {
								$lamlem =  "0 jam";
							} else {
								$lamlem = "$jam Jam $menit Menit";
							}
						}
					}
				} else {
					// -----asli ----
					if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
						$lamlem = "0 jam";
					} else if ($jam_masuk <= $r->jam_mulai) {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $r->jam_mulai);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							$kaljam = $jam + ($menit / 60);
							if ($kaljam >= 5 and $kaljam <= 10) {
								$krg_jam = 1;
							} else if ($kaljam > 10 and $kaljam < 15) {
								$krg_jam = 1.5;
							} else if ($kaljam >= 15 and $kaljam < 20) {
								$krg_jam = 2;
							} else if ($kaljam >= 20 and $kaljam < 24) {
								$krg_jam = 2.5;
							} else {
								$krg_jam = $krg_jam;
							}
							$totlem = $kaljam - $krg_jam;
							if (is_numeric($totlem) && floor($totlem) != $totlem) {
								$total_jam = explode(".", $totlem);
								$j = $total_jam[0];
								$m = $total_jam[1];
								$m = "0." . $m;
								$m = (float)$m;
								$m = round($m * 60);
								// $m = round($m * 60);
								// if($m == 60)
								// {
								// 	$j = $j+1;
								// 	$m = 0;
								// }
								// $lamlem = $totlem;
								$lamlem = "$j jam $m menit";
							} else {
								$lamlem = "$totlem jam";
							}
							// $lamlem = $kaljam;
						}
					} else {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							$kaljam = $jam + ($menit / 60);
							if ($kaljam >= 5 and $kaljam < 10) {
								$krg_jam = 1;
							} else if ($kaljam >= 10 and $kaljam < 15) {
								$krg_jam = 1.5;
							} else if ($kaljam >= 15 and $kaljam < 20) {
								$krg_jam = 2;
							} else if ($kaljam >= 20 and $kaljam < 24) {
								$krg_jam = 2.5;
							} else {
								$krg_jam = $krg_jam;
							}
							$totlem = $kaljam - $krg_jam;
							if (is_numeric($totlem) && floor($totlem) != $totlem) {
								$total_jam = explode(".", $totlem);
								$j = $total_jam[0];
								$m = $total_jam[1];
								$m = "0." . $m;
								$m = (float)$m;
								$m = round($m * 60);
								// if($m == 60)
								// {
								// 	$j = $j+1;
								// 	$m = 0;
								// }
								// $lamlem = $totlem;
								$lamlem = "$j jam $m menit";
							} else {
								$lamlem = "$totlem jam";
							}
							// $lamlem = $kaljam;
						}
					}
				}
			} else { /* HARI BIASA TIDAk ADA LEMBUR SHIFT*/
				if (($r->jam_mulai == "06:00:00" && $r->jam_sls == "14:00:00") || ($r->jam_mulai == "14:00:00" && $r->jam_sls == "22:00:00") || ($r->jam_mulai == "22:00:00" && $r->jam_sls == "06:00:00")) {
					if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
						$lamlem = "0 jam";
					} else if ($jam_masuk <= $r->jam_mulai) {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $r->jam_mulai);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							$lamlem = "$jam Jam $menit Menit";
						}
					} else {
						if ($jam_pulang >= $r->jam_sls) {
							$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
						} else {
							$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
						}
						$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
						$diff   = $akhir - $mulai;
						$jam    = floor($diff / (60 * 60));
						$menit  = ($diff - $jam * (60 * 60)) / 60;
						if ($jam < 0) {
							$lamlem =  "0 jam";
						} else {
							if ($jam < 0) {
								$lamlem =  "0 jam";
							} else {
								$lamlem = "$jam Jam $menit Menit";
							}
						}
					}
				} else {
					// aslii
					// jam mulai lembur - jam selesai
					if ($jam_pulang > "24:00") {
						$tgl_pulang = date('Y-m-d', strtotime($tgl_pulang . "+1 days"));
					}

					if ($jam_pulang >= $r->jam_sls) {
						$akhir = strtotime($tgl_pulang . ' ' . $r->jam_sls);
					} else {
						if ($r->jam_sls >= $jam_pulang) {
							$akhir = strtotime($tgl_masuk . ' ' . $r->jam_sls);
							$ca = "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
						} else {
							$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
							$ca = "beda tgl masuk & pulang, pulang lebih cepat";
						}
						// $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
					}
					$mulai = strtotime($tgl_masuk . ' ' . $r->jam_mulai);
					$diff   = $akhir - $mulai;
					$jam    = floor($diff / (60 * 60));
					$menit  = ($diff - $jam * (60 * 60)) / 60;
					if ($jam < 0) {
						$lamlem =  "0 jam";
					} else {
						$kaljam = $jam + ($menit / 60);
						if ($kaljam >= 5 and $kaljam < 10) {
							$krg_jam = 0.5;
						} else if ($kaljam >= 10 and $kaljam < 15) {
							$krg_jam = 1;
						} else if ($kaljam >= 15 and $kaljam < 20) {
							$krg_jam = 1.5;
						} else if ($kaljam >= 20 and $kaljam < 24) {
							$krg_jam = 2;
						} else {
							$krg_jam = $krg_jam;
						}
						$totlem = $kaljam - $krg_jam;
						// $lamlem = $krg_jam;
						if (is_numeric($totlem) && floor($totlem) != $totlem) {
							$total_jam = explode(".", $totlem);
							$j = $total_jam[0];
							$m = $total_jam[1];
							$m = "0." . $m;
							$m = (float)$m;
							$m = round($m * 60);
							// $m = round($m * 60);
							// if($m == 60)
							// {
							// 	$j = $j+1;
							// 	$m = 0;
							// }
							// $lamlem = $totlem;
							$lamlem = "$j jam $m menit";
						} else {
							$lamlem = "$totlem jam";
						}
					}
				}
			}

			$data[] = array(
				$no = $no + 1,
				$r->nik,
				$r->nama_karyawan,
				$r->indeks_hr,
				$r->indeks_jabatan,
				$tgl_lembur,
				$jam_masuk2,
				$r->jam_mulai,
				$r->jam_sls,
				$jam_pulang,
				$lamlem,
				$fdup,
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

	public function stkl_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$id_dept = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
				$id_dept = $user->recid_department;
			}
			// Show all departments and employees regardless of user role
			$data['bagian'] = $this->m_hris->all_bagian();
			$data['karyawan']	= $this->m_hris->karyawan_view();
			$data['dept']	= $department;
			$data['kategori']	= $this->m_lembur->kategori_lembur_aktif();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_emp_for_ot()
	{
		// Ambil data ID bagian yang dikirim via ajax post
		$tgl_lembur = $this->input->post('tgl_lembur');
		$recid_bag = $this->input->post('recid_bag');
		$nama = $this->m_hris->karyawan_view();
		// if($recid_bag == 23) // pengemudi 
		// {
		// 	$nama = $this->m_lembur->karyawan_offdown_lembur2($tgl_lembur);
		// }else{
		// 	$nama = $this->m_lembur->karyawan_offdown_lembur($tgl_lembur);
		// }
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";
		foreach ($nama as $data) {
			$lists .= "<option value='$data->recid_karyawan'>$data->nama_karyawan ($data->nik - $data->indeks_hr)</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function stkl_edit()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			if ($role == '1' or $role == '5') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else {
				$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
			}
			$data['dept']	= $department;
			$data['kategori']	= $this->m_lembur->kategori_lembur_aktif();
			$data['karyawan']	= $this->m_hris->karyawan_offdown();
			$data['stkl']		= $this->m_lembur->stkl_id($recid_stkl);
			$data['anggota']	= $this->m_lembur->karyawan_lembur($recid_stkl);
			$data['pekerjaan']	= $this->m_lembur->pekerjaan($recid_stkl);
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_edit', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function lembur_addkar()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->input->post('recid_karyawan');
			$recid_stkl = $this->input->post('recid_stkl');
			$stkl = $this->m_lembur->stkl_id($recid_stkl);
			foreach ($stkl->result() as $s) {
				$jam_selesai = $s->jam_selesai;
			}
			$keterangan = $this->input->post('keterangan');
			$dari = $this->input->post('dari');
			for ($i = 0; $i < count($this->input->post('recid_karyawan')); $i++) // looping sebanyak multi select
			{
				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d H:i:s'),
					'recid_stkl'			=> $recid_stkl,
					'recid_karyawan'		=> $recid_karyawan[$i],
					'jam_selesai'			=> $jam_selesai,
					'keterangan'			=> $keterangan,
				);
				$this->m_lembur->stkl_detail_insert($data3);
				if ($dari == 'acc') {
					$links = "index.php/Lembur/acc_lembur/$recid_stkl";
				} else if ($dari == 'realisasi') {
					$links = "index.php/Lembur/realisasi/$recid_stkl";
				} else {
					$links = "index.php/Lembur/stkl_edit/$recid_stkl";
				}
				// $links = "index.php/Lembur/stkl_edit/$recid_stkl";
				// echo $links;
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function update_jam_lembur()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_detstkl = $this->input->post('recid_detstkl');
			$recid_stkl = $this->input->post('recid_stkl');
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			$dari = $this->input->post('dari');

			$data3 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'jam_selesai'			=> $jam_selesai,
			);
			$this->m_lembur->stkl_detail_update($data3, $recid_detstkl);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			// $links = "index.php/Lembur/stkl_edit/$recid_stkl";
			// echo $links;
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function del_karyawan_lembur()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$recid_detstkl = $this->uri->segment(4);
			$dari = $this->uri->segment(5);
			$data3 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'is_delete'				=> '1'
			);
			$this->m_lembur->stkl_detail_update($data3, $recid_detstkl);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function lembur_addkerja()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->input->post('recid_plembur');
			$pekerjaan = $this->input->post('pekerjaan');
			$target = $this->input->post('target');
			$satuan = $this->input->post('satuan');
			$dari = $this->input->post('dari');
			$data2 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'recid_plembur'			=> $recid_stkl,
				'pekerjaan'				=> $pekerjaan,
				'target_kerja'			=> $target,
				'satuan'				=> $satuan,
			);
			$this->m_lembur->dlembur_insert($data2);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_target()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_detlembur = $this->input->post('recid_detlembur');
			$pekerjaan = $this->input->post('pekerjaan');
			$target = $this->input->post('target');
			$dari = $this->input->post('dari');
			$hasil = $this->input->post('hasil');
			$satuan = $this->input->post('satuan');
			$recid_stkl = $this->input->post('recid_stkl');
			$data2 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'recid_plembur'			=> $recid_stkl,
				'pekerjaan'				=> $pekerjaan,
				'target_kerja'			=> $target,
				'hasil'					=> $hasil,
				'satuan'				=> $satuan,
			);
			$this->m_lembur->dlembur_update($data2, $recid_detlembur);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function edit_target()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_detlembur = $this->input->post('recid_detlembur');
			$pekerjaan = $this->input->post('pekerjaan');
			$target = $this->input->post('target');
			$dari = $this->input->post('dari');
			$satuan = $this->input->post('satuan');
			$recid_stkl = $this->input->post('recid_stkl');
			$data2 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'recid_plembur'			=> $recid_stkl,
				'pekerjaan'				=> $pekerjaan,
				'target_kerja'			=> $target,
				'satuan'				=> $satuan,
			);
			$this->m_lembur->dlembur_update($data2, $recid_detlembur);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function del_pekerjaan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$recid_detlembur = $this->uri->segment(4);
			$dari = $this->uri->segment(5);
			$data2 = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'recid_plembur'			=> $recid_stkl,
				'is_delete'				=> '1',
			);
			$this->m_lembur->dlembur_update($data2, $recid_detlembur);
			if ($dari == 'acc') {
				$links = "index.php/Lembur/acc_lembur/$recid_stkl";
			} else if ($dari == 'realisasi') {
				$links = "index.php/Lembur/realisasi/$recid_stkl";
			} else {
				$links = "index.php/Lembur/stkl_edit/$recid_stkl";
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	/* public function plembur_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}

			if($role == '1' or $role =='5')
			{
				$data['bagian'] = $this->m_hris->bagian_by_role();
			}else{
				if($tingkatan > 9)
				{
					$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
				}else{
					if($usr == '1094')
					{
						$data['bagian'] = $this->m_hris->bagian_sales();
					}else{
						$data['bagian'] = $this->m_hris->bagian_by_str($struktur);
					}
				}
			}
			$data['dept']	= $department;
			$recid_plembur = $this->uri->segment(3);
			$data['lembur'] = $this->m_hris->plembur_by_recid($recid_plembur);
			$data['detail'] = $this->m_hris->dlembur_view($recid_plembur);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pengajuan/plembur_update', $data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	} */


	public function masterbudget()
	{
		$recid_bag = $this->input->post('recid_bag');
		$tgl = $this->input->post('tgl');
		$date = DateTime::createFromFormat("Y-m-d", $tgl);
		$tahun =  $date->format("Y");
		$jam_mulai = $this->input->post('jam_mulai');
		if ($jam_mulai != "") {
			$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
		}
		$jam_selesai = $this->input->post('jam_selesai');
		if ($jam_selesai != "") {
			$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
		}

		// $recid_bag = 23;
		// $tgl = "2023-11-08";
		// $date = DateTime::createFromFormat("Y-m-d", $tgl);
		// $tahun =  $date->format("Y");
		// $jam_mulai = "06:00";
		// $jam_selesai = "07:30";


		$cutoff = $this->m_lembur->cek_cutoff($tgl);
		$hasil = array();
		if ($cutoff->num_rows() > 0) {
			$cut_off = "Ready";
			array_push($hasil, $cut_off);
			foreach ($cutoff->result() as $cutoff) {
				$recid_clembur = $cutoff->recid_clembur;
			}
			$master_budget = $this->m_lembur->masterbudget($recid_bag, $recid_clembur);
			if ($master_budget->num_rows() > 0) {
				$budget = "Ada";
				array_push($hasil, $budget);
				foreach ($master_budget->result() as $key) {
					$recid_mbl = $key->recid_mbl;
					$kuartal = $key->kuartal;
					// array_push($hasil, $kuartal);

				}
				// echo $recid_mbl;
				$jml_jam = $this->m_lembur->total_jamkuartal($recid_bag, $kuartal, $tahun)->result();
				foreach ($jml_jam as $j) {
					$totjam_kuartal = $j->jumlah;
				}
				$cek_stkl_kuartal = $this->m_lembur->stkl_kuartal($recid_bag, $kuartal, $tahun);
				$budget_pengurang = 0;
				foreach ($cek_stkl_kuartal->result() as $skl) {
					$cek_pengurang_kuartal = $this->m_lembur->pengurang_kuartal($skl->recid_mbl);
					if ($cek_pengurang_kuartal->num_rows() > 0) {
						foreach ($cek_pengurang_kuartal->result() as $cpk) {
							$budget_pengurang = $budget_pengurang + $cpk->totjam_real;
						}
					}
				}
				$sisa_budget = $totjam_kuartal - $budget_pengurang;
				array_push($hasil, $sisa_budget);
				array_push($hasil, $recid_mbl);
			} else {
				$budget = "kosong";
				array_push($hasil, $budget);
			}
		} else {
			$cut_off = "Not Ready";
			$budget = "kosong";
			array_push($hasil, $cut_off);
			array_push($hasil, $budget);
		}

		$cek_holiday = $this->m_lembur->cek_holiday($tgl);
		if ($cek_holiday->num_rows() > 0) {
			$fholi = "1";	//merah
			//5 jam pertama
			// echo "jam : $jam_mulai - $jam_selesai <br>";
			$krg_jam = 0;
			$cnt_jam = 0;

			if (($jam_mulai == "06:00" && $jam_selesai == "14:00") || ($jam_mulai == "14:00" && $jam_selesai == "22:00") || ($jam_mulai == "22:00" && $jam_selesai == "06:00")) {
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";

				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					// if($cnt_jam == 5)
					// {
					// 	$krg_jam = $krg_jam + 1;
					// }else if($cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25)
					// {
					// 	$krg_jam = $krg_jam + 0.5;
					// }else{
					// 	$krg_jam = $krg_jam;
					// }
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $cnt_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			} else {
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";

				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					if ($cnt_jam == 5) {
						$krg_jam = $krg_jam + 1;
					} else if ($cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
						$krg_jam = $krg_jam + 0.5;
					} else {
						$krg_jam = $krg_jam;
					}
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $kal_jam - $krg_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			}
		} else {
			$nameOfDay = date('D', strtotime($tgl));
			if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
				$fholi = "1";	//weekend
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";
				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					if ($cnt_jam == 5) {
						$krg_jam = $krg_jam + 1;
					} else if ($cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
						$krg_jam = $krg_jam + 0.5;
					} else {
						$krg_jam = $krg_jam;
					}
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $kal_jam - $krg_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			} else {
				$fholi = "0";	//weekday OT
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";
				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					if ($cnt_jam == 5 or $cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
						$krg_jam = $krg_jam + 0.5;
					} else {
						$krg_jam = $krg_jam;
					}
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $kal_jam - $krg_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			}
		}
		array_push($hasil, $totlem);
		echo json_encode($hasil);
	}

	public function masterbudget2()
	{
		$recid_bag = $this->input->post('recid_bag');
		$recid_stkl = $this->input->post('recid_stkl');
		$tgl = $this->input->post('tgl');
		$date = DateTime::createFromFormat("Y-m-d", $tgl);
		$tahun =  $date->format("Y");
		$jam_mulai = $this->input->post('jam_mulai');
		if ($jam_mulai != "") {
			$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
		}
		$jam_selesai = $this->input->post('jam_selesai');
		if ($jam_selesai != "") {
			$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
		}
		// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
		// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
		$cutoff = $this->m_lembur->cek_cutoff($tgl);
		$hasil = array();
		if ($cutoff->num_rows() > 0) {
			$cut_off = "Ready";
			array_push($hasil, $cut_off);
			foreach ($cutoff->result() as $cutoff) {
				$recid_clembur = $cutoff->recid_clembur;
			}
			$master_budget = $this->m_lembur->masterbudget($recid_bag, $recid_clembur);
			if ($master_budget->num_rows() > 0) {
				$budget = "Ada";
				array_push($hasil, $budget);
				foreach ($master_budget->result() as $key) {
					$recid_mbl = $key->recid_mbl;
					$kuartal = $key->kuartal;
					// array_push($hasil, $kuartal);

				}
				$jml_jam = $this->m_lembur->total_jamkuartal($recid_bag, $kuartal, $tahun)->result();
				foreach ($jml_jam as $j) {
					$totjam_kuartal = $j->jumlah;
				}
				$cek_stkl_kuartal = $this->m_lembur->stkl_kuartal($recid_bag, $kuartal, $tahun);
				$budget_pengurang = 0;
				foreach ($cek_stkl_kuartal->result() as $skl) {
					$cek_pengurang_kuartal = $this->m_lembur->pengurang_kuartal($skl->recid_mbl);
					if ($cek_pengurang_kuartal->num_rows() > 0) {
						foreach ($cek_pengurang_kuartal->result() as $cpk) {
							$budget_pengurang = $budget_pengurang + $cpk->totjam_real;
						}
					}
				}
				$sisa_budget = $totjam_kuartal - $budget_pengurang;
				array_push($hasil, $sisa_budget);
				array_push($hasil, $recid_mbl);

				$karyawan_lembur = $this->m_lembur->karyawan_lembur($recid_stkl);
				$jml_orang = $karyawan_lembur->num_rows();
				array_push($hasil, $jml_orang);
			} else {
				$budget = "kosong";
				array_push($hasil, $budget);
			}
		} else {
			$cut_off = "Not Ready";
			$budget = "kosong";
			array_push($hasil, $cut_off);
			array_push($hasil, $budget);
		}

		$cek_holiday = $this->m_lembur->cek_holiday($tgl);
		if ($cek_holiday->num_rows() > 0) {
			$fholi = "1";	//merah
			//5 jam pertama
			// echo "jam : $jam_mulai - $jam_selesai <br>";
			$krg_jam = 0;
			$cnt_jam = 0;
			$mulai = date_create($tgl . ' ' . $jam_mulai);
			if ($jam_selesai < $jam_mulai) {
				$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
				$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
				// echo "hari besok : ".$akhir_jam."<br>";
			} else {
				$akhir = date_create($tgl . ' ' . $jam_selesai);
			}
			$diff  = date_diff($mulai, $akhir);
			// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
			$des_mnt = $diff->format('%I');
			$des_mnt = ((int)$des_mnt) / 60;
			$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
			// echo "Kalkulasi jam = $kal_jam <br>";
			while ($mulai < $akhir) {
				$cnt_jam = $cnt_jam + 1;
				if ($cnt_jam == 5) {
					$krg_jam = $krg_jam + 1;
				} else if ($cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
					$krg_jam = $krg_jam + 0.5;
				} else {
					$krg_jam = $krg_jam;
				}
				date_add($mulai, date_interval_create_from_date_string('1 hours'));
				// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
			}
			// echo "Total Pengurang Jam  = $krg_jam <br>";
			$totlem = $kal_jam - $krg_jam;
			// echo "Total Jam Lembur = $totlem <br>";
		} else {
			$nameOfDay = date('D', strtotime($tgl));
			if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
				$fholi = "1";	//weekend
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";
				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					if ($cnt_jam == 5) {
						$krg_jam = $krg_jam + 1;
					} else if ($cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
						$krg_jam = $krg_jam + 0.5;
					} else {
						$krg_jam = $krg_jam;
					}
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $kal_jam - $krg_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			} else {
				$fholi = "0";	//weekday OT
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				// echo "Kalkulasi jam = $kal_jam <br>";
				while ($mulai < $akhir) {
					$cnt_jam = $cnt_jam + 1;
					if ($cnt_jam == 5 or $cnt_jam == 10 or $cnt_jam == 15 or $cnt_jam == 20 or $cnt_jam == 25) {
						$krg_jam = $krg_jam + 0.5;
					} else {
						$krg_jam = $krg_jam;
					}
					date_add($mulai, date_interval_create_from_date_string('1 hours'));
					// echo "Pengurang Jam Ke- $cnt_jam = $krg_jam <br>";
				}
				// echo "Total Pengurang Jam  = $krg_jam <br>";
				$totlem = $kal_jam - $krg_jam;
				// echo "Total Jam Lembur = $totlem <br>";
			}
		}
		array_push($hasil, $totlem);
		echo json_encode($hasil);
	}

	public function masterbudget_shift()
	{
		$recid_bag = $this->input->post('recid_bag');
		$tgl = $this->input->post('tgl');
		$date = DateTime::createFromFormat("Y-m-d", $tgl);
		$tahun =  $date->format("Y");
		$jam_mulai = $this->input->post('jam_mulai');
		if ($jam_mulai != "") {
			$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
		}
		$jam_selesai = $this->input->post('jam_selesai');
		if ($jam_selesai != "") {
			$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
		}
		// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
		// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));

		// $recid_bag = 3;
		// $tgl = "2023-05-16";
		// $date = DateTime::createFromFormat("Y-m-d", $tgl);
		// $tahun =  $date->format("Y");
		// $jam_mulai = "17:00";
		// $jam_selesai = "03:00";


		$cutoff = $this->m_lembur->cek_cutoff($tgl);
		$hasil = array();
		if ($cutoff->num_rows() > 0) {
			$cut_off = "Ready";
			array_push($hasil, $cut_off);
			foreach ($cutoff->result() as $cutoff) {
				$recid_clembur = $cutoff->recid_clembur;
			}
			$master_budget = $this->m_lembur->masterbudget($recid_bag, $recid_clembur);
			if ($master_budget->num_rows() > 0) {
				$budget = "Ada";
				array_push($hasil, $budget);
				foreach ($master_budget->result() as $key) {
					$recid_mbl = $key->recid_mbl;
					$kuartal = $key->kuartal;
					// array_push($hasil, $kuartal);

				}
				$jml_jam = $this->m_lembur->total_jamkuartal($recid_bag, $kuartal, $tahun)->result();
				foreach ($jml_jam as $j) {
					$totjam_kuartal = $j->jumlah;
				}
				$cek_stkl_kuartal = $this->m_lembur->stkl_kuartal($recid_bag, $kuartal, $tahun);
				$budget_pengurang = 0;
				foreach ($cek_stkl_kuartal->result() as $skl) {
					$cek_pengurang_kuartal = $this->m_lembur->pengurang_kuartal($skl->recid_mbl);
					if ($cek_pengurang_kuartal->num_rows() > 0) {
						foreach ($cek_pengurang_kuartal->result() as $cpk) {
							$budget_pengurang = $budget_pengurang + $cpk->totjam_real;
						}
					}
				}
				$sisa_budget = $totjam_kuartal - $budget_pengurang;
				array_push($hasil, $sisa_budget);
				array_push($hasil, $recid_mbl);
			} else {
				$budget = "kosong";
				array_push($hasil, $budget);
			}
		} else {
			$cut_off = "Not Ready";
			$budget = "kosong";
			array_push($hasil, $cut_off);
			array_push($hasil, $budget);
		}

		$cek_holiday = $this->m_lembur->cek_holiday($tgl);
		if ($cek_holiday->num_rows() > 0) {
			$fholi = "1";	//merah
			//5 jam pertama
			// echo "jam : $jam_mulai - $jam_selesai <br>";
			$krg_jam = 0;
			$cnt_jam = 0;
			$mulai = date_create($tgl . ' ' . $jam_mulai);
			if ($jam_selesai < $jam_mulai) {
				$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
				$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
				// echo "hari besok : ".$akhir_jam."<br>";
			} else {
				$akhir = date_create($tgl . ' ' . $jam_selesai);
			}
			$diff  = date_diff($mulai, $akhir);
			// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
			$des_mnt = $diff->format('%I');
			$des_mnt = ((int)$des_mnt) / 60;
			$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
			$totlem = $kal_jam;
		} else {
			$nameOfDay = date('D', strtotime($tgl));
			if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
				$fholi = "1";	//weekend
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				$totlem = $kal_jam;
			} else {
				$fholi = "0";	//weekday OT
				$krg_jam = 0;
				$cnt_jam = 0;
				$mulai = date_create($tgl . ' ' . $jam_mulai);
				if ($jam_selesai < $jam_mulai) {
					$akhir_jam = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
					$akhir = date_create($akhir_jam . ' ' . $jam_selesai);
					// echo "hari besok : ".$akhir_jam."<br>";
				} else {
					$akhir = date_create($tgl . ' ' . $jam_selesai);
				}
				$diff  = date_diff($mulai, $akhir);
				// echo "Total Jam = ". $diff->format(' %H jam %I menit')."<br>";
				$des_mnt = $diff->format('%I');
				$des_mnt = ((int)$des_mnt) / 60;
				$kal_jam = ((int)$diff->format('%h')) + $des_mnt;
				$totlem = $kal_jam;
			}
		}
		array_push($hasil, $totlem);
		echo json_encode($hasil);
	}

	public function hitung_jam_lembur()
	{
		$recid_stkl = $this->input->post('recid_stkl');
		$jam = $this->m_lembur->jumlah_jam_lembur($recid_stkl);
		foreach ($jam->result() as $j) {
			$tot_jam = $j->total_jam;
		}
		echo json_encode($tot_jam);
	}

	public function cek_mbl()
	{
		$recid_bag = $this->input->post('recid_bag');
		$tgl = $this->input->post('tgl');
		$cutoff = $this->m_hris->cek_cutoff($tgl);
		foreach ($cutoff->result() as $cutoff) {
			$recid_clembur = $cutoff->recid_clembur;
		}
		$data = array();
		$master_budget = $this->m_hris->masterbudget($recid_bag, $recid_clembur)->result();
		foreach ($master_budget as $key) {
			$recid_mbl = $key->recid_mbl;
			$kuartal = $key->kuartal;
		}
		$jml_jam = $this->m_hris->total_jamkuartal($recid_bag, $kuartal)->result();
		foreach ($jml_jam as $datas) {
			$a = array($datas->jumlah);
			array_push($data, $a);
			array_push($data, $recid_mbl);
		}
		echo json_encode($data);
	}

	public function plembur_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl_lembur = $this->input->post('tgl_lembur');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			$jam_mulai = $this->input->post('jam_mulai');
			if ($jam_mulai != "") {
				$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			}
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			$klasifikasi = $this->input->post('klasifikasi');
			$tipe = $this->input->post('tipe');
			$recid_kategori = $this->input->post('recid_kategori');
			// If no category selected, set default category (assuming 1 is a valid default)
			if (empty($recid_kategori)) {
				$recid_kategori = 1; // Default category
			}
			$kat_lain = $this->input->post('kat_lain');
			$status = "Pengajuan";
			$approval = "Belum Acc Manager";
			$total_jam = $this->input->post('total_jam');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			$pekerjaan = $this->input->post('pekerjaan');
			$target = $this->input->post('target');
			$satuan = $this->input->post('satuan');
			// print_r($target);


			// echo "$tgl_lembur, $recid_mbl, $jam_mulai, $jam_selesai, $klasifikasi, $tipe, $recid_kategori, $status, $approval, $total_jam, $jml_orang, $alasan_over, $keterangan";

			// print_r($pekerjaan);
			// print_r($target);


			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'tgl_lembur'			=> $tgl_lembur,
				'recid_mbl'				=> $recid_mbl,
				'jam_mulai'				=> $jam_mulai,
				'jam_selesai'			=> $jam_selesai,
				'klasifikasi'			=> $klasifikasi,
				'tipe'					=> $tipe,
				'recid_kategori'		=> $recid_kategori,
				'kat_lain'				=> $kat_lain,
				'status'				=> $status,
				'approval'				=> $approval,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'alasan_over'			=> $alasan_over,
				'keterangan'			=> $keterangan,
			);
			$this->m_lembur->stkl_insert($data);

			$get_id_stkl = $this->m_lembur->last_stkl();
			foreach ($get_id_stkl->result() as $id) {
				$recid_stkl = $id->recid_stkl;
			}

			for ($k = 0; $k < count($this->input->post('recid_karyawan')); $k++) {
				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d H:i:s'),
					'recid_stkl'			=> $recid_stkl,
					'jam_selesai'			=> $jam_selesai,
					'recid_karyawan'		=> $recid_karyawan[$k],
				);
				$this->m_lembur->stkl_detail_insert($data3);
			}

			if (count($this->input->post('pekerjaan')) > 0) {
				for ($i = 0; $i < count($this->input->post('pekerjaan')); $i++) {
					$data2 = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d H:i:s'),
						'recid_plembur'			=> $recid_stkl,
						'pekerjaan'				=> $pekerjaan[$i],
						'target_kerja'			=> $target[$i],
						'satuan'			=> $satuan[$i],
					);
					$this->m_lembur->dlembur_insert($data2);
				}
			}

			// collect workflow
			$data_wf = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'recid_stkl'			=> $recid_stkl,
				'status'				=> $status,
				'approval'				=> $approval,
				'pic_wf'				=> $this->session->userdata('kar_id'),
			);
			$this->m_lembur->wf_insert($data_wf);

			$this->email_pengajuan($recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->input->post('recid_stkl');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			$jam_mulai = $this->input->post('jam_mulai');
			if ($jam_mulai != "") {
				$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			}
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			$klasifikasi = $this->input->post('klasifikasi');
			$tipe = $this->input->post('tipe');
			$recid_kategori = $this->input->post('recid_kategori');
			// If no category selected, set default category (assuming 1 is a valid default)
			if (empty($recid_kategori)) {
				$recid_kategori = 1; // Default category
			}
			$kat_lain = $this->input->post('kat_lain');
			$status = "Pengajuan";
			$approval = "Belum Acc Manager";
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');

			// echo "$recid_stkl, $tgl_lembur, $recid_mbl, $jam_mulai, $jam_selesai, $klasifikasi, $tipe, $recid_kategori, $status, $approval, $total_jam, $jml_orang, $alasan_over, $keterangan";

			$data = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'tgl_lembur'			=> $tgl_lembur,
				'recid_mbl'				=> $recid_mbl,
				'jam_mulai'				=> $jam_mulai,
				'jam_selesai'			=> $jam_selesai,
				'klasifikasi'			=> $klasifikasi,
				'tipe'					=> $tipe,
				'recid_kategori'		=> $recid_kategori,
				'kat_lain'				=> $kat_lain,
				'status'				=> $status,
				'approval'				=> $approval,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'alasan_over'			=> $alasan_over,
				'keterangan'			=> $keterangan,
			);
			$this->m_lembur->stkl_update($data, $recid_stkl);

			$data_updt = array(
				'jam_selesai'			=> $jam_selesai,
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
			);
			$this->m_lembur->sd_update_by_stkl($data_updt, $recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function acc_lembur()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			if ($role == '1' or $role == '5' or $role == '25') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else {
				$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
			}
			$data['dept']	= $department;
			$data['kategori']	= $this->m_lembur->kategori_lembur_aktif();
			$data['karyawan']	= $this->m_hris->karyawan_offdown();
			$data['stkl']		= $this->m_lembur->stkl_id($recid_stkl);
			$data['anggota']	= $this->m_lembur->karyawan_lembur($recid_stkl);
			$data['pekerjaan']	= $this->m_lembur->pekerjaan($recid_stkl);
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_acc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function approval_lembur()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}

			$app = $this->input->post('acc');
			if ($struktur == 11 and $tingkatan >= 8) {
				if ($app == 1) {
					$approval = "Acc HC";
				} else {
					$approval = "Tidak Acc HC";
				}
			} else if ($tingkatan > 9) {
				if ($app == 1) {
					$approval = "Acc Direksi";
				} else {
					$approval = "Tolak Direksi";
				}
			} else {
				if ($app == 1) {
					$approval = "Acc Manager";
				} else {
					$approval = "Tidak Acc Manager";
				}
			}

			$recid_stkl = $this->input->post('recid_stkl');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			$jam_mulai = $this->input->post('jam_mulai');
			if ($jam_mulai != "") {
				$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			}
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			$klasifikasi = $this->input->post('klasifikasi');
			$tipe = $this->input->post('tipe');
			$recid_kategori = $this->input->post('recid_kategori');
			$status = "Pengajuan";
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');

			echo "$recid_stkl, $tgl_lembur, $recid_mbl, $jam_mulai, $jam_selesai, $klasifikasi, $tipe, $recid_kategori, $status, $approval, $total_jam, $jml_orang, $alasan_over, $keterangan";

			$data = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'tgl_lembur'			=> $tgl_lembur,
				'recid_mbl'				=> $recid_mbl,
				'jam_mulai'				=> $jam_mulai,
				'jam_selesai'			=> $jam_selesai,
				'klasifikasi'			=> $klasifikasi,
				'tipe'					=> $tipe,
				'recid_kategori'		=> $recid_kategori,
				'status'				=> $status,
				'approval'				=> $approval,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'alasan_over'			=> $alasan_over,
				'keterangan'			=> $keterangan,
			);
			$this->m_lembur->stkl_update($data, $recid_stkl);
			// collect workflow
			$data_wf = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'recid_stkl'			=> $recid_stkl,
				'status'				=> $status,
				'approval'				=> $approval,
				'pic_wf'				=> $this->session->userdata('kar_id'),
			);
			$this->m_lembur->wf_insert($data_wf);

			$this->email_pengajuan($recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function acc_realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			if ($role == '1' or $role == '5' or $role == '25') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else {
				$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
			}
			$stkl = $this->m_lembur->stkl_id($recid_stkl);
			foreach ($stkl->result() as $s) {
				$cek_holiday = $this->m_lembur->cek_holiday($s->tgl_lembur);
				if ($cek_holiday->num_rows() > 0) {
					$fholi = '1'; // merah
				} else {
					$nameOfDay = date('D', strtotime($s->tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$fholi = '1'; // merah weekend
					} else {
						$fholi = '0'; // terusan
					}
				}
			}
			$data['dept']	= $department;
			$data['kategori']	= $this->m_lembur->kategori_lembur_aktif();
			$data['karyawan']	= $this->m_hris->karyawan_offdown();
			$data['stkl']		= $this->m_lembur->stkl_id($recid_stkl);
			$data['anggota']	= $this->m_lembur->karyawan_lembur($recid_stkl);
			$data['pekerjaan']	= $this->m_lembur->pekerjaan($recid_stkl);
			$data['fholi']	= $fholi;
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_acc_real', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function approval_realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}

			$app = $this->input->post('acc');
			if ($app == 1) {
				$approval = "Acc Realisasi Manager";
			} else {
				$approval = "Tidak Acc Realisasi";
			}

			$recid_stkl = $this->input->post('recid_stkl');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			$jam_mulai = $this->input->post('jam_mulai');
			if ($jam_mulai != "") {
				$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			}
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			$klasifikasi = $this->input->post('klasifikasi');
			$tipe = $this->input->post('tipe');
			$recid_kategori = $this->input->post('recid_kategori');
			$status = "Realisasi";
			$approval = "Acc Realisasi Manager";
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');

			echo "$recid_stkl, $tgl_lembur, $recid_mbl, $jam_mulai, $jam_selesai, $klasifikasi, $tipe, $recid_kategori, $status, $approval, $total_jam, $jml_orang, $alasan_over, $keterangan";

			$data = array(
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d H:i:s'),
				'tgl_lembur'			=> $tgl_lembur,
				'recid_mbl'				=> $recid_mbl,
				'jam_mulai'				=> $jam_mulai,
				'jam_selesai'			=> $jam_selesai,
				'klasifikasi'			=> $klasifikasi,
				'tipe'					=> $tipe,
				'recid_kategori'		=> $recid_kategori,
				'status'				=> $status,
				'approval'				=> $approval,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'alasan_over'			=> $alasan_over,
				'keterangan'			=> $keterangan,
			);
			$this->m_lembur->stkl_update($data, $recid_stkl);
			// collect workflow
			$data_wf = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'recid_stkl'			=> $recid_stkl,
				'status'				=> $status,
				'approval'				=> $approval,
				'pic_wf'				=> $this->session->userdata('kar_id'),
			);
			$this->m_lembur->wf_insert($data_wf);

			$this->email_pengajuan($recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function stkl_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->input->post('recid_stkl');
			$alasan = $this->input->post('alasan_hapus');
			$data1 = array(
				'is_delete'			=> '1',
				'alasan_hapus'		=> $alasan,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d H:i:s'),
			);
			$this->m_lembur->stkl_update($data1, $recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	/* public function plembur_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$recid_plembur = $this->uri->segment(3);
			$data1 = array(
				'plembur_delete'	=> '1',
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d H:i:s'),
			);
			$this->m_hris->plembur_pupdate($data1, $recid_plembur);
			redirect('Lembu/stkl_view');
		}else{
			redirect('Auth/keluar');	
		}
	} */

	public function r_master_budget()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$tahun = $this->input->post('tahun');
			$data['tahun'] = $tahun;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}

			if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25' or $role == '31' or $role == '32') {
				$data['role'] = "";
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/new_report/kuartal_budget', $data);
				$this->load->view('layout/a_footer');
			} else if ($role == '37') { //GM DEPT GROUP (BO, FO, MO)
				$data['role'] = " d.department = '$department'";
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/new_report/r_kuartal_budget_single', $data);
				$this->load->view('layout/a_footer');
			} else {
				$recid_karyawan = $this->session->userdata('kar_id');
				$bagian = "(b.indeks_hr =";
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
				$data['role'] = $bagian;
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/new_report/r_kuartal_budget_single', $data);
				$this->load->view('layout/a_footer');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	// public function r_pengajuan()
	// {
	// 	$logged_in = $this->session->userdata('logged_in');
	// 	if($logged_in == 1)
	// 	{
	// 		$usr = $this->session->userdata('kar_id');
	// 		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
	// 		$this->load->view('layout/a_header');
	// 		$this->load->view('layout/menu_super', $data);
	// 		$this->load->view('master_budget/report/r_pengajuan');
	// 		$this->load->view('layout/a_footer');
	// 	}
	// 	else
	// 	{
	// 		redirect('Auth/keluar');

	// 	}
	// }

	// public function r_ppengajuan()
	// {
	// 	$logged_in = $this->session->userdata('logged_in');
	// 	if($logged_in == 1)
	// 	{
	// 		$role = $this->session->userdata('role_id');
	// 		$awal = $this->input->post('tgl_awal');
	// 		$akhir = $this->input->post('tgl_akhir');
	// 		$usr = $this->session->userdata('kar_id');
	// 		$cek_usr = $this->m_hris->cek_usr($usr);
	// 		foreach ($cek_usr as $user) {
	// 			$nama = $user->nama_karyawan;
	// 			$bagian = $user->indeks_hr;
	// 			$recid_bag = $user->recid_bag;
	// 			$jabatan = $user->indeks_jabatan;
	// 			$tingkatan = $user->tingkatan;
	// 			$struktur = $user->recid_struktur;
	// 			$department = $user->nama_department;
	// 		}

	// 		if($role == '1' or $role =='5')
	// 		{
	// 			$data['realisasi'] = $this->m_hris->plembur_by_role($awal, $akhir);
	// 		}else{
	// 			if($tingkatan > 9)
	// 			{
	// 				$data['realisasi'] = $this->m_hris->plembur_by_dept($department, $awal, $akhir);
	// 			}else{
	// 				if($usr == '1094')
	// 				{
	// 					$data['realisasi'] = $this->m_hris->plembur_by_sales($awal, $akhir);
	// 				}else{
	// 					$data['realisasi'] = $this->m_hris->plembur_by_str($struktur, $awal, $akhir);
	// 				}

	// 			}
	// 		}
	// 		$data ['tgl_awal'] = $awal;
	// 		$data ['tgl_akhir'] = $akhir;
	// 		$usr = $this->session->userdata('kar_id');
	// 		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
	// 		$this->load->view('layout/a_header');
	// 		$this->load->view('layout/menu_super', $data);
	// 		$this->load->view('master_budget/report/r_ppengajuan', $data);
	// 		$this->load->view('layout/a_footer');
	// 	}
	// 	else
	// 	{
	// 		redirect('Auth/keluar');

	// 	}
	// }

	// public function plembur_crash()
	// {
	// 	$logged_in = $this->session->userdata('logged_in');
	// 	if($logged_in == 1)
	// 	{
	// 		$data['pengajuan'] = $this->m_hris->plembur_crash();
	// 		$usr = $this->session->userdata('kar_id');
	// 		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
	// 		$this->load->view('layout/a_header');
	// 		$this->load->view('layout/menu_super', $data);
	// 		$this->load->view('master_budget/pengajuan/plembur_crash', $data);
	// 		$this->load->view('layout/a_footer');
	// 	}
	// 	else
	// 	{
	// 		redirect('Auth/keluar');

	// 	}
	// }

	// public function plembur_adjust()
	// {
	// 	$logged_in = $this->session->userdata('logged_in');
	// 	if($logged_in == 1)
	// 	{
	// 		$recid_plembur = $this->uri->segment(3);
	// 		$data['lembur'] = $this->m_hris->plembur_by_id($recid_plembur);
	// 		$data['bagian'] = $this->m_hris->bagian_view();
	// 		$usr = $this->session->userdata('kar_id');
	// 		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
	// 		$this->load->view('layout/a_header');
	// 		$this->load->view('layout/menu_super', $data);
	// 		$this->load->view('master_budget/pengajuan/plembur_adjust', $data);
	// 		$this->load->view('layout/a_footer');
	// 	}
	// 	else
	// 	{
	// 		redirect('Auth/keluar');

	// 	}
	// }

	public function realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->uri->segment(3);
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			// Initialize department variable to prevent undefined variable error
			$department = '';
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = '';
			$struktur = '';
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			// if($role == '1' or $role =='5' or $role =='3')
			// {
			// 	$data['bagian'] = $this->m_hris->bagian_by_role();
			// }else{
			// 	$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
			// }
			if ($role == '1' or $role == '5' or $role == '3') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else if ($role == '32' or $role == '34') { // hc baros
				$data['bagian'] = $this->m_hris->bagian_by_str('11');
			} else {
				// $data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
				$recid_karyawan = $this->session->userdata('kar_id');
				$data['bagian'] = $this->m_absen->bagian_by_admin($recid_karyawan)->result();
			}
			$stkl = $this->m_lembur->stkl_id($recid_stkl);
			foreach ($stkl->result() as $s) {
				$cek_holiday = $this->m_lembur->cek_holiday($s->tgl_lembur);
				if ($cek_holiday->num_rows() > 0) {
					$fholi = '1'; // merah
				} else {
					//cek ganti hari
					$cek_gh = $this->m_absen->gh_by_date($s->tgl_lembur);
					if ($cek_gh->num_rows() > 0) {
						$fholi = '0'; // terusan
					} else {
						$nameOfDay = date('D', strtotime($s->tgl_lembur));
						if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
							$fholi = '1'; // merah weekend
						} else {
							$fholi = '0'; // terusan
						}
					}
				}
			}
			// echo $fholi;
			$data['dept']	= $department;
			$data['kategori']	= $this->m_lembur->kategori_lembur_aktif();
			$data['karyawan']	= $this->m_hris->karyawan_offdown();
			$data['stkl']		= $this->m_lembur->stkl_id($recid_stkl);
			$data['anggota']	= $this->m_lembur->karyawan_lembur($recid_stkl);
			$data['pekerjaan']	= $this->m_lembur->pekerjaan($recid_stkl);
			$data['fholi']		= $fholi;
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_realisasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function prealisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_stkl = $this->input->post('recid_stkl');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			$jam_mulai = $this->input->post('jam_mulai');
			if ($jam_mulai != "") {
				$jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			}
			$jam_selesai = $this->input->post('jam_selesai');
			if ($jam_selesai != "") {
				$jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			}
			// $jam_mulai = str_replace(" ", "", $this->input->post('jam_mulai'));
			// $jam_selesai = str_replace(" ", "", $this->input->post('jam_selesai'));
			$klasifikasi = $this->input->post('klasifikasi');
			$tipe = $this->input->post('tipe');
			$recid_kategori = $this->input->post('recid_kategori');
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			$lembur1 = 0;
			$ulembur1 = 0;
			$lembur2 = 0;
			$ulembur2 = 0;
			$lembur3 = 0;
			$ulembur3 = 0;

			$cek_holiday = $this->m_lembur->cek_holiday($tgl_lembur);
			if ($cek_holiday->num_rows() > 0) {
				foreach ($cek_holiday->result() as $ch) {
					$fholi = '1';
				}
			} else {
				$nameOfDay = date('D', strtotime($tgl_lembur));
				$cek_gh = $this->m_absen->gh_by_date($tgl_lembur);
				if ($cek_gh->num_rows() > 0) {
					$fholi = "0";	//weekday OT
				} else {
					$nameOfDay = date('D', strtotime($tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$fholi = "1";	//weekend
					} else {
						$fholi = "0";	//weekday OT
					}
				}
			}

			$updt_durasi = $this->m_lembur->karyawan_lembur($recid_stkl);
			foreach ($updt_durasi->result() as $ud) {
				$jam_barcode = $this->m_lembur->cek_kehadiran($tgl_lembur, $ud->recid_karyawan);
				if ($jam_barcode->num_rows() > 0) {
					foreach ($jam_barcode->result() as $jb) {
						$jam_masuk = $jb->jam_masuk;
						$jam_pulang = $jb->jam_keluar;
						$tgl_pulang = $jb->tgl_pulang;
						$tgl_masuk = $jb->tgl_masuk;
						$jam_in = $jb->jam_in;
						$jam_in2 = $jb->jam_in;
						$jam_in = date_create($jam_in);
						$cjam_in = date_add($jam_in, date_interval_create_from_date_string('1 minutes'));
						$jam_in = date_format($jam_in, 'H:i:s');
						if ($jam_masuk > $jam_in2  and $jam_masuk < $jam_in) {
							$jam_masuk = $jam_in2;
							// echo "jadi normal $jam_in2";
						}
					}
					if ($jam_pulang == "" or is_null($jam_pulang)) {
						$jam_pulang = "-";
					}
				} else {
					$jam_masuk = "-";
					$jam_pulang = "-";
				}
				// echo $jam_masuk;
				$krg_jam = 0;
				if ($fholi == '1') {
					// jika long shift hari libur
					$lembur1 = 0;
					echo "jam lembur : " . $jam_mulai . " - " . $ud->jam_sls . "<br>";
					/* ---- JIKA LEMBUR SHIFT DI HARI LIBUR ---- */

					if (($jam_mulai == "06:00" && $ud->jam_sls == "14:00:00") || ($jam_mulai == "14:00" && $ud->jam_sls == "22:00:00") || ($jam_mulai == "22:00" && $ud->jam_sls == "06:00:00")) {
						// kalo jam masuk / pulangnya 00/-/''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							// masuk normal
							if ($jam_pulang >= $ud->jam_sls) {
								// tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 1";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 2";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 3";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 4";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								}
							}
							echo $ceks;
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								echo  "0 jam";
								$durasi = "0";
							} else {
								// echo "$jam Jam $menit Menit";
								$menit = $menit / 60;
								$menit = substr($menit, strpos($menit, ",") + 1);
								$durasi = $jam . "." . $menit;
							}
						} else {
							// jam masuk dan pulang data normal
							if ($jam_pulang >= $ud->jam_sls) {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 5";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 6";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 7";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 8";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang cepat";
								}
							}
							echo $ceks;
							$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								// $durasi = $jam;
								$menit = $menit / 60;
								$menit = substr($menit, strpos($menit, ",") + 1);
								$durasi = $jam . "." . $menit;
							}
						}
						if ($durasi == "0") {
							$lembur2 = 0;
							$lembur3 = 0;
						} else if ($durasi >= 8) {
							$lembur2 = 8;
							$lembur3 = $durasi - 8;
							$lembur3 = $lembur3;
						} else {
							$lembur2 = $durasi;
							$lembur3 = 0;
						}
					} else {
						/* ASLI NON SHIFT LIBUR */
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							//masuk normal
							if ($jam_pulang >= $ud->jam_sls) {
								//tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 9";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, masuk & pulang normal";
								} else {
									echo "cek HL 10";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, masuk & pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 11";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, masuk telat, pulang cepat";
								} else {
									echo "cek HL 12";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, masuk telat, pulang cepat";
								}
							}
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam <= 10) {
									$krg_jam = 1;
								} else if ($kaljam > 10 and $kaljam < 15) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 2;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2.5;
								} else {
									$krg_jam = $krg_jam;
								}
								$durasi = $kaljam - $krg_jam;
								// echo $durasi;
							}
						} else {
							// jika masuk Telat
							if ($jam_pulang >= $ud->jam_sls) {
								//tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 13";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 14";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 15";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 16";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang cepat";
								}
							}
							$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 1;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 2;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2.5;
								} else {
									$krg_jam = $krg_jam;
								}
								$durasi = $kaljam - $krg_jam;
							}
						}
					}
					if ($durasi == "0") {
						$lembur2 = 0;
						$lembur3 = 0;
					} else if ($durasi >= 7) {
						$lembur2 = 7;
						$lembur3 = $durasi - 7;
						$lembur3 = $lembur3;
					} else {
						$lembur2 = $durasi;
						$lembur3 = 0;
					}
				} else { /* HARI BIASA */
					$cek_real_sameday = $this->m_lembur->realisasi_sameday($tgl_lembur, $ud->recid_karyawan);
					echo "OT SHIFT HARI BIASA : " . $jam_mulai . " - " . $ud->jam_sls . "<br>";
					//JIKA OT SHIFT HARI BIASA
					if (($jam_mulai == "06:00" && $ud->jam_sls == "14:00:00") || ($jam_mulai == "14:00" && $ud->jam_sls == "22:00:00") || ($jam_mulai == "22:00" && $ud->jam_sls == "06:00:00")) {

						//jika jam masuk / pulang 00:00 atau - atau ''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else {
							// masuk normal
							if ($jam_masuk <= $jam_mulai) {
								if ($jam_pulang >= $ud->jam_sls) {
									//tidak pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										//beda tanggal
										echo "cek HB 1";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
									} else {
										echo "cek HB 2";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
									}
								} else {
									//pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 3";
										$akhir = strtotime($tgl_masuk . ' ' . $key->jam_selesai);
										$ceks = "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
									} else {
										echo "cek HB 4";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
									}
								}
								$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
								$diff   = $akhir - $mulai;
								$jam    = floor($diff / (60 * 60));
								$menit  = ($diff - $jam * (60 * 60)) / 60;
								if ($jam < 0) {
									$durasi = "0";
								} else {
									// $durasi = $jam.$menit;
									$menit = $menit / 60;
									$menit = substr($menit, strpos($menit, ",") + 1);
									$durasi = $jam . "." . $menit;
								}
							} else {
								// masuk telat
								if ($jam_pulang >= $ud->jam_sls) {
									//tidak puang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 5";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "beda tgl masuk & pulang, pulang normal";
									} else {
										echo "cek HB 6";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "sama tgl masuk & pulang, pulang normal";
									}
								} else {
									//pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 7";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "beda tgl masuk & pulang, pulang lebih cepat";
									} else {
										echo "cek HB 8";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
									}
								}
								$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
								$diff   = $akhir - $mulai;
								$jam    = floor($diff / (60 * 60));
								$menit  = ($diff - $jam * (60 * 60)) / 60;
								if ($jam < 0) {
									$durasi = "0";
								} else {
									$menit = $menit / 60;
									$menit = substr($menit, strpos($menit, ",") + 1);
									$durasi = $jam . "." . $menit;
								}
							}
						}
						if ($durasi == "0") {
							$lembur1 = 0;
							$lembur2 = 0;
						} else if ($durasi >= 8) {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
							} else {
								$lembur1 = 1;
								$lembur2 = $durasi - 1;
								$lembur2 = $lembur2;
							}
						} else {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur2 = $durasi;
								$lembur1 = 0;
							} else {
								$lembur1 = $durasi;
								$lembur2 = 0;
							}
						}
					} else {
						// BUKAN OT SHIFT = yang biasa
						// jika jam masuk 00:00 atau - atau ''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							//masuk normal
							if ($jam_pulang > $ud->jam_sls) {
								// tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 9";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HB 10";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								// pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 11";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
								} else {
									echo "cek HB 12";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
								}
							}
							echo $ceks . " - " . $tgl_lembur . ' ' . $jam_mulai . " s/d " . $tgl_lembur . ' ' . $ud->jam_sls . "<br>";
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 0.5;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2;
								} else {
									$krg_jam = $krg_jam;
								}

								$durasi = $kaljam - $krg_jam;
							}
						} else {
							// masuk telat
							if ($jam_pulang > $ud->jam_sls) {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 13";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HB 14";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 15";
									$akhir = strtotime($tgl_masuk . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
								} else {
									echo "cek HB 16";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
								}
							}
							// $mulai = strtotime($tgl_lembur.' '.$jam_mulai);
							echo $ceks . " - " . $tgl_lembur . ' ' . $jam_mulai . " s/d " . $tgl_lembur . ' ' . $ud->jam_sls . "<br>";
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 0.5;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2;
								} else {
									$krg_jam = $krg_jam;
								}

								$durasi = $kaljam - $krg_jam;
								// echo $kaljam."-".$krg_jam." = ".$durasi;
							}
						}
						// echo $durasi."<br>";
						if ($durasi == "0") {
							$lembur1 = 0;
							$lembur2 = 0;
						} else if ($durasi >= 1) {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
								$lembur2 = $lembur2;
							} else {
								$lembur1 = 1;
								$lembur2 = $durasi - 1;
								$lembur2 = $lembur2;
							}
						} else {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
							} else {
								$lembur1 = $durasi;
								$lembur2 = 0;
							}
						}
					}
				}

				echo 'jam_pulang : ' . $jam_pulang . 'recid stkl : ' . $recid_stkl . " karyawan : " . $ud->recid_karyawan . " durasi : " . $durasi . " - lembur 1 : " . $lembur1 . " lembur2 : " . $lembur2 . " lembur3 : " . $lembur3 . "<br>";
				$role = $this->session->userdata('role_id');
				if ($role == '1' or $role == '5' or $role == '3') {

					$data_durasi = array(
						'mdf_by'				=> $this->session->userdata('kar_id'),
						'mdf_date'				=> date('y-m-d H:i:s'),
						'durasi_lembur'			=> $durasi,
						'lembur1'				=> $lembur1,
						'lembur2'				=> $lembur2,
						'lembur3'				=> $lembur3,
					);
					$this->m_lembur->stkl_detail_update($data_durasi, $ud->recid_detstkl);
				}
			}

			$totreal = $this->m_lembur->hitung_real_jam($recid_stkl);
			foreach ($totreal->result() as $tr) {
				$jum_real = $tr->total;
			}

			$role = $this->session->userdata('role_id');
			if ($role == '1' or $role == '5' or $role == '3') {

				$status = "selesai";
				$approval = "Realisasi HC";

				$cek_holiday = $this->m_lembur->cek_holiday($tgl_lembur);
				if ($cek_holiday->num_rows() > 0) {
					foreach ($cek_holiday->result() as $ch) {
						if ($ch->jenis == 'Libur Perusahaan') {
							$flag_holiday = '3';
						} else {
							$flag_holiday = '2';
						}
					}
				} else {
					$nameOfDay = date('D', strtotime($tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$flag_holiday = '1';
					} else {
						$flag_holiday = '0';
					}
				}
				$data = array(
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d H:i:s'),
					'tgl_lembur'			=> $tgl_lembur,
					'recid_mbl'				=> $recid_mbl,
					'jam_mulai'				=> $jam_mulai,
					'jam_selesai'			=> $jam_selesai,
					'klasifikasi'			=> $klasifikasi,
					'tipe'					=> $tipe,
					'recid_kategori'		=> $recid_kategori,
					'status'				=> $status,
					'approval'				=> $approval,
					'totjam_real'			=> $jum_real,
					'jml_orang'				=> $jml_orang,
					'alasan_over'			=> $alasan_over,
					'keterangan'			=> $keterangan,
					'jemputan'				=> $this->input->post('jemputan'),
					'makan'					=> $this->input->post('makan'),
					'flag_holiday'			=> $flag_holiday,
				);
			} else {
				$status = "realisasi";
				$approval = "Realisasi Bagian";
				$data = array(
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d H:i:s'),
					'tgl_lembur'			=> $tgl_lembur,
					'recid_mbl'				=> $recid_mbl,
					'jam_mulai'				=> $jam_mulai,
					'jam_selesai'			=> $jam_selesai,
					'klasifikasi'			=> $klasifikasi,
					'tipe'					=> $tipe,
					'recid_kategori'		=> $recid_kategori,
					'status'				=> $status,
					'approval'				=> $approval,
					'totjam_real'			=> $jum_real,
					'jml_orang'				=> $jml_orang,
					'alasan_over'			=> $alasan_over,
					'keterangan'			=> $keterangan,
				);
			}
			$this->m_lembur->stkl_update($data, $recid_stkl);
			// collect workflow
			$data_wf = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d H:i:s'),
				'recid_stkl'			=> $recid_stkl,
				'status'				=> $status,
				'approval'				=> $approval,
				'pic_wf'				=> $this->session->userdata('kar_id'),
			);
			$this->m_lembur->wf_insert($data_wf);

			$this->email_pengajuan($recid_stkl);
			redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function email_pengajuan($recid_stkl)
	{

		//Load email library
		$this->load->library('email');
		$msg = "";
		$stkl = $this->m_lembur->stkl_id($recid_stkl);
		$recid_struktur = 0; // Initialize variable
		$dept_group = "";
		foreach ($stkl->result() as $s) {
			$recid_bag = $s->recid_bag;
			$dept_group = $s->dept_group;
			$recid_struktur = $s->recid_struktur;
		}
		// echo $recid_struktur;
		$terima = array();
		$title = "[NOTIFIKASI] ";
		$pic = $this->m_lembur->pic_struktur($recid_struktur);
		$pic_lindep = $this->m_lembur->pic_lindep($recid_stkl);
		foreach ($pic_lindep->result() as $pl) {
			// array_push($terima, $pl->email);
			$epic = $pl->email;
		}

		if ($pic->num_rows() > 0) {
			foreach ($pic->result() as $p) {
				if ($s->approval == 'Belum Acc Manager') {
					array_push($terima, $p->email);
					array_push($terima, $epic);
					$title .= "Pengajuan Lembur ";
					$ke = "Manager / Ass Manager " . $s->indeks_hr;
				} else if ($s->approval == 'Acc Manager' or $s->approval == 'Tidak Acc Manager') {
					if ($s->approval == 'Acc Manager') {
						if ($dept_group == 'Middle Office') {
							array_push($terima, "ade.arifin@chitose.id");
							array_push($terima, "anita@chitose.id");
							$ke = "Direktur Middle Office ";
							$title .= "Pengajuan Lembur ";
						} else {
							array_push($terima, "diah@chitose.id");
							$ke = "Manager HC ";
							$title .= "Pengajuan Lembur ";
						}
					} else {
						array_push($terima, $p->email);
						array_push($terima, $epic);
						$ke = "Manager / Ass Manager " . $s->indeks_hr;
						$title .= "Pengajuan Lembur ";
					}
				} else if ($s->approval == 'Acc Direksi' or $s->approval == 'Tolak Direksi') {
					if ($s->approval == 'Acc Direksi') {
						array_push($terima, "diah@chitose.id");
						$ke = "Manager HC ";
						$title .= "Pengajuan Lembur ";
					} else {
						array_push($terima, $p->email);
						array_push($terima, $epic);
						$ke = "Manager / Ass Manager " . $s->indeks_hr;
						$title .= "Pengajuan Lembur ";
					}
				} else if ($s->approval == 'Acc HC' or $s->approval == 'Tidak Acc HC') {
					if ($dept_group == 'Middle Office') {
						array_push($terima, $p->email);
						array_push($terima, $epic);
						array_push($terima, "ade.arifin@chitose.id");
						array_push($terima, "anita@chitose.id");
						$ke = "Manager / Ass Manager " . $s->indeks_hr;
						$title .= "Pengajuan Lembur ";
					} else {
						array_push($terima, $p->email);
						array_push($terima, $epic);
						$ke = "Manager / Ass Manager " . $s->indeks_hr;
						$title .= "Pengajuan Lembur ";
					}
				} else if ($s->approval == 'Realisasi Bagian') {
					array_push($terima, $p->email);
					$title .= "Realisasi Lembur Admin Bagian";
					$ke = "Manager / Ass Manager " . $s->indeks_hr;
				} else if ($s->approval == 'Acc Realisasi Manager') {
					array_push($terima, "hrd@chitose.id");
					$title .= "Acc Realisasi Manager";
					$ke = "HC Payroll ";
				} else {
					$msg .= "Administrasi lemburan selesai";
					$title .= "Pengajuan - Realisasi Lembur ";
					$ke = "All ";
				}
			}

			array_push($terima, "anysah@chitose.id");

			$tgl = date('d-M-Y');
			$title .= $tgl;

			$pekerjaan = "";
			$pk = $this->m_lembur->pekerjaan($recid_stkl);
			$pkn = $pk->num_rows();
			if ($pkn > 0) {
				$np = 0;
				foreach ($pk->result() as $p) {
					$np = $np + 1;
					if ($np < $pkn) {
						$pekerjaan .= "" . $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= " " . $p->pekerjaan;
					}
				}
			} else {
				$pekerjaan .= " - ";
			}


			//SMTP & mail configuration
			$config = array(
				'protocol'  => 'smtp',
				'smtp_host' => 'mail.chitose.id',
				'smtp_port' => 25,
				'smtp_user' => 'hrd@chitose.id',
				'smtp_pass' => '$DrH77##',
				'mailtype'  => 'html',
				'charset'   => 'utf-8'
			);
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");

			$tbl1 = "<table border='0'><tr><td>Tanggal</td><td> : $s->tgl_lembur</td></tr><tr><td>Jam Lembur</td><td> : $s->jam_mulai s/d $s->jam_selesai</td></tr><tr><td>Kategori</td><td> : $s->kategori</td></tr><tr><td>Pekerjaan</td><td> : $pekerjaan</td></tr><tr><td>Status</td><td> : <b>" . ucfirst($s->status) . " - $s->approval</b></td></tr></table>";

			$no = 1;
			$tbl2 = "<table border = '1'><tr><td>No</td><td>NIK</td><td>Nama Karyawan</td><td>Bagian</td><td>Jabatan</td></tr>";
			$kry = $this->m_lembur->karyawan_lembur($recid_stkl);
			foreach ($kry->result() as $kr) {
				$tbl2 .=
					"<tr><td><center>" . $no++ . "</center></td><td>$kr->nik</td><td>$kr->nama_karyawan</td><td>$kr->indeks_hr</td><td>$kr->indeks_jabatan</td></tr>";
			}
			$tbl2 =  $tbl2 . "</table>";

			//Email content
			$htmlContent = '<p>' . $tgl . '</p>';
			$htmlContent .= '<p>Dear ' . $ke . ',  </p>';
			$htmlContent .= '<p>Berikut pengajuan lembur yang diajukan dengan detail sebagai berikut : </p>';
			$htmlContent .=  $tbl1;
			$htmlContent .= '<h4>Daftar Karyawan yang Mengikuti Lembur </h4>';
			$htmlContent .=  $tbl2;
			$htmlContent .= '<p><br>' . $msg . '</p>';
			$htmlContent .= '<p>Buka akun HRIS : https://hris.chitose.id </p>';
			$htmlContent .= '<p>Terima Kasih</p>';
			$htmlContent .= '<p><br>Regards</p>';
			$htmlContent .= '<p><u>HC & GA Departement</u></p>';
			$htmlContent .= '<p>PT. CHITOSE INTERNASIONAL TBK</p>';
			// echo $htmlContent;

			$terima = array_unique($terima);
			// echo $title."<br>";
			// echo $ke."<br>";
			// print_r($terima);
			// print_r($list);
			// $recipients = array('it.sysdev@chitose-indonesia.com');
			$this->email->to(implode(', ', $terima));
			// $this->email->to($recipients);
			$this->email->from('hrd@chitose.id', 'HRD Payroll');
			$this->email->subject($title);
			$this->email->message($htmlContent);

			//Send email

			if ($this->email->send()) {
				echo "Email Has Been Sent!";
			} else {
				echo "Email Failed";
				show_error($this->email->print_debugger());
			}
		} else {
			echo "Tidak Ada Email Atasan";
		}
	}

	public function stkl_detailperiode()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		// Initialize department variable to prevent undefined variable error
		$department = '';
		$nama = '';
		$bagian = '';
		$recid_bag = '';
		$jabatan = '';
		$tingkatan = '';
		$struktur = '';
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$department = $user->nama_department;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		$jenis = $this->input->post('jenis');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25' or $role == '32') {
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->det_stkl_prd($tgl_awal, $tgl_akhir);
			} else {
				$query2 = $this->m_lembur->det_stkl_prd_status($tgl_awal, $tgl_akhir, $jenis);
			}
		} else {
			if ($jenis == 'Semua') {
				$query2 = $this->m_lembur->det_stkl_prd_deptgroup($tgl_awal, $tgl_akhir, $department);
			} else {
				$query2 = $this->m_lembur->det_stkl_prd_status_deptgroup($tgl_awal, $tgl_akhir, $department, $jenis);
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			// $no = 0;
			if ($role == 1) {
				$data[] = array(
					$no = $no + 1,
					$r->recid_stkl,
					$tgl_lembur,
					$r->bag_lembur,
					$r->nik,
					$r->nama_karyawan,
					$r->jam_mulai,
					$r->jam_selesai,
				);
			} else {
				$data[] = array(
					$no = $no + 1,
					$tgl_lembur,
					$r->bag_lembur,
					$r->nik,
					$r->nama_karyawan,
					$r->jam_mulai,
					$r->jam_selesai,
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

	public function stkl_detailview()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hari_ini = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_detailview', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function stkl_karyawan()
	{

		$usr = $this->session->userdata('kar_id');
		$data['karyawan']	= $this->m_hris->karyawan_view();
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('stkl/report/stkl_karyawan', $data);
		$this->load->view('layout/a_footer');
	}

	public function get_stkl_karyawan()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		// Initialize department variable to prevent undefined variable error
		$department = '';
		$nama = '';
		$bagian = '';
		$recid_bag = '';
		$jabatan = '';
		$tingkatan = '';
		$struktur = '';
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$department = $user->nama_department;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		// $recid_karyawan = $this->input->post('recid_karyawan');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25' or $role == '32') {
			$query2 = $this->m_lembur->det_stkl_prd($tgl_awal, $tgl_akhir);
		} else if ($role == '23') {
			$bag = array();
			$bagian = "b.indeks_hr = ";
			$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
			foreach ($bgn->result() as $bg) {
				if (array_key_exists($bg->recid_bag, $bag)) {
					// echo "Key exists!";
				} else {
					array_push($bag, $bg->recid_bag);
				}
			}

			for ($i = 0; $i < count($bag); $i++) {
				// echo $bag[$i]."<br>";
				$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
				foreach ($iindex->result() as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			// echo $bagian;
			$query2 = $this->m_lembur->det_stkl_prd_bagian($tgl_awal, $tgl_akhir, $bagian);
		} else {
			$query2 = $this->m_lembur->det_stkl_prd_deptgroup($tgl_awal, $tgl_akhir, $department);
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$jam_plg = "";
			$jam_msk = "";
			$jam_pulang = $this->m_absenbarcode->cek_double($r->recid_karyawan, $r->tgl_lembur);
			foreach ($jam_pulang->result() as $j) {
				$jam_plg = $j->jam_keluar;
				$jam_msk = $j->jam_masuk;
				$tgl_masuk = $j->tgl_masuk;
				$tgl_pulang = $j->tgl_pulang;
			}
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			if ($r->jemputan == '0') {
				$jemputan = 'Jemputan';
			} else if ($r->jemputan == '1') {
				$jemputan = 'Uang Transport';
			} else {
				$jemputan = $r->jemputan;
			}

			if ($r->makan == '0') {
				$makan = 'Catering';
			} else if ($r->makan == '1') {
				$makan = 'Uang Makan';
			} else {
				$makan = $r->makan;
			}

			if ($r->flag_holiday == '0') {
				$flag_holiday = 'Hari Biasa';
			} else if ($r->flag_holiday == '1') {
				$flag_holiday = 'Akhir Pekan';
			} else if ($r->flag_holiday == '2') {
				$flag_holiday = 'Libur Nasional';
			} else if ($r->flag_holiday == '3') {
				$flag_holiday = 'Libur Perusahaan';
			} else {
				$flag_holiday = $r->flag_holiday;
			}

			if ($r->status == 'pengajuan') {
				$status = "<span class='badge progress-bar-danger'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Belum Acc Manager") {
					$approval = "<span class='badge progress-bar-danger'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Manager') {
					$approval = "<span class='badge progress-bar-warning'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc HC') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				}
			} else if ($r->status == 'realisasi') {
				$status = "<span class='badge progress-bar-warning'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Realisasi Bagian") {
					$approval = "<span class='badge progress-bar-info'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Realisasi Manager') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Tidak Acc Realisasi') {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				}
			} else {
				$status = "<span class='badge progress-bar-success'>" . ucfirst($r->status) . "</span>";
				$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
			}


			$get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
			$pekerjaan = "";
			$jml_kerja = $get_pekerjaan->num_rows();
			if ($jml_kerja > 0) {
				$i = 0;
				foreach ($get_pekerjaan->result() as $p) {
					$i = $i + 1;
					if ($i < $jml_kerja) {
						$pekerjaan .= $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= $p->pekerjaan;
					}
				}
			}

			// $no = 0;
			$data[] = array(
				$no = $no + 1,
				$r->recid_stkl,
				$status,
				$approval,
				$tgl_lembur,
				$r->bag_lembur,
				$r->nik,
				$r->nama_karyawan,
				$r->bag_kry,
				$jam_msk,
				$r->jam_mulai,
				$r->jam_selesai,
				$jam_plg,
				$r->durasi_lembur,
				$r->lembur1,
				$r->lembur2,
				$r->lembur3,
				$r->klasifikasi,
				$r->tipe,
				$r->kategori,
				$pekerjaan,
				$jemputan,
				$makan,
				$flag_holiday,
				$r->keterangan,
				$r->alasan_over,
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

	public function det_lembur_tahun()
	{
		$tipe = $this->uri->segment(3);
		$dept = $this->uri->segment(4);
		$tahun = $this->uri->segment(5);
		if ($dept != "") {
			$dept = str_replace("%20", " ", $dept);
		}
		$usr = $this->session->userdata('kar_id');
		$data['tipe']	= $tipe;
		$data['dept']	= $dept;
		$data['tahun']	= $tahun;
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		if ($dept == 'semua') {
			if ($tipe == "Realisasi") {
				$data['stkl'] =  $this->m_lembur->det_real_tahun($tahun);
				$this->load->view('stkl/report/det_chart_stkl', $data);
			} else {
				$data['budget'] =  $this->m_lembur->det_budget_tahun($tahun);
				$this->load->view('master_budget/new_report/det_mb_tahun', $data);
			}
		} else {

			if ($tipe == "Realisasi") {
				$data['stkl'] = $this->m_lembur->det_real_tahun_dept($tahun, $dept);
				$this->load->view('stkl/report/det_chart_stkl', $data);
			} else {
				$data['budget'] =  $this->m_lembur->det_budget_tahun_dept($tahun, $dept);
				$this->load->view('master_budget/new_report/det_mb_tahun', $data);
			}
		}
		$this->load->view('layout/a_footer');
	}

	public function get_workflow()
	{
		$recid_stkl = $this->input->post('recid_stkl');
		$isi_wf = "
		<div class='dashboard-widget-content'>
        <ul class='list-unstyled timeline widget'>";
		// $recid_stkl = '45';
		$wf = $this->m_lembur->get_workflow($recid_stkl);
		$data = array();
		if ($wf->num_rows() < 1) {
			array_push($data, '0');
			$isi_wf .= " <li><div class='block'>
                <div class='block_content'>
                <h2 class='title'>
                Tidak Ada Workflow Untuk STKL Ini
                 </h2>
                <div class='byline'>
                </div>
                 <p class='excerpt'>Workflow untuk STKL ini tidak ditemukan. Silahkan hubungi bagian IT.
                </p>
                </div>
            </div></li>";
		} else {
			array_push($data, '1');
			$wf_stage = array("Baru Dibuat", "Approval Manager", "Approval HC", "Realisasi Bagian", "Approval Realisasi", "Selesai");
			foreach ($wf->result() as $w) {
				$stage = array();
				if ($w->sts_wf == "Belum Acc Manager") {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>Baru Dibuat</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL baru saja dibuat, menunggu approval manager.</p></div></div></li>";
					array_push($stage, '1');
					array_push($stage, 'Baru Dibuat');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL baru saja dibuat, menunggu approval manager');
				} else if ($w->sts_wf == "Acc Manager") {
					if ($w->dept_group == 'Middle Office') {
						$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Manager</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL sudah di approve oleh manager, menunggu approval Direksi.
					</p></div></div></li>";
						array_push($stage, '2');
						array_push($stage, 'Approval Manager');
						array_push($stage, $w->sts_wf);
						array_push($stage, 'STKL sudah di approve oleh manager, menunggu approval Direksi');
					} else {
						$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Manager</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL sudah di approve oleh manager, menunggu approval HC.
					</p></div></div></li>";
						array_push($stage, '2');
						array_push($stage, 'Approval Manager');
						array_push($stage, $w->sts_wf);
						array_push($stage, 'STKL sudah di approve oleh manager, menunggu approval HC');
					}
				} else if ($w->sts_wf == 'Tidak Acc Manager') {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Manager</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL di <b>TOLAK</b> oleh Manager.</p></div></div></li>";
					array_push($stage, '2');
					array_push($stage, 'Approval Manager');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL di <b>TOLAK</b> oleh manager');
				} else if ($w->sts_wf == "Acc Direksi") {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Manager</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL sudah di approve oleh Direksi, menunggu approval HC.
					</p></div></div></li>";
					array_push($stage, '2');
					array_push($stage, 'Approval Manager');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL sudah di approve oleh Direksi, menunggu approval HC');
				} else if ($w->sts_wf == "Tolak Direksi") {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Manager</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL di <b>TOLAK</b> oleh Direksi.
					</p></div></div></li>";
					array_push($stage, '2');
					array_push($stage, 'Approval Manager');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL di <b>TOLAK</b> oleh Direksi');
				} else if ($w->sts_wf == 'Acc HC') {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval HC</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL sudah di approve oleh HC. Silahkan lakukan realisasi pada H+1 dari tanggal lembur.</p></div></div></li>";
					array_push($stage, '3');
					array_push($stage, 'Approval HC');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL sudah di approve oleh HC. Silahkan lakukan realisasi pada H+1 dari tanggal lembur');
				} else if ($w->sts_wf == 'Tidak Acc HC') {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval HC</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL di <b>TOLAK</b> oleh Manager HC.</p></div></div></li>";
					array_push($stage, '3');
					array_push($stage, 'Approval HC');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL di <b>TOLAK</b> oleh manager HC');
				} else if ($w->sts_wf == 'Realisasi Bagian') {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Realisasi Bagian</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3>
					<p class='excerpt'>Note : STKL sudah direalisasi oleh admin bagian, menunggu approval realisasi dari manager.</p></div></div></li>";
					array_push($stage, '4');
					array_push($stage, 'Realisasi Bagian');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL sudah direalisasi oleh admin bagian, menunggu approval realisasi dari manager');
				} else if ($w->sts_wf == 'Acc Realisasi Manager') {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Approval Realisasi</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : Realisai STKL sudah di approve oleh manager, menunggu realisasi dari HC.</p></div></div></li>";
					array_push($stage, '5');
					array_push($stage, 'Approval realisasi');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'Realisai STKL sudah di approve oleh manager, menunggu realisasi dari HC');
				} else {
					$isi_wf .= " <li><div class='block'><div class='block_content'><h2 class='title'>
					Realisasi HC</h2><div class='byline'><span>Waktu : " . $w->tgl_wf . "</span> Oleh : <a>" . $w->nama_pic . "</a></div><h3> Status : " . $w->sts_wf . "</h3><p class='excerpt'>Note : STKL sudah direalisasi oleh HC. Proses pendataan lembur selesai.</p></div></div></li>";
					array_push($stage, '6');
					array_push($stage, 'Selesai');
					array_push($stage, $w->sts_wf);
					array_push($stage, 'STKL sudah direalisasi oleh HC. Proses pendataan lembur selesai');
				}
				array_push($stage, $w->nama_pic);
				array_push($stage, $w->tgl_wf);
				array_push($data, $stage);
			}
		}
		// echo $isi_wf;
		echo json_encode($isi_wf);
		// $isi_wf .= "</ul></div></div>";
		// print_r($data);

	}

	public function sla_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$hari_ini = date('Y-m-d');
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/sla_stkl', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function get_sla()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$department = $user->nama_department;
		}
		$tgl_awal = $this->input->post('tgl_mulai');
		$tgl_akhir = $this->input->post('tgl_akhir');
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25') {
			$query2 = $this->m_lembur->get_workflow_periode($tgl_awal, $tgl_akhir);
		} else {
			$query2 = $this->m_lembur->get_workflow_periode_deptgroup($tgl_awal, $tgl_akhir, $department);
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			$status1 = $r->status_wf . " - " . $r->approval_wf;
			$waktu1 = $r->tgl_wf;
			$data[] = array(
				$no = $no + 1,
				$r->recid_stkl,
				$tgl_lembur,
				$r->indeks_hr,
				$r->jam_mulai,
				$r->jam_selesai,
				$r->jml_orang,
				$status1,
				$waktu1,
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

	public function adjust_realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $recid_stkl = $this->input->post('recid_stkl');
			$recid_stkl = $this->uri->segment(3);
			$stkl = $this->m_lembur->stkl_id($recid_stkl);
			foreach ($stkl->result() as $s) {
				$tgl_lembur = $s->tgl_lembur;
				$recid_mbl = $s->recid_mbl;
				$jam_mulai = $s->jam_mulai;
				if ($jam_mulai != "") {
					$jam_mulai = str_replace(" ", "", $s->jam_mulai);
				}
				$jam_selesai = $s->jam_selesai;
				if ($jam_selesai != "") {
					$jam_selesai = str_replace(" ", "", $s->jam_selesai);
				}
				$klasifikasi = $s->klasifikasi;
				$tipe = $s->tipe;
				$recid_kategori = $s->recid_kategori;
				$total_jam = $s->total_jam;
				$jml_orang = $s->jml_orang;
				$alasan_over = $s->alasan_over;
				$keterangan = $s->keterangan;
			}

			$lembur1 = 0;
			$ulembur1 = 0;
			$lembur2 = 0;
			$ulembur2 = 0;
			$lembur3 = 0;
			$ulembur3 = 0;

			$cek_holiday = $this->m_lembur->cek_holiday($tgl_lembur);
			if ($cek_holiday->num_rows() > 0) {
				foreach ($cek_holiday->result() as $ch) {
					$fholi = '1';
				}
			} else {
				$nameOfDay = date('D', strtotime($tgl_lembur));
				$cek_gh = $this->m_absen->gh_by_date($tgl_lembur);
				if ($cek_gh->num_rows() > 0) {
					$fholi = "0";	//weekday OT
				} else {
					$nameOfDay = date('D', strtotime($tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$fholi = "1";	//weekend
					} else {
						$fholi = "0";	//weekday OT
					}
				}
			}
			echo $fholi;

			$updt_durasi = $this->m_lembur->karyawan_lembur($recid_stkl);
			foreach ($updt_durasi->result() as $ud) {
				$jam_barcode = $this->m_lembur->cek_kehadiran($tgl_lembur, $ud->recid_karyawan);
				if ($jam_barcode->num_rows() > 0) {
					foreach ($jam_barcode->result() as $jb) {
						$jam_masuk = $jb->jam_masuk;
						$jam_pulang = $jb->jam_keluar;
						$tgl_pulang = $jb->tgl_pulang;
						$tgl_masuk = $jb->tgl_masuk;
						$jam_in = $jb->jam_in;
						$jam_in2 = $jb->jam_in;
						$jam_in = date_create($jam_in);
						$cjam_in = date_add($jam_in, date_interval_create_from_date_string('1 minutes'));
						$jam_in = date_format($jam_in, 'H:i:s');
						if ($jam_masuk > $jam_in2  and $jam_masuk < $jam_in) {
							$jam_masuk = $jam_in2;
						}
					}

					// if($jam_pulang == "" or is_null($jam_pulang))
					// {
					// 	$jam_pulang = "-";
					// }
				} else {
					$jam_masuk = "-";
					$jam_pulang = "-";
				}

				// echo $jam_masuk;
				$krg_jam = 0;
				if ($fholi == '1') {
					// jika long shift hari libur
					$lembur1 = 0;
					echo "jam lembur : " . $jam_mulai . " - " . $ud->jam_sls . "<br>";
					/* ---- JIKA LEMBUR SHIFT DI HARI LIBUR ---- */

					if (($jam_mulai == "06:00" && $ud->jam_sls == "14:00:00") || ($jam_mulai == "14:00" && $ud->jam_sls == "22:00:00") || ($jam_mulai == "22:00" && $ud->jam_sls == "06:00:00")) {
						// kalo jam masuk / pulangnya 00/-/''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							// masuk normal
							if ($jam_pulang >= $ud->jam_sls) {
								// tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 1";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 2";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 3";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 4";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								}
							}
							echo $ceks;
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								echo  "0 jam";
								$durasi = "0";
							} else {
								// echo "$jam Jam $menit Menit";
								$menit = $menit / 60;
								$menit = substr($menit, strpos($menit, ",") + 1);
								$durasi = $jam . "." . $menit;
							}
						} else {
							// jam masuk dan pulang data normal
							if ($jam_pulang >= $ud->jam_sls) {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 5";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 6";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 7";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 8";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang cepat";
								}
							}
							echo $ceks;
							$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								// $durasi = $jam;
								$menit = $menit / 60;
								$menit = substr($menit, strpos($menit, ",") + 1);
								$durasi = $jam . "." . $menit;
							}
						}
						if ($durasi == "0") {
							$lembur2 = 0;
							$lembur3 = 0;
						} else if ($durasi >= 8) {
							$lembur2 = 8;
							$lembur3 = $durasi - 8;
							$lembur3 = $lembur3;
						} else {
							$lembur2 = $durasi;
							$lembur3 = 0;
						}
					} else {
						/* ASLI NON SHIFT LIBUR */
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							//masuk normal
							if ($jam_pulang >= $ud->jam_sls) {
								//tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 9";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, masuk & pulang normal";
								} else {
									echo "cek HL 10";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, masuk & pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 11";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, masuk telat, pulang cepat";
								} else {
									echo "cek HL 12";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, masuk telat, pulang cepat";
								}
							}
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam <= 10) {
									$krg_jam = 1;
								} else if ($kaljam > 10 and $kaljam < 15) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 2;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2.5;
								} else {
									$krg_jam = $krg_jam;
								}
								$durasi = $kaljam - $krg_jam;
								// echo $durasi;
							}
						} else {
							// jika masuk Telat
							if ($jam_pulang >= $ud->jam_sls) {
								//tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 13";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HL 14";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HL 15";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "beda tgl masuk & pulang, pulang cepat";
								} else {
									echo "cek HL 16";
									$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang cepat";
								}
							}
							$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 1;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 2;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2.5;
								} else {
									$krg_jam = $krg_jam;
								}
								$durasi = $kaljam - $krg_jam;
							}
						}
					}
					if ($durasi == "0") {
						$lembur2 = 0;
						$lembur3 = 0;
					} else if ($durasi >= 7) {
						$lembur2 = 7;
						$lembur3 = $durasi - 7;
						$lembur3 = $lembur3;
					} else {
						$lembur2 = $durasi;
						$lembur3 = 0;
					}
				} else { /* HARI BIASA */
					$cek_real_sameday = $this->m_lembur->realisasi_sameday($tgl_lembur, $ud->recid_karyawan);
					//JIKA OT SHIFT HARI BIASA
					if (($jam_mulai == "06:00:00" && $ud->jam_sls == "14:00:00") || ($jam_mulai == "14:00:00" && $ud->jam_sls == "22:00:00") || ($jam_mulai == "22:00:00" && $ud->jam_sls == "06:00:00")) {
						//jika jam masuk / pulang 00:00 atau - atau ''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else {
							// masuk normal
							if ($jam_masuk <= $jam_mulai) {
								if ($jam_pulang >= $ud->jam_sls) {
									//tidak pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										//beda tanggal
										echo "cek HB 1";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
									} else {
										echo "cek HB 2";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
									}
								} else {
									//pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 3";
										$akhir = strtotime($tgl_masuk . ' ' . $key->jam_selesai);
										$ceks = "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
									} else {
										echo "cek HB 4";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
									}
								}
								$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
								$diff   = $akhir - $mulai;
								$jam    = floor($diff / (60 * 60));
								$menit  = ($diff - $jam * (60 * 60)) / 60;
								if ($jam < 0) {
									$durasi = "0";
								} else {
									// $durasi = $jam.$menit;
									$menit = $menit / 60;
									$menit = substr($menit, strpos($menit, ",") + 1);
									$durasi = $jam . "." . $menit;
								}
							} else {
								// masuk telat
								if ($jam_pulang >= $ud->jam_sls) {
									//tidak puang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 5";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "beda tgl masuk & pulang, pulang normal";
									} else {
										echo "cek HB 6";
										$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
										$ceks = "sama tgl masuk & pulang, pulang normal";
									}
								} else {
									//pulang cepat
									if ($tgl_pulang != $tgl_masuk) {
										echo "cek HB 7";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "beda tgl masuk & pulang, pulang lebih cepat";
									} else {
										echo "cek HB 8";
										$akhir = strtotime($tgl_pulang . ' ' . $jam_pulang);
										$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
									}
								}
								$mulai = strtotime($tgl_masuk . ' ' . $jam_masuk);
								$diff   = $akhir - $mulai;
								$jam    = floor($diff / (60 * 60));
								$menit  = ($diff - $jam * (60 * 60)) / 60;
								if ($jam < 0) {
									$durasi = "0";
								} else {
									$menit = $menit / 60;
									$menit = substr($menit, strpos($menit, ",") + 1);
									$durasi = $jam . "." . $menit;
								}
							}
						}
						if ($durasi == "0") {
							$lembur1 = 0;
							$lembur2 = 0;
						} else if ($durasi >= 8) {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
							} else {
								$lembur1 = 8;
								$lembur2 = $durasi - 8;
								$lembur2 = $lembur2;
							}
						} else {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur2 = $durasi;
								$lembur1 = 0;
							} else {
								$lembur1 = $durasi;
								$lembur2 = 0;
							}
						}
					} else {
						// BUKAN OT SHIFT = yang biasa
						// jika jam masuk 00:00 atau - atau ''
						if ($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00") {
							$durasi = "0";
						} else if ($jam_masuk <= $jam_mulai) {
							//masuk normal
							if ($jam_pulang > $ud->jam_sls) {
								// tidak pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 9";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HB 10";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								// pulang cepat
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 11";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
								} else {
									echo "cek HB 12";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
								}
							}
							echo $ceks . " - " . $tgl_lembur . ' ' . $jam_mulai . " s/d " . $tgl_lembur . ' ' . $ud->jam_sls . "<br>";
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 0.5;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2;
								} else {
									$krg_jam = $krg_jam;
								}

								$durasi = $kaljam - $krg_jam;
							}
						} else {
							// masuk telat
							if ($jam_pulang > $ud->jam_sls) {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 13";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang, pulang normal";
								} else {
									echo "cek HB 14";
									$akhir = strtotime($tgl_pulang . ' ' . $ud->jam_sls);
									$ceks = "sama tgl masuk & pulang, pulang normal";
								}
							} else {
								if ($tgl_masuk != $tgl_pulang) {
									echo "cek HB 15";
									$akhir = strtotime($tgl_masuk . ' ' . $ud->jam_sls);
									$ceks = "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
								} else {
									echo "cek HB 16";
									$akhir = strtotime($tgl_masuk . ' ' . $jam_pulang);
									$ceks = "sama tgl masuk & pulang, pulang lebih cepat";
								}
							}
							// $mulai = strtotime($tgl_lembur.' '.$jam_mulai);
							echo $ceks . " - " . $tgl_lembur . ' ' . $jam_mulai . " s/d " . $tgl_lembur . ' ' . $ud->jam_sls . "<br>";
							$mulai = strtotime($tgl_masuk . ' ' . $jam_mulai);
							$diff   = $akhir - $mulai;
							$jam    = floor($diff / (60 * 60));
							$menit  = ($diff - $jam * (60 * 60)) / 60;
							if ($jam < 0) {
								$durasi = "0";
							} else {
								$menit = $menit / 60;
								$kaljam = $jam + $menit;
								if ($kaljam >= 5 and $kaljam < 10) {
									$krg_jam = 0.5;
								} else if ($kaljam >= 10 and $kaljam < 15) {
									$krg_jam = 1;
								} else if ($kaljam >= 15 and $kaljam < 20) {
									$krg_jam = 1.5;
								} else if ($kaljam >= 20 and $kaljam < 24) {
									$krg_jam = 2;
								} else {
									$krg_jam = $krg_jam;
								}

								$durasi = $kaljam - $krg_jam;
								// echo $kaljam."-".$krg_jam." = ".$durasi;
							}
						}
						// echo $durasi."<br>";
						if ($durasi == "0") {
							$lembur1 = 0;
							$lembur2 = 0;
						} else if ($durasi >= 1) {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
								$lembur2 = $lembur2;
							} else {
								$lembur1 = 1;
								$lembur2 = $durasi - 1;
								$lembur2 = $lembur2;
							}
						} else {
							if ($cek_real_sameday->num_rows() > 0) {
								$lembur1 = 0;
								$lembur2 = $durasi;
							} else {
								$lembur1 = $durasi;
								$lembur2 = 0;
							}
						}
					}
				}

				echo "recid_karyawan : " . $ud->recid_karyawan . " => lembur 1 : " . $lembur1 . " lembur 2 : " . $lembur2 . " lembur 3 : " . $lembur3 . "<br>";
				// echo 'jam_pulang : '.$jam_pulang.'recid stkl : '.$recid_stkl." karyawan : ".$ud->recid_karyawan."- lembur 1 : ".$lembur1." lembur2 : ".$lembur2." lembur3 : ".$lembur3."<br>";
				$data_durasi = array(
					'durasi_lembur'			=> $durasi,
					'lembur1'				=> $lembur1,
					'lembur2'				=> $lembur2,
					'lembur3'				=> $lembur3,
				);
				$this->m_lembur->stkl_detail_update($data_durasi, $ud->recid_detstkl);
			}

			$totreal = $this->m_lembur->hitung_real_jam($recid_stkl);
			foreach ($totreal->result() as $tr) {
				$jum_real = $tr->total;
			}

			$role = $this->session->userdata('role_id');
			if ($role == '1' or $role == '5') {

				// $status = "selesai";
				// $approval = "Realisasi HC";

				$cek_holiday = $this->m_lembur->cek_holiday($tgl_lembur);
				if ($cek_holiday->num_rows() > 0) {
					foreach ($cek_holiday->result() as $ch) {
						if ($ch->jenis == 'Libur Perusahaan') {
							$flag_holiday = '3';
						} else {
							$flag_holiday = '2';
						}
					}
				} else {
					$nameOfDay = date('D', strtotime($tgl_lembur));
					if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
						$flag_holiday = '1';
					} else {
						$flag_holiday = '0';
					}
				}
				$data = array(
					'tgl_lembur'			=> $tgl_lembur,
					'recid_mbl'				=> $recid_mbl,
					'jam_mulai'				=> $jam_mulai,
					'jam_selesai'			=> $jam_selesai,
					'klasifikasi'			=> $klasifikasi,
					'tipe'					=> $tipe,
					'recid_kategori'		=> $recid_kategori,
					'totjam_real'			=> $jum_real,
					'jml_orang'				=> $jml_orang,
					'alasan_over'			=> $alasan_over,
					'keterangan'			=> $keterangan,
					'flag_holiday'			=> $flag_holiday,
				);
			} else {
				$status = "realisasi";
				$approval = "Realisasi Bagian";
				$data = array(
					'tgl_lembur'			=> $tgl_lembur,
					'recid_mbl'				=> $recid_mbl,
					'jam_mulai'				=> $jam_mulai,
					'jam_selesai'			=> $jam_selesai,
					'klasifikasi'			=> $klasifikasi,
					'tipe'					=> $tipe,
					'recid_kategori'		=> $recid_kategori,
					'totjam_real'			=> $jum_real,
					'jml_orang'				=> $jml_orang,
					'alasan_over'			=> $alasan_over,
					'keterangan'			=> $keterangan,
				);
			}
			$this->m_lembur->stkl_update($data, $recid_stkl);
			// redirect('Lembur/stkl_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_stkl1()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['tahun']	= $this->m_lembur->co_tahun_report();
			$data['tahun']	= $this->m_lembur->co_tahun_report();
			$tahun = Date('Y');
			$bulan = Date('m');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$data['bulans'] = $bulan;
			$co = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
			foreach ($co->result() as $c) {
				$tgl_mulai = $c->periode_awal;
				$tgl_akhir = $c->periode_akhir;
			}
			$data['periode_awal']  = $tgl_mulai;
			$data['periode_akhir']  = $tgl_akhir;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/r_stkl1', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function report_stkl1()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$tahun = $this->input->post('tahun');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$data['tgl_mulai']	= $tgl_mulai;
			$data['tgl_akhir']	= $tgl_akhir;

			$data['pay_group'] = $this->m_hris->paygroup();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $bulan;
			$data['tahun']	= $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/report_stkl1', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function detreport_stkl1()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$tahun = $this->input->post('tahun');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$data['tgl_mulai']	= $tgl_mulai;
			$data['tgl_akhir']	= $tgl_akhir;

			$data['pay_group'] = $this->m_hris->paygroup();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $bulan;
			$data['tahun']	= $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/detreport_stkl1', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_stkl2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['tahun']	= $this->m_lembur->co_tahun_report();
			$tahun = Date('Y');
			$bulan = Date('m');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$data['bulans'] = $bulan;
			$co = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
			foreach ($co->result() as $c) {
				$tgl_mulai = $c->periode_awal;
				$tgl_akhir = $c->periode_akhir;
			}
			$data['periode_awal']  = $tgl_mulai;
			$data['periode_akhir']  = $tgl_akhir;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/r_stkl2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function report_stkl2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$data['tgl_mulai']	= $tgl_mulai;
			$data['tgl_akhir']	= $tgl_akhir;
			$data['pay_group'] = $this->m_hris->paygroup_emp();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $bulan;
			$data['tahun']	= $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/report_stkl2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function detreport_stkl2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			$list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			$bulan = $list_bulan[$bulan - 1];
			$tgl_mulai = $this->input->post('tgl_mulai');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$data['tgl_mulai']	= $tgl_mulai;
			$data['tgl_akhir']	= $tgl_akhir;
			$data['pay_group'] = $this->m_hris->paygroup_emp();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $bulan;
			$data['tahun']	= $tahun;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/report/detreport_stkl2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_cut_off()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		$nama_bulan = $month[$bulan - 1];
		$co = $this->m_lembur->cutoff_thn_bln($tahun, $nama_bulan);
		$data = array();
		foreach ($co->result() as $c) {
			array_push($data, $c->periode_awal);
			array_push($data, $c->periode_akhir);
		}
		echo json_encode($data);
		exit();
	}

	public function get_stkl_pending()
	{
		$usr = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$recid_bag = $user->recid_bag;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$recid_struktur = $user->recid_struktur;
			$department = $user->nama_department;
			$dept_group = $user->dept_group;
		}
		if ($role == '1' or $role == '3' or $role == '5' or $role == '25') {
			$query2 = $this->m_lembur->stkl_pending();
		} else if ($role == '23') {
			// echo $usr;
			$bag = array();
			$bagian = "b.indeks_hr = ";
			// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
			$bgn = $this->m_hris->bagian_view_by_atasan($usr);

			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}

			for ($i = 0; $i < count($bag); $i++) {
				// echo $bag[$i]."<br>";
				$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
				foreach ($iindex->result() as $s) {
					$indeks_hr = $s->indeks_hr;
				}
				if ($i < (count($bag) - 1)) {
					$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
				} else {
					$bagian .= "'" . $indeks_hr . "'";
				}
			}
			// echo $bagian;
			if ($tingkatan >= 8) {
				$query2 = $this->m_lembur->stkl_pending_managerbagian($bagian);
			} else {
				$query2 = $this->m_lembur->stkl_pending_admbagian($bagian);
			}
		} else {
			if ($role == '30' or $role == '26' or $role == '35' or $role == '23') {
				$recid_login = $this->session->userdata('recid_login');
				$bagian = "b.indeks_hr = ";
				$recid_karyawan = $this->session->userdata('kar_id');
				$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
				$bag = array();
				foreach ($bgn->result() as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					// echo $bag[$i]."<br>";
					$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
					foreach ($iindex->result() as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				$query2 = $this->m_lembur->stkl_pending_admbagian($bagian);
			} else if ($role == '34') {
				$bagian = "b.indeks_hr = ";
				$bgn = $this->m_hris->bagian_by_str('11');
				$bag = array();
				foreach ($bgn as $bg) {
					array_push($bag, $bg->recid_bag);
				}
				for ($i = 0; $i < count($bag); $i++) {
					// echo $bag[$i]."<br>";
					$iindex = $this->m_hris->bagian_by_recid2($bag[$i]);
					foreach ($iindex->result() as $s) {
						$indeks_hr = $s->indeks_hr;
					}
					if ($i < (count($bag) - 1)) {
						$bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
					} else {
						$bagian .= "'" . $indeks_hr . "'";
					}
				}
				// echo $bagian;
				$query2 = $this->m_lembur->stkl_pending_admbagian($bagian);
			} else if ($role == '24') {
				$query2 = $this->m_lembur->stkl_pending_direksi($department);
			} else {
				$query2 = $this->m_lembur->stkl_pending_deptgroup($department);
			}
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = [];
		$no = 0;
		foreach ($query2->result() as $r) {
			$tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
			$tmb = "";

			if ($role == '1'  or $role == '3' or $role == '5') {
				// $tmb .= "<button type='button' class='btn btn-danger btn-xs' onclick='confirmDelete(".$r->recid_stkl.")'><span class='fa fa-trash'></span></button>";
				$tmb .= "<button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
			}

			if ($r->approval == "Belum Acc Manager") {
				if ($role == '1' or ($tingkatan >= 8 and $tingkatan < 10) and $recid_struktur != 11) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($recid_struktur == 11 and $r->recid_struktur == 11 and ($tingkatan >= 8 and $tingkatan < 10)) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else if ($tingkatan >= 10 and $recid_struktur == '33') {
					// PPIC langsung approval GM
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					if ($tingkatan < 8 and ($role != '1' and $role != '3' and $role != '5')) {
						$tmb .= "<a href='" . base_url() . "Lembur/stkl_edit/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Edit'><button class='btn btn-info btn-xs'><span class='fa fa-pencil'></span></button></a>
				<button class='btn btn-danger btn-xs' data-toggle='modal' data-target='#myModalDel' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Delete'><span class='fa fa-trash'></span></button>";
					} else {
						// Tidak aksi apapun
					}
				}
			} else if ($r->approval == "Acc Manager") {
				if ($role == '1' or ($recid_struktur == '11' and $tingkatan >= 8) or $tingkatan >= 10) {
					if ($r->dept_group == 'Middle Office') {
						//Acc Pa Ade / Bu Anita
						if ($tingkatan >= 10) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Direksi'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// bukan GM ke atas tidak ada aksi
						}
					} else {
						// Bukan Middle langsung Acc HC
						if ($recid_struktur == '11' and $tingkatan >= 8) {
							$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval $r->dept_group'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
						} else {
							// Bukan akun Asman Up HC Tidak ada aksi apapun
						}
					}
				} else {
					// Bukan HC dan Pa Ade, Tidak ada tombol aksi apapun
				}
			} else if ($r->approval == "Tidak Acc Manager" or $r->approval == "Tidak Acc HC" or $r->approval == "Tolak Direksi" or $r->approval == "Tidak Acc Realisasi") {
				//tidak ada tombol aksi apapun
			} else if ($r->approval == "Acc Direksi") {
				//Acc HC Setelah Acc Direksi
				if ($recid_struktur == '11' and $tingkatan >= 8) {
					$tmb .= "<a href='" . base_url() . "Lembur/acc_lembur/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval HC'><button class='btn btn-success btn-xs'><span class='fa fa-check'></span></button></a>";
				} else {
					// Bukan akun Asman Up HC Tidak ada aksi apapun
				}
			} else if ($r->approval == "Acc HC") {
				if (($role == '1' or $tingkatan < 8) and $role != '5' and $role != '3' and $role != '25') {
					//tombol realisasi bagian
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($r->approval == "Realisasi Bagian") {
				if ($role == '1' or $tingkatan >= 8) {
					//tombol realiasi acc realisasi manager
					$tmb .= "<a href='" . base_url() . "Lembur/acc_realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Approval Realisasi'><button class='btn btn-info btn-xs'><span class='fa fa-check-square'></span></button></a>";
				} else {
					// Tidak aksi apapun
				}
			} else if ($role == '1'  or $role == '3' or $r->approval == 'Acc Realisasi Manager') {
				if ($role == '1' or $role == '3' or ($recid_struktur == '11' and $tingkatan >= 7) and $role != '25') {
					//realisasi mega
					$tmb .= "<a href='" . base_url() . "Lembur/realisasi/" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Realisasi'><button class='btn btn-warning btn-xs'><span class='fa fa-refresh'></span></button></a>";
				} else {
					// bukan mega tidak ada aksi
				}
			} else {
				// Tidak ada tombol apapun
			}


			$tmb .= "<button class='btn btn-secondary btn-xs' data-toggle='modal' data-target='#myModal' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Karyawan Lembur'><span class='fa fa-user'></span></button>";

			$cek_wf = $this->m_lembur->get_workflow($r->recid_stkl);
			if ($cek_wf->num_rows() > 0) {
				$tmb .= "<button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModal2' data-recid_stkl = '" . $r->recid_stkl . "' data-toggle='tooltip' data-placement='top' title='Work Flow'><span class='fa fa-map-signs'></span></button>";
			}

			if ($r->status == 'realisasi' or $r->status == 'selesai') {
				$tot_jam = $r->totjam_real;
			} else {
				$tot_jam = $r->total_jam;
			}

			if ($r->jemputan == '0') {
				$jemputan = 'Jemputan';
			} else if ($r->jemputan == '1') {
				$jemputan = 'Uang Transport';
			} else {
				$jemputan = $r->jemputan;
			}

			if ($r->makan == '0') {
				$makan = 'Catering';
			} else if ($r->makan == '1') {
				$makan = 'Uang Makan';
			} else {
				$makan = $r->makan;
			}

			if ($r->flag_holiday == '0') {
				$flag_holiday = 'Hari Biasa';
			} else if ($r->flag_holiday == '1') {
				$flag_holiday = 'Akhir Pekan';
			} else if ($r->flag_holiday == '2') {
				$flag_holiday = 'Libur Nasional';
			} else if ($r->flag_holiday == '3') {
				$flag_holiday = 'Libur Perusahaan';
			} else {
				$flag_holiday = $r->flag_holiday;
			}

			if ($r->status == 'pengajuan') {
				$status = "<span class='badge progress-bar-danger'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Belum Acc Manager") {
					$approval = "<span class='badge progress-bar-danger'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Manager') {
					$approval = "<span class='badge progress-bar-warning'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc HC') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Direksi') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				}
			} else if ($r->status == 'realisasi') {
				$status = "<span class='badge progress-bar-warning'>" . ucfirst($r->status) . "</span>";
				if ($r->approval == "Realisasi Bagian") {
					$approval = "<span class='badge progress-bar-info'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Acc Realisasi Manager') {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				} else if ($r->approval == 'Tidak Acc Realisasi') {
					$approval = "<span class='badge progress-bar-default'>" . ucfirst($r->approval) . "</span>";
				} else {
					$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
				}
			} else {
				$status = "<span class='badge progress-bar-success'>" . ucfirst($r->status) . "</span>";
				$approval = "<span class='badge progress-bar-success'>" . ucfirst($r->approval) . "</span>";
			}



			$get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
			$pekerjaan = "";
			$jml_kerja = $get_pekerjaan->num_rows();
			if ($jml_kerja > 0) {
				$i = 0;
				foreach ($get_pekerjaan->result() as $p) {
					$i = $i + 1;
					if ($i < $jml_kerja) {
						$pekerjaan .= $p->pekerjaan . ", ";
					} else {
						$pekerjaan .= $p->pekerjaan;
					}
				}
			}

			// $no = 0;
			if ($role == 1 or $role == 3 or $role == 5) {
				$data[] = array(
					$no = $no + 1,
					$r->recid_stkl,
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
				);
			} else {
				$data[] = array(
					$no = $no + 1,
					$tmb,
					$status,
					$approval,
					$tgl_lembur,
					$r->indeks_hr,
					$r->jam_mulai,
					$r->jam_selesai,
					$r->kategori,
					$r->jml_orang,
					$tot_jam,
					$r->klasifikasi,
					$r->tipe,
					$pekerjaan,
					$jemputan,
					$makan,
					$flag_holiday,
					$r->keterangan,
					$r->alasan_over,
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

	public function stkl_pending()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('stkl/stkl_pending', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}
}

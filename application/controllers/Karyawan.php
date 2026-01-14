<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Karyawan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_hris', 'm_training', 'm_absen', 'm_lembur'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
		$this->load->library('email');
	}

	// ========================= Setup Bagian (Bulk) =========================
	public function setup_bagian_bulk()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in != 1) {
			redirect('Auth/keluar');
			return;
		}

		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);

		// Ambil daftar bagian untuk dropdown
		$data['bagian_list'] = $this->db->select('recid_bag, nama_bag')
			->from('bagian')
			->where('is_delete', '0')
			->order_by('nama_bag', 'ASC')
			->get()
			->result();

		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('karyawan/setup_bagian_bulk_view', $data);
		$this->load->view('layout/a_footer');
	}

	// API: List karyawan aktif (untuk tabel pilih checklist)
	public function get_karyawan_aktif()
	{
		header('Content-Type: application/json');
		try {
			$q = trim($this->input->get('q') ?: '');
			$this->db->select('k.recid_karyawan, k.nik, k.nama_karyawan, k.recid_bag, b.nama_bag')
					->from('karyawan k')
					->join('bagian b', 'k.recid_bag = b.recid_bag', 'left')
					->where('k.sts_aktif', 'AKTIF')
					->order_by('k.nama_karyawan', 'ASC');
			if ($q !== '') {
				$this->db->group_start()
					->like('k.nama_karyawan', $q)
					->or_like('k.nik', $q)
					->or_like('b.nama_bag', $q)
					->group_end();
			}
			$rows = $this->db->get()->result_array();

			echo json_encode([
				'success' => true,
				'total' => count($rows),
				'data' => $rows
			], JSON_PRETTY_PRINT);
		} catch (Exception $e) {
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}

	// API: Proses bulk update bagian
	public function process_setup_bagian_bulk()
	{
		header('Content-Type: application/json');
		$ids = $this->input->post('ids'); // array of recid_karyawan
		$recid_bag = $this->input->post('recid_bag');
		if (empty($ids) || empty($recid_bag)) {
			echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
			return;
		}

		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}

		$this->db->trans_start();
		$this->db->where_in('recid_karyawan', $ids)
				->update('karyawan', [
					'recid_bag' => $recid_bag,
					'mdf_by' => $this->session->userdata('kar_id'),
					'mdf_date' => date('Y-m-d H:i:s')
				]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data karyawan']);
			return;
		}

		// Ringkasan hasil
		$updated = $this->db->affected_rows();
		$bag = $this->db->select('nama_bag')->from('bagian')->where('recid_bag', $recid_bag)->get()->row();
		echo json_encode([
			'success' => true,
			'message' => 'Berhasil memperbarui ' . $updated . ' karyawan ke bagian: ' . ($bag ? $bag->nama_bag : $recid_bag),
			'updated' => $updated
		]);
	}

	public function index()
	{
		// Gunakan path absolut agar dapat terdeteksi writable oleh helper captcha CI
		$options = array(
			'img_path'   => FCPATH . 'captcha/', // folder captcha (pastikan ada dan writable)
			'img_url'    => base_url('captcha/'), // URL ke folder captcha
			'img_width'  => 200,
			'img_height' => 100,
			'font_size'  => 30,
			'expiration' => 7200,
			'font_path'  => FCPATH . 'assets/coolvetica.ttf',
			'word_length'=> 4,
			'pool'       => '0123456789',
			'colors' => array(
				'background' => array(242, 242, 242),
				'border'     => array(255, 255, 255),
				'text'       => array(0, 0, 0),
				'grid'       => array(255, 40, 40)
			)
		);

		$cap = create_captcha($options);

		// Tangani kegagalan pembuatan captcha (menghindari warning: array offset on value of type bool)
		if ($cap === FALSE || !isset($cap['image'], $cap['word'])) {
			// Kumpulkan sedikit diagnostik agar mudah ditelusuri di lingkungan lokal (non-kritis untuk produksi)
			$gd = extension_loaded('gd') ? 'ON' : 'OFF';
			$dir_exists = is_dir($options['img_path']) ? 'YES' : 'NO';
			$dir_writable = function_exists('is_really_writable') ? (is_really_writable($options['img_path']) ? 'YES' : 'NO') : (is_writable($options['img_path']) ? 'YES' : 'NO');
			$font_ok = (file_exists($options['font_path']) ? 'YES' : 'NO');
			$hint = "[GD:$gd | DIR:$dir_exists | WRITABLE:$dir_writable | FONT:$font_ok]";
			log_message('error', 'Gagal membuat CAPTCHA. ' . $hint . ' IMG_PATH=' . $options['img_path'] . ' IMG_URL=' . $options['img_url']);
			$data['image'] = '<small class="text-danger">Captcha tidak tersedia. Hubungi admin.</small>';
			$this->session->unset_userdata('mycaptcha');
		} else {
			$data['image'] = $cap['image'];
			$this->session->set_userdata('mycaptcha', $cap['word']);
		}

		$data['word'] = $this->session->userdata('mycaptcha');
		$this->load->view('layout/a_header');
		$this->load->view('login', $data);
	}

	public function login()
	{
		$this->load->view('layout/a_header');
		$this->load->view('login');
	}

	public function dash()
	{
		$logged_in = $this->session->userdata('logged_in');
		$as_user = $this->session->userdata('as_user');
		if ($logged_in == 1 and $as_user == "CINT") {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			
			// Initialize variables with default values
			$nama = '';
			$bagian = '';
			$recid_bag = '';
			$jabatan = '';
			$tingkatan = 0; // Changed to numeric default
			$struktur = '';
			$dept_group = '';
			$department = '';
			
			// Ensure variables have default values if session is empty
			if (!$usr) {
				$usr = 0;
			}
			if (!$role) {
				$role = '0';
			}
			
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
				$department = $user->nama_department;
			}
			// echo "bagian user : " . $bagian;
			$adminnya = ''; // Initialize adminnya variable
			$admin = $this->m_absen->admin_by_bagian($recid_bag);
			if ($admin && $admin->num_rows() > 0) {
				foreach ($admin->result() as $adm) {
					$adminnya = $adm->recid_karyawan;
				}
			}
			// echo "adminnya" . $recid_bag;

			if ($role == '2' or $role == '1' or $role == '25' or $role == '24') {
				$data['training'] = $this->m_training->train_aju();
			} else {
				$data['training'] = $this->m_training->train_aju_by_bagian($bagian);
			}

			if ($role == '23' or $role == '37') {
				$recid_karyawan = $this->session->userdata('kar_id');
				if ($role == '23') {
					if (!empty($adminnya)) {
						$data['team'] = $this->m_hris->karyawan_view_by_atasan($adminnya)->num_rows();
					} else {
						$data['team'] = 0;
					}
				} else {
					if (!empty($dept_group)) {
						$data['team'] = $this->m_hris->karyawan_view_by_dept_group($dept_group)->num_rows();
					} else {
						$data['team'] = 0;
					}
				}
			}

			if ($role == '1' or $role == '3' or $role == '5' or $role == '25') {
				$query2 = $this->m_lembur->stkl_pending();
				$data['stkl_pending'] = $query2->num_rows();
			} else if ($role == '23') {
				$bag = array();
				$bagian = "b.indeks_hr = ";
				// $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
				// $bgn = $this->m_hris->bagian_view_by_atasan($usr);

				if (!empty($adminnya)) {
					$bgn = $this->m_absen->bagian_by_admin($adminnya);
					$bag = array();
					foreach ($bgn->result() as $bg) {
						array_push($bag, $bg->recid_bag);
					}
				} else {
					// If adminnya is empty, use current user bagian as fallback
					$cek_usr = $this->m_hris->cek_usr($usr);
					foreach ($cek_usr as $user) {
						$recid_bag = $user->recid_bag;
					}
					array_push($bag, $recid_bag);
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
				// echo "tingkatannya : ".$tingkatan;
				if ($tingkatan >= 8) {
					$query2 = $this->m_lembur->stkl_pending_managerbagian($bagian);
				} else {
					$query2 = $this->m_lembur->stkl_pending_admbagian($bagian);
				}
				$data['stkl_pending'] = $query2->num_rows();
				// $data['stkl_pending'] = $bagian;
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
					// echo "test";
					// print_r($bag);
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

					$query2 = $this->m_lembur->stkl_pending_admbagian($bagian);
					$data['stkl_pending'] = $query2->num_rows();
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
					$data['stkl_pending'] = $query2->num_rows();
				} else if ($role == '24') {
					$query2 = $this->m_lembur->stkl_pending_direksi($department);
					$data['stkl_pending'] = $query2->num_rows();
				} else {
					$query2 = $this->m_lembur->stkl_pending_deptgroup($department);
					$data['stkl_pending'] = $query2->num_rows();
				}
			}

			$data['nama'] 	= $nama;
			$data['pengajuan'] = $this->m_hris->jml_pengajuan($struktur);
			$data['P'] = $this->m_hris->jml_p();
			$data['L'] = $this->m_hris->jml_l();
			$data['cci'] = $this->m_hris->jml_cci();
			$data['karyawan'] = $this->m_hris->jml_karyawan();
			$data['kontrak'] = $this->m_hris->karyawan_by_status();
			$data['spm'] = $this->m_hris->jml_spm();
			$data['blm_sk'] = $this->m_hris->blm_sk();
			$data['legal'] = $this->m_hris->jml_legal();
			$data['sd'] = $this->m_hris->sd();
			$data['smp'] = $this->m_hris->smp();
			$data['sma'] = $this->m_hris->sma();
			$data['d3'] = $this->m_hris->d3();
			$data['s1'] = $this->m_hris->s1();
			$data['s2'] = $this->m_hris->s2();
			$data['recruitment'] = $this->m_hris->totopen_recruitment();
			$data['vaksin'] = $this->m_hris->vaksin_covid();
			$data['exp_legal'] = $this->m_hris->totexp_legal();
			$data['perjanjian'] = $this->m_hris->totjanji_legal();
			$data['perizinan'] = $this->m_hris->totizin_legal();
			$data['usia'] 	= $this->m_hris->range_umur();
			$data['masker'] 	= $this->m_hris->range_masker();
			$data['masuk'] 	= $this->m_hris->masuk();
			$data['keluar'] = $this->m_hris->keluar();
			$data['telat'] = $this->m_hris->telat();
			$data['belum'] = $this->m_hris->jml_blm_lengkap();
			$data['unreal'] = $this->m_hris->jml_unreal($struktur);
			$data['unreal_payroll'] = $this->m_hris->jml_unreal_payroll();
			$data['absen_tahun'] = $this->m_hris->absen_tahun();
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('layout/a_content', $data);
		} else if ($logged_in == 1 and $as_user == "Kantin") {
			redirect('Kantin');
		} else if ($logged_in == 1 and $as_user == "Outsource") {
			$alias = $this->session->userdata('recid_login');
			$data['cek_usr'] = $this->m_hris->cek_kantin($alias);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_outsource', $data);
			$this->load->view('layout/plain_page', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_by_status()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$status = $this->uri->segment(3);
			if ($status == 'Tetap') {
				$data['karyawan'] = $this->m_hris->karyawan_tetap($status);
			} else {
				$data['karyawan'] = $this->m_hris->karyawan_kontrak($status);
			}
			$data['menu'] = "Data Karyawan $status";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_pendidikan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$pendidikan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->pendidikan($pendidikan);
			$data['menu'] = "Data Karyawan Pendidikan $pendidikan";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function gender()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$gender = $this->uri->segment(3);
			if ($gender == 'Laki%20-%20laki') {
				$gender = "Laki - laki";
			}
			$data['karyawan'] = $this->m_hris->gender($gender);
			$data['menu'] = "Data Karyawan $gender";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function gender_chart()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tipe = $this->input->post('tipe_gender');
			if ($tipe == 'semua') {
				$gender =  $this->m_hris->gender_chart();
			} else if ($tipe == 'FO') {
				$gender = $this->m_hris->gender_chart_by_dept($tipe);
			} else if ($tipe == 'MO') {
				$gender = $this->m_hris->gender_chart_by_dept($tipe);
			} else {
				$gender = $this->m_hris->gender_chart_by_dept($tipe);
			}
			$data = array();
			foreach ($gender as $gchart) {
				array_push($data, $gchart->pria);
				array_push($data, $gchart->perempuan);
			}

			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function disc_chart()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$filter = $this->input->post('filter_disc');
			if ($filter == 'semua') {
				$dominan =  $this->m_hris->dominan_disc();
			} else {
				$dominan = $this->m_hris->dominan_disc_by_dept($filter);
			}
			$data = array();
			foreach ($dominan as $dchart) {
				array_push($data, $dchart->D);
				array_push($data, $dchart->I);
				array_push($data, $dchart->S);
				array_push($data, $dchart->C);
			}

			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dominan_disc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$type = $this->uri->segment(3);
			if ($type == 'not%20fill') {
				$type = "-";
				$profile = "-";
			}
			if ($type == 'D') {
				$type = $type;
				$profile = "Dominant";
			} else if ($type == 'I') {
				$type = $type;
				$profile = "Influent";
			} else if ($type == 'S') {
				$type = $type;
				$profile = "Steadlines";
			} else if ($type == 'C') {
				$type = $type;
				$profile = "Compliant";
			}
			$data['karyawan'] = $this->m_hris->dominan_disc_by_type($type);
			$data['menu'] = "Dominan DISC $profile";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function profile_type()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$data_profile = array();
			$data_jml = array();
			$profile = $this->m_hris->profile_type();
			foreach ($profile->result() as $t) {
				// $val = "{value : ".$t->jml.", name:'".$t->kompetensi."'}";
				array_push($data_profile, $t->profile_type);
				array_push($data_jml, $t->jml_type);
			}
			$data = array();
			array_push($data, $data_profile);
			array_push($data, $data_jml);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_profile_type()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$profile = $this->uri->segment(3);
			$profile = str_replace("%20", " ", $profile);
			$data['karyawan'] = $this->m_hris->profile_by_type($profile);
			$data['menu'] = "DISC PROFILE TYPE $profile";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function pendidikan_kandidat()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$pend_kan = $this->m_hris->pendidikan_kandidat();
			$edu_kan = array();
			$edu = "";
			foreach ($pend_kan->result() as $pk) {
				array_push($edu_kan, $pk->SD);
				array_push($edu_kan, $pk->SMP);
				array_push($edu_kan, $pk->SMA);
				array_push($edu_kan, $pk->D3);
				array_push($edu_kan, $pk->S1);
				array_push($edu_kan, $pk->S2);
			}
			echo json_encode($edu_kan);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function mutasi_rotasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('thn_mutasi');
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$bln_skrg = date('m');
			$bln_chart = array();
			$mutasi = array();
			$rotasi = array();
			$promosi = array();
			$data = array();
			if ($thn <> date('Y')) {
				for ($i = 0; $i < count($all_bulan); $i++) {
					array_push($bln_chart, $all_bulan[$i]);
					$mutasi1 = $this->m_hris->mutasi_by_month($i + 1, $thn);
					array_push($mutasi, $mutasi1->num_rows());
					$rotasi1 = $this->m_hris->rotasi_by_month($i + 1, $thn);
					array_push($rotasi, $rotasi1->num_rows());
					$promosi1 = $this->m_hris->promosi_by_month($i + 1, $thn);
					array_push($promosi, $promosi1->num_rows());
				}
			} else {
				for ($i = 0; $i < $bln_skrg; $i++) {
					array_push($bln_chart, $all_bulan[$i]);
					$mutasi1 = $this->m_hris->mutasi_by_month($i + 1, $thn);
					array_push($mutasi, $mutasi1->num_rows());
					$rotasi1 = $this->m_hris->rotasi_by_month($i + 1, $thn);
					array_push($rotasi, $rotasi1->num_rows());
					$promosi1 = $this->m_hris->promosi_by_month($i + 1, $thn);
					array_push($promosi, $promosi1->num_rows());
				}
			}

			array_push($data, $bln_chart);
			array_push($data, $mutasi);
			array_push($data, $rotasi);
			array_push($data, $promosi);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rotasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->rotasi_by_month($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Report Rotasi Bulan " . $batas . " Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/rotasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function mutasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->mutasi_by_month($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Report Mutasi Bulan " . $batas . " Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/rotasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function promosi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->promosi_by_month($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Report Promosi Bulan " . $batas . " Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/rotasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function turn_over()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('thn_over');
			// $thn = 2021;
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$bln_skrg = date('m');
			$bln_chart = array();
			$normk1 = array();
			$norm = array();
			$nonnorm = array();
			$data = array();
			$awal_tahun = $thn . '-01-01';

			$jan = $this->m_hris->karyawan_awal_tahun($awal_tahun)->num_rows(); // get jml karyawan per akhir bulan jan


			if ($thn <> date('Y')) {
				for ($i = 0; $i < count($all_bulan); $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					$bulann = $i + 1;
					$tgl_akhir = $thn . '-' . $bulann . '-01';
					$tgl_akhir = date("Y-m-t", strtotime($tgl_akhir));
					$tot_kar_bulan = $this->m_hris->karyawan_awal_tahun($tgl_akhir)->num_rows(); // get jml karyawan per akhir bulan berjalan
					/*----------------- CALCULATE FOR NORMA UNDER 1 YEAR---------------------*/
					$normk11 = $this->m_hris->turnover_under1($i + 1, $thn);
					$normak1 = $normk11->num_rows();
					$result_normak1 = $normak1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($normk1, round($result_normak1, 2));

					/*----------------- CALCULATE FOR NORMA ---------------------*/
					$norm1 = $this->m_hris->turnover_norm($i + 1, $thn);
					$norma1 = $norm1->num_rows();
					$result_norma1 = $norma1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($norm, round($result_norma1, 2));

					/*----------------- CALCULATE FOR NON NORMA ---------------------*/
					$nonnorm1 = $this->m_hris->turnover_nonnorm($i + 1, $thn);
					$nonnorma1 = $nonnorm1->num_rows();
					$result_nonnorma = $nonnorma1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($nonnorm, round($result_nonnorma, 2));
				}
			} else {
				for ($i = 0; $i < $bln_skrg; $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					$bulann = $i + 1;
					$tgl_akhir = $thn . '-' . $bulann . '-01';
					$tgl_akhir = date("Y-m-t", strtotime($tgl_akhir));
					$tot_kar_bulan = $this->m_hris->karyawan_awal_tahun($tgl_akhir)->num_rows(); // get jml karyawan per akhir bulan berjalan
					// echo "karyawan bulan  ".$bulann."= ".$tot_kar_bulan."<br>";
					/*----------------- CALCULATE FOR NORMA UNDER 1 YEAR---------------------*/
					$normk11 = $this->m_hris->turnover_under1($i + 1, $thn);
					$normak1 = $normk11->num_rows();
					$result_normak1 = $normak1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($normk1, round($result_normak1, 2));

					/*----------------- CALCULATE FOR NORMA ---------------------*/
					$norm1 = $this->m_hris->turnover_norm($i + 1, $thn);
					$norma1 = $norm1->num_rows();
					$result_norma1 = $norma1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($norm, round($result_norma1, 2));
					// echo "karyawan akhir jan = ".$jan."<br>";
					// echo "karyawan by norma = ".$norma1."<br>";
					// echo "hasil = ".$result_norma1."<br>-------------------------<br><br>";

					/*----------------- CALCULATE FOR NON NORMA ---------------------*/
					$nonnorm1 = $this->m_hris->turnover_nonnorm($i + 1, $thn);
					$nonnorma1 = $nonnorm1->num_rows();
					$result_nonnorma = $nonnorma1 / (($jan + $tot_kar_bulan) / 2) * 100;
					array_push($nonnorm, round($result_nonnorma, 2));
				}
			}
			array_push($data, $bln_chart);
			array_push($data, $normk1);
			array_push($data, $norm);
			array_push($data, $nonnorm);
			// print_r($norm);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function turn_over_table()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('thn_over');
			// $thn = 2021;
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$bln_skrg = date('m');
			$bln_chart = array();
			$normk1 = array();
			$norm = array();
			$nonnorm = array();
			$data = array();
			$awal_tahun = $thn . '-01-01';

			$jan = $this->m_hris->karyawan_awal_tahun($awal_tahun)->num_rows(); // get jml karyawan per akhir bulan jan


			if ($thn <> date('Y')) {
				for ($i = 0; $i < count($all_bulan); $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					$bulann = $i + 1;
					/*----------------- CALCULATE FOR NORMA UNDER 1 YEAR---------------------*/
					$normk11 = $this->m_hris->turnover_under1_bulan($i + 1, $thn);
					$normak1 = $normk11->num_rows();
					array_push($normk1, $normak1);

					/*----------------- CALCULATE FOR NORMA ---------------------*/
					$norm1 = $this->m_hris->turnover_norm_bulan($i + 1, $thn);
					$norma1 = $norm1->num_rows();
					array_push($norm, $norma1);

					/*----------------- CALCULATE FOR NON NORMA ---------------------*/
					$nonnorm1 = $this->m_hris->turnover_nonnorm_bulan($i + 1, $thn);
					$nonnorma1 = $nonnorm1->num_rows();
					array_push($nonnorm, $nonnorma1);
				}
			} else {
				for ($i = 0; $i < $bln_skrg; $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					$bulann = $i + 1;

					$normk11 = $this->m_hris->turnover_under1_bulan($i + 1, $thn);
					$normak1 = $normk11->num_rows();
					array_push($normk1, $normak1);

					/*----------------- CALCULATE FOR NORMA ---------------------*/
					$norm1 = $this->m_hris->turnover_norm_bulan($i + 1, $thn);
					$norma1 = $norm1->num_rows();
					array_push($norm, $norma1);

					/*----------------- CALCULATE FOR NON NORMA ---------------------*/
					$nonnorm1 = $this->m_hris->turnover_nonnorm_bulan($i + 1, $thn);
					$nonnorma1 = $nonnorm1->num_rows();
					array_push($nonnorm, $nonnorma1);
				}
			}
			array_push($data, $bln_chart);
			array_push($data, $normk1);
			array_push($data, $norm);
			array_push($data, $nonnorm);
			// print_r($norm);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function norm_under1()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->turnover_under1($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Turn Over By Norm (lengt of contract under 1 year) Bulan " . $batas . "Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/turn_over', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function by_norm()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->turnover_norm($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Turn Over By Norm Bulan " . $batas . "Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/turn_over', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function non_norm()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$batas = $this->uri->segment(3);
			$key = array_search($batas, $all_bulan);
			$thn = $this->uri->segment(4);
			$data['rotasi'] = $this->m_hris->turnover_nonnorm($key + 1, $thn)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['bulan']	= $batas;
			$data['tahun']	= $thn;
			$data['menu']	= "Turn Over Non Norm Bulan " . $batas . "Tahun " . $thn;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/turn_over', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function info_chart()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$info = $this->m_hris->chart_info();
			$data = array();
			foreach ($info->result() as $ichart) {
				array_push($data, $ichart->Online);
				array_push($data, $ichart->Offline);
			}
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function source()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$source = $this->uri->segment(3);
			$data['kandidat'] = $this->m_hris->source_info($source);
			$data['menu'] = "Data Kandidat $source";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function kandidat_pendidikan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$pendidikan = $this->uri->segment(3);
			$data['kandidat'] = $this->m_hris->kandidat_by_pendidikan($pendidikan);
			$data['menu'] = "Data Pendidikan Kandidat - $pendidikan";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function usia()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$batas = $this->uri->segment(3);
			if ($batas == 'kurang30') {
				$usia = '(TIMESTAMPDIFF(YEAR, k.tgl_lahir, CURDATE()) < 30)';
				$menu = "< 30";
			} else if ($batas == 'u3140') {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_lahir, CURDATE()) between 31 and 40)";
				$menu = "31 - 40";
			} else if ($batas == 'u4150') {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_lahir, CURDATE()) between 41 and 50)";
				$menu = "41 - 50";
			} else {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_lahir, CURDATE()) > 50)";
				$menu = "> 50";
			}
			$data['karyawan'] = $this->m_hris->detail_usia($usia);
			$data['menu'] = "Data Karyawan Dengan Usia $menu Tahun";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function masker()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$batas = $this->uri->segment(3);
			if ($batas == 'kurang1') {
				$usia = '(TIMESTAMPDIFF(YEAR, k.tgl_m_kerja, CURDATE()) < 1 )';
				$menu = "< 1";
			} else if ($batas == 'u15') {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_m_kerja, CURDATE()) between 1 and 5)";
				$menu = "1 - 5";
			} else if ($batas == 'u610') {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_m_kerja, CURDATE()) between 6 and 10)";
				$menu = "6 - 10";
			} else if ($batas == 'u1120') {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_m_kerja, CURDATE()) between 11 and 20)";
				$menu = "11 - 20";
			} else {
				$usia = "(TIMESTAMPDIFF(YEAR, k.tgl_m_kerja, CURDATE()) > 20)";
				$menu = "> 20";
			}
			$data['karyawan'] = $this->m_hris->detail_masker($usia);
			$data['menu'] = "Data Karyawan Dengan Masa Kerja $menu Tahun";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function inout()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = array();

		$in = $this->m_hris->masuk2($bulan, $tahun);
		foreach ($in as $ins) {
			$in = $ins->masuk;
			array_push($data, $in);
		}
		$out = $this->m_hris->keluar2($bulan, $tahun);
		foreach ($out as $outs) {
			$out = $outs->keluar;
			array_push($data, $out);
		}
		echo json_encode($data);
	}

	public function in()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->uri->segment(3);
			$tahun = $this->uri->segment(4);
			$dateObj   = DateTime::createFromFormat('!m', $bulan);
			$monthName = $dateObj->format('F');
			$data['karyawan'] = $this->m_hris->detail_masuk($bulan, $tahun);
			$data['menu'] = "Data Karyawan Masuk $monthName - $tahun";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function out()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->uri->segment(3);
			$tahun = $this->uri->segment(4);
			$dateObj   = DateTime::createFromFormat('!m', $bulan);
			$monthName = $dateObj->format('F');
			$data['karyawan'] = $this->m_hris->detail_keluar($bulan, $tahun);
			$data['menu'] = "Data Karyawan Keluar $monthName - $tahun";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dash_absen1()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$filter = $this->input->post('filter');
		$total_karyawan = $this->input->post('total_karyawan');
		if ($filter == "ALL") {
			$absen = $this->m_hris->dash_absen1($bulan, $tahun);
		} else {
			$absen = $this->m_hris->dash_absen1_filter($bulan, $tahun, $filter);
		}
		$data = array();
		foreach ($absen->result() as $d) {
			if ($d->SID == "0") {
				$sid = 0;
			} else {
				$sid = $d->SID / $total_karyawan;
			}

			if ($d->C == 0) {
				$c = 0;
			} else {
				$c = $d->C / $total_karyawan;
			}

			if ($d->P1 == 0) {
				$p1 = 0;
			} else {
				$p1 = $d->P1 / $total_karyawan;
			}

			if ($d->H1 == 0) {
				$h1 = 0;
			} else {
				$h1 = $d->H1 / $total_karyawan;
			}

			if ($d->M == 0) {
				$m = 0;
			} else {
				$m = $d->M / $total_karyawan;
			}


			array_push($data, round($sid, 3));
			array_push($data, round($c, 3));
			array_push($data, round($p1, 3));
			array_push($data, round($h1, 3));
			array_push($data, round($m, 3));
		}
		echo json_encode($data);
	}

	public function rekap_dash1_absen()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$jenis_absen = $this->uri->segment(3);
			$bulan = $this->uri->segment(4);
			$tahun = $this->uri->segment(5);
			$filter = $this->uri->segment(6);
			$filter = str_replace("%20", " ", $filter);
			if ($filter == "ALL") {
				$data['rekap'] = $this->m_hris->rekap_dash1_absen($jenis_absen, $bulan, $tahun);
			} else {
				$data['rekap'] = $this->m_hris->rekap_dash1_absen_filter($jenis_absen, $bulan, $tahun, $filter);
			}
			$dateObj   = DateTime::createFromFormat('!m', $bulan);
			$monthName = $dateObj->format('F');
			$data['karyawan'] = $this->m_hris->detail_keluar($bulan, $tahun);
			$data['menu'] = "Data Karyawan $jenis_absen $monthName - $tahun ($filter)";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/new_report/rekap_dash1_absen', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dash_absen2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('thn_absen');
			$thn = 2022;
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$bln_skrg = date('m');
			$bln_chart = array();
			$data = array();

			if ($thn <> date('Y')) {
				for ($i = 0; $i < count($all_bulan); $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					/*----------- get jml hk per bulan ---------------*/
					$bulanke = $i + 1;
					$hk = $this->m_absen->hk_by_bulan($thn, $bulanke);
					foreach ($hk->result() as $h) {
						$jml_hk = $h->jml_hk;
						$bag = "b.recid_bag != 24";
						$not_kea = $this->m_hris->karyawan_by_bagian($bag);
						$jml_emp = $not_kea->num_rows();
						$tot_hk1 = $jml_hk * $jml_emp;

						$hk_kea = $this->m_hris->hk_bulan_keamanan($thn, $bulanke);
						$tot_hk2 = $hk_kea->num_rows();
						$total_hk = $tot_hk1 + $tot_hk2;
					}
				}
			} else {
				for ($i = 0; $i < $bln_skrg; $i++) {
					array_push($bln_chart, $all_bulan[$i]); // buat nama bulan di garis x
					/*----------- get jml hk per bulan ---------------*/
					$bulanke = $i + 1;
					$hk = $this->m_absen->hk_by_bulan($thn, $bulanke);
					foreach ($hk->result() as $h) {
						$jml_hk = $h->jml_hk;
						$bag = "b.recid_bag != 24";
						$not_kea = $this->m_hris->karyawan_by_bagian($bag);
						$jml_emp = $not_kea->num_rows();
						$tot_hk1 = $jml_hk * $jml_emp;

						$hk_kea = $this->m_absen->hk_bulan_keamanan($thn, $bulanke);
						$tot_hk2 = $hk_kea->num_rows();
						$total_hk = $tot_hk1 + $tot_hk2;
						echo "bulan ke " . $bulanke . " : " . $tot_hk1 . " + " . $tot_hk2 . "<br>";
					}
				}
			}
			/*array_push($data, $bln_chart);
			array_push($data, $normk1);
			array_push($data, $norm);
			array_push($data, $nonnorm);
			// print_r($norm);
			echo json_encode($data);*/
		} else {
			redirect('Auth/keluar');
		}
	}

	public function chart_lembur_tahun()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tahun = $this->input->post('lb_tahun');
			$tipe = $this->input->post('lb_dept');
			if ($tipe == 'semua') {
				$budget =  $this->m_lembur->jml_budget_tahun($tahun);
				$real =  $this->m_lembur->jml_real_tahun($tahun);
			} else {
				$budget = $this->m_lembur->jml_budget_tahun_dept($tahun, $tipe);
				$real = $this->m_lembur->jml_real_tahun_dept($tahun, $tipe);
			}
			$data = array();
			foreach ($budget->result() as $gb) {
				array_push($data, $gb->budgets);
			}
			foreach ($real->result() as $r) {
				array_push($data, $r->realisasi);
			}
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function chart_lembur_bulan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desembmer"];
			$data = array();
			array_push($data, $bulan);
			$tahun = $this->input->post('lb_tahun');
			$dept = $this->input->post('lb_dept');
			$bbulan = array();
			$rbulan = array();
			if ($dept == 'semua') {
				for ($i = 0; $i < count($bulan); $i++) {
					$blnke = $i + 1;
					$budget =  $this->m_lembur->jml_budget_bulan($tahun, $bulan[$i]);
					foreach ($budget->result() as $gb) {
						if ($gb->budgets == null) {
							array_push($bbulan, '0');
						} else {
							array_push($bbulan, $gb->budgets);
						}
					}
					$real =  $this->m_lembur->jml_real_bulan($tahun, $blnke);
					foreach ($real->result() as $r) {
						if ($r->realisasi == null) {
							array_push($rbulan, '0');
						} else {
							array_push($rbulan, $r->realisasi);
						}
					}
				}
			} else {
				for ($i = 0; $i < count($bulan); $i++) {
					$blnke = $i + 1;
					$budget =  $this->m_lembur->jml_budget_bulan_dept($tahun, $bulan[$i], $dept);
					foreach ($budget->result() as $gb) {
						if ($gb->budgets == null) {
							array_push($bbulan, '0');
						} else {
							array_push($bbulan, $gb->budgets);
						}
					}
					$real =  $this->m_lembur->jml_real_bulan_dept($tahun, $blnke, $dept);
					foreach ($real->result() as $r) {
						if ($r->realisasi == null) {
							array_push($rbulan, '0');
						} else {
							array_push($rbulan, $r->realisasi);
						}
					}
				}
			}
			array_push($data, $bbulan);
			array_push($data, $rbulan);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}


public function user_delete($recid_karyawan)
{
    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in == 1) {
        // Langsung hapus berdasarkan recid_karyawan
        $delete_result = $this->m_hris->user_delete($recid_karyawan);
        
        if($delete_result) {
            $this->session->set_flashdata('message', 'User deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user');
        }
        
        redirect('Karyawan/user_view');
    } else {
        redirect('Auth/keluar');
    }
}

	// ################################################### USER ###################################################################
	public function user_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['user'] = $this->m_hris->user_view();
			$data['role'] = $this->m_hris->role_view();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('role/user_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function generate_user()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->list_user();
			$data['roles'] = $this->m_hris->role_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('role/generate_user', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function auto_generate()
	{
		$recid_karyawan = $this->input->post('recid_karyawan');
		$recid_role = $this->input->post('recid_role');
		$myvalue = $this->m_hris->karyawan_view_by_id($recid_karyawan)->result();
		foreach ($myvalue as $k) {
			$nama_karyawan = $k->nama_karyawan;
		}
		$uname = explode(' ', trim($nama_karyawan));
		$uname = $uname[0];
		$password = md5($uname);
		$data = array(
			'crt_by'		=> $this->session->userdata('kar_id'),
			'crt_date'		=> date('y-m-d h:i:s'),
			'recid_karyawan' => $recid_karyawan,
			'username'		=> $uname,
			'password'		=> $password,
			'recid_role'	=> $recid_role
		);
		$this->m_hris->user_pinsert($data);
		echo "ok";
	}

	public function user_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->input->post('recid_karyawan');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$pwd = md5($password);
			$recid_role = $this->input->post('recid_role');
			$data = array(
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('y-m-d h:i:s'),
				'recid_karyawan' => $recid_karyawan,
				'username'		=> $username,
				'password'		=> $pwd,
				'recid_role'	=> $recid_role
			);
			$this->m_hris->user_pinsert($data);
			redirect('Karyawan/user_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function user_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_login = $this->input->post('recid_login');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password2 = $this->input->post('password2');
			$recid_role = $this->input->post('recid_role');

			$cek = $this->m_hris->user_by_username($username);
			if ($cek->num_rows() > 0) {
				foreach ($cek->result() as $c) {
					$recid_login2 = $c->recid_login;
				}

				if ($recid_login != $recid_login2) {
?>
					<script type="text/javascript">
						alert('Gagal Mengupdate Data! Username Sudah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
					<?php
				} else {
					// Cek Histori Pwd
					$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
					if ($histori->num_rows() > 0) {
					?>
						<script type="text/javascript">
							alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
							window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
						</script>
					<?php
					} else {
						// Get Data By Id
						$cek = $this->m_hris->user_by_recid($recid_login);
						foreach ($cek as $cek) {
							$username2 = $cek->username;
							$password3 = $cek->password;
							$recid_role2 = $cek->recid_role;
						}

						$text = "";

						//Comparing All Data with New Data record
						if ($username != $username2) {
							$text = "$text, $username2 -> $username ";
						} else {
							$text = $text;
						}

						if ($password != $password2) {
							$text = "$text, password changed, ";
						} else {
							$text = $text;
						}

						if ($recid_role != $recid_role2) {
							$text = "$text $recid_role2 -> $recid_role";
						} else {
							$text = $text;
						}

						echo "$text";
						//Update Data
						$data2 = array(
							'recid_karyawan' => $recid_karyawan,
							'username'		=> $username2, // Keep original username to prevent accidental changes
							'recid_role'	=> $recid_role,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
						);
						
						// Only update username if it has actually changed
						if ($username != $username2) {
							$data2['username'] = $username;
						}
						
						if ($password <> '') {
							$data3 = array(
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('y-m-d h:i:s'),
								'tgl_ubah'		=> date('Y-m-d'),
								'recid_login'	=> $recid_login,
								'password'		=> md5($password)
							);
							$this->m_hris->insert_histori_pwd($data3);

							$password = do_hash(($password), 'md5');
							$data2['password'] = $password;
						} else {
							$data2['password'] = $password2;
						}
						$this->m_hris->user_update($data2, $recid_karyawan);
						//Insert Log
						$data2 = array(
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
							'changed'		=> $text,
							'identity'		=> $recid_login,
						);
						$this->m_hris->user_linsert($data2);
						redirect('Karyawan/user_view');
					}
				}
			} else {
				$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
				if ($histori->num_rows() > 0) {
					?>
					<script type="text/javascript">
						alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
				<?php
				} else {
					$cek = $this->m_hris->user_by_recid($recid_login);
					foreach ($cek as $cek) {
						$username2 = $cek->username;
						$password3 = $cek->password;
						$recid_role2 = $cek->recid_role;
					}

					$text = "";

					//Comparing All Data with New Data record
					if ($username != $username2) {
						$text = "$text, $username2 -> $username ";
					} else {
						$text = $text;
					}

					if ($password != $password2) {
						$text = "$text, password changed, ";
					} else {
						$text = $text;
					}

					if ($recid_role != $recid_role2) {
						$text = "$text $recid_role2 -> $recid_role";
					} else {
						$text = $text;
					}

					echo "$text";
					//Update Data
					$data2 = array(
						'recid_karyawan' => $recid_karyawan,
						'username'		=> $username2, // Keep original username to prevent accidental changes
						'recid_role'	=> $recid_role,
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s'),
					);
											
					// Only update username if it has actually changed
					if ($username != $username2) {
						$data2['username'] = $username;
					}
											
					if ($password <> '') {
						$data3 = array(
							'crt_by'		=> $this->session->userdata('kar_id'),
							'crt_date'		=> date('y-m-d h:i:s'),
							'tgl_ubah'		=> date('Y-m-d'),
							'recid_login'	=> $recid_login,
							'password'		=> md5($password)
						);
						$this->m_hris->insert_histori_pwd($data3);
					
						$password = do_hash(($password), 'md5');
						$data2['password'] = $password;
					} else {
						$data2['password'] = $password2;
					}
					$this->m_hris->user_update($data2, $recid_karyawan);
					//Insert Log
					$data2 = array(
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s'),
						'changed'		=> $text,
						'identity'		=> $recid_login,
					);
					$this->m_hris->user_linsert($data2);
					redirect('Karyawan/user_view');
				}
			}
		} else {
			redirect('Auth/keluar');
			//echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function user_change()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_login = $this->input->post('recid_login');
			// $recid_karyawan= $this->input->post('recid_karyawan');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password2 = $this->input->post('password2');

			$cek = $this->m_hris->user_by_username($username);
			if ($cek->num_rows() > 0) {
				foreach ($cek->result() as $c) {
					$recid_login2 = $c->recid_login;
				}

				if ($recid_login != $recid_login2) {
				?>
					<script type="text/javascript">
						alert('Gagal Mengupdate Data! Username Sudah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
					<?php
				} else {
					$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
					if ($histori->num_rows() > 0) {
					?>
						<script type="text/javascript">
							alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
							window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
						</script>
					<?php
					} else {
						//Get Data By Id
						$cek = $this->m_hris->user_by_recid($recid_login);
						foreach ($cek as $cek) {
							$username2 = $cek->username;
							$password3 = $cek->password;
						}

						$text = "";

						//Comparing All Data with New Data record
						if ($username != $username2) {
							$text = "$text, $username2 -> $username ";
						} else {
							$text = $text;
						}

						if ($password != $password2) {
							$text = "$text, password changed, ";
						} else {
							$text = $text;
						}

						if ($recid_role != $recid_role2) {
							$text = "$text $recid_role2 -> $recid_role";
						} else {
							$text = $text;
						}

						echo "$text";
						//Update Data
						if ($password <> '') {
							// histori pwd
							$data3 = array(
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('y-m-d h:i:s'),
								'tgl_ubah'		=> date('Y-m-d'),
								'recid_login'	=> $recid_login,
								'password'		=> md5($password)
							);
							$this->m_hris->insert_histori_pwd($data3);

							$password = do_hash(($password), 'md5');
							$data2 = array(
								'username'		=> $username,
								'password'		=> $password,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('y-m-d h:i:s'),
								'last_pwd_change'	=> date('Y-m-d')
							);
						} else {
							$data2 = array(
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('y-m-d h:i:s'),
								'username'		=> $username,
								'password'		=> $password2,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('y-m-d h:i:s'),
							);
						}
						$this->m_hris->user_update($data2, $recid_karyawan);
						//Insert Log
						$data2 = array(
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
							'changed'		=> $text,
							'identity'		=> $recid_login,
						);
						$this->m_hris->user_linsert($data2);
						redirect('Karyawan/user_view');
					}
				}
			} else {
				$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
				if ($histori->num_rows() > 0) {
					?>
					<script type="text/javascript">
						alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
				<?php
				} else {
					$cek = $this->m_hris->user_by_recid($recid_login);
					foreach ($cek as $cek) {
						$username2 = $cek->username;
						$password3 = $cek->password;
					}

					$text = "";

					//Comparing All Data with New Data record
					if ($username != $username2) {
						$text = "$text, $username2 -> $username ";
					} else {
						$text = $text;
					}

					if ($password != $password2) {
						$text = "$text, password changed, ";
					} else {
						$text = $text;
					}

					if ($recid_role != $recid_role2) {
						$text = "$text $recid_role2 -> $recid_role";
					} else {
						$text = $text;
					}

					echo "$text";
					//Update Data
					if ($password <> '') {
						// histori pwd
						$data3 = array(
							'crt_by'		=> $this->session->userdata('kar_id'),
							'crt_date'		=> date('y-m-d h:i:s'),
							'tgl_ubah'		=> date('Y-m-d'),
							'recid_login'	=> $recid_login,
							'password'		=> md5($password)
						);
						$this->m_hris->insert_histori_pwd($data3);

						$password = do_hash(($password), 'md5');
						$data2 = array(
							'username'		=> $username,
							'password'		=> $password,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
							'last_pwd_change'	=> date('Y-m-d')
						);
					} else {
						$data2 = array(
							'crt_by'		=> $this->session->userdata('kar_id'),
							'crt_date'		=> date('y-m-d h:i:s'),
							'username'		=> $username,
							'password'		=> $password2,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
						);
					}
					$this->m_hris->user_update($data2, $recid_karyawan);
					//Insert Log
					$data2 = array(
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s'),
						'changed'		=> $text,
						'identity'		=> $recid_login,
					);
					$this->m_hris->user_linsert($data2);
					redirect('Karyawan/user_view');
				}
			}
		} else {
			redirect('Auth/keluar');
			//echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function user_change2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_login = $this->input->post('recid_login');
			// $recid_karyawan= $this->input->post('recid_karyawan');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password2 = $this->input->post('password2');

			$cek = $this->m_hris->user_by_username($username);
			if ($cek->num_rows() > 0) {
				foreach ($cek->result() as $c) {
					$recid_login2 = $c->recid_login;
				}

				if ($recid_login != $recid_login2) {
				?>
					<script type="text/javascript">
						alert('Gagal Mengupdate Data! Username Sudah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
					<?php
				} else {
					$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
					if ($histori->num_rows() > 0) {
					?>
						<script type="text/javascript">
							alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
							window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
						</script>
					<?php
					} else {
						//Get Data By Id
						$cek = $this->m_hris->user_by_recid($recid_login);
						foreach ($cek as $cek) {
							$username2 = $cek->username;
							$password3 = $cek->password;
						}

						$text = "";

						//Comparing All Data with New Data record
						if ($username != $username2) {
							$text = "$text, $username2 -> $username ";
						} else {
							$text = $text;
						}

						if ($password != $password2) {
							$text = "$text, password changed, ";
						} else {
							$text = $text;
						}

						// echo "$text";	
						//Update Data
						if ($password <> '') {
							// histori pwd
							$data3 = array(
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('y-m-d h:i:s'),
								'tgl_ubah'		=> date('Y-m-d'),
								'recid_login'	=> $recid_login,
								'password'		=> md5($password)
							);
							$this->m_hris->insert_histori_pwd($data3);

							$password = do_hash(($password), 'md5');
							$data2 = array(
								'username'		=> $username,
								'password'		=> $password,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('y-m-d h:i:s'),
								'last_pwd_change'	=> date('Y-m-d')
							);
						} else {
							$data2 = array(
								'crt_by'		=> $this->session->userdata('kar_id'),
								'crt_date'		=> date('y-m-d h:i:s'),
								'username'		=> $username,
								'password'		=> $password2,
								'mdf_by'		=> $this->session->userdata('kar_id'),
								'mdf_date'		=> date('y-m-d h:i:s'),
							);
						}
						$this->m_hris->user_update($data2, $recid_karyawan);
						//Insert Log
						$data2 = array(
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
							'changed'		=> $text,
							'identity'		=> $recid_login,
						);
						$this->m_hris->user_linsert($data2);
					?>
						<script type="text/javascript">
							alert('Berhasil Merubah Akun');
							window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
						</script>
					<?php
						// redirect('Karyawan/dash');
					}
				}
			} else {
				$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
				if ($histori->num_rows() > 0) {
					?>
					<script type="text/javascript">
						alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
				<?php
				} else {
					$cek = $this->m_hris->user_by_recid($recid_login);
					foreach ($cek as $cek) {
						$username2 = $cek->username;
						$password3 = $cek->password;
					}

					$text = "";

					//Comparing All Data with New Data record
					if ($username != $username2) {
						$text = "$text, $username2 -> $username ";
					} else {
						$text = $text;
					}

					if ($password != $password2) {
						$text = "$text, password changed, ";
					} else {
						$text = $text;
					}

					echo "$text";
					//Update Data
					if ($password <> '') {
						// histori pwd
						$data3 = array(
							'crt_by'		=> $this->session->userdata('kar_id'),
							'crt_date'		=> date('y-m-d h:i:s'),
							'tgl_ubah'		=> date('Y-m-d'),
							'recid_login'	=> $recid_login,
							'password'		=> md5($password)
						);
						$this->m_hris->insert_histori_pwd($data3);

						$password = do_hash(($password), 'md5');
						$data2 = array(
							'username'		=> $username,
							'password'		=> $password,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
							'last_pwd_change'	=> date('Y-m-d')
						);
					} else {
						$data2 = array(
							'crt_by'		=> $this->session->userdata('kar_id'),
							'crt_date'		=> date('y-m-d h:i:s'),
							'username'		=> $username,
							'password'		=> $password2,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s'),
						);
					}
					$this->m_hris->user_update($data2, $recid_karyawan);
					//Insert Log
					$data2 = array(
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s'),
						'changed'		=> $text,
						'identity'		=> $recid_login,
					);
					$this->m_hris->user_linsert($data2);

				?>
					<script type="text/javascript">
						alert('Berhasil Merubah Akun');
						window.location.href = "<?php echo base_url(); ?>Karyawan/dash";
					</script>
<?php
				}
			}
		} else {
			redirect('Auth/keluar');
			//echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function cek_uname()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$uname = $this->input->post('username');
			$cek = $this->m_hris->cek_uname($uname);
			if ($cek == '1') {
				echo json_encode('1');
			} else {
				echo json_encode('0');
			}
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	// ################################################### ROLE ###################################################################
	public function role_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$data['role'] = $this->m_hris->role_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('role/role_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			// redirect('Auth/keluar');
			echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function role_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_role = $this->input->post('nama_role');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('y-m-d h:i:s'),
				'nama_role'		=> $nama_role,
				'note'			=> $note,
			);
			$this->m_hris->role_pinsert($data);
			redirect('Karyawan/role_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function role_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_role = $this->input->post('recid_role');
			$nama_role = $this->input->post('nama_role');
			$note = $this->input->post('note');
			//Get Data By Id
			$cek = $this->m_hris->role_by_recid($recid_role);
			foreach ($cek as $cek) {
				$nama_role2 = $cek->nama_role;
				$note2 = $cek->note;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($nama_role != $nama_role2) {
				$text = "$nama_role2 - $nama_role, ";
			} else {
				$text = $text;
			}
			if ($note != $note2) {
				$text = "$text $note2 - $note";
			} else {
				$text = $text;
			}
			echo $text;
			//Update data
			$data1 = array(
				'nama_role'		=> $nama_role,
				'note'			=> $note,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->role_update($data1, $recid_role);
			//Insert Log
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_role
			);
			$this->m_hris->role_linsert($data2);
			redirect('Karyawan/role_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### BAGIAN ###################################################################
	public function bagian_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->all_bagian();
			$data['struktur'] = $this->m_hris->struktur_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['department'] = $this->m_hris->department_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/bagian_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function bagian_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$indeks_hr = $this->input->post('indeks_hr');
			$kode_bag = $this->input->post('kode_bag');
			$nama_bag = $this->input->post('nama_bag');
			$department = '-';
			$recid_department = $this->input->post('recid_department');
			$pic_bagian = $this->input->post('pic_bagian');
			$dept_group = $this->input->post('dept_group');
			$recid_struktur = $this->input->post('recid_struktur');
			$shift = $this->input->post('shift');
			$pay_group = $this->input->post('pay_group');
			$gl_acc = $this->input->post('gl_acc');
			$cost_center = $this->input->post('cost_center');
			$pay_group = $this->input->post('pay_group');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('y-m-d h:i:s'),
				'indeks_hr'		=> $indeks_hr,
				'kode_bag'		=> $kode_bag,
				'nama_bag'		=> $nama_bag,
				'recid_department'	=> $recid_department,
				'pic_bagian'	=> $pic_bagian,
				'recid_struktur'	=> $recid_struktur,
				'pay_group'		=> $pay_group,
				'gl_acc'		=> $gl_acc,
				'cost_center'	=> $cost_center,
				'shift'			=> $shift,
				'note'			=> $note,
			);
			// echo "$department";
			$this->m_hris->bagian_pinsert($data);
			redirect('Karyawan/bagian_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function bagian_update()
	{
		$recid = $this->uri->segment(3);
		$data['bagian'] = $this->m_hris->bagian_by_recid($recid);
		$data['struktur'] = $this->m_hris->struktur_view();
		$data['karyawan'] = $this->m_hris->karyawan_view();
		$data['department'] = $this->m_hris->department_view();


		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('karyawan/bagian_update', $data);
		$this->load->view('layout/a_footer');
	}

	public function bagian_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$indeks_hr = $this->input->post('indeks_hr');
			$recid_bag = $this->input->post('recid_bag');
			$nama_bag = $this->input->post('nama_bag');
			$kode_bag = $this->input->post('kode_bag');
			$department = $this->input->post('department');
			$recid_department = $this->input->post('recid_department');
			$pic_bagian = $this->input->post('pic_bagian');
			$recid_struktur = $this->input->post('recid_struktur');
			$shift = $this->input->post('shift');
			$pay_group = $this->input->post('pay_group');
			$gl_acc = $this->input->post('gl_acc');
			$cost_center = $this->input->post('cost_center');
			$note = $this->input->post('note');
			// echo "id = $recid_bag";
			//Get Data By Id
			$cek = $this->m_hris->bagian_by_recid($recid_bag);
			foreach ($cek as $cek) {
				$nama_bag2 = $cek->nama_bag;
				$note2 = $cek->note;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($nama_bag != $nama_bag2) {
				$text = "$nama_bag2 - $nama_bag, ";
			} else {
				$text = $text;
			}
			if ($note != $note2) {
				$text = "$text $note2 - $note";
			} else {
				$text = $text;
			}
			//Update data
			$data1 = array(
				'indeks_hr'		=> $indeks_hr,
				'kode_bag'		=> $kode_bag,
				'nama_bag'		=> $nama_bag,
				'recid_department'		=> $recid_department,
				'pic_bagian'		=> $pic_bagian,
				'recid_struktur'	=> $recid_struktur,
				'pay_group'		=> $pay_group,
				'shift'			=> $shift,
				'note'			=> $note,
				'gl_acc'		=> $gl_acc,
				'cost_center'		=> $cost_center,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->bagian_update($data1, $recid_bag);
			//Insert Log
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_bag
			);
			$this->m_hris->bagian_linsert($data2);
			redirect('Karyawan/bagian_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function bagian_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_bag = $this->uri->segment(3);
			$data1 = array(
				'is_delete'		=> '1',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->bagian_update($data1, $recid_bag);
			//Insert Log
			$text = "Tidak Terpakai";
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_bag
			);
			$this->m_hris->bagian_linsert($data2);
			redirect('Karyawan/bagian_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function bagian_active()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_bag = $this->uri->segment(3);
			$data1 = array(
				'is_delete'		=> '0',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->bagian_update($data1, $recid_bag);
			//Insert Log
			$text = "Active Lagi";
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_bag
			);
			$this->m_hris->bagian_linsert($data2);
			redirect('Karyawan/bagian_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dept_by_divisi()
	{
		$divisi = $this->input->post('divisi');
		// $divisi = ["Front Office", "Back office"];
		if (!empty($divisi)) {
			$cnt = count($divisi);
			if ($cnt == 1) {
				$where = "d.dept_group = '$divisi[0]'";
			} else {
				$where = '';
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$where .= "d.dept_group = '$divisi[$i]'";
					} else {
						$where .= "d.dept_group = '$divisi[$i]' or ";
					}
				}
			}
			// echo $where;
			$dept = $this->m_hris->dept_by_divisi($where)->result();
			$lists = "";
			foreach ($dept as $data) {
				$lists .= "<option value='" . $data->nama_department . "'>" . $data->nama_department . "</option>"; // Tambahkan tag option ke variabel $lists
			}

			$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function dept_by_paygroup()
	{
		$paygroup = $this->input->post('divisi');
		// $divisi = ["Front Office", "Back office"];
		if (!empty($paygroup)) {
			$cnt = count($paygroup);
			if ($cnt == 1) {
				$where = "b.pay_group = '$paygroup[0]'";
			} else {
				$where = '';
				for ($i = 0; $i < $cnt; $i++) {
					if ($i == $cnt - 1) {
						$where .= "b.pay_group = '$paygroup[$i]'";
					} else {
						$where .= "b.pay_group = '$paygroup[$i]' or ";
					}
				}
			}
			// echo $where;
			$dept = $this->m_hris->dept_by_divisi($where)->result();
			$lists = "";
			foreach ($dept as $data) {
				$lists .= "<option value='" . $data->nama_department . "'>" . $data->nama_department . "</option>"; // Tambahkan tag option ke variabel $lists
			}

			$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function dept_by_divisi_my()
	{
		$where = "(d.recid_department =";
		$recid_karyawan = $this->session->userdata('kar_id');
		$role = $this->session->userdata('role_id');
		if ($role == "30") {
			$dpt = $this->m_absen->dept_admin($recid_karyawan);
		} else if ($role == '26') {
			$dpt = $this->m_absen->dept_spm();
		} else {
			$dpt = $this->m_absen->dept_user($recid_karyawan);
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

		$dept = $this->m_hris->dept_by_divisi_my($where)->result();
		$lists = "";
		foreach ($dept as $data) {
			$lists .= "<option value='" . $data->nama_department . "'>" . $data->nama_department . "</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function bagian_by_dept()
	{
		$departement = $this->input->post('departement');
		$divisi = $this->input->post('divisi');
		// $departement = ['1. ADMINISTRASI & KEUANGAN'];
		// $divisi = ['Back Office'];
		if (!empty($departement)) {
			$cnt_div = count($divisi);
			$cnt_dept = count($departement);
			$where_div = '';
			if ($cnt_div == 1) {
				$where_div  = "d.dept_group = '$divisi[0]'";
			} else {
				for ($i = 0; $i < $cnt_div; $i++) {
					if ($i == $cnt_div - 1) {
						$where_div .= "d.dept_group = '$divisi[$i]'";
					} else {
						$where_div .= "d.dept_group = '$divisi[$i]' or ";
					}
				}
			}

			$where_dept = '';
			if ($cnt_dept == 1) {
				$where_dept  = "d.nama_department = '$departement[0]'";
			} else {
				for ($i = 0; $i < $cnt_dept; $i++) {
					if ($i == $cnt_dept - 1) {
						$where_dept .= "d.nama_department = '$departement[$i]'";
					} else {
						$where_dept .= "d.nama_department = '$departement[$i]' or ";
					}
				}
			}
			$bagian = $this->m_hris->bagian_by_department($where_dept, $where_div)->result();
			$lists2 = "";
			foreach ($bagian as $data) {
				$lists2 .= "<option value='" . $data->recid_bag . "'>" . $data->indeks_hr . "<small>( " . strtolower($data->nama_bag) . " )</small></option>";
			}

			$callback = array('list_bagian' => $lists2); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function bagian_by_payroll()
	{
		$departement = $this->input->post('departement');
		$paygroup = $this->input->post('divisi');
		// $departement = ['1. ADMINISTRASI & KEUANGAN'];
		// $divisi = ['Back Office'];
		if (!empty($departement)) {
			$cnt_div = count($paygroup);
			$cnt_dept = count($departement);
			$where_paygroup = '';
			if ($cnt_div == 1) {
				$where_paygroup  = "b.pay_group = '$paygroup[0]'";
			} else {
				for ($i = 0; $i < $cnt_div; $i++) {
					if ($i == $cnt_div - 1) {
						$where_paygroup .= "b.pay_group = '$paygroup[$i]'";
					} else {
						$where_paygroup .= "b.pay_group = '$paygroup[$i]' or ";
					}
				}
			}

			$where_dept = '';
			if ($cnt_dept == 1) {
				$where_dept  = "d.nama_department = '$departement[0]'";
			} else {
				for ($i = 0; $i < $cnt_dept; $i++) {
					if ($i == $cnt_dept - 1) {
						$where_dept .= "d.nama_department = '$departement[$i]'";
					} else {
						$where_dept .= "d.nama_department = '$departement[$i]' or ";
					}
				}
			}
			$bagian = $this->m_hris->bagian_by_department2($where_dept, $where_paygroup)->result();
			$lists2 = "";
			foreach ($bagian as $data) {
				$lists2 .= "<option value='" . $data->recid_bag . "'>" . $data->indeks_hr . "<small>( " . strtolower($data->nama_bag) . " )</small></option>";
			}

			$callback = array('list_bagian' => $lists2); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function karyawan_by_bagian()
	{
		$role = $this->session->userdata('role_id');
		$bagian = $this->input->post('bagian');
		if (!empty($bagian)) {
			$cnt_bag = count($bagian);
			$where_bag = '';
			if ($cnt_bag == 1) {
				$where_bag  = "b.recid_bag = '$bagian[0]'";
			} else {
				for ($i = 0; $i < $cnt_bag; $i++) {
					if ($i == $cnt_bag - 1) {
						$where_bag .= "b.recid_bag = '$bagian[$i]'";
					} else {
						$where_bag .= "b.recid_bag = '$bagian[$i]' or ";
					}
				}
			}

			if ($role == '26') {
				$bagian = $this->m_hris->karyawan_spm($where_bag)->result();
			} else {
				$bagian = $this->m_hris->karyawan_by_bagian($where_bag)->result();
			}
			$lists3 = "";
			foreach ($bagian as $data) {
				$lists3 .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . "<small>( " . strtolower($data->nik) . " )</small></option>";
			}

			$callback = array('list_karyawan' => $lists3); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function karyawanoffdown_by_bagian()
	{
		$role = $this->session->userdata('role_id');
		$bagian = $this->input->post('bagian');
		if (!empty($bagian)) {
			$cnt_bag = count($bagian);
			$where_bag = '';
			if ($cnt_bag == 1) {
				$where_bag  = "b.recid_bag = '$bagian[0]'";
			} else {
				for ($i = 0; $i < $cnt_bag; $i++) {
					if ($i == $cnt_bag - 1) {
						$where_bag .= "b.recid_bag = '$bagian[$i]'";
					} else {
						$where_bag .= "b.recid_bag = '$bagian[$i]' or ";
					}
				}
			}

			if ($role == '26') {
				$bagian = $this->m_hris->karyawanoffdown_spm($where_bag)->result();
			} else {
				$bagian = $this->m_hris->karyawanoffdown_by_bagian($where_bag)->result();
			}
			$lists3 = "";
			foreach ($bagian as $data) {
				$lists3 .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . "<small>( " . strtolower($data->nik) . " )</small></option>";
			}

			$callback = array('list_karyawan' => $lists3); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function karyawan_by_bagian_my()
	{
		$bagian = $this->input->post('bagian');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		if (!empty($bagian)) {
			$cnt_bag = count($bagian);
			$where_bag = '';
			if ($cnt_bag == 1) {
				$where_bag  = "b.recid_bag = '$bagian[0]'";
			} else {
				for ($i = 0; $i < $cnt_bag; $i++) {
					if ($i == $cnt_bag - 1) {
						$where_bag .= "b.recid_bag = '$bagian[$i]'";
					} else {
						$where_bag .= "b.recid_bag = '$bagian[$i]' or ";
					}
				}
			}

			$bagian = $this->m_hris->karyawan_by_bagian_active_my($where_bag, $bulan, $tahun)->result();
			$lists3 = "";
			foreach ($bagian as $data) {
				$lists3 .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . "<small>( " . strtolower($data->nik) . " )</small></option>";
			}

			$callback = array('list_karyawan' => $lists3); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
			echo json_encode($callback); // konversi varibael $callback menjadi JSON
		} else {
			echo json_encode('');
		}
	}

	public function bagian_by_dept_my()
	{
		$role = $this->session->userdata('role_id');
		$usr = $this->session->userdata('kar_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
			$dept_group = $user->dept_group;
		}
		$bagian = "(b.indeks_hr =";
		$recid_karyawan = $this->session->userdata('kar_id');
		$bag = array();
		if ($role == '30') {
			$bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}
		} else if ($role == '26') {
			$bgn = $this->m_absen->bagian_spm();
			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}
		} else if ($role == '37') {
			$bgn = $this->m_absen->bagian_by_dept_group($dept_group);
			foreach ($bgn->result() as $bg) {
				array_push($bag, $bg->recid_bag);
			}
		} else {
			if ($tingkatan > '7') {
				$bgn = $this->m_hris->karyawan_view_by_atasan($usr);
				foreach ($bgn->result() as $bg) {
					if (array_key_exists($bg->recid_bag, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->recid_bag);
					}
				}
			} else {
				$bgn = $this->m_hris->karyawan_view_by_id($usr);
				foreach ($bgn->result() as $bg) {
					if (array_key_exists($bg->recid_bag, $bag)) {
						// echo "Key exists!";
					} else {
						array_push($bag, $bg->recid_bag);
					}
				}
			}
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
		$bgn = $this->m_hris->bagian_by_admin($bagian)->result();
		$lists2 = "";
		foreach ($bgn as $data) {
			$lists2 .= "<option value='" . $data->recid_bag . "'>" . $data->indeks_hr . "<small>( " . strtolower($data->nama_bag) . " )</small></option>";
		}

		$callback = array('list_bagian' => $lists2); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
		echo json_encode($callback); // konversi varibael $callback menjadi JSON
		// } else {
		// 	echo json_encode('');
		// }
	}


	public function struktur_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['struktur'] = $this->m_hris->all_struktur();
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/struktur_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function struktur_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_struktur = $this->input->post('nama_struktur');
			$note = $this->input->post('note');
			$pic_struktur = $this->input->post('pic_struktur');
			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'nama_struktur'		=> $nama_struktur,
				'note'				=> $note,
				'pic_struktur'		=> $pic_struktur,
			);
			// echo "$department";
			$this->m_hris->struktur_pinsert($data);
			redirect('Karyawan/struktur_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function str_update()
	{
		$recid = $this->uri->segment(3);
		$data['struktur'] = $this->m_hris->struktur_by_recid($recid);
		$data['karyawan'] = $this->m_hris->karyawan_view($recid);
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('karyawan/struktur_update', $data);
		$this->load->view('layout/a_footer');
	}

	public function struktur_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_struktur = $this->input->post('recid_struktur');
			$nama_struktur = $this->input->post('nama_str');
			$note = $this->input->post('note');
			$pic_struktur = $this->input->post('pic_struktur');

			//Update data
			$data1 = array(
				'nama_struktur'	=> $nama_struktur,
				'note'			=> $note,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'pic_struktur'		=> $pic_struktur,
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->struktur_update($data1, $recid_struktur);
			redirect('Karyawan/struktur_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function struktur_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_struktur = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'sis_delete'	=> 1,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->struktur_update($data1, $recid_struktur);
			redirect('Karyawan/struktur_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function struktur_active()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_struktur = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'sis_delete'	=> 0,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->struktur_update($data1, $recid_struktur);
			redirect('Karyawan/struktur_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}


	public function department_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['dept'] = $this->m_hris->all_department();
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/department_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dept_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_department = $this->input->post('nama_department');
			$pic_dept = $this->input->post('pic_dept');
			$dept_group = $this->input->post('dept_group');
			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'nama_department'		=> $nama_department,
				'dept_group'		=> $dept_group,
				'pic_dept'		=> $pic_dept,
			);
			// echo "$department";
			$this->m_hris->dept_pinsert($data);
			redirect('Karyawan/department_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function department_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_department = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'		=> '1',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->department_update($data1, $recid_department);
			redirect('Karyawan/department_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function department_active()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_department = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'		=> '0',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->department_update($data1, $recid_department);
			redirect('Karyawan/department_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function dept_update()
	{
		$recid = $this->uri->segment(3);
		$data['dept'] = $this->m_hris->department_by_recid($recid);
		$data['karyawan'] = $this->m_hris->karyawan_view($recid);
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('karyawan/department_update', $data);
		$this->load->view('layout/a_footer');
	}

	public function department_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_department = $this->input->post('recid_department');
			$nama_department = $this->input->post('nama_department');
			$pic_dept = $this->input->post('pic_dept');
			$dept_group = $this->input->post('dept_group');

			//Update data
			$data1 = array(
				'nama_department'	=> $nama_department,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'dept_group'	=> $dept_group,
				'pic_dept'		=> $pic_dept,
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->department_update($data1, $recid_department);
			redirect('Karyawan/department_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	// ################################################### JABATAN ###################################################################
	public function jabatan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['jabatan'] = $this->m_hris->all_jabatan();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/jabatan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			// 
			redirect('Auth/keluar');
		}
	}

	public function jabatan_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$indeks_jabatan = $this->input->post('indeks_jabatan');
			$nama_jbtn = $this->input->post('nama_jbtn');
			$tingkatan = $this->input->post('tingkatan');
			$sts_jbtn = $this->input->post('sts_jbtn');
			$top = $this->input->post('top');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('y-m-d h:i:s'),
				'indeks_jabatan'		=> $indeks_jabatan,
				'nama_jbtn'		=> $nama_jbtn,
				'sts_jabatan'		=> $sts_jbtn,
				'tingkatan'		=> $tingkatan,
				'top'			=> $top,
				'note'			=> $note,
			);
			$this->m_hris->jabatan_pinsert($data);
			redirect('Karyawan/jabatan_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function jabatan_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_jbtn = $this->input->post('recid_jbtn');
			$indeks_jabatan = $this->input->post('indeks_jabatan');
			$nama_jbtn = $this->input->post('nama_jbtn');
			$tingkatan = $this->input->post('tingkatan');
			$top = $this->input->post('top');
			$note = $this->input->post('note');
			echo $top;
			// //Get Data By Id
			$cek = $this->m_hris->jabatan_by_recid($recid_jbtn);
			foreach ($cek as $cek) {
				$nama_jbtn2 = $cek->nama_jbtn;
				$tingakatan2 = $cek->tingkatan;
				$note2 = $cek->note;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($nama_jbtn != $nama_jbtn2) {
				$text = "$text, $nama_jbtn2 -> $nama_jbtn, ";
			} else {
				$text = $text;
			}

			if ($tingkatan != $tingakatan2) {
				$text = "$text, $tingakatan2 -> $tingkatan, ";
			} else {
				$text = $text;
			}

			if ($note != $note2) {
				$text = "$text $note2 -> $note";
			} else {
				$text = $text;
			}

			// echo "$text";
			//Update Data
			$data1 = array(
				'indeks_jabatan'		=> $indeks_jabatan,
				'nama_jbtn'		=> $nama_jbtn,
				'tingkatan'		=> $tingkatan,
				'top'			=> $top,
				'note'			=> $note,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->jabatan_update($data1, $recid_jbtn);
			//Insert Log
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_jbtn
			);
			$this->m_hris->jabatan_linsert($data2);
			redirect('Karyawan/jabatan_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function jabatan_active()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_jbtn = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'	=> '0',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->jabatan_update($data1, $recid_jbtn);
			redirect('Karyawan/jabatan_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function jabatan_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_jbtn = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'	=> '1',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->jabatan_update($data1, $recid_jbtn);
			redirect('Karyawan/jabatan_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function golongan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['golongan'] = $this->m_hris->all_golongan();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/golongan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			// 
			redirect('Auth/keluar');
		}
	}

	public function golongan_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_golongan = $this->input->post('nama_golongan');
			$masa_kerja = $this->input->post('masa_kerja');
			$klasifikasi = $this->input->post('klasifikasi');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'		=> $this->session->userdata('kar_id'),
				'crt_date'		=> date('y-m-d h:i:s'),
				'nama_golongan'		=> $nama_golongan,
				'masa_kerja'		=> $masa_kerja,
				'klasifikasi'		=> $klasifikasi,
				'note'			=> $note,
			);
			$this->m_hris->golongan_pinsert($data);
			redirect('Karyawan/golongan_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function golongan_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_golongan = $this->input->post('recid_golongan');
			$nama_golongan = $this->input->post('nama_golongan');
			$masa_kerja = $this->input->post('masa_kerja');
			$klasifikasi = $this->input->post('klasifikasi');
			$note = $this->input->post('note');


			$data1 = array(
				'nama_golongan'		=> $nama_golongan,
				'masa_kerja'		=> $masa_kerja,
				'klasifikasi'		=> $klasifikasi,
				'note'			=> $note,
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->golongan_update($data1, $recid_golongan);

			redirect('Karyawan/golongan_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function golongan_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_golongan = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'	=> '1',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->golongan_update($data1, $recid_golongan);
			redirect('Karyawan/golongan_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function golongan_active()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_golongan = $this->uri->segment(3);

			//Update data
			$data1 = array(
				'is_delete'	=> '0',
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
			);
			$this->m_hris->golongan_update($data1, $recid_golongan);
			redirect('Karyawan/golongan_view');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	// ################################################### KARYAWAN ###################################################################
	public function cek_spm()
	{
		$id = $this->input->post('id');
		// $id = 1189;
		$karyawan = $this->m_hris->karyawan_by_recid($id);
		foreach ($karyawan as $spm) {
			$spm = $spm->spm;
		}
		if ($spm == 'Ya') {
			$last = $this->m_hris->last_nik3();
		} else {
			$last = $this->m_hris->last_nik2();
		}
		foreach ($last as $id) {
			$nik = $id->nik;
		}
		if ($nik >= 999) {
			$nik = 0;
		}
		$count = $nik + 1;
		if ($count < 10) {
			$count = "00$count";
		} else if ($count < 100 && $count >= 10) {
			$count = "0$count";
		} else {
			$count;
		}
		// echo $count;
		// $data['count'] = $count;
		$data = array();
		$a = array($spm, $count);
		array_push($data, $a);
		echo json_encode($data);
	}


	public function karyawan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_viewbeta()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			
			// Initialize variables with default values
			$nama = '';
			$bagian = '';
			$jabatan = '';
			$tingkatan = 0;
			$struktur = '';
			$dept_group = '';
			
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}
			if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '31' or $role == '34') {
				$data['karyawan'] = $this->m_hris->karyawan_views();
			} else if ($role == '7' or  $role == '23' or $role == '35' or $role == '38' or $role == '41') {
				if ($tingkatan > '7') {
					if ($role == '41') {
						//custom role all produksi, data = pic struktur produksi (pa dadan 920)
						// $data['karyawan'] = $this->m_hris->prd_karyawan_by_atasan('920')->result();
						$data['karyawan'] = $this->m_hris->prd_karyawan_by_atasan($usr)->result();
					} else {
						$data['karyawan'] = $this->m_hris->karyawan_view_by_atasan($usr)->result();
					}
				} else {
					$data['karyawan'] = $this->m_hris->karyawan_view_by_id($usr)->result();
				}
			} else if ($role == '37') { //GM DEPT GROUP (BO, FO, MO)
				$data['karyawan'] = $this->m_hris->karyawan_view_by_dept_group($dept_group)->result();
			} else if ($role == '26') {
				$data['karyawan'] = $this->m_hris->spm_view();
			} else {

				$data['karyawan'] = $this->m_hris->karyawan_view_by_bagian($bagian);
				$data['temp_kary'] = $this->m_hris->temp_karyawan_view_by_id($usr);
			}
			//echo $bagian
			$notif = $this->m_hris->temp_karyawan_views();
			$notif = $notif->num_rows();
			$data['notif_edit'] = $notif;
			$data['tingkatan'] = $tingkatan;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_viewbeta', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_listupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$cek_usr = $this->m_hris->cek_usr($usr);
			
			// Initialize variables with default values
			$nama = '';
			$bagian = '';
			$jabatan = '';
			$tingkatan = 0;
			$struktur = '';
			
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['temp_kary'] = $this->m_hris->temp_karyawan_views()->result();
			$notif = $this->m_hris->temp_karyawan_views();
			$notif = $notif->num_rows();
			$data['notif_edit'] = $notif;
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_listupdate', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function listKaryawan()
	{
		// Ambil data ID Provinsi yang dikirim via ajax post
		$recid_bag = $this->input->post('recid_bag');

		$karyawan = $this->m_hris->v_karyawan_bagian($recid_bag)->result();

		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";

		foreach ($karyawan as $data) {
			$lists .= "<option value='" . $data->nik . "'>" . $data->nama_karyawan . "</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function karyawan_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['karir'] = $this->m_hris->karir_history($recid_karyawan);
			$data['training'] = $this->m_hris->training_history($recid_karyawan);
			$data['sanksi'] = $this->m_hris->sanksi_history($recid_karyawan);
			$data['karirs'] = $this->m_hris->karirs_history($recid_karyawan);
			$data['tunjangan'] = $this->m_hris->tunjangan_history($recid_karyawan);
			// Load contract data
			$this->load->model('M_kontrak');
			$data['kontrak'] = $this->M_kontrak->get_kontrak_by_karyawan($recid_karyawan);
			$data['usia'] = $this->m_hris->umur($recid_karyawan);
			$rp_masker = $this->m_hris->param_upah_id(1);
			foreach ($rp_masker->result() as $r) {
				$uph = $r->nilai;
			}
			$data['uph_masker'] = $uph;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	/*public function atasan_karyawan()
{
	$logged_in = $this->session->userdata('logged_in');
	if($logged_in == 1)
	{
		$role = $this->session->userdata('role_id');
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
		}
		
		$data['karyawan'] = $this->m_hris->karyawan_view();
		$data['atasan_k'] = $this->m_hris->karyawan_view();
		$notif = $this->m_hris->temp_karyawan_views();
		$notif = $notif->num_rows();
		$data['notif_edit'] = $notif;
		$data['tingkatan'] = $tingkatan;
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('karyawan/karyawan/karyawan_atasan',$data);
		$this->load->view('layout/a_footer');
	}
	else
	{
		redirect('Auth/keluar');
	}
}*/

	/*public function change_atasan()
	{
		$recid_karyawan = $this->input->post('recid_karyawan');	
		$recid_atasan= $this->input->post('recid_atasan');	
		$data1 = array(
			'recid_atasan'	=> $recid_atasan,
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('y-m-d h:i:s'),
		);
		$this->m_hris->karyawan_update($data1, $recid_karyawan);
		echo json_encode("ok");
	}*/

	public function cek_data()
	{
		$recid_karyawan = $this->input->post('id');
		$data = $this->m_hris->karyawan_view_by_id($recid_karyawan)->result();
		$hasil = array();
		foreach ($data as $emp) {
			array_push($hasil, $emp->recid_bag);
			array_push($hasil, $emp->recid_jbtn);
			array_push($hasil, $emp->recid_subbag);
			array_push($hasil, $emp->recid_golongan);
		}
		echo json_encode($hasil);
	}

	public function download()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['usia'] = $this->m_hris->umur($recid_karyawan);

			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download1', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLHeader('
			<div style="text-align: right; font-weight: bold;">
			Personal Data
			</div>');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} -
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			echo $html;
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function download2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['usia'] = $this->m_hris->umur($recid_karyawan);

			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download2', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} <br>
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';

			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			// echo $html;
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function download3()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['tunjangan'] = $this->m_hris->tunjangan_detail($recid_karyawan);

			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download3', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} <br>
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';
			// echo $html;
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function download4()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['karir'] = $this->m_hris->karir_history($recid_karyawan);
			$data['karirs'] = $this->m_hris->karirs_history($recid_karyawan);
			$data['tunjangan'] = $this->m_hris->tunjangan_history($recid_karyawan);

			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download4', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} <br>
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';
			// echo $html;
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function download5()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['karir'] = $this->m_hris->karir_history($recid_karyawan);
			$data['karirs'] = $this->m_hris->karirs_history($recid_karyawan);
			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download5', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} <br>
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			// echo $html;
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}


	public function download6()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_detail($recid_karyawan);
			$nik = $this->m_hris->nik_karyawan($recid_karyawan);
			foreach ($nik as $nik_karyawan) {
				$nik = $nik_karyawan->nik;
			}
			$data['training'] = $this->m_hris->training_history($recid_karyawan);
			$data['karirs'] = $this->m_hris->karirs_history($recid_karyawan);
			$mpdf = new \Mpdf\Mpdf();
			$html = $this->load->view('download/download6', $data, true);
			$tgl = date('d M y');
			$mpdf->SetHTMLFooter('
			<div style="text-align: center; font-size:7pt; font-color : #ccc;">
			printed : {DATE j-M-Y} <br>
			©Chitose Internasioal Tbk.
			</div>');
			$stylesheet = '<style>' . file_get_contents('assets/build/css/mpdfstyletables.css') . '</style>';
			$stylesheet = '<style>' . file_get_contents('assets/vendors/bootstrap/dist/css/bootstrap.min.css') . '</style>';
			// echo $html;
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			ob_clean();
			$mpdf->Output();
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function karyawan_bagian()
	{
		// Ambil data ID bagian yang dikirim via ajax post
		$bagian = $this->input->post('recid_bag');
		$nama = $this->m_hris->v_karyawan_bagian($bagian)->result();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";
		foreach ($nama as $data) {
			$lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function nik_karyawan()
	{
		$recid = $this->input->post('recid_karyawan');
		$nik = $this->m_hris->nik_karyawan($recid)->result();
		$nik_karyawan = '';
		foreach ($nik as $nik) {
			$nik_karyawan = $nik->nik;
		}
		echo json_encode($nik_karyawan);
	}

	public function karyawan_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_insert');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

public function karyawan_pinsert()
{
    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in == 1) {
        // Data Personal
        $nama_karyawan = $this->input->post('nama_karyawan');
        $tmp_lahir = $this->input->post('tmp_lahir');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $tgl_trisula = $this->input->post('tgl_trisula');
        $jenkel = $this->input->post('jenkel');
        
        if ($this->input->post('gol_darah') == '') {
            $gol_darah = '-';
        } else {
            $gol_darah = $this->input->post('gol_darah');
        }
        
        $no_ktp = $this->input->post('no_ktp');
        $no_kk = $this->input->post('no_kk');
        $no_npwp = $this->input->post('no_npwp');
        $sim1 = $this->input->post('sim1');
        $sim2 = $this->input->post('sim2');
        $agama = $this->input->post('agama');
        $pendidikan = $this->input->post('pendidikan');
        $thn_lulus = $this->input->post('thn_lulus');
        $jurusan = $this->input->post('jurusan');
        $no_jamsos = $this->input->post('no_jamsos');
        $no_bpjs_kes = $this->input->post('no_bpjs_kes');
        $no_bpjs_tk = $this->input->post('no_bpjs_tk');
        $no_aia = $this->input->post('no_aia');
        $no_kartu_trimas = $this->input->post('no_kartu_trimas');
        
        // Data Keluarga
        $nama_orang_tua = $this->input->post('nama_orang_tua');
        $nama_pasangan = $this->input->post('nama_pasangan');
        $jumlah_anak = $this->input->post('jumlah_anak');
        $nama_anak = $this->input->post('nama_anak');
        
        // Checkbox/Radio values
        $cek_npwp = $this->input->post('cek_npwp');
        $cek_bpjs_kes = $this->input->post('cek_bpjs_kes');
        $cek_no_bpjs_tk = $this->input->post('cek_no_bpjs_tk');
        
        // Data Tambahan
        $nik = $this->input->post('nik');
        $pin = $this->input->post('pin');
        $tgl_m_kerja = $this->input->post('tgl_m_kerja');
        $sts_nikah = $this->input->post('sts_nikah');
        $sts_penunjang = $this->input->post('sts_penunjang');
        $alamat_ktp = $this->input->post('alamat_ktp');
        $kota_ktp = $this->input->post('kota_ktp');
        $alamat_skrg = $this->input->post('alamat_skrg');
        $kota_skrg = $this->input->post('kota_skrg');
        
        // Kontak
        $telp1 = $this->input->post('telp1');
        if ($telp1 == '' or $telp1 == "'") {
            $last_counter = $this->m_hris->last_hp_kosong();
            $telp1 = '11111111';
        }
        
        $telp2 = $this->input->post('telp2');
        $hobi = $this->input->post('hobi');
        $email = $this->input->post('email');
        $email_tsgi = $this->input->post('email_tsgi');
        
        // Data Tambahan sesuai tabel
        $sts_aktif = $this->input->post('sts_aktif') ? $this->input->post('sts_aktif') : 'Aktif';
        $tipe_ptkp = $this->input->post('tipe_ptkp');
        $efin = $this->input->post('efin');
        $penyakit = $this->input->post('penyakit');
        $kat_penyakit = $this->input->post('kat_penyakit');
        $vaksin_covid = $this->input->post('vaksin_covid') ? $this->input->post('vaksin_covid') : '0';
        $ukuran_baju = $this->input->post('ukuran_baju');
        $ukuran_celana = $this->input->post('ukuran_celana');
        $ukuran_sepatu = $this->input->post('ukuran_sepatu');
        $note = $this->input->post('note');
        $keterangan = $this->input->post('keterangan');
        
        // Data Upah (hanya untuk role 2 atau 5)
        if ($this->session->userdata('role_id') == '2' or $this->session->userdata('role_id') == '5') {
            $gapok = $this->input->post('gapok') ? $this->input->post('gapok') : '0';
            $lspmi = $this->input->post('lspmi') ? $this->input->post('lspmi') : 'Tidak';
            $pensiun = $this->input->post('pensiun') ? $this->input->post('pensiun') : 'Tidak';
            $t_jabatan = $this->input->post('t_jabatan') ? $this->input->post('t_jabatan') : '0';
            $t_prestasi = $this->input->post('t_prestasi') ? $this->input->post('t_prestasi') : '0';
            $t_jen_pek = $this->input->post('t_jen_pek') ? $this->input->post('t_jen_pek') : '0';
            $ljemputan = $this->input->post('ljemputan') ? $this->input->post('ljemputan') : 'Ya';
            $acc_bank = $this->input->post('acc_bank');
            $nama_bank = $this->input->post('nama_bank');
            $bulanan = $this->input->post('bulanan') ? $this->input->post('bulanan') : 'Ya';
            $kontrak = $this->input->post('kontrak') ? $this->input->post('kontrak') : 'Tidak';
        } else {
            $gapok = '0';
            $lspmi = 'Tidak';
            $pensiun = 'Tidak';
            $t_jabatan = '0';
            $t_prestasi = '0';
            $t_jen_pek = '0';
            $ljemputan = 'Ya';
            $acc_bank = '';
            $nama_bank = '';
            $bulanan = 'Tidak';
            $kontrak = 'Tidak';
        }

        // Inisialisasi error handling untuk upload file
        $err_foto = 0;
        $err_ktp = 0;
        $err_kk = 0;
        $err_npwp = 0;
        $err_kes = 0;
        $err_tk = 0;
        $err_aia = 0;
        $err_sim1 = 0;
        $err_sim2 = 0;
        $err_ijazah = 0;

        // Inisialisasi path
        $path_foto = "";
        $path_ktp = "";
        $path_kk = "";
        $path_npwp = "";
        $path_bpjs_kes = "";
        $path_bpjs_tk = "";
        $path_aia = "";
        $path_sim = "";
        $path_ijazah = "";

        // Generate nama file
        $nama_ft = substr($nama_karyawan, 0, 5);
        $cnt = date('his');
        $nama_file_foto = "F-$nama_ft-$cnt";
        $nama_file_kes = "K-$nama_ft-$cnt";
        $nama_file_tk = "T-$nama_ft-$cnt";
        $nama_file_ktp = "KTP-$nama_ft-$cnt";
        $nama_file_kk = "KK-$nama_ft-$cnt";
        $nama_file_npwp = "NPWP-$nama_ft-$cnt";
        $nama_file_aia = "AIA-$nama_ft-$cnt";
        $nama_file_sim1 = "SIM1-$nama_ft-$cnt";
        $nama_file_sim2 = "SIM2-$nama_ft-$cnt";
        $nama_file_ijazah = "I-$nama_ft-$cnt";

        // Upload Foto
        $saved_foto_name = '';
        if ($_FILES['foto']['name'] != '') {
            $config3 = array();
            $config3['upload_path'] = './images/foto/';
            $config3['allowed_types'] = 'jpg|png|jpeg';
            $config3['max_size'] = '2000000';
            $config3['file_name'] = $nama_file_foto;
            $config3['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config3, 'sc_foto');
            $this->sc_foto->initialize($config3);
            $path_foto = './images/foto/';
            $upload_foto = $this->sc_foto->do_upload('foto');
            
            if (!$upload_foto) {
                $err_foto = 1;
            } else {
                $saved_foto_name = $this->sc_foto->data('file_name');
                $err_foto = 0;
            }
        }

        // Upload KTP
        $saved_ktp_name = '';
        if ($_FILES['scan_ktp']['name'] != '') {
            $config4 = array();
            $config4['upload_path'] = './images/ktp/';
            $config4['allowed_types'] = 'jpg|png|jpeg';
            $config4['max_size'] = '2000000';
            $config4['file_name'] = $nama_file_ktp;
            $config4['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config4, 'sc_ktp');
            $this->sc_ktp->initialize($config4);
            $path_ktp = './images/ktp/';
            $upload_ktp = $this->sc_ktp->do_upload('scan_ktp');
            
            if (!$upload_ktp) {
                $err_ktp = 1;
            } else {
                $saved_ktp_name = $this->sc_ktp->data('file_name');
                $err_ktp = 0;
            }
        }

        // Upload KK
        $saved_kk_name = '';
        if ($_FILES['scan_kk']['name'] != '') {
            $config5 = array();
            $config5['upload_path'] = './images/kk/';
            $config5['allowed_types'] = 'jpg|png|jpeg';
            $config5['max_size'] = '2000000';
            $config5['file_name'] = $nama_file_kk;
            $config5['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config5, 'sc_kk');
            $this->sc_kk->initialize($config5);
            $path_kk = './images/kk/';
            $upload_kk = $this->sc_kk->do_upload('scan_kk');
            
            if (!$upload_kk) {
                $err_kk = 1;
            } else {
                $saved_kk_name = $this->sc_kk->data('file_name');
                $err_kk = 0;
            }
        }

        // Upload NPWP
        $saved_npwp_name = '';
        if ($_FILES['scan_npwp']['name'] != '') {
            $config6 = array();
            $config6['upload_path'] = './images/npwp/';
            $config6['allowed_types'] = 'jpg|png|jpeg';
            $config6['max_size'] = '2000000';
            $config6['file_name'] = $nama_file_npwp;
            $config6['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config6, 'sc_npwp');
            $this->sc_npwp->initialize($config6);
            $path_npwp = './images/npwp/';
            $upload_npwp = $this->sc_npwp->do_upload('scan_npwp');
            
            if (!$upload_npwp) {
                $err_npwp = 1;
            } else {
                $saved_npwp_name = $this->sc_npwp->data('file_name');
                $err_npwp = 0;
            }
        }

        // Upload BPJS Kesehatan
        $saved_kes_name = '';
        if ($_FILES['scan_bpjs_kes']['name'] != '') {
            $config = array();
            $config['upload_path'] = './images/bpjs_kes/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2000000';
            $config['file_name'] = $nama_file_kes;
            $config['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config, 'sc_kes');
            $this->sc_kes->initialize($config);
            $path_bpjs_kes = './images/bpjs_kes/';
            $upload_kes = $this->sc_kes->do_upload('scan_bpjs_kes');
            
            if (!$upload_kes) {
                $err_kes = 1;
            } else {
                $saved_kes_name = $this->sc_kes->data('file_name');
                $err_kes = 0;
            }
        }

        // Upload BPJS Tenaga Kerja
        $saved_tk_name = '';
        if ($_FILES['scan_bpjs_tk']['name'] != '') {
            $config2 = array();
            $config2['upload_path'] = './images/bpjs_tk/';
            $config2['allowed_types'] = 'jpg|png|jpeg';
            $config2['max_size'] = '2000000';
            $config2['file_name'] = $nama_file_tk;
            $config2['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config2, 'sc_tk');
            $this->sc_tk->initialize($config2);
            $path_bpjs_tk = './images/bpjs_tk/';
            $upload_tk = $this->sc_tk->do_upload('scan_bpjs_tk');
            
            if (!$upload_tk) {
                $err_tk = 1;
            } else {
                $saved_tk_name = $this->sc_tk->data('file_name');
                $err_tk = 0;
            }
        }

        // Upload AIA
        $saved_aia_name = '';
        if ($_FILES['scan_aia']['name'] != '') {
            $config7 = array();
            $config7['upload_path'] = './images/aia/';
            $config7['allowed_types'] = 'jpg|png|jpeg';
            $config7['max_size'] = '2000000';
            $config7['file_name'] = $nama_file_aia;
            $config7['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config7, 'sc_aia');
            $this->sc_aia->initialize($config7);
            $path_aia = './images/aia/';
            $upload_aia = $this->sc_aia->do_upload('scan_aia');
            
            if (!$upload_aia) {
                $err_aia = 1;
            } else {
                $saved_aia_name = $this->sc_aia->data('file_name');
                $err_aia = 0;
            }
        }

        // Upload SIM 1
        $saved_sim1_name = '';
        if ($_FILES['scan_sim1']['name'] != '') {
            $config9 = array();
            $config9['upload_path'] = './images/sim/';
            $config9['allowed_types'] = 'jpg|png|jpeg';
            $config9['max_size'] = '2000000';
            $config9['file_name'] = $nama_file_sim1;
            $config9['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config9, 'sc_sim1');
            $this->sc_sim1->initialize($config9);
            $path_sim = './images/sim/';
            $upload_sim1 = $this->sc_sim1->do_upload('scan_sim1');
            
            if (!$upload_sim1) {
                $err_sim1 = 1;
            } else {
                $saved_sim1_name = $this->sc_sim1->data('file_name');
                $err_sim1 = 0;
            }
        }

        // Upload SIM 2
        $saved_sim2_name = '';
        if ($_FILES['scan_sim2']['name'] != '') {
            $config10 = array();
            $config10['upload_path'] = './images/sim/';
            $config10['allowed_types'] = 'jpg|png|jpeg';
            $config10['max_size'] = '2000000';
            $config10['file_name'] = $nama_file_sim2;
            $config10['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config10, 'sc_sim2');
            $this->sc_sim2->initialize($config10);
            $upload_sim2 = $this->sc_sim2->do_upload('scan_sim2');
            
            if (!$upload_sim2) {
                $err_sim2 = 1;
            } else {
                $saved_sim2_name = $this->sc_sim2->data('file_name');
                $err_sim2 = 0;
            }
        }

        // Upload Ijazah
        $saved_ijazah_name = '';
        if ($_FILES['scan_ijazah']['name'] != '') {
            $config11 = array();
            $config11['upload_path'] = './images/ijazah/';
            $config11['allowed_types'] = 'jpg|png|jpeg|pdf';
            $config11['max_size'] = '2000000';
            $config11['file_name'] = $nama_file_ijazah;
            $config11['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config11, 'sc_ijazah');
            $this->sc_ijazah->initialize($config11);
            $upload_ijazah = $this->sc_ijazah->do_upload('scan_ijazah');
            
            if (!$upload_ijazah) {
                $err_ijazah = 1;
            } else {
                $saved_ijazah_name = $this->sc_ijazah->data('file_name');
                $err_ijazah = 0;
            }
        }

        // Cek jika ada error upload
        if ($err_foto == 1 or $err_kes == 1 or $err_tk == 1 or $err_ktp == 1 or 
            $err_kk == 1 or $err_npwp == 1 or $err_aia == 1 or $err_sim1 == 1 or 
            $err_sim2 == 1 or $err_ijazah == 1) {
            
            if ($err_foto == 1) {
                echo 'Foto upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
            } 
            if ($err_kes == 1) {
                echo 'Upload BPJS Kesehatan Error : ' . $this->sc_kes->display_errors() . '<br/>';
            } 
            if ($err_tk == 1) {
                echo 'Upload BPJS Tenaga Kerja Error : ' . $this->sc_tk->display_errors() . '<br/>';
            } 
            if ($err_ktp == 1) {
                echo 'Upload KTP Error : ' . $this->sc_ktp->display_errors() . '<br/>';
            } 
            if ($err_kk == 1) {
                echo 'Upload KK Error : ' . $this->sc_kk->display_errors() . '<br/>';
            } 
            if ($err_npwp == 1) {
                echo 'Upload NPWP Error : ' . $this->sc_npwp->display_errors() . '<br/>';
            } 
            if ($err_aia == 1) {
                echo 'Upload AIA Error : ' . $this->sc_aia->display_errors() . '<br/>';
            } 
            if ($err_sim1 == 1) {
                echo 'Upload SIM 1 Error : ' . $this->sc_sim1->display_errors() . '<br/>';
            } 
            if ($err_sim2 == 1) {
                echo 'Upload SIM 2 Error : ' . $this->sc_sim2->display_errors() . '<br/>';
            } 
            if ($err_ijazah == 1) {
                echo 'Upload Ijazah Error : ' . $this->sc_ijazah->display_errors() . '<br/>';
            }
            
            echo '<a href="' . base_url() . 'Karyawan/karyawan_viewbeta">Kembali</a>';
            
        } else {
            // Prepare data untuk disimpan ke database
            $data = array(
                // Data dasar
                'crt_by' => $this->session->userdata('kar_id'),
                'crt_date' => date('Y-m-d H:i:s'),
                'nama_karyawan' => $nama_karyawan,
                'tmp_lahir' => $tmp_lahir,
                'tgl_lahir' => $tgl_lahir,
                'tgl_trisula' => $tgl_trisula,
                'jenkel' => $jenkel,
                'gol_darah' => $gol_darah,
                
                // KTP
                'no_ktp' => $no_ktp,
                'path_ktp' => $path_ktp,
                'scan_ktp' => $saved_ktp_name,
                
                // KK
                'no_kk' => $no_kk,
                'path_kk' => $path_kk,
                'scan_kk' => $saved_kk_name,
                
                // Data keluarga
                'nama_orang_tua' => $nama_orang_tua,
                'nama_pasangan' => $nama_pasangan,
                'jumlah_anak' => $jumlah_anak,
                'nama_anak' => $nama_anak,
                
                // NPWP
                'cek_npwp' => $cek_npwp,
                'no_npwp' => $no_npwp,
                'path_npwp' => $path_npwp,
                'scan_npwp' => $saved_npwp_name,
                
                // Pendidikan
                'agama' => $agama,
                'pendidikan' => $pendidikan,
                'jurusan' => $jurusan,
                'thn_lulus' => $thn_lulus,
                'scan_ijazah' => $saved_ijazah_name,
                
                // Data perusahaan
                'nik' => $nik,
                'pin' => $pin,
                'tgl_m_kerja' => $tgl_m_kerja,
                
                // Jamsostek & BPJS
                'no_jamsos' => $no_jamsos,
                'cek_bpjs_kes' => $cek_bpjs_kes,
                'no_bpjs_kes' => $no_bpjs_kes,
                'path_bpjs_kes' => $path_bpjs_kes,
                'scan_bpjs_kes' => $saved_kes_name,
                'cek_no_bpjs_tk' => $cek_no_bpjs_tk,
                'no_bpjs_tk' => $no_bpjs_tk,
                'path_bpjs_tk' => $path_bpjs_tk,
                'scan_bpjs_tk' => $saved_tk_name,
                'no_kartu_trimas' => $no_kartu_trimas,
                
                // AIA
                'no_aia' => $no_aia,
                'path_aia' => $path_aia,
                'scan_aia' => $saved_aia_name,
                
                // SIM
                'sim1' => $sim1,
                'path_sim' => $path_sim,
                'scan_sim1' => $saved_sim1_name,
                'sim2' => $sim2,
                'scan_sim2' => $saved_sim2_name,
                
                // Status
                'sts_nikah' => $sts_nikah,
                'sts_penunjang' => $sts_penunjang,
                'sts_aktif' => $sts_aktif,
                
                // Alamat
                'alamat_ktp' => $alamat_ktp,
                'kota_ktp' => $kota_ktp,
                'alamat_skrg' => $alamat_skrg,
                'kota_skrg' => $kota_skrg,
                
                // Kontak
                'telp1' => $telp1,
                'telp2' => $telp2,
                'hobi' => $hobi,
                'email' => $email,
                'email_tsgi' => $email_tsgi,
                
                // Foto
                'foto' => $saved_foto_name,
                
                // Data tambahan
                'tipe_ptkp' => $tipe_ptkp,
                'efin' => $efin,
                'penyakit' => $penyakit,
                'kat_penyakit' => $kat_penyakit,
                'vaksin_covid' => $vaksin_covid,
                'ukuran_baju' => $ukuran_baju,
                'ukuran_celana' => $ukuran_celana,
                'ukuran_sepatu' => $ukuran_sepatu,
                'note' => $note,
                'keterangan' => $keterangan,
                
                // Data upah
                'gapok' => $gapok,
                't_jabatan' => $t_jabatan,
                't_prestasi' => $t_prestasi,
                't_jen_pek' => $t_jen_pek,
                'lspmi' => $lspmi,
                'pensiun' => $pensiun,
                'ljemputan' => $ljemputan,
                'acc_bank' => $acc_bank,
                'nama_bank' => $nama_bank,
                'bulanan' => $bulanan,
                'kontrak' => $kontrak
            );
            
            // Simpan data ke database
            $this->m_hris->karyawan_pinsert($data);
            redirect('Karyawan/karyawan_viewbeta');
        }
    } else {
        echo "Session Anda Habis, Silakan Login Kembali";
    }
}

	public function karyawan_updatebeta()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_by_recid($recid_karyawan);
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_updatebeta', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_by_recid($recid_karyawan);
			$data['atasan_k'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_self_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_by_recid($recid_karyawan);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_selfedit', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_self_update2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->temp_karyawan_view_by_id($recid_karyawan);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_selfedit2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function karyawan_pselfedit()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$nama_karyawan = $this->input->post('nama_karyawan');
			$tmp_lahir = $this->input->post('tmp_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$tgl_trisula = $this->input->post('tgl_trisula');
			$jenkel = $this->input->post('jenkel');
			$gol_darah = $this->input->post('gol_darah');
			$no_ktp = $this->input->post('no_ktp');
			$no_kk = $this->input->post('no_kk');
			$no_npwp = $this->input->post('no_npwp');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$jurusan = $this->input->post('jurusan');
			$tgl_m_kerja = $this->input->post('tgl_m_kerja');
			$tgl_a_kerja = $this->input->post('tgl_a_kerja');
			$recid_bag = $this->input->post('recid_bag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			$no_jamsos = $this->input->post('no_jamsos');
			$no_bpjs_kes = $this->input->post('no_bpjs_kes');
			$no_bpjs_tk = $this->input->post('no_bpjs_tk');
			$no_aia = $this->input->post('no_aia');
			$no_askes = $this->input->post('no_askes');
			$sim1 = $this->input->post('sim1');
			$sim2 = $this->input->post('sim2');
			$sts_nikah =  $this->input->post('sts_nikah');
			$alamat_ktp = $this->input->post('alamat_ktp');
			$alamat_skrg = $this->input->post('alamat_skrg');
			$sts_aktif = $this->input->post('sts_aktif2');
			if ($sts_aktif == 'Aktif') {
				$sts_aktif2 = 'Aktif';
			} else {
				$sts_aktif2 = $this->input->post('sts_aktif');
			}
			$sts_penunjang = $this->input->post('sts_penunjang');
			$spm = $this->input->post('spm');
			$tmp_toko = $this->input->post('tmp_toko');
			$tmp_kota = $this->input->post('tmp_kota');
			$telp1 = $this->input->post('telp1');
			$telp2 = $this->input->post('telp2');
			$imei1 = $this->input->post('imei1');
			$imei2 = $this->input->post('imei2');
			$hobi = $this->input->post('hobi');
			$email = $this->input->post('email');
			$gapok = $this->input->post('gapok');
			$lspmi = $this->input->post('lspmi');
			$pensiun = $this->input->post('pensiun');
			$t_jabatan = $this->input->post('t_jabatan');
			$t_prestasi = $this->input->post('t_prestasi');
			$t_jen_pek = $this->input->post('t_jen_pek');
			$ljemputan = $this->input->post('ljemputan');
			$acc_bank = $this->input->post('acc_bank');
			$nama_bank = $this->input->post('nama_bank');
			$bulanan = $this->input->post('bulanan');
			$kontrak = $this->input->post('kontrak');
			$foto = $this->input->post('foto');
			$foto2 = $this->input->post('foto2'); // default db
			$scan_bpjs_kes = $this->input->post('scan_bpjs_kes');
			$scan_bpjs_kes2 = $this->input->post('scan_bpjs_kes2'); // default db
			$scan_bpjs_tk = $this->input->post('scan_bpjs_tk');
			$scan_bpjs_tk2 = $this->input->post('scan_bpjs_tk2'); // default db$scan_ktp = $this->input->post('scan_ktp');
			$scan_ktp2 = $this->input->post('scan_ktp2'); // default db
			$scan_kk = $this->input->post('scan_kk');
			$scan_kk2 = $this->input->post('scan_kk2'); // default db
			$scan_npwp = $this->input->post('scan_npwp');
			$scan_npwp2 = $this->input->post('scan_npwp2'); // default db
			$scan_aia = $this->input->post('scan_aia');
			$scan_aia2 = $this->input->post('scan_aia2'); // default db
			$scan_askes = $this->input->post('scan_askes');
			$scan_askes2 = $this->input->post('scan_askes2'); // default db
			$scan_sim1 = $this->input->post('scan_sim1');
			$scan_sim12 = $this->input->post('scan_sim12'); // default db
			$scan_sim2 = $this->input->post('scan_sim2');
			$scan_sim22 = $this->input->post('scan_sim22'); // default db

			$err_foto = 0;
			$err_ktp = 0;
			$err_kk = 0;
			$err_npwp = 0;
			$err_kes = 0;
			$err_tk = 0;
			$err_aia = 0;
			$err_askes = 0;
			$err_sim1 = 0;
			$err_sim2 = 0;

			$path_foto = "";
			$path_ktp = "";
			$path_kk = "";
			$path_npwp = "";
			$path_bpjs_kes = "";
			$path_bpjs_tk = "";
			$path_aia = "";
			$path_askes = "";
			$path_sim1 = "";
			$path_sim2 = "";


			$nama_ft = substr($nama_karyawan, 0, 5);
			$foto = $this->input->post('foto');
			$cnt = date('his');
			$nama_file_foto = "F-$nik-$cnt";
			$nama_file_kes = "K-$nik-$cnt";
			$nama_file_tk = "T-$nik-$cnt";
			$nama_file_ktp = "KTP-$nik-$cnt";
			$nama_file_kk = "KK-$nik-$cnt";
			$nama_file_npwp = "NPWP-$nik-$cnt";
			$nama_file_aia = "AIA-$nik-$cnt";
			$nama_file_askes = "ASKES-$nik-$cnt";
			$nama_file_sim1 = "SIM1-$nik-$cnt";
			$nama_file_sim2 = "SIM2-$nik-$cnt";
			// echo $sts_aktif2;


			if ($_FILES['foto']['name'] != '') {
				// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/foto/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				$this->sc_foto->initialize($config3);
				$upload_foto = $this->sc_foto->do_upload('foto');
				$saved_foto_name = $this->sc_foto->data('file_name');
				$path_ktp = './images/foto/';
				if (!$upload_foto) {
					$err_foto = 1;
				} else {
					$err_foto = 0;
				}
			} else {
				$saved_foto_name = $foto2;
			}

			if ($_FILES['scan_bpjs_kes']['name'] != '') {
				// BPJS Kesehatan Upload
				$config = array();
				$config['upload_path'] 		= './images/bpjs_kes/';
				$config['allowed_types'] 	= 'jpg|png|jpeg';
				$config['max_size'] 		= '200000';
				$config['file_name']      	= $nama_file_kes;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config, 'sc_kes'); // Create custom object for cover upload
				$this->sc_kes->initialize($config);
				$upload_kes = $this->sc_kes->do_upload('scan_bpjs_kes');
				$saved_kes_name = $this->sc_kes->data('file_name');
				$path_bpjs_kes  = './images/bpjs_kes/';
				if (!$upload_kes) {
					$err_kes = 1;
				} else {
					$err_kes = 0;
				}
			} else {
				$saved_kes_name = $scan_bpjs_kes2;
			}

			if ($_FILES['scan_bpjs_tk']['name'] != '') {
				// BPJS Tenaga Kerja Upload
				$config2 = array();
				$config2['upload_path'] 		= './images/bpjs_tk/';
				$config2['allowed_types'] 		= 'jpg|png|jpeg';
				$config2['max_size'] 			= '2000000';
				$config2['file_name'] 	     	= $nama_file_tk;
				$config2['encrypt_name'] = TRUE;
				$this->load->library('upload', $config2, 'sc_tk');  // Create custom object for catalog upload
				$this->sc_tk->initialize($config2);
				$upload_tk = $this->sc_tk->do_upload('scan_bpjs_tk');
				$saved_tk_name = $this->sc_tk->data('file_name');
				$path_bpjs_tk = './images/bpjs_tk/';
				if (!$upload_tk) {
					$err_tk = 1;
				} else {
					$err_tk = 0;
				}
			} else {
				$saved_tk_name = $scan_bpjs_tk2;
			}


			if ($_FILES['scan_ktp']['name'] != '') {
				// KTP Upload
				$config4 = array();
				$config4['upload_path'] 		= './images/ktp/';
				$config4['allowed_types'] 		= 'jpg|png|jpeg';
				$config4['max_size'] 			= '2000000';
				$config4['file_name'] 	     	= $nama_file_ktp;
				$config4['encrypt_name'] = TRUE;
				$this->load->library('upload', $config4, 'sc_ktp');  // Create custom object for catalog upload
				$this->sc_ktp->initialize($config4);
				$upload_ktp = $this->sc_ktp->do_upload('scan_ktp');
				$saved_ktp_name = $this->sc_ktp->data('file_name');
				$path_ktp = './images/ktp/';
				if (!$upload_ktp) {
					$err_ktp = 1;
				} else {
					$err_ktp = 0;
				}
			} else {
				$saved_ktp_name = $scan_ktp2;
			}

			if ($_FILES['scan_kk']['name'] != '') {
				// KK Upload
				$config5 = array();
				$config5['upload_path'] 		= './images/kk/';
				$config5['allowed_types'] 		= 'jpg|png|jpeg';
				$config5['max_size'] 			= '2000000';
				$config5['file_name'] 	     	= $nama_file_kk;
				$config5['encrypt_name'] = TRUE;
				$this->load->library('upload', $config5, 'sc_kk');  // Create custom object for catalog upload
				$this->sc_kk->initialize($config5);
				$upload_kk = $this->sc_kk->do_upload('scan_kk');
				$saved_kk_name = $this->sc_kk->data('file_name');
				$path_kk = './images/kk/';
				if (!$upload_kk) {
					$err_kk = 1;
				} else {
					$err_kk = 0;
				}
			} else {
				$saved_kk_name = $scan_kk2;
			}

			if ($_FILES['scan_npwp']['name'] != '') {
				// NPWP Upload
				$config6 = array();
				$config6['upload_path'] 		= './images/npwp/';
				$config6['allowed_types'] 		= 'jpg|png|jpeg';
				$config6['max_size'] 			= '2000000';
				$config6['file_name'] 	     	= $nama_file_npwp;
				$config6['encrypt_name'] = TRUE;
				$this->load->library('upload', $config6, 'sc_npwp');  // Create custom object for catalog upload
				$this->sc_npwp->initialize($config6);
				$upload_npwp = $this->sc_npwp->do_upload('scan_npwp');
				$saved_npwp_name = $this->sc_npwp->data('file_name');
				$path_npwp = './images/npwp/';
				if (!$upload_npwp) {
					$err_npwp = 1;
				} else {
					$err_npwp = 0;
				}
			} else {
				$saved_npwp_name = $scan_npwp2;
			}


			if ($_FILES['scan_aia']['name'] != '') {
				// AIA Upload
				$config7 = array();
				$config7['upload_path'] 		= './images/aia/';
				$config7['allowed_types'] 		= 'jpg|png|jpeg';
				$config7['max_size'] 			= '2000000';
				$config7['file_name'] 	     	= $nama_file_aia;
				$config7['encrypt_name'] = TRUE;
				$this->load->library('upload', $config7, 'sc_aia');  // Create custom object for catalog upload
				$this->sc_aia->initialize($config7);
				$upload_aia = $this->sc_aia->do_upload('scan_aia');
				$saved_aia_name = $this->sc_aia->data('file_name');
				$path_aia = './images/aia/';
				if (!$upload_aia) {
					$err_aia = 1;
				} else {
					$err_aia = 0;
				}
			} else {
				$saved_aia_name = $scan_aia2;
			}

			if ($_FILES['scan_askes']['name'] != '') {
				// ASKES Upload
				$config8 = array();
				$config8['upload_path'] 		= './images/askes/';
				$config8['allowed_types'] 		= 'jpg|png|jpeg';
				$config8['max_size'] 			= '2000000';
				$config8['file_name'] 	     	= $nama_file_askes;
				$config8['encrypt_name'] = TRUE;
				$this->load->library('upload', $config8, 'sc_askes');  // Create custom object for catalog upload
				$this->sc_askes->initialize($config8);
				$upload_askes = $this->sc_askes->do_upload('scan_askes');
				$saved_askes_name = $this->sc_askes->data('file_name');
				$path_askes = './images/askes/';
				if (!$upload_askes) {
					$err_askes = 1;
				} else {
					$err_askes = 0;
				}
			} else {
				$saved_askes_name = $scan_askes2;
			}

			if ($_FILES['scan_sim1']['name'] != '') {
				// SIM1 Upload
				$config9 = array();
				$config9['upload_path'] 		= './images/sim/';
				$config9['allowed_types'] 		= 'jpg|png|jpeg';
				$config9['max_size'] 			= '2000000';
				$config9['file_name'] 	     	= $nama_file_sim1;
				$config9['encrypt_name'] = TRUE;
				$this->load->library('upload', $config9, 'sc_sim1');  // Create custom object for catalog upload
				$this->sc_sim1->initialize($config9);
				$upload_sim1 = $this->sc_sim1->do_upload('scan_sim1');
				$saved_sim1_name = $this->sc_sim1->data('file_name');
				$path_sim1 = './images/sim/';
				if (!$upload_sim1) {
					$err_sim1 = 1;
				} else {
					$err_sim1 = 0;
				}
			} else {
				$saved_sim1_name = $scan_sim12;
			}

			if ($_FILES['scan_sim2']['name'] != '') {
				// SIM2 Upload
				$config10 = array();
				$config10['upload_path'] 		= './images/sim/';
				$config10['allowed_types'] 		= 'jpg|png|jpeg';
				$config10['max_size'] 			= '2000000';
				$config10['encrypt_name'] = TRUE;
				$config10['file_name'] 	     	= $nama_file_sim2;
				$this->load->library('upload', $config10, 'sc_sim2');  // Create custom object for catalog upload
				$this->sc_sim2->initialize($config10);
				$upload_sim2 = $this->sc_sim2->do_upload('scan_sim2');
				$saved_sim2_name = $this->sc_sim2->data('file_name');
				$path_sim1 = './images/sim/';
				if (!$upload_sim2) {
					$err_sim2 = 1;
				} else {
					$err_sim2 = 0;
				}
			} else {
				$saved_sim2_name = $scan_sim22;
			}

			if ($err_foto == 1 or $err_kes == 1 or $err_tk == 1 or $err_ktp == 1 or $err_kk == 1 or $err_npwp == 1 or $err_aia == 1 or $err_askes == 1 or $err_sim1 == 1  or $err_sim1 == 2) {
				if ($err_foto == 1) {
					echo 'Foto upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
				} else if ($err_kes == 1) {
					echo 'Upload BPJS Kesehatan Error : ' . $this->sc_kes->display_errors() . '<br/>';
				} else if ($err_ktp == 1) {
					echo 'Upload KTP Error : ' . $this->sc_ktp->display_errors() . '<br/>';
				} else if ($err_kk == 1) {
					echo 'Upload KK Error : ' . $this->sc_kk->display_errors() . '<br/>';
				} else if ($err_npwp == 1) {
					echo 'Upload NPWP Error : ' . $this->sc_npwp->display_errors() . '<br/>';
				} else if ($err_aia == 1) {
					echo 'Upload AIA Error : ' . $this->sc_aia->display_errors() . '<br/>';
				} else if ($err_askes == 1) {
					echo 'Upload Asuransi Kesehatan Error : ' . $this->sc_askes->display_errors() . '<br/>';
				} else if ($err_sim1 == 1) {
					echo 'Upload SIM 1 Error : ' . $this->sc_sim1->display_errors() . '<br/>';
				} else if ($err_sim2 == 1) {
					echo 'Upload SIM 2 Error : ' . $this->sc_sim2->display_errors() . '<br/>';
				} else {
					echo 'Upload BPJS Tenaga Kerja Error : ' . $this->sc_tk->display_errors() . '<br/>';
				}
			} else {
				$data = array(
					'recid_karyawan'	=> $recid_karyawan,
					'nik'				=> $nik,
					'nama_karyawan'		=> $nama_karyawan,
					'tmp_lahir'			=> $tmp_lahir,
					'tgl_lahir'			=> $tgl_lahir,
					'tgl_trisula'		=> $tgl_trisula,
					'jenkel'			=> $jenkel,
					'gol_darah'			=> $gol_darah,
					'no_ktp'			=> $no_ktp,
					'path_ktp'			=> $path_ktp,
					'scan_ktp'			=> $saved_ktp_name,
					'no_kk'				=> $no_kk,
					'path_kk'			=> $path_kk,
					'scan_kk'			=> $saved_kk_name,
					'no_npwp'			=> $no_npwp,
					'path_npwp'			=> $path_npwp,
					'scan_npwp'			=> $saved_npwp_name,
					'agama'				=> $agama,
					'pendidikan'		=> $pendidikan,
					'jurusan'			=> $jurusan,
					'tgl_m_kerja'		=> $tgl_m_kerja,
					'tgl_a_kerja'		=> $tgl_a_kerja,
					'sts_aktif'			=> "Aktif",
					'no_jamsos'			=> $no_jamsos,
					'no_bpjs_kes'		=> $no_bpjs_kes,
					'no_bpjs_tk'		=> $no_bpjs_tk,
					'no_aia'			=> $no_aia,
					'path_aia'			=> $path_aia,
					'scan_aia'			=> $saved_aia_name,
					'no_askes'			=> $no_askes,
					'path_askes'		=> $path_askes,
					'scan_askes'		=> $saved_askes_name,
					'sim1'				=> $sim1,
					'path_sim'			=> $path_sim1,
					'scan_sim1'			=> $saved_sim1_name,
					'sim2'				=> $sim2,
					'scan_sim2'			=> $saved_sim2_name,
					'sts_nikah'			=> $sts_nikah,
					'alamat_ktp'		=> $alamat_ktp,
					'alamat_skrg'		=> $alamat_skrg,
					'telp1'				=> $telp1,
					'telp2'				=> $telp2,
					'imei1'				=> $imei1,
					'imei2'				=> $imei2,
					'hobi'				=> $hobi,
					'email'				=> $email,
					'foto'				=> $saved_foto_name,
					'spm'				=> $spm,
					'tmp_toko'			=> $tmp_toko,
					'tmp_kota'			=> $tmp_kota,
					'scan_bpjs_kes'		=> $saved_kes_name,
					'scan_bpjs_tk'		=> $saved_tk_name,
					'sts_penunjang'		=> $sts_penunjang,
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('y-m-d h:i:s')
				);
				$this->m_hris->temp_karyawan_pinsert($data);
			}
			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_pselfedit2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$nama_karyawan = $this->input->post('nama_karyawan');
			$tmp_lahir = $this->input->post('tmp_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$tgl_trisula = $this->input->post('tgl_trisula');
			$jenkel = $this->input->post('jenkel');
			$gol_darah = $this->input->post('gol_darah');
			$no_ktp = $this->input->post('no_ktp');
			$no_kk = $this->input->post('no_kk');
			$no_npwp = $this->input->post('no_npwp');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$jurusan = $this->input->post('jurusan');
			$no_jamsos = $this->input->post('no_jamsos');
			$no_bpjs_kes = $this->input->post('no_bpjs_kes');
			$no_bpjs_tk = $this->input->post('no_bpjs_tk');
			$no_aia = $this->input->post('no_aia');
			$no_askes = $this->input->post('no_askes');
			$sim1 = $this->input->post('sim1');
			$sim2 = $this->input->post('sim2');
			$sts_nikah =  $this->input->post('sts_nikah');
			$alamat_ktp = $this->input->post('alamat_ktp');
			$alamat_skrg = $this->input->post('alamat_skrg');
			$sts_penunjang = $this->input->post('sts_penunjang');
			$spm = $this->input->post('spm');
			$tmp_toko = $this->input->post('tmp_toko');
			$tmp_kota = $this->input->post('tmp_kota');
			$telp1 = $this->input->post('telp1');
			$telp2 = $this->input->post('telp2');
			$imei1 = $this->input->post('imei1');
			$imei2 = $this->input->post('imei2');
			$hobi = $this->input->post('hobi');
			$email = $this->input->post('email');
			$gapok = $this->input->post('gapok');
			$lspmi = $this->input->post('lspmi');
			$pensiun = $this->input->post('pensiun');
			$foto = $this->input->post('foto');
			$foto2 = $this->input->post('foto2'); // default db
			$scan_bpjs_kes = $this->input->post('scan_bpjs_kes');
			$scan_bpjs_kes2 = $this->input->post('scan_bpjs_kes2'); // default db
			$scan_bpjs_tk = $this->input->post('scan_bpjs_tk');
			$scan_bpjs_tk2 = $this->input->post('scan_bpjs_tk2'); // default db$scan_ktp = $this->input->post('scan_ktp');
			$scan_ktp2 = $this->input->post('scan_ktp2'); // default db
			$scan_kk = $this->input->post('scan_kk');
			$scan_kk2 = $this->input->post('scan_kk2'); // default db
			$scan_npwp = $this->input->post('scan_npwp');
			$scan_npwp2 = $this->input->post('scan_npwp2'); // default db
			$scan_aia = $this->input->post('scan_aia');
			$scan_aia2 = $this->input->post('scan_aia2'); // default db
			$scan_askes = $this->input->post('scan_askes');
			$scan_askes2 = $this->input->post('scan_askes2'); // default db
			$scan_sim1 = $this->input->post('scan_sim1');
			$scan_sim12 = $this->input->post('scan_sim12'); // default db
			$scan_sim2 = $this->input->post('scan_sim2');
			$scan_sim22 = $this->input->post('scan_sim22'); // default db

			$err_foto = 0;
			$err_ktp = 0;
			$err_kk = 0;
			$err_npwp = 0;
			$err_kes = 0;
			$err_tk = 0;
			$err_aia = 0;
			$err_askes = 0;
			$err_sim1 = 0;
			$err_sim2 = 0;

			$nama_ft = substr($nama_karyawan, 0, 5);
			$foto = $this->input->post('foto');
			$cnt = date('his');
			$nama_file_foto = "F-$nik-$cnt";
			$nama_file_kes = "K-$nik-$cnt";
			$nama_file_tk = "T-$nik-$cnt";
			$nama_file_ktp = "KTP-$nik-$cnt";
			$nama_file_kk = "KK-$nik-$cnt";
			$nama_file_npwp = "NPWP-$nik-$cnt";
			$nama_file_aia = "AIA-$nik-$cnt";
			$nama_file_askes = "ASKES-$nik-$cnt";
			$nama_file_sim1 = "SIM1-$nik-$cnt";
			$nama_file_sim2 = "SIM2-$nik-$cnt";
			// echo $sts_aktif2;


			if ($_FILES['foto']['name'] != '') {
				// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/foto/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				$this->sc_foto->initialize($config3);
				$upload_foto = $this->sc_foto->do_upload('foto');
				$saved_foto_name = $this->sc_foto->data('file_name');
				if (!$upload_foto) {
					$err_foto = 1;
				} else {
					$err_foto = 0;
				}
			} else {
				$saved_foto_name = $foto2;
			}

			if ($_FILES['scan_bpjs_kes']['name'] != '') {
				// BPJS Kesehatan Upload
				$config = array();
				$config['upload_path'] 		= './images/bpjs_kes/';
				$config['allowed_types'] 	= 'jpg|png|jpeg';
				$config['max_size'] 		= '200000';
				$config['file_name']      	= $nama_file_kes;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config, 'sc_kes'); // Create custom object for cover upload
				$this->sc_kes->initialize($config);
				$upload_kes = $this->sc_kes->do_upload('scan_bpjs_kes');
				$saved_kes_name = $this->sc_kes->data('file_name');
				if (!$upload_kes) {
					$err_kes = 1;
				} else {
					$err_kes = 0;
				}
			} else {
				$saved_kes_name = $scan_bpjs_kes2;
			}

			if ($_FILES['scan_bpjs_tk']['name'] != '') {
				// BPJS Tenaga Kerja Upload
				$config2 = array();
				$config2['upload_path'] 		= './images/bpjs_tk/';
				$config2['allowed_types'] 		= 'jpg|png|jpeg';
				$config2['max_size'] 			= '2000000';
				$config2['file_name'] 	     	= $nama_file_tk;
				$config2['encrypt_name'] = TRUE;
				$this->load->library('upload', $config2, 'sc_tk');  // Create custom object for catalog upload
				$this->sc_tk->initialize($config2);
				$upload_tk = $this->sc_tk->do_upload('scan_bpjs_tk');
				$saved_tk_name = $this->sc_tk->data('file_name');
				if (!$upload_tk) {
					$err_tk = 1;
				} else {
					$err_tk = 0;
				}
			} else {
				$saved_tk_name = $scan_bpjs_tk2;
			}


			if ($_FILES['scan_ktp']['name'] != '') {
				// KTP Upload
				$config4 = array();
				$config4['upload_path'] 		= './images/ktp/';
				$config4['allowed_types'] 		= 'jpg|png|jpeg';
				$config4['max_size'] 			= '2000000';
				$config4['file_name'] 	     	= $nama_file_ktp;
				$config4['encrypt_name'] = TRUE;
				$this->load->library('upload', $config4, 'sc_ktp');  // Create custom object for catalog upload
				$this->sc_ktp->initialize($config4);
				$upload_ktp = $this->sc_ktp->do_upload('scan_ktp');
				$saved_ktp_name = $this->sc_ktp->data('file_name');
				if (!$upload_ktp) {
					$err_ktp = 1;
				} else {
					$err_ktp = 0;
				}
			} else {
				$saved_ktp_name = $scan_ktp2;
			}

			if ($_FILES['scan_kk']['name'] != '') {
				// KK Upload
				$config5 = array();
				$config5['upload_path'] 		= './images/kk/';
				$config5['allowed_types'] 		= 'jpg|png|jpeg';
				$config5['max_size'] 			= '2000000';
				$config5['file_name'] 	     	= $nama_file_kk;
				$config5['encrypt_name'] = TRUE;
				$this->load->library('upload', $config5, 'sc_kk');  // Create custom object for catalog upload
				$this->sc_kk->initialize($config5);
				$upload_kk = $this->sc_kk->do_upload('scan_kk');
				$saved_kk_name = $this->sc_kk->data('file_name');
				if (!$upload_kk) {
					$err_kk = 1;
				} else {
					$err_kk = 0;
				}
			} else {
				$saved_kk_name = $scan_kk2;
			}

			if ($_FILES['scan_npwp']['name'] != '') {
				// NPWP Upload
				$config6 = array();
				$config6['upload_path'] 		= './images/npwp/';
				$config6['allowed_types'] 		= 'jpg|png|jpeg';
				$config6['max_size'] 			= '2000000';
				$config6['file_name'] 	     	= $nama_file_npwp;
				$config6['encrypt_name'] = TRUE;
				$this->load->library('upload', $config6, 'sc_npwp');  // Create custom object for catalog upload
				$this->sc_npwp->initialize($config6);
				$upload_npwp = $this->sc_npwp->do_upload('scan_npwp');
				$saved_npwp_name = $this->sc_npwp->data('file_name');
				if (!$upload_npwp) {
					$err_npwp = 1;
				} else {
					$err_npwp = 0;
				}
			} else {
				$saved_npwp_name = $scan_npwp2;
			}


			if ($_FILES['scan_aia']['name'] != '') {
				// AIA Upload
				$config7 = array();
				$config7['upload_path'] 		= './images/aia/';
				$config7['allowed_types'] 		= 'jpg|png|jpeg';
				$config7['max_size'] 			= '2000000';
				$config7['file_name'] 	     	= $nama_file_aia;
				$config7['encrypt_name'] = TRUE;
				$this->load->library('upload', $config7, 'sc_aia');  // Create custom object for catalog upload
				$this->sc_aia->initialize($config7);
				$upload_aia = $this->sc_aia->do_upload('scan_aia');
				$saved_aia_name = $this->sc_aia->data('file_name');
				if (!$upload_aia) {
					$err_aia = 1;
				} else {
					$err_aia = 0;
				}
			} else {
				$saved_aia_name = $scan_aia2;
			}

			if ($_FILES['scan_askes']['name'] != '') {
				// ASKES Upload
				$config8 = array();
				$config8['upload_path'] 		= './images/askes/';
				$config8['allowed_types'] 		= 'jpg|png|jpeg';
				$config8['max_size'] 			= '2000000';
				$config8['file_name'] 	     	= $nama_file_askes;
				$config8['encrypt_name'] = TRUE;
				$this->load->library('upload', $config8, 'sc_askes');  // Create custom object for catalog upload
				$this->sc_askes->initialize($config8);
				$upload_askes = $this->sc_askes->do_upload('scan_askes');
				$saved_askes_name = $this->sc_askes->data('file_name');
				if (!$upload_askes) {
					$err_askes = 1;
				} else {
					$err_askes = 0;
				}
			} else {
				$saved_askes_name = $scan_askes2;
			}

			if ($_FILES['scan_sim1']['name'] != '') {
				// SIM1 Upload
				$config9 = array();
				$config9['upload_path'] 		= './images/sim/';
				$config9['allowed_types'] 		= 'jpg|png|jpeg';
				$config9['max_size'] 			= '2000000';
				$config9['file_name'] 	     	= $nama_file_sim1;
				$config9['encrypt_name'] = TRUE;
				$this->load->library('upload', $config9, 'sc_sim1');  // Create custom object for catalog upload
				$this->sc_sim1->initialize($config9);
				$upload_sim1 = $this->sc_sim1->do_upload('scan_sim1');
				$saved_sim1_name = $this->sc_sim1->data('file_name');
				if (!$upload_sim1) {
					$err_sim1 = 1;
				} else {
					$err_sim1 = 0;
				}
			} else {
				$saved_sim1_name = $scan_sim12;
			}

			if ($_FILES['scan_sim2']['name'] != '') {
				// SIM2 Upload
				$config10 = array();
				$config10['upload_path'] 		= './images/sim/';
				$config10['allowed_types'] 		= 'jpg|png|jpeg';
				$config10['max_size'] 			= '2000000';
				$config10['encrypt_name'] = TRUE;
				$config10['file_name'] 	     	= $nama_file_sim2;
				$this->load->library('upload', $config10, 'sc_sim2');  // Create custom object for catalog upload
				$this->sc_sim2->initialize($config10);
				$upload_sim2 = $this->sc_sim2->do_upload('scan_sim2');
				$saved_sim2_name = $this->sc_sim2->data('file_name');
				if (!$upload_sim2) {
					$err_sim2 = 1;
				} else {
					$err_sim2 = 0;
				}
			} else {
				$saved_sim2_name = $scan_sim22;
			}

			if ($err_foto == 1 or $err_kes == 1 or $err_tk == 1 or $err_ktp == 1 or $err_kk == 1 or $err_npwp == 1 or $err_aia == 1 or $err_askes == 1 or $err_sim1 == 1  or $err_sim1 == 2) {
				if ($err_foto == 1) {
					echo 'Foto upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
				} else if ($err_kes == 1) {
					echo 'Upload BPJS Kesehatan Error : ' . $this->sc_kes->display_errors() . '<br/>';
				} else if ($err_ktp == 1) {
					echo 'Upload KTP Error : ' . $this->sc_ktp->display_errors() . '<br/>';
				} else if ($err_kk == 1) {
					echo 'Upload KK Error : ' . $this->sc_kk->display_errors() . '<br/>';
				} else if ($err_npwp == 1) {
					echo 'Upload NPWP Error : ' . $this->sc_npwp->display_errors() . '<br/>';
				} else if ($err_aia == 1) {
					echo 'Upload AIA Error : ' . $this->sc_aia->display_errors() . '<br/>';
				} else if ($err_askes == 1) {
					echo 'Upload Asuransi Kesehatan Error : ' . $this->sc_askes->display_errors() . '<br/>';
				} else if ($err_sim1 == 1) {
					echo 'Upload SIM 1 Error : ' . $this->sc_sim1->display_errors() . '<br/>';
				} else if ($err_sim2 == 1) {
					echo 'Upload SIM 2 Error : ' . $this->sc_sim2->display_errors() . '<br/>';
				} else {
					echo 'Upload BPJS Tenaga Kerja Error : ' . $this->sc_tk->display_errors() . '<br/>';
				}
			} else {
				$data = array(
					'recid_karyawan'	=> $recid_karyawan,
					'nik'				=> $nik,
					'nama_karyawan'		=> $nama_karyawan,
					'tmp_lahir'			=> $tmp_lahir,
					'tgl_lahir'			=> $tgl_lahir,
					'tgl_trisula'		=> $tgl_trisula,
					'jenkel'			=> $jenkel,
					'gol_darah'			=> $gol_darah,
					'no_ktp'			=> $no_ktp,
					'scan_ktp'			=> $saved_ktp_name,
					'no_kk'				=> $no_kk,
					'scan_kk'			=> $saved_kk_name,
					'no_npwp'			=> $no_npwp,
					'scan_npwp'			=> $saved_npwp_name,
					'agama'				=> $agama,
					'pendidikan'		=> $pendidikan,
					'jurusan'			=> $jurusan,
					'no_jamsos'			=> $no_jamsos,
					'no_bpjs_kes'		=> $no_bpjs_kes,
					'no_bpjs_tk'		=> $no_bpjs_tk,
					'no_aia'			=> $no_aia,
					'scan_aia'			=> $saved_aia_name,
					'no_askes'			=> $no_askes,
					'scan_askes'		=> $saved_askes_name,
					'sim1'				=> $sim1,
					'scan_sim1'			=> $saved_sim1_name,
					'sim2'				=> $sim2,
					'scan_sim2'			=> $saved_sim2_name,
					'sts_nikah'			=> $sts_nikah,
					'alamat_ktp'		=> $alamat_ktp,
					'alamat_skrg'		=> $alamat_skrg,
					'telp1'				=> $telp1,
					'telp2'				=> $telp2,
					'imei1'				=> $imei1,
					'imei2'				=> $imei2,
					'hobi'				=> $hobi,
					'email'				=> $email,
					'foto'				=> $saved_foto_name,
					'spm'				=> $spm,
					'tmp_toko'			=> $tmp_toko,
					'tmp_kota'			=> $tmp_kota,
					'scan_bpjs_kes'		=> $saved_kes_name,
					'scan_bpjs_tk'		=> $saved_tk_name,
					'sts_penunjang'		=> $sts_penunjang,
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('y-m-d h:i:s')
				);
				$this->m_hris->karyawan_update($data, $recid_karyawan);
				$this->m_hris->temp_karyawan_delete($recid_karyawan);
			}

			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	/*	public function karyawan_pupdatebeta()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$nik = $this->input->post('nik');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$nama_karyawan = $this->input->post('nama_karyawan');
			$tmp_lahir = $this->input->post('tmp_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$tgl_trisula = $this->input->post('tgl_trisula');
			$jenkel = $this->input->post('jenkel');
			$gol_darah = $this->input->post('gol_darah');
			$no_ktp = $this->input->post('no_ktp');
			$no_kk = $this->input->post('no_kk');
			$no_npwp = $this->input->post('no_npwp');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$jurusan = $this->input->post('jurusan');
			$tgl_m_kerja = $this->input->post('tgl_m_kerja');
			$tgl_a_kerja = $this->input->post('tgl_a_kerja');
			$recid_bag = $this->input->post('recid_bag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			// $sts_jbtn = $this->input->post('sts_jbtn');
			$no_jamsos = $this->input->post('no_jamsos');
			$no_bpjs_kes = $this->input->post('no_bpjs_kes');
			$no_bpjs_tk = $this->input->post('no_bpjs_tk');
			$no_aia = $this->input->post('no_aia');
			$no_askes = $this->input->post('no_askes');
			$sim1 = $this->input->post('sim1');
			$sim2 = $this->input->post('sim2');
			$sts_nikah =  $this->input->post('sts_nikah');
			$alamat_ktp = $this->input->post('alamat_ktp');
			$alamat_skrg = $this->input->post('alamat_skrg');
			$sts_aktif = $this->input->post('sts_aktif2');
			if($sts_aktif == 'Aktif'){
				$sts_aktif2 = 'Aktif';
			}else{
				$sts_aktif2 = $this->input->post('sts_aktif');
			}
			$sts_penunjang = $this->input->post('sts_penunjang');
			$spm = $this->input->post('spm');
			$tmp_toko = $this->input->post('tmp_toko');
			$tmp_kota = $this->input->post('tmp_kota');
			$telp1 = $this->input->post('telp1');
			$telp2 = $this->input->post('telp2');
			$imei1 = $this->input->post('imei1');
			$imei2 = $this->input->post('imei2');
			$hobi = $this->input->post('hobi');
			$email = $this->input->post('email');
			$gapok = $this->input->post('gapok');
			$lspmi = $this->input->post('lspmi');
			$pensiun = $this->input->post('pensiun');
			$t_jabatan = $this->input->post('t_jabatan');
			$t_prestasi = $this->input->post('t_prestasi');
			$t_jen_pek = $this->input->post('t_jen_pek');
			$ljemputan = $this->input->post('ljemputan');
			$acc_bank = $this->input->post('acc_bank');
			$nama_bank = $this->input->post('nama_bank');
			$bulanan= $this->input->post('bulanan');
			$kontrak= $this->input->post('kontrak');
			$foto= $this->input->post('foto');
			$foto2= $this->input->post('foto2'); // default db
			$scan_bpjs_kes = $this->input->post('scan_bpjs_kes');
			$scan_bpjs_kes2 = $this->input->post('scan_bpjs_kes2'); // default db
			$scan_bpjs_tk = $this->input->post('scan_bpjs_tk');
			$scan_bpjs_tk2 = $this->input->post('scan_bpjs_tk2'); // default db$scan_ktp = $this->input->post('scan_ktp');
			$scan_ktp2 = $this->input->post('scan_ktp2'); // default db
			$scan_kk = $this->input->post('scan_kk');
			$scan_kk2 = $this->input->post('scan_kk2'); // default db
			$scan_npwp = $this->input->post('scan_npwp');
			$scan_npwp2 = $this->input->post('scan_npwp2'); // default db
			$scan_aia = $this->input->post('scan_aia');
			$scan_aia2 = $this->input->post('scan_aia2'); // default db
			$scan_askes = $this->input->post('scan_askes');
			$scan_askes2 = $this->input->post('scan_askes2'); // default db
			$scan_sim1 = $this->input->post('scan_sim1');
			$scan_sim12 = $this->input->post('scan_sim12'); // default db
			$scan_sim2 = $this->input->post('scan_sim2');
			$scan_sim22 = $this->input->post('scan_sim22'); // default db

			$err_foto = 0;
			$err_ktp = 0;
			$err_kk = 0;
			$err_npwp = 0;
			$err_kes = 0;
			$err_tk = 0;
			$err_aia = 0;
			$err_askes = 0;
			$err_sim1 = 0;
			$err_sim2 = 0;

			$path_foto = "";
			$path_ktp = "";
			$path_kk = "";
			$path_npwp = "";
			$path_bpjs_kes = "";
			$path_bpjs_tk = "";
			$path_aia = "";
			$path_askes = "";
			$path_sim1 = "";
			$path_sim2 = "";


			$nama_ft = substr($nama_karyawan, 0,5);
			$foto= $this->input->post('foto');
			$cnt = date('his');
			$nama_file_foto = "F-$nik-$cnt";
			$nama_file_kes = "K-$nik-$cnt";
			$nama_file_tk = "T-$nik-$cnt";
			$nama_file_ktp = "KTP-$nik-$cnt";
			$nama_file_kk = "KK-$nik-$cnt";
			$nama_file_npwp = "NPWP-$nik-$cnt";
			$nama_file_aia = "AIA-$nik-$cnt";
			$nama_file_askes = "ASKES-$nik-$cnt";
			$nama_file_sim1 = "SIM1-$nik-$cnt";
			$nama_file_sim2 = "SIM2-$nik-$cnt";
			// echo $sts_aktif2;

			
			if($_FILES['foto']['name'] != ''){
	// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/foto/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
			    $this->sc_foto->initialize($config3);
			    $upload_foto = $this->sc_foto->do_upload('foto');
			    $saved_foto_name = $this->sc_foto->data('file_name');
			    $path_ktp = './images/foto/';
			    if (!$upload_foto) {
			    	$err_foto = 1;
			    }else{
			    	$err_foto = 0;
			    }
			}else{
				$saved_foto_name = $foto2;
			}

			if($_FILES['scan_bpjs_kes']['name'] != ''){
	// BPJS Kesehatan Upload
				$config = array();
				$config['upload_path'] 		= './images/bpjs_kes/';
				$config['allowed_types'] 	= 'jpg|png|jpeg';
				$config['max_size'] 		= '200000';
				$config['file_name']      	= $nama_file_kes;
				$config['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config, 'sc_kes'); // Create custom object for cover upload
			    $this->sc_kes->initialize($config);
			    $upload_kes = $this->sc_kes->do_upload('scan_bpjs_kes');
			    $saved_kes_name = $this->sc_kes->data('file_name');
			    $path_bpjs_kes  = './images/bpjs_kes/';
			    if (!$upload_kes) {
			    	$err_kes = 1;
			    }else{
			    	$err_kes = 0;
			    }
			}else{
				$saved_kes_name = $scan_bpjs_kes2;
			}

			if($_FILES['scan_bpjs_tk']['name'] != ''){
		// BPJS Tenaga Kerja Upload
				$config2 = array();
				$config2['upload_path'] 		= './images/bpjs_tk/';
				$config2['allowed_types'] 		= 'jpg|png|jpeg';
				$config2['max_size'] 			= '2000000';
				$config2['file_name'] 	     	= $nama_file_tk;
				$config2['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config2, 'sc_tk');  // Create custom object for catalog upload
			    $this->sc_tk->initialize($config2);
			    $upload_tk = $this->sc_tk->do_upload('scan_bpjs_tk');
			    $saved_tk_name = $this->sc_tk->data('file_name');
			    $path_bpjs_tk = './images/bpjs_tk/';
			    if (!$upload_tk) {
			    	$err_tk = 1;
			    }else{
			    	$err_tk = 0;
			    }
			}else{
				$saved_tk_name = $scan_bpjs_tk2;
			}


			if($_FILES['scan_ktp']['name'] != ''){
		// KTP Upload
				$config4 = array();
				$config4['upload_path'] 		= './images/ktp/';
				$config4['allowed_types'] 		= 'jpg|png|jpeg';
				$config4['max_size'] 			= '2000000';
				$config4['file_name'] 	     	= $nama_file_ktp;
				$config4['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config4, 'sc_ktp');  // Create custom object for catalog upload
			    $this->sc_ktp->initialize($config4);
			    $upload_ktp = $this->sc_ktp->do_upload('scan_ktp');
			    $saved_ktp_name = $this->sc_ktp->data('file_name');
			    $path_ktp = './images/ktp/';
			    if (!$upload_ktp) {
			    	$err_ktp = 1;
			    }else{
			    	$err_ktp = 0;
			    }
			}else{
				$saved_ktp_name = $scan_ktp2;
			}

			if($_FILES['scan_kk']['name'] != ''){
		// KK Upload
				$config5 = array();
				$config5['upload_path'] 		= './images/kk/';
				$config5['allowed_types'] 		= 'jpg|png|jpeg';
				$config5['max_size'] 			= '2000000';
				$config5['file_name'] 	     	= $nama_file_kk;
				$config5['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config5, 'sc_kk');  // Create custom object for catalog upload
			    $this->sc_kk->initialize($config5);
			    $upload_kk = $this->sc_kk->do_upload('scan_kk');
			    $saved_kk_name = $this->sc_kk->data('file_name');
			    $path_kk = './images/kk/';
			    if (!$upload_kk) {
			    	$err_kk = 1;
			    }else{
			    	$err_kk = 0;
			    }
			}else{
				$saved_kk_name = $scan_kk2;
			}

			if($_FILES['scan_npwp']['name'] != ''){
		// NPWP Upload
				$config6 = array();
				$config6['upload_path'] 		= './images/npwp/';
				$config6['allowed_types'] 		= 'jpg|png|jpeg';
				$config6['max_size'] 			= '2000000';
				$config6['file_name'] 	     	= $nama_file_npwp;
				$config6['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config6, 'sc_npwp');  // Create custom object for catalog upload
			    $this->sc_npwp->initialize($config6);
			    $upload_npwp = $this->sc_npwp->do_upload('scan_npwp');
			    $saved_npwp_name = $this->sc_npwp->data('file_name');
			    $path_npwp = './images/npwp/';
			    if (!$upload_npwp) {
			    	$err_npwp = 1;
			    }else{
			    	$err_npwp = 0;
			    }
			}else{
				$saved_npwp_name = $scan_npwp2;
			}


			if($_FILES['scan_aia']['name'] != ''){
		// AIA Upload
				$config7 = array();
				$config7['upload_path'] 		= './images/aia/';
				$config7['allowed_types'] 		= 'jpg|png|jpeg';
				$config7['max_size'] 			= '2000000';
				$config7['file_name'] 	     	= $nama_file_aia;
				$config7['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config7, 'sc_aia');  // Create custom object for catalog upload
			    $this->sc_aia->initialize($config7);
			    $upload_aia = $this->sc_aia->do_upload('scan_aia');
			    $saved_aia_name = $this->sc_aia->data('file_name');
			    $path_aia = './images/aia/';
			    if (!$upload_aia) {
			    	$err_aia = 1;
			    }else{
			    	$err_aia = 0;
			    }
			}else{
				$saved_aia_name = $scan_aia2;
			}

			if($_FILES['scan_askes']['name'] != ''){
		// ASKES Upload
				$config8 = array();
				$config8['upload_path'] 		= './images/askes/';
				$config8['allowed_types'] 		= 'jpg|png|jpeg';
				$config8['max_size'] 			= '2000000';
				$config8['file_name'] 	     	= $nama_file_askes;
				$config8['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config8, 'sc_askes');  // Create custom object for catalog upload
			    $this->sc_askes->initialize($config8);
			    $upload_askes = $this->sc_askes->do_upload('scan_askes');
			    $saved_askes_name = $this->sc_askes->data('file_name');
			    $path_askes = './images/askes/';
			    if (!$upload_askes) {
			    	$err_askes = 1;
			    }else{
			    	$err_askes = 0;
			    }
			}else{
				$saved_askes_name = $scan_askes2;
			}

			if($_FILES['scan_sim1']['name'] != ''){
		// SIM1 Upload
				$config9 = array();
				$config9['upload_path'] 		= './images/sim/';
				$config9['allowed_types'] 		= 'jpg|png|jpeg';
				$config9['max_size'] 			= '2000000';
				$config9['file_name'] 	     	= $nama_file_sim1;
				$config9['encrypt_name'] = TRUE;
			    $this->load->library('upload', $config9, 'sc_sim1');  // Create custom object for catalog upload
			    $this->sc_sim1->initialize($config9);
			    $upload_sim1 = $this->sc_sim1->do_upload('scan_sim1');
			    $saved_sim1_name = $this->sc_sim1->data('file_name');
			    $path_sim1 = './images/sim/';
			    if (!$upload_sim1) {
			    	$err_sim1 = 1;
			    }else{
			    	$err_sim1 = 0;
			    }
			}else{
				$saved_sim1_name = $scan_sim12;
			}

			if($_FILES['scan_sim2']['name'] != ''){
		// SIM2 Upload
				$config10 = array();
				$config10['upload_path'] 		= './images/sim/';
				$config10['allowed_types'] 		= 'jpg|png|jpeg';
				$config10['max_size'] 			= '2000000';
				$config10['encrypt_name'] = TRUE;
				$config10['file_name'] 	     	= $nama_file_sim2;
			    $this->load->library('upload', $config10, 'sc_sim2');  // Create custom object for catalog upload
			    $this->sc_sim2->initialize($config10);
			    $upload_sim2 = $this->sc_sim2->do_upload('scan_sim2');
			    $saved_sim2_name = $this->sc_sim2->data('file_name');
			    $path_sim1 = './images/sim/';
			    if (!$upload_sim2) {
			    	$err_sim2 = 1;
			    }else{
			    	$err_sim2 = 0;
			    }
			}else{
				$saved_sim2_name = $scan_sim22;
			}

			if($err_foto == 1 or $err_kes == 1 or $err_tk == 1 or $err_ktp == 1 or $err_kk == 1 or $err_npwp == 1 or $err_aia == 1 or $err_askes == 1 or $err_sim1 == 1  or $err_sim1 == 2  )
			{
				if($err_foto == 1)
				{
					echo 'Foto upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
				}else if($err_kes == 1)
				{
					echo 'Upload BPJS Kesehatan Error : ' . $this->sc_kes->display_errors() . '<br/>';
				}
				else if($err_ktp == 1)
				{
					echo 'Upload KTP Error : ' . $this->sc_ktp->display_errors() . '<br/>';
				}
				else if($err_kk == 1)
				{
					echo 'Upload KK Error : ' . $this->sc_kk->display_errors() . '<br/>';
				}
				else if($err_npwp == 1)
				{
					echo 'Upload NPWP Error : ' . $this->sc_npwp->display_errors() . '<br/>';
				}
				else if($err_aia == 1)
				{
					echo 'Upload AIA Error : ' . $this->sc_aia->display_errors() . '<br/>';
				}
				else if($err_askes == 1)
				{
					echo 'Upload Asuransi Kesehatan Error : ' . $this->sc_askes->display_errors() . '<br/>';
				}
				else if($err_sim1 == 1)
				{
					echo 'Upload SIM 1 Error : ' . $this->sc_sim1->display_errors() . '<br/>';
				}
				else if($err_sim2 == 1)
				{
					echo 'Upload SIM 2 Error : ' . $this->sc_sim2->display_errors() . '<br/>';
				}
				else{
					echo 'Upload BPJS Tenaga Kerja Error : ' . $this->sc_tk->display_errors() . '<br/>';
				}
			}

			else
			{
				$data = array(
				'nik'				=> $nik,
				'nama_karyawan'		=> $nama_karyawan,
				'tmp_lahir'			=> $tmp_lahir,
				'tgl_lahir'			=> $tgl_lahir,
				'tgl_trisula'		=> $tgl_trisula,
				'jenkel'			=> $jenkel,
				'gol_darah'			=> $gol_darah,
				'no_ktp'			=> $no_ktp,
				'path_ktp'			=> $path_ktp,
				'scan_ktp'			=> $saved_ktp_name,
				'no_kk'				=> $no_kk,
				'path_kk'			=> $path_kk,
				'scan_kk'			=> $saved_kk_name,
				'no_npwp'			=> $no_npwp,
				'path_npwp'			=> $path_npwp,
				'scan_npwp'			=> $saved_npwp_name,
				'agama'				=> $agama,
				'pendidikan'		=> $pendidikan,
				'jurusan'			=> $jurusan,
				'tgl_m_kerja'		=> $tgl_m_kerja,
				'tgl_a_kerja'		=> $tgl_a_kerja,
				'recid_bag'			=> $recid_bag,
				'recid_jbtn'		=> $recid_jbtn,
				// 'sts_jbtn'			=> $sts_jbtn,
				'sts_aktif'			=> $sts_aktif2,
				'no_jamsos'			=> $no_jamsos,
				'no_bpjs_kes'		=> $no_bpjs_kes,
				'no_bpjs_tk'		=> $no_bpjs_tk,
				'no_aia'			=> $no_aia,
				'path_aia'			=> $path_aia,
				'scan_aia'			=> $saved_aia_name,
				'no_askes'			=> $no_askes,
				'path_askes'		=> $path_askes,
				'scan_askes'		=> $saved_askes_name,
				'sim1'				=> $sim1,
				'path_sim'			=> $path_sim1,
				'scan_sim1'			=> $saved_sim1_name,
				'sim2'				=> $sim2,
				'scan_sim2'			=> $saved_sim2_name,
				'sts_nikah'			=> $sts_nikah,
				'alamat_ktp'		=> $alamat_ktp,
				'alamat_skrg'		=> $alamat_skrg,
				'telp1'				=> $telp1,
				'telp2'				=> $telp2,
				'imei1'				=> $imei1,
				'imei2'				=> $imei2,
				'hobi'				=> $hobi,
				'email'				=> $email,
				'foto'				=> $saved_foto_name,
				'spm'				=> $spm,
				'tmp_toko'			=> $tmp_toko,
				'tmp_kota'			=> $tmp_kota,
				'scan_bpjs_kes'		=> $saved_kes_name,
				'scan_bpjs_tk'		=> $saved_tk_name,
				'sts_penunjang'		=> $sts_penunjang,
				'lspmi'				=> $lspmi,
				'pensiun'			=> $pensiun,
				'gapok'				=> $gapok,
				't_jabatan'			=> $t_jabatan,
				't_prestasi'		=> $t_prestasi,
				't_jen_pek'			=> $t_jen_pek,
				'ljemputan'			=> $ljemputan,
				'acc_bank'			=> $acc_bank,
				'nama_bank'			=> $nama_bank,
				'bulanan'			=> $bulanan,
				'kontrak'			=> $kontrak,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s')
			);

			   //Get Data By Id
			$cek = $this->m_hris->karyawan_by_recid($recid_karyawan);
			foreach ($cek as $cek) {
				$agama2 = $cek->agama;
				$pendidikan2 = $cek->pendidikan;
				$tgl_m_kerja2 = $cek->tgl_m_kerja;
				$tgl_a_kerja2 = $cek->tgl_a_kerja;
				$recid_bag2 = $cek->recid_bag;
				$recid_jbtn2 = $cek->recid_jbtn;
				$sts_nikah2 = $cek->sts_nikah;
				$sts_penunjang2 = $cek->sts_penunjang;
				$lspmi2 = $cek->lspmi;
				$pensiun2 = $cek->pensiun;
				$gapok2 = $cek->gapok;
				$t_jabatan2 = $cek->t_jabatan;
				$t_prestasi2 = $cek->t_prestasi;
				$t_jen_pek2 = $cek->t_jen_pek;
				$acc_bank2 = $cek->acc_bank;
				$bulanan2 = $cek->bulanan;
				$kontrak2 = $cek->kontrak;
			}

			$text = " ";

		//Comparing All Data with New Data record
			if($agama != $agama2){
				$text = "agama : $agama ";
			}else{
				$text = $text;
			}

			if($pendidikan != $pendidikan2){
				$text = "$text, pendidikan : $pendidikan ";
			}else{
				$text = $text;
			}

			if($tgl_m_kerja != $tgl_m_kerja2){
				$text = "$text, tgl_m_kerja : $tgl_m_kerja";
			}else{
				$text = $text;
			}

			if($tgl_a_kerja != $tgl_a_kerja2){
				$text = "$text, tgl_a_kerja : $tgl_a_kerja";
			}else{
				$text = $text;
			}

			if($recid_bag != $recid_bag2){
				$text = "text, recid_bag : $recid_bag";
			}else{
				$text = $text;
			}

			if($recid_jbtn != $recid_jbtn2){
				$text = "text, recid_jbtn : $recid_jbtn";
			}else{
				$text = $text;
			}

			if($sts_nikah != $sts_nikah2){
				$text = "$text, sts_nikah : $sts_nikah ";
			}else{
				$text = $text;
			}

			if($sts_penunjang != $sts_penunjang2){
				$text = "$text, sts_penunjang : $sts_penunjang ";
			}else{
				$text = $text;
			}

			if($lspmi != $lspmi2){
				$text = "$text, lspmi : $lspmi ";
			}else{
				$text = $text;
			}

			if($pensiun != $pensiun2){
				$text = "$text, pensiun : $pensiun ";
			}else{
				$text = $text;
			}

			if($gapok != $gapok2){
				$text = "$text, gapok : $gapok ";
			}else{
				$text = $text;
			}

			if($t_jabatan != $t_jabatan2){
				$text = "$text, t_jabatan : $t_jabatan ";
			}else{
				$text = $text;
			}

			if($t_prestasi != $t_prestasi2){
				$text = "$text, t_prestasi : $t_prestasi ";
			}else{
				$text = $text;
			}

			if($t_jen_pek != $t_jen_pek2){
				$text = "$text, t_jen_pek : $t_jen_pek ";
			}else{
				$text = $text;
			}

			if($acc_bank != $acc_bank2){
				$text = "$text, acc_bank : $acc_bank ";
			}else{
				$text = $text;
			}

			if($bulanan != $bulanan2){
				$text = "$text, bulanan : $bulanan ";
			}else{
				$text = $text;
			}

			if($kontrak != $kontrak2){
				$text = "$text, kontrak : $kontrak ";
			}else{
				$text = $text;
			}

			// echo "ket : $text";


			$this->m_hris->karyawan_update($data, $recid_karyawan);
		//insert log
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_karyawan
			);
			$this->m_hris->karyawan_linsert($data2);

			$jdl_sementara = "SK - $nik";
			$data4 = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'jenis_perjanjian'  => 'Karyawan',
				'judul_perjanjian'	=> $jdl_sementara,
				'tgl_m_legal'		=> $tgl_m_kerja,
				'tgl_a_legal'		=> $tgl_a_kerja,
			);
			$this->m_hris->legal_pinsert($data4);

			$no_legal = $this->m_hris->legal_last();
			foreach ($no_legal as $legalno ) {
				$recid_legal = $legalno->recid_legal;
			}

			$data3 = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'recid_karyawan'	=> $recid_karyawan,
				'recid_legal'		=> $recid_legal,
				'tgl_m_karir'		=> $tgl_m_kerja,
				'tgl_a_karir'		=> $tgl_a_kerja,
				'recid_bag'			=> $recid_bag,
				'recid_jbtn'		=> $recid_jbtn,
				// 'sts_jbtn'			=> $sts_jbtn,
			);
			$this->m_hris->karir_pinsert($data3);
			redirect('Karyawan/karyawan_viewbeta');
			}
			
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	}*/

	public function karyawan_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$nama_karyawan = $this->input->post('nama_karyawan');
			$tmp_lahir = $this->input->post('tmp_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$tgl_trisula = $this->input->post('tgl_trisula');
			$jenkel = $this->input->post('jenkel');
			$gol_darah = $this->input->post('gol_darah');
			$no_ktp = $this->input->post('no_ktp');
			$no_kk = $this->input->post('no_kk');
			$cek_npwp = $this->input->post('cek_npwp');
			$no_npwp = $this->input->post('no_npwp');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$jurusan = $this->input->post('jurusan');
			$thn_lulus = $this->input->post('thn_lulus');
			$tgl_m_kerja = $this->input->post('tgl_m_kerja');
			$tgl_a_kerja = $this->input->post('tgl_a_kerja');
			$recid_bag = $this->input->post('recid_bag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			$no_jamsos = $this->input->post('no_jamsos');
			$no_bpjs_kes = $this->input->post('no_bpjs_kes');
			$no_bpjs_tk = $this->input->post('no_bpjs_tk');
			$sts_nikah =  $this->input->post('sts_nikah');
			$alamat_ktp = $this->input->post('alamat_ktp');
			$alamat_skrg = $this->input->post('alamat_skrg');
			$sts_nikah = $this->input->post('sts_nikah');
			$sts_penunjang = $this->input->post('sts_penunjang');
			$telp1 = $this->input->post('telp1');
			$telp2 = $this->input->post('telp2');
			$imei1 = $this->input->post('imei1');
			$imei2 = $this->input->post('imei2');
			$sim1 = $this->input->post('sim1');
			$sim2 = $this->input->post('sim2');
			$no_aia = $this->input->post('no_aia');
			$no_askes = $this->input->post('no_askes');
			$hobi = $this->input->post('hobi');
			$email = $this->input->post('email');
			$email_cint = $this->input->post('email_cint');
			$spm = $this->input->post('spm');
			$tmp_toko = $this->input->post('tmp_toko');
			$tmp_kota = $this->input->post('tmp_kota');
			$gapok = $this->input->post('gapok');
			$lspmi = $this->input->post('lspmi');
			$pensiun = $this->input->post('pensiun');
			$t_jabatan = $this->input->post('t_jabatan');
			$t_prestasi = $this->input->post('t_prestasi');
			$t_jen_pek = $this->input->post('t_jen_pek');
			$ljemputan = $this->input->post('ljemputan');
			$acc_bank = $this->input->post('acc_bank');
			$nama_bank = $this->input->post('nama_bank');
			$bulanan = $this->input->post('bulanan');
			$kontrak = $this->input->post('kontrak');
			// $recid_atasan = $this->input->post('recid_atasan');
			$sts_aktif = $this->input->post('sts_aktif');
			$vaksin_covid = $this->input->post('vaksin_covid');
			$profile_disc = $this->input->post('profile_disc');
			$pattern_type = $this->input->post('pattern_type');
			$profile_type = $this->input->post('profile_type');
			$kat_penyakit = $this->input->post('kat_penyakit');
			$penyakit = $this->input->post('penyakit');
			// echo $recid_atasan;
			$foto = $this->input->post('foto');
			$foto2 = $this->input->post('foto2'); // default db
			$cek_bpjs_kes = $this->input->post('cek_bpjs_kes');
			$scan_bpjs_kes = $this->input->post('scan_bpjs_kes');
			$scan_bpjs_kes2 = $this->input->post('scan_bpjs_kes2'); // default db
			$cek_bpjs_tk = $this->input->post('cek_bpjs_tk');
			$scan_bpjs_tk = $this->input->post('scan_bpjs_tk');
			$scan_bpjs_tk2 = $this->input->post('scan_bpjs_tk2'); // default db
			$scan_ktp = $this->input->post('scan_ktp');
			$scan_ktp2 = $this->input->post('scan_ktp2'); // default db
			$scan_kk = $this->input->post('scan_kk');
			$scan_kk2 = $this->input->post('scan_kk2'); // default db
			$scan_npwp = $this->input->post('scan_npwp');
			$scan_npwp2 = $this->input->post('scan_npwp2'); // default db
			$scan_aia = $this->input->post('scan_aia');
			$scan_aia2 = $this->input->post('scan_aia2'); // default db
			$scan_askes = $this->input->post('scan_askes');
			$scan_askes2 = $this->input->post('scan_askes2'); // default db
			$scan_sim1 = $this->input->post('scan_sim1');
			$scan_sim12 = $this->input->post('scan_sim12'); // default db
			$scan_sim2 = $this->input->post('scan_sim2');
			$scan_sim22 = $this->input->post('scan_sim22'); // default db
			$scan_ijazah = $this->input->post('scan_ijazah');
			$scan_ijazah2 = $this->input->post('scan_ijazah2'); // default db

			$err_foto = 0;
			$err_ktp = 0;
			$err_kk = 0;
			$err_npwp = 0;
			$err_kes = 0;
			$err_tk = 0;
			$err_aia = 0;
			$err_askes = 0;
			$err_sim1 = 0;
			$err_sim2 = 0;
			$err_ijazah = 0;

			$path_foto = "";
			$path_ktp = "";
			$path_kk = "";
			$path_npwp = "";
			$path_bpjs_kes = "";
			$path_bpjs_tk = "";
			$path_aia = "";
			$path_askes = "";
			$path_sim1 = "";
			$path_sim2 = "";


			// $nama_ft = substr($nama_karyawan, 0, 5);
			$foto = $this->input->post('foto');
			$cnt = date('his');
			$nama_file_foto = "F-$nik-$cnt";
			$nama_file_kes = "K-$nik-$cnt";
			$nama_file_tk = "T-$nik-$cnt";
			$nama_file_ktp = "KTP-$nik-$cnt";
			$nama_file_kk = "KK-$nik-$cnt";
			$nama_file_npwp = "NPWP-$nik-$cnt";
			$nama_file_aia = "AIA-$nik-$cnt";
			$nama_file_askes = "ASKES-$nik-$cnt";
			$nama_file_sim1 = "SIM1-$nik-$cnt";
			$nama_file_sim2 = "SIM2-$nik-$cnt";
			$nama_file_ijazah = "I-$nik-$cnt";



			// $cnt = date('his');
			// $nama_file_foto = "F-$nik-$cnt";
			// $nama_file_kes = "K-$nik-$cnt";
			// $nama_file_tk = "T-$nik-$cnt";

			if ($_FILES['foto']['name'] != '') {
				// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/foto/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				$this->sc_foto->initialize($config3);
				$upload_foto = $this->sc_foto->do_upload('foto');
				$saved_foto_name = $this->sc_foto->data('file_name');
				$path_ktp = './images/ktp/';
				if (!$upload_foto) {
					$err_foto = 1;
				} else {
					$err_foto = 0;
				}
			} else {
				$saved_foto_name = $foto2;
			}

			if ($_FILES['scan_bpjs_kes']['name'] != '') {
				// BPJS Kesehatan Upload
				$config = array();
				$config['upload_path'] 		= './images/bpjs_kes/';
				$config['allowed_types'] 	= 'jpg|png|jpeg';
				$config['max_size'] 		= '200000';
				$config['file_name']      	= $nama_file_kes;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config, 'sc_kes'); // Create custom object for cover upload
				$this->sc_kes->initialize($config);
				$upload_kes = $this->sc_kes->do_upload('scan_bpjs_kes');
				$saved_kes_name = $this->sc_kes->data('file_name');
				$path_bpjs_kes  = './images/bpjs_kes/';
				if (!$upload_kes) {
					$err_kes = 1;
				} else {
					$err_kes = 0;
				}
			} else {
				$saved_kes_name = $scan_bpjs_kes2;
			}

			if ($_FILES['scan_bpjs_tk']['name'] != '') {
				// BPJS Tenaga Kerja Upload
				$config2 = array();
				$config2['upload_path'] 		= './images/bpjs_tk/';
				$config2['allowed_types'] 		= 'jpg|png|jpeg';
				$config2['max_size'] 			= '2000000';
				$config2['file_name'] 	     	= $nama_file_tk;
				$config2['encrypt_name'] = TRUE;
				$this->load->library('upload', $config2, 'sc_tk');  // Create custom object for catalog upload
				$this->sc_tk->initialize($config2);
				$upload_tk = $this->sc_tk->do_upload('scan_bpjs_tk');
				$saved_tk_name = $this->sc_tk->data('file_name');
				$path_bpjs_tk = './images/bpjs_tk/';
				if (!$upload_tk) {
					$err_tk = 1;
				} else {
					$err_tk = 0;
				}
			} else {
				$saved_tk_name = $scan_bpjs_tk2;
			}


			if ($_FILES['scan_ktp']['name'] != '') {
				// KTP Upload
				$config4 = array();
				$config4['upload_path'] 		= './images/ktp/';
				$config4['allowed_types'] 		= 'jpg|png|jpeg';
				$config4['max_size'] 			= '2000000';
				$config4['file_name'] 	     	= $nama_file_ktp;
				$config4['encrypt_name'] = TRUE;
				$this->load->library('upload', $config4, 'sc_ktp');  // Create custom object for catalog upload
				$this->sc_ktp->initialize($config4);
				$upload_ktp = $this->sc_ktp->do_upload('scan_ktp');
				$saved_ktp_name = $this->sc_ktp->data('file_name');
				$path_ktp = './images/ktp/';
				if (!$upload_ktp) {
					$err_ktp = 1;
				} else {
					$err_ktp = 0;
				}
			} else {
				$saved_ktp_name = $scan_ktp2;
			}

			if ($_FILES['scan_kk']['name'] != '') {
				// KK Upload
				$config5 = array();
				$config5['upload_path'] 		= './images/kk/';
				$config5['allowed_types'] 		= 'jpg|png|jpeg';
				$config5['max_size'] 			= '2000000';
				$config5['file_name'] 	     	= $nama_file_kk;
				$config5['encrypt_name'] = TRUE;
				$this->load->library('upload', $config5, 'sc_kk');  // Create custom object for catalog upload
				$this->sc_kk->initialize($config5);
				$upload_kk = $this->sc_kk->do_upload('scan_kk');
				$saved_kk_name = $this->sc_kk->data('file_name');
				$path_kk = './images/kk/';
				if (!$upload_kk) {
					$err_kk = 1;
				} else {
					$err_kk = 0;
				}
			} else {
				$saved_kk_name = $scan_kk2;
			}

			if ($_FILES['scan_npwp']['name'] != '') {
				// NPWP Upload
				$config6 = array();
				$config6['upload_path'] 		= './images/npwp/';
				$config6['allowed_types'] 		= 'jpg|png|jpeg';
				$config6['max_size'] 			= '2000000';
				$config6['file_name'] 	     	= $nama_file_npwp;
				$config6['encrypt_name'] = TRUE;
				$this->load->library('upload', $config6, 'sc_npwp');  // Create custom object for catalog upload
				$this->sc_npwp->initialize($config6);
				$upload_npwp = $this->sc_npwp->do_upload('scan_npwp');
				$saved_npwp_name = $this->sc_npwp->data('file_name');
				$path_npwp = './images/npwp/';
				if (!$upload_npwp) {
					$err_npwp = 1;
				} else {
					$err_npwp = 0;
				}
			} else {
				$saved_npwp_name = $scan_npwp2;
			}


			if ($_FILES['scan_aia']['name'] != '') {
				// AIA Upload
				$config7 = array();
				$config7['upload_path'] 		= './images/aia/';
				$config7['allowed_types'] 		= 'jpg|png|jpeg';
				$config7['max_size'] 			= '2000000';
				$config7['file_name'] 	     	= $nama_file_aia;
				$config7['encrypt_name'] = TRUE;
				$this->load->library('upload', $config7, 'sc_aia');  // Create custom object for catalog upload
				$this->sc_aia->initialize($config7);
				$upload_aia = $this->sc_aia->do_upload('scan_aia');
				$saved_aia_name = $this->sc_aia->data('file_name');
				$path_aia = './images/aia/';
				if (!$upload_aia) {
					$err_aia = 1;
				} else {
					$err_aia = 0;
				}
			} else {
				$saved_aia_name = $scan_aia2;
			}

			if ($_FILES['scan_askes']['name'] != '') {
				// ASKES Upload
				$config8 = array();
				$config8['upload_path'] 		= './images/askes/';
				$config8['allowed_types'] 		= 'jpg|png|jpeg';
				$config8['max_size'] 			= '2000000';
				$config8['file_name'] 	     	= $nama_file_askes;
				$config8['encrypt_name'] = TRUE;
				$this->load->library('upload', $config8, 'sc_askes');  // Create custom object for catalog upload
				$this->sc_askes->initialize($config8);
				$upload_askes = $this->sc_askes->do_upload('scan_askes');
				$saved_askes_name = $this->sc_askes->data('file_name');
				$path_askes = './images/askes/';
				if (!$upload_askes) {
					$err_askes = 1;
				} else {
					$err_askes = 0;
				}
			} else {
				$saved_askes_name = $scan_askes2;
			}

			if ($_FILES['scan_sim1']['name'] != '') {
				// SIM1 Upload
				$config9 = array();
				$config9['upload_path'] 		= './images/sim/';
				$config9['allowed_types'] 		= 'jpg|png|jpeg';
				$config9['max_size'] 			= '2000000';
				$config9['file_name'] 	     	= $nama_file_sim1;
				$config9['encrypt_name'] = TRUE;
				$this->load->library('upload', $config9, 'sc_sim1');  // Create custom object for catalog upload
				$this->sc_sim1->initialize($config9);
				$upload_sim1 = $this->sc_sim1->do_upload('scan_sim1');
				$saved_sim1_name = $this->sc_sim1->data('file_name');
				$path_sim1 = './images/sim/';
				if (!$upload_sim1) {
					$err_sim1 = 1;
				} else {
					$err_sim1 = 0;
				}
			} else {
				$saved_sim1_name = $scan_sim12;
			}

			if ($_FILES['scan_sim2']['name'] != '') {
				// SIM2 Upload
				$config10 = array();
				$config10['upload_path'] 		= './images/sim/';
				$config10['allowed_types'] 		= 'jpg|png|jpeg';
				$config10['max_size'] 			= '2000000';
				$config10['file_name'] 	     	= $nama_file_sim2;
				$config10['encrypt_name'] = TRUE;
				$this->load->library('upload', $config10, 'sc_sim2');  // Create custom object for catalog upload
				$this->sc_sim2->initialize($config10);
				$upload_sim2 = $this->sc_sim2->do_upload('scan_sim2');
				$saved_sim2_name = $this->sc_sim2->data('file_name');
				$path_sim1 = './images/sim/';
				if (!$upload_sim2) {
					$err_sim2 = 1;
				} else {
					$err_sim2 = 0;
				}
			} else {
				$saved_sim2_name = $scan_sim22;
			}

			if ($_FILES['scan_ijazah']['name'] != '') {
				// NPWP Upload
				$config6 = array();
				$config6['upload_path'] 		= './images/ijazah/';
				$config6['allowed_types'] 		= 'jpg|png|jpeg';
				$config6['max_size'] 			= '2000000';
				$config6['file_name'] 	     	= $nama_file_npwp;
				$config6['encrypt_name'] = TRUE;
				$this->load->library('upload', $config6, 'sc_ijazah');  // Create custom object for catalog upload
				$this->sc_ijazah->initialize($config6);
				$upload_ijazah = $this->sc_ijazah->do_upload('scan_ijazah');
				$saved_ijazah_name = $this->sc_ijazah->data('file_name');
				$path_ijazah = './images/ijazah/';
				if (!$upload_ijazah) {
					$err_ijazah = 1;
				} else {
					$err_ijazah = 0;
				}
			} else {
				$saved_ijazah_name = $scan_ijazah2;
			}


			if ($err_foto == 1 or $err_kes == 1 or $err_tk == 1 or $err_ktp == 1 or $err_kk == 1 or $err_npwp == 1 or $err_aia == 1 or $err_askes == 1 or $err_sim1 == 1  or $err_sim1 == 1 or $err_ijazah == 1) {
				if ($err_foto == 1) {
					echo 'Foto upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
				} else if ($err_kes == 1) {
					echo 'Upload BPJS Kesehatan Error : ' . $this->sc_kes->display_errors() . '<br/>';
				} else if ($err_ktp == 1) {
					echo 'Upload KTP Error : ' . $this->sc_ktp->display_errors() . '<br/>';
				} else if ($err_kk == 1) {
					echo 'Upload KK Error : ' . $this->sc_kk->display_errors() . '<br/>';
				} else if ($err_npwp == 1) {
					echo 'Upload NPWP Error : ' . $this->sc_npwp->display_errors() . '<br/>';
				} else if ($err_aia == 1) {
					echo 'Upload AIA Error : ' . $this->sc_aia->display_errors() . '<br/>';
				} else if ($err_askes == 1) {
					echo 'Upload Asuransi Kesehatan Error : ' . $this->sc_askes->display_errors() . '<br/>';
				} else if ($err_sim1 == 1) {
					echo 'Upload SIM 1 Error : ' . $this->sc_sim1->display_errors() . '<br/>';
				} else if ($err_sim2 == 1) {
					echo 'Upload SIM 2 Error : ' . $this->sc_sim2->display_errors() . '<br/>';
				} else if ($err_ijazah == 1) {
					echo 'Upload Ijazah Error : ' . $this->sc_ijazah->display_errors() . '<br/>';
				} else {
					echo 'Upload BPJS Tenaga Kerja Error : ' . $this->sc_tk->display_errors() . '<br/>';
				}
			} else {
				// echo "STS Penunjang :".$sts_penunjang;
				$role = $this->session->userdata('role_id');
				if ($role == '1' or $role == '5') {
					$data = array(
						'crt_by'			=> $this->session->userdata('kar_id'),
						'crt_date'			=> date('y-m-d h:i:s'),
						'nama_karyawan'		=> $nama_karyawan,
						'tmp_lahir'			=> $tmp_lahir,
						'tgl_lahir'			=> $tgl_lahir,
						'tgl_m_kerja'		=> $tgl_m_kerja,
						'tgl_a_kerja'		=> $tgl_a_kerja,
						'tgl_trisula'		=> $tgl_trisula,
						'jenkel'			=> $jenkel,
						'gol_darah'			=> $gol_darah,
						'no_ktp'			=> $no_ktp,
						'path_ktp'			=> $path_ktp,
						'scan_ktp'			=> $saved_ktp_name,
						'no_kk'				=> $no_kk,
						'path_kk'			=> $path_kk,
						'scan_kk'			=> $saved_kk_name,
						'cek_npwp'			=> $cek_npwp,
						'no_npwp'			=> $no_npwp,
						'path_npwp'			=> $path_npwp,
						'scan_npwp'			=> $saved_npwp_name,
						'sim1'				=> $sim1,
						'path_sim'			=> $path_sim1,
						'scan_sim1'			=> $saved_sim1_name,
						'sim2'				=> $sim2,
						'scan_sim2'			=> $saved_sim2_name,
						'agama'				=> $agama,
						'pendidikan'		=> $pendidikan,
						'thn_lulus'			=> $thn_lulus,
						'scan_ijazah'		=> $saved_ijazah_name,
						'jurusan'			=> $jurusan,
						'no_jamsos'			=> $no_jamsos,
						'cek_bpjs_kes'		=> $cek_bpjs_kes,
						'no_bpjs_kes'		=> $no_bpjs_kes,
						'cek_bpjs_tk'		=> $cek_bpjs_tk,
						'no_bpjs_tk'		=> $no_bpjs_tk,
						'no_aia'			=> $no_aia,
						'path_aia'			=> $path_aia,
						'scan_aia'			=> $saved_aia_name,
						'no_askes'			=> $no_askes,
						'path_askes'		=> $path_askes,
						'scan_askes'		=> $saved_askes_name,
						'sts_nikah'			=> $sts_nikah,
						'alamat_ktp'		=> $alamat_ktp,
						'alamat_skrg'		=> $alamat_skrg,
						'telp1'				=> $telp1,
						'telp2'				=> $telp2,
						'imei1'				=> $imei1,
						'imei2'				=> $imei2,
						'hobi'				=> $hobi,
						'email'				=> $email,
						'email_cint'		=> $email_cint,
						'sts_aktif'			=> $sts_aktif,
						'vaksin_covid'		=> $vaksin_covid,
						'profile_disc'		=> $profile_disc,
						'pattern_type'		=> $pattern_type,
						'profile_type'		=> $profile_type,
						'kat_penyakit'		=> $kat_penyakit,
						'penyakit'		=> $penyakit,
						'foto'				=> $saved_foto_name,
						'scan_bpjs_kes'		=> $saved_kes_name,
						'path_bpjs_kes'		=> $path_bpjs_kes,
						'scan_bpjs_tk'		=> $saved_tk_name,
						'path_bpjs_tk'		=> $path_bpjs_tk,
						'sts_penunjang'		=> $sts_penunjang,
						'lspmi'				=> $lspmi,
						'pensiun'			=> $pensiun,
						'gapok'				=> $gapok,
						't_jabatan'			=> $t_jabatan,
						't_prestasi'		=> $t_prestasi,
						't_jen_pek'			=> $t_jen_pek,
						'ljemputan'			=> $ljemputan,
						'acc_bank'			=> $acc_bank,
						'nama_bank'			=> $nama_bank,
						'bulanan'			=> $bulanan,
						'kontrak'			=> $kontrak,
						'spm'				=> $spm,
						'tmp_toko'			=> $tmp_toko,
						'tmp_kota'			=> $tmp_kota,
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('y-m-d h:i:s')
					);
				} else {
					$data = array(
						'crt_by'			=> $this->session->userdata('kar_id'),
						'crt_date'			=> date('y-m-d h:i:s'),
						'nama_karyawan'		=> $nama_karyawan,
						'tmp_lahir'			=> $tmp_lahir,
						'tgl_lahir'			=> $tgl_lahir,
						'tgl_m_kerja'		=> $tgl_m_kerja,
						'tgl_a_kerja'		=> $tgl_a_kerja,
						'tgl_trisula'		=> $tgl_trisula,
						'jenkel'			=> $jenkel,
						'gol_darah'			=> $gol_darah,
						'no_ktp'			=> $no_ktp,
						'path_ktp'			=> $path_ktp,
						'scan_ktp'			=> $saved_ktp_name,
						'no_kk'				=> $no_kk,
						'path_kk'			=> $path_kk,
						'scan_kk'			=> $saved_kk_name,
						'cek_npwp'			=> $cek_npwp,
						'no_npwp'			=> $no_npwp,
						'path_npwp'			=> $path_npwp,
						'scan_npwp'			=> $saved_npwp_name,
						'sim1'				=> $sim1,
						'path_sim'			=> $path_sim1,
						'scan_sim1'			=> $saved_sim1_name,
						'sim2'				=> $sim2,
						'scan_sim2'			=> $saved_sim2_name,
						'agama'				=> $agama,
						'pendidikan'		=> $pendidikan,
						'jurusan'			=> $jurusan,
						'thn_lulus'			=> $thn_lulus,
						'scan_ijazah'		=> $saved_ijazah_name,
						'no_jamsos'			=> $no_jamsos,
						'no_bpjs_kes'		=> $no_bpjs_kes,
						'no_bpjs_tk'		=> $no_bpjs_tk,
						'no_aia'			=> $no_aia,
						'path_aia'			=> $path_aia,
						'scan_aia'			=> $saved_aia_name,
						'no_askes'			=> $no_askes,
						'path_askes'		=> $path_askes,
						'scan_askes'		=> $saved_askes_name,
						'sts_nikah'			=> $sts_nikah,
						'alamat_ktp'		=> $alamat_ktp,
						'alamat_skrg'		=> $alamat_skrg,
						'telp1'				=> $telp1,
						'telp2'				=> $telp2,
						'imei1'				=> $imei1,
						'imei2'				=> $imei2,
						'hobi'				=> $hobi,
						'email'				=> $email,
						'email_cint'		=> $email_cint,
						'sts_aktif'			=> $sts_aktif,
						'vaksin_covid'		=> $vaksin_covid,
						'profile_disc'		=> $profile_disc,
						'pattern_type'		=> $pattern_type,
						'profile_type'		=> $profile_type,
						'kat_penyakit'		=> $kat_penyakit,
						'penyakit'			=> $penyakit,
						'foto'				=> $saved_foto_name,
						'scan_bpjs_kes'		=> $saved_kes_name,
						'path_bpjs_kes'		=> $path_bpjs_kes,
						'scan_bpjs_tk'		=> $saved_tk_name,
						'path_bpjs_tk'		=> $path_bpjs_tk,
						'sts_penunjang'		=> $sts_penunjang,
						'spm'				=> $spm,
						'tmp_toko'			=> $tmp_toko,
						'tmp_kota'			=> $tmp_kota,
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('y-m-d h:i:s')
					);
				}

				$this->m_hris->karyawan_update($data, $recid_karyawan);
				// echo "selesai update";


				//Get Data By Id
				$cek = $this->m_hris->karyawan_by_recid($recid_karyawan);
				foreach ($cek as $cek) {
					$agama2 = $cek->agama;
					$pendidikan2 = $cek->pendidikan;
					$tgl_m_kerja2 = $cek->tgl_m_kerja;
					$tgl_a_kerja2 = $cek->tgl_a_kerja;
					$recid_bag2 = $cek->recid_bag;
					$recid_jbtn2 = $cek->recid_jbtn;
					$sts_nikah2 = $cek->sts_nikah;
					$sts_penunjang2 = $cek->sts_penunjang;
					$lspmi2 = $cek->lspmi;
					$pensiun2 = $cek->pensiun;
					$gapok2 = $cek->gapok;
					$t_jabatan2 = $cek->t_jabatan;
					$t_prestasi2 = $cek->t_prestasi;
					$t_jen_pek2 = $cek->t_jen_pek;
					$acc_bank2 = $cek->acc_bank;
					$bulanan2 = $cek->bulanan;
					$kontrak2 = $cek->kontrak;
				}

				$text = " ";

				//Comparing All Data with New Data record
				if ($agama != $agama2) {
					$text = "agama : $agama ";
				} else {
					$text = $text;
				}

				if ($pendidikan != $pendidikan2) {
					$text = "$text, pendidikan : $pendidikan ";
				} else {
					$text = $text;
				}

				if ($tgl_m_kerja != $tgl_m_kerja2) {
					$text = "$text, tgl_m_kerja : $tgl_m_kerja";
				} else {
					$text = $text;
				}

				if ($tgl_a_kerja != $tgl_a_kerja2) {
					$text = "$text, tgl_a_kerja : $tgl_a_kerja";
				} else {
					$text = $text;
				}

				if ($recid_bag != $recid_bag2) {
					$text = "text, recid_bag : $recid_bag";
				} else {
					$text = $text;
				}

				if ($recid_jbtn != $recid_jbtn2) {
					$text = "text, recid_jbtn : $recid_jbtn";
				} else {
					$text = $text;
				}

				if ($sts_nikah != $sts_nikah2) {
					$text = "$text, sts_nikah : $sts_nikah ";
				} else {
					$text = $text;
				}

				if ($sts_penunjang != $sts_penunjang2) {
					$text = "$text, sts_penunjang : $sts_penunjang ";
				} else {
					$text = $text;
				}

				if ($lspmi != $lspmi2) {
					$text = "$text, lspmi : $lspmi ";
				} else {
					$text = $text;
				}

				if ($pensiun != $pensiun2) {
					$text = "$text, pensiun : $pensiun ";
				} else {
					$text = $text;
				}

				if ($gapok != $gapok2) {
					$text = "$text, gapok : $gapok ";
				} else {
					$text = $text;
				}

				if ($t_jabatan != $t_jabatan2) {
					$text = "$text, t_jabatan : $t_jabatan ";
				} else {
					$text = $text;
				}

				if ($t_prestasi != $t_prestasi2) {
					$text = "$text, t_prestasi : $t_prestasi ";
				} else {
					$text = $text;
				}

				if ($t_jen_pek != $t_jen_pek2) {
					$text = "$text, t_jen_pek : $t_jen_pek ";
				} else {
					$text = $text;
				}

				if ($acc_bank != $acc_bank2) {
					$text = "$text, acc_bank : $acc_bank ";
				} else {
					$text = $text;
				}

				if ($bulanan != $bulanan2) {
					$text = "$text, bulanan : $bulanan ";
				} else {
					$text = $text;
				}

				if ($kontrak != $kontrak2) {
					$text = "$text, kontrak : $kontrak ";
				} else {
					$text = $text;
				}

				// echo "ket : $text";

				//insert log
				$data2 = array(
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $recid_karyawan
				);
				$this->m_hris->karyawan_linsert($data2);
				redirect('Karyawan/karyawan_viewbeta');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function renew()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_anactive();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/renew', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_renew()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$id = $this->input->post('recid_karyawan');
			echo $id;
			$crt_by = $this->session->userdata('kar_id');
			$crt_date = date('y-m-d h:i:s');
			$this->m_hris->karyawan_ucopy($id, $crt_by, $crt_date);
			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### KARIR ###################################################################
	public function karir_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karir'] = $this->m_hris->karir_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karir/karir_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karir_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['karyawan2'] = $this->m_hris->karyawan_view();
			$data['legal'] = $this->m_hris->legal_view();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karir/karir_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function karir_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karir = $this->uri->segment(3);
			$data['karir'] = $this->m_hris->karir_by_recid($recid_karir);
			$data['karyawan'] = $this->m_hris->karyawan_views();
			$data['karyawan2'] = $this->m_hris->karyawan_views();
			$data['legal'] = $this->m_hris->legal_views();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['sub_bagian'] = $this->m_hris->sub_bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$data['golongan'] = $this->m_hris->golongan_view();
			$recid_karyawan = $this->m_hris->karir_by_recid(3);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			foreach ($recid_karyawan as $kry) {
				$recid_karyawan = $kry->recid_karyawan;
			}
			// $data['bulanan'] = $this->m_hris->karyawan_by_recid($recid_karyawan);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('new_karir/karir_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function karir_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karir = $this->input->post('recid_karir');
			$nik = $this->input->post('nik');
			$recid_legal = $this->input->post('recid_legal');
			$tgl_m_karir = $this->input->post('tgl_m_karir');
			$tgl_a_karir = $this->input->post('tgl_a_karir');
			$recid_bag = $this->input->post('recid_bag');
			$recid_subbag = $this->input->post('recid_subbag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			$recid_golongan = $this->input->post('recid_golongan');
			// $sts_jbtn = $this->input->post('sts_jbtn');
			$sts_aktif = $this->input->post('sts_aktif');
			$kategori = $this->input->post('jenis');
			$note = $this->input->post('note');
			// $bulanan = $this->input->post('bulanan');
			$recid_karyawan = $this->m_hris->karyawan_by_nik($nik);
			foreach ($recid_karyawan as $key) {
				$recid_karyawan = $key->recid_karyawan;
			}
			$data = array(
				'recid_karyawan'	=> $recid_karyawan,
				'recid_legal'		=> $recid_legal,
				'kategori'			=> $kategori,
				'recid_bag'			=> $recid_bag,
				'recid_subbag'			=> $recid_subbag,
				'recid_jbtn'		=> $recid_jbtn,
				'recid_golongan'		=> $recid_golongan,
				// 'bulanan'			=> $bulanan,
				'note'				=> $note,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s')
			);

			//Get Data By Id
			$cek = $this->m_hris->karir_by_recid($recid_karir);
			foreach ($cek as $cek) {
				$recid_legal2 = $cek->recid_legal;
				$tgl_m_karir2 = $cek->tgl_m_karir;
				$tgl_a_karir2 = $cek->tgl_a_karir;
				$tingakatan2 = $cek->tingkatan;
				$nama_jbtn2 = $cek->nama_jbtn;
				$note2 = $cek->note;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($recid_legal != $recid_legal2) {
				$text = "$recid_legal2 -> $recid_legal, ";
			} else {
				$text = $text;
			}

			// if($nama_jbtn != $nama_jbtn2){
			// 	$text = "$text, $nama_jbtn2 -> $nama_jbtn, ";
			// }else{
			// 	$text = $text;
			// }

			// if($tingkatan != $tingakatan2){
			// 	$text = "$text, $tingakatan2 -> $tingkatan, ";
			// }else{
			// 	$text = $text;
			// }

			if ($note != $note2) {
				$text = "$text, $note2 -> $note";
			} else {
				$text = $text;
			}

			$this->m_hris->karir_update($data, $recid_karir);
			$recid_karyawan = $this->m_hris->karyawan_by_nik($nik);
			foreach ($recid_karyawan as $recid_karyawan) {
				$recid_karyawan = $recid_karyawan->recid_karyawan;
			}
			echo $recid_karyawan;
			// echo "karyawan : $recid_karyawan - $nik";

			$data2 = array(
				'sts_aktif'			=> $sts_aktif,
				'recid_bag'			=> $recid_bag,
				'recid_jbtn'		=> $recid_jbtn,
				'recid_golongan'		=> $recid_golongan,

				// 'sts_jbtn'			=> $sts_jbtn,
				'tgl_a_kerja'		=> $tgl_a_karir,
				'tgl_m_kerja'		=> $tgl_m_karir,
				// 'bulanan'			=> $bulanan,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s')
			);
			// $this->m_hris->karyawan_update($data2, $recid_karyawan);

			$text = "aktif = $sts_aktif, bag : $recid_bag, jbtn : $recid_jbtn";
			$data3 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_karyawan,
			);
			$this->m_hris->karyawan_linsert($data3);

			$text = "recid_legal : $recid_legal, aktif = $sts_aktif, bag : $recid_bag, jbtn : $recid_jbtn";
			$data4 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_karir,
			);
			$this->m_hris->karir_linsert($data4);

			redirect('index.php/Karir/histori_karir/' . $recid_karyawan);
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function karir_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_legal = $this->uri->segment(3);
			$recid_karyawan = $this->uri->segment(4);
			$data1 = array(
				'legal_delete'		=> '1',
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->legal_update($data1, $recid_legal);
			//Insert Log
			$text = "Delete";
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_legal
			);
			$this->m_hris->legal_linsert($data2);
			redirect('index.php/Karir/histori_karir/' . $recid_karyawan);
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### TUNJANGAN ################################################################
	public function tunjangan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['tunjangan'] = $this->m_hris->tunjangan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/tunjangan/tunjangan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tunjangan_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$data['tunjangan'] = $this->m_hris->tunjangan_detail($recid_karyawan);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/tunjangan/tunjangan_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tunjangan_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/tunjangan/tunjangan_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
			// echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function tunjangan_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->input->post('recid_karyawan');
			// echo "$recid_karyawan";
			$nama_tunjangan = $this->input->post('nama_tunjangan');
			$hub_keluarga = $this->input->post('hub_keluarga');
			$tmp_tlahir = $this->input->post('tmp_lahir');
			$tgl_tlahir = $this->input->post('tgl_lahir');
			$no_id = $this->input->post('no_id');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$pekerjaan = $this->input->post('pekerjaan');
			$no_bpjs = $this->input->post('no_bpjs');
			$sts_tunjangan =  $this->input->post('sts_tunjangan');
			$note =  $this->input->post('note');


			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'recid_karyawan' 	=> $recid_karyawan,
				'nama_tunjangan' 	=> $nama_tunjangan,
				'hub_keluarga' 		=> $hub_keluarga,
				'tmp_tlahir' 		=> $tmp_tlahir,
				'tgl_tlahir' 		=> $tgl_tlahir,
				'no_id' 			=> $no_id,
				'agama' 			=> $agama,
				'pendidikan' 		=> $pendidikan,
				'pekerjaan'			=> $pekerjaan,
				'no_bpjs' 			=> $no_bpjs,
				'sts_tunjangan' 	=> $sts_tunjangan,
				'note' 				=> $note
			);
			$this->m_hris->tunjangan_pinsert($data);
			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tunjangan_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_tunjangan = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['tunjangan'] = $this->m_hris->tunjangan_by_recid($recid_tunjangan);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/tunjangan/tunjangan_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tunjangan_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_tunjangan = $this->input->post('recid_tunjangan');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$nama_tunjangan = $this->input->post('nama_tunjangan');
			$hub_keluarga = $this->input->post('hub_keluarga');
			$tmp_tlahir = $this->input->post('tmp_lahir');
			$tgl_tlahir = $this->input->post('tgl_lahir');
			$no_id = $this->input->post('no_id');
			$agama = $this->input->post('agama');
			$pendidikan = $this->input->post('pendidikan');
			$pekerjaan = $this->input->post('pekerjaan');
			$no_bpjs = $this->input->post('no_bpjs');
			$sts_tunjangan =  $this->input->post('sts_tunjangan');
			$note =  $this->input->post('note');

			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'recid_karyawan' 	=> $recid_karyawan,
				'nama_tunjangan' 	=> $nama_tunjangan,
				'hub_keluarga' 		=> $hub_keluarga,
				'tmp_tlahir' 		=> $tmp_tlahir,
				'tgl_tlahir' 		=> $tgl_tlahir,
				'no_id' 			=> $no_id,
				'agama' 			=> $agama,
				'pendidikan' 		=> $pendidikan,
				'pekerjaan'			=> $pekerjaan,
				'no_bpjs' 			=> $no_bpjs,
				'sts_tunjangan' 	=> $sts_tunjangan,
				'note' 				=> $note
			);

			$cek = $this->m_hris->tunjangan_by_recid($recid_tunjangan);
			foreach ($cek as $cek) {
				$hub_keluarga2 = $cek->hub_keluarga;
				$pekerjaan2 = $cek->pekerjaan;
				$no_bpjs2  = $cek->no_bpjs;
				$sts_tunjangan2  = $cek->sts_tunjangan;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($hub_keluarga != $hub_keluarga2) {
				$text = "$hub_keluarga2 -> $hub_keluarga, ";
			} else {
				$text = $text;
			}
			if ($pekerjaan != $pekerjaan2) {
				$text = "$text, $pekerjaan2 -> $pekerjaan, ";
			} else {
				$text = $text;
			}
			if ($no_bpjs != $no_bpjs2) {
				$text = "$text, $no_bpjs2 -> $no_bpjs, ";
			} else {
				$text = $text;
			}
			if ($sts_tunjangan != $sts_tunjangan2) {
				$text = "$text, $sts_tunjangan2 -> $sts_tunjangan, ";
			} else {
				$text = $text;
			}
			// echo "$text";
			//insert log
			$data4 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_tunjangan,
			);
			$this->m_hris->tunjangan_linsert($data4);
			//Check anak & pasangan
			if ($hub_keluarga2 != $hub_keluarga)		// bila hub keluarga berubah
			{
				if ($hub_keluarga == 'Pasangan') // anak -> pasangan
				{
					//cek jumlah pasangan 
					$jml_psg = $this->m_hris->jml_psg($recid_karyawan);
					foreach ($jml_psg as $pasangan) {
						$jml_psg = $pasangan->pasang;
					}

					if ($jml_psg >= 1) {
						echo "Tidak boleh lebih dari 1";
					} else {
						$this->m_hris->tunjangan_update($data, $recid_tunjangan);
						redirect('Karyawan/karyawan_viewbeta');
					}
				} else 		// pasangan -> anak
				{
					//cek jumlah anak
					$jml_anak = $this->m_hris->jml_anak($recid_karyawan);
					foreach ($jml_anak as $anak) {
						$jml_anak = $anak->anak;
					}

					if ($jml_anak >= 3) {
						echo "Anak Tidak Boleh Lebih dari 3";
					} else {
						$this->m_hris->tunjangan_update($data, $recid_tunjangan);
						redirect('Karyawan/karyawan_viewbeta');
					}
				}
			} else {
				//kalo hubungan tidak berubah
				$this->m_hris->tunjangan_update($data, $recid_tunjangan);
				redirect('Karyawan/karyawan_viewbeta');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_tanggungan()
	{
		$hub_keluarga = $this->input->post('hub_keluarga');
		$recid_karyawan = $this->input->post('recid_karyawan');
		// echo $hub_keluarga;
		if ($hub_keluarga == 'Pasangan') {
			//cek jumlah pasangan 
			$jml_psg = $this->m_hris->jml_psg($recid_karyawan);
			foreach ($jml_psg as $pasangan) {
				$jml_psg = $pasangan->pasang;
			}
			// echo $jml_psg;

			if ($jml_psg >= 1) {
				echo "Data Pasangan yang Ditunjang Tidak boleh lebih dari 1";
			} else {
				echo "Valid";
			}
		} else {
			//cek jumlah anak
			$jml_anak = $this->m_hris->jml_anak($recid_karyawan);
			foreach ($jml_anak as $anak) {
				$jml_anak = $anak->anak;
			}
			// echo $jml_anak;

			if ($jml_anak >= 3) {
				echo "Data Anak yang Ditunjang Tidak Boleh Lebih dari 3 ";
			} else {
				echo "Valid";
			}
		}
	}

	public function tunjangan_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_tunjangan = $this->uri->segment(3);
			$data1 = array(
				'tunjangan_delete'	=> '1',
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->tunjangan_update($data1, $recid_tunjangan);
			//Insert Log
			$text = "Delete";
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_tunjangan
			);
			$this->m_hris->tunjangan_linsert($data2);
			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tunjangan_dinamis()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/tunjangan/dinamis_tunjangan', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	// ################################################### LEGAL ##############################################################
	public function legal_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->legal_view();
			$data['judul'] = "Data Legal";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_views()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->legal_views();
			$data['judul'] = "Data Legal";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function perizinan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->perizinan_view();
			$data['judul'] = "Daftar Perizinan";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function perjanjian_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->perjanjian_view();
			$data['judul'] = "Daftar Perjanjian";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function hki_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->hki_view();
			$data['judul'] = "Daftar HKI";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_insert');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_inserto()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_insert');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$no_perjanjian = $this->input->post('no_perjanjian');
			$jenis_perjanjian = $this->input->post('jenis_perjanjian');
			$judul_perjanjian = $this->input->post('judul_perjanjian');
			$tgl_m_legal = $this->input->post('tgl_m_legal');
			$tgl_a_legal = $this->input->post('tgl_a_legal');
			$scan_perjanjian = $this->input->post('scan_perjanjian');
			$cnt = date('his');
			$note = $this->input->post('note');

			if ($scan_perjanjian == '') {
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'no_perjanjian'			=> $no_perjanjian,
					'jenis_perjanjian'		=> $jenis_perjanjian,
					'judul_perjanjian'		=> $judul_perjanjian,
					'tgl_m_legal'			=> $tgl_m_legal,
					'tgl_a_legal'			=> $tgl_a_legal,
					'note'					=> $note
				);
				$this->m_hris->legal_pinsert($data);
				redirect("Karyawan/legal_view");
			} else {

				// File Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/legal/';
				$config3['allowed_types'] 		= 'pdf';
				$config3['max_size'] 			= '2500000';
				$config3['file_name'] 	     	= $judul_perjanjian;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'scan_perjanjian');  // Create custom object for catalog upload
				$this->scan_perjanjian->initialize($config3);
				$upload_file = $this->scan_perjanjian->do_upload('scan_perjanjian');
				$saved_file_name = $this->scan_perjanjian->data('file_name');

				if ($upload_file) {
					$data = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'no_perjanjian'			=> $no_perjanjian,
						'jenis_perjanjian'		=> $jenis_perjanjian,
						'judul_perjanjian'		=> $judul_perjanjian,
						'tgl_m_legal'			=> $tgl_m_legal,
						'tgl_a_legal'			=> $tgl_a_legal,
						'scan_perjanjian'		=> $saved_file_name,
						'note'					=> $note
					);
					$this->m_hris->legal_pinsert($data);
					redirect("Karyawan/legal_view");
				} else {
					echo 'File upload Error : ' . $this->scan_perjanjian->display_errors() . '<br/>';
				}
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_legal = $this->uri->segment(3);
			$data['legal'] = $this->m_hris->legal_by_recid($recid_legal);

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/legal_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function legal_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_legal = $this->input->post('recid_legal');
			$no_perjanjian = $this->input->post('no_perjanjian');
			$jenis_perjanjian = $this->input->post('jenis_perjanjian');
			$judul_perjanjian = $this->input->post('judul_perjanjian');
			$tgl_m_legal = $this->input->post('tgl_m_legal');
			$tgl_a_legal = $this->input->post('tgl_a_legal');
			$sts_legal = $this->input->post('sts_legal');
			$scan_perjanjian = $this->input->post('scan_perjanjian'); // form
			$scan_perjanjian2 = $this->input->post('scan_perjanjian2'); // default asal db
			$cnt = date('his');
			$err_legal = 0;

			$note = $this->input->post('note');

			//Get Data By Id
			$cek = $this->m_hris->legal_by_recid($recid_legal);
			foreach ($cek as $cek) {
				$jenis_perjanjian2 = $cek->jenis_perjanjian;
				$no_perjanjian2 = $cek->no_perjanjian;
				$tgl_m_legal2 = $cek->tgl_m_legal;
				$tgl_a_legal2 = $cek->tgl_a_legal;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($jenis_perjanjian != $jenis_perjanjian2) {
				$text = "$jenis_perjanjian2 -> $jenis_perjanjian, ";
			} else {
				$text = $text;
			}

			if ($no_perjanjian != $no_perjanjian2) {
				$text = "$text, $no_perjanjian2 -> $no_perjanjian, ";
			} else {
				$text = $text;
			}

			if ($tgl_m_legal != $tgl_m_legal2) {
				$text = "$text, $tgl_m_legal2 -> $tgl_m_legal, ";
			} else {
				$text = $text;
			}

			if ($tgl_a_legal != $tgl_a_legal2) {
				$text = "$text, $tgl_a_legal2 -> $tgl_a_legal";
			} else {
				$text = $text;
			}
			// echo $text;
			// echo $scan_perjanjian;

			// Ceking file upload updated
			if ($_FILES['scan_perjanjian']['name'] != '') { // file diubah
				$scan_perjanjian = $scan_perjanjian;
				// File Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/legal/';
				$config3['allowed_types'] 		= 'pdf';
				$config3['max_size'] 			= '2500000';
				$config3['file_name'] 	     	= $judul_perjanjian;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'scan_perjanjian');  // Create custom object for catalog upload
				$this->scan_perjanjian->initialize($config3);
				$upload_file = $this->scan_perjanjian->do_upload('scan_perjanjian');
				$saved_file_name = $this->scan_perjanjian->data('file_name');
				// $nama_f = $saved_file_name.'-'.$cnt;

				if (!$upload_file) {
					$err_legal = 1;
				} else {
					$err_legal = 0;
				}

				if ($err_legal == 1) {
					echo 'Foto upload Error : ' . $this->scan_perjanjian->display_errors() . '<br/>';
				} else {
					$data = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'no_perjanjian'			=> $no_perjanjian,
						'jenis_perjanjian'		=> $jenis_perjanjian,
						'judul_perjanjian'		=> $judul_perjanjian,
						'tgl_m_legal'			=> $tgl_m_legal,
						'tgl_a_legal'			=> $tgl_a_legal,
						'scan_perjanjian'		=> $saved_file_name,
						'sts_legal'				=> $sts_legal,
						'note'					=> $note,
						'mdf_by'				=> $this->session->userdata('kar_id'),
						'mdf_date'				=> date('y-m-d h:i:s')
					);
					// echo $saved_file_name;
					$this->m_hris->legal_update($data, $recid_legal);

					// insert log
					$data4 = array(
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s'),
						'changed'		=> $text,
						'identity'		=> $recid_legal,
					);
					$this->m_hris->legal_linsert($data4);

					//Update tgl legal kalo diubah
					if ($tgl_m_legal != $tgl_m_legal2 || $tgl_a_legal != $tgl_a_legal2) { // data diganti
						$data5 = array(
							'tgl_m_karir'	=> $tgl_m_legal,
							'tgl_a_karir'	=> $tgl_a_legal,
							'mdf_by'		=> $this->session->userdata('kar_id'),
							'mdf_date'		=> date('y-m-d h:i:s')
						);
						$this->m_hris->legal_karir_update($data5, $recid_legal);
					}
					redirect("Karyawan/legal_view");
					//echo "$tgl_m_legal - $tgl_m_legal2 <br> $tgl_a_legal - $tgl_a_legal2";
				}
			} else {
				echo "update legal";
				$saved_file_name = $scan_perjanjian2; //default db tidak diganti
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'no_perjanjian'			=> $no_perjanjian,
					'jenis_perjanjian'		=> $jenis_perjanjian,
					'judul_perjanjian'		=> $judul_perjanjian,
					'tgl_m_legal'			=> $tgl_m_legal,
					'tgl_a_legal'			=> $tgl_a_legal,
					'sts_legal'				=> $sts_legal,
					'note'					=> $note,
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d h:i:s')
				);
				$this->m_hris->legal_update($data, $recid_legal);
				// insert log
				$data4 = array(
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $recid_legal,
				);
				$this->m_hris->legal_linsert($data4);

				//Update tgl legal kalo diubah
				if ($tgl_m_legal != $tgl_m_legal2 || $tgl_a_legal != $tgl_a_legal2) { // data diganti
					$data5 = array(
						'tgl_m_karir'	=> $tgl_m_legal,
						'tgl_a_karir'	=> $tgl_a_legal,
						'mdf_by'		=> $this->session->userdata('kar_id'),
						'mdf_date'		=> date('y-m-d h:i:s')
					);
					$this->m_hris->legal_karir_update($data5, $recid_legal);
				}
				redirect("Karyawan/legal_view");
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_pupdate2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_legal = $this->input->post('recid_legal');
			$no_perjanjian = $this->input->post('no_perjanjian');
			$jenis_perjanjian = $this->input->post('jenis_perjanjian');
			$judul_perjanjian = $this->input->post('judul_perjanjian');
			$tgl_m_legal = $this->input->post('tgl_m_legal');
			$tgl_a_legal = $this->input->post('tgl_a_legal');
			$sts_legal = $this->input->post('sts_legal');
			$scan_perjanjian = $this->input->post('scan_perjanjian'); // form
			$scan_perjanjian2 = $this->input->post('scan_perjanjian2'); // default asal db
			$cnt = date('his');

			$note = $this->input->post('note');

			//Get Data By Id
			$cek = $this->m_hris->legal_by_recid($recid_legal);
			foreach ($cek as $cek) {
				$jenis_perjanjian2 = $cek->jenis_perjanjian;
				$no_perjanjian2 = $cek->no_perjanjian;
				$tgl_m_legal2 = $cek->tgl_m_legal;
				$tgl_a_legal2 = $cek->tgl_a_legal;
			}

			$text = "";

			//Comparing All Data with New Data record
			if ($jenis_perjanjian != $jenis_perjanjian2) {
				$text = "$jenis_perjanjian2 -> $jenis_perjanjian, ";
			} else {
				$text = $text;
			}

			if ($no_perjanjian != $no_perjanjian2) {
				$text = "$text, $no_perjanjian2 -> $no_perjanjian, ";
			} else {
				$text = $text;
			}

			if ($tgl_m_legal != $tgl_m_legal2) {
				$text = "$text, $tgl_m_legal2 -> $tgl_m_legal, ";
			} else {
				$text = $text;
			}

			if ($tgl_a_legal != $tgl_a_legal2) {
				$text = "$text, $tgl_a_legal2 -> $tgl_a_legal";
			} else {
				$text = $text;
			}
			echo $text;
			// echo $scan_perjanjian;

			// Ceking file upload updated
			if ($_FILES['scan_perjanjian']['name'] != '') { // file diubah
				$scan_perjanjian = $scan_perjanjian;
				// File Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/legal/';
				$config3['allowed_types'] 		= 'pdf';
				$config3['max_size'] 			= '2500000';
				$config3['file_name'] 	     	= $judul_perjanjian;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'scan_perjanjian');  // Create custom object for catalog upload
				$this->scan_perjanjian->initialize($config3);
				$upload_file = $this->scan_perjanjian->do_upload('scan_perjanjian');
				$saved_file_name = $this->scan_perjanjian->data('file_name');
				// $nama_f = $saved_file_name.'-'.$cnt;
				if ($upload_file) {
					$data = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'no_perjanjian'			=> $no_perjanjian,
						'jenis_perjanjian'		=> $jenis_perjanjian,
						'judul_perjanjian'		=> $judul_perjanjian,
						'tgl_m_legal'			=> $tgl_m_legal,
						'tgl_a_legal'			=> $tgl_a_legal,
						'scan_perjanjian'		=> $saved_file_name,
						'sts_legal'				=> $sts_legal,
						'note'					=> $note,
						'mdf_by'				=> $this->session->userdata('kar_id'),
						'mdf_date'				=> date('y-m-d h:i:s')
					);
					// echo $saved_file_name;
					$this->m_hris->legal_update($data, $recid_legal);
				} else {
					echo 'File upload Error : ' . $this->scan_perjanjian->display_errors() . '<br/>';
				}
			} else {
				// $scan_perjanjian = $scan_perjanjian2; //default db tidak diganti
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'no_perjanjian'			=> $no_perjanjian,
					'jenis_perjanjian'		=> $jenis_perjanjian,
					'judul_perjanjian'		=> $judul_perjanjian,
					'tgl_m_legal'			=> $tgl_m_legal,
					'tgl_a_legal'			=> $tgl_a_legal,
					'sts_legal'				=> $sts_legal,
					'note'					=> $note,
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d h:i:s')
				);
				$this->m_hris->legal_update($data, $recid_legal);
			}
			// insert log
			$data4 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_legal,
			);
			$this->m_hris->legal_linsert($data4);

			//Update tgl legal kalo diubah
			if ($tgl_m_legal != $tgl_m_legal2 || $tgl_a_legal2 != $tgl_a_legal2) { // data diganti
				$data5 = array(
					'tgl_m_karir'	=> $tgl_m_legal,
					'tgl_a_karir'	=> $tgl_a_legal,
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s')
				);
				$this->m_hris->legal_karir_update($data5, $recid_legal);
			}
			redirect("Karyawan/legal_view");
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_legal = $this->uri->segment(3);
			$data1 = array(
				'legal_delete'		=> '1',
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->legal_update($data1, $recid_legal);
			//Insert Log
			$text = "Delete";
			$data2 = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $recid_legal
			);
			$this->m_hris->tunjangan_linsert($data2);
			redirect('Karyawan/karyawan_viewbeta');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### ABSEN ###################################################################
	public function absen_tarik()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_tarik');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_pulangcepat()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_plgcpt', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function cek_absen()
	{
		$tgl_work = $this->input->post('tgl');
		$nik = $this->input->post('nik');
		$cek = $this->m_hris->absen_by_tgwork2($nik, $tgl_work);
		if ($cek->num_rows() > 0) {
			return 1; //-------------------- ada
		} else {
			return 0;
		}
	}

	public function absen_ppulangcepat()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');
			$tgl_work = $this->input->post('tgl_work');
			$time_out = $this->input->post('time_out');
			$note = $this->input->post('note');
			$time_out = str_replace(" ", "", $time_out);
			$text = "time_out : $time_out";
			$identity = "$nik - $tgl_work";
			echo "$nik - $tgl_work";
			$cek = $this->m_hris->absen_by_tgwork2($nik, $tgl_work);
			if ($cek->num_rows() > 0) {
				$time_in = $this->m_hris->absen_by_tgwork($nik, $tgl_work);
				foreach ($time_in as $time) {
				}
				$time_out = "$time_out:00";
				list($jam, $menit, $detik) = explode(':', $time->time_in);
				$buatWaktuMulai = mktime($jam, $menit, $detik, 1, 1, 1);

				list($jam1, $menit1, $detik1) = explode(':', $time_out);
				$buatWaktuSelesai = mktime($jam1, $menit1, $detik1, 1, 1, 1);
				$selisihDetik = $buatWaktuSelesai - $buatWaktuMulai;

				$detik = $buatWaktuSelesai - $buatWaktuMulai; //hitung selisih dalam detik
				$jam = floor($detik / 3600); //hiutng menit
				if ($jam >= '1' && $jam <= '4') {
					$jenis_absen = 'K4';
				} else {
					$jenis_absen = 'K';
				}

				$data = array(
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $identity
				);
				$this->m_hris->mabsen_ppulangcepat($nik, $tgl_work, $time_out, $jenis_absen, $note);
				$this->m_hris->mabsen_linsert($data);
				redirect('Karyawan/absen_view');
			} else {
				echo '<script>';
				echo 'alert("Karyawan Belum Absen Hari Ini");';
				echo 'var hal = "' . base_url() . 'Karyawan/absen_pulangcepat"; ';
				echo 'window.location.replace(hal);';
				echo '</script>';
			}
		} else {
			redirect('Auth/keluar');
		}
	}


	public function export_absen()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$berkas = $this->input->post('berkas');
			$config2 = array();
			$config2['upload_path'] 		= './images/absen/';
			$config2['allowed_types'] 		= '*';
			$config2['max_size'] 			= '1000000';
			$config2['file_name'] 	     	= $berkas;
			$config2['encrypt_name'] = TRUE;
			$this->load->library('upload', $config2, 'sc_tk');  // Create custom object for catalog upload
			$this->sc_tk->initialize($config2);
			$upload_tk = $this->sc_tk->do_upload('berkas');
			$saved_tk_name = $this->sc_tk->data('file_name');
			// echo "nama file : $saved_tk_name";
			if ($upload_tk) {
				$path = "images/absen/$saved_tk_name";
				// echo "<br>$path";
				$db = dbase_open($path, 0);

				if ($db) {
					// echo "<br>READ<br>";
					$record_number = dbase_numrecords($db);
					$clear = $this->m_hris->absen_clear();

					if ($clear) {
						for ($i = $record_number; $i >= 1; $i--) {
							$row = dbase_get_record_with_names($db, $i);
							$NIK = $row['NIK'];
							$TGL_IN = $row['TGL_IN'];
							$TGL_OUT = $row['TGL_OUT'];
							$DATE_WORK = $row['DATE_WORK'];
							$TIME_IN = $row['TIME_IN'];
							$TIME_OUT = $row['TIME_OUT'];

							if ($TGL_IN == 0) {
								// echo "|| 0000-00-00 ||";
								$TGL_IN = "0000-00-00";
							} else {
								$thn_in = substr($TGL_IN, 0, 4);
								$bln_in = substr($TGL_IN, 4, 2);
								$tg_in = substr($TGL_IN, 6, 2);
								$TGL_IN = "$thn_in-$bln_in-$tg_in";
								// echo " || $thn_in-$bln_in-$tg_in ||"; 
							}
							if ($TIME_IN == 0) {
								// echo "00:00 ||";
								$TIME_IN = "00:00";
							} else {
								$jam_in = substr($TIME_IN, 0, 2);
								$mnt_in = substr($TIME_IN, 3, 2);
								$TIME_IN = "$jam_in:$mnt_in";
								// echo "$jam_in:$mnt_in ||";
							}
							if ($TIME_OUT == 0) {
								// echo "00:00 ||";
								$TIME_OUT = "00:00";
							} else {
								$jam_out = substr($TIME_OUT, 0, 2);
								$mnt_out = substr($TIME_OUT, 3, 2);
								// echo "$jam_out:$mnt_out || ";
								$TIME_OUT = "$jam_out:$mnt_out";
							}
							if ($TGL_OUT == 0) {
								// echo " 0000-00-00 || ";
								$TGL_OUT = "0000-00-00";
							} else {
								$thn_out = substr($TGL_OUT, 0, 4);
								$bln_out = substr($TGL_OUT, 4, 2);
								$tg_out = substr($TGL_OUT, 6, 2);
								// echo "  $thn_out-$bln_out-$tg_out ||"; 
								$TGL_OUT = "$thn_out-$bln_out-$tg_out";
							}
							if ($DATE_WORK == 0) {
								// echo " 0000-00-00 || <br>";
								$DATE_WORK = "0000-00-00";
							} else {
								$thn_work = substr($DATE_WORK, 0, 4);
								$bln_work = substr($DATE_WORK, 4, 2);
								$tg_work = substr($DATE_WORK, 6, 2);
								// echo "  $thn_work-$bln_work-$tg_work || <br>"; 
								$DATE_WORK = "$thn_work-$bln_work-$tg_work";
							}
							// echo "$NIK # $TGL_IN # $TIME_IN # $TIME_OUT # $TGL_OUT # $DATE_WORK <br>";

							$data = array(
								'nik'			=> $NIK,
								'tgl_in'		=> $TGL_IN,
								'time_in'		=> $TIME_IN,
								'time_out'		=> $TIME_OUT,
								'tgl_out'		=> $TGL_OUT,
								'tgl_work'		=> $DATE_WORK,
							);

							$data2 = array(
								'nik'			=> $NIK,
								'tgl_in'		=> $TGL_IN,
								'time_in'		=> $TIME_IN,
								'time_out'		=> $TIME_OUT,
								'tgl_out'		=> $TGL_OUT,
								'tgl_work'		=> $DATE_WORK,
								'jenis_absen'   => 'K',
								'note'			=> ''
							);
							$this->m_hris->save_absen($data);

							$cek_duo = $this->m_hris->absen_duo($NIK, $DATE_WORK);
							if ($cek_duo->num_rows() > 0) {
								echo "Absen double";
							} else {
								$this->m_hris->save_mabsen($data2);
							}
						}

						redirect('Karyawan/absen_tarik');
						dbase_close($db);
					} else {
						echo "Error Opening .dbf file";
					}
				}
			} else {
				echo '<br>upload Error : ' . $this->sc_tk->display_errors() . '<br/>';
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_adjust()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_adj', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_padjust()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nik = $this->input->post('nik');
			$tgl_work = $this->input->post('tgl_work');
			$time_in = $this->input->post('time_in');
			$time_out = $this->input->post('time_out');
			$note = $this->input->post('note');
			$time_in = str_replace(" ", "", $time_in);
			$time_out = str_replace(" ", "", $time_out);
			$text = "time_in : $time_in, time_out : $time_out";
			$identity = "$nik - $tgl_work";
			$data = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'			=> $text,
				'identity'		=> $identity
			);
			$this->m_hris->mabsen_pupdate($nik, $tgl_work, $time_in, $time_out, $note);
			$this->m_hris->mabsen_linsert($data);
			redirect('Karyawan/absen_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function absen_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['absen'] = $this->m_hris->absen_view();

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

	/*public function absen_absen()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_absensi', $data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	}*/

	// public function absen_pabsen()
	// {
	// 	$logged_in = $this->session->userdata('logged_in');
	// 	if($logged_in == 1)
	// 	{
	// 		$nik = $this->input->post('nik');
	// 		$tgl_mulai = $this->input->post('tgl_mulai');
	// 		$tgl_selesai = $this->input->post('tgl_selesai');
	// 		$jenis_absen = $this->input->post('jenis_absen');
	// 		$note = $this->input->post('note');

	// 	// echo "$nik - $tgl_mulai - $tgl_selesai - $jenis_absen - $note<br>";

	// 		while($tgl_mulai <= $tgl_selesai)
	// 		{
	// 			$nameOfDay = date('D', strtotime($tgl_mulai));
	// 			if($nameOfDay == 'Sat' or $nameOfDay == 'Sun'){
	// 				echo "$tgl_mulai : weekend skip<br>";
	// 			}else{
	// 				$query = $this->db->query("SELECT * from master_karyawan.libur where tgl_libur = '$tgl_mulai'");
	// 				if($query->num_rows() > 0){
	// 					echo "Holiday<br>";
	// 				}else{
	// 					echo "$tgl_mulai : save absen<br>";
	// 					$cek = $this->m_hris->absen_ganda($nik, $tgl_mulai);
	// 					if($cek == 1){
	// 						$tgl_work = $tgl_mulai;
	// 						$this->m_hris->mabsen_pupdate2($nik, $tgl_work, $jenis_absen, $note);
	// 					}else{
	// 						$data2 = array(
	// 							'nik'		=> $nik,
	// 							'tgl_in'	=> $tgl_mulai,
	// 							'time_in'	=> '00:00',
	// 							'time_out'	=> '00:00',
	// 							'tgl_out'	=> $tgl_mulai,
	// 							'tgl_work'	=> $tgl_mulai,
	// 							'jenis_absen' => $jenis_absen,
	// 							'note'		=> $note
	// 						);
	// 						$this->m_hris->save_mabsen($data2);
	// 					}
	// 				}
	// 			}
	// 		 $tgl_mulai= date('Y-m-d', strtotime('+1 days', strtotime($tgl_mulai))); // counter while 	
	// 		}
	// 		redirect('Karyawan/absen_view');
	// 	}
	// 	else
	// 	{
	// 		redirect('Auth/keluar');

	// 	}
	// }

	/*public function absen_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$nik = $this->uri->segment(3);
			$tgl_work = $this->uri->segment(4);
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['absensi'] = $this->m_hris->absen_by_tgwork($nik, $tgl_work);
			
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/absen/absen_update', $data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	}*/

	/*public function absen_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$nik = $this->input->post('nik');
			$tgl_work = $this->input->post('tgl_mulai');
			$jenis_absen = $this->input->post('jenis_absen');
			$note = $this->input->post('note');
			$recid_absen = $this->input->post('recid_absen');
			$cek = $this->m_hris->absen_by_recid($recid_absen);
			foreach ($cek as $key) {
				$tgl_work2 = $key->tgl_work;
				$jenis_absen2 = $key->jenis_absen;
			}
			$text = "";
			if($tgl_work != $tgl_work2){
				$text = "$tgl_work2 : $tgl_work";
			}
			if($jenis_absen != $jenis_absen2){
				$text = "$text, $jenis_absen2 : $jenis_absen";
			}

			$identity = "$recid_absen";
			$data = array(
				'mdf_by'		=> $this->session->userdata('kar_id'),
				'mdf_date'		=> date('y-m-d h:i:s'),
				'changed'		=> $text,
				'identity'		=> $identity
			);
			$this->m_hris->mabsen_pupdate2( $nik, $tgl_work, $jenis_absen, $note);
			$this->m_hris->mabsen_linsert($data);
			redirect('Karyawan/absen_view');
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	}*/

	public function terlambat()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['telat'] = $this->m_hris->list_telat();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_telat', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### TRAINING ################################################################

	// ################################################### TRAINING ############################################################

	/*public function training_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			// $data['training'] = $this->m_hris->train_aju();
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			$usr = $this->session->userdata('kar_id');
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}
			if($role == '2' or $role == '1' or $role == '25' or $role == '24')
			{
				$data['training'] = $this->m_hris->train_aju()->result();
			}else{
				$data['training'] = $this->m_hris->train_aju_by_bagian($bagian)->result();
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_view', $data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');
			
		}
	}*/

	public function karyawan_by_id()
	{
		$crt_by = $this->input->post('crt_by');
		$pengaju = $this->m_hris->karyawan_current_karir($crt_by);
		// foreach ($pengaju as $data) {
		// 	$counting = $data->counting;
		// }
		echo json_encode($pengaju);
	}


	// ################################################### PEKERJAAN  #############################################################

	public function pekerjaan_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['pekerjaan'] = $this->m_hris->pekerjaan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pekerjaan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pekerjaan_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pekerjaan_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pekerjaan_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_pekerjaan = $this->input->post('nama_pekerjaan');
			$note = $this->input->post('note');
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'nama_pekerjaan'		=> $nama_pekerjaan,
				'note'					=> $note,
			);
			$this->m_hris->pekerjaan_insert($data);
			redirect('Karyawan/pekerjaan_view');
		} else {
			redirect('Auth/keluar');
		}
	}


	// ################################################### MASTER BUDGET ###########################################################



	// ################################################### PENGAJUAN LEMBUR ########################################################


	public function plembur_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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
			if ($role == '1' or $role == '5') {
				$data['realisasi'] = $this->m_hris->plembur_by_roles();
			} else {
				if ($tingkatan > 9) {
					$data['realisasi'] = $this->m_hris->plembur_by_roles_dept($department);
				} else {
					if ($usr == '1094') {
						$data['realisasi'] = $this->m_hris->plembur_by_roles_sales();
					} else {
						$data['realisasi'] = $this->m_hris->plembur_by_roles_str($struktur);
					}
				}
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pengajuan/plembur_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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
			if ($role == '1' or $role == '5') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else {
				if ($tingkatan > 9) {
					$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
				} else {
					if ($usr == '1094') {
						$data['bagian'] = $this->m_hris->bagian_sales();
					} else {
						$data['bagian'] = $this->m_hris->bagian_by_str($struktur);
					}
				}
			}
			$data['dept']	= $department;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pengajuan/plembur_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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

			if ($role == '1' or $role == '5') {
				$data['bagian'] = $this->m_hris->bagian_by_role();
			} else {
				if ($tingkatan > 9) {
					$data['bagian'] = $this->m_hris->bagian_by_role_dept($department);
				} else {
					if ($usr == '1094') {
						$data['bagian'] = $this->m_hris->bagian_sales();
					} else {
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
		} else {
			redirect('Auth/keluar');
		}
	}

	function detail_pekerjaan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->input->post('recid_plembur');
			$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			$query = $this->m_hris->dlembur_view2($recid_plembur);
			$data = [];
			foreach ($query->result() as $r) {
				$data[] = array(
					$r->pekerjaan,
					$r->target_kerja,
					"
					<a data-recid_detlembur='" . $r->recid_detlembur . "' data-pekerjaan='" . $r->pekerjaan . "' data-target_kerja='" . $r->target_kerja . "'  data-toggle='modal' data-target='#edit_kerja'>
					<button type='button' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i></button></a><button type='button' class='btn btn-danger btn-sm' onclick='delete_pekerjaan(" . $r->recid_detlembur . ")'><i class='fa fa-trash'></i></button>",
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
		} else {
			redirect('Auth/keluar');
		}
	}

	public function add_pekerjaan()
	{
		$recid_plembur = $this->input->post('recid_plembur');
		$pekerjaan = $this->input->post('pekerjaan');
		$target_kerja = $this->input->post('target_kerja');
		// echo $recid_plembur." - ".$pekerjaan." - ".$target_kerja;
		$data2 = array(
			'crt_by'				=> $this->session->userdata('kar_id'),
			'crt_date'				=> date('y-m-d h:i:s'),
			'recid_plembur'			=> $recid_plembur,
			'pekerjaan'				=> $pekerjaan,
			'target_kerja'			=> $target_kerja
		);
		$this->m_hris->dlembur_insert($data2);
		echo "OK";
	}

	public function update_pekerjaan()
	{
		$recid_detlembur = $this->input->post('recid_detlembur');
		$pekerjaan = $this->input->post('pekerjaan');
		$target_kerja = $this->input->post('target_kerja');
		// echo $recid_plembur." - ".$pekerjaan." - ".$target_kerja;
		$data2 = array(
			'pekerjaan'				=> $pekerjaan,
			'target_kerja'			=> $target_kerja,
			'mdf_by'				=> $this->session->userdata('kar_id'),
			'mdf_date'				=> date('y-m-d h:i:s'),
		);
		$this->m_hris->dlembur_pupdate($data2, $recid_detlembur);
		echo "OK";
	}

	public function delete_pekerjaan()
	{
		$recid_detlembur = $this->input->post('recid_detlembur');
		$data2 = array(
			'mdf_by'				=> $this->session->userdata('kar_id'),
			'mdf_date'				=> date('y-m-d h:i:s'),
			'is_delete'				=> '1'
		);
		$this->m_hris->dlembur_pupdate($data2, $recid_detlembur);
	}

	public function masterbudget()
	{
		$recid_bag = $this->input->post('recid_bag');
		$tgl = $this->input->post('tgl');
		$cutoff = $this->m_hris->cek_cutoff($tgl);
		if ($cutoff->num_rows() > 0) {
			foreach ($cutoff->result() as $cutoff) {
				$recid_clembur = $cutoff->recid_clembur;
			}
			$master_budget = $this->m_hris->masterbudget($recid_bag, $recid_clembur);
			if ($master_budget->num_rows() > 0) {
				echo '1';
			} else {
				echo  '0';
			}
		} else {
			echo  '0';
		}
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

	public function cek_totalembur()
	{
		//$mbl = "3";
		$mbl = $this->input->post('recid_mbl');
		$tahun = $this->m_hris->cek_budget($mbl);
		foreach ($tahun as $thn) {
			$tahun = $thn->tahun;
		}
		$periode = $this->m_hris->cek_kuartal($mbl, $tahun)->result();
		foreach ($periode as $key) {
			$recid_bag = $key->recid_bag;
			$kuartal = $key->kuartal;
		}
		// echo $kuartal;
		$data = array();
		$total_lembur = $this->m_hris->cek_totalembur($kuartal, $recid_bag, $tahun);
		foreach ($total_lembur as $total) {
			$total = $total->total;
		}
		foreach ($total_lembur as $datas) {
			$a = array($datas->total);
			array_push($data, $a);
		}

		echo json_encode($data);
	}

	public function plembur_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_bag = $this->input->post('recid_bag');
			$recid_mbl = $this->input->post('recid_mbl');
			$kategori = $this->input->post('kategori');
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			$pekerjaan = $this->input->post('pekerjaan');
			$target = $this->input->post('target');
			// print_r($target);


			// echo "$tgl_lembur, $tahun, $bulan, $recid_bag, $recid_mbl,  $total_jam, $pekerjaan, $alasan_over, $keterangan";

			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_mbl'				=> $recid_mbl,
				'tgl_lembur'			=> $tgl_lembur,
				'kategori'				=> $kategori,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'keterangan'			=> $keterangan,
				'alasan_over'			=> $alasan_over,
			);
			$this->m_hris->plembur_pinsert($data);
			$get_recid_plembur = $this->m_hris->get_plembur();
			foreach ($get_recid_plembur as $id) {
				$recid_plembur = $id->recid_plembur;
			}
			for ($i = 0; $i < count($this->input->post('pekerjaan')); $i++) {
				// echo $pekerjaan[$i]." - ".$target[$i];
				$data2 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_plembur'			=> $recid_plembur,
					'pekerjaan'				=> $pekerjaan[$i],
					'target_kerja'				=> $target[$i]
				);
				$this->m_hris->dlembur_insert($data2);
			}
			redirect('Karyawan/plembur_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_plembur = $this->input->post('recid_lembur');
			$recid_bag = $this->input->post('recid_bag');
			$kategori = $this->input->post('kategori');
			$recid_mbl = $this->input->post('recid_mbl');
			$total_jam = $this->input->post('total_jam');
			$jml_orang = $this->input->post('jml_orang');
			$pekerjaan = $this->input->post('pekerjaan');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');

			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_mbl'				=> $recid_mbl,
				'tgl_lembur'			=> $tgl_lembur,
				'kategori'				=> $kategori,
				'total_jam'				=> $total_jam,
				'jml_orang'				=> $jml_orang,
				'pekerjaan'				=> $pekerjaan,
				'keterangan'			=> $keterangan,
				'alasan_over'			=> $alasan_over,
			);
			$this->m_hris->plembur_pupdate($data, $recid_plembur);
			redirect('Karyawan/plembur_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_delete()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->uri->segment(3);
			$data1 = array(
				'plembur_delete'	=> '1',
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->plembur_pupdate($data1, $recid_plembur);
			redirect('Karyawan/plembur_view');
		} else {
			redirect('Auth/keluar');
		}
	}

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
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$recid_bag = $user->recid_bag;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$department = $user->nama_department;
			}

			if ($role == '1' or $role == '5' or $role == '7' or $role == '8') {
				$data['role'] = "";
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/master/kuartal_budget', $data);
				$this->load->view('layout/a_footer');
			} else {
				if ($tingkatan > 9) {
					$data['role'] = " b.department = '$department'";
				} else {
					if ($usr == '1094') {
						$data['role'] = " (b.recid_struktur = '20' or b.recid_struktur = '22')";
					} else {
						$data['role'] = " b.recid_struktur = '$struktur'";
					}
				}
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/report/r_kuartal_budget_single', $data);
				$this->load->view('layout/a_footer');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_pengajuan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/report/r_pengajuan');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_ppengajuan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$awal = $this->input->post('tgl_awal');
			$akhir = $this->input->post('tgl_akhir');
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

			if ($role == '1' or $role == '5') {
				$data['realisasi'] = $this->m_hris->plembur_by_role($awal, $akhir);
			} else {
				if ($tingkatan > 9) {
					$data['realisasi'] = $this->m_hris->plembur_by_dept($department, $awal, $akhir);
				} else {
					if ($usr == '1094') {
						$data['realisasi'] = $this->m_hris->plembur_by_sales($awal, $akhir);
					} else {
						$data['realisasi'] = $this->m_hris->plembur_by_str($struktur, $awal, $akhir);
					}
				}
			}
			$data['tgl_awal'] = $awal;
			$data['tgl_akhir'] = $akhir;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/report/r_ppengajuan', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_crash()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['pengajuan'] = $this->m_hris->plembur_crash();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pengajuan/plembur_crash', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function plembur_adjust()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->uri->segment(3);
			$data['lembur'] = $this->m_hris->plembur_by_id($recid_plembur);
			$data['bagian'] = $this->m_hris->bagian_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/pengajuan/plembur_adjust', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}
	// ################################################### REALISASI LEMBUR #########################################################

	public function fast_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['pengajuan'] = $this->m_hris->realisasi_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/fast/fast_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fast_realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_view();
			$user = $this->session->userdata('kar_id');
			$cek_adm = $this->m_hris->dept_by_user($user);
			foreach ($cek_adm->result() as $de) {
				$dept = $de->department;
			}
			$data['dept']	= $dept;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/fast/fast_realisasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fast_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl_lembur = $this->input->post('tgl_lembur');
			$recid_bag = $this->input->post('recid_bag');
			$recid_mbl = $this->input->post('recid_mbl');
			$kategori = $this->input->post('kategori');
			$total_jam = $this->input->post('total_jam');
			$pekerjaan = $this->input->post('pekerjaan');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');

			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_mbl'				=> $recid_mbl,
				'tgl_lembur'			=> $tgl_lembur,
				'kategori'				=> $kategori,
				'total_jam'				=> $total_jam,
				'pekerjaan'				=> $pekerjaan,
				'keterangan'			=> $keterangan,
				'alasan_over'			=> $alasan_over,
			);
			$this->m_hris->plembur_pinsert($data);
			$get_plembur = $this->m_hris->get_plembur();
			foreach ($get_plembur as $get_plembur) {
				$recid_plembur = $get_plembur->recid_plembur;
			}
			$data2 = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_plembur'			=> $recid_plembur,
				'realisasi_jam'			=> $total_jam,
				'pekerjaan'				=> $pekerjaan,
				'alasan_over'			=> $alasan_over,
				'note'					=> $keterangan,
			);
			$this->m_hris->realisasi_pinsert($data2);
			redirect('Karyawan/fast_realisasi');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fast_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$user = $this->session->userdata('kar_id');
			$cek_adm = $this->m_hris->dept_by_user($user);
			foreach ($cek_adm->result() as $de) {
				$dept = $de->department;
			}
			$data['dept']	= $dept;
			$data['lembur'] = $this->m_hris->realisasi_by_recid($recid);
			$data['bagian'] = $this->m_hris->bagian_view($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/fast/fast_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fast_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->input->post('recid_plembur');
			$recid_lembur = $this->input->post('recid_lembur');
			$recid_mbl = $this->input->post('recid_mbl');
			$tgl_lembur = $this->input->post('tgl_lembur');
			$realisasi_jam = $this->input->post('total_jam');
			$pekerjaan = $this->input->post('pekerjaan');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			// echo $recid_plembur."-".$recid_lembur;
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_plembur'			=> $recid_plembur,
				'realisasi_jam'			=> $realisasi_jam,
				'pekerjaan'				=> $pekerjaan,
				'alasan_over'			=> $alasan_over,
				'note'					=> $keterangan,
			);
			$this->m_hris->realisasi_pupdate($data, $recid_lembur);

			$data2 = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_mbl'				=> $recid_mbl,
				'tgl_lembur'			=> $tgl_lembur,
				'total_jam'				=> $realisasi_jam,
				'pekerjaan'				=> $pekerjaan,
				'keterangan'			=> $keterangan,
				'alasan_over'			=> $alasan_over,
			);
			$this->m_hris->plembur_pupdate($data2, $recid_lembur);
			redirect('Karyawan/fast_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function unrealisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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

			if ($role == '1' or $role == '5') {
				$data['pengajuan'] = $this->m_hris->belum_realisasi_role();
			} else {
				if ($tingkatan > 9) {
					$data['pengajuan'] = $this->m_hris->belum_realisasi_dept($department);
				} else {
					if ($usr == '1094') {
						$data['pengajuan'] = $this->m_hris->belum_realisasi_sales();
					} else {
						$data['pengajuan'] = $this->m_hris->belum_realisasi_str($struktur);
					}
				}
			}
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/realisasi/unrealisasi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function unrealisasi_payroll()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {

			$data['pengajuan'] = $this->m_hris->realisasi_by_payroll();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/realisasi/unrealisasi_payroll', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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

			if ($role == '1' or $role == '5') {
				$data['pengajuan'] = $this->m_hris->realisasi_by_role($bagian);
			} else {
				if ($tingkatan > 9) {
					$data['pengajuan'] = $this->m_hris->realisasi_by_dept($department);
				} else {
					if ($usr == '1094') {
						$data['pengajuan'] = $this->m_hris->realisasi_by_sales();
					} else {
						$data['pengajuan'] = $this->m_hris->realisasi_by_str($struktur);
					}
				}
			}
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/realisasi/realisasi_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$user = $this->session->userdata('kar_id');
			$cek_adm = $this->m_hris->dept_by_user($user);
			foreach ($cek_adm->result() as $de) {
				$dept = $de->department;
			}
			$data['dept']	= $dept;
			$data['pengajuan'] = $this->m_hris->plembur_by_recid($recid);
			$data['detail'] = $this->m_hris->dlembur_view($recid);
			$data['bagian'] = $this->m_hris->bagian_view($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/realisasi/realisasi_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->input->post('recid_plembur');
			$mbl = $this->input->post('recid_mbl');
			$realisasi_jam = $this->input->post('realisasi_jam');
			if ($realisasi_jam == '') {
				$realisasi_jam = null;
			} else {
				$realisasi_jam = $realisasi_jam;
			}
			$realisasi_orang = $this->input->post('realisasi_orang');
			$pekerjaan = $this->input->post('pekerjaan');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			$hasil = $this->input->post('hasil');
			$recid_detlembur = $this->input->post('recid_detlembur');

			$role = $this->session->userdata('role_id');
			if ($role != '5') {
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_plembur'			=> $recid_plembur,
					'realisasi_orang'		=> $realisasi_orang,
					'pekerjaan'				=> $pekerjaan,
					'alasan_over'			=> $alasan_over,
					'note'					=> $keterangan,
				);
			} else {
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_plembur'			=> $recid_plembur,
					'realisasi_jam'			=> $realisasi_jam,
					'realisasi_orang'		=> $realisasi_orang,
					'pekerjaan'				=> $pekerjaan,
					'alasan_over'			=> $alasan_over,
					'note'					=> $keterangan,
				);
			}
			$this->m_hris->realisasi_pinsert($data);

			for ($i = 0; $i < count($this->input->post('recid_detlembur')); $i++) {
				$data2 = array(
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d h:i:s'),
					'hasil'					=> $hasil[$i]
				);
				$this->m_hris->dlembur_pupdate($data2, $recid_detlembur[$i]);
			}
			redirect('Karyawan/realisasi_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$data['realisasi'] = $this->m_hris->realisasi_by_recid($recid);
			$data['bagian'] = $this->m_hris->bagian_view($recid);
			$user = $this->session->userdata('kar_id');
			$cek_adm = $this->m_hris->dept_by_user($user);
			foreach ($cek_adm->result() as $de) {
				$dept = $de->department;
			}
			$plembur = $this->m_hris->realisasi_by_recid($recid);
			foreach ($plembur as $plmbur) {
				$recid_plembur = $plmbur->recid_plembur;
			}
			// echo $recid_plembur;
			$data['dept']	= $dept;
			$data['detail'] = $this->m_hris->dlembur_view($recid_plembur);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/realisasi/realisasi_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function realisasi_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_plembur = $this->input->post('recid_plembur');
			$recid_lembur = $this->input->post('recid_lembur');
			$realisasi_jam = $this->input->post('realisasi_jam');
			$realisasi_orang = $this->input->post('realisasi_orang');
			$pekerjaan = $this->input->post('pekerjaan');
			$alasan_over = $this->input->post('alasan_over');
			$keterangan = $this->input->post('keterangan');
			$recid_detlembur = $this->input->post('recid_detlembur');
			$hasil = $this->input->post('hasil');
			// echo $realisasi_jam;
			$role = $this->session->userdata('role_id');
			if ($role != '5') {
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_plembur'			=> $recid_plembur,
					'realisasi_orang'		=> $realisasi_orang,
					'pekerjaan'				=> $pekerjaan,
					'alasan_over'			=> $alasan_over,
					'note'					=> $keterangan,
				);

				for ($i = 0; $i < count($this->input->post('recid_detlembur')); $i++) {
					$data2 = array(
						'mdf_by'				=> $this->session->userdata('kar_id'),
						'mdf_date'				=> date('y-m-d h:i:s'),
						'hasil'					=> $hasil[$i]
					);
					$this->m_hris->dlembur_pupdate($data2, $recid_detlembur[$i]);
				}
			} else {
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_plembur'			=> $recid_plembur,
					'realisasi_jam'			=> $realisasi_jam,
					'realisasi_orang'		=> $realisasi_orang,
					'pekerjaan'				=> $pekerjaan,
					'alasan_over'			=> $alasan_over,
					'note'					=> $keterangan,
				);
			}
			$this->m_hris->realisasi_pupdate($data, $recid_lembur);

			redirect('Karyawan/realisasi_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_realisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['karyawan'] = $this->m_hris->totkar_bag();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/report/r_realisasi');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_realisasi2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['karyawan'] = $this->m_hris->totkar_bag();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/report/r_realisasi2');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_newrealisasi2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$tgl_awal = $this->input->post('tgl_awal');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$data['tgl_awal'] = $tgl_awal;
			$data['tgl_akhir'] = $tgl_akhir;
			$data['realisasi'] = $this->m_hris->new_realisasi($tgl_awal, $tgl_akhir);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('master_budget/report/r_newrealisasi2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_newrealisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$tgl_awal = $this->input->post('tgl_awal');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
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

			$data['tgl_awal'] = $tgl_awal;
			$data['tgl_akhir'] = $tgl_akhir;
			if ($role == '1' or $role == '5') {
				$data['role'] = "";
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/report/r_newrealisasi', $data);
				$this->load->view('layout/a_footer');
			} else {
				if ($tingkatan > 9) {
					$data['role'] = "and b.department = '$department'";
				} else {
					if ($usr == '1094') {
						$data['role'] = "and (b.recid_struktur = '20' or b.recid_struktur = '22')";
					} else {
						$data['role'] = "and b.recid_struktur = '$struktur'";
					}
				}
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/report/r_newrealisasi', $data);
				$this->load->view('layout/a_footer');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_prealisasi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$tgl_awal = $this->input->post('tgl_awal');
			$tgl_akhir = $this->input->post('tgl_akhir');
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
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

			$data['tgl_awal'] = $tgl_awal;
			$data['tgl_akhir'] = $tgl_akhir;
			if ($role == '1' or $role == '5') {
				$data['role'] = "";
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/report/r_prealisasi', $data);
				$this->load->view('layout/a_footer');
			} else {
				if ($tingkatan > 9) {
					$data['role'] = " b.department = '$department'";
				} else {
					if ($usr == '1094') {
						$data['role'] = "(b.recid_struktur = '20' or b.recid_struktur = '22')";
					} else {
						$data['role'] = "and b.recid_struktur = '$struktur'";
					}
				}
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('master_budget/report/r_prealisasi_single', $data);
				$this->load->view('layout/a_footer');
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### REPORT HR ##############################################################

	public function r_totkar()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->totkar_bag();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_totkar', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_totspm()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->spm_view();
			$data['menu']	= "Dinamis Data Karyawan SPM";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis_spm', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_totcci()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->cci_view();
			$data['menu']	= "Dinamis Data Karyawan CCI";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_totkar_male()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->totkar_bag_male();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_totkar_male', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_totkar_female()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->totkar_bag_female();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_totkar_male', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function vaksin_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$status = $this->uri->segment(3);
			if ($status == 'Sudah') {
				$status_v = '1';
			} else {
				$status_v = '0';
			}
			$data['karyawan'] = $this->m_hris->report_vaksin($status_v);
			$data['menu']	= "Data Karyawan " . $status . " Vaksin Covid19";
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function exp_legal()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->exp_legal();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('exp_legal', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_hr()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['department'] = $this->m_hris->direktorat();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_hr', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_per_bagian()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['bagian'] = $this->m_hris->bagian_list();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/rekap_per_bagian', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_hc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['sts_aktif'] = $this->m_hris->list_sts_aktif();
			$data['jenkel'] = $this->m_hris->list_jenkel();
			$data['dept_group'] = $this->m_hris->list_dept_group();
			$data['department'] = $this->m_hris->department_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$data['pendidikan'] = $this->m_hris->list_pendidikan();
			$data['agama'] = $this->m_hris->list_agama();
			$data['disc'] = $this->m_hris->list_disc();
			$data['disc_type'] = $this->m_hris->list_disc_type();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_hc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_phc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$text = "";
			$sts_kary = $this->input->post('sts_kary');	// karyawan aktif
			$sts_kary1 = $this->input->post('sts_kary1');	// karyawan aktif
			$sts_kary2 = $this->input->post('sts_kary2');	// karyawan tidak aktif
			$jenkel = $this->input->post('jenkel');	// jenis kelamin
			$divisi = $this->input->post('divisi');	// divisi
			$departement = $this->input->post('departement');	// department
			$bagian = $this->input->post('bagian');	// bagian
			$sts_kawin = $this->input->post('sts_kawin');	// sts_kawin
			$tanggungan = $this->input->post('tanggungan');	// tanggungan
			$tanggungan2 = $this->input->post('tanggungan2');	// filter tanggungan range/ default
			$anak_min = $this->input->post('anak_min');	// filter tanggungan range anak min
			$anak_max = $this->input->post('anak_max');	// filter tanggungan range anak max
			$default_tunjangan = $this->input->post('default_tunjangan');	// filter tanggungan default tunjangan
			if ($tanggungan2 == 'range') {
				$tmin = $anak_min;
				$tmax = $anak_max;
			} else {
				$tanggungan_def = $default_tunjangan;
			}
			$jbtn = $this->input->post('jbtn');	//  jabatan
			$pendidikan = $this->input->post('pendidikan');	//  pendidikan
			$agama = $this->input->post('agama');	//  agama
			$usia = $this->input->post('usia');	// filter usia range/ default
			$usia_min = $this->input->post('usia_min');	// filter tanggungan range usia min
			$usia_max = $this->input->post('usia_max');	// filter tanggungan range usia max
			$default_usia = $this->input->post('default_usia');	// filter tanggungan default usia
			if ($usia == 'range') {
				$umin = $usia_min;
				$umax = $usia_max;
			} else {
				$usia_def = $default_usia;
			}
			$masker = $this->input->post('masker');	// filter masker range/ default
			$masker_min = $this->input->post('masker_min');	// filter tanggungan range masker min
			$masker_max = $this->input->post('masker_max');	// filter tanggungan range masker max
			$default_masker = $this->input->post('default_masker');	// filter tanggungan default masker
			if ($masker == 'range') {
				$mmin = $masker_min;
				$mmax = $masker_max;
			} else {
				$masker_def = $default_masker;
			}
			//GENERATE WHERE CLAUSE
			if (!empty($sts_kary)) {
				$cnt = count($sts_kary);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.sts_aktif = '$sts_kary[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.sts_aktif = '$sts_kary[$i]'";
						} else {
							$text .= "p.sts_aktif = '$sts_kary[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($jenkel)) {
				// $text = "$text and p.jenkel = '$jenkel'";
				$cnt = count($jenkel);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.jenkel = '$jenkel[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.jenkel = '$jenkel[$i]'";
						} else {
							$text .= "p.jenkel = '$jenkel[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($divisi)) {
				// $text = "$text and b.dept_group = '$divisi'";
				$cnt = count($divisi);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "d.dept_group = '$divisi[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "d.dept_group = '$divisi[$i]'";
						} else {
							$text .= "d.dept_group = '$divisi[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($departement)) {
				// $text = "$text and b.department = '$departement'";
				$cnt = count($departement);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "d.nama_department = '$departement[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "d.nama_department = '$departement[$i]'";
						} else {
							$text .= "d.nama_department = '$departement[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($bagian)) {
				// $text = "$text and b.recid_bag = '$bagian'";
				$cnt = count($bagian);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "b.recid_bag = '$bagian[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "b.recid_bag = '$bagian[$i]'";
						} else {
							$text .= "b.recid_bag = '$bagian[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($sts_kawin)) {
				// $text = "$text and p.sts_nikah = '$sts_kawin'";
				$cnt = count($sts_kawin);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.sts_nikah = '$sts_kawin[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.sts_nikah = '$sts_kawin[$i]'";
						} else {
							$text .= "p.sts_nikah = '$sts_kawin[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($jbtn)) {
				// $text = "$text and j.recid_jbtn = '$jbtn'";
				$cnt = count($jbtn);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "j.recid_jbtn = '$jbtn[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "j.recid_jbtn = '$jbtn[$i]'";
						} else {
							$text .= "j.recid_jbtn = '$jbtn[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($pendidikan)) {
				// $text = "$text and p.pendidikan = '$pendidikan'";
				$cnt = count($pendidikan);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.pendidikan = '$pendidikan[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.pendidikan = '$pendidikan[$i]'";
						} else {
							$text .= "p.pendidikan = '$pendidikan[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($agama)) {
				// $text = "$text and p.agama = '$agama'";
				$cnt = count($agama);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.agama = '$agama[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.agama = '$agama[$i]'";
						} else {
							$text .= "p.agama = '$agama[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($disc)) {
				$cnt = count($disc);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.profile_disc = '$disc[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.profile_disc = '$disc[$i]'";
						} else {
							$text .= "p.profile_disc = '$disc[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($disc_type)) {
				// $text = "$text and p.agama = '$agama'";
				$cnt = count($disc_type);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.profile_type = '$disc_type[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.profile_type = '$disc_type[$i]'";
						} else {
							$text .= "p.profile_type = '$disc_type[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}


			if (!empty($usia)) {
				if ($usia == 'range') {
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_lahir, CURDATE()) between '$umin' and '$umax'";
				} else {
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_lahir, CURDATE()) ='$usia_def'";
				}
			} else {
				$text = "$text";
			}

			if (!empty($masker)) {
				if ($masker == 'range') {
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_m_kerja, CURDATE()) between '$mmin' and '$mmax'";
				} else {
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_m_kerja, CURDATE()) ='$masker_def'";
				}
			} else {
				$text = "$text";
			}

			// echo $text;
			$data['karyawan'] = $this->m_hris->r_hc($text);
			// echo "$report";

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['menu']	= "Employee Matching Profile Result";
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}



	public function filter_dept()
	{
		// Ambil data ID bagian yang dikirim via ajax post
		$nama_dept = $this->m_hris->department();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";
		foreach ($nama_dept as $data) {
			$lists .= "<option value='" . $data->department . "'>" . $data->department . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_dept' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function filter_bagian()
	{
		// Ambil data ID bagian yang dikirim via ajax post
		$nama_bag = $this->m_hris->bagian_view();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";
		foreach ($nama_bag as $data) {
			$lists .= "<option value='" . $data->recid_bag . "'>" . $data->nama_bag . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_bag' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function filter_struktur()
	{
		// Ambil data ID bagian yang dikirim via ajax post
		$nama_str = $this->m_hris->struktur_view();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";
		foreach ($nama_str as $data) {
			$lists .= "<option value='" . $data->recid_struktur . "'>" . $data->nama_struktur . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_str' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}


	public function tidak_gesek()
	{
		$date_work =  $this->input->post('sejak');
		$filter1 = $this->input->post('filter1');
		$filter2 = $this->input->post('filter2');
		$tipe = $this->input->post('tipe');
		$karyawan = array();
		$absen = array();
		$mangkir = array();
		$kosong = array();
		if ($tipe == 'Barcode') {
			if ($filter1 == "Semua") {
				// echo "load semua data";
				$emp = $this->m_hris->allabsen_semua();
				$ijin = $this->m_hris->allmangkir($date_work);
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$emp = $this->m_hris->allabsen_department($filter2);
				$ijin = $this->m_hris->allmangkir_department($filter2, $date_work);
			} else {
				// echo "looad data bagian by filter2";
				$emp = $this->m_hris->allabsen_bagian($filter2);
				$ijin = $this->m_hris->allmangkir_bagian($filter2, $date_work);
			}
			$abs = $this->m_hris->raw_absen($date_work);
			foreach ($abs->result() as $abs) {
				$abs = $abs->nik;
				array_push($absen, $abs);
			}
			//filtering
			foreach ($emp as $emp) {
				$emp = $emp->nik;
				array_push($karyawan, $emp);
			}

			foreach ($ijin as $ijin) {
				$ijin = $ijin->NIK;
				array_push($mangkir, $ijin);
			}
		} else {
			if ($filter1 == "Semua") {
				// echo "load semua data";
				$emp = $this->m_hris->allaccess_semua();
				$ijin = $this->m_hris->allmangkir($date_work);
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$emp = $this->m_hris->allaccess_department($filter2);
				$ijin = $this->m_hris->allmangkir_department($filter2, $date_work);
			} else if ($filter1 == "Struktur") {
				// echo "load data department by filter2";
				$emp = $this->m_hris->allaccess_struktur($filter2);
				$ijin = $this->m_hris->allmangkir_struktur($filter2, $date_work);
			} else {
				// echo "looad data bagian by filter2";
				$emp = $this->m_hris->allaccess_bagian($filter2);
				$ijin = $this->m_hris->allmangkir_bagian($filter2, $date_work);
			}
			$date_work = $date_work;
			$date_work = date("d/m/Y", strtotime($date_work));
			// echo $date_work;
			$abs = $this->m_hris->raw_attandance($date_work);
			foreach ($abs->result() as $abs) {
				$abs = $abs->SSN;
				array_push($absen, $abs);
			}
			//filtering
			foreach ($emp as $emp) {
				$emp = $emp->ssn;
				array_push($karyawan, $emp);
			}

			foreach ($ijin as $ijin) {
				$ijin = $ijin->NIK;
				array_push($mangkir, $ijin);
			}
		}

		$result1 = array_diff($karyawan, $absen);
		$result = array_diff($result1, $mangkir);
		// print_r($absen);
		foreach ($result as $key) {
			$data = $this->m_hris->karyawan_by_nik2($key);
			foreach ($data as $data) {
				$data =  array($data->nama_karyawan, $data->nama_bag, $data->nik);
				array_push($kosong, $data);
			}
		}
		$data['kosong'] = $kosong;
		$data['tgl'] = $date_work;
		$usr = $this->session->userdata('kar_id');
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('absen/report/r_notexist', $data);
		$this->load->view('layout/a_footer');
	}

	public function r_notexist()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_tidak_gesek');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function tidak_lengkap()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_tidak_lengkap');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_notfull()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$sejak =  $this->input->post('sejak');
			$sampai =  $this->input->post('sampai');
			$filter1 = $this->input->post('filter1');
			$filter2 = $this->input->post('filter2');
			$tipe = $this->input->post('tipe');

			if ($tipe == 'Barcode') {
				if ($filter1 == "Semua") {
					// echo "load semua data";
					$data['nama'] = $this->m_hris->notfull_semua($sejak, $sampai);
				} else if ($filter1 == "Department") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_hris->notfull_department($filter2, $sejak, $sampai);
				} else if ($filter1 == "Struktur") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_hris->notfull_struktur($filter2, $sejak, $sampai);
				} else {
					// echo "looad data bagian by filter2";
					$data['nama'] = $this->m_hris->notfull_bagian($filter2, $sejak, $sampai);
				}
			} else {
				if ($filter1 == "Semua") {
					// echo "load semua data";
					$data['nama'] = $this->m_hris->notfulla_semua($sejak, $sampai);
				} else if ($filter1 == "Department") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_hris->notfulla_department($filter2, $sejak, $sampai);
				} else if ($filter1 == "Struktur") {
					// echo "load data department by filter2";
					$data['nama'] = $this->m_hris->notfulla_struktur($filter2, $sejak, $sampai);
				} else {
					// echo "looad data bagian by filter2";
					$data['nama'] = $this->m_hris->notfulla_bagian($filter2, $sejak, $sampai);
				}
			}
			$data['sejak'] = $sejak;
			$data['sampai'] = $sampai;
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_notfull', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function notif()
	{
		$filter = $this->input->post('filter');
		$role = $this->input->post('role');
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		if ($role == '2') {
			$kategori =  "and jenis_perjanjian = 'Karyawan'";
		} else {
			$kategori = "";
		}
		if ($filter == 'sebulan') {
			$query = $this->m_hris->notif_sebulan($kategori);
		} else if ($filter == 'seminggu') {
			$query = $this->m_hris->notif_seminggu($kategori);
		} else if ($filter == 'tigahari') {
			$query = $this->m_hris->notif_tigahari($kategori);
		} else if ($filter == 'sehari') {
			$query = $this->m_hris->notif_sehari($kategori);
		} else if ($filter == 'empatlima') {
			$query = $this->m_hris->notif_empatlima($kategori);
		} else if ($filter == 'enampuluh') {
			$query = $this->m_hris->notif_enampuluh($kategori);
		} else {
			$query = $this->m_hris->notif_today($kategori);
		}

		$data = [];
		$i = 1;
		foreach ($query->result() as $r) {
			$data[] = array(
				$i++,
				$r->judul_perjanjian,
				$r->tgl_a_legal,
				'<a target="__blank" href="' . base_url() . 'images/legal/' . $r->scan_perjanjian . '">' . $r->scan_perjanjian . '</a>',
				$r->nama_karyawan
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

	public function notif_expedisi()
	{
		$role = $this->session->userdata('role_id');
		$expedisi = $this->input->post('expedisi');
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$query = $this->m_hris->notif_karir_tgl($expedisi);
		$data = [];
		$i = 1;
		foreach ($query->result() as $r) {
			$bagian = $r->indeks_hr;
			$bagian = substr($bagian, strpos($bagian, " ") + 1);

			$jabatan = $r->indeks_jabatan;
			$jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

			$golongan = $r->nama_golongan;
			$golongan = substr($golongan, strpos($golongan, " ") + 1);

			if ($role == '1' or $role == '2') {
				$bagian = $r->indeks_hr;
				$jabatan = $r->indeks_jabatan;
				$golongan = $r->nama_golongan;
			}
			$data[] = array(
				$i++,
				$r->nik,
				$r->nama_karyawan,
				$bagian,
				$jabatan,
				$golongan,
				$r->kategori,
				$r->tgl_m_karir,
				"<a href='" . base_url() . "images/legal/" . $r->scan_perjanjian . "' target='__blank'>" . $r->scan_perjanjian . "</a>"
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

	public function r_legal()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/r_legal');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_legals()
	{
		$awal = $this->input->post('awal');
		$akhir = $this->input->post('akhir');
		$jenis = $this->input->post('jenis');
		// echo "OK";
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$query = $this->m_hris->legal_jenis($awal, $akhir, $jenis);

		$data = [];
		$i = 1;
		foreach ($query->result() as $r) {
			$data[] = array(
				$i++,
				$r->judul_perjanjian,
				$r->tgl_a_legal,
				$r->sts_legal,
				$r->scan_perjanjian,
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

	/*public function resume_karyawan()
		{
			$logged_in = $this->session->userdata('logged_in');
			if($logged_in == 1)
			{
				$data['karyawan'] = $this->m_hris->karyawan_view();
				$usr = $this->session->userdata('kar_id');
				$data['cek_usr'] = $this->m_hris->cek_usr($usr);
				$notif = $this->m_hris->temp_karyawan_views();
				$notif = $notif->num_rows();
				$data['notif_edit'] = $notif;
				$this->load->view('layout/a_header');
				$this->load->view('layout/menu_super', $data);
				$this->load->view('karyawan/karyawan/karyawan_viewbeta',$data);
				$this->load->view('layout/a_footer');
			}
			else
			{
				redirect('Auth/keluar');
			}
		}*/

	public function report_hr()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_hr', $data);
			$this->load->view('layout/a_footer');
		} else {
			echo "Session Anda Habis, Silakan Login Kembali";
		}
	}

	public function report_absensi()
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

	public function report_legal()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/r_legal');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function report_training()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['training'] = $this->m_hris->training_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/r_training', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function report_hc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_hc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function data_payroll()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_upah();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/data_upah', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_dinamis()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['menu']	= "Dinamis Data Karyawan";
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			$role = $this->session->userdata('role_id');
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
				$dept_group = $user->dept_group;
			}
			if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '31' or $role == '32' or $role == '34') {
				$data['karyawan'] = $this->m_hris->karyawan_view();
			} else if ($role == '30' or $role == '26' or $role == '35') {
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
				$data['karyawan'] = $this->m_hris->karyawan_view_by_bagian($bagian);
			} else if ($role == '23' or $role == '41') {	//USER CHEKING ROLE BY JABATAN
				if ($role == '41') {
					// custom all produksi by asman
					//pic struktur = dadan rakhmat (920)
					// $data['karyawan'] = $this->m_hris->prd_karyawan_by_atasan('920')->result();
					if ($tingkatan > '7')	// ASS-MAN UP
					{
						// echo "asman";
						$recid_karyawan = $this->session->userdata('kar_id');
						// echo $recid_karyawan;
						$data['karyawan'] = $this->m_hris->karyawan_view_by_atasan($recid_karyawan)->result();
					} else {
						$recid_karyawan = $this->session->userdata('kar_id');
						$data['karyawan'] = $this->m_hris->karyawan_view_by_id($recid_karyawan)->result();
					}
				} else {
					if ($tingkatan > '7')	// ASS-MAN UP
					{
						// echo "asman";
						$recid_karyawan = $this->session->userdata('kar_id');
						// echo $recid_karyawan;
						$data['karyawan'] = $this->m_hris->karyawan_view_by_atasan($recid_karyawan)->result();
					} else {
						$recid_karyawan = $this->session->userdata('kar_id');
						$data['karyawan'] = $this->m_hris->karyawan_view_by_id($recid_karyawan)->result();
					}
				}
			} else if ($role == '37') { //GM DEPT GROUP (BO, FO, MO)
				$data['karyawan'] = $this->m_hris->karyawan_view_by_dept_group($dept_group)->result();
			} else {
				// echo $bagian;
				$bagian = "'" . $bagian . "'";
				$data['karyawan'] = $this->m_hris->karyawan_view_by_bagian($bagian);
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}


	public function r_belum_lengkap()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->blm_lengkap();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_belum_lengkap', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_jmangkir()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			// $data['department'] = $this->m_hris->department();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_mangkir');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_mangkir()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['sejak'] = $this->input->post('sejak');
			$data['sampai'] = $this->input->post('sampai');
			$sejak = $this->input->post('sejak');
			$sampai = $this->input->post('sampai');
			$filter1 = $this->input->post('filter1');
			$filter2 = $this->input->post('filter2');
			if ($filter1 == "Semua") {
				$data['nama'] = $this->m_hris->jmangkir_all($sejak, $sampai);
			} else if ($filter1 == "Department") {
				// echo "load data department by filter2";
				$data['nama'] = $this->m_hris->jmangkir_dept($filter2, $sejak, $sampai);
			} else if ($filter1 == "Struktur") {
				// echo "load data department by filter2";
				$data['nama'] = $this->m_hris->jmangkir_str($filter2, $sejak, $sampai);
			} else {
				// echo "looad data bagian by filter2";
				$data['nama'] = $this->m_hris->jmangkir_bag($filter2, $sejak, $sampai);
			}
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('absen/report/r_jmangkir', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_blm_sk()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->list_blm_sk();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_belum_sk', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_open()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['recruitment'] = $this->m_hris->open_recruitment();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment_open', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function legal_open()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->legal_open();
			$data['judul'] = 'Perizinan & Perjanjian Chitose Open';
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal_open', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_perjanjian()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->perjanjian_open();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['judul'] = 'Perjanjian Open Chitose';
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal_open', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function r_perizinan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['legal'] = $this->m_hris->perizinan_open();
			$data['judul'] = 'Perizinan Open Chitose';
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal_open', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function report_sanksi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['menu']	= "Report Sanksi";
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			$role = $this->session->userdata('role_id');
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '31') {
				$data['karyawan'] = $this->m_hris->report_sanksi()->result();
			} else {

				$data['karyawan'] = $this->m_hris->report_sanksi_bagian($bagian)->result();
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/report/r_sanksi', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function words()
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$section->addText('Hello World !');

		$writer = new Word2007($phpWord);

		$filename = 'document';

		header('Content-Type: application/msword');
		header('Content-Disposition: attachment;filename="' . $filename . '.docx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function gen_covid()
	{
		$all = $this->m_hris->gen_user_covid();
		foreach ($all->result() as $emp) {
			$recid_karyawan	= $emp->recid_karyawan;
			$data = array(
				"covid_uname"		=> $emp->nik,
				"covid_pwd"			=> md5($emp->nik),
				"covid_role"		=> 3
			);
			$this->m_hris->karyawan_update($data, $recid_karyawan);
		}
		redirect('Karyawan');
	}


	public function docsecre_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$struktur = $this->m_hris->struktur_by_karyawan($usr);
			foreach ($struktur->result() as $d) {
				$recid_struktur = $d->recid_struktur;
			}
			$data['recid_struktur'] = $recid_struktur;
			$data['doc'] = $this->m_hris->docsecre_view();
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/docsecre_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function doc_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			if ($role == '1') {
				// $data['struktur'] = $this->m_hris->struktur_view();
				$data['recid_struktur'] = "";
				$data['nama_struktur'] = "";
			} else {
				$struktur = $this->m_hris->struktur_by_karyawan($usr);
				foreach ($struktur->result() as $d) {
					$recid_struktur = $d->recid_struktur;
					$nama_struktur = $d->nama_struktur;
				}
				$data['recid_struktur'] = $recid_struktur;
				$data['nama_struktur'] = $nama_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/docsecre_insert');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function docsecre_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		$recid_doc = $this->uri->segment(3);
		$doc = $this->m_hris->doc_by_id($recid_doc);
		foreach ($doc->result() as $d) {
			$recid_struktur = $d->recid_struktur;
			$nama_struktur = $d->nama_struktur;
			$recid_karyawan = $d->recid_karyawan;
			$tujuan = $d->tujuan;
			$deskripsi = $d->deskripsi;
			$nomor_doc = $d->no_doc;
			$recid_doc = $d->recid_doc;
		}
		if ($logged_in == 1) {
			$usr = $this->session->userdata('kar_id');
			$role = $this->session->userdata('role_id');
			if ($role == '1') {
				$data['recid_struktur'] = $recid_struktur;
				$data['nama_struktur'] = $nama_struktur;
			} else {
				$struktur = $this->m_hris->struktur_by_karyawan($usr);
				foreach ($struktur->result() as $d) {
					$recid_struktur = $d->recid_struktur;
					$nama_struktur = $d->nama_struktur;
				}
				$data['recid_struktur'] = $recid_struktur;
				$data['nama_struktur'] = $nama_struktur;
			}
			$data['recid_karyawan'] = $recid_karyawan;
			$data['tujuan'] = $tujuan;
			$data['deskripsi'] = $deskripsi;
			$data['nomor_doc'] = $nomor_doc;
			$data['recid_doc'] = $recid_doc;
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('legal/docsecre_edit');
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function emp_by_struktur()
	{
		$recid_struktur = $this->input->post('recid_struktur');
		$karyawan = $this->m_hris->karyawan_by_struktur($recid_struktur);
		$lists = "<option value=''>Pilih</option>";
		foreach ($karyawan->result() as $data) {
			$lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function emp_aktif_all_edit()
	{
		$recid_karyawan = $this->input->post('recid_karyawan');
		$karyawan = $this->m_hris->karyawan_view();
		$lists = "<option value=''>Pilih</option>";
		foreach ($karyawan as $data) {
			if ($data->recid_karyawan == $recid_karyawan) {
				$lists .= "<option value='" . $data->recid_karyawan . "' selected ='selected'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
			} else {
				$lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
			}
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function emp_by_struktur_edit()
	{
		$recid_karyawan = $this->input->post('recid_karyawan');
		$recid_struktur = $this->input->post('recid_struktur');
		$karyawan = $this->m_hris->karyawan_by_struktur($recid_struktur);
		$lists = "<option value=''>Pilih</option>";
		foreach ($karyawan->result() as $data) {
			if ($data->recid_karyawan == $recid_karyawan) {
				$lists .= "<option value='" . $data->recid_karyawan . "' selected ='selected'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
			} else {
				$lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
			}
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function emp_aktif_all()
	{
		$karyawan = $this->m_hris->karyawan_view();
		$lists = "<option value=''>Pilih</option>";
		foreach ($karyawan as $data) {
			$lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function struktur_by_emp()
	{
		$usr = $this->input->post('karyawan');
		$struktur = $this->m_hris->struktur_by_karyawan($usr);
		$data = array();
		foreach ($struktur->result() as $d) {
			array_push($data, $d->recid_struktur);
			array_push($data, $d->nama_struktur);
		}
		echo json_encode($data);
	}

	public function docsecre_pinsert()
	{
		$recid_karyawan = $this->input->post('recid_karyawan');
		$recid_struktur = $this->input->post('recid_struktur');
		$inisial = $this->m_hris->struktur_by_recid($recid_struktur);
		foreach ($inisial as $i) {
			$ins = $i->inisial;
		}
		// $nama_struktur = $this->input->post('nama_struktur');
		// $nama_struktur = substr($nama_struktur, strpos($nama_struktur, ". ") + 1);  
		$tujuan = $this->input->post('tujuan');
		$perihal = $this->input->post('deskripsi');

		$bulan = date('n');
		$bln = array("", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
		$tahun = date('Y');
		$cek_count = $this->m_hris->docsecre_tahun(date('Y'));
		$cnt = $cek_count->num_rows();
		$cnt = $cnt + 1;
		$count = $cnt;
		if ($count < 10) {
			$count = "00$count";
		} else if ($count < 100 && $count >= 10) {
			$count = "0$count";
		} else {
			$count;
		}
		$nomor = $count . "/CINT/" . $ins . "/" . $bln[$bulan] . "/" . $tahun;

		$data = array(
			'crt_by'		=> $this->session->userdata('kar_id'),
			'crt_date'		=> date('y-m-d h:i:s'),
			'no_doc'		=> $nomor,
			'recid_karyawan' => $recid_karyawan,
			'recid_struktur' => $recid_struktur,
			'tanggal'		 => date('Y-m-d'),
			'tujuan'		 => $tujuan,
			'deskripsi'		 => $perihal
		);
		$this->m_hris->docsecre_pinsert($data);
		redirect('Karyawan/docsecre_view');
	}

	public function docsecre_pupdate()
	{
		$recid_doc = $this->input->post('recid_doc');
		$nomor_doc = $this->input->post('nomor_doc');
		$recid_karyawan = $this->input->post('recid_karyawan');
		$recid_struktur = $this->input->post('recid_struktur');
		// $recid_struktur = 4;
		$inisial = $this->m_hris->struktur_by_recid($recid_struktur);
		foreach ($inisial as $i) {
			$ins = $i->inisial;
		}
		// $nomor_doc = "001/CINT/PRODUCTION SUPPORT/IX/2022";
		$pecah = explode("/", $nomor_doc);
		$cnt =  $pecah[0];
		$cint =  $pecah[1];
		$bln = $pecah[3];
		$tahun = $pecah[4];
		$nomor_doc = "$cnt/$cint/$ins/$bln/$tahun";
		// $nama_struktur = $this->input->post('nama_struktur');
		// $nama_struktur = substr($nama_struktur, strpos($nama_struktur, ". ") + 1);
		$tujuan = $this->input->post('tujuan');
		$perihal = $this->input->post('deskripsi');

		$data = array(
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('y-m-d h:i:s'),
			'no_doc'		=> $nomor_doc,
			'recid_karyawan' => $recid_karyawan,
			'recid_struktur' => $recid_struktur,
			'tujuan'		 => $tujuan,
			'deskripsi'		 => $perihal
		);
		$this->m_hris->docsecre_pupdate($data, $recid_doc);
		redirect('Karyawan/docsecre_view');
	}
	
public function export($recid_karyawan = null)
{
    $this->load->library('session');
    if ($this->session->userdata('logged_in') != 1) {
        redirect('Auth/keluar');
        return;
    }

    // Load the contract model to get contract data
    $this->load->model('M_kontrak');

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();

    // Helper merge functions
    $v = fn($col) => $sheet->mergeCells("{$col}1:{$col}3");
    $merge2 = fn($col1, $col2) => $sheet->mergeCells("{$col1}1:{$col2}1");
    $merge_row_23 = fn($col) => $sheet->mergeCells("{$col}2:{$col}3");
    
    // ========================= HEADER =========================

    // Row 1: Main headers with multi-row spans
    $sheet->setCellValue('A1','NO'); $merge_row_23('A');
    $sheet->setCellValue('B1','NIK'); $merge_row_23('B');
    $sheet->setCellValue('C1','NAMA'); $merge_row_23('C');
    $sheet->setCellValue('D1','ALAMAT E-MAIL PRIBADI'); $merge_row_23('D');
    $sheet->setCellValue('E1','JABATAN'); $merge_row_23('E');
    $sheet->setCellValue('F1','BAGIAN'); $merge_row_23('F');
    $sheet->setCellValue('G1','SUB.BAGIAN'); $merge_row_23('G');
    $sheet->setCellValue('H1','DEPARTEMEN'); $merge_row_23('H');
    $sheet->setCellValue('I1','STATUS KARYAWAN'); $merge_row_23('I');
    $sheet->setCellValue('J1','TGL. MASUK'); $merge_row_23('J');
    $sheet->setCellValue('K1','TGL. KELUAR'); $merge_row_23('K');
    $sheet->setCellValue('L1','TGL.JEDA'); $merge_row_23('L');
    $sheet->setCellValue('M1','MASA KERJA'); $merge_row_23('M');
    $sheet->setCellValue('N1','SK. KARY TETAP'); $merge_row_23('N');
    
    // BPJS section with 3-level headers like import preview
    // BPJS section - based on import preview structure
    $sheet->setCellValue('O1','BPJS'); $sheet->mergeCells('O1:P1');
    $sheet->setCellValue('O2','BPJS NO.KPJ'); $merge_row_23('O');
    $sheet->setCellValue('P2','NO. KARTU TRIMAS'); $merge_row_23('P');
    
    $sheet->setCellValue('Q1','STATUS PERNIKAHAN'); $merge_row_23('Q');
    $sheet->setCellValue('R1','TEMPAT LAHIR'); $merge_row_23('R');
    $sheet->setCellValue('S1','TGL LAHIR'); $merge_row_23('S');
    $sheet->setCellValue('T1','TGL LAHIR HARI'); $merge_row_23('T');
    $sheet->setCellValue('U1','BULAN LAHIR'); $merge_row_23('U');
    $sheet->setCellValue('V1','USIA'); $merge_row_23('V');
    $sheet->setCellValue('W1','ALAMAT KTP'); $merge_row_23('W');
    $sheet->setCellValue('X1','ALAMAT TINGGAL SEKARANG'); $merge_row_23('X');
    $sheet->setCellValue('Y1','JENIS KELAMIN'); $merge_row_23('Y');
    $sheet->setCellValue('Z1','AGAMA'); $merge_row_23('Z');
    $sheet->setCellValue('AA1','PENDIDIKAN TERAKHIR'); $merge_row_23('AA');
    $sheet->setCellValue('AB1','NO. TELEPON'); $merge_row_23('AB');
    $sheet->setCellValue('AC1','NO. KK'); $merge_row_23('AC');
    $sheet->setCellValue('AD1','NO. KTP'); $merge_row_23('AD');
    $sheet->setCellValue('AE1','GOL DARAH'); $merge_row_23('AE');
    $sheet->setCellValue('AF1','NAMA ORANG TUA'); $merge_row_23('AF');
    $sheet->setCellValue('AG1','NAMA SUAMI / ISTRI'); $merge_row_23('AG');
    $sheet->setCellValue('AH1','JUMLAH ANAK'); $merge_row_23('AH');
    $sheet->setCellValue('AI1','NAMA ANAK'); $merge_row_23('AI');
    
    // CONTRACT COLUMNS - 44 pairs of AWAL/AKHIR (88 columns total)
    // Multi-row header structure like import preview
    $contract_start_col = 'AJ';
    $contract_end_col = 'DW';
    $contract_start_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($contract_start_col);
    
    // Row 1: KONTRAK spanning 88 columns
    $sheet->setCellValue('AJ1','KONTRAK'); $sheet->mergeCells('AJ1:DW1');
    
    // Row 2: Numbers 1-44 spanning AWAL/AKHIR pairs
    for ($i = 1; $i <= 44; $i++) {
        $col_index = $contract_start_index + ($i - 1) * 2;
        $col_awal = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index);
        $col_akhir = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index + 1);
        $sheet->setCellValue($col_awal . '2', $i);
        $sheet->mergeCells($col_awal . '2:' . $col_akhir . '2');
    }
    
    // Row 3: AWAL/AKHIR pairs
    for ($i = 1; $i <= 44; $i++) {
        $col_index = $contract_start_index + ($i - 1) * 2;
        $col_awal = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index);
        $col_akhir = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index + 1);
        $sheet->setCellValue($col_awal . '3', 'AWAL');
        $sheet->setCellValue($col_akhir . '3', 'AKHIR');
    }

    // Remaining columns after contract data
    $sheet->setCellValue('DX1','KONTRAK AKHIR'); $merge_row_23('DX');
    $sheet->setCellValue('DY1','NO.REKENING'); $merge_row_23('DY');
    $sheet->setCellValue('DZ1','TIPE PTKP'); $merge_row_23('DZ');
    $sheet->setCellValue('EA1','ALASAN KELUAR'); $merge_row_23('EA');
    $sheet->setCellValue('EB1','KETERANGAN'); $merge_row_23('EB');
    $sheet->setCellValue('EC1','LEVEL'); $merge_row_23('EC');
    $sheet->setCellValue('ED1','DL/IDL'); $merge_row_23('ED');

    // ========== HEADER STYLE ==========
    $sheet->getStyle('A1:ED3')->applyFromArray([
        'font'=>['bold'=>true],
        'alignment'=>[
            'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText'=>true
        ],
        'borders'=>['allBorders'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        'fill'=>[
            'fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor'=>['argb'=>'FFFFF7C6']
        ]
    ]);

    $sheet->freezePane('A4'); // Freeze header rows

    // QUERY
    $this->db->select("
        k.*, b.nama_bag, bs.sub_bag AS subbag_nama,
        j.nama_jbtn, d.nama_department AS departemen
    ");
    $this->db->from("karyawan k");
    $this->db->join("bagian b","b.recid_bag=k.recid_bag","left");
    $this->db->join("bagian_sub bs","bs.recid_subbag=k.recid_subbag","left");
    $this->db->join("jabatan j","j.recid_jbtn=k.recid_jbtn","left");
    $this->db->join("department d","d.recid_department=b.recid_department","left");
    $this->db->order_by("k.nama_karyawan","ASC");

    $data = $this->db->get()->result_array();

    // ========================= TULIS DATA =========================
    $row = 4;
    $no  = 1;

    $val = fn($x,$k)=> (!empty($x[$k])) ? $x[$k] : "-";
    
    foreach ($data as $k)
    {
        $sheet->setCellValue("A{$row}", $no++);
        $sheet->setCellValue("B{$row}", $val($k,'nik'));
        $sheet->setCellValue("C{$row}", $val($k,'nama_karyawan'));
        $sheet->setCellValue("D{$row}", $val($k,'email'));

        $sheet->setCellValue("E{$row}", $val($k,'nama_jbtn'));
        $sheet->setCellValue("F{$row}", $val($k,'nama_bag'));
        $sheet->setCellValue("G{$row}", $val($k,'subbag_nama'));
        $sheet->setCellValue("H{$row}", $val($k,'departemen'));
        $sheet->setCellValue("I{$row}", $val($k,'sts_aktif'));

        $sheet->setCellValue("J{$row}", $val($k,'tgl_m_kerja'));
        $sheet->setCellValue("K{$row}", $val($k,'tgl_keluar')); // TGL. KELUAR
        $sheet->setCellValue("L{$row}", $val($k,'tgl_jeda'));

        $sheet->setCellValue("M{$row}", $val($k,'masa_kerja'));
        $sheet->setCellValue("N{$row}", $val($k,'sk_kary_tetap_nomor'));

        $sheet->setCellValue("O{$row}", $val($k,'no_bpjs_tk'));
        $sheet->setCellValue("P{$row}", $val($k,'no_kartu_trimas'));

        $sheet->setCellValue("Q{$row}", $val($k,'sts_nikah'));
        $sheet->setCellValue("R{$row}", $val($k,'tmp_lahir'));
        $sheet->setCellValue("S{$row}", $val($k,'tgl_lahir'));
        // Extract day from tgl_lahir for TGL LAHIR HARI
        $tgl_lahir = $val($k,'tgl_lahir');
        $tgl_lahir_hari = '';
        if ($tgl_lahir && $tgl_lahir !== '-' && $tgl_lahir !== '0000-00-00') {
            $date = new DateTime($tgl_lahir);
            $tgl_lahir_hari = $date->format('j'); // Get day without leading zeros
        }
        $sheet->setCellValue("T{$row}", $tgl_lahir_hari); // TGL LAHIR HARI - extracted from tgl_lahir
        $sheet->setCellValue("U{$row}", $val($k,'bulan_lahir')); // BULAN LAHIR
        $sheet->setCellValue("V{$row}", $val($k,'usia')); // USIA

        $sheet->setCellValue("W{$row}", $val($k,'alamat_ktp'));
        $sheet->setCellValue("X{$row}", $val($k,'alamat_skrg'));
        $sheet->setCellValue("Y{$row}", $val($k,'jenkel'));
        $sheet->setCellValue("Z{$row}", $val($k,'agama'));
        $sheet->setCellValue("AA{$row}", $val($k,'pendidikan'));

        $sheet->setCellValue("AB{$row}", $val($k,'telp1'));
        $sheet->setCellValue("AC{$row}", $val($k,'no_kk'));
        $sheet->setCellValue("AD{$row}", $val($k,'no_ktp'));
        $sheet->setCellValue("AE{$row}", $val($k,'gol_darah'));
        $sheet->setCellValue("AF{$row}", $val($k,'nama_orang_tua'));
        $sheet->setCellValue("AG{$row}", $val($k,'nama_pasangan'));
        $sheet->setCellValue("AH{$row}", $val($k,'jumlah_anak'));
        $sheet->setCellValue("AI{$row}", $val($k,'nama_anak'));

        // CONTRACT DATA - Get contract periods for this employee
        $contract_data = $this->M_kontrak->get_kontrak_by_karyawan($k['recid_karyawan']);
        $contract_count = count($contract_data);
        
        // Write contract data up to 44 pairs (AWAL/AKHIR)
        for ($i = 0; $i < 44; $i++) {
            $col_index = $contract_start_index + $i * 2;
            $col_awal = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index);
            $col_akhir = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_index + 1);
            
            if (isset($contract_data[$i])) {
                $sheet->setCellValue($col_awal . "{$row}", $contract_data[$i]->tgl_mulai);
                $sheet->setCellValue($col_akhir . "{$row}", $contract_data[$i]->tgl_akhir);
            } else {
                $sheet->setCellValue($col_awal . "{$row}", '');
                $sheet->setCellValue($col_akhir . "{$row}", '');
            }
        }

        // Continue with remaining columns
        // For KONTRAK AKHIR, use the end date of the last contract if available, otherwise use tgl_keluar
        $kontrak_akhir = $val($k,'tgl_keluar');
        if (!empty($contract_data)) {
            $last_contract = end($contract_data);
            if ($last_contract && isset($last_contract->tgl_akhir) && $last_contract->tgl_akhir) {
                $kontrak_akhir = $last_contract->tgl_akhir;
            }
        }
        $sheet->setCellValue("DX{$row}", $kontrak_akhir); // KONTRAK AKHIR
        $sheet->setCellValue("DY{$row}", $val($k,'acc_bank')); // NO.REKENING
        $sheet->setCellValue("DZ{$row}", $val($k,'sts_penunjang'));
        $sheet->setCellValue("EA{$row}", $val($k,'alasan_keluar'));
        $sheet->setCellValue("EB{$row}", $val($k,'keterangan'));
        $sheet->setCellValue("EC{$row}", $val($k,'level'));
        $sheet->setCellValue("ED{$row}", $val($k,'dl_idl'));

        $row++;
    }

    // ========================= STYLE DATA =========================
    $sheet->getStyle("A4:ED{$row}")
        ->applyFromArray([
            'borders'=>['allBorders'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

    // Set column widths
    foreach (range('A','ED') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = "DATA_KARYAWAN_" . date('Ymd_His') . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
}

}
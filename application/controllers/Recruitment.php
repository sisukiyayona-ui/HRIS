<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recruitment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_hris', 'm_training'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
		$this->load->library('email');
	}

	// ################################################### PELAMAR ##################################################################

	public function pelamar_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['pelamar'] = $this->m_hris->pelamar_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$id_pelamar = $this->uri->segment(3);
			$cek_foto = $this->m_hris->cek_foto($id_pelamar);
			if ($cek_foto->num_rows() >= 1) {
				$data['biodata'] = $this->m_hris->biodata_pelamar_foto($id_pelamar);
			} else {
				$data['biodata'] = $this->m_hris->pelamar_view_byrecid($id_pelamar);
			}
			$data['keluarga'] = $this->m_hris->keluarga($id_pelamar);
			$data['pengalaman'] = $this->m_hris->pengalaman($id_pelamar);
			$data['pernyataan'] = $this->m_hris->pernyataan($id_pelamar);
			$data['berkas'] = $this->m_hris->berkas($id_pelamar);
			$data['foto'] = $cek_foto->num_rows();
			$data['cek_tanya'] = $this->m_hris->pernyataan($id_pelamar)->num_rows();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_pelamar = $this->input->post('nama_pelamar');
			$no_ktp = $this->input->post('no_ktp');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$alamat = $this->input->post('alamat');
			$no_telp = $this->input->post('no_telp');
			$email = $this->input->post('email');
			$jenis_referensi = $this->input->post('jenis_referensi');
			if ($jenis_referensi == 'Karyawan') {
				$referensi = $this->input->post('recid_karyawan');
			} else {
				$referensi = $this->input->post('referensi');
			}
			$note = $this->input->post('note');

			//cek double input 
			$cek_ktp = $this->m_hris->cek_ktp($no_ktp);
			if ($cek_ktp->num_rows() > 0) {
				$pesan = "<div class='alert alert-error'> No KTP Telah Terdaftar, data tidak tersimpan.</div>";
				$this->session->set_flashdata('message', $pesan);
				redirect('index.php/Recruitment/pelamar_insert');
			} else {

				$data = array(
					'crt_by'			=> $this->session->userdata('kar_id'),
					'crt_date'			=> date('y-m-d h:i:s'),
					'nama_pelamar'		=> $nama_pelamar,
					'no_ktp'			=> $no_ktp,
					'tgl_lahir'			=> $tgl_lahir,
					'alamat'			=> $alamat,
					'no_telp'			=> $no_telp,
					'email'				=> $email,
					'jenis_referensi'	=> $jenis_referensi,
					'referensi'			=> $referensi,
					'note'				=> $note,
				);
				$this->m_hris->pelamar_insert($data);
				redirect('index.php/Recruitment/pelamar_view');
			}
		} // punya if role
		else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_pelamar = $this->uri->segment(3);
			$data['pelamar'] = $this->m_hris->pelamar_view_byrecid($recid_pelamar);
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_pelamar = $this->input->post('recid_pelamar');
			$nama_pelamar = $this->input->post('nama_pelamar');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$no_ktp = $this->input->post('no_ktp');
			$agama = $this->input->post('agama');
			$alamat = $this->input->post('alamat');
			$no_telp = $this->input->post('no_telp');
			$email = $this->input->post('email');
			$jenis_referensi = $this->input->post('jenis_referensi');
			$karyawan2 = $this->input->post('recid_karyawan2');
			$referensi2 = $this->input->post('referensi2');
			$other_berkas = $this->input->post('other_berkas');
			if ($jenis_referensi == 'Karyawan') {
				if ($karyawan2 == '') {
					$referensi = $this->input->post('recid_karyawan');
				} else {
					$referensi = $this->input->post('recid_karyawan2');
				}
			} else {
				if ($referensi2 == '') {
					$referensi = $this->input->post('referensi');
				} else {
					$referensi = $this->input->post('referensi2');
				}
			}
			$note = $this->input->post('note');

			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'nama_pelamar'		=> $nama_pelamar,
				'tgl_lahir'			=> $tgl_lahir,
				'no_ktp'			=> $no_ktp,
				'alamat'			=> $alamat,
				'no_telp'			=> $no_telp,
				'email'				=> $email,
				'jenis_referensi'	=> $jenis_referensi,
				'referensi'			=> $referensi,
				'note'				=> $note,
				'other_berkas'		=> $other_berkas,
			);
			$this->m_hris->pelamar_pupdate($data, $recid_pelamar);
			redirect('index.php/Recruitment/pelamar_view');
		} // punya if role
		else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### FPTK ##################################################################
	public function fptk_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('fptk/fptk_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fptk_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['fptk'] = $this->m_hris->fptk_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('fptk/fptk_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fptk_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tgl_pengajuan = $this->input->post('tgl_pengajuan');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$alasan = $this->input->post('alasan');
			$recid_bag = $this->input->post('recid_bag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			$tgl_efektif = $this->input->post('tgl_efektif');
			$tgl_approve = $this->input->post('tgl_approve');
			$note = $this->input->post('note');

			$cur = $this->m_hris->karyawan_by_recid($recid_karyawan);
			foreach ($cur as $key) {
				$recid_bags = $key->recid_bag;
				$recid_jbtns = $key->recid_jbtn;
			}

			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'tgl_pengajuan'		=> $tgl_pengajuan,
				'recid_karyawan'	=> $recid_karyawan,
				'recid_bags'		=> $recid_bags,
				'recid_jbtns'		=> $recid_jbtns,
				'alasan'			=> $alasan,
				'recid_bag'			=> $recid_bag,
				'recid_jbtn'		=> $recid_jbtn,
				'tgl_efektif'		=> $tgl_efektif,
				'tgl_approve'		=> $tgl_approve,
				'note'				=> $note
			);
			$this->m_hris->fptk_pinsert($data);
			redirect('index.php/Recruitment/fptk_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fptk_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$data['fptk'] = $this->m_hris->fptk_by_recid($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('fptk/fptk_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function fptk_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_fptk = $this->input->post('recid_fptk');
			$tgl_pengajuan = $this->input->post('tgl_pengajuan');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$alasan = $this->input->post('alasan');
			$recid_bag = $this->input->post('recid_bag');
			$recid_jbtn = $this->input->post('recid_jbtn');
			$tgl_efektif = $this->input->post('tgl_efektif');
			$tgl_approve = $this->input->post('tgl_approve');
			$note = $this->input->post('note');

			$cur = $this->m_hris->karyawan_by_recid($recid_karyawan);
			foreach ($cur as $key) {
				$recid_bags = $key->recid_bag;
				$recid_jbtns = $key->recid_jbtn;
			}

			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'tgl_pengajuan'		=> $tgl_pengajuan,
				'recid_karyawan'	=> $recid_karyawan,
				'recid_bags'		=> $recid_bags,
				'recid_jbtns'		=> $recid_jbtns,
				'alasan'			=> $alasan,
				'recid_bag'			=> $recid_bag,
				'recid_jbtn'		=> $recid_jbtn,
				'tgl_efektif'		=> $tgl_efektif,
				'tgl_approve'		=> $tgl_approve,
				'note'				=> $note,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->fptk_pupdate($data, $recid_fptk);
			redirect('index.php/Recruitment/fptk_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### KFPTK ##################################################################
	public function kfptk_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$data['fptk'] = $this->m_hris->fptk_by_recid($recid);
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$rel = $this->m_hris->cek_relasi($recid);
			if ($rel == 1) {
				$data['kfptk'] = $this->m_hris->kfptk_by_fptk($recid);
				$this->load->view('kfptk/kfptk_update', $data);
			} else {
				$this->load->view('kfptk/kfptk_insert', $data);
			}
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function kfptk_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['fptk'] = $this->m_hris->fptk_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('kfptk/fptk_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function kfptk_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_fptk = $this->input->post('recid_fptk');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$status_acc = $this->input->post('status_acc');
			$tgl_hc = $this->input->post('tgl_hc');
			$tgl_bod = $this->input->post('tgl_bod');
			$note = $this->input->post('note');

			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'crt_date'			=> date('y-m-d h:i:s'),
				'recid_fptk'		=> $recid_fptk,
				'recid_karyawan'	=> $recid_karyawan,
				'status_acc'		=> $status_acc,
				'tgl_hc'			=> $tgl_hc,
				'tgl_bod'			=> $tgl_bod,
				'note'				=> $note,
			);
			$this->m_hris->kfptk_pinsert($data);
			redirect('index.php/Recruitment/fptk_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function kfptk_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_fptk = $this->input->post('recid_fptk');
			$recid_kfptk = $this->input->post('recid_kfptk');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$status_acc = $this->input->post('status_acc');
			$tgl_hc = $this->input->post('tgl_hc');
			$tgl_bod = $this->input->post('tgl_bod');
			$note = $this->input->post('note');

			// echo $recid_kfptk;

			$data = array(
				'recid_fptk'		=> $recid_fptk,
				'recid_karyawan'	=> $recid_karyawan,
				'status_acc'		=> $status_acc,
				'tgl_hc'			=> $tgl_hc,
				'tgl_bod'			=> $tgl_bod,
				'note'				=> $note,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);
			$this->m_hris->kfptk_pupdate($data, $recid_kfptk);
			redirect('index.php/Recruitment/fptk_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### RECRUITMENT #############################################################
	public function recruitment_add()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['fptk'] = $this->m_hris->kfptk_recruitment();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_add', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$data['fptk'] = $this->m_hris->kfptk_by_recid($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_kfptk = $this->input->post('recid_kfptk');
			$judul_recruitment = $this->input->post('judul_recruitment');
			$tgl_open = $this->input->post('tgl_open');
			$sasaran = $this->input->post('sasaran');
			$jobdesk = $this->input->post('jobdesk');
			$status = $this->input->post('status');
			$note = $this->input->post('note');
			// echo $recid_kfptk;

			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_kfptk'			=> $recid_kfptk,
				'judul_recruitment'		=> $judul_recruitment,
				'tgl_open'				=> $tgl_open,
				'sasaran'				=> $sasaran,
				'jobdesk'				=> $jobdesk,
				'status'				=> $status,
				'note'					=> $note,
			);
			$this->m_hris->recruitment_pinsert($data);
			$this->m_hris->kfptk_status($recid_kfptk);
			redirect("index.php/Recruitment/recruitment_view");
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['recruitment'] = $this->m_hris->recruitment_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$data['recruitment'] = $this->m_hris->recruitment_by_recid($recid);
			$data['test'] = $this->m_hris->test_by_recruitment($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$id = $this->m_hris->recruitment_by_recid($recid);
			$recid_kfptk = '';
			foreach ($id as $id) {
				$recid_kfptk = $id->recid_kfptk;
			}
			$data['fptk'] = $this->m_hris->kfptk_by_recid($recid_kfptk);
			$data['recruitment'] = $this->m_hris->recruitment_by_recid($recid);
			$data['test'] = $this->m_hris->test_by_recruitment($recid);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_kfptk = $this->input->post('recid_kfptk');
			$recid_recruitment = $this->input->post('recid_recruitment');
			$judul_recruitment = $this->input->post('judul_recruitment');
			$tgl_open = $this->input->post('tgl_open');
			$sasaran = $this->input->post('sasaran');
			$jobdesk = $this->input->post('jobdesk');
			$status = $this->input->post('status');
			if ($status == 'Closed') {
				$tgl_closed = date('y-m-d');
			} else {
				$tgl_closed = NULL;
			}
			$note = $this->input->post('note');

			// echo "id : $recid_recruitment";

			$data = array(
				'recid_kfptk'			=> $recid_kfptk,
				'judul_recruitment'		=> $judul_recruitment,
				'tgl_open'				=> $tgl_open,
				'tgl_closed'			=> $tgl_closed,
				'sasaran'				=> $sasaran,
				'jobdesk'				=> $jobdesk,
				'status'				=> $status,
				'note'					=> $note,
				'mdf_by'				=> $this->session->userdata('kar_id'),
				'mdf_date'				=> date('y-m-d h:i:s'),
			);
			$this->m_hris->recruitment_pupdate($data, $recid_recruitment);
			redirect("index.php/Recruitment/recruitment_view");
		} else {
			redirect('Auth/keluar');
		}
	}


	public function karyawan_generate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_pelamar = $this->uri->segment('3');
			$data['pelamar'] = $this->m_hris->pelamar_view_byrecid($recid_pelamar);
			$data['ijazah'] = $this->m_hris->pelamar_view_byrecid($recid_pelamar);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('karyawan/karyawan/karyawan_generate', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function recruitment_report()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['recruitment'] = $this->m_hris->detail_hire();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_report', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karyawan_hired()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_recruitment = $this->uri->segment('3');
			$data['fptk'] = $this->m_hris->recruitment_by_recid($recid_recruitment);
			$data['kfptk'] = $this->m_hris->recruitment_by_recid($recid_recruitment);
			$data['recruitment'] = $this->m_hris->recruitment_by_recid($recid_recruitment);
			$data['hired'] = $this->m_hris->detail_hire_by_id($recid_recruitment);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_hired', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function dash_recruitment()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$bln_skrg = date('m');
			$bln_chart = array();
			$recrut = array();
			$penuh = array();
			$percen = array();
			$thn = $this->input->post('tahun_rec');
			// $thn = '2021';
			if ($thn <> date('Y')) {
				for ($i = 0; $i < count($all_bulan); $i++) {
					array_push($bln_chart, $all_bulan[$i]);
					$all_rec =  $this->m_hris->recruitment_per_bulan($i + 1, $thn);
					array_push($recrut, $all_rec->num_rows());
					$terpenuhin =  $this->m_hris->leadtime_terpenuhi($i + 1, $thn);
					array_push($penuh, $terpenuhin->num_rows());
					if ($all_rec->num_rows() == 0) {
						$persen = 0;
					} else {
						$persen = ($terpenuhin->num_rows() / $all_rec->num_rows()) * 100;
					}
					array_push($percen, $persen);
				}
			} else {
				for ($i = 0; $i < $bln_skrg; $i++) {
					array_push($bln_chart, $all_bulan[$i]);
					$all_rec =  $this->m_hris->recruitment_per_bulan($i + 1, $thn);
					array_push($recrut, $all_rec->num_rows());
					$terpenuhin =  $this->m_hris->leadtime_terpenuhi($i + 1, $thn);
					array_push($penuh, $terpenuhin->num_rows());
					if ($all_rec->num_rows() == 0) {
						$persen = 0;
					} else {
						$persen = ($terpenuhin->num_rows() / $all_rec->num_rows()) * 100;
					}
					array_push($percen, $persen);
				}
			}

			$data = array();
			array_push($data, $bln_chart);
			array_push($data, $recrut);
			array_push($data, $penuh);
			array_push($data, $percen);

			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_recruitment()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->uri->segment('3');
			$tahun = $this->uri->segment('4');
			$all_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
			$month = array_search($bulan, $all_bulan);
			$month = $month + 1;
			$data['recruitment'] = $this->m_hris->leadtime_terpenuhi($month, $tahun);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);

			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_report', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	// ################################################### TEST ##################################################################
	public function test_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_recruitment = $this->uri->segment(3);
			$data['recruitment'] = $this->m_hris->recruitment_by_recid($recid_recruitment);
			$data['pelamar'] = $this->m_hris->pelamar_by_loker($recid_recruitment);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('test/test_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function test_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_recruitment = $this->input->post('recid_recruitment');
			$judul_test = $this->input->post('judul_test');
			$tgl_test = $this->input->post('tgl_test');
			$recid_pelamar = $this->input->post('recid_pelamar');
			$note = $this->input->post('note');

			$data2 = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_recruitment'		=> $recid_recruitment,
				'judul_test'			=> $judul_test,
				'tgl_test'				=> $tgl_test,
				'note'					=> $note,
			);
			$this->m_hris->test_insert($data2);

			$lastest = $this->m_hris->lastest();
			foreach ($lastest as $rec) {
				$recid_test = $rec->recid_test;
			}

			for ($i = 0; $i < count($this->input->post('recid_pelamar')); $i++) // looping sebanyak multi select
			{
				$data = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_test'			=> $recid_test,
					'recid_pelamar'			=> $recid_pelamar[$i],
					'note'					=> $note,
				);
				$this->m_hris->seleksi_insert($data);
			}
			redirect('index.php/Recruitment/recruitment_view');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function seleksi_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_seleksi = $this->input->post('recid_seleksi');
			$recid_recruitment = $this->input->post('recid_recruitment');
			$seleksi = $this->m_hris->seleksi_by_test($recid_seleksi);
			foreach ($seleksi as $s) {
				$test = $s->judul_test;
				$recid_pelamar = $s->recid_pelamar;
			}
			$hasil = $this->input->post('hasil');
			$status = $this->input->post('status');
			$note = $this->input->post('note');
			$other_berkas = $this->input->post('other_berkas');
			// echo $recid_recruitment;
			$data = array(
				'hasil'				=> $hasil,
				'status'			=> $status,
				'note'				=> $note,
			);
			$this->m_hris->seleksi_update($data, $recid_seleksi);

			$data2 = array(
				'status'			=> $status,
				'seleksi'			=> $test,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('Y-m-d h:i:s'),
			);
			$this->m_hris->pengumuman_update($data2, $recid_pelamar, $recid_recruitment);

			$data3 = array(
				'other_berkas'		=> $other_berkas
			);
			$this->m_hris->pelamar_pupdate($data3, $recid_pelamar);
			echo $other_berkas;

			$hal = "index.php/Recruitment/recruitment_detail/$recid_recruitment";
			redirect($hal);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function pelamar_match()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['jenkel'] = $this->m_hris->pelamar_jenkel();
			$data['sts_kawin'] = $this->m_hris->pelamar_stskawin();
			$data['pendidikan'] = $this->m_hris->pelamar_pendidikan();
			$data['job'] = $this->m_hris->pelamar_job();
			$data['kota'] = $this->m_hris->pelamar_domkota();
			$data['agama'] = $this->m_hris->pelamar_agama();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_match', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_match()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$text = "";
			$jenkel = $this->input->post('jenkel');	// jenis kelamin
			$sts_kawin = $this->input->post('sts_kawin');	// sts_kawin
			$agama = $this->input->post('agama');	//  agama
			$pendidikan = $this->input->post('pendidikan');	//  pendidikan
			$domisili = $this->input->post('domisili');	//  pendidikan
			$job = $this->input->post('job');	//  pendidikan
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
			if (!empty($jenkel)) {
				// $text = "$text and p.jenkel = '$jenkel'";
				$cnt = count($jenkel);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.pjenkel = '$jenkel[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.pjenkel = '$jenkel[$i]'";
						} else {
							$text .= "p.pjenkel = '$jenkel[$i]' or ";
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
					$text .= "p.psts_nikah = '$sts_kawin[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.psts_nikah = '$sts_kawin[$i]'";
						} else {
							$text .= "p.psts_nikah = '$sts_kawin[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($domisili)) {
				// $text = "$text and p.sts_nikah = '$sts_kawin'";
				$cnt = count($domisili);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "p.dom_kota = '$domisili[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.dom_kota = '$domisili[$i]'";
						} else {
							$text .= "p.dom_kota = '$domisili[$i]' or ";
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
					$text .= "p.ppendidikan = '$pendidikan[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.ppendidikan = '$pendidikan[$i]'";
						} else {
							$text .= "p.ppendidikan = '$pendidikan[$i]' or ";
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
					$text .= "p.pagama = '$agama[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "p.pagama = '$agama[$i]'";
						} else {
							$text .= "p.pagama = '$agama[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			if (!empty($job)) {
				// $text = "$text and p.sts_nikah = '$sts_kawin'";
				$cnt = count($job);
				$text .= " and (";
				if ($cnt == 1) {
					$text .= "pp.kategori_posisi = '$job[0]'";
				} else {
					for ($i = 0; $i < $cnt; $i++) {
						if ($i == $cnt - 1) {
							$text .= "pp.kategori_posisi = '$job[$i]'";
						} else {
							$text .= "pp.kategori_posisi = '$job[$i]' or ";
						}
					}
				}
				$text .= ")";
			} else {
				$text = "$text";
			}

			/*	if(!empty($masker)){
				if($masker =='range'){
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_m_kerja, CURDATE()) between '$mmin' and '$mmax'";
				}else{
					$text = "$text and TIMESTAMPDIFF(YEAR, p.tgl_m_kerja, CURDATE()) ='$masker_def'";
				}	
			}else{
				$text = "$text";
			}*/

			// echo $text;
			$data['pelamar'] = $this->m_hris->candidate_match($text)->result();
			// echo "$report";

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['menu']	= "Candidate Matching Profile Result";
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function vacancy_applied()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_recruitment = $this->uri->segment(3);
			$data['pelamar'] = $this->m_hris->pelamar_by_vacancy($recid_recruitment);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/pelamar_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function assign_vacancy()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_recruitment = $this->uri->segment(3);
			$data['pelamar'] = $this->m_hris->pelamar_view();
			$data['loker']	= $this->m_hris->open_recruitment();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('pelamar/assign_vacancy', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function assign_vacancy_to()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_pelamar = $this->input->post('recid_pelamar');
			$recid_recruitment = $this->input->post('recid_recruitment');
			$data = array(
				"recid_recruitment"		=> $recid_recruitment,
				"recid_pelamar"			=> $recid_pelamar,
				"seleksi"				=> "Administrasi",
				"status"				=> "Diajukan",
			);
			$this->m_hris->pelamar_apply_insert($data);
			echo json_encode("OK");
		} else {
			redirect('Auth/keluar');
		}
	}

	public function data_assign()
	{
		$mulai = $this->input->post('tgl_mulai');
		$mulai = $mulai . " 00:00:00";
		$akhir = $this->input->post('tgl_akhir');
		$akhir = $akhir . " 23:59:59";
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		// echo $mulai . " - " . $akhir;
		$query = $this->m_hris->pelamar_view_periode($mulai, $akhir);
		$data = [];
		$i = 1;
		foreach ($query->result() as $r) {
			$loker = '';
			$ap = $this->m_hris->pelamar_apply_to($r->recid_pelamar);
			$ap_cnt = $ap->num_rows();
			$ca = 0;
			foreach ($ap->result() as $a) {
				$ca = $ca + 1;
				if ($ap_cnt < 1) {
					$loker = $loker;
				} else {
					if ($ca < $ap_cnt) {
						$loker .= $a->judul_recruitment . ", ";
					} else {
						$loker .= $a->judul_recruitment;
					}
				}
			}
			if ($a->tgl_regis == "") {
				$tgl = "";
			} else {
				$tgl = date("d-m-Y H:i:s", strtotime($a->tgl_regis));
			}
			$data[] = array(
				$i++,
				$r->tgl_regis,
				$r->no_ktp,
				$r->nama_pelamar,
				$r->pjenkel,
				$r->ppendidikan,
				$r->pjurusan,
				$r->kota_name,
				$r->no_telp,
				$r->email,
				$loker,
				"<a href=" . base_url() . "index.php/Recruitment/pelamar_detail/" . $r->recid_pelamar . "><button class='btn btn-success btn-xs'><span class='fa fa-search-plus'></span></button></a>
                         <a 
                         data-recid_pelamar=" . $r->recid_pelamar . "
                         data-toggle='modal' data-target='#assignModal'><button class='btn btn-default btn-xs'><span class='fa fa-share'></button>
                        </a>"
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


	public function data_pelamar()
	{
		$role = $this->session->userdata('role_id');
		$mulai = $this->input->post('tgl_mulai');
		$mulai = $mulai . " 00:00:00";
		$akhir = $this->input->post('tgl_akhir');
		$akhir = $akhir . " 23:59:59";
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		// echo $mulai . " - " . $akhir;
		$query = $this->m_hris->pelamar_view_periode($mulai, $akhir);
		$data = [];
		$i = 1;
		foreach ($query->result() as $r) {
			if ($r->tgl_lahir == '0000-00-00') {
				$tgl_lahir =  " - ";
			} else {
				$dif = date_diff(date_create(), date_create($r->tgl_lahir));
				$tgl_lahir =  $dif->y . ' tahun, ' . $dif->m . ' bulan, ' . $dif->d . ' hari';
			}

			if ($role == '1' or $role == '2' or $role == '5' or $role == '25') {
				$button = '<a href="' . base_url() . 'index.php/Recruitment/pelamar_update/' . $r->recid_pelamar . '"><button class="btn btn-info btn-xs"><span class="fa fa-edit"></span></button></a>
				<a href="' . base_url() . 'index.php/Recruitment/pelamar_detail/' . $r->recid_pelamar . '"><button class="btn btn-success btn-xs"><span class="fa fa-search-plus"></span></button></a>';
			} else {
				$button = "";
			}
			$data[] = array(
				$i++,
				$r->tgl_regis,
				$r->no_ktp,
				$r->nama_pelamar,
				$r->pjenkel,
				$tgl_lahir,
				$r->ppendidikan,
				$r->pjurusan,
				$r->kota_name,
				$r->no_telp,
				$r->email,
				$r->profile_disc,
				$r->pattern_type,
				$r->profile_type,
				$button
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

	public function r_disc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == true) {
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/r_disc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth');
		}
	}


	public function result_disc_pelamar()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == true) {
			$sejak = $this->input->post('sejak');
			$sejak = $sejak . " 00:00:00";
			$sampai = $this->input->post('sampai');
			$sampai = $sampai . " 23:59:59";
			$data['report'] = $this->m_hris->result_disc_pelamar_periode($sejak, $sampai);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/recruitment_disc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth');
		}
	}

	public function detail_report_disc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == true) {
			$id = $this->uri->segment(3);
			$disc_employee = $this->m_hris->disc_by_pelamar($id);
			foreach ($disc_employee->result() as $d) {
				$tgl_test = $d->tgl_test;
			}
			$data['tgl_test'] = $tgl_test;
			$data['profil'] = $this->m_hris->disc_by_pelamar($id);
			$data['tinggi_s'] = $this->m_hris->hitung_jawaban_tinggi($id, 'S')->num_rows();
			$data['tinggi_k'] = $this->m_hris->hitung_jawaban_tinggi($id, 'K')->num_rows();
			$data['tinggi_b'] = $this->m_hris->hitung_jawaban_tinggi($id, 'B')->num_rows();
			$data['tinggi_z'] = $this->m_hris->hitung_jawaban_tinggi($id, 'Z')->num_rows();
			$data['tinggi_n'] = $this->m_hris->hitung_jawaban_tinggi($id, 'N')->num_rows();
			$data['rendah_s'] = $this->m_hris->hitung_jawaban_rendah($id, 'S')->num_rows();
			$data['rendah_k'] = $this->m_hris->hitung_jawaban_rendah($id, 'K')->num_rows();
			$data['rendah_b'] = $this->m_hris->hitung_jawaban_rendah($id, 'B')->num_rows();
			$data['rendah_z'] = $this->m_hris->hitung_jawaban_rendah($id, 'Z')->num_rows();
			$data['rendah_n'] = $this->m_hris->hitung_jawaban_rendah($id, 'N')->num_rows();
			$data['total_rendah'] = $this->m_hris->total_jawaban_rendah($id)->num_rows();
			$data['total_tinggi'] = $this->m_hris->total_jawaban_tinggi($id)->num_rows();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('recruitment/detail_disc_pelamar', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth');
		}
	}

	public function update_profile()
	{
		$mdf_by = $this->session->userdata('recid_karyawan');
		$mdf_date = date('Y-m-d h:i:s');
		$recid_karyawan = $this->input->post('recid_karyawan');
		$profile_disc = $this->input->post('profile_disc');
		$pattern_type = $this->input->post('pattern_type');
		$profile_type = $this->input->post('profile_type');
		$data = array(
			'mdf_by' => $mdf_by,
			'mdf_date' => $mdf_date,
			'profile_disc' => $profile_disc,
			'pattern_type' => $pattern_type,
			'profile_type' => $profile_type,
		);
		$this->m_hris->update_disc_pelamar($data, $recid_karyawan);
		redirect('index.php/Recruitment/result_disc_pelamar');
	}
}

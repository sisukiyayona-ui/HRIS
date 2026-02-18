<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karir extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_hris'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
		$this->load->library('email');
	}

	public function karir_view()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['karir'] = $this->m_hris->karir_view();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('new_karir/karir_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karir_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			if ($role == '26') {
				$data['karyawan'] = $this->m_hris->spm_view();
			} else {
				$data['karyawan'] = $this->m_hris->karyawan_view2();
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			// $data['karyawan2'] = $this->m_hris->karyawan_view();
			$data['legal'] = $this->m_hris->legal_view();
			$data['bagian'] = $this->m_hris->bagian_view();
			$data['sub_bagian'] = $this->m_hris->sub_bagian_view();
			$data['jabatan'] = $this->m_hris->jabatan_view();
			$data['golongan'] = $this->m_hris->golongan_view();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('new_karir/karir_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function histori_karir()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$role = $this->session->userdata('role_id');
			$usr = $this->session->userdata('kar_id');
			$cek_usr = $this->m_hris->cek_usr($usr);
			foreach ($cek_usr as $user) {
				$nama = $user->nama_karyawan;
				$bagian = $user->indeks_hr;
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$recid_karyawan = $this->uri->segment(3);
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['karir'] = $this->m_hris->karir_history($recid_karyawan);
			$data['sanksi'] = $this->m_hris->sanksi_history($recid_karyawan);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('new_karir/histori_karir', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function karir_pinsert()
	{
		$recid_karyawan = $this->input->post('nik'); // di form recid karyawan namanya nik
		$nik1 = $this->input->post('nik1');
		$nik2 = $this->input->post('nik2');
		$nik = "$nik1$nik2";
		$kategori = $this->input->post('kategori');
		$jenis_sanksi = $this->input->post('jenis_sanksi');
		$tgl_m_karir = $this->input->post('tgl_m_karir');
		$tgl_a_karir = $this->input->post('tgl_a_karir');
		$tgl_akhir_karir = $this->input->post('tgl_a_karir');
		if ($tgl_a_karir == "") {
			$tgl_a_karir = "0000-00-00";
		} else {
			$tgl_a_karir = $tgl_a_karir;
		}
		$recid_bag = $this->input->post('recid_bag');
		$recid_subbag = $this->input->post('recid_subbag');
		$recid_jbtn = $this->input->post('recid_jbtn');
		$recid_golongan = $this->input->post('recid_golongan');
		$penempatan = $this->input->post('penempatan');
		$sts_jbtn = $this->m_hris->jabatan_by_recid($recid_jbtn);
		$no_perjanjian = $this->input->post('no_perjanjian');
		$jenis_perjanjian = "Karyawan";
		$judul_perjanjian = $this->input->post('judul_perjanjian');
		$note = $this->input->post('note');
		$scan_perjanjian = $this->input->post('scan_perjanjian');
		$err_upload = 0;

		if ($_FILES['scan_perjanjian']['name'] != '') {
			$config3 = array();
			$config3['upload_path'] 		= './images/legal/';
			$config3['allowed_types'] 		= 'pdf';
			$config3['max_size'] 			= '2500000';
			$config3['file_name'] 	     	= $judul_perjanjian;
			$config3['encrypt_name'] 		= TRUE;
			$this->load->library('upload', $config3, 'scan');  // Create custom object for catalog upload
			$this->scan->initialize($config3);
			$upload_file = $this->scan->do_upload('scan_perjanjian');
			$saved_file_name = $this->scan->data('file_name');

			if (!$upload_file) {
				$err_upload = 1;
			} else {
				$err_upload = 0;
			}
		} else {
			$saved_file_name = '';
		}

		if ($err_upload == 1) {
			echo 'File upload Error : ' . $this->scan->display_errors() . '<br/>';
		} else {
			$datalegal = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'no_perjanjian'			=> $no_perjanjian,
				'jenis_perjanjian'		=> $jenis_perjanjian,
				'judul_perjanjian'		=> $judul_perjanjian,
				'tgl_m_legal'			=> $tgl_m_karir,
				'tgl_a_legal'			=> $tgl_a_karir,
				'scan_perjanjian'		=> $saved_file_name,
				'note'					=> $note
			);
			//SAVE LEGAL
			$this->m_hris->legal_pinsert($datalegal);

			// GET RECID LEGAL
			$cek_legal = $this->m_hris->legal_last();
			foreach ($cek_legal as $recid) {
				$recid_legal = $recid->recid_legal;
			}

			if ($kategori == 'Awal') {
				$kategori = 'Awal';
				$datakarir = array(
					'crt_by'			=> $this->session->userdata('kar_id'),
					'crt_date'			=> date('y-m-d h:i:s'),
					'kategori'			=> $kategori,
					'recid_karyawan'	=> $recid_karyawan,
					'recid_legal'		=> $recid_legal,
					'tgl_m_karir'		=> $tgl_m_karir,
					'tgl_a_karir'		=> $tgl_a_karir,
					'recid_bag'			=> $recid_bag,
					'recid_subbag'			=> $recid_subbag,
					'recid_jbtn'		=> $recid_jbtn,
					'recid_golongan'		=> $recid_golongan,
					'penempatan'		=> $penempatan,
					'note'				=> $note,
				);
				$this->m_hris->karir_pinsert($datakarir);

				$cek_kry = $this->m_hris->get_data($recid_karyawan);
				foreach ($cek_kry as $kry) {
					$nama_karyawan = $kry->nama_karyawan;
				}
				$cek_lamar = $this->m_hris->cek_generate($nama_karyawan);
				if ($cek_lamar->num_rows() > 0) {
					$lamar = $this->m_hris->cek_generate($nama_karyawan)->result();
					foreach ($lamar as $lamar) {
						$nama = $lamar->nama_pelamar;
						$recid = $lamar->recid_pelamar;
					}
					$updt = array(
						'nik'		=> $nik,
					);
					$this->m_hris->pelamar_pupdate($updt, $recid);
				} else {
					echo  'bukan dari rekrutmen';
				}
				$sts_aktif = 'Aktif';

				$datakary = array(
					'nik'				=> $nik,
					'tgl_m_kerja'		=> $tgl_m_karir,
					'sts_aktif'			=> $sts_aktif,
					'recid_bag'			=> $recid_bag,
					'recid_jbtn'		=> $recid_jbtn,
					'recid_golongan'		=> $recid_golongan,
					'penempatan'		=> $penempatan,
					'covid_uname'		=> $nik,
					'covid_pwd'			=> md5($nik),
					'covid_role'		=> 3,
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('y-m-d h:i:s')
				);
				$updt_kary = $this->m_hris->karyawan_update($datakary, $recid_karyawan);
				//set log_karyawan
				$text = "tgl_m : $tgl_m_karir, tgl_a : $tgl_a_karir, aktif = $sts_aktif, bag : $recid_bag, jbtn : $recid_jbtn";
				$data5 = array(
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $recid_karyawan,
				);
				$this->m_hris->karyawan_linsert($data5);
			} else if ($kategori == "Akhir")	// akhir legal
			{
				$sts_aktif = $this->input->post('sts_aktif');
				$kategori = 'Akhir';
				$cek_kry = $this->m_hris->get_data($recid_karyawan);
				foreach ($cek_kry as $kry) {
					$recid_bag = $kry->recid_bag;
					$recid_jbtn = $kry->recid_jbtn;
					$recid_golongan = $kry->recid_golongan;
				}
				$datakarir = array(
					'crt_by'			=> $this->session->userdata('kar_id'),
					'crt_date'			=> date('y-m-d h:i:s'),
					'kategori'			=> $kategori,
					'recid_karyawan'	=> $recid_karyawan,
					'recid_legal'		=> $recid_legal,
					'tgl_m_karir'		=> $tgl_m_karir,
					'tgl_a_karir'		=> $tgl_a_karir,
					'recid_bag'			=> $recid_bag,
					'recid_subbag'			=> $recid_subbag,
					'recid_jbtn'		=> $recid_jbtn,
					'recid_golongan'	=> $recid_golongan,
					'penempatan'		=> $penempatan,
					'note'				=> $note,
				);
				$this->m_hris->karir_pinsert($datakarir);

				$datakary = array(
					'sts_aktif'			=> $sts_aktif,
					'recid_bag'			=> $recid_bag,
					'recid_jbtn'		=> $recid_jbtn,
					'recid_golongan'		=> $recid_golongan,
					'penempatan'		=> $penempatan,
					'tgl_a_kerja'		=> $tgl_m_karir,
					'mdf_by'			=> $this->session->userdata('kar_id'),
					'mdf_date'			=> date('y-m-d h:i:s'),
				);
				$updt_kary = $this->m_hris->karyawan_update($datakary, $recid_karyawan);
				//set log_karyawan
				$text = "tgl_m : $tgl_m_karir, tgl_a : $tgl_a_karir, aktif = $sts_aktif, bag : $recid_bag, jbtn : $recid_jbtn";
				$data5 = array(
					'mdf_by'		=> $this->session->userdata('kar_id'),
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $recid_karyawan,
				);
				$this->m_hris->karyawan_linsert($data5);
			} else {
				$kategori = $this->input->post('kategori');
				$datakarir = array(
					'crt_by'			=> $this->session->userdata('kar_id'),
					'crt_date'			=> date('y-m-d h:i:s'),
					'kategori'			=> $kategori,
					'jenis_sanksi'		=> $jenis_sanksi,
					'recid_karyawan'	=> $recid_karyawan,
					'recid_legal'		=> $recid_legal,
					'tgl_m_karir'		=> $tgl_m_karir,
					'tgl_a_karir'		=> $tgl_a_karir,
					'recid_bag'			=> $recid_bag,
					'recid_subbag'			=> $recid_subbag,
					'recid_jbtn'		=> $recid_jbtn,
					'recid_golongan'		=> $recid_golongan,
					'penempatan'		=> $penempatan,
					'note'				=> $note,
				);
				$this->m_hris->karir_pinsert($datakarir);
			}
		}
		redirect('index.php/Karir/histori_karir/' . $recid_karyawan);
	}

	public function set_current()
	{
		$recid_karir = $this->uri->segment(3);
		$karir = $this->m_hris->karir($recid_karir);
		foreach ($karir->result() as $karir) {
			$recid_karyawan = $karir->recid_karyawan;
			$bag = $karir->recid_bag;
			$sub_bag = $karir->recid_subbag;
			$jbtn = $karir->recid_jbtn;
			$golongan = $karir->recid_golongan;
			$penempatan = $karir->penempatan;
			$tgl_a_kerja = $karir->tgl_a_karir;
		}
		echo "$recid_karir - " . $bag . " - " . $jbtn . " - " . $tgl_a_kerja;
		$datakary = array(
			'recid_bag'			=> $bag,
			'recid_subbag'			=> $sub_bag,
			'recid_jbtn'		=> $jbtn,
			'recid_golongan'		=> $golongan,
			'penempatan'		=> $penempatan,
			'tgl_a_kerja'		=> $tgl_a_kerja,
			'mdf_by'			=> $this->session->userdata('kar_id'),
			'mdf_date'			=> date('y-m-d h:i:s'),
		);
		$updt_kary = $this->m_hris->karyawan_update($datakary, $recid_karyawan);
		//set log_karyawan
		$text = "bag : $bag, jbtn : $jbtn, tgl_a_kerja : $tgl_a_kerja";
		$data5 = array(
			'mdf_by'		=> $this->session->userdata('kar_id'),
			'mdf_date'		=> date('y-m-d h:i:s'),
			'changed'		=> $text,
			'identity'		=> $recid_karyawan,
		);
		$this->m_hris->karyawan_linsert($data5);
		redirect('index.php/Karir/histori_karir/' . $recid_karyawan);
	}

	public function email_expedisi()
	{
		//Load email library
		$this->load->library('email');

		$recipients = $this->m_hris->expedisi_karir();
		$terima = array();
		foreach ($recipients->result() as $o) {
			array_push($terima, $o->email);
		}
		array_push($terima, "diah@chitose.id");
		array_push($terima, "legal@chitose.id");
		array_push($terima, "training@chitose.id");
		array_push($terima, "hrd@chitose.id");
		array_push($terima, "anysah@chitose.id");

		$tgl = date('d-M-Y');
		$title = "EKSPEDISI DIGITAL HRIS - " . $tgl;

		//SMTP & mail configuration
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


		$karir = $this->m_hris->notif_karir();
		if ($karir->num_rows() > 0) {

			$no = 1;
			$tbl = "<table border='1'><tr><td><center>No</center></td><td><center>NIK</center></td><td><center>Nama Karyawan</center></td><td><center>Bagian</center></td><td><center>Jabatan</center></td><td><center>Golongan</center></td><td><center>Jenis Karir</center></td><td><center>Periode</center></td></tr>";
			foreach ($karir->result() as $kr) {
				$tbl .=
					"<tr><td><center>" . $no++ . "</center></td><td>$kr->nik</td><td>$kr->nama_karyawan</td><td>$kr->indeks_hr</td><td>$kr->indeks_jabatan</td><td>$kr->nama_golongan</td><td><center>$kr->kategori</center></td><td>$kr->tgl_m_karir</td></tr>";
			}
			$tbl =  $tbl . "</table>";

			//Email content
			$htmlContent = '<p>' . $tgl . '</p>';
			$htmlContent .= '<p>Dear All,  </p>';
			$htmlContent .= '<p>Berikut ekpedisi digital HRIS.</p>';
			$htmlContent .= '<p>Detail dan Scan berkas ekspedisi terkait dapat diakses dan didownload di Akun HRIS anda.</p>';
			$htmlContent .= '<h3>EKSPEDISI BERKAS HC </h3>';
			$htmlContent .=  $tbl;
			$htmlContent .= '<p><br>Mohon untuk dapat update data segera setelah mendapatkan notifikasi ini.</p>';
			$htmlContent .= '<p><br>Buka akun HRIS : https://hris.chitose.id </p>';
			$htmlContent .= '<p>Terima Kasih</p>';
			$htmlContent .= '<p><br>Regards</p>';
			$htmlContent .= '<p><u>HC & GA Departement</u></p>';
			$htmlContent .= '<p>PT. CHITOSE INTERNASIONAL TBK</p>';
			// echo $htmlContent;

			// $recipients = array('it.sysdev@chitose-indonesia.com');
			$this->email->to(implode(', ', $terima));
			// $this->email->to($recipients);
			$this->email->from('hrd@chitose.id', 'Human Resources Chitose');
			$this->email->subject($title);
			$this->email->message($htmlContent);

			//Send email

			if ($this->email->send()) {
				echo "Email Has Been Sent!";
				$this->notif_user_cis();
				$this->notif_email_spm();
				// redirect('index.php/Karir/success_sent_mail');
			} else {
				echo "Email Failed";
				show_error($this->email->print_debugger());
			}
		} else {
			redirect('index.php/Karir/no_sent_mail');
		}
	}

	public function notif_user_cis()
	{
		//Load email library
		$this->load->library('email');

		$recipients = $this->m_hris->expedisi_karir();
		$terima = array();
		array_push($terima, "anysah@chitose.id");
		array_push($terima, "andhika@chitose.id");
		array_push($terima, "mis@chitose.id");

		$tgl = date('d-M-Y');
		$title = "Notifikasi Update User CIS " . $tgl;

		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.chitose.id',
			'smtp_port' => 25,
			'smtp_user' => 'andhika@chitose.id',
			'smtp_pass' => '$Dhika1177',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$karir = $this->m_hris->notif_karir();
		$same_bag = 0;
		if ($karir->num_rows() > 0) {
			$no = 1;
			$tbl = "<table border='1'><tr><td><center>No</center></td><td><center>NIK</center></td><td><center>Nama Karyawan</center></td><td><center>Bagian</center></td><td><center>Jabatan</center></td><td><center>Jenis Karir</center></td></tr>";
			foreach ($karir->result() as $kr) {
				$cek_bcs = $this->m_hris->cek_bag_cis($kr->recid_bag);
				if ($cek_bcs->num_rows() > 0) {
					$same_bag = $same_bag + 1;
				}

				if ($same_bag > 0) {
					$tbl .=
						"<tr><td><center>" . $no++ . "</center></td><td>$kr->nik</td><td>$kr->nama_karyawan</td><td>$kr->indeks_hr</td><td>$kr->indeks_jabatan</td><td><center>$kr->kategori</center></td></tr>";
				}
			}
			$tbl =  $tbl . "</table>";

			//Email content
			$htmlContent = '<p>' . $tgl . '</p>';
			$htmlContent .= '<p>Dear Team IT,  </p>';
			$htmlContent .= '<p>Berdasarkan ekpedisi digital HRIS, berikut user CIS yang perlu dicek kembali.</p>';
			$htmlContent .= '<h3>Data User HRIS : </h3>';
			$htmlContent .=  $tbl;
			$htmlContent .= '<p><br>Mohon untuk dapat update database segera setelah mendapatkan notifikasi ini.</p>';
			$htmlContent .= '<p>Terima Kasih</p>';
			$htmlContent .= '<p><br>Regards</p>';
			$htmlContent .= '<p><u>SAP System Development</u></p>';
			$htmlContent .= '<p>PT. CHITOSE INTERNASIONAL TBK</p>';
			// echo $htmlContent;

			// $recipients = array('it.sysdev@chitose-indonesia.com');
			$this->email->to(implode(', ', $terima));
			$this->email->from('andhika@chitose.id', 'SAP Development');
			$this->email->subject($title);
			$this->email->message($htmlContent);

			//Send email
			if ($same_bag > 0) {
				if ($this->email->send()) {
					echo "Email Has Been Sent!";
					redirect('index.php/Karir/success_sent_mail');
				} else {
					echo "Email Failed";
					show_error($this->email->print_debugger());
				}
			}
		} else {
			redirect('index.php/Karir/no_sent_mail');
		}
	}

	public function notif_email_spm()
	{
		//Load email library
		$this->load->library('email');

		$recipients = $this->m_hris->expedisi_karir();
		$terima = array();
		array_push($terima, "adminb2c@chitose.id");
		// array_push($terima, "it.sysdev@chitose-indonesia.com");

		$tgl = date('d-M-Y');
		$title = "EKSPEDISI DIGITAL SPM HRIS - " . $tgl;

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


		$karir = $this->m_hris->notif_karir();
		$same_bag = 0;
		if ($karir->num_rows() > 0) {
			$no = 1;
			$tbl = "<table border='1'><tr><td><center>No</center></td><td><center>NIK</center></td><td><center>Nama Karyawan</center></td><td><center>Bagian</center></td><td><center>Jabatan</center></td><td><center>Jenis Karir</center></td></tr>";
			foreach ($karir->result() as $kr) {
				if ($kr->recid_bag == 140) {
					$same_bag = $same_bag + 1;
				}

				if ($same_bag > 0) {
					$tbl .=
						"<tr><td><center>" . $no++ . "</center></td><td>$kr->nik</td><td>$kr->nama_karyawan</td><td>$kr->indeks_hr</td><td>$kr->indeks_jabatan</td><td><center>$kr->kategori</center></td></tr>";
				}
			}
			$tbl =  $tbl . "</table>";

			//Email content
			$htmlContent = '<p>' . $tgl . '</p>';
			$htmlContent .= '<p>Dear All,  </p>';
			$htmlContent .= '<p>Berikut ekpedisi digital HRIS.</p>';
			$htmlContent .= '<p>Detail dan Scan berkas ekspedisi terkait dapat diakses dan didownload di Akun HRIS anda.</p>';
			$htmlContent .= '<h3>EKSPEDISI BERKAS HC </h3>';
			$htmlContent .=  $tbl;
			$htmlContent .= '<p><br>Mohon untuk dapat update data segera setelah mendapatkan notifikasi ini.</p>';
			$htmlContent .= '<p><br>Buka akun HRIS : https://hris.chitose.id </p>';
			$htmlContent .= '<p>Terima Kasih</p>';
			$htmlContent .= '<p><br>Regards</p>';
			$htmlContent .= '<p><u>HC & GA Departement</u></p>';
			$htmlContent .= '<p>PT. CHITOSE INTERNASIONAL TBK</p>';
			// echo $htmlContent;

			// $recipients = array('it.sysdev@chitose-indonesia.com');
			$this->email->to(implode(', ', $terima));
			// $this->email->to($recipients);
			$this->email->from('hrd@chitose.id', 'Human Resources Chitose');
			$this->email->subject($title);
			$this->email->message($htmlContent);

			if ($same_bag > 0) {
				if ($this->email->send()) {
					echo "Email Has Been Sent!";
					redirect('index.php/Karir/success_sent_mail');
				} else {
					echo "Email Failed";
					show_error($this->email->print_debugger());
				}
			}
		} else {
			redirect('index.php/Karir/no_sent_mail');
		}
	}

	public function success_sent_mail()
	{
		$usr = $this->session->userdata('kar_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
		}
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('layout/success_sent_email');
		$this->load->view('layout/a_footer');
	}

	public function no_sent_mail()
	{
		$usr = $this->session->userdata('kar_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
		}
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('layout/no_sent_email');
		$this->load->view('layout/a_footer');
	}

	public function expedisi_karir()
	{
		$usr = $this->session->userdata('kar_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
		}
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('new_karir/expedisi_karir');
		$this->load->view('layout/a_footer');
	}

	public function cek_email()
	{
		//Load email library
		$this->load->library('email');

		// $recipients = $this->m_hris->expedisi_karir();
		$terima = array();
		array_push($terima, "anysah@chitose.id");

		$tgl = date('d-M-Y');
		$title = "Tes Email";

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

		//Email content
		$htmlContent = '<p>' . $tgl . '</p>';
		$htmlContent .= '<p>Test Email Function</p>';
		$htmlContent .= '<p>Chitose-indonesia.com</p>';
		$htmlContent .= '<p>PT. CHITOSE INTERNASIONAL TBK</p>';
		// echo $htmlContent;

		// $recipients = array('it.sysdev@chitose-indonesia.com');
		$this->email->to(implode(', ', $terima));
		$this->email->from('hrd@chitose.id', 'test');
		$this->email->subject($title);
		$this->email->message($htmlContent);

		//Send email
		if ($this->email->send()) {
			echo "Email Has Been Sent!";
		} else {
			echo "Email Failed";
			show_error($this->email->print_debugger());
		}
	}

	public function generate_promosi()
	{
		$usr = $this->session->userdata('kar_id');
		$cek_usr = $this->m_hris->cek_usr($usr);
		foreach ($cek_usr as $user) {
			$nama = $user->nama_karyawan;
			$bagian = $user->indeks_hr;
			$jabatan = $user->indeks_jabatan;
			$tingkatan = $user->tingkatan;
			$struktur = $user->recid_struktur;
		}
		$data['cek_usr'] = $this->m_hris->cek_usr($usr);
		$data['karyawan'] = $this->m_hris->karyawan_view2();
		$data['bagian'] = $this->m_hris->bagian_view();
		$data['jabatan'] = $this->m_hris->jabatan_view();
		$data['golongan'] = $this->m_hris->golongan_view();
		$this->load->view('layout/a_header');
		$this->load->view('layout/menu_super', $data);
		$this->load->view('new_karir/generate_promosi');
		$this->load->view('layout/a_footer');
	}

	public function promosi_pdf()
	{
		$no_sk = $this->input->post('no_sk');
		$tgl_m_karir = $this->input->post('tgl_m_karir');
		$tgl_m_karir = date("d-m-Y", strtotime($tgl_m_karir));
		$id = $this->input->post('recid_karyawan');
		$detail = $this->m_hris->karyawan_view_by_id($id);
		foreach ($detail->result() as $k) {
			$nik = $k->nik;
			$nama = $k->nama_karyawan;
			$bef_bagian = $k->indeks_hr;
			$bef_dept = $k->nama_struktur;
			$bef_jbtn = $k->indeks_jabatan;
			$bef_golongan = $k->nama_golongan;
		}

		//new position
		$bag = $this->input->post('recid_bag');
		$dept = $this->m_hris->bagian_by_recid2($bag);
		if ($dept->num_rows() > 0) {
			foreach ($dept->result() as $d) {
				$new_dept = $d->nama_struktur;
				$new_bag = $d->indeks_hr;
			}
		} else {
			$new_dept = "";
		}
		$jbtn = $this->input->post('recid_jbtn');
		$jabatan = $this->m_hris->jabatan_by_recid($jbtn);
		foreach ($jabatan as $j) {
			$new_jbtn = $j->indeks_jabatan;
			$new_tingkat = $j->tingkatan;
		}

		$golongan = $this->input->post('recid_golongan');
		$gol = $this->m_hris->golongan_by_recid($golongan);
		foreach ($gol as $g) {
			$new_golongan = $g->nama_golongan;
		}

		// $data['no_sk'] = $no_sk;
		$data = array(
			'no_sk'			=> $no_sk,
			'tgl_m_karir'			=> $tgl_m_karir,
			'nik'			=> $nik,
			'nama'			=> $nama,
			'bef_bagian'	=> $bef_bagian,
			'bef_dept'		=> $bef_dept,
			'bef_jbtn'		=> $bef_jbtn,
			'bef_golongan'	=> $bef_golongan,
			'new_bagian'	=> $new_bag,
			'new_dept'		=> $new_dept,
			'new_jbtn'		=> $new_jbtn,
			'tingkatan'		=> $new_tingkat,
			'new_golongan'	=> $new_golongan,
		);
		// // echo "test";
		// $this->load->view('download/sk_promosi');

		ob_start();
		$mpdf = new \Mpdf\Mpdf(['format' => 'A4-P']);
		$mpdf->AddPageByArray([
			'margin-left' => 15,
			'margin-right' => 15,
			'margin-top' => 15,
			'margin-bottom' => 15,
		]);

		$html = $this->load->view('download/sk_promosi', $data,  true);

		$mpdf->WriteHTML($html);
		ob_end_clean();
		$mpdf->Output();
	}
}

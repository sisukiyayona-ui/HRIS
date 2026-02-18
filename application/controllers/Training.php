<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Training extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_hris', 'm_training'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
		$this->load->library('email');
	}

	public function index()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
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
			if ($role == '2' or $role == '1' or $role == '25' or $role == '24' or $role == '34') {
				$data['training'] = $this->m_training->train_aju()->result();
			} else {
				$data['training'] = $this->m_training->train_aju_by_bagian($bagian)->result();
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_view', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_acc()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$id = $this->uri->segment(3);
			$crt_by = $this->session->userdata('kar_id');
			$data['pengaju'] = $this->m_training->train_ajuan($id);
			$data['training'] = $this->m_training->train_ajuan($id);
			$data['peserta'] = $this->m_training->train_pst($id)->result();
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_acc', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function add_kompetensi()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$kompetensi = $this->input->post('kompetensi');
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'kompetensi'			=> $kompetensi,
				'is_active'				=> 1,
			);
			$result = $this->m_training->kompetensi_insert($data);
			echo json_encode($result);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function kompetensi_aktif()
	{

		$kompetensi = $this->m_training->kompetensi_aktif();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>-- Pilih --</option>";
		foreach ($kompetensi->result() as $data) {
			$lists .= "<option value='" . $data->recid_komp . "'>" . $data->kompetensi . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function train_hcacc()
	{
		$recid_training = $this->input->post('recid_training');
		$judul_training = $this->input->post('judul_training');
		// $no_perjanjian = $this->input->post('no_perjanjian');
		// $scan_perjanjian = $this->input->post('scan_perjanjian');
		$metoda = $this->input->post('metoda');
		$tgl_m_training = $this->input->post('tgl_m_training');
		$tgl_a_training = $this->input->post('tgl_a_training');
		$kompetensi = $this->input->post('kompetensi');
		$sertifikat = $this->input->post('sertifikat');
		$tna = $this->input->post('tna');
		$evaluasi = $this->input->post('evaluasi');
		$poin = $this->input->post('poin');
		$acc_hc = $this->input->post('acc_hc');
		$status_acc = "Approved";
		$acc_direksi = $this->input->post('acc_direksi');
		$scan_direksi = $this->input->post('scan_direksi');
		$note = $this->input->post('note');
		// echo $recid_training;

		$err_dir = 0;
		$nama_dir = $_FILES['scan_direksi']['name'];
		if ($_FILES['scan_direksi']['name'] != '') {
			// Scan Direksi
			$config4 = array();
			$config4['upload_path'] 		= './images/training/';
			$config4['allowed_types'] 		= 'jpg|png|jpeg|pdf';
			$config4['max_size'] 			= '2000000';
			$config4['file_name'] 	     	= $nama_dir;
			$config4['overwrite']       	 = FALSE;
			$config4['encrypt_name']    	 = TRUE;
			$this->load->library('upload', $config4, 'sc_dir');  // Create custom object for catalog upload
			$this->sc_dir->initialize($config4);
			$path_foto = './images/training/';
			$upload_foto = $this->sc_dir->do_upload('scan_direksi');
			$saved_dir_name = $this->sc_dir->data('file_name');
			if (!$upload_foto) {
				$err_dir = 1;
			} else {
				$err_dir = 0;
			}
			// echo $saved_dir_name;
		} else {
			$saved_dir_name = '';
		}

		if ($err_dir == 1) {
			echo 'Scan Direksi upload Error : ' . $this->sc_dir->display_errors() . '<br/>';
		} else {
			//INSERT DATA TRAINING - LEGAL - KARIR - TRAINING PARTICIPANT
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'jenis_perjanjian' 		=> 'Karyawan',
				// 'no_perjanjian'			=> $no_perjanjian,
				'judul_perjanjian'		=> $judul_training,
				'tgl_m_legal'			=> $tgl_m_training,
				'tgl_a_legal'			=> $tgl_a_training,
				// 'scan_perjanjian'		=> $saved_sk_name,
				'note'					=> $note
			);
			$this->m_hris->legal_pinsert($data);

			$recid_legal = $this->m_hris->legal_last();
			foreach ($recid_legal as $key) {
				$recid_legal = $key->recid_legal;
			}

			$data2 = array(
				'recid_legal'			=> $recid_legal,
				'recid_komp'			=> $kompetensi,
				'metoda'				=> $metoda,
				'sertifikat'			=> $sertifikat,
				'tna'					=> $tna,
				'evaluasi'				=> $evaluasi,
				'acc_hc'				=> $acc_hc,
				'acc_direksi'			=> $acc_direksi,
				'status_acc'			=> $status_acc,
				'scan_direksi'			=> $saved_dir_name,
				'poin'					=> $poin,
				'note'					=> $note,
				'mdf_date'				=> date('y-m-d h:i:s'),
				'mdf_by'				=> $this->session->userdata('kar_id')

			);
			$this->m_training->training_pupdate($recid_training, $data2);

			$peserta = $this->m_training->train_pst($recid_training);
			foreach ($peserta->result() as $pst) {
				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'kategori'				=> 'Training',
					'recid_legal'			=> $recid_legal,
					'tgl_m_karir'			=> $tgl_m_training,
					'tgl_a_karir'			=> $tgl_a_training,
					'recid_karyawan'		=> $pst->recid_karyawan,
					'note'					=> $note,
				);
				$this->m_hris->karir_pinsert($data3);
			}


			$participant = $this->m_training->train_pst($recid_training);
			foreach ($peserta->result() as $pst) {
				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_training'		=> $recid_training,
					'recid_karyawan'		=> $pst->recid_karyawan,
				);
				$this->m_training->participant_insert($data3);
			}
			$this->m_training->pst_delete($recid_training);

			//SEND EMAIL//
			$pengaju = $this->m_training->train_ajuan($recid_training);
			foreach ($pengaju as $key) {
				$pengaju = $key->crt_by;
			}
			$this->email_training($pengaju, $recid_training);
			// redirect('index.php/Training/index');
		}
	}

	public function email_training($e_aju, $training)
	{


		//Load email library
		$this->load->library('email');

		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.chitose.id',
			'smtp_port' => 25,
			'smtp_user' => 'training@chitose.id',
			'smtp_pass' => '$trnHRD',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$recipients = array();

		$email_atasan = "";
		$train = $this->m_training->train_ajuan($training);
		foreach ($train as $t) {
			$no_form = $t->no_form;
			$no_form = "CINT/HC-GA/" . $no_form . "/Formulir Usulan Pelatihan";
			$tgl_aju = $t->tgl_pengajuan;
			$tgl_aju = date("d M Y",  strtotime($tgl_aju));
			$topik = $t->judul_training;
			$status_acc = $t->status_acc;
			$atasan = $t->atasan;
			$tgl_m_training = $t->tgl_m_training;
			$tgl_m_training = date("d M Y",  strtotime($tgl_m_training));
			$tgl_a_training = $t->tgl_a_training;
			$tgl_a_training = date("d M Y",  strtotime($tgl_a_training));
			if ($status_acc == "Approved") {
				$note = "Pengajuan Ini Telah Disetujui";
			} else {
				$note = "Pengajuan Ini Ditolak";
			}
		}


		$e_atasan = $this->m_hris->karyawan_current_karir($e_aju);
		foreach ($e_atasan as $aju) {
			$atasan1 = $aju->atasan1;
			$atasan2 = $aju->atasan2;
			$atasan2 = $aju->atasan2;
			$eatasan1 = $aju->email1;
			$eatasan2 = $aju->email2;
			$email = $aju->email;
			$email_cint = $aju->email;

			if ($email_cint == '') {
				$email_pengaju = $email;
			} else {
				$email_pengaju = $email_cint;
			}

			if ($atasan1 == $atasan2) {
				if ($eatasan1 == $email_pengaju) {
					$e_notif = $eatasan1;
				} else {
					$e_notif = $eatasan1 . ', ' . $email_pengaju;
				}
			} else {
				if ($eatasan1 == '') {
					if ($eatasan2 == $email_pengaju) {
						$e_notif = $eatasan2;
					} else {
						$e_notif = $eatasan2 . ', ' . $email_pengaju;
					}
				} else if ($eatasan2 == '') {
					if ($eatasan1 == $email_pengaju) {
						$e_notif = $eatasan1;
					} else {
						$e_notif = $eatasan1 . ', ' . $email_pengaju;
					}
				} else {
					if ($eatasan1 == $email_pengaju) {
						$e_notif = $eatasan2 . ', ' . $email_pengaju;
					} else if ($eatasan2 == $email_pengaju) {
						$e_notif = $eatasan1 . ', ' . $email_pengaju;
					} else {
						$e_notif = $eatasan1 . ', ' . $eatasan2 . ', ' . $email_pengaju;
					}
				}
			}
		}

		array_push($recipients, $e_notif);
		array_push($recipients, "training@chitose.id");
		array_push($recipients, "diah@chitose.id");
		$recipients = array_unique($recipients);

		$title = "Notifikasi Pengajuan Training";

		echo json_encode($recipients);
		//Email content
		$htmlContent = '<h3>Notifikasi Pengajuan Training</h3>';
		$htmlContent .= '<p>Berikut disampaikan hasil pengajuan usulan training dengan detail sebagai berikut : </p>';
		$htmlContent .= '<table><tr><td>Nomor Form Pengajuan</td><td> : &nbsp;' . $no_form . '</td></tr><tr><td>Tanggal Pengajuan</td><td>: &nbsp;' . $tgl_aju . '</td></tr><tr><td>Topik Training </td><td> : &nbsp; ' . $topik . '</td></tr><tr><td>Tanggal Training</td><td> : &nbsp;' . $tgl_m_training . ' - ' . $tgl_a_training . '</td></tr></table>';
		$htmlContent .= '<p><br><h3><b>' . $note . '</b></h3></p>';
		$htmlContent .= '<p>Terima Kasih</p>';
		$htmlContent .= '<p><u>Recruitment & Training</u></p>';
		echo $htmlContent;

		// $recipients = array('anysah.murti@gmail.com', 'it.sysdev@chitose-indonesia.com', 'anysah.rinjani@gmail.com');
		$this->email->to(implode(', ', $recipients));
		$this->email->from('training@chitose.id', 'Training Chitose');
		$this->email->subject($title);
		$this->email->message($htmlContent);

		//Send email

		// if($this->email->send())
		// {
		// 	echo "Email Has Been Sent!";
		// 	redirect('index.php/Training');
		// }else{
		// 	echo "Email Failed";
		// 	 show_error($this->email->print_debugger());
		// }
		redirect('index.php/Training');
	}

	public function train_denied()
	{
		$recid_training = $this->uri->segment(3);
		$data2 = array(
			'status_acc'			=> "Denied",
			'mdf_date'				=> date('y-m-d h:i:s'),
			'mdf_by'				=> $this->session->userdata('kar_id')
		);
		$this->m_training->training_pupdate($recid_training, $data2);

		/*SEND EMAIL*/
		$pengaju = $this->m_training->train_ajuan($recid_training);
		foreach ($pengaju as $key) {
			$pengaju = $key->crt_by;
		}
		$this->email_training($pengaju, $recid_training);
		// redirect('Karyawan/training_view');
	}

	public function training_insert2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['master'] = $this->m_training->training_master();
			$crt_by = $this->session->userdata('kar_id');
			$pengaju = $this->m_hris->karyawan_current_karir($crt_by);
			foreach ($pengaju as $aju) {
				$nama = $aju->nama_karyawan;
				$bagian = $aju->indeks_hr;
				$jabatan = $aju->indeks_jabatan;
				$department = $aju->nama_department;
				$recid_karyawan = $aju->recid_karyawan;
			}

			$data['nama'] = $nama;
			$data['bagian'] = $bagian;
			$data['jabatan'] = $jabatan;
			$data['department'] = $department;
			$data['recid_karyawan'] = $recid_karyawan;

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_insert2', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['karyawan'] = $this->m_hris->karyawan_view();
			$data['master'] = $this->m_training->training_master();

			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_insert', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_pinsert2()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$seq = $this->m_training->count_training();
			$count = $seq->num_rows();
			$count = $count + 1;
			if ($count < 10) {
				$count = "00$count";
			} else if ($count < 100 && $count >= 10) {
				$count = "0$count";
			} else {
				$count;
			}
			$bulan = date('M');
			$tahun = date('Y');
			$no_form = "$count/$bulan/$tahun";

			$crt_by = $this->input->post('crt_by');
			$tgl_pengajuan = $this->input->post('tgl_pengajuan');
			$jenis_training = $this->input->post('jenis_training');
			$kategori = $this->input->post('kategorit');
			$recid_mtlevel = $this->input->post('recid_mtlevel');
			$judul_training = $this->input->post('judul_training');
			$lembaga = $this->input->post('lembaga');
			$trainer = $this->input->post('trainer');
			$tgl_m_training = $this->input->post('tgl_m_training');
			$tgl_a_training = $this->input->post('tgl_a_training');
			$jml_jam = $this->input->post('jml_jam');
			$tempat_training = $this->input->post('tempat_training');
			$berbayar = $this->input->post('berbayar');
			$biaya = $this->input->post('biaya');
			$recid_karyawan = $this->input->post('nik');
			$scan_brosur = $this->input->post('scan_brosur');
			$alasan = $this->input->post('alasan');
			$no_perjanjian = $this->input->post('no_perjanjian');
			$scan_perjanjian = $this->input->post('scan_perjanjian');
			// $metoda = $this->input->post('metoda');
			$sertifikat = $this->input->post('sertifikat');
			$tna = $this->input->post('tna');
			$evaluasi = $this->input->post('evaluasi');
			$poin = $this->input->post('poin');
			$acc_hc = $this->input->post('acc_hc');
			$acc_direksi = $this->input->post('acc_direksi');
			$note = $this->input->post('note');
			$notif = $this->input->post('notifikasi');

			$err_brosur = 0;
			$nama_brosur = $_FILES['scan_brosur']['name'];

			if ($_FILES['scan_brosur']['name'] != '') {
				// Brosur Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/training/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg|pdf';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_brosur;
				$config3['overwrite']        = FALSE;
				$config3['encrypt_name']     = TRUE;
				$this->load->library('upload', $config3, 'sc_brosur');  // Create custom object for catalog upload
				$this->sc_brosur->initialize($config3);
				$path_foto = './images/training/';
				$upload_foto = $this->sc_brosur->do_upload('scan_brosur');
				$saved_brosur_name = $this->sc_brosur->data('file_name');
				if (!$upload_foto) {
					$err_brosur = 1;
				} else {
					$err_brosur = 0;
				}
				echo $saved_brosur_name;
			} else {
				$saved_brosur_name = '';
			}

			if ($err_brosur == 1) {
				echo 'Brosur upload Error : ' . $this->sc_brosur->display_errors() . '<br/>';
			} else {
				// ******************************* SAVE PROCESS *******************************************************
				if ($kategori == 'Khusus') {
					$pesan = "<div class='alert alert-danger' style='max_height:auto;'>";
					$hitung = 0; // yang belum ikut training
					$hitungsudah = 0; // yang sudah ikut training
					$hitung2 = 0; // yang trainingnya blm pernah ada yang ikutin
					// echo "<br>*******<br>".$recid_karyawan." - ";

					// CEK CURRENT LEVEL
					$current = $this->current_level($recid_mtlevel);
					foreach ($current as $current) {
						$recid_mt = $current[0];
						$level = $current[1];
					}
					// echo $recid_mt." - ".$level." - ";
					$bawah = $level - 1;
					if ($bawah == 0) {
						$hitungsudah = $hitungsudah + 1;
					} else {
						//COUNT JML LEVEL PREVIOUS PER MASTER DAN LEVEL CURRENT
						$jm_level = $this->m_training->jml_level($recid_mt, $level);
						foreach ($jm_level as $jml_level) {
							$jml_level = $jml_level->jml_level;
						}
						// echo $jml_level." # <br>";
						for ($k = 0; $k < count($recid_karyawan); $k++) {
							$nama_karya = $this->m_hris->karyawan_by_recid($recid_karyawan[$k]);
							foreach ($nama_karya as $karyawan) {
								$nama_karyawan = $karyawan->nama_karyawan;
							}
							for ($x = $jml_level; $x > 0; $x--) {
								// echo "<br>looping master $recid_mt, level ke $x punya $recid_karyawan <br> ";
								$a = $this->m_training->get_levelprev($recid_mt, $x);
								foreach ($a as $b) {
									$c = $b->recid_mtlevel;
									$nama_train = $b->nama_training;
									$deskripsi = $b->deskripsi;
									// echo  "mt level ".$c."<br>";
									//GET RECID LEGAL DARI TABEL TRAINING UNTUK CEK DATA KARIR KARYAWAN
									$cek_legal = $this->m_training->training_legal($c);
									if ($cek_legal->num_rows() > 0) {
										$test_row = $cek_legal->num_rows();
										// echo "ROWS : $test_row<br>";
										$recid_legal = $this->m_training->training_legal($c)->result();
										foreach ($recid_legal  as $key) {
											$legal_id = $key->recid_legal;
											$cek_karyawan = $this->m_training->cek_training($legal_id);
											foreach ($cek_karyawan as $kry_train) {
												$kary_train = $kry_train->recid_karyawan;
												$nama_karyawan = $this->m_hris->karyawan_by_recid($recid_karyawan[$k]);
												foreach ($nama_karyawan as $nama) {
													$nama_karyawan = $nama->nama_karyawan;
												}
												if ($kary_train == $recid_karyawan[$k]) {
													$hitungsudah = $hitungsudah + 1;
													echo "SUDAH - $nama_karyawan - $legal_id <br>";
												} else {
													$hitung = $hitung + 1;
													echo "BELUM - $nama_karyawan - $legal_id <br>";
												}
											}
										}
										// echo "jml hitung = $hitung";
										if ($hitung >= 1) {
											$pesan = "$pesan $nama_karyawan Belum Mengikuti Training $nama_train level $x ($deskripsi)";
										}
									} else {
										// echo "Belum Ada Karyawan yang Training $nama_train level $x ($deskripsi)";
										$hitung2 = $hitung2 + 1;
										$pesan = "$pesan Belum Ada Karyawan yang Training $nama_train level $x ($deskripsi) <br>";
										// $this->session->set_flashdata('message',$pesan);
									}
								}
							}
						}
					}
					echo "legal = $hitung2 # belum level previous = $hitung # sudah level previous = $hitungsudah";
					$pesan = "$pesan </div>";
					if ($hitung2 == 0 and $hitungsudah >= 1) {
						$pesan = "<br>$pesan - INSERT DATA<br>";
						//INSERT DATA TRAINING - LEGAL - KARIR
						$data2 = array(
							'crt_by'				=> $crt_by,
							'crt_date'				=> date('y-m-d h:i:s'),
							'no_form'				=> $no_form,
							'tgl_pengajuan'			=> $tgl_pengajuan,
							// 'atasan'				=> $atasan,
							'jenis_training'		=> $jenis_training,
							'kategori'				=> $kategori,
							'recid_mtlevel'			=> $recid_mtlevel,
							'judul_training'		=> $judul_training,
							'lembaga'				=> $lembaga,
							'trainer'				=> $trainer,
							'tgl_m_training'		=> $tgl_m_training,
							'tgl_a_training'		=> $tgl_a_training,
							'jml_jam'				=> $jml_jam,
							'tempat_training'		=> $tempat_training,
							'berbayar'				=> $berbayar,
							'biaya'					=> $biaya,
							'scan_brosur'			=> $saved_brosur_name,
							'alasan_pengajuan'		=> $alasan,
						);
						$this->m_training->training_pinsert($data2);

						$last_tr = $this->m_training->training_last();
						foreach ($last_tr as $lt) {
							$recid_training = $lt->recid_training;
						}

						for ($i = 0; $i < count($recid_karyawan); $i++) {
							$data3 = array(
								'crt_by'				=> $this->session->userdata('kar_id'),
								'crt_date'				=> date('y-m-d h:i:s'),
								'recid_training'		=> $recid_training,
								'recid_karyawan'		=> $recid_karyawan[$i],
							);
							$this->m_training->traintmp_insert($data3);
						}

						//END INSERT DATA TRAINING - LEGAL - KARIR
					} else {
						$pesan = "$pesan";
						$this->session->set_flashdata('message', $pesan);
					}
					echo $pesan;
					// $this->notif_training($recid_training);
					redirect('index.php/Training');
				}
				// +++++ BUKAN LEVEL +++++
				else {
					//INSERT DATA TRAINING - LEGAL - KARIR
					$data2 = array(
						'crt_by'				=> $crt_by,
						'crt_date'				=> date('y-m-d h:i:s'),
						'no_form'				=> $no_form,
						'tgl_pengajuan'			=> $tgl_pengajuan,
						// 'atasan'				=> $atasan,
						'jenis_training'		=> $jenis_training,
						'kategori'				=> $kategori,
						'recid_mtlevel'			=> $recid_mtlevel,
						'judul_training'		=> $judul_training,
						'lembaga'				=> $lembaga,
						'trainer'				=> $trainer,
						'tgl_m_training'		=> $tgl_m_training,
						'tgl_a_training'		=> $tgl_a_training,
						'jml_jam'				=> $jml_jam,
						'tempat_training'		=> $tempat_training,
						'berbayar'				=> $berbayar,
						'biaya'					=> $biaya,
						'scan_brosur'			=> $saved_brosur_name,
						'alasan_pengajuan'		=> $alasan,
					);
					$this->m_training->training_pinsert($data2);
					$last_tr = $this->m_training->training_last();
					foreach ($last_tr as $lt) {
						$recid_training = $lt->recid_training;
					}
					for ($i = 0; $i < count($recid_karyawan); $i++) {
						$data3 = array(
							'crt_by'				=> $crt_by,
							'crt_date'				=> date('y-m-d h:i:s'),
							'recid_training'		=> $recid_training,
							'recid_karyawan'		=> $recid_karyawan[$i],
						);
						$this->m_training->traintmp_insert($data3);
					}
					// $this->notif_training($recid_training);
					redirect('index.php/Training');
					//END INSERT DATA TRAINING - LEGAL - KARIR
				}
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$no_perjanjian = $this->input->post('no_perjanjian');
			$kategori = $this->input->post('kategorit');
			$judul_training = $this->input->post('judul_training');
			$recid_mtlevel = $this->input->post('recid_mtlevel');
			$tempat_training = $this->input->post('tempat_training');
			$trainer = $this->input->post('trainer');
			$sertifikat = $this->input->post('sertifikat');
			$tgl_m_training = $this->input->post('tgl_m_training');
			$tgl_a_training = $this->input->post('tgl_a_training');
			$scan_perjanjian = $this->input->post('scan_perjanjian');
			$note = $this->input->post('note');
			$recid_karyawan = $this->input->post('nik');

			// File Upload
			$config3 = array();
			$config3['upload_path'] 		= './images/legal/';
			$config3['allowed_types'] 		= 'pdf';
			$config3['max_size'] 			= '2500000';
			$config3['file_name'] 	     	= $judul_training;
			$config3['encrypt_name'] = TRUE;
			$this->load->library('upload', $config3, 'scan');  // Create custom object for catalog upload
			$this->scan->initialize($config3);
			$upload_file = $this->scan->do_upload('scan_perjanjian');
			$saved_file_name = $this->scan->data('file_name');

			if ($upload_file) {
				if ($kategori == 'Khusus') {
					$pesan = "<div class='alert alert-danger' style='max_height:auto;'>";
					$hitung = 0; // yang belum ikut training
					$hitungsudah = 0; // yang sudah ikut training
					$hitung2 = 0; // yang trainingnya blm pernah ada yang ikutin
					// echo "<br>*******<br>".$recid_karyawan." - ";

					// CEK CURRENT LEVEL
					$current = $this->current_level($recid_mtlevel);
					foreach ($current as $current) {
						$recid_mt = $current[0];
						$level = $current[1];
					}
					// echo $recid_mt." - ".$level." - ";
					$bawah = $level - 1;
					if ($bawah == 0) {
						$hitungsudah = $hitungsudah + 1;
					} else {
						//COUNT JML LEVEL PREVIOUS PER MASTER DAN LEVEL CURRENT
						$jm_level = $this->m_training->jml_level($recid_mt, $level);
						foreach ($jm_level as $jml_level) {
							$jml_level = $jml_level->jml_level;
						}
						// echo $jml_level." # <br>";
						$nama_karya = $this->m_hris->karyawan_by_recid($recid_karyawan);
						foreach ($nama_karya as $karyawan) {
							$nama_karyawan = $karyawan->nama_karyawan;
						}
						for ($x = $jml_level; $x > 0; $x--) {
							// echo "<br>looping master $recid_mt, level ke $x punya $recid_karyawan <br> ";
							$a = $this->m_training->get_levelprev($recid_mt, $x);
							foreach ($a as $b) {
								$c = $b->recid_mtlevel;
								$nama_train = $b->nama_training;
								$deskripsi = $b->deskripsi;
								// echo  "mt level ".$c."<br>";
								//GET RECID LEGAL DARI TABEL TRAINING UNTUK CEK DATA KARIR KARYAWAN
								$cek_legal = $this->m_hris->training_legal($c);
								if ($cek_legal->num_rows() > 0) {
									$test_row = $cek_legal->num_rows();
									// echo "ROWS : $test_row<br>";
									$recid_legal = $this->m_training->training_legal($c)->result();
									foreach ($recid_legal  as $key) {
										$legal_id = $key->recid_legal;
										$cek_karyawan = $this->m_training->cek_training($legal_id);
										foreach ($cek_karyawan as $kry_train) {
											$kary_train = $kry_train->recid_karyawan;
											$nama_karyawan = $this->m_hris->karyawan_by_recid($recid_karyawan);
											foreach ($nama_karyawan as $nama) {
												$nama_karyawan = $nama->nama_karyawan;
											}
											if ($kary_train == $recid_karyawan) {
												$hitungsudah = $hitungsudah + 1;
												echo "SUDAH - $nama_karyawan - $legal_id <br>";
											} else {
												$hitung = $hitung + 1;
												echo "BELUM - $nama_karyawan - $legal_id <br>";
											}
										}
									}
									// echo "jml hitung = $hitung";
									if ($hitung >= 1) {
										$pesan = "$pesan $nama_karyawan Belum Mengikuti Training $nama_train level $x ($deskripsi)";
									}
								} else {
									// echo "Belum Ada Karyawan yang Training $nama_train level $x ($deskripsi)";
									$hitung2 = $hitung2 + 1;
									$pesan = "$pesan Belum Ada Karyawan yang Training $nama_train level $x ($deskripsi) <br>";
									// $this->session->set_flashdata('message',$pesan);
								}
							}
						}
					}
					echo "legal = $hitung2 # belum level previous = $hitung # sudah level previous = $hitungsudah";
					$pesan = "$pesan </div>";
					if ($hitung2 == 0 and $hitungsudah >= 1) {
						$pesan = "<br>$pesan - INSERT DATA<br>";
						//INSERT DATA TRAINING - LEGAL - KARIR
						$data = array(
							'crt_by'				=> $this->session->userdata('kar_id'),
							'crt_date'				=> date('y-m-d h:i:s'),
							'jenis_perjanjian' 		=> 'Karyawan',
							'no_perjanjian'			=> $no_perjanjian,
							'judul_perjanjian'		=> $judul_training,
							'tgl_m_legal'			=> $tgl_m_training,
							'tgl_a_legal'			=> $tgl_a_training,
							'scan_perjanjian'		=> $saved_file_name,
							'note'					=> $note
						);
						$this->m_hris->legal_pinsert($data);

						$recid_legal = $this->m_hris->legal_last();
						foreach ($recid_legal as $key) {
							$recid_legal = $key->recid_legal;
						}

						$data2 = array(
							'crt_by'				=> $this->session->userdata('kar_id'),
							'crt_date'				=> date('y-m-d h:i:s'),
							'recid_legal'			=> $recid_legal,
							'kategori'				=> $kategori,
							'judul_training'		=> $judul_training,
							'recid_mtlevel'			=> $recid_mtlevel,
							'tempat_training'		=> $tempat_training,
							'tgl_m_training'		=> $tgl_m_training,
							'tgl_a_training'		=> $tgl_a_training,
							'trainer'				=> $trainer,
							'sertifikat'			=> $sertifikat,
							'note'					=> $note,
						);
						$this->m_training->training_pinsert($data2);
						$data3 = array(
							'crt_by'				=> $this->session->userdata('kar_id'),
							'crt_date'				=> date('y-m-d h:i:s'),
							'kategori'				=> 'Training',
							'recid_legal'			=> $recid_legal,
							'tgl_m_karir'			=> $tgl_m_training,
							'tgl_a_karir'			=> $tgl_a_training,
							'recid_karyawan'		=> $recid_karyawan,
							'note'					=> $note,
						);
						$this->m_hris->karir_pinsert($data3);
						//END INSERT DATA TRAINING - LEGAL - KARIR
					} else {
						$pesan = "$pesan";
						$this->session->set_flashdata('message', $pesan);
					}
					echo $pesan;
					redirect('index.php/Training/training_insert');
				} else {
					//INSERT DATA TRAINING - LEGAL - KARIR
					$data = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'jenis_perjanjian' 		=> 'Karyawan',
						'no_perjanjian'			=> $no_perjanjian,
						'judul_perjanjian'		=> $judul_training,
						'tgl_m_legal'			=> $tgl_m_training,
						'tgl_a_legal'			=> $tgl_a_training,
						'scan_perjanjian'		=> $saved_file_name,
						'note'					=> $note
					);
					$this->m_hris->legal_pinsert($data);

					$recid_legal = $this->m_hris->legal_last();
					foreach ($recid_legal as $key) {
						$recid_legal = $key->recid_legal;
					}

					$data2 = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'recid_legal'			=> $recid_legal,
						'kategori'				=> $kategori,
						'judul_training'		=> $judul_training,
						//'recid_mtlevel'			=> $recid_mtlevel,
						'tempat_training'		=> $tempat_training,
						'tgl_m_training'		=> $tgl_m_training,
						'tgl_a_training'		=> $tgl_a_training,
						'trainer'				=> $trainer,
						'sertifikat'			=> $sertifikat,
						'note'					=> $note,
					);
					$this->m_training->training_pinsert($data2);
					$data3 = array(
						'crt_by'				=> $this->session->userdata('kar_id'),
						'crt_date'				=> date('y-m-d h:i:s'),
						'kategori'				=> 'Training',
						'recid_legal'			=> $recid_legal,
						'tgl_m_karir'			=> $tgl_m_training,
						'tgl_a_karir'			=> $tgl_a_training,
						'recid_karyawan'		=> $recid_karyawan,
						'note'					=> $note,
					);
					$this->m_hris->karir_pinsert($data3);
					redirect('index.php/Training/training_insert');
					//END INSERT DATA TRAINING - LEGAL - KARIR
				}
			} else // punya if upload
			{
				echo 'File upload Error : ' . $this->scan->display_errors() . '<br/>';
			}
		} else {
			redirect('Auth/keluar');
		}
	}

	function current_level($recid_mtlevel)
	{
		$current_level  = $this->m_training->get_traininglevel($recid_mtlevel);
		$data = array();
		foreach ($current_level as $level) {
			$a = array($level->recid_mt, $level->level);
			array_push($data, $a);
		}
		return $data;
	}

	public function training_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->uri->segment(3);
			$data['training'] = $this->m_training->training_karyawan_detail($recid_training)->result();
			$data['karyawan'] = $this->m_training->training_karyawan_detail($recid_training)->result();
			$data['peserta'] = $this->m_training->train_pst($recid_training)->result();
			$data['karyawan2'] = $this->m_hris->karyawan_view();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function train_acc_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->uri->segment(3);
			$data['training'] = $this->m_training->training_karyawan_detail($recid_training)->result();
			$data['karyawan'] = $this->m_training->training_karyawan_detail($recid_training)->result();
			$data['karyawan2'] = $this->m_hris->karyawan_view();
			$data['kompetensi'] = $this->m_training->kompetensi_aktif();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/train_acc_update', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->input->post('recid_training');
			$jenis_training = $this->input->post('jenis_training');
			$judul_training = $this->input->post('judul_training');
			$lembaga = $this->input->post('lembaga');
			$trainer 		= $this->input->post('trainer');
			$tgl_m_training = $this->input->post('tgl_m_training');
			$tgl_a_training = $this->input->post('tgl_a_training');
			$jml_jam = $this->input->post('jml_jam');
			$tempat_training = $this->input->post('tempat_training');
			$scan_brosur = $this->input->post('scan_brosur');
			$scan_brosur2 = $this->input->post('scan_brosur2');
			$alasan_pengajuan = $this->input->post('alasan_pengajuan');
			$berbayar = $this->input->post('berbayar');
			$biaya = $this->input->post('biaya');
			$alasan_pengajuan = $this->input->post('alasan_pengajuan');
			$recid_karyawan = $this->input->post('nik');

			$err_brosur = 0;
			$nama_brosur = $_FILES['scan_brosur']['name'];

			if ($_FILES['scan_brosur']['name'] != '') {
				// Brosur Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/training/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg|pdf';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_brosur;
				$config3['overwrite']        = FALSE;
				$config3['encrypt_name']     = TRUE;
				$this->load->library('upload', $config3, 'sc_brosur');  // Create custom object for catalog upload
				$this->sc_brosur->initialize($config3);
				$path_foto = './images/training/';
				$upload_foto = $this->sc_brosur->do_upload('scan_brosur');
				$saved_brosur_name = $this->sc_brosur->data('file_name');
				if (!$upload_foto) {
					$err_brosur = 1;
				} else {
					$err_brosur = 0;
				}
				echo $saved_brosur_name;
			} else {
				$saved_brosur_name = $scan_brosur2;
			}

			$data = array(
				'jenis_training'   	=> $jenis_training,
				'judul_training'   	=> $judul_training,
				'lembaga'   		=> $lembaga,
				'trainer'   		=> $trainer,
				'tgl_m_training'   	=> $tgl_m_training,
				'tgl_a_training'   	=> $tgl_a_training,
				'jml_jam'   		=> $jml_jam,
				'tempat_training'   => $tempat_training,
				'berbayar'  			=> $berbayar,
				'biaya'  			=> $biaya,
				'scan_brosur'  		=> $saved_brosur_name,
				'alasan_pengajuan'  => $alasan_pengajuan
			);
			$this->m_training->training_pupdate($recid_training, $data);
			redirect('index.php/Training');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function train_acc_pupdate()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->input->post('recid_training');
			$jenis_training = $this->input->post('jenis_training');
			$judul_training = $this->input->post('judul_training');
			$lembaga = $this->input->post('lembaga');
			$trainer 		= $this->input->post('trainer');
			$tgl_m_training = $this->input->post('tgl_m_training');
			$tgl_a_training = $this->input->post('tgl_a_training');
			$jml_jam = $this->input->post('jml_jam');
			$tempat_training = $this->input->post('tempat_training');
			$scan_brosur = $this->input->post('scan_brosur');
			$scan_brosur2 = $this->input->post('scan_brosur2');
			$alasan_pengajuan = $this->input->post('alasan_pengajuan');
			$biaya = $this->input->post('biaya');
			$berbayar = $this->input->post('berbayar');
			$alasan_pengajuan = $this->input->post('alasan_pengajuan');
			$recid_karyawan = $this->input->post('nik');
			$recid_komp = $this->input->post('kompetensi');

			$recid_legal = $this->input->post('recid_legal');
			/*$no_perjanjian = $this->input->post('no_perjanjian');
			$scan_perjanjian = $this->input->post('scan_perjanjian');
			$scan_perjanjian2 = $this->input->post('scan_perjanjian2');*/
			$kompetensi = $this->input->post('kompetensi');
			$sertifikat = $this->input->post('sertifikat');
			$metoda = $this->input->post('metoda');
			$tna = $this->input->post('tna');
			$evaluasi = $this->input->post('evaluasi');
			// $atasan = $this->input->post('atasan');
			$poin = $this->input->post('poin');
			$acc_hc = $this->input->post('acc_hc');
			$status_acc = $this->input->post('status_acc');
			$acc_direksi = $this->input->post('acc_direksi');
			$scan_direksi2 = $this->input->post('scan_direksi2');
			$note = $this->input->post('note');
			$status = $this->input->post('status');

			$err_brosur = 0;
			$nama_brosur = $_FILES['scan_brosur']['name'];

			if ($_FILES['scan_brosur']['name'] != '') {
				// Brosur Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/training/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg|pdf';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_brosur;
				$config3['overwrite']        = FALSE;
				$config3['encrypt_name']     = TRUE;
				$this->load->library('upload', $config3, 'sc_brosur');  // Create custom object for catalog upload
				$this->sc_brosur->initialize($config3);
				$path_foto = './images/training/';
				$upload_foto = $this->sc_brosur->do_upload('scan_brosur');
				$saved_brosur_name = $this->sc_brosur->data('file_name');
				if (!$upload_foto) {
					$err_brosur = 1;
				} else {
					$err_brosur = 0;
				}
				echo $saved_brosur_name;
			} else {
				$saved_brosur_name = $scan_brosur2;
			}

			// $err_sk = 0;
			// $nama_sk = "Tr-$no_perjanjian";
			$err_dir = 0;
			$nama_dir = $_FILES['scan_brosur']['name'];

			/*	if($_FILES['scan_perjanjian']['name'] != ''){
	// Brosur Upload
					$config31 = array();
					$config31['upload_path'] 		= './images/legal/';
					$config31['allowed_types'] 		= 'jpg|png|jpeg|pdf';
					$config31['max_size'] 			= '2000000';
					$config31['file_name'] 	     	= $nama_sk;
					$config31['overwrite']        = FALSE;
					$config31['encrypt_name']     = TRUE;
				    $this->load->library('upload', $config31, 'sc_sk');  // Create custom object for catalog upload
				    $this->sc_sk->initialize($config31);
				    $path_foto = './images/legal/';
				    $upload_foto = $this->sc_sk->do_upload('scan_perjanjian');
				    $saved_sk_name = $this->sc_sk->data('file_name');
				    if (!$upload_foto) {
				    	$err_sk = 1;
				    }else{
				    	$err_sk = 0;
				    }
				    // echo $saved_sk_name;
				}else{
					$saved_sk_name = $scan_perjanjian2;
				}
*/
			if ($_FILES['scan_direksi']['name'] != '') {
				// Scan Direksi
				$config4 = array();
				$config4['upload_path'] 		= './images/training/';
				$config4['allowed_types'] 		= 'jpg|png|jpeg|pdf';
				$config4['max_size'] 			= '2000000';
				$config4['file_name'] 	     	= $nama_dir;
				$config4['overwrite']        = FALSE;
				$config4['encrypt_name']     = TRUE;
				$this->load->library('upload', $config4, 'sc_dir');  // Create custom object for catalog upload
				$this->sc_dir->initialize($config4);
				$path_foto = './images/training/';
				$upload_foto = $this->sc_dir->do_upload('scan_direksi');
				$saved_dir_name = $this->sc_dir->data('file_name');
				if (!$upload_foto) {
					$err_dir = 1;
				} else {
					$err_dir = 0;
				}
				// echo $saved_dir_name;
			} else {
				$saved_dir_name = $scan_direksi2;
			}

			$data = array(
				'jenis_training'   	=> $jenis_training,
				'judul_training'   	=> $judul_training,
				'lembaga'   		=> $lembaga,
				'trainer'   		=> $trainer,
				'tgl_m_training'   	=> $tgl_m_training,
				'tgl_a_training'   	=> $tgl_a_training,
				'jml_jam'   		=> $jml_jam,
				'tempat_training'   => $tempat_training,
				'berbayar'  		=> $berbayar,
				'biaya'  			=> $biaya,
				'scan_brosur'  		=> $saved_brosur_name,
				'alasan_pengajuan'  => $alasan_pengajuan,
				'recid_legal'			=> $recid_legal,
				'recid_komp'			=> $recid_komp,
				'metoda'			=> $metoda,
				'sertifikat'			=> $sertifikat,
				'tna'					=> $tna,
				'evaluasi'				=> $evaluasi,
				'acc_hc'				=> $acc_hc,
				'acc_direksi'			=> $acc_direksi,
				'status_acc'			=> $status_acc,
				'scan_direksi'			=> $saved_dir_name,
				'poin'					=> $poin,
				'status'				=> $status,
				'note'					=> $note,
				'mdf_date'				=> date('y-m-d h:i:s'),
				'mdf_by'				=> $this->session->userdata('kar_id')
			);
			$this->m_training->training_pupdate($recid_training, $data);

			$data2 = array(
				/*'no_perjanjian'   		=> $no_perjanjian, 
				'judul_perjanjian'   	=> $judul_training, */
				'tgl_m_legal'   		=> $tgl_m_training,
				'tgl_a_legal'   		=> $tgl_a_training,
				// 'scan_perjanjian' 		=> $saved_sk_name,
				'note'  		 		=> $note
			);
			$this->m_hris->legal_update($data2, $recid_legal);

			$data3 = array(
				'tgl_m_karir'   		=> $tgl_m_training,
				'tgl_a_karir'   		=> $tgl_a_training,
				'note'  		 		=> $note
			);
			$this->m_training->karir_pupdatetraining($recid_legal, $data3);
			redirect('index.php/Training');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_dkaryawan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$recid_training = $this->uri->segment(4);
			$this->m_training->training_dkaryawan($recid);
			$links = "index.php/Training/training_update/$recid_training";
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_dkkaryawan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid = $this->uri->segment(3);
			$recid_training = $this->uri->segment(4);
			$this->m_training->training_dkkaryawan($recid);
			$links = "index.php/Training/train_acc_update/$recid_training";
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_addkar()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->input->post('nik2');
			$recid_training = $this->input->post('recid_training');
			$tgl_m_training = $this->input->post('tgl_m_karir');
			$tgl_a_training = $this->input->post('tgl_a_karir');
			$note = $this->input->post('note');
			for ($i = 0; $i < count($this->input->post('nik2')); $i++) // looping sebanyak multi select
			{
				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_training'		=> $recid_training,
					'recid_karyawan'		=> $recid_karyawan[$i],
				);
				$this->m_training->traintmp_insert($data3);
				$links = "index.php/Training/training_update/$recid_training";
				// echo $links;
			}
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_addkar_update()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->input->post('nik2');
			$recid_training = $this->input->post('recid_training');
			$tgl_m_training = $this->input->post('tgl_m_training');
			$tgl_a_training = $this->input->post('tgl_a_training');
			$note = $this->input->post('note');

			$training = $this->m_training->train_ajuan($recid_training);
			foreach ($training as $t) {
				$recid_legal = $t->recid_legal;
			}

			for ($i = 0; $i < count($this->input->post('nik2')); $i++) // looping sebanyak multi select
			{

				$data3 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'kategori'				=> 'Training',
					'recid_legal'			=> $recid_legal,
					'tgl_m_karir'			=> $tgl_m_training,
					'tgl_a_karir'			=> $tgl_a_training,
					'recid_karyawan'		=> $recid_karyawan[$i],
					'note'					=> $note,
				);
				$this->m_hris->karir_pinsert($data3);

				$data4 = array(
					'crt_by'				=> $this->session->userdata('kar_id'),
					'crt_date'				=> date('y-m-d h:i:s'),
					'recid_training'		=> $recid_training,
					'recid_karyawan'		=> $recid_karyawan[$i],
				);
				$this->m_training->participant_insert($data4);
			}
			$links = "index.php/Training/train_acc_update/$recid_training";
			redirect($links);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_detail_ajuan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->uri->segment(3);
			$data['pengaju'] = $this->m_training->train_ajuan($recid_training);
			$data['training'] = $this->m_training->train_ajuan($recid_training);
			$data['hc'] = $this->m_training->train_detail($recid_training);
			$data['karyawan'] = $this->m_training->train_pst($recid_training)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_detail()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->uri->segment(3);
			$data['pengaju'] = $this->m_training->train_ajuan($recid_training);
			$data['training'] = $this->m_training->train_ajuan($recid_training);
			$data['hc'] = $this->m_training->train_detail($recid_training);
			$ajuan = $this->m_training->train_ajuan($recid_training);
			foreach ($ajuan as $key) {
			}
			if ($key->status_acc == "Approved") {
				$data['karyawan'] = $this->m_training->training_karyawan_detail($recid_training)->result();
			} else {
				$data['karyawan'] = $this->m_training->train_pst($recid_training)->result();
			}
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_detail', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function master_training()
	{

		$master = $this->m_training->training_master();
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>-- Pilih --</option>";
		foreach ($master as $data) {
			$lists .= "<option value='" . $data->recid_mt . "'>" . $data->nama_training . "</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function get_level()
	{

		$master = $this->input->post('recid_mt');
		$level = $this->m_training->level_master($master);
		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>-- Pilih Level--</option>";
		foreach ($level as $data) {
			$lists .= "<option value='" . $data->recid_mtlevel . "'>" . $data->deskripsi . " (" . $data->level . ")</option>"; // Tambahkan tag option ke variabel $lists
		}
		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function tmaster_insert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$nama_training = $this->input->post('nama_training');
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'nama_training'			=> $nama_training,
			);
			$result = $this->m_training->tmaster_insert($data);
			echo json_encode($result);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function cek_master()
	{
		$recid_mt = $this->input->post('recid_mt');
		$master = $this->m_training->cek_level($recid_mt);
		// Buat variabel untuk menampung tag-tag option nya
		foreach ($master as $data) {
			$counting = $data->counting;
		}
		echo json_encode($counting);
	}

	public function tmasterlevel_pinsert()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_mt = $this->input->post('recid_mt');
			$level = $this->input->post('level');
			$deskripsi = $this->input->post('deskripsi');
			$data = array(
				'crt_by'				=> $this->session->userdata('kar_id'),
				'crt_date'				=> date('y-m-d h:i:s'),
				'recid_mt'				=> $recid_mt,
				'level'					=> $level,
				'deskripsi'				=> $deskripsi,
			);
			$result = $this->m_training->tmasterlevel_insert($data);
			echo json_encode($result);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function download_training()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->uri->segment(3);
			$data['pengaju'] = $this->m_training->train_ajuan($recid_training);
			$data['training'] = $this->m_training->train_ajuan($recid_training);
			$data['karyawan'] = $this->m_training->train_pst($recid_training)->result();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			// $this->load->view('layout/a_header');
			// $this->load->view('layout/menu_super', $data);
			$this->load->view('training/download', $data);
			// $this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_dinamis()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['training'] = $this->m_training->training_dinamis();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_dinamis', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function detail_participant()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_karyawan = $this->uri->segment(3);
			$recid_training = $this->uri->segment(4);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['training'] = $this->m_training->train_detail($recid_training);
			$data['peserta'] = $this->m_hris->karyawan_view_by_id($recid_karyawan);
			$data['evaluasi'] = $this->m_training->train_evaluasi($recid_training, $recid_karyawan);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/training_participant', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function evaluasi_peserta()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$recid_training = $this->input->post('recid_training');
			$recid_karyawan = $this->input->post('recid_karyawan');
			$sertifikat = $this->input->post('sertifikat');
			$old_sertifikat = $this->input->post('old_sertifikat');
			$poin_plus = $this->input->post('poin_plus');
			$pre_test = $this->input->post('pre_test');
			$post_test = $this->input->post('post_test');
			$superior_rating = $this->input->post('superior_rating');
			$closed_evaluasi = $this->input->post('closed_evaluasi');
			$nama_file_foto = "T-$recid_karyawan-$recid_training";
			if ($pre_test == '') {
				$pre_test = '0';
			} else {
				$pre_test = '1';
			}
			if ($post_test == '') {
				$post_test = '0';
			} else {
				$post_test = '1';
			}
			if ($superior_rating == '') {
				$superior_rating = '0';
			} else {
				$superior_rating = '1';
			}

			if ($_FILES['sertifikat']['name'] != '') {
				// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/training/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg|pdf';
				$config3['max_size'] 			= '2000000';
				$config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name'] = TRUE;
				$this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				$this->sc_foto->initialize($config3);
				$path_foto = './images/training/';
				$upload_foto = $this->sc_foto->do_upload('sertifikat');
				$saved_sertifikat = $this->sc_foto->data('file_name');
				if (!$upload_foto) {
					$err_dir = 1;
				} else {
					$err_dir = 0;
				}
			} else {
				$saved_sertifikat = '';
			}

			if ($err_dir == 1) {
				echo 'Sertifikat upload Error : ' . $this->sc_foto->display_errors() . '<br/>';
			} else {
				$data = array(
					'mdf_by'				=> $this->session->userdata('kar_id'),
					'mdf_date'				=> date('y-m-d h:i:s'),
					'sertifikat'			=> $saved_sertifikat,
					'poin_plus'				=> $poin_plus,
					'pre_test'				=> $pre_test,
					'post_test'				=> $post_test,
					'superior_rating'		=> $superior_rating,
					'closed_evaluasi'		=> $closed_evaluasi,
				);
				$result = $this->m_training->evaluasi_insert($recid_training, $recid_karyawan, $data);
				$links = "index.php/Training/train_acc_update/$recid_training";
				redirect($links);
			}

			// echo $saved_sertifikat."status : ".$err_dir;
			/*
		if($_FILES['sertifikat']['name'] != ''){
	// Scan Direksi
			$config4 = array();
			$config4['upload_path'] 		= './images/training/';
			$config4['allowed_types'] 		= 'jpg|png|jpeg|pdf';
			$config4['max_size'] 			= '2000000';
			$config4['file_name'] 	     	= $_FILES['sertifikat']['name'];
			$config4['overwrite']        = FALSE;
			$config4['encrypt_name']     = TRUE;
				    $this->load->library('upload', $config4, 'sc_dir');  // Create custom object for catalog upload
				    $this->sc_dir->initialize($config4);
				    $path_foto = './images/training/';
				    $upload_foto = $this->sc_dir->do_upload('sertifikat');
				    $saved_sertifikat = $this->sc_dir->data('file_name');
				    if (!$upload_foto) {
				    	$err_dir = 1;
				    }else{
				    	$err_dir = 0;
				    }
				    // echo $saved_dir_name;
				}else{
					$saved_sertifikat = $old_sertifikat;
				}*/
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekapitulasi_training()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$data['training'] = $this->m_training->rekapitulasi_training();
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/rekapitulasi_training', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_hour()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('train_tahun');
			$data = array();
			$training = $this->m_training->training_hour($thn)->result();
			foreach ($training as $t) {
				array_push($data, $t->jan);
				array_push($data, $t->feb);
				array_push($data, $t->mar);
				array_push($data, $t->apr);
				array_push($data, $t->mei);
				array_push($data, $t->jun);
				array_push($data, $t->jul);
				array_push($data, $t->agu);
				array_push($data, $t->sep);
				array_push($data, $t->okt);
				array_push($data, $t->nov);
				array_push($data, $t->des);
			}
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function training_comp()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == '1') {
			$thn = $this->input->post('train_tahun');
			// $thn = '2021';
			$data_kompetensi = array();
			$data_jml = array();
			$training = $this->m_training->train_comp($thn);
			foreach ($training as $t) {
				// $val = "{value : ".$t->jml.", name:'".$t->kompetensi."'}";
				array_push($data_kompetensi, $t->kompetensi);
				array_push($data_jml, $t->jml);
			}
			$data = array();
			array_push($data, $data_kompetensi);
			array_push($data, $data_jml);
			echo json_encode($data);
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_training()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$bulan = $this->uri->segment(3);
			$tahun = $this->uri->segment(4);
			$data['training'] = $this->m_training->rekap_training($bulan, $tahun);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/rekapitulasi_training', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function rekap_by_category()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in == 1) {
			$tahun = $this->uri->segment(3);
			$kompetensi = $this->uri->segment(4);
			$kompetensi = str_replace("%20", " ", $kompetensi);
			$kompetensi = str_replace("-", "&", $kompetensi);
			$data['training'] = $this->m_training->rekap_training_kompetensi($tahun, $kompetensi);
			$usr = $this->session->userdata('kar_id');
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super', $data);
			$this->load->view('training/rekapitulasi_training', $data);
			$this->load->view('layout/a_footer');
		} else {
			redirect('Auth/keluar');
		}
	}

	public function notif_training($training)
	{
		//Load email library
		$this->load->library('email');

		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.chitose.id',
			'smtp_port' => 25,
			'smtp_user' => 'training@chitose.id',
			'smtp_pass' => '$trnHRD',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$recipients = array();

		$train = $this->m_training->train_ajuan($training);
		foreach ($train as $t) {
			$no_form = $t->no_form;
			$no_form = "CINT/HC-GA/" . $no_form . "/Formulir Usulan Pelatihan";
			$tgl_aju = $t->tgl_pengajuan;
			$tgl_aju = date("d M Y",  strtotime($tgl_aju));
			$topik = $t->judul_training;
			$nama_karyawan = $t->nama_karyawan;
			$biaya = "Rp. " . number_format($t->biaya);
			$tgl_m_training = $t->tgl_m_training;
			$tgl_m_training = date("d M Y",  strtotime($tgl_m_training));
			$tgl_a_training = $t->tgl_a_training;
			$tgl_a_training = date("d M Y",  strtotime($tgl_a_training));
		}

		array_push($recipients, "training@chitose.id");
		array_push($recipients, "diah@chitose.id");
		$recipients = array_unique($recipients);

		$title = "Notifikasi Training";

		// echo json_encode($recipients);
		//Email content
		$htmlContent = '<h3>Notifikasi Approval Training By HC</h3>';
		$htmlContent .= '<p>Berikut disampaikan pengajuan usulan training dengan detail sebagai berikut : </p>';
		$htmlContent .= '<table><tr><td>Nomor Form Pengajuan</td><td> : &nbsp;' . $no_form . '</td></tr><tr><td>Nama Pengaju</td><td>: &nbsp;' . $nama_karyawan . '</td></tr><tr><td>Tanggal Pengajuan</td><td>: &nbsp;' . $tgl_aju . '</td></tr><tr><td>Topik Training </td><td> : &nbsp; ' . $topik . '</td></tr><tr><td>Tanggal Training</td><td> : &nbsp;' . $tgl_m_training . ' - ' . $tgl_a_training . '</td></tr><tr><td>Biaya</td><td> : &nbsp;' . $biaya . '</td></tr></table>';
		$htmlContent .= '<p>Lakukan Validasi / Approval pada aplikasi HRIS</p>';
		$htmlContent .= '<p>Terima Kasih</p>';
		$htmlContent .= '<p><u>Recruitment & Training</u></p>';
		echo $htmlContent;

		// $recipients = array('anysah.murti@gmail.com', 'it.sysdev@chitose-indonesia.com', 'anysah.rinjani@gmail.com');
		$this->email->to(implode(', ', $recipients));
		$this->email->from('training@chitose.id', 'Training Chitose');
		$this->email->subject($title);
		$this->email->message($htmlContent);

		//Send email

		// if ($this->email->send()) {
		// 	echo "Email Has Been Sent!";
		// 	redirect('index.php/Training');
		// } else {
		// 	echo "Email Failed";
		// 	show_error($this->email->print_debugger());
		// }
		redirect('index.php/Training');
	}
}

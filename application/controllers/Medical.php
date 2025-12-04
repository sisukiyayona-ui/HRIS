<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medical extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('m_medical', 'm_hris'));
		// ini_set('max_execution_time', 600);
		ob_start(); # add this
	}

	public function plafon()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['plafon'] = $this->m_medical->plafon();
			$data['emp'] = $this->m_hris->karyawan_offup();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/plafon',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');

		}
	}

	public function add_plafon()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$plafon = $this->input->post('plafon');
			$plafon = str_replace(".","",$plafon);
			$data = array(
				'crt_by'			=> $this->session->userdata('kar_id'),
				'tahun'				=> $this->input->post('tahun'),
				'recid_karyawan'	=> $this->input->post('recid_karyawan'),
				'jumlah_plafon'		=> $plafon,
			);
			//cek double
			$double = $this->m_medical->cek_double($this->input->post('tahun'), $this->input->post('recid_karyawan'));
			echo $double->num_rows();
			if($double->num_rows() > 0)
			{
				$this->session->set_flashdata('warning','Data Sudah Ada!');
			}else{

				$this->m_medical->save_data('plafon', $data);
			}
			redirect('Medical/plafon');
		}else
		{
			redirect('Auth/keluar');

		}
	}

	public function edit_plafon()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$plafon_recid = $this->input->post('plafon_recid');
			$plafon = $this->input->post('plafon');
			$plafon = str_replace(".","",$plafon);
			$data = array(
				'tahun'				=> $this->input->post('tahun'),
				'recid_karyawan'	=> $this->input->post('recid_karyawan'),
				'jumlah_plafon'		=> $plafon,
				'mdf_by'			=> $this->session->userdata('kar_id'),
				'mdf_date'			=> date('y-m-d h:i:s'),
			);

			$double = $this->m_medical->cek_double($this->input->post('tahun'), $this->input->post('recid_karyawan'));
			foreach ($double->result() as $key) {
				$plafon_db = $key->plafon_recid;
			}
			if($plafon_db != $plafon_recid)
			{
				$this->session->set_flashdata('warning','Data dengan Tahun Tersebut Sudah Ada!');
			}else{

				$this->m_medical->edit_plafon($plafon_recid, $data);
			}
			redirect('Medical/plafon');
		}else
		{
			redirect('Auth/keluar');

		}
	}

	public function pengajuan()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['pengajuan'] = $this->m_medical->pengajuan();
			$data['emp'] = $this->m_hris->karyawan_offup();
			$data['emp2'] = $this->m_hris->karyawan_offup();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/pengajuan',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');

		}
	}

public function pengajuan_form()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['pengajuan'] = $this->m_medical->pengajuan();
			$data['emp'] = $this->m_hris->karyawan_offup();
			$data['emp2'] = $this->m_hris->karyawan_offup();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/pengajuan_form',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');

		}
	}

	public function add_pengajuan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$nominal = $this->input->post('nominal');
			$nominal = str_replace(".","",$nominal);
			$jenis = $this->input->post('jenis');
			$tipe = $this->input->post('tipe');
			$file_medical= $this->input->post('foto');
			// echo "file : ".$file_medical;
			// echo "nominal : ".$nominal;

			if($tipe == "Pengobatan")
			{
				$jenis = "";
			}else{
				$jenis = $jenis;
			}
			$err_foto = 0;

			if($_FILES['foto']['name'] != ''){
	// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/medical/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				// $config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name']		= TRUE;
				    $this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				    $this->sc_foto->initialize($config3);
				    $path_foto = './images/medical/';
				    $upload_foto = $this->sc_foto->do_upload('foto');
				    $saved_foto_name = $this->sc_foto->data('file_name');
				    if (!$upload_foto) {
				    	$err_foto = 1;
				    }else{
				    	$err_foto = 0;
				    }
				}else{
					$saved_foto_name = '';
				}

				if($err_foto == 1){
					echo 'Upload Scan Error : ' . $this->sc_foto->display_errors() . '<br/>';
				}else{
					$data = array(
						'crt_by'			=> $this->session->userdata('kar_id'),
						'tipe_medic'		=> $this->input->post('tipe'),
						'jenis'				=> $jenis,
						'recid_karyawan'	=> $this->input->post('recid_karyawan'),
						'nama_kuitansi'		=> $this->input->post('nama_kuitansi'),
						'tgl_kuitansi'		=> $this->input->post('tgl_kuitansi'),
						'diagnosa'			=> $this->input->post('diagnosa'),
						'nominal'			=> $nominal,
						'file_medical'		=> $saved_foto_name,
						'path_medical'		=> $path_foto,
					);
					$this->m_medical->save_data('medical', $data);
					redirect('Medical/pengajuan');
				}
			
		}else
		{
			redirect('Auth/keluar');

		}
	}

	public function pengajuan_edit()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['pengajuan'] = $this->m_medical->pengajuan();
			$data['emp'] = $this->m_hris->karyawan_offup();
			$data['emp2'] = $this->m_hris->karyawan_offup();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/edit_pengajuan',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');

		}
	}

	public function edit_pengajuan()
	{
		$logged_in = $this->session->userdata('logged_in');
		if($logged_in == 1)
		{
			$medical_recid = $this->input->post('medical_recid');
			$nominal = $this->input->post('nominal');
			$nominal = str_replace(".","",$nominal);
			$jenis = $this->input->post('jenis');
			$tipe = $this->input->post('tipe');
			$foto = $this->input->post('foto');		// data yg prnah masuk
			$foto2 = $this->input->post('foto2');	// data baru
			$file_medical2 = $foto2;	//baru
			$file_medical = $foto;		//database
			if($tipe == "Pengobatan")
			{
				$jenis = "";
			}else{
				$jenis = $jenis;
			}

			if($_FILES['foto2']['name'] != ''){
	// Foto Upload
				$config3 = array();
				$config3['upload_path'] 		= './images/medical/';
				$config3['allowed_types'] 		= 'jpg|png|jpeg';
				$config3['max_size'] 			= '2000000';
				// $config3['file_name'] 	     	= $nama_file_foto;
				$config3['encrypt_name']		= TRUE;
				    $this->load->library('upload', $config3, 'sc_foto');  // Create custom object for catalog upload
				    $this->sc_foto->initialize($config3);
				    $path_foto = './images/medical/';
				    $upload_foto = $this->sc_foto->do_upload('foto2');
				    $saved_foto_name = $this->sc_foto->data('file_name');
				    if (!$upload_foto) {
				    	$err_foto = 1;
				    }else{
				    	$err_foto = 0;
				    }
				    $file_medical = $saved_foto_name;
				}else{
					$file_medical = $foto;
				}

				if($err_foto == 1){
					echo 'Upload Scan Error : ' . $this->sc_foto->display_errors() . '<br/>';
				}else{
					$data = array(
						'tipe_medic'		=> $this->input->post('tipe'),
						'jenis'				=> $jenis,
						'recid_karyawan'	=> $this->input->post('recid_karyawan'),
						'nama_kuitansi'		=> $this->input->post('nama_kuitansi'),
						'tgl_kuitansi'		=> $this->input->post('tgl_kuitansi'),
						'diagnosa'			=> $this->input->post('diagnosa'),
						'nominal'			=> $nominal,
						'path_medical'		=> $path_foto,
						'file_medical'		=> $file_medical,
						'mdf_by'			=> $this->session->userdata('kar_id'),
						'mdf_date'			=> date('y-m-d h:i:s'),
					);
					$this->m_medical->edit_pengajuan($medical_recid, $data);
					redirect('Medical/pengajuan');
				}
		}else
		{
			redirect('Auth/keluar');

		}
	}

	public function cek_tanggungan()
	{
    // Ambil data ID Provinsi yang dikirim via ajax post
		$recid_karyawan = $this->input->post('recid_karyawan');
		
		 // Buat variabel untuk menampung tag-tag option nya
   		 // Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>-- Pilih --</option>";
		$nama_karyawan = $this->m_hris->karyawan_by_recid($recid_karyawan);
		foreach ($nama_karyawan as $key) {
		  	$lists .= "<option value='".$key->nama_karyawan."'>".$key->nama_karyawan."</option>"; // Tambahkan tag option ke variabel $lists
		}

		$tanggungan = $this->m_medical->cek_tanggungan($recid_karyawan)->result();
		foreach($tanggungan as $data){
     		 $lists .= "<option value='".$data->nama_tunjangan."'>".$data->nama_tunjangan."</option>"; // Tambahkan tag option ke variabel $lists
     		}

   		 $callback = array('list_karyawan'=>$lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
   		 // var_dump($callback);
    	echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function realisasi()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$recid_medical = $this->uri->segment(3);
			$recid_karyawan = 0;
			$emp = $this->m_medical->pengajuan($recid_medical);
			foreach ($emp->result() as $em) {
				$recid_karyawan = $em->recid_karyawan;
			}
			echo $recid_karyawan;
			$data['pengajuan'] = $this->m_medical->pengajuan();
			$data['emp'] = $this->m_hris->karyawan_offup();
			$data['emp2'] = $this->m_hris->karyawan_offup();
			$plafon = $this->m_medical->plafon_emp($recid_karyawan);
			$jumlah_plafon = 0;
			foreach ($plafon->result() as $plafon) {
				$jumlah_plafon = $plafon->jumlah_plafon;
			}
			$tot_real = 0;
			$realisasi = $this->m_medical->realisasi_emp($recid_karyawan);
			foreach ($realisasi->result() as $real) {
				$tot_real = $real->tot_realisasi;
			}
			$data['sisa']	= $jumlah_plafon - $tot_real;

			// echo "$jumlah_plafon - $tot_real";
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/realisasi',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');
		}
	}


	function hitung_realisasi()
	{
		$medical_recid = $this->input->post('medical_recid');
		$aju = $this->m_medical->pengajuan($medical_recid);
		foreach ($aju->result() as $ajuan) {
			$nama_karyawan = $ajuan->nama_karyawan;
			$recid_karyawan = $ajuan->recid_karyawan;
			$nama_kuitansi = $ajuan->nama_kuitansi;
			$nominal = $ajuan->nominal;
		}
		$nilai_ganti = 0;
		if($nama_karyawan == $nama_kuitansi)
		{
			$nilai_ganti = $nominal;
		}else 
		{
			$nilai_ganti = $nominal * (80/100);
		}
		echo json_encode($nilai_ganti);
	}

	public function proses_realisasi()
	{
		$medical_recid = $this->input->post('medical_recid');
		$nilai_ganti = $this->input->post('nilai_ganti');
		$nilai_ganti = str_replace(".","",$nilai_ganti);
		$tmbl = $this->input->post('flag_button');
		$status = $this->input->post('status');
		$note = $this->input->post('note');

		if($status == "Ditolak")
		{
			$nilai_ganti = 0;
		}
		$data = array(
			'nilai_ganti'		=> $nilai_ganti,
			'status'		    => $status,
			'note'		    	=> $note,
			'mdf_by'			=> $this->session->userdata('kar_id'),
			'mdf_date'			=> date('y-m-d h:i:s'),
		);
		$this->m_medical->edit_pengajuan($medical_recid, $data);
		redirect('Medical/pengajuan');

	}

	public function sisa_plafon()
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
				$jabatan = $user->indeks_jabatan;
				$tingkatan = $user->tingkatan;
				$struktur = $user->recid_struktur;
			}
			$data['cek_usr'] = $this->m_hris->cek_usr($usr);
			$data['realisasi'] = $this->m_medical->sisa_plafon();
			$this->load->view('layout/a_header');
			$this->load->view('layout/menu_super',$data);
			$this->load->view('reimburse/medical/realisasi_view',$data);
			$this->load->view('layout/a_footer');
		}
		else
		{
			redirect('Auth/keluar');

		}
	}

}
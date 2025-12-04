<?php
class M_auth extends CI_Model
{

	function __contsruct()
	{
		parent::Model();
	}

	function cek_login($where_login)
	{

		$data = "";
		$username = $this->input->post('uname');
		$password = $this->input->post('password');
		$pass = md5($password);
		//$test = do_hash('GnH48', 'md5');
		$query = "SELECT l.*, k.nama_karyawan, k.nik, k.foto, k.recid_karyawan, k.recid_karyawan, r.recid_role, r.nama_role  from login l
		join karyawan k on k.recid_karyawan = l.recid_karyawan
		join role r on r.recid_role = l.recid_role
		where username = '$username' and password = '$pass' and l.is_delete = '0'";
		$check = $this->db->query($query);
		// echo "$username - $password - $pass";

		if ($check->num_rows() > 0) {
			$data = $check->row();
			$this->set_session($data);
			return true;
		} else {
			$query = "SELECT * from tamu  
			where username = '$username' and password = '$pass'";
			$check = $this->db->query($query);
			if ($check->num_rows() > 0) {
				$data = $check->row();
				$this->set_session_kantin($data);
				return true;
			} else {
				return false;
			}
		}
	}

	function cek_pwd($where_login)
	{

		$data = "";
		$username = $this->input->post('uname');
		$password = $this->input->post('password');
		$pass = do_hash($password, 'md5');
		$query = "SELECT l.*, k.nama_karyawan, k.nik, k.foto, k.recid_karyawan, k.recid_karyawan, r.recid_role, r.nama_role  from login l
		join karyawan k on k.recid_karyawan = l.recid_karyawan
		join role r on r.recid_role = l.recid_role
		where username = '$username' and password = '$pass' and l.is_delete = '0'";
		$check = $this->db->query($query);
		// echo "$username - $password - $pass";

		if ($check->num_rows() > 0) {
			return $check;
		} else {
			$query = "SELECT * from tamu  
			where username = '$username' and password = '$pass'";
			$check = $this->db->query($query);
			return $check;
		}
	}

	function set_session(&$data)
	{
		$session = array(
			'recid_login' 		=> $data->recid_login,
			'recid_karyawan' 	=> $data->recid_karyawan,
			'nik' 				=> $data->nik,
			'nama' 				=> $data->nama_karyawan,
			'kar_id' 			=> $data->recid_karyawan,
			'foto'				=> $data->foto,
			'username' 			=> $data->username,
			'password' 			=> $data->password,
			'role_id'			=> $data->recid_role,
			'role_name'			=> $data->nama_role,
			'as_user'			=> "CINT",
			'logged_in'			=> TRUE
		);
		$this->session->set_userdata($session);
	}

	function set_session_kantin(&$data)
	{
		$session = array(
			'recid_login' 		=> $data->alias,
			'nama' 				=> $data->guest_name,
			'username' 			=> $data->username,
			'password' 			=> $data->password,
			'role_id'			=> $data->recid_role,
			'role_name'			=> $data->nama_role,
			'as_user'			=> $data->kategori,
			'logged_in'			=> TRUE
		);
		$this->session->set_userdata($session);
	}


	function remov_session()
	{
		$session = array(
			'recid_login' 		=> '',
			'nik' 				=> '',
			'nama' 				=> '',
			'kar_id' 			=> '',
			'foto'				=> '',
			'username' 			=> '',
			'password' 			=> '',
			'role_id'			=> '',
			'role_name'			=> '',
			'logged_in'		=> FALSE
		);
		$this->session->unset_userdata($session);
	}

	function save_hlogin($data)
	{
		$this->db->insert('hlogin', $data);
	}
}

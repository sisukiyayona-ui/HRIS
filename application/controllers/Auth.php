<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('M_auth', 'm_hris'));
	}

	public function index()
	{
		if ($this->session->userdata('logged_in') == TRUE) {
			$a = $this->session->userdata('nik');
			// echo "id - $a";
			redirect('Karyawan/dash', 'refresh');
		} else {
			redirect('Karyawan/index');
			$a = $this->session->userdata('nik');
			// echo "id - $a";             
		}
	}

	public function ganti_password()
	{
		$options = array(
			'img_path'   => FCPATH . 'captcha/',
			'img_url'    => base_url('captcha/'),
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
		if ($cap === FALSE || !isset($cap['image'], $cap['word'])) {
			log_message('error', 'Gagal membuat CAPTCHA (ganti_password). Pastikan ekstensi GD aktif dan folder captcha writable: ' . $options['img_path']);
			$data['image'] = '<small class="text-danger">Captcha tidak tersedia. Hubungi admin.</small>';
			$this->session->unset_userdata('mycaptcha');
		} else {
			$data['image'] = $cap['image'];
			$this->session->set_userdata('mycaptcha', $cap['word']);
		}

		$data['word'] = $this->session->userdata('mycaptcha');
		$this->load->view('layout/a_header');
		$this->load->view('layout/ganti_pwd', $data);
	}

	public function cekLogin()
	{
		$captcha = $this->input->post('captcha_code'); #mengambil value inputan pengguna
		$word = $this->session->userdata('mycaptcha'); #mengambil value captcha
		if (isset($captcha)) { #cek variabel $captcha kosong/tidak
			if (strtoupper($captcha) == strtoupper($word)) { #proses pencocokan captcha
				$username		= $this->input->post('uname');
				$password		= $this->input->post('password');
				$test 			= md5($password);

				// echo "$username - $password - $test";
				$where_login['username']	= $username;
				$where_login['password']	= md5($password);
				$lulus = $this->M_auth->cek_login($where_login);
				// echo $password."<br>";
				// echo $test."<br>";
				// echo $where_login['password'];

				if ($this->M_auth->cek_login($where_login) === TRUE) {
					$as_user = $this->session->userdata('as_user');
					if ($as_user == "CINT") {
						$last = $this->M_auth->cek_pwd($where_login);
						foreach ($last->result() as $l) {
							$last_change = $l->last_pwd_change;
						}

						if ($last_change === null) {
?>
							<script type="text/javascript">
								alert('Password Expired, Silahkan Ganti Password Untuk Melanjutkan');
								window.location.href = "<?php echo base_url(); ?>Auth/ganti_password";
							</script>
							<?php
						} else {
							$tgl1 = strtotime($last_change);
							$tgl2 = strtotime(date('Y-m-d'));

							$jarak = $tgl2 - $tgl1;

							$hari = $jarak / 60 / 60 / 24;
							// echo $hari;
							if ($hari > 90) {
							?>
								<script type="text/javascript">
									alert('Password Expired, Silahkan Ganti Password Untuk Melanjutkan');
									window.location.href = "<?php echo base_url(); ?>Auth/ganti_password";
								</script>
					<?php
							} else {
								$a = $this->session->userdata('username');
								$recid_login = $this->session->userdata('recid_login');
								$data = array(
									'recid_login' => $recid_login
								);
								$this->M_auth->save_hlogin($data);
								redirect("Karyawan/dash");
							}
						}
					} else {
						$a = $this->session->userdata('username');
						$recid_login = $this->session->userdata('recid_login');
						$data = array(
							'recid_login' => $recid_login
						);
						$this->M_auth->save_hlogin($data);
						redirect("Karyawan/dash");
					}
				} else {
					?>
					<script type="text/javascript">
						alert('Username dan Password tidak cocok!');
						window.location.href = "<?php echo base_url(); ?>";
					</script>
				<?php
				}
			} else {
				$this->session->set_flashdata('message', 'Validation Fail Try Again'); ?>
				<script type="text/javascript">
					alert('Validation Fail Try Again!');
					window.location.href = "<?php echo base_url(); ?>Auth";
				</script>
			<?php }
		} else {
			$this->session->set_flashdata('message', 'Validation Fail Try Again'); ?>
			<script type="text/javascript">
				alert('Validation Fail Try Again!');
				window.location.href = "<?php echo base_url(); ?>Auth";
			</script>
		<?php }
	}

	// Endpoint diagnostik ringan untuk mengecek syarat CAPTCHA
	public function diagnose_captcha()
	{
		$img_path = FCPATH . 'captcha/';
		$img_url  = base_url('captcha/');
		$checks = array(
			'gd_loaded'    => extension_loaded('gd') ? 'YES' : 'NO',
			'dir_exists'   => is_dir($img_path) ? 'YES' : 'NO',
			'dir_writable' => (function_exists('is_really_writable') ? (is_really_writable($img_path) ? 'YES' : 'NO') : (is_writable($img_path) ? 'YES' : 'NO')),
			'font_exists'  => (file_exists(FCPATH . 'assets/coolvetica.ttf') ? 'YES' : 'NO'),
			'img_path'     => $img_path,
			'img_url'      => $img_url
		);
		header('Content-Type: application/json');
		echo json_encode($checks, JSON_PRETTY_PRINT);
	}

	public function keluar()
	{
		$this->M_auth->remov_session();
		session_destroy();
		redirect("Auth");
	}

	public function user_change()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		//Get Data By Id
		$cek = $this->m_hris->user_by_username($username);
		if ($cek->num_rows() == 0) {
		?>
			<script type="text/javascript">
				alert('Username Tidak Ditemukan!');
				window.location.href = "<?php echo base_url(); ?>Auth/ganti_password";
			</script>
			<?php
		} else {
			foreach ($cek->result() as $c) {
				$recid_login = $c->recid_login;
				$recid_karyawan = $c->recid_karyawan;
				$username2 = $c->username;
				$password2 = $c->password;
				$recid_role = $c->recid_role;
			}

			$histori = $this->m_hris->histori_pwd_user($recid_login, md5($password));
			if ($histori->num_rows() > 0) {
			?>
				<script type="text/javascript">
					alert('Gagal Merubah Akun! Password Sudah Pernah Digunakan!');
					window.location.href = "<?php echo base_url(); ?>Auth/ganti_password";
				</script>
			<?php
			} else {
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
					$data3 = array(
						'crt_by'		=> $recid_karyawan,
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
						'mdf_by'		=> $recid_karyawan,
						'mdf_date'		=> date('y-m-d h:i:s'),
						'last_pwd_change'	=> date('Y-m-d')
					);
				} else {
					$data2 = array(
						'crt_by'		=>  $recid_karyawan,
						'crt_date'		=> date('y-m-d h:i:s'),
						'username'		=> $username,
						'password'		=> $password2,
						'mdf_by'		=>  $recid_karyawan,
						'mdf_date'		=> date('y-m-d h:i:s'),
					);
				}
				$this->m_hris->user_update($data2, $recid_login);
				//Insert Log
				$data2 = array(
					'mdf_by'		=>  $recid_karyawan,
					'mdf_date'		=> date('y-m-d h:i:s'),
					'changed'		=> $text,
					'identity'		=> $recid_login,
				);
				$this->m_hris->user_linsert($data2);
			?>
				<script type="text/javascript">
					alert('Password Berhasil Diubah, Silahkan Login Kembali');
					window.location.href = "<?php echo base_url(); ?>";
				</script>
<?php
			}
		}
	}
}

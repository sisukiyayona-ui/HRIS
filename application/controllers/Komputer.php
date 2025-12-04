<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Komputer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // muat model sesuai konvensi proyek
        $this->load->model(array('m_komputer', 'm_hris'));
        $this->load->library('session');
    }

    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != 1) {
            redirect('Auth/keluar');
            return;
        }

        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);

        // ambil data komputer
        $data['rows'] = $this->m_komputer->get_all();

        // muat layout (mengikuti pola yang ada di proyek)
        $this->load->view('layout/a_header');
        // gunakan menu_super agar tampilan konsisten; menu mungkin butuh data cek_usr
        $this->load->view('layout/menu_super', $data);
        $this->load->view('komputer/list_view', $data);
        $this->load->view('layout/a_footer');
    }
}

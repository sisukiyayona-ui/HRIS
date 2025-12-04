<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UpahCustom extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    $this->load->model(array('m_hris','m_gaji'));
    }

    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);

            // ambil data gaji (limit 100 untuk view awal)
            $query = $this->m_gaji->get_all(100, 0);
            $data['gaji'] = $query->result();

            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah_custom/index', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }
}

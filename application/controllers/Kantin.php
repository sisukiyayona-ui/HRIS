<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kantin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('m_absen', 'm_hris', 'm_kantin'));
        // ini_set('max_execution_time', 600);
        ini_set('memory_limit', "1024M");
        ob_start(); # add this
    }

    public function index()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $tgl = date('Y-m-d');
            $hadir = "(status = '1' or status = '4' or status = '9' or (status between 13 and 23))";
            $absen = "(status = '2' or status = '3' or(status between 5 and 8) or (status between 10 and 12) or status = '25' or status = '24')";
            $data['totkar'] = $this->m_kantin->jml_all();   // termasuk TC
            $total_hadir = $this->m_kantin->jml_by_status($tgl, $hadir); // termasuku TC
            $data['total_hadir']    = $total_hadir->num_rows();
            $data['total_tidak_hadir'] = $this->m_kantin->jml_by_status($tgl, $absen);
            $data['makan_karyawan'] = $this->m_kantin->data_makan_karyawan($tgl);
            $data['makan_karyawan_ind'] = $this->m_kantin->data_makan_karyawani($tgl);
            $data['makan_karyawan_brs'] = $this->m_kantin->data_makan_karyawanb($tgl);
            $data['tidak_makan_karyawan'] = $this->m_kantin->karyawan_blm_makan($tgl);
            $data['tidak_makan_karyawani'] = $this->m_kantin->karyawan_blm_makani($tgl);
            $data['tidak_makan_karyawanb'] = $this->m_kantin->karyawan_blm_makanb($tgl);
            $data['all_tamu'] = $this->m_kantin->all_tamu_pkl();
            $data['all_koprasi'] = $this->m_kantin->all_kop_out();
            $data['makan_tamu'] = $this->m_kantin->data_makan_tamu($tgl);
            $data['makan_tamu_ind'] = $this->m_kantin->data_makan_tamui($tgl);
            $data['makan_tamu_brs'] = $this->m_kantin->data_makan_tamub($tgl);
            $data['makan_jemputan'] = $this->m_kantin->data_makan_jemputan($tgl);
            $data['makan_koprasi'] = $this->m_kantin->data_makan_koprasi($tgl);
            $data['makan_koprasii'] = $this->m_kantin->data_makan_koprasii($tgl);
            $data['makan_koprasib'] = $this->m_kantin->data_makan_koprasib($tgl);
            $data['tidak_makan_tamu'] = $this->m_kantin->tamu_blm_makan($tgl);
            $data['tidak_makan_koprasi'] = $this->m_kantin->koprasi_blm_makan($tgl);
            $data['manual_karyawan'] = $this->m_kantin->data_manual_karyawan($tgl);
            $data['manual_tamu'] = $this->m_kantin->data_manual_tamu($tgl);
            $data['manual_koprasi'] = $this->m_kantin->data_manual_koprasi($tgl);
            $data['manual_koprasi_ind'] = $this->m_kantin->data_manual_koprasii($tgl);
            $data['manual_koprasi_brs'] = $this->m_kantin->data_manual_koprasib($tgl);
            $data['scan_karyawan'] = $this->m_kantin->data_scan_karyawan($tgl);
            $data['scan_tamu'] = $this->m_kantin->data_scan_tamu($tgl);
            $data['scan_koprasi'] = $this->m_kantin->data_scan_koprasi($tgl);
            $data['scan']    = $this->m_kantin->all_scan($tgl);
            $data['manual']    = $this->m_kantin->all_manual($tgl);
            $data['flat']    = $this->m_kantin->all_flat($tgl);
            $data['jemputan'] = $this->m_kantin->data_jemputan($tgl);
            $data['mess_karyawan'] = $this->m_kantin->data_mess_karyawan($tgl);
            $data['mess_tamu'] = $this->m_kantin->data_mess_tamu($tgl);
            $data['absen_makan'] = $this->m_kantin->compare_absen_makan($tgl);
            $data['makan_absen'] = $this->m_kantin->compare_makan_absen($tgl);

            $data['direksi_scan'] = $this->m_kantin->dist_direksi($tgl, '0');
            $data['direksi_manual'] = $this->m_kantin->dist_direksi($tgl, '1');
            $data['direksi_flat'] = $this->m_kantin->dist_direksi($tgl, '3');

            $data['baros_scan'] = $this->m_kantin->dist_baros($tgl, '0');
            $data['baros_manual'] = $this->m_kantin->dist_baros($tgl, '1');
            $data['baros_flat'] = $this->m_kantin->dist_baros($tgl, '3');

            $data['industri_scan'] = $this->m_kantin->dist_industri($tgl, '0');
            $data['industri_manual'] = $this->m_kantin->dist_industri($tgl, '1');
            $data['industri_flat'] = $this->m_kantin->dist_industri($tgl, '3');

            $data['kopout_scan'] = $this->m_kantin->dist_kopout($tgl, '0');
            $data['kopout_manual'] = $this->m_kantin->dist_kopout($tgl, '1');
            $data['kopout_flat'] = $this->m_kantin->dist_kopout($tgl, '3');

            $data['keamanan_scan'] = $this->m_kantin->dist_keamanan($tgl, '0');
            $data['keamanan_manual'] = $this->m_kantin->dist_keamanan($tgl, '1');
            $data['keamanan_flat'] = $this->m_kantin->dist_keamanan($tgl, '3');

            $data['pengemudi_scan'] = $this->m_kantin->dist_pengemudi($tgl, '0');
            $data['pengemudi_manual'] = $this->m_kantin->dist_pengemudi($tgl, '1');
            $data['pengemudi_flat'] = $this->m_kantin->dist_pengemudi($tgl, '3');

            $data['jemputan_scan'] = $this->m_kantin->dist_jemputan($tgl, '0');
            $data['jemputan_manual'] = $this->m_kantin->dist_jemputan($tgl, '1');
            $data['jemputan_flat'] = $this->m_kantin->dist_jemputan($tgl, '3');

            $data['pkltamu_scan'] = $this->m_kantin->dist_pkltamu($tgl, '0');
            $data['pkltamu_manual'] = $this->m_kantin->dist_pkltamu($tgl, '1');
            $data['pkltamu_flat'] = $this->m_kantin->dist_pkltamu($tgl, '3');

            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/dash', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function scan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/scan', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function scan_baros()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/scan_baros', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function cek_hadir()
    {
        $data = array();
        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        $rekar = $this->m_kantin->karyawan_by_nik($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                //cek double scan
                $double = $this->m_kantin->cek_double($recid_karyawan, $tgl);
                if ($double->num_rows() == 0) {
                    $absen = $this->m_absen->cek_emp_hadir($recid_karyawan, $tgl);
                    if ($absen->num_rows() > 0) {
                        foreach ($absen->result() as $a) {
                            $absen_group = $a->absen_group;
                            $status = $a->status;
                            $keterangan = $a->keterangan;
                            if ($status == '24') {
                                $absen_group = "Tidak Hadir";
                            }
                            array_push($data, $absen_group);
                            array_push($data, $keterangan);
                        }
                    } else {
                        array_push($data, "Tidak Hadir");
                        array_push($data, "Belum Absen");
                    }
                } else {
                    foreach ($double->result() as $d) {
                        $lokasi = $d->lokasi;
                    }
                    array_push($data, "Sudah Scan");
                    array_push($data, $lokasi);
                }
            }
        } else {
            $recid_karyawan = 0;
            $tamu = $this->m_kantin->tamu_by_barcode($nik);
            foreach ($tamu->result() as $t) {
                $recid_tamu = $t->guest_id;
                $double = $this->m_kantin->cek_double_tamu($recid_tamu, $tgl);
                if ($double->num_rows() < 1) {
                    $absen_group = "Hadir";
                    $keterangan = "Kerja";
                    array_push($data, $absen_group);
                    array_push($data, $keterangan);
                } else {
                    // array_push($data, "Sudah Scan");
                    foreach ($double->result() as $d) {
                        $lokasi = $d->lokasi;
                    }
                    array_push($data, "Sudah Scan");
                    array_push($data, $lokasi);
                }
            }
        }
        echo json_encode($data);
    }

    public function save_makan()
    {
        $nik = $this->input->post('nik');
        // $nik = "92022000001";
        $rekar = $this->m_kantin->karyawan_by_nik($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                $guest_id = 0;
                $kategori = "Karyawan";
            }
        } else {
            $recid_karyawan = 0;
            $tamu = $this->m_kantin->tamu_by_barcode($nik);
            foreach ($tamu->result() as $t) {
                $guest_id = $t->guest_id;
                $kategori = $t->kategori;
            }
        }
        $data = array(
            'crt_date'          => date('Y-m-d H:i:s'),
            'kategori'          => $kategori,
            'recid_karyawan'    => $recid_karyawan,
            'recid_tamu'        => $guest_id,
            'tgl_makan'         => date('Y-m-d'),
            'manuals'            => "0",
            'alasan'            => "",
            "lokasi"            => "Industri"
        );
        $this->m_kantin->save_makan($data);
        echo json_encode("Ok");
    }

    public function save_makan_baros()
    {
        $nik = $this->input->post('nik');
        // $nik = "92022000001";
        $rekar = $this->m_kantin->karyawan_by_nik($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                $guest_id = 0;
                $kategori = "Karyawan";
            }
        } else {
            $recid_karyawan = 0;
            $tamu = $this->m_kantin->tamu_by_barcode($nik);
            foreach ($tamu->result() as $t) {
                $guest_id = $t->guest_id;
                $kategori = $t->kategori;
            }
        }
        $data = array(
            'crt_date'          => date('Y-m-d H:i:s'),
            'kategori'          => $kategori,
            'recid_karyawan'    => $recid_karyawan,
            'recid_tamu'        => $guest_id,
            'tgl_makan'         => date('Y-m-d'),
            'manuals'            => "0",
            'alasan'            => "",
            "lokasi"            => "Baros"
        );
        $this->m_kantin->save_makan($data);
        echo json_encode("Ok");
    }

    public function manual_input()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['opsi'] = $this->m_kantin->opsi_manuals();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/manual_input', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function manual_input_baros()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['opsi'] = $this->m_kantin->opsi_manuals();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/manual_input_baros', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function belum_makan()
    {
        // $tgl = date("Y-m-d");
        $tgl = $this->input->post('tgl_makan');
        $karyawan = $this->m_kantin->karyawan_blm_makan($tgl);
        $guest = $this->m_kantin->tamu_blm_makan($tgl);
        $outsource = $this->m_kantin->outsource_blm_makan($tgl);
        $koprasi = $this->m_kantin->koprasi_blm_makan($tgl);

        $lists = "<option value=''>Pilih</option>";
        foreach ($karyawan->result() as $data) {
            $lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
        }
        foreach ($guest->result() as $data) {
            $lists .= "<option value='" . $data->no_barcode . "'>" . $data->guest_name . "</option>"; // Tambahkan tag option ke variabel $lists
        }
        foreach ($outsource->result() as $data) {
            $lists .= "<option value='" . $data->no_barcode . "'>" . $data->guest_name . "</option>"; // Tambahkan tag option ke variabel $lists
        }
        foreach ($koprasi->result() as $data) {
            $lists .= "<option value='" . $data->no_barcode . "'>" . $data->guest_name . "</option>"; // Tambahkan tag option ke variabel $lists
        }
        $callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

        echo json_encode($callback); // konversi varibael $callback menjadi JSON
    }

    public function save_manual()
    {
        $nik = $this->input->post('nik');
        $alasan = $this->input->post('alasan');
        $tgl_makan = $this->input->post('tgl_makan');
        $rekar = $this->m_kantin->karyawan_by_recid($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                $guest_id = 0;
                $kategori = "Karyawan";
            }
        } else {
            $recid_karyawan = 0;
            $tamu = $this->m_kantin->tamu_by_barcode($nik);
            foreach ($tamu->result() as $t) {
                $guest_id = $t->guest_id;
                $kategori = $t->kategori;
            }
        }
        $data = array(
            'crt_date'          => date('Y-m-d H:i:s'),
            'kategori'          => $kategori,
            'recid_karyawan'    => $recid_karyawan,
            'recid_tamu'        => $guest_id,
            'tgl_makan'         => $tgl_makan,
            'manuals'            => "1",
            'lokasi'            => "Industri",
            'alasan'            => $alasan,
        );
        $this->m_kantin->save_makan($data);
        echo json_encode("Ok");
    }

    public function save_manual_baros()
    {
        $nik = $this->input->post('nik');
        $alasan = $this->input->post('alasan');
        $tgl_makan = $this->input->post('tgl_makan');
        $rekar = $this->m_kantin->karyawan_by_recid($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                $guest_id = 0;
                $kategori = "Karyawan";
            }
        } else {
            $recid_karyawan = 0;
            $tamu = $this->m_kantin->tamu_by_barcode($nik);
            foreach ($tamu->result() as $t) {
                $guest_id = $t->guest_id;
                $kategori = $t->kategori;
            }
        }
        $data = array(
            'crt_date'          => date('Y-m-d H:i:s'),
            'kategori'          => $kategori,
            'recid_karyawan'    => $recid_karyawan,
            'recid_tamu'        => $guest_id,
            'tgl_makan'         => $tgl_makan,
            'manuals'            => "1",
            'lokasi'            => "Baros",
            'alasan'            => $alasan,
        );
        $this->m_kantin->save_makan($data);
        echo json_encode("Ok");
    }

    public function manual_mess()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['opsi'] = $this->m_kantin->opsi_manuals();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/mess', $data);
            $this->load->view('layout/a_footer');
        } else {
        }
    }

    public function belum_makan_top()
    {
        $tgl = date("Y-m-d");
        $karyawan = $this->m_kantin->belum_makan_top($tgl);
        $guest = $this->m_kantin->tamu_blm_makan($tgl);

        $lists = "";
        foreach ($karyawan->result() as $data) {
            $lists .= "<option value='" . $data->recid_karyawan . "'>" . $data->nama_karyawan . " ( " . $data->indeks_hr . " )</option>"; // Tambahkan tag option ke variabel $lists
        }
        foreach ($guest->result() as $data) {
            $lists .= "<option value='" . $data->no_barcode . "'>" . $data->guest_name . "</option>"; // Tambahkan tag option ke variabel $lists
        }
        $callback = array('list_karyawan' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

        echo json_encode($callback); // konversi varibael $callback menjadi JSON
    }

    public function save_mess()
    {
        $nik = $this->input->post('recid_karyawan');
        $alasan = "Makan Di Mess / Pantry";

        for ($i = 0; $i < count($nik); $i++) {
            $rekar = $this->m_kantin->karyawan_by_recid($nik[$i]);
            if ($rekar->num_rows() > 0) {
                foreach ($rekar->result() as $r) {
                    $recid_karyawan = $r->recid_karyawan;
                    $guest_id = 0;
                    $kategori = "Karyawan";
                }
            } else {
                $recid_karyawan = 0;
                $tamu = $this->m_kantin->tamu_by_barcode($nik[$i]);
                foreach ($tamu->result() as $t) {
                    $guest_id = $t->guest_id;
                    $kategori = $t->kategori;
                }
            }
            $data = array(
                'crt_date'          => date('Y-m-d H:i:s'),
                'kategori'          => $kategori,
                'recid_karyawan'    => $recid_karyawan,
                'recid_tamu'        => $guest_id,
                'tgl_makan'         => date('Y-m-d'),
                'manuals'            => "2",
                'alasan'            => $alasan,
            );
            $this->m_kantin->save_makan($data);
        }
        redirect("Kantin/manual_mess");
    }

    public function save_baros()
    {
        $tgl = $this->input->post('tanggal');
        // $cek_absen = $this->m_kantin->generate_makan_baros($tgl);   // all baros
        $top = $this->m_kantin->belum_makan_top($tgl);  // all manager
        $lainnya = $this->m_kantin->flat_kupon_lainnya($tgl); // pengemudi & satpam chitose
        $jemputan = $this->m_kantin->flat_kupon_jemputan($tgl); // supir jemputan
        $baros_out_kop = $this->m_kantin->flat_kupon_baros_outkop($tgl); // supir jemputan

        // foreach ($cek_absen->result() as $ca) {
        //     $cek_dbl = $this->m_kantin->cek_double($ca->recid_karyawan, $tgl);
        //     if ($cek_dbl->num_rows() == 0) {
        //         if ($ca->tingkatan > 9) {
        //             $alasan = "Mess / Pantry";
        //         } else {
        //             $alasan = "Karyawan Baros";
        //         }
        //         $data = array(
        //             'crt_date'          => date('Y-m-d H:i:s'),
        //             'kategori'          => "Karyawan",
        //             'recid_karyawan'    => $ca->recid_karyawan,
        //             'recid_tamu'        => 0,
        //             'tgl_makan'         => $tgl,
        //             'manuals'            => "3",
        //             'alasan'            => $alasan,
        //         );
        //         $this->m_kantin->save_makan($data);
        //     }
        // }

        foreach ($top->result() as $t) {
            $cek_dbl = $this->m_kantin->cek_double($t->recid_karyawan, $tgl);
            if ($cek_dbl->num_rows() == 0) {
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'kategori'          => "Karyawan",
                    'recid_karyawan'    => $t->recid_karyawan,
                    'recid_tamu'        => 0,
                    'tgl_makan'         => $tgl,
                    'manuals'            => "3",
                    'alasan'            => "Mess / Pantry",
                );
                $this->m_kantin->save_makan($data);
            }
        }

        foreach ($lainnya->result() as $l) {
            $cek_dbl = $this->m_kantin->cek_double($l->recid_karyawan, $tgl);
            if ($cek_dbl->num_rows() == 0) {
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'kategori'          => "Karyawan",
                    'recid_karyawan'    => $l->recid_karyawan,
                    'recid_tamu'        => 0,
                    'tgl_makan'         => $tgl,
                    'manuals'            => "3",
                    'alasan'            => "Flat",
                );
                $this->m_kantin->save_makan($data);
            }
        }

        foreach ($baros_out_kop->result() as $br) {
            $cek_dbl = $this->m_kantin->cek_double_tamu($br->guest_id, $tgl);
            if ($cek_dbl->num_rows() == 0) {
                if ($br->kategori == "Outsource") {
                    $alasan = "Outsource Baros Flat";
                } else {
                    $alasan = "Koprasi Baros Flat";
                }
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'kategori'          => $br->kategori,
                    'recid_karyawan'    => 0,
                    'recid_tamu'        => $br->guest_id,
                    'tgl_makan'         => $tgl,
                    'manuals'            => "3",
                    'alasan'            => $alasan,
                );
                $this->m_kantin->save_makan($data);
            }
        }

        foreach ($jemputan->result() as $kp) {
            $cek_dbl = $this->m_kantin->cek_double_tamu($kp->guest_id, $tgl);
            if ($cek_dbl->num_rows() == 0) {
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'kategori'          => "Jemputan",
                    'recid_karyawan'    => 0,
                    'recid_tamu'        => $kp->guest_id,
                    'tgl_makan'         => $tgl,
                    'manuals'            => "3",
                    'alasan'            => "Supir Jemputan",
                );
                $this->m_kantin->save_makan($data);
            }
        }
        redirect("Kantin/generate_baros");
    }

    public function generate_baros()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['opsi'] = $this->m_kantin->opsi_manuals();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/baros', $data);
            $this->load->view('layout/a_footer');
        } else {
        }
    }

    public function generate_industri()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['opsi'] = $this->m_kantin->opsi_manuals();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/industri', $data);
            $this->load->view('layout/a_footer');
        } else {
        }
    }

    public function makan_today()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_kantin->data_makan_today($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->recid_karyawan != 0) {
                $detkar = $this->m_kantin->karyawan_by_recid($r->recid_karyawan);
                foreach ($detkar->result() as $dk) {
                    $nama = $dk->nama_karyawan;
                    $bagian = $dk->indeks_hr;
                    $nik = $dk->nik;
                }
            } else {
                $detamu = $this->m_kantin->tamu_by_recid($r->recid_tamu);
                foreach ($detamu->result() as $dt) {
                    $nama = $dt->guest_name;
                    $bagian = $dt->kategori;
                    $nik = $dt->no_barcode;
                }
            }
            $data[] = array(
                $no = $no + 1,
                $nik,
                $nama,
                $bagian,
                $r->waktu_makan,
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

    public function makan_today_baros()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_kantin->data_makan_today_baros($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->recid_karyawan != 0) {
                $detkar = $this->m_kantin->karyawan_by_recid($r->recid_karyawan);
                foreach ($detkar->result() as $dk) {
                    $nama = $dk->nama_karyawan;
                    $bagian = $dk->indeks_hr;
                    $nik = $dk->nik;
                }
            } else {
                $detamu = $this->m_kantin->tamu_by_recid($r->recid_tamu);
                foreach ($detamu->result() as $dt) {
                    $nama = $dt->guest_name;
                    $bagian = $dt->kategori;
                    $nik = $dt->no_barcode;
                }
            }
            $data[] = array(
                $no = $no + 1,
                $nik,
                $nama,
                $bagian,
                $r->waktu_makan,
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

    public function monitor_kantin()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_kantin->monitor_kantin($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->recid_karyawan != 0) {
                $detkar = $this->m_kantin->karyawan_by_recid($r->recid_karyawan);
                foreach ($detkar->result() as $dk) {
                    $nama = $dk->nama_karyawan;
                    $bagian = $dk->indeks_hr;
                    $nik = $dk->nik;
                }
            } else {
                $detamu = $this->m_kantin->tamu_by_recid($r->recid_tamu);
                foreach ($detamu->result() as $dt) {
                    $nama = $dt->guest_name;
                    $bagian = $dt->kategori;
                    $nik = $dt->no_barcode;
                }
            }
            $data[] = array(
                $no = $no + 1,
                $nik,
                $nama,
                $bagian,
                $r->waktu_makan,
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

    public function monitor_kantin_baros()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_kantin->monitor_kantin_baros($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->recid_karyawan != 0) {
                $detkar = $this->m_kantin->karyawan_by_recid($r->recid_karyawan);
                foreach ($detkar->result() as $dk) {
                    $nama = $dk->nama_karyawan;
                    $bagian = $dk->indeks_hr;
                    $nik = $dk->nik;
                }
            } else {
                $detamu = $this->m_kantin->tamu_by_recid($r->recid_tamu);
                foreach ($detamu->result() as $dt) {
                    $nama = $dt->guest_name;
                    $bagian = $dt->kategori;
                    $nik = $dt->no_barcode;
                }
            }
            $data[] = array(
                $no = $no + 1,
                $nik,
                $nama,
                $bagian,
                $r->waktu_makan,
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

    public function data_mess()
    {
        $tgl_awal = $this->input->post('tgl_mulai');
        // $tgl_awal = '2022-08-22';
        $query = $this->m_kantin->data_mess_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_mess_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->waktu_makan,
            );
        }

        foreach ($query2->result() as $r) {

            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->waktu_makan,
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

    public function data_manual()
    {
        $role = $this->session->userdata('role_id');
        $tgl_awal = $this->input->post('tgl_mulai');
        // $tgl_awal = '2022-08-22';
        $query = $this->m_kantin->data_manual_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_manual_tamu($tgl_awal);
        $query3 = $this->m_kantin->data_manual_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
            );
        }

        foreach ($query2->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
            );
        }

        foreach ($query3->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
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

    public function data_manual_baros()
    {
        $role = $this->session->userdata('role_id');
        $tgl_awal = $this->input->post('tgl_mulai');
        // $tgl_awal = '2022-08-22';
        $query = $this->m_kantin->data_manual_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_manual_tamu($tgl_awal);
        $query3 = $this->m_kantin->data_manual_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
            );
        }

        foreach ($query2->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
            );
        }

        foreach ($query3->result() as $r) {
            if ($role == '1' or $role == '3') {
                $del = "<a href='" . base_url() . "Kantin/delete_manual/" . $r->makan_id . "'><button class='btn btn-danger'>Delete</button></a>";
            } else {
                $del = "";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->alasan,
                $r->waktu_makan,
                $r->lokasi,
                $del
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

    public function delete_manual()
    {
        $id_makan = $this->uri->segment(3);
        $this->m_kantin->delete_manual($id_makan);
        redirect('kantin/manual_input');
    }

    public function delete_flat()
    {
        $id_makan = $this->uri->segment(3);
        $this->m_kantin->delete_manual($id_makan);
        redirect('kantin/generate_baros');
    }

    public function data_flat()
    {
        $tgl_awal = $this->input->post('tgl_mulai');
        $query = $this->m_kantin->data_flat_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_flat_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->tgl_makan,
                $r->alasan,
                "<a href='" . base_url() . "Kantin/delete_flat/$r->makan_id'><button type='button' class='btn btn-danger'><i class='fa fa-trash'></i></button></a>"
            );
        }

        foreach ($query2->result() as $r) {

            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kat,
                $r->penempatan,
                $r->tgl_makan,
                $r->alasan,
                "<a href='" . base_url() . "Kantin/delete_flat/$r->makan_id'><button type='button' class='btn btn-danger'><i class='fa fa-trash'></i></button></a>"

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

    public function All_Karyawan()
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

    public function list_karyawan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $data['karyawan'] = $this->m_kantin->jml_all()->result();
            $data['menu'] = "Data Karyawan";
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('karyawan/report/r_dinamis', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function list_hadir()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $hadir = "(status = '1' or status = '4' or status = '9' or (status between 12 and 23))";
            $data['absen'] = $this->m_kantin->jml_by_status($tgl, $hadir); // termasuku TC
            $data['status'] = "Data Karyawan Hadir Tanggal $tgl";
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/new_report/detail_dashboard', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function list_absen()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $absen = "(status = '2' or status = '3' or(status between 5 and 8) or (status between 10 and 12) or status = '25' or status = '24')";
            $data['absen'] = $this->m_kantin->jml_by_status($tgl, $absen); // termasuku TC
            $data['status'] = "Data Karyawan Tidak Hadir Tanggal $tgl";
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/new_report/detail_dashboard', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function detail_makan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl_awal = date('Y-m-d');
            $kategori = $this->uri->segment(3);
            $lokasi = $this->uri->segment(4);
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            if ($kategori == "makan") {
                $data['menu'] = "Data Karyawan Makan";
                $data['tipe'] = "Karyawan";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_makan', $data);
            } else if ($kategori == "tidak_makan") {
                $data['menu'] = "Data Karyawan Tidak Makan";
                $data['tipe'] = "Karyawan";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_tidak_makan', $data);
            } else if ($kategori == "tamu_tidak_makan") {
                $data['menu'] = "Data Tamu Tidak Makan";
                $data['tipe'] = "Tamu";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_tidak_makan', $data);
            } else if ($kategori == "koprasi_tidak_makan") {
                $data['menu'] = "Data Koprasi Tidak Makan";
                $data['tipe'] = "Koprasi";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_tidak_makan', $data);
            } else if ($kategori == "tamu_makan") {
                $data['menu'] = "Data Tamu & PKL Makan";
                $data['tipe'] = "Tamu";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_makan', $data);
            } else if ($kategori == "koprasi_makan") {
                $data['menu'] = "Data Koprasi & Outsource Makan";
                $data['tipe'] = "Koprasi";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_makan', $data);
            } else if ($kategori == "makan_seluruh") {
                $data['menu'] = "Data Makan Seluruhnya";
                $data['tipe'] = "Semua";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_makan', $data);
            } else {
                $data['menu'] = "Data  Tamu & PKL Tidak Makan";
                $data['tipe'] = "Tamu";
                $data['flag'] = "Manual & Scan";
                $data['lokasi'] = $lokasi;
                $this->load->view('kantin/report_makan', $data);
            }
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function data_makan_semua()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $query3 = $this->m_kantin->data_makan_koprasi($tgl_awal);
        $query4 = $this->m_kantin->data_makan_jemputan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query4->result() as $j) {
            if ($j->manuals == '1' or $j->manuals == '2') {
                $man = "Ya";
            } else if ($j->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $j->no_barcode,
                $j->guest_name,
                $j->kategori,
                $j->penempatan,
                $j->waktu_makan,
                $man,
                $j->alasan,
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

    public function data_makan_karyawan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_karyawan($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        // foreach ($query2->result() as $r) {
        //     if ($r->manuals == '1') {
        //         $man = "Ya";
        //     } else {
        //         $man = "Tidak";
        //     }
        //     $data[] = array(
        //         $no = $no + 1,
        //         $r->no_barcode,
        //         $r->guest_name,
        //         $r->kategori,
        //         $r->waktu_makan,
        //         $man,
        //         $r->alasan,
        //     );
        // }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data" => $data
        );

        echo json_encode($result);
        exit();
    }

    public function data_makan_karyawani()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_karyawani($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_karyawanb()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_karyawanb($tgl_awal);
        // $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_tamu()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->data_makan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_tamui()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->data_makan_tamui($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_tamub()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->data_makan_tamub($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_tidak_makan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->karyawan_blm_makan($tgl_awal);
        // $query2 = $this->m_kantin->tamu_blm_makan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan
            );
        }

        // foreach ($query2->result() as $r) {
        //     $data[] = array(
        //         $no = $no + 1,
        //         $r->no_barcode,
        //         $r->guest_name,
        //         $r->kategori,
        //     );
        // }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data" => $data
        );

        echo json_encode($result);
        exit();
    }

    public function tamu_tidak_makan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->tamu_blm_makan($tgl_awal);
        $query3 = $this->m_kantin->koprasi_blm_makan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
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

    public function koprasi_tidak_makan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->koprasi_blm_makan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
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

    public function semua_tidak_makan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->karyawan_blm_makan($tgl_awal);
        $query2 = $this->m_kantin->tamu_blm_makan($tgl_awal);
        $query3 = $this->m_kantin->koprasi_blm_makan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
            );
        }

        foreach ($query2->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
            );
        }

        foreach ($query3->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
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

    public function list_tamu()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $data['karyawan'] =
                $this->m_kantin->all_tamu_pkl()->result(); // termasuku TC
            $data['menu'] = "Data Tamu dan PKL";
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/list_tamu', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function list_koprasi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $data['karyawan'] = $this->m_kantin->all_kop_out()->result();
            $data['menu'] = "Data Koprasi";
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/list_tamu', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function chart_makan()
    {
        $pilihan = $this->input->post('pilihan');
        $periode = $this->input->post('periode');
        $tgl = date("d");
        $bulan = date("F");
        $tahun = date("Y");
        $sekarang = $tgl . " " . $bulan . " " . $tahun;
        if ($periode == $sekarang) {
            $tgl = date("Y-m-d");
        }

        $data = array();
        if ($pilihan == "Semua") {
            $karyawan_makan = $this->m_kantin->data_makan_karyawan($tgl);
            $tamu_makan = $this->m_kantin->data_makan_tamu($tgl);
            $koprasi_makan = $this->m_kantin->data_makan_koprasi($tgl);
            $all_makan = $karyawan_makan->num_rows() + $tamu_makan->num_rows() + $koprasi_makan->num_rows();

            $karyawan_tidak = $this->m_kantin->karyawan_blm_makan($tgl);
            $tamu_tidak = $this->m_kantin->tamu_blm_makan($tgl);
            $koprasi_tidak = $this->m_kantin->koprasi_blm_makan($tgl);
            $all_tidak = $karyawan_tidak->num_rows() + $tamu_tidak->num_rows() + $koprasi_tidak->num_rows();
        } else if ($pilihan == "Karyawan") {
            $karyawan_makan = $this->m_kantin->data_makan_karyawan($tgl);
            $all_makan = $karyawan_makan->num_rows();

            $karyawan_tidak = $this->m_kantin->karyawan_blm_makan($tgl);
            $all_tidak = $karyawan_tidak->num_rows();
        } else if ($pilihan == "Koprasi") {
            $koprasi_makan = $this->m_kantin->data_makan_koprasi($tgl);
            $all_makan = $koprasi_makan->num_rows();

            $koprasi_tidak = $this->m_kantin->koprasi_blm_makan($tgl);
            $all_tidak = $koprasi_tidak->num_rows();
        } else {
            $tamu_makan = $this->m_kantin->data_makan_tamu($tgl);
            $all_makan  = $tamu_makan->num_rows();

            $tamu_tidak = $this->m_kantin->tamu_blm_makan($tgl);
            $all_tidak = $tamu_tidak->num_rows();
        }
        array_push($data, $all_makan);
        array_push($data, $all_tidak);

        echo json_encode($data); // konversi varibael $callback menjadi JSON
    }

    public function detail_chart_makan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $kategori = $this->uri->segment(3);
            $opsi = $this->uri->segment(4);
            $periode = $this->uri->segment(5);
            $periode = str_replace("%20", " ", $periode);
            $tgl = date("d");
            $bulan = date("F");
            $tahun = date("Y");
            $sekarang = $tgl . " " . $bulan . " " . $tahun;
            if ($periode == $sekarang) {
                $tgl = date("Y-m-d");
            }

            if ($kategori == "Makan") {
                if ($opsi == "Semua") {
                    $data['menu'] = "Data Makan";
                    $data['tipe'] = "Semua";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Karyawan") {
                    $data['menu'] = "Data Karyawan Makan";
                    $data['tipe'] = "Karyawan";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_makan', $data);
                } else {
                    $data['menu'] = "Data Tamu Makan";
                    $data['tipe'] = "Tamu";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_makan', $data);
                }
            } else {
                if ($opsi == "Semua") {
                    $data['menu'] = "Data Tidak Makan";
                    $data['tipe'] = "Semua";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_tidak_makan', $data);
                } else if ($opsi == "Karyawan") {
                    $data['menu'] = "Data Karyawan Tidak Makan";
                    $data['tipe'] = "Karyawan";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_tidak_makan', $data);
                } else if ($opsi == "Koprasi") {
                    $data['menu'] = "Data Koprasi Tidak Makan";
                    $data['tipe'] = "Koprasi";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_tidak_makan', $data);
                } else {
                    $data['menu'] = "Data Tamu Tidak Makan";
                    $data['tipe'] = "Tamu";
                    $data['flag'] = "Manual & Scan";
                    $this->load->view('kantin/report_tidak_makan', $data);
                }
            }
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function chart_manual()
    {
        $pilihan = $this->input->post('pilihan');
        $periode = $this->input->post('periode');
        $tgl = date("d");
        $bulan = date("F");
        $tahun = date("Y");
        $sekarang = $tgl . " " . $bulan . " " . $tahun;
        if ($periode == $sekarang) {
            $tgl = date("Y-m-d");
        }

        $data = array();
        if ($pilihan == "Semua") {
            $manual_semua = $this->m_kantin->all_manual($tgl);
            $all_manual = ($manual_semua->num_rows());

            $scan_semua = $this->m_kantin->all_scan($tgl);
            $all_scan = ($scan_semua->num_rows());

            $flat_semua = $this->m_kantin->all_flat($tgl);
            $all_flat = ($flat_semua->num_rows());
        } else if ($pilihan == "Karyawan") {
            $karyawan_manual = $this->m_kantin->data_manual_karyawan($tgl);
            $all_manual = ($karyawan_manual->num_rows());

            $karyawan_scan = $this->m_kantin->data_scan_karyawan($tgl);
            $all_scan = ($karyawan_scan->num_rows());

            $karyawan_flat = $this->m_kantin->flat_karyawan($tgl);
            $all_flat = ($karyawan_flat->num_rows());
        } else if ($pilihan == "Koprasi") {
            $koprasi_manual = $this->m_kantin->data_manual_koprasi($tgl);
            $all_manual = ($koprasi_manual->num_rows());

            $koprasi_scan = $this->m_kantin->data_scan_koprasi($tgl);
            $all_scan = (($koprasi_scan->num_rows()));

            $koprasi_flat = $this->m_kantin->flat_koprasi($tgl);
            $all_flat = ($koprasi_flat->num_rows());
        } else {
            $tamu_manual = $this->m_kantin->data_manual_tamu($tgl);
            $all_manual = ($tamu_manual->num_rows());

            $tamu_scan = $this->m_kantin->data_scan_tamu($tgl);
            $all_scan = (($tamu_scan->num_rows()));

            $tamu_flat = $this->m_kantin->flat_tamu($tgl);
            $all_flat = ($tamu_flat->num_rows());
        }
        array_push($data, $all_manual);
        array_push($data, $all_scan);
        array_push($data, $all_flat);

        echo json_encode($data); // konversi varibael $callback menjadi JSON
    }

    public function detail_chart_manual()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $kategori = $this->uri->segment(3); // scan, manual
            $opsi = $this->uri->segment(4); // all, karyawan, tamu
            $periode = $this->uri->segment(5);
            $periode = str_replace("%20", " ", $periode);
            $tgl = date("d");
            $bulan = date("F");
            $tahun = date("Y");
            $sekarang = $tgl . " " . $bulan . " " . $tahun;
            if ($periode == $sekarang) {
                $tgl = date("Y-m-d");
            }

            if ($kategori == "Manual") {
                if ($opsi == "Semua") {
                    $data['menu'] = "Data Makan Manual";
                    $data['flag'] = "Manual"; // uri(3)
                    $data['tipe'] = "Semua"; // opsi
                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Karyawan") {
                    $data['menu'] = "Data Karyawan Makan Manual";
                    $data['flag'] = "Manual";
                    $data['tipe'] = "Karyawan";

                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Koprasi") {
                    $data['menu'] = "Data Koprasi Makan Manual";
                    $data['flag'] = "Manual";
                    $data['tipe'] = "Koprasi";

                    $this->load->view('kantin/report_makan', $data);
                } else {
                    $data['menu'] = "Data Tamu Makan Manual";
                    $data['flag'] = "Manual";
                    $data['tipe'] = "Tamu";

                    $this->load->view('kantin/report_makan', $data);
                }
            } else if ($kategori == "Flat") {
                if ($opsi == "Semua") {
                    $data['menu'] = "Data Makan Flat";
                    $data['flag'] = "Flat"; // uri(3)
                    $data['tipe'] = "Semua"; // opsi
                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Karyawan") {
                    $data['menu'] = "Data Karyawan Kupon Makan Flat";
                    $data['flag'] = "Flat";
                    $data['tipe'] = "Karyawan";

                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Koprasi") {
                    $data['menu'] = "Data Koprasi Kupon Makan Flat";
                    $data['flag'] = "Flat";
                    $data['tipe'] = "Koprasi";

                    $this->load->view('kantin/report_makan', $data);
                } else {
                    $data['menu'] = "Data Tamu Kupon Makan Flat";
                    $data['flag'] = "Flat";
                    $data['tipe'] = "Tamu";

                    $this->load->view('kantin/report_makan', $data);
                }
            } else {
                if ($opsi == "Semua") {
                    $data['menu'] = "Data Scan Makan";
                    $data['flag'] = "Scan";
                    $data['tipe'] = "Semua";
                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Karyawan") {
                    $data['menu'] = "Data Karyawan Scan Makan";
                    $data['flag'] = "Scan";
                    $data['tipe'] = "Karyawan";
                    $this->load->view('kantin/report_makan', $data);
                } else if ($opsi == "Koprasi") {
                    $data['menu'] = "Data Koprasi Scan Makan";
                    $data['flag'] = "Scan";
                    $data['tipe'] = "Koprasi";
                    $this->load->view('kantin/report_makan', $data);
                } else {
                    $data['menu'] = "Data Tamu Scan Makan";
                    $data['flag'] = "Scan";
                    $data['tipe'] = "Tamu";
                    $this->load->view('kantin/report_makan', $data);
                }
            }
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function data_manual_semua()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_manual_koprasi($tgl_awal);
        $query3 = $this->m_kantin->data_manual_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_ind()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawani($tgl_awal);
        $query2 = $this->m_kantin->data_manual_koprasii($tgl_awal);
        $query3 = $this->m_kantin->data_manual_tamui($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_semuab()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawanb($tgl_awal);
        $query2 = $this->m_kantin->data_manual_koprasib($tgl_awal);
        $query3 = $this->m_kantin->data_manual_tamub($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_karyawan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_mess_karyawan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_karyawani()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawani($tgl_awal);
        $query2 = $this->m_kantin->data_mess_karyawani($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_karyawanb()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_karyawanb($tgl_awal);
        $query2 = $this->m_kantin->data_mess_karyawanb($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }

            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_tamu()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_tamu($tgl_awal);
        $query2 = $this->m_kantin->data_mess_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_tamui()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_tamui($tgl_awal);
        $query2 = $this->m_kantin->data_mess_tamui($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_tamub()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_tamub($tgl_awal);
        $query2 = $this->m_kantin->data_mess_tamub($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_koprasi()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_koprasii()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_koprasii($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_manual_koprasib()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_manual_koprasib($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_semua()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_karyawan($tgl_awal);
        $query2 = $this->m_kantin->flat_jemputan($tgl_awal);
        $query3 = $this->m_kantin->flat_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_karyawan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_karyawan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_karyawani()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_karyawani($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_karyawanb()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_karyawanb($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_tamu()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_tamui()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamui($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_tamub()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamub($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_koprasi()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_koprasii()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamui($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_flat_koprasib()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->flat_tamub($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_makan_koprasi()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_koprasii()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_makan_koprasib()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_makan_koprasib($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $r->lokasi,
                $man,
                $r->alasan,
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

    public function data_scan_semua()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_scan_karyawan($tgl_awal);
        $query2 = $this->m_kantin->data_scan_tamu($tgl_awal);
        $query3 = $this->m_kantin->data_scan_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
            );
        }

        foreach ($query3->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_scan_karyawan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->data_scan_karyawan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_scan_tamu()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->data_scan_tamu($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_scan_koprasi()
    {
        $tgl_awal = $this->input->post('tgl');
        $query2 = $this->m_kantin->data_scan_koprasi($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query2->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->no_barcode,
                $r->guest_name,
                $r->kategori,
                $r->penempatan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function detail_chart_compare()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);

            $kategori = $this->uri->segment(3); // absen, makan
            $periode = $this->uri->segment(4);
            $periode = str_replace("%20", " ", $periode);
            $tgl = date("d");
            $bulan = date("F");
            $tahun = date("Y");
            $sekarang = $tgl . " " . $bulan . " " . $tahun;
            if ($periode == $sekarang) {
                $tgls = date("Y-m-d");
            }
            if ($kategori == "Absen") {
                $data['menu'] = "Data Komparasi Absensi dan Makan";
            } else {
                $data['menu'] = "Data Komparasi Kehadiran dan Makan";
            }
            $data['tipe'] = $kategori;
            $data['periode'] = $tgls;
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('kantin/report_compare', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function data_compare_absen()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->compare_absen_makan($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->keterangan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function data_compare_makan()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_kantin->compare_makan_absen($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            if ($r->manuals == '1' or $r->manuals == '2') {
                $man = "Ya";
            } else if ($r->manuals == '3') {
                $man = "Flat";
            } else {
                $man = "Tidak";
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->keterangan,
                $r->waktu_makan,
                $man,
                $r->alasan,
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

    public function r_karyawan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            // $usr = $this->session->userdata('kar_id');
            // $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $role = $this->session->userdata('role_id');
            if ($role == '30') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "kantin/r_minggu_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "kantin/r_mingguan";
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function report_kupon()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $mulai =  $this->input->post('sejak');
            $sampai = $this->input->post('sampai');
            $mulai_t = new DateTime($mulai);
            $sampai_t = new DateTime($sampai);

            $fdivisi = array();
            $divisi = $this->input->post('divisi');
            if (!empty($divisi)) {
                for ($i = 0; $i < count($divisi); $i++) {
                    array_push($fdivisi, $divisi[$i]);
                }
            } else {
                $divisi = $this->m_hris->list_dept_group();
                foreach ($divisi->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
            }

            $fdepartment = "";
            $department = $this->input->post('departement');
            if (!empty($department)) {
                $cnt = count($department);
                $fdepartment .= " and (";
                for ($i = 0; $i < $cnt; $i++) {
                    if ($cnt == 1) {
                        $fdepartment .= "d.nama_department = '$department[0]'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fdepartment .= "d.nama_department = '$department[$i]' or ";
                        } else {
                            $fdepartment .= "d.nama_department = '$department[$i]'";
                        }
                    }
                }
                $fdepartment .= ")";
            } else {
                // $fdepartment = $fdepartment;
                $department = $this->m_hris->list_department();
                $fdepartment .= " and (";
                $no = 0;
                $cnt = $department->num_rows();
                foreach ($department->result() as $d) {
                    $no = $no + 1;
                    if ($cnt == 1) {
                        $fdepartment .= "d.nama_department = '$d->nama_department'";
                    } else {
                        if ($no + 1 <= $cnt) {
                            $fdepartment .= "d.nama_department = '$d->nama_department' or ";
                        } else {
                            $fdepartment .= "d.nama_department = '$d->nama_department'";
                        }
                    }
                }
                $fdepartment .= ")";
            }


            $fbagian = "";
            $bagian = $this->input->post('bagian10');
            if (!empty($bagian)) {
                $cnt = count($bagian);
                $fbagian .= " and (";
                for ($i = 0; $i < $cnt; $i++) {
                    if ($cnt == 1) {
                        $fbagian .= "b.recid_bag = '$bagian[0]'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fbagian .= "b.recid_bag = '$bagian[$i]' or ";
                        } else {
                            $fbagian .= "b.recid_bag = '$bagian[$i]'";
                        }
                    }
                }
                $fbagian .= ")";
            } else {
                $fbagian = $fbagian;
            }


            $fkaryawan = "";
            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                // $text = "$text and b.recid_bag = '$bagian'";
                $cnt = count($karyawan);
                $fkaryawan .= " and (";
                if ($cnt == 1) {
                    $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                } else {
                    for ($i = 0; $i < $cnt; $i++) {
                        if ($i == $cnt - 1) {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                        } else {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                        }
                    }
                }
                $fkaryawan .= ")";
            } else {
                $fkaryawan = $fkaryawan;
            }

            /* $fdivisi = ["Back Office"];
    $fdepartment = "and (d.nama_department ='ADMINISTRASI & KEUANGAN')";
    $fbagian = "and (b.recid_bag ='41')";
    $karyawan = "and (k.recid_karyawan ='1189')"; */

            $data['fdivisi'] = $fdivisi;
            $data['divisi'] = $divisi;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['mulai'] = $mulai;
            $data['sampai'] = $sampai;
            // $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/rekap_kantin', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function r_bagian()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            // if ($role == '30') {
            //     $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
            //     $data['department'] = $this->m_absen->dept_admin($usr);
            //     $content = "kantin/r_minggu_adm";
            // } else {
            //     $data['dept_group'] = $this->m_hris->list_dept_group();
            //     $data['department'] = $this->m_hris->department_view();
            //     $content = "kantin/r_mingguan";
            // }
            $content = "kantin/r_bagian";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function report_kupon_bagian()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $mulai =  $this->input->post('sejak');
            $sampai = $this->input->post('sampai');

            $data['divisi'] = $this->m_hris->list_dept_group();
            $data['mulai'] = $mulai;
            $data['sampai'] = $sampai;
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }

            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/rekap_kantin_bagian', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function r_compare()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['dept_group'] = $this->m_hris->list_dept_group();
            $data['department'] = $this->m_hris->department_view();
            $content = "kantin/r_compare";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function rekap_compare_absen()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
            $sejak = $this->input->post('sejak');
            $sampai = $this->input->post('sampai');
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['menu'] = "Tidak Absen - Makan";
            $data['compare'] = $this->m_kantin->compare_absen_makan_periode($sejak, $sampai);
            $content = "kantin/rekap_compare";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content, $data);
            $this->load->view('layout/a_footer');
            echo $sampai;
        } else {
            redirect('Auth/keluar');
        }
    }

    public function r_manual()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $content = "kantin/r_manual";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function rekap_manual()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $mulai =  $this->input->post('sejak');
            $sampai = $this->input->post('sampai');
            $data['manual'] = $this->m_kantin->data_manual_periode($mulai, $sampai);
            $data['mulai'] = $mulai;
            $data['sampai'] = $sampai;
            $data['menu'] = "Report Manual Input Kantin Periode " . $mulai . " " . $sampai;
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }

            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/rekap_manual', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function monitor()
    {
        $this->load->view('layout/a_header');
        $this->load->view('kantin/monitor');
        $this->load->view('layout/footer_js');
    }

    public function monitor_baros()
    {
        $this->load->view('layout/a_header');
        $this->load->view('kantin/monitor_baros');
        $this->load->view('layout/footer_js');
    }

    public function r_makan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['dept_group'] = $this->m_hris->list_dept_group();
            $data['department'] = $this->m_hris->department_view();
            $content = "kantin/r_kupon";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function report_makan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl_awal = $this->input->post('sejak');
            $tgl_akhir2 = $this->input->post('sampai');
            $tgl_akhir = $this->input->post('sampai');
            $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir . ' +1 day'));
            $usr = $this->session->userdata('kar_id');
            $as_user = $this->session->userdata('as_user');
            if ($as_user == "CINT") {
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $menus = 'layout/menu_super';
            } else {
                $alias = $this->session->userdata('recid_login');
                $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
                $menus = 'layout/menu_kantin';
            }
            $data['karyawan'] = $this->m_kantin->data_makan_karyawan_periode($tgl_awal, $tgl_akhir2);
            $data['tamu'] = $this->m_kantin->data_makan_all_tamu_periode($tgl_awal, $tgl_akhir2);
            // $data['baros'] = $this->m_kantin->data_makan_baros_periode($tgl_awal, $tgl_akhir);
            $data['menu'] = "Report Makan Kantin Periode $tgl_awal - $tgl_akhir2";
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('kantin/report_makan_periode', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function generate_flat_industri()
    {
        $tgl = $this->input->post('tanggal');
        $cek_absen = $this->m_kantin->generate_makan_industri($tgl);   // all baros
        foreach ($cek_absen->result() as $ca) {
            $cek_dbl = $this->m_kantin->cek_double($ca->recid_karyawan, $tgl);
            if ($cek_dbl->num_rows() == 0) {
                if ($ca->tingkatan > 9) {
                    $alasan = "Mess / Pantry";
                } else {
                    $alasan = "Dibungkus";
                }
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'kategori'          => "Karyawan",
                    'recid_karyawan'    => $ca->recid_karyawan,
                    'recid_tamu'        => 0,
                    'tgl_makan'         => $tgl,
                    'manuals'            => "3",
                    'alasan'            => $alasan,
                );
                $this->m_kantin->save_makan($data);
            }
        }
        redirect("Kantin/generate_industri");
    }
}

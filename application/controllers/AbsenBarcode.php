<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('Asia/Jakarta');
class AbsenBarcode extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('m_absenbarcode', 'm_hris', 'm_kantin', 'm_absen'));
        // ini_set('max_execution_time', 600);
        ob_start(); # add this
    }

    public function jam_server()
    {
        $waktu = date('Y-m-d H:i:s');
        // echo $waktu;
        echo json_encode($waktu);
    }

    public function absen_masuk()
    {
        $this->load->view('layout/a_header2');
        // $this->load->view($menus, $data);
        $this->load->view('absen/absen_barcode/scan_masuk');
        $this->load->view('layout/footer_js');
    }

    public function absen_masuk_baros()
    {
        $this->load->view('layout/a_header2');
        // $this->load->view($menus, $data);
        $this->load->view('absen/absen_barcode/scan_masuk_baros');
        $this->load->view('layout/footer_js');
    }

    public function monitor_masuk()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_masuk');
        $this->load->view('layout/footer_js');
    }


    public function monitor_masuk_baros()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_masuk_baros');
        $this->load->view('layout/footer_js');
    }


    public function data_masuk()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_absenbarcode->monitor_masuk($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        
        foreach ($query->result() as $r) {
            $data[] = array(
                // $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->jam_masuk,
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

    public function data_masuk_baros()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_absenbarcode->monitor_masuk_baros($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $data[] = array(
                // $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                // $r->indeks_hr,
                $r->jam_masuk,
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

    public function simpan_masuk()
    {
        $data = array();
        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        $waktu_masuk = date('H:i:s');
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                //cek double scan
                $day = date('D', strtotime($tgl));
                if ($day == 'Sat' or $day == 'Sun') {
                    $cek_gh = $this->m_absen->gh_by_date($tgl);
                    if ($cek_gh->num_rows() == 0) {
                        //lembur
                        $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                        if ($shift->num_rows() > 0) {
                            //shift
                            foreach ($shift->result() as $s) {
                                $jam_masuk = $s->jam_in;
                                $status = $s->recid_jenisabsen;
                                $tmp_status = $s->recid_jenisabsen;
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            }
                        } else {
                            $status = 28;
                        }
                        $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                        if ($double->num_rows() == 0) {
                            $lembur = $this->m_absen->jenis_absen_id($status);
                            foreach ($lembur->result() as $s) {
                                $jam_masuk = $s->jam_in;
                                $status = $s->recid_jenisabsen;
                                $tmp_status = $s->recid_jenisabsen;
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            }
                            if ($waktu_masuk > $jam_masuk) {
                                $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                                $jam   = floor($diff / (60 * 60));
                                $menit = $diff - ($jam * (60 * 60));

                                if ($jam >= 2 and $menit >= 1) {
                                    $status = 10;
                                    $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                    if ($cek->num_rows == 0) {
                                        $data_absen = array(
                                            'recid_karyawan'    => $recid_karyawan,
                                            'tanggal'        => $tgl,
                                            'jenis_absen'    => $status,
                                            'keterangan'    => "terlambat",
                                            'validasi_cuti'  => "0",
                                            'crt_by'        => $recid_karyawan,
                                            'crt_date'        => date('Y-m-d h:i:s'),
                                        );
                                        $this->m_absen->save_absensi($data_absen);
                                    }
                                } else {
                                    // status = normal kehadiran dengan izin terlambat
                                    $status = $status;
                                    $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                    if ($dbl->num_rows() == 0) {
                                        $data_izin = array(
                                            'crt_date'          => date('Y-m-d H:i:s'),
                                            'crt_by'            => $recid_karyawan,
                                            'tgl_izin'          => date('Y-m-d'),
                                            'recid_karyawan'    => $recid_karyawan,
                                            'jenis'             => "Terlambat",
                                            'jam_in'            => date('H:i:s'),
                                            'keterangan'        => ''
                                        );
                                        $this->m_absen->save_data('izin', $data_izin);
                                        // echo "berhasil save izin";
                                    }
                                }
                                $validasi = '1';
                            } else {
                                $validasi = '0';
                            }

                            $data = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'           => date('Y-m-d'),
                                'tgl_masuk'          => date('Y-m-d'),
                                'jam_masuk'         => date('H:i:s'),
                                'status'            => $status,
                                'lokasi_masuk'      => 'Industri',
                                'tmp_status'        => $tmp_status,
                                'perlu_validasi'    => $validasi,
                                'flag_premi'         => '',
                            );
                            $this->m_absenbarcode->save_absen_masuk($data);
                            $hasil = "Ok";
                        } else {
                            $hasil = "Anda Sudah Absen";
                        }
                    } else {
                        //ganti hari
                        $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                        if ($double->num_rows() == 0) {
                            $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                            if ($shift->num_rows() > 0) {
                                //shift
                                foreach ($shift->result() as $s) {
                                    $jam_masuk = $s->jam_in;
                                    $status = $s->recid_jenisabsen;
                                    $tmp_status = $s->recid_jenisabsen;
                                    $jam_masuk = date_create($jam_masuk);
                                    date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                    $jam_masuk = date_format($jam_masuk, 'H:i:s');
                                }
                            } else {
                                $cek_puasa = $this->m_absenbarcode->cek_puasa();
                                if ($cek_puasa->num_rows() > 0) {
                                    $jam_ker  = $this->m_absen->jenis_absen_id(29);
                                } else {
                                    $jam_ker  = $this->m_absen->jenis_absen_id(1);
                                }
                                foreach ($jam_ker->result() as $jk) {
                                    $jam_masuk = $jk->jam_in;
                                    $status  = $jk->recid_jenisabsen;
                                }
                                // non shift
                                // $jam_masuk = "07:00:00";
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                                $tmp_status = $status;
                            }

                            if ($waktu_masuk > $jam_masuk) {
                                $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                                $jam   = floor($diff / (60 * 60));
                                $menit = $diff - ($jam * (60 * 60));

                                if ($jam >= 2 and $menit >= 1) {
                                    $status = 10;
                                    $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                    if ($cek->num_rows == 0) {
                                        $data_absen = array(
                                            'recid_karyawan'    => $recid_karyawan,
                                            'tanggal'        => $tgl,
                                            'jenis_absen'    => $status,
                                            'keterangan'    => "terlambat",
                                            'validasi_cuti'  => "0",
                                            'crt_by'        => $recid_karyawan,
                                            'crt_date'        => date('Y-m-d h:i:s'),
                                        );
                                        $this->m_absen->save_absensi($data_absen);
                                    }
                                } else {
                                    // status = normal kehadiran dengan izin terlambat
                                    $status = $status;
                                    $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                    if ($dbl->num_rows() == 0) {
                                        $data_izin = array(
                                            'crt_date'          => date('Y-m-d H:i:s'),
                                            'crt_by'            => $recid_karyawan,
                                            'tgl_izin'          => date('Y-m-d'),
                                            'recid_karyawan'    => $recid_karyawan,
                                            'jenis'             => "Terlambat",
                                            'jam_in'            => date('H:i:s'),
                                            'keterangan'        => ''
                                        );
                                        $this->m_absen->save_data('izin', $data_izin);
                                        // echo "berhasil save izin";
                                    }
                                }
                                $validasi = '1';
                            } else {
                                $validasi = '0';
                            }

                            $data = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'           => date('Y-m-d'),
                                'tgl_masuk'          => date('Y-m-d'),
                                'jam_masuk'         => date('H:i:s'),
                                'status'            => $status,
                                'lokasi_masuk'      => 'Industri',
                                'tmp_status'        => $tmp_status,
                                'perlu_validasi'    => $validasi,
                            );
                            $this->m_absenbarcode->save_absen_masuk($data);
                            $hasil = "Ok";
                        } else {
                            $hasil = "Anda Sudah Absen";
                        }
                    }
                } else {
                    // bukan sabtu minggu
                    $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                    if ($double->num_rows() == 0) {
                        $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                        if ($shift->num_rows() > 0) {
                            //shift
                            foreach ($shift->result() as $s) {
                                $jam_masuk = $s->jam_in;
                                $status = $s->recid_jenisabsen;
                                $tmp_status = $s->recid_jenisabsen;
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            }
                        } else {
                            // non shift
                            $cek_puasa = $this->m_absenbarcode->cek_puasa();
                            if ($cek_puasa->num_rows() > 0) {
                                $jam_ker  = $this->m_absen->jenis_absen_id(29);
                            } else {
                                $jam_ker  = $this->m_absen->jenis_absen_id(1);
                            }
                            foreach ($jam_ker->result() as $jk) {
                                $jam_masuk = $jk->jam_in;
                                $status = $jk->recid_jenisabsen;
                            }
                            // $jam_masuk = "07:00:00";
                            $jam_masuk = date_create($jam_masuk);
                            date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                            $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            $tmp_status = $status;
                        }

                        if ($waktu_masuk > $jam_masuk) {
                            $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                            $jam   = floor($diff / (60 * 60));
                            $menit = $diff - ($jam * (60 * 60));

                            if ($jam >= 2 and $menit >= 1) {
                                $status = 10;
                                $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                if ($cek->num_rows == 0) {
                                    $data_absen = array(
                                        'recid_karyawan'    => $recid_karyawan,
                                        'tanggal'        => $tgl,
                                        'jenis_absen'    => $status,
                                        'keterangan'    => "terlambat",
                                        'validasi_cuti'  => "0",
                                        'crt_by'        => $recid_karyawan,
                                        'crt_date'        => date('Y-m-d h:i:s'),
                                    );
                                    $this->m_absen->save_absensi($data_absen);
                                }
                            } else {
                                // status = normal kehadiran dengan izin terlambat
                                $status = $status;
                                $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                if ($dbl->num_rows() == 0) {
                                    $data_izin = array(
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'crt_by'            => $recid_karyawan,
                                        'tgl_izin'          => date('Y-m-d'),
                                        'recid_karyawan'    => $recid_karyawan,
                                        'jenis'             => "Terlambat",
                                        'jam_in'            => date('H:i:s'),
                                        'keterangan'        => ''
                                    );
                                    $this->m_absen->save_data('izin', $data_izin);
                                    // echo "berhasil save izin";
                                }
                            }
                            $validasi = '1';
                        } else {
                            $validasi = '0';
                        }

                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => date('Y-m-d'),
                            'tgl_masuk'          => date('Y-m-d'),
                            'jam_masuk'         => date('H:i:s'),
                            'status'            => $status,
                            'lokasi_masuk'      => 'Industri',
                            'tmp_status'        => $tmp_status,
                            'perlu_validasi'    => $validasi,
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                        $hasil = "Ok";
                    } else {
                        $hasil = "Anda Sudah Absen";
                    }
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        echo json_encode($hasil);
    }

    public function simpan_masuk_baros()
    {
        $data = array();
        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        // $nik = '20180108416';
        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        $waktu_masuk = date('H:i:s');
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                //cek double scan
                $day = date('D', strtotime($tgl));
                if ($day == 'Sat' or $day == 'Sun') {
                    $cek_gh = $this->m_absen->gh_by_date($tgl);
                    if ($cek_gh->num_rows() == 0) {
                        //lembur
                        $status = 28;
                        $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                        if ($double->num_rows() == 0) {
                            $lembur = $this->m_absen->jenis_absen_id($status);
                            foreach ($lembur->result() as $s) {
                                $jam_masuk = $s->jam_in;
                                $status = $s->recid_jenisabsen;
                                $tmp_status = $s->recid_jenisabsen;
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            }
                            if ($waktu_masuk > $jam_masuk) {
                                $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                                $jam   = floor($diff / (60 * 60));
                                $menit = $diff - ($jam * (60 * 60));

                                if ($jam >= 2 and $menit >= 1) {
                                    $status = 10;
                                    $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                    if ($cek->num_rows == 0) {
                                        $data_absen = array(
                                            'recid_karyawan'    => $recid_karyawan,
                                            'tanggal'        => $tgl,
                                            'jenis_absen'    => $status,
                                            'keterangan'    => "terlambat",
                                            'validasi_cuti'  => "0",
                                            'crt_by'        => $recid_karyawan,
                                            'crt_date'        => date('Y-m-d h:i:s'),
                                        );
                                        $this->m_absen->save_absensi($data_absen);
                                    }
                                } else {
                                    // status = normal kehadiran dengan izin terlambat
                                    $status = $status;
                                    $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                    if ($dbl->num_rows() == 0) {
                                        $data_izin = array(
                                            'crt_date'          => date('Y-m-d H:i:s'),
                                            'crt_by'            => $recid_karyawan,
                                            'tgl_izin'          => date('Y-m-d'),
                                            'recid_karyawan'    => $recid_karyawan,
                                            'jenis'             => "Terlambat",
                                            'jam_in'            => date('H:i:s'),
                                            'keterangan'        => ''
                                        );
                                        $this->m_absen->save_data('izin', $data_izin);
                                        // echo "berhasil save izin";
                                    }
                                }
                                $validasi = '1';
                            } else {
                                $validasi = '0';
                            }

                            $data = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'           => date('Y-m-d'),
                                'tgl_masuk'          => date('Y-m-d'),
                                'jam_masuk'         => date('H:i:s'),
                                'status'            => $status,
                                'lokasi_masuk'      => 'Baros',
                                'tmp_status'        => $tmp_status,
                                'perlu_validasi'    => $validasi,
                            );
                            $this->m_absenbarcode->save_absen_masuk($data);
                            $hasil = "Ok";
                        } else {
                            $hasil = "Anda Sudah Absen";
                        }
                    } else {
                        //ganti hari
                        $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                        if ($double->num_rows() == 0) {
                            $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                            if ($shift->num_rows() > 0) {
                                //shift
                                foreach ($shift->result() as $s) {
                                    $jam_masuk = $s->jam_in;
                                    $status = $s->recid_jenisabsen;
                                    $tmp_status = $s->recid_jenisabsen;
                                    $jam_masuk = date_create($jam_masuk);
                                    date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                    $jam_masuk = date_format($jam_masuk, 'H:i:s');
                                }
                            } else {
                                // non shift
                                // $jam_masuk = "07:00:00";
                                $cek_puasa = $this->m_absenbarcode->cek_puasa();
                                if ($cek_puasa->num_rows() > 0) {
                                    $jam_ker  = $this->m_absen->jenis_absen_id(29);
                                } else {
                                    $jam_ker  = $this->m_absen->jenis_absen_id(1);
                                }
                                foreach ($jam_ker->result() as $jk) {
                                    $jam_masuk = $jk->jam_in;
                                    $status = $jk->recid_jenisabsen;
                                }
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                                $tmp_status = $status;
                            }

                            if ($waktu_masuk > $jam_masuk) {
                                $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                                $jam   = floor($diff / (60 * 60));
                                $menit = $diff - ($jam * (60 * 60));

                                if ($jam >= 2 and $menit >= 1) {
                                    $status = 10;
                                    $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                    if ($cek->num_rows == 0) {
                                        $data_absen = array(
                                            'recid_karyawan'    => $recid_karyawan,
                                            'tanggal'        => $tgl,
                                            'jenis_absen'    => $status,
                                            'keterangan'    => "terlambat",
                                            'validasi_cuti'  => "0",
                                            'crt_by'        => $recid_karyawan,
                                            'crt_date'        => date('Y-m-d h:i:s'),
                                        );
                                        $this->m_absen->save_absensi($data_absen);
                                    }
                                } else {
                                    // status = normal kehadiran dengan izin terlambat
                                    $status = $status;
                                    $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                    if ($dbl->num_rows() == 0) {
                                        $data_izin = array(
                                            'crt_date'          => date('Y-m-d H:i:s'),
                                            'crt_by'            => $recid_karyawan,
                                            'tgl_izin'          => date('Y-m-d'),
                                            'recid_karyawan'    => $recid_karyawan,
                                            'jenis'             => "Terlambat",
                                            'jam_in'            => date('H:i:s'),
                                            'keterangan'        => ''
                                        );
                                        $this->m_absen->save_data('izin', $data_izin);
                                        // echo "berhasil save izin";
                                    }
                                }
                                $validasi = '1';
                            } else {
                                $validasi = '0';
                            }

                            $data = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'           => date('Y-m-d'),
                                'tgl_masuk'          => date('Y-m-d'),
                                'jam_masuk'         => date('H:i:s'),
                                'status'            => $status,
                                'lokasi_masuk'      => 'Baros',
                                'tmp_status'        => $tmp_status,
                                'perlu_validasi'    => $validasi,
                            );
                            $this->m_absenbarcode->save_absen_masuk($data);
                            $hasil = "Ok";
                        } else {
                            $hasil = "Anda Sudah Absen";
                        }
                    }
                } else {
                    // bukan sabtu minggu
                    $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
                    if ($double->num_rows() == 0) {
                        $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                        if ($shift->num_rows() > 0) {
                            //shift
                            foreach ($shift->result() as $s) {
                                $jam_masuk = $s->jam_in;
                                $status = $s->recid_jenisabsen;
                                $tmp_status = $s->recid_jenisabsen;
                                $jam_masuk = date_create($jam_masuk);
                                date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                                $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            }
                        } else {
                            // non shift
                            // $jam_masuk = "07:00:00";
                            $cek_puasa = $this->m_absenbarcode->cek_puasa();
                            if ($cek_puasa->num_rows() > 0) {
                                $jam_ker  = $this->m_absen->jenis_absen_id(29);
                            } else {
                                $jam_ker  = $this->m_absen->jenis_absen_id(1);
                            }
                            foreach ($jam_ker->result() as $jk) {
                                $jam_masuk = $jk->jam_in;
                                $status = $jk->recid_jenisabsen;
                            }
                            $jam_masuk = date_create($jam_masuk);
                            date_add($jam_masuk, date_interval_create_from_date_string('1 minutes'));
                            $jam_masuk = date_format($jam_masuk, 'H:i:s');
                            $tmp_status = $status;
                        }

                        if ($waktu_masuk > $jam_masuk) {
                            $diff = strtotime($waktu_masuk) - strtotime($jam_masuk);
                            $jam   = floor($diff / (60 * 60));
                            $menit = $diff - ($jam * (60 * 60));

                            if ($jam >= 2 and $menit >= 1) {
                                $status = 10;
                                $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl);
                                if ($cek->num_rows == 0) {
                                    $data_absen = array(
                                        'recid_karyawan'    => $recid_karyawan,
                                        'tanggal'        => $tgl,
                                        'jenis_absen'    => $status,
                                        'keterangan'    => "terlambat",
                                        'validasi_cuti'  => "0",
                                        'crt_by'        => $recid_karyawan,
                                        'crt_date'        => date('Y-m-d h:i:s'),
                                    );
                                    $this->m_absen->save_absensi($data_absen);
                                }
                            } else {
                                // status = normal kehadiran dengan izin terlambat
                                $status = $status;
                                $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Terlambat');
                                if ($dbl->num_rows() == 0) {
                                    $data_izin = array(
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'crt_by'            => $recid_karyawan,
                                        'tgl_izin'          => date('Y-m-d'),
                                        'recid_karyawan'    => $recid_karyawan,
                                        'jenis'             => "Terlambat",
                                        'jam_in'            => date('H:i:s'),
                                        'keterangan'        => ''
                                    );
                                    $this->m_absen->save_data('izin', $data_izin);
                                    // echo "berhasil save izin";
                                }
                            }
                            $validasi = '1';
                        } else {
                            $validasi = '0';
                        }

                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => date('Y-m-d'),
                            'tgl_masuk'          => date('Y-m-d'),
                            'jam_masuk'         => date('H:i:s'),
                            'status'            => $status,
                            'lokasi_masuk'      => 'Baros',
                            'tmp_status'        => $tmp_status,
                            'perlu_validasi'    => $validasi,
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                        $hasil = "Ok";
                    } else {
                        $hasil = "Anda Sudah Absen";
                    }
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        echo json_encode($hasil);
    }

    public function absen_pulang()
    {
        $this->load->view('layout/a_header2');
        // $this->load->view($menus, $data);
        $this->load->view('absen/absen_barcode/scan_pulang');
        $this->load->view('layout/footer_js');
    }

    public function absen_pulang_baros()
    {
        $this->load->view('layout/a_header2');
        // $this->load->view($menus, $data);
        $this->load->view('absen/absen_barcode/scan_pulang_baros');
        $this->load->view('layout/footer_js');
    }

    public function simpan_pulang()
    {
        $data = array();
        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        // $nik = "20091113164";
        $absen_pulang = date('H:i:s');
        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
            }
            // echo $tgl;
            //cek double adakah absen masuk hari ini?
            $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
            //kalo g ada
            if ($double->num_rows() == 0) {
                // kalo blm ada record absen hari ini, cek shift kemaren
                $tgl_kemaren = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
                // $shift_malam = $this->m_absenbarcode->cek_shift_malam_karyawan($recid_karyawan, $tgl_kemaren);
                $shift_malam = $this->m_absenbarcode->scan_masuk_malam($recid_karyawan, $tgl_kemaren);
                // kalo ada shift malem di hari sebelumnya
                if ($shift_malam->num_rows() > 0) {
                    // echo "ada shift malam sebelumnya<br>";
                    foreach ($shift_malam->result() as $s) {
                        $cek_shift_malam = $this->m_absenbarcode->cek_shift_malam_karyawan($recid_karyawan, $tgl_kemaren);
                        if ($cek_shift_malam->num_rows() == 0) {
                            $validasi = '1';
                            $jam_masuk = $s->jam_masuk;
                            $status = $s->status;
                            $tmp_status = $s->tmp_status;
                        } else {
                            foreach ($cek_shift_malam->result() as $csm) {
                                $jam_masuk = $csm->jam_in;
                                $status = $csm->recid_jenisabsen;
                                $tmp_status = $csm->recid_jenisabsen;
                                $validasi = '0';
                            }
                        }
                    }
                    // echo "update jam pulang hari kemaren<br>";
                    $double_kemaren = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_kemaren);
                    if ($double_kemaren->num_rows() == 0) {
                        // echo "tidak absen masuk kemaren <br>";
                        // echo "save absen masuk jam pulang saja<br>";
                        $validasi = '1';
                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => date('Y-m-d'),
                            'tgl_masuk'         => $tgl_kemaren,
                            'jam_masuk'         => "00:00:00",
                            'jam_keluar'         => date('H:i:s'),
                            'status'            => $status,
                            'perlu_validasi'    => $validasi,
                            'lokasi_pulang'     => 'Industri',
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                    } else {
                        // echo "update jam pulang hari kemaren<br>";
                        foreach ($double_kemaren->result() as $dk) {
                            $jam_keluar = $dk->jam_keluar;
                            $recid_absen = $dk->recid_absen;
                            $jam_masuk = $dk->jam_masuk;
                            $status = $dk->status;
                        }
                        if (is_null($jam_keluar)) {
                            //    echo  "jam keluar blm ada isinya, update jam<br>";
                            $jam_kerja_harusnya = $this->m_absen->jenis_absen_id($status);
                            foreach ($jam_kerja_harusnya->result() as $jkh) {
                                $jadwal_keluar = $jkh->jam_out;
                            }
                            $jam1 = $tgl_kemaren . " " . $jam_masuk;
                            $jam1 = date($jam1);
                            // jam absen
                            $jam2 = $tgl . " " . $absen_pulang;
                            $jam2 = date($jam2);
                            $diff = strtotime($jam2) - strtotime($jam1);
                            $jam   = floor($diff / (60 * 60));
                            $menit = $diff - ($jam * (60 * 60));
                            // echo $jam."<br>";
                            // ideal jam pulang
                            $jam3 = $tgl . " " . $jam_keluar;
                            $jam3 = date($jam3);
                            if ($jam2 < $jam3) {
                                // echo "pulang cepat <br>";
                                // pulang cepat
                                if ($jam < 4) {
                                    // echo "kurang 4 jam <br>";
                                    // kerja < 4 jam
                                    // status = cuti or p1
                                    $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                                    //jika masih ada cuti
                                    if ($cek_cuti->num_rows() > 0) {
                                        $status = 3;    //cuti
                                    } else {
                                        $status = 5;   //p1
                                    }
                                    // echo "status : ".$status;
                                    //update absensi
                                    $data_absen = array(
                                        'recid_karyawan'    => $recid_karyawan,
                                        'tanggal'        => $tgl,
                                        'jenis_absen'    => $status,
                                        'keterangan'    => "pulang",
                                        'validasi_cuti'  => "0",
                                        'crt_by'        => $recid_karyawan,
                                        'crt_date'        => date('Y-m-d h:i:s'),
                                    );
                                    $this->m_absen->save_absensi($data_absen);
                                    // echo "save absen jadi cuti<br>";
                                }
                                $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                                if ($dbl->num_rows() == 0) {
                                    $data_izin = array(
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'crt_by'            => $recid_karyawan,
                                        'tgl_izin'          => date('Y-m-d'),
                                        'recid_karyawan'    => $recid_karyawan,
                                        'jenis'             => "Pulang",
                                        'jam_out'            => date('H:i:s'),
                                        'keterangan'        => '',
                                        'perlu_validasi'    => '1'
                                    );
                                    $this->m_absen->save_data('izin', $data_izin);
                                }
                                // echo "save izin pulang cepat<br>";
                                $data = array(
                                    'mdf_date'          => date('Y-m-d H:i:s'),
                                    'mdf_by'            => $recid_karyawan,
                                    'recid_karyawan'    => $recid_karyawan,
                                    'status'            => $status,
                                    'tgl_pulang'        => date('Y-m-d'),
                                    'jam_keluar'        => $absen_pulang,
                                    'lokasi_pulang'     => 'Industri',
                                    'perlu_validasi'     => $validasi,
                                );
                                $this->m_absenbarcode->update_hadir($data, $recid_absen);
                                // echo "update jam pulang di kehadiran<br>";
                                $hasil = "Ok";
                            } else {
                                // pulang normal
                                // echo "pulang normal<br>";
                                $data = array(
                                    'mdf_date'          => date('Y-m-d H:i:s'),
                                    'mdf_by'            => $recid_karyawan,
                                    'recid_karyawan'    => $recid_karyawan,
                                    'status'            => $status,
                                    'tgl_pulang'        => date('Y-m-d'),
                                    'jam_keluar'        => $absen_pulang,
                                    'lokasi_pulang'     => 'Industri',
                                );
                                $this->m_absenbarcode->update_hadir($data, $recid_absen);
                                // echo "update jam pulang di kehadiran<br>";
                                $hasil = "Ok";
                            }
                        } else {
                            $hasil = "Anda Sudah Absen Pulang";
                            // echo $hasil;
                        }
                    }
                } else {
                    // echo "tidak ada shift malam blm absen masuk<br>";
                    $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                    if ($shift->num_rows() > 0) {
                        //shift
                        // echo "blm absen masuk <br> cek shift ambil jam masuknya<br>";
                        foreach ($shift->result() as $s) {
                            $jam_masuk = $s->jam_in;
                            $jam_keluar = $s->jam_out;
                            $status = $s->recid_jenisabsen;
                            $tmp_status = $s->recid_jenisabsen;
                        }
                    } else {
                        // non shift
                        // echo "jika tidak shift jam masuk jam 07:00<br>";
                        $cek_puasa = $this->m_absenbarcode->cek_puasa();
                        if ($cek_puasa->num_rows() > 0) {
                            $jam_ker  = $this->m_absen->jenis_absen_id(29);
                        } else {
                            $jam_ker  = $this->m_absen->jenis_absen_id(1);
                        }
                        foreach ($jam_ker->result() as $jk) {
                            $jam_masuk = $jk->jam_in;
                            $jam_keluar = $jk->jam_out;
                            $status = $jk->recid_jenisabsen;
                        }
                        // $jam_masuk = "07:00:00";
                        // $jam_keluar = "16:00:00";
                        $tmp_status = $status;
                    }

                    if (date('H:i:s') > $jam_masuk) {
                        // echo "jika pas scan lebih bedar dari ".$jam_masuk." maka perlu validasi blm scan masuk<br>";
                        $validasi = '1';
                    } else {
                        $validasi = '0';
                    }

                    $absen_pulang = date('H:i:s');
                    $diff = strtotime($absen_pulang) - strtotime($jam_masuk);
                    $jam   = floor($diff / (60 * 60));
                    $menit = $diff - ($jam * (60 * 60));
                    // echo $jam;
                    if ($absen_pulang < $jam_keluar) {
                        // echo "pulang cepat <br>";
                        // pulang cepat
                        if ($jam < 4) {
                            // echo "kurang 4 jam <br>";
                            // kerja < 4 jam
                            // status = cuti or p1
                            $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                            //jika masih ada cuti
                            if ($cek_cuti->num_rows() > 0) {
                                $status = 3;    //cuti
                            } else {
                                $status = 5;   //p1
                            }
                            // echo "status : ".$status;
                            //update absensi
                            $data_absen = array(
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'        => $tgl,
                                'jenis_absen'    => $status,
                                'keterangan'    => "pulang",
                                'validasi_cuti'  => "0",
                                'crt_by'        => $recid_karyawan,
                                'crt_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->save_absensi($data_absen);
                            // echo "save absen jadi cuti<br>";
                        }
                        $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                        if ($dbl->num_rows() == 0) {
                            $data_izin = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'tgl_izin'          => date('Y-m-d'),
                                'recid_karyawan'    => $recid_karyawan,
                                'jenis'             => "Pulang",
                                'jam_out'            => date('H:i:s'),
                                'keterangan'        => '',
                                'perlu_validasi'    => '1'
                            );
                            $this->m_absen->save_data('izin', $data_izin);
                        }
                        // echo "save izin pulang cepat<br>";
                    }

                    // echo "save absen masuk jam pulang saja<br>";
                    $data = array(
                        'crt_date'          => date('Y-m-d H:i:s'),
                        'crt_by'            => $recid_karyawan,
                        'recid_karyawan'    => $recid_karyawan,
                        'tanggal'           => date('Y-m-d'),
                        'tgl_masuk'         => date('Y-m-d'),
                        'jam_masuk'         => "00:00:00",
                        'tgl_pulang'         => date('Y-m-d'),
                        'jam_keluar'         => date('H:i:s'),
                        'status'            => $status,
                        'perlu_validasi'    => $validasi,
                        'lokasi_pulang'     => 'Industri',
                    );
                    $this->m_absenbarcode->save_absen_masuk($data);
                    $hasil = "Ok";
                }
            } else {
                //    echo "absen pulang normal";
                foreach ($double->result() as $d) {
                    $recid_absen = $d->recid_absen;
                    $jam_masuk = $d->jam_masuk;
                    $jam_keluar = $d->jam_keluar;
                    $status = $d->status;
                }
                if (is_null($jam_keluar)) {
                    // echo "status absen = ".$status."<br>";
                    $jam_kerja_harusnya = $this->m_absen->jenis_absen_id($status);
                    foreach ($jam_kerja_harusnya->result() as $jkh) {
                        $jam_keluar = $jkh->jam_out;
                    }

                    $absen_pulang = date('H:i:s');
                    $diff = strtotime($absen_pulang) - strtotime($jam_masuk);
                    $jam   = floor($diff / (60 * 60));
                    $menit = $diff - ($jam * (60 * 60));
                    // echo "jmljam: ".$jam;
                    // echo "scan pulang : ".$absen_pulang." -  jam pulang : ".$jam_keluar . "<br>";
                    if ($absen_pulang < $jam_keluar) {
                        // echo "pulang cepat <br>";
                        // pulang cepat
                        if ($jam < 4) {
                            // echo "kurang 4 jam <br>";
                            // kerja < 4 jam
                            // status = cuti or p1
                            $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                            //jika masih ada cuti
                            if ($cek_cuti->num_rows() > 0) {
                                $status = 3;    //cuti
                            } else {
                                $status = 5;   //p1
                            }
                            // echo "status : ".$status;
                            //update absensi
                            $data_absen = array(
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'        => $tgl,
                                'jenis_absen'    => $status,
                                'keterangan'    => "pulang",
                                'validasi_cuti'  => "0",
                                'crt_by'        => $recid_karyawan,
                                'crt_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->save_absensi($data_absen);
                            // echo "save absen jadi cuti<br>";
                        }
                        $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                        if ($dbl->num_rows() == 0) {
                            $data_izin = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'tgl_izin'          => date('Y-m-d'),
                                'recid_karyawan'    => $recid_karyawan,
                                'jenis'             => "Pulang",
                                'jam_out'            => date('H:i:s'),
                                'keterangan'        => '',
                                'perlu_validasi'    => '1'
                            );
                            $this->m_absen->save_data('izin', $data_izin);
                        }
                        // echo "save izin pulang cepat<br>";
                        $data = array(
                            'mdf_date'          => date('Y-m-d H:i:s'),
                            'mdf_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'status'            => $status,
                            'tgl_pulang'        => date('Y-m-d'),
                            'jam_keluar'        => $absen_pulang,
                            'lokasi_pulang'     => 'Industri',
                        );
                        $this->m_absenbarcode->update_hadir($data, $recid_absen);
                        // echo "update jam pulang di kehadiran<br>";
                        $hasil = "Ok";
                    } else {
                        // pulang normal
                        // echo "pulang normal<br>";
                        $data = array(
                            'mdf_date'          => date('Y-m-d H:i:s'),
                            'mdf_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'status'            => $status,
                            'tgl_pulang'        => date('Y-m-d'),
                            'jam_keluar'        => $absen_pulang,
                            'lokasi_pulang'     => 'Industri',
                        );
                        $this->m_absenbarcode->update_hadir($data, $recid_absen);
                        // echo "update jam pulang di kehadiran<br>";
                        $hasil = "Ok";
                    }
                } else {
                    $hasil = "Anda Sudah Absen Pulang";
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        // echo $hasil;
        echo json_encode($hasil);
    }

    public function simpan_pulang_baros()
    {
        $data = array();
        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        // $nik = "20091113164";
        $absen_pulang = date('H:i:s');
        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
            }
            // echo $tgl;
            //cek double adakah absen masuk hari ini?
            $double = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl);
            //kalo g ada
            if ($double->num_rows() == 0) {
                // kalo blm ada record absen hari ini, cek shift kemaren
                $tgl_kemaren = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
                // $shift_malam = $this->m_absenbarcode->cek_shift_malam_karyawan($recid_karyawan, $tgl_kemaren);
                $shift_malam = $this->m_absenbarcode->scan_masuk_malam($recid_karyawan, $tgl_kemaren);
                // kalo ada shift malem di hari sebelumnya
                if ($shift_malam->num_rows() > 0) {
                    // echo "ada shift malam sebelumnya<br>";
                    foreach ($shift_malam->result() as $s) {
                        $cek_shift_malam = $this->m_absenbarcode->cek_shift_malam_karyawan($recid_karyawan, $tgl_kemaren);
                        if ($cek_shift_malam->num_rows() == 0) {
                            $validasi = '1';
                            $jam_masuk = $s->jam_masuk;
                            $status = $s->status;
                            $tmp_status = $s->tmp_status;
                        } else {
                            foreach ($cek_shift_malam->result() as $csm) {
                                $jam_masuk = $csm->jam_in;
                                $status = $csm->recid_jenisabsen;
                                $tmp_status = $csm->recid_jenisabsen;
                                $validasi = '0';
                            }
                        }
                    }
                    // echo "update jam pulang hari kemaren<br>";
                    $double_kemaren = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_kemaren);
                    if ($double_kemaren->num_rows() == 0) {
                        // echo "tidak absen masuk kemaren <br>";
                        // echo "save absen masuk jam pulang saja<br>";
                        $validasi = '1';
                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => date('Y-m-d'),
                            'tgl_masuk'         => $tgl_kemaren,
                            'jam_masuk'         => "00:00:00",
                            'jam_keluar'         => date('H:i:s'),
                            'status'            => $status,
                            'perlu_validasi'    => $validasi,
                            'lokasi_pulang'     => 'Baros',
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                    } else {
                        // echo "update jam pulang hari kemaren<br>";
                        foreach ($double_kemaren->result() as $dk) {
                            $jam_keluar = $dk->jam_keluar;
                            $recid_absen = $dk->recid_absen;
                            $jam_masuk = $dk->jam_masuk;
                            $status = $dk->status;
                        }
                        if (is_null($jam_keluar)) {
                            //    echo  "jam keluar blm ada isinya, update jam<br>";
                            $jam_kerja_harusnya = $this->m_absen->jenis_absen_id($status);
                            foreach ($jam_kerja_harusnya->result() as $jkh) {
                                $jadwal_keluar = $jkh->jam_out;
                            }
                            $jam1 = $tgl_kemaren . " " . $jam_masuk;
                            $jam1 = date($jam1);
                            // jam absen
                            $jam2 = $tgl . " " . $absen_pulang;
                            $jam2 = date($jam2);
                            $diff = strtotime($jam2) - strtotime($jam1);
                            $jam   = floor($diff / (60 * 60));
                            $menit = $diff - ($jam * (60 * 60));
                            // echo $jam."<br>";
                            // ideal jam pulang
                            $jam3 = $tgl . " " . $jam_keluar;
                            $jam3 = date($jam3);
                            if ($jam2 < $jam3) {
                                // echo "pulang cepat <br>";
                                // pulang cepat
                                if ($jam < 4) {
                                    // echo "kurang 4 jam <br>";
                                    // kerja < 4 jam
                                    // status = cuti or p1
                                    $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                                    //jika masih ada cuti
                                    if ($cek_cuti->num_rows() > 0) {
                                        $status = 3;    //cuti
                                    } else {
                                        $status = 5;   //p1
                                    }
                                    // echo "status : ".$status;
                                    //update absensi
                                    $data_absen = array(
                                        'recid_karyawan'    => $recid_karyawan,
                                        'tanggal'        => $tgl,
                                        'jenis_absen'    => $status,
                                        'keterangan'    => "Pulang",
                                        'validasi_cuti'  => "0",
                                        'crt_by'        => $recid_karyawan,
                                        'crt_date'        => date('Y-m-d h:i:s'),
                                    );
                                    $this->m_absen->save_absensi($data_absen);
                                    // echo "save absen jadi cuti<br>";
                                }
                                $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                                if ($dbl->num_rows() == 0) {
                                    $data_izin = array(
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'crt_by'            => $recid_karyawan,
                                        'tgl_izin'          => date('Y-m-d'),
                                        'recid_karyawan'    => $recid_karyawan,
                                        'jenis'             => "Pulang",
                                        'jam_out'            => date('H:i:s'),
                                        'keterangan'        => '',
                                        'perlu_validasi'    => '1'
                                    );
                                    $this->m_absen->save_data('izin', $data_izin);
                                }
                                // echo "save izin pulang cepat<br>";
                                $data = array(
                                    'mdf_date'          => date('Y-m-d H:i:s'),
                                    'mdf_by'            => $recid_karyawan,
                                    'recid_karyawan'    => $recid_karyawan,
                                    'status'            => $status,
                                    'tgl_pulang'        => date('Y-m-d'),
                                    'jam_keluar'        => $absen_pulang,
                                    'lokasi_pulang'     => 'Baros',
                                    'perlu_validasi'     => $validasi,
                                );
                                $this->m_absenbarcode->update_hadir($data, $recid_absen);
                                // echo "update jam pulang di kehadiran<br>";
                                $hasil = "Ok";
                            } else {
                                // pulang normal
                                // echo "pulang normal<br>";
                                $data = array(
                                    'mdf_date'          => date('Y-m-d H:i:s'),
                                    'mdf_by'            => $recid_karyawan,
                                    'recid_karyawan'    => $recid_karyawan,
                                    'status'            => $status,
                                    'tgl_pulang'        => date('Y-m-d'),
                                    'jam_keluar'        => $absen_pulang,
                                    'lokasi_pulang'     => 'Baros',
                                );
                                $this->m_absenbarcode->update_hadir($data, $recid_absen);
                                // echo "update jam pulang di kehadiran<br>";
                                $hasil = "Ok";
                            }
                        } else {
                            $hasil = "Anda Sudah Absen Pulang";
                            // echo $hasil;
                        }
                    }
                } else {
                    // echo "tidak ada shift malam blm absen masuk<br>";
                    $shift = $this->m_absenbarcode->cek_shift_karyawan($recid_karyawan, $tgl);
                    if ($shift->num_rows() > 0) {
                        //shift
                        // echo "blm absen masuk <br> cek shift ambil jam masuknya<br>";
                        foreach ($shift->result() as $s) {
                            $jam_masuk = $s->jam_in;
                            $jam_keluar = $s->jam_out;
                            $status = $s->recid_jenisabsen;
                            $tmp_status = $s->recid_jenisabsen;
                        }
                    } else {
                        // non shift
                        // echo "jika tidak shift jam masuk jam 07:00<br>";
                        // $jam_masuk = "07:00:00";
                        // $jam_keluar = "16:00:00";
                        $cek_puasa = $this->m_absenbarcode->cek_puasa();
                        if ($cek_puasa->num_rows() > 0) {
                            $jam_ker  = $this->m_absen->jenis_absen_id(29);
                        } else {
                            $jam_ker  = $this->m_absen->jenis_absen_id(1);
                        }
                        foreach ($jam_ker->result() as $jk) {
                            $jam_masuk = $jk->jam_in;
                            $jam_keluar = $jk->jam_out;
                            $status = $jk->recid_jenisabsen;
                        }
                        $tmp_status = $status;
                    }

                    if (date('H:i:s') > $jam_masuk) {
                        // echo "jika pas scan lebih bedar dari ".$jam_masuk." maka perlu validasi blm scan masuk<br>";
                        $validasi = '1';
                    } else {
                        $validasi = '0';
                    }

                    $absen_pulang = date('H:i:s');
                    $diff = strtotime($absen_pulang) - strtotime($jam_masuk);
                    $jam   = floor($diff / (60 * 60));
                    $menit = $diff - ($jam * (60 * 60));
                    // echo $jam;
                    if ($absen_pulang < $jam_keluar) {
                        // echo "pulang cepat <br>";
                        // pulang cepat
                        if ($jam < 4) {
                            // echo "kurang 4 jam <br>";
                            // kerja < 4 jam
                            // status = cuti or p1
                            $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                            //jika masih ada cuti
                            if ($cek_cuti->num_rows() > 0) {
                                $status = 3;    //cuti
                            } else {
                                $status = 5;   //p1
                            }
                            // echo "status : ".$status;
                            //update absensi
                            $data_absen = array(
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'        => $tgl,
                                'jenis_absen'    => $status,
                                'keterangan'    => "pulang",
                                'validasi_cuti'  => "0",
                                'crt_by'        => $recid_karyawan,
                                'crt_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->save_absensi($data_absen);
                            // echo "save absen jadi cuti<br>";
                        }
                        $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                        if ($dbl->num_rows() == 0) {
                            $data_izin = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'tgl_izin'          => date('Y-m-d'),
                                'recid_karyawan'    => $recid_karyawan,
                                'jenis'             => "Pulang",
                                'jam_out'            => date('H:i:s'),
                                'keterangan'        => '',
                                'perlu_validasi'    => '1'
                            );
                            $this->m_absen->save_data('izin', $data_izin);
                        }
                        // echo "save izin pulang cepat<br>";
                    }

                    // echo "save absen masuk jam pulang saja<br>";
                    $data = array(
                        'crt_date'          => date('Y-m-d H:i:s'),
                        'crt_by'            => $recid_karyawan,
                        'recid_karyawan'    => $recid_karyawan,
                        'tanggal'           => date('Y-m-d'),
                        'tgl_masuk'         => date('Y-m-d'),
                        'jam_masuk'         => "00:00:00",
                        'tgl_pulang'         => date('Y-m-d'),
                        'jam_keluar'         => date('H:i:s'),
                        'status'            => $status,
                        'perlu_validasi'    => $validasi,
                        'lokasi_pulang'     => 'Baros',
                    );
                    $this->m_absenbarcode->save_absen_masuk($data);
                    $hasil = "Ok";
                }
            } else {
                //    echo "absen pulang normal";
                foreach ($double->result() as $d) {
                    $recid_absen = $d->recid_absen;
                    $jam_masuk = $d->jam_masuk;
                    $jam_keluar = $d->jam_keluar;
                    $status = $d->status;
                }
                if (is_null($jam_keluar)) {
                    // echo "status absen = ".$status."<br>";
                    $jam_kerja_harusnya = $this->m_absen->jenis_absen_id($status);
                    foreach ($jam_kerja_harusnya->result() as $jkh) {
                        $jam_keluar = $jkh->jam_out;
                    }

                    $absen_pulang = date('H:i:s');
                    $diff = strtotime($absen_pulang) - strtotime($jam_masuk);
                    $jam   = floor($diff / (60 * 60));
                    $menit = $diff - ($jam * (60 * 60));
                    // echo "jmljam: ".$jam;
                    // echo "scan pulang : ".$absen_pulang." -  jam pulang : ".$jam_keluar . "<br>";
                    if ($absen_pulang < $jam_keluar) {
                        // echo "pulang cepat <br>";
                        // pulang cepat
                        if ($jam < 4) {
                            // echo "kurang 4 jam <br>";
                            // kerja < 4 jam
                            // status = cuti or p1
                            $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                            //jika masih ada cuti
                            if ($cek_cuti->num_rows() > 0) {
                                $status = 3;    //cuti
                            } else {
                                $status = 5;   //p1
                            }
                            // echo "status : ".$status;
                            //update absensi
                            $data_absen = array(
                                'recid_karyawan'    => $recid_karyawan,
                                'tanggal'        => $tgl,
                                'jenis_absen'    => $status,
                                'keterangan'    => "Pulang",
                                'validasi_cuti'  => "0",
                                'crt_by'        => $recid_karyawan,
                                'crt_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->save_absensi($data_absen);
                            // echo "save absen jadi cuti<br>";
                        }
                        $dbl = $this->m_absen->cek_duplikat_izin($tgl, $recid_karyawan, 'Pulang');
                        if ($dbl->num_rows() == 0) {
                            $data_izin = array(
                                'crt_date'          => date('Y-m-d H:i:s'),
                                'crt_by'            => $recid_karyawan,
                                'tgl_izin'          => date('Y-m-d'),
                                'recid_karyawan'    => $recid_karyawan,
                                'jenis'             => "Pulang",
                                'jam_out'            => date('H:i:s'),
                                'keterangan'        => '',
                                'perlu_validasi'    => '1'
                            );
                            $this->m_absen->save_data('izin', $data_izin);
                        }
                        // echo "save izin pulang cepat<br>";
                        $data = array(
                            'mdf_date'          => date('Y-m-d H:i:s'),
                            'mdf_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'status'            => $status,
                            'tgl_pulang'        => date('Y-m-d'),
                            'jam_keluar'        => $absen_pulang,
                            'lokasi_pulang'     => 'Baros',
                        );
                        $this->m_absenbarcode->update_hadir($data, $recid_absen);
                        // echo "update jam pulang di kehadiran<br>";
                        $hasil = "Ok";
                    } else {
                        // pulang normal
                        // echo "pulang normal<br>";
                        $data = array(
                            'mdf_date'          => date('Y-m-d H:i:s'),
                            'mdf_by'            => $recid_karyawan,
                            'recid_karyawan'    => $recid_karyawan,
                            'status'            => $status,
                            'tgl_pulang'        => date('Y-m-d'),
                            'jam_keluar'        => $absen_pulang,
                            'lokasi_pulang'     => 'Baros',
                        );
                        $this->m_absenbarcode->update_hadir($data, $recid_absen);
                        // echo "update jam pulang di kehadiran<br>";
                        $hasil = "Ok";
                    }
                } else {
                    $hasil = "Anda Sudah Absen Pulang";
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        // echo $hasil;
        echo json_encode($hasil);
    }

    public function scan_izin()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/scan_izin');
        $this->load->view('layout/footer_js');
    }

    public function scan_izin_baros()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/scan_izin_baros');
        $this->load->view('layout/footer_js');
    }

    public function monitor_pulang()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_pulang');
        $this->load->view('layout/footer_js');
    }

    public function monitor_pulang_baros()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_pulang_baros');
        $this->load->view('layout/footer_js');
    }

    public function data_pulang()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_absenbarcode->monitor_pulang($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $data[] = array(
                // $no = $no + 1,
                // $r->nik,
                $r->nama_karyawan,
                // $r->indeks_hr,
                $r->jam_masuk,
                $r->jam_keluar,
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

    public function data_pulang_baros()
    {
        $tgl_awal = date('Y-m-d');
        $query = $this->m_absenbarcode->monitor_pulang_baros($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $data[] = array(
                // $no = $no + 1,
                // $r->nik,
                $r->nama_karyawan,
                // $r->indeks_hr,
                $r->jam_masuk,
                $r->jam_keluar,
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

    public function list_validasi_siang()
    {
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $menus = 'layout/menu_super';
        } else {
            $alias = $this->session->userdata('recid_login');
            $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
            $menus = 'layout/menu_outsource';
        }
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $data['scan'] = $this->m_absenbarcode->validasi_masuk_siang();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/absen_barcode/list_validasi_siang', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function validasi_telat()
    {
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $menus = 'layout/menu_super';
        } else {
            $alias = $this->session->userdata('recid_login');
            $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
            $menus = 'layout/menu_outsource';
        }
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id = $this->uri->segment(3);
            $data['hadir'] = $this->m_absenbarcode->hadir_by_id($id);
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/absen_barcode/validasi_siang', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function pvalidasi_telat()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id = $this->input->post('recid_absen');
            $recid_karyawan = $this->input->post('recid_karyawan');
            $status = $this->input->post('status');
            $tmp_status = $this->input->post('tmp_status');
            $ket_valid = $this->input->post('keterangan_validasi');
            $alasan = $this->input->post('alasan');
            $tgl_kerja = $this->input->post('tgl_kerja');

            if ($status == '10') {
                if ($ket_valid == "Dinas" or $ket_valid == "Terlambat Angkutan Perusahaan") {
                    $status = $tmp_status;
                    $flag_premi = "";
                } else {
                    // terlambat karena pribadi (terlambat / izin siang)
                    // status = cuti or mangkir
                    $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                    //jika masih ada cuti
                    if ($cek_cuti->num_rows() > 0) {
                        $status = 3;    //cuti
                    } else {
                        $status = 11;   //mangkir
                    }
                    $flag_premi = $ket_valid;
                }

                //update absensi
                $data_absensi = array(
                    'mdf_date'          => date('Y-m-d H:i:s'),
                    'mdf_by'            => $this->session->userdata('kar_id'),
                    'validasi_cuti'     => '1',
                    'jenis_absen'       => $status,
                    'keterangan'        => $ket_valid . " - " . $alasan,
                );
                $this->m_absen->edit_absensi($recid_karyawan, $tgl_kerja, $data_absensi);
            } else {
                $cek_jenis = $this->m_absenbarcode->cek_ja_hadir($recid_karyawan, $tgl_kerja);
                $durasi = 0;
                $over_durasi = 0;
                if ($cek_jenis->num_rows() > 0) {
                    foreach ($cek_jenis->result() as $k) {
                        $jam_masuk_normal  = new DateTime($k->jam_in);
                        $jam_scan_masuk = new DateTime($k->jam_masuk);
                        $status = $k->status;

                        $selisih = $jam_scan_masuk->diff($jam_masuk_normal);
                        $jam = $selisih->format('%h');
                        $menit = $selisih->format('%i');
                        if ($menit >= 60) {
                            $jam = $jam + 1;
                            $menit = $menit - 60;
                        }
                        if ($menit >= 0 && $menit <= 9) {
                            $menit = "0" . $menit;
                        }
                        $durasi = $jam . ' jam ' . $menit . " menit";
                        $over_durasi = $jam . "." . $menit;
                    }
                }

                $izin = $this->m_absen->cek_duplikat_telat($tgl_kerja, $recid_karyawan, $ket_valid);
                if ($izin->num_rows() > 0) {
                    foreach ($izin->result() as $i) {
                        $id_izin = $i->izin_recid;
                    }

                    $data_izin = array(
                        "mdf_by"        => $this->session->userdata('kar_id'),
                        "mdf_date"      => date('Y-m-d H:i:s'),
                        'perlu_validasi'    => '0',
                        'keterangan'    => $alasan,
                        'over_durasi'   => $over_durasi,
                        'jenis'         => $ket_valid
                    );
                    $this->m_absen->edit_izin($id_izin, $data_izin);
                    $flag_premi = $ket_valid;
                } else {
                    $flag_premi = $ket_valid;
                }
            }
            // update hadir
            $data = array(
                'mdf_date'          => date('Y-m-d H:i:s'),
                'mdf_by'            => $this->session->userdata('kar_id'),
                'perlu_validasi'    => '0',
                'status'         => $status,
                'ket_validasi'     => $ket_valid,
                'flag_premi'     => $flag_premi,
            );
            $this->m_absenbarcode->update_hadir($data, $id);

            redirect('AbsenBarcode/list_validasi_siang');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function list_validasi_pulang()
    {
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $menus = 'layout/menu_super';
        } else {
            $alias = $this->session->userdata('recid_login');
            $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
            $menus = 'layout/menu_outsource';
        }
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            // $data['scan'] = $this->m_absenbarcode->validasi_pulang_cepat();
            $data['scan'] = $this->m_absenbarcode->validasi_pulang_cepat2();
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/absen_barcode/list_validasi_pulang', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function validasi_pulang()
    {
        $as_user = $this->session->userdata('as_user');
        if ($as_user == "CINT") {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $menus = 'layout/menu_super';
        } else {
            $alias = $this->session->userdata('recid_login');
            $data['cek_usr'] = $this->m_hris->cek_kantin($alias);
            $menus = 'layout/menu_outsource';
        }
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id = $this->uri->segment(3);
            // $data['hadir'] = $this->m_absenbarcode->hadir_by_id($id);
            $data['izin'] = $this->m_absen->izin_by_id($id);
            $this->load->view('layout/a_header');
            $this->load->view($menus, $data);
            $this->load->view('absen/absen_barcode/validasi_pulang', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function pvalidasi_pulang()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id_izin = $this->input->post('recid_izin');
            // $id_absen = $this->input->post('recid_absen');
            $recid_karyawan = $this->input->post('recid_karyawan');
            $alasan = $this->input->post('alasan');
            $tgl_kerja = $this->input->post('tgl_kerja');

            $cek_jenis = $this->m_absenbarcode->cek_ja_hadir($recid_karyawan, $tgl_kerja);
            if ($cek_jenis->num_rows() > 0) {
                foreach ($cek_jenis->result() as $k) {
                    $jam_keluar1 = $k->jam_keluar;
                    $status = $k->status;

                    if ($status == 1) // kerja normal (11:30 - 12:30)
                    {
                        if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
                            $jam_keluar = new DateTime("12:31"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 26) { /* Middle Shift 1 (11:00 - 12:00)*/
                        if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
                            $jam_keluar = new DateTime("12:01"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 27) {/* Middle Shift 2 (13:00 - 14:00)*/
                        if ($jam_keluar1 > "13:00" and $jam_keluar1 < "14:01") {
                            $jam_keluar = new DateTime("14:01"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 29) {/* Kerja Non Shift Ramadhan (11:00 - 12:00)*/
                        if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
                            $jam_keluar = new DateTime("12:01"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 30) {/* Middle Shift 3 (12:00 - 13.00)*/
                        if ($jam_keluar1 > "12:00" and $jam_keluar1 < "13:01") {
                            $jam_keluar = new DateTime("13:01"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 35) {/* Ganti Hari Kerja (11:30 - 12:30)*/
                        if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
                            $jam_keluar = new DateTime("12:31"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else if ($status == 37) {/* Middle Shift 4 (14:30 - 15:30) */
                        if ($jam_keluar1 > "14:30" and $jam_keluar1 < "15:31") {
                            $jam_keluar = new DateTime("15:31"); // jam scan
                        } else {
                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                        }
                    } else {
                        $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                    }
                    $pulang_normal = new DateTime($k->jam_out);
                    $selisih = $pulang_normal->diff($jam_keluar);
                    $jam = $selisih->format('%h');
                    $menit = $selisih->format('%i');
                    if ($menit >= 60) {
                        $jam = $jam + 1;
                        $menit = $menit - 60;
                    }
                    if ($menit >= 0 && $menit <= 9) {
                        $menit = "0" . $menit;
                    }
                    $durasi = $jam . ' jam ' . $menit . " menit";
                    $over_durasi = $jam . "." . $menit;
                }
            } else {
                $pulang_normal = "00:00:00";
                $jam_masuk = "00:00:00";
                $jam_pulang = "00:00:00";
                $durasi = "00:00:00";
                $over_durasi = 0;
            }


            // echo $id_absen;
            $izin = $this->m_absen->cek_duplikat_izin($tgl_kerja, $recid_karyawan, "Pulang");
            if ($izin->num_rows() > 0) {
                foreach ($izin->result() as $i) {
                    $id_izin = $i->izin_recid;
                    $data_izin = array(
                        "mdf_by"        => $this->session->userdata('kar_id'),
                        "mdf_date"      => date('Y-m-d H:i:s'),
                        'keterangan'    => $alasan,
                        'over_durasi'   => $over_durasi,
                        'perlu_validasi' => '0',
                    );
                    $this->m_absen->edit_izin($id_izin, $data_izin);
                }

                $cek_hadir = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_kerja);
                if ($cek_hadir->num_rows() > 0) {
                    foreach ($cek_hadir->result() as $h) {
                        $data_hadir = array(
                            "mdf_by"        => $this->session->userdata('kar_id'),
                            "mdf_date"      => date('Y-m-d H:i:s'),
                            'perlu_validasi' => '0',
                            'flag_premi'    => 'Pulang'
                        );
                        $this->m_absenbarcode->update_hadir($data_hadir, $h->recid_absen);
                    }
                }
            } else {
                $data_izin = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'crt_by'            => $recid_karyawan,
                    'tgl_izin'          => $tgl_kerja,
                    'recid_karyawan'    => $recid_karyawan,
                    'jenis'             => "Pulang",
                    'jam_out'            => date('H:i:s'),
                    'keterangan'        => $alasan,
                    'over_durasi'       => $over_durasi,
                    'perlu_validasi'    => '0',
                );
                $this->m_absen->save_data('izin', $data_izin);

                $cek_hadir = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_kerja);
                if ($cek_hadir->num_rows() > 0) {
                    foreach ($cek_hadir->result() as $h) {
                        $data_hadir = array(
                            "mdf_by"        => $this->session->userdata('kar_id'),
                            "mdf_date"      => date('Y-m-d H:i:s'),
                            'perlu_validasi' => '0',
                            'flag_premi'    => 'Pulang'
                        );
                        $this->m_absenbarcode->update_hadir($data_hadir, $h->recid_absen);
                    }
                }
            }
            redirect('AbsenBarcode/list_validasi_pulang');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function shift_view()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['shift'] = $this->m_absen->jenis_absen();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/shift/shift_view', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function data_shift_date()
    {
        $tgl_awal = $this->input->post('tgl_mulai');
        $query2 = $this->m_absenbarcode->data_shift_date($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;

        foreach ($query2->result() as $r) {
            $shift = $r->jenis . " - " . $r->keterangan;
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $r->indeks_jabatan,
                $r->penempatan,
                $r->tgl_kerja,
                $shift,
                "<a data-gh_recid ='$r->recid_jadwal' data-nama ='$r->nama_karyawan' data-nik ='$r->recid_karyawan' data-nik2 ='$r->nik' data-bag ='$r->indeks_hr' data-jbtn ='$r->indeks_jabatan' data-tanggal ='$r->tgl_kerja' data-shift ='$shift'  data-jenis_shift = '$r->recid_jenisabsen' data-toggle='modal' data-target='#edit_shift'><button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a><a href='" . base_url() . "AbsenBarcode/shift_hapus/" . $r->recid_jadwal . "'><button class='btn btn-danger btn-xs'><span class='fa fa-trash'></button></a>"
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

    public function data_jenis_absensi()
    {
        $query2 = $this->m_absen->jenis_absen();
        $data = [];
        $no = 0;
        foreach ($query2->result() as $r) {
            $data[] = array(
                $r->recid_jenisabsen
            );
        }

        echo json_encode($data);
        exit();
    }

    function upload_shift()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/shift/upload_shift', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function import_shift()
    {
        $text = "";
        $error = 0;
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['file_exc']['name']) && in_array($_FILES['file_exc']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['file_exc']['name']);
            $extension = end($arr_file);
            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ('xls' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($_FILES['file_exc']['tmp_name']);
            // $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $highestColumm = $spreadsheet->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL" 
            $ColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumm);
            $highestRow = $spreadsheet->setActiveSheetIndex(0)->getHighestRow();
            $highestColumm++;
            $baris = 0;
            for ($row = 5; $row < $highestRow + 1; $row++) {
                $baris = $baris + 1;
                $dataset = array();
                ${"datake" . $baris} = array();
                for ($column = 'A'; $column != $highestColumm; $column++) {
                    $dataset[] = $spreadsheet->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                    ${"datake" . $baris}[] = $spreadsheet->setActiveSheetIndex(0)->getCell($column . $row)->getValue();
                }
                $this->datasets[] = $dataset;
            }

            for ($k = 2; $k <= $baris; $k++) // looping banyak karyawan
            {
                for ($d = 1; $d < $ColumnIndex; $d++) { //looping sebanyak tanggal
                    $nik = ${"datake" . $k}[1];
                    $nama = ${"datake" . $k}[2];

                    $cek = $this->m_hris->karyawan_by_nik3($nik);
                    if ($cek->num_rows() > 0) {
                        foreach ($cek->result() as $c) {
                            $recid_karyawan = $c->recid_karyawan;
                        }

                        if ($d == 1 or $d == 2) {
                            //skip aja karena data nama dan nik sudah di dapat
                            // echo "NIK = ".${"datake" . $k}[$d]."<br>";
                        } else {
                            //proses simpan jadwal shift
                            echo "nik : " . $nik . " nama : " . $nama . " tanggal : " . $datake1[$d] . " => " . ${"datake" . $k}[$d] . "<br>";
                            if (${"datake" . $k}[$d] != '-' and ${"datake" . $k}[$d] != 'K') {
                                $shift = $this->m_absenbarcode->jenis_absen_by_kode(${"datake" . $k}[$d]);
                                if ($shift->num_rows() > 0) {
                                    foreach ($shift->result() as $sh) {
                                        $recid_jenisabsen = $sh->recid_jenisabsen;
                                    }

                                    $dbl = $this->m_absenbarcode->shift_double($recid_karyawan, $datake1[$d]);
                                    if ($dbl->num_rows() == 0) {
                                        $data_shift = array(
                                            'crt_by'  => $this->session->userdata('kar_id'),
                                            'crt_date'  => date('Y-m-d H:i:s'),
                                            'tgl_kerja' => $datake1[$d],
                                            'recid_karyawan' => $recid_karyawan,
                                            'recid_jenisabsen' => $recid_jenisabsen
                                        );
                                        $this->m_absenbarcode->save_shift($data_shift);
                                        // echo "save shift ". $datake1[$d]." id emp ". $recid_karyawan." tipe ". $recid_jenisabsen;
                                    } else {
                                        foreach ($dbl->result() as $us) {
                                            $recid_jadwal = $us->recid_jadwal;
                                            $data_shift = array(
                                                'mdf_by'  => $this->session->userdata('kar_id'),
                                                'mdf_date'  => date('Y-m-d H:i:s'),
                                                'tgl_kerja' => $datake1[$d],
                                                'recid_karyawan' => $recid_karyawan,
                                                'recid_jenisabsen' => $recid_jenisabsen
                                            );
                                            $this->m_absenbarcode->update_shift($data_shift, $recid_jadwal);
                                        }
                                        // echo "edit shift " . $datake1[$d] . " id emp " . $recid_karyawan . " tipe " . $recid_jenisabsen;
                                    }
                                } else {
                                    $text .= "karyawan dengan NIK " . $nik . " Salah Input Shift Pada Tanggal " . $datake1[$d] . "<br>";
                                    $error = $error + 1;
                                }
                            }
                        }
                    } else {
                        $text .= "karyawan dengan NIK " . $nik . " Tidak Terdaftar <br>";
                        $error = $error + 1;
                    }
                }
            }
            echo "jumlah_error : " . $error . "<br>";
            echo $text;
        }
        if ($error > 0) {
            echo "jumlah_error : " . $error . "<br>";
            echo $text;
        } else {
            redirect("AbsenBarcode/shift_view");
        }
    }

    public function shift_update()
    {
        $recid_jadwal = $this->input->post('jadwal_recid');
        $recid_jenisabsen = $this->input->post('recid_jenisabsen');
        $data_shift = array(
            'mdf_by'  => $this->session->userdata('kar_id'),
            'mdf_date'  => date('Y-m-d H:i:s'),
            'recid_jenisabsen' => $recid_jenisabsen
        );
        $this->m_absenbarcode->update_shift($data_shift, $recid_jadwal);
        redirect("AbsenBarcode/shift_view");
    }

    public function shift_hapus()
    {
        $recid_jadwal = $this->uri->segment(3);
        $data_shift = array(
            'mdf_by'  => $this->session->userdata('kar_id'),
            'mdf_date'  => date('Y-m-d H:i:s'),
            'is_delete' => '1'
        );
        $this->m_absenbarcode->update_shift($data_shift, $recid_jadwal);
        redirect("AbsenBarcode/shift_view");
    }

    public function monitor_izin()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_izin');
        $this->load->view('layout/footer_js');
    }

    public function monitor_izin_baros()
    {
        $this->load->view('layout/a_header2');
        $this->load->view('absen/absen_barcode/monitor_izin_baros');
        $this->load->view('layout/footer_js');
    }

    public function data_izin()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_absenbarcode->validasi_izin_tgl($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $kat = $r->kat_keluar;
            if ($kat == 'Dinas') {
                $kat = "Dinas Dalam / Luar Kota";
            } else if ($kat == 'Baros') {
                $kat = 'Dinas Baros / Industri';
            } else {
                $kat = $kat;
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $kat,
                $r->jam_in,
                $r->jam_out,
                $r->keterangan,
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

    public function data_izin_baros()
    {
        $tgl_awal = $this->input->post('tgl');
        $query = $this->m_absenbarcode->validasi_izin_baros_tgl($tgl_awal);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];
        $no = 0;
        foreach ($query->result() as $r) {
            $kat = $r->kat_keluar;
            if ($kat == 'Dinas') {
                $kat = "Dinas Dalam / Luar Kota";
            } else if ($kat == 'Baros') {
                $kat = 'Dinas Baros / Industri';
            } else {
                $kat = $kat;
            }
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $kat,
                $r->jam_in,
                $r->jam_out,
                $r->keterangan,
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

    public function simpan_izin()
    {

        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        $jenis = $this->input->post('jenis');
        // $jenis = "Baros";
        // $nik = "20140120264";
        // $jenis = "pribadi";

        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                //cek double scan
                $double = $this->m_absen->cek_duplikat_keluar($tgl, $recid_karyawan, $jenis);
                if ($double->num_rows() > 0) {
                    foreach ($double->result() as $d) {
                        $izin_recid = $d->izin_recid;
                    }
                    $data_izin = array(
                        'mdf_date'          => date('Y-m-d H:i:s'),
                        'mdf_by'            => $recid_karyawan,
                        'tgl_izin'          => date('Y-m-d'),
                        'recid_karyawan'    => $recid_karyawan,
                        'jam_in'            => date('H:i:s'),
                        'keterangan'        => '',
                        'lokasi_keluar'     => 'Industri'
                    );
                    $this->m_absen->edit_izin($izin_recid, $data_izin);
                    $hasil = "Ok";
                } else {
                    $data_izin = array(
                        'crt_date'          => date('Y-m-d H:i:s'),
                        'crt_by'            => $recid_karyawan,
                        'tgl_izin'          => date('Y-m-d'),
                        'recid_karyawan'    => $recid_karyawan,
                        'jenis'             => "Keluar",
                        'kat_keluar'        => $jenis,
                        'jam_out'           => date('H:i:s'),
                        'perlu_validasi'    => 1,
                        'keterangan'        => '',
                        'lokasi_masuk'     => 'Industri'
                    );
                    $this->m_absen->save_data('izin', $data_izin);
                    $hasil = "Ok";
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        echo json_encode($hasil);
    }

    public function simpan_izin_baros()
    {

        $tgl = date('Y-m-d');
        $nik = $this->input->post('nik');
        $jenis = $this->input->post('jenis');
        // $jenis = "Baros";
        // $nik = "20140120264";
        // $jenis = "pribadi";

        // Use flexible NIK validation for both old and new formats
        $rekar = $this->m_kantin->karyawan_by_nik_flexible($nik);
        if ($rekar->num_rows() > 0) {
            foreach ($rekar->result() as $r) {
                $recid_karyawan = $r->recid_karyawan;
                //cek double scan
                $double = $this->m_absen->cek_duplikat_keluar($tgl, $recid_karyawan, $jenis);
                if ($double->num_rows() > 0) {
                    foreach ($double->result() as $d) {
                        $izin_recid = $d->izin_recid;
                    }
                    $data_izin = array(
                        'mdf_date'          => date('Y-m-d H:i:s'),
                        'mdf_by'            => $recid_karyawan,
                        'tgl_izin'          => date('Y-m-d'),
                        'recid_karyawan'    => $recid_karyawan,
                        'jam_in'            => date('H:i:s'),
                        'keterangan'        => '',
                        'lokasi_keluar'     => 'Baros'
                    );
                    $this->m_absen->edit_izin($izin_recid, $data_izin);
                    $hasil = "Ok";
                } else {
                    $data_izin = array(
                        'crt_date'          => date('Y-m-d H:i:s'),
                        'crt_by'            => $recid_karyawan,
                        'tgl_izin'          => date('Y-m-d'),
                        'recid_karyawan'    => $recid_karyawan,
                        'jenis'             => "Keluar",
                        'kat_keluar'        => $jenis,
                        'jam_out'           => date('H:i:s'),
                        'perlu_validasi'    => 1,
                        'keterangan'        => '',
                        'lokasi_masuk'     => 'Baros'
                    );
                    $this->m_absen->save_data('izin', $data_izin);
                    $hasil = "Ok";
                }
            }
        } else {
            $hasil = "NIK Tidak Terdaftar";
        }
        echo json_encode($hasil);
    }

    public function list_validasi_izin()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            // $tgl = "2022-12-01";
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['scan'] = $this->m_absenbarcode->validasi_izin_tgl($tgl);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/list_validasi_izin', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function validasi_izin()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id = $this->uri->segment(3);
            $data['hadir'] = $this->m_absen->izin_by_id($id);
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/validasi_izin', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function pvalidasi_izin()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $recid_karyawan = $this->input->post('recid_karyawan');
            $alasan = $this->input->post('alasan');
            $tgl_izin = $this->input->post('tgl_izin');
            $kat_keluar = $this->input->post('kat_keluar');

            $izin = $this->m_absen->cek_duplikat_keluar($tgl_izin, $recid_karyawan, $kat_keluar);
            if ($izin->num_rows() > 0) {
                foreach ($izin->result() as $i) {
                    $id_izin = $i->izin_recid;
                }

                $data_izin = array(
                    "mdf_by"        => $this->session->userdata('kar_id'),
                    "mdf_date"      => date('Y-m-d H:i:s'),
                    'keterangan'    => $alasan,
                    'perlu_validasi' => '0',
                );
                $this->m_absen->edit_izin($id_izin, $data_izin);
            } else {
                $data_izin = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'crt_by'            => $recid_karyawan,
                    'tgl_izin'          => $tgl_izin,
                    'recid_karyawan'    => $recid_karyawan,
                    'jenis'             => "Keluar",
                    'jam_out'            => date('H:i:s'),
                    'keterangan'        => $alasan,
                    'perlu_validasi'    => '0',
                );
                $this->m_absen->save_data('izin', $data_izin);
            }
            redirect('AbsenBarcode/list_validasi_izin');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function hadir_today()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            // Initialize variables with default values
            $nama = '';
            $recid_bags = '';
            $bagian = '';
            $jabatan = '';
            $tingkatan = 0;
            $struktur = '';
            $dept_group = '';

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $recid_bags = $user->recid_bag;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
                $dept_group = $user->dept_group;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '23' or $role == '24' or $role == '25' or $role == '26' or $role == '29' or $role == '30' or $role == '34' or $role == '35' or $role == '37' or $role == '41') {
                $data['absen'] = $this->m_absenbarcode->hadir_today($tgl);
                $close = $this->m_absen->cek_closing_kehadiran($tgl);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = '0';
                }

                $data['closing']  = $closing;
            } else if ($role == '32') {
                $data['absen'] = $this->m_absenbarcode->jml_all_baros($tgl);
                $data['closing']  = '0';
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $data['absen'] = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            } else if ($role == '23' or $role == '41' or $role == '29') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    //custom role for all produksi = pic produksi (pa dadan 920)
                    // $bgn = $this->m_hris->prd_view_by_atasan('920');
                    $bgn = $this->m_absen->bagian_by_admin(1061); //admin bagian produksi
                } else if ($role == '29') {
                    $admin = $this->m_absen->admin_by_bagian($recid_bags);
                    foreach ($admin->result() as $adm) {
                        $adminnya = $adm->recid_karyawan;
                    }
                    $bgn = $this->m_absen->bagian_by_admin($adminnya);
                } else {
                    $admin = $this->m_absen->admin_by_bagian($recid_bags);
                    foreach ($admin->result() as $adm) {
                        $adminnya = $adm->recid_karyawan;
                    }
                    $bgn = $this->m_absen->bagian_by_admin($adminnya);
                }
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
                }
                for ($i = 0; $i < count($bag); $i++) {
                    $iindex = $this->m_hris->bagian_by_recid($bag[$i]);
                    foreach ($iindex as $s) {
                        $indeks_hr = $s->indeks_hr;
                        if ($i < (count($bag) - 1)) {
                            $bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
                        } else {
                            $bagian .= "'" . $indeks_hr . "'";
                        }
                    }
                }
                $bagian .= ")";
                $data['absen'] = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            } else if ($role == 37) {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                // echo $dept_group;
                $bgn = $this->m_absen->bagian_by_dept_group($dept_group);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                // echo "<br>".$tgl;
                $cek_num = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
                // echo "<br>".$cek_num->num_rows();
                $data['absen'] = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['absen'] = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/kehadiran/hadir_today', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function tidak_hadir_today()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_tidak_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $recid_bags = $user->recid_bag;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $data['absen'] = $this->m_absenbarcode->tidak_hadir_today($tgl);
                $close = $this->m_absen->cek_closing_kehadiran($tgl);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = '0';
                }

                $data['closing']  = $closing;
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $data['absen'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            } else if ($role == '32') {
                $data['absen'] = $this->m_absenbarcode->tidak_hadir_today_baros($tgl);
                $data['closing']  = '0';
            } else if ($role == '23' or $role == '41' or $role == '29') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    $bgn = $this->m_absen->bagian_by_admin(1061); //admin bagian produksi
                } else {
                    $admin = $this->m_absen->admin_by_bagian($recid_bags);
                    foreach ($admin->result() as $adm) {
                        $adminnya = $adm->recid_karyawan;
                    }
                    $bgn = $this->m_absen->bagian_by_admin($adminnya);
                    // $bgn = $this->m_hris->bagian_by_pic_str($recid_karyawan);
                }
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
                }
                for ($i = 0; $i < count($bag); $i++) {
                    $iindex = $this->m_hris->bagian_by_recid($bag[$i]);
                    foreach ($iindex as $s) {
                        $indeks_hr = $s->indeks_hr;
                        if ($i < (count($bag) - 1)) {
                            $bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
                        } else {
                            $bagian .= "'" . $indeks_hr . "'";
                        }
                    }
                }
                $bagian .= ")";
                // echo $bagian;
                $data['absen'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['absen'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $close = $this->m_absen->cek_closing_bagian($tgl, $bagian);
                if ($close->num_rows() > 0) {
                    foreach ($close->result() as $cl) {
                        $closing = $cl->is_closed;
                    }
                } else {
                    $closing = "0";
                }

                $data['closing']  = $closing;
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/kehadiran/belum_absen', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function update_absen()    // dari selectbox admin
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $recid_karyawan = $this->input->post('recid_karyawan');
            // $recid_karyawan = 1189;
            $tgl_work = date('Y-m-d');
            $jenis_absen = $this->input->post('jenis_absen');
            // $jenis_absen = 3;
            $jam = $this->m_absen->jenis_absen_id($jenis_absen);
            foreach ($jam->result() as $j) {
                $jam_masuk = $j->jam_in;
                $jam_keluar = $j->jam_out;
            }

            $hadir = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_work);
            if ($hadir->num_rows() > 0) {
                //edit
                foreach ($hadir->result() as $hd) {
                    $recid_kehadiran = $hd->recid_absen;
                }
                if ($jenis_absen == 1 or $jenis_absen == 4  or ($jenis_absen >= 14 and $jenis_absen <= 23) or ($jenis_absen > 25 and $jenis_absen <= 27)) { /*if jenis absen barunya adalah kerja*/
                    // UPDATE KEHADIRAN
                    $data = array(
                        'mdf_date'          => date('Y-m-d H:i:s'),
                        'mdf_by'            => $usr,
                        'tanggal'           => $tgl_work,
                        'tgl_masuk'          => $tgl_work,
                        'jam_masuk'         => $jam_masuk,
                        'tgl_pulang'         => $tgl_work,
                        'jam_keluar'         => $jam_keluar,
                        'status'            => $jenis_absen,
                        'tmp_status'        => $jenis_absen,
                        'lokasi_masuk'      => 'Industri',
                        'lokasi_pulang'      => 'Industri',
                        'ket_validasi'      => "By Admin",
                        'perlu_validasi'    => '0',
                    );
                    $result = $this->m_absenbarcode->update_hadir($data, $recid_kehadiran);
                } else {
                    /* jenis absen barunya tidak hadir */
                    $data = array(
                        'mdf_date'          => date('Y-m-d H:i:s'),
                        'mdf_by'            => $usr,
                        'tanggal'           => $tgl_work,
                        'tgl_masuk'          => $tgl_work,
                        'jam_masuk'         => $jam_masuk,
                        'tgl_pulang'         => $tgl_work,
                        'jam_keluar'         => $jam_keluar,
                        'status'            => $jenis_absen,
                        'tmp_status'        => $jenis_absen,
                        'lokasi_masuk'      => 'Industri',
                        'lokasi_pulang'      => 'Industri',
                        'ket_validasi'      => "By Admin",
                        'perlu_validasi'    => '0',
                    );
                    $result = $this->m_absenbarcode->update_hadir($data, $recid_kehadiran);

                    /* update absensi */
                    $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_work);
                    if ($cek->num_rows() > 0) {    /* update data yg udah ada*/
                        if ($jenis_absen != '2') {
                            $diagnosa = '';
                            $kategori = '';
                            $ket_sakit = '';
                            if ($jenis_absen == '3' or $jenis_absen == '5' or $jenis_absen == '7' or $jenis_absen == '8' or $jenis_absen == '10' or $jenis_absen == '12' or $jenis_absen == '25') {
                                $validasi_cuti = '0';     /*belum di validasi kiki*/
                            } else {
                                $validasi_cuti = '';
                            }
                        } else {
                            if ($jenis_absen == '2') {
                                $validasi_cuti = '0';
                            } else {
                                $validasi_cuti = '';
                            }
                        }
                        $data_edit = array(
                            'validasi_cuti'    => $validasi_cuti,
                            'jenis_absen'    => $jenis_absen,
                            'mdf_by'        => $this->session->userdata('kar_id'),
                            'mdf_date'        => date('Y-m-d h:i:s'),
                            'is_delete'        => '0',
                        );
                        $result = $this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
                    } else { // simpan absensi baru
                        if ($jenis_absen != '2') {
                            $diagnosa = '';
                            $kategori = '';
                            $ket_sakit = '';
                            if ($jenis_absen == '3' or $jenis_absen == '5' or $jenis_absen == '7' or $jenis_absen == '8' or $jenis_absen == '10' or $jenis_absen == '12' or $jenis_absen == '25') {
                                $validasi_cuti = '0';     /*belum di validasi kiki*/
                            } else {
                                $validasi_cuti = '';
                            }
                        } else {
                            if ($jenis_absen == '2') {
                                $validasi_cuti = '0';
                            } else {
                                $validasi_cuti = '';
                            }
                        }
                        $data_absen = array(
                            'validasi_cuti'    => $validasi_cuti,
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'        => $tgl_work,
                            'jenis_absen'    => $jenis_absen,
                            'crt_by'        => $this->session->userdata('kar_id'),
                            'crt_date'        => date('Y-m-d h:i:s'),
                        );
                        $result = $this->m_absen->save_absensi($data_absen);
                    }
                }
            } else {
                //save baru
                $data = array(
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'crt_by'            => $usr,
                    'recid_karyawan'    => $recid_karyawan,
                    'tanggal'           => $tgl_work,
                    'tgl_masuk'          => $tgl_work,
                    'jam_masuk'         => $jam_masuk,
                    'tgl_pulang'         => $tgl_work,
                    'jam_keluar'         => $jam_keluar,
                    'status'            => $jenis_absen,
                    'lokasi_masuk'      => 'Industri',
                    'lokasi_pulang'      => 'Industri',
                    'status'            => $jenis_absen,
                    'tmp_status'        => $jenis_absen,
                    'ket_validasi'      => "By Admin",
                    'perlu_validasi'    => '0',
                );
                $result = $this->m_absenbarcode->save_absen_masuk($data);

                /* update absensi */
                $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_work);
                if ($cek->num_rows() > 0) {    /* update data yg udah ada*/
                    if ($jenis_absen != '2') {
                        $diagnosa = '';
                        $kategori = '';
                        $ket_sakit = '';
                        if ($jenis_absen == '3' or $jenis_absen == '5' or $jenis_absen == '7' or $jenis_absen == '8' or $jenis_absen == '10' or $jenis_absen == '12' or $jenis_absen == '25') {
                            $validasi_cuti = '0';     /*belum di validasi kiki*/
                        } else {
                            $validasi_cuti = '';
                        }
                    } else {
                        if ($jenis_absen == '2') {
                            $validasi_cuti = '0';
                        } else {
                            $validasi_cuti = '';
                        }
                    }
                    $data_edit = array(
                        'validasi_cuti'    => $validasi_cuti,
                        'jenis_absen'    => $jenis_absen,
                        'mdf_by'        => $this->session->userdata('kar_id'),
                        'mdf_date'        => date('Y-m-d h:i:s'),
                        'is_delete'        => '0',
                    );
                    $result = $this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
                } else { // simpan absensi baru
                    if ($jenis_absen != '2') {
                        $diagnosa = '';
                        $kategori = '';
                        $ket_sakit = '';
                        if ($jenis_absen == '3' or $jenis_absen == '5' or $jenis_absen == '7' or $jenis_absen == '8' or $jenis_absen == '10' or $jenis_absen == '12' or $jenis_absen == '25') {
                            $validasi_cuti = '0';     /*belum di validasi kiki*/
                        } else {
                            $validasi_cuti = '';
                        }
                    } else {
                        if ($jenis_absen == '2') {
                            $validasi_cuti = '0';
                        } else {
                            $validasi_cuti = '';
                        }
                    }
                    $data_absen = array(
                        'validasi_cuti'    => $validasi_cuti,
                        'recid_karyawan'    => $recid_karyawan,
                        'tanggal'        => $tgl_work,
                        'jenis_absen'    => $jenis_absen,
                        'crt_by'        => $this->session->userdata('kar_id'),
                        'crt_date'        => date('Y-m-d h:i:s'),
                    );
                    $result = $this->m_absen->save_absensi($data_absen);
                }
            }
            echo json_encode($result);
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_tidak_hadir_periode()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $absen = $this->m_absenbarcode->tidak_hadir_today($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->tidak_hadir_today_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->tidak_hadir_today_bagian($tgl, $bagian);
            } else if ($role == '23' or $role == '41') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    //custom role for all produksi = pic produksi (pa dadan 920)
                    $bgn = $this->m_hris->prd_view_by_atasan('920');
                } else {
                    $bgn = $this->m_hris->bagian_by_pic_str($recid_karyawan);
                }
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
                }
                for ($i = 0; $i < count($bag); $i++) {
                    $iindex = $this->m_hris->bagian_by_recid($bag[$i]);
                    foreach ($iindex as $s) {
                        $indeks_hr = $s->indeks_hr;
                        if ($i < (count($bag) - 1)) {
                            $bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
                        } else {
                            $bagian .= "'" . $indeks_hr . "'";
                        }
                    }
                }
                $bagian .= ")";
                $absen = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $absen = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
            }
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($absen->result() as $r) {
                $bagian = $r->indeks_hr;
                $bagian = substr($bagian, strpos($bagian, " ") + 1);

                $jabatan = $r->indeks_jabatan;
                $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                $golongan = $r->nama_golongan;
                $golongan = substr($golongan, strpos($golongan, " ") + 1);

                $struktur = $r->nama_struktur;
                $struktur = substr($struktur, strpos($struktur, " ") + 1);

                if ($r->recid_bag == 0) {
                    $bagian = "-";
                } else {
                    $bagian = $bagian;
                }

                if ($r->recid_jbtn == 0) {
                    $jabatan = "-";
                } else {
                    $jabatan = $jabatan;
                }
                $data[] = array(
                    $no = $no + 1,
                    $r->nik,
                    $r->nama_karyawan,
                    $bagian,
                    $jabatan,
                    $golongan,
                    $r->penempatan,
                    $tgl,
                    "-",
                    "-",
                    "-",
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $absen->num_rows(),
                "recordsFiltered" => $absen->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_blm_absen_periode()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $absen = $this->m_absenbarcode->tidak_hadir_today($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $absen = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
            }
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($absen->result() as $r) {
                $bagian = $r->indeks_hr;
                $bagian = substr($bagian, strpos($bagian, " ") + 1);

                $jabatan = $r->indeks_jabatan;
                $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                $golongan = $r->nama_golongan;
                $golongan = substr($golongan, strpos($golongan, " ") + 1);

                $struktur = $r->nama_struktur;
                $struktur = substr($struktur, strpos($struktur, " ") + 1);

                if ($r->recid_bag == 0) {
                    $bagian = "-";
                } else {
                    $bagian = $bagian;
                }

                if ($r->recid_jbtn == 0) {
                    $jabatan = "-";
                } else {
                    $jabatan = $jabatan;
                }
                $data[] = array(
                    $no = $no + 1,
                    $r->nik,
                    $r->nama_karyawan,
                    $bagian,
                    $jabatan,
                    $golongan,
                    $r->penempatan,
                    $tgl,
                    "-",
                    "-",
                    "<a href='" . base_url() . "AbsenBarcode/padjust_hadir/" . $r->recid_karyawan . "/" . $tgl . "/1'><button type='button' class='btn btn-info btn-sm'>Adjust</button></a>",
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $absen->num_rows(),
                "recordsFiltered" => $absen->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_adjust_hadir_periode()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $absen = $this->m_absenbarcode->hadir_today($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            }
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($absen->result() as $r) {
                $bagian = $r->indeks_hr;
                $bagian = substr($bagian, strpos($bagian, " ") + 1);

                $jabatan = $r->indeks_jabatan;
                $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                $golongan = $r->nama_golongan;
                $golongan = substr($golongan, strpos($golongan, " ") + 1);

                $struktur = $r->nama_struktur;
                $struktur = substr($struktur, strpos($struktur, " ") + 1);

                if ($r->recid_bag == 0) {
                    $bagian = "-";
                } else {
                    $bagian = $bagian;
                }

                if ($r->recid_jbtn == 0) {
                    $jabatan = "-";
                } else {
                    $jabatan = $jabatan;
                }
                $data[] = array(
                    $no = $no + 1,
                    $r->nik,
                    $r->nama_karyawan,
                    $bagian,
                    $jabatan,
                    $golongan,
                    $r->penempatan,
                    $tgl,
                    $r->keterangan,
                    $r->jam_masuk,
                    $r->jam_keluar,
                    "<a href='" . base_url() . "AbsenBarcode/padjust_hadir/" . $r->recid_karyawan . "/" . $tgl . "/2'><button type='button' class='btn btn-info btn-sm'>Adjust</button></a><a href='" . base_url() . "AbsenBarcode/delete_hadir/" . $r->recid_absen . "'><button type='button' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button></a>",
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $absen->num_rows(),
                "recordsFiltered" => $absen->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_hadir_periode()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $recid_bag = $user->recid_bag;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25' or $role == '34') {
                $absen = $this->m_absenbarcode->hadir_today($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            } else if ($role == '23' or $role == '41') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    //custom role for all produksi = pic produksi (pa dadan 920)
                    $bgn = $this->m_hris->prd_view_by_atasan('920');
                    $bag = array();
                    foreach ($bgn->result() as $bg) {
                        array_push($bag, $bg->recid_bag);
                    }
                    for ($i = 0; $i < count($bag); $i++) {
                        $iindex = $this->m_hris->bagian_by_recid($bag[$i]);
                        foreach ($iindex as $s) {
                            $indeks_hr = $s->indeks_hr;
                            if ($i < (count($bag) - 1)) {
                                $bagian .= "'" . $indeks_hr . "' or b.indeks_hr = ";
                            } else {
                                $bagian .= "'" . $indeks_hr . "'";
                            }
                        }
                    }
                    $bagian .= ")";
                } else {
                    // $bgn = $this->m_hris->bagian_by_pic_str($recid_karyawan);
                    $admin = $this->m_absen->admin_by_bagian($recid_bag);
                    foreach ($admin->result() as $adm) {
                        $adminnya = $adm->recid_karyawan;
                    }
                    $bgn = $this->m_absen->bagian_by_admin($adminnya);
                    $bag = array();
                    foreach ($bgn->result() as $bg) {
                        array_push($bag, $bg->recid_bag);
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
                }
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $absen = $this->m_absenbarcode->hadir_today_bagian($tgl, $bagian);
            }
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($absen->result() as $r) {
                $bagian = $r->indeks_hr;
                $bagian = substr($bagian, strpos($bagian, " ") + 1);

                $jabatan = $r->indeks_jabatan;
                $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                $golongan = $r->nama_golongan;
                $golongan = substr($golongan, strpos($golongan, " ") + 1);

                $struktur = $r->nama_struktur;
                $struktur = substr($struktur, strpos($struktur, " ") + 1);

                if ($r->recid_bag == 0) {
                    $bagian = "-";
                } else {
                    $bagian = $bagian;
                }

                if ($r->recid_jbtn == 0) {
                    $jabatan = "-";
                } else {
                    $jabatan = $jabatan;
                }
                $data[] = array(
                    $no = $no + 1,
                    $r->nik,
                    $r->nama_karyawan,
                    $bagian,
                    $jabatan,
                    $golongan,
                    $r->penempatan,
                    $tgl,
                    $r->jam_masuk,
                    $r->jam_keluar,
                    $r->ket_validasi,
                    $r->keterangan . " - " . $r->jenis,
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $absen->num_rows(),
                "recordsFiltered" => $absen->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_absen_sebelah()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $absen = $this->m_absenbarcode->absen_sebelah($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->absen_sebelah_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $absen = $this->m_absenbarcode->absen_sebelah_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $absen = $this->m_absenbarcode->absen_sebelah_bagian($tgl, $bagian);
            }
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($absen->result() as $r) {
                $bagian = $r->indeks_hr;
                $bagian = substr($bagian, strpos($bagian, " ") + 1);

                $jabatan = $r->indeks_jabatan;
                $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                $golongan = $r->nama_golongan;
                $golongan = substr($golongan, strpos($golongan, " ") + 1);

                $struktur = $r->nama_struktur;
                $struktur = substr($struktur, strpos($struktur, " ") + 1);

                if ($r->recid_bag == 0) {
                    $bagian = "-";
                } else {
                    $bagian = $bagian;
                }

                if ($r->recid_jbtn == 0) {
                    $jabatan = "-";
                } else {
                    $jabatan = $jabatan;
                }
                $data[] = array(
                    $no = $no + 1,
                    $r->nik,
                    $r->nama_karyawan,
                    $bagian,
                    $jabatan,
                    $golongan,
                    $r->penempatan,
                    $tgl,
                    $r->keterangan,
                    $r->jam_masuk,
                    $r->jam_keluar,
                    "<a href='" . base_url() . "AbsenBarcode/padjust_hadir/" . $r->recid_karyawan . "/" . $tgl . "/2'><button type='button' class='btn btn-info btn-sm'>Adjust</button></a><a href='" . base_url() . "AbsenBarcode/delete_hadir/" . $r->recid_absen . "'><button type='button' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></button></a>",
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $absen->num_rows(),
                "recordsFiltered" => $absen->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function dashboard()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $role = $this->session->userdata('role_id');
            $data['bagian'] = $this->m_hris->bagian_view();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $check_usr = $this->m_hris->cek_usr($usr);
            foreach ($check_usr as $key => $cu) {
                $dept_group = $cu->dept_group;
                $recid_bags = $cu->recid_bag;
            }
            // $data['closed'] = $this->m_absen->rekap_closing_admin($tgl, '1');
            // $data['open'] = $this->m_absen->rekap_closing_admin($tgl, '0');
            $non_shift = "(status = '1')";
            $hadir_baros = "(status = '1' or status = '14' or status = '15' or status= '16' )";
            $wfh = "(status = '8')";
            $shift = "(status between 14 and 23)";
            $cuti = "(status ='3' or status = '7' or status = '12')";
            $sakit = "(status = '2')";
            $lainnya = "(status between 4 and 6) or (status between 9 and 11) or (status = 13) or (status = 24) or (status = 25)";
            $hadir_spm = "(status = '22' or status = '23')";
            $tidak_hadir_spm = "(status != '22' and status != '23')";

            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25' or $role == '34') {
                $data['totkar'] = $this->m_absenbarcode->jml_karyawan();
                $data['tothadir'] = $this->m_absenbarcode->jml_all($tgl);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_today($tgl);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status($tgl, $non_shift);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status($tgl, $wfh);
                $data['shift'] = $this->m_absenbarcode->jml_by_status($tgl, $shift);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status($tgl, $cuti);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status($tgl, $sakit);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status($tgl, $lainnya);
                $data['baros'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $hadir_baros);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_spm($tgl);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_spm($tgl, $hadir_spm);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_spm($tgl, $wfh);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_spm($tgl, $tidak_hadir_spm);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $data['totkar'] = $this->m_absenbarcode->jml_karyawan_baros();
                $data['tothadir'] = $this->m_absenbarcode->jml_all_baros($tgl);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_today_baros($tgl);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $non_shift);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $wfh);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $shift);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $cuti);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $sakit);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $lainnya);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $data['totkar'] = $this->m_absenbarcode->jml_bagian($tgl, $bagian);
                $data['tothadir'] = $this->m_absenbarcode->jml_all($tgl);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_today($tgl);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            } else if ($role == '37') {
                $bag = array();
                $bg = $this->m_hris->bagian_by_dept_group($dept_group);
                foreach ($bg as $bgg) {
                    array_push($bag, $bgg->recid_bagian);
                }
                $bagian = "(b.indeks_hr =";
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
                $data['totkar'] = $this->m_absenbarcode->jml_karyawan_baros();
                $data['tothadir'] = $this->m_absenbarcode->jml_all_baros($tgl);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_today_baros($tgl);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $non_shift);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $wfh);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $shift);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $cuti);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $sakit);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $lainnya);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            } else if ($role == '29') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $admin = $this->m_absen->admin_by_bagian($recid_bags);
                foreach ($admin->result() as $adm) {
                    $adminnya = $adm->recid_karyawan;
                }
                $bgn = $this->m_absen->bagian_by_admin($adminnya);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['totkar'] = $this->m_absenbarcode->jml_bagian($bagian);
                $data['tothadir'] = $this->m_absenbarcode->jml_all_bagian($tgl, $bagian);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            } else if ($role == '23' or $role == '41') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    //custom role for all produksi = pic produksi (pa dadan 920)
                    $bgn = $this->m_hris->prd_view_by_atasan('920');
                } else {
                    $bgn = $this->m_hris->bagian_by_pic_str($recid_karyawan);
                }
                // $bgn = $this->m_absen->bagian_by_admin($adminnya);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['totkar'] = $this->m_absenbarcode->jml_bagian($bagian);
                $data['tothadir'] = $this->m_absenbarcode->jml_all_bagian($tgl, $bagian);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['totkar'] = $this->m_absenbarcode->jml_bagian($bagian);
                $data['tothadir'] = $this->m_absenbarcode->jml_all_bagian($tgl, $bagian);
                $data['totblm'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian);
                $data['non_shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                $data['wfh'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                $data['shift'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                $data['cuti'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                $data['sakit'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                $data['lainnya'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);

                $data['totkar_spm'] = $this->m_absenbarcode->jml_bagian_spm($tgl, $bagian);
                $data['hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $hadir_spm, $bagian);
                $data['wfh_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $wfh, $bagian);
                $data['tidak_hadir_spm'] = $this->m_absenbarcode->jml_by_status_bagian_spm($tgl, $tidak_hadir_spm, $bagian);
            }

            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/report/dashboard', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function detail_kehadiran()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->uri->segment(3);
            $status = $this->uri->segment(4);
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['status'] = $status;

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            $non_shift = "(status = '1')";
            $hadir_baros = "(status = '1' or status = '14' or status = '15' or status= '16')";
            $wfh = "(status = '8')";
            $shift = "(status between 14 and 23)";
            $cuti = "(status ='3' or status = '7' or status = '12')";
            $sakit = "(status = '2')";
            $lainnya = "(status between 4 and 6) or (status between 9 and 11) or (status = 13) or (status = 24 or status = 25)";
            $hadir_spm = "(status = '22' or status = '23')";
            $tidak_hadir_spm = "(status != '22' and status != '23')";
            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                if ($status == 'All') {
                    $data['absen'] = $this->m_absenbarcode->jml_all($tgl);
                } else if ($status == "wfh") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $wfh);
                } else if ($status == "shift") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $shift);
                } else if ($status == "cuti") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $cuti);
                } else if ($status == "sakit") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $sakit);
                } else if ($status == "baros") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $hadir_baros);
                } else if ($status == "lainnya") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $lainnya);
                } else {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status($tgl, $non_shift);
                }
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                if ($status == 'All') {
                    $data['absen'] = $this->m_absenbarcode->jml_all_baros($tgl);
                } else if ($status == "wfh") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $wfh);
                } else if ($status == "shift") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $shift);
                } else if ($status == "cuti") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $cuti);
                } else if ($status == "sakit") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $sakit);
                } else if ($status == "lainnya") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $lainnya);
                } else {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_baros($tgl, $non_shift);
                }
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                if ($status == 'All') {
                    $data['absen'] = $this->m_absenbarcode->jml_all_bagian($tgl, $bagian);
                } else if ($status == "wfh") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                } else if ($status == "shift") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                } else if ($status == "cuti") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                } else if ($status == "sakit") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                } else if ($status == "lainnya") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);
                } else {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                }
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                if ($status == 'All') {
                    $data['absen'] = $this->m_absenbarcode->jml_all_bagian($tgl, $bagian);
                } else if ($status == "wfh") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $wfh, $bagian);
                } else if ($status == "shift") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $shift, $bagian);
                } else if ($status == "cuti") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $cuti, $bagian);
                } else if ($status == "sakit") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $sakit, $bagian);
                } else if ($status == "lainnya") {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $lainnya, $bagian);
                } else {
                    $data['absen'] = $this->m_absenbarcode->jml_by_status_bagian($tgl, $non_shift, $bagian);
                }
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/new_report/detail_dashboard', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function list_total_karyawan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $data['menu']    = "Data Karyawan";
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
            if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '31' or $role == '34') {
                $data['karyawan'] = $this->m_absenbarcode->jml_karyawan()->result();
            } else if ($role == '32') {
                $data['karyawan'] = $this->m_absenbarcode->jml_karyawan_baros()->result();
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
            } else if ($role == '23') {    //USER CHEKING ROLE BY JABATAN
                if ($tingkatan > '7')    // ASS-MAN UP
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

    public function total_blm_absen()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->uri->segment(3);
            $data['menu']    = "Tidak Ada Konfirmasi Kehadiran";
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
            if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '31' or $role == '34') {
                $data['karyawan'] = $this->m_absenbarcode->tidak_hadir_today($tgl)->result();
            } else if ($role == '32') {
                $data['karyawan'] = $this->m_absenbarcode->tidak_hadir_today_baros($tgl)->result();
            } else if ($role == '23' or $role == '41') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "indeks_hr = ";
                $recid_karyawan = $this->session->userdata('kar_id');
                if ($role == '41') {
                    //custom role for all produksi = pic produksi (pa dadan 920)
                    $bgn = $this->m_hris->prd_view_by_atasan('920');
                } else {
                    $bgn = $this->m_absen->bagian_by_pic_str($recid_karyawan);
                }
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
                $data['karyawan'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian)->result();
            } else if ($role == '30' or $role == '26' or $role == '35') {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "indeks_hr = ";
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
                $data['karyawan'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian)->result();
            } else {
                // echo $bagian;
                $bagian = "'" . $bagian . "'";
                $data['karyawan'] = $this->m_absenbarcode->tidak_hadir_bagian($tgl, $bagian)->result();
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

    public function rekap_kehadiran()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $data['menu'] = "Rekap Kahadiran Semua Karyawan";
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $recid_bags = $user->recid_bag;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
                $dept_group = $user->dept_group;
            }

            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25') {
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else if ($role == "23" or $role == '41' or $role = '29') {    // user - off up
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                // $bgn = $this->m_hris->bagian_by_karyawan($recid_karyawan);
                $bag = array();
                if ($tingkatan > '7') {
                    if ($role == '41') {
                        //custom role for all produksi = pic produksi (pa dadan 920)
                        $bgn = $this->m_hris->karyawan_view_by_atasan('920');
                    } else {
                        $admin = $this->m_absen->admin_by_bagian($recid_bags);
                        foreach ($admin->result() as $adm) {
                            $adminnya = $adm->recid_karyawan;
                        }
                        $bgn = $this->m_absen->bagian_by_admin($adminnya);
                        // $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
                    }
                    foreach ($bgn->result() as $bg) {
                        if (array_key_exists($bg->recid_bag, $bag)) {
                            // echo "Key exists!";
                        } else {
                            array_push($bag, $bg->recid_bag);
                        }
                    }
                } else {
                    $bgn = $this->m_hris->karyawan_view_by_id($usr);
                    foreach ($bgn as $bg) {
                        if (array_key_exists($bg->recid_bag, $bag)) {
                            // echo "Key exists!";
                        } else {
                            array_push($bag, $bg->recid_bag);
                        }
                    }
                }
                // foreach ($bgn->result() as $bg) {
                // 	array_push($bag, $bg->recid_bag);
                // }
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
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else if ($role == '37') {
                $bag = array();
                $bg = $this->m_hris->bagian_by_deptgroup($dept_group);
                foreach ($bg as $bgg) {
                    array_push($bag, $bgg->recid_bag);
                }
                $bagian = "(b.indeks_hr =";
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
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $data['rekap'] = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            }

            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/new_report/rekap_kehadiran');
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function get_rekap_kehadiran()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = $this->input->post('tgl_mulai');
            // $tgl = "2024-01-02";
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $data['menu'] = "Rekap Kahadiran Semua Karyawan";
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

            if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '25' or $role == '32') {
                $rekap = $this->m_absenbarcode->rekap_kehadiran($tgl);
            } else if ($role == '32') {
                $bag = ['117', '102', '147', '27', '81', '17', '149', '122', '150', '30', '151', '152',  '95', '110',  '125', '148', '137'];
                $bagian = "(b.indeks_hr =";
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
                $rekap = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else if ($role == '31') {    // mega - keamanan {24}
                $bag = ['24'];
                $bagian = "(b.indeks_hr =";
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
                $rekap = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else if ($role == "23") {
                $bagian = "(b.indeks_hr =";
                $bag = array();
                if ($tingkatan > '7') {
                    $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
                    $no = 0;
                    $cnt = $this->m_hris->karyawan_view_by_atasan($usr)->num_rows();
                    foreach ($bgn->result() as $bg) {
                        $no = $no + 1;
                        if (array_key_exists($bg->recid_bag, $bag)) {
                            // echo "Key exists!";
                        } else {
                            array_push($bag, $bg->recid_bag);
                            if ($no < $cnt) {
                                $bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                            } else {
                                $bagian .= "b.indeks_hr = '$bg->indeks_hr'";
                            }
                        }
                    }
                } else {
                    $bgn = $this->m_hris->karyawan_view_by_id($usr);
                    $no = 0;
                    $cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
                    foreach ($bgn->result() as $bg) {
                        $no = $no + 1;
                        if (array_key_exists($bg->recid_bag, $bag)) {
                            // echo "Key exists!";
                        } else {
                            array_push($bag, $bg->recid_bag);
                            if ($no < $cnt) {
                                $bagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                            } else {
                                $bagian .= "b.indeks_hr = '$bg->indeks_hr'";
                            }
                        }
                    }
                }
                $bagian .= ")";
                $rekap = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            } else {
                $recid_login = $this->session->userdata('recid_login');
                $bagian = "(b.indeks_hr =";
                $recid_karyawan = $this->session->userdata('kar_id');
                $bgn = $this->m_absen->bagian_by_admin($recid_karyawan);
                $bag = array();
                foreach ($bgn->result() as $bg) {
                    array_push($bag, $bg->recid_bag);
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
                $rekap = $this->m_absenbarcode->rekap_kehadiran_bagian($tgl, $bagian);
            }
            // echo $bagian;
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $data = [];
            $no = 0;
            foreach ($rekap->result() as $r) {
                $data[] = array(
                    $r->indeks_hr,
                    $r->nama_struktur,
                    $r->nama_department,
                    $r->jml_karyawan,
                    $r->K,
                    $r->SID,
                    $r->C,
                    $r->D,
                    $r->P1,
                    $r->H1,
                    $r->H2,
                    $r->WFH,
                    $r->MS,
                    $r->A,
                    $r->M,
                    $r->P4,
                    $r->TOS,
                    $r->S1,
                    $r->S2,
                    $r->S3,
                    $r->LS1,
                    $r->LS2,
                    $r->SS1,
                    $r->SS2,
                    $r->SS3,
                    $r->SPM1,
                    $r->SPM2,
                    $r->L,
                    $r->KK,
                    $r->MS1,
                    $r->MS2,
                    $r->OT,
                    $r->KR,
                    $r->MS2R,
                    $r->OT1,
                    $r->OT2,
                    $r->OT3,
                    $r->MU,
                    $r->GH,
                    $r->P,
                    $r->MS4,
                );
            }

            $result = array(
                "draw" => $draw,
                "recordsTotal" => $rekap->num_rows(),
                "recordsFiltered" => $rekap->num_rows(),
                "data" => $data
            );

            echo json_encode($result);
            exit();
        } else {
            redirect('Auth/keluar');
        }
    }

    public function r_detail_hadir()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $role = $this->session->userdata('role_id');
            if ($role == '30') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_hadir_adm";
            } else if ($role == '26') {
                $data['dept_group'] = $this->m_absen->dept_group_spm($usr);
                $data['department'] = $this->m_absen->dept_spm($usr);
                $content = "absen/absen_barcode/report/r_hadir_adm";
            } else if ($role == '23') {
                $data['dept_group'] = $this->m_absen->dept_group_user($usr);
                $data['department'] = $this->m_absen->dept_user($usr);
                $content = "absen/absen_barcode/report/r_hadir_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_hadir";
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function report_detail_hadir()
    {

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
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/report_absen', $data);
        $this->load->view('layout/a_footer');
    }

    public function report_detail_hadir_adm()
    {

        $usr = $this->session->userdata('kar_id');
        $role = $this->session->userdata('role_id');
        $mulai =  $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_t = new DateTime($mulai);
        $sampai_t = new DateTime($sampai);
        $cek_usr = $this->m_hris->cek_usr($usr);
        foreach ($cek_usr as $user) {
            $nama = $user->nama_karyawan;
            $bagian = $user->indeks_hr;
            $jabatan = $user->indeks_jabatan;
            $tingkatan = $user->tingkatan;
            $struktur = $user->recid_struktur;
        }

        $fdivisi = array();
        $divisi = $this->input->post('divisi');
        if (!empty($divisi)) {
            for ($i = 0; $i < count($divisi); $i++) {
                array_push($fdivisi, $divisi[$i]);
            }
        } else {
            // $divisi = $this->m_hris->list_dept_group();
            // foreach ($divisi->result() as $dv) {
            // 	array_push($fdivisi, $dv->dept_group);
            if ($role == "23") {
                $dept_group = $this->m_absen->dept_group_user($usr);
                foreach ($dept_group->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
            } else if ($role == "26") {
                $dept_group = $this->m_absen->dept_group_spm();
                foreach ($dept_group->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
            } else {
                $dept_group = $this->m_absen->dept_group_admin($usr);
                foreach ($dept_group->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
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
            $where = "and (d.recid_department =";
            $recid_karyawan = $this->session->userdata('kar_id');
            if ($role == "23") {
                $dpt = $this->m_absen->dept_user($recid_karyawan);
            } else if ($role == "26") {
                $dpt = $this->m_absen->dept_spm();
            } else {
                $dpt = $this->m_absen->dept_admin($recid_karyawan);
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
            $fdepartment = $where;
            // $dept = $this->m_hris->dept_by_divisi_my($where)->result();
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
            // $fbagian = $fbagian;
            $fbagian .= " and (";
            $bag = array();
            if ($tingkatan > '7') {
                $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
                $no = 0;
                $cnt = $this->m_hris->karyawan_view_by_atasan($usr)->num_rows();
                foreach ($bgn->result() as $bg) {
                    $no = $no + 1;
                    if (array_key_exists($bg->recid_bag, $bag)) {
                        // echo "Key exists!";
                    } else {
                        array_push($bag, $bg->recid_bag);
                        if ($no < $cnt) {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                        } else {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                        }
                    }
                }
            } else {
                if ($role == "26") {
                    $bgn = $this->m_absen->bagian_spm();
                    $cnt = $this->m_absen->bagian_spm($usr)->num_rows();
                } else {
                    $bgn = $this->m_hris->karyawan_view_by_id($usr);
                    $cnt = $this->m_hris->karyawan_view_by_id($usr)->num_rows();
                }
                $no = 0;
                foreach ($bgn->result() as $bg) {
                    $no = $no + 1;
                    if (array_key_exists($bg->recid_bag, $bag)) {
                        // echo "Key exists!";
                    } else {
                        array_push($bag, $bg->recid_bag);
                        if ($no < $cnt) {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                        } else {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                        }
                    }
                }
            }
            $fbagian .= ")";
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

        // echo $fbagian;
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $usr = $this->session->userdata('kar_id');
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/report_absen', $data);
        $this->load->view('layout/a_footer');
    }

    public function r_bagian()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            if ($role == '30') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_bagian_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_bagian";
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function persentase_bagian()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai =  $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_t = new DateTime($mulai);
        $sampai_t = new DateTime($sampai);
        $jml_hari = $sampai_t->diff($mulai_t)->days + 1;

        // echo $jml_hari;

        $plushk = $this->m_absen->gh_by_tanggal($mulai, $sampai);
        $gh = $plushk->num_rows();

        $libur = $this->m_absen->cuber_by_tanggal2($mulai, $sampai);
        $minhk = $libur->num_rows();
        $date_start = $mulai;
        $date_end = $sampai;

        $selisih = '86400'; //selisih strtotime untuk satu hari

        $time_start = strtotime($date_start);
        $time_end = strtotime($date_end);
        $sm = 0;
        for ($i = $time_start; $i <= $time_end; $i = $i + $selisih) {
            //pengecekan apakah tanggal tersebut hari sabtu atau minggu 
            if ((date('D', $i) == 'Sun') or (date('D', $i) == 'Sat')) {
                $sm = $sm + 1;
                //$data[] = date('Y-m-d', $i); //hasil dalam bentuk array
            }
        }

        $jml_hk = $jml_hari - $sm - $minhk + $gh;
        // echo $jml_hari." - ".$sm. " - ".$minhk." + ".$gh." = ".$jml_hk;

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

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
            // $bagian = $this->m_hris->list_bagian();
            // $fbagian .= " and (";
            // $no = 0;
            // $cnt = $bagian->num_rows();
            // foreach ($bagian->result() as $d) {
            // 	$no = $no + 1;
            // 	if ($cnt == 1) {
            // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 	} else {
            // 		if ($no + 1 <= $cnt) 
            // 		{
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
            // 		} else {
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 		}
            // 	}
            // }
            // $fbagian .= ")";
        }
        // echo $fbagian;

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persen_absen_bagian', $data);
        $this->load->view('layout/a_footer');
    }

    public function r_minggu()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            if ($role == '30' or $role == '26') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_minggu_adm";
            } else if ($role == '23' or $role == '29') {
                $data['dept_group'] = $this->m_absen->dept_group_user($usr);
                $data['department'] = $this->m_absen->dept_user($usr);
                $content = "absen/absen_barcode/report/r_minggu_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_minggu";
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function persentase_mingguan()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai =  $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_t = $mulai;
        $sampai_t = $sampai;
        // $mulai_t = new DateTime($mulai);
        // $sampai_t = new DateTime($sampai);
        // $jml_hari = $sampai_t->diff($mulai_t)->days + 1;

        // echo $jml_hari;
        /* 
	$plushk = $this->m_absen->gh_by_tanggal($mulai, $sampai);
	$gh = $plushk->num_rows();
	
	$libur = $this->m_absen->cuber_by_tanggal2($mulai, $sampai);
	$minhk = $libur->num_rows();
		$date_start = $mulai;
		$date_end = $sampai;

		$selisih = '86400'; //selisih strtotime untuk satu hari

		$time_start = strtotime($date_start);
		$time_end = strtotime($date_end);
		$sm = 0;
		for ($i = $time_start; $i <= $time_end; $i = $i + $selisih) {
			//pengecekan apakah tanggal tersebut hari sabtu atau minggu 
			if ((date('D', $i) == 'Sun') or (date('D', $i) == 'Sat')) {
				$sm = $sm+1;
				//$data[] = date('Y-m-d', $i); //hasil dalam bentuk array
			}
		}

	$jml_hk = $jml_hari - $sm - $minhk + $gh; */
        $jml_hari = 0;
        while ($mulai_t <= $sampai_t) {
            $hari = date('l', strtotime($mulai_t));
            if ($hari == 'Saturday' or $hari == 'Sunday') {
                // cek weekend ganti hari ot not
                $ganti_hari = $this->m_absen->gh_by_date($mulai_t);
                if ($ganti_hari->num_rows() == 1) {
                    $jml_hari = $jml_hari + 1;
                    // echo "Ganti Hari Di ". $hari . "<br>";
                } else {
                    // echo $hari."<br>";s
                }
            } else {
                $libur = $this->m_absen->libur_by_date($mulai_t);
                if ($libur->num_rows() == 1) {
                    // echo "libur tgl ".$mulai_t."<br>";
                } else {
                    $jml_hari = $jml_hari + 1;
                    // echo $mulai_t . "<br>";
                }
            }
            $mulai_t = date('Y-m-d', strtotime('+1 days', strtotime($mulai_t)));
        }
        // echo $jml_hari;
        $jml_hk = $jml_hari;
        // echo $jml_hk;                                                                        

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

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
            // $bagian = $this->m_hris->list_bagian();
            // $fbagian .= " and (";
            // $no = 0;
            // $cnt = $bagian->num_rows();
            // foreach ($bagian->result() as $d) {
            // 	$no = $no + 1;
            // 	if ($cnt == 1) {
            // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 	} else {
            // 		if ($no + 1 <= $cnt) 
            // 		{
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
            // 		} else {
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 		}
            // 	}
            // }
            // $fbagian .= ")";
        }
        // echo $fbagian;


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

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        // $this->load->view('absen/absen_barcode/report/rekap_persen_absen_mingguan', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persentase_mingguan2', $data);
        $this->load->view('layout/a_footer');
    }

    public function persentase_mingguan_adm()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai =  $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_t = $mulai;
        $sampai_t = $sampai;
        // $jml_hari = $sampai_t->diff($mulai_t)->days + 1;
        $role = $this->session->userdata('role_id');
        $cek_usr = $this->m_hris->cek_usr($usr);
        foreach ($cek_usr as $user) {
            $nama = $user->nama_karyawan;
            $bagian = $user->indeks_hr;
            $jabatan = $user->indeks_jabatan;
            $tingkatan = $user->tingkatan;
            $struktur = $user->recid_struktur;
        }
        // echo $jml_hari;
        $jml_hari = 0;
        while ($mulai_t <= $sampai_t) {
            $hari = date('l', strtotime($mulai_t));
            if ($hari == 'Saturday' or $hari == 'Sunday') {
                // cek weekend ganti hari ot not
                $ganti_hari = $this->m_absen->gh_by_date($mulai_t);
                if ($ganti_hari->num_rows() == 1) {
                    $jml_hari = $jml_hari + 1;
                    // echo "Ganti Hari Di ". $hari . "<br>";
                } else {
                    // echo $hari."<br>";s
                }
            } else {
                $libur = $this->m_absen->libur_by_date($mulai_t);
                if ($libur->num_rows() == 1) {
                    // echo "libur tgl ".$mulai_t."<br>";
                } else {
                    $jml_hari = $jml_hari + 1;
                    // echo $mulai_t . "<br>";
                }
            }
            $mulai_t = date('Y-m-d', strtotime('+1 days', strtotime($mulai_t)));
        }
        // echo $jml_hari;
        $jml_hk = $jml_hari;

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }


        $fdivisi = array();
        if (!empty($divisi)) {
            for ($i = 0; $i < count($divisi); $i++) {
                array_push($fdivisi, $divisi[$i]);
            }
        } else {
            if ($role == "23" or $role == '29') {
                $divisi = $this->m_absen->dept_group_user($usr);
                foreach ($divisi->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
            } else {
                $divisi = $this->m_absen->dept_group_admin($usr);
                foreach ($divisi->result() as $dv) {
                    array_push($fdivisi, $dv->dept_group);
                }
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
            if ($role == "23" or $role == '29') {
                $department = $this->m_absen->dept_user($usr);
            } else {
                $department = $this->m_absen->dept_admin($usr);
            }
            $cnt = $department->num_rows();
            $fdepartment .= " and (";
            foreach ($department->result() as $dept) {
                if ($cnt == 1) {
                    $fdepartment .= "d.nama_department = '$dept->department'";
                } else {
                    if ($i + 1 < $cnt) {
                        $fdepartment .= "d.nama_department = '$dept->department' or ";
                    } else {
                        $fdepartment .= "d.nama_department = '$dept->department'";
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
            // $fbagian = $fbagian;
            $fbagian .= " and (";
            $bag = array();
            if ($tingkatan > '7') {
                $bgn = $this->m_hris->karyawan_view_by_atasan($usr);
                $no = 0;
                $cnt = $this->m_hris->karyawan_view_by_atasan($usr)->num_rows();
                foreach ($bgn->result() as $bg) {
                    $no = $no + 1;
                    if (array_key_exists($bg->recid_bag, $bag)) {
                        // echo "Key exists!";
                    } else {
                        array_push($bag, $bg->recid_bag);
                        if ($no < $cnt) {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                        } else {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                        }
                    }
                }
            } else {
                $bgn = $this->m_hris->karyawan_view_by_id($usr);
                $no = 0;
                foreach ($bgn->result() as $bg) {
                    $no = $no + 1;
                    if (array_key_exists($bg->recid_bag, $bag)) {
                        // echo "Key exists!";
                    } else {
                        array_push($bag, $bg->recid_bag);
                        if ($no < $cnt) {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                        } else {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                        }
                    }
                }
            }
            $fbagian .= ")";
        }
        // echo $fbagian;

        $fkaryawan = "";
        $karyawan = $this->input->post('karyawan');
        if (!empty($karyawan)) {
            $cnt = count($karyawan);
            $fkaryawan .= " and (";
            for ($i = 0; $i < $cnt; $i++) {
                if ($cnt == 1) {
                    $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                } else {
                    if ($i + 1 < $cnt) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                    } else {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                    }
                }
            }
            $fkaryawan .= ")";
        } else {
            $fkaryawan = $fkaryawan;
        }
        // echo $fkaryawan;

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        // $this->load->view('absen/absen_barcode/report/rekap_persen_absen_mingguan', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persentase_mingguan2', $data);
        $this->load->view('layout/a_footer');
    }

    public function r_bulan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            if ($role == '30' or $role == '26') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_bulan_adm";
            } else if ($role == '23' or $role == '29') {
                $data['dept_group'] = $this->m_absen->dept_group_user($usr);
                $data['department'] = $this->m_absen->dept_user($usr);
                $content = "absen/absen_barcode/report/r_bulan_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_bulan";
            }
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function persentase()
    {

        $usr = $this->session->userdata('kar_id');
        $month_name = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $cek = $this->m_absen->cek_hadir_bulan_tahun($bulan, $tahun);
        if ($cek->num_rows() > 0) {
            $norma = array();
            $jnorma = $this->m_absen->jenis_norma('Norma');
            foreach ($jnorma->result() as $n) {
                array_push($norma, $n->recid_jenisabsen);
            }

            $non_norma = array();
            $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
            foreach ($jnonnorma->result() as $nn) {
                array_push($non_norma, $nn->recid_jenisabsen);
            }

            $hk = $this->m_absen->hk_by_bulan($tahun, $bulan);
            foreach ($hk->result() as $hk) {
                $jml_hk = $hk->jml_hk;
            }

            $jml_norma = "( status =";
            $jenis_ab = "( recid_jenisabsen = ";
            for ($n = 0; $n < count($norma); $n++) {
                if ($n < (count($norma) - 1)) {
                    $jml_norma .= "'" . $norma[$n] . "' or status = ";
                    $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
                } else {
                    $jml_norma .= "'" . $norma[$n] . "'";
                    $jenis_ab .= "'" . $norma[$n] . "'";
                }
            }
            $jml_norma .= " )";
            $jenis_ab .= " )";

            $detail_norma = "Norma : ";
            $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
            $cdnorma = $dnorma->num_rows();
            $no = 0;
            foreach ($dnorma->result() as $dn) {
                $no = $no + 1;
                if ($no < $cdnorma) {
                    $detail_norma .= $dn->jenis . ", ";
                } else {
                    $detail_norma .= $dn->jenis;
                }
            }

            $jml_nonnorma = "( status =";
            $jenis_abn = "( recid_jenisabsen = ";

            for ($nn = 0; $nn < count($non_norma); $nn++) {
                if ($nn < (count($non_norma) - 1)) {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                    $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
                } else {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                    $jenis_abn .= "'" . $non_norma[$nn] . "'";
                }
            }
            $jml_nonnorma .= " )";
            $jenis_abn .= " )";

            $detail_nnorma = "Non Norma : ";
            $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
            $cdnnorma = $dnnorma->num_rows();
            $no = 0;
            foreach ($dnnorma->result() as $dnn) {
                $no = $no + 1;
                if ($no < $cdnnorma) {
                    $detail_nnorma .= $dnn->jenis . ", ";
                } else {
                    $detail_nnorma .= $dnn->jenis;
                }
            }

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
                // $bagian = $this->m_hris->list_bagian();
                // $fbagian .= " and (";
                // $no = 0;
                // $cnt = $bagian->num_rows();
                // foreach ($bagian->result() as $d) {
                // 	$no = $no + 1;
                // 	if ($cnt == 1) {
                // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
                // 	} else {
                // 		if ($no + 1 <= $cnt) 
                // 		{
                // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
                // 		} else {
                // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
                // 		}
                // 	}
                // }
                // $fbagian .= ")";
            }
            // echo $fbagian;

            $fkaryawan = "";
            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $cnt = count($karyawan);
                $fkaryawan .= " and (";
                for ($i = 0; $i < $cnt; $i++) {
                    if ($cnt == 1) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                        } else {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                        }
                    }
                }
                $fkaryawan .= ")";
            } else {
                $fkaryawan = $fkaryawan;
            }
            // echo $fkaryawan;

            $data['norma'] = $jml_norma;
            $data['non_norma'] = $jml_nonnorma;
            // $data['dept'] = $this->m_hris->list_dept_group();
            $data['fdivisi'] = $fdivisi;
            $data['divisi'] = $divisi;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['jml_hkn'] = $jml_hk;
            $data['nama_bulan'] = $month_name[$bulan - 1];
            $data['tahun'] = $this->input->post('tahun');
            $data['bulan'] = $this->input->post('bulan');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['detail_norma'] = $detail_norma;
            $data['detail_nnorma'] = $detail_nnorma;


            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/report/rekap_persentase_bulanan', $data);
            $this->load->view('layout/a_footer');
        } else {
            echo "Data Untuk Bulan Ini Belum Ada";
        }
    }

    public function persentase_adm()
    {

        $usr = $this->session->userdata('kar_id');
        $role = $this->session->userdata('role_id');
        $month_name = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $cek_usr = $this->m_hris->cek_usr($usr);
        foreach ($cek_usr as $user) {
            $nama = $user->nama_karyawan;
            $bagian = $user->indeks_hr;
            $jabatan = $user->indeks_jabatan;
            $tingkatan = $user->tingkatan;
            $struktur = $user->recid_struktur;
        }
        $cek = $this->m_absen->cek_hadir_bulan_tahun($bulan, $tahun);
        if ($cek->num_rows() > 0) {
            $norma = array();
            $jnorma = $this->m_absen->jenis_norma('Norma');
            foreach ($jnorma->result() as $n) {
                array_push($norma, $n->recid_jenisabsen);
            }

            $non_norma = array();
            $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
            foreach ($jnonnorma->result() as $nn) {
                array_push($non_norma, $nn->recid_jenisabsen);
            }

            $hk = $this->m_absen->hk_by_bulan($tahun, $bulan);
            foreach ($hk->result() as $hk) {
                $jml_hk = $hk->jml_hk;
            }

            $jml_norma = "( status =";
            $jenis_ab = "( recid_jenisabsen = ";
            for ($n = 0; $n < count($norma); $n++) {
                if ($n < (count($norma) - 1)) {
                    $jml_norma .= "'" . $norma[$n] . "' or status = ";
                    $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
                } else {
                    $jml_norma .= "'" . $norma[$n] . "'";
                    $jenis_ab .= "'" . $norma[$n] . "'";
                }
            }
            $jml_norma .= " )";
            $jenis_ab .= " )";

            $detail_norma = "Norma : ";
            $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
            $cdnorma = $dnorma->num_rows();
            $no = 0;
            foreach ($dnorma->result() as $dn) {
                $no = $no + 1;
                if ($no < $cdnorma) {
                    $detail_norma .= $dn->jenis . ", ";
                } else {
                    $detail_norma .= $dn->jenis;
                }
            }

            $jml_nonnorma = "( status =";
            $jenis_abn = "( recid_jenisabsen = ";

            for ($nn = 0; $nn < count($non_norma); $nn++) {
                if ($nn < (count($non_norma) - 1)) {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                    $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
                } else {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                    $jenis_abn .= "'" . $non_norma[$nn] . "'";
                }
            }
            $jml_nonnorma .= " )";
            $jenis_abn .= " )";

            $detail_nnorma = "Non Norma : ";
            $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
            $cdnnorma = $dnnorma->num_rows();
            $no = 0;
            foreach ($dnnorma->result() as $dnn) {
                $no = $no + 1;
                if ($no < $cdnnorma) {
                    $detail_nnorma .= $dnn->jenis . ", ";
                } else {
                    $detail_nnorma .= $dnn->jenis;
                }
            }


            $fdivisi = array();
            if (!empty($divisi)) {
                for ($i = 0; $i < count($divisi); $i++) {
                    array_push($fdivisi, $divisi[$i]);
                }
            } else {
                if ($role == "23" or $role == '29') {
                    $divisi = $this->m_absen->dept_group_user($usr);
                    foreach ($divisi->result() as $dv) {
                        array_push($fdivisi, $dv->dept_group);
                    }
                } else {
                    $divisi = $this->m_absen->dept_group_admin($usr);
                    foreach ($divisi->result() as $dv) {
                        array_push($fdivisi, $dv->dept_group);
                    }
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
                if ($role == "23" or $role == '29') {
                    $department = $this->m_absen->dept_user($usr);
                } else {
                    $department = $this->m_absen->dept_admin($usr);
                }
                $cnt = $department->num_rows();
                $fdepartment .= " and (";
                foreach ($department->result() as $dept) {
                    if ($cnt == 1) {
                        $fdepartment .= "d.nama_department = '$dept->department'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fdepartment .= "d.nama_department = '$dept->department' or ";
                        } else {
                            $fdepartment .= "d.nama_department = '$dept->department'";
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
                // $fbagian = $fbagian;
                $fbagian .= " and (";
                $bag = array();
                if ($tingkatan > '7') {
                    $bgn = $this->m_hris->bagian_view_by_atasan($usr);
                    $no = 0;
                    $cnt = $this->m_hris->bagian_view_by_atasan($usr)->num_rows();
                    foreach ($bgn->result() as $bg) {
                        $no = $no + 1;
                        array_push($bag, $bg->recid_bag);
                        if ($no < $cnt) {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                        } else {
                            $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                        }
                    }
                } else {
                    $bgn = $this->m_hris->karyawan_view_by_id($usr);
                    $no = 0;
                    foreach ($bgn->result() as $bg) {
                        $no = $no + 1;
                        if (array_key_exists($bg->recid_bag, $bag)) {
                            // echo "Key exists!";
                        } else {
                            array_push($bag, $bg->recid_bag);
                            if ($no < $cnt) {
                                $fbagian .= "b.indeks_hr = '$bg->indeks_hr' or ";
                            } else {
                                $fbagian .= "b.indeks_hr = '$bg->indeks_hr'";
                            }
                        }
                    }
                }
                $fbagian .= ")";
            }
            // echo $fbagian;

            $fkaryawan = "";
            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $cnt = count($karyawan);
                $fkaryawan .= " and (";
                for ($i = 0; $i < $cnt; $i++) {
                    if ($cnt == 1) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                        } else {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                        }
                    }
                }
                $fkaryawan .= ")";
            } else {
                $fkaryawan = $fkaryawan;
            }
            // echo $fkaryawan;

            $data['norma'] = $jml_norma;
            $data['non_norma'] = $jml_nonnorma;
            // $data['dept'] = $this->m_hris->list_dept_group();
            $data['fdivisi'] = $fdivisi;
            $data['divisi'] = $divisi;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['jml_hkn'] = $jml_hk;
            $data['nama_bulan'] = $month_name[$bulan - 1];
            $data['tahun'] = $this->input->post('tahun');
            $data['bulan'] = $this->input->post('bulan');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['detail_norma'] = $detail_norma;
            $data['detail_nnorma'] = $detail_nnorma;


            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/report/rekap_persentase_bulanan', $data);
            $this->load->view('layout/a_footer');
        } else {
            echo "Data Untuk Bulan Ini Belum Ada";
        }
    }

    public function r_semester()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            if ($role == '30') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_semester_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_semester";
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function persentase_semester()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai = $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_tb = explode(" ", $mulai);
        $mulai_t = $mulai_tb[1];
        $mulai_b = $mulai_tb[0];
        $mulai_b = date("m", strtotime($mulai));
        $sampai_tb = explode(" ", $sampai);
        $sampai_t = $sampai_tb[1];
        $sampai_b = $sampai_tb[0];
        $sampai_b = date("m", strtotime($sampai));
        // echo $mulai_t." - ".$sampai_t;

        $where = "";
        if ($mulai_t == $sampai_t) {
            $where = "tahun = $mulai_t and bulan between $mulai_b and $sampai_b";
        } else {
            $where = "(tahun = $mulai_t and bulan between $mulai_b and 12) or (tahun = $sampai_t and bulan between 1 and $sampai_b)";
        }

        // echo $where;
        $jml_hk = $this->m_absen->hk_by_periode($where);
        foreach ($jml_hk->result() as $hk) {
            $jml_hk = $hk->jml_hk;
        }
        // echo $jml_hk;

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }


        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

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
            // $bagian = $this->m_hris->list_bagian();
            // $fbagian .= " and (";
            // $no = 0;
            // $cnt = $bagian->num_rows();
            // foreach ($bagian->result() as $d) {
            // 	$no = $no + 1;
            // 	if ($cnt == 1) {
            // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 	} else {
            // 		if ($no + 1 <= $cnt) 
            // 		{
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
            // 		} else {
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 		}
            // 	}
            // }
            // $fbagian .= ")";
        }
        // echo $fbagian;


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

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai_t'] = $mulai_t;
        $data['sampai_t'] = $sampai_t;
        $data['mulai_b'] = $mulai_b;
        $data['sampai_b'] = $sampai_b;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persen_absen_semester', $data);
        $this->load->view('layout/a_footer');
    }

    public function persentase_semester_adm()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai = $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_tb = explode(" ", $mulai);
        $mulai_t = $mulai_tb[1];
        $mulai_b = $mulai_tb[0];
        $mulai_b = date("m", strtotime($mulai));
        $sampai_tb = explode(" ", $sampai);
        $sampai_t = $sampai_tb[1];
        $sampai_b = $sampai_tb[0];
        $sampai_b = date("m", strtotime($sampai));
        // echo $mulai_t." - ".$sampai_t;

        $where = "";
        if ($mulai_t == $sampai_t) {
            $where = "tahun = $mulai_t and bulan between $mulai_b and $sampai_b";
        } else {
            $where = "(tahun = $mulai_t and bulan between $mulai_b and 12) or (tahun = $sampai_t and bulan between 1 and $sampai_b)";
        }

        // echo $where;
        $jml_hk = $this->m_absen->hk_by_periode($where);
        foreach ($jml_hk->result() as $hk) {
            $jml_hk = $hk->jml_hk;
        }
        // echo $jml_hk;

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

        $fdivisi = array();
        $divisi = $this->m_absen->dept_group_admin($usr);
        foreach ($divisi->result() as $dv) {
            array_push($fdivisi, $dv->dept_group);
        }


        $fdepartment = "";
        $department = $this->m_absen->dept_admin($usr);
        $cnt = $department->num_rows();
        $fdepartment .= " and (";
        $i = 0;
        foreach ($department->result() as $dept) {
            $i = $i + 1;
            if ($cnt == 1) {
                $fdepartment .= "d.nama_department = '$dept->department'";
            } else {
                if ($i + 1 < $cnt) {
                    $fdepartment .= "d.nama_department = '$dept->department' or ";
                } else {
                    $fdepartment .= "d.nama_department = '$dept->department'";
                }
            }
        }
        $fdepartment .= ")";


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
        // echo $fbagian;

        $fkaryawan = "";
        $karyawan = $this->input->post('karyawan');
        if (!empty($karyawan)) {
            $cnt = count($karyawan);
            $fkaryawan .= " and (";
            for ($i = 0; $i < $cnt; $i++) {
                if ($cnt == 1) {
                    $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                } else {
                    if ($i + 1 < $cnt) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                    } else {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                    }
                }
            }
            $fkaryawan .= ")";
        } else {
            $fkaryawan = $fkaryawan;
        }
        // echo $fkaryawan;

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai_t'] = $mulai_t;
        $data['sampai_t'] = $sampai_t;
        $data['mulai_b'] = $mulai_b;
        $data['sampai_b'] = $sampai_b;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persen_absen_semester', $data);
        $this->load->view('layout/a_footer');
    }

    public function r_tahun()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            if ($role == '30') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_tahun_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_tahun";
            }
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function persentase_tahun()
    {

        $usr = $this->session->userdata('kar_id');
        $tahun = $this->input->post('tahun');

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $hk = $this->m_absen->hk_by_tahun($tahun);
        foreach ($hk->result() as $hk) {
            $jml_hk = $hk->jml_hk;
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

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
            // $bagian = $this->m_hris->list_bagian();
            // $fbagian .= " and (";
            // $no = 0;
            // $cnt = $bagian->num_rows();
            // foreach ($bagian->result() as $d) {
            // 	$no = $no + 1;
            // 	if ($cnt == 1) {
            // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 	} else {
            // 		if ($no + 1 <= $cnt) 
            // 		{
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
            // 		} else {
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 		}
            // 	}
            // }
            // $fbagian .= ")";
        }
        // echo $fbagian;


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


        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['tahun'] = $this->input->post('tahun');
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;


        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persentase_tahun', $data);
        $this->load->view('layout/a_footer');
    }

    public function persentase_tahun_adm()
    {

        $usr = $this->session->userdata('kar_id');
        $tahun = $this->input->post('tahun');

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $hk = $this->m_absen->hk_by_tahun($tahun);
        foreach ($hk->result() as $hk) {
            $jml_hk = $hk->jml_hk;
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

        $fdivisi = array();
        $divisi = $this->m_absen->dept_group_admin($usr);
        foreach ($divisi->result() as $dv) {
            array_push($fdivisi, $dv->dept_group);
        }


        $fdepartment = "";
        $department = $this->m_absen->dept_admin($usr);
        $cnt = $department->num_rows();
        $fdepartment .= " and (";
        $i = 0;
        foreach ($department->result() as $dept) {
            $i = $i + 1;
            if ($cnt == 1) {
                $fdepartment .= "d.nama_department = '$dept->department'";
            } else {
                if ($i + 1 < $cnt) {
                    $fdepartment .= "d.nama_department = '$dept->department' or ";
                } else {
                    $fdepartment .= "d.nama_department = '$dept->department'";
                }
            }
        }
        $fdepartment .= ")";


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
        // echo $fbagian;

        $fkaryawan = "";
        $karyawan = $this->input->post('karyawan');
        if (!empty($karyawan)) {
            $cnt = count($karyawan);
            $fkaryawan .= " and (";
            for ($i = 0; $i < $cnt; $i++) {
                if ($cnt == 1) {
                    $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                } else {
                    if ($i + 1 < $cnt) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                    } else {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                    }
                }
            }
            $fkaryawan .= ")";
        } else {
            $fkaryawan = $fkaryawan;
        }

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['tahun'] = $this->input->post('tahun');
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;


        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        $this->load->view('absen/absen_barcode/report/rekap_persentase_tahun', $data);
        $this->load->view('layout/a_footer');
    }

    public function adjust_hadir()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tgl = date('Y-m-d');
            $role = $this->session->userdata('role_id');
            $data['jenis'] = $this->m_absen->jenis_absen();
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }

            $data['absen'] = $this->m_absenbarcode->hadir_today($tgl);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/kehadiran/adjust_kehadiran', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function padjust_hadir()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $recid_karyawan = $this->uri->segment(3);
            $tgl = $this->uri->segment(4);
            $tipe = $this->uri->segment(5);
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['bagian'] = $this->m_hris->bagian_view();

            $cek_usr = $this->m_hris->cek_usr($usr);
            foreach ($cek_usr as $user) {
                $nama = $user->nama_karyawan;
                $bagian = $user->indeks_hr;
                $jabatan = $user->indeks_jabatan;
                $tingkatan = $user->tingkatan;
                $struktur = $user->recid_struktur;
            }
            if ($tipe == 1) {
                $data['kehadiran'] = $this->m_absenbarcode->karyawan_by_recid($recid_karyawan);
            } else {
                $data['kehadiran'] = $this->m_absenbarcode->data_adjust($recid_karyawan, $tgl);
            }
            $data['tgl'] = $tgl;
            $data['tipe'] = $tipe;
            $data['jenis_kehadiran'] = $this->m_absen->jenis_masuk();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/kehadiran/form_adjust', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function proses_adjust()
    {
        $recid_absen = $this->input->post('recid_absen');
        echo $recid_absen;
        $keterangan = $this->input->post('keterangan');
        $jenis_absen = $this->input->post('jenis_absen');
        $tgl = $this->input->post('tgl');
        $tgl_pulang = $this->input->post('tgl_pulang');
        $jam_masuk = $this->input->post('jam_masuk');
        $jam_keluar = $this->input->post('jam_keluar');
        // echo "jam masuk : " . $jam_masuk;

        if (empty($jam_keluar)) {
            $jam_keluar = null;
        } else {
            $jam_keluar = str_replace(" ", "", $jam_keluar);
        }

        if (empty($jam_masuk)) {
            $jam_masuk = null;
        } else {
            $jam_masuk = str_replace(" ", "", $jam_masuk);
        }

        if (!empty($recid_absen)) {
            echo "adjust -" . $recid_absen;
            if ($jenis_absen == '16' or $jenis_absen == '18' or $jenis_absen == '21' or $jenis_absen == '33') {
                // shift malam, tgl pulang h+1 dari tanggal kerja
                $tgl_pulang = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
            } else {
                // tgl pulang = tgl kerja
                $tgl_pulang = $tgl_pulang;
            }

            // $jam_masuk = $this->input->post('jam_masuk');
            // if ($jam_masuk != "") {
            //     $jam_masuk =  str_replace(" ", "", $this->input->post('jam_masuk'));
            // }

            $data = array(
                'mdf_date'          => date('Y-m-d H:i:s'),
                'mdf_by'            => $this->session->userdata('kar_id'),
                'tanggal'           => $this->input->post('tgl'),
                'tgl_masuk'         => $this->input->post('tgl'),
                'tgl_pulang'        => $tgl_pulang,
                'jam_masuk'         => $jam_masuk,
                'jam_keluar'        => $jam_keluar,
                'lokasi_masuk'      => 'Industri',
                'lokasi_pulang'     => 'Industri',
                'status'            => $jenis_absen,
                'ket_validasi'      => $this->input->post('keterangan')
            );
            $this->m_absenbarcode->update_hadir($data, $recid_absen);
        } else {
            // echo "simpan";
            if ($jenis_absen == '16' or $jenis_absen == '18' or $jenis_absen == '21' or $jenis_absen == '33') {
                // shift malam, tgl pulang h+1 dari tanggal kerja
                $tgl_pulang = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
            } else {
                // tgl pulang = tgl kerja
                $tgl_pulang = $tgl_pulang;
            }
            // $jam_masuk = $this->input->post('jam_masuk');
            // if ($jam_masuk != "") {
            //     str_replace(" ", "", $this->input->post('jam_masuk'));
            // }
            $data = array(
                'crt_date'          => date('Y-m-d H:i:s'),
                'crt_by'            => $this->session->userdata('kar_id'),
                'recid_karyawan'    => $this->input->post('recid_karyawan'),
                'tanggal'           => $this->input->post('tgl'),
                'tgl_masuk'         => $this->input->post('tgl'),
                'tgl_pulang'        => $tgl_pulang,
                'jam_masuk'         => $jam_masuk,
                'jam_keluar'        => $jam_keluar,
                'lokasi_masuk'      => 'Industri',
                'lokasi_pulang'     => 'Industri',
                'status'            => $jenis_absen,
                'ket_validasi'      => $this->input->post('keterangan')
            );
            $this->m_absenbarcode->save_absen_masuk($data);
        }
        // echo $tgl_pulang;
        redirect('AbsenBarcode/adjust_hadir');
    }

    public function absen_pabsen()
    {
        $nama = "";
        $start_cuti = "";
        $exp_cuti = "";
        //(1b) lakukan proses absensi
        $recid_karyawan = $this->input->post('recid_karyawan');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_selesai = $this->input->post('tgl_selesai');
        $jenis_absen = $this->input->post('jenis_absen');
        $diagnosa = $this->input->post('diagnosa');
        $kategori = $this->input->post('kategori');
        $ket_sakit = $this->input->post('ket_sakit');
        $note = $this->input->post('note');
        $cuti_thn_ke = $this->input->post('cuti_thn_ke');
        $cek_jam = $this->m_absen->jenis_absen_id($jenis_absen);

        foreach ($cek_jam->result() as $j) {
            $jam_masuk = $j->jam_in;
            $jam_pulang = $j->jam_out;
        }
        // echo $jam_pulang;

        // echo $recid_karyawan;
        //(2) while (tgl_mulai <= tgl_selesai)
        while ($tgl_mulai <= $tgl_selesai) {
            //(3) cek weekend
            $nameOfDay = date('D', strtotime($tgl_mulai));

            //jika weekend
            if ($nameOfDay == 'Sat' or $nameOfDay == 'Sun') {
                //(3a) cek ganti hari
                $gh = $this->m_absen->gh_by_date($tgl_mulai);
                //jika ganti hari
                if ($gh->num_rows() >= 1) {
                    $this->input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_thn_ke);
                    $cek_kehadirann = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_mulai);
                    // echo $cek_kehadirann->num_rows();
                    if ($cek_kehadirann->num_rows() > 0) {
                        $edit_hadir = array(
                            'mdf_by'            => $this->session->userdata('kar_id'),
                            'mdf_date'            => date('Y-m-d h:i:s'),
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'            => $tgl_mulai,
                            'jam_masuk'            => $jam_masuk,
                            'jam_keluar'        => $jam_pulang,
                            'status'            => $jenis_absen,
                        );
                        $this->m_absenbarcode->update_hadir2($recid_karyawan, $tgl_mulai, $edit_hadir);
                    } else {
                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $this->session->userdata('kar_id'),
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => $tgl_mulai,
                            'tgl_masuk'          => $tgl_mulai,
                            'jam_masuk'         => $jam_masuk,
                            'jam_keluar'        => $jam_pulang,
                            'status'            => $jenis_absen,
                            'lokasi_masuk'      => 'Industri',
                            'lokasi_pulang'      => 'Industri',
                            'tmp_status'        => $jenis_absen,
                            'perlu_validasi'    => '0',
                            'ket_validasi'      => 'By Admin',
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                    }
                }
                //jika bukan ganti hari /normal libur
                else {
                    //skip
                    echo "lanjut tanggal berikutnya";
                }
            }
            //jika bukan weekend
            else {
                //(3b) cek cuti bersama / holiday
                $query = $this->m_absen->cek_cuber($tgl_mulai);
                //jika libur 
                if ($query->num_rows() > 0) {
                    //skip
                }
                //jika bukan hari libur
                else {
                    $this->input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_thn_ke);
                    //(4) Edit Kehadirannya
                    $cek_kehadirann = $this->m_absenbarcode->cek_double($recid_karyawan, $tgl_mulai);
                    // echo $cek_kehadirann->num_rows();
                    if ($cek_kehadirann->num_rows() > 0) {
                        $edit_hadir = array(
                            'mdf_by'            => $this->session->userdata('kar_id'),
                            'mdf_date'            => date('Y-m-d h:i:s'),
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'            => $tgl_mulai,
                            'jam_masuk'            => $jam_masuk,
                            'jam_keluar'        => $jam_pulang,
                            'status'            => $jenis_absen,
                        );
                        $this->m_absenbarcode->update_hadir2($recid_karyawan, $tgl_mulai, $edit_hadir);
                    } else {
                        $data = array(
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'crt_by'            => $this->session->userdata('kar_id'),
                            'recid_karyawan'    => $recid_karyawan,
                            'tanggal'           => $tgl_mulai,
                            'tgl_masuk'          => $tgl_mulai,
                            'jam_masuk'         => $jam_masuk,
                            'jam_keluar'        => $jam_pulang,
                            'status'            => $jenis_absen,
                            'lokasi_masuk'      => 'Industri',
                            'lokasi_pulang'      => 'Industri',
                            'tmp_status'        => $jenis_absen,
                            'perlu_validasi'    => '0',
                            'ket_validasi'      => 'By Admin',
                        );
                        $this->m_absenbarcode->save_absen_masuk($data);
                    }
                }
            }
            //tgl_mulai = tgl_mulai + 1;
            $tgl_mulai = date('Y-m-d', strtotime('+1 days', strtotime($tgl_mulai))); // counter while
        }
        //(5) jika jenisnya cuti, tampilkan sisa cuti
        if ($jenis_absen == 3) {
            $sisa_cuti = $this->m_absen->cek_sisa_cuti_kar($recid_karyawan);
            foreach ($sisa_cuti->result() as $sc) {
                $nama = $sc->nama_karyawan;
                $jml_cuti = $sc->jml_cuti;
            }
            $notif_p1 = 'Sisa Cuti ' . $nama . " : " . $jml_cuti;
            // $this->session->set_flashdata('sukses','Sisa Cuti '.$nama." : ".$jml_cuti);

            $cek_cuti = $this->m_absen->sisa_cuti_active($recid_karyawan);
            foreach ($cek_cuti->result() as $c) {
                $start_cuti = $c->tgl_mulai;
                $exp_cuti = date('Y-m-d', strtotime('+1 year', strtotime($start_cuti)));
                // echo $start_cuti;
            }
            $ntf = 'sukses';
            $cek_p1 = $this->m_absenbarcode->cek_p1_periode($recid_karyawan, $start_cuti, $exp_cuti);
            if ($cek_p1->num_rows() >= 2) {
                $ntf = 'warning';
                $notif_p1 .=  "<br> " . $nama . ' Sudah P1 (Izin Diluar Cuti) ' . $cek_p1->num_rows() . " x ";
            }
            $this->session->set_flashdata($ntf, $notif_p1);
        }

        redirect('Absen/absen_view');
    }

    public function input_data_absen($recid_karyawan, $tgl_mulai, $jenis_absen, $diagnosa, $kategori, $ket_sakit, $note, $cuti_thn_ke)
    {
        //(1) cek datanya udah ada atau belum
        $cek = $this->m_absen->cek_absensi($recid_karyawan, $tgl_mulai);
        //jika data sudah ada
        if ($cek->num_rows() > 0)
        // -------------- BLOK EDIT DATA -----------------
        {
            $tgl_work = $tgl_mulai;
            //(2) jika data barunya Kerja atau Dinas Luar
            if ($jenis_absen == '1' or $jenis_absen == '4') {
                $is_delete = '1';
                $validasi_cuti = '';
                //(3) Delete Absensi, karena dianggap hadir
                foreach ($cek->result() as $c) {
                    $data_absen = array(
                        'is_delete'        => $is_delete,
                        'mdf_by'        => $this->session->userdata('kar_id'),
                        'mdf_date'        => date('Y-m-d h:i:s'),
                    );
                    $this->m_absen->edit_absensi_recid($c->absensi_recid, $data_absen);
                }
                //(4) kembalikan sisa cuti
                $cek_cuti = $this->m_absen->sisa_cuti_active($recid_karyawan);
                //(6) Kembali cuti 1
                foreach ($cek_cuti->result() as $c) {
                    $recid_cuti = $c->recid_cuti;
                    $jml_cuti = $c->jml_cuti;
                    // echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
                    $sisa = $jml_cuti + 1;
                    echo "ditambah 1 jadi sisa : " . $sisa . "<br><br>";

                    $data_cuti = array(
                        'jml_cuti'        => $sisa,
                        'mdf_by'        => $this->session->userdata('kar_id'),
                        'mdf_date'        => date('Y-m-d h:i:s'),
                    );
                    $this->m_absen->edit_cukar($data_cuti, $recid_cuti);
                }
            }
            //(2) Atau jika data barunya bukan SID
            else if ($jenis_absen != '2') {
                $is_delete = '0';
                //(3) Field untuk detail SID di kosongkan
                foreach ($cek->result() as $c) {
                    $jenis_absen_lama = $c->jenis_absen;
                    $valid_lama = $c->validasi_cuti;
                }

                $diagnosa = '';
                $kategori = '';
                $ket_sakit = '';

                //(4) Jika absen lamanya cuti dan sudah divalidasi
                if ($jenis_absen_lama == '3' && $valid_lama == '1') {
                    //(5) flag sudah validasi, tidak potong cuti
                    $validasi_cuti = '1';
                }
                //(4) Jika absen lamanya cuti dan belum divaliadsi
                else if ($jenis_absen == '3' and ($valid_lama == '0' or $valid_lama == null)) {
                    //(5)flag sudah validasi
                    $validasi_cuti = '1';
                    //(6) cek cuti karyawan
                    $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                    //jika masih ada cuti
                    if ($cek_cuti->num_rows() > 0) {
                        //(7) potong cuti karyawan
                        foreach ($cek_cuti->result() as $c) {
                            $recid_cuti = $c->recid_cuti;
                            echo "jumlah cuti sebelumnya : " . $c->jml_cuti . "<br>";
                            $sisa = $c->jml_cuti - 1;
                            echo "dikurang 1 jadi sisa : " . $sisa . "<br><br>";

                            $data_cuti = array(
                                'jml_cuti'        => $sisa,
                                'mdf_by'        => $this->session->userdata('kar_id'),
                                'mdf_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->edit_cukar($data_cuti, $recid_cuti);
                        }
                    }
                    //jika cuti habis
                    else {
                        $jenis_absen = '5';    //P1
                        //(7) edit absen sebagai P1

                    }
                }
                //(4) Atau edit absen normal, validasi = ''
                else {
                    $validasi_cuti = '';
                }
            }
            //(2) Atau edit absen normal, validasi = ''
            else {
                $validasi_cuti = '';
                $is_delete = '0';
            }

            //(...)array edit absensi
            $data_edit = array(
                'jenis_absen'    => $jenis_absen,
                'diagnosa'        => $diagnosa,
                'kategori'        => $kategori,
                'ket_sakit'        => $ket_sakit,
                'keterangan'    => $note,
                'cuti_ke'        => $cuti_thn_ke,
                'validasi_cuti'    => $validasi_cuti,
                'is_delete'    => $is_delete,
                'mdf_by'        => $this->session->userdata('kar_id'),
                'mdf_date'        => date('Y-m-d h:i:s'),
            );
            $this->m_absen->edit_absensi($recid_karyawan, $tgl_work, $data_edit);
        }
        //jika data belum ada
        else
        // ------------- BLOK SIMPAN DATA NORMAL -----------
        {
            //(2) jika bukan SID
            if ($jenis_absen != '2') {
                //Field untuk detail SID di kosongkan
                $diagnosa = '';
                $kategori = '';
                $ket_sakit = '';

                //(3) Jika absennya Cuti 
                if ($jenis_absen == '3') {
                    //(4) flag sudah validasi
                    $validasi_cuti = '1';
                    //(5) cek cuti karyawan
                    $cek_cuti = $this->m_absen->cek_cuti_idkar_sisa($recid_karyawan);
                    //jika cuti masih ada
                    if ($cek_cuti->num_rows() > 0) {
                        //(6) Potong cuti
                        foreach ($cek_cuti->result() as $c) {
                            $recid_cuti = $c->recid_cuti;
                            $jml_cuti = $c->jml_cuti;
                            // echo "jumlah cuti sebelumnya : ".$c->jml_cuti."<br>";
                            $sisa = $jml_cuti - 1;
                            echo "dikurang 1 jadi sisa : " . $sisa . "<br><br>";

                            $data_cuti = array(
                                'jml_cuti'        => $sisa,
                                'mdf_by'        => $this->session->userdata('kar_id'),
                                'mdf_date'        => date('Y-m-d h:i:s'),
                            );
                            $this->m_absen->edit_cukar($data_cuti, $recid_cuti);
                        }
                    }
                    //jika cuti habis
                    else {
                        //(6) jenis cuti = p1
                        $jenis_absen = '5';
                    }
                } else {
                    $validasi_cuti = '0';
                }
            }
            //jika SID
            else {
                //Field SID sesuai inputan
                $diagnosa = $this->input->post('diagnosa');
                $kategori = $this->input->post('kategori');
                $ket_sakit = $this->input->post('ket_sakit');
                $validasi_cuti = '1';
            }
            //(...) Simpan Data Cuti
            $data_absen = array(
                'recid_karyawan'    => $recid_karyawan,
                'tanggal'        => $tgl_mulai,
                'jenis_absen'    => $jenis_absen,
                'diagnosa'        => $diagnosa,
                'kategori'        => $kategori,
                'ket_sakit'        => $ket_sakit,
                'keterangan'    => $note,
                'cuti_ke'        => $cuti_thn_ke,
                'validasi_cuti'    => $validasi_cuti,
                'crt_by'        => $this->session->userdata('kar_id'),
                'crt_date'        => date('Y-m-d h:i:s'),
            );
            $this->m_absen->save_absensi($data_absen);
        }
    }

    function delete_hadir()
    {
        $id = $this->uri->segment(3);
        $data = array(
            'mdf_date'          => date('Y-m-d H:i:s'),
            'mdf_by'            => $this->session->userdata('kar_id'),
            'ket_validasi'      => 'Salah Link / Perbaikan Data',
            'is_closed'         => '1'
        );
        $this->m_absenbarcode->update_hadir($data, $id);
        redirect('AbsenBarcode/adjust_hadir');
    }

    public function r_produktif_bulanan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            if ($role == '30' or $role == '26') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_produktif_bulanan_adm";
            } else if ($role == '23' or $role == '29') {
                $data['dept_group'] = $this->m_absen->dept_group_user($usr);
                $data['department'] = $this->m_absen->dept_user($usr);
                $content = "absen/absen_barcode/report/r_produktif_bulanan_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_produktif_bulan";
            }
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function produktivitas_bulanan()
    {
        $usr = $this->session->userdata('kar_id');
        $month_name = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $cek = $this->m_absen->cek_hadir_bulan_tahun($bulan, $tahun);
        if ($cek->num_rows() > 0) {
            $norma = array();
            $jnorma = $this->m_absen->jenis_norma('Norma');
            foreach ($jnorma->result() as $n) {
                array_push($norma, $n->recid_jenisabsen);
            }

            $non_norma = array();
            $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
            foreach ($jnonnorma->result() as $nn) {
                array_push($non_norma, $nn->recid_jenisabsen);
            }

            $hk = $this->m_absen->hk_by_bulan($tahun, $bulan);
            foreach ($hk->result() as $hk) {
                $jml_hk = $hk->jml_hk;
            }

            $jml_norma = "( status =";
            $jenis_ab = "( recid_jenisabsen = ";
            for ($n = 0; $n < count($norma); $n++) {
                if ($n < (count($norma) - 1)) {
                    $jml_norma .= "'" . $norma[$n] . "' or status = ";
                    $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
                } else {
                    $jml_norma .= "'" . $norma[$n] . "'";
                    $jenis_ab .= "'" . $norma[$n] . "'";
                }
            }
            $jml_norma .= " )";
            $jenis_ab .= " )";

            $detail_norma = "Norma : ";
            $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
            $cdnorma = $dnorma->num_rows();
            $no = 0;
            foreach ($dnorma->result() as $dn) {
                $no = $no + 1;
                if ($no < $cdnorma) {
                    $detail_norma .= $dn->jenis . ", ";
                } else {
                    $detail_norma .= $dn->jenis;
                }
            }

            $jml_nonnorma = "( status =";
            $jenis_abn = "( recid_jenisabsen = ";

            for ($nn = 0; $nn < count($non_norma); $nn++) {
                if ($nn < (count($non_norma) - 1)) {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                    $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
                } else {
                    $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                    $jenis_abn .= "'" . $non_norma[$nn] . "'";
                }
            }
            $jml_nonnorma .= " )";
            $jenis_abn .= " )";

            $detail_nnorma = "Non Norma : ";
            $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
            $cdnnorma = $dnnorma->num_rows();
            $no = 0;
            foreach ($dnnorma->result() as $dnn) {
                $no = $no + 1;
                if ($no < $cdnnorma) {
                    $detail_nnorma .= $dnn->jenis . ", ";
                } else {
                    $detail_nnorma .= $dnn->jenis;
                }
            }

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
                // $bagian = $this->m_hris->list_bagian();
                // $fbagian .= " and (";
                // $no = 0;
                // $cnt = $bagian->num_rows();
                // foreach ($bagian->result() as $d) {
                // 	$no = $no + 1;
                // 	if ($cnt == 1) {
                // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
                // 	} else {
                // 		if ($no + 1 <= $cnt) 
                // 		{
                // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
                // 		} else {
                // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
                // 		}
                // 	}
                // }
                // $fbagian .= ")";
            }
            // echo $fbagian;

            $fkaryawan = "";
            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $cnt = count($karyawan);
                $fkaryawan .= " and (";
                for ($i = 0; $i < $cnt; $i++) {
                    if ($cnt == 1) {
                        $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
                    } else {
                        if ($i + 1 < $cnt) {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
                        } else {
                            $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
                        }
                    }
                }
                $fkaryawan .= ")";
            } else {
                $fkaryawan = $fkaryawan;
            }
            // echo $fkaryawan;

            $data['norma'] = $jml_norma;
            $data['non_norma'] = $jml_nonnorma;
            // $data['dept'] = $this->m_hris->list_dept_group();
            $data['fdivisi'] = $fdivisi;
            $data['divisi'] = $divisi;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['jml_hkn'] = $jml_hk;
            $data['nama_bulan'] = $month_name[$bulan - 1];
            $data['tahun'] = $this->input->post('tahun');
            $data['bulan'] = $this->input->post('bulan');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['detail_norma'] = $detail_norma;
            $data['detail_nnorma'] = $detail_nnorma;


            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('absen/absen_barcode/report/rekap_produktivitas_bulan', $data);
            $this->load->view('layout/a_footer');
        } else {
            echo "Data Untuk Bulan Ini Belum Ada";
        }
    }

    public function r_produktif_mingguan()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $role = $this->session->userdata('role_id');
            if ($role == '30' or $role == '26') {
                $data['dept_group'] = $this->m_absen->dept_group_admin($usr);
                $data['department'] = $this->m_absen->dept_admin($usr);
                $content = "absen/absen_barcode/report/r_produktif_mingguan_adm";
            } else if ($role == '23' or $role == '29') {
                $data['dept_group'] = $this->m_absen->dept_group_user($usr);
                $data['department'] = $this->m_absen->dept_user($usr);
                $content = "absen/absen_barcode/report/r_produktif_mingguan_adm";
            } else {
                $data['dept_group'] = $this->m_hris->list_dept_group();
                $data['department'] = $this->m_hris->department_view();
                $content = "absen/absen_barcode/report/r_produktif_mingguan";
            }
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['jenis'] = $this->m_absen->jenis_tidak_masuk();
            $data['tahun'] = $this->m_absen->tahun_hk();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view($content);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function produktivitas_mingguan()
    {

        $usr = $this->session->userdata('kar_id');
        $mulai =  $this->input->post('sejak');
        $sampai = $this->input->post('sampai');
        $mulai_t = $mulai;
        $sampai_t = $sampai;
        // $mulai_t = new DateTime($mulai);
        // $sampai_t = new DateTime($sampai);
        // $jml_hari = $sampai_t->diff($mulai_t)->days + 1;

        // echo $jml_hari;
        /* 
	$plushk = $this->m_absen->gh_by_tanggal($mulai, $sampai);
	$gh = $plushk->num_rows();
	
	$libur = $this->m_absen->cuber_by_tanggal2($mulai, $sampai);
	$minhk = $libur->num_rows();
		$date_start = $mulai;
		$date_end = $sampai;

		$selisih = '86400'; //selisih strtotime untuk satu hari

		$time_start = strtotime($date_start);
		$time_end = strtotime($date_end);
		$sm = 0;
		for ($i = $time_start; $i <= $time_end; $i = $i + $selisih) {
			//pengecekan apakah tanggal tersebut hari sabtu atau minggu 
			if ((date('D', $i) == 'Sun') or (date('D', $i) == 'Sat')) {
				$sm = $sm+1;
				//$data[] = date('Y-m-d', $i); //hasil dalam bentuk array
			}
		}

	$jml_hk = $jml_hari - $sm - $minhk + $gh; */
        $jml_hari = 0;
        while ($mulai_t <= $sampai_t) {
            $hari = date('l', strtotime($mulai_t));
            if ($hari == 'Saturday' or $hari == 'Sunday') {
                // cek weekend ganti hari ot not
                $ganti_hari = $this->m_absen->gh_by_date($mulai_t);
                if ($ganti_hari->num_rows() == 1) {
                    $jml_hari = $jml_hari + 1;
                    // echo "Ganti Hari Di ". $hari . "<br>";
                } else {
                    // echo $hari."<br>";s
                }
            } else {
                $libur = $this->m_absen->libur_by_date($mulai_t);
                if ($libur->num_rows() == 1) {
                    // echo "libur tgl ".$mulai_t."<br>";
                } else {
                    $jml_hari = $jml_hari + 1;
                    // echo $mulai_t . "<br>";
                }
            }
            $mulai_t = date('Y-m-d', strtotime('+1 days', strtotime($mulai_t)));
        }
        // echo $jml_hari;
        $jml_hk = $jml_hari;
        // echo $jml_hk;                                                                        

        $norma = array();
        $jnorma = $this->m_absen->jenis_norma('Norma');
        foreach ($jnorma->result() as $n) {
            array_push($norma, $n->recid_jenisabsen);
        }

        $non_norma = array();
        $jnonnorma = $this->m_absen->jenis_norma('Non Norma');
        foreach ($jnonnorma->result() as $nn) {
            array_push($non_norma, $nn->recid_jenisabsen);
        }

        $jml_norma = "( status =";
        $jenis_ab = "( recid_jenisabsen = ";
        for ($n = 0; $n < count($norma); $n++) {
            if ($n < (count($norma) - 1)) {
                $jml_norma .= "'" . $norma[$n] . "' or status = ";
                $jenis_ab .= "'" . $norma[$n] . "' or recid_jenisabsen = ";
            } else {
                $jml_norma .= "'" . $norma[$n] . "'";
                $jenis_ab .= "'" . $norma[$n] . "'";
            }
        }
        $jml_norma .= " )";
        $jenis_ab .= " )";

        $detail_norma = "Norma : ";
        $dnorma = $this->m_absen->jenis_absen_status($jenis_ab);
        $cdnorma = $dnorma->num_rows();
        $no = 0;
        foreach ($dnorma->result() as $dn) {
            $no = $no + 1;
            if ($no < $cdnorma) {
                $detail_norma .= $dn->jenis . ", ";
            } else {
                $detail_norma .= $dn->jenis;
            }
        }

        $jml_nonnorma = "( status =";
        $jenis_abn = "( recid_jenisabsen = ";

        for ($nn = 0; $nn < count($non_norma); $nn++) {
            if ($nn < (count($non_norma) - 1)) {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "' or status = ";
                $jenis_abn .= "'" . $non_norma[$nn] . "' or recid_jenisabsen = ";
            } else {
                $jml_nonnorma .= "'" . $non_norma[$nn] . "'";
                $jenis_abn .= "'" . $non_norma[$nn] . "'";
            }
        }
        $jml_nonnorma .= " )";
        $jenis_abn .= " )";

        $detail_nnorma = "Non Norma : ";
        $dnnorma = $this->m_absen->jenis_absen_status($jenis_abn);
        $cdnnorma = $dnnorma->num_rows();
        $no = 0;
        foreach ($dnnorma->result() as $dnn) {
            $no = $no + 1;
            if ($no < $cdnnorma) {
                $detail_nnorma .= $dnn->jenis . ", ";
            } else {
                $detail_nnorma .= $dnn->jenis;
            }
        }

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
            // $bagian = $this->m_hris->list_bagian();
            // $fbagian .= " and (";
            // $no = 0;
            // $cnt = $bagian->num_rows();
            // foreach ($bagian->result() as $d) {
            // 	$no = $no + 1;
            // 	if ($cnt == 1) {
            // 		$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 	} else {
            // 		if ($no + 1 <= $cnt) 
            // 		{
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr' or ";
            // 		} else {
            // 			$fbagian .= "b.indeks_hr = '$d->indeks_hr'";
            // 		}
            // 	}
            // }
            // $fbagian .= ")";
        }
        // echo $fbagian;


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

        $data['norma'] = $jml_norma;
        $data['non_norma'] = $jml_nonnorma;
        // $data['dept'] = $this->m_hris->list_dept_group();
        $data['fdivisi'] = $fdivisi;
        $data['divisi'] = $divisi;
        $data['fdepartment'] = $fdepartment;
        $data['fbagian'] = $fbagian;
        $data['fkaryawan'] = $fkaryawan;
        $data['jml_hkn'] = $jml_hk;
        $data['mulai'] = $mulai;
        $data['sampai'] = $sampai;
        $data['cek_usr'] = $this->m_hris->cek_usr($usr);
        $data['detail_norma'] = $detail_norma;
        $data['detail_nnorma'] = $detail_nnorma;

        $this->load->view('layout/a_header');
        $this->load->view('layout/menu_super', $data);
        // $this->load->view('absen/absen_barcode/report/rekap_persen_absen_mingguan', $data);
        $this->load->view('absen/absen_barcode/report/rekap_produktif_minggu', $data);
        $this->load->view('layout/a_footer');
    }
}

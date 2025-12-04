<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Upah extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('m_absen', 'm_hris', 'm_absenbarcode', 'm_lembur'));
        // ini_set('max_execution_time', 600);

        ob_start(); # add this
    }

    public function kalkulasi_upah()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $paygroup = $this->m_hris->paygroup_emp();
            $tipe = "2"; // 1 simpan, 2 download, 3 simpan & download;
            $tgl_mulai = "2024-04-19";
            $tgl_akhir = "2024-05-18";
            $tahun = 2024;
            $bulan = 5;

            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulan - 1];

            //base masker = 1
            $rp_masker = $this->m_hris->param_upah_id(1);
            foreach ($rp_masker->result() as $r) {
                $uph_masker = $r->nilai;
            }


            //pembagi lembur = 10
            $plbr = $this->m_hris->param_upah_id(10);
            foreach ($plbr->result() as $r) {
                $bagi_lembur = $r->nilai;
            }

            //shift1-2 = 8
            $slbr1 = $this->m_hris->param_upah_id(8);
            foreach ($slbr1->result() as $slbr1a) {
                $shift_lbr1 = $slbr1a->nilai;
            }

            //shift3 = 9
            $slbr2 = $this->m_hris->param_upah_id(9);
            foreach ($slbr2->result() as $slbr2a) {
                $shift_lbr2 = $slbr2a->nilai;
            }

            //pengali lembur 1
            $klbr1 = $this->m_hris->param_upah_id(11);
            foreach ($klbr1->result() as $klbr1a) {
                $kali_lembur1 = $klbr1a->nilai;
            }

            //pengali lembur 2
            $klbr2 = $this->m_hris->param_upah_id(12);
            foreach ($klbr2->result() as $klbr2a) {
                $kali_lembur2 = $klbr2a->nilai;
            }

            //pengali lembur 3
            $klbr3 = $this->m_hris->param_upah_id(13);
            foreach ($klbr3->result() as $klbr3a) {
                $kali_lembur3 = $klbr3a->nilai;
            }

            $tgl_skrg = date('d M Y / H:s:s');
            $usr = strtolower($this->session->userdata('nama'));
            $karyawan = $this->m_hris->karyawan_view_by_id(1187);
            foreach ($paygroup->result() as $pg) {
                echo $pg->pay_group;
                echo "<br>
            Gaji Karyawan Bulanan Yang Termasuk ke Upah $pg->pay_group<br>
            Bulan $nama_bulan $tahun (Lembur dari tgl : $tgl_mulai s/d $tgl_akhir)
            <br>
            Cimahi, $tgl_skrg<br>
            Dicetak oleh : $usr<br><br>
            
            <table border='1'><tr>
            <td>TAHUN</td>
            <td>BULAN</td>
            <td>PERIODE</td>
            <td>STS_PPH</td>
            <td>NIK</td>
            <td>NAMA</td>
            <td>BAGIAN</td>
            <td>JABATAN</td>
            <td>MASA KERJA</td>
            <td>U.POKOK</td>
            <td>TJBTN</td>
            <td>TJENPEK</td>
            <td>TMASKER</td>
            <td>U.GLOBAL</td>
            <td>SLEMBUR1</td>
            <td>SLEMBUR2</td>
            <td>T.LEMBUR2</td>
            <td>LEMBUR1</td>
            <td>U.LBR1</td>
            <td>T.LBR1</td>
            <td>LEMBUR2</td>
            <td>U.LBR2</td>
            <td>T.LBR2</td>
             <td>LEMBUR3</td>
            <td>U.LBR3</td>
            <td>T.LBR3</td>
            <td>TOT LBR</td>
            <td>HADIR</td>
            <td>PREMI</td>
            <td>B.PREMI</td>
            <td>T.RENCANA</td>
            <td>P.RENCANA</td>
            <td>T.TDK RENCANA</td>
            <td>P.TDK RENCANA</td>
            <td>PULANG</td>
            <td>P.PULANG</td>
            <td>KELUAR</td>
            <td>KELUAR > 2J</td>
            <td>P.KELUAR</td>
            <td>TP. PREMI</td>
            <td>BONUS</td>
            <td>J.TRANS</td>
            <td>U.TRANS</td>
            <td>U.MAKAN</td>
            <td>ASTEK</td>
            <td>ASURANSI PT</td>
            <td>BRUTO</td>
            <td>PENSIUN</td>
            <td>SPMI</td>
            <td>MANGKIR</td>
            <td>PTKP</td>
            <td>B.JAB</td>
            <td>ASTEK PPH</td>
            <td>TOTAL POTONG</td>
            <td>PKP</td>
            <td>PPH 21</td>
            <td>PPH 21</td>
            <td>PPH 21</td>
            <td>TOT PPH 21</td>
            <td>NET ASR</td>
            <td>NET</td>
            </tr>
        ";
                $bagian = $this->m_hris->bagian_by_paygroup($pg->pay_group);
                foreach ($bagian->result() as $b) {
                    $karyawan = $this->m_hris->offdown_by_bagian2($b->recid_bag);
                    foreach ($karyawan->result() as $k) {
                        $recid_karyawan = $k->recid_karyawan;
                        $nik = $k->nik;
                        $nama_karyawan = $k->nama_karyawan;
                        echo $nama_karyawan;
                        $bagian = $k->indeks_hr;
                        $jabatan = $k->indeks_jabatan;
                        $uph_pokok = $k->gapok;
                        $t_jbtn = $k->t_jabatan;
                        $t_prestasi = $k->t_prestasi;
                        $t_jenpek = $k->t_jen_pek;
                        $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                        $masker_tahun = $diff->format('%y');
                        // echo "masa kerja : $masker_tahun<br>";
                        $t_masker = $masker_tahun * $uph_masker;
                        $global = $uph_pokok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;

                        /* START LEMBURAN */

                        $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 14);
                        $s1 = $shift1->num_rows();
                        $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 15);
                        $s2 = $shift2->num_rows();
                        $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 16);
                        $s3 = $shift3->num_rows();

                        $lemburan = $this->m_lembur->karyawan_lembur_report($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        $tot_lbr = 0;
                        foreach ($lemburan->result() as $l) {
                            $lembur1 = round($l->lembur1, 1);
                            $lembur2 = round($l->lembur2, 1);
                            $lembur3 = round($l->lembur3, 1);
                        }
                        echo ":<br>lembur 1 : " . $lembur1 . "<br>lembur 2 : " . $lembur2 . "<br>lembur 3 : " . $lembur3;

                        // Cek adjustment lemburan
                        $atrans = 0;
                        $amakan = 0;
                        $as1 = 0;
                        $as2 = 0;
                        $adj_upah = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        // echo $adj_upah->num_rows();
                        if ($adj_upah->num_rows() > 0) {
                            foreach ($adj_upah->result() as $al) {
                                $lembur1 = $lembur1 + $al->jam_lbr1;
                                $lembur2 = $lembur2 + $al->jam_lbr2;
                                $lembur3 = $lembur3 + $al->jam_lbr3;
                                $as1 = $as1 + $al->premi1;
                                $as2 = $as2 + $al->premi2;
                                $atrans = $al->jml_transport;
                                $amakan = $al->jml_makan;
                            }
                        } else {
                            $atrans = 0;
                            $amakan = 0;
                        }

                        $slembur1 = $s1 + $s2 + $as1;
                        $uslembur1 = $slembur1 * $shift_lbr1;
                        $slembur2 = $s3 + $as2;
                        $uslembur2 = $slembur2 * $shift_lbr2;
                        $utot_shift = $uslembur1 + $uslembur2;

                        $uph_lbr1 = round(($global / $bagi_lembur) * $kali_lembur1);
                        $lbr1 = $uph_lbr1 * $lembur1;
                        $uph_lbr2 = round(($global / $bagi_lembur) * $kali_lembur2);
                        $lbr2 = $uph_lbr2 * $lembur2;
                        $uph_lbr3 = round(($global / $bagi_lembur) * $kali_lembur3);
                        $lbr3 = $uph_lbr3 * $lembur3;
                        $tot_lbr = $lbr1 + $lbr2 + $lbr3;


                        /* END LEMBURAN */

                        /* START PREMI */
                        $tingkatan = $k->tingkatan;
                        if ($tingkatan >= 1 and $tingkatan < 4) {
                            //param premi hadir operator - karu, id = 2
                            $ph = $this->m_hris->param_upah_id(2);
                            foreach ($ph->result() as $p) {
                                $premi = $p->nilai;
                            }
                        } else if ($tingkatan >= 4 and $tingkatan <= 5) {
                            //param premi hadir wakasi - kasi, id = 3
                            $ph = $this->m_hris->param_upah_id(3);
                            foreach ($ph->result() as $p) {
                                $premi = $p->nilai;
                            }
                        } else {
                            $premi = 0;
                        }


                        $total_hadir = $this->m_absenbarcode->hitung_kerja($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        $jml_hadir = $total_hadir->num_rows();
                        $upah_hadir = $jml_hadir * $premi;

                        // potong terlambat terencana
                        $pr = $this->m_hris->param_upah_id(4);
                        foreach ($pr->result() as $pr1) {
                            $ppersenpr = $pr1->nilai;
                        }

                        // potong terlambat tidak terencana
                        $ptr = $this->m_hris->param_upah_id(5);
                        foreach ($ptr->result() as $ptr1) {
                            $ppersenptr = $ptr1->nilai;
                        }

                        // potong izin keluar > 2 jam
                        $pk = $this->m_hris->param_upah_id(6);
                        foreach ($pk->result() as $pk1) {
                            $ppersenpk = $pk1->nilai;
                        }

                        // potong pulang cepat
                        $pp = $this->m_hris->param_upah_id(7);
                        foreach ($pp->result() as $pp1) {
                            $ppersenpp = $pp1->nilai;
                        }

                        $trencana = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Terlambat Terencana');
                        $prencana = $trencana->num_rows() * ($premi * ($ppersenpr / 100));

                        $tdkrencana = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Terlambat Tidak Terencana');
                        $ptdrencana = $tdkrencana->num_rows() * ($premi * ($ppersenptr / 100));

                        $pulang = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Pulang');
                        $ppulang = $pulang->num_rows() * ($premi * ($ppersenpp / 100));

                        $keluar = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Keluar');
                        $cnt_keluar = 0;
                        foreach ($keluar->result() as $i) {
                            if ($i->over_durasi >= 2) {
                                $cnt_keluar = $cnt_keluar + 1;
                            }
                        }
                        $pkeluar = $cnt_keluar * ($premi * ($ppersenpk / 100));
                        /* END PREMI */

                        /* UANG TRANSPORT */
                        // transport lembur holiday
                        $ut = $this->m_hris->param_upah_id(14);
                        foreach ($ut->result() as $ut1) {
                            $utrans2 = $ut1->nilai;
                        }
                        // transport lembur terusan
                        $ut = $this->m_hris->param_upah_id(39);
                        foreach ($ut->result() as $ut1) {
                            $utrans1 = $ut1->nilai;
                        }
                        // transport lembur terusan
                        $transport = $this->m_lembur->transport_lembur_karyawan($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        $trsp = $transport->num_rows();
                        $transport2 = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        $trsp2 = $transport2->num_rows();

                        // transport shift1,2,3
                        $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 14);
                        $s1 = $shift1->num_rows();
                        $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 15);
                        $s2 = $shift2->num_rows();
                        $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 16);
                        $s3 = $shift3->num_rows();
                        $tot_shift = $s1 + $s2 + $s3;
                        $uang_transport1 = $trsp * $utrans1;
                        $uang_transport2 = ($trsp2 + $tot_shift) * $utrans2;
                        $uang_transport_adj = $atrans * $utrans2;
                        // echo $uang_transport2;
                        // echo $uang_transport_adj;
                        $uang_transport = $uang_transport1 + $uang_transport2 + $uang_transport_adj;
                        // $uang_transport = $uang_transport1 + $uang_transport2;
                        /* END UANG TRANSPORT */

                        /* START UANG MAKAN */
                        // uang makan
                        $um = $this->m_hris->param_upah_id(15);
                        foreach ($um->result() as $um1) {
                            $umakan = $um1->nilai;
                        }
                        $uang_makan = $s3 * $umakan;

                        // makan lembur
                        $mkn = $this->m_lembur->makan_lembur($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        // $makaan = $mkn->num_rows();
                        $makaan = ($mkn->num_rows()) + $amakan;
                        $makan = $makaan * $umakan;
                        $uang_makan = $uang_makan + $makan;
                        echo $uang_makan;

                        // $adj_makan = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan, 1);
                        // if($adj_makan->num_rows() > 0)
                        // {
                        //     foreach ($adj_makan->result() as $am) {
                        //         $uang_makan = $uang_makan + $makan + $am->nominal;
                        //     }
                        // }

                        // $uang_makan = 13000;
                        /* END UANG MAKAN */

                        /* START ASTEK */
                        // astek jp
                        $ajp = $this->m_hris->param_upah_id(19);
                        foreach ($ajp->result() as $ajp1) {
                            $asuransi_jp = $ajp1->nilai;
                        }

                        // astek jht
                        $ajht = $this->m_hris->param_upah_id(20);
                        foreach ($ajht->result() as $ajht1) {
                            $asuransi_jht = $ajht1->nilai;
                        }

                        //astek bpjs kesehatan
                        $abkes = $this->m_hris->param_upah_id(21);
                        foreach ($abkes->result() as $abkes1) {
                            $asuransi_bkes = $abkes1->nilai;
                        }

                        $kali_astek = $asuransi_jp + $asuransi_jht + $asuransi_bkes;
                        $astek = $global * ($kali_astek / 100);
                        /* END ASTEK */

                        /* START ASURANSI PERUSAHAAN */
                        //persentase jkm
                        $jkm = $this->m_hris->param_upah_id(16);
                        foreach ($jkm->result() as $jkm1) {
                            $ujkm = $jkm1->nilai;
                        }

                        // persentase jkk
                        $jkk = $this->m_hris->param_upah_id(17);
                        foreach ($jkk->result() as $jkk1) {
                            $ujkk = $jkk1->nilai;
                        }

                        //persentase bpjs kesehatan
                        $bpjs = $this->m_hris->param_upah_id(18);
                        foreach ($bpjs->result() as $bpjs1) {
                            $ubpjs = $bpjs1->nilai;
                        }

                        $per_asr = $ujkm + $ujkk + $ubpjs;
                        $uasuransi = $global * ($per_asr / 100);
                        /* END ASURANSI PERUSAHAAN */

                        /* START PPH21 */
                        $bruto = $global + $tot_lbr + $utot_shift + ($upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana) + $uang_transport + $uang_makan + $uasuransi);

                        /* START PENSIUN */
                        if ($k->pensiun == "Ya") {
                            // persentase dplk
                            $dplk = $this->m_hris->param_upah_id(22);
                            foreach ($dplk->result() as $dplk1) {
                                $dplk_aia = $dplk1->nilai;
                            }
                            $pot_dplk = $global * ($dplk_aia / 100);
                        } else {
                            $pot_dplk = 0;
                        }
                        /* END PENSIUN */

                        /* START SPMI */
                        if ($k->lspmi == "Ya") {
                            // spmi
                            $spmi = $this->m_hris->param_upah_id(23);
                            foreach ($spmi->result() as $spmi1) {
                                $pspmi = $spmi1->nilai;
                            }
                            $uspmi = ($uph_pokok * ($pspmi / 100)) + 5000;
                        } else {
                            $uspmi = 0;
                        }
                        /* END SPMI */

                        /* START MANGKIR */
                        $mkr = $this->m_absenbarcode->hitung_mangkir($tgl_mulai, $tgl_akhir, $recid_karyawan);
                        // potongan mangir
                        $mangkir = $this->m_hris->param_upah_id(24);
                        foreach ($mangkir->result() as $mangkir1) {
                            $pmangkir = $mangkir1->nilai;
                        }
                        $umangkir = $mkr->num_rows() * (($pmangkir / 100) * $global);
                        /* END MAGKIR */

                        /* START PTKP */
                        $sts_kawin = $k->sts_penunjang;
                        if ($sts_kawin == "K0") {
                            // ptkp K0
                            $ptkp = $this->m_hris->param_upah_id(28);
                        } else if ($sts_kawin == "K1") {
                            // ptkp K1
                            $ptkp = $this->m_hris->param_upah_id(29);
                        } else if ($sts_kawin == "K2") {
                            $ptkp = $this->m_hris->param_upah_id(30);
                        } else if ($sts_kawin == "K3") {
                            // ptkp K3
                            $ptkp = $this->m_hris->param_upah_id(31);
                        } else if ($sts_kawin == "TK1") {
                            // ptkp TK1
                            $ptkp = $this->m_hris->param_upah_id(45);
                        } else if ($sts_kawin == "TK2") {
                            // ptkp TK2
                            $ptkp = $this->m_hris->param_upah_id(46);
                        } else if ($sts_kawin == "TK3") {
                            // ptkp TK3
                            $ptkp = $this->m_hris->param_upah_id(47);
                        } else {
                            // ptkp TK
                            $ptkp = $this->m_hris->param_upah_id(27);
                        }
                        foreach ($ptkp->result() as $ptkp1) {
                            $uptkp = $ptkp1->nilai;
                        }
                        /* END PTKP */

                        /* START BIAYA JABATAN */
                        //biaya jabatan
                        $bjab = $this->m_hris->param_upah_id(25);
                        foreach ($bjab->result() as $bjab1) {
                            $b_jabatan = $bjab1->nilai;
                        }

                        //max biaya jabatan
                        $max_jbtn = $this->m_hris->param_upah_id(26);
                        foreach ($max_jbtn->result() as $max_jbtn1) {
                            $max_jab = $max_jbtn1->nilai;
                        }

                        $biaya_jabatan = $bruto * ($b_jabatan / 100);
                        if ($biaya_jabatan > $max_jab) {
                            $biaya_jabatan = $max_jab;
                        }
                        /* END BIAYA JABATAN */

                        $astek_pph = $asuransi_jp + $asuransi_jht;
                        $uapph = $global * ($astek_pph / 100);
                        $tot_pot = $uptkp + $biaya_jabatan + $uapph + $pot_dplk;
                        $pkp = $bruto - $tot_pot;
                        /* START LAPISAN PKP */
                        // lapisan pkp per bulan
                        $lvlpkp = [40, 41, 42];
                        $lap_pkp1 = 0;
                        $lap_pkp2 = 0;
                        $lap_pkp3 = 0;
                        for ($i = 0; $i < count($lvlpkp); $i++) {

                            $lapisan = $this->m_hris->param_upah_id($lvlpkp[$i]);
                            foreach ($lapisan->result() as $hasil) {
                                $x = $i + 1;
                                ${"lap_pkp" . $x} = $hasil->nilai;
                                // echo ${"lap_pkp" . $x};
                            }
                        }

                        // echo $lap_pkp1;
                        $cek_npwp = $k->cek_npwp;
                        if ($cek_npwp == '1') {
                            $perpkp = [32, 33, 34];
                        } else {
                            $perpkp = [44, 33, 34];
                        }
                        // $perpkp = [32,33,34];
                        $per_pkp1 = 0;
                        $per_pkp2 = 0;
                        $per_pkp3 = 0;
                        for ($x = 0; $x < count($perpkp); $x++) {
                            $persen = $this->m_hris->param_upah_id($perpkp[$x]);
                            foreach ($persen->result() as $hasil2) {
                                ${"per_pkp" . ($x + 1)} = $hasil2->nilai;
                            }
                        }

                        // echo $per_pkp[$x+1]."<br>";
                        // echo ($lap_pkp1)."<br>";
                        if ($pkp >= 0 and $pkp <= $lap_pkp1) {
                            if ($pkp >= $lap_pkp1) {
                                $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                            } else {
                                $pphl1 = round($pkp * ($per_pkp1 / 100));
                            }
                            // echo "Lapisan 1 : ".$pphl1."<br>";
                            $pphl2 = 0;
                            $pphl3 = 0;
                        } else if ($pkp > $lap_pkp1 and $pkp <= $lap_pkp2) {
                            // echo "lapisan 2<br>";
                            if ($pkp >= $lap_pkp1) {
                                $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                            } else {
                                $pphl1 = round($pkp * ($per_pkp1 / 100));
                            }
                            // echo "Lapisan 1 : ".$pphl1."<br>";
                            $pkp = $pkp - $lap_pkp1;
                            if ($pkp >= $lap_pkp2) {
                                $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                            } else {
                                $pphl2 = round($pkp * ($per_pkp2 / 100));
                            }
                            // echo "Lapisan 2 : ".$pphl2."<br>";
                            $pphl3 = 0;
                        } else if ($pkp > $lap_pkp2 and $pkp <= $lap_pkp3) {
                            // echo "lapisan 3";
                            if ($pkp >= $lap_pkp1) {
                                $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                            } else {
                                $pphl1 = round($pkp * ($per_pkp1 / 100));
                            }
                            // echo "Lapisan 1 : ".$pphl1."<br>";
                            $pkp2 = $pkp - $lap_pkp1;
                            if ($pkp2 >= $lap_pkp2) {
                                $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                            } else {
                                $pphl2 = round($pkp * ($per_pkp2 / 100));
                            }
                            $pkp3 = $pkp - $lap_pkp1 - $lap_pkp2;
                            $pphl3 = round($pkp3 * ($per_pkp3 / 100));
                        } else {
                            $pphl1 = 0;
                            $pphl2 = 0;
                            $pphl3 = 0;
                        }
                        /* END LAPISAN PKP */
                        $tot_pph = $pphl1 + $pphl2 + $pphl3;

                        /* END PPH21 */

                        /* NETT */
                        $net_asr = $bruto - $pot_dplk - $astek - $uspmi - $umangkir - $tot_pph;
                        $net = $net_asr - $uasuransi;

                        $sts_pph =  $k->sts_penunjang . " / " . $k->nama_jbtn;
                        $data = array(
                            'crt_by'            => '',
                            'crt_date'          => '',
                            'bulan'             => $bulan,
                            'tahun'             => $bulan,
                            'periode_awal'      => $tgl_mulai,
                            'periode_akhir'     => $tgl_akhir,
                            'recid_karyawan'    => $k->recid_karyawan,
                            'recid_bag'         => $k->recid_bag,
                            'recid_jbtn'        => $k->recid_jbtn,
                            'sts_upah'          => $sts_pph,
                            'masker'            => $masker_tahun,
                            'rp_masker'         => $uph_masker,
                            'upokok'            => $uph_pokok,
                            'tjbtn'             => $t_jbtn,
                            'tjenpek'           => $t_jenpek,
                            'uglobal'           => $global,
                            'slbr1'             => $slembur1,
                            'rp_slbr1'          => $shift_lbr1,
                            'slbr2'             => $slembur2,
                            'rp_slbr2'          => $shift_lbr2,
                            'lbr1'              => $lembur1,
                            'rp_lbr1'           => $uph_lbr1,
                            'lbr2'              => $lembur2,
                            'rp_lbr2'           => $uph_lbr2,
                            'lbr3'              => $lembur3,
                            'rp_lbr3'           => $uph_lbr3,
                            'tot_rp_lbr'        => $tot_lbr,
                            'hadir'             => $jml_hadir,
                            'rp_hadir'          => $premi,
                            'trencana'          => $trencana->num_rows(),
                            'rp_trencana'       => $prencana,
                            'ttrencana'         => $tdkrencana->num_rows(),
                            'rp_ttrencana'      => $ptdrencana,
                            'pulang'            => $pulang->num_rows(),
                            'rp_pulang'         => $ppulang,
                            'keluar'            => $keluar->num_rows(),
                            'keluar2'           => $cnt_keluar,
                            'rp_keluar2'        => $pkeluar,
                            'premi'             => $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana),
                            'uang_transport'    => $uang_transport,
                            'uang_makan'        => $uang_makan,
                            'astek'             => $astek,
                            'asuransi'          => $uasuransi,
                            'bruto'             => $bruto,
                            'pensiun'           => $pot_dplk,
                            'spmi'              => $uspmi,
                            'mangkir'           => $umangkir,
                            'ptkp'              => $uptkp,
                            'bjab'              => $biaya_jabatan,
                            'astek_pph'         => $uapph,
                            'tot_pot'           => $tot_pot,
                            'pkp'               => $pkp,
                            'pph21_1'           => $pphl1,
                            'pph21_2'           => $pphl2,
                            'pph21_3'           => $pphl3,
                            'netasr'            => $net_asr,
                            'netto'             => $net,
                        );
                        // $this->m_lembur->insert_upah($data);

                        echo "
                <tr>
                <td> $tahun</td>
                <td> $bulan </td>
                <td> $tgl_mulai s/d $tgl_akhir </td>
                <td> $k->sts_penunjang / $k->nama_jbtn </td>
                <td> $nik </td>
                <td> $nama_karyawan </td>
                <td> $bagian </td>
                <td> $jabatan </td>
                <td> $masker_tahun </td>
                <td> " . $uph_pokok . " </td>
                <td> " . $t_jbtn . " </td>
                <td> " . $t_jenpek . " </td>
                <td> " . $t_masker . " </td>
                <td> " . $global . " </td>
                <td> $slembur1 </td>
                <td> $slembur2 </td>
                <td> " . $utot_shift . " </td>
                <td> $lembur1 </td>
                <td> " . $uph_lbr1 . " </td>
                <td> " . $lbr1 . " </td>
                <td> $lembur2</td>
                <td> " . $uph_lbr2 . " </td>
                <td> " . $lbr2 . " </td>
                <td> $lembur3</td>
                <td> " . $uph_lbr3 . " </td>
                <td> " . $lbr3 . " </td>
                <td> " . $tot_lbr . " </td>
                <td> $jml_hadir </td>
                <td> " . $premi . " </td>
                <td> " . $upah_hadir . " </td>
                <td> " . $trencana->num_rows() . "</td>
                <td> " . $prencana . " </td>
                <td> " . $tdkrencana->num_rows() . "</td>
                <td> " . $ptdrencana . " </td>
                <td> " . $pulang->num_rows() . "</td>
                <td> " . $ppulang . " </td>
                <td> " . $keluar->num_rows() . "</td>
                <td> " . $cnt_keluar . "</td>
                <td> " . $pkeluar . " </td>
                <td> " . $pkeluar + $ppulang + $ptdrencana + $prencana . " </td>
                <td> " . $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana) . " </td>
                <td> " . $trsp + $tot_shift . "</td>
                <td> " . $uang_transport . "</td>
                <td> " . $uang_makan . "</td>
                <td> " . $astek . "</td>
                <td> " . $uasuransi . "</td>
                <td> " . $bruto . "</td>
                <td> " . $pot_dplk . "</td>
                <td> " . $uspmi . "</td>
                <td> " . $umangkir . "</td>
                <td> " . $uptkp . "</td>
                <td> " . $biaya_jabatan . "</td>
                <td> " . $uapph . "</td>
                <td> " . $tot_pot . "</td>
                <td> " . $pkp . "</td>
                <td> " . $pphl1 . "</td>
                <td> " . $pphl2 . "</td>
                <td> " . $pphl3 . "</td>
                <td> " . $tot_pph . "</td>
                <td> " . $net_asr . "</td>
                <td> " . $net . "</td>
                </tr>   
                ";
                    }
                }
                echo "</table>";
            }
        } else {
            redirect('Auth/keluar');
        }
    }



    public function adjustment_view()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['adjust'] = $this->m_lembur->adjust_view();
            $data['kategori'] = $this->m_lembur->kategori_adjust();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/adjust_view', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function adjustment_periode()
    {
        $awal = $this->input->post('tgl_mulai');
        $akhir = $this->input->post('tgl_akhir');

        $query2 = $this->m_lembur->adjust_periode($awal, $akhir);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $datas = [];
        $no = 0;
        foreach ($query2->result() as $data) {
            $datas[] = array(
                $no = $no + 1,
                $data->periode_awal,
                $data->periode_akhir,
                $data->tanggal,
                $data->nik,
                $data->nama_karyawan,
                $data->indeks_hr,
                $data->jml_makan,
                $data->jml_transport,
                $data->jam_lbr1,
                $data->jam_lbr2,
                $data->jam_lbr3,
                $data->premi1,
                $data->premi2,
                $data->keterangan,
                '<a href="' . base_url() . 'Upah/adjust_edit/' . $data->recid_auph . '"><button class="btn btn-info btn-xs"><span class="fa fa-edit"></button></a><a href="' . base_url() . 'Upah/adjust_hapus/' . $data->recid_auph . '"><button class="btn btn-danger btn-xs"><span class="fa fa-trash"></button></a>'
            );
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query2->num_rows(),
            "recordsFiltered" => $query2->num_rows(),
            "data" => $datas
        );

        echo json_encode($result);
        exit();
    }

    public function adjustment_upah()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
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

            $data['karyawan'] = $this->m_hris->karyawan_offdown();
            $data['kategori'] = $this->m_lembur->kategori_adjust();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/adjust_upah', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function adjust_edit()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $role = $this->session->userdata('role_id');
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $id = $this->uri->segment(3);
            $data['adjust'] = $this->m_lembur->adjustment_by_id($id);
            $data['karyawan'] = $this->m_hris->karyawan_offdown();
            $data['kategori'] = $this->m_lembur->kategori_adjust();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/adjust_edit', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function upload_adjustment()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/upload_adjustment', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function proses_adjust()
    {
        $recid_karyawan = $this->input->post('recid_karyawan');
        $periode_awal = $this->input->post('periode_awal');
        $periode_akhir = $this->input->post('periode_akhir');
        $tanggal = $this->input->post('tanggal');
        $jml_makan = $this->input->post('jml_makan');
        $jml_transport = $this->input->post('jml_transport');
        $jml_lbr1 = $this->input->post('jml_lbr1');
        $jml_lbr2 = $this->input->post('jml_lbr2');
        $jml_lbr3 = $this->input->post('jml_lbr3');
        $jml_premi1 = $this->input->post('jml_premi1');
        $jml_premi2 = $this->input->post('jml_premi2');
        $keterangan = $this->input->post('keterangan');

        for ($i = 0; $i < count($recid_karyawan); $i++) {
            $double = $this->m_lembur->cek_duplikat_adjust($recid_karyawan[$i], $periode_awal, $periode_akhir);
            if ($double->num_rows() < 1) {
                $data = array(
                    'crt_by'            => $this->session->userdata('kar_id'),
                    'crt_date'          => date('Y-m-d H:i:s'),
                    'recid_karyawan'    => $recid_karyawan[$i],
                    'periode_awal'      => $periode_awal,
                    'periode_akhir'     => $periode_akhir,
                    'tanggal'           => $tanggal,
                    'jml_makan'         => $jml_makan,
                    'jml_transport'     => $jml_transport,
                    'jam_lbr1'          => $jml_lbr1,
                    'jam_lbr2'          => $jml_lbr2,
                    'jam_lbr3'          => $jml_lbr3,
                    'premi1'            => $jml_premi1,
                    'premi2'            => $jml_premi2,
                    'keterangan'        => $keterangan,
                );
                $this->m_lembur->adjust_upah($data);
            } else {
                foreach ($double->resulut() as $d) {
                    $id = $d->recid_auph;
                }
                $data = array(
                    'mdf_by'            => $this->session->userdata('kar_id'),
                    'mdf_date'          => date('Y-m-d H:i:s'),
                    'recid_karyawan'    => $recid_karyawan[$i],
                    'periode_awal'      => $periode_awal,
                    'periode_akhir'     => $periode_akhir,
                    'tanggal'           => $tanggal,
                    'jml_makan'         => $jml_makan,
                    'jml_transport'     => $jml_transport,
                    'jam_lbr1'          => $jml_lbr1,
                    'jam_lbr2'          => $jml_lbr2,
                    'jam_lbr3'          => $jml_lbr3,
                    'premi1'            => $jml_premi1,
                    'premi2'            => $jml_premi2,
                    'keterangan'        => $keterangan,
                );
                $this->m_lembur->adjust_upah_update($data, $id);
            }
        }
        redirect("Upah/adjustment_view");
    }

    function import_adjustment()
    {
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
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $jml_data = count($sheetData);
            $jml_data = $jml_data - 1;
            echo "jumlah data = " . $jml_data . "<br>";
            if (!empty($sheetData)) {
                for ($i = 1; $i < count($sheetData); $i++) {
                    $nik = $sheetData[$i][1];
                    $cek = $this->m_hris->karyawan_by_nik($nik);
                    foreach ($cek as $k) {
                        $recid_karyawan = $k->recid_karyawan;
                    }
                    $periode_awal = $sheetData[$i][3];
                    $periode_akhir = $sheetData[$i][4];
                    $tanggal = $sheetData[$i][5];
                    // $kategori_adjust = $sheetData[$i][5];
                    // $nominal = $sheetData[$i][6];
                    $jml_makan = $sheetData[$i][6];
                    $jml_transport = $sheetData[$i][7];
                    $jml_lbr1 = $sheetData[$i][8];
                    $jml_lbr2 = $sheetData[$i][9];
                    $jml_lbr3 = $sheetData[$i][10];
                    $premi1 = $sheetData[$i][11];
                    $premi2 = $sheetData[$i][12];
                    $keterangan = $sheetData[$i][13];
                    // echo "emp : $recid_karyawan <br> periode awal :  $periode_awal <br> periode akhir : $periode_akhir <br> makan : $jml_makan <br> transport : $jml_transport <br> jml lembur 1 : $jml_lbr2 <br> jml lembur 1 : $jml_lbr2 <br> jml lembur 3 : $jml_lbr3 <br> ket : $keterangan <br><br><br>";

                    $double = $this->m_lembur->cek_duplikat_adjust($recid_karyawan, $periode_awal, $periode_akhir);
                    if ($double->num_rows() < 1) {
                        $data = array(
                            'crt_by'            => $this->session->userdata('kar_id'),
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'recid_karyawan'    => $recid_karyawan,
                            'periode_awal'      => $periode_awal,
                            'periode_akhir'     => $periode_akhir,
                            'tanggal'           => $tanggal,
                            'jml_makan'         => $jml_makan,
                            'jml_transport'     => $jml_transport,
                            'jam_lbr1'          => $jml_lbr1,
                            'jam_lbr2'          => $jml_lbr2,
                            'jam_lbr3'          => $jml_lbr3,
                            'jam_lbr3'          => $jml_lbr3,
                            'premi1'            => $premi1,
                            'premi2'            => $premi2,
                            'keterangan'        => $keterangan,
                        );
                        $this->m_lembur->adjust_upah($data);
                    } else {
                        foreach ($double->result() as $d) {
                            $id = $d->recid_auph;
                        }
                        $data = array(
                            'mdf_by'            => $this->session->userdata('kar_id'),
                            'mdf_date'          => date('Y-m-d H:i:s'),
                            'recid_karyawan'    => $recid_karyawan,
                            'periode_awal'      => $periode_awal,
                            'periode_akhir'     => $periode_akhir,
                            'tanggal'           => $tanggal,
                            // 'kategori_adjust'   => $kategori_adjust,
                            'jml_makan'         => $jml_makan,
                            'jml_transport'     => $jml_transport,
                            'jam_lbr1'          => $jml_lbr1,
                            'jam_lbr2'          => $jml_lbr2,
                            'jam_lbr3'          => $jml_lbr3,
                            'premi1'            => $premi1,
                            'premi2'            => $premi2,
                            'keterangan'        => $keterangan,
                        );
                        $this->m_lembur->adjust_upah_edit($data, $id);
                    }
                }
            }
        }
        redirect("Upah/adjustment_view");
    }

    public function adjust_hapus()
    {
        $id = $this->uri->segment(3);
        $data = array(
            'mdf_by'            => $this->session->userdata('kar_id'),
            'mdf_date'          => date('Y-m-d H:i:s'),
            'is_delete'         => '1',
        );
        $this->m_lembur->adjust_upah_edit($data, $id);
        redirect("Upah/adjustment_view");
    }

    public function edit_adjust()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $id = $this->input->post('recid_auph');
            $periode_awal = $this->input->post('periode_awal');
            $periode_akhir = $this->input->post('periode_akhir');
            $kategori = $this->input->post('kategori');
            $jml_makan = $this->input->post('jml_makan');
            $jml_transport = $this->input->post('jml_transport');
            $jml_lbr1 = $this->input->post('jml_lbr1');
            $jml_lbr2 = $this->input->post('jml_lbr2');
            $jml_lbr3 = $this->input->post('jml_lbr3');
            $keterangan = $this->input->post('keterangan');
            $data = array(
                'mdf_by'            => $this->session->userdata('kar_id'),
                'mdf_date'          => date('Y-m-d H:i:s'),
                'periode_awal'      => $periode_awal,
                'periode_akhir'     => $periode_akhir,
                'kategori_adjust'   => $kategori,
                'jml_makan'         => $jml_makan,
                'jml_transport'     => $jml_transport,
                'jam_lbr1'          => $jml_lbr1,
                'jam_lbr2'          => $jml_lbr2,
                'jam_lbr3'          => $jml_lbr3,
                'keterangan'        => $keterangan,
            );
            $this->m_lembur->adjust_upah_edit($data, $id);
            redirect("Upah/adjustment_view");
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_master()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['tahun'] = $this->m_absen->tahun_hk();
            $tahun = Date('Y');
            $bulan = Date('m');
            $list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = $list_bulan[$bulan - 1];
            $data['bulans'] = $bulan;
            
            // Inisialisasi variabel dengan nilai default
            $awal = '';
            $akhir = '';
            
            $periode_awal = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
            foreach ($periode_awal->result() as $p) {
                $awal = $p->periode_awal;
                $akhir = $p->periode_akhir;
            }
            $data['periode_awal']  = $awal;
            $data['periode_akhir']  = $akhir;
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_master', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_transisi_upah()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['tahun'] = $this->m_absen->tahun_hk();
            $tahun = Date('Y');
            $bulan = Date('n');
            $list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = $list_bulan[$bulan - 1];
            $data['bulans'] = $bulan;
            $periode_awal = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
            foreach ($periode_awal->result() as $p) {
                $awal = $p->periode_awal;
                $akhir = $p->periode_akhir;
            }
            //  echo $tahun."-".$bulan;
            $data['periode_awal']  = $awal;
            $data['periode_akhir']  = $akhir;
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_transisi_upah', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_kalkulasi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['tahun'] = $this->m_absen->tahun_hk();
            $tahun = Date('Y');
            $bulan = Date('m');
            $list_bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = $list_bulan[$bulan - 1];
            $data['bulans'] = $bulan;
            $periode_awal = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
            foreach ($periode_awal->result() as $p) {
                $awal = $p->periode_awal;
                $akhir = $p->periode_akhir;
            }
            $data['periode_awal']  = $awal;
            $data['periode_akhir']  = $akhir;
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_kalkulasi', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function cek_cut_off()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nama_bulan = $month[$bulan - 1];
        $co = $this->m_lembur->cutoff_thn_bln($tahun, $nama_bulan);
        $data = array();
        foreach ($co->result() as $c) {
            array_push($data, $c->periode_awal);
            array_push($data, $c->periode_akhir);
        }
        echo json_encode($data);
        exit();
    }

    public function download_upah2()
    {
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tipe = $this->input->post('tipe');
        $fpaygroup = array();
        $paygroup = $this->input->post('divisi');
        $department = $this->input->post('departement');
        $bagian = $this->input->post('bagian10');
        $karyawan = $this->input->post('karyawan');

        if (!empty($paygroup)) {
            for ($i = 0; $i < count($paygroup); $i++) {
                array_push($fpaygroup, $paygroup[$i]);
            }
        } else {
            $paygroup = $this->m_hris->paygroup_emp();
            foreach ($paygroup->result() as $dv) {
                array_push($fpaygroup, $dv->pay_group);
            }
        }
        // print_r($fpaygroup);

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
        // echo $fdepartment;


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
        // echo $fkaryawan;

        if ($tipe == '1') {
        } else if ($tipe == '2') {
        } else {
            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulan - 1];
            $data['paygroup'] = $fpaygroup;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['bulan'] = $nama_bulan;
            $data['tahun'] = $tahun;
            $data['tgl_mulai'] = $tgl_mulai;
            $data['tgl_akhir'] = $tgl_akhir;
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/master_upah', $data);
            $this->load->view('layout/a_footer');
        }
    }

    public function download_upah()
    {
        /* tipe 1 : simpan & download, tipe 2: download saja  */
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');

        $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nama_bulan = $month[$bulan - 1];
        $tipe = $this->input->post('tipe');
        $fpaygroup = array();
        $paygroup = $this->input->post('divisi');
        $department = $this->input->post('departement');
        $bagian = $this->input->post('bagian10');
        $karyawan = $this->input->post('karyawan');

        if (!empty($paygroup)) {
            for ($i = 0; $i < count($paygroup); $i++) {
                array_push($fpaygroup, $paygroup[$i]);
            }
        } else {
            $paygroup = array();
            $pg = $this->m_hris->paygroup_emp();
            foreach ($pg->result() as $dv) {
                array_push($fpaygroup, $dv->pay_group);
                array_push($paygroup, $dv->pay_group);
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
        // echo $fdepartment;


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

        if ($tipe == '1') {
            /* truncate all data table upah */
            $this->m_lembur->kosongkan_upah();
            $iduniq = uniqid();
        } else if ($tipe == '3') {
            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulan - 1];
            $data['paygroup'] = $fpaygroup;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['bulan'] = $nama_bulan;
            $data['bulanke'] = $bulan;
            $data['tahun'] = $tahun;
            $data['tgl_mulai'] = $tgl_mulai;
            $data['tgl_akhir'] = $tgl_akhir;
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/master_upah', $data);
            $this->load->view('layout/a_footer');
        } else {
        }

        if ($tipe != '3') {
            //base masker = 1
            $rp_masker = $this->m_hris->param_upah_id(1);
            foreach ($rp_masker->result() as $r) {
                $uph_masker = $r->nilai;
            }

            //pembagi lembur = 10
            $plbr = $this->m_hris->param_upah_id(10);
            foreach ($plbr->result() as $r) {
                $bagi_lembur = $r->nilai;
            }

            //shift1-2 = 8
            $slbr1 = $this->m_hris->param_upah_id(8);
            foreach ($slbr1->result() as $slbr1a) {
                $shift_lbr1 = $slbr1a->nilai;
            }

            //shift3 = 9
            $slbr2 = $this->m_hris->param_upah_id(9);
            foreach ($slbr2->result() as $slbr2a) {
                $shift_lbr2 = $slbr2a->nilai;
            }

            //pengali lembur 1
            $klbr1 = $this->m_hris->param_upah_id(11);
            foreach ($klbr1->result() as $klbr1a) {
                $kali_lembur1 = $klbr1a->nilai;
            }

            //pengali lembur 2
            $klbr2 = $this->m_hris->param_upah_id(12);
            foreach ($klbr2->result() as $klbr2a) {
                $kali_lembur2 = $klbr2a->nilai;
            }

            //pengali lembur 3
            $klbr3 = $this->m_hris->param_upah_id(13);
            foreach ($klbr3->result() as $klbr3a) {
                $kali_lembur3 = $klbr3a->nilai;
            }

            $tgl_skrg = date('d M Y  H:i:s');
            $usr = strtolower($this->session->userdata('nama'));

            $spreadsheet = new Spreadsheet();
            $tahap = ["Tahap1", "Tahap2"];
            $pgx = 1;

            $tot_global = 0;
            $tot_shift1 = 0;
            $tot_shift2 = 0;
            $tot_totshift = 0;
            $tot_ushift = 0;
            $tot_lbr1 = 0;
            $tot_lbr2 = 0;
            $tot_lbr3 = 0;
            $tot_totlbr = 0;
            $tot_ulbr = 0;
            $tot_premi  = 0;
            $tot_trans   = 0;
            $tot_mak    = 0;
            $tot_asr    = 0;
            $tot_pensiun = 0;
            $tot_astek = 0;
            $tot_spmi = 0;
            $tot_mangkir = 0;
            $tot_cpph = 0;
            $tot_netasr = 0;
            $tot_net = 0;
            $tot_potkop = 0;
            $tot_terima = 0;

            // foreach ($paygroup->result() as $pg)
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                // echo $fpaygroup[$pyg];
                ${"rekap_" . $paygroup[$pyg]} = array();
                ${"totpaygroup" . $paygroup[$pyg]} = array();
                $pgapok = 0;
                $pjbtn = 0;
                $pjenpek = 0;
                $pmasker = 0;
                $pglobal = 0;
                $pshift1 = 0;
                $pshift2 = 0;
                $ptotshift = 0;
                $ptotushift = 0;
                $plbr1 = 0;
                $plbr2 = 0;
                $plbr3 = 0;
                $ptotlbr = 0;
                $ptotulbr = 0;
                $ppremi = 0;
                $ptrans = 0;
                $pmak = 0;
                $pasr = 0;
                $ppen = 0;
                $past = 0;
                $ppspmi = 0;
                $pmang = 0;
                $ppph = 0;
                $pnetasr = 0;
                $pnet = 0;
                $ppotkop = 0;
                $pterima = 0;
                // echo $tahap[$p]."<br>";
                // Create a new worksheet called "My Data"
                $pgx = $pgx + 1;
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $paygroup[$pyg]);
                $spreadsheet->addSheet($myWorkSheet, $pgx);
                $spreadsheet->setActiveSheetIndexByName($paygroup[$pyg]);
                /* ----- START SETTING PAPER -------------------- */
                $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
                $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.7);
                $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.7);
                /* ----- END SETTING PAPER -------------------- */

                $sheet = $spreadsheet->getActiveSheet();
                $max_baris = $sheet->getHighestRow();

                // if($pgx>1)
                // {
                //     $max_baris = $max_baris + 3;
                // }
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Gaji Karyawan Bulanan Yang Termasuk ke Upah ' . $paygroup[$pyg]);
                //  echo 'A'.$max_baris." => Gaji Pay Group <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Bulan ' . $nama_bulan . ' ' . $tahun . ' (Lembur dari tgl : ' . $tgl_mulai . ' s/d ' . $tgl_akhir . ')');
                //  echo 'A'.$max_baris." => Periode <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Cimahi, ' . $tgl_skrg);
                //  echo 'A'.$max_baris." => Tanggal Akses <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $usr = strtolower($this->session->userdata('nama'));
                $sheet->setCellValue('A' . $max_baris, 'Dicetak Oleh : ' . $usr);
                //  echo 'A'.$max_baris." => Print <br>";

                $awal_baris = $max_baris + 2;
                $max_baris = $max_baris + 2;
                $max_bariss = $max_baris + 1;


                $sheet->getColumnDimension('A')->setWidth(3);
                $sheet->getColumnDimension('C')->setWidth(6);
                $sheet->getColumnDimension('F')->setWidth(8);
                $sheet->getColumnDimension('G')->setWidth(8);
                $sheet->getColumnDimension('H')->setWidth(8);
                $sheet->getColumnDimension('I')->setWidth(11);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('k')->setWidth(6);
                $sheet->getColumnDimension('L')->setWidth(6);
                $sheet->getColumnDimension('N')->setWidth(6);
                $sheet->getColumnDimension('O')->setWidth(6);
                $sheet->getColumnDimension('P')->setWidth(6);
                $sheet->getColumnDimension('Q')->setWidth(6);
                $sheet->getColumnDimension('S')->setWidth(8);
                $sheet->getColumnDimension('T')->setWidth(8);
                $sheet->getColumnDimension('U')->setWidth(8);
                $sheet->getColumnDimension('V')->setWidth(8);
                $sheet->getColumnDimension('W')->setWidth(8);
                $sheet->getColumnDimension('X')->setWidth(8);
                $sheet->getColumnDimension('Y')->setWidth(8);
                $sheet->getColumnDimension('Z')->setWidth(8);
                $sheet->getColumnDimension('AA')->setWidth(8);
                $sheet->getColumnDimension('AC')->setWidth(8); // SISIPAN RETUR PPH
                $sheet->getColumnDimension('AD')->setWidth(11);
                $sheet->getColumnDimension('AE')->setWidth(11); //POT KOP
                $sheet->getColumnDimension('AF')->setWidth(11); //JML TERIMA
                $sheet->getColumnDimension('AG')->setWidth(8); // NIK

                $sheet->getStyle('A' . $max_baris . ':AF' . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle('A' . $max_bariss . ':AF' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
                $sheet->getStyle('A' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('A' . $max_baris, 'No');
                $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
                $sheet->getStyle('B' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('B' . $max_baris, 'Nama Karyawan');
                $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
                $sheet->getStyle('C' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('C' . $max_baris, 'Masa Kerja');
                $sheet->mergeCells('D' . $max_baris . ':D' . $max_bariss);
                $sheet->getStyle('D' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('D' . $max_baris, 'Status Kary. Gol');
                $sheet->mergeCells('E' . $max_baris . ':E' . $max_bariss);
                $sheet->getStyle('E' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('E' . $max_baris, 'Gaji Pokok');
                $sheet->mergeCells('F' . $max_baris . ':H' . $max_baris); // colspan
                // $sheet->getStyle('F'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('F' . $max_baris, 'Tunjangan');
                $sheet->setCellValue('F' . $max_bariss, 'Jabatan');
                $sheet->setCellValue('G' . $max_bariss, 'Jen.Pek');
                $sheet->setCellValue('H' . $max_bariss, 'Mas.Ker');
                $sheet->mergeCells('I' . $max_baris . ':I' . $max_bariss);
                $sheet->getStyle('I' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('I' . $max_baris, 'Gaji per Bulan');
                $sheet->mergeCells('J' . $max_baris . ':M' . $max_baris);
                $sheet->setCellValue('J' . $max_baris, 'Premi Shift');
                // $sheet->getStyle('j'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');

                $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('J' . $max_bariss, 'Jml Jam1');
                $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('K' . $max_bariss, 'Jml Jam 2');
                $sheet->getStyle('L' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('L' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('M' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('M' . $max_bariss, 'Total Premi shift');
                $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_baris, 'Lbr1');
                $sheet->getStyle('N' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_baris, 'Lbr2');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('P' . $max_baris, 'Lbr3');
                $sheet->getStyle('P' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('P' . $max_bariss, 'Jml Jam');
                $sheet->mergeCells('Q' . $max_baris . ':R' . $max_baris);
                $sheet->getStyle('Q' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('Q' . $max_baris, 'Jumlah Lembur');
                $sheet->setCellValue('Q' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('R' . $max_bariss, 'Jml Upah');
                $sheet->mergeCells('S' . $max_baris . ':S' . $max_bariss);
                $sheet->getStyle('S' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('S' . $max_baris, 'Premi Hadir');
                $sheet->mergeCells('T' . $max_baris . ':T' . $max_bariss);
                $sheet->getStyle('T' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('T' . $max_baris, 'Transport');
                $sheet->mergeCells('U' . $max_baris . ':U' . $max_bariss);
                $sheet->setCellValue('U' . $max_baris, 'Makan');
                $sheet->mergeCells('V' . $max_baris . ':V' . $max_bariss);
                $sheet->setCellValue('V' . $max_baris, 'Asuransi');
                $sheet->mergeCells('W' . $max_baris . ':AA' . $max_baris);
                // $sheet->getStyle('W'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('W' . $max_baris, 'Potongan');
                $sheet->setCellValue('W' . $max_bariss, 'Pensiun');
                $sheet->setCellValue('X' . $max_bariss, 'Astek');
                $sheet->setCellValue('Y' . $max_bariss, 'SPMI');
                $sheet->setCellValue('Z' . $max_bariss, 'Mangkir');
                $sheet->setCellValue('AA' . $max_bariss, 'PPH21');
                $sheet->mergeCells('AB' . $max_baris . ':AE' . $max_baris);
                // $sheet->getStyle('AB'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('AB' . $max_baris, 'Bersih');
                $sheet->setCellValue('AB' . $max_bariss, 'Net + Asr');
                $sheet->setCellValue('AC' . $max_bariss, 'Retur PPH21');
                $sheet->setCellValue('AD' . $max_bariss, 'Net');
                $sheet->setCellValue('AE' . $max_bariss, 'Pot. Kopkar');
                $sheet->setCellValue('AF' . $max_bariss, 'Jml Terima');
                $sheet->setCellValue('AG' . $max_bariss, 'NIK');
                // $sheet->setCellValue('AD' . $max_bariss, 'Pot. Kopkar');
                // $sheet->setCellValue('AE' . $max_bariss, 'Jml Terima');
                // $sheet->setCellValue('AF' . $max_bariss, 'NIK');

                //  $new_baris = $new_baris+1;
                //  echo "baris ke : ".$max_baris;
                if ($fdepartment != '') {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }

                    foreach ($bag->result() as $b) {
                        // echo $b->indeks_hr."<br>";
                        $no = 0;
                        ${"total" . $b->recid_bag} = 0;
                        $bgapok = 0;
                        $bjbtn = 0;
                        $bjenpek = 0;
                        $bmasker = 0;
                        $bglobal = 0;
                        $bshift1 = 0;
                        $bshift2 = 0;
                        $btotshift = 0;
                        $btotushift = 0;
                        $blbr1 = 0;
                        $blbr2 = 0;
                        $blbr3 = 0;
                        $btotlbr = 0;
                        $btotulbr = 0;
                        $bpremi = 0;
                        $btrans = 0;
                        $bmak = 0;
                        $basr = 0;
                        $bpen = 0;
                        $bast = 0;
                        $bspmi = 0;
                        $bmang = 0;
                        $bpph = 0;
                        $bnetasr = 0;
                        $bnet = 0;
                        $bpotkop = 0;
                        $bterima = 0;

                        if ($fkaryawan != '') {
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        if ($karyawan->num_rows() > 0) {
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            $sheet->mergeCells('A' . $max_baris . ':AE' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->setCellValue('A' . $max_baris, "Bagian : " . $b->indeks_hr);

                            $max_baris = $sheet->getHighestRow();
                            $new_baris = $max_baris;
                            foreach ($karyawan->result() as $k) {
                                $new_baris = $new_baris + 1;
                                $recid_karyawan = $k->recid_karyawan;
                                $nik = $k->nik;
                                $nama_karyawan = $k->nama_karyawan;
                                $bagian = $k->indeks_hr;
                                $jabatan = $k->indeks_jabatan;
                                $tingkatan = $k->tingkatan;
                                $t_prestasi = $k->t_prestasi;
                                $penempatan = $k->penempatan;
                                $sts_jabatan = $k->sts_jabatan;
                                // $t_jbtn = $k->t_jabatan;
                                // $t_jenpek = $k->t_jen_pek;
                                // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                                $trakhir = date("Y-m-t", strtotime($tgl_akhir));
                                $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
                                $masker_tahun = $diff->format('%y');
                                // echo "masa kerja : $masker_tahun<br>";

                                // if($k->tingkatan == 1)
                                // {
                                //   if($masker_tahun < 1){
                                //     $uph_pokok = $k->gapok;
                                //   }else if($masker_tahun >=1 and $masker_tahun < 5)
                                //   {
                                //       $uph_pokok = $k->gapok + 10000;
                                //   }else if($masker_tahun >=5 and $masker_tahun <10)
                                //   {
                                //       $uph_pokok = $k->gapok + 20000;
                                //   }else if($masker_tahun >=10 and $masker_tahun <15)
                                //   {
                                //       $uph_pokok = $k->gapok + 30000;
                                //   }else if($masker_tahun >=15 and $masker_tahun <20)
                                //   {
                                //       $uph_pokok = $k->gapok + 40000;
                                //   }else{
                                //       $uph_pokok = $k->gapok + 50000;
                                //   }
                                // }else{
                                //     $uph_pokok = $k->gapok;
                                // }

                                $tahun = date('Y');
                                $thn_upah = $this->m_hris->tahun_upah();
                                foreach ($thn_upah->result() as $th) {
                                    if ($th->tahun == date('Y')) {
                                        $tahun = date('Y');
                                    } else {
                                        $tahun = $th->tahun;
                                    }
                                }

                                if ($tingkatan == 1) {
                                    if ($penempatan == 'Jakarta') {
                                        //pake umk jakarta
                                        $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            if ($sts_jabatan == "Advisor") {
                                                $uph_pokok =  (80 / 100) * $gp2->nilai;
                                            } else {
                                                $uph_pokok = $gp2->nilai;
                                            }
                                        }
                                    } else {
                                        $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            if ($sts_jabatan == "Advisor") {
                                                $uph_pokok = (80 / 100) * $gp2->nilai;
                                            } else {
                                                $uph_pokok = $gp2->nilai;
                                            }
                                        }
                                    }
                                } else {
                                    // pa nofi advisor gajiikut ke tahun 2024
                                    if ($sts_jabatan == "Advisor") {
                                        $gapok_baru = $this->m_hris->gapok($tingkatan, 2024);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            $uph_pokok = (80 / 100) * $gp2->nilai;
                                        }
                                    } else {
                                        $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            $uph_pokok = $gp2->nilai;
                                        }
                                    }
                                }

                                if ($k->tingkatan == 1) {
                                    //operator tunjangan jabatan = 0
                                    $t_jbtn = 0;
                                    // cek tunjangan jenis pekerjaan by bagian
                                    $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun);
                                    if ($tjenpek2->num_rows() > 0) {
                                        foreach ($tjenpek2->result() as $t2) {
                                            $t_jenpek = $t2->nilai;
                                        }
                                    } else {
                                        $t_jenpek = 0;
                                    }

                                    // echo "t_jbtn : $t_jbtn<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";

                                } else {
                                    //wakaru ke atas, t_jenpek = 0
                                    $t_jenpek = 0;
                                    //cek tunjangan jabatannya
                                    // pa nofi advisor gajiikut ke tahun 2024

                                    if ($sts_jabatan == "Advisor") {
                                        $tjbtn2 = $this->m_hris->tjabatan($tingkatan, 2024);
                                        foreach ($tjbtn2->result() as $t2) {
                                            $t_jbtn = (80 / 100) * $t2->nilai;
                                        }
                                    } else {
                                        $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun);
                                        foreach ($tjbtn2->result() as $t2) {
                                            $t_jbtn = $t2->nilai;
                                        }
                                    }
                                }
                                $t_masker = $masker_tahun * $uph_masker;
                                $global = $uph_pokok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;

                                // if ($sts_jabatan == "Advisor") {
                                //     $global = ((80 / 100) * ($uph_pokok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
                                // } else {
                                //     $global = $uph_pokok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
                                // }

                                /* START LEMBURAN */

                                $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 14);
                                $s1 = $shift1->num_rows();
                                $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 15);
                                $s2 = $shift2->num_rows();
                                $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 16);
                                $s3 = $shift3->num_rows();

                                $lemburan = $this->m_lembur->karyawan_lembur_report($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                $tot_lbr = 0;
                                foreach ($lemburan->result() as $l) {
                                    // $lembur1 = round($l->lembur1, 1);
                                    // $lembur2 = round($l->lembur2, 1);
                                    // $lembur3 = round($l->lembur3, 1);
                                    $lembur1 = $l->lembur1;
                                    if ($l->lembur1 !== null) {
                                        $lembur1 = round($l->lembur1, 1);
                                    } else {
                                        $lembur1 = 0;
                                    }

                                    $lembur2 = $l->lembur2;
                                    if ($l->lembur1 !== null) {
                                        $lembur2 = round($l->lembur2, 1);
                                    } else {
                                        $lembur2 = 0;
                                    }

                                    $lembur3 = $l->lembur3;
                                    if ($l->lembur1 !== null) {
                                        $lembur3 = round($l->lembur3, 1);
                                    } else {
                                        $lembur3 = 0;
                                    }
                                }
                                // Cek adjustment lemburan
                                $atrans = 0;
                                $amakan = 0;
                                $as1 = 0;
                                $as2 = 0;
                                $adj_upah = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                if ($adj_upah->num_rows() > 0) {
                                    foreach ($adj_upah->result() as $al) {
                                        $as1 = $as1 + $al->premi1;
                                        $as2 = $as2 + $al->premi2;
                                        $lembur1 = $lembur1 + $al->jam_lbr1;
                                        $lembur2 = $lembur2 + $al->jam_lbr2;
                                        $lembur3 = $lembur3 + $al->jam_lbr3;

                                        $atrans = $al->jml_transport;
                                        $amakan = $al->jml_makan;
                                    }
                                } else {
                                    $atrans = 0;
                                    $amakan = 0;
                                }


                                $slembur1 = $s1 + $s2 + $as1;
                                $uslembur1 = $slembur1 * $shift_lbr1;
                                $slembur2 = $s3 + $as2;
                                $uslembur2 = $slembur2 * $shift_lbr2;
                                $utot_shift = $uslembur1 + $uslembur2;


                                $uph_lbr1 = round(($global / $bagi_lembur) * $kali_lembur1);
                                $lbr1 = $uph_lbr1 * $lembur1;
                                $uph_lbr2 = round(($global / $bagi_lembur) * $kali_lembur2);
                                $lbr2 = $uph_lbr2 * $lembur2;
                                $uph_lbr3 = round(($global / $bagi_lembur) * $kali_lembur3);
                                $lbr3 = $uph_lbr3 * $lembur3;
                                $tot_lbr = $lbr1 + $lbr2 + $lbr3;

                                /* END LEMBURAN */

                                /* START PREMI */
                                $tingkatan = $k->tingkatan;
                                if ($tingkatan >= 1 and $tingkatan < 4) {
                                    //param premi hadir operator - karu, id = 2
                                    $ph = $this->m_hris->param_upah_id(2);
                                    foreach ($ph->result() as $p) {
                                        $premi = $p->nilai;
                                    }
                                } else if ($tingkatan >= 4 and $tingkatan <= 5) {
                                    //param premi hadir wakasi - kasi, id = 3
                                    $ph = $this->m_hris->param_upah_id(3);
                                    foreach ($ph->result() as $p) {
                                        $premi = $p->nilai;
                                    }
                                } else {
                                    $premi = 0;
                                }

                                $total_hadir = $this->m_absenbarcode->hitung_kerja($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                $jml_hadir = $total_hadir->num_rows();
                                $upah_hadir = $jml_hadir * $premi;

                                // potong terlambat terencana
                                $pr = $this->m_hris->param_upah_id(4);
                                foreach ($pr->result() as $pr1) {
                                    $ppersenpr = $pr1->nilai;
                                }

                                // potong terlambat tidak terencana
                                $ptr = $this->m_hris->param_upah_id(5);
                                foreach ($ptr->result() as $ptr1) {
                                    $ppersenptr = $ptr1->nilai;
                                }

                                // potong izin keluar > 2 jam
                                $pk = $this->m_hris->param_upah_id(6);
                                foreach ($pk->result() as $pk1) {
                                    $ppersenpk = $pk1->nilai;
                                }

                                // potong pulang cepat
                                $pp = $this->m_hris->param_upah_id(7);
                                foreach ($pp->result() as $pp1) {
                                    $ppersenpp = $pp1->nilai;
                                }

                                $trencana = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Terlambat Terencana');
                                $prencana = $trencana->num_rows() * ($premi * ($ppersenpr / 100));

                                $tdkrencana = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Terlambat Tidak Terencana');
                                $ptdrencana = $tdkrencana->num_rows() * ($premi * ($ppersenptr / 100));

                                $pulang = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Pulang');
                                $ppulang = $pulang->num_rows() * ($premi * ($ppersenpp / 100));

                                $keluar = $this->m_absenbarcode->hitung_izin($tgl_mulai, $tgl_akhir, $recid_karyawan, 'Keluar');
                                $cnt_keluar = 0;
                                foreach ($keluar->result() as $i) {
                                    if ($i->over_durasi >= 2) {
                                        $cnt_keluar = $cnt_keluar + 1;
                                    }
                                }
                                $pkeluar = $cnt_keluar * ($premi * ($ppersenpk / 100));
                                $bonus = $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana);
                                /* END PREMI */

                                /* UANG TRANSPORT */
                                // transport lembur holiday
                                $ut = $this->m_hris->param_upah_id(14);
                                foreach ($ut->result() as $ut1) {
                                    $utrans2 = $ut1->nilai;
                                }
                                // transport lembur terusan
                                $ut = $this->m_hris->param_upah_id(39);
                                foreach ($ut->result() as $ut1) {
                                    $utrans1 = $ut1->nilai;
                                }
                                // transport lembur terusan
                                $transport = $this->m_lembur->transport_lembur_karyawan($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                $trsp = $transport->num_rows();
                                $transport2 = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                $trsp2 = $transport2->num_rows();

                                // transport shift1,2,3
                                $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 14);
                                $s1 = $shift1->num_rows();
                                $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 15);
                                $s2 = $shift2->num_rows();
                                $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai, $tgl_akhir, 16);
                                $s3 = $shift3->num_rows();
                                $tot_shift = $s1 + $s2 + $s3;
                                $uang_transport1 = $trsp * $utrans1;
                                $uang_transport2 = ($trsp2 + $tot_shift) * $utrans2;
                                $uang_transport_adj = $atrans * $utrans2;
                                // echo $atrans;
                                $uang_transport = $uang_transport1 + $uang_transport2 + $uang_transport_adj;
                                // $uang_transport = $uang_transport1 + $uang_transport2;
                                /* END UANG TRANSPORT */

                                /* START UANG MAKAN */
                                // uang makan
                                $um = $this->m_hris->param_upah_id(15);
                                foreach ($um->result() as $um1) {
                                    $umakan = $um1->nilai;
                                }
                                $uang_makan = $s3 * $umakan;

                                // makan lembur
                                $mkn = $this->m_lembur->makan_lembur($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                // $makaan = $mkn->num_rows();
                                $makaan = ($mkn->num_rows()) + $amakan;
                                $makan = $makaan * $umakan;
                                $uang_makan = $uang_makan + $makan;

                                // /* START ADJUSTMENT */
                                // $adj_makan = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                // if($adj_makan->num_rows() > 0)
                                // {
                                //     foreach ($adj_makan->result() as $am) {
                                //         $uang_makan = $uang_makan + $makan + ($am->jml_makan * $umakan);
                                //     }
                                // }
                                /* END ADJUSTMENT */

                                // $uang_makan = 13000;
                                /* END UANG MAKAN */

                                /* START ASTEK */
                                // astek jp
                                if ($k->cek_bpjs_tk == '1') {
                                    $ajp = $this->m_hris->param_upah_id(19);
                                    foreach ($ajp->result() as $ajp1) {
                                        $asuransi_jp = $ajp1->nilai;
                                    }

                                    // astek jht
                                    $ajht = $this->m_hris->param_upah_id(20);
                                    foreach ($ajht->result() as $ajht1) {
                                        $asuransi_jht = $ajht1->nilai;
                                    }
                                } else {
                                    $asuransi_jp = 0;
                                    $asuransi_jht = 0;
                                }


                                //astek bpjs kesehatan
                                if ($k->cek_bpjs_kes == '1') {
                                    $abkes = $this->m_hris->param_upah_id(21);
                                    foreach ($abkes->result() as $abkes1) {
                                        $asuransi_bkes = $abkes1->nilai;
                                    }
                                } else {
                                    $asuransi_bkes = 0;
                                }

                                $kali_astek = $asuransi_jp + $asuransi_jht + $asuransi_bkes;
                                $astek = $global * ($kali_astek / 100);
                                /* END ASTEK */

                                /* START ASURANSI PERUSAHAAN */
                                if ($k->cek_bpjs_tk == '1') {
                                    //persentase jkm
                                    $jkm = $this->m_hris->param_upah_id(16);
                                    foreach ($jkm->result() as $jkm1) {
                                        $ujkm = $jkm1->nilai;
                                    }

                                    // persentase jkk
                                    $jkk = $this->m_hris->param_upah_id(17);
                                    foreach ($jkk->result() as $jkk1) {
                                        $ujkk = $jkk1->nilai;
                                    }
                                } else {
                                    $ujkm = 0;
                                    $ujkk = 0;
                                }

                                if ($k->cek_bpjs_kes == '1') {
                                    //persentase bpjs kesehatan
                                    $bpjs = $this->m_hris->param_upah_id(18);
                                    foreach ($bpjs->result() as $bpjs1) {
                                        $ubpjs = $bpjs1->nilai;
                                    }
                                } else {
                                    $ubpjs = 0;
                                }


                                $per_asr = $ujkm + $ujkk + $ubpjs;
                                $uasuransi = $global * ($per_asr / 100);
                                /* END ASURANSI PERUSAHAAN */

                                /* START PPH21 */
                                $bruto = $global + $tot_lbr + $utot_shift + ($upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana) + $uang_transport + $uang_makan + $uasuransi);

                                /* START PENSIUN */
                                if ($k->pensiun == "Ya") {
                                    // persentase dplk
                                    $dplk = $this->m_hris->param_upah_id(22);
                                    foreach ($dplk->result() as $dplk1) {
                                        $dplk_aia = $dplk1->nilai;
                                    }
                                    $pot_dplk = $global * ($dplk_aia / 100);
                                } else {
                                    $pot_dplk = 0;
                                }
                                /* END PENSIUN */

                                /* START SPMI */
                                if ($k->lspmi == "Ya") {
                                    // spmi
                                    $spmi = $this->m_hris->param_upah_id(23);
                                    foreach ($spmi->result() as $spmi1) {
                                        $pspmi = $spmi1->nilai;
                                    }
                                    $uspmi = ($uph_pokok * ($pspmi / 100)) + 5000;
                                } else {
                                    $uspmi = 0;
                                }
                                /* END SPMI */

                                /* START MANGKIR */
                                $mkr = $this->m_absenbarcode->hitung_mangkir($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                // potongan mangir
                                $mangkir = $this->m_hris->param_upah_id(24);
                                foreach ($mangkir->result() as $mangkir1) {
                                    $pmangkir = $mangkir1->nilai;
                                }
                                $umangkir = $mkr->num_rows() * (($pmangkir / 100) * $global);
                                /* END MAGKIR */

                                /* START PTKP */
                                $sts_kawin = $k->sts_penunjang;
                                if ($sts_kawin == "K0") {
                                    // ptkp K0
                                    $ptkp = $this->m_hris->param_upah_id(28);
                                } else if ($sts_kawin == "K1") {
                                    // ptkp K1
                                    $ptkp = $this->m_hris->param_upah_id(29);
                                } else if ($sts_kawin == "K2") {
                                    $ptkp = $this->m_hris->param_upah_id(30);
                                } else if ($sts_kawin == "K3") {
                                    // ptkp K3
                                    $ptkp = $this->m_hris->param_upah_id(31);
                                } else if ($sts_kawin == "TK1") {
                                    // ptkp TK/1
                                    $ptkp = $this->m_hris->param_upah_id(45);
                                } else if ($sts_kawin == "TK2") {
                                    // ptkp TK/2
                                    $ptkp = $this->m_hris->param_upah_id(46);
                                } else if ($sts_kawin == "TK3") {
                                    // ptkp TK/3
                                    $ptkp = $this->m_hris->param_upah_id(47);
                                } else {
                                    // ptkp TK
                                    $ptkp = $this->m_hris->param_upah_id(27);
                                }
                                foreach ($ptkp->result() as $ptkp1) {
                                    $uptkp = $ptkp1->nilai;
                                }
                                /* END PTKP */

                                /* START BIAYA JABATAN */
                                //biaya jabatan
                                $bjab = $this->m_hris->param_upah_id(25);
                                foreach ($bjab->result() as $bjab1) {
                                    $b_jabatan = $bjab1->nilai;
                                }

                                //max biaya jabatan
                                $max_jbtn = $this->m_hris->param_upah_id(26);
                                foreach ($max_jbtn->result() as $max_jbtn1) {
                                    $max_jab = $max_jbtn1->nilai;
                                }

                                $biaya_jabatan = $bruto * ($b_jabatan / 100);
                                if ($biaya_jabatan > $max_jab) {
                                    $biaya_jabatan = $max_jab;
                                }
                                /* END BIAYA JABATAN */

                                $astek_pph = $asuransi_jp + $asuransi_jht;
                                $uapph = $global * ($astek_pph / 100);
                                $tot_pot = $uptkp + $biaya_jabatan + $uapph + $pot_dplk;
                                $pkp = $bruto - $tot_pot;
                                /* START LAPISAN PKP */
                                // lapisan pkp per bulan
                                $lvlpkp = [40, 41, 42];
                                $lap_pkp1 = 0;
                                $lap_pkp2 = 0;
                                $lap_pkp3 = 0;
                                for ($i = 0; $i < count($lvlpkp); $i++) {

                                    $lapisan = $this->m_hris->param_upah_id($lvlpkp[$i]);
                                    foreach ($lapisan->result() as $hasil) {
                                        $x = $i + 1;
                                        ${"lap_pkp" . $x} = $hasil->nilai;
                                        // echo ${"lap_pkp" . $x};
                                    }
                                }

                                // echo $lap_pkp1;
                                $cek_npwp = $k->cek_npwp;
                                if ($cek_npwp == '1') {
                                    $perpkp = [32, 33, 34];
                                } else {
                                    $perpkp = [44, 33, 34];
                                }
                                $per_pkp1 = 0;
                                $per_pkp2 = 0;
                                $per_pkp3 = 0;
                                for ($x = 0; $x < count($perpkp); $x++) {
                                    $persen = $this->m_hris->param_upah_id($perpkp[$x]);
                                    foreach ($persen->result() as $hasil2) {
                                        ${"per_pkp" . ($x + 1)} = $hasil2->nilai;
                                    }
                                }

                                // echo $per_pkp[$x+1]."<br>";
                                // echo ($lap_pkp1)."<br>";
                                if ($pkp >= 0 and $pkp <= $lap_pkp1) {
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pphl2 = 0;
                                    $pphl3 = 0;
                                } else if ($pkp > $lap_pkp1 and $pkp <= $lap_pkp2) {
                                    // echo "lapisan 2<br>";
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pkp = $pkp - $lap_pkp1;
                                    if ($pkp >= $lap_pkp2) {
                                        $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                                    } else {
                                        $pphl2 = round($pkp * ($per_pkp2 / 100));
                                    }
                                    // echo "Lapisan 2 : ".$pphl2."<br>";
                                    $pphl3 = 0;
                                } else if ($pkp > $lap_pkp2 and $pkp <= $lap_pkp3) {
                                    // echo "lapisan 3";
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pkp2 = $pkp - $lap_pkp1;
                                    if ($pkp2 >= $lap_pkp2) {
                                        $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                                    } else {
                                        $pphl2 = round($pkp * ($per_pkp2 / 100));
                                    }
                                    $pkp3 = $pkp - $lap_pkp1 - $lap_pkp2;
                                    $pphl3 = round($pkp3 * ($per_pkp3 / 100));
                                } else {
                                    $pphl1 = 0;
                                    $pphl2 = 0;
                                    $pphl3 = 0;
                                }
                                /* END LAPISAN PKP */
                                $tot_pph = $pphl1 + $pphl2 + $pphl3;

                                /* END PPH21 */

                                /* NETT */
                                $net_asr = $bruto - $pot_dplk - $astek - $uspmi - $umangkir - $tot_pph;
                                $net = $net_asr - $uasuransi;
                                $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;

                                $kopkar = $this->m_lembur->potongan_kopkar($recid_karyawan, $tahun, $bulan);
                                if ($kopkar->num_rows() > 0) {
                                    foreach ($kopkar->result() as $kop) {
                                        $pot_kopkar = $kop->potongan;
                                    }
                                } else {
                                    $pot_kopkar = 0;
                                }
                                // $jml_terima = $net - $pot_kopkar;
                                $jml_terima = $net - $pot_kopkar + $tot_pph;

                                /* -----------------------START SAVE DATA TO DATABASE--------------------- */
                                if ($tipe == 1) {
                                    $data = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'bulan'             => $bulan,
                                        'tahun'             => $tahun,
                                        'periode_awal'      => $tgl_mulai,
                                        'periode_akhir'     => $tgl_akhir,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker,
                                        'upokok'            => $uph_pokok,
                                        'tjbtn'             => $t_jbtn,
                                        'tjenpek'           => $t_jenpek,
                                        'uglobal'           => $global,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $premi,
                                        'trencana'          => $trencana->num_rows(),
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana->num_rows(),
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang->num_rows(),
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar->num_rows(),
                                        'keluar2'           => $cnt_keluar,
                                        'rp_keluar2'        => $pkeluar,
                                        'premi'             => $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana),
                                        'uang_transport'    => $uang_transport,
                                        'uang_makan'        => $uang_makan,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pphl1,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'netasr'            => $net_asr,
                                        'netto'             => $net,
                                        'pot_kopkar'        => $pot_kopkar,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_upah($data);
                                    $last = $this->m_lembur->last_upahid();
                                    foreach ($last->result() as $lu) {
                                        $recid_upah = $lu->recid_upah;
                                    }

                                    $datalog = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'id_unik'           => $iduniq,
                                        'recid_upah'        => $recid_upah,
                                        'bulan'             => $bulan,
                                        'tahun'             => $tahun,
                                        'periode_awal'      => $tgl_mulai,
                                        'periode_akhir'     => $tgl_akhir,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker,
                                        'upokok'            => $uph_pokok,
                                        'tjbtn'             => $t_jbtn,
                                        'tjenpek'           => $t_jenpek,
                                        'uglobal'           => $global,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $premi,
                                        'trencana'          => $trencana->num_rows(),
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana->num_rows(),
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang->num_rows(),
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar->num_rows(),
                                        'keluar2'           => $cnt_keluar,
                                        'rp_keluar2'        => $pkeluar,
                                        'premi'             => $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana),
                                        'uang_transport'    => $uang_transport,
                                        'uang_makan'        => $uang_makan,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pphl1,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'netasr'            => $net_asr,
                                        'netto'             => $net,
                                        'pot_kopkar'        => $pot_kopkar,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_upahlog($datalog);
                                }
                                /* -----------------------END SAVE DATA TO DATABASE--------------------- */

                                /* --------- START LOAD DATA DETAIL TO TABLE--------- */
                                // $new_rows = 'A'.$new_baris;
                                $no = $no + 1;
                                $sheet->setCellValue('A' . $new_baris, $no . ".");
                                $sheet->setCellValue('B' . $new_baris, $nama_karyawan);
                                $sheet->getStyle('C' . $new_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                                $sheet->setCellValue('C' . $new_baris, $masker_tahun);
                                $sheet->setCellValue('D' . $new_baris, $sts_pph);
                                $sheet->setCellValue('E' . $new_baris, $uph_pokok);
                                $bgapok = $bgapok + $uph_pokok;
                                $sheet->setCellValue('F' . $new_baris, $t_jbtn);
                                $bjbtn = $bjbtn + $t_jbtn;
                                $sheet->setCellValue('G' . $new_baris, $t_jenpek);
                                $bjenpek = $bjenpek + $t_jenpek;
                                $sheet->setCellValue('H' . $new_baris, $t_masker);
                                $bmasker = $bmasker + $t_masker;
                                $sheet->setCellValue('I' . $new_baris, $global);
                                $bglobal = $bglobal + $global;
                                $sheet->setCellValue('J' . $new_baris, $slembur1);
                                $bshift1 = $bshift1 + $slembur1;
                                $sheet->setCellValue('K' . $new_baris, $slembur2);
                                $bshift2 = $bshift2 + $slembur2;
                                $sheet->setCellValue('L' . $new_baris, $slembur2 + $slembur1);
                                $btotshift = $btotshift + ($slembur2 + $slembur1);
                                $sheet->setCellValue('M' . $new_baris, $utot_shift);
                                $btotushift = $btotushift + $utot_shift;
                                $sheet->setCellValue('N' . $new_baris, $lembur1);
                                $blbr1 = $blbr1 + $lembur1;
                                $sheet->setCellValue('O' . $new_baris, $lembur2);
                                $blbr2 = $blbr2 + $lembur2;
                                $sheet->setCellValue('P' . $new_baris, $lembur3);
                                $blbr3 = $blbr3 + $lembur3;
                                $sheet->setCellValue('Q' . $new_baris, $lembur3 + $lembur2 + $lembur1);
                                $btotlbr = $btotlbr + ($lembur3 + $lembur2 + $lembur1);
                                $sheet->setCellValue('R' . $new_baris, round($tot_lbr));
                                $btotulbr = $btotulbr + $tot_lbr;
                                $sheet->setCellValue('S' . $new_baris, round($bonus));
                                $bpremi = $bpremi + $bonus;
                                $sheet->setCellValue('T' . $new_baris, $uang_transport);
                                $btrans = $btrans +  $uang_transport;
                                $sheet->setCellValue('U' . $new_baris, $uang_makan);
                                $bmak = $bmak + $uang_makan;
                                $sheet->setCellValue('V' . $new_baris, round($uasuransi));
                                $basr = $basr + $uasuransi;
                                $sheet->setCellValue('W' . $new_baris, round($pot_dplk));
                                $bpen = $bpen + $pot_dplk;
                                $sheet->setCellValue('X' . $new_baris, round($astek));
                                $bast = $bast + $astek;
                                $sheet->setCellValue('Y' . $new_baris, round($uspmi));
                                $bspmi = $bspmi + $uspmi;
                                $sheet->setCellValue('Z' . $new_baris, round($umangkir));
                                $bmang = $bmang + $umangkir;
                                $sheet->setCellValue('AA' . $new_baris, round($tot_pph));
                                $bpph = $bpph + $tot_pph;
                                $sheet->setCellValue('AB' . $new_baris, round($net_asr));
                                $bnetasr = $bnetasr + $net_asr;
                                $sheet->setCellValue('AC' . $new_baris, round($tot_pph));
                                $sheet->setCellValue('AD' . $new_baris, round($net  + $tot_pph));
                                $bnet = $bnet + $net + $tot_pph;
                                $sheet->setCellValue('AE' . $new_baris, round($pot_kopkar));
                                $bpotkop = $bpotkop + $pot_kopkar;
                                $sheet->setCellValue('AF' . $new_baris, round($jml_terima));
                                $bterima = $bterima + $jml_terima;
                                $sheet->setCellValue('AG' . $new_baris, round($nik));
                                // $sheet->setCellValue('AD' . $new_baris, round($pot_kopkar));
                                // $bpotkop = $bpotkop + $pot_kopkar;
                                // $sheet->setCellValue('AE' . $new_baris, round($jml_terima));
                                // $bterima = $bterima + $jml_terima;
                                // $sheet->setCellValue('AF' . $new_baris, round($nik));
                                $bnik = "";
                                /* --------- END LOAD DATA DETAIL TO TABLE--------- */
                            } // loop karyawan
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            /* --------- START LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                            $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->getStyle('A' . $max_baris)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheet->setCellValue('A' . $max_baris, 'Sub Total');
                            $sheet->setCellValue('I' . $max_baris, $bglobal);
                            $sheet->setCellValue('J' . $max_baris, $bshift1);
                            $sheet->setCellValue('K' . $max_baris, $bshift2);
                            $sheet->setCellValue('L' . $max_baris, $btotshift);
                            $sheet->setCellValue('M' . $max_baris, $btotushift);
                            $sheet->setCellValue('N' . $max_baris, $blbr1);
                            $sheet->setCellValue('O' . $max_baris, $blbr2);
                            $sheet->setCellValue('P' . $max_baris, $blbr3);
                            $sheet->setCellValue('Q' . $max_baris, $btotlbr);
                            $sheet->setCellValue('R' . $max_baris, round($btotulbr));
                            $sheet->setCellValue('S' . $max_baris, round($bpremi));
                            $sheet->setCellValue('T' . $max_baris, $btrans);
                            $sheet->setCellValue('U' . $max_baris, $bmak);
                            $sheet->setCellValue('V' . $max_baris, round($basr));
                            $sheet->setCellValue('W' . $max_baris, round($bpen));
                            $sheet->setCellValue('X' . $max_baris, round($bast));
                            $sheet->setCellValue('Y' . $max_baris, round($bspmi));
                            $sheet->setCellValue('Z' . $max_baris, round($bmang));
                            $sheet->setCellValue('AA' . $max_baris, round($bpph));
                            $sheet->setCellValue('AB' . $max_baris, round($bnetasr));
                            $sheet->setCellValue('AC' . $max_baris, round($bpph));
                            $sheet->setCellValue('AD' . $max_baris, round($bnet));
                            $sheet->setCellValue('AE' . $max_baris, round($bpotkop));
                            $sheet->setCellValue('AF' . $max_baris, round($bterima));
                            $sheet->setCellValue('AG' . $max_baris, "");
                            // $sheet->setCellValue('AD' . $max_baris, round($bpotkop));
                            // $sheet->setCellValue('AE' . $max_baris, round($bterima));
                            // $sheet->setCellValue('AF' . $max_baris, "");
                            /* --------- END LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                        } // karyawan > 0
                        /* --------- START CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        $pgapok = $pgapok + $bgapok;
                        $pjbtn = $pjbtn + $bjbtn;
                        $pjenpek = $pjenpek  + $bjenpek;
                        $pmasker = $pmasker + $bmasker;
                        $pglobal = $pglobal + $bglobal;
                        $pshift1 = $pshift1 + $bshift1;
                        $pshift2 = $pshift2 + $bshift2;
                        $ptotshift = $ptotshift + $btotshift;
                        $ptotushift = $ptotushift + $btotushift;
                        $plbr1 = $plbr1 + $blbr1;
                        $plbr2 = $plbr2 + $blbr2;
                        $plbr3 = $plbr3  + $blbr3;
                        $ptotlbr = $ptotlbr + $btotlbr;
                        $ptotulbr = $ptotulbr + $btotulbr;
                        $ppremi = $ppremi + $bpremi;
                        $ptrans = $ptrans + $btrans;
                        $pmak = $pmak  + $bmak;
                        $pasr = $pasr + $basr;
                        $ppen = $ppen + $bpen;
                        $past = $past  + $bast;
                        $ppspmi = $ppspmi + $bspmi;
                        $pmang = $pmang + $bmang;
                        $ppph = $ppph + $bpph;
                        $pnetasr = $pnetasr + $bnetasr;
                        $pnet = $pnet + $bnet;
                        $ppotkop = $ppotkop + $bpotkop;
                        $pterima = $pterima + $bterima;
                        /* --------- END CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        /* --------- START CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                        ${"total" . $b->recid_bag} = array(
                            "bagian"    => $b->indeks_hr,
                            "global"    => round($bglobal),
                            "bshift1"    => round($bshift1),
                            "bshift2"    => round($bshift2),
                            "btotshift"    => round($btotshift),
                            "btotushift"    => round($btotushift),
                            "blbr1"    => round($blbr1),
                            "blbr2"    => round($blbr2),
                            "blbr3"    => round($blbr3),
                            "btotlbr"    => round($btotlbr),
                            "btotulbr"    => round($btotulbr),
                            "bpremi"    => round($bpremi),
                            "btrans"    => round($btrans),
                            "bmak"    => round($bmak),
                            "basr"    => round($basr),
                            "bpen"    => round($bpen),
                            "bast"    => round($bast),
                            "bspmi"    => round($bspmi),
                            "bmang"    => round($bmang),
                            "bpph"    => round($bpph),
                            "bnetasr"    => round($bnetasr),
                            "nett"      => round($bnet),
                            "bpotkop"      => round($bpotkop),
                            "bterima"      => round($bterima),
                        );
                        array_push(${"rekap_" . $paygroup[$pyg]},  ${"total" . $b->recid_bag});
                        /* --------- END CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                    } // filter bagian
                } // filter department
                ${"totpaygroup" . $paygroup[$pyg]} = array(
                    "pglobal"    => round($pglobal),
                    "pshift1"    => round($pshift1),
                    "pshift2"    => round($pshift2),
                    "ptotshift"    => round($ptotshift),
                    "ptotushift"    => round($ptotushift),
                    "plbr1"    => round($plbr1),
                    "plbr2"    => round($plbr2),
                    "plbr3"    => round($plbr3),
                    "ptotlbr"    => round($ptotlbr),
                    "ptotulbr"    => round($ptotulbr),
                    "ppremi"    => round($ppremi),
                    "ptrans"    => round($ptrans),
                    "pmak"    => round($pmak),
                    "pasr"    => round($pasr),
                    "ppen"    => round($ppen),
                    "past"    => round($past),
                    "pspmi"    => round($ppspmi),
                    "pmang"    => round($pmang),
                    "ppph"    => round($ppph),
                    "pnetasr"    => round($pnetasr),
                    "pnet"      => round($pnet),
                    "ppotkop"      => round($ppotkop),
                    "pterima"      => round($pterima),
                );
                //   array_push( ${"totpaygroup" . $paygroup[$pyg]},  ${"tot_paygroup" .  $paygroup[$pyg]});
                // echo $paygroup[$pyg]." - ".$pnet."<br>";
                $tot_global = $tot_global + $pglobal;
                $tot_shift1 = $tot_shift1 + $pshift1;
                $tot_shift2 = $tot_shift2 + $pshift2;
                $tot_totshift = $tot_totshift + $ptotshift;
                $tot_ushift = $tot_ushift + $ptotushift;
                $tot_lbr1 = $tot_lbr1 + $plbr1;
                $tot_lbr2 = $tot_lbr2 + $plbr2;
                $tot_lbr3 = $tot_lbr3 + $plbr3;
                $tot_totlbr = $tot_totlbr + $ptotlbr;
                $tot_ulbr = $tot_ulbr + $ptotulbr;
                $tot_premi = $tot_premi + $ppremi;
                $tot_trans = $tot_trans + $ptrans;
                $tot_mak = $tot_mak + $pmak;
                $tot_asr = $tot_asr + $pasr;
                $tot_pensiun = $tot_pensiun + $ppen;
                $tot_astek = $tot_astek + $past;
                $tot_spmi = $tot_spmi + $ppspmi;
                $tot_mangkir = $tot_mangkir + $pmang;
                $tot_cpph = $tot_cpph + $ppph;
                $tot_netasr = $tot_netasr + $pnetasr;
                $tot_net = $tot_net + $pnet;
                $tot_potkop = $tot_potkop + $ppotkop;
                $tot_terima = $tot_terima + $pterima;
                // echo $tot_astek;
                /* --------- START LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $max_baris = $sheet->getHighestRow();
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                $sheet->getStyle('A' . $max_baris)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('A' . $max_baris, 'TOTAL');
                $sheet->setCellValue('I' . $max_baris, $pglobal);
                $sheet->setCellValue('J' . $max_baris, $pshift1);
                $sheet->setCellValue('K' . $max_baris, $pshift2);
                $sheet->setCellValue('L' . $max_baris, $ptotshift);
                $sheet->setCellValue('M' . $max_baris, $ptotushift);
                $sheet->setCellValue('N' . $max_baris, $plbr1);
                $sheet->setCellValue('O' . $max_baris, $plbr2);
                $sheet->setCellValue('P' . $max_baris, $plbr3);
                $sheet->setCellValue('Q' . $max_baris, $ptotlbr);
                $sheet->setCellValue('R' . $max_baris, round($ptotulbr));
                $sheet->setCellValue('S' . $max_baris, round($ppremi));
                $sheet->setCellValue('T' . $max_baris, $ptrans);
                $sheet->setCellValue('U' . $max_baris, $pmak);
                $sheet->setCellValue('V' . $max_baris, round($pasr));
                $sheet->setCellValue('W' . $max_baris, round($ppen));
                $sheet->setCellValue('X' . $max_baris, round($past));
                $sheet->setCellValue('Y' . $max_baris, round($ppspmi));
                $sheet->setCellValue('Z' . $max_baris, round($pmang));
                $sheet->setCellValue('AA' . $max_baris, round($ppph));
                $sheet->setCellValue('AB' . $max_baris, round($pnetasr));
                $sheet->setCellValue('AC' . $max_baris, round($ppph));
                $sheet->setCellValue('AD' . $max_baris, round($pnet));
                $sheet->setCellValue('AE' . $max_baris, round($ppotkop));
                $sheet->setCellValue('AF' . $max_baris, round($pterima));
                $sheet->setCellValue('AG' . $max_baris, "");
                // $sheet->setCellValue('AD' . $max_baris, round($ppotkop));
                // $sheet->setCellValue('AE' . $max_baris, round($pterima));
                // $sheet->setCellValue('AF' . $max_baris, "");
                /* --------- END LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $akhir_baris = $max_baris;
                $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris . ":AG" . $akhir_baris) // + RETUR
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                /* --------- START FOOTER APPROVAL --------- */
                $tgl = date('d M Y');
                $max_baris = $max_baris + 2;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Diperiksa & Disetujui Oleh');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Mengetahui');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Cimahi, ' . $tgl);

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Finance');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'HC & GA Manager');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Pembuat Data Payroll');

                $max_baris = $max_baris + 3;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Kisty Riagustina');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Diah Nur Kusumawardani');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Mega Oktaviani');
                /* --------- END FOOTER APPROVAL --------- */
                $sheet->setBreak('A' . $max_baris, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
            } // loop paygroup

            /* --------- START FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL) --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji1');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji1");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran Gaji Karyawan Bulanan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Untuk Bulan : ' . $nama_bulan . ' ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Perhitungan Lembur Mulai Tgl ' . $tgl_mulai . ' s/d ' . $tgl_akhir);
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;

            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
            $sheet->setCellValue('C' . $max_baris, 'Gaji Per Bulan');

            $sheet->mergeCells('D' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('D' . $max_baris, 'Premi Shift');
            $sheet->getStyle('D' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('D' . $max_bariss, 'Jml Jam1');
            $sheet->getStyle('E' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('E' . $max_bariss, 'Jml Jam 2');
            $sheet->getStyle('F' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('F' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('G' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('G' . $max_bariss, 'Total Premi shift');
            $sheet->getStyle('H' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_baris, 'Lbr1');
            $sheet->getStyle('H' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('I' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('I' . $max_baris, 'Lbr2');
            $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('J' . $max_baris, 'Lbr3');

            $sheet->mergeCells('K' . $max_baris . ':L' . $max_baris);
            $sheet->setCellValue('K' . $max_baris, 'Jumlah Lembur');
            $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('K' . $max_bariss, 'Jml Jam');
            $sheet->setCellValue('L' . $max_bariss, 'Jml Upah');
            $sheet->mergeCells('M' . $max_baris . ':M' . $max_bariss);
            $sheet->getStyle('M' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('M' . $max_baris, 'Premi Hadir');
            $sheet->mergeCells('N' . $max_baris . ':N' . $max_bariss);
            $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('N' . $max_baris, 'Transport');
            $sheet->mergeCells('O' . $max_baris . ':O' . $max_bariss);
            $sheet->setCellValue('O' . $max_baris, 'Makan');
            $sheet->mergeCells('P' . $max_baris . ':P' . $max_bariss);
            $sheet->setCellValue('P' . $max_baris, 'Asuransi');

            $sheet->mergeCells('Q' . $max_baris . ':U' . $max_baris);
            $sheet->setCellValue('Q' . $max_baris, 'Potongan');
            $sheet->setCellValue('Q' . $max_bariss, 'Pensiun');
            $sheet->setCellValue('R' . $max_bariss, 'Astek');
            $sheet->setCellValue('S' . $max_bariss, 'SPMI');
            $sheet->setCellValue('T' . $max_bariss, 'Mangkir');
            $sheet->setCellValue('U' . $max_bariss, 'PPH21');

            $sheet->mergeCells('V' . $max_baris . ':Y' . $max_baris);
            $sheet->setCellValue('V' . $max_baris, 'Bersih');
            $sheet->setCellValue('V' . $max_bariss, 'Net + Asr');
            $sheet->setCellValue('W' . $max_bariss, 'Retur PPH21');
            $sheet->setCellValue('X' . $max_bariss, 'Net');
            $sheet->setCellValue('Y' . $max_bariss, 'Pot. Kopkar');
            $sheet->setCellValue('Z' . $max_bariss, 'Jml Terima');
            // $sheet->setCellValue('X' . $max_bariss, 'Pot. Kopkar');
            // $sheet->setCellValue('Y' . $max_bariss, 'Jml Terima');
            /* --------- END HEADER TABLE  --------- */
            $max_baris = $max_baris + 1;
            /* --------- START LOAD DATA TO TABLE  --------- */
            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                $no = 0;
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':W' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, $paygroup[$pg2]);
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                    $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["global"]);
                    $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift1"]);
                    $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift2"]);
                    $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotshift"]);
                    $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotushift"]);
                    $sheet->setCellValue('H' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr1"]);
                    $sheet->setCellValue('I' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr2"]);
                    $sheet->setCellValue('J' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr3"]);
                    $sheet->setCellValue('K' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotlbr"]);
                    $sheet->setCellValue('L' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotulbr"]);
                    $sheet->setCellValue('M' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpremi"]);
                    $sheet->setCellValue('N' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btrans"]);
                    $sheet->setCellValue('O' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmak"]);
                    $sheet->setCellValue('P' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["basr"]);
                    $sheet->setCellValue('Q' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpen"]);
                    $sheet->setCellValue('R' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bast"]);
                    $sheet->setCellValue('S' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bspmi"]);
                    $sheet->setCellValue('T' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmang"]);
                    $sheet->setCellValue('U' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpph"]);
                    $sheet->setCellValue('V' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bnetasr"]);
                    $sheet->setCellValue('W' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpph"]);
                    $sheet->setCellValue('X' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    $sheet->setCellValue('Y' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpotkop"]);
                    $sheet->setCellValue('Z' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    // $sheet->setCellValue('X' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpotkop"]);
                    // $sheet->setCellValue('Y' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                }
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, "TOTAL " . $paygroup[$pg2]);
                $sheet->setCellValue('C' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pglobal"]);
                $sheet->setCellValue('D' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift1"]);
                $sheet->setCellValue('E' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift2"]);
                $sheet->setCellValue('F' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotshift"]);
                $sheet->setCellValue('G' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotushift"]);
                $sheet->setCellValue('H' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr1"]);
                $sheet->setCellValue('I' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr2"]);
                $sheet->setCellValue('J' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr3"]);
                $sheet->setCellValue('K' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotlbr"]);
                $sheet->setCellValue('L' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotulbr"]);
                $sheet->setCellValue('M' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppremi"]);
                $sheet->setCellValue('N' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptrans"]);
                $sheet->setCellValue('O' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmak"]);
                $sheet->setCellValue('P' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pasr"]);
                $sheet->setCellValue('Q' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppen"]);
                $sheet->setCellValue('R' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["past"]);
                $sheet->setCellValue('S' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pspmi"]);
                $sheet->setCellValue('T' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmang"]);
                $sheet->setCellValue('U' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppph"]);
                $sheet->setCellValue('V' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnetasr"]);
                $sheet->setCellValue('W' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppph"]);
                $sheet->setCellValue('X' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnet"]);
                $sheet->setCellValue('Y' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppotkop"]);
                $sheet->setCellValue('Z' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pterima"]);
                // $sheet->setCellValue('X' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppotkop"]);
                // $sheet->setCellValue('Y' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pterima"]);
            }
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, "GRAND TOTAL");
            $sheet->setCellValue('C' . $max_baris, $tot_global);
            $sheet->setCellValue('D' . $max_baris, $tot_shift1);
            $sheet->setCellValue('E' . $max_baris, $tot_shift2);
            $sheet->setCellValue('F' . $max_baris, $tot_totshift);
            $sheet->setCellValue('G' . $max_baris, $tot_ushift);
            $sheet->setCellValue('H' . $max_baris, $tot_lbr1);
            $sheet->setCellValue('I' . $max_baris, $tot_lbr2);
            $sheet->setCellValue('J' . $max_baris, $tot_lbr3);
            $sheet->setCellValue('K' . $max_baris, $tot_totlbr);
            $sheet->setCellValue('L' . $max_baris, $tot_ulbr);
            $sheet->setCellValue('M' . $max_baris, $tot_premi);
            $sheet->setCellValue('N' . $max_baris, $tot_trans);
            $sheet->setCellValue('O' . $max_baris, $tot_mak);
            $sheet->setCellValue('P' . $max_baris, $tot_asr);
            $sheet->setCellValue('Q' . $max_baris, $tot_pensiun);
            $sheet->setCellValue('R' . $max_baris, $tot_astek);
            $sheet->setCellValue('S' . $max_baris, $tot_spmi);
            $sheet->setCellValue('T' . $max_baris, $tot_mangkir);
            $sheet->setCellValue('U' . $max_baris, $tot_cpph);
            $sheet->setCellValue('V' . $max_baris, $tot_netasr);
            $sheet->setCellValue('W' . $max_baris, $tot_cpph);
            $sheet->setCellValue('X' . $max_baris, $tot_net);
            $sheet->setCellValue('Y' . $max_baris, $tot_potkop);
            $sheet->setCellValue('Z' . $max_baris, round($tot_terima));
            // $sheet->setCellValue('X' . $max_baris, $tot_potkop);
            // $sheet->setCellValue('Y' . $max_baris, round($tot_terima));
            /* --------- END LOAD DATA TO TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":Z" . $akhir_baris2) /* + RETUR */
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL)  --------- */

            /* --------- START FOR SHEET REKAPITULASI GAJI 2 --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji2');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji2");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran Gaji Karyawan Bulanan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Untuk Bulan : ' . $nama_bulan . ' ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Perhitungan Lembur Mulai Tgl ' . $tgl_mulai . ' s/d ' . $tgl_akhir);
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->mergeCells('C' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_baris, 'Kelompok Gaji');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_bariss, 'Direct');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('D' . $max_bariss, 'Indirect');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('E' . $max_bariss, 'Admin');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('F' . $max_bariss, 'Penjualan');
            $sheet->getStyle('F')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('G' . $max_baris . ':G' . $max_bariss);
            $sheet->getStyle('G')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('G' . $max_baris, 'Total');
            /* --------- END HEADER TABLE  --------- */

            /* --------- START LOAD DATA TO TABLE  --------- */
            $no = 0;
            // foreach ($paygroup->result() as $pg2) {

            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                // print_r(${"rekap_" . $pg2->pay_group});
                // echo ${"rekap_" . $pg2->pay_group}[0]["bagian"];
                $max_baris = $sheet->getHighestRow();
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    // echo $paygroup[$pg2]."<br>";
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    if ($paygroup[$pg2] == "Direct") {
                        // echo ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"];
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else if ($paygroup[$pg2] == "Indirect") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else if ($paygroup[$pg2] == "Admin") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    }
                }
            }
            /* --------- END LOAD DATA TO TABLE  --------- */

            /* --------- START TOTAL TABLE  --------- */
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Total Pengupahan');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan Koprasi');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_potkop));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Retur PPH21');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_cpph));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Jumlah Transfer');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Dana S.P.M.I');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_spmi));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan');
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Astek');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_astek));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pensiun');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_pensiun));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pph 21');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_cpph));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Mangkir');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_mangkir));

            /* --------- END TOTAL TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":G" . $akhir_baris2)
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI2  --------- */
            $writer = new Xlsx($spreadsheet);
            $filename = 'Gaji Karyawan Bulan ' . $nama_bulan . " " . $tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    function print_struk()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            /* ----- START SETTING PAPER -------------------- */
            $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
            $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.75);
            $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.75);
            /*  ------------ END SETTING PAPER ---------------- */
            $sheet->getDefaultRowDimension()->setRowHeight(8.25);


            $batas_kolom = array(
                array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'),
                array('L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'),
                array('W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'),
                array('AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR'),
                array('AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC'),
                array('BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN'),
            );

            $fpaygroup = array();
            $paygroup = $this->input->post('divisi');
            $department = $this->input->post('departement');
            $bagian = $this->input->post('bagian10');
            $karyawan = $this->input->post('karyawan');

            if (!empty($paygroup)) {
                for ($i = 0; $i < count($paygroup); $i++) {
                    array_push($fpaygroup, $paygroup[$i]);
                }
            } else {
                $paygroup = array();
                $pg = $this->m_hris->paygroup_emp();
                foreach ($pg->result() as $dv) {
                    array_push($fpaygroup, $dv->pay_group);
                    array_push($paygroup, $dv->pay_group);
                }
            }
            // print_r($fpaygroup);

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
            // echo $fdepartment;


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


            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $fkaryawan = "";
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
                $fkaryawan = "-";
            }

            // if (!empty($karyawan)) {
            //     $fkaryawan = "";
            //     // $text = "$text and b.recid_bag = '$bagian'";
            //     $cnt = count($karyawan);
            //     $fkaryawan .= " and (";
            //     if ($cnt == 1) {
            //         $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
            //     } else {
            //         for ($i = 0; $i < $cnt; $i++) {
            //             if ($i == $cnt - 1) {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
            //             } else {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
            //             }
            //         }
            //     }
            //     $fkaryawan .= ")";
            // } else {
            //     $fkaryawan = $fkaryawan;
            // }
            // echo $fkaryawan."<br>";

            $idkaryawan = array();
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                if ($fdepartment != '') {
                    // echo "ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    // echo "tidak ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        // echo "ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        // echo "tidak ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }
                    foreach ($bag->result() as $b) {
                        if ($fkaryawan != '-') {
                            // echo "ada filter karyawan <br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            // echo "tidak ada filter karyawan <br>";
                            // echo $paygroup[$pyg]."-".$dp->nama_department."-".$b->indeks_hr."<br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and b.indeks_hr = '$b->indeks_hr'");
                            // $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        foreach ($karyawan->result() as $lk) {
                            array_push($idkaryawan, $lk->recid_karyawan);
                        } // looping karyawan
                    } // looping bagian
                } // looping department
            } // looping pay group

            //$karyawan = $this->m_lembur->cek_print();
            $banyak = count($idkaryawan);
            $jejer = $banyak / 6;
            $nik = array();
            $nama_karyawan = array();
            $bagian = array();
            $gapok = array();
            $tjabatan = array();
            $tjenpek = array();
            $tmasker = array();
            $tjml = array();

            $lbr1 = array();
            $rp_lbr1 = array();
            $lbr2 = array();
            $rp_lbr2 = array();
            $lbr3 = array();
            $rp_lbr3 = array();
            $ulbr1 = array();
            $ulbr2 = array();
            $ulbr3 = array();
            $jlbr = array();

            $slbr1 = array();
            $slbr2 = array();
            $uslbr1 = array();
            $uslbr2 = array();

            $premi = array();
            $transport = array();
            $makan = array();
            $asuransi = array();

            $bruto = array();

            $astek = array();
            $spmi = array();
            $pensiun = array();
            $pph = array();
            $mangkir = array();
            $jpotongan = array();

            $netasr = array();
            $net = array();
            $pot_kopkar = array();
            $jml_terima = array();
            $bulanke = 0;
            $tahunke = 0;

            // echo count($idkaryawan);

            for ($idk = 0; $idk < count($idkaryawan); $idk++) {
                $karyawan = $this->m_lembur->print_struk($idkaryawan[$idk]);
                foreach ($karyawan->result() as $k) {
                    $bulan = $k->bulan;
                    $bulanke = $k->bulan;
                    $tahun = $k->tahun;
                    $tahunke = $k->tahun;
                    $awal =  date("d-m-Y", strtotime($k->periode_awal));
                    $akhir =  date("d-m-Y", strtotime($k->periode_akhir));
                    $periode = $awal . " s/d " . $akhir;
                    $t_masker = $k->rp_masker;
                    $t_jml = $k->tjbtn + $k->tjenpek + $t_masker;
                    $rp_slbr1 = $k->rp_slbr1;
                    $rp_slbr2 = $k->rp_slbr2;
                    $u_lbr1 = $k->lbr1 * $k->rp_lbr1;
                    $u_lbr2 = $k->lbr2 * $k->rp_lbr2;
                    $u_lbr3 = $k->lbr3 * $k->rp_lbr3;
                    $j_lbr = $u_lbr1 + $u_lbr2 + $u_lbr3;
                    $u_slbr1 = $k->slbr1 * $rp_slbr1;
                    $u_slbr2 = $k->slbr2 * $rp_slbr2;
                    $totpph = $k->pph21_1 + $k->pph21_2 + $k->pph21_3;
                    $j_potong = $k->astek + $k->spmi + $k->pensiun + $totpph + $k->mangkir;
                    array_push($nik, $k->nik);
                    array_push($nama_karyawan, $k->nama_karyawan);
                    array_push($bagian, $k->indeks_hr);
                    array_push($gapok, $k->upokok);
                    array_push($tjabatan, $k->tjbtn);
                    array_push($tjenpek, $k->tjenpek);
                    array_push($tmasker, $t_masker);
                    array_push($tjml, $t_jml);
                    array_push($lbr1, $k->lbr1);
                    array_push($rp_lbr1, $k->rp_lbr1);
                    array_push($lbr2, $k->lbr2);
                    array_push($rp_lbr2, $k->rp_lbr2);
                    array_push($lbr3, $k->lbr3);
                    array_push($rp_lbr3, $k->rp_lbr3);
                    array_push($ulbr1,  $u_lbr1);
                    array_push($ulbr2,  $u_lbr2);
                    array_push($ulbr3,  $u_lbr3);
                    array_push($jlbr,  $j_lbr);
                    array_push($slbr1, $k->slbr1);
                    array_push($uslbr1, $u_slbr1);
                    array_push($slbr2, $k->slbr2);
                    array_push($uslbr2, $u_slbr2);
                    array_push($premi, $k->premi);
                    array_push($transport, $k->uang_transport);
                    array_push($makan, $k->uang_makan);
                    array_push($asuransi, $k->asuransi);
                    array_push($bruto, $k->bruto);
                    array_push($astek, $k->astek);
                    array_push($spmi, $k->spmi);
                    array_push($pensiun, $k->pensiun);
                    array_push($pph, $totpph);
                    array_push($mangkir, $k->mangkir);
                    array_push($jpotongan, $j_potong);
                    array_push($netasr, $k->netasr);
                    array_push($net, $k->netto);
                    array_push($pot_kopkar, $k->pot_kopkar);
                    array_push($jml_terima, $k->jml_terima);
                } // loop data karyawan
            } // loop for list_karyawan

            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulanke - 1];
            $untuk = $nama_bulan . " - " . $tahun;

            $cnt = 0;
            $indeknya = 0;
            $bts_awal = 1;
            $bts_halaman = 0; //tiap 2 jejer + pagebreak

            for ($g = 0; $g < $jejer; $g++) {
                $max_baris = $bts_awal;
                $batas_baris = $bts_awal + 45;
                $bts_halaman = $bts_halaman + 1;
                if ($bts_halaman > 2) {
                    $sheet->setBreak('A' . $max_baris - 2, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                    $bts_halaman = 0;
                }
                // echo "group ".$g."<br>";
                if ($banyak >= 6) {
                    // echo "bayaknya :".$banyak."<br>";
                    //looping 6x
                    $bts_indek = $indeknya + 6;
                    for ($i = 0; $i < 6; $i++) {
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP GAJI KARYAWAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya +1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BULAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $untuk);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $periode);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'C.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'LEMBUR');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr1[$indeknya] . ' JAM x ' . $rp_lbr1[$indeknya]);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr2[$indeknya] . ' JAM x ' . $rp_lbr2[$indeknya] . " = ");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr2[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '3.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris,  $lbr3[$indeknya] . ' JAM x ' . $rp_lbr3[$indeknya] . " = ");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr3[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jlbr[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'D.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI SHIFT');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr1[$indeknya] . ' JAM x ' . $rp_slbr1);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($uslbr1[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr2[$indeknya] . ' JAM x ' . $rp_slbr2);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($uslbr2[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH PREMI SHIFT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'E.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI HADIR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($premi[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'F.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TRANSPORT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($transport[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'G.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'UANG MAKAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($makan[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'H.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($asuransi[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($bruto[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'I.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASTEK');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($astek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'SPMI');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($spmi[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PENSIUN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pensiun[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'MANGKIR');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($mangkir[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jpotongan[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NET + ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($netasr[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NETTO');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($net[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'KASIR');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, 'PENERIMA');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POT. KOPKAR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pot_kopkar[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JML TERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                    $banyak = $banyak - 6;
                } else {
                    //looping sisanya
                    //    echo "bayaknya :".$banyak."<br>";
                    for ($i = 0; $i < $banyak; $i++) {
                        // echo "- ".$indeknya."<br>";
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }


                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP GAJI KARYAWAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya] . " - " . ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BULAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $untuk);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $periode);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'C.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'LEMBUR');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr1[$indeknya] . ' JAM x ' . $rp_lbr1[$indeknya]);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr2[$indeknya] . ' JAM x ' . $rp_lbr2[$indeknya]);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr2[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '3.');
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris,  $lbr3[$indeknya] . ' JAM x ' . $rp_lbr3[$indeknya]);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr3[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jlbr[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'D.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI SHIFT');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr1[$indeknya] . ' JAM x ' . $rp_slbr1);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, number_format($uslbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr2[$indeknya] . ' JAM x ' . $rp_slbr2);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, number_format($uslbr2[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH PREMI SHIFT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'E.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI HADIR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($premi[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'F.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TRANSPORT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($transport[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'G.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'UANG MAKAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($makan[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'H.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($asuransi[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($bruto[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'I.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASTEK');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($astek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'SPMI');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($spmi[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PENSIUN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pensiun[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'MANGKIR');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($mangkir[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jpotongan[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NET ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($netasr[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'RETUR PPH21');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pph[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NETTO');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($net[$indeknya] + $pph[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'KASIR');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, 'PENERIMA');

                        $max_baris = $max_baris + 1;
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POT. KOPKAR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pot_kopkar[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JML TERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }
                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                }

                $bts_awal = $batas_baris + 2;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'Struk Gaji Karyawan' . $untuk;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } else {
            redirect('Auth/keluar');
        }
    }

    function print_struk_transisi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            /* ----- START SETTING PAPER -------------------- */
            $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
            $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.75);
            $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.75);
            /*  ------------ END SETTING PAPER ---------------- */
            $sheet->getDefaultRowDimension()->setRowHeight(8.25);


            $batas_kolom = array(
                array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'),
                array('L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'),
                array('W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'),
                array('AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR'),
                array('AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC'),
                array('BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN'),
            );

            $fpaygroup = array();
            $paygroup = $this->input->post('divisi');
            $department = $this->input->post('departement');
            $bagian = $this->input->post('bagian10');
            $karyawan = $this->input->post('karyawan');

            if (!empty($paygroup)) {
                for ($i = 0; $i < count($paygroup); $i++) {
                    array_push($fpaygroup, $paygroup[$i]);
                }
            } else {
                $paygroup = array();
                $pg = $this->m_hris->paygroup_emp();
                foreach ($pg->result() as $dv) {
                    array_push($fpaygroup, $dv->pay_group);
                    array_push($paygroup, $dv->pay_group);
                }
            }
            // print_r($fpaygroup);

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
            // echo $fdepartment;


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


            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $fkaryawan = "";
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
                $fkaryawan = "-";
            }

            // if (!empty($karyawan)) {
            //     $fkaryawan = "";
            //     // $text = "$text and b.recid_bag = '$bagian'";
            //     $cnt = count($karyawan);
            //     $fkaryawan .= " and (";
            //     if ($cnt == 1) {
            //         $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
            //     } else {
            //         for ($i = 0; $i < $cnt; $i++) {
            //             if ($i == $cnt - 1) {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
            //             } else {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
            //             }
            //         }
            //     }
            //     $fkaryawan .= ")";
            // } else {
            //     $fkaryawan = $fkaryawan;
            // }
            // echo $fkaryawan."<br>";

            $idkaryawan = array();
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                if ($fdepartment != '') {
                    // echo "ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    // echo "tidak ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        // echo "ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        // echo "tidak ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }
                    foreach ($bag->result() as $b) {
                        if ($fkaryawan != '-') {
                            // echo "ada filter karyawan <br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            // echo "tidak ada filter karyawan <br>";
                            // echo $paygroup[$pyg]."-".$dp->nama_department."-".$b->indeks_hr."<br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and b.indeks_hr = '$b->indeks_hr'");
                            // $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        foreach ($karyawan->result() as $lk) {
                            array_push($idkaryawan, $lk->recid_karyawan);
                        } // looping karyawan
                    } // looping bagian
                } // looping department
            } // looping pay group

            //$karyawan = $this->m_lembur->cek_print();
            $banyak = count($idkaryawan);
            $jejer = $banyak / 6;
            $nik = array();
            $nama_karyawan = array();
            $bagian = array();
            $gapok = array();
            $tjabatan = array();
            $tjenpek = array();
            $tmasker = array();
            $tjml = array();
            $uglobal = array();

            $lbr1 = array();
            $rp_lbr1 = array();
            $lbr2 = array();
            $rp_lbr2 = array();
            $lbr3 = array();
            $rp_lbr3 = array();
            $ulbr1 = array();
            $ulbr2 = array();
            $ulbr3 = array();
            $jlbr = array();

            $slbr1 = array();
            $slbr2 = array();
            $uslbr1 = array();
            $uslbr2 = array();

            $premi = array();
            $transport = array();
            $makan = array();
            $asuransi = array();

            $bruto = array();

            $astek = array();
            $spmi = array();
            $pensiun = array();
            $pph = array();
            $mangkir = array();
            $jpotongan = array();

            $netasr = array();
            $net = array();
            $pot_kopkar = array();
            $jml_terima = array();
            $bulanke = 0;
            $tahunke = 0;

            // echo count($idkaryawan);

            for ($idk = 0; $idk < count($idkaryawan); $idk++) {
                $karyawan = $this->m_lembur->print_struk($idkaryawan[$idk]);
                foreach ($karyawan->result() as $k) {
                    $recid_karyawan = $k->recid_karyawan;
                    $bulan = $k->bulan;
                    $bulanke = $k->bulan;
                    $tahun = $k->tahun;
                    $tahunke = $k->tahun;
                    $awal =  date("d-m-Y", strtotime($k->periode_awal));
                    $akhir =  date("d-m-Y", strtotime($k->periode_akhir));
                    $periode = $awal . " s/d " . $akhir;
                    $t_masker = $k->rp_masker;
                    $t_jml = $k->tjbtn + $k->tjenpek + $t_masker;
                    $rp_slbr1 = $k->rp_slbr1;
                    $rp_slbr2 = $k->rp_slbr2;
                    $u_lbr1 = $k->lbr1 * $k->rp_lbr1;
                    $u_lbr2 = $k->lbr2 * $k->rp_lbr2;
                    $u_lbr3 = $k->lbr3 * $k->rp_lbr3;
                    $j_lbr = $u_lbr1 + $u_lbr2 + $u_lbr3;
                    $u_slbr1 = $k->slbr1 * $rp_slbr1;
                    $u_slbr2 = $k->slbr2 * $rp_slbr2;
                    $totpph = $k->pph21_1 + $k->pph21_2 + $k->pph21_3;
                    $j_potong = $k->astek + $k->spmi + $k->pensiun + $totpph + $k->mangkir;

                    /* kalkulasi periode desember */
                    $tgl_mulai = $k->periode_awal;
                    $tahun_lama = date("Y", strtotime($tgl_mulai));
                    $tgl_mulai_lama = $tgl_mulai;
                    $tgl_akhir_lama = date("Y-m-t", strtotime($tgl_mulai_lama));

                    /* kalkulasi periode januari */
                    $tgl_akhir = $k->periode_akhir;
                    $tahun_baru = date("Y", strtotime($tgl_akhir));
                    $tgl_mulai_baru = date("Y-m-01", strtotime($tgl_akhir));
                    $tgl_akhir_baru = $tgl_akhir;

                    /* -------- base masker = 1 ------------------*/
                    //2023
                    $rp_masker = $this->m_hris->param_upah_tmp_id(1);
                    foreach ($rp_masker->result() as $r) {
                        $uph_masker1 = $r->nilai;
                    }
                    //2024
                    $rp_masker = $this->m_hris->param_upah_id(1);
                    foreach ($rp_masker->result() as $r) {
                        $uph_masker2 = $r->nilai;
                    }
                    /* --------  shift 1-2 ------------------*/
                    //2024
                    //shift1-2 = 8
                    $cslbr1 = $this->m_hris->param_upah_id(8);
                    foreach ($cslbr1->result() as $slbr1a) {
                        $shift_lbr1_baru = $slbr1a->nilai;
                    }

                    //shift3 = 9
                    $cslbr2 = $this->m_hris->param_upah_id(9);
                    foreach ($cslbr2->result() as $slbr2a) {
                        $shift_lbr2_baru = $slbr2a->nilai;
                    }

                    //2023
                    //shift1-2 = 8
                    $cslbr1 = $this->m_hris->param_upah_tmp_id(8);
                    foreach ($cslbr1->result() as $slbr1a) {
                        $shift_lbr1_lama = $slbr1a->nilai;
                    }

                    //shift3 = 9
                    $cslbr2 = $this->m_hris->param_upah_tmp_id(9);
                    foreach ($cslbr2->result() as $slbr2a) {
                        $shift_lbr2_lama = $slbr2a->nilai;
                    }

                    /* -------- pengali lembur 1 ------------------*/
                    //2023
                    $klbr1 = $this->m_hris->param_upah_tmp_id(11);
                    foreach ($klbr1->result() as $klbr1a) {
                        $kali_lembur1_lama = $klbr1a->nilai;
                    }

                    //pengali lembur 2
                    $klbr2 = $this->m_hris->param_upah_tmp_id(12);
                    foreach ($klbr2->result() as $klbr2a) {
                        $kali_lembur2_lama = $klbr2a->nilai;
                    }

                    //pengali lembur 3
                    $klbr3 = $this->m_hris->param_upah_tmp_id(13);
                    foreach ($klbr3->result() as $klbr3a) {
                        $kali_lembur3_lama = $klbr3a->nilai;
                    }

                    //2024
                    $klbr1 = $this->m_hris->param_upah_id(11);
                    foreach ($klbr1->result() as $klbr1a) {
                        $kali_lembur1_baru = $klbr1a->nilai;
                    }

                    //pengali lembur 2
                    $klbr2 = $this->m_hris->param_upah_id(12);
                    foreach ($klbr2->result() as $klbr2a) {
                        $kali_lembur2_baru = $klbr2a->nilai;
                    }

                    //pengali lembur 3
                    $klbr3 = $this->m_hris->param_upah_id(13);
                    foreach ($klbr3->result() as $klbr3a) {
                        $kali_lembur3_baru = $klbr3a->nilai;
                    }
                    /* --------  pembagi lembur = 10 ------------------*/
                    //2023
                    $plbr = $this->m_hris->param_upah_tmp_id(10);
                    foreach ($plbr->result() as $r) {
                        $bagi_lembur_lama = $r->nilai;
                    }

                    //2024
                    $plbr = $this->m_hris->param_upah_id(10);
                    foreach ($plbr->result() as $r) {
                        $bagi_lembur_baru = $r->nilai;
                    }

                    $tingkatan = $k->tingkatan;
                    $t_prestasi = $k->t_prestasi;
                    $t_jenpek = $k->t_jen_pek;
                    $penempatan = $k->penempatan;
                    $sts_jabatan = $k->sts_jabatan;
                    // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                    $trakhir = date("Y-m-t", strtotime($tgl_akhir));
                    $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
                    $masker_tahun = $diff->format('%y');
                    $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;

                    if ($tingkatan == 1) {
                        if ($penempatan == 'Jakarta') {
                            if ($sts_jabatan == "Advisor") {
                                //2023
                                $gapokl = 0;
                                $gapok_lama = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_lama);
                                foreach ($gapok_lama->result() as $gp1) {
                                    $gapokb = round((80 / 100) * $gp1->nilai);
                                }
                            } else {
                                //2023
                                $gapok_lama = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_lama);
                                foreach ($gapok_lama->result() as $gp1) {
                                    if ($sts_jabatan == "Advisor") {
                                        $gapokl = round((80 / 100) * $gp1->nilai);
                                    } else {
                                        $gapokl = $gp1->nilai;
                                    }
                                }
                                //2024
                                $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_baru);
                                foreach ($gapok_baru->result() as $gp2) {
                                    if ($sts_jabatan == "Advisor") {
                                        $gapokb = round((80 / 100) * $gp2->nilai);
                                    } else {
                                        $gapokb = $gp2->nilai;
                                    }
                                }
                            }
                        } else {
                            if ($sts_jabatan == "Advisor") {
                                //2023
                                $gapokl = 0;
                                $gapok_lama = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_lama);
                                foreach ($gapok_lama->result() as $gp1) {
                                    $gapokb = round((80 / 100) * $gp1->nilai);
                                }
                            } else {
                                //2023
                                $gapok_lama = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_lama);
                                foreach ($gapok_lama->result() as $gp1) {
                                    $gapokl = $gp1->nilai;
                                }
                                //2024
                                $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_baru);
                                foreach ($gapok_baru->result() as $gp2) {
                                    $gapokb = $gp2->nilai;
                                }
                            }
                        }
                    } else {
                        if ($sts_jabatan == "Advisor") {
                            $gapokl = 0;
                            $gapok_lama = $this->m_hris->gapok($tingkatan, $tahun_lama);
                            foreach ($gapok_lama->result() as $gp1) {
                                $gapokb = round((80 / 100) * $gp1->nilai);
                            }
                        } else {
                            //2023
                            $gapok_lama = $this->m_hris->gapok($tingkatan, $tahun_lama);
                            foreach ($gapok_lama->result() as $gp1) {
                                if ($sts_jabatan == "Advisor") {
                                    $gapokl = round((80 / 100) * $gp1->nilai);
                                } else {
                                    $gapokl = $gp1->nilai;
                                }
                            }
                            //2024
                            $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun_baru);
                            foreach ($gapok_baru->result() as $gp2) {
                                if ($sts_jabatan == "Advisor") {
                                    $gapokb = round((80 / 100) * $gp2->nilai);
                                } else {
                                    $gapokb = $gp2->nilai;
                                }
                            }
                        }
                    }

                    if ($k->tingkatan == 1) {
                        //operator tunjangan jabatan = 0
                        $t_jbtn1 = 0;
                        $t_jbtn2 = 0;
                        // cek tunjangan jenis pekerjaan by bagian
                        $tjenpek1 = $this->m_hris->tjenpek($k->recid_bag, $tahun_lama);
                        if ($tjenpek1->num_rows() > 0) {
                            foreach ($tjenpek1->result() as $t1) {
                                $t_jenpek1 = $t1->nilai;
                            }
                        } else {
                            $t_jenpek1 = 0;
                        }


                        $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun_baru);
                        if ($tjenpek2->num_rows() > 0) {
                            foreach ($tjenpek2->result() as $t2) {
                                $t_jenpek2 = $t2->nilai;
                            }
                        } else {
                            $t_jenpek2 = 0;
                        }

                        // echo "t_jbtn : $t_jbtn<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";

                    } else {
                        //wakaru ke atas, t_jenpek = 0
                        $t_jenpek1 = 0;
                        $t_jenpek2 = 0;
                        if ($sts_jabatan == "Advisor") {
                            $tjbtn1 = $this->m_hris->tjabatan($tingkatan, $tahun_lama);
                            foreach ($tjbtn1->result() as $t1) {
                                $t_jbtn2 = round((80 / 100) * $t1->nilai);
                            }
                            $t_jbtn1 = 0;
                        } else {
                            //cek tunjangan jabatannya
                            //2023
                            $tjbtn1 = $this->m_hris->tjabatan($tingkatan, $tahun_lama);
                            foreach ($tjbtn1->result() as $t1) {
                                if ($sts_jabatan == "Advisor") {
                                    $t_jbtn1 = round((80 / 100) * $t1->nilai);
                                } else {
                                    $t_jbtn1 = $t1->nilai;
                                }
                            }
                            //2024
                            $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun_baru);
                            foreach ($tjbtn2->result() as $t2) {
                                if ($sts_jabatan == "Advisor") {
                                    $t_jbtn2 = round((80 / 100) * $t2->nilai);
                                } else {
                                    $t_jbtn2 = $t2->nilai;
                                }
                            }
                        }
                    }

                    $t_masker1 = $masker_tahun * $uph_masker1; //2023
                    $t_masker2 = $masker_tahun * $uph_masker2; //2024

                    // if ($sts_jabatan == "Advisor") {
                    //     $global_lama = ((80 / 100) * ($gapokl + $t_jbtn1)) + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                    //     $global_baru = ((80 / 100) * ($gapokb + $t_jbtn2)) + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
                    //     // $global = ((80 / 100) * ($uph_pokok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
                    // } else {
                    //     $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                    //     $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
                    // }

                    $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                    $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024


                    /* START LEMBURAN */
                    /* SHIFT */
                    //2023
                    $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 14);
                    $s1_lama = $shift1->num_rows();
                    $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 15);
                    $s2_lama = $shift2->num_rows();
                    $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 16);
                    $s3_lama = $shift3->num_rows();

                    $slembur1_lama = $s1_lama + $s2_lama;
                    $uslembur1_lama = $slembur1_lama * $shift_lbr1_lama;
                    $slembur2_lama = $s3_lama;
                    $uslembur2_lama = $slembur2_lama * $shift_lbr2_lama;
                    $utot_shift_lama = $uslembur1_lama + $uslembur2_lama;

                    //2024
                    $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 14);
                    $s1_baru = $shift1->num_rows();
                    $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 15);
                    $s2_baru = $shift2->num_rows();
                    $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 16);
                    $s3_baru = $shift3->num_rows();

                    $slembur1_baru = $s1_baru + $s2_baru;
                    $uslembur1_baru = $slembur1_baru * $shift_lbr1_baru;
                    $slembur2_baru = $s3_baru;
                    $uslembur2_baru = $slembur2_baru * $shift_lbr2_baru;
                    $utot_shift_baru = $uslembur1_baru + $uslembur2_baru;

                    $slembur1 = $slembur1_baru + $slembur1_lama;
                    $uslembur1 = $uslembur1_baru +  $uslembur1_lama;
                    $slembur2 = $slembur2_baru + $slembur2_lama;
                    $uslembur2 = $uslembur2_baru + $uslembur2_lama;
                    $utot_shift = $utot_shift_baru + $utot_shift_lama;

                    //2023
                    $lemburan_lama = $this->m_lembur->karyawan_lembur_report($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                    $tot_lbr_lama = 0;
                    foreach ($lemburan_lama->result() as $l) {
                        if ($l->lembur1 != "") {
                            $lembur1_lama = round($l->lembur1, 1);
                        } else {
                            $lembur1_lama = $l->lembur1;
                        }

                        if ($l->lembur2 != "") {
                            $lembur2_lama = round($l->lembur2, 1);
                        } else {
                            $lembur2_lama = $l->lembur2;
                        }

                        if ($l->lembur3 != "") {
                            $lembur3_lama = round($l->lembur3, 1);
                        } else {
                            $lembur3_lama = $l->lembur3;
                        }
                    }

                    //2024
                    $lemburan_baru = $this->m_lembur->karyawan_lembur_report($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                    $tot_lbr_baru = 0;
                    foreach ($lemburan_baru->result() as $l) {
                        if ($l->lembur1 != 0) {
                            $lembur1_baru = round($l->lembur1, 1);
                        } else {
                            $lembur1_baru = $l->lembur1;
                        }

                        if ($l->lembur2 != 0) {
                            $lembur2_baru = round($l->lembur2, 1);
                        } else {
                            $lembur2_baru = $l->lembur2;
                        }

                        if ($l->lembur3 != 0) {
                            $lembur3_baru = round($l->lembur3, 1);
                        } else {
                            $lembur3_baru = $l->lembur3;
                        }
                    }

                    // Cek adjustment lemburan 2023
                    $atrans_lama = 0;
                    $amakan_lama = 0;
                    $adj_upah_lama = $this->m_lembur->adjust_periode_karyawan($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                    if ($adj_upah_lama->num_rows() > 0) {
                        foreach ($adj_upah_lama->result() as $al) {
                            $lembur1_lama = $lembur1_lama + $al->jam_lbr1;
                            $lembur2_lama = $lembur2_lama + $al->jam_lbr2;
                            $lembur3_lama = $lembur3_lama + $al->jam_lbr3;
                            $atrans_lama = $al->jml_transport;
                            $amakan_lama = $al->jml_makan;
                        }
                    } else {
                        $atrans_lama = 0;
                        $amakan_lama = 0;
                    }

                    // Cek adjustment lemburan 2024
                    $atrans_baru = 0;
                    $amakan_baru = 0;
                    $adj_upah_baru = $this->m_lembur->adjust_periode_karyawan($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                    if ($adj_upah_baru->num_rows() > 0) {
                        foreach ($adj_upah_baru->result() as $al) {
                            $lembur1_baru = $lembur1_baru + $al->jam_lbr1;
                            $lembur2_baru = $lembur2_baru + $al->jam_lbr2;
                            $lembur3_baru = $lembur3_baru + $al->jam_lbr3;
                            $atrans_baru = $al->jml_transport;
                            $amakan_baru = $al->jml_makan;
                        }
                    } else {
                        $atrans_baru = 0;
                        $amakan_baru = 0;
                    }

                    $lembur1 = $lembur1_baru + $lembur1_lama;
                    $lembur2 = $lembur2_baru + $lembur2_lama;
                    $lembur3 = $lembur3_baru + $lembur3_lama;
                    $atrans = $atrans_baru + $atrans_lama;
                    $amakan = $amakan_baru + $amakan_lama;

                    //2023
                    $uph_lbr1_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur1_lama);
                    $lbr1_lama = $uph_lbr1_lama * $lembur1_lama;
                    $uph_lbr2_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur2_lama);
                    $lbr2_lama = $uph_lbr2_lama * $lembur2_lama;
                    $uph_lbr3_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur3_lama);
                    $lbr3_lama = $uph_lbr3_lama * $lembur3_lama;
                    $tot_lbr_lama = $lbr1_lama + $lbr2_lama + $lbr3_lama;


                    //2024
                    $uph_lbr1_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur1_baru);
                    $lbr1_baru = $uph_lbr1_baru * $lembur1_baru;
                    $uph_lbr2_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur2_baru);
                    $lbr2_baru = $uph_lbr2_baru * $lembur2_baru;
                    $uph_lbr3_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur3_baru);
                    $lbr3_baru = $uph_lbr3_baru * $lembur3_baru;
                    $tot_lbr_baru = $lbr1_baru + $lbr2_baru + $lbr3_baru;

                    $uph_lbr1 = $uph_lbr1_lama + $uph_lbr1_baru;
                    $jlbr1 = $lbr1_lama + $lbr1_baru;
                    $trp_lbr1 = ($uph_lbr1_lama * $lembur1_lama) + ($uph_lbr1_baru * $lembur1_baru);
                    $uph_lbr2 = $uph_lbr2_lama + $uph_lbr2_baru;
                    $jlbr2 =  $lbr2_lama +  $lbr2_baru;
                    $trp_lbr2 = ($uph_lbr2_lama * $lembur2_lama) + ($uph_lbr2_baru * $lembur2_baru);
                    $uph_lbr3 = $uph_lbr3_lama + $uph_lbr3_baru;
                    $jlbr3 = $lbr3_lama + $lbr3_baru;
                    $trp_lbr3 = ($uph_lbr3_lama * $lembur3_lama) + ($uph_lbr3_baru * $lembur3_baru);
                    $tot_lbr = $tot_lbr_lama + $tot_lbr_baru;
                    /* END LEMBURAN */

                    array_push($nik, $k->nik);
                    array_push($nama_karyawan, $k->nama_karyawan);
                    array_push($bagian, $k->indeks_hr);
                    array_push($gapok, $k->upokok);
                    array_push($tjabatan, $k->tjbtn);
                    array_push($tjenpek, $k->tjenpek);
                    array_push($tmasker, $t_masker);
                    array_push($tjml, $t_jml);
                    array_push($lbr1, $k->lbr1);
                    array_push($rp_lbr1, $k->rp_lbr1);
                    array_push($lbr2, $k->lbr2);
                    array_push($rp_lbr2, $k->rp_lbr2);
                    array_push($lbr3, $k->lbr3);
                    array_push($rp_lbr3, $k->rp_lbr3);
                    array_push($ulbr1,  $trp_lbr1);
                    array_push($ulbr2,  $trp_lbr2);
                    array_push($ulbr3,  $trp_lbr3);
                    array_push($jlbr,  $tot_lbr);
                    array_push($slbr1, $k->slbr1);
                    array_push($uslbr1, $u_slbr1);
                    array_push($slbr2, $k->slbr2);
                    array_push($uslbr2, $u_slbr2);
                    array_push($premi, $k->premi);
                    array_push($transport, $k->uang_transport);
                    array_push($makan, $k->uang_makan);
                    array_push($asuransi, $k->asuransi);
                    array_push($bruto, $k->bruto);
                    array_push($astek, $k->astek);
                    array_push($spmi, $k->spmi);
                    array_push($pensiun, $k->pensiun);
                    array_push($pph, $totpph);
                    array_push($mangkir, $k->mangkir);
                    array_push($jpotongan, $j_potong);
                    array_push($netasr, $k->netasr);
                    array_push($net, $k->netto);
                    array_push($pot_kopkar, $k->pot_kopkar);
                    array_push($jml_terima, $k->jml_terima);
                } // loop data karyawan
            } // loop for list_karyawan

            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulanke - 1];
            $untuk = $nama_bulan . " - " . $tahun;

            $cnt = 0;
            $indeknya = 0;
            $bts_awal = 1;
            $bts_halaman = 0; //tiap 2 jejer + pagebreak

            for ($g = 0; $g < $jejer; $g++) {
                $max_baris = $bts_awal;
                $batas_baris = $bts_awal + 45;
                $bts_halaman = $bts_halaman + 1;
                if ($bts_halaman > 2) {
                    $sheet->setBreak('A' . $max_baris - 2, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                    $bts_halaman = 0;
                }
                // echo "group ".$g."<br>";
                if ($banyak >= 6) {
                    // echo "bayaknya :".$banyak."<br>";
                    //looping 6x
                    $bts_indek = $indeknya + 6;
                    for ($i = 0; $i < 6; $i++) {
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP GAJI KARYAWAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya +1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BULAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $untuk);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $periode);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'C.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'LEMBUR');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr1[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr2[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr2[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '3.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris,  $lbr3[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr3[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jlbr[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'D.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI SHIFT');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr1[$indeknya] . ' JAM x ' . $rp_slbr1);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($uslbr1[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr2[$indeknya] . ' JAM x ' . $rp_slbr2);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($uslbr2[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH PREMI SHIFT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'E.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI HADIR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($premi[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'F.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TRANSPORT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($transport[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'G.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'UANG MAKAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($makan[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'H.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($asuransi[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($bruto[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'I.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASTEK');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($astek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'SPMI');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($spmi[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PENSIUN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pensiun[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'MANGKIR');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($mangkir[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jpotongan[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NET + ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($netasr[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NETTO');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($net[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'KASIR');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, 'PENERIMA');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POT. KOPKAR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pot_kopkar[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JML TERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                    $banyak = $banyak - 6;
                } else {
                    //looping sisanya
                    //    echo "bayaknya :".$banyak."<br>";
                    for ($i = 0; $i < $banyak; $i++) {
                        // echo "- ".$indeknya."<br>";
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }


                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP GAJI KARYAWAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya] . " - " . ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BULAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $untuk);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $periode);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'C.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'LEMBUR');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr1[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $lbr2[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr2[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '3.');
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris,  $lbr3[$indeknya] . ' JAM');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($ulbr3[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH LEMBUR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jlbr[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'D.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI SHIFT');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '1.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr1[$indeknya] . ' JAM x ' . $rp_slbr1);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, number_format($uslbr1[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, '2.');
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][4] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, $slbr2[$indeknya] . ' JAM x ' . $rp_slbr2);
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, "=");
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, number_format($uslbr2[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH PREMI SHIFT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'E.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PREMI HADIR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($premi[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'F.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TRANSPORT');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($transport[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'G.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'UANG MAKAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($makan[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'H.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($asuransi[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($bruto[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'I.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'ASTEK');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($astek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'SPMI');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($spmi[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PENSIUN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pensiun[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'MANGKIR');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($mangkir[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jpotongan[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NET ASURANSI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($netasr[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'NETTO');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($net[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'KASIR');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, 'PENERIMA');

                        $max_baris = $max_baris + 1;
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POT. KOPKAR');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pot_kopkar[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JML TERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }
                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                }

                $bts_awal = $batas_baris + 2;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'Struk Gaji Karyawan' . $untuk;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_struk()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_struk', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_struk_transisi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_struk_transisi', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function download_master()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $periode_upah = $this->m_lembur->periode_upah();
            if ($periode_upah->num_rows() > 0) {
                $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                foreach ($periode_upah->result() as $p) {
                    $bulan = $p->bulan;
                    $tahun = $p->tahun;
                    $tgl_mulai = $p->periode_awal;
                    $tgl_akhir = $p->periode_akhir;
                }
                $nama_bulan = $month[$bulan - 1];
                $data['bulan'] = $nama_bulan;
                $data['tahun'] = $tahun;
                $data['tgl_mulai'] = $tgl_mulai;
                $data['tgl_akhir'] = $tgl_akhir;
                $data['upah'] = $this->m_lembur->data_upah();
                $usr = $this->session->userdata('kar_id');
                $data['cek_usr'] = $this->m_hris->cek_usr($usr);
                $this->load->view('layout/a_header');
                $this->load->view('layout/menu_super', $data);
                $this->load->view('upah/report/master_upah2', $data);
                $this->load->view('layout/a_footer');
            } else {
                echo "Tidak Ada Data";
            }
        } else {
            redirect('Auth/keluar');
        }
    }

    public function upload_potkop()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $tahun = $this->m_lembur->tahun_potkop();
            $bulan = Date('m');
            $list_bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = $list_bulan[$bulan - 1];
            $data['bulans'] = $bulan;
            $data['tahun'] = $tahun;
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/upload_potkop', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function import_potkop()
    {

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
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            if (!empty($sheetData)) {
                for ($i = 3; $i < count($sheetData); $i++) {
                    $nik = $sheetData[$i][1];
                    $bulan = $sheetData[$i][3];
                    $tahun = $sheetData[$i][4];
                    $potongan = $sheetData[$i][5];
                    $cek = $this->m_hris->karyawan_by_nik($nik);
                    foreach ($cek as $k) {
                        $recid_karyawan = $k->recid_karyawan;
                    }
                    $cek_dbl = $this->m_lembur->potongan_kopkar($recid_karyawan, $tahun, $bulan);
                    if ($cek_dbl->num_rows() > 0) {
                        //update
                        foreach ($cek_dbl->result() as $a) {
                            $recid = $a->recid;
                        }
                        $data = array(
                            'potongan'      => $potongan,
                            'mdf_by'        => $this->session->userdata('kar_id'),
                            'mdf_date'      => date('Y-m-d H:i:s'),
                        );
                        $this->m_lembur->update_potkop($data, $recid);
                    } else {
                        //insert
                        $data = array(
                            'crt_by'            => $this->session->userdata('kar_id'),
                            'crt_date'          => date('Y-m-d H:i:s'),
                            'recid_karyawan'    => $recid_karyawan,
                            'bulan'             => $bulan,
                            'tahun'             => $tahun,
                            'potongan'          => $potongan,
                        );
                        $this->m_lembur->insert_potkop($data);
                    }
                }
            }
        }
        redirect('Upah/upload_potkop');
    }

    public function potkop_periode()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $query2 = $this->m_lembur->potkop_periode($tahun, $bulan);
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nama_bulan = $month[$bulan - 1];
        $data = [];
        $no = 0;
        foreach ($query2->result() as $r) {
            $data[] = array(
                $no = $no + 1,
                $r->nik,
                $r->nama_karyawan,
                $r->indeks_hr,
                $nama_bulan,
                $tahun,
                $r->potongan,
                "<td><center><a 
                    data-recid='" . $r->recid . "'
                    data-nik='" . $r->nik . "'
                    data-nama_karyawan='" . $r->nama_karyawan . "'
                    data-tahun='" . $r->tahun . "'
                    data-bulan='" . $r->bulan . "'
                    data-potongan='" . $r->potongan . "'
                    data-toggle='modal' data-target='#edit_potkop'><button type='button' class='btn btn-xs btn-info'><span class='fa fa-edit'></span></button></a></td>",
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

    function potkop_update()
    {
        $recid = $this->input->post('recid');
        $potongan = $this->input->post('potongan');
        // echo $potongan;
        $data = array(
            'potongan'      => $potongan,
            'mdf_by'        => $this->session->userdata('kar_id'),
            'mdf_date'      => date('Y-m-d H:i:s'),
        );
        $this->m_lembur->update_potkop($data, $recid);
        redirect("Upah/upload_potkop");
    }

    public function transisi_upah()
    {
        /* tipe 1 : simpan & download, tipe 2: download saja  */
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir');
        // $tgl_mulai = "2023-12-19";
        // $tgl_akhir = "2024-01-18";
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        // $tahun=2024;
        // $bulan = 1;

        /* kalkulasi periode desember */
        $tahun_lama = date("Y", strtotime($tgl_mulai));
        $tgl_mulai_lama = $tgl_mulai;
        $tgl_akhir_lama = date("Y-m-t", strtotime($tgl_mulai_lama));

        /* kalkulasi periode januari */
        $tahun_baru = date("Y", strtotime($tgl_akhir));
        $tgl_mulai_baru = date("Y-m-01", strtotime($tgl_akhir));
        $tgl_akhir_baru = $tgl_akhir;

        $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nama_bulan = $month[$bulan - 1];
        $tipe = $this->input->post('tipe');
        $fpaygroup = array();
        $paygroup = $this->input->post('divisi');
        $department = $this->input->post('departement');
        $bagian = $this->input->post('bagian10');
        $karyawan = $this->input->post('karyawan');

        if (!empty($paygroup)) {
            for ($i = 0; $i < count($paygroup); $i++) {
                array_push($fpaygroup, $paygroup[$i]);
            }
        } else {
            $paygroup = array();
            $pg = $this->m_hris->paygroup_emp();
            foreach ($pg->result() as $dv) {
                array_push($fpaygroup, $dv->pay_group);
                array_push($paygroup, $dv->pay_group);
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
        // echo $fdepartment;


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
        // $karyawan = 1187;
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

        if ($tipe == '1') {
            /* truncate all data table upah */
            $this->m_lembur->kosongkan_upah();
            $iduniq = uniqid();
        } else if ($tipe == '3') {
            $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $nama_bulan = $month[$bulan - 1];
            $data['paygroup'] = $fpaygroup;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['bulan'] = $nama_bulan;
            $data['bulanke'] = $bulan;
            $data['tahun'] = $tahun;
            $data['tgl_mulai'] = $tgl_mulai;
            $data['tgl_akhir'] = $tgl_akhir;
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/transisi_master_upah', $data);
            $this->load->view('layout/a_footer');
        } else {
        }

        if ($tipe != '3') {
            // echo "<br>LAPORAN UPAH RAPEL <br>";
            // echo "<table border='1'>
            // <tr><td>NIK</td>
            // <td>NAMA KARYAWAN</td>
            // <td>MASKER</td>
            // <td>STATUS</td>
            // <td>POKOK LAMA</td>
            // <td>GLOBAL LAMA</td>
            // <td>POKOK</td>
            // <td>TJBTN</td>
            // <td>TJENPEK</td>
            // <td>TMASKER</td>
            // <td>GLOBAL</td>
            // <td>S1</td>
            // <td>S2</td>
            // <td>TOT S</td>
            // <td>PREMI S</td>
            // <td>LEMBUR1 2023</td>
            // <td>ULEMBUR1 2023</td>
            // <td>LEMBUR2 2023</td>
            // <td>ULEMBUR2 2023</td>
            // <td>LEMBUR3 2023</td>
            // <td>ULEMBUR3 2023</td>
            // <td>TOT LBR 2023</td>
            // <td>UTOT LBR 2023</td>

            // <td>LEMBUR1 2024</td>
            // <td>ULEMBUR1 2024</td>
            // <td>LEMBUR2 2024</td>
            // <td>ULEMBUR2 2024</td>
            // <td>LEMBUR3 2024</td>
            // <td>ULEMBUR3 2024</td>
            // <td>TOT LBR 2024</td>
            // <td>UTOT LBR 2024</td>
            // <td>JML HADIR 2023</td>
            // <td>PREMI 2023</td>
            // <td>JML HADIR 2024</td>
            // <td>PREMI 2024</td>
            // <td>POTONGAN</td>
            // <td>TRANSPORT</td>
            // <td>MAKAN</td>
            // <td>ASURANSI</td>
            // <td>PENSIUN</td>
            // <td>ASTEK</td>
            // <td>SPMI</td>
            // <td>MANGKIR</td>
            // <td>BRUTO</td>
            // <td>PTKP</td>
            // <td>BJAB</td>
            // <td>ASTEK</td>
            // <td>TPOT</td>
            // <td>PKP</td>
            // <td>PPH21 - 1</td>
            // <td>PPH21 - 2</td>
            // <td>PPH21 - 3</td>
            // <td>NET+ASS</td>
            // <td>NETT</td>
            // <td>POT KOP</td>
            // <td>JML TERIMA</td>
            // </tr>";
            /* -------- base masker = 1 ------------------*/
            //2023
            $rp_masker = $this->m_hris->param_upah_tmp_id(1);
            foreach ($rp_masker->result() as $r) {
                $uph_masker1 = $r->nilai;
            }
            //2024
            $rp_masker = $this->m_hris->param_upah_id(1);
            foreach ($rp_masker->result() as $r) {
                $uph_masker2 = $r->nilai;
            }

            /* --------  shift 1-2 ------------------*/
            //2024
            //shift1-2 = 8
            $slbr1 = $this->m_hris->param_upah_id(8);
            foreach ($slbr1->result() as $slbr1a) {
                $shift_lbr1_baru = $slbr1a->nilai;
            }

            //shift3 = 9
            $slbr2 = $this->m_hris->param_upah_id(9);
            foreach ($slbr2->result() as $slbr2a) {
                $shift_lbr2_baru = $slbr2a->nilai;
            }

            //2023
            //shift1-2 = 8
            $slbr1 = $this->m_hris->param_upah_tmp_id(8);
            foreach ($slbr1->result() as $slbr1a) {
                $shift_lbr1_lama = $slbr1a->nilai;
            }

            //shift3 = 9
            $slbr2 = $this->m_hris->param_upah_tmp_id(9);
            foreach ($slbr2->result() as $slbr2a) {
                $shift_lbr2_lama = $slbr2a->nilai;
            }

            /* -------- pengali lembur 1 ------------------*/
            //2023
            $klbr1 = $this->m_hris->param_upah_tmp_id(11);
            foreach ($klbr1->result() as $klbr1a) {
                $kali_lembur1_lama = $klbr1a->nilai;
            }

            //pengali lembur 2
            $klbr2 = $this->m_hris->param_upah_tmp_id(12);
            foreach ($klbr2->result() as $klbr2a) {
                $kali_lembur2_lama = $klbr2a->nilai;
            }

            //pengali lembur 3
            $klbr3 = $this->m_hris->param_upah_tmp_id(13);
            foreach ($klbr3->result() as $klbr3a) {
                $kali_lembur3_lama = $klbr3a->nilai;
            }

            //2024
            $klbr1 = $this->m_hris->param_upah_id(11);
            foreach ($klbr1->result() as $klbr1a) {
                $kali_lembur1_baru = $klbr1a->nilai;
            }

            //pengali lembur 2
            $klbr2 = $this->m_hris->param_upah_id(12);
            foreach ($klbr2->result() as $klbr2a) {
                $kali_lembur2_baru = $klbr2a->nilai;
            }

            //pengali lembur 3
            $klbr3 = $this->m_hris->param_upah_id(13);
            foreach ($klbr3->result() as $klbr3a) {
                $kali_lembur3_baru = $klbr3a->nilai;
            }
            /* --------  pembagi lembur = 10 ------------------*/
            //2023
            $plbr = $this->m_hris->param_upah_tmp_id(10);
            foreach ($plbr->result() as $r) {
                $bagi_lembur_lama = $r->nilai;
            }

            //2024
            $plbr = $this->m_hris->param_upah_id(10);
            foreach ($plbr->result() as $r) {
                $bagi_lembur_baru = $r->nilai;
            }

            $tgl_skrg = date('d M Y  H:i:s');
            $usr = strtolower($this->session->userdata('nama'));

            $spreadsheet = new Spreadsheet();
            $tahap = ["Tahap1", "Tahap2"];
            $pgx = 1;

            $tot_global = 0;
            $tot_shift1 = 0;
            $tot_shift2 = 0;
            $tot_totshift = 0;
            $tot_ushift = 0;
            $tot_lbr1 = 0;
            $tot_lbr2 = 0;
            $tot_lbr3 = 0;
            $tot_totlbr = 0;
            $tot_ulbr = 0;
            $tot_premi  = 0;
            $tot_trans   = 0;
            $tot_mak    = 0;
            $tot_asr    = 0;
            $tot_pensiun = 0;
            $tot_astek = 0;
            $tot_spmi = 0;
            $tot_mangkir = 0;
            $tot_cpph = 0;
            $tot_netasr = 0;
            $tot_net = 0;
            $tot_potkop = 0;
            $tot_terima = 0;

            // foreach ($paygroup->result() as $pg)
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                // echo $fpaygroup[$pyg];
                ${"rekap_" . $paygroup[$pyg]} = array();
                ${"totpaygroup" . $paygroup[$pyg]} = array();
                $pgapok = 0;
                $pjbtn = 0;
                $pjenpek = 0;
                $pmasker = 0;
                $pglobal = 0;
                $pshift1 = 0;
                $pshift2 = 0;
                $ptotshift = 0;
                $ptotushift = 0;
                $plbr1 = 0;
                $plbr2 = 0;
                $plbr3 = 0;
                $ptotlbr = 0;
                $ptotulbr = 0;
                $ppremi = 0;
                $ptrans = 0;
                $pmak = 0;
                $pasr = 0;
                $ppen = 0;
                $past = 0;
                $ppspmi = 0;
                $pmang = 0;
                $ppph = 0;
                $pnetasr = 0;
                $pnet = 0;
                $ppotkop = 0;
                $pterima = 0;
                // echo $tahap[$p]."<br>";
                // Create a new worksheet called "My Data"
                $pgx = $pgx + 1;
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $paygroup[$pyg]);
                $spreadsheet->addSheet($myWorkSheet, $pgx);
                $spreadsheet->setActiveSheetIndexByName($paygroup[$pyg]);
                /* ----- START SETTING PAPER -------------------- */
                $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
                $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.7);
                $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.7);
                /* ----- END SETTING PAPER -------------------- */

                $sheet = $spreadsheet->getActiveSheet();
                $max_baris = $sheet->getHighestRow();

                // if($pgx>1)
                // {
                //     $max_baris = $max_baris + 3;
                // }
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Gaji Karyawan Bulanan Yang Termasuk ke Upah ' . $paygroup[$pyg]);
                //  echo 'A'.$max_baris." => Gaji Pay Group <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Bulan ' . $nama_bulan . ' ' . $tahun . ' (Lembur dari tgl : ' . $tgl_mulai . ' s/d ' . $tgl_akhir . ')');
                //  echo 'A'.$max_baris." => Periode <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Cimahi, ' . $tgl_skrg);
                //  echo 'A'.$max_baris." => Tanggal Akses <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $usr = strtolower($this->session->userdata('nama'));
                $sheet->setCellValue('A' . $max_baris, 'Dicetak Oleh : ' . $usr);
                //  echo 'A'.$max_baris." => Print <br>";

                $awal_baris = $max_baris + 2;
                $max_baris = $max_baris + 2;
                $max_bariss = $max_baris + 1;


                $sheet->getColumnDimension('A')->setWidth(3);
                $sheet->getColumnDimension('C')->setWidth(6);
                $sheet->getColumnDimension('F')->setWidth(8);
                $sheet->getColumnDimension('G')->setWidth(8);
                $sheet->getColumnDimension('H')->setWidth(8);
                $sheet->getColumnDimension('I')->setWidth(11);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('k')->setWidth(6);
                $sheet->getColumnDimension('L')->setWidth(6);
                $sheet->getColumnDimension('N')->setWidth(6);
                $sheet->getColumnDimension('O')->setWidth(6);
                $sheet->getColumnDimension('P')->setWidth(6);
                $sheet->getColumnDimension('Q')->setWidth(6);
                $sheet->getColumnDimension('S')->setWidth(8);
                $sheet->getColumnDimension('T')->setWidth(8);
                $sheet->getColumnDimension('U')->setWidth(8);
                $sheet->getColumnDimension('V')->setWidth(8);
                $sheet->getColumnDimension('W')->setWidth(8);
                $sheet->getColumnDimension('X')->setWidth(8);
                $sheet->getColumnDimension('Y')->setWidth(8);
                $sheet->getColumnDimension('Z')->setWidth(8);
                $sheet->getColumnDimension('AA')->setWidth(8);
                $sheet->getColumnDimension('AC')->setWidth(11);
                $sheet->getColumnDimension('AD')->setWidth(8);
                $sheet->getColumnDimension('AE')->setWidth(11);
                $sheet->getColumnDimension('AF')->setWidth(8);

                $sheet->getStyle('A' . $max_baris . ':AF' . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle('A' . $max_bariss . ':AF' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
                $sheet->getStyle('A' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('A' . $max_baris, 'No');
                $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
                $sheet->getStyle('B' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('B' . $max_baris, 'Nama Karyawan');
                $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
                $sheet->getStyle('C' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('C' . $max_baris, 'Masa Kerja');
                $sheet->mergeCells('D' . $max_baris . ':D' . $max_bariss);
                $sheet->getStyle('D' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('D' . $max_baris, 'Status Kary. Gol');
                $sheet->mergeCells('E' . $max_baris . ':E' . $max_bariss);
                $sheet->getStyle('E' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('E' . $max_baris, 'Gaji Pokok');
                $sheet->mergeCells('F' . $max_baris . ':H' . $max_baris); // colspan
                // $sheet->getStyle('F'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('F' . $max_baris, 'Tunjangan');
                $sheet->setCellValue('F' . $max_bariss, 'Jabatan');
                $sheet->setCellValue('G' . $max_bariss, 'Jen.Pek');
                $sheet->setCellValue('H' . $max_bariss, 'Mas.Ker');
                $sheet->mergeCells('I' . $max_baris . ':I' . $max_bariss);
                $sheet->getStyle('I' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('I' . $max_baris, 'Gaji per Bulan');
                $sheet->mergeCells('J' . $max_baris . ':M' . $max_baris);
                $sheet->setCellValue('J' . $max_baris, 'Premi Shift');
                // $sheet->getStyle('j'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');

                $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('J' . $max_bariss, 'Jml Jam1');
                $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('K' . $max_bariss, 'Jml Jam 2');
                $sheet->getStyle('L' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('L' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('M' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('M' . $max_bariss, 'Total Premi shift');
                $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_baris, 'Lbr1');
                $sheet->getStyle('N' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_baris, 'Lbr2');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('P' . $max_baris, 'Lbr3');
                $sheet->getStyle('P' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('P' . $max_bariss, 'Jml Jam');
                $sheet->mergeCells('Q' . $max_baris . ':R' . $max_baris);
                $sheet->getStyle('Q' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('Q' . $max_baris, 'Jumlah Lembur');
                $sheet->setCellValue('Q' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('R' . $max_bariss, 'Jml Upah');
                $sheet->mergeCells('S' . $max_baris . ':S' . $max_bariss);
                $sheet->getStyle('S' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('S' . $max_baris, 'Premi Hadir');
                $sheet->mergeCells('T' . $max_baris . ':T' . $max_bariss);
                $sheet->getStyle('T' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('T' . $max_baris, 'Transport');
                $sheet->mergeCells('U' . $max_baris . ':U' . $max_bariss);
                $sheet->setCellValue('U' . $max_baris, 'Makan');
                $sheet->mergeCells('V' . $max_baris . ':V' . $max_bariss);
                $sheet->setCellValue('V' . $max_baris, 'Asuransi');
                $sheet->mergeCells('W' . $max_baris . ':AA' . $max_baris);
                // $sheet->getStyle('W'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('W' . $max_baris, 'Potongan');
                $sheet->setCellValue('W' . $max_bariss, 'Pensiun');
                $sheet->setCellValue('X' . $max_bariss, 'Astek');
                $sheet->setCellValue('Y' . $max_bariss, 'SPMI');
                $sheet->setCellValue('Z' . $max_bariss, 'Mangkir');
                $sheet->setCellValue('AA' . $max_bariss, 'PPH21');
                $sheet->mergeCells('AB' . $max_baris . ':AE' . $max_baris);
                // $sheet->getStyle('AB'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('AB' . $max_baris, 'Bersih');
                $sheet->setCellValue('AB' . $max_bariss, 'Net + Asr');
                $sheet->setCellValue('AC' . $max_bariss, 'Net');
                $sheet->setCellValue('AD' . $max_bariss, 'Pot. Kopkar');
                $sheet->setCellValue('AE' . $max_bariss, 'Jml Terima');
                $sheet->setCellValue('AF' . $max_bariss, 'NIK');

                //  $new_baris = $new_baris+1;
                //  echo "baris ke : ".$max_baris;
                if ($fdepartment != '') {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }

                    foreach ($bag->result() as $b) {
                        // echo $b->indeks_hr."<br>";
                        $no = 0;
                        ${"total" . $b->recid_bag} = 0;
                        $bgapok = 0;
                        $bjbtn = 0;
                        $bjenpek = 0;
                        $bmasker = 0;
                        $bglobal = 0;
                        $bshift1 = 0;
                        $bshift2 = 0;
                        $btotshift = 0;
                        $btotushift = 0;
                        $blbr1 = 0;
                        $blbr2 = 0;
                        $blbr3 = 0;
                        $btotlbr = 0;
                        $btotulbr = 0;
                        $bpremi = 0;
                        $btrans = 0;
                        $bmak = 0;
                        $basr = 0;
                        $bpen = 0;
                        $bast = 0;
                        $bspmi = 0;
                        $bmang = 0;
                        $bpph = 0;
                        $bnetasr = 0;
                        $bnet = 0;
                        $bpotkop = 0;
                        $bterima = 0;

                        if ($fkaryawan != '') {
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        if ($karyawan->num_rows() > 0) {
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            $sheet->mergeCells('A' . $max_baris . ':AE' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->setCellValue('A' . $max_baris, "Bagian : " . $b->indeks_hr);

                            $max_baris = $sheet->getHighestRow();
                            $new_baris = $max_baris;
                            foreach ($karyawan->result() as $k) {
                                $new_baris = $new_baris + 1;
                                $recid_karyawan = $k->recid_karyawan;
                                $nik = $k->nik;
                                $nama_karyawan = $k->nama_karyawan;
                                $bagian = $k->indeks_hr;
                                $jabatan = $k->indeks_jabatan;
                                $t_jbtn = $k->t_jabatan;
                                $sts_jabatan = $k->sts_jabatan;
                                $tingkatan = $k->tingkatan;
                                $t_prestasi = $k->t_prestasi;
                                $t_jenpek = $k->t_jen_pek;
                                $penempatan = $k->penempatan;
                                // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                                $trakhir = date("Y-m-t", strtotime($tgl_akhir));
                                $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
                                $masker_tahun = $diff->format('%y');
                                $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;

                                if ($tingkatan == 1) {
                                    if ($penempatan == 'Jakarta') {
                                        if ($sts_jabatan == "Advisor") {
                                            //2023
                                            $gapokl = 0;
                                            $gapok_lama = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_lama);
                                            foreach ($gapok_lama->result() as $gp1) {
                                                $gapokb = round((80 / 100) * $gp1->nilai);
                                            }
                                        } else {
                                            //2023
                                            $gapok_lama = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_lama);
                                            foreach ($gapok_lama->result() as $gp1) {
                                                if ($sts_jabatan == "Advisor") {
                                                    $gapokl = round((80 / 100) * $gp1->nilai);
                                                } else {
                                                    $gapokl = $gp1->nilai;
                                                }
                                            }
                                            //2024
                                            $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_baru);
                                            foreach ($gapok_baru->result() as $gp2) {
                                                if ($sts_jabatan == "Advisor") {
                                                    $gapokb = round((80 / 100) * $gp2->nilai);
                                                } else {
                                                    $gapokb = $gp2->nilai;
                                                }
                                            }
                                        }
                                    } else {
                                        if ($sts_jabatan == "Advisor") {
                                            //2023
                                            $gapokl = 0;
                                            $gapok_lama = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_lama);
                                            foreach ($gapok_lama->result() as $gp1) {
                                                $gapokb = round((80 / 100) * $gp1->nilai);
                                            }
                                        } else {
                                            //2023
                                            $gapok_lama = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_lama);
                                            foreach ($gapok_lama->result() as $gp1) {
                                                $gapokl = $gp1->nilai;
                                            }
                                            //2024
                                            $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_baru);
                                            foreach ($gapok_baru->result() as $gp2) {
                                                $gapokb = $gp2->nilai;
                                            }
                                        }
                                    }
                                } else {
                                    if ($sts_jabatan == "Advisor") {
                                        $gapokl = 0;
                                        $gapok_lama = $this->m_hris->gapok($tingkatan, $tahun_lama);
                                        foreach ($gapok_lama->result() as $gp1) {
                                            $gapokb = round((80 / 100) * $gp1->nilai);
                                        }
                                    } else {
                                        //2023
                                        $gapok_lama = $this->m_hris->gapok($tingkatan, $tahun_lama);
                                        foreach ($gapok_lama->result() as $gp1) {
                                            if ($sts_jabatan == "Advisor") {
                                                $gapokl = round((80 / 100) * $gp1->nilai);
                                            } else {
                                                $gapokl = $gp1->nilai;
                                            }
                                        }
                                        //2024
                                        $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun_baru);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            if ($sts_jabatan == "Advisor") {
                                                $gapokb = round((80 / 100) * $gp2->nilai);
                                            } else {
                                                $gapokb = $gp2->nilai;
                                            }
                                        }
                                    }
                                }

                                if ($k->tingkatan == 1) {
                                    //operator tunjangan jabatan = 0
                                    $t_jbtn1 = 0;
                                    $t_jbtn2 = 0;
                                    // cek tunjangan jenis pekerjaan by bagian
                                    $tjenpek1 = $this->m_hris->tjenpek($k->recid_bag, $tahun_lama);
                                    if ($tjenpek1->num_rows() > 0) {
                                        foreach ($tjenpek1->result() as $t1) {
                                            $t_jenpek1 = $t1->nilai;
                                        }
                                    } else {
                                        $t_jenpek1 = 0;
                                    }


                                    $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun_baru);
                                    if ($tjenpek2->num_rows() > 0) {
                                        foreach ($tjenpek2->result() as $t2) {
                                            $t_jenpek2 = $t2->nilai;
                                        }
                                    } else {
                                        $t_jenpek2 = 0;
                                    }

                                    // echo "t_jbtn : $t_jbtn<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";

                                } else {
                                    //wakaru ke atas, t_jenpek = 0
                                    $t_jenpek1 = 0;
                                    $t_jenpek2 = 0;
                                    if ($sts_jabatan == "Advisor") {
                                        $tjbtn1 = $this->m_hris->tjabatan($tingkatan, $tahun_lama);
                                        foreach ($tjbtn1->result() as $t1) {
                                            $t_jbtn2 = round((80 / 100) * $t1->nilai);
                                        }
                                        $t_jbtn1 = 0;
                                    } else {
                                        //cek tunjangan jabatannya
                                        //2023
                                        $tjbtn1 = $this->m_hris->tjabatan($tingkatan, $tahun_lama);
                                        foreach ($tjbtn1->result() as $t1) {
                                            if ($sts_jabatan == "Advisor") {
                                                $t_jbtn1 = round((80 / 100) * $t1->nilai);
                                            } else {
                                                $t_jbtn1 = $t1->nilai;
                                            }
                                        }
                                        //2024
                                        $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun_baru);
                                        foreach ($tjbtn2->result() as $t2) {
                                            if ($sts_jabatan == "Advisor") {
                                                $t_jbtn2 = round((80 / 100) * $t2->nilai);
                                            } else {
                                                $t_jbtn2 = $t2->nilai;
                                            }
                                        }
                                    }
                                }
                                $t_masker1 = $masker_tahun * $uph_masker1; //2023
                                $t_masker2 = $masker_tahun * $uph_masker2; //2024
                                $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                                $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
                                // if ($sts_jabatan == "Advisor") {
                                //     $global_lama = ((80 / 100) * ($gapokl + $t_jbtn1)) + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                                //     $global_baru = ((80 / 100) * ($gapokb + $t_jbtn2)) + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
                                // } else {
                                //     $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
                                //     $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
                                // }



                                /* SHIFT */
                                //2023
                                $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 14);
                                $s1_lama = $shift1->num_rows();
                                $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 15);
                                $s2_lama = $shift2->num_rows();
                                $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 16);
                                $s3_lama = $shift3->num_rows();

                                $slembur1_lama = $s1_lama + $s2_lama;
                                $uslembur1_lama = $slembur1_lama * $shift_lbr1_lama;
                                $slembur2_lama = $s3_lama;
                                $uslembur2_lama = $slembur2_lama * $shift_lbr2_lama;
                                $utot_shift_lama = $uslembur1_lama + $uslembur2_lama;

                                //2024
                                $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 14);
                                $s1_baru = $shift1->num_rows();
                                $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 15);
                                $s2_baru = $shift2->num_rows();
                                $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 16);
                                $s3_baru = $shift3->num_rows();

                                $slembur1_baru = $s1_baru + $s2_baru;
                                $uslembur1_baru = $slembur1_baru * $shift_lbr1_baru;
                                $slembur2_baru = $s3_baru;
                                $uslembur2_baru = $slembur2_baru * $shift_lbr2_baru;
                                $utot_shift_baru = $uslembur1_baru + $uslembur2_baru;

                                $slembur1 = $slembur1_baru + $slembur1_lama;
                                $uslembur1 = $uslembur1_baru +  $uslembur1_lama;
                                $slembur2 = $slembur2_baru + $slembur2_lama;
                                $uslembur2 = $uslembur2_baru + $uslembur2_lama;
                                $utot_shift = $utot_shift_baru + $utot_shift_lama;

                                //2023
                                $lemburan_lama = $this->m_lembur->karyawan_lembur_report($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                $tot_lbr_lama = 0;
                                foreach ($lemburan_lama->result() as $l) {
                                    if ($l->lembur1 != "") {
                                        $lembur1_lama = round($l->lembur1, 1);
                                    } else {
                                        $lembur1_lama = $l->lembur1;
                                    }

                                    if ($l->lembur2 != "") {
                                        $lembur2_lama = round($l->lembur2, 1);
                                    } else {
                                        $lembur2_lama = $l->lembur2;
                                    }

                                    if ($l->lembur3 != "") {
                                        $lembur3_lama = round($l->lembur3, 1);
                                    } else {
                                        $lembur3_lama = $l->lembur3;
                                    }
                                }

                                //2024
                                $lemburan_baru = $this->m_lembur->karyawan_lembur_report($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                $tot_lbr_baru = 0;
                                foreach ($lemburan_baru->result() as $l) {
                                    if ($l->lembur1 != 0) {
                                        $lembur1_baru = round($l->lembur1, 1);
                                    } else {
                                        $lembur1_baru = $l->lembur1;
                                    }

                                    if ($l->lembur2 != 0) {
                                        $lembur2_baru = round($l->lembur2, 1);
                                    } else {
                                        $lembur2_baru = $l->lembur2;
                                    }

                                    if ($l->lembur3 != 0) {
                                        $lembur3_baru = round($l->lembur3, 1);
                                    } else {
                                        $lembur3_baru = $l->lembur3;
                                    }
                                }

                                // Cek adjustment lemburan 2023
                                $atrans_lama = 0;
                                $amakan_lama = 0;
                                $adj_upah_lama = $this->m_lembur->adjust_periode_karyawan($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                if ($adj_upah_lama->num_rows() > 0) {
                                    foreach ($adj_upah_lama->result() as $al) {
                                        $lembur1_lama = $lembur1_lama + $al->jam_lbr1;
                                        $lembur2_lama = $lembur2_lama + $al->jam_lbr2;
                                        $lembur3_lama = $lembur3_lama + $al->jam_lbr3;
                                        $atrans_lama = $al->jml_transport;
                                        $amakan_lama = $al->jml_makan;
                                    }
                                } else {
                                    $atrans_lama = 0;
                                    $amakan_lama = 0;
                                }

                                // Cek adjustment lemburan 2024
                                $atrans_baru = 0;
                                $amakan_baru = 0;
                                $adj_upah_baru = $this->m_lembur->adjust_periode_karyawan($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                if ($adj_upah_baru->num_rows() > 0) {
                                    foreach ($adj_upah_baru->result() as $al) {
                                        $lembur1_baru = $lembur1_baru + $al->jam_lbr1;
                                        $lembur2_baru = $lembur2_baru + $al->jam_lbr2;
                                        $lembur3_baru = $lembur3_baru + $al->jam_lbr3;
                                        $atrans_baru = $al->jml_transport;
                                        $amakan_baru = $al->jml_makan;
                                    }
                                } else {
                                    $atrans_baru = 0;
                                    $amakan_baru = 0;
                                }

                                $lembur1 = $lembur1_baru + $lembur1_lama;
                                $lembur2 = $lembur2_baru + $lembur2_lama;
                                $lembur3 = $lembur3_baru + $lembur3_lama;
                                $atrans = $atrans_baru + $atrans_lama;
                                $amakan = $amakan_baru + $amakan_lama;

                                //2023
                                $uph_lbr1_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur1_lama);
                                $lbr1_lama = $uph_lbr1_lama * $lembur1_lama;
                                $uph_lbr2_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur2_lama);
                                $lbr2_lama = $uph_lbr2_lama * $lembur2_lama;
                                $uph_lbr3_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur3_lama);
                                $lbr3_lama = $uph_lbr3_lama * $lembur3_lama;
                                $tot_lbr_lama = $lbr1_lama + $lbr2_lama + $lbr3_lama;


                                //2024
                                $uph_lbr1_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur1_baru);
                                $lbr1_baru = $uph_lbr1_baru * $lembur1_baru;
                                $uph_lbr2_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur2_baru);
                                $lbr2_baru = $uph_lbr2_baru * $lembur2_baru;
                                $uph_lbr3_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur3_baru);
                                $lbr3_baru = $uph_lbr3_baru * $lembur3_baru;
                                $tot_lbr_baru = $lbr1_baru + $lbr2_baru + $lbr3_baru;

                                $uph_lbr1 = $uph_lbr1_lama + $uph_lbr1_baru;
                                $lbr1 = $lbr1_lama + $lbr1_baru;
                                $uph_lbr2 = $uph_lbr2_lama + $uph_lbr2_baru;
                                $lbr2 =  $lbr2_lama +  $lbr2_baru;
                                $uph_lbr3 = $uph_lbr3_lama + $uph_lbr3_baru;
                                $lbr3 = $lbr3_lama + $lbr3_baru;
                                $tot_lbr = $tot_lbr_lama + $tot_lbr_baru;
                                /* END LEMBURAN */

                                /* START PREMI */
                                $tingkatan = $k->tingkatan;
                                if ($tingkatan >= 1 and $tingkatan < 4) {
                                    //param premi hadir operator - karu, id = 2
                                    //2023
                                    $ph = $this->m_hris->param_upah_tmp_id(2);
                                    foreach ($ph->result() as $p) {
                                        $premi_lama = $p->nilai;
                                    }

                                    //2024
                                    $ph = $this->m_hris->param_upah_id(2);
                                    foreach ($ph->result() as $p) {
                                        $premi_baru = $p->nilai;
                                    }
                                } else if ($tingkatan >= 4 and $tingkatan <= 5) {
                                    //param premi hadir wakasi - kasi, id = 3
                                    //2023
                                    if ($sts_jabatan == "Advisor") {
                                        $premi_lama = 20000;
                                        $premi_baru = 20000;
                                    } else {
                                        $ph = $this->m_hris->param_upah_tmp_id(3);
                                        foreach ($ph->result() as $p) {
                                            $premi_lama = $p->nilai;
                                        }
                                        //2024
                                        $ph = $this->m_hris->param_upah_id(3);
                                        foreach ($ph->result() as $p) {
                                            $premi_baru = $p->nilai;
                                        }
                                    }
                                } else {
                                    $premi_lama = 0;
                                    $premi_baru = 0;
                                }

                                // echo "premi : ".$premi_lama." - ".$premi_baru."<br>";

                                //2023
                                $total_hadir_lama = $this->m_absenbarcode->hitung_kerja($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                $jml_hadir_lama = $total_hadir_lama->num_rows();
                                $upah_hadir_lama = $jml_hadir_lama * $premi_lama;

                                //2024
                                $total_hadir_baru = $this->m_absenbarcode->hitung_kerja($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                $jml_hadir_baru = $total_hadir_baru->num_rows();
                                $upah_hadir_baru = $jml_hadir_baru * $premi_baru;
                                $jml_hadir = $jml_hadir_lama + $jml_hadir_baru;
                                $upah_hadir = $upah_hadir_lama + $upah_hadir_baru;

                                // potong terlambat terencana
                                $pr = $this->m_hris->param_upah_id(4);
                                foreach ($pr->result() as $pr1) {
                                    $ppersenpr = $pr1->nilai;
                                }

                                // potong terlambat tidak terencana
                                $ptr = $this->m_hris->param_upah_id(5);
                                foreach ($ptr->result() as $ptr1) {
                                    $ppersenptr = $ptr1->nilai;
                                }

                                // potong izin keluar > 2 jam
                                $pk = $this->m_hris->param_upah_id(6);
                                foreach ($pk->result() as $pk1) {
                                    $ppersenpk = $pk1->nilai;
                                }

                                // potong pulang cepat
                                $pp = $this->m_hris->param_upah_id(7);
                                foreach ($pp->result() as $pp1) {
                                    $ppersenpp = $pp1->nilai;
                                }
                                //2023
                                $trencana_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Terlambat Terencana');
                                $prencana_lama = $trencana_lama->num_rows() * ($premi_lama * ($ppersenpr / 100));

                                //2024
                                $trencana_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Terlambat Terencana');
                                $prencana_baru = $trencana_baru->num_rows() * ($premi_baru * ($ppersenpr / 100));

                                $prencana = $prencana_lama + $prencana_baru;

                                //2023
                                $tdkrencana_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Terlambat Tidak Terencana');
                                $ptdrencana_lama = $tdkrencana_lama->num_rows() * ($premi_lama * ($ppersenptr / 100));

                                //2024
                                $tdkrencana_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Terlambat Tidak Terencana');
                                $ptdrencana_baru = $tdkrencana_baru->num_rows() * ($premi_baru * ($ppersenptr / 100));

                                $ptdrencana = $ptdrencana_baru + $ptdrencana_lama;

                                //2023
                                $pulang_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Pulang');
                                $ppulang_lama = $pulang_lama->num_rows() * ($premi_lama * ($ppersenpp / 100));

                                //2024
                                $pulang_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Pulang');
                                $ppulang_baru = $pulang_baru->num_rows() * ($premi_baru * ($ppersenpp / 100));

                                $ppulang = $ppulang_baru + $ppulang_lama;

                                //2023
                                $keluar_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Keluar');
                                $cnt_keluar_lama = 0;
                                foreach ($keluar_lama->result() as $i) {
                                    if ($i->over_durasi >= 2) {
                                        $cnt_keluar_lama = $cnt_keluar_lama + 1;
                                    }
                                }
                                $pkeluar_lama = $cnt_keluar_lama * ($premi_lama * ($ppersenpk / 100));

                                //2024
                                $keluar_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Keluar');
                                $cnt_keluar_baru = 0;
                                foreach ($keluar_baru->result() as $i) {
                                    if ($i->over_durasi >= 2) {
                                        $cnt_keluar_baru = $cnt_keluar_baru + 1;
                                    }
                                }
                                $pkeluar_baru = $cnt_keluar_baru * ($premi_baru * ($ppersenpk / 100));

                                // echo "potongan keluar : ".$pkeluar_lama." + ".$pkeluar_baru."<br>";
                                // echo "potongan pulang : ".$ppulang_lama." + ".$ppulang_baru."<br>";
                                // echo "potongan terencana : ".$prencana_lama." + ".$prencana_baru."<br>";
                                // echo "potongan tidak terencana : ".$ptdrencana_lama." + ".$ptdrencana_baru."<br>";

                                //2023
                                $bonus_lama = $upah_hadir_lama - ($pkeluar_lama + $ppulang_lama + $ptdrencana_lama + $prencana_lama);
                                $bonus_baru = $upah_hadir_baru - ($pkeluar_baru + $ppulang_baru + $ptdrencana_baru + $prencana_baru);

                                $bonus = round($bonus_lama + $bonus_baru);
                                $potongan = ($pkeluar_lama + $ppulang_lama + $ptdrencana_lama + $prencana_lama) + ($pkeluar_baru + $ppulang_baru + $ptdrencana_baru + $prencana_baru);
                                /* END PREMI */

                                /* UANG TRANSPORT */
                                // transport lembur holiday
                                //2023
                                $ut_lama = $this->m_hris->param_upah_tmp_id(14);
                                foreach ($ut_lama->result() as $ut1) {
                                    $utrans2_lama = $ut1->nilai;
                                }
                                // transport lembur terusan
                                $ut_lama = $this->m_hris->param_upah_tmp_id(39);
                                foreach ($ut_lama->result() as $ut1) {
                                    $utrans1_lama = $ut1->nilai;
                                }

                                //2024
                                $ut_baru = $this->m_hris->param_upah_id(14);
                                foreach ($ut_baru->result() as $ut1) {
                                    $utrans2_baru = $ut1->nilai;
                                }
                                // transport lembur terusan
                                $ut_baru = $this->m_hris->param_upah_id(39);
                                foreach ($ut_baru->result() as $ut1) {
                                    $utrans1_baru = $ut1->nilai;
                                }

                                // transport lembur terusan
                                //2023
                                $transport_lama = $this->m_lembur->transport_lembur_karyawan($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                $trsp_lama = $transport_lama->num_rows();
                                $transport2_lama = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                $trsp2_lama = $transport2_lama->num_rows();

                                //2024
                                $transport_baru = $this->m_lembur->transport_lembur_karyawan($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                $trsp_baru = $transport_baru->num_rows();
                                $transport2_baru = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                $trsp2_baru = $transport2_baru->num_rows();

                                // transport shift1,2,3
                                //2023
                                $shift1_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 14);
                                $s1_lama = $shift1_lama->num_rows();
                                $shift2_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 15);
                                $s2_lama = $shift2_lama->num_rows();
                                $shift3_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 16);
                                $s3_lama = $shift3_lama->num_rows();
                                $tot_shift_lama = $s1_lama + $s2_lama + $s3_lama;
                                $uang_transport1_lama = $trsp_lama * $utrans1_lama;
                                $uang_transport2_lama = ($trsp2_lama + $tot_shift_lama) * $utrans2_lama;
                                $uang_transport_adj_lama = $atrans_lama * $utrans2_lama;
                                $uang_transport_lama = $uang_transport1_lama + $uang_transport2_lama + $uang_transport_adj_lama;

                                //2024
                                $shift1_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 14);
                                $s1_baru = $shift1_baru->num_rows();
                                $shift2_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 15);
                                $s2_baru = $shift2_baru->num_rows();
                                $shift3_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 16);
                                $s3_baru = $shift3_baru->num_rows();
                                $tot_shift_baru = $s1_baru + $s2_baru + $s3_baru;
                                $uang_transport1_baru = $trsp_baru * $utrans1_baru;
                                $uang_transport2_baru = ($trsp2_baru + $tot_shift_baru) * $utrans2_baru;
                                $uang_transport_adj_baru = $atrans_baru * $utrans2_baru;
                                $uang_transport_baru = $uang_transport1_baru + $uang_transport2_baru + $uang_transport_adj_baru;
                                // $uang_transport = $uang_transport1 + $uang_transport2;

                                $uang_transport = $uang_transport_lama + $uang_transport_baru;
                                /* END UANG TRANSPORT */

                                /* START UANG MAKAN */
                                // uang makan
                                //2023
                                $um_lama = $this->m_hris->param_upah_tmp_id(15);
                                foreach ($um_lama->result() as $um1) {
                                    $umakan_lama = $um1->nilai;
                                }
                                $uang_makan_lama = $s3_lama * $umakan_lama;

                                //2024
                                $um_baru = $this->m_hris->param_upah_id(15);
                                foreach ($um_baru->result() as $um1) {
                                    $umakan_baru = $um1->nilai;
                                }
                                $uang_makan_baru = $s3_baru * $umakan_baru;

                                // makan lembur
                                //2023
                                $mkn_lama = $this->m_lembur->makan_lembur($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
                                // $makaan = $mkn->num_rows();
                                $makaan_lama = ($mkn_lama->num_rows()) + $amakan_lama;
                                $makan_lama = $makaan_lama * $umakan_lama;
                                $uang_makan_lama = $uang_makan_lama + $makan_lama;

                                //2024
                                $mkn_baru = $this->m_lembur->makan_lembur($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
                                // $makaan = $mkn->num_rows();
                                $makaan_baru = ($mkn_baru->num_rows()) + $amakan_baru;
                                $makan_baru = $makaan_baru * $umakan_baru;
                                $uang_makan_baru = $uang_makan_baru + $makan_baru;

                                $uang_makan = $uang_makan_lama + $uang_makan_baru;

                                // /* START ADJUSTMENT */
                                // $adj_makan = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                // if($adj_makan->num_rows() > 0)
                                // {
                                //     foreach ($adj_makan->result() as $am) {
                                //         $uang_makan = $uang_makan + $makan + ($am->jml_makan * $umakan);
                                //     }
                                // }
                                /* END ADJUSTMENT */

                                // $uang_makan = 13000;
                                /* END UANG MAKAN */

                                /* START ASTEK */
                                // astek jp
                                if ($k->cek_bpjs_tk == '1') {
                                    $ajp = $this->m_hris->param_upah_id(19);
                                    foreach ($ajp->result() as $ajp1) {
                                        $asuransi_jp = $ajp1->nilai;
                                    }

                                    // astek jht
                                    $ajht = $this->m_hris->param_upah_id(20);
                                    foreach ($ajht->result() as $ajht1) {
                                        $asuransi_jht = $ajht1->nilai;
                                    }
                                } else {
                                    $asuransi_jp = 0;
                                    $asuransi_jht = 0;
                                }

                                //astek bpjs kesehatan
                                if ($k->cek_bpjs_kes == '1') {
                                    $abkes = $this->m_hris->param_upah_id(21);
                                    foreach ($abkes->result() as $abkes1) {
                                        $asuransi_bkes = $abkes1->nilai;
                                    }
                                } else {
                                    $asuransi_bkes = 0;
                                }

                                $kali_astek = $asuransi_jp + $asuransi_jht + $asuransi_bkes;
                                $astek = $global_baru * ($kali_astek / 100);
                                /* END ASTEK */

                                /* START ASURANSI PERUSAHAAN */
                                if ($k->cek_bpjs_tk == '1') {
                                    //persentase jkm
                                    $jkm = $this->m_hris->param_upah_id(16);
                                    foreach ($jkm->result() as $jkm1) {
                                        $ujkm = $jkm1->nilai;
                                    }

                                    // persentase jkk
                                    $jkk = $this->m_hris->param_upah_id(17);
                                    foreach ($jkk->result() as $jkk1) {
                                        $ujkk = $jkk1->nilai;
                                    }
                                } else {
                                    $ujkm = 0;
                                    $ujkk = 0;
                                }

                                if ($k->cek_bpjs_kes == '1') {
                                    //persentase bpjs kesehatan
                                    $bpjs = $this->m_hris->param_upah_id(18);
                                    foreach ($bpjs->result() as $bpjs1) {
                                        $ubpjs = $bpjs1->nilai;
                                    }
                                } else {
                                    $ubpjs = 0;
                                }


                                $per_asr = $ujkm + $ujkk + $ubpjs;
                                $uasuransi = $global_baru * ($per_asr / 100);
                                /* END ASURANSI PERUSAHAAN */

                                /* START PPH21 */
                                $bruto = $global_baru + $tot_lbr + $utot_shift + ($upah_hadir - ($potongan)) + $uang_transport + $uang_makan + $uasuransi;

                                /* START PENSIUN */
                                if ($k->pensiun == "Ya") {
                                    // persentase dplk
                                    $dplk = $this->m_hris->param_upah_id(22);
                                    foreach ($dplk->result() as $dplk1) {
                                        $dplk_aia = $dplk1->nilai;
                                    }
                                    $pot_dplk = $global_baru * ($dplk_aia / 100);
                                } else {
                                    $pot_dplk = 0;
                                }
                                /* END PENSIUN */

                                /* START SPMI */
                                if ($k->lspmi == "Ya") {
                                    // spmi
                                    $spmi = $this->m_hris->param_upah_id(23);
                                    foreach ($spmi->result() as $spmi1) {
                                        $pspmi = $spmi1->nilai;
                                    }
                                    $uspmi = ($gapokb * ($pspmi / 100)) + 5000;
                                } else {
                                    $uspmi = 0;
                                }
                                /* END SPMI */

                                /* START MANGKIR */
                                $mkr = $this->m_absenbarcode->hitung_mangkir($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                // potongan mangir
                                $mangkir = $this->m_hris->param_upah_id(24);
                                foreach ($mangkir->result() as $mangkir1) {
                                    $pmangkir = $mangkir1->nilai;
                                }
                                $umangkir = $mkr->num_rows() * (($pmangkir / 100) * $global_baru);
                                /* END MAGKIR */

                                /* START PTKP */
                                $sts_kawin = $k->sts_penunjang;
                                if ($sts_kawin == "K0") {
                                    // ptkp K0
                                    $ptkp = $this->m_hris->param_upah_id(28);
                                } else if ($sts_kawin == "K1") {
                                    // ptkp K1
                                    $ptkp = $this->m_hris->param_upah_id(29);
                                } else if ($sts_kawin == "K2") {
                                    $ptkp = $this->m_hris->param_upah_id(30);
                                } else if ($sts_kawin == "K3") {
                                    // ptkp K3
                                    $ptkp = $this->m_hris->param_upah_id(31);
                                } else if ($sts_kawin == "TK1") {
                                    // ptkp TK1
                                    $ptkp = $this->m_hris->param_upah_id(45);
                                } else if ($sts_kawin == "TK2") {
                                    // ptkp TK2
                                    $ptkp = $this->m_hris->param_upah_id(46);
                                } else if ($sts_kawin == "TK3") {
                                    // ptkp TK3
                                    $ptkp = $this->m_hris->param_upah_id(47);
                                } else {
                                    // ptkp TK
                                    $ptkp = $this->m_hris->param_upah_id(27);
                                }
                                foreach ($ptkp->result() as $ptkp1) {
                                    $uptkp = $ptkp1->nilai;
                                }
                                /* END PTKP */

                                /* START BIAYA JABATAN */
                                //biaya jabatan
                                $bjab = $this->m_hris->param_upah_id(25);
                                foreach ($bjab->result() as $bjab1) {
                                    $b_jabatan = $bjab1->nilai;
                                }

                                //max biaya jabatan
                                $max_jbtn = $this->m_hris->param_upah_id(26);
                                foreach ($max_jbtn->result() as $max_jbtn1) {
                                    $max_jab = $max_jbtn1->nilai;
                                }

                                $biaya_jabatan = $bruto * ($b_jabatan / 100);
                                if ($biaya_jabatan > $max_jab) {
                                    $biaya_jabatan = $max_jab;
                                }
                                /* END BIAYA JABATAN */

                                $astek_pph = $asuransi_jp + $asuransi_jht;
                                $uapph = $global_baru * ($astek_pph / 100);
                                $tot_pot = $uptkp + $biaya_jabatan + $uapph + $pot_dplk;
                                $pkp = $bruto - $tot_pot;
                                /* START LAPISAN PKP */
                                // lapisan pkp per bulan
                                $lvlpkp = [40, 41, 42];
                                $lap_pkp1 = 0;
                                $lap_pkp2 = 0;
                                $lap_pkp3 = 0;
                                for ($i = 0; $i < count($lvlpkp); $i++) {

                                    $lapisan = $this->m_hris->param_upah_id($lvlpkp[$i]);
                                    foreach ($lapisan->result() as $hasil) {
                                        $x = $i + 1;
                                        ${"lap_pkp" . $x} = $hasil->nilai;
                                        // echo ${"lap_pkp" . $x};
                                    }
                                }

                                // echo $lap_pkp1;
                                $cek_npwp = $k->cek_npwp;
                                if ($cek_npwp == '1') {
                                    $perpkp = [32, 33, 34];
                                } else {
                                    $perpkp = [44, 33, 34];
                                }
                                $per_pkp1 = 0;
                                $per_pkp2 = 0;
                                $per_pkp3 = 0;
                                for ($x = 0; $x < count($perpkp); $x++) {
                                    $persen = $this->m_hris->param_upah_id($perpkp[$x]);
                                    foreach ($persen->result() as $hasil2) {
                                        ${"per_pkp" . ($x + 1)} = $hasil2->nilai;
                                    }
                                }

                                // echo $per_pkp[$x+1]."<br>";
                                // echo ($lap_pkp1)."<br>";
                                if ($pkp >= 0 and $pkp <= $lap_pkp1) {
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pphl2 = 0;
                                    $pphl3 = 0;
                                } else if ($pkp > $lap_pkp1 and $pkp <= $lap_pkp2) {
                                    // echo "lapisan 2<br>";
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pkp = $pkp - $lap_pkp1;
                                    if ($pkp >= $lap_pkp2) {
                                        $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                                    } else {
                                        $pphl2 = round($pkp * ($per_pkp2 / 100));
                                    }
                                    // echo "Lapisan 2 : ".$pphl2."<br>";
                                    $pphl3 = 0;
                                } else if ($pkp > $lap_pkp2 and $pkp <= $lap_pkp3) {
                                    // echo "lapisan 3";
                                    if ($pkp >= $lap_pkp1) {
                                        $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                                    } else {
                                        $pphl1 = round($pkp * ($per_pkp1 / 100));
                                    }
                                    // echo "Lapisan 1 : ".$pphl1."<br>";
                                    $pkp2 = $pkp - $lap_pkp1;
                                    if ($pkp2 >= $lap_pkp2) {
                                        $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                                    } else {
                                        $pphl2 = round($pkp * ($per_pkp2 / 100));
                                    }
                                    $pkp3 = $pkp - $lap_pkp1 - $lap_pkp2;
                                    $pphl3 = round($pkp3 * ($per_pkp3 / 100));
                                } else {
                                    $pphl1 = 0;
                                    $pphl2 = 0;
                                    $pphl3 = 0;
                                }
                                /* END LAPISAN PKP */
                                $tot_pph = $pphl1 + $pphl2 + $pphl3;

                                /* END PPH21 */

                                /* NETT */
                                $net_asr = $bruto - $pot_dplk - $astek - $uspmi - $umangkir - $tot_pph;
                                $net = $net_asr - $uasuransi;
                                $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;

                                $kopkar = $this->m_lembur->potongan_kopkar($recid_karyawan, $tahun, $bulan);
                                if ($kopkar->num_rows() > 0) {
                                    foreach ($kopkar->result() as $kop) {
                                        $pot_kopkar = $kop->potongan;
                                    }
                                } else {
                                    $pot_kopkar = 0;
                                }
                                $jml_terima = $net - $pot_kopkar;

                                // echo "
                                // <tr>
                                // <td>".$nik."</td>
                                // <td>".$nama_karyawan."</td>
                                // <td>".$masker_tahun."</td>
                                // <td>".$sts_pph."</td>
                                // <td>".$gapokl."</td>
                                // <td>".$global_lama."</td>
                                // <td>".$gapokb."</td>
                                // <td>".$t_jbtn2."</td>
                                // <td>".$t_jenpek2."</td>
                                // <td>".$t_masker2."</td>
                                // <td>".$global_baru."</td>
                                // <td>".$slembur1."</td>
                                // <td>".$slembur2."</td>
                                // <td>".$slembur1 + $slembur2."</td>
                                // <td>".$utot_shift."</td>
                                // <td>".$lembur1_lama."</td>
                                // <td>".$uph_lbr1_lama."</td>
                                // <td>".$lembur2_lama."</td>
                                // <td>".$uph_lbr2_lama."</td>
                                // <td>".$lembur3_lama."</td>
                                // <td>".$uph_lbr3_lama."</td>
                                // <td>".$lembur1_lama + $lembur2_lama + $lembur3_lama."</td>
                                // <td>".$tot_lbr_lama."</td>

                                // <td>".$lembur1_baru."</td>
                                // <td>".$uph_lbr1_baru."</td>
                                // <td>".$lembur2_baru."</td>
                                // <td>".$uph_lbr2_baru."</td>
                                // <td>".$lembur3_baru."</td>
                                // <td>".$uph_lbr3_baru."</td>
                                // <td>".$lembur1_baru + $lembur2_baru + $lembur3_baru."</td>
                                // <td>".$tot_lbr_baru."</td>
                                // <td>".$jml_hadir_lama."</td>
                                // <td>".$upah_hadir_lama."</td>
                                // <td>".$jml_hadir_baru."</td>
                                // <td>".$upah_hadir_baru."</td>
                                // <td>".$potongan."</td>
                                // <td>".$uang_transport_baru + $uang_transport_lama."</td>
                                // <td>".$uang_makan_baru + $uang_makan_lama."</td>
                                // <td>".round($uasuransi)."</td>
                                // <td>".round($pot_dplk)."</td>
                                // <td>".round($astek)."</td>
                                // <td>".round($uspmi)."</td>
                                // <td>".round($umangkir)."</td>
                                // <td>".round($bruto)."</td>
                                // <td>".round($uptkp)."</td>
                                // <td>".round($biaya_jabatan)."</td>
                                // <td>".round($uapph)."</td>
                                // <td>".round($tot_pot)."</td>
                                // <td>".round($pkp)."</td>
                                // <td>".round($pphl1)."</td>
                                // <td>".round($pphl2)."</td>
                                // <td>".round($pphl3)."</td>
                                // <td>".round($net_asr)."</td>
                                // <td>".round($net)."</td>
                                // <td>".round($pot_kopkar)."</td>
                                // <td>".round($jml_terima)."</td>
                                // </tr>";

                                /* -----------------------START SAVE DATA TO DATABASE--------------------- */
                                if ($tipe == 1) {
                                    $data = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'bulan'             => $bulan,
                                        'tahun'             => $tahun,
                                        'periode_awal'      => $tgl_mulai,
                                        'periode_akhir'     => $tgl_akhir,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker2,
                                        'upokok'            => $gapokb,
                                        'tjbtn'             => $t_jbtn2,
                                        'tjenpek'           => $t_jenpek2,
                                        'uglobal'           => $global_baru,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1_baru,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2_baru,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $bonus,
                                        'trencana'          => $trencana_lama->num_rows() + $trencana_baru->num_rows(),
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana_baru->num_rows() + $tdkrencana_lama->num_rows(),
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang_lama->num_rows() + $pulang_baru->num_rows(),
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar_lama->num_rows() + $keluar_baru->num_rows(),
                                        'keluar2'           => $cnt_keluar_lama + $cnt_keluar_baru,
                                        'rp_keluar2'        => $pkeluar_baru + $pkeluar_lama,
                                        'premi'             => $bonus,
                                        'uang_transport'    => $uang_transport_baru + $uang_transport_lama,
                                        'uang_makan'        => $uang_makan_baru + $uang_makan_lama,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pphl1,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'netasr'            => $net_asr,
                                        'netto'             => $net,
                                        'pot_kopkar'        => $pot_kopkar,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_upah($data);
                                    $last = $this->m_lembur->last_upahid();
                                    foreach ($last->result() as $lu) {
                                        $recid_upah = $lu->recid_upah;
                                    }

                                    $datalog = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'id_unik'           => $iduniq,
                                        'recid_upah'        => $recid_upah,
                                        'bulan'             => $bulan,
                                        'tahun'             => $tahun,
                                        'periode_awal'      => $tgl_mulai,
                                        'periode_akhir'     => $tgl_akhir,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker2,
                                        'upokok'            => $gapokb,
                                        'tjbtn'             => $t_jbtn2,
                                        'tjenpek'           => $t_jenpek2,
                                        'uglobal'           => $global_baru,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1_baru,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2_baru,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $bonus,
                                        'trencana'          => $trencana_lama->num_rows() + $trencana_baru->num_rows(),
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana_baru->num_rows() + $tdkrencana_lama->num_rows(),
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang_lama->num_rows() + $pulang_baru->num_rows(),
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar_lama->num_rows() + $keluar_baru->num_rows(),
                                        'keluar2'           => $cnt_keluar_lama + $cnt_keluar_baru,
                                        'rp_keluar2'        => $pkeluar_baru + $pkeluar_lama,
                                        'premi'             => $bonus,
                                        'uang_transport'    => $uang_transport_baru + $uang_transport_lama,
                                        'uang_makan'        => $uang_makan_baru + $uang_makan_lama,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pphl1,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'netasr'            => $net_asr,
                                        'netto'             => $net,
                                        'pot_kopkar'        => $pot_kopkar,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_upahlog($datalog);
                                }
                                /* -----------------------END SAVE DATA TO DATABASE--------------------- */

                                /* --------- START LOAD DATA DETAIL TO TABLE--------- */
                                // $new_rows = 'A'.$new_baris;
                                $no = $no + 1;
                                $sheet->setCellValue('A' . $new_baris, $no . ".");
                                $sheet->setCellValue('B' . $new_baris, $nama_karyawan);
                                $sheet->getStyle('C' . $new_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                                $sheet->setCellValue('C' . $new_baris, $masker_tahun);
                                $sheet->setCellValue('D' . $new_baris, $sts_pph);
                                $sheet->setCellValue('E' . $new_baris, $gapokb);
                                $bgapok = $bgapok + $gapokb;
                                $sheet->setCellValue('F' . $new_baris, $t_jbtn2);
                                $bjbtn = $bjbtn + $t_jbtn2;
                                $sheet->setCellValue('G' . $new_baris, $t_jenpek2);
                                $bjenpek = $bjenpek + $t_jenpek2;
                                $sheet->setCellValue('H' . $new_baris, $t_masker2);
                                $bmasker = $bmasker + $t_masker2;
                                $sheet->setCellValue('I' . $new_baris, $global_baru);
                                $bglobal = $bglobal + $global_baru;
                                $sheet->setCellValue('J' . $new_baris, $slembur1);
                                $bshift1 = $bshift1 + $slembur1;
                                $sheet->setCellValue('K' . $new_baris, $slembur2);
                                $bshift2 = $bshift2 + $slembur2;
                                $sheet->setCellValue('L' . $new_baris, $slembur2 + $slembur1);
                                $btotshift = $btotshift + ($slembur2 + $slembur1);
                                $sheet->setCellValue('M' . $new_baris, $utot_shift);
                                $btotushift = $btotushift + $utot_shift;
                                $sheet->setCellValue('N' . $new_baris, $lembur1);
                                $blbr1 = $blbr1 + $lembur1;
                                $sheet->setCellValue('O' . $new_baris, $lembur2);
                                $blbr2 = $blbr2 + $lembur2;
                                $sheet->setCellValue('P' . $new_baris, $lembur3);
                                $blbr3 = $blbr3 + $lembur3;
                                $sheet->setCellValue('Q' . $new_baris, $lembur3 + $lembur2 + $lembur1);
                                $btotlbr = $btotlbr + ($lembur3 + $lembur2 + $lembur1);
                                $sheet->setCellValue('R' . $new_baris, round($tot_lbr));
                                $btotulbr = $btotulbr + $tot_lbr;
                                $sheet->setCellValue('S' . $new_baris, round($bonus));
                                $bpremi = $bpremi + $bonus;
                                $sheet->setCellValue('T' . $new_baris, $uang_transport);
                                $btrans = $btrans +  $uang_transport;
                                $sheet->setCellValue('U' . $new_baris, $uang_makan);
                                $bmak = $bmak + $uang_makan;
                                $sheet->setCellValue('V' . $new_baris, round($uasuransi));
                                $basr = $basr + $uasuransi;
                                $sheet->setCellValue('W' . $new_baris, round($pot_dplk));
                                $bpen = $bpen + $pot_dplk;
                                $sheet->setCellValue('X' . $new_baris, round($astek));
                                $bast = $bast + $astek;
                                $sheet->setCellValue('Y' . $new_baris, round($uspmi));
                                $bspmi = $bspmi + $uspmi;
                                $sheet->setCellValue('Z' . $new_baris, round($umangkir));
                                $bmang = $bmang + $umangkir;
                                $sheet->setCellValue('AA' . $new_baris, round($tot_pph));
                                $bpph = $bpph + $tot_pph;
                                $sheet->setCellValue('AB' . $new_baris, round($net_asr));
                                $bnetasr = $bnetasr + $net_asr;
                                $sheet->setCellValue('AC' . $new_baris, round($net));
                                $bnet = $bnet + $net;
                                $sheet->setCellValue('AD' . $new_baris, round($pot_kopkar));
                                $bpotkop = $bpotkop + $pot_kopkar;
                                $sheet->setCellValue('AE' . $new_baris, round($jml_terima));
                                $bterima = $bterima + $jml_terima;
                                $sheet->setCellValue('AF' . $new_baris, $nik);

                                /* --------- END LOAD DATA DETAIL TO TABLE--------- */
                            } // loop karyawan
                            // echo "</table>";
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            /* --------- START LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                            $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->getStyle('A' . $max_baris)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheet->setCellValue('A' . $max_baris, 'Sub Total');
                            $sheet->setCellValue('I' . $max_baris, $bglobal);
                            $sheet->setCellValue('J' . $max_baris, $bshift1);
                            $sheet->setCellValue('K' . $max_baris, $bshift2);
                            $sheet->setCellValue('L' . $max_baris, $btotshift);
                            $sheet->setCellValue('M' . $max_baris, $btotushift);
                            $sheet->setCellValue('N' . $max_baris, $blbr1);
                            $sheet->setCellValue('O' . $max_baris, $blbr2);
                            $sheet->setCellValue('P' . $max_baris, $blbr3);
                            $sheet->setCellValue('Q' . $max_baris, $btotlbr);
                            $sheet->setCellValue('R' . $max_baris, round($btotulbr));
                            $sheet->setCellValue('S' . $max_baris, round($bpremi));
                            $sheet->setCellValue('T' . $max_baris, $btrans);
                            $sheet->setCellValue('U' . $max_baris, $bmak);
                            $sheet->setCellValue('V' . $max_baris, round($basr));
                            $sheet->setCellValue('W' . $max_baris, round($bpen));
                            $sheet->setCellValue('X' . $max_baris, round($bast));
                            $sheet->setCellValue('Y' . $max_baris, round($bspmi));
                            $sheet->setCellValue('Z' . $max_baris, round($bmang));
                            $sheet->setCellValue('AA' . $max_baris, round($bpph));
                            $sheet->setCellValue('AB' . $max_baris, round($bnetasr));
                            $sheet->setCellValue('AC' . $max_baris, round($bnet));
                            $sheet->setCellValue('AD' . $max_baris, round($bpotkop));
                            $sheet->setCellValue('AE' . $max_baris, round($bterima));
                            $sheet->setCellValue('AF' . $max_baris, "");
                            /* --------- END LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                        } // karyawan > 0
                        /* --------- START CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        $pgapok = $pgapok + $bgapok;
                        $pjbtn = $pjbtn + $bjbtn;
                        $pjenpek = $pjenpek  + $bjenpek;
                        $pmasker = $pmasker + $bmasker;
                        $pglobal = $pglobal + $bglobal;
                        $pshift1 = $pshift1 + $bshift1;
                        $pshift2 = $pshift2 + $bshift2;
                        $ptotshift = $ptotshift + $btotshift;
                        $ptotushift = $ptotushift + $btotushift;
                        $plbr1 = $plbr1 + $blbr1;
                        $plbr2 = $plbr2 + $blbr2;
                        $plbr3 = $plbr3  + $blbr3;
                        $ptotlbr = $ptotlbr + $btotlbr;
                        $ptotulbr = $ptotulbr + $btotulbr;
                        $ppremi = $ppremi + $bpremi;
                        $ptrans = $ptrans + $btrans;
                        $pmak = $pmak  + $bmak;
                        $pasr = $pasr + $basr;
                        $ppen = $ppen + $bpen;
                        $past = $past  + $bast;
                        $ppspmi = $ppspmi + $bspmi;
                        $pmang = $pmang + $bmang;
                        $ppph = $ppph + $bpph;
                        $pnetasr = $pnetasr + $bnetasr;
                        $pnet = $pnet + $bnet;
                        $ppotkop = $ppotkop + $bpotkop;
                        $pterima = $pterima + $bterima;
                        /* --------- END CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        /* --------- START CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                        ${"total" . $b->recid_bag} = array(
                            "bagian"    => $b->indeks_hr,
                            "global"    => round($bglobal),
                            "bshift1"    => round($bshift1),
                            "bshift2"    => round($bshift2),
                            "btotshift"    => round($btotshift),
                            "btotushift"    => round($btotushift),
                            "blbr1"    => round($blbr1),
                            "blbr2"    => round($blbr2),
                            "blbr3"    => round($blbr3),
                            "btotlbr"    => round($btotlbr),
                            "btotulbr"    => round($btotulbr),
                            "bpremi"    => round($bpremi),
                            "btrans"    => round($btrans),
                            "bmak"    => round($bmak),
                            "basr"    => round($basr),
                            "bpen"    => round($bpen),
                            "bast"    => round($bast),
                            "bspmi"    => round($bspmi),
                            "bmang"    => round($bmang),
                            "bpph"    => round($bpph),
                            "bnetasr"    => round($bnetasr),
                            "nett"      => round($bnet),
                            "bpotkop"      => round($bpotkop),
                            "bterima"      => round($bterima),
                        );
                        array_push(${"rekap_" . $paygroup[$pyg]},  ${"total" . $b->recid_bag});
                        /* --------- END CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                    } // filter bagian
                } // filter department
                ${"totpaygroup" . $paygroup[$pyg]} = array(
                    "pglobal"    => round($pglobal),
                    "pshift1"    => round($pshift1),
                    "pshift2"    => round($pshift2),
                    "ptotshift"    => round($ptotshift),
                    "ptotushift"    => round($ptotushift),
                    "plbr1"    => round($plbr1),
                    "plbr2"    => round($plbr2),
                    "plbr3"    => round($plbr3),
                    "ptotlbr"    => round($ptotlbr),
                    "ptotulbr"    => round($ptotulbr),
                    "ppremi"    => round($ppremi),
                    "ptrans"    => round($ptrans),
                    "pmak"    => round($pmak),
                    "pasr"    => round($pasr),
                    "ppen"    => round($ppen),
                    "past"    => round($past),
                    "pspmi"    => round($ppspmi),
                    "pmang"    => round($pmang),
                    "ppph"    => round($ppph),
                    "pnetasr"    => round($pnetasr),
                    "pnet"      => round($pnet),
                    "ppotkop"      => round($ppotkop),
                    "pterima"      => round($pterima),
                );
                //   array_push( ${"totpaygroup" . $paygroup[$pyg]},  ${"tot_paygroup" .  $paygroup[$pyg]});
                // echo $paygroup[$pyg]." - ".$pnet."<br>";
                $tot_global = $tot_global + $pglobal;
                $tot_shift1 = $tot_shift1 + $pshift1;
                $tot_shift2 = $tot_shift2 + $pshift2;
                $tot_totshift = $tot_totshift + $ptotshift;
                $tot_ushift = $tot_ushift + $ptotushift;
                $tot_lbr1 = $tot_lbr1 + $plbr1;
                $tot_lbr2 = $tot_lbr2 + $plbr2;
                $tot_lbr3 = $tot_lbr3 + $plbr3;
                $tot_totlbr = $tot_totlbr + $ptotlbr;
                $tot_ulbr = $tot_ulbr + $ptotulbr;
                $tot_premi = $tot_premi + $ppremi;
                $tot_trans = $tot_trans + $ptrans;
                $tot_mak = $tot_mak + $pmak;
                $tot_asr = $tot_asr + $pasr;
                $tot_pensiun = $tot_pensiun + $ppen;
                $tot_astek = $tot_astek + $past;
                $tot_spmi = $tot_spmi + $ppspmi;
                $tot_mangkir = $tot_mangkir + $pmang;
                $tot_cpph = $tot_cpph + $ppph;
                $tot_netasr = $tot_netasr + $pnetasr;
                $tot_net = $tot_net + $pnet;
                $tot_potkop = $tot_potkop + $ppotkop;
                $tot_terima = $tot_terima + $pterima;
                // echo $tot_astek;
                /* --------- START LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $max_baris = $sheet->getHighestRow();
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                $sheet->getStyle('A' . $max_baris)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('A' . $max_baris, 'TOTAL');
                $sheet->setCellValue('I' . $max_baris, $pglobal);
                $sheet->setCellValue('J' . $max_baris, $pshift1);
                $sheet->setCellValue('K' . $max_baris, $pshift2);
                $sheet->setCellValue('L' . $max_baris, $ptotshift);
                $sheet->setCellValue('M' . $max_baris, $ptotushift);
                $sheet->setCellValue('N' . $max_baris, $plbr1);
                $sheet->setCellValue('O' . $max_baris, $plbr2);
                $sheet->setCellValue('P' . $max_baris, $plbr3);
                $sheet->setCellValue('Q' . $max_baris, $ptotlbr);
                $sheet->setCellValue('R' . $max_baris, round($ptotulbr));
                $sheet->setCellValue('S' . $max_baris, round($ppremi));
                $sheet->setCellValue('T' . $max_baris, $ptrans);
                $sheet->setCellValue('U' . $max_baris, $pmak);
                $sheet->setCellValue('V' . $max_baris, round($pasr));
                $sheet->setCellValue('W' . $max_baris, round($ppen));
                $sheet->setCellValue('X' . $max_baris, round($past));
                $sheet->setCellValue('Y' . $max_baris, round($ppspmi));
                $sheet->setCellValue('Z' . $max_baris, round($pmang));
                $sheet->setCellValue('AA' . $max_baris, round($ppph));
                $sheet->setCellValue('AB' . $max_baris, round($pnetasr));
                $sheet->setCellValue('AC' . $max_baris, round($pnet));
                $sheet->setCellValue('AD' . $max_baris, round($ppotkop));
                $sheet->setCellValue('AE' . $max_baris, round($pterima));
                $sheet->setCellValue('AF' . $max_baris, "");
                /* --------- END LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $akhir_baris = $max_baris;
                $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris . ":AE" . $akhir_baris)
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                /* --------- START FOOTER APPROVAL --------- */
                $tgl = date('d M Y');
                $max_baris = $max_baris + 2;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Diperiksa & Disetujui Oleh');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Mengetahui');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Cimahi, ' . $tgl);

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Finance');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'HC & GA Manager');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Pembuat Data Payroll');

                $max_baris = $max_baris + 3;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Kisty Riagustina');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Diah Nur Kusumawardani');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Mega Oktaviani');
                /* --------- END FOOTER APPROVAL --------- */
                $sheet->setBreak('A' . $max_baris, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
            } // loop paygroup

            /* --------- START FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL) --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji1');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji1");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran Gaji Karyawan Bulanan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Untuk Bulan : ' . $nama_bulan . ' ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Perhitungan Lembur Mulai Tgl ' . $tgl_mulai . ' s/d ' . $tgl_akhir);
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;

            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
            $sheet->setCellValue('C' . $max_baris, 'Gaji Per Bulan');

            $sheet->mergeCells('D' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('D' . $max_baris, 'Premi Shift');
            $sheet->getStyle('D' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('D' . $max_bariss, 'Jml Jam1');
            $sheet->getStyle('E' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('E' . $max_bariss, 'Jml Jam 2');
            $sheet->getStyle('F' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('F' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('G' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('G' . $max_bariss, 'Total Premi shift');
            $sheet->getStyle('H' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_baris, 'Lbr1');
            $sheet->getStyle('H' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('I' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('I' . $max_baris, 'Lbr2');
            $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('J' . $max_baris, 'Lbr3');

            $sheet->mergeCells('K' . $max_baris . ':L' . $max_baris);
            $sheet->setCellValue('K' . $max_baris, 'Jumlah Lembur');
            $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('K' . $max_bariss, 'Jml Jam');
            $sheet->setCellValue('L' . $max_bariss, 'Jml Upah');
            $sheet->mergeCells('M' . $max_baris . ':M' . $max_bariss);
            $sheet->getStyle('M' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('M' . $max_baris, 'Premi Hadir');
            $sheet->mergeCells('N' . $max_baris . ':N' . $max_bariss);
            $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('N' . $max_baris, 'Transport');
            $sheet->mergeCells('O' . $max_baris . ':O' . $max_bariss);
            $sheet->setCellValue('O' . $max_baris, 'Makan');
            $sheet->mergeCells('P' . $max_baris . ':P' . $max_bariss);
            $sheet->setCellValue('P' . $max_baris, 'Asuransi');

            $sheet->mergeCells('Q' . $max_baris . ':U' . $max_baris);
            $sheet->setCellValue('Q' . $max_baris, 'Potongan');
            $sheet->setCellValue('Q' . $max_bariss, 'Pensiun');
            $sheet->setCellValue('R' . $max_bariss, 'Astek');
            $sheet->setCellValue('S' . $max_bariss, 'SPMI');
            $sheet->setCellValue('T' . $max_bariss, 'Mangkir');
            $sheet->setCellValue('U' . $max_bariss, 'PPH21');

            $sheet->mergeCells('V' . $max_baris . ':Y' . $max_baris);
            $sheet->setCellValue('V' . $max_baris, 'Bersih');
            $sheet->setCellValue('V' . $max_bariss, 'Net + Asr');
            $sheet->setCellValue('W' . $max_bariss, 'Net');
            $sheet->setCellValue('X' . $max_bariss, 'Pot. Kopkar');
            $sheet->setCellValue('Y' . $max_bariss, 'Jml Terima');
            /* --------- END HEADER TABLE  --------- */
            $max_baris = $max_baris + 1;
            /* --------- START LOAD DATA TO TABLE  --------- */
            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                $no = 0;
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':W' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, $paygroup[$pg2]);
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                    $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["global"]);
                    $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift1"]);
                    $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift2"]);
                    $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotshift"]);
                    $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotushift"]);
                    $sheet->setCellValue('H' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr1"]);
                    $sheet->setCellValue('I' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr2"]);
                    $sheet->setCellValue('J' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr3"]);
                    $sheet->setCellValue('K' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotlbr"]);
                    $sheet->setCellValue('L' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotulbr"]);
                    $sheet->setCellValue('M' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpremi"]);
                    $sheet->setCellValue('N' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btrans"]);
                    $sheet->setCellValue('O' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmak"]);
                    $sheet->setCellValue('P' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["basr"]);
                    $sheet->setCellValue('Q' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpen"]);
                    $sheet->setCellValue('R' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bast"]);
                    $sheet->setCellValue('S' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bspmi"]);
                    $sheet->setCellValue('T' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmang"]);
                    $sheet->setCellValue('U' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpph"]);
                    $sheet->setCellValue('V' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bnetasr"]);
                    $sheet->setCellValue('W' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    $sheet->setCellValue('X' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpotkop"]);
                    $sheet->setCellValue('Y' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                }
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, "TOTAL " . $paygroup[$pg2]);
                $sheet->setCellValue('C' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pglobal"]);
                $sheet->setCellValue('D' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift1"]);
                $sheet->setCellValue('E' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift2"]);
                $sheet->setCellValue('F' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotshift"]);
                $sheet->setCellValue('G' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotushift"]);
                $sheet->setCellValue('H' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr1"]);
                $sheet->setCellValue('I' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr2"]);
                $sheet->setCellValue('J' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr3"]);
                $sheet->setCellValue('K' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotlbr"]);
                $sheet->setCellValue('L' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotulbr"]);
                $sheet->setCellValue('M' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppremi"]);
                $sheet->setCellValue('N' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptrans"]);
                $sheet->setCellValue('O' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmak"]);
                $sheet->setCellValue('P' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pasr"]);
                $sheet->setCellValue('Q' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppen"]);
                $sheet->setCellValue('R' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["past"]);
                $sheet->setCellValue('S' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pspmi"]);
                $sheet->setCellValue('T' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmang"]);
                $sheet->setCellValue('U' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppph"]);
                $sheet->setCellValue('V' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnetasr"]);
                $sheet->setCellValue('W' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnet"]);
                $sheet->setCellValue('X' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppotkop"]);
                $sheet->setCellValue('Y' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pterima"]);
            }
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, "GRAND TOTAL");
            $sheet->setCellValue('C' . $max_baris, $tot_global);
            $sheet->setCellValue('D' . $max_baris, $tot_shift1);
            $sheet->setCellValue('E' . $max_baris, $tot_shift2);
            $sheet->setCellValue('F' . $max_baris, $tot_totshift);
            $sheet->setCellValue('G' . $max_baris, $tot_ushift);
            $sheet->setCellValue('H' . $max_baris, $tot_lbr1);
            $sheet->setCellValue('I' . $max_baris, $tot_lbr2);
            $sheet->setCellValue('J' . $max_baris, $tot_lbr3);
            $sheet->setCellValue('K' . $max_baris, $tot_totlbr);
            $sheet->setCellValue('L' . $max_baris, $tot_ulbr);
            $sheet->setCellValue('M' . $max_baris, $tot_premi);
            $sheet->setCellValue('N' . $max_baris, $tot_trans);
            $sheet->setCellValue('O' . $max_baris, $tot_mak);
            $sheet->setCellValue('P' . $max_baris, $tot_asr);
            $sheet->setCellValue('Q' . $max_baris, $tot_pensiun);
            $sheet->setCellValue('R' . $max_baris, $tot_astek);
            $sheet->setCellValue('S' . $max_baris, $tot_spmi);
            $sheet->setCellValue('T' . $max_baris, $tot_mangkir);
            $sheet->setCellValue('U' . $max_baris, $tot_cpph);
            $sheet->setCellValue('V' . $max_baris, $tot_netasr);
            $sheet->setCellValue('W' . $max_baris, $tot_net);
            $sheet->setCellValue('X' . $max_baris, $tot_potkop);
            $sheet->setCellValue('Y' . $max_baris, round($tot_terima));
            /* --------- END LOAD DATA TO TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":Y" . $akhir_baris2)
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL)  --------- */

            /* --------- START FOR SHEET REKAPITULASI GAJI 2 --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji2');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji2");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran Gaji Karyawan Bulanan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Untuk Bulan : ' . $nama_bulan . ' ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Perhitungan Lembur Mulai Tgl ' . $tgl_mulai . ' s/d ' . $tgl_akhir);
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->mergeCells('C' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_baris, 'Kelompok Gaji');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_bariss, 'Direct');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('D' . $max_bariss, 'Indirect');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('E' . $max_bariss, 'Admin');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('F' . $max_bariss, 'Penjualan');
            $sheet->getStyle('F')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('G' . $max_baris . ':G' . $max_bariss);
            $sheet->getStyle('G')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('G' . $max_baris, 'Total');
            /* --------- END HEADER TABLE  --------- */

            /* --------- START LOAD DATA TO TABLE  --------- */
            $no = 0;
            // foreach ($paygroup->result() as $pg2) {

            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                // print_r(${"rekap_" . $pg2->pay_group});
                // echo ${"rekap_" . $pg2->pay_group}[0]["bagian"];
                $max_baris = $sheet->getHighestRow();
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    // echo $paygroup[$pg2]."<br>";
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    if ($paygroup[$pg2] == "Direct") {
                        // echo ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"];
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else if ($paygroup[$pg2] == "Indirect") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else if ($paygroup[$pg2] == "Admin") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    } else {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    }
                }
            }
            /* --------- END LOAD DATA TO TABLE  --------- */

            /* --------- START TOTAL TABLE  --------- */
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Total Pengupahan');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan Koprasi');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_potkop));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Jumlah Transfer');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Dana S.P.M.I');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_spmi));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan');
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Astek');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_astek));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pensiun');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_pensiun));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pph 21');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_cpph));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Mangkir');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_mangkir));

            /* --------- END TOTAL TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":G" . $akhir_baris2)
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI2  --------- */
            $writer = new Xlsx($spreadsheet);
            $filename = 'Gaji Karyawan Bulan ' . $nama_bulan . " " . $tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    public function transisi_upah_cek()
    {
        /* tipe 1 : simpan & download, tipe 2: download saja  */
        // $tgl_mulai = $this->input->post('tgl_mulai');
        // $tgl_akhir = $this->input->post('tgl_akhir');

        $tgl_mulai = "2023-12-19";
        $tgl_akhir = "2024-01-18";

        /* kalkulasi periode desember */
        $tahun_lama = date("Y", strtotime($tgl_mulai));
        $tgl_mulai_lama = $tgl_mulai;
        $tgl_akhir_lama = date("Y-m-t", strtotime($tgl_mulai_lama));

        /* kalkulasi periode januari */
        $tahun_baru = date("Y", strtotime($tgl_akhir));
        $tgl_mulai_baru = date("Y-m-01", strtotime($tgl_akhir));
        $tgl_akhir_baru = $tgl_akhir;

        echo "Periode Desember : " . $tgl_mulai_lama . " s.d " . $tgl_akhir_lama . " Tahun : " . $tahun_lama . "<br>";
        echo "Periode Januari : " . $tgl_mulai_baru . " s.d " . $tgl_akhir_baru . " Tahun : " . $tahun_baru . "<br>";

        // $tahun = $this->input->post('tahun');
        // $bulan = $this->input->post('bulan');

        $tahun = 2024;
        $bulan = 1;

        echo "<br>LAPORAN UPAH RAPEL <br>";
        echo "<table border='1'>
        <tr><td>NIK</td>
        <td>NAMA KARYAWAN</td>
        <td>MASKER</td>
        <td>STATUS</td>
        <td>POKOK LAMA</td>
        <td>GLOBAL LAMA</td>
        <td>POKOK</td>
        <td>TJBTN</td>
        <td>TJENPEK</td>
        <td>TMASKER</td>
        <td>GLOBAL</td>
        <td>S1</td>
        <td>S2</td>
        <td>TOT S</td>
        <td>PREMI S</td>
        <td>LEMBUR1 2023</td>
        <td>ULEMBUR1 2023</td>
        <td>LEMBUR2 2023</td>
        <td>ULEMBUR2 2023</td>
        <td>LEMBUR3 2023</td>
        <td>ULEMBUR3 2023</td>
        <td>TOT LBR 2023</td>
        <td>UTOT LBR 2023</td>
        
        <td>LEMBUR1 2024</td>
        <td>ULEMBUR1 2024</td>
        <td>LEMBUR2 2024</td>
        <td>ULEMBUR2 2024</td>
        <td>LEMBUR3 2024</td>
        <td>ULEMBUR3 2024</td>
        <td>TOT LBR 2024</td>
        <td>UTOT LBR 2024</td>
        <td>JML HADIR 2023</td>
        <td>PREMI 2023</td>
        <td>JML HADIR 2024</td>
        <td>PREMI 2024</td>
        <td>POTONGAN</td>
        <td>TRANSPORT</td>
        <td>MAKAN</td>
        <td>ASURANSI</td>
        <td>PENSIUN</td>
        <td>ASTEK</td>
        <td>SPMI</td>
        <td>MANGKIR</td>
        <td>BRUTO</td>
        <td>PTKP</td>
        <td>BJAB</td>
        <td>ASTEK</td>
        <td>TPOT</td>
        <td>PKP</td>
        <td>PPH21 - 1</td>
        <td>PPH21 - 2</td>
        <td>PPH21 - 3</td>
        <td>NET+ASS</td>
        <td>NETT</td>
        <td>POT KOP</td>
        <td>JML TERIMA</td>
        </tr>";

        /* -------- base masker = 1 ------------------*/
        //2023
        $rp_masker = $this->m_hris->param_upah_tmp_id(1);
        foreach ($rp_masker->result() as $r) {
            $uph_masker1 = $r->nilai;
        }
        //2024
        $rp_masker = $this->m_hris->param_upah_id(1);
        foreach ($rp_masker->result() as $r) {
            $uph_masker2 = $r->nilai;
        }

        /* --------  shift 1-2 ------------------*/
        //2024
        //shift1-2 = 8
        $slbr1 = $this->m_hris->param_upah_id(8);
        foreach ($slbr1->result() as $slbr1a) {
            $shift_lbr1_baru = $slbr1a->nilai;
        }

        //shift3 = 9
        $slbr2 = $this->m_hris->param_upah_id(9);
        foreach ($slbr2->result() as $slbr2a) {
            $shift_lbr2_baru = $slbr2a->nilai;
        }

        //2023
        //shift1-2 = 8
        $slbr1 = $this->m_hris->param_upah_tmp_id(8);
        foreach ($slbr1->result() as $slbr1a) {
            $shift_lbr1_lama = $slbr1a->nilai;
        }

        //shift3 = 9
        $slbr2 = $this->m_hris->param_upah_tmp_id(9);
        foreach ($slbr2->result() as $slbr2a) {
            $shift_lbr2_lama = $slbr2a->nilai;
        }

        /* -------- pengali lembur 1 ------------------*/
        //2023
        $klbr1 = $this->m_hris->param_upah_tmp_id(11);
        foreach ($klbr1->result() as $klbr1a) {
            $kali_lembur1_lama = $klbr1a->nilai;
        }

        //pengali lembur 2
        $klbr2 = $this->m_hris->param_upah_tmp_id(12);
        foreach ($klbr2->result() as $klbr2a) {
            $kali_lembur2_lama = $klbr2a->nilai;
        }

        //pengali lembur 3
        $klbr3 = $this->m_hris->param_upah_tmp_id(13);
        foreach ($klbr3->result() as $klbr3a) {
            $kali_lembur3_lama = $klbr3a->nilai;
        }

        //2024
        $klbr1 = $this->m_hris->param_upah_id(11);
        foreach ($klbr1->result() as $klbr1a) {
            $kali_lembur1_baru = $klbr1a->nilai;
        }

        //pengali lembur 2
        $klbr2 = $this->m_hris->param_upah_id(12);
        foreach ($klbr2->result() as $klbr2a) {
            $kali_lembur2_baru = $klbr2a->nilai;
        }

        //pengali lembur 3
        $klbr3 = $this->m_hris->param_upah_id(13);
        foreach ($klbr3->result() as $klbr3a) {
            $kali_lembur3_baru = $klbr3a->nilai;
        }
        /* --------  pembagi lembur = 10 ------------------*/
        //2023
        $plbr = $this->m_hris->param_upah_tmp_id(10);
        foreach ($plbr->result() as $r) {
            $bagi_lembur_lama = $r->nilai;
        }

        //2024
        $plbr = $this->m_hris->param_upah_id(10);
        foreach ($plbr->result() as $r) {
            $bagi_lembur_baru = $r->nilai;
        }

        $recid_karyawan = 906; // hary kurnia
        $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and recid_karyawan = $recid_karyawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        /* and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan */

        foreach ($karyawan->result() as $k) {
            $recid_karyawan = $k->recid_karyawan;
            $nik = $k->nik;
            $nama_karyawan = $k->nama_karyawan;
            $bagian = $k->indeks_hr;
            $jabatan = $k->indeks_jabatan;
            $t_jbtn = $k->t_jabatan;
            $tingkatan = $k->tingkatan;
            $t_prestasi = $k->t_prestasi;
            $t_jenpek = $k->t_jen_pek;
            $penempatan = $k->penempatan;
            // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
            $trakhir = date("Y-m-t", strtotime($tgl_akhir));
            $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
            $masker_tahun = $diff->format('%y');
            $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;
            $sts_jabatan = $k->sts_jabatan;

            echo "tingkatan : " . $tingkatan . "<br>";
            echo "masker tahun : " . $masker_tahun . "<br>";


            if ($tingkatan == 1) {
                if ($penempatan == 'Jakarta') {
                    //2023
                    $gapok_lama = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_lama);
                    foreach ($gapok_lama->result() as $gp1) {
                        if ($sts_jabatan == "Advisor") {
                            $gapokl = (80 / 100) * $gp1->nilai;
                        } else {
                            $gapokl = $gp1->nilai;
                        }
                    }
                    //2024
                    $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_baru);
                    foreach ($gapok_baru->result() as $gp2) {
                        if ($sts_jabatan == "Advisor") {
                            $gapokb = (80 / 100) * $gp2->nilai;
                        } else {
                            $gapokb = $gp2->nilai;
                        }
                    }
                } else {
                    if ($sts_jabatan == "Advisor") {
                        /* gapok advisor = 80% dari gapok 2024 */
                        $gapokl = 0;
                        $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, 2024);
                        foreach ($gapok_baru->result() as $gp2) {
                            if ($sts_jabatan == "Advisor") {
                                $gapokb = (80 / 100) * $gp2->nilai;
                            } else {
                                $gapokb = $gp2->nilai;
                            }
                        }
                    } else {
                        //2023
                        $gapok_lama = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_lama);
                        foreach ($gapok_lama->result() as $gp1) {
                            if ($sts_jabatan == "Advisor") {
                                $gapokl = (80 / 100) * $gp1->nilai;
                            } else {
                                $gapokl = $gp1->nilai;
                            }
                        }
                        //2024
                        $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_baru);
                        foreach ($gapok_baru->result() as $gp2) {
                            if ($sts_jabatan == "Advisor") {
                                $gapokb = (80 / 100) * $gp2->nilai;
                            } else {
                                $gapokb = $gp2->nilai;
                            }
                        }
                    }
                }
            } else {
                if ($sts_jabatan == "Advisor") {
                    $gapokl = 0;
                    $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, 2024);
                    foreach ($gapok_baru->result() as $gp2) {
                        if ($sts_jabatan == "Advisor") {
                            $gapokb = (80 / 100) * $gp2->nilai;
                        } else {
                            $gapokb = $gp2->nilai;
                        }
                    }
                } else {
                    //2023
                    $gapok_lama = $this->m_hris->gapok($tingkatan, $tahun_lama);
                    foreach ($gapok_lama->result() as $gp1) {
                        $gapokl = $gp1->nilai;
                    }
                    //2024
                    $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun_baru);
                    foreach ($gapok_baru->result() as $gp2) {
                        $gapokb = $gp2->nilai;
                    }
                }
            }

            if ($k->tingkatan == 1) {
                //operator tunjangan jabatan = 0
                $t_jbtn1 = 0;
                $t_jbtn2 = 0;
                // cek tunjangan jenis pekerjaan by bagian
                $tjenpek1 = $this->m_hris->tjenpek($k->recid_bag, $tahun_lama);
                if ($tjenpek1->num_rows() > 0) {
                    foreach ($tjenpek1->result() as $t1) {
                        $t_jenpek1 = $t1->nilai;
                    }
                } else {
                    $t_jenpek1 = 0;
                }

                $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun_baru);
                if ($tjenpek2->num_rows() > 0) {
                    foreach ($tjenpek2->result() as $t2) {
                        $t_jenpek2 = $t2->nilai;
                    }
                } else {
                    $t_jenpek2 = 0;
                }

                echo "t_jbtn : $t_jbtn2<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";
            } else {
                //wakaru ke atas, t_jenpek = 0
                $t_jenpek1 = 0;
                $t_jenpek2 = 0;
                //cek tunjangan jabatannya
                if ($sts_jabatan == "Advisor") {
                    //2024
                    $t_jbtn1 = 0;
                    $tjbtn2 = $this->m_hris->tjabatan($tingkatan, 2024);
                    foreach ($tjbtn2->result() as $t2) {
                        $t_jbtn2 = (80 / 100) * $t2->nilai;
                    }
                } else {
                    //2023
                    $tjbtn1 = $this->m_hris->tjabatan($tingkatan, $tahun_lama);
                    foreach ($tjbtn1->result() as $t1) {
                        $t_jbtn1 = $t1->nilai;
                    }
                    //2024
                    $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun_baru);
                    foreach ($tjbtn2->result() as $t2) {
                        $t_jbtn2 = $t2->nilai;
                    }
                }
            }
            $t_masker1 = $masker_tahun * $uph_masker1; //2023
            $t_masker2 = $masker_tahun * $uph_masker2; //2024
            $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
            $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
            // if ($sts_jabatan == "Advisor") {
            //     $global_lama = ((80 / 100) * ($gapokl + $t_jbtn1)) + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
            //     $global_baru = ((80 / 100) * ($gapokb + $t_jbtn2)) + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
            // } else {
            //     $global_lama = $gapokl + $t_jbtn1 + $t_prestasi + $t_jenpek1 + $t_masker1; // 2023
            //     $global_baru = $gapokb + $t_jbtn2 + $t_prestasi + $t_jenpek2 + $t_masker2; // 2024
            // }


            /* SHIFT */
            //2023
            $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 14);
            $s1_lama = $shift1->num_rows();
            $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 15);
            $s2_lama = $shift2->num_rows();
            $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 16);
            $s3_lama = $shift3->num_rows();

            $slembur1_lama = $s1_lama + $s2_lama;
            $uslembur1_lama = $slembur1_lama * $shift_lbr1_lama;
            $slembur2_lama = $s3_lama;
            $uslembur2_lama = $slembur2_lama * $shift_lbr2_lama;
            $utot_shift_lama = $uslembur1_lama + $uslembur2_lama;

            //2024
            $shift1 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 14);
            $s1_baru = $shift1->num_rows();
            $shift2 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 15);
            $s2_baru = $shift2->num_rows();
            $shift3 = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 16);
            $s3_baru = $shift3->num_rows();

            $slembur1_baru = $s1_baru + $s2_baru;
            $uslembur1_baru = $slembur1_baru * $shift_lbr1_baru;
            $slembur2_baru = $s3_baru;
            $uslembur2_baru = $slembur2_baru * $shift_lbr2_baru;
            $utot_shift_baru = $uslembur1_baru + $uslembur2_baru;

            $slembur1 = $slembur1_baru + $slembur1_lama;
            $uslembur1 = $uslembur1_baru +  $uslembur1_lama;
            $slembur2 = $slembur2_baru + $slembur2_lama;
            $uslembur2 = $uslembur2_baru + $uslembur2_lama;
            $utot_shift = $utot_shift_baru + $utot_shift_lama;

            //2023
            $lemburan_lama = $this->m_lembur->karyawan_lembur_report($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            $tot_lbr_lama = 0;
            foreach ($lemburan_lama->result() as $l) {
                $lembur1_lama = round($l->lembur1, 1);
                $lembur2_lama = round($l->lembur2, 1);
                $lembur3_lama = round($l->lembur3, 1);
            }

            //2024
            $lemburan_baru = $this->m_lembur->karyawan_lembur_report($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            $tot_lbr_baru = 0;
            foreach ($lemburan_baru->result() as $l) {
                $lembur1_baru = round($l->lembur1, 1);
                $lembur2_baru = round($l->lembur2, 1);
                $lembur3_baru = round($l->lembur3, 1);
            }

            // Cek adjustment lemburan 2023
            $atrans_lama = 0;
            $amakan_lama = 0;
            $adj_upah_lama = $this->m_lembur->cek_adjust($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            if ($adj_upah_lama->num_rows() > 0) {
                foreach ($adj_upah_lama->result() as $al) {
                    $lembur1_lama = $lembur1_lama + $al->jam_lbr1;
                    $lembur2_lama = $lembur2_lama + $al->jam_lbr2;
                    $lembur3_lama = $lembur3_lama + $al->jam_lbr3;
                    $atrans_lama = $al->jml_transport;
                    $amakan_lama = $al->jml_makan;
                }
            } else {
                $atrans_lama = 0;
                $amakan_lama = 0;
            }

            // Cek adjustment lemburan 2024
            $atrans_baru = 0;
            $amakan_baru = 0;
            $adj_upah_baru = $this->m_lembur->cek_adjust($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            if ($adj_upah_baru->num_rows() > 0) {
                foreach ($adj_upah_baru->result() as $al) {
                    $lembur1_baru = $lembur1_baru + $al->jam_lbr1;
                    $lembur2_baru = $lembur2_baru + $al->jam_lbr2;
                    $lembur3_baru = $lembur3_baru + $al->jam_lbr3;
                    $atrans_baru = $al->jml_transport;
                    $amakan_baru = $al->jml_makan;
                }
            } else {
                $atrans_baru = 0;
                $amakan_baru = 0;
            }

            $lembur1 = $lembur1_baru + $lembur1_lama;
            $lembur2 = $lembur2_baru + $lembur2_lama;
            $lembur3 = $lembur3_baru + $lembur3_lama;
            $atrans = $atrans_baru + $atrans_lama;
            $amakan = $amakan_baru + $amakan_lama;

            //2023
            $uph_lbr1_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur1_lama);
            $lbr1_lama = $uph_lbr1_lama * $lembur1_lama;
            $uph_lbr2_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur2_lama);
            $lbr2_lama = $uph_lbr2_lama * $lembur2_lama;
            $uph_lbr3_lama = round(($global_lama / $bagi_lembur_lama) * $kali_lembur3_lama);
            $lbr3_lama = $uph_lbr3_lama * $lembur3_lama;
            $tot_lbr_lama = $lbr1_lama + $lbr2_lama + $lbr3_lama;


            //2024
            $uph_lbr1_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur1_baru);
            $lbr1_baru = $uph_lbr1_baru * $lembur1_baru;
            $uph_lbr2_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur2_baru);
            $lbr2_baru = $uph_lbr2_baru * $lembur2_baru;
            $uph_lbr3_baru = round(($global_baru / $bagi_lembur_baru) * $kali_lembur3_baru);
            $lbr3_baru = $uph_lbr3_baru * $lembur3_baru;
            $tot_lbr_baru = $lbr1_baru + $lbr2_baru + $lbr3_baru;

            $uph_lbr1 = $uph_lbr1_lama + $uph_lbr1_baru;
            $lbr1 = $lbr1_lama + $lbr1_baru;
            $uph_lbr2 = $uph_lbr2_lama + $uph_lbr2_baru;
            $lbr2 =  $lbr2_lama +  $lbr2_baru;
            $uph_lbr3 = $uph_lbr3_lama + $uph_lbr3_baru;
            $lbr3 = $lbr3_lama + $lbr3_baru;
            $tot_lbr = $tot_lbr_lama + $tot_lbr_baru;


            /* START PREMI */
            $tingkatan = $k->tingkatan;
            if ($tingkatan >= 1 and $tingkatan < 4) {
                //param premi hadir operator - karu, id = 2
                //2023
                $ph = $this->m_hris->param_upah_tmp_id(2);
                foreach ($ph->result() as $p) {
                    $premi_lama = $p->nilai;
                }

                //2024
                $ph = $this->m_hris->param_upah_id(2);
                foreach ($ph->result() as $p) {
                    $premi_baru = $p->nilai;
                }
            } else if ($tingkatan >= 4 and $tingkatan <= 5) {
                //param premi hadir wakasi - kasi, id = 3
                //2023
                $ph = $this->m_hris->param_upah_tmp_id(3);
                foreach ($ph->result() as $p) {
                    $premi_lama = $p->nilai;
                }
                //2024
                $ph = $this->m_hris->param_upah_id(3);
                foreach ($ph->result() as $p) {
                    $premi_baru = $p->nilai;
                }
            } else {
                $premi_lama = 0;
                $premi_baru = 0;
            }

            echo "premi : " . $premi_lama . " - " . $premi_baru . "<br>";

            //2023
            $total_hadir_lama = $this->m_absenbarcode->hitung_kerja($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            $jml_hadir_lama = $total_hadir_lama->num_rows();
            $upah_hadir_lama = $jml_hadir_lama * $premi_lama;

            //2024
            $total_hadir_baru = $this->m_absenbarcode->hitung_kerja($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            $jml_hadir_baru = $total_hadir_baru->num_rows();
            $upah_hadir_baru = $jml_hadir_baru * $premi_baru;
            $jml_hadir = $jml_hadir_lama + $jml_hadir_baru;
            $upah_hadir = $upah_hadir_lama + $upah_hadir_baru;

            // potong terlambat terencana
            $pr = $this->m_hris->param_upah_id(4);
            foreach ($pr->result() as $pr1) {
                $ppersenpr = $pr1->nilai;
            }

            // potong terlambat tidak terencana
            $ptr = $this->m_hris->param_upah_id(5);
            foreach ($ptr->result() as $ptr1) {
                $ppersenptr = $ptr1->nilai;
            }

            // potong izin keluar > 2 jam
            $pk = $this->m_hris->param_upah_id(6);
            foreach ($pk->result() as $pk1) {
                $ppersenpk = $pk1->nilai;
            }

            // potong pulang cepat
            $pp = $this->m_hris->param_upah_id(7);
            foreach ($pp->result() as $pp1) {
                $ppersenpp = $pp1->nilai;
            }
            //2023
            $trencana_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Terlambat Terencana');
            $prencana_lama = $trencana_lama->num_rows() * ($premi_lama * ($ppersenpr / 100));

            //2024
            $trencana_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Terlambat Terencana');
            $prencana_baru = $trencana_baru->num_rows() * ($premi_baru * ($ppersenpr / 100));

            $prencana = $prencana_lama + $prencana_baru;

            //2023
            $tdkrencana_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Terlambat Tidak Terencana');
            $ptdrencana_lama = $tdkrencana_lama->num_rows() * ($premi_lama * ($ppersenptr / 100));

            //2024
            $tdkrencana_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Terlambat Tidak Terencana');
            $ptdrencana_baru = $tdkrencana_baru->num_rows() * ($premi_baru * ($ppersenptr / 100));

            $ptdrencana = $ptdrencana_baru + $ptdrencana_lama;

            //2023
            $pulang_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Pulang');
            $ppulang_lama = $pulang_lama->num_rows() * ($premi_lama * ($ppersenpp / 100));

            //2024
            $pulang_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Pulang');
            $ppulang_baru = $pulang_baru->num_rows() * ($premi_baru * ($ppersenpp / 100));

            $ppulang = $ppulang_baru + $ppulang_lama;

            //2023
            $keluar_lama = $this->m_absenbarcode->hitung_izin($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan, 'Keluar');
            $cnt_keluar_lama = 0;
            foreach ($keluar_lama->result() as $i) {
                if ($i->over_durasi >= 2) {
                    $cnt_keluar_lama = $cnt_keluar_lama + 1;
                }
            }
            $pkeluar_lama = $cnt_keluar_lama * ($premi_lama * ($ppersenpk / 100));

            //2024
            $keluar_baru = $this->m_absenbarcode->hitung_izin($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan, 'Keluar');
            $cnt_keluar_baru = 0;
            foreach ($keluar_baru->result() as $i) {
                if ($i->over_durasi >= 2) {
                    $cnt_keluar_baru = $cnt_keluar_baru + 1;
                }
            }
            $pkeluar_baru = $cnt_keluar_baru * ($premi_baru * ($ppersenpk / 100));

            echo "potongan keluar : " . $pkeluar_lama . " + " . $pkeluar_baru . "<br>";
            echo "potongan pulang : " . $ppulang_lama . " + " . $ppulang_baru . "<br>";
            echo "potongan terencana : " . $prencana_lama . " + " . $prencana_baru . "<br>";
            echo "potongan tidak terencana : " . $ptdrencana_lama . " + " . $ptdrencana_baru . "<br>";

            //2023
            $bonus_lama = $upah_hadir_lama - ($pkeluar_lama + $ppulang_lama + $ptdrencana_lama + $prencana_lama);
            $bonus_baru = $upah_hadir_baru - ($pkeluar_baru + $ppulang_baru + $ptdrencana_baru + $prencana_baru);

            $bonus = round($bonus_lama + $bonus_baru);
            $potongan = ($pkeluar_lama + $ppulang_lama + $ptdrencana_lama + $prencana_lama) + ($pkeluar_baru + $ppulang_baru + $ptdrencana_baru + $prencana_baru);
            /* END PREMI */

            /* UANG TRANSPORT */
            // transport lembur holiday
            //2023
            $ut_lama = $this->m_hris->param_upah_tmp_id(14);
            foreach ($ut_lama->result() as $ut1) {
                $utrans2_lama = $ut1->nilai;
            }
            // transport lembur terusan
            $ut_lama = $this->m_hris->param_upah_tmp_id(39);
            foreach ($ut_lama->result() as $ut1) {
                $utrans1_lama = $ut1->nilai;
            }

            //2024
            $ut_baru = $this->m_hris->param_upah_id(14);
            foreach ($ut_baru->result() as $ut1) {
                $utrans2_baru = $ut1->nilai;
            }
            // transport lembur terusan
            $ut_baru = $this->m_hris->param_upah_id(39);
            foreach ($ut_baru->result() as $ut1) {
                $utrans1_baru = $ut1->nilai;
            }

            // transport lembur terusan
            //2023
            $transport_lama = $this->m_lembur->transport_lembur_karyawan($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            $trsp_lama = $transport_lama->num_rows();
            $transport2_lama = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            $trsp2_lama = $transport2_lama->num_rows();

            //2024
            $transport_baru = $this->m_lembur->transport_lembur_karyawan($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            $trsp_baru = $transport_baru->num_rows();
            $transport2_baru = $this->m_lembur->transport_lembur_karyawan1($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            $trsp2_baru = $transport2_baru->num_rows();

            // transport shift1,2,3
            //2023
            $shift1_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 14);
            $s1_lama = $shift1_lama->num_rows();
            $shift2_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 15);
            $s2_lama = $shift2_lama->num_rows();
            $shift3_lama = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_lama, $tgl_akhir_lama, 16);
            $s3_lama = $shift3_lama->num_rows();
            $tot_shift_lama = $s1_lama + $s2_lama + $s3_lama;
            $uang_transport1_lama = $trsp_lama * $utrans1_lama;
            $uang_transport2_lama = ($trsp2_lama + $tot_shift_lama) * $utrans2_lama;
            $uang_transport_adj_lama = $atrans_lama * $utrans2_lama;
            $uang_transport_lama = $uang_transport1_lama + $uang_transport2_lama + $uang_transport_adj_lama;

            //2024
            $shift1_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 14);
            $s1_baru = $shift1_baru->num_rows();
            $shift2_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 15);
            $s2_baru = $shift2_baru->num_rows();
            $shift3_baru = $this->m_absenbarcode->shift_karyawan_periode($recid_karyawan, $tgl_mulai_baru, $tgl_akhir_baru, 16);
            $s3_baru = $shift3_baru->num_rows();
            $tot_shift_baru = $s1_baru + $s2_baru + $s3_baru;
            $uang_transport1_baru = $trsp_baru * $utrans1_baru;
            $uang_transport2_baru = ($trsp2_baru + $tot_shift_baru) * $utrans2_baru;
            $uang_transport_adj_baru = $atrans_baru * $utrans2_baru;
            $uang_transport_baru = $uang_transport1_baru + $uang_transport2_baru + $uang_transport_adj_baru;
            // $uang_transport = $uang_transport1 + $uang_transport2;

            $uang_transport = $uang_transport_lama + $uang_transport_baru;
            /* END UANG TRANSPORT */

            /* START UANG MAKAN */
            // uang makan
            //2023
            $um_lama = $this->m_hris->param_upah_tmp_id(15);
            foreach ($um_lama->result() as $um1) {
                $umakan_lama = $um1->nilai;
            }
            $uang_makan_lama = $s3_lama * $umakan_lama;

            //2024
            $um_baru = $this->m_hris->param_upah_id(15);
            foreach ($um_baru->result() as $um1) {
                $umakan_baru = $um1->nilai;
            }
            $uang_makan_baru = $s3_baru * $umakan_baru;

            // makan lembur
            //2023
            $mkn_lama = $this->m_lembur->makan_lembur($tgl_mulai_lama, $tgl_akhir_lama, $recid_karyawan);
            // $makaan = $mkn->num_rows();
            $makaan_lama = ($mkn_lama->num_rows()) + $amakan_lama;
            $makan_lama = $makaan_lama * $umakan_lama;
            $uang_makan_lama = $uang_makan_lama + $makan_lama;

            //2024
            $mkn_baru = $this->m_lembur->makan_lembur($tgl_mulai_baru, $tgl_akhir_baru, $recid_karyawan);
            // $makaan = $mkn->num_rows();
            $makaan_baru = ($mkn_baru->num_rows()) + $amakan_baru;
            $makan_baru = $makaan_baru * $umakan_baru;
            $uang_makan_baru = $uang_makan_baru + $makan_baru;

            $uang_makan = $uang_makan_lama + $uang_makan_baru;

            // /* START ADJUSTMENT */
            // $adj_makan = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
            // if($adj_makan->num_rows() > 0)
            // {
            //     foreach ($adj_makan->result() as $am) {
            //         $uang_makan = $uang_makan + $makan + ($am->jml_makan * $umakan);
            //     }
            // }
            /* END ADJUSTMENT */

            // $uang_makan = 13000;
            /* END UANG MAKAN */
            /* START ASTEK */
            // astek jp
            if ($k->cek_bpjs_tk == '1') {
                $ajp = $this->m_hris->param_upah_id(19);
                foreach ($ajp->result() as $ajp1) {
                    $asuransi_jp = $ajp1->nilai;
                }

                // astek jht
                $ajht = $this->m_hris->param_upah_id(20);
                foreach ($ajht->result() as $ajht1) {
                    $asuransi_jht = $ajht1->nilai;
                }
            } else {
                $asuransi_jp = 0;
                $asuransi_jht = 0;
            }

            //astek bpjs kesehatan
            if ($k->cek_bpjs_kes == '1') {
                $abkes = $this->m_hris->param_upah_id(21);
                foreach ($abkes->result() as $abkes1) {
                    $asuransi_bkes = $abkes1->nilai;
                }
            } else {
                $asuransi_bkes = 0;
            }

            $kali_astek = $asuransi_jp + $asuransi_jht + $asuransi_bkes;
            $astek = $global_baru * ($kali_astek / 100);
            /* END ASTEK */

            /* START ASURANSI PERUSAHAAN */
            if ($k->cek_bpjs_tk == '1') {
                //persentase jkm
                $jkm = $this->m_hris->param_upah_id(16);
                foreach ($jkm->result() as $jkm1) {
                    $ujkm = $jkm1->nilai;
                }

                // persentase jkk
                $jkk = $this->m_hris->param_upah_id(17);
                foreach ($jkk->result() as $jkk1) {
                    $ujkk = $jkk1->nilai;
                }
            } else {
                $ujkm = 0;
                $ujkk = 0;
            }

            if ($k->cek_bpjs_kes == '1') {
                //persentase bpjs kesehatan
                $bpjs = $this->m_hris->param_upah_id(18);
                foreach ($bpjs->result() as $bpjs1) {
                    $ubpjs = $bpjs1->nilai;
                }
            } else {
                $ubpjs = 0;
            }


            $per_asr = $ujkm + $ujkk + $ubpjs;
            $uasuransi = $global_baru * ($per_asr / 100);
            /* END ASURANSI PERUSAHAAN */

            /* START PPH21 */
            $bruto = $global_baru + $tot_lbr + $utot_shift + ($upah_hadir - ($potongan)) + $uang_transport + $uang_makan + $uasuransi;

            /* START PENSIUN */
            if ($k->pensiun == "Ya") {
                // persentase dplk
                $dplk = $this->m_hris->param_upah_id(22);
                foreach ($dplk->result() as $dplk1) {
                    $dplk_aia = $dplk1->nilai;
                }
                $pot_dplk = $global_baru * ($dplk_aia / 100);
            } else {
                $pot_dplk = 0;
            }
            /* END PENSIUN */

            /* START SPMI */
            if ($k->lspmi == "Ya") {
                // spmi
                $spmi = $this->m_hris->param_upah_id(23);
                foreach ($spmi->result() as $spmi1) {
                    $pspmi = $spmi1->nilai;
                }
                $uspmi = ($gapokb * ($pspmi / 100)) + 5000;
            } else {
                $uspmi = 0;
            }
            /* END SPMI */

            /* START MANGKIR */
            $mkr = $this->m_absenbarcode->hitung_mangkir($tgl_mulai, $tgl_akhir, $recid_karyawan);
            // potongan mangir
            $mangkir = $this->m_hris->param_upah_id(24);
            foreach ($mangkir->result() as $mangkir1) {
                $pmangkir = $mangkir1->nilai;
            }
            $umangkir = $mkr->num_rows() * (($pmangkir / 100) * $global_baru);
            /* END MAGKIR */

            /* START PTKP */
            $sts_kawin = $k->sts_penunjang;
            if ($sts_kawin == "K0") {
                // ptkp K0
                $ptkp = $this->m_hris->param_upah_id(28);
            } else if ($sts_kawin == "K1") {
                // ptkp K1
                $ptkp = $this->m_hris->param_upah_id(29);
            } else if ($sts_kawin == "K2") {
                $ptkp = $this->m_hris->param_upah_id(30);
            } else if ($sts_kawin == "K3") {
                // ptkp K3
                $ptkp = $this->m_hris->param_upah_id(31);
            } else if ($sts_kawin == "TK1") {
                // ptkp TK1
                $ptkp = $this->m_hris->param_upah_id(45);
            } else if ($sts_kawin == "TK2") {
                // ptkp TK2
                $ptkp = $this->m_hris->param_upah_id(46);
            } else if ($sts_kawin == "TK3") {
                // ptkp TK3
                $ptkp = $this->m_hris->param_upah_id(47);
            } else {
                // ptkp TK
                $ptkp = $this->m_hris->param_upah_id(27);
            }
            foreach ($ptkp->result() as $ptkp1) {
                $uptkp = $ptkp1->nilai;
            }
            /* END PTKP */

            /* START BIAYA JABATAN */
            //biaya jabatan
            $bjab = $this->m_hris->param_upah_id(25);
            foreach ($bjab->result() as $bjab1) {
                $b_jabatan = $bjab1->nilai;
            }

            //max biaya jabatan
            $max_jbtn = $this->m_hris->param_upah_id(26);
            foreach ($max_jbtn->result() as $max_jbtn1) {
                $max_jab = $max_jbtn1->nilai;
            }

            $biaya_jabatan = $bruto * ($b_jabatan / 100);
            if ($biaya_jabatan > $max_jab) {
                $biaya_jabatan = $max_jab;
            }
            /* END BIAYA JABATAN */

            $astek_pph = $asuransi_jp + $asuransi_jht;
            $uapph = $global_baru * ($astek_pph / 100);
            $tot_pot = $uptkp + $biaya_jabatan + $uapph + $pot_dplk;
            $pkp = $bruto - $tot_pot;
            /* START LAPISAN PKP */
            // lapisan pkp per bulan
            $lvlpkp = [40, 41, 42];
            $lap_pkp1 = 0;
            $lap_pkp2 = 0;
            $lap_pkp3 = 0;
            for ($i = 0; $i < count($lvlpkp); $i++) {

                $lapisan = $this->m_hris->param_upah_id($lvlpkp[$i]);
                foreach ($lapisan->result() as $hasil) {
                    $x = $i + 1;
                    ${"lap_pkp" . $x} = $hasil->nilai;
                    // echo ${"lap_pkp" . $x};
                }
            }

            // echo $lap_pkp1;
            $cek_npwp = $k->cek_npwp;
            if ($cek_npwp == '1') {
                $perpkp = [32, 33, 34];
            } else {
                $perpkp = [44, 33, 34];
            }
            $per_pkp1 = 0;
            $per_pkp2 = 0;
            $per_pkp3 = 0;
            for ($x = 0; $x < count($perpkp); $x++) {
                $persen = $this->m_hris->param_upah_id($perpkp[$x]);
                foreach ($persen->result() as $hasil2) {
                    ${"per_pkp" . ($x + 1)} = $hasil2->nilai;
                }
            }

            // echo $per_pkp[$x+1]."<br>";
            // echo ($lap_pkp1)."<br>";
            if ($pkp >= 0 and $pkp <= $lap_pkp1) {
                if ($pkp >= $lap_pkp1) {
                    $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                } else {
                    $pphl1 = round($pkp * ($per_pkp1 / 100));
                }
                // echo "Lapisan 1 : ".$pphl1."<br>";
                $pphl2 = 0;
                $pphl3 = 0;
            } else if ($pkp > $lap_pkp1 and $pkp <= $lap_pkp2) {
                // echo "lapisan 2<br>";
                if ($pkp >= $lap_pkp1) {
                    $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                } else {
                    $pphl1 = round($pkp * ($per_pkp1 / 100));
                }
                // echo "Lapisan 1 : ".$pphl1."<br>";
                $pkp = $pkp - $lap_pkp1;
                if ($pkp >= $lap_pkp2) {
                    $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                } else {
                    $pphl2 = round($pkp * ($per_pkp2 / 100));
                }
                // echo "Lapisan 2 : ".$pphl2."<br>";
                $pphl3 = 0;
            } else if ($pkp > $lap_pkp2 and $pkp <= $lap_pkp3) {
                // echo "lapisan 3";
                if ($pkp >= $lap_pkp1) {
                    $pphl1 = round($lap_pkp1 * ($per_pkp1 / 100));
                } else {
                    $pphl1 = round($pkp * ($per_pkp1 / 100));
                }
                // echo "Lapisan 1 : ".$pphl1."<br>";
                $pkp2 = $pkp - $lap_pkp1;
                if ($pkp2 >= $lap_pkp2) {
                    $pphl2 = round($lap_pkp2 * ($per_pkp2 / 100));
                } else {
                    $pphl2 = round($pkp * ($per_pkp2 / 100));
                }
                $pkp3 = $pkp - $lap_pkp1 - $lap_pkp2;
                $pphl3 = round($pkp3 * ($per_pkp3 / 100));
            } else {
                $pphl1 = 0;
                $pphl2 = 0;
                $pphl3 = 0;
            }
            /* END LAPISAN PKP */
            $tot_pph = $pphl1 + $pphl2 + $pphl3;

            /* END PPH21 */

            /* NETT */
            $net_asr = $bruto - $pot_dplk - $astek - $uspmi - $umangkir - $tot_pph;
            $net = $net_asr - $uasuransi;
            $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;

            $kopkar = $this->m_lembur->potongan_kopkar($recid_karyawan, $tahun, $bulan);
            if ($kopkar->num_rows() > 0) {
                foreach ($kopkar->result() as $kop) {
                    $pot_kopkar = $kop->potongan;
                }
            } else {
                $pot_kopkar = 0;
            }
            $jml_terima = $net - $pot_kopkar;

            echo "
            <tr>
            <td>" . $nik . "</td>
            <td>" . $nama_karyawan . "</td>
            <td>" . $masker_tahun . "</td>
            <td>" . $sts_pph . "</td>
            <td>" . $gapokl . "</td>
            <td>" . $global_lama . "</td>
            <td>" . $gapokb . "</td>
            <td>" . $t_jbtn2 . "</td>
            <td>" . $t_jenpek2 . "</td>
            <td>" . $t_masker2 . "</td>
            <td>" . $global_baru . "</td>
            <td>" . $slembur1 . "</td>
            <td>" . $slembur2 . "</td>
            <td>" . $slembur1 + $slembur2 . "</td>
            <td>" . $utot_shift . "</td>
            <td>" . $lembur1_lama . "</td>
            <td>" . $uph_lbr1_lama . "</td>
            <td>" . $lembur2_lama . "</td>
            <td>" . $uph_lbr2_lama . "</td>
            <td>" . $lembur3_lama . "</td>
            <td>" . $uph_lbr3_lama . "</td>
            <td>" . $lembur1_lama + $lembur2_lama + $lembur3_lama . "</td>
            <td>" . $tot_lbr_lama . "</td>

            <td>" . $lembur1_baru . "</td>
            <td>" . $uph_lbr1_baru . "</td>
            <td>" . $lembur2_baru . "</td>
            <td>" . $uph_lbr2_baru . "</td>
            <td>" . $lembur3_baru . "</td>
            <td>" . $uph_lbr3_baru . "</td>
            <td>" . $lembur1_baru + $lembur2_baru + $lembur3_baru . "</td>
            <td>" . $tot_lbr_baru . "</td>
            <td>" . $jml_hadir_lama . "</td>
            <td>" . $upah_hadir_lama . "</td>
            <td>" . $jml_hadir_baru . "</td>
            <td>" . $upah_hadir_baru . "</td>
            <td>" . $potongan . "</td>
            <td>" . $uang_transport_baru + $uang_transport_lama . "</td>
            <td>" . $uang_makan_baru + $uang_makan_lama . "</td>
            <td>" . round($uasuransi) . "</td>
            <td>" . round($pot_dplk) . "</td>
            <td>" . round($astek) . "</td>
            <td>" . round($uspmi) . "</td>
            <td>" . round($umangkir) . "</td>
            <td>" . round($bruto) . "</td>
            <td>" . round($uptkp) . "</td>
            <td>" . round($biaya_jabatan) . "</td>
            <td>" . round($uapph) . "</td>
            <td>" . round($tot_pot) . "</td>
            <td>" . round($pkp) . "</td>
            <td>" . round($pphl1) . "</td>
            <td>" . round($pphl2) . "</td>
            <td>" . round($pphl3) . "</td>
            <td>" . round($net_asr) . "</td>
            <td>" . round($net) . "</td>
            <td>" . round($pot_kopkar) . "</td>
            <td>" . round($jml_terima) . "</td>
            </tr>";
        }
        echo "</table>";
    }

    function thr_cek()
    {
        $tahun_baru = date('Y');
        echo "<br>LAPORAN THR <br>";
        echo "<table border='1'>
        <tr><td>NIK</td>
        <td>NAMA KARYAWAN</td>
        <td>MASKER</td>
        <td>STATUS</td>
        <td>GAPOK</td>
        <td>TJBTN</td>
        <td>TJENPEK</td>
        <td>TMASKER</td>
        <td>GLOBAL</td>
        <td>ASURANSI</td>
        <td>BRUTO</td>
        <td>BRUTO SETAHUN</td>
        <td>THR</td>
        <td>BRUTO THN+THR</td>
        <td>PTKP</td>
        <td>ASTEK</td>
        <td>PENSIUN</td>
        <td>TOTAL PENGURANG</td>
        <td>PENGURANG SETAHUN</td>
        <td>BJBTN BRTUO</td>
        <td>BJBTN BRTUO + THR</td>
        <td>PKP BRUTO</td>
        <td>PKP BRUTO + THR</td>
        <td>PPH BRUTO</td>
        <td>PPH BRUTO + THR</td>
        <td>PPH THR</td>
        <td>THR NET</td>
        </tr>";

        /* -------- base masker = 1 ------------------*/
        //2024
        $rp_masker = $this->m_hris->param_upah_id(1);
        foreach ($rp_masker->result() as $r) {
            $uph_masker = $r->nilai;
        }

        $recid_karyawan = 928; // kori
        $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and recid_karyawan = $recid_karyawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
        /* and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan */

        foreach ($karyawan->result() as $k) {
            $recid_karyawan = $k->recid_karyawan;
            $nik = $k->nik;
            $nama_karyawan = $k->nama_karyawan;
            $bagian = $k->indeks_hr;
            $jabatan = $k->indeks_jabatan;
            $t_jbtn = $k->t_jabatan;
            $tingkatan = $k->tingkatan;
            $t_prestasi = $k->t_prestasi;
            $t_jenpek = $k->t_jen_pek;
            $penempatan = $k->penempatan;
            // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
            $tgl_akhir = date('Y-m-d');
            $fil = "Idul Fitri " . date('Y');
            $cek_lebaran = $this->m_lembur->cek_lebaran($fil);
            if ($cek_lebaran->num_rows() > 0) {
                foreach ($cek_lebaran->result() as $l) {
                    $tgl_akhir = $l->nilai;
                }
            } else {
                $tgl_akhir = date('Y-m-d');
            }
            echo $tgl_akhir;
            $trakhir = date("Y-m-t", strtotime($tgl_akhir));
            $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
            $masker_tahun = $diff->format('%y');
            $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;
            $sts_jabatan = $k->sts_jabatan;

            // echo "tingkatan : ".$tingkatan."<br>";
            // echo "masker tahun : ".$masker_tahun."<br>";


            if ($tingkatan == 1) {
                if ($penempatan == 'Jakarta') {
                    //2024
                    $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun_baru);
                    foreach ($gapok_baru->result() as $gp2) {
                        if ($sts_jabatan == "Advisor") {
                            $gapok = (80 / 100) * $gp2->nilai;
                        } else {
                            $gapok = $gp2->nilai;
                        }
                    }
                } else {
                    //2024
                    $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun_baru);
                    foreach ($gapok_baru->result() as $gp2) {
                        if ($sts_jabatan == "Advisor") {
                            $gapok = (80 / 100) * $gp2->nilai;
                        } else {
                            $gapok = $gp2->nilai;
                        }
                    }
                }
            } else {
                //2024
                $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun_baru);
                foreach ($gapok_baru->result() as $gp2) {
                    if ($sts_jabatan == "Advisor") {
                        $gapok = (80 / 100) * $gp2->nilai;
                    } else {
                        $gapok = $gp2->nilai;
                    }
                }
            }

            if ($k->tingkatan == 1) {
                //operator tunjangan jabatan = 0
                $t_jbtn = 0;
                // cek tunjangan jenis pekerjaan by bagian
                $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun_baru);
                if ($tjenpek2->num_rows() > 0) {
                    foreach ($tjenpek2->result() as $t2) {
                        $t_jenpek = $t2->nilai;
                    }
                } else {
                    $t_jenpek = 0;
                }

                // echo "t_jbtn : $t_jbtn<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";

            } else {
                //wakaru ke atas, t_jenpek = 0
                $t_jenpek = 0;
                //cek tunjangan jabatannya
                $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun_baru);
                foreach ($tjbtn2->result() as $t2) {
                    if ($sts_jabatan == "Advisor") {
                        $t_jbtn = (80 / 100) * $t2->nilai;
                    } else {
                        $t_jbtn = $t2->nilai;
                    }
                }
            }
            $t_masker = $masker_tahun * $uph_masker;
            $global = $gapok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
            // if ($sts_jabatan == "Advisor") {
            //     $global = ((80 / 100) * ($gapok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
            // } else {
            //     $global = $gapok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
            // }

            /* START ASURANSI PERUSAHAAN */
            if ($k->cek_bpjs_tk == '1') {
                //persentase jkm
                $jkm = $this->m_hris->param_upah_id(16);
                foreach ($jkm->result() as $jkm1) {
                    $ujkm = $jkm1->nilai;
                }

                // persentase jkk
                $jkk = $this->m_hris->param_upah_id(17);
                foreach ($jkk->result() as $jkk1) {
                    $ujkk = $jkk1->nilai;
                }
            } else {
                $ujkm = 0;
                $ujkk = 0;
            }

            if ($k->cek_bpjs_kes == '1') {
                //persentase bpjs kesehatan
                $bpjs = $this->m_hris->param_upah_id(18);
                foreach ($bpjs->result() as $bpjs1) {
                    $ubpjs = $bpjs1->nilai;
                }
            } else {
                $ubpjs = 0;
            }


            $per_asr = $ujkm + $ujkk + $ubpjs;
            $uasuransi = round($global * ($per_asr / 100));
            $thr = $global;
            /* END ASURANSI PERUSAHAAN */

            $bruto = $global + $uasuransi;
            $bruto_thn = ($bruto * 12);

            /* START PTKP */
            $sts_kawin = $k->sts_penunjang;
            if ($sts_kawin == "K0") {
                // ptkp K0
                $ptkp = $this->m_hris->param_upah_id(28);
            } else if ($sts_kawin == "K1") {
                // ptkp K1
                $ptkp = $this->m_hris->param_upah_id(29);
            } else if ($sts_kawin == "K2") {
                $ptkp = $this->m_hris->param_upah_id(30);
            } else if ($sts_kawin == "K3") {
                // ptkp K3
                $ptkp = $this->m_hris->param_upah_id(31);
            } else if ($sts_kawin == "TK1") {
                // ptkp TK1
                $ptkp = $this->m_hris->param_upah_id(45);
            } else if ($sts_kawin == "TK2") {
                // ptkp TK2
                $ptkp = $this->m_hris->param_upah_id(46);
            } else if ($sts_kawin == "TK3") {
                // ptkp TK3
                $ptkp = $this->m_hris->param_upah_id(47);
            } else {
                // ptkp TK
                $ptkp = $this->m_hris->param_upah_id(27);
            }
            foreach ($ptkp->result() as $ptkp1) {
                $uptkp = $ptkp1->nilai;
            }
            /* END PTKP */

            /* START ASTEK */
            // astek jp
            if ($k->cek_bpjs_tk == '1') {
                $ajp = $this->m_hris->param_upah_id(19);
                foreach ($ajp->result() as $ajp1) {
                    $asuransi_jp = $ajp1->nilai;
                }

                // astek jht
                $ajht = $this->m_hris->param_upah_id(20);
                foreach ($ajht->result() as $ajht1) {
                    $asuransi_jht = $ajht1->nilai;
                }
            } else {
                $asuransi_jp = 0;
                $asuransi_jht = 0;
            }

            //astek bpjs kesehatan
            if ($k->cek_bpjs_kes == '1') {
                $abkes = $this->m_hris->param_upah_id(21);
                foreach ($abkes->result() as $abkes1) {
                    $asuransi_bkes = $abkes1->nilai;
                }
            } else {
                $asuransi_bkes = 0;
            }

            $kali_astek = $asuransi_jp + $asuransi_jht;
            $astek = round($global * ($kali_astek / 100));
            /* END ASTEK */

            /* START PENSIUN */
            if ($k->pensiun == "Ya") {
                // persentase dplk
                $dplk = $this->m_hris->param_upah_id(22);
                foreach ($dplk->result() as $dplk1) {
                    $dplk_aia = $dplk1->nilai;
                }
                $pot_dplk = $global * ($dplk_aia / 100);
            } else {
                $pot_dplk = 0;
            }
            /* END PENSIUN */

            $pengurang = $uptkp + $astek + $pot_dplk;
            $pengurang_thn = $pengurang * 12;

            /* START BIAYA JABATAN */
            //biaya jabatan
            $bjab = $this->m_hris->param_upah_id(25);
            foreach ($bjab->result() as $bjab1) {
                $b_jabatan = $bjab1->nilai;
            }

            //max biaya jabatan
            $max_jbtn = $this->m_hris->param_upah_id(26);
            foreach ($max_jbtn->result() as $max_jbtn1) {
                $max_jab = $max_jbtn1->nilai;
            }

            $biaya_jabatan = $bruto * ($b_jabatan / 100);
            if ($biaya_jabatan > $max_jab) {
                $biaya_jabatan = $max_jab;
            }
            /* END BIAYA JABATAN */
            $bjab_brut = round($bruto_thn * 0.05);
            $bjab_brut_thr = round(($bruto_thn + $thr) * 0.05);
            $pkp_brutto = $bruto_thn - $pengurang_thn - $bjab_brut;
            if ($pkp_brutto < 0) {
                $pkp_brutto = 0;
            }

            $pkp_brut_thr = ($bruto_thn + $thr) - $pengurang_thn - $bjab_brut_thr;
            if ($pkp_brut_thr < 0) {
                $pkp_brut_thr = 0;
            }

            $pph_bruto = round($pkp_brutto * 0.05);
            $pph_bruto_thr = round($pkp_brut_thr * 0.05);
            $pph_thr = $pph_bruto_thr - $pph_bruto;
            $terima = $thr - $pph_thr;

            echo "
            <tr>
            <td>" . $nik . "</td>
            <td>" . $nama_karyawan . "</td>
            <td>" . $masker_tahun . "</td>
            <td>" . $sts_pph . "</td>
            <td>" . $gapok . "</td>
            <td>" . $t_jbtn . "</td>
            <td>" . $t_jenpek . "</td>
            <td>" . $t_masker . "</td>
            <td>" . $global . "</td>
            <td>" . $uasuransi . "</td>
            <td>" . $bruto . "</td>
            <td>" . $bruto_thn . "</td>
            <td>" . $thr . "</td>
            <td>" . $bruto_thn + $thr . "</td>
            <td>" . $uptkp . "</td>
            <td>" . $astek . "</td>
            <td>" . $pot_dplk . "</td>
            <td>" . $pengurang . "</td>
            <td>" . $pengurang_thn . "</td>
            <td>" . $bjab_brut . "</td>
            <td>" . $bjab_brut_thr . "</td>
            <td>" . $pkp_brutto . "</td>
            <td>" . $pkp_brut_thr . "</td>
            <td>" . $pph_bruto . "</td>
            <td>" . $pph_bruto_thr . "</td>
            <td>" . $pph_thr . "</td>
            <td>" . $terima . "</td>
            </tr>";
        }
        echo "</table>";
    }


    function r_master_thr()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['tahun'] = $this->m_absen->tahun_hk();
            $tahun = Date('Y');
            $bulan = Date('m');
            $list_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = $list_bulan[$bulan - 1];
            $data['bulans'] = $bulan;
            $periode_awal = $this->m_lembur->cutoff_thn_bln($tahun, $bulan);
            foreach ($periode_awal->result() as $p) {
                $awal = $p->periode_awal;
                $akhir = $p->periode_akhir;
            }
            $data['periode_awal']  = $awal;
            $data['periode_akhir']  = $akhir;
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_master_thr', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    public function download_thr()
    {
        /* tipe 1 : simpan & download, tipe 2: download saja  */
        // $tahun = $this->input->post('tahun');
        $tahun = date('Y');
        $tipe = $this->input->post('tipe');
        $fpaygroup = array();
        $paygroup = $this->input->post('divisi');
        $department = $this->input->post('departement');
        $bagian = $this->input->post('bagian10');
        $karyawan = $this->input->post('karyawan');

        if (!empty($paygroup)) {
            for ($i = 0; $i < count($paygroup); $i++) {
                array_push($fpaygroup, $paygroup[$i]);
            }
        } else {
            $paygroup = array();
            $pg = $this->m_hris->paygroup_emp();
            foreach ($pg->result() as $dv) {
                array_push($fpaygroup, $dv->pay_group);
                array_push($paygroup, $dv->pay_group);
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
        // echo $fdepartment;


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

        if ($tipe == '1') {
            /* truncate all data table upah */
            $this->m_lembur->kosongkan_thr();
            $iduniq = uniqid();
        } else if ($tipe == '3') {
            $data['paygroup'] = $fpaygroup;
            $data['fdepartment'] = $fdepartment;
            $data['fbagian'] = $fbagian;
            $data['fkaryawan'] = $fkaryawan;
            $data['tahun'] = $tahun;
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/thr', $data);
            $this->load->view('layout/a_footer');
        } else {
        }

        if ($tipe != '3') {
            //base masker = 1
            $rp_masker = $this->m_hris->param_upah_id(1);
            foreach ($rp_masker->result() as $r) {
                $uph_masker = $r->nilai;
            }

            $tgl_skrg = date('d M Y  H:i:s');
            $usr = strtolower($this->session->userdata('nama'));

            $spreadsheet = new Spreadsheet();
            $tahap = ["Tahap1", "Tahap2"];
            $pgx = 1;

            $tot_global = 0;
            $tot_shift1 = 0;
            $tot_shift2 = 0;
            $tot_totshift = 0;
            $tot_ushift = 0;
            $tot_lbr1 = 0;
            $tot_lbr2 = 0;
            $tot_lbr3 = 0;
            $tot_totlbr = 0;
            $tot_ulbr = 0;
            $tot_premi  = 0;
            $tot_trans   = 0;
            $tot_mak    = 0;
            $tot_asr    = 0;
            $tot_pensiun = 0;
            $tot_astek = 0;
            $tot_spmi = 0;
            $tot_mangkir = 0;
            $tot_cpph = 0;
            $tot_netasr = 0;
            $tot_net = 0;
            $tot_potkop = 0;
            $tot_terima = 0;

            // foreach ($paygroup->result() as $pg)
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                // echo $fpaygroup[$pyg];
                ${"rekap_" . $paygroup[$pyg]} = array();
                ${"totpaygroup" . $paygroup[$pyg]} = array();
                $pgapok = 0;
                $pjbtn = 0;
                $pjenpek = 0;
                $pmasker = 0;
                $pglobal = 0;
                $pshift1 = 0;
                $pshift2 = 0;
                $ptotshift = 0;
                $ptotushift = 0;
                $plbr1 = 0;
                $plbr2 = 0;
                $plbr3 = 0;
                $ptotlbr = 0;
                $ptotulbr = 0;
                $ppremi = 0;
                $ptrans = 0;
                $pmak = 0;
                $pasr = 0;
                $ppen = 0;
                $past = 0;
                $ppspmi = 0;
                $pmang = 0;
                $ppph = 0;
                $pnetasr = 0;
                $pnet = 0;
                $ppotkop = 0;
                $pterima = 0;
                // echo $tahap[$p]."<br>";
                // Create a new worksheet called "My Data"
                $pgx = $pgx + 1;
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $paygroup[$pyg]);
                $spreadsheet->addSheet($myWorkSheet, $pgx);
                $spreadsheet->setActiveSheetIndexByName($paygroup[$pyg]);
                /* ----- START SETTING PAPER -------------------- */
                $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
                $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
                $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.7);
                $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
                $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.7);
                /* ----- END SETTING PAPER -------------------- */

                $sheet = $spreadsheet->getActiveSheet();
                $max_baris = $sheet->getHighestRow();

                // if($pgx>1)
                // {
                //     $max_baris = $max_baris + 3;
                // }
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Rekapitulasi THR Tahun ' . $tahun . ' Yang Termasuk ke Upah ' . $paygroup[$pyg]);
                //  echo 'A'.$max_baris." => Gaji Pay Group <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Tahun ' . $tahun);
                //  echo 'A'.$max_baris." => Periode <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Cimahi, ' . $tgl_skrg);
                //  echo 'A'.$max_baris." => Tanggal Akses <br>";

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
                $usr = strtolower($this->session->userdata('nama'));
                $sheet->setCellValue('A' . $max_baris, 'Dicetak Oleh : ' . $usr);
                //  echo 'A'.$max_baris." => Print <br>";

                $awal_baris = $max_baris + 2;
                $max_baris = $max_baris + 2;
                $max_bariss = $max_baris + 1;


                $sheet->getColumnDimension('A')->setWidth(3);
                $sheet->getColumnDimension('C')->setWidth(6);
                $sheet->getColumnDimension('F')->setWidth(8);
                $sheet->getColumnDimension('G')->setWidth(8);
                $sheet->getColumnDimension('H')->setWidth(8);
                $sheet->getColumnDimension('I')->setWidth(11);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('k')->setWidth(6);
                $sheet->getColumnDimension('L')->setWidth(6);
                $sheet->getColumnDimension('N')->setWidth(6);
                $sheet->getColumnDimension('O')->setWidth(6);
                $sheet->getColumnDimension('P')->setWidth(6);
                $sheet->getColumnDimension('Q')->setWidth(6);
                $sheet->getColumnDimension('S')->setWidth(8);
                $sheet->getColumnDimension('T')->setWidth(8);
                $sheet->getColumnDimension('U')->setWidth(8);
                $sheet->getColumnDimension('V')->setWidth(8);
                $sheet->getColumnDimension('W')->setWidth(8);
                $sheet->getColumnDimension('X')->setWidth(8);
                $sheet->getColumnDimension('Y')->setWidth(8);
                $sheet->getColumnDimension('Z')->setWidth(8);
                $sheet->getColumnDimension('AA')->setWidth(8);
                $sheet->getColumnDimension('AB')->setWidth(8);
                //  $sheet->getColumnDimension('AC')->setWidth(11);
                //  $sheet->getColumnDimension('AD')->setWidth(8);
                //  $sheet->getColumnDimension('AE')->setWidth(11);
                //  $sheet->getColumnDimension('AF')->setWidth(8);

                $sheet->getStyle('A' . $max_baris . ':B' . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle('A' . $max_bariss . ':AB' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
                $sheet->getStyle('A' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('A' . $max_baris, 'No');
                $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
                $sheet->getStyle('B' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('B' . $max_baris, 'Nama Karyawan');
                $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
                $sheet->getStyle('C' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('C' . $max_baris, 'Masa Kerja');
                $sheet->mergeCells('D' . $max_baris . ':D' . $max_bariss);
                $sheet->getStyle('D' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('D' . $max_baris, 'Status Kary. Gol');
                $sheet->mergeCells('E' . $max_baris . ':E' . $max_bariss);
                $sheet->getStyle('E' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('E' . $max_baris, 'Gaji Pokok');
                $sheet->mergeCells('F' . $max_baris . ':H' . $max_baris); // colspan
                // $sheet->getStyle('F'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('F' . $max_baris, 'Tunjangan');
                $sheet->setCellValue('F' . $max_bariss, 'Jabatan');
                $sheet->setCellValue('G' . $max_bariss, 'Jen.Pek');
                $sheet->setCellValue('H' . $max_bariss, 'Mas.Ker');
                $sheet->mergeCells('I' . $max_baris . ':I' . $max_bariss);
                $sheet->getStyle('I' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('I' . $max_baris, 'Gaji per Bulan');
                $sheet->mergeCells('J' . $max_baris . ':M' . $max_baris);
                $sheet->setCellValue('J' . $max_baris, 'Premi Shift');
                // $sheet->getStyle('j'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');

                $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('J' . $max_bariss, 'Jml Jam1');
                $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('K' . $max_bariss, 'Jml Jam 2');
                $sheet->getStyle('L' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('L' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('M' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('M' . $max_bariss, 'Total Premi shift');
                $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_baris, 'Lbr1');
                $sheet->getStyle('N' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('N' . $max_bariss, 'Jml Jam');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_baris, 'Lbr2');
                $sheet->getStyle('O' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('O' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('P' . $max_baris, 'Lbr3');
                $sheet->getStyle('P' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('P' . $max_bariss, 'Jml Jam');
                $sheet->mergeCells('Q' . $max_baris . ':R' . $max_baris);
                $sheet->getStyle('Q' . $max_bariss)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('Q' . $max_baris, 'Jumlah Lembur');
                $sheet->setCellValue('Q' . $max_bariss, 'Jml Jam');
                $sheet->setCellValue('R' . $max_bariss, 'Jml Upah');
                $sheet->mergeCells('S' . $max_baris . ':S' . $max_bariss);
                $sheet->getStyle('S' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('S' . $max_baris, 'Premi Hadir');
                $sheet->mergeCells('T' . $max_baris . ':T' . $max_bariss);
                $sheet->getStyle('T' . $max_baris)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('T' . $max_baris, 'Transport');
                $sheet->mergeCells('U' . $max_baris . ':U' . $max_bariss);
                $sheet->setCellValue('U' . $max_baris, 'Makan');
                $sheet->mergeCells('V' . $max_baris . ':V' . $max_bariss);
                $sheet->setCellValue('V' . $max_baris, 'Asuransi');
                $sheet->mergeCells('W' . $max_baris . ':AA' . $max_baris);
                // $sheet->getStyle('W'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('W' . $max_baris, 'Potongan');
                $sheet->setCellValue('W' . $max_bariss, 'Pensiun');
                $sheet->setCellValue('X' . $max_bariss, 'Astek');
                $sheet->setCellValue('Y' . $max_bariss, 'SPMI');
                $sheet->setCellValue('Z' . $max_bariss, 'Mangkir');
                $sheet->setCellValue('AA' . $max_bariss, 'PPH21');
                //  $sheet->mergeCells('AB'.$max_baris.':AE'.$max_baris);
                // $sheet->getStyle('AB'.$max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->setCellValue('AB' . $max_baris, 'Bersih');
                $sheet->setCellValue('AB' . $max_bariss, 'TERIMA');
                $sheet->setCellValue('AC' . $max_bariss, 'NIK');

                //  $sheet->setCellValue('AB'.$max_bariss, 'Net + Asr');
                //  $sheet->setCellValue('AC'.$max_bariss, 'Net');
                //  $sheet->setCellValue('AD'.$max_bariss, 'Pot. Kopkar');
                //  $sheet->setCellValue('AE'.$max_bariss, 'Jml Terima');
                //  $sheet->setCellValue('AF'.$max_bariss, 'NIK');

                //  $new_baris = $new_baris+1;
                //  echo "baris ke : ".$max_baris;
                if ($fdepartment != '') {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.spm = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }

                    foreach ($bag->result() as $b) {
                        // echo $b->indeks_hr."<br>";
                        $no = 0;
                        ${"total" . $b->recid_bag} = 0;
                        $bgapok = 0;
                        $bjbtn = 0;
                        $bjenpek = 0;
                        $bmasker = 0;
                        $bglobal = 0;
                        $bshift1 = 0;
                        $bshift2 = 0;
                        $btotshift = 0;
                        $btotushift = 0;
                        $blbr1 = 0;
                        $blbr2 = 0;
                        $blbr3 = 0;
                        $btotlbr = 0;
                        $btotulbr = 0;
                        $bpremi = 0;
                        $btrans = 0;
                        $bmak = 0;
                        $basr = 0;
                        $bpen = 0;
                        $bast = 0;
                        $bspmi = 0;
                        $bmang = 0;
                        $bpph = 0;
                        $bnetasr = 0;
                        $bnet = 0;
                        $bpotkop = 0;
                        $bterima = 0;

                        if ($fkaryawan != '') {
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        if ($karyawan->num_rows() > 0) {
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            $sheet->mergeCells('A' . $max_baris . ':AB' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->setCellValue('A' . $max_baris, "Bagian : " . $b->indeks_hr);

                            $max_baris = $sheet->getHighestRow();
                            $new_baris = $max_baris;
                            foreach ($karyawan->result() as $k) {
                                $new_baris = $new_baris + 1;
                                $recid_karyawan = $k->recid_karyawan;
                                $nik = $k->nik;
                                $nama_karyawan = $k->nama_karyawan;
                                $bagian = $k->indeks_hr;
                                $jabatan = $k->indeks_jabatan;
                                $t_jbtn = $k->t_jabatan;
                                $tingkatan = $k->tingkatan;
                                $t_prestasi = $k->t_prestasi;
                                $t_jenpek = $k->t_jen_pek;
                                // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                                $tgl_akhir = date('Y-m-d');
                                $fil = "Idul Fitri " . date('Y');
                                $cek_lebaran = $this->m_lembur->cek_lebaran($fil);
                                if ($cek_lebaran->num_rows() > 0) {
                                    foreach ($cek_lebaran->result() as $l) {
                                        $tgl_akhir = $l->nilai;
                                    }
                                } else {
                                    $tgl_akhir = date('Y-m-d');
                                }
                                $trakhir = date("Y-m-t", strtotime($tgl_akhir));
                                $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
                                $masker_tahun = $diff->format('%y');
                                $masker_bulan = $diff->format('%m');
                                $sts_pph =  $k->sts_penunjang . "/" . $k->sts_jbtn;
                                $sts_jabatan = $k->sts_jabatan;

                                // echo "tingkatan : ".$tingkatan."<br>";
                                // echo "masker tahun : ".$masker_tahun."<br>";


                                if ($tingkatan == 1) {
                                    if ($penempatan == 'Jakarta') {
                                        //2024
                                        $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            $gapok = $gp2->nilai;
                                        }
                                    } else {
                                        //2024
                                        $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun);
                                        foreach ($gapok_baru->result() as $gp2) {
                                            $gapok = $gp2->nilai;
                                        }
                                    }
                                } else {
                                    //2024
                                    $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun);
                                    foreach ($gapok_baru->result() as $gp2) {
                                        $gapok = $gp2->nilai;
                                    }
                                }

                                if ($k->tingkatan == 1) {
                                    //operator tunjangan jabatan = 0
                                    $t_jbtn = 0;
                                    // cek tunjangan jenis pekerjaan by bagian
                                    $tjenpek2 = $this->m_hris->tjenpek($k->recid_bag, $tahun);
                                    if ($tjenpek2->num_rows() > 0) {
                                        foreach ($tjenpek2->result() as $t2) {
                                            $t_jenpek = $t2->nilai;
                                        }
                                    } else {
                                        $t_jenpek = 0;
                                    }

                                    // echo "t_jbtn : $t_jbtn<br> t_jenpek lama : $t_jenpek1<br> t_jenpek baru : $t_jenpek2<br>";

                                } else {
                                    //wakaru ke atas, t_jenpek = 0
                                    $t_jenpek = 0;
                                    //cek tunjangan jabatannya
                                    $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun);
                                    foreach ($tjbtn2->result() as $t2) {
                                        $t_jbtn = $t2->nilai;
                                    }
                                }
                                $t_masker = $masker_tahun * $uph_masker;
                                if ($sts_jabatan == "Advisor") {
                                    $global = ((80 / 100) * ($gapok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
                                } else {
                                    $global = $gapok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
                                }

                                $slembur1 = 0;
                                $slembur2 = 0;
                                $shift_lbr1 = 0;
                                $shift_lbr2 = 0;
                                $uph_lbr1 = 0;
                                $uph_lbr2 = 0;
                                $uph_lbr3 = 0;
                                $utot_shift = 0;
                                $lembur1 = 0;
                                $lembur2 = 0;
                                $lembur3 = 0;
                                $tot_lbr = 0;
                                $jml_hadir = 0;
                                $premi = 0;
                                $trencana = 0;
                                $prencana = 0;
                                $tdkrencana = 0;
                                $ptdrencana = 0;
                                $pulang = 0;
                                $ppulang = 0;
                                $keluar = 0;
                                $cnt_keluar = 0;
                                $pkeluar = 0;
                                $ppulang = 0;
                                $ptdrencana = 0;
                                $prencana = 0;
                                $upah_hadir = 0;
                                $uapph = 0;
                                $tot_pot = 0;
                                $pkp = 0;
                                $pphl1 = 0;
                                $pphl2 = 0;
                                $pphl3 = 0;
                                $bonus = 0;
                                $uang_transport = 0;
                                $uang_makan = 0;
                                $uspmi = 0;
                                $umangkir = 0;

                                /* START ASURANSI PERUSAHAAN */
                                if ($k->cek_bpjs_tk == '1') {
                                    //persentase jkm
                                    $jkm = $this->m_hris->param_upah_id(16);
                                    foreach ($jkm->result() as $jkm1) {
                                        $ujkm = $jkm1->nilai;
                                    }

                                    // persentase jkk
                                    $jkk = $this->m_hris->param_upah_id(17);
                                    foreach ($jkk->result() as $jkk1) {
                                        $ujkk = $jkk1->nilai;
                                    }
                                } else {
                                    $ujkm = 0;
                                    $ujkk = 0;
                                }

                                if ($k->cek_bpjs_kes == '1') {
                                    //persentase bpjs kesehatan
                                    $bpjs = $this->m_hris->param_upah_id(18);
                                    foreach ($bpjs->result() as $bpjs1) {
                                        $ubpjs = $bpjs1->nilai;
                                    }
                                } else {
                                    $ubpjs = 0;
                                }


                                $per_asr = $ujkm + $ujkk + $ubpjs;
                                $uasuransi = round($global * ($per_asr / 100));
                                if ($masker_tahun < 1) {
                                    $thr = ($masker_bulan / 12) * $global;
                                } else {
                                    $thr = $global;
                                }
                                /* END ASURANSI PERUSAHAAN */

                                $bruto = $global + $uasuransi;
                                $bruto_thn = ($bruto * 12);

                                /* START PTKP */
                                $sts_kawin = $k->sts_penunjang;
                                if ($sts_kawin == "K0") {
                                    // ptkp K0
                                    $ptkp = $this->m_hris->param_upah_id(28);
                                } else if ($sts_kawin == "K1") {
                                    // ptkp K1
                                    $ptkp = $this->m_hris->param_upah_id(29);
                                } else if ($sts_kawin == "K2") {
                                    $ptkp = $this->m_hris->param_upah_id(30);
                                } else if ($sts_kawin == "K3") {
                                    // ptkp K3
                                    $ptkp = $this->m_hris->param_upah_id(31);
                                } else if ($sts_kawin == "TK1") {
                                    // ptkp TK1
                                    $ptkp = $this->m_hris->param_upah_id(45);
                                } else if ($sts_kawin == "TK2") {
                                    // ptkp TK2
                                    $ptkp = $this->m_hris->param_upah_id(46);
                                } else if ($sts_kawin == "TK3") {
                                    // ptkp TK3
                                    $ptkp = $this->m_hris->param_upah_id(47);
                                } else {
                                    // ptkp TK
                                    $ptkp = $this->m_hris->param_upah_id(27);
                                }
                                foreach ($ptkp->result() as $ptkp1) {
                                    $uptkp = $ptkp1->nilai;
                                }
                                /* END PTKP */

                                /* START ASTEK */
                                // astek jp
                                if ($k->cek_bpjs_tk == '1') {
                                    $ajp = $this->m_hris->param_upah_id(19);
                                    foreach ($ajp->result() as $ajp1) {
                                        $asuransi_jp = $ajp1->nilai;
                                    }

                                    // astek jht
                                    $ajht = $this->m_hris->param_upah_id(20);
                                    foreach ($ajht->result() as $ajht1) {
                                        $asuransi_jht = $ajht1->nilai;
                                    }
                                } else {
                                    $asuransi_jp = 0;
                                    $asuransi_jht = 0;
                                }

                                //astek bpjs kesehatan
                                if ($k->cek_bpjs_kes == '1') {
                                    $abkes = $this->m_hris->param_upah_id(21);
                                    foreach ($abkes->result() as $abkes1) {
                                        $asuransi_bkes = $abkes1->nilai;
                                    }
                                } else {
                                    $asuransi_bkes = 0;
                                }

                                $kali_astek = $asuransi_jp + $asuransi_jht;
                                $astek = round($global * ($kali_astek / 100));
                                /* END ASTEK */

                                /* START PENSIUN */
                                if ($k->pensiun == "Ya") {
                                    // persentase dplk
                                    $dplk = $this->m_hris->param_upah_id(22);
                                    foreach ($dplk->result() as $dplk1) {
                                        $dplk_aia = $dplk1->nilai;
                                    }
                                    $pot_dplk = $global * ($dplk_aia / 100);
                                } else {
                                    $pot_dplk = 0;
                                }
                                /* END PENSIUN */

                                $pengurang = $uptkp + $astek + $pot_dplk;
                                $pengurang_thn = $pengurang * 12;

                                /* START BIAYA JABATAN */
                                //biaya jabatan
                                $bjab = $this->m_hris->param_upah_id(25);
                                foreach ($bjab->result() as $bjab1) {
                                    $b_jabatan = $bjab1->nilai;
                                }

                                //max biaya jabatan
                                $max_jbtn = $this->m_hris->param_upah_id(26);
                                foreach ($max_jbtn->result() as $max_jbtn1) {
                                    $max_jab = $max_jbtn1->nilai;
                                }

                                $biaya_jabatan = $bruto * ($b_jabatan / 100);
                                if ($biaya_jabatan > $max_jab) {
                                    $biaya_jabatan = $max_jab;
                                }
                                /* END BIAYA JABATAN */
                                $bjab_brut = round($bruto_thn * 0.05);
                                $bjab_brut_thr = round(($bruto_thn + $thr) * 0.05);
                                $pkp_brutto = $bruto_thn - $pengurang_thn - $bjab_brut;
                                if ($pkp_brutto < 0) {
                                    $pkp_brutto = 0;
                                }

                                $pkp_brut_thr = ($bruto_thn + $thr) - $pengurang_thn - $bjab_brut_thr;
                                if ($pkp_brut_thr < 0) {
                                    $pkp_brut_thr = 0;
                                }

                                $pph_bruto = round($pkp_brutto * 0.05);
                                $pph_bruto_thr = round($pkp_brut_thr * 0.05);
                                $pph_thr = $pph_bruto_thr - $pph_bruto;
                                $jml_terima = $thr - $pph_thr;

                                $tot_pph = $pph_thr;


                                /* -----------------------START SAVE DATA TO DATABASE--------------------- */
                                if ($tipe == 1) {
                                    $data = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'bulan'             => date('m'),
                                        'tahun'             => $tahun,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker,
                                        'upokok'            => $gapok,
                                        'tjbtn'             => $t_jbtn,
                                        'tjenpek'           => $t_jenpek,
                                        'uglobal'           => $global,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $premi,
                                        'trencana'          => $trencana,
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana,
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang,
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar,
                                        'keluar2'           => $cnt_keluar,
                                        'rp_keluar2'        => $pkeluar,
                                        'premi'             => $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana),
                                        'uang_transport'    => $uang_transport,
                                        'uang_makan'        => $uang_makan,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pph_thr,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_thr($data);
                                    $last = $this->m_lembur->last_thrid();
                                    foreach ($last->result() as $lu) {
                                        $recid_upah = $lu->recid_upah;
                                    }

                                    $iduniq = uniqid();
                                    $datalog = array(
                                        'crt_by'            => $this->session->userdata('kar_id'),
                                        'crt_date'          => date('Y-m-d H:i:s'),
                                        'id_unik'           => $iduniq,
                                        'recid_upah'        => $recid_upah,
                                        'bulan'             => date('m'),
                                        'tahun'             => $tahun,
                                        'recid_karyawan'    => $k->recid_karyawan,
                                        'recid_bag'         => $k->recid_bag,
                                        'recid_jbtn'        => $k->recid_jbtn,
                                        'sts_upah'          => $sts_pph,
                                        'masker'            => $masker_tahun,
                                        'rp_masker'         => $t_masker,
                                        'upokok'            => $gapok,
                                        'tjbtn'             => $t_jbtn,
                                        'tjenpek'           => $t_jenpek,
                                        'uglobal'           => $global,
                                        'slbr1'             => $slembur1,
                                        'rp_slbr1'          => $shift_lbr1,
                                        'slbr2'             => $slembur2,
                                        'rp_slbr2'          => $shift_lbr2,
                                        'lbr1'              => $lembur1,
                                        'rp_lbr1'           => $uph_lbr1,
                                        'lbr2'              => $lembur2,
                                        'rp_lbr2'           => $uph_lbr2,
                                        'lbr3'              => $lembur3,
                                        'rp_lbr3'           => $uph_lbr3,
                                        'tot_rp_lbr'        => $tot_lbr,
                                        'hadir'             => $jml_hadir,
                                        'rp_hadir'          => $premi,
                                        'trencana'          => $trencana,
                                        'rp_trencana'       => $prencana,
                                        'ttrencana'         => $tdkrencana,
                                        'rp_ttrencana'      => $ptdrencana,
                                        'pulang'            => $pulang,
                                        'rp_pulang'         => $ppulang,
                                        'keluar'            => $keluar,
                                        'keluar2'           => $cnt_keluar,
                                        'rp_keluar2'        => $pkeluar,
                                        'premi'             => $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana),
                                        'uang_transport'    => $uang_transport,
                                        'uang_makan'        => $uang_makan,
                                        'astek'             => $astek,
                                        'asuransi'          => $uasuransi,
                                        'bruto'             => $bruto,
                                        'pensiun'           => $pot_dplk,
                                        'spmi'              => $uspmi,
                                        'mangkir'           => $umangkir,
                                        'ptkp'              => $uptkp,
                                        'bjab'              => $biaya_jabatan,
                                        'astek_pph'         => $uapph,
                                        'tot_pot'           => $tot_pot,
                                        'pkp'               => $pkp,
                                        'pph21_1'           => $pph_thr,
                                        'pph21_2'           => $pphl2,
                                        'pph21_3'           => $pphl3,
                                        'jml_terima'         => $jml_terima,
                                    );
                                    $this->m_lembur->insert_thrlog($datalog);
                                }
                                /* -----------------------END SAVE DATA TO DATABASE--------------------- */

                                /* --------- START LOAD DATA DETAIL TO TABLE--------- */
                                // $new_rows = 'A'.$new_baris;
                                $no = $no + 1;
                                $sheet->setCellValue('A' . $new_baris, $no . ".");
                                $sheet->setCellValue('B' . $new_baris, $nama_karyawan);
                                $sheet->getStyle('C' . $new_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                                $sheet->setCellValue('C' . $new_baris, $masker_tahun);
                                $sheet->setCellValue('D' . $new_baris, $sts_pph);
                                $sheet->setCellValue('E' . $new_baris, $gapok);
                                $bgapok = $bgapok + $gapok;
                                $sheet->setCellValue('F' . $new_baris, $t_jbtn);
                                $bjbtn = $bjbtn + $t_jbtn;
                                $sheet->setCellValue('G' . $new_baris, $t_jenpek);
                                $bjenpek = $bjenpek + $t_jenpek;
                                $sheet->setCellValue('H' . $new_baris, $t_masker);
                                $bmasker = $bmasker + $t_masker;
                                $sheet->setCellValue('I' . $new_baris, $global);
                                $bglobal = $bglobal + $global;
                                $sheet->setCellValue('J' . $new_baris, $slembur1);
                                $bshift1 = $bshift1 + $slembur1;
                                $sheet->setCellValue('K' . $new_baris, $slembur2);
                                $bshift2 = $bshift2 + $slembur2;
                                $sheet->setCellValue('L' . $new_baris, $slembur2 + $slembur1);
                                $btotshift = $btotshift + ($slembur2 + $slembur1);
                                $sheet->setCellValue('M' . $new_baris, $utot_shift);
                                $btotushift = $btotushift + $utot_shift;
                                $sheet->setCellValue('N' . $new_baris, $lembur1);
                                $blbr1 = $blbr1 + $lembur1;
                                $sheet->setCellValue('O' . $new_baris, $lembur2);
                                $blbr2 = $blbr2 + $lembur2;
                                $sheet->setCellValue('P' . $new_baris, $lembur3);
                                $blbr3 = $blbr3 + $lembur3;
                                $sheet->setCellValue('Q' . $new_baris, $lembur3 + $lembur2 + $lembur1);
                                $btotlbr = $btotlbr + ($lembur3 + $lembur2 + $lembur1);
                                $sheet->setCellValue('R' . $new_baris, round($tot_lbr));
                                $btotulbr = $btotulbr + $tot_lbr;
                                $sheet->setCellValue('S' . $new_baris, round($bonus));
                                $bpremi = $bpremi + $bonus;
                                $sheet->setCellValue('T' . $new_baris, $uang_transport);
                                $btrans = $btrans +  $uang_transport;
                                $sheet->setCellValue('U' . $new_baris, $uang_makan);
                                $bmak = $bmak + $uang_makan;
                                $sheet->setCellValue('V' . $new_baris, round($uasuransi));
                                $basr = $basr + $uasuransi;
                                $sheet->setCellValue('W' . $new_baris, round($pot_dplk));
                                $bpen = $bpen + $pot_dplk;
                                $sheet->setCellValue('X' . $new_baris, round($astek));
                                $bast = $bast + $astek;
                                $sheet->setCellValue('Y' . $new_baris, round($uspmi));
                                $bspmi = $bspmi + $uspmi;
                                $sheet->setCellValue('Z' . $new_baris, round($umangkir));
                                $bmang = $bmang + $umangkir;
                                $sheet->setCellValue('AA' . $new_baris, round($tot_pph));
                                $bpph = $bpph + $tot_pph;
                                $sheet->setCellValue('AB' . $new_baris, round($jml_terima));
                                $bterima = $bterima + $jml_terima;
                                $sheet->setCellValue('AC' . $new_baris, $nik);
                                $bnik = "";
                                // $sheet->setCellValue('AB'.$new_baris, "hapus");
                                // $bnetasr = $bnetasr + 0;
                                // $sheet->setCellValue('AC'.$new_baris, "hapus");
                                // $bnet = $bnet + 0;
                                // $sheet->setCellValue('AD'.$new_baris, "hapus");
                                // $bpotkop = $bpotkop + 0;
                                // $sheet->setCellValue('AE'.$new_baris, round($jml_terima));
                                // $bterima = $bterima + $jml_terima;
                                // $sheet->setCellValue('AF'.$new_baris, round($nik));
                                // $bnik= "";
                                /* --------- END LOAD DATA DETAIL TO TABLE--------- */
                            } // loop karyawan
                            $max_baris = $sheet->getHighestRow();
                            $max_baris = $max_baris + 1;
                            /* --------- START LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                            $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                            $sheet->getStyle('A' . $max_baris)
                                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheet->setCellValue('A' . $max_baris, 'Sub Total');
                            $sheet->setCellValue('I' . $max_baris, $bglobal);
                            $sheet->setCellValue('J' . $max_baris, $bshift1);
                            $sheet->setCellValue('K' . $max_baris, $bshift2);
                            $sheet->setCellValue('L' . $max_baris, $btotshift);
                            $sheet->setCellValue('M' . $max_baris, $btotushift);
                            $sheet->setCellValue('N' . $max_baris, $blbr1);
                            $sheet->setCellValue('O' . $max_baris, $blbr2);
                            $sheet->setCellValue('P' . $max_baris, $blbr3);
                            $sheet->setCellValue('Q' . $max_baris, $btotlbr);
                            $sheet->setCellValue('R' . $max_baris, round($btotulbr));
                            $sheet->setCellValue('S' . $max_baris, round($bpremi));
                            $sheet->setCellValue('T' . $max_baris, $btrans);
                            $sheet->setCellValue('U' . $max_baris, $bmak);
                            $sheet->setCellValue('V' . $max_baris, round($basr));
                            $sheet->setCellValue('W' . $max_baris, round($bpen));
                            $sheet->setCellValue('X' . $max_baris, round($bast));
                            $sheet->setCellValue('Y' . $max_baris, round($bspmi));
                            $sheet->setCellValue('Z' . $max_baris, round($bmang));
                            $sheet->setCellValue('AA' . $max_baris, round($bpph));
                            $sheet->setCellValue('AB' . $max_baris, round($bterima));
                            $sheet->setCellValue('AC' . $max_baris, "");
                            // $sheet->setCellValue('AB'.$max_baris, round($bnetasr));
                            // $sheet->setCellValue('AC'.$max_baris, round($bnet));
                            // $sheet->setCellValue('AD'.$max_baris, round($bpotkop));
                            // $sheet->setCellValue('AE'.$max_baris, round($bterima));
                            // $sheet->setCellValue('AF'.$max_baris, "");
                            /* --------- END LOAD SUBTOTAL BY BAGIAN TO TABLE--------- */
                        } // karyawan > 0
                        /* --------- START CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        $pgapok = $pgapok + $bgapok;
                        $pjbtn = $pjbtn + $bjbtn;
                        $pjenpek = $pjenpek  + $bjenpek;
                        $pmasker = $pmasker + $bmasker;
                        $pglobal = $pglobal + $bglobal;
                        $pshift1 = $pshift1 + $bshift1;
                        $pshift2 = $pshift2 + $bshift2;
                        $ptotshift = $ptotshift + $btotshift;
                        $ptotushift = $ptotushift + $btotushift;
                        $plbr1 = $plbr1 + $blbr1;
                        $plbr2 = $plbr2 + $blbr2;
                        $plbr3 = $plbr3  + $blbr3;
                        $ptotlbr = $ptotlbr + $btotlbr;
                        $ptotulbr = $ptotulbr + $btotulbr;
                        $ppremi = $ppremi + $bpremi;
                        $ptrans = $ptrans + $btrans;
                        $pmak = $pmak  + $bmak;
                        $pasr = $pasr + $basr;
                        $ppen = $ppen + $bpen;
                        $past = $past  + $bast;
                        $ppspmi = $ppspmi + $bspmi;
                        $pmang = $pmang + $bmang;
                        $ppph = $ppph + $bpph;
                        $pnetasr = $pnetasr + $bnetasr;
                        $pnet = $pnet + $bnet;
                        $ppotkop = $ppotkop + $bpotkop;
                        $pterima = $pterima + $bterima;
                        /* --------- END CALCULATE SUB TOTAL BY PAY GROUP --------- */
                        /* --------- START CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                        ${"total" . $b->recid_bag} = array(
                            "bagian"    => $b->indeks_hr,
                            "global"    => round($bglobal),
                            "bshift1"    => round($bshift1),
                            "bshift2"    => round($bshift2),
                            "btotshift"    => round($btotshift),
                            "btotushift"    => round($btotushift),
                            "blbr1"    => round($blbr1),
                            "blbr2"    => round($blbr2),
                            "blbr3"    => round($blbr3),
                            "btotlbr"    => round($btotlbr),
                            "btotulbr"    => round($btotulbr),
                            "bpremi"    => round($bpremi),
                            "btrans"    => round($btrans),
                            "bmak"    => round($bmak),
                            "basr"    => round($basr),
                            "bpen"    => round($bpen),
                            "bast"    => round($bast),
                            "bspmi"    => round($bspmi),
                            "bmang"    => round($bmang),
                            "bpph"    => round($bpph),
                            "bnetasr"    => round($bnetasr),
                            "nett"      => round($bnet),
                            "bpotkop"      => round($bpotkop),
                            "bterima"      => round($bterima),
                        );
                        array_push(${"rekap_" . $paygroup[$pyg]},  ${"total" . $b->recid_bag});
                        /* --------- END CALCULATE TOTAL FOR SHEET REKAPITULASI GAJI --------- */
                    } // filter bagian
                } // filter department
                ${"totpaygroup" . $paygroup[$pyg]} = array(
                    "pglobal"    => round($pglobal),
                    "pshift1"    => round($pshift1),
                    "pshift2"    => round($pshift2),
                    "ptotshift"    => round($ptotshift),
                    "ptotushift"    => round($ptotushift),
                    "plbr1"    => round($plbr1),
                    "plbr2"    => round($plbr2),
                    "plbr3"    => round($plbr3),
                    "ptotlbr"    => round($ptotlbr),
                    "ptotulbr"    => round($ptotulbr),
                    "ppremi"    => round($ppremi),
                    "ptrans"    => round($ptrans),
                    "pmak"    => round($pmak),
                    "pasr"    => round($pasr),
                    "ppen"    => round($ppen),
                    "past"    => round($past),
                    "pspmi"    => round($ppspmi),
                    "pmang"    => round($pmang),
                    "ppph"    => round($ppph),
                    "pnetasr"    => round($pnetasr),
                    "pnet"      => round($pnet),
                    "ppotkop"      => round($ppotkop),
                    "pterima"      => round($pterima),
                );
                //   array_push( ${"totpaygroup" . $paygroup[$pyg]},  ${"tot_paygroup" .  $paygroup[$pyg]});
                // echo $paygroup[$pyg]." - ".$pnet."<br>";
                $tot_global = $tot_global + $pglobal;
                $tot_shift1 = $tot_shift1 + $pshift1;
                $tot_shift2 = $tot_shift2 + $pshift2;
                $tot_totshift = $tot_totshift + $ptotshift;
                $tot_ushift = $tot_ushift + $ptotushift;
                $tot_lbr1 = $tot_lbr1 + $plbr1;
                $tot_lbr2 = $tot_lbr2 + $plbr2;
                $tot_lbr3 = $tot_lbr3 + $plbr3;
                $tot_totlbr = $tot_totlbr + $ptotlbr;
                $tot_ulbr = $tot_ulbr + $ptotulbr;
                $tot_premi = $tot_premi + $ppremi;
                $tot_trans = $tot_trans + $ptrans;
                $tot_mak = $tot_mak + $pmak;
                $tot_asr = $tot_asr + $pasr;
                $tot_pensiun = $tot_pensiun + $ppen;
                $tot_astek = $tot_astek + $past;
                $tot_spmi = $tot_spmi + $ppspmi;
                $tot_mangkir = $tot_mangkir + $pmang;
                $tot_cpph = $tot_cpph + $ppph;
                $tot_netasr = $tot_netasr + $pnetasr;
                $tot_net = $tot_net + $pnet;
                $tot_potkop = $tot_potkop + $ppotkop;
                $tot_terima = $tot_terima + $pterima;
                // echo $tot_astek;
                /* --------- START LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $max_baris = $sheet->getHighestRow();
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':H' . $max_baris);
                $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
                $sheet->getStyle('A' . $max_baris)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('A' . $max_baris, 'TOTAL');
                $sheet->setCellValue('I' . $max_baris, $pglobal);
                $sheet->setCellValue('J' . $max_baris, $pshift1);
                $sheet->setCellValue('K' . $max_baris, $pshift2);
                $sheet->setCellValue('L' . $max_baris, $ptotshift);
                $sheet->setCellValue('M' . $max_baris, $ptotushift);
                $sheet->setCellValue('N' . $max_baris, $plbr1);
                $sheet->setCellValue('O' . $max_baris, $plbr2);
                $sheet->setCellValue('P' . $max_baris, $plbr3);
                $sheet->setCellValue('Q' . $max_baris, $ptotlbr);
                $sheet->setCellValue('R' . $max_baris, round($ptotulbr));
                $sheet->setCellValue('S' . $max_baris, round($ppremi));
                $sheet->setCellValue('T' . $max_baris, $ptrans);
                $sheet->setCellValue('U' . $max_baris, $pmak);
                $sheet->setCellValue('V' . $max_baris, round($pasr));
                $sheet->setCellValue('W' . $max_baris, round($ppen));
                $sheet->setCellValue('X' . $max_baris, round($past));
                $sheet->setCellValue('Y' . $max_baris, round($ppspmi));
                $sheet->setCellValue('Z' . $max_baris, round($pmang));
                $sheet->setCellValue('AA' . $max_baris, round($ppph));
                $sheet->setCellValue('AB' . $max_baris, round($pterima));
                $sheet->setCellValue('AC' . $max_baris, "");

                // $sheet->setCellValue('AB'.$max_baris, round($pnetasr));
                // $sheet->setCellValue('AC'.$max_baris, round($pnet));
                // $sheet->setCellValue('AD'.$max_baris, round($ppotkop));
                // $sheet->setCellValue('AE'.$max_baris, round($pterima));
                // $sheet->setCellValue('AF'.$max_baris, "");
                /* --------- END LOAD SUBTOTAL BY PAYGROUP TO TABLE--------- */
                $akhir_baris = $max_baris;
                $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris . ":AB" . $akhir_baris)
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                /* --------- START FOOTER APPROVAL --------- */
                $tgl = date('d M Y');
                $max_baris = $max_baris + 2;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Diperiksa & Disetujui Oleh');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Mengetahui');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Cimahi, ' . $tgl);

                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Finance');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'HC & GA Manager');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Pembuat Data Payroll');

                $max_baris = $max_baris + 3;
                $sheet->mergeCells('A' . $max_baris . ':C' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, 'Kisty Riagustina');
                $sheet->mergeCells('E' . $max_baris . ':G' . $max_baris);
                $sheet->setCellValue('E' . $max_baris, 'Diah Nur Kusumawardani');
                $sheet->mergeCells('I' . $max_baris . ':L' . $max_baris);
                $sheet->setCellValue('I' . $max_baris, 'Mega Oktaviani');
                /* --------- END FOOTER APPROVAL --------- */
                $sheet->setBreak('A' . $max_baris, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
            } // loop paygroup

            /* --------- START FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL) --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji1');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji1");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran THR Karyawan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Tahun : ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, '');
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;

            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('C' . $max_baris . ':C' . $max_bariss);
            $sheet->setCellValue('C' . $max_baris, 'Gaji Per Bulan');

            $sheet->mergeCells('D' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('D' . $max_baris, 'Premi Shift');
            $sheet->getStyle('D' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('D' . $max_bariss, 'Jml Jam1');
            $sheet->getStyle('E' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('E' . $max_bariss, 'Jml Jam 2');
            $sheet->getStyle('F' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('F' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('G' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('G' . $max_bariss, 'Total Premi shift');
            $sheet->getStyle('H' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_baris, 'Lbr1');
            $sheet->getStyle('H' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('H' . $max_bariss, 'Jml Jam');
            $sheet->getStyle('I' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('I' . $max_baris, 'Lbr2');
            $sheet->getStyle('J' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('J' . $max_baris, 'Lbr3');

            $sheet->mergeCells('K' . $max_baris . ':L' . $max_baris);
            $sheet->setCellValue('K' . $max_baris, 'Jumlah Lembur');
            $sheet->getStyle('K' . $max_bariss)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('K' . $max_bariss, 'Jml Jam');
            $sheet->setCellValue('L' . $max_bariss, 'Jml Upah');
            $sheet->mergeCells('M' . $max_baris . ':M' . $max_bariss);
            $sheet->getStyle('M' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('M' . $max_baris, 'Premi Hadir');
            $sheet->mergeCells('N' . $max_baris . ':N' . $max_bariss);
            $sheet->getStyle('N' . $max_baris)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('N' . $max_baris, 'Transport');
            $sheet->mergeCells('O' . $max_baris . ':O' . $max_bariss);
            $sheet->setCellValue('O' . $max_baris, 'Makan');
            $sheet->mergeCells('P' . $max_baris . ':P' . $max_bariss);
            $sheet->setCellValue('P' . $max_baris, 'Asuransi');

            $sheet->mergeCells('Q' . $max_baris . ':U' . $max_baris);
            $sheet->setCellValue('Q' . $max_baris, 'Potongan');
            $sheet->setCellValue('Q' . $max_bariss, 'Pensiun');
            $sheet->setCellValue('R' . $max_bariss, 'Astek');
            $sheet->setCellValue('S' . $max_bariss, 'SPMI');
            $sheet->setCellValue('T' . $max_bariss, 'Mangkir');
            $sheet->setCellValue('U' . $max_bariss, 'PPH21');

            // $sheet->mergeCells('V'.$max_baris.':Y'.$max_baris);
            $sheet->setCellValue('V' . $max_baris, 'Bersih');
            $sheet->setCellValue('V' . $max_bariss, 'Jml Terima');
            // $sheet->setCellValue('V'.$max_bariss, 'Net + Asr');
            // $sheet->setCellValue('W'.$max_bariss, 'Net');       
            // $sheet->setCellValue('X'.$max_bariss, 'Pot. Kopkar');       
            // $sheet->setCellValue('Y'.$max_bariss, 'Jml Terima');       
            /* --------- END HEADER TABLE  --------- */
            $max_baris = $max_baris + 1;
            /* --------- START LOAD DATA TO TABLE  --------- */
            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                $no = 0;
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':W' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, $paygroup[$pg2]);
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                    $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["global"]);
                    $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift1"]);
                    $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bshift2"]);
                    $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotshift"]);
                    $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotushift"]);
                    $sheet->setCellValue('H' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr1"]);
                    $sheet->setCellValue('I' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr2"]);
                    $sheet->setCellValue('J' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["blbr3"]);
                    $sheet->setCellValue('K' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotlbr"]);
                    $sheet->setCellValue('L' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btotulbr"]);
                    $sheet->setCellValue('M' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpremi"]);
                    $sheet->setCellValue('N' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["btrans"]);
                    $sheet->setCellValue('O' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmak"]);
                    $sheet->setCellValue('P' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["basr"]);
                    $sheet->setCellValue('Q' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpen"]);
                    $sheet->setCellValue('R' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bast"]);
                    $sheet->setCellValue('S' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bspmi"]);
                    $sheet->setCellValue('T' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bmang"]);
                    $sheet->setCellValue('U' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpph"]);
                    $sheet->setCellValue('V' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    // $sheet->setCellValue('V'.$max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    // $sheet->setCellValue('W'.$max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["nett"]);
                    // $sheet->setCellValue('X'.$max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bpotkop"]);
                    // $sheet->setCellValue('Y'.$max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                }
                $max_baris = $max_baris + 1;
                $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
                $sheet->setCellValue('A' . $max_baris, "TOTAL " . $paygroup[$pg2]);
                $sheet->setCellValue('C' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pglobal"]);
                $sheet->setCellValue('D' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift1"]);
                $sheet->setCellValue('E' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pshift2"]);
                $sheet->setCellValue('F' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotshift"]);
                $sheet->setCellValue('G' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotushift"]);
                $sheet->setCellValue('H' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr1"]);
                $sheet->setCellValue('I' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr2"]);
                $sheet->setCellValue('J' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["plbr3"]);
                $sheet->setCellValue('K' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotlbr"]);
                $sheet->setCellValue('L' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptotulbr"]);
                $sheet->setCellValue('M' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppremi"]);
                $sheet->setCellValue('N' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ptrans"]);
                $sheet->setCellValue('O' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmak"]);
                $sheet->setCellValue('P' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pasr"]);
                $sheet->setCellValue('Q' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppen"]);
                $sheet->setCellValue('R' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["past"]);
                $sheet->setCellValue('S' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pspmi"]);
                $sheet->setCellValue('T' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pmang"]);
                $sheet->setCellValue('U' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppph"]);
                $sheet->setCellValue('V' . $max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pterima"]);
                // $sheet->setCellValue('V'.$max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnetasr"]);
                // $sheet->setCellValue('W'.$max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pnet"]);
                // $sheet->setCellValue('X'.$max_baris, ${"totpaygroup" . $paygroup[$pg2]}["ppotkop"]);
                // $sheet->setCellValue('Y'.$max_baris, ${"totpaygroup" . $paygroup[$pg2]}["pterima"]);
            }
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':B' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, "GRAND TOTAL");
            $sheet->setCellValue('C' . $max_baris, $tot_global);
            $sheet->setCellValue('D' . $max_baris, $tot_shift1);
            $sheet->setCellValue('E' . $max_baris, $tot_shift2);
            $sheet->setCellValue('F' . $max_baris, $tot_totshift);
            $sheet->setCellValue('G' . $max_baris, $tot_ushift);
            $sheet->setCellValue('H' . $max_baris, $tot_lbr1);
            $sheet->setCellValue('I' . $max_baris, $tot_lbr2);
            $sheet->setCellValue('J' . $max_baris, $tot_lbr3);
            $sheet->setCellValue('K' . $max_baris, $tot_totlbr);
            $sheet->setCellValue('L' . $max_baris, $tot_ulbr);
            $sheet->setCellValue('M' . $max_baris, $tot_premi);
            $sheet->setCellValue('N' . $max_baris, $tot_trans);
            $sheet->setCellValue('O' . $max_baris, $tot_mak);
            $sheet->setCellValue('P' . $max_baris, $tot_asr);
            $sheet->setCellValue('Q' . $max_baris, $tot_pensiun);
            $sheet->setCellValue('R' . $max_baris, $tot_astek);
            $sheet->setCellValue('S' . $max_baris, $tot_spmi);
            $sheet->setCellValue('T' . $max_baris, $tot_mangkir);
            $sheet->setCellValue('U' . $max_baris, $tot_cpph);
            $sheet->setCellValue('V' . $max_baris, round($tot_terima));
            // $sheet->setCellValue('V'.$max_baris, $tot_netasr);
            // $sheet->setCellValue('W'.$max_baris, $tot_net);
            // $sheet->setCellValue('X'.$max_baris, $tot_potkop);
            // $sheet->setCellValue('Y'.$max_baris, round($tot_terima));
            /* --------- END LOAD DATA TO TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":V" . $akhir_baris2)
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI 1 (GRAND TOTAL)  --------- */

            /* --------- START FOR SHEET REKAPITULASI GAJI 2 --------- */
            $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap Gaji2');
            $spreadsheet->addSheet($myWorkSheet, $pgx + 1);
            $spreadsheet->setActiveSheetIndexByName("Rekap Gaji2");
            $sheet = $spreadsheet->getActiveSheet();
            $max_baris = $sheet->getHighestRow();

            /* --------- START TITLE WORKBOOK  --------- */
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Rekap Pembayaran THR Karyawan');
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, 'Tahun : ' . $tahun);
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':G' . $max_baris);
            $sheet->setCellValue('A' . $max_baris, '');
            /* --------- END TITLE WORKBOOK  --------- */

            /* --------- START HEADER TABLE  --------- */
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(20);
            $max_baris = $max_baris + 2;
            $awal_baris2 = $max_baris;
            $max_bariss = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':A' . $max_bariss);
            $sheet->getStyle('A' . $max_baris . ':A' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('A' . $max_baris, 'No');
            $sheet->mergeCells('B' . $max_baris . ':B' . $max_bariss);
            $sheet->getStyle('B' . $max_baris . ':B' . $max_bariss)->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('B' . $max_baris, 'Nama Bagian');
            $sheet->mergeCells('C' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('C')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_baris, 'Kelompok Gaji');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('C' . $max_bariss, 'Direct');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('D' . $max_bariss, 'Indirect');
            $sheet->getStyle('D')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('E' . $max_bariss, 'Admin');
            $sheet->getStyle('E')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('F' . $max_bariss, 'Penjualan');
            $sheet->getStyle('F')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->mergeCells('G' . $max_baris . ':G' . $max_bariss);
            $sheet->getStyle('G')->getAlignment()->setHorizontal('center')->setVertical('center');
            $sheet->setCellValue('G' . $max_baris, 'Total');
            /* --------- END HEADER TABLE  --------- */

            /* --------- START LOAD DATA TO TABLE  --------- */
            $no = 0;
            // foreach ($paygroup->result() as $pg2) {

            for ($pg2 = 0; $pg2 < count($paygroup); $pg2++) {
                // print_r(${"rekap_" . $pg2->pay_group});
                // echo ${"rekap_" . $pg2->pay_group}[0]["bagian"];
                $max_baris = $sheet->getHighestRow();
                $pay = count(${"rekap_" . $paygroup[$pg2]});
                for ($x = 0; $x < $pay; $x++) {
                    // echo $paygroup[$pg2]."<br>";
                    $no = $no + 1;
                    $max_baris = $max_baris + 1;
                    $sheet->setCellValue('A' . $max_baris, $no);
                    if ($paygroup[$pg2] == "Direct") {
                        // echo ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"];
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('C' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    } else if ($paygroup[$pg2] == "Indirect") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('D' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    } else if ($paygroup[$pg2] == "Admin") {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('E' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    } else {
                        $sheet->setCellValue('B' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bagian"]);
                        $sheet->setCellValue('F' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                        $sheet->setCellValue('G' . $max_baris, ${"rekap_" . $paygroup[$pg2]}[$x]["bterima"]);
                    }
                }
            }
            /* --------- END LOAD DATA TO TABLE  --------- */

            /* --------- START TOTAL TABLE  --------- */
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Total Pengupahan');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan Koprasi');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_potkop));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Jumlah Transfer');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_net));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':F' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Dana S.P.M.I');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_spmi));

            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('A' . $max_baris, 'Potongan');
            $sheet->getStyle('A' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('A' . $max_baris)->getFont()->setBold(true);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Astek');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_astek));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pensiun');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_pensiun));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Pph 21');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_cpph));
            $max_baris = $max_baris + 1;
            $sheet->mergeCells('A' . $max_baris . ':E' . $max_baris);
            $sheet->getStyle('F' . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
            $sheet->getStyle('F' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('F' . $max_baris, 'Mangkir');
            $sheet->getStyle('G' . $max_baris)->getFont()->setBold(true);
            $sheet->setCellValue('G' . $max_baris, round($tot_mangkir));

            /* --------- END TOTAL TABLE  --------- */
            $akhir_baris2 = $max_baris;
            $spreadsheet->getActiveSheet()->getStyle('A' . $awal_baris2 . ":G" . $akhir_baris2)
                ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /* --------- END FOR SHEET REKAPITULASI GAJI2  --------- */
            $writer = new Xlsx($spreadsheet);
            $filename = 'THR Karyawan ' . $tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    public function download_master_thr()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $data['upah'] = $this->m_lembur->data_thr();
            $data['tahun'] = date('Y');
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/master_thr', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function r_struk_thr()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $usr = $this->session->userdata('kar_id');
            $data['cek_usr'] = $this->m_hris->cek_usr($usr);
            $data['paygroup'] = $this->m_hris->paygroup_emp();
            $data['department'] = $this->m_hris->department_view();
            $this->load->view('layout/a_header');
            $this->load->view('layout/menu_super', $data);
            $this->load->view('upah/report/r_struk_thr', $data);
            $this->load->view('layout/a_footer');
        } else {
            redirect('Auth/keluar');
        }
    }

    function print_struk_thr()
    {
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in == 1) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            /* ----- START SETTING PAPER -------------------- */
            $spreadsheet->getDefaultStyle()->getFont()->setName('calibri');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
            $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.75);
            $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.25);
            $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0.75);
            /*  ------------ END SETTING PAPER ---------------- */
            $sheet->getDefaultRowDimension()->setRowHeight(8.25);


            $batas_kolom = array(
                array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'),
                array('L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'),
                array('W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'),
                array('AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR'),
                array('AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC'),
                array('BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN'),
            );

            $fpaygroup = array();
            $paygroup = $this->input->post('divisi');
            $department = $this->input->post('departement');
            $bagian = $this->input->post('bagian10');
            $karyawan = $this->input->post('karyawan');
            // echo print_r($karyawan);

            if (!empty($paygroup)) {
                for ($i = 0; $i < count($paygroup); $i++) {
                    array_push($fpaygroup, $paygroup[$i]);
                }
            } else {
                $paygroup = array();
                $pg = $this->m_hris->paygroup_emp();
                foreach ($pg->result() as $dv) {
                    array_push($fpaygroup, $dv->pay_group);
                    array_push($paygroup, $dv->pay_group);
                }
            }
            // print_r($fpaygroup);

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
            // echo $fdepartment;


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


            $karyawan = $this->input->post('karyawan');
            if (!empty($karyawan)) {
                $fkaryawan = "";
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
                $fkaryawan = "-";
            }

            // if (!empty($karyawan)) {
            //     $fkaryawan = "";
            //     // $text = "$text and b.recid_bag = '$bagian'";
            //     $cnt = count($karyawan);
            //     $fkaryawan .= " and (";
            //     if ($cnt == 1) {
            //         $fkaryawan .= "k.recid_karyawan = '$karyawan[0]'";
            //     } else {
            //         for ($i = 0; $i < $cnt; $i++) {
            //             if ($i == $cnt - 1) {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]'";
            //             } else {
            //                 $fkaryawan .= "k.recid_karyawan = '$karyawan[$i]' or ";
            //             }
            //         }
            //     }
            //     $fkaryawan .= ")";
            // } else {
            //     $fkaryawan = $fkaryawan;
            // }
            // echo $fkaryawan."<br>";

            $idkaryawan = array();
            for ($pyg = 0; $pyg < count($fpaygroup); $pyg++) {
                if ($fdepartment != '') {
                    // echo "ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                } else {
                    // echo "tidak ada filter department <br>";
                    $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                }
                foreach ($dept->result() as $dp) {
                    if ($fbagian != '') {
                        // echo "ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    } else {
                        // echo "tidak ada filter bagian <br>";
                        $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.recid_bag from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                    }
                    foreach ($bag->result() as $b) {
                        if ($fkaryawan != '-') {
                            // echo "ada filter karyawan <br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah_thr u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        } else {
                            // echo "tidak ada filter karyawan <br>";
                            // echo $paygroup[$pyg]."-".$dp->nama_department."-".$b->indeks_hr."<br>";
                            $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn from upah_thr u join karyawan k on k.recid_karyawan = u.recid_karyawan join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and b.indeks_hr = '$b->indeks_hr'");
                            // $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$b->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                        }
                        foreach ($karyawan->result() as $lk) {
                            array_push($idkaryawan, $lk->recid_karyawan);
                        } // looping karyawan
                    } // looping bagian
                } // looping department
            } // looping pay group

            //$karyawan = $this->m_lembur->cek_print();
            $banyak = count($idkaryawan);
            $jejer = $banyak / 6;
            $nik = array();
            $nama_karyawan = array();
            $bagian = array();
            $gapok = array();
            $tjabatan = array();
            $tjenpek = array();
            $tmasker = array();
            $tjml = array();
            $global = array();

            $lbr1 = array();
            $rp_lbr1 = array();
            $lbr2 = array();
            $rp_lbr2 = array();
            $lbr3 = array();
            $rp_lbr3 = array();
            $ulbr1 = array();
            $ulbr2 = array();
            $ulbr3 = array();
            $jlbr = array();

            $slbr1 = array();
            $slbr2 = array();
            $uslbr1 = array();
            $uslbr2 = array();

            $premi = array();
            $transport = array();
            $makan = array();
            $asuransi = array();

            $bruto = array();

            $astek = array();
            $spmi = array();
            $pensiun = array();
            $pph = array();
            $mangkir = array();
            $jpotongan = array();

            $netasr = array();
            $net = array();
            $pot_kopkar = array();
            $jml_terima = array();
            $bulanke = 0;
            $tahunke = 0;



            for ($idk = 0; $idk < count($idkaryawan); $idk++) {

                $karyawan = $this->m_lembur->print_struk_thr($idkaryawan[$idk]);
                foreach ($karyawan->result() as $k) {
                    // $bulan = $k->bulan;
                    // $bulanke = $k->bulan;
                    // $tahun = $k->tahun;
                    $tahunke = $k->tahun;
                    // $awal =  date("d-m-Y", strtotime($k->periode_awal));
                    // $akhir =  date("d-m-Y", strtotime($k->periode_akhir));
                    // $periode = $awal." s/d ".$akhir;
                    $t_masker = $k->rp_masker;
                    $t_jml = $k->tjbtn + $k->tjenpek + $t_masker;
                    $rp_slbr1 = $k->rp_slbr1;
                    $rp_slbr2 = $k->rp_slbr2;
                    $u_lbr1 = $k->lbr1 * $k->rp_lbr1;
                    $u_lbr2 = $k->lbr2 * $k->rp_lbr2;
                    $u_lbr3 = $k->lbr3 * $k->rp_lbr3;
                    $j_lbr = $u_lbr1 + $u_lbr2 + $u_lbr3;
                    $u_slbr1 = $k->slbr1 * $rp_slbr1;
                    $u_slbr2 = $k->slbr2 * $rp_slbr2;
                    $totpph = $k->pph21_1 + $k->pph21_2 + $k->pph21_3;
                    $j_potong = $k->astek + $k->spmi + $k->pensiun + $totpph + $k->mangkir;
                    array_push($nik, $k->nik);
                    array_push($nama_karyawan, $k->nama_karyawan);
                    array_push($bagian, $k->indeks_hr);
                    array_push($gapok, $k->upokok);
                    array_push($tjabatan, $k->tjbtn);
                    array_push($tjenpek, $k->tjenpek);
                    array_push($tmasker, $t_masker);
                    array_push($tjml, $t_jml);
                    array_push($global, $k->uglobal);
                    array_push($lbr1, $k->lbr1);
                    array_push($rp_lbr1, $k->rp_lbr1);
                    array_push($lbr2, $k->lbr2);
                    array_push($rp_lbr2, $k->rp_lbr2);
                    array_push($lbr3, $k->lbr3);
                    array_push($rp_lbr3, $k->rp_lbr3);
                    array_push($ulbr1,  $u_lbr1);
                    array_push($ulbr2,  $u_lbr2);
                    array_push($ulbr3,  $u_lbr3);
                    array_push($jlbr,  $j_lbr);
                    array_push($slbr1, $k->slbr1);
                    array_push($uslbr1, $u_slbr1);
                    array_push($slbr2, $k->slbr2);
                    array_push($uslbr2, $u_slbr2);
                    array_push($premi, $k->premi);
                    array_push($transport, $k->uang_transport);
                    array_push($makan, $k->uang_makan);
                    array_push($asuransi, $k->asuransi);
                    array_push($bruto, $k->bruto);
                    array_push($astek, $k->astek);
                    array_push($spmi, $k->spmi);
                    array_push($pensiun, $k->pensiun);
                    array_push($pph, $totpph);
                    array_push($mangkir, $k->mangkir);
                    array_push($jpotongan, $j_potong);
                    // array_push($netasr, $k->netasr);
                    // array_push($net, $k->netto);
                    // array_push($pot_kopkar, $k->pot_kopkar);
                    array_push($jml_terima, $k->jml_terima);
                } // loop data karyawan
            } // loop for list_karyawan

            // $month = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            // $nama_bulan = $month[$bulanke-1];
            $tahun = date('Y');
            $untuk = $tahun;

            $cnt = 0;
            $indeknya = 0;
            $bts_awal = 1;
            $bts_halaman = 0; //tiap 2 jejer + pagebreak

            for ($g = 0; $g < $jejer; $g++) {
                $max_baris = $bts_awal;
                $batas_baris = $bts_awal + 21;
                $bts_halaman = $bts_halaman + 1;
                if ($bts_halaman > 2) {
                    $sheet->setBreak('A' . $max_baris - 2, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                    $bts_halaman = 0;
                }
                // echo "group ".$g."<br>";
                if ($banyak >= 6) {
                    // echo "bayaknya :".$banyak."<br>";
                    //looping 6x
                    $bts_indek = $indeknya + 6;
                    for ($i = 0; $i < 6; $i++) {
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getLeft()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP THR KARYAWAN');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya +1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'C.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'LEMBUR');
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '1.');
                        // $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $lbr1[$indeknya].' JAM x '.$rp_lbr1[$indeknya]);
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr1[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '2.');
                        // $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $lbr2[$indeknya].' JAM x '.$rp_lbr2[$indeknya]." = ");
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr2[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '3.');
                        // $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][2].$max_baris,  $lbr3[$indeknya].' JAM x '.$rp_lbr3[$indeknya]." = ");
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr3[$indeknya]));
                        // $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        // $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JUMLAH LEMBUR');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($jlbr[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'D.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PREMI SHIFT');
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '1.');
                        // $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $slbr1[$indeknya].' JAM x '.$rp_slbr1);
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($uslbr1[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '2.');
                        // $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $slbr2[$indeknya].' JAM x '.$rp_slbr2);
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($uslbr2[$indeknya]));
                        // $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        // $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JUMLAH PREMI SHIFT');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'E.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PREMI HADIR');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($premi[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'F.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'TRANSPORT');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($transport[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'G.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'UANG MAKAN');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($makan[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'H.');
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'ASURANSI');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($asuransi[$indeknya]));
                        // $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        // $sheet->getStyle($batas_kolom[$i][8].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        // $sheet->setCellValue($batas_kolom[$i][8].$max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($global[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'I.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'ASTEK');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($astek[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'SPMI');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($spmi[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PENSIUN');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($pensiun[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'MANGKIR');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($mangkir[$indeknya]));
                        // $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        // $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pph[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'NET + ASURANSI');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($netasr[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH THR DITERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][2].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'KASIR');
                        // $sheet->setCellValue($batas_kolom[$i][5].$max_baris, 'PENERIMA');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(5);

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'POT. KOPKAR');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($pot_kopkar[$indeknya]));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JML TERIMA');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][10] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }

                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                    $banyak = $banyak - 6;
                } else {
                    //looping sisanya
                    //    echo "bayaknya :".$banyak."<br>";
                    for ($i = 0; $i < $banyak; $i++) {
                        // echo "- ".$indeknya."<br>";
                        // echo "$nama_karyawan[$indeknya]<br>";
                        $sheet->getDefaultRowDimension()->setRowHeight(8.25);
                        $sheet->getColumnDimension($batas_kolom[$i][0])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][1])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][2])->setWidth(6);
                        $sheet->getColumnDimension($batas_kolom[$i][3])->setWidth(3);
                        $sheet->getColumnDimension($batas_kolom[$i][4])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][5])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][6])->setWidth(8.5);
                        $sheet->getColumnDimension($batas_kolom[$i][7])->setWidth(9);
                        $sheet->getColumnDimension($batas_kolom[$i][8])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][9])->setWidth(2);
                        $sheet->getColumnDimension($batas_kolom[$i][10])->setWidth(2);
                        $sheet->getStyle($batas_kolom[$i][5])->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][6])->getAlignment()->setHorizontal('right')->setVertical('center');
                        //    $sheet->getStyle($batas_kolom[$i][7])->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][8])->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->getStyle($batas_kolom[$i][0] . $bts_awal . ':' . $batas_kolom[$i][8] . ($batas_baris - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        if ($i < 5) {
                            $sheet->getStyle($batas_kolom[$i][10] . $bts_awal . ':' . $batas_kolom[$i][10] . ($batas_baris + 2))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }


                        $max_baris = $bts_awal;

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'PT. CHITOSE INTERNASIONAL TBK');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'SLIP THR KARYAWAN TAHUN ' . $untuk);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][0].$max_baris, ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NIK');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nik[$indeknya] . " - " . ($indeknya + 1));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'NAMA');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $nama_karyawan[$indeknya]);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'BAGIAN');
                        $sheet->setCellValue($batas_kolom[$i][3] . $max_baris, ':');
                        $sheet->mergeCells($batas_kolom[$i][4] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][4] . $max_baris, $bagian[$indeknya]);
                        $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));



                        $max_baris = $max_baris + 1;
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'A.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'GAJI POKOK');
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('right')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($gapok[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'B.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'TUNJANGAN');
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JABATAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjabatan[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'JENIS PEKERJAAN');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tjenpek[$indeknya]));
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][2] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][2] . $max_baris, 'MASA KERJA');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($tmasker[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][6] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH TUNJANGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($tjml[$indeknya]));

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'C.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'LEMBUR');
                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '1.');
                        //     $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $lbr1[$indeknya].' JAM x '.$rp_lbr1[$indeknya]);
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        //     $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr1[$indeknya]));
                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '2.');
                        //     $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $lbr2[$indeknya].' JAM x '.$rp_lbr2[$indeknya]);
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        //      $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr2[$indeknya]));
                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '3.');
                        //     $sheet->setCellValue($batas_kolom[$i][2].$max_baris,  $lbr3[$indeknya].' JAM x '.$rp_lbr3[$indeknya]);
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        //      $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($ulbr3[$indeknya]));
                        //     $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        //     $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JUMLAH LEMBUR');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($jlbr[$indeknya]));

                        //      $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'D.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PREMI SHIFT');
                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '1.');
                        //     $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $slbr1[$indeknya].' JAM x '.$rp_slbr1);
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, number_format($uslbr1[$indeknya]));
                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, '2.');
                        //     $sheet->mergeCells($batas_kolom[$i][2].$max_baris.':'.$batas_kolom[$i][4].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][2].$max_baris, $slbr2[$indeknya].' JAM x '.$rp_slbr2);
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, "=");
                        //     $sheet->setCellValue($batas_kolom[$i][5].$max_baris, number_format($uslbr2[$indeknya]));
                        //     $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        //     $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JUMLAH PREMI SHIFT');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($uslbr1[$indeknya] + $uslbr2[$indeknya]));

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'E.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PREMI HADIR');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($premi[$indeknya]));

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'F.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'TRANSPORT');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($transport[$indeknya]));

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'G.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'UANG MAKAN');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($makan[$indeknya]));

                        //     $max_baris = $max_baris + 1;
                        //     $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        //     $sheet->setCellValue($batas_kolom[$i][0].$max_baris, 'H.');
                        //     $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        //     $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'ASURANSI');
                        //     $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($asuransi[$indeknya]));
                        //      $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        //    $sheet->getStyle($batas_kolom[$i][8].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        //     $sheet->setCellValue($batas_kolom[$i][8].$max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH GAJI');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($global[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->setCellValue($batas_kolom[$i][0] . $max_baris, 'C.');
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'POTONGAN');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'ASTEK');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($astek[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'SPMI');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($spmi[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'PENSIUN');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($pensiun[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][5] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'PPH21');
                        $sheet->setCellValue($batas_kolom[$i][6] . $max_baris, number_format($pph[$indeknya]));
                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][5].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'MANGKIR');
                        // $sheet->setCellValue($batas_kolom[$i][6].$max_baris, number_format($mangkir[$indeknya]));
                        // $sheet->getStyle($batas_kolom[$i][6].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        // $sheet->getStyle($batas_kolom[$i][7].$max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, '+');

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH POTONGAN');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($pph[$indeknya]));
                        $sheet->getStyle($batas_kolom[$i][7] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));
                        $sheet->getStyle($batas_kolom[$i][8] . $max_baris)->getAlignment()->setHorizontal('left')->setVertical('center');
                        $sheet->setCellValue($batas_kolom[$i][8] . $max_baris, '-');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'NET ASURANSI');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, "");
                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][6] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'JUMLAH THR DITERIMA');
                        $sheet->setCellValue($batas_kolom[$i][7] . $max_baris, number_format($jml_terima[$indeknya]));

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(5);
                        $sheet->mergeCells($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][8] . $max_baris);

                        $max_baris = $max_baris + 1;
                        $sheet->getRowDimension($max_baris)->setRowHeight(8.25);
                        $sheet->mergeCells($batas_kolom[$i][1] . $max_baris . ':' . $batas_kolom[$i][2] . $max_baris);
                        $sheet->setCellValue($batas_kolom[$i][1] . $max_baris, 'KASIR');
                        $sheet->setCellValue($batas_kolom[$i][5] . $max_baris, 'PENERIMA');

                        // $max_baris = $max_baris + 1;
                        // $sheet->getStyle($batas_kolom[$i][0].$max_baris.':'.$batas_kolom[$i][8].$max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('00000000'));

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(5); 

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'POT. KOPKAR');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, "");

                        // $max_baris = $max_baris + 1;
                        // $sheet->getRowDimension($max_baris)->setRowHeight(8.25); 
                        // $sheet->mergeCells($batas_kolom[$i][1].$max_baris.':'.$batas_kolom[$i][6].$max_baris);
                        // $sheet->setCellValue($batas_kolom[$i][1].$max_baris, 'JML TERIMA');
                        // $sheet->setCellValue($batas_kolom[$i][7].$max_baris, number_format($jml_terima[$indeknya]));

                        if ($i < 5) {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        } else {
                            $max_baris = $max_baris + 1;
                            $sheet->getStyle($batas_kolom[$i][0] . $max_baris . ':' . $batas_kolom[$i][9] . $max_baris)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DASHED)->setColor(new Color('00000000'));
                        }
                        // echo $batas_kolom[$i][0].$bts_awal."<br>";
                        $indeknya = $indeknya + 1;
                    }
                }

                $bts_awal = $batas_baris + 2;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'Struk Gaji Karyawan' . $untuk;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } else {
            redirect('Auth/keluar');
        }
    }
}

<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Gaji Karyawan Bulan <?php echo $bulan . " Tahun " . $tahun ?></h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        Periode <?php echo $tgl_mulai . " s/d " . $tgl_akhir ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="x_content">
                        <!--Add content to the page ...-->
                        <!-- Content Table -->
                        <div class="table-responsive">
                            <table id="t_upah" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan='2'>NO</th>
                                        <th rowspan='2'>NIK</th>
                                        <th rowspan='2'>NAMA</th>
                                        <th rowspan='2'>MASA KERJA</th>
                                        <th rowspan='2'>STATUS / GOL</th>
                                        <th rowspan='2'>U.POKOK</th>
                                        <th colspan='3'>Tunjangan</th>
                                        <th rowspan='2'>U.GLOBAL</th>
                                        <th colspan='4'>PREMI SHIFT</th>
                                        <th>LBR1</th>
                                        <th>LBR2</th>
                                        <th>LBR3</th>
                                        <th colspan='2'>JUMLAH LEMBUR</th>
                                        <th rowspan='2'>PREMI HADIR</th>
                                        <th rowspan='2'>TRANSPORT</th>
                                        <th rowspan='2'>MAKAN</th>
                                        <th rowspan='2'>ASURANSI</th>
                                        <th colspan='5'>POTONGAN</th>
                                        <th colspan='5'>BERSIH</th>
                                    </tr>
                                    <tr>
                                        <th>JABATAN</th>
                                        <th>JENPEK</th>
                                        <th>MASKER</th>
                                        <th>SHIFT 1&2</th>
                                        <th>SHIFT 3</th>
                                        <th>JML SHIFT</th>
                                        <th>TOT PREMI SHIFT</th>
                                        <th>JML JAM</th>
                                        <th>JML JAM</th>
                                        <th>JML JAM</th>
                                        <th>JML JAM</th>
                                        <th>JML UPAH</th>
                                        <th>PENSIUN</th>
                                        <th>ASTEK</th>
                                        <th>SPMI</th>
                                        <th>MANGKIR</th>
                                        <th>PPH21</th>
                                        <th>NETT + ASR</th>
                                        <th>RETUR PPH</th>
                                        <th>NETT</th>
                                        <th>POT. KOPKAR</th>
                                        <th>JML TERIMA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
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

                                    for ($pyg = 0; $pyg < count($paygroup); $pyg++) { ?>
                                        <tr>
                                            <td colspan="33">Paygroup : <?php echo $paygroup[$pyg] ?></td>
                                            <?php
                                            for ($m = 0; $m < 32; $m++) { ?>
                                                <td style="display:none"></td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        // $dept = $this->m_hris->dept_by_paygroup2($paygroup[$pyg],$fdepartment);
                                        if ($fdepartment != '') {
                                            $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by d.nama_department asc");
                                        } else {
                                            $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by d.nama_department asc");
                                        }
                                        foreach ($dept->result() as $dp) { ?>
                                            <tr>
                                                <td colspan="33">Department : <?php echo $dp->nama_department ?></td>
                                                <?php
                                                for ($m = 0; $m < 32; $m++) { ?>
                                                    <td style="display:none"></td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                            if ($fbagian != '') {
                                                $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by b.indeks_hr asc");
                                            } else {
                                                $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by b.indeks_hr asc");
                                            }

                                            foreach ($bag->result() as $bag) { ?>
                                                <tr>
                                                    <td colspan="33">Bagian : <?php echo $bag->indeks_hr . "( " . $bag->gl_acc . " - " . $bag->cost_center . " )"; ?></td>
                                                    <?php
                                                    for ($m = 0; $m < 32; $m++) { ?>
                                                        <td style="display:none"></td>
                                                    <?php } ?>
                                                </tr>

                                                <?php
                                                if ($fkaryawan != '') {
                                                    $karyawan = $this->db->query("SELECT *, j.note as sts_jbtn, j.tingkatan, b.indeks_hr, b.pay_group, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$bag->indeks_hr' $fkaryawan and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                                                } else {
                                                    $karyawan =  $this->db->query("SELECT *, j.note as sts_jbtn, j.tingkatan, b.indeks_hr, b.pay_group, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department' and b.indeks_hr = '$bag->indeks_hr' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                                                }

                                                $no = 0;
                                                foreach ($karyawan->result() as $k) {
                                                    $recid_karyawan = $k->recid_karyawan;
                                                    $nik = $k->nik;
                                                    $nama_karyawan = $k->nama_karyawan;
                                                    $bagian = $k->indeks_hr;
                                                    $jabatan = $k->indeks_jabatan;
                                                    $tingkatan = $k->tingkatan;
                                                    $uph_pokok = $k->gapok;
                                                    $t_jbtn = $k->t_jabatan;
                                                    $t_prestasi = $k->t_prestasi;
                                                    $t_jenpek = $k->t_jen_pek;
                                                    $penempatan = $k->penempatan;
                                                    $sts_jabatan = $k->sts_jabatan;
                                                    // echo $penempatan;
                                                    $trakhir = date("Y-m-t", strtotime($tgl_akhir));
                                                    // $tgl_mulai = "2023-03-01";
                                                    $diff  = date_diff(date_create($k->tgl_m_kerja), date_create($trakhir));
                                                    // $diff  = date_diff(date_create($k->tgl_m_kerja), date_create());
                                                    $masker_tahun = $diff->format('%y');
                                                    // echo $trakhir." - ".$tgl_mulai." = ".$masker_tahun;
                                                    // echo "masa kerja : $masker_tahun<br>";
                                                    // if($k->tingkatan == 1)
                                                    //     {
                                                    //     if($masker_tahun < 1){
                                                    //         $uph_pokok = $k->gapok;
                                                    //     }else if($masker_tahun >=1 and $masker_tahun < 5)
                                                    //     {
                                                    //         $uph_pokok = $k->gapok + 10000;
                                                    //     }else if($masker_tahun >=5 and $masker_tahun <10)
                                                    //     {
                                                    //         $uph_pokok = $k->gapok + 20000;
                                                    //     }else if($masker_tahun >=10 and $masker_tahun <15)
                                                    //     {
                                                    //         $uph_pokok = $k->gapok + 30000;
                                                    //     }else if($masker_tahun >=15 and $masker_tahun <20)
                                                    //     {
                                                    //         $uph_pokok = $k->gapok + 40000;
                                                    //     }else{
                                                    //         $uph_pokok = $k->gapok + 50000;
                                                    //     }
                                                    //     }else{
                                                    //         $uph_pokok = $k->gapok;
                                                    //     }
                                                    $tahun = date('Y');
                                                    $thn_upah = $this->m_hris->tahun_upah();
                                                    foreach ($thn_upah->result() as $th) {
                                                        if ($th->tahun == date('Y')) {
                                                            $tahun = date('Y');
                                                        } else {
                                                            $tahun = $th->tahun;
                                                        }
                                                    }
                                                    // echo $tahun;
                                                    echo $sts_jabatan;
                                                    if ($tingkatan == 1) {
                                                        if ($penempatan == 'Jakarta') {
                                                            $gapok_baru = $this->m_hris->gapok_masker_jkt($tingkatan, $masker_tahun, $tahun);
                                                            foreach ($gapok_baru->result() as $gp2) {
                                                                if ($sts_jabatan == "Advisor") {
                                                                    $uph_pokok = (80 / 100) * $gp2->nilai;
                                                                } else {
                                                                    $uph_pokok = $gp2->nilai;
                                                                }
                                                            }
                                                        } else {
                                                            $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun);
                                                            foreach ($gapok_baru->result() as $gp2) {
                                                                $uph_pokok = $gp2->nilai;
                                                            }
                                                        }
                                                    } else {
                                                        // pa nofi advisor gajiikut ke tahun 2024
                                                        if ($sts_jabatan == "Advisor") {
                                                            $gapok_baru = $this->m_hris->gapok($tingkatan, 2024);
                                                            foreach ($gapok_baru->result() as $gp2) {
                                                                $uph_pokok = (80 / 100) * $gp2->nilai;
                                                                // echo "$gp2->nilai";
                                                            }
                                                        } else {
                                                            $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun);
                                                            foreach ($gapok_baru->result() as $gp2) {
                                                                $uph_pokok = $gp2->nilai;
                                                                // echo "$gp2->nilai";
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
                                                            echo $t2->nilai;
                                                        } else {
                                                            $tjbtn2 = $this->m_hris->tjabatan($tingkatan, $tahun);
                                                            foreach ($tjbtn2->result() as $t2) {
                                                                $t_jbtn = $t2->nilai;
                                                            }
                                                            echo $t2->nilai;
                                                        }
                                                    }
                                                    $t_masker = $masker_tahun * $uph_masker;
                                                    // if ($sts_jabatan == "Advisor") {
                                                    //     $global = ((80 / 100) * ($uph_pokok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
                                                    // } else {
                                                    //     $global = $uph_pokok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
                                                    // }

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
                                                        $lembur1 = $l->lembur1;
                                                        if ($l->lembur1 !== null) {
                                                            $lembur1 = round($l->lembur1, 1);
                                                        }

                                                        $lembur2 = $l->lembur2;
                                                        if ($l->lembur1 !== null) {
                                                            $lembur2 = round($l->lembur2, 1);
                                                        }

                                                        $lembur3 = $l->lembur3;
                                                        if ($l->lembur1 !== null) {
                                                            $lembur3 = round($l->lembur3, 1);
                                                        }
                                                    }

                                                    // Cek adjustment lemburan
                                                    $atrans = 0;
                                                    $amakan = 0;
                                                    $as1 = 0;
                                                    $as2 = 0;
                                                    $adj_upah = $this->m_lembur->cek_adjust($tgl_mulai, $tgl_akhir, $recid_karyawan);
                                                    // echo $tgl_mulai." - ".$tgl_akhir." : ".$adj_upah->num_rows();
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

                                                    $bonus = $upah_hadir - ($pkeluar + $ppulang + $ptdrencana + $prencana);
                                                    // echo $uang_makan;

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
                                                    // echo "<br>mangkir = ".$mkr->num_rows();
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

                                                    $kopkar = $this->m_lembur->potongan_kopkar($recid_karyawan, $tahun, $bulanke);
                                                    if ($kopkar->num_rows() > 0) {
                                                        foreach ($kopkar->result() as $kop) {
                                                            $pot_kopkar = $kop->potongan;
                                                        }
                                                    } else {
                                                        $pot_kopkar = 0;
                                                    }
                                                    $jml_terima = $net - $pot_kopkar;
                                                ?>

                                                    <tr>
                                                        <td><?php echo $no = $no + 1 ?></td>
                                                        <td><?php echo $k->nik ?></td>
                                                        <td><?php echo $k->nama_karyawan ?></td>
                                                        <?php for ($m = 0; $m < 27; $m++) { ?>
                                                            <td style="display:none"></td>
                                                        <?php } ?>
                                                        <td><?php echo $masker_tahun ?></td>
                                                        <td><?php echo $k->sts_penunjang . " / " . $k->sts_jbtn ?></td>
                                                        <td><?php echo number_format($uph_pokok) ?></td>
                                                        <td><?php echo number_format($t_jbtn) ?></td>
                                                        <td><?php echo number_format($t_jenpek) ?></td>
                                                        <td><?php echo number_format($t_masker) ?></td>
                                                        <td><?php echo number_format($global) ?></td>
                                                        <td><?php echo $slembur1 ?></td>
                                                        <td><?php echo $slembur2 ?></td>
                                                        <td><?php echo $slembur1 + $slembur2 ?></td>
                                                        <td><?php echo number_format($utot_shift) ?></td>
                                                        <td><?php echo $lembur1 ?></td>
                                                        <td><?php echo $lembur2 ?></td>
                                                        <td><?php echo $lembur3 ?></td>
                                                        <td><?php echo $lembur3 + $lembur2 + $lembur1 ?></td>
                                                        <td><?php echo number_format($tot_lbr) ?></td>
                                                        <td><?php echo number_format($bonus) ?></td>
                                                        <td><?php echo number_format($uang_transport) ?></td>
                                                        <td><?php echo number_format($uang_makan) ?></td>
                                                        <td><?php echo number_format($uasuransi) ?></td>
                                                        <td><?php echo number_format($pot_dplk) ?></td>
                                                        <td><?php echo number_format($astek) ?></td>
                                                        <td><?php echo number_format($uspmi) ?></td>
                                                        <td><?php echo number_format($umangkir) ?></td>
                                                        <td><?php echo number_format($tot_pph) ?></td>
                                                        <td><?php echo number_format($net_asr) ?></td>
                                                        <td><?php echo number_format($tot_pph) ?></td>
                                                        <td><?php echo number_format($net) ?></td>
                                                        <td><?php echo number_format($pot_kopkar) ?></td>
                                                        <td><?php echo number_format($jml_terima + $tot_pph) ?></td>
                                                    <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php }
                                        ?>
                                </tbody>
                            </table>
                            <!--/ Content Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var table = $('#t_upah').DataTable({
            "lengthChange": false,
            "ordering": false
        });
        table.buttons().disable();
    });
</script>
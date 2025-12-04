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
                                        <th rowspan='2'>UPH MASKER</th>
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
                                        <th colspan='4'>BERSIH</th>
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
                                        <th>NETT</th>
                                        <th>POT. KOPKAR</th>
                                        <th>JML TERIMA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // echo "bulan ke : ".$bulanke;
                                    /* kalkulasi periode desember */
                                    $tahun_lama = date("Y", strtotime($tgl_mulai));
                                    $tgl_mulai_lama = $tgl_mulai;
                                    $tgl_akhir_lama = date("Y-m-t", strtotime($tgl_mulai_lama));

                                    /* kalkulasi periode januari */
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
                                            $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' $fdepartment and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                                        } else {
                                            $dept = $this->db->query("SELECT distinct(d.nama_department) as nama_department from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
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
                                                $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department='$dp->nama_department' $fbagian and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
                                            } else {
                                                $bag = $this->db->query("SELECT distinct(b.indeks_hr) as indeks_hr, b.gl_acc, b.cost_center from karyawan k join bagian b on k.recid_bag = b.recid_bag  join jabatan j on k.recid_jbtn = j.recid_jbtn join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where  k.sts_aktif='Aktif' and k.cci = 'Tidak' and k.tc= '0' and b.pay_group = '$paygroup[$pyg]' and d.nama_department = '$dp->nama_department'  and j.tingkatan < 6 order by k.sts_aktif, b.indeks_hr, j.indeks_jabatan, k.nama_karyawan asc");
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
                                                        <td><?php echo number_format($uph_masker2) ?></td>
                                                        <td><?php echo $k->sts_penunjang . " / " . $k->sts_jbtn ?></td>
                                                        <td><?php echo number_format($gapokb) ?></td>
                                                        <td><?php echo number_format($t_jbtn2) ?></td>
                                                        <td><?php echo number_format($t_jenpek2) ?></td>
                                                        <td><?php echo number_format($t_masker2) ?></td>
                                                        <!-- <td><?php echo number_format($uph_masker2) ?></td> -->
                                                        <td><?php echo number_format($global_baru) ?></td>
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
                                                        <td><?php echo number_format($net) ?></td>
                                                        <td><?php echo number_format($pot_kopkar) ?></td>
                                                        <td><?php echo number_format($jml_terima) ?></td>
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
            "ordering": false,
            buttons: [
                'copy',
                {
                    extend: 'excel',
                    title: 'Report Transisi Upah <?php echo $bulan . " Tahun " . $tahun ?> ',
                },
            ]
        });
        //table.buttons().disable();
    });
</script>
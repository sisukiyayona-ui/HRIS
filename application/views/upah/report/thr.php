<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>THR Karyawan <?php echo " Tahun " . $tahun ?></h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
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
                                        <th>TERIMA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    /* -------- base masker = 1 ------------------*/
                                    $rp_masker = $this->m_hris->param_upah_id(1);
                                    foreach ($rp_masker->result() as $r) {
                                        $uph_masker = $r->nilai;
                                    }


                                    for ($pyg = 0; $pyg < count($paygroup); $pyg++) { ?>
                                        <tr>
                                            <td colspan="30">Paygroup : <?php echo $paygroup[$pyg] ?></td>
                                            <?php
                                            for ($m = 0; $m < 29; $m++) { ?>
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
                                                <td colspan="30">Department : <?php echo $dp->nama_department ?></td>
                                                <?php
                                                for ($m = 0; $m < 29; $m++) { ?>
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
                                                    <td colspan="30">Bagian : <?php echo $bag->indeks_hr . "( " . $bag->gl_acc . " - " . $bag->cost_center . " )"; ?></td>
                                                    <?php
                                                    for ($m = 0; $m < 29; $m++) { ?>
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

                                                    if ($tingkatan == 1) {
                                                        //2024
                                                        $gapok_baru = $this->m_hris->gapok_masker($tingkatan, $masker_tahun, $tahun);
                                                        foreach ($gapok_baru->result() as $gp2) {
                                                            if ($sts_jabatan == "Advisor") {
                                                                $gapok = (80 / 100) * $gp2->nilai;
                                                            } else {
                                                                $gapok = $gp2->nilai;
                                                            }
                                                        }
                                                    } else {
                                                        //2024
                                                        $gapok_baru = $this->m_hris->gapok($tingkatan, $tahun);
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
                                                            if ($sts_jabatan == "Advisor") {
                                                                $t_jbtn = (80 / 100) * $t2->nilai;
                                                            } else {
                                                                $t_jbtn = $t2->nilai;
                                                            }
                                                        }
                                                    }
                                                    $t_masker = $masker_tahun * $uph_masker;
                                                    // if ($sts_jabatan == "Advisor") {
                                                    //     $global = ((80 / 100) * ($gapok + $t_jbtn)) + $t_prestasi + $t_jenpek + $t_masker;
                                                    // } else {
                                                    //     $global = $gapok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;
                                                    // }

                                                    $global = $gapok + $t_jbtn + $t_prestasi + $t_jenpek + $t_masker;

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
                                                ?>

                                                    <tr>
                                                        <td><?php echo $no = $no + 1 ?></td>
                                                        <td><?php echo $k->nik ?></td>
                                                        <td><?php echo $k->nama_karyawan ?></td>
                                                        <?php for ($m = 0; $m < 24; $m++) { ?>
                                                            <td style="display:none"></td>
                                                        <?php } ?>
                                                        <td><?php echo $masker_tahun ?></td>
                                                        <td><?php echo number_format($uph_masker) ?></td>
                                                        <td><?php echo $k->sts_penunjang . " / " . $k->sts_jbtn ?></td>
                                                        <td><?php echo number_format($gapok) ?></td>
                                                        <td><?php echo number_format($t_jbtn) ?></td>
                                                        <td><?php echo number_format($t_jenpek) ?></td>
                                                        <td><?php echo number_format($t_masker) ?></td>

                                                        <td><?php echo number_format($global) ?></td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td><?php echo number_format($uasuransi) ?></td>
                                                        <td><?php echo number_format($pot_dplk) ?></td>
                                                        <td><?php echo number_format($astek) ?></td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td><?php echo number_format($pph_thr) ?></td>
                                                        <td><?php echo number_format($terima) ?></td>
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
  <style>
      td {
          text-align: center;
      }
  </style>
  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Rekapitulasi Persentase Kehadiran Per Bagian</h3>
              </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Periode <?php echo $mulai ?> s/d <?php echo $sampai ?> </h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <?php echo $detail_norma ?><br>
                          <?php echo $detail_nnorma ?><br><br>
                          <input type="hidden" id="mulai" value="<?php echo $mulai ?>">
                          <input type="hidden" id="sampai" value="<?php echo $sampai ?>">
                          <input type="hidden" id="norma" value="<?php echo $detail_norma ?>">
                          <input type="hidden" id="nonnorma" value="<?php echo $detail_nnorma ?>">

                          <table class="table table-bordered" id="rekap_absen" border="1">
                              <thead>
                                  <tr>
                                      <td rowspan="2">No</td>
                                      <td rowspan="2">Bagian</td>
                                      <td rowspan="2">Jumlah Karyawan</td>
                                      <td rowspan="2">Hari Kerja</td>
                                      <td rowspan="2">Real Kerja</td>
                                      <td rowspan="2">Hari Efektif</td>
                                      <td rowspan="2">%</td>
                                      <td rowspan="2">TDK MSK</td>
                                      <td rowspan="2">%</td>
                                      <td rowspan="2">Target TK</td>
                                      <td rowspan="2">Norma</td>
                                      <td rowspan="2">%</td>
                                      <td rowspan="2">Non Norma</td>
                                      <td rowspan="2">%</td>
                                      <td colspan="9">Kategori Absensi</td>
                                      <td colspan="3">Surat</td>
                                      <td colspan="3">Analisa Sakit</td>
                                  </tr>
                                  <tr>
                                      <td>M</td>
                                      <td>P1</td>
                                      <td>SID</td>
                                      <td>KK</td>
                                      <td>H1</td>
                                      <td>H2</td>
                                      <td>P4</td>
                                      <td>C</td>
                                      <td>MU</td>
                                      <td>Terlambat</td>
                                      <td>Pulang</td>
                                      <td>Keluar</td>
                                      <td>Ringan</td>
                                      <td>Berkelanjutan</td>
                                      <td>Berat</td>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    /*------------ chitose inisiasi awal start ----------------*/
                                    $kar_chitose  = 0;
                                    $c_hk = 0;
                                    $c_hk2 = 0;
                                    $c_efektif = 0;
                                    $c_efektif_p = 0;
                                    $c_tdk_kerja = 0;
                                    $c_tdk_kerja_p =  0;
                                    $c_norma = 0;
                                    $c_nonnorma = 0;
                                    $c_norma_p = 0;
                                    $c_nonnorma_p = 0;
                                    $c_target_tk = 0;
                                    $c_m = 0;
                                    $c_p1 = 0;
                                    $c_sid = 0;
                                    $c_kk = 0;
                                    $c_h1 = 0;
                                    $c_h2 = 0;
                                    $c_p4 = 0;
                                    $c_c = 0;
                                    $c_mu = 0;
                                    $c_telat = 0;
                                    $c_pulang = 0;
                                    $c_keluar = 0;
                                    $c_ringan = 0;
                                    $c_lanjut = 0;
                                    $c_berat = 0;
                                    $c_lbh = 0;
                                    /*------------ chitose inisiasi awal end ----------------*/
                                    $jml_hk = $jml_hkn;
                                    $default_hk = $jml_hkn; 
                                    $db2 = $this->load->database('absen', TRUE);
                                    for ($dg = 0; $dg < count($fdivisi); $dg++)  /*--- loop dept group ---*/ {
                                        /*------------ dept group inisiasi awal start ----------------*/
                                        $dg_hk = 0;
                                        $dg_hk2 = 0;
                                        $dg_efektif = 0;
                                        $dg_efektif_p = 0;
                                        $dg_tdk_kerja = 0;
                                        $dg_tdk_kerja_p =  0;
                                        $dg_norma = 0;
                                        $dg_nonnorma = 0;
                                        $dg_norma_p = 0;
                                        $dg_nonnorma_p = 0;
                                        $dg_target_tk = 0;
                                        $dg_m = 0;
                                        $dg_p1 = 0;
                                        $dg_sid = 0;
                                        $dg_kk = 0;
                                        $dg_h1 = 0;
                                        $dg_h2 = 0;
                                        $dg_p4 = 0;
                                        $dg_c = 0;
                                        $dg_mu = 0;
                                        $dg_telat = 0;
                                        $dg_pulang = 0;
                                        $dg_keluar = 0;
                                        $dg_ringan = 0;
                                        $dg_lanjut = 0;
                                        $dg_berat = 0;
                                        $dg_lbh= 0;
                                        /*------------ dept group inisiasi awal end ----------------*/
                                        //department by bagian
                                        if ($fdepartment != '') {
                                            $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' $fdepartment order by nama_department asc");
                                        } else {
                                            $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' order by nama_department asc");
                                        }

                                        $kar_dept_group  = 0;
                                        foreach ($dept->result() as $dp) {
                                            /*------------ dept inisiasi awal start ----------------*/
                                            $b_hk = 0;
                                            $b_hk2 = 0;
                                            $b_efektif = 0;
                                            $b_efektif_p = 0;
                                            $b_tdk_kerja = 0;
                                            $b_tdk_kerja_p =  0;
                                            $b_norma = 0;
                                            $b_nonnorma = 0;
                                            $b_norma_p = 0;
                                            $b_nonnorma_p = 0;
                                            $b_target_tk = 0;
                                            $b_m = 0;
                                            $b_p1 = 0;
                                            $b_sid = 0;
                                            $b_kk = 0;
                                            $b_h1 = 0;
                                            $b_h2 = 0;
                                            $b_p4 = 0;
                                            $b_c = 0;
                                            $b_mu = 0;
                                            $b_telat = 0;
                                            $b_pulang = 0;
                                            $b_keluar = 0;
                                            $b_ringan = 0;
                                            $b_lanjut = 0;
                                            $b_berat = 0;
                                            $b_lbh= 0;
                                            /*------------ dept inisiasi awal end ----------------*/
                                            $role = $this->session->userdata('role_id');
                                            $usr = $this->session->userdata('kar_id');
                                            if ($role == 30) {
                                                $bagian = $this->db->query("SELECT  b.recid_bag, indeks_hr
                                  from master_absen.admin_bagian a 
                                  JOIN hris.bagian b on b.recid_bag = a.recid_bag
                                  join hris.department d on d.recid_department = b.recid_department
                                  where recid_karyawan = '$usr' order by indeks_hr asc");
                                            } else {
                                                $bagian = $this->db->query("SELECT * from bagian b join department d on d.recid_department = b.recid_department where d.recid_department = $dp->recid_department and b.is_delete = '0' and b.indeks_hr != '' $fbagian order by indeks_hr asc");
                                            }
                                            
                                            $kar_dept  = 0;
                                            $no = 0;
                                            $tot_hk = 0;
                                            $tot_hk2 = 0;
                                            foreach ($bagian->result() as $b) {
                                                $hitung_hk = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where b.recid_bag = $b->recid_bag and (tanggal between '$mulai' and '$sampai') and (status != 24 and status != '28' and status != '31' and status != '32' and status != '33' and status != '36') and k.tc = '0' and k.cci = 'Tidak' and is_closed = '0';");
                                                $jml_hk = $hitung_hk->num_rows();
                                                // echo $jml_hk;

                                                $hitung_karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag where k.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and tc = '0'");

                                                $jml_kar = $hitung_karyawan->num_rows();
                                                $kar_dept = $kar_dept + $jml_kar;
                                                $kar_dept_group = $kar_dept_group + $jml_kar;
                                                $kar_chitose = $kar_chitose + $jml_kar;
                                                $tot_hk = $tot_hk + $jml_hk;
                                                $tot_hk2 = $hitung_karyawan->num_rows() * $default_hk;

                                                $m = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai')  and h.status = '11' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $p1 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '5' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $sid = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '2' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $kk = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '25' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $h1 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '6' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $h2 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '7' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $p4 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '12' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $c = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '3' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $mu = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and h.status = '34' and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                $fnorma = $db2->query("SELECT count(h.recid_absen) as jml_norma FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and $norma and b.recid_bag = $b->recid_bag and k.cci = 'Tidak'");

                                                foreach ($fnorma->result() as $fnorma) {
                                                    $jml_norma = $fnorma->jml_norma;
                                                }

                                                $fnnorma = $db2->query("SELECT count(h.recid_absen) as jml_nonnorma FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and $non_norma and b.recid_bag = $b->recid_bag");

                                                foreach ($fnnorma->result() as $fnnorma) {
                                                    $jml_nonnorma = $fnnorma->jml_nonnorma;
                                                }

                                                $telat = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where(tgl_izin between '$mulai' and '$sampai') and (jenis = 'Terlambat' or jenis = 'Terlambat Terencana' or jenis = 'Terlambat Tidak Terencana')  and b.recid_bag = $b->recid_bag and i.is_delete = '0'");

                                                $pulang = $db2->query("SELECT * from izin i join hris.karyawan k on k.recid_karyawan = i.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where(tgl_izin between '$mulai' and '$sampai') and jenis = 'Pulang' and b.recid_bag = $b->recid_bag and i.is_delete = '0'");

                                                $jml_keluar = 0;
                                                foreach ($hitung_karyawan->result() as $k) {
                                                    $keluar = $db2->query("SELECT *, timediff(jam_in, jam_out) as lama from izin where jenis = 'Keluar' and(tgl_izin between '$mulai' and '$sampai') and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                                                    foreach ($keluar->result() as $kl) {
                                                        if ($kl->jam_out == "11:30" and $kl->jam_in == "12:30") {
                                                        } else {
                                                            $jml_keluar = $jml_keluar + 1;
                                                        }
                                                    }
                                                }


                                                $ringan = $db2->query("SELECT * from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and jenis_absen = 2 and kategori = 'Ringan' and b.recid_bag = $b->recid_bag and a.is_delete = '0'");

                                                $lanjut = $db2->query("SELECT * from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and jenis_absen = 2 and kategori = 'Berkelanjutan' and b.recid_bag = $b->recid_bag and a.is_delete = '0'");

                                                $berat = $db2->query("SELECT * from absensi_hris a join hris.karyawan k on k.recid_karyawan = a.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where (tanggal between '$mulai' and '$sampai') and jenis_absen = 2 and kategori = 'Berat' and  b.recid_bag = $b->recid_bag and a.is_delete = '0'");

                                                if ($jml_hk == 0) {
                                                    $efektif = 0;
                                                    $efektif_p = 0;
                                                    $tdk_kerja = 0;
                                                    $tdk_kerja_p = 0;
                                                    $target_tk = 0;
                                                    $norma_p = 0;
                                                    $nonnorma_p = 0;
                                                } else {
                                                    $efektif = $jml_hk - ($jml_nonnorma + $jml_norma);
                                                    $efektif_p = ($efektif / $jml_hk) * 100;
                                                    $tdk_kerja = $jml_hk - $efektif;
                                                    $tdk_kerja_p = ($tdk_kerja / $jml_hk) * 100;
                                                    $target_tk = (($jml_hk - $jml_nonnorma) / $jml_hk) * 100;
                                                    $norma_p = ($jml_norma / $jml_hk) * 100;
                                                    $nonnorma_p = ($jml_nonnorma / $jml_hk) * 100;
                                                }



                                                $b_hk = $tot_hk;
                                                $b_hk2 = $b_hk2 + $tot_hk2;
                                                if ($b_hk == 0) {
                                                    $b_hkb = 1;
                                                } else {
                                                    $b_hkb = $b_hk;
                                                }
                                                $b_efektif = $b_efektif + $efektif;
                                                $b_efektif_p = ($b_efektif / $b_hkb) * 100;
                                                $b_tdk_kerja = $b_tdk_kerja + $tdk_kerja;
                                                $b_tdk_kerja_p = ($b_tdk_kerja / $b_hkb) * 100;
                                                $b_norma = $b_norma + $jml_norma;
                                                $b_nonnorma = $b_nonnorma + $jml_nonnorma;
                                                $b_norma_p = ($b_norma / $b_hkb) * 100;
                                                $b_nonnorma_p = ($b_nonnorma / $b_hkb) * 100;
                                                $b_target_tk = (($b_hkb - $b_nonnorma) / $b_hkb) * 100;
                                                $b_m = $b_m + $m->num_rows();
                                                $b_p1 = $b_p1 + $p1->num_rows();
                                                $b_sid = $b_sid + $sid->num_rows();
                                                $b_kk = $b_kk + $kk->num_rows();
                                                $b_h1 = $b_h1 + $h1->num_rows();
                                                $b_h2 = $b_h2 + $h2->num_rows();
                                                $b_p4 = $b_p4 + $p4->num_rows();
                                                $b_c = $b_c + $c->num_rows();
                                                $b_mu = $b_mu + $mu->num_rows();
                                                $b_telat = $b_telat + $telat->num_rows();
                                                $b_pulang = $b_pulang + $pulang->num_rows();
                                                $b_keluar = $b_keluar + $keluar->num_rows();
                                                $b_ringan = $b_ringan + $ringan->num_rows();
                                                $b_lanjut = $b_lanjut + $lanjut->num_rows();
                                                $b_berat = $b_berat + $berat->num_rows();
                                    ?>
                                              <tr>
                                                  <td><?php echo $no = $no + 1 ?></td>
                                                  <td><?php echo $b->indeks_hr ?></td>
                                                  <td><?php echo $jml_kar ?></td>
                                                  <td><?php echo $default_hk * $jml_kar ?></td>
                                                  <td><?php echo $jml_hk ?></td>
                                                  <td><?php echo $efektif ?></td>
                                                  <td><?php echo round($efektif_p, 2) ?> %</td>
                                                  <td><?php echo $tdk_kerja ?></td>
                                                  <td><?php echo round($tdk_kerja_p, 2) ?> %</td>
                                                  <td><?php echo round($target_tk, 2) ?> %</td>
                                                  <td><?php echo $jml_norma ?></td>
                                                  <td><?php echo round($norma_p, 2) ?> %</td>
                                                  <td><?php echo $jml_nonnorma ?></td>
                                                  <td><?php echo round($nonnorma_p, 2) ?> %</td>
                                                  <td><?php echo $m->num_rows() ?></td>
                                                  <td><?php echo $p1->num_rows() ?></td>
                                                  <td><?php echo $sid->num_rows() ?></td>
                                                  <td><?php echo $kk->num_rows() ?></td>
                                                  <td><?php echo $h1->num_rows() ?></td>
                                                  <td><?php echo $h2->num_rows() ?></td>
                                                  <td><?php echo $p4->num_rows() ?></td>
                                                  <td><?php echo $c->num_rows() ?></td>
                                                  <td><?php echo $mu->num_rows() ?></td>
                                                  <td><?php echo $telat->num_rows() ?></td>
                                                  <td><?php echo $pulang->num_rows() ?></td>
                                                  <td><?php echo $jml_keluar ?></td>
                                                  <td><?php echo $ringan->num_rows() ?></td>
                                                  <td><?php echo $lanjut->num_rows() ?></td>
                                                  <td><?php echo $berat->num_rows() ?></td>
                                              </tr>
                                              <tr>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td>
                                                <?php if ($jml_hk == 0) {
                                                            $jml_hk = 1;
                                                        } else {
                                                            $jml_hk = $jml_hk;
                                                        }
                                                        echo round((($m->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($p1->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round(((($sid->num_rows()) / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($kk->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($h1->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($h2->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($p4->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($c->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($mu->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($telat->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($pulang->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($keluar->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($ringan->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($lanjut->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                                  <td><?php echo round((($berat->num_rows() / $jml_hk) * 100), 2) ?> %</td>
                                              </tr>
                                          <?php }
                                            ?>
                                          <tr style="background-color:#bbf0c5; color:#000;">
                                              <td colspan="2">Total Departemen <?php echo $dp->nama_department ?> </td>
                                              <td style="display:none"></td>
                                              <td><?php echo $kar_dept ?></td>
                                              <td><?php echo $b_hk2 ?></td>
                                              <td><?php echo $b_hk ?></td>
                                              <td><?php echo $b_efektif ?></td>
                                              <td><?php echo round($b_efektif_p, 2) ?> %</td>
                                              <td><?php echo $b_tdk_kerja ?></td>
                                              <td><?php echo round($b_tdk_kerja_p, 2) ?> %</td>
                                              <td><?php echo round($b_target_tk, 2) ?> %</td>
                                              <td><?php echo $b_norma ?></td>
                                              <td><?php echo round($b_norma_p, 2) ?> %</td>
                                              <td><?php echo $b_nonnorma ?></td>
                                              <td><?php echo round($b_nonnorma_p, 2) ?> %</td>
                                              <td><?php echo $b_m ?></td>
                                              <td><?php echo $b_p1 ?></td>
                                              <td><?php echo $b_sid ?></td>
                                              <td><?php echo $b_kk ?></td>
                                              <td><?php echo $b_h1 ?></td>
                                              <td><?php echo $b_h2 ?></td>
                                              <td><?php echo $b_p4 ?></td>
                                              <td><?php echo $b_c ?></td>
                                              <td><?php echo $b_mu ?></td>
                                              <td><?php echo $b_telat ?></td>
                                              <td><?php echo $b_pulang ?></td>
                                              <td><?php echo $b_keluar ?></td>
                                              <td><?php echo $b_ringan ?></td>
                                              <td><?php echo $b_lanjut ?></td>
                                              <td><?php echo $b_berat ?></td>
                                          </tr>
                                          <tr style="background-color:#bbf0c5; color:#000;">
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td></td>
                                              <td><?php echo round((($b_m / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_p1 / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_sid / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_kk / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_h1 / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_h2 / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_p4 / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_c / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_mu / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_telat / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_pulang / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_keluar / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_ringan / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_lanjut / $b_hkb) * 100), 2) ?> %</td>
                                              <td><?php echo round((($b_berat / $b_hkb) * 100), 2) ?> %</td>
                                          </tr>
                                          <?php  /*------------ direktorat calculation start ----------------*/
                                            if ($dg_hk + $b_hk == 0) {
                                                $dg_hkg = 1;
                                            } else {
                                                $dg_hkg = $dg_hk + $b_hk;
                                            }
                                            $dg_hk = $dg_hk + $b_hk;
                                            $dg_hk2 = $dg_hk2 + $b_hk2;
                                            $dg_efektif = $dg_efektif + $b_efektif;
                                            $dg_efektif_p = ($dg_efektif / $dg_hkg) * 100;
                                            $dg_tdk_kerja = $dg_tdk_kerja + $b_tdk_kerja;
                                            $dg_tdk_kerja_p = ($dg_tdk_kerja / $dg_hkg) * 100;
                                            $dg_norma = $dg_norma + $b_norma;
                                            $dg_nonnorma = $dg_nonnorma + $b_nonnorma;
                                            $dg_norma_p = ($dg_norma / $dg_hkg) * 100;
                                            $dg_nonnorma_p = ($dg_nonnorma / $dg_hkg) * 100;
                                            $dg_target_tk = (($dg_hk - $dg_nonnorma) / $dg_hkg) * 100;
                                            $dg_m = $dg_m + $b_m;
                                            $dg_p1 = $dg_p1 + $b_p1;
                                            $dg_sid = $dg_sid + $b_sid;
                                            $dg_kk = $dg_kk + $b_kk;
                                            $dg_h1 = $dg_h1 + $b_h1;
                                            $dg_h2 = $dg_h2 + $b_h2;
                                            $dg_p4 = $dg_p4 + $b_p4;
                                            $dg_c = $dg_c + $b_c;
                                            $dg_mu = $dg_mu + $b_mu;
                                            $dg_telat = $dg_telat + $b_telat;
                                            $dg_pulang = $dg_pulang + $b_pulang;
                                            $dg_keluar = $dg_keluar + $b_keluar;
                                            $dg_ringan = $dg_ringan + $b_ringan;
                                            $dg_lanjut = $dg_lanjut + $b_lanjut;
                                            $dg_berat = $dg_berat + $b_berat;
                                            /*------------ direktorat calculation end ----------------*/ ?>
                                          <?php  /*------------ chitose calculation start ----------------*/
                                            if ($dg_hk + $b_hk == 0) {
                                                $c_hkc = 1;
                                            } else {
                                                $c_hkc = $dg_hk + $b_hk;
                                            }
                                            $c_hk = $c_hk + $b_hk;
                                            $c_hk2 = $c_hk2 + $b_hk2;
                                            // echo $c_hk;
                                            $c_efektif = $c_efektif + $b_efektif;
                                            $c_efektif_p = ($c_efektif / $dg_hk) * 100;
                                            $c_tdk_kerja = $c_tdk_kerja + $b_tdk_kerja;
                                            $c_tdk_kerja_p = ($c_tdk_kerja / $dg_hk) * 100;
                                            $c_norma = $c_norma + $b_norma;
                                            $c_nonnorma = $c_nonnorma + $b_nonnorma;
                                            $c_norma_p = ($c_norma / $dg_hk) * 100;
                                            $c_nonnorma_p = ($c_nonnorma / $c_hkc) * 100;
                                            $c_target_tk = (($dg_hk - $c_nonnorma) / $dg_hk) * 100;
                                            $c_m = $c_m + $b_m;
                                            $c_p1 = $c_p1 + $b_p1;
                                            $c_sid = $c_sid + $b_sid;
                                            $c_kk = $c_kk + $b_kk;
                                            $c_h1 = $c_h1 + $b_h1;
                                            $c_h2 = $c_h2 + $b_h2;
                                            $c_p4 = $c_p4 + $b_p4;
                                            $c_c = $c_c + $b_c;
                                            $c_mu = $c_mu + $b_mu;
                                            $c_telat = $c_telat + $b_telat;
                                            $c_pulang = $c_pulang + $b_pulang;
                                            $c_keluar = $c_keluar + $b_keluar;
                                            $c_ringan = $c_ringan + $b_ringan;
                                            $c_lanjut = $c_lanjut + $b_lanjut;
                                            $c_berat = $c_berat + $b_berat;
                                            /*------------ chitose calculation end ----------------*/ ?>
                                      <?php }
                                        ?>
                                      <tr style="background-color:#b8def5; color:#000;">
                                          <td colspan="2">Total Direktorat <?php echo $fdivisi[$dg] ?> </td>
                                          <td style="display:none"></td>
                                          <td><?php echo $kar_dept_group ?></td>
                                          <td><?php echo $dg_hk2 ?></td>
                                          <td><?php echo $dg_hk ?></td>
                                          <td><?php echo $dg_efektif ?></td>
                                          <td><?php echo round($dg_efektif_p, 2) ?> %</td>
                                          <td><?php echo $dg_tdk_kerja ?></td>
                                          <td><?php echo round($dg_tdk_kerja_p, 2) ?> %</td>
                                          <td><?php echo round($dg_target_tk, 2) ?> %</td>
                                          <td><?php echo $dg_norma ?></td>
                                          <td><?php echo round($dg_norma_p, 2) ?> %</td>
                                          <td><?php echo $dg_nonnorma ?></td>
                                          <td><?php echo round($dg_nonnorma_p, 2) ?> %</td>
                                          <td><?php echo $dg_m ?></td>
                                          <td><?php echo $dg_p1 ?></td>
                                          <td><?php echo $dg_sid ?></td>
                                          <td><?php echo $dg_kk ?></td>
                                          <td><?php echo $dg_h1 ?></td>
                                          <td><?php echo $dg_h2 ?></td>
                                          <td><?php echo $dg_p4 ?></td>
                                          <td><?php echo $dg_c ?></td>
                                          <td><?php echo $dg_mu ?></td>
                                          <td><?php echo $dg_telat ?></td>
                                          <td><?php echo $dg_pulang ?></td>
                                          <td><?php echo $dg_keluar ?></td>
                                          <td><?php echo $dg_ringan ?></td>
                                          <td><?php echo $dg_lanjut ?></td>
                                          <td><?php echo $dg_berat ?></td>
                                      </tr>
                                      <tr style="background-color:#b8def5; color:#000;">
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><?php echo round((($dg_p1 / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_sid / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_kk / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_h1 / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_h2 / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_p4 / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_c / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_mu / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_telat / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_pulang / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_keluar / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_ringan / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_lanjut / $dg_hkg) * 100), 2) ?> %</td>
                                          <td><?php echo round((($dg_berat / $dg_hkg) * 100), 2) ?> %</td>
                                      </tr>
                                  <?php }
                                    ?>
                                  <tr style="background-color:#f57e76; color:#000;">
                                      <td colspan="2">Total Chitose </td>
                                      <td style="display:none"></td>
                                      <td><?php echo $kar_chitose ?></td>
                                      <td><?php echo $c_hk2 ?></td>
                                      <td><?php echo $c_hk ?></td>
                                      <td><?php echo $c_efektif ?></td>
                                      <td><?php echo round($c_efektif_p, 2) ?> %</td>
                                      <td><?php echo $c_tdk_kerja ?></td>
                                      <td><?php echo round($c_tdk_kerja_p, 2) ?> %</td>
                                      <td><?php echo round($c_target_tk, 2) ?> %</td>
                                      <td><?php echo $c_norma ?></td>
                                      <td><?php echo round($c_norma_p, 2) ?> %</td>
                                      <td><?php echo $c_nonnorma ?></td>
                                      <td><?php echo round($c_nonnorma_p, 2) ?> %</td>
                                      <td><?php echo $c_m ?></td>
                                      <td><?php echo $c_p1 ?></td>
                                      <td><?php echo $c_sid ?></td>
                                      <td><?php echo $c_kk ?></td>
                                      <td><?php echo $c_h1 ?></td>
                                      <td><?php echo $c_h2 ?></td>
                                      <td><?php echo $c_p4 ?></td>
                                      <td><?php echo $c_c ?></td>
                                      <td><?php echo $c_mu ?></td>
                                      <td><?php echo $c_telat ?></td>
                                      <td><?php echo $c_pulang ?></td>
                                      <td><?php echo $c_keluar ?></td>
                                      <td><?php echo $c_ringan ?></td>
                                      <td><?php echo $c_lanjut ?></td>
                                      <td><?php echo $c_berat ?></td>
                                  </tr>
                                  <tr style="background-color:#f57e76; color:#000;">
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <!-- pembagi c_hkc diganti jadi dg_hk -->
                                      <td><?php echo round((($c_m / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_p1 / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_sid / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_kk / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_h1 / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_h2 / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_p4 / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_c / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_mu / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_telat / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_pulang / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_keluar / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_ringan / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_lanjut / $dg_hk) * 100), 2) ?> %</td>
                                      <td><?php echo round((($c_berat / $dg_hk) * 100), 2) ?> %</td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- /page content -->


  <script>
      $(document).ready(function() {
          mulai = document.getElementById('mulai').value;
          sampai = document.getElementById('sampai').value;
          norma = document.getElementById('norma').value;
          nonnorma = document.getElementById('nonnorma').value;
          judul = norma + ' | ' + nonnorma;
          console.log(judul);
          var table = $('#rekap_absen').DataTable({
              scrollY: "600px",
              scrollX: true,
              scrollCollapse: true,
              paging: false,
              "bSort": false,
              dom: 'Bfrtip',
              buttons: [{
                  extend: 'excel',
                  className: 'btn btn-primary btn-sm',
                  title: 'Laporan Absensi Bagian Periode ' + mulai + ' s/d ' + sampai,
              }]
          });
      });
  </script>
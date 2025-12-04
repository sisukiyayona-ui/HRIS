    <style>
      td{text-align: center;}
    </style>
    <!-- page content -->
          <div class="right_col" role="main">
            <div class="">
              <div class="page-title">
                <div class="title_left">
                  <h3>Rekapitulasi Persentase Kehadiran Tahunan Karyawan</h3>
                </div>
              </div>

              <div class="clearfix"></div>

              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Tahun <?php echo $tahun?> </h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <?php echo $detail_norma?><br>
                      <?php echo $detail_nnorma?><br><br>
                      <input type="hidden" id="tahun" value="<?php echo $tahun?>">
                      <input type="hidden" id="norma" value="<?php echo $detail_norma?>">
                      <input type="hidden" id="nonnorma" value="<?php echo $detail_nnorma?>">
                      
                        <table class="table table-bordered" id="rekap_absen" border="1">
                          <thead>
                           <tr>
                            <td rowspan="2">No</td>
                            <td rowspan="2">NIK</td>
                            <td rowspan="2">Nama Karyawan</td>
                            <td rowspan="2">Bagian</td>
                            <td rowspan="2">Jabatan</td>
                            <td rowspan="2">Hari Kerja</td>
                            <td rowspan="2">Hari Efektif</td>
                            <td rowspan="2">%</td>
                            <td rowspan="2">TDK MSK</td>
                            <td rowspan="2">%</td>
                            <td rowspan="2">Target TK</td>
                            <td rowspan="2">Norma</td>
                            <td rowspan="2">%</td>
                            <td rowspan="2">Non Norma</td>
                            <td rowspan="2">%</td>
                            <td colspan="8">Kategori Absensi</td>
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
                          $c_telat = 0;
                          $c_pulang = 0;
                          $c_keluar = 0;
                          $c_ringan = 0;
                          $c_lanjut = 0;
                          $c_berat = 0;
                          /*------------ chitose inisiasi awal end ----------------*/
                          $jml_hk = $jml_hkn;
                          $db2 = $this->load->database('absen', TRUE);
                          for($dg=0;$dg<count($fdivisi);$dg++)  /*--- loop dept group ---*/
                          {
                               /*------------ dept group inisiasi awal start ----------------*/
                              $dg_hk = 0;
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
                              $dg_telat = 0;
                              $dg_pulang = 0;
                              $dg_keluar = 0;
                              $dg_ringan = 0;
                              $dg_lanjut = 0;
                              $dg_berat = 0;
                               /*------------ dept group inisiasi awal end ----------------*/
                            //department by bagian
                            if($fdepartment != '')
                            {
                               $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' $fdepartment order by nama_department asc");
                            }else{
                             $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' order by nama_department asc");
                            }
                           
                            $kar_dept_group  = 0;
                            foreach ($dept->result() as $dp) {
                              /*------------ dept inisiasi awal start ----------------*/
                              $d_hk = 0;
                              $d_efektif = 0;
                              $d_efektif_p = 0;
                              $d_tdk_kerja = 0;
                              $d_tdk_kerja_p =  0;
                              $d_norma = 0;
                              $d_nonnorma = 0;
                              $d_norma_p = 0;
                              $d_nonnorma_p = 0; 
                              $d_target_tk = 0;
                              $d_m = 0;
                              $d_p1 = 0;
                              $d_sid = 0;
                              $d_kk = 0;
                              $d_h1 = 0;
                              $d_h2 = 0;
                              $d_p4 = 0;
                              $d_c = 0;
                              $d_telat = 0;
                              $d_pulang = 0;
                              $d_keluar = 0;
                              $d_ringan = 0;
                              $d_lanjut = 0;
                              $d_berat = 0;
                               /*------------ dept inisiasi awal end ----------------*/
                              // echo "Set HK Dept : ".$dp->nama_department." : ".$d_hk;
                                //bagian by dept
                              $role = $this->session->userdata('role_id');
                              $usr = $this->session->userdata('kar_id');
                              if($role == 30)
                              {
                                $bagian = $this->db->query("SELECT  b.recid_bag, indeks_hr
                                  from master_absen.admin_bagian a 
                                  JOIN hris.bagian b on b.recid_bag = a.recid_bag
                                  join hris.department d on d.recid_department = b.recid_department
                                  where recid_karyawan = '$usr' order by indeks_hr asc");
                              }else{
                                 $bagian = $this->db->query("SELECT * from bagian b join department d on d.recid_department = b.recid_department where d.recid_department = $dp->recid_department and b.is_delete = '0' and b.indeks_hr != '' $fbagian order by indeks_hr asc");
                              }
                              $kar_dept  = 0;
                              foreach ($bagian->result() as $b) {
                                /*------------ bagian inisiasi awal end ----------------*/
                                $no = 0;
                                $s_hk = 0;
                                $s_efektif = 0;
                                $s_efektif_p = 0;
                                $s_tdk_kerja = 0;
                                $s_tdk_kerja_p =  0;
                                $s_norma = 0;
                                $s_nonnorma = 0;
                                $s_norma_p = 0;
                                $s_nonnorma_p = 0; 
                                $s_target_tk = 0;
                                $s_m = 0;
                                $s_p1 = 0;
                                $s_sid = 0;
                                $s_kk = 0;
                                $s_h1 = 0;
                                $s_h2 = 0;
                                $s_p4 = 0;
                                $s_c = 0;
                                $s_telat = 0;
                                $s_pulang = 0;
                                $s_keluar = 0;
                                $s_ringan = 0;
                                $s_lanjut = 0;
                                $s_berat = 0;
                                /*------------ bagian inisiasi awal end ----------------*/

                                  //karyawan by bagian
                                if($fkaryawan != '')
                                {
                                  $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak' and tc = '0' $fkaryawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                                }else{
                                  $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak' and tc = '0' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                                }
                                
                                $kar_bagian  = 0;
                                $no = 1;
                                if($karyawan->num_rows() == 0)
                                {
                                  continue;
                                }else{
                                  foreach ($karyawan->result() as $k) { 
                                    if($k->recid_bag == '24')
                                    {
                                      $hitung_hk = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where b.recid_bag = 24 and k.recid_karyawan = $k->recid_karyawan and year(h.tanggal) = $tahun and status != 24;");
                                      $jml_hk = $hitung_hk->num_rows();
                                    }else{
                                      $jml_hk = $jml_hkn;
                                    }
                                    // echo "HK ".$k->nama_karyawan." : ".$jml_hk;

                                    $kar_bagian = $kar_bagian+1;
                                    $kar_dept = $kar_dept+1;
                                    $kar_dept_group = $kar_dept_group+1;
                                    $kar_chitose = $kar_chitose+1;

                                    $m = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun  and h.status = '11' and k.recid_karyawan = $k->recid_karyawan");

                                    $p1 = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '5' and k.recid_karyawan = $k->recid_karyawan");

                                    $sid = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '2' and k.recid_karyawan = $k->recid_karyawan");

                                    $kk = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '25' and k.recid_karyawan = $k->recid_karyawan");

                                    $h1 = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '6' and k.recid_karyawan = $k->recid_karyawan");

                                    $h2 = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '7' and k.recid_karyawan = $k->recid_karyawan");

                                    $p4 = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '12' and k.recid_karyawan = $k->recid_karyawan");

                                    $c = $db2->query("SELECT * FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and h.status = '3' and k.recid_karyawan = $k->recid_karyawan");


                                    $fnorma = $db2->query("SELECT count(h.recid_absen) as jml_norma FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and $norma and k.recid_karyawan = $k->recid_karyawan");

                                    foreach ($fnorma->result() as $fnorma) {
                                      $jml_norma = $fnorma->jml_norma;
                                    } 

                                    $fnnorma = $db2->query("SELECT count(h.recid_absen) as jml_nonnorma FROM hadir_hris h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where year(tanggal) = $tahun and $non_norma and k.recid_karyawan = $k->recid_karyawan");

                                    foreach ($fnnorma->result() as $fnnorma) {
                                      $jml_nonnorma = $fnnorma->jml_nonnorma;
                                    } 

                                    $telat = $db2->query("SELECT * from izin where year(tgl_izin) = $tahun and jenis = 'Terlambat' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                                   

                                    $pulang = $db2->query("SELECT * from izin where year(tgl_izin) = $tahun and jenis = 'Pulang' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");

                                    $jml_keluar = 0;
                                    $keluar = $db2->query("SELECT *, timediff(jam_in, jam_out) as lama from izin where jenis = 'Keluar' and year(tgl_izin) = $tahun and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                                    foreach ($keluar->result() as $kl) {
                                      if ($kl->jam_out == "11:30" and $kl->jam_in == "12:30") {
                                      } else {
                                        $jml_keluar = $jml_keluar + 1;
                                      }
                                    }

                                    $ringan = $db2->query("SELECT * from absensi_hris where year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Ringan' and recid_karyawan = $k->recid_karyawan");

                                    $lanjut = $db2->query("SELECT * from absensi_hris where year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Berkelanjutan' and recid_karyawan = $k->recid_karyawan");

                                    $berat =  $db2->query("SELECT * from absensi_hris where year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Berat' and recid_karyawan = $k->recid_karyawan");

                                    $efektif = $jml_hk - ($jml_nonnorma + $jml_norma);
                                    $efektif_p = ($efektif / $jml_hk) * 100;
                                    $tdk_kerja = $jml_hk - $efektif;
                                    $tdk_kerja_p = ($tdk_kerja / $jml_hk) * 100;
                                    $target_tk = (($jml_hk - $jml_nonnorma)/$jml_hk) * 100;
                                    $norma_p = ($jml_norma / $jml_hk) * 100;
                                    $nonnorma_p = ($jml_nonnorma / $jml_hk) * 100;

                                    /*------------ bagian calculation start ----------------*/
                                    $s_hk = $s_hk + $jml_hk;
                                    $s_efektif = $s_efektif + $efektif;
                                    $s_efektif_p = ($s_efektif/$s_hk) * 100;
                                    $s_tdk_kerja = $s_tdk_kerja + $tdk_kerja;
                                    $s_tdk_kerja_p = ($s_tdk_kerja / $s_hk) * 100;
                                    $s_norma = $s_norma + $jml_norma;
                                    $s_nonnorma = $s_nonnorma + $jml_nonnorma;
                                    $s_norma_p = ($s_norma / $s_hk) * 100;
                                    $s_nonnorma_p = ($s_nonnorma / $s_hk) * 100;
                                    $s_target_tk = (($s_hk - $s_nonnorma)/$s_hk) * 100;
                                    $s_m = $s_m + ($m->num_rows());
                                    $s_p1 = $s_p1 + ($p1->num_rows());
                                    $s_sid = $s_sid + ($sid->num_rows());
                                    $s_kk = $s_kk + ($kk->num_rows());
                                    $s_h1 = $s_h1 + ($h1->num_rows());
                                    $s_h2 = $s_h2 + ($h2->num_rows());
                                    $s_p4 = $s_p4 + ($p4->num_rows());
                                    $s_c = $s_c + ($c->num_rows());
                                    $s_telat = $s_telat + ($telat->num_rows());
                                    $s_pulang = $s_pulang + ($pulang->num_rows());
                                    $s_keluar = $s_keluar + ($jml_keluar);
                                    $s_ringan = $s_ringan + ($ringan->num_rows());
                                    $s_lanjut = $s_lanjut + ($lanjut->num_rows());
                                    $s_berat = $s_berat + ($berat->num_rows());
                                    /*------------ bagian calculation end ----------------*/
                                    ?>

                                    <tr>
                                      <td><?php echo $no++ ?></td>
                                      <td><?php echo $k->nik ?></td>
                                      <td><?php echo $k->nama_karyawan ?></td>
                                      <td><?php echo $k->indeks_hr ?></td>
                                      <td><?php echo $k->indeks_jabatan ?></td>
                                      <td><?php echo $jml_hk?></td>
                                      <td><?php echo $efektif?></td>
                                      <td><?php echo round($efektif_p,2)?> %</td>
                                      <td><?php echo $tdk_kerja?></td>
                                      <td><?php echo round($tdk_kerja_p,2)?> %</td>
                                      <td><?php echo round($target_tk,2)?> %</td>
                                      <td><?php echo $jml_norma?></td>
                                      <td><?php echo round($norma_p,2)?> %</td>
                                      <td><?php echo $jml_nonnorma?></td>
                                      <td><?php echo round($nonnorma_p,2)?> %</td>
                                      <td><?php echo $m->num_rows()?></td>
                                      <td><?php echo $p1->num_rows()?></td>
                                      <td><?php echo $sid->num_rows()?></td>
                                      <td><?php echo $kk->num_rows()?></td>
                                      <td><?php echo $h1->num_rows()?></td>
                                      <td><?php echo $h2->num_rows()?></td>
                                      <td><?php echo $p4->num_rows()?></td>
                                      <td><?php echo $c->num_rows()?></td>
                                      <td><?php echo $telat->num_rows()?></td>
                                      <td><?php echo $pulang->num_rows()?></td>
                                      <td><?php echo $jml_keluar?></td>
                                      <td><?php echo $ringan->num_rows()?></td>
                                      <td><?php echo $lanjut->num_rows()?></td>
                                      <td><?php echo $berat->num_rows()?></td>
                                    </tr>
                                    <?php } ?> <!-- end foreach karyawan -->
                                  <?php }

                                
                                if($kar_bagian != 0)
                                  {?>
                                    <tr>
                                      <td colspan="4" rowspan="2">Total Bagian <?php echo $k->indeks_hr?> </td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td><?php echo $kar_bagian ?></td>
                                      <td><?php echo $s_hk?></td>
                                      <td><?php echo $s_efektif?></td>
                                      <td><?php echo round($s_efektif_p,2)?> %</td>
                                      <td><?php echo $s_tdk_kerja?></td>
                                      <td><?php echo round($s_tdk_kerja_p,2)?> %</td>
                                      <td><?php echo round($s_target_tk,2)?> %</td>
                                      <td><?php echo $s_norma?></td>
                                      <td><?php echo round($s_norma_p,2)?> %</td>
                                      <td><?php echo $s_nonnorma?></td>
                                      <td><?php echo round($s_nonnorma_p,2)?> %</td>
                                      <td><?php echo $s_m ?></td>
                                      <td><?php echo $s_p1 ?></td>
                                      <td><?php echo $s_sid ?></td>
                                      <td><?php echo $s_kk ?></td>
                                      <td><?php echo $s_h1 ?></td>
                                      <td><?php echo $s_h2 ?></td>
                                      <td><?php echo $s_p4 ?></td>
                                      <td><?php echo $s_c ?></td>
                                      <td><?php echo $s_telat ?></td>
                                      <td><?php echo $s_pulang ?></td>
                                      <td><?php echo $s_keluar ?></td>
                                      <td><?php echo $s_ringan ?></td>
                                      <td><?php echo $s_lanjut ?></td>
                                      <td><?php echo $s_berat ?></td>
                                    </tr> 
                                    <tr>
                                      <!-- <td colspan="4">Total Bagian<?php echo $b->indeks_hr?></td> -->
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
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
                                      <td><?php echo round((($s_m / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_p1 / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_sid / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_kk / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_h1 / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_h2 / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_p4 / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_c / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_telat / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_pulang / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_keluar / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_ringan / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_lanjut / $s_hk) * 100), 2)?> %</td>
                                      <td><?php echo round((($s_berat / $s_hk) * 100), 2)?> %</td>
                                    </tr>
                                  <?php } ?>
                                  <?php 
                                  /*------------ department calculation start ----------------*/
                                  $d_hk = $d_hk + $s_hk;
                                  $d_efektif = $s_efektif;
                                  $d_efektif_p = ($d_efektif/$d_hk) * 100;
                                  $d_tdk_kerja = $d_tdk_kerja + $s_tdk_kerja;
                                  $d_tdk_kerja_p = ($d_tdk_kerja / $d_hk) * 100;
                                  $d_norma = $d_norma + $s_norma;
                                  $d_nonnorma = $d_nonnorma + $s_nonnorma;
                                  $d_norma_p = ($d_norma / $d_hk) * 100;
                                  $d_nonnorma_p = ($d_nonnorma / $d_hk) * 100;
                                  $d_target_tk = (($d_hk - $d_nonnorma)/$d_hk) * 100;
                                  $d_m = $d_m + $s_m;
                                  $d_p1 = $d_p1 + $s_p1;
                                  $d_sid = $d_sid + $s_sid;
                                  $d_kk = $d_kk + $s_kk;
                                  $d_h1 = $d_h1 + $s_h1;
                                  $d_h2 = $d_h2 + $s_h2;
                                  $d_p4 = $d_p4 + $s_p4;
                                  $d_c = $d_c + $s_c;
                                  $d_telat = $d_telat + $s_telat;
                                  $d_pulang = $d_pulang + $s_pulang;
                                  $d_keluar = $d_keluar + $s_keluar;
                                  $d_ringan = $d_ringan + $s_ringan;
                                  $d_lanjut = $d_lanjut + $s_lanjut;
                                  $d_berat = $d_berat + $s_berat;
                                  /*------------ department calculation end ----------------*/ ?>
                              <?php } ?><!--  end forach bagian -->
                                  <tr>
                                    <td colspan="4" rowspan="2">Total Department <?php echo $dp->nama_department?> </td>
                                    <td style="display:none"></td>
                                    <td style="display:none"></td>
                                    <td style="display:none"></td>
                                    <td><?php echo $kar_dept?></td>
                                    <td><?php echo $d_hk?></td>
                                    <td><?php echo $d_efektif?></td>
                                    <td><?php echo round($d_efektif_p,2)?> %</td>
                                    <td><?php echo $d_tdk_kerja?></td>
                                    <td><?php echo round($d_tdk_kerja_p,2)?> %</td>
                                    <td><?php echo round($d_target_tk,2)?> %</td>
                                    <td><?php echo $d_norma?></td>
                                    <td><?php echo round($d_norma_p,2)?> %</td>
                                    <td><?php echo $d_nonnorma?></td>
                                    <td><?php echo round($d_nonnorma_p,2)?> %</td>
                                    <td><?php echo $d_m ?></td>
                                    <td><?php echo $d_p1 ?></td>
                                    <td><?php echo $d_sid ?></td>
                                    <td><?php echo $d_kk ?></td>
                                    <td><?php echo $d_h1 ?></td>
                                    <td><?php echo $d_h2 ?></td>
                                    <td><?php echo $d_p4 ?></td>
                                    <td><?php echo $d_c ?></td>
                                    <td><?php echo $d_telat ?></td>
                                    <td><?php echo $d_pulang ?></td>
                                    <td><?php echo $d_keluar ?></td>
                                    <td><?php echo $d_ringan ?></td>
                                    <td><?php echo $d_lanjut ?></td>
                                    <td><?php echo $d_berat ?></td>
                                  </tr>
                                  <tr>
                                  <!-- <td colspan="4">Total Department <?php echo $dp->nama_department?></td> -->
                                   <td style="display:none"></td>
                                   <td style="display:none"></td>
                                   <td style="display:none"></td>
                                   <td style="display:none"></td>
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
                                    <td><?php echo round((($d_m / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_p1 / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_sid / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_kk / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_h1 / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_h2 / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_p4 / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_c / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_telat / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_pulang / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_keluar / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_ringan / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_lanjut / $d_hk) * 100), 2)?> %</td>
                                    <td><?php echo round((($d_berat / $d_hk) * 100), 2)?> %</td>
                                </tr>
                                 <?php  /*------------ department calculation start ----------------*/
                                  $dg_hk = $dg_hk + $d_hk;
                                  $dg_efektif = $dg_efektif;
                                  $dg_efektif_p = ($dg_efektif/$d_hk) * 100;
                                  $dg_tdk_kerja = $dg_tdk_kerja + $d_tdk_kerja;
                                  $dg_tdk_kerja_p = ($dg_tdk_kerja / $dg_hk) * 100;
                                  $dg_norma = $dg_norma + $d_norma;
                                  $dg_nonnorma = $dg_nonnorma + $d_nonnorma;
                                  $dg_norma_p = ($dg_norma / $dg_hk) * 100;
                                  $dg_nonnorma_p = ($dg_nonnorma / $dg_hk) * 100;
                                  $dg_target_tk = (($dg_hk - $dg_nonnorma)/$dg_hk) * 100;
                                  $dg_m = $dg_m + $d_m;
                                  $dg_p1 = $dg_p1 + $d_p1;
                                  $dg_sid = $dg_sid + $d_sid;
                                  $dg_kk = $dg_kk + $d_kk;
                                  $dg_h1 = $dg_h1 + $d_h1;
                                  $dg_h2 = $dg_h2 + $d_h2;
                                  $dg_p4 = $dg_p4 + $d_p4;
                                  $dg_c = $dg_c + $d_c;
                                  $dg_telat = $dg_telat + $d_telat;
                                  $dg_pulang = $dg_pulang + $d_pulang;
                                  $dg_keluar = $dg_keluar + $d_keluar;
                                  $dg_ringan = $dg_ringan + $d_ringan;
                                  $dg_lanjut = $dg_lanjut + $d_lanjut;
                                  $dg_berat = $dg_berat + $d_berat;
                                  /*------------ department calculation end ----------------*/?>
                                  <?php  /*------------ chitose calculation start ----------------*/
                                  $c_hk = $c_hk + $d_hk;
                                  $c_efektif = $d_efektif;
                                  $c_efektif_p = ($c_efektif/$c_hk) * 100;
                                  $c_tdk_kerja = $c_tdk_kerja + $d_tdk_kerja;
                                  $c_tdk_kerja_p = ($c_tdk_kerja / $c_hk) * 100;
                                  $c_norma = $c_norma + $d_norma;
                                  $c_nonnorma = $c_nonnorma + $d_nonnorma;
                                  $c_norma_p = ($c_norma / $c_hk) * 100;
                                  $c_nonnorma_p = ($c_nonnorma / $c_hk) * 100;
                                  $c_target_tk = (($c_hk - $c_nonnorma)/$c_hk) * 100;
                                  $c_m = $c_m + $d_m;
                                  $c_p1 = $c_p1 + $d_p1;
                                  $c_sid = $c_sid + $d_sid;
                                  $c_kk = $c_kk + $d_kk;
                                  $c_h1 = $c_h1 + $d_h1;
                                  $c_h2 = $c_h2 + $d_h2;
                                  $c_p4 = $c_p4 + $d_p4;
                                  $c_c = $c_c + $d_c;
                                  $c_telat = $c_telat + $d_telat;
                                  $c_pulang = $c_pulang + $d_pulang;
                                  $c_keluar = $c_keluar + $d_keluar;
                                  $c_ringan = $c_ringan + $d_ringan;
                                  $c_lanjut = $c_lanjut + $d_lanjut;
                                  $c_berat = $c_berat + $d_berat;
                                  /*------------ chitose calculation end ----------------*/?>
                            <?php }?> <!--  end foreach dept -->
                              <tr>
                                <td colspan="4" rowspan="2">Total Department Group <?php echo $fdivisi[$dg]?> </td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td><?php echo $kar_dept_group?></td>
                                <td><?php echo $dg_hk?></td>
                                <td><?php echo $dg_efektif?></td>
                                <td><?php echo round($dg_efektif_p,2)?> %</td>
                                <td><?php echo $dg_tdk_kerja?></td>
                                <td><?php echo round($dg_tdk_kerja_p,2)?> %</td>
                                <td><?php echo round($dg_target_tk,2)?> %</td>
                                <td><?php echo $dg_norma?></td>
                                <td><?php echo round($dg_norma_p,2)?> %</td>
                                <td><?php echo $dg_nonnorma?></td>
                                <td><?php echo round($dg_nonnorma_p,2)?> %</td>
                                <td><?php echo $dg_m ?></td>
                                <td><?php echo $dg_p1 ?></td>
                                <td><?php echo $dg_sid ?></td>
                                <td><?php echo $dg_kk ?></td>
                                <td><?php echo $dg_h1 ?></td>
                                <td><?php echo $dg_h2 ?></td>
                                <td><?php echo $dg_p4 ?></td>
                                <td><?php echo $dg_c ?></td>
                                <td><?php echo $dg_telat ?></td>
                                <td><?php echo $dg_pulang ?></td>
                                <td><?php echo $dg_keluar ?></td>
                                <td><?php echo $dg_ringan ?></td>
                                <td><?php echo $dg_lanjut ?></td>
                                <td><?php echo $dg_berat ?></td>
                              </tr>
                              <tr>
                                <!-- <td colspan="4">Total Dept Group <?php echo $fdivisi[$dg]?>g</td> -->
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
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
                                <td><?php echo round((($dg_m / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_p1 / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_sid / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_kk / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_h1 / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_h2 / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_p4 / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_c / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_telat / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_pulang / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_keluar / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_ringan / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_lanjut / $dg_hk) * 100), 2)?> %</td>
                                <td><?php echo round((($dg_berat / $dg_hk) * 100), 2)?> %</td>
                              </tr>
                    <?php } //end loop dept_group      ?>
                                <tr>
                                  <td colspan="4" rowspan="2">Total PT. Chitose </td>
                                  <td style="display:none"></td>
                                  <td style="display:none"></td>
                                  <td style="display:none"></td>
                                  <td><?php echo $kar_chitose?></td>
                                  <td><?php echo $c_hk?></td>
                                  <td><?php echo $c_efektif?></td>
                                  <td><?php echo round($c_efektif_p,2)?> %</td>
                                  <td><?php echo $c_tdk_kerja?></td>
                                  <td><?php echo round($c_tdk_kerja_p,2)?> %</td>
                                  <td><?php echo round($c_target_tk,2)?> %</td>
                                  <td><?php echo $c_norma?></td>
                                  <td><?php echo round($c_norma_p,2)?> %</td>
                                  <td><?php echo $c_nonnorma?></td>
                                  <td><?php echo round($c_nonnorma_p,2)?> %</td>
                                  <td><?php echo $c_m ?></td>
                                  <td><?php echo $c_p1 ?></td>
                                  <td><?php echo $c_sid ?></td>
                                  <td><?php echo $c_kk ?></td>
                                  <td><?php echo $c_h1 ?></td>
                                  <td><?php echo $c_h2 ?></td>
                                  <td><?php echo $c_p4 ?></td>
                                  <td><?php echo $c_c ?></td>
                                  <td><?php echo $c_telat ?></td>
                                  <td><?php echo $c_pulang ?></td>
                                  <td><?php echo $c_keluar ?></td>
                                  <td><?php echo $c_ringan ?></td>
                                  <td><?php echo $c_lanjut ?></td>
                                  <td><?php echo $c_berat ?></td>
                                </tr>
                                <tr>
                                  <!-- <td colspan="4">Total PT. Chitose Internasional Tbk</td> -->
                                  <td style="display:none"></td>
                                  <td style="display:none"></td>
                                  <td style="display:none"></td>
                                  <td style="display:none"></td>
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
                                  <td><?php echo round((($c_m / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_p1 / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_sid / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_kk / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_h1 / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_h2 / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_p4 / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_c / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_telat / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_pulang / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_keluar / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_ringan / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_lanjut / $c_hk) * 100), 2)?> %</td>
                                  <td><?php echo round((($c_berat / $c_hk) * 100), 2)?> %</td>
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
      tahun = document.getElementById('tahun').value;
      norma = document.getElementById('norma').value;
      nonnorma = document.getElementById('nonnorma').value;
      judul = norma+' | '+nonnorma;
      console.log(judul);
      var table = $('#rekap_absen').DataTable( {
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        "bSort" : false,
        dom: 'Bfrtip',
        buttons: [
        {
          extend: 'excel',
          className: 'btn btn-primary btn-sm',
          title: 'Laporan Absensi Tahun'+' '+tahun,
        }
        ]
      } );
    } );

  </script>
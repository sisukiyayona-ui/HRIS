    <style>
      td {
        text-align: center;
        color: black;
      }
    </style>
    <!-- page content -->
    <div class="right_col" role="main">
      <div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Rekapitulasi Persentase Kehadiran Bulanan Karyawan</h3>
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Bulan <?php echo $nama_bulan ?> Tahun <?php echo $tahun ?> </h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <?php echo $detail_norma ?><br>
                <?php echo $detail_nnorma ?><br><br>
                <input type="hidden" id="bulan" value="<?php echo $nama_bulan ?>">
                <input type="hidden" id="tahun" value="<?php echo $tahun ?>">
                <input type="hidden" id="norma" value="<?php echo $detail_norma ?>">
                <input type="hidden" id="nonnorma" value="<?php echo $detail_nnorma ?>">

                <table class="table table-bordered" id="rekap_absen" border="1">
                  <thead>
                    <tr>
                      <td rowspan="2">No</td>
                      <td rowspan="2">NIK</td>
                      <td rowspan="2">Nama Karyawan</td>
                      <td rowspan="2">Bagian</td>
                      <td rowspan="2">Jabatan</td>
                      <td rowspan="2">Hari Kerja</td>
                      <td rowspan="2">% Kehadiran</td>
                      <td rowspan="2">Hari Efektif</td>
                      <td rowspan="2">%</td>
                      <td rowspan="2">TDK MSK</td>
                      <td rowspan="2">%</td>
                      <td rowspan="2">Norma</td>
                      <td rowspan="2">%</td>
                      <td rowspan="2">Non Norma</td>
                      <td rowspan="2">%</td>
                      <!-- <td rowspan="2">% Produktif</td> -->
                      <td colspan="8">Kategori Absensi</td>
                      <td colspan="2">Lainya</td>
                      <td colspan="6">Surat</td>
                      <td colspan="3">Analisa Sakit</td>
                      <!-- <td rowspan="2">Real Kerja</td> -->
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
                      <td>GH</td>
                      <td>Terlambat</td>
                      <td>Durasi</td>
                      <td>Pulang</td>
                      <td>Durasi</td>
                      <td>Keluar</td>
                      <td>Durasi</td>
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
                    $c_gh = 0;
                    $c_telat = 0;
                    $cdur_telat = 0;
                    $c_pulang = 0;
                    $cdur_pulang = 0;
                    $c_keluar = 0;
                    $cdur_kel = 0;
                    $c_ringan = 0;
                    $c_lanjut = 0;
                    $c_berat = 0;
                    $c_lbh = 0;
                    $cnon_prod = 0;

                    /*------------ chitose inisiasi awal end ----------------*/
                    $jml_hkn = $jml_hkn;
                    $default_hk = $jml_hkn;
                    $hk_jam = $default_hk * 8;

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
                      $dg_gh = 0;
                      $dg_telat = 0;
                      $dgdur_telat = 0;
                      $dg_pulang = 0;
                      $dgdur_pulang = 0;
                      $dg_keluar = 0;
                      $dgdur_kel = 0;
                      $dg_ringan = 0;
                      $dg_lanjut = 0;
                      $dg_berat = 0;
                      $dg_lbh = 0;
                      $dgnon_prod = 0;
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
                        $d_hk = 0;
                        $d_hk2 = 0;
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
                        $d_mu = 0;
                        $d_gh = 0;
                        $d_telat = 0;
                        $ddur_telat = 0;
                        $d_pulang = 0;
                        $ddur_pulang = 0;
                        $d_keluar = 0;
                        $ddur_kel = 0;
                        $d_ringan = 0;
                        $d_lanjut = 0;
                        $d_berat = 0;
                        $d_lbh = 0;
                        $dnon_prod = 0;
                        /*------------ dept inisiasi awal end ----------------*/
                        // echo "Set HK Dept : ".$dp->nama_department." : ".$d_hk;
                        //bagian by dept
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
                        foreach ($bagian->result() as $b) {
                          /*------------ bagian inisiasi awal end ----------------*/
                          $no = 0;
                          $s_hk = 0;
                          $s_hk2 = 0;
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
                          $s_mu = 0;
                          $s_gh = 0;
                          $s_telat = 0;
                          $sdur_telat = 0;
                          $s_pulang = 0;
                          $sdur_pulang = 0;
                          $s_keluar = 0;
                          $sdur_kel = 0;
                          $s_ringan = 0;
                          $s_lanjut = 0;
                          $s_berat = 0;
                          $s_lbh = 0;
                          $snon_prod = 0;
                          $sprod = 0;
                          /*------------ bagian inisiasi awal end ----------------*/

                          //karyawan by bagian
                          if ($fkaryawan != '') {
                            $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak' and tc = '0' $fkaryawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                          } else {
                            $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak' and tc = '0' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                          }

                          $kar_bagian  = 0;
                          $no = 1;
                          if ($karyawan->num_rows() == 0) {
                            continue;
                          } else {
                            foreach ($karyawan->result() as $k) {
                              $hitung_hk = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan join hris.bagian b on b.recid_bag = k.recid_bag where b.recid_bag = $b->recid_bag and k.recid_karyawan = $k->recid_karyawan and month(h.tanggal) = $bulan  and year(h.tanggal) = $tahun and is_closed = '0' and (status != 24 and status != '28' and status != '31' and status != '32' and status != '33' and status != '36');");
                              $jml_hk = $hitung_hk->num_rows();
                              // if($jml_hk > $default_hk)
                              // {
                              //   $lbh = 1;
                              //   $s_lbh = $s_lbh + 1;
                              //   $d_lbh = $d_lbh + 1;
                              //   $dg_lbh = $dg_lbh + 1;
                              //   $c_lbh = $c_lbh + 1;
                              // }else{
                              //   $lbh = 0;
                              //   $s_lbh = $s_lbh + 0;
                              //   $d_lbh = $d_lbh + 0;
                              //   $c_lbh = $c_lbh + 0;
                              // }
                              // echo "HK ".$k->nama_karyawan." : ".$jml_hk;

                              $kar_bagian = $kar_bagian + 1;
                              $kar_dept = $kar_dept + 1;
                              $kar_dept_group = $kar_dept_group + 1;
                              $kar_chitose = $kar_chitose + 1;
                              $jam_nonprd = 0;

                              $m = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun  and h.status = '11' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $p1 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '5' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $sid = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '2' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $kk = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '25' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $h1 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '6' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $h2 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '7' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $p4 = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '12' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $c = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '3' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $mu = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '34' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $gh = $db2->query("SELECT * FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and h.status = '35' and k.recid_karyawan = $k->recid_karyawan and h.is_closed = '0'");

                              $total_absen = $m->num_rows() + $m->num_rows() + $sid->num_rows() + $kk->num_rows() + $h1->num_rows() + $h2->num_rows() + $p4->num_rows() + $c->num_rows() + $mu->num_rows() + $gh->num_rows();

                              $jam_nonprd = number_format(($total_absen * 8), 2, '.', '');

                              $fnorma = $db2->query("SELECT count(h.recid_absen) as jml_norma FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and $norma and k.recid_karyawan = $k->recid_karyawan and h.is_closed='0'");

                              foreach ($fnorma->result() as $fnorma) {
                                $jml_norma = $fnorma->jml_norma;
                              }

                              $fnnorma = $db2->query("SELECT count(h.recid_absen) as jml_nonnorma FROM hadir_barcode h join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where (month(tanggal) = $bulan) and year(tanggal) = $tahun and $non_norma and k.recid_karyawan = $k->recid_karyawan and h.is_closed='0'");

                              foreach ($fnnorma->result() as $fnnorma) {
                                $jml_nonnorma = $fnnorma->jml_nonnorma;
                              }

                              $telat = $db2->query("SELECT * from izin where month(tgl_izin) = $bulan and year(tgl_izin) = $tahun and (jenis = 'Terlambat' or jenis = 'Terlambat Terencana' or jenis = 'Terlambat Tidak Terencana') and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                              $durasi_telat = 0;
                              if ($telat->num_rows() > 0) {
                                foreach ($telat->result() as $t) {
                                  $tgl_izin = $t->tgl_izin;
                                  $cek_jenis = $db2->query("SELECT * from hadir_barcode h join jenis_absen ja on ja.recid_jenisabsen = h.status where tanggal = '$tgl_izin'and recid_karyawan = $k->recid_karyawan and is_closed = '0';");
                                  if ($cek_jenis->num_rows() > 0) {
                                    foreach ($cek_jenis->result() as $pn) {
                                      $masuk_normal = new DateTime($pn->jam_in);
                                      $jam_masuk = new DateTime($pn->jam_masuk); // jam scan
                                      $selisih = $jam_masuk->diff($masuk_normal);
                                      $jam = $selisih->format('%h');
                                      $menit = $selisih->format('%i');
                                      if ($menit >= 60) {
                                        $jam = $jam + 1;
                                        $menit = $menit - 60;
                                      }
                                      if ($menit >= 0 && $menit <= 9) {
                                        $menit = "0" . $menit;
                                      }
                                      $selisih_masuk = $jam . '.' . $menit;
                                      // echo $pn->jam_masuk . "<br>.";
                                    }
                                    $durasi_telat = number_format(($durasi_telat + $selisih_masuk), 2, '.', '');
                                    $dur = explode(".", $durasi_telat);
                                    if ($dur[1] > 60) {
                                      $dur[0] = $dur[0] + 1;
                                      $dur[1] = $dur[1] - 60;
                                      $dur = $dur[0] . '.' . $dur[1];
                                      $durasi_telat = number_format($dur, 2, '.', '');
                                    }
                                  }
                                }
                              } else {
                                $durasi_telat = 0;
                              }

                              $jam_nonprd = number_format(($jam_nonprd + $durasi_telat), 2, '.', '');

                              $pulang = $db2->query("SELECT * from izin where month(tgl_izin) = $bulan and year(tgl_izin) = $tahun and jenis = 'Pulang' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                              $durasi_pulang = 0;
                              foreach ($pulang->result() as $p) {
                                $tgl_izin = $p->tgl_izin;
                                $cek_jenis = $db2->query("SELECT * from hadir_barcode h join jenis_absen ja on ja.recid_jenisabsen = h.status where tanggal = '$tgl_izin'and recid_karyawan = $k->recid_karyawan and is_closed = '0';");
                                if ($cek_jenis->num_rows() > 0) {
                                  foreach ($cek_jenis->result() as $pn) {
                                    $pulang_normal = new DateTime($pn->jam_out);
                                    $jam_keluar = new DateTime($pn->jam_keluar); // jam scan
                                    // $jam_keluar = new DateTime(($pn->jam_keluar) ?? ''); // jam scan
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
                                    $selisih_pulang = $jam . '.' . $menit;
                                  }
                                  $durasi_pulang = number_format(($durasi_pulang + $selisih_pulang), 2, '.', '');
                                  $dur = explode(".", $durasi_pulang);
                                  if ($dur[1] > 60) {
                                    $dur[0] = $dur[0] + 1;
                                    $dur[1] = $dur[1] - 60;
                                    $dur = $dur[0] . '.' . $dur[1];
                                    $durasi_pulang = number_format($dur, 2, '.', '');
                                  }
                                } else {
                                  $durasi_pulang = 0;
                                }
                              }

                              $jam_nonprd = number_format(($jam_nonprd + $durasi_pulang), 2, '.', '');

                              $jml_keluar = 0;
                              $keluar = $db2->query("SELECT *, timediff(jam_in, jam_out) as lama from izin where jenis = 'Keluar' and month(tgl_izin) = $bulan and year(tgl_izin) = $tahun and recid_karyawan = $k->recid_karyawan and is_delete = '0'");
                              $total_keluar = $keluar->num_rows();
                              $durasi_kel = 0;
                              $jam_kel = 0;
                              $menit_kel = 0;
                              if ($keluar->num_rows() > 0) {
                                foreach ($keluar->result() as $kl) {
                                  if ($kl->jam_out >= "11:30" and $kl->jam_in <= "12:30") {
                                  } else {
                                    $jml_keluar = $jml_keluar + 1;
                                    $durasi_kel = number_format(($durasi_kel + $kl->over_durasi), 2, '.', '');
                                    $dur = explode(".", $durasi_kel);
                                    if ($dur[1] > 60) {
                                      $dur[0] = $dur[0] + 1;
                                      $dur[1] = $dur[1] - 60;
                                      $dur = $dur[0] . '.' . $dur[1];
                                      $durasi_kel = number_format($dur, 2, '.', '');
                                    }
                                  }
                                }
                              } else {
                                $durasi_kel = 0;
                              }

                              $jam_nonprd = number_format(($jam_nonprd + $durasi_kel), 2, '.', '');
                              // echo "Jam Produktifitas : " . ($default_hk * 8) . "<br>";
                              // echo "Jam Non Produktif : " . $jam_nonprd . "<br>";
                              // echo "Jam Produktif (x): " . ($default_hk * 8) - $jam_nonprd . "<br><br>";
                              if ($jam_nonprd != 0) {
                                $jam_prod = ((($default_hk * 8) - $jam_nonprd) / ($default_hk * 8)) * 100;
                              } else {
                                $jam_prod = ((($default_hk * 8)) / ($default_hk * 8)) * 100;
                              }
                              $jam_prod = number_format($jam_prod, 2, '.', '');

                              // echo ($default_hk * 8) . " / " . $jam_nonprd . " = " . $jam_prod . "<br>";

                              $ringan = $db2->query("SELECT * from absensi_hris where month(tanggal) = $bulan and year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Ringan' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");

                              $lanjut =  $db2->query("SELECT * from absensi_hris where month(tanggal) = $bulan and year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Berkelanjutan' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");

                              $berat =  $db2->query("SELECT * from absensi_hris where month(tanggal) = $bulan and year(tanggal) = $tahun and jenis_absen = 2 and kategori = 'Berat' and recid_karyawan = $k->recid_karyawan and is_delete = '0'");



                              // $efektif = $default_hk - ($jml_nonnorma + $jml_norma);
                              $tdk_kerja = $jml_nonnorma + $jml_norma;
                              $efektif = $default_hk - $tdk_kerja;
                              $efektif_p = ($efektif / $default_hk) * 100;
                              $tdk_kerja_p = ($tdk_kerja / $default_hk) * 100;
                              $target_tk = (($default_hk - $jml_nonnorma) / $default_hk) * 100;
                              $norma_p = ($jml_norma / $default_hk) * 100;
                              $nonnorma_p = ($jml_nonnorma / $default_hk) * 100;

                              // $efektif = $default_hk - ($jml_nonnorma + $jml_norma);
                              // if($lbh == 1)
                              // {
                              //   $efektif_p = ($efektif / $default_hk) * 100;
                              //   $tdk_kerja = $jml_norma + $jml_nonnorma; /* $default_hk - $efektif; */
                              //   $tdk_kerja_p = ($tdk_kerja / $default_hk) * 100;
                              //   $target_tk = (($default_hk - $jml_nonnorma) / $default_hk) * 100;
                              //   $norma_p = ($jml_norma / $default_hk) * 100;
                              //   $nonnorma_p = ($jml_nonnorma / $default_hk) * 100;
                              // }else{
                              //   $efektif_p = ($efektif / $jml_hkn) * 100;
                              //   $tdk_kerja = $jml_hkn - $efektif;
                              //   $tdk_kerja_p = ($tdk_kerja / $jml_hkn) * 100;
                              //   $target_tk = (($jml_hkn - $jml_nonnorma) / $jml_hkn) * 100;
                              //   $norma_p = ($jml_norma / $jml_hkn) * 100;
                              //   $nonnorma_p = ($jml_nonnorma / $jml_hkn) * 100;
                              // }

                              /*------------ bagian calculation start ----------------*/
                              $s_hk = $s_hk + $default_hk;
                              // $ = $s_hk2 + $default_hk;
                              // if($s_lbh > 0)
                              // {
                              //   $s_hk2 = $s_hk2 + $jml_hkn;
                              // }else{
                              //   $s_hk2 = $s_hk2 + $default_hk;
                              // }
                              $s_efektif = $s_efektif + $efektif;
                              $s_efektif_p = ($s_efektif / $s_hk) * 100;
                              $s_tdk_kerja = $s_tdk_kerja + $tdk_kerja;
                              $s_tdk_kerja_p = ($s_tdk_kerja / $s_hk) * 100;
                              $s_norma = $s_norma + $jml_norma;
                              $s_nonnorma = $s_nonnorma + $jml_nonnorma;
                              $s_norma_p = ($s_norma / $s_hk) * 100;
                              $s_nonnorma_p = ($s_nonnorma / $s_hk) * 100;
                              $s_target_tk = (($s_hk - $s_nonnorma) / $s_hk) * 100;
                              $s_m = $s_m + ($m->num_rows());
                              $s_p1 = $s_p1 + ($p1->num_rows());
                              $s_sid = $s_sid + ($sid->num_rows());
                              $s_kk = $s_kk + ($kk->num_rows());
                              $s_h1 = $s_h1 + ($h1->num_rows());
                              $s_h2 = $s_h2 + ($h2->num_rows());
                              $s_p4 = $s_p4 + ($p4->num_rows());
                              $s_c = $s_c + ($c->num_rows());
                              $s_mu = $s_mu + ($mu->num_rows());
                              $s_gh = $s_gh + ($gh->num_rows());
                              $s_telat = $s_telat + ($telat->num_rows());
                              // $sdur_telat = $sdur_telat + $durasi_telat;
                              $sdur_telat = number_format(($sdur_telat + $durasi_telat), 2, '.', '');
                              $s_pulang = $s_pulang + ($pulang->num_rows());
                              $sdur_pulang = number_format(($sdur_pulang + $durasi_pulang), 2, '.', '');
                              $s_keluar = $s_keluar + $jml_keluar;
                              $sdur_kel = number_format(($sdur_kel + $durasi_kel), 2, '.', '');
                              $s_ringan = $s_ringan + ($ringan->num_rows());
                              $s_lanjut = $s_lanjut + ($lanjut->num_rows());
                              $s_berat = $s_berat + ($berat->num_rows());
                              $snon_prod = number_format(($snon_prod + $jam_nonprd), 2, '.', '');

                              /*------------ bagian calculation end ----------------*/
                    ?>
                              <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $k->nik ?></td>
                                <td><?php echo $k->nama_karyawan ?></td>
                                <td><?php echo $k->indeks_hr ?></td>
                                <td><?php echo $k->indeks_jabatan ?></td>
                                <td><?php echo $default_hk ?></td>
                                <td <?php echo (round($target_tk, 2) > 100) ? 'style="background-color:#eeef4e"' : 'style="background-color:#9eef94"'; ?>><?php echo round($target_tk, 2) ?> %</td>
                                <td><?php echo $efektif ?></td>
                                <td><?php echo round($efektif_p, 2) ?> %</td>
                                <td><?php echo $tdk_kerja ?></td>
                                <td><?php echo round($tdk_kerja_p, 2) ?> %</td>
                                <td><?php echo $jml_norma ?></td>
                                <td><?php echo round($norma_p, 2) ?> %</td>
                                <td><?php echo $jml_nonnorma ?></td>
                                <td><?php echo round($nonnorma_p, 2) ?> %</td>
                                <!-- <td><?php echo $jam_prod ?> % </td> -->
                                <td><?php echo $m->num_rows() ?></td>
                                <td><?php echo $p1->num_rows() ?></td>
                                <td><?php echo $sid->num_rows() ?></td>
                                <td><?php echo $kk->num_rows() ?></td>
                                <td><?php echo $h1->num_rows() ?></td>
                                <td><?php echo $h2->num_rows() ?></td>
                                <td><?php echo $p4->num_rows() ?></td>
                                <td><?php echo $c->num_rows() ?></td>
                                <td><?php echo $mu->num_rows() ?></td>
                                <td><?php echo $gh->num_rows() ?></td>
                                <td><?php echo $telat->num_rows() ?></td>
                                <td><?php if ($durasi_telat != 0) {
                                      $durasi = $durasi_telat;
                                      $dur = explode(".", $durasi);
                                      if ($dur[1] > 60) {
                                        $dur[0] = $dur[0] + 1;
                                        $dur[1] = $dur[1] - 60;
                                      }
                                      echo $dur[0] . " jam " . $dur[1] . " menit";
                                    } ?></td>
                                <td><?php echo $pulang->num_rows() ?></td>
                                <td><?php if ($durasi_pulang != 0) {
                                      $durasi = $durasi_pulang;
                                      $dur = explode(".", $durasi);
                                      if ($dur[1] > 60) {
                                        $dur[0] = $dur[0] + 1;
                                        $dur[1] = $dur[1] - 60;
                                      }
                                      echo $dur[0] . " jam " . $dur[1] . " menit";
                                    } ?></td>
                                <td><?php echo $jml_keluar ?></td>
                                <td><?php if ($durasi_kel != 0) {
                                      $durasi = $durasi_kel;
                                      $dur = explode(".", $durasi);
                                      if ($dur[1] > 60) {
                                        $dur[0] = $dur[0] + 1;
                                        $dur[1] = $dur[1] - 60;
                                      }
                                      echo $dur[0] . " jam " . $dur[1] . " menit";
                                    } ?></td>
                                <td><?php echo $ringan->num_rows() ?></td>
                                <td><?php echo $lanjut->num_rows() ?></td>
                                <td><?php echo $berat->num_rows() ?></td>
                                <!-- <td><?php echo $jml_hk ?></td> -->
                              </tr>
                            <?php } ?>
                            <!-- end foreach karyawan -->
                          <?php }

                          $sprod =  number_format((($snon_prod / ($s_hk * 8)) * 100), 2, '.', '');
                          $sprod = number_format((100 - $sprod), 2, '.', '');
                          // $sprod = number_format(($snon_prod / ($s_hk * 8)), 2, '.', '');
                          if ($kar_bagian != 0) { ?>
                            <tr>
                              <td colspan="4" rowspan="2">Total Bagian <?php echo $k->indeks_hr ?> </td>
                              <td style="display:none"></td>
                              <td style="display:none"></td>
                              <td style="display:none"></td>
                              <td><?php echo $kar_bagian ?></td>
                              <td><?php echo $s_hk ?></td>
                              <td <?php echo (round($target_tk, 2) > 100) ? 'style="background-color:#eeef4e"' : 'style="background-color:#9eef94"'; ?>><?php echo round($s_target_tk, 2) ?> %</td>
                              <td><?php echo $s_efektif ?></td>
                              <td><?php echo round($s_efektif_p, 2) ?> %</td>
                              <td><?php echo $s_tdk_kerja ?></td>
                              <td><?php echo round($s_tdk_kerja_p, 2) ?> %</td>
                              <td><?php echo $s_norma ?></td>
                              <td><?php echo round($s_norma_p, 2) ?> %</td>
                              <td><?php echo $s_nonnorma ?></td>
                              <td><?php echo round($s_nonnorma_p, 2) ?> %</td>
                              <!-- <td><?php echo $sprod ?>%</td> -->
                              <td><?php echo $s_m ?></td>
                              <td><?php echo $s_p1 ?></td>
                              <td><?php echo $s_sid ?></td>
                              <td><?php echo $s_kk ?></td>
                              <td><?php echo $s_h1 ?></td>
                              <td><?php echo $s_h2 ?></td>
                              <td><?php echo $s_p4 ?></td>
                              <td><?php echo $s_c ?></td>
                              <td><?php echo $s_mu ?></td>
                              <td><?php echo $s_gh ?></td>
                              <td><?php echo $s_telat ?></td>
                              <td><?php if ($sdur_telat != 0) {
                                    $durasi = $sdur_telat;
                                    $dur = explode(".", $durasi);
                                    if ($dur[1] > 60) {
                                      $dur[0] = $dur[0] + 1;
                                      $dur[1] = $dur[1] - 60;
                                    }
                                    echo $dur[0] . " jam " . $dur[1] . " menit";
                                  } ?></td>
                              <td><?php echo $s_pulang ?></td>
                              <td><?php if ($sdur_pulang != 0) {
                                    $durasi = $sdur_pulang;
                                    $dur = explode(".", $durasi);
                                    if ($dur[1] > 60) {
                                      $dur[0] = $dur[0] + 1;
                                      $dur[1] = $dur[1] - 60;
                                    }
                                    echo $dur[0] . " jam " . $dur[1] . " menit";
                                  } ?></td>
                              <td><?php echo $s_keluar ?></td>
                              <td><?php if ($sdur_kel != 0) {
                                    $durasi = $sdur_kel;
                                    $dur = explode(".", $durasi);
                                    if ($dur[1] > 60) {
                                      $dur[0] = $dur[0] + 1;
                                      $dur[1] = $dur[1] - 60;
                                    }
                                    echo $dur[0] . " jam " . $dur[1] . " menit";
                                  }  ?></td>
                              <td><?php echo $s_ringan ?></td>
                              <td><?php echo $s_lanjut ?></td>
                              <td><?php echo $s_berat ?></td>
                              <!-- <td><?php echo $s_hk2 ?></td> -->
                            </tr>
                            <tr>
                              <!-- <td colspan="4">Total Bagian<?php echo $b->indeks_hr ?></td> -->
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
                              <td><?php echo round((($s_m / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_p1 / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_sid / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_kk / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_h1 / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_h2 / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_p4 / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_c / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_mu / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_gh / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_telat / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($sdur_telat / ($s_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                              <td><?php echo round((($s_pulang / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($sdur_pulang / ($s_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                              <td><?php echo round((($s_keluar / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($sdur_kel / ($s_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                              <td><?php echo round((($s_ringan / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_lanjut / $s_hk) * 100), 2) ?> %</td>
                              <td><?php echo round((($s_berat / $s_hk) * 100), 2) ?> %</td>
                              <!-- <td></td> -->
                            </tr>
                          <?php } ?>
                          <?php
                          /*------------ department calculation start ----------------*/
                          $d_hk = $d_hk + $s_hk;
                          $dhk_jam = $d_hk * 8;

                          // $d_hk2 = $d_hk2 + $s_hk2;
                          // if($d_lbh > 0)
                          // {
                          //   $ = $d_hk2 + $s_hk2;
                          // }else{
                          //   $d_hk2 = $d_hk2 + $s_hk;
                          // }
                          $d_efektif = $d_efektif + $s_efektif;
                          $d_efektif_p = ($d_efektif / $d_hk) * 100;
                          $d_tdk_kerja = $d_tdk_kerja + $s_tdk_kerja;
                          $d_tdk_kerja_p = ($d_tdk_kerja / $d_hk) * 100;
                          $d_norma = $d_norma + $s_norma;
                          $d_nonnorma = $d_nonnorma + $s_nonnorma;
                          $d_norma_p = ($d_norma / $d_hk) * 100;
                          $d_nonnorma_p = ($d_nonnorma / $d_hk) * 100;
                          $d_target_tk = (($d_hk - $d_nonnorma) / $d_hk) * 100;
                          $d_m = $d_m + $s_m;
                          $d_p1 = $d_p1 + $s_p1;
                          $d_sid = $d_sid + $s_sid;
                          $d_kk = $d_kk + $s_kk;
                          $d_h1 = $d_h1 + $s_h1;
                          $d_h2 = $d_h2 + $s_h2;
                          $d_p4 = $d_p4 + $s_p4;
                          $d_c = $d_c + $s_c;
                          $d_mu = $d_mu + $s_mu;
                          $d_gh = $d_gh + $s_gh;
                          $d_telat = $d_telat + $s_telat;
                          $ddur_telat = number_format(($ddur_telat + $sdur_telat), 2, '.', '');
                          $d_pulang = $d_pulang + $s_pulang;
                          $ddur_pulang = number_format(($ddur_pulang + $sdur_pulang), 2, '.', '');
                          $d_keluar = $d_keluar + $s_keluar;
                          $ddur_kel = number_format(($ddur_kel + $sdur_kel), 2, '.', '');
                          $d_ringan = $d_ringan + $s_ringan;
                          $d_lanjut = $d_lanjut + $s_lanjut;
                          $d_berat = $d_berat + $s_berat;
                          $dnon_prod = number_format(($dnon_prod + $snon_prod), 2, '.', '');
                          /*------------ department calculation end ----------------*/ ?>
                        <?php }
                        // echo $dnon_prod . "/" . ($d_hk * 8);
                        $dprod =  number_format((($dnon_prod / ($d_hk * 8)) * 100), 2, '.', '');
                        $dprod = number_format((100 - $dprod), 2, '.', '');
                        ?>
                        <!--  end forach bagian -->
                        <tr>
                          <td colspan="4" rowspan="2">Total Department <?php echo $dp->nama_department ?> </td>
                          <td style="display:none"></td>
                          <td style="display:none"></td>
                          <td style="display:none"></td>
                          <td><?php echo $kar_dept ?></td>
                          <td><?php echo $d_hk ?></td>
                          <td <?php echo (round($target_tk, 2) > 100) ? 'style="background-color:#eeef4e"' : 'style="background-color:#9eef94"'; ?>><?php echo round($d_target_tk, 2) ?> %</td>
                          <td><?php echo $d_efektif ?></td>
                          <td><?php echo round($d_efektif_p, 2) ?> %</td>
                          <td><?php echo $d_tdk_kerja ?></td>
                          <td><?php echo round($d_tdk_kerja_p, 2) ?> %</td>
                          <td><?php echo $d_norma ?></td>
                          <td><?php echo round($d_norma_p, 2) ?> %</td>
                          <td><?php echo $d_nonnorma ?></td>
                          <td><?php echo round($d_nonnorma_p, 2) ?> %</td>
                          <!-- <td><?php echo $dprod ?>%</td> -->
                          <td><?php echo $d_m ?></td>
                          <td><?php echo $d_p1 ?></td>
                          <td><?php echo $d_sid ?></td>
                          <td><?php echo $d_kk ?></td>
                          <td><?php echo $d_h1 ?></td>
                          <td><?php echo $d_h2 ?></td>
                          <td><?php echo $d_p4 ?></td>
                          <td><?php echo $d_c ?></td>
                          <td><?php echo $d_mu ?></td>
                          <td><?php echo $d_gh ?></td>
                          <td><?php echo $d_telat ?></td>
                          <td><?php if ($ddur_telat != 0) {
                                $durasi = $ddur_telat;
                                $dur = explode(".", $durasi);
                                if ($dur[1] > 60) {
                                  $dur[0] = $dur[0] + 1;
                                  $dur[1] = $dur[1] - 60;
                                }
                                echo $dur[0] . " jam " . $dur[1] . " menit";
                              }  ?></td>
                          <td><?php echo $d_pulang ?></td>
                          <td><?php if ($ddur_pulang != 0) {
                                $durasi = $ddur_pulang;
                                $dur = explode(".", $durasi);
                                if ($dur[1] > 60) {
                                  $dur[0] = $dur[0] + 1;
                                  $dur[1] = $dur[1] - 60;
                                }
                                echo $dur[0] . " jam " . $dur[1] . " menit";
                              }  ?></td>
                          <td><?php echo $d_keluar ?></td>
                          <td><?php if ($ddur_kel != 0) {
                                $durasi = $ddur_kel;
                                $dur = explode(".", $durasi);
                                if ($dur[1] > 60) {
                                  $dur[0] = $dur[0] + 1;
                                  $dur[1] = $dur[1] - 60;
                                }
                                echo $dur[0] . " jam " . $dur[1] . " menit";
                              } ?></td>
                          <td><?php echo $d_ringan ?></td>
                          <td><?php echo $d_lanjut ?></td>
                          <td><?php echo $d_berat ?></td>
                          <!-- <td><?php echo $d_hk2 ?></td> -->
                        </tr>
                        <tr>
                          <!-- <td colspan="4">Total Department <?php echo $dp->nama_department ?></td> -->
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
                          <td><?php echo round((($d_m / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_p1 / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_sid / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_kk / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_h1 / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_h2 / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_p4 / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_c / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_mu / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_gh / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_telat / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($ddur_telat / ($d_hk * 8)) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_pulang / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($ddur_pulang / ($d_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                          <td><?php echo round((($d_keluar / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($ddur_kel / ($d_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                          <td><?php echo round((($d_ringan / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_lanjut / $d_hk) * 100), 2) ?> %</td>
                          <td><?php echo round((($d_berat / $d_hk) * 100), 2) ?> %</td>
                          <!-- <td></td> -->
                        </tr>
                        <?php  /*------------ department calculation start ----------------*/
                        $dg_hk = $dg_hk + $d_hk;
                        $dghk_jam = $dg_hk * 8;
                        // $dg_hk2 = $dg_hk2 + $d_hk2;
                        /* if($dg_lbh > 0)
                          {
                            $dg_hk2 = $dg_hk2 + $d_hk;
                          }else{
                            $dg_hk2 = $dg_hk2 + $d_hk2;
                          } */
                        $dg_efektif = $dg_efektif + $d_efektif;
                        $dg_efektif_p = ($dg_efektif / $dg_hk) * 100;
                        $dg_tdk_kerja = $dg_tdk_kerja + $d_tdk_kerja;
                        $dg_tdk_kerja_p = ($dg_tdk_kerja / $dg_hk) * 100;
                        $dg_norma = $dg_norma + $d_norma;
                        $dg_nonnorma = $dg_nonnorma + $d_nonnorma;
                        $dg_norma_p = ($dg_norma / $dg_hk) * 100;
                        $dg_nonnorma_p = ($dg_nonnorma / $dg_hk) * 100;
                        $dg_target_tk = (($dg_hk - $dg_nonnorma) / $dg_hk) * 100;
                        $dg_m = $dg_m + $d_m;
                        $dg_p1 = $dg_p1 + $d_p1;
                        $dg_sid = $dg_sid + $d_sid;
                        $dg_kk = $dg_kk + $d_kk;
                        $dg_h1 = $dg_h1 + $d_h1;
                        $dg_h2 = $dg_h2 + $d_h2;
                        $dg_p4 = $dg_p4 + $d_p4;
                        $dg_c = $dg_c + $d_c;
                        $dg_mu = $dg_mu + $d_mu;
                        $dg_gh = $dg_gh + $d_gh;
                        $dg_telat = $dg_telat + $d_telat;
                        $dgdur_telat = number_format(($dgdur_telat + $ddur_telat), 2, '.', '');
                        $dg_pulang = $dg_pulang + $d_pulang;
                        $dgdur_pulang = number_format(($dgdur_pulang + $ddur_pulang), 2, '.', '');
                        $dg_keluar = $dg_keluar + $d_keluar;
                        $dgdur_kel = number_format(($dgdur_kel + $ddur_kel), 2, '.', '');
                        $dg_ringan = $dg_ringan + $d_ringan;
                        $dg_lanjut = $dg_lanjut + $d_lanjut;
                        $dg_berat = $dg_berat + $d_berat;
                        $dgnon_prod = number_format(($dgnon_prod + $dnon_prod), 2, '.', '');
                        /*------------ department calculation end ----------------*/ ?>
                        <?php  /*------------ chitose calculation start ----------------*/
                        $c_hk = $c_hk + $d_hk;
                        $chk_jam = $c_hk * 8;
                        // $c_hk2 = $c_hk2 + $d_hk2;
                        //  if($c_lbh > 0)
                        //   {
                        //     $c_hk2 = $c_hk2 + $d_hk;
                        //   }else{
                        //     $c_hk2 = $c_hk2 + $d_hk2;
                        //   }
                        $c_efektif = $c_efektif + $d_efektif;
                        $c_efektif_p = ($c_efektif / $c_hk) * 100;
                        $c_tdk_kerja = $c_tdk_kerja + $d_tdk_kerja;
                        $c_tdk_kerja_p = ($c_tdk_kerja / $c_hk) * 100;
                        $c_norma = $c_norma + $d_norma;
                        $c_nonnorma = $c_nonnorma + $d_nonnorma;
                        $c_norma_p = ($c_norma / $c_hk) * 100;
                        $c_nonnorma_p = ($c_nonnorma / $c_hk) * 100;
                        $c_target_tk = (($c_hk - $c_nonnorma) / $c_hk) * 100;
                        $c_m = $c_m + $d_m;
                        $c_p1 = $c_p1 + $d_p1;
                        $c_sid = $c_sid + $d_sid;
                        $c_kk = $c_kk + $d_kk;
                        $c_h1 = $c_h1 + $d_h1;
                        $c_h2 = $c_h2 + $d_h2;
                        $c_p4 = $c_p4 + $d_p4;
                        $c_c = $c_c + $d_c;
                        $c_mu = $c_mu + $d_mu;
                        $c_gh = $c_gh + $d_gh;
                        $c_telat = $c_telat + $d_telat;
                        $cdur_telat = number_format(($cdur_telat + $ddur_telat), 2, '.', '');
                        $c_pulang = $c_pulang + $d_pulang;
                        $cdur_pulang = number_format(($cdur_pulang + $ddur_pulang), 2, '.', '');
                        $c_keluar = $c_keluar + $d_keluar;
                        $cdur_kel = number_format(($cdur_kel + $ddur_kel), 2, '.', '');
                        $c_ringan = $c_ringan + $d_ringan;
                        $c_lanjut = $c_lanjut + $d_lanjut;
                        $c_berat = $c_berat + $d_berat;
                        $cnon_prod = number_format(($cnon_prod + $dgnon_prod), 2, '.', '');
                        /*------------ chitose calculation end ----------------*/ ?>
                      <?php } ?>
                      <!--  end foreach dept -->
                      <?php $dgprod =  number_format((($dgnon_prod / ($dg_hk * 8)) * 100), 2, '.', '');
                      $dgprod = number_format((100 - $dgprod), 2, '.', '');
                      $cprod =  number_format((($cnon_prod / ($c_hk * 8)) * 100), 2, '.', '');
                      $cprod = number_format((100 - $cprod), 2, '.', ''); ?>
                      <tr>
                        <td colspan="4" rowspan="2">Total Department Group <?php echo $fdivisi[$dg] ?> </td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td><?php echo $kar_dept_group ?></td>
                        <td><?php echo $dg_hk ?></td>
                        <td <?php echo (round($target_tk, 2) > 100) ? 'style="background-color:#eeef4e"' : 'style="background-color:#9eef94"'; ?>><?php echo round($dg_target_tk, 2) ?> %</td>
                        <td><?php echo $dg_efektif ?></td>
                        <td><?php echo round($dg_efektif_p, 2) ?> %</td>
                        <td><?php echo $dg_tdk_kerja ?></td>
                        <td><?php echo round($dg_tdk_kerja_p, 2) ?> %</td>
                        <td><?php echo $dg_norma ?></td>
                        <td><?php echo round($dg_norma_p, 2) ?> %</td>
                        <td><?php echo $dg_nonnorma ?></td>
                        <td><?php echo round($dg_nonnorma_p, 2) ?> %</td>
                        <!-- <td><?php echo $dgprod ?> %</td> -->
                        <td><?php echo $dg_m ?></td>
                        <td><?php echo $dg_p1 ?></td>
                        <td><?php echo $dg_sid ?></td>
                        <td><?php echo $dg_kk ?></td>
                        <td><?php echo $dg_h1 ?></td>
                        <td><?php echo $dg_h2 ?></td>
                        <td><?php echo $dg_p4 ?></td>
                        <td><?php echo $dg_c ?></td>
                        <td><?php echo $dg_mu ?></td>
                        <td><?php echo $dg_gh ?></td>
                        <td><?php echo $dg_telat ?></td>
                        <td><?php if ($dgdur_telat != 0) {
                              $durasi = $dgdur_telat;
                              $dur = explode(".", $durasi);
                              if ($dur[1] > 60) {
                                $dur[0] = $dur[0] + 1;
                                $dur[1] = $dur[1] - 60;
                              }
                              echo $dur[0] . " jam " . $dur[1] . " menit";
                            }  ?></td>
                        <td><?php echo $dg_pulang ?></td>
                        <td><?php if ($dgdur_pulang != 0) {
                              $durasi = $dgdur_pulang;
                              $dur = explode(".", $durasi);
                              if ($dur[1] > 60) {
                                $dur[0] = $dur[0] + 1;
                                $dur[1] = $dur[1] - 60;
                              }
                              echo $dur[0] . " jam " . $dur[1] . " menit";
                            }  ?></td>
                        <td><?php echo $dg_keluar ?></td>
                        <td><?php if ($dgdur_kel != 0) {
                              $durasi = $dgdur_kel;
                              $dur = explode(".", $durasi);
                              if ($dur[1] > 60) {
                                $dur[0] = $dur[0] + 1;
                                $dur[1] = $dur[1] - 60;
                              }
                              echo $dur[0] . " jam " . $dur[1] . " menit";
                            }  ?></td>
                        <td><?php echo $dg_ringan ?></td>
                        <td><?php echo $dg_lanjut ?></td>
                        <td><?php echo $dg_berat ?></td>
                        <!-- <td><?php echo $dg_hk2 ?></td> -->
                      </tr>
                      <tr>
                        <!-- <td colspan="4">Total Dept Group <?php echo $fdivisi[$dg] ?>g</td> -->
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
                        <td><?php echo round((($dg_m / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_p1 / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_sid / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_kk / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_h1 / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_h2 / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_p4 / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_c / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_mu / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_gh / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_telat / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dgdur_telat / ($dg_hk * 8)) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_pulang / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dgdur_pulang / ($dg_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                        <td><?php echo round((($dg_keluar / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dgdur_kel / ($dg_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                        <td><?php echo round((($dg_ringan / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_lanjut / $dg_hk) * 100), 2) ?> %</td>
                        <td><?php echo round((($dg_berat / $dg_hk) * 100), 2) ?> %</td>
                        <!-- <td></td> -->
                      </tr>
                    <?php } //end loop dept_group      
                    ?>
                    <tr>
                      <td colspan="4" rowspan="2">Total PT. Chitose </td>
                      <td style="display:none"></td>
                      <td style="display:none"></td>
                      <td style="display:none"></td>
                      <td><?php echo $kar_chitose ?></td>
                      <td><?php echo $c_hk ?></td>
                      <td <?php echo (round($target_tk, 2) > 100) ? 'style="background-color:#eeef4e"' : 'style="background-color:#9eef94"'; ?>><?php echo round($c_target_tk, 2) ?> %</td>
                      <td><?php echo $c_efektif ?></td>
                      <td><?php echo round($c_efektif_p, 2) ?> %</td>
                      <td><?php echo $c_tdk_kerja ?></td>
                      <td><?php echo round($c_tdk_kerja_p, 2) ?> %</td>
                      <td><?php echo $c_norma ?></td>
                      <td><?php echo round($c_norma_p, 2) ?> %</td>
                      <td><?php echo $c_nonnorma ?></td>
                      <td><?php echo round($c_nonnorma_p, 2) ?> %</td>
                      <!-- <td><?php echo $cprod ?></td> -->
                      <td><?php echo $c_m ?></td>
                      <td><?php echo $c_p1 ?></td>
                      <td><?php echo $c_sid ?></td>
                      <td><?php echo $c_kk ?></td>
                      <td><?php echo $c_h1 ?></td>
                      <td><?php echo $c_h2 ?></td>
                      <td><?php echo $c_p4 ?></td>
                      <td><?php echo $c_c ?></td>
                      <td><?php echo $c_mu ?></td>
                      <td><?php echo $c_gh ?></td>
                      <td><?php echo $c_telat ?></td>
                      <td><?php if ($cdur_telat != 0) {
                            $durasi = $cdur_telat;
                            $dur = explode(".", $durasi);
                            if ($dur[1] > 60) {
                              $dur[0] = $dur[0] + 1;
                              $dur[1] = $dur[1] - 60;
                            }
                            echo $dur[0] . " jam " . $dur[1] . " menit";
                          }  ?></td>
                      <td><?php echo $c_pulang ?></td>
                      <td><?php if ($cdur_pulang != 0) {
                            $durasi = $cdur_pulang;
                            $dur = explode(".", $durasi);
                            if ($dur[1] > 60) {
                              $dur[0] = $dur[0] + 1;
                              $dur[1] = $dur[1] - 60;
                            }
                            echo $dur[0] . " jam " . $dur[1] . " menit";
                          }  ?></td>
                      <td><?php echo $c_keluar ?></td>
                      <td><?php if ($cdur_kel != 0) {
                            $durasi = $cdur_kel;
                            $dur = explode(".", $durasi);
                            if ($dur[1] > 60) {
                              $dur[0] = $dur[0] + 1;
                              $dur[1] = $dur[1] - 60;
                            }
                            echo $dur[0] . " jam " . $dur[1] . " menit";
                          } ?></td>
                      <td><?php echo $c_ringan ?></td>
                      <td><?php echo $c_lanjut ?></td>
                      <td><?php echo $c_berat ?></td>
                      <!-- <td><?php echo $c_hk2 ?></td> -->
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
                      <td><?php echo round((($c_m / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_p1 / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_sid / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_kk / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_h1 / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_h2 / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_p4 / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_c / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_mu / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_gh / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_telat / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($cdur_telat / ($c_hk * 8)) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_pulang / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($cdur_pulang / ($c_hk * 8)) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_keluar / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($cdur_kel / ($c_hk * 8)) * 100), 2) ?> %</td><!-- durasi_keluar % -->
                      <td><?php echo round((($c_ringan / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_lanjut / $c_hk) * 100), 2) ?> %</td>
                      <td><?php echo round((($c_berat / $c_hk) * 100), 2) ?> %</td>
                      <!-- <td></td> -->
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
        bulan = document.getElementById('bulan').value;
        tahun = document.getElementById('tahun').value;
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
            title: 'Laporan Absensi Bulanan' + bulan + ' ' + tahun,
          }]
        });
      });
    </script>
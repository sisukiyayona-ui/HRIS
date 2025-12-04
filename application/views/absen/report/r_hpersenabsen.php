                <div class="right_col" role="main">
                  <div class="">
                    <div class="page-title">
                      <div class="title_left">
                        <h3> Report</h3>
                      </div>

                      <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        </div>
                      </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>Absensi Karyawan Periode <?php echo $sejak ?> s/d <?php echo $sampai ?> </h2>
                            <?php 
                            $awal = $sejak;
                            $akhir = $sampai;
                            $jenis_norma = $norma;
                            $jenis_nonnorma = $non_norma;
                            ?>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <p>Norma : <?php foreach ($pilih_norma as $pn) {
                                echo "($pn) ";
                            } ?>
                            </p>
                             <p>Non Norma : <?php foreach ($pilih_non as $pnn) {
                                echo "($pnn) ";
                            } ?>
                            </p>
                            <table class="table table-striped table-bordered table-hover" id="r_allabsensi" style="width:100%">
                              <thead>
                                <th><center>NIK</th>
                                  <th><center>Nama</center></th>
                                  <th><center>Bagian</center></th>
                                  <th><center>Jabatan</center></th>
                                  <th>Total</th>
                                  <th>P1 + MA</th>
                                  <th>S1D</th>
                                  <th>CUTI</th>
                                  <th>H1</th>
                                  <th>H2</th>
                                  <th>P1</th>
                                  <th>P3</th>
                                  <th>P4</th>
                                  <th>MANGKIR</th>
                                  <th><center>HK</center></th>
                                   <th><center>Efektif</center></th>
                                  <th><center>%</center></th>
                                  <th><center>Tdk Msk</center></th>
                                  <th><center>%</center></th>
                                  <th><center>Target</center></th>
                                  <th><center>Norma</center></th>
                                  <th><center>%</center></th>
                                  <th><center>Non Norma</center></th>
                                  <th><center>%</center></th>
                                  <th><center>Terlambat</center></th>
                                  <th><center>Pulang</center></th>
                                  <th><center>Keluar</center></th>
                                  <th><center>Ringan</center></th>
                                  <th><center>Berat</center></th>
                                  <th><center>Berkelanjutan</center></th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <?php
                                    $start = $sejak;
                                    $end = $sampai;
                                    
                                    $i = 0;
                    // $emp = $this->m_hris->allabsen_bagian('24');
                                    foreach ($nama as $bag) { 
                                      $query = $this->db->query("SELECT * from karyawan k join bagian b on k.recid_bag = b.recid_bag join jabatan j on k.recid_jbtn = j.recid_jbtn where k.recid_bag ='$bag->recid_bag' and k.sts_aktif='Aktif' and spm = 'Tidak'  order by b.nama_bag asc")->result();
                                      $tothk = 0;  
                                      $totnon = 0;
                                      $totnorma = 0;
                                      $totlmbt = 0;
                                      $totplg = 0;
                                      $totklr = 0;
                                      $totr = 0;
                                      $totb = 0;
                                      $totl = 0;
                                      foreach ($query as $karyawan) {
                                       $hk = 0;
                                       $start = $sejak;
                                       $end = $sampai;
                                       while($start <= $end)
                                       {
                                        if($karyawan->shift == 'Shift')
                                        {
                                          $hk2 = $this->db->query("SELECT count(nik) as hk from master_absen.shift where nik = '$karyawan->nik' and TGL_SHIFT BETWEEN '$sejak' and '$sampai' and SHIFT_CODE != 0");
                                          foreach ($hk2->result() as $da) {
                                            $hk = $da->hk;
                                          }

                                          if($hk == 0)
                                          {
                                            $hk = 0;
                                            $awal = $sejak;
                                            $akhir = $sampai;
                                            while($awal <= $akhir){
                                // cek weekend
                                              $datetime = new datetime($awal);
                                              $dt = $datetime->format('D');
                                              if($dt == "Sun" or $dt == "Sat" ){
                              // cek ganti hari perusahaan
                                                $gh = $this->m_absen->gh_by_date($awal);
                                                if($gh->num_rows() > 0)
                                                {
                                                  $hk = $hk + 1;
                                                }else
                                                {
                                                  $hk = $hk;
                                                }
                                              }else{
                              // cek hari libur
                                                $libur = $this->m_absen->libur_by_date($awal);
                                                if($libur->num_rows() > 0)
                                                {
                                                  $hk = $hk;
                                                }else
                                                {
                                                  $hk = $hk + 1;
                                                }
                                              }
                                              $awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));
                              }// while 2
                            }
                          }else{
                            // cek weekend
                            $datetime = new datetime($start);
                            $dt = $datetime->format('D');
                            if($dt == "Sun" or $dt == "Sat" ){
                            // cek ganti hari perusahaan
                              $gh = $this->m_absen->gh_by_date($start);
                              if($gh->num_rows() > 0)
                              {
                                $hk = $hk + 1;
                              }else
                              {
                                $hk = $hk;
                              }
                            }else{
                            // cek hari libur
                              $libur = $this->m_absen->libur_by_date($start);
                              if($libur->num_rows() > 0)
                              {
                                $hk = $hk;
                              }else
                              {
                                $hk = $hk + 1;
                              }
                            }
                          }
                          
                          $start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
                        } // for while
                        $tothk = $tothk + $hk;
                        /*---- CALCULATE DETAIL ABSEN ------*/
                        $jmangkir = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and k.nik = '$karyawan->nik'");
                        foreach ($jmangkir->result() as $jmangkir) { }

                          /*---- CALCULATE NON - NORMA (M+P1+S1D+H1+H2) ------*/
                        if($jenis_nonnorma == "")
                        {
                          $non = 0;
                        }else{
                           $nonorma = $this->db->query("SELECT count(nik) as non from master_absen.mangkir where nik = '$karyawan->nik' and TANGGAL BETWEEN '$sejak' and '$sampai' $jenis_nonnorma");
                        foreach ($nonorma->result() as $non) {
                         $non = $non->non;
                          }  
                        }
                       
                       $nonper = ($non / $hk) * 100;


                       /*---- CALCULATE NORMA ( KECUALI M+P1) ------*/
                       $norma = $this->db->query("SELECT count(nik) as norma from master_absen.mangkir where nik = '$karyawan->nik' and TANGGAL BETWEEN '$sejak' and '$sampai' $jenis_norma ");
                       foreach ($norma->result() as $norma) {
                         $norma = $norma->norma;
                       }

                       $normaper = ($norma / $hk) * 100;

                       $efektif = $hk - ($norma + $non);
                       $efektifper = ($efektif / $hk) * 100;
                       $absen = $hk - $efektif;
                       $absenper = ($absen / $hk) * 100;
                       $target = (($hk  - $non) / $hk) * 100;
                        // echo $karyawan->nik." - ".$karyawan->nama_karyawan." - ".$hk."<br>";
                       $totnon = $totnon + $non;
                       $totnorma = $totnorma + $norma;
                       $totefektif = $tothk - ($totnon + $totnorma);
                       $tottdk = $tothk - $totefektif;

                       ?>
                       <td><?php echo $karyawan->nik; ?></td>
                       <td><?php echo $karyawan->nama_karyawan; ?></td>
                       <td><center><?php echo $karyawan->nama_bag; ?></center></td>
                       <td><center><?php echo $karyawan->nama_jbtn; ?></center></td>
                       <td><center><?php echo $jmangkir->total ?></center></td>
                       <?php $tot = $jmangkir->P1 + $jmangkir->MANGKIR; 
                       if( $tot >= 3){
                        echo"<td style='background-color:yellow; color:#000;'><center><b>$tot</b></center></td>";
                      }else{
                        echo"<td><center>$tot</center></td>";
                        } echo"</center></td>
                        <td><center>$jmangkir->SID</center></td>
                        <td><center>$jmangkir->CUTI</center></td>
                        <td><center>$jmangkir->H1</center></td>
                        <td><center>$jmangkir->H2</center></td>";
                        if($jmangkir->P1 >= 3){
                          echo"<td style='background-color:yellow; color:#000;'><center><b>$jmangkir->P1</b></center></td>";
                        }else{
                          echo"<td><center>$jmangkir->P1</center></td>";
                        }
                        echo"
                        <td><center>$jmangkir->P3</center></td>
                        <td><center>$jmangkir->P4</center></td>";
                        if($jmangkir->MANGKIR >= 3){
                          echo"<td style='background-color:yellow; color:#000;'><center><b>$jmangkir->MANGKIR</b></center></td>";
                        }else{
                          echo"<td><center>$jmangkir->MANGKIR</center></td>";
                        }?>
                        <td><center><?php echo $hk ?></center></td>
                        <td><center><?php echo $efektif ?></center></td>
                        <td><center><?php echo round($efektifper)."%"; ?></center></td>
                        <td><center><?php echo $absen ; ?></center></td>
                        <td><center><?php echo round($absenper)."%"; ?></center></td>
                        <td><center><?php echo round($target)."%"; ?></center></td>
                        <td><center><?php echo $norma ?></center></td>
                        <td><center><?php echo round($normaper)."%"; ?></center></td>
                        <td><center><?php echo $non ?></center></td>
                        <td><center><?php echo round($nonper)."%"; ?></center></td>
                        <?php 
                           $totalabsn = $this->db->query("SELECT COUNT(IF(jenis = 'Terlambat', 1, NULL)) AS terlambat, COUNT(IF(jenis = 'Pulang', 1, NULL)) AS pulang, COUNT(IF(jenis = 'Keluar', 1, NULL)) AS keluar FROM master_absen.izin i join hris.karyawan k on k.nik = i.nik join hris.bagian b on b.recid_bag = k.recid_bag where tgl_izin  BETWEEN '$sejak' and '$sampai' and k.nik = '$karyawan->nik'");
                        foreach ($totalabsn->result() as $totalabsn) { }
                        $totlmbt = $totlmbt + $totalabsn->terlambat;
                        $totplg = $totplg + $totalabsn->pulang;
                        $totklr = $totklr + $totalabsn->keluar;
                        ?>
                        <td><center><?php echo $totalabsn->terlambat; ?></center></td>
                        <td><center><?php echo $totalabsn->pulang;?></center></td>
                        <td><center><?php echo $totalabsn->keluar;?></center></td>
                        <?php 
                           $totaldiag = $this->db->query("SELECT COUNT(IF(KATEGORI = 'Ringan', 1, NULL)) AS ringan, COUNT(IF(KATEGORI = 'Berat', 1, NULL)) AS berat, COUNT(IF(KATEGORI = 'Berkelanjutan', 1, NULL)) AS lanjut FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and k.nik = '$karyawan->nik'");
                        foreach ($totaldiag->result() as $totaldiag) { }
                        $totr = $totr + $totaldiag->ringan;
                        $totb = $totb + $totaldiag->berat;
                        $totl = $totl + $totaldiag->lanjut;
                        ?>
                        <td><center><?php echo $totaldiag->ringan; ?></center></td>
                        <td><center><?php echo $totaldiag->berat;?></center></td>
                        <td><center><?php echo $totaldiag->lanjut;?></center></td>
                      </tr>
                      <?php 
                      $i++;   
                    } ?> <!-- foreach karyawan -->
                    <!-- KALKULASI PER BAGIAN -->
                    <tr><td></td><td></td>
                      <td><center><b><?php echo $bag->nama_bag ?></b></center></td>
                      <td><center><b>
                        <?php $jmlkar = $this->db->query("SELECT count(nik) as jumlah from karyawan k where recid_bag = '$bag->recid_bag' and k.sts_aktif='Aktif' and spm = 'Tidak' ");
                        foreach ($jmlkar->result() as $jumkar) {
                          $jumkar = $jumkar->jumlah;
                        }
                        echo $jumkar;
                        ?>
                      </b></center></td>
                      <td><center><b>
                        <?php  $jmangkirbag = $this->db->query("SELECT k.nik, k.nama_karyawan, b.nama_bag, COUNT(*) AS total, COUNT(IF(CODE = 'S1D', 1, NULL)) AS SID, COUNT(IF(CODE = 'T', 1, NULL)) AS CUTI, COUNT(IF(CODE = 'H1', 1, NULL)) AS H1, COUNT(IF(CODE = 'H2', 1, NULL)) AS H2, COUNT(IF(CODE = 'P1', 1, NULL)) AS P1, COUNT(IF(CODE = 'P3', 1, NULL)) AS P3, COUNT(IF(CODE = 'P4', 1, NULL)) AS P4, COUNT(IF(CODE = 'MA', 1, NULL)) AS MANGKIR FROM master_absen.mangkir m join hris.karyawan k on k.nik = m.nik join hris.bagian b on b.recid_bag = k.recid_bag where TANGGAL  BETWEEN '$sejak' and '$sampai' and k.recid_bag = '$bag->recid_bag'");
                        foreach ($jmangkirbag->result() as $jmangkirbag) { } 
                          echo $jmangkirbag->total;
                        ?>
                      </b></center></td>
                      <td><center><b><?php echo $jmangkirbag->MANGKIR + $jmangkirbag->P1 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->SID ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->CUTI ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->H1 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->H2 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->P1 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->P3 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->P4 ?></b></center></td>
                      <td><center><b><?php echo $jmangkirbag->MANGKIR ?></b></center></td>
                      <td><center><b><?php echo $tothk?> </b></center></td>
                      <?php

                      $totnonpers = ($totnon / ($tothk)) * 100;
                      $totnormapers = ($totnorma /  $tothk) * 100;
                      $totefektifpers = ($totefektif /  $tothk) * 100;
                      $tottdkpers = ($tothk - $totefektif) / 100 ;
                      $target2 = (($tothk - $totnon) /  $tothk) * 100;

                      ?>
                      <td><center><b><?php echo $totefektif ?></b></center></td>
                      <td><center><b><?php echo round($totefektifpers) ?>%</b></center></td>
                      <td><center><b><?php echo $tottdk ?></b></center></td>
                      <td><center><b><?php echo ($tottdkpers) ?>%</b></center></td>
                      <td><center><b><?php echo round($target2) ?>%</b></center></td>
                       <td><center><b><?php echo $totnorma ?></b></center></td>
                      <td><center><b><?php echo round($totnormapers) ?>%</b></center></td>
                      <td><center><b><?php echo $totnon ?></b></center></td>
                      <td><center><b><?php echo round($totnonpers) ?>%</b></center></td>
                      <td><center><b><?php echo $totlmbt ?></b></center></td>
                      <td><center><b><?php echo $totplg ?></b></center></td>
                      <td><center><b><?php echo $totklr ?></b></center></td>
                       <td><center><b><?php echo $totr ?></b></center></td>
                      <td><center><b><?php echo $totb ?></b></center></td>
                      <td><center><b><?php echo $totl ?></b></center></td>
                    </tr>
                    <?php 
                    }// for foreach bagian
                    ?>
                    
                    
                  </tbody>
                </table> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

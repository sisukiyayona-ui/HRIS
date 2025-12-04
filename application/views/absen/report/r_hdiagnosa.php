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
                    ?>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table class="table table-striped table-bordered table-hover" id="r_allabsensi" style="width:100%">
              <thead>
                <tr><th>NIK</th>
                <th>Nama</th>
                <th>Bagian</th>
                <?php 
                $awal = $sejak;
                $akhir = $sampai;
                while ($awal <= $akhir){?>
                  <th><?php echo $awal; ?></th>
                  <?php $awal = date('Y-m-d', strtotime('+1 days', strtotime($awal)));}
                  ?>
                <th>Total SID</th>
                 <th>Ringan</th>
                <th>Berat</th>
                <th>Berkelanjutan</th> 
                </tr>
              </thead>

              <!-- BODY TABLE-->
              <tbody>
                <?php
                $mulai = $sejak;
                $henti = $sampai;
 
                foreach ($nama as $emp)
                  {?>
                    <tr>
                      <td><?php echo $emp->nik; ?></td>
                      <td><?php echo $emp->nama_karyawan; ?></td>
                      <td><?php echo $emp->nama_bag;  ?></td>
                      <?php 
                      $start = $sejak;
                      $end = $sampai;
                      while($start <= $end)
                      {
                        $cek_absen = $this->db->query("SELECT * from master_absen.mangkir where NIK = '$emp->nik' and TANGGAL = '$start'");
                        if($cek_absen->num_rows() < 1)
                        {
                          echo "<td><center>-</center></td>";
                        }else
                        {
                          foreach ($cek_absen->result() as $diag) {
                            $diagnosa = $diag->DIAGNOSA;
                            $kat = $diag->KATEGORI;
                            if($kat == 'Ringan')
                            {
                              $kat = 'R';
                            }else if($kat == 'Berat')
                            {
                              $kat = 'B';
                            }else
                            {
                              $kat = 'L';
                            }
                          }
                          if($diagnosa == '')
                          {
                            echo "<td><center>$diag->CODE</center></td>";
                          }else{
                           echo "<td><center>$diagnosa<br>($kat)</center></td>";
                         }
                       }
                       $start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
                      }// end while

                      $total = $this->db->query("SELECT count(NIK) as total from master_absen.mangkir where NIK = '$emp->nik' and TANGGAL between '$sejak' and '$sampai' and CODE = 'S1D' ");
                      foreach ($total->result() as $total) { }
                      echo "<td><center>$total->total</center></td>";

                    $ringan = $this->db->query("SELECT count(NIK) as ringan from master_absen.mangkir where NIK = '$emp->nik' and (TANGGAL between '$sejak' and '$sampai') and KATEGORI = 'Ringan' ");
                      foreach ($ringan->result() as $ringan) { }
                      echo "<td><center>$ringan->ringan</center></td>";

                     $berat = $this->db->query("SELECT count(NIK) as berat from master_absen.mangkir where NIK = '$emp->nik' and (TANGGAL between '$sejak' and '$sampai') and KATEGORI = 'Berat' ");
                      foreach ($berat->result() as $berat) { }
                      echo "<td><center>$berat->berat</center></td>";

                     $lanjut = $this->db->query("SELECT count(NIK) as lanjut from master_absen.mangkir where NIK = '$emp->nik' and (TANGGAL between '$sejak' and '$sampai') and KATEGORI = 'Berkelanjutan' ");
                      foreach ($lanjut->result() as $lanjut) { }
                      echo "<td><center>$lanjut->lanjut</center></td>";

                    echo "</tr>";
                  } // end foreach
                ?>
               </table> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

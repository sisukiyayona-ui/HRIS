
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
                <tr>
                  <th>NIK</th>
                  <th>Nama Karyawan</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <?php 

                  while($sejak <= $sampai){
                   $datetime = new datetime($sejak);
                   $dt = $datetime->format('D');
                   $tg = $datetime->format('d');
                   if($dt == "Sun" or $dt == "Sat" ){
                    echo "<td><center><font color='red'>$dt<br>$tg </font></center></td>";  
                  }else{
                    $query = $this->db->query("SELECT * from master_absen.libur where tgl = '$sejak'");
                    if($query->num_rows() > 0){
                             // $libur = 1; 
                      // foreach ($query->result() as $lib) {
                      //   $display = $lib->display;
                      // }
                      echo "<td><center><font color='red'>$dt<br>$tg </font></center></td>";     
                    }
                    else{
                              //normal day
                      echo "<td><center>$dt<br>$tg</center></td>";
                    }

                  }
                  ?>
                  <?php $sejak = date('Y-m-d', strtotime('+1 days', strtotime($sejak)));?>
                         <?php } //punya while
                         ?>
                         <th>Hadir</th>
                         <th>Telat</th>
                         <th>C</th>
                         <th>SID</th>
                         <th>H1</th>
                         <th>H2</th>
                         <th>P1</th>
                         <th>P3</th>
                         <th>P4</th>
                         <th>M</th>
                       </tr>
                     </thead>

                     <tbody>
                      <?php

                      foreach ($nama as $nama) {?>
                        <tr>
                          <td><?php echo $nama->nik ?></td>
                          <td><?php echo $nama->nama_karyawan ?></td>
                          <td><?php echo $nama->nama_bag ?></td>
                          <td><?php echo $nama->nama_jbtn ?></td>
                          <?php 
                          $a=$awal;
                          $b=$akhir;
                          $terlambat = 0;
                          while ($a<=$b)
                          {

                            $work = "";
                            $cek_absen = $this->db->query("SELECT m.*, k.recid_bag From master_absen.absen m join hris.karyawan k on m.nik = k.nik where m.nik = '$nama->nik' and m.date_work = '$a' and k.sts_aktif = 'Aktif'");
                            if($cek_absen->num_rows() == 0)
                            {
                            //tidak absen
                              $cek_mangkir = $this->db->query("SELECT m.* From master_absen.mangkir m join hris.karyawan k on m.nik = k.nik where m.nik = '$nama->nik' and m.tanggal = '$a' and k.sts_aktif = 'Aktif'");
                              if($cek_mangkir->num_rows() == 0)
                              {
                              //tidak ada keterangan
                                $work = " - ";
                              }else{
                                foreach($cek_mangkir->result() as $mangkir)
                                {
                                // tidak hadir ada keterangan
                                  $work = $mangkir->CODE;
                                }
                              }
                            }else{
                            //absen
                              foreach($cek_absen->result() as $absen)
                              {
                              //cek shift
                                $lambat = $this->db->query("SELECT * FROM master_absen.shift where nik = '$nama->nik' and TGL_SHIFT = '$a'");
                                if($lambat->num_rows() > 0)
                                {
                                // shift
                                  $bag = $absen->recid_bag;
                                // keamanam
                                  if($bag == '24'){
                                    foreach ($lambat->result() as $lmb) {
                                      $shift = $lmb->SHIFT_CODE;
                                    }
                                    if($shift == '1'){
                                      if($absen->TIME_IN >= '07:01'){
                                        $terlambat = $terlambat + 1;
                                      }else{
                                        $terlambat = $terlambat;
                                      }
                                    }else if($shift == '2'){
                                     if($absen->TIME_IN >= '15:01'){
                                      $terlambat = $terlambat + 1;
                                    }else{
                                      $terlambat = $terlambat;
                                    }
                                  }else{
                                    if($absen->TIME_IN >= '23:05'){
                                      $terlambat = $terlambat + 1;
                                    }else{
                                      $terlambat = $terlambat;
                                    }
                                  }
                                  } // end keamanan
                                  else if($bag == '3' or $bag == '62'){ //chrome
                                    foreach ($lambat->result() as $lmb) {
                                      $shift = $lmb->SHIFT_CODE;
                                    }
                                    if($shift == '1'){
                                      if($absen->TIME_IN >= '06:01'){
                                        $terlambat = $terlambat + 1;
                                      }else{
                                        $terlambat = $terlambat;
                                      }
                                    }else if($shift == '2'){
                                     if($absen->TIME_IN >= '14:01'){
                                      $terlambat = $terlambat + 1;
                                    }else{
                                      $terlambat = $terlambat;
                                    }
                                  }else{
                                    if($absen->TIME_IN >= '22:01'){
                                      $terlambat = $terlambat + 1;
                                    }else{
                                      $terlambat = $terlambat;
                                    }
                                  }
                                }
                                  else{ // normal
                                   if($absen->TIME_IN >= '07:31'){
                                    $terlambat = $terlambat + 1;
                                  }else{
                                    $terlambat = $terlambat;
                                  }
                                }  
                                $work = $absen->DATE_WORK;
                                $in = $absen->TIME_IN;
                                $out = $absen->TIME_OUT;
                                $ins = substr($in,0,5);
                                $outs = substr($out,0,5);
                                if($ins != null and $outs != null)
                                {
                                  $work = "K";
                                }else{
                                  $work = $in." - ".$out;
                                }
                              }else{
                                if($absen->TIME_IN >= '07:31'){
                                  $terlambat = $terlambat + 1;
                                }else{
                                  $terlambat = $terlambat;
                                }
                                $work = $absen->DATE_WORK;
                                $in = $absen->TIME_IN;
                                $out = $absen->TIME_OUT;
                                $ins = substr($in,0,5);
                                $outs = substr($out,0,5);
                                if($ins != null and $outs != null)
                                {
                                  $work =  "K";
                                }else{
                                  $work = $in." - ".$out;
                                }
                              }
                              


                              // if($absen->TIME_IN >= '07:31'){
                              //   $terlambat = $terlambat + 1;
                              // }else{
                              //   $terlambat = $terlambat;
                              // }
                            }
                          }
                          echo"<td><center>$work</center></td>";
                          $a = date('Y-m-d', strtotime('+1 days', strtotime($a)));
                        }
                        // $hadir =  $this->db->query("SELECT count(nik) as kerja from master_absen.absen where nik = '$nama->nik' and time_in != '' and time_out !='' and DATE_WORK between '$awal' and '$akhir'")->result();
                        $hadir =  $this->db->query("SELECT count(nik) as kerja from master_absen.absen where nik = '$nama->nik'  and DATE_WORK between '$awal' and '$akhir'")->result();
                        foreach ($hadir as $hadir) {
                         $kerja = $hadir->kerja;
                       }
                       echo "<td><center>$kerja</center></td>";
                       echo "<td><center>$terlambat</center></td>";
                       $cuti =  $this->db->query("SELECT count(nik) as cuti from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'T')")->result();
                       foreach ($cuti as $cuti) {
                         $cuti = $cuti->cuti;
                       }
                       echo "<td><center>$cuti</center></td>";
                       $sid =  $this->db->query("SELECT count(nik) as sid from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'S1D')")->result();
                       foreach ($sid as $sid) {
                         $sid = $sid->sid;
                       }
                       echo "<td><center>$sid</center></td>";
                       $h1 =  $this->db->query("SELECT count(nik) as h1 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'H1')")->result();
                       foreach ($h1 as $h1) {
                         $h1 = $h1->h1;
                       }
                       echo "<td><center>$h1</center></td>";
                       $h2 =  $this->db->query("SELECT count(nik) as h2 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'H2')")->result();
                       foreach ($h2 as $h2) {
                         $h2 = $h2->h2;
                       }
                       echo "<td><center>$h2</center></td>";
                       $p1 =  $this->db->query("SELECT count(nik) as p1 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'P1')")->result();
                       foreach ($p1 as $p1) {
                         $p1 = $p1->p1;
                       }
                       echo "<td><center>$p1</center></td>";
                       $p3 =  $this->db->query("SELECT count(nik) as p3 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'P3')")->result();
                       foreach ($p3 as $p3) {
                         $p3 = $p3->p3;
                       }
                       echo "<td><center>$p3</center></td>";
                       $p4 =  $this->db->query("SELECT count(nik) as p4 from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'P4')")->result();
                       foreach ($p4 as $p4) {
                         $p4 = $p4->p4;
                       }
                       echo "<td><center>$p4</center></td>";
                       $mangkir =  $this->db->query("SELECT count(nik) as mangkir from master_absen.mangkir where nik = '$nama->nik' and tanggal between '$awal' and '$akhir' and (code = 'MA')")->result();
                       foreach ($mangkir as $mangkir) {
                         $mangkir = $mangkir->mangkir;
                       }
                       echo "<td><center>$mangkir</center></td>";
                       ?>
                     </tr>
                   <?php } // punya foreach nama?>
                 </tbody>
               </table> 
              <!-- <br>rendering : <?php echo $waktu; ?> seconds. -->
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
    
    
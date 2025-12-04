<!-- page content -->
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
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Human Resource</h2>
            <ul class="nav navbar-right panel_toolbox">
            </ul>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <table id="tr_hr" class="table table-striped table-bordered" width="100%">
              <thead>
                <tr>
                  <th rowspan="2"><center>Bagian/Department</center></th>
                  <th colspan="13"><center>Golongan / Jabatan</center></th>
                  <th colspan="4"><center>Status Karyawan</center></th>
                  <th colspan="3"><center>Gender</center></th>
                  <th colspan="7"><center>Pendidikan</center></th>
                  <th colspan="4"><center>Usia</center></th>
                  <th colspan="4"><center>Masa Kerja</center></th>
                </tr>
                <tr>
                  <th>BOD</th>
                  <th>GM</th>
                  <th>MGR</th>
                  <th>ASMGR</th>
                  <th>CO</th>
                  <th>OFFICER</th>
                  <th>KASIE</th>
                  <th>WAKASIE</th>
                  <th>KARU</th>
                  <th>WAKARU</th>
                  <th>OP BLN</th>
                  <th>OP HRN</th>
                  <th>&Sigma;</th>
                  <th>TETAP</th>
                  <th>KONTRAK</th>
                  <th>HONORER</th>
                  <th>&Sigma;</th>
                  <th>W</th>
                  <th>P</th>
                  <th>&Sigma;</th>
                  <th>SD</th>
                  <th>SMP</th>
                  <th>SMA</th>
                  <th>D3</th>
                  <th>S1</th>
                  <th>S2</th>
                  <th>&Sigma;</th>
                  <th>&lt;=35</th>
                  <th>36-44</th>
                  <th>&gt;=45</th>
                  <th>&Sigma;</th>
                  <th>&lt;3</th>
                  <th>3-10</th>
                  <th>&gt;10</th>
                  <th>&Sigma;</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // $pres1 = 0;
                $bod1 = 0;
                $gm1 = 0;
                $mgr1 = 0;
                $asmgr1 = 0;
                $co1 = 0;
                $o1 = 0;
                $kasi1 = 0;
                $wakasi1 = 0;
                $karu1 = 0;
                $wakaru1 = 0;
                $opb1 = 0;
                $oph1 = 0;
                $jml1a = 0;
                $tetap1 = 0;
                $kontrak1 = 0;
                $tetap_bod1 = 0;
                $kontrak_bod1 = 0;
                $honor1 = 0;
                $honor_bod1 = 0;
                $jml2a = 0;
                $L1 = 0;
                $P1 = 0;
                $jml3a = 0;
                $L1a = 0;
                $P1a = 0;
                $sd1 = 0;
                $smp1 = 0;
                $slta1 = 0;
                $d31 = 0;
                $s11 = 0;
                $s21 = 0;
                $sd1a = 0;
                $smp1a = 0;
                $slta1a = 0;
                $d31a = 0;
                $s11a = 0;
                $s21a = 0;
                $jml4a = 0;
                $u35a = 0;
                $u44a = 0;
                $u45a = 0;
                $jml5a = 0;
                $u35ab = 0;
                $u44ab = 0;
                $u45ab = 0;
                $jml5ab = 0;
                $u35abc = 0;
                $u44abc = 0;
                $u45abc = 0;
                $jml5abc = 0;
                $u3a = 0;
                $u10a = 0;
                $u11a = 0;
                $jml6a = 0;
                $u3as = 0;
                $u10as = 0;
                $u11as = 0;
                $jml6as = 0;
                $u3asc = 0;
                $u10asc = 0;
                $u11asc = 0;
                $jml6asc = 0;
                foreach ($department as $dept) { 
                  if($dept->department == 'PRESDIR'){ 
              //jml BOD
                    $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and sts_aktif = 'Aktif'")->result();
                    foreach ($pres as $pres) {
                      $pres = $pres->pres;
                      $bod1 = $bod1 + $pres;
                      if($pres == '0'){
                        $pres = '';
                      }else{
                        $pres = $pres;
                      }
                    }?>
                    <tr style="background-color: #CCC"><td><b><?php echo strtoupper($dept->department) ?></b></td>
                      <td><center><?php echo $pres; ?></td>
                      <?php
                      for($i=0;$i<11;$i++){ ?>
                        <td><center>0</center></td>
                      <?php } ?>
                      <td><center><b><?php echo $pres ?></b></center></td>
                      <?php
                      $tetap_pres = $this->db->query("SELECT count(recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and sts_jbtn = 'Tetap' and sts_aktif = 'Aktif'")->result();
                      foreach ($tetap_pres as $tetap_pres) { $tetap_pres = $tetap_pres->tetap;}

                      $kontrak_pres = $this->db->query("SELECT count(recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where  j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir')and sts_jbtn = 'Kontrak' and sts_aktif = 'Aktif'")->result();
                      foreach ($kontrak_pres as $kontrak_pres) {$kontrak_pres = $kontrak_pres->kontrak;}

                      $honor_pres = $this->db->query("SELECT count(recid_karyawan) as honor FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and sts_jbtn = 'Honorer' and sts_aktif = 'Aktif'")->result();
                      foreach ($honor_pres as $honor_pres) {$honor_pres = $honor_pres->honor;}
                      ?>
                      <td><center><?php  echo $tetap_pres ?></td>
                        <td><center><?php  echo $kontrak_pres ?></td>
                          <td><center><?php  echo $honor_pres?></td>
                           <td><center><b><?php echo $pres ?></b></center></td>
                       <?php
                       $pria_pres = $this->db->query("SELECT count(recid_karyawan) as pria_pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and jenkel = 'Pria' and sts_aktif = 'Aktif'")->result();
                       foreach ($pria_pres as $pria_pres) {$pria_pres = $pria_pres->pria_pres;}
                       $wanita_pres = $this->db->query("SELECT count(recid_karyawan) as wanita_pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and jenkel = 'Wanita' and sts_aktif = 'Aktif'")->result();
                       foreach ($wanita_pres as $wanita_pres) {$wanita_pres = $wanita_pres->wanita_pres;}
                       ?>
                       <td><center><?php  echo $wanita_pres ?></center></td>
                       <td><center><?php  echo $pria_pres ?></center></td>
                       <td><center><b><?php echo $pres ?></b></center></td>
                       <?php
                       for($i4=0;$i4<3;$i4++){ ?>
                        <td><center>0</center></td>
                      <?php } ?>
                      <?php 
                      $d3_pres = $this->db->query("SELECT count(recid_karyawan) as d3_pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and pendidikan = 'D3' and sts_aktif = 'Aktif'")->result();
                      foreach ($d3_pres as $d3_pres) {$d3_pres = $d3_pres->d3_pres;}
                      $s1_pres = $this->db->query("SELECT count(recid_karyawan) as s1_pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and pendidikan = 'S1' and sts_aktif = 'Aktif'")->result();
                      foreach ($s1_pres as $s1_pres) {$s1_pres = $s1_pres->s1_pres;}
                      $s2_pres = $this->db->query("SELECT count(recid_karyawan) as s2_pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and pendidikan = 'S2' and sts_aktif = 'Aktif'")->result();
                      foreach ($s2_pres as $s2_pres) {$s2_pres = $s2_pres->s2_pres;}
                      ?>
                      <td><center><?php  echo $d3_pres ?></center></td>
                      <td><center><?php  echo $s1_pres ?></center></td>
                      <td><center><?php  echo $s2_pres ?></center></td>
                      <td><center><b><?php echo $pres ?></b></center></td>
                      <?php
                      $umurs_pres = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select nik, nama_karyawan, tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department') as dummy_table")->result();
                      foreach ($umurs_pres as $umurs_pres) {
                       $u35bc = $umurs_pres->kurang35;
                       $u44bc = $umurs_pres->u3644;
                       $u45bc = $umurs_pres->lebih45;
                       $jml5bc = $u35bc + $u44bc + $u45bc;
                       $u35abc = $u35abc + $u35bc;
                       $u44abc = $u44abc + $u44bc;
                       $u45abc = $u45abc + $u45bc;
                       $jml5abc = $jml5abc + $jml5bc;
                     }
                    //range masker
                     $maskers_pres = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department') as dummy_table")->result();
                     foreach ($maskers_pres as $usias_pres) {
                      $u3sc = $usias_pres->kurang3;
                      $u10sc = $usias_pres->u10;
                      $u11sc = $usias_pres->lebih10;
                      $jml6sc = $u3sc + $u10sc + $u11sc;
                      $u3asc = $u3asc + $u3sc;
                      $u10asc = $u10asc + $u10sc;
                      $u11asc = $u11asc + $u11sc;
                      $jml6asc = $jml6asc + $jml6sc;
                    }
                    ?>
                    <td><center><?php  echo $u35bc; ?></td>
                    <td><center><?php  echo $u44bc ?></td>
                    <td><center><?php  echo $u45bc ?></td>
                    <td><center><b><?php echo $pres ?></b></center></td>
                    <td><center><?php echo $u3sc ?></center></td>
                    <td><center><?php echo $u10sc ?></center></td>
                    <td><center><?php echo $u11sc ?></center></td>
                    <td><center><b><?php echo $pres ?></b></center></td>
                    </tr>
                  <?php }else{ ?>
                    <!-- ******************************* NOT PRESDIR (DEPARTEMENT) ***************************************************** -->
                    <tr style="background-color: #CCC">
                      <td><b><?php echo strtoupper($dept->department) ?></b></td>
                      <?php 
                         //jml BOD
                      $bod = $this->db->query("SELECT count(recid_karyawan) as bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '11' and b.department = '$dept->department' and (s.nama_struktur = 'BOD' or s.nama_struktur = 'Presdir') and sts_aktif = 'Aktif'")->result();
                      foreach ($bod as $bod) {
                       $bod = $bod->bod;
                       $bod1 = $bod1 + $bod;
                      //  if($bod == '0'){
                      //   $bod = '';
                      // }else{
                      //   $bod = $bod;
                      // }
                    }

                    //jml GM
                    $gm = $this->db->query("SELECT count(recid_karyawan) as gm FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '10' and b.department = '$dept->department' and s.nama_struktur = 'GM' and sts_aktif = 'Aktif'")->result();
                    foreach ($gm as $gm) {
                     $gm = $gm->gm;
                     $gm1 = $gm1 + $gm;
                    //  if($gm == '0'){
                    //   $gm = '';
                    // }else{
                    //   $gm = $gm;
                    // }
                  }

                  ?>
                  <td><center><?php echo $bod; ?></center></td>
                  <td><center><?php echo $gm; ?></center></td>
                  <?php
                  for($i=0;$i<10;$i++){ ?>
                    <td><center>0</center></td>
                  <?php } ?>
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                  <!-- bod / gm tetap -->
                  <?php
                     $tetap_bod = $this->db->query("SELECT count(recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and sts_jbtn = 'Tetap' and sts_aktif = 'Aktif'")->result();
                     foreach (
                      $tetap_bod as $tetap_top) { $tetap_bod = $tetap_top->tetap;
                      $tetap_bod1 = $tetap_bod1 + $tetap_bod;
                     }

                      $kontrak_bod = $this->db->query("SELECT count(recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and sts_jbtn = 'Kontrak' and sts_aktif = 'Aktif'")->result();
                     foreach ($kontrak_bod as $kontrak_top) {$kontrak_bod = $kontrak_top->kontrak;
                      $kontrak_bod1 = $kontrak_bod1 + $kontrak_bod;}

                       $honor_bod = $this->db->query("SELECT count(recid_karyawan) as honor FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and sts_jbtn = 'Honorer' and sts_aktif = 'Aktif'")->result();
                     foreach ($honor_bod as $honor_top) {$honor_bod = $honor_top->honor;
                      $honor_bod1 = $honor_bod1 + $honor_bod;}
                  ?>
                    <td><center><?php  echo $tetap_bod ?></td>
                    <td><center><?php  echo $kontrak_bod ?></td>
                    <td><center><?php  echo $honor_bod?></td>
                 <!--  <td><center><?php  echo $tetap_top->tetap ?></center></td>
                  <td></td> -->
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                  <?php
                    $pria_bod = $this->db->query("SELECT count(recid_karyawan) as pria_bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and jenkel = 'Pria' and sts_aktif = 'Aktif'")->result();
                     foreach ($pria_bod as $pria_bod) {$pria_bod = $pria_bod->pria_bod;
                      $L1a = $L1a + $pria_bod;}
                      $wanita_bod = $this->db->query("SELECT count(recid_karyawan) as wanita_bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and jenkel = 'Wanita' and sts_aktif = 'Aktif'")->result();
                     foreach ($wanita_bod as $wanita_bod) {$wanita_bod = $wanita_bod->wanita_bod;
                      $P1a = $P1a + $wanita_bod;}
                  ?>
                  <td><center><?php  echo $wanita_bod ?></center></td>
                  <td><center><?php  echo $pria_bod ?></center></td>
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                  <?php
                  for($i4=0;$i4<3;$i4++){ ?>
                    <td><center>0</center></td>
                  <?php } ?>
                  <?php 
                  $d3_bod = $this->db->query("SELECT count(recid_karyawan) as d3_bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and pendidikan = 'D3' and sts_aktif = 'Aktif'")->result();
                  foreach ($d3_bod as $d3_bod) {$d3_bod = $d3_bod->d3_bod;
                    $d31a = $d31a + $d3_bod;}
                   $s1_bod = $this->db->query("SELECT count(recid_karyawan) as s1_bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and pendidikan = 'S1' and sts_aktif = 'Aktif'")->result();
                  foreach ($s1_bod as $s1_bod) {$s1_bod = $s1_bod->s1_bod; 
                   $s11a = $s11a + $s1_bod;}
                   $s2_bod = $this->db->query("SELECT count(recid_karyawan) as s2_bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department' and pendidikan = 'S2' and sts_aktif = 'Aktif'")->result();
                  foreach ($s2_bod as $s2_bod) {$s2_bod = $s2_bod->s2_bod;
                   $s21a = $s21a + $s2_bod;}
                  ?>
                    <td><center><?php  echo $d3_bod ?></center></td>
                    <td><center><?php  echo $s1_bod ?></center></td>
                    <td><center><?php  echo $s2_bod ?></center></td>
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                  <?php
                    $umurs = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select nik, nama_karyawan, tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department') as dummy_table")->result();
                    foreach ($umurs as $umurs) {
                     $u35b = $umurs->kurang35;
                     $u44b = $umurs->u3644;
                     $u45b = $umurs->lebih45;
                     $jml5b = $u35b + $u44b + $u45b;
                     $u35ab = $u35ab + $u35b;
                     $u44ab = $u44ab + $u44b;
                     $u45ab = $u45ab + $u45b;
                     $jml5ab = $jml5ab + $jml5b;
                    }
                    //range masker
                    $maskers = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on j.recid_jbtn = k.recid_jbtn where sts_aktif = 'Aktif' and (j.recid_jbtn = '10' or j.recid_jbtn = '11') and b.department = '$dept->department') as dummy_table")->result();
                    foreach ($maskers as $usias) {
                      $u3s = $usias->kurang3;
                      $u10s = $usias->u10;
                      $u11s = $usias->lebih10;
                      $jml6s = $u3s + $u10s + $u11s;
                      $u3as = $u3as + $u3s;
                      $u10as = $u10as + $u10s;
                      $u11as = $u11as + $u11s;
                      $jml6as = $jml6as + $jml6s;
                    }
                  ?>
                  <td><center><?php  echo $u35b; ?></td>
                  <td><center><?php  echo $u44b ?></td>
                  <td><center><?php  echo $u45b ?></td>
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                  <td><center><?php echo $u3s ?></center></td>
                  <td><center><?php echo $u10s ?></center></td>
                  <td><center><?php echo $u11s ?></center></td>
                  <td><center><b><?php echo $bod+$gm; ?></b></center></td>
                </tr>
                <!-- ********************** BAGIAN DI BAWAH DEPARTMENT *********************************** -->
                <tr>
                  <?php
                     $query = $this->db->query("SELECT nama_struktur From struktur s join bagian b on s.recid_struktur = b.recid_struktur where department = '$dept->department' and (s.nama_struktur != 'BOD' and s.nama_struktur != 'GM')  and b.is_delete = '0' and s.sis_delete = '0'  group by s.nama_struktur")->result(); // AND s.nama_struktur NOT IN ('BOD', 'Presdir')
                     foreach ($query as $bagian) { ?>
                      <td><?php echo strtoupper($bagian->nama_struktur) ?></td>   
                      <!-- DATA PER KOLOM -->
                          <?php 
                              //jml MGR
                          $mgr = $this->db->query("SELECT count(recid_karyawan) as mgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '9' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                          foreach ($mgr as $mgr) {
                           $mgr = $mgr->mgr;
                           $mgr1 = $mgr1 + $mgr;
                         }

                              //jml ASMGR
                         $asmgr = $this->db->query("SELECT count(recid_karyawan) as asmgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '8' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($asmgr as $asmgr) {
                           $asmgr = $asmgr->asmgr;
                           $asmgr1 = $asmgr1 + $asmgr;
                         }

                                //jml CO
                         $co = $this->db->query("SELECT count(recid_karyawan) as co FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '7' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($co as $co) {
                           $co = $co->co;
                           $co1 = $co1 + $co;
                         }

                                //jml O
                         $o = $this->db->query("SELECT count(recid_karyawan) as o FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '6' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($o as $o) {
                           $o = $o->o;
                           $o1 = $o1 + $o;
                         }

                                 //jml kasi
                         $kasi = $this->db->query("SELECT count(recid_karyawan) as kasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '5' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($kasi as $kasi) {
                           $kasi = $kasi->kasi;
                           $kasi1 = $kasi1 + $kasi;
                         }

                                 //jml wakasi
                         $wakasi = $this->db->query("SELECT count(recid_karyawan) as wakasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '4' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($wakasi as $wakasi) {
                           $wakasi = $wakasi->wakasi;
                           $wakasi1 = $wakasi1 + $wakasi;
                         }

                                 //jml karu
                         $karu = $this->db->query("SELECT count(recid_karyawan) as karu FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '3' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($karu as $karu) {
                           $karu = $karu->karu;
                           $karu1 = $karu1 + $karu;
                         }

                                 //jml wakaru
                         $wakaru = $this->db->query("SELECT count(recid_karyawan) as wakaru FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where j.recid_jbtn = '2' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($wakaru as $wakaru) {
                           $wakaru = $wakaru->wakaru;
                           $wakaru1 = $wakaru1 + $wakaru;
                         }

                                 //jml operator bulanan
                         $opb = $this->db->query("SELECT count(recid_karyawan) as opb FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and bulanan = 'Ya' and j.recid_jbtn = '1'")->result();
                         foreach ($opb as $opb) {
                           $opb = $opb->opb;
                           $opb1 = $opb1 + $opb;
                         }

                                 //jml operator harian
                         $oph = $this->db->query("SELECT count(recid_karyawan) as oph FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where  s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and bulanan = 'Tidak' and j.recid_jbtn = '1'")->result();
                         foreach ($oph as $oph) {
                           $oph = $oph->oph;
                           $oph1 = $oph1 + $oph;
                         }

                             // jml per Golongan
                       $jml1 = $this->db->query("SELECT count(recid_karyawan) as jml1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where s.nama_struktur = '$bagian->nama_struktur' and (j.recid_jbtn != '11' or j.recid_jbtn != '10' ) and sts_aktif = 'Aktif'")->result();
                       foreach ($jml1 as $jml1) {
                         $jml1 = $jml1->jml1;
                           $jml1a = $jml1a + $jml1;                         
                       }

                         // $jml1 = $mgr + $asmgr + $co + $o + $kasi + $wakasi + $karu + $wakaru + $opb + $oph;
                         // $jml1a = $jml1a + $jml1;

                           //tetap
                         $tetap = $this->db->query("SELECT count(k.recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and k.sts_jbtn = 'Tetap'")->result();
                         foreach ($tetap as $tetap) {
                           $tetap = $tetap->tetap;
                           $tetap1 = $tetap1 + $tetap;
                         }

                            //kontrak
                         $kontrak = $this->db->query("SELECT count(k.recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and k.sts_jbtn = 'Kontrak'")->result();
                         foreach ($kontrak as $kontrak) {
                           $kontrak = $kontrak->kontrak;
                           $kontrak1 = $kontrak1 + $kontrak;
                         }
                          //kontrak
                         $honor = $this->db->query("SELECT count(k.recid_karyawan) as honor FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and k.sts_jbtn = 'Honorer'")->result();
                         foreach ($honor as $honor) {
                           $honor = $honor->honor;
                           $honor1 = $honor + $honor;
                         }

                              //jml per status karyawan
                       // $jml2 = $this->db->query("SELECT count(k.recid_karyawan) as jml2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join karir r on k.recid_karyawan = r.recid_karyawan where j.recid_jbtn = '1' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                       // foreach ($jml2 as $jml2) {
                       //   $jml2 = $jml2->jml2;
                       // }
                         $jml2 = $tetap + $kontrak;
                         $jml2a = $jml2a + $jml2;

                                //jml Pria
                         $L = $this->db->query("SELECT s.nama_struktur, count(recid_karyawan) as total_l FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.jenkel = 'Pria' and s.nama_struktur = '$bagian->nama_struktur'  and sts_aktif = 'Aktif'")->result();
                         foreach ($L as $pria) {
                           $L = $pria->total_l;
                           $L1 = $L1 + $L;
                         }
                                 //jml_Wanita
                         $P = $this->db->query("SELECT count(recid_karyawan) as total_p FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.jenkel = 'Wanita' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($P as $wanita) {
                           $P = $wanita->total_p;
                           $P1 = $P1 + $P;
                         }

                       //           //jml per gender
                       // $jml3 = $this->db->query("SELECT count(recid_karyawan) as jml3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                       // foreach ($jml3 as $wanita) {
                       //   $jml3 = $wanita->jml3;
                       // }
                         $jml3 = $L + $P;
                         $jml3a = $jml3a + $jml3;

                                 //jml sd
                         $sd = $this->db->query("SELECT count(recid_karyawan) as sd FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.Pendidikan = 'SD' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($sd as $sd) {
                           $sd = $sd->sd;
                           $sd1 = $sd1 + $sd;
                         }
                                 //jml smp
                         $smp = $this->db->query("SELECT count(recid_karyawan) as smp FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.Pendidikan = 'SMP' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($smp as $smp) {
                           $smp = $smp->smp;
                           $smp1 = $smp1 + $smp;
                         }
                                 //jml slta
                         $slta = $this->db->query("SELECT count(recid_karyawan) as slta FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.Pendidikan = 'SMA' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($slta as $slta) {
                           $slta = $slta->slta;
                           $slta1 = $slta1 + $slta;
                         }
                                 //jml d3
                         $d3 = $this->db->query("SELECT count(recid_karyawan) as d3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.Pendidikan = 'D3' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($d3 as $d3) {
                           $d3 = $d3->d3;
                           $d31 = $d31 + $d3;
                         }
                                  //jml s1
                         $s1 = $this->db->query("SELECT count(recid_karyawan) as s1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where k.Pendidikan = 'S1' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($s1 as $s1) {
                           $s1 = $s1->s1;
                           $s11 = $s11 + $s1;
                         }
                                  //jml s2
                         $s2 = $this->db->query("SELECT count(recid_karyawan) as s2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where k.Pendidikan = 'S2' and s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif'")->result();
                         foreach ($s2 as $s2) {
                           $s2 = $s2->s2;
                           $s21 = $s21 + $s2;
                         }

                                   //jml per pendidikan
                       // $jml4 = $this->db->query("SELECT count(recid_karyawan) as jml4 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur  where s.nama_struktur = '$bagian->nama_struktur' and sts_aktif = 'Aktif' and (k.pendidikan = 'SMP' or k.pendidikan = 'SMA' or k.pendidikan = 'D3' or k.pendidikan = 'S1' or k.pendidikan = 'S2')")->result();
                       // foreach ($jml4 as $jml4) {
                       //   $jml4 = $jml4->jml4;
                       // }
                         $jml4 =  $sd + $smp + $slta + $d3 + $s1 + $s2;
                         $jml4a = $jml4a + $jml4;

                                 //range umur
                         $umur = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select nik, nama_karyawan, tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where sts_aktif = 'Aktif' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                         foreach ($umur as $umur) {
                          $u35 = $umur->kurang35;
                          $u44 = $umur->u3644;
                          $u45 = $umur->lebih45;
                          $jml5 = $u35 + $u44 + $u45;
                          $u35a = $u35a + $u35;
                          $u44a = $u44a + $u44;
                          $u45a = $u45a + $u45;
                          $jml5a = $jml5a + $jml5;
                        }

                      //range masker
                        $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k join bagian b on b.recid_bag = k.recid_bag join struktur s on s.recid_struktur = b.recid_struktur where sts_aktif = 'Aktif' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                        foreach ($masker as $usia) {
                          $u3 = $usia->kurang3;
                          $u10 = $usia->u10;
                          $u11 = $usia->lebih10;
                          $jml6 = $u3 + $u10 + $u11;
                          $u3a = $u3a + $u3;
                          $u10a = $u10a + $u10;
                          $u11a = $u11a + $u11;
                          $jml6a = $jml6a + $jml6;
                        }
                        ?>
                        <td><center>0</center></td>
                        <td><center>0</center></td>
                        <td><center><?php echo $mgr; ?></center></td>
                        <td><center><?php echo $asmgr; ?></center></td>
                        <td><center><?php echo $co; ?></center></td>
                        <td><center><?php echo $o; ?></center></td>
                        <td><center><?php echo $kasi; ?></center></td>
                        <td><center><?php echo $wakasi; ?></center></td>
                        <td><center><?php echo $karu; ?></center></td>
                        <td><center><?php echo $wakaru; ?></center></td>
                        <td><center><?php echo $opb; ?></center></td>
                        <td><center><?php echo $oph;  ?></center></td>
                        <td><center><b><?php echo $jml1; ?></b></center></td>
                        <td><center><?php echo $tetap ?></center></td>
                        <td><center><?php echo $kontrak ?></center></td>
                        <td><center><?php echo $honor; ?></center></td>
                        <td><center><b><?php echo $jml2; ?></b></center></td>
                        <td><center><?php echo $P ?></center></td>
                        <td><center><?php echo $L ?></center></td>
                        <td><center><b><?php echo $jml3; ?></b></center></td>
                        <td><center><?php echo $sd ?></center></td>
                        <td><center><?php echo $smp ?></center></td>
                        <td><center><?php echo $slta ?></center></td>
                        <td><center><?php echo $d3 ?></center></td>
                        <td><center><?php echo $s1 ?></center></td>
                        <td><center><?php echo $s2 ?></center></td>
                        <td><center><b><?php echo $jml4; ?></b></center></td>
                        <td><center><?php echo $u35 ?></center></td>
                        <td><center><?php echo $u44 ?></center></td>
                        <td><center><?php echo $u45 ?></center></td>
                        <td><center><b><?php echo $jml5; ?></b></center></td>
                        <td><center><?php echo $u3 ?></center></td>
                        <td><center><?php echo $u10 ?></center></td>
                        <td><center><?php echo $u11 ?></center></td>
                        <td><center><b><?php echo $jml6; ?></b></center></td>
                      </tr>
                  <?php  } // loop bagian ?>
                <?php   } // PUNYA DEPARTEMEN ?> 
         
            <?php } ?><!-- Punya loop dept -->
            <tr style="background-color:#4d648d; color: #fff">
             <td><b>Grand Total</b></td>
             <td><center><b><?php echo $bod1; ?></b></td>
               <td><center><b><?php echo $gm1; ?></b></td>
                 <td><center><b><?php echo $mgr1; ?></b></center></td>
                 <td><center><b><?php echo $asmgr1; ?></b></center></td>
                 <td><center><b><?php echo $co1; ?></b></center></td>
                 <td><center><b><?php echo $o1; ?></b></center></td>
                 <td><center><b><?php echo $kasi1; ?></b></center></td>
                 <td><center><b><?php echo $wakasi1; ?></b></center></td>
                 <td><center><b><?php echo $karu1; ?></b></center></td>
                 <td><center><b><?php echo $wakaru1; ?></b></center></td>
                 <td><center><b><?php echo $opb1; ?></b></center></td>
                 <td><center><b><?php echo $oph1;  ?></b></center></td>
                 <td><center><b><?php echo $jml1a+$bod1+$gm1; ?></b></center></td>
                 <td><center><b><?php echo $tetap1+$tetap_bod1+$tetap_pres ?></b></center></td>
                 <td><center><b><?php echo $kontrak1+$kontrak_bod+$kontrak_pres ?></b></center></td>
                 <td><center><b><?php echo $honor1+$honor_bod+$honor_pres ?></b></center></td>
                 <td><center><b><?php echo $jml2a+$bod1+$gm1; ?></b></center></td>
                 <td><center><b><?php echo $P1+$P1a+$wanita_pres?></b></center></td>
                 <td><center><b><?php echo $L1+$L1a+$pria_pres ?></b></center></td>
                 <td><center><b><?php echo $jml3a+$bod1+$gm1; ?></b></center></td>
                 <td><center><b><?php echo $sd1; ?></b></center></td>
                 <td><center><b><?php echo $smp1; ?></b></center></td>
                 <td><center><b><?php echo $slta1; ?></b></center></td>
                 <td><center><b><?php echo $d31+$d31a+$d3_pres; ?></b></center></td>
                 <td><center><b><?php echo $s11+$s11a+$s1_pres; ?></b></center></td>
                 <td><center><b><?php echo $s21+$s21a+$s2_pres; ?></b></center></td>
                 <td><center><b><?php echo $jml4a+$bod1+$gm1; ?></b></center></td>
                 <td><center><b><?php echo $u35a+$u35ab+$u35abc; ?></b></center></td>
                 <td><center><b><?php echo $u44a+$u44ab+$u44abc; ?></b></center></td>
                 <td><center><b><?php echo $u45a+$u45ab+$u45abc ?></b></center></td>
                 <td><center><b><?php echo $jml5a+$bod1+$gm1; ?></b></center></td>
                 <td><center><b><?php echo $u3a+$u3as+$u3asc; ?></b></center></td>
                 <td><center><b><?php echo $u10a+$u10as+$u10asc; ?></b></center></td>
                 <td><center><b><?php echo $u11a+$u11as+$u11asc ?></b></center></td>
                 <td><center><b><?php echo $jml6a+$bod1+$gm1; ?></b></center></td>
               </tr>
             </tbody>
           </table>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
<footer>
  <div class="pull-right">
    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
  </div>
  <div class="clearfix"></div>
</footer>
 <!-- /footer content
</div>
</div>

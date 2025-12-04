<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
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
            <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Human Resource</h2>
            <ul class="nav navbar-right panel_toolbox">
            </ul>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <table id="tr_hr" class="<!-- table table-striped table-bordered -->" border='1' width="100%">
              <thead>
                <tr>
                  <th rowspan="2">
                    <center>Bagian/Department</center>
                  </th>
                  <th colspan="12">
                    <center>Golongan / Jabatan</center>
                  </th>
                  <th colspan="5">
                    <center>Status Karyawan</center>
                  </th>
                  <th colspan="3">
                    <center>Gender</center>
                  </th>
                  <th colspan="7">
                    <center>Pendidikan</center>
                  </th>
                  <th colspan="4">
                    <center>Usia</center>
                  </th>
                  <th colspan="4">
                    <center>Masa Kerja</center>
                  </th>
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
                  <th>OP</th>
                  <th>&Sigma;</th>
                  <th>TETAP</th>
                  <th>ADVISOR</th>
                  <th>KONTRAK</th>
                  <th>PROJECT</th>
                  <th>&Sigma;</th>
                  <th>L</th>
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
                foreach ($department as $dept) { ?>
                  <!-- ********************** DIREKTORAT UTAMA *********************************** -->
                  <tr style="background-color: #CCC">
                    <td><b><?php echo strtoupper($dept->nama_department) ?></b></td>
                    <?php
                    // jabatan by direktorat
                    for ($j = 11; $j > 0; $j--) {
                      $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department join jabatan j on k.recid_jbtn = j.recid_jbtn  where j.tingkatan = '$j' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and b.recid_department = '$dept->recid_department'")->result();
                      foreach ($pres as $pres) {
                        $pres = $pres->pres;
                        if ($pres == '0') {
                          $pres = '0';
                        } else {
                          $pres = $pres;
                        }
                      } ?>
                      <td>
                        <center><?php echo $pres; ?>
                      </td>
                    <?php } ?>
                    <!-- total jabatan -->
                    <?php
                    $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and  b.recid_department = '$dept->recid_department'")->result();
                    foreach ($pres as $pres) {
                      $pres = $pres->pres;
                      if ($pres == '0') {
                        $pres = '0';
                      } else {
                        $pres = $pres;
                      }
                    } ?>
                    <td>
                      <center><?php echo $pres; ?>
                    </td>

                    <!-- status jabatan by direktorat -->
                    <?php
                    $sts_jbtan = $this->db->query("SELECT COUNT(IF(sts_jabatan = 'Tetap',1,NULL)) AS 'tetap', COUNT(IF(sts_jabatan = 'Advisor',1,NULL)) AS 'advisor', COUNT(IF(sts_jabatan = 'Kontrak',1,NULL)) AS 'kontrak', COUNT(IF(sts_jabatan = 'Project',1,NULL)) AS 'project' FROM (select sts_jabatan from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and  b.recid_department = '$dept->recid_department') as dummy_table")->result();
                    foreach ($sts_jbtan as $sts_jbtan) {
                      $tetap = $sts_jbtan->tetap;
                      $kontrak = $sts_jbtan->kontrak;
                      $advisor = $sts_jbtan->advisor;
                      $project = $sts_jbtan->project;
                    } ?>
                    <td>
                      <center><?php echo $tetap; ?>
                    </td>
                    <td>
                      <center><?php echo $advisor; ?>
                    </td>
                    <td>
                      <center><?php echo $kontrak; ?>
                    </td>
                    <td>
                      <center><?php echo $project; ?>
                    </td>
                    <td>
                      <center><?php echo $tetap + $advisor + $kontrak + $project; ?>
                    </td>

                    <!-- gender by direktorat -->
                    <?php
                    $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'  and  b.recid_department = '$dept->recid_department') as dummy_table")->result();
                    foreach ($gender as $gender) {
                      $pria = $gender->pria;
                      $Perempuan = $gender->Perempuan;
                    } ?>
                    <td>
                      <center><?php echo $pria; ?>
                    </td>
                    <td>
                      <center><?php echo $Perempuan; ?>
                    </td>
                    <td>
                      <center><?php echo $pria + $Perempuan; ?>
                    </td>

                    <!-- pendidikan by direktorat -->
                    <?php
                    $pendidikan = $this->db->query("SELECT COUNT(IF(pendidikan = 'SD',1,NULL)) AS 'SD', 
                        COUNT(IF(pendidikan = 'SMP',1,NULL)) AS 'SMP',
                        COUNT(IF(pendidikan = 'SMA',1,NULL)) AS 'SMA',
                        COUNT(IF(pendidikan = 'D3',1,NULL)) AS 'D3',
                        COUNT(IF(pendidikan = 'S1',1,NULL)) AS 'S1',
                        COUNT(IF(pendidikan = 'S2',1,NULL)) AS 'S2'
                        FROM (select pendidikan from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and  b.recid_department = '$dept->recid_department') as dummy_table")->result();
                    foreach ($pendidikan as $pendidikan) {
                      $SD = $pendidikan->SD;
                      $SMP = $pendidikan->SMP;
                      $SMA = $pendidikan->SMA;
                      $D3 = $pendidikan->D3;
                      $S1 = $pendidikan->S1;
                      $S2 = $pendidikan->S2;
                    } ?>
                    <td>
                      <center><?php echo $SD; ?>
                    </td>
                    <td>
                      <center><?php echo $SMP; ?>
                    </td>
                    <td>
                      <center><?php echo $SMA; ?>
                    </td>
                    <td>
                      <center><?php echo $D3; ?>
                    </td>
                    <td>
                      <center><?php echo $S1; ?>
                    </td>
                    <td>
                      <center><?php echo $S2; ?>
                    </td>
                    <td>
                      <center><?php echo $SD + $SMP + $SMA + $D3 + $S1 + $S2; ?>
                    </td>

                    <!-- range umur-->
                    <?php
                    $umur = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and  b.recid_department = '$dept->recid_department') as dummy_table")->result();
                    foreach ($umur as $umur) {
                      $u35 = $umur->kurang35;
                      $u44 = $umur->u3644;
                      $u45 = $umur->lebih45;
                      $jml5 = $u35 + $u44 + $u45;
                    }
                    ?>
                    <td>
                      <center><?php echo $u35; ?>
                    </td>
                    <td>
                      <center><?php echo $u44; ?>
                    </td>
                    <td>
                      <center><?php echo $u45; ?>
                    </td>
                    <td>
                      <center><?php echo $jml5; ?>
                    </td>

                    <!--range masker-->
                    <?php
                    $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and  b.recid_department = '$dept->recid_department') as dummy_table")->result();
                    foreach ($masker as $usia) {
                      $u3 = $usia->kurang3;
                      $u10 = $usia->u10;
                      $u11 = $usia->lebih10;
                      $jml6 = $u3 + $u10 + $u11;
                    }
                    ?>
                    <td>
                      <center><?php echo $u3; ?>
                    </td>
                    <td>
                      <center><?php echo $u10; ?>
                    </td>
                    <td>
                      <center><?php echo $u11; ?>
                    </td>
                    <td>
                      <center><?php echo $jml6; ?>
                    </td>
                  </tr>
                  <!-- ********************** BAGIAN DI BAWAH DEPARTMENT *********************************** -->
                  <?php
                  $query = $this->db->query("SELECT nama_struktur From struktur s join bagian b on s.recid_struktur = b.recid_struktur join department d on d.recid_department = b.recid_department where b.recid_department = '$dept->recid_department' and b.is_delete = '0' and s.sis_delete = '0'  group by s.nama_struktur order by s.recid_struktur asc")->result(); // AND s.nama_struktur NOT IN ('BOD', 'Presdir')
                  foreach ($query as $bagian) {
                    $struktur = $bagian->nama_struktur;
                    $struktur = substr($struktur, strpos($struktur, " ") + 1);
                    if ($role == '1' or $role == '2') {
                      $struktur = $bagian->nama_struktur;
                    }
                  ?>
                    <tr>
                      <td><b><?php echo strtoupper($struktur) ?></b></td>
                      <?php
                      // jabatan by nama struktur
                      for ($j = 11; $j > 0; $j--) {
                        $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where j.tingkatan = '$j' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur'")->result();
                        foreach ($pres as $pres) {
                          $pres = $pres->pres;
                          if ($pres == '0') {
                            $pres = '0';
                          } else {
                            $pres = $pres;
                          }
                        } ?>
                        <td>
                          <center><?php echo $pres; ?>
                        </td>
                      <?php } ?>
                      <!-- total jabatan -->
                      <?php
                      $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur'")->result();
                      foreach ($pres as $pres) {
                        $pres = $pres->pres;
                        if ($pres == '0') {
                          $pres = '0';
                        } else {
                          $pres = $pres;
                        }
                      } ?>
                      <td>
                        <center><?php echo $pres; ?>
                      </td>

                      <!-- status jabatan by struktur -->
                      <?php
                      $sts_jbtan = $this->db->query("SELECT COUNT(IF(sts_jabatan = 'Tetap',1,NULL)) AS 'tetap', COUNT(IF(sts_jabatan = 'Advisor',1,NULL)) AS 'advisor', COUNT(IF(sts_jabatan = 'Kontrak',1,NULL)) AS 'kontrak', COUNT(IF(sts_jabatan = 'Project',1,NULL)) AS 'project' FROM (select sts_jabatan from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                      foreach ($sts_jbtan as $sts_jbtan) {
                        $tetap = $sts_jbtan->tetap;
                        $kontrak = $sts_jbtan->kontrak;
                        $advisor = $sts_jbtan->advisor;
                        $project = $sts_jbtan->project;
                      } ?>
                      <td>
                        <center><?php echo $tetap; ?>
                      </td>
                      <td>
                        <center><?php echo $advisor; ?>
                      </td>
                      <td>
                        <center><?php echo $kontrak; ?>
                      </td>
                      <td>
                        <center><?php echo $project; ?>
                      </td>
                      <td>
                        <center><?php echo $tetap + $advisor + $kontrak + $project; ?>
                      </td>

                      <!-- gender by struktur -->
                      <?php
                      $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'  and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                      foreach ($gender as $gender) {
                        $pria = $gender->pria;
                        $Perempuan = $gender->Perempuan;
                      } ?>
                      <td>
                        <center><?php echo $pria; ?>
                      </td>
                      <td>
                        <center><?php echo $Perempuan; ?>
                      </td>
                      <td>
                        <center><?php echo $pria + $Perempuan; ?>
                      </td>

                      <!-- pendidikan by struktur -->
                      <?php
                      $pendidikan = $this->db->query("SELECT COUNT(IF(pendidikan = 'SD',1,NULL)) AS 'SD', 
                        COUNT(IF(pendidikan = 'SMP',1,NULL)) AS 'SMP',
                        COUNT(IF(pendidikan = 'SMA',1,NULL)) AS 'SMA',
                        COUNT(IF(pendidikan = 'D3',1,NULL)) AS 'D3',
                        COUNT(IF(pendidikan = 'S1',1,NULL)) AS 'S1',
                        COUNT(IF(pendidikan = 'S2',1,NULL)) AS 'S2'
                        FROM (select pendidikan from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department  where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                      foreach ($pendidikan as $pendidikan) {
                        $SD = $pendidikan->SD;
                        $SMP = $pendidikan->SMP;
                        $SMA = $pendidikan->SMA;
                        $D3 = $pendidikan->D3;
                        $S1 = $pendidikan->S1;
                        $S2 = $pendidikan->S2;
                      } ?>
                      <td>
                        <center><?php echo $SD; ?>
                      </td>
                      <td>
                        <center><?php echo $SMP; ?>
                      </td>
                      <td>
                        <center><?php echo $SMA; ?>
                      </td>
                      <td>
                        <center><?php echo $D3; ?>
                      </td>
                      <td>
                        <center><?php echo $S1; ?>
                      </td>
                      <td>
                        <center><?php echo $S2; ?>
                      </td>
                      <td>
                        <center><?php echo $SD + $SMP + $SMA + $D3 + $S1 + $S2; ?>
                      </td>

                      <!-- range umur-->
                      <?php
                      $umur = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                      foreach ($umur as $umur) {
                        $u35 = $umur->kurang35;
                        $u44 = $umur->u3644;
                        $u45 = $umur->lebih45;
                        $jml5 = $u35 + $u44 + $u45;
                      }
                      ?>
                      <td>
                        <center><?php echo $u35; ?>
                      </td>
                      <td>
                        <center><?php echo $u44; ?>
                      </td>
                      <td>
                        <center><?php echo $u45; ?>
                      </td>
                      <td>
                        <center><?php echo $jml5; ?>
                      </td>

                      <!--range masker-->
                      <?php
                      $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' and s.nama_struktur = '$bagian->nama_struktur') as dummy_table")->result();
                      foreach ($masker as $usia) {
                        $u3 = $usia->kurang3;
                        $u10 = $usia->u10;
                        $u11 = $usia->lebih10;
                        $jml6 = $u3 + $u10 + $u11;
                      }
                      ?>
                      <td>
                        <center><?php echo $u3; ?>
                      </td>
                      <td>
                        <center><?php echo $u10; ?>
                      </td>
                      <td>
                        <center><?php echo $u11; ?>
                      </td>
                      <td>
                        <center><?php echo $jml6; ?>
                      </td>

                    <?php } ?>
                    <!-- PUNYA INDEKS BAGIAN -->
                  <?php } ?>
                  <!-- PUNYA DIREKTORAT -->
                  <!-- ********************** GRAND TOTAL *********************************** -->
                    <tr style="background-color:#4d648d; color: #fff">
                      <td><b>Grand Total</b></td>
                      <?php
                      // jabatan ALL
                      for ($j = 11; $j > 0; $j--) {
                        $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where j.tingkatan = '$j' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' ")->result();
                        foreach ($pres as $pres) {
                          $pres = $pres->pres;
                          if ($pres == '0') {
                            $pres = '0';
                          } else {
                            $pres = $pres;
                          }
                        } ?>
                        <td>
                          <center><?php echo $pres; ?>
                        </td>
                      <?php } ?>
                      <!-- total jabatan -->
                      <?php
                      $pres = $this->db->query("SELECT count(recid_karyawan) as pres FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'")->result();
                      foreach ($pres as $pres) {
                        $pres = $pres->pres;
                        if ($pres == '0') {
                          $pres = '0';
                        } else {
                          $pres = $pres;
                        }
                      } ?>
                      <td>
                        <center><?php echo $pres; ?>
                      </td>

                      <!-- total status jabatan -->
                      <?php
                      $sts_jbtan = $this->db->query("SELECT COUNT(IF(sts_jabatan = 'Tetap',1,NULL)) AS 'tetap', COUNT(IF(sts_jabatan = 'Advisor',1,NULL)) AS 'advisor', COUNT(IF(sts_jabatan = 'Kontrak',1,NULL)) AS 'kontrak', COUNT(IF(sts_jabatan = 'Project',1,NULL)) AS 'project' FROM (select sts_jabatan from karyawan k join jabatan j on k.recid_jbtn = j.recid_jbtn  where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0') as dummy_table")->result();
                      foreach ($sts_jbtan as $sts_jbtan) {
                        $tetap = $sts_jbtan->tetap;
                        $kontrak = $sts_jbtan->kontrak;
                        $advisor = $sts_jbtan->advisor;
                        $project = $sts_jbtan->project;
                      } ?>
                      <td>
                        <center><?php echo $tetap; ?>
                      </td>
                      <td>
                        <center><?php echo $advisor; ?>
                      </td>
                      <td>
                        <center><?php echo $kontrak; ?>
                      </td>
                      <td>
                        <center><?php echo $project; ?>
                      </td>
                      <td>
                        <center><?php echo $tetap + $advisor + $kontrak + $project; ?>
                      </td>

                      <!-- total gender -->
                      <?php
                      $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0') as dummy_table")->result();
                      foreach ($gender as $gender) {
                        $pria = $gender->pria;
                        $Perempuan = $gender->Perempuan;
                      } ?>
                      <td>
                        <center><?php echo $pria; ?>
                      </td>
                      <td>
                        <center><?php echo $Perempuan; ?>
                      </td>
                      <td>
                        <center><?php echo $pria + $Perempuan; ?>
                      </td>

                      <!-- total pendidikan -->
                      <?php
                      $pendidikan = $this->db->query("SELECT COUNT(IF(pendidikan = 'SD',1,NULL)) AS 'SD', 
                        COUNT(IF(pendidikan = 'SMP',1,NULL)) AS 'SMP',
                        COUNT(IF(pendidikan = 'SMA',1,NULL)) AS 'SMA',
                        COUNT(IF(pendidikan = 'D3',1,NULL)) AS 'D3',
                        COUNT(IF(pendidikan = 'S1',1,NULL)) AS 'S1',
                        COUNT(IF(pendidikan = 'S2',1,NULL)) AS 'S2'
                        FROM (select pendidikan from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0') as dummy_table")->result();
                      foreach ($pendidikan as $pendidikan) {
                        $SD = $pendidikan->SD;
                        $SMP = $pendidikan->SMP;
                        $SMA = $pendidikan->SMA;
                        $D3 = $pendidikan->D3;
                        $S1 = $pendidikan->S1;
                        $S2 = $pendidikan->S2;
                      } ?>
                      <td>
                        <center><?php echo $SD; ?>
                      </td>
                      <td>
                        <center><?php echo $SMP; ?>
                      </td>
                      <td>
                        <center><?php echo $SMA; ?>
                      </td>
                      <td>
                        <center><?php echo $D3; ?>
                      </td>
                      <td>
                        <center><?php echo $S1; ?>
                      </td>
                      <td>
                        <center><?php echo $S2; ?>
                      </td>
                      <td>
                        <center><?php echo $SD + $SMP + $SMA + $D3 + $S1 + $S2; ?>
                      </td>

                      <!-- range umur-->
                      <?php
                      $umur = $this->db->query("SELECT COUNT(IF(umur <= 35,1,NULL)) AS 'kurang35', COUNT(IF(umur BETWEEN 36 and 44,1,NULL)) AS 'u3644', COUNT(IF(umur >= 45,1,NULL)) AS 'lebih45' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0' ) as dummy_table")->result();
                      foreach ($umur as $umur) {
                        $u35 = $umur->kurang35;
                        $u44 = $umur->u3644;
                        $u45 = $umur->lebih45;
                        $jml5 = $u35 + $u44 + $u45;
                      }
                      ?>
                      <td>
                        <center><?php echo $u35; ?>
                      </td>
                      <td>
                        <center><?php echo $u44; ?>
                      </td>
                      <td>
                        <center><?php echo $u45; ?>
                      </td>
                      <td>
                        <center><?php echo $jml5; ?>
                      </td>


                      <!--range masker-->
                      <?php
                      $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn join department d on d.recid_department = b.recid_department where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0') as dummy_table")->result();
                      foreach ($masker as $usia) {
                        $u3 = $usia->kurang3;
                        $u10 = $usia->u10;
                        $u11 = $usia->lebih10;
                        $jml6 = $u3 + $u10 + $u11;
                      }
                      ?>
                      <td>
                        <center><?php echo $u3; ?>
                      </td>
                      <td>
                        <center><?php echo $u10; ?>
                      </td>
                      <td>
                        <center><?php echo $u11; ?>
                      </td>
                      <td>
                        <center><?php echo $jml6; ?>
                      </td>

                    </tr>
                    <!-- PUNYA GRAND TOTAL -->
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
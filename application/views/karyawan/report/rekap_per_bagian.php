<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Rekap Karyawan per Bagian</h3>
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
            <h2>Rekap Karyawan Per Bagian</h2>
            <ul class="nav navbar-right panel_toolbox">
            </ul>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <table id="tr_hr2" class="<!-- table table-striped table-bordered -->" border='1' width="100%">
              <thead>
                <tr>
                  <th rowspan="2">
                    <center>Pay Group</center>
                  </th>
                  <th rowspan="2">
                    <center>Bagian</center>
                  </th>
                  <th rowspan="2">
                    <center>Jml Karyawan</center>
                  </th>
                  <th rowspan="2">
                    <center>PKWTT</center>
                  </th>
                  <th rowspan="2">
                    <center>PKWT</center>
                  </th>
                  <th colspan="12">
                    <center>Golongan / Jabatan</center>
                  </th>
                  <th colspan="7">
                    <center>Pendidikan</center>
                  </th>
                  <th colspan="6">
                    <center>Usia</center>
                  </th>
                  <th colspan="3">
                    <center>Gender</center>
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
                  <th>SD</th>
                  <th>SMP</th>
                  <th>SMA</th>
                  <th>D3</th>
                  <th>S1</th>
                  <th>S2</th>
                  <th>&Sigma;</th>
                  <th>17-22</th>
                  <th>23-28</th>
                  <th>29-34</th>
                  <th>35-40</th>
                  <th>&gt;40</th>
                  <th>&Sigma;</th>
                  <th>L</th>
                  <th>P</th>
                  <th>&Sigma;</th>
                  <th>&lt;3</th>
                  <th>3-10</th>
                  <th>&gt;10</th>
                  <th>&Sigma;</th>
                </tr>
              </thead>
              <tbody>
                <!--https://stackoverflow.com/questions/32088676/how-to-add-rowspan-in-jquery-datatables-->
                <?php
                $pay = array('Admin', 'Penjualan', 'Direct', 'Indirect', 'BOD');
                for ($i = 0; $i < count($pay); $i++) {
                  $paygroup = $pay[$i];
                  $bagian = $this->db->query("SELECT distinct(indeks_hr) from bagian where pay_group = '$paygroup' and is_delete = 0 and indeks_hr != '' order by indeks_hr asc");
                  foreach ($bagian->result() as $bagian) {
                    $bg = $bagian->indeks_hr;
                    $bg = substr($bg, strpos($bg, " ") + 1);
                    if ($role == '1' or $role == '2') {
                      $bg = $bagian->indeks_hr;
                    }
                ?>
                    <tr>
                      <td><?php echo $paygroup ?></td>
                      <td><?php echo $bg ?></td>
                      <?php
                      /* JUMLAH KARYAWAN PER BAGIAN */
                      $jml = $this->db->query("SELECT count(recid_karyawan) as jml FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak'  and b.is_delete = 0");
                      foreach ($jml->result() as $jml) {
                        $jml_k = $jml->jml;
                      } ?>
                      <td>
                        <center><?php echo $jml_k ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN TETAP & ADVISOR PER BAGIAN */
                      $tetap = $this->db->query("SELECT count(recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (j.sts_jabatan = 'Tetap' or sts_jabatan = 'Advisor') and b.is_delete = 0");
                      foreach ($tetap->result() as $tetap) {
                        $tetap = $tetap->tetap;
                      } ?>
                      <td>
                        <center><?php echo $tetap ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN KONTRAK PER BAGIAN */
                      $kontrak = $this->db->query("SELECT count(recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (j.sts_jabatan = 'Kontrak' or j.sts_jabatan = 'Project') and b.is_delete = 0");
                      foreach ($kontrak->result() as $kontrak) {
                        $kontrak = $kontrak->kontrak;
                      } ?>
                      <td>
                        <center><?php echo $kontrak ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN BOD PER BAGIAN */
                      $bod = $this->db->query("SELECT count(recid_karyawan) as bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'BOD' and b.is_delete = 0");
                      foreach ($bod->result() as $bod) {
                        $bod = $bod->bod;
                      } ?>
                      <td>
                        <center><?php echo $bod ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN GM PER BAGIAN */
                      $gm = $this->db->query("SELECT count(recid_karyawan) as gm FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'GENERAL MANAGER' and b.is_delete = 0");
                      foreach ($gm->result() as $gm) {
                        $gm = $gm->gm;
                      } ?>
                      <td>
                        <center><?php echo $gm ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN MGR PER BAGIAN */
                      $mgr = $this->db->query("SELECT count(recid_karyawan) as mgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'MANAGER' and b.is_delete = 0");
                      foreach ($mgr->result() as $mgr) {
                        $mgr = $mgr->mgr;
                      } ?>
                      <td>
                        <center><?php echo $mgr ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN ASMGR PER BAGIAN */
                      $asmgr = $this->db->query("SELECT count(recid_karyawan) as asmgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'ASS MANAGER' and b.is_delete = 0");
                      foreach ($asmgr->result() as $asmgr) {
                        $asmgr = $asmgr->asmgr;
                      } ?>
                      <td>
                        <center><?php echo $asmgr ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN CO PER BAGIAN */
                      $co = $this->db->query("SELECT count(recid_karyawan) as co FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'CHIEF OFFICER' and b.is_delete = 0");
                      foreach ($co->result() as $co) {
                        $co = $co->co;
                      } ?>
                      <td>
                        <center><?php echo $co ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN O PER BAGIAN */
                      $o = $this->db->query("SELECT count(recid_karyawan) as o FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OFFICER' and b.is_delete = 0");
                      foreach ($o->result() as $o) {
                        $o = $o->o;
                      } ?>
                      <td>
                        <center><?php echo $o ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN KASI PER BAGIAN */
                      $kasi = $this->db->query("SELECT count(recid_karyawan) as kasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KASI' and b.is_delete = 0");
                      foreach ($kasi->result() as $kasi) {
                        $kasi = $kasi->kasi;
                      } ?>
                      <td>
                        <center><?php echo $kasi ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN WAKASI PER BAGIAN */
                      $wakasi = $this->db->query("SELECT count(recid_karyawan) as wakasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKASI' and b.is_delete = 0");
                      foreach ($wakasi->result() as $wakasi) {
                        $wakasi = $wakasi->wakasi;
                      } ?>
                      <td>
                        <center><?php echo $wakasi ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN KARU PER BAGIAN */
                      $karu = $this->db->query("SELECT count(recid_karyawan) as karu FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KARU' and b.is_delete = 0");
                      foreach ($karu->result() as $karu) {
                        $karu = $karu->karu;
                      } ?>
                      <td>
                        <center><?php echo $karu ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN WAKARU PER BAGIAN */
                      $wakaru = $this->db->query("SELECT count(recid_karyawan) as wakaru FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKARU' and b.is_delete = 0");
                      foreach ($wakaru->result() as $wakaru) {
                        $wakaru = $wakaru->wakaru;
                      } ?>
                      <td>
                        <center><?php echo $wakaru ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN OPERATOR PER BAGIAN */
                      $operator = $this->db->query("SELECT count(recid_karyawan) as operator FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OPERATOR' and b.is_delete = 0 ");
                      foreach ($operator->result() as $operator) {
                        $operator = $operator->operator;
                      } ?>
                      <td>
                        <center><?php echo $operator ?></center>
                      </td>
                      <td>
                        <center><?php echo $bod + $gm + $mgr + $asmgr + $co + $o + $kasi + $wakasi + $karu + $wakaru + $operator; ?>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN SD PER BAGIAN */
                      $sd = $this->db->query("SELECT count(recid_karyawan) as sd FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SD' and b.is_delete = 0");
                      foreach ($sd->result() as $sd) {
                        $sd = $sd->sd;
                      } ?>
                      <td>
                        <center><?php echo $sd ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN SMP PER BAGIAN */
                      $smp = $this->db->query("SELECT count(recid_karyawan) as smp FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMP' and b.is_delete = 0");
                      foreach ($smp->result() as $smp) {
                        $smp = $smp->smp;
                      } ?>
                      <td>
                        <center><?php echo $smp ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN SMA PER BAGIAN */
                      $sma = $this->db->query("SELECT count(recid_karyawan) as sma FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMA' and b.is_delete = 0");
                      foreach ($sma->result() as $sma) {
                        $sma = $sma->sma;
                      } ?>
                      <td>
                        <center><?php echo $sma ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN D3 PER BAGIAN */
                      $d3 = $this->db->query("SELECT count(recid_karyawan) as d3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'D3' and b.is_delete = 0");
                      foreach ($d3->result() as $d3) {
                        $d3 = $d3->d3;
                      } ?>
                      <td>
                        <center><?php echo $d3 ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN S1 PER BAGIAN */
                      $s1 = $this->db->query("SELECT count(recid_karyawan) as s1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S1' and b.is_delete = 0");
                      foreach ($s1->result() as $s1) {
                        $s1 = $s1->s1;
                      } ?>
                      <td>
                        <center><?php echo $s1 ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN S2 PER BAGIAN */
                      $s2 = $this->db->query("SELECT count(recid_karyawan) as s2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where b.indeks_hr = '$bagian->indeks_hr' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S2' and b.is_delete = 0");
                      foreach ($s2->result() as $s2) {
                        $s2 = $s2->s2;
                      } ?>
                      <td>
                        <center><?php echo $s2 ?></center>
                      </td>
                      <td>
                        <center><?php echo $sd + $smp + $sma + $d3 + $s1 + $s2 ?></center>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN Sesuai Umur PER BAGIAN */
                      $umur = $this->db->query("SELECT COUNT(IF(umur BETWEEN 17 and 22,1,NULL)) AS 'u1722', COUNT(IF(umur BETWEEN 23 and 28,1,NULL)) AS 'u2328', COUNT(IF(umur BETWEEN 29 and 34,1,NULL)) AS 'u2934',COUNT(IF(umur BETWEEN 35 and 40,1,NULL)) AS 'u3540', COUNT(IF(umur > 40,1,NULL)) AS 'lebih40' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and b.indeks_hr = '$bagian->indeks_hr' and b.is_delete = 0) as dummy_table")->result();
                      foreach ($umur as $umur) {
                        $u1722 = $umur->u1722;
                        $u2328 = $umur->u2328;
                        $u2934 = $umur->u2934;
                        $u3540 = $umur->u3540;
                        $lebih40 = $umur->lebih40;
                        $juml6 = $u1722 + $u2328 + $u2934 + $u3540 + $lebih40;
                      }
                      ?>
                      <td>
                        <center><?php echo $u1722; ?>
                      </td>
                      <td>
                        <center><?php echo $u2328; ?>
                      </td>
                      <td>
                        <center><?php echo $u2934; ?>
                      </td>
                      <td>
                        <center><?php echo $u3540; ?>
                      </td>
                      <td>
                        <center><?php echo $lebih40; ?>
                      </td>
                      <td>
                        <center><?php echo $juml6; ?>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN Sesuai JENIS KELAMIN PER BAGIAN */
                      $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak'  and b.indeks_hr = '$bagian->indeks_hr' and b.is_delete = 0) as dummy_table")->result();
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
                        <center><?php echo $Perempuan + $pria; ?>
                      </td>
                      <?php
                      /* JUMLAH KARYAWAN Sesuai Masa Kerja PER BAGIAN */
                      $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and b.indeks_hr = '$bagian->indeks_hr' and b.is_delete = 0) as dummy_table")->result();
                      foreach ($masker as $usia) {
                        $u3 = $usia->kurang3;
                        $u10 = $usia->u10;
                        $u11 = $usia->lebih10;
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
                        <center><?php echo $u11 + $u10 + $u3; ?>
                      </td>
                    </tr>
                  <?php } // LOOP BAGIAN PER PAYGROUP 
                  ?>
                  <!-- TOTAL PER PAYGROUP -->
                  <tr>
                    <td style="background-color: #CCC"><?php echo $paygroup ?></td>
                    <td style="background-color: #CCC"><b>TOTAL <?php echo $paygroup ?></b></td>
                    <?php
                    /* JUMLAH KARYAWAN PER PAYGROUP */
                    $jml = $this->db->query("SELECT count(recid_karyawan) as jml FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak'");
                    foreach ($jml->result() as $jml) {
                      $jml_k = $jml->jml;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $jml_k ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN TETAP & ADVISOR PER PAYGROUP */
                    $tetap = $this->db->query("SELECT count(recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (j.sts_jabatan = 'Tetap' or sts_jabatan = 'Advisor')");
                    foreach ($tetap->result() as $tetap) {
                      $tetap = $tetap->tetap;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $tetap ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN KONTRAK PER PAYGROUP */
                    $kontrak = $this->db->query("SELECT count(recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (sts_jabatan = 'Kontrak' or sts_jabatan = 'Project')");
                    foreach ($kontrak->result() as $kontrak) {
                      $kontrak = $kontrak->kontrak;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $kontrak ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN BOD PER PAYGROUP */
                    $bod = $this->db->query("SELECT count(recid_karyawan) as bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'BOD'");
                    foreach ($bod->result() as $bod) {
                      $bod = $bod->bod;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $bod ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN GM PER PAYGROUP */
                    $gm = $this->db->query("SELECT count(recid_karyawan) as gm FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'GENERAL MANAGER'");
                    foreach ($gm->result() as $gm) {
                      $gm = $gm->gm;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $gm ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN MGR PER PAYGROUP */
                    $mgr = $this->db->query("SELECT count(recid_karyawan) as mgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'MANAGER'");
                    foreach ($mgr->result() as $mgr) {
                      $mgr = $mgr->mgr;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $mgr ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN ASMGR PER PAYGROUP */
                    $asmgr = $this->db->query("SELECT count(recid_karyawan) as asmgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'ASS MANAGER'");
                    foreach ($asmgr->result() as $asmgr) {
                      $asmgr = $asmgr->asmgr;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $asmgr ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN CO PER PAYGROUP */
                    $co = $this->db->query("SELECT count(recid_karyawan) as co FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'CHIEF OFFICER'");
                    foreach ($co->result() as $co) {
                      $co = $co->co;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $co ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN O PER PAYGROUP */
                    $o = $this->db->query("SELECT count(recid_karyawan) as o FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OFFICER'");
                    foreach ($o->result() as $o) {
                      $o = $o->o;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $o ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN KASI PER PAYGROUP */
                    $kasi = $this->db->query("SELECT count(recid_karyawan) as kasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KASI'");
                    foreach ($kasi->result() as $kasi) {
                      $kasi = $kasi->kasi;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $kasi ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN WAKASI PER BAGIAN */
                    $wakasi = $this->db->query("SELECT count(recid_karyawan) as wakasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKASI'");
                    foreach ($wakasi->result() as $wakasi) {
                      $wakasi = $wakasi->wakasi;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $wakasi ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN KARU PER PAYGROUP */
                    $karu = $this->db->query("SELECT count(recid_karyawan) as karu FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KARU'");
                    foreach ($karu->result() as $karu) {
                      $karu = $karu->karu;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $karu ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN WAKARU PER PAYGROUP */
                    $wakaru = $this->db->query("SELECT count(recid_karyawan) as wakaru FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKARU'");
                    foreach ($wakaru->result() as $wakaru) {
                      $wakaru = $wakaru->wakaru;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $wakaru ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN OPERATOR PER PAYGROUP */
                    $operator = $this->db->query("SELECT count(recid_karyawan) as operator FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OPERATOR'");
                    foreach ($operator->result() as $operator) {
                      $operator = $operator->operator;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $operator ?></center>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $bod + $gm + $mgr + $asmgr + $co + $o + $kasi + $wakasi + $karu + $wakaru + $operator; ?>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN SD PER PAYGROUP */
                    $sd = $this->db->query("SELECT count(recid_karyawan) as sd FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SD'");
                    foreach ($sd->result() as $sd) {
                      $sd = $sd->sd;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $sd ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN SMP PER PAYGROUP */
                    $smp = $this->db->query("SELECT count(recid_karyawan) as smp FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMP'");
                    foreach ($smp->result() as $smp) {
                      $smp = $smp->smp;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $smp ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN SMA PER PAYGROUP */
                    $sma = $this->db->query("SELECT count(recid_karyawan) as sma FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMA'");
                    foreach ($sma->result() as $sma) {
                      $sma = $sma->sma;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $sma ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN D3 PER PAYGROUP */
                    $d3 = $this->db->query("SELECT count(recid_karyawan) as d3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'D3'");
                    foreach ($d3->result() as $d3) {
                      $d3 = $d3->d3;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $d3 ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN S1 PER PAYGROUP */
                    $s1 = $this->db->query("SELECT count(recid_karyawan) as s1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S1'");
                    foreach ($s1->result() as $s1) {
                      $s1 = $s1->s1;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $s1 ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN S2 PER PAYGROUP */
                    $s2 = $this->db->query("SELECT count(recid_karyawan) as s2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where pay_group='$paygroup' and sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S2'");
                    foreach ($s2->result() as $s2) {
                      $s2 = $s2->s2;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $s2 ?></center>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $sd + $smp + $sma + $d3 + $s1 + $s2 ?></center>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN Sesuai Umur PER PAYGROUP */
                    $umur = $this->db->query("SELECT COUNT(IF(umur BETWEEN 17 and 22,1,NULL)) AS 'u1722', COUNT(IF(umur BETWEEN 23 and 28,1,NULL)) AS 'u2328', COUNT(IF(umur BETWEEN 29 and 34,1,NULL)) AS 'u2934',COUNT(IF(umur BETWEEN 35 and 40,1,NULL)) AS 'u3540', COUNT(IF(umur > 40,1,NULL)) AS 'lebih40' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pay_group='$paygroup') as dummy_table")->result();
                    foreach ($umur as $umur) {
                      $u1722 = $umur->u1722;
                      $u2328 = $umur->u2328;
                      $u2934 = $umur->u2934;
                      $u3540 = $umur->u3540;
                      $lebih40 = $umur->lebih40;
                      $juml6 = $u1722 + $u2328 + $u2934 + $u3540 + $lebih40;
                    }
                    ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $u1722; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u2328; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u2934; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u3540; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $lebih40; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $juml6; ?>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN Sesuai JENIS KELAMIN PER PAYGROUP */
                    $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak'  and pay_group='$paygroup') as dummy_table")->result();
                    foreach ($gender as $gender) {
                      $pria = $gender->pria;
                      $Perempuan = $gender->Perempuan;
                    } ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $pria; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $Perempuan; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $Perempuan + $pria; ?>
                    </td>
                    <?php
                    /* JUMLAH KARYAWAN Sesuai Masa Kerja PER PAYGROUP */
                    $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pay_group='$paygroup') as dummy_table")->result();
                    foreach ($masker as $usia) {
                      $u3 = $usia->kurang3;
                      $u10 = $usia->u10;
                      $u11 = $usia->lebih10;
                    }
                    ?>
                    <td style="background-color: #CCC">
                      <center><?php echo $u3; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u10; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u11; ?>
                    </td>
                    <td style="background-color: #CCC">
                      <center><?php echo $u11 + $u10 + $u3; ?>
                    </td>
                  </tr>
                <?php } // LOOP PAYGROUP
                ?>
                <!-- GRAND TOTAL -->
                <tr style="background-color:#4d648d; color: #fff">
                  <td></td>
                  <td><b>GRAND TOTAL</b></td>
                  <?php
                  /* JUMLAH KARYAWAN PER ALL */
                  $jml = $this->db->query("SELECT count(recid_karyawan) as jml FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak'");
                  foreach ($jml->result() as $jml) {
                    $jml_k = $jml->jml;
                  } ?>
                  <td>
                    <center><?php echo $jml_k ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN TETAP & ADVISOR PER ALL */
                  $tetap = $this->db->query("SELECT count(recid_karyawan) as tetap FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (j.sts_jabatan = 'Tetap' or sts_jabatan = 'Advisor')");
                  foreach ($tetap->result() as $tetap) {
                    $tetap = $tetap->tetap;
                  } ?>
                  <td>
                    <center><?php echo $tetap ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN KONTRAK PER ALL */
                  $kontrak = $this->db->query("SELECT count(recid_karyawan) as kontrak FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and (sts_jabatan = 'Kontrak' or sts_jabatan='Project')");
                  foreach ($kontrak->result() as $kontrak) {
                    $kontrak = $kontrak->kontrak;
                  } ?>
                  <td>
                    <center><?php echo $kontrak ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN BOD PER ALL */
                  $bod = $this->db->query("SELECT count(recid_karyawan) as bod FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'BOD'");
                  foreach ($bod->result() as $bod) {
                    $bod = $bod->bod;
                  } ?>
                  <td>
                    <center><?php echo $bod ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN GM PER ALL */
                  $gm = $this->db->query("SELECT count(recid_karyawan) as gm FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'GENERAL MANAGER'");
                  foreach ($gm->result() as $gm) {
                    $gm = $gm->gm;
                  } ?>
                  <td>
                    <center><?php echo $gm ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN MGR PER ALL */
                  $mgr = $this->db->query("SELECT count(recid_karyawan) as mgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'MANAGER'");
                  foreach ($mgr->result() as $mgr) {
                    $mgr = $mgr->mgr;
                  } ?>
                  <td>
                    <center><?php echo $mgr ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN ASMGR PER ALL */
                  $asmgr = $this->db->query("SELECT count(recid_karyawan) as asmgr FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'ASS MANAGER'");
                  foreach ($asmgr->result() as $asmgr) {
                    $asmgr = $asmgr->asmgr;
                  } ?>
                  <td>
                    <center><?php echo $asmgr ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN CO PER ALL */
                  $co = $this->db->query("SELECT count(recid_karyawan) as co FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'CHIEF OFFICER'");
                  foreach ($co->result() as $co) {
                    $co = $co->co;
                  } ?>
                  <td>
                    <center><?php echo $co ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN O PER ALL */
                  $o = $this->db->query("SELECT count(recid_karyawan) as o FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OFFICER'");
                  foreach ($o->result() as $o) {
                    $o = $o->o;
                  } ?>
                  <td>
                    <center><?php echo $o ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN KASI PER ALL */
                  $kasi = $this->db->query("SELECT count(recid_karyawan) as kasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KASI'");
                  foreach ($kasi->result() as $kasi) {
                    $kasi = $kasi->kasi;
                  } ?>
                  <td>
                    <center><?php echo $kasi ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN WAKASI PER BAGIAN */
                  $wakasi = $this->db->query("SELECT count(recid_karyawan) as wakasi FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKASI'");
                  foreach ($wakasi->result() as $wakasi) {
                    $wakasi = $wakasi->wakasi;
                  } ?>
                  <td>
                    <center><?php echo $wakasi ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN KARU PER ALL */
                  $karu = $this->db->query("SELECT count(recid_karyawan) as karu FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'KARU'");
                  foreach ($karu->result() as $karu) {
                    $karu = $karu->karu;
                  } ?>
                  <td>
                    <center><?php echo $karu ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN WAKARU PER ALL */
                  $wakaru = $this->db->query("SELECT count(recid_karyawan) as wakaru FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'WAKARU'");
                  foreach ($wakaru->result() as $wakaru) {
                    $wakaru = $wakaru->wakaru;
                  } ?>
                  <td>
                    <center><?php echo $wakaru ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN OPERATOR PER ALL */
                  $operator = $this->db->query("SELECT count(recid_karyawan) as operator FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and nama_jbtn = 'OPERATOR'");
                  foreach ($operator->result() as $operator) {
                    $operator = $operator->operator;
                  } ?>
                  <td>
                    <center><?php echo $operator ?></center>
                  </td>
                  <td>
                    <center><?php echo $bod + $gm + $mgr + $asmgr + $co + $o + $kasi + $wakasi + $karu + $wakaru + $operator; ?>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN SD PER ALL */
                  $sd = $this->db->query("SELECT count(recid_karyawan) as sd FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SD'");
                  foreach ($sd->result() as $sd) {
                    $sd = $sd->sd;
                  } ?>
                  <td>
                    <center><?php echo $sd ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN SMP PER ALL */
                  $smp = $this->db->query("SELECT count(recid_karyawan) as smp FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMP'");
                  foreach ($smp->result() as $smp) {
                    $smp = $smp->smp;
                  } ?>
                  <td>
                    <center><?php echo $smp ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN SMA PER ALL */
                  $sma = $this->db->query("SELECT count(recid_karyawan) as sma FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'SMA'");
                  foreach ($sma->result() as $sma) {
                    $sma = $sma->sma;
                  } ?>
                  <td>
                    <center><?php echo $sma ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN D3 PER ALL */
                  $d3 = $this->db->query("SELECT count(recid_karyawan) as d3 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'D3'");
                  foreach ($d3->result() as $d3) {
                    $d3 = $d3->d3;
                  } ?>
                  <td>
                    <center><?php echo $d3 ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN S1 PER ALL */
                  $s1 = $this->db->query("SELECT count(recid_karyawan) as s1 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S1'");
                  foreach ($s1->result() as $s1) {
                    $s1 = $s1->s1;
                  } ?>
                  <td>
                    <center><?php echo $s1 ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN S2 PER ALL */
                  $s2 = $this->db->query("SELECT count(recid_karyawan) as s2 FROM karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak' and pendidikan = 'S2'");
                  foreach ($s2->result() as $s2) {
                    $s2 = $s2->s2;
                  } ?>
                  <td>
                    <center><?php echo $s2 ?></center>
                  </td>
                  <td>
                    <center><?php echo $sd + $smp + $sma + $d3 + $s1 + $s2 ?></center>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN Sesuai Umur PER ALL */
                  $umur = $this->db->query("SELECT COUNT(IF(umur BETWEEN 17 and 22,1,NULL)) AS 'u1722', COUNT(IF(umur BETWEEN 23 and 28,1,NULL)) AS 'u2328', COUNT(IF(umur BETWEEN 29 and 34,1,NULL)) AS 'u2934',COUNT(IF(umur BETWEEN 35 and 40,1,NULL)) AS 'u3540', COUNT(IF(umur > 40,1,NULL)) AS 'lebih40' FROM (select TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak') as dummy_table")->result();
                  foreach ($umur as $umur) {
                    $u1722 = $umur->u1722;
                    $u2328 = $umur->u2328;
                    $u2934 = $umur->u2934;
                    $u3540 = $umur->u3540;
                    $lebih40 = $umur->lebih40;
                    $juml6 = $u1722 + $u2328 + $u2934 + $u3540 + $lebih40;
                  }
                  ?>
                  <td>
                    <center><?php echo $u1722; ?>
                  </td>
                  <td>
                    <center><?php echo $u2328; ?>
                  </td>
                  <td>
                    <center><?php echo $u2934; ?>
                  </td>
                  <td>
                    <center><?php echo $u3540; ?>
                  </td>
                  <td>
                    <center><?php echo $lebih40; ?>
                  </td>
                  <td>
                    <center><?php echo $juml6; ?>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN Sesuai JENIS KELAMIN PER ALL */
                  $gender = $this->db->query("SELECT COUNT(IF(jenkel = 'Laki - laki',1,NULL)) AS 'pria', COUNT(IF(jenkel = 'Perempuan',1,NULL)) AS 'Perempuan' FROM (select jenkel from karyawan k join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak') as dummy_table")->result();
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
                    <center><?php echo $Perempuan + $pria; ?>
                  </td>
                  <?php
                  /* JUMLAH KARYAWAN Sesuai Masa Kerja PER ALL */
                  $masker = $this->db->query("SELECT COUNT(IF(usia < 3,1,NULL)) AS 'kurang3', COUNT(IF(usia BETWEEN 3 and 10,1,NULL)) AS 'u10', COUNT(IF(usia > 10,1,NULL)) AS 'lebih10' FROM (select nik, nama_karyawan, tgl_m_kerja, TIMESTAMPDIFF(YEAR, tgl_m_kerja, CURDATE()) AS usia from karyawan k  join bagian b on k.recid_bag = b.recid_bag join struktur s on s.recid_struktur = b.recid_struktur join jabatan j on k.recid_jbtn = j.recid_jbtn where sts_aktif = 'Aktif' and spm = 'Tidak' and tc = '0'and cci = 'Tidak') as dummy_table")->result();
                  foreach ($masker as $usia) {
                    $u3 = $usia->kurang3;
                    $u10 = $usia->u10;
                    $u11 = $usia->lebih10;
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
                    <center><?php echo $u11 + $u10 + $u3; ?>
                  </td>
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
    Copyright © <a href="https://sittv2.trimas-sgi.com/">Sistem HRIS Trimas</a> <?php echo date('Y'); ?>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content
</div>
</div>
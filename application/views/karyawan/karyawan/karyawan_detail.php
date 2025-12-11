<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<style>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 20000;
}
.toast {
    min-width: 250px;
    padding: 12px 18px;
    margin-bottom: 10px;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(50px);
    animation: slideIn 0.4s forwards;
}
.toast-success { background: #4caf50; }
.toast-error   { background: #f44336; }
.toast-info    { background: #2196f3; }
.toast-warning { background: #ff9800; }

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>


    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Detail Data</h2>

          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab1" class="nav nav-tabs bar_tabs right" role="tablist">
              <li role="presentation" class="active"><a href="#tab_content11" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Personal Data</a>
              </li>
              <?php if ($role != '4' and $role != '23') { ?>
                <li role="presentation" class=""><a href="#tab_content22" role="tab" id="profile-tabb" data-toggle="tab" aria-controls="profile" aria-expanded="false">Keluarga</a>
                </li>
              <?php } ?>
              <li role="presentation" class=""><a href="#tab_content33" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Karir</a>
              </li>
              <?php if ($role != '27' and $role != '28' and $role != '4') { ?>
                <li role="presentation" class=""><a href="#tab_content44" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Training</a>
                </li>
              <?php } ?>
              <?php if ($role != '27' and $role != '28') { ?>
                <li role="presentation" class=""><a href="#tab_content55" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Sanksi</a>
                </li>
              <?php } ?>
              <li role="presentation" class=""><a href="#tab_content66" role="tab" id="profile-tabb4" data-toggle="tab" aria-controls="profile" aria-expanded="false">Kontrak</a>
              </li>
            </ul>
            <div id="myTabContent2" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content11" aria-labelledby="home-tab">
                <table class="table table-bordered" id="tr_hr">
                  <?php foreach ($karyawan as $data) {
                    # code...
                  } ?>
                  <?php if ($role == '1' or $role == '5') {
                    if (isset($data->spm) && $data->spm == 'Ya') { ?>
                      <tr>
                        <td rowspan="56" width="30%">
                        <?php } else { ?>
                      <tr>
                        <td rowspan="55" width="30%">
                        <?php  } ?>
                        <?php } else {
                        if (isset($data->spm) && $data->spm == 'Ya') { ?>
                      <tr>
                        <td rowspan="45" width="30%">
                        <?php } else { ?>
                      <tr>
                        <td rowspan="44" width="30%">
                        <?php  } ?>

                      <?php } ?>
                      <div class="">
                        <div class="image view view-first">
                          <?php
                          if ($data->foto == '') { ?>
                            <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/user.png" alt="image" />
                          <?php  } else { ?>
                            <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/foto/<?php echo $data->foto ?>" alt="image" />
                          <?php } ?>
                        </div>
                        <div class="caption">
                          <?php
                          if ($data->foto == '') {
                            echo "<center>-</center>";
                          } else { ?>
                            <center>
                              <p><a href="<?php echo base_url() ?>images/foto/<?php echo $data->foto ?>" target="__blank"><?php echo $data->foto ?></a></p>
                            </center>
                          <?php } ?>
                        </div>
                      </div>
              </div>
              </td>

              <td colspan="2" bgcolor="#c0daf5"><a href="<?php echo base_url() ?>Karyawan/export/<?php echo $data->recid_karyawan ?>">
                  <?php echo "<b>$data->sts_aktif (";
                  if (isset($data->spm) && $data->spm == "Ya") echo "SPM - ";
                  echo $data->sts_jabatan;
                  echo ") - $data->nik</b>"; ?>
                  <?php if ($role == '1' or $role == '5') { ?>
                    <a href="<?php echo base_url() ?>Karyawan/download/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Pdf) </a> <?php } else { ?>
                    <a href="<?php echo base_url() ?>Karyawan/download2/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Pdf) </a>
                  <?php } ?>
                  <a href="<?php echo base_url() ?>Down_ms/download_resume_ms/<?php echo $data->recid_karyawan ?>">(<span><i class="fa fa-download"></i></span> Download Word) </a>
              </td>
              </tr>
              <tr>
                <td>Nama Karyawan</td>
                <td><?php echo $data->nama_karyawan ?></td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td><?php echo isset($data->tmp_lahir) ? $data->tmp_lahir . ", " : "-, "; ?>
                  <?php 
                  if (isset($data->tgl_lahir) && $data->tgl_lahir) {
                    echo $newDate = date("d M Y", strtotime($data->tgl_lahir));
                  } else {
                    echo "-";
                  }
                  $umur_thn = "";
                  foreach ($usia as $umur) {
                    $umur_thn = " ($umur->umur thn)";
                  }
                  echo $umur_thn; ?></td>
              </tr>
              <tr>
                <td>jenis Kelamin</td>
                <td><?php echo $data->jenkel ?></td>
              </tr>
              <tr>
                <td>Golongan Darah</td>
                <td><?php echo $data->gol_darah ?></td>
              </tr>
              <tr>
                <td>Agama</td>
                <td><?php echo $data->agama ?></td>
              </tr>
              <tr>
                <td>Status Perkawinan</td>
                <td><?php echo $data->sts_nikah ?></td>
              </tr>
              <tr>
                <td>Tanggungan</td>
                <td><?php echo $data->sts_penunjang ?>
                  <?php if ($data->sts_penunjang == 'TK') {
                    echo " (Tidak Kawin)";
                  } else if ($data->sts_penunjang == 'K0') {
                    echo "(Kawin, Anak 0)";
                  } else if ($data->sts_penunjang == 'K1') {
                    echo "(Kawin, Anak 1)";
                  } else if ($data->sts_penunjang == 'K2') {
                    echo "(Kawin, Anak 2)";
                  } else if ($data->sts_penunjang == 'TK1') {
                    echo "(Tidak Kawin, Anak 1)";
                  } else if ($data->sts_penunjang == 'TK2') {
                    echo "(Tidak Kawin, Anak 2)";
                  } else if ($data->sts_penunjang == 'TK3') {
                    echo "(Tidak Kawin, Anak 3)";
                  } else {
                    echo "(Kawin, Anak 3)";
                  } ?>
                </td>
              </tr>
              <tr>
                <td>Keluarga</td>
                <td>
                  <?php
                  $pasangan = $this->m_hris->jml_psg2($data->recid_karyawan);
                  $jml_pasang = $pasangan->num_rows();
                  foreach ($pasangan->result() as $ps) {
                    $sts_tunjangan = $ps->sts_tunjangan;
                    if ($sts_tunjangan == "Yes") {
                      $sts_tunjangan = "Ditanggung";
                    } else {
                      $sts_tunjangan = "Tidak Ditanggung";
                    }
                  }
                  $anak = $this->m_hris->jml_anak2($data->recid_karyawan);
                  foreach ($anak as $pa) {
                    $jml_anak = $pa->anak;
                  }
                  $anak_t = $this->m_hris->jml_anak($data->recid_karyawan);
                  foreach ($anak_t as $pat) {
                    $jml_anak_t = $pat->anak;
                  }
                  $tdk_tanggung = $jml_anak - $jml_anak_t;

                  if ($data->sts_nikah == "Kawin") {
                    if ($data->jenkel == "Laki - laki") {
                      $gpasangan = "Istri";
                    } else {
                      $gpasangan = "Suami";
                    }
                  } else {
                    $gpasangan = "Tidak Ada";
                  }


                  if ($jml_anak == 0) {
                    $jml_anak = "Tida Ada";
                  }

                  echo "Pasangan : " . $gpasangan . "<br>" . "Anak : " . $jml_anak . " Anak";
                  // echo  $jml_pasang." Pasangan (".$sts_tunjangan.")  <br>".$jml_anak ." Anak (".$jml_anak_t. "  Ditanggung ,". $tdk_tanggung." Tidak Ditanggung)";
                  ?>
                </td>
              </tr>
              <tr>
                <td>No Ktp</td>
                <td><?php echo isset($data->no_ktp) ? $data->no_ktp : '-' ?></td>
              </tr>
              <tr>
                <td>Scan KTP</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_ktp == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/ktp/<?php echo $data->scan_ktp ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_ktp == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/ktp/<?php echo $data->scan_ktp ?>" target="__blank"><?php echo $data->scan_ktp ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>No Kartu Keluarga</td>
                <td><?php echo isset($data->no_kk) ? $data->no_kk : '-' ?></td>
              </tr>
              <tr>
                <td>Scan Kartu Keluarga</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_kk == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/kk/<?php echo $data->scan_kk ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_kk == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/kk/<?php echo $data->scan_kk ?>" target="__blank"><?php echo $data->scan_kk ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>No NPWP</td>
                <td><?php echo isset($data->no_npwp) ? $data->no_npwp : '-' ?></td>
              </tr>
              <tr>
                <td>Scan NPWP</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_npwp == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/npwp/<?php echo $data->scan_npwp ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_npwp == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/npwp/<?php echo $data->scan_npwp ?>" target="__blank"><?php echo $data->scan_npwp ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Nomor Jamsostek</td>
                <td>
                  <?php
                  if (!isset($data->no_jamsos) || $data->no_jamsos == '') {
                    echo "-";
                  } else {
                    echo $data->no_jamsos;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Nomor BPJS Kesehatan</td>
                <td>
                  <?php
                  if (!isset($data->no_bpjs_kes) || $data->no_bpjs_kes == '') {
                    echo "-";
                  } else {
                    echo $data->no_bpjs_kes;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Scan BPJS Kesehatan</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_bpjs_kes == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_bpjs_kes == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes ?>" target="__blank"><?php echo $data->scan_bpjs_kes ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Nomor BPJS Tenaga Kerja</td>
                <td>
                  <?php
                  if (!isset($data->no_bpjs_tk) || $data->no_bpjs_tk == '') {
                    echo "-";
                  } else {
                    echo $data->no_bpjs_tk;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Scan BPJS Tenaga Kerja</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_bpjs_tk == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_bpjs_tk == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk ?>" target="__blank"><?php echo $data->scan_bpjs_tk ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Nomor AIA</td>
                <td><?php echo isset($data->no_aia) ? $data->no_aia : '-' ?></td>
              </tr>
              <tr>
                <td>Scan AIA</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if ($data->scan_aia == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/aia/<?php echo $data->scan_aia ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if ($data->scan_aia == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/aia/<?php echo $data->scan_aia ?>" target="__blank"><?php echo $data->scan_aia ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Asuransi Kesehatan Lain</td>
                <td><?php echo isset($data->no_askes) ? $data->no_askes : '-' ?></td>
              </tr>
              <tr>
                <td>Scan Asuransi Kesehatan Lain</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if (!isset($data->scan_askes) || $data->scan_askes == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/askes/<?php echo $data->scan_askes ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if (!isset($data->scan_askes) || $data->scan_askes == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/askes/<?php echo $data->scan_askes ?>" target="__blank"><?php echo $data->scan_askes ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Pendidikan</td>
                <td><?php echo (isset($data->pendidikan) ? $data->pendidikan : '-') . ' ' . (isset($data->jurusan) ? $data->jurusan : '-') ?></td>
              </tr>
              <tr>
                <td>Tahun Lulus</td>
                <td><?php echo isset($data->thn_lulus) ? $data->thn_lulus : '-' ?></td>
              </tr>
              <tr>
                <td>Scan Ijazah</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if (!isset($data->scan_ijazah) || $data->scan_ijazah == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/ijazah/<?php echo $data->scan_ijazah ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if (!isset($data->scan_ijazah) || $data->scan_ijazah == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/ijazah/<?php echo $data->scan_ijazah ?>" target="__blank"><?php echo $data->scan_ijazah ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Alamat KTP</td>
                <td><?php echo isset($data->alamat_ktp) ? $data->alamat_ktp : '-' ?></td>
              </tr>
              <tr>
                <td>Alamat Sekarang</td>
                <td><?php echo isset($data->alamat_skrg) ? $data->alamat_skrg : '-' ?></td>
              </tr>
              <tr>
                <td>Telp / HP</td>
                <td><?php echo isset($data->telp1) ? $data->telp1 : '-' ?></td>
              </tr>
              <tr>
                <td>IMEI 1</td>
                <td><?php echo isset($data->imei1) ? $data->imei1 : '-' ?></td>
              </tr>
              <tr>
                <td>Telp Alternatif (Keluarga)</td>
                <td>
                  <?php
                  if (!isset($data->telp2) || $data->telp2 == '') {
                    echo "-";
                  } else {
                    echo $data->telp2;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>IMEI 2</td>
                <td>
                  <?php
                  if (!isset($data->imei2) || $data->imei2 == '') {
                    echo "-";
                  } else {
                    echo $data->imei2;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Email</td>
                <td><?php echo isset($data->email) ? $data->email : '-' ?> / <?php echo isset($data->email_cint) ? $data->email_cint : '-' ?> </td>
              <tr>
                <td>Hobi</td>
                <td><?php
                    if (!isset($data->hobi) || $data->hobi == '') {
                      echo "-";
                    } else {
                      echo $data->hobi;
                    }
                    ?></td>
              </tr>
              <tr>
                <td>Tanggal Masuk Trisula</td>
                <td>
                  <?php
                  if (!isset($data->tgl_trisula) || $data->tgl_trisula == null || $data->tgl_trisula == "0000-00-00") {
                    echo "-";
                  } else {
                    echo $newDate = date("d M Y", strtotime($data->tgl_trisula));
                    $diff  = date_diff(date_create($data->tgl_trisula), date_create());
                    echo $diff->format(' ( %Y tahun %m bulan %d hari )');
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Tanggal Mulai Kerja</td>
                <td>
                  <?php
                  $mulai_kerja = isset($data->tgl_trisula) ? $data->tgl_trisula : null;
                  if (!isset($data->tgl_m_kerja) || $data->tgl_m_kerja == null || $data->tgl_m_kerja == "0000-00-00") {
                    echo "-";
                  } else {
                    echo $newDate = date("d M Y", strtotime($data->tgl_m_kerja));
                    $diff  = date_diff(date_create($data->tgl_m_kerja), date_create());
                    $diff2  = date_diff(date_create(isset($data->tgl_trisula) ? $data->tgl_trisula : date('Y-m-d')), date_create());

                    $masker_tahun = $diff2->format('%y');
                    // echo $masker_tahun;
                    // $diff->format(' ( %Y tahun %m bulan %d hari )');
                    if ($masker_tahun >= 0 and $masker_tahun < 1) {
                      $kat_masker = 'MK 0';
                    } else if ($masker_tahun >= 1 and $masker_tahun <= 5) {
                      $kat_masker = 'MK 1';
                    } else if ($masker_tahun > 5 and $masker_tahun <= 10) {
                      $kat_masker = 'MK 6';
                    } else if ($masker_tahun >= 11 and $masker_tahun <= 15) {
                      $kat_masker = 'MK 11';
                    } else if ($masker_tahun >= 16 and $masker_tahun <= 20) {
                      $kat_masker = 'MK 16';
                    } else {
                      $kat_masker = 'MK 21';
                    }
                    echo $diff->format(' ( %Y tahun %m bulan %d hari )');
                    echo " - ( " . $kat_masker . " )";
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>Tanggal Akhir Kerja</td>
                <td>
                  <?php
                  if (!isset($data->tgl_a_kerja) || $data->tgl_a_kerja == null || $data->tgl_a_kerja == "0000-00-00" || $data->tgl_a_kerja == "9999-12-31") {
                    echo "-";
                  } else {
                    echo $newDate = date("d M Y", strtotime($data->tgl_a_kerja));
                  }
                  ?>
                </td>
              </tr>
              <?php
              if (isset($data->spm) && $data->spm == 'Ya') { ?>
                <tr>
                  <td>Penempatan</td>
                  <td><?php echo (isset($data->tmp_toko) ? $data->tmp_toko : '-') . ' ( ' . (isset($data->tmp_kota) ? $data->tmp_kota : '-') . ' )'; ?></td>
                </tr>
              <?php } ?>
              <tr>
                <td>SIM 1</td>
                <td><?php echo isset($data->sim1) ? $data->sim1 : '-' ?></td>
              </tr>
              <tr>
                <td>Scan SIM 1</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if (!isset($data->scan_sim1) || $data->scan_sim1 == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim1 ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if (!isset($data->scan_sim1) || $data->scan_sim1 == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim1 ?>" target="__blank"><?php echo $data->scan_sim1 ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>

              <tr>
                <td>SIM 2</td>
                <td><?php echo isset($data->sim1) ? $data->sim1 : '-' ?></td>
              </tr>
              <tr>
                <td>Scan SIM 2</td>
                <td>
                  <div class="col-md-8">
                    <div class="thumbnail">
                      <div class="image view view-first">
                        <?php if (!isset($data->scan_sim2) || $data->scan_sim2 == '') { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/inbox.jpg" alt="image" />
                        <?php } else { ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim2 ?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                        <?php
                        if (!isset($data->scan_sim2) || $data->scan_sim2 == '') {
                          echo "<center>-</center>";
                        } else { ?>
                          <center>
                            <p><a href="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim2 ?>" target="__blank"><?php echo $data->scan_sim2 ?></p>
                          </center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Profile DISC</td>
                <td><?php echo isset($data->profile_disc) ? $data->profile_disc : '-' ?></td>
              </tr>
              <tr>
                <td>Pattern Type</td>
                <td><?php echo isset($data->pattern_type) ? $data->pattern_type : '-' ?></td>
              </tr>
              <tr>
                <td>Profile Personality</td>
                <td> <?php echo isset($data->profile_type) ? $data->profile_type : '-' ?></td>
              </tr>


              <?php if ($role == '2' or $role == '5' or $role == '3') { ?>
                <tr>
                  <td colspan="2" bgcolor="#c0daf5"><b><?php echo "Data Upah"; ?></b></td>
                </tr>
                <tr>
                  <td>LSPMI</td>
                  <td><?php echo isset($data->lspmi) ? $data->lspmi : '-' ?></td>
                </tr>
                <tr>
                  <td>Tunjangan Pensiun</td>
                  <td><?php echo isset($data->pensiun) ? $data->pensiun : '-' ?></td>
                </tr>
                <tr>
                  <td>Gaji Pokok</td>
                  <?php
                  if (isset($data->tingkatan) && $data->tingkatan == 1) {
                    if ($masker_tahun < 1) {
                      $uph_pokok = isset($data->gapok) ? $data->gapok : 0;
                    } else if ($masker_tahun >= 1 and $masker_tahun < 5) {
                      $uph_pokok = (isset($data->gapok) ? $data->gapok : 0) + 10000;
                    } else if ($masker_tahun >= 5 and $masker_tahun < 10) {
                      $uph_pokok = (isset($data->gapok) ? $data->gapok : 0) + 20000;
                    } else if ($masker_tahun >= 10 and $masker_tahun < 15) {
                      $uph_pokok = (isset($data->gapok) ? $data->gapok : 0) + 30000;
                    } else if ($masker_tahun >= 15 and $masker_tahun < 20) {
                      $uph_pokok = (isset($data->gapok) ? $data->gapok : 0) + 40000;
                    } else {
                      $uph_pokok = (isset($data->gapok) ? $data->gapok : 0) + 50000;
                    }
                  } else {
                    $uph_pokok = isset($data->gapok) ? $data->gapok : 0;
                  }
                  ?>
                  <td>Rp. <?php echo isset($uph_pokok) ? number_format($uph_pokok) : '0' ?></td>
                </tr>
                <tr>
                  <td>Tunjangan Jabatan</td>
                  <td>Rp. <?php echo isset($data->t_jabatan) ? number_format($data->t_jabatan) : '0' ?></td>
                </tr>
                <tr>
                  <td>Tunjangan Masa Kerja</td>
                  <td>Rp. <?php
                          if (isset($data->tgl_m_kerja) && $data->tgl_m_kerja) {
                            $diff  = date_diff(date_create($data->tgl_m_kerja), date_create());
                            $masker_tahun = $diff->format('%y');
                            $t_masker = $masker_tahun * $uph_masker;
                            echo number_format($t_masker);
                          } else {
                            echo number_format(0);
                          }
                          ?></td>
                </tr>
                <tr>
                  <td>Tunjangan Jenis Pekerjaan</td>
                  <td>Rp. <?php echo isset($data->t_jen_pek) ? number_format($data->t_jen_pek) : '0' ?></td>
                </tr>
                <tr>
                  <td>Jemputan</td>
                  <td><?php echo isset($data->ljemputan) ? $data->ljemputan : '-' ?></td>
                </tr>
                <tr>
                  <td>Akun Bank</td>
                  <td><?php echo isset($data->acc_bank) ? $data->acc_bank : '-' ?></td>
                </tr>
                <tr>
                  <td>Nama Bank</td>
                  <td><?php echo isset($data->nama_bank) ? $data->nama_bank : '-' ?></td>
                </tr>
              <?php } ?>
              </table>
            </div>
            <!-- TUNJANGAN -->
            <?php if ($role != '3' and $role != '4' and $role != '23') { ?>
              <div role="tabpanel" class="tab-pane fade" id="tab_content22" aria-labelledby="profile-tab">
                <a href="<?php echo base_url() ?>Down_ms/download_tanggungan_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download All) </a>
                <table class="table table-bordered">
                  <thead>
                    <th>Nama Tanggungan</th>
                    <th>Hubungan Keluarga</th>
                    <th>Status Tanggungan</th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </thead>
                  <?php $no = 1;
                  foreach ($tunjangan as $data) { ?>
                    <tbody>
                      <tr>
                        <td><?php echo  $data->nama_tunjangan ?></td>
                        <td><?php echo  $data->hub_keluarga ?></td>
                        <td><?php if ($data->sts_tunjangan == "Yes") {
                              echo "Ditanggung";
                            } else {
                              echo "Tidak Ditanggung";
                            }  ?></td>
                        <td>
                          <center><?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                              <a href="<?php echo base_url() ?>Karyawan/tunjangan_update/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-info btn-xs"><i class="fa fa-edit"></i></button></a>
                              <a href="<?php echo base_url() ?>Karyawan/tunjangan_detail/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-success btn-xs"><i class="fa fa-search-plus"></i></button></a>
                              <a href="<?php echo base_url() ?>Down_ms/download_detail_tanggungan_ms/<?php echo $data->recid_tunjangan ?>" target="__blank"><button class="btn btn-primary btn-xs"><i class="fa fa-download"></i></button></a>
                              <a href="<?php echo base_url() ?>Karyawan/tunjangan_delete/<?php echo $data->recid_tunjangan ?>" target="__blank"><button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></a>
                            <?php } ?>
                          </center>
                        </td>
                      </tr>
                    </tbody>
                  <?php } ?>
                </table>
              </div>
              <!--/ TUNJANGAN -->
            <?php } ?>
            <!-- KARIR -->
            <div role="tabpanel" class="tab-pane fade" id="tab_content33" aria-labelledby="profile-tab">
              <div class="x_content">
                <ul class="list-unstyled timeline">
                  <a href="<?php echo base_url() ?>Down_ms/download_karir_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Karir)</a><br>

                  <?php foreach ($karir as $data) { ?>
                    <li>
                      <div class="block">
                        <div class="tags">
                          <a href="#" class="tag">
                            <span><?php echo $data->kategori ?></span>
                          </a>
                        </div>
                        <div class="block_content">
                          <h2 class="title">
                            <p>
                              <?php if ($data->kategori == 'Akhir') {
                                if (isset($data->tgl_m_karir) && $data->tgl_m_karir) {
                                  echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                                  $flag_masker = $newDate;
                                } else {
                                  echo "-";
                                  $flag_masker = date('Y-m-d');
                                }
                              } else { ?>
                                <?php 
                                if (isset($data->tgl_m_karir) && $data->tgl_m_karir) {
                                  echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                                } else {
                                  echo "-";
                                }
                                ?> s/d
                                <?php if ($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00') {
                                  echo "Sekarang";
                                  $flag_masker = date('Y-m-d');
                                } else {
                                  if (isset($data->tgl_a_karir) && $data->tgl_a_karir) {
                                    echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));
                                    $flag_masker = $newDate;
                                  } else {
                                    echo "-";
                                    $flag_masker = date('Y-m-d');
                                  }
                                } ?> |
                              <?php } ?>
                              <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                                <?php if ($data->no_perjanjian == '') { ?>
                                  <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                                  <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><i class="fa fa-trash"></i></a>
                                <?php  } else { ?>
                                  <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>
                                  <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>/<?php echo $data->recid_karyawan ?>"><i class="fa fa-trash"></i></a>
                                <?php } ?>
                                <a class="btn btn-primary btn-xs" href="<?php echo base_url() ?>index.php/Karir/set_current/<?php echo $data->recid_karir ?>">Set as Current</a>
                              <?php } ?>
                            </p>
                          </h2>
                          <div class="byline">
                            <?php if ($data->no_perjanjian == '') {
                              echo "Tidak Ada no SK";
                            } else {
                              if ($data->scan_perjanjian != "") { ?>
                                <a href="<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php echo $data->no_perjanjian; ?>
                                </a>
                              <?php } else {
                                echo $data->no_perjanjian;
                              } ?>
                            <?php } ?>

                            <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                              | <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                            <?php } ?>
                          </div>
                          <p class="excerpt">
                          <table class="table">
                            <tr>
                              <td>Jenis Karir</td>
                              <td>:</td>
                              <td><?php echo isset($data->kategori) ? $data->kategori : '-' ?></td>
                            </tr>
                            <tr>
                              <td>Bagian</td>
                              <td>:</td>
                              <td><?php echo isset($data->indeks_hr) ? $data->indeks_hr : '-' ?></td>
                            </tr>
                            <tr>
                              <td>Sub Bagian</td>
                              <td>:</td>
                              <td><?php echo isset($data->sub_bag) ? $data->sub_bag : '-' ?></td>
                            </tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><?php echo  strtoupper($data->indeks_jabatan) ?>
                            </td>
                            </tr>
                            <tr>
                              <td>Golongan</td>
                              <td>:</td>
                              <?php
                              if (isset($mulai_kerja) && $mulai_kerja) {
                                $newDate = date("d M Y", strtotime($mulai_kerja));
                              } else {
                                $newDate = date('Y-m-d');
                              }
                              // $flag_masker = date("Y m d", strtotime($flag_masker));
                              if (isset($newDate) && isset($flag_masker)) {
                                $diff  = date_diff(date_create($newDate), date_create($flag_masker));
                                $masker_tahun = $diff->format('%y');
                              } else {
                                $masker_tahun = 0;
                              }
                              // echo $masker_tahun;
                              // $diff->format(' ( %Y tahun %m bulan %d hari )');
                              if ($masker_tahun >= 0 and $masker_tahun < 1) {
                                $kat_masker = 'MK 0';
                              } else if ($masker_tahun >= 1 and $masker_tahun <= 5) {
                                $kat_masker = 'MK 1';
                              } else if ($masker_tahun > 5 and $masker_tahun <= 10) {
                                $kat_masker = 'MK 6';
                              } else if ($masker_tahun >= 11 and $masker_tahun <= 15) {
                                $kat_masker = 'MK 11';
                              } else if ($masker_tahun >= 16 and $masker_tahun <= 20) {
                                $kat_masker = 'MK 16';
                              } else {
                                $kat_masker = 'MK 21';
                              }
                              ?>
                              <td><?php echo isset($data->nama_golongan) ? $data->nama_golongan : '-' ?> (<?php echo isset($kat_masker) ? $kat_masker : '-' ?>)</td>
                            </tr>
                            <tr>
                              <td>Penempatan</td>
                              <td>:</td>
                              <td><?php echo isset($data->penempatan) ? $data->penempatan : '-' ?></td>
                            </tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td><?php echo isset($data->note) ? $data->note : '-' ?></td>
                            </tr>
                          </table>
                          </p>
                        </div>
                      </div>
                    </li>
                  <?php } ?>
                  <?php foreach ($karyawan as $data2) {
                    $aktif = $data2->sts_aktif;
                  } ?>
                </ul>
              </div>
            </div>
            <!-- KARIR -->
            <!-- TRAINING -->
            <?php if ($role != '27' and $role != '28' and $role != '4') { ?>
              <div role="tabpanel" class="tab-pane fade" id="tab_content44" aria-labelledby="profile-tab">
                <div class="x_content">
                  <ul class="list-unstyled timeline">
                    <a href="<?php echo base_url() ?>Down_ms/download_histori_training_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Training)</a><br>
                    <?php foreach ($training as $data) { ?>
                      <li>
                        <div class="block">
                          <div class="tags">
                            <a href="#" class="tag">
                              <span><?php echo $data->kategori ?></span>
                            </a>
                          </div>
                          <div class="block_content">
                            <h2 class="title">
                              <p>
                              <?php 
                              if (isset($data->tgl_m_karir) && $data->tgl_m_karir) {
                                echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                              } else {
                                echo "-";
                              }
                              ?> s/d
                                <?php if ($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00') {
                                  echo "Sekarang";
                                } else {
                                  if (isset($data->tgl_a_karir) && $data->tgl_a_karir) {
                                    echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));
                                  } else {
                                    echo "-";
                                  }
                                } ?> |
                                <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                                  <a href="<?php echo base_url() ?>index.php/Training/train_acc_update/<?php echo $data->recid_training ?>"><i class="fa fa-edit"></i></a>
                                <?php } ?>
                              </p>
                            </h2>
                            <div class="byline">
                              <?php if ($data->sertifikat == '') {
                                echo "Tidak Ada Sertifikat";
                              } else { ?><a href="<?php echo base_url() ?>images/training/<?php echo $data->sertifikat; ?>" target="__blank"><?php echo $data->sertifikat;
                                                                                                                                            } ?></a>
                            </div>
                            <p class="excerpt">
                            <table class="table">
                              <tr>
                                <td>Jenis Karir</td>
                                <td>:</td>
                                <td><?php echo isset($data->kategori) ? $data->kategori : '-' ?></td>
                              </tr>
                              <tr>
                                <td>Topik Training</td>
                                <td>:</td>
                                <td><?php echo isset($data->judul_training) ? ucwords($data->judul_training) : '-' ?></td>
                              </tr>
                              <tr>
                                <td>Tempat</td>
                                <td>:</td>
                                <td><?php echo isset($data->tempat_training) ? ucwords($data->tempat_training) : '-' ?></td>
                              </tr>
                              <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td><?php echo isset($data->note) ? ucwords($data->note) : '-' ?></td>
                              </tr>
                            </table>
                            </p>
                          </div>
                        </div>
                      </li>
                    <?php } ?>
                  </ul>

                </div>
              </div>
              <!--/ TRAINING -->
            <?php } ?>
            <?php if ($role != '27' and $role != '28') { ?>
              <!-- SANKSI -->
              <div role="tabpanel" class="tab-pane fade" id="tab_content55" aria-labelledby="profile-tab">
                <div class="x_content">
                  <ul class="list-unstyled timeline">
                    <a href="<?php echo base_url() ?>Down_ms/download_sanksi_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Sanksi)</a><br>
                    <?php foreach ($sanksi as $data) { ?>
                      <li>
                        <div class="block">
                          <div class="tags">
                            <a href="#" class="tag">
                              <span><?php echo $data->kategori ?></span>
                            </a>
                          </div>
                          <div class="block_content">
                            <h2 class="title">
                              <p>
                              <?php 
                              if (isset($data->tgl_m_karir) && $data->tgl_m_karir) {
                                echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                              } else {
                                echo "-";
                              }
                              ?> s/d
                                <?php if ($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00') {
                                  echo "Sekarang";
                                } else {
                                  if (isset($data->tgl_a_karir) && $data->tgl_a_karir) {
                                    echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));
                                  } else {
                                    echo "-";
                                  }
                                } ?> |
                                <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                                  <?php if ($data->no_perjanjian == '') { ?>
                                    <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                                  <?php  } else { ?>
                                    <a href="<?php echo base_url() ?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>
                                  <?php } ?>
                                <?php } ?>
                              </p>
                            </h2>
                            <div class="byline">
                              <?php if ($data->no_perjanjian == '') {
                                echo "Tidak Ada no SK";
                              } else {
                                if ($data->scan_perjanjian != "") { ?>
                                  <a href="<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php echo $data->no_perjanjian; ?>
                                  </a>
                                <?php } else {
                                  echo $data->no_perjanjian;
                                } ?>
                              <?php } ?>

                              <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                                | <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                              <?php } ?>
                            </div>
                            <p class="excerpt">
                            <table class="table">
                              <tr>
                                <td>Jenis Sanksi</td>
                                <td>:</td>
                                <td><?php echo isset($data->jenis_sanksi) ? $data->jenis_sanksi : '-' ?></td>
                              </tr>
                              <tr>
                                <td>Bagian</td>
                                <td>:</td>
                                <td><?php echo isset($data->indeks_hr) ? $data->indeks_hr : '-' ?></td>
                              </tr>
                              <td>Jabatan</td>
                              <td>:</td>
                              <td><?php echo isset($data->indeks_jabatan) ? strtoupper($data->indeks_jabatan) : '-' ?>
                                <!-- - <?php echo isset($data->sts_jbtn) ? strtoupper($data->sts_jbtn) : '-' ?> --> (<?php echo isset($data->sts_jabatan) ? $data->sts_jabatan : '-' ?>)
                              </td>
                              </tr>
                              <td>Keterangan</td>
                              <td>:</td>
                              <td><?php echo isset($data->note) ? $data->note : '-' ?></td>
                              </tr>
                            </table>
                            </p>
                          </div>
                        </div>
                      </li>
                    <?php } ?>
                  </ul>

                </div>
              </div>
              <!--/ SANKSI -->
            <?php } ?>
          <!-- KONTRAK -->
          <div role="tabpanel" class="tab-pane fade" id="tab_content66" aria-labelledby="profile-tab">
            <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createKontrakModal">
              <i class="fa fa-plus"></i> Tambah Kontrak
            </button>
            <br><br>
            <?php } ?>
            <table class="table table-striped jambo_table bulk_action">
              <thead>
                <tr class="headings">
                  <th class="column-title">No</th>
                  <th class="column-title">Tanggal Mulai</th>
                  <th class="column-title">Tanggal Akhir</th>
                  <th class="column-title">Created At</th>
                  <th class="column-title">Updated At</th>
                  <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                  <th class="column-title">Aksi</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1;
                foreach ($kontrak as $data) { 
                ?>
                <tr class="even pointer">
                  <td><?php echo $no++; ?></td>
                  <td>
                  <?php 
                  if (isset($data->tgl_mulai) && $data->tgl_mulai) {
                    echo date('d M Y', strtotime($data->tgl_mulai));
                  } else {
                    echo "-";
                  }
                  ?>
                  </td>
                  <td>
                  <?php 
                  if (isset($data->tgl_akhir) && $data->tgl_akhir) {
                    echo date('d M Y', strtotime($data->tgl_akhir));
                  } else {
                    echo "-";
                  }
                  ?>
                  </td>
                  <td>
                  <?php 
                  if (isset($data->created_at) && $data->created_at) {
                    echo date('d M Y H:i:s', strtotime($data->created_at));
                  } else {
                    echo "-";
                  }
                  ?>
                  </td>
                  <td>
                  <?php 
                  if (isset($data->updated_at) && $data->updated_at) {
                    echo date('d M Y H:i:s', strtotime($data->updated_at));
                  } else {
                    echo "-";
                  }
                  ?>
                  </td>
                  <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                  <td>
                    <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editKontrakModal" 
                            data-id="<?php echo $data->recid_kontrak; ?>" 
                            data-tgl_mulai="<?php echo $data->tgl_mulai; ?>" 
                            data-tgl_akhir="<?php echo $data->tgl_akhir; ?>">
                      <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" onclick="deleteKontrak(<?php echo $data->recid_kontrak; ?>)">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                  <?php } ?>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <!--/ KONTRAK -->
        </div>
      </div>

    </div>
  </div>
</div>
<div class="clearfix"></div>

          <!-- Create Kontrak Modal -->
          <div class="modal fade" id="createKontrakModal" tabindex="-1" role="dialog" aria-labelledby="createKontrakModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="createKontrakModalLabel">Tambah Kontrak Baru</h4>
                </div>
                <div class="modal-body">
                  <form id="createKontrakForm">
                    <input type="hidden" id="recid_karyawan" value="<?php echo $data->recid_karyawan; ?>">
                    <div class="form-group">
                      <label for="tgl_mulai">Tanggal Mulai:</label>
                      <input type="date" class="form-control" id="tgl_mulai" required>
                    </div>
                    <div class="form-group">
                      <label for="tgl_akhir">Tanggal Akhir:</label>
                      <input type="date" class="form-control" id="tgl_akhir" required>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  <button type="button" class="btn btn-primary" id="saveCreateKontrak">Simpan</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Edit Kontrak Modal -->
          <div class="modal fade" id="editKontrakModal" tabindex="-1" role="dialog" aria-labelledby="editKontrakModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="editKontrakModalLabel">Edit Kontrak</h4>
                </div>
                <div class="modal-body">
                  <form id="editKontrakForm">
                    <input type="hidden" id="edit_recid_kontrak">
                    <div class="form-group">
                      <label for="edit_tgl_mulai">Tanggal Mulai:</label>
                      <input type="date" class="form-control" id="edit_tgl_mulai" required>
                    </div>
                    <div class="form-group">
                      <label for="edit_tgl_akhir">Tanggal Akhir:</label>
                      <input type="date" class="form-control" id="edit_tgl_akhir" required>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  <button type="button" class="btn btn-primary" id="saveEditKontrak">Simpan</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="successModalLabel">Sukses</h4>
                </div>
                <div class="modal-body">
                  <p id="successMessage"></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Delete Confirmation Modal -->
          <div class="modal fade" id="deleteKontrakModal" tabindex="-1">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Konfirmasi</h4>
                </div>
                <div class="modal-body">
                  <p>Yakin ingin menghapus kontrak ini?</p>
                  <input type="hidden" id="delete_recid_kontrak">
                </div>
                <div class="modal-footer">
                  <button class="btn btn-default" data-dismiss="modal">Batal</button>
                  <button class="btn btn-danger" id="confirmDeleteKontrak">Hapus</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Toast Container -->
          <div class="toast-container" id="toastContainer"></div>

          <!-- Toast JavaScript -->
          <script>
          function showToast(message, type = "success") {
              var container = document.getElementById("toastContainer");

              var toast = document.createElement("div");
              toast.className = "toast toast-" + type;
              toast.innerText = message;

              container.appendChild(toast);

              // Hapus otomatis
              setTimeout(() => {
                  toast.style.opacity = "0";
                  toast.style.transform = "translateX(50px)";
                  setTimeout(() => container.removeChild(toast), 300);
              }, 3000);
          }
          </script>

          <!-- JavaScript for Kontrak CRUD Operations -->
          <script>
          $(document).ready(function() {

              // Open Edit Modal
              $('#editKontrakModal').on('show.bs.modal', function (event) {
                  var button = $(event.relatedTarget);
                  $('#edit_recid_kontrak').val(button.data('id'));
                  $('#edit_tgl_mulai').val(button.data('tgl_mulai'));
                  $('#edit_tgl_akhir').val(button.data('tgl_akhir'));
              });

              // CREATE KONTRAK
              $('#saveCreateKontrak').click(function() {
                  var recid_karyawan = $('#recid_karyawan').val();
                  var tgl_mulai = $('#tgl_mulai').val();
                  var tgl_akhir = $('#tgl_akhir').val();

                  if (!tgl_mulai || !tgl_akhir) {
                      showToast('Harap isi semua field', 'error');
                      return;
                  }

                  $.ajax({
                      url: '<?php echo base_url(); ?>Kontrak/create',
                      type: 'POST',
                      data: { recid_karyawan, tgl_mulai, tgl_akhir },
                      success: function() {
                          $('#createKontrakModal').modal('hide');
                          showToast("Kontrak berhasil ditambahkan", "success");
                          setTimeout(() => location.reload(), 1500);
                      },
                      error: function() {
                          showToast("Gagal menambah kontrak", "error");
                      }
                  });
              });

              // UPDATE KONTRAK
              $('#saveEditKontrak').click(function() {
                  var id = $('#edit_recid_kontrak').val();
                  var tgl_mulai = $('#edit_tgl_mulai').val();
                  var tgl_akhir = $('#edit_tgl_akhir').val();

                  if (!tgl_mulai || !tgl_akhir) {
                      showToast('Harap isi semua field', 'error');
                      return;
                  }

                  $.ajax({
                      url: '<?php echo base_url(); ?>Kontrak/update/' + id,
                      type: 'POST',
                      data: { tgl_mulai, tgl_akhir },
                      success: function() {
                          $('#editKontrakModal').modal('hide');
                          showToast("Kontrak berhasil diupdate", "success");
                          setTimeout(() => location.reload(), 1500);
                      },
                      error: function() {
                          showToast("Gagal update kontrak", "error");
                      }
                  });
              });

              // Confirm Delete
              $('#confirmDeleteKontrak').click(function() {
                  var id = $('#delete_recid_kontrak').val();

                  $.ajax({
                      url: '<?php echo base_url(); ?>Kontrak/delete/' + id,
                      type: 'POST',
                      success: function() {
                          $('#deleteKontrakModal').modal('hide');
                          showToast("Kontrak berhasil dihapus", "success");
                          setTimeout(() => location.reload(), 1500);
                      },
                      error: function() {
                          showToast("Gagal hapus kontrak", "error");
                      }
                  });
              });

          });

          // OPEN delete modal
          function deleteKontrak(id) {
              $('#delete_recid_kontrak').val(id);
              $('#deleteKontrakModal').modal('show');
          }
          </script>

        </div>
      </div>

    </div>
  </div>
</div>
<div class="clearfix"></div>

</div>
</div>
<!-- /page content -->
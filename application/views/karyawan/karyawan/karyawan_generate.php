<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Personal Info</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/karyawan_pinsert" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

              <!-- <span class="section">Personal Info</span>-->
              <?php foreach ($pelamar as $key) {
                $nama = $key->nama_pelamar;
                $tgl_lahir = $key->tgl_lahir;
                $ptmp_lahir = $key->ptmp_lahir;
                $pjenkel = $key->pjenkel;
                $pgoldar = $key->pgoldar;
                $pagama = $key->pagama;
                $psts_kawin = $key->psts_kawin;
                $no_ktp = $key->no_ktp;
                $ppendidikan = $key->ppendidikan;
                $pinstansi = $key->pinstansi;
                $pjurusan = $key->pjurusan;
                $pthn_lulus = $key->pthn_lulus;
                $palamat_ktp = $key->palamat_ktp;
                $alamat = $key->alamat;
                $no_telp = $key->no_telp;
                $ptelp_alt = $key->ptelp_alt;
                $phobi = $key->phobi;
                $email = $key->email;
              } ?>


              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_karyawan" placeholder="Nama Lengkap" required="required" type="text" value="<?php echo $nama; ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tmp_lahir" name="tmp_lahir" class="form-control col-md-7 col-xs-12" placeholder="Tempat Lahir" value="<?php echo $ptmp_lahir; ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_lahir" value="<?php echo $tgl_lahir; ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Masuk Trisula <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_trisula" placeholder="thn-bln-tgl" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">Jenis Kelamin <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                  $gender = ["Laki - laki", "Perempuan"];
                  for ($i = 0; $i < count($gender); $i++) {
                    if ($pjenkel == $gender[$i]) { ?>
                      <input type="radio" class="flat" name="jenkel" value="<?php echo $gender[$i] ?>" checked="checked" /> <?php echo $gender[$i] ?>
                    <?php } else { ?>
                      <input type="radio" class="flat" name="jenkel" value="<?php echo $gender[$i] ?>" /> <?php echo $gender[$i] ?>
                  <?php }
                  }
                  ?>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Golongan Darah <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                  $goldar = ["A", "B", "O", "AB", "-"];
                  for ($x = 0; $x < count($goldar); $x++) {
                    if ($pgoldar == $goldar[$x]) { ?>
                      <input type="radio" class="flat" name="gol_darah" id="genderM" value="<?php echo $goldar[$x] ?>" checked="checked" /> <?php echo $goldar[$x] ?>
                    <?php } else { ?>
                      <input type="radio" class="flat" name="gol_darah" id="genderM" value="<?php echo $goldar[$x] ?>" /> <?php echo $goldar[$x] ?>
                  <?php }
                  }
                  ?>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_ktp" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Nomor KTP" value="<?php echo $no_ktp ?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_ktp">Scan KTP<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_ktp" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KK <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_kk" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Nomor KK">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_ktp">Scan KK<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_kk" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor NPWP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="text" type="text" name="no_npwp" class="form-control col-md-7 col-xs-12" placeholder="Nomor NPWP">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_ktp">Scan NPWP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_npwp" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Agama<span style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="agama" class="form-control col-md-7 col-xs-12">
                    <option value="">-- Pilih --</option>
                    <?php
                    $agm = ["Islam", "Kristen", "Hindu", "Budha"];
                    for ($a = 0; $a < count($agm); $a++) {
                      if ($pagama == $agm[$a]) { ?>
                        <option value="<?php echo $agm[$a] ?>" selected="selected"><?php echo $agm[$a] ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $agm[$a] ?>"><?php echo $agm[$a] ?></option>
                    <?php }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Pendidikan<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="pendidikan" class="form-control col-md-7 col-xs-12">
                    <option value="">-- Pilih --</option>
                    <?php
                    $pend = ["SD", "SMP", "SMA", "D3", "S1", "S2"];
                    for ($p = 0; $p < count($pend); $p++) {
                      if ($ppendidikan == $pend[$p]) { ?>
                        <option value="<?php echo $pend[$p] ?>" selected="selected"><?php echo $pend[$p] ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $pend[$p] ?>"><?php echo $pend[$p] ?></option>
                    <?php }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Jurusan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="jurusan" type="text" name="jurusan" class="form-control col-md-7 col-xs-12" placeholder="Jurusan" value="<?php echo $pjurusan ?>">
                </div>
              </div>
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Lulus</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="thn_lulus" type="text" name="thn_lulus" class="form-control col-md-7 col-xs-12" placeholder="Tahun Lulus" value="<?= $pthn_lulus; ?>">
                </div>
              </div>
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Scan Ijazah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="ijazah" type="file" name="scan_ijazah" class="form-control col-md-7 col-xs-12" placeholder="Ijazah">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_jamsos">Nomor Jamsostek
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_jamsos" class="form-control col-md-7 col-xs-12" placeholder="Nomor Jamsostek">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_bkes">Nomor BPJS Kesehatan<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_bpjs_kes" class="form-control col-md-7 col-xs-12" placeholder="Nomor BPJS Kesehatan">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Scan Bpjs Kesehatan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_bpjs_kes" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor BPJS Tenaga Kerja<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_bpjs_tk" class="form-control col-md-7 col-xs-12" placeholder="Nomor BPJS Tenaga Kerja">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan Bpjs Tenaga Kerja <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_bpjs_tk" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor AIA
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_aia" class="form-control col-md-7 col-xs-12" placeholder="Nomor BPJS Tenaga Kerja">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan AIA
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_aia" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor Asuransi Kesehatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_askes" class="form-control col-md-7 col-xs-12" placeholder="Nomor  Asuransi Kesehatan">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan Asuransi Kesehatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_askes" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_nikah">Status Perkawinan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                  $kawin = ["Belum Kawin", "Kawin", "Janda", "Duda"];
                  for ($k = 0; $k < count($kawin); $k++) {
                    if ($psts_kawin == $kawin[$k]) { ?>
                      <input type="radio" class="flat" name="sts_nikah" id="genderM" value="<?php echo $kawin[$k] ?>" checked="checked" /> <?php echo $kawin[$k] ?><br>
                    <?php } else { ?>
                      <input type="radio" class="flat" name="sts_nikah" id="genderM" value="<?php echo $kawin[$k] ?>" /> <?php echo $kawin[$k] ?><br>
                  <?php }
                  }
                  ?>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_nikah">Status Penunjang <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="sts_penunjang" value="TK" checked="" /> Tidak Kawin <br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K0" /> Kawin Anak 0 <br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K1" /> Kawin Anak 1 <br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K2" /> Kawin Anak 2 <br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K3" /> Kawin Anak 3 <br>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="alamat_ktp" class="form-control col-md-7 col-xs-12"><?php echo $key->palamat_ktp; ?></textarea>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Alamat Sekarang <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="alamat_skrg" class="form-control col-md-7 col-xs-12" placeholder="Alamat Sekarang"><?php echo $key->alamat; ?></textarea>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="telp1" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="No Telp Pribadi" type="text" value="<?php echo $no_telp; ?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="imei1">IMEI 1 <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="imei1" class="form-control col-md-7 col-xs-12" placeholder="IMEI 1"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Alternatif Telp (Keluarga)
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="telp2" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="No Telp Alternatif" type="text" value="<?php echo $ptelp_alt; ?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="imei1">IMEI2</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="imei2" class="form-control col-md-7 col-xs-12" placeholder="IMEI 2"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">SIM 1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="sim1" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="SIM 1" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan SIM 1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_sim1" class="form-control col-md-7 col-xs-12">
                </div>
              </div>


              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">SIM 2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="sim1" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="SIM 2" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan SIM 2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_sim2" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hobi">Hobi</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="hobi" class="form-control col-md-7 col-xs-12" placeholder="Hobi"><?php echo $phobi; ?></textarea>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hobi">Email</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Email" value="<?php echo $email ?>"></textarea>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Foto<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="foto" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">SPM <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" name="spm" id="SPMY" value="Ya" /> Ya
                  <input type="radio" name="spm" id="SPMT" value="Tidak" checked="" /> Tidak
                </div>
              </div>
              <div class="item form-group" id="tmp_toko" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Penempatan Toko
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="spm_tmp1" type="text" name="tmp_toko" class="form-control col-md-7 col-xs-12" placeholder="Penempatan Toko">
                </div>
              </div>
              <div class="item form-group" id="tmp_kota" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Penempatan Kota
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="spm_tmp2" type="text" name="tmp_kota" class="form-control col-md-7 col-xs-12" placeholder="Penempatan Kota">
                </div>
              </div>

              <?php if ($role == '1' or $role == '5') { ?>
                <div class="x_title">
                  <h2>Data Pendukung Upah</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="spmi">SPMI
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="lspmi" id="lspmi" value="Ya" /> Ya
                    <input type="radio" class="flat" name="lspmi" id="lspmi" value="Tidak" checked="" /> Tidak
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pensiun">Jaminan Pensiun
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="pensiun" id="pensiun" value="Ya" /> Ya
                    <input type="radio" class="flat" name="pensiun" id="pensiun" value="Tidak" checked="" /> Tidak
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gapok">Gaji Pokok
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="gapok" type="text" name="gapok" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Gaji Pokok">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jabatan">Tunjangan Jabatan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_jabatan" type="text" name="t_jabatan" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jabatan">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_prestasi">Tunjangan Masa Kerja
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_prestasi" type="text" name="t_prestasi" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Masa Kerja">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jenk_pek">Tunjangan Jenis Pekerjaan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_jenk_pek" type="text" name="t_jen_pek" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jenis Pekerjaan">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Jemputan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Ya" checked="" /> Ya
                    <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Tidak" /> Tidak
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Akun Bank
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="acc_bank" name="acc_bank" class="form-control col-md-7 col-xs-12" placeholder="Akun Bank">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Nama Bank
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="nama_bank" name="nama_bank" class="form-control col-md-7 col-xs-12" placeholder="Nama Bank">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Bulanan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="bulanan" id="lspmi" value="Ya" checked="" /> Ya
                    <input type="radio" class="flat" name="bulanan" id="lspmi" value="Tidak" /> Tidak
                  </div>
                </div>
              <?php } ?>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <a href='<?php echo base_url() ?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                  <button id="send" type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
            <!--/ Content Form -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content-->
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Karir</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Karir Info</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/karir_pupdate" novalidate>

              <!-- <span class="section">Personal Info</span>-->
              <?php foreach ($karir as $data) {
              } ?>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type='hidden' class="form-control" name="recid_karir" required="required" value="<?php echo $data->recid_karir ?>" />
                  <select name="nik" class="form-control col-md-7 col-xs-12" onchange="test()" id="pnik">
                    <?php
                    foreach ($karyawan as $option) {
                      if ($data->nik == $option->nik) {
                        echo "<option value='$option->nik' selected='selected'>$option->nama_karyawan - $option->nik</option>";
                      } else {
                        echo "<option value='$option->nik'>$option->nama_karyawan - $option->nik</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">NIK<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" id="nik" name="nik" required="required" type="text" value="<?php echo $data->nik ?>" readonly>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat">Kategori Karir <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control col-md-7 col-xs-12" id="jenis" name="jenis">
                    <?php
                    $sts = ["Awal", "Rotasi", "Mutasi", "Perpanjang", "Penempatan Kerja",  "Akhir", "Training", "Promosi", "Demosi", "Angkat Tetap", "Sanksi"];
                    for ($s = 0; $s < count($sts); $s++) {
                      if ($data->kategori == $sts[$s]) { ?>
                        <option value="<?php echo $sts[$s] ?>" selected="selected"> <?php echo $sts[$s] ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $sts[$s] ?>"> <?php echo $sts[$s] ?></option>
                    <?php }
                    } ?>

                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_sk">No SK <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span>*Perubahan data master SK di menu legal</span>
                  <select name="recid_legal" class="form-control col-md-7 col-xs-12">
                    <?php
                    foreach ($legal as $option) {
                      if ($data->recid_legal == $option->recid_legal) {
                        echo "<option value='$option->recid_legal' selected='selected'>$option->no_perjanjian </option>";
                      } else {
                        echo "<option value='$option->recid_legal'>$option->no_perjanjian</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span>*Perubahan data master SK di menu legal</span>
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_m_karir" required="required" value="<?php echo $data->tgl_m_karir ?>" readonly />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_karir">Tanggal Akhir</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span>*Perubahan data master SK di menu legal</span>
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_a_karir" value="<?php echo $data->tgl_a_karir ?>" readonly />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Bagian<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_bag" class="form-control col-md-7 col-xs-12" required="required">
                    <?php
                    foreach ($bagian as $option) {
                      if ($data->recid_bag == $option->recid_bag) {
                        echo "<option value='$option->recid_bag' selected='selected'>$option->indeks_hr ($option->nama_bag) </option>";
                      } else {
                        echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Sub Bagian<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_subbag" class="form-control col-md-7 col-xs-12" required="required">
                    <?php
                    foreach ($sub_bagian as $option) {
                      if ($data->recid_subbag == $option->recid_subbag) {
                        echo "<option value='$option->recid_subbag' selected='selected'>$option->sub_bag</option>";
                      } else {
                        echo "<option value='$option->recid_subbag'>$option->sub_bag</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="jabatan" class="control-label col-md-3">Jabatan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_jbtn" class="form-control col-md-7 col-xs-12" required="required">
                    <?php
                    foreach ($jabatan as $option) {
                      if ($data->recid_jbtn == $option->recid_jbtn) {
                        echo "<option value='$option->recid_jbtn' selected='selected'>$option->indeks_jabatan </option>";
                      } else {
                        echo "<option value='$option->recid_jbtn'>$option->indeks_jabatan</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Golongan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_golongan" class="form-control col-md-7 col-xs-12" required="required">
                    <?php
                    foreach ($golongan->result() as $option) {
                      if ($option->masa_kerja != NULL) {
                        $note = " - $option->masa_kerja ( $option->note )";
                      } else {
                        $note = "";
                      }
                      if ($data->recid_golongan == $option->recid_golongan) {
                        echo "<option value='$option->recid_golongan' selected='selected'>$option->nama_golongan $note </option>";
                      } else {
                        echo "<option value='$option->recid_golongan'>$option->nama_golongan $note</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Penempatan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="penempatan" class="form-control col-md-7 col-xs-12" required="required">
                    <?php
                    $tempat = ["Industri", "Baros", "Jakarta", "Pavilliun 14", "Kebon Jeruk", "SPM"];
                    for ($i = 0; $i < count($tempat); $i++) {
                      if ($tempat[$i] == $data->penempatan) {
                        echo "<option value='$tempat[$i]' selected='selected'>$tempat[$i]</option>";
                      } else {
                        echo "<option value='$tempat[$i]'>$tempat[$i]</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Status Jabatan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="sts_jbtn" class="form-control col-md-7 col-xs-12">
                    <?php if ($data->sts_jbtn == "Tetap") { ?>
                      <option value="Tetap" selected="selected">Tetap</option>
                      <option value="Kontrak">Kontrak</option>
                    <?php } else { ?>
                      <option value="Tetap">Tetap</option>
                      <option value="Kontrak" selected="selected">Kontrak</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <!-- <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Bulanan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="bulanan" class="form-control col-md-7 col-xs-12">
                    <?php
                    if ($data->bulan == "Ya") { ?>
                      <option value="Ya" selected="selected">Bulanan</option>
                      <option value="Tidak">Harian</option>
                    <?php } else { ?>
                      <option value="Ya">Bulanan</option>
                      <option value="Tidak" selected="selected">Harian</option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div> -->
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                  $sts2 = ["Aktif", "Resign", "PHK", "Pensiun", "Pensiun Dini", "Mutasi"];
                  for ($s2 = 0; $s2 < count($sts2); $s2++) {
                    if ($data->sts_aktif == $sts2[$s2]) { ?>
                      <?php echo $sts2[$s2] ?> : <input type="radio" class="flat" name="sts_aktif" id="genderM" value="<?php echo $sts2[$s2] ?>" checked="checked" required />
                    <?php } else { ?>
                      <?php echo $sts2[$s2] ?> : <input type="radio" class="flat" name="sts_aktif" id="genderM" value="<?php echo $sts2[$s2] ?>" required />
                  <?php }
                  } ?>


                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"><?php echo $data->note ?></textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <button type="button" class="btn btn-primary" onclick="goBack()">Cancel</button>
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
<!-- /page content -->
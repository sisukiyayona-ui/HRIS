<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Edit Data Perizinan & Perjanjian</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/legal_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Data Perizinan & Perjanjian</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/legal_pupdate" novalidate>

              <!-- <span class="section">Personal Info</span>-->
              <?php foreach ($legal as $data) {
              } ?>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Nomor <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" name="recid_legal" placeholder="Nomor Perjanjian" required="required" type="hidden" value="<?php echo $data->recid_legal ?>">
                  <input class="form-control col-md-7 col-xs-12" name="no_perjanjian" placeholder="Nomor Perjanjian" required="required" type="text" value="<?php echo $data->no_perjanjian ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis">Jenis <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php if ($data->jenis_perjanjian == "Karyawan") { ?>
                    Karyawan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderM" value="Karyawan" checked="checked" required />
                    Perizinan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perizinan" />
                    Perjanjian : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perjanjian" />
                    HKI : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="HKI" />
                  <?php } else if ($data->jenis_perjanjian == "Perizinan") { ?>
                    Karyawan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderM" value="Karyawan" />
                    Perizinan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perizinan" checked="checked" required />
                    Perjanjian : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perjanjian" />
                    HKI : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="HKI" />
                  <?php } else if ($data->jenis_perjanjian == "HKI") { ?>
                    Karyawan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderM" value="Karyawan" />
                    Perizinan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perizinan" required />
                    Perjanjian : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perjanjian" />
                    HKI : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="HKI" checked="checked" />
                  <?php } else { ?>
                    Karyawan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderM" value="Karyawan" required />
                    Perizinan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perizinan" />
                    Perjanjian : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perjanjian" checked="checked" required />
                    HKI : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="HKI" />
                  <?php } ?>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="judul_perjanjian" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->judul_perjanjian ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m">Tanggal Mulai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_m_legal" required="required" value="<?php echo $data->tgl_m_legal ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m">Tanggal Selesai
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker4'>
                    <input type='text' class="form-control" name="tgl_a_legal" value="<?php echo $data->tgl_a_legal ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <a href="<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian ?>" target="__blank"><span class="fa fa-download">&nbsp;&nbsp;&nbsp;<?php echo $data->scan_perjanjian ?></span></a><br>
                  <span>*.pdf (Isi Bila Akan Diubah)</span>
                  <input type="hidden" name="scan_perjanjian2" class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_perjanjian ?>">
                  <input type="file" name="scan_perjanjian" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_legal">Status Legal
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="selectpicker form-control  col-md-12 col-xs-12" name="sts_legal">
                    <?php if ($data->sts_legal == 'Open') { ?>
                      <option value="">-- Pilih --</option>
                      <option value="Open" selected='selected'>Open</option>
                      <option value="Process">Process</option>
                      <option value="Closed">Closed</option>
                    <?php } else if ($data->sts_legal == 'Process') { ?>
                      <option value="">-- Pilih --</option>
                      <option value="Open">Open</option>
                      <option value="Process" selected='selected'>Process</option>
                      <option value="Closed">Closed</option>
                    <?php } else { ?>
                      <option value="">-- Pilih --</option>
                      <option value="Open">Open</option>
                      <option value="Process">Process</option>
                      <option value="Closed" selected='selected'>Closed</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Note
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="note" class="form-control col-md-7 col-xs-12"><?php echo $data->note ?></textarea>
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
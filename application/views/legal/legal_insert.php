<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Perizinan & Perjanjian</h3>
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
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/legal_pinsert" novalidate>

              <!-- <span class="section">Personal Info</span>-->

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Nomor <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" name="no_perjanjian" placeholder="Nomor" required="required" type="text">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis">Jenis <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  Karyawan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderM" value="Karyawan" />
                  Perizinan : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perizinan" checked="" required />
                  Perjanjian : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="Perjanjian" />
                  HKI : <input type="radio" class="flat" name="jenis_perjanjian" id="genderF" value="HKI" />
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="judul_perjanjian" required="required" placeholder="Judul" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m">Tanggal Mulai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_m_legal" required="required" placeholder="Tanggal Mulai" />
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
                    <input type='text' class="form-control" name="tgl_a_legal" placeholder="Tanggal Selesai" />
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
                  <span>*.pdf</span>
                  <input type="file" name="scan_perjanjian" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Note
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="note" class="form-control col-md-7 col-xs-12"></textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <a href="<?php echo base_url() ?>Karyawan/legal_view"><button type="button" class="btn btn-primary">Cancel</button></a>
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
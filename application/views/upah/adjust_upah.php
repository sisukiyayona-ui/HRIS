<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Adjustment Upah</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Adjustment Upah</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Upah/upload_adjustment">
              <i class="fa fa-upload"></i> | Upload Data </a>
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Upah/proses_adjust" novalidate>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Periode Awal<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="periode_awal" required="required" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Periode Akhir<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="periode_akhir" required="required" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Untuk Tanggal <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker4'>
                    <input type='text' class="form-control" name="tanggal" required="required" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <!-- <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Kategori Adjustment
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="kategori" class="form-control">
              <?php
              foreach ($kategori->result() as $j) { ?>
                   <option value="<?php echo $j->recid_akatuph ?>"><?php echo $j->kategori ?></option>
                <?php } ?>
            </select>
          </div>
        </div> -->

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Karyawan<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control selectpicker" multiple="multiple" data-actions-box="true" data-live-search="true" id="jenis" name="recid_karyawan[]">
                    <?php
                    foreach ($karyawan as $k) { ?>
                      <option value="<?php echo $k->recid_karyawan ?>"><?php echo $k->nama_karyawan . " - " . $k->nik . "(" . $k->indeks_hr . ")" ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Makan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_makan" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Transport
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_transport" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_lbr1" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_lbr2" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur3
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_lbr3" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Premi1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_premi1" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Premi2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jml_premi2" class="form-control">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea class="form-control" name="keterangan"></textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <a href="<?php echo base_url() ?>Karyawan/karyawan_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
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


<script type="text/javascript">

</script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Report Lembur Karyawan</h3>
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
            <h2>Report Lembur Karyawan</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Lembur/stkl_karyawan" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-1 col-sm-1 col-xs-1"><label>Tanggal Mulai</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <div class='input-group date' id='myDatepicker2'>
                        <input type='text' class="form-control" name="sejak" id="tgl_mulai" value="<?php echo date("Y-m-d") ?>" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                         </span>
                    </div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1"><label>Tanggal Akhir</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                 <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="sampai" id="tgl_akhir" value="<?php echo date("Y-m-d") ?>" />
                    <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                </div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <label>Nama Karyawan</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control selectpicker" name="recid_karyawan"  data-live-search="true">
                    <?php
                      foreach ($karyawan as $k) {?>
                          <option value="<?php echo $k->recid_karyawan ?>"><?php echo $k->nama_karyawan."(".$k->nik.")"; ?></option>
                      <?php }
                    ?>
                  </select>
                </div>
              </div>
              </div>
          </div>



          <div class="col-md-12 col-sm-12 col-xs-12">
            <br><br>
            <div class="col-md-1 col-sm-1 col-xs-1">
              <button type="submit" class="btn btn-primary">Cari Report</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /page content -->


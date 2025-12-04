<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Direktorat</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/struktur_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Direktorat</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <?php
            foreach ($dept as $data) {
              # code...
            }
            ?>
            <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/department_update" novalidate>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Nama Direktorat <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_department" value="<?php echo $data->recid_department ?>">
                  <input id="enama_str" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_department" placeholder="Nama Direktorat" required="required" type="text" value="<?php echo $data->nama_department ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Direktorat Group <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="dept_group" id="pic_dept" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true'>
                    <?php
                    $dp = array("Presdir", "Fron Office", "Middle Office", "Back Office", "Utama");
                    for ($i = 0; $i < count($dp); $i++) {
                      if ($dp[$i] == $data->dept_group) { ?>
                        <option value="<?php echo $dp[$i] ?>" selected="selected"><?php echo $dp[$i] ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $dp[$i] ?>"><?php echo $dp[$i] ?></option>
                      <?php }
                    }?>
                  
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">PIC Direktorat <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="pic_dept" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true'>
                    <option value="">-- Pilih -- </option>
                    <?php
                    foreach ($karyawan as $key) {
                      if ($key->recid_karyawan == $data->pic_dept) { ?>
                        <option value="<?php echo $key->recid_karyawan ?>" selected="selected"><?php echo $key->nama_karyawan . " (" . $key->nik . ")" ?> </option>
                      <?php } else { ?>
                        <option value="<?php echo $key->recid_karyawan ?>"><?php echo $key->nama_karyawan . " (" . $key->nik . ")" ?> </option>
                    <?php }
                    }
                    ?>
                  </select>
                </div>
              </div>

              <!--/ Content Modal -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value='Save changes'>
            </form>
            <!--/ Content Form -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content-->
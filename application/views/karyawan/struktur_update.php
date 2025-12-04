
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Departemen</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/struktur_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Departemen</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <?php
              foreach ($struktur as $data) {
                # code...
              }
             ?>
             <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/struktur_update" novalidate>
                <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Nama Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_struktur" value="<?php echo $data->recid_struktur?>" >
              <input id="enama_str" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_str" placeholder="Nama Departemen" required="required" type="text" value="<?php echo $data->nama_struktur?>">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note' id='enote' placeholder='Keterangan'><?php echo $data->note?></textarea>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">PIC Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="pic_struktur" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
                 <option value="">-- Pilih -- </option>
              <?php 
               foreach ($karyawan as $key ) {
                if($key->recid_karyawan == $data->pic_struktur){ ?>
                  <option value="<?php echo $key->recid_karyawan?>" selected="selected"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
                <?php }else{?>
                  <option value="<?php echo $key->recid_karyawan?>"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
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
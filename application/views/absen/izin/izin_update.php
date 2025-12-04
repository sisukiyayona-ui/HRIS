<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Izin Karyawan</h3>
      </div>
    </div>
            <div class="clearfix"></div>

             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><a href="<?php echo base_url()?>Karyawan/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Absen Karyawan</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
          
              <?php foreach ($izin->result() as $data) {
                # code...
              } ?>
             <!-- <span class="section">Personal Info</span>-->
               <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Absen/izin_update" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div>
                <input type="hidden" id='izin_recid' name="izin_recid" class="form-control" value="<?php echo $data->izin_recid ?>" readonly="readonly">
                <input type="text" id='nama2' class="form-control" value="<?php echo $data->nama_karyawan ?>" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='niks2' name="niks2" class="form-control" value="<?php echo $data->nik ?>" readonly="readonly">
              <input type="hidden" id='recid_karyawan'  name="recid_karyawan" value="<?php echo $data->recid_karyawan ?>" class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='bagian2' class="form-control" readonly="readonly" value="<?php echo $data->indeks_hr ?>">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='jabatan2' class="form-control" value="<?php echo $data->indeks_jabatan ?>" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tanggal2" id="tanggal2" placeholder="tanggal" required="required" value="<?php echo $data->tgl_izin ?>"/>
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php 
                    $jenis = ["Terlambat Terencana", "Terlambat Tidak Terencana", "Pulang", "Keluar"];
                    for($j=0; $j<count($jenis); $j++)
                    {
                        if($data->jenis == $jenis[$j])
                        {?>
                             <input type="radio" name="jenis2" id="jenis2" value="<?php echo $jenis[$j]?>" checked> <?php echo $jenis[$j]?>
                        <?php }else{?>
                            <input type="radio" name="jenis2" id="jenis2" value="<?php echo $jenis[$j]?>"> <?php echo $jenis[$j]?>
                        <?php }
                    }
                ?>
            </div>
          </div>
          <div class="item form-group" id="jam_masuk">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Masuk <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime3'>
                <input type='text' class="form-control" name="jam_in" id="ejam_masuk"  value="<?php echo $data->jam_in ?>" />
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group" id="jam_keluar">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Keluar <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime4'>
                <input type='text' class="form-control" name="jam_out" id="ejam_keluar"  value="<?php echo $data->jam_out ?>"/>
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea name="keterangan2" id="keterangan2" class="form-control" required="required"><?php echo $data->keterangan ?></textarea>
            </div>
          </div>
          <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
        </form>
            <!--/ Content Form -->
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->
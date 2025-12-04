<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Absensi Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Absen Pulang Cepat</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/absen_ppulangcepat" novalidate>

             <!-- <span class="section">Personal Info</span>-->
             <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
               </label>
               <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="nik" id="nik_cpt" name="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                 <?php
                 echo "<option value=''>-- Pilih --</option>";
                 foreach ($karyawan as $option) {
                   echo "<option data-subtext='$option->nik' value='$option->nik'>$option->nama_karyawan</option>";
                 }
                 ?>
               </select>
             </div>
           </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_work" required="required" />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jam Pulang
          </label>
          <div class="col-md-2 col-sm-3 col-xs-12">
            <div class='input-group date' id='myTime1'>
              <input type='text' class="form-control" name="time_out" id="time1" required="required" />
              <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
             </span>
           </div>
         </div>
       </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href="<?php  echo base_url()?>Karyawan/karyawan_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
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
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Form Wizard Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Karir Info</h2>
           
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/karir_pwinsert" novalidate>

             <!-- <span class="section">Personal Info</span>-->
               <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="nik" class="form-control col-md-7 col-xs-12">
                   <?php
                   foreach ($karyawan as $option) {
                     echo "<option value='$option->nik'>$option->nama_karyawan</option>";
                   }
                   ?>
                 </select>
                 <?php foreach ($karyawan2 as $data) { $recid = $data->recid_karyawan; } ?>
                 <input type='hidden' class="form-control" name="recid_karyawan" required="required" value="<?php echo $recid ?>" />
               </div>
             </div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_sk">No SK <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" name="no_perjanjian" placeholder="Nomor Perjanjian" required="required" type="text">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul Perjanjian <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="judul_perjanjian" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <div class='input-group date' id='myDatepicker3'>
                  <input type='text' class="form-control" name="tgl_m_karir" required="required" />
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
               <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_a_karir"  />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
          <label for="agama" class="control-label col-md-3">Bagian<span class="required">*</span></label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="recid_bag"  class="form-control col-md-7 col-xs-12" required="required">
              <?php
              foreach ($bagian as $option) {
                echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="item form-group">
          <label for="jabatan" class="control-label col-md-3">Jabatan<span class="required">*</span></label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="recid_jbtn"  class="form-control col-md-7 col-xs-12" required="required">
              <?php
              foreach ($jabatan as $option) {
                echo "<option value='$option->recid_jbtn'>$option->nama_jbtn</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Status Jabatan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select id="sts-jbtn"  name="sts_jbtn" class="form-control col-md-7 col-xs-12">
              <option value="Tetap">Tetap</option>
              <option value="Kontrak">Kontrak</option>
              <option value="Bulanan">Bulanan</option>
              <option value="Harian">Harian</option>
            </select>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Posisi Aktif <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            Aktif: <input type="radio" class="flat" name="posisi_aktif" id="genderM" value="Yes" checked="" required /> 
            Tidak Aktif : <input type="radio" class="flat" name="posisi_aktif" id="genderF" value="No" />
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            Aktif: <input type="radio" class="flat" name="sts_aktif" id="genderM" value="Aktif" checked="" required /> 
            Resign: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Resign" />
            Pensiun: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Pensiun" />
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File Perjanjian
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <span>*.pdf</span>
            <input  type="file" name="scan_perjanjian"  class="form-control col-md-7 col-xs-12">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="note" required="required" name="note" class="form-control col-md-7 col-xs-12"></textarea>
          </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-md-offset-3">
           <a href="<?php  echo base_url()?>Karyawan/karir_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
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
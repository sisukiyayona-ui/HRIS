
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Tanggungan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Tanggungan Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/tunjangan_pinsert" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

             <!-- <span class="section">Personal Info</span>-->
             <div class="item form-group">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
               <?php
                   echo "<option value=''>-- Pilih --</option>";
                foreach ($karyawan as $option) {
                   echo "<option data-subtext='$option->nik' value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                }
               ?>
             </select>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Tanggungan <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_tunjangan" placeholder="Nama Lengkap Tanggungan" required="required" type="text">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hub_kel">Hubungan Keluarga<span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                Istri / Suami : <input type="radio"  name="hub_keluarga" id="genderM" value="Pasangan" checked="" required /> 
                Anak : <input type="radio" name="hub_keluarga" id="genderF" value="Anak" />
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="tmp_lahir" name="tmp_lahir" required="required" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class='input-group date' id='myDatepicker2'>
                  <input type='text' class="form-control" name="tgl_lahir" placeholder="yyyy-mm-dd" />
                  <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                 </span>
               </div>
             </div>
           </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kk">Nik Tangguangan <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="number" type="text" name="no_id"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required">
            </div>
          </div>
          <div class="item form-group">
            <label for="agama" class="control-label col-md-3">Agama<span class="required" style="color: red">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="bagian"  name="agama" class="selectpicker form-control  col-md-12 col-xs-12" required="required">
                <option value="">-- Pilih --</option>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha">Budha</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label for="agama" class="control-label col-md-3">Pendidikan<span class="required" style="color: red">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="bagian" name="pendidikan" class="selectpicker form-control  col-md-12 col-xs-12" required="required">
                <option value="">-- Pilih --</option>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kerja">Pekerjaan <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="pekerjaan" placeholder="Pekerjaan" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor BPJS Kesehatan <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="number" type="text" name="no_bpjs"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_tunjangan">Status Tanggungan <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              Tidak Ditanggung : <input type="radio" name="sts_tunjangan" id="genderM" value="No" checked="" required /> 
              Ditanggung : <input type="radio"  name="sts_tunjangan" id="genderF" value="Yes" />
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"></textarea>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
             <a href='<?php echo base_url()?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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
        <!-- /page content-->
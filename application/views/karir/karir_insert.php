<!-- page content -->

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Karir</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Karir Info</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/karir_pinsertbeta" id="karir" novalidate >

             <!-- <span class="section">Personal Info</span>-->
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat">Jenis Karir <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select  class="selectpicker form-control  col-md-12 col-xs-12" id="jenis" name="jenis">
                 <option value=""> -- Pilih --</option>
                 <option value="Awal"> Awal</option>
                 <option value="Lanjutan"> Lanjutan</option>
                 <option value="Akhir"> Akhir</option>
               </select>
             </div>
           </div>
           <div class="item form-group" id="lanjutan" style="display: none">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat">Kategori Karir <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="radio"  name="kategori" value="Rotasi"  checked="" required /> Rotasi 
              <input type="radio"  name="kategori"  value="Mutasi" /> Mutasi 
              <input type="radio"  name="kategori"  value="Perpanjang"/> Perpanjang 
              <input type="radio"  name="kategori"  value="Promosi"/> Promosi 
              <input type="radio"  name="kategori"  value="Angkat Tetap"/> Angkat Tetap 
            </div>
          </div>
          <div class="item form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
           </label>
           <div class="col-md-6 col-sm-6 col-xs-12">
             <select class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required" id="emp12" name="nik">
              <option value="">-- Pilih --</option>
              <?php
              foreach ($karyawan as $option) {
               echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
             }
             ?>
             </select>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Nomor SK <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input class="form-control col-md-7 col-xs-12" name="no_perjanjian" placeholder="Nomor SK" required="required" type="text">
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul SK <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" name="judul_perjanjian" required="required" class="form-control col-md-7 col-xs-12">
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
         <div class='input-group date' id='myDatepicker5'>
          <input type='text' class="form-control" name="tgl_m_karir" required="required" />
          <span class="input-group-addon">
           <span class="glyphicon glyphicon-calendar"></span>
         </span>
       </div>
     </div>
   </div>
   <div class="item form-group akhir">
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
<div class="item form-group" style="display: none" id="genik">
  <label for="agama" class="control-label col-md-3">NIK<span class="required" style="color: red">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <div class="col-md-6 col-sm-6 col-xs-12">
    <input type='text' class="form-control" name="nik1" id="nik1"  readonly="readonly" />
  </div>
  <div class="col-md-3 col-sm-3 col-xs-12">
    <input type='text' class="form-control" name="nik2" id="nik2" readonly="readonly" />
    <input type='hidden' class="form-control" id="spm" readonly="readonly" />
  </div>
</div>
</div>
<div class="item form-group akhir">
  <label for="agama" class="control-label col-md-3">Bagian<span class="required" style="color: red">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <select name="recid_bag"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" >
      <?php
      echo "<option value=''>-- Pilih --</option>";
      foreach ($bagian as $option) {
        echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
      }
      ?>
    </select>
  </div>
</div>
<div class="item form-group akhir">
  <label for="jabatan" class="control-label col-md-3">Jabatan<span class="required" style="color: red">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <select name="recid_jbtn" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
      <?php
      echo "<option value=''>-- Pilih --</option>";
      foreach ($jabatan as $option) {
        echo "<option value='$option->recid_jbtn'>$option->indeks_jabatan ($option->sts_jabatan)</option>";
      }
      ?>
    </select>
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Bulanan <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    Ya : <input type="radio" class="flat" name="bulanan" id="bulan" value="Ya" checked="checked"  /> 
    Tidak : <input type="radio" class="flat" name="bulanan" id="bulan" value="Tidak" />
</div>
</div> 
<!-- <div class="item form-group akhir">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Status Jabatan <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <select id="sts-jbtn"  name="sts_jbtn" class="selectpicker form-control  col-md-12 col-xs-12" required="required">
      <option value="">-- Pilih --</option>
      <option value="Tetap">Tetap</option>
      <option value="Kontrak">Kontrak</option>
	  <option value="Honorer">Honorer</option>

    </select>
  </div>
</div> -->
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Aktif"  />  Aktif  &nbsp;
    <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Tidak Aktif" />  Tidak Aktif 
  </div><br>
  <div class="col-md-6 col-sm-6 col-xs-12" style="display: none" id="aktif">
    <br>
    <select class="selectpicker form-control  col-md-12 col-xs-12" name="sts_aktif">
      <option value="Resign">Resign</option>
      <option value="Pensiun">Pensiun</option>
      <option value="PHK">PHK</option>
    </select>
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File SK
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <span style="color: red">*.pdf</span>
    <input  type="file" name="scan_perjanjian"  class="form-control col-md-7 col-xs-12">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
  </div>
</div>
<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3">
   <a href="<?php  echo base_url()?>Karyawan/karyawan_viewbeta"> <button type="button" class="btn btn-primary">Cancel</button></a>
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


<script>
  $(document).ready(function() {
    var form = document.getElementById("karir");
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i) {
      elements[i].readOnly = true;
    }
  });
</script>
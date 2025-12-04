<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Pelamar</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>index.php/Recruitment/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Personal Info</h2>
          
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Recruitment/pelamar_pinsert" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

             <!-- <span class="section">Personal Info</span>-->
            <?php echo $this->session->flashdata('message');?>
            
           
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Pelamar <spanclass="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_pelamar" placeholder="Nama Lengkap" required="required" type="text">
              </div>
            </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP <spanclass="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="number" type="text" name="no_ktp"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required" placeholder="Nomor KTP">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_lahir" placeholder="thn-bln-tgl" required="required" />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Email <spanclass="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="email" name="email" class="form-control col-md-7 col-xs-12" required="required" placeholder="Email">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea id="textarea" name="alamat" class="form-control col-md-7 col-xs-12"  placeholder="Alamat KTP"></textarea>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon  <spanclass="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input name="no_telp" class="form-control col-md-7 col-xs-12"  onkeypress="return hanyaAngka(event)" placeholder="No Telp Pribadi" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jenis Referensi  <spanclass="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <input type="radio" name="jenis_referensi" value="Karyawan"> Karyawan
             <input type="radio" name="jenis_referensi" value="Bukan Karyawan"> Bukan Karyawan
           </div>
           <br>
           <div class="col-md-6 col-sm-6 col-xs-12" id="karyawan" style="display: none;">
            <select name="recid_karyawan" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
              <option value="">-- Pilih --</option>
              <?php foreach ($karyawan as $data) {?> 
                <option data-subtext='<?php echo $data->nik ?>' value="<?php echo $data->recid_karyawan ?>"><?php echo $data->nama_karyawan ?></option>
              <?php } ?>
            </select>
          </div>
            <div class="col-md-6 col-sm-6 col-xs-12"  id="referensi" style="display: none;">
              <input name="referensi" class="form-control col-md-7 col-xs-12" placeholder="Referensi"  type="text">
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
         <a href='<?php echo base_url()?>index.php/Recruitment/pelamar_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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

        <script type="text/javascript">
          $(document).ready(function() {
           $('input:radio[name=jenis_referensi]').change(function() {
            if (this.value == 'Karyawan') {
             $("#karyawan").show();
             $("#referensi").hide();
           }
           else{
             $("#karyawan").hide();
             $("#referensi").show();
           }
         });
         });
       </script>

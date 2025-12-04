<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Edit Data Pelamar</h3>
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
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Recruitment/pelamar_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

             <!-- <span class="section">Personal Info</span>-->
            <?php echo $this->session->flashdata('message');?>
            <?php foreach ($pelamar as $data) { } ?>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Pelamar <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" name="nama_pelamar" placeholder="Nama Lengkap" required="required" type="text" value="<?php echo $data->nama_pelamar ?>">
                <input id="name" class="form-control col-md-7 col-xs-12" name="recid_pelamar" placeholder="Nama Lengkap" required="required" type="hidden" value="<?php echo $data->recid_pelamar ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP <spanclass="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="number" type="text" name="no_ktp"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required" placeholder="Nomor KTP" value="<?php echo $data->no_ktp ?>">
              </div>
            </div>
             <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_lahir" placeholder="thn-bln-tgl" value="<?php echo $data->tgl_lahir ?>" />
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
                <input type="email" name="email" class="form-control col-md-7 col-xs-12" required="required" placeholder="Email" value="<?php echo $data->email; ?>">
              </div>
            </div>
             <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea id="textarea" name="alamat" class="form-control col-md-7 col-xs-12"  placeholder="Alamat KTP"><?php echo $data->alamat ?></textarea>
            </div>
          </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon  <spanclass="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input name="no_telp" class="form-control col-md-7 col-xs-12"  onkeypress="return hanyaAngka(event)" placeholder="No Telp Pribadi" required="required" type="text" value="<?php echo $data->no_telp ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jenis Referensi  <spanclass="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->jenis_referensi == 'Karyawan'){ ?>
                 <input type="radio" name="jenis_referensi" value="Karyawan" checked="checked"> Karyawan
                 <input type="radio" name="jenis_referensi" value="Bukan Karyawan"> Bukan Karyawan
                 <br><br>
                 <div class="col-md-12 col-sm-6 col-xs-12" id="karyawan"  style="display: contents;">
                  <select name="recid_karyawan" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($karyawan as $option) {
                      if($data->referensi == $option->recid_karyawan){?> 
                        <option data-subtext='<?php echo $option->nik ?>' value="<?php echo $option->recid_karyawan ?>" selected="selected"><?php echo $option->nama_karyawan ?></option>
                      <?php }else{ ?>
                        <option data-subtext='<?php echo $option->nik ?>' value="<?php echo $option->recid_karyawan ?>"><?php echo $option->nama_karyawan ?></option>
                      <?php }
                    } ?>
                  </select>
                </div>
              <?php }else{ ?>
                <input type="radio" name="jenis_referensi" value="Karyawan"> Karyawan
                <input type="radio" name="jenis_referensi" value="Bukan Karyawan" checked="checked"> Bukan Karyawan
                <br><br>
                <div class="col-md-12 col-sm-12 col-xs-12"  id="referensi"  style="display: contents;">
                  <input name="referensi" class="form-control col-md-12 col-xs-12" placeholder="Referensi" type="text" value="<?php echo $data->referensi ?>">
                </div>
              <?php } ?>

              <div class="col-md-6 col-sm-6 col-xs-12" id="karyawan2" style="display: none;">
                <select name="recid_karyawan2" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
                  <option value="">-- Pilih --</option>
                  <?php foreach ($karyawan as $option) {?> 
                    <option data-subtext='<?php echo $option->nik ?>' value="<?php echo $option->recid_karyawan ?>"><?php echo $option->nama_karyawan ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12"  id="referensi2" style="display: none;">
                <input name="referensi2" class="form-control col-md-7 col-xs-12" placeholder="Referensi" required="required" type="text">
              </div>

            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"><?php echo $data->note ?></textarea>
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Berkas Lainnya
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <?php 
                  if($data->other_berkas == '0')
                  {?>
                     <input type="radio" name="other berkas" value="1"> Open
                     <input type="radio" name="other berkas" value="0" checked="checked"> Closed
                  <?php }else{?>
                       <input type="radio" name="other berkas" value="1" checked="checked"> Open
                      <input type="radio" name="other berkas" value="0" > Closed
                  <?php }?>
            
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
              $("#karyawan2").show();
             $("#referensi2").hide();
             $("#referensi").hide();
           }
           else{
             $("#karyawan2").hide();
             $("#karyawan").hide();
             $("#referensi2").show();
           }
         });
         });
       </script>
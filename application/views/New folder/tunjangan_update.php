
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Tanggungan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Tanggungan Karyawan</h2>
               
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/tunjangan_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

             <!-- <span class="section">Personal Info</span>-->
             <?php 
              foreach ($tunjangan as $data) { }
             ?>
             <div class="item form-group">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="hidden" id="recid_tunjangan" name="recid_tunjangan" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_tunjangan ?>">
                <select name="recid_karyawan" class="form-control col-md-7 col-xs-12">
               <?php
                foreach ($karyawan as $option) {
                  if($data->recid_karyawan == $option->recid_karyawan){
                   echo "<option value='$option->recid_karyawan' selected='selected'>$option->nama_karyawan</option>";
                  }else{
                     echo "<option value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                  }
                }
               ?>
             </select>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Tunjangan <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_tunjangan" placeholder="Nama Lengkap Tunjangan" required="required" type="text" value="<?php echo $data->nama_tunjangan ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hub_kel">Hubungan Keluarga <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php
                if($data->hub_keluarga == "Pasangan"){?>
                  Istri / Suami : <input type="radio" class="flat" name="hub_keluarga" id="genderM" value="Pasangan" checked="checked" required /> 
                  Anak : <input type="radio" class="flat" name="hub_keluarga" id="genderF" value="Anak" />
                <?php  }else { ?>
                  Istri / Suami : <input type="radio" class="flat" name="hub_keluarga" id="genderM" value="Pasangan" required /> 
                  Anak : <input type="radio" class="flat" name="hub_keluarga" id="genderF" value="Anak" checked="checked"  />
                <?php } ?>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="tmp_lahir" name="tmp_lahir" required="required" class="form-control col-md-7 col-xs-12"  value="<?php echo $data->tmp_tlahir ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class='input-group date' id='myDatepicker2'>
                  <input type='text' class="form-control" name="tgl_lahir"  value="<?php echo $data->tgl_tlahir ?>" />
                  <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                 </span>
               </div>
             </div>
           </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kk">Nomor NIK KK <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="number" type="text" name="no_id"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required"  value="<?php echo $data->no_id ?>">
            </div>
          </div>
          <div class="item form-group">
            <label for="agama" class="control-label col-md-3">Agama<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="agama"  name="agama" class="form-control col-md-7 col-xs-12">
                <?php if($data->agama == "Islam") { ?>
                  <option value="Islam" selected="selected">Islam</option>
                  <option value="Kristen">Kristen</option>
                  <option value="Hindu">Hindu</option>
                  <option value="Budha">Budha</option>
                <?php } else if($data->agama == "Kristen"){?>
                 <option value="Islam">Islam</option>
                 <option value="Kristen" selected="selected">Kristen</option>
                 <option value="Hindu">Hindu</option>
                 <option value="Budha">Budha</option>
               <?php } else if($data->agama == "Hindu"){?>
                 <option value="Islam">Islam</option>
                 <option value="Kristen">Kristen</option>
                 <option value="Hindu" selected="selected">Hindu</option>
                 <option value="Budha">Budha</option>
               <?php } else{?>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha" selected="selected">Budha</option>
              <?php } ?>
            </select>
            </div>
          </div>
          <div class="item form-group">
            <label for="agama" class="control-label col-md-3">Pendidikan<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="pendidikan" name="pendidikan" class="form-control col-md-7 col-xs-12">
                <?php if($data->pendidikan == "Belum Sekolah"){ ?>
                <option value="Belum Sekolah" selected="selected">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
                <?php } else if($data->pendidikan == "TK"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK" selected="selected">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "SD"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD" selected="selected">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "SMP"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP"  selected="selected">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "SLTA"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA"  selected="selected">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "D1"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1"  selected="selected">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "D2"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2" selected="selected">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "D3"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3"  selected="selected">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
               <?php } else if($data->pendidikan == "D4"){ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4"  selected="selected">D4</option>
                <option value="S1">S1</option>
               <?php } else{ ?>
                <option value="Belum Sekolah">Belum Sekolah</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SLTA">SLTA</option>
                <option value="D1">D1</option>
                <option value="D2">D2</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1"  selected="selected">S1</option>
               <?php } ?>
             </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kerja">Pekerjaan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="pekerjaan" placeholder="Pekerjaan" required="required" type="text" value="<?php echo $data->pekerjaan ?>">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor BPJS Kesehatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="number" type="text" name="no_bpjs"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" required="required" value="<?php echo $data->no_bpjs ?>">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_tunjangan">Status Tunjangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <?php 
              if($data->sts_tunjangan == "Yes"){?>
              Ditanggung : <input type="radio" class="flat" name="sts_tunjangan" id="genderM" value="Yes" checked="checked" required /> 
              Tidak Ditanggung : <input type="radio" class="flat" name="sts_tunjangan" id="genderF" value="No" />
              <?php } else{ ?>
              Ditanggung : <input type="radio" class="flat" name="sts_tunjangan" id="genderM" value="Yes" required /> 
              Tidak Ditanggung : <input type="radio" class="flat" name="sts_tunjangan" id="genderF" value="No" checked="checked" />
            <?php } ?>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"><?php echo $data->note ?></textarea>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
             <button type="button" class="btn btn-primary" onclick="goBack()">Cancel</button>  
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
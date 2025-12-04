<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Karir</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Karir Info</h2>
           
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/karir_pupdate" novalidate>

             <!-- <span class="section">Personal Info</span>-->
             <?php foreach ($karir as $data) { } ?>
               <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type='hidden' class="form-control" name="recid_karir" required="required" value="<?php echo $data->recid_karir ?>" />
                  <select name="nik" class="form-control col-md-7 col-xs-12"  onchange="test()" id="pnik">
                   <?php
                   foreach ($karyawan as $option) {
                    if($data->nik == $option->nik){
                     echo "<option value='$option->nik' selected='selected'>$option->nama_karyawan - $option->nik</option>";
                    }else{
                      echo "<option value='$option->nik'>$option->nama_karyawan - $option->nik</option>";
                    }
                   }
                   ?>
                 </select>
               </div>
             </div>
             <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">NIK<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" id="nik" name="nik" required="required" type="text" value="<?php echo $data->nik ?>" readonly>
                </div>
              </div>
               <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat">Kategori Karir <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select  class="form-control col-md-7 col-xs-12" id="jenis" name="jenis">
                  <?php
                    if($data->kategori == 'Awal'){?>
                      <option value="Awal" selected="selected"> Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php }else if($data->kategori == 'Rotasi'){?>php
                     <option value="Awal"> Awal</option>
                      <option value="Rotasi" selected="selected"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php } else if ($data->kategori == 'Mutasi'){ ?>
                      <option value="Awal"> Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi" selected="selected"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php } else if ($data->kategori == 'Perpanjang'){ ?>
                      <option value="Awal"> Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang" selected="selected"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php } else if ($data->kategori == 'Akhir'){ ?>
                      <option value="Awal" > Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir" selected="selected"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php } else if ($data->kategori == 'Training'){ ?>
                      <option value="Awal"> Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training" selected="selected"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap"> Angkat Tetap</option>
                    <?php } else if ($data->kategori == 'Promosi'){ ?>
                      <option value="Awal" > Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi" selected="selected"> Promosi</option>
                      <option value="Angkat Tetap" > Angkat Tetap</option>
                    <?php } else {?>
                     <option value="Awal"> Awal</option>
                      <option value="Rotasi"> Rotasi</option>
                      <option value="Mutasi"> Mutasi</option>
                      <option value="Perpanjang"> Perpanjang</option>
                      <option value="Akhir"> Akhir</option>
                      <option value="Training"> Training</option>
                      <option value="Promosi"> Promosi</option>
                      <option value="Angkat Tetap" selected="selected"> Angkat Tetap</option>
                    <?php } ?>

                 
               </select>
             </div>
           </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_sk">No SK <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span>*Perubahan data master SK di menu legal</span>
                  <select name="recid_legal" class="form-control col-md-7 col-xs-12">
                   <?php
                   foreach ($legal as $option) {
                    if($data->recid_legal == $option->recid_legal){
                     echo "<option value='$option->recid_legal' selected='selected'>$option->no_perjanjian </option>";
                    }else{
                      echo "<option value='$option->recid_legal'>$option->no_perjanjian</option>";
                    }
                   }
                   ?>
                 </select>
               </div>
              </div>
                  <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <span>*Perubahan data master SK di menu legal</span>
                     <div class='input-group date' id='myDatepicker3'>
                      <input type='text' class="form-control" name="tgl_m_karir" required="required"  value="<?php echo $data->tgl_m_karir ?>" readonly />
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
                     <span>*Perubahan data master SK di menu legal</span>
                   <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_a_karir"  value="<?php echo $data->tgl_a_karir ?>" readonly/>
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
                    if($data->recid_bag == $option->recid_bag){
                     echo "<option value='$option->recid_bag' selected='selected'>$option->nama_bag </option>";
                    }else{
                      echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
                    }
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
                    if($data->recid_jbtn == $option->recid_jbtn){
                     echo "<option value='$option->recid_jbtn' selected='selected'>$option->nama_jbtn </option>";
                    }else{
                      echo "<option value='$option->recid_jbtn'>$option->nama_jbtn</option>";
                    }
                   }
                   ?>
                </select>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Status Jabatan <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="sts_jbtn" class="form-control col-md-7 col-xs-12">
                  <?php if($data->sts_jbtn == "Tetap"){ ?>
                    <option value="Tetap" selected = "selected">Tetap</option>
                    <option value="Kontrak">Kontrak</option>
                  <?php } else {?>
                    <option value="Tetap" >Tetap</option>
                    <option value="Kontrak" selected = "selected">Kontrak</option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php
                  if($data->sts_aktif == "Aktif"){ ?>
                    Aktif: <input type="radio" class="flat" name="sts_aktif" id="genderM" value="Aktif" checked="checked" required /> 
                    Resign: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Resign" />
                    PHK: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="PHK" />
                    Pensiun: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Pensiun" />
                  <?php }else if($data->sts_aktif == "Resign"){ ?>
                    Aktif: <input type="radio" class="flat" name="sts_aktif" id="genderM" value="Aktif" required /> 
                    Resign: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Resign"   checked="checked"/>
                    PHK: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="PHK" />
                    Pensiun: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Pensiun" />
                    <?php }else if($data->sts_aktif == "PHK"){ ?>
                    Aktif: <input type="radio" class="flat" name="sts_aktif" id="genderM" value="Aktif" required /> 
                    Resign: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Resign"   />
                    PHK: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="PHK" checked="checked" />
                    Pensiun: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Pensiun" />
                  <?php }else{ ?>
                    Aktif: <input type="radio" class="flat" name="sts_aktif" id="genderM" value="Aktif" required /> 
                    Resign: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Resign" />
                    PHK: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="PHK" />
                    Pensiun: <input type="radio" class="flat" name="sts_aktif" id="genderF" value="Pensiun" checked="checked"  />
                  <?php }?>
               
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"><?php echo $data->note ?></textarea>
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
        <!-- /page content -->
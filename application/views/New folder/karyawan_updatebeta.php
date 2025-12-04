<!--page content -->
<?php $role=$this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Karyawan Beta Version</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Edit Personal Data</h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form enctype="multipart/form-data" method="post" id="testForm"  action="<?php echo base_url()?>Karyawan/karyawan_pupdatebeta" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

             <!-- <span class="section">Personal Info</span>-->
             <?php 
             foreach ($karyawan as $data) { } ?>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="foto">
                </label>
                <div class="col-md-3 col-sm-6 col-xs-6">
                  <div class="">
                    <div class="image view view-first">
                      <?php  if($data->foto == ''){?> 
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/user.png" alt="image" />
                      <?php }else{ ?>
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" alt="image" />
                      <?php } ?>
                    </div>
                    <div class="caption"> 
                      <?php 
                      if($data->foto == ''){
                        echo"<center>-</center>";
                      }else{ ?>
                        <center><p><a href="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" target="__blank"><?php echo $data->foto?></a></p></center>
                      <?php } ?>
                    </div>
                  </div>
                  <span class="required" style="color: red" style="color: red"> * Isi Bila Akan Diubah </span>
                  <input  type="hidden" name="foto2"  class="form-control col-md-7 col-xs-12" value="<?php echo $data->foto?>">
                  <input  type="file" name="foto"  class="form-control col-md-7 col-xs-12" >
                </div>
              </div>
              <br>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="nik" class="form-control col-md-7 col-xs-12" name="nik" type="text" value="<?php echo $data->nik ?>" readonly>
                  <input id="recid_karyawan" class="form-control col-md-7 col-xs-12" name="recid_karyawan" type="hidden" value="<?php echo $data->recid_karyawan ?>" >
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_karyawan" value="<?php echo $data->nama_karyawan ?>" required="required" type="text">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir <span class="required" style="color: red" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tmp_lahir" name="tmp_lahir" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tmp_lahir ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_lahir" value="<?php echo $data->tgl_lahir ?>"/>
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">Jenis Kelamin <span class="required" style="color: red" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->jenkel == "Pria") {?>
                  <input type="radio" class="flat" name="jenkel" id="genderM" value="Pria" checked="checked"  /> Pria 
                  <input type="radio" class="flat" name="jenkel" id="genderF" value="Wanita" /> Wanita 
                <?php  } else if($data->jenkel == "Wanita"){?>
                  <input type="radio" class="flat" name="jenkel" id="genderM" value="Pria"  />  Pria 
                  <input type="radio" class="flat" name="jenkel" id="genderF" value="Wanita" checked="checked" /> Wanita 
                <?php }else{?>
                  <input type="radio" class="flat" name="jenkel" id="genderM" value="Pria"  />  Pria : 
                  <input type="radio" class="flat" name="jenkel" id="genderF" value="Wanita" /> Wanita : 
                <?php } ?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Golongan Darah 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->gol_darah == "A") {?>
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="A" checked="checked" /> A 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" />B 
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" />  O  
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" /> AB 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" /> -
                <?php  } else if($data->gol_darah == "B"){?>
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="A"  /> A 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" checked="checked"/> B 
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" /> O 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" /> AB 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" /> -
                <?php  } else if($data->gol_darah == "O"){?>
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="A"  /> A 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" /> B 
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" checked="checked" />  O 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" /> AB 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" /> -
                <?php  } else if($data->gol_darah == "AB"){?>
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="A" />  A 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" /> B  
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" />  O 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" checked="checked"  /> AB 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" /> -
                 <?php  } else{?>
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="A"    /> A  
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" /> B  
                  <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" /> O 
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB"  /> AB  
                  <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" checked="checked"/> -
                <?php  }?>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP <span class="required" style="color: red" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="number" type="text" name="no_ktp"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_ktp ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor NPWP <span class="required" style="color: red" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="number" type="text" name="no_npwp" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_npwp ?>">
              </div>
            </div>
            <div class="item form-group">
              <label for="agama" class="control-label col-md-3">Agama<span class="required" style="color: red" style="color: red">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="agama"  name="agama" class="selectpicker form-control  col-md-12 col-xs-12" >
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
                 <?php } else if($data->agama == "Budha"){?>
                     <option value="Islam">Islam</option>
                     <option value="Kristen">Kristen</option>
                     <option value="Hindu" >Hindu</option>
                     <option value="Budha" selected="selected">Budha</option>
                <?php } else{?>
                    <option value="">-- Pilih --</option>
                     <option value="Islam">Islam</option>
                     <option value="Kristen">Kristen</option>
                     <option value="Hindu" >Hindu</option>
                     <option value="Budha">Budha</option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label for="agama" class="control-label col-md-3">Pendidikan<span class="required" style="color: red" style="color: red">*</span></label> 
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="pendidikan" name="pendidikan" class="selectpicker form-control  col-md-12 col-xs-12">
                <?php if($data->pendidikan == "SD"){ ?>
                  <option value="SD" selected="selected">SD</option>
                  <option value="SMP">SMP</option>
                  <option value="SMA">SMA</option>
                  <option value="D3">D3</option>
                  <option value="S1">S1</option>
                  <option value="S2">S2</option>
                <?php } else if($data->pendidikan == "SMP"){ ?>
                 <option value="SD">SD</option>
                 <option value="SMP" selected="selected">SMP</option>
                 <option value="SMA">SMA</option>
                 <option value="D3">D3</option>
                 <option value="S1">S1</option>
                 <option value="S2">S2</option>
               <?php } else if($data->pendidikan == "SMA"){ ?>
                 <option value="SD">SD</option>
                 <option value="SMP">SMP</option>
                 <option value="SMA" selected="selected">SMA</option>
                 <option value="D3">D3</option>
                 <option value="S1">S1</option>
                 <option value="S2">S2</option>
               <?php } else if($data->pendidikan == "D3"){ ?>
                 <option value="SD">SD</option>
                 <option value="SMP">SMP</option>
                 <option value="SMA">SMA</option>
                 <option value="D3" selected="selected">D3</option>
                 <option value="S1">S1</option>
                 <option value="S2">S2</option>
               <?php } else if($data->pendidikan == "S1"){ ?>
                 <option value="SD">SD</option>
                 <option value="SMP">SMP</option>
                 <option value="SMA">SMA</option>
                 <option value="D3">D3</option>
                 <option value="S1" selected="selected">S1</option>
                 <option value="S2">S2</option>
               <?php } else if ($data->pendidikan == "S2"){ ?>
                 <option value="SD">SD</option>
                 <option value="SMP">SMP</option>
                 <option value="SMA">SMA</option>
                 <option value="D3">D3</option>
                 <option value="S1">S1</option>
                 <option value="S2" selected="selected">S2</option>
               <?php } else { ?>
                 <option value="">-- Pilih --</option>
                 <option value="SD">SD</option>
                 <option value="SMP">SMP</option>
                 <option value="SMA">SMA</option>
                 <option value="D3">D3</option>
                 <option value="S1">S1</option>
                 <option value="S2" selected="selected">S2</option>
               <?php } ?>
             </select>
           </div>
         </div>
         <div class="item form-group">
          <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Jurusan</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="jurusan" type="text" name="jurusan" class="form-control col-md-7 col-xs-12" value="<?php echo $data->jurusan?>">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_jamsos">Nomor Jamsostek 
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="number" type="text" name="no_jamsos" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_jamsos?>">
          </div>
        </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_bkes">Nomor BPJS Kesehatan  / KIS<span class="required" style="color: red" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="number" type="text" name="no_bpjs_kes"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_bpjs_kes?>" >
        </div>
      </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor BPJS Tenaga Kerja<span class="required" style="color: red" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="number" type="text" name="no_bpjs_tk"  class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_bpjs_tk ?>" >
        </div>
      </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_nikah">Status Perkawinan <span class="required" style="color: red" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <?php if($data->sts_nikah == "Belum Kawin"){?>
              <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin" checked="checked"   /> Belum Kawin <br>
              <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" /> Kawin  <br>
              <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" /> Janda <br>
              <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" /> Duda <br>
            <?php } else if($data->sts_nikah == "Kawin"){ ?>
             <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"  />  Belum Kawin <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" checked="checked" /> Kawin <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" />  Janda <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" /> Duda <br>
           <?php } else if($data->sts_nikah == "Janda"){ ?>
             <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"  />  Belum Kawin <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" /> Kawin <br>
            <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" checked="checked" /> Janda <br>
            <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" />Duda <br>
           <?php } else { ?>
             <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"   />  Belum Kawin<br> 
             <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" /> Kawin  <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" />  Janda <br>
             <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" checked="checked" />Duda <br>
           <?php } ?>
         </div>
       </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai  <span class="required" style="color: red" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                  <input type='text' class="form-control" name="tgl_m_kerja"  value="<?php echo $data->tgl_m_kerja?>"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_karir">Tanggal Akhir <span class="required" style="color: red" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
           <div class='input-group date' id='myDatepicker2'>
            <input type='text' class="form-control" name="tgl_a_kerja"  value="<?php echo $data->tgl_a_kerja?>"/>
            <span class="input-group-addon">
             <span class="glyphicon glyphicon-calendar"></span>
           </span>
         </div>
       </div>
      </div>
      <div class="item form-group">
        <label for="agama" class="control-label col-md-3">Bagian  <span class="required" style="color: red" style="color: red">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select name="recid_bag"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" >
           <?php
           if($data->recid_bag == '0'){ ?>
            <option value="">-- Pilih --</option>
            <?php 
            foreach ($bagian as $option) {
              echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
            }
          }else{
            foreach ($bagian as $option) {
            if($data->recid_bag == $option->recid_bag){
             echo "<option value='$option->recid_bag' selected='selected'>$option->nama_bag </option>";
            }else{
               echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
            }
           }
         }

         ?>
          </select>
        </div>
      </div>
      <div class="item form-group">
        <label for="jabatan" class="control-label col-md-3">Jabatan <span class="required" style="color: red" style="color: red">*</span></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select name="recid_jbtn"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" >
             <?php
           if($data->recid_jbtn == '0'){ ?>
            <option value="">-- Pilih --</option>
            <?php 
            foreach ($jabatan as $option) {
              echo "<option value='$option->recid_jbtn'>$option->nama_jbtn</option>";
            }
          }else{
            foreach ($jabatan as $option) {
            if($data->recid_jbtn == $option->recid_jbtn){
             echo "<option value='$option->recid_jbtn' selected='selected'>$option->nama_jbtn </option>";
            }else{
               echo "<option value='$option->recid_jbtn'>$option->nama_jbtn</option>";
            }
           }
         }

         ?>
          </select>
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts">Status Jabatan  <span class="required" style="color: red" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select id="sts-jbtn"  name="sts_jbtn" class="selectpicker form-control  col-md-12 col-xs-12">
            <?php if($data->sts_jbtn == "Tetap"){ ?>
              <option value="Tetap" selected = "selected">Tetap</option>
              <option value="Kontrak">Kontrak</option>
              <option value="Honorer">Honorer</option>
            <?php } else if ($data->sts_jbtn == "Kontrak"){?>
              <option value="Tetap" >Tetap</option>
              <option value="Kontrak" selected = "selected">Kontrak</option>
              <option value="Honorer" >Honorer</option>
            <?php } else if ($data->sts_jbtn == "Honorer"){?>
              <option value="Tetap" >Tetap</option>
              <option value="Kontrak" >Kontrak</option>
              <option value="Honorer" selected = "selected">Honorer</option>
            <?php } else {?>
            <option value="">-- Pilih --</option>
              <option value="Tetap" >Tetap</option>
              <option value="Kontrak">Kontrak</option>
              <option value="Honorer">Honorer</option>
            <?php }?>
          </select>
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan  <span class="required" style="color: red" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <?php if($data->sts_aktif == 'Aktif'){ ?>
          <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Aktif" checked="checked" />  Aktif  &nbsp;
          <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Tidak Aktif" />  Tidak Aktif 
        </div><br>
        <div class="col-md-6 col-sm-6 col-xs-12" style="display: none" id="aktif2">
          <br>
          <select class="selectpicker form-control  col-md-12 col-xs-12" name="sts_aktif">
            <option value="Resign">Resign</option>
            <option value="Pensiun">Pensiun</option>
            <option value="PHK">PHK</option>
          </select>
        </div>
      </div>
        <?php } else{ ?>
          <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Aktif" />  Aktif  &nbsp;
          <input type="radio"  name="sts_aktif2" id="tidak_aktif" value="Tidak Aktif" checked="checked" />  Tidak Aktif 
        </div><br>
        <div class="col-md-6 col-sm-6 col-xs-12"id="aktif2">
          <br>
          <select class="selectpicker form-control  col-md-12 col-xs-12" name="sts_aktif">
            <option value="Resign">Resign</option>
            <option value="Pensiun">Pensiun</option>
            <option value="PHK">PHK</option>
          </select>
        </div>
      </div>
        <?php }?>
       

       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_penunjang">Status Penunjang <span class="required" style="color: red" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <?php if($data->sts_penunjang == "TK"){?>
            <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK" checked="checked"  /> Tidak Kawin <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" /> Kawin Anak 0 <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> Kawin Anak 1 <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" /> Kawin Anak 2 <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" /> Kawin Anak 3  <br>
          <?php } else if($data->sts_penunjang == "K0"){ ?>
          <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  />  Tidak Kawin <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" checked="checked" /> Kawin Anak 0 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> Kawin Anak 1  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" /> Kawin Anak 2 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" /> Kawin Anak 3 <br> 
         <?php } else if($data->sts_penunjang == "K1"){ ?>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  /> Tidak Kawin  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" />  Kawin Anak 0 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" checked="checked"/> Kawin Anak 1 <br> 
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" /> Kawin Anak 2  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" /> Kawin Anak 3 <br>
         <?php } else if($data->sts_penunjang == "K2"){ ?>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  /> Tidak Kawin  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" /> Kawin Anak 0  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> Kawin Anak 1 <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" checked="checked" /> Kawin Anak 2 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" /> Kawin Anak 3 <br>
         <?php } else { ?>
            <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"   /> Tidak Kawin <br>
            <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" /> Kawin Anak 0 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" />  Kawin Anak 1  <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2"  /> Kawin Anak 2 <br>
           <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" checked="checked" /> Kawin Anak 3 <br>
         <?php } ?>
       </div>
     </div>
     <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP  <span class="required" style="color: red" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="textarea"  name="alamat_ktp" class="form-control col-md-7 col-xs-12"><?php echo $data->alamat_ktp ?></textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Alamat Sekarang
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="textarea"  name="alamat_skrg" class="form-control col-md-7 col-xs-12"><?php echo $data->alamat_skrg ?></textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telp  <span class="required" style="color: red" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="telp1" class="form-control col-md-7 col-xs-12" data-validate-length-range="15"  onkeypress="return hanyaAngka(event)" name="telp1" placeholder="No Telp Pribadi" value="<?php echo $data->telp1?>" type="text">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Alternatif Telp (Keluarga) 
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="telp2" class="form-control col-md-7 col-xs-12" data-validate-length-range="15"  onkeypress="return hanyaAngka(event)" name="telp2" placeholder="No Telp Alternativ"  value="<?php echo $data->telp2?>" type="text">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hobi">Hobi</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="textarea"  name="hobi" class="form-control col-md-7 col-xs-12" placeholder="Hobi"><?php echo $data->hobi ?></textarea>
      </div>
    </div>
     <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hobi">Email</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type = "text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Email" value="<?php echo $data->email ?>"></textarea>
      </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">SPM <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <?php
            if($data->spm == 'Ya'){?>
               <input type="radio" name="spm" id="SPMY" value="Ya"  checked="checked"  /> Ya  
               <input type="radio" name="spm" id="SPMT" value="Tidak"/>  Tidak
          <?php }else{ ?>
            <input type="radio" name="spm" id="SPMY" value="Ya"  /> Ya  
            <input type="radio" name="spm" id="SPMT" value="Tidak" checked=""/>  Tidak
          <?php } ?>
        </div>
      </div>
       <div class="item form-group" id="tmp_toko">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Penempatan Toko 
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="spm_tmp1" type="text" name="tmp_toko" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tmp_toko?>" placeholder='Toko'>
          <span class="required" style="color: red">*</span>Khusus SPM
        </div>
      </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Scan Bpjs Kesehatan 
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="thumbnail">
          <div class="image view view-first">
            <?php  if($data->scan_bpjs_kes == ''){?> 
              <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
            <?php }else{ ?>
              <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes?>" alt="image" />
            <?php } ?>
          </div>
          <div class="caption"> 
            <?php 
            if($data->scan_bpjs_kes == ''){
              echo"<center>-</center>";
            }else{ ?>
              <center><p><a href="<?php echo base_url()?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes?>" target="__blank"><?php echo $data->scan_bpjs_kes?></a></p></center>
            <?php } ?>
          </div>
        </div>
        <span class="required" style="color: red" style="color: red"> * Isi Bila Akan Diubah </span>
        <input  type="hidden" name="scan_bpjs_kes2"  class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_bpjs_kes?>">
        <input  type="file" name="scan_bpjs_kes"  class="form-control col-md-7 col-xs-12">
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan Bpjs Tenaga Kerja
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="thumbnail">
          <div class="image view view-first">
            <?php  if($data->scan_bpjs_tk == ''){?> 
              <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
            <?php }else{ ?>
              <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk?>" alt="image" />
            <?php } ?>
          </div>
          <div class="caption"> 
            <?php 
            if($data->scan_bpjs_tk == ''){
              echo"<center>-</center>";
            }else{ ?>
              <center><p><a href="<?php echo base_url()?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk?>" target="__blank"><?php echo $data->scan_bpjs_tk?></a></p></center>
            <?php } ?>
          </div>
        </div>
        <span class="required" style="color: red" style="color: red"> * Isi Bila Akan Diubah </span>
        <input  type="hidden" name="scan_bpjs_tk2"  class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_bpjs_tk?>">
        <input  type="file" name="scan_bpjs_tk"  class="form-control col-md-7 col-xs-12" >
      </div>
    </div>
     <?php if($role == '1' or $role == '5'){ ?>
    <div class="x_title">
      <h2>Data Pendukung Upah</h2>
      <div class="clearfix"></div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lspmi">SPMI
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <?php
        if($data->lspmi == 'Ya'){ ?>
         <input type="radio" class="flat" name="lspmi" id="lspmi" value="Ya" checked="checked"  /> Ya 
         <input type="radio" class="flat" name="lspmi" id="lspmi" value="Tidak" /> Tidak 
       <?php  }
       else {?>
        <input type="radio" class="flat" name="lspmi" id="lspmi" value="Ya" />   Ya 
          <input type="radio" class="flat" name="lspmi" id="lspmi" value="Tidak" checked="checked" /> Tidak 
       <?php } ?>
     </div>
   </div>
   <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pensiun">Tunjangan Pensiun
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <?php
      if($data->pensiun == 'Ya'){ ?>
       <input type="radio" class="flat" name="pensiun" id="pensiun" value="Ya" checked="checked"  /> Ya 
      <input type="radio" class="flat" name="pensiun" id="pensiun" value="Tidak" /> Tidak 
     <?php  }
     else {?>
       <input type="radio" class="flat" name="pensiun" id="pensiun" value="Ya" />  Ya 
       <input type="radio" class="flat" name="pensiun" id="pensiun" value="Tidak" checked="checked" /> Tidak 
     <?php } ?>
   </div>
 </div>
 <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gapok">Gaji Pokok
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input id="gapok" type="text" name="gapok"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Gaji Pokok" value="<?php  echo $data->gapok?>">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jabatan">Tunjangan Jabatan
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input id="t_jabatan" type="text" name="t_jabatan"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jabatan" value="<?php  echo $data->t_jabatan?>">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_prestasi">Tunjangan Masa Kerja
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input id="t_prestasi" type="text" name="t_prestasi"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Masa Kerja" value="<?php  echo $data->t_prestasi?>">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jenk_pek">Tunjangan Jenis Pekerjaan
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input id="t_jenk_pek" type="text" name="t_jen_pek"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jenis Pekerjaan" value="<?php  echo $data->t_jen_pek?>">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Jemputan
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php
    if($data->ljemputan == 'Ya'){ ?>
      <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Ya" checked="checked"  /> Ya 
       <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Tidak" /> Tidak
    <?php  }
    else {?>
      <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Ya"   />  Ya 
      <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Tidak"  checked="checked"/> Tidak 
   <?php } ?>
   
 </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Akun Bank
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" id="acc_bank" name="acc_bank"  class="form-control col-md-7 col-xs-12" placeholder="Akun Bank" value="<?php  echo $data->acc_bank?>">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Nama Bank <span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" id="nama_bank" name="nama_bank" class="form-control col-md-7 col-xs-12" placeholder="Nama Bank" value="<?php  echo $data->nama_bank?>">
  </div>
</div> 
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Bulanan<span class="required">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <?php
   if($data->ljemputan == 'Ya'){ ?>
    <input type="radio" class="flat" name="bulanan" id="lspmi" value="Ya" checked="checked"  />  Ya 
     <input type="radio" class="flat" name="bulanan" id="lspmi" value="Tidak" /> Tidak 
  <?php  }
  else {?>
    <input type="radio" class="flat" name="bulanan" id="lspmi" value="Ya"   /> Ya
    <input type="radio" class="flat" name="bulanan" id="lspmi" value="Tidak" checked="checked" /> Tidak 
 <?php } ?>
</div>
</div> 
<?php } ?>
<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3">
   <a href='<?php echo base_url()?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
    <input id="send" type="submit" class="btn btn-success"></button>
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
    $('input:radio[name=sts_aktif2]').change(function() {
      if (this.value == 'Tidak Aktif') {
       $("#aktif2").show();
     }
     else{
       $("#aktif2").hide();
     }
   });
  });
</script>
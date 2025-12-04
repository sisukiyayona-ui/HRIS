<!-- page content -->
<?php $role=$this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Edit Personal Data</h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/karyawan_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

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
                  <span>* Isi Bila Akan Diubah </span>
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
                  <input id="recid_bag" class="form-control col-md-7 col-xs-12" name="recid_bag" type="hidden" value="<?php echo $data->recid_bag ?>" >
                  <input id="recid_jbtn" class="form-control col-md-7 col-xs-12" name="recid_jbtn" type="hidden" value="<?php echo $data->recid_jbtn ?>" >
                  <input id="tgl_m_kerja" class="form-control col-md-7 col-xs-12" name="tgl_m_kerja" type="hidden" value="<?php echo $data->tgl_m_kerja ?>" >
                  <input id="tgl_a_kerja" class="form-control col-md-7 col-xs-12" name="tgl_a_kerja" type="hidden" value="<?php echo $data->tgl_a_kerja ?>" >
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_karyawan" value="<?php echo $data->nama_karyawan ?>" required="required" type="text">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tmp_lahir" name="tmp_lahir" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tmp_lahir ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_lahir" value="<?php echo $data->tgl_lahir ?>" />
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">Jenis Kelamin
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->jenkel == "Pria") {?>
                  Pria : <input type="radio" class="flat" name="jenkel" id="genderM" value="Pria" checked="checked"  /> 
                  Wanita : <input type="radio" class="flat" name="jenkel" id="genderF" value="Wanita" />
                <?php  } else{?>
                  Pria : <input type="radio" class="flat" name="jenkel" id="genderM" value="Pria"  /> 
                  Wanita : <input type="radio" class="flat" name="jenkel" id="genderF" value="Wanita" checked="checked" />
                <?php } ?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Golongan Darah
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->gol_darah == "A") {?>
                  A : <input type="radio" class="flat" name="gol_darah" id="genderM" value="A" checked="checked" required /> 
                  B : <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" />
                  O : <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" /> 
                  AB : <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" />
                  - : <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" />
                <?php  } else if($data->gol_darah == "B"){?>
                  A : <input type="radio" class="flat" name="gol_darah" id="genderM" value="A" required /> 
                  B : <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" checked="checked"/>
                  O : <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" /> 
                  AB : <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" />
                  - : <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" />
                <?php  } else if($data->gol_darah == "O"){?>
                  A : <input type="radio" class="flat" name="gol_darah" id="genderM" value="A" required /> 
                  B : <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" />
                  O : <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" checked="checked" /> 
                  AB : <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" />
                  - : <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" />
                <?php  } else{?>
                  A : <input type="radio" class="flat" name="gol_darah" id="genderM" value="A"required /> 
                  B : <input type="radio" class="flat" name="gol_darah" id="genderF" value="B" />
                  O : <input type="radio" class="flat" name="gol_darah" id="genderM" value="O" /> 
                  AB : <input type="radio" class="flat" name="gol_darah" id="genderF" value="AB" checked="checked"  />
                  - : <input type="radio" class="flat" name="gol_darah" id="genderF" value="-" />
                <?php  }?>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="number" type="text" name="no_ktp"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_ktp ?>" required="required">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor NPWP
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="number" type="text" name="no_npwp" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_npwp ?>"required="required">
              </div>
            </div>
            <div class="item form-group">
              <label for="agama" class="control-label col-md-3">Agama</label>
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
            <label for="agama" class="control-label col-md-3">Pendidikan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="pendidikan" name="pendidikan" class="form-control col-md-7 col-xs-12">
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
               <?php } else{ ?>
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
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_bkes">Nomor BPJS Kesehatan
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="number" type="text" name="no_bpjs_kes"  onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_bpjs_kes?>">
        </div>
      </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor BPJS Tenaga Kerja
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="number" type="text" name="no_bpjs_tk" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_bpjs_tk?>">
        </div>
      </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_nikah">Status Perkawinan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <?php if($data->sts_nikah == "Belum Kawin"){?>
              Belum Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin" checked="checked" required=""  /> 
              Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" />
              Janda : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" /> 
              Duda : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" />
            <?php } else if($data->sts_nikah == "Kawin"){ ?>
             Belum Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"  /> 
             Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" checked="checked" />
             Janda : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" /> 
             Duda : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" />
           <?php } else if($data->sts_nikah == "Janda"){ ?>
             Belum Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"  /> 
             Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" />
             Janda : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" checked="checked" /> 
             Duda : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" />
           <?php } else { ?>
             Belum Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Belum Kawin"   /> 
             Kawin : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Kawin" />
             Janda : <input type="radio" class="flat" name="sts_nikah" id="genderM" value="Janda" /> 
             Duda : <input type="radio" class="flat" name="sts_nikah" id="genderF" value="Duda" checked="checked" />
           <?php } ?>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_penunjang">Status Penunjang
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <?php if($data->sts_penunjang == "TK"){?>
            Tidak Kawin : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK" checked="checked" required=""  /> 
            Kawin Anak 0 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" />
            Kawin Anak 1 : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> 
            Kawin Anak 2 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" />
            Kawin Anak 3 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" />
          <?php } else if($data->sts_penunjang == "K0"){ ?>
           Tidak Kawin : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  /> 
           Kawin Anak 0 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" checked="checked" />
           Kawin Anak 1 : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> 
           Kawin Anak 2 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" />
           Kawin Anak 3 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" />
         <?php } else if($data->sts_penunjang == "K1"){ ?>
           Tidak Kawin : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  /> 
           Kawin Anak 0 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" />
           Kawin Anak 1 : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" checked="checked"/> 
           Kawin Anak 2 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" />
           Kawin Anak 3 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" />
         <?php } else if($data->sts_penunjang == "K2"){ ?>
           Tidak Kawin : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"  /> 
           Kawin Anak 0 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" />
           Kawin Anak 1 : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> 
           Kawin Anak 2 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2" checked="checked" />
           Kawin Anak 3 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" />
         <?php } else { ?>
           Tidak Kawin : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="TK"   /> 
           Kawin Anak 0 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K0" />
           Kawin Anak 1 : <input type="radio" class="flat" name="sts_penunjang" id="genderM" value="K1" /> 
           Kawin Anak 2 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K2"  />
           Kawin Anak 3 : <input type="radio" class="flat" name="sts_penunjang" id="genderF" value="K3" checked="checked" />
         <?php } ?>
       </div>
     </div>
     <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulanan">Bulanan <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
       <?php
       if($data->bulanan == 'Ya'){ ?>
        Ya : <input type="radio" class="flat" name="bulanan" id="bulan" value="Ya" checked="checked"  /> 
        Tidak : <input type="radio" class="flat" name="bulanan" id="bulan" value="Tidak" />
      <?php  }
      else {?>
       Ya : <input type="radio" class="flat" name="bulanan" id="bulan" value="Ya"   /> 
       Tidak : <input type="radio" class="flat" name="bulanan" id="bulan" value="Tidak" checked="checked" />
     <?php } ?>
   </div>
 </div> 
     <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP 
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="textarea" required="required" name="alamat_ktp" class="form-control col-md-7 col-xs-12"><?php echo $data->alamat_ktp ?></textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Alamat Sekarang
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea id="textarea" required="required" name="alamat_skrg" class="form-control col-md-7 col-xs-12"><?php echo $data->alamat_skrg ?></textarea>
      </div>
    </div>
    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon  <span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input id="telp1" class="form-control col-md-7 col-xs-12" data-validate-length-range="15"  onkeypress="return hanyaAngka(event)" name="telp1" placeholder="No Telp Pribadi" value="<?php echo $data->telp1?>" type="text" required>
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
          <textarea id="textarea"  name="hobi" class="form-control col-md-7 col-xs-12" placeholder="Hobi"></textarea>
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
       <div class="item form-group" id="tmp_kota">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Penempatan Kota
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input id="spm_tmp2" type="text" name="tmp_kota" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tmp_kota?>" placeholder='Kota'>
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
        <span>* Isi Bila Akan Diubah </span>
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
        <span>* Isi Bila Akan Diubah </span>
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
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lspmi">LSPMI
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <?php
        if($data->lspmi == 'Ya'){ ?>
         Ya : <input type="radio" class="flat" name="lspmi" id="lspmi" value="Ya" checked="checked"  /> 
         Tidak : <input type="radio" class="flat" name="lspmi" id="lspmi" value="Tidak" />
       <?php  }
       else {?>
         Ya : <input type="radio" class="flat" name="lspmi" id="lspmi" value="Ya" /> 
         Tidak : <input type="radio" class="flat" name="lspmi" id="lspmi" value="Tidak" checked="checked"   />
       <?php } ?>
     </div>
   </div>
   <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pensiun">Tunjangan Pensiun
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <?php
      if($data->pensiun == 'Ya'){ ?>
       Ya : <input type="radio" class="flat" name="pensiun" id="pensiun" value="Ya" checked="checked"  /> 
       Tidak : <input type="radio" class="flat" name="pensiun" id="pensiun" value="Tidak" />
     <?php  }
     else {?>
       Ya : <input type="radio" class="flat" name="pensiun" id="pensiun" value="Ya" /> 
       Tidak : <input type="radio" class="flat" name="pensiun" id="pensiun" value="Tidak" checked="checked"   />
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
      Ya : <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Ya" checked="checked"  /> 
      Tidak : <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Tidak" />
    <?php  }
    else {?>
     Ya : <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Ya"   /> 
     Tidak : <input type="radio" class="flat" name="ljemputan" id="ljemputan" value="Tidak"  checked="checked"/>
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

</script>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Human Resource</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <span>*</span> Report ini efektif terhadap data yang sudah berelasi antara data personal, karir, dan tanggungan karyawan<br><br>

                    <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/r_phc" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <label>Status Karyawan</label>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <select class="form-control selectpicker" multiple id="sts_kary" name="sts_kary[]">
                            <?php 
                                foreach ($sts_aktif->result() as $sa) {?>
                                   <option value="<?php echo $sa->sts_aktif?>"><?php echo $sa->sts_aktif?></option>
                                <?php }
                              ?>
                          </select>
                        </div> 
                        
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Jenis Kelamin</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple id="jenkel" name="jenkel[]">
                              <?php 
                                foreach ($jenkel->result() as $jk) {?>
                                   <option value="<?php echo $jk->jenkel?>"><?php echo $jk->jenkel?></option>
                                <?php }
                              ?>
                            </select>
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Status Perkawinan</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple name="sts_kawin[]">
                              <option value="Belum Kawin">Belum Kawin</option>
                              <option value="Kawin">Kawin</option>
                              <option value="Janda">Janda</option>
                              <option value="Duda">Duda</option>
                            </select>
                          </div>
    <br><br>           
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Department Group</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                             <select class="form-control selectpicker" multiple id="divisi" name="divisi[]">
                             <?php 
                                foreach ($dept_group->result() as $dg) {?>
                                   <option value="<?php echo $dg->dept_group?>"><?php echo $dg->dept_group?></option>
                                <?php }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Departement</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple name="departement[]" id="departement">
                              
                            </select>
                          </div>
                           <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Bagian</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple name="bagian[]" id="bagian">
                              <option value="">-- Pilih --</option>
                            </select>
                          </div>
                        </div>

                <br><br>
                        <!--<div class="col-md-12 col-sm-12 col-xs-12">
                          
                           <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Tanggungan</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control" name="tanggungan" id="tanggungan">
                              <option value="">-- Pilih --</option>
                              <option value="jml_anak">Jumlah Anak</option>
                              <option value="jml_tunjangan">Jumlah Tanggungan</option>
                            </select>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2" id="jenis_filter" style="display: none;">
                            <select class="form-control" name="tanggungan2" id="tanggungan2">
                              <option value="">-- Pilih --</option>
                              <option value="range">Range</option>
                              <option value="default">Default</option>
                            </select>
                          </div>
                           <div class="col-md-2 col-sm-2 col-xs-2" id="tunjangan_range" style="display: none">
                           <input type="text" name="anak_min" class="form-control" placeholder="min" onkeypress="return hanyaAngka(event)">
                           <input type="text" name="anak_max" class="form-control" placeholder="max" onkeypress="return hanyaAngka(event)">
                           <br>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2" id="tunjangan_default" style="display: none"> -->
                            <!-- <select class="form-control" name="default_tunjangan">
                              <option value="">-- Pilih --</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="lbh3"> > 3</option>
                            </select> -->
                         <!--   <input type="text" name="default_tunjangan" class="form-control" placeholder="max" onkeypress="return hanyaAngka(event)">
                            <br>
                          </div>
                        </div> -->
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Jabatan</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" data-live-search="true" multiple id="jabatan" name="jbtn[]">
                              <?php
                                foreach ($jabatan as $data) { ?>
                                  <option value="<?php echo $data->recid_jbtn ?>" data-subtext='<?php echo $data->sts_jabatan ?>'><?php echo $data->indeks_jabatan ?></option>
                                <?php }?>
                            </select>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Pendidikan</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple name="pendidikan[]">
                             <?php
                                foreach ($pendidikan->result() as $p) { ?>
                                  <option value="<?php echo $p->pendidikan ?>"><?php echo $p->pendidikan ?></option>
                                <?php }?>
                            </select>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <label>Agama</label>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control selectpicker" multiple name="agama[]">
                             <?php
                                foreach ($agama->result() as $a) { ?>
                                  <option value="<?php echo $a->agama ?>"><?php echo $a->agama ?></option>
                                <?php }?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                         <div class="col-md-2 col-sm-2 col-xs-2">
                          <label>Profile DISC</label>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <select class="form-control selectpicker" data-live-search="true" multiple id="jabatan" name="disc[]">
                            <?php
                            foreach ($disc->result() as $disc) { ?>
                              <option value="<?php echo $disc->profile_disc ?>"><?php echo $disc->profile_disc ?></option>
                            <?php }?>
                          </select>
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <label>Profile Personality</label>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <select class="form-control selectpicker" data-live-search="true" multiple id="jabatan" name="disc_type[]">
                            <?php
                            foreach ($disc_type->result() as $dtype) { ?>
                              <option value="<?php echo $dtype->profile_type ?>"><?php echo $dtype->profile_type ?></option>
                            <?php }?>
                          </select>
                        </div>
                      </div>


            <br><br>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <label>Usia</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <select class="form-control selectpicker" name="usia" id="usia_filter">
                                <option value="">Nothing selected</option>
                                <option value="range">Range</option>
                                <option value="default">Default</option>
                              </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2" id="usia_range" style="display: none">
                           <input type="text" name="usia_min" class="form-control" placeholder="min" onkeypress="return hanyaAngka(event)">
                           <input type="text" name="usia_max" class="form-control" placeholder="max" onkeypress="return hanyaAngka(event)">
                           <br>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2" id="usia_default" style="display: none">
                           <input type="text" name="default_usia" class="form-control" placeholder="usia" onkeypress="return hanyaAngka(event)">
                            <!-- <select class="form-control" name="default_usia">
                              <option value="">-- Pilih --</option>
                              <option value="kur35"> < 35 </option>
                              <option value="3536">35</option>
                              <option value="3644">36 - 44</option>
                              <option value="4554">45 - 54</option>
                              <option value="54 - 55"> 54 - 55</option>
                              <option value="lbh55"> > 55</option>
                            </select> -->
                            <br>
                          </div>
                        </div>

                <br><br>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-2 col-sm-2 col-xs-2">
                              <label>Masa Kerja</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <select class="form-control selectpicker" name="masker" id="masker_filter">
                                <option value="">Nothing selected</option>
                                <option value="range">Range</option>
                                <option value="default">Default</option>
                              </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2" id="masker_range" style="display: none">
                           <input type="text" name="masker_min" class="form-control" placeholder="min" onkeypress="return hanyaAngka(event)">
                           <input type="text" name="masker_max" class="form-control" placeholder="max" onkeypress="return hanyaAngka(event)">
                           <br>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2" id="masker_default" style="display: none">
                           <input type="text" name="default_masker" class="form-control" placeholder="max" onkeypress="return hanyaAngka(event)">
                            <!-- <select class="form-control" name="default_masker">
                              <option value="">-- Pilih --</option>
                              <option value="kur3">< 3</option>
                              <option value="310">3 - 10</option>
                              <option value="lbh10"> > 10</option>
                            </select> -->
                            <br>
                          </div>
                        </div>
               <br><br><hr>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                             <div class="col-md-10 col-sm-10 col-xs-10">
                             </div>
                             <div class="col-md-2 col-sm-2 col-xs-2">
                              <button type="submit" class="btn btn-primary">Match Employee Profile</button>
                              <!-- <button type="button" class="btn btn-success">Reset</button> --> 
                             </div>
                          </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


<script>
  $(document).ready(function() {
     $(".selectpicker").selectpicker();
   $('#sts_kary').change(function() {
    var sts_kary = $("#sts_kary").val();
    if(sts_kary == 'Aktif'){
      $("#sts_kary1").show();
      $("#sts_kary2").hide();
    }else{
      $("#sts_kary1").hide();
      $("#sts_kary2").show();
    }
  });

   $('#tanggungan').change(function() {
    var tanggungan = $("#tanggungan").val();
    if(tanggungan == 'jml_anak'){
      $("#jenis_filter").show();
    }else{
      $("#jenis_filter").show();
    }
  });


   $('#tanggungan2').change(function() {
    var tanggungan2 = $("#tanggungan2").val();
    if(tanggungan2 == 'range'){
      $("#tunjangan_range").show();
      $("#tunjangan_default").hide();
    }else{
      $("#tunjangan_range").hide();
      $("#tunjangan_default").show();
    }
  });

  $('#usia_filter').change(function() {
    var usia_filter = $("#usia_filter").val();
    if(usia_filter == 'range'){
      $("#usia_range").show();
      $("#usia_default").hide();
    }else{
      $("#usia_range").hide();
      $("#usia_default").show();
    }
  });

   $('#masker_filter').change(function() {
    var masker_filter = $("#masker_filter").val();
    if(masker_filter == 'range'){
      $("#masker_range").show();
      $("#masker_default").hide();
    }else{
      $("#masker_range").hide();
      $("#masker_default").show();
    }
  });

   // Chain Departement By Divisi
      $("#divisi").change(function(){ // Ketika user mengganti atau memilih data provinsi
      $("#departement").hide(); // Sembunyikan dulu combobox kota nya
      var divisi =  $("#divisi").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url();?>Karyawan/dept_by_divisi", // Isi dengan url/path file php yang dituju
        data: {divisi : divisi}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data){ // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#departement").html(response.list_karyawan).show();
           $('.selectpicker').selectpicker('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
    });

    // Chain Bagian By Departement
      $("#departement").change(function(){ // Ketika user mengganti atau memilih data provinsi
      $("#bagian").hide(); // Sembunyikan dulu combobox kota nya
      var departement =  $("#departement").val();
      var divisi =  $("#divisi").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url();?>Karyawan/bagian_by_dept", // Isi dengan url/path file php yang dituju
        data: {departement : departement, divisi: divisi}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data){ // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#bagian").html(response.list_bagian).show();
          $('.selectpicker').selectpicker('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
    });

 });
</script>
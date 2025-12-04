<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Tambah Training Karyawan</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Training Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/training_pinsert" id="karir" novalidate >

                      <?php echo $this->session->flashdata('message');?>
            
                     <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">No SK <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="no_perjanjian" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nomor SK">
                      </div>
                    </div>
                     <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul Training <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="judul_training" required="required" class="form-control col-md-7 col-xs-12" placeholder="Judul Training">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kategori Training <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                       <input type="radio" name="kategorit"  value="Umum" checked="checked"> Umum
                       <input type="radio" name="kategorit"  value="Khusus" > Khusus
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12" id="master" style="display: none">
                      <br> 
                      <div class="col-md-6 col-sm-6 col-xs-6">
                     <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> | Master Training</button>
                      </div><br><br>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                       <select name="recid_mt" class="form-control col-md-7 col-xs-12 " id='recid_mt'>
                       </select>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-6">
                       <select class="form-control col-md-3 col-xs-6" id='master_level' name="recid_mtlevel">
                       
                       </select>
                     </div>
                   </div>
                 </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tempat Training <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="tempat_training" required="required" class="form-control col-md-7 col-xs-12" placeholder="Tempat Training">
                      </div>
                    </div>
                     <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Trainer <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="trainer" required="required" class="form-control col-md-7 col-xs-12" placeholder="Pemateri">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jenis Training <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                       <input type="radio" name="sertifikat" class="flat" value="Bersertifikat"> Bersertifikat
                       <input type="radio" name="sertifikat" class="flat" value="Tidak Bersertifikat" checked="checked"> Tidak Bersertifikat
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Mulai <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                       <div class='input-group date' id='myDatepicker3'>
                        <input type='text' class="form-control" name="tgl_m_training" required="required" placeholder="Tanggal Mulai Training"/>
                        <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                       </span>
                     </div>
                   </div>
                 </div>
                 <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_training">Tanggal Akhir <span class="required" style="color: red">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                   <div class='input-group date' id='myDatepicker4'>
                    <input type='text' class="form-control" name="tgl_a_training" required="required" placeholder="Tanggal Akhir Training"/>
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
               </label>
               <div class="col-md-6 col-sm-6 col-xs-12">
                
                <select name="nik"  class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                  <!-- <select name="nik[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required"> -->
                  <option value="">-- Pilih -- </option>
                 <?php
                 foreach ($karyawan as $option) {
                   echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
                 }
                 ?>
               </select>
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
                     
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Master Training</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo base_url()?>Karyawan/tmasterlevel_pinsert">
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Master Training <span class="required">*</span>
            </label>
            <div class="col-md-5 col-sm-5 col-xs-11">
             <select name="nama_training" class="form-control col-md-7 col-xs-12 " id='master_training'>
             </select>
           </div>
           <div class="col-md-2 col-sm-2 col-xs-6">
           <a data-toggle="modal" href="#myModal2" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Next Level<span class="required">*</span>
          </label>
          <div class="col-md-5 col-sm-5 col-xs-11">
            <input type="text" name="level" class="form-control" readonly="readonly" id="level">
          </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Deskripsi<span class="required">*</span>
          </label>
          <div class="col-md-5 col-sm-5 col-xs-11">
            <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        <button type="button" class="btn btn-primary" onclick="save_level()">Simpan</button>
        </form>
      </div>
    </div>

  </div>
</div>


 <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tambah Master Training</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="<?php echo base_url()?>Karyawan/tmaster_insert">
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Master Training <span class="required">*</span>
            </label>
            <div class="col-md-5 col-sm-5 col-xs-11">
              <input type="text" name="nama_training" id="nama_training" class="form-control col-md-7 col-xs-12">
           </div>
        </div>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="save_master()">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
   list_master_depan();
   list_master();
   $('input:radio[name=kategorit]').change(function() {
    if (this.value == 'Khusus') {
     $("#master").show();
   }
   else{
     $("#master").hide();
   }
 });
   
   $("#master_training").change(function(){
    var recid_mt = document.getElementById('master_training').value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/cek_master", // Isi dengan url/path file php yang dituju
            data: {recid_mt : recid_mt}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              next = parseInt(response) + 1;
              $("#level").val(next);
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }); // master training modal

   $("#recid_mt").change(function(){
    var recid_mt = document.getElementById('recid_mt').value;
    console.log(recid_mt);
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/get_level", // Isi dengan url/path file php yang dituju
            data: {recid_mt : recid_mt}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             $("#master_level").html(response.list_kota).show();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }); // recid_mt change option luar

 }); // doc ready

  function list_master()
  {
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/master_training", // Isi dengan url/path file php yang dituju
            // data: {recid_bag : bagian}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              $("#master_training").html(response.list_kota).show();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function list_master_depan()
  {
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/master_training", // Isi dengan url/path file php yang dituju
            // data: {recid_bag : bagian}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              $("#recid_mt").html(response.list_kota).show();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function save_master()
  {
    var nama_training = document.getElementById('nama_training').value;
    // alert(nama_training);
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/tmaster_insert", // Isi dengan url/path file php yang dituju
            data: {nama_training : nama_training}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              $('#myModal2').modal('hide');
              list_master();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function save_level()
  {
    var recid_mt = document.getElementById('master_training').value;
    var level = document.getElementById('level').value;
    var deskripsi = document.getElementById('deskripsi').value;
    // alert(recid_mt + "-" + level + "-" + deskripsi);
    
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/tmasterlevel_pinsert", // Isi dengan url/path file php yang dituju
            data: {recid_mt : recid_mt, level : level, deskripsi : deskripsi}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              $('#myModal').modal('hide');
              list_master_depan();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }
</script>

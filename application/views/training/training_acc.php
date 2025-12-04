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
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/train_hcacc" id="karir" novalidate >

              <?php echo $this->session->flashdata('message');?>
              <!-- yang mengajukan -->
              <h4><span class="label label-success">Data Pemohon</span></h4>
              <div class="item form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <table class="table table-striped table-bordered">
                    <?php foreach ($pengaju as $aju) { ?>
                      <tr><td colspan="2"><b>Diajukan Oleh : </b></td></tr>
                      <tr><td>Nama </td><td><?php echo $aju->nama_karyawan ?></td></tr>
                      <tr><td>Bagian </td><td><?php echo $aju->indeks_hr ?></td></tr>
                      <tr><td>Jabatan </td><td><?php echo $aju->indeks_jabatan?></td></tr>
                      <tr><td>Department</td><td><?php echo $aju->nama_department ?></td></tr>
                      <tr><td>Tanggal Pengajuan </td><td><?php echo $newDate = date("d M Y", strtotime($aju->tgl_pengajuan));?></td></tr>
                      <tr><td>Atasan </td><td><?php echo $aju->nama_atasan ?></td></tr>
                    <?php } ?>
                    
                  </table>
                </div>
              </div>

               <hr>
              <h4><span class="label label-success">Data Pengajuan Training</span></h4>
              <div class="item form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <table class="table table-striped table-bordered">
                    <?php
                      foreach ($training as $t) { ?>
                        <tr><td colspan="2"><b>Detail Pengajuan Training : </b></td></tr>
                        <tr><td>Jenis Training </td><td><?php echo $t->jenis_training ?></td></tr>
                        <tr><td>Kategori </td><td><?php echo $t->kategori ?></td></tr>
                        <tr><td>Topik Training </td><td><?php echo $t->judul_training?><input type="hidden" name="judul_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->judul_training ?>"></td></tr>
                        <tr><td>Lembaga</td><td><?php echo $t->lembaga ?></td></tr>
                        <tr><td>Trainer</td><td><?php echo $t->trainer ?></td></tr>
                        <tr><td>Tanggal Mulai </td><td><?php echo $newDate = date("d M Y",  strtotime($t->tgl_m_training));?>
                        <input type="hidden" name="tgl_m_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->tgl_m_training ?>"></td></tr>
                        <tr><td>Tanggal Selesai </td><td><?php echo $newDate = date("d M Y",  strtotime($t->tgl_a_training));?>
                        <input type="hidden" name="tgl_a_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->tgl_a_training ?>"></td></tr>
                        <tr><td>Jumlah Jam</td><td><?php echo $t->jml_jam ?> Jam</td></tr>
                        <tr><td>Tempat Training</td><td><?php echo $t->tempat_training ?></td></tr>
                        <tr><td>Berbayar</td><td><?php echo $t->berbayar ?></td></tr>
                        <tr><td>Biaya</td><td>Rp. <?php echo number_format($t->biaya) ?></td></tr>
                        <tr><td>Scan Brosur</td><td><a href='<?php echo base_url()?>images/training/<?php echo $t->scan_brosur; ?>'  target="__blank"><?php echo $t->scan_brosur ?></a></td></tr>
                        <tr><td>Alasan Pengajuan</td><td><?php echo $t->alasan_pengajuan ?></td></tr>
                         <tr>
                            <td>Peserta</td>
                            <td> <table class="table table-striped table-bordered">
                              <thead>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Bagian</th>
                                <th>Jabatan</th>
                              </thead>
                              <tbody>
                                <?php
                                foreach ($peserta as $key) {?>
                                  <tr>
                                    <td><?php echo $key->nik ?></td>
                                    <td><?php echo $key->nama_karyawan ?></td>
                                    <td><?php echo $key->indeks_hr ?></td>
                                    <td><?php echo $key->indeks_jabatan ?></td>
                                  </tr>
                                <?php  }
                                ?>
                              </tbody>
                            </table></td>
                          </tr>
                    <?php }?>

                    
                  </table>
                </div>
              </div>


<hr>
 <h4><span class="label label-success">Data Human Training</span></h4>
 <!-- <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">No SK <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" name="no_perjanjian" class="form-control col-md-7 col-xs-12" placeholder="Nomor SK">
    <input type="hidden" name="recid_training" class="form-control col-md-7 col-xs-12" value="<?php echo $this->uri->segment(3) ?>">
   
  </div>
</div> -->
<!-- <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File SK
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <span style="color: red">*.pdf</span>
    <input  type="file" name="scan_perjanjian"  class="form-control col-md-7 col-xs-12">
  </div>
</div> -->
 
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kompetensi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-4 col-sm-4 col-xs-12">
     <input type="hidden" name="recid_training" class="form-control col-md-7 col-xs-12" value="<?php echo $this->uri->segment(3) ?>">
      <select name="kompetensi" id="id_kompetensi" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
        
      </select>
  </div>
   <div class="col-md-2 col-sm-2 col-xs-12">
     <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#add_kompetensi">+ | Kompetensi</button>
   </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Metoda <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" name="metoda" class="form-control col-md-7 col-xs-12" placeholder="Metoda">
  </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Sertifikasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <input type="radio" name="sertifikat" class="flat" value="Bersertifikat"> Bersertifikat
   <input type="radio" name="sertifikat" class="flat" value="Tidak Bersertifikat" checked="checked"> Tidak Bersertifikat
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">TNA <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <input type="radio" name="tna" class="flat" value="Ya" checked="checked"> Ya
   <input type="radio" name="tna" class="flat" value="Tidak"> Tidak
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Evaluasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <input type="radio" name="evaluasi" class="flat" value="Ya" checked="checked"> Ya
   <input type="radio" name="evaluasi" class="flat" value="Tidak"> Tidak
 </div>
</div>

<!-- <div class="item form-group">
 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Atasan <span class="required" style="color: red">*</span>
 </label>
 <div class="col-md-6 col-sm-6 col-xs-12">

  <select name="atasan"  class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
      <option value="">-- Pilih -- </option>
      <?php
      foreach ($karyawan as $option) {
       echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
     }
     ?>
   </select>
 </select>
</div>
</div> -->

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Poin <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" name="poin" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="Poin">
  </div>
</div>

<!-- <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Status Validasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <input type="radio" name="status_acc" class="flat" value="Approved" checked="checked"> Approved
   <input type="radio" name="status_acc" class="flat" value="Denied"> Denied
 </div>
</div> -->

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_training">Tanggal Validasi HC<span class="required" style="color: red"> *</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <div class='input-group date' id='myDatepicker4'>
    <input type='text' class="form-control" name="acc_hc" placeholder="Tanggal Validasi HC"/>
    <span class="input-group-addon">
     <span class="glyphicon glyphicon-calendar"></span>
   </span>
 </div>
</div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_training">Tanggal Approve Direksi<span class="required" style="color: red"> *</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <div class='input-group date' id='myDatepicker5'>
    <input type='text' class="form-control" name="acc_direksi" placeholder="Tanggal Approve Direksi"/>
    <span class="input-group-addon">
     <span class="glyphicon glyphicon-calendar"></span>
   </span>
 </div>
</div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Scan Validasi Direksi
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <span style="color: red">*.pdf</span>
    <input  type="file" name="scan_direksi"  class="form-control col-md-7 col-xs-12">
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
        <form method="post" action="<?php echo base_url()?>index.php/Training/tmasterlevel_pinsert">
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
        <form method="post" action="<?php echo base_url()?>index.php/Training/tmaster_insert">
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

<!-- Modal -->
<div class="modal fade" id="add_kompetensi" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Kompetensi</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="">
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Kompetensi <span class="required">*</span>
            </label>
            <div class="col-md-5 col-sm-5 col-xs-11">
              <input type="text" name="kompetensi" id="kompetensi" class="form-control col-md-7 col-xs-12">
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="save_kompetensi()">Simpan</button>
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
   list_kompetensi();
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
            url: "<?php echo base_url();?>index.php/Training/cek_master", // Isi dengan url/path file php yang dituju
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
            url: "<?php echo base_url();?>index.php/Training/get_level", // Isi dengan url/path file php yang dituju
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
            url: "<?php echo base_url();?>index.php/Training/master_training", // Isi dengan url/path file php yang dituju
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
            url: "<?php echo base_url();?>index.php/Training/master_training", // Isi dengan url/path file php yang dituju
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
            url: "<?php echo base_url();?>index.php/Training/tmaster_insert", // Isi dengan url/path file php yang dituju
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
            url: "<?php echo base_url();?>index.php/Training/tmasterlevel_pinsert", // Isi dengan url/path file php yang dituju
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

   function save_kompetensi()
  {
    var kompetensi = document.getElementById('kompetensi').value;
    // alert(nama_training);
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>index.php/Training/add_kompetensi", // Isi dengan url/path file php yang dituju
            data: {kompetensi : kompetensi}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              $('#add_kompetensi').modal('hide');
              list_kompetensi();
              document.getElementById('kompetensi').value =  '';
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function list_kompetensi()
  {
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>index.php/Training/kompetensi_aktif", // Isi dengan url/path file php yang dituju
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
              $("#id_kompetensi").html(response.list_kota).show();
              $('.selectpicker').selectpicker('refresh');
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }
</script>

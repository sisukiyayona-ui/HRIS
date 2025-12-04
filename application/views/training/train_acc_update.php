<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Edit Validasi Training Karyawan</h3>
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
            <h2><a href="<?php echo base_url()?>Training"><i class="fa fa-arrow-circle-o-left"></i></a> | Training Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php foreach ($training as $data) {

            } ?>
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/train_acc_pupdate" id="karir" novalidate >

             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jenis Training <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php 
                  if($data->jenis_training == "Teknis")
                  { ?>
                    <input type="radio" name="jenis_training" class="flat" value="Teknis" checked="checked"> Teknis
                    <input type="radio" name="jenis_training" class="flat" value="Non Teknis" > Non Teknis
                  <?php }
                  else{ ?>
                    <input type="radio" name="jenis_training" class="flat" value="Teknis"> Teknis
                    <input type="radio" name="jenis_training" class="flat" value="Non Teknis" checked="checked"> Non Teknis
                  <?php } ?>
                
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul Training <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="judul_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->judul_training ?>">
                <input type="hidden" name="recid_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_training ?>" readonly>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Lembaga <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="lembaga" required="required" class="form-control col-md-7 col-xs-12" placeholder="Lembaga / Penyelenggara" value="<?php echo $data->lembaga ?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Trainer <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="trainer" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->trainer ?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Mulai <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_m_training" required="required" value="<?php echo $data->tgl_m_training ?>"/>
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
            <input type='text' class="form-control" name="tgl_a_training" required="required" value="<?php echo $data->tgl_a_training ?>"/>
            <span class="input-group-addon">
             <span class="glyphicon glyphicon-calendar"></span>
           </span>
         </div>
       </div>
     </div>

     <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jumlah Jam <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="jml_jam" required="required" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" value="<?php echo $data->jml_jam ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tempat Training <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="tempat_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tempat_training ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Berbayar<span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <?php 
        if($data->berbayar == "Ya")
          { ?>
            <input type="radio" name="berbayar" class="flat" value="Ya" checked="checked"> Ya
            <input type="radio" name="berbayar" class="flat" value="Tidak" > Tidak
          <?php }
          else{ ?>
            <input type="radio" name="berbayar" class="flat" value="Ya"> Ya
            <input type="radio" name="berbayar" class="flat" value="Tidak" checked="checked"> Tidak
          <?php } ?>

        </div>
      </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Biaya <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="biaya" required="required" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="Biaya Sesuai Invoice" value="<?php echo $data->biaya ?>">
      </div>
    </div>  

    <div class="item form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Peserta <span class="required" style="color: red">*</span>
     </label>
     <div class="col-md-6 col-sm-6 col-xs-12">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Tambah Peserta</button><br><br>
      <table class="table table-striped table-bordered">
        <thead>
          <th>NIK</th>
          <th>Nama</th>
          <th>Bagian</th>
          <th>Jabatan</th>
          <th>Aksi</th>
        </thead>
        <tbody>
          <?php
          foreach ($karyawan as $key) {?>
            <tr>
              <td><?php echo $key->nik ?></td>
              <td><?php echo $key->nama_karyawan ?></td>
              <td><?php echo $key->indeks_hr ?></td>
              <td><?php echo $key->indeks_jabatan ?></td>
             <td><center><a href="<?php echo base_url()?>index.php/Training/training_dkkaryawan/<?php echo $key->recid_karir ?>/<?php echo $data->recid_training ?>"><span class="btn btn-sm btn-danger fa fa-trash"></span></button></a>&nbsp;
              <a href="<?php echo base_url()?>index.php/Training/detail_participant/<?php echo $key->recid_karyawan ?>/<?php echo $data->recid_training ?>"><span class="btn btn-sm btn-success fa fa-star" ></span></button></a></center></td>
            </tr>
          <?php  }
          ?>

        </tbody>
      </table>
    </div>
  </div>

  <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Brosur
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <a href="<?php echo base_url()?>images/training/<?php echo $data->scan_brosur?>" target="__blank"><span  class="fa fa-download">&nbsp;&nbsp;&nbsp;<?php echo $data->scan_brosur ?></span></a><br>
      <span>*.pdf (Isi Bila Akan Diubah)</span>
      <input  type="hidden" name="scan_brosur2" class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_brosur ?>">
      <input  type="file" name="scan_brosur"  class="form-control col-md-7 col-xs-12">
    </div>
  </div>

  <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Alasan Pengajuan
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <textarea id="alasan_pengajuan" name="alasan_pengajuan" class="form-control col-md-7 col-xs-12"><?php  echo $data->alasan_pengajuan ?></textarea>
    </div>
  </div>

  <hr>
 <h4><span class="label label-success">Data Human Training</span></h4>
<!--  <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">No SK <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" name="no_perjanjian" class="form-control col-md-7 col-xs-12" value="<?php echo $data->no_perjanjian ?>">
  </div>
</div> -->
    <input type="hidden" name="recid_legal" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_legal ?>">
<!-- <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File SK
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
     <a href="<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian?>" target="__blank"><span  class="fa fa-download">&nbsp;&nbsp;&nbsp;<?php echo $data->scan_perjanjian ?></span></a><br>
      <span>*.pdf (Isi Bila Akan Diubah)</span>
      <input  type="hidden" name="scan_perjanjian2" class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_perjanjian ?>">
      <input  type="file" name="scan_perjanjian"  class="form-control col-md-7 col-xs-12">
  </div>
</div> -->

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kompetensi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-4 col-sm-4 col-xs-12">
     <input type="hidden" name="recid_training" class="form-control col-md-7 col-xs-12" value="<?php echo $this->uri->segment(3) ?>">
      <select name="kompetensi" id="id_kompetensi" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true">
        <?php 
          foreach ($kompetensi->result() as $k) {
            if($k->recid_komp == $data->recid_komp)
            { ?>
              <option value="<?php echo $k->recid_komp?>" selected="selected"><?php echo $k->kompetensi?></option>
            <?php }else{?>
              <option value="<?php echo $k->recid_komp?>"><?php echo $k->kompetensi?></option>
            <?php }
          }
        ?>
      </select>
  </div>
   <div class="col-md-2 col-sm-2 col-xs-12">
     <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#add_kompetensi">+ | Kompetensi</button>
   </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Sertifikasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php 
      if($data->sertifikat == "Bersertifikat")
      { ?>
        <input type="radio" name="sertifikat" class="flat" value="Bersertifikat" checked="checked"> Bersertifikat
        <input type="radio" name="sertifikat" class="flat" value="Tidak Bersertifikat" > Tidak Bersertifikat
      <?php }
      else
      { ?>
        <input type="radio" name="sertifikat" class="flat" value="Bersertifikat"> Bersertifikat
        <input type="radio" name="sertifikat" class="flat" value="Tidak Bersertifikat" checked="checked"> Tidak Bersertifikat
      <?php } ?>
   
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">TNA <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php 
      if($data->tna == "Ya")
      { ?>
         <input type="radio" name="tna" class="flat" value="Ya" checked="checked"> Ya
         <input type="radio" name="tna" class="flat" value="Tidak"> Tidak
      <?php }
      else{ ?>
         <input type="radio" name="tna" class="flat" value="Ya" > Ya
         <input type="radio" name="tna" class="flat" value="Tidak" checked="checked"> Tidak
      <?php } ?>
  
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Evaluasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php 
      if($data->evaluasi == "Ya")
      { ?>
        <input type="radio" name="evaluasi" class="flat" value="Ya" checked="checked"> Ya
        <input type="radio" name="evaluasi" class="flat" value="Tidak"> Tidak
      <?php }
      else
      { ?>
        <input type="radio" name="evaluasi" class="flat" value="Ya" > Ya
        <input type="radio" name="evaluasi" class="flat" value="Tidak" checked="checked"> Tidak
      <?php } ?>
   
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Poin <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" name="poin" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" value="<?php echo $data->poin ?>">
  </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Status Validasi <span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php
      if($data->status_acc == "Approved")
      { ?>
       <input type="radio" name="status_acc" class="flat" value="Approved" checked="checked"> Approved
       <input type="radio" name="status_acc" class="flat" value="Denied"> Denied
      <?php }
      else
      { ?>
       <input type="radio" name="status_acc" class="flat" value="Approved" > Approved
       <input type="radio" name="status_acc" class="flat" value="Denied" checked="checked"> Denied
      <?php } ?>
  
 </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_training">Tanggal Validasi HC<span class="required" style="color: red"> *</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
   <div class='input-group date' id='myDatepicker4'>
    <input type='text' class="form-control" name="acc_hc" value="<?php echo $data->acc_hc ?>"/>
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
    <input type='text' class="form-control" name="acc_direksi" value="<?php echo $data->acc_direksi ?>"/>
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
    <a href="<?php echo base_url()?>images/training/<?php echo $data->scan_direksi?>" target="__blank"><span  class="fa fa-download">&nbsp;&nbsp;&nbsp;<?php echo $data->scan_direksi ?></span></a><br>
      <span>*.pdf (Isi Bila Akan Diubah)</span>
      <input  type="hidden" name="scan_direksi2" class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_direksi ?>">
    <input  type="file" name="scan_direksi"  class="form-control col-md-7 col-xs-12">
  </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Status Evaluasi<span class="required" style="color: red">*</span>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <?php
    if($data->status == "Open")
      { ?>
        <input type="radio" name="status"  class="flat" value="Open" checked="checked" > Open
        <input type="radio" name="status"  class="flat" value="Closed"> Close
      <?php }else{ ?>
        <input type="radio" name="status"  class="flat" value="Open" > Open
        <input type="radio" name="status"  class="flat" value="Closed" checked="checked"> Close
      <?php }
      ?>

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
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Peserta</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/training_addkar_update" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nama Karyawan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="recid_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_training ?>">
             <input type='hidden' class="form-control" name="tgl_m_training" required="required" value="<?php echo $data->tgl_m_training ?>"/>
              <input type='hidden' class="form-control" name="tgl_a_training" required="required" value="<?php echo $data->tgl_a_training ?>"/>
            <select name="nik2[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required">
             <?php
             foreach ($karyawan2 as $option) {
               echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
             }
             ?>
           </select>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
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
  });

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
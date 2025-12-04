<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Pengajuan Medical Reimburse</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Tambah Pengajuan</h2>

            <div class="clearfix"></div>
          </div>
            <?php foreach ($pengajuan->result() as $data) {
              # code...
            } ?>
            <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
              <form  enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Medical/edit_pengajuan" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Tipe <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="tipe" id="tipe" class="selectpicker form-control col-md-12 col-xs-12" required="required" onchange="jenis_optik()">
                    <?php if($data->tipe_medic == "Pengobatan"){ ?>
                      <option value="Pengobatan" selected="selected">Pengobatan</option>
                      <option value="Kaca Mata">Kaca Mata</option>
                    <?php  }else{ ?>
                     <option value="Pengobatan">Pengobatan</option>
                     <option value="Kaca Mata" selected="selected">Kaca Mata</option>
                    <?php } ?>
                    
                  </select>
                </div>
              </div>
              <div class="item form-group" id="optik" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12" >
                  <select name="jenis" id="jenis" class="selectpicker form-control col-md-12 col-xs-12" required="required">
                    <option value="Lensa">Lensa</option>
                    <option value="Frame" disabled="disabled">Frame</option>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div>
                    <select name="nik" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required" onchange="detail_karyawan()">
                      <option value="">-- Pilih --</option>
                      <?php
                      foreach ($emp as $emp) { 
                        if($emp->nik == $data->nik){ ?>
                          <option value="<?php echo $emp->nik ?>" selected="selected"><?php echo $emp->nama_karyawan ?></option>
                        <?php }else{ ?>
                          <option value="<?php echo $emp->nik ?>"><?php echo $emp->nama_karyawan ?></option>
                        <?php }?>
                      <?php }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id='niks' class="form-control" readonly="readonly" value="<?php echo $data->nik ?>">
                  <input type="hidden" id='recid_karyawan' name="recid_karyawan" class="form-control" readonly="readonly" value="<?php echo $data->recid_karyawan ?>">
                  <input type="hidden" id='medical_recid' name="medical_recid" class="form-control" readonly="readonly" value="<?php echo $data->medical_recid ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id='bagian' class="form-control" readonly="readonly">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id='jabatan' class="form-control" readonly="readonly">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Kuitansi <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select  id="nama_kuitansi"  class="form-control" name="nama_kuitansi" >
                    <option></option>
                  </select>
                  <input type="hidden" id="nama_kuitansi_edit" value="<?php echo $data->nama_kuitansi ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal Kuitansi <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date ' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_kuitansi" value="<?php echo $data->tgl_kuitansi ?>"/>
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Diagnosa <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea name="diagnosa" id="diagnosa" class="form-control"><?php echo $data->diagnosa ?></textarea>
              </div> 
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nominal <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='nominal' name="nominal" class="form-control money" value="<?php echo $data->nominal ?>">
             </div>
           </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">File Medical <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <span>File Sebelumnya : <a target="__blank" href="<?php echo base_url()?><?php echo $data->path_medical?><?php $data->file_medical?><?php echo $data->file_medical?>"><?php echo $data->file_medical ?></span>
              <input  type="file" name="foto2" class="form-control col-md-7 col-xs-12" >
              <input type="hidden" name="foto" value="<?php echo $data->file_medical ?>">
            </div>
          </div>
          <br>
          <div class="item form-group">
            <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Save changes"></button>
          </div>
          </div>
        </form>
        <!--/ Content Table -->
      </div>
    </div>
  </div>
</div>
</div>
</div>


<script>
  $(document).ready(function() {
    $('.money').mask('000.000.000.000.000', {reverse: true});
    detail_karyawan();
    jenis_optik();
    });

  function detail_karyawan()
  {
    var nik = document.getElementById('nik').value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/karyawan_detail", // Isi dengan url/path file php yang dituju
            data: {nik : nik}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             document.getElementById('niks').value = nik;
             document.getElementById('recid_karyawan').value = response[0][3];
             document.getElementById('bagian').value = response[0][1];
             document.getElementById('jabatan').value = response[0][2];
             recid_karyawan = response[0][3];
             tanggungan (recid_karyawan);
           },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function tanggungan(recid_karyawan)
  {
   $.ajax({
    type : "POST",
              url : "<?php echo base_url();?>Medical/cek_tanggungan", // Isi dengan url/path file php yang dituju
              data: {recid_karyawan : recid_karyawan}, // data yang akan dikirim ke file yang dituju
              dataType: "json",
              beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response, data){
               $("#nama_kuitansi").html(response.list_karyawan).show();
               var nama = document.getElementById("nama_kuitansi_edit").value;
               document.getElementById("nama_kuitansi").value = nama;
               $('.selectpicker').selectpicker('refresh');
             }
           });
 }

 function jenis_optik()
 {
  tipe = document.getElementById('tipe').value;
  if(tipe == 'Kaca Mata')
  {
    $("#optik").show();
  }else{
    $("#optik").hide();  
  }
}

</script>
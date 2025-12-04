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

            <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
              <form  enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Medical/add_pengajuan" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Tipe <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="tipe" id="tipe" class="selectpicker form-control col-md-12 col-xs-12" required="required" onchange="jenis_optik()">
                    <option value="Pengobatan">Pengobatan</option>
                    <option value="Kaca Mata">Kaca Mata</option>
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
                      foreach ($emp as $emp) { ?>
                        <option value="<?php echo $emp->nik ?>"><?php echo $emp->nama_karyawan ?></option>
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
                  <input type="text" id='niks' class="form-control" readonly="readonly">
                  <input type="hidden" id='recid_karyawan' name="recid_karyawan" class="form-control" readonly="readonly">
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
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal Kuitansi <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date ' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_kuitansi"  value="<?php echo date("Y-m-d") ?>"/>
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
                <textarea name="diagnosa" id="diagnosa" class="form-control"></textarea>
              </div> 
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nominal <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='nominal' name="nominal" class="form-control money">
             </div>
           </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">File Medical <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input  type="file" name="foto" class="form-control col-md-7 col-xs-12" >
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

    $("#attachform").on('submit',function(event){
      event.preventDefault();
      var postData = new FormData($("#attachform")[0]);
      console.log(postData);
      $.ajax({
       url: "<?php echo base_url()?>Medical/add_pengajuan",
       type: "POST",
       data: postData,
   processData: false,  // tell jQuery not to process the data
   contentType: false
 }).done(function(msg) {
   console.log(msg);
   // $('#myAttachModal').modal('hide');
 });
});


    $('.money').mask('000.000.000.000.000', {reverse: true});
          // Untuk sunting
          $('#edit_user').on('show.bs.modal', function (event) {
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal          = $(this)

              // Isi nilai pada field
              modal.find('#medical_recid').attr("value",div.data('medical_recid'));
              // modal.find('#tipe2').attr("value",div.data('tipe'));
              var tipe =  document.getElementById("tipe2").value = div.data('tipe');
              $('.selectpicker').selectpicker('refresh');
              if(tipe == "Kaca Mata") 
              {
                document.getElementById("jenis2").value = div.data('jenis');
                $('.selectpicker').selectpicker('refresh');
                $("#optik2").show();
              }else{
                $("#optik2").hide();
              }
              document.getElementById("nik2").value = div.data('nik');
              $('.selectpicker').selectpicker('refresh');
              modal.find('#niks2').attr("value",div.data('niks'));
              modal.find('#nama').attr("value",div.data('nama'));
              modal.find('#bagian2').attr("value",div.data('bag'));
              modal.find('#jabatan2').attr("value",div.data('jbtn'));
              // modal.find('#nama_kuitansi').attr("value",div.data('nama_kuitansi'));
              modal.find('#tgl_kuitansi').attr("value",div.data('tgl_kuitansi'));
              $("#diagnosa2").val(div.data('diagnosa'));
              modal.find('#nominal2').attr("value",div.data('nominal'));
              modal.find('#recid_karyawan2').attr("value",div.data('recid_karyawan'));
              recid_karyawan = document.getElementById("recid_karyawan2").value = div.data('recid_karyawan');
              
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
                 var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
                 var modal          = $('#edit_user')
                 $("#nama_kuitansi2").html(response.list_karyawan).show();
                 var nama = document.getElementById("nama_kuitansi2").value = div.data('nama_kuitansi');
                 $('.selectpicker').selectpicker('refresh');
               }
             });

            });
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

  function detail_karyawan2()
  {
    var nik = document.getElementById('nik2').value;
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
             document.getElementById('niks2').value = nik;
             document.getElementById('recid_karyawan2').value = response[0][3];
             document.getElementById('bagian2').value = response[0][1];
             document.getElementById('jabatan2').value = response[0][2];
             recid_karyawan = response[0][3];
             tanggungan2(recid_karyawan);
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
             }
           });
 }

 function tanggungan2(recid_karyawan)
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
                 var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
                 var modal          = $('#edit_user')
                 $("#nama_kuitansi2").html(response.list_karyawan).show();
                 var nama = document.getElementById("nama_kuitansi2").value = div.data('nama_kuitansi');
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

function jenis_optik2()
{
  tipe = document.getElementById('tipe2').value;
  if(tipe == 'Kaca Mata')
  {
    $("#optik2").show();
  }else{
    $("#optik2").hide();  
  }
}
</script>
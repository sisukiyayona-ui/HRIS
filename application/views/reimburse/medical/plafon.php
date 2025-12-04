<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Plafon Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user">
              <i class="fa fa-plus"></i> | Tambah Data
            </a>
          
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Tahun Plafon</th>
                  <th>Nik</th>
                  <th>Nama</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <th>Jumlah Plafon</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
                <?php 
                foreach ($plafon->result() as $data) {
                  echo "
                  <tr>
                  <td>$data->tahun</td>
                  <td>$data->nik</td>
                  <td>$data->nama_karyawan</td>
                  <td>$data->nama_bag</td>
                  <td>$data->nama_jbtn</td>
                  <td>";
                  echo number_format($data->jumlah_plafon);
                  echo "</td>
                  <td><center>";?>
                  <a 
                  data-plafon_recid="<?php echo $data->plafon_recid ?>"
                  data-nama="<?php echo $data->nama_karyawan ?>"
                  data-nik="<?php echo $data->nik ?>"
                  data-recid_karyawan="<?php echo $data->recid_karyawan ?>"
                  data-bag="<?php echo $data->nama_bag ?>"
                  data-jbtn="<?php echo $data->nama_jbtn ?>"
                  data-tahun="<?php echo $data->tahun ?>"
                  data-plafon="<?php echo $data->jumlah_plafon ?>"
                  data-toggle="modal" data-target="#edit_user">
                  <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?>
                  <?php 
                }
                ?>

              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Plafon</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Medical/add_plafon" novalidate>
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
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tahun <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='tahun' name="tahun" class="form-control">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Jumlah Plafon <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='plafon' name="plafon" class="form-control money">
          </div>
        </div>
       
       
        <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
      </form>
    </div>

  </div>
</div>
</div>
<!--/ Modal Tambah Data -->


<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Plafon</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
       <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Medical/edit_plafon" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
              <input type="hidden" id='plafon_recid' name="plafon_recid" class="form-control" readonly="readonly">
              <input type="text" id='nama2' class="form-control" readonly="readonly">
           </div>
         </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='niks2' name="niks2" class="form-control" readonly="readonly">
          <input type="hidden" id='recid_karyawan2' name="recid_karyawan" class="form-control" readonly="readonly">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='bagian2' class="form-control" readonly="readonly">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='jabatan2' class="form-control" readonly="readonly">
          </div>
        </div>
        <div class="item form-group">
         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tahun <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='tahun2' name="tahun" class="form-control">
          </div>
        </div>
       <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Jumlah Plafon <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
               <input type="text" id='plafon2' name="plafon" class="form-control money">
          </div>
        </div>
        
        <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
      </form>
    </div>

  </div>
</div>
</div>
<!--/ Modal Edit Data -->


<!-- /page content -->

<script>
    $(document).ready(function() {
       $('.money').mask('000.000.000.000.000', {reverse: true});
          // Untuk sunting
          $('#edit_user').on('show.bs.modal', function (event) {
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal          = $(this)

              // Isi nilai pada field
              modal.find('#plafon_recid').attr("value",div.data('plafon_recid'));
              modal.find('#nama2').attr("value",div.data('nama'));
              modal.find('#bagian2').attr("value",div.data('bag'));
              modal.find('#jabatan2').attr("value",div.data('jbtn'));
              modal.find('#tahun2').attr("value",div.data('tahun'));
              modal.find('#niks2').attr("value",div.data('nik'));
              modal.find('#plafon2').attr("value",div.data('plafon'));
              modal.find('#recid_karyawan2').attr("value",div.data('recid_karyawan'));
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
          },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
          }
      });
   }

   
</script>
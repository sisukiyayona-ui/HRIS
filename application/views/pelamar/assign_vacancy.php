<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Kandidat</h3>
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
          <?php if ($role == '1' or $role == '2' or $role == '5' or $role == '25') { ?>
            <div class="x_title">
              <h2><a href="<?php echo base_url() ?>index.php/Recruitment/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Data Vacancy</h2>
              <br>
              <br>
              <br>
              <p> Cari Berdasarkan Tanggal Registrasi</p>
              <form method="post">
                <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Dari Tanggal<span class="required">*</span>
                  </label>
                  <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class='input-group date' id='myDatepicker3'>
                      <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Sampai Tanggal<span class="required">*</span>
                  </label>
                  <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class='input-group date' id='myDatepicker2'>
                      <input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" required="required" value="<?php echo date('Y-m-d') ?>" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-2">
                    <button id="send" type="button" class="btn btn-success" onclick="load_tabel();">Cari</button>
                  </div>
                </div>
              </form>
              <div class="clearfix"></div>
            </div>
          <?php } ?>
          <div class="x_content">
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_assign" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>
                      <center>Nomor</center>
                    </th>
                    <th>
                      <center>Tgl Regis</center>
                    </th>
                    <th>
                      <center>No KTP</center>
                    </th>
                    <th>
                      <center>Nama Kandidat</center>
                    </th>
                    <th>
                      <center>Jenis Kelamin</center>
                    </th>
                    <th>
                      <center>Pendidikan</center>
                    </th>
                    <th>
                      <center>Jurusan</center>
                    </th>
                    <th>
                      <center>Kota Domisili</center>
                    </th>
                    <th>
                      <center>Telp</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Assign To</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>

                </tbody>
              </table>
            </div>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->

  <div class="modal fade bs-example-modal-sm" tabindex="-1" id="assignModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Recruitment Open List</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="erecid_pelamar" class="form-control" value="" />
          <label>Open Vacancy</label><br>
          <select name="recid_recruitment" class="form-control" id="erecid_recruitment">
            <?php
            foreach ($loker as $l) { ?>
              <option value="<?php echo $l->recid_recruitment ?>"><?php echo $l->judul_recruitment ?></option>
            <?php }
            ?>
          </select>
          <br>
          <button type="button" class="btn btn-primary" onclick="assign()">Submit</button>
        </div>
      </div>
    </div>
  </div>


  <script type="text/JavaScript">
    $(document).ready(function() {
    load_tabel();
   });
$(function () {
  $("#assignModal").on("show.bs.modal", function (e) {
  var recid_pelamar = $(e.relatedTarget).data('recid_pelamar');
    $(e.currentTarget).find('input[id="erecid_pelamar"]').val(recid_pelamar);
  });
});

function assign()
{
  recid_pelamar = document.getElementById('erecid_pelamar').value;
  recid_recruitment = document.getElementById('erecid_recruitment').value;
  // alert("pelamar "+recid_pelamar+"loker : "+recid_recruitment); 
  $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url(); ?>index.php/Recruitment/assign_vacancy_to", // Isi dengan url/path file php yang dituju
            data: {recid_pelamar : recid_pelamar, recid_recruitment:recid_recruitment}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
             $('#assignModal').modal('hide');
             alert("Kandidat Berhasil Ditambahkan");
             load_tabel();
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
}

function load_tabel()
{
  tgl_mulai = $('#tgl_mulai').val();
  tgl_akhir = $('#tgl_akhir').val();
  var table = $('#t_assign').DataTable();
            table.destroy();
            var table = $('#t_assign').DataTable( {
              // "responsive":true,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              "bDestroy": true,
              buttons: [
              'excel', 'print'
              ],
              "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>Recruitment/data_assign",
                dataType: 'json',
                data : {
                  tgl_mulai : tgl_mulai,
                  tgl_akhir : tgl_akhir,
                }
               
              },
            });
}
</script>
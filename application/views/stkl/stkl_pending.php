<!-- page content -->
<style>
  table#kar_lembur.dataTable tbody tr.Highlight_inUse > .sorting_1 {
    background-color: yellow;
    color : black;
}
 
table#kar_lembur.dataTable tbody tr.Highlight_inUse {
    background-color: yellow;
    color : black;
}
</style>
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Pending Approval</h3>
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
                    <h3>STKL Pending Approval</h3>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_stkl" class="table table-striped table-bordered">
                     <thead>
                      <tr class="text-center">
                        <td>No</td>
                        <?php
                          $role = $this->session->userdata('role_id');
                        if( $role == '1' or $role == '3' or $role == '5'){ ?>
                          <th><center>Recid STKL</center></th>
                        <?php } ?>
                        <th><center>Aksi</center></th>
                        <th><center>Status</center></th>
                        <th><center>Approval</center></th>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Jam Mulai</center></th>
                        <th><center>Jam Selesai</center></th>
                        <th><center>Kategori</center></th>
                        <th><center>Jumlah Orang</center></th>
                        <th><center>Jumlah Jam</center></th>
                        <th><center>Klasifikasi</center></th>
                        <th><center>Tipe</center></th>
                        <th><center>Pekerjaan</center></th>
                        <th><center>Jemputan</center></th>
                        <th><center>Makan</center></th>
                        <th><center>Tgl Merah</center></th>
                        <th><center>Keterangan</center></th>
                        <th><center>Alasan Over</center></th>
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
        </div>
        <!-- /page content -->

 <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Karyawan Lembur</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
        <table id="kar_lembur" class="table table-striped table-bordered">
             <thead>
              <tr class="text-center">
                <td>No</td>
                <th><center>NIK</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Bagian</center></th>
                <th><center>Jabatan</center></th>
                <th><center>Tgl Lembur</center></th>
                <th><center>Jam Masuk</center></th>
                <th><center>Jam Mulai Lembur</center></th>
                <th><center>Jam Selesai Lembur</center></th>
                <th><center>Jam Pulang</center></th>
                <th><center>Lama Lembur</center></th>
                <th><center>Duplikat</center></th>
               </tr>
            </thead>
            <tbody>
          </tbody>
        </table>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>


       <!-- Modal -->
<div id="myModalDel" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete STKL</h4>
      </div>
      <div class="modal-body">
        <div>
          <center><h4>Apakah Anda Yakin Akan Menghapus STKL Ini?</h4></center>
          <center><h5>Isikan Alasan Hapus!</h5></center>
          <form id="form_delete" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/stkl_delete" data-toggle="validator">
          <div class="item form-group" style="display:none">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">ID STKL<span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="recid_stkl" id="del_recid_stkl" class="form-control">
            </div>
          </div>
          <div class="item form-group" id="alasan">
             <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Hapus <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea name="alasan_hapus" id="alasan_hapus" class="form-control" placeholder="Masukan Alasan Hapus"></textarea>
            </div>
          </div>
        </form>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal" onClick="confirmDelete()">Hapus</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>


<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Work Flow</h4>
      </div>
      <div class="modal-body">
        <div>
          <div id="isi_workflow">
          </div>
        </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>
</div>
  
  <script>
    $( document ).ready(function() {
        getData();
    })

    $('#myModal').on('show.bs.modal', function (event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal          = $(this)
      
        recid_stkl = div.data('recid_stkl'); 
        console.log(recid_stkl); 
        var table = $('#kar_lembur').DataTable();
        table.destroy();
        var table = $('#kar_lembur').DataTable( {
          // "responsive":true,
          "bScrollCollapse": true,
          "bLengthChange": true,
          "searching": true,
           "ordering": false,
          "dom": 'Bfrtip',
          buttons: [
          'excel', 'print'
          ],
          "columnDefs": [
            {
                "targets": [ 11 ],
                "visible": false
            },
            ],
             createdRow: function (row, data, index) {
                if (data[11] == 1) {
                    console.dir(row);
                    $(row).addClass("Highlight_inUse");
                }
            },
          "ajax": {
            type: "POST",
            url: "<?php echo base_url(); ?>Lembur/karyawan_lembur",
            dataType: 'JSON',
            data: {recid_stkl:recid_stkl},
          },
        });

    });

    $('#myModal2').on('show.bs.modal', function (event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal          = $(this)
      
        recid_stkl = div.data('recid_stkl'); 
        console.log(recid_stkl); 
         $.ajax({  
          type: "POST", // 
          url: "<?php echo base_url();?>Lembur/get_workflow", 
          data: {recid_stkl : recid_stkl}, 
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(data, response){ // Ketika proses pengiriman berhasil
            document.getElementById('isi_workflow').innerHTML = data;
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
          }
        });
      });

        $('#myModalDel').on('show.bs.modal', function (event) {
          var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
          var modal          = $(this)
          
          recid_stkl =  div.data('recid_stkl');
          document.getElementById('del_recid_stkl').value = recid_stkl; 
          // console.log(recid_stkl); 
          });

    function getData()
     {
      var table = $('#t_stkl').DataTable();
      table.destroy();
      var table = $('#t_stkl').DataTable( {
        // "responsive":true,
        "bScrollCollapse": true,
        "bLengthChange": true,
        "searching": true,
        "dom": 'Bfrtip',
        buttons: [
        'excel', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Lembur/get_stkl_pending",
          dataType: 'JSON',
        },
      });
     }

     function confirmDelete(id) {
       text = document.getElementById('alasan_hapus').value;
      if (text == '') {
       alert("Harap Masukan Alasan Menghapus STKL");
      } else {
        $( "#form_delete" ).submit();
      }
      
    }
  </script>
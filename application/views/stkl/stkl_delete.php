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
                <h3> Daftar STKL Delete</h3>
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
                   <a class="btn btn-info btn-sm" href="<?php echo base_url()?>Lembur/stkl_insert">
                      <i class="fa fa-plus"></i>  | Pengajuan Lembur
                    </a> 
                      <form method="post">
                        <div class="item form-group">
                          <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Dari Tanggal<span class="required">*</span>
                          </label>
                          <div class="col-md-2 col-sm-2 col-xs-12">
                          <div class='input-group date' id='myDatepicker3'>
                            <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d')?>"  />
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
                            <input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" required="required" value="<?php echo date('Y-m-d')?>"  />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="item form-group">
                          <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Status<span class="required">*</span>
                          </label>
                          <div class="col-md-2 col-sm-2 col-xs-12">
                            <select class="form-control selectpicker" id="jenis">
                              <option value="Semua">Semua</option>
                              <option value="Belum Acc Manager">Belum Acc Manager</option>
                              <option value="Acc Manager">Acc Manager</option>
                              <option value="Tidak Acc Manager">Tidak Acc Manager</option>
                              <option value="Acc Direksi">Acc Direksi</option>
                              <option value="Tolak Direksi">Tidak Acc Direksi</option>
                              <option value="Acc Hc">Acc Hc</option>
                              <option value="Tidak Acc Hc">Tidak Acc Hc</option>
                              <option value="Realisasi Bagian">Realisasi Bagian</option>
                              <option value="Acc Realisasi Manager">Acc Realisasi Manager</option>
                              <option value="Tidak Acc Realisasi">Tidak Acc Realisasi</option>
                              <option value="Realisasi HC">Realisasi HC</option>
                            </select> 
                          </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-2">
                      <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                    </div>
                  </div>
                      </form>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_stkl" class="table table-striped table-bordered">
                     <thead>
                      <tr class="text-center">
                        <td>No</td>
                        <?php if($this->session->userdata('role_id') == '1'){ ?>
                          <th><center>Recid STKL</center></th>
                        <?php } ?>
                        <th><center>Aksi</center></th>
                        <th><center>Alasan Hapus</center></th>
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
        <div>
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

    function getData()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      tgl_akhir = document.getElementById("tgl_akhir").value;
      jenis = $('#jenis').val();
      // console.log(jenis);
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
          url: "<?php echo base_url(); ?>Lembur/stkl_periode_delete",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai, tgl_akhir:tgl_akhir, jenis:jenis},
        },
      });
     }

     function confirmDelete(id) {
      let text = "Apakah Anda Yakin Akan Menghapus STKL Ini?";
      if (confirm(text) == true) {
        links = '<?php echo base_url()?>Lembur/stkl_delete/'+id;
        // console.log("Delete Berhasil " + links);
        window.location.href = links;
      } else {
        console.log("Delete dibatalkan");
      }
      
    }
  </script>
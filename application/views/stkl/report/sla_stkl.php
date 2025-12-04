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
                <h3> Service Level Aggrement STKL</h3>
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
                   <a class="btn btn-info btn-sm" href="<?php echo base_url()?>Lembur/stkl_view">
                      <i class="fa fa-plus"></i>  | STKL View
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
                          <th><center>Recid STKL</center></th>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Jam Mulai</center></th>
                        <th><center>Jam Selesai</center></th>
                        <th><center>Jumlah Orang</center></th>
                        <th>Workflow1</th>
                        <th>Waktu</th>
                        <?php
                            for($a=0; $a<9; $a++)
                            {?>
                                 <th>Workflow<?php echo $a+2 ?></th>
                                <th>Waktu</th>
                                <th>SLA</th>
                            <?php }
                        ?>
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
          url: "<?php echo base_url(); ?>Lembur/get_sla",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai, tgl_akhir:tgl_akhir},
        },
      });
     }
  </script>
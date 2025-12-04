<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> List Karyawan Lembur</h3>
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
                    <!-- <div class="item form-group">
                          <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Karyawan<span class="required">*</span>
                          </label>
                          <div class="col-md-2 col-sm-2 col-xs-12">
                           <select class="form-control selectpicker" name="recid_karyawan" id="recid_karyawan" data-live-search="true">
                            <option value="Semua">Semua</option>
                            <?php
                            foreach ($karyawan as $k) {?>
                                <option value="<?php echo $k->recid_karyawan ?>"><?php echo $k->nama_karyawan."(".$k->nik.")"; ?></option>
                            <?php }
                            ?>
                        </select>
                          </div>
                    </div> -->
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
                    <table id="t_stkl2" class="table table-striped table-bordered">
                     <thead>
                      <tr class="text-center">
                        <td>No</td>
                        <th><center>ID STKL</center></th>
                        <th><center>Status</center></th>
                        <th><center>Approval</center></th>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Bag Lembur</center></th>
                        <th><center>NIK</center></th>
                        <th><center>Nama Karyawan</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Absen Masuk</center></th>
                        <th><center>Jam Mulai</center></th>
                        <th><center>Jam Selesai</center></th>
                        <th><center>Absen Pulang</center></th>
                        <th><center>Durasi</center></th>
                        <th><center>Lembur1</center></th>
                        <th><center>Lembur2</center></th>
                        <th><center>Lembur3</center></th>
                        <th><center>Klasifikasi</center></th>
                        <th><center>Tipe</center></th>
                        <th><center>Kategori</center></th>
                        <th><center>Pekerjaan</center></th>
                        <th><center>Jemputan</center></th>
                        <th><center>Uang Makan</center></th>
                        <th><center>Tgl Merah</center></th>
                        <th><center>Keterangan</center></th>
                        <th><center>Alasan Over</center></th>
                      </tr>
                    </thead>
                    <tbody>
                       
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

        <script>
    $( document ).ready(function() {
        getData();
    })

    function getData()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      tgl_akhir = document.getElementById("tgl_akhir").value;
      // recid_karyawan = $('#recid_karyawan').val();
      // console.log(jenis);
      var table = $('#t_stkl2').DataTable();
      table.destroy();
      var table = $('#t_stkl2').DataTable( {
        // "responsive":true,
        "bScrollCollapse": true,
        "bLengthChange": true,
        "searching": true,
        // "serverSide": true,
        "dom": 'Bfrtip',
        buttons: [
        'excel', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Lembur/get_stkl_karyawan",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai, tgl_akhir:tgl_akhir},
        },
      });
     }
  </script>
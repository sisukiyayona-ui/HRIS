<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Data Tanggungan</h3>
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
                    <h2><i class="fa fa-arrow-circle-o-left" onclick="goBack()"></i> | Detail Tanggungan</h2>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   <table class="table table-bordered">
                    <?php foreach ($tunjangan as $data) {
                    # code...
                    } ?>
                   
                    <td>Nama Karyawan</td><td><?php echo $data->nama_karyawan ?></td></tr>
                    <td>Nama Tanggungan</td><td><?php echo $data->nama_tunjangan ?></td></tr>
                    <tr><td>Hubungan Keluarga</td><td><?php echo $data->hub_keluarga ?></td></tr>
                    <tr><td >Tempat, Tanggal Lahir</td><td><?php echo "$data->tmp_tlahir, $data->tgl_tlahir"; ?></td></tr>
                    <tr><td>No NIK KK</td><td><?php echo $data->no_id ?></td></tr>
                    <tr><td>Agama</td><td><?php echo $data->agama ?></td></tr>
                    <tr><td>Pendidikan</td><td><?php echo "$data->panak"; ?></td></tr> 
                    <tr><td>Pekerjaan</td><td><?php echo $data->pekerjaan ?></td></tr>
                    <tr><td>No BPJS</td><td><?php echo $data->no_bpjs ?></td></tr> 
                    <tr><td>Status Tunjangan</td><td><?php if($data->sts_tunjangan == "Yes"){echo "Ditanggung";}else{echo "Tidak Ditanggung";} ?></td></tr> 
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report</h3>
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
                    <h2>Training Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                      <table id="datatable-buttons" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th><center>Nomor</center></th>
                          <th><center>Judul Training</center></th>
                          <th><center>Tempat</center></th>
                          <th><center>Tanggal Mulai</center></th>
                          <th><center>Tanggal Selesai</center></th>
                          <th><center>Scan File</center></th>
                          <th width="8%"><center>Aksi</center></th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       foreach ($training as $data) {
                        echo "
                        <tr>
                        <td>$data->no_perjanjian</td>
                        <td>$data->judul_training</td>
                        <td>$data->tempat_training</td>
                        <td>";echo $newDate = date("d M Y", strtotime($data->tgl_m_training)); echo"</td>
                        <td>"; echo $newDate = date("d M Y", strtotime($data->tgl_a_training)); echo"</td>
                        <td>"; ?><a href='<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>'  target="__blank"><?php echo $data->scan_perjanjian; ?></a></td>
                        <td><center>
                       <a href="<?php echo base_url()?>index.php/Training/training_detail/<?php echo $data->recid_training ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a></center></td>
                        <?php  } ?>
                    </tbody>
                  </table>
          <!--/ Content Table -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
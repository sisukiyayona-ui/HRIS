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
                    <h2>Rekapitulasi Training Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                      <div class="table-responsive">
                      <table id="t_dinamis" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th><center>No</center></th>
                          <th><center>SERTIFIKASI/ PELATIHAN/ SHARING KNOWLEDGE</center></th>
                          <th><center>KOMPETENSI</center></th>
                          <th><center>TRAINER / LEMBAGA</center></th>
                          <th><center>JUMLAH PESERTA</center></th>
                          <th><center>PELAKSANAAN</center></th>
                         
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       $no = 1;
                       foreach ($training as $data) {?>
                        <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data->judul_training ?></td>
                        <td><?php echo  $data->komp ?></td>
                        <td><?php echo $data->trainer ." / ". $data->lembaga ?></td>
                        <td><?php echo $data->jml ?></td>
                        <td><?php echo $data->tgl_m_training;?></td>
                       </tr>
                        <?php } ?>
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
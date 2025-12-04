<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <h3> Absen Tidak Lengkap <?php echo $sejak ." s/d ". $sampai ?></h3>

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
                    <h2><a href="<?php echo base_url()?>Karyawan/tidak_lengkap"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Absensi Karyawan Tidak Lengkap</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table class="table table-bordered" id="t_default">
                      <thead>
                      <th>Tanggal</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      </thead>
                      <tbody>
                        <?php foreach ($nama as $data) { ?>
                          <tr>
                          <td><?php echo $data->DATE_WORK ?></td>  
                          <td><?php echo $data->NIK ?></td>  
                          <td><?php echo $data->nama_karyawan ?></td>
                          <td><?php echo $data->TIME_IN ?></td>
                          <td><?php echo $data->TIME_OUT ?></td>
                          </tr>  
                        <?php } ?>
                      </tbody>
                     </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
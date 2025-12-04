<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report Tidak Absen <?php echo $tgl ?></h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/r_notexist"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Tidak Absen Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table class="table table-bordered" id="t_default">
                      <thead>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Bagian</th>
                      </thead>
                      <tbody>
                        <?php foreach ($kosong as $ks) { ?>
                          <tr>
                          <td><?php echo $ks[2] ?></td>  
                          <td><?php echo $ks[0] ?></td>  
                          <td><?php echo $ks[1] ?></td>
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
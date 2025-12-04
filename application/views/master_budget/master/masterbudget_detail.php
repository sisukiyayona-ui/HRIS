<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Master Budget</h3>
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
                   <h2><a href="<?php echo base_url()?>Karyawan/masterbudget_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Master Budget</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="t_kar" class="table table-striped table-bordered">
                       <?php foreach ($master as $key ) { } ?>
                        <tr><td><b>Tahun</b></td><td><b><?php echo $key->tahun ?></b></td></tr>
                        <tr><td><b>Bagian</b></td><td><b><?php echo $key->nama_bag ?></b></td></tr>
                        <tr><td colspan="2"></td></tr>
                        <tr>
                          <th><center>Bulan</center></th>
                          <th><center>Jumlah Jam </center></th>
                        </tr>
                      <tbody>
                       <?php 
                       foreach ($budget as $data) {
                        echo "
                        <tr>
                        <td><center>$data->bulan</center></td>
                        <td><center>".round($data->jml_jam)."</center></td></tr>
                       ";?>
                        <?php } ?>
                        <tr><td colspan="1"><center><b>Total jam</b></center></td><td><b><center><?php echo round($key->total) ?></center></b></td></tr>

                    </tbody>
                     </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
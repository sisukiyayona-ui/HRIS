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
                    <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Detail Total Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Bagian</th>
                          <th>Department</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                         $i = 1;
                       foreach ($karyawan as $data) {
                        echo "
                        <tr>
                        <td>$i</td>
                        <td>$data->indeks_hr</td>
                        <td>$data->nama_department</td>
                        <td>$data->total</td>
                        </tr>";
                        $i++;
                      }
                      ?>
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
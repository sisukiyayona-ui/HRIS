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
                        <h4>Master Budget <?php echo $dept ?> Group Tahun <?php echo $tahun ?></h4>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="t_budget" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                            <th><center>Tahun</center></th>
                          <th><center>Bagian</center></th>
                          <th><center>Total Jam</center></th>
                          <th><center>Dept Group</center></th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       foreach ($budget->result() as $data) {?>
                        <tr>
                            <td><?php echo $data->tahun?></td>
                        <td><?php echo $data->indeks_hr?></td>
                        <td><?php echo round($data->mb)?></td>
                        <td><?php echo $data->dept_group?></td>
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
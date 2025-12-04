<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Closing Absensi - Karyawan (<?php echo $tahun ?>)</h3>
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
                    <h2>Closing</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php 
                    $bulan = array();
                    $tahun = $tahun;
                    ?>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                    <table class="table table-bordered table-stripped">
                      <thead>
                        <tr><th><center>Bulan</center></th>
                          <th><center>Status Closing</center></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        for($i = 1; $i<=12; $i++)
                        {
                          $month_num = $i;
                          $month_name = date("F", mktime(0, 0, 0, $month_num, 10));
                          $bln =  date('m',strtotime($month_name));
                          $curmonth = date("F");
                          array_push($bulan, $bln);  

                          $cl = $this->db->query("SELECT * from closing_karyawan where tahun = '$tahun' and bulan = '$i' group by bulan");
                          if($cl->num_rows() > 0)
                          {
                            foreach ($cl->result() as $cl) {

                                echo "<tr><td><center>$month_name</center></td><td><center><button type='button' class = 'btn btn-danger btn-sm' disabled>Close</button></td></center></tr>";
                            }
                          }else
                          {
                              $b1 =  date('m',strtotime($month_name));   // set angka bulan
                              $b2 =  date('m',strtotime($curmonth));   // set angka bulan
                              if($b1 > $b2)
                              {
                                echo "<tr><td><center>$month_name</center></td><td><center><button type='button' class = 'btn btn-warning btn-sm' disabled>Soon</button></center></td></tr>";
                              }else
                              {
                               echo "<tr><td><center>$month_name</center></td>
                               <td><center><a href=";echo base_url()."Absen/closing_process/$month_name><button type='button' class = 'btn btn-success btn-sm'>Open</button></a></center></td></tr>";
                              }
                              //  if($month_name == $curmonth)
                              //  {
                              //   echo "<tr><td><center>$month_name</center></td>
                              //   <td><center><a href=";echo base_url()."Absen/closing_process/$month_name><button type='button' class = 'btn btn-success btn-sm'>Open</button></a></center></td></tr>";
                              // }
                              // else{

                              //   echo "<tr><td><center>$month_name</center></td><td><center><button type='button' class = 'btn btn-warning btn-sm' disabled>Soon</button></center></td></tr>";
                              // }
                            }
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
        </div>
        <!-- /page content -->
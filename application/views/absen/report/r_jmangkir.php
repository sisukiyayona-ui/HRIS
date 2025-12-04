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
                    <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Jumlah Ketidakhadiran Karyawan Periode <?php echo $sejak ?> s/d <?php echo $sampai ?></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
                          <th>Total</th>
                          <th>P1 + MA</th>
                          <th>S1D</th>
                          <th>CUTI</th>
                          <th>H1</th>
                          <th>H2</th>
                          <th>P1</th>
                          <th>P3</th>
                          <th>P4</th>
                          <th>MANGKIR</th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                         $i = 1;
                       foreach ($nama->result() as $data) {
                        echo "
                        <tr>
                        <td><center>$i</center></td>
                        <td>$data->nik</td>
                        <td>$data->nama_karyawan</td>
                        <td>$data->nama_bag</td>
                        <td><center>$data->total</center></td>";
                        $tot = $data->P1 + $data->MANGKIR; 
                        if( $tot >= 3){
                        echo"<td style='background-color:yellow; color:#000;'><center><b>$tot</b></center></td>";
                        }else{
                          echo"<td><center>$tot</center></td>";
                        } echo"</center></td>
                        <td><center>$data->SID</center></td>
                        <td><center>$data->CUTI</center></td>
                        <td><center>$data->H1</center></td>
                        <td><center>$data->H2</center></td>";
                        if($data->P1 >= 3){
                        echo"<td style='background-color:yellow; color:#000;'><center><b>$data->P1</b></center></td>";
                        }else{
                          echo"<td><center>$data->P1</center></td>";
                        }
                        echo"
                        <td><center>$data->P3</center></td>
                        <td><center>$data->P4</center></td>";
                        if($data->MANGKIR >= 3){
                        echo"<td style='background-color:yellow; color:#000;'><center><b>$data->MANGKIR</b></center></td>";
                        }else{
                          echo"<td><center>$data->MANGKIR</center></td>";
                        }
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
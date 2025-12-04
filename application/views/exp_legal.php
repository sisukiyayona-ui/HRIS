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
                    <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Perjanjian Expired</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="responsive">
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th>No Perjanjian</th>
                          <th>Judul Perjanjian</th>
                          <th>Tanggal Berlaku</th>
                          <th>Tanggal Selesai</th>
                          <th>Jenis</th>
                          <th>Status</th>
                          <th>Scan File</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       foreach ($legal as $data) {
                        echo "
                        <tr>
                        <td>$data->no_perjanjian</td>
                        <td>$data->judul_perjanjian</td>
                        <td>";echo $newDate = date("d M Y", strtotime($data->tgl_m_legal)); echo"</td>
                        <td>"; if($data->tgl_a_legal == '0000-00-00'){echo "00-00-0000";}else{echo $newDate = date("d M Y", strtotime($data->tgl_a_legal));}  echo"</td>
                        <td>$data->jenis_perjanjian</td>
                        <td>$data->sts_legal</td>
                        <td>"; ?><a href='<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>'  target="__blank"><?php echo substr($data->scan_perjanjian, 0,20); ?></a></td>
                        <td><center>
                        <a href="<?php echo base_url()?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><button class="btn btn-info"><span class='fa fa-edit'></span></button></a></center></td>
                        <?php  } ?>
                    </tbody>
                  </table>
                </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content  -->
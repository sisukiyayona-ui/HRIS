<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Training Detail</h3>
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
                    <h2><i class="fa fa-arrow-circle-o-left" onclick="goBack()"></i> | Detail Training Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php foreach ($training as $data) {
                    } ?>
                      <table class="table table-striped table-bordered">
                          <tr>
                            <td>No SK</td>
                            <td><a href='<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>'  target="__blank"><?php echo $data->no_perjanjian ?></a></td>
                          </tr>
                          <tr>
                            <td>Topik Training</td>
                            <td><?php echo $data->judul_training ?></td>
                          </tr>
                          <tr>
                            <td>Tempat</td>
                            <td><?php echo $data->tempat_training ?></td>   
                          </tr>
                          <tr>
                            <td>Tanggal</td>
                            <td><?php echo $newDate = date("d M Y", strtotime($data->tgl_m_training)); ?>s/d 
                                <?php echo $newDate = date("d M Y", strtotime($data->tgl_a_training));?></td>
                          </tr>
                          <tr>
                            <td>Peserta</td>
                            <td> <table class="table table-striped table-bordered">
                              <thead>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Bagian</th>
                                <th>Jabatan</th>
                              </thead>
                              <tbody>
                                <?php
                                foreach ($karyawan as $key) {?>
                                  <tr>
                                    <td><?php echo $key->nik ?></td>
                                    <td><?php echo $key->nama_karyawan ?></td>
                                    <td><?php echo $key->nama_bag ?></td>
                                    <td><?php echo $key->nama_jbtn ?></td>
                                  </tr>
                                <?php  }
                                ?>
                              </tbody>
                            </table></td>
                          </tr>
                          <tr>
                            <td>Keterangan</td>
                             <td><?php echo $data->ket ?></td>
                          </tr>
                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
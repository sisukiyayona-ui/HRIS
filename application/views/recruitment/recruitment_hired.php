<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Detail Hired Recruitment</h3>
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
                  <div class="x_content">
                   <!-- Content Table -->
                   <h4><b>FPTK</b></h4>
                   <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>Tanggal Pengajuan</center></th>
                        <th><center>Pengaju</center></th>
                        <th><center>Posisi Yang Diajukan</center></th>
                        <th><center>Jumlah Kebutuhan</center></th>
                        <th><center>Tanggal Efektif</center></th>
                        <th><center>Alasan</center></th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                     $no = 1;
                     foreach ($fptk as $dataf) { ?>
                     <tr>
                     <td><center><?php echo $dataf->tgl_pengajuan ?></center></td>
                     <td><center><?php echo $dataf->pengaju ?></center></td>
                     <td><center><?php echo $dataf->nama_bag ?> / <?php echo $dataf->nama_jbtn ?></center></td>
                     <td><center><?php echo $dataf->jml_kebutuhan ?></center></td>
                     <td><center><?php echo $dataf->tgl_efektif ?></center></td>
                     <td><center><?php echo $dataf->alasan ?></center></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>

              <h4><b>KFPTK</b></h4>
                   <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>Status Appoved</center></th>
                        <th><center>Approved By HC</center></th>
                        <th><center>Approved By BOD</center></th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                     foreach ($kfptk as $datak) { ?>
                     <tr>
                     <td><center><?php echo $datak->status ?></center></td>
                     <td><center><?php echo $datak->tgl_hc ?> </center></td>
                     <td><center><?php echo $datak->tgl_bod ?></center></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>

               <h4><b>RECRUITMENT</b></h4>
                   <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>Judul Recruitment</center></th>
                        <th><center>Tanggal Open</center></th>
                        <th><center>Tanggal Closed</center></th>
                        <th><center>Sasaran</center></th>
                        <th><center>Jobdesk</center></th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                     foreach ($recruitment as $datar) { ?>
                     <tr>
                     <td><center><?php echo $datar->judul_recruitment ?></center></td>
                     <td><center><?php echo $datar->tgl_open ?> </center></td>
                     <td><center><?php echo $datar->tgl_closed ?></center></td>
                     <td><center><?php echo $datar->sasaran ?></center></td>
                     <td><center><?php echo $datar->jobdesk ?></center></td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>

              <h4><b>KARYAWAN HIRED</b></h4>
                   <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>No</center></th>
                        <th><center>Nama Karyawan</center></th>
                        <th><center>Tanggal Mulai Kerja</center></th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                     $no = 1;
                     foreach ($hired->result() as $data) { ?>
                     <tr>
                     <td><center><?php echo $no++ ?></center></td>
                     <td><?php echo $data->nama_karyawan ?></td>
                     <td><center><?php echo $data->tgl_m_kerja ?> </center></td>
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
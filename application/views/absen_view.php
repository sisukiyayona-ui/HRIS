<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Absensi Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                     <a class="btn btn-primary" href="<?php echo base_url()?>Karyawan/absen_absen">
                      <i class="fa fa-plus"></i> Absen
                    </a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- Content Table -->
                    <table id="t_absen" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Tanggal Kerja</th>
                          <th>Jam Masuk</th>
                          <th>Jam Pulang</th>
                          <th>Jenis</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
                       foreach ($absen as $data) {
                        echo "
                        <tr>
                        <td>$data->nik</td>
                        <td>$data->nama_karyawan</td>
                        <td>$data->tgl_work</td>
                        <td>$data->time_in</td>
                        <td>$data->time_out</td>
                        <td>$data->jenis_absen</td>
                        <td><center>";?>
                        <a href="<?php echo base_url()?>Karyawan/absen_update/<?php echo $data->nik ?>/<?php echo $data->tgl_work ?>"><button class="btn btn-info"><span class='fa fa-edit'></span></button></a>
                        <?php 
                      }
                      ?>
                    </tbody>
                  </table>
                  <!--/ Content Table -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
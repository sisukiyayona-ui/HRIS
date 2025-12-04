<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Histori Absensi Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="x_panel">
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- Content Table -->
                    <table id="t_absen" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
                          <th>Jabatan</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Diagnosa</th>
                          <th>Kategori</th>
                          <th>Detail Sakit</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
                        $no = 0;
                       foreach ($absen->result() as $data) {?>
                        <tr>
                        <td><?php echo $no = $no+1?></td>
                        <td><?php echo $data->nik ?></td>
                        <td><?php echo $data->nama_karyawan ?></td>
                        <td><?php echo $data->indeks_hr ?></td>
                        <td><?php echo $data->indeks_jabatan ?></td>
                        <td><?php echo $data->tanggal ?></td>
                        <td><center><?php echo $data->jenis." - ".$data->keterangan?></center></td>
                        <td><?php echo $data->diagnosa ?></td>
                        <td><?php echo $data->kategori ?></td>
                        <td><?php echo $data->ket_sakit ?></td>
                        <td><?php echo $data->ket ?></td>
                      </tr>
                      <?php } ?>
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

<script type="application/javascript">  
    
   </script>
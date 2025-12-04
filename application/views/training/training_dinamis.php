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
                    <h2>Report Training Karyawan</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                      <div class="table-responsive">
                      <table id="t_dinamis" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th><center>No</center></th>
                          <th><center>Nomor Form</center></th>
                          <th><center>Judul Training</center></th>
                          <th><center>Jenis Training</center></th>
                          <th><center>Kategori</center></th>
                          <th><center>Kompetensi</center></th>
                          <th><center>Lembaga</center></th>
                          <th><center>Trainer</center></th>
                          <th><center>Tanggal Mulai</center></th>
                          <th><center>Tanggal Selesai</center></th>
                          <th><center>Jumlah Jam</center></th>
                          <th><center>Tempat</center></th>
                          <th><center>Metoda</center></th>
                          <th><center>Berbayar</center></th>
                          <th><center>Biaya</center></th>
                          <th><center>Alasan Pengajuan</center></th>
                          <th><center>Scan Direksi</center></th>
                          <th><center>Sertifikat</center></th>
                          <th><center>Point</center></th>
                          <th><center>Peserta</center></th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       $no = 1;
                       foreach ($training->result() as $data) {
                        echo "
                        <tr>
                        <td>".$no++."</td>
                        <td>$data->no_form</td>
                        <td>$data->judul_training</td>
                        <td>$data->jenis_training</td>
                        <td>$data->kategori</td>
                        <td>$data->kompetensi</td>
                        <td>$data->lembaga</td>
                        <td>$data->trainer</td>
                        <td>";echo $data->tgl_m_training; echo"</td>
                        <td>";$data->tgl_a_training; echo"</td>
                        <td>$data->jml_jam</td>
                        <td>$data->tempat_training</td>
                        <td>$data->metoda</td>
                        <td>$data->berbayar</td>
                        <td>$data->biaya</td>
                        <td>$data->alasan_pengajuan</td>
                        <td>"; ?><a href='<?php echo base_url()?>images/training/<?php echo $data->scan_direksi; ?>'  target="__blank"><?php echo $data->scan_direksi; ?></a></td>
                        <td><a href='<?php echo base_url()?>images/training/<?php echo $data->sertifikat; ?>'  target="__blank"><?php echo $data->sertifikat; ?></a></td>
                        <td><?php echo $data->poin + $data->poin_plus ?></td>
                        <td><?php echo $data->nama_karyawan ?></td>
                        <?php  } ?>
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
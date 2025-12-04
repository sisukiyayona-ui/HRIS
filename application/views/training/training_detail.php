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

                    <!-- yang mengajukan -->
                    <h4><span class="label label-success">Data Pemohon</span></h4>
                    <div class="item form-group">
                      <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                      </label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <table class="table table-striped table-bordered">
                          <?php foreach ($pengaju as $aju) { ?>
                            <tr><td colspan="2"><b>Diajukan Oleh : </b></td></tr>
                            <tr><td>Nama </td><td><?php echo $aju->nama_karyawan ?></td></tr>
                            <tr><td>Bagian </td><td><?php echo $aju->indeks_hr ?></td></tr>
                            <tr><td>Jabatan </td><td><?php echo $aju->indeks_jabatan?></td></tr>
                            <tr><td>Department</td><td><?php echo $aju->nama_department ?></td></tr>
                            <tr><td>Tanggal Pengajuan </td><td><?php echo $newDate = date("d M Y", strtotime($aju->tgl_pengajuan));?></td></tr>
                            <tr><td>Atasan </td><td><?php echo $aju->nama_atasan ?></td></tr>
                          <?php } ?>

                        </table>
                      </div>
                    </div>
                  </div>

                    <hr>
                    <div class="x_content">
                    <h4><span class="label label-success">Data Pengajuan Training</span></h4>
                    <div class="item form-group">
                      <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                      </label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <table class="table table-striped table-bordered">
                          <?php
                          foreach ($training as $t) { 
                              $status = $t->status_acc;
                            ?>
                            <tr><td colspan="2"><b>Detail Pengajuan Training : </b></td></tr>
                            <tr><td>Jenis Training </td><td><?php echo $t->jenis_training ?></td></tr>
                            <tr><td>Kategori </td><td><?php echo $t->kategori ?></td></tr>
                            <tr><td>Topik Training </td><td><?php echo $t->judul_training?><input type="hidden" name="judul_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->judul_training ?>"></td></tr>
                            <tr><td>Lembaga</td><td><?php echo $t->lembaga ?></td></tr>
                            <tr><td>Trainer</td><td><?php echo $t->trainer ?></td></tr>
                            <tr><td>Tanggal Mulai </td><td><?php echo $newDate = date("d M Y",  strtotime($t->tgl_m_training));?>
                            <input type="hidden" name="tgl_m_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->tgl_m_training ?>"></td></tr>
                            <tr><td>Tanggal Selesai </td><td><?php echo $newDate = date("d M Y",  strtotime($t->tgl_a_training));?>
                            <input type="hidden" name="tgl_a_training" class="form-control col-md-7 col-xs-12" value="<?php echo $t->tgl_a_training ?>"></td></tr>
                            <tr><td>Jumlah Jam</td><td><?php echo $t->jml_jam ?></td></tr>
                            <tr><td>Tempat Training</td><td><?php echo $t->tempat_training ?></td></tr>
                            <tr><td>Biaya</td><td><?php echo $t->biaya ?></td></tr>
                            <tr><td>Scan Brosur</td><td><a href='<?php echo base_url()?>images/training/<?php echo $t->scan_brosur; ?>'  target="__blank"><?php echo $t->scan_brosur ?></a></td></tr>
                            <tr><td>Alasan Pengajuan</td><td><?php echo $t->alasan_pengajuan ?></td></tr>
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
                                      <td><?php echo $key->indeks_hr ?></td>
                                      <td><?php echo $key->indeks_jabatan ?></td>
                                    </tr>
                                  <?php  }
                                  ?>
                                </tbody>
                              </table></td>
                            </tr>
                          <?php }?>
                        </table>
                      </div>
                    </div>
                  </div>

                  <?php
                    if($status == NULL)
                    { ?>
                       <hr>
                    <div class="x_content">
                    <h4><span class="label label-success">Data Belum Di Validasi HC</span></h4>
                    <?php }
                    else
                    { 
                      if($status == "Denied")
                      {
                        echo "Pengajuan Pelatihan Ini Ditolak";
                      }else{?>

                         <hr>
                    <div class="x_content">
                    <h4><span class="label label-success">Data Validasi HC</span></h4>
                    <div class="item form-group">
                      <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                      </label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <table class="table table-striped table-bordered">
                          <?php
                          foreach ($hc as $h) { ?>
                            <tr><td colspan="2"><b>Detail Validasi HC : </b></td></tr>
                           <!--  <tr><td>Nomor Surat </td><td><?php echo $h->no_perjanjian ?></td></tr>
                            <tr><td>File Surat </td><td><a href='<?php echo base_url()?>images/legal/<?php echo $h->scan_perjanjian; ?>'  target="__blank"><?php echo $h->scan_perjanjian ?></a></td></tr> -->
                            <tr><td>Kompetensi </td><td><?php echo $h->komp?></td></tr>
                            <tr><td>Metoda </td><td><?php echo $h->metoda?></td></tr>
                            <tr><td>Sertifikasi</td><td><?php echo $h->sertifikat ?></td></tr>
                            <tr><td>Training Need Analysist</td><td><?php echo $h->tna ?></td></tr>
                            <tr><td>Evaluasi </td><td><?php echo $h->evaluasi ?></td></tr>
                            <tr><td>Atasan </td><td><?php echo $h->evaluasi ?></td></tr>
                            <tr><td>Poin</td><td><?php echo $h->poin ?></td></tr>
                            <tr><td>Status validasi</td><td><?php echo $h->status_acc ?></td></tr>
                            <tr><td>Tanggal Validasi HC</td><td><?php echo $newDate = date("d M Y",  strtotime($h->acc_hc));?></td></tr>
                            <tr><td>Tanggal Validasi Direksi</td><td><?php echo $newDate = date("d M Y",  strtotime($h->acc_direksi));?></td></tr>
                            <tr><td>Scan Validasi Direksi</td><td><a href='<?php echo base_url()?>images/training/<?php echo $h->scan_direksi; ?>'  target="__blank"><?php echo $h->scan_direksi ?></a></td></tr>
                            <tr><td>Keterangan</td><td><?php echo $h->ket ?></td></tr>
                          <?php }?>
                        </table>
                      </div>
                    </div>
                  </div>

                      <?php } ?>

                      
                      
                    <?php }?>

                 

                </div>
              </div>
            </div>
          </div>
        <!-- /page content -->
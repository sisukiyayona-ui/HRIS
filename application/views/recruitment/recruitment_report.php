<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report Recruitment</h3>
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
                   <div class="table-responsive">
                    <table id="tr_hr" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>NO</center></th>
                        <th><center>Judul / Posisi</center></th>
                        <th><center>Tanggal Pengajuan FPTK</center></th>
                        <th><center>Tanggal Permintaan Efektif Kerja</center></th>
                        <th><center>Leadtime User</center></th>
                        <th><center>Jumlah Kebutuhan</center></th>
                        <th><center>Tanggal Real Efektif Kerja</center></th>
                        <th><center>Leadtime Proses</center></th>
                        <th><center><= 49 Hari (35 HK)</center></th>
                        <th><center>>= 49 Hari (35 HK)</center></th>
                        <th><center>Aktual Lead Time</center></th>
                        <th><center>Karyawan Hired</center></th>
                      </tr>
                    </thead>


                    <tbody>
                     <?php 
                     $no = 1;
                     foreach ($recruitment->result() as $data) { ?>
                     <tr>
                     <td><center><?php echo $no++; ?></center></td>
                     <td><center><?php echo $data->judul_recruitment ?></center></td>
                     <td><center><?php echo $data->tgl_fptk ?></center></td>
                     <td><center><?php echo $data->efektif_by_fptk ?></center></td>
                     <td><center><?php echo $data->leadtime_user ?> Hari</center></td>
                     <td><center><?php echo $data->jml_kebutuhan ?></center></td>
                     <td><center><?php echo $data->tgl_m_kerja ?></center></td>
                     <td><center><?php echo $data->leadtime ?> Hari</center></td>
                     <?php 
                      if($data->leadtime >= 45)
                      {?>
                        <td></td>
                        <td><center>&#10004;</center></td>
                      <?php }else{?>
                        <td><center>&#10004;</center></td>
                        <td></td>
                    <?php }
                     ?>
                     <td><center><?php if($data->leadtime >= 45) echo "Tidak Tercapai"; else{echo "Tercapai";} ?></center></td>
                     <td><?php 
                          $jml = $this->db->query("SELECT count(p2.recid_pelamar) as jml_hire, r2.recid_recruitment from pelamar p2 join seleksi s2 on p2.recid_pelamar = s2.recid_pelamar join test t2 on t2.recid_test = s2.recid_test join recruitment r2 on r2.recid_recruitment = t2.recid_recruitment where s2.status = 'Terima' and r2.recid_recruitment = '$data->recid_recruitment'");
                          foreach($jml->result() as $hire)
                          {
                            $jml_hire = $hire->jml_hire;
                          }?> 
                          <a href="<?php echo base_url()?>index.php/Recruitment/Karyawan_hired/<?php echo $data->recid_recruitment?>"><?php echo $jml_hire; ?> Orang</a></td>
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
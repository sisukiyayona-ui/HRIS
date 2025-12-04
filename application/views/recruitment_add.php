<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Recruitment</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | FPTK Disetujui</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="t_kar" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th><center>Tanggal Pengajuan</center></th>
                          <th><center>Pemohon</center></th>
                          <th><center>Alasan</center></th>
                          <th><center>Tanggal Efektif</center></th>
                          <th><center>Tanggal Approve</center></th>
                          <th><center>Aksi</center></th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
                       foreach ($fptk as $data) {
                        echo "
                        <tr>
                        <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_pengajuan));echo"</center></td>
                        <td><center>"; echo strtolower($data->nama_karyawan); echo"</center></td>
                        <td>$data->alasan</td>
                        <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_efektif));echo"</center></td>
                        <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_approve));echo"</center></td>
                        <td><center>";
                        if($role == '1' or $role == '2' or $role == '5'){?>
                          <a href="<?php echo base_url()?>Karyawan/recruitment_insert/<?php echo $data->recid_kfptk?>" data-toggle="tooltip" data-placement="top" title="Create"><button class="btn btn-primary  btn-xs"><span class='fa fa-bullhorn'></span></button></a>
                        <?php } ?>
                      <?php } ?>

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
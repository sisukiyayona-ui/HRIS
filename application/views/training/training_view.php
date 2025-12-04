<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Training</h3>
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
                    <a class="btn btn-info btn-sm" href="<?php echo base_url()?>index.php/Training/training_insert2">
                      <i class="fa fa-plus"></i>  | Training
                    </a> 
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                      <table id="t_desc" class="table table-striped table-bordered">
                       <thead>
                        <tr>
                          <th><center>Create By</center></th>
                          <th><center>Judul Training</center></th>
                          <th><center>Tanggal Pengajuan</center></th>
                          <th><center>Tanggal Mulai</center></th>
                          <th><center>Tanggal Selesai</center></th>
                          <th><center>Scan Direksi</center></th>
                          <th><center>Status Acc</center></th>
                          <th><center>Status</center></th>
                          <th width="8%"><center>Aksi</center></th>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       foreach ($training as $data) {
                        echo "
                        <tr>
                        <td>$data->nama_karyawan</td>
                        <td>$data->judul_training</td>
                        <td>"; echo $data->tgl_pengajuan; echo "</td>
                        <td>"; echo $data->tgl_m_training; echo"</td>
                        <td>"; echo $data->tgl_a_training; echo"</td>
                        <td>";?><a href='<?php echo base_url()?>images/training/<?php echo $data->scan_direksi; ?>'  target="__blank"><?php echo $data->scan_direksi; ?></a></td>
                        <td><?php echo $data->status_acc; ?></td>
                        <td><?php echo $data->status; ?></td>
                        <td><center>
                          <?php 
                            if($data->status_acc == '') // tombol ceklis u/ validasi HC, edit untuk yg mengajukan
                            {
                              if( $role == '2' or $role == '1' )
                              {?> <a href="<?php echo base_url()?>index.php/Training/training_acc/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Approve"><button class="btn btn-primary btn-xs" >A</button></a>

                             <a href="<?php echo base_url()?>index.php/Training/train_denied/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Denied"><button class="btn btn-danger btn-xs">D</button></a>
                             <?php } ?>
                                 
                            <a href="<?php echo base_url()?>index.php/Training/training_update/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>  

                             <a href="<?php echo base_url()?>index.php/Training/training_detail_ajuan/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Detail"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>   

                             <a href="<?php echo base_url()?>Down_ms/download_training_ms/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Print"><button class="btn btn-default btn-xs"><span class='fa fa-print'></span></button></a></center></td>                       
                           <?php }
                           else{  // tombol edit u/ validasi hc 
                            if(($role == '2' or $role == '1') and $data->status == 'Open')
                              {?> <a href="<?php echo base_url()?>index.php/Training/train_acc_update/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                            <?php } ?>
                              <a href="<?php echo base_url()?>index.php/Training/training_detail/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Detail"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>  
                           <?php } ?>
                               <!-- <a href="<?php echo base_url()?>Down_ms/download_training_ms/<?php echo $data->recid_training ?>" data-toggle="tooltip" data-placement="top" title="Print"><button class="btn btn-default btn-xs"><span class='fa fa-print'></span></button></a></center></td> -->

                        <?php  } ?>
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
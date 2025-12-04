<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Pengajuan Lembur</h3>
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
                   <a class="btn btn-info btn-sm" href="<?php echo base_url()?>Karyawan/plembur_insert">
                      <i class="fa fa-plus"></i>  | Pengajuan Lembur
                    </a> 
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_desc" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <?php if($this->session->userdata('role_id') == '1'){ ?>
                          <th><center>Id Lembur</center></th>
                          <th><center>Id Realisasi</center></th>
                        <?php } ?>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Kategori</center></th>
                        <th><center>Jumlah Jam</center></th>
                        <th><center>Pekerjaan</center></th>
                        <th><center>Keterangan</center></th>
                        <th><center>Alasan Over</center></th>
                        <th><center>Status Realisasi</center></th>
                        <th><center>Aksi</center></th>
                      </tr>
                    </thead>


                    <tbody>
                     <?php 
                     foreach ($realisasi as $data) { echo "
                     <tr>";
                      if($this->session->userdata('role_id') == '1'){
                          echo "<th><center>$data->recid_plembur</center></th>
                                <th><center>$data->recid_lembur</center></th>";
                     }
                     echo "
                     <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_lembur));echo"</center></td>
                     <td><center>$data->nama_bag</center></td>
                     <td><center>";
                      if($data->kategori == 'Kgagal'){echo "Komponen Kegagalan";}
                      else if($data->kategori == 'Kterlambat'){echo "Komponen Terlambat Pengiriman";}
                      else if($data->kategori == 'Ekirim'){echo "Pengiriman Barang";}
                      else if($data->kategori == 'Ddinas'){echo "Dinas Driver";}
                      else{echo $data->kategori; }
                      echo"</center></td>
                     <td><center>$data->total_jam</center></td>
                     <td><center>$data->pekerjaan</center></td>
                     <td><center>$data->keterangan</center></td>
                     <td><center>$data->alasan_over</center></td>
                     <td><center>";if($data->recid_lembur == null){echo "<font color = 'red'>Belum</font>";}else{echo "Sudah";}echo"</center></td>
                     <td><center>"; if($data->recid_lembur == null){ ?>
                      <a href="<?php echo base_url()?>Karyawan/plembur_update/<?php echo $data->recid_plembur?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs"><span class='fa fa-pencil'></span></button></a>
                       <a href="<?php echo base_url()?>Karyawan/plembur_delete/<?php echo $data->recid_plembur?>" data-toggle="tooltip" data-placement="top" title="Delete"><button class="btn btn-danger btn-xs"><span class='fa fa-trash'></span></button></a>
                    <?php  }else{ ?>
                         <a href="<?php echo base_url()?>Karyawan/plembur_update/<?php echo $data->recid_plembur?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs" disabled="disabled"><span class='fa fa-pencil'></span></button></a>
                     <?php }
                  } ?>

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
<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Open Recruitment</h3>
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
                   <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Open Recruitment</h2>
                 <?php if($role == '1' or $role == '2'){ ?>
                  <div class="x_title">
                   <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Kandidat
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url()?>Karyawan/pelamar_view"><i class='fa fa-search-plus'></i> View Pelamar</a></li>
                        <li><a href="<?php echo base_url()?>Karyawan/pelamar_insert"><i class="fa fa-plus"></i> Add Pelamar</a></li>
                      </ul>
                    </div>
                    <div class="btn-group">
                      <a href="<?php echo base_url()?>Karyawan/recruitment_add">
                        <button class="btn btn-success btn-sm" type="button">Recruitment
                        </button>
                      </a>
                      </div>
                    <div class="btn-group">
                      <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown">FPTK
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                         <li><a href="<?php echo base_url()?>Karyawan/fptk_view"><i class='fa fa-search-plus'> </i> View FPTK</a></li>
                         <li><a href="<?php echo base_url()?>Karyawan/fptk_insert"><i class="fa fa-plus"></i> Add FPTK</a></li>
                        </ul>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                <?php } ?>
                  <div class="x_content">
                   <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_rec" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>Judul</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Jabatan</center></th>
                        <th><center>Tanggal Efektif</center></th>
                        <th><center>Sasaran</center></th>
                        <th><center>Status</center></th>
                        <th><center>Aksi</center></th>
                      </tr>
                    </thead>


                    <tbody>
                     <?php 
                     foreach ($recruitment as $data) { echo "
                     <tr>
                     <td><center>$data->judul_recruitment</center></td>
                     <td><center>$data->nama_bag</center></td>
                     <td><center>$data->nama_jbtn</center></td>
                     <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_efektif));echo"</center></td>
                     <td><center>$data->sasaran</center></td>
                     <td><center>$data->status</center></td>
                     <td><center>";
                     if($role == '1' or $role == '2' or $role == '5'){ ?>
                     
                      <a href="<?php echo base_url()?>Karyawan/recruitment_update/<?php echo $data->recid_recruitment?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs"><span class='fa fa-pencil'></span></button></a>
                      <a href="<?php echo base_url()?>Karyawan/recruitment_detail/<?php echo $data->recid_recruitment?>" data-toggle="tooltip" data-placement="top" title="Detail"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>
                    <?php } ?>
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
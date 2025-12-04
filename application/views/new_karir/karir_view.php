<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Data Karir Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                  <?php if($role == '1' or $role == '2' or $role == '4' ){ ?>
                    <a class="btn btn-app" href="<?php echo base_url()?>index.php/Karir/karir_insert">
                      <i class="fa fa-plus"></i> Tambah Data
                    </a>
                  <?php } ?>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!--Add content to the page ...-->
                      <!-- Content Table -->
                      <div class="responsive">
                      <table id="tr_hr2" class="table table-striped table-bordered" width="100%">
                       <thead>
                        <tr>
                          <th>Recid</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Nomor SK</th>
                          <th>Jenis SK</th>
                          <th>Bagian</th>
                          <th>Jabatan</th>
                          <th>Tanggal Berlaku</th>
                          <th>No SK</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
                       foreach ($karir as $data) {
                        echo "
                        <tr>
                        <td>$data->recid_karir</td>
                        <td>$data->nik</td>
                        <td>$data->nama_karyawan</td>
                        <td>$data->no_perjanjian</td>
                        <td>$data->kategori</td>
                        <td>$data->indeks_hr</td>
                        <td>$data->indeks_jabatan</td>
                        <td>";
                        echo  $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                        echo"</td>
                        <td>$data->no_perjanjian<br>";?><a href='<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>'  target="__blank"><?php echo $data->scan_perjanjian; ?></a></td>
                        <td><center>
                        <?php if($role == '1' or $role == '2' or $role == '4') {?>
                        <a href="<?php echo base_url()?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                        <a href="<?php echo base_url()?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><button class="btn btn-danger btn-xs"><span class='fa fa-trash'></span></button></a>
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
<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Kandidat</h3>
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
                 <?php if($role == '1' or $role == '2'){ ?>
                  <div class="x_title">
                    <h2><a href="<?php echo base_url()?>Karyawan/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Data Kandidat</h2>
                    <div class="clearfix"></div>
                  </div>
                <?php } ?>
                  <div class="x_content">
                   <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_kar" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>No KTP</center></th>
                        <th><center>Nama Kandidat</center></th>
                        <th><center>Tgl Lahir</center></th>
                        <th><center>Alamat</center></th>
                        <th><center>No Telp</center></th>
                        <th><center>Email</center></th>
                        <th><center>Referensi</center></th>
                        <th><center>Aksi</center></th>
                      </tr>
                    </thead>


                    <tbody>
                     <?php 
                     foreach ($pelamar as $data) {
                      echo "
                      <tr>
                      <td>$data->no_ktp</td>
                      <td>$data->nama_pelamar</td>
                      <td>$data->tgl_lahir</td>
                      <td>$data->alamat</td>
                      <td>$data->no_telp</td>
                      <td>$data->email</td>
                      <td>";
                       if($data->jenis_referensi == 'Karyawan'){
                          $karyawan = $this->db->query("SELECT * from karyawan k left join bagian b on k.recid_bag = b.recid_bag left join jabatan j on k.recid_jbtn = j.recid_jbtn where k.sts_aktif='Aktif' and recid_karyawan = '$data->referensi'")->result();
                          foreach ($karyawan as $karyawan) {
                            $nama = $karyawan->nama_karyawan;
                           }
                           echo strtolower($nama);
                        }else{
                          echo $data->referensi;
                        }
                      echo"</td>
                      <td><center>";
                      if($role == '1' or $role == '2' or $role == '5'){?>
                        <a href="<?php echo base_url()?>Karyawan/pelamar_update/<?php echo $data->recid_pelamar?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
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
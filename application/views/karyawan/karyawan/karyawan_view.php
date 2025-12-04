<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <?php if ($role == '1' or $role == '2') { ?>
            <div class="x_title">
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_insert">
                <i class="fa fa-plus"></i> Karyawan
              </a>
              <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>Karyawan/karir_insert">
                <i class="fa fa-plus"></i> Karir
              </a>
              <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Karyawan/training_insert">
                <i class="fa fa-plus"></i> Training
              </a>
              <a class="btn btn-warning btn-sm" href="<?php echo base_url() ?>Karyawan/tunjangan_insert">
                <i class="fa fa-plus"></i> Tanggungan
              </a>
              <div class="clearfix"></div>
            </div>
          <?php } ?>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_kar" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>
                      <center>Nik</center>
                    </th>
                    <th>
                      <center>Nama Karyawan</center>
                    </th>
                    <th>
                      <center>Bagian</center>
                    </th>
                    <th>
                      <center>Jabatan</center>
                    </th>
                    <th>
                      <center>Golongan</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    if ($data->recid_bag == 0) {
                      $nama_bag = "-";
                    } else {
                      $nama_bag = $data->nama_bag;
                    }

                    if ($data->recid_jbtn == 0) {
                      $nama_jbtn = "-";
                    } else {
                      $nama_jbtn = $data->nama_jbtn;
                    }
                    echo "
            <tr>
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$nama_bag</td>
            <td>$nama_jbtn</td>
            <td>$$data->nama_golongan</td>
            <td><center>";
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_updatebeta/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                    <?php } ?>
                    <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_detail/<?php echo $data->recid_karyawan ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>
                    <?php } else {
                      echo "-";
                    } ?>
                  <?php
                  }
                  ?>

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
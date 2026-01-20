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
          <div class="x_title">
            <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_insert">
                <i class="fa fa-plus"></i> | Karyawan
              </a>
              <?php if ($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '26') { ?>
                <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>index.php/Karir/karir_insert">
                  <i class="fa fa-plus"></i> | Karir
                </a>
              <?php } ?>
              <!--  <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Karyawan/training_insert">
              <i class="fa fa-plus"></i>  | Training
            </a>  -->
            <?php } ?>
            <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5') { ?>
              <a class="btn btn-warning btn-sm" href="<?php echo base_url() ?>Karyawan/tunjangan_insert">
                <i class="fa fa-plus"></i> | Tanggungan
              </a>
              <a class="btn btn-default btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-plus"></i> | Renew Data
              </a>
              <a class="btn btn-danger btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-plus"></i> | Updating Data (<?php echo $notif_edit ?>)
              </a>
              <a class="btn btn-info btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-upload"></i> | Ekspedisi Berkas
              </a>

            <?php } ?>
            <?php if ($tingkatan >= 6) { ?>
              <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_self_update/<?php echo $this->session->userdata('recid_karyawan') ?>">
                <i class="fa fa-plus"></i> | Self Update
              </a>
            <?php } ?>

          <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
              <a class="btn btn-dark btn-sm" href="<?php echo base_url('karyawan/export') ?>">
                  <i class="fa fa-file-excel-o"></i> | Export Data Karyawan
              </a>
          <?php } ?>

            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_kar" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <?php
                    if ($role == '1') { ?>
                      <th>
                        <center>RECID</center>
                      </th>
                    <?php }
                    ?>
                    <th>
                      <center>Nik</center>
                    </th>
                    <th>
                      <center>Nama</center>
                    </th>
                    <th>
                      <center>Jenis Kelamin</center>
                    </th>
                    <th>
                      <center>Jabatan</center>
                    </th>
                    <th>
                      <center>Bagian</center>
                    </th>
                    <th>
                      <center>Sub Bagian</center>
                    </th>
                    <th>
                      <center>Departemen</center>
                    </th>
                    <th>
                      <center>Status Karyawan</center>
                    </th>
                    <th>
                      <center>Tanggal Masuk</center>
                    </th>
                    <th>
                      <center>Tanggal Keluar</center>
                    </th>
                    <th>
                      <center>Tanggal Habis Kontrak</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    // Format dates
                    $tgl_masuk = ($data->tgl_m_kerja && $data->tgl_m_kerja != '0000-00-00') ? date("d M Y", strtotime($data->tgl_m_kerja)) : '-';
                    $tgl_keluar = (isset($data->tgl_a_kerja) && $data->tgl_a_kerja && $data->tgl_a_kerja != '0000-00-00') ? date("d M Y", strtotime($data->tgl_a_kerja)) : '-';
                    $tgl_habis_kontrak = ($data->tgl_akhir_kontrak && $data->tgl_akhir_kontrak != '0000-00-00') ? date("d M Y", strtotime($data->tgl_akhir_kontrak)) : '-';
                    
                    echo "
            <tr>
            ";
                    if ($role == '1') {
                      echo "<td>$data->recid_karyawan</td>";
                    }
                    echo "
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$data->jenkel</td>
            <td>" . (isset($data->nama_jbtn) ? $data->nama_jbtn : '-') . "</td>
            <td>" . (isset($data->nama_bag) ? $data->nama_bag : '-') . "</td>
            <td>" . (isset($data->sub_bag) ? $data->sub_bag : '-') . "</td>
            <td>" . (isset($data->departemen) ? $data->departemen : '-') . "</td>
            <td>$data->sts_aktif</td>
            <td>$tgl_masuk</td>
            <td>$tgl_keluar</td>
            <td>$tgl_habis_kontrak</td>
            <td><center>"; ?>
                    <?php
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_update/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                    <?php }
                    ?>
                    <a href="<?php echo base_url() ?>Karyawan/karyawan_detail/<?php echo $data->recid_karyawan ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a></td>
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
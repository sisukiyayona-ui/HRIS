<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $menu ?></h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_dinamis" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <?php if ($role == '1') { ?>
                      <th>
                        <center>recid_karyawan</center>
                      </th>
                    <?php } ?>
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
                      <center>Department</center>
                    </th>
                    <th>
                      <center>Direktorat</center>
                    </th>
                    <th>
                      <center>Jabatan</center>
                    </th>
                    <th>
                      <center>Golongan</center>
                    </th>
                    <th>
                      <center>Atasan1</center>
                    </th>
                    <th>
                      <center>Atasan2</center>
                    </th>
                    <th>
                      <center>Jenis Sanksi</center>
                    </th>
                    <th>
                      <center>Keterangan</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    $bagian = $data->indeks_hr;
                    $bagian = substr($bagian, strpos($bagian, " ") + 1);

                    $jabatan = $data->indeks_jabatan;
                    $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                    $golongan = $data->nama_golongan;
                    $golongan = substr($golongan, strpos($golongan, " ") + 1);

                    $struktur = $data->nama_struktur;
                    $struktur = substr($struktur, strpos($struktur, " ") + 1);
                    if ($data->recid_bag == 0) {
                      $bagian = "-";
                    } else {
                      $bagian = $bagian;
                    }

                    if ($data->recid_jbtn == 0) {
                      $jabatan = "-";
                    } else {
                      $jabatan = $jabatan;
                    }
                    echo "<tr>";
                    if ($role == '1') { ?>
                      <td><?php echo $data->recid_karyawan ?></td>
                  <?php }
                    echo "
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$bagian</td>
            <td>$data->nama_struktur</td>
            <td>$data->nama_department</td>
            <td>$jabatan</td>
            <td>$golongan</td>
            <td>$data->atasan1</td>
            <td>$data->atasan2</td>
            <td>$data->jenis_sanksi</td>
            <td>$data->note</td>
           ";
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
<!-- /page content
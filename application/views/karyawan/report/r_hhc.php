<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Report Data Karyawan</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/r_hc"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Human Resource</h2>

            <div class="clearfix"></div>
          </div>
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
                      <center>Usia</center>
                    </th>
                    <th>
                      <center>Masa Kerja</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($report as $data) {
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
                    echo "
                <tr>
                <td>$data->nik</td>
                <td>$data->nama_karyawan</td>
                <td>$bagian</td>
                <td>$jabatan</td>
                <td>$golongan</td>
                <td>$data->umur</td>
                <td>$data->masker thn</td>
                <td><center>"; ?>
                    <?php if ($role == '1' or $role == '2' or $role == '5' or $role == '6') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_detail/<?php echo $data->rekar ?>"><button class="btn btn-success"><span class='fa fa-search-plus'></span></button></a>
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
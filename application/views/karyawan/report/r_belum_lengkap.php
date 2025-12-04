<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan Belum Lengkap</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <h2><a href="<?php echo base_url() ?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Data Karyawan Belum Lengkap</h2>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_dinamis" class="table table-striped table-bordered">
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
                      <center>Status Aktif</center>
                    </th>
                    <th>
                      <center>Tanggal Mulai Kerja</center>
                    </th>
                    <th>
                      <center>Tempat, Tgl Lahir</center>
                    </th>
                    <th>
                      <center>Gender</center>
                    </th>
                    <th>
                      <center>Golongan Darah</center>
                    </th>
                    <th>
                      <center>No KTP</center>
                    </th>
                    <th>
                      <center>No NPWP</center>
                    </th>
                    <th>
                      <center>Agama</center>
                    </th>
                    <th>
                      <center>Pendidikan</center>
                    </th>
                    <th>
                      <center>No Jamsostek</center>
                    </th>
                    <th>
                      <center>No BPJS Kesehatan</center>
                    </th>
                    <th>
                      <center>No BPJS Tenaga Kerja</center>
                    </th>
                    <th>
                      <center>Status Nikah</center>
                    </th>
                    <th>
                      <center>Status Penunjang</center>
                    </th>
                    <th>
                      <center>Alamat KTP</center>
                    </th>
                    <th>
                      <center>Alamat Sekarang</center>
                    </th>
                    <th>
                      <center>Telp</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    $bagian = $data->indeks_hr;
                    $bagian = $bagian ? substr($bagian, strpos($bagian, " ") + 1) : '';

                    $jabatan = $data->indeks_jabatan;
                    $jabatan = $jabatan ? substr($jabatan, strpos($jabatan, " ") + 1) : '';

                    $golongan = $data->nama_golongan;
                    $golongan = $golongan ? substr($golongan, strpos($golongan, " ") + 1) : '';

                    $struktur = $data->nama_struktur;
                    $struktur = $struktur ? substr($struktur, strpos($struktur, " ") + 1) : '';

                    if ($role == '1' or $role == '2') {
                      $bagian = $data->indeks_hr;
                      $jabatan = $data->indeks_jabatan;
                      $golongan = $data->nama_golongan;
                      $struktur = $data->nama_struktur;
                    }

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
            <td>$golongan</td>
            <td>$data->sts_aktif</td>
            <td>$data->tgl_m_kerja</td>
            <td>$data->tmp_lahir, $data->tgl_lahir</td>
            <td>$data->jenkel</td>
            <td>$data->gol_darah</td>
            <td>$data->no_ktp</td>
            <td>$data->no_npwp</td>
            <td>$data->agama</td>
            <td>$data->pendidikan</td>
            <td>$data->no_jamsos</td>
            <td>$data->no_bpjs_kes</td>
            <td>$data->no_bpjs_tk</td>
            <td>$data->sts_nikah</td>
            <td>$data->sts_penunjang</td>
            <td>$data->alamat_ktp</td>
            <td>$data->alamat_skrg</td>
            <td>$data->telp1 / $data->telp2 </td>
            <td><center>";
                    if ($role == '1' or $role == '2' or $role == '5') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_updatebeta/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                    <?php } ?>
                    <?php if ($role == '1' or $role == '2' or $role == '5' or $role == '6') { ?>
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
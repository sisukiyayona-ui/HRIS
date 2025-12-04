<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Dinamis Data Tunjangan</h3>
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
                      <center>Nama Tanggungan</center>
                    </th>
                    <th>
                      <center>Hubungan Keluarga</center>
                    </th>
                    <th>
                      <center>Tempat Lahir</center>
                    </th>
                    <th>
                      <center>Tanggal Lahir</center>
                    </th>
                    <th>
                      <center>NIK KK</center>
                    </th>
                    <th>
                      <center>Agama</center>
                    </th>
                    <th>
                      <center>Pendidikan</center>
                    </th>
                    <th>
                      <center>Pekerjaan</center>
                    </th>
                    <th>
                      <center>No BPJS</center>
                    </th>
                    <th>
                      <center>Status</center>
                    </th>
                    <!-- 
              <th><center>Masa Kerja</center></th>
              <th><center>Gaji</center></th> -->
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    $bagian = $data->indeks_hr;
                    $bagian = substr($bagian, strpos($bagian, ". ") + 1);

                    $jabatan = $data->indeks_jabatan;
                    $jabatan = substr($jabatan, strpos($jabatan, ". ") + 1);

                    $golongan = $data->nama_golongan;
                    $golongan = substr($golongan, strpos($golongan, ". ") + 1);

                    $struktur = $data->nama_struktur;
                    $struktur = substr($struktur, strpos($struktur, ". ") + 1);

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
                    $hub_keluarga = "Karyawan";
                    $no_id = $data->no_ktp;
                    $sts_tunjangan = "Yes";
                    $nama_tunjangan = $data->nama_karyawan;
                    $pekerjaan = "Karyawan";
                    echo "
            <tr>
            <td>$data->nik</td>
            <td>$nama_tunjangan</td>";
            if($role == 1 or $role == 2)
            {
            echo " <td>$data->indeks_hr</td>
            <td>$data->nama_struktur</td>
            <td>$data->nama_department</td>
            <td>$data->indeks_jabatan</td>
            <td>$data->nama_golongan</td>";
            }else{
              echo " <td>$bagian</td>
            <td>$struktur</td>
            <td>$data->nama_department</td>
            <td>$jabatan</td>
            <td>$golongan</td>";
            }
           echo "
            <td>$nama_tunjangan</td>
            <td>$hub_keluarga</td>
            <td>$data->tmp_lahir</td>
            <td>$data->tgl_lahir</td>
            <td>$no_id</td>
            <td>$data->agama</td>
            <td>$data->pendidikan</td>
            <td>$pekerjaan</td>
            <td>$data->no_bpjs_kes</td>
            <td>$sts_tunjangan</td>
            </tr>";

                    /* DATA TANGGUNGAN */

                    $tanggungan = $this->db->query("SELECT * FROM `tunjangan` where recid_karyawan = '$data->recid_karyawan' and tunjangan_delete = '0' order by hub_keluarga asc")->result();
                    foreach ($tanggungan as $t) {
                      echo "
             <tr>
             <td>$data->nik</td>
             <td>$data->nama_karyawan</td>
             <td>$bagian</td>
             <td>$struktur</td>
             <td>$data->nama_department</td>
             <td>$jabatan</td>
             <td>$golongan</td>
             <td>$t->nama_tunjangan</td>
             <td>$t->hub_keluarga</td>
             <td>$t->tmp_tlahir</td>
             <td>$t->tgl_tlahir</td>
             <td>$t->no_id</td>
             <td>$t->agama</td>
             <td>$t->pendidikan</td>
             <td>$t->pekerjaan</td>
             <td>$t->no_bpjs</td>
             <td>$t->sts_tunjangan</td>
             </tr>";
                    }
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
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h4><?php echo $menu ?></h4>
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
              <table id="t_absen" class="table table-striped table-bordered">
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
                      <center>Sub Bagian</center>
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
                    
                    <?php if ($role != '30') { ?>
                      <th>
                        <center>Atasan1</center>
                      </th>
                      <th>
                        <center>Atasan2</center>
                      </th>
                      <th>
                        <center>Status</center>
                      </th>
                      <th>
                        <center>Bulanan</center>
                      </th>
                      <th>
                        <center>Status Aktif</center>
                      </th>
                      <th>
                        <center>Tanggal Mulai Kerja</center>
                      </th>
                      <th>
                        <center>Tanggal Akhir Kerja</center>
                      </th>
                    <?php } ?>
                    <th>
                      <center>Tempat</center>
                    </th>
                    <th>
                      <center>Tgl Lahir</center>
                    </th>
                    <th>
                      <center>Gender</center>
                    </th>
                    <?php if ($role != '30') { ?>
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
                    <?php } ?>
                    <th>
                      <center>Pendidikan</center>
                    </th>
                    <th>
                      <center>Jurusan</center>
                    </th>
                    <?php
                    if ($role != '30') { ?>
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
                        <center>Email</center>
                      </th>
                      <th>
                        <center>Email Internal</center>
                      </th>
                      <th>
                        <center>Telp</center>
                      </th>
                      <th>
                        <center>Profile DISC</center>
                      </th>
                      <th>
                        <center>Pattern Type</center>
                      </th>
                      <th>
                        <center>Profile Personality</center>
                      </th>
                    <?php }
                    ?>

                    <!-- 
              <th><center>Masa Kerja</center></th>
              <th><center>Gaji</center></th> -->
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    // echo $data->indeks_hr;
                    $bagian = $data->indeks_hr;
                    if ($bagian && $bagian != "") {
                      $bagian = substr($bagian, strpos($bagian, " ") + 1);
                    }

                    $jabatan = $data->indeks_jabatan;
                    if ($jabatan && $jabatan != "") {
                      $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);
                    }

                    $golongan = $data->nama_golongan;
                    if ($golongan && $golongan != "") {
                      $golongan = substr($golongan, strpos($golongan, " ") + 1);
                    }

                    $struktur = $data->nama_struktur;
                    if ($struktur && $struktur != "") {
                      $struktur = substr($struktur, strpos($struktur, " ") + 1);
                    }

                    $nama_department = $data->nama_department;
                    if ($nama_department && $nama_department != "") {
                      $nama_department = substr($nama_department, strpos($nama_department, " ") + 1);
                    }


                    if ($data->recid_bag == 0) {
                      $indeks_hr = "-";
                    } else {
                      $indeks_hr = $data->indeks_hr;
                    }

                    if ($data->recid_jbtn == 0) {
                      $indeks_jabatan = "-";
                    } else {
                      $indeks_jabatan = $data->indeks_jabatan;
                    }

                    echo "<tr>";
                    if ($role == '1') { ?>
                      <td><?php echo $data->recid_karyawan ?></td>
                    <?php } ?>
                    <td><?php echo $data->nik ?></td>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <?php
                    if ($role == '1' or $role == '2'  or $role == '5'  or $role == '25' or $role == '27') { ?>
                      <td><?php echo $data->indeks_hr ?></td>
                      <td><?php echo $data->sub_bag ?></td>
                      <td><?php echo $data->nama_struktur ?></td>
                      <td><?php echo $data->nama_department ?></td>
                      <td><?php echo $data->indeks_jabatan ?></td>
                      <td><?php echo $data->nama_golongan ?></td>
                    <?php } else { ?>
                      <td><?php echo $bagian ?></td>
                      <td><?php echo $data->sub_bag ?></td>
                      <td><?php echo $struktur ?></td>
                      <td><?php echo $nama_department ?></td>
                      <td><?php echo $jabatan ?></td>
                      <td><?php echo $golongan ?></td>
                    <?php }
                    ?>
                    <?php if ($role != '30') { ?>
                      <td><?php echo $data->atasan1 ?></td>
                      <td><?php echo $data->atasan2 ?></td>
                      <td><?php echo $data->sts_jabatan ?></td>
                      <td><?php echo $data->bulanan ?></td>
                      <td><?php echo $data->sts_aktif ?></td>
                      <td><?php echo $data->tgl_m_kerja ?></td>
                      <td><?php echo $data->tgl_a_kerja ?></td>
                    <?php } ?>
                    <td><?php echo $data->tmp_lahir ?></td>
                    <td><?php echo $data->tgl_lahir ?></td>
                    <td><?php echo $data->jenkel ?></td>
                    <?php
                    if ($role != '30') { ?>
                      <td><?php echo $data->gol_darah ?></td>
                      <td><?php echo $data->no_ktp ?></td>
                      <td><?php echo $data->no_npwp ?></td>
                      <td><?php echo $data->agama ?></td>
                    <?php }
                    ?>
                    <td><?php echo $data->pendidikan ?></td>
                    <td><?php echo $data->jurusan ?></td>
                    <?php
                    if ($role != '30') { ?>
                      <td><?php echo $data->no_jamsos ?></td>
                      <td><?php echo $data->no_bpjs_kes ?></td>
                      <td><?php echo $data->no_bpjs_tk ?></td>
                      <td><?php echo $data->sts_nikah ?></td>
                      <td><?php echo $data->sts_penunjang ?></td>
                      <td><?php echo $data->alamat_ktp ?></td>
                      <td><?php echo $data->alamat_skrg ?></td>
                      <td><?php echo $data->email ?></td>
                      <td><?php echo $data->email_tsgi ?></td>
                      <td><?php echo $data->telp1 . " / " . $data->telp2  ?></td>
                      <td><?php echo $data->profile_disc ?></td>
                      <td><?php echo $data->pattern_type ?></td>
                      <td><?php echo $data->profile_type ?></td>
                    <?php } ?>
                  <?php }
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
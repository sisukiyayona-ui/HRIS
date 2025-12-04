<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Upah Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          Data Upah Karyawan
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
                      <center>Department</center>
                    </th>
                    <th>
                      <center>Struktur</center>
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
                      <center>Status</center>
                    </th>
                    <th>
                      <center>Masa Kerja</center>
                    </th>
                    <th>
                      <center>Kategori MK</center>
                    </th>
                    <th>
                      <center>Gaji</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    $bagian = $data->indeks_hr;
                    $bagian = $bagian ? substr($bagian, strpos($bagian, ". ") + 1) : '';

                    $jabatan = $data->indeks_jabatan;
                    $jabatan = $jabatan ? substr($jabatan, strpos($jabatan, ". ") + 1) : '';

                    $golongan = $data->nama_golongan;
                    $golongan = $golongan ? substr($golongan, strpos($golongan, ". ") + 1) : '';

                    $struktur = $data->nama_struktur;
                    $struktur = $struktur ? substr($struktur, strpos($struktur, ". ") + 1) : '';

                    if ($data->recid_bag == 0) {
                      $bagian = "-";
                    } else {
                      if($role == '1'or $role == '2' )
                      {
                        $bagian = $data->indeks_hr;
                      } else{
                        $bagian = $bagian;
                      }
                    }

                    if ($data->recid_jbtn == 0) {
                      $jabatan = "-";
                    } else {
                      if ($role == '1' or $role == '2') {
                        $jabatan = $data->indeks_jabatan;
                      } else {
                        $jabatan = $jabatan;
                      }
                    }
                    if ($role == '1' or $role == '2') {
                      $golongan = $data->nama_golongan;
                    } else {
                      $golongan = $golongan;
                    }
                    echo "
            <tr>
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$data->nama_department</td>
            <td>$data->nama_struktur</td>
            <td>$bagian</td>
            <td>$jabatan</td>
            <td>$golongan</td>
            <td>$data->sts_penunjang</td>
            <td>";
                    if ($data->tgl_m_kerja == null) {
                      echo "-";
                    } else if ($data->tgl_m_kerja == "0000-00-00") {
                      echo "-";
                    } else {
                      // echo $newDate = date("d M Y", strtotime($data->tgl_m_kerja));
                      $diff  = date_diff(date_create($data->tgl_m_kerja), date_create());
                      echo $diff->format(' %Y thn %m bln %d hari');
                    }
                    echo "</td>"; ?>
            <?php
                  $mulai_kerja = $data->tgl_trisula;
                    $newDate = date("d M Y", strtotime($data->tgl_trisula));
                    $diff  = date_diff(date_create($data->tgl_trisula), date_create());
                   
                    $masker_tahun = $diff->format('%y');
                   // echo $masker_tahun;
                    // $diff->format(' ( %Y tahun %m bulan %d hari )');
                    if($masker_tahun >=0 and $masker_tahun < 1)
                    {
                      $kat_masker = 'MK 0';
                    }
                    else if($masker_tahun >= 1 and $masker_tahun <=5)
                    {
                      $kat_masker = 'MK 1';
                    }else if($masker_tahun > 5 and $masker_tahun <=10)
                     {
                        $kat_masker = 'MK 6';
                    }else if($masker_tahun >= 11 and $masker_tahun <=15)
                    {
                      $kat_masker = 'MK 11';
                    }else if($masker_tahun >= 16 and $masker_tahun <=20)
                    {
                      $kat_masker = 'MK 16';
                    }else{
                      $kat_masker = 'MK 21';
                    }
                  ?>
                  <td><?php echo $kat_masker ?></td>
                   <td><?php echo $data->gaji?></td>
                  <?php }?>

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
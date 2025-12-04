<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Atasan Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <?php if ($role == '1' or $role == '2') { ?>
            <div class="x_title">
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_insert">
                <i class="fa fa-plus"></i> | Karyawan
              </a>
              <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>Karir/karir_insert">
                <i class="fa fa-plus"></i> | Karir
              </a>
              <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Karyawan/training_insert">
                <i class="fa fa-plus"></i> | Training
              </a>
              <a class="btn btn-warning btn-sm" href="<?php echo base_url() ?>Karyawan/tunjangan_insert">
                <i class="fa fa-plus"></i> | Tanggungan
              </a>
              <a class="btn btn-default btn-sm" href="<?php echo base_url() ?>Karyawan/renew">
                <i class="fa fa-plus"></i> | Renew Data
              </a>
              <a class="btn btn-danger btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_listupdate">
                <i class="fa fa-plus"></i> | Updating Data (<?php echo $notif_edit ?>)
              </a>
              <div class="clearfix"></div>
            </div>
          <?php } ?>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <?php
            if ($role != '1' and $role != '2' and $role != '3' and $role != '4' and $role != '5' and $role != '6') { ?>
              <h2>Biodata Karyawan Saat Ini</h2>
            <?php } ?>
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
                      <center>Atasan</center>
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
                    echo "
            <tr>
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$bagian</td>
            <td>$jabatan</td>
            <td>$golongan</td>
            <td>
              <select name='recid_atasan'  class='form-control col-md-12 col-xs-12' data-live-search='true' required='required'>
                  <option value=''>-- Pilih --</option>";
                    foreach ($atasan_k as $option) {
                      if ($data->recid_atasan == $option->recid_atasan) {
                        echo "<option value='$option->recid_karyawan' selected>$option->nama_karyawan</option>";
                      } else {
                        echo "<option value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                      }
                    }
                    echo "</select>
            </td>
            <td><center></td></tr>";
                  }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
  function atasan_change(id) {
    var str = id;
    var recid_karyawan = str.substring(3);
    opsi = "atasan" + recid_karyawan;
    recid_atasan = document.getElementById(opsi).value;
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Karyawan/change_atasan", // Isi dengan url/path file php yang dituju
      data: {
        recid_karyawan: recid_karyawan,
        recid_atasan: recid_atasan
      }, // data yang akan dikirim ke file yang dituju
      success: function(data, response) {
        if (data == "ok") {
          alert(data);
          location.reload();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        console.log("textStatus: " + textStatus);
        console.log("errorThrown: " + errorThrown);
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }
</script>
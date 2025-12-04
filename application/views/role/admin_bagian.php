<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>PIC Admin Bagian</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <table id="t_absen" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Bagian</th>
                  <th>Departemen</th>
                  <th>Direktorat</th>
                  <th>PIC</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($bagian as $data) {
                  if ($role == '1' or $role == '2') {
                    $bagian = $data->indeks_hr;
                    $dept = $data->nama_struktur;
                  } else {
                    $bagian = $data->indeks_hr;
                    $bagian = substr($bagian, strpos($bagian, ". ") + 1);

                    $dept = $data->nama_struktur;
                    $dept = substr($dept, strpos($dept, ". ") + 1);
                  }
                ?>
                  <tr>
                    <td><?php echo $bagian ?><input type="hidden" name="recid_bag"></td>
                    <td><?php echo $dept ?></td>
                    <td><?php echo $data->nama_department ?></td>
                    <td><select class="form-control" id="recid_karyawan<?php echo $data->recid_bag ?>">
                        <?php
                        echo "<option value=''>-- Pilih --</option>";
                        foreach ($karyawan as $k) {
                          if ($data->recid_karyawan == $k->recid_karyawan) {
                            echo "<option value='$k->recid_karyawan' selected>$k->nama_karyawan</option>";
                          } else {
                            echo "<option value='$k->recid_karyawan'>$k->nama_karyawan</option>";
                          }
                        }
                        ?>
                      </select></td>
                    <td>
                      <center><button type="button" id="btn<?php echo $data->recid_bag ?>" class='btn btn-primary btn-sm' onclick="admin_bag(this.id)">Save</button></center>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- /page content -->

<script>
  function admin_bag(id) {
    var bag = id;
    var recid_bag = bag.substring(3);
    opsi = "recid_karyawan" + recid_bag;
    recid_karyawan = document.getElementById(opsi).value;
    console.log(recid_bag);
    console.log(recid_karyawan);
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Absen/update_admin_bagian", // Isi dengan url/path file php yang dituju
      data: {
        recid_karyawan: recid_karyawan,
        recid_bag: recid_bag
      }, // data yang akan dikirim ke file yang dituju
      success: function(data, response) {
        if (response == "success") {
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
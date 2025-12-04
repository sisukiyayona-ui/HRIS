<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Generate User</h3>
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
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Nama Karyawan</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <th>Role</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
                <?php 
                foreach ($karyawan as $data) { ?>
                  <tr>
                  <td><?php echo $data->nama_karyawan ?><input type="hidden" name="recid_karyawan"></td>
                  <td><?php echo $data->indeks_hr ?></td>
                  <td><?php echo $data->indeks_jabatan ?></td>
                  <td><select id="recid_role<?php echo $data->recid_karyawan ?>"  class=" form-control ">
                    <?php
                    echo "<option value=''>-- Pilih --</option>";
                    foreach ($roles as $role) {
                      echo "<option value='$role->recid_role'>$role->nama_role</option>";
                    }
                    ?>
                  </select>
                  <td><center><button type="button" id="btn<?php echo $data->recid_karyawan?>" class='btn btn-primary btn-xs' onclick = "gen_user(this.id)">Generate</button></td></tr>
                  </form>
                <?php }?>

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
  function gen_user(id)
  {
    var str = id;
    var recid_karyawan = str.substring(3);
    opsi = "recid_role"+recid_karyawan;
    recid_role = document.getElementById(opsi).value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/auto_generate", // Isi dengan url/path file php yang dituju
            data: {recid_karyawan :recid_karyawan, recid_role: recid_role}, // data yang akan dikirim ke file yang dituju
            success: function(data, response){ 
             if(data == "ok")
             {
              location.reload();
             }
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              console.log("textStatus: " + textStatus);
              console.log("errorThrown: " + errorThrown);
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

</script>
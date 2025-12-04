<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>User Login</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user">
              <i class="fa fa-plus"></i> | Tambah Data
            </a>

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
                  <th>Golongan</th>
                  <th>Penempatan</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Role</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
                <?php
                foreach ($user as $data) {
                  echo "
                  <tr>
                  <td>$data->nama_karyawan</td>
                  <td>$data->indeks_hr</td>
                  <td>$data->indeks_jabatan</td>
                  <td>$data->nama_golongan</td>
                  <td>$data->penempatan</td>
                  <td>$data->username</td>
                  <td>$data->password</td>
                  <td>$data->nama_role</td>
                  <td>$data->note</td><td>";
                  echo ($data->is_delete == '0') ? "Active" : 'Not Active';
                  echo "</td><td><center>";
                  if ($data->recid_karyawan == '1189') {
                    echo "<button class='btn btn-info btn-xs' disabled><span class='fa fa-edit'></button></a>";
                  } else {
                ?>
                    <a
                      data-recid_login="<?php echo $data->recid_login ?>"
                      data-recid_karyawan="<?php echo $data->recid_karyawan ?>"
                      data-nama="<?php echo $data->nama_karyawan ?>"
                      data-username="<?php echo $data->username ?>"
                      data-password="<?php echo $data->password ?>"
                      data-role="<?php echo $data->recid_role ?>"
                      data-note="<?php echo $data->note ?>"
                      data-toggle="modal" data-target="#edit_user">
                    <?php echo "<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a>";
                  } ?>
                  <?php
                }
                  ?>

              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Account</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/user_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                <?php
                echo "<option value=''>-- Pilih --</option>";
                foreach ($karyawan as $option) {
                  echo "<option data-subtext='$option->nik' value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="username" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="username" placeholder="Username" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="password" placeholder="Password" required="required" type="password">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_user">Role User <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_role" class="form-control col-md-7 col-xs-12" required="required">
                <?php
                echo "<option value=''>-- Pilih --</option>";
                foreach ($role as $option) {
                  echo "<option value='$option->recid_role'>$option->nama_role</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
        </form>
      </div>

    </div>
  </div>
</div>
<!--/ Modal Tambah Data -->


<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Account</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/user_update" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="erecid_login" class="form-control col-md-7 col-xs-12" type="hidden" readonly="readonly" name="recid_login">
              <input id="erecid_karyawan" class="form-control col-md-7 col-xs-12" type="hidden" readonly="readonly" name="recid_karyawan">
              <input id="enama" class="form-control col-md-7 col-xs-12" type="text" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="eusername" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="username" placeholder="Username" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="epassword2" class="form-control col-md-7 col-xs-12" name="password2" type="hidden">
              <input id="epassword" class="form-control col-md-7 col-xs-12" name="password" placeholder="Password (isi bila ingin diubah)" type="password">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_user">Role User <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_role" id="erole" class="form-control col-md-7 col-xs-12">
                <?php
                foreach ($role as $option) {
                  echo "<option value='$option->recid_role'>$option->nama_role</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
        </form>
      </div>

    </div>
  </div>
</div>
<!--/ Modal Edit Data -->


<!-- /page content -->

<script>
  $("#username").change(function() {
    var username = document.getElementById("username").value;
    $.ajax({ //---------------------------------------- cek total lembur --------------------------------------------------------
      type: "POST", // 
      url: "<?php echo base_url(); ?>Karyawan/cek_uname",
      data: {
        username: username
      },
      dataType: "json",
      beforeSend: function(e) {
        if (e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response, data) { // Ketika proses pengiriman berhasil
        if (response == '1') {
          alert("Username alrady exist, please insert the  other one");
          document.getElementById("username").value = ' ';
        }
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  });
</script>
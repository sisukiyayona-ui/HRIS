<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <!-- Display flash messages -->
    <?php if($this->session->flashdata('message')): ?>
      <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?php echo $this->session->flashdata('message'); ?>
      </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>
    
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
            <!-- Debug: Tampilkan data untuk testing -->
            <!--
            <div class="alert alert-info">
              <strong>Debug Info:</strong><br>
              <?php foreach ($user as $data): ?>
                recid_login: <?php echo $data->recid_login ?>, 
                recid_karyawan: <?php echo $data->recid_karyawan ?>, 
                nama: <?php echo $data->nama_karyawan ?><br>
              <?php endforeach; ?>
            </div>
            -->
            
            <!-- Content Table -->
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Nama Karyawan</th>
                  <th>Jabatan</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Role</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($user as $data): ?>
                  <tr>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <td><?php echo $data->indeks_jabatan ?></td>
                    <td><?php echo $data->username ?></td>
                    <td><?php echo $data->password ?></td>
                    <td><?php echo $data->nama_role ?></td>
                    <td><?php echo $data->note ?></td>
                    <td><?php echo ($data->is_delete == '0') ? "Active" : 'Not Active'; ?></td>
                    <td>
                      <center>
                        <?php if ($data->recid_karyawan == '1189'): ?>
                          <button class='btn btn-info btn-xs' disabled><span class='fa fa-edit'></span></button>
                          <button class='btn btn-danger btn-xs' disabled><span class='fa fa-trash'></span></button>
                        <?php else: ?>
                          <!-- Edit Button -->
                          <a
                            data-recid_login="<?php echo $data->recid_login ?>"
                            data-recid_karyawan="<?php echo $data->recid_karyawan ?>"
                            data-nama="<?php echo $data->nama_karyawan ?>"
                            data-username="<?php echo $data->username ?>"
                            data-password="<?php echo $data->password ?>"
                            data-role="<?php echo $data->recid_role ?>"
                            data-note="<?php echo $data->note ?>"
                            data-toggle="modal" data-target="#edit_user">
                            <button class='btn btn-info btn-xs'><span class='fa fa-edit'></span></button>
                          </a>

                          <!-- Delete Button - Mengirim recid_karyawan -->
                          <button onclick="showDeleteModal(
                            <?php echo $data->recid_karyawan ?>, 
                            '<?php echo htmlspecialchars($data->username, ENT_QUOTES) ?>', 
                            '<?php echo htmlspecialchars($data->nama_karyawan, ENT_QUOTES) ?>',
                            '<?php echo $data->recid_karyawan ?>'  <!-- Tambahkan ini untuk ditampilkan di modal -->
                          )" 
                                  class='btn btn-danger btn-xs'>
                            <span class='fa fa-trash'></span>
                          </button>
                        <?php endif; ?>
                      </center>
                    </td>
                  </tr>
                <?php endforeach; ?>
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Account</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/user_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                <option value=''>-- Pilih --</option>
                <?php foreach ($karyawan as $option): ?>
                  <option data-subtext='<?php echo $option->nik ?>' value='<?php echo $option->recid_karyawan ?>'>
                    <?php echo $option->nama_karyawan ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="username" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="username" placeholder="Username" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="password" placeholder="Password" required="required" type="password">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_user">Role User <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_role" class="form-control col-md-7 col-xs-12" required="required">
                <option value=''>-- Pilih --</option>
                <?php foreach ($role as $option): ?>
                  <option value='<?php echo $option->recid_role ?>'><?php echo $option->nama_role ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes">
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Account</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/user_update" novalidate>
          <input type="hidden" id="erecid_login" name="recid_login">
          <input type="hidden" id="erecid_karyawan" name="recid_karyawan">
          
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="enama" class="form-control col-md-7 col-xs-12" type="text" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="eusername" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="username" placeholder="Username" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="epassword2" class="form-control col-md-7 col-xs-12" name="password2" type="hidden">
              <input id="epassword" class="form-control col-md-7 col-xs-12" name="password" placeholder="Password (isi bila ingin diubah)" type="password">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_user">Role User <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_role" id="erole" class="form-control col-md-7 col-xs-12">
                <?php foreach ($role as $option): ?>
                  <option value='<?php echo $option->recid_role ?>'><?php echo $option->nama_role ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes">
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Data - Diperbaiki untuk menampilkan recid_karyawan -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus User</h4>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus user ini?</p>
        <div class="alert alert-warning">
          <strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan!
        </div>
        <div class="form-group">
          <label for="delete_username">Username:</label>
          <input type="text" id="delete_username" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label for="delete_nama">Nama Karyawan:</label>
          <input type="text" id="delete_nama" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label for="delete_karyawan_id">ID Karyawan:</label>
          <input type="text" id="delete_karyawan_id" class="form-control" readonly>
        </div>
        <input type="hidden" id="delete_recid_karyawan">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" onclick="deleteUser()">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Cek username saat tambah data
  $("#username").change(function() {
    var username = document.getElementById("username").value;
    $.ajax({
      type: "POST",
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
      success: function(response, data) {
        if (response == '1') {
          alert("Username sudah ada, silakan gunakan username lain");
          document.getElementById("username").value = '';
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  });
  
  // Populate edit modal with correct values when edit button is clicked
  $(document).on('click', 'a[data-target="#edit_user"]', function() {
    var recid_login = $(this).data('recid_login');
    var recid_karyawan = $(this).data('recid_karyawan');
    var nama = $(this).data('nama');
    var username = $(this).data('username');
    var password = $(this).data('password');
    var role = $(this).data('role');
    var note = $(this).data('note');
    
    $('#erecid_login').val(recid_login);
    $('#erecid_karyawan').val(recid_karyawan);
    $('#enama').val(nama);
    $('#eusername').val(username);
    $('#epassword').val('');
    $('#epassword2').val(password);
    $('#erole').val(role);
  });
});

// Variabel global untuk menyimpan data yang akan dihapus
let deleteData = {
  recid_karyawan: null,
  username: null,
  nama: null
};

// Fungsi untuk menampilkan modal delete
function showDeleteModal(recid_karyawan, username, nama, karyawan_id) {
  // Simpan data ke variabel global
  deleteData.recid_karyawan = recid_karyawan;
  deleteData.username = username;
  deleteData.nama = nama;
  
  // Isi data ke modal
  $('#delete_username').val(username);
  $('#delete_nama').val(nama);
  $('#delete_karyawan_id').val(karyawan_id);
  $('#delete_recid_karyawan').val(recid_karyawan);
  
  // Tampilkan modal
  $('#deleteModal').modal('show');
}

// Fungsi untuk menghapus user
function deleteUser() {
  if (deleteData.recid_karyawan) {
    // Tutup modal
    $('#deleteModal').modal('hide');
    
    // Tampilkan konfirmasi debug
    console.log('Menghapus recid_karyawan:', deleteData.recid_karyawan);
    console.log('URL:', "<?php echo base_url(); ?>Karyawan/user_delete/" + deleteData.recid_karyawan);
    
    // Redirect ke URL delete
    window.location.href = "<?php echo base_url(); ?>Karyawan/user_delete/" + deleteData.recid_karyawan;
  }
}

// Reset data ketika modal ditutup
$('#deleteModal').on('hidden.bs.modal', function () {
  deleteData.recid_karyawan = null;
  deleteData.username = null;
  deleteData.nama = null;
  $('#delete_username').val('');
  $('#delete_nama').val('');
  $('#delete_karyawan_id').val('');
  $('#delete_recid_karyawan').val('');
});
</script>
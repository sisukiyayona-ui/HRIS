<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_insert">
                <i class="fa fa-plus"></i> | Karyawan
              </a>
              <?php if ($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '26') { ?>
                <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>index.php/Karir/karir_insert">
                  <i class="fa fa-plus"></i> | Karir
                </a>
              <?php } ?>
              <!--  <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Karyawan/training_insert">
              <i class="fa fa-plus"></i>  | Training
            </a>  -->
            <?php } ?>
            <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5') { ?>
              <a class="btn btn-warning btn-sm" href="<?php echo base_url() ?>Karyawan/tunjangan_insert">
                <i class="fa fa-plus"></i> | Tanggungan
              </a>
              <a class="btn btn-default btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-plus"></i> | Renew Data
              </a>
              <a class="btn btn-danger btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-plus"></i> | Updating Data (<?php echo $notif_edit ?>)
              </a>
              <a class="btn btn-info btn-sm disabled" href="#" onclick="return false;">
                <i class="fa fa-upload"></i> | Ekspedisi Berkas
              </a>
              <a class="btn btn-primary btn-sm" href="<?php echo base_url()?>Status_update_import">
                <i class="fa fa-upload"></i> | Ubah Status Jadi Tetap
              </a>

            <?php } ?>
            <?php if ($tingkatan >= 6) { ?>
              <a class="btn btn-success btn-sm" href="<?php echo base_url() ?>Karyawan/karyawan_self_update/<?php echo $this->session->userdata('recid_karyawan') ?>">
                <i class="fa fa-plus"></i> | Self Update
              </a>
            <?php } ?>

          <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
              <a class="btn btn-dark btn-sm" id="exportBtn" href="javascript:void(0);" onclick="exportFilteredData()">
                  <i class="fa fa-file-excel-o"></i> | Export Data Karyawan
              </a>
          <?php } ?>

            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_kar" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <?php
                    if ($role == '1') { ?>
                      <th>
                        <center>RECID</center>
                      </th>
                    <?php }
                    ?>
                    <th>
                      <center>Nik</center>
                    </th>
                    <th>
                      <center>Nama</center>
                    </th>
                    <th>
                      <center>Jenis Kelamin</center>
                    </th>
                    <th>
                      <center>Jabatan</center>
                    </th>
                    <th>
                      <center>Bagian</center>
                    </th>
                    <th>
                      <center>Sub Bagian</center>
                    </th>
                    <th>
                      <center>Departemen</center>
                    </th>
                    <th>
                      <center>Status Karyawan</center>
                    </th>
                    <th>
                      <center>Tanggal Masuk</center>
                    </th>
                    <th>
                      <center>Tanggal Keluar</center>
                    </th>
                    <th>
                      <center>Tanggal Habis Kontrak</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  foreach ($karyawan as $data) {
                    // Format dates
                    $tgl_masuk = ($data->tgl_m_kerja && $data->tgl_m_kerja != '0000-00-00') ? date("d M Y", strtotime($data->tgl_m_kerja)) : '-';
                    $tgl_keluar = (isset($data->tgl_a_kerja) && $data->tgl_a_kerja && $data->tgl_a_kerja != '0000-00-00') ? date("d M Y", strtotime($data->tgl_a_kerja)) : '-';
                    $tgl_habis_kontrak = ($data->tgl_akhir_kontrak && $data->tgl_akhir_kontrak != '0000-00-00') ? date("d M Y", strtotime($data->tgl_akhir_kontrak)) : '-';
                    
                    echo "
            <tr>
            ";
                    if ($role == '1') {
                      echo "<td>$data->recid_karyawan</td>";
                    }
                    echo "
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$data->jenkel</td>
            <td>" . (isset($data->nama_jbtn) ? $data->nama_jbtn : '-') . "</td>
            <td>" . (isset($data->nama_bag) ? $data->nama_bag : '-') . "</td>
            <td>" . (isset($data->sub_bag) ? $data->sub_bag : '-') . "</td>
            <td>" . (isset($data->departemen) ? $data->departemen : '-') . "</td>
            <td>$data->sts_aktif</td>
            <td>$tgl_masuk</td>
            <td>$tgl_keluar</td>
            <td>$tgl_habis_kontrak</td>
            <td><center>"; ?>
                    <?php
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_update/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                    <?php }
                    ?>
                    <a href="<?php echo base_url() ?>Karyawan/karyawan_detail/<?php echo $data->recid_karyawan ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>
                    <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '26') { ?>
                      <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#kontrakActionModal" 
                              data-recid-karyawan="<?php echo $data->recid_karyawan ?>" 
                              data-nama-karyawan="<?php echo $data->nama_karyawan ?>">
                        <span class='fa fa-file-text-o'></span>
                      </button>
                    <?php } ?>
                    </td>
                    </tr>
                  <?php } ?>

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

<!-- Kontrak Action Modal -->
<div class="modal fade" id="kontrakActionModal" tabindex="-1" role="dialog" aria-labelledby="kontrakActionModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="kontrakActionModalLabel">Pilih Aksi Kontrak</h4>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <h4 id="modalEmployeeName"></h4>
          <input type="hidden" id="modalRecidKaryawan">
          <br>
          <button type="button" class="btn btn-primary btn-lg" id="btnTambahKontrak" style="margin: 10px;">
            <i class="fa fa-plus"></i> Tambah Kontrak Baru
          </button>
          <button type="button" class="btn btn-success btn-lg" id="btnJadikanTetap" style="margin: 10px;">
            <i class="fa fa-check"></i> Diangkat Jadi Pegawai Tetap
          </button>
          <button type="button" class="btn btn-danger btn-lg" id="btnNonAktif" style="margin: 10px;">
            <i class="fa fa-times"></i> Non Aktif Karyawan
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tambah Kontrak Modal -->
<div class="modal fade" id="tambahKontrakModal" tabindex="-1" role="dialog" aria-labelledby="tambahKontrakModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="tambahKontrakModalLabel">Tambah Kontrak Baru</h4>
      </div>
      <div class="modal-body">
        <form id="tambahKontrakForm">
          <input type="hidden" id="tk_recid_karyawan">
          <div class="form-group">
            <label for="tk_tgl_mulai">Tanggal Mulai:</label>
            <input type="date" class="form-control" id="tk_tgl_mulai" required>
          </div>
          <div class="form-group">
            <label for="tk_tgl_akhir">Tanggal Akhir:</label>
            <input type="date" class="form-control" id="tk_tgl_akhir" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveTambahKontrak">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Jadikan Pegawai Tetap Modal -->
<div class="modal fade" id="jadikanTetapModal" tabindex="-1" role="dialog" aria-labelledby="jadikanTetapModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="jadikanTetapModalLabel">Diangkat Jadi Pegawai Tetap</h4>
      </div>
      <div class="modal-body">
        <form id="jadikanTetapForm">
          <input type="hidden" id="jt_recid_karyawan">
          <div class="form-group">
            <label for="jt_sk_kary_tetap_nomor">Nomor SK Karyawan Tetap:</label>
            <input type="text" class="form-control" id="jt_sk_kary_tetap_nomor" placeholder="Masukkan nomor SK karyawan tetap" required>
          </div>
          <div class="form-group">
            <label for="jt_sk_kary_tetap_tanggal">Tanggal SK Karyawan Tetap:</label>
            <input type="date" class="form-control" id="jt_sk_kary_tetap_tanggal" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="saveJadikanTetap">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Non Aktif Karyawan Modal -->
<div class="modal fade" id="nonAktifModal" tabindex="-1" role="dialog" aria-labelledby="nonAktifModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="nonAktifModalLabel">Non Aktif Karyawan</h4>
      </div>
      <div class="modal-body">
        <form id="nonAktifForm">
          <input type="hidden" id="na_recid_karyawan">
          <div class="form-group">
            <label>Karyawan:</label>
            <p id="na_nama_karyawan" class="form-control-static"></p>
          </div>
          <div class="form-group">
            <label for="na_jenis_non_aktif">Jenis Non Aktif:</label>
            <select class="form-control" id="na_jenis_non_aktif" required>
              <option value="">Pilih Jenis Non Aktif</option>
              <option value="Habis Kontrak">Habis Kontrak</option>
              <option value="Resign">Resign</option>
              <option value="Diputus Perusahaan">Diputus Perusahaan</option>
              <option value="Pensiun">Pensiun</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="form-group">
            <label for="na_tgl_non_aktif">Tanggal Non Aktif:</label>
            <input type="date" class="form-control" id="na_tgl_non_aktif" required>
          </div>
          <div class="form-group">
            <label for="na_keterangan">Keterangan:</label>
            <textarea class="form-control" id="na_keterangan" rows="3" required placeholder="Jelaskan alasan status non aktif"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="saveNonAktif">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<style>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 20000;
}
.toast {
    min-width: 250px;
    padding: 12px 18px;
    margin-bottom: 10px;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(50px);
    animation: slideIn 0.4s forwards;
}
.toast-success { background: #4caf50; }
.toast-error   { background: #f44336; }
.toast-info    { background: #2196f3; }
.toast-warning { background: #ff9800; }

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<script>
// Toast function
function showToast(message, type = "success") {
    var container = document.getElementById("toastContainer");

    var toast = document.createElement("div");
    toast.className = "toast toast-" + type;
    toast.innerText = message;

    container.appendChild(toast);

    // Auto remove
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(50px)";
        setTimeout(() => container.removeChild(toast), 300);
    }, 3000);
}

$(document).ready(function() {
    // Open main kontrak action modal
    $('#kontrakActionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recidKaryawan = button.data('recid-karyawan');
        var namaKaryawan = button.data('nama-karyawan');
        
        $('#modalRecidKaryawan').val(recidKaryawan);
        $('#modalEmployeeName').text(namaKaryawan);
    });

    // Handle Tambah Kontrak button click
    $('#btnTambahKontrak').click(function() {
        var recidKaryawan = $('#modalRecidKaryawan').val();
        var namaKaryawan = $('#modalEmployeeName').text();
        
        $('#kontrakActionModal').modal('hide');
        $('#tk_recid_karyawan').val(recidKaryawan);
        $('#tambahKontrakModalLabel').text('Tambah Kontrak Baru - ' + namaKaryawan);
        $('#tk_tgl_mulai').val('');
        $('#tk_tgl_akhir').val('');
        $('#tambahKontrakModal').modal('show');
    });
    
    // Handle Jadikan Tetap button click
    $('#btnJadikanTetap').click(function() {
        var recidKaryawan = $('#modalRecidKaryawan').val();
        var namaKaryawan = $('#modalEmployeeName').text();
        
        $('#kontrakActionModal').modal('hide');
        $('#jt_recid_karyawan').val(recidKaryawan);
        $('#jadikanTetapModalLabel').text('Diangkat Jadi Pegawai Tetap - ' + namaKaryawan);
        $('#jt_sk_kary_tetap_nomor').val('');
        $('#jt_sk_kary_tetap_tanggal').val('');
        $('#jadikanTetapModal').modal('show');
    });
    
    // Handle Non Aktif button click
    $('#btnNonAktif').click(function() {
        var recidKaryawan = $('#modalRecidKaryawan').val();
        var namaKaryawan = $('#modalEmployeeName').text();
        
        $('#kontrakActionModal').modal('hide');
        $('#na_recid_karyawan').val(recidKaryawan);
        $('#na_nama_karyawan').text(namaKaryawan);
        $('#na_jenis_non_aktif').val('');
        $('#na_tgl_non_aktif').val('');
        $('#na_keterangan').val('');
        $('#nonAktifModal').modal('show');
    });

    // Save Tambah Kontrak
    $('#saveTambahKontrak').click(function() {
        var recid_karyawan = $('#tk_recid_karyawan').val();
        var tgl_mulai = $('#tk_tgl_mulai').val();
        var tgl_akhir = $('#tk_tgl_akhir').val();

        if (!tgl_mulai || !tgl_akhir) {
            showToast('Harap isi semua field', 'error');
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>Kontrak/create',
            type: 'POST',
            dataType: 'json',
            data: { recid_karyawan, tgl_mulai, tgl_akhir },
            success: function(response) {
                if (typeof response === 'object' && response.status === 'success') {
                    $('#tambahKontrakModal').modal('hide');
                    showToast("Kontrak berhasil ditambahkan", "success");
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast("Gagal menambah kontrak: " + (response.message || 'Unknown error'), "error");
                }
            },
            error: function(xhr, status, error) {
                console.log('Error details:', xhr.responseText);
                showToast("Gagal menambah kontrak: " + error, "error");
            }
        });
    });

    // Save Non Aktif
    $('#saveNonAktif').click(function() {
        var recid_karyawan = $('#na_recid_karyawan').val();
        var jenis_non_aktif = $('#na_jenis_non_aktif').val();
        var tgl_non_aktif = $('#na_tgl_non_aktif').val();
        var keterangan = $('#na_keterangan').val();
    
        if (!jenis_non_aktif || !tgl_non_aktif || !keterangan) {
            showToast('Harap lengkapi semua field', 'error');
            return;
        }
    
        // First, we need to get the active contract for this employee
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/Karyawan/get_active_contract/' + recid_karyawan,
            type: 'GET',
            dataType: 'json',
            success: function(contractResponse) {
                if (contractResponse && contractResponse.recid_kontrak) {
                    // Use existing contract
                    $.ajax({
                        url: '<?php echo base_url(); ?>Kontrak/non_aktif/' + contractResponse.recid_kontrak,
                        type: 'POST',
                        dataType: 'json',
                        data: { jenis_non_aktif, tgl_non_aktif, keterangan_non_aktif: keterangan },
                        success: function(response) {
                            if (typeof response === 'object' && response.status === 'success') {
                                $('#nonAktifModal').modal('hide');
                                showToast("Status karyawan berhasil diubah menjadi non aktif", "success");
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showToast("Gagal mengubah status karyawan: " + (response.message || 'Unknown error'), "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error details:', xhr.responseText);
                            showToast("Gagal mengubah status karyawan: " + error, "error");
                        }
                    });
                } else {
                    // No active contract found, create a temporary one
                    var today = new Date().toISOString().split('T')[0];
                    $.ajax({
                        url: '<?php echo base_url(); ?>Kontrak/create',
                        type: 'POST',
                        dataType: 'json',
                        data: { 
                            recid_karyawan: recid_karyawan,
                            tgl_mulai: today,
                            tgl_akhir: tgl_non_aktif
                        },
                        success: function(createResponse) {
                            if (typeof createResponse === 'object' && createResponse.status === 'success') {
                                // Now get the newly created contract and mark it as non-aktif
                                $.ajax({
                                    url: '<?php echo base_url(); ?>index.php/Karyawan/get_active_contract/' + recid_karyawan,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(newContractResponse) {
                                        if (newContractResponse && newContractResponse.recid_kontrak) {
                                            $.ajax({
                                                url: '<?php echo base_url(); ?>Kontrak/non_aktif/' + newContractResponse.recid_kontrak,
                                                type: 'POST',
                                                dataType: 'json',
                                                data: { jenis_non_aktif, tgl_non_aktif, keterangan_non_aktif: keterangan },
                                                success: function(finalResponse) {
                                                    if (typeof finalResponse === 'object' && finalResponse.status === 'success') {
                                                        $('#nonAktifModal').modal('hide');
                                                        showToast("Status karyawan berhasil diubah menjadi non aktif", "success");
                                                        setTimeout(() => location.reload(), 1500);
                                                    } else {
                                                        showToast("Gagal mengubah status karyawan: " + (finalResponse.message || 'Unknown error'), "error");
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    console.log('Error details:', xhr.responseText);
                                                    showToast("Gagal mengubah status karyawan: " + error, "error");
                                                }
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.log('Error getting active contract:', xhr.responseText);
                                        showToast("Gagal mendapatkan kontrak aktif: " + error, "error");
                                    }
                                });
                            } else {
                                showToast("Gagal membuat kontrak sementara: " + (createResponse.message || 'Unknown error'), "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error creating contract:', xhr.responseText);
                            showToast("Gagal membuat kontrak sementara: " + error, "error");
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Error checking active contract:', xhr.responseText);
                showToast("Gagal memeriksa kontrak aktif: " + error, "error");
            }
        });
    });
    
    // Save Jadikan Tetap
    $('#saveJadikanTetap').click(function() {
        var recid_karyawan = $('#jt_recid_karyawan').val();
        var sk_kary_tetap_nomor = $('#jt_sk_kary_tetap_nomor').val();
        var sk_kary_tetap_tanggal = $('#jt_sk_kary_tetap_tanggal').val();
    
        if (!sk_kary_tetap_nomor || !sk_kary_tetap_tanggal) {
            showToast('Harap lengkapi semua field', 'error');
            return;
        }
    
        // Update employee to permanent status
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/Karyawan/jadikan_tetap',
            type: 'POST',
            dataType: 'json',
            data: { 
                recid_karyawan: recid_karyawan,
                sk_kary_tetap_nomor: sk_kary_tetap_nomor,
                sk_kary_tetap_tanggal: sk_kary_tetap_tanggal
            },
            success: function(response) {
                if (typeof response === 'object' && response.status === 'success') {
                    $('#jadikanTetapModal').modal('hide');
                    showToast("Karyawan berhasil diangkat menjadi pegawai tetap", "success");
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast("Gagal mengubah status karyawan: " + (response.message || 'Unknown error'), "error");
                }
            },
            error: function(xhr, status, error) {
                console.log('Error details:', xhr.responseText);
                showToast("Gagal mengubah status karyawan: " + error, "error");
            }
        });
    });
});

// Function to export filtered data
function exportFilteredData() {
    // Get the current search term from the DataTables search input
    var searchTerm = $('.dataTables_filter input').val();
    
    // Check if the search term contains "resign" (case insensitive)
    if (searchTerm.toLowerCase().includes('resign')) {
        window.location.href = '<?php echo base_url('karyawan/export?filter=resign'); ?>';
    } 
    // Check if the search term contains "aktif" (case insensitive)
    else if (searchTerm.toLowerCase().includes('aktif')) {
        window.location.href = '<?php echo base_url('karyawan/export?filter=aktif'); ?>';
    }
    // For other search terms
    else if (searchTerm.trim() !== '') {
        window.location.href = '<?php echo base_url('karyawan/export?filter='); ?>' + encodeURIComponent(searchTerm);
    }
    // If no filter, export all data
    else {
        window.location.href = '<?php echo base_url('karyawan/export'); ?>';
    }
}
</script>
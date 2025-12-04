<style>
  .table-terlambat {
    max-height: calc(100vh - 300px);
    overflow-y: auto;
  }
  .late-badge {
    background: #ff6b6b;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
  }
  .action-cell {
    white-space: nowrap;
  }
  .btn-validasi {
    padding: 5px 10px;
    font-size: 12px;
  }
</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Daftar Karyawan Terlambat</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h4>Filter Tanggal</h4>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">

          <!-- FILTER SECTION -->
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-4">
              <div class="form-group">
                <label>Pilih Tanggal:</label>
                <input type="date" id="filter_tgl" class="form-control" value="<?php echo $tgl; ?>" onchange="filterByDate()">
              </div>
            </div>
          </div>

          <!-- INFO BOX -->
          <div class="alert alert-info">
            <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($tgl)); ?> | 
            <strong>Total Terlambat:</strong> <span id="total-count" class="badge badge-danger"><?php echo $terlambat->num_rows(); ?></span>
          </div>

          <!-- TABLE TERLAMBAT -->
          <div class="table-responsive table-terlambat">
            <table class="table table-striped table-hover">
              <thead style="background: #f5f5f5;">
                <tr>
                  <th>No</th>
                  <th>NIK</th>
                  <th>Nama Karyawan</th>
                  <th>Bagian</th>
                  <th>Jam Masuk</th>
                  <th style="text-align: center;">Terlambat</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tbody_terlambat">
                <?php
                  if ($terlambat->num_rows() > 0) {
                    $no = 1;
                    foreach ($terlambat->result() as $t) {
                      // Hitung terlambat
                      $jam_masuk = strtotime($t->jam_masuk);
                      $jam_masuk_standar = strtotime(date('Y-m-d') . ' 07:00:00');
                      $selisih = ($jam_masuk - $jam_masuk_standar) / 60; // dalam menit
                      $jam = floor($selisih / 60);
                      $menit = $selisih % 60;
                      $terlambat_display = $jam . 'j ' . $menit . 'm';
                ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $t->nik; ?></td>
                  <td><?php echo $t->nama_karyawan; ?></td>
                  <td><?php echo $t->indeks_hr; ?></td>
                  <td><strong><?php echo date('H:i:s', strtotime($t->jam_masuk)); ?></strong></td>
                  <td style="text-align: center;">
                    <span class="late-badge"><?php echo $terlambat_display; ?></span>
                  </td>
                  <td class="action-cell">
                    <button class="btn btn-sm btn-success btn-validasi" onclick="validasiTerlambat(<?php echo $t->recid_hadir; ?>, <?php echo $t->recid_karyawan; ?>, '<?php echo $t->tanggal; ?>', '<?php echo $t->nama_karyawan; ?>')">
                      <i class="fa fa-check"></i> Validasi
                    </button>
                  </td>
                </tr>
                <?php
                    }
                  } else {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                    <i class="fa fa-smile-o" style="font-size: 32px; margin-bottom: 10px;"></i><br>
                    Semua karyawan hadir tepat waktu! 🎉
                  </td>
                </tr>
                <?php
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

<script>
// Filter by date
function filterByDate() {
  var tgl = document.getElementById('filter_tgl').value;
  window.location.href = '<?php echo base_url("Absen/terlambat_list"); ?>?tgl=' + tgl;
}

// Validasi terlambat
function validasiTerlambat(recidHadir, recidKaryawan, tgl, namaKaryawan) {
  if (confirm('Validasi terlambat untuk ' + namaKaryawan + '?')) {
    $.ajax({
      url: '<?php echo base_url("Absen/validasi_terlambat"); ?>',
      type: 'POST',
      dataType: 'JSON',
      data: {
        recid_hadir: recidHadir,
        recid_karyawan: recidKaryawan,
        tgl: tgl
      },
      success: function(response) {
        if (response.success) {
          alert(response.message);
          location.reload(); // Reload halaman
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function() {
        alert('Gagal memvalidasi terlambat');
      }
    });
  }
}
</script>

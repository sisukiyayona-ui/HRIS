<style>
  .table-izin {
    max-height: calc(100vh - 300px);
    overflow-y: auto;
  }
  .validated-badge {
    background: #28a745;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
  }
</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Daftar Izin (Terlambat Tervalidasi)</h3>
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
                <label>Pilih Tanggal (Optional):</label>
                <input type="date" id="filter_tgl" class="form-control" value="<?php echo $tgl ? $tgl : ''; ?>" onchange="filterByDate()">
              </div>
            </div>
            <div class="col-md-2" style="margin-top: 24px;">
              <button class="btn btn-secondary" onclick="clearFilter()">
                <i class="fa fa-times"></i> Clear Filter
              </button>
            </div>
          </div>

          <!-- INFO BOX -->
          <div class="alert alert-success">
            <strong>Total Izin Tervalidasi:</strong> <span id="total-count" class="badge badge-success"><?php echo $izin->num_rows(); ?></span>
            <?php if ($tgl): ?>
              | <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($tgl)); ?>
            <?php endif; ?>
          </div>

          <!-- TABLE IZIN -->
          <div class="table-responsive table-izin">
            <table class="table table-striped table-hover">
              <thead style="background: #f5f5f5;">
                <tr>
                  <th>No</th>
                  <th>NIK</th>
                  <th>Nama Karyawan</th>
                  <th>Bagian</th>
                  <th>Tanggal</th>
                  <th>Jam Masuk</th>
                  <th>Divalidasi Oleh</th>
                  <th>Tanggal Validasi</th>
                  <th>Status Validasi</th>
                </tr>
              </thead>
              <tbody id="tbody_izin">
                <?php
                  if ($izin->num_rows() > 0) {
                    $no = 1;
                    foreach ($izin->result() as $i) {
                      $validated_by = !empty($i->validated_by) ? $i->validated_by : '-';
                      $validated_date = !empty($i->validated_date) ? date('d M Y H:i', strtotime($i->validated_date)) : '-';
                ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $i->nik; ?></td>
                  <td><?php echo $i->nama_karyawan; ?></td>
                  <td><?php echo $i->nama_bag; ?></td>
                  <td><?php echo date('d M Y', strtotime($i->tgl_izin)); ?></td>
                  <td><?php echo !empty($i->jam_in) ? date('H:i:s', strtotime($i->jam_in)) : '-'; ?></td>
                  <td><?php echo $validated_by; ?></td>
                  <td><?php echo $validated_date; ?></td>
                  <td>
                    <span class="validated-badge">
                      <i class="fa fa-check-circle"></i> Tervalidasi
                    </span>
                  </td>
                </tr>
                <?php
                    }
                  } else {
                ?>
                <tr>
                  <td colspan="9" style="text-align: center; padding: 30px; color: #999;">
                    <i class="fa fa-inbox" style="font-size: 32px; margin-bottom: 10px;"></i><br>
                    Belum ada izin tervalidasi
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
  if (tgl) {
    window.location.href = '<?php echo base_url("Absen/izin_list"); ?>?tgl=' + tgl;
  } else {
    window.location.href = '<?php echo base_url("Absen/izin_list"); ?>';
  }
}

// Clear filter
function clearFilter() {
  document.getElementById('filter_tgl').value = '';
  window.location.href = '<?php echo base_url("Absen/izin_list"); ?>';
}
</script>

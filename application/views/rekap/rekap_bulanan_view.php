<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Rekap Absensi Bulanan</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-10">
                <form method="GET" action="<?php echo base_url('Rekap/rekap_bulanan')?>">
                  <div class="row">
                    <div class="col-md-3">
                      <select name="bulan" class="form-control" required>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                          <option value="<?= sprintf('%02d', $m) ?>" <?= $bulan == sprintf('%02d', $m) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <select name="tahun" class="form-control" required>
                        <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                          <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i> Tampilkan
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-2 text-right">
                <a href="<?= base_url('Rekap/export_rekap_bulanan?bulan=' . $bulan . '&tahun=' . $tahun) ?>" 
                   class="btn btn-success btn-sm">
                  <i class="fa fa-file-excel-o"></i> Export Excel
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <div class="alert alert-info">
              <strong>Periode:</strong> <?= date('d F Y', strtotime($tanggal_mulai)) ?> - <?= date('d F Y', strtotime($tanggal_akhir)) ?> | 
              <strong>Total Data:</strong> <?= !empty($attendance) ? count($attendance) : 0 ?> record
            </div>
            
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="tbl_rekap_bulanan" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th><center>No.</center></th>
                    <th><center>Name</center></th>
                    <th><center>Time</center></th>
                    <th><center>State</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if (!empty($attendance)) {
                    $no = 1;
                    foreach ($attendance as $data) {
                      ?>
                      <tr>
                        <td><center><?php echo $data->nik?></center></td>
                        <td><?php echo $data->nama_karyawan?></td>
                        <td>
                          <?php echo date('d/m/Y', strtotime($data->tanggal))?><br/>
                          <strong><?php echo date('h:i A', strtotime($data->waktu))?></strong>
                        </td>
                        <td><center><?php echo $data->state?></center></td>
                      </tr>
                    <?php 
                    }
                  } else {
                    ?>
                    <tr>
                      <td colspan="4" class="text-center">
                        <br>
                        <i class="fa fa-info-circle fa-3x" style="color: #ccc;"></i>
                        <h4 style="color: #999;">Tidak ada data untuk periode ini</h4>
                        <p style="color: #999;">
                          Pastikan karyawan sudah ter-mapping dengan PIN mesin absensi
                        </p>
                        <br>
                      </td>
                    </tr>
                  <?php 
                  }
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
<!-- /page content -->

<script>
$(document).ready(function() {
  $('#tbl_rekap_bulanan').DataTable({
    "pageLength": 100,
    "order": [[0, 'asc'], [2, 'asc']],
    "language": {
      "search": "Cari:",
      "lengthMenu": "Tampilkan _MENU_ data per halaman",
      "zeroRecords": "Data tidak ditemukan",
      "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
      "infoEmpty": "Tidak ada data tersedia",
      "infoFiltered": "(difilter dari _MAX_ total data)",
      "paginate": {
        "first": "Pertama",
        "last": "Terakhir",
        "next": "Selanjutnya",
        "previous": "Sebelumnya"
      }
    }
  });
});
</script>

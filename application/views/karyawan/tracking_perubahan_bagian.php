<style>
  .table-responsive {
    overflow-x: auto;
  }
</style>

<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tracking Perpindahan Bagian</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-12">
                <h4>Filter Data</h4>
                <form method="GET" style="margin-top: 10px;">
                  <div class="row">
                    <div class="col-md-2">
                      <label>Dari Tanggal:</label>
                      <input type="date" name="from_date" class="form-control input-sm" value="<?php echo $from_date; ?>">
                    </div>

                    <div class="col-md-2">
                      <label>Sampai Tanggal:</label>
                      <input type="date" name="to_date" class="form-control input-sm" value="<?php echo $to_date; ?>">
                    </div>

                    <div class="col-md-3">
                      <label>Bagian Asal:</label>
                      <select name="recid_bagian_lama" class="form-control input-sm">
                        <option value="">-- Semua --</option>
                        <?php foreach ($bagian as $b): ?>
                          <option value="<?php echo $b['recid_bag']; ?>" <?php echo ($filter_recid_bagian_lama == $b['recid_bag']) ? 'selected' : ''; ?>>
                            <?php echo $b['indeks_hr']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label>Bagian Tujuan:</label>
                      <select name="recid_bagian_baru" class="form-control input-sm">
                        <option value="">-- Semua --</option>
                        <?php foreach ($bagian as $b): ?>
                          <option value="<?php echo $b['recid_bag']; ?>" <?php echo ($filter_recid_bagian_baru == $b['recid_bag']) ? 'selected' : ''; ?>>
                            <?php echo $b['indeks_hr']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <label>&nbsp;</label>
                      <div>
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                          <i class="fa fa-search"></i> Filter
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                      <a href="<?php echo base_url('Karyawan/tracking_perubahan_bagian'); ?>" class="btn btn-secondary btn-sm">
                        <i class="fa fa-refresh"></i> Reset Filter
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <!-- INFO SECTION -->
            <div class="alert alert-info">
              <strong>Total Catatan:</strong> <?php echo $total_records; ?> perubahan bagian
            </div>

            <!-- TABLE SECTION -->
            <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIK</th>
                  <th>Nama Karyawan</th>
                  <th>Tgl Pindah</th>
                  <th>Dari Bagian</th>
                  <th>Dari Sub Bagian</th>
                  <th>Ke Bagian</th>
                  <th>Ke Sub Bagian</th>
                  <th>Tgl Proses</th>
                  <th>User Approve</th>
                  <th>Catatan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($history) > 0): ?>
                  <?php $no = (($current_page - 1) * 50) + 1; ?>
                  <?php foreach ($history as $item): ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><?php echo $item['nik']; ?></td>
                      <td><?php echo $item['nama_karyawan']; ?></td>
                      <td><?php echo date('d/m/Y', strtotime($item['tanggal_efektif'])); ?></td>
                      <td><?php echo $item['bagian_lama'] ?: '-'; ?></td>
                      <td><?php echo $item['sub_lama'] ?: '-'; ?></td>
                      <td><?php echo $item['bagian_baru'] ?: '-'; ?></td>
                      <td><?php echo $item['sub_baru'] ?: '-'; ?></td>
                      <td><?php echo date('d/m/Y H:i', strtotime($item['tanggal_proses'])); ?></td>
                      <td><?php echo $item['user_approve'] ?: '-'; ?></td>
                      <td>
                        <span title="<?php echo $item['catatan']; ?>">
                          <?php echo strlen($item['catatan']) > 20 ? substr($item['catatan'], 0, 20) . '...' : $item['catatan']; ?>
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-info" onclick="showDetail(<?php echo $item['id']; ?>)">
                          <i class="fa fa-eye"></i> Detail
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="12" style="text-align: center; padding: 30px; color: #999;">
                      <i class="fa fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                      Tidak ada data perpindahan bagian
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          <?php if ($total_pages > 1): ?>
            <nav>
              <ul class="pagination">
                <?php if ($current_page > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo base_url('Karyawan/tracking_perubahan_bagian?page=1' . ($from_date ? '&from_date=' . $from_date : '') . ($to_date ? '&to_date=' . $to_date : '') . ($filter_recid_bagian_lama ? '&recid_bagian_lama=' . $filter_recid_bagian_lama : '') . ($filter_recid_bagian_baru ? '&recid_bagian_baru=' . $filter_recid_bagian_baru : '')); ?>">
                      <i class="fa fa-step-backward"></i> Pertama
                    </a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo base_url('Karyawan/tracking_perubahan_bagian?page=' . ($current_page - 1) . ($from_date ? '&from_date=' . $from_date : '') . ($to_date ? '&to_date=' . $to_date : '') . ($filter_recid_bagian_lama ? '&recid_bagian_lama=' . $filter_recid_bagian_lama : '') . ($filter_recid_bagian_baru ? '&recid_bagian_baru=' . $filter_recid_bagian_baru : '')); ?>">
                      <i class="fa fa-chevron-left"></i> Sebelumnya
                    </a>
                  </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                  <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo base_url('Karyawan/tracking_perubahan_bagian?page=' . $i . ($from_date ? '&from_date=' . $from_date : '') . ($to_date ? '&to_date=' . $to_date : '') . ($filter_recid_bagian_lama ? '&recid_bagian_lama=' . $filter_recid_bagian_lama : '') . ($filter_recid_bagian_baru ? '&recid_bagian_baru=' . $filter_recid_bagian_baru : '')); ?>">
                      <?php echo $i; ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo base_url('Karyawan/tracking_perubahan_bagian?page=' . ($current_page + 1) . ($from_date ? '&from_date=' . $from_date : '') . ($to_date ? '&to_date=' . $to_date : '') . ($filter_recid_bagian_lama ? '&recid_bagian_lama=' . $filter_recid_bagian_lama : '') . ($filter_recid_bagian_baru ? '&recid_bagian_baru=' . $filter_recid_bagian_baru : '')); ?>">
                      Berikutnya <i class="fa fa-chevron-right"></i>
                    </a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="<?php echo base_url('Karyawan/tracking_perubahan_bagian?page=' . $total_pages . ($from_date ? '&from_date=' . $from_date : '') . ($to_date ? '&to_date=' . $to_date : '') . ($filter_recid_bagian_lama ? '&recid_bagian_lama=' . $filter_recid_bagian_lama : '') . ($filter_recid_bagian_baru ? '&recid_bagian_baru=' . $filter_recid_bagian_baru : '')); ?>">
                      Akhir <i class="fa fa-step-forward"></i>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </nav>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Perpindahan Bagian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="detail-content">
        <!-- Dynamic content -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
function showDetail(id) {
  $.ajax({
    url: '<?php echo base_url("Karyawan/detail_perubahan"); ?>',
    type: 'GET',
    dataType: 'JSON',
    data: { id: id },
    success: function(response) {
      if (response.success) {
        var data = response.data;
        var html = `
          <div class="form-group">
            <label>NIK:</label>
            <input type="text" class="form-control" value="${data.nik}" disabled>
          </div>
          <div class="form-group">
            <label>Nama Karyawan:</label>
            <input type="text" class="form-control" value="${data.nama_karyawan}" disabled>
          </div>
          <div class="form-group">
            <label>Dari Bagian / Sub Bagian:</label>
            <input type="text" class="form-control" value="${data.bagian_lama || '-'} / ${data.sub_lama || '-'}" disabled>
          </div>
          <div class="form-group">
            <label>Ke Bagian / Sub Bagian:</label>
            <input type="text" class="form-control" value="${data.bagian_baru || '-'} / ${data.sub_baru || '-'}" disabled>
          </div>
          <div class="form-group">
            <label>Tanggal Efektif Pindah:</label>
            <input type="text" class="form-control" value="${new Date(data.tanggal_efektif).toLocaleDateString('id-ID')}" disabled>
          </div>
          <div class="form-group">
            <label>Tanggal Proses:</label>
            <input type="text" class="form-control" value="${new Date(data.tanggal_proses).toLocaleString('id-ID')}" disabled>
          </div>
          <div class="form-group">
            <label>User Approve:</label>
            <input type="text" class="form-control" value="${data.user_approve || '-'}" disabled>
          </div>
          <div class="form-group">
            <label>Catatan:</label>
            <textarea class="form-control" rows="3" disabled>${data.catatan || '-'}</textarea>
          </div>
        `;
        document.getElementById('detail-content').innerHTML = html;
        $('#detailModal').modal('show');
      } else {
        alert('Data tidak ditemukan');
      }
    },
    error: function() {
      alert('Terjadi kesalahan');
    }
  });
}
</script>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

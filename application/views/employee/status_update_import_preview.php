<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Preview Perubahan Status Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Data Preview</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>Total records found: <?php echo $total_rows; ?></p>
            <p>Showing first 10 records for preview:</p>
            
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>NIK</th>
                    <th>STATUS_KARYAWAN</th>
                    <th>SK_KARY_TETAP_NOMOR</th>
                    <th>SK_KARY_TETAP_TANGGAL</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($preview_data as $employee): ?>
                    <tr>
                      <td><?php echo isset($employee['NIK']) ? htmlspecialchars($employee['NIK']) : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_KARYAWAN']) ? htmlspecialchars($employee['STATUS_KARYAWAN']) : ''; ?></td>
                      <td><?php echo isset($employee['SK_KARY_TETAP_NOMOR']) ? htmlspecialchars($employee['SK_KARY_TETAP_NOMOR']) : ''; ?></td>
                      <td><?php echo isset($employee['SK_KARY_TETAP_TANGGAL']) ? htmlspecialchars($employee['SK_KARY_TETAP_TANGGAL']) : ''; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            
            <div class="form-group">
              <a href="<?php echo base_url('Status_update_import/validate_data'); ?>" class="btn btn-success">
                <i class="fa fa-check"></i> Proses & Import Data
              </a>
              <a href="<?php echo base_url('Status_update_import'); ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back to Upload
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
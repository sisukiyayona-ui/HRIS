<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Preview Import Kontrak Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Pratinjau Data Kontrak Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p><strong>Jumlah Baris Data:</strong> <?php echo count($preview_data); ?></p>
            
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <?php
                  // Determine the maximum contract number in the data
                  $max_contract = 1;
                  foreach ($preview_data as $contract) {
                      for ($i = 1; $i <= 44; $i++) {
                          if (isset($contract['AWAL_' . $i]) && !empty($contract['AWAL_' . $i]) || 
                              isset($contract['AKHIR_' . $i]) && !empty($contract['AKHIR_' . $i])) {
                              $max_contract = max($max_contract, $i);
                          }
                      }
                  }
                  ?>
                  <tr>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">No</th>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">NIK</th>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">STATUS KARYAWAN</th>
                    <th colspan="<?php echo $max_contract * 2; ?>" style="text-align: center;">KONTRAK</th>
                  </tr>
                  <tr>
                    <?php for ($i = 1; $i <= $max_contract; $i++): ?>
                    <th colspan="2" style="text-align: center;"><?php echo $i; ?></th>
                    <?php endfor; ?>
                  </tr>
                  <tr>
                    <?php for ($i = 1; $i <= $max_contract; $i++): ?>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (array_slice($preview_data, 0, 10) as $index => $contract): ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo isset($contract['NIK']) ? htmlspecialchars($contract['NIK']) : ''; ?></td>
                    <td><?php echo isset($contract['STATUS_KARYAWAN']) ? htmlspecialchars($contract['STATUS_KARYAWAN']) : ''; ?></td>
                    <?php for ($i = 1; $i <= $max_contract; $i++): ?>
                    <td><?php echo isset($contract['AWAL_' . $i]) ? htmlspecialchars($contract['AWAL_' . $i]) : ''; ?></td>
                    <td><?php echo isset($contract['AKHIR_' . $i]) ? htmlspecialchars($contract['AKHIR_' . $i]) : ''; ?></td>
                    <?php endfor; ?>
                  </tr>
                  <?php endforeach; ?>
                  
                  <?php if (count($preview_data) > 10): ?>
                  <tr>
                    <td colspan="<?php echo 3 + ($max_contract * 2); ?>" class="text-center">... dan <?php echo count($preview_data) - 10; ?> data lainnya</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            
            <div class="form-group">
              <a href="<?php echo base_url('Contract_import/process_data'); ?>" class="btn btn-success">
                <i class="fa fa-check"></i> Proses & Import Data
              </a>
              <a href="<?php echo base_url('Contract_import'); ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Kembali ke Upload
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
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
                  <tr>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">No</th>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">NIK</th>
                    <th colspan="6" style="text-align: center;">KONTRAK</th>
                  </tr>
                  <tr>
                    <th colspan="2" style="text-align: center;">1</th>
                    <th colspan="2" style="text-align: center;">2</th>
                    <th colspan="2" style="text-align: center;">3</th>
                  </tr>
                  <tr>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (array_slice($preview_data, 0, 10) as $index => $contract): ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo isset($contract['NIK']) ? htmlspecialchars($contract['NIK']) : ''; ?></td>
                    <td><?php echo isset($contract['AWAL_1']) ? htmlspecialchars($contract['AWAL_1']) : ''; ?></td>
                    <td><?php echo isset($contract['AKHIR_1']) ? htmlspecialchars($contract['AKHIR_1']) : ''; ?></td>
                    <td><?php echo isset($contract['AWAL_2']) ? htmlspecialchars($contract['AWAL_2']) : ''; ?></td>
                    <td><?php echo isset($contract['AKHIR_2']) ? htmlspecialchars($contract['AKHIR_2']) : ''; ?></td>
                    <td><?php echo isset($contract['AWAL_3']) ? htmlspecialchars($contract['AWAL_3']) : ''; ?></td>
                    <td><?php echo isset($contract['AKHIR_3']) ? htmlspecialchars($contract['AKHIR_3']) : ''; ?></td>
                  </tr>
                  <?php endforeach; ?>
                  
                  <?php if (count($preview_data) > 10): ?>
                  <tr>
                    <td colspan="8" class="text-center">... dan <?php echo count($preview_data) - 10; ?> data lainnya</td>
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
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Hasil Import Kontrak Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Ringkasan Import Kontrak Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-12">
                <h4>Statistik Import</h4>
                <table class="table table-bordered">
                  <tr>
                    <td><strong>Total Baris Data</strong></td>
                    <td><?php echo $import_summary['total_records']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Data Berhasil Ditambahkan</strong></td>
                    <td><?php echo $import_summary['inserted_records']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Data Diperbarui</strong></td>
                    <td><?php echo $import_summary['updated_records']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Data Gagal Diimpor</strong></td>
                    <td><?php echo $import_summary['failed_imports']; ?></td>
                  </tr>
                </table>
                
                <?php if (!empty($import_summary['inserted_details'])): ?>
                <h4>Data Berhasil Ditambahkan</h4>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($import_summary['inserted_details'] as $detail): ?>
                      <tr>
                        <td><?php echo $detail['NIK']; ?></td>
                        <td><?php echo $detail['NAMA']; ?></td>
                        <td><?php echo $detail['tgl_mulai']; ?></td>
                        <td><?php echo $detail['tgl_akhir']; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($import_summary['updated_details'])): ?>
                <h4>Data Diperbarui</h4>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Informasi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($import_summary['updated_details'] as $detail): ?>
                      <tr>
                        <td><?php echo $detail['NIK']; ?></td>
                        <td><?php echo $detail['NAMA']; ?></td>
                        <td><?php echo $detail['tgl_mulai']; ?></td>
                        <td><?php echo $detail['tgl_akhir']; ?></td>
                        <td><?php echo $detail['message']; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($import_summary['errors'])): ?>
                <h4>Error Selama Import</h4>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Baris</th>
                        <th>Field</th>
                        <th>Pesan Error</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($import_summary['errors'] as $error): ?>
                      <tr class="danger">
                        <td><?php echo $error['row'] ?? 'N/A'; ?></td>
                        <td><?php echo $error['field'] ?? 'N/A'; ?></td>
                        <td><?php echo $error['message']; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($results['data'])): ?>
                <h4>Contoh Data yang Diproses</h4>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th rowspan="3" style="vertical-align: middle; text-align: center;">No</th>
                        <th rowspan="3" style="vertical-align: middle; text-align: center;">NIK</th>
                        <th colspan="4" style="text-align: center;">KONTRAK</th>
                      </tr>
                      <tr>
                        <th colspan="2" style="text-align: center;">1</th>
                        <th colspan="2" style="text-align: center;">2</th>
                      </tr>
                      <tr>
                        <th style="text-align: center;">AWAL</th>
                        <th style="text-align: center;">AKHIR</th>
                        <th style="text-align: center;">AWAL</th>
                        <th style="text-align: center;">AKHIR</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach (array_slice($results['data'], 0, 5) as $index => $contract): ?>
                      <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo isset($contract['NIK']) ? htmlspecialchars($contract['NIK']) : ''; ?></td>
                        <td><?php echo isset($contract['AWAL_1']) ? htmlspecialchars($contract['AWAL_1']) : ''; ?></td>
                        <td><?php echo isset($contract['AKHIR_1']) ? htmlspecialchars($contract['AKHIR_1']) : ''; ?></td>
                        <td><?php echo isset($contract['AWAL_2']) ? htmlspecialchars($contract['AWAL_2']) : ''; ?></td>
                        <td><?php echo isset($contract['AKHIR_2']) ? htmlspecialchars($contract['AKHIR_2']) : ''; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                  <a href="<?php echo base_url('Contract_import'); ?>" class="btn btn-primary">
                    <i class="fa fa-upload"></i> Import File Lain
                  </a>
                  <a href="<?php echo base_url('Kontrak'); ?>" class="btn btn-default">
                    <i class="fa fa-list"></i> Lihat Kontrak
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
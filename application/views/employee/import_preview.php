<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Employee Import Preview</h3>
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
                  <!-- First header row -->
                  <tr>
                    <th colspan="34"></th>
                    <th colspan="88" style="text-align: center;">KONTRAK</th>
                    <th colspan="7"></th>
                  </tr>
                  <tr>
                    <!-- Employee data headers -->
                    <th>NIK</th>
                    <th>NAMA</th>
                    <th>ALAMAT E-MAIL PRIBADI</th>
                    <th>JABATAN</th>
                    <th>BAGIAN</th>
                    <th>SUB.BAGIAN</th>
                    <th>DEPARTEMEN</th>
                    <th>STATUS KARYAWAN</th>
                    <th>TGL. MASUK</th>
                    <th>TGL. KELUAR</th>
                    <th>TGL.JEDA</th>
                    <th>MASA KERJA</th>
                    <th>SK. KARY TETAP</th>
                    <th>BPJS NO.KPJ</th>
                    <th>NO. KARTU TRIMAS</th>
                    <th>STATUS PERNIKAHAN</th>
                    <th>TEMPAT LAHIR</th>
                    <th>TGL LAHIR</th>
                    <th>TGL LAHIR HARI</th>
                    <th>BULAN LAHIR</th>
                    <th>USIA</th>
                    <th>ALAMAT KTP</th>
                    <th>ALAMAT TINGGAL SEKARANG</th>
                    <th>JENIS KELAMIN</th>
                    <th>AGAMA</th>
                    <th>PENDIDIKAN TERAKHIR</th>
                    <th>NO. TELEPON</th>
                    <th>NO. KK</th>
                    <th>NO. KTP</th>
                    <th>GOL DARAH</th>
                    <th>NAMA ORANG TUA</th>
                    <th>NAMA SUAMI / ISTRI</th>
                    <th>JUMLAH ANAK</th>
                    <th>NAMA ANAK</th>
                    
                    <!-- Contract headers (KONTRAK 1, KONTRAK 2, etc.) - 44 pairs of AWAL/AKHIR = 88 columns -->
                    <?php for ($i = 1; $i <= 44; $i++): ?>
                      <th colspan="2">KONTRAK <?php echo $i; ?></th>
                    <?php endfor; ?>
                    
                    <!-- Other contract-related columns -->
                    <th>KONTRAK AKHIR</th>
                    <th>NO.REKENING</th>
                    <th>TIPE PTKP</th>
                    <th>ALASAN KELUAR</th>
                    <th>KETERANGAN</th>
                    <th>LEVEL</th>
                    <th>DL/IDL</th>
                  </tr>
                  <tr>
                    <!-- Empty cells for employee data columns -->
                    <?php for ($i = 1; $i <= 34; $i++): ?>
                      <th></th>
                    <?php endfor; ?>
                    
                    <!-- Contract sub-headers (AWAL/AKHIR pairs) - 44 pairs = 88 columns -->
                    <?php for ($i = 1; $i <= 44; $i++): ?>
                      <th>AWAL</th>
                      <th>AKHIR</th>
                    <?php endfor; ?>
                    
                    <!-- Empty cells for other contract columns -->
                    <?php for ($i = 1; $i <= 7; $i++): ?>
                      <th></th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($preview_data as $employee): ?>
                    <tr>
                      <td><?php echo isset($employee['NIK']) ? htmlspecialchars($employee['NIK']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA']) ? htmlspecialchars($employee['NAMA']) : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_E_MAIL_PRIBADI']) ? htmlspecialchars($employee['ALAMAT_E_MAIL_PRIBADI']) : ''; ?></td>
                      <td><?php echo isset($employee['JABATAN']) ? htmlspecialchars($employee['JABATAN']) : ''; ?></td>
                      <td><?php echo isset($employee['BAGIAN']) ? htmlspecialchars($employee['BAGIAN']) : ''; ?></td>
                      <td><?php echo isset($employee['SUB_BAGIAN']) ? htmlspecialchars($employee['SUB_BAGIAN']) : ''; ?></td>
                      <td><?php echo isset($employee['DEPARTEMEN']) ? htmlspecialchars($employee['DEPARTEMEN']) : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_KARYAWAN']) ? htmlspecialchars($employee['STATUS_KARYAWAN']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_MASUK']) ? htmlspecialchars($employee['TGL_MASUK']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_KELUAR']) ? htmlspecialchars($employee['TGL_KELUAR']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_JEDA']) ? htmlspecialchars($employee['TGL_JEDA']) : ''; ?></td>
                      <td><?php echo isset($employee['MASA_KERJA']) ? htmlspecialchars($employee['MASA_KERJA']) : ''; ?></td>
                      <td><?php echo isset($employee['SK_KARY_TETAP']) ? htmlspecialchars($employee['SK_KARY_TETAP']) : ''; ?></td>
                      <td><?php echo isset($employee['BPJS_NO_KPJ']) ? htmlspecialchars($employee['BPJS_NO_KPJ']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_KARTU_TRIMAS']) ? htmlspecialchars($employee['NO_KARTU_TRIMAS']) : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_PERNIKAHAN']) ? htmlspecialchars($employee['STATUS_PERNIKAHAN']) : ''; ?></td>
                      <td><?php echo isset($employee['TEMPAT_LAHIR']) ? htmlspecialchars($employee['TEMPAT_LAHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_LAHIR']) ? htmlspecialchars($employee['TGL_LAHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_LAHIR_HARI']) ? htmlspecialchars($employee['TGL_LAHIR_HARI']) : ''; ?></td>
                      <td><?php echo isset($employee['BULAN_LAHIR']) ? htmlspecialchars($employee['BULAN_LAHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['USIA']) ? htmlspecialchars($employee['USIA']) : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_KTP']) ? htmlspecialchars($employee['ALAMAT_KTP']) : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_TINGGAL_SEKARANG']) ? htmlspecialchars($employee['ALAMAT_TINGGAL_SEKARANG']) : ''; ?></td>
                      <td><?php echo isset($employee['JENIS_KELAMIN']) ? htmlspecialchars($employee['JENIS_KELAMIN']) : ''; ?></td>
                      <td><?php echo isset($employee['AGAMA']) ? htmlspecialchars($employee['AGAMA']) : ''; ?></td>
                      <td><?php echo isset($employee['PENDIDIKAN_TERAKHIR']) ? htmlspecialchars($employee['PENDIDIKAN_TERAKHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_TELEPON']) ? htmlspecialchars($employee['NO_TELEPON']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_KK']) ? htmlspecialchars($employee['NO_KK']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_KTP']) ? htmlspecialchars($employee['NO_KTP']) : ''; ?></td>
                      <td><?php echo isset($employee['GOL_DARAH']) ? htmlspecialchars($employee['GOL_DARAH']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_ORANG_TUA']) ? htmlspecialchars($employee['NAMA_ORANG_TUA']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_SUAMI_ISTRI']) ? htmlspecialchars($employee['NAMA_SUAMI_ISTRI']) : ''; ?></td>
                      <td><?php echo isset($employee['JUMLAH_ANAK']) ? htmlspecialchars($employee['JUMLAH_ANAK']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_ANAK']) ? htmlspecialchars($employee['NAMA_ANAK']) : ''; ?></td>
                      
                      <!-- Contract data cells (KONTRAK 1 AWAL/KONTRAK 1 AKHIR, KONTRAK 2 AWAL/KONTRAK 2 AKHIR, etc.) -->
                      <?php for ($i = 1; $i <= 44; $i++): ?>
                        <?php 
                          // Calculate the actual column indices for AWAL and AKHIR
                          $colIndexAWAL = 35 + ($i - 1) * 2;
                          $colIndexAKHIR = 35 + ($i - 1) * 2 + 1;
                          
                          // Get data for AWAL and AKHIR
                          $keyAWAL = 'KONTRAK_AWAL_' . $colIndexAWAL;
                          $keyAKHIR = 'KONTRAK_AKHIR_' . $colIndexAKHIR;
                          
                          // Get values
                          $valueAWAL = isset($employee[$keyAWAL]) ? $employee[$keyAWAL] : '';
                          $valueAKHIR = isset($employee[$keyAKHIR]) ? $employee[$keyAKHIR] : '';
                        ?>
                        <td><?php echo htmlspecialchars($valueAWAL); ?></td>
                        <td><?php echo htmlspecialchars($valueAKHIR); ?></td>
                      <?php endfor; ?>
                      
                      <!-- Other contract-related columns -->
                      <td><?php echo isset($employee['KONTRAK_AKHIR']) ? htmlspecialchars($employee['KONTRAK_AKHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_REKENING']) ? htmlspecialchars($employee['NO_REKENING']) : ''; ?></td>
                      <td><?php echo isset($employee['TIPE_PTKP']) ? htmlspecialchars($employee['TIPE_PTKP']) : ''; ?></td>
                      <td><?php echo isset($employee['ALASAN_KELUAR']) ? htmlspecialchars($employee['ALASAN_KELUAR']) : ''; ?></td>
                      <td><?php echo isset($employee['KETERANGAN']) ? htmlspecialchars($employee['KETERANGAN']) : ''; ?></td>
                      <td><?php echo isset($employee['LEVEL']) ? htmlspecialchars($employee['LEVEL']) : ''; ?></td>
                      <td><?php echo isset($employee['DL_IDL']) ? htmlspecialchars($employee['DL_IDL']) : ''; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            
            <!-- Debug: Show raw data structure -->
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Raw Data Debug (First Record)</h3>
              </div>
              <div class="panel-body">
                <pre><?php echo isset($preview_data[0]) ? print_r($preview_data[0], true) : 'No data available'; ?></pre>
              </div>
            </div>
            
            <div class="form-group">
              <a href="<?php echo base_url('Employee_import/process_data'); ?>" class="btn btn-success">
                <i class="fa fa-check"></i> Process & Import Data
              </a>
              <a href="<?php echo base_url('Employee_import'); ?>" class="btn btn-default">
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
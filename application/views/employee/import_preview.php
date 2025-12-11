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
                  <tr>
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
                    <th>SEJAK AWAL</th>
                    <th>NOMOR SK</th>
                    <th>TGL.DIANGKAT</th>
                    <th>BPJS NO.KPJ</th>
                    <th>NO. KARTU TRIMAS</th>
                    <th>NO.REKENING</th>
                    <th>TIPE PTKP</th>
                    <th>ALASAN KELUAR</th>
                    <th>KETERANGAN</th>
                    <th>LEVEL</th>
                    <th>DL/IDL</th>
                    <th>STATUS PERNIKAHAN</th>
                    <th>TEMPAT LAHIR</th>
                    <th>TGL LAHIR</th>
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
                    <th>NAMA ORANG TUA</th>
                    <th>NAMA SUAMI / ISTRI</th>
                    <th>JUMLAH ANAK</th>
                    <th>NAMA ANAK</th>
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
                      <td><?php echo isset($employee['SEJAK_AWAL']) ? htmlspecialchars($employee['SEJAK_AWAL']) : ''; ?></td>
                      <td><?php echo isset($employee['NOMOR_SK']) ? htmlspecialchars($employee['NOMOR_SK']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_DIANGKAT']) ? htmlspecialchars($employee['TGL_DIANGKAT']) : ''; ?></td>
                      <td><?php echo isset($employee['BPJS_NO_KPJ']) ? htmlspecialchars($employee['BPJS_NO_KPJ']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_KARTU_TRIMAS']) ? htmlspecialchars($employee['NO_KARTU_TRIMAS']) : ''; ?></td>
                      <td><?php echo isset($employee['NO_REKENING']) ? htmlspecialchars($employee['NO_REKENING']) : ''; ?></td>
                      <td><?php echo isset($employee['TIPE_PTKP']) ? htmlspecialchars($employee['TIPE_PTKP']) : ''; ?></td>
                      <td><?php echo isset($employee['ALASAN_KELUAR']) ? htmlspecialchars($employee['ALASAN_KELUAR']) : ''; ?></td>
                      <td><?php echo isset($employee['KETERANGAN']) ? htmlspecialchars($employee['KETERANGAN']) : ''; ?></td>
                      <td><?php echo isset($employee['LEVEL']) ? htmlspecialchars($employee['LEVEL']) : ''; ?></td>
                      <td><?php echo isset($employee['DL_IDL']) ? htmlspecialchars($employee['DL_IDL']) : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_PERNIKAHAN']) ? htmlspecialchars($employee['STATUS_PERNIKAHAN']) : ''; ?></td>
                      <td><?php echo isset($employee['TEMPAT_LAHIR']) ? htmlspecialchars($employee['TEMPAT_LAHIR']) : ''; ?></td>
                      <td><?php echo isset($employee['TGL_LAHIR']) ? htmlspecialchars($employee['TGL_LAHIR']) : ''; ?></td>
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
                      <td><?php echo isset($employee['NAMA_ORANG_TUA']) ? htmlspecialchars($employee['NAMA_ORANG_TUA']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_SUAMI_ISTRI']) ? htmlspecialchars($employee['NAMA_SUAMI_ISTRI']) : ''; ?></td>
                      <td><?php echo isset($employee['JUMLAH_ANAK']) ? htmlspecialchars($employee['JUMLAH_ANAK']) : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_ANAK']) ? htmlspecialchars($employee['NAMA_ANAK']) : ''; ?></td>
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
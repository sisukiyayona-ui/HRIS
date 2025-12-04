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
                      <td><?php echo isset($employee['NIK']) ? $employee['NIK'] : ''; ?></td>
                      <td><?php echo isset($employee['NAMA']) ? $employee['NAMA'] : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_E_MAIL_PRIBADI']) ? $employee['ALAMAT_E_MAIL_PRIBADI'] : ''; ?></td>
                      <td><?php echo isset($employee['JABATAN']) ? $employee['JABATAN'] : ''; ?></td>
                      <td><?php echo isset($employee['BAGIAN']) ? $employee['BAGIAN'] : ''; ?></td>
                      <td><?php echo isset($employee['SUB_BAGIAN']) ? $employee['SUB_BAGIAN'] : ''; ?></td>
                      <td><?php echo isset($employee['DEPARTEMEN']) ? $employee['DEPARTEMEN'] : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_KARYAWAN']) ? $employee['STATUS_KARYAWAN'] : ''; ?></td>
                      <td><?php echo isset($employee['TGL_MASUK']) ? $employee['TGL_MASUK'] : ''; ?></td>
                      <td><?php echo isset($employee['TGL_KELUAR']) ? $employee['TGL_KELUAR'] : ''; ?></td>
                      <td><?php echo isset($employee['TGL_JEDA']) ? $employee['TGL_JEDA'] : ''; ?></td>
                      <td><?php echo isset($employee['SEJAK_AWAL']) ? $employee['SEJAK_AWAL'] : ''; ?></td>
                      <td><?php echo isset($employee['NOMOR_SK']) ? $employee['NOMOR_SK'] : ''; ?></td>
                      <td><?php echo isset($employee['TGL_DIANGKAT']) ? $employee['TGL_DIANGKAT'] : ''; ?></td>
                      <td><?php echo isset($employee['BPJS_NO_KPJ']) ? $employee['BPJS_NO_KPJ'] : ''; ?></td>
                      <td><?php echo isset($employee['NO_KARTU_TRIMAS']) ? $employee['NO_KARTU_TRIMAS'] : ''; ?></td>
                      <td><?php echo isset($employee['STATUS_PERNIKAHAN']) ? $employee['STATUS_PERNIKAHAN'] : ''; ?></td>
                      <td><?php echo isset($employee['TEMPAT_LAHIR']) ? $employee['TEMPAT_LAHIR'] : ''; ?></td>
                      <td><?php echo isset($employee['TGL_LAHIR']) ? $employee['TGL_LAHIR'] : ''; ?></td>
                      <td><?php echo isset($employee['BULAN_LAHIR']) ? $employee['BULAN_LAHIR'] : ''; ?></td>
                      <td><?php echo isset($employee['USIA']) ? $employee['USIA'] : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_KTP']) ? $employee['ALAMAT_KTP'] : ''; ?></td>
                      <td><?php echo isset($employee['ALAMAT_TINGGAL_SEKARANG']) ? $employee['ALAMAT_TINGGAL_SEKARANG'] : ''; ?></td>
                      <td><?php echo isset($employee['JENIS_KELAMIN']) ? $employee['JENIS_KELAMIN'] : ''; ?></td>
                      <td><?php echo isset($employee['AGAMA']) ? $employee['AGAMA'] : ''; ?></td>
                      <td><?php echo isset($employee['PENDIDIKAN_TERAKHIR']) ? $employee['PENDIDIKAN_TERAKHIR'] : ''; ?></td>
                      <td><?php echo isset($employee['NO_TELEPON']) ? $employee['NO_TELEPON'] : ''; ?></td>
                      <td><?php echo isset($employee['NO_KK']) ? $employee['NO_KK'] : ''; ?></td>
                      <td><?php echo isset($employee['NO_KTP']) ? $employee['NO_KTP'] : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_ORANG_TUA']) ? $employee['NAMA_ORANG_TUA'] : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_SUAMI__ISTRI']) ? $employee['NAMA_SUAMI__ISTRI'] : ''; ?></td>
                      <td><?php echo isset($employee['JUMLAH_ANAK']) ? $employee['JUMLAH_ANAK'] : ''; ?></td>
                      <td><?php echo isset($employee['NAMA_ANAK']) ? $employee['NAMA_ANAK'] : ''; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
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
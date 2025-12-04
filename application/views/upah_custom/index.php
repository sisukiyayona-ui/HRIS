<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 ">
      <div class="x_panel">
        <div class="x_title">
          <h2>Upah Baru <small>View awal fitur Upah (draft)</small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <p>Halaman ini adalah view awal untuk fitur Upah baru. Untuk sementara ditampilkan layout dan contoh tabel agar konsisten dengan fitur lain.</p>

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Periode</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <th>Masa Kerja</th>
                  <th>Hari Kerja</th>
                  <th>Hadir</th>
                  <th>Gaji Pokok</th>
                  <th>Premi Hadir</th>
                  <th>Transport</th>
                  <th>Tunjangan Lain</th>
                  <th>Total Kotor</th>
                  <th>Total Bersih</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($gaji)): ?>
                  <?php $no = 0; foreach ($gaji as $row): $no++; ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo htmlspecialchars($row->periode); ?></td>
                      <td><?php echo htmlspecialchars($row->nik); ?></td>
                      <td><?php echo htmlspecialchars($row->nama); ?></td>
                      <td><?php echo htmlspecialchars($row->bagian); ?></td>
                      <td><?php echo htmlspecialchars($row->jabatan); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($row->masa_kerja); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($row->hari_kerja); ?></td>
                      <td class="text-center"><?php echo htmlspecialchars($row->hari_hadir); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->gaji_pokok,2,',','.'); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->premi_hadir,2,',','.'); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->transport,2,',','.'); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->tunjangan_lain,2,',','.'); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->total_kotor,2,',','.'); ?></td>
                      <td class="text-right"><?php echo number_format((float)$row->total_bersih,2,',','.'); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="15" class="text-center">Tidak ada data gaji.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // small script hook if needed later
  console.log('UpahCustom view loaded');
</script>

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
              <div class="col-md-6">
                <form method="GET" action="<?php echo base_url('rekap/bulanan')?>">
                  <div class="input-group">
                    <select name="bulan" class="form-control" style="display: inline-block; width: auto; margin-right: 5px;">
                      <?php
                      $bulan_list = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                      ];
                      foreach ($bulan_list as $key => $val) {
                        $selected = ($key == $bulan) ? 'selected' : '';
                        echo "<option value='$key' $selected>$val</option>";
                      }
                      ?>
                    </select>
                    <input type="number" name="tahun" class="form-control" value="<?php echo $tahun?>" min="2020" style="display: inline-block; width: auto; margin-right: 5px;">
                    <button class="btn btn-primary" type="submit" style="display: inline-block;">
                      <i class="fa fa-search"></i> Tampilkan
                    </button>
                  </div>
                </form>
              </div>
              <div class="col-md-6 text-right">
                <a href="<?php echo base_url('rekap/export_bulanan?bulan='.$bulan.'&tahun='.$tahun)?>" class="btn btn-success btn-sm">
                  <i class="fa fa-download"></i> Export Excel
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <div class="alert alert-info">
              <strong>Periode:</strong> <?php echo date('d M Y', strtotime($start_date))?> s/d <?php echo date('d M Y', strtotime($end_date))?> | 
              <strong>Total Karyawan Aktif:</strong> <?php echo count($rekap)?> orang
            </div>
            
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="tbl_rekap" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th style="text-align: center; width: 5%;">No</th>
                    <th style="text-align: center; width: 12%;">NIK</th>
                    <th style="text-align: center; width: 30%;">Nama Karyawan</th>
                    <th style="text-align: center; width: 12%;">Tepat Waktu</th>
                    <th style="text-align: center; width: 12%;">Terlambat</th>
                    <th style="text-align: center; width: 12%;">Tidak Masuk</th>
                    <th style="text-align: center; width: 12%;">Ijin/Cuti</th>
                    <th style="text-align: center; width: 15%;">Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  if (count($rekap) > 0) {
                    foreach ($rekap as $data) {
                      echo '<tr>';
                      echo '<td style="text-align: center;">'.$no.'</td>';
                      echo '<td style="text-align: center;">'.$data['nik'].'</td>';
                      echo '<td>'.$data['nama_karyawan'].'</td>';
                      echo '<td style="text-align: center;"><span class="label label-success">'.$data['tepat_waktu'].'</span></td>';
                      echo '<td style="text-align: center;"><span class="label label-warning">'.$data['terlambat'].'</span></td>';
                      echo '<td style="text-align: center;"><span class="label label-danger">'.$data['tidak_masuk'].'</span></td>';
                      echo '<td style="text-align: center;"><span class="label label-info">'.$data['ijin_cuti'].'</span></td>';
                      echo '<td style="text-align: center;">'.$data['keterangan'].'</td>';
                      echo '</tr>';
                      $no++;
                    }
                  } else {
                    echo '<tr>';
                    echo '<td colspan="8" style="text-align: center; padding: 20px;"><em>Tidak ada data karyawan</em></td>';
                    echo '</tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
$(document).ready(function() {
  $('#tbl_rekap').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "pageLength": 50,
    "language": {
      "url": "<?php echo base_url('assets/dataTables/Indonesian.json')?>",
    }
  });
});
</script>

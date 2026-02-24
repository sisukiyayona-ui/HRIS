<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Rekap Absensi Harian</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-6">
                <form method="GET" action="<?php echo base_url('rekap/absensi')?>">
                  <div class="input-group">
                    <input type="date" class="form-control" name="tanggal" value="<?php echo $tanggal?>" required>
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i> Tampilkan
                      </button>
                    </span>
                  </div>
                </form>
              </div>
              <div class="col-md-6 text-right">
                <a href="<?php echo base_url('rekap/tarik_ui')?>" class="btn btn-success btn-sm">
                  <i class="fa fa-download"></i> Tarik Data dari Mesin
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <div class="alert alert-info">
              <strong>Tanggal:</strong> <?php echo date('d F Y', strtotime($tanggal))?> | 
              <strong>Total Karyawan Aktif:</strong> <?php echo $statistik['total']?> orang
            </div>
            
            <!-- <div class="alert alert-warning" style="margin-bottom: 15px;">
              <i class="fa fa-info-circle"></i> <strong>Logika Jam Pulang:</strong>
              <ul style="margin: 5px 0 0 20px;">
                <li><strong>Jam Masuk:</strong> Tap pertama (paling awal)</li>
                <li><strong>Jam Pulang:</strong> Hanya muncul jika tap terakhir <strong>lebih dari 4 jam</strong> dari tap pertama</li>
                <li><strong>Tap &lt; 4 jam:</strong> Dianggap absen masuk berulang (misal: keluar-masuk area)</li>
              </ul>
            </div> -->
            
            <!-- Statistik Keterangan -->
            <div class="row" style="margin-bottom: 20px;">
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="background: #5cb85c; color: white; padding: 15px; border-radius: 5px; text-align: center;">
                  <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $statistik['tepat_waktu']?></h3>
                  <p style="margin: 5px 0 0 0; font-size: 14px;">Tepat Waktu</p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" data-toggle="modal" data-target="#modalTerlambat" style="background: #f0ad4e; color: white; padding: 15px; border-radius: 5px; text-align: center; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                  <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $statistik['terlambat']?></h3>
                  <p style="margin: 5px 0 0 0; font-size: 14px;">Terlambat <i class="fa fa-eye"></i></p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" data-toggle="modal" data-target="#modalBelumAbsen" style="background: #d9534f; color: white; padding: 15px; border-radius: 5px; text-align: center; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                  <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $statistik['belum_absen']?></h3>
                  <p style="margin: 5px 0 0 0; font-size: 14px;">Belum Absen <i class="fa fa-eye"></i></p>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="background: #5bc0de; color: white; padding: 15px; border-radius: 5px; text-align: center;">
                  <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $statistik['total']?></h3>
                  <p style="margin: 5px 0 0 0; font-size: 14px;">Total</p>
                </div>
              </div>
            </div>
            
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="tbl_absensi" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th><center>No</center></th>
                    <th><center>NIK</center></th>
                    <th><center>Nama Karyawan</center></th>
                    <th><center>Bagian</center></th>
                    <th><center>Mode</center></th>
                    <th><center>Jam Masuk</center></th>
                    <th><center>Jam Pulang</center></th>
                    <th><center>Keterangan</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  foreach ($absensi as $data) {
                    // Hitung keterangan
                    $keterangan = '';
                    $badge_class = '';
                    $is_shift = !empty($data->jam_in_shift);
                    $patokan = $is_shift ? $data->jam_in_shift : $jam_masuk_std;
                    
                    if (empty($data->jam_masuk) && empty($data->jam_pulang)) {
                      $keterangan = 'Belum Absen';
                      $badge_class = 'label-danger';
                    } else if (!empty($data->jam_masuk)) {
                      $jam_masuk_time = date('H:i:s', strtotime($data->jam_masuk));
                      
                      if ($jam_masuk_time > $patokan) {
                        $diff = strtotime($jam_masuk_time) - strtotime($patokan);
                        $menit_telat = floor($diff / 60);
                        $keterangan = 'Telat ' . $menit_telat . ' menit';
                        $badge_class = 'label-warning';
                      } else {
                        $keterangan = 'Tepat Waktu';
                        $badge_class = 'label-success';
                      }
                      
                      if (empty($data->jam_pulang)) {
                        $keterangan .= ' (Belum Pulang)';
                      }
                    }
                    
                    $jam_masuk_display = !empty($data->jam_masuk) ? date('H:i', strtotime($data->jam_masuk)) : '-';
                    $jam_pulang_display = !empty($data->jam_pulang) ? date('H:i', strtotime($data->jam_pulang)) : '-';
                    $mode_display = $is_shift 
                      ? ('Shift: '.(!empty($data->nama_shift)?$data->nama_shift:'-').' ('.($data->jam_in_shift ?: '-') .' - '.($data->jam_out_shift ?: '-') .')')
                      : ('Normal ('.$jam_masuk_std.' )');
                    ?>
                    <tr>
                      <td><center><?php echo $no++?></center></td>
                      <td><?php echo $data->nik?></td>
                      <td><?php echo $data->nama_karyawan?></td>
                      <td><?php echo isset($data->nama_bagian) && $data->nama_bagian ? $data->nama_bagian : '-' ?></td>
                      <td><center><?php echo $mode_display?></center></td>
                      <td><center><?php echo $jam_masuk_display?></center></td>
                      <td><center><?php echo $jam_pulang_display?></center></td>
                      <td><center>
                        <span class="label <?php echo $badge_class?>">
                          <?php echo $keterangan?>
                        </span>
                        <?php if($data->total_tap > 1) { ?>
                          <br><a href="#" class="btn-debug-tap" data-nik="<?php echo $data->nik?>" data-tanggal="<?php echo $tanggal?>">
                            <small><i class="fa fa-search"></i> Lihat Detail (<?php echo $data->total_tap?> tap)</small>
                          </a>
                        <?php } ?>
                      </center></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<!-- Modal Debug Tap -->
<div class="modal fade" id="modalDebugTap" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #3498db; color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-search"></i> Detail Tap Karyawan</h4>
      </div>
      <div class="modal-body">
        <div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Terlambat -->
<div class="modal fade" id="modalTerlambat" tabindex="-1" role="dialog" aria-labelledby="modalTerlambatLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #f0ad4e; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
          <h4 class="modal-title" id="modalTerlambatLabel">
            <i class="fa fa-clock-o"></i> 
            Daftar Karyawan Terlambat - <?php echo date('d F Y', strtotime($tanggal))?>
          </h4>
          <div>
            <button type="button" class="btn btn-sm btn-success" id="exportTerlambatBtn" style="color: white; margin-right: 10px;">
              <i class="fa fa-file-excel-o"></i> Export Excel
            </button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1; font-size: 24px; line-height: 1;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Total:</strong> <span id="total_terlambat">0</span> karyawan aktif terlambat
        </div>
        
        <div class="table-responsive">
          <table id="tbl_terlambat" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th><center>No</center></th>
                <th><center>NIK</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Bagian</center></th>
                <th><center>Jam Masuk</center></th>
                <th><center>Keterangan</center></th>
                <th><center>Validasi</center></th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be loaded via AJAX -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Validasi Terlambat -->
<div class="modal fade" id="modalValidasiTerlambat" tabindex="-1" role="dialog" aria-labelledby="modalValidasiTerlambatLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #337ab7; color:#fff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalValidasiTerlambatLabel"><i class="fa fa-check"></i> Validasi Ketidakhadiran</h4>
      </div>
      <div class="modal-body">
        <form id="formValidasiTerlambat">
          <input type="hidden" name="recid_karyawan" id="valt_recid_karyawan">
          <input type="hidden" name="tanggal" id="valt_tanggal" value="<?php echo $tanggal?>">

          <div class="form-group">
            <label>Karyawan</label>
            <input type="text" class="form-control" id="valt_nama" readonly>
          </div>

          <div class="form-group">
            <label>Jenis Izin</label>
            <select name="jenis" id="valt_jenis" class="form-control" required>
              <option value="">-- Pilih --</option>
              <option value="SAKIT">SAKIT (Sakit)</option>
              <option value="MANGKIR">MANGKIR (Tanpa Izin)</option>
              <option value="TANPA KETERANGAN">TANPA KETERANGAN (Alpa)</option>
              <option value="CM">CM (Cuti Melahirkan)</option>
              <option value="CT">CT (Cuti Tahunan)</option>
              <option value="CK">CK (Cuti Khusus)</option>
              <option value="CS">CS (Cuti Sakit)</option>
              <option value="CN">CN (Cuti Nikah)</option>
              <option value="LAIN">Lain-Lainnya</option>
            </select>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="valt_keterangan" class="form-control" rows="3" placeholder="Opsional"></textarea>
          </div>
        </form>
        <div id="valt_alert" style="display:none" class="alert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" id="btnSimpanValidasiTerlambat" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalBelumAbsen" tabindex="-1" role="dialog" aria-labelledby="modalBelumAbsenLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #d9534f; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
          <h4 class="modal-title" id="modalBelumAbsenLabel">
            <i class="fa fa-exclamation-triangle"></i> 
            Daftar Karyawan Belum Absen - <?php echo date('d F Y', strtotime($tanggal))?>
          </h4>
          <div>
            <button type="button" class="btn btn-sm btn-success" id="exportBelumAbsenBtn" style="color: white; margin-right: 10px;">
              <i class="fa fa-file-excel-o"></i> Export Excel
            </button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1; font-size: 24px; line-height: 1;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Total:</strong> <?php echo count($belum_absen)?> karyawan aktif belum melakukan absensi
        </div>
        
        <div class="table-responsive">
          <table id="tbl_belum_absen" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th><center>No</center></th>
                <th><center>NIK</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Bagian</center></th>
                <th><center>Validasi</center></th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $no = 1;
              foreach ($belum_absen as $data) { ?>
                <tr>
                  <td><center><?php echo $no++?></center></td>
                  <td><?php echo $data->nik?></td>
                  <td><?php echo $data->nama_karyawan?></td>
                  <td><?php echo isset($data->nama_bagian) && $data->nama_bagian ? $data->nama_bagian : '-' ?></td>
                  <td class="text-center">
                    <button class="btn btn-xs btn-primary btn-validasi-belum" 
                            data-recid_karyawan="<?php echo $data->recid_karyawan?>"
                            data-nama="<?php echo htmlspecialchars($data->nama_karyawan, ENT_QUOTES)?>"
                            data-nik="<?php echo $data->nik?>"
                            data-tanggal="<?php echo $tanggal?>">
                      <i class="fa fa-check"></i> Validasi
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Validasi Belum Absen -->
<div class="modal fade" id="modalValidasiBelumAbsen" tabindex="-1" role="dialog" aria-labelledby="modalValidasiBelumAbsenLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #337ab7; color:#fff;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalValidasiBelumAbsenLabel"><i class="fa fa-check"></i> Validasi Ketidakhadiran</h4>
      </div>
      <div class="modal-body">
        <form id="formValidasiBelumAbsen">
          <input type="hidden" name="recid_karyawan" id="val_recid_karyawan">
          <input type="hidden" name="tanggal" id="val_tanggal" value="<?php echo $tanggal?>">

          <div class="form-group">
            <label>Karyawan</label>
            <input type="text" class="form-control" id="val_nama" readonly>
          </div>

          <div class="form-group">
            <label>Jenis Izin</label>
            <select name="jenis" id="val_jenis" class="form-control" required>
              <option value="">-- Pilih --</option>
              <option value="SAKIT">SAKIT (Sakit)</option>
              <option value="MANGKIR">MANGKIR (Tanpa Izin)</option>
              <option value="TANPA KETERANGAN">TANPA KETERANGAN (Alpa)</option>
              <option value="CM">CM (Cuti Melahirkan)</option>
              <option value="CT">CT (Cuti Tahunan)</option>
              <option value="CK">CK (Cuti Khusus)</option>
              <option value="CS">CS (Cuti Sakit)</option>
              <option value="CN">CN (Cuti Nikah)</option>
              <option value="LAIN">Lain-Lainnya</option>
            </select>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="val_keterangan" class="form-control" rows="3" placeholder="Opsional"></textarea>
          </div>
        </form>
        <div id="val_alert" style="display:none" class="alert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" id="btnSimpanValidasi" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
  </div>

<script>
$(document).ready(function() {
  $('#tbl_absensi').DataTable({
    "pageLength": 25,
    "order": [[2, "asc"]], // Sort by nama
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
    },
    "columnDefs": [{
      "targets": 0, // Kolom No (index 0)
      "orderable": false, // Tidak bisa diurutkan
      "searchable": false, // Tidak ikut search
      "render": function (data, type, row, meta) {
        // meta.row = nomor baris (0-based index)
        // meta.settings._iDisplayStart = index awal di halaman ini
        return meta.row + meta.settings._iDisplayStart + 1;
      }
    }]
  });
  
  // DataTable untuk modal belum absen
  $('#tbl_belum_absen').DataTable({
    "pageLength": 10,
    "order": [[1, "asc"]], // Sort by NIK
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
    }
  });
  
  // DataTable untuk modal terlambat
  $('#tbl_terlambat').DataTable({
    "pageLength": 10,
    "order": [[1, "asc"]], // Sort by NIK
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
    }
  });
  
  // Handle modal terlambat show event
  $('#modalTerlambat').on('show.bs.modal', function() {
    loadTerlambatData();
  });
  
  // Handle export terlambat button click
  $(document).on('click', '#exportTerlambatBtn', function() {
    const tanggal = '<?php echo $tanggal?>';
    const url = '<?php echo base_url('rekap/export_terlambat_list')?>' + '?tanggal=' + encodeURIComponent(tanggal);
    window.open(url, '_blank');
  });
  
  // Handle export belum absen button click
  $(document).on('click', '#exportBelumAbsenBtn', function() {
    const tanggal = '<?php echo $tanggal?>';
    const url = '<?php echo base_url('rekap/export_belum_absen_list')?>' + '?tanggal=' + encodeURIComponent(tanggal);
    window.open(url, '_blank');
  });
  
  function loadTerlambatData() {
    $.ajax({
      url: '<?php echo base_url('rekap/get_terlambat_data')?>',
      method: 'POST',
      data: { tanggal: '<?php echo $tanggal?>' },
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          renderTerlambatTable(res.data);
          $('#total_terlambat').text(res.data.length);
        } else {
          alert('Error: ' + res.message);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading terlambat data:', error);
        alert('Terjadi kesalahan saat memuat data terlambat');
      }
    });
  }
  
  function renderTerlambatTable(data) {
    let tbody = $('#tbl_terlambat tbody');
    tbody.empty();
    
    if (data && data.length > 0) {
      data.forEach(function(karyawan, idx) {
        let row = `
          <tr>
            <td><center>${idx + 1}</center></td>
            <td>${karyawan.nik}</td>
            <td>${karyawan.nama_karyawan}</td>
            <td>${karyawan.nama_bagian || '-'}</td>
            <td><center>${karyawan.jam_masuk_display}</center></td>
            <td><center><span class="label label-warning">${karyawan.keterangan}</span></center></td>
            <td class="text-center">
              <button class="btn btn-xs btn-primary btn-validasi-terlambat" 
                      data-recid_karyawan="${karyawan.recid_karyawan}"
                      data-nama="${karyawan.nama_karyawan}"
                      data-nik="${karyawan.nik}"
                      data-tanggal="<?php echo $tanggal?>">
                <i class="fa fa-check"></i> Validasi
              </button>
            </td>
          </tr>
        `;
        tbody.append(row);
      });
    } else {
      tbody.append('<tr><td colspan="7" class="text-center">Tidak ada data karyawan terlambat</td></tr>');
    }
  }
  
  // Handle klik validasi terlambat
  $(document).on('click', '.btn-validasi-terlambat', function() {
    const recid = $(this).data('recid_karyawan');
    const nama  = $(this).data('nama');
    const nik   = $(this).data('nik');
    const tgl   = $(this).data('tanggal');

    $('#valt_recid_karyawan').val(recid);
    $('#valt_tanggal').val(tgl);
    $('#valt_nama').val(nik + ' - ' + nama);
    $('#valt_jenis').val('');
    $('#valt_keterangan').val('');
    $('#valt_alert').hide().removeClass('alert-danger alert-success').text('');
    $('#modalValidasiTerlambat').modal('show');
  });

  // Simpan validasi terlambat
  $('#btnSimpanValidasiTerlambat').on('click', function(){
    const form = $('#formValidasiTerlambat');
    const data = form.serialize();
    $('#btnSimpanValidasiTerlambat').prop('disabled', true).text('Menyimpan...');

    $.ajax({
      url: '<?php echo base_url('rekap/simpan_izin_terlambat')?>',
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function(res){
      const alert = $('#valt_alert');
      if(res.success){
        alert.removeClass('alert-danger').addClass('alert alert-success').text(res.message).show();
        setTimeout(function(){ 
          $('#modalValidasiTerlambat').modal('hide');
          loadTerlambatData(); // Refresh data
        }, 800);
      } else {
        alert.removeClass('alert-success').addClass('alert alert-danger').text(res.message||'Gagal menyimpan').show();
      }
    }).fail(function(xhr){
      $('#valt_alert').removeClass('alert-success').addClass('alert alert-danger').text('Error: ' + (xhr.responseText||xhr.statusText)).show();
    }).always(function(){
      $('#btnSimpanValidasiTerlambat').prop('disabled', false).text('Simpan');
    });
  });
  
  // Handle klik debug tap
  $(document).on('click', '.btn-debug-tap', function(e) {
    e.preventDefault();
    let nik = $(this).data('nik');
    let tanggal = $(this).data('tanggal');
    
    // Show modal with loading
    $('#modalDebugTap .modal-title').html('Memuat data...');
    $('#modalDebugTap .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Mengambil detail tap untuk NIK: ' + nik + '</p></div>');
    $('#modalDebugTap').modal('show');
    
    $.ajax({
      url: '<?php echo base_url('rekap/debug_tap')?>',
      method: 'GET',
      data: { nik: nik, tanggal: tanggal },
      dataType: 'json',
      success: function(res) {
        $('#modalDebugTap .modal-title').html('Detail Tap Karyawan: ' + res.nik);
        
        let html = '<div style="text-align: left;">';
        html += '<dl class="dl-horizontal">';
        html += '<dt>NIK:</dt><dd>' + res.nik + '</dd>';
        html += '<dt>Tanggal:</dt><dd>' + res.tanggal + '</dd>';
        html += '<dt>Total Tap:</dt><dd><strong>' + res.total_taps + '</strong></dd>';
        html += '</dl>';
        
        if (res.mapped_pins && res.mapped_pins.length > 0) {
          html += '<h5><i class="fa fa-link"></i> Mapped PINs:</h5>';
          html += '<table class="table table-bordered table-sm">';
          html += '<thead><tr><th>PIN</th><th>Device SN</th></tr></thead><tbody>';
          res.mapped_pins.forEach(function(pin) {
            html += '<tr><td>' + pin.pin + '</td><td>' + pin.device_sn + '</td></tr>';
          });
          html += '</tbody></table>';
        }
        
        if (res.taps && res.taps.length > 0) {
          html += '<h5><i class="fa fa-list"></i> Detail Tap:</h5>';
          html += '<div style="max-height: 300px; overflow-y: auto;">';
          html += '<table class="table table-bordered table-striped table-sm">';
          html += '<thead><tr><th>No</th><th>PIN</th><th>Waktu</th><th>UID</th></tr></thead>';
          html += '<tbody>';
          res.taps.forEach(function(tap, index) {
            let rowClass = index === 0 ? 'success' : (index === res.taps.length - 1 ? 'warning' : '');
            html += '<tr class="' + rowClass + '">';
            html += '<td>' + (index + 1) + '</td>';
            html += '<td>' + tap.nik + '</td>';
            html += '<td><strong>' + tap.waktu + '</strong></td>';
            html += '<td>' + (tap.uid || '-') + '</td>';
            html += '</tr>';
          });
          html += '</tbody></table>';
          html += '</div>';
          
          html += '<div class="alert alert-info" style="margin-top: 15px;">';
          html += '<strong><i class="fa fa-lightbulb-o"></i> Analisis:</strong><br>';
          if (res.total_taps > 1) {
            html += '✓ Karyawan ini tap <strong>' + res.total_taps + ' kali</strong> hari ini.<br>';
            if (res.mapped_pins.length > 1) {
              html += '⚠ Karyawan memiliki <strong>' + res.mapped_pins.length + ' PIN berbeda</strong> di ' + res.mapped_pins.length + ' mesin.<br>';
              html += '→ <strong>Kemungkinan: Tap di beberapa mesin yang berbeda.</strong><br>';
            } else {
              html += '→ <strong>Kemungkinan: Tap berulang di mesin yang sama.</strong><br>';
            }
            
            // Hitung selisih jam antara tap pertama dan terakhir
            if (res.taps.length >= 2) {
              let firstTap = new Date(res.taps[0].waktu);
              let lastTap = new Date(res.taps[res.taps.length - 1].waktu);
              let diffHours = Math.abs(lastTap - firstTap) / 36e5; // Convert ms to hours
              html += '<br><strong>Selisih waktu:</strong> ' + diffHours.toFixed(1) + ' jam<br>';
              
              if (diffHours >= 4) {
                html += '✓ Selisih <strong>≥ 4 jam</strong> → Tap terakhir dihitung sebagai <strong>Jam Pulang</strong>';
              } else {
                html += '⚠ Selisih <strong>&lt; 4 jam</strong> → Tap terakhir <strong>TIDAK dihitung</strong> sebagai jam pulang (dianggap tap masuk berulang)';
              }
            }
            
            html += '<br><br><em><i class="fa fa-info-circle"></i> Tap pertama (hijau) = Jam Masuk, Tap terakhir (kuning) = Jam Pulang (jika ≥4 jam)</em>';
          }
          html += '</div>';
        } else {
          html += '<p class="text-muted">Tidak ada data tap.</p>';
        }
        
        html += '</div>';
        
        $('#modalDebugTap .modal-body').html(html);
      },
      error: function(xhr) {
        $('#modalDebugTap .modal-title').html('Error');
        $('#modalDebugTap .modal-body').html('<div class="alert alert-danger">Gagal memuat data: ' + xhr.responseText + '</div>');
      }
    });
  });

  // Open modal validasi
  $(document).on('click', '.btn-validasi-belum', function() {
    const recid = $(this).data('recid_karyawan');
    const nama  = $(this).data('nama');
    const nik   = $(this).data('nik');
    const tgl   = $(this).data('tanggal');

    $('#val_recid_karyawan').val(recid);
    $('#val_tanggal').val(tgl);
    $('#val_nama').val(nik + ' - ' + nama);
    $('#val_jenis').val('');
    $('#val_keterangan').val('');
    $('#val_alert').hide().removeClass('alert-danger alert-success').text('');
    $('#modalValidasiBelumAbsen').modal('show');
  });

  // Simpan validasi
  $('#btnSimpanValidasi').on('click', function(){
    const form = $('#formValidasiBelumAbsen');
    const data = form.serialize();
    $('#btnSimpanValidasi').prop('disabled', true).text('Menyimpan...');

    $.ajax({
      url: '<?php echo base_url('rekap/simpan_izin_belum_absen')?>',
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function(res){
      const alert = $('#val_alert');
      if(res.success){
        alert.removeClass('alert-danger').addClass('alert alert-success').text(res.message).show();
        setTimeout(function(){ window.location.reload(); }, 800);
      } else {
        alert.removeClass('alert-success').addClass('alert alert-danger').text(res.message||'Gagal menyimpan').show();
      }
    }).fail(function(xhr){
      $('#val_alert').removeClass('alert-success').addClass('alert alert-danger').text('Error: ' + (xhr.responseText||xhr.statusText)).show();
    }).always(function(){
      $('#btnSimpanValidasi').prop('disabled', false).text('Simpan');
    });
  });
});
</script>

  </div>
</div>
<!-- /page content -->

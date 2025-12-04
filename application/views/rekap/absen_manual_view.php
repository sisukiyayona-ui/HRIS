<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-hand-pointer-o"></i> Konfirmasi Absen Manual</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <!-- Filter Panel -->
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-filter"></i> Filter Data</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form id="formFilter" class="form-inline">
              <div class="form-group">
                <label>Tanggal:</label>
                <input type="date" class="form-control" id="filterTanggal" name="tanggal" 
                       value="<?php echo date('Y-m-d'); ?>" required>
              </div>
              
              <div class="form-group">
                <label>Bagian:</label>
                <select class="form-control" id="filterBagian" name="bagian" style="min-width: 200px;">
                  <option value="">-- Semua Bagian --</option>
                  <?php 
                  if (isset($bagian_list) && !empty($bagian_list)) {
                    foreach($bagian_list as $bag): 
                  ?>
                    <option value="<?php echo $bag->recid_bag; ?>"><?php echo $bag->nama_bag; ?></option>
                  <?php 
                    endforeach;
                  }
                  ?>
                </select>
              </div>
              
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Tampilkan Data
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Info Panel -->
    <div class="row" id="infoPanel" style="display:none;">
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sudah Absen</span>
            <span class="info-box-number" id="totalSudahAbsen">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="fa fa-times"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Belum Absen</span>
            <span class="info-box-number" id="totalBelumAbsen">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-orange">
          <span class="info-box-icon"><i class="fa fa-plane"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sedang Dinas</span>
            <span class="info-box-number" id="totalSedangDinas">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-blue">
          <span class="info-box-icon"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Karyawan</span>
            <span class="info-box-number" id="totalKaryawan">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Hadir</span>
            <span class="info-box-number" id="totalHadir">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-percent"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">% Kehadiran</span>
            <span class="info-box-number" id="persenKehadiran">0%</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div class="row" id="loadingSection" style="display:none;">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_content text-center">
            <h3><i class="fa fa-spinner fa-spin"></i> Memuat data...</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Karyawan Belum Absen -->
    <div class="row" id="dataSection" style="display:none;">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-list"></i> Daftar Karyawan - <span id="tanggalTampil"></span></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#belumAbsen" role="tab" data-toggle="tab">
                  <i class="fa fa-times-circle text-danger"></i> Belum Absen (<span id="countBelumAbsen">0</span>)
                </a>
              </li>
              <li role="presentation">
                <a href="#sudahAbsen" role="tab" data-toggle="tab">
                  <i class="fa fa-check-circle text-success"></i> Sudah Absen (<span id="countSudahAbsen">0</span>)
                </a>
              </li>
              <li role="presentation">
                <a href="#sedangDinas" role="tab" data-toggle="tab">
                  <i class="fa fa-plane text-warning"></i> Sedang Dinas (<span id="countSedangDinas">0</span>)
                </a>
              </li>
            </ul>

            <div class="tab-content" style="margin-top: 20px;">
              <!-- Tab Belum Absen -->
              <div role="tabpanel" class="tab-pane active" id="belumAbsen">
                <table class="table table-bordered table-striped" id="tblBelumAbsen">
                  <thead>
                    <tr style="background: #34495e; color: white;">
                      <th style="width: 50px;">No</th>
                      <th style="width: 120px;">NIK</th>
                      <th>Nama Karyawan</th>
                      <th style="width: 200px;">Bagian</th>
                      <th style="width: 150px;">Jabatan</th>
                      <th style="width: 150px;">Jam Masuk</th>
                      <th style="width: 150px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data akan di-load via JavaScript -->
                  </tbody>
                </table>
              </div>

              <!-- Tab Sudah Absen -->
              <div role="tabpanel" class="tab-pane" id="sudahAbsen">
                <table class="table table-bordered table-striped" id="tblSudahAbsen">
                  <thead>
                    <tr style="background: #27ae60; color: white;">
                      <th style="width: 50px;">No</th>
                      <th style="width: 120px;">NIK</th>
                      <th>Nama Karyawan</th>
                      <th style="width: 200px;">Bagian</th>
                      <th style="width: 150px;">Jabatan</th>
                      <th style="width: 120px;">Jam Masuk</th>
                      <th style="width: 120px;">Jam Pulang</th>
                      <th style="width: 100px;">Total Tap</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data akan di-load via JavaScript -->
                  </tbody>
                </table>
              </div>
              
              <!-- Tab Sedang Dinas -->
              <div role="tabpanel" class="tab-pane" id="sedangDinas">
                <div class="alert alert-warning">
                  <i class="fa fa-plane"></i> 
                  <strong>Info:</strong> Karyawan yang sedang dinas keluar otomatis dihitung sebagai hadir dan tidak perlu absen manual.
                </div>
                <table class="table table-bordered table-striped" id="tblSedangDinas">
                  <thead>
                    <tr style="background: #f39c12; color: white;">
                      <th style="width: 50px;">No</th>
                      <th style="width: 120px;">NIK</th>
                      <th>Nama Karyawan</th>
                      <th style="width: 200px;">Bagian</th>
                      <th style="width: 150px;">Jabatan</th>
                      <th>Keterangan Dinas</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data akan di-load via JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Modal Input Absen Manual -->
<div class="modal fade" id="modalAbsenManual" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #3498db; color: white;">
        <button type="button" class="close" data-dismiss="modal" style="color: white;">
          <span>&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-hand-pointer-o"></i> Konfirmasi Absen Manual</h4>
      </div>
      <form id="formAbsenManual">
        <div class="modal-body">
          <input type="hidden" id="recid_karyawan" name="recid_karyawan">
          <input type="hidden" id="tanggal_absen" name="tanggal">
          
          <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> 
            <strong>Info:</strong> Absen manual ini akan dicatat sebagai kehadiran karyawan pada tanggal tersebut.
          </div>
          
          <div class="form-group">
            <label>NIK:</label>
            <input type="text" class="form-control" id="nik_tampil" readonly>
          </div>
          
          <div class="form-group">
            <label>Nama Karyawan:</label>
            <input type="text" class="form-control" id="nama_tampil" readonly>
          </div>
          
          <div class="form-group">
            <label>Bagian:</label>
            <input type="text" class="form-control" id="bagian_tampil" readonly>
          </div>
          
          <div class="form-group">
            <label>Tanggal Absen:</label>
            <input type="text" class="form-control" id="tanggal_tampil" readonly>
          </div>
          
          <div class="form-group">
            <label>Jam Masuk: <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" 
                   value="08:00" required>
            <small class="text-muted">Default: 08:00 (bisa disesuaikan)</small>
          </div>
          
          <div class="form-group">
            <label>Keterangan (Opsional):</label>
            <textarea class="form-control" id="keterangan" name="keterangan" 
                      rows="3" placeholder="Contoh: Direksi, Lupa absen, dll..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary" id="btnSubmitAbsen">
            <i class="fa fa-check"></i> Konfirmasi Absen
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.info-box {
  display: flex;
  padding: 15px;
  border-radius: 5px;
  color: white;
  margin-bottom: 15px;
}
.info-box-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 70px;
  font-size: 35px;
}
.info-box-content {
  flex: 1;
  padding-left: 15px;
}
.info-box-text {
  display: block;
  font-size: 14px;
  opacity: 0.9;
}
.info-box-number {
  display: block;
  font-size: 28px;
  font-weight: bold;
}
.bg-green { background: #27ae60; }
.bg-red { background: #e74c3c; }
.bg-orange { background: #f39c12; }
.bg-blue { background: #3498db; }
.bg-aqua { background: #00c0ef; }
.bg-yellow { background: #f1c40f; }
</style>

<script>
$(document).ready(function() {
  console.log('🚀 Absen Manual page loaded');
  
  // Form filter submit
  $('#formFilter').on('submit', function(e) {
    e.preventDefault();
    loadDataAbsen();
  });
  
  // Load data on page load
  loadDataAbsen();
});

function loadDataAbsen() {
  let tanggal = $('#filterTanggal').val();
  let bagian = $('#filterBagian').val();
  
  console.log('📋 Loading data for:', tanggal, 'Bagian:', bagian || 'Semua');
  
  $('#loadingSection').show();
  $('#dataSection').hide();
  $('#infoPanel').hide();
  
  $.ajax({
    url: '<?php echo base_url('rekap/get_data_absen_manual')?>',
    method: 'POST',
    data: { tanggal: tanggal, bagian: bagian },
    dataType: 'json',
    success: function(res) {
      console.log('✅ Response:', res);
      $('#loadingSection').hide();
      
      if (res.success) {
        renderTables(res.data);
        updateInfo(res.summary);
        
        $('#tanggalTampil').text(formatTanggal(tanggal));
        $('#dataSection').show();
        $('#infoPanel').show();
      } else {
        alert('Error: ' + res.message);
      }
    },
    error: function(xhr, status, error) {
      console.error('❌ Error:', error);
      $('#loadingSection').hide();
      alert('Terjadi kesalahan: ' + error);
    }
  });
}

function renderTables(data) {
  // Render tabel belum absen
  let htmlBelum = '';
  if (data.belum_absen && data.belum_absen.length > 0) {
    data.belum_absen.forEach(function(karyawan, idx) {
      htmlBelum += '<tr>';
      htmlBelum += '<td>' + (idx + 1) + '</td>';
      htmlBelum += '<td>' + karyawan.nik + '</td>';
      htmlBelum += '<td>' + karyawan.nama_karyawan + '</td>';
      htmlBelum += '<td>' + karyawan.nama_bagian + '</td>';
      htmlBelum += '<td>' + karyawan.nama_jabatan + '</td>';
      htmlBelum += '<td><input type="time" class="form-control input-sm" id="jam_' + karyawan.recid_karyawan + '" value="08:00"></td>';
      htmlBelum += '<td>';
      htmlBelum += '<button class="btn btn-success btn-sm" onclick="konfirmasiAbsen(\'' + karyawan.recid_karyawan + '\', \'' + karyawan.nik + '\', \'' + karyawan.nama_karyawan + '\', \'' + karyawan.nama_bagian + '\')">';
      htmlBelum += '<i class="fa fa-check"></i> Absen</button>';
      htmlBelum += '</td>';
      htmlBelum += '</tr>';
    });
  } else {
    htmlBelum = '<tr><td colspan="7" class="text-center"><i class="fa fa-check-circle text-success"></i> Semua karyawan sudah absen</td></tr>';
  }
  $('#tblBelumAbsen tbody').html(htmlBelum);
  $('#countBelumAbsen').text(data.belum_absen ? data.belum_absen.length : 0);
  
  // Render tabel sudah absen
  let htmlSudah = '';
  if (data.sudah_absen && data.sudah_absen.length > 0) {
    data.sudah_absen.forEach(function(karyawan, idx) {
      htmlSudah += '<tr>';
      htmlSudah += '<td>' + (idx + 1) + '</td>';
      htmlSudah += '<td>' + karyawan.nik + '</td>';
      htmlSudah += '<td>' + karyawan.nama_karyawan + '</td>';
      htmlSudah += '<td>' + karyawan.nama_bagian + '</td>';
      htmlSudah += '<td>' + karyawan.nama_jabatan + '</td>';
      htmlSudah += '<td>' + (karyawan.jam_masuk || '-') + '</td>';
      htmlSudah += '<td>' + (karyawan.jam_pulang || '-') + '</td>';
      htmlSudah += '<td class="text-center">' + (karyawan.total_tap || 0) + '</td>';
      htmlSudah += '</tr>';
    });
  } else {
    htmlSudah = '<tr><td colspan="8" class="text-center">Belum ada karyawan yang absen</td></tr>';
  }
  $('#tblSudahAbsen tbody').html(htmlSudah);
  $('#countSudahAbsen').text(data.sudah_absen ? data.sudah_absen.length : 0);
  
  // Render tabel sedang dinas
  let htmlDinas = '';
  if (data.sedang_dinas && data.sedang_dinas.length > 0) {
    data.sedang_dinas.forEach(function(karyawan, idx) {
      htmlDinas += '<tr>';
      htmlDinas += '<td>' + (idx + 1) + '</td>';
      htmlDinas += '<td>' + karyawan.nik + '</td>';
      htmlDinas += '<td>' + karyawan.nama_karyawan + '</td>';
      htmlDinas += '<td>' + karyawan.nama_bagian + '</td>';
      htmlDinas += '<td>' + karyawan.nama_jabatan + '</td>';
      htmlDinas += '<td>';
      
      // Lokasi dinas
      if (karyawan.lokasi_dinas) {
        htmlDinas += '<strong><i class="fa fa-map-marker"></i> ' + karyawan.lokasi_dinas + '</strong><br>';
      }
      
      // Keterangan dinas
      if (karyawan.keterangan_dinas) {
        htmlDinas += '<i class="fa fa-info-circle"></i> ' + karyawan.keterangan_dinas + '<br>';
      }
      
      // Periode dinas
      if (karyawan.tanggal_mulai && karyawan.tanggal_selesai) {
        htmlDinas += '<small><i class="fa fa-calendar"></i> ' + formatTanggal(karyawan.tanggal_mulai);
        if (karyawan.tanggal_mulai != karyawan.tanggal_selesai) {
          htmlDinas += ' s/d ' + formatTanggal(karyawan.tanggal_selesai);
        }
        htmlDinas += '</small><br>';
      }
      
      // Nomor surat
      if (karyawan.nomor_surat) {
        htmlDinas += '<small><i class="fa fa-file-text"></i> ' + karyawan.nomor_surat + '</small>';
      }
      
      htmlDinas += '</td>';
      htmlDinas += '</tr>';
    });
  } else {
    htmlDinas = '<tr><td colspan="6" class="text-center">Tidak ada karyawan yang sedang dinas</td></tr>';
  }
  $('#tblSedangDinas tbody').html(htmlDinas);
  $('#countSedangDinas').text(data.sedang_dinas ? data.sedang_dinas.length : 0);
}

function updateInfo(summary) {
  $('#totalSudahAbsen').text(summary.sudah_absen);
  $('#totalBelumAbsen').text(summary.belum_absen);
  $('#totalSedangDinas').text(summary.sedang_dinas || 0);
  $('#totalKaryawan').text(summary.total_karyawan);
  $('#totalHadir').text(summary.sudah_absen + (summary.sedang_dinas || 0));
  $('#persenKehadiran').text(summary.persen_kehadiran + '%');
}

function konfirmasiAbsen(recid, nik, nama, bagian) {
  $('#recid_karyawan').val(recid);
  $('#tanggal_absen').val($('#filterTanggal').val());
  $('#nik_tampil').val(nik);
  $('#nama_tampil').val(nama);
  $('#bagian_tampil').val(bagian);
  $('#tanggal_tampil').val(formatTanggal($('#filterTanggal').val()));
  
  // Set jam dari input di tabel
  let jamInput = $('#jam_' + recid).val();
  $('#jam_masuk').val(jamInput);
  
  $('#modalAbsenManual').modal('show');
}

// Form submit absen manual
$('#formAbsenManual').on('submit', function(e) {
  e.preventDefault();
  
  let formData = {
    recid_karyawan: $('#recid_karyawan').val(),
    tanggal: $('#tanggal_absen').val(),
    jam_masuk: $('#jam_masuk').val(),
    keterangan: $('#keterangan').val()
  };
  
  $('#btnSubmitAbsen').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
  
  $.ajax({
    url: '<?php echo base_url('rekap/submit_absen_manual')?>',
    method: 'POST',
    data: formData,
    dataType: 'json',
    success: function(res) {
      $('#btnSubmitAbsen').prop('disabled', false).html('<i class="fa fa-check"></i> Konfirmasi Absen');
      
      if (res.success) {
        alert('✅ ' + res.message);
        $('#modalAbsenManual').modal('hide');
        $('#formAbsenManual')[0].reset();
        loadDataAbsen(); // Reload data
      } else {
        alert('❌ Error: ' + res.message);
      }
    },
    error: function(xhr, status, error) {
      $('#btnSubmitAbsen').prop('disabled', false).html('<i class="fa fa-check"></i> Konfirmasi Absen');
      alert('Terjadi kesalahan: ' + error);
    }
  });
});

function formatTanggal(tanggal) {
  let date = new Date(tanggal);
  let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('id-ID', options);
}
</script>

  </div>
</div>
<!-- /page content -->

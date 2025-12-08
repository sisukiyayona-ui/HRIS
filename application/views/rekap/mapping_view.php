<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-link"></i> Rebuild Mapping Karyawan & Mesin Fingerprint</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <!-- Info Panel -->
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-info-circle"></i> Apa itu Mapping?</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="alert alert-info">
              <p><strong>Mapping</strong> adalah proses menghubungkan:</p>
              <ul>
                <li><strong>PIN/UserID</strong> di mesin fingerprint</li>
                <li><strong>recid_karyawan</strong> di database HRIS</li>
              </ul>
              <p><strong>Kapan perlu rebuild mapping?</strong></p>
              <ul>
                <li>✅ Setelah upload ulang data karyawan</li>
                <li>✅ Setelah update NIK atau Nama karyawan</li>
                <li>✅ Ada user baru di mesin fingerprint</li>
                <li>✅ Data absensi tidak muncul nama karyawan</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Mapping Saat Ini -->
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-dashboard"></i> Status Mapping Saat Ini</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <button id="btnCekStatus" class="btn btn-info">
              <i class="fa fa-refresh"></i> Cek Status Mapping
            </button>
            
            <div id="statusResult" style="margin-top: 20px;"></div>
          </div>
        </div>
      </div>
    </div>

    Upload CSV Mapping
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-upload"></i> Upload CSV untuk Bulk Mapping</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="alert alert-success">
              <i class="fa fa-info-circle"></i> 
              <strong>Upload CSV Mapping:</strong>
              <ul>
                <li><strong>Format CSV dari Mesin:</strong> <code>No.;AC No.;Name</code> (delimiter: <strong>semicolon ;</strong>)</li>
                <li><strong>Contoh:</strong> <code>6;E3-2000;AJI RAHMAT</code></li>
                <li>Kolom 1: <strong>No. (PIN)</strong> → Kolom 2: <strong>AC No. (NIK)</strong> → Kolom 3: <strong>Name</strong></li>
                <li>Sistem akan cocokkan berdasarkan <strong>PIN</strong> atau <strong>NIK</strong> atau <strong>Nama</strong></li>
                <li>Download template: <a href="<?php echo base_url('assets/Format_Mapping_Template.csv')?>" class="btn btn-xs btn-info"><i class="fa fa-download"></i> Template CSV</a></li>
              </ul>
              <div class="alert alert-warning" style="margin-top: 10px; margin-bottom: 0;">
                <strong>📝 Cara Export dari Mesin:</strong> Buka software mesin fingerprint → Menu "Data Mgt" → Export to CSV → Upload file CSV tersebut ke sini!
              </div>
            </div>

            <form id="formUploadCSV" enctype="multipart/form-data">
              <div class="form-group">
                <label for="csvFile">Pilih File CSV:</label>
                <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                <small class="text-muted">Format dari mesin: <strong>No.;AC No.;Name</strong> (semicolon delimiter)</small>
              </div>

              <div class="form-group">
                <label for="deviceSN">Device Serial Number (opsional):</label>
                <input type="text" class="form-control" id="deviceSN" name="device_sn" placeholder="Contoh: BJKW234560123">
                <small class="text-muted">Kosongkan jika mapping untuk semua mesin</small>
              </div>

              <button type="submit" id="btnUploadCSV" class="btn btn-success btn-lg">
                <i class="fa fa-upload"></i> Upload & Proses Mapping
              </button>
            </form>

            <!-- Progress Section CSV -->
            <div id="progressSectionCSV" style="display: none; margin-top: 30px;">
              <h4>Memproses CSV...</h4>
              <div class="progress" style="height: 25px;">
                <div class="progress-bar progress-bar-striped active" id="progressBarCSV" 
                     role="progressbar" style="width: 0%;">
                  0%
                </div>
              </div>
              <div id="progressLogCSV" style="background: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;"></div>
            </div>

            <!-- Result Section CSV -->
            <div id="resultSectionCSV" style="display: none; margin-top: 30px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rebuild Mapping -->
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-cogs"></i> Rebuild Mapping per Mesin</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="alert alert-info">
              <i class="fa fa-info-circle"></i> 
              <strong>Cara Kerja:</strong>
              <ul>
                <li>Pilih mesin fingerprint (1 mesin per proses)</li>
                <li>Ambil semua user dari mesin tersebut</li>
                <li>Cocokkan nama user dengan nama karyawan di database</li>
                <li>Buat mapping di table <code>hris.karyawan_pin_map</code></li>
              </ul>
              <p class="text-warning"><strong>Tips:</strong> Jika ada mesin yang error/timeout, skip dulu dan lanjutkan mesin lain. Bisa diulang kapan saja.</p>
            </div>

            <form id="formRebuild">
              <div class="form-group">
                <label for="mesinId">Pilih Mesin:</label>
                <select class="form-control" id="mesinId" name="mesin_id" required>
                  <option value="">-- Pilih Mesin --</option>
                  <option value="semua" style="background: #3498db; color: white; font-weight: bold;">🔄 Semua Mesin (1-9 Sekaligus)</option>
                  <option value="1">Mesin 1 (192.168.9.201)</option>
                  <option value="2">Mesin 2 (192.168.9.202)</option>
                  <option value="3">Mesin 3 (192.168.9.203)</option>
                  <option value="4">Mesin 4 (192.168.9.204)</option>
                  <option value="5">Mesin 5 (192.168.9.205)</option>
                  <option value="6">Mesin 6 (192.168.9.206)</option>
                  <option value="7">Mesin 7 (192.168.9.207)</option>
                  <option value="8">Mesin 8 (192.168.9.208)</option>
                  <option value="9">Mesin 9 (192.168.9.209)</option>
                </select>
              </div>

              <button type="submit" id="btnRebuild" class="btn btn-primary btn-lg">
                <i class="fa fa-refresh"></i> Mulai Rebuild Mapping
              </button>
            </form>

            <!-- Progress Section -->
            <div id="progressSection" style="display: none; margin-top: 30px;">
              <h4>Memproses...</h4>
              <div class="progress" style="height: 25px;">
                <div class="progress-bar progress-bar-striped active" id="progressBar" 
                     role="progressbar" style="width: 0%;">
                  0%
                </div>
              </div>
              <div id="progressLog" style="background: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;"></div>
            </div>

            <!-- Result Section -->
            <div id="resultSection" style="display: none; margin-top: 30px;"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- /page content -->

<script>
$(document).ready(function() {
  
  // Force expand sidebar menu "Absen Finger"
  $('.nav.side-menu').find('li:has(a:contains("Absen Finger"))').addClass('active');
  $('.nav.side-menu').find('li:has(a:contains("Absen Finger"))').find('.child_menu').show();
  $('.nav.side-menu').find('li:has(a[href="<?php echo base_url('rekap/mapping_ui')?>"])').addClass('current-page');
  
  // Cek Status Mapping
  $('#btnCekStatus').click(function() {
    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
    
    $.ajax({
      url: '<?php echo base_url('rekap/status_mapping')?>',
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          let html = `
            <div class="row">
              <div class="col-md-3">
                <div class="well text-center">
                  <h2 style="margin: 0; color: #3498db;">${res.summary.total_karyawan_aktif}</h2>
                  <p>Total Karyawan Aktif</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="well text-center">
                  <h2 style="margin: 0; color: #2ecc71;">${res.summary.total_mapped}</h2>
                  <p>Sudah Dimapping</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="well text-center">
                  <h2 style="margin: 0; color: #e74c3c;">${res.summary.total_unmapped}</h2>
                  <p>Belum Dimapping</p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="well text-center">
                  <h2 style="margin: 0; color: #f39c12;">${res.summary.persentase_mapped}</h2>
                  <p>Persentase Mapped</p>
                </div>
              </div>
            </div>
          `;
          
          if (res.karyawan_unmapped && res.karyawan_unmapped.length > 0) {
            html += `
              <div class="alert alert-warning">
                <h4><i class="fa fa-exclamation-triangle"></i> Karyawan Belum Dimapping (${res.karyawan_unmapped.length} orang)</h4>
                <div style="max-height: 200px; overflow-y: auto;">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                      </tr>
                    </thead>
                    <tbody>
            `;
            
            res.karyawan_unmapped.forEach((k, index) => {
              html += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${k.nik}</td>
                  <td>${k.nama_karyawan}</td>
                </tr>
              `;
            });
            
            html += `
                    </tbody>
                  </table>
                </div>
                <p><strong>Solusi:</strong> Rebuild mapping untuk mencocokkan karyawan dengan user di mesin fingerprint.</p>
              </div>
            `;
          } else {
            html += `
              <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> <strong>Sempurna!</strong> Semua karyawan aktif sudah dimapping.
              </div>
            `;
          }
          
          $('#statusResult').html(html);
        } else {
          $('#statusResult').html(`
            <div class="alert alert-danger">
              <i class="fa fa-times-circle"></i> ${res.message}
            </div>
          `);
        }
      },
      error: function() {
        $('#statusResult').html(`
          <div class="alert alert-danger">
            <i class="fa fa-times-circle"></i> Gagal mengambil status mapping
          </div>
        `);
      },
      complete: function() {
        $('#btnCekStatus').prop('disabled', false).html('<i class="fa fa-refresh"></i> Cek Status Mapping');
      }
    });
  });
  
  // Rebuild Mapping
  $('#formRebuild').submit(function(e) {
    e.preventDefault();
    
    let mesinId = $('#mesinId').val();
    if (!mesinId) {
      alert('Pilih mesin terlebih dahulu!');
      return;
    }
    
    // Check if "semua" is selected
    if (mesinId === 'semua') {
      if (!confirm('Yakin ingin rebuild mapping dari SEMUA MESIN (1-9)? Proses ini akan memakan waktu beberapa menit.')) {
        return;
      }
      rebuildSemuaMesin();
      return;
    }
    
    if (!confirm('Yakin ingin rebuild mapping dari Mesin ' + mesinId + '?')) {
      return;
    }
    
    $('#btnRebuild').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
    $('#progressSection').show();
    $('#resultSection').hide();
    $('#progressLog').html('');
    $('#progressBar').css('width', '0%').text('0%');
    
    addLog('Memulai rebuild mapping dari Mesin ' + mesinId + '...', 'info');
    updateProgress(10);
    
    $.ajax({
      url: '<?php echo base_url('rekap/rebuild_mapping')?>',
      method: 'POST',
      data: { mesin_id: mesinId },
      dataType: 'json',
      timeout: 120000, // 2 menit
      success: function(res) {
        updateProgress(100);
        
        if (res.success) {
          addLog('✓ ' + res.message, 'success');
          addLog('Device: ' + res.mesin.nama + ' (' + res.mesin.ip + ')', 'info');
          addLog('Serial Number: ' + res.mesin.device_sn, 'info');
          addLog('─────────────────────────────────', 'info');
          addLog('Total user di mesin: ' + res.summary.total_users, 'info');
          addLog('Berhasil dimapping: ' + res.summary.total_mapped, 'success');
          addLog('  • Mapped by NIK: ' + res.summary.mapped_by_nik, 'success');
          addLog('  • Mapped by Nama: ' + res.summary.mapped_by_nama, 'success');
          addLog('Duplikat (sudah ada): ' + res.summary.total_duplicate, 'warning');
          addLog('Tidak ditemukan: ' + res.summary.total_unmapped, 'warning');
          addLog('Persentase mapped: ' + res.summary.persentase_mapped, 'info');
          
          // Show result
          let resultHtml = `
            <div class="alert alert-success">
              <h4><i class="fa fa-check-circle"></i> ${res.message}</h4>
              <p style="margin: 5px 0 0 0;">
                <i class="fa fa-info-circle"></i> 
                Matching: <strong>${res.summary.mapped_by_nik} by NIK</strong>, 
                <strong>${res.summary.mapped_by_nama} by Nama</strong>
              </p>
            </div>
            
            <div class="row">
              <div class="col-md-2">
                <div class="well text-center" style="background: #3498db; color: white;">
                  <h2>${res.summary.total_users}</h2>
                  <p>Total User</p>
                </div>
              </div>
              <div class="col-md-2">
                <div class="well text-center" style="background: #2ecc71; color: white;">
                  <h2>${res.summary.total_mapped}</h2>
                  <p>Berhasil</p>
                </div>
              </div>
              <div class="col-md-2">
                <div class="well text-center" style="background: #27ae60; color: white;">
                  <h2>${res.summary.mapped_by_nik}</h2>
                  <p>By NIK</p>
                </div>
              </div>
              <div class="col-md-2">
                <div class="well text-center" style="background: #16a085; color: white;">
                  <h2>${res.summary.mapped_by_nama}</h2>
                  <p>By Nama</p>
                </div>
              </div>
              <div class="col-md-2">
                <div class="well text-center" style="background: #e74c3c; color: white;">
                  <h2>${res.summary.total_unmapped}</h2>
                  <p>Gagal</p>
                </div>
              </div>
              <div class="col-md-2">
                <div class="well text-center" style="background: #f39c12; color: white;">
                  <h2>${res.summary.persentase_mapped}</h2>
                  <p>Persentase</p>
                </div>
              </div>
            </div>
          `;
          
          if (res.unmapped_users && res.unmapped_users.length > 0) {
            // Categorize unmapped users
            let tidakAda = [];
            let nonAktif = [];
            
            res.unmapped_users.forEach(user => {
              if (user.kategori === 'non_aktif') {
                nonAktif.push(user);
              } else {
                tidakAda.push(user);
              }
            });
            
            resultHtml += `
              <div class="panel panel-warning">
                <div class="panel-heading">
                  <h4>User di Mesin yang Tidak Ditemukan (${res.unmapped_users.length})</h4>
                </div>
                <div class="panel-body" style="max-height: 500px; overflow-y: auto;">
            `;
            
            // Display non-active users
            if (nonAktif.length > 0) {
              resultHtml += `
                <h5><strong>1. Karyawan Non-Aktif (${nonAktif.length} user)</strong></h5>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>PIN</th>
                      <th>Nama di Mesin</th>
                      <th>Card No (AC.No)</th>
                      <th>NIK Karyawan</th>
                      <th>Nama Karyawan</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
              `;
              
              nonAktif.forEach((user, index) => {
                resultHtml += `
                  <tr>
                    <td>${index + 1}</td>
                    <td>${user.pin}</td>
                    <td>${user.nama_mesin}</td>
                    <td>${user.cardno || '<span class="text-muted">-</span>'}</td>
                    <td>${user.karyawan_info ? user.karyawan_info.nik : '-'}</td>
                    <td>${user.karyawan_info ? user.karyawan_info.nama_karyawan : '-'}</td>
                    <td><span class="label label-warning">${user.karyawan_info ? user.karyawan_info.sts_aktif : '-'}</span></td>
                  </tr>
                `;
              });
              
              resultHtml += `
                  </tbody>
                </table>
              `;
            }
            
            // Display users not found in database
            if (tidakAda.length > 0) {
              resultHtml += `
                <h5><strong>2. User Tidak Ada di Database (${tidakAda.length} user)</strong></h5>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>PIN</th>
                      <th>Nama di Mesin</th>
                      <th>Card No (AC.No)</th>
                    </tr>
                  </thead>
                  <tbody>
              `;
              
              tidakAda.forEach((user, index) => {
                resultHtml += `
                  <tr>
                    <td>${index + 1}</td>
                    <td>${user.pin}</td>
                    <td>${user.nama_mesin}</td>
                    <td>${user.cardno || '<span class="text-muted">-</span>'}</td>
                  </tr>
                `;
              });
              
              resultHtml += `
                  </tbody>
                </table>
              `;
            }
            
            resultHtml += `
                  <div class="alert alert-info">
                    <strong>Catatan:</strong><br>
                    • <strong>Karyawan Non-Aktif:</strong> User ditemukan di database karyawan tetapi statusnya tidak aktif<br>
                    • <strong>User Tidak Ada di Database:</strong> User tidak ditemukan sama sekali di database karyawan
                    <br><br>
                    Sistem mencoba matching berdasarkan NIK (Card No) terlebih dahulu, jika tidak cocok baru matching berdasarkan Nama.
                  </div>
                </div>
              </div>
            `;
          }
          
          resultHtml += `
            <div class="form-group">
              <a href="<?php echo base_url('rekap/absensi')?>" class="btn btn-success">
                <i class="fa fa-eye"></i> Lihat Rekap Absensi
              </a>
              <button class="btn btn-info" onclick="location.reload()">
                <i class="fa fa-refresh"></i> Rebuild Mesin Lain
              </button>
            </div>
          `;
          
          $('#resultSection').html(resultHtml).show();
          
        } else {
          addLog('✗ ' + res.message, 'error');
          $('#resultSection').html(`
            <div class="alert alert-danger">
              <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> ${res.message}
            </div>
          `).show();
        }
      },
      error: function(xhr, status, error) {
        updateProgress(100);
        addLog('✗ Error: ' + error, 'error');
        $('#resultSection').html(`
          <div class="alert alert-danger">
            <i class="fa fa-times-circle"></i> Terjadi kesalahan saat rebuild mapping
          </div>
        `).show();
      },
      complete: function() {
        $('#btnRebuild').prop('disabled', false).html('<i class="fa fa-refresh"></i> Rebuild Mapping Sekarang');
        $('#progressBar').removeClass('active');
      }
    });
  });
  
});

function addLog(message, type = 'info') {
  let icon = '';
  let color = '#333';
  
  if (type === 'success') {
    icon = '✓';
    color = '#5cb85c';
  } else if (type === 'error') {
    icon = '✗';
    color = '#d9534f';
  } else if (type === 'warning') {
    icon = '⚠';
    color = '#f0ad4e';
  } else {
    icon = '→';
    color = '#5bc0de';
  }
  
  let timestamp = new Date().toLocaleTimeString();
  let logHtml = '<div style="margin-bottom: 5px; color: ' + color + ';">' +
                '[' + timestamp + '] <strong>' + icon + '</strong> ' + message +
                '</div>';
  
  $('#progressLog').append(logHtml);
  $('#progressLog').scrollTop($('#progressLog')[0].scrollHeight);
}

function updateProgress(percent) {
  $('#progressBar').css('width', percent + '%');
  $('#progressBar').attr('aria-valuenow', percent);
  $('#progressBar').text(percent + '%');
}

// Rebuild Semua Mesin (1-9)
function rebuildSemuaMesin() {
  $('#btnRebuild').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses 9 Mesin...');
  $('#progressSection').show();
  $('#resultSection').hide();
  $('#progressLog').html('');
  $('#progressBar').css('width', '0%').text('0%');
  
  addLog('========================================', 'info');
  addLog('Mulai rebuild mapping dari 9 mesin...', 'info');
  addLog('========================================', 'info');
  
  let machines = [1, 2, 3, 4, 5, 6, 7, 8, 9];
  let currentIndex = 0;
  let totalMapped = 0;
  let totalUnmapped = 0;
  let successCount = 0;
  let failCount = 0;
  let allResults = [];
  let allUnmappedUsers = []; // Store all unmapped users
  
  function prosesNext() {
    if (currentIndex >= machines.length) {
      selesaiSemuaMesin();
      return;
    }
    
    let mesinId = machines[currentIndex];
    let progressPercent = Math.round((currentIndex / machines.length) * 100);
    updateProgress(progressPercent);
    
    addLog('───────────────────────────────────', 'info');
    addLog('Memproses Mesin ' + mesinId + '...', 'info');
    
    $.ajax({
      url: '<?php echo base_url('rekap/rebuild_mapping')?>',
      method: 'POST',
      data: { mesin_id: mesinId },
      dataType: 'json',
      timeout: 120000,
      success: function(res) {
        if (res.success) {
          addLog('✓ Mesin ' + mesinId + ': ' + res.summary.total_mapped + ' mapped, ' + res.summary.total_unmapped + ' unmapped', 'success');
          totalMapped += res.summary.total_mapped;
          totalUnmapped += res.summary.total_unmapped;
          successCount++;
          allResults.push(res);
          
          // Collect unmapped users
          if (res.unmapped_users && res.unmapped_users.length > 0) {
            res.unmapped_users.forEach(user => {
              user.mesin = res.mesin.nama; // Add mesin info to each user
              allUnmappedUsers.push(user);
            });
          }
        } else {
          addLog('✗ Mesin ' + mesinId + ': ' + res.message, 'error');
          failCount++;
        }
        
        currentIndex++;
        setTimeout(prosesNext, 500);
      },
      error: function(xhr) {
        addLog('✗ Mesin ' + mesinId + ': Error koneksi', 'error');
        failCount++;
        currentIndex++;
        setTimeout(prosesNext, 500);
      }
    });
  }
  
  function selesaiSemuaMesin() {
    updateProgress(100);
    addLog('========================================', 'info');
    addLog('Proses selesai!', 'success');
    addLog('Berhasil: ' + successCount + ' mesin', 'success');
    addLog('Gagal: ' + failCount + ' mesin', failCount > 0 ? 'warning' : 'info');
    addLog('Total mapped: ' + totalMapped, 'success');
    addLog('Total unmapped: ' + totalUnmapped, 'warning');
    
    let resultHtml = '<div class="alert alert-success">';
    resultHtml += '<h4><i class="fa fa-check-circle"></i> Rebuild Mapping dari 9 Mesin Selesai!</h4>';
    resultHtml += '</div>';
    
    resultHtml += '<div class="row">';
    resultHtml += '<div class="col-md-3">';
    resultHtml += '<div class="well text-center" style="background: #3498db; color: white;">';
    resultHtml += '<h2>' + (successCount + failCount) + '</h2>';
    resultHtml += '<p>Total Mesin</p>';
    resultHtml += '</div>';
    resultHtml += '</div>';
    resultHtml += '<div class="col-md-3">';
    resultHtml += '<div class="well text-center" style="background: #2ecc71; color: white;">';
    resultHtml += '<h2>' + successCount + '</h2>';
    resultHtml += '<p>Mesin Berhasil</p>';
    resultHtml += '</div>';
    resultHtml += '</div>';
    resultHtml += '<div class="col-md-3">';
    resultHtml += '<div class="well text-center" style="background: #e74c3c; color: white;">';
    resultHtml += '<h2>' + failCount + '</h2>';
    resultHtml += '<p>Mesin Gagal</p>';
    resultHtml += '</div>';
    resultHtml += '</div>';
    resultHtml += '<div class="col-md-3">';
    resultHtml += '<div class="well text-center" style="background: #27ae60; color: white;">';
    resultHtml += '<h2>' + totalMapped + '</h2>';
    resultHtml += '<p>Total Mapped</p>';
    resultHtml += '</div>';
    resultHtml += '</div>';
    resultHtml += '</div>';
    
    // Detail per mesin
    if (allResults.length > 0) {
      resultHtml += '<div class="panel panel-info">';
      resultHtml += '<div class="panel-heading"><h4>Detail Hasil per Mesin</h4></div>';
      resultHtml += '<div class="panel-body">';
      resultHtml += '<table class="table table-striped">';
      resultHtml += '<thead><tr><th>Mesin</th><th>Total Users</th><th>Mapped</th><th>By PIN</th><th>By Nama</th><th>Unmapped</th><th>Status</th></tr></thead>';
      resultHtml += '<tbody>';
      
      allResults.forEach(function(res) {
        let statusColor = res.success ? 'success' : 'danger';
        let statusIcon = res.success ? '✓' : '✗';
        resultHtml += '<tr class="' + statusColor + '">';
        resultHtml += '<td>' + res.mesin.nama + '</td>';
        resultHtml += '<td>' + res.summary.total_users + '</td>';
        resultHtml += '<td><strong>' + res.summary.total_mapped + '</strong></td>';
        resultHtml += '<td>' + (res.summary.mapped_by_nik || 0) + '</td>';
        resultHtml += '<td>' + res.summary.mapped_by_nama + '</td>';
        resultHtml += '<td>' + res.summary.total_unmapped + '</td>';
        resultHtml += '<td><span class="label label-' + statusColor + '">' + statusIcon + '</span></td>';
        resultHtml += '</tr>';
      });
      
      resultHtml += '</tbody></table>';
      resultHtml += '</div></div>';
    }
    
    // Detail users yang tidak ditemukan
    if (allUnmappedUsers.length > 0) {
      // Categorize unmapped users
      let tidakAda = [];
      let nonAktif = [];
      
      allUnmappedUsers.forEach(user => {
        if (user.kategori === 'non_aktif') {
          nonAktif.push(user);
        } else {
          tidakAda.push(user);
        }
      });
      
      resultHtml += '<div class="panel panel-warning">';
      resultHtml += '<div class="panel-heading"><h4>User di Mesin yang Tidak Ditemukan (' + allUnmappedUsers.length + ')</h4></div>';
      resultHtml += '<div class="panel-body" style="max-height: 500px; overflow-y: auto;">';
      
      // Display non-active users
      if (nonAktif.length > 0) {
        resultHtml += '<h5><strong>1. Karyawan Non-Aktif (' + nonAktif.length + ' user)</strong></h5>';
        resultHtml += '<table class="table table-striped">';
        resultHtml += '<thead><tr><th>No</th><th>Mesin</th><th>PIN</th><th>Nama di Mesin</th><th>Card No (AC.No)</th><th>NIK Karyawan</th><th>Nama Karyawan</th><th>Status</th></tr></thead>';
        resultHtml += '<tbody>';
        
        nonAktif.forEach(function(user, index) {
          resultHtml += '<tr>';
          resultHtml += '<td>' + (index + 1) + '</td>';
          resultHtml += '<td>' + user.mesin + '</td>';
          resultHtml += '<td>' + user.pin + '</td>';
          resultHtml += '<td>' + user.nama_mesin + '</td>';
          resultHtml += '<td>' + (user.cardno || '-') + '</td>';
          resultHtml += '<td>' + (user.karyawan_info ? user.karyawan_info.nik : '-') + '</td>';
          resultHtml += '<td>' + (user.karyawan_info ? user.karyawan_info.nama_karyawan : '-') + '</td>';
          resultHtml += '<td><span class="label label-warning">' + (user.karyawan_info ? user.karyawan_info.sts_aktif : '-') + '</span></td>';
          resultHtml += '</tr>';
        });
        
        resultHtml += '</tbody></table>';
      }
      
      // Display users not found in database
      if (tidakAda.length > 0) {
        resultHtml += '<h5><strong>2. User Tidak Ada di Database (' + tidakAda.length + ' user)</strong></h5>';
        resultHtml += '<table class="table table-striped">';
        resultHtml += '<thead><tr><th>No</th><th>Mesin</th><th>PIN</th><th>Nama di Mesin</th><th>Card No (AC.No)</th></tr></thead>';
        resultHtml += '<tbody>';
        
        tidakAda.forEach(function(user, index) {
          resultHtml += '<tr>';
          resultHtml += '<td>' + (index + 1) + '</td>';
          resultHtml += '<td>' + user.mesin + '</td>';
          resultHtml += '<td>' + user.pin + '</td>';
          resultHtml += '<td>' + user.nama_mesin + '</td>';
          resultHtml += '<td>' + (user.cardno || '-') + '</td>';
          resultHtml += '</tr>';
        });
        
        resultHtml += '</tbody></table>';
      }
      
      resultHtml += '<div class="alert alert-info">';
      resultHtml += '<strong>Catatan:</strong><br>';
      resultHtml += '• <strong>Karyawan Non-Aktif:</strong> User ditemukan di database karyawan tetapi statusnya tidak aktif<br>';
      resultHtml += '• <strong>User Tidak Ada di Database:</strong> User tidak ditemukan sama sekali di database karyawan';
      resultHtml += '<br><br>';
      resultHtml += 'Sistem mencoba matching berdasarkan NIK (Card No) terlebih dahulu, jika tidak cocok baru matching berdasarkan Nama.';
      resultHtml += '</div>';
      resultHtml += '</div></div>';
    }
    
    resultHtml += '<div class="form-group">';
    resultHtml += '<a href="<?php echo base_url('rekap/absensi')?>" class="btn btn-success">';
    resultHtml += '<i class="fa fa-eye"></i> Lihat Rekap Absensi';
    resultHtml += '</a>';
    resultHtml += '<button class="btn btn-info" onclick="location.reload()">';
    resultHtml += '<i class="fa fa-refresh"></i> Rebuild Lagi';
    resultHtml += '</button>';
    resultHtml += '</div>';
    
    $('#resultSection').html(resultHtml).show();
    $('#btnRebuild').prop('disabled', false).html('<i class="fa fa-refresh"></i> Mulai Rebuild Mapping');
  }
  
  prosesNext();
}

function addLogCSV(message, type = 'info') {
  let icon = '';
  let color = '#333';
  
  if (type === 'success') {
    icon = '✓';
    color = '#5cb85c';
  } else if (type === 'error') {
    icon = '✗';
    color = '#d9534f';
  } else if (type === 'warning') {
    icon = '⚠';
    color = '#f0ad4e';
  } else {
    icon = '→';
    color = '#5bc0de';
  }
  
  let timestamp = new Date().toLocaleTimeString();
  let logHtml = '<div style="margin-bottom: 5px; color: ' + color + ';">' +
                '[' + timestamp + '] <strong>' + icon + '</strong> ' + message +
                '</div>';
  
  $('#progressLogCSV').append(logHtml);
  $('#progressLogCSV').scrollTop($('#progressLogCSV')[0].scrollHeight);
}

function updateProgressCSV(percent) {
  $('#progressBarCSV').css('width', percent + '%');
  $('#progressBarCSV').attr('aria-valuenow', percent);
  $('#progressBarCSV').text(percent + '%');
}

// Handle Upload CSV
$('#formUploadCSV').submit(function(e) {
  e.preventDefault();
  
  let fileInput = $('#csvFile')[0];
  if (!fileInput.files.length) {
    alert('Pilih file CSV terlebih dahulu!');
    return;
  }
  
  let formData = new FormData();
  formData.append('csv_file', fileInput.files[0]);
  formData.append('device_sn', $('#deviceSN').val());
  
  $('#btnUploadCSV').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
  $('#progressSectionCSV').show();
  $('#resultSectionCSV').hide().html('');
  $('#progressLogCSV').html('');
  updateProgressCSV(10);
  
  addLogCSV('Mulai upload file CSV...', 'info');
  
  $.ajax({
    url: '<?php echo base_url('rekap/upload_mapping_csv')?>',
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    timeout: 120000,
    xhr: function() {
      let xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          let percentComplete = Math.round((evt.loaded / evt.total) * 50);
          updateProgressCSV(percentComplete);
        }
      }, false);
      return xhr;
    },
    success: function(res) {
      updateProgressCSV(100);
      
      if (res.success) {
        addLogCSV('✓ ' + res.message, 'success');
        addLogCSV('─────────────────────────────────', 'info');
        addLogCSV('Total baris CSV: ' + res.summary.total_rows, 'info');
        addLogCSV('Berhasil dimapping: ' + res.summary.total_mapped, 'success');
        addLogCSV('  • Mapped by PIN: ' + res.summary.mapped_by_pin, 'success');
        addLogCSV('  • Mapped by NIK: ' + res.summary.mapped_by_nik, 'success');
        addLogCSV('  • Mapped by Nama: ' + res.summary.mapped_by_nama, 'success');
        addLogCSV('Duplikat (sudah ada): ' + res.summary.total_duplicate, 'warning');
        addLogCSV('Tidak ditemukan: ' + res.summary.total_unmapped, 'warning');
        
        let resultHtml = `
          <div class="alert alert-success">
            <h4><i class="fa fa-check-circle"></i> ${res.message}</h4>
            <p style="margin: 5px 0 0 0;">
              <i class="fa fa-info-circle"></i> 
              Matching: <strong>${res.summary.mapped_by_pin} by PIN</strong>, 
              <strong>${res.summary.mapped_by_nik} by NIK</strong>, 
              <strong>${res.summary.mapped_by_nama} by Nama</strong>
            </p>
          </div>
          
          <div class="row">
            <div class="col-md-2">
              <div class="well text-center" style="background: #3498db; color: white;">
                <h2>${res.summary.total_rows}</h2>
                <p>Total Baris</p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="well text-center" style="background: #2ecc71; color: white;">
                <h2>${res.summary.total_mapped}</h2>
                <p>Berhasil</p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="well text-center" style="background: #27ae60; color: white;">
                <h2>${res.summary.mapped_by_pin}</h2>
                <p>By PIN</p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="well text-center" style="background: #16a085; color: white;">
                <h2>${res.summary.mapped_by_nik}</h2>
                <p>By NIK</p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="well text-center" style="background: #1abc9c; color: white;">
                <h2>${res.summary.mapped_by_nama}</h2>
                <p>By Nama</p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="well text-center" style="background: #e74c3c; color: white;">
                <h2>${res.summary.total_unmapped}</h2>
                <p>Gagal</p>
              </div>
            </div>
          </div>
        `;
        
        if (res.unmapped_users && res.unmapped_users.length > 0) {
          resultHtml += `
            <div class="panel panel-warning">
              <div class="panel-heading">
                <h4>Data CSV yang Tidak Ditemukan (${res.unmapped_users.length})</h4>
              </div>
              <div class="panel-body" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>PIN</th>
                      <th>Nama di CSV</th>
                      <th>AC No (NIK)</th>
                    </tr>
                  </thead>
                  <tbody>
          `;
          
          res.unmapped_users.forEach((user, index) => {
            resultHtml += `
              <tr>
                <td>${index + 1}</td>
                <td>${user.pin}</td>
                <td>${user.nama}</td>
                <td>${user.ac_no || '<span class="text-muted">-</span>'}</td>
              </tr>
            `;
          });
          
          resultHtml += `
                  </tbody>
                </table>
                <div class="alert alert-info">
                  <strong>Catatan:</strong> Data ini tidak ditemukan di database karyawan. 
                  Pastikan NIK, PIN, atau Nama sudah sesuai dengan database.
                </div>
              </div>
            </div>
          `;
        }
        
        resultHtml += `
          <div class="form-group">
            <a href="<?php echo base_url('rekap/absensi')?>" class="btn btn-success">
              <i class="fa fa-eye"></i> Lihat Rekap Absensi
            </a>
            <button class="btn btn-info" onclick="location.reload()">
              <i class="fa fa-refresh"></i> Upload Lagi
            </button>
          </div>
        `;
        
        $('#resultSectionCSV').html(resultHtml).show();
        
      } else {
        addLogCSV('✗ ' + res.message, 'error');
        $('#resultSectionCSV').html(`
          <div class="alert alert-danger">
            <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> ${res.message}
          </div>
        `).show();
      }
      
      $('#btnUploadCSV').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Proses Mapping');
    },
    error: function(xhr) {
      updateProgressCSV(100);
      addLogCSV('✗ Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Connection failed'), 'error');
      $('#resultSectionCSV').html(`
        <div class="alert alert-danger">
          <i class="fa fa-times-circle"></i> <strong>Error!</strong> Gagal memproses CSV. Silakan coba lagi.
        </div>
      `).show();
      $('#btnUploadCSV').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Proses Mapping');
    }
  });
});
</script>

  </div>
</div>
<!-- /page content -->

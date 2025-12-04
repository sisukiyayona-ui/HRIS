<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-download"></i> Tarik Data Absensi dari Mesin Fingerprint</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Pilih Mesin</h2>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <div class="alert alert-info">
              <i class="fa fa-info-circle"></i> 
              <strong>Petunjuk:</strong> Pilih mesin fingerprint yang ingin ditarik datanya, atau pilih "Semua Mesin" untuk tarik data dari 9 mesin sekaligus.
            </div>

            <form id="form_tarik">
              <div class="form-group">
                <label for="mesin_id">Pilih Mesin:</label>
                <select class="form-control" id="mesin_id" name="mesin_id" required>
                  <option value="">-- Pilih Mesin --</option>
                  <option value="semua">🔄 Semua Mesin (9 Mesin)</option>
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

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn_tarik">
                  <i class="fa fa-download"></i> Mulai Tarik Data
                </button>
              </div>
            </form>

            <!-- Progress Section (Hidden by default) -->
            <div id="progress_section" style="display: none; margin-top: 30px;">
              <h4 id="progress_title">Memproses...</h4>
              
              <div class="progress" style="height: 30px; margin-bottom: 20px;">
                <div class="progress-bar progress-bar-striped active" id="progress_bar" 
                     role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" 
                     style="width: 0%; font-size: 16px; line-height: 30px;">
                  0%
                </div>
              </div>

              <div id="progress_log" style="background: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                <!-- Log akan muncul disini -->
              </div>
            </div>

            <!-- Result Section (Hidden by default) -->
            <div id="result_section" style="display: none; margin-top: 30px;">
              <div class="alert" id="result_alert">
                <!-- Result akan muncul disini -->
              </div>
              
              <div id="result_detail">
                <!-- Detail result -->
              </div>

              <button type="button" class="btn btn-default" onclick="location.reload()">
                <i class="fa fa-refresh"></i> Tarik Data Lagi
              </button>
              <a href="<?php echo base_url('rekap/absensi')?>" class="btn btn-success">
                <i class="fa fa-eye"></i> Lihat Rekap Absensi
              </a>
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
  $('#form_tarik').on('submit', function(e) {
    e.preventDefault();
    
    var mesin_id = $('#mesin_id').val();
    
    if (!mesin_id) {
      alert('Silakan pilih mesin terlebih dahulu!');
      return;
    }
    
    // Hide form, show progress
    $('#form_tarik').hide();
    $('#progress_section').show();
    $('#result_section').hide();
    $('#progress_log').html('');
    
    if (mesin_id === 'semua') {
      // Tarik semua mesin
      tarikSemuaMesin();
    } else {
      // Tarik satu mesin
      tarikSatuMesin(mesin_id);
    }
  });
});

function addLog(message, type = 'info') {
  var icon = '';
  var color = '#333';
  
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
  
  var timestamp = new Date().toLocaleTimeString();
  var logHtml = '<div style="margin-bottom: 5px; color: ' + color + ';">' +
                '[' + timestamp + '] <strong>' + icon + '</strong> ' + message +
                '</div>';
  
  $('#progress_log').append(logHtml);
  $('#progress_log').scrollTop($('#progress_log')[0].scrollHeight);
}

function updateProgress(current, total) {
  var percent = Math.round((current / total) * 100);
  $('#progress_bar').css('width', percent + '%');
  $('#progress_bar').attr('aria-valuenow', percent);
  $('#progress_bar').text(percent + '%');
}

function tarikSatuMesin(mesin_id) {
  $('#progress_title').text('Menarik Data dari Mesin ' + mesin_id + '...');
  addLog('Memulai proses tarik data dari Mesin ' + mesin_id + '...', 'info');
  updateProgress(0, 100);
  
  $.ajax({
    url: '<?php echo base_url("rekap/tarik")?>?mesin=' + mesin_id,
    type: 'GET',
    dataType: 'json',
    timeout: 120000, // 2 menit timeout
    success: function(response) {
      updateProgress(100, 100);
      
      if (response.success) {
        addLog('✓ Berhasil terhubung ke ' + response.info.nama_mesin, 'success');
        addLog('IP: ' + response.info.ip + ' | Port: ' + response.info.port, 'info');
        addLog('Device: ' + response.info.device_name, 'info');
        addLog('Serial: ' + response.info.serial_number, 'info');
        addLog('Total data ditarik: ' + response.total + ' record', 'info');
        addLog('Data baru disimpan: ' + response.saved + ' record', 'success');
        addLog('Data duplikat dilewati: ' + response.duplicate + ' record', 'warning');
        
        showResult(true, 'Data berhasil ditarik dari ' + response.info.nama_mesin, response);
      } else {
        addLog('✗ ' + response.message, 'error');
        showResult(false, response.message, response);
      }
    },
    error: function(xhr, status, error) {
      updateProgress(100, 100);
      addLog('✗ Error: ' + error, 'error');
      showResult(false, 'Terjadi kesalahan saat menarik data', null);
    }
  });
}

function tarikSemuaMesin() {
  $('#progress_title').text('Menarik Data dari 9 Mesin...');
  addLog('Memulai proses tarik data dari 9 mesin secara berurutan...', 'info');
  
  var mesinIds = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
  var currentIndex = 0;
  var totalMesin = mesinIds.length;
  var results = [];
  
  function tarikNext() {
    if (currentIndex >= totalMesin) {
      // Selesai semua
      var successCount = results.filter(r => r.success).length;
      var failedCount = results.filter(r => !r.success).length;
      var totalSaved = results.reduce((sum, r) => sum + (r.saved || 0), 0);
      var totalData = results.reduce((sum, r) => sum + (r.total || 0), 0);
      
      addLog('─────────────────────────────────────', 'info');
      addLog('RINGKASAN:', 'info');
      addLog('Total Mesin Berhasil: ' + successCount + ' mesin', 'success');
      addLog('Total Mesin Gagal: ' + failedCount + ' mesin', 'error');
      addLog('Total Data Ditarik: ' + totalData + ' record', 'info');
      addLog('Total Data Disimpan: ' + totalSaved + ' record', 'success');
      
      var message = 'Selesai! ' + successCount + ' mesin berhasil, ' + failedCount + ' mesin gagal';
      showResult(failedCount === 0, message, {
        summary: {
          success: successCount,
          failed: failedCount,
          total_data: totalData,
          total_saved: totalSaved
        },
        detail: results
      });
      return;
    }
    
    var mesinId = mesinIds[currentIndex];
    updateProgress(currentIndex, totalMesin);
    
    addLog('─────────────────────────────────────', 'info');
    addLog('Memproses Mesin ' + mesinId + '...', 'info');
    
    $.ajax({
      url: '<?php echo base_url("rekap/tarik")?>?mesin=' + mesinId,
      type: 'GET',
      dataType: 'json',
      timeout: 120000,
      success: function(response) {
        if (response.success) {
          addLog('✓ ' + response.info.nama_mesin + ' berhasil!', 'success');
          addLog('  Data ditarik: ' + response.total + ' | Disimpan: ' + response.saved, 'info');
          results.push({
            success: true,
            mesin: response.info.nama_mesin,
            total: response.total,
            saved: response.saved,
            duplicate: response.duplicate
          });
        } else {
          addLog('✗ Mesin ' + mesinId + ' gagal: ' + response.message, 'error');
          results.push({
            success: false,
            mesin: 'Mesin ' + mesinId,
            message: response.message
          });
        }
        
        currentIndex++;
        tarikNext();
      },
      error: function(xhr, status, error) {
        addLog('✗ Mesin ' + mesinId + ' error: ' + error, 'error');
        results.push({
          success: false,
          mesin: 'Mesin ' + mesinId,
          message: error
        });
        
        currentIndex++;
        tarikNext();
      }
    });
  }
  
  tarikNext();
}

function showResult(success, message, data) {
  updateProgress(100, 100);
  $('#progress_bar').removeClass('active');
  
  setTimeout(function() {
    $('#result_section').show();
    
    if (success) {
      $('#result_alert').removeClass('alert-danger').addClass('alert-success');
      $('#result_alert').html('<i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> ' + message);
    } else {
      $('#result_alert').removeClass('alert-success').addClass('alert-danger');
      $('#result_alert').html('<i class="fa fa-times-circle"></i> <strong>Gagal!</strong> ' + message);
    }
    
    if (data && data.summary) {
      var detailHtml = '<div class="row">' +
        '<div class="col-md-3"><div class="well text-center"><h3>' + data.summary.success + '</h3>Berhasil</div></div>' +
        '<div class="col-md-3"><div class="well text-center"><h3>' + data.summary.failed + '</h3>Gagal</div></div>' +
        '<div class="col-md-3"><div class="well text-center"><h3>' + data.summary.total_data + '</h3>Total Data</div></div>' +
        '<div class="col-md-3"><div class="well text-center"><h3>' + data.summary.total_saved + '</h3>Data Disimpan</div></div>' +
        '</div>';
      $('#result_detail').html(detailHtml);
    }
  }, 500);
}
</script>

  </div>
</div>
<!-- /page content -->

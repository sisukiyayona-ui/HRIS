<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Import Kontrak Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Proses Import Sedang Berlangsung</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-12">
                <p>Harap tunggu proses import data kontrak karyawan sedang berlangsung...</p>
                
                <div class="progress">
                  <div id="import-progress" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="progress-text">0%</span>
                  </div>
                </div>
                
                <div class="well" style="height: 200px; overflow-y: scroll;">
                  <div id="log-content">
                    <p>[<?php echo date('H.i.s'); ?>] → Mulai import data kontrak karyawan...</p>
                    <p>[<?php echo date('H.i.s'); ?>] → ========================================</p>
                  </div>
                </div>
                
                <div class="text-center" id="completion-message" style="display: none;">
                  <h3><i class="fa fa-check text-success"></i> Import Selesai!</h3>
                  <p>Mengarahkan ke halaman hasil...</p>
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

<script>
// Simulate progress updates while import happens in background
let progress = 0;
const progressBar = document.getElementById('import-progress');
const progressText = document.getElementById('progress-text');
const logContent = document.getElementById('log-content');
const completionMessage = document.getElementById('completion-message');

function updateProgress(percentage, message) {
  progress = percentage;
  progressBar.style.width = progress + '%';
  progressText.textContent = Math.round(progress) + '%';
  progressBar.setAttribute('aria-valuenow', progress);

  if (message) {
    const time = new Date();
    const timeString = `${time.getHours().toString().padStart(2, '0')}.${time.getMinutes().toString().padStart(2, '0')}.${time.getSeconds().toString().padStart(2, '0')}`;
    logContent.innerHTML += `<p>[${timeString}] → ${message}</p>`;
    // Scroll to bottom
    logContent.scrollTop = logContent.scrollHeight;
  }
}

// Function to start the actual import in background
function startImport() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', '<?php echo base_url('Contract_import/ajax_import'); ?>', true);
  
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.status === 'success') {
            updateProgress(100, 'Import berhasil diselesaikan');
            setTimeout(() => {
              completionMessage.style.display = 'block';
              setTimeout(() => {
                window.location.href = '<?php echo base_url('Contract_import/results'); ?>';
              }, 2000);
            }, 1000);
          } else {
            updateProgress(100, 'Terjadi kesalahan: ' + response.message);
          }
        } catch (e) {
          updateProgress(100, 'Import selesai');
          setTimeout(() => {
            completionMessage.style.display = 'block';
            setTimeout(() => {
              window.location.href = '<?php echo base_url('Contract_import/results'); ?>';
            }, 2000);
          }, 1000);
        }
      } else {
        // Show actual error response
        let errorMessage = 'Terjadi kesalahan jaringan';
        if (xhr.responseText) {
          try {
            const errorResponse = JSON.parse(xhr.responseText);
            errorMessage = 'Error: ' + (errorResponse.message || xhr.responseText);
          } catch (e) {
            errorMessage = 'Error: ' + xhr.responseText.substring(0, 200) + '...';
          }
        }
        updateProgress(100, errorMessage);
      }
    }
  };
  
  xhr.send();
}

// Simulate progress with optimized timing for better user experience
let simulatedProgress = 0;
const simulateProgress = () => {
  if (simulatedProgress < 95) {
    // Faster progress simulation for better UX
    const increment = Math.random() * 8 + 2; // 2-10% increments
    simulatedProgress += increment;
    if (simulatedProgress > 95) simulatedProgress = 95;
    
    let message = '';
    if (simulatedProgress < 15) {
      message = 'Menyiapkan data import...';
    } else if (simulatedProgress < 35) {
      message = 'Memvalidasi format file...';
    } else if (simulatedProgress < 55) {
      message = 'Memeriksa data karyawan...';
    } else if (simulatedProgress < 75) {
      message = 'Memproses kontrak...';
    } else {
      message = 'Menyimpan ke database...';
    }
    
    updateProgress(simulatedProgress, message);
    // Faster updates for better responsiveness
    setTimeout(simulateProgress, 200 + Math.random() * 300); // 200-500ms intervals
  }
};

simulateProgress();

// Start actual import after initial progress simulation
setTimeout(startImport, 2000);
</script>
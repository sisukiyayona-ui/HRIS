<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Employee Import Progress</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Importing Employee Data</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-12">
                <div class="progress">
                  <div id="import-progress" class="progress-bar progress-bar-striped active" role="progressbar" 
                       aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="progress-text">0%</span>
                  </div>
                </div>
                
                <div id="import-log" class="well" style="height: 300px; overflow-y: auto; margin-top: 20px;">
                  <h4>Import Log:</h4>
                  <div id="log-content">
                    <p>[<?php echo date('H.i.s'); ?>] → ========================================</p>
                    <p>[<?php echo date('H.i.s'); ?>] → Mulai import data karyawan...</p>
                    <p>[<?php echo date('H.i.s'); ?>] → ========================================</p>
                  </div>
                </div>
                
                <div class="text-center" id="completion-message" style="display: none;">
                  <h3><i class="fa fa-check text-success"></i> Import Completed!</h3>
                  <p>Redirecting to results page...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
  xhr.open('GET', '<?php echo base_url('Employee_import/do_import_ajax'); ?>', true);
  
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.status === 'success') {
            updateProgress(100, response.message);
            completionMessage.style.display = 'block';
            
            // Redirect to results page after a delay
            setTimeout(() => {
              window.location.href = '<?php echo base_url('Employee_import/do_import'); ?>';
            }, 2000);
          } else {
            updateProgress(0, 'Error: ' + response.message);
          }
        } catch (e) {
          updateProgress(0, 'Error parsing response: ' + e.message);
        }
      } else {
        updateProgress(0, 'HTTP Error: ' + xhr.status);
      }
    }
  };
  
  xhr.onerror = function() {
    updateProgress(0, 'Network error occurred');
  };
  
  xhr.send();
}

// Simulate the import process with progress updates
function simulateImport() {
  updateProgress(5, "Memulai proses import...");
  
  setTimeout(() => {
    updateProgress(15, "Membaca file Excel...");
  }, 800);
  
  setTimeout(() => {
    updateProgress(30, "Memproses data karyawan...");
  }, 1600);
  
  setTimeout(() => {
    updateProgress(50, "Menyimpan data ke database...");
  }, 2400);
  
  setTimeout(() => {
    updateProgress(70, "Memproses kontrak karyawan...");
  }, 3200);
  
  setTimeout(() => {
    updateProgress(85, "Menyelesaikan proses...");
  }, 4000);
  
  // Start actual import in background at 90%
  setTimeout(() => {
    updateProgress(90, "Finalisasi import...");
    startImport();
  }, 4800);
}

// Start the simulation when page loads
window.onload = simulateImport;
</script>
<!-- /page content -->
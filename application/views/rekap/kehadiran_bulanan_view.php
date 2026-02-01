<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-calendar"></i> Kehadiran Karyawan Bulanan</h3>
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
            <form id="formFilter" class="form-horizontal">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label col-md-4">Bagian:</label>
                    <div class="col-md-8">
                      <select class="form-control" id="filterBagian" name="bagian">
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
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                      <input type="date" class="form-control" id="startDate" name="startDate" required>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                      <input type="date" class="form-control" id="endDate" name="endDate" required>
                    </div>
                  </div>
                </div>
                

                
                <div class="col-md-3">
                  <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa fa-search"></i> Tampilkan Data
                  </button>
                  <button type="button" id="btnExportExcel" class="btn btn-success btn-lg" style="display:none;">
                    <i class="fa fa-file-excel-o"></i> Export XLSX
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Info Panel -->
    <div class="row" id="infoPanel" style="display:none;">
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Karyawan</span>
            <span class="info-box-number" id="totalKaryawan">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-blue">
          <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Hari Kerja</span>
            <span class="info-box-number" id="hariKerja">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Rata-rata Kehadiran</span>
            <span class="info-box-number" id="avgKehadiran">0%</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="fa fa-times-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Absen</span>
            <span class="info-box-number" id="totalAbsen">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-purple">
          <span class="info-box-icon"><i class="fa fa-medkit"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Sakit</span>
            <span class="info-box-number" id="totalSakit">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Izin</span>
            <span class="info-box-number" id="totalIzin">0</span>
          </div>
        </div>
      </div>
      
      <div class="col-md-2 col-sm-4 col-xs-6">
        <div class="info-box bg-teal">
          <span class="info-box-icon"><i class="fa fa-plane"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Cuti</span>
            <span class="info-box-number" id="totalCuti">0</span>
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

    <!-- Tabel Kehadiran -->
    <div class="row" id="dataSection" style="display:none;">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-table"></i> Rekap Kehadiran - <span id="periodeTampil"></span> - <span id="bagianTampil" class="text-primary"></span></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="alert alert-info">
              <strong>Keterangan:</strong>
              <span class="label label-success">✓</span> Hadir &nbsp;
              <span class="label label-danger">✗</span> Tidak Hadir &nbsp;
              <span class="label label-default">-</span> Libur/Weekend &nbsp;
              <span class="label label-warning">½</span> Izin/Sakit &nbsp;
              <span class="label label-info">C</span> Cuti
              <div class="form-group" style="margin-top: 10px;">
                <label><strong>Filter Nama:</strong></label>
                <input type="text" class="form-control" id="filterNama" placeholder="Masukkan nama karyawan...">
              </div>
            </div>
            
            <!-- Table wrapper with horizontal scroll -->
            <div class="table-responsive" style="overflow-x: auto; max-height: 600px; overflow-y: auto;">
              <table class="table table-bordered table-hover" id="tblKehadiran" style="width: 100%; white-space: nowrap;">
                <thead>
                  <!-- Header akan di-generate oleh JavaScript -->
                </thead>
                <tbody>
                  <!-- Data akan di-load via AJAX -->
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

<style>
/* Sticky column styles */
#tblKehadiran {
  border-collapse: separate;
  border-spacing: 0;
}

#tblKehadiran th,
#tblKehadiran td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

#tblKehadiran thead th {
  position: sticky;
  top: 0;
  z-index: 5;
  background: #34495e;
  color: white;
}

/* Sticky left columns */
#tblKehadiran td:nth-child(1),
#tblKehadiran td:nth-child(2),
#tblKehadiran td:nth-child(3),
#tblKehadiran th:nth-child(1),
#tblKehadiran th:nth-child(2),
#tblKehadiran th:nth-child(3) {
  position: sticky;
  background: white;
  z-index: 4;
  border-right: 2px solid #34495e;
}

#tblKehadiran td:nth-child(1),
#tblKehadiran th:nth-child(1) {
  left: 0;
  width: 50px;
  min-width: 50px;
}

#tblKehadiran td:nth-child(2),
#tblKehadiran th:nth-child(2) {
  left: 50px;
  width: 120px;
  min-width: 120px;
}

#tblKehadiran td:nth-child(3),
#tblKehadiran th:nth-child(3) {
  left: 170px;
  min-width: 250px;
  text-align: left;
  font-weight: bold;
}

/* Date cell styling */
#tblKehadiran td,
#tblKehadiran th {
  min-width: 45px;
  max-width: 45px;
  text-align: center;
  padding: 8px 4px;
  font-size: 14px;
}

/* Status styling */
.status-hadir {
  background: #d4edda !important;
  color: #155724;
  font-weight: bold;
  font-size: 18px;
}

.status-absen {
  background: #f8d7da !important;
  color: #721c24;
  font-weight: bold;
  font-size: 18px;
}

.status-libur {
  background: #e2e3e5 !important;
  color: #6c757d;
}

.status-izin {
  background: #fff3cd !important;
  color: #856404;
  font-weight: bold;
}

.status-cuti {
  background: #d1ecf1 !important;
  color: #0c5460;
  font-weight: bold;
}

/* Info box styles */
.info-box {
  display: block;
  min-height: 90px;
  background: #fff;
  width: 100%;
  box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  border-radius: 2px;
  margin-bottom: 15px;
}

.info-box-icon {
  border-top-left-radius: 2px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 2px;
  display: block;
  float: left;
  height: 90px;
  width: 90px;
  text-align: center;
  font-size: 45px;
  line-height: 90px;
  background: rgba(0,0,0,0.2);
}

.info-box-content {
  padding: 5px 10px;
  margin-left: 90px;
}

.info-box-text {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.info-box-number {
  display: block;
  font-weight: bold;
  font-size: 32px;
}

.bg-green { background: #00a65a !important; color: white; }
.bg-blue { background: #3c8dbc !important; color: white; }
.bg-yellow { background: #f39c12 !important; color: white; }
.bg-red { background: #dd4b39 !important; color: white; }
.bg-purple { background: #9b59b6 !important; color: white; }
.bg-aqua { background: #3498db !important; color: white; }
.bg-teal { background: #1abc9c !important; color: white; }

/* Hover effect */
#tblKehadiran tbody tr:hover {
  background-color: #f5f5f5;
}

/* Weekend column highlight */
.weekend-col {
  background-color: #f0f0f0 !important;
}
</style>

<script>
$(document).ready(function() {
  console.log('🚀 Page loaded, initializing...');
  
  // Test jQuery
  if (typeof jQuery === 'undefined') {
    console.error('❌ jQuery not loaded!');
    alert('Error: jQuery tidak terdeteksi. Refresh halaman.');
    return;
  }
  console.log('✅ jQuery version:', jQuery.fn.jquery);
  
  // Set default to current month - start date as first day, end date as today
  let currentDate = new Date();
  let currentYear = currentDate.getFullYear();
  let currentMonth = currentDate.getMonth(); // Month is 0-indexed
  
  // First day of current month
  let startDate = new Date(currentYear, currentMonth, 1);
  // Today's date (full date object with current date)
  let endDate = new Date();
  
  // Format dates as YYYY-MM-DD for input[type=date]
  let pad = function(num) { return (num < 10 ? '0' : '') + num; };
  let startDateFormatted = currentYear + '-' + pad(currentMonth + 1) + '-01';
  let endDateFormatted = endDate.getFullYear() + '-' + pad(endDate.getMonth() + 1) + '-' + pad(endDate.getDate());
  
  $('#startDate').val(startDateFormatted);
  $('#endDate').val(endDateFormatted);
  
  console.log('✅ Default filter set:', startDateFormatted + ' to ' + endDateFormatted);
  
  // Check if form exists
  if ($('#formFilter').length === 0) {
    console.error('❌ Form #formFilter not found!');
  } else {
    console.log('✅ Form found, attaching event listener...');
  }
  
  // Form submit - multiple approaches for compatibility
  $('#formFilter').on('submit', function(e) {
    console.log('📝 Form submit triggered via form event');
    e.preventDefault();
    loadKehadiranData();
    return false;
  });
  
  // Also attach to button directly as fallback
  $('#formFilter button[type="submit"]').on('click', function(e) {
    console.log('📝 Button clicked directly');
    e.preventDefault();
    loadKehadiranData();
    return false;
  });
  
  // Export XLSX for monthly attendance
  $('#btnExportExcel').click(function() {
    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();
    let bagian = $('#filterBagian').val();
    const url = '<?php echo base_url('rekap/export_kehadiran_bulanan')?>' +
                '?startDate=' + encodeURIComponent(startDate) +
                '&endDate=' + encodeURIComponent(endDate) +
                '&bagian=' + encodeURIComponent(bagian||'');
    window.open(url, '_blank');
  });

}); // End document.ready

// Function definitions (outside document.ready but accessible)
function loadKehadiranData() {
  let startDate = $('#startDate').val();
  let endDate = $('#endDate').val();
  let bagian = $('#filterBagian').val();
  
  console.log('🔍 Loading data for:', startDate + ' to ' + endDate + ' - Bagian:', bagian || 'Semua');
  
  // Show loading
  $('#loadingSection').show();
  $('#dataSection').hide();
  $('#infoPanel').hide();
  $('#btnExportExcel').hide();
  
  $.ajax({
    url: '<?php echo base_url('rekap/get_kehadiran_bulanan')?>',
    method: 'POST',
    data: { startDate: startDate, endDate: endDate, bagian: bagian },
    dataType: 'json',
    success: function(res) {
      console.log('✅ Response received:', res);
      $('#loadingSection').hide();
      
      if (res.success) {
        console.log('📊 Data:', {
          karyawan: res.data.length,
          tanggal: res.dates.length,
          summary: res.summary,
          debug: res.debug
        });
        
        renderKehadiranTable(res.data, res.dates, res.summary);
        $('#dataSection').show();
        $('#infoPanel').show();
  $('#btnExportExcel').show();
        
        // Update periode dan bagian
        $('#periodeTampil').text(startDate + ' s/d ' + endDate);
        $('#bagianTampil').text(res.summary.nama_bagian || 'Semua Bagian');
        
        // Update info boxes
        $('#totalKaryawan').text(res.summary.total_karyawan);
        $('#hariKerja').text(res.summary.hari_kerja);
        $('#avgKehadiran').text(res.summary.avg_kehadiran + '%');
        $('#totalAbsen').text(res.summary.total_absen);
        $('#totalSakit').text(res.summary.total_sakit || 0);
        $('#totalIzin').text(res.summary.total_izin || 0);
        $('#totalCuti').text(res.summary.total_cuti || 0);
      } else {
        console.error('❌ Error response:', res);
        alert('Error: ' + res.message);
        $('#loadingSection').hide();
      }
    },
    error: function(xhr, status, error) {
      console.error('❌ AJAX Error:', {
        status: status,
        error: error,
        response: xhr.responseText
      });
      alert('Terjadi kesalahan: ' + error + '\n\nCek console untuk detail.');
      $('#loadingSection').hide();
    }
  });
}

function renderKehadiranTable(data, dates, summary) {
  console.log('🎨 Rendering table...');
  
  if (!data || data.length === 0) {
    $('#tblKehadiran tbody').html(
      '<tr><td colspan="100%" class="text-center">Tidak ada data karyawan</td></tr>'
    );
    return;
  }
  
  let html = '';
  
  // Header row - dates
  let headerHtml = '<tr style="position:sticky; top:0; z-index:10; background:#2c3e50; color:white;">';
  headerHtml += '<th style="position:sticky; left:0; z-index:11; background:#2c3e50; width:50px;">No</th>';
  headerHtml += '<th style="position:sticky; left:50px; z-index:11; background:#2c3e50; width:120px;">NIK</th>';
  headerHtml += '<th style="position:sticky; left:170px; z-index:11; background:#2c3e50; min-width:250px;">Nama Karyawan</th>';
  
  dates.forEach(function(d) {
    let isWeekend = (d.day_name === 'Sabtu' || d.day_name === 'Minggu');
    let bgColor = isWeekend ? '#95a5a6' : '#2c3e50';
    let dayShort = d.day_name ? d.day_name.substring(0, 3) : '';
    headerHtml += '<th style="background:' + bgColor + '; min-width:45px; text-align:center;">' + 
                  d.tanggal + '<br><small>' + dayShort + '</small></th>';
  });
  
  headerHtml += '<th style="background:#27ae60; min-width:80px;">Total<br>Hadir</th>';
  headerHtml += '<th style="background:#e74c3c; min-width:80px;">Total<br>Absen</th>';
  headerHtml += '<th style="background:#f39c12; min-width:80px;">Tidak<br>Hadir</th>';
  headerHtml += '<th style="background:#9b59b6; min-width:80px;">Jumlah<br>Sakit</th>';
  headerHtml += '<th style="background:#3498db; min-width:80px;">Jumlah<br>Izin</th>';
  headerHtml += '<th style="background:#1abc9c; min-width:80px;">Jumlah<br>Cuti</th>';
  headerHtml += '<th style="background:#34495e; min-width:80px;">%<br>Hadir</th>';
  headerHtml += '</tr>';
  
  $('#tblKehadiran thead').html(headerHtml);
  
  // Body rows - karyawan
  data.forEach(function(karyawan, idx) {
    html += '<tr>';
    html += '<td style="position:sticky; left:0; background:white; z-index:5; width:50px; text-align:center;">' + (idx + 1) + '</td>';
    html += '<td style="position:sticky; left:50px; background:white; z-index:5; width:120px;">' + karyawan.nik + '</td>';
    html += '<td style="position:sticky; left:170px; background:white; z-index:5; text-align:left; min-width:250px;">' + karyawan.nama_karyawan + '</td>';
    
    // Count attendance per employee
    let totalHadir = 0;
    let totalAbsen = 0;
    let totalTidakHadir = 0; // New counter for non-attendance reasons
    
    dates.forEach(function(d) {
      let status = karyawan.kehadiran[d.full_date];
      let cssClass = '';
      let icon = '-';
      
      if (status === 'hadir') {
        cssClass = 'status-hadir';
        icon = '✓';
        totalHadir++;
      } else if (status === 'absen') {
        cssClass = 'status-absen';
        icon = '✗';
        totalAbsen++;
      } else if (status === 'libur') {
        cssClass = 'status-libur';
        icon = '-';
        totalTidakHadir++; // Count libur as tidak hadir
      } else if (status === 'izin') {
        cssClass = 'status-izin';
        icon = '½';
        totalTidakHadir++; // Count izin as tidak hadir
      } else if (status === 'cuti') {
        cssClass = 'status-cuti';
        icon = 'C';
        totalTidakHadir++; // Count cuti as tidak hadir
      }
      
      html += '<td class="' + cssClass + '">' + icon + '</td>';
    });
    
    // Calculate percentage
    let hariKerja = summary.hari_kerja || 23; // Use from summary
    let persenHadir = hariKerja > 0 ? Math.round((totalHadir / hariKerja) * 100) : 0;
    
    // Get izin counts from karyawan data
    let jumlahSakit = karyawan.jumlah_sakit || 0;
    let jumlahIzin = karyawan.jumlah_izin || 0;
    let jumlahCuti = karyawan.jumlah_cuti || 0;
    
    html += '<td style="background:#ecf0f1;"><strong>' + totalHadir + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + totalAbsen + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + totalTidakHadir + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + jumlahSakit + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + jumlahIzin + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + jumlahCuti + '</strong></td>';
    html += '<td style="background:#ecf0f1;"><strong>' + persenHadir + '%</strong></td>';
    html += '</tr>';
  });
  
  $('#tblKehadiran tbody').html(html);
  console.log('✅ Table rendered with ' + data.length + ' rows');
}

// Function to filter table rows based on name input
function filterTableByName() {
  let filter = $('#filterNama').val().toLowerCase();
  
  // Get all rows in the table body
  let rows = $('#tblKehadiran tbody tr');
  
  rows.each(function() {
    let row = $(this);
    let nameCell = row.find('td:nth-child(3)'); // Third column is the name
    let nameText = nameCell.text().toLowerCase();
    
    if (nameText.includes(filter)) {
      row.show();
    } else {
      row.hide();
    }
  });
}

// Attach real-time filtering to the name input
$(document).on('keyup', '#filterNama', function() {
  filterTableByName();
});

// Global error handler
$(window).on('error', function(e) {
  console.error('⚠️ Window error:', e.originalEvent.message);
});
</script>

  </div>
</div>
<!-- /page content -->

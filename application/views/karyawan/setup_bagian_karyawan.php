<style>
  .fixed-bottom-action {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 2px solid #ddd;
    padding: 15px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 999;
  }
  .main-content-wrapper {
    padding-bottom: 200px;
  }
  .table-container {
    max-height: calc(100vh - 400px);
    overflow-y: auto;
  }
  .selected-count {
    font-size: 16px;
    font-weight: bold;
    color: #2196F3;
  }
  
  /* Debounce search info */
  .search-info {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
  }
  
  .search-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 5px;
    background-color: #ccc;
  }
  
  .search-indicator.active {
    background-color: #2196F3;
    animation: pulse 1s infinite;
  }
  
  @keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
  }
  
  /* Loading spinner animation */
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  #loading i {
    animation: spin 1s linear infinite;
  }
  
  /* Slide up animation */
  @keyframes slideUp {
    from {
      transform: translateY(100%);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
</style>

<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Setup Bagian Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h4>Cari Karyawan</h4>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">

          <!-- SEARCH SECTION -->
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-8">
              <input type="text" id="search_setup" class="form-control" placeholder="Filter berdasarkan NIK atau Nama Karyawan... (min. 2 karakter)">
              <small class="search-info">
                <span class="search-indicator"></span>Ketik untuk filter data di tabel (minimum 2 karakter)
              </small>
            </div>
          </div>

          <!-- LOADING SPINNER -->
          <div id="loading" style="display: none; text-align: center; padding: 20px;">
            <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i> Loading data...
          </div>

          <!-- TABEL KARYAWAN -->
          <div id="table-section">
            <div class="table-responsive table-container">
              <table class="table table-striped table-hover">
                <thead style="position: sticky; top: 0; background: #f5f5f5;">
                  <tr>
                    <th style="width: 40px;">
                      <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                    </th>
                    <th style="width: 80px;">NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Bagian Sekarang</th>
                    <th>Sub Bagian Sekarang</th>
                  </tr>
                </thead>
                <tbody id="tbody_karyawan">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- EMPTY STATE -->
          <div id="empty-state" style="display: none; text-align: center; padding: 40px; color: #999;">
            <i class="fa fa-search" style="font-size: 48px; margin-bottom: 10px;"></i>
            <p>Data karyawan tidak ditemukan</p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- FIXED BOTTOM ACTION -->
<div class="fixed-bottom-action" style="display: none;" id="action-section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-12">
            <span class="selected-count">
              <i class="fa fa-check-circle"></i> <span id="selected-count">0</span> karyawan dipilih
            </span>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label>Bagian Tujuan: <span style="color: red;">*</span></label>
            <select id="bagian_tujuan" class="form-control" onchange="updateSubBagian()">
              <option value="">-- Pilih Bagian --</option>
              <?php foreach ($bagian as $b): ?>
                <option value="<?php echo $b['recid_bag']; ?>"><?php echo $b['indeks_hr']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label>Sub Bagian Tujuan: <small style="color: #999;">(Optional)</small></label>
            <select id="subbag_tujuan" class="form-control">
              <option value="">-- Tidak Berubah --</option>
            </select>
          </div>

          <div class="col-md-4"></div>
        </div>

        <div class="row" style="margin-top: 15px;">
          <div class="col-md-12">
            <button class="btn btn-secondary" onclick="clearSelection()">
              <i class="fa fa-times"></i> Batal
            </button>
            <button class="btn btn-info" onclick="previewSetup()">
              <i class="fa fa-eye"></i> Preview
            </button>
            <button class="btn btn-success" onclick="processSetup()" style="margin-left: 10px;">
              <i class="fa fa-check"></i> Proses Setup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Setup Bagian Karyawan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Setup Bagian untuk:</strong><br>
          <p id="preview-info"></p>
        </div>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Dari</th>
                <th>Ke</th>
              </tr>
            </thead>
            <tbody id="preview-table">
              <!-- Dynamic content -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" onclick="confirmSetup()">Lanjutkan Setup</button>
      </div>
    </div>
  </div>
</div>

<script>
var allKaryawan = [];
var filteredKaryawan = [];
var selectedKaryawanSet = new Set(); // Global set untuk track selected recid_karyawan
var subBagianData = <?php echo json_encode($sub_bagian); ?>;

// Debounce Configuration
var debounceTimer = null;
var DEBOUNCE_DELAY = 400; // 400ms delay
var MIN_SEARCH_LENGTH = 2; // Minimum 2 characters

// Load semua karyawan saat page load
document.addEventListener('DOMContentLoaded', function() {
  loadAllKaryawan();

  // Debounced search on input
  document.getElementById('search_setup').addEventListener('keyup', function(e) {
    debouncedFilterKaryawan();
  });
});

// Load semua karyawan aktif
function loadAllKaryawan() {
  document.getElementById('loading').style.display = 'block';
  document.getElementById('table-section').style.display = 'none';

  $.ajax({
    url: '<?php echo base_url("Karyawan/get_karyawan_setup"); ?>',
    type: 'GET',
    dataType: 'JSON',
    timeout: 10000,
    data: {
      search: '', // kosong untuk ambil sebagian (limit)
      limit: 30   // when blank search, request only 30 records to avoid loading everything
    },
    success: function(response) {
      if (response.success && response.total > 0) {
        allKaryawan = response.data;
        filteredKaryawan = response.data;
        renderTable();
        document.getElementById('table-section').style.display = 'block';
      } else {
        document.getElementById('empty-state').style.display = 'block';
      }
      document.getElementById('loading').style.display = 'none';
    },
    error: function(jqXHR, textStatus, errorThrown) {
      var errorMsg = 'Gagal mengambil data karyawan';
      if (textStatus === 'timeout') {
        errorMsg = 'Request timeout - data terlalu besar';
      }
      alert(errorMsg);
      document.getElementById('loading').style.display = 'none';
    }
  });
}

// Debounced Filter Function
function debouncedFilterKaryawan() {
  clearTimeout(debounceTimer);
  var searchInput = document.getElementById('search_setup').value.trim();
  var indicator = document.querySelector('.search-indicator');
  
  // Jika kosong, reload data awal (30 records)
  if (searchInput.length === 0) {
    indicator.classList.remove('active');
    loadAllKaryawan();
    return;
  }
  
  // Jika kurang dari minimum length, show info
  if (searchInput.length < MIN_SEARCH_LENGTH) {
    indicator.classList.remove('active');
    document.getElementById('table-section').style.display = 'none';
    document.getElementById('empty-state').innerHTML = '<i class="fa fa-info-circle" style="font-size: 48px; margin-bottom: 10px; color: #2196F3;"></i><p>Ketik minimal ' + MIN_SEARCH_LENGTH + ' karakter untuk mulai filter</p>';
    document.getElementById('empty-state').style.display = 'block';
    return;
  }
  
  // Show loading indicator
  indicator.classList.add('active');
  
  // Set debounce timer - fetch dari server dengan search term
  debounceTimer = setTimeout(function() {
    searchKaryawanFromServer(searchInput);
  }, DEBOUNCE_DELAY);
}

// Fetch dari server dengan search term (untuk mencari data lebih besar dari 30 record awal)
function searchKaryawanFromServer(searchTerm) {
  var indicator = document.querySelector('.search-indicator');
  
  $.ajax({
    url: '<?php echo base_url("Karyawan/get_karyawan_setup"); ?>',
    type: 'GET',
    dataType: 'JSON',
    timeout: 10000,
    data: {
      search: searchTerm,
      limit: 1000  // Ketika search, ambil sampai 1000 records
    },
    success: function(response) {
      indicator.classList.remove('active');
      
      if (response.success && response.total > 0) {
        allKaryawan = response.data;
        filteredKaryawan = response.data;
        renderTable();
        document.getElementById('table-section').style.display = 'block';
        document.getElementById('empty-state').style.display = 'none';
      } else {
        document.getElementById('empty-state').innerHTML = '<i class="fa fa-search" style="font-size: 48px; margin-bottom: 10px;"></i><p>Tidak ada karyawan ditemukan untuk "' + searchTerm + '"</p>';
        document.getElementById('empty-state').style.display = 'block';
        document.getElementById('table-section').style.display = 'none';
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      indicator.classList.remove('active');
      var errorMsg = 'Gagal mencari data';
      if (textStatus === 'timeout') {
        errorMsg = 'Request timeout';
      }
      document.getElementById('empty-state').innerHTML = '<i class="fa fa-exclamation-circle" style="font-size: 48px; margin-bottom: 10px; color: #d32f2f;"></i><p>' + errorMsg + '</p>';
      document.getElementById('empty-state').style.display = 'block';
      document.getElementById('table-section').style.display = 'none';
    }
  });
}

// Render tabel karyawan dengan restore selected checkboxes
function renderTable() {
  var tbody = document.getElementById('tbody_karyawan');
  tbody.innerHTML = '';

  filteredKaryawan.forEach(function(kar) {
    var isChecked = selectedKaryawanSet.has(parseInt(kar.recid_karyawan));
    var checkedAttr = isChecked ? 'checked' : '';
    
    var row = `
      <tr>
        <td>
          <input type="checkbox" value="${kar.recid_karyawan}" ${checkedAttr} onchange="toggleKaryawan(this)">
        </td>
        <td>${kar.nik}</td>
        <td>${kar.nama_karyawan}</td>
        <td>${kar.indeks_hr || '-'}</td>
        <td>${kar.sub_bag || '-'}</td>
      </tr>
    `;
    tbody.innerHTML += row;
  });

  document.getElementById('select-all').checked = false;
  updateSelectedCount();
}

// Toggle karyawan selection
function toggleKaryawan(checkbox) {
  var recidKaryawan = parseInt(checkbox.value);
  
  if (checkbox.checked) {
    selectedKaryawanSet.add(recidKaryawan);
  } else {
    selectedKaryawanSet.delete(recidKaryawan);
  }
  updateSelectedCount();
}

// Toggle select all
function toggleSelectAll(checkbox) {
  var checkboxes = document.querySelectorAll('#tbody_karyawan input[type="checkbox"]');
  checkboxes.forEach(function(cb) {
    cb.checked = checkbox.checked;
    var recidKaryawan = parseInt(cb.value);
    if (checkbox.checked) {
      selectedKaryawanSet.add(recidKaryawan);
    } else {
      selectedKaryawanSet.delete(recidKaryawan);
    }
  });
  updateSelectedCount();
}

// Update selected count
function updateSelectedCount() {
  var count = selectedKaryawanSet.size; // Gunakan global set count, bukan checkbox yang visible

  document.getElementById('selected-count').textContent = count;

  if (count > 0) {
    var actionSection = document.getElementById('action-section');
    actionSection.style.display = 'block';
    actionSection.style.animation = 'slideUp 0.3s ease-out';
  } else {
    document.getElementById('action-section').style.display = 'none';
  }
}

// Update sub bagian dropdown
function updateSubBagian() {
  var bagianId = document.getElementById('bagian_tujuan').value;
  var subBagSelect = document.getElementById('subbag_tujuan');

  subBagSelect.innerHTML = '<option value="">-- Tidak Berubah --</option>';

  if (bagianId) {
    var filtered = subBagianData.filter(sb => sb.recid_bag == bagianId);
    filtered.sort(function(a, b) {
      return a.sub_bag.localeCompare(b.sub_bag);
    });

    filtered.forEach(function(sb) {
      var option = document.createElement('option');
      option.value = sb.recid_subbag;
      option.text = sb.sub_bag;
      subBagSelect.appendChild(option);
    });
  }
}

// Preview setup
function previewSetup() {
  var bagianTujuan = document.getElementById('bagian_tujuan').value;
  
  if (!bagianTujuan) {
    alert('Pilih Bagian Tujuan terlebih dahulu');
    return;
  }

  if (selectedKaryawanSet.size === 0) {
    alert('Pilih minimal 1 karyawan');
    return;
  }

  // Get selected data from global set dan allKaryawan array
  var selected = [];
  selectedKaryawanSet.forEach(function(recidKaryawan) {
    var kar = allKaryawan.find(k => k.recid_karyawan == recidKaryawan);
    if (kar) {
      selected.push(kar);
    }
  });

  // Get destination bagian name
  var bagianSelect = document.getElementById('bagian_tujuan');
  var bagianName = bagianSelect.options[bagianSelect.selectedIndex].text;

  var subbagTujuan = document.getElementById('subbag_tujuan').value;
  var subbagName = subbagTujuan ? document.getElementById('subbag_tujuan').options[document.getElementById('subbag_tujuan').selectedIndex].text : '(Tidak berubah)';

  // Build preview table
  var previewTable = document.getElementById('preview-table');
  previewTable.innerHTML = '';

  selected.forEach(function(kar) {
    var dari = kar.indeks_hr + (kar.sub_bag ? ' - ' + kar.sub_bag : '');
    var ke = bagianName + (subbagTujuan ? ' - ' + subbagName : ' - (Tidak berubah)');

    var row = `
      <tr>
        <td>${kar.nik}</td>
        <td>${kar.nama_karyawan}</td>
        <td>${dari}</td>
        <td><strong>${ke}</strong></td>
      </tr>
    `;
    previewTable.innerHTML += row;
  });

  // Update info
  document.getElementById('preview-info').innerHTML = `
    <strong>${selected.length} karyawan</strong> akan disetup ke bagian <strong>${bagianName}</strong>
  `;

  // Show modal
  $('#previewModal').modal('show');
}

// Confirm setup
function confirmSetup() {
  $('#previewModal').modal('hide');
  processSetup();
}

// Process setup
function processSetup() {
  var bagianTujuan = document.getElementById('bagian_tujuan').value;
  var subbagTujuan = document.getElementById('subbag_tujuan').value || '';

  if (selectedKaryawanSet.size === 0) {
    alert('Pilih minimal 1 karyawan');
    return;
  }

  // Convert Set to Array
  var recidArray = Array.from(selectedKaryawanSet);

  $.ajax({
    url: '<?php echo base_url("Karyawan/process_setup_bagian"); ?>',
    type: 'POST',
    dataType: 'JSON',
    data: {
      recid_karyawan: recidArray,
      recid_bagian_baru: bagianTujuan,
      recid_subbag_baru: subbagTujuan
    },
    success: function(response) {
      if (response.success) {
        alert(response.message);
        clearSelection();
        loadAllKaryawan();
      } else {
        alert('Error: ' + response.message);
      }
    },
    error: function() {
      alert('Gagal memproses setup bagian');
    }
  });
}

// Clear selection
function clearSelection() {
  document.getElementById('search_setup').value = '';
  document.getElementById('bagian_tujuan').value = '';
  document.getElementById('subbag_tujuan').innerHTML = '<option value="">-- Tidak Berubah --</option>';
  selectedKaryawanSet.clear();
  document.getElementById('selected-count').textContent = '0';
  document.getElementById('action-section').style.display = 'none';
  loadAllKaryawan(); // Reload data awal (30 records)
}

// Handle Enter key pada search
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('search_setup').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      searchKaryawan();
    }
  });
});
</script>

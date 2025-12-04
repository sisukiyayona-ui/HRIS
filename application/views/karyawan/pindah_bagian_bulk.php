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
        <h3>Pindah Bagian (Bulk Update)</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-8">
                <h4>Filter Karyawan</h4>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-md-3">
                    <select id="filter_bagian" class="form-control input-sm" onchange="fetchKaryawan()">
                      <option value="">-- Pilih Bagian --</option>
                      <?php foreach ($bagian as $b): ?>
                        <option value="<?php echo $b['recid_bag']; ?>"><?php echo $b['indeks_hr']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div class="col-md-3">
                    <select id="filter_subbag" class="form-control input-sm" onchange="fetchKaryawan()">
                      <option value="">-- Pilih Sub Bagian --</option>
                    </select>
                  </div>
                  
                  <div class="col-md-4">
                    <input type="text" id="search_karyawan" class="form-control input-sm" placeholder="Cari NIK / Nama (min. 2 karakter)" onkeyup="debouncedSearch()" title="Ketik minimal 2 karakter untuk search">
                  </div>
                  
                  <div class="col-md-2">
                    <button class="btn btn-primary btn-sm btn-block" onclick="fetchKaryawan()"><i class="fa fa-search"></i> Cari</button>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-right">
                <a href="<?php echo base_url() ?>Karyawan/tracking_perubahan_bagian" class="btn btn-info btn-sm"><i class="fa fa-history"></i> Tracking Perubahan</a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">

          <!-- LOADING SPINNER -->
          <div id="loading" style="display: none; text-align: center; padding: 20px;">
            <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i> Loading data...
          </div>

          <!-- TABEL KARYAWAN -->
          <div id="table-section" style="display: none;">
            <div class="table-responsive table-container">
              <table class="table table-striped table-hover">
                <thead style="position: sticky; top: 0; background: #f5f5f5;">
                  <tr>
                    <th style="width: 40px;">
                      <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                    </th>
                    <th style="width: 80px;">NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Bagian</th>
                    <th>Sub Bagian</th>
                  </tr>
                </thead>
                <tbody id="tbody_karyawan">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- EMPTY STATE -->
          <div id="empty-state" style="text-align: center; padding: 40px; color: #999;">
            <i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i>
            <p>Gunakan filter di atas untuk mencari karyawan</p>
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
          <div class="col-md-3">
            <label>Bagian Tujuan:</label>
            <select id="bagian_tujuan" class="form-control" onchange="updateSubBagian()">
              <option value="">-- Pilih Bagian --</option>
              <?php foreach ($bagian as $b): ?>
                <option value="<?php echo $b['recid_bag']; ?>"><?php echo $b['indeks_hr']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label>Sub Bagian Tujuan: <small style="color: #999;">(Optional)</small></label>
            <select id="subbag_tujuan" class="form-control">
              <option value="">-- Tidak Berubah --</option>
            </select>
          </div>

          <div class="col-md-2">
            <label>Tanggal Efektif:</label>
            <input type="date" id="tanggal_efektif" class="form-control" value="<?php echo date('Y-m-d'); ?>">
          </div>

          <div class="col-md-4">
            <label>Catatan (Optional):</label>
            <input type="text" id="catatan" class="form-control" placeholder="Misal: Promosi, Restruktur, dll">
          </div>
        </div>

        <div class="row" style="margin-top: 15px;">
          <div class="col-md-12">
            <button class="btn btn-secondary" onclick="clearSelection()">
              <i class="fa fa-times"></i> Batal
            </button>
            <button class="btn btn-info" onclick="previewPindah()">
              <i class="fa fa-eye"></i> Preview
            </button>
            <button class="btn btn-success" onclick="processPindah()" style="margin-left: 10px;">
              <i class="fa fa-check"></i> Proses Update
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
        <h5 class="modal-title">Preview Perubahan Bagian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <strong>Informasi Perubahan:</strong><br>
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
        <button type="button" class="btn btn-success" onclick="confirmPindah()">Lanjutkan Proses</button>
      </div>
    </div>
  </div>
</div>

<script>
var allKaryawan = [];
var selectedKaryawan = new Set();
var subBagianData = <?php echo json_encode($sub_bagian); ?>;

// Debounce Timer
var debounceTimer = null;
var DEBOUNCE_DELAY = 500; // 500ms delay
var MIN_SEARCH_LENGTH = 2; // Minimum 2 characters

// Debounced Search Function
function debouncedSearch() {
  clearTimeout(debounceTimer);
  var search = document.getElementById('search_karyawan').value.trim();
  
  // Jika search kosong atau kurang dari min length, langsung clear
  if (search.length === 0) {
    fetchKaryawan();
    return;
  }
  
  // Jika kurang dari min length, tunggu user ketik lebih banyak
  if (search.length < MIN_SEARCH_LENGTH) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('table-section').style.display = 'none';
    document.getElementById('empty-state').innerHTML = '<i class="fa fa-info-circle" style="font-size: 48px; margin-bottom: 10px; color: #2196F3;"></i><p>Ketik minimal ' + MIN_SEARCH_LENGTH + ' karakter untuk search</p>';
    document.getElementById('empty-state').style.display = 'block';
    return;
  }
  
  // Set debounce timer untuk menunda pemanggilan fungsi
  debounceTimer = setTimeout(function() {
    fetchKaryawan();
  }, DEBOUNCE_DELAY);
}

// Fetch karyawan berdasarkan filter
function fetchKaryawan() {
  var bagian = document.getElementById('filter_bagian').value;
  var subbag = document.getElementById('filter_subbag').value;
  var search = document.getElementById('search_karyawan').value.trim();

  document.getElementById('loading').style.display = 'block';
  document.getElementById('table-section').style.display = 'none';
  document.getElementById('empty-state').style.display = 'none';
  
  var startTime = performance.now();

  $.ajax({
    url: '<?php echo base_url("Karyawan/get_karyawan_by_filter"); ?>',
    type: 'GET',
    dataType: 'JSON',
    timeout: 10000, // 10 second timeout
    data: {
      recid_bag: bagian,
      recid_subbag: subbag,
      search: search,
      limit: 100 // Limit hasil maksimal 100 records
    },
    success: function(response) {
      var endTime = performance.now();
      var loadTime = ((endTime - startTime) / 1000).toFixed(2);
      
      if (response.success && response.total > 0) {
        allKaryawan = response.data;
        renderTable();
        
        // Tampilkan jumlah hasil dan waktu loading
        var resultInfo = 'Menampilkan ' + response.data.length + ' dari ' + response.total + ' karyawan (~' + loadTime + 's)';
        document.getElementById('table-section').style.display = 'block';
        document.getElementById('loading').style.display = 'none';
        
        // Optional: Tampilkan info di console
        console.log('Search result:', resultInfo);
      } else {
        var noResultMsg = '';
        if (search.length > 0) {
          noResultMsg = '<i class="fa fa-search" style="font-size: 48px; margin-bottom: 10px;"></i><p>Data karyawan tidak ditemukan untuk pencarian "' + search + '"</p>';
        } else {
          noResultMsg = '<i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i><p>Gunakan filter di atas untuk mencari karyawan</p>';
        }
        document.getElementById('empty-state').innerHTML = noResultMsg;
        document.getElementById('empty-state').style.display = 'block';
      }
      document.getElementById('loading').style.display = 'none';
    },
    error: function(jqXHR, textStatus, errorThrown) {
      document.getElementById('loading').style.display = 'none';
      var errorMsg = 'Terjadi kesalahan saat mengambil data';
      if (textStatus === 'timeout') {
        errorMsg = 'Request timeout - data terlalu banyak atau server lambat';
      }
      alert(errorMsg);
      console.error('Error:', textStatus, errorThrown);
    }
  });
}

// Update sub bagian dropdown saat filter bagian berubah
$('#filter_bagian').on('change', function() {
  var recid_bag = $(this).val();
  var filtered = subBagianData.filter(sb => sb.recid_bag == recid_bag);
  
  // Sort by sub_bag A-Z
  filtered.sort((a, b) => a.sub_bag.localeCompare(b.sub_bag));
  
  var html = '<option value="">-- Pilih Sub Bagian --</option>';
  filtered.forEach(sb => {
    html += '<option value="' + sb.recid_subbag + '">' + sb.sub_bag + '</option>';
  });
  
  $('#filter_subbag').html(html);
  
  // Jika bagian berubah, langsung reset search dan fetch
  document.getElementById('search_karyawan').value = '';
  clearTimeout(debounceTimer);
  fetchKaryawan();
});

// Update sub bagian tujuan saat bagian tujuan berubah
function updateSubBagian() {
  var recid_bag = document.getElementById('bagian_tujuan').value;
  var filtered = subBagianData.filter(sb => sb.recid_bag == recid_bag);
  
  // Sort by sub_bag A-Z
  filtered.sort((a, b) => a.sub_bag.localeCompare(b.sub_bag));
  
  var html = '<option value="">-- Pilih Sub Bagian --</option>';
  filtered.forEach(sb => {
    html += '<option value="' + sb.recid_subbag + '">' + sb.sub_bag + '</option>';
  });
  
  document.getElementById('subbag_tujuan').innerHTML = html;
}

// Render tabel karyawan
function renderTable() {
  var html = '';
  allKaryawan.forEach((kar, idx) => {
    var checked = selectedKaryawan.has(kar.recid_karyawan) ? 'checked' : '';
    html += '<tr>';
    html += '<td><input type="checkbox" class="kar-checkbox" value="' + kar.recid_karyawan + '" ' + checked + ' onchange="updateSelection()"></td>';
    html += '<td>' + kar.nik + '</td>';
    html += '<td>' + kar.nama_karyawan + '</td>';
    html += '<td>' + (kar.bagian || '-') + '</td>';
    html += '<td>' + (kar.sub_bagian || '-') + '</td>';
    html += '</tr>';
  });
  
  document.getElementById('tbody_karyawan').innerHTML = html;
}

// Update selection
function updateSelection() {
  selectedKaryawan.clear();
  
  document.querySelectorAll('.kar-checkbox:checked').forEach(cb => {
    selectedKaryawan.add(parseInt(cb.value));
  });
  
  var count = selectedKaryawan.size;
  document.getElementById('selected-count').textContent = count;
  
  // Show/hide action section with animation
  if (count > 0) {
    document.getElementById('action-section').style.display = 'block';
    document.getElementById('action-section').style.animation = 'slideUp 0.3s ease-out';
    // Update select all checkbox
    var total_checked = document.querySelectorAll('.kar-checkbox:checked').length;
    var total_checkboxes = document.querySelectorAll('.kar-checkbox').length;
    document.getElementById('select-all').checked = total_checked === total_checkboxes && total_checkboxes > 0;
  } else {
    document.getElementById('action-section').style.display = 'none';
  }
  
  console.log('Selection updated: ' + count + ' karyawan dipilih');
}

// Toggle select all
function toggleSelectAll(checkbox) {
  document.querySelectorAll('.kar-checkbox').forEach(cb => {
    cb.checked = checkbox.checked;
  });
  updateSelection();
}

// Clear selection
function clearSelection() {
  selectedKaryawan.clear();
  document.querySelectorAll('.kar-checkbox').forEach(cb => cb.checked = false);
  document.getElementById('select-all').checked = false;
  document.getElementById('action-section').style.display = 'none';
  document.getElementById('selected-count').textContent = '0';
}

// Preview pindah
function previewPindah() {
  var bagian_tujuan = document.getElementById('bagian_tujuan').value;
  var subbag_tujuan = document.getElementById('subbag_tujuan').value;
  var tanggal_efektif = document.getElementById('tanggal_efektif').value;
  
  if (!bagian_tujuan) {
    alert('Pilih bagian tujuan');
    return;
  }
  
  if (!tanggal_efektif) {
    alert('Pilih tanggal efektif pindah');
    return;
  }
  
  if (selectedKaryawan.size === 0) {
    alert('Pilih minimal 1 karyawan');
    return;
  }
  
  // Build preview
  var bagian_obj = <?php echo json_encode($bagian); ?>.find(b => b.recid_bag == bagian_tujuan);
  var subbag_obj = subbag_tujuan ? subBagianData.find(sb => sb.recid_subbag == subbag_tujuan) : null;
  
  var subbag_text = subbag_obj ? subbag_obj.sub_bag : '(Tidak berubah)';
  
  var info = 'Karyawan: ' + selectedKaryawan.size + ' orang<br>' +
             'Ke Bagian: <strong>' + bagian_obj.indeks_hr + '</strong><br>' +
             'Sub Bagian: <strong>' + subbag_text + '</strong><br>' +
             'Tanggal Efektif: <strong>' + tanggal_efektif + '</strong>';
  
  document.getElementById('preview-info').innerHTML = info;
  
  // Build preview table
  var previewHtml = '';
  selectedKaryawan.forEach(recid => {
    var kar = allKaryawan.find(k => k.recid_karyawan == recid);
    previewHtml += '<tr>';
    previewHtml += '<td>' + kar.nik + '</td>';
    previewHtml += '<td>' + kar.nama_karyawan + '</td>';
    previewHtml += '<td>' + (kar.bagian || '-') + ' / ' + (kar.sub_bagian || '-') + '</td>';
    previewHtml += '<td>' + bagian_obj.indeks_hr + ' / ' + subbag_text + '</td>';
    previewHtml += '</tr>';
  });
  
  document.getElementById('preview-table').innerHTML = previewHtml;
  $('#previewModal').modal('show');
}

// Confirm dan proses pindah
function confirmPindah() {
  processPindah();
}

// Process pindah
function processPindah() {
  var bagian_tujuan = document.getElementById('bagian_tujuan').value;
  var subbag_tujuan = document.getElementById('subbag_tujuan').value;
  var tanggal_efektif = document.getElementById('tanggal_efektif').value;
  var catatan = document.getElementById('catatan').value;
  
  if (selectedKaryawan.size === 0) {
    alert('Pilih minimal 1 karyawan');
    return;
  }
  
  // Convert Set to Array
  var recid_arr = Array.from(selectedKaryawan);
  
  $.ajax({
    url: '<?php echo base_url("Karyawan/process_pindah_bagian"); ?>',
    type: 'POST',
    dataType: 'JSON',
    data: {
      recid_karyawan: recid_arr,
      recid_bagian_baru: bagian_tujuan,
      recid_subbag_baru: subbag_tujuan,
      tanggal_efektif: tanggal_efektif,
      catatan: catatan
    },
    success: function(response) {
      $('#previewModal').modal('hide');
      
      if (response.success) {
        alert('✓ Berhasil! ' + response.message);
        // Reset
        clearSelection();
        fetchKaryawan();
        document.getElementById('bagian_tujuan').value = '';
        document.getElementById('subbag_tujuan').value = '';
        document.getElementById('catatan').value = '';
      } else {
        alert('✗ Gagal! ' + response.message);
      }
    },
    error: function() {
      alert('✗ Error! Terjadi kesalahan saat memproses');
    }
  });
}
</script>
<!-- /page content -->

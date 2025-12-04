<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>History Absensi Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-6">
                <form id="form_filter">
                  <div class="input-group">
                    <select id="select_bulan" name="bulan" class="form-control" style="display: inline-block; width: auto; margin-right: 5px;">
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
                    <input type="number" id="select_tahun" name="tahun" class="form-control" value="<?php echo $tahun?>" min="2020" style="display: inline-block; width: auto; margin-right: 5px;">
                    <button class="btn btn-primary" id="btn_fetch" type="button" style="display: inline-block;">
                      <i class="fa fa-search"></i> Tampilkan
                    </button>
                  </div>
                </form>
              </div>
              <div class="col-md-6 text-right">
                <a id="btn_export" href="javascript:void(0)" class="btn btn-success btn-sm" style="display: none;">
                  <i class="fa fa-download"></i> Export Excel
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">
            <!-- Alert Info -->
            <div id="alert_info" class="alert alert-info" style="display: none;">
              <strong>Periode:</strong> <span id="periode_text"></span> | 
              <strong>Total Records:</strong> <span id="total_records">0</span>
            </div>

            <!-- Loading -->
            <div id="loading" style="display: none; text-align: center; padding: 20px;">
              <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i>
              <p>Memproses data...</p>
            </div>

            <!-- Content Table -->
            <div id="table_container" style="display: none;">
              <div class="table-responsive">
                <table id="tbl_history" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th style="text-align: center; width: 5%;">No</th>
                      <th style="text-align: center; width: 12%;">NIK</th>
                      <th style="text-align: center; width: 25%;">Nama Karyawan</th>
                      <th style="text-align: center; width: 12%;">Tanggal</th>
                      <th style="text-align: center; width: 10%;">Jam</th>
                      <th style="text-align: center; width: 10%;">AM/PM</th>
                      <th style="text-align: center; width: 10%;">State</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_history">
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" style="text-align: center; padding: 40px;">
              <i class="fa fa-inbox" style="font-size: 48px; color: #ccc;"></i>
              <p style="margin-top: 20px; color: #999;">Pilih bulan dan tahun untuk menampilkan data history absensi</p>
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
  var $loadingDiv = $('#loading');
  var $emptyState = $('#empty_state');
  var $tableContainer = $('#table_container');
  var $alertInfo = $('#alert_info');
  var $btnExport = $('#btn_export');
  var $btnFetch = $('#btn_fetch');

  // Handle Fetch button click
  $btnFetch.on('click', function() {
    var bulan = $('#select_bulan').val();
    var tahun = $('#select_tahun').val();

    // Show loading
    $loadingDiv.show();
    $emptyState.hide();
    $tableContainer.hide();
    $alertInfo.hide();
    $btnExport.hide();

    // AJAX fetch data
    $.ajax({
      url: '<?php echo base_url("rekap/get_history_by_month")?>',
      type: 'GET',
      dataType: 'JSON',
      data: {
        bulan: bulan,
        tahun: tahun
      },
      success: function(response) {
        $loadingDiv.hide();

        if (response.success) {
          var data = response.data;
          var html = '';
          var no = 1;

          // Build table rows
          $.each(data, function(index, item) {
            html += '<tr>';
            html += '<td style="text-align: center;">' + no + '</td>';
            // NIK: API mengembalikan field 'nik' (NIK karyawan). Fallback ke nik_karyawan/pin jika ada versi lama.
            html += '<td style="text-align: center;">' + (item.nik || item.nik_karyawan || item.pin || '-') + '</td>';
            html += '<td>' + (item.nama_karyawan || '-') + '</td>';
            html += '<td style="text-align: center;">' + (item.tanggal || '-') + '</td>';
            html += '<td style="text-align: center;">' + (item.jam || '-') + '</td>';
            html += '<td style="text-align: center;"><span class="label label-default">' + (item.am_pm || '-') + '</span></td>';
            html += '<td style="text-align: center;"><span class="label ' + getLabelClass(item.state) + '">' + (item.state || '-') + '</span></td>';
            html += '</tr>';
            no++;
          });

          $('#tbody_history').html(html);

          // Show info
          $('#periode_text').text(response.periode);
          $('#total_records').text(response.total);
          $alertInfo.show();

          // Show table
          $tableContainer.show();

          // Show export button
          $btnExport.show();
          $btnExport.attr('href', '<?php echo base_url("rekap/export_history_bulanan?bulan=") ?>' + bulan + '&tahun=' + tahun);

          // Initialize DataTable if not already
          if ($.fn.DataTable.isDataTable('#tbl_history')) {
            $('#tbl_history').DataTable().destroy();
          }

          var table = $('#tbl_history').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 50,
            "language": {
              "url": "<?php echo base_url('assets/dataTables/Indonesian.json')?>"
            }
          });

          // Update Export link to include current search text (export per nama)
          $btnExport.off('click').on('click', function(e) {
            var searchText = table.search() || '';
            var href = '<?php echo base_url("rekap/export_history_bulanan?bulan=") ?>' + encodeURIComponent(bulan) + '&tahun=' + encodeURIComponent(tahun);
            if (searchText) {
              href += '&q=' + encodeURIComponent(searchText);
            }
            $(this).attr('href', href);
          });
        } else {
          $emptyState.html('<i class="fa fa-warning" style="font-size: 48px; color: #f0ad4e;"></i><p style="margin-top: 20px; color: #999;">Tidak ada data untuk periode ini</p>');
          $emptyState.show();
        }
      },
      error: function(xhr, status, error) {
        $loadingDiv.hide();
        console.error('XHR:', xhr);
        console.error('Status:', status);
        console.error('Error:', error);
        console.error('Response:', xhr.responseText);
        
        var errorMsg = 'Error: ' + error;
        if (xhr.responseText) {
          try {
            var errorObj = JSON.parse(xhr.responseText);
            errorMsg = 'Error: ' + (errorObj.message || error);
          } catch(e) {
            errorMsg = 'Server Error: ' + xhr.status + ' ' + xhr.statusText;
          }
        }
        
        alert(errorMsg);
      }
    });
  });

  // Helper function to get label class
  function getLabelClass(state) {
    if (state === 'Check-in') {
      return 'label-info';
    } else if (state === 'Check-out') {
      return 'label-success';
    }
    return 'label-default';
  }
});
</script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Setup Bagian (Bulk)</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="row">
              <div class="col-md-8">
                <div class="form-inline">
                  <label for="bagian_target">Pilih Bagian Tujuan:</label>
                  <select id="bagian_target" class="form-control" style="min-width:280px;margin-left:8px;">
                    <option value="">-- Pilih Bagian --</option>
                    <?php foreach ($bagian_list as $b): ?>
                      <option value="<?php echo $b->recid_bag; ?>"><?php echo $b->nama_bag; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="button" id="btn_proses" class="btn btn-success" disabled style="margin-left:10px;">
                    <i class="fa fa-check"></i> Proses Sekarang
                  </button>
                  <button type="button" id="btn_reset" class="btn btn-default" style="margin-left:5px;">
                    <i class="fa fa-refresh"></i> Reset Seleksi
                  </button>
                </div>
              </div>
              <div class="col-md-4 text-right">
                <div style="padding-top:6px;">
                  <span class="label label-info" id="lbl_selected">Terpilih: 0 karyawan</span>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <div class="table-responsive">
              <table id="tbl_karyawan" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th style="width:40px; text-align:center;">
                      <input type="checkbox" id="check_all" />
                    </th>
                    <th style="width:120px; text-align:center;">NIK</th>
                    <th>Nama Karyawan</th>
                    <th style="width:240px;">Bagian Saat Ini</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal konfirmasi -->
<div id="modal_confirm" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfirmasi Proses</h4>
      </div>
      <div class="modal-body" style="max-height:60vh; overflow:auto;">
        <p id="confirm_text" style="margin-bottom:10px;">Anda yakin akan memindahkan karyawan terpilih?</p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="preview_table">
            <thead>
              <tr>
                <th style="width:50px; text-align:center;">No</th>
                <th style="width:120px; text-align:center;">NIK</th>
                <th>Nama Karyawan</th>
                <th style="width:220px;">Bagian Sebelumnya</th>
                <th style="width:220px;">Bagian Tujuan</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" id="btn_confirm_process" class="btn btn-success">Ya, Proses</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function() {
  var selected = new Set();
  var table = null;
  var allData = [];

  function updateSelectedLabel() {
    $('#lbl_selected').text('Terpilih: ' + selected.size + ' karyawan');
    $('#btn_proses').prop('disabled', !(selected.size > 0 && $('#bagian_target').val()));
  }

  function renderCheckbox(recid) {
    var checked = selected.has(String(recid)) ? 'checked' : '';
    return '<input type="checkbox" class="row-check" data-id="'+ recid +'" '+ checked +'/>';
  }

  function drawTable(data) {
    allData = data || [];
    if (table) {
      table.clear().rows.add(data).draw(false);
      return;
    }

    table = $('#tbl_karyawan').DataTable({
      data: data,
      columns: [
        { data: 'recid_karyawan', render: function(val){ return renderCheckbox(val); }, orderable: false, searchable: false, className: 'text-center' },
        { data: 'nik', className: 'text-center' },
        { data: 'nama_karyawan' },
        { data: 'nama_bag', defaultContent: '-' }
      ],
      pageLength: 50,
      language: { url: '<?php echo base_url('assets/dataTables/Indonesian.json'); ?>' },
      order: [[2, 'asc']]
    });

    // Persist checkbox states on redraw
    $('#tbl_karyawan').on('draw.dt', function() {
      $('#tbl_karyawan .row-check').each(function(){
        var id = String($(this).data('id'));
        $(this).prop('checked', selected.has(id));
      });
      // Update master checkbox
      var pageAllChecked = $('#tbl_karyawan .row-check').length > 0 && $('#tbl_karyawan .row-check').filter(':checked').length === $('#tbl_karyawan .row-check').length;
      $('#check_all').prop('checked', pageAllChecked);
    });

    // Row checkbox
    $('#tbl_karyawan tbody').on('change', '.row-check', function(){
      var id = String($(this).data('id'));
      if (this.checked) selected.add(id); else selected.delete(id);
      updateSelectedLabel();
    });

    // Master checkbox (per halaman)
    $('#check_all').on('change', function(){
      var checked = this.checked;
      $('#tbl_karyawan .row-check').each(function(){
        $(this).prop('checked', checked).trigger('change');
      });
    });

    // Search ties to DataTables built-in; selection persists via Set
    updateSelectedLabel();
  }

  function loadData(q) {
    $.ajax({
      url: '<?php echo base_url('karyawan/get_karyawan_aktif'); ?>',
      data: { q: q || '' },
      dataType: 'json',
      success: function(res){
        if (res.success) {
          drawTable(res.data);
        } else {
          alert('Gagal memuat data');
        }
      },
      error: function(){ alert('Gagal memuat data'); }
    });
  }

  // Initial
  loadData('');

  // Enable/disable proses button when target changes
  $('#bagian_target').on('change', updateSelectedLabel);

  // Reset selection
  $('#btn_reset').on('click', function(){ selected.clear(); updateSelectedLabel(); table.draw(false); });

  // Proses sekarang -> modal confirm
  $('#btn_proses').on('click', function(){
    var bagText = $('#bagian_target option:selected').text();
    $('#confirm_text').text('Pindahkan ' + selected.size + ' karyawan ke bagian: ' + bagText + ' ?');

    // Build preview rows from allData according to selected ids
    var target = $('#bagian_target').val();
    var rows = [];
    var ids = Array.from(selected);
    var map = {};
    for (var i = 0; i < allData.length; i++) {
      map[String(allData[i].recid_karyawan)] = allData[i];
    }
    for (var j = 0; j < ids.length; j++) {
      var id = String(ids[j]);
      if (map[id]) rows.push(map[id]);
    }
    // Sort by nama
    rows.sort(function(a,b){ return (a.nama_karyawan || '').localeCompare(b.nama_karyawan || ''); });

    var html = '';
    for (var k = 0; k < rows.length; k++) {
      var r = rows[k];
      html += '<tr>'+
        '<td style="text-align:center;">'+ (k+1) +'</td>'+
        '<td style="text-align:center;">'+ (r.nik || '-') +'</td>'+
        '<td>'+ (r.nama_karyawan || '-') +'</td>'+
        '<td>'+ (r.nama_bag || '-') +'</td>'+
        '<td>'+ bagText +'</td>'+
      '</tr>';
    }
    $('#preview_table tbody').html(html);
    $('#modal_confirm').modal('show');
  });

  // Confirm process
  $('#btn_confirm_process').on('click', function(){
    var ids = Array.from(selected);
    var recid_bag = $('#bagian_target').val();
    if (!ids.length || !recid_bag) return;

    $(this).prop('disabled', true);

    $.ajax({
      url: '<?php echo base_url('karyawan/process_setup_bagian_bulk'); ?>',
      method: 'POST',
      dataType: 'json',
      data: { ids: ids, recid_bag: recid_bag },
      success: function(res){
        $('#btn_confirm_process').prop('disabled', false);
        if (res.success) {
          // After update, reload table and clear selection
          selected.clear();
          updateSelectedLabel();
          $('#modal_confirm').modal('hide');
          loadData('');
          new PNotify({ title: 'Sukses', text: res.message || 'Perubahan berhasil', type: 'success', styling: 'bootstrap3' });
        } else {
          new PNotify({ title: 'Gagal', text: res.message || 'Proses gagal', type: 'error', styling: 'bootstrap3' });
        }
      },
      error: function(xhr){
        $('#btn_confirm_process').prop('disabled', false);
        new PNotify({ title: 'Gagal', text: 'Terjadi kesalahan server', type: 'error', styling: 'bootstrap3' });
      }
    });
  });
});
</script>

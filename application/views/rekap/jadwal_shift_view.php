<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Jadwal Shift (Absen Finger)</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <style>
              .filter-bar .form-control { height: 34px; }
              .filter-bar .control-label { margin-right: 8px; font-weight: 600; }
              .filter-bar .btn { margin-left: 4px; }
              @media (max-width: 991px) { .filter-bar .form-control, .filter-bar .btn { margin-top: 6px; } }
            </style>
            <div class="row">
              <div class="col-md-12">
                <form id="form_filter" class="form-inline filter-bar" onsubmit="return false;">
                  <label class="control-label">Tanggal</label>
                  <input type="date" id="tanggal" name="tanggal" class="form-control input-sm" value="<?php echo $default_tanggal ?>" required>

                  <label class="control-label">Bagian</label>
                  <select id="bagian" class="form-control input-sm">
                    <option value="">Semua</option>
                    <?php foreach ($bagian_list as $b): ?>
                      <option value="<?php echo $b->recid_bag ?>"><?php echo $b->nama_bag ?></option>
                    <?php endforeach; ?>
                  </select>

                  <label class="control-label">Shift</label>
                  <div class="input-group">
                    <select id="shift_id" class="form-control input-sm" required>
                      <option value="">- Pilih Shift -</option>
                    </select>
                    <span class="input-group-btn">
                      <button type="button" id="btn_new_shift" class="btn btn-default btn-sm" title="Buat Shift Baru"><i class="fa fa-plus"></i></button>
                    </span>
                  </div>

                  <button type="button" id="btn_fetch" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Tampilkan</button>
                </form>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <div id="status_bar"></div>
            <div class="row" style="margin-bottom:10px;">
              <div class="col-md-12">
                <label class="control-label">Pilih Karyawan</label>
                <div class="table-responsive">
                  <table id="tbl_karyawan_pick" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                      <tr>
                        <th style="width:40px; text-align:center;"><input type="checkbox" id="check_all" /></th>
                        <th style="width:120px; text-align:center;">NIK</th>
                        <th>Nama Karyawan</th>
                        <th style="width:240px;">Bagian</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
                <div class="clearfix" style="margin-top:4px;">
                  <small class="text-muted pull-left">Gunakan filter Bagian untuk mempersempit daftar. Gunakan kotak pencarian tabel untuk mencari cepat.</small>
                  <small class="text-info pull-right">Dipilih: <span id="sel_count">0</span></small>
                </div>
                <div style="margin-top:8px;">
                  <button id="btn_simpan" class="btn btn-success btn-sm"><i class="fa fa-save"></i> <span class="btn-text">Simpan Jadwal</span></button>
                </div>
              </div>
            </div>

            <h4>Jadwal pada tanggal <span id="lbl_tanggal"><?php echo $default_tanggal ?></span></h4>
            <div class="table-responsive">
              <table id="tbl_jadwal" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th style="text-align:center;">NIK</th>
                    <th style="text-align:center;">Nama</th>
                    <th style="text-align:center;">Bagian</th>
                    <th style="text-align:center;">Shift</th>
                    <th style="text-align:center;">Jam In</th>
                    <th style="text-align:center;">Jam Out</th>
                    <th style="text-align:center;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="tbody_jadwal"><tr><td colspan="7" class="text-center">Memuat...</td></tr></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
$(document).ready(function(){
  function showStatus(message, type){
    var cls = type==='error' ? 'alert-danger' : (type==='info' ? 'alert-info' : 'alert-success');
    var $el = $('<div class="alert '+cls+'" role="alert" style="padding:8px 12px; margin-bottom:10px;">'+message+'</div>');
    $('#status_bar').html($el);
    setTimeout(function(){ $el.fadeOut(300, function(){ $(this).remove(); }); }, 3000);
  }

  function setBtnLoading($btn, isLoading, textLoading){
    var $icon = $btn.find('i.fa');
    var $txt = $btn.find('.btn-text');
    if(isLoading){
      $btn.prop('disabled', true);
      $icon.removeClass('fa-save fa-search').addClass('fa-spinner fa-spin');
      if ($txt.length && textLoading) $txt.text(textLoading);
    } else {
      $btn.prop('disabled', false);
      if ($btn.is('#btn_simpan')) { $icon.removeClass('fa-spinner fa-spin').addClass('fa-save'); $txt.text('Simpan Jadwal'); }
      if ($btn.is('#btn_fetch')) { $icon.removeClass('fa-spinner fa-spin').addClass('fa-search'); }
    }
  }

  var pickerTable = null;
  var selectedIds = new Set();

  function updateSelectedLabel(){ $('#sel_count').text(selectedIds.size); }

  function renderPickCheckbox(recid){
    var checked = selectedIds.has(String(recid)) ? 'checked' : '';
    return '<input type="checkbox" class="row-pick" data-id="'+ recid +'" '+checked+' />';
  }

  function drawPickerTable(data){
    if (pickerTable){ pickerTable.clear().rows.add(data).draw(false); return; }
    pickerTable = $('#tbl_karyawan_pick').DataTable({
      data: data,
      columns: [
        { data: 'recid_karyawan', render: function(v){ return renderPickCheckbox(v); }, orderable:false, searchable:false, className:'text-center' },
        { data: 'nik', className:'text-center' },
        { data: 'nama_karyawan' },
        { data: 'nama_bag', defaultContent:'-' }
      ],
      pageLength: 3,
      language: { url: '<?php echo base_url('assets/dataTables/Indonesian.json'); ?>' },
      order: [[2,'asc']]
    });

    // Persist selection on redraw
    $('#tbl_karyawan_pick').on('draw.dt', function(){
      $('#tbl_karyawan_pick .row-pick').each(function(){
        var id = String($(this).data('id'));
        $(this).prop('checked', selectedIds.has(id));
      });
      var allChecked = $('#tbl_karyawan_pick .row-pick').length>0 && $('#tbl_karyawan_pick .row-pick:checked').length === $('#tbl_karyawan_pick .row-pick').length;
      $('#check_all').prop('checked', allChecked);
    });

    // Row pick
    $('#tbl_karyawan_pick tbody').on('change', '.row-pick', function(){
      var id = String($(this).data('id'));
      if (this.checked) selectedIds.add(id); else selectedIds.delete(id);
      updateSelectedLabel();
    });

    // Master checkbox per halaman
    $('#check_all').on('change', function(){
      var checked = this.checked;
      $('#tbl_karyawan_pick .row-pick').each(function(){
        $(this).prop('checked', checked).trigger('change');
      });
    });

    updateSelectedLabel();
  }

  function loadKaryawan(){
    var bagian = $('#bagian').val();
    $.ajax({ url: '<?php echo base_url('rekap/get_karyawan_aktif')?>', type:'GET', dataType:'json', data:{ bagian: bagian } })
    .done(function(res){ if(res.success){ drawPickerTable(res.data||[]); } });
  }

  function loadShiftOptions(selectedId){
    $.ajax({ url: '<?php echo base_url('rekap/get_shift_list')?>', type: 'GET', dataType:'json' })
    .done(function(res){
      if(res.success){
        var html = '<option value="">- Pilih Shift -</option>';
        (res.data||[]).forEach(function(s){
          html += '<option value="'+s.recid_jenisabsen+'" data-in="'+(s.jam_in||'')+'" data-out="'+(s.jam_out||'')+'">'+
                  s.keterangan+' ('+(s.jam_in||'-')+' - '+(s.jam_out||'-')+')'+
                  '</option>';
        });
        $('#shift_id').html(html);
        if (selectedId) { $('#shift_id').val(String(selectedId)); }
      }
    });
  }

  function loadJadwal(){
    var tgl = $('#tanggal').val();
    var bagian = $('#bagian').val();
    $('#lbl_tanggal').text(tgl);
    $('#tbody_jadwal').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>');
    $.ajax({
      url: '<?php echo base_url('rekap/get_jadwal_shift')?>',
      type: 'GET', dataType: 'json', data: { tanggal: tgl, bagian: bagian }
    }).done(function(res){
      if(res.success){
        var rows='';
        (res.data||[]).forEach(function(r){
          rows += '<tr>'+
                  '<td>'+ (r.nik||'-') +'</td>'+
                  '<td>'+ (r.nama_karyawan||'-') +'</td>'+
                  '<td>'+ (r.nama_bag||'-') +'</td>'+
                  '<td>'+ (r.nama_shift||'-') +'</td>'+
                  '<td>'+ (r.jam_in||'-') +'</td>'+
                  '<td>'+ (r.jam_out||'-') +'</td>'+
                  '<td class="text-center">'+
                    '<button class="btn btn-xs btn-danger btn-hapus" data-id="'+r.recid_jadwal+'"><i class="fa fa-trash"></i> <span class="btn-text">Hapus</span></button>'+
                  '</td>'+
                '</tr>';
        });
        $('#tbody_jadwal').html(rows || '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
        if ($.fn.DataTable.isDataTable('#tbl_jadwal')) { $('#tbl_jadwal').DataTable().destroy(); }
        $('#tbl_jadwal').DataTable({
          paging:true, pageLength:50, searching:true, ordering:true, info:true,
          language: { url: "<?php echo base_url('assets/dataTables/Indonesian.json')?>" }
        });
        showStatus('Jadwal diperbarui', 'info');
      } else { alert(res.message||'Gagal memuat jadwal'); }
    }).fail(function(xhr){ alert('Error: '+(xhr.responseText||xhr.statusText)); });
  }

  $('#bagian').on('change', function(){ loadKaryawan(); loadJadwal(); });
  $('#btn_fetch').on('click', function(){ setBtnLoading($('#btn_fetch'), true); loadJadwal(); setBtnLoading($('#btn_fetch'), false); });
  $('#tanggal').on('change', function(){ loadJadwal(); });

  $('#btn_simpan').on('click', function(){
    var tgl = $('#tanggal').val();
    var shift_id = $('#shift_id').val();
  var ids = Array.from(selectedIds);
    if(!tgl || !shift_id || ids.length===0){ alert('Tanggal, Shift, dan Karyawan wajib dipilih'); return; }
    setBtnLoading($('#btn_simpan'), true, 'Menyimpan...');
    $.ajax({
      url: '<?php echo base_url('rekap/simpan_jadwal_shift')?>',
      type: 'POST', dataType: 'json', data: { tanggal: tgl, shift_id: shift_id, 'karyawan_ids[]': ids }
    }).done(function(res){
      if(res.success){
        showStatus('Berhasil simpan jadwal: '+(res.created||0)+' baru, '+(res.updated||0)+' diupdate', 'success');
        loadJadwal();
      } else { alert(res.message||'Gagal menyimpan'); }
    }).fail(function(xhr){ alert('Error: '+(xhr.responseText||xhr.statusText)); })
    .always(function(){ setBtnLoading($('#btn_simpan'), false); });
  });

  $(document).on('click', '.btn-hapus', function(){
    var id = $(this).data('id');
    var $btn = $(this);
    if(!confirm('Hapus jadwal ini?')) return;
    $btn.prop('disabled', true);
    var $icon = $btn.find('i.fa'); var $txt = $btn.find('.btn-text');
    $icon.removeClass('fa-trash').addClass('fa-spinner fa-spin'); $txt.text('Menghapus...');
    $.ajax({ url: '<?php echo base_url('rekap/hapus_jadwal_shift')?>', type: 'POST', dataType:'json', data:{ id:id } })
    .done(function(res){ if(res.success){ showStatus('Jadwal dihapus','success'); loadJadwal(); } else { alert(res.message||'Gagal hapus'); } })
    .fail(function(xhr){ alert('Error: '+(xhr.responseText||xhr.statusText)); })
    .always(function(){ $btn.prop('disabled', false); $icon.removeClass('fa-spinner fa-spin').addClass('fa-trash'); $txt.text('Hapus'); });
  });

  // initial
  loadKaryawan();
  loadShiftOptions();
  loadJadwal();

  // New Shift Modal
  $('#btn_new_shift').on('click', function(){
    $('#modal_new_shift').modal('show');
  });

  $('#form_new_shift').on('submit', function(e){
    e.preventDefault();
    var title = $('#ns_title').val().trim();
    var jin = $('#ns_in').val();
    var jout = $('#ns_out').val();
    if(!title || !jin || !jout){ alert('Lengkapi Judul, Jam In, dan Jam Out'); return; }
    $.ajax({
      url: '<?php echo base_url('rekap/create_custom_shift')?>',
      type: 'POST', dataType: 'json', data: { title: title, jam_in: jin, jam_out: jout }
    }).done(function(res){
      if(res.success){
        var newId = res.id || res.id === 0 ? res.id : (res.exists ? res.id : null);
        $('#modal_new_shift').modal('hide');
        $('#form_new_shift')[0].reset();
        loadShiftOptions(newId);
        showStatus('Shift baru tersimpan','success');
      } else {
        alert(res.message||'Gagal membuat shift');
      }
    }).fail(function(xhr){ alert('Error: '+(xhr.responseText||xhr.statusText)); });
  });
});
</script>

<!-- Modal: New Shift -->
<div class="modal fade" id="modal_new_shift" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form_new_shift">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Buat Shift Baru</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Judul Shift</label>
            <input type="text" id="ns_title" class="form-control" placeholder="Contoh: Shift Siang 10-16" required>
          </div>
          <div class="form-group">
            <label>Jam In</label>
            <input type="time" id="ns_in" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Jam Out</label>
            <input type="time" id="ns_out" class="form-control" required>
          </div>
          <p class="text-muted">Catatan: Shift overnight (melewati tengah malam) belum didukung.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

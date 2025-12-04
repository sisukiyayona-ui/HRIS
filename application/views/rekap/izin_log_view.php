<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Log Izin (Finger)</h3>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <style>
              /* Tidy filter bar layout */
              .filter-bar .form-control { height: 34px; }
              .filter-bar .control-label { margin-right: 8px; font-weight: 600; }
              .filter-bar .sep { margin: 0 8px; color: #777; }
              .filter-bar .filter-q { max-width: 360px; margin-left: 8px; margin-right: 8px; }
              .filter-bar .btn { margin-left: 4px; }
              @media (max-width: 991px) {
                .filter-bar .form-control, .filter-bar .btn { margin-top: 6px; }
                .filter-bar .filter-q { width: 100%; max-width: none; display: block; margin: 6px 0; }
              }
            </style>
            <div class="row">
              <div class="col-md-8">
                <form id="form_filter" class="form-inline filter-bar" onsubmit="return false;">
                  <label class="control-label">Periode</label>
                  <input type="date" id="start_date" name="start_date" class="form-control input-sm" value="<?php echo $start_date ?>" required>
                  <span class="sep">s/d</span>
                  <input type="date" id="end_date" name="end_date" class="form-control input-sm" value="<?php echo $end_date ?>" required>
                  <input type="text" id="q" name="q" class="form-control input-sm filter-q" placeholder="Cari nama / NIK / jenis / keterangan … (Enter)">
                  <button type="button" id="btn_fetch" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Tampilkan</button>
                </form>
              </div>
              <div class="col-md-4 text-right">
                <a id="btn_export" href="javascript:void(0)" class="btn btn-success btn-sm" style="display:none;">
                  <i class="fa fa-download"></i> Export (CSV)
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <div id="alert_info" class="alert alert-info" style="display:none;">
              <strong>Periode:</strong> <span id="periode_text"></span> |
              <strong>Total:</strong> <span id="total_records">0</span> data
            </div>

            <div id="loading" style="display:none; text-align:center; padding:20px;">
              <i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>
              <p>Memuat data...</p>
            </div>

            <div id="table_container" style="display:none;">
              <div class="table-responsive">
                <table id="tbl_izin" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th style="text-align:center; width:5%;">No</th>
                      <th style="text-align:center; width:10%;">Tanggal</th>
                      <th style="text-align:center; width:12%;">NIK</th>
                      <th style="text-align:center; width:25%;">Nama</th>
                      <th style="text-align:center; width:18%;">Bagian</th>
                      <th style="text-align:center; width:10%;">Jenis</th>
                      <th style="text-align:center;">Keterangan</th>
                      <th style="text-align:center; width:15%;">Validated</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_izin"></tbody>
                </table>
              </div>
            </div>

            <div id="empty_state" style="text-align:center; padding:40px;">
              <i class="fa fa-inbox" style="font-size:48px; color:#ccc;"></i>
              <p style="margin-top:20px; color:#999;">Pilih periode lalu klik Tampilkan</p>
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
  var $loading = $('#loading');
  var $empty = $('#empty_state');
  var $tableWrap = $('#table_container');
  var $alert = $('#alert_info');

  function badgeJenis(jenis){
    var cls = 'label-default';
    if(jenis === 'SAKIT') cls = 'label-warning';
    else if(jenis === 'MANGKIR' || jenis === 'TANPA KETERANGAN') cls = 'label-danger';
    else cls = 'label-info';
    return '<span class="label ' + cls + '">' + jenis + '</span>';
  }

  $('#btn_fetch').on('click', function(){
    var start = $('#start_date').val();
    var end = $('#end_date').val();
    var q = $('#q').val();

    $loading.show();
    $empty.hide();
    $tableWrap.hide();
    $alert.hide();

    $.ajax({
      url: '<?php echo base_url("rekap/get_izin_log")?>',
      type: 'GET',
      dataType: 'json',
      data: { start_date: start, end_date: end, q: q }
    }).done(function(res){
      $loading.hide();
      if(res.success){
        var html = '';
        var no = 1;
        (res.data||[]).forEach(function(r){
          var tgl = r.tgl_mulai ? r.tgl_mulai : '-';
          var ket = r.keterangan ? r.keterangan : '';
          var validBy = r.validated_nama ? r.validated_nama : (r.validated_by || '-');
          var validAt = r.validated_date ? r.validated_date : '';
          var validatedInfo = (validBy && validAt) ? (validBy + '<br><small>' + validAt + '</small>') : '-';
          html += '<tr>'+
                  '<td style="text-align:center;">'+ (no++) +'</td>'+
                  '<td style="text-align:center;">'+ tgl +'</td>'+
                  '<td style="text-align:center;">'+ (r.nik||'-') +'</td>'+
                  '<td>'+ (r.nama_karyawan||'-') +'</td>'+
                  '<td>'+ (r.nama_bagian||'-') +'</td>'+
                  '<td style="text-align:center;">'+ badgeJenis(r.jenis||'-') +'</td>'+
                  '<td>'+ ket +'</td>'+
                  '<td style="text-align:center;">'+ validatedInfo +'</td>'+
                  '</tr>';
        });
        $('#tbody_izin').html(html);

        $('#periode_text').text(res.periode||'');
        $('#total_records').text(res.total||0);
        $alert.show();

        $tableWrap.show();
        if ($.fn.DataTable.isDataTable('#tbl_izin')) {
          $('#tbl_izin').DataTable().destroy();
        }
        $('#tbl_izin').DataTable({
          paging: true,
          lengthChange: true,
          searching: false, // hide built-in search box — we use the top filter
          ordering: true,
          info: true,
          autoWidth: false,
          responsive: true,
          pageLength: 25,
          dom: 'lrtip', // remove the default filter input (f)
          language: {
            url: "<?php echo base_url('assets/dataTables/Indonesian.json')?>"
          }
        });
      } else {
        $empty.html('<i class="fa fa-warning" style="font-size:48px; color:#f0ad4e;"></i><p style="margin-top:20px; color:#999;">'+ (res.message||'Tidak ada data') +'</p>').show();
      }
    }).fail(function(xhr){
      $loading.hide();
      alert('Gagal memuat data: ' + (xhr.responseText || xhr.statusText));
    });
  });

  // Submit on Enter from any filter field
  $('#q, #start_date, #end_date').on('keypress', function(e){
    if(e.which === 13){
      $('#btn_fetch').click();
    }
  });
});
</script>

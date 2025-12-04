<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Rekap Statistik Per Bagian</h3>
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
              tfoot tr th { font-weight: 700; background: #f7f7f7; }
              .badge-mini { padding: 3px 6px; font-size: 11px; }
            </style>
            <div class="row">
              <div class="col-md-8">
                <form id="form_filter" class="form-inline filter-bar" onsubmit="return false;">
                  <label class="control-label">Tanggal</label>
                  <input type="date" id="tanggal" name="tanggal" class="form-control input-sm" value="<?php echo $tanggal ?>" required>
                  <button type="button" id="btn_fetch" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Tampilkan</button>
                </form>
              </div>
              <div class="col-md-4 text-right">
                <span id="periode_label" class="label label-info" style="display:none;"></span>
                <button type="button" id="btn_export" class="btn btn-success btn-sm" title="Export ke Excel (XLSX)" style="margin-left:8px;"><i class="fa fa-file-excel-o"></i> Export XLSX</button>
                <button type="button" id="btn_debug" class="btn btn-default btn-sm" title="Lihat Debug JSON" style="margin-left:8px;"><i class="fa fa-bug"></i> Debug</button>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <div id="loading" style="display:none; text-align:center; padding:20px;">
              <i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>
              <p>Memuat data...</p>
            </div>

            <div class="table-responsive">
              <table id="tbl_stat_bag" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th style="text-align:center;">Bagian</th>
                    <th style="text-align:center;">Total</th>
                    <th style="text-align:center;">Hadir</th>
                    <th style="text-align:center;">S (Sakit)</th>
                    <th style="text-align:center;">I (Izin)</th>
                    <th style="text-align:center;">M (Mangkir)</th>
                    <th style="text-align:center;">Tidak Hadir</th>
                    <th style="text-align:center;">% Tidak Hadir</th>
                  </tr>
                </thead>
                <tbody id="tbody_stat"></tbody>
                <tfoot>
                  <tr>
                    <th style="text-align:right;">TOTAL</th>
                    <th id="ft_total" style="text-align:center;">0</th>
                    <th id="ft_hadir" style="text-align:center;">0</th>
                    <th id="ft_s" style="text-align:center;">0</th>
                    <th id="ft_i" style="text-align:center;">0</th>
                    <th id="ft_m" style="text-align:center;">0</th>
                    <th id="ft_th" style="text-align:center;">0</th>
                    <th id="ft_persen" style="text-align:center;">0%</th>
                  </tr>
                </tfoot>
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
  function number(n){ return (n==null?0:n); }

  function render(data, footer, tanggal){
    var html = '';
    (data||[]).forEach(function(r){
      html += '<tr>'+
              '<td>'+ (r.nama_bagian||'-') +'</td>'+
              '<td style="text-align:center;">'+ number(r.total) +'</td>'+
              '<td style="text-align:center;">'+ number(r.hadir) +'</td>'+
              '<td style="text-align:center;">'+ number(r.s) +'</td>'+
              '<td style="text-align:center;">'+ number(r.i) +'</td>'+
              '<td style="text-align:center;">'+ number(r.m) +'</td>'+
              '<td style="text-align:center;">'+ number(r.tidak_hadir) +'</td>'+
              '<td style="text-align:center;">'+ (r.persen_tidak_hadir||0) +'%</td>'+
              '</tr>';
    });
    $('#tbody_stat').html(html);
    $('#ft_total').text(number(footer.total));
    $('#ft_hadir').text(number(footer.hadir));
    $('#ft_s').text(number(footer.s));
    $('#ft_i').text(number(footer.i));
    $('#ft_m').text(number(footer.m));
    $('#ft_th').text(number(footer.tidak_hadir));
    $('#ft_persen').text((footer.persen_tidak_hadir||0) + '%');

    if ($.fn.DataTable.isDataTable('#tbl_stat_bag')) {
      $('#tbl_stat_bag').DataTable().destroy();
    }
    $('#tbl_stat_bag').DataTable({
      paging: true,
      pageLength: 50,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      language: { url: "<?php echo base_url('assets/dataTables/Indonesian.json')?>" }
    });

    $('#periode_label').text('Tanggal: ' + tanggal).show();
  }

  function fetch(){
    var tgl = $('#tanggal').val();
    $('#loading').show();
    $.ajax({
      url: '<?php echo base_url('rekap/get_statistik_bagian')?>',
      type: 'GET',
      dataType: 'json',
      data: { tanggal: tgl }
    }).done(function(res){
      $('#loading').hide();
      if(res.success){
        render(res.data, res.footer, res.tanggal);
      } else {
        alert(res.message||'Gagal memuat data');
      }
    }).fail(function(xhr){
      $('#loading').hide();
      alert('Error: ' + (xhr.responseText||xhr.statusText));
    });
  }

  $('#btn_fetch').on('click', fetch);
  $('#tanggal').on('keypress', function(e){ if(e.which===13) fetch(); });

  // Open debug JSON in new tab
  $('#btn_debug').on('click', function(){
    var tgl = $('#tanggal').val();
    window.open('<?php echo base_url('rekap/debug_statistik_bagian?tanggal=')?>' + encodeURIComponent(tgl), '_blank');
  });

  // Export XLSX
  $('#btn_export').on('click', function(){
    var tgl = $('#tanggal').val();
    window.open('<?php echo base_url('rekap/export_statistik_bagian?tanggal=')?>' + encodeURIComponent(tgl), '_blank');
  });

  // auto load on first open
  fetch();
});
</script>

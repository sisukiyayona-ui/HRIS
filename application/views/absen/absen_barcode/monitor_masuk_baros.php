    <style>
    table.dataTable {
    font-size: 1.2em;
    color:#000;
}
</style>
  <!-- page content -->
  <div class="row" style="height:100%; background-color:#f7f7f7;">
      <div class=" col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
              <div class="x_title">
                  <h2>Absen Masuk</h2>
                  <div class="clearfix">
                  </div>
              </div>
              <div class="x_content">
                  <table class="table" id="t_pantry">
                      <thead>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Waktu</th>
                      </thead>
                      <tbody>

                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  <!-- /page content -->
  <script type="text/javascript">
      window.onload = function() {
          getData();
      }

      function getData() {
          var table = $('#t_pantry').DataTable();
          table.destroy();
          var table = $('#t_pantry').DataTable({
             "processing": false,
              "serverSide": true,
              "responsive": false,
              "ordering": false,
              "dom": 'rt',
              buttons: [
                  'excel', 'print'
              ],
              "ajax": {
                  type: "POST",
                  url: "<?php echo base_url(); ?>AbsenBarcode/data_masuk_baros",
                  dataType: 'JSON',

              },
          });
          setInterval(function() {
              table.ajax.reload();
          }, 1000);

      }
  </script>
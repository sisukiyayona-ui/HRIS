  <style>
    table.dataTable {
    font-size: 1.2em;
    color:#000;

    body {
        background: #f5f5f5;
    }
}
</style>
  <!-- page content -->
      <div class=" col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
              <div class="x_title">
                  <h2>Absen Masuk</h2>
                  <div class="clearfix">
                  </div>
              </div>
              <div class="x_content">
                  <table class="table" id="t_monitor">
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
  <!-- /page content -->
  <script type="text/javascript">
      window.onload = function() {
          getData();
      }

      function getData() {
          var table = $('#t_monitor').DataTable();
          table.destroy();
          var table = $('#t_monitor').DataTable({
            "processing": false,
              "serverSide": true,
              "responsive": false,
              "ordering": false,
              "dom": 'rt',
              "ajax": {
                  type: "POST",
                  url: "<?php echo base_url(); ?>AbsenBarcode/data_masuk",
                  dataType: 'JSON',
                  async:true

              },
          });
          setInterval(function() {
              table.ajax.reload();
          }, 1000);

      }
  </script>
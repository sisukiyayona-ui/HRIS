  <!-- page content -->
  <div class="row" style="height:100%">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
              <div class="x_title">
                  <h2>Data Kupon Makan</h2>
                  <div class="clearfix">
                  </div>
              </div>
              <div class="x_content">
                  <table class="table" id="t_pantry">
                      <thead>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
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
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              buttons: [
                  'excel', 'print'
              ],
              "ajax": {
                  type: "POST",
                  url: "<?php echo base_url(); ?>Kantin/monitor_kantin_baros",
                  dataType: 'JSON',

              },
          });
          setInterval(function() {
              table.ajax.reload();
          }, 1000);

      }
  </script>
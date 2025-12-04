  <!-- page content -->
  <div class="row" style="height:100%; background-color:#f7f7f7;">
      <div class=" col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
              <div class="x_title">
                  <h2>Izin Keluar - Masuk</h2>
                  <input type="hidden" id="tgl" value="<?php echo date('Y-m-d') ?>">
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
                          <th>Kategori</th>
                          <th>Jam Masuk</th>
                          <th>Jam Keluar</th>
                          <th>Keterangan</th>
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
          tgl = document.getElementById('tgl').value;
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
                  url: "<?php echo base_url(); ?>AbsenBarcode/data_izin_baros",
                  dataType: 'JSON',
                  data: {
                      tgl: tgl
                  },
              },
          });
          setInterval(function() {
              table.ajax.reload();
          }, 1000);

      }
  </script>
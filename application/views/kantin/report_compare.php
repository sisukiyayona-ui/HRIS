  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Report Komparasi Absen dan Makan</h3>
                  <input type="hidden" id="tgl_makan" value="<?php echo $periode; ?>">
                  <input type="hidden" id="kategori" value="<?php echo $tipe ?>">
              </div>
          </div>

          <div class="clearfix"></div>
          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2><?php echo $menu ?></h2>
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
                                  <th>Kehadiran</th>
                                  <th>Waktu Makan</th>
                                  <th>Manual</th>
                                  <th>Alasan</th>
                              </thead>
                              <tbody>

                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>

      </div>
  </div>
  <!-- /page content -->

  <script src="<?php echo base_url() ?>assets/vendors/sweetalert/sweetalert.min.js"></script>
  <script type="text/javascript">
      window.onload = function() {
          getData();
      }

      function getData() {
          tgl = document.getElementById('tgl_makan').value;
        //   links = "<?php echo base_url(); ?>Kantin/data_compare_absen";
          kategori = document.getElementById('kategori').value;
          if (kategori == "Absen") {
              links = "<?php echo base_url(); ?>Kantin/data_compare_absen";
          } else {
              links = "<?php echo base_url(); ?>Kantin/data_compare_makan";
          }

          var table = $('#t_pantry').DataTable();
          table.destroy();
          var table = $('#t_pantry').DataTable({
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
                  url: links,
                  data: {
                      tgl: tgl
                  },
                  dataType: 'JSON',

              },
          });
      }
  </script>
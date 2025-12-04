  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Report Kupon Makan</h3>
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
                                  <th>Waktu</th>
                                  <th>Lokasi</th>
                                  <th>Manual</th>
                                  <th>Alasan</th>
                              </thead>
                              <tbody>
                                  <?php
                                    $no = 0;
                                    foreach ($karyawan->result() as $k) { ?>
                                      <?php if ($k->manuals == '1' or $k->manuals == '2') {
                                            $man = "Ya";
                                            $waktu_makan = $k->waktu_makan;
                                        } else if ($k->manuals == '3') {
                                            $man = "Flat";
                                            $waktu_makan = $k->tgl_makan;
                                        } else {
                                            $man = "Tidak";
                                            $waktu_makan = $k->waktu_makan;
                                        } ?>
                                      <tr>
                                          <td><?php echo $no = $no + 1 ?></td>
                                          <td><?php echo $k->nik ?></td>
                                          <td><?php echo $k->nama_karyawan ?></td>
                                          <td><?php echo $k->indeks_hr ?></td>
                                          <td><?php echo $waktu_makan ?></td>
                                          <td><?php echo $k->lokasi ?></td>
                                          <td><?php echo $man ?></td>
                                          <td><?php echo $k->alasan ?></td>
                                      </tr>
                                  <?php }
                                    ?>
                                  <?php
                                    foreach ($tamu->result() as $k) { ?>
                                      <?php
                                        if ($k->manuals == '1' or $k->manuals == '2') {
                                            $man = "Ya";
                                            $waktu_makan = $k->waktu_makan;
                                        } else if ($k->manuals == '3') {
                                            $man = "Flat";
                                            $waktu_makan = $k->tgl_makan;
                                        } else {
                                            $man = "Tidak";
                                            $waktu_makan = $k->waktu_makan;
                                        } ?>
                                      <tr>
                                          <td><?php echo $no = $no + 1 ?></td>
                                          <td><?php echo $k->no_barcode ?></td>
                                          <td><?php echo $k->guest_name ?></td>
                                          <td><?php echo $k->kategori ?></td>
                                          <td><?php echo $waktu_makan ?></td>
                                          <td><?php echo $k->lokasi ?></td>
                                          <td><?php echo $man ?></td>
                                          <td><?php echo $k->alasan ?></td>
                                      </tr>
                                  <?php }
                                    ?>
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
          var table = $('#t_pantry').DataTable({
              "responsive": false,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              "pageLength": 30,
              buttons: [
                  'excel', 'print'
              ],
          });
      }

      function getData() {

      }
  </script>
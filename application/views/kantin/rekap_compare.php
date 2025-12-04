  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Rekapitulasi Komparasi Absen dan Makan</h3><br>
                  <!-- <h6>Tidak Absen - Makan</h6> -->

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
                                  <?php
                                    $no = 0;
                                    foreach ($compare->result() as $r) {
                                        if ($r->manuals == '1' or $r->manuals == '2') {
                                            $man = "Ya";
                                        } else if ($r->manuals == '3') {
                                            $man = "Flat";
                                        } else {
                                            $man = "Tidak";
                                        } ?>
                                      <tr>
                                          <td><?php echo $no = $no + 1 ?></td>
                                          <td><?php echo $r->nik ?></td>
                                          <td><?php echo $r->nama_karyawan ?></td>
                                          <td><?php echo $r->indeks_hr ?></td>
                                          <td><?php echo $r->keterangan ?></td>
                                          <td><?php echo $r->waktu_makan ?></td>
                                          <td><?php echo $man ?></td>
                                          <td><?php echo $r->alasan ?></td>
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

  <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#t_pantry').DataTable({
              "dom": 'Bfrtip',
              buttons: [
                  'excel', 'print'
              ],
          });
      });
  </script>
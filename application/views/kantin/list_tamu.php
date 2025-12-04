  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3><?php echo $menu ?></h3>
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
                                  <th>No Barcode</th>
                                  <th>Nama</th>
                                  <th>Kategori</th>
                              </thead>
                              <tbody>
                                  <?php
                                    $no = 0;
                                    foreach ($karyawan as $data) { ?>
                                      <tr>
                                          <td><?php echo $no = $no + 1 ?></td>
                                          <td><?php echo $data->no_barcode ?></td>
                                          <td><?php echo $data->guest_name ?></td>
                                          <td><?php echo $data->kategori ?></td>
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
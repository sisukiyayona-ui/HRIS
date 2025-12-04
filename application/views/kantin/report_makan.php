  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Report Kupon Makan</h3>
                  <input type="hidden" id="tgl_makan" value="<?php echo date('Y-m-d'); ?>">
                  <input type="hidden" id="flag" value="<?php echo $flag ?>">
                  <input type="hidden" id="kategori" value="<?php echo $tipe ?>">
                  <input type="hidden" id="lokasi" value="<?php echo $lokasi ?>">
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
                                  <th>Penempatan</th>
                                  <th>Waktu</th>
                                  <th>Lokasi</th>
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
          kategori = document.getElementById('kategori').value;
          flag = document.getElementById('flag').value;
          lokasi = document.getElementById('lokasi').value;
          if (flag == "Manual") {
              if (kategori == 'Karyawan') {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_karyawanb";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_karyawani";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_karyawan";
                  }
              } else if (kategori == "Semua") {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_ind";
                  } else if (lokasi == 'industri') {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_brs";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_semua";
                  }
              } else if (kategori == "Koprasi") {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_koprasib";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_koprasii";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_koprasi";
                  }
              } else {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_tamub";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_tamui";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_manual_tamu";
                  }
              }
          } else if (flag == "Scan") {
              if (kategori == 'Karyawan') {
                  links = "<?php echo base_url(); ?>Kantin/data_scan_karyawan";
              } else if (kategori == "Koprasi") {
                  links = "<?php echo base_url(); ?>Kantin/data_scan_koprasi";
              } else if (kategori == "Semua") {
                  links = "<?php echo base_url(); ?>Kantin/data_scan_semua";
              } else {
                  links = "<?php echo base_url(); ?>Kantin/data_scan_tamu";
              }
          } else if (flag == "Flat") {
              if (kategori == 'Karyawan') {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_karyawanb";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_karyawani";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_karyawan";
                  }
              } else if (kategori == "Koprasi") {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_koprasib";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_koprasii";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_koprasi";
                  }
              } else if (kategori == "Semua") {
                  links = "<?php echo base_url(); ?>Kantin/data_flat_semua";
              } else {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_tamub";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_tamui";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_flat_tamu";
                  }
              }
          } else {
              if (kategori == 'Karyawan') {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_karyawanb";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_karyawani";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_karyawan";
                  }
              } else if (kategori == "Koprasi") {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_koprasib";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_koprasii";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_koprasi";
                  }
              } else if (kategori == "Semua") {
                  links = "<?php echo base_url(); ?>Kantin/data_makan_semua";
              } else {
                  if (lokasi == "baros") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_tamub";
                  } else if (lokasi == "industri") {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_tamui";
                  } else {
                      links = "<?php echo base_url(); ?>Kantin/data_makan_tamu";
                  }
              }
          }


          var table = $('#t_pantry').DataTable();
          table.destroy();
          var table = $('#t_pantry').DataTable({
              "responsive": false,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "pageLength": 30,
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
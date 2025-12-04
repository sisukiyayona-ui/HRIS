  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Generate Kupon Makan Flat Industri</h3>
              </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Generate Kupon Makan Flat Industri</h2>
                          <div class="clearfix">
                          </div>
                      </div>
                      <div class="x_content">
                          <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Kantin/generate_flat_industri" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                              <div class="item form-group">
                                  <div class="col-md-1 col-sm-1 col-xs-1"><label>Tanggal</label></div>
                                  <div class="col-md-2 col-sm-2 col-xs-2">
                                      <div class='input-group date' id='myDatepicker2'>
                                          <input type='text' class="form-control" name="tanggal" id="tanggal" value="<?php echo date("Y-m-d") ?>" />
                                          <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-calendar"></span>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="ln_solid"></div>
                              <div class="form-group">
                                  <div class="col-md-6 col-md-offset-3">
                                      <a href='<?php echo base_url() ?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                                      <button id="send" type="submit" class="btn btn-success">Submit</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Kupon Makan Flat </h2>
                          <div class="clearfix">
                          </div>
                      </div>
                      <div class="x_content">
                          <div class="item form-group">
                              <label class="col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir"> Tanggal<span class="required">*</span>
                              </label>
                              <div class="col-md-2 col-sm-2 col-xs-12">
                                  <div class='input-group date' id='myDatepicker3'>
                                      <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <div class="col-md-2 col-sm-2 col-xs-12">
                                  <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                              </div>
                          </div>
                          <br>
                          <br>
                          <table class="table" id="t_pantry">
                              <thead>
                                  <th>No</th>
                                  <th>NIK</th>
                                  <th>Nama Karyawan</th>
                                  <th>Bagian</th>
                                  <th>Penempatan</th>
                                  <th>Waktu</th>
                                  <th>Alasan</th>
                                  <th>Aksi</th>
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
      $(document).ready(function() {
          getData();
      });

      function getData() {
          tgl_mulai = document.getElementById("tgl_mulai").value;
          //   tgl_akhir = document.getElementById("tgl_akhir").value;
          //   jenis = $('#jenis').val();
          // console.log(jenis);
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
                  url: "<?php echo base_url(); ?>Kantin/data_flat",
                  dataType: 'JSON',
                  data: {
                      tgl_mulai: tgl_mulai
                  },
              },
          });
      }

      /*  function save_makan_top() {
           nik = document.getElementById('karyawan').value;
           alasan = "Makan Di Mess / Pantry";
           link = "<?php echo base_url() ?>Kantin/manual_mess";
           $.ajax({
               type: "POST", // Method pengiriman data bisa dengan GET atau POST
               url: "<?php echo base_url(); ?>Kantin/save_manual", // Isi dengan url/path file php yang dituju
               data: {
                   nik: nik,
                   alasan: alasan,
               }, // data yang akan dikirim ke file yang dituju
               dataType: "json",
               beforeSend: function(e) {
                   if (e && e.overrideMimeType) {
                       e.overrideMimeType("application/json;charset=UTF-8");
                   }
               },
               success: function(data, response) { // Ketika proses pengiriman berhasil
                   // set isi dari combobox kota
                   // lalu munculkan kembali combobox kotanya
                   if (data == "Ok") {
                       swal({
                           title: "Berhasil!",
                           text: "Input Berhasil!",
                           type: "success",
                           icon: "success",
                           timer: 4000,
                       }).then(function() {
                           location.href = link;
                       });

                   } else {

                       swal({
                           title: "Error!",
                           text: "Gagal Menyimpan Data!",
                           type: "error",
                           icon: "error",
                           timer: 4000,
                       }).then(function() {
                           location.reload();
                       });
                   }
               },
               error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                   alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
               }
           });
       } */
  </script>
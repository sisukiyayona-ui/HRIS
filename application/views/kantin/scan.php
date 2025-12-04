  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Scan Makan</h3>
              </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Scan Kupon Makan </h2>
                          <div class="clearfix">
                          </div>
                      </div>
                      <div class="x_content" style="height:400px;">
                          <div style="width:100%; text-align:center; font-size:22pt">
                              <span id="hari"></span></b><br>
                              <span id="jam"></span></b>
                          </div>
                          <div style="margin-top: 30px;">
                              <center>
                                  <p>Silakan Scan ID Card</p><br>
                              </center>
                              <center>
                                  <div style="width:500px; margin-top:-30px;">
                                      <form method="post" id="izin" action="">
                                          <input type="text" id="nik" name="nik" maxlength="11" class="form-control validate paste scanner" autofocus="autofocus" autocomplete="off" required>
                                          <!-- <input type="text" name="shadow" maxlength="11" readonly> -->
                                          <input type="submit" name="simpan" value="Simpan" id="simpan" class="btn btn-primary" style="display:none;">
                                      </form>
                                  </div>
                              </center>
                              <!--   <a href="#" class="btn btn-white">
                                Our Works
                            </a> -->
                          </div><!-- /.intro -->
                          <!-- Add content to the page ... -->
                      </div>
                  </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                  <div style="position:relative; width:100%; height:0px; padding-bottom:85%;">
                      <iframe id="monitor" style="position:absolute; left:0; top:0; width:100%; height:100%" src="<?php echo base_url() ?>Kantin/monitor">
                      </iframe>
                  </div>
              </div>
          </div>

          <div class="row">

          </div>

          <!-- <div class="row">
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
          </div> -->

      </div>
  </div>
  <!-- /page content -->

  <script src="<?php echo base_url() ?>assets/vendors/sweetalert/sweetalert.min.js"></script>
  <script type="text/javascript">
      window.onload = function() {
          jam();
          hari();
          getData();
      }

      function jam() {
          var e = document.getElementById('jam'),
              d = new Date(),
              h, m, s;
          h = d.getHours();
          m = set(d.getMinutes());
          s = set(d.getSeconds());

          e.innerHTML = h + ':' + m + ':' + s;

          setTimeout('jam()', 1000);
      }

      function set(e) {
          e = e < 10 ? '0' + e : e;
          return e;
      }

      function hari() {
          date = new Date;
          year = date.getFullYear();
          month = date.getMonth();
          months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
          d = date.getDate();
          day = date.getDay();
          days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
          result = '' + d + ' ' + months[month] + '  ' + year;
          document.getElementById('hari').innerHTML = result;
      }

      function textLength(value) {
          var maxLength = 11;
          if (value.length >= maxLength) return false;
          return true;
      }

      var oldValue = '';
      //var alert = document.getElementById('alert');
      document.getElementById('nik').onkeyup = function() {
          if (!textLength(this.value)) {

              oldValue = this.value;
              simpan_makan();

          } else {
              console.log("belum lengkap");
          }
      }



      function simpan_makan() {
          var nik = document.getElementById('nik').value;
          no = 0;
          //   alert(nik);
          $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?php echo base_url(); ?>Kantin/cek_hadir", // Isi dengan url/path file php yang dituju
              async: false,
              data: {
                  nik: nik
              }, // data yang akan dikirim ke file yang dituju
              dataType: "json",
              beforeSend: function(e) {
                  if (e && e.overrideMimeType) {
                      e.overrideMimeType("application/json;charset=UTF-8");
                  }
              },
              success: function(data, response) { // Ketika proses pengiriman berhasil
                  no = no + 1;
                  //   alert(no);
                  // set isi dari combobox kota
                  // lalu munculkan kembali combobox kotanya
                  if (data[0] == "Hadir") {
                      //   alert("Save makan");
                      //   recid_karyawan = data[0];
                      //   recid_tamu = data[3];
                      //   kategori = data[4];
                      $.ajax({
                          type: "POST", // Method pengiriman data bisa dengan GET atau POST
                          url: "<?php echo base_url(); ?>Kantin/save_makan", // Isi dengan url/path file php yang dituju
                          async: false,
                          data: {
                              nik: nik,
                          }, // data yang akan dikirim ke file yang dituju
                          dataType: "json",
                          beforeSend: function(e) {
                              if (e && e.overrideMimeType) {
                                  e.overrideMimeType("application/json;charset=UTF-8");
                              }
                          },
                          success: function(data2, response2) { // Ketika proses pengiriman berhasil
                              // set isi dari combobox kota
                              // lalu munculkan kembali combobox kotanya
                              if (data2 == "Ok") {
                                  swal({
                                      title: "Berhasil!",
                                      text: "Scan Berhasil!",
                                      type: "success",
                                      icon: "success",
                                      timer: 1000,
                                  }).then(function() {
                                      $('#nik').focus();
                                      //   const iframe = document.getElementById("monitor");
                                      //   iframe.contentWindow.location.reload();
                                  });

                              } else {

                                  swal({
                                      title: "Error!",
                                      text: "Gagal Menyimpan Data!",
                                      type: "error",
                                      icon: "error",
                                      timer: 1000,
                                  }).then(function() {
                                      $('#nik').focus();
                                      //   const iframe = document.getElementById("monitor");
                                      //   iframe.contentWindow.location.reload();
                                  });
                              }
                          },
                          error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                          }
                      });
                      document.getElementById('nik').value = "";

                  } else if (data[0] == "Sudah Scan") {

                      swal({
                          title: "Maaf!",
                          text: "Anda Sudah Scan di " + data[1],
                          type: "error",
                          icon: "error",
                          timer: 1000,
                      }).then(function() {
                          document.getElementById("nik").value = "";
                          $('#nik').focus();
                          //   const iframe = document.getElementById("monitor");
                          //   iframe.contentWindow.location.reload();
                      });

                  } else {
                      //   swal("Maaf!", "Absen Hari Ini Adalah " + data[1], "error");
                      swal({
                          title: "Maaf!",
                          text: "Absen Hari Ini Adalah " + data[1],
                          type: "error",
                          icon: "error",
                          timer: 1000,
                      }).then(function() {
                          document.getElementById("nik").value = "";
                          $('#nik').focus();
                          //   const iframe = document.getElementById("monitor");
                          //   iframe.contentWindow.location.reload();
                      });
                  }
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
          });
      }

      function autofc() {
          const autofocusedElements = document.querySelectorAll('input[autofocus]');
          if (autofocusedElements.length) {
              autofocusedElements[0].focus();
          }
      }

      function getData() {
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
                  url: "<?php echo base_url(); ?>Kantin/makan_today",
                  dataType: 'JSON',

              },
          });
      }
  </script>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="navbar nav_title" style="border: 0;">
                <a href="<?php echo base_url() ?>Karyawan/dash" class="site_title"><i class="fa fa-leaf"></i> <span>HRIS</span></a>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right" style="height:56px;">
                            <li class="">
                                <div class="nav" style="margin:20px;">
                                    <a href="<?php echo base_url() ?>AbsenBarcode/absen_masuk_baros" style="color:#FFF"><button type='button' class="btn btn-sm btn-primary">Absen Masuk</buttton></a>
                                    <a href="<?php echo base_url() ?>AbsenBarcode/absen_pulang_baros" style="color:#FFF"><button type='button' class="btn btn-sm btn-warning">Absen Pulang</buttton></a>
                                </div>

                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="" style="background-color:#f7f7f7; padding: 30px;">
                <!-- <div class="page-title">
                            <div class="title_right">
                                <h3 style="text-align: center;">Absen Masuk</h3>
                            </div>
                        </div> -->

                <div class="clearfix"></div>

                <div class="row" style="margin-top:25px;">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Scan Izin</h2>
                                <div class="clearfix">
                                </div>
                            </div>
                            <div class="x_content" style="height:400px;">
                                <div style="width:95%; text-align:center; font-size:22pt">
                                    <span id="hari"></span></b><br>
                                    <span id="jam"></span></b>
                                </div>
                                <div style="margin-top: 30px;">
                                    <center>
                                        <p>Silakan Scan ID Card</p><br><br>
                                    </center>
                                    <center>
                                        <div style="width:500px; margin-top:-30px;">
                                            <form method="post" action="">
                                                <input type="radio" name="jenis" id="jenis" value="Pribadi" checked> Pribadi
                                                <input type="radio" name="jenis" id="jenis" value="Dinas"> Dinas Dalam / Luar Kota
                                                <input type="radio" name="jenis" id="jenis" value="Baros"> Dinas Baros
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
                            <iframe id="monitor" style="position:absolute; left:0; top:0; width:100%; height:100%" src="<?php echo base_url() ?>AbsenBarcode/monitor_izin_baros">
                            </iframe>
                        </div>
                    </div>
                </div>

                <div class="row">

                </div>
            </div>
        </div>
        <!-- /page content -->

        <script src="<?php echo base_url() ?>assets/vendors/sweetalert/sweetalert.min.js"></script>
        <script type="text/javascript">
            window.onload = function() {
                jam();
                hari();
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
                    simpan_izin();

                } else {
                    console.log("belum lengkap");
                }
            }



            function simpan_izin() {
                var nik = document.getElementById('nik').value;
                // var jenis = document.getElementById('jenis').value;
                jenis = document.querySelector('input[name="jenis"]:checked').value;
                no = 0;
                //   alert(nik);
                $.ajax({
                    type: "POST", // Method pengiriman data bisa dengan GET atau POST
                    url: "<?php echo base_url(); ?>AbsenBarcode/simpan_izin_baros", // Isi dengan url/path file php yang dituju
                    async: false,
                    data: {
                        nik: nik,
                        jenis: jenis,
                    }, // data yang akan dikirim ke file yang dituju
                    dataType: "json",
                    beforeSend: function(e) {
                        if (e && e.overrideMimeType) {
                            e.overrideMimeType("application/json;charset=UTF-8");
                        }
                    },
                    success: function(data, response) { // Ketika proses pengiriman berhasil
                        if (data == "Ok") {
                            swal({
                                title: "Berhasil!",
                                text: "Scan Berhasil!",
                                type: "success",
                                icon: "success",
                                timer: 1000,
                            }).then(function() {
                                document.getElementById('nik').value = "";
                                $('#nik').focus();
                                //   const iframe = document.getElementById("monitor");
                                //   iframe.contentWindow.location.reload();
                            });

                        } else {

                            swal({
                                title: "Error!",
                                text: data,
                                type: "error",
                                icon: "error",
                                timer: 1500,
                            }).then(function() {
                                document.getElementById('nik').value = "";
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
        </script>
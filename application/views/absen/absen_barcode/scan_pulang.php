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
                        <div class="nav toggle" style="height: 58px;">

                        </div>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="" style="background-color:#f7f7f7; padding: 30px;">
                <div class="clearfix"></div>

                <div class="row">
                    <div class=" col-md-4 col-sm-4 col-xs-4">
                        <div class="x_panel" style="background-image: url('<?php echo base_url()?>images/dongker4.webp');background-size:cover; background-repeat:no-repeat">
                            <div class="x_title">
                               <div class="row">
                                    <div class="col-md-6 box1" style="float:left">
                                        <a href="<?php echo base_url() ?>AbsenBarcode/absen_masuk"><button type="button" class="btn btn-danger btn-lg">Absen Masuk</button></a>
                                    </div>
                                    <div class="col-md-6 box2" style="float:right">
                                        <a href="<?php echo base_url() ?>AbsenBarcode/absen_pulang"><button type="button" class="btn btn-warning btn-lg" style="float:right">Absen Pulang</button></a>
                                    </div>
                                </div>
                                <center>
                                    <br>
                                 <span style="font-size: 23pt; color:white">Absen </span> <span style="font-size: 23pt; color:red"><u>Pulang</u></span> <span style="font-size: 23pt; color:white">Industri</span>
                                </center>
                                <div class="clearfix">
                                </div>
                            </div>
                            <div class="x_content" style="height:258px;">
                                <div style="width:95%; text-align:center; font-size:22pt; color:white">
                                    <span id="hari"></span></b><br>
                                    <span id="jam"></span></b>
                                </div>
                                <div style="margin-top: 30px;">
                                    <center>
                                        <p style="color:white">Silakan Scan ID Card</p><br>
                                    </center>
                                    <center>
                                        <div style="margin-top:-30px;">
                                            <form method="post" id="izin" action="">
                                                <input type="text" id="nik" name="nik" maxlength="20" class="form-control validate paste scanner" autofocus="autofocus" autocomplete="off" required>
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

                    <div class="col-md-8 col-sm-8 col-xs-8">
                        <div style="position:relative; width:100%; height:0px; padding-bottom:48%;">
                            <iframe id="monitor" style="position:absolute; left:0; top:0; width:100%; height:100%" src="<?php echo base_url() ?>AbsenBarcode/monitor_pulang">
                            </iframe>
                        </div>
                    </div>

                </div>

                <div class=" row">

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
                // Support both old format (11 chars) and new format (variable length)
                // Old format: numeric like "20180108416" (11 chars)
                // New format: alphanumeric like "A1-0001" (minimum 7 chars)
                if (value.length >= 11) {
                    // Check if it's old format (numeric, exactly 11 chars)
                    if (/^\d{11}$/.test(value)) {
                        return false; // Auto submit for old format
                    }
                }
                // For new format, wait for at least 7 characters to avoid premature submission
                if (value.length >= 7 && /[A-Za-z]/.test(value)) {
                    return false; // Auto submit for new format
                }
                return true; // Still typing
            }

            var oldValue = '';
            var typingTimer; // Timer for detecting manual typing vs barcode scan
            var doneTypingInterval = 1000; // 1 second delay
            
            document.getElementById('nik').onkeyup = function() {
                clearTimeout(typingTimer);
                
                if (!textLength(this.value)) {
                    // If it looks like a complete NIK, set a timer
                    typingTimer = setTimeout(function() {
                        oldValue = document.getElementById('nik').value;
                        simpan_pulang();
                    }, doneTypingInterval);
                } else {
                    console.log("belum lengkap");
                }
            }

            // Immediate submission for very fast input (likely barcode scan)
            document.getElementById('nik').oninput = function() {
                if (!textLength(this.value)) {
                    // Check if input was very fast (barcode scan characteristics)
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(function() {
                        oldValue = document.getElementById('nik').value;
                        simpan_pulang();
                    }, 300); // Shorter delay for barcode scan
                }
            }

            function simpan_pulang() {
                var nik = document.getElementById('nik').value;
                no = 0;
                //   alert(nik);
                $.ajax({
                    type: "POST", // Method pengiriman data bisa dengan GET atau POST
                    url: "<?php echo base_url(); ?>AbsenBarcode/simpan_pulang", // Isi dengan url/path file php yang dituju
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
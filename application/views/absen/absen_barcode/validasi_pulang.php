<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Validasi Pulang Oleh Keamanan</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><a href="<?php echo base_url() ?>Karyawan/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Validasi Pulang</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- Content Form -->
                        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>AbsenBarcode/pvalidasi_pulang" novalidate>
                            <?php foreach ($izin->result() as $data) {
                                $cek_hadir = $this->m_absenbarcode->cek_double($data->recid_karyawan, $data->tgl_izin);
                                if ($cek_hadir->num_rows() > 0) {
                                    foreach ($cek_hadir->result() as $h) {
                                        $id_hadir = $h->recid_absen;
                                        $status = $h->status;
                                        $tmp_status = $h->tmp_status;
                                    }
                                }

                                $cek_jenis = $this->m_absenbarcode->cek_ja_hadir($data->recid_karyawan, $data->tgl_izin);
                                if ($cek_jenis->num_rows() > 0) {
                                    foreach ($cek_jenis->result() as $k) {
                                        $jam_keluar1 = $k->jam_keluar;
                                        $jam_keluar = $k->jam_keluar;
                                        $jam_masuk1 = $k->jam_masuk;
                                        $jam_masuk = $k->jam_masuk;

                                        if ($status == 1) // kerja normal (11:30 - 12:30)
                                        {
                                            if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
                                                $jam_keluar = new DateTime("12:31"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 26) { /* Middle Shift 1 (11:00 - 12:00)*/
                                            if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
                                                $jam_keluar = new DateTime("12:01"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 27) {/* Middle Shift 2 (13:00 - 14:00)*/
                                            if ($jam_keluar1 > "13:00" and $jam_keluar1 < "14:01") {
                                                $jam_keluar = new DateTime("14:01"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 29) {/* Kerja Non Shift Ramadhan (11:00 - 12:00)*/
                                            if ($jam_keluar1 > "11:00" and $jam_keluar1 < "12:01") {
                                                $jam_keluar = new DateTime("12:01"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 30) {/* Middle Shift 3 (12:00 - 13.00)*/
                                            if ($jam_keluar1 > "12:00" and $jam_keluar1 < "13:01") {
                                                $jam_keluar = new DateTime("13:01"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 35) {/* Ganti Hari Kerja (11:30 - 12:30)*/
                                            if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
                                                $jam_keluar = new DateTime("12:31"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else if ($status == 37) {/* Middle Shift 4 (14:30 - 15:30) */
                                            if ($jam_keluar1 > "14:30" and $jam_keluar1 < "15:31") {
                                                $jam_keluar = new DateTime("15:31"); // jam scan
                                            } else {
                                                $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                            }
                                        } else {
                                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                        }
                                        $pulang_normal = new DateTime($k->jam_out);
                                        $selisih = $pulang_normal->diff($jam_keluar);
                                        $jam = $selisih->format('%h');
                                        $menit = $selisih->format('%i');
                                        if ($menit >= 60) {
                                            $jam = $jam + 1;
                                            $menit = $menit - 60;
                                        }
                                        if ($menit >= 0 && $menit <= 9) {
                                            $menit = "0" . $menit;
                                        }
                                        $durasi = $jam . ' jam ' . $menit . " menit";
                                        $over_durasi = $jam . "." . $menit;
                                    }
                                }
                            } ?>


                            <!-- <span class="section">Personal Info</span>-->
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="nik" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->nik ?>" readonly>
                                    <input type="hidden" name="recid_karyawan" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_karyawan ?>" readonly>
                                    <!-- <input type="hidden" name="recid_absen" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_absen ?>" readonly> -->
                                    <input type="text" name="recid_izin" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $this->uri->segment(3); ?>" readonly>
                                    <input type="hidden" name="status" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $status ?>" readonly>
                                    <input type="hidden" name="tmp_status" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $tmp_status ?>" readonly>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="nama" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->nama_karyawan ?>" readonly>
                                    <input type="hidden" name="recid_karyawan" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_karyawan ?>" readonly>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label for="agama" class="control-label col-md-3">Bagian<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="bag" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->indeks_hr ?>" readonly>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Izin<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class='input-group date' id='myDatepicker3'>
                                        <input type='text' class="form-control" name="tgl_kerja" required="required" value="<?php echo $data->tgl_izin ?>" readonly />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Kerja <span class="required">*</span>
                                </label>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="text" id='jam_masuk' class="form-control" value="<?php echo $jam_masuk1 ?>" readonly="readonly">
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <input type="text" id='jam_masuk' class="form-control" value="<?php echo $jam_keluar1 ?>" readonly="readonly">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label for="agama" class="control-label col-md-3">Durasi Izin<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id='' class="form-control col-md-7" value="<?php echo $durasi; ?>" readonly="readonly">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label for="agama" class="control-label col-md-3">Validasi Keterangan<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id='' class="form-control col-md-7" value="Pulang" readonly="readonly">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Alasan <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="alasan" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <a href="<?php echo base_url() ?>Karyawan/absen_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
                                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!--/ Content Form -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /page content -->
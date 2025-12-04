<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Validasi Data Karyawan Pulang Cepat</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix">Dafar Validasi Pulang Cepat</div>
                    </div>
                    <div class="x_content">
                        <!--Add content to the page ...-->
                        <!-- Content Table -->
                        <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nik</th>
                                        <th>Nama</th>
                                        <th>Bagian</th>
                                        <th>Jabatan</th>
                                        <th>Jenis</th>
                                        <th>Jam Keluar</th>
                                        <th>Scan Masuk</th>
                                        <th>Scan Pulang</th>
                                        <th>Durasi Izin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($scan->result() as $s) {
                                        $db2 = $this->load->database('absen', TRUE);
                                        $cek_jenis = $this->m_absenbarcode->cek_ja_hadir($s->recid_karyawan, $s->tgl_izin);
                                        if ($cek_jenis->num_rows() > 0) {
                                            foreach ($cek_jenis->result() as $k) {
                                                $jam_masuk = $k->jam_masuk;
                                                $pulang_normal1 = $k->jam_out;
                                                $jam_keluar1 = $k->jam_keluar;
                                                if ($jam_keluar1 != "" or $jam_keluar1 != null) {
                                                    if ($k->status == 1) // kerja normal
                                                    {
                                                        if ($jam_keluar1 > "11:30" and $jam_keluar1 < "12:31") {
                                                            $jam_keluar = new DateTime("12:31"); // jam scan
                                                        } else {
                                                            $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                                        }
                                                    } else {
                                                        $jam_keluar = new DateTime($k->jam_keluar); // jam scan
                                                    }
                                                    $pulang_normal = new DateTime($k->jam_out);
                                                    // $jam_keluar = new DateTime($k->jam_keluar); // jam scan
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
                                                } else {
                                                    $durasi = "";
                                                }
                                            }
                                        } else {
                                            $pulang_normal = "00:00:00";
                                            $pulang_normal1 = "00:00:00";
                                            $jam_masuk1 = "00:00:00";
                                            $jam_masuk = "00:00:00";
                                            $jam_pulang = "00:00:00";
                                            $jam_keluar1 = "00:00:00";
                                            $jam_keluar = "00:00:00";
                                            $durasi = "00:00:00";
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $s->tgl_izin ?></td>
                                            <td><?php echo $s->nik ?></td>
                                            <td><?php echo $s->nama_karyawan ?></td>
                                            <td><?php echo $s->indeks_hr ?></td>
                                            <td><?php echo $s->indeks_jabatan ?></td>
                                            <td><?php echo $s->jenis ?></td>
                                            <td><?php echo $pulang_normal1 ?></td>
                                            <td><?php echo $jam_masuk ?></td>
                                            <td><?php echo $jam_keluar1 ?></td>
                                            <td><?php echo $durasi ?></td>
                                            <td><a href="<?php echo base_url() ?>AbsenBarcode/validasi_pulang/<?php echo $s->izin_recid ?>"><button type="button" class="btn btn-primary">Validasi</button></a></td>
                                        <?php }
                                        ?>
                                        </tr>

                                </tbody>
                            </table>
                            <!--/ Content Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- /page content -->
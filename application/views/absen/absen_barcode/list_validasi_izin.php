<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Validasi Izin Masuk dan Keluar Karyawan</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix">Daftar Validasi Izin Masuk dan Keluar</div>
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
                                        <th>Jenis Keluar</th>
                                        <th>Scan Keluar</th>
                                        <th>Scan Masuk</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($scan->result() as $s) { ?>
                                        <tr>
                                            <td><?php echo $s->tgl_izin ?></td>
                                            <td><?php echo $s->nik ?></td>
                                            <td><?php echo $s->nama_karyawan ?></td>
                                            <td><?php echo $s->indeks_hr ?></td>
                                            <td><?php echo $s->indeks_jabatan ?></td>
                                            <td><?php echo $s->kat_keluar ?></td>
                                            <td><?php echo $s->jam_out ?></td>
                                            <td><?php echo $s->jam_in ?></td>
                                            <?php
                                            $diff = strtotime($s->jam_in) - strtotime($s->jam_out);
                                            $jam   = floor($diff / (60 * 60));
                                            $menit = $diff - ($jam * (60 * 60));
                                            $detik = $diff % 60;
                                            if ($s->jam_in == '' or $s->jam_out == '') { ?>
                                                <td></td>
                                            <?php } else { ?>
                                                <td><?php echo $jam . " jam " . floor($menit / 60) . " menit "; ?></td>
                                            <?php }
                                            ?>
                                            <td><a href="<?php echo base_url() ?>AbsenBarcode/validasi_izin/<?php echo $s->izin_recid ?>"><button type="button" class="btn btn-primary">Validasi</button></a></td>
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
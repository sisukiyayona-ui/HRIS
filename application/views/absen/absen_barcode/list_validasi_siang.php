<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Validasi Data Karyawan Terlambat</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix">Dafar Validasi Terlambat</div>
                    </div>
                    <div class="x_content">
                        <!--Add content to the page ...-->
                        <!-- Content Table -->
                        <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Tanggal</th>
                                        <th>Nik</th>
                                        <th>Nama</th>
                                        <th>Bagian</th>
                                        <th>Jabatan</th>
                                        <th>Shift</th>
                                        <th>Jam Masuk</th>
                                        <th>Waktu Absen</th>
                                        <th>Terlambat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($scan->result() as $s) { ?>
                                        <tr>
                                            <td><?php echo $s->recid_absen ?></td>
                                            <td><?php echo $s->tanggal ?></td>
                                            <td><?php echo $s->nik ?></td>
                                            <td><?php echo $s->nama_karyawan ?></td>
                                            <td><?php echo $s->indeks_hr ?></td>
                                            <td><?php echo $s->indeks_jabatan ?></td>
                                            <td><?php echo $s->nama_shift ?></td>
                                            <td><?php echo $s->in_tmp ?></td>
                                            <td><?php echo $s->jam_masuk ?></td>
                                            <?php
                                            $diff = strtotime($s->jam_masuk) - strtotime($s->in_tmp);
                                            $jam   = floor($diff / (60 * 60));
                                            $menit = $diff - ($jam * (60 * 60));
                                            $detik = $diff % 60;
                                            ?>
                                            <td><?php echo $jam . " jam " . floor($menit / 60) . " menit "; ?></td>
                                            <td><a href="<?php echo base_url()?>AbsenBarcode/validasi_telat/<?php echo $s->recid_absen ?>"><button type="button" class="btn btn-primary">Validasi</button></a></td>
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
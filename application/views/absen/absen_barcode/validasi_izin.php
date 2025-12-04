<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Validasi Izin Masuk - Keluar Keamanan</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><a href="<?php echo base_url() ?>Karyawan/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Validasi Izin Masuk - Keluar Oleh Keamanan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- Content Form -->
                        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>AbsenBarcode/pvalidasi_izin" novalidate>
                            <?php foreach ($hadir->result() as $data) {
                                # code...
                            } ?>
                            <!-- <span class="section">Personal Info</span>-->
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="nik" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->nik ?>" readonly>
                                    <input type="hidden" name="recid_karyawan" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_karyawan ?>" readonly>
                                    <input type="hidden" name="izin_recid" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->izin_recid ?>" readonly>
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
                                        <input type='text' class="form-control" name="tgl_izin" required="required" value="<?php echo $data->tgl_izin ?>" readonly />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $kat = $data->kat_keluar;
                            if ($kat == 'Dinas') {
                                $kat = "Dinas Dalam / Luar Kota";
                            } else if ($kat == 'Baros') {
                                $kat = 'Dinas Baros / Industri';
                            } else {
                                $kat = $kat;
                            }
                            ?>
                            <div class="item form-group">
                                <label for="agama" class="control-label col-md-3">Kategori Keluar<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id='' class="form-control col-md-7" value="<?php echo $kat ?>" readonly="readonly">
                                    <input type="text" id='' name="kat_keluar" class="form-control col-md-7" value="<?php echo $data->kat_keluar ?>" readonly="readonly">
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
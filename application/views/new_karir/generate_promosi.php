<!-- page content -->

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Generate SK Promosi</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix"></div>
                        <h5>Generate SK Promosi</h5>
                    </div>
                    <div class="x_content">
                        <!-- Content Form -->
                        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>index.php/Karir/promosi_pdf" target="_blank" id="karir" novalidate>

                            <!-- <span class="section">Personal Info</span>-->
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required" id="emp12" name="recid_karyawan">
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        foreach ($karyawan as $option) {
                                            echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Nomor SK <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="no_sk" placeholder="Nomor SK" required="required" type="text">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class='input-group date' id='myDatepicker5'>
                                        <input type='text' class="form-control" name="tgl_m_karir" required="required" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="item form-group akhir">
                                <label for="agama" class="control-label col-md-3">Bagian<span class="required" style="color: red">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="recid_bag" id="recid_bag" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                                        <?php
                                        echo "<option value=''>-- Pilih --</option>";
                                        foreach ($bagian as $option) {
                                            echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group akhir">
                                <label for="jabatan" class="control-label col-md-3">Jabatan<span class="required" style="color: red">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="recid_jbtn" id="recid_jbtn" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                                        <?php
                                        echo "<option value=''>-- Pilih --</option>";
                                        foreach ($jabatan as $option) {
                                            echo "<option value='$option->recid_jbtn'>$option->indeks_jabatan ($option->sts_jabatan)</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group akhir">
                                <label for="jabatan" class="control-label col-md-3">Golongan<span class="required" style="color: red">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="recid_golongan" id="recid_golongan" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                                        <?php
                                        echo "<option value=''>-- Pilih --</option>";
                                        foreach ($golongan->result() as $option) {
                                            if ($option->masa_kerja != NULL) {
                                                $note = " - $option->masa_kerja ( $option->note )";
                                            } else {
                                                $note = "";
                                            }
                                            if ($data->recid_golongan == $option->recid_golongan) {
                                                echo "<option value='$option->recid_golongan' selected='selected'>$option->nama_golongan $note </option>";
                                            } else {
                                                echo "<option value='$option->recid_golongan'>$option->nama_golongan $note</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"> <button type="button" class="btn btn-primary">Cancel</button></a>
                                    <button id="send" type="submit" class="btn btn-success">Generate SK</button>
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


<script>
    $(document).ready(function() {

    });
</script>
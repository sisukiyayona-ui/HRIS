<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Tambah Nomor Dokumen Eksternal Corsec</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><a href="<?php echo base_url() ?>Karyawan/legal_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Nomor Dokumen Eksternal Corsec</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- Content Form -->
                        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/docsecre_pinsert" novalidate>

                            <!-- <span class="section">Personal Info</span>-->
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" id="recid_role" name="recid_role" type="hidden" value="<?php echo $role; ?>">
                                    <select name="recid_karyawan" id="karyawan" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" <?php if ($role == '1') { ?> onchange=get_dept(); <?php } ?> </select></select>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal"> Department <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" id="recid_struktur" name="recid_struktur" type="hidden" value="<?php echo $recid_struktur ?>">
                                    <input class="form-control col-md-7 col-xs-12" name="nama_struktur" id="struktur_name" placeholder="Dept Name" required="required" type="text" value="<?php echo $nama_struktur ?>" readonly>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Tujuan <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" name="tujuan" placeholder="Tujuan Dokumen Eksternal" required="required" type="text">
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Perihal
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="deskripsi" required="required" placeholder="Perihal" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <a href="<?php echo base_url() ?>Karyawan/docrecre_view"><button type="button" class="btn btn-primary">Cancel</button></a>
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
<script type="text/javascript">
    $(document).ready(function() {
        load_karyawan();
    });

    function load_karyawan() {
        role = document.getElementById('recid_role').value;
        if (role == '1') {
            urls = "<?php echo base_url(); ?>Karyawan/emp_aktif_all";
            recid_struktur = '';
        } else {
            recid_struktur = document.getElementById('recid_struktur').value;
            urls = "<?php echo base_url(); ?>Karyawan/emp_by_struktur";
        }
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: urls, // Isi dengan url/path file php yang dituju
            data: {
                recid_struktur: recid_struktur
            },
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response, data) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#karyawan").html(response.list_karyawan).show();
                $('.selectpicker').selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }

    function get_dept() {
        karyawan = document.getElementById('karyawan').value;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url(); ?>Karyawan/struktur_by_emp", // Isi dengan url/path file php yang dituju
            data: {
                karyawan: karyawan
            },
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(data, response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                document.getElementById('recid_struktur').value = data[0];
                document.getElementById('struktur_name').value = data[1];
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }
</script>
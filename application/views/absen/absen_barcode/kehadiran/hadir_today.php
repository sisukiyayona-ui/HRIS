<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Kehadiran Karyawan</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if ($this->session->flashdata('sukses')) { ?>
                    <div class="alert alert-success col-12">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('sukses'); ?>
                    </div>
                <?php } else if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger col-12">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error!</strong> <?php echo $this->session->flashdata('eror'); ?>
                    </div>
                <?php } else if ($this->session->flashdata('warning')) { ?>
                    <div class="alert alert-info col-12">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>
                    </div>
                <?php } else {
                } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">
                    <div class="x_title">
                        <form method="post">
                            <div class="item form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Tanggal<span class="required">*</span>
                                </label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <div class='input-group date' id='myDatepicker2'>
                                        <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="x_content">
                        <!-- Content Table -->
                        <table id="t_hadir" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Bagian</th>
                                    <th>Jabatan</th>
                                    <th>Golongan</th>
                                    <th>Penempatan</th>
                                    <th>Tanggal Kerja</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                                $no = 0;
                                foreach ($absen->result() as $data) {
                                    $bagian = $data->indeks_hr;
                                    $bagian = $bagian ? substr($bagian, strpos($bagian, " ") + 1) : "";

                                    $jabatan = $data->indeks_jabatan;
                                    $jabatan = $jabatan ? substr($jabatan, strpos($jabatan, " ") + 1) : "";

                                    $golongan = $data->nama_golongan;
                                    $golongan = $golongan ? substr($golongan, strpos($golongan, " ") + 1) : "";

                                    $struktur = $data->nama_struktur;
                                    $struktur = $struktur ? substr($struktur, strpos($struktur, " ") + 1) : "";

                                    if ($data->recid_bag == 0) {
                                        $bagian = "-";
                                    } else {
                                        if ($role == '1' or $role == '2') {
                                            $bagian = $data->indeks_hr;
                                        } else {
                                            $bagian = $bagian;
                                        }
                                    }

                                    if ($data->recid_jbtn == 0) {
                                        $jabatan = "-";
                                    } else {
                                        if ($role == '1' or $role == '2') {
                                            $jabatan = $data->indeks_jabatan;
                                        } else {
                                            $jabatan = $jabatan;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $no = $no + 1 ?></td>
                                        <td><?php echo $data->nik ?></td>
                                        <td><?php echo $data->nama_karyawan ?></td>
                                        <td><?php echo $bagian ?></td>
                                        <td><?php echo $jabatan ?></td>
                                        <td><?php echo $golongan ?></td>
                                        <td><?php echo $data->penempatan ?></td>
                                        <td><?php echo $data->tanggal ?></td>
                                        <td><?php echo $data->jam_masuk ?></td>
                                        <td><?php echo $data->jam_keluar ?></td>
                                        <td><?php echo $data->ket_validasi ?></td>
                                        <td><?php echo $data->keterangan . " - " . $data->jenis ?></td>
                                    <?php } ?>
                                    <!-- looping foreach -->
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

<script type="application/javascript">
    $(document).ready(function() {


        var table = $('#t_hadir').DataTable({
            "responsive": false,
            "ordering": false,
            // "order": [[ 2, "desc" ]],
            "paging": true,
            "pageLength": 30,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ],
        });
    });
    /** After windod Load */
    $(window).bind("load", function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
                <?php unset($_SESSION['sukses']); ?>
                <?php unset($_SESSION['eror']); ?>
                <?php unset($_SESSION['warning']); ?>
            });
        }, 3000);
    });

    function updateData(id) {
        jenis_id = "jenis" + id;
        jenis = document.getElementById(jenis_id).value;
        // alert(jenis);
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url(); ?>AbsenBarcode/update_absen", // Isi dengan url/path file php yang dituju
            data: {
                recid_karyawan: id,
                jenis_absen: jenis
            }, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response, data) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                // $("#karyawan").html(response.list_kota).show();
                location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }

    function getData() {
        tgl_mulai = document.getElementById("tgl_mulai").value;
        var table = $('#t_hadir').DataTable();
        table.destroy();
        var table = $('#t_hadir').DataTable({
            "responsive": false,
            "ordering": false,
            // "order": [[ 2, "desc" ]],
            "paging": true,
            "pageLength": 30,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ],
            "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>AbsenBarcode/get_hadir_periode",
                dataType: 'JSON',
                data: {
                    tgl_mulai: tgl_mulai
                },
            },
        });
    }

    function closing_kehadiran() {
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url(); ?>Absen/closing_kehadiran", // Isi dengan url/path file php yang dituju
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response, data) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                // $("#karyawan").html(response.list_kota).show();
                location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }

    function open_kehadiran() {
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url(); ?>Absen/open_kehadiran", // Isi dengan url/path file php yang dituju
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response, data) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                // $("#karyawan").html(response.list_kota).show();
                location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }
</script>
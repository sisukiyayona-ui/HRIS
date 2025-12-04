<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Data Golongan</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#golongan">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!--Add content to the page ...-->
                        <!-- Content Table -->
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Golongan</th>
                                    <th>Masa Kerja</th>
                                    <th>Klasifikasi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                                foreach ($golongan->result() as $data) {?>
                                    <tr>
                                        <td><?php echo $data->nama_golongan ?></td>
                                        <td><?php echo $data->masa_kerja ?></td>
                                        <td><?php echo $data->klasifikasi ?></td>
                                        <td><?php echo $data->note ?></td>
                                        <td><?php echo ($data->is_delete == '1') ? "Not Active" : 'Active'; ?></td>
                                        <td><center>
                                         <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                                            <a data-recid_golongan="<?php echo $data->recid_golongan ?>" data-nama_golongan="<?php echo $data->nama_golongan ?>" data-masa_kerja="<?php echo $data->masa_kerja ?>" <a data-recid_golongan="<?php echo $data->recid_golongan ?>" data-nama_golongan="<?php echo $data->nama_golongan ?>" data-klasifikasi="<?php echo $data->masa_kerja ?>" data-note="<?php echo $data->note ?>" data-toggle="modal" data-target="#edit_golongan">
                                            <button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a>&nbsp;&nbsp;&nbsp;
                                            <?php if($data->is_delete == '1'){?>
                                                <a href="<?php echo base_url()?>Karyawan/golongan_active/<?php echo $data->recid_golongan?>">
                                                <button class='btn btn-warning btn-xs'><span class='fa fa-check'></button>&nbsp;</a>
                                            <?php }else{ ?>
                                                <a href="<?php echo base_url()?>Karyawan/golongan_delete/<?php echo $data->recid_golongan?>">
                                                <button class='btn btn-danger btn-xs'><span class='fa fa-times'></button>&nbsp;</a>
                                            <?php } ?>
                                        <?php } ?>
                                            </td>
                                                </tr>
                                        <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                        <!--/ Content Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id='golongan'>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Tambah Data Golongan</h4>
            </div>
            <div class="modal-body">
                <!-- Content Modal -->
                <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/golongan_pinsert" novalidate>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama Golongan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_golongan" placeholder="Nama Golongan" required="required" type="text">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tingkatan">Masa Kerja
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="text" name="masa_kerja" class="form-control col-md-7 col-xs-12" placeholder="Masa Kerja">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Klasifikasi <spanclass="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="klasifikasi" class="form-control col-md-7 col-xs-12" required="required">
                                <option value="">-- Pilih --</option>
                                <option value="BOD">BOD</option>
                                <option value="STAF">STAF</option>
                                <option value="NON STAFF">NON STAFF</option>
                                <option value="SPM">SPM</option>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12" name='note' placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                    <!--/ Content Modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value='Save changes'>
                </form>
            </div>

        </div>
    </div>
</div>
<!--/ Modal Tambah Data -->

<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_golongan">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edit Data Golongan</h4>
            </div>
            <div class="modal-body">
                <!-- Content Modal -->
                <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/golongan_update" novalidate>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama Golongan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="erecid_golongan" type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_golongan" readonly>
                            <input id="enama_golongan" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_golongan" placeholder="Nama Golongan" required="required" type="text">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Masa Kerja
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="emasa_kerja" name="masa_kerja" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Klasifikasi <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select id="eklasifikasi" name="klasifikasi" class="form-control col-md-7 col-xs-12">
                                <option value="">-- Pilih --</option>
                                <option value="BOD">BOD</option>
                                <option value="STAF">STAF</option>
                                <option value="NON STAFF">NON STAFF</option>
                                <option value="SPM">SPM</option>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12" name='note' id='enote' placeholder='Keterangan'></textarea>
                        </div>
                    </div>
                    <!--/ Content Modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value='Save changes'>
                </form>
            </div>

        </div>
    </div>
</div>
<!--/ Modal Edit Data -->


<!-- /page content -->

<script>
    $(document).ready(function() {
        // Untuk sunting
        $('#edit_golongan').on('show.bs.modal', function(event) {
            var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
            var modal = $(this)
            var a = div.data('top');
            // Isi nilai pada field
            modal.find('#erecid_golongan').attr("value", div.data('recid_golongan'));
            modal.find('#enama_golongan').attr("value", div.data('nama_golongan'));
            modal.find('#emasa_kerja').attr("value", div.data('masa_kerja'));
            modal.find('#klasifiasi').attr("value", div.data('klasifiasi'));
            // $("#etop").val(div.data('top'));
            $("#enote").val(div.data('note'));
        });
    });
</script>
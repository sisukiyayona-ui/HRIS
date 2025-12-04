<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<?php $user = $this->session->userdata('kar_id'); ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Nomor Dokumen Eksternal Corsec</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/doc_insert">
                            <i class="fa fa-plus"></i> | Tambah Data
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!--Add content to the page ...-->
                        <!-- Content Table -->
                        <div class="table-responsive">
                            <table id="t_rec" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Dokumen</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal</th>
                                        <th>Tujuan</th>
                                        <th>Perihal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    foreach ($doc->result() as $l) { ?>
                                        <tr>
                                            <td><?php echo $no = $no + 1 ?></td>
                                            <td><?php echo $l->no_doc ?></td>
                                            <td><?php echo $l->nama_karyawan ?></td>
                                            <td><?php echo $l->nama_struktur ?></td>
                                            <td><?php echo $l->tujuan ?></td>
                                            <td><?php echo $l->deskripsi ?></td>
                                            <td><?php if ($role == '1') { ?>
                                                    <a href="<?php echo base_url() ?>Karyawan/docsecre_update/<?php echo $l->recid_doc ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                                                    <?php } else {
                                                    if ($recid_struktur == $l->recid_struktur) { ?>
                                                        <a href="<?php echo base_url() ?>Karyawan/docsecre_update/<?php echo $l->recid_doc ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>

                                                <?php }
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!--/ Content Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
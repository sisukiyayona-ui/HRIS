<?php $role = $this->session->userdata('role_id'); ?>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Dashboard Absensi</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">


                <div class="row tile_count" style="margin-left: 10px;">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
                        <div class="count"><?php echo $totkar->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/list_total_karyawan/">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Belum Konfirmasi</span>
                        <div class="count"><?php echo $totblm->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/total_blm_absen/<?php echo date('Y-m-d') ?>/All">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Konfirmasi Kehadiran</span>
                        <div class="count"><?php echo $tothadir->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/All">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Non Shift</span>
                        <div class="count"><?php echo $non_shift->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/non_shift">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Shift</span>
                        <div class="count"><?php echo $shift->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/shift">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> WFH</span>
                        <div class="count green"><?php echo $wfh->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/wfh">Details</a></span>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Sakit</span>
                        <div class="count"><?php echo $sakit->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/sakit">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Cuti</span>
                        <div class="count"><?php echo $cuti->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/cuti">Details</a></span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Lainnya</span>
                        <div class="count"><?php echo $lainnya->num_rows() ?></div>
                        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/lainnya">Details</a></span>
                    </div>
                    <?php
                    if ($role  == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25') { ?>
                        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> Hadir Baros</span>
                            <div class="count"><?php echo $baros->num_rows() ?></div>
                            <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran/<?php echo date('Y-m-d') ?>/baros">Details</a></span>
                        </div>
                    <?php  }
                    ?>
                </div>

                <?php
                if ($role == '1' or $role == '2' or $role == '3' or $role == '26') { ?>
                    <div class="row tile_count" style="margin-left: 10px;">
                        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> Total Karyawan SPM</span>
                            <div class="count"><?php echo $totkar_spm->num_rows() ?></div>
                            <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/All/spm">Details</a></span>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-clock-o"></i> Hadir</span>
                            <div class="count"><?php echo $hadir_spm->num_rows() ?></div>
                            <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/hadir_spm/spm">Details</a></span>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> WFH SPM</span>
                            <div class="count green"><?php echo $wfh_spm->num_rows() ?></div>
                            <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/wfh/spm">Details</a></span>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> Tidak Hadir SPM</span>
                            <div class="count"><?php echo $tidak_hadir_spm->num_rows() ?></div>
                            <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>AbsenBarcode/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/tidak_hadir_spm/spm">Details</a></span>
                        </div>
                    </div>
                <?php } ?>



            </div>
        </div>

    </div>
</div>
<!-- /page content -->
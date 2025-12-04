<?php $role = $this->session->userdata('role_id'); ?>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Closing Absensi <?php echo date("D d M Y"); ?></h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-check"></i>
                            </div>
                            <div class="count"><?php echo $closed->num_rows(); ?></div>

                            <h3>Closed</h3>
                            <p><a target="__blank" href="<?php echo base_url() ?>Absen/rekap_admin_open">Details</a></p>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-times"></i>
                            </div>
                            <div class="count"><?php echo $open->num_rows(); ?></div>

                            <h3>Open</h3>
                            <p><a target="__blank" href="<?php echo base_url() ?>Absen/rekap_admin_closing">Details</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<!-- /page content -->
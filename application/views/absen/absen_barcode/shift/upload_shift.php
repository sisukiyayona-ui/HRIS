<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Preview Upload Shift Karyawan</h3>
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
                <div class="x_panel">
                    <div class="x_title">
                        <form action="<?php echo base_url() ?>AbsenBarcode/import_shift" method="POST" enctype="multipart/form-data">
                            <input type="file" class="form-control" name="file_exc"><br>
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </form>
                    </div>
                    <div class="x_content">
                        <!-- Content Table -->

                        <!--/ Content Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script type="application/javascript">
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
</script>
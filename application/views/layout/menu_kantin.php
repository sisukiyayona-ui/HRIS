<?php
$role = $this->session->userdata('role_id');
$as_user = $this->session->userdata('as_user');
if ($as_user == "CINT") {
    foreach ($cek_usr as $user) {
        $nama = $user->nama_karyawan;
        $bagian = $user->indeks_hr;
        $jabatan = $user->indeks_jabatan;
        $tingkatan = $user->tingkatan;
        $struktur = $user->recid_struktur;
    }
} else {
    foreach ($cek_usr as $user) {
        $nama = $user->guest_name;
        $bagian = "B.Kantin";
        $jabatan = "J.Kantin";
        $tingkatan = "0";
        $struktur = "0";
    }
}

?>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo base_url() ?>Kantin" class="site_title"><i class="fa fa-leaf"></i> <span>HRIS</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <?php
                            if ($this->session->userdata('foto') == '') { ?>
                                <img src="<?php echo base_url() ?>images/user.png" alt="..." class="img-circle profile_img">
                            <?php } else { ?>
                                <img src="<?php echo base_url() ?>images/foto/<?php echo $this->session->userdata('foto') ?>" alt="..." class="img-circle profile_img">
                            <?php   }
                            ?>
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?php echo $this->session->userdata('nama'); ?></h2>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="<?php echo base_url() ?>Kantin"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                                <?php $alias = $this->session->userdata('recid_login');
                                if ($alias == "K001") { ?>
                                    <li><a href="<?php echo base_url() ?>Kantin/scan"><i class="fa fa-barcode"></i> Scan </a></li>
                                    <li><a href="<?php echo base_url() ?>Kantin/manual_input"><i class="fa fa-pencil"></i> Manual </a></li>
                                <?php } else { ?>
                                    <li><a href="<?php echo base_url() ?>Kantin/scan_baros"><i class="fa fa-barcode"></i> Scan Baros </a></li>
                                    <li><a href="<?php echo base_url() ?>Kantin/manual_input_baros"><i class="fa fa-pencil"></i> Manual Baros</a></li>
                                <?php  } ?>
                                <!-- <li><a href="<?php echo base_url() ?>Kantin/scan"><i class="fa fa-barcode"></i> Scan </a></li> -->
                                <!-- <li><a href="<?php echo base_url() ?>Kantin/manual_input"><i class="fa fa-pencil"></i> Manual </a></li> -->
                                <li><a><i class="fa fa-cutlery"></i>Report Kantin <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_karyawan">Rekap Makan Per Karyawan</a></li>
                                        <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_bagian">Rekap Makan Per Bagian</a></li>
                                        <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_compare">Rekap Kantin Compare</a></li>
                                        <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_manual">Rekap Kantin Manual</a></li>
                                    </ul>
                            </ul>
                            </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    if ($this->session->userdata('foto') == '') { ?>
                                        <img src="<?php echo base_url() ?>images/user.png" alt=""><?php echo $this->session->userdata('nama') ?>
                                    <?php } else { ?>
                                        <img src="<?php echo base_url() ?>images/foto/<?php echo $this->session->userdata('foto') ?>" alt=""><?php echo $this->session->userdata('role_name') ?>
                                    <?php   } ?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a data-recid_login3="<?php echo $this->session->userdata('recid_login') ?>" data-username3="<?php echo $this->session->userdata('username') ?>" data-password3="<?php echo $this->session->userdata('password') ?>" data-toggle="modal" data-target="#edit_password">
                                            <i class="fa fa-cogs pull-right"></i> Ganti Password</a></li>
                                    <li><a href="<?php echo base_url() ?>Auth/keluar"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- Modal Edit Data -->
            <!--/ Modal Edit Password By Self -->
            <!-- Large modal -->
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_password">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Edit Account</h4>
                        </div>
                        <div class="modal-body">
                            <!-- Content Modal -->
                            <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Karyawan/user_change2" novalidate>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="eusername3" class="form-control col-md-7 col-xs-12" name="username" required="required" type="text">
                                        <input id="erecid_login3" class="form-control col-md-7 col-xs-12" name="recid_login" required="required" type="hidden">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="epassword3" class="form-control col-md-7 col-xs-12" name="password2" type="hidden">
                                        <input id="epasswor4" class="form-control col-md-7 col-xs-12" name="password" placeholder="Password (isi bila ingin diubah)" type="password">
                                    </div>
                                </div>
                                <!--/ Content Modal -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Save Change">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!--/ Modal Edit Data -->

            <script>
                $(document).ready(function() {
                    //retrieve data for edit user
                    $('#edit_password').on('show.bs.modal', function(event) {
                        var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
                        var modal = $(this)

                        // Isi nilai pada field
                        modal.find('#erecid_login3').attr("value", div.data('recid_login3'));
                        modal.find('#eusername3').attr("value", div.data('username3'));
                        modal.find('#epassword3').attr("value", div.data('password3'));
                        $("#eusername3").change(function() {
                            var username = document.getElementById("eusername3").value;
                            $.ajax({ //---------------------------------------- cek total lembur --------------------------------------------------------
                                type: "POST", // 
                                url: "<?php echo base_url(); ?>Karyawan/cek_uname",
                                data: {
                                    username: username
                                },
                                dataType: "json",
                                beforeSend: function(e) {
                                    if (e && e.overrideMimeType) {
                                        e.overrideMimeType("application/json;charset=UTF-8");
                                    }
                                },
                                success: function(response, data) { // Ketika proses pengiriman berhasil
                                    if (response == "1") {
                                        alert("Username alrady exist, please insert the  other one");
                                        location.reload();
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                                }
                            });
                        });
                    });
                });
            </script>
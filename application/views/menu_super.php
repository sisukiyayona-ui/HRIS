<?php $role = $this->session->userdata('role_id'); ?>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo base_url()?>Karyawan/dash" class="site_title"><i class="fa fa-leaf"></i> <span>HRIS</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <?php 
              if($this->session->userdata('foto') == ''){ ?> 
               <img src="<?php echo base_url()?>images/user.png" alt="..." class="img-circle profile_img">
             <?php }else{ ?>
               <img src="<?php echo base_url()?>images/foto/<?php echo $this->session->userdata('foto') ?>" alt="..." class="img-circle profile_img">
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
             <li><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-dashboard"></i> Dashboard </a></li>
             <!-- <li><a target="_blank" href="../../../ROC/Karyawan/get_data"><i class="fa fa-dashboard"></i> TES </a></li> -->
             <?php  if($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '4' or $role == '22'){ ?> 
              <li><a><i class="fa fa-users"></i> Human Resources <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta">Biodata Karyawan</a></li>
                  <?php  if($role == '1'  or $role == '2' ){ ?> 
                    <li><a href="<?php echo base_url()?>Karyawan/recruitment_view">Recruitment</a></li>
                  <?php } ?>
                  <?php  if($role == '1'  or $role == '2' ){ ?> 
                    <li><a href="<?php echo base_url()?>Karyawan/training_view">Training Karyawan</a></li>
                  <?php } ?>
                  <?php  if($role == '1' or $role == '2' or $role == '5' or $role == '3' or $role == '4' or $role == '22'){ ?> 
                    <li><a href="<?php echo base_url()?>Karyawan/r_dinamis">Dinamis Data Karyawan</a></li>
                  <?php } ?>
                  <?php  if($role == '1' or $role == '2' or $role == '5' or $role == '3'  or $role == '4' or $role == '22'){ ?> 
                    <li><a href="<?php echo base_url()?>Karyawan/r_totspm">Dinamis Data SPM</a></li>
                  <?php } ?>
                  <?php  if($role == '1' or $role == '2' or $role == '5' or $role == '22'){ ?> 
                   <li><a href="<?php echo base_url()?>Karyawan/karir_view">Data Karir</a></li>
                   <li><a href="<?php echo base_url()?>Karyawan/r_hr">Rekap Karyawan</a></li>
                   <li><a href="<?php echo base_url()?>Karyawan/r_hc">Report HC</a></li>
                 <?php } ?>
                 <?php  if($role == '1' or $role == '5'){ ?> 
                  <li><a href="<?php echo base_url()?>Karyawan/data_payroll">Data Upah</a></li> <!-- data karyawan berdasarkan filter -->
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
          <?php  if($role == '1' or $role == '3' or $role == '5' or $role == '22'){ ?>
            <li><a><i class="fa fa-check-square-o"></i> Absen Karyawan <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <?php  if($role == '1' or $role == '3' or $role == '22'){ ?> 
                 <!--  <li><a href="<?php echo base_url()?>Karyawan/absen_tarik">Tarik Data Absen</a></li>
                  <li><a href="<?php echo base_url()?>Karyawan/absen_pulangcepat">Pulang Cepat</a></li>
                  <li><a href="<?php echo base_url()?>Karyawan/absen_adjust">Adjustment Absen</a></li>
                  <li><a href="<?php echo base_url()?>Karyawan/absen_view">Tidak Hadir Karyawan</a></li> -->
                <?php } ?>
                <li><a href="<?php echo base_url()?>Absen/ganti_hari">Ganti Hari</a></li>
                <li><a href="<?php echo base_url()?>Absen/r_absensi">Report Kehadiran</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/tidak_lengkap">Report Absen Tidak Lengkap</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/r_notexist">Report Tidak Absen</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/r_jmangkir">Rekap Tidak Hadir</a></li>
                <li><a href="<?php echo base_url()?>Absen/r_diagnosa">Report Diagnosa</a></li>
                <li><a href="<?php echo base_url()?>Absen/r_persentase">Report Persentase Kehadiran</a></li>
              </ul>
            </li>
          <?php } ?>
          <?php  if($role == '1' or $role == '4' or $role == '22'){ ?>
            <li><a><i class="fa fa-bank"></i> Legal <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="<?php echo base_url()?>Karyawan/legal_view">Perjanjian & Perizinan</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/r_legal">Report</a></li>
              </ul>
            </li>
          <?php } ?>
          <?php  if($role == '1' or $role == '5' or $role == '6' or $role == '7' or $role == '8' or $role == '9' or $role == '10' or $role == '11' or $role == '12' or $role == '13' or $role == '14' or $role == '15' or $role == '16' or $role == '17' or $role == '18' or $role == '19' or $role == '20' or $role == '21' or $role == '22'){ ?>
            <li><a><i class="fa fa-dollar"></i> Budget Lembur <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
               <?php  if($role == '1' or $role == '7' or $role == '5' or $role == '22'){ ?>
                <li><a href="<?php echo base_url()?>Karyawan/cutoff_view">Cut Off Lembur</a></li>
              <?php } ?>
              <?php  if($role == '1' or $role == '7' or $role == '22'){ ?>
                <li><a href="<?php echo base_url()?>Karyawan/masterbudget_view">Master Budget Lembur</a></li>
              <?php } ?>
              <?php  if($role == '1' or $role == '6' or $role == '5' or $role == '8' or $role == '9' or $role == '7' or $role == '8' or $role == '9' or $role == '10' or $role == '11' or $role == '12' or $role == '13' or $role == '14' or $role == '15' or $role == '16' or $role == '17' or $role == '18'  or $role == '19' or $role == '20' or $role == '21' or $role == '22'){ ?>
                <!-- <li><a href="<?php echo base_url()?>Karyawan/pekerjaan_view">Pekerjaan</a></li> -->
                <li><a href="<?php echo base_url()?>Karyawan/plembur_view">Pengajuan Lembur</a></li>
              <?php } ?>
              <?php  if($role == '1' or $role == '5' or $role == '7'){ ?>
                <li><a href="<?php echo base_url()?>Karyawan/realisasi_view">Realisasi Lembur</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/fast_view">Pengajuan - Realisasi</a></li>
                <li><a href="<?php echo base_url()?>Karyawan/plembur_crash">Pengajuan Gantung</a></li>
              <?php } ?>
              <li><a href="<?php echo base_url()?>Karyawan/r_pengajuan">Report Pengajuan</a></li>
              <li><a href="<?php echo base_url()?>Karyawan/r_realisasi">Report Realisasi</a></li>
              <li><a href="<?php echo base_url()?>Karyawan/r_master_budget">Report Master Budget</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php  if($role == '1' or $role == '6' or $role == '22'){ ?>
          <li><a><i class="fa fa-bar-chart"></i> All Report <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo base_url()?>Karyawan/resume_karyawan">Resume Karyawan</a></li> <!-- kaya cv berisi identitas dan karir selama di chitose -->
              <li><a href="<?php echo base_url()?>Karyawan/report_training">Training Karyawan</a></li> <!-- data training -->
              <li><a href="<?php echo base_url()?>Karyawan/report_hr">Statistik Karyawan</a></li> <!-- data jumlah karyawan (tabel) -->
              <li><a href="<?php echo base_url()?>Karyawan/report_hc">Report HC</a></li> <!-- data karyawan berdasarkan filter -->
              <li><a href="<?php echo base_url()?>Karyawan/report_absensi">Rekapitulasi Absensi</a></li>  <!-- bentuk chart -->
              <li><a href="<?php echo base_url()?>Karyawan/report_legal">Rekapitulasi Perjanjian Legal</a></li>   <!-- bentuknya kaya repositori pencarian dokumen -->
            </ul>
          </li>
        <?php } ?>
      </ul>
    </div>
    <?php
    if($role == '1' or $role == '5' or $role == '22' ){ ?> 
      <div class="menu_section">
        <h3>Settings</h3>
        <ul class="nav side-menu">
         <?php  if($role == '1'){ ?>
          <li><a><i class="fa fa-key"></i> Login <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo base_url()?>Karyawan/user_view">User</a></li>
              <li><a href="<?php echo base_url()?>Karyawan/role_view">Role User</a></li>
            </ul>
          </li>
        <?php  } ?>
        <?php  if($role == '1' or $role == '5' or $role == '22'){ ?>
          <li><a><i class="fa fa-wrench"></i> Parameter <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo base_url()?>Karyawan/bagian_view">Bagian</a></li>
              <li><a href="<?php echo base_url()?>Karyawan/struktur_view">Struktur</a></li>
              <li><a href="<?php echo base_url()?>Karyawan/jabatan_view">Jabatan</a></li>
            </ul>
          </li>
        </ul>
      </div>
    <?php  } ?>
  <?php  } ?>

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
            if($this->session->userdata('foto') == ''){ ?> 
              <img src="<?php echo base_url()?>images/user.png" alt=""><?php echo $this->session->userdata('nama') ?>
            <?php }else{ ?>
              <img src="<?php echo base_url()?>images/foto/<?php echo $this->session->userdata('foto') ?>" alt=""><?php echo $this->session->userdata('role_name') ?>
            <?php   } ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a 
              data-recid_login3="<?php echo $this->session->userdata('recid_login') ?>"
              data-username3="<?php echo $this->session->userdata('username') ?>"
              data-password3="<?php echo $this->session->userdata('password') ?>"
              data-toggle="modal" data-target="#edit_password">
              <i class="fa fa-cogs pull-right"></i> Ganti Password</a></li>
              <li><a href="<?php echo base_url()?>Auth/keluar"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
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
          <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/user_change2" novalidate >
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
                <input id="epassword3" class="form-control col-md-7 col-xs-12"  name="password2" type="hidden">
                <input id="epasswor4" class="form-control col-md-7 col-xs-12"  name="password" placeholder="Password (isi bila ingin diubah)" type="password">
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
   $('#edit_password').on('show.bs.modal', function (event) {
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal          = $(this)

              // Isi nilai pada field
              modal.find('#erecid_login3').attr("value",div.data('recid_login3'));
              modal.find('#eusername3').attr("value",div.data('username3'));
              modal.find('#epassword3').attr("value",div.data('password3'));
              $("#eusername3").change(function(){
               var username = document.getElementById("eusername3").value;
                 $.ajax({  //---------------------------------------- cek total lembur --------------------------------------------------------
                      type: "POST", // 
                      url: "<?php echo base_url();?>Karyawan/cek_uname", 
                      data: {username : username}, 
                      dataType: "json",
                      beforeSend: function(e) {
                        if(e && e.overrideMimeType) {
                          e.overrideMimeType("application/json;charset=UTF-8");
                        }
                      },
                      success: function(response, data){ // Ketika proses pengiriman berhasil
                        if(response == "1"){
                          alert("Username alrady exist, please insert the  other one");
                          location.reload();
                        }
                      },
                      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                      }
                    });
               });
            });
 });
</script>



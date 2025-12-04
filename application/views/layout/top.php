<?php $role = $this->session->userdata('role_id'); ?>
<?php $recid_login = $this->session->userdata('recid_login'); ?>
 <body class="sidebar-fixed sidebar-dark header-light header-fixed" id="body">
   
    <div class="mobile-sticky-body-overlay"></div>

    <div class="wrapper">
      
              <!--
          ====================================
          ——— LEFT SIDEBAR WITH FOOTER
          =====================================
        -->
        <aside class="left-sidebar bg-sidebar">
          <div id="sidebar" class="sidebar sidebar-with-footer">
            <!-- Aplication Brand -->
            <div class="app-brand" style="padding:10px;">
                <img src="<?php echo base_url()?>assets/template/src/assets/img/im-chivid.gif" class="user-image" alt="User Image" />
                <span class="brand-name">Covid19 Tracking</span>
            </div>

         <!-- begin sidebar scrollbar -->
            <div class="sidebar-scrollbar">
             <input type="hidden" id="active_menu" value="<?php echo $menu ?>">
              <!-- sidebar menu -->
              <ul class="nav sidebar-inner" id="sidebar-menu">
                <?php if($role == '1' or $role == '2'){ ?>
                 <li id="link0" class="has-sub">
                    <a class="sidenav-item-link"  href="<?php echo base_url()?>Master/index">
                      <i class="mdi mdi-account-question"></i>
                      <span class="nav-text">Question Master</span>
                    </a>
                  </li>
                <?php } ?>
                 <?php if($role == '1' or $role == '2' or $role == '4' or $role == '5'){ ?>
                  <li id="link1" class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#karyawan"
                      aria-expanded="false" aria-controls="karyawan">
                      <i class="mdi mdi-format-list-checks"></i>
                      <span class="nav-text">Questioner Karyawan</span> <b class="caret"></b>
                    </a>
                    <ul  class="collapse"  id="karyawan">
                      <div class="sub-menu">

                        <li >
                          <a href="<?php echo base_url()?>Tracking">Dashboard Karyawan</a>
                        </li>

                        <li >
                          <a href="<?php echo base_url()?>Tracking/questioner">Questioner Karyawan </a>
                        </li>
                        <?php if($role == '1')
                        { ?>
                          <li >
                            <a href="<?php echo base_url()?>Tracking/questioner_list">Questioner List</a>
                          </li>
                        <?php }?>
                      </div>
                    </ul>
                  </li>
                <?php } ?>
                <?php if($role == '1' or $role == '2'){ ?>
                  <li id="link2" class="has-sub" >
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#tamu"
                      aria-expanded="false" aria-controls="tamu">
                      <i class="mdi mdi-format-list-checks"></i>
                      <span class="nav-text">Questioner Tamu</span> <b class="caret"></b>
                    </a>
                    <ul  class="collapse"  id="tamu">
                      <div class="sub-menu">

                        <li >
                          <a href="<?php echo base_url()?>Assesment/dashboard">Dashboard Tamu</a>
                        </li>

                        <li >
                          <a href="<?php echo base_url()?>Assesment">Questioner Tamu </a>
                        </li>
                        
                         <li >
                          <a href="<?php echo base_url()?>Assesment/questioner_list">Questioner List Tamu</a>
                        </li>
                      </div>
                    </ul>
                  </li>
                <?php } ?>
                <?php if($role == '1'){ ?>
                   <li id="link3" class="has-sub" >
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#rups"
                      aria-expanded="false" aria-controls="rups">
                      <i class="mdi mdi-format-list-checks"></i>
                      <span class="nav-text">Questioner RUPS</span> <b class="caret"></b>
                    </a>
                    <ul  class="collapse"  id="rups">
                      <div class="sub-menu">

                        <li >
                          <a href="<?php echo base_url()?>RUPS2021/dashboard">Dashboard RUPS</a>
                        </li>

                        <li >
                          <a href="<?php echo base_url()?>RUPS2021">Questioner RUPS </a>
                        </li>

                         <li >
                          <a href="<?php echo base_url()?>RUPS2021/questioner_list">Questioner List RUPS</a>
                        </li>
                      </div>
                    </ul>
                  </li>
                <?php } ?>
                <?php if($role == '1' or $role == '2' or $role == '4' or $role == '5'){ ?>
                  <li id="link4" class="has-sub" >
                    <a class="sidenav-item-link" href="<?php echo base_url()?>Auth/user_list">
                      <i class="mdi mdi-account-check"></i>
                      <span class="nav-text">User Login</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if($role == '3')
                { ?>
                  <li id="link5" class="has-sub">
                    <a class="sidenav-item-link"  href="<?php echo base_url()?>Tracking/questioner">
                     <i class="mdi mdi-format-list-checks"></i>
                     <span class="nav-text">Questioners</span>
                   </a>
                 </li>
               <?php } ?>
                 
               
              </ul>
            </div>

            <!-- <hr class="separator" /> -->

            <div class="sidebar-footer">
              
            </div>
          </div>
        </aside>

      

      <div class="page-wrapper">
                  <!-- Header -->
          <header class="main-header " id="header">
            <nav class="navbar navbar-static-top navbar-expand-lg">
              <!-- Sidebar toggle button -->
              <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
              </button>

              <div class="navbar-right ">
                <ul class="nav navbar-nav">
                   <li class="github-link mr-3">
                   </li>
                   <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <li class="dropdown notifications-menu">
                  </li>
                  <!-- User Account -->
                  <li class="dropdown user-menu">
                    <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                      <img src="<?php echo base_url()?>assets/template/src/assets/img/user/icon_user.png" class="user-image" alt="User Image" />
                      <?php $nama = $this->session->userdata('nama');
                        $user = explode(" ",$nama);
                      ?>
                      <span class="d-none d-lg-inline-block"><?php echo $user[0] ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <!-- User image -->
                      <li>
                        <a href="<?php echo base_url()?>Auth/change_password/<?php echo $recid_login ?>">
                          <i class="mdi mdi-account"></i> Change Password
                        </a>
                      </li>
                      <li class="dropdown-footer">
                        <a href="<?php echo base_url()?>Auth/logout"> <i class="mdi mdi-logout"></i> Log Out </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>


          </header>

           

        
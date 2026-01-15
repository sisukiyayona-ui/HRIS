<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  /* body{
        background: #4967d3;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    } */

  /* .password-input-box{
        position: relative;
        width: 300px;
        height: 40px;
    }

    .password-input{
        width: 100%;
        height: 100%;
        background: #fff;
        border: none;
        padding: 5px 15px;
        outline: none;
        border-radius: 5px;
        color: #d34970;
        padding-right: 45px;
    }

    .password-input::placeholder{
        color: #d34970;
    } */

  .password-input:focus {
    box-shadow: 0 0 0 3px #4967d3,
      /* 0 0 0 6px #4fe222; */
  }

  .password-input2:focus {
    box-shadow: 0 0 0 3px #4967d3,
      /* 0 0 0 6px #4fe222; */
  }

  .show-password {
    /* position: absolute;
        right: 15px;
        top: 50%; 
        transform: translateY(-50%);
        cursor: pointer;
        color: #92203f;*/
  }

  .show-password2 {
    /* position: absolute;
        right: 15px;
        top: 50%; 
        transform: translateY(-50%);
        cursor: pointer;
        color: #92203f;*/
  }

  .password-checklist {
    position: absolute;
    left: calc(100% + 10px);
    width: 50%;
    padding: 20px 30px;
    background: #808fd5;
    border-radius: 5px;
    opacity: 0;
    pointer-events: none;
    transform: translateY(20px);
    transition: .5s ease;
  }

  .password-checklist2 {
    position: absolute;
    left: calc(100% + 10px);
    width: 100%;
    padding: 20px 30px;
    background: #808fd5;
    border-radius: 5px;
    opacity: 0;
    pointer-events: none;
    transform: translateY(20px);
    transition: .5s ease;
  }

  .password-input:focus~.password-checklist {
    opacity: 1;
    transform: translateY(0);
  }

  .password-input2:focus~.password-checklist2 {
    opacity: 1;
    transform: translateY(0);
  }

  .checklist-title {
    font-size: 15px;
    color: #922037;
    margin-bottom: 10px;
  }

  .checklist-title2 {
    font-size: 15px;
    color: #922037;
    margin-bottom: 10px;
  }

  .checklist {
    list-style: none;
  }

  .checklist2 {
    list-style: none;
  }

  .list-item {
    padding-left: 30px;
    color: #fff;
    font-size: 14px;
  }

  .list-item2 {
    padding-left: 30px;
    color: #fff;
    font-size: 14px;
  }

  .list-item::before {
    content: '\f00d';
    font-family: FontAwesome;
    display: inline-block;
    margin: 8px 0;
    margin-left: -30px;
    width: 20px;
    font-size: 12px;
  }

  .list-item2::before {
    content: '\f00d';
    font-family: FontAwesome;
    display: inline-block;
    margin: 8px 0;
    margin-left: -30px;
    width: 20px;
    font-size: 12px;
  }

  .list-item.checked {
    opacity: 0.5;
  }

  .list-item2.checked {
    opacity: 0.5;
  }

  .list-item.checked::before {
    content: '\f00c';
    color: #922037;
  }

  .list-item2.checked::before {
    content: '\f00c';
    color: #922037;
  }

  .table thead th {
    background-color: #4f5793;
    color: #FFF
  }
</style>

<?php
$role = $this->session->userdata('role_id');
$recid_login = $this->session->userdata('recid_login');
$as_user = $this->session->userdata('as_user');

// Initialize variables with default values
$nama = '';
$bagian = '';
$jabatan = '';
$tingkatan = 0;
$struktur = '';

// Ensure $role has default value
if (!$role) {
    $role = '0';
}

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
            <a href="<?php echo base_url() ?>Karyawan/dash" class="site_title"><i class="fa fa-leaf"></i> <span>HRIS</span></a>
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

                <li><a href="<?php echo base_url() ?>Karyawan/dash"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                <li><a><i class="fa fa-users"></i> Human Resources <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <?php
                    if ($role != '30' and $role != '32') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta">Biodata Karyawan</a></li>
                    <?php }
                    ?>
                    <?php
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25' or $role == '31' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/tunjangan_view">Tanggungan Karyawan</a></li>
                    <?php } ?>
                    <?php
                    if ($role != '26') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/r_dinamis">Dinamis Data Karyawan</a></li>
                    <?php }
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '24' or $role == '25' or $role == '26'  or $role == '27' or $role == '28' or $role == '29' or $role == '31' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/r_totspm">Dinamis Data SPM</a></li>
                    <?php }
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '27' or $role == '28' or $role == '25' or $role == '31' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/tunjangan_dinamis">Dinamis Data Tanggungan</a></li>
                    <?php }
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5'  or $role == '25' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/karir_view">Data Karir</a></li>
                    <?php }
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/r_hr">Rekap Karyawan</a></li>
                    <?php } ?>
                    <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '31' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '34') { ?>

                      <li><a href="<?php echo base_url() ?>Karyawan/rekap_per_bagian">Rekap Per Bagian</a></li>
                    <?php }
                    if ($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '31' or $role == '24' or $role == '25' or $role == '27' or $role == '28' or $role == '29' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/setup_bagian_bulk">Pindah Bagian (Bulk)</a></li>
                    <?php }
                    if ($role == '1' or $role == '2'  or $role == '3' or $role == '5' or $role == '25' or $role == '34') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/r_hc">Report HC</a></li>
                    <?php }

                    if ($role == '5' or $role == '1' or $role == '31' or $role == '27') { ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/data_payroll">Data Upah</a></li> <!-- data karyawan berdasarkan filter -->
                    <?php } ?>
                    <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25' or $role == '27') { ?>
                      <li><a href="<?php echo base_url() ?>index.php/Karir/expedisi_karir">Ekspedisi</a></li>
                    <?php } ?>
                    <?php if ($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25') { ?>
                      <li><a href="<?php echo base_url() ?>Employee_import">Bulk Employee Import</a></li>
                    <?php } ?>

                  </ul>
                </li>
                <?php /* if ($role == '1' or $role == '2' or $role == '5' or $role == '25') { ?>
                  <li><a><i class="fa fa-bullhorn"></i> Recruitment <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php
                      if ($role == '1' or $role == '2' or $role == '5' or $role == '25') { ?>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/pelamar_view">Kandidat</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/r_disc">DISC Candidates</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/recruitment_view">Recruitment</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/assign_vacancy">Assign Vacancy</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/pelamar_match">Candidate Match</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/Recruitment/recruitment_report">Report Recruitment</a></li>
                      <?php } ?>
                    </ul>
                  </li>
                <?php } */ ?>
                <?php /* if ($tingkatan >= 6 or $role == '1' or $role == '2' or $role == '25') { ?>
                  <li><a><i class="fa fa-star"></i> Training <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>index.php/Training">Training Karyawan</a></li>
                      <li><a href="<?php echo base_url() ?>index.php/Training/rekapitulasi_training">Rekapitulasi Training</a></li>
                      <?php
                      if ($role == '1' or $role == '2' or $role == '5' or $role == '25') { ?>
                        <li><a href="<?php echo base_url() ?>index.php/Training/training_dinamis">Report Training</a></li>
                      <?php }
                      ?>
                    </ul>
                  </li>
                <?php } */ ?>


                <?php /* if ($role == '1' or $role == '3' or $role == '2' or $role == '5' or $role == '23' or $role == '25' or $role == '26' or $role == '29' or $role == '30'  or $role == '31' or $role == '32' or $role == '34' or $role == '35' or  $role == '37' or $role == '41') { ?>
                  <li><a><i class="fa fa-barcode"></i> Absen Barcode <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>AbsenBarcode/dashboard">Dashboard Kehadiran</a></li>
                      <li><a href="<?php echo base_url() ?>AbsenBarcode/hadir_today">Kehadiran</a></li>
                      <li><a href="<?php echo base_url() ?>Absen/izin">Izin</a></li>
                      <?php
                      if ($role != 34) { ?>
                        <li><a href="<?php echo base_url() ?>AbsenBarcode/tidak_hadir_today">Belum Absen</a></li>
                      <?php } ?>

                      <?php
                      if ($role == '1' or $role == '3' or $role == '5') { ?>
                        <li><a href=" <?php echo base_url() ?>AbsenBarcode/absen_masuk">Scan Masuk</a></li>
                        <li><a href="<?php echo base_url() ?>AbsenBarcode/absen_pulang">Scan Pulang</a></li>
                        <li><a href="<?php echo base_url() ?>AbsenBarcode/adjust_hadir">Adjust Kehadiran</a></li>
                        <li><a href="<?php echo base_url() ?>AbsenBarcode/shift_view">Jadwal Shift</a></li>
                      <?php }
                      ?>
                      <li><a href="<?php echo base_url() ?>Absen/absen_view">Absensi</a></li>
                      <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '25' or $role == '23' or $role == '29' or $role == '32' or $role == '37') { ?>

                        <li><a href="<?php echo base_url() ?>Absen/hari_kerja">Jumlah Hari Kerja</a></li>
                        <li><a href="<?php echo base_url() ?>Absen/libur">Libur</a></li>
                        <li><a href="<?php echo base_url() ?>Absen/ganti_hari">Ganti Hari</a></li>
                      <?php } ?>
                      <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '32' or $role == '26' or $role == '25' or $role == '23' or $role == '37') {
                        if ($role != 32 and $role != 26 and $role != '23' and $role != '29' and $role != '37') { ?>
                          <li><a href="<?php echo base_url() ?>Absen/admin_bagian">Admin Bagian</a></li>
                        <?php } ?>
                      <?php } ?>

                      <li class="sub_menu"><a href="<?php echo base_url() ?>Absen/saldo_cuti">Sisa Cuti Karyawan</a></li>
                      <li><a>Report Absensi<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <?php
                          if ($role == '1' or $role == '3' or $role == '5') { ?>
                            <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/rekap_kehadiran">Rekap Kehadiran</a></li>
                          <?php } ?>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_detail_hadir">Report Detail Kehadiran</a></li>
                          <?php if ($role == 1 or $role == 2 or $role == 3 or $role == 5 or $role == '23' or $role == '25' or $role == '29' or $role == '30 ' or $role == '34 ' or $role == '35' or $role == '37' or $role == '41') {
                            if ($role ==  1 or $role == 2 or $role == 3 or $role == 5 or $role == 25) { ?>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_bagian">Report Kehadiran Per Bagian</a></li>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_tahun">Report Kehadiran Tahunan</a></li>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_semester">Report Kehadiran Semester</a></li>
                            <?php } ?>
                            <?php if ($role != 34) { ?>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_bulan">Report Kehadiran Bulanan</a></li>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_minggu">Report Kehadiran Mingguan</a></li>

                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_produktif_bulanan">Report Produktivitas Bulanan</a></li>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>AbsenBarcode/r_produktif_mingguan">Report Produktivitas Mingguan</a></li>
                            <?php } ?>
                            <?php if ($role ==  1 or $role == 2 or $role == 3 or $role == 5 or $role == '25' or $role == 34 or $role == 37 or $role == '41') { ?>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>Absen/r_sid">Report Bulanan SID</a></li>
                            <?php } ?>
                          <?php } ?>
                        </ul>
                      </li>
                    </ul>
                  </li>
                <?php } */ ?>

                <?php if ($role == '1' or $role == '3' or $role == '2' or $role == '5' or $role == '23' or $role == '25' or $role == '26' or $role == '29' or $role == '30'  or $role == '31' or $role == '32' or $role == '34' or $role == '35' or  $role == '37' or $role == '41') { ?>
                  <li><a><i class="fa fa-fingerprint"></i> Absen Finger <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>absensi"><i class="fa fa-check"></i> Absensi</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/kehadiran_bulanan"><i class="fa fa-calendar"></i> Kehadiran Karyawan</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/history_absensi"><i class="fa fa-history"></i> Rekap Bulanan</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/absen_manual"><i class="fa fa-pencil"></i> Absen Manual</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/jadwal_shift"><i class="fa fa-clock-o"></i> Jadwal Shift</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/izin_log"><i class="fa fa-list"></i> Log Izin (Finger)</a></li>
                      <li><a href="<?php echo base_url() ?>rekap/statistik_bagian"><i class="fa fa-pie-chart"></i> Rekap Statistik Bagian</a></li>
                      <li><a><i class="fa fa-bar-chart"></i> Report Absen <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?php echo base_url()?>Reports/daily_attendance_report">Laporan Kehadiran Harian</a></li>
                          <li><a href="<?php echo base_url()?>Reports/monthly_percentage_attendance_report">REKAP PERSENTASE ABSENSI HARIAN</a></li>
                        </ul>
                      </li>
                        <li><a href="<?php echo base_url() ?>rekap/mapping_ui" style="color: #888;"><i class="fa fa-link"></i> Mapping Device</a></li>
                    </ul>
                  </li>
                <?php } ?>

                <?php /* if ($role == '1' or $role == '3' or $role == '5' or $role == '25') { ?>
                  <li><a><i class="fa fa-cutlery"></i> Kantin <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>Kantin">Dashboard</a></li>
                      <li><a href=" <?php echo base_url() ?>Kantin/scan">Scan</a></li>
                      <li><a href="<?php echo base_url() ?>Kantin/manual_input">Manual Kantin</a></li>
                      <!-- <li><a href="<?php echo base_url() ?>Kantin/manual_mess">Pantry / Mess</a></li> -->
                      <li><a href="<?php echo base_url() ?>Kantin/generate_baros">Generate Flat Kupon</a></li>
                      <li><a href="<?php echo base_url() ?>Kantin/generate_industri">Generate Kupon Industri</a></li>
                      <li><a>Report Kantin<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_karyawan">Rekap Makan Per Karyawan</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_bagian">Rekap Makan Per Bagian</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_compare">Rekap Kantin Compare</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_manual">Rekap Kantin Manual</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Kantin/r_makan">Report Makan Kantin</a></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                <?php } */ ?>

                <?php if ($role == '1' or $role == '4' or $role == '5'  or $role == '34' or $role == '25') { ?>
                  <li><a><i class="fa fa-legal"></i> Legal <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if ($role == '1' or $role == '5' or $role == '25') { ?>
                        <li><a>SK Karyawan<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <li class="sub_menu"><a href="<?php echo base_url() ?>Karir/generate_promosi">Generate SK Promosi</a></li>
                          </ul>
                        </li>
                      <?php } ?>
                      <li><a href="<?php echo base_url() ?>Karyawan/perjanjian_view">Perjanjian</a></li>
                      <li><a href="<?php echo base_url() ?>Karyawan/perizinan_view">Perizinan</a></li>
                      <li><a href="<?php echo base_url() ?>Karyawan/hki_view">HKI</a></li>
                      <li><a href="<?php echo base_url() ?>Karyawan/r_legal">Report</a></li>
                    </ul>
                  </li>
                <?php } ?>

                <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '31' or $role == '32' or $role == '6' or $role == '7'  or $role == '24' or $role == '25' or $role == '29' or $role == '30' or $role == '34' or $role == '37' or $role == '41' or  ($role == '23' and $tingkatan > 7)) { ?>
                  <li><a><i class="fa fa-dollar"></i>Lembur <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '7' or $role == '31' or $role == '25' or $role == '29') { ?>
                        <li><a href="<?php echo base_url() ?>Lembur/cutoff_view">Cut Off Lembur</a></li>
                      <?php } ?>
                      <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '7' or $role == '25' or $role == '29') { ?>
                        <li><a href="<?php echo base_url() ?>Lembur/masterbudget_view">Master Budget Lembur</a></li>
                      <?php } ?>

                      <?php if ($role == '1' or $role == '3' or $role == '6' or $role == '7'  or $role == '5' or $role == '23' or $role == '24' or $role == '25' or $role == '29' or $role == '7' or $role == '30' or $role == '31' or $role == '32' or $role == '34'  or $role == '37' or $role == '41') { ?>
                        <?php
                        if ($role != '7' and $role != '29') { ?>
                          <li><a href="<?php echo base_url() ?>Lembur/stkl_view">Lembur</a></li>
                        <?php }
                        ?>
                        <?php
                        if ($role == '1' or $role == '3' or $role == '5') { ?>
                          <li><a href="<?php echo base_url() ?>Lembur/stkl_view_delete">STKL Delete</a></li>
                        <?php }
                        if ($role == '1' or $role == '25') { ?>
                          <li><a href="<?php echo base_url() ?>Lembur/stkl_approve">STKL Approval HC</a></li>
                        <?php }
                        if ($role == '1' or $role == '24') { ?>
                          <li><a href="<?php echo base_url() ?>Lembur/stkl_approve">STKL Approval Direksi</a></li>
                        <?php } ?>
                        <li><a>Report Lembur<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <?php
                            if ($role != '7' and $role != '29') { ?>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>Lembur/stkl_karyawan">Rekap Lembur Karyawan</a></li>
                              <li class="sub_menu"><a href="<?php echo base_url() ?>Lembur/stkl_detailview">List Karyawan Lembur</a></li>
                              <li><a href="<?php echo base_url() ?>Lembur/masterbudget_tahun">Report Master Budget</a></li>
                            <?php } ?>

                            <!-- <li><a href="<?php echo base_url() ?>Lembur/r_stkl1">Realisasi Budget Pada Karyawan</a></li> -->
                            <li><a href="<?php echo base_url() ?>Lembur/r_stkl2">Realisasi Budget Pada Bagian Pemohon</a></li>
                          </ul>
                        </li>
                        <!-- <li><a href="<?php echo base_url() ?>Lembur/realisasi_view">Realisasi Lembur</a></li> -->
                      <?php }
                      if ($role == '1') { ?>
                        <!-- <li><a href="<?php echo base_url() ?>Lembur/plembur_crash">Pengajuan Gantung</a></li> -->
                      <?php } ?>
                      <!-- <li><a href="<?php echo base_url() ?>Lembur/r_pengajuan">Report Pengajuan</a></li>
                      <li><a href="<?php echo base_url() ?>Lembur/r_realisasi">Report Realisasi I</a></li>
                      <li><a href="<?php echo base_url() ?>Lembur/r_realisasi2">Report Realisasi II</a></li> -->

                    </ul>
                  </li>
                <?php } ?>
                <?php if ($role == '1' or $role == '5' or $role == '3' or $role == '7' or $role == '25' or $role == '27' or $role == '29') { ?>
                  <li><a><i class="fa fa-money"></i> Upah <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php
                      if ($role == '1' or $role == '3' or $role == '5') { ?>
                        <li><a href="<?php echo base_url() ?>Upah/adjustment_view">Adjustment Upah</a></li>
                        <li><a href="<?php echo base_url() ?>Upah/upload_potkop">Potongan Koprasi</a></li>
                      <?php } ?>
                      <li><a href="<?php echo base_url() ?>Upah/r_master">Kalkulasi Upah</a></li>
                      <li><a href="<?php echo base_url() ?>UpahCustom">Upah Baru</a></li>
                      <!-- <li><a href="<?php echo base_url() ?>Upah/r_transisi_upah">Transisi Upah</a></li> -->
                      <li><a href="<?php echo base_url() ?>Upah/download_master">Download Master</a></li>
                      <?php
                      if ($role == '1' or $role == '3' or $role == '5') { ?>
                        <li><a href="<?php echo base_url() ?>Upah/r_struk">Print Struk</a></li>
                      <?php } ?>
                      <li><a>Rapel<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?php echo base_url() ?>Upah/r_transisi_upah">Kalkulasi Rapel</a></li>
                          <li><a href="<?php echo base_url() ?>Upah/r_struk_transisi">Print Struk Rapel</a></li>
                        </ul>
                      </li>
                      <li><a>THR<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Upah/r_master_thr">Kalkulasi THR</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Upah/download_master_thr">Master THR</a></li>
                          <li class="sub_menu"><a href="<?php echo base_url() ?>Upah/r_struk_thr">Print Struk THR</a></li>
                          <li class="sub_menu"><a href="#">Cek THR By Karyawan</a></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                <?php } ?>
                <?php /* if ($role == '1') { ?>
                  <li><a><i class="fa fa-medkit"></i> Medical Reimburst <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>Medical/plafon">Plafon</a></li>
                      <li><a href="<?php echo base_url() ?>Medical/pengajuan">Pengajuan</a></li>
                      <li><a href="<?php echo base_url() ?>Medical/sisa_plafon">Sisa Plafon</a></li>
                    </ul>
                  </li>
                <?php } */ ?>
                <?php if ($role == '1' or $role == '3' or $role == '5' or $role == '32') { ?>
                  <li><a><i class="fa fa-shield"></i> Validasi Keamanan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url() ?>AbsenBarcode/list_validasi_siang">Validasi Masuk Terlambat</a></li>
                      <li><a href="<?php echo base_url() ?>AbsenBarcode/list_validasi_pulang">Validasi Pulang Cepat</a></li>
                      <!-- <li><a href="<?php echo base_url() ?>AbsenBarcode/list_validasi_izin">Validasi Izin</a></li> -->
                    </ul>
                  </li>
                <?php } ?>
                <!-- <?php
                      if ($role != '32') { ?>
                  <li><a href="<?php echo base_url() ?>Karyawan/docsecre_view"><i class="fa fa-file"></i> Dokumen Corsec </a></li>
                <?php }
                ?> -->

                <?php if ($role == '1') { ?>
                  <!-- <li><a><i class="fa fa-bar-chart"></i> All Report <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta">Resume Karyawan</a></li> // kaya cv berisi identitas dan karir selama di chitose 
             <li><a href="<?php echo base_url() ?>Karyawan/report_training">Training Karyawan</a></li>  data training
              <li><a href="<?php echo base_url() ?>Karyawan/report_hr">Statistik Karyawan</a></li>  // data jumlah karyawan (tabel) 
              <li><a href="<?php echo base_url() ?>Karyawan/report_hc">Report HC</a></li>  data karyawan berdasarkan filter
              <li><a href="<?php echo base_url() ?>Karyawan/report_absensi">Rekapitulasi Absensi</a></li>   bentuk chart
              <li><a href="<?php echo base_url() ?>Karyawan/report_legal">Rekapitulasi Perjanjian Legal</a></li>    bentuknya kaya repositori pencarian dokumen
            </ul>
          </li> -->
                <?php } ?>


              </ul>
            </div>
            <?php
            if ($role == '1' or $role == '2' or $role == '5'  or $role == '25' or $role == '27') { ?>
              <div class="menu_section">
                <h3>Settings</h3>
                <ul class="nav side-menu">
                  <?php if ($role == '1') { ?>
                    <li><a><i class="fa fa-key"></i> Login <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url() ?>Karyawan/user_view">User</a></li>
                        <li><a href="<?php echo base_url() ?>Karyawan/role_view">Role User</a></li>
                        <li><a href="<?php echo base_url() ?>Karyawan/generate_user">Generate User</a></li>
                      </ul>
                    </li>
                  <?php  } ?>
                  <?php if ($role == '1' or $role == '2'  or $role == '5'  or $role == '25' or $role == '27') { ?>
                    <li><a><i class="fa fa-wrench"></i> Parameter <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">

                        <li><a href="<?php echo base_url() ?>Karyawan/bagian_view">Bagian</a></li>
                        <?php
                        if ($role == '1' or $role == '2' or $role == '5' or $role == '25') { ?>
                          <li><a href="<?php echo base_url() ?>Karyawan/struktur_view">Departemen</a></li>
                          <li><a href="<?php echo base_url() ?>Karyawan/department_view">Direktorat</a></li>
                          <li><a href="<?php echo base_url() ?>Karyawan/jabatan_view">Jabatan</a></li>
                          <li><a href="<?php echo base_url() ?>Karyawan/golongan_view">Golongan</a></li>
                        <?php } ?>
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
                <div class="item form-group  password-input-box">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </span>
                  </label>
                  <div class="input-group">
                    <input id="epassword3" class="form-control col-md-7 col-xs-12" name="password2" type="hidden">
                    <input type="password" name="password" id="epassword" placeholder="Password" class="form-control password-input2">
                    <span class="input-group-btn">
                      <button class="btn border border-left-0" type="button"><i class="fa-solid fa-eye show-password2"></i></button>
                    </span>
                    <div class="password-checklist2">
                      <h3 class="checklist-title2">Password should be</h3>
                      <ul class="checklist2">
                        <li class="list-item2">At least 6 character long</li>
                        <li class="list-item2">At least 1 number</li>
                        <li class="list-item2">At least 1 lowercase letter</li>
                        <li class="list-item2">At least 1 uppercase letter</li>
                        <li class="list-item2">At least 1 special character</li>
                      </ul>
                    </div>
                  </div>
                  <!-- <div class="col-md-6 col-sm-6 col-xs-12">
                    
                    <input id="epasswor4" class="form-control col-md-7 col-xs-12" name="password" placeholder="Password (isi bila ingin diubah)" type="password">
                  </div> -->
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
          $('[data-toggle="tooltip"]').tooltip()
          /* ----------------- EDIT ------------------------------- */

          let showPasswordBtn2 = document.querySelector('.show-password2');
          let passwordInp2 = document.querySelector('.password-input2');

          showPasswordBtn2.addEventListener('click', () => {
            // toggle icon 
            // font awesome class for eye icon
            showPasswordBtn2.classList.toggle('fa-eye');
            // font awesome class for slashed eye icon
            showPasswordBtn2.classList.toggle('fa-eye-slash');
            // ternary operator a shorthand for if and else to change the type of password input
            passwordInp2.type = passwordInp2.type === 'password' ? 'text' : 'password';
          })

          // string password validation

          let passwordChecklist2 = document.querySelectorAll('.list-item2');
          let validationRegex2 = [{
              regex: /.{6,}/
            }, // min 6 letters,
            {
              regex: /[0-9]/
            }, // numbers from 0 - 9
            {
              regex: /[a-z]/
            }, // letters from a - z (lowercase)
            {
              regex: /[A-Z]/
            }, // letters from A-Z (uppercase),
            {
              regex: /[^A-Za-z0-9]/
            } // special characters
          ]

          passwordInp2.addEventListener('keyup', () => {
            var total2 = 0;
            validationRegex2.forEach((item, i) => {

              let isValid2 = item.regex.test(passwordInp2.value);

              if (isValid2) {
                passwordChecklist2[i].classList.add('checked');
              } else {
                passwordChecklist2[i].classList.remove('checked');
              }
            })
            total2 = $('.checked').length
            if (total2 == 5) {
              $('.btn-simpan').prop('disabled', false);
            } else {
              $('.btn-simpan').prop('disabled', true);
            }
          });

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
      <?php 
        //JUMLAH TOTAL KARYAWAN
      foreach ($karyawan as $totkar) {
        $total = $totkar->total;
      }
         //JUMLAH TOTAL SPM
      foreach ($spm as $totspm) {
        $total_spm = $totspm->total;
      }

        //JUMLAH CCI
      foreach ($cci as $ccii) {
        $total_cci = $ccii->total;
      }

        //JUMLAH PEREMPUAN
      foreach ($P as $ce) {
        $perempuan = $ce->p;
      }
      // JUMLAH LAKI_LAKI
      foreach ($L as $co) {
        $laki = $co->l;
      }

      // JUMLAH vaksin
      foreach ($vaksin as $v) {
        $sudah_v = $v->sudah_v;
        $belum_v = $v->belum_v;
      }

      // JUMLAH TELAT
      foreach ($telat as $telat) {
        $terlambat = $telat->telat;
      }
      // JUMLAH LEGAL
      foreach ($legal as $legal) {
        $legal = $legal->legal;
      }
      // JUMLAH EXP LEGAL
      foreach ($exp_legal as $exp) {
        $exp_l = $exp->exp;
      }
      //JUMLAH PERJANJIAN
      foreach ($perjanjian as $janji) {
        $janji_l = $janji->janji;
      }
       //JUMLAH PERIZINAN
      foreach ($perizinan as $izin) {
        $izin_l = $izin->izin;
      }
      // JUMLAH BELUM LENGKAP
      foreach ($belum as $belum) {
        $belum = $belum->blm_lengkap;
      }
       // JUMLAH BELUM REALISASI
      foreach ($unreal as $unreal) {
        $unreal = $unreal->unreal;
      }

        // JUMLAH BELUM REALISASI PAYROLL
      foreach ($unreal_payroll as $unreals) {
        $unreals = $unreals->unreal;
      }
        // JUMLAH BELUM SK
      foreach ($blm_sk as $sk) {
        $blm_sk = $sk->blm_sk;
      }
         // JUMLAH OPEN RECRUITMENT
      foreach ($recruitment as $open_rec) {
        $open_rec = $open_rec->rec;
      }
         // JUMLAH TRAINING
      $training = $training->num_rows();
         // JUMLAH PENGAJUAN
      foreach ($pengajuan as $pengajuan) {
        $pengajuan = $pengajuan->pengajuan;
      }
      //JUMLAH PEREMPUAN
      foreach ($P as $ce) {
        $perempuan = $ce->p;
      }
      // JUMLAH LAKI_LAKI
      foreach ($L as $co) {
        $laki = $co->l;
      }
      // SD
      foreach ($sd as $sd) {
        $sd = $sd->sd;
      }
       // smp
      foreach ($smp as $smp) {
        $smp = $smp->smp;
      }
       // sma
      foreach ($sma as $sma) {
        $sma = $sma->sma;
      }
       // D3
      foreach ($d3 as $d3) {
        $d3 = $d3->d3;
      }
      // s1
      foreach ($s1 as $s1) {
        $s1 = $s1->s1;
      }
      // s2
      foreach ($s2 as $s2) {
        $s2 = $s2->s2;
      }
      //umur
      foreach ($usia as $umur) {
        $uk30 = $umur->kurang30;
        $um31 = $umur->u3140;
        $um50 = $umur->u4150;
        $u50 = $umur->lebih50;
      }
      //masker
      foreach ($masker as $umur) {
        $u1 = $umur->kurang1;
        $u5 = $umur->u5;
        $u10 = $umur->u10;
        $u20 = $umur->u20;
        $u21 = $umur->lebih20;
      }
      //masuk
      foreach ($masuk as $masuk) {
        $masuk = $masuk->masuk;
      }
      //keluar
      foreach ($keluar as $keluar) {
        $keluar = $keluar->keluar;
      }

      //karyawan by status jabatan
      foreach ($kontrak as $kontrak) {
        $s_kontrak = $kontrak->kontrak;
        $s_tetap = $kontrak->tetap;
      }



      ?>

      <?php $role = $this->session->userdata('role_id');
      foreach ($cek_usr as $user) {
        $nama = $user->nama_karyawan;
        $bagian = $user->indeks_hr;
        $jabatan = $user->indeks_jabatan;
        $tingkatan = $user->tingkatan;
        $struktur = $user->recid_struktur;
      }
      ?>


      <!-- page content -->
      <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row tile_count">
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
            <div class="count"><?php echo $total; ?><input type="hidden" id="total_karyawan" value="<?php echo $total?>"></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan Aktif">details</a> Karyawan</span>
          </div>

          <?php
          if($role == '1' or $role == '2' or $role == '5' or $role == '24' or $role == '25' or $role == '26' or $role == '27' or $role == '28' or $role == '29'or $role == '34' ){ ?>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total SPM</span>
              <div class="count"><?php echo $total_spm; ?></div>
              <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totspm" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan SPM">details</a> SPM</span>
            </div>
            <?php 
              if($role !=34)
              {?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total CCI</span>
                <div class="count"><?php echo $total_cci; ?></div>
                <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totcci" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan SPM">details</a> SPM</span>
              </div>
              <?php }
            ?>
            
          <?php } ?>

          <?php
          if($role == '1' or $role == '2' or $role == '5' or $role == '24' or $role == '25' or $role == '26' or $role == '27' or $role == '28' or $role == '29'or $role == '34' ){ ?>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Tetap</span>
              <div class="count"><?php echo $s_tetap; ?></div>
              <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/karyawan_by_status/Tetap" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan SPM">details</a> Tetap</span>
            </div>
          <?php } ?>

           <?php
          if($role == '1' or $role == '2' or $role == '5'  or $role == '23'  or $role == '24' or $role == '25' or $role == '26' or $role == '27' or $role == '28' or $role == '29'or $role == '34' or $role == '37' ){ ?>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Kontrak</span>
              <div class="count"><?php echo $s_kontrak; ?></div>
              <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/karyawan_by_status/Kontrak" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan Kontrak">details</a> Kontrak</span>
            </div>
          <?php } ?>

         <!--  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-male"></i> Total Male</span>
            <div class="count"><?php echo $laki; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_male" data-toggle="tooltip" data-placement="bottom" title="Karyawan Laki - laki Aktif">details</a> Karyawan</span>
          </div> -->
         <!--  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-female"></i> Total Female</span>
            <div class="count"><?php echo $perempuan; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_female" data-toggle="tooltip" data-placement="bottom" title="Karyawan Perempuan Aktif">details</a> Karyawan</span>
          </div> -->
          <?php
          if($role == '1' or $role == '2' or $role == '34'){ ?>
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-bullhorn"></i> Open Recruitment</span>
            <div class="count"><?php echo $open_rec ?></div>
            <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/recruitment_open" data-toggle="tooltip" data-placement="bottom" title="Open Recruitment">details</a> Data</span>
          </div>
        <?php } ?>

        <?php
        if($role == '1' or $role == '2' or $role == '5' or $role == '23' or $role == '24' or $role == '25' or $role == '26' or $role == '27' or $role == '28' or $role == '29'or $role == '34' or $role == '37'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-book"></i> Training</span>
          <div class="count"><?php echo $training ?></div>
          <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>index.php/Training" data-toggle="tooltip" data-placement="bottom" title="Training Karyawan">details</a> Data</span>
        </div>
      <?php } ?>


        <?php
        if($role == '1' or $role == '2'  or $role == '25'or $role == '34'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-star-half-o"></i> Data Belum Lengkap</span>
          <div class="count"><?php echo $belum; ?></div>
          <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_belum_lengkap" data-toggle="tooltip" data-placement="bottom" title="Data Karyawan Belum Lengkap">details</a> Data</span>
        </div>
      <?php } ?>

      <!-- <?php
      if($role == '1' or $role == '2'){ ?>
       <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-file-pdf-o"></i> Belum SK Karyawan</span>
        <div class="count"><?php echo $blm_sk ?></div>
        <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_blm_sk" data-toggle="tooltip" data-placement="bottom" title="Karyawan Tidak Ada SK">details</a> Data</span>
      </div>
    <?php } ?> -->
   

<?php
if($role == '1' or $role == '3' or $role == '5' or $role == '24' or $role == '25'or $role == '34'){ ?>
  <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-clock-o" style="color: red"></i> Total Keterlambatan</span>
    <div class="count"><?php if($terlambat > 0){echo "<blink>$terlambat</blink>";}else{echo $terlambat;}  ?></div>
    <span class="count_bottom"><a class="red" href="<?php echo base_url()?>Karyawan/terlambat" data-toggle="tooltip" data-placement="bottom" title="Keterlambatan Karyawan">details</a> Absen</span>
  </div> -->
<?php } ?>
<?php
if($role == '1' or $role == '4'or $role == '34'){ ?>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-balance-scale"></i> Total Legal</span>
    <div class="count"><?php echo $legal; ?></div>
    <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/legal_open" data-toggle="tooltip" data-placement="bottom" title="Legal">details</a> Legal</span>
  </div>
<?php } ?>
<?php
if($role == '1' or $role == '4'or $role == '34'){ ?>
 <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-file-pdf-o"></i> Total Perjanjian</span>
  <div class="count"><?php echo $janji_l ?></div>
  <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_perjanjian" data-toggle="tooltip" data-placement="bottom" title="Open Perjanjian">details</a> Data</span>
</div>
<?php } ?>
<?php
if($role == '1' or $role == '4' or $role == '34'){ ?>
 <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-file-pdf-o"></i> Total Perizinan</span>
  <div class="count"><?php echo $izin_l ?></div>
  <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_perizinan" data-toggle="tooltip" data-placement="bottom" title="Open Perizinan">details</a> Data</span>
</div>
<?php } ?>

<?php
if($role == '1' or $role == '4' or $role == '25' or $role == '34'){ ?>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-bell" style="color: red"></i> Total Legal Expired</span>
    <div class="count"><?php if($exp_l > 0){echo "<blink>$exp_l</blink>";}else{echo $exp_l;}  ?></div>
    <span class="count_bottom"><a class="red" href="<?php echo base_url()?>Karyawan/exp_legal" data-toggle="tooltip" data-placement="bottom" title="Legal Expired">details</a> Perjanjian</span>
  </div>
<?php } ?>
<!-- </div> -->
<!-- /top tiles -->
<!-- top tiles -->
<!-- <div class="row tile_count"> -->

  <?php
  if($role == '1' or $role == '5' or $role == '25'or $role == '34'){ ?>
   <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-file"></i> Total Pengajuan</span>
    <div class="count"><?php echo $pengajuan;  ?></div>
    <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/plembur_view" data-toggle="tooltip" data-placement="bottom" title="Pengajuan Lembur">details</a> Data</span>
  </div> -->
<?php } ?>

 <?php
if($role == '1' or $role == '5' /* or $role == '23'  */){ 
  if($role == '5' or $role == '1'){ ?>
   <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-warning" style="color: red"></i> Lembur Belum Realisasi</span>
    <div class="count" style="color: red"><?php if($unreal > 0){echo "<blink>$unreals</blink>";}else{echo $unreals;}  ?></div>
    <span class="count_bottom"><a class="red"  href="<?php echo base_url()?>Karyawan/unrealisasi_payroll" data-toggle="tooltip" data-placement="bottom" title="Lembur Belum Realisasi">details</a> Data</span>
  </div> -->
<?php }
else{ 
  if($tingkatan >= 8){ ?>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-warning" style="color: red"></i> Lembur Belum Realisasi</span>
      <div class="count" style="color: red"><?php if($unreal > 0){echo "<blink>$unreal</blink>";}else{echo $unreal;}  ?></div>
      <span class="count_bottom"><a class="red"  href="<?php echo base_url()?>Karyawan/unrealisasi" data-toggle="tooltip" data-placement="bottom" title="Lembur Belum Realisasi">details</a> Data</span>
    </div>
  <?php }?>

<?php }
?> 

<?php } ?>

<?php
          if($role == '23' or $role == '37' ){ ?>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Team</span>
              <div class="count"><?php echo $team; ?></div>
              <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_dinamis" data-toggle="tooltip" data-placement="bottom" title="Team">details</a> Kontrak</span>
            </div>
          <?php } ?>


</div>
<!-- /top tiles -->
 <div class="row">
  <!-- Grafik Realisasi Budget Lembur -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Realisasi Budget Lembur</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="lbudget">
          <input type="hidden" id="lreal">
          <div class="col-md-6 col-sm-6 col-xs-12">
             <select class="form-control" id="lb_dept" onchange="realisasi_lembur_tahun()">
              <option value="semua">All</option>
              <option value="PRESDIR">PRESDIR</option>
              <option value="FRONT OFFICE">FRONT OFFICE</option>
              <option value="MIDDLE OFFICE">MIDDLE OFFICE</option>
              <option value="BACK OFFICE">BACK OFFICE</option>
            </select>
          </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" id="lb_tahun" onchange="realisasi_lembur_tahun()">
            <?php 
              for($th1=date('Y'); $th1>=2023; $th1--)
              {?>
                <option value="<?php echo $th1?>"><?php echo $th1?></option>
              <?php } ?>
            </select>
          </div><br>
          <div id="main19" style="height:290px;"></div>

        </div>
      </div>
    </div>
    <!-- end Grafik Realisasi Budget Lembur per tahun -->

  <!-- Grafik Realisasi Budget Lembur -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Budget vs Realisasi Lembur</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="lbudget">
          <input type="hidden" id="lreal">
          <div class="col-md-6 col-sm-6 col-xs-12">
             <select class="form-control" id="lb_dept2" onchange="realisasi_lembur_bulan()">
              <option value="semua">All</option>
              <option value="PRESDIR">PRESDIR</option>
              <option value="FRONT OFFICE">FRONT OFFICE</option>
              <option value="MIDDLE OFFICE">MIDDLE OFFICE</option>
              <option value="BACK OFFICE">BACK OFFICE</option>
            </select>
          </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control" id="lb_tahun2" onchange="realisasi_lembur_bulan()">
            <?php 
              for($th2=date('Y'); $th2>=2023; $th2--)
              {?>
                <option value="<?php echo $th2?>"><?php echo $th2?></option>
              <?php } ?>
            </select>
          </div><br>
          <div id="main20" style="height:290px;"></div>

        </div>
      </div>
    </div>
    <!-- end Grafik Realisasi Budget Lembur per tahun -->
 

    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>COVID19 VACCINE</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="vsudah" value="<?php echo $sudah_v?>">
          <input type="hidden" id="vbelum" value="<?php echo $belum_v?>">
          <div id="main16" style="height:290px;"></div>

        </div>
      </div>
    </div>

    
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>EMPLOYEE HEADCOUNT BY GENDER</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="cpria">
          <input type="hidden" id="cwanita">
             <select class="form-control" id="filter_gender">
              <option value="semua">All</option>
              <option value="PRESDIR">PRESDIR</option>
              <option value="FRONT OFFICE">FRONT OFFICE</option>
              <option value="MIDDLE OFFICE">MIDDLE OFFICE</option>
              <option value="BACK OFFICE">BACK OFFICE</option>
            </select><br>
          <div id="main" style="height:290px;"></div>

        </div>
      </div>
    </div>
    <!-- end gender -->

    <!-- Pendidikan -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>EDUCATIONAL STAGE</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div id="main2" style="height:350px;"></div>

        </div>
      </div>
    </div>
    <!-- end pendidikan -->

    <!-- Rentang Usia -->
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>EMPLOYEE HEADCOUNT BY AGE</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div id="main3" style="height:350px;"></div>

          </div>
        </div>
      </div>
      <!-- end rentang usia -->

      <!-- masa kerja -->
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>EMPLOYEE HEADCOUNT BY WORKING PERIOD</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div id="main4" style="height:350px;"></div>

          </div>
        </div>
      </div>

       <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>MUTASI & ROTASI</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <input type="hidden" id="thn_rotasi">
            <select class="form-control" id="tahun_mutasi">
             <?php
              for($i=date("Y");$i>=2014;$i--)
              {?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
              <?php }
            ?>
            </select><br>
            <div id="main9" style="height:290px;"></div>

          </div>
        </div>
      </div>

       <!-- masuk - keluar -->
      <div class="col-md-6 col-sm-6 col-xs-12" <?php if($role == '4'){echo "style='display:none'";} ?>>
        <div class="x_panel">
          <div class="x_title">
            <h2>EMPLOYEE IN - OUT</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
           <input type="hidden" id="cin" value="<?php echo $masuk ?>">
           <input type="hidden" id="cout" value="<?php echo $keluar ?>">
           <?php $bulan = array('Januari','Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
            $bulans = array('1','2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
            $bskrg = date('n');
            ?>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <select class="form-control" id="bulan">
               <?php
               for($i=0; $i<count($bulans); $i++){
                if($bskrg == $bulans[$i]){
                  echo "<option value='$bulans[$i]' selected='selected'>$bulan[$i]</option>";
                }else{
                  echo "<option value='$bulans[$i]'>$bulan[$i]</option>";
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
           <select class="form-control" id="tahun">
            <?php
            $max_tahun = date('Y');
            $min_tahun = $max_tahun - 5;
            for($tahun=$min_tahun;$tahun<=$max_tahun;$tahun++){
              if($tahun == $max_tahun){
               echo "<option value='$tahun' selected='selected'>$tahun</option>";
             }else{
               echo "<option value='$tahun'>$tahun</option>";
             }

           }
           ?>
         </select>
         </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <br><br>
           <div id="main5" style="height:350px;"></div>
         </div>
         </div>
       </div>
     </div>
     <!-- end masuk keluar -->


      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>EMPLOYEE TURN OVER</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <input type="hidden" id="thn_over">
            <select class="form-control" id="tahun_over">
             <?php
              for($i=date("Y");$i>=2014;$i--)
              {?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
              <?php }
            ?>
            </select>
            <br>
            <div id="main10" style="height:350px;"></div>
            <div id="forTable">
              
            </div>
          </div>
        </div>
      </div>

      <?php
if($role == '1' or $role == '2' or $role == '4' or $role == '5' or $role == '27' or $role == '29'or $role == '34'){ ?>

    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>RECRUITMENT FULLFILLMENT </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <select class="form-control" id="rec_thn">
             <?php
              for($i=date("Y");$i>=2014;$i--)
              {?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
              <?php }
            ?>
            </select>
            <br>
            
          <div id="main15" style="height:290px;"></div>
        </div>
    </div>
  </div>

      <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>APPLICANT BY MEDIA SOURCE</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="text" id="online">
          <input type="text" id="offline">
            
          <div id="main13" style="height:290px;"></div>
        </div>
    </div>
  </div>
<?php } ?>


<?php
if($role == '1' or $role == '2' or $role == '4' or $role == '5' or $role == '27' or $role == '29'or $role == '34'){ ?>
  <!-- Pendidikan -->
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>EDUCATIONAL STAGE OF APPLICANT</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div id="main14" style="height:350px;"></div>

        </div>
      </div>
    </div>
    <!-- end pendidikan -->
<?php } ?>

     
<!-- TRAINING -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>TRAINING BY HOURS</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="thn_over">
            <select class="form-control" id="train_thn">
             <?php
              for($i=date("Y");$i>=2014;$i--)
              {?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
              <?php }
            ?>
            </select>
            <br>
          <div id="main11" style="height:350px;"></div>

        </div>
      </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>TRAINING BY COMPETENCY</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
           <select class="form-control" id="train_cthn">
            <?php
            $max_tahun = date('Y');
            $min_tahun = $max_tahun - 5;
            for($tahun=$min_tahun;$tahun<=$max_tahun;$tahun++){
              if($tahun == $max_tahun){
               echo "<option value='$tahun' selected='selected'>$tahun</option>";
             }else{
               echo "<option value='$tahun'>$tahun</option>";
             }

           }
           ?>
         </select>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <br><br>
           <div id="main12" style="height:350px;"></div>
         </div>
      </div>
    </div>
  </div>
    <!-- end TRAINING -->

   <?php
   if($role == '1' or $role == '2' or $role == '4' or $role == '24' or $role == '25'or $role == '34'){ ?>
    <!-- Notif --> 
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>LEGAL NOTIFICATION</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div >
              <form method="post" action="">
               <div class="col-md-4 col-sm-4 col-xs-12">
                <select class="form-control" id="ranges">
                  <option>-- Pilih --</option>
                  <option value="today">Today</option>
                  <option value="sehari">H-1</option>
                  <option value="tigahari">H-3</option>
                  <option value="seminggu">H-7</option>
                  <option value="sebulan">H-30</option>
                  <option value="empatlima">H-45</option>
                  <option value="enampuluh">H-60</option>
                </select>
              </div>
            </form>
            <br>
            <div class="table-responsive">
            <table id="notif" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Judul</th>
                  <th>Tgl Akhir</th>
                  <th>File</th>
                  <th>Karyawan</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          </div>

        </div>
      </div>
    </div>
  <!-- end notif -->
<?php } ?>

<?php
if($role == '1' or $role == '2' or $role == '4' or $role == '5' or $role == '27' or $role == '29'or $role == '34'){ ?>
  <!-- Notif Expedisi-->
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>EXPEDITION HC NOTIFICATION</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div style="height:350px;" >
            <form method="post" action="">
              <div class='input-group date col-lg-4' id='myDatepicker5'>
              <input type='text' class="form-control" name="tgl_m_karir" id="tgl_notif" value=<?php echo date('y-m-d')?> />
              <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
             </span>
           </div>
          </form>
          <br>

          <table id="notif_karir" class="table table-striped table-bordered" style="display: none">
            <thead>
              <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Bagian</th>
                <th>Jabatan</th>
                <th>Jenis Karir</th>
                <th>Periode</th>
                <th>Scan File</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
<?php } ?>

<?php
if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '24' or $role == '23' or $role == '25'or $role == '34'){ ?>
  <!-- Grafik Absen per tahun -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Ketidak Hadiran Karyawan</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
           <input type="hidden" id="ain" value="<?php echo $masuk ?>">
           <input type="hidden" id="aout" value="<?php echo $keluar ?>">
           <?php $bulan = array('Januari','Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
            $bulans = array('1','2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
            $bskrg = date('n');
            ?>
            <div class="col-md-4 col-sm-4 col-xs-12">
             <select class="form-control" id="abulan" onchange="dash_absen1()">
               <?php
               for($i=0; $i<count($bulans); $i++){
                if($bskrg == $bulans[$i]){
                  echo "<option value='$bulans[$i]' selected='selected'>$bulan[$i]</option>";
                }else{
                  echo "<option value='$bulans[$i]'>$bulan[$i]</option>";
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-12">
           <select class="form-control" id="atahun" onchange="dash_absen1()">
            <?php
            $max_tahun = date('Y');
            $min_tahun = $max_tahun - 5;
            for($tahun=$min_tahun;$tahun<=$max_tahun;$tahun++){
              if($tahun == $max_tahun){
               echo "<option value='$tahun' selected='selected'>$tahun</option>";
             }else{
               echo "<option value='$tahun'>$tahun</option>";
             }

           }
           ?>
         </select>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-12">
           <select class="form-control" id="afilterbo" onchange="dash_absen1()">
            <option value="ALL">All</option>
            <option value="FRONT OFFICE">FRONT OFFICE</option>
            <option value="MIDDLE OFFICE">MIDDLE OFFICE</option>
            <option value="BACK OFFICE">BACK OFFICE</option>
          </select>
        </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <br><br>
           <div id="main6" style="height:350px;"></div>
         </div>
         </div>
      </div>
    </div>
    <!-- end Grafik Absen per tahun -->
  <?php } ?>

  <?php
if($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '24' or $role == '25'or $role == '34'){ ?>
  <!-- Grafik Absen per tahun2 -->
   <!--  <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
          <h2>Presentase Keseluruhan Kehadiaran</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="col-md-12 col-sm-12 col-xs-12">
           <select class="form-control" id="tahun_absen" onchange="dash_absen2()">
            <?php
            $max_tahun = date('Y');
            $min_tahun = $max_tahun - 5;
            for($tahun=$min_tahun;$tahun<=$max_tahun;$tahun++){
              if($tahun == $max_tahun){
               echo "<option value='$tahun' selected='selected'>$tahun</option>";
             }else{
               echo "<option value='$tahun'>$tahun</option>";
             }

           }
           ?>
         </select>
       </div>
          <div id="main7" style="height:350px;"></div>
        </div>
      </div>
    </div> -->
    <!-- end Grafik Absen per tahun -->
  <?php } ?>

  <?php
if($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '24' or $role == '25'){ ?>
  <!-- Grafik Jenis Absen -->
  <!-- <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Grafik Persentase Katagori Absensi Mangkir, P1, SID & Cuti </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div id="main8" style="height:350px;"></div>
        </div>
      </div>
    </div>
  </div> -->
    <!-- end Grafik Jenis Absen -->
  <?php } ?>

  <?php
if($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '24' or $role == '25'or $role == '34'){ ?>
  <!-- Grafik Profile DISC -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>DISC Dominan Karyawan</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="domD">
          <input type="hidden" id="domI">
          <input type="hidden" id="domS">
          <input type="hidden" id="domC">

             <select class="form-control" id="filter_disc">
              <option value="semua">All</option>
              <option value="PRESDIR">PRESDIR</option>
              <option value="FRONT OFFICE">FRONT OFFICE</option>
              <option value="MIDDLE OFFICE">MIDDLE OFFICE</option>
              <option value="BACK OFFICE">BACK OFFICE</option>
            </select><br>
          <div id="main17" style="height:290px;"></div>

        </div>
      </div>
    </div>
    <!-- end Grafik Profile DISC per tahun -->
  <?php } ?>

   <?php
if($role == '1' or $role == '2' or $role == '3'  or $role == '5' or $role == '24' or $role == '25'or $role == '34'){ ?>
  <!-- Grafik DISC Profile Type -->
   <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>DISC PROFILE TYPE</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <br><br>
           <div id="main18" style="height:350px;"></div>
         </div>
      </div>
    </div>
  </div>
    <!-- end Grafik DISC Profile Type -->
  <?php } ?>

 

</div>
</div>
<!-- /page content -->

<footer>
  <div class="pull-right">
    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modalNotif">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Notifikasi</h4>
            </div>
            <div class="modal-body">
              <!-- Content Modal -->
                <table class="table">
                  <thead>
                    <tr style="font-weight:bold">
                      <td>Notifikasi</td>
                      <td>Keterangan</td>
                      <td></td>
                    </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Approval STKL</td>
                        <td style="color:red"><?php echo $stkl_pending ?> pending</td>
                        <td><a href="<?php echo base_url()?>Lembur/stkl_pending"><button class="btn btn-warning btn-sm">Lihat Detail</button></a></td>
                      </tr>
                    </tbody>
                </table>

                <!--/ Content Modal -->
            </div>
            <div class="modal-footer">
            </div>

          </div>
        </div>
      </div>



<!-- Bootstrap -->
<script src="<?php echo base_url()?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url()?>assets/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="<?php echo base_url()?>assets/vendors/nprogress/nprogress.js"></script>
<!-- Datatables -->
<script src="<?php echo base_url()?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo base_url()?>assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- validator -->
<script src="<?php echo base_url()?>assets/vendors/validator/validator.js"></script>
<!-- bootstrap-datepicker -->  
<script src="<?php echo base_url()?>assets/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo base_url()?>assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url()?>assets/vendors/iCheck/icheck.min.js"></script>
<!-- jQuery Smart Wizard -->
<script src="<?php echo base_url()?>assets/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<!-- ECharts -->
<script src="<?php echo base_url()?>assets/vendors/echarts/dist/echarts.min.js"></script>
<script src="<?php echo base_url()?>assets/vendors/echarts/map/js/world.js"></script>
<!-- Multi Select -->
<script src="<?php echo base_url()?>assets/vendors/multi-select/js/jquery.multi-select.js" type="text/javascript"></script>
<!-- Custom Theme Scripts -->
<script src="<?php echo base_url()?>assets/build/js/custom.min.js"></script>
</body>
</html>


<!-- ################################################### REPORT ################################################################### -->
<!-- *************************** DASH *************************** -->

<script type="text/javascript">
   $(window).on('load', function() {
        $('#modalNotif').modal('show');
    });

 var today = new Date();
 var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
        ];

        const namaBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        if(dd<10) {
          dd = '0'+dd
        } 

        if(mm<10) {
          mm = '0'+mm
        } 

        today = monthNames[today.getMonth()];
        periode = today + " " +yyyy;

        tahun_ini = yyyy;
        // alert(periode);

      </script>

      <!-- CHART USIA -->
      <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main3'));

        // specify chart configuration item and data
        var option = {
          title: {
            x: 'center',
            text: 'Rentang Usia',
            subtext: periode,
          },
          tooltip: {
            trigger: 'item'
          },
          toolbox: {
            show: true,
            feature: {
              // dataView: {show: true, readOnly: false},
              // restore: {show: true},
              saveAsImage: {show: true}
            }
          },
          calculable: true,
          grid: {
            borderWidth: 0,
            y: 80,
            y2: 60
          },
          xAxis: [
          {
            type: 'category',
            show: false,
            data: [' < 30', '31 - 40','41 - 50',' > 50']
          }
          ],
          yAxis: [
          {
            type: 'value',
            show: false
          }
          ],
          series: [
          {
            name: 'Usia',
            type: 'bar',
            itemStyle: {
              normal: {
                color: function(params) {
                        // build a color map as your need.
                        var colorList = [
                        '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                        '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                        '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                        ];
                        return colorList[params.dataIndex]
                      },
                      label: {
                        show: true,
                        position: 'top',
                        formatter: '{b}\n{c}'
                      }
                    }
                  },
                  data: ['<?php echo $uk30 ?>', '<?php echo $um31 ?>','<?php echo $um50 ?>','<?php echo $u50 ?>'],
                  markPoint: {
                    tooltip: {
                      trigger: 'item',
                      backgroundColor: 'rgba(0,0,0,0)',
                      formatter: function(params){
                        return '<img src="' 
                        + params.data.symbol.replace('image://', '')
                        + '"/>';
                      }
                    },
                    data: [
                    {xAxis:0, y: 350, name:'< 30', symbolSize:20},
                    {xAxis:1, y: 350, name:'31 - 40', symbolSize:20},
                    {xAxis:3, y: 350, name:'41 - 50', symbolSize:20},
                    {xAxis:3, y: 350, name:'> 50', symbolSize:20},
                    ]
                  }
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == ' < 30'){
            link = "<?php echo base_url()?>Karyawan/usia/kurang30";
            window.open(link);
          }else if(data.name == '31 - 40'){
            link = "<?php echo base_url()?>Karyawan/usia/u3140";
            window.open(link);
          }else if(data.name == '41 - 50'){
            link = "<?php echo base_url()?>Karyawan/usia/u4150";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/usia/lebih50";
           window.open(link);
         }
       })
      </script>

      <!-- CHART MASKER -->
      <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main4'));

        // specify chart configuration item and data
        var option = {
          title: {
            x: 'center',
            text: 'Rentang Masa Kerja',
            subtext: periode,
          },
          tooltip: {
            trigger: 'item'
          },
          toolbox: {
            show: true,
            feature: {
              // dataView: {show: true, readOnly: false},
              // restore: {show: true},
              saveAsImage: {show: true}
            }
          },
          calculable: true,
          grid: {
            borderWidth: 0,
            y: 80,
            y2: 60
          },
          xAxis: [
          {
            type: 'category',
            show: false,
            data: [' < 1', '1 - 5','6 - 10', '11 - 20', '> 20']
          }
          ],
          yAxis: [
          {
            type: 'value',
            show: false
          }
          ],
          series: [
          {
            name: 'Masa Kerja',
            type: 'bar',
            itemStyle: {
              normal: {
                color: function(params) {
                        // build a color map as your need.
                        var colorList = [
                        '#27727B','#FE8463','#FCCE10','#F0805A','#C1232B', 
                        '#B5C334','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                        '#26C0C0','#C6E579','#F4E001','#E87C25','#D7504B' 
                        ];
                        return colorList[params.dataIndex]
                      },
                      label: {
                        show: true,
                        position: 'top',
                        formatter: '{b}\n{c}'
                      }
                    }
                  },
                  data: ['<?php echo $u1 ?>', '<?php echo $u5 ?>','<?php echo $u10 ?>', '<?php echo $u20 ?>', '<?php echo $u21 ?>'],
                  markPoint: {
                    tooltip: {
                      trigger: 'item',
                      backgroundColor: 'rgba(0,0,0,0)',
                      formatter: function(params){
                        return '<img src="' 
                        + params.data.symbol.replace('image://', '')
                        + '"/>';
                      }
                    },
                    data: [
                    {xAxis:0, y: 350, name:'... - 1', symbolSize:20},
                    {xAxis:1, y: 350, name:'1 - 5', symbolSize:20},
                    {xAxis:1, y: 350, name:'6 - 10', symbolSize:20},
                    {xAxis:1, y: 350, name:'11 - 20', symbolSize:20},
                    {xAxis:3, y: 350, name:'21 - ...', symbolSize:20},
                    ]
                  }
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == ' < 1'){
            link = "<?php echo base_url()?>Karyawan/masker/kurang1";
            window.open(link);
          }else if(data.name == '1 - 5'){
            link = "<?php echo base_url()?>Karyawan/masker/u15";
            window.open(link);
          }else if(data.name == '6 - 10'){
            link = "<?php echo base_url()?>Karyawan/masker/u610";
            window.open(link);
          }else if(data.name == '11 - 20'){
            link = "<?php echo base_url()?>Karyawan/masker/u1120";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/masker/lebih20";
           window.open(link);
         }
       })
      </script>


      <!-- CHART rotasi mutasi -->
      <script type="text/javascript">
        function mutasi_rotasi(){
          thn_mutasi = document.getElementById('thn_rotasi').value = document.getElementById('tahun_mutasi').value;
          $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/mutasi_rotasi", // Isi dengan url/path file php yang dituju
            data : {thn_mutasi : thn_mutasi},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // console.log(response);
               // based on prepared DOM, initialize echarts instance
               var myChart = echarts.init(document.getElementById('main9'));

        // specify chart configuration item and data
        option = {
          title: {
            text: 'Mutasi - Rotasi - Promosi',
            textStyle: {
              fontSize: 14
            }
          },
          tooltip: {
            trigger: 'axis'
          },
          legend: {
            data: ['Rotasi', 'Mutasi', 'Promosi'],
            right:20,
            top: 10,
          },
          toolbox: {
            feature: {
              saveAsImage: {}
            }
          },
          xAxis: {
            type: 'category',
            data: response[0],
          },
          yAxis: {
            type: 'value'
          },
          series: [{
            name : 'Mutasi',
            data: response[1],
            type: 'line',
            smooth: true
          },
          {
            name : 'Rotasi',
            data: response[2],
            type: 'line',
            smooth: true
          },
          {
            name : 'Promosi',
            data: response[3],
            type: 'line',
            smooth: true
          }]
        };
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.seriesName == 'Mutasi'){
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/mutasi/"+bulan_cek+"/"+thn_mutasi;
            window.open(link);
          }else if(data.seriesName == 'Promosi'){
             var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/promosi/"+bulan_cek+"/"+thn_mutasi;
            window.open(link);
          }else{
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/rotasi/"+bulan_cek+"/"+thn_mutasi;
           window.open(link);
         }
       })
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
      });
       
   }
       
     </script>

     <!-- CHART rotasi TURN OVER -->
      <script type="text/javascript">
        function turn_over(){
          thn_over = document.getElementById('thn_over').value = document.getElementById('tahun_over').value;
          $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/turn_over", // Isi dengan url/path file php yang dituju
            data : {thn_over : thn_over},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // console.log(response);
               // based on prepared DOM, initialize echarts instance
               table_turnover(thn_over);
               var myChart = echarts.init(document.getElementById('main10'));

        // specify chart configuration item and data
        option = {
          title: {
            text: 'Norm < 1 Year - By Norm - Non Norm',
            textStyle: {
              fontSize: 14
            }
          },
          tooltip: {
            trigger: 'item',
            formatter: "{b} <br> {c}%"
          },
          legend: {
            data: ['Norm < 1 Year', 'By Norm', 'Non Norm'],
            right:20,
            top: 10,
          },
          toolbox: {
            feature: {
              saveAsImage: {}
            }
          },
          xAxis: {
            type: 'category',
            data: response[0],
          },
          yAxis: {
            type: 'value',
            name: "%",
            axisLabel: {
              formatter: "{value} %"
            } 
          },
          series: [{
            name : 'Norm < 1 Year',
            data: response[1],
            type: 'line',
            smooth: true
          },
          {
            name : 'By Norm',
            data: response[2],
            type: 'line',
            smooth: true
          },
          {
            name : 'Non Norm',
            data: response[3],
            type: 'line',
            smooth: true
          }]
        };
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.seriesName == 'Non Norm'){
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/non_norm/"+bulan_cek+"/"+thn_over;
            window.open(link);
          }else if(data.seriesName == 'By Norm'){
             var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/by_norm/"+bulan_cek+"/"+thn_over;
            window.open(link);
          }else{
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/norm_under1/"+bulan_cek+"/"+thn_over;
           window.open(link);
         }
       })
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
      });
       
   }
       
     </script>

      <!-- CHART GENDER -->
      <script type="text/javascript">
        function gender(){
        var tipe_gender = document.getElementById('filter_gender').value;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/gender_chart", // Isi dengan url/path file php yang dituju
            data: {tipe_gender : tipe_gender}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              document.getElementById('cpria').value = response[0];
             document.getElementById('cwanita').value = response[1];
             // based on prepared DOM, initialize echarts instance
             var myChart = echarts.init(document.getElementById('main'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Gender',
            subtext: yyyy,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Laki - laki','Perempuan']
          },
          toolbox: {
            show : true,
            feature : {
              mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Gender',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    // shadowBlur:3,
                    // shadowOffsetX: 2,
                    // shadowOffsetY: 2,
                    // shadowColor: '#999',
                    // padding: [0, 7],
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        // abg: {
                        //     backgroundColor: '#333',
                        //     width: '100%',
                        //     align: 'right',
                        //     height: 22,
                        //     borderRadius: [4, 4, 0, 0]
                        // },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:[
                  {value:document.getElementById('cpria').value, name:'Laki - laki'},
                  {value:document.getElementById('cwanita').value, name:'Perempuan'}
                  ]
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == 'Perempuan'){
            link = "<?php echo base_url()?>Karyawan/gender/Perempuan";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/gender/Laki - laki";
           window.open(link);
         }
       })
      },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });
    }
       
     </script>

     <!-- CHART PENDIDIKAN -->
     <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main2'));

        // specify chart configuration item and data
        var option = {
          title: {
            x: 'center',
            text: 'Pendidikan',
            subtext: periode,
          },
          tooltip: {
            trigger: 'item'
          },
          toolbox: {
            show: true,
            feature: {
              // dataView: {show: true, readOnly: false},
              // restore: {show: true},
              saveAsImage: {show: true}
            }
          },
          calculable: true,
          grid: {
            borderWidth: 0,
            y: 80,
            y2: 60
          },
          xAxis: [
          {
            type: 'category',
            show: false,
            data: ['SD','SMP','SMA','D3','S1','S2']
          }
          ],
          yAxis: [
          {
            type: 'value',
            show: false
          }
          ],
          series: [
          {
            name: 'Education',
            type: 'bar',
            itemStyle: {
              normal: {
                color: function(params) {
                        // build a color map as your need.
                        var colorList = [
                        '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                        '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                        '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                        ];
                        return colorList[params.dataIndex]
                      },
                      label: {
                        show: true,
                        position: 'top',
                        formatter: '{b}\n{c}'
                      }
                    }
                  },
                  data: ['<?php echo $sd ?>', '<?php echo $smp ?>','<?php echo $sma ?>','<?php echo $d3 ?>','<?php echo $s1 ?>','<?php echo $s2 ?>'],
                  markPoint: {
                    tooltip: {
                      trigger: 'item',
                      backgroundColor: 'rgba(0,0,0,0)',
                      formatter: function(params){
                        return '<img src="' 
                        + params.data.symbol.replace('image://', '')
                        + '"/>';
                      }
                    },
                    data: [
                    {xAxis:0, y: 350, name:'sd', symbolSize:20},
                    {xAxis:1, y: 350, name:'smp', symbolSize:20},
                    {xAxis:3, y: 350, name:'sma', symbolSize:20},
                    {xAxis:3, y: 350, name:'d3', symbolSize:20},
                    {xAxis:3, y: 350, name:'s1', symbolSize:20},
                    {xAxis:3, y: 350, name:'s2', symbolSize:20},
                    ]
                  }
                }
                ]
              };
        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == 'SD'){
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/SD";
            window.open(link);
          }else if(data.name == 'SMP'){
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/SMP";
            window.open(link);
          } else if(data.name == 'SMA'){
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/SMA";
            window.open(link);
          } else if(data.name == 'D3'){
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/D3";
            window.open(link);
          }else if(data.name == 'S1'){
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/S1";
            window.open(link);
          }else{
            link = "<?php echo base_url()?>Karyawan/rekap_pendidikan/S2";
            window.open(link);
          }
        })
      </script>
      <!-- ################################################### ABSEN ################################################################### -->

      <script>
        $(document).ready(function() {


      //tgl_lahir
          $('#myDatepicker5').datetimepicker({
            format: 'YYYY-MM-DD'
          });
          realisasi_lembur_tahun();
          realisasi_lembur_bulan();
          inout();
          vaksin_covid();
          gender();
          mutasi_rotasi();
          turn_over();
          training_hour();
          train_comp();
          info_source();
          pendidikan_kandidat();
          leadtime();
          dash_absen1();
          dominan_disc();
          profile_disc();
          

          var expedisi = document.getElementById('tgl_notif').value;
          var table = $('#notif_karir').DataTable();
            table.destroy();
            var table = $('#notif_karir').DataTable( {
              "responsive":true,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              "bDestroy": true,
              buttons: [
              'excel', 'print'
              ],
              "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>Karyawan/notif_expedisi",
                dataType: 'json',
                data: {expedisi: expedisi},
              },
            });
            $("#notif_karir").show(); 

            $('#myDatepicker5').datetimepicker().on('dp.change', function (event) {
              var expedisi = document.getElementById('tgl_notif').value;
              var table = $('#notif_karir').DataTable();
              table.destroy();
              var table = $('#notif_karir').DataTable( {
                "responsive":true,
                "bScrollCollapse": true,
                "bLengthChange": true,
                "searching": true,
                "dom": 'Bfrtip',
                "bDestroy": true,
                buttons: [
                'excel', 'print'
                ],
                "ajax": {
                  type: "POST",
                  url: "<?php echo base_url(); ?>Karyawan/notif_expedisi",
                  dataType: 'json',
                  data: {expedisi: expedisi},
                },
              });
              $("#notif_karir").show(); 
            });

           
            var filter = 'sebulan';
            $("#ranges").val(filter);
            var role = <?php echo $this->session->userdata('role_id'); ?>;
            var table = $('#notif').DataTable();
            table.destroy();
            var table = $('#notif').DataTable( {
              "responsive":true,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              "bDestroy": true,
              buttons: [
              'excel', 'print'
              ],
              "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>Karyawan/notif",
                dataType: 'json',
                data: {filter: filter, role:role},
              },
            });
            $("#notif").show(); 

          $("#ranges").change(function(){
            var filter = $("#ranges").val();
            var role = <?php echo $this->session->userdata('role_id'); ?>;
            var table = $('#notif').DataTable();
            table.destroy();
            var table = $('#notif').DataTable( {
              "responsive":true,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              "bDestroy": true,
              buttons: [
              'excel', 'print'
              ],
              "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>Karyawan/notif",
                dataType: 'json',
                data: {filter: filter, role:role},
              },
            });
            $("#notif").show(); 
          });
        });
      </script>

      <script>
       $("#bulan").change(function(){
        inout();
      });
       $("#tahun").change(function(){
        inout();
      });
       $("#filter_gender").change(function(){
        gender();
      });
        $("#tahun_mutasi").change(function(){
        mutasi_rotasi();
      });
         $("#tahun_over").change(function(){
        turn_over();
      });
          $("#train_thn").change(function(){
        training_hour();
      });
          $("#train_cthn").change(function(){
          train_comp()
      });
           $("#rec_thn").change(function(){
          leadtime()
      });
       $("#filter_disc").change(function(){
        dominan_disc();
      });


       function inout()
       {
        var month = document.getElementById('bulan').value;
        var year = document.getElementById('tahun').value;
        var  monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
        ];
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/inout", // Isi dengan url/path file php yang dituju
            data: {bulan : month, tahun : year}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
             document.getElementById('cin').value = response[0];
             document.getElementById('cout').value = response[1];
             var myChart = echarts.init(document.getElementById('main5'));

              // specify chart configuration item and data
              var option = {
               title: {
                x: 'center',
                text: 'Masuk - Keluar',
                subtext: monthNames[month-1]+" - "+year,
              },
              tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
              },
              legend: {
                orient : 'vertical',
                x : 'left',
                data:['in','out']
              },
              toolbox: {
                show : true,
                feature : {
                  mark : {show: true},
                  // dataView : {show: true, readOnly: false},
                  magicType : {
                    show: true, 
                    type: ['pie', 'funnel'],
                    option: {
                      funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'center',
                        max: 1548
                      }
                    }
                  },
                  // restore : {show: true},
                  saveAsImage : {show: true}
                }
              },
              calculable : true,
              series : [
              {
                name:'masuk - keluar',
                type:'pie',
                radius : ['50%', '60%'],
                center: ['50%', '50%'],
                itemStyle : {
                  normal : {
                    label : {
                      show : true
                    },
                    labelLine : {
                      show : true
                    }
                  },
                  emphasis : {
                    label : {
                      show : true,
                      position : 'center',
                      textStyle : {
                        fontSize : '16',
                        fontWeight : 'bold'
                      }
                    }
                  }
                },  
                label: {
                  normal: {
                    formatter: '{b} : {c}',
                    backgroundColor: '#eee',
                    borderColor: '#aaa',
                    borderWidth: 1,
                    borderRadius: 4,
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                      b: {
                        fontSize: 16,
                        lineHeight: 33
                      },
                    }
                  }
                },
                data:[
                {value:document.getElementById('cin').value, name:'in'},
                {value : document.getElementById('cout').value, name:'out'}
                ]
              }
              ]
            };

              // use configuration item and data specified to show chart
              myChart.setOption(option);
              myChart.on('click', function (data) {
                if(data.name == 'in'){
                  link = "<?php echo base_url()?>Karyawan/in/"+month+"/"+year;
                  window.open(link);
                }else{
                 link = "<?php echo base_url()?>Karyawan/out/"+month+"/"+year;
                 window.open(link);
               }
             })
            },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
      }
    </script>
      <!-- CHART TRAINING -->
      <script type="text/javascript">
        function training_hour()
        {
         train_tahun = document.getElementById('train_thn').value;

         $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>index.php/Training/training_hour", // Isi dengan url/path file php yang dituju
            data : {train_tahun : train_tahun},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // console.log(response[0]);
               // based on prepared DOM, initialize echarts instance
               var myChart = echarts.init(document.getElementById('main11'));

              // specify chart configuration item and data
              var option = {
                title: {
                  x: 'center',
                  text: 'TRAINING JAM PER BULAN',
                  subtext: train_tahun,
                },
                tooltip: {
                  trigger: 'axis',
                  axisPointer: {            
                    type: 'shadow'        
                  }
                },
                toolbox: {
                  show: true,
                  feature: {
                    // dataView: {show: true, readOnly: false},
                    // restore: {show: true},
                    saveAsImage: {show: true}
                  }
                },
                grid: {
                  borderWidth: 0,
                  y: 80,
                  y2: 60
                },
                xAxis: [
                {
                  type: 'category',
                  show: true,
                  data: namaBulan,
                }
                ],
                yAxis: [
                {
                  type: 'value',
                  show: true
                }
                ],
                series: [
                {
                  name: 'Training Hours',
                  type: 'bar',
                  itemStyle: {
                    normal: {
                      color: function(params) {
                              // build a color map as your need.
                              var colorList = [
                              '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                              '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                              '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                              ];
                              return colorList[params.dataIndex]
                            },
                            label: {
                              show: false,
                              position: 'bottom',
                              formatter: '{b}\n{c}'
                            }
                          }
                        },
                        data: [response[0], response[1], response[2], response[3], response[4], response[5], response[6], response[7], response[8], response[9], response[10], response[11]],
                        markPoint: {
                          tooltip: {
                            trigger: 'item',
                            backgroundColor: 'rgba(0,0,0,0)',
                            formatter: function(params){
                              return '<img src="' 
                              + params.data.symbol.replace('image://', '')
                              + '"/>';
                            }
                          },
                          data: [
                          {xAxis:0, y: 350, name:'sd', symbolSize:20},
                          {xAxis:1, y: 350, name:'smp', symbolSize:20},
                          {xAxis:3, y: 350, name:'sma', symbolSize:20},
                          {xAxis:3, y: 350, name:'d3', symbolSize:20},
                          {xAxis:3, y: 350, name:'s1', symbolSize:20},
                          {xAxis:3, y: 350, name:'s2', symbolSize:20},
                          ]
                        }
                      }
                      ]
                    };
               
              // use configuration item and data specified to show chart
              myChart.setOption(option);
              myChart.on('click', function (data) {
                if(data.name == 'Jan'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/1/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Feb'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/2/"+train_tahun;
                  window.open(link);
                } else if(data.name == 'Mar'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/3/"+train_tahun;
                  window.open(link);
                } else if(data.name == 'Apr'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/4/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'May'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/5/"+train_tahun;
                  window.open(link);
                }
                else if(data.name == 'Jun'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/6/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Jul'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/7/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Aug'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/8/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Sep'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/9/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Oct'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/10/"+train_tahun;
                  window.open(link);
                }else if(data.name == 'Nov'){
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/11/"+train_tahun;
                  window.open(link);
                }else{
                  link = "<?php echo base_url()?>index.php/Training/rekap_training/12/"+train_tahun;
                  window.open(link);
                }
              })
             },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });         
       }

       function train_comp()
       {
        var train_tahun = document.getElementById('train_cthn').value;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>index.php/Training/training_comp", // Isi dengan url/path file php yang dituju
            data: {train_tahun : train_tahun}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
            /*  console.log(response[0]);
              console.log(response[1]);
*/
              const nilai = [];
              for (let i = 0; i < response[1].length; i++) {
                    nilai.push({value : response[1][i], name: response[0][i]});
                  }
              // console.log(nilai);
              
              var myChart = echarts.init(document.getElementById('main12'));

              // specify chart configuration item and data
              var option = {
                title : {
                  text: 'Training Kompetensi',
                  subtext: train_tahun,
                  x:'center'
                },
                tooltip : {
                  trigger: 'item',
                  formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                  orient : 'vertical',
                  x : 'left',
                  data:response[0]
                },
                toolbox: {
                  show : true,
                  feature : {
                    mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Training Kompetensi',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    // shadowBlur:3,
                    // shadowOffsetX: 2,
                    // shadowOffsetY: 2,
                    // shadowColor: '#999',
                    // padding: [0, 7],
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        //     backgroundColor: '#333',
                        //     width: '100%',
                        //     align: 'right',
                        //     height: 22,
                        //     borderRadius: [4, 4, 0, 0]
                        // },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:nilai
                }
                ]
              };

              // use configuration item and data specified to show chart
              myChart.setOption(option);
              myChart.on('click', function (data) {
                comp = data.name;
                comp = comp.replace("&", "-");
                link = "<?php echo base_url()?>index.php/Training/rekap_by_category/"+train_tahun+"/"+comp;
                window.open(link);
             })
            },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
    }

    function table_turnover(thn_over)
    {
      $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/turn_over_table", // Isi dengan url/path file php yang dituju
            data : {thn_over : thn_over},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){
              var col_bulan = response[0]; // nama bulan
              var row1 = response[1]; // norma under 1 year
              var row2 = response[2]; // by norma
              var row3 = response[3]; // non norma

              var eTable="<table class='table'><thead><tr><td>Category</td>"
              for(var i=0; i<col_bulan.length;i++)
              {
                eTable += "<td>"+col_bulan[i]+"</td>";
              }
              eTable +="</tr></thead>";

              eTable +="<tbody><tr><td>Norm < 1y</td>";
              for(var r1=0; r1<row1.length;r1++ )
              {
                eTable += "<td>"+row1[r1]+"</td>";
              }
              eTable +="</tr>";

              eTable +="<tr><td>By Norm</td>";
              for(var r2=0; r2<row2.length;r2++ )
              {
                eTable += "<td>"+row2[r2]+"</td>";
              }
              eTable +="</tr>";

              eTable +="<tr><td>Non Norm</td>";
              for(var r3=0; r3<row3.length;r3++ )
              {
                eTable += "<td>"+row3[r3]+"</td>";
              }
              eTable +="</tr>";

              eTable +="</tbody></table>";

              $('#forTable').html(eTable);
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
           }
      });
    }
     </script>

<!-- CHART INFO CHART -->
      <script type="text/javascript">
        function info_source(){
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/info_chart", // Isi dengan url/path file php yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              document.getElementById('online').value = response[0];
             document.getElementById('offline').value = response[1];
             // based on prepared DOM, initialize echarts instance
             var myChart = echarts.init(document.getElementById('main13'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Source',
            subtext: yyyy,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Online','Offline']
          },
          toolbox: {
            show : true,
            feature : {
              mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Source',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    // shadowBlur:3,
                    // shadowOffsetX: 2,
                    // shadowOffsetY: 2,
                    // shadowColor: '#999',
                    // padding: [0, 7],
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        // abg: {
                        //     backgroundColor: '#333',
                        //     width: '100%',
                        //     align: 'right',
                        //     height: 22,
                        //     borderRadius: [4, 4, 0, 0]
                        // },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:[
                  {value:document.getElementById('online').value, name:'Online'},
                  {value:document.getElementById('offline').value, name:'Offline'}
                  ]
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == 'Online'){
            link = "<?php echo base_url()?>Karyawan/source/Online";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/source/Offline";
           window.open(link);
         }
       })
      },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });
    }   
     </script>

     <!-- CHART PENDIDIKAN -->
     <script type="text/javascript">
      function pendidikan_kandidat(){
        // based on prepared DOM, initialize echarts instance
         $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/pendidikan_kandidat", // Isi dengan url/path file php yang dituju
            data: {train_tahun : train_tahun}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
            var myChart = echarts.init(document.getElementById('main14'));

            // specify chart configuration item and data
            var option = {
              title: {
                x: 'center',
                text: 'Pendidikan Kandidat',
                subtext: yyyy,
              },
              tooltip: {
                trigger: 'item'
              },
              toolbox: {
                show: true,
                feature: {
                  // dataView: {show: true, readOnly: false},
                  // restore: {show: true},
                  saveAsImage: {show: true}
                }
              },
              calculable: true,
              grid: {
                borderWidth: 0,
                y: 80,
                y2: 60
              },
              xAxis: [
              {
                type: 'category',
                show: false,
                data: ['SD','SMP','SMA','D3','S1','S2']
              }
              ],
              yAxis: [
              {
                type: 'value',
                show: false
              }
              ],
              series: [
              {
                name: 'Education',
                type: 'bar',
                itemStyle: {
                  normal: {
                    color: function(params) {
                            // build a color map as your need.
                            var colorList = [
                            '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                            '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                            '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                            ];
                            return colorList[params.dataIndex]
                          },
                          label: {
                            show: true,
                            position: 'top',
                            formatter: '{b}\n{c}'
                          }
                        }
                      },
                      data: response,
                      markPoint: {
                        tooltip: {
                          trigger: 'item',
                          backgroundColor: 'rgba(0,0,0,0)',
                          formatter: function(params){
                            return '<img src="' 
                            + params.data.symbol.replace('image://', '')
                            + '"/>';
                          }
                        },
                        data: [
                        {xAxis:0, y: 350, name:'sd', symbolSize:20},
                        {xAxis:1, y: 350, name:'smp', symbolSize:20},
                        {xAxis:3, y: 350, name:'sma', symbolSize:20},
                        {xAxis:3, y: 350, name:'d3', symbolSize:20},
                        {xAxis:3, y: 350, name:'s1', symbolSize:20},
                        {xAxis:3, y: 350, name:'s2', symbolSize:20},
                        ]
                      }
                    }
                    ]
                  };
       
        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == 'SD'){
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/SD";
            window.open(link);
          }else if(data.name == 'SMP'){
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/SMP";
            window.open(link);
          } else if(data.name == 'SMA'){
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/SMA";
            window.open(link);
          } else if(data.name == 'D3'){
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/D3";
            window.open(link);
          }else if(data.name == 'S1'){
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/S1";
            window.open(link);
          }else{
            link = "<?php echo base_url()?>Karyawan/kandidat_pendidikan/S2";
            window.open(link);
          }
        })
      },
       error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
          }
        });
    }

      </script>

      <!-- CHART LEADTIME -->
      <script type="text/javascript">
        function leadtime()
        {
         rec_tahun = document.getElementById('rec_thn').value;

         $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>index.php/Recruitment/dash_recruitment", // Isi dengan url/path file php yang dituju
            data : {rec_tahun : rec_tahun},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // console.log(response[0]);
               // based on prepared DOM, initialize echarts instance
               var myChart = echarts.init(document.getElementById('main15'));

              // specify chart configuration item and data
              var option = {
                title: {
                  x: 'center',
                  text: 'RECRUITMENT FULLFILLMENT',
                  subtext: rec_tahun,
                },
                tooltip: {
                  trigger: 'axis',
                  axisPointer: {            
                    type: 'shadow'        
                  }
                },
                toolbox: {
                  show: true,
                  feature: {
                    // dataView: {show: true, readOnly: false},
                    // restore: {show: true},
                    saveAsImage: {show: true}
                  }
                },
                grid: {
                  borderWidth: 0,
                  y: 80,
                  y2: 60
                },
                xAxis: [
                {
                  type: 'category',
                  show: true,
                  data: response[0],
                }
                ],
                yAxis: [{
                  type: "value",
                  name: "%",
                  axisLabel: {
                    formatter: "{value} %"
                  }
                }],
                series: [
                {
                  name: 'Leadtime percentage',
                  type: 'bar',
                  itemStyle: {
                    normal: {
                      color: function(params) {
                              // build a color map as your need.
                              var colorList = [
                              '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                              '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                              '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                              ];
                              return colorList[params.dataIndex]
                            },
                            label: {
                              show: false,
                              position: 'bottom',
                              formatter: "{value} %"
                            }
                          }
                        },
                        data: response[3],
                      }
                      ]
                    };

              // use configuration item and data specified to show chart
              myChart.setOption(option);
              myChart.on('click', function (data) {
                  link = "<?php echo base_url()?>index.php/Recruitment/rekap_recruitment/"+data.name+"/"+train_tahun;
                  window.open(link);
              })
            },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });         
    }
  </script>

  <!-- CHART GENDER -->
  <script type="text/javascript">
    function vaksin_covid(){
      var myChart = echarts.init(document.getElementById('main16'));
        var option = {
          title : {
            text: 'Vaccine Covid19',
            subtext: yyyy,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Sudah','Belum']
          },
          toolbox: {
            show : true,
            feature : {
              mark : {show: true},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Vaccine',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:[
                  {value:document.getElementById('vsudah').value, name:'Sudah'},
                  {value:document.getElementById('vbelum').value, name:'Belum'}
                  ]
                }
                ]
              };

              myChart.setOption(option);
              myChart.on('click', function (data) {
                if(data.name == 'Sudah'){
                  link = "<?php echo base_url()?>Karyawan/vaksin_view/Sudah";
                  window.open(link);
                }else{
                 link = "<?php echo base_url()?>Karyawan/vaksin_view/Belum";
                 window.open(link);
               }
             })


            }

  </script>
  <script>
    function dash_absen1()
       {
        var month = document.getElementById('abulan').value;
        var year = document.getElementById('atahun').value;
        var filter = document.getElementById('afilterbo').value;
        var total_karyawan = document.getElementById('total_karyawan').value;
        var  monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
        ];
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/dash_absen1", // Isi dengan url/path file php yang dituju
            data: {bulan : month, tahun : year, filter:filter, total_karyawan:total_karyawan}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(data, response){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              // based on prepared DOM, initialize echarts instance
              var myChart = echarts.init(document.getElementById('main6'));

                // specify chart configuration item and data
                var option = {
                  title: {
                    x: 'center',
                    text: 'Tidak Hadir Karyawan',
                    subtext: monthNames[month-1]+" "+year,
                  },
                  tooltip: {
                    trigger: 'axis',
                    axisPointer: {            
                      type: 'shadow' 
                    }  
                  },
                  toolbox: {
                    show: true,
                    feature: {
                      // dataView: {show: true, readOnly: false},
                      // restore: {show: true},
                      saveAsImage: {show: true}
                    }
                  },
                  // calculable: true,
                  grid: {
                    borderWidth: 0,
                    y: 80,
                    y2: 60
                  },
                  xAxis: [
                  {
                    type: 'category',
                    show: true,
                    data: ['SID', 'C','P1','H1', 'M']
                  }
                  ],
                  yAxis: [{
                  type: "value",
                  name: "%",
                  axisLabel: {
                    formatter: "{value} %"
                  }
                }],
                  series: [
                  {
                    name: 'Absen',
                    type: 'bar',
                    itemStyle: {
                      normal: {
                        color: function(params) {
                                // build a color map as your need.
                                var colorList = [
                                '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                                '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                                '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                                ];
                                return colorList[params.dataIndex]
                              },
                              label: {
                                show: true,
                                position: 'top',
                                formatter: '{b}\n{c}'
                              }
                            }
                          },
                          data: [data[0], data[1], data[2], data[3], data[4]],
                          markPoint: {
                            tooltip: {
                              trigger: 'item',
                              backgroundColor: 'rgba(0,0,0,0)',
                              formatter: function(params){
                                return '<img src="' 
                                + params.data.symbol.replace('image://', '')
                                + '"/>';
                              }
                            },
                            data: [
                            {xAxis:0, y: 350, name:'SID', symbolSize:20},
                            {xAxis:1, y: 350, name:'C', symbolSize:20},
                            {xAxis:2, y: 350, name:'P1', symbolSize:20},
                            {xAxis:3, y: 350, name:'H1', symbolSize:20},
                            {xAxis:4, y: 350, name:'M', symbolSize:20},
                            ]
                          }
                        }
                        ]
                      };

                // use configuration item and data specified to show chart
                myChart.setOption(option);
                myChart.on('click', function (data) {
                  jenis = data.name;
                    link = "<?php echo base_url()?>Karyawan/rekap_dash1_absen/"+jenis+"/"+month+"/"+year+"/"+filter;
                    window.open(link);
               })
            },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
      }
    </script>

     <!-- CHART PERSENTASE KESELURUHAN ABSENSI -->
      <script type="text/javascript">
        function dash_absen2(){
          thn_absen = document.getElementById('thn_absen').value = document.getElementById('tahun_absen').value;
          $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/turn_over", // Isi dengan url/path file php yang dituju
            data : {thn_over : thn_over},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // console.log(response);
               // based on prepared DOM, initialize echarts instance
               table_turnover(thn_over);
               var myChart = echarts.init(document.getElementById('main10'));

        // specify chart configuration item and data
        option = {
          title: {
            text: 'Norm < 1 Year - By Norm - Non Norm',
            textStyle: {
              fontSize: 14
            }
          },
          tooltip: {
            trigger: 'item',
            formatter: "{b} <br> {c}%"
          },
          legend: {
            data: ['Norm < 1 Year', 'By Norm', 'Non Norm'],
            right:20,
            top: 10,
          },
          toolbox: {
            feature: {
              saveAsImage: {}
            }
          },
          xAxis: {
            type: 'category',
            data: response[0],
          },
          yAxis: {
            type: 'value',
            name: "%",
            axisLabel: {
              formatter: "{value} %"
            } 
          },
          series: [{
            name : 'Norm < 1 Year',
            data: response[1],
            type: 'line',
            smooth: true
          },
          {
            name : 'By Norm',
            data: response[2],
            type: 'line',
            smooth: true
          },
          {
            name : 'Non Norm',
            data: response[3],
            type: 'line',
            smooth: true
          }]
        };
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.seriesName == 'Non Norm'){
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/non_norm/"+bulan_cek+"/"+thn_over;
            window.open(link);
          }else if(data.seriesName == 'By Norm'){
             var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/by_norm/"+bulan_cek+"/"+thn_over;
            window.open(link);
          }else{
            var bulan_cek = data.name;
            link = "<?php echo base_url()?>Karyawan/norm_under1/"+bulan_cek+"/"+thn_over;
           window.open(link);
         }
       })
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
      });
       
   }

   function dominan_disc(){
        var filter_disc = document.getElementById('filter_disc').value;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/disc_chart", // Isi dengan url/path file php yang dituju
            data: {filter_disc : filter_disc}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              document.getElementById('domD').value = response[0];
             document.getElementById('domI').value = response[1];
             document.getElementById('domS').value = response[2];
             document.getElementById('domC').value = response[3];
             // based on prepared DOM, initialize echarts instance
             var myChart = echarts.init(document.getElementById('main17'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Dominan DISC',
            subtext: yyyy,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Dominant','Influent', 'Steadlines','Compliant']
          },
          toolbox: {
            show : true,
            feature : {
              mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Profile Type',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    // shadowBlur:3,
                    // shadowOffsetX: 2,
                    // shadowOffsetY: 2,
                    // shadowColor: '#999',
                    // padding: [0, 7],
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        // abg: {
                        //     backgroundColor: '#333',
                        //     width: '100%',
                        //     align: 'right',
                        //     height: 22,
                        //     borderRadius: [4, 4, 0, 0]
                        // },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:[
                  {value:document.getElementById('domD').value, name:'Dominant'},
                  {value:document.getElementById('domI').value, name:'Influent'},
                  {value:document.getElementById('domS').value, name:'Steadlines'},
                  {value:document.getElementById('domC').value, name:'Compliant'},
                  ]
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
           link = "<?php echo base_url()?>Karyawan/dominan_disc/"+data.name;
            window.open(link);
       })
      },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });
    }

    function profile_disc()
       {
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/profile_type", // Isi dengan url/path file php yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
            /*  console.log(response[0]);
              console.log(response[1]);
*/
              const nilai = [];
              for (let i = 0; i < response[1].length; i++) {
                    nilai.push({value : response[1][i], name: response[0][i]});
                  }
              // console.log(nilai);
              
              var myChart = echarts.init(document.getElementById('main18'));

              // specify chart configuration item and data
              var option = {
                title : {
                  text: 'DISC Profile Type',
                  x:'center'
                },
                tooltip : {
                  trigger: 'item',
                  formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                   type: 'scroll',
                  orient: 'vertical',
                  right: 10,
                  top: 20,
                  bottom: 20,
                  data:response[0]
                },
                toolbox: {
                  show : true,
                  feature : {
                    mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Profile Type',
            type:'pie',
            radius : '55%',
            center: ['30%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    // shadowBlur:3,
                    // shadowOffsetX: 2,
                    // shadowOffsetY: 2,
                    // shadowColor: '#999',
                    // padding: [0, 7],
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                        //     backgroundColor: '#333',
                        //     width: '100%',
                        //     align: 'right',
                        //     height: 22,
                        //     borderRadius: [4, 4, 0, 0]
                        // },
                        hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:nilai
                }
                ]
              };

              // use configuration item and data specified to show chart
              myChart.setOption(option);
              myChart.on('click', function (data) {
                comp = data.name;
                // comp = comp.replace("&", "-");
                link = "<?php echo base_url()?>karyawan/rekap_profile_type/"+comp;
                window.open(link);
             })
            },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
    }

    function realisasi_lembur_tahun(){
        var lb_dept = document.getElementById('lb_dept').value;
        var lb_tahun = document.getElementById('lb_tahun').value;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/chart_lembur_tahun", // Isi dengan url/path file php yang dituju
            data: {lb_dept : lb_dept, lb_tahun : lb_tahun}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
            document.getElementById('lbudget').value = response[0];
            document.getElementById('lreal').value = response[1];
             // based on prepared DOM, initialize echarts instance
             var myChart = echarts.init(document.getElementById('main19'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Realisasi Budget Lembur',
            subtext: lb_tahun,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Budget','Realisasi']
          },
          toolbox: {
            show : true,
            feature : {
              mark : {show: true},
              // dataView : {show: true, readOnly: false},
              magicType : {
                show: true, 
                type: ['pie', 'funnel'],
                option: {
                  funnel: {
                    x: '25%',
                    width: '50%',
                    funnelAlign: 'left',
                    max: 1548
                  }
                }
              },
              // restore : {show: true},
              saveAsImage : {show: true}
            }
          },
          calculable : true,
          series : [
          {
            name:'Profile Type',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            label: {
              normal: {
                formatter: '{b} : {c} ({d}%)',
                backgroundColor: '#eee',
                borderColor: '#aaa',
                borderWidth: 1,
                borderRadius: 4,
                    rich: {
                      a: {
                        color: '#999',
                        lineHeight: 22,
                        align: 'center'
                      },
                      hr: {
                          borderColor: '#aaa',
                          width: '100%',
                          borderWidth: 0.5,
                          height: 0
                        },
                        b: {
                          fontSize: 16,
                          lineHeight: 33
                        },
                        per: {
                          color: '#eee',
                          backgroundColor: '#334455',
                          padding: [2, 4],
                          borderRadius: 2
                        }
                      }
                    }
                  },
                  data:[
                  {value:document.getElementById('lbudget').value, name:'Budget'},
                  {value:document.getElementById('lreal').value, name:'Realisasi'},
                  ]
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
           link = "<?php echo base_url()?>Lembur/det_lembur_tahun/"+data.name+"/"+lb_dept+"/"+lb_tahun;
            window.open(link);
       })
      },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });
    }

    function realisasi_lembur_bulan(){
          var lb_dept = document.getElementById('lb_dept2').value;
          var lb_tahun = document.getElementById('lb_tahun2').value;
          $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Karyawan/chart_lembur_bulan", // Isi dengan url/path file php yang dituju
            data: {lb_dept : lb_dept, lb_tahun : lb_tahun},
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(data, response){ // Ketika proses pengiriman berhasil
              // console.log(response);
               // based on prepared DOM, initialize echarts instance
               var myChart = echarts.init(document.getElementById('main20'));

        // specify chart configuration item and data
        option = {
          title: {
            text: 'Budget vs Realiasasi Lembur',
            textStyle: {
              fontSize: 14
            }
          },
          tooltip: {
            trigger: 'item',
            formatter: "{b} <br> {c} Jam"
          },
          legend: {
            data: ['Budget', 'Realisasi'],
            right:20,
            top: 10,
          },
          toolbox: {
            feature: {
              saveAsImage: {}
            }
          },
          xAxis: {
            type: 'category',
            data: data[0],
            axisLabel: {
              interval: 0,
              rotate: 30 //If the label names are too long you can manage this by rotating the label.
            }
          },
          yAxis: {
            type: 'value',
            name: "H",
            axisLabel: {
              formatter: "{value}"
            } 
          },
          series: [{
            name : 'Budget',
            data: data[1],
            type: 'bar',
            smooth: true
          },
          {
            name : 'Realisasi',
            data: data[2],
            type: 'bar',
            smooth: true
          }]
        };
        myChart.setOption(option);
        myChart.on('click', function (data) {
        //   if(data.seriesName == 'Budget'){
        //     var bulan_cek = data.name;
        //     link = "<?php echo base_url()?>Karyawan/non_norm/"+bulan_cek+"/"+thn_over;
        //     window.open(link);
        //   }else{
        //     var bulan_cek = data.name;
        //     link = "<?php echo base_url()?>Karyawan/norm_under1/"+bulan_cek+"/"+thn_over;
        //    window.open(link);
        //  }
       })
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
      });
       
   }

       
     </script>
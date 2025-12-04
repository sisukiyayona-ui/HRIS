      <?php 
        //JUMLAH TOTAL KARYAWAN
      foreach ($karyawan as $totkar) {
        $total = $totkar->total;
      }
         //JUMLAH TOTAL SPM
      foreach ($spm as $totspm) {
        $total_spm = $totspm->total;
      }

        //JUMLAH PEREMPUAN
      foreach ($P as $ce) {
        $perempuan = $ce->p;
      }
      // JUMLAH LAKI_LAKI
      foreach ($L as $co) {
        $laki = $co->l;
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
        // JUMLAH BELUM SK
      foreach ($blm_sk as $sk) {
        $blm_sk = $sk->blm_sk;
      }
         // JUMLAH OPEN RECRUITMENT
      foreach ($recruitment as $open_rec) {
        $open_rec = $open_rec->rec;
      }
         // JUMLAH TRAINING
      foreach ($training as $train) {
        $training = $train->train;
      }
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
        $uk35 = $umur->kurang35;
        $um36 = $umur->u3644;
        $u45 = $umur->lebih45;
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

      ?>

      <?php $role = $this->session->userdata('role_id'); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row tile_count">
         <?php
         if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6' or $role == '22'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
            <div class="count"><?php echo $total; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
        <?php
        if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6' or $role == '22'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-male"></i> Total Male</span>
            <div class="count"><?php echo $laki; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_male" data-toggle="tooltip" data-placement="bottom" title="Karyawan Pria Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
        <?php
        if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6' or $role == '22'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-female"></i> Total Female</span>
            <div class="count"><?php echo $perempuan; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_female" data-toggle="tooltip" data-placement="bottom" title="Karyawan Wanita Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
        <?php
        if($role == '1' or $role == '2'  or $role == '6' or $role == '22'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-star-half-o"></i> Data Belum Lengkap</span>
          <div class="count"><?php echo $belum; ?></div>
          <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_belum_lengkap" data-toggle="tooltip" data-placement="bottom" title="Data Karyawan Belum Lengkap">details</a> Data</span>
        </div>
      <?php } ?>
      <?php
      if($role == '1' or $role == '2' or $role == '6' or $role == '22'){ ?>
       <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-file-pdf-o"></i> Belum SK Karyawan</span>
        <div class="count"><?php echo $blm_sk ?></div>
        <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_blm_sk" data-toggle="tooltip" data-placement="bottom" title="Karyawan Tidak Ada SK">details</a> Data</span>
      </div>
    <?php } ?>
     <?php
         if($role == '1' or $role == '2' or $role == '3' or $role == '4' or $role == '5' or $role == '6' or $role == '22'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total SPM</span>
            <div class="count"><?php echo $total_spm; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totspm" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan SPM">details</a> SPM</span>
          </div>
        <?php } ?>
    <?php
    if($role == '1' or $role == '2' or $role == '6' or $role == '22'){ ?>
     <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-bullhorn"></i> Open Recruitment</span>
      <div class="count"><?php echo $open_rec ?></div>
      <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/recruitment_open" data-toggle="tooltip" data-placement="bottom" title="Open Recruitment">details</a> Data</span>
    </div>
  <?php } ?>

  <?php
  if($role == '1' or $role == '2' or $role == '6' or $role == '22'){ ?>
   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-book"></i> Training</span>
    <div class="count"><?php echo $training ?></div>
    <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/training_view" data-toggle="tooltip" data-placement="bottom" title="Training Karyawan">details</a> Data</span>
  </div>
<?php } ?>

<?php
if($role == '1' or $role == '3' or $role == '5' or $role == '6' or $role == '22'){ ?>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-clock-o" style="color: red"></i> Total Keterlambatan</span>
    <div class="count"><?php if($terlambat > 0){echo "<blink>$terlambat</blink>";}else{echo $terlambat;}  ?></div>
    <span class="count_bottom"><a class="red" href="<?php echo base_url()?>Karyawan/terlambat" data-toggle="tooltip" data-placement="bottom" title="Keterlambatan Karyawan">details</a> Absen</span>
  </div>
<?php } ?>
<?php
if($role == '1' or $role == '4' or $role == '6' or $role == '22'){ ?>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-balance-scale"></i> Total Legal</span>
    <div class="count"><?php echo $legal; ?></div>
    <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/legal_open" data-toggle="tooltip" data-placement="bottom" title="Legal">details</a> Legal</span>
  </div>
<?php } ?>
<?php
if($role == '1' or $role == '4' or $role == '6' or $role == '22'){ ?>
 <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-file-pdf-o"></i> Total Perjanjian</span>
  <div class="count"><?php echo $janji_l ?></div>
  <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_perjanjian" data-toggle="tooltip" data-placement="bottom" title="Open Perjanjian">details</a> Data</span>
</div>
<?php } ?>
<?php
if($role == '1' or $role == '4' or $role == '6' or $role == '22'){ ?>
 <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-file-pdf-o"></i> Total Perizinan</span>
  <div class="count"><?php echo $izin_l ?></div>
  <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_perizinan" data-toggle="tooltip" data-placement="bottom" title="Open Perizinan">details</a> Data</span>
</div>
<?php } ?>

<?php
if($role == '1' or $role == '4' or $role == '6' or $role == '22'){ ?>
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
  if($role == '1' or $role == '5' or $role == '6' or $role == '22'){ ?>
   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-file"></i> Total Pengajuan</span>
    <div class="count"><?php echo $pengajuan;  ?></div>
    <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/plembur_view" data-toggle="tooltip" data-placement="bottom" title="Pengajuan Lembur">details</a> Data</span>
  </div>
<?php } ?>

<?php
if($role == '1' or $role == '5' or $role == '6' or $role == '22'){ ?>
 <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-warning" style="color: red"></i> Lembur Belum Realisasi</span>
  <div class="count" style="color: red"><?php if($unreal > 0){echo "<blink>$unreal</blink>";}else{echo $unreal;}  ?></div>
  <span class="count_bottom"><a class="red"  href="<?php echo base_url()?>Karyawan/unrealisasi" data-toggle="tooltip" data-placement="bottom" title="Lembur Belum Realisasi">details</a> Data</span>
</div>
<?php } ?>


</div>
<!-- /top tiles -->

<?php
if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6' or $role == '22'){ ?>
  <!-- Gender -->
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Gender</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <input type="hidden" id="cpria" value="<?php echo $laki ?>">
          <input type="hidden" id="cwanita" value="<?php echo $perempuan ?>">
          <div id="main" style="height:350px;"></div>

        </div>
      </div>
    </div>
    <!-- end gender -->
  <?php } ?>

  <?php
  if($role == '1' or $role == '2' or $role == '5' or $role == '6' or $role == '22'){ ?>
    <!-- Pendidikan -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Pendidikan</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div id="main2" style="height:350px;"></div>

        </div>
      </div>
    </div>
    <!-- end pendidikan -->
  <?php } ?>

  <?php
  if($role == '1' or $role == '2' or $role == '5' or $role == '6' or $role == '22'){ ?>
    <!-- Rentang Usia -->
    <div class="row">
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Rentang Usia</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div id="main3" style="height:350px;"></div>

          </div>
        </div>
      </div>
      <!-- end rentang usia -->
    <?php } ?>

    <?php
    if($role == '1' or $role == '2' or $role == '5' or $role == '6' or $role == '22'){ ?>
      <!-- masa kerja -->
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Masa Kerja</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div id="main4" style="height:350px;"></div>

          </div>
        </div>
      </div>

    </div>
    <!-- end masa kerja -->
  <?php } ?>

  <?php
  if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6' or $role == '4' or $role == '22'){ ?>
    <!-- masuk - keluar -->
    <div class="row">
      <div class="col-md-4 col-sm-4 col-xs-12" <?php if($role == '4'){echo "style='display:none'";} ?>>
        <div class="x_panel">
          <div class="x_title">
            <h2>Karyawan Masuk - Keluar</h2>
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
   <?php } ?>
   <?php
   if($role == '1' or $role == '2' or $role == '4' or $role == '6' or $role == '22'){ ?>
    <!-- Notif -->
    <?php if($role == '4'){ ?>
      <div class="row">
      <?php } ?>
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Notifikasi Legal</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div style="height:350px;" >
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

            <table id="notif" class="table table-striped table-bordered" style="display: none">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Judul</th>
                  <th>Tgl Akhir</th>
                  <th>File</th>
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
 var today = new Date();
 var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
        ];

        if(dd<10) {
          dd = '0'+dd
        } 

        if(mm<10) {
          mm = '0'+mm
        } 

        today = monthNames[today.getMonth()];
        periode = today + " " +yyyy;
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
            data: [' <= 35', '36 - 44','>= 45']
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
                  data: ['<?php echo $uk35 ?>', '<?php echo $um36 ?>','<?php echo $u45 ?>'],
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
                    {xAxis:0, y: 350, name:'... - 35', symbolSize:20},
                    {xAxis:1, y: 350, name:'36 - 44', symbolSize:20},
                    {xAxis:3, y: 350, name:'45 - ...', symbolSize:20},
                    ]
                  }
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == ' <= 35'){
            link = "<?php echo base_url()?>Karyawan/usia/kurang35";
            window.open(link);
          }else if(data.name == '36 - 44'){
            link = "<?php echo base_url()?>Karyawan/usia/u3644";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/usia/lebih45";
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

      <!-- CHART GENDER -->
      <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Gender',
            subtext: periode,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['Pria','Wanita']
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
                  {value:document.getElementById('cpria').value, name:'Pria'},
                  {value:document.getElementById('cwanita').value, name:'Wanita'}
                  ]
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
        myChart.on('click', function (data) {
          if(data.name == 'Wanita'){
            link = "<?php echo base_url()?>Karyawan/gender/Wanita";
            window.open(link);
          }else{
           link = "<?php echo base_url()?>Karyawan/gender/pria";
           window.open(link);
         }
       })
     </script>

     <!-- CHART PENDIDIKAN -->
     <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main2'));

        // specify chart configuration item and data
        var option = {
          title : {
            text: 'Pendidikan',
            subtext: periode,
            x:'center'
          },
          tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
            orient : 'vertical',
            x : 'left',
            data:['SD','SMP','SMA','D3','S1','S2']
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
            name:'Pendidikan',
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
                  {value:<?php echo $sd; ?>, name:'SD'},
                  {value:<?php echo $smp; ?>, name:'SMP'},
                  {value:<?php echo $sma; ?>, name:'SMA'},
                  {value:<?php echo $d3; ?>, name:'D3'},
                  {value:<?php echo $s1; ?>, name:'S1'},
                  {value:<?php echo $s2; ?>, name:'S2'}
                  ]
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
            inout();
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

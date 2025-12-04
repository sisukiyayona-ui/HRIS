      <?php 
        //JUMLAH TOTAL KARYAWAN
      foreach ($karyawan as $totkar) {
        $total = $totkar->total;
      }
        //JUMLAH PEREMPUAN
      foreach ($P as $ce) {
        $perempuan = $ce->p;
      }
      // JUMLAH LAKI_LAKI
      foreach ($L as $co) {
        $laki = $co->l;
      }
     
      // JUMLAH BELUM LENGKAP
      foreach ($belum as $belum) {
        $belum = $belum->blm_lengkap;
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
       
      ?>

      <?php $role = $this->session->userdata('role_id'); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row tile_count">
         <?php
         if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
            <div class="count"><?php echo $total; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar" data-toggle="tooltip" data-placement="bottom" title="Semua Karyawan Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
        <?php
        if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-male"></i> Total Male</span>
            <div class="count"><?php echo $laki; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_male" data-toggle="tooltip" data-placement="bottom" title="Karyawan Pria Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
        <?php
        if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6'){ ?>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-female"></i> Total Female</span>
            <div class="count"><?php echo $perempuan; ?></div>
            <span class="count_bottom"><a class="green" href="<?php echo base_url()?>Karyawan/r_totkar_female" data-toggle="tooltip" data-placement="bottom" title="Karyawan Wanita Aktif">details</a> Karyawan</span>
          </div>
        <?php } ?>
         <?php
         if($role == '1' or $role == '2'  or $role == '6'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-star-half-o"></i> Data Belum Lengkap</span>
            <div class="count"><?php echo $belum; ?></div>
            <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_belum_lengkap" data-toggle="tooltip" data-placement="bottom" title="Data Karyawan Belum Lengkap">details</a> Data</span>
          </div>
        <?php } ?>
         <?php
        if($role == '1' or $role == '2' or $role == '6'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-file-pdf-o"></i> Belum SK Karyawan</span>
          <div class="count"><?php echo $blm_sk ?></div>
          <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/r_blm_sk" data-toggle="tooltip" data-placement="bottom" title="Karyawan Tidak Ada SK">details</a> Data</span>
        </div>
      <?php } ?>
       <?php
        if($role == '1' or $role == '2' or $role == '6'){ ?>
         <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-bullhorn"></i> Open Recruitment</span>
          <div class="count"><?php echo $open_rec ?></div>
          <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/recruitment_open" data-toggle="tooltip" data-placement="bottom" title="Open Recruitment">details</a> Data</span>
        </div>
      <?php } ?>

      <?php
      if($role == '1' or $role == '2' or $role == '6'){ ?>
       <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-book"></i> Training</span>
        <div class="count"><?php echo $training ?></div>
        <span class="count_bottom"><a class="green"  href="<?php echo base_url()?>Karyawan/training_view" data-toggle="tooltip" data-placement="bottom" title="Training Karyawan">details</a> Data</span>
      </div>
    <?php } ?>

    <!-- </div> -->
    <!-- /top tiles -->
    <!-- top tiles -->
    <!-- <div class="row tile_count"> -->
      </div>
      <!-- /top tiles -->

      <?php
      if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6'){ ?>
        <!-- Gender -->
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Gender</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div id="main" style="height:350px;"></div>

              </div>
            </div>
          </div>
          <!-- end gender -->
        <?php } ?>

        <?php
        if($role == '1' or $role == '2' or $role == '5' or $role == '6'){ ?>
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
      if($role == '1' or $role == '2' or $role == '5' or $role == '6'){ ?>
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
        if($role == '1' or $role == '2' or $role == '5' or $role == '6'){ ?>
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
        if($role == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '6'){ ?>
          <!-- masuk - keluar -->
          <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Karyawan Masuk - Keluar</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div id="main5" style="height:350px;"></div>

                </div>
              </div>
            </div>
            <!-- end masuk keluar -->
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
<?php
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
  $u3 = $umur->kurang3;
  $u10 = $umur->u10;
  $u11 = $umur->lebih10;
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

      <!-- CHART INOUT -->
      <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('main5'));
        
        // specify chart configuration item and data
        var option = {
         title: {
          x: 'center',
          text: 'Masuk - Keluar',
          subtext: periode,
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
          {value:<?php echo $masuk ?>, name:'in'},
          {value:<?php echo $keluar ?>, name:'out'}
          ]
        }
        ]
      };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
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
            data: [' < 3', '4 - 10','> 10']
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
                  data: ['<?php echo $u3 ?>', '<?php echo $u10 ?>','<?php echo $u11 ?>'],
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
                    {xAxis:0, y: 350, name:'... - 3', symbolSize:20},
                    {xAxis:1, y: 350, name:'4 - 10', symbolSize:20},
                    {xAxis:3, y: 350, name:'11 - ...', symbolSize:20},
                    ]
                  }
                }
                ]
              };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
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
            {value:<?php echo $laki; ?>, name:'Pria'},
            {value:<?php echo $perempuan; ?>, name:'Wanita'}
            ]
          }
          ]
        };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
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
      </script>
      <!-- ################################################### ABSEN ################################################################### -->

      <script>
        $(document).ready(function() {
          $("#ranges").change(function(){
            var filter = $("#ranges").val();
            var table = $('#notif').DataTable();
            table.destroy();
            var table = $('#notif').DataTable( {
              "responsive":true,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              buttons: [
              'excel', 'print'
              ],
              "ajax": {
                type: "POST",
                url: "<?php echo base_url(); ?>Karyawan/notif",
                dataType: 'json',
                data: {filter: filter},
              },
            });
            $("#notif").show(); 
          });
        });
      </script>

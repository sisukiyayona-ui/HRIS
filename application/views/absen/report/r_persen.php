<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Report Persentase Absensi</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                       <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Absen/persentase" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Bulan</label></div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                             <?php $bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                             $x=0;?>
                             <select name="bulan" class="form-control" id="bulan" onchange="cutoff()">
                              <?php
                                for($i=0;$i<count($bulan);$i++)
                                {?>
                                  <option value="<?php echo $x=$x+1?>"><?php echo $bulan[$i]?></option>
                              <?php } ?>
                             </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Tahun</label></div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <select class="form-control" name="tahun" onchange="cutoff()" id="tahun">
                                <?php 
                                  foreach ($tahun->result() as $t) {?>
                                    <option value="<?php echo $t->tahun?>"><?php echo $t->tahun?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>

                        <!--   <div class="col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Cut Off Mulai</label></div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <div class='input-group date' id='myDatepicker2'>
                                <input type='text' class="form-control" name="sejak" id="tgl_mulai" value="<?php echo date("Y-m-d") ?>" readonly/>
                                <span class="input-group-addon">
                                 <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                             </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Cut Off Akhir</label></div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <div class='input-group date' id='myDatepicker3'>
                                <input type='text' class="form-control" name="sampai" id="tgl_akhir" value="<?php echo date("Y-m-d") ?>" readonly/>
                                <span class="input-group-addon">
                                 <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                             </div>
                            </div>
                            
                          </div> -->

                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <br>
                              <div class="col-md-2 col-sm-2 col-xs-2"><label>Pilih Norma</label></div>
                              <div class="col-md-4 col-sm-2 col-xs-2">
                               <select class="form-control selectpicker" multiple="multiple" data-actions-box="true" data-live-search="true" id="jenis" name="norma[]">
                                <?php 
                                foreach ($jenis->result() as $j) {?>
                                  <option value="<?php echo $j->recid_jenisabsen?>" selected="selected"><?php echo $j->keterangan." - ".$j->jenis?></option>
                                <?php }
                                ?>
                              </select> 
                              </div>
                            </div>
                          </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <br><br>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              <button type="submit" class="btn btn-primary">Cari Report</button>
                            </div>
                          </div>
                       </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

  <script>
  $(document).ready(function(){
    cutoff();
    $("#all").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
     });
  });

  function cutoff()
  {
    bulan = document.getElementById('bulan').value;
    tahun = document.getElementById('tahun').value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/cutoff_hk", // Isi dengan url/path file php yang dituju
            data: {bulan : bulan, tahun : tahun}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             document.getElementById('tgl_mulai').value = response[0];
             document.getElementById('tgl_akhir').value = response[1];
           },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }
</script>

        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Master Budget</h3>
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
                     <h2><a href="<?php echo base_url()?>Lembur/masterbudget_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Master Budget</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/masterbudget_pinsert" novalidate >
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tahun Budget<span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="tahun" id="tahun" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tahun Budget">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian<span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_bag"  id="bagian" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                             <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                              echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <?php 
                        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober','November', 'Desember');
                        for($i=0; $i<count($bulan);$i++){ ?>
                          <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul"><?php echo "$bulan[$i]" ?><span class="required" style="color: red">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="hidden" name="bulan[]" class="form-control col-md-7 col-xs-12" value="<?php echo "$bulan[$i]" ?>">
                              <input type="text" name="jml_jam[]" onkeypress="return hanyaAngka(event)" required="required" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Jam Lembur">
                              <input type="hidden" name="recid_clembur[]" id="<?php echo "$bulan[$i]" ?>"class="form-control col-md-7 col-xs-12" readonly="readonly">
                            </div>
                        </div>
                        <?php }
                      ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="note" name="note" class="form-control col-md-7 col-xs-12" placeholder="Keterangan"></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Lembur/masterbudget_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                         <button id="send" type="submit" class="btn btn-success">Submit</button>
                       </div>
                     </div>
                      </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content-->


        <script>
          Date.prototype.getMonthName = function() {
            var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];
            return monthNames[this.getMonth()];
          }

          $("#tahun").change(function(){
            var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];
            var tahun =  document.getElementById("tahun").value;
            $.ajax({  //---------------------------------------- GET RANGE --------------------------------------------------------
                  type: "POST", // 
                  url: "<?php echo base_url();?>Lembur/get_cutoff", 
                  data: {tahun : tahun}, 
                  dataType: "json",
                  beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                      e.overrideMimeType("application/json;charset=UTF-8");
                    }
                  },
                  success: function(response, data){ // Ketika proses pengiriman berhasil
                    // total = response[3][1];
                    // alert(total);
                    for(banyak = 0; banyak < monthNames.length; banyak++){
                     // bulan = document.getElementById('Januari').value;
                      $('#'+monthNames[banyak]).val(response[banyak][0]);
                     // console.log(bulan);
                    }
                  },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
          });

           $("#bagian").change(function(){
               var tahun =  document.getElementById("tahun").value;
               var bagian =  document.getElementById("bagian").value;
                $.ajax({  //---------------------------------------- GET RANGE --------------------------------------------------------
                  type: "POST", // 
                  url: "<?php echo base_url();?>Lembur/masterbudget_cek", 
                  data: {tahun : tahun, bagian : bagian}, 
                  dataType: "json",
                  beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                      e.overrideMimeType("application/json;charset=UTF-8");
                    }
                  },
                  success: function(data, response){ // Ketika proses pengiriman berhasil
                   if(data == "Ada")
                   {
                    alert("Master Budget Sudah Diinputkan");
                    document.getElementById("send").disabled = true;
                   }else{
                     document.getElementById("send").disabled = false;
                   }
                 },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
           });
        </script>
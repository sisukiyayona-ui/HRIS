
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
                     <h2><a href="<?php echo base_url()?>Karyawan/masterbudget_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Edit Master Budget</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/masterbudget_pupdate" novalidate >
                        <?php foreach ($budget as $data) { } ?>

                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tahun Budget<span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="tahun" id="tahun" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tahun ?>">
                        </div>
                      </div>
                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian<span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_bag" id="bagian" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                              if($data->recid_bag == $option->recid_bag){
                                 echo "<option value='$option->recid_bag' selected>$option->indeks_hr ($option->nama_bag)</option>";
                              }else{
                                 echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <?php 
                        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober','November', 'Desember');
                        for($i=0; $i<count($bulan);$i++){ ?>
                          <!-- echo "$bulan[$i] <br>"; -->
                          <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul"><?php echo "$bulan[$i]" ?> <span class="required" style="color: red">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="hidden" name="bulan[]" class="form-control col-md-2 col-xs-2" value="<?php echo "$bulan[$i]" ?>">
                              <?php
                                $jml_jam = $this->db->query("SELECT * From master_budget where bulan = '$bulan[$i]' and tahun = '$data->tahun' and recid_bag = '$data->recid_bag'")->result();
                                foreach ($jml_jam as $jml) {
                                   # code...
                                 } ?>
                                <input type="text" name="jml_jam[]" onkeypress="return hanyaAngka(event)" required="required" class="form-control col-md-2 col-xs-2" value="<?php echo $jml->jml_jam ?>">

                                <input type="hidden" name="recid_mbl[]"  required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $jml->recid_mbl ?>">
                            </div>
                          </div>
                        <?php }
                      ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"><?php echo $data->note ?></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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
        <!-- /page content -->

<script>
 $("#bagian").change(function(){
   var tahun =  document.getElementById("tahun").value;
   var bagian =  document.getElementById("bagian").value;
                $.ajax({  //---------------------------------------- GET RANGE --------------------------------------------------------
                  type: "POST", // 
                  url: "<?php echo base_url();?>Karyawan/masterbudget_cek", 
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
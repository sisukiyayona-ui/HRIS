<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Pengajuan Lembur</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/plembur_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Pengajuan Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/plembur_pupdate" data-toggle="validator" >
                      <?php foreach ($lembur as $data) {
                        # code...
                      } ?>

                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class='input-group date' id='myDatepicker6'>
                            <input type='text' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" required="required" value="<?php echo $data->tgl_lembur?>" />
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div>
                       </div>
                     </div>
                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type='hidden' class="form-control" name="recid_lembur" value="<?php echo $data->recid_plembur?>" />
                          <select name="recid_bag" id="bag"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                              if($data->recid_bag == $option->recid_bag){
                                echo "<option value='$option->recid_bag' selected>$option->nama_bag</option>";
                              }else{
                                echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kategori Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <?php if($data->kategori == "Kapasitas"){ ?>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required checked="checked"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                             <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php }else if($data->kategori == 'Absensi'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required checked="checked"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Kgagal'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required > Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required checked="checked"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                        <?php }else if($data->kategori == 'Kterlambat'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required > Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required > Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required checked="checked"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Ekirim'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required > Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required > Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required > Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required checked="checked"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                             <?php }else if($data->kategori == 'Administrasi'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required > Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required > Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required > Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required  checked="checked"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Ddinas'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required > Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required > Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required > Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required > Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required checked="checked"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php }else{ ?>
                              <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php } ?>


                          </div>
                      </div>
                    </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Total Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="total_jam" id="total_lembur" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->total_jam ?>">
                          <input type="hidden" name="total_jam2" id="total_lembur2" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->total_jam ?>">
                          <input type="hidden" name="recid_mbl" id="mbl" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_mbl ?>">
                        </div>
                      </div>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Pekerjaan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="pekerjaan" class="form-control" placeholder="kegiatan lembur"><?php echo $data->pekerjaan ?></textarea>
                        </div>
                      </div>
                      <div class="item form-group" id="alasan">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Over Budget <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="alasan_over" class="form-control" placeholder="Alasan Over Budget"><?php echo $data->alasan_over ?></textarea>
                        </div>
                      </div>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Keterangan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="keterangan" class="form-control" placeholder="qty lembur"><?php echo $data->keterangan ?></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Karyawan/plembur_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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



  Date.prototype.getMonthName = function() {
    var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];
    return monthNames[this.getMonth()];
  }

$("#total_lembur").change(function(){
 var tgl = document.getElementById("tgl_lembur").value;
 var tahun = tgl.substring(0, 4);
 var date = new Date(tgl);
 var bulan = date.getMonthName();
 var recid_bag = document.getElementById("bag").value;
 var jam_lembur = document.getElementById("total_lembur").value;
 var total_lembur2 = document.getElementById("total_lembur2").value;
 total_lembur2 = parseInt(total_lembur2);
 jam_lembur = parseInt(jam_lembur);
 console.log(recid_bag);
 $.ajax({ //----------------------------------------- cek master budget ada atau engga -----------------------------------------------
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url();?>Karyawan/masterbudget", // Isi dengan url/path file php yang dituju
        data: {recid_bag :recid_bag, tahun:tahun, tgl, tgl}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data){ // Ketika proses pengiriman berhasil
          if(response == '0'){
            alert("Harap isi master budget!");
          }else{
            $.ajax({  //---------------------------------------- get recid mbl --------------------------------------------------------
              type: "POST", // 
              url: "<?php echo base_url();?>Karyawan/cek_mbl", 
              data: {recid_bag :recid_bag,tgl, tgl}, 
              dataType: "json",
              beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response, data){ // Ketika proses pengiriman berhasil
               recid_mbl = response[1];
              recid_mbl = parseInt(recid_mbl);
              $('#mbl').val(recid_mbl);
              jml_jam = response[0];
                  $.ajax({  //---------------------------------------- cek total lembur --------------------------------------------------------
                  type: "POST", // 
                  url: "<?php echo base_url();?>Karyawan/cek_totalembur", 
                  data: {recid_mbl : recid_mbl}, 
                  dataType: "json",
                  beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                      e.overrideMimeType("application/json;charset=UTF-8");
                    }
                  },
                  success: function(response, data){ // Ketika proses pengiriman berhasil
                    total = response[0][0];
                    if(total == null){
                      total = 0;
                    }else{
                      total = total;
                    }
                    total = parseInt(total);
                    kurang = total + jam_lembur - total_lembur2;
                    sisa = jml_jam - kurang;
                    if(sisa < 0){
                      alert("Master Budget Over, Harap Isi Alasan!");
                       $("#alasan").show();
                    }
                  },
                  error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                  }
                });
              },
              error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          }
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
});
</script>
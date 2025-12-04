<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Realisasi Lembur</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/realisasi_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Realisasi Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <?php
                      foreach ($pengajuan as $data) { }
                     ?>
                     <form id="form_realisasi" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/realisasi_pinsert" novalidate >
                      <?php echo $this->session->flashdata('message'); ?>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="hidden" name="recid_plembur" id="recid_plembur" value="<?php echo $data->recid_plembur ?>">
                          <div class='input-group date' id='myDatepicker2'>
                            <input type='text' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" value="<?php echo $data->tgl_lembur ?>" readonly='readonly'/>
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div>
                       </div>
                     </div>
                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
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
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required checked="checked" disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                             <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php }else if($data->kategori == 'Absensi'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required checked="checked" disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Kgagal'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required  disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required checked="checked" disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                        <?php }else if($data->kategori == 'Kterlambat'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required  disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required  disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required checked="checked" disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Ekirim'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required  disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required  disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required  disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required checked="checked" disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
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
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required  disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required  disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required  disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required  checked="checked" disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                          <?php }else if($data->kategori == 'Ddinas'){?>
                                <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required  disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required  disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required  disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required  disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required checked="checked" disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php }else{ ?>
                              <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kapasitas" required disabled="disabled"> Kapasitas<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Absensi" required disabled="disabled"> Absensi<br>
                              </label>
                            </div>
                            <div class="radio">
                              <label>
                                <input type="radio" name="kategori" class="flat" value="Kgagal" required disabled="disabled"> Komponen Kegagalan<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Kterlambat" required disabled="disabled"> Komponen Terlambat Pengiriman<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                              <input type="radio" name="kategori" class="flat" value="Ekirim" required disabled="disabled"> Pengiriman Barang<br>
                             </label>
                           </div>
                            <div class="radio">
                              <label>
                               <input type="radio" name="kategori" class="flat" value="Ddinas" required disabled="disabled"> Dinas Driver<br>
                             </label>
                           </div>
                            <?php
                                if($dept == "KEU & ADM"){ ?>
                                 <div class="radio">
                                  <label>
                                    <input type="radio" name="kategori" class="flat" value="Administrasi" required disabled="disabled"> Administrasi<br>
                                 </label>
                               </div>
                               <?php  }
                            ?>
                         <?php } ?>


                          </div>
                      </div>
                    </div>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Pengajuan Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="total_jam" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->total_jam ?>" readonly = 'readonly' >
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Realisasi Jam Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="realisasi_jam" id="total_lembur" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Realisasi Jam Lembur" >
                          <input type="hidden" name="recid_mbl" id="mbl" class="form-control col-md-7 col-xs-12" placeholder="mbl">
                        </div>
                      </div>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Pekerjaan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="pekerjaan" class="form-control" value="<?php echo $data->pekerjaan ?>" required="required"><?php echo $data->pekerjaan; ?></textarea>
                        </div>
                      </div>
                      <div class="item form-group" id="alasan" style="display: none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Over Budget 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="alasan_over" class="form-control" placeholder="Alasan Over Budget"><?php echo $data->alasan_over; ?></textarea>
                        </div>
                      </div>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="keterangan" class="form-control" placeholder="qty lembur"><?php echo $data->keterangan; ?></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Karyawan/realisasi_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                         <button type="submit" class="btn btn-success" >Submit</button>
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
 var realisasi_jam = document.getElementById("total_lembur").value;
 realisasi_jam = parseInt(realisasi_jam);
 var recid_plembur = document.getElementById("recid_plembur").value;

 // console.log(recid_bag);
 $.ajax({ //----------------------------------------- cek master budget ada atau engga -----------------------------------------------
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url();?>Karyawan/masterbudget", // Isi dengan url/path file php yang dituju
        data: {recid_bag :recid_bag, tahun:tahun, bulan:bulan, tgl :tgl}, // data yang akan dikirim ke file yang dituju
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
              data: {recid_bag :recid_bag, tahun:tahun,tgl :tgl}, 
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
                    kurang = total + realisasi_jam;
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

// function submi()
// {
//   var mbl = document.getElementById('mbl').value;
//   if(mbl == '')
//   {
//     alert('error');
//   }else{
//     // alert('Completed');
//    $('form_realisasi').submit();
//   }
// }
</script>
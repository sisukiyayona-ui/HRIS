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
                    <h2><a href="<?php echo base_url()?>Lembur/plembur_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Pengajuan Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <form id="form_lembur" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/plembur_pinsert" data-toggle="validator">
                      <?php echo $this->session->flashdata('message'); ?>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type='date' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" required="required" onchange="emp_free_ot()"/>
                            
                          <!-- <div class='input-group date' id='myDatepicker2'>
                            <input type='text' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" required="required" onchange="emp_free_ot()"/>
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div> -->
                       </div>
                     </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_bag" id="recid_bagian"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required" onchange="emp_free_ot()">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                              echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kategori Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_kategori" id="recid_kategori"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required" onchange="kategoris()">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($kategori->result() as $k) {
                              echo "<option value='$k->recid_kategori'>$k->kategori</option>";
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                    <div class="item form-group" id="lainnya" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kategori Lainnya <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="kat_lain" id="kat_lain" class="form-control">
                        </div>
                      </div>
                        <div class="item form-group" id="jam_masuk">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Mulai <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myTime1'>
                              <input type='text' class="form-control" name="jam_mulai"  id="jam_mulai"/>
                              <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="item form-group" id="jam_keluar">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Selesai <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myTime2'>
                              <input type='text' class="form-control" name="jam_selesai" id="jam_selesai" />
                              <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                              </span>
                            </div>
                          </div>
                        </div>
                        
                        <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Klasifikasi <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="klasifikasi" id="klasifikasi"  class="selectpicker form-control  col-md-12 col-xs-12" required="required">
                            <option value="Tidak Terencana">Tidak Terencana</option>
                            <option value="Terencana">Terencana</option>
                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Tipe <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="tipe" id="tipe"  class="selectpicker form-control  col-md-12 col-xs-12"  required="required">
                            <option value="Produksi">Produksi</option>
                            <option value="Non Produksi">Non Produksi</option>
                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="recid_karyawan[]" class="form-control col-md-12 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required" onchange="hitung_orang()">
                              <option value="">-- Pilih -- </option>
                              <?php
                              foreach ($karyawan as $option) {
                              echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik - $option->indeks_hr)</option>";
                            }
                            ?> 
                          <!-- </select> -->
                        </select>
                        </div>
                          </div>

                        <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Jumlah Karyawan <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="jum_org" id="jumlah_org" readonly class="form-control col-md-7 col-xs-12" value="0">
                        </div>
                      </div>

                      <div class="item form-group" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Total Jam Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="total_jam" id="total_jam" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Total Lembur (satuan jam)">
                          <input type="text" name="jml_orang" id="jml_orang" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Jumlah Orang">
                          <input type="text" name="recid_mbl" id="mbl" class="form-control col-md-7 col-xs-12" placeholder="mbl">
                        </div>
                      </div>
                        <div class="item form-group" style="display: none" id="alasan">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Over Budget <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="alasan_over" id="alasan_over" class="form-control" placeholder="Alasan Over Budget"></textarea>
                        </div>
                      </div>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Keterangan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan lembur"></textarea>
                        </div>
                      </div>
                      <br>
                      <div id="repeater">
                        <div class="repeater-heading" align="center">
                          <button type="button" class="btn btn-primary repeater-add-btn">Tambah Uraian Pekerjaan</button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="items" data-group="dinamis">
                          <div class="item-content">
                            <div class="form-group">
                              <div class="row">
                               <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Uraian Pekerjaan <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <textarea name="pekerjaan[]" id="pekerjaan[]" class="form-control pekerjaan" placeholder="kegiatan lembur"></textarea>
                                </div>
                              </div>
                               <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Target <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <textarea name="target[]" id="target[]" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Target Lembur" rows="1" cols="1"></textarea>
                                </div>
                              </div>
                              <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Satuan <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <textarea name="satuan[]" id="satuan[]" class="form-control" placeholder="Satuan Target (contoh : PCS, Persen, dll)" rows="1" cols="1"></textarea>
                                </div>
                              </div>
                              <div class="col-md-3" style="margin-top:24px;" align="center">
                                <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">Remove</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Karyawan/plembur_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                         <button id="send" type="button" class="btn btn-success" onclick="save_lembur()">Submit</button>
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

  $(document).ready(function(){
    $("#repeater").createRepeater();
    
  });

  function hitung_orang()
  {
    var jum_org = ($("#callbacks").val()).length;
    document.getElementById('jumlah_org').value = jum_org;
    console.log("jumlah orang : "+ jum_org);
  }

  function emp_free_ot()
  {
  			var tgl_lembur = document.getElementById('tgl_lembur').value;
  			var recid_bag = document.getElementById('recid_bagian').value;
  			$.ajax({
		        type: "POST", // Method pengiriman data bisa dengan GET atau POST
		        url: "<?php echo base_url();?>Lembur/get_emp_for_ot", // Isi dengan url/path file php yang dituju
		        data: {tgl_lembur : tgl_lembur, recid_bag : recid_bag}, // data yang akan dikirim ke file yang dituju
		        dataType: "json",
		        beforeSend: function(e) {
		        	if(e && e.overrideMimeType) {
		        		e.overrideMimeType("application/json;charset=UTF-8");
		        	}
		        },
		        success: function(response, data){ // Ketika proses pengiriman berhasil
		          // set isi dari combobox kota
		          // lalu munculkan kembali combobox kotanya
              $("#callbacks").multiSelect('destroy');
		          $("#callbacks").html(response.list_kota).show();
              // var element = document.getElementById("callbacks");
              // element.classList.add("searchable");
              // $("#callbacks").multiSelect();
               $('.searchable').multiSelect({
              selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='try \"nama\"'>",
              selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='try \"nama\"'>",
              afterInit: function(ms){
                var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                  if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                  }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                  if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                  }
                });
              },
              afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
              },
              afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
              }
            });
		      },
		        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
		          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
		      }
		  });
  }

  function kategoris()
  {
    kat = $("#recid_kategori").val();
    if(kat == '11')
    {
      $("#lainnya").show();
    }else{
      $("#lainnya").hide();
    }
  }

  function save_lembur()
  {
    tgl_lembur = $("#tgl_lembur").val();
    recid_bagian = $("#recid_bagian").val();
    jam_mulai = $("#jam_mulai").val();
    jam_selesai = $("#jam_selesai").val();
    if((jam_mulai == "06 : 00 " && jam_selesai == "14 : 00 ") || (jam_mulai == "14 : 00 " && jam_selesai == "22 : 00 ") || (jam_mulai == "22 : 00 " && jam_selesai == "06 : 00 "))
    {
       $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url();?>index.php/Lembur/masterbudget_shift", // Isi dengan url/path file php yang dituju
      data: {recid_bag :recid_bagian,tgl :tgl_lembur, jam_mulai : jam_mulai, jam_selesai : jam_selesai},
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
          }
       },
      success: function(data, response){ // Ketika proses pengiriman berhasil
        //data[0] => cek cut off udah dibuat apa belum
        //data[1] => cek master budget bagian itu udah dibuat apa belum
        //data[2] => cek jumlah budget per kuartal
        //data[3] => recid mbl
        //data[4] => kalkulasi jam lembur
        
        console.log("Cut off "+data[0]);
        console.log("MBL "+data[1]);
        if(data[0] == "Ready")
        {
          if(data[1] == "Ada")
          {
              budget_kuartal = data[2];
              
              kal_jam = data[4];

              // jumlah orang
              recid_karyawan = $("#callbacks").val();
              jml_orang = recid_karyawan.length
              console.log(jml_orang);

              // jml jam lembur
              jml_jam_lembur = kal_jam * jml_orang;
              console.log("jml jam lemburnya : "+jml_jam_lembur);

              /* hitung budget cukup atau tidak */
              sisa_budget = budget_kuartal - jml_jam_lembur;
              console.log("sisa budget = "+sisa_budget);
               recid_mbl = parseInt(data[3]);
              $('#mbl').val(recid_mbl);
              $('#total_jam').val(jml_jam_lembur);
              $('#jml_orang').val(jml_orang);

              if(sisa_budget >= 0)
              {
                console.log("Budget Cukup");
                $( "#form_lembur" ).submit();
              }else{
                // alert("Master Budget Over, Harap Isi Alasan!");
                $("#alasan").show();
                if($("#alasan_over").val() != '')  
                {
                  $( "#form_lembur" ).submit();
                }else{
                  alert("Master Budget Over, Harap Isi Alasan!");
                }
              }
          }else{
            alert("Harap isi master budget! (Hubungi bagian HC / Finance)");
          }
        }else{
          alert("Cut Off Lembur Belum Dibuat");
        }
        console.log(data);
       },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
    }else{
      $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url();?>index.php/Lembur/masterbudget", // Isi dengan url/path file php yang dituju
      data: {recid_bag :recid_bagian,tgl :tgl_lembur, jam_mulai : jam_mulai, jam_selesai : jam_selesai},
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
          }
       },
      success: function(data, response){ // Ketika proses pengiriman berhasil
        //data[0] => cek cut off udah dibuat apa belum
        //data[1] => cek master budget bagian itu udah dibuat apa belum
        //data[2] => cek jumlah budget per kuartal
        //data[3] => recid mbl
        //data[4] => kalkulasi jam lembur
        
        console.log("Cut off "+data[0]);
        console.log("MBL "+data[1]);
        if(data[0] == "Ready")
        {
          if(data[1] == "Ada")
          {
              budget_kuartal = data[2];
              
              kal_jam = data[4];

              // jumlah orang
              recid_karyawan = $("#callbacks").val();
              jml_orang = recid_karyawan.length
              console.log(jml_orang);

              // jml jam lembur
              jml_jam_lembur = kal_jam * jml_orang;
              console.log("jml jam lemburnya : "+jml_jam_lembur);

              /* hitung budget cukup atau tidak */
              sisa_budget = budget_kuartal - jml_jam_lembur;
              console.log("sisa budget = "+sisa_budget);
               recid_mbl = parseInt(data[3]);
              $('#mbl').val(recid_mbl);
              $('#total_jam').val(jml_jam_lembur);
              $('#jml_orang').val(jml_orang);

              if(sisa_budget >= 0)
              {
                console.log("Budget Cukup");
                $( "#form_lembur" ).submit();
              }else{
                // alert("Master Budget Over, Harap Isi Alasan!");
                $("#alasan").show();
                if($("#alasan_over").val() != '')  
                {
                  $( "#form_lembur" ).submit();
                }else{
                  alert("Master Budget Over, Harap Isi Alasan!");
                }
              }
          }else{
            alert("Harap isi master budget! (Hubungi bagian HC / Finance)");
          }
        }else{
          alert("Cut Off Lembur Belum Dibuat");
        }
        console.log(data);
       },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
    }
  }


$("#total_lembur").change(function(){
 var tgl = document.getElementById("tgl_lembur").value;
 var tahun = tgl.substring(0, 4);
 var date = new Date(tgl);
 var bulan = date.getMonthName();
 var recid_bag = document.getElementById("recid_bagian").value;
 var jam_lembur = document.getElementById("total_lembur").value;
 jam_lembur = parseInt(jam_lembur);
 console.log(recid_bag);
 $.ajax({ //----------------------------------------- cek master budget ada atau engga -----------------------------------------------
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url();?>Karyawan/masterbudget", // Isi dengan url/path file php yang dituju
        data: {recid_bag :recid_bag,tgl :tgl}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data){ // Ketika proses pengiriman berhasil
          if(response == '0'){
            alert("Harap isi master budget! (Hubungi bagian HC / Finance)");
            document.getElementById("send").disabled = true;
          }else{
            $.ajax({  //---------------------------------------- get recid mbl --------------------------------------------------------
              type: "POST", // 
              url: "<?php echo base_url();?>Karyawan/cek_mbl", 
              data: {recid_bag :recid_bag, tgl :tgl}, 
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
                    kurang = total + jam_lembur;
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

  function cek_pekerjaan()
  {

  }
</script>
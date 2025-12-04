<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Approval Pengajuan Lembur</h3>
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
                    <h2><a href="<?php echo base_url()?>Lembur/stkl_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Pengajuan Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                        foreach ($stkl->result() as $s) {
                            # code...
                        }
                    ?>
                     <form id="form_lembur" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/approval_lembur" data-toggle="validator">
                      <?php echo $this->session->flashdata('message'); ?>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type='hidden' class="form-control" name="recid_stkl"  id="recid_stkl" value="<?php echo $s->recid_stkl ?>"/>
                          <div class='input-group date' id='myDatepicker2'>
                            <input type='text' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" required="required" value="<?php echo $s->tgl_lembur ?>" />
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div>
                       </div>
                     </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_bag" id="recid_bagian"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                                if($option->recid_bag == $s->recid_bag)
                                {
                                    echo "<option value='$option->recid_bag' selected>$option->indeks_hr ($option->nama_bag)</option>";
                                }else{
                                    echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
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
                          <select name="recid_kategori" id="recid_kategori"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($kategori->result() as $k) {
                                if($k->recid_kategori == $s->recid_kategori)
                                {
                                    echo "<option value='$k->recid_kategori' selected>$k->kategori</option>";
                                }else{
                                    echo "<option value='$k->recid_kategori'>$k->kategori</option>";
                                }
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                        <div class="item form-group" id="jam_masuk">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Mulai <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myTime1'>
                              <input type='text' class="form-control" name="jam_mulai"  id="jam_mulai" value="<?php echo $s->jam_mulai ?>"/>
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
                              <input type='text' class="form-control" name="jam_selesai" id="jam_selesai" value="<?php echo $s->jam_selesai ?>"/>
                              <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                              </span>
                            </div>
                          </div>
                        </div>
                        
                        <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Klasifikasi <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php 
                                $klas = ["Tidak Terencana", "Terencana"];
                            ?>
                          <select name="klasifikasi" id="klasifikasi"  class="selectpicker form-control  col-md-12 col-xs-12" required="required">
                            <?php 
                                for($kl=0; $kl<count($klas); $kl++)
                                {
                                    if($klas[$kl] == $s->klasifikasi)
                                    {?>
                                         <option value="<?php echo $klas[$kl] ?>" selected><?php echo $klas[$kl] ?></option>
                                    <?php }else{?>
                                         <option value="<?php echo $klas[$kl] ?>"><?php echo $klas[$kl] ?></option>
                                    <?php }
                                }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Tipe <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php 
                                $tp = ["Produksi", "Non Produksi"];
                            ?>
                          <select name="tipe" id="tipe"  class="selectpicker form-control  col-md-12 col-xs-12"  required="required">
                           <?php 
                                for($t=0; $t<count($tp); $t++)
                                {
                                    if($tp[$t] == $s->tipe)
                                    {?>
                                         <option value="<?php echo $tp[$t] ?>" selected><?php echo $tp[$t] ?></option>
                                    <?php }else{?>
                                         <option value="<?php echo $tp[$t] ?>"><?php echo $tp[$t] ?></option>
                                    <?php }
                                }
                            ?>
                          </select>
                        </div>
                      </div>
                            
                    
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Karyawan Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Tambah Karyawan</button><br><br>
                        <span style="color:red">* Data Pada Tabel Akan Tersimpan Otomatis</span>
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th>#</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Aksi</th>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            foreach ($anggota->result() as $key) {?>
                                <tr>
                                <td><?php echo $no = $no+1 ?></td>
                                <td><?php echo $key->nik ?></td>
                                <td><?php echo $key->nama_karyawan ?></td>
                                <td><?php echo $key->indeks_hr ?></td>
                                <td><center><a href="<?php echo base_url()?>index.php/Lembur/del_karyawan_lembur/<?php echo $key->recid_stkl ?>/<?php echo $key->recid_detstkl ?>"><span class="fa fa-trash" style="color:red"></span></button></a></center></td>
                                </tr>
                            <?php  }
                            ?>

                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>

                      <div class="item form-group" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Total Jam Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="total_jam" id="total_jam"  value="<?php echo $s->total_jam ?>" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Total Lembur (satuan jam)">
                          <input type="text" name="jml_orang" id="jml_orang"  value="<?php echo $s->jml_orang ?>" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Jumlah Orang">
                          <input type="text" name="recid_mbl" id="mbl" value="<?php echo $s->recid_mbl ?>" class="form-control col-md-7 col-xs-12" readonly placeholder="mbl">
                        </div>
                      </div>
                        <div class="item form-group" id="alasan">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Over Budget <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="alasan_over" id="alasan_over" class="form-control" placeholder="Alasan Over Budget"><?php echo $s->alasan_over ?></textarea>
                        </div>
                      </div>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Keterangan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan lembur"><?php echo $s->keterangan ?></textarea>
                        </div>
                      </div>
                      <br>
                      
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Uraian Pekerjaan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal2">Tambah Uraian Pekerjaan</button><br><br>
                        <span style="color:red">* Data Pada Tabel Akan Tersimpan Otomatis</span>
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered" width="100%">
                            <thead>
                            <th>#</th>
                            <th>Pekerjaan</th>
                            <th>Target</th>
                            <th>Aksi</th>
                            </thead>
                            <tbody>
                            <?php
                            $noo = 0;
                            foreach ($pekerjaan->result() as $p) {?>
                                <tr>
                                <td><?php echo $noo = $noo+1 ?></td>
                                <td><?php echo $p->pekerjaan ?></td>
                                <td><?php echo $p->target_kerja." ".$p->satuan ?></td>
                                <td><center><a href="<?php echo base_url()?>index.php/Lembur/del_pekerjaan/<?php echo $p->recid_plembur ?>/<?php echo $p->recid_detlembur ?>/acc"><span class="fa fa-trash" style="color:red"></span></button></a></center></td>
                                </tr>
                            <?php  }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Approval <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php 
                            if($s->approval != 'Tidak Acc Manager' and $s->approval != 'Tidak Acc HC'){?>
                               <input type="radio" name="acc" value="1" checked> Approve<br>
                              <input type="radio" name="acc" value="0"> Tolak<br>
                            <?php }else{?>
                              <input type="radio" name="acc" value="1"> Approve<br>
                              <input type="radio" name="acc" value="0" checked> Tolak<br>
                            <?php }
                          ?>
                        </div>
                      </div>
                      <br>
      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <button id="send" type="button" class="btn btn-success" onclick="update_lembur()">Submit</button>
                         <a href='<?php echo base_url()?>Lembur/stkl_view'><input type="button" class="btn btn-danger" value="Cancel"></button></a>
                         
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

        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Karyawan Lembur</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/lembur_addkar" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nama Karyawan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="recid_stkl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $s->recid_stkl ?>">
            <input type="hidden" name="dari" required="required" class="form-control col-md-7 col-xs-12" value="acc">
            <select name="recid_karyawan[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required">
             <?php
             foreach ($karyawan as $option) {
               echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik - $option->indeks_hr)</option>";
             }
             ?>
           </select>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea id="note" name="keterangan" class="form-control col-md-7 col-xs-12"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>
</div>
</div>

        <!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Uraian Pekerjaan</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/lembur_addkerja" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Uraian Pekerjaan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="recid_plembur" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $s->recid_stkl ?>">
        
           <textarea name="pekerjaan" id="pekerjaan" class="form-control pekerjaan" placeholder="kegiatan lembur"></textarea>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Target <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="target" id="target" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Target Lembur" rows="1" cols="1"></textarea>
        </div>
        </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Satuan <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="satuan" id="satuans" class="form-control"  placeholder="Satuan Target (contoh : PCS, Persen, dll)" rows="1" cols="1"></textarea>
        </div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>
</div>
</div>

<script>
  Date.prototype.getMonthName = function() {
    var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];
    return monthNames[this.getMonth()];
  }

   $('#myModal').on('show.bs.modal', function (event) {
      emp_free_ot()        
    });

    function emp_free_ot()
  {
  			var tgl_lembur = document.getElementById('tgl_lembur').value;
  			$.ajax({
		        type: "POST", // Method pengiriman data bisa dengan GET atau POST
		        url: "<?php echo base_url();?>Lembur/get_emp_for_ot", // Isi dengan url/path file php yang dituju
		        data: {tgl_lembur : tgl_lembur}, // data yang akan dikirim ke file yang dituju
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

            $('#callbacks').multiSelect({
              afterSelect: function(values){
                  // alert("Select value: "+values);
                },
                afterDeselect: function(values){
                  // alert("Deselect value: "+values);
                }
              });
		      },
		        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
		          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
		      }
		  });
  }

 function update_lembur()
  {
    tgl_lembur = $("#tgl_lembur").val();
    recid_bagian = $("#recid_bagian").val();
    var recid_stkl = document.getElementById("recid_stkl").value;
    jam_mulai = $("#jam_mulai").val();
    jam_selesai = $("#jam_selesai").val();
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url();?>index.php/Lembur/masterbudget2", // Isi dengan url/path file php yang dituju
      data: {recid_bag :recid_bagian,tgl :tgl_lembur, recid_stkl : recid_stkl, jam_mulai : jam_mulai, jam_selesai : jam_selesai},
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
        //data[4] => jumlah orang
        //data[5] => kalkulasi jam lembur
        console.log("Cut off "+data[0]);
        console.log("MBL "+data[1]);
        if(data[0] == "Ready")
        {
          if(data[1] == "Ada")
          {
              budget_kuartal = data[2];
             kal_jam = data[5];

              // jumlah orang
              jml_orang = data[4];
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
</script>
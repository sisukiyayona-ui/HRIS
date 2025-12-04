<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Absensi Karyawan</h3>
      </div>
    </div>
            <div class="clearfix"></div>

             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><a href="<?php echo base_url()?>Absen/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Absen Karyawan</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>AbsenBarcode/absen_pabsen" novalidate>

             <!-- <span class="section">Personal Info</span>-->
                 <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
              <select name="nik" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required" onchange="detail_karyawan()">
                <option value="">-- Pilih --</option>
                <?php
                  foreach ($emp as $emp) { ?>
                    <option value="<?php echo $emp->recid_karyawan ?>"><?php echo $emp->nama_karyawan ?> (<?php echo $emp->nik ?>)</option>
                  <?php }
                ?>
              </select>
           </div>
         </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='niks' class="form-control" readonly="readonly">
          <input type="hidden" id='recid_karyawan' name="recid_karyawan" class="form-control">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='bagian' class="form-control" readonly="readonly">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='jabatan' class="form-control" readonly="readonly">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Sisa Cuti <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='sisa_cuti' class="form-control" readonly="readonly">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Tahun Cuti Dipakai<span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='cuti_thn_ke' name="cuti_thn_ke" class="form-control" readonly="readonly">
          </div>
        </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_mulai" required="required" />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Selesai<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <div class='input-group date' id='myDatepicker2'>
              <input type='text' class="form-control" name="tgl_selesai" required="required" />
              <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
             </span>
           </div>
         </div>
       </div>
         <div class="item form-group">
          <label for="agama" class="control-label col-md-3">Jenis Absen<span class="required">*</span></label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="jenis_absen" id="jenis_absen" class="form-control col-md-7 col-xs-12" required="required" onchange="detail()">
             <?php  foreach ($jenis->result() as $j) {?>
              <option value="<?php echo $j->recid_jenisabsen?>"><?php echo $j->jenis?> ( <?php echo $j->keterangan?> )</option>
             <?php } ?>
            </select>
          </div>
        </div>
         <div class="item form-group" id="diagnosa"  style="display: none">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Diagnosa
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="ediagnosa" name="diagnosa" class="form-control col-md-7 col-xs-12"></textarea>
          </div>
        </div>
         <div class="item form-group" id="kategori"  style="display: none">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Kategori
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <?php 
              $kat = ["Ringan", "Berat", "Berkelanjutan"];
            ?>
            <select name="kategori" id="ekategori" class="form-control">
              <?php 
                for($k=0;$k<count($kat);$k++)
                {?>
                  <option value="<?php echo $kat[$k]?>"><?php echo $kat[$k]?></option>
                <?php }
              ?>
            </select>
          </div>
        </div>
         <div class="item form-group" id="detail_sakit" style="display: none">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Detail Sakit
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="edetail_sakit" name="ket_sakit" class="form-control col-md-7 col-xs-12"></textarea>
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
          </div>
        </div>
       
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href="<?php  echo base_url()?>Karyawan/karyawan_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
                <button id="send" type="submit" class="btn btn-success">Submit</button>
              </div>
            </div>
            </form>
            <!--/ Content Form -->
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->


<script type="text/javascript">
  function detail_karyawan()
  {
    var nik = document.getElementById('nik').value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/karyawan_detail", // Isi dengan url/path file php yang dituju
            data: {nik : nik}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             document.getElementById('niks').value = response[0][4];
             document.getElementById('bagian').value = response[0][1];
             document.getElementById('jabatan').value = response[0][2];
             document.getElementById('recid_karyawan').value = response[0][3];
             document.getElementById('sisa_cuti').value = response[1];
             document.getElementById('cuti_thn_ke').value = response[2];
           },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }

  function detail()
  {
    jenis = document.getElementById('jenis_absen').value;
    if(jenis == 2)
    {
      $("#diagnosa").show();
      $("#kategori").show();
      $("#detail_sakit").show();
    }else
    {
      $("#diagnosa").hide();
      $("#kategori").hide();
      $("#detail_sakit").hide();
    }
  }
</script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Adjust Absen Karyawan</h3>
      </div>
    </div>
            <div class="clearfix"></div>
             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
<?php 
    foreach ($kehadiran->result() as $data) {
        
    }
?>
        <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>AbsenBarcode/proses_adjust" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Nama Karyawan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id='nama_karyawan' class="form-control" value="<?php echo $data->nama_karyawan ?>" readonly="readonly">
           <?php if($tipe == 2){?>
                <input type="hidden" name="recid_absen" class="form-control" value="<?php echo $data->recid_absen ?>">
            <?php }else{?>
                <input type="hidden" name="recid_absen" class="form-control" value="">
            <?php } ?>
          <input type="hidden" id='recid_karyawan' name="recid_karyawan" value="<?php echo $data->recid_karyawan ?>" class="form-control">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control" value="<?php echo $data->nik ?>" readonly="readonly">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text"  class="form-control" value="<?php echo $data->indeks_hr ?>" readonly="readonly">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control" value="<?php echo $data->indeks_jabatan ?>" readonly="readonly">
          </div>
        </div>
        <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl" value="<?php echo $tgl ?>" required="required" readonly/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Pulang<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_pulang" value="<?php if($tipe == '2'){echo $data->tgl_pulang;}else{echo $tgl;}  ?>" required="required"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group" id="jam_masuk">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Masuk <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime1'>
                <?php if($tipe == 2){?>
                    <input type='text' class="form-control" name="jam_masuk" value="<?php echo $data->jam_masuk ?>" />
                <?php }else{?>
                    <input type='text' class="form-control" name="jam_masuk" />
                <?php } ?>
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group" id="jam_keluar">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Keluar <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime2'>
                <?php if($tipe == 2){?>
                    <input type='text' class="form-control" name="jam_keluar" value="<?php echo $data->jam_keluar ?>" />
                <?php }else{?>
                    <input type='text' class="form-control" name="jam_keluar" />
                <?php } ?>
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>  
          <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jenis Kehadiran
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="jenis_absen" class="form-control">
              <?php 
                foreach ($jenis_kehadiran->result() as $j) {
                    if($j->recid_jenisabsen == $data->status){?>
                         <option value="<?php echo $j->recid_jenisabsen ?>" selected><?php echo $j->jenis."( ".$j->keterangan." )" ?></option>
                    <?php } else{ ?>
                         <option value="<?php echo $j->recid_jenisabsen ?>"><?php echo $j->jenis."( ".$j->keterangan." )" ?></option>
                   <?php  }?>
                   
                <?php }
              ?>
            </select>
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="keterangan" class="form-control">
              <option value="Lupa Absen Masuk">Lupa Absen Masuk</option>
              <option value="Lupa Absen Pulang">Lupa Absen Pulang</option>
              <option value="Tidak Absen">Tidak Absen</option>
              <option value="Tidak Bawa ID Card">Tidak Bawa ID Card</option>
              <option value="Dinas">Dinas</option>
              <option value="ID Card Dalam Perbaikan">ID Card Dalam Perbaikan</option>
              <option value="Adjust Kehadiran">Adjust Kehadiran</option>
            </select>
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
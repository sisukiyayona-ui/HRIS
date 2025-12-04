<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Tambah Cuti Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Tambah Cuti</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Absen/cuti_pinsert" novalidate>
             <!-- <span class="section">Personal Info</span>-->
                 <div class="item form-group">
                  <label for="agama" class="control-label col-md-3">Nama Karyawan</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="recid_karyawan" id="recid_karyawan" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required" onchange="detail_karyawan()">
                       <option value="">-- Pilih --</option>
                      <?php 
                        foreach ($karyawan as $k) { ?>
                            <option value="<?php echo $k->recid_karyawan ?>"><?php echo $k->nama_karyawan ?> (<?php echo $k->nik ?>)</option>
                        <?php } ?>
                    </select>
                  </div>
                </div>
                 <div class="item form-group">
                  <label for="agama" class="control-label col-md-3">NIK</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" id="nik" readonly>
                  </div>
                </div>
                <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Bagian <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" id="bagian" readonly>
               </div>
             </div>
              <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jabatan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" id="jabatan" readonly>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">Cuti Tahun Ke <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="cuti_thn_ke" class="form-control col-md-7 col-xs-12" >
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_mulai" required="required"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Expired<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker4'>
                <input type='text' class="form-control" name="expired" required="required" />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
          <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">Jumlah Cuti <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="jml_cuti" class="form-control col-md-7 col-xs-12">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">Hangus<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" name="hangus" value="0" checked> Belum hangus
                      <input type="radio" name="hangus" value="1"> Hangus
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
    var recid_karyawan = document.getElementById('recid_karyawan').value;
    $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/karyawan_detail", // Isi dengan url/path file php yang dituju
            data: {nik : recid_karyawan}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             document.getElementById('nik').value = response[0][4];
             document.getElementById('bagian').value = response[0][1];
             document.getElementById('jabatan').value = response[0][2];
             document.getElementById('recid_karyawan').value = response[0][3];
           },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
  }
</script>
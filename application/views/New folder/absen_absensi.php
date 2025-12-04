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
                    <h2><a href="<?php echo base_url()?>Karyawan/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Absen Karyawan</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/absen_pabsen" novalidate>

             <!-- <span class="section">Personal Info</span>-->
                 <div class="item form-group">
                  <label for="agama" class="control-label col-md-3">Bagian<span class="required">*</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="recid_bag" id="divisi" class="form-control col-md-7 col-xs-12" required="required">
                        <option value="">Pilih</option>
                      <?php
                      foreach ($bagian as $option) {
                        echo "<option value='$option->recid_bag'>$option->nama_bag</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                  <select  class="form-control col-md-7 col-xs-12" id="karyawan" onchange="cek_nik()">
                  <option value="">Pilih</option>
                 </select>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="nik" readonly class="form-control col-md-7 col-xs-12" id="nik">
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
            <select name="jenis_absen" id="divisi" class="form-control col-md-7 col-xs-12" required="required">
              <option value="C">Cuti</option>
              <option value="P1">Ijin</option>
              <option value="H1">Haid</option>
              <option value="H2">Hamil</option>
              <option value="SID">Surat Dokter</option>
              <option value="P4">Menikah, Melahirkan, Meninggal</option>
              <option value="M">Mangkir</option>
            </select>
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
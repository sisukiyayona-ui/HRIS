<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Adjustment Cuti Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Adjustment Cuti</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Absen/cuti_update" novalidate>

              <?php 
                foreach ($cuti->result() as $c) {
                  // code...
                }
              ?>
             <!-- <span class="section">Personal Info</span>-->
                 <div class="item form-group">
                  <label for="agama" class="control-label col-md-3">NIK</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $c->nik?>" readonly>
                    <input type="hidden" name="recid_cuti" class="form-control" value="<?php echo $c->recid_cuti?>" readonly>
                  </div>
                </div>
                <div class="item form-group">
                  <label for="agama" class="control-label col-md-3">Nama Karyawan</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $c->nama_karyawan?>" readonly>
                  </div>
                </div>
                <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Bagian <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $c->indeks_hr?>" readonly>
               </div>
             </div>
              <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jabatan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?php echo $c->indeks_jabatan?>" readonly>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">Cuti Tahun Ke <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="cuti_thn_ke" class="form-control col-md-7 col-xs-12" value="<?php echo $c->cuti_thn_ke ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_mulai" required="required" value="<?php echo $c->tgl_mulai?>"/>
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
                <input type='text' class="form-control" name="expired" required="required" value="<?php echo $c->expired?>"/>
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
                <input type="text" name="jml_cuti" class="form-control col-md-7 col-xs-12" value="<?php echo $c->jml_cuti ?>">
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">Hangus<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php 
                    if($c->hangus == '0')
                    {?>
                      <input type="radio" name="hangus" value="0" checked> Belum hangus
                      <input type="radio" name="hangus" value="1"> Hangus
                    <?php }else{?>
                      <input type="radio" name="hangus" value="0"> Belum hangus
                      <input type="radio" name="hangus" value="1" checked> Hangus
                    <?php } ?>
                
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
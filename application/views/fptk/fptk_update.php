
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Formulir Permintaan Tenaga Kerja (FPTK)</h3>
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
                    <h2><a href="<?php echo base_url()?>index.php/Recruitment/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update FPTK</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <h4><span class="label label-success">Data Pemohon</span></h4>
                   <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Recruitment/fptk_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                     <?php echo $this->session->flashdata('message');?>
                     <?php foreach ($fptk as $data) { } ?>
                     <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Pengajuan <span class="required" style="color: red">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type='hidden' class="form-control" name="recid_fptk" value="<?php echo $data->recid_fptk ?>"/>
                       <div class='input-group date' id='myDatepicker3'>
                        <input type='text' class="form-control" name="tgl_pengajuan" required="required" placeholder="Tanggal Pengajuan" value="<?php echo $data->tgl_pengajuan ?>" />
                        <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                       </span>
                     </div>
                   </div>
                 </div>
                 <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Pemohon <span class="required" style="color: red">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                     <?php
                     echo "<option value=''>-- Pilih --</option>";
                     foreach ($karyawan as $option) {
                      if($data->recid_karyawan == $option->recid_karyawan){
                       echo "<option data-subtext='$option->nik' value='$option->recid_karyawan' selected='selected'>$option->nama_karyawan</option>";
                      }else{
                        "<option data-subtext='$option->nik' value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                      }
                     }
                     ?>
                   </select>
                 </div></div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Alasan Pengajuan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea class="form-control col-md-7 col-xs-12" name='alasan'  placeholder="Alasan"><?php echo $data->alasan?></textarea>
                </div>
              </div>
              <hr>
              <h4><span class="label label-success">Kebutuhan Kandidat</span></h4>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Bagian <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_bag" id="bagian" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                   <?php
                   echo "<option value=''>-- Pilih --</option>";
                   foreach ($bagian as $option) {
                    if($data->recid_bag == $option->recid_bag){
                     echo "<option value='$option->recid_bag' selected='selected'>$option->indeks_hr</option>";
                    }else{
                      echo "<option value='$option->recid_bag'>$option->indeks_hr</option>";
                    }
                   }
                   ?>
                 </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jabatan  <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <select name="recid_jbtn" id="bagian" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                   <?php
                   echo "<option value=''>-- Pilih --</option>";
                   foreach ($jabatan as $option) {
                    if($data->recid_jbtn == $option->recid_jbtn){
                     echo "<option value='$option->recid_jbtn' selected='selected'>$option->indeks_jabatan</option>";
                    }else{
                      echo "<option value='$option->recid_jbtn'>$option->indeks_jabatan</option>";
                    }
                   }
                   ?>
                 </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Efektif <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <div class='input-group date' id='myDatepicker2'>
                  <input type='text' class="form-control" name="tgl_efektif" required="required" placeholder="Tanggal Efektif" value="<?php echo $data->tgl_efektif ?>"/>
                  <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                 </span>
               </div>
             </div>
           </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Approve <span class="required" style="color: red">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <div class='input-group date' id='myDatepicker4'>
              <input type='text' class="form-control" name="tgl_approve" required="required" placeholder="Tanggal Approve" value="<?php echo $data->tgl_approve ?>"/>
              <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
             </span>
           </div>
         </div>
       </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"><?php echo $data->ket ?></textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href='<?php echo base_url()?>index.php/Recruitment/fptk_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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
        <!-- /page content
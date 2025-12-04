
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Kajian FPTK </h3>
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
            <h2><a href="<?php echo base_url()?>index.php/Recruitment/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Kajian FPTK</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <h4><span class="label label-success">Data FPTK</span></h4>
            <?php foreach ($fptk as $fptk) {} ?>
            <?php foreach ($kfptk as $data) { } ?>
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Recruitment/kfptk_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
             <?php echo $this->session->flashdata('message');?>
             <div class="item form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="hidden" name="recid_fptk" value="<?php echo $fptk->recid_fptk ?>">
                <input type="hidden" name="recid_kfptk" value="<?php echo $data->recid_kfptk ?>">
                <table class="table table-striped table-bordered">
                  <tr><td colspan="2"><b>Pemohon <?php if($data->recruitment == '1'){echo "<span style='color:red; font-size:8pt'>(Recruitment Sudah Dibuat)";} ?></b></td></tr>
                    <tr><td>Tanggal Pengajuan </td><td><?php echo $newDate = date("d M Y", strtotime($fptk->tgl_pengajuan)); ?></td></tr>
                    <tr><td>Pemohon</td><td><?php echo $fptk->nama_karyawan." ( ".$fptk->nama_bags." / ".$fptk->nama_jbtns." )" ?></td></tr>
                    <tr><td>Alasan </td><td><?php echo $fptk->alasan ?></td></tr>
                    <tr><td colspan="2"><b>Kandidat</b></td></tr>
                    <tr><td>Posisi </td><td><?php echo $fptk->nama_bag." / ".$fptk->nama_jbtn ?></td></tr>
                    <tr><td>Tanggal Efektif </td><td><?php echo $newDate = date("d M Y", strtotime($fptk->tgl_efektif)); ?></td></tr>
                    <tr><td>Tanggal Approve </td><td><?php echo $newDate = date("d M Y", strtotime($fptk->tgl_approve)); ?></td></tr>
                  </table>
                </div>
              </div>
              <hr>

              <h4><span class="label label-success">Kajian FPTK</span></h4>
              <?php if($data->recruitment == '1'){ ?>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">User <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required" disabled="disabled">
                   <?php
                   echo "<option value=''>-- Pilih --</option>";
                   foreach ($karyawan as $option) {
                    if($data->recid_karyawan == $option->recid_karyawan){
                     echo "<option data-subtext='$option->nik' value='$option->recid_karyawan' selected='selected'>$option->nama_karyawan</option>";
                   }else{
                     echo "<option data-subtext='$option->nik' value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                   }
                 }
                 ?>
               </select>
             </div>
                   </div>
                   <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Status Acc  <span class="required" style="color: red">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <?php if($data->status_acc == 'Disetujui'){ ?>
                       <input type="radio" class="flat" name="status_acc" value="Disetujui" checked="checked" disabled="disabled"> Disetujui
                       <input type="radio" class="flat" name="status_acc" value="Ditolak" disabled="disabled"> Ditolak
                     <?php } else{ ?>
                       <input type="radio" class="flat" name="status_acc" value="Disetujui"disabled="disabled" > Disetujui
                       <input type="radio" class="flat" name="status_acc" value="Ditolak" checked="checked" disabled="disabled"> Ditolak
                     <?php }?>
                   </div>
                   </div>
                   <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Acc HC <span class="required" style="color: red">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                     <div class='input-group date' id='myDatepicker2'>
                      <input type='text' class="form-control" name="tgl_hc" required="required" value="<?php echo $data->tgl_hc ?>" disabled="disabled" />
                      <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                   </div>
                 </div>
               </div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Acc BOD <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <div class='input-group date' id='myDatepicker4'>
                  <input type='text' class="form-control" name="tgl_bod" required="required" value="<?php echo $data->tgl_bod ?>" disabled="disabled"/>
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
              <textarea class="form-control col-md-7 col-xs-12" name='note' disabled="disabled"><?php echo $data->ket ?></textarea>
            </div>
          </div>
          <div class="ln_solid"></div>
         </div>

          <!-- ENABLE FORM -->
              <?php } else{ ?>
               <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">User <span class="required" style="color: red">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                   <select name="recid_karyawan" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required">
                     <?php
                     echo "<option value=''>-- Pilih --</option>";
                     foreach ($karyawan as $option) {
                      if($data->recid_karyawan == $option->recid_karyawan){
                       echo "<option data-subtext='$option->nik' value='$option->recid_karyawan' selected='selected'>$option->nama_karyawan</option>";
                     }else{
                       echo "<option data-subtext='$option->nik' value='$option->recid_karyawan'>$option->nama_karyawan</option>";
                     }
                   }
                   ?>
                 </select>
               </div>
             </div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Status Acc  <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php if($data->status_acc == 'Disetujui'){ ?>
                   <input type="radio" class="flat" name="status_acc" value="Disetujui" checked="checked"> Disetujui
                   <input type="radio" class="flat" name="status_acc" value="Ditolak"> Ditolak
                 <?php } else{ ?>
                   <input type="radio" class="flat" name="status_acc" value="Disetujui" > Disetujui
                   <input type="radio" class="flat" name="status_acc" value="Ditolak" checked="checked"> Ditolak
                 <?php }?>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Acc HC <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="tgl_hc" required="required" value="<?php echo $data->tgl_hc ?>" />
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Acc BOD <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker4'>
                <input type='text' class="form-control" name="tgl_bod" required="required" value="<?php echo $data->tgl_bod ?>"/>
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
               <a href='<?php echo base_url()?>index.php/Recruitment/pelamar_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
               <button id="send" type="submit" class="btn btn-success">Submit</button>
             </div>
           </div>
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <!-- /page content
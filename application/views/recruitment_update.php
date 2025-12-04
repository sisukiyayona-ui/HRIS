
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Recruitment </h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Edit Recruitment</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <h4><span class="label label-success">Resume FPTK</span></h4>
                   
                  <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/recruitment_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                   <?php echo $this->session->flashdata('message');?>
                   <div class="item form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <?php foreach ($fptk as $fptk) {
                      } ?>
                      <input type="hidden" name="recid_kfptk" value="<?php echo $fptk->recid_kfptk ?>">
                      <table class="table table-striped table-bordered">
                        <tr><td colspan="2"><b>Resume FPTK</b></td></tr>
                        <tr><td>Posisi </td><td><?php echo $fptk->nama_bag." / ".$fptk->nama_jbtn ?></td></tr>
                        <tr><td>Tanggal Efektif </td><td><?php echo $newDate = date("d M Y", strtotime($fptk->tgl_efektif)); ?></td></tr>
                        <tr><td>Tanggal Approve BOD </td><td><?php echo $newDate = date("d M Y", strtotime($fptk->tgl_bod)); ?></td></tr>
                        <tr><td>Pemohon</td><td><?php echo $fptk->nama_karyawan." ( ".$fptk->nama_bags." / ".$fptk->nama_jbtns." )" ?></td></tr>
                        <tr><td>Alasan </td><td><?php echo $fptk->alasan ?></td></tr>
                      </table>
                    </div>
                  </div>
                
              <hr>
              <?php foreach ($recruitment as $data) {
              } if($data->status == "Open"){ ?>
              <input type="hidden" class="form-control col-md-6 col-sm-6 col-xs-12" name="recid_recruitment"  value="<?php echo $data->recid_recruitment?>" readonly>
              <h4><span class="label label-success">Form Open Recruitmen</span></h4>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Judul Recruitment <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12" name="judul_recruitment" placeholder="Judul Recruitment" value="<?php echo $data->judul_recruitment ?>">
                </div>
              </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Open <span class="required" style="color: red">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                   <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_open" required="required" value="<?php echo $data->tgl_open ?>"/>
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sasaran  <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='sasaran'  placeholder="Requirement Kandidat" rows="7"><?php echo $data->sasaran ?></textarea>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jobdesk / Fungsi  <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='jobdesk'  placeholder="Jobdesk / Fungsi" rows="7"><?php echo $data->jobdesk ?></textarea>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Status <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->status == 'Open'){ ?>
                <input type="radio" name="status" class="flat" value="Open" checked="checked">Open
                <input type="radio" name="status" class="flat" value="Closed">Closed
              <?php }else{ ?>
               <input type="radio" name="status" class="flat" value="Open" >Open
               <input type="radio" name="status" class="flat" value="Closed" checked="checked">Closed
              <?php } ?>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"><?php echo $data->note ?></textarea>
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href='<?php echo base_url()?>Karyawan/recruitment_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
               <button id="send" type="submit" class="btn btn-success">Submit</button>
             </div>
           </div>

         <?php } else{ ?>
            <input type="hidden" class="form-control col-md-6 col-sm-6 col-xs-12" name="recid_recruitment"  value="<?php echo $data->recid_recruitment?>" readonly>
              <h4><span class="label label-success">Form Open Recruitmen</span></h4>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Judul Recruitment <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12" name="judul_recruitment" Recruitment" value="<?php echo $data->judul_recruitment ?>" disabled>
                </div>
              </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Open <span class="required" style="color: red">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                   <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_open" required="required" value="<?php echo $data->tgl_open ?>" disabled/>
                    <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                   </span>
                 </div>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sasaran  <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='sasaran' rows="7" disabled><?php echo $data->sasaran ?></textarea>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Jobdesk / Fungsi  <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='jobdesk' rows="7" disabled><?php echo $data->jobdesk ?></textarea>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Status <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php if($data->status == 'Open'){ ?>
                <input type="radio" name="status" class="flat" value="Open" checked="checked" >Open
                <input type="radio" name="status" class="flat" value="Closed" disabled>Closed
              <?php }else{ ?>
               <input type="radio" name="status" class="flat" value="Open" disabled>Open
               <input type="radio" name="status" class="flat" value="Closed" checked="checked" >Closed
              <?php } ?>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name='note'  disabled><?php echo $data->note ?></textarea>
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
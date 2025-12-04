<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Training Karyawan</h3>
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
            <h2><a href="<?php echo base_url()?>Training"><i class="fa fa-arrow-circle-o-left"></i></a> | Training Karyawan</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php 
              foreach ($training as $t) {
                # code...
              }
            ?>
            <table class="table table-striped table-bordered">
              <tr><td colspan="2">Detail Training</td></tr>
              <tr><td>Topik Training</td><td><?php echo $t->judul_training ?></td></tr>
              <tr><td>Jenis Training</td><td><?php echo $t->jenis_training ?></td></tr>
              <tr><td>Lembaga</td><td><?php echo $t->lembaga?> - <?php echo $t->trainer?></td></tr>
              <tr><td>Tanggal</td><td><?php echo $t->tgl_m_training?> s/d <?php echo $t->tgl_a_training?></td></tr>
              <tr><td>Jumlah Jam</td><td><?php echo $t->jml_jam?> Jam</td></tr>
            </table>

            <?php 
              foreach ($peserta->result() as $p) {
                # code...
              }
            ?>
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/evaluasi_peserta" novalidate >
               <hr>
              <h4><span class="label label-success">Form Evaluasi Peserta Training</span></h4>
                
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nama Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nama_karyawan" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->nama_karyawan?>">
                  <input type="hidden" name="recid_karyawan" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->recid_karyawan?>">
                  <input type="hidden" name="recid_training" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $t->recid_training?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">NIK Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nik" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->nik?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Bagian / Department <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->indeks_hr."/". $p->nama_department?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jabatan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->indeks_jabatan?>">
                </div>
              </div>

               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Atasan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $p->atasan1?>">
                </div>
              </div>

              <?php
                foreach ($evaluasi as $e) {
                  
                }
              ?>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Scan Sertifikat  <span style="color: red">*.pdf | jpg | jpeg  |png</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span style="color: red"><a href="<?php echo base_url() ?>images/training/<?php echo $e->sertifikat; ?>"  target="__blank"><?php echo $e->sertifikat ?></span></a><br>
                 
                  <input  type="file" name="sertifikat"  class="form-control col-md-7 col-xs-12">
                   <input  type="hidden" name="old_sertifikat"  class="form-control col-md-7 col-xs-12" value="<?php echo $e->sertifikat?>">
                </div>
              </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Poin Plus
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="poin_plus" class="form-control col-md-7 col-xs-12" placeholder="Poin Plus" value="<?php echo $e->poin_plus?>">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nilai
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php 
                    if($e->pre_test == '1')
                    {?>
                       <label class="checkbox-inline"><input type="checkbox" name = "pre_test" value="1" checked="checked">Pre Test</label>
                    <?php }else{?>
                       <label class="checkbox-inline"><input type="checkbox" name = "pre_test" value="0">Pre Test</label>
                    <?php }

                    if($e->post_test == '1')
                    {?>
                       <label class="checkbox-inline"><input type="checkbox" name = "post_test" value="1" checked="checked">Post Test</label>
                    <?php }else{?>
                       <label class="checkbox-inline"><input type="checkbox" name = "post_test" value="0">Post Test</label>
                    <?php }

                    if($e->superior_rating == '1')
                    {?>
                       <label class="checkbox-inline"><input type="checkbox" name = "superior_rating" value="1" checked="checked">Nilai Atasan</label>
                    <?php }else{?>
                       <label class="checkbox-inline"><input type="checkbox" name = "superior_rating" value="0">Nilai Atasan</label>
                    <?php }

                  ?>
                </div>
              </div>

               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Status Evaluasi<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                    if($e->closed_evaluasi == "Open")
                    { ?>
                      <input type="radio" name="closed_evaluasi" class="radio-inline" value="Open" checked="checked" > Open
                      <input type="radio" name="closed_evaluasi" class="radio-inline" value="Close"> Close
                    <?php }else{ ?>
                      <input type="radio" name="closed_evaluasi" class="radio-inline" value="Open" > Open
                      <input type="radio" name="closed_evaluasi" class="radio-inline" value="Close" checked="checked"> Close
                    <?php }
                  ?>
                  
                </div>
              </div>

<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3">
   <a href="<?php  echo base_url()?>Training"> <button type="button" class="btn btn-danger">Cancel</button></a>
   <button id="send" type="submit" class="btn btn-success">Submit</button>
   <a href="<?php  echo base_url()?>index.php/Training/send_email_evaluasi"> <button type="button" class="btn btn-info">Send Email Evaluasi</button></a>
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


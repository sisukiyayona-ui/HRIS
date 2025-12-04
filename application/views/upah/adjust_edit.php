<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Edit Adjustment Upah</h3>
      </div>
    </div>
            <div class="clearfix"></div>
             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Adjustment Upah</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
   <!-- Content Form -->
            <?php 
                foreach ($adjust->result() as $a) {
                    # code...
                }
            ?>
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Upah/edit_adjust" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Periode Awal<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" name="recid_auph" class="form-control" value="<?php echo $a->recid_auph ?>">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="periode_awal" required="required" value="<?php echo $a->periode_awal ?>"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
         <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Periode Akhir<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker2'>
                <input type='text' class="form-control" name="periode_akhir" required="required" value="<?php echo $a->periode_akhir ?>"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>
        
          <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Untuk Tanggal <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker4'>
                <input type='text' class="form-control" name="tanggal" required="required" value="<?php echo $a->tanggal ?>"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>

        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Karyawan<span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select class="form-control selectpicker" multiple="multiple" data-actions-box="true" data-live-search="true" id="jenis" name="recid_karyawan[]" disabled>
            <?php 
                foreach ($karyawan as $k) {
                if($a->recid_karyawan == $k->recid_karyawan){?>
                    <option value="<?php echo $k->recid_karyawan?>"  selected><?php echo $k->nama_karyawan." - ".$k->nik ."(".$k->indeks_hr.")"?></option>
                <?php }else{?>
                    <option value="<?php echo $k->recid_karyawan?>"><?php echo $k->nama_karyawan." - ".$k->nik ."(".$k->indeks_hr.")"?></option>
                <?php }?>
            <?php }?>
            </select> 
         </div>
      </div>
        
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Makan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="jml_makan" class="form-control" value="<?php echo $a->jml_makan ?>">
          </div>
        </div>

        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Transport
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="jml_transport" class="form-control" value="<?php echo $a->jml_transport ?>">
          </div>
        </div>

        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur1
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="jml_lbr1" class="form-control" value="<?php echo $a->jam_lbr1 ?>">
          </div>
        </div>

        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur2
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="jml_lbr2" class="form-control" value="<?php echo $a->jam_lbr2 ?>">
          </div>
        </div>

        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Jml Lembur3
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="jml_lbr3" class="form-control" value="<?php echo $a->jam_lbr3 ?>">
          </div>
        </div>
       
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control" name="keterangan"><?php echo $a->keterangan ?></textarea>
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
 

</script>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Bagian</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/bagian_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Bagian</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <?php
              foreach ($bagian as $data) {
                # code...
              }
             ?>
             <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/bagian_pupdate" novalidate>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Index <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="indeks_hr" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="indeks_hr" placeholder="Index Bagian" required="required" type="text" value="<?php echo $data->indeks_hr ?>">
                </div>
              </div>
               <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Kode Bagian <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="ekode_bag" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="kode_bag" placeholder="Nama Bagian" required="required" type="text" value="<?php echo $data->kode_bag ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Nama Bagian <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="erecid_bag" type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_bag" readonly value="<?php echo $data->recid_bag ?>">
                  <input id="enama_bag" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_bag" placeholder="Nama Bagian" required="required" type="text" value="<?php echo $data->nama_bag ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_str">Nama Struktur <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="erecid_struktur"  name="recid_struktur" class="selectpicker form-control col-md-7 col-xs-12" required="required">
                    <option value="">-- Pilih --</option>
                    <?php 
                    foreach ($struktur as $option ) { 
                      if($option->recid_struktur == $data->recid_struktur){?>
                        <option value="<?php echo $data->recid_struktur ?>" selected="selected"><?php echo $data->nama_struktur ?></option>
                      <?php }else {?>
                       <option value="<?php echo $option->recid_struktur ?>"><?php echo $option->nama_struktur ?></option>
                     <?php } ?>
                   <?php } ?>
                 </select>
               </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">PIC Bagian<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <select name="pic_bagian" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
                   <option value="">-- Pilih -- </option>
                  <?php 
                  foreach ($karyawan as $key ) {
                    if($key->recid_karyawan == $data->pic_bagian){ ?>
                      <option value="<?php echo $key->recid_karyawan?>" selected="selected"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
                    <?php }else{?>
                      <option value="<?php echo $key->recid_karyawan?>"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
                    <?php }
                  }
                  ?>
                </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Department<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <select name="recid_department" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
                   <option value="">-- Pilih -- </option>
                  <?php 
                  foreach ($department as $dept ) {
                    if($dept->recid_department == $data->recid_department){ ?>
                      <option value="<?php echo $dept->recid_department?>" selected="selected"><?php echo $dept->nama_department ?> </option>
                    <?php }else{?>
                      <option value="<?php echo $dept->recid_department?>"><?php echo $dept->nama_department ?> </option>
                    <?php }
                  }
                  ?>
                </select>
                </div>
              </div>
             <!--  <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Department Group <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="edept_group"  name="dept_group" class="form-control col-md-7 col-xs-12">
                    <?php if($data->dept_group == 'Presdir') {?>
                      <option value="Presdir" selected="selected">Presdir</option>
                      <option value="Front Office">Front Office</option>
                      <option value="Middle Office">Middle Office</option>
                      <option value="Back Office">Back Office</option>
                      <option value="Lain-Lain">Lain-Lain</option>
                    <?php }else if($data->dept_group == 'Front Office'){ ?>
                      <option value="Presdir" >Presdir</option>
                      <option value="Front Office"  selected="selected">Front Office</option>
                      <option value="Middle Office">Middle Office</option>
                      <option value="Back Office">Back Office</option>
                      <option value="Lain-Lain">Lain-Lain</option>
                       <?php }else if($data->dept_group == 'Back Office'){ ?>
                      <option value="Presdir" >Presdir</option>
                      <option value="Front Office">Front Office</option>
                      <option value="Middle Office">Middle Office</option>
                      <option value="Back Office" selected="selected">Back Office</option>
                      <option value="Lain-Lain">Lain-Lain</option>
                    <?php }else if($data->dept_group == 'Middle Office'){ ?>
                      <option value="Presdir" >Presdir</option>
                      <option value="Front Office">Front Office</option>
                      <option value="Middle Office" selected="selected">Middle Office</option>
                      <option value="Back Office">Back Office</option>
                      <option value="Lain-Lain">Lain-Lain</option>
                    <?php }else if($data->dept_group == 'Lain-Lain'){ ?>
                      <option value="Presdir" >Presdir</option>
                      <option value="Front Office">Front Office</option>
                      <option value="Middle Office">Middle Office</option>
                      <option value="Back Office">Back Office</option>
                      <option value="Lain-Lain" selected="selected">Lain-Lain</option>
                    <?php }else{ ?>
                     <option value="">-- Pilih --</option>
                     <option value="Presdir" >Presdir</option>
                     <option value="Front Office">Front Office</option>
                     <option value="Middle Office">Middle Office</option>
                     <option value="Back Office">Back Office</option>
                     <option value="Lain-Lain">Lain-Lain</option>
                   <?php }?>
                  </select>
                </div>
              </div> -->
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Shift <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="eshift"  name="shift" class="form-control col-md-7 col-xs-12">
                    <?php if($data->shift == 'Shift') {?>
                      <option value="Shift" selected="selected">Shift</option>
                      <option value="Non-Shift">Non Shift</option>
                    <?php } else if($data->shift == 'Non-Shift') {?>
                     <option value="Shift">Shift</option>
                     <option value="Non-Shift" selected="selected">Non Shift</option>
                     <?php } else {?>
                       <option value="">-- Pilih --</option>
                       <option value="Shift">Shift</option>
                       <option value="Non-Shift" >Non Shift</option>
                     <?php }?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Pay Group <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="epay"  name="pay_group" class="form-control col-md-7 col-xs-12">
                    <?php if($data->pay_group == 'Direct') {?>
                    <option value="Direct" selected="selected">Direct</option>
                    <option value="Indirect">Indirect</option>
                    <option value="Admin">Admin</option>
                    <option value="Penjualan">Penjualan</option>
                    <option value="BOD">BOD</option>
                    <?php } else if($data->pay_group == 'Indirect') {?>
                     <option value="Direct">Direct</option>
                     <option value="Indirect" selected="selected">Indirect</option>
                     <option value="Admin">Admin</option>
                     <option value="Penjualan">Penjualan</option>
                     <option value="BOD">BOD</option>
                     <?php } else if($data->pay_group == 'Admin') {?>
                     <option value="Direct">Direct</option>
                     <option value="Indirect">Indirect</option>
                     <option value="Admin" selected="selected">Admin</option>
                     <option value="Penjualan">Penjualan</option>
                     <option value="BOD">BOD</option>
                     <?php } else if($data->pay_group == 'Penjualan') {?>
                     <option value="Direct">Direct</option>
                     <option value="Indirect">Indirect</option>
                     <option value="Admin">Admin</option>
                     <option value="Penjualan"  selected="selected">Penjualan</option>
                     <option value="BOD">BOD</option>
                    <?php } else if($data->pay_group == 'BOD') {?>
                     <option value="Direct">Direct</option>
                     <option value="Indirect">Indirect</option>
                     <option value="Admin">Admin</option>
                     <option value="Penjualan">Penjualan</option>
                     <option value="BOD" selected="selected">BOD</option>
                     <?php } else {?>
                     <option value="">-- Pilih --</option>
                     <option value="Direct">Direct</option>
                     <option value="Indirect">Indirect</option>
                     <option value="Admin">Admin</option>
                     <option value="Penjualan">Penjualan</option>
                     <?php } ?>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">GL Account <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="gl_acc" class="form-control" value="<?php echo $data->gl_acc ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Cost Center <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="cost_center" class="form-control" value="<?php echo $data->cost_center ?>">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea class="form-control col-md-7 col-xs-12" name='note' id='enote' placeholder='Keterangan'><?php echo $data->note ?></textarea>
                </div>
              </div>
              <!--/ Content Modal -->
            <br><br>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href='<?php echo base_url()?>Karyawan/bagian_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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
        <!-- /page content-->
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Edit Pengajuan Training Karyawan</h3>
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
            <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Training Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php foreach ($training as $data) {

            } ?>
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/training_pupdate" id="karir" novalidate >

             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jenis Training <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <?php 
                  if($data->jenis_training == "Teknis")
                  { ?>
                    <input type="radio" name="jenis_training" class="flat" value="Teknis" checked="checked"> Teknis
                    <input type="radio" name="jenis_training" class="flat" value="Non Teknis" > Non Teknis
                  <?php }
                  else{ ?>
                    <input type="radio" name="jenis_training" class="flat" value="Teknis"> Teknis
                    <input type="radio" name="jenis_training" class="flat" value="Non Teknis" checked="checked"> Non Teknis
                  <?php } ?>
                
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul Training <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="judul_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->judul_training ?>">
                <input type="hidden" name="recid_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_training ?>" readonly>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Lembaga <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="lembaga" required="required" class="form-control col-md-7 col-xs-12" placeholder="Lembaga / Penyelenggara" value="<?php echo $data->lembaga ?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Trainer <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="trainer" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->trainer ?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_training">Tanggal Mulai <span class="required" style="color: red">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_m_training" required="required" value="<?php echo $data->tgl_m_training ?>"/>
                <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
               </span>
             </div>
           </div>
         </div>

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_training">Tanggal Akhir <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
           <div class='input-group date' id='myDatepicker4'>
            <input type='text' class="form-control" name="tgl_a_training" required="required" value="<?php echo $data->tgl_a_training ?>"/>
            <span class="input-group-addon">
             <span class="glyphicon glyphicon-calendar"></span>
           </span>
         </div>
       </div>
     </div>

     <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Jumlah Jam <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="jml_jam" required="required" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" value="<?php echo $data->jml_jam ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tempat Training <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="tempat_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tempat_training ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Berbayar<span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <?php 
        if($data->berbayar == "Ya")
          { ?>
            <input type="radio" name="berbayar" class="flat" value="Ya" checked="checked"> Ya
            <input type="radio" name="berbayar" class="flat" value="Tidak" > Tidak
          <?php }
          else{ ?>
            <input type="radio" name="berbayar" class="flat" value="Ya"> Ya
            <input type="radio" name="berbayar" class="flat" value="Tidak" checked="checked"> Tidak
          <?php } ?>

        </div>
      </div>

    <div class="item form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Biaya <span class="required" style="color: red">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="biaya" required="required" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="Biaya Sesuai Invoice" value="<?php echo $data->biaya ?>">
      </div>
    </div>  

    <div class="item form-group">
     <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Peserta <span class="required" style="color: red">*</span>
     </label>
     <div class="col-md-6 col-sm-6 col-xs-12">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Tambah Peserta</button><br><br>
      <table class="table table-striped table-bordered">
        <thead>
          <th>NIK</th>
          <th>Nama</th>
          <th>Bagian</th>
          <th>Jabatan</th>
          <th>Aksi</th>
        </thead>
        <tbody>
          <?php
          foreach ($peserta as $key) {?>
            <tr>
              <td><?php echo $key->nik ?></td>
              <td><?php echo $key->nama_karyawan ?></td>
              <td><?php echo $key->indeks_hr ?></td>
              <td><?php echo $key->indeks_jabatan ?></td>
              <td><center><a href="<?php echo base_url()?>index.php/Training/training_dkaryawan/<?php echo $key->recid_tmp ?>/<?php echo $data->recid_training ?>"><span class="fa fa-trash" style="color:red"></span></button></a></center></td>
            </tr>
          <?php  }
          ?>

        </tbody>
      </table>
    </div>
  </div>

  <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File 
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <a href="<?php echo base_url()?>images/training/<?php echo $data->scan_brosur?>" target="__blank"><span  class="fa fa-download">&nbsp;&nbsp;&nbsp;<?php echo $data->scan_brosur ?></span></a><br>
      <span>*.pdf (Isi Bila Akan Diubah)</span>
      <input  type="hidden" name="scan_brosur2" class="form-control col-md-7 col-xs-12" value="<?php echo $data->scan_brosur ?>">
      <input  type="file" name="scan_brosur"  class="form-control col-md-7 col-xs-12">
    </div>
  </div>

  <div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Alasan Pengajuan
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <textarea id="alasan_pengajuan" name="alasan_pengajuan" class="form-control col-md-7 col-xs-12"><?php  echo $data->alasan_pengajuan ?></textarea>
    </div>
  </div>
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-md-offset-3">
     <a href="<?php  echo base_url()?>Karyawan/karyawan_viewbeta"> <button type="button" class="btn btn-primary">Cancel</button></a>
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
<!-- /page content -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Peserta</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Training/training_addkar" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nama Karyawan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="recid_training" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_training ?>">
            <select name="nik2[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required">
             <?php
             foreach ($karyawan2 as $option) {
               echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
             }
             ?>
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
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>

</div>
</div>
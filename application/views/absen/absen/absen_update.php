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
                    <h2><a href="<?php echo base_url()?>Karyawan/absen_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Update Absen Karyawan</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Absen/absen_pupdate" novalidate>
              <?php foreach ($absensi->result() as $data) {
                # code...
              } ?>
             <!-- <span class="section">Personal Info</span>-->
                 <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="nik" readonly class="form-control col-md-7 col-xs-12"  value="<?php echo $data->nik ?>" readonly>
                    <input type="hidden" name="recid_absen" readonly class="form-control col-md-7 col-xs-12"  value="<?php echo $data->absensi_recid ?>" readonly>
                  </div>
                </div>
                <div class="item form-group">
                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required">*</span>
                 </label>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nama" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->nama_karyawan ?>" readonly>
                  <input type="hidden" name="absensi_recid" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->absensi_recid ?>" readonly>
                  <input type="hidden" name="recid_karyawan" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_karyawan ?>" readonly>
                </div>
              </div>
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Bagian<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="text" name="bag" readonly class="form-control col-md-7 col-xs-12" value="<?php echo $data->indeks_hr ?>" readonly>
               </div>
             </div>
             <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Akumulasi Sisa Cuti <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id='sisa_cuti' class="form-control" value="<?php echo $sisa_cuti ?>" readonly="readonly">
              </div>
            </div> 
            <?php 
              if($data->cuti_ke == NULL)
              {?>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Tahun Cuti Dipakai <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id='cuti_thn_ke' name="cuti_thn_ke" class="form-control" value="<?php echo $cuti_thn_ke?>">
                    </div>
                  </div> 
              <?php }else{?>
                 <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Tahun Cuti Dipakai <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id='cuti_thn_ke' name="cuti_thn_ke" class="form-control" value="<?php echo $data->cuti_ke?>">
                    </div>
                  </div> 
              <?php }
            ?>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tgl_mulai" required="required" value="<?php echo $data->tanggal ?>"  />
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
               <?php 
                foreach ($jenis->result() as $j) {
                  if($j->recid_jenisabsen == $data->jenis_absen)
                  {?>
                    <option value="<?php echo $j->recid_jenisabsen?>" selected><?php echo $j->jenis?> (<?php echo $j->keterangan?>)</option>
                  <?php }else{?>
                    <option value="<?php echo $j->recid_jenisabsen?>"><?php echo $j->jenis?> (<?php echo $j->keterangan?>)</option>
                  <?php }
                }
               ?>
            </select>
          </div>
        </div>
         <div class="item form-group" id="diagnosa">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Diagnosa
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="ediagnosa" name="diagnosa" class="form-control col-md-7 col-xs-12"><?php echo $data->diagnosa?></textarea>
          </div>
        </div>
         <div class="item form-group" id="kategori">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Kategori
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <?php 
              $kat = ["","Ringan", "Berat", "Berkelanjutan"];
            ?>
            <select name="kategori" id="ekategori" class="form-control">
              <?php 
              for($k=0;$k<count($kat);$k++)
                {if($kat[$k] == $data->kategori){?>
                  <option value="<?php echo $kat[$k]?>" selected><?php echo $kat[$k]?></option>
                <?php }else{?>
                  <option value="<?php echo $kat[$k]?>"><?php echo $kat[$k]?></option>
                <?php }?>
              <?php }
              ?>
            </select>
          </div>
        </div>
         <div class="item form-group" id="detail_sakit">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Detail Sakit
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="edetail_sakit" name="ket_sakit" class="form-control col-md-7 col-xs-12"><?php echo $data->ket_sakit?></textarea>
            <input type="hidden" name="validasi_cuti" class="form-control col-md-7 col-xs-12" value="<?php echo $data->validasi_cuti?>">
          </div>
        </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"><?php echo $data->keterangan?></textarea>
          </div>
        </div>
           
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
               <a href="<?php  echo base_url()?>Karyawan/absen_view"> <button type="button" class="btn btn-primary">Cancel</button></a>
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
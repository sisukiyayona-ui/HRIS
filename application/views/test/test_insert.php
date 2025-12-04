<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Test </h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Recruitment Test</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Recruitment/test_pinsert" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                    
                      <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tgl_m_training">
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                          <?php foreach ($recruitment as $data) {?>
                          <?php } ?>
                          <input type="hidden" class="form-control col-md-6 col-sm-6 col-xs-12" name="recid_recruitment"  value="<?php echo $data->recid_recruitment?>" readonly>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Judul Recruitment <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12" name="judul_recruitment" value="<?php echo $data->judul_recruitment ?>" readonly>
                       </div>
                     </div>

                     <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Judul Test <span class="required" style="color: red">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                           <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12" name="judul_test">
                         </div>
                       </div>

                        <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Tanggal Test <span class="required" style="color: red">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myDatepicker2'>
                              <input type='text' class="form-control" name="tgl_test" placeholder="thn-bln-tgl" required="required" />
                              <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                             </span>
                           </div>
                         </div>
                       </div>

                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Kandidat <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_pelamar[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required">
                           <?php
                           foreach ($pelamar as $option) {
                             echo "<option value='$option->recid_pelamar'>$option->nama_pelamar ($option->email)</option>";
                           }
                           ?>
                         </select>
                       </div>
                     </div>

                     <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"></textarea>
                      </div>
                    </div>

                    <div class="ln_solid" ></div>
                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                       <a href='<?php echo base_url()?>Karyawan/recruitment_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                       <button id="send" type="submit" class="btn btn-success">Submit</button>
                     </div>
                   </div> 
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
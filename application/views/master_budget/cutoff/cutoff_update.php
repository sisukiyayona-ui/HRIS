
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Cut Off Lembur</h3>
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
                     <h2><a href="<?php echo base_url()?>Lembur/cutoff_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Cut Off Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php foreach ($cutoff as $data) {
                      # code...
                    } ?>
                      <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/cutoff_pupdate" novalidate >
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Tahun Budget<span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" name="tahun" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->tahun ?>">
                          <input type="hidden" name="recid_clembur" required="required" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" value="<?php echo $data->recid_clembur ?>">
                        </div>
                      </div>
                          <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulan">Bulan<span class="required" style="color: red">*</span>
                            </label>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <input type="text" name="bulan" class="form-control col-md-7 col-xs-12" value="<?php echo $data->bulan ?>">
                            </div>
                        </div>
                         <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulan">Periode Awal<span class="required" style="color: red">*</span>
                            </label>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <input type='date' class="form-control" name="tgl_dari" value="<?php echo $data->periode_awal ?>"required="required"/>
                            </div>
                        </div>
                          <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Bulan">Periode Akhir<span class="required" style="color: red">*</span>
                            </label>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <input type='date' class="form-control" name="tgl_sampai" value="<?php echo $data->periode_akhir ?>" required="required"/>
                          </div>
                        </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <textarea id="note" name="note" class="form-control col-md-7 col-xs-12" placeholder="Keterangan"><?php echo $data->note?></textarea>
                        </div>
                      </div>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <a href='<?php echo base_url()?>Lembur/masterbudget_view'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
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
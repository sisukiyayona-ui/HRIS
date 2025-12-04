<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Renew Data Karyawn</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Renew </h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form method="post" action="<?php echo base_url()?>Karyawan/karyawan_renew">
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Pilih Karyawan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="recid_karyawan" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                              <?php
                              echo "<option value=''>-- Pilih --</option>";
                              foreach ($karyawan as $id) { ?>
                                <option value="<?php echo $id->recid_karyawan ?>"><?php echo $id->nama_karyawan ?></option>
                              <?php }
                              ?>
                            </select>
                       </div>
                     </div>
                      <button type="sumbit" class="btn btn-primary">Copy</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
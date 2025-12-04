<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Report Master Budget</h3>
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
                    <h2>Master Budget Lembur</h2>
                  <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                       <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/r_master_budget" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Tahun Budget</label></div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                              <select class="form-control" id="tahun" name="tahun">
                               <?php foreach ($tahun as $tahun){ ?>
                                <option value="<?php echo $tahun->tahun ?>"><?php echo $tahun->tahun ?></option>
                               <?php } ?>
                              </select>
                            </div>
                          </div>                          
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <br><br>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                              <button type="submit" class="btn btn-primary">Cari Report</button>
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
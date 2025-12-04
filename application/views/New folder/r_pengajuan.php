<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Pengajuan Budget Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                        <form method="post" action="<?php echo base_url()?>Karyawan/r_ppengajuan">
                         <div class="item form-group">
                          <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Mulai Dari 
                          </label>
                          <div class="col-md-2 col-sm-2 col-xs-12">
                           <div class='input-group date' id='myDatepicker3'>
                            <input type='text' class="form-control" name="tgl_awal" id='awal' required="required" />
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div>
                       </div>
                     </div>
                     <div class="item form-group">
                      <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Sampai
                      </label>
                      <div class="col-md-2 col-sm-2 col-xs-12">
                       <div class='input-group date' id='myDatepicker2'>
                        <input type='text' class="form-control" name="tgl_akhir" id='akhir' required="required" />
                        <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                       </span>
                     </div>
                   </div>
                 </div>
                 
                <div class="form-group">
                  <div class="col-md-2 col-sm-2 col-xs-12">
                   <input type="submit" class="btn btn-success" value="Submit">
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


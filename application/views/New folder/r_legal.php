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
                    <h2><a href="<?php echo base_url()?>Karyawan/legal_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Legal</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                        <form method="post" action="">
                         <div class="item form-group">
                          <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Sejak 
                          </label>
                          <div class="col-md-2 col-sm-2 col-xs-12">
                           <div class='input-group date' id='myDatepicker3'>
                            <input type='text' class="form-control" name="tgl_m_karir" id='awal' required="required" />
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
                        <input type='text' class="form-control" name="tgl_m_karir" id='akhir' required="required" />
                        <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                       </span>
                     </div>
                   </div>
                 </div>
                 <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Status Berkas
                  </label>
                  <div class="col-md-2 col-sm-2 col-xs-12">
                    <select class="form-control" id="jenis">
                      <option>-- Pilih --</option>
                      <option value="Open">Open</option>
                      <option value="Process">Process</option>
                      <option value="Closed">Closed</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group">
                  <div class="col-md-2 col-sm-2 col-xs-12">
                   <button id="send" type="button" class="btn btn-success" onclick="notif()">Submit</button>
                 </div>
               </div>  
             </form>
                  <br>
                      <table id="t_notif" class="table table-striped table-bordered" style="display: none" >
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Judul</th>
                          <th>Tanggal Akhir</th>
                          <th>Status</th>
                          <th>File</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


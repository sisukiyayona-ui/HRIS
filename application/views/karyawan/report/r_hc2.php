<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report Human Resource</h3>
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
                    <h2><a href="<?php echo base_url()?>Karyawan/karyawan_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Human Resource</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <form enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Karyawan/r_hc" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
                         
                           <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-2 col-sm-2 col-xs-2"><label>Filter By</label></div>
                            <div class="col-md-4 col-sm-4 col-xs-4" id="form-clone" >
                              <select class="form-control" name="filter2" id="cloning[]" onchange="aksi();">
                                <option value="">-- Pilih Filter --</option>
                                <option value="div">Divisi</option>
                                <option value="dept">Department</option>
                                <option value="bagian">Bagian</option>
                                <option value="gol">Golongan / Jabatan</option>
                                <option value="sts_kry">Status Karyawan</option>
                                <option value="jenkel">Jenis Kelamin</option>
                                <option value="sts_kwn">Status Perkawinan</option>
                                <option value="usia">Usia</option>
                                <option value="masker">Masa Kerja</option>
                                <option value="pend">Pendidikan</option>
                                <option value="tunj">Tanggungan</option>
                                <option value="agama">Agama</option>
                              </select>
                            </div>
                            <div id="filter"  style="display: none">
                              <div class="col-md-2 col-sm-2 col-xs-2">
                                <select  id="pdiv"  class="form-control" name="pdiv" >
                                  <option value="">-- Pilih Divisi --</option>
                                  <option value="Front Office">Front Office</option>
                                  <option value="Middle Office">Middle Office</option>
                                  <option value="Back Office">Back Office</option>
                                </select>
                              </div>
                            </div>
                          </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <br><br>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                              <button type="submit" class="btn btn-primary"><i class="fa fa-search-plus"></i></button>
                              <button type="button" class="btn btn-success" id="add"><i class="fa fa-plus"></i></button>
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

        <script type="text/javascript">
          $(document).ready(function() {
            var addbutton = document.getElementById("add");
            addbutton.addEventListener("click", function() {
              var boxes = document.getElementById("form-clone");
              var clone = boxes.firstElementChild.cloneNode(true);
              boxes.appendChild(clone);
            });
          });
          function aksi(){
            var filter1 =  $("#cloning").val();
            if(filter1 == 'div'){
              $("#pdiv").show();
            }
          }
        </script>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Data Tunjangan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a class="btn btn-app" href="<?php echo base_url()?>Karyawan/tunjangan_insert">
                      <i class="fa fa-plus"></i> Tambah Data
                    </a>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!--Add content to the page ...-->
                      <!-- Content Table -->
           <table id="datatable-buttons" class="table table-striped table-bordered">
             <thead>
                <tr>
                  <th>Nama Karyawan</th>
                  <th>Nama Tunjangan</th>
                  <th>Hubungan Keluarga</th>
                  <th>Status Tunjangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($tunjangan as $data) {
                echo "
                  <tr>
                    <td>$data->nama_karyawan</td>
                    <td>$data->nama_tunjangan</td>
                    <td>$data->hub_keluarga</td>
                    <td>$data->sts_tunjangan</td>
                    <td><center>";?>
                     <a href="<?php echo base_url()?>Karyawan/tunjangan_update/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                    <a href="<?php echo base_url()?>Karyawan/tunjangan_detail/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>
                <?php 
                }
                ?>
             
            </tbody>
          </table>
          <!--/ Content Table -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
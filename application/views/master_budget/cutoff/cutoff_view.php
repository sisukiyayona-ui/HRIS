<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
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
                   <?php if($role == '1' or $role == '7' or $role == '5' ){ ?>
                    <a class="btn btn-info btn-sm" href="<?php echo base_url()?>Lembur/cutoff_insert">
                      <i class="fa fa-plus"></i>  | Cut off Lembur
                    </a> 
                    <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <table id="t_tahun" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th><center>Tahun</center></th>
                          <th><center>Bulan</center></th>
                          <th><center>Periode Awal</center></th>
                          <th><center>Periode Akhir</center></th>
                          <th><center>Keterangan</center></th>
                           <?php if($role == '1' or $role == '5'){?>
                          <th><center>Aksi</center></th>
                        <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                       <?php 
                       foreach ($cutlembur as $data) {
                        echo "
                        <tr>
                        <td>$data->tahun</td>
                        <td>$data->bulan</td>
                        <td>$data->periode_awal</td>
                        <td>$data->periode_akhir</td>
                        <td>$data->note</td>";
                        if($role == '1' or $role == '5'){?>
                        <td><center>
                         <!--  <a href="<?php echo base_url()?>Karyawan/cutoff_detail/<?php echo $data->recid_clembur?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a> -->
                          <a href="<?php echo base_url()?>Lembur/cutoff_update/<?php echo $data->recid_clembur?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                        <?php } ?>
                      <?php } ?>

                    </tbody>
                     </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
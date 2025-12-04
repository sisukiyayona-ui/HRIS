<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List Update Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
         <?php if($role == '1' or $role == '2' ){ ?>
          <div class="x_title">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url()?>Karyawan/karyawan_insert">
              <i class="fa fa-plus"></i> | Karyawan
            </a>
            <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Karir/karir_insert">
              <i class="fa fa-plus"></i>  | Karir
            </a> 
              <a class="btn btn-info btn-sm" href="<?php echo base_url()?>Karyawan/training_insert">
              <i class="fa fa-plus"></i>  | Training
            </a> 
            <a class="btn btn-warning btn-sm" href="<?php echo base_url()?>Karyawan/tunjangan_insert">
              <i class="fa fa-plus"></i> | Tanggungan
            </a>
             <a class="btn btn-default btn-sm" href="<?php echo base_url()?>Karyawan/renew">
              <i class="fa fa-plus"></i> | Renew Data
            </a>
             <a class="btn btn-danger btn-sm" href="<?php echo base_url()?>Karyawan/renew">
              <i class="fa fa-plus"></i> | Updating Data (<?php echo $notif_edit ?>)
            </a>
            <div class="clearfix"></div>
          </div>
        <?php } ?>
        <div class="x_content">
          <!--Add content to the page ...-->
          <!-- Content Table -->
          <h2>Antrian Untuk Update HC</h2>
          <div class="table-responsive">
            <table id="t_kar" class="table table-striped table-bordered">
             <thead>
              <tr>
                <?php
                ?>
                <th><center>Nik</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Tempat Tanggal Lahir</center></th>
                <th><center>Jenis Kelamin</center></th>
                <th><center>No KTP</center></th>
                <th><center>Aksi</center></th>
              </tr>
            </thead>
            <tbody>
             <?php 
             foreach ($temp_kary as $data) {
              echo "
              <tr>
              <td>$data->nik</td>
              <td>$data->nama_karyawan</td>
              <td>$data->tmp_lahir , $data->tgl_lahir</td>
              <td>$data->jenkel</td>
              <td>$data->no_ktp</td>
              <td><center>";
              $role = $this->session->userdata('role_id');
              ?>
              <a href="<?php echo base_url()?>Karyawan/karyawan_self_update2/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
              <?php 
            }
            ?>

          </tbody>
        </table>
      </div>
   
      <!--/ Content Table -->
    </div>
  </div>
</div>
</div>
</div>
</div>
        <!-- /page content -->
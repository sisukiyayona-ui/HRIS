<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan Belum SK</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
         <?php if($role == '1' or $role == '2' or $role = '5'){ ?>
          <div class="x_title">
            <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Data Karyawan Belum SK</h2>
            <div class="clearfix"></div>
          </div>
        <?php } ?>
        <div class="x_content">
          <!--Add content to the page ...-->
          <!-- Content Table -->
          <div class="table-responsive">
          <table id="t_kar" class="table table-striped table-bordered">
           <thead>
            <tr>
			<?php
				if($role == '1'){ ?>
				<th><center>RECID</center></th>		
				<?php }
			?>
              <th><center>Nik</center></th>
              <th><center>Nama Karyawan</center></th>
              <th><center>Bagian</center></th>
              <th><center>Jabatan</center></th>
              <th><center>Status</center></th>
              <th><center>Tanggal Mulai</center></th>
              <th><center>Tanggal Berakhir</center></th>
              <th><center>Aksi</center></th>
            </tr>
          </thead>


          <tbody>
           <?php 
           foreach ($karyawan as $data) {
            if($data->recid_bag == 0){
              $nama_bag = "-";
            }else{
              $nama_bag = $data->nama_bag;
            }

            if($data->recid_jbtn == 0){
              $nama_jbtn = "-";
            }else{
              $nama_jbtn = $data->nama_jbtn;
            }
            echo "
            <tr>
			";
			if($role == '1'){
				echo "<td>$data->recid_karyawan</td>";
			}
			echo"
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$nama_bag</td>
            <td>$nama_jbtn</td>
            <td>$data->sts_aktif</td>
            <td>$data->tgl_m_kerja</td>
            <td>$data->tgl_a_kerja</td>
            <td><center>";
            if($role == '1' or $role == '2' or $role == '5'){?>
              <a href="<?php echo base_url()?>Karyawan/karyawan_updatebeta/<?php echo $data->recid_karyawan ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
            <?php } ?>
            <?php  if($role == '1' or $role == '2' or $role == '5' or $role == '6'){?>
              <a href="<?php echo base_url()?>Karyawan/karyawan_detail/<?php echo $data->recid_karyawan ?>"><button class="btn btn-success btn-xs"><span class='fa fa-search-plus'></span></button></a>
            <?php } else{ echo "-";}?>
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
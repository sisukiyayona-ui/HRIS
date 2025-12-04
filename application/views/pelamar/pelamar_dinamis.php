
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $menu ?></h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_content">
          <!--Add content to the page ...-->
          <!-- Content Table -->
          <div class="table-responsive">
          <table id="t_dinamis" class="table table-striped table-bordered">
           <thead>
            <tr>
              <?php if($role == '1'){ ?>
                <th><center>recid_pelamar</center></th>
              <?php } ?>
              <th><center>Nama Pelamar</center></th>
              <th><center>Tempat, Tanggal Lahir</center></th>
              <th><center>Gender</center></th>
              <th><center>Golongan Darah</center></th>
              <th><center>No KTP</center></th>
              <th><center>Agama</center></th>
              <th><center>Pendidikan</center></th>
              <th><center>Instansi</center></th>
              <th><center>Thn Lulus</center></th>
              <th><center>Status Nikah</center></th>
              <th><center>Alamat KTP</center></th>
              <th><center>Alamat Sekarang</center></th>
              <th><center>Email</center></th>
              <th><center>Telp</center></th>
            </tr>
          </thead>


          <tbody>
           <?php 
           foreach ($kandidat->result() as $data) {
            echo "<tr>";
            if($role == '1'){ ?>
            <td><?php echo $data->recid_pelamar?></td>
            <?php } ?>
            <td><?php echo$data->nama_pelamar?></td>
            <td><?php echo$data->ptmp_lahir?>, <?php echo$data->tgl_lahir?></td>
            <td><?php echo$data->pjenkel?></td>
            <td><?php echo$data->pgoldar?></td>
            <td><?php echo$data->no_ktp?></td>
            <td><?php echo$data->pagama?></td>
            <td><?php echo$data->ppendidikan?>, <?php echo$data->pjurusan?></td>
            <td><?php echo$data->pinstansi?></td>
            <td><?php echo$data->pthn_lulus?></td>
            <td><?php echo$data->psts_kawin?></td>
            <td><?php echo$data->palamat_ktp?></td>
            <td><?php echo$data->alamat?></td>
            <td><?php echo$data->email?></td>
            <td><?php echo $data->no_telp ." / ". $data->ptelp_alt ?></td></tr>
          <?php }
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
        <!-- /page content
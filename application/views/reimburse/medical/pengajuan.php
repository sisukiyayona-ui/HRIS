<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Pengajuan Reimburse Pengobatan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url()?>Medical/pengajuan_form">
              <i class="fa fa-plus"></i> | Tambah Data
            </a>
          
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Tipe</th>
                  <th>Nama Karyawan</th>
                  <th>Nama Kuitansi</th>
                  <th>Tanggal Kuitansi</th>
                  <th>Nominal</th>
                  <th>Realisasi</th>
                  <th>File</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
                <?php 
                foreach ($pengajuan->result() as $data) {
                  echo "
                  <tr>
                  <td>$data->tipe_medic</td>
                  <td>$data->nama_karyawan</td>
                  <td>$data->nama_kuitansi</td>
                  <td>$data->tgl_kuitansi</td>
                   <td>"; echo number_format($data->nominal);echo"</td>
                   <td>"; echo number_format($data->nilai_ganti);echo"</td>
                  <td><a target='__blank' href='"; echo base_url()."$data->path_medical$data->file_medical'>$data->file_medical</td>
                  <td><center>"; if($data->status == "Ditolak"){?><font color="red"><?php echo $data->status; }else{?><?php echo $data->status; } ?><?php echo "</td>
                  <td><center>";?>
                  <a href="<?php echo base_url()?>Medical/pengajuan_edit/<?php echo $data->medical_recid ?>">
                  <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?>
                </a>
                <?php if($data->status == "Pengajuan"){ ?>
                  <a href="<?php echo base_url()?>Medical/realisasi/<?php echo $data->medical_recid ?>">
                  <?php echo"<button class='btn btn-success btn-xs'><span class='fa fa-check'></button>&nbsp;&nbsp;&nbsp;";?>
                </a>
                <?php } ?>
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


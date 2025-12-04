<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Realisasi Pengajuan Medical</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
           <!--  <a class="btn btn-primary btn-sm" href="<?php echo base_url()?>Medical/pengajuan_form">
              <i class="fa fa-plus"></i> | Tambah Data
            </a> -->
          
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <h4 style="color: red;"><?php echo $this->session->flashdata('warning'); ?></h2>
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Tahun</th>
                  <th>Nama Karyawan</th>
                  <th>Jumlah Plafon</th>
                  <th>Total Realisasi</th>
                  <th>Sisa Plafon</th>
                </tr>
              </thead>


              <tbody>
                <?php 
                foreach ($realisasi->result() as $data) {
                  $sisa = $data->jumlah_plafon - $data->tot_realisasi;
                  echo "
                  <tr>
                  <td>$data->tahun</td>
                  <td>$data->nama_karyawan</td>
                  <td>".number_format($data->jumlah_plafon)."</td>
                  <td>".number_format($data->tot_realisasi)."</td>
                  <td>".number_format($sisa)."</td>
                  ";
              }  ?>

              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


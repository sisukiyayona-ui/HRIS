<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $judul ?></h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <?php if ($role == '1' or $role == '2' or $role == '5' or $role == '4') { ?>
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Karyawan/legal_insert">
                <i class="fa fa-plus"></i> | Tambah Data
              </a>
            <?php } ?>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_rec" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Nomor</th>
                    <th>Judul</th>
                    <th>Tanggal Berlaku</th>
                    <th>Tanggal Selesai</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Scan File</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($legal as $data) {
                    echo "
                        <tr>
                        <td>$data->no_perjanjian</td>
                        <td>$data->judul_perjanjian</td>
                        <td>";
                    echo $newDate = date("d M Y", strtotime($data->tgl_m_legal));
                    echo "</td>
                        <td>";
                    if ($data->tgl_a_legal == '0000-00-00') {
                      echo "00-00-0000";
                    } else {
                      echo $newDate = date("d M Y", strtotime($data->tgl_a_legal));
                    }
                    echo "</td>
                        <td>$data->jenis_perjanjian</td>
                        <td>$data->sts_legal</td>
                        <td>"; ?><a href='<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian; ?>' target="__blank"><?php echo  substr($data->scan_perjanjian, 0, 20); ?></a></td>
                    <td>
                      <center>
                        <?php if ($role == '1' or $role == '2' or $role == '4') { ?>
                          <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><button class="btn btn-info btn-xs"><span class='fa fa-edit'></span></button></a>
                          <a href="<?php echo base_url() ?>Karyawan/legal_delete/<?php echo $data->recid_legal ?>"><button class="btn btn-danger btn-xs"><span class='fa fa-trash'></span></button></a>
                        <?php } ?>
                      </center>
                    </td>
                  <?php  } ?>
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
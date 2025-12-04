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
          <div class="x_title">
            <?php if ($role == '1' or $role == '3' or $role == '5') { ?>
              <a href="<?php echo base_url() ?>Absen/cukar_input"><button type="button" class="btn btn-primary">+ | Cuti Karyawan</button></a>
            <?php } ?>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="rekap_absen" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>
                      <center>No</center>
                    </th>
                    <th>
                      <center>NIK</center>
                    </th>
                    <th>
                      <center>Nama Karyawan</center>
                    </th>
                    <th>
                      <center>Bagian</center>
                    </th>
                    <th>
                      <center>Jabatan</center>
                    </th>
                    <th>
                      <center>Cuti Tahun Ke</center>
                    </th>
                    <th>
                      <center>Sisa Cuti</center>
                    </th>
                    <th>
                      <center>Tanggal Expired</center>
                    </th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $no = 0;
                  foreach ($rekap->result() as $r) { ?>
                    <tr>
                      <td><?php echo $no = $no + 1 ?></td>
                      <td><?php echo $r->nik ?></td>
                      <td><?php echo $r->nama_karyawan ?></td>
                      <td><?php echo $r->indeks_hr ?></td>
                      <td><?php echo $r->indeks_jabatan ?></td>
                      <td>ke-<?php echo $r->cuti_thn_ke ?></td>
                      <td><?php echo $r->sisa_cuti ?> hari</td>
                      <td><?php echo $r->expired ?></td>
                      <td>
                        <?php
                        if ($role == '1' or $role == '3' or $role == '5') { ?>
                          <a href="<?php echo base_url() ?>Absen/adjust_cuti/<?php echo $r->recid_cuti ?>"><button type="button" class="btn btn-xs btn-warning"><span class='fa fa-edit'></span></button></a>
                        <?php } ?>
                        <a href="<?php echo base_url() ?>Absen/histori_cuti/<?php echo $r->recid_cuti ?>"><button type="button" class="btn btn-xs btn-info"><i class="fa fa-info-circle"></i></button></a>

                      </td>
                    </tr>
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
<!-- /page content -->


<script>
  $(document).ready(function() {
    var table = $('#rekap_absen').DataTable({
      paging: true,
      "pageLength": 30,
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        'excel'
      ]
    });
  });
</script>
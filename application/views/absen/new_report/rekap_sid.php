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
            <form enctype="multipart/form-data" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-2 col-sm-2 col-xs-2"><label>Bulan</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <?php $bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                  $x = 0; ?>
                  <select name="bulan" class="form-control" id="bulan">
                    <?php
                    for ($i = 0; $i < count($bulan); $i++) {
                      if ($x + 1 == date('m')) { ?>
                        <option value="<?php echo $x = $x + 1 ?>" selected><?php echo $bulan[$i] ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $x = $x + 1 ?>"><?php echo $bulan[$i] ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2"><label>Tahun</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control" name="tahun" id="tahun">
                    <?php
                    foreach ($tahun->result() as $t) {
                      if ($tahun == date('Y')) { ?>
                        <option value="<?php echo $t->tahun ?>" selected><?php echo $t->tahun ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $t->tahun ?>"><?php echo $t->tahun ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-1">
                  <button type="button" class="btn btn-primary" onclick="getData()">Cari</button>
                </div>
              </div>

            </form>
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
                      <center>Kategori</center>
                    </th>
                    <th>
                      <center>Diagnosa</center>
                    </th>
                    <th>
                      <center>Jumlah Hari</center>
                    </th>
                    <th>
                      <center>Tanggal</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                
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
    getData();
  });

  function getData() {
    bulan = document.getElementById("bulan").value;
    tahun = document.getElementById("tahun").value;
    var table = $('#rekap_absen').DataTable();
    table.destroy();
    var table = $('#rekap_absen').DataTable({
      paging: true,
      "pageLength": 30,
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        'excel'
      ],
      "ajax": {
        type: "POST",
        url: "<?php echo base_url(); ?>Absen/get_rekap_sid",
        dataType: 'JSON',
        data: {
          bulan: bulan,
          tahun: tahun
        },
      },
    });
  }
</script>
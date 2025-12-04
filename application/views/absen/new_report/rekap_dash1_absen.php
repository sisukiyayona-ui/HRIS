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
                      <center>Golongan</center>
                    </th>
                    <th>
                      <center>Jumlah Hari</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $no = 0;
                  foreach ($rekap->result() as $r) {
                    $bagian = $r->indeks_hr;
                    $bagian = $bagian ? substr($bagian, strpos($bagian, ". ") + 1) : '';

                    $jabatan = $r->indeks_jabatan;
                    $jabatan = $jabatan ? substr($jabatan, strpos($jabatan, ". ") + 1) : '';

                    $golongan = $r->nama_golongan;
                    $golongan = $golongan ? substr($golongan, strpos($golongan, ". ") + 1) : '';

                    $struktur = $r->nama_struktur;
                    $struktur = $struktur ? substr($struktur, strpos($struktur, ". ") + 1) : '';
                  ?>
                    <tr>
                      <td><?php echo $no = $no + 1 ?></td>
                      <td><?php echo $r->nik ?></td>
                      <td><?php echo $r->nama_karyawan ?></td>
                      <td><?php echo $bagian ?></td>
                      <td><?php echo $jabatan ?></td>
                      <td><?php echo $golongan ?></td>
                      <td><?php echo $r->lama ?></td>
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
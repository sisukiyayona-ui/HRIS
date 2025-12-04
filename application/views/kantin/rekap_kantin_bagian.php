  <style>
    td {
      text-align: center;
    }
  </style>
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Rekapitulasi Kupon Makan Kantin</h3>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Periode <?php echo $mulai ?> s/d <?php echo $sampai ?> </h2>
              <?php
              $mulai_t = new DateTime($mulai);
              $sampai_t = new DateTime($sampai);
              $jml_hari = $sampai_t->diff($mulai_t)->days + 1; ?>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <input type="hidden" id="mulai" value="<?php echo $mulai ?>">
              <input type="hidden" id="sampai" value="<?php echo $sampai ?>">
              <table class="table table-bordered" id="rekap_absen" border="1" font-color:"black">
                <thead>
                  <tr>
                    <th rowspan="2">No</th> <!-- column 1 -->
                    <th rowspan="2">Bagian</th> <!-- column 2 -->
                    <th colspan="<?php echo $jml_hari ?>"><center>Tanggal</center></th> <!-- Loop sesuai tgl -->
                    <th rowspan="2">Total Makan</th> <!-- total -->
                  </tr>
                  <tr>
                    <?php
                    $mulai1 = $mulai;
                    $sampai1 = $sampai;
                    while ($mulai1 <= $sampai1) { ?>
                      <th><?php echo $mulai1; ?> </th>
                      <?php
                      $mulai1 = date('Y-m-d', strtotime('+1 days', strtotime($mulai1))); ?>
                    <?php }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $total_cint = 0;
                  $mulai8 = $mulai;
                  $sampai8 = $sampai;
                  while ($mulai8 <= $sampai8) {
                    $tgls = DateTime::createFromFormat("Y-m-d", $mulai8);
                    $tgl = $tgls->format("d");
                    $bulan = $tgls->format("m");
                    ${'totctgl' . $tgl . $bulan} = 0;
                    // echo ${'totbtgl'.$tgl.$bulan};
                  ?>
                    <!-- <td><?php echo $mulai8; ?> </td> -->
                    <?php
                    $mulai8 = date('Y-m-d', strtotime('+1 days', strtotime($mulai8))); ?>
                    <?php }
                  foreach ($divisi->result() as $dg) {
                    $dept = $this->db->query("SELECT distinct(d.recid_department), d.nama_department from department d join bagian b on b.recid_department = d.recid_department join karyawan k on k.recid_bag = b.recid_bag where k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak' and tc = '0' and dept_group = '$dg->dept_group' and d.is_delete = '0' order by nama_department asc ");
                    $tot_bag_all = 0;
                    $mulai7 = $mulai;
                    $sampai7 = $sampai;
                    while ($mulai7 <= $sampai7) {
                      $tgls = DateTime::createFromFormat("Y-m-d", $mulai7);
                      $tgl = $tgls->format("d");
                      $bulan = $tgls->format("m");
                      ${'totdptgl' . $tgl . $bulan} = 0;
                      // echo ${'totbtgl'.$tgl.$bulan};
                    ?>
                      <!-- <td><?php echo $mulai7; ?> </td> -->
                      <?php
                      $mulai7 = date('Y-m-d', strtotime('+1 days', strtotime($mulai7))); ?>
                      <?php }
                    foreach ($dept->result() as $dp) {
                      $total_dept = 0;
                      $no = 0;
                      $bagian =
                        $this->db->query("SELECT * from bagian b join department d on d.recid_department = b.recid_department where d.recid_department = $dp->recid_department and b.is_delete = '0' and b.indeks_hr != '' and b.recid_department = $dp->recid_department order by indeks_hr asc");
                      $mulai3 = $mulai;
                      $sampai3 = $sampai;
                      while ($mulai3 <= $sampai3) {
                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai3);
                        $tgl = $tgls->format("d");
                        $bulan = $tgls->format("m");
                        ${'totbtgl' . $tgl . $bulan} = 0;
                        // echo ${'totbtgl'.$tgl.$bulan};
                      ?>
                        <!-- <td><?php echo $mulai3; ?> </td> -->
                        <?php
                        $mulai3 = date('Y-m-d', strtotime('+1 days', strtotime($mulai3))); ?>
                      <?php }
                      foreach ($bagian->result() as $b) {
                        $tot_bag_tgl = 0;
                        $total_bagian = 0;
                      ?>
                        <tr>
                          <td><?php echo $no = $no + 1 ?></td>
                          <td><?php echo $b->indeks_hr ?></td>
                          <?php
                          $mulai2 = $mulai;
                          $sampai2 = $sampai;
                          while ($mulai2 <= $sampai2) {
                            $tgls = DateTime::createFromFormat("Y-m-d", $mulai2);
                            $tgl = $tgls->format("d");
                            $bulan = $tgls->format("m");
                            $tbt = $this->db->query("SELECT * from makan m join karyawan k on k.recid_karyawan = m.recid_karyawan join bagian b on b.recid_bag = k.recid_bag where b.recid_bag = $b->recid_bag and tgl_makan = '$mulai2' and k.sts_aktif = 'Aktif' and k.cci = 'Tidak' and spm = 'Tidak';");
                            $bag_tgl = $tbt->num_rows();
                            $tot_bag_tgl = $tot_bag_tgl + $bag_tgl;
                            ${'totbtgl' . $tgl . $bulan} = ${'totbtgl' . $tgl . $bulan} + $bag_tgl;
                            ${'totdptgl' . $tgl . $bulan} = ${'totdptgl' . $tgl . $bulan} + $bag_tgl;
                            ${'totctgl' . $tgl . $bulan} = ${'totctgl' . $tgl . $bulan} + $bag_tgl;
                          ?>
                            <td><?php echo $bag_tgl; ?> </td>
                            <?php
                            $mulai2 = date('Y-m-d', strtotime('+1 days', strtotime($mulai2))); ?>
                          <?php }
                          ?>
                          <td><?php echo $tot_bag_tgl; ?></td>
                        </tr>
                      <?php } ?>
                      <tr style="background-color:#bbf0c5; color:#000;">
                        <td colspan='2'>Total <?php echo $dp->nama_department ?></td>
                        <td style="display:none;"></td>
                        <?php
                        $mulai6 = $mulai;
                        $sampai6 = $sampai;
                        while ($mulai6 <= $sampai6) {
                          $tgls = DateTime::createFromFormat("Y-m-d", $mulai6);
                          $tgl = $tgls->format("d");
                          $bulan = $tgls->format("m");
                          $total_bagian = $total_bagian +  ${'totbtgl' . $tgl . $bulan};
                        ?>
                          <td><?php echo ${'totbtgl' . $tgl . $bulan} ?></td>
                          <?php
                          $mulai6 = date('Y-m-d', strtotime('+1 days', strtotime($mulai6))); ?>
                        <?php }
                        ?>
                        <td><?php echo $total_bagian ?></td>
                      </tr>
                    <?php }
                    ?>
                    <tr style="background-color:#b8def5; color:#000;">
                      <td colspan='2'>Total <?php echo $dg->dept_group ?></td>
                      <td style="display:none;"></td>
                      <?php
                      $mulai4 = $mulai;
                      $sampai4 = $sampai;
                      while ($mulai4 <= $sampai4) {
                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai4);
                        $tgl = $tgls->format("d");
                        $bulan = $tgls->format("m");
                        $total_dept = $total_dept +  ${'totdptgl' . $tgl . $bulan};
                      ?>
                        <td><?php echo ${'totdptgl' . $tgl . $bulan} ?></td>
                        <?php
                        $mulai4 = date('Y-m-d', strtotime('+1 days', strtotime($mulai4))); ?>
                      <?php }
                      ?>
                      <td><?php echo $total_dept ?></td>
                    </tr>
                  <?php }
                  ?>
                  <?php
                  $tipe = ["Tamu", "PKL", "Koprasi", "Outsource", "Jemputan"];
                  $no = 0;
                  for ($i = 0; $i < count($tipe); $i++) { ?>
                    <tr>
                      <td><?php echo $no = $no + 1 ?></td>
                      <td><?php echo ($tipe[$i] == "Jemputan") ? "Supir Jemputan" : $tipe[$i]; ?></td>
                      <?php
                      $mulai20 = $mulai;
                      $sampai20 = $sampai;
                      $tot_tamu_tgl = 0;
                      while ($mulai20 <= $sampai20) {
                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai20);
                        $tgl = $tgls->format("d");
                        $bulan = $tgls->format("m");
                        ${'tottamutgl' . $tgl . $bulan} = 0;
                        $tamu = $this->db->query("SELECT * from makan where kategori = '$tipe[$i]' and tgl_makan = '$mulai20'");
                        $tamu_tgl = $tamu->num_rows();
                        $tot_tamu_tgl = $tot_tamu_tgl + $tamu_tgl;
                        ${'tottamutgl' . $tgl . $bulan} = ${'tottamutgl' . $tgl . $bulan} + $tamu_tgl;
                        ${'totctgl' . $tgl . $bulan} = ${'totctgl' . $tgl . $bulan} + $tamu_tgl;
                      ?>
                        <td><?php echo $tamu_tgl; ?> </td>
                        <?php
                        $mulai20 = date('Y-m-d', strtotime('+1 days', strtotime($mulai20))); ?>
                      <?php }
                      ?>
                      <td><?php echo $tot_tamu_tgl; ?></td>
                    </tr>
                  <?php }
                  ?>
                  <tr style="background-color:#f57e76; color:#000;">
                    <td colspan='2'>Total Chitose</td>
                    <td style="display:none;"></td>
                    <?php
                    $mulai5 = $mulai;
                    $sampai5 = $sampai;
                    while ($mulai5 <= $sampai1) {
                      $tgls = DateTime::createFromFormat("Y-m-d", $mulai5);
                      $tgl = $tgls->format("d");
                      $bulan = $tgls->format("m");
                      $total_cint = $total_cint +  ${'totctgl' . $tgl . $bulan};
                    ?>
                      <td><?php echo ${'totctgl' . $tgl . $bulan} ?></td>
                      <?php
                      $mulai5 = date('Y-m-d', strtotime('+1 days', strtotime($mulai5))); ?>
                    <?php }
                    ?>
                    <td><?php echo $total_cint ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->


  <script>
    $(document).ready(function() {
      mulai = document.getElementById('mulai').value;
      sampai = document.getElementById('sampai').value;
      var table = $('#rekap_absen').DataTable({
        scrollY: "600px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        "bSort": false,
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excel',
          className: 'btn btn-primary btn-sm',
          title: 'Rekapitulasi Kupon Makan Kantin ' + mulai + ' s/d ' + sampai,
        }]
      });
    });
  </script>
<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Slip THR Karyawan <?php echo "Tahun " . $tahun ?></h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
          </div>
          <div class="clearfix"></div>

          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_upah2" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th rowspan='2'>NO</th>
                    <th rowspan='2'>GL ACC</th>
                    <th rowspan='2'>COST CENTER</th>
                    <th rowspan='2'>PAYGROP</th>
                    <th rowspan='2'>BAGIAN</th>
                    <th rowspan='2'>NIK</th>
                    <th rowspan='2'>NAMA</th>
                    <th rowspan='2'>MASA KERJA</th>
                    <th rowspan='2'>STATUS / GOL</th>
                    <th rowspan='2'>U.POKOK</th>
                    <th colspan='3'>Tunjangan</th>
                    <th rowspan='2'>U.GLOBAL</th>
                    <th colspan='4'>PREMI SHIFT</th>
                    <th>LBR1</th>
                    <th>LBR2</th>
                    <th>LBR3</th>
                    <th colspan='2'>JUMLAH LEMBUR</th>
                    <th rowspan='2'>PREMI HADIR</th>
                    <th rowspan='2'>TRANSPORT</th>
                    <th rowspan='2'>MAKAN</th>
                    <th rowspan='2'>ASURANSI</th>
                    <th colspan='5'>POTONGAN</th>
                    <th>BERSIH</th>
                  </tr>
                  <tr>
                    <th>JABATAN</th>
                    <th>JENPEK</th>
                    <th>MASKER</th>
                    <th>SHIFT 1&2</th>
                    <th>SHIFT 3</th>
                    <th>JML SHIFT</th>
                    <th>TOT PREMI SHIFT</th>
                    <th>JML JAM</th>
                    <th>JML JAM</th>
                    <th>JML JAM</th>
                    <th>JML JAM</th>
                    <th>JML UPAH</th>
                    <th>PENSIUN</th>
                    <th>ASTEK</th>
                    <th>SPMI</th>
                    <th>MANGKIR</th>
                    <th>PPH21</th>
                    <th>JML TERIMA</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 0;
                  foreach ($upah->result() as $d) { ?>
                    <tr>
                      <td><?php echo $no = $no + 1 ?></td>
                      <td><?php echo $d->gl_acc ?></td>
                      <td><?php echo $d->cost_center ?></td>
                      <td><?php echo $d->pay_group ?></td>
                      <td><?php echo $d->indeks_hr ?></td>
                      <td><?php echo $d->nik ?></td>
                      <td><?php echo $d->nama_karyawan ?></td>
                      <td><?php echo $d->masker ?></td>
                      <td><?php echo $d->sts_upah ?></td>
                      <td><?php echo $d->upokok ?></td>
                      <td><?php echo $d->tjbtn ?></td>
                      <td><?php echo $d->tjenpek ?></td>
                      <td><?php echo $d->rp_masker ?></td>
                      <td><?php echo $d->uglobal ?></td>
                      <td><?php echo $d->slbr1 ?></td>
                      <td><?php echo $d->slbr2 ?></td>
                      <td><?php echo ($d->slbr1 + $d->slbr2) ?></td>
                      <td><?php echo (($d->slbr1 * $d->rp_slbr1) + ($d->slbr2 * $d->rp_slbr2)) ?></td>
                      <td><?php echo $d->lbr1 ?></td>
                      <td><?php echo $d->lbr2 ?></td>
                      <td><?php echo $d->lbr3 ?></td>
                      <td><?php echo ($d->lbr1 + $d->lbr2 + $d->lbr3) ?></td>
                      <td><?php echo $d->tot_rp_lbr ?></td>
                      <td><?php echo round($d->premi) ?></td>
                      <td><?php echo $d->uang_transport ?></td>
                      <td><?php echo $d->uang_makan ?></td>
                      <td><?php echo round($d->asuransi) ?></td>
                      <td><?php echo round($d->pensiun) ?></td>
                      <td><?php echo round($d->astek) ?></td>
                      <td><?php echo round($d->spmi) ?></td>
                      <td><?php echo round($d->mangkir) ?></td>
                      <td><?php echo round(($d->pph21_1 + $d->pph21_2 + $d->pph21_3)) ?></td>
                      <td><?php echo round($d->jml_terima) ?></td>
                    </tr>
                  <?php }
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
</div>


<script>
  $(document).ready(function() {
    $('#t_upah2').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy',
        {
          extend: 'excel',
          title: 'Report Upah Master <?php echo "Tahun " . $tahun ?>',
        },
      ]
    });
  });
</script>
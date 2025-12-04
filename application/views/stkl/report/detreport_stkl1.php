<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h5> Report Budget Lembur Bulan <?php echo $bulan." ".$tahun ?> </h5>
         <h4> Detail Realisasi Budget Lembur Terhadap Upah Karyawan </h4>
      </div>

      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h4>Periode <?php echo $tgl_mulai ?> s/d <?php echo $tgl_akhir ?></h4>
            <ul class="nav navbar-right panel_toolbox">
            </ul>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <table id="tr_hr" class="table table-striped table-bordered" width="100%">
              <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Bagian</th>
                    <th style="text-align: center">Nama Karyawan</th>
                    <th style="text-align: center">Jml Jam Lembur</th>
                    <th style="text-align: center">Jml Upah Lembur</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $no =0;
                    $tahun = substr($tgl_mulai, 0,4);
                    $rp_masker = $this->m_hris->param_upah_id(1);
                    foreach ($rp_masker->result() as $r) {
                      $uph_masker = $r->nilai;
                    }

                        $plbr = $this->m_hris->param_upah_id(10);
                    foreach ($plbr->result() as $r) {
                      $bagi_lembur = $r->nilai;
                    }

                    $rupjs = $this->m_hris->param_upah_id(38);
                    foreach ($rupjs->result() as $rp) {
                      $rata_upah = $rp->nilai;
                    }


                    $non_staff_cint = 0;
                    $karyawan_lembur_cint = 0;
                    $totjam_cint = 0;
                    $totupah_cint = 0;
                    $budget_cint = 0;
                    foreach ($pay_group->result() as $p) {
                            $non_staff_pg = 0;
                            $karyawan_lembur_pg = 0;
                            $totjam_pg = 0;
                            $totupah_pg = 0;
                            $budget_pg = 0;
                        $struktur = $this->m_lembur->struktur_mb_report($tahun, $p->pay_group);
                        foreach ($struktur->result() as $s) {
                            $bagian = $this->m_lembur->bagian_mb_report($tahun, $s->recid_struktur);
                            $non_staff_str = 0;
                            $karyawan_lembur_str = 0;
                            $totjam_str = 0;
                            $totupah_str = 0;
                            $budget_str = 0;
                            foreach ($bagian->result() as $b) {
                              $budget = $this->m_lembur->budget_bag_bln_tahun($b->recid_bag, $bulan, $tahun);
                              foreach ($budget->result() as $bg) {
                                $budget_bag = $bg->jml_jam;
                              }
                              $budget_str = $budget_str + $budget_bag;
                              $totjam_bag = 0;
                              $non_staff = $this->m_lembur->karyawan_nonstaff_bagian($b->recid_bag);
                              $non_staff_str = $non_staff_str + $non_staff->num_rows();
                              $karyawan_lembur = $this->m_lembur->karyawan_mb_report($tgl_mulai, $tgl_akhir, $b->recid_bag);
                              $karyawan_lembur_str = $karyawan_lembur_str + $karyawan_lembur->num_rows();
                              $totupah_bag = 0;
                              $nonya = 0;
                              foreach ($karyawan_lembur->result() as $kl) {
                                $upah_lembur = 0;
                                  $totjam_bag = $totjam_bag + $kl->total_jam;
                                  $uph_pokok = $kl->gapok;
                                  $t_jbtn = $kl->t_jabatan;
                                  $t_prestasi = $kl->t_prestasi;
                                  $t_jenpek = $kl->t_jen_pek;
                                      $diff  = date_diff(date_create($kl->tgl_m_kerja), date_create());
                                      $masker_tahun = $diff->format('%y');
                                      // echo "masa kerja : $masker_tahun<br>";
                                  $t_masker = $masker_tahun * $uph_masker;
                                  $global = $uph_pokok + $t_jbtn+ $t_prestasi + $t_jenpek + $t_masker;

                                   $uph_lbr1 = (round($global / $bagi_lembur)) * 1.5;
                                   $lbr1 = round($uph_lbr1 * $kl->lembur1);
                                   $uph_lbr2 = (round($global / $bagi_lembur)) * 2;
                                   $lbr2 = round($uph_lbr2 * $kl->lembur2);
                                   $uph_lbr3 = (round($global / $bagi_lembur)) * 3;
                                   $lbr3 = round($uph_lbr3 * $kl->lembur3);
                                   $upah_lembur = $lbr1 + $lbr2 + $lbr3;
                                  //  echo $kl->nama_karyawan." : ".number_format($upah_lembur)."<br>";
                                  $totupah_bag = $totupah_bag + $upah_lembur;

                                  ?>
                                  <tr>
                                  <td style="text-align: center"><?php echo $nonya = $nonya+1 ?></td>
                                  <td><?php echo $b->indeks_hr?></td>
                                  <td style="text-align: center"><?php echo $kl->nama_karyawan ?></td>
                                  <td style="text-align: center"><?php echo $kl->total_jam ?></td>
                                  <td style="text-align: center"><?php echo $upah_lembur ?></td>
                                  </tr>

                                <?php 
                                }
                              } ?> <!-- punya bagian -->
                        <?php 
                            $non_staff_pg = $non_staff_pg + $non_staff_str;
                            $karyawan_lembur_pg = $karyawan_lembur_pg + $karyawan_lembur_str;
                            $totjam_pg = $totjam_pg + $totjam_str;
                            $totupah_pg = $totupah_pg + $totupah_str;
                            $budget_pg = $budget_pg + $budget_str;
                        }?>
                    <?php 
                       $non_staff_cint = $non_staff_cint + $non_staff_pg;
                       $karyawan_lembur_cint = $karyawan_lembur_cint + $karyawan_lembur_pg;
                       $totjam_cint = $totjam_cint + $totjam_pg;
                       $totupah_cint = $totupah_cint + $totupah_pg;
                       $budget_cint = $budget_cint + $budget_pg;  
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<footer>
  <div class="pull-right">
    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content
</div>
</div> -->

<script>
    //  var table = $('#t_stkl').DataTable();
</script>
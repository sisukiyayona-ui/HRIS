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
                  <h3>Report Detail Absensi </h3>
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
                          <table class="table table-bordered" id="rekap_absen" border="1">
                              <thead>
                                  <tr>
                                      <td rowspan="2">No</td>
                                      <td rowspan="2">NIK</td>
                                      <td rowspan="2">Nama Karyawan</td>
                                      <td rowspan="2">Bagian</td>
                                      <td rowspan="2">Jabatan</td>
                                      <td rowspan="2">Golongan</td>
                                      <td colspan="<?php echo $jml_hari ?>">Tanggal</td>
                                      <td rowspan="2">Total Hadir</td>
                                  </tr>
                                  <tr>
                                      <?php
                                        $mulai1 = $mulai;
                                        $sampai1 = $sampai;
                                        while ($mulai1 <= $sampai1) { ?>
                                          <td><?php echo $mulai1; ?> </td>
                                          <?php
                                            $mulai1 = date('Y-m-d', strtotime('+1 days', strtotime($mulai1))); ?>
                                      <?php }
                                        ?>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $total_cint = 0;
                                    $mulai9 = $mulai;
                                    $sampai9 = $sampai;
                                    while ($mulai9 <= $sampai9) {
                                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai9);
                                        $tgl = $tgls->format("d");
                                        $bulan = $tgls->format("m");
                                        ${'totctgl' . $tgl . $bulan} = 0;
                                        // echo ${'totbtgl'.$tgl.$bulan};
                                        $mulai9 = date('Y-m-d', strtotime('+1 days', strtotime($mulai9)));
                                    }
                                    for ($dg = 0; $dg < count($fdivisi); $dg++)  /*--- loop dept group ---*/ {
                                        //department by bagian
                                        if ($fdepartment != '') {
                                            $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' $fdepartment order by nama_department asc");
                                        } else {
                                            $dept = $this->db->query("SELECT * from department d where dept_group = '$fdivisi[$dg]' and d.is_delete = '0' order by nama_department asc");
                                        }
                                        $total_div = 0;
                                        $mulai7 = $mulai;
                                        $sampai7 = $sampai;
                                        while ($mulai7 <= $sampai7) {
                                            $tgls = DateTime::createFromFormat("Y-m-d", $mulai7);
                                            $tgl = $tgls->format("d");
                                            $bulan = $tgls->format("m");
                                            ${'totdivtgl' . $tgl . $bulan} = 0;
                                            // echo ${'totbtgl'.$tgl.$bulan};
                                            $mulai7 = date('Y-m-d', strtotime('+1 days', strtotime($mulai7)));
                                        }
                                        foreach ($dept->result() as $dp) { ?>
                                          <?php $role = $this->session->userdata('role_id');
                                            $usr = $this->session->userdata('kar_id');
                                            if ($role == 30) {
                                                $bagian = $this->db->query("SELECT b.recid_bag, indeks_hr
                                          from master_absen.admin_bagian a
                                          JOIN hris.bagian b on b.recid_bag = a.recid_bag
                                          join hris.department d on d.recid_department = b.recid_department
                                          where recid_karyawan = '$usr' order by indeks_hr asc");
                                            } else {
                                                $bagian = $this->db->query("SELECT * from bagian b join department d on d.recid_department = b.recid_department where d.recid_department = $dp->recid_department and b.is_delete = '0' and b.indeks_hr != '' $fbagian order by indeks_hr asc");
                                            }
                                            $total_dept = 0;
                                            $mulai5 = $mulai;
                                            $sampai5 = $sampai;
                                            while ($mulai5 <= $sampai5) {
                                                $tgls = DateTime::createFromFormat("Y-m-d", $mulai5);
                                                $tgl = $tgls->format("d");
                                                $bulan = $tgls->format("m");
                                                ${'totdtgl' . $tgl . $bulan} = 0;
                                                // echo ${'totbtgl'.$tgl.$bulan};
                                                $mulai5 = date('Y-m-d', strtotime('+1 days', strtotime($mulai5)));
                                            }
                                            foreach ($bagian->result() as $b) {
                                                //karyawan by bagian
                                                if ($fkaryawan != '') {
                                                    $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak'  and tc = '0' $fkaryawan order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                                                } else {
                                                    $karyawan = $this->db->query("SELECT * from karyawan k join bagian b on b.recid_bag = k.recid_bag join jabatan j on j.recid_jbtn = k.recid_jbtn join golongan g on g.recid_golongan = k.recid_golongan  where b.recid_bag = $b->recid_bag and k.sts_aktif = 'Aktif' and k.cci = 'Tidak'  and tc = '0' order by b.indeks_hr, j.indeks_jabatan, k.nama_karyawan");
                                                }
                                                if ($karyawan->num_rows() == 0) {
                                                    continue;
                                                } else {
                                                    $no_k = 0;
                                                    $total_bagian = 0;
                                                    $mulai3 = $mulai;
                                                    $sampai3 = $sampai;
                                                    while ($mulai3 <= $sampai3) {
                                                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai3);
                                                        $tgl = $tgls->format("d");
                                                        $bulan = $tgls->format("m");
                                                        ${'totbtgl' . $tgl . $bulan} = 0;
                                                        // echo ${'totbtgl'.$tgl.$bulan};
                                                        $mulai3 = date('Y-m-d', strtotime('+1 days', strtotime($mulai3)));
                                                    }

                                                    foreach ($karyawan->result() as $k) {
                                                        $total_k = 0;   /* total makan per karyawan */
                                            ?>
                                                      <tr>
                                                          <td><?php echo $no_k = $no_k + 1; ?></td>
                                                          <td><?php echo $k->nik ?></td>
                                                          <td><?php echo $k->nama_karyawan ?></td>
                                                          <td><?php echo $k->indeks_hr ?></td>
                                                          <td><?php echo $k->indeks_jabatan ?></td>
                                                          <td><?php echo $k->nama_golongan ?></td>
                                                          <?php
                                                            $mulai2 = $mulai;
                                                            $sampai2 = $sampai;
                                                            // echo "recid karyawan " . $k->recid_karyawan;
                                                            while ($mulai2 <= $sampai2) {
                                                                $cek_absen = $this->db->query("SELECT * FROM master_absen.hadir_barcode h join master_absen.jenis_absen ja on h.status = ja.recid_jenisabsen join hris.karyawan k on k.recid_karyawan = h.recid_karyawan where k.recid_karyawan = $k->recid_karyawan and tanggal = '$mulai2' and is_closed = '0'");
                                                                /*  $cek_makan = $this->db->query("SELECT * from makan where recid_karyawan = $k->recid_karyawan and tgl_makan = '$mulai2'");*/
                                                                $tgls = DateTime::createFromFormat("Y-m-d", $mulai2);
                                                                $tgl = $tgls->format("d");
                                                                $bulan = $tgls->format("m");
                                                                // ${'totbtgl' . $tgl . $bulan} = 0;
                                                                if ($cek_absen->num_rows() > 0) {
                                                                    foreach ($cek_absen->result() as $ca) {
                                                                        $hdr = $ca->jenis;
                                                                        $absen_group = $ca->absen_group;
                                                                        $status = $ca->recid_jenisabsen;
                                                                    }
                                                                    if ($absen_group == "Hadir" and (($status != 24 and $status != '28' and $status != '31' and $status != '32' and $status != '33'))) {
                                                                        $total_k = $total_k + 1;
                                                                        ${'totbtgl' . $tgl . $bulan} = ${'totbtgl' . $tgl . $bulan} + 1;
                                                                        ${'totdtgl' . $tgl . $bulan} = ${'totdtgl' . $tgl . $bulan} + 1;
                                                                        ${'totdivtgl' . $tgl . $bulan} = ${'totdivtgl' . $tgl . $bulan} + 1;
                                                                        ${'totctgl' . $tgl . $bulan} = ${'totctgl' . $tgl . $bulan} + 1;
                                                                    }
                                                                } else {
                                                                    $hdr = "-";
                                                                    $total_k = $total_k;
                                                                }
                                                            ?>
                                                              <td><?php echo $hdr ?></td>
                                                              <?php
                                                                $mulai2 = date('Y-m-d', strtotime('+1 days', strtotime($mulai2))); ?>
                                                          <?php }
                                                            ?>
                                                          <td><?php echo $total_k ?></td>
                                                      </tr>
                                              <?php } /* ---- Loop Karyawan--- */
                                                }/* ------------ else tidak 0 ------------- */
                                                ?>
                                              <tr style="background-color:#f7e4b0; color:#000;">
                                                  <td colspan="6">Total Bagian <?php echo $b->indeks_hr ?></td>
                                                  <td style="display:none"></td>
                                                  <td style="display:none"></td>
                                                  <td style="display:none"></td>
                                                  <td style="display:none"></td>
                                                  <td style="display:none"></td>
                                                  <?php
                                                    $mulai4 = $mulai;
                                                    $sampai4 = $sampai;
                                                    while ($mulai4 <= $sampai4) {
                                                        $tgls = DateTime::createFromFormat("Y-m-d", $mulai4);
                                                        $tgl = $tgls->format("d");
                                                        $bulan = $tgls->format("m");
                                                        $total_bagian = $total_bagian +  ${'totbtgl' . $tgl . $bulan};
                                                    ?>
                                                      <td><?php echo ${'totbtgl' . $tgl . $bulan} ?></td>
                                                  <?php $mulai4 = date('Y-m-d', strtotime('+1 days', strtotime($mulai4)));
                                                    }
                                                    ?>
                                                  <td><?php echo $total_bagian ?></td>
                                              </tr>
                                          <?php  }/* --------- loop bagian --------- */ ?>
                                          <tr style="background-color:#bbf0c5; color:#000;">
                                              <td colspan="6">Total Dept <?php echo $dp->nama_department ?></td>
                                              <td style="display:none"></td>
                                              <td style="display:none"></td>
                                              <td style="display:none"></td>
                                              <td style="display:none"></td>
                                              <td style="display:none"></td>
                                              <?php
                                                $mulai6 = $mulai;
                                                $sampai6 = $sampai;
                                                while ($mulai6 <= $sampai6) {
                                                    $tgls = DateTime::createFromFormat("Y-m-d", $mulai6);
                                                    $tgl = $tgls->format("d");
                                                    $bulan = $tgls->format("m");
                                                    $total_dept = $total_dept +  ${'totdtgl' . $tgl . $bulan}; ?>
                                                  <td><?php echo ${'totdtgl' . $tgl . $bulan} ?></td>
                                              <?php $mulai6 = date('Y-m-d', strtotime('+1 days', strtotime($mulai6)));
                                                }
                                                ?>
                                              <td><?php echo $total_dept ?></td>
                                          </tr>
                                      <?php } /* -------- foreach dept ------------- */ ?>
                                      <tr style="background-color:#b8def5; color:#000;">
                                          <td colspan="6">Total Divisi <?php echo $fdivisi[$dg] ?></td>
                                          <td style="display:none"></td>
                                          <td style="display:none"></td>
                                          <td style="display:none"></td>
                                          <td style="display:none"></td>
                                          <td style="display:none"></td>
                                          <?php
                                            $mulai8 = $mulai;
                                            $sampai8 = $sampai;
                                            while ($mulai8 <= $sampai8) {
                                                $tgls = DateTime::createFromFormat("Y-m-d", $mulai8);
                                                $tgl = $tgls->format("d");
                                                $bulan = $tgls->format("m");
                                                $total_div = $total_div +  ${'totdivtgl' . $tgl . $bulan}; ?>
                                              <td><?php echo ${'totdivtgl' . $tgl . $bulan} ?></td>
                                          <?php $mulai8 = date('Y-m-d', strtotime('+1 days', strtotime($mulai8)));
                                            } ?>
                                          <td><?php echo $total_div ?></td>
                                      </tr>
                                  <?php } /* --------- loop dept group ------ */ ?>
                                  <tr style="background-color:#f57e76; color:#000;">
                                      <td colspan="6">Total Chitose</td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <td style="display:none"></td>
                                      <?php
                                        $mulai10 = $mulai;
                                        $sampai10 = $sampai;
                                        while ($mulai10 <= $sampai10) {
                                            $tgls = DateTime::createFromFormat("Y-m-d", $mulai10);
                                            $tgl = $tgls->format("d");
                                            $bulan = $tgls->format("m");
                                            $total_cint = $total_cint +  ${'totctgl' . $tgl . $bulan}; ?>
                                          <td><?php echo ${'totctgl' . $tgl . $bulan} ?></td>
                                      <?php $mulai10 = date('Y-m-d', strtotime('+1 days', strtotime($mulai10)));
                                        } ?>
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
                  title: 'Rekapitulasi Kehadiran Karyawan ' + mulai + ' s/d ' + sampai,
              }]
          });
      });
  </script>
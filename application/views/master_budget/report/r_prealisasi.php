
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Report</h3>
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
            <h2><a href="<?php echo base_url()?>Karyawan/r_realisasi"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Realisasi Lembur Periode <?php echo $newDate = date("d M Y", strtotime($tgl_awal)); ?> s/d <?php echo $newDate = date("d M Y", strtotime($tgl_akhir)); ?> </h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="tr_hr" class="table table-striped table-bordered" width="100%">
              <thead>
                <tr>
                 <th><center>Bagian/Department</center></th>
                 <th><center>Tanggal Lembur</center></th>
                 <th><center>Kategori</center></th>
                 <th><center>Pengajuan</center></th>
                 <th><center>Realisasi</center></th>
                 <th><center>Pekerjaan</center></th>
                 <th><center>Alasan Over</center></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                  $bagian = $this->db->query("SELECT b.nama_bag, b.indeks_hr, b.recid_bag, pl.kategori from realisasi_lembur rl 
                            join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                            join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                            join bagian b on b.recid_bag = mb.recid_bag 
                            where pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir' group by b.recid_bag")->result();
                  foreach ($bagian as $bagian) { ?>
                     <!--   <tr>
                        <td><center><?php echo $bagian->nama_bag ?></center></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr> -->
                      <?php  $realisasi = $this->db->query("SELECT rl.*, pl.tgl_lembur, pl.total_jam, pl.keterangan, b.nama_bag, rl.note as notes, pl.kategori 
                                          from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                                          join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                                          join bagian b on b.recid_bag = mb.recid_bag
                                          where b.recid_bag = '$bagian->recid_bag' and pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir'")->result();
                      foreach ($realisasi as $realisasi) { ?>
                        <tr>
                          <td><center><?php echo $bagian->indeks_hr ?> (<?php echo $bagian->nama_bag ?>)</center></td>
                          <td><center><?php echo $newDate = date("d M Y", strtotime($realisasi->tgl_lembur)); ?></center></td>
                          <td><center>
                            <?php if($realisasi->kategori == 'Kgagal'){echo "Komponen Kegagalan";}
                            else if($realisasi->kategori == 'Kterlambat'){echo "Komponen Terlambat Pengiriman";}
                            else if($realisasi->kategori == 'Ekirim'){echo "Pengiriman Barang";}
                            else if($realisasi->kategori == 'Ddinas'){echo "Dinas Driver";}
                            else{echo $realisasi->kategori; }?>
                          </center></td>
                          <td><center><?php echo round($realisasi->total_jam) ?></center></td>
                          <td><center><?php echo round($realisasi->realisasi_jam) ?></center></td>
                          <td><center><?php echo $realisasi->pekerjaan ?></center></td>
                          <td><center><?php echo $realisasi->alasan_over ?></center></td>
                        </tr>
                      <?php  } ?> <!-- looping data realisasi by bagian -->
                      <!-- Jumlah Per Bagian -->
                      <tr style="background-color: #5ea7e6; color: #fff">
                        <th><center>TOTAL <?php echo $bagian->indeks_hr ?> (<?php echo $bagian->nama_bag ?>)</center></th>
                        <?php 
                            $tbagian = $this->db->query("SELECT count(recid_lembur) as hari, sum(realisasi_jam) as jumlah, sum(total_jam) as total
                                        from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                                        join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                                        join bagian b on b.recid_bag = mb.recid_bag
                                        where b.recid_bag = '$bagian->recid_bag' and pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir' ")->result();
                            foreach ($tbagian as $tbagian) {}
                        ?>
                        <th><center><?php echo $tbagian->hari." hari"; ?></center></th>
                        <th></th>
                        <th><center><?php echo round($tbagian->total)." jam"; ?></center></th>
                        <th><center><?php echo round($tbagian->jumlah)." jam"; ?></center></th>
                        <th></th>
                        <th></th>
                      </tr>
                  <?php } ?> <!-- looping bagian -->
                  <!-- Jumlah Grand Total -->
                  <tr style="background-color:#164c7b; color: #fff">
                    <th><center>GRAND TOTAL </center></th>
                    <?php 
                    $gbagian = $this->db->query("SELECT count(recid_lembur) as hari, sum(realisasi_jam) as jumlah, sum(total_jam) as total
                      from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                      join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                      join bagian b on b.recid_bag = mb.recid_bag
                      where pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir'")->result();
                    foreach ($gbagian as $gbagian) {}
                      ?>
                    <th><center><?php echo $gbagian->hari." hari"; ?></center></th>
                    <th></th>
                    <th><center><?php echo round($gbagian->total)." jam"; ?></center></th>
                    <th><center><?php echo round($gbagian->jumlah)." jam"; ?></center></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tbody>
          
                </table>


              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



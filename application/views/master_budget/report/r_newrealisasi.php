
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
              <a id="downloadLink" onclick="exportF(this)"><button type="button" class="btn btn-primary">Export to excel</button></a>

            <table id="table_expo" class="table table-striped table-bordered" width="100%">
              <thead>
                <tr>
                 <th><center>Bagian/Department</center></th>
                 <th><center>Tanggal Lembur</center></th>
                 <th><center>Kategori</center></th>
                 <th><center>Alasan Over</center></th>
                 <th><center>Pengajuan Jam</center></th>
                 <th><center>Realisasi Jam</center></th>
                 <th><center>Pengajuan Orang</center></th>
                 <th><center>Realisasi Orang</center></th>
                 <th><center>Pekerjaan</center></th>
                 <th><center>Target</center></th>
                 <th><center>Hasil</center></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                  $bagian = $this->db->query("SELECT distinct dl.recid_plembur, b.nama_bag, b.indeks_hr, b.recid_bag, pl.kategori , pl.tgl_lembur, pl.total_jam, pl.jml_orang, pl.keterangan, b.nama_bag, rl.note as notes, pl.recid_plembur, pl.alasan_over, rl.realisasi_jam, rl.realisasi_orang
                            from realisasi_lembur rl 
                            join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                            Join detail_lembur dl on dl.recid_plembur = pl.recid_plembur
                            join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                            join bagian b on b.recid_bag = mb.recid_bag 
                            where pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir' and rl.realisasi_jam is not null $role")->result();
                  foreach ($bagian as $bagian) { 
                            $recid_plembur = $bagian->recid_plembur;
                            $itung = $this->db->query("SELECT * from detail_lembur where recid_plembur = '$recid_plembur'");
                            $merges = $itung->num_rows();
                            // echo $merges;
                   ?>
                        <tr>
                            <td rowspan="<?php echo $merges?>"><center><?php echo $bagian->indeks_hr ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo $newDate = date("d M Y", strtotime($bagian->tgl_lembur)); ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center>
                            <?php if($bagian->kategori == 'Kgagal'){echo "Komponen Kegagalan";}
                            else if($bagian->kategori == 'Kterlambat'){echo "Komponen Terlambat Pengiriman";}
                            else if($bagian->kategori == 'Ekirim'){echo "Pengiriman Barang";}
                            else if($bagian->kategori == 'Ddinas'){echo "Dinas Driver";}
                            else{echo $bagian->kategori; }?>
                            </center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo $bagian->alasan_over ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo round($bagian->total_jam) ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo round($bagian->realisasi_jam) ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo round($bagian->jml_orang) ?></center></td>
                            <td rowspan="<?php echo $merges?>"><center><?php echo round($bagian->realisasi_orang) ?></center></td>

                             <?php
                                $det_lembur = $this->db->query("SELECT dl.* from detail_lembur dl right join pengajuan_lembur pl on pl.recid_plembur = dl.recid_plembur where pl.recid_plembur = '$recid_plembur'");
                                $i=0;
                               foreach ($det_lembur->result() as $dl) { 
                                    if($i >= 1)
                                    {
                                        echo "</tr><tr>";
                                    }
                                ?>
                                    <td><center><?php echo $dl->pekerjaan ?></center></td>
                                    <td><center><?php echo $dl->target_kerja ?></center></td>
                                    <td><center><?php echo $dl->hasil ?></center></td>
                                    <?php $i++; ?>
                                </tr>
                                <?php } ?>
                      <!-- Jumlah Per Bagian -->
                     <!--   <tr style="background-color: #5ea7e6; color: #fff">
                        <td><center>TOTAL <?php echo $bagian->nama_bag ?> </center></td>
                        <?php 
                            $tbagian = $this->db->query("SELECT count(recid_lembur) as hari, sum(realisasi_jam) as jumlah, sum(total_jam) as total, sum(jml_orang) as jml_orang, sum(realisasi_orang) as realisasi_orang
                                        from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur 
                                        join master_budget mb on pl.recid_mbl = mb.recid_mbl 
                                        join detail_lembur dl on dl.recid_plembur = pl.recid_plembur
                                        join bagian b on b.recid_bag = mb.recid_bag
                                        where b.recid_bag = '$bagian->recid_bag' and pl.tgl_lembur between '$tgl_awal' and '$tgl_akhir' and rl.realisasi_jam is not null ")->result();
                            foreach ($tbagian as $tbagian) {}
                        ?>
                        <td><center><?php echo $tbagian->hari." kali"; ?></center></td>
                        <td></td>
                        <td></td>
                        <td><center><?php echo round($tbagian->total)." jam"; ?></center></td>
                        <td><center><?php echo round($tbagian->jumlah)." jam"; ?></center></td>
                        <td><center><?php echo round($tbagian->jml_orang)." orang"; ?></center></td>
                        <td><center><?php echo round($tbagian->realisasi_orang)." orang"; ?></center></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>  -->
                  <?php } ?> <!-- looping bagian -->
                  <!-- Jumlah Grand Total -->
                 <!--  <tr style="background-color:#164c7b; color: #fff">
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
                  </tr> -->
                </tbody>
          
                </table>


              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


<script type="text/javascript">
    function exportF(elem) {
      var table = document.getElementById("table_expo");
      var html = table.outerHTML;
  var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
  elem.setAttribute("href", url);
  elem.setAttribute("download", "export.xls"); // Choose the file name
  return false;
}
</script>
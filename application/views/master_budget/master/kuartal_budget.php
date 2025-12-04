
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
            <h2><a href="<?php echo base_url()?>Karyawan/dash"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Master Budget Kuartal <?php
            // $tahun = $tahun;
            echo $tahun; ?></h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php
            $month = array('Januari', 'Februari', 'Maret', 'Kuartal I', 'April', 'Mei', 'Juni', 'Kuartal II', 'Juli', 'Agustus', 'September','Kuartal III', 'Oktober', 'November', 'Desember', 'Kuartal IV');
            ?>
             
            <table id="tr_hr" class="table table-striped table-bordered" width="100%" style="color: #000">
              <thead>
                <tr>
                 <th rowspan="2"><center>Bagian / Department</center></th> 
                 <?php for ($i=0;$i < count($month); $i++) {?> 
                  <th colspan="3"><center><?php echo $month[$i] ?></center></th>  
                <?php } ?>
                <th colspan="3"><center>Total</center></th>
              </tr>
              <tr>
                <?php for ($i=0;$i < count($month)+1; $i++) {?> 
                  <th style="background-color: #d4e7bf; color: #000">Budget</th>
                  <th style="background-color: #abe0ee ; color: #000">Realisasi</th>
                  <th style="background-color: #d6b9d3; color: #000">Pencapaian</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
             <?php  
             $pay_group = $this->db->query("SELECT DISTINCT pay_group from bagian b join master_budget mb on b.recid_bag = mb.recid_bag where mb.tahun =  '$tahun' order by b.pay_group asc")->result();
             foreach ($pay_group as $pay) {?>
              <tr style="background-color: #e9edee; color: #000">
                <td><b><?php echo $pay->pay_group ?></b></td>
                <?php for($i=0; $i<=50;$i++){ ?>
                  <td></td>
                <?php } ?>
              </tr>
              <?php 
               $bagian = $this->db->query("SELECT b.nama_bag, indeks_hr, mbl.recid_bag from pengajuan_lembur pl right join master_budget mbl on pl.recid_mbl = mbl.recid_mbl join bagian b on b.recid_bag = mbl.recid_bag left join realisasi_lembur rl on rl.recid_plembur = pl.recid_plembur where pay_group = '$pay->pay_group' and mbl.tahun = '$tahun' group by nama_bag order by indeks_hr asc")->result();
                foreach ($bagian as $dbagian) { ?>
                  <tr><td><?php echo $dbagian->indeks_hr?> (<?php echo $dbagian->nama_bag?>)</td>
                    <!-- kelipatan 4 -->
                    <?php 
                      for($x=0; $x<15;$x++){
                        if((($x+4) == 3 || ($x+4) == 7 || ($x+4) == 11 || ($x+4) == 15 || ($x+4) == 19  ) and $x != 0){ 
                            $moon = substr($month[$x], 8);
                            $budget_k = $this->db->query("SELECT sum(jml_jam) as jml_jam from master_budget where tahun  = '$tahun' and recid_bag = '$dbagian->recid_bag' and kuartal = '$moon'")->result(); 
                            foreach ($budget_k as $dbudgetk) { 
                              $mbudgetk = $dbudgetk->jml_jam;
                              $mbudgetk = round($mbudgetk,2);
                            }
                            $real_k = $this->db->query("SELECT sum(realisasi_jam) as total from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl where mb.recid_bag = '$dbagian->recid_bag' and mb.kuartal = '$moon' and mb.tahun =  '$tahun' ")->result();
                            foreach ($real_k as $dreal) {
                              if($dreal->total == ''){
                                $total = 0;
                              }else{
                                $total = $dreal->total;
                                $total = round($total,2);
                              }
                            }
                            $selisihk =  $dbudgetk->jml_jam - $total;
                            if($mbudgetk == 0){
                              $selisihk = "0";
                            }else{
                              $selisihk = ($total / $mbudgetk) * 100;
                              $selisihk = round($selisihk);
                            }

                          ?>
                          <td style="background-color: #d4e7bf; color: #000"><center><?php if($selisihk > 100 or ($mbudgetk == '0' and $total > '0')){echo "<font color= 'red'><b>";echo number_format($mbudgetk, 2, ',', '.');"</b>";}else{ echo number_format($mbudgetk, 2, ',', '.');} ?></center></td>
                          <td style="background-color: #abe0ee; color: #000"><center><?php if($selisihk > 100  or ($mbudgetk == '0' and $total > '0')){echo "<font color= 'red'><b>";echo number_format($total, 2, ',', '.');"</b>";}else{ echo number_format($total, 2, ',', '.');} ?></center></td>
                          <td style="background-color: #d6b9d3 ; color: #000"><center><?php if($selisihk > 100  or ($mbudgetk == '0' and $total > '0')){echo "<font color= 'red'><b>$selisihk %</b>";}else{ echo $selisihk."%";} ?></center></td>
                        <?php }else{ 
                           $budget = $this->db->query("SELECT * from master_budget where tahun  = '$tahun' and recid_bag = '$dbagian->recid_bag' and bulan = '$month[$x]'")->result();
                           foreach ($budget as $dbudget) { 
                            $mbudget = $dbudget->jml_jam;
                            $mbudget = round($mbudget,2);

                            $real = $this->db->query("SELECT sum(realisasi_jam) as total from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur where pl.recid_mbl = '$dbudget->recid_mbl'")->result();
                            foreach ($real as $dreal) {
                              if($dreal->total == ''){
                                $total = 0;
                              }else{
                                $total = $dreal->total;
                                $total = round($total,2);
                              }
                            }
                            $selisih =  $dbudget->jml_jam - $total;
                            if($mbudget == 0){
                              $selisih = "0";
                            }else{
                              $selisih = ($total / $mbudget) * 100;
                              $selisih = round($selisih);
                            }
                          }
                          ?>
                          <td style="background-color: #d4e7bf; color: #000"><center><?php if($selisih > 100 or ($mbudget == '0' and $total > '0')){echo "<font color= 'red'><b>";echo number_format($mbudget, 2, ',', '.');"</b>";}else{ echo number_format($mbudget, 2, ',', '.');;} ?></center></td>
                          <td style="background-color: #abe0ee; color: #000"><center><?php if($selisih > 100  or ($mbudget == '0' and $total > '0')){echo "<font color= 'red'><b>";echo number_format($total, 2, ',', '.');"</b>";}else{ echo number_format($total, 2, ',', '.');} ?></center></td>
                          <td style="background-color: #d6b9d3 ; color: #000"><center><?php if($selisih > 100  or ($mbudget == '0' and $total > '0')){echo "<font color= 'red'><b>$selisih %</b>";}else{ echo $selisih."%";} ?></center></td>
                        <?php }
                      }
                    ?>
                    <!-- kelipatan 4 end -->
                   <!-- Kuartal IV -->
                   <?php
                   $budget_4 = $this->db->query("SELECT sum(jml_jam) as jml_jam from master_budget where tahun  = '$tahun' and recid_bag = '$dbagian->recid_bag' and kuartal = 'IV'")->result(); 
                   foreach ($budget_4 as $dbudget4) { 
                    $mbudget4 = $dbudget4->jml_jam;
                    $mbudget4 = round($mbudget4,2);
                  }
                  $real_4 = $this->db->query("SELECT sum(realisasi_jam) as total from realisasi_lembur rl join pengajuan_lembur pl on rl.recid_plembur = pl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl where mb.recid_bag = '$dbagian->recid_bag' and mb.kuartal = 'IV'")->result();
                  foreach ($real_4 as $dreal) {
                    if($dreal->total == ''){
                      $total = 0;
                    }else{
                      $total = $dreal->total;
                      $total = round($total,2);
                    }
                  }
                  $selisih4 =  $dbudget4->jml_jam - $total;
                  if($mbudget4 == 0){
                    $selisih4 = "0";
                  }else{
                    $selisih4 = ($total / $mbudget4) * 100;
                    $selisih4 = round($selisih4);
                  }

                  ?>
                  <td style="background-color: #d4e7bf; color: #000"><center><?php if($selisih4 > 100 or ($mbudget4 == '0' and $total > '0')){ echo "<font color= 'red'><b>"; echo number_format($mbudget4, 2, ',', '.');"</b>";}else{ echo  number_format($mbudget4, 2, ',', '.');} ?></center></td>
                  <td style="background-color: #abe0ee; color: #000"><center><?php if($selisih4 > 100  or ($mbudget4 == '0' and $total > '0')){echo "<font color= 'red'><b>";echo number_format($total, 2, ',', '.');"</b>";}else{ echo number_format($total, 2, ',', '.');} ?></center></td>
                  <td style="background-color: #d6b9d3 ; color: #000"><center><?php if($selisih4 > 100  or ($mbudget4 == '0' and $total > '0')){echo "<font color= 'red'><b>$selisih4 %</b>";}else{ echo $selisih4."%";} ?></center></td>
                  <!-- Kuartal IV end -->
                  <!-- Total per Tahun -->
                  <?php
                  $tahun_budget = $this->db->query("SELECT sum(jml_jam) as tahun_budget from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and mb.recid_bag = '$dbagian->recid_bag'")->result();
                  $tahun_realisasi = $this->db->query("SELECT sum(realisasi_jam) as tahun_realisasi from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and mb.recid_bag = '$dbagian->recid_bag'")->result();
                  foreach ($tahun_budget as $tbudget) {}
                    if($tbudget->tahun_budget == 0){
                      $tbudget = '0';
                    }else{
                      $tbudget = $tbudget->tahun_budget;
                      $tbudget = round($tbudget,2);
                    }
                    foreach ($tahun_realisasi as $trealisasi) {}
                      if($trealisasi->tahun_realisasi == 0){
                        $trealisasi = '0';
                      }else{
                        $trealisasi = $trealisasi->tahun_realisasi;
                        $trealisasi = round($trealisasi,2);
                      }
                      if($tbudget == '0'){
                        $tahun_capai = '0';
                      }else{
                        $tahun_capai = ($trealisasi / $tbudget) * 100;
                        $tahun_capai = round($tahun_capai);
                      }
                      ?>
                      <td style="background-color: #d4e7bf; color: #000"><center><?php if($tahun_capai > 100){echo "<font color= 'red'><b>";echo number_format($tbudget, 2, ',', '.');"</b>";}else{ echo number_format($tbudget, 2, ',', '.');} ?></center></td>
                      <td style="background-color: #abe0ee; color: #000"><center><?php if($tahun_capai > 100){echo "<font color= 'red'><b>";echo number_format($trealisasi, 2, ',', '.');"</b>";}else{ echo number_format($trealisasi, 2, ',', '.');} ?></center></td>
                      <td style="background-color: #d6b9d3; color: #000"><center><?php if($tahun_capai > 100){echo "<font color= 'red'><b>$tahun_capai %</b>";}else{ echo $tahun_capai." %";} ?></center></td>
                  <!-- Total Per Tahun end -->
                    <?php } //bagian ?>
                    <!-- Total Per Paygroup -->
                  </tr>
                   <tr style="background-color: #b5dce1; color: #000"><td><b>Jumlah <?php echo $pay->pay_group ?></b></td>
                   <?php 
                      for($x=0; $x<15;$x++){
                        if((($x+4) == 3 || ($x+4) == 7 || ($x+4) == 11 || ($x+4) == 15 || ($x+4) == 19  ) and $x != 0){ 
                           $moon = substr($month[$x], 8);
                           $total_budget = $this->db->query("SELECT sum(jml_jam) as total_budget from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and kuartal = '$moon'")->result();
                         $total_realisasi = $this->db->query("SELECT sum(realisasi_jam) as total_realisasi from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and kuartal = '$moon' and mb.tahun =  '$tahun' ")->result();
                         foreach ($total_budget as $total_budget) { }
                           if($total_budget->total_budget == null){
                            $total_budget = '0';
                          }else{
                            $total_budget = $total_budget->total_budget;
                            $total_budget = round($total_budget,2);
                          }
                          foreach ($total_realisasi as $total_realisasi) { }
                            if($total_realisasi->total_realisasi == null){
                              $total_realisasi = '0';
                            }else{
                              $total_realisasi = $total_realisasi->total_realisasi;
                              $total_realisasi = round($total_realisasi,2);
                            }
                            if($total_budget == 0){
                              $total_pencapaian = '0';
                            }else{
                              $total_pencapaian = ($total_realisasi/ $total_budget) * 100;
                              $total_pencapaian = round($total_pencapaian);
                            }
                          ?>
                          <td style="background-color: #c4e1c1; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>";echo number_format($total_budget, 2, ',', '.');"</b>";}else{ echo  number_format($total_budget, 2, ',', '.');}?></b></center></td>
                          <td style="background-color: #aae0fa; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>"; echo number_format($total_realisasi, 2, ',', '.');",</b>";}else{ echo number_format($total_realisasi, 2, ',', '.');} ?></b></center></td>
                          <td style="background-color: #d1b6d9; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>$total_pencapaian %</b>";}else{ echo $total_pencapaian." %";} ?></b></center></td>
                        <?php }else{
                         $total_budget = $this->db->query("SELECT sum(jml_jam) as total_budget from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and bulan = '$month[$x]'")->result();
                         $total_realisasi = $this->db->query("SELECT sum(realisasi_jam) as total_realisasi from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and bulan = '$month[$x]'")->result();
                         foreach ($total_budget as $total_budget) { }
                           if($total_budget->total_budget == null){
                            $total_budget = '0';
                          }else{
                            $total_budget = $total_budget->total_budget;
                            $total_budget = round($total_budget,2);
                          }
                          foreach ($total_realisasi as $total_realisasi) { }
                            if($total_realisasi->total_realisasi == null){
                              $total_realisasi = '0';
                            }else{
                              $total_realisasi = $total_realisasi->total_realisasi;
                              $total_realisasi = round($total_realisasi,2);
                            }
                            if($total_budget == 0){
                              $total_pencapaian = '0';
                            }else{
                              $total_pencapaian = ($total_realisasi/ $total_budget) * 100;
                              $total_pencapaian = round($total_pencapaian);
                            }
                          ?>
                          <td style="background-color: #c4e1c1; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>";echo number_format($total_budget, 2, ',', '.');"</b>";}else{ echo  number_format($total_budget, 2, ',', '.');}?></b></center></td>
                          <td style="background-color: #aae0fa; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>";echo number_format($total_realisasi, 2, ',', '.');"</b>";}else{ echo number_format($total_realisasi, 2, ',', '.');} ?></b></center></td>
                          <td style="background-color: #d1b6d9; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>$total_pencapaian %</b>";}else{ echo $total_pencapaian." %";} ?></b></center></td>
                        <?php }?>
                      <?php } ?>

                      <!-- Total kuartal IV -->
                      <?php
                        $total_budget = $this->db->query("SELECT sum(jml_jam) as total_budget from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and kuartal = 'IV'")->result();
                         $total_realisasi = $this->db->query("SELECT sum(realisasi_jam) as total_realisasi from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on pl.recid_mbl = mb.recid_mbl join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group' and kuartal = 'IV'")->result();
                         foreach ($total_budget as $total_budget) { }
                           if($total_budget->total_budget == null){
                            $total_budget = '0';
                          }else{
                            $total_budget = $total_budget->total_budget;
                            $total_budget = round($total_budget,2);
                          }
                          foreach ($total_realisasi as $total_realisasi) { }
                            if($total_realisasi->total_realisasi == null){
                              $total_realisasi = '0';
                            }else{
                              $total_realisasi = $total_realisasi->total_realisasi;
                              $total_realisasi = round($total_realisasi,2);
                            }
                            if($total_budget == 0){
                              $total_pencapaian = '0';
                            }else{
                              $total_pencapaian = ($total_realisasi/ $total_budget) * 100;
                              $total_pencapaian = round($total_pencapaian);
                            }
                          ?>
                          <td style="background-color: #c4e1c1; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>";echo number_format($total_budget, 2, ',', '.');"</b>";}else{ echo  number_format($total_budget, 2, ',', '.');}?></b></center></td>
                          <td style="background-color: #aae0fa; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>$total_realisasi</b>";}else{ echo $total_realisasi;} ?></b></center></td>
                          <td style="background-color: #d1b6d9; color: #000;"><center><b><?php if($total_pencapaian > 100){echo "<font color= 'red'><b>$total_pencapaian %</b>";}else{ echo $total_pencapaian." %";} ?></b></center></td>
                      <!-- Total Kuartal IV End -->

                      <!-- Total Paygroup Pertahun -->
                      <?php  
                      $budget_total = $this->db->query("SELECT sum(jml_jam) as budget_total from master_budget mb join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group'")->result();
                      $realisasi_total = $this->db->query("SELECT sum(realisasi_jam) as realisasi_total from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl join bagian b on mb.recid_bag = b.recid_bag where tahun = '$tahun' and pay_group = '$pay->pay_group'")->result();
                      foreach ($budget_total as $btotal) {}
                       if($btotal->budget_total == null){
                        $btotal = '0';
                      }else{
                        $btotal = $btotal->budget_total;
                        $btotal = round($btotal,2);
                      }
                      foreach ($realisasi_total as $rtotal) {}
                        if($rtotal->realisasi_total == null){
                          $rtotal = '0';
                        }else{
                          $rtotal = $rtotal->realisasi_total;
                          $rtotal = round($rtotal,2);
                        }
                        if($btotal == '0'){
                          $total_capai = '0';
                        }else{
                          $total_capai = ($rtotal / $btotal) * 100;
                          $total_capai = round($total_capai);
                        }
                        ?>
                        <td style="background-color: #c4e1c1; color: #000;"><center><b><?php if($total_capai > 100){echo "<font color= 'red'>";echo number_format($btotal, 2, ',', '.');}else{ echo number_format($btotal, 2, ',', '.');}  ?></b></center></td>
                        <td style="background-color: #aae0fa; color: #000;"><center><b><?php if($total_capai > 100){echo "<font color= 'red'>";echo number_format($rtotal, 2, ',', '.'); }else{ echo number_format($rtotal, 2, ',', '.');}?></b></center></td>
                        <td style="background-color: #d1b6d9; color: #000;"><center><b><?php if($total_capai > 100){echo "<font color= 'red'>$total_capai %";}else{ echo $total_capai."%";} ?></b></center></td>
                      <!-- Total Paygroup Pertahun End -->
                    </tr>
             <?php } // Total Per Paygroup End ?> 
             <!-- GRAND TOTAL -->
                <tr style="background-color:#4d648d; color: #fff";>
                  <td><b>Grand Total</b></td>
                <?php 
                      for($x=0; $x<15;$x++){
                        $moon = substr($month[$x], 8);
                        if((($x+4) == 3 || ($x+4) == 7 || ($x+4) == 11 || ($x+4) == 15 || ($x+4) == 19  ) and $x != 0){ 
                          $grandb = $this->db->query("SELECT sum(jml_jam) as grandb  from master_budget mb  where tahun = '$tahun' and kuartal = '$moon'")->result();
                          $grandr = $this->db->query("SELECT sum(realisasi_jam) as grandr  from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl and kuartal = '$moon' and mb.tahun =  '$tahun' ")->result();
                          foreach ($grandb as $grandb) { }
                            if($grandb->grandb == '0' || $grandb->grandb == null){
                              $grandb = '0';
                            }else{
                              $grandb = $grandb->grandb;
                              $grandb = round($grandb,2);
                            }
                            foreach ($grandr as $grandr) { } 
                              if($grandr->grandr == '0' || $grandr->grandr == null){
                                $grandr = '0';
                              }else{
                                $grandr = $grandr->grandr;
                                $grandr = round($grandr,2);
                              }

                              if($grandb == '0' || $grandb == null){
                                $grandp = "0";
                              }else{
                                $grandp = ($grandr / $grandb) * 100;
                                $grandp = round($grandp);
                              }
                          ?>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandb, 2, ',', '.');}else{ echo number_format($grandb, 2, ',', '.');} ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandr, 2, ',', '.');}else{ echo number_format($grandr, 2, ',', '.');}  ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>$grandp %";}else{ echo $grandp."%";}  ?></b></center></td>
                        <?php }else{
                          $grandb = $this->db->query("SELECT sum(jml_jam) as grandb  from master_budget mb  where tahun = '$tahun' and bulan = '$month[$x]'")->result();
                          $grandr = $this->db->query("SELECT sum(realisasi_jam) as grandr  from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl and bulan = '$month[$x]' and mb.tahun =  '$tahun'")->result();
                          foreach ($grandb as $grandb) { }
                            if($grandb->grandb == '0' || $grandb->grandb == null){
                              $grandb = '0';
                            }else{
                              $grandb = $grandb->grandb;
                              $grandb = round($grandb,2);
                            }
                            foreach ($grandr as $grandr) { } 
                              if($grandr->grandr == '0' || $grandr->grandr == null){
                                $grandr = '0';
                              }else{
                                $grandr = $grandr->grandr;
                                $grandr = round($grandr,2);
                              }

                              if($grandb == '0' || $grandb == null){
                                $grandp = "0";
                              }else{
                                $grandp = ($grandr / $grandb) * 100;
                                $grandp = round($grandp);
                              }
                          ?>
                         <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandb, 2, ',', '.');}else{ echo number_format($grandb, 2, ',', '.');} ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandr, 2, ',', '.');}else{ echo number_format($grandr, 2, ',', '.');}  ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>$grandp %";}else{ echo $grandp."%";}  ?></b></center></td>
                        <?php }?>
                      <?php } ?>

                      <!-- Total kuartal IV -->
                      <?php 
                       $grandb = $this->db->query("SELECT sum(jml_jam) as grandb  from master_budget mb  where tahun = '$tahun' and kuartal = 'IV'")->result();
                          $grandr = $this->db->query("SELECT sum(realisasi_jam) as grandr  from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl and kuartal = 'IV' and mb.tahun =  '$tahun'")->result();
                          foreach ($grandb as $grandb) { }
                            if($grandb->grandb == '0' || $grandb->grandb == null){
                              $grandb = '0';
                            }else{
                              $grandb = $grandb->grandb;
                              $grandb = round($grandb,2);
                            }
                            foreach ($grandr as $grandr) { } 
                              if($grandr->grandr == '0' || $grandr->grandr == null){
                                $grandr = '0';
                              }else{
                                $grandr = $grandr->grandr;
                                $grandr = round($grandr,2);
                              }

                              if($grandb == '0' || $grandb == null){
                                $grandp = "0";
                              }else{
                                $grandp = ($grandr / $grandb) * 100;
                                $grandp = round($grandp);
                              }
                          ?>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandb, 2, ',', '.');}else{ echo number_format($grandb, 2, ',', '.');} ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>"; echo number_format($grandr, 2, ',', '.');}else{ echo number_format($grandr, 2, ',', '.');}  ?></b></center></td>
                          <td><center><b><?php if($grandp > 100){echo "<font color= 'red'>$grandp %";}else{ echo $grandp."%";}  ?></b></center></td>
                      <!-- Total Kuartal IV End -->

                      <!-- Total Paygroup Pertahun -->
                      <?php 
                      $grand_budget = $this->db->query("SELECT sum(jml_jam) as gbudget  from master_budget mb  where tahun = '$tahun'")->result();
                      foreach ($grand_budget as $gbudget) { }
                        if($gbudget->gbudget == '0' || $gbudget->gbudget == 'null'){
                          $gbudget = "0";
                        }else{
                          $gbudget = $gbudget->gbudget;
                          $gbudget = round($gbudget,2);
                        }
                        $grand_real = $this->db->query("SELECT sum(realisasi_jam) as greal from realisasi_lembur rl join pengajuan_lembur pl on pl.recid_plembur = rl.recid_plembur join master_budget mb on mb.recid_mbl = pl.recid_mbl where mb.tahun =  '$tahun'")->result();
                        foreach ($grand_real as $greal) { }
                         if($greal->greal == "0" || $greal->greal == null){
                          $greal = '0';
                        }else{
                          $greal = $greal->greal;
                          $greal = round($greal,2);
                        }
                        if($gbudget == "0" || $gbudget == null){
                          $grand_percent = "0";
                        }else{
                          $grand_percent = ($greal /  $gbudget) * 100;
                          $grand_percent = round($grand_percent); 
                        }
                        ?>
                        <td><center><?php if($grand_percent > 100){echo "<font color= 'red'><b>";echo number_format($gbudget, 2, ',', '.');"</b>";}else{ echo number_format($gbudget, 2, ',', '.');} ?></center></td>
                        <td><center><?php  if($grand_percent > 100){echo "<font color= 'red'><b>";echo number_format($gbudget, 2, ',', '.');"</b>";}else{ echo number_format($greal, 2, ',', '.');}?></center></td>
                        <td><center><?php  if($grand_percent > 100){echo "<font color= 'red'><b>$grand_percent %</b>";}else{ echo $grand_percent."%";} ?></center></td>
                      <!-- Total Paygroup Pertahun End -->
                    </tr>
             <!-- GRAND TOTAL END-->
          
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>



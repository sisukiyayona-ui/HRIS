<!-- page content -->

<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Realisasi Lembur</h3>
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
                    <h2><a href="<?php echo base_url()?>Lembur/stkl_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Pengajuan Lembur</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php
                        foreach ($stkl->result() as $s) {
                            # code...
                        }
                    ?>
                     <form id="form_lembur" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>Lembur/prealisasi" data-toggle="validator">
                      <?php echo $this->session->flashdata('message'); ?>
                       <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type='hidden' class="form-control" name="recid_stkl"  id="recid_stkl" value="<?php echo $s->recid_stkl ?>"/>
                            <input type='hidden' class="form-control"  id="lamalem"/>
                          <div class='input-group date' id='myDatepicker2'>
                            <input type='text' class="form-control" id="tgl_lembur" name="tgl_lembur" placeholder="thn-bln-tgl" required="required" value="<?php echo $s->tgl_lembur ?>" />
                            <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                           </span>
                         </div>
                       </div>
                     </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Bagian <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_bag" id="recid_bagian"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($bagian as $option) {
                                if($option->recid_bag == $s->recid_bag)
                                {
                                    echo "<option value='$option->recid_bag' selected>$option->indeks_hr ($option->nama_bag)</option>";
                                }else{
                                    echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                                }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Kategori Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="recid_kategori" id="recid_kategori"  class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required">
                            <?php
                            echo "<option value=''>-- Pilih --</option>";
                            foreach ($kategori->result() as $k) {
                                if($k->recid_kategori == $s->recid_kategori)
                                {
                                    echo "<option value='$k->recid_kategori' selected>$k->kategori</option>";
                                }else{
                                    echo "<option value='$k->recid_kategori'>$k->kategori</option>";
                                }
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                        <div class="item form-group" id="jam_masuk">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Mulai <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myTime1'>
                              <input type='text' class="form-control" name="jam_mulai"  id="jam_mulai" value="<?php echo $s->jam_mulai ?>"/>
                              <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="item form-group" id="jam_keluar">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Selesai <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class='input-group date' id='myTime2'>
                              <input type='text' class="form-control" name="jam_selesai" id="jam_selesai" value="<?php echo $s->jam_selesai ?>"/>
                              <span class="input-group-addon">
                                <span class="fa fa-clock-o"></span>
                              </span>
                            </div>
                          </div>
                        </div>
                        
                        <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Klasifikasi <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php 
                                $klas = ["Tidak Terencana", "Terencana"];
                            ?>
                          <select name="klasifikasi" id="klasifikasi"  class="selectpicker form-control  col-md-12 col-xs-12" required="required">
                            <?php 
                                for($kl=0; $kl<count($klas); $kl++)
                                {
                                    if($klas[$kl] == $s->klasifikasi)
                                    {?>
                                         <option value="<?php echo $klas[$kl] ?>" selected><?php echo $klas[$kl] ?></option>
                                    <?php }else{?>
                                         <option value="<?php echo $klas[$kl] ?>"><?php echo $klas[$kl] ?></option>
                                    <?php }
                                }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label for="agama" class="control-label col-md-3">Tipe <span class="required" style="color: red">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php 
                                $tp = ["Produksi", "Non Produksi"];
                            ?>
                          <select name="tipe" id="tipe"  class="selectpicker form-control  col-md-12 col-xs-12"  required="required">
                           <?php 
                                for($t=0; $t<count($tp); $t++)
                                {
                                    if($tp[$t] == $s->tipe)
                                    {?>
                                         <option value="<?php echo $tp[$t] ?>" selected><?php echo $tp[$t] ?></option>
                                    <?php }else{?>
                                         <option value="<?php echo $tp[$t] ?>"><?php echo $tp[$t] ?></option>
                                    <?php }
                                }
                            ?>
                          </select>
                        </div>
                      </div>
                            
                    
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Karyawan Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <!-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Tambah Karyawan</button><br><br> -->
                        <!-- <span style="color:red">* Data Pada Tabel Akan Tersimpan Otomatis</span> -->
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th>#</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Jam Pulang Lembur</th>
                            <th>Lama Lembur</th>
                            <th>Aksi</th>
                            </thead>
                            <tbody>
                            <?php
                            $no = 0;
                            foreach ($anggota->result() as $key) {
                              $dup = $this->m_lembur->cek_dup_emp($s->tgl_lembur, $key->recid_karyawan);
                              if($dup->num_rows()> 1)
                              {
                                $fdup = 1;
                              }else{
                                $fdup = 0;
                              }
                                $jam_barcode = $this->m_lembur->cek_kehadiran($s->tgl_lembur, $key->recid_karyawan);
                                    if($jam_barcode->num_rows() > 0)
                                    {
                                        foreach ($jam_barcode->result() as $jb) {
                                            $jam_masuk = $jb->jam_masuk;
                                            $jam_pulang = $jb->jam_keluar;
                                            $tgl_pulang = $jb->tgl_pulang;
                                            $tgl_masuk = $jb->tgl_masuk;
                                        }
                                    }else{
                                        $jam_masuk = "-";
                                        $jam_pulang = "-";
                                    }
                                    ?>
                                
                                <?php echo ($fdup == 1) ? "<tr style='background-color : yellow; color:black'>" : "<tr>"; ?>
                                <td><?php echo $no = $no+1 ?></td>
                                <td><?php echo $key->nik ?></td>
                                <td><?php echo $key->nama_karyawan ?></td>
                                <td><?php echo $key->indeks_hr ?></td>
                                <td><?php echo $jam_masuk ?></td>
                                <td><?php echo $jam_pulang ?></td>
                                <td><?php echo $key->jam_selesai ?></td>
                                <td>
                                <?php 
                                  $ca = "";
                                  $krg_jam = 0;
                                  $jam_in = $jb->jam_in;
                                  $jam_in2 = $jb->jam_in;
                                  $jam_in = date_create($jam_in);
                                  $cjam_in = date_add($jam_in, date_interval_create_from_date_string('1 minutes'));
                                  $jam_in= date_format($jam_in, 'H:i:s');
                                  if($jam_masuk > $jam_in2  and $jam_masuk < $jam_in )
                                  {
                                    $jam_masuk = $jam_in2;
                                              // echo "jadi normal $jam_in2";
                                  }
                                  if($fholi == 1){
                                  // jika long shift hari libur
                                    if(($s->jam_mulai == "06:00:00" && $key->jam_selesai == "14:00:00") || ($s->jam_mulai == "14:00:00" && $key->jam_selesai == "22:00:00") || ($s->jam_mulai == "22:00:00" && $key->jam_selesai == "06:00:00"))
                                      {
                                      // kalo jam masuk / pulangnya 00/-/''
                                      if($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00")
                                      {
                                        echo "0 jam";
                                      }else if($jam_masuk <= $s->jam_mulai)
                                      {
                                        // masuk normal
                                        if($jam_pulang >= $key->jam_selesai)
                                        {
                                          // tidak pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                          {
                                            // pulang beda hari
                                            $ca .= "Cek HL 1<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "beda tgl masuk & pulang, pulang normal";
                                          }else{
                                            $ca .= "Cek HL 2<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "sama tgl masuk & pulang, pulang normal";
                                          }
                                        }else{
                                          // pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                            {
                                              //pulang beda hari
                                              $ca .= "Cek HL 3<br>";
                                              $akhir = strtotime($tgl_masuk.' '.$jam_pulang);
                                               $ca .= "beda tgl masuk & pulang, pulang cepat";
                                            }else{
                                              $ca .= "Cek HL 4<br>";
                                              $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                              $ca .= "sama tgl masuk & pulang, pulang cepat";
                                            }
                                        }
                                        // echo $ca;
                                        $mulai = strtotime($tgl_masuk.' '.$s->jam_mulai);
                                        $diff   = $akhir - $mulai;
                                        $jam    = floor($diff / (60 * 60));
                                        $menit  =($diff - $jam * (60 * 60)) / 60;
                                        if($jam < 0)
                                        {
                                          echo  "0 jam";
                                        }else{
                                          echo "$jam Jam $menit Menit";
                                        }
                                      }else{
                                        // jam masuk dan pulang data normal
                                        if($jam_pulang >= $key->jam_selesai)
                                        {
                                          //tidak pulang cepat
                                         if($tgl_pulang != $tgl_masuk)
                                          {
                                            $ca .= "Cek HL 5<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "beda tgl masuk & pulang, pulang normal";
                                          }else{
                                            $ca .= "Cek HL 6<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "sama tgl masuk & pulang, pulang normal";
                                          }
                                        }else{
                                          //pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                            {
                                              $ca .= "Cek HL 7<br>";
                                              $akhir = strtotime($tgl_masuk.' '.$jam_pulang);
                                               $ca .= "beda tgl masuk & pulang, pulang cepat";
                                            }else{
                                              $ca .= "Cek HL 8<br>";
                                              $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                              $ca .= "sama tgl masuk & pulang, pulang cepat";
                                            }
                                        }
                                        // echo $ca;
                                        $mulai = strtotime($tgl_masuk.' '.$jam_masuk);
                                        $diff   = $akhir - $mulai;
                                        $jam    =floor($diff / (60 * 60));
                                        $menit  =($diff - $jam * (60 * 60)) / 60;
                                        if($jam < 0)
                                        {
                                          echo  "0 jam";
                                        }else{
                                          if($jam < 0)
                                          {
                                            echo  "0 jam";
                                          }else{
                                            echo "$jam Jam $menit Menit";
                                          }
                                        }
                                      }
                                    }else{
                                      // -----asli non sift libur ----
                                      // jika jam masuk/pulang 00:00 atau - atau ''
                                      if($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00")
                                      {
                                        echo "0 jam";
                                      }else if($jam_masuk <= $s->jam_mulai)
                                      {
                                        //Jika MASUK NORMAL
                                        if($jam_pulang >= $key->jam_selesai)
                                        {
                                          //tidak pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                          {
                                            $ca .= "Cek HL 9<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "beda tgl masuk & pulang, masuk & pulang normal";
                                          }else{
                                            $ca .= "Cek HL 10<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "sama tgl masuk & pulang, masuk & pulang normal";
                                          }
                                        }else{
                                          // pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                            {
                                              $ca .= "Cek HL 11<br>";
                                              $akhir = strtotime($tgl_masuk.' '.$jam_pulang);
                                              $ca .= "beda tgl masuk & pulang, masuk telat, pulang cepat";
                                            }else{
                                              $ca .= "Cek HL 12<br>";
                                              $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                              $ca .= "sama tgl masuk & pulang, masuk telat, pulang cepat";
                                            }
                                        }
                                        // echo $ca;
                                        $mulai = strtotime($tgl_masuk.' '.$s->jam_mulai);
                                        $diff   = $akhir - $mulai;
                                        $jam    = floor($diff / (60 * 60));
                                        $menit  =($diff - $jam * (60 * 60)) / 60;
                                      if($jam < 0)
                                        {
                                          echo "0 jam";
                                        }else{
                                          $kaljam = $jam+($menit/60);
                                          if($kaljam >= 5 and $kaljam <= 10)
                                          {
                                            $krg_jam = 1;
                                          }else if($kaljam > 10 and $kaljam < 15)
                                          {
                                            $krg_jam = 1.5;
                                          }else if($kaljam >= 15 and $kaljam < 20){
                                            $krg_jam = 2;
                                          }else if($kaljam >= 20 and $kaljam < 24){
                                            $krg_jam = 2.5;
                                          }else{
                                            $krg_jam = $krg_jam;
                                          }
                                          $totlem = $kaljam - $krg_jam;
                                          if(is_numeric($totlem) && floor($totlem) != $totlem)
                                          {
                                            $total_jam = explode(".", $totlem);
                                            $j = $total_jam[0];
                                            $m = $total_jam[1];
                                            $m = "0.".$m;
                                            $m = (float)$m;
                                            $m = round($m * 60);
                                            if($m == 60)
                                            {
                                              $j = $j+1;
                                              $m = 0;
                                            }
                                            echo "$j jam $m menit";
                                          }else{
                                            echo "$totlem jam";
                                          }
                                        }
                                      }else{
                                        // Jika masuk telat
                                        if($jam_pulang >= $key->jam_selesai)
                                        {
                                          // tidak pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                          {
                                            $ca .= "Cek HL 13<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "beda tgl masuk & pulang, pulang normal";
                                          }else{
                                            $ca .= "Cek HL 14<br>";
                                            $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                            $ca .= "sama tgl masuk & pulang, pulang normal";
                                          }
                                        }else{
                                          // pulang cepat
                                          if($tgl_pulang != $tgl_masuk)
                                            {
                                              $ca .= "Cek HL 15<br>";
                                              $akhir = strtotime($tgl_masuk.' '.$jam_pulang);
                                              $ca .= "beda tgl masuk & pulang, pulang cepat";
                                            }else{
                                              $ca .= "Cek HL 16<br>";
                                              $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                              $ca .= "sama tgl masuk & pulang, pulang cepat";
                                            }
                                        }
                                        // echo $ca;
                                        $mulai = strtotime($tgl_masuk.' '.$jam_masuk);
                                        $diff   = $akhir - $mulai;
                                        $jam    =floor($diff / (60 * 60));
                                        $menit  =($diff - $jam * (60 * 60)) / 60;
                                        if($jam < 0)
                                        {
                                          echo  "0 jam";
                                        }else{
                                          $kaljam = $jam+($menit/60);
                                          if($kaljam >= 5 and $kaljam < 10)
                                          {
                                            $krg_jam = 1;
                                          }else if($kaljam >= 10 and $kaljam < 15)
                                          {
                                            $krg_jam = 1.5;
                                          }else if($kaljam >= 15 and $kaljam < 20){
                                            $krg_jam = 2;
                                          }else if($kaljam >= 20 and $kaljam < 24){
                                            $krg_jam = 2.5;
                                          }else{
                                            $krg_jam = $krg_jam;
                                          }
                                          $totlem = $kaljam - $krg_jam;
                                          if(is_numeric($totlem) && floor($totlem) != $totlem)
                                          {
                                            $total_jam = explode(".", $totlem);
                                            $j = $total_jam[0];
                                            $m = $total_jam[1];
                                            $m = "0.".$m;
                                            $m = (float)$m;
                                            $m = round($m * 60);
                                            if($m == 60)
                                            {
                                              $j = $j+1;
                                              $m = 0;
                                            }
                                            echo "$j jam $m menit";
                                          }else{
                                            echo "$totlem jam";
                                          }
                                        }
                                      }
                                    }
                                        }else{ /* HARI BIASA*/
                                          //JIKA OT SHIFT HARI BIASA
                                          if(($s->jam_mulai == "06:00:00" && $key->jam_selesai == "14:00:00") || ($s->jam_mulai == "14:00:00" && $key->jam_selesai == "22:00:00") || ($s->jam_mulai == "22:00:00" && $key->jam_selesai == "06:00:00"))
                                          {
                                            //jika jam masuk / pulang 00:00 atau - atau ''
                                            if($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00")
                                            {
                                              echo "0 jam";
                                            }else if($jam_masuk <= $s->jam_mulai)
                                            {
                                              //masuk normal
                                              if($jam_pulang >= $key->jam_selesai)
                                              {
                                                //tidak pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 1";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
                                                }else{
                                                  $ca .= "cek HB 2";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang sesuai";
                                                }
                                              }else{
                                                //pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 3";
                                                  $akhir = strtotime($tgl_masuk.' '.$key->jam_selesai);
                                                  $ca .= "Beda tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
                                                }else{
                                                  $ca .= "cek HB 4";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "Sama tgl masuk dan pulang, OT Shift HB, jam masuk/pulang barcode tidak normal, pulang cepat";
                                                }
                                              }
                                              // echo $ca;
                                              $mulai = strtotime($tgl_masuk.' '.$s->jam_mulai);
                                              $diff   = $akhir - $mulai;
                                              $jam    = floor($diff / (60 * 60));
                                              $menit  =($diff - $jam * (60 * 60)) / 60;
                                              if($jam < 0)
                                              {
                                                echo  "0 jam";
                                              }else{
                                                echo "$jam Jam $menit Menit";
                                              }
                                            }else{
                                            //masuk telat
                                              if($jam_pulang >= $key->jam_selesai)
                                              {
                                                //tidak pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 5";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "beda tgl masuk & pulang, pulang normal";
                                                }else{
                                                  $ca .= "cek HB 6";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "sama tgl masuk & pulang, pulang normal";
                                                }
                                              }else{
                                                //pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 7";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "beda tgl masuk & pulang, pulang lebih cepat";
                                                }else{
                                                  $ca .= "cek HB 8";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "sama tgl masuk & pulang, pulang lebih cepat";
                                                }
                                              }
                                              // echo $ca;
                                              $mulai = strtotime($tgl_masuk.' '.$jam_masuk);
                                              $diff   = $akhir - $mulai;
                                              $jam    =floor($diff / (60 * 60));
                                              $menit  =($diff - $jam * (60 * 60)) / 60;
                                              if($jam < 0)
                                              {
                                                echo  "0 jam";
                                              }else{
                                                if($jam < 0)
                                                {
                                                  echo  "0 jam";
                                                }else{
                                                  echo "$jam Jam $menit Menit";
                                                }
                                              }
                                            }
                                        }else{
                                          // echo "Bukan OT SHIFT";
                                          // BUKAN OT SHIFT = yang biasa
                                          //jika jam masuk / pulang 00:00 atau - atau ''
                                            if($jam_masuk == '-' or $jam_pulang == '-' or $jam_masuk == '' or $jam_pulang == '' or $jam_masuk == '00:00:00' or $jam_pulang == "00:00:00")
                                            {
                                              echo "0 jam";
                                              $akhir = strtotime($tgl_pulang.' 00:00:00');
                                            }else if($jam_masuk <= $s->jam_mulai)
                                            {
                                              //masuk normal
                                              if($jam_pulang >= $key->jam_selesai)
                                              {
                                                //tidak pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 9";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "beda tgl masuk & pulang, pulang normal";
                                                }else{
                                                  $ca .= "cek HB 10";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "sama tgl masuk & pulang, pulang normal";
                                                }
                                              }else{
                                                //pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 11";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "beda tgl masuk & pulang (bisa jadi 2 stkl pada hari yg sama), pulang lebih cepat karena sudah beda tgl";
                                                }else{
                                                  $ca .= "cek HB 12";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "sama tgl masuk & pulang, pulang lebih cepat";
                                                }
                                              }
                                              // echo $ca;
                                              // $mulai = strtotime($tgl_masuk.' '.$s->jam_mulai);
                                              // $diff   = $akhir - $mulai;
                                              // $jam    = floor($diff / (60 * 60));
                                              // $menit  =($diff - $jam * (60 * 60)) / 60;
                                              // if($jam < 0)
                                              // {
                                              //   echo  "0 jam";
                                              // }else{
                                              //   echo "$jam Jam $menit Menit";
                                              // }
                                            }else{
                                            //masuk telat
                                              if($jam_pulang >= $key->jam_selesai)
                                              {
                                                //tidak pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 13";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "beda tgl masuk & pulang, pulang normal";
                                                }else{
                                                  $ca .= "cek HB 14";
                                                  $akhir = strtotime($tgl_pulang.' '.$key->jam_selesai);
                                                  $ca .= "sama tgl masuk & pulang, pulang normal";
                                                }
                                              }else{
                                                //pulang cepat
                                                if($tgl_pulang != $tgl_masuk)
                                                {
                                                  $ca .= "cek HB 15";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "beda tgl masuk & pulang, pulang lebih cepat";
                                                }else{
                                                  $ca .= "cek HB 16";
                                                  $akhir = strtotime($tgl_pulang.' '.$jam_pulang);
                                                  $ca .= "sama tgl masuk & pulang, pulang lebih cepat";
                                                }
                                              }
                                              // $mulai = strtotime($tgl_masuk.' '.$jam_masuk);
                                              // $diff   = $akhir - $mulai;
                                              // $jam    =floor($diff / (60 * 60));
                                              // $menit  =($diff - $jam * (60 * 60)) / 60;
                                              // if($jam < 0)
                                              // {
                                              //   echo  "0 jam";
                                              // }else{
                                              //   if($jam < 0)
                                              //   {
                                              //     echo  "0 jam";
                                              //   }else{
                                              //     echo "$jam Jam $menit Menit";
                                              //   }
                                              // }
                                            }

                                          // echo $ca;
                                            $mulai = strtotime($tgl_masuk.' '.$s->jam_mulai);
                                            $diff   = $akhir - $mulai;
                                            $jam    =floor($diff / (60 * 60));
                                            $menit  =($diff - $jam * (60 * 60)) / 60;
                                            if($jam < 0)
                                            {
                                              echo "0 jam";
                                            }else{
                                              $kaljam = $jam+($menit/60);
                                              if($kaljam >= 5 and $kaljam < 10)
                                              {
                                                $krg_jam = 0.5;
                                              }else if($kaljam >= 10 and $kaljam < 15)
                                              {
                                                $krg_jam = 1;
                                              }else if($kaljam >= 15 and $kaljam < 20){
                                                $krg_jam = 1.5;
                                              }else if($kaljam >= 20 and $kaljam < 24){
                                                $krg_jam = 2;
                                              }else{
                                                $krg_jam = $krg_jam;
                                              }
                                              
                                              $totlem = $kaljam - $krg_jam;
                                              if(is_numeric($totlem) && floor($totlem) != $totlem)
                                              {
                                                $total_jam = explode(".", $totlem);
                                                $j = $total_jam[0];
                                                $m = $total_jam[1];
                                                $m = "0.".$m;
                                                $m = (float)$m;
                                                $m = round($m * 60);
                                                if($m == 60)
                                                {
                                                  $j = $j+1;
                                                  $m = 0;
                                                }
                                                // $lamlem = $totlem;
                                                echo "$j jam $m menit";
                                                // echo $jam_pulang." >= ".$s->jam_sls;
                                              }else{
                                                echo "$totlem jam";
                                              }
                                            }
                                        } 
                                      }
                                ?>
                                </td>
                                <td><a href="<?php echo base_url()?>index.php/Lembur/del_karyawan_lembur/<?php echo $key->recid_stkl ?>/<?php echo $key->recid_detstkl ?>/realisasi"><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></a>&nbsp;
                                <button type="button" class="btn btn-xs btn-info" data-toggle='modal' data-target='#myModal3' data-recid_detstkl = '<?php echo $key->recid_detstkl?>' data-jam_selesai_real = '<?php echo $key->jam_sls?>'><i class="fa fa-edit"></i></button></td>
                                </tr>
                            <?php  }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                      <div class="item form-group" style="display:none">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Total Jam Lembur <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="total_jam" id="total_jam"  value="<?php echo $s->total_jam ?>" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Total Lembur (satuan jam)">
                          <input type="text" name="jml_orang" id="jml_orang"  value="<?php echo $s->jml_orang ?>" required="required" readonly class="form-control col-md-7 col-xs-12" placeholder="Jumlah Orang">
                          <input type="text" name="recid_mbl" id="mbl" value="<?php echo $s->recid_mbl ?>" class="form-control col-md-7 col-xs-12" readonly placeholder="mbl">
                        </div>
                      </div>
                        <div class="item form-group" id="alasan">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Alasan Over Budget <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="alasan_over" id="alasan_over" class="form-control" placeholder="Alasan Over Budget"><?php echo $s->alasan_over ?></textarea>
                        </div>
                      </div>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Keterangan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan lembur"><?php echo $s->keterangan ?></textarea>
                        </div>
                      </div>
                      <br>
                      
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Uraian Pekerjaan <span class="required" style="color: red">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal2">Tambah Uraian Pekerjaan</button><br><br>
                        <span style="color:red">* Data Pada Tabel Akan Tersimpan Otomatis</span>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Pekerjaan</th>
                            <th>Target</th>
                            <th>Hasil</th>
                            <th>Aksi</th>
                            </thead>
                            <tbody>
                            <?php
                            $noo = 0;
                            foreach ($pekerjaan->result() as $p) {?>
                                <tr>
                                <td><?php echo $noo = $noo+1?></td>
                                <td><?php echo $p->pekerjaan ?></td>
                                <td><?php echo $p->target_kerja." ".$p->satuan ?></td>
                                <td><?php echo $p->hasil." ".$p->satuan ?></td>
                                <td><a href="<?php echo base_url()?>index.php/Lembur/del_pekerjaan/<?php echo $p->recid_plembur ?>/<?php echo $p->recid_detlembur ?>"><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></a>
                                <button type="button" class="btn btn-xs btn-info" data-toggle='modal' data-target='#myModal4' data-recid_detlembur = '<?php echo $p->recid_detlembur?>' data-pekerjaan = '<?php echo $p->pekerjaan?>' data-target_kerja= '<?php echo $p->target_kerja?>' data-hasil = '<?php echo $p->hasil?>' data-satuans = '<?php echo $p->satuan?>'><i class="fa fa-edit"></i></button>
                              </td>
                                </tr>
                            <?php  }
                            ?>

                            </tbody>
                        </table>
                        </div>
                    </div>
                    
                    <?php 
                        if($role == '1' or $role=='3' or $role == '5')
                        {?>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Transport <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="radio" name="jemputan" value="1" > Uang<br>
                                <input type="radio" name="jemputan" value="0" checked> Jemputan<br>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Makan <span class="required" style="color: red">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="radio" name="makan" value="1" > Uang<br>
                                <input type="radio" name="makan" value="0" checked > Catering<br>
                                </div>
                            </div>
                        <?php }
                    ?>

                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                         <button id="send" type="button" class="btn btn-success" onclick="update_lembur()">Submit</button>
                          <a href='<?php echo base_url()?>Lembur/stkl_view'><input type="button" class="btn btn-danger" value="Cancel"></button></a>
                       </div>
                     </div>
                     </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Karyawan Lembur</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/lembur_addkar" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Nama Karyawan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="dari" required="required" class="form-control col-md-7 col-xs-12" value="realisasi">
            <input type="hidden" name="recid_stkl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $s->recid_stkl ?>">
            <select name="recid_karyawan[]" class="form-control col-md-7 col-xs-12 searchable" id='callbacks' multiple='multiple' required="required">
             <?php
             foreach ($karyawan as $option) {
               echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik - $option->indeks_hr)</option>";
             }
             ?>
           </select>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <textarea id="note" name="keterangan" class="form-control col-md-7 col-xs-12"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>
</div>
</div>

        <!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Uraian Pekerjaan</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/lembur_addkerja" novalidate >

         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Uraian Pekerjaan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="recid_plembur" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $s->recid_stkl ?>">
            <input type="hidden" name="dari" required="required" class="form-control col-md-7 col-xs-12" value="realisasi">
        
           <textarea name="pekerjaan" id="pekerjaan" class="form-control pekerjaan" placeholder="kegiatan lembur"></textarea>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Target <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="target" id="target" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Target Lembur" rows="1" cols="1"></textarea>
        </div>
        </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Satuan <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="satuan" id="satuan" class="form-control"  placeholder="Satuan Target (contoh : PCS, Persen, dll)" rows="1" cols="1"></textarea>
        </div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>
</div>
</div>

  <!-- Modal -->
<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Jam Realisasi Lembur</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/update_jam_lembur" novalidate >
        <div class="item form-group" id="jam_keluar">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Selesai<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
               <input type='hidden' class="form-control" name="recid_detstkl" id="recid_detstkl"/>
               <input type='hidden' class="form-control" name="dari" value="realisasi"/>
               <input type='hidden' class="form-control" name="recid_stkl" value="<?php echo $s->recid_stkl ?>"/>
               <div class='input-group date' id='myTime3'>
                <input type='text' class="form-control" name="jam_selesai" id="jam_selesai_real"/>
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
              </div>
          </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
</div>
</div>
</div>

 <!-- Modal -->
<div id="myModal4" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Realisasi Target</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url()?>index.php/Lembur/realisasi_target" novalidate >
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Uraian Pekerjaan <span class="required" style="color: red">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="recid_detlembur" id="recid_detlembur" required="required" class="form-control col-md-7 col-xs-12"">
        
           <textarea name="pekerjaan" id="pekerjaan2" class="form-control" placeholder="kegiatan lembur"></textarea>
         </div>
       </div>
       <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Target <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" name="dari" required="required" class="form-control col-md-7 col-xs-12" value="realisasi">
            <input type="hidden" name="recid_stkl" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $s->recid_stkl ?>">

            <textarea name="target" id="target2" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Target Lembur" rows="1" cols="1"></textarea>
        </div>
        </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Hasil <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="hasil" id="hasil" class="form-control" onkeypress="return hanyaAngka(event)" placeholder="Target Lembur" rows="1" cols="1"></textarea>
        </div>
        </div>
        <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Satuan <span class="required" style="color: red">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="satuan" id="satuans" class="form-control"  placeholder="Satuan Target (contoh : PCS, Persen, dll)" rows="1" cols="1"></textarea>
        </div>
        </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value="Save">
    </form>
  </div>
  </div>
</div>
</div>
</div>

<script>
  Date.prototype.getMonthName = function() {
    var monthNames = [ "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember" ];
    return monthNames[this.getMonth()];
  }

  $('#myModal3').on('show.bs.modal', function (event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal          = $(this)

       modal.find('#recid_detstkl').attr("value",div.data('recid_detstkl'));
      $('#jam_selesai_real').val(div.data('jam_selesai_real'));
    });

    $('#myModal4').on('show.bs.modal', function (event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal          = $(this)

      $('#recid_detlembur').val(div.data('recid_detlembur'));
      $('#pekerjaan2').val(div.data('pekerjaan'));
      $('#target2').val(div.data('target_kerja'));
      $('#hasil').val(div.data('hasil'));
      $('#satuans').val(div.data('satuans'));
    });


  function update_lembur()
  {
    tgl_lembur = $("#tgl_lembur").val();
    recid_bagian = $("#recid_bagian").val();
    var recid_stkl = document.getElementById("recid_stkl").value;
    
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url();?>index.php/Lembur/masterbudget2", // Isi dengan url/path file php yang dituju
      data: {recid_bag :recid_bagian,tgl :tgl_lembur, recid_stkl : recid_stkl},
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
          }
       },
      success: function(data, response){ // Ketika proses pengiriman berhasil
        //data[0] => cek cut off udah dibuat apa belum
        //data[1] => cek master budget bagian itu udah dibuat apa belum
        //data[2] => cek jumlah budget per kuartal
        //data[3] => recid mbl

        console.log("Cut off "+data[0]);
        console.log("MBL "+data[1]);
        if(data[0] == "Ready")
        {
          if(data[1] == "Ada")
          {
              budget_kuartal = data[2];
          
              // jumlah orang
              jml_orang = data[4];
              console.log(jml_orang);

               recid_mbl = parseInt(data[3]);
               $('#mbl').val(recid_mbl);
              
              $.ajax({  
                type: "POST", // 
                url: "<?php echo base_url();?>Lembur/hitung_jam_lembur", 
                data: {recid_stkl : recid_stkl}, 
                dataType: "json",
                beforeSend: function(e) {
                  if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                  }
                },
                success: function(data, response){ // Ketika proses pengiriman berhasil
                  $('#lamalem').val(data);
                  // jml jam lembur
                  lamlem = data;
                  
                  hours = lamlem.split(':')[0];
                  hours = parseInt(hours);
                  minutes = lamlem.split(':')[1];
            
                  minutes = minutes.toString().length<2?''+minutes:minutes;
                  if(minutes<0){ 
                      hours--;
                      minutes = 60 + minutes; 
                      min = 0;       
                  }else{
                    min = minutes/60;
                  }
                  kal_jam = hours+min;
                  // kal_jam = parseInt(kal_jam);

                  hours = hours.toString().length<2?''+hours:hours;
                  console.log(hours + ' jam ' + minutes + ' menit = ' + kal_jam);

                  /* hitung budget cukup atau tidak */
                  sisa_budget = budget_kuartal - kal_jam;
                  console.log("sisa budget = "+sisa_budget);
                  $('#total_jam').val(kal_jam);
                  $('#jml_orang').val(jml_orang);

                  if(sisa_budget >= 0)
                  {
                    console.log("Budget Cukup");
                    $( "#form_lembur" ).submit();
                  }else{
                    // alert("Master Budget Over, Harap Isi Alasan!");
                    $("#alasan").show();
                    if($("#alasan_over").val() != '')  
                    {
                      $( "#form_lembur" ).submit();
                    }else{
                      alert("Master Budget Over, Harap Isi Alasan!");
                    }
                  }
                },
                error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                  alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
                }
              });
          }else{
            alert("Harap isi master budget! (Hubungi bagian HC / Finance)");
          }
        }else{
          alert("Cut Off Lembur Belum Dibuat");
        }
        console.log(data);
       },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }

  function hitung_jam_lembur(recid_stkl)
  {
    var hasils = 0;
    $.ajax({  
      type: "POST", // 
      url: "<?php echo base_url();?>Lembur/hitung_jam_lembur", 
      data: {recid_stkl : recid_stkl}, 
      dataType: "json",
      beforeSend: function(e) {
        if(e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(data, response){ // Ketika proses pengiriman berhasil
        $('#lamalem').val(data);
      },
      error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }
</script>
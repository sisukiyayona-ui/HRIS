<!-- <--page content --> 
<?php $role=$this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Kandidat</h3>
      </div>
    </div>

    <div class="clearfix"></div>


    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><a href="<?php echo base_url()?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Detail Data</h2>
          
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab1" class="nav nav-tabs bar_tabs right" role="tablist">
              <li role="presentation" class="active"><a href="#tab_content11" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Data Diri</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content22" role="tab" id="profile-tabb" data-toggle="tab" aria-controls="profile" aria-expanded="false">Data Keluarga</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content33" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Pengalaman</a>
              </li>
               <li role="presentation" class=""><a href="#tab_content44" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Pernyataan</a>
              </li>
               <li role="presentation" class=""><a href="#tab_content55" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Berkas</a>
              </li>
            </ul>
            <div id="myTabContent2" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content11" aria-labelledby="home-tab">
                 <h3>Data Diri</h3>
                    <hr><br>
                <table class="table table-bordered" id="tr_hr">
                  <?php foreach ($biodata as $pel) {
                     $age = date_diff(date_create($pel->tgl_lahir), date_create('now'))->y;
                  } ?>
                    <tr><td rowspan="17" width="30%">
                      <div class="">
                        <div class="image view view-first">
                          <?php 
                          if($foto >= 1)
                            { ?>
                                <?php 
                                  if($pel->berkas == ''){?>
                                   <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/user.png" alt="image" />
                                 <?php  } else{ ?>
                                  <img style="width: 100%; display: block;" src="http://192.168.10.10/Karir/asset/berkas/<?php echo $pel->berkas?>" alt="image" />
                                <?php } ?>
                              </div>
                              <div class="caption">
                               <?php 
                               if($pel->berkas == ''){
                                echo"<center>-</center>";
                              }else{ ?>
                                <center><p><a href="http://192.168.10.10/Karir/asset/berkas/<?php echo $pel->berkas?>" target="__blank"><?php echo $pel->berkas?></a></p></center>
                                <?php } ?>
                            <?php }else{?>
                              <img src="<?php echo base_url()?>images/user.png" class="card-img-top" alt="...">
                            <?php }
                          ?>
                          
                      </div>
                    </div>
                  </div>
                </td>

                <td colspan="2" bgcolor="#c0daf5">
                  <a href="<?php echo base_url()?>Down_ms/download_resume_pelamar_ms/<?php echo $pel->recid_pelamar ?>">(<span><i class="fa fa-download"></i></span> Download Word) </a>
               </td></tr>
                <tr><td>Nama Kandidat</td><td><?php echo $pel->nama_pelamar ?></td></tr>
                <tr><td >Tempat, Tanggal Lahir</td><td><?php echo "$pel->ptmp_lahir, "; ?><?php echo $newDate = date("d M Y", strtotime($pel->tgl_lahir)); echo " ($age thn)"; ?></td></tr>
                <tr><td>jenis Kelamin</td><td><?php echo $pel->pjenkel ?></td></tr>
                <tr><td>Golongan Darah</td><td><?php echo $pel->pgoldar ?></td></tr> 
                <tr><td>Agama</td><td><?php echo $pel->pagama ?></td></tr>
                <tr><td>Status Perkawinan</td><td><?php echo $pel->psts_kawin ?></td></tr>
                <tr><td>No Ktp</td><td><?php echo $pel->no_ktp ?></td></tr>
                <tr><td>Pendidikan</td><td><?php echo "$pel->ppendidikan  $pel->pjurusan"; ?></td></tr> 
                <tr><td>Instansi</td><td><?php echo "$pel->pinstansi"; ?></td></tr> 
                <tr><td>Tahun Lulus</td><td><?php echo "$pel->pthn_lulus"; ?></td></tr> 
                <tr><td>Alamat KTP</td><td><?php echo "$pel->palamat_ktp"; ?></td></tr> 
                <tr><td>Alamat Sekarang</td><td><?php echo "$pel->alamat"; ?></td></tr> 
                <tr><td>Telp / HP</td><td><?php echo "$pel->no_telp"; ?></td></tr> 
                <tr><td>Telp Alternatif</td><td><?php echo "$pel->ptelp_alt"; ?></td></tr> 
                <tr><td>Email</td><td><?php echo $pel->email ?></td>
                <tr><td>Hobi</td><td><?php 
                    if($pel->phobi == ''){
                      echo "-";
                    }else{
                      echo $pel->phobi ;
                    }
                  ?></td></tr>
              </table>
            </div>


            <!-- Keluarga -->
           
            <div role="tabpanel" class="tab-pane fade" id="tab_content22" aria-labelledby="profile-tab">
               <h3>Data Keluarga</h3>
                <hr><br>
               <table class="table" id="t_kel">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Hubungan Keluarga</th>
                        <th scope="col">Tempat, Tgl Lahir</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">Pendidikan Terakhir</th>
                        <th scope="col">Pekerjaan</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                     $no = 0;
                     foreach ($keluarga->result() as $kel) { ?>
                       <tr>
                        <td><?php echo $no+1 ?></td>
                        <td><?php echo $kel->pknama?></td>
                        <td><?php echo $kel->pkhub?></td>
                        <td><?php echo $kel->pktmp_lahir?>, <?php echo $kel->pktgl_lahir?> </td>
                        <td><?php echo $kel->pkjenkel?> </td>
                        <td><?php echo $kel->pkpendidikan?> </td>
                        <td><?php echo $kel->pkpekerjaan?> </td>
                      </tr>
                     <?php }

                     ?>
                    </tbody>
                  </table>

              </div>
              <!--/ Keluarga -->


              <!-- Pengalaman -->
              <div role="tabpanel" class="tab-pane fade" id="tab_content33" aria-labelledby="profile-tab">
                <h3>Pengalaman</h3>
                <hr><br>
               <div class="x_content">
                <ul class="list-unstyled timeline">
                  <!--  <a href="<?php echo base_url()?>Down_ms/download_karir_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Karir)</a><br> -->
                        <?php 
                            foreach ($pengalaman->result() as $alaman) {?>
                               <li>
                                <div class="block">
                                <div class="tags">
                                  <?php echo $alaman->thn_mulai?> - <?php echo $alaman->thn_akhir?>
                                </div>
                                <div class="block_content">
                                <h2 class="title"><b><?php echo $alaman->nama_perusahaan?></b></h2>
                                <p><b>Alamat : </b><?php echo $alaman->alamat_perusahaan ?><br> 
                                  <b>Telphone : </b><?php echo $alaman->telp_perusahaan ?></p>
                                <b>Posisi :</b> <?php echo $alaman->posisi ?><br>
                                <b>Pendapatan : </b> Rp. <?php  echo $alaman->pendapatan?></br>
                                <b>Job Desc  : </b><?php echo $alaman->job_desc ?></br>
                                <b>Fasilitas  : </b> <?php echo $alaman->fasilitas ?></br>
                                <b>Alasan Keluar : </b> <?php echo $alaman->alasan_keluar ?></br>
                                 </div>
                                </div>
                              </li>
                            <?php }?>
                      
                      </ul>
                    </div>
                  </div>
                  <!-- Pengalaman -->

                <!-- Pernyatan -->
               
               <div role="tabpanel" class="tab-pane fade" id="tab_content44" aria-labelledby="profile-tab">
                 <h3>Pernyataan</h3>
                    <hr><br>
                 <div class="x_content">
                  <?php 
                      if($cek_tanya >=1)
                      {
                        foreach ($pernyataan->result() as $tanya) { 
                          $recid_pernyataan = $tanya->recid_pernyataan;
                          $ppgaji = $tanya->ppgaji;
                          $ppfasilitas = $tanya->ppfasilitas;
                          $tgl_join = $tanya->tgl_join;
                          $sakit_keras = $tanya->sakit_keras;
                          $ppsakit = $tanya->ppsakit;
                          $dinas_luar = $tanya->dinas_luar;
                          $part_time = $tanya->part_time;
                          $referensi = $tanya->referensi;
                          $hub_referensi = $tanya->hub_referensi;
                          $kelebihan = $tanya->kelebihan;
                          $kekurangan = $tanya->kekurangan;
                          $info_dari = $tanya->info_dari;
                        }
                      }else{
                        $referensi = '';
                      }
                    ?> 

                    <form method="post" action="<?php echo base_url()?>Recruitment/update_pernyataan">
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Sallary yang Diharapkan</label>
                      <?php  if($cek_tanya >=1){ ?>
                         <input type="text" class="form-control" id="" placeholder="Sallary yang Diharapkan" name="ppgaji" value="<?php echo $ppgaji?>" readonly>
                         <input type="hidden" class="form-control" id="" placeholder="Sallary yang Diharapkan" name="recid_pernyataan" value="<?php echo $recid_pernyataan?>">
                      <?php }else{ ?>
                           <input type="text" class="form-control" id="" placeholder="Sallary yang Diharapkan" name="ppgaji">
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label">Fasilitas Yang Diharapkan</label>
                      <?php  if($cek_tanya >=1){ ?>
                         <textarea class="form-control" id="" rows="3" name="ppfasilitas" readonly><?php echo $ppfasilitas?></textarea>
                      <?php }else{ ?>
                           <textarea class="form-control" id="" rows="3" name="ppfasilitas" readonly></textarea>
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label for="exampleFormControlInput1" class="form-label">Dapat Bergabung Pada</label>
                       <?php  if($cek_tanya >=1){ ?>
                          <input type="text" class="form-control" id="" placeholder="Dapat Bergabung Pada" name="tgl_join" value="<?php echo $tgl_join?>" readonly>
                      <?php }else{ ?>
                           <input type="text" class="form-control" id="" placeholder="Dapat Bergabung Pada" name="tgl_join" readonly>
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label">Pernah Menderita Sakit Keras?</label>
                       <?php  if($cek_tanya >=1){ ?>
                         <?php 
                          if($sakit_keras == 'Ya')
                          {?>
                            <div class="row">
                             <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox1" value="Ya" checked="checked" name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="Tidak"  name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          </div>
                          <?php }else{?>
                            <div class="row">
                             <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox1" value="Ya"  name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="Tidak" checked="checked"  name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          </div>
                          <?php }?>
                      <?php }else{ ?>
                           <div class="row">
                             <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox1" value="Ya"  name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="Tidak"  name="sakit_keras" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          </div>
                      <?php } ?>
                    </div>
                      <div class="mb-3">
                      <label for="" class="form-label">Sebutkan Penyakit Keras yang Diderita</label>
                       <?php  if($cek_tanya >=1){ ?>
                          <input type="text" class="form-control" id="" placeholder="Sebutkan Sakit Keras yang Diderita" name="ppsakit" value="<?php echo $ppsakit?>" readonly>
                      <?php }else{ ?>
                            <input type="text" class="form-control" id="" placeholder="Sebutkan Sakit Keras yang Diderita" name="ppsakit" readonly>
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label">Bersedia ditugaskan keluar kota?</label>
                      <?php  if($cek_tanya >=1){ ?>
                         <?php 
                          if($sakit_keras == 'Ya')
                          {?>
                            <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked="checked" value="Ya"  name="dinas_luar" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="Tidak" name="dinas_luar" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          <?php }else{?>
                            <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="Ya"  name="dinas_luar" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked="checked" value="Tidak" name="dinas_luar" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          <?php }?>
                      <?php }else{ ?>
                           <div class="col-sm">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="inlineCheckbox1" value="Ya"  name="dinas_luar" disabled="disabled">
                              <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="inlineCheckbox2" value="Tidak" name="dinas_luar" disabled="disabled">
                              <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                            </div>
                          </div>
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label">Memiliki Pekerjaan sampingan / part time?</label>
                       <?php  if($cek_tanya >=1){ ?>
                         <?php 
                          if($part_time == 'Ya')
                          {?>
                            <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked="checked" value="Ya"  name="part_time" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="Tidak" name="part_time" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          <?php }else{?>
                            <div class="col-sm">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="Ya"  name="part_time" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked="checked" value="Tidak" name="part_time" disabled="disabled">
                                <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                              </div>
                            </div>
                          <?php }?>
                      <?php }else{ ?>
                           <div class="col-sm">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="inlineCheckbox1" value="Ya"  name="part_time" disabled="disabled">
                              <label class="form-check-label" for="inlineCheckbox1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="inlineCheckbox2" value="Tidak" name="part_time" disabled="disabled">
                              <label class="form-check-label" for="inlineCheckbox2">Tidak</label>
                            </div>
                          </div>
                      <?php } ?>
                    </div>
                  <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Referensi <span>(Apakah anda memiliki keluarga atau kenalan di PT. Chitose Internasional Tbk ?)</span></label>
                    <div class="col-sm">
                      <input type="text" name="referensi" class="form-control" value="<?php echo $referensi?>" readonly>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="" class="form-label">Hubungan Dengan Referensi</label>
                    <select class="form-select form-control" aria-label="Default select example" name="hub_referensi" readonly>
                       <?php 
                       $hub_ref = ["Orang Tua", "Keluarga Inti", "Keluarga Jauh", "Teman", "Lainnya"];
                        if($cek_tanya >=1){ 
                          for($pp = 0; $pp < count($hub_ref); $pp++)
                          {
                            if($hub_ref[$pp] == $hub_referensi){?>
                              <option value="<?php echo $hub_ref[$pp] ?>" selected="selected"><?php echo $hub_ref[$pp] ?></option>
                            <?php }else{?>
                              <option value="<?php echo $hub_ref[$pp] ?>"><?php echo $hub_ref[$pp] ?></option>
                            <?php }
                          }
                        }else{ ?>
                         <option selected>-- Pilih --</option>
                         <option value="Orang Tua">Orang Tua</option>
                         <option value="Keluarga Inti">Keluarga Inti</option>
                         <option value="Keluarga Jauh">Keluarga Jauh</option>
                         <option value="Teman">Teman / Kolega</option>
                         <option value="Lainnya">Lainnya</option>
                        <?php } ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="" class="form-label">Tuliskan 3 Kelebihan yang Anda Miliki</label>
                    <?php if($cek_tanya >=1){ ?>
                      <textarea class="form-control" id="" rows="3" name="kelebihan" readonly><?php echo $kelebihan?></textarea>
                    <?php }else{?>
                      <textarea class="form-control" id="" rows="3" name="kelebihan" readonly></textarea>
                    <?php }?>
                  </div>
                  <div class="mb-3">
                    <label for="" class="form-label">Tuliskan 3 Kekurangan yang Anda Rasakan</label>
                     <?php if($cek_tanya >=1){ ?>
                      <textarea class="form-control" id="" rows="3" name="kekurangan" readonly><?php echo $kekurangan?></textarea>
                    <?php }else{?>
                     <textarea class="form-control" id="" rows="3" name="kekurangan" readonly></textarea>
                    <?php }?>
                  </div>
                  <div class="mb-3">
                    <label for="" class="form-label">Mengetahuih Lowongan Dari</label>
                     <select class="form-select form-control" aria-label="Default select example" name="info_dari" readonly>
                       <?php 
                       $info = ["Online", "Offline"];
                        if($cek_tanya >=1){ 
                          for($in = 0; $in < count($info); $in++)
                          {
                            if($info[$in] == $info_dari){?>
                              <option value="<?php echo $info[$in] ?>" selected="selected"><?php echo $info[$in] ?></option>
                            <?php }else{?>
                              <option value="<?php echo $info[$in] ?>"><?php echo $info[$in] ?></option>
                            <?php }
                          }
                        }else{ ?>
                         <option selected>-- Pilih --</option>
                         <option value="Online">Online</option>
                         <option value="Offline">Offline</option>
                        <?php } ?>
                    </select>
                  </div>


                    </div>
                  </div>
              <!--/ Pernyataan -->
            

             
               <!-- Berkas -->
               <div role="tabpanel" class="tab-pane fade" id="tab_content55" aria-labelledby="profile-tab">
                 <div class="x_content">
                   <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Jenis Berkas</th>
                        <th scope="col">File</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                        foreach ($berkas->result() as $ber) { ?>
                          <tr>
                            <td><?php echo $no++?></td>
                            <td><?php echo $ber->jenis_berkas?></td>
                            <td><a target="_blank" href = "http://192.168.10.10/Karir/asset/berkas/<?php echo $ber->berkas?>"><?php echo $ber->berkas?></a></td>
                          </tr>
                        <?php }?>
                    </tbody>
                  </table>

                    </div>
                  </div>
              <!--/ Berkas -->
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      </div>
    </div>
        <!-- /page content
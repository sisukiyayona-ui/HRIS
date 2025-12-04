<!-- page content -->
<?php $role=$this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Karyawan</h3>
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
              <li role="presentation" class="active"><a href="#tab_content11" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Personal Data</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content22" role="tab" id="profile-tabb" data-toggle="tab" aria-controls="profile" aria-expanded="false">Tanggungan</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content33" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Karir</a>
              </li>
            </ul>
            <div id="myTabContent2" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content11" aria-labelledby="home-tab">
                <table class="table table-bordered" id="tr_hr">
                  <?php foreach ($karyawan as $data) {
                    # code...
                  } ?>
                  <?php if($role == '1' or $role == '5'){ ?>
                    <tr><td rowspan="34" width="30%">
                    <?php } else{ ?>
                      <tr><td rowspan="23" width="30%">
                      <?php } ?>
                      <div class="">
                        <div class="image view view-first">
                          <?php 
                          if($data->foto == ''){?>
                           <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/user.png" alt="image" />
                         <?php  } else{ ?>
                          <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" alt="image" />
                        <?php } ?>
                      </div>
                      <div class="caption">
                       <?php 
                       if($data->foto == ''){
                        echo"<center>-</center>";
                      }else{ ?>
                        <center><p><a href="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" target="__blank"><?php echo $data->foto?></a></p></center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>

                <td colspan="2" bgcolor="#c0daf5"><a href="<?php echo base_url()?>Karyawan/export/<?php echo $data->recid_karyawan ?>"><?php echo "<b>$data->sts_aktif ($data->sts_jbtn) - $data->nik</b>"; ?>
                <?php if($role == '1' or $role == '5'){ ?>
                <a href="<?php echo base_url()?>Karyawan/download/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Pdf) </a> <?php } else{ ?>
                  <a href="<?php echo base_url()?>Karyawan/download2/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Pdf) </a>
               <?php }?>
               <a href="<?php echo base_url()?>Karyawan/download_ms/<?php echo $data->recid_karyawan ?>">(<span><i class="fa fa-download"></i></span> Download Word) </a>
              </td></tr>
                <tr><td>Nama Karyawan</td><td><?php echo $data->nama_karyawan ?></td></tr>
                <tr><td >Tempat, Tanggal Lahir</td><td><?php echo "$data->tmp_lahir, "; ?><?php echo $newDate = date("d M Y", strtotime($data->tgl_lahir)); foreach ($usia as $umur) {}  echo " ($umur->umur thn)"; ?></td></tr>
                <tr><td>jenis Kelamin</td><td><?php echo $data->jenkel ?></td></tr>
                <tr><td>Golongan Darah</td><td><?php echo $data->gol_darah ?></td></tr> 
                <tr><td>Agama</td><td><?php echo $data->agama ?></td></tr>
                <tr><td>Status Perkawinan</td><td><?php echo $data->sts_nikah ?></td></tr>
                <tr><td>Status Penunjang</td><td>
                  <?php if($data->sts_penunjang == 'TK'){
                    echo "Tidak Kawin";
                  } else if($data->sts_penunjang == 'K0'){
                    echo "Kawin, Anak 0";
                  } else if($data->sts_penunjang == 'K1'){
                    echo "Kawin, Anak 1";
                  } else if($data->sts_penunjang == 'K2'){
                    echo "Kawin, Anak 2";
                  }else{
                    echo "Kawin, Anak 3";
                  }?></td></tr>
                  <tr><td>No Ktp</td><td><?php echo $data->no_ktp ?></td></tr>
                  <tr><td>No NPWP</td><td><?php echo $data->no_npwp ?></td></tr>
                  <tr><td>Nomor Jamsostek</td><td>
                    <?php 
                    if($data->no_jamsos == ''){
                      echo "-";
                    }else{
                      echo $data->no_jamsos ;
                    }
                    ?>
                  </td></tr>
                   <tr><td>Nomor BPJS Kesehatan</td><td>
                    <?php 
                    if($data->no_bpjs_kes == ''){
                      echo "-";
                    }else{
                      echo $data->no_bpjs_kes ;
                    }
                    ?>
                  </td></tr>
                   <tr><td>Nomor BPJS Tenaga Kerja</td><td>
                    <?php 
                    if($data->no_bpjs_tk == ''){
                      echo "-";
                    }else{
                      echo $data->no_bpjs_tk ;  
                    }
                    ?>
                  </td></tr>
                  <tr><td>Pendidikan</td><td><?php echo "$data->pendidikan  $data->jurusan"; ?></td></tr> 
                  <tr><td>Alamat KTP</td><td><?php echo "$data->alamat_ktp"; ?></td></tr> 
                  <tr><td>Alamat Sekarang</td><td><?php echo "$data->alamat_skrg"; ?></td></tr> 
                  <tr><td>Telp / HP</td><td><?php echo "$data->telp1"; ?></td></tr> 
                  <tr><td>Telp Alternatif (Keluarga)</td><td> 
                    <?php 
                    if($data->telp2 == ''){
                      echo "-";
                    }else{
                      echo $data->telp2 ;
                    }
                    ?>
                  </td></tr> 
                  <tr><td>Hobi</td><td><?php 
                    if($data->hobi == ''){
                      echo "-";
                    }else{
                      echo $data->hobi ;
                    }
                    ?></td></tr>
                  <tr><td>Tanggal Mulai Kerja</td><td>
                    <?php
                    if($data->tgl_m_kerja == null){
                      echo "-";
                    }else if($data->tgl_m_kerja == "0000-00-00"){
                     echo "-";
                   }else{
                    echo $newDate = date("d M Y", strtotime($data->tgl_m_kerja));
                    $diff  = date_diff( date_create($data->tgl_m_kerja), date_create() );
                    echo $diff->format(' ( %Y tahun %m bulan %d hari )');
                  }
                  ?>
                </td></tr>
                <tr><td>Tanggal Akhir Kerja</td><td>
                  <?php 
                  if($data->tgl_a_kerja == null){
                    echo "-";
                  }else if($data->tgl_a_kerja == "0000-00-00") {
                    echo "-";
                  }else if($data->tgl_a_kerja == "9999-12-31") {
                    echo "-";
                  }else{
                    echo $newDate = date("d M Y", strtotime($data->tgl_a_kerja));
                  } 
                  ?> 
                </td></tr> 
                <tr><td>Scan BPJS Kesehatan</td><td>
                 <div class="col-md-8">
                  <div class="thumbnail">
                    <div class="image view view-first">
                      <?php  if($data->scan_bpjs_kes == ''){?> 
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
                      <?php }else{ ?>
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes?>" alt="image" />
                      <?php } ?>
                    </div>
                    <div class="caption"> 
                      <?php 
                      if($data->scan_bpjs_kes == ''){
                        echo"<center>-</center>";
                      }else{ ?>
                        <center><p><a href="<?php echo base_url()?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes?>" target="__blank"><?php echo $data->scan_bpjs_kes?></p></center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td></tr> 
                <tr><td>Scan PBJS Tenaga Kerja</td><td> <div class="col-md-8">
                  <div class="thumbnail">
                    <div class="image view view-first">
                      <?php  if($data->scan_bpjs_tk == ''){?> 
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
                      <?php }else{ ?>
                        <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk?>" alt="image" />
                      <?php } ?>
                    </div>
                    <div class="caption"> 
                      <?php 
                      if($data->scan_bpjs_tk == ''){
                        echo"<center>-</center>";
                      }else{ ?>
                        <center><p><a href="<?php echo base_url()?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk?>" target="__blank"><?php echo $data->scan_bpjs_tk?></p></center>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td></tr>
                <?php if($role == '1' or $role == '5'){ ?>
                  <tr><td colspan="2" bgcolor="#c0daf5"><b><?php echo "Data Upah"; ?></b></td></tr> 
                  <tr><td>LSPMI</td><td><?php echo $data->lspmi ?></td></tr>
                  <tr><td>Tunjangan Pensiun</td><td><?php echo $data->pensiun ?></td></tr>
                  <tr><td>Gaji Pokok</td><td><?php echo $data->gapok ?></td></tr>
                  <tr><td>Tunjangan Jabatan</td><td><?php echo $data->t_jabatan ?></td></tr>
                  <tr><td>Tunjangan Masa Kerja</td><td><?php echo $data->t_prestasi ?></td></tr>
                  <tr><td>Tunjangan Jenis Pekerjaan</td><td><?php echo $data->t_jen_pek ?></td></tr>
                  <tr><td>Jemputan</td><td><?php echo $data->ljemputan ?></td></tr>
                  <tr><td>Akun Bank</td><td><?php echo $data->acc_bank ?></td></tr>
                  <tr><td>Nama Bank</td><td><?php echo $data->nama_bank ?></td></tr>
                  <tr><td>Karyawan Bulanan</td><td><?php echo $data->bulanan ?></td></tr>
                <?php } ?>
              </table>
            </div>
            <!-- TUNJANGAN -->
            <div role="tabpanel" class="tab-pane fade" id="tab_content22" aria-labelledby="profile-tab">
               <a href="<?php echo base_url()?>Karyawan/download4/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download All) </a>
               <table class="table table-bordered">
                    <thead>
                    <th>Nama Tanggungan</th>
                    <th>Hubungan Keluarga</th>
                    <th>Status Tanggungan</th>
                    <th><center>Aksi</center></th>
                  </thead>
              <?php   $no = 1; foreach ($tunjangan as $data) { ?>
                  <tbody>
                    <tr><td><?php echo  $data->nama_tunjangan ?></td>
                    <td><?php echo  $data->hub_keluarga ?></td>
                    <td><?php if($data->sts_tunjangan == "Yes"){echo "Ditanggung";}else{echo "Tidak Ditanggung";}  ?></td>
                    <td><center><?php if($role=='1' or $role=='2' or $role=='5' ){ ?>
                      <a href="<?php echo base_url()?>Karyawan/tunjangan_update/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-info btn-xs"><i class="fa fa-edit"></i></button></a> 
                       <a href="<?php echo base_url()?>Karyawan/tunjangan_detail/<?php echo $data->recid_tunjangan ?>"><button class="btn btn-success btn-xs"><i class="fa fa-search-plus"></i></button></a>
                       <a href="<?php echo base_url()?>Karyawan/download3/<?php echo $data->recid_tunjangan ?>" target="__blank"><button class="btn btn-primary btn-xs"><i class="fa fa-download"></i></button></a>
                       <a href="<?php echo base_url()?>Karyawan/tunjangan_delete/<?php echo $data->recid_tunjangan ?>" target="__blank"><button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></a>
                      <?php } ?>
                   </center> </td></tr>
                  </tbody>
                <?php } ?>
                  </table>
              </div>

              <!--/ TUNJANGAN -->
              <!-- KARIR -->
              <div role="tabpanel" class="tab-pane fade" id="tab_content33" aria-labelledby="profile-tab">
               <div class="x_content">
                <ul class="list-unstyled timeline">
                   <a href="<?php echo base_url()?>Karyawan/download5/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Karir)</a><br>
                  <?php foreach ($karyawan as $data2 ) {
                    $aktif = $data2->sts_aktif;
                  } ?>
                  <?php foreach ($karir as $data) { ?>
                    <li>
                      <div class="block">
                        <div class="tags">
                          <a href="#" class="tag">
                            <span><?php echo $data->kategori ?></span>
                          </a>
                        </div>
                        <div class="block_content">
                          <h2 class="title">
                            <p>
                              <?php if($data->kategori == 'Akhir'){
                               echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                              }else{ ?>
                              <?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d 
                              <?php if($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00'){echo "Sekarang";}else{ echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));} ?> |
                              <?php } ?>  
                              <?php if($role == '1' or $role=='2'){ ?>
                                <?php if($data->no_perjanjian == ''){ ?>
                                  <a href="<?php echo base_url()?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>  
                                  <a href="<?php echo base_url()?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><i class="fa fa-trash"></i></a>
                               <?php  }else{ ?>
                                <a href="<?php echo base_url()?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>  
                                <a href="<?php echo base_url()?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><i class="fa fa-trash"></i></a>
                                <?php }?>
                                <?php } ?></p>
                              </h2>
                              <div class="byline">
                                <?php if($data->no_perjanjian == ''){echo "Tidak Ada no SK";}else{ ?><a href="<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php  echo $data->no_perjanjian;  } ?> </a>
                                 <?php if($role == '1' or $role == '2'){ ?>
                                  |  <a href="<?php echo base_url()?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                                 <?php } ?>
                              </div>
                              <p class="excerpt">
                                <table class="table">
                                  <tr>
                                    <td>Jenis Karir</td><td>:</td><td><?php echo $data->kategori ?></td></tr>
                                    <tr><td>Bagian</td><td>:</td><td><?php echo $data->nama_bag ?></td></tr>
                                    <td>Jabatan</td><td>:</td><td><?php echo  strtoupper($data->nama_jbtn)?> - <?php echo  strtoupper($data->sts_jbtn)?> (<?php if($data->bulanan == 'Ya'){echo "Bulanan";}else{ echo "Harian";} ?>)</td></tr>
                                    <td>Keterangan</td><td>:</td><td><?php echo "$data->note";?></td></tr>
                                  </table>
                                </p>
                              </div>
                            </div>
                          </li>
                        <?php } ?>
                         <?php foreach ($karyawan as $data2 ) {
                    $aktif = $data2->sts_aktif;
                  } ?>
                      </ul>

                    </div>
              
              <!-- TRAINING -->

                    <div class="x_content">
                <ul class="list-unstyled timeline">
                   <a href="<?php echo base_url()?>Karyawan/download6/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Training)</a><br>
                  <?php foreach ($karyawan as $data2 ) {
                    $aktif = $data2->sts_aktif;
                  } ?>
                  <?php foreach ($training as $data) { ?>
                    <li>
                      <div class="block">
                        <div class="tags">
                          <a href="#" class="tag">
                            <span><?php echo $data->kategori ?></span>
                          </a>
                        </div>
                        <div class="block_content">
                          <h2 class="title">
                            <p><?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d 
                              <?php if($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00'){echo "Sekarang";}else{ echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));} ?> |  
                              <?php if($role == '1' or $role=='2'){ ?>
                                <?php if($data->no_perjanjian == ''){ ?>
                                  <a href="<?php echo base_url()?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                               <?php  }else{ ?>
                                <a href="<?php echo base_url()?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>
                                <?php }?>
                                <?php } ?></p>
                              </h2>
                              <div class="byline">
                                <?php if($data->no_perjanjian == ''){echo "Tidak Ada no SK";}else{ ?><a href="<?php echo base_url()?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php  echo $data->no_perjanjian;  } ?></a>
                              </div>
                              <p class="excerpt">
                                <table class="table">
                                  <tr>
                                    <td>Jenis Karir</td><td>:</td><td><?php echo $data->kategori ?></td></tr>
                                    <tr><td>Topik Training</td><td>:</td><td><?php echo ucwords($data->judul_training) ?></td></tr>
                                    <tr><td>Tempat</td><td>:</td><td><?php echo  ucwords($data->tempat_training)?></td></tr>
                                    <tr><td>Keterangan</td><td>:</td><td><?php echo ucwords($data->note)?></td></tr>
                                  </table>
                                </p>
                              </div>
                            </div>
                          </li>
                        <?php } ?>
                         <?php foreach ($karyawan as $data2 ) {
                    $aktif = $data2->sts_aktif;
                  } ?>
                      </ul>

                    </div>
                  </div>
                  <!-- KARIR -->
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="clearfix"></div>

      </div>
    </div>
        <!-- /page content -->
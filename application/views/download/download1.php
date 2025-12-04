
  <table style=" width:100%; font-size: 12pt; padding: 5px; border-collapse: collapse" cellPadding="7">
      <?php foreach ($karyawan as $data) {
                    # code...
      } ?>
        <tr><td rowspan="46" style="vertical-align: top;" >
          <div class="">
            <div class="image view view-first">
              <?php 
              if($data->foto == ''){?>
               <img style="width: 200px;" src="<?php echo base_url()?>images/user.png" alt="image" />
             <?php  } else{ ?>
              <img style="width: 200px; display: block;" src="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" alt="image" />
            <?php } ?>
          </div>
        </div>
      </div>
    </td>
    <td bgcolor="#c0daf5">Status </td><td bgcolor="#c0daf5"><?php echo "<b>$data->sts_aktif ($data->sts_jabatan)</b>"; ?></td></tr>
    <tr><td>Nama Karyawan</td><td><?php echo $data->nama_karyawan ?></td></tr>
    <tr><td>NIK</td><td><?php echo $data->nik ?></td></tr>
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
      <tr><td>Scan KTP</td><td>
       <div class="col-md-8">
        <div class="thumbnail">
          <div class="image view view-first">
            <?php  if($data->scan_ktp == ''){?> 
              <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
            <?php }else{ ?>
              <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/ktp/<?php echo $data->scan_ktp?>" alt="image" />
            <?php } ?>
          </div>
        </div>
      </div>
    </td></tr> 
    <tr><td>No Kartu Keluarga</td><td><?php echo $data->no_kk ?></td></tr>
      <tr><td>Scan Kartu Keluarga</td><td>
       <div class="col-md-8">
        <div class="thumbnail">
          <div class="image view view-first">
            <?php  if($data->scan_kk == ''){?> 
              <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
            <?php }else{ ?>
              <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/kk/<?php echo $data->scan_kk?>" alt="image" />
            <?php } ?>
          </div>
        </div>
      </div>
    </td></tr> 
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
      <tr><td>Pendidikan</td><td><?php echo "$data->pendidikan  $data->jurusan"; ?></td></tr> 
      <tr><td>Alamat KTP</td><td><?php echo "$data->alamat_ktp"; ?></td></tr> 
      <tr><td>Alamat Sekarang</td><td><?php echo "$data->alamat_skrg"; ?></td></tr> 
      <tr><td>Telp / HP</td><td><?php echo "$data->telp1"; ?></td></tr> 
      <tr><td>IMEI 1</td><td><?php echo "$data->imei1"; ?></td></tr> 
      <tr><td>Telp Alternatif (Keluarga)</td><td> 
        <?php 
        if($data->telp2 == ''){
          echo "-";
        }else{
          echo $data->telp2 ;
        }
        ?>
      </td></tr> 
       <tr><td>IMEI 2</td><td> 
        <?php 
        if($data->imei2 == ''){
          echo "-";
        }else{
          echo $data->imei2 ;
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
      <tr><td>Tanggal Masuk Trisula</td><td>
        <?php
        if($data->tgl_trisula == null){
          echo "-";
        }else if($data->tgl_trisula == "0000-00-00"){
         echo "-";
       }else{
        echo $newDate = date("d M Y", strtotime($data->tgl_trisula));
        $diff  = date_diff( date_create($data->tgl_trisula), date_create() );
        echo $diff->format(' ( %Y tahun %m bulan %d hari )');
      }
      ?>
    </td></tr>
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
     <tr><td>Nomor BPJS Kesehatan</td><td>
        <?php 
        if($data->no_bpjs_kes == ''){
          echo "-";
        }else{
          echo $data->no_bpjs_kes ;
        }
        ?>
      </td></tr>
    <tr><td>Scan BPJS Kesehatan</td><td>
     <div class="col-md-8">
      <div class="thumbnail">
        <div class="image view view-first">
          <?php  if($data->scan_bpjs_kes == ''){?> 
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
          <?php }else{ ?>
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes?>" alt="image" />
          <?php } ?>
        </div>
        </div>
      </div>
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
    <tr><td>Scan PBJS Tenaga Kerja</td><td> <div class="col-md-8">
      <div class="thumbnail">
        <div class="image view view-first">
          <?php  if($data->scan_bpjs_tk == ''){?> 
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
          <?php }else{ ?>
            <img style="width:100px; display: block;" src="<?php echo base_url()?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk?>" alt="image" />
          <?php } ?>
        </div>
        </div>
      </div>
    </td></tr>
     <tr><td>Nomor AIA</td><td>
        <?php 
        if($data->no_aia == ''){
          echo "-";
        }else{
          echo $data->no_aia ;
        }
        ?>
      </td></tr>
    <tr><td>Scan AIA</td><td>
     <div class="col-md-8">
      <div class="thumbnail">
        <div class="image view view-first">
          <?php  if($data->scan_aia == ''){?> 
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
          <?php }else{ ?>
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/aia/<?php echo $data->scan_aia?>" alt="image" />
          <?php } ?>
        </div>
        </div>
      </div>
    </td></tr> 
     <tr><td>Nomor Asuransi Kesehatan Lain</td><td>
        <?php 
        if($data->no_askes == ''){
          echo "-";
        }else{
          echo $data->no_askes ;
        }
        ?>
      </td></tr>
    <tr><td>Scan Asuransi Kesehatan Lain</td><td>
     <div class="col-md-8">
      <div class="thumbnail">
        <div class="image view view-first">
          <?php  if($data->scan_askes == ''){?> 
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/inbox.jpg" alt="image" />
          <?php }else{ ?>
            <img style="width: 100px; display: block;" src="<?php echo base_url()?>images/askes/<?php echo $data->scan_askes?>" alt="image" />
          <?php } ?>
        </div>
        </div>
      </div>
    </td></tr> 

    <tr><td>Profile DISC</td><td>
       <?php 
        if($data->profile_disc == ''){
          echo "-";
        }else{
          echo $data->profile_disc ;
        }
        ?>
    </td></tr>
    <tr><td>Profile Personality</td><td>
      <?php 
        if($data->profile_type == ''){
          echo "-";
        }else{
          echo $data->profile_type ;
        }
        ?>
    </td></tr>

       <tr><td colspan="2" bgcolor="#c0daf5"><b><?php echo "Data Upah"; ?></b></td></tr> 
      <tr><td>LSPMI</td><td><?php echo $data->lspmi ?></td></tr>
      <tr><td>Tunjangan Pensiun</td><td><?php echo $data->pensiun ?></td></tr>
      <tr><td>Gaji Pokok</td><td><?php echo $data->gapok ?></td></tr>
      <tr><td>Tunjangan Jabatan</td><td><?php echo $data->t_jabatan ?></td></tr>
      <tr><td>Tunjangan Prestasi</td><td><?php echo $data->t_prestasi ?></td></tr>
      <tr><td>Tunjangan Jenis Pekerjaan</td><td><?php echo $data->t_jen_pek ?></td></tr>
      <tr><td>Jemputan</td><td><?php echo $data->ljemputan ?></td></tr>
      <tr><td>Akun Bank</td><td><?php echo $data->acc_bank ?></td></tr>
      <tr><td>Nama Bank</td><td><?php echo $data->nama_bank ?></td></tr>
      <tr><td>Karyawan Bulanan</td><td><?php echo $data->bulanan ?></td></tr>
  </table>
</div>
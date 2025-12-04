 
<h4>Personal Data </h4><hr>
    <table style="font-size: 22pt; padding: 5px;" cellpadding="5" cellspacing="5px">
      <?php foreach ($karyawan as $data) {
                    # code...
      } ?>
        <tr><td rowspan="34" width="30%" style="vertical-align: top;" >
          <div class="">
            <div class="image view view-first">
              <?php 
              if($data->foto == ''){?>
               <img style="width: 100%;" src="<?php echo base_url()?>images/user.png" alt="image" />
             <?php  } else{ ?>
              <img style="width: 100%; display: block;" src="<?php echo base_url()?>images/foto/<?php echo $data->foto?>" alt="image" />
            <?php } ?>
          </div>
        </div>
      </div>
    </td>
    <td colspan="2" bgcolor="#c0daf5" ><?php echo "<b>$data->sts_aktif ($data->sts_jbtn) - $data->nik</b>"; ?></td></tr>
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
        </div>
      </div>
    </td></tr>
  </table>
</div>
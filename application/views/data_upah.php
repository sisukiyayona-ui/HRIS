
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Upah Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        Data Upah Karyawan
        <div class="x_content">
          <!--Add content to the page ...-->
          <!-- Content Table -->
          <div class="table-responsive">
          <table id="t_kar" class="table table-striped table-bordered">
           <thead>
            <tr>
              <th><center>Nik</center></th>
              <th><center>Nama Karyawan</center></th>
              <th><center>Struktur</center></th>
              <th><center>Bagian</center></th>
              <th><center>Jabatan</center></th>
              <th><center>Status</center></th>
              <th><center>Masa Kerja</center></th>
              <th><center>Gaji</center></th>
            </tr>
          </thead>


          <tbody>
           <?php 
           foreach ($karyawan as $data) {
            if($data->recid_bag == 0){
              $nama_bag = "-";
            }else{
              $nama_bag = $data->nama_bag;
            }

            if($data->recid_jbtn == 0){
              $nama_jbtn = "-";
            }else{
              $nama_jbtn = $data->nama_jbtn;
            }
            echo "
            <tr>
            <td>$data->nik</td>
            <td>$data->nama_karyawan</td>
            <td>$data->nama_struktur</td>
            <td>$nama_bag</td>
            <td>$nama_jbtn</td>
            <td>$data->sts_penunjang</td>
            <td>";
                    if($data->tgl_m_kerja == null){
                      echo "-";
                    }else if($data->tgl_m_kerja == "0000-00-00"){
                     echo "-";
                   }else{
                    // echo $newDate = date("d M Y", strtotime($data->tgl_m_kerja));
                    $diff  = date_diff( date_create($data->tgl_m_kerja), date_create() );
                    echo $diff->format(' %Y thn %m bln %d hari');
                  }
                  echo "</td>
            <td>$data->gaji</td>";
          }
          ?>
          
        </tbody>
      </table>
    </div>
      <!--/ Content Table -->
    </div>
  </div>
</div>
</div>
</div>
</div>
        <!-- /page content
<?php  $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Kehadiran Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rekap Kehadiran Karyawan <?php echo ucfirst($status)?> Tanggal <?php echo date('d M Y')?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                    <table id="t_hadir" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
                          <th>Jabatan</th>
                          <th>Tanggal Izin</th>
                          <th>Jenis</th>
                          <th>Jam Masuk</th>
                          <th>Jam Keluar</th>
                          <th>Status</th>
                        </tr>
                      </thead>


                      <tbody>
                       <?php 
                       $no = 0;
                       foreach ($absen->result() as $data) {?>
                        <tr>
                        <td><?php echo $no = $no+1?></td>
                        <td><?php echo $data->nik ?></td>
                        <td><?php echo $data->nama_karyawan ?></td>
                        <td><?php echo $data->indeks_hr ?></td>
                        <td><?php echo $data->indeks_jabatan ?></td>
                        <td><?php echo $data->tgl_izin ?></td>
                        <td><?php echo $data->jenis?></td>
                        <td><?php echo $data->jam_in ?></td>
                        <td><?php echo $data->jam_out ?></td>
                        <td><?php echo $data->keterangan ?></td>
                        <?php } ?> <!-- looping foreach -->
                        </tr>
                      
                    </tbody>
                  </table>
                  <!--/ Content Table -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<script type="application/javascript">  
   $(document).ready(function() {
       var table = $('#t_hadir').DataTable( {
         "responsive":false,
      "ordering": false,
       // "order": [[ 2, "desc" ]],
       "paging":   true,
       "pageLength": 30,
      dom: 'Bfrtip',
      buttons: [
      'excel', 'pdf', 'print'
      ],
    } );
} );
     /** After windod Load */  
     $(window).bind("load", function() {  
       window.setTimeout(function() {  
         $(".alert").fadeTo(500, 0).slideUp(500, function() {  
           $(this).remove();  
           <?php unset($_SESSION['sukses']); ?>
           <?php unset($_SESSION['eror']); ?>
           <?php unset($_SESSION['warning']); ?>
         });  
       }, 3000);  
     });  
     
     function updateData(id)
     {
      jenis_id = "jenis"+id;
      jenis = document.getElementById(jenis_id).value;
      // alert(jenis);
      $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/update_absen", // Isi dengan url/path file php yang dituju
            data: {hadir_recid : id, jenis_absen:jenis}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
              // set isi dari combobox kota
              // lalu munculkan kembali combobox kotanya
              // $("#karyawan").html(response.list_kota).show();
              location.reload();
          },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
          }
      });
     }

     function getData()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      var table = $('#t_hadir').DataTable();
      table.destroy();
      var table = $('#t_hadir').DataTable( {
       "responsive":false,
      "ordering": false,
       // "order": [[ 2, "desc" ]],
       "paging":   true,
       "pageLength": 30,
      dom: 'Bfrtip',
      buttons: [
      'excel', 'pdf', 'print'
      ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Absen/get_hadir_periode",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai},
        },
      });
     }
   </script>
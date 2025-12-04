
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $menu ?></h3>
        <input type="hidden" id="boo" value="<?php echo $boo?>">
      </div>
    </div>

    <div class="clearfix"></div> 
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <form method="post">
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Tanggal<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                 <div class='input-group date' id='myDatepicker3'>
                  <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d')?>"  />
                  <span class="input-group-addon">
                   <span class="glyphicon glyphicon-calendar"></span>
                 </span>
               </div>
             </div>
           </div>
            <div class="form-group">
              <div class="col-md-2">
               <button id="send" type="button" class="btn btn-success" onclick="getAllData();">Cari</button>
             </div>
           </div>
         </form>
          </div>
        <div class="x_content">
          <!--Add content to the page ...-->
          <!-- Content Table -->
          <div class="table-responsive">
          <table id="rekap_absen" class="table table-striped table-bordered">
           <thead>
            <tr>
              <th><center>No</center></th>
              <th><center>Bagian</center></th>
              <th><center>Admin Bagian</center></th>
              <?php 
                if($boo == "Belum")
                {?>
                  <th><center>Aksi</center></th>
                <?php }
              ?>
            </tr>
          </thead>

          <tbody>
          
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
        <!-- /page content -->


<script>
  $(document).ready(function() {
    boo = document.getElementById('boo').value;
    if(boo == "Sudah")
    {
      getAllData2();
    }else{
      getAllData();
    }
  } );

  function getData(no)
     {
      bag = "bagian"+no;
      tgl_mulai = document.getElementById("tgl_mulai").value;
      bagian = document.getElementById(bag).value;
      $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "<?php echo base_url();?>Absen/closing_kehadiran_bagian", // Isi dengan url/path file php yang dituju
            data: {tanggal : tgl_mulai, bagian : bagian}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response, data){ // Ketika proses pengiriman berhasil
             location.reload();
           },
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
          });
      }

   function getAllData()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      var table = $('#rekap_absen').DataTable();
      table.destroy();
      boo = document.getElementById('boo').value;
      if(boo == "Belum")
      {
        var table = $('#rekap_absen').DataTable( {
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
          /*left: 4,*/
        },
        dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Absen/get_closing",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai},
        },
      });
      }else{
        getAllData2();
      }
      
     }

      function getAllData2()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      var table = $('#rekap_absen').DataTable();
      table.destroy();
      var table = $('#rekap_absen').DataTable( {
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
          /*left: 4,*/
        },
        dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Absen/get_open",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai},
        },
      });
     }
</script>
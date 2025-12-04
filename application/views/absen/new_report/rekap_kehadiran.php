
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $menu ?></h3>
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
               <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
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
              <th><center>Bagian</center></th>
              <th><center>Department</center></th>
              <th><center>Direktorat</center></th>
              <th><center>Jumlah Karyawan</center></th>
              <?php 
                foreach ($jenis->result() as $j) {?>
                  <th><center><?php echo $j->jenis?></center></th>      
              <?php } ?>
            </tr>
          </thead>

          <tbody>
          <?php 
            foreach ($rekap->result() as $r) {?>
              <tr>
                <td><?php echo $r->indeks_hr?></td>
                <td><?php echo $r->nama_struktur?></td>
                <td><?php echo $r->nama_department?></td>
                <td><?php echo $r->jml_karyawan?></td>
                <td><?php echo $r->K?></td>
                <td><?php echo $r->SID?></td>
                <td><?php echo $r->C?></td>
                <td><?php echo $r->D?></td>
                <td><?php echo $r->P1?></td>
                <td><?php echo $r->H1?></td>
                <td><?php echo $r->H2?></td>
                <td><?php echo $r->WFH?></td>
                <td><?php echo $r->MS?></td>
                <td><?php echo $r->A?></td>
                <td><?php echo $r->M?></td>
                <td><?php echo $r->P4?></td>
                <td><?php echo $r->TOS?></td>
                <td><?php echo $r->S1?></td>
                <td><?php echo $r->S2?></td>
                <td><?php echo $r->S3?></td>
                <td><?php echo $r->LS1?></td>
                <td><?php echo $r->LS2?></td>
                <td><?php echo $r->SS1?></td>
                <td><?php echo $r->SS2?></td>
                <td><?php echo $r->SS3?></td>
                <td><?php echo $r->SPM1?></td>
                <td><?php echo $r->SPM2?></td>
                <td><?php echo $r->L?></td>
                <td><?php echo $r->KK?></td>
                <td><?php echo $r->MS1?></td>
                <td><?php echo $r->MS2?></td>
                <td><?php echo $r->OT?></td>
                <td><?php echo $r->KR?></td>
                <td><?php echo $r->MS2R?></td>
                <td><?php echo $r->OT1?></td>
                <td><?php echo $r->OT2?></td>
                <td><?php echo $r->OT3?></td>
                <td><?php echo $r->MU?></td>
                <td><?php echo $r->GH?></td>
                <td><?php echo $r->P?></td>
                <td><?php echo $r->MS4?></td>

              </tr>
          <?php }
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
        <!-- /page content -->


<script>
  $(document).ready(function() {
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
      ]
    } );
} );

  function getData()
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
          url: "<?php echo base_url(); ?>AbsenBarcode/get_rekap_kehadiran",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai},
        },
      });
     }
</script>
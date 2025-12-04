<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Pengajuan Gantung</h3>
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
                   <h2><a href="<?php echo base_url()?>Karyawan/realisasi_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Edit Pengajuan Lembur</h2>
                    
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                        <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="table-filtering" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Kategori</center></th>
                        <th><center>Jumlah Pengajuan</center></th>
                        <th><center>Pekerjaan</center></th>
                        <th><center>Keterangan</center></th>
                        <th><center>Alasan Over budget</center></th>
                        <th><center></center></th>
                      </tr>
                    </thead>


                    <tbody>
                     <?php 
                     foreach ($pengajuan->result() as $data) { echo "
                     <tr>
                     <td><center>"; echo $newDate = date("d M Y", strtotime($data->tgl_lembur));echo"</center></td>
                      <td><center>";
                      if($data->kategori == 'Kgagal'){echo "Komponen Kegagalan";}
                      else if($data->kategori == 'Kterlambat'){echo "Komponen Terlambat Pengiriman";}
                      else if($data->kategori == 'Ekirim'){echo "Pengiriman Barang";}
                      else if($data->kategori == 'Ddinas'){echo "Dinas Driver";}
                      else{echo $data->kategori; }
                      echo"</center></td>
                     <td><center>$data->total_jam</center></td>
                     <td><center>$data->pekerjaan</center></td>
                     <td><center>$data->keterangan</center></td>
                     <td><center>$data->alasan_over</center></td>
                     <td><center>";?>
                     <a href="<?php echo base_url()?>Karyawan/plembur_adjust/<?php echo $data->recid_plembur?>" data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-info btn-xs"><span class='fa fa-pencil'></span></button></a> 
                     <a href="<?php echo base_url()?>Karyawan/plembur_delete/<?php echo $data->recid_plembur?>" data-toggle="tooltip" data-placement="top" title="Delete"><button class="btn btn-danger btn-xs"><span class='fa fa-trash'></span></button></a>
                    <?php } ?>

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


<script>
   $(document).ready(function() {
    // ----------------- Datatable Search Header ----------------- 
      // Setup - add a text input to each footer cell
      $('#table-filtering thead tr').clone(true).appendTo( '#table-filtering thead' );
      $('#table-filtering thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        if(title != '')
        {
          $(this).html( '<input type="text" size="15"/>' );

          $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
              table
              .column(i)
              .search( this.value )
              .draw();
            }
          } );
        }        
      } );

      var table = $('#table-filtering').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
       ordering : false,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
      } );
    } );
</script>
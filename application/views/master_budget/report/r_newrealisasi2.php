
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Report</h3>
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
            <h2><a href="<?php echo base_url()?>Karyawan/r_realisasi"><i class="fa fa-arrow-circle-o-left"></i></a> | Report Realisasi Lembur Periode <?php echo $newDate = date("d M Y", strtotime($tgl_awal)); ?> s/d <?php echo $newDate = date("d M Y", strtotime($tgl_akhir)); ?> </h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="tb_rowsgroup" class="table table-striped table-bordered" width="100%">
              <thead>
                <tr>
                 <th><center>Bagian/Department</center></th>
                 <th><center>Tanggal Lembur</center></th>
                 <th><center>Kategori</center></th>
                 <th><center>Alasan Over</center></th>
                 <th><center>Pengajuan Jam</center></th>
                 <th><center>Realisasi Jam</center></th>
                 <th><center>Pengajuan Orang</center></th>
                 <th><center>Realisasi Orang</center></th>
                 <th><center>Pekerjaan</center></th>
                 <th><center>Target</center></th>
                 <th><center>Hasil</center></th>
              </tr>
            </thead>
            <tbody>
             <?php
              foreach ($realisasi->result() as $data) { ?>
                <tr>
                  <td><center><?php echo $data->indeks_hr ?></center></td>
                  <td><center><?php echo $data->tgl_lembur ?></center></td>
                  <td><center>
                    <?php if($data->kategori == 'Kgagal'){echo "Komponen Kegagalan";}
                    else if($data->kategori == 'Kterlambat'){echo "Komponen Terlambat Pengiriman";}
                    else if($data->kategori == 'Ekirim'){echo "Pengiriman Barang";}
                    else if($data->kategori == 'Ddinas'){echo "Dinas Driver";}
                    else{echo $data->kategori; }?>
                  </center></td>
                  <td><center><?php echo $data->alasan_over ?></center></td>
                  <td><center><?php echo $data->total_jam ?></center></td>
                  <td><center><?php echo $data->realisasi_jam ?></center></td>
                  <td><center><?php echo $data->jml_orang ?></center></td>
                  <td><center><?php echo $data->realisasi_orang ?></center></td>
                  <td width="20%"><center><?php echo $data->pekerjaan ?></center></td>
                  <td><center><?php echo $data->target_kerja ?></center></td>
                  <td><center><?php echo $data->hasil ?></center></td>
                </tr> 
              <?php }
             ?>

            </tbody>
          
                </table>


              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<script>
 $(document).ready(function() {
    $('#tb_rowsgroup').dataTable( {
      // "responsive":true,
      "ordering": false,
      "paging":   false,
      "scrollX": true,
      'rowsGroup': [0,1,2,3,4,5,6,7],
      dom: 'Bfrtip',
      scrollY:        "600px",
      scrollX:        true,
      scrollCollapse: true,
      paging:         false,
      buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    } );
  });
  </script>
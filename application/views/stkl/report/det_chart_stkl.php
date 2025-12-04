<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Report Detail Chart Lembur</h3>
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
                    <h3>Report <?php echo $tipe ?> STKL <?php echo $dept ?> Group Tahun <?php echo $tahun ?></h3>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <!-- Content Table -->
                   <div class="table-responsive">
                    <table id="t_absen" class="table table-striped table-bordered">
                     <thead>
                      <tr class="text-center">
                        <td>No</td>
                        <?php if($this->session->userdata('role_id') == '1'){ ?>
                          <th><center>Recid STKL</center></th>
                        <?php } ?>
                        <th><center>Status</center></th>
                        <th><center>Approval</center></th>
                        <th><center>Tanggal Lembur</center></th>
                        <th><center>Bagian</center></th>
                        <th><center>Jam Mulai</center></th>
                        <th><center>Jam Selesai</center></th>
                        <th><center>Klasifikasi</center></th>
                        <th><center>Tipe</center></th>
                        <th><center>Kategori</center></th>
                        <th><center>Jumlah Orang</center></th>
                        <th><center>Karyawan</center></th>
                        <th><center>Jumlah Jam</center></th>
                        <th><center>Pekerjaan</center></th>
                        <th><center>Jemputan</center></th>
                        <th><center>Uang Makan</center></th>
                        <th><center>Tgl Merah</center></th>
                        <th><center>Keterangan</center></th>
                        <th><center>Alasan Over</center></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = 0;
                            foreach ($stkl->result() as $r) {
                                $tgl_lembur = date("d M Y", strtotime($r->tgl_lembur));
                                if($r->status == 'realisasi' or $r->status == 'selesai')
                                {
                                    $tot_jam = $r->totjam_real;
                                }else{
                                    $tot_jam = $r->total_jam;
                                }

                                if($r->jemputan == '0')
                                {
                                    $jemputan = 'Tidak';
                                }else if($r->jemputan == '1')
                                {
                                    $jemputan = 'Ada';
                                }else{
                                    $jemputan = $r->jemputan;
                                }

                                if($r->makan == '0')
                                {
                                    $makan = 'Tidak';
                                }else if($r->makan == '1')
                                {
                                    $makan = 'Ada';
                                }else{
                                    $makan = $r->makan;
                                }

                                if($r->flag_holiday == '0')
                                {
                                    $flag_holiday = 'Hari Biasa';
                                }else if($r->flag_holiday == '1')
                                {
                                    $flag_holiday = 'Akhir Pekan';
                                }else if($r->flag_holiday == '2')
                                {
                                    $flag_holiday = 'Libur Nasional';
                                }else if($r->flag_holiday == '3')
                                {
                                    $flag_holiday = 'Libur Perusahaan';
                                }else{
                                    $flag_holiday = $r->flag_holiday;
                                }

                                $status = "<span class='badge badge-success'>".ucfirst($r->status)."</span>";
                                $approval = "<span class='badge badge-primary'>".ucfirst($r->approval)."</span>";

                                $get_pekerjaan = $this->m_lembur->get_det_lembur($r->recid_stkl);
                                $pekerjaan = "";
                                $jml_kerja = $get_pekerjaan->num_rows();
                                if($jml_kerja > 0)
                                {
                                    $i = 0;
                                    foreach ($get_pekerjaan->result() as $p) {
                                        $i= $i+1;
                                        if($i < $jml_kerja )
                                        {
                                            $pekerjaan .= $p->pekerjaan.", ";
                                        }else{
                                            $pekerjaan .= $p->pekerjaan;
                                        }
                                    }
                                }?>
                                <tr>
                                <td><?php echo $no = $no +1 ?></td>
                                <td><?php echo $r->recid_stkl ?></td>
                                <td><?php echo $status ?></td>
                                <td><?php echo $approval ?></td>
                                <td><?php echo $tgl_lembur ?></td>
                                <td><?php echo $r->indeks_hr ?></td>
                                <td><?php echo $r->jam_mulai ?></td>
                                <td><?php echo $r->jam_selesai ?></td>
                                <td><?php echo $r->klasifikasi ?></td>
                                <td><?php echo $r->tipe ?></td>
                                <td><?php echo $r->kategori ?></td>
                                <td><?php echo $r->jml_orang ?></td>
                                 <td><button class='btn btn-secondary btn-xs' data-toggle='modal' data-target='#myModal' data-recid_stkl = '<?php echo $r->recid_stkl?>' data-toggle='tooltip' data-placement='top' title='Karyawan Lembur'><span class='fa fa-user'></span></button></td>
                                <td><?php echo $tot_jam ?></td>
                                <td><?php echo $pekerjaan ?></td>
                                <td><?php echo $jemputan ?></td>
                                <td><?php echo $makan ?></td>
                                <td><?php echo $flag_holiday ?></td>
                                <td><?php echo $r->keterangan ?></td>
                                <td><?php echo $r->alasan_over ?></td>
                            </tr>
                        <?php }?>
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

 <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Karyawan Lembur</h4>
      </div>
      <div class="modal-body">
        <div>
        <table id="kar_lembur" class="table table-striped table-bordered">
             <thead>
              <tr class="text-center">
                <td>No</td>
                <th><center>NIK</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Bagian</center></th>
                <th><center>Jabatan</center></th>
                <th><center>Jam Masuk</center></th>
                <th><center>Jam Selesai Lembur</center></th>
                <th><center>Jam Pulang</center></th>
                <th><center>Lama Lembur</center></th>
               </tr>
            </thead>
            <tbody>
          </tbody>
        </table>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>
  
  <script>
    $( document ).ready(function() {
        
    })

    $('#myModal').on('show.bs.modal', function (event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal          = $(this)
      
        recid_stkl = div.data('recid_stkl'); 
        console.log(recid_stkl); 
        var table = $('#kar_lembur').DataTable();
        table.destroy();
        var table = $('#kar_lembur').DataTable( {
          // "responsive":true,
          "bScrollCollapse": true,
          "bLengthChange": true,
          "searching": true,
          "dom": 'Bfrtip',
          buttons: [
          'excel', 'print'
          ],
          "ajax": {
            type: "POST",
            url: "<?php echo base_url(); ?>Lembur/karyawan_lembur",
            dataType: 'JSON',
            data: {recid_stkl:recid_stkl},
          },
        });

    });

   
  </script>
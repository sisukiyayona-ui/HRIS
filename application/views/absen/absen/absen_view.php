<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Absensi Karyawan</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if($this->session->flashdata('sukses')){ ?>  
                 <div class="alert alert-success col-12">  
                   <a href="#" class="close" data-dismiss="alert">&times;</a>  
                   <strong>Success!</strong> <?php echo $this->session->flashdata('sukses'); ?>  
                 </div>  
               <?php } else if($this->session->flashdata('error')){ ?>  
                <div class="alert alert-danger col-12">  
                 <a href="#" class="close" data-dismiss="alert">&times;</a>  
                 <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>  
               </div>  
             <?php }else if($this->session->flashdata('warning')){?>
              <div class="alert alert-info col-12">  
                 <a href="#" class="close" data-dismiss="alert">&times;</a>  
                 <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>  
               </div>  
             <?php }else{

             } ?>
                <div class="x_panel">
                  <div class="x_title">
                    <?php 
                      if($role == '1' or $role == '3' or $role == '26')
                      {if($role == '1' or $role == '3'){?>
                        <a class="btn btn-primary btn-sm" href="<?php echo base_url()?>Absen/absen_absen">
                          <i class="fa fa-plus"></i> Absen </a>
                        <?php } ?>
                          <a class="btn btn-success btn-sm" href="<?php echo base_url()?>Absen/upload_updateabsensi">
                          <i class="fa fa-plus"></i> Upload Absensi </a>
                          <br>
                      <?php } ?>
                     
                    <form method="post">
                      <div class="item form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Dari Tanggal<span class="required">*</span>
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
                   <div class="item form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Sampai Tanggal<span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                         <div class='input-group date' id='myDatepicker2'>
                          <input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" required="required" value="<?php echo date('Y-m-d')?>"  />
                          <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                         </span>
                       </div>
                     </div>
                   </div>
                   <div class="item form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Jenis Absen<span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <select class="form-control selectpicker" multiple="multiple" data-actions-box="true" data-live-search="true" id="jenis">
                            <?php 
                              foreach ($jenis->result() as $j) {?>
                                <option value="<?php echo $j->recid_jenisabsen?>"><?php echo $j->keterangan." - ".$j->jenis?></option>
                              <?php }
                            ?>
                          </select> 
                        </div>
                   </div>
                   <div class="form-group">
                    <div class="col-md-2">
                     <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                   </div>
                 </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- Content Table -->
                    <table id="t_absen" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
                          <th>Jabatan</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Diagnosa</th>
                          <th>Kategori</th>
                          <th>Detail Sakit</th>
                          <th>Keterangan</th>
                          <th>Validasi Cuti</th>
                          <?php if($role == '1' or $role == '3'){?>
                            <th>Aksi</th>
                          <?php }?>
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
                        <td><?php echo $data->tanggal ?></td>
                        <td><center><?php echo $data->jenis." - ".$data->keterangan?></center></td>
                        <td><?php echo $data->diagnosa ?></td>
                        <td><?php echo $data->kategori ?></td>
                        <td><?php echo $data->ket_sakit ?></td>
                        <td><?php echo $data->ket ?></td>
                        <td><?php echo ($data->validasi_cuti == '0') ? "Belum Validasi" : ''; ?></td>
                        <?php if($role == '1' or $role == '3'){?>
                         <td><center>
                          <a href="<?php echo base_url()?>Absen/absen_update/<?php echo $data->absensi_recid?>"><button type="button" class="btn btn-xs btn-info"><span class='fa fa-edit'></span></button></a>
                          <!-- <a href="<?php echo base_url()?>Absen/absen_delete/<?php echo $data->absensi_recid?>"><button type="button" class="btn btn-xs btn-danger"><span class='fa fa-trash'></span></button></a> -->
                        </center></td>
                      <?php } ?>
                      </tr>
                      <?php } ?>
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
     /** After windod Load */  
     $(window).bind("load", function() {  
       window.setTimeout(function() {  
         $(".alert").fadeTo(100, 0).slideUp(500, function() {  
           $(this).remove();  
           <?php unset($_SESSION['sukses']); ?>
           <?php unset($_SESSION['eror']); ?>
           <?php unset($_SESSION['warning']); ?>
         });  
       }, 3000);  
     });  

     function getData()
     {
      tgl_mulai = document.getElementById("tgl_mulai").value;
      tgl_akhir = document.getElementById("tgl_akhir").value;
      jenis = $('#jenis').val();
      // console.log(jenis);
      var table = $('#t_absen').DataTable();
      table.destroy();
      var table = $('#t_absen').DataTable( {
        "responsive":true,
        "bScrollCollapse": true,
        "bLengthChange": true,
        "searching": true,
        "dom": 'Bfrtip',
        buttons: [
        'excel', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Absen/absen_periode",
          dataType: 'JSON',
          data: {tgl_mulai:tgl_mulai, tgl_akhir:tgl_akhir, jenis:jenis},
        },
      });
     }
   </script>
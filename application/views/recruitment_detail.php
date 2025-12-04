<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Recruitment</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                </div>
              </div>
            </div>

           <?php  foreach ($recruitment as $data) {} ?>

            <div class="clearfix"></div> 
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                   <div class="x_title">
                    <h2> <a href="<?php echo base_url()?>Karyawan/recruitment_view"><i class="fa fa-arrow-circle-o-left"></i></a> | Detail Recruitment</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="">
                  
                  </div>
                  <div class="x_content">
                   <!-- Content Table -->
                   <div class="table-responsive col-md-8 col-sm-8 col-xs-8">
                    <table class="table table-striped table-bordered">
                      <tr><td colspan="2"> <?php if($role == '1' or $role == '2'){ ?>
                       <div class="btn-group">
                        <a href="<?php echo base_url()?>Karyawan/test_insert/<?php echo $data->recid_recruitment ?>">
                          <button class="btn btn-info btn-sm" type="button"><i class="fa fa-plus"></i> | Testing
                          </button>
                        </a>
                      </div>
                      <?php } ?></td></tr>
                        <tr><td colspan="2"><b><?php echo $data->judul_recruitment ?></b></td></tr>
                        <tr><td>Posisi </td><td><?php echo $data->nama_bag." / ".$data->nama_jbtn ?></td></tr>
                        <tr><td>Tanggal Efektif </td><td><?php echo $newDate = date("d M Y", strtotime($data->tgl_efektif)); ?></td></tr>
                        <tr><td>Status </td><td><b><?php echo $data->status?></b></td></tr>
                      </table>
                   </div>
                   <div class="clearfix"></div>
                   <h3>Testing</h3>
                   <?php
                    foreach ($test as $key ) {?>
                      <table class="table table-striped table-bordered">
                        <tr><td colspan="2"><b><?php echo $key->judul_test?></b></td></tr>
                       <tr><td>Tanggal Test </td><td><?php echo $key->tgl_test?></td></tr>
                       <tr><td>Peserta Test</td>
                        <td>
                          <table  class="table table-striped table-bordered">
                            <tr><th>Nama Peserta</th><th>Email</th><th>No Telp</th><th>Hasil</th><th>Status</th><th>Aksi</th></tr>
                            <?php 
                            $kandidat = $this->db->query("SELECT s.*, p.* From seleksi s join pelamar p on s.recid_pelamar = p.recid_pelamar where recid_test = '$key->recid_test'")->result();
                            foreach ($kandidat as $dataa) { ?>
                              <tr><td><?php echo $dataa->nama_pelamar ?></td>
                                <td><?php echo $dataa->email ?></td>
                                <td><?php echo $dataa->no_telp ?></td>
                                <td><?php echo $dataa->hasil ?></td>
                                <td><?php if($dataa->status == 'Terima') {echo "<b>$dataa->status</b>";}else{ echo $dataa->status;} ?></td>
                                <td><center>
                                  <?php if($data->status == 'Open'){ ?>
                                  <a 
                                  data-recid_seleksi="<?php echo $dataa->recid_seleksi ?>"
                                  data-hasil="<?php echo $dataa->hasil ?>"
                                  data-status="<?php echo $dataa->status ?>"
                                  data-note="<?php echo $dataa->note ?>"
                                  data-recid_recruitment="<?php echo $data->recid_recruitment ?>"
                                  data-toggle="modal" data-target="#edit_seleksi">
                                  <button class='btn btn-info btn-xs'><span class='fa fa-edit'>
                                   </span></button></a> <?php }else{ ?>
                                    <button class='btn btn-info btn-xs' disabled="disabled"><span class='fa fa-edit'>
                                      <?php if($dataa->status == 'Terima' and $dataa->nik == null){?>
                                        <a href="<?php echo base_url()?>/Karyawan/karyawan_generate/<?php echo $dataa->recid_pelamar ?>"><button class="btn btn-success btn-xs"  data-toggle="tooltip" data-placement="top" title="Generate"><span class="fa fa-retweet"></span></button></a>
                                      <?php }
                                    }?>
                               </center></td>
                            <?php } ?>
                          </table>
                        </td></tr>
                     </table>
                    <?php }
                    ?>
              <!--/ Content Table -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


        <!-- Modal -->
<div id="edit_seleksi" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Hasil Seleksi</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo base_url()?>Karyawan/seleksi_update">
       <input type="hidden" class="form-control" name="recid_seleksi" id="erecid_seleksi">
       <input type="hidden" class="form-control" name="recid_recruitment" id="erecid_recruitment">
        <label>Hasil Test</label>
        <textarea name="hasil" class="form-control" id="ehasil"></textarea><br>
        <label>Status Lolos</label>
        <select name="status" class="form-control" id="ests">
          <option value="">-- Pilih --</option>
          <option value="Lolos">Lolos</option>
          <option value="Tidak Lolos">Tidak Lolos</option>
          <option value="Terima">Terima</option>
        </select><br>
         <label>Keterangan</label>
        <textarea name="note" id="enote3" class="form-control"></textarea><br>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </form>
      </div>
    </div>

  </div>
</div>
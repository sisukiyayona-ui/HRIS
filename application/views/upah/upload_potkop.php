<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Potongan Koprasi</h3>
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
                 <strong>Error!</strong> <?php echo $this->session->flashdata('eror'); ?>  
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
                    <h2>Upload Potongan Koprasi</h2>
                     <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     <form action="<?php echo base_url()?>Upah/import_potkop" method="POST" enctype="multipart/form-data">
                      <input type="file" class="form-control" name="file_exc"><br>
                      <input type="submit"class="btn btn-primary" value="Submit">
                    </form> 
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="x_panel">
                  <div class="x_title">
                   <h2>Daftar Potongan koprasi</h2>
                      <form enctype="multipart/form-data" method="post">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-2 col-sm-2 col-xs-2"><label>Bulan</label></div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <?php $bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                            $x = 0; ?>
                            <select name="bulan" class="form-control" id="bulan">
                              <?php
                              for ($i = 0; $i < count($bulan); $i++) { 
                                  if($bulan[$i] == $bulans){?>
                                      <option value="<?php echo $x = $x + 1 ?>"selected><?php echo $bulan[$i] ?></option>
                                  <?php }else{?>
                                      <option value="<?php echo $x = $x + 1 ?>"><?php echo $bulan[$i] ?></option>
                                  <?php }?>
                                
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-md-2 col-sm-2 col-xs-2"><label>Tahun</label></div>
                          <div class="col-md-2 col-sm-2 col-xs-2">
                            <select class="form-control" name="tahun" id="tahun">
                              <?php
                              foreach ($tahun->result() as $t) { ?>
                                <option value="<?php echo $t->tahun ?>"><?php echo $t->tahun ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <button type="button" class="btn btn-primary" onclick="getData()">Cari</button>
                        </div>
                        
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- Content Table -->
                    <table id="potkop" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Bagian</th>
                          <th>Bulan</th>
                          <th>Tahun</th>
                          <th>Potongan</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        
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

  <!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_potkop">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Potongan Koprasi</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Upah/potkop_update" novalidate>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">NIK <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="erecid" class="form-control col-md-7 col-xs-12" placeholder="RECID" name="recid" required="required" type="hidden">
              <input id="enik" class="form-control col-md-7 col-xs-12" placeholder="NIK" required="required" type="text" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama Karyawan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="enama_karyawan" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" placeholder="Nama Karyawan" required="required" type="text" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Bulan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="ebulan" class="form-control col-md-7 col-xs-12" placeholder="Bulan" required="required" type="text" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Tahun <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="etahun" class="form-control col-md-7 col-xs-12" placeholder="Tahun" required="required" type="text" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Jml Potongan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="epotongan" name="potongan" class="form-control col-md-7 col-xs-12"placeholder="Potongan" required="required" type="text">
            </div>
          </div>
           
      <!--/ Content Modal -->
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <input type="submit" class="btn btn-primary" value='Save changes'>
      </form>
    </div>

  </div>
</div>
</div>
<!--/ Modal Edit Data -->


<script>
  $(document).ready(function() {
    getData();

     $('#edit_potkop').on('show.bs.modal', function (event) {
        var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
        var modal          = $(this)
        var a = div.data('top');
        // Isi nilai pada field
        modal.find('#erecid').attr("value",div.data('recid'));
        modal.find('#enik').attr("value",div.data('nik'));
        modal.find('#enama_karyawan').attr("value",div.data('nama_karyawan'));
        $("#ebulan").val(div.data('bulan'));
        $("#etahun").val(div.data('tahun'));
        $("#epotongan").val(div.data('potongan'));
    });
  });

  function getData() {
    bulan = document.getElementById("bulan").value;
    tahun = document.getElementById("tahun").value;
    var table = $('#potkop').DataTable();
    table.destroy();
    var table = $('#potkop').DataTable({
      "responsive": true,
      "bScrollCollapse": true,
      "bLengthChange": true,
      "searching": true,
      "dom": 'Bfrtip',
      buttons: [
        'excel', 'print'
      ],
      "ajax": {
        type: "POST",
        url: "<?php echo base_url(); ?>Upah/potkop_periode",
        dataType: 'JSON',
        data: {
          bulan: bulan,
          tahun: tahun,
        },
      },
    });
  }
</script>
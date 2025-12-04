<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Departemen</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
              <?php if($role == '1' or $role == '2' or $role == '5' ){ ?>
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_struktur ">
              <i class="fa fa-plus"></i> Tambah Data
            </a>
           <?php } ?>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <?php if($role == 1){
                    echo "<td>recid</td>";
                  }?>
                  <th>Nama Departemen</th>
                  <th>PIC Departemen</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($struktur as $data) {?>
                  <tr>
                    <?php if($role == 1){
                      echo "<td>".$data->recid_struktur."</td>";
                    }?>
                    <td><?php echo $data->nama_struktur?></td>
                    <td><?php echo $data->nama_karyawan?></td>
                    <td><?php echo $data->note?></td>
                    <td><?php echo ($data->sis_delete == '1') ? "Not Active" : 'Active'; ?></td>
                    <td><center>
                   <?php if($role == '1' or $role == '2' or $role == '5'){?>
                    <a href="<?php echo base_url()?>Karyawan/str_update/<?php echo $data->recid_struktur?>">
                    <button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;
                  </a>
                  <?php 
                      if($data->sis_delete == '1')
                      {?>
                        <a href="<?php echo base_url()?>Karyawan/struktur_active/<?php echo $data->recid_struktur?>"><button class='btn btn-warning btn-xs'><span class='fa fa-check'></button></a>
                      <?php } else{?>
                        <a href="<?php echo base_url()?>Karyawan/struktur_delete/<?php echo $data->recid_struktur?>"><button class='btn btn-danger btn-xs'><span class='fa fa-times'></button>
                      <?php }                
                  ?>
                
                 <?php }?>
                </td>
                <?php echo "</tr>" ;
                }
               ?>
              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal_struktur">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Data Departemen</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/struktur_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_str">Nama Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_struktur" placeholder="Nama Pada Departemen" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note' placeholder='Keterangan'></textarea>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">PIC Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <select name="pic_struktur" id="pic_struktur" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
               <option value="">-- Pilih -- </option>
               <?php 
                foreach ($karyawan as $key ) {?>
                  <option value="<?php echo $key->recid_karyawan?>"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
                <?php }
               ?>
             </select>
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
<!--/ Modal Tambah Data -->

<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_struktur">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Data Departemen</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/struktur_update" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Nama Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="erecid_str" type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_struktur" readonly >
              <input id="enama_str" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_str" placeholder="Nama Bagian Struktur" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note' id='enote' placeholder='Keterangan'></textarea>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">PIC Departemen <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="pic_struktur" id="epic_struktur" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
               <?php 
               foreach ($karyawan as $key ) {?>
                <option value="<?php echo $key->recid_karyawan?>"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
              <?php }
              ?>
            </select>
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

<!-- /page content -->

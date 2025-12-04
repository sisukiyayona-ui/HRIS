<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Department</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
              <?php if($role == '1' or $role == '2'){ ?>
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
                  <th>Nama Direktorat</th>
                  <th>Direktorat Group</th>
                  <th>PIC Direktorat</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($dept as $data) {?>
                  <tr>
                    <td><?php echo $data->nama_department?></td>
                    <td><?php echo $data->dept_group?></td>
                    <td><?php echo $data->nama_karyawan?></td>
                    <td><?php echo ($data->is_delete == '1') ? "Not Active" : 'Active'; ?></td>
                    <td><center>
                    <?php if($role == '1' or $role == '2' or $role == '5'){?>
                    <a href="<?php echo base_url()?>Karyawan/dept_update/<?php echo $data->recid_department?>">
                    <button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a>&nbsp;&nbsp;
                    <?php 
                      if($data->is_delete == '1')
                      {?>
                        <a href="<?php echo base_url()?>Karyawan/department_active/<?php echo $data->recid_department?>"><button class='btn btn-warning btn-xs'><span class='fa fa-check'></button></a>
                      <?php }else{?>
                        <a href="<?php echo base_url()?>Karyawan/department_delete/<?php echo $data->recid_department?>"><button class='btn btn-danger btn-xs'><span class='fa fa-times'></button></a>
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
        <h4 class="modal-title" id="myModalLabel">Tambah Data Direktorat</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/dept_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_str">Nama Direktorat <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_department" placeholder="Nama Direktorat" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Direktorat Group <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <select name="dept_group" id="pic_dept" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
              <option value="Presdir">Presdir</option>
              <option value="Front Office">Front Office</option>
              <option value="Middle Office">Middle Office</option>
              <option value="Back Office">Back Office</option>
             </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">PIC Direktorat <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <select name="pic_dept" id="pic_dept" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
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


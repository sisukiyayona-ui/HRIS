<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Bagian</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
             <?php if($role == '1' or $role == '2' or $role == '5' ){ ?>
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_bagian">
              <i class="fa fa-plus"></i> | Tambah Data
            </a>
             <?php } ?>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <table id="t_default" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <?php
                    if($role == 1)
                    {
                      echo "<th>Recid</th>";
                    }
                  ?>
                  <th>Index Bagian</th>
                  <!-- <th>Kode</th> -->
                  <th>Bagian</th>
                  <th>Departemen</th>
                  <th>Direktorat</th>
                  <th>PIC Bagian</th>
                  <th>Direktorat Group</th>
                  <th>Pay Group</th>
                  <th>Shift</th>
                  <th>GL Account</th>
                  <th>Cost Center</th>
                  <th>Status</th>
                  <?php if($role == '1' or $role == '2') {?>
                  <th>Aksi</th>
                  <?php } ?>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($bagian as $data) {?>
                  <tr>
                  <?php if($role == 1)
                  {?>
                    <td><?php echo $data->recid_bag ?></td>
                  <?php } ?>
                    <td><?php echo $data->indeks_hr ?></td>
                    <!-- <td><?php echo $data->kode_bag ?></td> -->
                    <td><?php echo $data->nama_bag ?></td>
                    <td><?php echo $data->nama_struktur ?></td>
                    <td><?php echo $data->nama_department ?></td>
                    <td><?php echo $data->atasan ?></td>
                    <td><?php echo $data->dept_group ?></td>
                    <td><?php echo $data->pay_group ?></td>
                    <td><?php echo $data->shift ?></td>
                    <td><?php echo $data->gl_acc ?></td>
                    <td><?php echo $data->cost_center ?></td>
                    <td><?php echo ($data->is_delete == '1') ? "Not Active" : 'Active'; ?></td>
                    <?php if($role == '1' or $role == '2') {?>
                      <td><center>
                    <a href="<?php echo base_url()?>Karyawan/bagian_update/<?php echo $data->recid_bag?>">
                    <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;";?></a>
                    <?php 
                        if($data->is_delete == '1')
                        {?>
                           <a href="<?php echo base_url()?>Karyawan/bagian_active/<?php echo $data->recid_bag?>">
                           <?php echo"<button class='btn btn-warning btn-xs'><span class='fa fa-check'></button>&nbsp;"; ?></a>
                        <?php }else{ ?>
                            <a href="<?php echo base_url()?>Karyawan/bagian_delete/<?php echo $data->recid_bag?>"><button class='btn btn-danger btn-xs'><span class='fa fa-times'></button>&nbsp;</a>
                    <?php }?>
                     </td>
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

<!-- Modal Tambah Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal_bagian">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Data Bagian</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/bagian_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Index Bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="indeks_hr" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="indeks_hr" placeholder="Index Bagian" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Kode <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="kode_bag" placeholder="Kode Bagian" required="required" type="text">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_bag" placeholder="Nama Bagian" required="required" type="text">
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_str">Departemen<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="recid_struktur"  name="recid_struktur" class="selectpicker form-control col-md-7 col-xs-12" required="required">
                <option value="">-- Pilih --</option>
                <?php 
                foreach ($struktur as $option ) { ?>
                  <option value="<?php echo $option->recid_struktur ?>"><?php echo $option->nama_struktur ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Direktorat <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="recid_department" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
                <option value="">-- Pilih -- </option>
                 <?php 
                  foreach ($department as $dept ) {?>
                      <option value="<?php echo $dept->recid_department?>"><?php echo $dept->nama_department ?> </option>
                    <?php }
                  ?>
                </select>
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Direktorat Group <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="department"  name="dept_group" class="selectpicker form-control col-md-7 col-xs-12" required="required">
                 <option value="">-- Pilih --</option>
                <option value="Presdir">Presdir</option>
                <option value="Front Office">Front Office</option>
                <option value="Back Office">Back Office</option>
                <option value="Middle Office">Middle Office</option>
                <option value="Lain-Lain">Lain-Lain</option>
              </select>
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Shift <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="shift"  name="shift" class="form-control col-md-7 col-xs-12" required="required">
                 <option value="">-- Pilih --</option>
                <option value="Shift">Shift</option>
                <option value="Non-Shift">Non Shift</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Pay Group <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="pay"  name="pay_group" class="form-control col-md-7 col-xs-12" required="required">
                 <option value="">-- Pilih --</option>
                <option value="Direct">Direct</option>
                <option value="Indirect">Indirect</option>
                <option value="Admin">Admin</option>
                <option value="Penjualan">Penjualan</option>
                <option value="BOD">BOD</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">PIC Bagian<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
               <select name="pic_bagian" class='form-control col-md-12 col-xs-12 selectpicker' data-live-search='true' >
                 <option value="">-- Pilih -- </option>
                  <?php 
                  foreach ($karyawan as $key ) {?>
                     <option value="<?php echo $key->recid_karyawan?>"><?php echo $key->nama_karyawan." (".$key->nik.")" ?> </option>
                  <?php }
                  ?>
                </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">GL Account <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <input type="text" name="gl_acc" class="form-control">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Cost Center <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <input type="text" name="cost_center" class="form-control">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note' placeholder='Keterangan'></textarea>
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
<!-- /page content -->

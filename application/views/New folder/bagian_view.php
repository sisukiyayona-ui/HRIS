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
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Bagian</th>
                  <th>Nama Struktur</th>
                  <th>Department</th>
                  <th>Department Group</th>
                  <th>Pay Group</th>
                  <th>Shift</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($bagian as $data) {
                echo "
                  <tr>
                    <td>$data->kode_bag</td>
                    <td>$data->nama_bag</td>
                    <td>$data->nama_struktur</td>
                    <td>$data->department</td>
                    <td>$data->dept_group</td>
                    <td>$data->pay_group</td>
                    <td>$data->shift</td>
                    <td><center>";?>
                    <a href="<?php echo base_url()?>Karyawan/bagian_update/<?php echo $data->recid_bag?>">
                    <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?></a>
                      <a href="<?php echo base_url()?>Karyawan/bagian_delete/<?php echo $data->recid_bag?>">
                    <?php echo"<button class='btn btn-danger btn-xs'><span class='fa fa-trash'></button>&nbsp;&nbsp;&nbsp;";?></a>
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_str">Nama Struktur <span class="required">*</span>
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Department <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="department"  name="department" class="selectpicker form-control col-md-7 col-xs-12" required="required">
                <option value="">-- Pilih --</option>
                <option value="PRESDIR">Presdir</option>
                <option value="MARKETING">Marketing</option>
                <option value="KEU & ADM">Keu & Adm</option>
                <option value="PRODUKSI">Produksi</option>
                <option value="BUSINESS & DEV">Business & Dev</option>
                <option value="Lain-Lain">Lain-Lain</option>
              </select>
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Department Group <span class="required">*</span>
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
                <option value="Indirect">Inderect</option>
                <option value="Admin">Admin</option>
                <option value="Penjualan">Penjualan</option>
              </select>
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

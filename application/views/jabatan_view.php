<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Jabatan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
              <?php if($role == '1' or $role == '2' or $role == '5' ){ ?>
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#jabatan">
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
                  <th>Nama Jabatan</th>
                  <th>Tingkatan</th>
                  <th>Top</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($jabatan as $data) {
                echo "
                  <tr>
                    <td>$data->nama_jbtn</td>
                    <td>$data->tingkatan</td>
                    <td>$data->top</td>
                    <td>$data->note</td>
                    <td><center>";?>
                    <a 
                    data-recid_jbtn="<?php echo $data->recid_jbtn ?>"
                    data-nama_jbtn="<?php echo $data->nama_jbtn ?>"
                    data-tingkatan="<?php echo $data->tingkatan ?>"
                    data-top="<?php echo $data->top ?>"
                    data-note="<?php echo $data->note ?>"
                    data-toggle="modal" data-target="#edit_jabatan">
                    <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?>
                <?php 
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id='jabatan'>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Data Jabatan</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/jabatan_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama Jabatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nik" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_jbtn" placeholder="Nama Jabatan" required="required" type="text">
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tingkatan">Tingkatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" id="number" name="tingkatan" required="required" data-validate-minmax="1,100" class="form-control col-md-7 col-xs-12" placeholder="Number">
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Top <spanclass="required" >*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="top" class="form-control col-md-7 col-xs-12" required="required">
                <option value="">-- Pilih --</option>
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan 
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note'  placeholder="Keterangan"></textarea>
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_jabatan">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Data Jabatan</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/jabatan_update" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama Jabatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="erecid_jbtn" type='hidden' class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="recid_jbtn" readonly >
              <input id="enama_jbtn" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_jbtn" placeholder="Nama Jabatan" required="required" type="text">
            </div>
          </div>
           <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Tingkatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
             <input type="number" id="etingkatan" name="tingkatan" required="required" data-validate-minmax="1,100" class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Top <spanclass="required" >*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="etop"  name="top" class="form-control col-md-7 col-xs-12">
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control col-md-7 col-xs-12" name='note' id='enote' placeholder='Keterangan'></textarea>
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
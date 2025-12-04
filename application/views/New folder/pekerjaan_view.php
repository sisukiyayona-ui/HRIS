<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Master Pekerjaan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_bagian">
               <i class="fa fa-plus"></i> | Master Pekerjaan
            </a>
          
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <table id="datatable-buttons" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Nama Pekerjaan</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>


              <tbody>
               <?php 
                foreach ($pekerjaan as $data) {
                echo "
                  <tr>
                    <td>$data->nama_pekerjaan</td>
                    <td>$data->note</td>
                    <td><center>"; ?>
                    <a href="<?php echo base_url()?>Karyawan/pekerjaan_update/<?php echo $data->recid_pekerjaan ?>">
                    <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?></a>
                      <a href="<?php echo base_url()?>Karyawan/pekerjaan_delete/recid_pekerjaan">
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
        <h4 class="modal-title" id="myModalLabel">Tambah Data Pekerjaan</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Karyawan/pekerjaan_pinsert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bag">Nama Pekerjaan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nama_pekerjaan" class="form-control col-md-7 col-xs-12" name="nama_pekerjaan" placeholder="Nama Pekerjaan" required="required" type="text">
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

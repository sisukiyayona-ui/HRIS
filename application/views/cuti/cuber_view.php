<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Libur & Cuti Bersama</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
          <?php if($role == '1' or $role == '3' or $role == '5' ){ ?>
            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user">
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
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Keterangan</th>
                  <th>Jenis</th>
                   <?php if($role == '1' or $role == '3'){?>
                    <th>Aksi</th>
                  <?php }?>
                </tr>
              </thead>


              <tbody>
                <?php 
                $no = 0;
                foreach ($cuber->result() as $data) {?>
                  <tr>
                  <td><?php echo $no = $no+1 ?></td>
                  <td><?php echo $data->tanggal ?></td>
                  <td><?php echo $data->keterangan ?></td>
                  <td><?php echo $data->jenis ?></td>
                   <?php if($role == '1' or $role == '3'){?>
                  <td><center>
                  <a 
                  data-cuber_recid="<?php echo $data->cuber_recid ?>"
                  data-tanggal="<?php echo $data->tanggal ?>"
                  data-keterangan="<?php echo $data->keterangan ?>"
                  data-jenis="<?php echo $data->jenis ?>"
                  data-toggle="modal" data-target="#edit_user">
                  <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?>
                  </a><a href="<?php echo base_url()?>Absen/cuber_hapus/<?php echo $data->cuber_recid?>"><button class="btn btn-danger btn-xs"><span class="fa fa-trash"></span></button></a></center></td>
                    <?php }?>
                    </tr>
                <?php }?>

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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Cuti Bersama</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Absen/cuber_insert" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
              <input type='date' class="form-control" name="tanggal" placeholder="tanggal"  />
           </div>
         </div>
         <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="jenis" class="form-control">
              <option value="Libur Nasional">Libur Nasional</option>
              <option value="Cuti Bersama">Cuti Bersama</option>
              <option value="Libur Perusahaan">Libur Perusahaan</option>
            </select>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Keterangan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="keterangan" class="form-control"></textarea>
          </div>
        </div>
        <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
      </form>
    </div>

  </div>
</div>
</div>
<!--/ Modal Tambah Data -->


<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Edit Cuti Bersama</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Absen/cuber_update" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal<span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="cuber_recid" class="form-control col-md-7 col-xs-12"  type="hidden" name="cuber_recid">
              <input type='text' class="form-control" name="tanggal" id="tanggal" placeholder="tanggal cuti"  />
           </div>
          </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="jenis" class="form-control" id="jenis">
              <option value="Libur Nasional">Libur Nasional</option>
              <option value="Cuti Bersama">Cuti Bersama</option>
              <option value="Libur Perusahaan">Libur Perusahaan</option>
            </select>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Keterangan <span class="required">*</span>
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
          </div>
        </div>
        <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Save changes"></button>
      </form>
    </div>

  </div>
</div>
</div>
<!--/ Modal Edit Data -->


<!-- /page content -->

<script>
    $(document).ready(function() {
          // Untuk sunting
          $('#edit_user').on('show.bs.modal', function (event) {
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal          = $(this)

              // Isi nilai pada field
              modal.find('#cuber_recid').attr("value",div.data('cuber_recid'));
              modal.find('#tanggal').attr("value",div.data('tanggal'));
              $("#keterangan").val(div.data('keterangan'));
              $("#jenis").val(div.data('jenis'));
          });
      });

  
</script>
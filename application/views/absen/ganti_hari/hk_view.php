<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Hari Kerja</h3>
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
                  <th>Tahun</th>
                  <th>Bulan</th>
                  <th>Tanggal Awal</th>
                  <th>Tanggal Akhir</th>
                  <th>Jumlah Hari Kerja</th>
                  <?php if($role == '1' or $role == '3'){?>
                    <th>Aksi</th>
                  <?php }?>
                </tr>
              </thead>


              <tbody>
                <?php 
                $bln = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                foreach ($hk->result() as $data) {
                  $buln = $bln[$data->bulan-1];?>
                  <tr>
                  <td><?php echo $data->tahun ?></td>
                  <td><?php echo $buln ?></td>
                  <td><?php echo $data->tgl_awal ?></td>
                  <td><?php echo $data->tgl_akhir ?></td>
                  <td><?php echo $data->jml_hk ?></td>
                  <?php 
                  if($role == '1' or $role == '3'){?>
                   <td><center>
                    <a 
                    data-hk_recid="<?php echo $data->recid_hk ?>"
                    data-tahun="<?php echo $data->tahun ?>"
                    data-bulan="<?php echo $data->bulan ?>"
                    data-tgl_awal="<?php echo $data->tgl_awal ?>"
                    data-tgl_akhir="<?php echo $data->tgl_akhir ?>"
                    data-jml_hk="<?php echo $data->jml_hk ?>"
                    data-toggle="modal" data-target="#edit_user">
                    <?php echo"<button class='btn btn-info btn-xs'><span class='fa fa-edit'></button>&nbsp;&nbsp;&nbsp;";?></a></center></td>
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Hari Kerja</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Absen/hk_insert" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nama">Tahun <span class="required">*</span>
          </label>
          <div class="col-md-8 col-sm-68col-xs-12">
              <input type='text' class="form-control" name="tahun" id="tahun" placeholder="tahun" />
           </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="username">Hari Kerja <span class="required">*</span>
          </label>
          <div class="col-md-8 col-sm-8 col-xs-12">
            <table class="table table table-bordered">
            <tr><td><b>Bulan</b></td><td>Tanggal Awal</td><td>Tanggal Akhir</td><td><b>Jumlah Hari kerja</b></td></tr>
            <?php 
              $bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "Sepetember", "Oktober", "November", "Desember"];
              $x=0;
              for($i=0;$i<count($bulan);$i++)
              {?>
                 <tr><td><?php echo $bulan[$i]?><input type="hidden" class="form-control" name="bulan[]" value="<?php echo $x=$x+1?>"></td><td><input type="date" class="form-control" name="tgl_awal[]"></td><td><input type="date" class="form-control" name="tgl_akhir[]" ></td><td><input type="number" class="form-control" name="jml_hk[]"></td></tr>
              <?php } ?>
          </table>
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
        <h4 class="modal-title" id="myModalLabel">Edit Hari Kerja</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url()?>Absen/hk_update" novalidate>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tahun <span class="required">*</span>
          </label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input id="hk_recid" class="form-control col-md-7 col-xs-12"  type="hidden" name="hk_recid">
              <input type='text' class="form-control" name="tahun" id="tahun" placeholder="tahun"  />
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Bulan <span class="required">*</span>
          </label>
          <div class="col-md-3 col-sm-2 col-xs-12">
              <input type='hidden' class="form-control" name="bulan" id="bulan" placeholder="bulan"  />
              <input type='text' class="form-control" id="bulan_name" placeholder="bulan" readonly />
          </div>
          <div class="col-md-3 col-sm-2 col-xs-12">
              <input type='date' class="form-control" id="tgl_awal" name="tgl_awal" placeholder="Tanggal Awal" />
          </div>
          <div class="col-md-3 col-sm-2 col-xs-12">
              <input type='date' class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="Tanggal Akhir"  />
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Jumlah HK <span class="required">*</span>
          </label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input type='text' class="form-control" name="jml_hk" id="jml_hk" placeholder="jml_hk"  />
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
            bln = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal          = $(this)

              // Isi nilai pada field
              modal.find('#hk_recid').attr("value",div.data('hk_recid'));
              modal.find('#tahun').attr("value",div.data('tahun'));
              modal.find('#bulan').attr("value",div.data('bulan'));
              modal.find('#tgl_awal').attr("value",div.data('tgl_awal'));
              modal.find('#tgl_akhir').attr("value",div.data('tgl_akhir'));
              modal.find('#jml_hk').attr("value",div.data('jml_hk'));

              buln = document.getElementById('bulan').value; 
              buln = bln[buln-1];
              document.getElementById("bulan_name").value = buln;
          });
      });

  
</script>
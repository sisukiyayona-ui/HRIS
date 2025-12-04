<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Adjustment Upah</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <?php
            if ($role == '1' or $role == '3'  or $role == '5'  or $role == '32') { ?>
              <a class="btn btn-primary btn-sm" href="<?php echo base_url() ?>Upah/adjustment_upah">
                <i class="fa fa-plus"></i> | Tambah Data </a>
              <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Upah/upload_adjustment">
                <i class="fa fa-upload"></i> | Upload Data </a>
              <br>
              <br>
            <?php } ?>

            <form method="post">
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Periode Awal<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_mulai" id="periode_awal" required="required" value="<?php echo date('Y-m-d') ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Periode Akhir<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_akhir" id="periode_akhir" required="required" value="<?php echo date('Y-m-d') ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Kategori<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control selectpicker" data-live-search="true" id="kategori">
                    <option value="All">Semua</option>
                    <?php
                    foreach ($kategori->result() as $j) { ?>
                      <option value="<?php echo $j->recid_akatuph ?>"><?php echo $j->kategori ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-2">
                  <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                </div>
              </div>
            </form>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!--Add content to the page ...-->
            <!-- Content Table -->
            <table id="t_absen" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Periode Awal</th>
                  <th>Periode Akhir</th>
                  <th>Tanggal</th>
                  <th>Nik</th>
                  <th>Nama</th>
                  <th>Bagian</th>
                  <th>Jml Makan</th>
                  <th>Jml Transport</th>
                  <th>Lembur1</th>
                  <th>Lembur2</th>
                  <th>Lembur3</th>
                  <th>Shift 1&2</th>
                  <th>Shift 3</th>
                  <th>Keterangan</th>
                  <?php if ($role == '1' or $role == '5') { ?>
                    <th>Aksi</th>
                  <?php } ?>

                </tr>
              </thead>


              <tbody>
                <?php
                $no = 0;
                foreach ($adjust->result() as $data) {
                  $no = $no + 1; ?>
                  <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $data->periode_awal ?></td>
                    <td><?php echo $data->periode_akhir ?></td>
                    <td><?php echo $data->tanggal ?></td>
                    <td><?php echo $data->nik ?></td>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <td><?php echo $data->indeks_hr ?></td>
                    <td><?php echo $data->jml_makan ?></td>
                    <td><?php echo $data->jml_transport ?></td>
                    <td><?php echo $data->jam_lbr1 ?></td>
                    <td><?php echo $data->jam_lbr2 ?></td>
                    <td><?php echo $data->jam_lbr3 ?></td>
                    <td><?php echo $data->premi1 ?></td>
                    <td><?php echo $data->premi2 ?></td>
                    <td><?php echo $data->keterangan ?></td>
                    <?php if ($role == '1' or $role == '5') { ?>
                      <td><a href="<?php echo base_url() ?>Upah/adjust_edit/<?php echo $data->recid_auph ?>"><button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a><a href='<?php echo base_url() ?>Upah/adjust_hapus/<?php echo $data->recid_auph ?>'><button class='btn btn-danger btn-xs'><span class='fa fa-trash'></button></a></center>
                      </td>
                    <?php } ?>
                  </tr>

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


<script>
  $(document).ready(function() {});

  function getData() {
    tgl_mulai = document.getElementById("periode_awal").value;
    tgl_akhir = document.getElementById("periode_akhir").value;
    jenis = document.getElementById("kategori").value;
    var table = $('#t_absen').DataTable();
    table.destroy();
    var table = $('#t_absen').DataTable({
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
        url: "<?php echo base_url(); ?>Upah/adjustment_periode",
        dataType: 'JSON',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_akhir: tgl_akhir,
          jenis: jenis
        },
      },
    });
  }
</script>
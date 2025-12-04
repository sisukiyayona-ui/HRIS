<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Data Izin Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <?php
            if ($role == '1' or $role == '3'  or $role == '5' or $role == '32') { ?>
              <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user">
                <i class="fa fa-plus"></i> | Tambah Data </a>
              <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>Absen/upload_izin">
                <i class="fa fa-upload"></i> | Upload Data </a>
              <br>
              <br>
            <?php } ?>

            <form method="post">
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Dari Tanggal<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Sampai Tanggal<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" required="required" value="<?php echo date('Y-m-d') ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Jenis Absen<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <select class="form-control selectpicker" data-live-search="true" id="jenis">
                    <option value="All">Semua</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Terlambat Terencana">Terlambat Terencana</option>
                    <option value="Terlambat Tidak Terencana">Terlambat Tidak Terencana</option>
                    <option value="Pulang">Pulang</option>
                    <option value="Keluar">Keluar</option>
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
                  <th>Tanggal</th>
                  <th>Nik</th>
                  <th>Nama</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <th>Jenis</th>
                  <th>Jam Masuk</th>
                  <th>Jam Keluar</th>
                  <th>Over Durasi</th>
                  <?php
                  if ($role == 1) { ?>
                    <th>Over Durasi</th>
                  <?php } ?>
                  <th>Status</th>
                  <th>Keterangan</th>
                  <?php if ($role != '2') { ?>
                    <th>Validasi</th>
                    <th>Aksi</th>
                  <?php } ?>

                </tr>
              </thead>


              <tbody>
                <?php
                $no = 0;
                foreach ($izin->result() as $data) {
                  $no = $no + 1; ?>
                  <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $data->tgl_izin ?></td>
                    <td><?php echo $data->nik ?></td>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <td><?php echo $data->indeks_hr ?></td>
                    <td><?php echo $data->indeks_jabatan ?></td>
                    <td><?php echo $data->jenis ?></td>
                    <td><?php echo $data->jam_in ?></td>
                    <td><?php echo $data->jam_out ?></td>
                    <?php

                    $status = "";
                    $cek = $this->db->query("SELECT ja.jenis, ja.keterangan, ja.jam_in from master_absen.hadir_barcode h left join master_absen.jenis_absen ja on ja.recid_jenisabsen = h.status where recid_karyawan = $data->recid_karyawan and tanggal = '$data->tgl_izin'");
                    foreach ($cek->result() as $c) {
                      $status = $c->jenis . " - " . $c->keterangan;
                      $jam_msk = $c->jam_in;
                    }

                    if ($data->over_durasi != "" or $data->over_durasi != null) {
                      $dur = explode(".", $data->over_durasi);
                      $jam = $dur[0];
                      $menit = $dur[1];

                    ?><td><?php echo $jam . " jam" . $menit . " menit"; ?></td>
                    <?php } else { ?>
                      <td></td>
                    <?php } ?>

                    <?php
                    if ($role == '1') { ?>
                      <td><?php echo $data->over_durasi ?></td>
                    <?php }
                    ?>
                    <td><?php echo $status ?></td>
                    <td><?php echo $data->keterangan ?></td>
                    <td>
                      <center>
                        <?php if ($role != '2') {
                          echo ($data->perlu_validasi == '1') ? "Belum Validasi" : ''; ?>
                    </td>
                    <?php
                          if ($role == '1' or $role == '3' or $role == '5') { ?>
                      <td><a href="<?php echo base_url() ?>Absen/izin_edit/<?php echo $data->izin_recid ?>"><button class='btn btn-info btn-xs'><span class='fa fa-edit'></button></a><a href='<?php echo base_url() ?>Absen/izin_hapus/<?php echo $data->izin_recid ?>'><button class='btn btn-danger btn-xs'><span class='fa fa-trash'></button></a></center>
                      </td>
                    <?php } else {
                            echo "<td></td>";
                          }
                    ?>

                  </tr>
                <?php } ?>

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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tambah Izin</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Absen/izin_insert" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div>
                <select name="nik" id="nik" class="selectpicker form-control col-md-12 col-xs-12" data-live-search="true" required="required" onchange="detail_karyawan()">
                  <option value="">-- Pilih --</option>
                  <?php
                  foreach ($emp as $emp) { ?>
                    <option value="<?php echo $emp->recid_karyawan ?>"><?php echo $emp->nama_karyawan ?> (<?php echo $emp->nik ?>)</option>
                  <?php }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='niks' class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='bagian' class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='jabatan' class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myDatepicker4'>
                <input type='text' class="form-control" name="tanggal" id="tanggal" placeholder="tanggal" required="required" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="radio" name="jenis" value="Terlambat Terencana" required="required" checked="checked"> Terlambat Terencana
              <input type="radio" name="jenis" value="Terlambat Tidak Terencana" required="required" checked="checked"> Terlambat Tidak Terencana
              <input type="radio" name="jenis" value="Pulang"> Pulang
              <input type="radio" name="jenis" value="Keluar"> Keluar
            </div>
          </div>
          <div class="item form-group" id="jam_masuk">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Masuk <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime1'>
                <input type='text' class="form-control" name="jam_in" />
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group" id="jam_keluar" style=" display: none">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Keluar <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime2'>
                <input type='text' class="form-control" name="jam_out" required="required" />
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea name="keterangan" id="keterangan" class="form-control" required="required"></textarea>
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
        <h4 class="modal-title" id="myModalLabel">Edit Izin</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>Absen/izin_update" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div>
                <input type="hidden" id='izin_recid' name="izin_recid" class="form-control" readonly="readonly">
                <input type="text" id='nama2' class="form-control" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">NIK <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='niks2' name="niks2" class="form-control" readonly="readonly">
              <input type="hidden" id='recid_karyawan' name="recid_karyawan" class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='bagian2' class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jabatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id='jabatan2' class="form-control" readonly="readonly">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Tanggal <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myDatepicker3'>
                <input type='text' class="form-control" name="tanggal2" id="tanggal2" placeholder="tanggal" required="required" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jenis <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="radio" name="jenis2" id="jenis2" value="Terlambat Terencana"> Terlambat Terencana <br>
              <input type="radio" name="jenis2" id="jenis2" value="Terlambat Tidak Terencana"> Terlambat Tidak Terencana <br>
              <input type="radio" name="jenis2" id="jenis2" value="Pulang"> Pulang <br>
              <input type="radio" name="jenis2" id="jenis2" value="Keluar"> Keluar <br>
            </div>
          </div>
          <div class="item form-group" id="jam_masuk">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Masuk <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime3'>
                <input type='text' class="form-control" name="jam_in" id="ejam_masuk" />
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group" id="jam_keluar">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Jam Keluar <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class='input-group date' id='myTime4'>
                <input type='text' class="form-control" name="jam_out" id="ejam_keluar" />
                <span class="input-group-addon">
                  <span class="fa fa-clock-o"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Keterangan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea name="keterangan2" id="keterangan2" class="form-control" required="required"></textarea>
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
    $('input:radio[name=jenis]').change(function() {
      if (this.value == 'Terlambat Terencana' || this.value == 'Terlambat Tidak Terencana') {
        $("#jam_keluar").hide();
        $("#jam_masuk").show();
      } else if (this.value == "Pulang") {
        $("#jam_keluar").show();
        $("#jam_masuk").hide();
      } // punya if
      else {
        $("#jam_keluar").show();
        $("#jam_masuk").show();
      }
    });

    // Untuk sunting
    $('#edit_user').on('show.bs.modal', function(event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal = $(this)

      // Isi nilai pada field
      modal.find('#izin_recid').attr("value", div.data('gh_recid'));
      modal.find('#nama2').attr("value", div.data('nama'));
      modal.find('#bagian2').attr("value", div.data('bag'));
      modal.find('#jabatan2').attr("value", div.data('jbtn'));
      modal.find('#tanggal2').attr("value", div.data('tanggal'));
      modal.find('#niks2').attr("value", div.data('nik2'));
      modal.find('#recid_karyawan').attr("value", div.data('nik'));
      modal.find('#ejam_masuk').attr("value", div.data('jam_in'));
      modal.find('#ejam_keluar').attr("value", div.data('jam_out'));
      // alert(div.data('jenis'));
      if (div.data('jenis') == 'Pulang') {
        $('input:radio[id="jenis2"][value="Pulang"]').prop('checked', true);
      } else if (div.data('jenis') == 'Terlambat') {
        $('input:radio[id="jenis2"][value="Terlambat"]').prop('checked', true);
      } else {
        $('input:radio[id="jenis2"][value="Keluar"]').prop('checked', true);
      }
      // $("#jenis2").val(div.data('jenis'));
      $("#keterangan2").val(div.data('keterangan'));
    });
  });

  function detail_karyawan() {
    var nik = document.getElementById('nik').value;
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Absen/karyawan_detail", // Isi dengan url/path file php yang dituju
      data: {
        nik: nik
      }, // data yang akan dikirim ke file yang dituju
      dataType: "json",
      beforeSend: function(e) {
        if (e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response, data) { // Ketika proses pengiriman berhasil
        document.getElementById('niks').value = nik;
        document.getElementById('bagian').value = response[0][1];
        document.getElementById('jabatan').value = response[0][2];
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }

  function getData() {
    tgl_mulai = document.getElementById("tgl_mulai").value;
    tgl_akhir = document.getElementById("tgl_akhir").value;
    jenis = document.getElementById("jenis").value;
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
        url: "<?php echo base_url(); ?>Absen/izin_periode",
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
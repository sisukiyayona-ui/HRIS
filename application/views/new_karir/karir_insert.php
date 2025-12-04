<!-- page content -->

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Karir</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Karir Info</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>index.php/Karir/karir_pinsert" id="karir" novalidate>

              <!-- <span class="section">Personal Info</span>-->
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat">Kategori Karir<span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="selectpicker form-control  col-md-12 col-xs-12" id="kategori" name="kategori">
                    <option value=""> -- Pilih --</option>
                    <option value="Awal"> Awal</option>
                    <option value="Perpanjang"> Perpanjang</option>
                    <option value="Angkat Tetap"> Angkat Tetap</option>
                    <option value="Promosi"> Promosi</option>
                    <option value="Penempatan Kerja"> Penempatan Kerja</option>
                    <option value="Demosi"> Demosi</option>
                    <option value="Rotasi"> Rotasi</option>
                    <option value="Mutasi"> Mutasi</option>
                    <option value="Akhir"> Akhir</option>
                    <option value="Sanksi"> Sanksi</option>
                  </select>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true" required="required" id="emp12" name="nik">
                    <option value="">-- Pilih --</option>
                    <?php
                    foreach ($karyawan as $option) {
                      echo "<option value='$option->recid_karyawan'>$option->nama_karyawan ($option->nik)</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group" id="jenis_s" style=" display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Jenis Sanksi <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="selectpicker form-control  col-md-12 col-xs-12" id="jenis_sanksi" name="jenis_sanksi">
                    <option value=""> -- Pilih --</option>
                    <option value="Surat Teguran"> Surat Teguran</option>
                    <option value="SP1"> SP1</option>
                    <option value="SP1"> SP1</option>
                    <option value="SP2"> SP2</option>
                    <option value="SP3"> SP3</option>
                  </select>
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_legal">Nomor SK <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" name="no_perjanjian" placeholder="Nomor SK" required="required" type="text">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="judul">Judul SK <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="judul_perjanjian" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_karir">Tanggal Mulai <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker5'>
                    <input type='text' class="form-control" name="tgl_m_karir" required="required" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group akhir">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_a_karir">Tanggal Akhir</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_a_karir" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="item form-group" style="display: none" id="genik">
                <label for="agama" class="control-label col-md-3">NIK<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type='text' class="form-control" name="nik1" id="nik1" readonly="readonly" />
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <input type='text' class="form-control" name="nik2" id="nik2" readonly="readonly" />
                    <input type='hidden' class="form-control" id="spm" readonly="readonly" />
                  </div>
                </div>
              </div>
              <div class="item form-group akhir">
                <label for="agama" class="control-label col-md-3">Bagian<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_bag" id="recid_bag" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                    <?php
                    echo "<option value=''>-- Pilih --</option>";
                    foreach ($bagian as $option) {
                      echo "<option value='$option->recid_bag'>$option->indeks_hr ($option->nama_bag)</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group akhir">
                <label for="agama" class="control-label col-md-3">Sub Bagian<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_subbag" id="recid_subbag" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                    <?php
                    echo "<option value=''>-- Pilih --</option>";
                    foreach ($sub_bagian as $option) {
                      echo "<option value='$option->recid_subbag'>$option->sub_bag</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group akhir">
                <label for="jabatan" class="control-label col-md-3">Jabatan<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_jbtn" id="recid_jbtn" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                    <?php
                    echo "<option value=''>-- Pilih --</option>";
                    foreach ($jabatan as $option) {
                      echo "<option value='$option->recid_jbtn'>$option->indeks_jabatan ($option->sts_jabatan)</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group akhir">
                <label for="jabatan" class="control-label col-md-3">Golongan<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="recid_golongan" id="recid_golongan" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                    <?php
                    echo "<option value=''>-- Pilih --</option>";
                    foreach ($golongan->result() as $option) {
                      if ($option->masa_kerja != NULL) {
                        $note = " - $option->masa_kerja ( $option->note )";
                      } else {
                        $note = "";
                      }
                      if ($data->recid_golongan == $option->recid_golongan) {
                        echo "<option value='$option->recid_golongan' selected='selected'>$option->nama_golongan $note </option>";
                      } else {
                        echo "<option value='$option->recid_golongan'>$option->nama_golongan $note</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="item form-group akhir">
                <label for="jabatan" class="control-label col-md-3">Penempatan<span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="penempatan" id="penempatan" class="selectpicker form-control  col-md-12 col-xs-12" data-live-search="true">
                    <option value="Industri">Industri</option>
                    <option value="Baros">Baros</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Pav14">Pav14</option>
                    <option value="Kebon Jeruk">Kebon Jeruk</option>
                    <option value="SPM">SPM</option>
                  </select>
                </div>
              </div>

              <div class="item form-group" id="sts_karyawan" style="display: none">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="posisi">Status Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12" id="kry_aktif" style="display: none">
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="Aktif" checked="checked" /> Aktif
                </div><br>

                <div class="col-md-6 col-sm-6 col-xs-12" id="kry_keluar" style="display: none">
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="Resign" checked="checked" /> Resign
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="Pensiun" /> Pensiun
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="Pensiun Dini" /> Pensiun Dini
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="PHK" /> PHK
                  <input type="radio" name="sts_aktif" id="tidak_aktif" value="Mutasi" /> Mutasi
                </div><br>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">File SK
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <span style="color: red">*.pdf</span>
                  <input type="file" name="scan_perjanjian" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Keterangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="note" name="note" class="form-control col-md-7 col-xs-12"></textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"> <button type="button" class="btn btn-primary">Cancel</button></a>
                  <button id="send" type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
            <!--/ Content Form -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->


<script>
  $(document).ready(function() {
    $('#kategori').change(function() {
      ubah_jenis();
    });
  });

  function ubah_jenis() {
    var test = $("#kategori").val();
    if (test == 'Awal') {
      $("#sts_karyawan").show();
      $("#kry_aktif").show();
      $("#kry_keluar").hide();
      $("#genik").show();
      document.getElementById("nik1").readOnly = true;
      document.getElementById("nik2").readOnly = true;
      $('#myDatepicker5').datetimepicker({
          format: 'YYYY-MM-DD'
        })
        .on('dp.change', function(e) {
          if (e.date) {
            var tgl = e.date.format();
            console.log(tgl);
            var thn = tgl.substring(0, 4);
            var bln = tgl.substring(7, 5);
            var da = tgl.substring(10, 8);
            spm = document.getElementById('spm').value;
            if (spm == 'Ya') {
              var iden = '7';
              var nik = iden + thn + bln + da;
            } else {
              var nik = thn + bln + da;
            }
            $("#nik1").val(nik);
          }
        })
      $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
    } else if (test == 'Akhir') {
      $("#sts_karyawan").show();
      $("#kry_keluar").show();
      $("#kry_aktif").hide();
      $(".akhir").hide();
      $("#aktif").show();
      $("#genik").hide();
    } else if (test == 'Sanksi') {
      $("#jenis_s").show();
      $("#genik").hide();
      $("#sts_karyawan").show();
      $("#kry_aktif").hide();
      $("#kry_keluar").hide();
      $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
    } else {
      $("#genik").hide();
      $("#sts_karyawan").show();
      $("#kry_aktif").show();
      $("#kry_keluar").hide();
      $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
    }
  }

  $('#emp12').change(function() {
    var id = $("#emp12").val();
    var test = $("#kategori").val();
    if (test == "Sanksi") {
      var id = $("#emp12").val();
      console.log = id;
      $.ajax({ //---------------------------------------- auto fill bagian --------------------------------------------------------
        type: "POST", // 
        url: "<?php echo base_url(); ?>Karyawan/cek_data",
        data: {
          id: id
        },
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(data, response) { // Ketika proses pengiriman berhasil           
          $('select[name=recid_bag]').val(data[0]);
          $('select[name=recid_jbtn]').val(data[1]);
          $('select[name=recid_subbag]').val(data[2]);
          $('select[name=recid_golongan]').val(data[3]);
          $('.selectpicker').selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
      $("#genik").hide();
      $("#sts_karyawan").show();
      $("#kry_aktif").show();
      $("#kry_keluar").hide();
      $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
    } else {
      $.ajax({ //---------------------------------------- nik --------------------------------------------------------
        type: "POST", // 
        url: "<?php echo base_url(); ?>Karyawan/cek_spm",
        data: {
          id: id
        },
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data) { // Ketika proses pengiriman berhasil           
          document.getElementById('nik2').value = response[0][1];
          document.getElementById('spm').value = response[0][0];
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
      ubah_jenis();
    }

  });


  $('input:radio[name=spm]').change(function() {
    // alert("change!");
    if (this.value == 'Ya') {
      $("#tmp_toko").show();
      $("#tmp_kota").show();
    } else {
      $("#tmp_toko").hide();
      $("#tmp_kota").hide();
    }
  });
</script>
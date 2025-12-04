<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Report Kehadiran Mingguan</h3>
      </div>

      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Report Persentase Kehadiran Mingguan</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Absen/persentase_mingguan_adm" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <br>
                <div class="col-md-1 col-sm-1 col-xs-1"><label>Tanggal Mulai</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="sejak" id="tgl_mulai" value="<?php echo date("Y-m-d") ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1"><label>Tanggal Akhir</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="sampai" id="tgl_akhir" value="<?php echo date("Y-m-d") ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>

              </div>

              <!--  <div class="col-md-12 col-sm-12 col-xs-12">
                              <br>
                              <div class="col-md-1 col-sm-1 col-xs-1"><label>Pilih Norma</label></div>
                              <div class="col-md-4 col-sm-2 col-xs-2">
                               <select class="form-control selectpicker" multiple="multiple" data-actions-box="true" data-live-search="true" id="jenis" name="norma[]">
                                <?php
                                foreach ($jenis->result() as $j) { ?>
                                  <option value="<?php echo $j->recid_jenisabsen ?>" selected="selected"><?php echo $j->keterangan . " - " . $j->jenis ?></option>
                                <?php }
                                ?>
                              </select> 
                              </div>
                            </div> -->

              <div class="col-md-12 col-sm-12 col-xs-12">
                <br>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <label>Department Group</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control selectpicker" multiple id="divisi" name="divisi[]">
                    <?php
                    foreach ($dept_group->result() as $dg) { ?>
                      <option value="<?php echo $dg->dept_group ?>"><?php echo $dg->dept_group ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <label>Departement</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control selectpicker" multiple name="departement[]" id="departement">

                  </select>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <label>Bagian</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control selectpicker" multiple name="bagian10[]" id="bagian10">
                  </select>
                </div>

                <div class="col-md-1 col-sm-1 col-xs-1">
                  <label>Nama Karyawan</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control selectpicker" multiple name="karyawan[]" id="karyawan10">
                  </select>
                </div>
              </div>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <br><br>
            <div class="col-md-1 col-sm-1 col-xs-1">
              <button type="submit" class="btn btn-primary">Cari Report</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /page content -->

<script>
  $(document).ready(function() {
    $("#all").click(function() {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });

    // Chain Departement By Divisi
    $("#divisi").change(function() { // Ketika user mengganti atau memilih data provinsi
      $("#departement").hide(); // Sembunyikan dulu combobox kota nya
      var divisi = $("#divisi").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        // url: "<?php echo base_url(); ?>Karyawan/dept_by_divisi", // Isi dengan url/path file php yang dituju
        url: "<?php echo base_url(); ?>Karyawan/dept_by_divisi_my",
        data: {
          divisi: divisi
        }, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data) { // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#departement").html(response.list_karyawan).show();
          $('.selectpicker').selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
    });

    // Chain Bagian By Departement
    $("#departement").change(function() { // Ketika user mengganti atau memilih data provinsi
      $("#bagian10").hide(); // Sembunyikan dulu combobox kota nya
      var departement = $("#departement").val();
      var divisi = $("#divisi").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        // url: "<?php echo base_url(); ?>Karyawan/bagian_by_dept", // Isi dengan url/path file php yang dituju
        url: "<?php echo base_url(); ?>Karyawan/bagian_by_dept_my", // Isi dengan url/path file php yang dituju
        data: {
          departement: departement,
          divisi: divisi
        }, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data) { // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#bagian10").html(response.list_bagian).show();
          $('.selectpicker').selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
    });

    // Chain Karyawan By Bagian
    $("#bagian10").change(function() { // Ketika user mengganti atau memilih data provinsi
      $("#karyawan10").hide(); // Sembunyikan dulu combobox kota nya
      var bagian = $("#bagian10").val();
      console.log(bagian);
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url(); ?>Karyawan/karyawan_by_bagian", // Isi dengan url/path file php yang dituju
        data: {
          bagian: bagian
        }, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response, data) { // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#karyawan10").html(response.list_karyawan).show();
          $('.selectpicker').selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
    });
  });
</script>
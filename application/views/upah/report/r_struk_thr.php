<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> Print Struk THR</h3>
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
            <h2>Print Struk THR</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Upah/print_struk_thr" class="form-horizontal form-label-left" id="rmster-form" novalidate data-parsley-validate>
              <!--  -->
              <div class="col-md-12 col-sm-12 col-xs-12"><br>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <label>Department Group</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <select class="form-control selectpicker" multiple id="divisi" name="divisi[]">
                        <?php 
                        foreach ($paygroup->result() as $dg) {?>
                        <option value="<?php echo $dg->pay_group?>"><?php echo $dg->pay_group?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <label>Departement</label>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <select class="form-control selectpicker" multiple name="departement[]" id="departement"></select>
                </div>
			        </div>

              <div class="col-md-12 col-sm-12 col-xs-12"><br>
              <div class="col-md-2 col-sm-2 col-xs-2">
                          <label>Bagian</label>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2">
                          <select class="form-control selectpicker" multiple name="bagian10[]" id="bagian10"></select>
                      </div>

                      <div class="col-md-2 col-sm-2 col-xs-2">
                          <label>Nama Karyawan</label>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2">
                          <select class="form-control selectpicker" multiple name="karyawan[]" id="karyawan10"></select>
                      </div>
                  </div>


          <div class="col-md-12 col-sm-12 col-xs-12">
            <br><br>
            <div class="col-md-1 col-sm-1 col-xs-1">
              <button type="submit" class="btn btn-primary">Download Struk</button>
              <!-- <button type="submit" class="btn btn-primary">Cari Report</button> -->
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

</div>
</div>

<script>
  $(document).ready(function() {
    // cutoff();
    $("#all").click(function() {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
});
    // Chain Departement By Paygroup
    $("#divisi").change(function() { // Ketika user mengganti atau memilih data provinsi
      $("#departement").hide(); // Sembunyikan dulu combobox kota nya
      var divisi = $("#divisi").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url(); ?>Karyawan/dept_by_paygroup", // Isi dengan url/path file php yang dituju
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
        url: "<?php echo base_url(); ?>Karyawan/bagian_by_payroll", // Isi dengan url/path file php yang dituju
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
      var bulan = $("#bulan").val();
      var tahun = $("#tahun").val();
      console.log(bagian);
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url(); ?>Karyawan/karyawanoffdown_by_bagian", // Isi dengan url/path file php yang dituju
        data: {
          bagian: bagian,
          bulan: bulan,
          tahun: tahun
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
</script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Kalkulasi Upah</h3>
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
            <h2>Kalkulasi Upah</h2>

            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Upah/download_upah" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-2 col-sm-2 col-xs-2"><label>Bulan</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <?php $bulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                  $x = 0; ?>
                  <select name="bulan" class="form-control" id="bulan">
                    <?php
                    for ($i = 0; $i < count($bulan); $i++) { 
                        if($bulan[$i] == $bulans){?>
                            <option value="<?php echo $x = $x + 1 ?>"selected><?php echo $bulan[$i] ?></option>
                        <?php }else{?>
                            <option value="<?php echo $x = $x + 1 ?>"><?php echo $bulan[$i] ?></option>
                        <?php }?>
                      
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2"><label>Tahun</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                  <select class="form-control" name="tahun" id="tahun">
                    <?php
                    foreach ($tahun->result() as $t) { ?>
                      <option value="<?php echo $t->tahun ?>"><?php echo $t->tahun ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
              <div class="col-md-2 col-sm-2 col-xs-2"><label>Periode Awal</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" value="<?php echo $periode_awal ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </div>
              <div class="col-md-2 col-sm-2 col-xs-2"><label>Periode Akhir</label></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_akhir" id="tgl_akhir" value="<?php echo $periode_akhir ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
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
    // cutoff();
    $("#all").click(function() {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });

      $("#bulan").change(function() { // Ketika user mengganti atau memilih data provinsi
     cut_off();
    });

    $("#tahun").change(function() { // Ketika user mengganti atau memilih data provinsi
     cut_off();
    });
});

function cut_off()
{
      var bulan = $("#bulan").val();
      var tahun = $("#tahun").val();
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "<?php echo base_url(); ?>Upah/cek_cut_off", // Isi dengan url/path file php yang dituju
        data: {
          bulan: bulan, tahun : tahun
        }, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(data, response) { // Ketika proses pengiriman berhasil
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
        $("#tgl_mulai").val(data[0]);
        $("#tgl_akhir").val(data[1]);
        //  document.GetElementById('periode_akhir').value= data[1];
        },
        error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
        }
      });
}

//     // Chain Departement By Divisi
//     $("#divisi").change(function() { // Ketika user mengganti atau memilih data provinsi
//       $("#departement").hide(); // Sembunyikan dulu combobox kota nya
//       var divisi = $("#divisi").val();
//       $.ajax({
//         type: "POST", // Method pengiriman data bisa dengan GET atau POST
//         url: "<?php echo base_url(); ?>Karyawan/dept_by_divisi", // Isi dengan url/path file php yang dituju
//         data: {
//           divisi: divisi
//         }, // data yang akan dikirim ke file yang dituju
//         dataType: "json",
//         beforeSend: function(e) {
//           if (e && e.overrideMimeType) {
//             e.overrideMimeType("application/json;charset=UTF-8");
//           }
//         },
//         success: function(response, data) { // Ketika proses pengiriman berhasil
//           // set isi dari combobox kota
//           // lalu munculkan kembali combobox kotanya
//           $("#departement").html(response.list_karyawan).show();
//           $('.selectpicker').selectpicker('refresh');
//         },
//         error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
//           alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
//         }
//       });
//     });

//     // Chain Bagian By Departement
//     $("#departement").change(function() { // Ketika user mengganti atau memilih data provinsi
//       $("#bagian10").hide(); // Sembunyikan dulu combobox kota nya
//       var departement = $("#departement").val();
//       var divisi = $("#divisi").val();
//       $.ajax({
//         type: "POST", // Method pengiriman data bisa dengan GET atau POST
//         url: "<?php echo base_url(); ?>Karyawan/bagian_by_dept", // Isi dengan url/path file php yang dituju
//         data: {
//           departement: departement,
//           divisi: divisi
//         }, // data yang akan dikirim ke file yang dituju
//         dataType: "json",
//         beforeSend: function(e) {
//           if (e && e.overrideMimeType) {
//             e.overrideMimeType("application/json;charset=UTF-8");
//           }
//         },
//         success: function(response, data) { // Ketika proses pengiriman berhasil
//           // set isi dari combobox kota
//           // lalu munculkan kembali combobox kotanya
//           $("#bagian10").html(response.list_bagian).show();
//           $('.selectpicker').selectpicker('refresh');
//         },
//         error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
//           alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
//         }
//       });
//     });

//     // Chain Karyawan By Bagian
//     $("#bagian10").change(function() { // Ketika user mengganti atau memilih data provinsi
//       $("#karyawan10").hide(); // Sembunyikan dulu combobox kota nya
//       var bagian = $("#bagian10").val();
//       var bulan = $("#bulan").val();
//       var tahun = $("#tahun").val();
//       console.log(bagian);
//       $.ajax({
//         type: "POST", // Method pengiriman data bisa dengan GET atau POST
//         url: "<?php echo base_url(); ?>Karyawan/karyawan_by_bagian", // Isi dengan url/path file php yang dituju
//         data: {
//           bagian: bagian,
//           bulan: bulan,
//           tahun: tahun
//         }, // data yang akan dikirim ke file yang dituju
//         dataType: "json",
//         beforeSend: function(e) {
//           if (e && e.overrideMimeType) {
//             e.overrideMimeType("application/json;charset=UTF-8");
//           }
//         },
//         success: function(response, data) { // Ketika proses pengiriman berhasil
//           // set isi dari combobox kota
//           // lalu munculkan kembali combobox kotanya
//           $("#karyawan10").html(response.list_karyawan).show();
//           $('.selectpicker').selectpicker('refresh');
//         },
//         error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
//           alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
//         }
//       });
//     });
//   });
</script>
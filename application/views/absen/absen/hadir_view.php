<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Kehadiran Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <?php if ($this->session->flashdata('sukses')) { ?>
          <div class="alert alert-success col-12">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> <?php echo $this->session->flashdata('sukses'); ?>
          </div>
        <?php } else if ($this->session->flashdata('error')) { ?>
          <div class="alert alert-danger col-12">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Error!</strong> <?php echo $this->session->flashdata('eror'); ?>
          </div>
        <?php } else if ($this->session->flashdata('warning')) { ?>
          <div class="alert alert-info col-12">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>
          </div>
        <?php } else {
        } ?>
        <?php
        if ($role == '1' or $role == '3') { ?>
          <!-- <div class="x_panel">
                  <div class="x_title">
                    <h2>Generate Absensi</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                        <form method="post" action="<?php echo base_url() ?>Absen/generate_absen">
                          <div class="item form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Kehadiran Tanggal <span class="required">*</span>
                            </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                             <div class='input-group date' id='myDatepicker3'>
                              <input type='text' class="form-control" name="tgl_kehadiran" id="tgl_kehadiran" required="required" value="<?php echo date('Y-m-d') ?>"  />
                              <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                             </span>
                           </div>
                         </div>
                       </div>
                       <div class="item form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Bagian <span class="required">*</span>
                        </label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <select class="form-control selectpicker" multiple data-actions-box="true" data-live-search="true" id="bagian[]" name="bagian[]">
                            <?php
                            foreach ($bagian as $b) { ?>
                                <option value="<?php echo $b->indeks_hr ?>"><?php echo $b->indeks_hr ?></option>
                              <?php }
                              ?>
                            </select> 
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">Generate Absen</button>
                          </div>
                        </div>
                      </form>
                    <div class="clearfix"></div>
                    <br>
                    <div>
                    </div>
                  </div>
                </div> -->
        <?php } ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <!-- Statistik Terlambat -->
        <div class="x_panel">
          <div class="x_title">
            <h2>Statistik Kehadiran Harian</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row tile_count" style="margin-left: 10px;">
              <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-check"></i> Tepat Waktu</span>
                <div class="count" id="count_tepat_waktu">0</div>
                <span class="count_bottom">Karyawan</span>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top" data-toggle="modal" data-target="#modalTerlambatList" style="cursor: pointer;"><i class="fa fa-clock-o"></i> Terlambat <i class="fa fa-eye"></i></span>
                <div class="count yellow" id="count_terlambat" style="cursor: pointer;" data-toggle="modal" data-target="#modalTerlambatList">0</div>
                <span class="count_bottom"><a href="#" data-toggle="modal" data-target="#modalTerlambatList" onclick="return false;">Lihat Detail</a></span>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-hourglass"></i> Izin/Tidak Hadir</span>
                <div class="count" id="count_izin">0</div>
                <span class="count_bottom">Karyawan</span>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total</span>
                <div class="count blue" id="count_total">0</div>
                <span class="count_bottom">Karyawan</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="x_panel">
          <div class="x_title">
            <form method="post">
              <div class="item form-group">
                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir">Tanggal<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6">
                  <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                  <input type="hidden" id="closed" value="<?php echo $closing ?>">
                  <?php
                  $jam = date("H");
                  if (($role == '30' or $role == '26' or $role = "35") and $jam < 10) { ?>
                    <button id="closing" type="button" class="btn btn-primary" onclick="closing_kehadiran();">Submit Kehadiran</button>
                    <button id="open" type="button" class="btn btn-warning" onclick="open_kehadiran();">Open Kehadiran</button>
                  <?php } ?>
                </div>
              </div>
            </form>
          </div>
          <div class="x_content">
            <!-- Content Table -->
            <table id="t_hadir" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIK</th>
                  <th>Nama Karyawan</th>
                  <th>Bagian</th>
                  <th>Jabatan</th>
                  <th>Golongan</th>
                  <th>Penempatan</th>
                  <th>Tanggal Kerja</th>
                  <th>Jam Masuk</th>
                  <th>Jam Pulang</th>
                  <th>Status</th>
                </tr>
              </thead>


              <tbody>
                <?php
                $no = 0;
                foreach ($absen->result() as $data) {
                  $bagian = $data->indeks_hr;
                  $bagian = substr($bagian, strpos($bagian, " ") + 1);

                  $jabatan = $data->indeks_jabatan;
                  $jabatan = substr($jabatan, strpos($jabatan, " ") + 1);

                  $golongan = $data->nama_golongan;
                  $golongan = substr($golongan, strpos($golongan, " ") + 1);

                  $struktur = $data->nama_struktur;
                  $struktur = substr($struktur, strpos($struktur, " ") + 1);

                  if ($data->recid_bag == 0) {
                    $bagian = "-";
                  } else {
                    if ($role == '1' or $role == '2') {
                      $bagian = $data->indeks_hr;
                    } else {
                      $bagian = $bagian;
                    }
                  }

                  if ($data->recid_jbtn == 0) {
                    $jabatan = "-";
                  } else {
                    if ($role == '1' or $role == '2') {
                      $jabatan = $data->indeks_jabatan;
                    } else {
                      $jabatan = $jabatan;
                    }
                  }
                ?>
                  <tr>
                    <td><?php echo $no = $no + 1 ?></td>
                    <td><?php echo $data->nik ?></td>
                    <td><?php echo $data->nama_karyawan ?></td>
                    <td><?php echo $bagian ?></td>
                    <td><?php echo $jabatan ?></td>
                    <td><?php echo $golongan ?></td>
                    <td><?php echo $data->penempatan ?></td>
                    <td><?php echo $data->tanggal ?></td>
                    <td><?php echo $data->jam_masuk ?></td>
                    <td><?php echo $data->jam_keluar ?></td>
                    <?php
                    if ($role == '30') {
                      $jam = date("H");
                      if ($jam >= 10) { ?>
                        <td><?php echo $data->keterangan . " - " . $data->jenis ?></td>
                      <?php } else { ?>
                        <td>
                          <center><select class="form-control" id="jenis<?php echo $data->recid_absen ?>" onchange="updateData(<?php echo $data->recid_absen ?>)">
                              <?php
                              foreach ($jenis->result() as $j) {
                                if ($j->recid_jenisabsen == $data->status) { ?>
                                  <option value="<?php echo $j->recid_jenisabsen ?>" selected><?php echo $j->jenis . " - " . $j->keterangan ?></option>
                                <?php } else { ?>
                                  <option value="<?php echo $j->recid_jenisabsen ?>"><?php echo $j->jenis . " - " . $j->keterangan ?></option>
                              <?php }
                              }
                              ?>
                            </select></center>
                        </td>
                      <?php }
                    } else if ($role == '5') { ?>
                      <td><?php echo $data->keterangan . " - " . $data->jenis ?></td>
                    <?php } else { ?>
                      <td>
                        <center><select class="form-control" id="jenis<?php echo $data->recid_absen ?>" onchange="updateData(<?php echo $data->recid_absen ?>)">
                            <?php
                            foreach ($jenis->result() as $j) {
                              if ($j->recid_jenisabsen == $data->status) { ?>
                                <option value="<?php echo $j->recid_jenisabsen ?>" selected><?php echo $j->jenis . " - " . $j->keterangan ?></option>
                              <?php } else { ?>
                                <option value="<?php echo $j->recid_jenisabsen ?>"><?php echo $j->jenis . " - " . $j->keterangan ?></option>
                            <?php }
                            }
                            ?>
                          </select></center>
                      </td>
                    <?php } ?>
                  <?php } ?>
                  <!-- looping foreach -->
                  </tr>

              </tbody>
            </table>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<!-- Modal Terlambat List -->
<div class="modal fade" id="modalTerlambatList" tabindex="-1" role="dialog" aria-labelledby="modalTerlambatLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #f0ad4e; color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalTerlambatLabel">
          <i class="fa fa-clock-o"></i> 
          Daftar Karyawan Terlambat - <span id="modal_tanggal"><?php echo date('d F Y') ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>Total:</strong> <span id="terlambat_count">0</span> karyawan terlambat
        </div>
        
        <div class="table-responsive">
          <table id="tbl_terlambat_list" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th><center>No</center></th>
                <th><center>NIK</center></th>
                <th><center>Nama Karyawan</center></th>
                <th><center>Bagian</center></th>
                <th><center>Jam Masuk</center></th>
                <th><center>Durasi Terlambat</center></th>
                <th><center>Status</center></th>
                <th><center>Aksi</center></th>
              </tr>
            </thead>
            <tbody id="tbody_terlambat">
              <!-- Diisi via AJAX -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="application/javascript">
  $(document).ready(function() {
    var closed = document.getElementById('closed').value;
    if (closed == '0') {
      $("#closing").show();
      $("#open").hide();
    } else {
      $("#closing").hide();
      $("#open").show();
    }

    // Initialize statistics on page load
    updateStatistics();

    var table = $('#t_hadir').DataTable({
      "responsive": false,
      "ordering": false,
      // "order": [[ 2, "desc" ]],
      "paging": true,
      "pageLength": 30,
      dom: 'Bfrtip',
      buttons: [
        'excel', 'pdf', 'print'
      ],
    });

    // Handle modal show event - load terlambat data
    $('#modalTerlambatList').on('show.bs.modal', function(e) {
      loadTerlambatData();
    });

    // Handle Validasi Terlambat button
    $(document).on('click', '.btn-validasi-terlambat', function(e) {
      e.preventDefault();
      let recid_hadir = $(this).data('recid-hadir');
      let nik = $(this).data('nik');
      let nama = $(this).data('nama');
      let btn = $(this);
      
      if (confirm('Apakah Anda yakin ingin memvalidasi terlambat untuk ' + nama + '?')) {
        $.ajax({
          url: '<?php echo base_url('absen/validasi_terlambat')?>',
          method: 'POST',
          data: { recid_hadir: recid_hadir },
          dataType: 'json',
          success: function(res) {
            if (res.success) {
              // Remove row from table
              btn.closest('tr').fadeOut(300, function() { $(this).remove(); });
              
              // Reload data
              loadTerlambatData();
              updateStatistics();
              
              alert('Terlambat berhasil divalidasi dan dipindahkan ke daftar izin');
            } else {
              alert('Error: ' + (res.message || 'Tidak diketahui'));
            }
          },
          error: function(xhr) {
            alert('Error: ' + xhr.responseText);
          }
        });
      }
    });
  });

  function updateStatistics() {
    let tgl = document.getElementById("tgl_mulai").value || '<?php echo date('Y-m-d') ?>';
    let html = $('#t_hadir tbody').html();
    
    // Parse tabel untuk menghitung statistik
    let tepat_waktu = 0;
    let terlambat = 0;
    let izin = 0;
    let total = 0;
    
    $('#t_hadir tbody tr').each(function() {
      total++;
      let jam_masuk = $(this).find('td:eq(8)').text().trim();
      
      if (jam_masuk && jam_masuk !== '-' && jam_masuk !== 'Jam Masuk') {
        // Format: HH:MM
        if (jam_masuk > '07:00') {
          terlambat++;
        } else {
          tepat_waktu++;
        }
      } else {
        izin++;
      }
    });
    
    $('#count_tepat_waktu').text(tepat_waktu);
    $('#count_terlambat').text(terlambat);
    $('#count_izin').text(izin);
    $('#count_total').text(total);
  }

  function loadTerlambatData() {
    let tgl = document.getElementById("tgl_mulai").value || '<?php echo date('Y-m-d') ?>';
    $('#modal_tanggal').text(new Date(tgl).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }));
    
    $.ajax({
      url: '<?php echo base_url('absen/get_terlambat_data')?>',
      method: 'POST',
      data: { tgl: tgl },
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          let html = '';
          let no = 1;
          
          $('#terlambat_count').text(res.data.length);
          
          if (res.data.length === 0) {
            html = '<tr><td colspan="8" class="text-center"><em>Tidak ada karyawan terlambat</em></td></tr>';
          } else {
            res.data.forEach(function(item) {
              let durasi = item.durasi_terlambat;
              let status_badge = item.is_validated == 1 ? 
                '<span class="label label-success">Tervalidasi</span>' : 
                '<span class="label label-warning">Belum Validasi</span>';
              
              let validasi_btn = item.is_validated == 1 ? 
                '<button class="btn btn-xs btn-success" disabled><i class="fa fa-check"></i> Sudah</button>' :
                '<button class="btn btn-xs btn-warning btn-validasi-terlambat" data-recid-hadir="' + item.recid_hadir + '" data-nik="' + item.nik + '" data-nama="' + item.nama_karyawan + '"><i class="fa fa-check"></i> Validasi</button>';
              
              html += '<tr>';
              html += '<td><center>' + no++ + '</center></td>';
              html += '<td>' + item.nik + '</td>';
              html += '<td>' + item.nama_karyawan + '</td>';
              html += '<td>' + (item.indeks_hr ? item.indeks_hr.substring(item.indeks_hr.indexOf(' ') + 1) : '-') + '</td>';
              html += '<td><center>' + item.jam_masuk + '</center></td>';
              html += '<td><center><span class="label label-warning">' + durasi + '</span></center></td>';
              html += '<td><center>' + status_badge + '</center></td>';
              html += '<td><center>' + validasi_btn + '</center></td>';
              html += '</tr>';
            });
          }
          
          $('#tbody_terlambat').html(html);
        } else {
          $('#tbody_terlambat').html('<tr><td colspan="8" class="text-center text-danger">Error: ' + res.message + '</td></tr>');
        }
      },
      error: function(xhr) {
        $('#tbody_terlambat').html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
      }
    });
  }
</script>

<script type="application/javascript">
  $(document).ready(function() {
    var closed = document.getElementById('closed').value;
    if (closed == '0') {
      $("#closing").show();
      $("#open").hide();
    } else {
      $("#closing").hide();
      $("#open").show();
    }

    var table = $('#t_hadir').DataTable({
      "responsive": false,
      "ordering": false,
      // "order": [[ 2, "desc" ]],
      "paging": true,
      "pageLength": 30,
      dom: 'Bfrtip',
      buttons: [
        'excel', 'pdf', 'print'
      ],
    });
  });
  /** After windod Load */
  $(window).bind("load", function() {
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
        <?php unset($_SESSION['sukses']); ?>
        <?php unset($_SESSION['eror']); ?>
        <?php unset($_SESSION['warning']); ?>
      });
    }, 3000);
  });

  function updateData(id) {
    jenis_id = "jenis" + id;
    jenis = document.getElementById(jenis_id).value;
    // alert(jenis);
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Absen/update_absen", // Isi dengan url/path file php yang dituju
      data: {
        hadir_recid: id,
        jenis_absen: jenis
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
        // $("#karyawan").html(response.list_kota).show();
        location.reload();
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }

  function getData() {
    tgl_mulai = document.getElementById("tgl_mulai").value;
    var table = $('#t_hadir').DataTable();
    table.destroy();
    var table = $('#t_hadir').DataTable({
      "responsive": false,
      "ordering": false,
      // "order": [[ 2, "desc" ]],
      "paging": true,
      "pageLength": 30,
      dom: 'Bfrtip',
      buttons: [
        'excel', 'pdf', 'print'
      ],
      "ajax": {
        type: "POST",
        url: "<?php echo base_url(); ?>Absen/get_hadir_periode",
        dataType: 'JSON',
        data: {
          tgl_mulai: tgl_mulai
        },
      },
    });
  }

  function closing_kehadiran() {
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Absen/closing_kehadiran", // Isi dengan url/path file php yang dituju
      dataType: "json",
      beforeSend: function(e) {
        if (e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response, data) { // Ketika proses pengiriman berhasil
        // set isi dari combobox kota
        // lalu munculkan kembali combobox kotanya
        // $("#karyawan").html(response.list_kota).show();
        location.reload();
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }

  function open_kehadiran() {
    $.ajax({
      type: "POST", // Method pengiriman data bisa dengan GET atau POST
      url: "<?php echo base_url(); ?>Absen/open_kehadiran", // Isi dengan url/path file php yang dituju
      dataType: "json",
      beforeSend: function(e) {
        if (e && e.overrideMimeType) {
          e.overrideMimeType("application/json;charset=UTF-8");
        }
      },
      success: function(response, data) { // Ketika proses pengiriman berhasil
        // set isi dari combobox kota
        // lalu munculkan kembali combobox kotanya
        // $("#karyawan").html(response.list_kota).show();
        location.reload();
      },
      error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
      }
    });
  }
</script>
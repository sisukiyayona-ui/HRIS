<?php $role = $this->session->userdata('role_id'); ?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Adjust Kehadiran Karyawan</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
           
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
                                </div>
                            </div>
                        </form>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Belum Absen</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Adjust Absen</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="tidak_lengkap" data-toggle="tab" aria-expanded="false">Absen Tidak Lengkap</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                           <table id="t_blmabsen" class="table table-striped table-bordered">
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                          <table id="t_adjust" class="table table-striped table-bordered">
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
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="tidak_lengkap">
                          <table id="t_pincang" class="table table-striped table-bordered">
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
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            
        </div>
    </div>
</div>
<!-- /page content -->

<script type="application/javascript">
$(document).ready(function() {
    getData1();
    getData2();
    getData3();
  
    });

    function getData()
    {
        getData1();
        getData2();
        getData3();
    }

    function getData1() {
        tgl_mulai = document.getElementById("tgl_mulai").value;
        var table = $('#t_blmabsen').DataTable();
        table.destroy();
        var table = $('#t_blmabsen').DataTable({
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
                url: "<?php echo base_url(); ?>AbsenBarcode/get_blm_absen_periode",
                dataType: 'JSON',
                data: {
                    tgl_mulai: tgl_mulai
                },
            },
        });
    }

    function getData2() {
        tgl_mulai = document.getElementById("tgl_mulai").value;
        var table = $('#t_adjust').DataTable();
        table.destroy();
        var table = $('#t_adjust').DataTable({
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
                url: "<?php echo base_url(); ?>AbsenBarcode/get_adjust_hadir_periode",
                dataType: 'JSON',
                data: {
                    tgl_mulai: tgl_mulai
                },
            },
        });
    }

    function getData3() {
        tgl_mulai = document.getElementById("tgl_mulai").value;
        var table = $('#t_pincang').DataTable();
        table.destroy();
        var table = $('#t_pincang').DataTable({
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
                url: "<?php echo base_url(); ?>AbsenBarcode/get_absen_sebelah",
                dataType: 'JSON',
                data: {
                    tgl_mulai: tgl_mulai
                },
            },
        });
    }
</script>
  <!-- page content -->
  <div class="right_col" role="main">
      <div class="">
          <div class="page-title">
              <div class="title_left">
                  <h3>Jadwal Shift Karyawan</h3>
              </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Jadwal Shift Karyawan </h2>
                          <div class="clearfix">
                          </div>
                      </div>
                      <div class="x_content">
                          <div class="item form-group">
                              <label class="col-md-1 col-sm-1 col-xs-12" for="tgl_m_karir"> Tanggal<span class="required">*</span>
                              </label>
                              <div class="col-md-2 col-sm-2 col-xs-12">
                                  <div class='input-group date' id='myDatepicker3'>
                                      <input type='text' class="form-control" name="tgl_mulai" id="tgl_mulai" required="required" value="<?php echo date('Y-m-d') ?>" />
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <div class="col-md-2 col-sm-2 col-xs-12">
                                  <button id="send" type="button" class="btn btn-success" onclick="getData();">Cari</button>
                                  <a href="<?php echo base_url() ?>AbsenBarcode/upload_shift"><button type="button" class="btn btn-primary">Upload Shift</button></a>
                              </div>
                          </div>
                          <br>
                          <br>
                          <table class="table" id="t_pantry">
                              <thead>
                                  <th>No</th>
                                  <th>NIK</th>
                                  <th>Nama Karyawan</th>
                                  <th>Bagian</th>
                                  <th>Jabatan</th>
                                  <th>Penempatan</th>
                                  <th>Tanggal</th>
                                  <th>Shift</th>
                                  <th>Aksi</th>
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
  <!-- /page content -->

  <!-- Modal Edit Data -->
  <!-- Large modal -->
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="edit_shift">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">

              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Edit Shift Karyawan</h4>
              </div>
              <div class="modal-body">
                  <!-- Content Modal -->
                  <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>AbsenBarcode/shift_update" novalidate>
                      <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <div>
                                  <input type="hidden" id='jadwal_recid' name="jadwal_recid" class="form-control" readonly="readonly">
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
                                  <input type='text' class="form-control" name="tanggal2" id="tanggal2" placeholder="tanggal" required="required" readonly/>
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Shift <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select class="selectpicker form-control" name="recid_jenisabsen" id="jenis_shift">
                                  <?php
                                    foreach ($shift->result() as $sf) { ?>
                                      <option value="<?php echo $sf->recid_jenisabsen ?>"><?php echo $sf->jenis . " - " . $sf->keterangan; ?></option>
                                  <?php }
                                    ?>
                              </select>
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

  <script src="<?php echo base_url() ?>assets/vendors/sweetalert/sweetalert.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {
          getData();

          // Untuk sunting
          $('#edit_shift').on('show.bs.modal', function(event) {
              var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
              var modal = $(this)

              // Isi nilai pada field
              modal.find('#jadwal_recid').attr("value", div.data('gh_recid'));
              modal.find('#nama2').attr("value", div.data('nama'));
              modal.find('#bagian2').attr("value", div.data('bag'));
              modal.find('#jabatan2').attr("value", div.data('jbtn'));
              modal.find('#tanggal2').attr("value", div.data('tanggal'));
              modal.find('#niks2').attr("value", div.data('nik2'));
              modal.find('#recid_karyawan').attr("value", div.data('nik'));
              //   modal.find('#jenis_shift').val(div.data('jenis_shift'));
              document.getElementById('jenis_shift').value = div.data('jenis_shift');
              $('.selectpicker').selectpicker('refresh');
              console.log(div.data('jenis_shift'));

          });
      });

      function getData() {
          tgl_mulai = document.getElementById("tgl_mulai").value;
          //   tgl_akhir = document.getElementById("tgl_akhir").value;
          //   jenis = $('#jenis').val();
          // console.log(jenis);
          var table = $('#t_pantry').DataTable();
          table.destroy();
          var table = $('#t_pantry').DataTable({
              "responsive": false,
              "bScrollCollapse": true,
              "bLengthChange": true,
              "searching": true,
              "dom": 'Bfrtip',
              buttons: [
                  'excel', 'print'
              ],
              "ajax": {
                  type: "POST",
                  url: "<?php echo base_url(); ?>AbsenBarcode/data_shift_date",
                  dataType: 'JSON',
                  data: {
                      tgl_mulai: tgl_mulai
                  },
              },
          });
      }
  </script>
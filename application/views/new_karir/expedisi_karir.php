  <?php $role = $this->session->userdata('role_id'); ?>
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>EXPEDITION HC</h3>
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>EXPEDITION HC</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <?php if ($role == '1' or $role == '2' or $role == '25') { ?>
                <a class="btn btn-info btn-sm" href="<?php echo base_url() ?>index.php/Karir/email_expedisi">
                  <i class="fa fa-upload"></i> | Ekspedisi Berkas
                </a>
              <?php } ?>

              <div style="height:350px;">
                <form method="post" action="">
                  <p>Tanggal Berdasarkan Email Yang Dikirim / Tanggal Data Dibuat</p>
                  <div class='input-group date col-lg-4' id='myDatepicker5'>
                    <input type='text' class="form-control" name="tgl_m_karir" id="tgl_notif" value=<?php echo date('y-m-d') ?> />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </form>
                <br>

                <table id="notif_karir" class="table table-striped table-bordered" style="display: none">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>NIK</th>
                      <th>Nama Karyawan</th>
                      <th>Bagian</th>
                      <th>Jabatan</th>
                      <th>Golongan</th>
                      <th>Jenis Karir</th>
                      <th>Periode</th>
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
  <!-- /page content -->

  <script>
    $(document).ready(function() {
      $('#myDatepicker5').datetimepicker({
        format: 'YYYY-MM-DD'
      });

      var expedisi = document.getElementById('tgl_notif').value;
      var table = $('#notif_karir').DataTable();
      table.destroy();
      var table = $('#notif_karir').DataTable({
        "responsive": true,
        "bScrollCollapse": true,
        "bLengthChange": true,
        "searching": true,
        "dom": 'Bfrtip',
        "bDestroy": true,
        buttons: [
          'excel', 'print'
        ],
        "ajax": {
          type: "POST",
          url: "<?php echo base_url(); ?>Karyawan/notif_expedisi",
          dataType: 'json',
          data: {
            expedisi: expedisi
          },
        },
      });
      $("#notif_karir").show();

      $('#myDatepicker5').datetimepicker().on('dp.change', function(event) {
        var expedisi = document.getElementById('tgl_notif').value;
        var table = $('#notif_karir').DataTable();
        table.destroy();
        var table = $('#notif_karir').DataTable({
          "responsive": true,
          "bScrollCollapse": true,
          "bLengthChange": true,
          "searching": true,
          "dom": 'Bfrtip',
          "bDestroy": true,
          buttons: [
            'excel', 'print'
          ],
          "ajax": {
            type: "POST",
            url: "<?php echo base_url(); ?>Karyawan/notif_expedisi",
            dataType: 'json',
            data: {
              expedisi: expedisi
            },
          },
        });
        $("#notif_karir").show();
      });
    });
  </script>
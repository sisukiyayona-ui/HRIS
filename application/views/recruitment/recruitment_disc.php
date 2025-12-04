<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3> DISC Candidates</h3>
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

          <div class="x_content">
            <!-- Content Table -->
            <div class="table-responsive">
              <table id="t_default" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>
                      <center>Nomor</center>
                    </th>
                    <th>
                      <center>Tgl Regis</center>
                    </th>
                    <th>
                      <center>No KTP</center>
                    </th>
                    <th>
                      <center>Nama Kandidat</center>
                    </th>
                    <th>
                      <center>Jenis Kelamin</center>
                    </th>
                    <th>
                      <center>Profile DISC</center>
                    </th>
                    <th>
                      <center>Pattern Type</center>
                    </th>
                    <th>
                      <center>Profile Type</center>
                    </th>
                    <th>D High</th>
                    <th>D Low</th>
                    <th>I High</th>
                    <th>I Low</th>
                    <th>S High</th>
                    <th>S Low</th>
                    <th>C High</th>
                    <th>C Low</th>
                    <th>D Diff</th>
                    <th>I Diff</th>
                    <th>S Diff</th>
                    <th>C Diff</th>
                    <th>
                      <center>Aksi</center>
                    </th>
                  </tr>
                </thead>


                <tbody>
                  <?php
                  $no = 0;
                  foreach ($report->result() as $r) {
                    if ($r->profile_disc == "D") {
                      $profile = "Dominant";
                    } else if ($r->profile_disc == "I") {
                      $profile = "Influence";
                    } else if ($r->profile_disc == "S") {
                      $profile = "Steadlines";
                    } else if ($r->profile_disc == "C") {
                      $profile = "Compliant";
                    } else {
                      $profile = "";
                    } ?>
                    <tr>
                      <td><?php echo $no = $no + 1 ?></td>
                      <td><?php echo $r->tgl_regis ?></td>
                      <td><?php echo $r->no_ktp ?></td>
                      <td><?php echo $r->nama_pelamar ?></td>
                      <td><?php echo $r->pjenkel ?></td>
                      <td><?php echo $r->profile_disc ?> - <?php echo $profile ?></td>
                      <td><?php echo $r->pattern_type ?></td>
                      <td><?php echo $r->profile_type ?></td>
                      <?php $ops = ["Z", "K", "S", "B"];
                      for ($op = 0; $op < count($ops); $op++) {
                        ${"tinggi" . $ops[$op]} = $this->db->query("SELECT * FROM disc.disc where tinggi = '$ops[$op]' and recid_karyawan = $r->recid_pelamar;"); ?>
                        <td><?php echo  ${"tinggi" . $ops[$op]}->num_rows() ?></td>
                        <?php ${"rendah" . $ops[$op]} = $this->db->query("SELECT * FROM disc.disc where rendah = '$ops[$op]' and recid_karyawan = $r->recid_pelamar;"); ?>
                        <td><?php echo ${"rendah" . $ops[$op]}->num_rows() ?></td>
                      <?php }

                      for ($op2 = 0; $op2 < count($ops); $op2++) {
                        ${"tinggi" . $ops[$op2]} = $this->db->query("SELECT * FROM disc.disc where tinggi = '$ops[$op2]' and recid_karyawan = $r->recid_pelamar;");

                        ${"rendah" . $ops[$op2]} = $this->db->query("SELECT * FROM disc.disc where rendah = '$ops[$op2]' and recid_karyawan = $r->recid_pelamar;");

                        ${"dif" . $ops[$op2]} = ${"tinggi" . $ops[$op2]}->num_rows() -  ${"rendah" . $ops[$op2]}->num_rows(); ?>
                        <td><?php echo  ${"dif" . $ops[$op2]}; ?></td>
                      <?php  }
                      ?>
                      <td><a target="__blank" href="<?php echo base_url() ?>index.php/Recruitment/detail_report_disc/<?php echo $r->recid_pelamar ?>"><button type="button" class="btn btn-sm btn-success">Info</button></a>
                        <a data-toggle="modal" data-target="#update_profile" data-recid_karyawan="<?php echo $r->recid_pelamar ?>" data-nama_karyawan="<?php echo $r->nama_pelamar ?>" data-nik="<?php echo $r->no_ktp ?>" data-disc="<?php echo $r->profile_disc ?>" data-pattern="<?php echo $r->pattern_type ?>" data-profile="<?php echo $r->profile_type ?>"><button class="btn btn-sm btn-info">update profile</button></a>
                      </td>
                    </tr>
                  <?php } ?>

                </tbody>
              </table>
            </div>
            <!--/ Content Table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->


<!-- Modal Edit Data -->
<!-- Large modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="update_profile">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Update Profile DISC</h4>
      </div>
      <div class="modal-body">
        <!-- Content Modal -->
        <form class="form-horizontal form-label-left" method="post" action="<?php echo base_url() ?>index.php/Recruitment/update_profile" novalidate>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">No Ktp <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="nik" id="enik" class="form-control" readonly>
              <input type="hidden" name="recid_karyawan" id="erecid_karyawan" class="form-control" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Nama
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="nama_karyawan" id="enama_karyawan" class="form-control" readonly>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_jbtn">Profile DISC <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control" name="profile_disc" id="edisc">
                <option value="">-- Choose Profile DISC --</option>
                <option value="D"> D (Dominat) </option>
                <option value="I"> I (Influence) </option>
                <option value="S"> S (Steadlines) </option>
                <option value="C"> C (Compliant) </option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Pattern Type <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="pattern_type" id="epattern" class="form-control">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Profile Type <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="profile_type" id="eprofile" class="form-control">
            </div>
          </div>
          <!--/ Content Modal -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value='Save changes'>
        </form>
      </div>

    </div>
  </div>
</div>
<!--/ Modal Edit Data -->

<script>
  $(document).ready(function() {
    $('#update_profile').on('show.bs.modal', function(event) {
      var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
      var modal = $(this)

      // Isi nilai pada field
      modal.find('#erecid_karyawan').attr("value", div.data('recid_karyawan'));
      modal.find('#enama_karyawan').val(div.data('nama_karyawan'));
      modal.find('#enik').val(div.data('nik'));
      modal.find('#edisc').val(div.data('disc'));
      modal.find('#epattern').val(div.data('pattern'));
      modal.find('#eprofile').val(div.data('profile'));
    });
  });
</script>
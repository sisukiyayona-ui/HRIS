<?php $role = $this->session->userdata('role_id'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Dashboard Absensi</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <?php
        if ($role == '1' or $role == '3') { ?>
          <div class="x_panel">
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
                      <input type='text' class="form-control" name="tgl_kehadiran" id="tgl_kehadiran" required="required" value="<?php echo date('Y-m-d') ?>" />
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
                  <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm">Generate Absen</button>
                    <a href="<?php echo base_url()?>Absen/cek_closing"><button type="button" class="btn btn-success btn-sm">Cek Closing</button></a>
                  </div>
                </div>
              </form>
              <div class="clearfix"></div>
              <br>
              <div>
              </div>
            </div>
          </div>
      </div>
    <?php } ?>

    <div class="row tile_count" style="margin-left: 10px;">
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
        <div class="count"><?php echo $totkar->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/All">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-clock-o"></i> Non Shift</span>
        <div class="count"><?php echo $non_shift->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/non_shift">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> WFH</span>
        <div class="count green"><?php echo $wfh->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/wfh">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Shift</span>
        <div class="count"><?php echo $shift->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/shift">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Sakit</span>
        <div class="count"><?php echo $sakit->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/sakit">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Cuti</span>
        <div class="count"><?php echo $cuti->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/cuti">Details</a></span>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Lainnya</span>
        <div class="count"><?php echo $lainnya->num_rows() ?></div>
        <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/lainnya">Details</a></span>
      </div>
      <?php
      if ($role  == '1' or $role == '2' or $role == '3' or $role == '5' or $role == '25') { ?>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-user"></i> Hadir Baros</span>
          <div class="count"><?php echo $baros->num_rows() ?></div>
          <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran/<?php echo date('Y-m-d') ?>/baros">Details</a></span>
        </div>
      <?php  }
      ?>
    </div>

    <?php
    if ($role == '1' or $role == '2' or $role == '3' or $role == '26') { ?>
      <div class="row tile_count" style="margin-left: 10px;">
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-user"></i> Total Karyawan SPM</span>
          <div class="count"><?php echo $totkar_spm->num_rows() ?></div>
          <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/All/spm">Details</a></span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-clock-o"></i> Hadir</span>
          <div class="count"><?php echo $hadir_spm->num_rows() ?></div>
          <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/hadir_spm/spm">Details</a></span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-user"></i> WFH SPM</span>
          <div class="count green"><?php echo $wfh_spm->num_rows() ?></div>
          <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/wfh/spm">Details</a></span>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
          <span class="count_top"><i class="fa fa-user"></i> Tidak Hadir SPM</span>
          <div class="count"><?php echo $tidak_hadir_spm->num_rows() ?></div>
          <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Absen/detail_kehadiran_spm/<?php echo date('Y-m-d') ?>/tidak_hadir_spm/spm">Details</a></span>
        </div>
      </div>
    <?php } ?>



    </div>
  </div>

</div>
</div>
<!-- /page content -->
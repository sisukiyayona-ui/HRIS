<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Validasi Perubahan Status Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Validation Results</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check text-success"></i></div>
                  <div class="count"><?php echo $validation_results['total_valid']; ?></div>
                  <h3>Valid Records</h3>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-times text-danger"></i></div>
                  <div class="count"><?php echo $validation_results['total_invalid']; ?></div>
                  <h3>Invalid Records</h3>
                </div>
              </div>
            </div>

            <?php if ($validation_results['total_invalid'] > 0): ?>
              <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4><i class="icon fa fa-warning"></i> Validation Errors Found</h4>
                <p><?php echo $validation_results['total_invalid']; ?> records have validation errors and cannot be imported.</p>
                <p>Please fix these errors before proceeding with the import.</p>
              </div>

              <h4>Invalid Records Details:</h4>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>NIK</th>
                      <th>Name</th>
                      <th>Errors</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($validation_results['invalid_records'] as $invalid_record): ?>
                      <tr>
                        <td><?php echo isset($invalid_record['NIK']) ? $invalid_record['NIK'] : ''; ?></td>
                        <td><?php echo isset($invalid_record['NAMA']) ? $invalid_record['NAMA'] : ''; ?></td>
                        <td>
                          <ul>
                            <?php foreach ($invalid_record['errors'] as $error): ?>
                              <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                          </ul>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4><i class="icon fa fa-check"></i> All Records Valid</h4>
                <p>All <?php echo $validation_results['total_valid']; ?> records passed validation and are ready for import.</p>
              </div>
            <?php endif; ?>

            <div class="form-group">
              <?php if ($validation_results['total_invalid'] == 0): ?>
                <a href="<?php echo base_url('Status_update_import/do_import'); ?>" class="btn btn-success">
                  <i class="fa fa-upload"></i> Import Data
                </a>
              <?php else: ?>
                <a href="<?php echo base_url('Status_update_import'); ?>" class="btn btn-primary">
                  <i class="fa fa-upload"></i> Upload New File
                </a>
              <?php endif; ?>
              <a href="<?php echo base_url('Status_update_import'); ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back to Upload
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
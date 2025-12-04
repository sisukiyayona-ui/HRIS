<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Employee Import Complete</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Import Summary</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-database text-info"></i></div>
                  <div class="count"><?php echo $import_summary['total_records']; ?></div>
                  <h3>Total Records</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check text-success"></i></div>
                  <div class="count"><?php echo $import_summary['successful_imports']; ?></div>
                  <h3>Successful Imports</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-plus-circle text-primary"></i></div>
                  <div class="count"><?php echo $import_summary['inserted_records']; ?></div>
                  <h3>New Records</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-refresh text-warning"></i></div>
                  <div class="count"><?php echo $import_summary['updated_records']; ?></div>
                  <h3>Updated Records</h3>
                </div>
              </div>
            </div>

            <?php if ($import_summary['failed_imports'] > 0): ?>
              <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4><i class="icon fa fa-warning"></i> Some Records Failed to Import</h4>
                <p><?php echo $import_summary['failed_imports']; ?> records could not be imported. Please check the details below.</p>
              </div>

              <h4>Failed Import Details:</h4>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>NIK</th>
                      <th>Name</th>
                      <th>Error Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($import_summary['errors'] as $error): ?>
                      <tr>
                        <td><?php echo isset($error['NIK']) ? $error['NIK'] : ''; ?></td>
                        <td><?php echo isset($error['NAMA']) ? $error['NAMA'] : ''; ?></td>
                        <td><?php echo $error['error_message']; ?></td>
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
                <h4><i class="icon fa fa-check"></i> Import Completed Successfully</h4>
                <p>All <?php echo $import_summary['successful_imports']; ?> records were processed successfully.</p>
                <p>Inserted: <?php echo $import_summary['inserted_records']; ?> | Updated: <?php echo $import_summary['updated_records']; ?></p>
              </div>
            <?php endif; ?>

            <div class="form-group">
              <a href="<?php echo base_url('Employee_import'); ?>" class="btn btn-primary">
                <i class="fa fa-upload"></i> Import Another File
              </a>
              <a href="<?php echo base_url('Karyawan'); ?>" class="btn btn-default">
                <i class="fa fa-list"></i> View Employees
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
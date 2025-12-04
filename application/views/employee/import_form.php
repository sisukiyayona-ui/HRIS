<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Bulk Employee Import</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php endif; ?>

        <div class="x_panel">
          <div class="x_title">
            <h2>Upload Excel File</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>Please upload an Excel file (.xlsx or .xls) containing employee data.</p>
            
            <form action="<?php echo base_url('Employee_import/upload'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_file">Select Excel File:
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="employee_file" id="employee_file" class="form-control" accept=".xlsx,.xls" required>
                  <p class="help-block">Supported formats: .xlsx, .xls (Max size: 10MB)</p>
                </div>
              </div>
              
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-primary">Upload and Parse</button>
                  <a href="<?php echo base_url('Employee_import/download_template'); ?>" class="btn btn-info">Download Template</a>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="x_panel">
          <div class="x_title">
            <h2>Instructions</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-8 col-sm-8 col-xs-12">
              <ol>
                <li>Download the template Excel file using the "Download Template" button</li>
                <li>Fill in employee data according to the template columns</li>
                <li>Save the file in Excel format (.xlsx or .xls)</li>
                <li>Upload the file using the form above</li>
                <li>Review the data preview to check parsing accuracy</li>
                <li>Click "Process & Import Data" to complete the import</li>
              </ol>
              
              <div class="divider-dashed"></div>
              
              <h4>Notes:</h4>
              <ul>
                <li><strong>No validation will be performed</strong> - data will be imported as-is</li>
                <li>Empty fields are acceptable</li>
                <li>Duplicate NIK entries may cause import failures</li>
                <li>Review the preview carefully to ensure correct parsing</li>
              </ul>
              
              <h4>Required Columns:</h4>
              <ul>
                <li><strong>NIK</strong> (Unique employee identifier)</li>
                <li><strong>NAMA</strong> (Employee name)</li>
                <li>Other fields are optional but recommended for complete records</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
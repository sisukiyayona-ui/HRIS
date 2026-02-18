<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Import Kontrak Karyawan Bulk</h3>
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
            <h2>Unggah File Excel Kontrak Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>Format file harus Excel (.xlsx) dengan struktur header berikut:</p>
            
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">NIK</th>
                    <th rowspan="3" style="vertical-align: middle; text-align: center;">STATUS KARYAWAN</th>
                    <th colspan="6" style="text-align: center;">KONTRAK</th>
                  </tr>
                  <tr>
                    <th colspan="2" style="text-align: center;">1</th>
                    <th colspan="2" style="text-align: center;">2</th>
                    <th colspan="2" style="text-align: center;">3</th>
                  </tr>
                  <tr>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                    <th style="text-align: center;">AWAL</th>
                    <th style="text-align: center;">AKHIR</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>123456789</td>
                    <td>KONTRAK</td>
                    <td>30-Jun-25</td>
                    <td>19-Sep-25</td>
                    <td>20-Sep-25</td>
                    <td>19-Dec-25</td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <ul>
              <li><strong>NIK</strong>: Nomor Induk Karyawan (wajib)</li>
              <li><strong>STATUS KARYAWAN</strong>: Status karyawan (wajib diisi dengan "KONTRAK" untuk karyawan kontrak)</li>
              <li><strong>Hanya karyawan dengan status "KONTRAK" yang dapat memiliki kontrak</strong></li>
              <li><strong>Karyawan dengan status "TETAP" tidak dapat memiliki kontrak</strong></li>
              <li><strong>AWAL</strong>: Tanggal mulai kontrak (format: DD-MMM-YY)</li>
              <li><strong>AKHIR</strong>: Tanggal akhir kontrak (format: DD-MMM-YY)</li>
              <li>Kolom bisa kosong jika tidak ada data kontrak</li>
            </ul>
            
            <div class="form-group">
              <a href="<?php echo base_url('Contract_import/download_template'); ?>" class="btn btn-info">
                <i class="fa fa-download"></i> Download Template
              </a>
            </div>

            <?php echo form_open_multipart('Contract_import/upload', array('class' => 'form-horizontal form-label-left', 'id' => 'upload-form')); ?>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contract_file">
                  Pilih File Excel <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" id="contract_file" name="contract_file" required="required" class="form-control col-md-7 col-xs-12" accept=".xlsx,.xls,.csv">
                  <p class="help-block">Format file: .xlsx, .xls, .csv</p>
                </div>
              </div>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success" id="upload-btn">
                    <i class="fa fa-upload"></i> Upload & Import
                  </button>
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
$(document).ready(function() {
  $('#upload-form').on('submit', function(e) {
    // Show processing message
    var uploadBtn = $('#upload-btn');
    uploadBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    
    // Show a loading indicator
    $('.x_content').append('<div class="overlay" id="upload-overlay"><i class="fa fa-refresh fa-spin"></i></div>');
  });
});
</script>
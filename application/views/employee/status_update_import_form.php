<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Import Perubahan Status Karyawan</h3>
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
            <h2>Unggah File Excel</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p>Silakan unggah file Excel (.xlsx, .xls, atau .csv) yang berisi data perubahan status karyawan.</p>
            
            <form action="<?php echo base_url('Status_update_import/upload'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status_file">Pilih File Excel:
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="status_file" id="status_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                  <p class="help-block">Format yang didukung: .xlsx, .xls, .csv (Ukuran maksimal: 10MB)</p>
                </div>
              </div>
              
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-primary">Unggah dan Parsing Data</button>
                  <a href="<?php echo base_url('Status_update_import/download_template'); ?>" class="btn btn-info">Download Template</a>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="x_panel">
          <div class="x_title">
            <h2>Petunjuk Penggunaan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-8 col-sm-8 col-xs-12">
              <ol>
                <li>Download file template Excel menggunakan tombol "Download Template"</li>
                <li>Isi data perubahan status karyawan sesuai dengan kolom template yang tersedia</li>
                <li>Simpan file dalam format Excel (.xlsx, .xls) atau CSV</li>
                <li>Unggah file menggunakan form di atas</li>
                <li>Tinjau preview data untuk memastikan akurasi parsing</li>
                <li>Klik "Proses & Import Data" untuk menyelesaikan import</li>
              </ol>
              
              <div class="divider-dashed"></div>
              
              <h4>Catatan Penting:</h4>
              <ul>
                <li><strong>NIK</strong> harus sesuai dengan data karyawan yang sudah ada di database</li>
                <li><strong>STATUS_KARYAWAN</strong> harus diisi dengan "KARYAWAN TETAP" atau "TETAP"</li>
                <li><strong>SK_KARY_TETAP_NOMOR</strong> adalah nomor surat keputusan status tetap</li>
                <li><strong>SK_KARY_TETAP_TANGGAL</strong> adalah tanggal surat keputusan status tetap</li>
                <li>Data akan diupdate ke tabel karyawan, mengubah status menjadi tetap</li>
              </ul>
              
              <h4>Kolom yang Diperlukan:</h4>
              <ul>
                <li><strong>NIK</strong> (Identifikasi unik karyawan - Wajib)</li>
                <li><strong>STATUS_KARYAWAN</strong> (Status baru karyawan - Wajib)</li>
                <li><strong>SK_KARY_TETAP_NOMOR</strong> (Nomor SK status tetap - Wajib)</li>
                <li><strong>SK_KARY_TETAP_TANGGAL</strong> (Tanggal SK status tetap - Wajib)</li>
              </ul>
              
              <h4>Format Data yang Direkomendasikan:</h4>
              <ul>
                <li><strong>Tanggal:</strong> Format DD/MM/YYYY, DD-MM-YYYY, atau YYYY-MM-DD</li>
                <li><strong>Status:</strong> Gunakan "KARYAWAN TETAP" atau "TETAP"</li>
              </ul>
              
              <div class="alert alert-warning">
                <strong>Perhatian:</strong> Proses ini akan mengupdate data karyawan menjadi status tetap, mengubah kolom kontrak menjadi "Tidak" dan mengosongkan tgl_akhir_kontrak.
              </div>
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="well">
                <h4>Tips Cepat:</h4>
                <ul>
                  <li>Gunakan template yang disediakan</li>
                  <li>Hapus baris kosong sebelum header</li>
                  <li>Jangan ganti nama kolom header</li>
                  <li>Simpan dalam format .xlsx untuk kompatibilitas terbaik</li>
                  <li>Cek NIK sebelum import untuk memastikan data karyawan sudah ada</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Import Karyawan Bulk</h3>
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
            <p>Silakan unggah file Excel (.xlsx atau .xls) yang berisi data karyawan.</p>
            
            <form action="<?php echo base_url('Employee_import/upload'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employee_file">Pilih File Excel:
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="employee_file" id="employee_file" class="form-control" accept=".xlsx,.xls" required>
                  <p class="help-block">Format yang didukung: .xlsx, .xls (Ukuran maksimal: 10MB)</p>
                </div>
              </div>
              
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-primary">Unggah dan Parsing Data</button>
                  <a href="<?php echo base_url('Employee_import/download_template'); ?>" class="btn btn-info">Download Template</a>
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
                <li>Isi data karyawan sesuai dengan kolom template yang tersedia</li>
                <li>Simpan file dalam format Excel (.xlsx atau .xls)</li>
                <li>Unggah file menggunakan form di atas</li>
                <li>Tinjau preview data untuk memastikan akurasi parsing</li>
                <li>Klik "Proses & Import Data" untuk menyelesaikan import</li>
              </ol>
              
              <div class="divider-dashed"></div>
              
              <h4>Catatan Penting:</h4>
              <ul>
                <li><strong>Tidak ada validasi yang akan dilakukan</strong> - data akan diimport apa adanya</li>
                <li>Kolom yang kosong diperbolehkan</li>
                <li>NIK yang duplikat dapat menyebabkan kegagalan import</li>
                <li>Tinjau preview dengan seksama untuk memastikan parsing yang benar</li>
                <li>Pastikan data bagian (department) sudah tersedia di database sebelum import</li>
              </ul>
              
              <h4>Kolom yang Diperlukan:</h4>
              <ul>
                <li><strong>NIK</strong> (Identifikasi unik karyawan - Wajib)</li>
                <li><strong>NAMA</strong> (Nama karyawan - Wajib)</li>
                <li>Kolom lainnya opsional namun direkomendasikan untuk kelengkapan data</li>
              </ul>
              
              <h4>Format Data yang Direkomendasikan:</h4>
              <ul>
                <li><strong>Tanggal:</strong> Format DD/MM/YYYY atau YYYY-MM-DD</li>
                <li><strong>Jenis Kelamin:</strong> L/P atau LAKI-LAKI/PEREMPUAN</li>
                <li><strong>Status:</strong> AKTIF/TIDAK AKTIF atau sesuai dengan data master</li>
                <li><strong>Bagian/Department:</strong> Sesuai dengan nama bagian yang sudah terdaftar di database</li>
              </ul>
              
              <div class="alert alert-warning">
                <strong>Perhatian:</strong> Pastikan file Excel tidak mengandung formula, hanya data mentah. Format sel harus sebagai teks untuk data yang berisi angka dengan awalan nol (seperti NIK).
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
                  <li>Cek duplikasi NIK sebelum import</li>
                </ul>
              </div>
              
              <div class="well">
                <h4>Daftar Bagian yang Tersedia:</h4>
                <p>Pastikan data bagian sesuai dengan salah satu dari:</p>
                <ul style="max-height: 200px; overflow-y: auto;">
                  <li>LINE 1</li>
                  <li>PRESS</li>
                  <li>SAMPLE</li>
                  <li>LINE 5</li>
                  <li>LINE 3</li>
                  <li>BERUTO</li>
                  <li>UMUM</li>
                  <li>PACKING</li>
                  <li>LINE 2</li>
                  <li>CUTTING</li>
                  <li>LINE 6</li>
                  <li>PERSIAPAN</li>
                  <li>TANOKOU</li>
                  <li>LINE 4</li>
                  <li>POCKET</li>
                  <li>RUPU</li>
                  <li>ADM PROD</li>
                  <li>SUSTER</li>
                  <li>R&D</li>
                  <li>RECEPTIONIST</li>
                  <li>QA</li>
                  <li>MANAGER</li>
                  <li>TEKFIT</li>
                  <li>TECHNICAL LEADER</li>
                  <!-- Tambahkan bagian lainnya sesuai kebutuhan -->
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
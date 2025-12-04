<?php $role = $this->session->userdata('role_id'); ?>
<style>
    .absensi-table {
        font-size: 12px;
    }
    
    .absensi-table th {
        background: #4472C4;
        color: white;
        text-align: center;
        vertical-align: middle;
        padding: 8px 4px;
        font-weight: 600;
    }
    
    .absensi-table td {
        text-align: center;
        vertical-align: middle;
        padding: 6px 4px;
        border: 1px solid #ddd;
    }
    
    .employee-info {
        text-align: left !important;
        padding-left: 8px !important;
    }
    
    .date-cell {
        width: 35px;
        min-width: 35px;
        max-width: 35px;
        position: relative;
    }
    
    .hadir {
        background-color: #d4edda !important;
        color: #155724;
        font-weight: bold;
    }
    
    .tidak-hadir {
        background-color: #f8d7da !important;
        color: #721c24;
    }
    
    .weekend {
        background-color: #e9ecef !important;
    }
    
    .filter-form {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
    }
    
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }
    
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .sticky-column {
        position: sticky;
        left: 0;
        background: white;
        z-index: 5;
    }
    
    .sticky-column.sticky-header {
        z-index: 15;
    }
</style>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Tabel Absensi Bulanan</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if ($this->session->flashdata('sukses')) { ?>
                    <div class="alert alert-success col-12">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Success!</strong> <?php echo $this->session->flashdata('sukses'); ?>
                    </div>
                <?php } elseif ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger col-12">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <!-- Filter Form -->
                        <form method="get" action="<?= site_url('absensi') ?>" class="filter-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bulan" class="control-label">Bulan:</label>
                                        <select class="form-control" name="bulan" id="bulan">
                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?= $i ?>" <?= ($i == $bulan) ? 'selected' : '' ?>>
                                                    <?= $namaBulan[$i] ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tahun" class="control-label">Tahun:</label>
                                        <select class="form-control" name="tahun" id="tahun">
                                            <?php for($y = 2020; $y <= 2030; $y++): ?>
                                                <option value="<?= $y ?>" <?= ($y == $tahun) ? 'selected' : '' ?>>
                                                    <?= $y ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" style="visibility: hidden;">Action:</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fa fa-search"></i> Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" style="visibility: hidden;">Export:</label>
                                        <div>
                                            <a class="btn btn-success btn-block" href="<?= site_url('absensi/export?bulan='.$bulan.'&tahun='.$tahun) ?>">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <?php if (empty($employees)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Tidak ada data karyawan ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <strong>Info:</strong> 
                                Menampilkan data absensi untuk <strong><?= $namaBulan[$bulan] ?> <?= $tahun ?></strong> 
                                (<?= $jumlahHari ?> hari). 
                                <span class="hadir" style="padding: 2px 6px; margin: 0 5px;">✓</span> = Hadir, 
                                <span class="tidak-hadir" style="padding: 2px 6px; margin: 0 5px;">-</span> = Tidak Hadir,
                                <span class="weekend" style="padding: 2px 6px; margin: 0 5px;">Weekend</span>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered absensi-table">
                                    <thead>
                                        <tr>
                                            <th class="column-title sticky-header sticky-column" rowspan="2">#</th>
                                            <th class="column-title sticky-header sticky-column" rowspan="2">NIK</th>
                                            <th class="column-title sticky-header sticky-column" rowspan="2">NAMA</th>
                                            <th class="column-title sticky-header sticky-column" rowspan="2">BAGIAN</th>
                                            <th class="column-title sticky-header" colspan="<?= $jumlahHari ?>">TANGGAL</th>
                                        </tr>
                                        <tr>
                                            <?php for($d = 1; $d <= $jumlahHari; $d++): 
                                                $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                                                $dayOfWeek = date('w', strtotime($tanggal)); // 0=Sunday, 6=Saturday
                                                $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                                            ?>
                                                <th class="column-title date-cell sticky-header <?= $isWeekend ? 'weekend' : '' ?>">
                                                    <?= $d ?>
                                                </th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach($employees as $emp): ?>
                                        <tr>
                                            <td class="sticky-column"><?= $no++ ?></td>
                                            <td class="employee-info sticky-column"><?= htmlspecialchars($emp->nik ?? '') ?></td>
                                            <td class="employee-info sticky-column"><?= htmlspecialchars($emp->name ?? '') ?></td>
                                            <td class="employee-info sticky-column"><?= htmlspecialchars($emp->bagian ?? '-') ?></td>
                                            
                                            <?php for($d = 1; $d <= $jumlahHari; $d++): 
                                                $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                                                $dayOfWeek = date('w', strtotime($tanggal));
                                                $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                                                
                                                $hadir = isset($emp->attendance[$d]) && $emp->attendance[$d]['hadir'];
                                                
                                                if ($isWeekend) {
                                                    $cellClass = 'weekend';
                                                    $cellContent = '';
                                                } elseif ($hadir) {
                                                    $cellClass = 'hadir';
                                                    $cellContent = '✓';
                                                } else {
                                                    $cellClass = 'tidak-hadir';
                                                    $cellContent = '-';
                                                }
                                            ?>
                                                <td class="date-cell <?= $cellClass ?>" title="<?= $tanggal ?>">
                                                    <?= $cellContent ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when month/year changes
    const bulanSelect = document.getElementById('bulan');
    const tahunSelect = document.getElementById('tahun');
    
    if (bulanSelect && tahunSelect) {
        bulanSelect.addEventListener('change', function() {
            this.form.submit();
        });
        
        tahunSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Add tooltip for date cells
    const dateCells = document.querySelectorAll('.date-cell[title]');
    dateCells.forEach(cell => {
        cell.addEventListener('mouseenter', function() {
            // You can add custom tooltip logic here if needed
        });
    });
});
</script>
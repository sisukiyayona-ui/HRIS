<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?php echo $title; ?></h3>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo $title; ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <style>
                            .report-container {
                                max-width: 100%;
                                overflow-x: auto;
                            }
                            .report-header {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .filter-section {
                                margin-bottom: 20px;
                                padding: 15px;
                                background-color: #f8f9fa;
                                border-radius: 5px;
                            }
                            .table-container {
                                overflow-x: auto;
                            }
                            .attendance-table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 20px;
                                font-size: 12px;
                            }
                            .attendance-table th,
                            .attendance-table td {
                                border: 1px solid #000;
                                padding: 5px;
                                text-align: center;
                                vertical-align: middle;
                            }
                            .attendance-table th {
                                background-color: #e9ecef;
                                font-weight: bold;
                            }
                            .dept-name {
                                text-align: left;
                            }
                            .action-buttons {
                                margin-bottom: 15px;
                            }
                            .loading {
                                text-align: center;
                                padding: 20px;
                                display: none;
                            }
                            .dept-total {
                                background-color: #f1f1f1;
                                font-weight: bold;
                            }
                            .grand-total {
                                background-color: #e0e0e0;
                                font-weight: bold;
                            }
                            .letter-header {
                                background-color: #D3D3D3;
                                font-weight: bold;
                            }
                        </style>
                        
                        <div class="report-header">
                            <h2><?php echo $title; ?></h2>
                            <p id="report_date_info" style="font-size: 14px; margin-top: 5px;"></p>
                        </div>

                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="report_date">Tanggal:</label>
                                    <input type="date" id="report_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button id="load_report" class="btn btn-primary form-control">Load Data</button>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button id="export_excel" class="btn btn-success form-control">Export ke Excel</button>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons text-right">
                            <button id="print_report" class="btn btn-info">Cetak Laporan</button>
                        </div>

                        <div class="loading" id="loading">
                            <p>Memuat data laporan...</p>
                        </div>

                        <div class="report-container">
                            <div class="table-container">
                                <table class="attendance-table" id="attendance_report_table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2" colspan="3">DEPARTEMEN</th>
                                            <th rowspan="2">Budget</th>
                                            <th rowspan="2">Total</th>
                                            <th colspan="3">TETAP</th>
                                            <th colspan="3">KONTRAK</th>
                                            <th rowspan="2">Hadir</th>
                                            <th rowspan="2">Line Hadir</th>
                                            <th rowspan="2">Tidak Hadir</th>
                                            <th colspan="3">Keterangan</th>
                                            <th rowspan="2">%Bag</th>
                                            <th rowspan="2">%Jum Kar</th>
                                            <th colspan="5">CUTI</th>
                                            <th colspan="2">Karyawan</th>
                                            <th colspan="2">Outsource Skill</th>
                                            <th colspan="2">Outsource Non Skill</th>
                                            <th rowspan="2">Krywn Off</th>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <th>P</th>
                                            <th>JML</th>
                                            <th>L</th>
                                            <th>P</th>
                                            <th>JML</th>
                                            <th>S</th>
                                            <th>M</th>
                                            <th>I</th>
                                            <th>CM</th>
                                            <th>CK</th>
                                            <th>CN</th>
                                            <th>CT</th>
                                            <th>CS</th>
                                            <th>Baru</th>
                                            <th>Keluar</th>
                                            <th>Baru</th>
                                            <th>Keluar</th>
                                            <th>Baru</th>
                                            <th>Keluar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report_data_body">
                                        <tr>
                                            <td colspan="33" style="text-align: center; padding: 20px;">Pilih tanggal dan klik "Load Data" untuk menampilkan laporan</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Load report when button is clicked
        $('#load_report').click(function() {
            loadReportData();
        });
        
        // Export to Excel
        $('#export_excel').click(function() {
            var reportDate = $('#report_date').val();
            if (!reportDate) {
                alert('Silakan pilih tanggal terlebih dahulu');
                return;
            }
            window.location.href = '<?php echo base_url(); ?>index.php/Reports/export_daily_attendance?date=' + reportDate;
        });
        
        // Print report
        $('#print_report').click(function() {
            window.print();
        });
        
        // Load report data function
        function loadReportData() {
            var reportDate = $('#report_date').val();
            if (!reportDate) {
                alert('Silakan pilih tanggal terlebih dahulu');
                return;
            }
            
            $('#loading').show();
            $('#report_data_body').html('<tr><td colspan="33" style="text-align: center; padding: 20px;">Memuat data...</td></tr>');
            
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/Reports/get_daily_attendance_data',
                type: 'POST',
                data: {
                    date: reportDate
                },
                dataType: 'json',
                success: function(response) {
                    $('#loading').hide();
                    
                    if (response.status === 'success') {
                        renderReportData(response.data, response.totals, response.date);
                    } else {
                        $('#report_data_body').html('<tr><td colspan="33" style="text-align: center; padding: 20px; color: red;">Gagal memuat data: ' + response.message + '</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    console.error('Error:', error);
                    $('#report_data_body').html('<tr><td colspan="33" style="text-align: center; padding: 20px; color: red;">Terjadi kesalahan saat memuat data</td></tr>');
                }
            });
        }
        
        // Function to render report data
        function renderReportData(data, totals, reportDate) {
            var tbody = $('#report_data_body');
            tbody.empty();
            
            if (Object.keys(data).length === 0) {
                tbody.html('<tr><td colspan="33" style="text-align: center; padding: 20px;">Tidak ada data untuk tanggal ini</td></tr>');
                return;
            }
            
            // Format and display date
            var date = new Date(reportDate);
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var dayName = days[date.getDay()];
            var day = date.getDate();
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            var monthName = months[date.getMonth()];
            var year = date.getFullYear();
            var formattedDate = dayName + ' ' + day + '-' + monthName + '-' + year;
            
            $('#report_date_info').text('Tanggal: ' + formattedDate);
            
            // Hitung total semua karyawan untuk rumus %Bag dan %Jum Kar
            var totalAllKaryawan = 0;
            var sortedLetters = Object.keys(data).sort();
            
            // Pertama, hitung total semua karyawan
            $.each(sortedLetters, function(letterIndex, letter) {
                var departments = data[letter];
                var deptKeys = Object.keys(departments);
                
                $.each(deptKeys, function(deptIndex, deptName) {
                    var deptData = departments[deptName];
                    totalAllKaryawan += parseInt(deptData.total || 0);
                });
            });
            
            // Sekarang render data dengan perhitungan persentase
            $.each(sortedLetters, function(letterIndex, letter) {
                var departments = data[letter];
                var deptKeys = Object.keys(departments);
                
                if (deptKeys.length > 0) {
                    // Add letter header row - hanya untuk grup huruf
                    var letterRow = '<tr class="letter-header">';
                    letterRow += '<td>' + letter + '</td>';
                    letterRow += '<td colspan="32"></td>';
                    letterRow += '</tr>';
                    tbody.append(letterRow);
                    
                    // Add department rows under this letter
                    $.each(deptKeys, function(deptIndex, deptName) {
                        var deptData = departments[deptName];
                        
                        // Hitung persentase untuk departemen ini
                        var keteranganS = parseInt(deptData.keterangan.s || 0);
                        var keteranganM = parseInt(deptData.keterangan.m || 0);
                        var keteranganI = parseInt(deptData.keterangan.i || 0);
                        var jumKar = parseInt(deptData.jum_kar || 0);
                        var totalDept = parseInt(deptData.total || 0);
                        
                        // Rumus %Bag: ((S + M + I) / Total Semua Karyawan) * 100%
                        var persenBag = 0;
                        if (totalAllKaryawan > 0) {
                            persenBag = ((keteranganS + keteranganM + keteranganI) / totalAllKaryawan) * 100;
                        }
                        
                        // Rumus %Jum Kar: ((S + M + I) / Total Departemen) * 100%
                        var persenJumKar = 0;
                        if (totalDept > 0) {
                            persenJumKar = ((keteranganS + keteranganM + keteranganI) / totalDept) * 100;
                        }
                        
                        var row = '<tr>';
                        row += '<td></td>'; // Kolom No. (huruf) dikosongkan untuk baris departemen
                        row += '<td>' + (deptIndex + 1) + '</td>'; // Kolom No (angka urutan)
                        row += '<td class="dept-name">' + deptName + '</td>';
                        row += '<td>' + (deptData.dl_idl || '') + '</td>';
                        row += '<td>' + (deptData.budget || 0) + '</td>';
                        row += '<td>' + totalDept + '</td>';
                        row += '<td>' + (deptData.tetap.l || 0) + '</td>';
                        row += '<td>' + (deptData.tetap.p || 0) + '</td>';
                        row += '<td>' + (deptData.tetap.jml || 0) + '</td>';
                        row += '<td>' + (deptData.kontrak.l || 0) + '</td>';
                        row += '<td>' + (deptData.kontrak.p || 0) + '</td>';
                        row += '<td>' + (deptData.kontrak.jml || 0) + '</td>';
                        row += '<td>' + (deptData.hadir || 0) + '</td>';
                        row += '<td>' + (deptData.hadir || 0) + '</td>'; // Line Hadir = Hadir
                        row += '<td>' + (deptData.tidak_hadir || 0) + '</td>';
                        row += '<td>' + keteranganS + '</td>';
                        row += '<td>' + keteranganM + '</td>';
                        row += '<td>' + keteranganI + '</td>';
                        row += '<td>' + persenBag.toFixed(2) + '%</td>'; // %Bag
                        row += '<td>' + persenJumKar.toFixed(2) + '%</td>'; // %Jum Kar
                        row += '<td>' + (deptData.cuti.cm || 0) + '</td>';
                        row += '<td>' + (deptData.cuti.ck || 0) + '</td>';
                        row += '<td>' + (deptData.cuti.cn || 0) + '</td>';
                        row += '<td>' + (deptData.cuti.ct || 0) + '</td>';
                        row += '<td>' + (deptData.cuti.cs || 0) + '</td>';
                        row += '<td>' + (deptData.karyawan.baru || 0) + '</td>';
                        row += '<td>' + (deptData.karyawan.keluar || 0) + '</td>';
                        row += '<td>' + (deptData.outsource_skill.baru || 0) + '</td>';
                        row += '<td>' + (deptData.outsource_skill.keluar || 0) + '</td>';
                        row += '<td>' + (deptData.outsource_nonskill.baru || 0) + '</td>';
                        row += '<td>' + (deptData.outsource_nonskill.keluar || 0) + '</td>';
                        row += '<td>' + (deptData.karyawan_off || 0) + '</td>';
                        row += '</tr>';
                        
                        tbody.append(row);
                    });
                }
            });
            
            // Add total row dengan perhitungan persentase total
            if (totals) {
                var totalKeteranganS = parseInt(totals.keterangan.s || 0);
                var totalKeteranganM = parseInt(totals.keterangan.m || 0);
                var totalKeteranganI = parseInt(totals.keterangan.i || 0);
                var totalJumKar = parseInt(totals.jum_kar || 0);
                var totalBudget = parseInt(totals.total || 0);
                
                // Rumus %Bag Total: ((Total S + M + I) / Total Semua Karyawan) * 100%
                var totalPersenBag = 0;
                if (totalAllKaryawan > 0) {
                    totalPersenBag = ((totalKeteranganS + totalKeteranganM + totalKeteranganI) / totalAllKaryawan) * 100;
                }
                
                // Rumus %Jum Kar Total: ((Total S + M + I) / Total Budget) * 100%
                var totalPersenJumKar = 0;
                if (totalBudget > 0) {
                    totalPersenJumKar = ((totalKeteranganS + totalKeteranganM + totalKeteranganI) / totalBudget) * 100;
                }
                
                var totalRow = '<tr class="grand-total">';
                totalRow += '<td></td>'; // Kolom No. (huruf)
                totalRow += '<td colspan="2" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>';
                totalRow += '<td></td>'; // DL/IDL
                totalRow += '<td>' + (totals.budget || 0) + '</td>';
                totalRow += '<td>' + totalBudget + '</td>';
                totalRow += '<td>' + (totals.tetap.l || 0) + '</td>';
                totalRow += '<td>' + (totals.tetap.p || 0) + '</td>';
                totalRow += '<td>' + (totals.tetap.jml || 0) + '</td>';
                totalRow += '<td>' + (totals.kontrak.l || 0) + '</td>';
                totalRow += '<td>' + (totals.kontrak.p || 0) + '</td>';
                totalRow += '<td>' + (totals.kontrak.jml || 0) + '</td>';
                totalRow += '<td>' + (totals.hadir || 0) + '</td>';
                totalRow += '<td>' + (totals.hadir || 0) + '</td>'; // Line Hadir = Hadir
                totalRow += '<td>' + (totals.tidak_hadir || 0) + '</td>';
                totalRow += '<td>' + totalKeteranganS + '</td>';
                totalRow += '<td>' + totalKeteranganM + '</td>';
                totalRow += '<td>' + totalKeteranganI + '</td>';
                totalRow += '<td>' + totalPersenBag.toFixed(2) + '%</td>'; // %Bag Total
                totalRow += '<td>' + totalPersenJumKar.toFixed(2) + '%</td>'; // %Jum Kar Total
                totalRow += '<td>' + (totals.cuti.cm || 0) + '</td>';
                totalRow += '<td>' + (totals.cuti.ck || 0) + '</td>';
                totalRow += '<td>' + (totals.cuti.cn || 0) + '</td>';
                totalRow += '<td>' + (totals.cuti.ct || 0) + '</td>';
                totalRow += '<td>' + (totals.cuti.cs || 0) + '</td>';
                totalRow += '<td>' + (totals.karyawan.baru || 0) + '</td>';
                totalRow += '<td>' + (totals.karyawan.keluar || 0) + '</td>';
                totalRow += '<td>' + (totals.outsource_skill.baru || 0) + '</td>';
                totalRow += '<td>' + (totals.outsource_skill.keluar || 0) + '</td>';
                totalRow += '<td>' + (totals.outsource_nonskill.baru || 0) + '</td>';
                totalRow += '<td>' + (totals.outsource_nonskill.keluar || 0) + '</td>';
                totalRow += '<td>' + (totals.karyawan_off || 0) + '</td>';
                totalRow += '</tr>';
                
                tbody.append(totalRow);
            }
        }
    });
</script>

<!-- Additional required scripts for Gentelella template -->
<script src="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/nprogress/nprogress.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/Chart.js/dist/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/autosize/dist/autosize.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/starrr/dist/starrr.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo base_url(); ?>assets/build/js/custom.min.js"></script>
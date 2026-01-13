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
                            .sub-header {
                                background-color: #f0f0f0;
                                font-weight: bold;
                            }
                            .chart-container {
                                margin-top: 30px;
                                padding: 20px;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                background-color: #fff;
                                min-height: 350px;
                                display: block !important;
                            }
                            
                            .chart-container canvas {
                                display: block !important;
                                width: 100% !important;
                                height: 300px !important;
                            }
                            .chart-title {
                                text-align: center;
                                margin-bottom: 20px;
                                font-weight: bold;
                                font-size: 16px;
                            }
                            
                            /* Layout untuk laporan di kiri dan grafik di kanan */
                            .content-wrapper {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 20px;
                            }
                            .report-section {
                                flex: 1;
                                min-width: 300px;
                            }
                            .chart-section {
                                flex: 1;
                                min-width: 300px;
                            }
                            
                            @media print {
                                .filter-section, .action-buttons, .no-print {
                                    display: none !important;
                                }
                                
                                .chart-container {
                                    page-break-inside: avoid;
                                    break-inside: avoid;
                                }
                                
                                .report-container {
                                    overflow: visible !important;
                                }
                                
                                .attendance-table {
                                    font-size: 10px !important;
                                }
                                
                                body {
                                    font-size: 12px;
                                    line-height: 1.2;
                                }
                            }
                        </style>
                        
                        <div class="report-header">
                            <h2 id="report_title"><?php echo $title; ?></h2>
                            <p id="report_date_info" style="font-size: 14px; margin-top: 5px;"></p>
                        </div>

                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="report_month">Bulan:</label>
                                    <input type="month" id="report_month" class="form-control" value="<?php echo date('Y-m'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button id="load_report" class="btn btn-primary form-control">Load Data</button>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button id="export_excel" class="btn btn-success form-control">Export ke Excel</button>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button id="print_report" class="btn btn-info form-control">Cetak Laporan</button>
                                </div>
                            </div>
                        </div>

                        <div class="loading" id="loading">
                            <p>Memuat data laporan...</p>
                        </div>

                        <!-- Layout utama: Laporan di kiri, Grafik di kanan -->
                        <div class="content-wrapper">
                            <!-- Bagian Laporan (Kiri) -->
                            <div class="report-section">
                                <div class="report-container">
                                    <div class="table-container">
                                        <table class="attendance-table" id="attendance_report_table">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" id="table_title">REKAP PERSENTASE ABSENSI HARIAN BULAN DESEMBER 2025</th>
                                                </tr>
                                                <tr id="table_month_row">
                                                    <th>Keterangan</th>
                                                    <th>Org</th>
                                                    <th>%</th>
                                                </tr>
                                            </thead>
                                            <tbody id="report_data_body">
                                                <!-- Data akan diisi oleh JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bagian Grafik (Kanan) -->
                            <div class="chart-section">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="chart-container">
                                            <div class="chart-title">Grafik Presentase Absensi</div>
                                            <div style="height: 300px;">
                                                <canvas id="attendanceChart" width="400" height="300"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Initialize charts
        let attendanceChart = null;
        
        // Prevent conflicts with global chart initialization
        if (typeof Chart !== 'undefined') {
            // Ensure Chart.js is properly configured
            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;
        }
        
        // Disable global chart initialization that conflicts with our report charts
        if (typeof init_charts === 'function') {
            // Override the global init_charts function temporarily
            var original_init_charts = init_charts;
            window.init_charts = function() {
                // Do nothing on this page to prevent conflicts
                return false;
            };
        }
        
        // Load report when button is clicked
        $('#load_report').click(function() {
            loadReportData();
        });
        
        // Load report when month changes
        $('#report_month').change(function() {
            loadReportData();
        });
        
        // Export to Excel
        $('#export_excel').click(function() {
            var reportMonth = $('#report_month').val();
            if (!reportMonth) {
                alert('Silakan pilih bulan terlebih dahulu');
                return;
            }
            window.location.href = '<?php echo base_url(); ?>index.php/Reports/export_monthly_percentage_attendance?month=' + reportMonth;
        });
        
        // Print report
        $('#print_report').click(function() {
            window.print();
        });
        
        // Function to initialize charts
        function initializeCharts(data) {
            // Check if canvas elements exist before initializing
            const attendanceCanvas = document.getElementById('attendanceChart');
            
            if (!attendanceCanvas) {
                console.error('Attendance canvas element not found');
                return;
            }
            
            // Destroy existing chart if it exists
            if (attendanceChart) {
                attendanceChart.destroy();
            }
            
            // Data for Attendance Chart (Line Chart)
            const attendanceData = {
                labels: ['Hadir', 'Sakit', 'Ijin', 'Mangkir', 'Cuti'],
                datasets: [
                    {
                        label: 'Jumlah',
                        data: [
                            (data.hadir && typeof data.hadir.total !== 'undefined') ? data.hadir.total : 0,
                            (data.absensi && typeof data.absensi.sakit !== 'undefined') ? data.absensi.sakit : 0,
                            (data.absensi && typeof data.absensi.izin !== 'undefined') ? data.absensi.izin : 0,
                            (data.absensi && typeof data.absensi.mangkir !== 'undefined') ? data.absensi.mangkir : 0,
                            (data.subtotal_cuti && typeof data.subtotal_cuti.total !== 'undefined') ? data.subtotal_cuti.total : 0
                        ],
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }
                ]
            };
            
            // Data for Employee Chart (Line Chart)
            const employeeData = {
                labels: ['Total', 'Sewing', 'Non Sewing'],
                datasets: [
                    {
                        label: 'Karyawan',
                        data: [
                            (data.karyawan && typeof data.karyawan.total !== 'undefined') ? data.karyawan.total : 0,
                            (data.karyawan && typeof data.karyawan.sewing !== 'undefined') ? data.karyawan.sewing : 0,
                            (data.karyawan && typeof data.karyawan.non_sewing !== 'undefined') ? data.karyawan.non_sewing : 0
                        ],
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Resign',
                        data: [
                            (data.resign && typeof data.resign.total !== 'undefined') ? data.resign.total : 0,
                            (data.resign && typeof data.resign.sewing !== 'undefined') ? data.resign.sewing : 0,
                            (data.resign && typeof data.resign.non_sewing !== 'undefined') ? data.resign.non_sewing : 0
                        ],
                        borderColor: '#4BC0C0',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }
                ]
            };
            
            try {
                // Attendance Chart (Line)
                const attendanceCtx = attendanceCanvas.getContext('2d');
                attendanceChart = new Chart(attendanceCtx, {
                    type: 'line',
                    data: attendanceData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Kategori'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Error creating attendance chart:', e);
            }
            
            // Prevent any global chart initialization from interfering
            $(document).off('chart:init');
            
            // Restore original init_charts function if it was overridden
            if (typeof original_init_charts === 'function') {
                window.init_charts = original_init_charts;
            }
        }
        
        // Function to create table rows from template
        function createTableRows(data) {
            let rows = '';
            
            // Absensi section
            rows += `
                <tr>
                    <td class="text-left">Sakit</td>
                    <td>${data.absensi ? data.absensi.sakit : 0}</td>
                    <td>${(data.absensi && data.absensi.sakit_pct) ? data.absensi.sakit_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Ijin</td>
                    <td>${data.absensi ? data.absensi.izin : 0}</td>
                    <td>${(data.absensi && data.absensi.izin_pct) ? data.absensi.izin_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Mangkir</td>
                    <td>${data.absensi ? data.absensi.mangkir : 0}</td>
                    <td>${(data.absensi && data.absensi.mangkir_pct) ? data.absensi.mangkir_pct : '0,00%'}</td>
                </tr>
                <tr class="sub-header">
                    <td class="text-left">Subtotal</td>
                    <td>${data.subtotal_absensi ? data.subtotal_absensi.total : 0}</td>
                    <td>${(data.subtotal_absensi && data.subtotal_absensi.pct) ? data.subtotal_absensi.pct : '0,00%'}</td>
                </tr>
            `;
            
            // Cuti section
            rows += `
                <tr>
                    <td class="text-left">Cuti Khusus</td>
                    <td>${data.cuti ? data.cuti.khusus : 0}</td>
                    <td>${(data.cuti && data.cuti.khusus_pct) ? data.cuti.khusus_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Cuti Tahunan</td>
                    <td>${data.cuti ? data.cuti.tahunan : 0}</td>
                    <td>${(data.cuti && data.cuti.tahunan_pct) ? data.cuti.tahunan_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Cuti Nikah</td>
                    <td>${data.cuti ? data.cuti.nikah : 0}</td>
                    <td>${(data.cuti && data.cuti.nikah_pct) ? data.cuti.nikah_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Cuti Sakit</td>
                    <td>${data.cuti ? data.cuti.sakit : 0}</td>
                    <td>${(data.cuti && data.cuti.sakit_pct) ? data.cuti.sakit_pct : '0,00%'}</td>
                </tr>
                <tr>
                    <td class="text-left">Cuti Melahirkan</td>
                    <td>${data.cuti ? data.cuti.melahirkan : 0}</td>
                    <td>${(data.cuti && data.cuti.melahirkan_pct) ? data.cuti.melahirkan_pct : '0,00%'}</td>
                </tr>
                <tr class="sub-header">
                    <td class="text-left">Subtotal</td>
                    <td>${data.subtotal_cuti ? data.subtotal_cuti.total : 0}</td>
                    <td>${(data.subtotal_cuti && data.subtotal_cuti.pct) ? data.subtotal_cuti.pct : '0,00%'}</td>
                </tr>
            `;
            
            // Total absensi
            rows += `
                <tr class="grand-total">
                    <td class="text-left">TOTAL ABSENSI</td>
                    <td>${data.total_absensi ? data.total_absensi.total : 0}</td>
                    <td>${(data.total_absensi && data.total_absensi.pct) ? data.total_absensi.pct : '0,00%'}</td>
                </tr>
            `;
            
            // Hadir section
            rows += `
                <tr>
                    <td class="text-left">TOTAL HADIR</td>
                    <td>${data.hadir ? data.hadir.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Sewing Hadir</td>
                    <td>${data.hadir ? data.hadir.sewing : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Non Sew Hadir</td>
                    <td>${data.hadir ? data.hadir.non_sewing : 0}</td>
                    <td></td>
                </tr>
            `;
            
            // Total karyawan
            rows += `
                <tr>
                    <td class="text-left">TOTAL KARYAWAN</td>
                    <td>${data.karyawan ? data.karyawan.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Sewing</td>
                    <td>${data.karyawan ? data.karyawan.sewing : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Non Sewing</td>
                    <td>${data.karyawan ? data.karyawan.non_sewing : 0}</td>
                    <td></td>
                </tr>
            `;
            
            // Resign section
            rows += `
                <tr>
                    <td class="text-left">KARYAWAN RESIGN</td>
                    <td>${data.resign ? data.resign.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Sewing</td>
                    <td>${data.resign ? data.resign.sewing : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Non Sewing</td>
                    <td>${data.resign ? data.resign.non_sewing : 0}</td>
                    <td></td>
                </tr>
            `;
            
            // Baru section
            rows += `
                <tr>
                    <td class="text-left">KARYAWAN BARU</td>
                    <td>${data.baru ? data.baru.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Sewing</td>
                    <td>${data.baru ? data.baru.sewing : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Non Sewing</td>
                    <td>${data.baru ? data.baru.non_sewing : 0}</td>
                    <td></td>
                </tr>
            `;
            
            // OS Skill section
            rows += `
                <tr>
                    <td class="text-left">OS SKILL</td>
                    <td>${data.os_skill ? data.os_skill.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Resign</td>
                    <td>${data.os_skill ? data.os_skill.resign : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Baru</td>
                    <td>${data.os_skill ? data.os_skill.baru : 0}</td>
                    <td></td>
                </tr>
            `;
            
            // OS Non Skill section
            rows += `
                <tr>
                    <td class="text-left">OS NON SKILL</td>
                    <td>${data.os_non_skill ? data.os_non_skill.total : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Resign</td>
                    <td>${data.os_non_skill ? data.os_non_skill.resign : 0}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Baru</td>
                    <td>${data.os_non_skill ? data.os_non_skill.baru : 0}</td>
                    <td></td>
                </tr>
            `;
            
            return rows;
        }
        
        // Load report data function
        function loadReportData() {
            var reportMonth = $('#report_month').val();
            if (!reportMonth) {
                alert('Silakan pilih bulan terlebih dahulu');
                return;
            }
            
            $('#loading').show();
            
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/Reports/get_monthly_percentage_attendance_data',
                type: 'POST',
                data: {
                    month: reportMonth
                },
                dataType: 'json',
                success: function(response) {
                    $('#loading').hide();
                    
                    if (response.status === 'success') {
                        renderReportData(response.data, response.month);
                    } else {
                        alert('Gagal memuat data: ' + response.message);
                        // Tampilkan data kosong
                        renderReportData(getEmptyData(), reportMonth);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                    // Tampilkan data kosong
                    renderReportData(getEmptyData(), reportMonth);
                }
            });
        }
        
        // Function to get empty data structure
        function getEmptyData() {
            return {
                absensi: {
                    sakit: 0,
                    sakit_pct: '0,00%',
                    izin: 0,
                    izin_pct: '0,00%',
                    mangkir: 0,
                    mangkir_pct: '0,00%'
                },
                subtotal_absensi: {
                    total: 0,
                    pct: '0,00%'
                },
                cuti: {
                    khusus: 0,
                    khusus_pct: '0,00%',
                    tahunan: 0,
                    tahunan_pct: '0,00%',
                    nikah: 0,
                    nikah_pct: '0,00%',
                    sakit: 0,
                    sakit_pct: '0,00%',
                    melahirkan: 0,
                    melahirkan_pct: '0,00%'
                },
                subtotal_cuti: {
                    total: 0,
                    pct: '0,00%'
                },
                total_absensi: {
                    total: 0,
                    pct: '0,00%'
                },
                hadir: {
                    total: 0,
                    sewing: 0,
                    non_sewing: 0
                },
                karyawan: {
                    total: 0,
                    sewing: 0,
                    non_sewing: 0
                },
                resign: {
                    total: 0,
                    sewing: 0,
                    non_sewing: 0
                },
                baru: {
                    total: 0,
                    sewing: 0,
                    non_sewing: 0
                },
                os_skill: {
                    total: 0,
                    resign: 0,
                    baru: 0
                },
                os_non_skill: {
                    total: 0,
                    resign: 0,
                    baru: 0
                }
            };
        }
        
        // Function to render report data
        function renderReportData(data, month) {
            // Format and display month
            var monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            var monthParts = month.split('-');
            var year = monthParts[0];
            var monthNum = parseInt(monthParts[1]) - 1;
            var monthName = monthNames[monthNum];
            var formattedDate = monthName + ' ' + year;
            
            // Update titles
            $('#report_title').text('REKAP PERSENTASE ABSENSI HARIAN - ' + formattedDate);
            $('#table_title').text('REKAP PERSENTASE ABSENSI HARIAN BULAN ' + formattedDate.toUpperCase());
            $('#table_month_row').html('<th>Keterangan</th><th>Org</th><th>%</th>');
            $('#report_date_info').text('Bulan: ' + formattedDate);
            
            // Clear and populate table body
            $('#report_data_body').html(createTableRows(data));
            
            // Initialize charts with data after a small delay to ensure DOM is ready
            // Temporarily disable global chart events to prevent conflicts
            $(document).off('chart:init');
            setTimeout(function() {
                initializeCharts(data);
            }, 100);
        }
        
        // Load report data on page load with current month
        loadReportData();
    });
</script>
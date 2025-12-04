<?php $role = $this->session->userdata('role_id'); ?>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Dashboard Kantin</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row tile_count" style="margin-left: 10px;">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Karyawan</span>
                <div class="count"><?php echo $totkar->num_rows() ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/list_karyawan">Details</a></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Tidak Hadir</span>
                <div class="count"><?php echo $total_tidak_hadir->num_rows() ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/list_absen">Details</a></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-clock-o"></i> Total Hadir</span>
                <div class="count"><?php echo $total_hadir ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/list_hadir">Details</a></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan</span>
                <div class="count green"><?php echo $makan_karyawan->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/makan/all">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan Industri</span>
                <div class="count green"><?php echo $makan_karyawan_ind->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/makan/industri">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan Baros</span>
                <div class="count green"><?php echo $makan_karyawan_brs->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/makan/baros">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Tidak Makan</span>
                <div class="count"><?php echo $tidak_makan_karyawan->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/tidak_makan/x">Details</a></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan Seluruhnya</span>
                <div class="count red"><?php echo ($makan_karyawan->num_rows() + $makan_tamu->num_rows() + $makan_koprasi->num_rows() + $makan_jemputan->num_rows()) ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/makan_seluruh">Details</a></span>
            </div>
        </div>


        <div class="row tile_count" style="margin-left: 10px;">
            <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Tamu & PKL</span>
                <div class="count"><?php echo $all_tamu->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/list_tamu">Details</a></span>
            </div> -->
            <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Koprasi & Outsource</span>
                <div class="count"><?php echo $all_koprasi->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/list_koprasi">Details</a></span>
            </div> -->
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan Tamu & PKL</span>
                <div class="count green"><?php echo $makan_tamu->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/tamu_makan">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>(I) Total Makan Tamu & PKL</span>
                <div class="count green"><?php echo $makan_tamu_ind->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/tamu_makan/industri">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>(B)Total Makan Tamu & PKL</span>
                <div class="count green"><?php echo $makan_tamu_brs->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/tamu_makan/baros">Details</a></span>
            </div>

            <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Tidak Makan Tamu & PKL</span>
                <div class="count"><?php echo  $tidak_makan_tamu->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/tamu_tidak_makan">Details</a></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Tidak Makan Koprasi & Outsource</span>
                <div class="count"><?php echo  $tidak_makan_koprasi->num_rows(); ?></div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/koprasi_tidak_makan">Details</a></span>
            </div> -->
        </div>

        <div class="row tile_count" style="margin-left: 10px;">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Makan Koprasi & Outsource</span>
                <div class=" count green"><?php echo $makan_koprasi->num_rows(); ?>
                </div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/koprasi_makan">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>(I) Makan Koprasi & Outsource</span>
                <div class=" count green"><?php echo $makan_koprasii->num_rows(); ?>
                </div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/koprasi_makan/industri">Details</a></span>
            </div>

            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>(B) Makan Koprasi & Outsource</span>
                <div class=" count green"><?php echo $makan_koprasib->num_rows(); ?>
                </div>
                <span class="count_bottom"><a target="_blank" href="<?php echo base_url() ?>Kantin/detail_makan/koprasi_makan/baros">Details</a></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Makan & Tidak Makan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <input type="hidden" id="makan" value="<?php echo $makan_karyawan->num_rows() + $makan_tamu->num_rows() + $makan_koprasi->num_rows() ?>">
                        <input type="hidden" id="tidak_makan" value="<?php echo $tidak_makan_karyawan->num_rows() + $tidak_makan_tamu->num_rows() + $tidak_makan_koprasi->num_rows() ?>">
                        <select class="form-control" id="opsi_makan" onchange="update_makan()">
                            <option value="Semua">All</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="Tamu">Tamu dan PKL</option>
                            <option value="Koprasi">Koprasi dan Outsource</option>
                        </select><br>
                        <div id="main1" style="height:290px;"></div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Tipe Makan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <input type="hidden" id="scan" value="<?php echo ($scan->num_rows()); ?>">
                        <input type="hidden" id="manual" value="<?php echo ($manual->num_rows()); ?>">
                        <input type="hidden" id="flat" value="<?php echo ($flat->num_rows()); ?>">
                        <select class="form-control" id="opsi_manual" onchange="update_manual()">
                            <option value="Semua">All</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="Tamu">Tamu dan PKL</option>
                            <option value="Koprasi">Koprasi dan Outsource</option>
                        </select><br>
                        <div id="main2" style="height:290px;"></div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Komparasi Absen dan Makan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <input type="hidden" id="absen_makan" value="<?php echo $absen_makan->num_rows() ?>">
                        <input type="hidden" id="makan_absen" value="<?php echo $makan_absen->num_rows() ?>">
                        <div id="main3" style="height:290px;"></div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Tabel Distribusi Kupon Makan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-stripped table-bordered">
                            <tr>
                                <th></th>
                                <th>Scan</th>
                                <th>Manual</th>
                                <th>Flat</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <td>Direksi</td>
                                <td><?php echo $scan_direksi = $direksi_scan->num_rows() ?></td>
                                <td><?php echo $manual_direksi = $direksi_manual->num_rows() ?></td>
                                <td><?php echo $flat_direksi = $direksi_flat->num_rows() ?></td>
                                <th><?php echo $all_direksi = $direksi_scan->num_rows() + $direksi_manual->num_rows() + $direksi_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>Baros</td>
                                <td><?php echo $scan_baros = $baros_scan->num_rows() ?></td>
                                <td><?php echo $manual_baros = $baros_manual->num_rows() ?></td>
                                <td><?php echo $flat_baros = $baros_flat->num_rows() ?></td>
                                <th><?php echo $all_baros = $baros_scan->num_rows() + $baros_manual->num_rows() + $baros_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>Industri</td>
                                <td><?php echo $scan_industri = $industri_scan->num_rows() ?></td>
                                <td><?php echo $manual_industri = $industri_manual->num_rows() ?></td>
                                <td><?php echo $flat_industri = $industri_flat->num_rows() ?></td>
                                <th><?php echo $all_industri = $industri_scan->num_rows() + $industri_manual->num_rows() + $industri_flat->num_rows() ?></td>
                            <tr>
                                <td>Koprasi / Outsource</td>
                                <td><?php echo $scan_kopout = $kopout_scan->num_rows() ?></td>
                                <td><?php echo $manual_kopout = $kopout_manual->num_rows() ?></td>
                                <td><?php echo $flat_kopout = $kopout_flat->num_rows() ?></td>
                                <th><?php echo $all_kopout = $kopout_scan->num_rows() + $kopout_manual->num_rows() + $kopout_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>Keamanan</td>
                                <td><?php echo $scan_keamanan = $keamanan_scan->num_rows() ?></td>
                                <td><?php echo $manual_keamanan = $keamanan_manual->num_rows() ?></td>
                                <td><?php echo $flat_keamanan = $keamanan_flat->num_rows() ?></td>
                                <th><?php echo $all_keamanan = $keamanan_scan->num_rows() + $keamanan_manual->num_rows() + $keamanan_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>Pengemudi</td>
                                <td><?php echo $scan_pengemudi = $pengemudi_scan->num_rows() ?></td>
                                <td><?php echo $manual_pengemudi = $pengemudi_manual->num_rows() ?></td>
                                <td><?php echo $flat_pengemudi = $pengemudi_flat->num_rows() ?></td>
                                <th><?php echo $all_pengemudi = $pengemudi_scan->num_rows() + $pengemudi_manual->num_rows() + $pengemudi_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>Jemputan</td>
                                <td><?php echo $scan_jemputan = $jemputan_scan->num_rows() ?></td>
                                <td><?php echo $manual_jemputan = $jemputan_manual->num_rows() ?></td>
                                <td><?php echo $flat_jemputan = $jemputan_flat->num_rows() ?></td>
                                <th><?php echo $all_jemputan = $jemputan_scan->num_rows() + $jemputan_manual->num_rows() + $jemputan_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <td>PKL & Tamu</td>
                                <td><?php echo $scan_pkltamu = $pkltamu_scan->num_rows() ?></td>
                                <td><?php echo $manual_pkltamu = $pkltamu_manual->num_rows() ?></td>
                                <td><?php echo $flat_pkltamu = $pkltamu_flat->num_rows() ?></td>
                                <th><?php echo $all_pkltamu = $pkltamu_scan->num_rows() + $pkltamu_manual->num_rows() + $pkltamu_flat->num_rows() ?></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th><?php echo $scan_direksi + $scan_baros + $scan_industri + $scan_kopout + $scan_keamanan + $scan_pengemudi + $scan_jemputan + $scan_pkltamu ?></th>
                                <th><?php echo $manual_direksi + $manual_baros + $manual_industri + $manual_kopout + $manual_keamanan + $manual_pengemudi + $manual_jemputan + $manual_pkltamu ?></th>
                                <th><?php echo $flat_direksi + $flat_baros + $flat_industri + $flat_kopout + $flat_keamanan + $flat_pengemudi + $flat_jemputan + $flat_pkltamu ?></th>
                                <th><?php echo $all_direksi + $all_baros + $all_industri + $all_kopout + $all_keamanan + $all_pengemudi + $all_jemputan + $all_pkltamu ?></th>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
</div>
<!-- /page content -->


<script type="text/javascript">
    $(document).ready(function() {
        var sekarang;
        var bulan_ini;
        var tahun_ini;
        // bulan_ini = "";
        // tahun_ini = "";
        hari_ini();
        update_makan();
        update_manual();
        chart_compare();
    });

    function hari_ini() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const namaBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        bulan = monthNames[today.getMonth()];
        bulan_ini = bulan + " " + yyyy;
        tahun_ini = yyyy;
        sekarang = dd + " " + bulan + " " + yyyy;
        // alert(periode);
    }

    function update_makan() {
        pilihan = document.getElementById('opsi_makan').value;
        link_makan = "<?php echo base_url() ?>Kantin/chart_makan";
        periode = sekarang;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: link_makan, // Isi dengan url/path file php yang dituju
            data: {
                pilihan: pilihan,
                periode: periode
            },
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(data, response) { // Ketika proses pengiriman berhasil
                document.getElementById('makan').value = data[0];
                document.getElementById('tidak_makan').value = data[1];
                chart_makan();
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }

    function chart_makan() {
        var myChart = echarts.init(document.getElementById('main1'));
        var option = {
            title: {
                text: 'Makan & Tidak Makan',
                subtext: sekarang,
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['Makan', 'Tidak Makan']
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {
                        show: true
                    },
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    // restore : {show: true},
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'makan',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                label: {
                    normal: {
                        formatter: '{b} : {c} ({d}%)',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [{
                        value: document.getElementById('makan').value,
                        name: 'Makan'
                    },
                    {
                        value: document.getElementById('tidak_makan').value,
                        name: 'Tidak Makan'
                    }
                ]
            }]
        };

        myChart.setOption(option);
        myChart.on('click', function(data) {
            opsi = document.getElementById('opsi_makan').value;
            if (data.name == 'Makan') {
                link = "<?php echo base_url() ?>Kantin/detail_chart_makan/Makan/" + opsi + "/" + periode;
                window.open(link);
            } else {
                link = "<?php echo base_url() ?>Kantin/detail_chart_makan/Belum/" + opsi + "/" + periode;
                window.open(link);
            }
        })
    }

    function update_manual() {
        pilihan = document.getElementById('opsi_manual').value;
        link_manual = "<?php echo base_url() ?>Kantin/chart_manual";
        periode = sekarang;
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: link_manual, // Isi dengan url/path file php yang dituju
            data: {
                pilihan: pilihan,
                periode: periode
            },
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(data, response) { // Ketika proses pengiriman berhasil
                document.getElementById('manual').value = data[0];
                document.getElementById('scan').value = data[1];
                document.getElementById('flat').value = data[2];
                chart_manual();
            },
            error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
            }
        });
    }

    function chart_manual() {
        var myChart = echarts.init(document.getElementById('main2'));
        var option = {
            title: {
                text: 'Scan - Manual - Flat',
                subtext: sekarang,
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['Scan', 'Manual', 'Flat']
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {
                        show: true
                    },
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    // restore : {show: true},
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'scan',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                label: {
                    normal: {
                        formatter: '{b} : {c} ({d}%)',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [{
                        value: document.getElementById('manual').value,
                        name: 'Manual'
                    },
                    {
                        value: document.getElementById('scan').value,
                        name: 'Scan'
                    },
                    {
                        value: document.getElementById('flat').value,
                        name: 'Flat'
                    }
                ]
            }]
        };

        myChart.setOption(option);
        myChart.on('click', function(data) {
            opsi = document.getElementById('opsi_manual').value;
            if (data.name == 'Manual') {
                link = "<?php echo base_url() ?>Kantin/detail_chart_manual/Manual/" + opsi + "/" + periode;
                window.open(link);
            } else if (data.name == "Flat") {
                link = "<?php echo base_url() ?>Kantin/detail_chart_manual/Flat/" + opsi + "/" + periode;
                window.open(link);
            } else {
                link = "<?php echo base_url() ?>Kantin/detail_chart_manual/Scan/" + opsi + "/" + periode;
                window.open(link);
            }
        })
    }

    function chart_compare() {
        var myChart = echarts.init(document.getElementById('main3'));
        var option = {
            title: {
                text: 'Absensi & Makan',
                subtext: sekarang,
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['Absen', 'Makan']
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {
                        show: true
                    },
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    // restore : {show: true},
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'absen',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                label: {
                    normal: {
                        formatter: '{b} : {c} ({d}%)',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [{
                        value: document.getElementById('absen_makan').value,
                        name: 'Tidak Absen - Makan'
                    },
                    {
                        value: document.getElementById('makan_absen').value,
                        name: 'Absen - Makan'
                    }
                ]
            }]
        };

        myChart.setOption(option);
        myChart.on('click', function(data) {
            opsi = document.getElementById('opsi_manual').value;
            log = console.log(data.name);
            if (data.name == 'Absen - Makan') {
                link = "<?php echo base_url() ?>Kantin/detail_chart_compare/Makan/" + periode;
                window.open(link);
            } else {
                link = "<?php echo base_url() ?>Kantin/detail_chart_compare/Absen/" + periode;
                window.open(link);
            }
        })
    }
</script>
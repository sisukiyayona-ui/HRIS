<!DOCTYPE html>
<html lang="en">

<head>
    <title>SK Promosi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: "verdana";
            text-align: justify;
        }

        .borderless td,
        .borderless th {
            border: none;
        }
    </style>
</head>

<body>

    <div class="container" style="padding-top:100px">
        <div class="row">
            <h3 style="text-align:center;text-decoration: underline; font-weight:bold">SURAT KEPUTUSAN PROMOSI</h3>
            <p style="text-align:center;font-size:8pt;">Nomor :<?php echo $no_sk ?></p>
        </div>
        <div class="row" style="margin-top:30px;">
            <table>
                <tr>
                    <td width="20%" valign="top">
                        <p style="text-align:left;font-size:10pt;font-weight:bold;">Menimbang</p>
                    </td>
                    <td width="2%" valign="top">:</td>
                    <td>
                        <div>
                            <ol>
                                <li> Guna memenuhi tuntutan serta mengantisipasi terhadap sistem kerja Perusahaan yang lebih efektif.</li>
                                <li>Perencanaan dan Pengembangan Sumber Daya Manusia Perusahaan.</li>
                                <li>Mengoptimalkan potensi yang dimiliki oleh karyawan.</li>
                            </ol>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align:left;font-size:10pt;font-weight:bold;">Mengingat</p>
                    </td>
                    <td width="2%" valign="top">:</td>
                    <td>
                        <p>Pasal 13 PKB PT Chitose Internasional Tbk 2022 - 2024</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" valign="center" style="text-align:center; padding:3px;">
                        <p style=" text-align:center;font-size:14pt;font-weight:bold;letter-spacing: 3px;">MEMUTUSKAN</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style=" text-align:left;font-size:10pt;font-weight:bold;">Menetapkan</p>
                    </td>
                    <td width="2%" valign="top">:</td>
                    <td>

                        <table class="table" style="margin-top:15px">
                            <tr>
                                <td width=" 20%">Nama</td>
                                <td width="2%">:</td>
                                <td><?php echo $nama ?></td>
                            </tr>
                            <tr>
                                <td width="20%">NIK</td>
                                <td>:</td>
                                <td><?php echo $nik ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Departemen</td>
                                <td>:</td>
                                <td><?php echo $bef_dept ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Bagian</td>
                                <td>:</td>
                                <td><?php echo $bef_bagian ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Golongan</td>
                                <td>:</td>
                                <td><?php echo $bef_golongan ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Jabatan</td>
                                <td>:</td>
                                <td><?php echo $bef_jbtn ?></td>
                            </tr>
                            <tr>
                                <td colspan='3'>Bahwa karyawan sebagaimana tersebut di atas dipromosikan untuk menduduki golongan dan jabatan baru sebagai berikut :</td>
                            </tr>
                            <tr>
                                <td width="20%">Departemen</td>
                                <td>:</td>
                                <td><?php echo $new_dept ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Bagian</td>
                                <td>:</td>
                                <td><?php echo $new_bagian ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Golongan</td>
                                <td>:</td>
                                <td><?php echo $new_golongan ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Jabatan</td>
                                <td>:</td>
                                <td><?php echo $new_jbtn ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>


        <div class="row" style="margin-top:20px">
            <div class="col-sm-12">
                <p style="text-align:left;font-size:10pt;font-weight:bold;">Dengan syarat-syarat :</p>
                <ol>
                    <li>Jabatan, perubahan tugas baru dan pelaksanaannya mulai berlaku tanggal <?php echo $tgl_m_karir ?>.</li>
                    <li>Masa Evaluasi selama 3 (tiga) bulan sejak tanggal berlakunya promosi ini.</li>
                    <li>Hak dan kewajibannya disesuaikan dengan kebijaksanaan Perusahaan yang berlaku dan mulai berlaku sejak tanggal berlakunya promosi ini.</li>
                    <li>Apabila di kemudian hari terdapat kekeliruan atau kekurangan dalam penetapan ini, maka akan diadakan perubahan sebagaimana mestinya.</li>
                </ol>
            </div>
        </div>

        <div class="row" style="margin-top:20px">
            <div class="col-sm-12">
                <p>Demikian Surat Keputusan Promosi ini dibuat untuk dilaksanakan dengan baik.</p>
            </div>
        </div>

        <div class="row" style="margin-top:30px">
            <div class="col-sm-12">
                <p style="text-align:center;">Cimahi, ______________________</p>
                <div style="height:75px"></div>
                <?php if ($tingkatan >= 6) { ?>
                    <p style="text-align:center; text-decoration:underline">R. Nurwulan Kusumawati</p>
                    <p style="text-align:center;">Director </p>
                <?php } else { ?>
                    <p style="text-align:center; text-decoration:underline">Diah Nur Kusumawardhani</p>
                    <p style="text-align:center;">HC&GA Assisten Manager </p>
                <?php } ?>

            </div>
        </div>
    </div>

</body>

</html>
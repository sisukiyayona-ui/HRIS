
<? header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Report.doc");
?>
<html>
<head></head>
<title></title>
<body>

<table border = "0" width="100%">
		<tr><td><b>PT. Chitose Internasional Tbk.</b></td><td rowspan="3"><img src="<?php echo base_url()?>assets/logo-kecil2.png"></td></tr>
		<tr><td><b>Jl. Industri III No. 5 Kelurahan Utama Cimahi 40533</b></td></tr>
		<tr><td><b>Departement HC & GA</b></td></tr>
</table>
<br>
<table border="1" width="100%">
	<tr><td><center><u>FORMULIR USULAN PELATIHAN (F-UP)</u></center></td></tr>
	<tr><td><center>CINT/HC&GA/F-003.1/Formulir Usulan Pelatihan</center></td></tr>
</table>
<br>
<table class="table table-striped table-bordered" border="0" width="100%">
	<?php foreach ($pengaju as $aju) { ?>
		<tr><td colspan="3">Diajukan Oleh : </td></tr>
		<tr><td>Nama </td><td> : </td><td><?php echo $aju->nama_karyawan ?></td></tr>
		<tr><td>Bagian </td><td> : </td><td><?php echo $aju->indeks_hr ?></td></tr>
		<tr><td>Jabatan </td><td> : </td><td><?php echo $aju->indeks_jabatan?></td></tr>
		<tr><td>Department</td><td> : </td><td><?php echo $aju->department ?></td></tr>
		<tr><td>Tanggal Pengajuan </td><td> : </td><td><?php echo $newDate = date("d M Y", strtotime($aju->tgl_pengajuan));?></td></tr>
		<tr><td colspan="3"><br>Mengusulkan nama - nama di bawah ini :</td></tr>
	<?php } ?>
</table>

<table class="table table-striped table-bordered" border="1" width="100%">
	<thead>
		<th>No</th>
		<th>NIK</th>
		<th>Nama</th>
		<th>Bagian</th>
		<th>Jabatan</th>
	</thead>
	<tbody>
		<?php
		$no = 0;
		foreach ($karyawan as $key) {
			$no = $no + 1;
			?>
			<tr>
				<td><center><?php echo $no ?></center></td>
				<td><?php echo $key->nik ?></td>
				<td><?php echo $key->nama_karyawan ?></td>
				<td><?php echo $key->indeks_hr ?></td>
				<td><?php echo $key->indeks_jabatan ?></td>
			</tr>
		<?php  }
		?>
	</tbody>
</table>
<br>
<table class="table table-striped table-bordered" border="0" width="100%">
	<?php foreach ($training as $t) { ?>
		<tr><td colspan="3">Mengikuti Training, Seminar, Workshop, Sertifikasi, Other *</td></tr>
		<tr><td>Jenis Pelatihan </td><td> : </td><td><?php echo $t->jenis_training ?></td></tr>
		<tr><td>Topik </td><td> : </td><td><?php echo $t->judul_training ?></td></tr>
		<tr><td>Pelatih </td><td> : </td><td><?php echo $t->trainer?></td></tr>
		<tr><td>Tanggal Pelatihan </td><td> : </td><td><?php echo $newDate = date("d M Y", strtotime($t->tgl_m_training));?> - <?php echo $newDate = date("d M Y", strtotime($t->tgl_a_training));?></td></tr>
		<tr><td>Tempat</td><td> : </td><td><?php echo $t->tempat_training ?></td></tr>
		<tr><td>Waktu</td><td> : </td><td><?php echo $t->jml_jam ?> Jam </td></tr>
		<tr><td>Penyelenggara</td><td> : </td><td><?php echo $t->lembaga ?></td></tr>
		<tr><td>Biaya</td><td> : </td><td>Rp. <?php echo number_format($t->biaya) ?></td></tr>
		<tr><td>Alasan Pengajuan</td><td> : </td><td><?php echo $t->alasan_pengajuan ?></td></tr>

	<?php } ?>
</table>
<br>
<table class="table table-striped table-bordered" border="1" width="100%">
	<tr><td> *****Manager HC & GA</td><td>Keterangan</td></tr>
	<tr><td><br><br><br><br></td><td></td></tr>
	<tr><td>Tanggal </td><td></td></tr>
	<tr><td> *****Direksi</td><td>Keterangan</td></tr>
	<tr><td><br><br><br><br></td><td><center>Disetujui / Ditolak*</center></td></tr>
	<tr><td>Tanggal </td><td></td></tr>
</table>

</body>
</html>
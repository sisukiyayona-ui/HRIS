Tanggungan Karyawan<hr><br>
<table class="table" width="100%" border="1">
	
		
		<tr>
		<td><b>Nama Tanggungan</b></td>
		<td><b>Hubungan Keluarga</b></td>
		<td><b>No NIK (KTP)</b></td>
		<td><b>No BPJS</b></td>
		<td><b>Status Tanggungan</b></td></tr>
	<tbody>
		<?php foreach ($karyawan as $kary ) {
			$nama = $kary->nama_karyawan;
			$no_bpjs_kes = $kary->no_bpjs_kes;
			$no_ktp = $kary->no_ktp;
			$nama = strtolower($nama);
		} ?>
		<tr><td><?php echo  ucwords($nama) ?></td>
			<td>Karyawan</td>
			<td><?php echo $no_ktp ?></td>
			<td><?php echo $no_bpjs_kes ?></td>
			<td>Ditanggung</td></tr>
			
	<?php foreach ($tunjangan as $data) { 
		$nama_tunjangan = $data->nama_tunjangan;
		$nama_tunjangan = strtolower($nama_tunjangan);
		?>
		<tr><td><?php echo  ucwords($nama_tunjangan) ?></td>
			<td><?php echo  $data->hub_keluarga ?></td>
			<td><?php echo  $data->no_id ?></td>
			<td><?php echo  $data->no_bpjs ?></td>
			<td><?php if($data->sts_tunjangan == "Yes"){echo "Ditanggung";}else{echo "Tidak Ditanggung";}  ?></td></tr>
	<?php } ?>
	</tbody>
</table>
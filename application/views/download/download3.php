Tanggungan Karyawan<hr><br>
<table class="table" width="100%" border="1">
	<?php foreach ($tunjangan as $data) {
        $nama = $data->nama_karyawan;
		$nama = strtolower($nama);     
	} ?>

	<tr  bgcolor="#c0daf5"><td><b>Nama Karyawan</b></td><td><b><?php echo ucwords($nama)?></b></td></tr>
	<tr><td>Nama Tanggungan</td><td><?php echo $data->nama_tunjangan ?></td></tr>
	<tr><td>Hubungan Keluarga</td><td><?php echo $data->hub_keluarga ?></td></tr>
	<tr><td >Tempat, Tanggal Lahir</td><td><?php echo "$data->tmp_tlahir, $data->tgl_tlahir"; ?></td></tr>
	<tr><td>No NIK KK</td><td><?php echo $data->no_id ?></td></tr>
	<tr><td>Agama</td><td><?php echo $data->agama ?></td></tr>
	<tr><td>Pendidikan</td><td><?php echo "$data->panak"; ?></td></tr> 
	<tr><td>Pekerjaan</td><td><?php echo $data->pekerjaan ?></td></tr>
	<tr><td>No BPJS</td><td><?php echo $data->no_bpjs ?></td></tr> 
	<tr><td>Status Tunjangan</td><td><?php if($data->sts_tunjangan == "Yes"){echo "Ditanggung";}else{echo "Tidak Ditanggung";} ?></td></tr> 
</table>
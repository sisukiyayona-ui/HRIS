<?php
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; Filename=SaveAsWordDoc.doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
<title>Saves as a Word Doc</title>
</head>
<body>
<h1>Header</h1>
<?php foreach ($karyawan as $data) {
    $foto = $data->foto;
    $nama = $data->nama_karyawan;
} ?>
<img style="width: 100%; display: block;" src="<?php echo base_url()?>images/user.png" alt="image" />
<table>
	<tr><td>Nama Karyawan</td><td><?php echo $nama ?></td></tr>
</table>
</body>
</html>
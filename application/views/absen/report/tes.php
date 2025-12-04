<?php
	$start = '2019-07-01';
	$end = '2019-07-31';
?>
<table border="1">
	<tr>
		<td>NIK</td>
		<td>Nama</td>
		<td>Bagian</td>
		<?php
			while($start <= $end)
			{
				echo "<td>$start</td>";
				$start = date('Y-m-d', strtotime('+1 days', strtotime($start)));
			}
		?>
		<td>Hadir</td>
		<td>Cuti</td>
		<td>SID</td>
		<td>H1</td>
		<td>H2</td>
		<td>P1</td>
		<td>P3</td>
		<td>P4</td>
		<td>MA</td>
	</tr>
<?php 
	foreach ($nilai as $nilai) {
		$sejak = '2019-07-01';
		$sampai = '2019-07-31';

		echo "<tr><td>".$nilai[0]."</td>";
		echo "<td>".$nilai[1]."</td>";
		echo "<td>".$nilai[2]."</td>";
		// echo $nilai[0]." | ".$nilai[1]." | ".$nilai[2]."  :  ";
		$i = 2;
		while($sejak <= $sampai)
		{
			$i = $i + 1;
			// echo $nilai[$i]." | ";
			echo "<td>".$nilai[$i]."</td>";
			$sejak = date('Y-m-d', strtotime('+1 days', strtotime($sejak)));
		}
			// 0echo "<td>".$nilai[35]."</td>";
		$i = $i;
		$x = $i+9;
		while ($i < $x)
		{
			echo "<td>".$nilai[$i]."</td>";
			$i++;
		}
		
	

	echo "</tr>";

	}
?>
</table>

<?php echo "i = $i <br>".$nilai[$i] ?>
<br>rendering : <?php echo $waktu; ?> seconds.
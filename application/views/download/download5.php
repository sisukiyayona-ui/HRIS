<h4>Histori Karir Karyawan </h4><hr><br>
<div role="tabpanel" class="tab-pane fade" id="tab_content33" aria-labelledby="profile-tab">
	<div class="x_content">
		<ul class="list-unstyled timeline">
			<?php foreach ($karyawan as $data2 ) {
				$aktif = $data2->sts_aktif;
			} ?>
			<?php foreach ($karir as $data) { ?>
				<div class="block">
					<div class="block_content">
						<p><?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d 
							<?php if($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00'){echo "Sekarang";}else{ echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));} ?> 

							<a href="#"><?php if($data->no_perjanjian == ''){echo "(Tidak Ada no SK)";}else{ echo "($data->no_perjanjian)"; } ?></a>
								<table class="table">
									<tr><td>Jenis Karir</td><td>:</td><td><?php echo $data->kategori ?></td></tr>
									<tr><td>Bagian</td><td>:</td><td><?php echo $data->indeks_hr ?></td></tr>
									<tr><td>Jabatan</td><td>:</td><td><?php echo  strtoupper($data->indeks_jabatan)?> - <?php echo  strtoupper($data->sts_jabatan)?></td></tr>
									<tr><td>Keterangan</td><td>:</td><td><?php echo "$data->note";?></td></tr>
								</table>
						</div>
					</div>
					<hr>
				<?php } ?>
				<?php foreach ($karyawan as $data2 ) {
					$aktif = $data2->sts_aktif;
				} ?>
			</ul>

		</div>
	</div>
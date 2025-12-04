<h4>Histori Training Karyawan </h4><hr><br>
<div role="tabpanel" class="tab-pane fade" id="tab_content33" aria-labelledby="profile-tab">
	<div class="x_content">
		<ul class="list-unstyled timeline">
			<?php foreach ($karyawan as $data2 ) {
				$aktif = $data2->sts_aktif;
			} ?>
			<?php foreach ($training as $data) { ?>
				<div class="block">
					<div class="block_content">
						<p><?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d 
							<?php if($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00'){echo "Sekarang";}else{ echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));} ?> 

							<a href="#"><?php if($data->no_perjanjian == ''){echo "(Tidak Ada no SK)";}else{ echo "($data->no_perjanjian)"; } ?></a>
								<table class="table">
									<td>Jenis Karir</td><td>:</td><td><?php echo $data->kategori ?></td></tr>
                                    <tr><td>Topik Training</td><td>:</td><td><?php echo ucwords($data->judul_training) ?></td></tr>
                                    <tr><td>Tempat</td><td>:</td><td><?php echo  ucwords($data->tempat_training)?></td></tr>
                                    <tr><td>Keterangan</td><td>:</td><td><?php echo ucwords($data->note)?></td></tr>
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
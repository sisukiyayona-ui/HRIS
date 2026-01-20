<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Update Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>
              <a href="<?php echo base_url() ?>Karyawan/karyawan_view" class="btn-back">
                <i class="fa fa-arrow-circle-o-left"></i> Kembali
              </a> 
              <span class="form-title">Edit Data Personal</span>
            </h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Content Form -->
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/karyawan_pupdate" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

              <?php foreach ($karyawan as $data) { } ?>

              <!-- Foto Profil -->
              <div class="row">
                <div class="col-md-12">
                  <div class="profile-section">
                    <h3><i class="fa fa-user"></i> Foto Profil</h3>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="profile-image-container">
                          <?php if ($data->foto == '') { ?>
                            <img class="profile-img" src="<?php echo base_url() ?>images/user.png" alt="image" />
                          <?php } else { ?>
                            <img class="profile-img" src="<?php echo base_url() ?>images/foto/<?php echo $data->foto ?>" alt="image" />
                          <?php } ?>
                          <?php if ($data->foto != '') { ?>
                            <div class="image-caption">
                              <a href="<?php echo base_url() ?>images/foto/<?php echo $data->foto ?>" target="_blank">
                                <?php echo $data->foto ?>
                              </a>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-md-9">
                        <div class="form-group">
                          <label>Ubah Foto</label>
                          <p class="text-muted small">* Kosongkan jika tidak ingin mengubah foto</p>
                          <input type="hidden" name="foto2" value="<?php echo $data->foto ?>">
                          <input type="file" name="foto" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Personal -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-id-card"></i> Data Personal</h3>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">NIK *</label>
                          <input type="text" class="form-control" value="<?php echo $data->nik ?>" readonly>
                          <input type="hidden" name="nik" value="<?php echo $data->nik ?>">
                          <input type="hidden" name="recid_karyawan" value="<?php echo $data->recid_karyawan ?>">
                          <input type="hidden" name="recid_bag" value="<?php echo $data->recid_bag ?>">
                          <input type="hidden" name="recid_jbtn" value="<?php echo $data->recid_jbtn ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Nama Karyawan *</label>
                          <input type="text" class="form-control" name="nama_karyawan" value="<?php echo $data->nama_karyawan ?>" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tempat Lahir *</label>
                          <input type="text" class="form-control" name="tmp_lahir" value="<?php echo $data->tmp_lahir ?>" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tanggal Lahir</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control" name="tgl_lahir" value="<?php echo $data->tgl_lahir ?>">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Jenis Kelamin</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="jenkel" value="Laki - laki" <?php echo ($data->jenkel == "Laki - laki") ? 'checked' : '' ?>> Laki-laki
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="jenkel" value="Perempuan" <?php echo ($data->jenkel == "Perempuan") ? 'checked' : '' ?>> Perempuan
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Golongan Darah</label>
                          <div class="radio-group">
                            <?php 
                            $gol_darah_options = ['A', 'B', 'O', 'AB', '-'];
                            foreach ($gol_darah_options as $option) {
                              $checked = ($data->gol_darah == $option) ? 'checked' : '';
                              echo "<label class='radio-inline'><input type='radio' name='gol_darah' value='$option' $checked> $option</label>";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Agama</label>
                          <select name="agama" class="form-control">
                            <?php
                            // Sesuaikan dengan ENUM di tabel karyawan
                            $agm = ["Islam", "Kristen", "Hindu", "Budha", "Protestan", "Katolik", "Konghucu"];
                            foreach ($agm as $a) {
                              $selected = ($data->agama == $a) ? 'selected' : '';
                              echo "<option value='$a' $selected>$a</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Status Perkawinan</label>
                          <div class="radio-group">
                            <?php
                            $status_options = [
                              'Belum Kawin' => 'Belum Kawin',
                              'Kawin' => 'Kawin',
                              'Janda' => 'Janda',
                              'Duda' => 'Duda'
                            ];
                            foreach ($status_options as $value => $label) {
                              $checked = ($data->sts_nikah == $value) ? 'checked' : '';
                              echo "<label class='radio-inline'><input type='radio' name='sts_nikah' value='$value' $checked> $label</label>";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Dokumen -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-file-text"></i> Dokumen</h3>
                    
                    <!-- KTP -->
                    <div class="document-group">
                      <h4>KTP</h4>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Nomor KTP</label>
                            <input type="text" class="form-control" name="no_ktp" onkeypress="return hanyaAngka(event)" value="<?php echo $data->no_ktp ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Scan KTP</label>
                            <?php if ($data->scan_ktp != '') { ?>
                              <p>
                                <a href="<?php echo base_url() ?>images/ktp/<?php echo $data->scan_ktp ?>" target="_blank" class="btn-link">
                                  <i class="fa fa-eye"></i> Lihat File
                                </a>
                              </p>
                            <?php } ?>
                            <input type="hidden" name="scan_ktp2" value="<?php echo $data->scan_ktp ?>">
                            <input type="file" name="scan_ktp" class="form-control">
                            <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- KK -->
                    <div class="document-group">
                      <h4>Kartu Keluarga</h4>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Nomor KK</label>
                            <input type="text" class="form-control" name="no_kk" onkeypress="return hanyaAngka(event)" value="<?php echo $data->no_kk ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Scan KK</label>
                            <?php if ($data->scan_kk != '') { ?>
                              <p>
                                <a href="<?php echo base_url() ?>images/kk/<?php echo $data->scan_kk ?>" target="_blank" class="btn-link">
                                  <i class="fa fa-eye"></i> Lihat File
                                </a>
                              </p>
                            <?php } ?>
                            <input type="hidden" name="scan_kk2" value="<?php echo $data->scan_kk ?>">
                            <input type="file" name="scan_kk" class="form-control">
                            <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- NPWP -->
                    <div class="document-group">
                      <h4>NPWP</h4>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Status NPWP</label>
                            <div class="radio-group">
                              <label class="radio-inline">
                                <input type="radio" name="cek_npwp" value="1" <?php echo ($data->cek_npwp == "1") ? 'checked' : '' ?>> Ada
                              </label>
                              <label class="radio-inline">
                                <input type="radio" name="cek_npwp" value="0" <?php echo ($data->cek_npwp == "0") ? 'checked' : '' ?>> Tidak Ada
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Nomor NPWP</label>
                            <input type="text" class="form-control" name="no_npwp" value="<?php echo $data->no_npwp ?>">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Scan NPWP</label>
                            <?php if ($data->scan_npwp != '') { ?>
                              <p>
                                <a href="<?php echo base_url() ?>images/npwp/<?php echo $data->scan_npwp ?>" target="_blank" class="btn-link">
                                  <i class="fa fa-eye"></i> Lihat File
                                </a>
                              </p>
                            <?php } ?>
                            <input type="hidden" name="scan_npwp2" value="<?php echo $data->scan_npwp ?>">
                            <input type="file" name="scan_npwp" class="form-control">
                            <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Pendidikan -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-graduation-cap"></i> Pendidikan</h3>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Pendidikan Terakhir</label>
                          <select name="pendidikan" class="form-control">
                            <?php
                            // Sesuaikan dengan ENUM di tabel karyawan
                            $pend = ["SD", "SMP", "SMA", "D3", "S1", "S2", "SMK", "D1", "D2"];
                            foreach ($pend as $p) {
                              $selected = ($data->pendidikan == $p) ? 'selected' : '';
                              echo "<option value='$p' $selected>$p</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Jurusan</label>
                          <input type="text" class="form-control" name="jurusan" value="<?php echo $data->jurusan ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Tahun Lulus</label>
                          <input type="text" class="form-control" name="thn_lulus" value="<?php echo $data->thn_lulus ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Scan Ijazah</label>
                          <?php if ($data->scan_ijazah != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/ijazah/<?php echo $data->scan_ijazah ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_ijazah2" value="<?php echo $data->scan_ijazah ?>">
                          <input type="file" name="scan_ijazah" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data BPJS & Asuransi -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-heartbeat"></i> BPJS & Asuransi</h3>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Nomor Jamsostek</label>
                          <input type="text" class="form-control" name="no_jamsos" value="<?php echo $data->no_jamsos ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Cek BPJS TK (cek_no_bpjs_tk)</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="cek_no_bpjs_tk" value="1" <?php echo ($data->cek_no_bpjs_tk == "1") ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="cek_no_bpjs_tk" value="0" <?php echo ($data->cek_no_bpjs_tk == "0") ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">BPJS Kesehatan</label>
                          <input type="text" class="form-control" name="no_bpjs_kes" onkeypress="return hanyaAngka(event)" value="<?php echo $data->no_bpjs_kes ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Scan BPJS Kesehatan</label>
                          <?php if ($data->scan_bpjs_kes != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/bpjs_kes/<?php echo $data->scan_bpjs_kes ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_bpjs_kes2" value="<?php echo $data->scan_bpjs_kes ?>">
                          <input type="file" name="scan_bpjs_kes" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">BPJS Tenaga Kerja</label>
                          <input type="text" class="form-control" name="no_bpjs_tk" value="<?php echo $data->no_bpjs_tk ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">No Kartu Diluar Trimas</label>
                          <input type="text" class="form-control" name="no_kartu_diluar_trimas" value="<?php echo $data->no_kartu_diluar_trimas ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">No Kartu Trimas</label>
                          <input type="text" class="form-control" name="no_kartu_trimas" value="<?php echo $data->no_kartu_trimas ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Scan BPJS TK</label>
                          <?php if ($data->scan_bpjs_tk != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/bpjs_tk/<?php echo $data->scan_bpjs_tk ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_bpjs_tk2" value="<?php echo $data->scan_bpjs_tk ?>">
                          <input type="file" name="scan_bpjs_tk" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">AIA</label>
                          <input type="text" class="form-control" name="no_aia" value="<?php echo $data->no_aia ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Scan AIA</label>
                          <?php if ($data->scan_aia != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/aia/<?php echo $data->scan_aia ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_aia2" value="<?php echo $data->scan_aia ?>">
                          <input type="file" name="scan_aia" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data SIM -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-car"></i> Surat Izin Mengemudi</h3>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">SIM 1</label>
                          <input type="text" class="form-control" name="sim1" value="<?php echo $data->sim1 ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Scan SIM 1</label>
                          <?php if ($data->scan_sim1 != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim1 ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_sim12" value="<?php echo $data->scan_sim1 ?>">
                          <input type="file" name="scan_sim1" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">SIM 2</label>
                          <input type="text" class="form-control" name="sim2" value="<?php echo $data->sim2 ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Scan SIM 2</label>
                          <?php if ($data->scan_sim2 != '') { ?>
                            <p>
                              <a href="<?php echo base_url() ?>images/sim/<?php echo $data->scan_sim2 ?>" target="_blank" class="btn-link">
                                <i class="fa fa-eye"></i> Lihat File
                              </a>
                            </p>
                          <?php } ?>
                          <input type="hidden" name="scan_sim22" value="<?php echo $data->scan_sim2 ?>">
                          <input type="file" name="scan_sim2" class="form-control">
                          <small class="text-muted">* Kosongkan jika tidak ingin mengubah</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Status & Kontak -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-info-circle"></i> Status & Kontak</h3>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Status Karyawan</label>
                          <div class="radio-group vertical">
                            <?php
                            // Sesuaikan dengan ENUM di tabel karyawan
                            $status_aktif = ["Aktif", "Resign", "Pensiun", "Tidak Aktif", "Blacklist"];
                            foreach ($status_aktif as $status) {
                              $checked = ($data->sts_aktif == $status) ? 'checked' : '';
                              echo "<div class='radio-item'><label><input type='radio' name='sts_aktif' value='$status' $checked> $status</label></div>";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Kontrak Kerja</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="kontrak" value="Ya" <?php echo ($data->kontrak == 'Ya') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="kontrak" value="Tidak" <?php echo ($data->kontrak == 'Tidak') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="control-label">Bulanan</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="bulanan" value="Ya" <?php echo ($data->bulanan == 'Ya') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="bulanan" value="Tidak" <?php echo ($data->bulanan == 'Tidak') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label class="control-label">Status Penunjang</label>
                          <select name="sts_penunjang" class="form-control">
                            <?php
                            $ptkp = ["TK", "K0", "K1", "K2", "K3", "TK1", "TK2", "TK3"];
                            $ptkp_name = ["Tidak Kawin", "Kawin Anak 0", "Kawin Anak 1", "Kawin Anak 2", "Kawin Anak 3", "Tidak Kawin Anak 1", "Tidak Kawin Anak 2", "Tidak Kawin Anak 3"];
                            for ($i = 0; $i < count($ptkp); $i++) {
                              $selected = ($data->sts_penunjang == $ptkp[$i]) ? 'selected' : '';
                              echo "<option value='{$ptkp[$i]}' $selected>{$ptkp_name[$i]}</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Telepon 1 *</label>
                          <input type="text" class="form-control" name="telp1" onkeypress="return hanyaAngka(event)" value="<?php echo $data->telp1 ?>" required>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Telepon 2</label>
                          <input type="text" class="form-control" name="telp2" onkeypress="return hanyaAngka(event)" value="<?php echo $data->telp2 ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Email</label>
                          <input type="email" class="form-control" name="email" value="<?php echo $data->email ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Alamat KTP</label>
                          <textarea class="form-control" name="alamat_ktp" rows="3"><?php echo $data->alamat_ktp ?></textarea>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Kota (KTP)</label>
                          <input type="text" class="form-control" name="kota_ktp" value="<?php echo $data->kota_ktp ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Alamat Sekarang</label>
                          <textarea class="form-control" name="alamat_skrg" rows="3"><?php echo $data->alamat_skrg ?></textarea>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Kota (Sekarang)</label>
                          <input type="text" class="form-control" name="kota_skrg" value="<?php echo $data->kota_skrg ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Kota (Umum)</label>
                          <input type="text" class="form-control" name="kota" value="<?php echo $data->kota ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Keluarga -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-users"></i> Data Keluarga</h3>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Nama Orang Tua</label>
                          <input type="text" class="form-control" name="nama_orang_tua" value="<?php echo $data->nama_orang_tua ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Nama Pasangan</label>
                          <input type="text" class="form-control" name="nama_pasangan" value="<?php echo $data->nama_pasangan ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Jumlah Anak</label>
                          <input type="number" class="form-control" name="jumlah_anak" value="<?php echo $data->jumlah_anak ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Nama Anak</label>
                          <textarea class="form-control" name="nama_anak" rows="2"><?php echo $data->nama_anak ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kepegawaian / SK -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-briefcase"></i> Kepegawaian</h3>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">SK Karyawan Tetap (Nomor)</label>
                          <input type="text" class="form-control" name="sk_kary_tetap_nomor" value="<?php echo $data->sk_kary_tetap_nomor ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">SK Karyawan Tetap (Tanggal)</label>
                          <div class="input-group date datepicker">
                            <input type="text" class="form-control" name="sk_kary_tetap_tanggal" value="<?php echo $data->sk_kary_tetap_tanggal ?>">
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">E-FIN</label>
                          <input type="text" class="form-control" name="efin" value="<?php echo $data->efin ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Struktur -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-sitemap"></i> Struktur</h3>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Departemen</label>
                          <select class="form-control" name="departemen">
                            <option value="">- Pilih Departemen -</option>
                            <?php if (!empty($department_list)) { ?>
                              <?php foreach ($department_list as $dpt) { ?>
                                <?php $selected = ((string)$data->departemen === (string)$dpt->nama_department) ? 'selected' : ''; ?>
                                <option value="<?php echo $dpt->nama_department ?>" <?php echo $selected; ?>>
                                  <?php echo $dpt->nama_department; ?>
                                </option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Bagian</label>
                          <select class="form-control" name="recid_bag" id="recid_bag">
                            <option value="0">- Pilih Bagian -</option>
                            <?php if (!empty($bagian_list)) { ?>
                              <?php foreach ($bagian_list as $bag) { ?>
                                <?php
                                  $selected = ((string)$data->recid_bag === (string)$bag->recid_bag) ? 'selected' : '';
                                  $label = isset($bag->nama_bag) ? $bag->nama_bag : (isset($bag->indeks_hr) ? $bag->indeks_hr : $bag->recid_bag);
                                ?>
                                <option value="<?php echo $bag->recid_bag; ?>" <?php echo $selected; ?>>
                                  <?php echo $label; ?>
                                </option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Sub Bagian</label>
                          <select class="form-control" name="recid_subbag" id="recid_subbag">
                            <option value="0">- Pilih Sub Bagian -</option>
                            <?php if (!empty($sub_bagian_list)) { ?>
                              <?php foreach ($sub_bagian_list as $sb) { ?>
                                <?php $selected = ((string)$data->recid_subbag === (string)$sb->recid_subbag) ? 'selected' : ''; ?>
                                <option value="<?php echo $sb->recid_subbag ?>" <?php echo $selected; ?>>
                                  <?php echo $sb->sub_bag; ?>
                                </option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                          <!-- disimpan juga nama sub bagian ke kolom karyawan.sub_bagian -->
                          <input type="hidden" name="sub_bagian" id="sub_bagian" value="<?php echo $data->sub_bagian ?>">
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Jabatan</label>
                          <select class="form-control" name="recid_jbtn">
                            <option value="0">- Pilih Jabatan -</option>
                            <?php if (!empty($jabatan_list)) { ?>
                              <?php foreach ($jabatan_list as $jb) { ?>
                                <?php
                                  $selected = ((string)$data->recid_jbtn === (string)$jb->recid_jbtn) ? 'selected' : '';
                                  $label = isset($jb->nama_jbtn) ? $jb->nama_jbtn : $jb->recid_jbtn;
                                ?>
                                <option value="<?php echo $jb->recid_jbtn; ?>" <?php echo $selected; ?>>
                                  <?php echo $label; ?>
                                </option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Golongan</label>
                          <select class="form-control" name="recid_golongan">
                            <option value="0">- Pilih Golongan -</option>
                            <?php if (!empty($golongan_list)) { ?>
                              <?php foreach ($golongan_list as $gol) { ?>
                                <?php
                                  $selected = ((string)$data->recid_golongan === (string)$gol->recid_golongan) ? 'selected' : '';
                                  $label = isset($gol->nama_golongan) ? $gol->nama_golongan : (isset($gol->golongan) ? $gol->golongan : $gol->recid_golongan);
                                ?>
                                <option value="<?php echo $gol->recid_golongan ?>" <?php echo $selected; ?>>
                                  <?php echo $label; ?>
                                </option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Seragam -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-tags"></i> Seragam</h3>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Ukuran Baju</label>
                          <input type="text" class="form-control" name="ukuran_baju" value="<?php echo $data->ukuran_baju ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Ukuran Celana</label>
                          <input type="text" class="form-control" name="ukuran_celana" value="<?php echo $data->ukuran_celana ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Ukuran Sepatu</label>
                          <input type="text" class="form-control" name="ukuran_sepatu" value="<?php echo $data->ukuran_sepatu ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Keterangan Seragam</label>
                          <input type="text" class="form-control" name="keterangan_seragam" value="<?php echo $data->keterangan_seragam ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Lainnya -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-sticky-note"></i> Lainnya</h3>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Tipe PTKP</label>
                          <input type="text" class="form-control" name="tipe_ptkp" value="<?php echo $data->tipe_ptkp ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">DL/IDL</label>
                          <input type="text" class="form-control" name="dl_idl" value="<?php echo $data->dl_idl ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Level</label>
                          <input type="text" class="form-control" name="level" value="<?php echo $data->level ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Email TSGI</label>
                          <input type="email" class="form-control" name="email_tsgi" value="<?php echo $data->email_tsgi ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Alasan Keluar</label>
                          <textarea class="form-control" name="alasan_keluar" rows="2"><?php echo $data->alasan_keluar ?></textarea>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Keterangan</label>
                          <textarea class="form-control" name="keterangan" rows="2"><?php echo $data->keterangan ?></textarea>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Note</label>
                          <textarea class="form-control" name="note" rows="2"><?php echo $data->note ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Data Tambahan -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-plus-circle"></i> Data Tambahan</h3>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Vaksin Covid-19</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="vaksin_covid" value="1" <?php echo ($data->vaksin_covid == '1') ? 'checked' : '' ?>> Sudah
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="vaksin_covid" value="0" <?php echo ($data->vaksin_covid == '0') ? 'checked' : '' ?>> Belum
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Profile DISC</label>
                          <div class="radio-group">
                            <?php
                            $prof = ["-", "D", "I", "S", "C"];
                            $prof_val = ["Undefined", "Dominant", "Influence", "Steadlines", "Compliant"];
                            foreach ($prof as $index => $value) {
                              $checked = ($data->profile_disc == $value) ? 'checked' : '';
                              echo "<label class='radio-inline'><input type='radio' name='profile_disc' value='$value' $checked> {$prof_val[$index]}</label>";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Kategori Penyakit</label>
                          <div class="radio-group">
                            <?php
                            $kat = ["Ringan", "Berat", "Sedang"];
                            foreach ($kat as $value) {
                              $checked = ($data->kat_penyakit == $value) ? 'checked' : '';
                              echo "<label class='radio-inline'><input type='radio' name='kat_penyakit' value='$value' $checked> $value</label>";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Pattern Type</label>
                          <input type="text" class="form-control" name="pattern_type" value="<?php echo $data->pattern_type ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Profile Type</label>
                          <input type="text" class="form-control" name="profile_type" value="<?php echo $data->profile_type ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Penyakit / Riwayat Kesehatan</label>
                          <textarea class="form-control" name="penyakit" rows="2"><?php echo $data->penyakit ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if ($role == '1' or $role == '5') { ?>
              <!-- Data Gaji -->
              <div class="row">
                <div class="col-md-12">
                  <div class="section-container">
                    <h3><i class="fa fa-money"></i> Data Gaji & Keuangan</h3>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Gaji Pokok</label>
                          <input type="text" class="form-control" name="gapok" onkeypress="return hanyaAngka(event)" value="<?php echo $data->gapok ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tunjangan Jabatan</label>
                          <input type="text" class="form-control" name="t_jabatan" onkeypress="return hanyaAngka(event)" value="<?php echo $data->t_jabatan ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tunjangan Masa Kerja</label>
                          <input type="text" class="form-control" name="t_prestasi" onkeypress="return hanyaAngka(event)" value="<?php echo $data->t_prestasi ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tunjangan Jenis Pekerjaan</label>
                          <input type="text" class="form-control" name="t_jen_pek" onkeypress="return hanyaAngka(event)" value="<?php echo $data->t_jen_pek ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">LSPMI</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="lspmi" value="Ya" <?php echo ($data->lspmi == 'Ya') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="lspmi" value="Tidak" <?php echo ($data->lspmi == 'Tidak') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Tunjangan Pensiun</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="pensiun" value="Ya" <?php echo ($data->pensiun == 'Ya') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="pensiun" value="Tidak" <?php echo ($data->pensiun == 'Tidak') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">BPJS Kesehatan (Perusahaan)</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="cek_bpjs_kes" value="1" <?php echo ($data->cek_bpjs_kes == '1') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="cek_bpjs_kes" value="0" <?php echo ($data->cek_bpjs_kes == '0') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Jemputan</label>
                          <div class="radio-group">
                            <label class="radio-inline">
                              <input type="radio" name="ljemputan" value="Ya" <?php echo ($data->ljemputan == 'Ya') ? 'checked' : '' ?>> Ya
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="ljemputan" value="Tidak" <?php echo ($data->ljemputan == 'Tidak') ? 'checked' : '' ?>> Tidak
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Akun Bank</label>
                          <input type="text" class="form-control" name="acc_bank" value="<?php echo $data->acc_bank ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Nama Bank *</label>
                          <input type="text" class="form-control" name="nama_bank" value="<?php echo $data->nama_bank ?>" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Recid Atasan</label>
                          <input type="text" class="form-control" name="recid_atasan" value="<?php echo $data->recid_atasan ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">HK Before</label>
                          <input type="text" class="form-control" name="hk_before" value="<?php echo $data->hk_before ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Covid Username</label>
                          <input type="text" class="form-control" name="covid_uname" value="<?php echo $data->covid_uname ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Covid Password</label>
                          <input type="text" class="form-control" name="covid_pwd" value="<?php echo $data->covid_pwd ?>">
                        </div>
                        <div class="form-group">
                          <label class="control-label">Covid Role</label>
                          <input type="text" class="form-control" name="covid_role" value="<?php echo $data->covid_role ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>

              <!-- Tombol Submit -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-footer">
                    <div class="pull-right">
                      <a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta" class="btn btn-default">
                        <i class="fa fa-times"></i> Batal
                      </a>
                      <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan Perubahan
                      </button>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Styling Umum */
.section-container {
  background: #fff;
  border: 1px solid #e4e4e4;
  border-radius: 5px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-container h3 {
  color: #2A3F54;
  border-bottom: 2px solid #1ABB9C;
  padding-bottom: 10px;
  margin-bottom: 20px;
  font-size: 16px;
}

.profile-section {
  background: #f9f9f9;
  padding: 20px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.profile-image-container {
  text-align: center;
  padding: 15px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.profile-img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 5px;
}

.image-caption {
  margin-top: 10px;
  padding: 5px;
  background: #f8f9fa;
  border-radius: 3px;
}

.document-group {
  border: 1px solid #e4e4e4;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 15px;
  background: #f9f9f9;
}

.document-group h4 {
  color: #555;
  font-size: 14px;
  margin-bottom: 15px;
  padding-bottom: 8px;
  border-bottom: 1px solid #e4e4e4;
}

/* Radio Group Styling */
.radio-group {
  margin-top: 5px;
}

.radio-group.vertical .radio-item {
  display: block;
  margin-bottom: 5px;
}

.radio-group .radio-inline {
  margin-right: 15px;
}

.radio-group .radio-inline input[type="radio"] {
  margin-right: 5px;
}

/* Form Controls */
.form-control {
  border-radius: 4px;
  border: 1px solid #ccc;
  padding: 6px 12px;
  height: 34px;
}

.form-control:focus {
  border-color: #66afe9;
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
}

textarea.form-control {
  min-height: 80px;
  resize: vertical;
}

/* Label */
.control-label {
  font-weight: 600;
  color: #555;
  margin-bottom: 5px;
  font-size: 13px;
}

/* Datepicker */
.datepicker {
  width: 100%;
}

.datepicker .input-group-addon {
  background: #fff;
  border-left: 0;
  cursor: pointer;
}

/* File Upload */
.form-control[type="file"] {
  padding: 5px;
  height: auto;
}

/* Tombol */
.btn-back {
  color: #337ab7;
  text-decoration: none;
  margin-right: 15px;
}

.btn-back:hover {
  color: #23527c;
  text-decoration: none;
}

.btn-link {
  color: #337ab7;
  text-decoration: none;
}

.btn-link:hover {
  color: #23527c;
  text-decoration: underline;
}

.form-footer {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e4e4e4;
}

/* Required Field */
.required-field::after {
  content: " *";
  color: #e74c3c;
}

/* Text Muted */
.text-muted {
  font-size: 12px;
  color: #777;
}

/* Responsive */
@media (max-width: 768px) {
  .section-container {
    padding: 15px;
  }
  
  .profile-img {
    width: 120px;
    height: 120px;
  }
  
  .radio-group .radio-inline {
    display: block;
    margin-bottom: 5px;
    margin-right: 0;
  }
}

/* Hover Effects */
.section-container:hover {
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.document-group:hover {
  background: #f5f5f5;
}

/* Icon Styling */
.fa {
  margin-right: 5px;
}

/* Success Button */
.btn-success {
  background: #1ABB9C;
  border-color: #17a08c;
}

.btn-success:hover {
  background: #17a08c;
  border-color: #148f77;
}

/* Default Button */
.btn-default {
  background: #fff;
  border-color: #ccc;
  color: #333;
}

.btn-default:hover {
  background: #e6e6e6;
  border-color: #adadad;
}
</style>

<script>
$(document).ready(function() {
  // Sync nama sub bagian (kolom karyawan.sub_bagian) dari dropdown recid_subbag
  function syncSubBagianLabel() {
    var label = $('#recid_subbag option:selected').text();
    if (label && label !== '- Pilih Sub Bagian -') {
      $('#sub_bagian').val(label);
    }
  }
  syncSubBagianLabel();
  $('#recid_subbag').on('change', syncSubBagianLabel);

  // Initialize datepicker
  $('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
  });
  
  // Number formatting for salary fields
  $('input[name="gapok"], input[name="t_jabatan"], input[name="t_prestasi"], input[name="t_jen_pek"]').on('keyup', function() {
    var value = $(this).val().replace(/[^\d]/g, '');
    if (value) {
      value = parseInt(value).toLocaleString('id-ID');
      $(this).val(value);
    }
  });
  
  // Restrict numeric input
  function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  }
});
</script>
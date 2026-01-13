<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Data Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><a href="<?php echo base_url() ?>Karyawan/karyawan_viewbeta"><i class="fa fa-arrow-circle-o-left"></i></a> | Personal Info</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>Karyawan/karyawan_pinsert" class="form-horizontal form-label-left" id="demo-form" novalidate data-parsley-validate>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Karyawan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="nama_karyawan" placeholder="Nama Lengkap" required="required" type="text">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tmp">Tempat Lahir <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tmp_lahir" name="tmp_lahir" class="form-control col-md-7 col-xs-12" placeholder="Tempat Lahir">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Lahir <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker2'>
                    <input type='text' class="form-control" name="tgl_lahir" placeholder="thn-bln-tgl" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl">Tanggal Masuk Trisula
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='myDatepicker3'>
                    <input type='text' class="form-control" name="tgl_trisula" placeholder="thn-bln-tgl" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenkel">Jenis Kelamin <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="jenkel" value="Laki - laki" checked /> Laki - laki
                  <input type="radio" class="flat" name="jenkel" value="Perempuan" /> Perempuan
                  <input type="radio" class="flat" name="jenkel" value="Pria" /> Pria
                  <input type="radio" class="flat" name="jenkel" value="Wanita" /> Wanita
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="goldar">Golongan Darah <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="gol_darah" value="A" checked /> A
                  <input type="radio" class="flat" name="gol_darah" value="B" /> B
                  <input type="radio" class="flat" name="gol_darah" value="O" /> O
                  <input type="radio" class="flat" name="gol_darah" value="AB" /> AB
                  <input type="radio" class="flat" name="gol_darah" value="-" /> -
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KTP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_ktp" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Nomor KTP">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_ktp">Scan KTP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_ktp" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor KK <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_kk" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Nomor KK">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_ktp">Scan KK
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_kk" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <!-- Data Keluarga (sesuai tabel) -->
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_orang_tua">Nama Orang Tua
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nama_orang_tua" class="form-control col-md-7 col-xs-12" placeholder="Nama Orang Tua">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_pasangan">Nama Pasangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nama_pasangan" class="form-control col-md-7 col-xs-12" placeholder="Nama Pasangan">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_anak">Jumlah Anak
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" name="jumlah_anak" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Anak" min="0">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_anak">Nama Anak
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea name="nama_anak" class="form-control col-md-7 col-xs-12" placeholder="Nama Anak (pisahkan dengan koma)"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cek_npwp">NPWP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="cek_npwp" value="1" checked /> Ya
                  <input type="radio" class="flat" name="cek_npwp" value="0" /> Tidak
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ktp">Nomor NPWP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="text" type="text" name="no_npwp" class="form-control col-md-7 col-xs-12" placeholder="Nomor NPWP">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_npwp">Scan NPWP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_npwp" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Agama <span style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="agama" class="form-control col-md-7 col-xs-12">
                    <option value="">-- Pilih --</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Budha">Budha</option>
                    <option value="Protestan">Protestan</option>
                    <option value="Katolik">Katolik</option>
                  </select>
                </div>
              </div>
              
              <div class="item form-group">
                <label for="agama" class="control-label col-md-3">Pendidikan <span class="required" style="color: red">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="pendidikan" class="form-control col-md-7 col-xs-12">
                    <option value="">-- Pilih --</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                    <option value="D3">D3</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                  </select>
                </div>
              </div>
              
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Jurusan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="jurusan" type="text" name="jurusan" class="form-control col-md-7 col-xs-12" placeholder="Jurusan">
                </div>
              </div>
              
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Lulus</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="thn_lulus" type="number" name="thn_lulus" class="form-control col-md-7 col-xs-12" placeholder="Tahun Lulus">
                </div>
              </div>
              
              <div class="item form-group">
                <label for="jurusan" class="control-label col-md-3 col-sm-3 col-xs-12">Ijazah</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="ijazah" type="file" name="scan_ijazah" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK Karyawan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="nik" class="form-control col-md-7 col-xs-12" placeholder="NIK Karyawan">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pin">PIN
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="pin" class="form-control col-md-7 col-xs-12" placeholder="PIN">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tgl_m_kerja">Tanggal Mulai Kerja
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class='input-group date' id='datepicker_kerja'>
                    <input type='text' class="form-control" name="tgl_m_kerja" placeholder="thn-bln-tgl" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_jamsos">Nomor Jamsostek
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_jamsos" class="form-control col-md-7 col-xs-12" placeholder="Nomor Jamsostek">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cek_bpjs_kes">BPJS Kesehatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="cek_bpjs_kes" value="1" checked /> Ya
                  <input type="radio" class="flat" name="cek_bpjs_kes" value="0" /> Tidak
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_bkes">Nomor BPJS Kesehatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_bpjs_kes" class="form-control col-md-7 col-xs-12" placeholder="Nomor BPJS Kesehatan">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Scan BPJS Kesehatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_bpjs_kes" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cek_no_bpjs_tk">BPJS Tenaga Kerja
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="cek_no_bpjs_tk" value="1" checked /> Ya
                  <input type="radio" class="flat" name="cek_no_bpjs_tk" value="0" /> Tidak
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_btk">Nomor BPJS Tenaga Kerja
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_bpjs_tk" class="form-control col-md-7 col-xs-12" placeholder="Nomor BPJS Tenaga Kerja">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs_tk">Scan BPJS Tenaga Kerja
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_bpjs_tk" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_kartu_trimas">Nomor Kartu Trimas
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_kartu_trimas" class="form-control col-md-7 col-xs-12" placeholder="Nomor Kartu Trimas">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_aia">Nomor AIA
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="number" type="text" name="no_aia" class="form-control col-md-7 col-xs-12" placeholder="Nomor AIA">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_aia">Scan AIA
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_aia" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sim1">SIM 1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="sim1" class="form-control col-md-7 col-xs-12" placeholder="SIM 1" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_sim1">Scan SIM 1
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_sim1" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sim2">SIM 2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="sim2" class="form-control col-md-7 col-xs-12" placeholder="SIM 2" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_sim2">Scan SIM 2
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="scan_sim2" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_nikah">Status Perkawinan <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="sts_nikah" value="Belum Kawin" checked /> Belum Kawin <br>
                  <input type="radio" class="flat" name="sts_nikah" value="Kawin" /> Kawin <br>
                  <input type="radio" class="flat" name="sts_nikah" value="Janda" /> Janda <br>
                  <input type="radio" class="flat" name="sts_nikah" value="Duda" /> Duda <br>
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_penunjang">Status Penunjang <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="sts_penunjang" value="TK" checked /> TK (Tidak Kawin)<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K0" /> K0 (Kawin Anak 0)<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K1" /> K1 (Kawin Anak 1)<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K2" /> K2 (Kawin Anak 2)<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="K3" /> K3 (Kawin Anak 3)<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="TK1" /> TK1<br>
                  <input type="radio" class="flat" name="sts_penunjang" value="TK2" /> TK2<br>
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_ktp">Alamat KTP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="alamat_ktp" class="form-control col-md-7 col-xs-12" placeholder="Alamat KTP"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota_ktp">Kota KTP <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="kota_ktp" class="form-control col-md-7 col-xs-12" placeholder="Kota KTP">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat_skrg">Alamat Sekarang <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="alamat_skrg" class="form-control col-md-7 col-xs-12" placeholder="Alamat Sekarang"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota_skrg">Kota Sekarang <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="kota_skrg" class="form-control col-md-7 col-xs-12" placeholder="Kota Sekarang">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon 1 <span class="required" style="color: red">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="telp1" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="No Telp Pribadi" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Telpon 2 (Alternatif)
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="telp2" class="form-control col-md-7 col-xs-12" onkeypress="return hanyaAngka(event)" placeholder="No Telp Alternatif" type="text">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hobi">Hobi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="textarea" name="hobi" class="form-control col-md-7 col-xs-12" placeholder="Hobi"></textarea>
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Email">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email_tsgi">Email Tsgi
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="email_tsgi" class="form-control col-md-7 col-xs-12" placeholder="Email Tsgi">
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="scan_bpjs">Foto
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="file" name="foto" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <!-- Data Tambahan sesuai tabel -->
              <div class="x_title">
                <h2>Data Tambahan</h2>
                <div class="clearfix"></div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sts_aktif">Status Aktif
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="sts_aktif" class="form-control col-md-7 col-xs-12">
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Resign">Resign</option>
                    <option value="Pensiun">Pensiun</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                  </select>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipe_ptkp">Tipe PTKP
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="tipe_ptkp" class="form-control col-md-7 col-xs-12" placeholder="Tipe PTKP">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="efin">EFIN
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="efin" class="form-control col-md-7 col-xs-12" placeholder="EFIN">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penyakit">Riwayat Penyakit
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea name="penyakit" class="form-control col-md-7 col-xs-12" placeholder="Riwayat Penyakit"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kat_penyakit">Kategori Penyakit
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="kat_penyakit" class="form-control col-md-7 col-xs-12">
                    <option value="">-- Pilih --</option>
                    <option value="Ringan">Ringan</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Berat">Berat</option>
                  </select>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vaksin_covid">Vaksin COVID
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="radio" class="flat" name="vaksin_covid" value="1" /> Sudah
                  <input type="radio" class="flat" name="vaksin_covid" value="0" checked /> Belum
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ukuran_baju">Ukuran Baju
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="ukuran_baju" class="form-control col-md-7 col-xs-12" placeholder="Ukuran Baju">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ukuran_celana">Ukuran Celana
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="ukuran_celana" class="form-control col-md-7 col-xs-12" placeholder="Ukuran Celana">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ukuran_sepatu">Ukuran Sepatu
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="ukuran_sepatu" class="form-control col-md-7 col-xs-12" placeholder="Ukuran Sepatu">
                </div>
              </div>

              <?php if ($role == '2' or $role == '5') { ?>
                <div class="x_title">
                  <h2>Data Pendukung Upah</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lspmi">SPMI
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="lspmi" value="Ya" /> Ya
                    <input type="radio" class="flat" name="lspmi" value="Tidak" checked /> Tidak
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pensiun">Jaminan Pensiun
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="pensiun" value="Ya" /> Ya
                    <input type="radio" class="flat" name="pensiun" value="Tidak" checked /> Tidak
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gapok">Gaji Pokok
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="gapok" type="text" name="gapok" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Gaji Pokok">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jabatan">Tunjangan Jabatan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_jabatan" type="text" name="t_jabatan" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jabatan">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_prestasi">Tunjangan Masa Kerja
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_prestasi" type="text" name="t_prestasi" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Masa Kerja">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jen_pek">Tunjangan Jenis Pekerjaan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="t_jenk_pek" type="text" name="t_jen_pek" onkeypress="return hanyaAngka(event)" class="form-control col-md-7 col-xs-12" placeholder="Tunjangan Jenis Pekerjaan">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ljemputan">Jemputan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="ljemputan" value="Ya" checked /> Ya
                    <input type="radio" class="flat" name="ljemputan" value="Tidak" /> Tidak
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="acc_bank">Akun Bank
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="acc_bank" name="acc_bank" class="form-control col-md-7 col-xs-12" placeholder="Akun Bank">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_bank">Nama Bank
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="nama_bank" name="nama_bank" class="form-control col-md-7 col-xs-12" placeholder="Nama Bank">
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bulanan">Bulanan
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="bulanan" value="Ya" checked /> Ya
                    <input type="radio" class="flat" name="bulanan" value="Tidak" /> Tidak
                  </div>
                </div>
                
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kontrak">Kontrak
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="radio" class="flat" name="kontrak" value="Ya" /> Ya
                    <input type="radio" class="flat" name="kontrak" value="Tidak" checked /> Tidak
                  </div>
                </div>
              <?php } ?>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="note">Catatan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea name="note" class="form-control col-md-7 col-xs-12" placeholder="Catatan"></textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea name="keterangan" class="form-control col-md-7 col-xs-12" placeholder="Keterangan"></textarea>
                </div>
              </div>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <a href='<?php echo base_url() ?>Karyawan/karyawan_viewbeta'><input type="button" class="btn btn-primary" value="Cancel"></button></a>
                  <button id="send" type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Datepicker initialization
  $('#myDatepicker2').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  
  $('#myDatepicker3').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  
  $('#datepicker_kerja').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  
  // Fungsi hanya angka
  function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  }
  
  // SPM toggle
  $('input[name="spm"]').change(function() {
    if ($(this).val() === 'Ya') {
      $('#tmp_toko').show();
      $('#tmp_kota').show();
    } else {
      $('#tmp_toko').hide();
      $('#tmp_kota').hide();
    }
  });
});
</script>
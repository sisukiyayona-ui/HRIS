<?php $role = $this->session->userdata('role_id'); ?>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Histori Karir Karyawan</h3>
      </div>
    </div>


    <?php foreach ($karir as $data) {
      $nama = $data->nama_karyawan;
      $nama = strtolower($nama);
      $nama = ucfirst($nama);
    } ?>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <h3>Histori Karir <?php echo $nama ?></h3>
            <ul class="list-unstyled timeline">
              <a href="<?php echo base_url() ?>Down_ms/download_karir_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Karir)</a><br>
              <?php foreach ($karir as $data) { ?>
                <li>
                  <div class="block">
                    <div class="tags">
                      <a href="#" class="tag">
                        <span><?php echo $data->kategori ?></span>
                      </a>
                    </div>
                    <div class="block_content">
                      <h2 class="title">
                        <p>
                          <?php if ($data->kategori == 'Akhir') {
                            echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                          } else { ?>
                            <?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d
                            <?php if ($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00') {
                              echo "Sekarang";
                            } else {
                              echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));
                            } ?> |
                          <?php } ?>
                          <?php if ($role == '1' or $role == '2' or $role = '5') { ?>
                            <?php if ($data->no_perjanjian == '') { ?>
                              <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><i class="fa fa-trash"></i></a>
                            <?php  } else { ?>
                              <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>/<?php echo $data->recid_karyawan ?>"><i class="fa fa-trash"></i></a>

                            <?php } ?>
                            <a class="btn btn-primary btn-xs" href="<?php echo base_url() ?>index.php/Karir/set_current/<?php echo $data->recid_karir ?>">Set as Current</a>
                          <?php } ?>
                        </p>
                      </h2>
                      <div class="byline">
                        <?php if ($data->no_perjanjian == '') {
                          echo "Tidak Ada no SK";
                        } else {
                          if ($data->scan_perjanjian != "") { ?>
                            <a href="<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php echo $data->no_perjanjian; ?>
                            </a>
                          <?php } else {
                            echo $data->no_perjanjian;
                          } ?>
                        <?php } ?>

                        <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                          | <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                        <?php } ?>
                      </div>
                      <p class="excerpt">
                      <table class="table">
                        <tr>
                          <td>Jenis Karir</td>
                          <td>:</td>
                          <td><?php echo $data->kategori ?></td>
                        </tr>
                        <tr>
                          <td>Bagian</td>
                          <td>:</td>
                          <td><?php echo $data->indeks_hr ?></td>
                        </tr>
                        <tr>
                          <td>Sub Bagian</td>
                          <td>:</td>
                          <td><?php if ($data->recid_subbag != 0) {
                                echo $data->sub_bag;
                              } else {
                                echo "-";
                              }  ?></td>
                        </tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><?php echo  strtoupper($data->indeks_jabatan) ?>
                          <!-- - <?php echo  strtoupper($data->sts_jbtn) ?> --> (<?php echo $data->sts_jabatan ?>)
                        </td>
                        </tr>
                        <tr>
                          <td>Golongan</td>
                          <td>:</td>
                          <td><?php echo $data->nama_golongan ?></td>
                        </tr>
                        <tr>
                          <td>Penempatan</td>
                          <td>:</td>
                          <td><?php echo $data->penempatan ?></td>
                        </tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td><?php echo "$data->note"; ?></td>
                        </tr>
                      </table>
                      </p>
                    </div>
                  </div>
                </li>
              <?php } ?>
            </ul>
            <br>
            <br>
            <h3>Histori Sanksi <?php echo $nama ?></h3>
            <ul class="list-unstyled timeline">
              <a href="<?php echo base_url() ?>Down_ms/download_sanksi_ms/<?php echo $data->recid_karyawan ?>" target="__blank"> (<span><i class="fa fa-download"></i></span> Download Sanksi)</a><br>
              <?php foreach ($sanksi as $data) { ?>
                <li>
                  <div class="block">
                    <div class="tags">
                      <a href="#" class="tag">
                        <span><?php echo $data->kategori ?></span>
                      </a>
                    </div>
                    <div class="block_content">
                      <h2 class="title">
                        <p>
                          <?php if ($data->kategori == 'Akhir') {
                            echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir));
                          } else { ?>
                            <?php echo $newDate = date("d-M-Y", strtotime($data->tgl_m_karir)); ?> s/d
                            <?php if ($data->tgl_a_karir == '' || $data->tgl_a_karir == '0000-00-00') {
                              echo "Sekarang";
                            } else {
                              echo $newDate = date("d-M-Y", strtotime($data->tgl_a_karir));
                            } ?> |
                          <?php } ?>
                          <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                            <?php if ($data->no_perjanjian == '') { ?>
                              <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>"><i class="fa fa-trash"></i></a>
                            <?php  } else { ?>
                              <a class="btn btn-info btn-xs" href="<?php echo base_url() ?>Karyawan/karir_update/<?php echo $data->recid_karir ?>"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-danger btn-xs" href="<?php echo base_url() ?>Karyawan/karir_delete/<?php echo $data->recid_legal ?>/<?php echo $data->recid_karyawan ?>"><i class="fa fa-trash"></i></a>

                            <?php } ?>
                            <!-- <a class="btn btn-primary btn-xs" href="<?php echo base_url() ?>Karir/set_current/<?php echo $data->recid_karir ?>">Set as Current</a> -->
                          <?php } ?>
                        </p>
                      </h2>
                      <div class="byline">
                        <?php if ($data->no_perjanjian == '') {
                          echo "Tidak Ada no SK";
                        } else {
                          if ($data->scan_perjanjian != "") { ?>
                            <a href="<?php echo base_url() ?>images/legal/<?php echo $data->scan_perjanjian; ?>" target="__blank"><?php echo $data->no_perjanjian; ?>
                            </a>
                          <?php } else {
                            echo $data->no_perjanjian;
                          } ?>
                        <?php } ?>

                        <?php if ($role == '1' or $role == '2' or $role == '5') { ?>
                          | <a href="<?php echo base_url() ?>Karyawan/legal_update/<?php echo $data->recid_legal ?>"><i class="fa fa-edit"></i></a>
                        <?php } ?>
                      </div>
                      <p class="excerpt">
                      <table class="table">
                        <tr>
                          <td>Jenis Sanksi</td>
                          <td>:</td>
                          <td><?php echo $data->jenis_sanksi ?></td>
                        </tr>
                        <tr>
                          <td>Bagian</td>
                          <td>:</td>
                          <td><?php echo $data->indeks_hr ?></td>
                        </tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><?php echo  strtoupper($data->indeks_jabatan) ?>
                          <!-- - <?php echo  strtoupper($data->sts_jbtn) ?> --> (<?php echo $data->sts_jabatan ?>)
                        </td>
                        </tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td><?php echo "$data->note"; ?></td>
                        </tr>
                      </table>
                      </p>
                    </div>
                  </div>
                </li>
              <?php } ?>
            </ul>

          </div>
        </div>
      </div>
    </div>
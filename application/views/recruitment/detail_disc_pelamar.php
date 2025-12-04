<!-- page content -->
<?php $role = $this->session->userdata('role_id'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Detail DISC Candidates</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                </div>
              </div>
            </div>

            <div class="clearfix"></div> 
           
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="col">
                      <div class="card shadow">
                        <div class="card-header border-0">
                          <h3 class="mb-0">Profile</h3>
                        </div>
                        <?php 
                            foreach ($profil->result() as $p) {
                                # code...
                            }
                        ?>
                        <table class="table">
                            <tr><td>Nama Karyawan</td><td>: <?php echo $p->nama_pelamar ?></td></tr>
                            <tr><td>Jenis Kelamin</td><td>: <?php echo $p->pjenkel ?></td></tr>
                            <tr><td>Tempat / Tanggal Lahir</td><td>: <?php echo $p->ptmp_lahir ?>, <?php echo date("d M Y", strtotime($p->tgl_lahir)); ?></td></tr>
                            <tr><td>Tanggal Test</td><td>: <?php echo date("d M Y", strtotime($tgl_test)); ?></td></tr>
                        </table>
                        <div class="card-footer py-4">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>

              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="col">
                      <div class="card shadow">
                        <div class="card-header border-0">
                          <h3 class="mb-0">Result Test</h3>
                        </div>
                        <div class="card-body">
                                <div class="accordion-1 mt--5">
                                <div class="container">
                                    <div class="row p-3">
                                        <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th><center>Opsi</center></th>
                                                <th><center>Tinggi</center></th>
                                                <th><center>Rendah</center></th>
                                                <th><center>(T)-(R)</center></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span>▲</span></td>
                                                <td><?php echo $tinggi_s; ?></td>
                                                <td><?php echo $rendah_s; ?></td>
                                                <td><?php echo  $tinggi_s - $rendah_s; ?></td>
                                                </tr>
                                                <tr>
                                                <td><span><i class='fa fa-square'></i></span></td>
                                                <td><?php echo $tinggi_k; ?></td>
                                                <td><?php echo $rendah_k; ?></td>
                                                <td><?php echo  $tinggi_k - $rendah_k; ?></td>
                                                </tr><tr>
                                                <td><span><i class='fa fa-star'></i></span></td>
                                                <td><?php echo $tinggi_b; ?></td>
                                                <td><?php echo $rendah_b; ?></td>
                                                <td><?php echo  $tinggi_b - $rendah_b; ?></td>
                                                </tr><tr>
                                                <td>Z</td>
                                                <td><?php echo $tinggi_z; ?></td>
                                                <td><?php echo $rendah_z; ?></td>
                                                <td><?php echo  $tinggi_z - $rendah_z; ?></td>
                                                </tr><tr>
                                                <td>N</td>
                                                <td><?php echo $tinggi_n; ?></td>
                                                <td><?php echo $rendah_n; ?></td>
                                                <td><?php echo  $tinggi_n - $rendah_n; ?></td>
                                                </tr><tr>
                                                <td>Total</td>
                                                <td><?php echo $total_rendah; ?></td>
                                                <td><?php echo $total_tinggi; ?></td>
                                                <td><?php echo  $total_tinggi - $total_rendah; ?></td>
                                                </tr>
                                            </tbody> 
                                        </table> 
                            </div>

                                    <div class="row mt-5">
                                        <h3 class="ml-3">Jawaban Soal DISC</h3><br>
                                    <div class="col-md-12 ml-auto mb-5">
                                      
                            <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                       <?php 
                      for($i=1; $i<=24; $i++)
                      {?>
                      <div class="panel">
                        <a class="panel-heading" role="tab" id="heading<?php echo $i ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>" aria-expanded="false" aria-controls="collapse<?php echo $i ?>">
                          <h4 class="panel-title"> #Soal No <?php echo $i ?></h4>
                        </a>
                        <div id="collapse<?php echo $i ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i ?>">
                          <div class="panel-body">
                            <table class="table table-striped">
                            <tr><td></td><td>Tinggi</td><td>Rendah</td></tr>
                            <?php
                                $recid_karyawan = $this->uri->segment(3);
                                $soal = $this->db->query("SELECT * from disc.soal where no_soal = $i");
                                $jawaban = $this->db->query("SELECT * from disc.disc where no_soal = $i and recid_karyawan = $recid_karyawan");
                                foreach ($jawaban->result() as $j) {
                                  $j_rendah = $j->rendah;
                                  $j_tinggi = $j->tinggi;
                                }
                                foreach ($soal->result() as $s) {
                                  $soal = $s->soal;
                                  if($s->rendah == 'S'){
                                  $ops = "<span>▲</span> ";
                                  }else if($s->rendah == 'B')
                                  {
                                  $ops = "<span><i class='fa fa-star'></i></span> ";
                                  }else if($s->rendah == 'K')
                                  {
                                  $ops = "<span><i class='fa fa-square'></i></span> ";
                                  }else if($s->rendah == 'N')
                                  {
                                  $ops = "<span>N</span> ";
                                  }else
                                  {
                                  $ops = "<span>Z</span> ";
                                  }
                        
                                  if($s->tinggi == 'S'){
                                  $ops2 = "<span>▲</span> ";
                                  }else if($s->tinggi == 'B')
                                  {
                                  $ops2 = "<span><i class='fa fa-star'></i></span> ";
                                  }else if($s->tinggi == 'K')
                                  {
                                  $ops2 = "<span><i class='fa fa-square'></i></span> ";
                                  }else if($s->tinggi == 'N')
                                  {
                                  $ops2 = "<span>N</span> ";
                                  }else
                                  {
                                  $ops2 = "<span>Z</span> ";
                                  }
                                ?>
                                  <tr>
                                  <td><?php echo  $soal ?> <input type="hidden" name="no_soal<?php echo $s->no_soal ?>" value="<?php echo $s->no_soal?>"></td>
                                  <td>
                                    <?php 
                                      if($s->tinggi == $j_tinggi)
                                      {?>
                                        <input type="radio" name="tinggi<?php echo $i?>" value="<?php echo $s->tinggi; ?>" checked disabled> <?php echo $ops2; ?>
                                      <?php }else{ ?>
                                        <input type="radio" name="tinggi<?php echo $i?>" value="<?php echo $s->tinggi; ?>" disabled > <?php echo $ops2; ?>
                                      <?php }?>
                                  </td>
                                  <td>
                                  <?php 
                                      if($s->rendah == $j_rendah)
                                      {?>
                                        <input type="radio" value="<?php echo $s->rendah; ?>" checked disabled > <?php echo $ops; ?>
                                      <?php }else{ ?>
                                        <input type="radio" value="<?php echo $s->rendah; ?>" disabled > <?php echo $ops; ?>
                                      <?php }?>
                                  </td>
                                  </tr>
                                <?php }
                                ?>
                                </table>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <!-- end of accordion -->
                                      
                        <div class="card-footer py-4">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>




                </div>
                </div>


           
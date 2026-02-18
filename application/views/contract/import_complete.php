<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <!-- Display flash messages -->
    <?php if($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="page-title">
      <div class="title_left">
        <h3>Hasil Import Kontrak Karyawan</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Ringkasan Import Kontrak Karyawan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php if (empty($import_summary)): ?>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <strong>Error!</strong> No import results found.
              </div>
            <?php else: ?>
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-database text-info"></i></div>
                  <div class="count"><?php echo isset($import_summary['total_records']) ? $import_summary['total_records'] : 0; ?></div>
                  <h3>Total Baris Data</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check text-success"></i></div>
                  <div class="count"><?php echo isset($import_summary['successful_imports']) ? $import_summary['successful_imports'] : 0; ?></div>
                  <h3>Proses Berhasil</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-plus-circle text-primary"></i></div>
                  <div class="count"><?php echo isset($import_summary['inserted_records']) ? $import_summary['inserted_records'] : 0; ?></div>
                  <h3>Data Ditambahkan</h3>
                </div>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-refresh text-warning"></i></div>
                  <div class="count"><?php echo isset($import_summary['updated_records']) ? $import_summary['updated_records'] : 0; ?></div>
                  <h3>Data Diperbarui</h3>
                </div>
              </div>
            </div>

            <?php if (isset($import_summary['failed_imports']) && $import_summary['failed_imports'] > 0): ?>
              <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4><i class="icon fa fa-warning"></i> Sebagian Data Gagal Diimport</h4>
                <p><?php echo isset($import_summary['failed_imports']) ? $import_summary['failed_imports'] : 0; ?> data kontrak tidak dapat diimport. Silakan lihat detail di bawah.</p>
              </div>
            <?php else: ?>
              <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4><i class="icon fa fa-check"></i> Import Berhasil</h4>
                <p>Semua <?php echo isset($import_summary['successful_imports']) ? $import_summary['successful_imports'] : 0; ?> data kontrak diproses dengan sukses.</p>
                <p>Data Ditambahkan: <?php echo isset($import_summary['inserted_records']) ? $import_summary['inserted_records'] : 0; ?> | Data Diperbarui: <?php echo isset($import_summary['updated_records']) ? $import_summary['updated_records'] : 0; ?></p>
              </div>
            <?php endif; ?>

            <!-- Inserted Records Section -->
            <?php if (isset($import_summary['inserted_records']) && $import_summary['inserted_records'] > 0): ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2><i class="fa fa-plus-circle text-primary"></i> Data Ditambahkan (<?php echo isset($import_summary['inserted_records']) ? $import_summary['inserted_records'] : 0; ?>)</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Akhir</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        // Pagination for inserted records
                        $inserted_records = isset($import_summary['inserted_details']) ? $import_summary['inserted_details'] : [];
                        $inserted_total = count($inserted_records);
                        $inserted_per_page = 10;
                        $inserted_current_page = isset($_GET['inserted_page']) ? (int)$_GET['inserted_page'] : 1;
                        $inserted_offset = ($inserted_current_page - 1) * $inserted_per_page;
                        $inserted_paginated = array_slice($inserted_records, $inserted_offset, $inserted_per_page);
                        ?>
                        
                        <?php foreach ($inserted_paginated as $record): ?>
                          <tr>
                            <td><?php echo isset($record['NIK']) ? htmlspecialchars($record['NIK']) : ''; ?></td>
                            <td><?php echo isset($record['NAMA']) ? htmlspecialchars($record['NAMA']) : ''; ?></td>
                            <td><?php echo isset($record['tgl_mulai']) ? htmlspecialchars($record['tgl_mulai']) : ''; ?></td>
                            <td><?php echo isset($record['tgl_akhir']) ? htmlspecialchars($record['tgl_akhir']) : ''; ?></td>
                            <td><span class="label label-success">DITAMBAHKAN</span></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                    
                    <!-- Improved Pagination for inserted records -->
                    <?php if ($inserted_total > $inserted_per_page): ?>
                      <div class="text-center">
                        <ul class="pagination">
                          <?php 
                          $inserted_total_pages = ceil($inserted_total / $inserted_per_page);
                          $inserted_visible_pages = 5; // Number of visible page links
                          $inserted_start_page = max(1, $inserted_current_page - floor($inserted_visible_pages / 2));
                          $inserted_end_page = min($inserted_total_pages, $inserted_start_page + $inserted_visible_pages - 1);
                          
                          // Adjust start page if needed
                          if ($inserted_end_page - $inserted_start_page + 1 < $inserted_visible_pages) {
                              $inserted_start_page = max(1, $inserted_end_page - $inserted_visible_pages + 1);
                          }
                          
                          // Build base URL with existing parameters
                          $base_url = base_url('Contract_import/paginate_results');
                          $url_params = array();
                          if (isset($_GET['updated_page'])) {
                              $url_params['updated_page'] = $_GET['updated_page'];
                          }
                          if (isset($_GET['failed_page'])) {
                              $url_params['failed_page'] = $_GET['failed_page'];
                          }
                          
                          // Previous button
                          if ($inserted_current_page > 1): 
                              $prev_params = $url_params;
                              $prev_params['inserted_page'] = $inserted_current_page - 1;
                              $prev_url = $base_url . '?' . http_build_query($prev_params);
                              ?>
                            <li><a href="<?php echo $prev_url; ?>">&laquo;</a></li>
                          <?php endif; ?>
                          
                          <!-- First page link -->
                          <?php if ($inserted_start_page > 1): 
                              $first_params = $url_params;
                              $first_params['inserted_page'] = 1;
                              $first_url = $base_url . '?' . http_build_query($first_params);
                              ?>
                            <li><a href="<?php echo $first_url; ?>">1</a></li>
                            <?php if ($inserted_start_page > 2): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                          <?php endif; ?>
                          
                          <!-- Page links -->
                          <?php for ($i = $inserted_start_page; $i <= $inserted_end_page; $i++): 
                              $page_params = $url_params;
                              $page_params['inserted_page'] = $i;
                              $page_url = $base_url . '?' . http_build_query($page_params);
                              ?>
                            <li class="<?php echo $i == $inserted_current_page ? 'active' : ''; ?>">
                              <a href="<?php echo $page_url; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endfor; ?>
                          
                          <!-- Last page link -->
                          <?php if ($inserted_end_page < $inserted_total_pages): 
                              $last_params = $url_params;
                              $last_params['inserted_page'] = $inserted_total_pages;
                              $last_url = $base_url . '?' . http_build_query($last_params);
                              ?>
                            <?php if ($inserted_end_page < $inserted_total_pages - 1): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                            <li><a href="<?php echo $last_url; ?>"><?php echo $inserted_total_pages; ?></a></li>
                          <?php endif; ?>
                          
                          <!-- Next button -->
                          <?php if ($inserted_current_page < $inserted_total_pages): 
                              $next_params = $url_params;
                              $next_params['inserted_page'] = $inserted_current_page + 1;
                              $next_url = $base_url . '?' . http_build_query($next_params);
                              ?>
                            <li><a href="<?php echo $next_url; ?>">&raquo;</a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <!-- Updated Records Section -->
            <?php if (isset($import_summary['updated_records']) && $import_summary['updated_records'] > 0): ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2><i class="fa fa-refresh text-warning"></i> Data Diperbarui (<?php echo isset($import_summary['updated_records']) ? $import_summary['updated_records'] : 0; ?>)</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Akhir</th>
                          <th>Info Perubahan</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        // Pagination for updated records
                        $updated_records = isset($import_summary['updated_details']) ? $import_summary['updated_details'] : [];
                        $updated_total = count($updated_records);
                        $updated_per_page = 10;
                        $updated_current_page = isset($_GET['updated_page']) ? (int)$_GET['updated_page'] : 1;
                        $updated_offset = ($updated_current_page - 1) * $updated_per_page;
                        $updated_paginated = array_slice($updated_records, $updated_offset, $updated_per_page);
                        ?>
                        
                        <?php foreach ($updated_paginated as $record): ?>
                          <tr>
                            <td><?php echo isset($record['NIK']) ? htmlspecialchars($record['NIK']) : ''; ?></td>
                            <td><?php echo isset($record['NAMA']) ? htmlspecialchars($record['NAMA']) : ''; ?></td>
                            <td><?php echo isset($record['tgl_mulai']) ? htmlspecialchars($record['tgl_mulai']) : ''; ?></td>
                            <td><?php echo isset($record['tgl_akhir']) ? htmlspecialchars($record['tgl_akhir']) : ''; ?></td>
                            <td><?php echo isset($record['message']) ? htmlspecialchars($record['message']) : ''; ?></td>
                            <td><span class="label label-warning">DIPERBARUI</span></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                    
                    <!-- Improved Pagination for updated records -->
                    <?php if ($updated_total > $updated_per_page): ?>
                      <div class="text-center">
                        <ul class="pagination">
                          <?php 
                          $updated_total_pages = ceil($updated_total / $updated_per_page);
                          $updated_visible_pages = 5; // Number of visible page links
                          $updated_start_page = max(1, $updated_current_page - floor($updated_visible_pages / 2));
                          $updated_end_page = min($updated_total_pages, $updated_start_page + $updated_visible_pages - 1);
                          
                          // Adjust start page if needed
                          if ($updated_end_page - $updated_start_page + 1 < $updated_visible_pages) {
                              $updated_start_page = max(1, $updated_end_page - $updated_visible_pages + 1);
                          }
                          
                          // Build base URL with existing parameters
                          $base_url = base_url('Contract_import/paginate_results');
                          $url_params = array();
                          if (isset($_GET['inserted_page'])) {
                              $url_params['inserted_page'] = $_GET['inserted_page'];
                          }
                          if (isset($_GET['failed_page'])) {
                              $url_params['failed_page'] = $_GET['failed_page'];
                          }
                          
                          // Previous button
                          if ($updated_current_page > 1): 
                              $prev_params = $url_params;
                              $prev_params['updated_page'] = $updated_current_page - 1;
                              $prev_url = $base_url . '?' . http_build_query($prev_params);
                              ?>
                            <li><a href="<?php echo $prev_url; ?>">&laquo;</a></li>
                          <?php endif; ?>
                          
                          <!-- First page link -->
                          <?php if ($updated_start_page > 1): 
                              $first_params = $url_params;
                              $first_params['updated_page'] = 1;
                              $first_url = $base_url . '?' . http_build_query($first_params);
                              ?>
                            <li><a href="<?php echo $first_url; ?>">1</a></li>
                            <?php if ($updated_start_page > 2): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                          <?php endif; ?>
                          
                          <!-- Page links -->
                          <?php for ($i = $updated_start_page; $i <= $updated_end_page; $i++): 
                              $page_params = $url_params;
                              $page_params['updated_page'] = $i;
                              $page_url = $base_url . '?' . http_build_query($page_params);
                              ?>
                            <li class="<?php echo $i == $updated_current_page ? 'active' : ''; ?>">
                              <a href="<?php echo $page_url; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endfor; ?>
                          
                          <!-- Last page link -->
                          <?php if ($updated_end_page < $updated_total_pages): 
                              $last_params = $url_params;
                              $last_params['updated_page'] = $updated_total_pages;
                              $last_url = $base_url . '?' . http_build_query($last_params);
                              ?>
                            <?php if ($updated_end_page < $updated_total_pages - 1): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                            <li><a href="<?php echo $last_url; ?>"><?php echo $updated_total_pages; ?></a></li>
                          <?php endif; ?>
                          
                          <!-- Next button -->
                          <?php if ($updated_current_page < $updated_total_pages): 
                              $next_params = $url_params;
                              $next_params['updated_page'] = $updated_current_page + 1;
                              $next_url = $base_url . '?' . http_build_query($next_params);
                              ?>
                            <li><a href="<?php echo $next_url; ?>">&raquo;</a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <!-- Failed Records Section -->
            <?php if (isset($import_summary['failed_imports']) && $import_summary['failed_imports'] > 0): ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2><i class="fa fa-times-circle text-danger"></i> Data Gagal Diimport (<?php echo isset($import_summary['failed_imports']) ? $import_summary['failed_imports'] : 0; ?>)</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Nama Karyawan</th>
                          <th>Pesan Error</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        // Pagination for failed records
                        $failed_records = $import_summary['errors'];
                        $failed_total = count($failed_records);
                        $failed_per_page = 10;
                        $failed_current_page = isset($_GET['failed_page']) ? (int)$_GET['failed_page'] : 1;
                        $failed_offset = ($failed_current_page - 1) * $failed_per_page;
                        $failed_paginated = array_slice($failed_records, $failed_offset, $failed_per_page);
                        ?>
                        
                        <?php foreach ($failed_paginated as $error): ?>
                          <tr>
                            <td><?php echo isset($error['NIK']) ? htmlspecialchars($error['NIK']) : ''; ?></td>
                            <td><?php echo isset($error['NAMA']) ? htmlspecialchars($error['NAMA']) : ''; ?></td>
                            <td><?php 
                                if (isset($error['message'])) {
                                    echo htmlspecialchars($error['message']);
                                } elseif (isset($error['error_message'])) {
                                    echo htmlspecialchars($error['error_message']);
                                } else {
                                    echo '';
                                }
                            ?></td>
                            <td><span class="label label-danger">GAGAL</span></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                    
                    <!-- Improved Pagination for failed records -->
                    <?php if ($failed_total > $failed_per_page): ?>
                      <div class="text-center">
                        <ul class="pagination">
                          <?php 
                          $failed_total_pages = ceil($failed_total / $failed_per_page);
                          $failed_visible_pages = 5; // Number of visible page links
                          $failed_start_page = max(1, $failed_current_page - floor($failed_visible_pages / 2));
                          $failed_end_page = min($failed_total_pages, $failed_start_page + $failed_visible_pages - 1);
                          
                          // Adjust start page if needed
                          if ($failed_end_page - $failed_start_page + 1 < $failed_visible_pages) {
                              $failed_start_page = max(1, $failed_end_page - $failed_visible_pages + 1);
                          }
                          
                          // Build base URL with existing parameters
                          $base_url = base_url('Contract_import/paginate_results');
                          $url_params = array();
                          if (isset($_GET['inserted_page'])) {
                              $url_params['inserted_page'] = $_GET['inserted_page'];
                          }
                          if (isset($_GET['updated_page'])) {
                              $url_params['updated_page'] = $_GET['updated_page'];
                          }
                          
                          // Previous button
                          if ($failed_current_page > 1): 
                              $prev_params = $url_params;
                              $prev_params['failed_page'] = $failed_current_page - 1;
                              $prev_url = $base_url . '?' . http_build_query($prev_params);
                              ?>
                            <li><a href="<?php echo $prev_url; ?>">&laquo;</a></li>
                          <?php endif; ?>
                          
                          <!-- First page link -->
                          <?php if ($failed_start_page > 1): 
                              $first_params = $url_params;
                              $first_params['failed_page'] = 1;
                              $first_url = $base_url . '?' . http_build_query($first_params);
                              ?>
                            <li><a href="<?php echo $first_url; ?>">1</a></li>
                            <?php if ($failed_start_page > 2): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                          <?php endif; ?>
                          
                          <!-- Page links -->
                          <?php for ($i = $failed_start_page; $i <= $failed_end_page; $i++): 
                              $page_params = $url_params;
                              $page_params['failed_page'] = $i;
                              $page_url = $base_url . '?' . http_build_query($page_params);
                              ?>
                            <li class="<?php echo $i == $failed_current_page ? 'active' : ''; ?>">
                              <a href="<?php echo $page_url; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endfor; ?>
                          
                          <!-- Last page link -->
                          <?php if ($failed_end_page < $failed_total_pages): 
                              $last_params = $url_params;
                              $last_params['failed_page'] = $failed_total_pages;
                              $last_url = $base_url . '?' . http_build_query($last_params);
                              ?>
                            <?php if ($failed_end_page < $failed_total_pages - 1): ?>
                              <li><span>...</span></li>
                            <?php endif; ?>
                            <li><a href="<?php echo $last_url; ?>"><?php echo $failed_total_pages; ?></a></li>
                          <?php endif; ?>
                          
                          <!-- Next button -->
                          <?php if ($failed_current_page < $failed_total_pages): 
                              $next_params = $url_params;
                              $next_params['failed_page'] = $failed_current_page + 1;
                              $next_url = $base_url . '?' . http_build_query($next_params);
                              ?>
                            <li><a href="<?php echo $next_url; ?>">&raquo;</a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="form-group">
              <a href="<?php echo base_url('Contract_import'); ?>" class="btn btn-primary">
                <i class="fa fa-upload"></i> Import File Lain
              </a>
              <a href="<?php echo base_url('Kontrak'); ?>" class="btn btn-default">
                <i class="fa fa-list"></i> Lihat Kontrak
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>
<!-- /page content -->
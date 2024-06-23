<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-file-export mr-2"></i> Laporan Nota</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Nota</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php
    // ambil data hasil submit dari form filter
    $tanggal_awal   = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : '';
    $tanggal_akhir  = isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : '';
    $status         = isset($_POST['status']) ? $_POST['status'] : '';
    $sort_by        = isset($_POST['sort_by']) ? $_POST['sort_by'] : '';
  ?>
    <div class="page-inner mt--5">
      <div class="card">
        <div class="card-header">
          <!-- judul form -->
          <div class="card-title">Filter Data Nota</div>
        </div>
        <!-- form filter data -->
        <div class="card-body">
          <form action="?module=nota" method="post" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Awal Jatuh Tempo</label>
                  <input type="date" name="tanggal_awal" class="form-control" autocomplete="off" value="<?php echo $tanggal_awal; ?>" >
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-group">
                  <label>Tanggal Akhir Jatuh Tempo</label>
                  <input type="date" name="tanggal_akhir" class="form-control" autocomplete="off" value="<?php echo $tanggal_akhir; ?>" >
                </div>
              </div>

              <div class="col-lg-2 pr-0">
                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control">
                    <option value="">Pilih Status</option>
                    <option value="lunas"
                    <?php if ($status == 'lunas') echo 'selected'; ?>
                    >Lunas</option>
                    <option value="belum_lunas"
                    <?php if ($status == 'belum_lunas') echo 'selected'; ?>
                    >Belum Lunas</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-2 pr-0">
                <div class="form-group">
                  <label>Urutkan Berdasarkan</label>
                  <select name="sort_by" class="form-control">
                    <option value="">Pilih Urutan</option>
                    <option value="nomor_nota" 
                    <?php if ($sort_by == 'nomor_nota') echo 'selected'; ?>
                    >Nomor Nota</option>
                    <option value="jatuh_tempo"
                    <?php if ($sort_by == 'jatuh_tempo') echo 'selected'; ?>
                    >Jatuh Tempo</option>
                    <option value="jumlah"
                    <?php if ($sort_by == 'jumlah') echo 'selected'; ?>
                    >Jumlah</option>
                    <option value="jumlah_belum_dibayar"
                    <?php if ($sort_by == 'jumlah_belum_dibayar') echo 'selected'; ?>
                    >Jumlah Belum Dibayar</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 pr-0">
                <div class="form-group">
                  <!-- tombol tampil data -->
                  <a href="?module=nota" class="btn btn-secondary btn-round btn-block">
                    <i class="fas fa-sync-alt"></i> Reset
                  </a>
                </div>
              </div>
              <div class="col-lg-2 pr-0">
                <div class="form-group">
                  <!-- tombol tampil data -->
                  <input type="submit" name="tampil" value="Tampilkan" class="btn btn-primary btn-round btn-block">
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <!-- judul tabel -->
          <div class="card-title">
              <i class="fas fa-file-alt mr-2"></i> Laporan Data Nota
              <a href='?module=form_entri_nota' class="float-right btn btn-secondary btn-round text-white">+ Nota</a>
              <br> 
              <?php
                  if ($status != '') {
                      $info_status = $status == 'lunas' ? 'Lunas' : 'Belum Lunas';
                      echo 'Status <strong>' . $info_status . '</strong> <br>';
                  }
                  if ($sort_by != '') {
                      $info_sort_by = str_replace('_', ' ', $sort_by);
                      echo 'Diurutkan berdasarkan <strong>' . $info_sort_by . '</strong> <br>';
                  }
                  if ($tanggal_awal != '' && $tanggal_akhir != '') {
                      echo 'Tanggal <strong>' . $tanggal_awal . '</strong> s.d. <strong>' . $tanggal_akhir . '</strong> <br>';
                  }
              ?>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <!-- tabel untuk menampilkan data dari database -->
            <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th class="text-center"
                    style="width: 5%;">#</th>
                  <th class="text-center"
                    style="width: 20%;">Nomor Nota</th>
                    <th class="text-center"
                    style="width: 15%;">Jumlah</th>
                    <th class="text-center"
                    style="width: 17%;">Jumlah Belum Dibayar</th>
                    <th class="text-center"
                      style="width: 10%;">Status</th>
                    <th class="text-center"
                      style="width: 15%;">Jatuh Tempo</th>
                    <th class="text-center"
                      style="width: 5%; min-width: 100px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // ubah format tanggal menjadi Tahun-Bulan-Hari (Y-m-d)
                if($tanggal_awal != '' && $tanggal_akhir != ''){
                  $tanggal_awal  = date('Y-m-d', strtotime($tanggal_awal));
                  $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));
                }

                // variabel untuk nomor urut tabel
                $no = 1;

                $query = "
                    SELECT n.*, n.jumlah - IFNULL(SUM(bn.jumlah_bayar), 0) as jumlah_belum_dibayar, 
                    DATEDIFF(n.jatuh_tempo, CURDATE()) as days_until_due
                    FROM tbl_nota n
                    LEFT JOIN tbl_bayar_nota bn ON n.id = bn.id_nota
                ";
                
                if(($tanggal_akhir != '' && $tanggal_awal != '')) {
                    $query .= " WHERE ";
                }
                
                if ($tanggal_awal != '' && $tanggal_akhir != '') {
                    $query .= " n.jatuh_tempo BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ";
                }
                
                $query .= " GROUP BY n.id ";
                
                if ($status == 'lunas') {
                    $query .= " HAVING jumlah_belum_dibayar <= 0";
                } elseif ($status == 'belum_lunas') {
                    $query .= " HAVING jumlah_belum_dibayar > 0";
                }
                
                if ($sort_by != '') {
                    $query .= " ORDER BY $sort_by ASC ";
                }

                $result = mysqli_query($mysqli, $query);

                // ambil data hasil query
                if($result){
                    while ($data = mysqli_fetch_assoc($result)) { ?>
                    <!-- tampilkan data -->
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo $data['nomor_nota']; ?></td>
                        <td class="text-right
                        ">Rp. <?php echo number_format($data['jumlah'], 0, ',', '.'); ?></td>
                        <td class="text-right
                        ">Rp. <?php echo number_format($data['jumlah_belum_dibayar'], 0, ',', '.'); ?></td>
                        <td class="text-center">
                            <?php
                            if ($data['jumlah_belum_dibayar'] <= 0) {
                                echo '<span class="badge badge-success">Lunas</span>';
                            } else {
                                echo '<span class="badge badge-danger">Belum Lunas</span>';
                            }
                            ?>
                        </td>
                        <?php
                        $days = $data['days_until_due'];
                        $statusNota = $data['jumlah_belum_dibayar'] <= 0 ? 'lunas' : 'belum_lunas';
                        $labelColor = 'green';
                        $textColor = 'white';

                        if ($days < 3 && $statusNota == 'belum_lunas') {
                            $labelColor = 'red';
                        } elseif ($days < 10 && $statusNota == 'belum_lunas') {
                            $labelColor = 'yellow';
                            $textColor = 'black';
                        }
                        ?>

                        <td class="text-center">
                            <span class="label p-2" style="background-color: <?= $labelColor; ?>; border-radius: 25px; color: <?=$textColor?>">
                                <?php 
                                    if ($statusNota == 'lunas') {
                                        echo 'Lunas';
                                    } elseif ($days < 0) {
                                        echo 'Telat ' . abs($days) . ' hari';
                                    } else {
                                        echo $days . ' hari lagi';
                                    }
                                ?>
                            </span>
                        </td>

                        <td class="text-center">
                        <!-- tombol detail -->
                        <a href="?module=nota_detail&id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                        </td>
                    </tr>
                    <?php }
                    }
                else {
                    echo '
                        <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    ';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php
  }

?>
<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { 
    if (isset($_GET['id'])) {
        $id_nota = $_GET['id'];
        $query = "SELECT n.*,  n.jumlah - IFNULL(SUM(bn.jumlah_bayar), 0) as jumlah_belum_dibayar, 
        DATEDIFF(n.jatuh_tempo, CURDATE()) as days_until_due
        FROM tbl_nota n
        LEFT JOIN tbl_bayar_nota bn ON n.id = bn.id_nota 
        WHERE n.id = '$id_nota'
        GROUP BY n.id ";

        $result = mysqli_query($mysqli, $query)
                                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
        $dataNota = mysqli_fetch_assoc($result);
      }
?>
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
          <li class="nav-item"><a href="?module=nota" class="text-white">Nota</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Detail</a></li>
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
                <div class="card-title">Data Nota</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ml-auto">
                        <div class="form-group">
                            <label>Nama perusahaan </label>
                            <input type="text" name="nama_perusahaan" class="form-control" autocomplete="off" disabled 
                                value="<?=$dataNota['nama_perusahaan']?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>No Nota</label>
                            <input type="text" name="no_nota" class="form-control" autocomplete="off" required disabled
                                value="<?=$dataNota['nomor_nota']?>">
                        </div>
                    </div>
                    <div class="col-md-5 ml-auto">
                        <div class="form-group">
                            <?php
                                $date = DateTime::createFromFormat('Y-m-d', $dataNota['tanggal']);
                            ?>
                            <label>Tanggal Jatuh Tempo</label>
                            <input type="text" name="tanggal" class="form-control date-picker" autocomplete="off" value="<?=$date->format('d-m-Y')?>" required disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="text" id="jumlah" name="jumlah" class="form-control" value="<?= number_format($dataNota['jumlah'], 0, ',', '.') ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Jumlah Belum Dibayar</label>
                            <input type="text" name="jumlah_belum_dibayar" class="form-control" autocomplete="off" required disabled
                                value="Rp. <?=number_format($dataNota['jumlah_belum_dibayar'], 0, ',', '.')?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <br>
                            <?php
                                if ($dataNota['jumlah_belum_dibayar'] == 0) {
                                    echo '<span class="badge badge-success label p-2">Lunas</span>';
                                } else {
                                    echo '<span class="badge badge-danger label p-2">Belum Lunas</span>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" disabled><?=$dataNota['deskripsi']?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <!-- judul tabel -->
                <div class="card-title">Data Pembayaran Nota
                    <a href='?module=form_entri_nota_bayar&nomor_nota=<?=$dataNota['nomor_nota']?>&id_nota=<?=$dataNota['id']?>' class="float-right btn btn-secondary btn-round text-white">+ Nota</a>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <!-- tabel untuk menampilkan data dari database -->
                <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Jumlah Dibayar</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // variabel untuk nomor urut tabel
                    $no = 1;
                    // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan"
                    $query = mysqli_query($mysqli, "SELECT nb.*
                                                    FROM tbl_bayar_nota as nb
                                                    WHERE nb.id_nota = '$id_nota'
                                                    ORDER BY nb.id DESC
                                                    ")
                                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                    // ambil data hasil query
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <!-- tampilkan data -->
                        <tr>
                            <td width="50" class="text-center"><?php echo $no++; ?></td>
                            <td width="80" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggal_bayar'])); ?></td>
                            <td width="100" class="text-right">Rp. <?php echo number_format($data['jumlah_bayar'], 0, '', '.'); ?></td>
                            <td width="220">
                                <div class="row" style="padding-right: 10px;">
                                    <div class="col-md-9">
                                    <?php
                                    $deskripsi = $data['deskripsi'];
                                    // jika deskripsi lebih dari 50 karakter
                                    if (strlen($data['deskripsi']) > 50) {
                                        // potong deskripsi menjadi 50 karakter
                                        $deskripsi = substr($data['deskripsi'], 0, 50) . '...';
                                    }
                                    if (empty($data['deskripsi'])) {
                                        echo '-';
                                    } else {
                                        echo $deskripsi;
                                    }
                                    ?>

                                    </div class='col-md-2'>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modaldeskripsi<?php echo $data['id']; ?>">
                                    Details
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modaldeskripsi<?php echo $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $data['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel<?php echo $data['id']; ?>">keterangan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php echo $data['deskripsi']; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div> 
                            </td>
                            <td width="50" class="text-center">
                                <div>
                                    <!-- tombol hapus data -->
                                    <a href="modules/nota/proses_hapus_bayar.php?id=<?=$data['id'];?>" onclick="return confirm('Anda yakin ingin menghapus data bayar nota <?php echo $data['id']; ?>?')" class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus">
                                        <i class="fas fa-trash fa-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <!-- judul tabel -->
                <div class="card-title">Data Barang Masuk
                    <a href='?module=form_entri_data_barang&nomor_nota=<?=$dataNota['nomor_nota']?>&id_nota=<?=$dataNota['id']?>' class="float-right btn btn-secondary btn-round text-white">+ Nota</a>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <!-- tabel untuk menampilkan data dari database -->
                <table id="basic-datatables2" class="display table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">ID Transaksi</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Barang</th>
                        <th class="text-center">Jumlah Masuk</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // variabel untuk nomor urut tabel
                    $no = 1;
                    // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan"
                    $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggal, a.barang, a.jumlah, b.nama_barang, c.nama_satuan, a.keterangan
                                                    FROM tbl_barang_masuk as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c
                                                    ON a.barang=b.id_barang AND b.satuan=c.id_satuan 
                                                    ORDER BY a.id_transaksi DESC")
                                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                    // ambil data hasil query
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <!-- tampilkan data -->
                        <tr>
                        <td width="50" class="text-center"><?php echo $no++; ?></td>
                        <td width="90" class="text-center"><?php echo $data['id_transaksi']; ?></td>
                        <td width="80" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                        <td width="220"><?php echo $data['barang']; ?> - <?php echo $data['nama_barang']; ?></td>
                        <td width="100" class="text-right"><?php echo number_format($data['jumlah'], 0, '', '.'); ?></td>
                        <td width="60"><?php echo $data['nama_satuan']; ?></td>
                        <td width="220">
                        <div class="row" style="padding-right: 10px;">
                            <div class="col-md-9">
                            <?php
                            $keterangan = $data['keterangan'];
                            // jika keterangan lebih dari 50 karakter
                            if (strlen($data['keterangan']) > 50) {
                                // potong keterangan menjadi 50 karakter
                                $keterangan = substr($data['keterangan'], 0, 50) . '...';
                            }
                            if (empty($data['keterangan'])) {
                                echo '-';
                            } else {
                                echo $keterangan;
                            }
                            ?>

                            </div class='col-md-2'>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalKeterangan<?php echo $data['id_transaksi']; ?>">
                            Details
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="modalKeterangan<?php echo $data['id_transaksi']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $data['id_transaksi']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel<?php echo $data['id_transaksi']; ?>">Keterangan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php echo $data['keterangan']; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div> 
                        </td>
                        <td width="50" class="text-center">
                            <div>
                            <!-- tombol hapus data -->
                            <a href="modules/barang-masuk/proses_hapus.php?id=<?php echo $data['id_transaksi']; ?>" onclick="return confirm('Anda yakin ingin menghapus data barang masuk <?php echo $data['id_transaksi']; ?>?')" class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus">
                                <i class="fas fa-trash fa-sm"></i>
                            </a>
                            </div>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
  <?php
  }

?>
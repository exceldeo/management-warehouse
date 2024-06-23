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
        $query = "SELECT n.*
        FROM tbl_nota n
        WHERE n.id = '$id_nota'";

        $result = mysqli_query($mysqli, $query)
                                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
        $dataNota = mysqli_fetch_assoc($result);
      }
?>
  <!-- menampilkan pesan kesalahan -->
  <div id="pesan"></div>

  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-sign-out-alt mr-2"></i> Barang Keluar</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=nota" class="text-white">Nota</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=nota_detail&id=<?=$id_nota?>" class="text-white">Detail</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Ubah</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Ubah Data Nota</div>
      </div>
      <!-- form entri data -->
      <form action="modules/nota/proses_ubah.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
            <input type="hidden" name="id_nota" value="<?php echo $dataNota['id']; ?>">
            <div class="row">
                <div class="col-md-12 ml-auto">
                <div class="form-group">
                    <label>Nama perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_perusahaan" class="form-control" autocomplete="off" required value="<?=$dataNota['nama_perusahaan']?>">
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>No Nota<span class="text-danger">*</span></label>
                        <input type="text" name="no_nota" class="form-control" autocomplete="off" required value="<?=$dataNota['nomor_nota']?>">
                    </div>
                </div>
                <div class="col-md-5 ml-auto">
                    <div class="form-group">
                        <label>Tanggal Jatuh Tempo<span class="text-danger">*</span></label>
                        <?php
                            $date = DateTime::createFromFormat('Y-m-d', $dataNota['tanggal']);
                        ?>
                        <input type="text" name="tanggal" class="form-control date-picker" autocomplete="off" value="<?=$date->format('d-m-Y')?>" required >
                        <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Jumlah<span class="text-danger">*</span></label>
                        <input type="text" id="jumlah" name="jumlah" class="form-control" autocomplete="off" required
                                value="<?=$dataNota['jumlah']?>">
                    </div>
                </div>
            </div>
            <script>
                function formatJumlah() {
                    var jumlahField = document.getElementById('jumlah');
                    var numStr = jumlahField.value.replace(/,/g, '');
                    jumlahField.value = Number(numStr).toLocaleString();
                }

                document.getElementById('jumlah').addEventListener('keyup', formatJumlah);

                // Call the function on page load
                window.onload = formatJumlah;
            </script>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?=$dataNota['deskripsi']?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data barang keluar -->
          <a href="?module=nota_detail&id=<?=$id_nota?>" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
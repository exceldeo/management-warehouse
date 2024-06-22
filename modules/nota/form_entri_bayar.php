<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { 
  if (isset($_GET['nomor_nota'])) {
    $nomor_nota = $_GET['nomor_nota'];
  }
  if (isset($_GET['id_nota'])) {
    $id_nota = $_GET['id_nota'];
  }
  ?>
  <!-- menampilkan pesan kesalahan -->
  <div id="pesan"></div>

  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Barang Masuk</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Laporan</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=nota" class="text-white">Nota</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=nota_detail&id=<?=$id_nota?>" class="text-white">Detail Nota</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Entri</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Entri Data Bayar</div>
      </div>
      <!-- form entri data -->
      <form action="modules/nota/proses_entri_bayar.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Nomor Nota</label>
                <input type="text" name="nomor_nota" class="form-control" autocomplete="off"
                  value="<?=$nomor_nota;?>" disabled>
                <input type="hidden" name="id_nota" 
                  value="<?=$id_nota?>">
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label>Jumlah yang dibayar<span class="text-danger">*</span></label>
                      <input type="text" id="jumlah" name="jumlah_bayar" class="form-control">
                  </div>
              </div>
          </div>
          <script>
              document.getElementById('jumlah').addEventListener('keyup', function(e) {
                  var numStr = this.value.replace(/,/g, '');
                  this.value = Number(numStr).toLocaleString();
              });
          </script>
          <div class="row">
            <div class="col-md-12 ml-auto">
                <div class="form-group">
                    <label>Tanggal Bayar<span class="text-danger">*</span></label>
                    <input type="text" name="tanggal_bayar" class="form-control date-picker" autocomplete="off" value="<?php echo date("d-m-Y"); ?>" required>
                    <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
              </div>
             </div>
          </div>
        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data barang masuk -->
          <a href="?module=nota_detail&id=<?=$id_nota?>" class="btn btn-default btn-round pl-4 pr-4">Kembali</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
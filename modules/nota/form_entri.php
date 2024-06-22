<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { ?>
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
          <li class="nav-item"><a>Entri</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Entri Data Nota</div>
      </div>
      <!-- form entri data -->
      <form action="modules/nota/proses_entri.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
            <div class="row">

                <div class="col-md-12 ml-auto">
                <div class="form-group">
                    <label>Nama perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_perusahaan" class="form-control" autocomplete="off" required>
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>No Nota<span class="text-danger">*</span></label>
                        <input type="text" name="no_nota" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-md-5 ml-auto">
                    <div class="form-group">
                        <label>Tanggal Jatuh Tempo<span class="text-danger">*</span></label>
                        <input type="text" name="tanggal" class="form-control date-picker" autocomplete="off" value="<?php echo date("d-m-Y"); ?>" required>
                        <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Jumlah<span class="text-danger">*</span></label>
                        <input type="text" id="jumlah" name="jumlah" class="form-control" rows="3">
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                            // sql statement untuk menampilkan data dari tabel "tbl_barang"
                            $query_barang = mysqli_query($mysqli, "SELECT id_barang, nama_barang FROM tbl_barang ORDER BY id_barang ASC")
                                                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                            // var_dump($query_barang,mysqli_num_rows($query_barang),mysqli_fetch_array($query_barang));
                            $barangs = mysqli_fetch_all($query_barang, MYSQLI_ASSOC);
                            // die;
                        ?>
                        <label>Barang Masuk</label>
                        <div id="barangMasukContainer"></div>
                        <button type="button" id="addBarangMasuk" class="btn btn-primary mt-2">Tambah Barang Masuk</button>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('addBarangMasuk').addEventListener('click', function() {
                    var barangMasukContainer = document.getElementById('barangMasukContainer');
                    var newEntry = document.createElement('div');
                    newEntry.className = 'row mt-2';
                    newEntry.innerHTML = `
                        <div class="col-md-7">
                            <select name="barang[]" class="form-control chosen-select" required>
                                <option value="">Pilih Barang</option>
                                <?php
                                    foreach ($barangs as $barang) {
                                        echo '<option value="' . $barang['id_barang'] . '">' . $barang['nama_barang'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="jumlah_masuk[]" class="form-control" placeholder="Jumlah Masuk" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger removeBarangMasuk">Hapus</button>
                        </div>
                    `;
                    barangMasukContainer.appendChild(newEntry);
                });

                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('removeBarangMasuk')) {
                        e.target.parentNode.parentNode.remove();
                    }
                });
            </script>
        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data barang keluar -->
          <a href="?module=nota" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
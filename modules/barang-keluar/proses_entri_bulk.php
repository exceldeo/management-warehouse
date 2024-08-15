<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk insert
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data hasil submit dari form
  if (isset($_POST['simpan'])) {


    $nota_transaksi = mysqli_real_escape_string($mysqli, $_POST['nota_transaksi']);
    $tanggal = mysqli_real_escape_string($mysqli, trim($_POST['tanggal']));
    $keterangan = mysqli_real_escape_string($mysqli, $_POST['keterangan']);
    
    // Escape each element in the arrays
    $barangs_keluar = array_map(function($item) use ($mysqli) {
        return mysqli_real_escape_string($mysqli, $item);
    }, $_POST['barang_keluar']);

    // Escape each element in the arrays
    $id_barangs = array_map(function($item) use ($mysqli) {
      return mysqli_real_escape_string($mysqli, $item);
    }, $_POST['id_barang']);
    
    $jumlahs = array_map(function($item) use ($mysqli) {
        return mysqli_real_escape_string($mysqli, $item);
    }, $_POST['jumlah']);

    for($i = 0; $i < count($barangs_keluar); $i++ ){
        // ubah format tanggal menjadi Tahun-Bulan-Hari (Y-m-d) sebelum disimpan ke database
        $tanggal_keluar = date('Y-m-d', strtotime($tanggal));

        // membuat "id_transaksi"
        // sql statement untuk menampilkan 7 digit terakhir dari "id_transaksi" pada tabel "tbl_barang_keluar"
        $query = mysqli_query($mysqli, "SELECT RIGHT(id_transaksi,7) as nomor FROM tbl_barang_keluar ORDER BY id_transaksi DESC LIMIT 1")
        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
        // ambil jumlah baris data hasil query
        $rows = mysqli_num_rows($query);

        // cek hasil query
        // jika "id_transaksi" sudah ada
        if ($rows <> 0) {
        // ambil data hasil query
        $data = mysqli_fetch_assoc($query);
        // nomor urut "id_transaksi" yang terakhir + 1 (contoh nomor urut yang terakhir adalah 2, maka 2 + 1 = 3, dst..)
        $nomor_urut = $data['nomor'] + 1;
        }
        // jika "id_transaksi" belum ada
        else {
        // nomor urut "id_transaksi" = 1
        $nomor_urut = 1;
        }

        // menambahkan karakter "TK-" diawal dan karakter "0" disebelah kiri nomor urut
        $id_transaksi = "TM-" . str_pad($nomor_urut, 7, "0", STR_PAD_LEFT);

        $query = "INSERT INTO tbl_barang_keluar(id_transaksi, tanggal, barang, jumlah, keterangan, kode_transaksi) 
                                        VALUES('$id_transaksi', '$tanggal_keluar', '$id_barangs[$i]', '$jumlahs[$i]',   '$keterangan', '$nota_transaksi')";

        // sql statement untuk insert data ke tabel "tbl_barang_keluar"
        $insert = mysqli_query($mysqli, $query);
    } 

  
    header('location: ../../main.php?module=barang_keluar&pesan=1');
  }
}

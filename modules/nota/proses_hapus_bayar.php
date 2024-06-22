<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk delete
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data GET "id_transaksi"
  if (isset($_GET['id'])) {
    // ambil data GET dari tombol hapus
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);

    // sql statement untuk delete data dari tabel "tbl_barang_masuk" berdasarkan "id_transaksi"
    $query = "DELETE FROM tbl_bayar_nota WHERE id='$id'";

    $delete = mysqli_query($mysqli, $query)
                                     or die('Ada kesalahan pada query delete : ' . mysqli_error($mysqli));
    // cek query
    // jika proses delete berhasil
    if ($delete) {
      // alihkan ke halaman barang masuk dan tampilkan pesan berhasil hapus data
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }
}

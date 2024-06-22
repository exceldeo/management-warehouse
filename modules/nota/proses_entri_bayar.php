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
    // ambil data hasil submit dari form
    $id_nota        = mysqli_real_escape_string($mysqli, $_POST['id_nota']);
    $jumlah         = str_replace(',', '', $_POST['jumlah_bayar']); // Remove delimiters
    $tanggal        = mysqli_real_escape_string($mysqli, trim($_POST['tanggal_bayar']));
    $keterangan     = mysqli_real_escape_string($mysqli, $_POST['keterangan']);
    $date = DateTime::createFromFormat('d-m-Y', $tanggal);
    $tanggal        = $date->format('Y-m-d');

    $query = "INSERT INTO tbl_bayar_nota(id_nota, jumlah_bayar, tanggal_bayar, deskripsi)
              VALUES('$id_nota', '$jumlah', '$tanggal', '$keterangan')";
    $insert = mysqli_query($mysqli, $query)
                                     or die('Ada kesalahan pada query insert : ' . mysqli_error($mysqli));
    // cek query
    // jika proses insert berhasil
    if ($insert) {
      // alihkan ke halaman barang masuk dan tampilkan pesan berhasil simpan data
      header('location: ../../main.php?module=nota_detail&id='.$id_nota);
    }
  }
}

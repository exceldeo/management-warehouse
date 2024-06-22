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
    $nama_perusahaan      = mysqli_real_escape_string($mysqli, $_POST['nama_perusahaan']);
    $no_nota              = mysqli_real_escape_string($mysqli, $_POST['no_nota']);
    $tanggal_jatuh_tempo = mysqli_real_escape_string($mysqli, trim($_POST['tanggal']));
    $date = DateTime::createFromFormat('d-m-Y', $tanggal_jatuh_tempo);
    $tanggal_jatuh_tempo = $date->format('Y-m-d');
    $jumlah               = str_replace(',', '', $_POST['jumlah']); // Remove delimiters
    $jumlah               = mysqli_real_escape_string($mysqli, $jumlah);
    $tanggal              = date('Y-m-d'); // Set to current date
    $keterangan           = mysqli_real_escape_string($mysqli, $_POST['keterangan']);
    $barangs              = $_POST['barang'];

    $queryInsertNota = "INSERT INTO tbl_nota(nama_perusahaan, nomor_nota, jatuh_tempo, jumlah, tanggal, deskripsi) 
                        VALUES('$nama_perusahaan', '$no_nota', '$tanggal_jatuh_tempo', '$jumlah', '$tanggal', '$keterangan')";
    try {
      $insertNota = mysqli_query($mysqli, $queryInsertNota);

      if ($insertNota) {
        $idNota = mysqli_insert_id($mysqli);
        foreach ($barangs as $key => $barang) {

          $query = mysqli_query($mysqli, "SELECT RIGHT(id_transaksi,7) as nomor FROM tbl_barang_masuk ORDER BY id_transaksi DESC LIMIT 1")
                                          or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
          $rows = mysqli_num_rows($query);
          if ($rows <> 0) {
            $data = mysqli_fetch_assoc($query);
            $nomor_urut = $data['nomor'] + 1;
          }
          else {
            $nomor_urut = 1;
          }
          $id_transaksi = "TM-" . str_pad($nomor_urut, 7, "0", STR_PAD_LEFT);

          $jumlahMasuk = str_replace(',', '', $_POST['jumlah_masuk'][$key]); // Remove delimiters
          $jumlahMasuk = mysqli_real_escape_string($mysqli, $jumlahMasuk);
          $queryInsertNotaDetail = "INSERT INTO tbl_barang_masuk(id_nota, barang, jumlah, tanggal, id_transaksi) 
                                    VALUES('$idNota', '$barang', '$jumlahMasuk', '$tanggal', '$id_transaksi')";
          $insertNotaDetail = mysqli_query($mysqli, $queryInsertNotaDetail);

          if (!$insertNotaDetail) {
            throw new Exception('Ada kesalahan pada query insert nota detail : ' . mysqli_error($mysqli));
          }
        }
        header('location: ../../main.php?module=nota&pesan=1');
      } else {
        throw new Exception('Ada kesalahan pada query insert nota : ' . mysqli_error($mysqli));
      }
    } catch (Exception $e) {
      header('location: ../../main.php?module=form_entri_nota&pesan=0');
    }
  }
}

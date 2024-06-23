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
  if (isset($_POST['simpan'])) {
    // ambil data hasil submit dari form
    $nama_perusahaan      = mysqli_real_escape_string($mysqli, $_POST['nama_perusahaan']);
    $no_nota              = mysqli_real_escape_string($mysqli, $_POST['no_nota']);
    $tanggal_jatuh_tempo  = mysqli_real_escape_string($mysqli, trim($_POST['tanggal']));
    $date = DateTime::createFromFormat('d-m-Y', $tanggal_jatuh_tempo);
    $tanggal_jatuh_tempo  = $date->format('Y-m-d');
    $jumlah               = str_replace(',', '', $_POST['jumlah']); // Remove delimiters
    $jumlah               = mysqli_real_escape_string($mysqli, $jumlah);
    $keterangan           = mysqli_real_escape_string($mysqli, $_POST['keterangan']);
    $id_nota              = mysqli_real_escape_string($mysqli, $_POST['id_nota']);

    // create SQL statement
    $queryUpdateNota = "UPDATE tbl_nota SET 
                        nama_perusahaan = '$nama_perusahaan', 
                        nomor_nota = '$no_nota', 
                        jatuh_tempo = '$tanggal_jatuh_tempo', 
                        jumlah = '$jumlah', 
                        deskripsi = '$keterangan' 
                        WHERE id = '$id_nota'";

    // execute SQL statement
    // var_dump($queryUpdateNota);
    // die();
    try {
        $updateNota = mysqli_query($mysqli, $queryUpdateNota);
        if ($updateNota) {
            header('location: ../../main.php?module=nota_detail&id='.$id_nota.'&pesan=1');
        } else {
            throw new Exception('Ada kesalahan pada query update nota : ' . mysqli_error($mysqli));
        }
    } catch (Exception $e) {
        header('location: ../../main.php?module=nota_detail&id='.$id_nota.'&pesan=2');
    }
}
}

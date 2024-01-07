<?php
include 'koneksi.php';
session_start();
 if (!isset($_SESSION['id_admin'])) {
     header("Location: login.php");
 }
// Ambil data yang dikirim melalui AJAX
$barang = $_POST['barang'];
$durasi = $_POST['durasi'];
$tanggal_awal = $_POST['tanggal_awal'];
$tanggal_akhir = $_POST['tanggal_akhir'];
$tanggal_hasil = $_POST['tanggal_hasil'];
$data_ramal = $_POST['data_ramal'];
$mape = $_POST['mape'];

// Lakukan validasi atau manipulasi data sesuai kebutuhan

// Simpan data ke dalam database atau lakukan operasi penyimpanan lainnya
// Gantilah bagian ini dengan logika penyimpanan data ke database Anda
// Contoh sederhana:
// $koneksi = mysqli_connect("localhost", "username", "password", "nama_database");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "INSERT INTO peramalan (`id_peramalan`, `barang`, `durasi`, `tanggal_awal`, `tanggal_akhir`, `tanggal_hasil`, `data_ramal`, `mape`) 
        VALUES (NULL, '$barang', '$durasi', '$tanggal_awal', '$tanggal_akhir', '$tanggal_hasil', '$data_ramal', '$mape')";
$insertSql = mysqli_query($conn, $sql);
if ($insertSql) {
    echo "Data berhasil disimpan";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

?>

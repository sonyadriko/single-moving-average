<?php
// fetch_dates.php

// Include your database connection file (koneksi.php)
include 'koneksi.php';
include 'date_response.php';

// Assuming $nama_barang is coming from the AJAX request
if (!empty($_GET['nama_barang'])) {
    $nama_barang = $_GET['nama_barang'];

    // Fetch distinct dates based on the selected product
    $get_dates = mysqli_query($conn, "SELECT DISTINCT tanggal FROM penjualan WHERE nama_barang = '$nama_barang' ORDER BY tanggal");

    // Generate HTML options for the "Tanggal Awal" dropdown
    $options = array();
    while ($date = mysqli_fetch_assoc($get_dates)) {
        $formatted_date = date("j F Y", strtotime($date['tanggal']));
        $options[] = new DateResponse($date['tanggal'], $formatted_date);

    }

    echo json_encode($options);
}
?>

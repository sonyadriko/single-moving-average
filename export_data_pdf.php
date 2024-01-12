<?php
require 'vendor/autoload.php'; // Adjust the path if needed
include 'koneksi.php';
use Mpdf\Mpdf;

$pdf = new Mpdf();

$pdf->SetCreator('Your Creator');
$pdf->SetAuthor('Your Author');
$pdf->SetTitle('Data Penjualan');
$pdf->SetSubject('Data Penjualan Export');

$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', '', 12);

// Add table headers with improved formatting
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(30, 10, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(15, 10, 'Durasi', 1, 0, 'C');
$pdf->Cell(25, 10, 'Tanggal Awal', 1, 0, 'C');
$pdf->Cell(25, 10, 'Tanggal Akhir', 1, 0, 'C');
$pdf->Cell(25, 10, 'Tanggal Hasil', 1, 0, 'C');
$pdf->Cell(25, 10, 'Data Ramal', 1, 0, 'C');
$pdf->Cell(15, 10, 'MAPE', 1, 0, 'C');
$pdf->Ln(); // Move to the next line

// Fetch data from the database and add rows to the PDF
$query = "SELECT * FROM peramalan";
$result = mysqli_query($conn, $query);

$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10, 10, $counter++, 1, 0, 'C');
    $pdf->Cell(30, 10, $row['barang'], 1, 0, 'L');
    $pdf->Cell(15, 10, $row['durasi'], 1, 0, 'C');
    $pdf->Cell(25, 10, date("d F Y", strtotime($row['tanggal_awal'])), 1, 0, 'C');
    $pdf->Cell(25, 10, date("d F Y", strtotime($row['tanggal_akhir'])), 1, 0, 'C');
    $pdf->Cell(25, 10, date("d F Y", strtotime($row['tanggal_hasil'])), 1, 0, 'C');
    $pdf->Cell(25, 10, $row['data_ramal'], 1, 0, 'C');
    $pdf->Cell(15, 10, $row['mape'] . '%', 1, 0, 'C');
    $pdf->Ln(); // Move to the next line
}

$pdf->Output('Data_Penjualan.pdf', 'D');
?>


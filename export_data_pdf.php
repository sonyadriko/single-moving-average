<?php
require 'vendor/autoload.php'; // Adjust the path if needed

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

// Buffer the output of the PHP execution
ob_start();
include 'history.php';
$html = ob_get_clean();

// Load HTML to dompdf
$dompdf->loadHtml($html);

// Set paper size (optional)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output the generated PDF file
$output = $dompdf->output();

// Set the appropriate headers for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="output.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($output));

// Output the PDF file to the browser
echo $output;

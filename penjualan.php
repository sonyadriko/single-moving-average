<?php
 include 'koneksi.php';
 session_start();
  if (!isset($_SESSION['id_admin'])) {
      header("Location: login.php");
  }
  
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Data Penjualan</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- Template style -->
<link rel="stylesheet" href="dist/css/style.css">
<link rel="stylesheet" href="dist/et-line-font/et-line-font.css">
<link rel="stylesheet" href="dist/font-awesome/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="dist/weather/weather-icons.min.css">
<link type="text/css" rel="stylesheet" href="dist/weather/weather-icons-wind.min.css">
<script src="plugins/charts/code/highcharts.js"></script>
</head>

<!-- ... -->

<body class="sidebar-mini">
<div class="wrapper"> 
  
<?php include 'header.php'?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include 'sidebar.php'?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>Data Barang</h1>
    </section> -->
    
    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="chart-box">
            <h4>Data Penjualan</h4>
            <form action="penjualan.php" method="post" enctype="multipart/form-data">
              <label for="excelFile">Choose Excel File:</label>
              <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
              <button type="submit" name="import">Import</button>
            </form>  
            <?php
require 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;

function mapMonth($indonesianMonth)
{
    $monthMapping = [
        'Januari' => 'January',
        'Februari' => 'February',
        'Maret' => 'March',
        'April' => 'April',
        'Mei' => 'May',
        'Juni' => 'June',
        'Juli' => 'July',
        'Agustus' => 'August',
        'September' => 'September',
        'Oktober' => 'October',
        'November' => 'November',
        'Desember' => 'December',
    ];

    return $monthMapping[$indonesianMonth];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['import'])) {
        // Process the uploaded Excel file for import
        if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
            $excelFile = $_FILES['excelFile']['tmp_name'];

            // Load the Excel file with allowOnly setting
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($excelFile);

            $worksheet = $spreadsheet->getActiveSheet();

            // Prepare the statement for inserting data into the 'penjualan' table
            $stmtInsert = $conn->prepare('INSERT INTO penjualan (nama_barang, tanggal, jumlah) VALUES (?, ?, ?)');

            $alertDisplayed = false;
            // Iterate through rows and insert data into the 'penjualan' table
            foreach ($worksheet->getRowIterator() as $row) {
              $rowData = [];
              foreach ($row->getCellIterator() as $cell) {
                  $rowData[] = $cell->getValue();
              }

              // Assuming the Excel columns are in the order of 'nama_barang', 'tanggal', 'jumlah'
              if (count($rowData) == 3) {
                  $nama_barang = $rowData[0];
                  $raw_tanggal = $rowData[1];
                  $jumlah = $rowData[2];

                  // echo 'Raw Date: ' . $raw_tanggal . '<br>'; // Print the raw date

                  // Map Indonesian month names to English month names
                  $raw_tanggal = preg_replace_callback('/\b(\p{L}+)\b/u', function ($matches) {
                      return mapMonth($matches[1]);
                  }, $raw_tanggal);

                  // Convert the date to the desired format
                  $dateObject = DateTime::createFromFormat('j-F-Y', $raw_tanggal);

                  if ($dateObject !== false) {
                      $tanggal = $dateObject->format('Y-m-d');
                  } else {
                      // echo 'Error: Unable to parse the date.';

                      // You might want to handle this error condition appropriately
                      continue; // Skip to the next iteration
                  }
            // Debug information
            // echo 'Debug: Nama Barang - ' . $nama_barang . ', Tanggal - ' . $tanggal . ', Jumlah - ' . $jumlah . '<br>';
                  // Check if the combination of 'nama_barang' and 'tanggal' already exists
                  $stmtSelect = $conn->prepare('SELECT COUNT(*) FROM penjualan WHERE nama_barang = ? AND tanggal = ? AND jumlah = ?');
                  $stmtSelect->bind_param('ssi', $nama_barang, $tanggal, $jumlah); // Adjust 'sss' to match the data types

                  $stmtSelect->execute();

                  $result = $stmtSelect->get_result();
                  $rowCount = $result->fetch_assoc()['COUNT(*)'];

                  // Insert data into the 'penjualan' table if the combination doesn't exist
                  // if ($rowCount == 0 && $nama_barang !== null && $tanggal !== null && $jumlah !== null) {
                  //     if ($stmtInsert->execute([$nama_barang, $tanggal, $jumlah])) {
                  //         echo '<script>alert("Success: Data successfully imported.");</script>';
                  //         // echo 'success';
                  //     } else {
                  //         // echo '<script>alert("Error: Failed to save data. ' . $stmtInsert->error ;
                  //         echo '<script>alert("Error: Failed to save data.");</script>';
                  //         // echo 'error';
                  //     }
                  // } else {
                  //   // echo 'exists';/
                  //   echo '<script>alert("Data with the combination of Kode and Nama barang already exists or Kode or Nama barang is empty or NULL.");</script>';
                  //     // echo 'Warning: Data with the combination of Kode and Nama barang already exists or Kode or Nama barang is empty or NULL."</br>"';
                  // }
                  if ($rowCount == 0 && $nama_barang !== null && $tanggal !== null && $jumlah !== null) {
                    if ($stmtInsert->execute([$nama_barang, $tanggal, $jumlah])) {
                        $alertMessage = 'Import successful!';  // Set the alert message
                    } else {
                        $alertMessage = 'Error: Failed to save data.';
                    }
                } else {
                    $alertMessage = 'Peringatan: Data dengan kombinasi Kode dan Nama barang sudah ada atau Kode atau Nama barang kosong atau NULL.';
                }
              }
            }
            echo '<script>alert("Import successful!");</script>';
        } else {
            echo '<script>alert("Error uploading the file.");</script>';
        }
echo '<script>alert("' . $alertMessage . '");</script>';

    }
}
// if (!$alertDisplayed) {
//   echo '<script>alert("Import successful!");</script>';
// }
            ?>
            </br>
            <div id="example_filter" class="dataTables_filter pull-right">
              <input class="form-control" id="placeholderInput" placeholder="Search" type="email">
            </div>

            <a href="tambah_penjualan.php" class="btn btn-primary btn-user">Tambah Penjualan</a>

            <table class="table table-responsive">
              <thead>
                <tr>
                  <th class="sortable">No</th>
                  <th class="sortable">Nama barang</th>
                  <th class="sortable">Tanggal</th>
                  <th class="sortable">Jumlah</th>
                  <th class="sortable">opsi</th>
                </tr>
              </thead>
              <tr>
                <?php 
                $no = 1;
                $get_data = mysqli_query($conn, "select * from penjualan");
                while($display = mysqli_fetch_array($get_data)) {
                    $id = $display['id_penjualan'];
                    $id_barang = $display['nama_barang'];
                    $tanggal = $display['tanggal'];
                    $jumlah = $display['jumlah'];
                
                
                    
                ?>
                <td class="text-truncate"><?php echo $no ?></td>
                <td class="text-truncate"><?php echo $id_barang ?></td>
                <td class="text-truncate"><?php echo $tanggal ?></td>
                <td class="text-truncate"><?php echo $jumlah ?></td>
                <td class="text-truncate">
                    <a href='ubah_penjualan.php?GetID=<?php echo $id ?>' style="text-decoration: none; list-style: none;"><input type='submit' value='Ubah' id='editbtn' class="btn btn-primary btn-user" ></a>
                    <a href='delete_penjualan.php?Del=<?php echo $id ?>' style="text-decoration: none; list-style: none;"><input type='submit' value='Hapus' id='delbtn' class="btn btn-primary btn-user" ></a>                       
                </td>
              </tr>
              <?php
              $no++;
                }
              ?>
            </table>
            <ul class="pagination m-bot-0">
              <li> <a href="#" aria-label="Previous"> <span aria-hidden="true">«</span> </a> </li>
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li> <a href="#" aria-label="Next"> <span aria-hidden="true">»</span> </a> </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- content --> 
  </div>
  <!-- content-wrapper --> 
  
  <!-- Main Footer -->
  <?php include 'footer.php' ?>
</div>
<!-- wrapper --> 

<!-- jQuery --> 
<script src="dist/js/jquery.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="dist/js/ovio.js"></script> 
<script src="plugins/tables/jquery.tablesort.js"></script> 
<script type="text/javascript">
(function($) {
  "use strict";
$("table").tablesort();
})(jQuery);
</script>
</body>
</html>

<!-- <script>
    function handleFormSubmit() {
        // Display a loading message
        document.getElementById("importMessages").innerHTML = "Importing...";

        // Use AJAX to submit the form without reloading the page
        var form = document.getElementById("importForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.onload = function () {
            // Update the messages container with the response from the server
            document.getElementById("importMessages").innerHTML = xhr.responseText;
        };
        xhr.send(formData);

        // Prevent the default form submission
        return false;
    }
</script> -->

<!-- ... (di antara tag <head> dan <body>) ... -->
<script>
    function handleFormSubmit() {
        // Display a loading message
        document.getElementById("importMessages").innerHTML = "Importing...";

        // Use AJAX to submit the form without reloading the page
        var form = document.getElementById("importForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.onload = function () {
            // Update the messages container with the response from the server
            var response = xhr.responseText.trim();
            if (response === 'success') {
                alert('Data successfully imported.');
            } else if (response === 'error') {
                alert('Failed to save data.');
            } else if (response === 'exists') {
                alert('Data with the combination of Kode and Nama barang already exists or Kode or Nama barang is empty or NULL.');
            } else {
                document.getElementById("importMessages").innerHTML = response;
            }
        };
        xhr.send(formData);

        // Prevent the default form submission
        return false;
    }
</script>
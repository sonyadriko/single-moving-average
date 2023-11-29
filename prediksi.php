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
                    <h4>Prediksi</h4>
                    <form method="GET" action="perhitungan.php">
                        <div class="row">
                        <div class="col-md-4">
                        <fieldset class="form-group">
                        <label class="mb-3">Nama Barang : </label>
                        <select class="form-control" id="nama_barang" name="nama_barang">
                            <option value="" selected disabled>Pilih Barang</option>

                            <?php
                            // Fetch data from the database
                            $get_barang = mysqli_query($conn, "SELECT * FROM barang");

                            // Check if there are rows in the result
                            if (mysqli_num_rows($get_barang) > 0) {
                                while ($barang = mysqli_fetch_assoc($get_barang)) {
                                    $id_barang = $barang['id_barang'];
                                    $nama_barang = $barang['nama_barang'];

                                    // Generate options for the dropdown
                                    echo "<option value='$id_barang'>$nama_barang</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No barang available</option>";
                            }
                            ?>
                        </select>
                        </fieldset>
                        </div>
                        <div class="col-md-4">
                        <fieldset class="form-group">
                        <label>Durasi : </label>
                        <select class="form-control" id="durasi" name="durasi">
                            <option value="" selected disabled>Pilih Durasi</option>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="20harian">20 Harian</option>
                        </select>
                        </fieldset>
                        </div>
                        </div>
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary">Hitung</button>
                    </form>
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
</body>
</html>


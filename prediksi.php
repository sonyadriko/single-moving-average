<?php
 include 'koneksi.php';
 session_start();
  if (!isset($_SESSION['id_admin'])) {
      header("Location: login.php");
  }
  // Fetch distinct dates from the database
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

<?php
        // Fetch distinct dates from the database
        $get_dates = mysqli_query($conn, "SELECT DISTINCT tanggal FROM penjualan ORDER BY tanggal");

        // Store unique dates in an array
        $unique_dates = array();
        while ($date = mysqli_fetch_assoc($get_dates)) {
            $unique_dates[] = $date['tanggal'];
        }
    ?>

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
                    <form method="GET" action="perhitungan.php" id="hitungForm">
                        <div class="row">
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label class="mb-3">Nama Barang : </label>
                                    <select class="form-control" id="nama_barang" name="nama_barang">
                                        <option value="" selected disabled>Pilih Barang</option>
                                        <?php
                                        $get_barang = mysqli_query($conn, "SELECT DISTINCT id_penjualan, nama_barang FROM penjualan");

                                        $unique_barang = array();
                                        while ($barang = mysqli_fetch_assoc($get_barang)) {
                                            $id_barang = $barang['id_penjualan'];
                                            $nama_barang = $barang['nama_barang'];
                                        
                                            // Menyaring hasil yang unik
                                            if (!in_array($nama_barang, $unique_barang)) {
                                                $unique_barang[] = $nama_barang;
                                        
                                                // Generate options for the dropdown
                                                echo "<option value='$nama_barang'>$nama_barang</option>";
                                            }
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
                                        <option value="3hari">3 Hari</option>
                                        <option value="7hari">7 Hari</option>
                                        <option value="20harian">20 Hari</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label>Bulan : </label>
                                    <select class="form-control" id="bulan" name="bulan">
                                        <option value="" selected disabled>Pilih Bulan</option>
                                        <?php
                                        // Generate options for the dropdown (January to December)
                                        $months = [
                                            "January", "February", "March", "April", "May", "June",
                                            "July", "August", "September", "October", "November", "December"
                                        ];

                                        foreach ($months as $month) {
                                            echo "<option value='$month'>$month</option>";
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label>Tanggal Awal : </label>
                                    <select class="form-control" id="tanggal_awal" name="tanggal_awal" onchange="updateTanggalAkhirOptions()">
                                        <option value="" selected disabled>Pilih Tanggal Awal</option>
                                        <?php
                                        foreach ($unique_dates as $date) {
                                            echo "<option value='$date'>$date</option>";
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label>Tanggal Akhir : </label>
                                    <select class="form-control" id="tanggal_akhir" name="tanggal_akhir">
                                        <option value="" selected disabled>Pilih Tanggal Akhir</option>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <!-- Submit button -->
                        <button type="button" class="btn btn-primary" onclick="hitung()">Hitung</button>
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
<script src="proses.js"></script>
<script>
    // Pass PHP data to your JavaScript functions
    var uniqueDates = <?php echo json_encode($unique_dates); ?>;
    // Call a function in your script to initialize with the data
    initScript(uniqueDates);
</script>
</body>


</html>


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
                    <form method="GET" action="perhitungan.php" id="hitungForm">
    <div class="row">
        <div class="col-md-4">
            <fieldset class="form-group">
                <label class="mb-3">Nama Barang : </label>
                <select class="form-control" id="nama_barang" name="nama_barang">
                    <option value="" selected disabled>Pilih Barang</option>
                    <?php
                    // Fetch data from the database
                    // $get_barang = mysqli_query($conn, "SELECT * FROM penjualan");
                    $get_barang = mysqli_query($conn, "SELECT DISTINCT id_penjualan, nama_barang FROM penjualan");

                    $unique_barang = array();
                    while ($barang = mysqli_fetch_assoc($get_barang)) {
                        $id_barang = $barang['id_barang'];
                        $nama_barang = $barang['nama_barang'];
                    
                        // Menyaring hasil yang unik
                        if (!in_array($nama_barang, $unique_barang)) {
                            $unique_barang[] = $nama_barang;
                    
                            // Generate options for the dropdown
                            echo "<option value='$id_barang'>$nama_barang</option>";
                        }
                    }
                    
                    // Check if there are rows in the result
                    // if (mysqli_num_rows($get_barang) > 0) {
                    //     while ($barang = mysqli_fetch_assoc($get_barang)) {
                    //         $id_barang = $barang['id_barang'];
                    //         $nama_barang = $barang['nama_barang'];

                    //         // Generate options for the dropdown
                    //         echo "<option value='$id_barang'>$nama_barang</option>";
                    //     }
                    // } else {
                    //     echo "<option value='' disabled>No barang available</option>";
                    // }
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

                    foreach ($months as $index => $month) {
                        echo "<option value='" . ($index + 1) . "'>$month</option>";
                    }
                    ?>
                </select>
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-group">
                <label>Tanggal Awal : </label>
                <select class="form-control" id="tanggal_awal" name="tanggal_awal">
                    <option value="" selected disabled>Pilih Tanggal Awal</option>
                    <?php
                    // Generate options for the dropdown (1 to 30)
                    for ($i = 1; $i <= 30; $i++) {
                        echo "<option value='$i'>$i</option>";
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

<script>
    // Function to update the options for Tanggal Akhir based on selected Tanggal Awal
    function updateTanggalAkhirOptions() {
        var tanggalAwal = document.getElementById("tanggal_awal");
        var tanggalAkhir = document.getElementById("tanggal_akhir");

        // Clear existing options
        tanggalAkhir.innerHTML = '<option value="" selected disabled>Pilih Tanggal Akhir</option>';

        // Get the selected value of Tanggal Awal
        var selectedTanggalAwal = tanggalAwal.value;

        // Generate options for Tanggal Akhir based on Tanggal Awal
        for (var i = parseInt(selectedTanggalAwal) + 1; i <= 30; i++) {
            tanggalAkhir.innerHTML += '<option value="' + i + '">' + i + '</option>';
        }
    }

    // Function to be called when Durasi, Bulan, or Tanggal Awal is changed
    function hitung() {
        // Add your logic here to handle the calculation
        // You can retrieve selected values using document.getElementById("element_id").value
        // For example:
        var namaBarang = document.getElementById("nama_barang").value;
        var durasi = document.getElementById("durasi").value;
        var bulan = document.getElementById("bulan").value;
        var tanggalAwal = document.getElementById("tanggal_awal").value;
        var tanggalAkhir = document.getElementById("tanggal_akhir").value;

        // You can use these values to perform the calculation or send them to the server for processing
        // Example: You might want to use AJAX to send the data to the server
        // For simplicity, I'll just submit the form for now
        document.getElementById("hitungForm").submit();
    }

    // Add event listeners to update Tanggal Akhir options when Tanggal Awal is changed
    document.getElementById("tanggal_awal").addEventListener("change", updateTanggalAkhirOptions);
</script>



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


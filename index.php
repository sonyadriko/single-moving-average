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
<title>Dashboard</title>

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
    <section class="content-header">
      <h1>Dashboard</h1>
    </section>
    
    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
        <!-- <div class="col-lg-4 col-xs-6">
          <div class="media-box">
            <div class="media-icon pull-left"><i class="icon-bargraph"></i> </div>
            <div class="media-info">
              
              <h5>Data Barang</h5>
              <h3>
              <?php // Query to get total items
              $sql = "SELECT COUNT(*) AS jumlah FROM barang";
              $resultBarang = $conn->query($sql); 
              $hasilBarang = mysqli_fetch_array($resultBarang);
              echo "{$hasilBarang['jumlah']}";?>
              </h3>
            </div>
          </div>
        </div> -->
        <div class="col-lg-6 col-xs-6">
          <div class="media-box bg-sea">
            <div class="media-icon pull-left"><i class="icon-wallet"></i> </div>
            <div class="media-info">
              <h5>Data Penjualan</h5>
              <h3>
              <?php // Query to get total items
              $sql = "SELECT COUNT(*) AS jumlah FROM penjualan";
              $resultBarang = $conn->query($sql); 
              $hasilBarang = mysqli_fetch_array($resultBarang);
              echo "{$hasilBarang['jumlah']}";?>
              </h3>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-xs-6">
          <div class="media-box bg-blue">
            <div class="media-icon pull-left"><i class="icon-basket"></i> </div>
            <div class="media-info">
              <h5>Data User</h5>
              <h3>
              <?php // Query to get total items
              $sql = "SELECT COUNT(*) AS jumlah FROM admin";
              $resultBarang = $conn->query($sql); 
              $hasilBarang = mysqli_fetch_array($resultBarang);
              echo "{$hasilBarang['jumlah']}";?>
              </h3>
            </div>
          </div>
        </div>
        <!-- <div class="col-lg-3 col-xs-6">
          <div class="media-box bg-green">
            <div class="media-icon pull-left"><i class="icon-layers"></i> </div>
            <div class="media-info">
              <h5>New Orders</h5>
              <h3>5324</h3>
            </div>
          </div>
        </div> -->
      </div>
      
      <div class="row">
        <div class="col-lg-12">
          <div class="chart-box"> 
            
          
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
                <div class="message-widget">
                  <div>
                    <!-- <div class="user-img pull-left"> <img src="dist/img/img3.jpg" class="img-circle img-responsive" alt="User Image"> </div> -->
                    <div class="mail-contnet">
                      <h5>Selamat Datang <?php 
                      if($_SESSION['role'] == '1')
                      echo "Admin"; 
                    elseif($_SESSION['role'] == '2')
                    echo "Pemilik"
                      ?></h5>
                      <p>Aplikasi prediksi penjualan dengan metode Single Moving Average.</p>
                      <!-- <span class="time m-bot-2">10:30 AM</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
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

<!--charts--> 
<script src="plugins/charts/code/modules/exporting.js"></script> 
<script src="plugins/charts/chart-functions.js"></script>
</body>
</html>
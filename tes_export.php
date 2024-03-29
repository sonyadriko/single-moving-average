<?php
 include 'koneksi.php';
 session_start();
  if (!isset($_SESSION['id_admin'])) {
      header("Location: login.php");
  }
  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
<body>
  <div class="wrapper"> 
    <!-- Content Wrapper. Contains page content -->
    <div> 
      <!-- Main content -->
      <section class="content container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="chart-box">
              <h4>Data History Peramalan</h4>
              <div id="example_filter" class="dataTables_filter pull-right">
                <input class="form-control" id="placeholderInput" placeholder="Search" type="email">
              </div>
              <!-- <button class="btn btn-primary btn-user" onclick="exportToPDF()">Export to PDF</button> -->
              <!-- <form id="exportForm" action="export_data_pdf.php" method="post" target="_blank">
                  <button type="submit" class="btn btn-primary btn-user" onclick="return previewPDF()">Export to PDF</button>
              </form> -->
              <table class="table table-responsive">
                <thead>
                  <tr>
                    <th class="sortable">No</th>
                    <th class="sortable">Nama barang</th>
                    <th class="sortable">Durasi</th>
                    <th class="sortable">Tanggal Awal</th>
                    <th class="sortable">Tanggal Akhir</th>
                    <th class="sortable">Tanggal Hasil</th>
                    <th class="sortable">Data Ramal</th>
                    <th class="sortable">MAPE</th>
                    <!-- <th class="sortable">Action</th> -->
                  </tr>
                </thead>
                <tr>
                  <?php 
                  $no = 1;
                  $get_data = mysqli_query($conn, "select * from peramalan");
                  while($display = mysqli_fetch_array($get_data)) {
                      $id = $display['id_peramalan'];
                      $nama_barang = $display['barang'];
                      $durasi = $display['durasi'];
                      $tanggal_awal = $display['tanggal_awal'];
                      $tanggal_akhir = $display['tanggal_akhir'];
                      $tanggal_hasil = $display['tanggal_hasil'];
                      $data_ramal = $display['data_ramal'];
                      $mape = $display['mape'];
                  ?>
                  <td class="text-truncate"><?php echo $no ?></td>
                  <td class="text-truncate"><?php echo $nama_barang ?></td>
                  <td class="text-truncate"><?php echo $durasi ?></td>
                  <td class="text-truncate"><?php echo date("d F Y", strtotime($tanggal_awal)) ;?></td>
                  <td class="text-truncate"><?php echo date("d F Y", strtotime($tanggal_akhir)) ;?></td>
                  <td class="text-truncate"><?php echo date("d F Y", strtotime($tanggal_hasil)) ;?></td>
                  <td class="text-truncate"><?php echo $data_ramal ?></td>
                  <td class="text-truncate"><?php echo $mape ?>%</td>
                  <!-- <td class="text-truncate">
                      <a href='delete_history.php?Del=<?php echo $id ?>' style="text-decoration: none; list-style: none;"><input type='submit' value='Hapus' id='delbtn' class="btn btn-primary btn-user" ></a>                       
                      <!-- <a href='delete_history.php?Del=<?php echo $id ?>' style="text-decoration: none; list-style: none;"><input type='submit' value='Cetak' id='delbtn' class="btn btn-primary btn-user" ></a>              
                  </td> -->
                </tr>
                <?php
                $no++;
                  }
                ?>
              </table>
              <!-- <ul class="pagination m-bot-0">
                <li> <a href="#" aria-label="Previous"> <span aria-hidden="true">«</span> </a> </li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li> <a href="#" aria-label="Next"> <span aria-hidden="true">»</span> </a> </li>
              </ul> -->
            </div>
          </div>
        </div>
      </section>
      <!-- content --> 
    </div>
  </div>
  <!-- jQuery --> 
  <script src="dist/js/jquery.min.js"></script> 
  <script src="bootstrap/js/bootstrap.min.js"></script> 
  <script src="dist/js/ovio.js"></script> 
  <script src="plugins/tables/jquery.tablesort.js"></script> 
  <script>
    window.print();
    </script>

  <script type="text/javascript">
  (function($) {
    "use strict";
  $("table").tablesort();
  })(jQuery);
  </script>
</body>
</html>
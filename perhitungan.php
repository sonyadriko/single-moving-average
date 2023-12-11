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
        <section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="chart-box">
                <h4>Perhitungan</h4>

                <?php
                // Include the connection file
                include 'koneksi.php';

                // Fetch data from the database
                $get_barang = mysqli_query($conn, "SELECT * FROM barang");

                // Display the form to select product and duration
                ?>
                  <form method="GET" action="perhitungan.php" id="hitungForm">
    <div class="row">
        <div class="col-md-4">
            <fieldset class="form-group">
                <label class="mb-3">Nama Barang : </label>
                <select class="form-control" id="nama_barang" name="nama_barang">
                    <option value="" selected disabled>Pilih Barang</option>
                    <?php
                    // Fetch data from the database
                    // $get_barang = mysqli_query($conn, "SELECT * FROM barang");

                    // // Check if there are rows in the result
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


                <?php
                // Check if form is submitted
                if (isset($_GET['nama_barang']) && isset($_GET['durasi']) && isset($_GET['bulan']) && isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
                    // Retrieve selected product and duration
                    $id_barang = $_GET['nama_barang'];
                    $durasi = $_GET['durasi'];
                    $selected_month = $_GET['bulan'];
                    $tanggal_awal = $_GET['tanggal_awal'];
                    $tanggal_akhir = $_GET['tanggal_akhir'];

                    // Fetch historical sales data for the selected product
                    $get_sales_data = mysqli_query($conn, "SELECT * FROM penjualan WHERE nama_barang = '$id_barang' AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ORDER BY tanggal ASC");
                    
                    $sales_data = array();
                        while ($row = mysqli_fetch_assoc($get_sales_data)) {
                            $sales_data[] = $row;  // Menyimpan seluruh baris sebagai array asosiatif
                        }

                    // Calculate SMA based on the chosen duration
                    switch ($durasi) {
                        case '3hari':
                            $period = 1;
                            break;
                        case '7hari':
                            $period = 7;
                            break;
                        case '20harian':
                            $period = 20;
                            break;
                        default:
                            $period = 1;
                            break;
                    }

                    echo "<p>Results for $durasi period:</p>";

                    if ($durasi == '3hari') {
                        // Assuming $tanggal_awal and $tanggal_akhir are the selected start and end dates
                        
                       
                        // Display the filtered sales data in a table
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Daily Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";
                        $daily_averages = [];
                        $actual_sales = [];

                        // $sdate = $start_date + 2;
                        // var_dump($sdate);
                
                        for ($i = 0; $i < count($sales_data); $i++) {
                            if ($i < 3) {
                                $daily_average = null;
                                $mape = null;
                            } else {
                                $average_sales = array_slice($sales_data, $i - 3, 4);
                                $daily_average = array_sum(array_column($average_sales, 'jumlah')) / count($average_sales);
                                $mape = abs(($sales_data[$i]['jumlah'] - $daily_average) / $sales_data[$i]['jumlah']) * 100;

                                // Populate the arrays with daily averages and actual sales
                                $daily_averages[] = number_format($daily_average, 2);
                                $actual_sales[] = $sales_data[$i]['jumlah'];

                                // Calculate the overall daily average
                                $overall_daily_average = array_sum($daily_averages) / count($daily_averages);
                                $rounded_overall_daily_average = number_format($overall_daily_average, 1);
                                
        
                            }
                
                            echo "<tr>";
                            echo "<td>" . $sales_data[$i]['tanggal'] . " " . $selected_month . "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                            echo "<td>" . ($daily_average !== null ? number_format($daily_average, 2) : 'N/A') . "</td>";
                            echo "<td>" . ($mape !== null ? number_format($mape, 2) . "%" : 'N/A') . "</td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td>" .$tanggal_akhir + '1' . " ". $selected_month ."</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_daily_average . "</td>";
                        if (count($actual_sales) > 0) {
                            $mape_overall = array_sum(array_map(function($actual, $daily_avg) {
                                // Check if daily_avg is not null to avoid division by zero
                                return $daily_avg !== null ? abs(($actual - $daily_avg) / $actual) * 100 : null;
                            }, $actual_sales, $daily_averages)) / count($actual_sales);
                        
                            echo "<td>" . ($mape_overall !== null ? number_format($mape_overall, 2) . "%" : 'N/A') . "</td>";
                        } else {
                            echo "<td>N/A</td>";
                        }
                        echo "</tr>";
                
                        echo "</tbody>";
                        echo "</table>";
                
                        // Add the chart
                        echo "<canvas id='dailyAverageChart' width='400' height='200'></canvas>";
                        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                        echo "<script>";
                        echo "var ctx = document.getElementById('dailyAverageChart').getContext('2d');";

                        echo "var dates = [];";
                        echo "var dailyAverages = [];";
                        echo "var actualSales = [];";

                        // Populate the dates, daily averages, and actual sales arrays for the chart
                        foreach ($sales_data as $key => $data) {
                            if ($key >= 3) {
                                $average_sales = array_slice($sales_data, $key - 3, 4);
                                $daily_average = array_sum(array_column($average_sales, 'jumlah')) / count($average_sales);

                                // Calculate the overall daily average within the loop
                                $overall_daily_average = array_sum($daily_averages) / count($daily_averages);
                                $rounded_overall_daily_average = number_format($overall_daily_average, 1);

                                // Populate the arrays with daily averages and actual sales
                                echo "dates.push('" . $data['tanggal'] . "');";
                                echo "dailyAverages.push(" . number_format($daily_average, 2) . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }

                        // Manually add the last date and the overall daily average to ensure they connect
                        echo "dates.push('".$tanggal_akhir + '1'."');";
                        echo "dailyAverages.push(" . $rounded_overall_daily_average . ");";
                        echo "actualSales.push(null);"; // Assuming you want to show null for Actual Sales on the 31st

                        echo "var myChart = new Chart(ctx, {";
                        echo "type: 'line',";
                        echo "data: {";
                        echo "labels: dates,";
                        echo "datasets: [{";
                        echo "label: 'Daily Averages',";
                        echo "data: dailyAverages,";
                        echo "backgroundColor: 'rgba(75, 192, 192, 0.2)',";
                        echo "borderColor: 'rgba(75, 192, 192, 1)',";
                        echo "borderWidth: 1";
                        echo "}, {";
                        echo "label: 'Actual Sales',";
                        echo "data: actualSales,";
                        echo "backgroundColor: 'rgba(255, 99, 132, 0.2)',";
                        echo "borderColor: 'rgba(255, 99, 132, 1)',";
                        echo "borderWidth: 1";
                        echo "}]},";  // close datasets and data
                        echo "options: {";
                        echo "scales: {";
                        echo "y: {";
                        echo "beginAtZero: true";
                        echo "}";
                        echo "}";
                        echo "}";
                        echo "});";
                        echo "</script>";
                    }
                    

                    elseif ($durasi == '7hari') {
                        // Display the results in a table
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";
                    
                        // Initialize arrays to store weekly averages and MAPE
                        $weekly_averages = [];
                        $mape_values = [];
                    
                        // Calculate moving average considering today and the six days before for weekly duration
                        for ($i = 0; $i < count($sales_data); $i++) {
                            echo "<tr>";
                            echo "<td>" . $sales_data[$i]['tanggal'] . " " . $selected_month . "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                    
                            // Check if there are enough data points to calculate the moving average
                            if ($i < 6) {
                                $weekly_average = 'N/A'; // Set to 'N/A' or any default value for the first six days
                                $mape = 'N/A'; // Set to 'N/A' for the first six days
                            } else {
                                $average_sales = array_slice($sales_data, $i - 6, 7);
                                $weekly_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);
                    
                                // Calculate MAPE starting from the seventh day
                                $mape = number_format(abs(($sales_data[$i]['jumlah'] - $weekly_average) / $sales_data[$i]['jumlah']) * 100, 2);
                    
                                // Populate the arrays with weekly averages and MAPE
                                $weekly_averages[] = $weekly_average;
                                $mape_values[] = $mape;
                            }
                    
                            echo "<td>" . $weekly_average . "</td>";
                            echo "<td>" . ($i < 6 ? 'N/A' : $mape . "%") . "</td>";
                    
                            echo "</tr>";
                        }
                    
                        // Calculate the overall weekly average after processing all days
                        $overall_weekly_average = array_sum($weekly_averages) / count($weekly_averages);
                        $rounded_overall_weekly_average = number_format($overall_weekly_average, 1);
                    
                        echo "<tr>";
                        echo "<td>" .$tanggal_akhir + '1' . " ". $selected_month ."</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_weekly_average . "</td>";
                        echo "<td>N/A</td>";
                        echo "</tr>";
                    
                        echo "</tbody>";
                        echo "</table>";
                    
                        // Add the chart
                        echo "<canvas id='weeklyAverageChart' width='400' height='200'></canvas>";
                        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                        echo "<script>";
                        echo "var ctx = document.getElementById('weeklyAverageChart').getContext('2d');";
                    
                        echo "var dates = [];";
                        echo "var weeklyAverages = [];";
                        echo "var actualSales = [];";
                    
                        // Populate the dates, weekly averages, and actual sales arrays for the chart
                        foreach ($sales_data as $key => $data) {
                            if ($key >= 6) {
                                $average_sales = array_slice($sales_data, $key - 6, 7);
                                $weekly_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);
                    
                                $overall_weekly_average = array_sum($weekly_averages) / count($weekly_averages);
                                $rounded_overall_weekly_average = number_format($overall_weekly_average, 1);
                                // Populate the arrays with weekly averages and actual sales
                                echo "dates.push('" . $data['tanggal'] . "');";
                                echo "weeklyAverages.push(" . $weekly_average . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }
                        echo "dates.push('".$tanggal_akhir + '1'."');";
                        echo "weeklyAverages.push(" . $rounded_overall_weekly_average . ");";
                        echo "actualSales.push(null);"; // Assuming you want to show null for Actual Sales on the 31st
                    
                        echo "var myChart = new Chart(ctx, {";
                        echo "type: 'line',";
                        echo "data: {";
                        echo "labels: dates,";
                        echo "datasets: [{";
                        echo "label: 'Weekly Averages',";
                        echo "data: weeklyAverages,";
                        echo "backgroundColor: 'rgba(75, 192, 192, 0.2)',";
                        echo "borderColor: 'rgba(75, 192, 192, 1)',";
                        echo "borderWidth: 1";
                        echo "}, {";
                        echo "label: 'Actual Sales',";
                        echo "data: actualSales,";
                        echo "backgroundColor: 'rgba(255, 99, 132, 0.2)',";
                        echo "borderColor: 'rgba(255, 99, 132, 1)',";
                        echo "borderWidth: 1";
                        echo "}]},";  // close datasets and data
                        echo "options: {";
                        echo "scales: {";
                        echo "y: {";
                        echo "beginAtZero: true";
                        echo "}";
                        echo "}";
                        echo "}";
                        echo "});";
                        echo "</script>";
                    }
                    
                    elseif ($durasi == '20harian') {
                        // Display the results in a table
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";
                    
                        // Initialize arrays to store 20-day averages and MAPE
                        $twenty_day_averages = [];
                        $mape_values = [];
                    
                        // Calculate moving average considering today and the 19 days before for 20-day duration
                        for ($i = 0; $i < count($sales_data); $i++) {
                            echo "<tr>";
                            echo "<td>" . ($i + 1) . " " . $selected_month . "</td>"; // Assuming days are numbered from 1 to 30
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                    
                            // Check if there are enough data points to calculate the moving average
                            if ($i < 19) {
                                $twenty_day_average = 'N/A'; // Set to 'N/A' or any default value for the first 19 days
                                $mape = 'N/A'; // Set to 'N/A' for the first 19 days
                            } else {
                                $average_sales = array_slice($sales_data, $i - 19, 20);
                                $twenty_day_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);
                    
                                // Calculate MAPE starting from the 20th day
                                $mape = number_format(abs(($sales_data[$i]['jumlah'] - $twenty_day_average) / $sales_data[$i]['jumlah']) * 100, 2);
                    
                                // Populate the arrays with 20-day averages and MAPE
                                $twenty_day_averages[] = $twenty_day_average;
                                $mape_values[] = $mape;
                            }
                    
                            echo "<td>" . $twenty_day_average . "</td>";
                    
                            // Set MAPE to 'N/A' for the first 19 days
                            echo "<td>" . ($i < 19 ? 'N/A' : $mape . "%") . "</td>";
                    
                            echo "</tr>";
                        }
                    
                        // Calculate the overall 20-day average after processing all days
                        $overall_twenty_day_average = array_sum($twenty_day_averages) / count($twenty_day_averages);
                        $rounded_overall_twenty_day_average = number_format($overall_twenty_day_average, 1);
                    
                        // Display the row for October 1, 2023
                        echo "<tr>";
                        echo "<td>" .$tanggal_akhir + '1' . " ". $selected_month ."</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_twenty_day_average . "</td>";
                        echo "<td>N/A</td>";
                        echo "</tr>";
                    
                        echo "</tbody>";
                        echo "</table>";
                    
                        // Add the chart
                        echo "<canvas id='twentyDayAverageChart' width='400' height='200'></canvas>";
                        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                        echo "<script>";
                        echo "var ctx = document.getElementById('twentyDayAverageChart').getContext('2d');";
                    
                        echo "var dates = [];";
                        echo "var twentyDayAverages = [];";
                        echo "var actualSales = [];";
                    
                        // Populate the dates, 20-day averages, and actual sales arrays for the chart
                        foreach ($sales_data as $key => $data) {
                            if ($key >= 19) {
                                $average_sales = array_slice($sales_data, $key - 19, 20);
                                $twenty_day_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);

                                $overall_twenty_day_average = array_sum($twenty_day_averages) / count($twenty_day_averages);
                                $rounded_overall_twenty_day_average = number_format($overall_twenty_day_average, 1);
                    
                                // Populate the arrays with 20-day averages and actual sales
                                echo "dates.push('" . $data['tanggal'] . "');";
                                echo "twentyDayAverages.push(" . $twenty_day_average . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }

                        echo "dates.push('".$tanggal_akhir + '1'."');";
                        echo "twentyDayAverages.push(" . $rounded_overall_twenty_day_average . ");";
                        echo "actualSales.push(null);"; // Assuming you want to show null for Actual Sales on the 31st
                    
                        echo "var myChart = new Chart(ctx, {";
                        echo "type: 'line',";
                        echo "data: {";
                        echo "labels: dates,";
                        echo "datasets: [{";
                        echo "label: '20-Day Averages',";
                        echo "data: twentyDayAverages,";
                        echo "backgroundColor: 'rgba(75, 192, 192, 0.2)',";
                        echo "borderColor: 'rgba(75, 192, 192, 1)',";
                        echo "borderWidth: 1";
                        echo "}, {";
                        echo "label: 'Actual Sales',";
                        echo "data: actualSales,";
                        echo "backgroundColor: 'rgba(255, 99, 132, 0.2)',";
                        echo "borderColor: 'rgba(255, 99, 132, 1)',";
                        echo "borderWidth: 1";
                        echo "}]},";  // close datasets and data
                        echo "options: {";
                        echo "scales: {";
                        echo "y: {";
                        echo "beginAtZero: true";
                        echo "}";
                        echo "}";
                        echo "}";
                        echo "});";
                        echo "</script>";
                    }
                    
                }
                ?>
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

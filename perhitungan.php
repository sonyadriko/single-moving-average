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
                <form method="GET" action="perhitungan.php">
                    <div class="row">
                        <div class="col-md-4">
                            <fieldset class="form-group">
                                <label class="mb-3">Nama Barang : </label>
                                <select class="form-control" id="nama_barang" name="nama_barang">
                                    <option value="" selected disabled>Pilih Barang</option>

                                    <?php
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

                <?php
                // Check if form is submitted
                if (isset($_GET['nama_barang']) && isset($_GET['durasi'])) {
                    // Retrieve selected product and duration
                    $id_barang = $_GET['nama_barang'];
                    $durasi = $_GET['durasi'];

                    // Fetch historical sales data for the selected product
                    $get_sales_data = mysqli_query($conn, "SELECT * FROM penjualan WHERE id_barang = $id_barang ORDER BY tanggal ASC");

                    $sales_data = array();
                    while ($row = mysqli_fetch_assoc($get_sales_data)) {
                        $sales_data[] = $row['jumlah'];
                    }

                    // Calculate single moving average based on the chosen duration
                    function calculateSMA($data, $period) {
                        $sma = array();
                        $total = 0;

                        for ($i = 0; $i < $period; $i++) {
                            $total += $data[$i];
                        }

                        $sma[] = $total / $period;

                        for ($i = $period; $i < count($data); $i++) {
                            $total = $total - $data[$i - $period] + $data[$i];
                            $sma[] = $total / $period;
                        }

                        return $sma;
                    }

                    // Calculate MAPE (Mean Absolute Percentage Error)
                    function calculateMAPE($actual, $forecast) {
                        $totalError = 0;

                        for ($i = 0; $i < count($actual); $i++) {
                            $totalError += abs(($actual[$i] - $forecast[$i]) / $actual[$i]) * 100;
                        }

                        return $totalError / count($actual);
                    }

                    // Calculate SMA based on the chosen duration
                    switch ($durasi) {
                        case 'harian':
                            $period = 1;
                            break;
                        case 'mingguan':
                            $period = 7;
                            break;
                        case '20harian':
                            $period = 20;
                            break;
                        default:
                            $period = 1;
                            break;
                    }

                    $sma_values = calculateSMA($sales_data, $period);

                    // Calculate MAPE and forecast for the next period
                    $actual_data = array_slice($sales_data, $period);
                    $mape = calculateMAPE($actual_data, array_slice($sma_values, 0, count($actual_data)));
                    $next_forecast = end($sma_values);

                    
                    $get_sales_data = mysqli_query($conn, "SELECT tanggal, jumlah FROM penjualan WHERE id_barang = $id_barang ORDER BY tanggal ASC");

                    $sales_data = array();
                    while ($row = mysqli_fetch_assoc($get_sales_data)) {
                        $sales_data[] = $row;
                    }

                                    

                    echo "<p>Results for $durasi period:</p>";

                    if ($durasi == 'harian') {
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Daily Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";

                        // Initialize an array to store daily averages
                        $daily_averages = [];

                        // Calculate daily moving average considering today and the three days before
                        for ($i = 0; $i < count($sales_data); $i++) {
                            if ($i < 3) {
                                $daily_average = null;
                                $mape = null;
                            } else {
                                $average_sales = array_slice($sales_data, $i - 3, 4);
                                $daily_average = array_sum(array_column($average_sales, 'jumlah')) / count($average_sales);
                                $mape = abs(($sales_data[$i]['jumlah'] - $daily_average) / $sales_data[$i]['jumlah']) * 100;

                                // Populate the array with daily averages
                                $daily_averages[] = $daily_average;
                            }

                            echo "<tr>";
                            echo "<td>" . $sales_data[$i]['tanggal'] . " September 2023" .  "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                            echo "<td>" . ($daily_average !== null ? number_format($daily_average, 2) : 'N/A') . "</td>";
                            echo "<td>" . ($mape !== null ? number_format($mape, 2) . "%" : 'N/A') . "</td>";
                            echo "</tr>";

                            // Calculate daily moving average for the next day (1 Oktober 2023)
                            if ($i == count($sales_data) - 1 && $i >= 2) {
                                // Calculate the average of the daily averages
                                $overall_daily_average = array_sum($daily_averages) / count($daily_averages);
                            
                                // Round the overall daily average to one decimal place
                                $rounded_overall_daily_average = number_format($overall_daily_average, 1);
                            
                                echo "<tr>";
                                echo "<td>1 Oktober 2023</td>";
                                echo "<td>N/A</td>";
                                echo "<td>" . $rounded_overall_daily_average . "</td>";
                                echo "<td>N/A</td>";
                                echo "</tr>";
                            }
                            
                            
                        }

                        echo "</tbody>";
                        echo "</table>";

                        // Add the chart
                        echo "<canvas id='dailyAverageChart' width='400' height='200'></canvas>";
                        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                        echo "<script>";
                        echo "var ctx = document.getElementById('dailyAverageChart').getContext('2d');";

                        echo "var dates = [];";
                        echo "var dailyAverages = [];";

                        // Populate the dates and daily averages array for the chart
                        foreach ($sales_data as $key => $data) {
                            if ($key >= 3) {
                                $average_sales = array_slice($sales_data, $key - 3, 4);
                                $daily_average = array_sum(array_column($average_sales, 'jumlah')) / count($average_sales);

                                echo "dates.push('" . $data['tanggal'] . "');";
                                echo "dailyAverages.push(" . number_format($daily_average, 2) . ");";
                            }
                        }

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
                        echo "}]";
                        echo "},";
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

                    elseif ($durasi == 'mingguan') {
                        // Display the results in a table
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";

                        // Calculate moving average considering today and the six days before for weekly duration
                        for ($i = 0; $i < count($sales_data); $i++) {
                            echo "<tr>";
                            // echo "<td>" . ($i + 1) . "</td>"; // Assuming days are numbered from 1 to 30
                            echo "<td>" . $sales_data[$i]['tanggal'] . " September 2023" .  "</td>";
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
                            }

                            echo "<td>" . $weekly_average . "</td>";

                            // Set MAPE to 'N/A' for the first six days
                            echo "<td>" . ($i < 6 ? 'N/A' : $mape . "%") . "</td>";

                            // echo "<td>" . $next_forecast . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } elseif ($durasi == '20harian') {
                        // Display the results in a table
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";

                        // Calculate moving average considering today and the 19 days before for 20-day duration
                        for ($i = 0; $i < count($sales_data); $i++) {
                            echo "<tr>";
                            echo "<td>" . ($i + 1) . "</td>"; // Assuming days are numbered from 1 to 30
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
                            }

                            echo "<td>" . $twenty_day_average . "</td>";

                            // Set MAPE to 'N/A' for the first 19 days
                            echo "<td>" . ($i < 19 ? 'N/A' : $mape . "%") . "</td>";

                            // echo "<td>" . $next_forecast . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
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

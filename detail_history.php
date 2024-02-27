<?php
include 'koneksi.php'; session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Detail History</title>
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
        $id_detail_history = $_GET['Id'];
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
        <section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="chart-box">
                <h4>Perhitungan</h4>
                <?php
                include 'koneksi.php';
                $get_barang = mysqli_query($conn, "SELECT * FROM barang");?>
                <?php
                    $sql = "SELECT * FROM peramalan
                            JOIN BARANG ON peramalan.BARANG = BARANG.NAMA_BARANG
                            WHERE peramalan.id_peramalan = '$id_detail_history'";
                            // var_dump($sql);
                    $prosessql = mysqli_query($conn, $sql);

                    if ($prosessql) {
                        while ($row = mysqli_fetch_assoc($prosessql)) {
                            $id_barang = $row['nama_barang'];  // Replace 'id_barang' with the actual column name
                            $durasi = $row['durasi'];        // Replace 'durasi' with the actual column name
                            $tanggal_awal = $row['tanggal_awal'];  // Replace 'tanggal_awal' with the actual column name
                            $tanggal_akhir = $row['tanggal_akhir'];
                        }
                    echo '</br><button id="printButton" class="btn btn-success" onclick="printIn()">Print</button>';
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
                    echo "<hr><p>Results for $durasi period:</p>";
                    if ($durasi == '3hari') {
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Daily Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";
                        $daily_averages = [];
                        $actual_sales = [];
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
                                $tanggal_baru = date("Y-m-d", strtotime($tanggal_akhir . " +1 day"));
                                
                                echo "<script>";
                                echo "var roundedOverallDailyAverage = " . json_encode($rounded_overall_daily_average) . ";";
                                echo "</script>";
                            }
                            echo "<tr>";
                            // echo "<td>" . $sales_data[$i]['tanggal'] . "</td>";
                            echo "<td>" . date("d F Y", strtotime($sales_data[$i]['tanggal'])) . "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                            echo "<td>" . ($daily_average !== null ? number_format($daily_average, 2) : 'N/A') . "</td>";
                            echo "<td>" . ($mape !== null ? number_format($mape, 2) . "%" : 'N/A') . "</td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        // echo "<td>" . $tanggal_baru . "</td>";
                        echo "<td>" . date("d F Y", strtotime($tanggal_baru)) . "</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_daily_average . "</td>";
                        if (count($actual_sales) > 0) {
                            $mape_overall = array_sum(array_map(function ($actual, $daily_avg) {
                                // Check if daily_avg is not null to avoid division by zero
                                return $daily_avg !== null ? abs(($actual - $daily_avg) / $actual) * 100 : null;
                            }, $actual_sales, $daily_averages)) / count($actual_sales);
                        
                            $mape_hasil = ($mape_overall !== null ? number_format($mape_overall, 2) : null);
                            echo "<td>" . ($mape_hasil !== null ? $mape_hasil . "%" : 'N/A') . "</td>";
                            echo "<script>";
                            echo "var mape_overall = " . json_encode(floatval($mape_hasil)) . ";";
                            echo "</script>";
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
                                echo "dates.push('" . date("d F Y", strtotime($data['tanggal'])) . "');";
                                echo "dailyAverages.push(" . number_format($daily_average, 2) . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }
                        // Manually add the last date and the overall daily average to ensure they connect
                        echo "dates.push('".date("d F Y", strtotime($tanggal_baru))."');";

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
                            // echo "<td>" . $sales_data[$i]['tanggal'] .  "</td>";
                            echo "<td>" . date("d F Y", strtotime($sales_data[$i]['tanggal'])) . "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                            // Check if there are enough data points to calculate the moving average
                            if ($i < 6) {
                                $weekly_average = 'N/A'; // Set to 'N/A' or any default value for the first six days
                                $mape = 'N/A'; // Set to 'N/A' for the first six days
                            } else {
                                $average_sales = array_slice($sales_data, $i - 6, 7);
                                $weekly_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);
                                // Calculate MAPE starting from the seventh day
                                $actual_sales[] = $sales_data[$i]['jumlah'];
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
                        $tanggal_baru = date("Y-m-d", strtotime($tanggal_akhir . " +1 day"));
                        echo "<script>";
                        echo "var roundedOverallWeeklyAverage = " . json_encode($rounded_overall_weekly_average) . ";";
                        echo "</script>";
                        echo "<tr>";
                        echo "<td>" . date("d F Y", strtotime($tanggal_baru)) . "</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_weekly_average . "</td>";
                        if (count($actual_sales) > 0) {
                            $mape_overall = array_sum(array_map(function($actual, $daily_avg) {
                                // Check if daily_avg is not null to avoid division by zero
                                return $daily_avg !== null ? abs(($actual - $daily_avg) / $actual) * 100 : null;
                            }, $actual_sales, $weekly_averages)) / count($actual_sales);
                            $mape_hasil = ($mape_overall !== null ? number_format($mape_overall, 2) : null);
                            echo "<td>" . ($mape_hasil !== null ? $mape_hasil . "%" : 'N/A') . "</td>";
                            echo "<script>";
                            echo "var mape_overall = " . json_encode(floatval($mape_hasil)) . ";";
                            echo "</script>";
                        } else {
                            echo "<td>N/A</td>";
                        }
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
                                echo "dates.push('" . date("d F Y", strtotime($data['tanggal'])) . "');";
                                echo "weeklyAverages.push(" . $weekly_average . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }
                        echo "dates.push('".date("d F Y", strtotime($tanggal_baru))."');";
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
                        echo "<table class='table'>";
                        echo "<thead><tr><th>Date</th><th>Actual Sales</th><th>Moving Average</th><th>MAPE</th></tr></thead>";
                        echo "<tbody>";
                        // Initialize arrays to store 20-day averages and MAPE
                        $twenty_day_averages = [];
                        $mape_values = [];
                        // Calculate moving average considering today and the 19 days before for 20-day duration
                        for ($i = 0; $i < count($sales_data); $i++) {
                            echo "<tr>";
                            echo "<td>" . date("d F Y", strtotime($sales_data[$i]['tanggal'])) . "</td>";
                            echo "<td>" . $sales_data[$i]['jumlah'] . "</td>";
                            // Check if there are enough data points to calculate the moving average
                            if ($i < 19) {
                                $twenty_day_average = 'N/A'; // Set to 'N/A' or any default value for the first 19 days
                                $mape = 'N/A'; // Set to 'N/A' for the first 19 days
                            } else {
                                $average_sales = array_slice($sales_data, $i - 19, 20);
                                $twenty_day_average = number_format(array_sum(array_column($average_sales, 'jumlah')) / count($average_sales), 2);
                                $actual_sales[] = $sales_data[$i]['jumlah'];
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
                        $tanggal_baru = date("Y-m-d", strtotime($tanggal_akhir . " +1 day"));
                        echo "<script>";
                        echo "var roundedOverallTwentyDayAverage = " . json_encode($rounded_overall_twenty_day_average) . ";";
                        echo "</script>";
                        // Display the row 
                        echo "<tr>";
                        echo "<td>" . date("d F Y", strtotime($tanggal_baru)) . "</td>";
                        echo "<td>N/A</td>";
                        echo "<td>" . $rounded_overall_twenty_day_average . "</td>";
                        if (count($actual_sales) > 0) {
                            $mape_overall = array_sum(array_map(function($actual, $daily_avg) {
                                // Check if daily_avg is not null to avoid division by zero
                                return $daily_avg !== null ? abs(($actual - $daily_avg) / $actual) * 100 : null;
                            }, $actual_sales, $twenty_day_averages)) / count($actual_sales);
                        
                            $mape_hasil = ($mape_overall !== null ? number_format($mape_overall, 2) : null);
                            echo "<td>" . ($mape_hasil !== null ? $mape_hasil . "%" : 'N/A') . "</td>";
                            echo "<script>";
                            echo "var mape_overall = " . json_encode(floatval($mape_hasil)) . ";";
                            echo "</script>";
                        } else {
                            echo "<td>N/A</td>";
                        }
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
                                echo "dates.push('" . date("d F Y", strtotime($data['tanggal'])) . "');";
                                echo "twentyDayAverages.push(" . $twenty_day_average . ");";
                                echo "actualSales.push(" . $data['jumlah'] . ");";
                            }
                        }

                        echo "dates.push('".date("d F Y", strtotime($tanggal_baru))."');";
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
        </div>
        <?php include 'footer.php' ?>
    </div>
    <script src="dist/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/ovio.js"></script>
    <script src="proses.js"></script>
    <script>
        function initUniqueDates() {
            // Pass PHP data to your JavaScript functions
            var uniqueDates = <?php echo json_encode($unique_dates); ?>;
            // Call a function in your script to initialize with the data
            console.log("Unique Dates:", uniqueDates);

            initScript(uniqueDates);
        }
    </script>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function printIn(){
     window.print();
    }   
</script>
<script>
    function saveData() {

        var urlParams = new URLSearchParams(window.location.search);

        // Retrieve values from the URL
        var namaBarang = urlParams.get("nama_barang");
        var durasi = urlParams.get("durasi");
        var tanggalAwal = urlParams.get("tanggal_awal");
        var tanggalAkh = urlParams.get("tanggal_akhir");
        var dateObj = new Date(tanggalAkh);
        dateObj.setDate(dateObj.getDate() + 1);
        var tanggalHasil = dateObj.toISOString().split('T')[0];
        var tanggalHasilAkhir = tanggalHasil;
        var moving_average;
        var mape;

        if (durasi == '3hari') {
            moving_average = urlParams.get("rounded_overall_daily_average") || roundedOverallDailyAverage || null;
            mape = urlParams.get("mape_overall") || mape_overall || null;
        } else if (durasi == '7hari') {
            moving_average = urlParams.get("rounded_overall_weekly_average") || roundedOverallWeeklyAverage || null;
            mape = urlParams.get("mape_overall") || mape_overall || null;
        } else if (durasi == '20harian') {
            moving_average = urlParams.get("rounded_overall_twenty_day_average") || roundedOverallTwentyDayAverage || null;
            mape = urlParams.get("mape_overall") || mape_overall || null;
        }

        console.log("namaBarang:", namaBarang);
        console.log("durasi:", durasi);
        console.log("tanggalAwal:", tanggalAwal);
        console.log("tanggalAkhir:", tanggalAkh);
        console.log("tanggalHasilAkhir:", tanggalHasil);
        console.log("movingAverage:", moving_average);
        console.log("mape:", mape);

        $.ajax({
            type: "POST",
            url: "save_data_script.php",
            data: {
                barang: namaBarang,
                durasi: durasi,
                tanggal_awal: tanggalAwal,
                tanggal_akhir: tanggalAkh,
                tanggal_hasil: tanggalHasilAkhir,
                data_ramal: moving_average,
                mape: mape,
                // ... (tambahkan data lain yang perlu disimpan)
            },
            success: function(response) {
                alert('Data saved successfully!');
                document.getElementById('saveButton').disabled = true;
            },
            error: function(error) {
                console.error('Error saving data:', error);
                // Tambahan logika atau penanganan jika terjadi kesalahan
            }
        });
    }
</script>
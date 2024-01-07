<?php
 include 'koneksi.php';
 include 'date_response.php';

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
<script src="plugins/charts/code/highcharts.js"></script>
<script src="proses.js"></script>


 <?php
        $unique_dates = array();
        if (!empty($_GET['nama_barang'])) {
            $nama_barang = $_GET['nama_barang'];
            $stmt = mysqli_prepare($conn, "SELECT DISTINCT tanggal FROM penjualan WHERE nama_barang = ? ORDER BY tanggal");
            mysqli_stmt_bind_param($stmt, "s", $nama_barang);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            // Store unique dates in the array
            while ($date = mysqli_fetch_assoc($result)) {
                $unique_dates[] = $date['tanggal'];
            }
        
            // Close the statement
            mysqli_stmt_close($stmt);
        }

        

    ?>
</head>

<body class="sidebar-mini">
<div class="wrapper"> 
  
<?php include 'header.php'?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include 'sidebar.php'?>
  
  <div class="content-wrapper"> 
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
                                    <select class="form-control" id="nama_barang" name="nama_barang" onchange="fetchDates()">
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
                            <script>
                                // function fetchDates() {
                                //     var selectedProduct = $('#nama_barang').val();

                                //     $.ajax({
                                //         type: 'GET',
                                //         url: 'fetch_dates.php', // The PHP file handling the AJAX request,
                                //         data: { nama_barang: selectedProduct },
                                //         success: function (data) {
                                //             // Update the "Tanggal Awal" dropdown with fetched dates
                                //             let result = JSON.parse(data);
                                //             let tanggalAwalDropdown = $('#tanggal_awal');

                                //             tanggalAwalDropdown.empty();
                                //             tanggalAwalDropdown.append('<option value="" selected disabled>Pilih Tanggal Awal</option>');
                                //             // console.log(JSON.stringify(result));
                                //             // console.log(result[0]['value']);
                                //             for (let index = 0; index < result.length; index++) {
                                //                 const date = result[index]['value'];
                                //                 // console.log(element);
                                //                 const formattedDate = formatDate(date);
                                //                 tanggalAwalDropdown.append('<option value="' + date + '">' + formattedDate + '</option>');
                                //             }
                                //         }

                                //     });
                                // }
                                var selectedDates = [];
                                function fetchDates() {
    var selectedProduct = $('#nama_barang').val();

    $.ajax({
        type: 'GET',
        url: 'fetch_dates.php',
        data: { nama_barang: selectedProduct },
        success: function (data) {
            let result = JSON.parse(data);

            let tanggalAwalDropdown = $('#tanggal_awal');
            tanggalAwalDropdown.empty();
            tanggalAwalDropdown.append('<option value="" selected disabled>Pilih Tanggal Awal</option>');

            // Clear the existing selectedDates array
            selectedDates = [];

            for (let index = 0; index < result.length; index++) {
                const date = result[index]['value'];
                const formattedDate = formatDate(date);
                tanggalAwalDropdown.append('<option value="' + date + '">' + formattedDate + '</option>');
                selectedDates.push(date);
            }

            // Debug statement to check selectedDates
            console.log('Selected Dates:', selectedDates);

            // Call the update function
            updateTanggalAkhirOptions();
        },
        error: function () {
            console.error('Error fetching dates');

            // If an error occurs, update Tanggal Akhir with an empty array
            updateTanggalAkhirOptions();
        }
    });
}


                                function formatDate(dateString) {
                                            const options = { day: 'numeric', month: 'long', year: 'numeric' };
                                            const formattedDate = new Date(dateString).toLocaleDateString('en-US', options);
                                            return formattedDate;
                                        }
                            </script>
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
                            <?php
                            ?>
                            <script>
                  

                                </script>
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label>Tanggal Awal : </label>
                                    <select class="form-control" id="tanggal_awal" name="tanggal_awal" onchange="updateTanggalAkhirOptions()">
                                        <option value="" selected disabled>Pilih Tanggal Awal</option>
                                        <?php
                                        foreach ($unique_dates as $date) {
                                            // Ubah format tanggal
                                            $formatted_date = date("j F Y", strtotime($date));
                                            echo "<option value='$date'>$formatted_date</option>";
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                            </div>
                            <script>
                                // function updateTanggalAkhirOptions() {
                                //     var selectedTanggalAwal = $('#tanggal_awal').val();
                                //     var tanggalAkhirDropdown = $('#tanggal_akhir');

                                //     // Clear existing options
                                //     tanggalAkhirDropdown.empty();

                                //     // Add a default disabled option
                                //     tanggalAkhirDropdown.append('<option value="" selected disabled>Pilih Tanggal Akhir</option>');

                                //     // Parse selected date to get the next day
                                //     var nextDate = new Date(selectedTanggalAwal);
                                //     nextDate.setDate(nextDate.getDate() + 1);

                                //     // Loop from the next day to the end of the month
                                //     while (nextDate.getMonth() == new Date(selectedTanggalAwal).getMonth()) {
                                //         const formattedNextDate = formatDate(nextDate);
                                //         tanggalAkhirDropdown.append('<option value="' + formattedNextDate + '">' + formattedNextDate + '</option>');
                                //         nextDate.setDate(nextDate.getDate() + 1);
                                //     }
                                // }
                          

                                function updateTanggalAkhirOptions() {
                                    var selectedTanggalAwal = $('#tanggal_awal').val();
                                    var tanggalAkhirDropdown = $('#tanggal_akhir');

                                    // Clear existing options
                                    tanggalAkhirDropdown.empty();

                                    // Add a default disabled option
                                    tanggalAkhirDropdown.append('<option value="" selected disabled>Pilih Tanggal Akhir</option>');

                                    // Check if selectedTanggalAwal is not set
                                    if (!selectedTanggalAwal) {
                                        return;
                                    }

                                    if (selectedDates && selectedDates.length > 0) {
                                        // Find the maximum date in selectedDates
                                        var maxDate = new Date(Math.max.apply(null, selectedDates.map(date => new Date(date))));

                                        // Parse selectedTanggalAwal and maxDate
                                        var startDate = new Date(selectedTanggalAwal);
                                        startDate.setDate(startDate.getDate() + 1); // Start from the next day

                                        // Loop from startDate to maxDate
                                        while (startDate <= maxDate) {
                                            const formattedDate = formatDate(startDate);
                                            tanggalAkhirDropdown.append('<option value="' + startDate.toISOString() + '">' + formattedDate + '</option>');
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                    }
                                }


                            </script>
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
  </div>
  <!-- Main Footer -->
  <?php include 'footer.php' ?>
</div>
<!-- jQuery --> 
<script src="dist/js/jquery.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="dist/js/ovio.js"></script> 
<script>
    // var uniqueDates = <?php echo json_encode($unique_dates); ?>;
    // // Call a function in your script to initialize with the data
    // console.log("Unique Dates:", uniqueDates);
    // initScript(uniqueDates);
</script>
</body>


</html>


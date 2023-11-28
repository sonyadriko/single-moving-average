<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
}

$id_data = $_GET['GetID'];
$query = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '".$id_data."'");
while($row = mysqli_fetch_assoc($query)){
    $nama = $row['nama_barang'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Barang</title>

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
        <?php include 'sidebar.php'?>

        <div class="content-wrapper">
            <section class="content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart-box">
                            <h4>Data Barang</h4>
                            
                            <div class="col-md-4">
                                <form action="update_barang.php?id=<?php echo $id_data ?>" method="post">
                                    <fieldset class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $nama ?>" type="text" placeholder="Enter Nama Barang">
                                    </fieldset>
                                    <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" value="Tambah">
                                </form>
                            </div>
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
    <script src="plugins/tables/jquery.tablesort.js"></script>
</body>
</html>

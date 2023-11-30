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
    <title>Tambah Penjualan</title>

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
                            <h4>Data Penjualan</h4>
                        <div class="row">
                            
                            <div class="col-md-4">
                            <form action="tambah_penjualan.php" method="post">
                                <fieldset class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <select class="form-control" id="nama_barang" name="nama_barang">
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
                                <fieldset class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <select class="form-control" id="tanggal" name="tanggal">
                                        <?php
                                        for ($i = 1; $i <= 30; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input class="form-control" id="jumlah" name="jumlah" type="text" placeholder="Enter Jumlah">
                                </fieldset>
                                <input type="submit" class="btn btn-primary btn-user btn-block" name="submit" value="Tambah">
                            </form>

                            </div>
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

<?php 
    include 'koneksi.php';

    if(isset($_POST['submit'])){
        $id_barang = $_POST['nama_barang'];
        $tanggal = $_POST['tanggal'];
        $jumlah = $_POST['jumlah'];
        // $harga_barang = mysqli_real_escape_string($conn, $_POST['harga_barang']); // Uncomment and use if needed

        $insertData = "INSERT INTO penjualan (`id_penjualan`, `id_barang`, `tanggal`, `jumlah`) VALUES (NULL, '$id_barang', '$tanggal', '$jumlah')";
        $insertResult = mysqli_query($conn, $insertData);

        if($insertResult){
            echo "<script>alert('Berhasil menambah data penjualan.')</script>";
            echo "<script>window.location.href = 'tambah_penjualan.php';</script>";
            // Alternatively, you can use: echo "<script>location.reload();</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>

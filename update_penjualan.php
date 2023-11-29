
<?php 
    include 'koneksi.php';

   
    $id_data = $_GET['id'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $query = "UPDATE penjualan set tanggal = '".$tanggal."', jumlah = '".$jumlah."' where id_penjualan = '".$id_data."'";
    $result = mysqli_query($conn, $query);
    if($result){
        header("Location:penjualan.php");
    }else {
        header('please check again');
    }

?>

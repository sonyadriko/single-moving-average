
<?php 
    include 'koneksi.php';

   
    $id_data = $_GET['id'];
    $nama = $_POST['nama_barang'];
    $query = "UPDATE barang set nama_barang = '".$nama."' where id_barang = '".$id_data."'";
    $result = mysqli_query($conn, $query);
    if($result){
        header("Location:barang.php");
    }else {
        header('please check again');
    }

?>

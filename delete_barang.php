<?php 
	
	include 'koneksi.php';

	if (isset($_GET['Del'])) {
		// code...
		$id_datatraining = $_GET['Del'];
		$query = "DELETE FROM barang WHERE id_barang = '".$id_datatraining."'";
		$result = mysqli_query($conn, $query);

		if ($result) {
			// code...
			header("Location:barang.php");
		}else {
			echo "Please Check Again";
		}
	}
?>
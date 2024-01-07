<?php
// Include your database connection logic (e.g., connection to MySQL)

// Assume you have a table named 'penjualan' with a column 'tanggal'
// You may need to adjust this query based on your actual database schema
$query = "SELECT MAX(tanggal) AS max_date FROM penjualan";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $maxDate = $row['max_date'];
    
    // Output the maximum date as a string
    echo $maxDate;
} else {
    // Handle the error case
    echo "Error fetching maximum date";
}

// Close the database connection
mysqli_close($conn);
?>

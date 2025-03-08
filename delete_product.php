<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Onlineshopping";


if(isset($_GET['id'])){
    $productId = $_GET['id'];

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $sql = "DELETE FROM products WHERE id = '$productId'";

    if (mysqli_query($conn, $sql)) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid product ID";
}
?>

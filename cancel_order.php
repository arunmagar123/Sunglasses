<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Onlineshopping";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $order_id = $_POST['order_id'];

  
    $sql = "UPDATE order_items SET order_status = 'canceled' WHERE order_id = $order_id";
    
    if (mysqli_query($conn, $sql)) {
        
        header('Location: order.php');
        exit();
    } else {
        
        echo '<p>Failed to cancel the order. Please try again.</p>';
    }
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST["order_id"];

    // Perform database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Onlineshopping";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete the order from the database
    $delete_query = "DELETE FROM orders WHERE order_id = $order_id";

    if (mysqli_query($conn, $delete_query)) {
        echo "Order deleted successfully.";
    } else {
        echo "Error deleting order: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle invalid requests here
    echo "Invalid request.";
}
?>

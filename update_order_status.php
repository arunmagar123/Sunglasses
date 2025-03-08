<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];

    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

   
    $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
       
        echo json_encode(["success" => false, "message" => "Error preparing statement"]);
    } else {
        
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        if (mysqli_stmt_execute($stmt)) {
           
            echo json_encode(["success" => true, "message" => "Order status updated."]);
        } else {
           
            echo json_encode(["success" => false, "message" => "Error updating order status: " . mysqli_error($conn)]);
        }

       
        mysqli_stmt_close($stmt);
    }

   
    mysqli_close($conn);
}
?>

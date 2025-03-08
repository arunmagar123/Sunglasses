<?php
$servername = "localhost";
$username1 = "root";
$password1 = "";
$dbname = "Onlineshopping";

$conn = mysqli_connect($servername, $username1, $password1, $dbname);
if (!$conn) {
    die("Connection failed:" . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
   
    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productImage = $_FILES["productImage"]["name"];
    $productImageTmp = $_FILES["productImage"]["tmp_name"];

   
    $targetDir = "images/";
    $targetFilePath = $targetDir . $productImage;
    move_uploaded_file($productImageTmp, $targetFilePath);

   
    $sql = "INSERT INTO products (product_name, price, image) VALUES ('$productName', $productPrice, '$productImage')";
    if ($conn->query($sql) === true) {
        echo "Product added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

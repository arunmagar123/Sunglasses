<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }


        table img {
            max-width: 100px;
            max-height: 100px;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h1> My Order</h1>

    <?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Onlineshopping";


    $conn = mysqli_connect($servername, $username, $password, $dbname);


    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    $sql = "SELECT o.order_id, oi.product_name, oi.product_image, oi.product_quantity, o.order_status
            FROM orders AS o
            INNER JOIN order_items AS oi ON o.order_id = oi.order_id";


    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Order ID</th><th>Product Name</th><th>Product Image</th><th>Product Quantity</th><th>Order Status</th></tr>";


        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["order_id"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td><img src='Image/" . $row["product_image"] . "' alt='" . $row["product_name"] . "'></td>";
            echo "<td>" . $row["product_quantity"] . "</td>";
            echo "<td>" . $row["order_status"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
    }


    mysqli_close($conn);
    ?>
</body>

</html>
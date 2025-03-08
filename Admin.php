<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="css/adminstyle.css">
    </style>
    <style>
        .dashboard {
            display: flex;
            flex-direction: column;
            color: #fff;
            padding: 17rem;
            margin-top: -14rem;

        }


        .dashboard-row {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 20px;


        }

        .dashboard-item {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            border-radius: 35px;
            width: calc(33.33% - 20px);
            height: 200px;
        }

        .dashboard-item h2 {
            font-size: 24px;
            margin: 0;
        }

        .dashboard-item p {
            font-size: 36px;
            font-weight: bold;

        }

        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>Admin Panel</h1>
        <ul>
            <li><a href="#dashboard" onclick="showContent('dashboardContent')">Dashboard</a></li>
            <li><a href="#products" onclick="showContent('addProductForm'); loadProductForm();">Products</a></li>
            <li><a href="#categories" onclick="showContent('categoriesContent')">Categories</a></li>
            <li><a href="#orders" onclick="showContent('ordersContent')">Orders</a></li>
            <li> <a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="dashboard" id="dashboardContent">
        <div class="dashboard-row">
            <div class="dashboard-item">
                <h2>Total Products</h2>
                <p>
                    <?php echo getTotalProducts(); ?>
                </p>
            </div>
            <div class="dashboard-item">
                <h2>Total Orders</h2>
                <p>
                    <?php echo getTotalOrders(); ?>
                </p>
            </div>
            <div class="dashboard-item">
                <h2>Total Customers</h2>
                <p>
                    <?php echo getTotalCustomers(); ?>
                </p>
            </div>
        </div>
        <div class="dashboard-row">
            <div class="dashboard-item">
                <h2>Men Products</h2>
                <p>
                    <?php echo countProductsByCategory("men"); ?>
                </p>
            </div>
            <div class="dashboard-item">
                <h2>Women Products</h2>
                <p>
                    <?php echo countProductsByCategory("women"); ?>
                </p>
            </div>
            <div class="dashboard-item">
                <h2>Popular Products</h2>
                <p>
                    <?php echo countProductsByCategory("popularProduct"); ?>
                </p>
            </div>
        </div>
        <div class="dashboard-row">
            <div class="dashboard-item">
                <h2>Today's Sales</h2>
                <p>
                    <?php echo getTodaySales(); ?>
                </p>
            </div>
            <div class="dashboard-item">
                <h2>One Week Sales</h2>
                <p>
                    <?php echo getOneWeekSales(); ?>
                </p>
            </div>
        </div>

    </div>




    <?php
    $servername = "localhost";
    $username1 = "root";
    $password1 = "";
    $dbname = "Onlineshopping";

    $conn = mysqli_connect($servername, $username1, $password1, $dbname);
    if (!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }

    $errProductName = $errProductPrice = $errProductCategory = $errProductImage = $errForm = '';

    if (isset($_POST["submit"])) {
        $productName = $_POST["productName"];
        $productPrice = $_POST["productPrice"];
        $productCategory = $_POST["productCategory"];
        $productImage = $_FILES["productImage"]["name"];
        $productImageTmp = $_FILES["productImage"]["tmp_name"];


        if (empty($productName)) {
            $errProductName = "Product name is required.";
        }

        if (!is_numeric($productPrice) || $productPrice <= 0) {
            $errProductPrice = "Invalid product price.";
        }

        $maxFileSize = 2 * 1024 * 1024;
        if ($_FILES["productImage"]["size"] > $maxFileSize) {
            $errProductImage = "Image size exceeds the maximum limit of 2MB.";
        }

        if (!empty($errors)) {
            $errForm = '<div class="error-message">';
            foreach ($errors as $error) {
                $errForm .= '<p>' . $error . '</p>';
            }
            $errForm .= '</div>';
        } else {

            $targetDir = "Image/";
            $targetFilePath = $targetDir . $productImage;
            if (!move_uploaded_file($productImageTmp, $targetFilePath)) {
                $errProductImage = "Failed to upload image.";
            } else {

                $formattedPrice = number_format($productPrice, 0, '', '');
                $sql = "INSERT INTO products (product_name, price, category, image) VALUES ('$productName', $productPrice, '$productCategory', '$productImage')";
                if ($conn->query($sql) === true) {
                    echo "Product added successfully.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }

    $conn->close();
    ?>

    <div class="content" id="addProductForm" style="display: none;">
        <form method="post" action="" enctype="multipart/form-data">
            <h2>Add Product</h2>
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName">
            <span class="error" id="productNameError">
                <?php echo $errProductName; ?>
            </span>
            <br>
            <label for="productPrice">Product Price:</label>
            <input type="number" id="productPrice" name="productPrice">
            <span class="error" id="productPriceError">
                <?php echo $errProductPrice; ?>
            </span>
            <br>
            <label for="productCategory">Product Category:</label>
            <select id="productCategory" name="productCategory">

                <option value="popularProduct">Popular Product</option>
                <option value="men">Men</option>
                <option value="women">Women</option>
            </select>
            <span class="error" id="productCategoryError">
                <?php echo $errProductCategory; ?>
            </span>
            <br>
            <label for="productImage">Product Image:</label>
            <input type="file" id="productImage" name="productImage" accept="image/*">
            <span class="error" id="productImageError">
                <?php echo $errProductImage; ?>
            </span>
            <br>
            <button type="submit" name="submit" value="submit" onclick="submitForm()">Add Product</button>
        </form>
    </div>

    <div class="content" id="categoriesContent" style="display: none;">
        <div class="categories-container">
            <?php
            $servername = "localhost";
            $username1 = "root";
            $password1 = "";
            $dbname = "Onlineshopping";

            $conn = mysqli_connect($servername, $username1, $password1, $dbname);
            if (!$conn) {
                die("Connection failed:" . mysqli_connect_error());
            }

            $sql = "SELECT * FROM category";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                    echo '<tr><th>Category ID</th><th>Category Name</th></tr>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['category_id'] . '</td>';
                        echo '<td>' . $row['category_type'] . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo "No categories found.";
                }
            } else {
                echo "Query failed: " . mysqli_error($conn);
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>


    <div class="content" id="ordersContent" style="display:none;">
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Order Total</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>

                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "Onlineshopping";


                $conn = mysqli_connect($servername, $username, $password, $dbname);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }


                $sql = "SELECT * FROM orders";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $order_id = $row["order_id"];
                        $customer_name = $row["customer_name"];
                        $order_date = $row["order_date"];
                        $total_order = isset($row["total_order"]) ? $row["total_order"] : "N/A";
                        $customer_phone = isset($row["customer_phone"]) ? $row["customer_phone"] : "N/A";
                        $customer_address = isset($row["customer_address"]) ? $row["customer_address"] : "N/A";
                        $status = isset($row["status"]) ? $row["status"] : "Pending";
                        echo '<tr>';
                        echo '<td>' . $order_id . '</td>';
                        echo '<td>' . $customer_name . '</td>';
                        echo '<td>' . $order_date . '</td>';
                        echo '<td>' . $total_order . '</td>';
                        echo '<td>' . $customer_phone . '</td>';
                        echo '<td>' . $customer_address . '</td>';
                        echo '<td id="status_' . $order_id . '">' . $status . '</td>';
                        echo '<td>';
                        if ($status === "Pending") {
                            echo '<button onclick="confirmOrder(' . $order_id . ')">Confirm</button>';
                            echo '<button onclick="cancelOrder(' . $order_id . ')">Cancel</button>';
                        } else {
                            echo 'N/A';
                        }
                        echo '<td><a href="#" onclick="toggleProductDetails(' . $order_id . ')">View Details</a></td>';
                        echo '</td>';
                        echo '</tr>';



                        $orderItemsQuery = "SELECT * FROM order_items WHERE order_id = $order_id";
                        $orderItemsResult = mysqli_query($conn, $orderItemsQuery);

                        echo '<tr id="productDetails_' . $order_id . '" style="display: none;">';
                        echo '<td colspan="8">';
                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Product ID</th>';
                        echo '<th>Product Name</th>';
                        echo '<th>Product Price</th>';
                        echo '<th>Quantity</th>';
                        echo '<th>Image</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        if (mysqli_num_rows($orderItemsResult) > 0) {
                            while ($orderItemRow = mysqli_fetch_assoc($orderItemsResult)) {
                                echo '<tr>';
                                echo '<td>' . $orderItemRow["product_id"] . '</td>';
                                echo '<td>' . $orderItemRow["product_name"] . '</td>';
                                echo '<td>' . $orderItemRow["product_price"] . '</td>';
                                echo '<td>' . $orderItemRow["product_quantity"] . '</td>';
                                echo '<td><img src="Image/' . $orderItemRow["product_image"] . '" alt="Product Image"></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No order items found for this order.</td></tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8">No orders found.</td></tr>';
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>




    <script>
        function toggleProductDetails(orderId) {
            var productDetails = document.getElementById("productDetails_" + orderId);
            if (productDetails.style.display === "none" || productDetails.style.display === "") {
                productDetails.style.display = "table-row";
            } else {
                productDetails.style.display = "none";
            }
        }



        function showContent(contentId) {
            var contents = document.getElementsByClassName("content");
            for (var i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }

            if (contentId !== "dashboardContent") {
                var dashboard = document.getElementById("dashboardContent");
                dashboard.style.display = "none";
            }

            var content = document.getElementById(contentId);
            content.style.display = "";
        }



        function submitForm() {
            var productName = document.getElementById("productName").value.trim();
            var productPrice = document.getElementById("productPrice").value;
            var productCategory = document.getElementById("productCategory").value;
            var productImage = document.getElementById("productImage").value.trim();

            var productNameError = document.getElementById("productNameError");
            var productPriceError = document.getElementById("productPriceError");
            var productCategoryError = document.getElementById("productCategoryError");
            var productImageError = document.getElementById("productImageError");

            productNameError.textContent = "";
            productPriceError.textContent = "";
            productCategoryError.textContent = "";
            productImageError.textContent = "";

            // Validation
            if (productName === "") {
                productNameError.textContent = "Product name is required.";
                return;
            }

            if (isNaN(productPrice) || Number(productPrice) <= 0) {
                productPriceError.textContent = "Invalid product price.";
                return;
            }

            if (productCategory === "") {
                productCategoryError.textContent = "Product category is required.";
                return;
            }

            if (productImage === "") {
                productImageError.textContent = "Product image is required.";
                return;
            }


            document.querySelector("form").submit();
        }

        function confirmOrder(orderId) {
            var statusCell = document.getElementById("status_" + orderId);
            statusCell.textContent = "Confirmed";


            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_order_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    console.log(xhr.responseText);
                }
            };
            xhr.send("order_id=" + orderId + "&status=Confirmed");
        }

        function cancelOrder(orderId) {
            var statusCell = document.getElementById("status_" + orderId);
            statusCell.textContent = "Canceled";


            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_order_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    console.log(xhr.responseText);
                }
            };
            xhr.send("order_id=" + orderId + "&status=Canceled");
        }

        window.onload = function () {

            var orderRows = document.querySelectorAll("[id^='status_']");
            orderRows.forEach(function (row) {
                var orderId = row.id.replace("status_", "");
                fetchOrderStatus(orderId, row);
            });
        };

        function fetchOrderStatus(orderId, statusCell) {

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_order_status.php?order_id=" + orderId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    statusCell.textContent = xhr.responseText;
                }
            };
            xhr.send();
        }


    </script>

</body>

</html>

<?php
function getTotalProducts()
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT COUNT(*) AS total_products FROM products";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row["total_products"];
}

function getTotalOrders()
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT COUNT(*) AS total_orders FROM orders";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row["total_orders"];
}

function getTotalCustomers()
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT COUNT(*) AS total_customers FROM user";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row["total_customers"];
}

function countProductsByCategory($category)
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $category = mysqli_real_escape_string($conn, $category);
    $sql = "SELECT COUNT(*) AS count FROM products WHERE category = '$category'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($conn);
    return $row["count"];
}


function getTodaySales()
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $today = date("Y-m-d");
    $sql = "SELECT SUM(total_order) AS total_sales FROM orders WHERE DATE(order_date) = '$today'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($conn);
    return $row["total_sales"] ?: 0;
}


function getOneWeekSales()
{
    $conn = mysqli_connect("localhost", "root", "", "Onlineshopping");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $oneWeekAgo = date("Y-m-d", strtotime("-7 days"));
    $today = date("Y-m-d");
    $sql = "SELECT SUM(total_order) AS total_sales FROM orders WHERE DATE(order_date) BETWEEN '$oneWeekAgo' AND '$today'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($conn);
    return $row["total_sales"];
}

?>
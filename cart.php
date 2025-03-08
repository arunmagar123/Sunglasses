<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'Onlineshopping');

if (isset($_GET['product_id']) && isset($_GET['product_name']) && isset($_GET['product_price']) && isset($_GET['product_image'])) {
    $product_id = $_GET['product_id'];
    $product_name = $_GET['product_name'];
    $product_price = $_GET['product_price'];
    $product_image = $_GET['product_image'];

    $item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1,
        'image' => $product_image
    );

    if (isset($_SESSION['cart'])) {
        $index = -1;
        foreach ($_SESSION['cart'] as $key => $cartItem) {
            if ($cartItem['id'] == $product_id) {
                $index = $key;
                break;
            }
        }

        if (isset($_POST['update_quantity'])) {
            $product_id = $_POST['product_id'];
            $new_quantity = $_POST['quantity'];

            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    if ($new_quantity > 0) {
                        $_SESSION['cart'][$key]['quantity'] = $new_quantity;
                    } else {
                        unset($_SESSION['cart'][$key]);
                    }
                    break;
                }
            }
        } elseif ($index !== -1) {
            $_SESSION['cart'][$index]['quantity'] += 1;
        } else {
            $_SESSION['cart'][] = $item;
        }
    } else {
        $_SESSION['cart'] = array($item);
    }
}

if (isset($_GET['delete_item'])) {
    $delete_id = $_GET['delete_item'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $delete_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/indexstyle.css">
    <style>
        .cart-item {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        .cart {
            text-align: center;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .cart h1 {
            font-size: 24px;
            margin: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .cart-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            padding: 10px;
        }

        .cart-table th {
            background-color: #f2f2f2;
        }

        .cart-table td {
            text-align: center;
        }

        .cart-table .remove-item a {
            text-decoration: none;
        }

        .cart-table .remove-item a:hover {
            text-decoration: underline;
            color: red;
            font-weight: bold;
        }

        .checkout-button {
            display: block;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-button a {
            text-decoration: none;
            color: inherit;
        }

        .checkout-button:hover {
            background-color: green;
        }
    </style>

</head>

<body>

    <section id="header">
        <a href="index2.php" class="logo"><img src="image.png"></a>

        <div>
            <ul id="nav-bar">
                <li><a href="index2.php">Home</li>
                <li><a href="#shop">Product</li>
                <li class="dropdown"><a href="#">Category</a>
                    <ul class="dropdown-menu">
                        <li><a href="#men">Man</a></li>
                        <li><a href="#women">Woman</a></li>
                    </ul>
                </li>
                <li><a href="#contact">Contact</li>
                <li><a href="cart.php">Cart</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    $profileImage = "Image/profile.png";
                    $userCartLink = "cart.php";
                    echo '
                    <li>
                        <img src="' . $profileImage . '" class="profile-img" onclick="toggleMenu()">
                        <div class="sub" id="drop">
                            <div class="sub-profile">
                                <div class="user-profile">
                                    <img src="' . $profileImage . '">
                                    <h3>' . $username . '</h3>
                                </div>
                                <hr>
                                <a href="' . $userCartLink . '" class="sub-menu">
                                    <img src="Image/shopping-bag.png">
                                    <p>My Order</p>
                                    <span>></span>
                                </a>
                                <a href="logout.php" class="sub-menu">
                                    <img src="Image/logout.png">
                                    <p>Logout</p>
                                    <span>></span>
                                </a>
                            </div>
                        </div>
                    </li>';
                } else {
                    echo '<li><a href="login.php" id="login-button">Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </section>

    <script>
        let drop = document.getElementById("drop");

        function toggleMenu() {
            drop.classList.toggle("open-menu");
        }
    </script>

    <div class="cart-item" id="cart-item">
        <div class="cart">
            <h1>My Cart</h1>
            <?php
            if (isset($_SESSION['cart'])) {
                echo '<table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

                $total = 0;
                foreach ($_SESSION['cart'] as $cartItem) {
                    $product_id = $cartItem['id'];
                    $product_name = $cartItem['name'];
                    $product_price = $cartItem['price'];
                    $product_quantity = $cartItem['quantity'];
                    $product_image = $cartItem['image'];

                    $subtotal = $product_price * $product_quantity;
                    $total += $subtotal;

                    echo '
                    <tr>
                        <td>' . $product_name . '</td>
                        <td>Rs' . $product_price . ' /-</td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="' . $product_id . '">
                                <input type="number" name="quantity" value="' . $product_quantity . '" min="1" onchange="this.form.submit()">
                                <input type="hidden" name="update_quantity" value="true">
                            </form>
                        </td>
                        <td>Rs' . $subtotal . ' /-</td>
                        <td class="remove-item">
                            <a href="cart.php?delete_item=' . $product_id . '">Delete</a>
                        </td>
                    </tr>';
                }
                echo '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td><strong>Total:</strong> Rs' . $total . ' /-</td>
                    </tr>
                </tfoot>
                </table>';
                echo '<a href="checkout.php" class="checkout-button">Proceed to Checkout</a>';
            } else {
                echo '<p>Your cart is empty.</p>';
            }
            ?>
        </div>
    </div>

    <!-- footer content -->
</body>

</html>

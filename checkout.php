<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'onlineshopping');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Your cart is empty. Please add some items before checking out.</p>';
    exit();
}

if (!isset($_SESSION['login'])) {
    echo '<p>You need to be logged in to proceed to checkout.</p>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone)) {
        echo '<p>Please fill in all the required fields.</p>';
        exit();
    }

    $total_order = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product_price = $item['price'];
        $product_quantity = $item['quantity'];
        $total_order += ($product_price * $product_quantity);
    }

    $query = "INSERT INTO orders (customer_name, customer_address, customer_phone, customer_email, total_order) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssssd", $customer_name, $customer_address, $customer_phone, $customer_email, $total_order);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $order_id = mysqli_insert_id($db);

        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $product_name = $item['name'];
            $product_price = $item['price'];
            $product_quantity = $item['quantity'];
            $product_image = $item['image'];

            $query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, product_quantity, product_image) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "iisdis", $order_id, $product_id, $product_name, $product_price, $product_quantity, $product_image);
            mysqli_stmt_execute($stmt);
        }

        unset($_SESSION['cart']);

        require 'stripe-php-master/init.php';
        \Stripe\Stripe::setApiKey('sk_test_51Nsp0xDtiwzXW8ZNu0KWNEAIR6cty4DE5djX2lEWlXq5fUpPnpOyd0A7HlAYSYtGzgbcXNQGS7hatMA9N0FKvscD00sC0GjrT0');

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $total_order * 100,
                'currency' => 'npr',
                'description' => 'Online Shopping Order',
                'payment_method' => $_POST['payment_intent_id'],
                'confirmation_method' => 'manual',
                'return_url' => 'http://localhost/Project%201/payment_success.php',
                'confirm' => true,
            ]);
            
var_dump($paymentIntent->status); 


            if ($paymentIntent->status === 'succeeded') {
                header('Location: payment_success.php');
                exit();
            } else {
                echo '<h1>Payment Failed</h1>';
                echo '<p>There was an issue processing your payment.</p>';
            }

            unset($_SESSION['payment_intent_id']);
        } catch (\Stripe\Exception\CardException $e) {
            echo '<h1>Payment Failed</h1>';
            echo '<p>Error: ' . $e->getMessage() . '</p>';
        } catch (Exception $e) {
            echo '<h1>Payment Failed</h1>';
            echo '<p>Error: ' . $e->getMessage() . '</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/checkout.css">
    <title>Checkout</title>
<style>
  .payment-logo {
    max-width: 40px; /* Adjust the size as needed */
    vertical-align: middle; /* Align the image vertically */
}

.stripe-text {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-weight: bold;
    font-size: 18px;
    color: #00a2ff;  /* Adjust the color as needed */
    display: inline-block; /* Make the text display as inline-block */
    vertical-align: middle; /* Align the text vertically */
    margin-left: 5px; /* Adjust the spacing between image and text */
}



</style>
</head>

<body>
    <div class="checkout">
        <h1>Checkout</h1>
        <h2>Customer Details</h2>

        <form id="checkout-form" method="POST" action="checkout.php">
            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required><br>

            <label for="customer_email">Email:</label>
            <input type="email" id="customer_email" name="customer_email" required><br>

            <label for="customer_phone">Phone:</label>
            <input type="tel" id="customer_phone" name="customer_phone" required><br>

            <label for="customer_address">Address:</label>
            <input type="text" id="customer_address" name="customer_address" required><br>

            <input type="hidden" name="payment_intent_id" id="payment_intent_id" value="">

            <h2>Payment Method</h2>
            <p class="stripe-text"><img src="/Project%201/Image/stripe-logo-3.png" alt="Payment Logo" class="payment-logo"> Stripe</p>

           
            <div id="card-element"></div>

            <div id="card-errors" role="alert"></div>

            <button type="submit">Process to Payment</button>
        </form>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('pk_test_51Nsp0xDtiwzXW8ZNaLla9riGJAF0jGHfuueCnu9IwJ6hvqpFLWl1FeagTUj06Al2sjYwduplgX4kM2WSuGNExlZV00ejRqf3m1'); // Replace with your Stripe Public Key
        var elements = stripe.elements();
        var cardElement = elements.create('card');

        cardElement.mount('#card-element');

        var form = document.getElementById('checkout-form');
        var errorElement = document.getElementById('card-errors');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            }).then(function (result) {
                if (result.error) {
                    errorElement.textContent = result.error.message;
                } else {
                    var paymentMethodId = result.paymentMethod.id;
                    document.getElementById('payment_intent_id').value = paymentMethodId;
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>

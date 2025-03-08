<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/indexstyle.css">
    <title>Online Shopping</title>
    <style>
        #login-button {
            display:
                <?php echo isset($_SESSION['username']) ? 'none' : 'block'; ?>
            ;
        }
    </style>

    <title>Index</title>
</head>

<body>

    <section id="header">
        <a href="index2.php" class="logo"><img src="image.png"></a>

        <div>
            <ul id="nav-bar">
                <li><a href="index2.php">Home</li>
                <li><a href="#popularProduct">Product</li>
                <li class="dropdown"><a href="#">Category</a>
                    <ul class="dropdown-menu">
                        <li><a href="#men">Man</a></li>
                        <li><a href="#women">Woman</a></li>

                    </ul>
                </li>
                <li><a href="#contact">Contact</li>
                <li><a href="cart.php">Cart</a></li>
                <?php
                require_once 'config.php';
                session_start();


                if (isset($_SESSION['username'])) {

                    $username = $_SESSION['username'];

                    $profileImage = "Image/profile.png";
                    $userCartLink = "myorder.php";


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



    <div id="banner-image">
        <div class="banner-content">
            <button> Shop Now </button>
        </div>
    </div>
    <div id="box-slide">
        <div class="slide-box">
            <h2 class="slide-title">Trending product</h2>

            <div class="slide-body">
                <div class="slide-container">
                    <div class="slides">
                        <img src="Image/Barcur.png" class="active">
                        <img src="Image/gucci.png">
                        <img src="Image/rayban-sunnies2.png">
                        <img src="Image/Rayban.png">
                    </div>
                    <div class="button">
                        <span class="next">&#10095;</span>
                        <span class="prev">&#10094;</span>
                    </div>
                    <div class="slide-content">
                        <button> Shop Now </button>
                    </div>
                </div>
            </div>


            <script type="text/javascript">
                let slideImages = document.querySelectorAll('.slides img');

                let next = document.querySelector('.next');
                let prev = document.querySelector('.prev');

                let dots = document.querySelectorAll('.dots');
                var counter = 0;

                next.addEventListener('click', slideNext);
                function slideNext() {
                    slideImages[counter].style.animation = 'next1 0.5s ease-in forwards';
                    if (counter >= slideImages.length - 1) {
                        counter = 0;
                    }
                    else {
                        counter++;
                    }
                    slideImages[counter].style.animation = 'next2 0.5s ease-in forwards';

                }


                prev.addEventListener('click', slidePrev);
                function slidePrev() {
                    slideImages[counter].style.animation = 'prev1 0.5s ease-in forwards';
                    if (counter == 0) {
                        counter = slideImages.length - 1;
                    }
                    else {
                        counter--;
                    }
                    slideImages[counter].style.animation = 'prev2 0.5s ease-in forwards';

                }


                function autoSliding() {
                    deletInterval = setInterval(timer, 1000);
                    function timer() {
                        slideNext();

                    }
                }
                autoSliding();

                const container = document.querySelector('.slide-container');
                container.addEventListener('mouseover', function () {
                    clearInterval(deletInterval);
                });

                container.addEventListener('mouseout', autoSliding);
            </script>

        </div>
    </div>

    <!-- Shop -->
    <div class="shop-container">
        <section class="shop" id="popularProduct">
            <h3 class="sub-heading"></h3>
            <h1 class="heading">Popular product</h1>

            <div class="box-container">


                <?php
                $servername = "localhost";
                $username1 = "root";
                $password1 = "";
                $dbname = "Onlineshopping";

                $conn = mysqli_connect($servername, $username1, $password1, $dbname);
                if (!$conn) {
                    die("Connection failed:" . mysqli_connect_error());
                }

                $sql = "SELECT * FROM products WHERE category='popularProduct'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="box">
                            <img src="Image/' . $row["image"] . '" alt="">
                            <h3>' . $row["product_name"] . '</h3>
                            <span>Rs' . $row["price"] . ' /-</span>
                            <a href="cart.php?product_id=' . $row["id"] . '&product_name=' . urlencode($row["product_name"]) . '&product_price=' . $row["price"] . '&product_image=' . urlencode($row["image"]) . '" class="btn">Add to cart</a>

                        </div>';
                    }
                }

                $conn->close();
                ?>
            </div>

        </section>
    </div>

    <div class="shop-container">
        <section class="shop" id="men">
            <h3 class="sub-heading">Our Product</h3>
            <h1 class="heading">For Men</h1>

            <div class="box-container">


                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "Onlineshopping";


                $conn = mysqli_connect($servername, $username, $password, $dbname);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM products WHERE category='men'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                    <div class="box">
                        <img src="Image/' . $row["image"] . '" alt="">
                        <h3>' . $row["product_name"] . '</h3>
                        <span>Rs' . $row["price"] . ' /-</span>
                        <a href="cart.php?product_id=' . $row["id"] . '&product_name=' . urlencode($row["product_name"]) . '&product_price=' . $row["price"] . '&product_image=' . urlencode($row["image"]) . '" class="btn">Add to cart</a>

                    </div>';
                    }
                }

                mysqli_close($conn);
                ?>
            </div>
        </section>
    </div>



    <div class="shop-container">
        <section class="shop" id="women">
            <h3 class="sub-heading">Our Product</h3>
            <h1 class="heading">For Women</h1>

            <div class="box-container">

                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "Onlineshopping";

                $conn = mysqli_connect($servername, $username, $password, $dbname);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM products WHERE category='women'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                    <div class="box">
                        <img src="Image/' . $row["image"] . '" alt="">
                        <h3>' . $row["product_name"] . '</h3>
                        <span>Rs' . $row["price"] . ' /-</span>
                        <a href="cart.php?product_id=' . $row["id"] . '&product_name=' . urlencode($row["product_name"]) . '&product_price=' . $row["price"] . '&product_image=' . urlencode($row["image"]) . '" class="btn">Add to cart</a>
                    </div>';

                    }
                }

                mysqli_close($conn);
                ?>
            </div>
        </section>
    </div>



    <!-- contact -->
    <div class="contact-container" id="contact">
        <h1>Contact Us</h1>
        <form>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your name.." required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Your email.." required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" placeholder="Write something.." required></textarea>

            <input type="submit" value="Submit">
        </form>
    </div>



    <div>
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-row">
                    <div class="footer-col">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="#">about</a></li>
                            <li><a href="#">our service</a></li>
                            <li><a href="#">privacy policy</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Get Help</h4>
                        <ul>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">shipping</a></li>
                            <li><a href="#">returns</a></li>
                            <li><a href="#">tracking</a></li>
                            <li><a href="#">payment option</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>Online shop</h4>
                        <ul>
                            <li><a href="#">rayban</a></li>
                            <li><a href="#">blueray</a></li>
                            <li><a href="#">newmew</a></li>
                            <li><a href="#">gucci</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>follow us</h4>
                        <div class="social-links">
                            <a href="https://www.facebook.com/"><img src="Image/social.png"></a>
                        </div>
                    </div>
                </div>
                <p>&copy; 2023 copyright</p>
            </div>
        </footer>
        <div>
</body>

</html>
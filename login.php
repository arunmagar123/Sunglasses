<?php
$servername = "localhost";
$username1 = "root";
$password1 = "";
$dbname = "Onlineshopping";

$conn = mysqli_connect($servername, $username1, $password1, $dbname);
if (!$conn) {
    die("Connection failed:" . mysqli_connect_error());
}


$errUname = $errEmail = $errPassword = $errSelect = $errForm = '';
if (isset($_POST['submit'])) {
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($uname)) {
        $errUname = "<br />Name is required";
    }
    if (empty($email)) {
        $errEmail = "<br />Email is required";
    }
    if (empty($password)) {
        $errPassword = "<br />Password is required";
    } else {
        if (strlen($password) < 8) {
            $errPassword = "<br />At least 8 digits";
        }
    }


    $query = "SELECT * FROM user WHERE username='$uname' and email='$email' and password='$password'";
    $res = mysqli_query($conn, $query);

    if ($res) {
        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $user_id = $row['id'];


            session_start();

            $_SESSION['login'] = TRUE;
            $_SESSION['customer_id'] = $user_id;
            $_SESSION['username'] = $uname;
           
            

            header("Location:index2.php");

        } else {
            $errForm = "User name and password incorrect";
        }
    }

    $query = "SELECT * FROM  admin WHERE username='$uname' and email='$email' and password='$password'";
    $res = mysqli_query($conn, $query);

    if ($res) {
        if (mysqli_num_rows($res) == 1) {
            session_start();

            $_SESSION['login'] = TRUE;
            $_SESSION['Admin_Name'] = $Admin_Name;

            header("Location:Admin.php");
        } else {
            $errForm = "User name and password incorrect";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>login</title>
    <style>
        
    #check {
        display: inline-block;
        vertical-align: middle;
        margin-left: 60%;
        margin-top: -7%;
        border: 2px solid #000000; 
        border-radius: 4px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <form method="post" action="">
            <h2>Login</h2>
            <span class="error">
                <?php echo $errForm; ?>
            </span><br>
            <label for="uname">Username</label>
            <input type="name" name="uname" text placeholder="Enter your name" id="uname"><span class="error">
                <?php echo $errUname; ?>
            </span><br><br>
            <label for="email"> Email</label>
            <input type="name" name="email" text placeholder="Enter your email" id="email"><span class="error">
                <?php echo $errEmail; ?>
            </span><br><br>
            <label for="password">Password
            <input type="password" name="password" text placeholder="Enter your password" id="password"><span
                class="error">
                <?php echo $errPassword; ?>
               
            </span><input type="checkbox" id="check"><br><br>

            <input type="submit" name="submit" value="Login" class="login-btn"><span class="error">
                <div class="register-container">
                    <p> Does not have an account? <a href="register.php">Register</a>
                </div>
        </form>
    </div>

    <script>
        check.onclick=togglePassword;

        function togglePassword(){
            if(check.checked)password.type="text";
            else password.type="password";
        }
    </script>

</body>

</html>
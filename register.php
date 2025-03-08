<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Register</title>
    <style>
        .registration-success {
            text-align: center;
            margin-top: 250px;

        }

        .registration-success h2 {
            color: crimson;

        }

        .registration-success p {
            margin: 10px 0;

        }


        .registration-success p:last-child {
            color: #888;
        }

        #check {
            display: inline-block;
            vertical-align: middle;
            margin-left: 67%;
            margin-top: -8%;
            border: 2px solid #000000;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    $servername = "localhost";
    $username1 = "root";
    $password1 = "";
    $dbname = "Onlineshopping";
    $conn = mysqli_connect($servername, $username1, $password1, $dbname);
    $errname = $erremail = $errpass = $errcpass = $errgender = "";
    $registrationSuccess = false;
    if (isset($_POST['submit'])) {
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $gender = $_POST['gender'];
        if (empty($uname)) {
            $errname = "Username is required";
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erremail = "Enter valid email";
        } elseif (empty($password)) {
            $errpass = "Password must be 8 character";
        } elseif (strlen($password) < 8) {
            $errpass = "Password must be 8 character";
        } elseif ($password != $cpassword) {
            $errcpass = "Password must be same";
        } elseif (empty($gender)) {
            $errgender = "Gender is required";
        } else {
            $value = "INSERT INTO user(Username,Email,Password,Cpassword,Gender) VALUES('$uname','$email','$password','$cpassword','$gender')";
            mysqli_query($conn, $value);
            $registrationSuccess = true;
        }
    }
    ?>
    <?php if ($registrationSuccess) { ?>
        <div class="registration-success">
            <h2>Registration Successful</h2>
            <p>You can now log in.</p>
            <p>Redirecting to the login page...</p>
        </div>
        <script>
            setTimeout(function () {
                window.location.href = "login.php";
            }, 3000);
        </script>
    <?php } else { ?>
        <div class="form-container">
            <form action="" method="post">
                <h2>Register now</h2>
                <label for="uname">Username</label>
                <input type="text" name="uname" text placeholder="Enter your name" id="uname"><br><span class="error">
                    <?php echo $errname ?>
                </span><br><br>
                <label for="email">Email</label>
                <input type="text" name="email" text placeholder="Enter your email" id="email"><br><span class="error">
                    <?php echo $erremail ?>
                </span><br><br>
                <label for="password"> Password</label>
                <input type="password" name="password" text placeholder="Enter your password" id="password"><br><span
                    class="error">
                    <?php echo $errpass ?>
                </span><br><br>
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" text placeholder="Confirm Your password" id="cpassword"><br><span
                    class="error">
                    <?php echo $errcpass ?>
                </span><input type="checkbox" id="check"><br>
                <div class="gender-container">
                    <label for="gender">Gender</label>
                    <input type="radio" name="gender" value="male">Male
                    <input type="radio" name="gender" value="female">Female
                    <span class="error"><br>
                        <?php echo $errgender ?>
                    </span>
                </div>
                <input type="submit" name="submit" value="register now" class="form-btn">
                <div class="login-bar">
                    <p> Already have an account? <a href="login.php">Login</a>
                </div>
                </select>
            </form>
        </div>
    <?php } ?>


    <script>
        var password = document.getElementById("password");
        var cpassword = document.getElementById("cpassword");
        var check = document.getElementById("check");

        check.onclick = togglePasswords;

        function togglePasswords() {
            if (check.checked) {
                password.type = "text";
                cpassword.type = "text";
            } else {
                password.type = "password";
                cpassword.type = "password";
            }
        }
    </script>


</body>

</html>
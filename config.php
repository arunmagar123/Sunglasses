<?php
$servername="localhost";
$username="root";
$password="";
$conn=mysqli_connect($servername,$username,$password);
$sql="CREATE DATABASE Onlineshopping";
if(mysqli_query($conn,$sql)){
    echo"Database created successfully";
}
mysqli_close($conn);
?>
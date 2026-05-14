<?php
$host = "localhost";
$user = "root";
$password = "WJ28@krhps";
$database = "wecrochet";  

$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
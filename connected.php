<?php
$servername = "localhost";
$username = "root"; // your db username
$password = "";     // your db password
$database = "food";  // replace with your DB name

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

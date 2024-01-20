<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tourlism_root";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");

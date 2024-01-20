<?php
header('Access-Control-Allow-Origin: *');
include "conn.php";

$firstname = isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : '';
$lastname = isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : '';
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
$phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';

// Insert data into the register table
$sql = "INSERT INTO register (firstname, lastname, email, password, phone)
        VALUES ('$firstname', '$lastname', '$email', '$password', '$phone')";
mysqli_query($conn, $sql);

http_response_code(200);

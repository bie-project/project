<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "project";

$connect    = mysqli_connect($servername, $username, $password, $dbname);
$conn       = new mysqli($servername, $username, $password, $dbname);

if (!$connect) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
} else {
    mysqli_set_charset($connect, 'utf8');
}


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>




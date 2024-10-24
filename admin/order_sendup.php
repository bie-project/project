<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');


$id = $_POST["id"];
$ostatus = $_POST["ostatus"];
$ok = $_POST["ok"];
$dateup = date("Y-m-d H:i:s");

if ($ok <> "") {
    $sql = "UPDATE orders SET ostatus = '$ostatus', odateup = '$dateup' WHERE oid = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result == TRUE) {
        echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=order.php';</script>";
    } else {
        echo "<script>alert('ไม่สามารถบันทึกสำเร็จ');window.location='index.php?page=order.php';</script>";
    }
    $conn->close();
}
?>

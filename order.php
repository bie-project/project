<?php
session_start();
include('connection/connect.php'); 

if (empty($_SESSION["user_id"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='index.php?page=form_login.php';</script>";
    exit();
}

?>
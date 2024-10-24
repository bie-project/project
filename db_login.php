<?php 
session_start();
include('connection/connect.php'); 

if (isset($_POST["ok"]) && $_POST["ok"] != "") {
    $email = $_POST["email"];
    $pass = $_POST["pass"];  

    // แก้ไขคำสั่ง SQL (form -> from)
    $sql = "SELECT * FROM member WHERE email = ? AND pass = ?";

    // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $num = $result->num_rows;

        if ($num == 1) {
            $row = $result->fetch_assoc();

            $_SESSION["session"] = session_id();
            $_SESSION["memid"]   = $row["memid"];
            $_SESSION["fname"]   = $row["fname"];
            $_SESSION["lname"]   = $row["lname"]; 
            $_SESSION["status"]  = $row["status"];

            if ($_SESSION["status"] == 0) {
                echo "<script>alert('ยินดีต้อนรับเข้าสู่ระบบ') $SE; window.location='member/index.php';</script>";
            } else {
                echo "<script>alert('ยินดีต้อนรับ admin เข้าสู่ระบบ'); window.location='admin/index.php';</script>";
            }
        } else {
            echo "<script>alert('email หรือ password ไม่ถูกต้อง'); window.location='index.php?page=login.php';</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล');</script>";
    }
}
?>

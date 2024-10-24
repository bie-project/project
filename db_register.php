<?php 

include("connection/connect.php");

if (isset($_POST["ok"]) && $_POST["ok"] != "") {
    $prefix = $_POST["prefix"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $address = $_POST["address"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $status = $_POST["status"];

    // ตรวจสอบว่าอีเมลนี้มีการใช้งานไปแล้วหรือไม่
    $stmt = $connect->prepare("SELECT * FROM member WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_email = $stmt->get_result();

    if ($result_email->num_rows > 0) {
        echo "<script>alert('อีเมล์นี้ถูกใช้งานไปแล้วกรุณาทำรายการใหม่อีกครั้ง'); window.location='index.php';</script>";
    } else {
        $stmt = $connect->prepare("INSERT INTO member (prefix, fname, lname, address, tel, email, pass, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $prefix, $fname, $lname, $address, $tel, $email, $pass, $status);
        $resultin = $stmt->execute();

        if ($resultin) {
            echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('บันทึกไม่สำเร็จ'); window.location='index.php';</script>";
        }
        $stmt->close();
    }
}
?>
<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connect.php");

// ตรวจสอบค่าของเซสชันให้ถูกต้อง
if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

// ตรวจสอบการส่งข้อมูลด้วย POST แทน GET
if (isset($_POST["ok"]) && $_POST["ok"] != "") {
    $prefix = $_POST["prefix"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $address = $_POST["address"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $pass = $_POST["pass"]; 
    $status = $_POST["status"];
    $memid = $_POST["memid"];

    // ตรวจสอบว่าอีเมลนี้มีการใช้งานไปแล้วหรือไม่
    $sql_check = "SELECT * FROM member WHERE email=? AND memid != ?";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->bind_param("ss", $email, $memid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $num = $result_check->num_rows;

    if ($num > 0) {
        echo "<script>alert('อีเมล์นี้ถูกใช้งานไปแล้วกรุณาทำรายการใหม่อีกครั้ง'); window.location='index.php?page=member_add.php';</script>";
    } else {
        $sql_update = "UPDATE member SET 
            prefix=?, 
            fname=?, 
            lname=?, 
            address=?, 
            tel=?, 
            email=?, 
            pass=?, 
            status=? 
            WHERE memid=?";
        $stmt_update = $connect->prepare($sql_update);
        $stmt_update->bind_param("ssssssssi", $prefix, $fname, $lname, $address, $tel, $email, $pass, $status, $memid);

        if ($stmt_update->execute()) {
            echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php?page=member.php';</script>";
        } else {
            echo "<script>alert('บันทึกไม่สำเร็จ'); window.location='index.php?page=member.php';</script>";
        }

        $stmt_update->close();
    }

    $stmt_check->close();
}

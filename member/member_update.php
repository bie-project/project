<?php
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// ตรวจสอบการส่งข้อมูลด้วย POST
if (isset($_POST["ok"]) && $_POST["ok"] == "edit") {
    $prefix = $_POST["prefix"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $address = $_POST["address"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $pass = $_POST["pass"]; 
    $memid = $_SESSION["memid"]; // ดึง memid จาก session

    // ตรวจสอบว่าอีเมลนี้มีการใช้งานไปแล้วหรือไม่ (ยกเว้นตัวเอง)
    $sql_check = "SELECT * FROM member WHERE email=? AND memid != ?";
    $stmt_check = $connect->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $memid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $num = $result_check->num_rows;

    if ($num > 0) {
        echo "<script>alert('อีเมล์นี้ถูกใช้งานไปแล้ว กรุณาใช้เมล์อื่น'); window.location='index.php?page=member_edit.php';</script>";
    } else {
        // อัปเดตข้อมูลสมาชิก
        $sql_update = "UPDATE member SET 
            prefix=?, 
            fname=?, 
            lname=?, 
            address=?, 
            tel=?, 
            email=?, 
            pass=? 
            WHERE memid=?";
        $stmt_update = $connect->prepare($sql_update);
        $stmt_update->bind_param("sssssssi", $prefix, $fname, $lname, $address, $tel, $email, $pass, $memid);

        if ($stmt_update->execute()) {
            echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location='index.php?page.php';</script>";
        } else {
            echo "<script>alert('บันทึกข้อมูลไม่สำเร็จ'); window.location='index.php?page=member_edit.php';</script>";
        }

        $stmt_update->close();
    }

    $stmt_check->close();
}
?>

<?php
session_start();
include("../connection/connect.php");

if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

if (isset($_POST["ok"]) && $_POST["ok"] != "") {
    $proid = $_POST["proid"];            // ID ของสินค้า
    $proname = $_POST["proname"];        // ชื่อสินค้า
    $prodetail = $_POST["prodetail"];   // รายละเอียดสินค้า
    $price = $_POST["price"];           // ราคาสินค้า
    $sale = $_POST["sale"];             // ราคาขาย
    $pic = $_FILES["pic"];              // รูปสินค้า
    $num = $_POST["num"];               // จำนวนสินค้าคงเหลือ

    $date = date("YmdHis");             // วันที่ทำรายการ
    $numrand = mt_rand();               // สุ่มตัวเลข

    $newname = "";                      // ชื่อไฟล์รูปใหม่ (กำหนดเป็นค่าว่างก่อน)
    if ($pic["name"] != "") {           // ตรวจสอบว่ามีการอัปโหลดรูป
        $path = "../image/";            // โฟลเดอร์เก็บไฟล์
        $type = strrchr($pic["name"], "."); // นามสกุลไฟล์
        $newname = $date . $numrand . $type; // ตั้งชื่อไฟล์ใหม่
        $path_copy = $path . $newname;
        move_uploaded_file($pic["tmp_name"], $path_copy); // ย้ายไฟล์ไปยังโฟลเดอร์
    } else {
        // ถ้าไม่มีการอัปโหลดรูปใหม่ ให้ใช้ชื่อรูปเดิม
        $stmt_select = $connect->prepare("SELECT pic FROM product WHERE proid = ?");
        $stmt_select->bind_param("i", $proid);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $row = $result->fetch_assoc();
        $newname = $row['pic']; // ใช้ชื่อเดิม
    }

    // เตรียมคำสั่ง SQL UPDATE ด้วย prepared statement
    $stmt = $connect->prepare("UPDATE product SET proname = ?, prodetail = ?, price = ?, sale = ?, pic = ?, num = ? WHERE proid = ?");
    $stmt->bind_param("ssssssi", $proname, $prodetail, $price, $sale, $newname, $num, $proid); // เพิ่ม $proid เป็น parameter

    // รันคำสั่ง SQL
    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตสำเร็จ'); window.location='index.php?page=product.php';</script>";
    } else {
        echo "<script>alert('อัปเดตไม่สำเร็จ'); window.location='index.php?page=product.php';</script>";
    }

    // ปิด statement หลังจากใช้งานเสร็จ
    $stmt->close();
}
?>

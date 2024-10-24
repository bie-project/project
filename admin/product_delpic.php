<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');

if (isset($_GET["del"]) && $_GET["del"] == "ok") {
    // แก้ไขการดึง id ให้ตรงกับลิงก์
    $proid = $_GET["proid"];

    // ดึงข้อมูลชื่อไฟล์รูปภาพจากฐานข้อมูล
    $delname = "SELECT pic FROM product WHERE proid = '$proid'";
    $resultdelname = mysqli_query($conn, $delname);

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($rowdel = mysqli_fetch_assoc($resultdelname)) {
        $rowpic = $rowdel["pic"];
        
        // ลบไฟล์รูปภาพจากโฟลเดอร์
        if (!empty($rowpic) && file_exists("../image/$rowpic")) {
            @unlink("../image/$rowpic");
        }

        // อัปเดตฐานข้อมูลให้รูปภาพเป็นค่าว่าง
        $sqldel = "UPDATE product SET pic='' WHERE proid='$proid'";
        if (mysqli_query($conn, $sqldel)) {
            echo "<script>alert('ลบข้อมูลรูปภาพสำเร็จ');window.location='index.php?page=product_edit.php&proid=$proid&edit=ok';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลรูปภาพที่ต้องการลบ');</script>";
    }
}
?>

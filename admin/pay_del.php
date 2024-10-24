<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');

// ตรวจสอบว่ามีการส่งค่าผ่าน URL หรือไม่
if (isset($_GET["del"]) && $_GET["del"] == "ok") {
    $id = $_GET["id"];

    // ดึงชื่อไฟล์รูปภาพที่ต้องการลบจากฐานข้อมูล
    $delname = "SELECT ofile FROM orders WHERE oid = '$id'";
    $resultdelname = mysqli_query($conn, $delname);

    if ($resultdelname && mysqli_num_rows($resultdelname) > 0) {
        $rowdel = mysqli_fetch_array($resultdelname);
        $rowpic = $rowdel["ofile"];

        // อัปเดตข้อมูลในฐานข้อมูลให้ค่าว่างในฟิลด์ 'ofile'
        $sqldel = "UPDATE orders SET ofile = '' WHERE oid = '$id'";
        $resultdel = mysqli_query($conn, $sqldel);

        // ตรวจสอบว่าอัปเดตข้อมูลในฐานข้อมูลสำเร็จหรือไม่
        if ($resultdel) {
            // ลบไฟล์ภาพที่เกี่ยวข้อง
            if (!empty($rowpic) && file_exists("../payment/$rowpic")) {
                unlink("../payment/$rowpic");
            }
            echo "<script>alert('ลบข้อมูลภาพสำเร็จ');window.location='index.php?page=order_manage.php&id=$id&pay=ok';</script>";
        } else {
            echo "<script>alert('ไม่สามารถลบข้อมูลได้');window.location='index.php?page=order_manage.php&id=$id&pay=ok';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลรูปภาพ');window.location='index.php?page=order_manage.php&id=$id&pay=ok';</script>";
    }
}
?>

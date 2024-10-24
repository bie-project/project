<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');

if ($_SESSION["session"] == "") {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

$cname = $_SESSION["fname"];

if (isset($_POST["ok"])) {

    $pay   = $_POST['priceend'];  //ราคาสินค้ารวมทั้งหมดรวม
    $oname = $_POST['oname'];
    $oaddr = $_POST['oaddr'];
    $otel  = $_POST['otel'];

    $ndate = date("Y-m-d H:i:s");
    $shipping_fee = 60;
    $newpay = $pay + $shipping_fee;

    $sqlin_order = "INSERT INTO orders (odate, ocusname, oname, oaddr, otel, ototal, ofile, oems, ostatus, odateup) VALUES ('$ndate', '$cname', '$oname', '$oaddr', '$otel', '$newpay', '', '', '1', '$ndate')";
    $resul_oder = mysqli_query($conn, $sqlin_order);

    // ดึงค่า ID ของคำสั่งซื้อที่เพิ่งเพิ่ม
    $oid = $conn->insert_id;

    // เลือกสินค้าที่อยู่ในตะกร้า
    $sqlsel = "SELECT * FROM basket LEFT JOIN product ON basket.proid = product.proid WHERE basket.bcus='$cname' AND basket.bstatus = '0'";
    $resultsel = mysqli_query($conn, $sqlsel);
    $num_rows = mysqli_num_rows($resultsel);

    for ($i = 0; $i < $num_rows; $i++) {
        $row = mysqli_fetch_array($resultsel);
        $bid = $row['bid'];
        $proid = $row['proid'];

        // อัปเดตสถานะของสินค้าในตะกร้า โดยเพิ่ม oid และ proid
        $sqlup_ostatus = "UPDATE basket SET bstatus = '1', oid='$oid', proid='$proid' WHERE bid='$bid'";
        $resultup_ostatus = mysqli_query($conn, $sqlup_ostatus);
    }

    if ($resultsel) {
        while ($row = mysqli_fetch_array($resultsel)) {
            $bid = $row['bid'];
            $proid = $row['proid'];

            // อัปเดตสถานะของสินค้าในตะกร้า โดยเพิ่ม oid และ proid
            $sqlup_ostatus = "UPDATE basket SET bstatus = '1', oid='$oid', proid='$proid' WHERE bid='$bid'";
            $resultup_ostatus = mysqli_query($conn, $sqlup_ostatus);

            // ตรวจสอบความสำเร็จของการอัปเดต
            if (!$resultup_ostatus) {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
                break; // ออกจากลูปหากมีข้อผิดพลาด
            }
        }
    }

    if ($resultup_ostatus) {
        echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=order.php';</script>";
    } else {
        echo "<script>alert('บันทึกไม่สำเร็จ');</script>";
    }

    $conn->close();

    if ($resultsel == TRUE) {
        echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=order.php';</script>";
    } else {
        echo "บันทึกไม่สำเร็จ";
    }
    $conn->close();
}

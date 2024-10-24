<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connect.php");

// ตรวจสอบว่ามีการส่งค่า id, new_quantity และ pid หรือไม่
if (isset($_GET["id"]) && isset($_GET["new_quantity"]) && isset($_GET["pid"])) {
    $order_id = $_GET["id"];
    $new_quantity = $_GET["new_quantity"];  // จำนวนใหม่ที่ต้องการตั้งค่า
    $proid = $_GET["pid"];

    // อัปเดตจำนวนสินค้าที่สั่งซื้อในตาราง orders
    $update_order = "UPDATE orders SET quantity = ? WHERE oid = ? AND product_id = ?";
    $stmt = $conn->prepare($update_order);  // ใช้ prepare statement
    $stmt->bind_param("iii", $new_quantity, $order_id, $proid);  // กำหนดค่าต่างๆ
    $stmt->execute();

    // ตรวจสอบว่าทำงานสำเร็จหรือไม่
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('อัปเดตข้อมูลสินค้าสำเร็จ');window.location='index.php?page=order_manage.php';</script>";
    } else {
        echo "<script>alert('ไม่พบข้อมูลใบสั่งซื้อหรือไม่มีการเปลี่ยนแปลง');window.location='index.php?page=order_manage.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน');window.location='index.php?page=order_manage.php';</script>";
}

$conn->close();
?>

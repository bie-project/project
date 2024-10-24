<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// ตรวจสอบว่ามีการส่งค่า id, bnum และ pid หรือไม่
if (isset($_GET["id"]) && isset($_GET["bnum"]) && isset($_GET["pid"])) {
    $bid = $_GET["id"];
    $bnum = $_GET["bnum"];  // เปลี่ยนจาก $bname เป็น $bnum
    $proid = $_GET["pid"];

    // ลบรายการในตะกร้า
    $sql = "DELETE FROM basket WHERE bid = ?";
    $stmt = $conn->prepare($sql);  // ใช้ prepare statement
    $stmt->bind_param("i", $bid);  // กำหนดค่าเป็น integer
    $stmt->execute();

    // อัปเดตจำนวนสินค้ากลับเข้าไปในสต็อก
    $update = "UPDATE product SET num = num + ? WHERE proid = ?";
    $stmt = $conn->prepare($update);  // ใช้ prepare statement
    $stmt->bind_param("ii", $bnum, $proid);  // ใช้ค่า bnum และ proid
    $stmt->execute();

    // ตรวจสอบว่าทำงานสำเร็จหรือไม่
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('ลบสำเร็จ');window.location='index.php?page=cart.php';</script>";
    } else {
        echo "บันทึกไม่สำเร็จ";
    }

    $stmt->close();
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

$conn->close();
?>

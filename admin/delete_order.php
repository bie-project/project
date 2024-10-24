<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET["del"]) && $_GET["del"] == "ok") {
    // เชื่อมต่อฐานข้อมูล
    include("../connection/connect.php");
    
    $id = $_GET["id"];
    
    // ลบข้อมูลจากตาราง orders
    $sqldel = "DELETE FROM orders WHERE oid = ?";
    $stmt = $conn->prepare($sqldel);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ตรวจสอบข้อมูลจากตาราง basket
    $sqlse = "SELECT * FROM basket WHERE oid = ?";
    $stmt = $conn->prepare($sqlse);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultse = $stmt->get_result();
    
    while ($rowse = $resultse->fetch_assoc()) {
        $bid = $rowse["bid"];
        $bnum = $rowse["bnum"];
        $proid = $rowse['proid'];

        // ลบข้อมูลจากตาราง basket
        $sql = "DELETE FROM basket WHERE bid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bid);
        $stmt->execute();

        // อัปเดตจำนวนสินค้าในตาราง product
        $update = "UPDATE product SET num = num + ? WHERE proid = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ii", $bnum, $proid);
        $stmt->execute();
    }

    // ลบข้อมูลจากตาราง basket สำหรับ oid
    $sqldelb = "DELETE FROM basket WHERE oid = ?";
    $stmt = $conn->prepare($sqldelb);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ตรวจสอบผลลัพธ์
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('ลบข้อมูลสำเร็จ');window.location='index.php?page=order_manage.php';</script>";
    } else {
        echo "<script>alert('ลบข้อมูลไม่สำเร็จ');window.location='index.php?page=order_manage.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

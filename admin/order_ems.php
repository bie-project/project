<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// ตรวจสอบว่ามีการส่งค่า id หรือไม่
if (isset($_GET["id"])) {
    $order_id = $_GET["id"];
} else {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน');window.location='index.php?page=order_manage.php';</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $oems = $_POST["oems"]; // ข้อมูลที่กรอกจากฟอร์มการจัดส่ง

    // อัปเดตข้อมูลการจัดส่งในตาราง orders
    $update_sql = "UPDATE orders SET oems = ? WHERE oid = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $oems, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('อัปเดตข้อมูลการจัดส่งเรียบร้อย');window.location='index.php?page=order_manage.php';</script>";
    } else {
        echo "<script>alert('ไม่สามารถอัปเดตข้อมูลการจัดส่งได้');window.location='index.php?page=order_manage.php';</script>";
    }

    $stmt->close();
}
?>
<br><br>
<div class="container mt-5">
        <h2>กรอกข้อมูลการจัดส่ง</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="oems">ข้อมูลการจัดส่ง:</label>
                <textarea class="form-control" id="oems" name="oems" required></textarea>
            </div><br>
            <button type="submit" class="btn btn-success">บันทึก</button>
            <a href="index.php?page=order_manage.php" class="btn btn-danger">ยกเลิก</a>
        </form>
    </div>
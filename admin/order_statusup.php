<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// Check if the order ID is set
if (isset($_GET["id"])) {
    $order_id = $_GET["id"];

    // Check if the form has been submitted to update the status
    if (isset($_POST["update_status"])) {
        $status = $_POST["status"];

        // Update the order status in the database
        $sql = "UPDATE orders SET ostatus = ? WHERE oid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $status, $order_id);

        if ($stmt->execute()) {
            echo "<script>alert('สถานะคำสั่งซื้อถูกแก้ไขเรียบร้อยแล้ว');window.location='index.php?page=order_manage.php&id=$id&pay=ok';</script>";
        } else {
            echo "<div class='alert alert-danger mt-3'>ไม่สามารถปรับปรุงสถานะคำสั่งซื้อได้.</div>";
        }

        $stmt->close();
    }

    // Fetch the current status for display
    $sqlFetch = "SELECT ostatus FROM orders WHERE oid = ?";
    $stmtFetch = $conn->prepare($sqlFetch);
    $stmtFetch->bind_param("i", $order_id);
    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();

    if ($resultFetch->num_rows > 0) {
        $order = $resultFetch->fetch_assoc();
        $currentStatus = $order["ostatus"];
    } else {
        echo "<div class='alert alert-danger mt-3'>ไม่พบข้อมูลคำสั่งซื้อ.</div>";
    }

    $stmtFetch->close();
} else {
    echo "<div class='alert alert-danger mt-3'>ไม่มีหมายเลขคำสั่งซื้อที่ระบุ.</div>";
}
$conn->close();
?>
<br><br>
<div class="container mt-5">
    <h2>แก้ไขสถานะคำสั่งซื้อ</h2>
    <form action="#" method="post">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
        <div class="form-group">
            <label for="status">เลือกสถานะ:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" <?php echo ($currentStatus == 1) ? 'selected' : ''; ?>>รอการชำระเงิน</option>
                <option value="2" <?php echo ($currentStatus == 2) ? 'selected' : ''; ?>>กำลังตรวจสอบการชำระเงิน</option>
                <option value="3" <?php echo ($currentStatus == 3) ? 'selected' : ''; ?>>กำลังจัดส่งสินค้า</option>
                <option value="4" <?php echo ($currentStatus == 4) ? 'selected' : ''; ?>>จัดส่งสินค้าสำเร็จ</option>
            </select>
        </div><br>
        <button type="submit" name="update_status" class="btn btn-primary">ปรับปรุงสถานะ</button>
    </form>
</div>


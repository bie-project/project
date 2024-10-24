<div class="container mt-5">
    <h2 class="text-center">ค้นหาเลขที่พัสดุที่จัดส่ง</h2>
    <form action="" method="post" class="form-inline justify-content-center">
        <div class="form-group">
            <label for="order_id" class="sr-only">Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control mr-2" placeholder="กรอกเลขที่ใบสั่งซื้อ" required><br>
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </div>
    </form>

    <?php
    // เช็คการส่งข้อมูลจากฟอร์ม
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // เชื่อมต่อกับฐานข้อมูล
        include("../connection/connect.php");

        // ค้นหาข้อมูลจากตาราง orders ตาม oid
        $sql = "SELECT oems FROM orders WHERE oid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // แสดงผลลัพธ์
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            

            // ตรวจสอบค่าว่างของ oems
            if (empty($row["oems"])) {
                echo '<div class="alert alert-danger mt-4" role="alert">';
                echo '<h4 class="alert-heading">ผลการค้นหา:</h4>';
                echo '<p>เลขที่พัสดุ: <strong style="color: black;">กำลังตรวจสอบการชำระเงิน</strong></p>'; // แสดงสีแดง
            } else {
                echo '<div class="alert alert-success mt-4" role="alert">';
                echo '<h4 class="alert-heading">ผลการค้นหา:</h4>';
                echo '<p>เลขที่พัสดุ: <strong>' . htmlspecialchars($row["oems"]) . '</strong></p>';
            }

            echo '</div>';
        } else {
            echo '<div class="alert alert-danger mt-4" role="alert">';
            echo '<h4 class="alert-heading">ไม่พบข้อมูล!</h4>';
            echo '<p>ไม่มีใบสั่งซื้อที่ตรงกับ Order ID นี้.</p>';
            echo '</div>';
        }

        // ปิดการเชื่อมต่อ
        $stmt->close();
        $conn->close();
    }
    ?>
</div>

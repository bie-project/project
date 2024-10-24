<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");





if ($_GET["edit"] == "ok") {
    $proid = $_GET["proid"];
    $sqle = "SELECT * FROM product WHERE proid = ?";
    $stmt = $conn->prepare($sqle); // ใช้ prepared statement เพื่อป้องกัน SQL Injection
    $stmt->bind_param("i", $proid); // ผูกค่า proid เข้ากับการ query
    $stmt->execute(); // รันคำสั่ง
    $resulte = $stmt->get_result(); // ดึงผลลัพธ์จาก query

    $rowe = $resulte->fetch_assoc(); // ดึงข้อมูลเป็น array
?>

    <br>
    <h5>แก้ไขข้อมูลสินค้า</h5>

    <form action="product_update.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="proname">ชื่อสินค้า:</label>
            <input type="text" class="form-control" id="proname" name="proname" value="<?php echo ($rowe["proname"]) ?>" required>
        </div>

        <div class="form-group">
            <label for="prodetail">รายละเอียดสินค้า:</label>
            <textarea class="form-control" rows="3" id="prodetail" name="prodetail" required><?php echo ($rowe["prodetail"]); ?></textarea>
        </div>


        <div class="form-group">
            <label for="price">ราคาสินค้า:</label>
            <input type="text" class="form-control" id="price" name="price" value="<?php echo ($rowe["price"]) ?> บาท" required>
        </div>

        <div class="form-group">
            <label for="sale">ราคาขาย:</label>
            <input type="text" class="form-control" id="sale" name="sale" value="<?php echo ($rowe["sale"]) ?> บาท" required>
        </div><br>

        <div class="form-group">
            <label for="pic">รูปสินค้า:</label>

            <!-- แสดงภาพสินค้าเดิม (ถ้ามี) -->
            <?php if (!empty($rowe["pic"])): ?>
                <img src="../image/<?php echo ($rowe["pic"]); ?>" alt="Product Image" style="max-width: 200px; height: auto;">
                <a href="index.php?page=product_delpic.php&proid=<?php echo ($rowe["proid"]); ?>&del=ok">
                    <button type="button" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ');">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </a>
                <br><br>
            <?php else: ?>
                <p>ไม่มีรูปภาพสำหรับสินค้านี้</p>
            <?php endif; ?>

            <td>เปลี่ยรูปภาพ</td2>
            <!-- ช่องสำหรับอัปโหลดรูปใหม่ -->
            <input type="file" class="form-control-file" id="pic" name="pic">
        </div><br>


        <div class="form-group">
            <label for="num">จำนวนคงเหลือ:</label>
            <input type="text" class="form-control" id="num" name="num" value="<?php echo ($rowe["num"]) ?> ชิ้น" required>
        </div>

        <br>
        <button type="submit" class="btn btn-success" name="ok" value="add">ยืนยัน</button>
        <a href="index.php?page=product.php"><button type="button" class="btn btn-warning">ยกเลิก</button></a>
        <input type="hidden" class="form-control" id="proid" name="proid" value="<?php echo ($rowe["proid"]) ?>">
    </form>


<?php
  $stmt->close(); // ปิด statement
} else {
    echo "<script>alert('ไม่สามารถแก้ไขข้อมูลได้'); window.location='index.php?page=product.php';</script>";
}
?>
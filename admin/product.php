<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connect.php");
if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู้ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

if (isset($_GET["del"]) && $_GET["del"] == "ok" && isset($_GET["proid"])) {
    $proid = $_GET["proid"];

    // ดึงชื่อไฟล์รูปภาพก่อนลบ
    $delname = "SELECT pic FROM product WHERE proid = ?";
    $stmt = $connect->prepare($delname);
    $stmt->bind_param("i", $proid);
    $stmt->execute();
    $result = $stmt->get_result(); // ใช้ get_result เพื่อดึงข้อมูล
    $row_del = $result->fetch_assoc(); // ดึงข้อมูลแถวเดียว

    if ($row_del) {
        $row_pic = $row_del["pic"];
        // ลบไฟล์รูป
        @unlink("../image/$row_pic");
    }

    // ลบข้อมูลสินค้า
    $stmt = $connect->prepare("DELETE FROM product WHERE proid = ?");
    $stmt->bind_param("i", $proid);
    $result_delete = $stmt->execute();

    if ($result_delete) {
        echo "<script>alert('ลบสำเร็จ'); window.location='index.php?page=product.php';</script>";
    } else {
        echo "<script>alert('ลบไม่สำเร็จ'); window.location='index.php?page=product.php';</script>";
    }
    $stmt->close();
}
?>
<div><br><br><br>
    <h2 class="text-center">จัดการข้อมูลสินค้า</h2>
    <div><a href="index.php?page=product_add.php"><button type="button" class="btn btn-success"><i class="bi bi-folder-plus"></i>&nbsp;เพิ่มข้อมูลสินค้า</button></a></div>
    <br>

    <!-- กำหนด div สำหรับตารางข้อมูลที่สามารถเลื่อนได้ -->
    <div style="max-height: 600px; overflow-y: auto; border: 1px solid #ddd;">
        <table class="table table-hover">
            <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th>รายละเอียดสินค้า</th>
                    <th>ราคาสินค้า</th>
                    <th>ราคาขาย</th>
                    <th>รูปสินค้า</th>
                    <th>จำนวนคงเหลือ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_product = "SELECT * FROM product";
                $result_product = mysqli_query($connect, $sql_product);
                $i = 1;
                while ($row_product = mysqli_fetch_array($result_product)) {
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo ($row_product["proname"]); ?></td>
                        <td><?php echo ($row_product["prodetail"]); ?></td>
                        <td><?php echo ($row_product["price"]); ?> บาท</td>
                        <td><?php echo ($row_product["sale"]); ?> บาท</td>
                        <td><img src="../image/<?php echo ($row_product["pic"]); ?>" width="250" height="250"></td>
                        <td><?php echo ($row_product["num"]); ?> ชิ้น</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="index.php?page=product_review.php&proid=<?php echo ($row_product["proid"]); ?>">
                                    <button type="button" class="btn btn-info"><i class="bi bi-eye-fill"></i></button>
                                </a>
                                <a href="index.php?page=product_edit.php&proid=<?php echo ($row_product["proid"]); ?>&edit=ok">
                                    <button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                </a>
                                <a href="index.php?page=product.php&proid=<?php echo ($row_product["proid"]); ?>&del=ok">
                                    <button type="button" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ');">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


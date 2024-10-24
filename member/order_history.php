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

// แสดงข้อมูลการสั่งซื้อโดยการใช้ DISTINCT ใน SELECT เพื่อเลือก oid ที่ไม่ซ้ำกัน
$sql = "SELECT DISTINCT orders.oid, orders.odate, orders.oname, orders.oaddr, orders.otel, orders.ototal, orders.ostatus 
        FROM orders 
        INNER JOIN member ON orders.ocusname = member.fname 
        WHERE orders.ocusname = ? 
        ORDER BY orders.odate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
?>
<br><h2 class="text-center">ประวัติการสั่งซื้อของคุณ</h2>
<table width="90%" class="table table-striped table-bordered">
    <tr>
        <th>เลขที่ใบสั่งซื้อ</th>
        <th>วันที่สั่งซื้อ</th>
        <th>ชื่อผู้รับสินค้า</th>
        <th>ที่อยู่จัดส่ง</th>
        <th>เบอร์โทร</th>
        <th>ราคารวม</th>
        <th>สถานะ</th>
        <th>รายละเอียดสินค้า</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["oid"]; ?></td>
            <td><?php echo $row["odate"]; ?></td>
            <td><?php echo htmlspecialchars($row["oname"]); ?></td>
            <td><?php echo htmlspecialchars($row["oaddr"]); ?></td>
            <td><?php echo htmlspecialchars($row["otel"]); ?></td>
            <td><?php echo number_format($row["ototal"], 2); ?> บาท</td>
            <td>
                <?php
                switch ($row["ostatus"]) {
                    case 1:
                        echo '<span style="color: red;">รอการชำระเงิน</span>';
                        break;
                    case 2:
                        echo '<span style="color: orange;">กำลังตรวจสอบการชำระเงิน</span>';
                        break;
                    case 3:
                        echo '<span style="color: lightblue;">กำลังจัดส่งสินค้า</span>';
                        break;
                    case 4:
                        echo '<span style="color: green;">จัดส่งสินค้าสำเร็จ</span>';
                        break;
                    default:
                        echo '<span>สถานะไม่ทราบ</span>';
                        break;
                }
                ?>
            </td>
            <td>
                <button class="btn btn-info" data-toggle="collapse" data-target="#details<?php echo $row["oid"]; ?>">
                    ดูรายละเอียดสินค้า
                </button>
            </td>
        </tr>
        <tr id="details<?php echo $row["oid"]; ?>" class="collapse">
            <td colspan="8">
                <?php
                $order_id = $row["oid"];
                $sqlProducts = "
                    SELECT product.proname, product.sale, basket.bnum
                    FROM basket 
                    LEFT JOIN product ON basket.proid = product.proid 
                    WHERE basket.oid = ?";
                $stmtProducts = $conn->prepare($sqlProducts);
                $stmtProducts->bind_param("i", $order_id);
                $stmtProducts->execute();
                $resultProducts = $stmtProducts->get_result();

                if ($resultProducts->num_rows > 0) {
                    echo "<table class='table table-bordered'>
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวน</th>
                                <th>ราคารวม</th>
                            </tr>";
                    while ($product = $resultProducts->fetch_assoc()) {
                        $totalPrice = $product['sale'] * $product['bnum'];
                        echo "<tr>
                                <td>" . htmlspecialchars($product['proname']) . "</td>
                                <td>" . number_format($product['sale'], 2) . "</td>
                                <td>" . $product['bnum'] . "</td>
                                <td>" . number_format($totalPrice, 2) . "</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='alert alert-info'>ไม่มีสินค้าที่เกี่ยวข้องกับคำสั่งซื้อนี้</div>";
                }
                $stmtProducts->close();
                ?>
            </td>
        </tr>
    <?php } ?>
</table>
<?php
} else {
    echo '
    <div style="display: flex; justify-content: center; align-items: center; height: 200px;">
        <div style="padding: 20px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24; width: 50%; text-align: center;">
            <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 10px; color: #721c24;"></i>
            <p style="font-size: 18px; font-weight: bold; margin: 0;">ยังไม่มีประวัติการสั่งซื้อ</p>
        </div>
    </div>
';
}

$conn->close();
?>

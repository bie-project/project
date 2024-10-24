<br>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

?>


<br><br>
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่ใบสั่งซื้อ</th>
                        <th>วันที่สั่งซื้อสินค้า</th>
                        <th>ชื่อผู้รับ</th>
                        <th>ที่อยู่จัดส่ง</th>
                        <th>ราคาทั้งหมด</th>
                        <th>ไฟล์สลิป</th>
                        <th>เลขที่พัสดุ ems</th>
                        <th>สถานะสั่งซื้อ</th>
                        <th>รายละเอียดสินค้า</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlseo = "SELECT * FROM orders ORDER BY odate DESC";
                    $resultseo = $conn->query($sqlseo);

                    while ($rowseo = $resultseo->fetch_assoc()) {
                    ?>
                        <tr data-toggle="collapse" data-target="#details-<?php echo $rowseo['oid']; ?>" class="clickable-row">
                            <td align="center"><?php echo ($rowseo["oid"]); ?></td>
                            <td align="center"><?php echo ($rowseo["odate"]); ?></td>
                            <td align="center"><?php echo ($rowseo["ocusname"]); ?></td>
                            <td align="center"><?php echo ($rowseo["oaddr"]); ?></td>
                            <td align="center"><?php echo ($rowseo["ototal"]); ?> ฿</td>
                            <td>
                                <?php
                                if (empty($rowseo["ofile"])) {
                                    echo "ยังไม่ได้อัพโหลดไฟล์";
                                } else {
                                ?>
                                    <a href="../payment/<?php echo ($rowseo["ofile"]); ?>">
                                        <img src="../image/down.png" width="50px" alt="Download">
                                    </a>
                                <?php
                                }
                                ?>
                            </td>
                            <td align="center"><?php echo ($rowseo["oems"]); ?></td>
                            <td align="center">
                                <?php
                                switch ($rowseo["ostatus"]) {
                                    case 1:
                                        echo '<span style="color: red;">รอการชำระเงิน</span>';
                                        break;
                                    case 2:
                                        echo '<span style="color: orange;">กำลังตรวจสอบการชำระเงิน</span>';
                                        break;
                                    case 3:
                                        echo '<span style="color: Light Blue;">กำลังจัดส่งสินค้า</span>';
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
                            <td align="center">
                                <button class="btn btn-primary" data-toggle="collapse" data-target="#details-<?php echo $rowseo['oid']; ?>">ดูรายละเอียด</button>
                            </td>
                            <td>
                                <a href="index.php?page=order_statusup.php&id=<?php echo ($rowseo["oid"]); ?>&up=ok" onclick="return confirm('คุณแน่ใจที่จะจัดการหรือไม่')">
                                    <button type="button" class="btn btn-info"><i class="far fa-edit"></i> สถานะ</button>
                                </a>
                                <a href="index.php?page=order_ems.php&id=<?php echo ($rowseo["oid"]); ?>&send=ok" onclick="return confirm('คุณแน่ใจที่จะจัดส่งสินค้าหรือไม่')">
                                    <button type="button" class="btn btn-success"><i class="fas fa-shipping-fast"></i> เพิ่มเลขที่พัสดุ</button>
                                </a>
                                <a href="index.php?page=delete_order.php&id=<?php echo ($rowseo["oid"]); ?>&del=ok" onclick="return confirm('คุณแน่ใจที่จะลบใบสั่งซื้อนี้หรือไม่?')">
                                    <button type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i> ลบ</button>
                                </a>
                            </td>
                        </tr>
                        <tr id="details-<?php echo $rowseo['oid']; ?>" class="collapse">
                            <td colspan="10">
                                <div class="p-3">
                                    <!-- ดึงรายละเอียดสินค้า -->
                                    <?php
                                    $orderId = $rowseo['oid'];
                                    $sqlProducts = "
                                    SELECT product.proname, product.sale, basket.bnum 
                                    FROM basket 
                                    LEFT JOIN product ON basket.proid = product.proid 
                                    WHERE basket.oid = ?";
                                    $stmtProducts = $conn->prepare($sqlProducts);
                                    $stmtProducts->bind_param("i", $orderId);
                                    $stmtProducts->execute();
                                    $resultProducts = $stmtProducts->get_result();

                                    if ($resultProducts->num_rows > 0) {
                                        echo "<table class='table table-bordered'>";
                                        echo "<thead><tr><th>ชื่อสินค้า</th><th>ราคา</th><th>จำนวน</th><th>ราคารวม</th></tr></thead><tbody>";

                                        while ($product = $resultProducts->fetch_assoc()) {
                                            $totalPrice = $product['sale'] * $product['bnum'];
                                            echo "<tr>
                                                <td>" . htmlspecialchars($product['proname']) . "</td>
                                                <td>" . number_format($product['sale'], 2) . " ฿</td>
                                                <td>" . $product['bnum'] . "</td>
                                                <td>" . number_format($totalPrice, 2) . " ฿</td>
                                            </tr>";
                                        }
                                        echo "</tbody></table>";
                                    } else {
                                        echo "ไม่มีสินค้าที่เกี่ยวข้องกับคำสั่งซื้อนี้";
                                    }

                                    $stmtProducts->close();
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sqlm = "SELECT * FROM product WHERE proid = '$proid'";
$resultm = mysqli_query($conn, $sqlm);
$rowm = mysqli_fetch_assoc($resultm);
?>
<br><br>
<div class="row">
    <div class="col-sm-12">
        <table width="90%" class="table table-striped table-bordered">
            <tr>
                <th>ลำดับ</th>
                <th>วันที่สั่งซื้อสินค้า</th>
                <th>ชื่อลูกค้า</th>
                <th>ที่อยู่จัดส่ง</th>
                <th>ราคาทั้งหมด</th>
                <th>ไฟล์สลิป</th>
                <th>EMS</th>
                <th>สถานะสั่งซื้อ</th>
                <th>จัดการ</th>
            </tr>
            <?php
            // ดึงข้อมูลการสั่งซื้อจากฐานข้อมูล
            $sqlseo = "SELECT * FROM orders";
            $resultseo = $conn->query($sqlseo);

            $i = 1;
            while ($rowseo = $resultseo->fetch_assoc()) {
            ?>
                <tr>
                    <td align="center"><?php echo $i; ?></td>
                    <td align="center"><?php echo ($rowseo["odate"]); ?></td>
                    <td align="center"><?php echo ($rowseo["ocusname"]); ?></td>
                    <td align="center"><?php echo ($rowseo["oaddr"]); ?></td>
                    <td align="center"><?php echo ($rowseo["ototal"]); ?></td>
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
                    <td align="center"><?php echo ($rowseo["ostatus"]); ?></td>
                    <th>
                        <a href="index.php?page=order.php&id=<?php echo ($rowseo["oid"]); ?>&up=ok" onclick="return confirm('คุณแน่ใจที่จะจัดการหรือไม่')">
                            <button type="button" class="btn btn-info"><i class="far fa-edit"></i> สถานะ</button>
                        </a>
                        <a href="index.php?page=order.php&id=<?php echo ($rowseo["oid"]); ?>&send=ok" onclick="return confirm('คุณแน่ใจที่จะจัดส่งสินค้าหรือไม่')">
                            <button type="button" class="btn btn-success"><i class="fas fa-shipping-fast"></i> จัดส่งสินค้า</button>
                        </a>
                        <a href="index.php?page=order.php&id=<?php echo ($rowseo["oid"]); ?>&del=ok" onclick="return confirm('คุณแน่ใจที่จะลบหรือไม่')">
                            <button type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i> ลบ</button>
                        </a>
                    </th>
                </tr>
            <?php
                $i++;
            }
            ?>
        </table>
    </div>
</div>
<?php
	echo "ยินดีต้อนรับเข้าสู่เว็บไซต์";
?>
<?php
	$page = $_GET["page"];
	
	if($page != ""){
		include("$page");
	}else{
		echo "กรุณาเลือกเมนู";
	}
	
?>


<?php include("productall.php"); ?>
/////////////////////////////////////////////////////////
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');

// ตรวจสอบว่ามีการส่งค่า 'id' มาหรือไม่
if (isset($_GET["id"])) {
    $proid = $_GET["id"];
} else {
    echo "<script>alert('ไม่มีรหัสสินค้า'); window.location.href = 'index.php?page=productall.php';</script>";
    exit();
}




// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sqlm = "SELECT * FROM product WHERE proid = '$proid'";
$resultm = mysqli_query($conn, $sqlm);
$rowm = mysqli_fetch_assoc($resultm);

// ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
if (!$rowm) {
    echo "<script>alert('ไม่พบข้อมูลสินค้า'); window.location.href = 'index.php?page=productall.php';</script>";
    exit();
}
?>

<div class="row">
    <div class="col-sm-4 d-flex flex-wrap justify-content-start gap-3">

        <div class="card" style="width: 18rem;">
            <!-- ใช้ htmlspecialchars เพื่อป้องกันการโจมตี XSS -->
            <img src="../image/<?php echo ($rowm['pic']); ?>" class="card-img-top" alt="Product Image" style="width: 100%; height: 250px;">
            <br><br>
            <form action="#" name="order" method="post" enctype="multipart/form-data">
                <p>เลือก
                    <select name="num">
                        <?php
                        // แสดงจำนวนสินค้าที่สามารถเลือกได้
                        for ($i = 1; $i <= $rowm["num"]; $i++) {
                        ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                    </select> ชิ้น
                </p>

                <!-- ตรวจสอบหากสินค้ายังไม่มีให้เลือก -->
                <?php if (empty($proid)) { ?>
                    <input type="button" class="btn btn-info" value="เลือกสินค้า" onclick="window.location ='index.php?page=productall.php'" />
                <?php } ?>

                <input type="submit" class="btn btn-success" name="ok" value="ยืนยันการสั่งซื้อ">
                <a href="index.php?page=productall.php">
                    <button type="button" class="btn btn-danger">ยกเลิก</button>
                </a>

                <input type="hidden" name="cname" value="<?php echo ($_SESSION["fname"]); ?>" />
                <input type="hidden" name="sale" value="<?php echo ($rowm["sale"]); ?>" />
                <input type="hidden" name="proid" value="<?php echo ($rowm["proid"]); ?>" />
            </form>
        </div>
    </div>
<br>
    <div class="col-sm-8">
        <div class="card-body">
            <p><label for="proname"><b>ชื่อสินค้า :</b></label>&nbsp;<?php echo ($rowm['proname']); ?></p>
            <p><label for="prodetail"><b>รายละเอียดสินค้า:</b></label><?php echo ($rowm['prodetail']); ?></p>
            <p><label for="price"><b>ราคาสินค้า :</b></label>&nbsp;<del style="color:gray;"><?php echo ($rowm['price']); ?></del><b> บาท/ชิ้น</b></p>
            <p><label for="sale"><b>ราคาขาย :</b></label><b style="color:red;">&nbsp;<?php echo ($rowm['sale']); ?></b><b> บาท/ชิ้น</b></p>
            <p><label for="num"><b>จำนวนคงเหลือ :</b></label>&nbsp;<?php echo ($rowm['num']); ?><b> ชิ้น</b></p>
        </div>
    </div>
</div>

<br>

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
                        <a href="index.php?page=order.php&id=<?php echo ($rowseo["oid"]); ?>&pay=ok" onclick="return confirm('คุณแน่ใจที่จะจัดการแจ้งชำระเงินหรือไม่')">
                            <button type="button" class="btn btn-warning"><i class="fas fa-money-bill-alt"></i>ชำระเงิน</button>
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
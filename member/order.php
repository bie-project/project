<br>
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
    echo "<script>alert('กรุณาเลือกสินค้า'); window.location.href = 'index.php?page=productall.php';</script>";
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

if (isset($_POST["ok"])) {
    $cname = $_POST["cname"];
    $proid = $_POST["proid"];
    $num = $_POST["num"];
    $sale = $_POST["sale"];
    $pay = $num * $sale;
    $date = date("Y-m-d H:i:s");

    if ($num <= '0') {
        echo "<script>alert('ไม่สามารถบันทึกได้');window.location='index.php?page=order.php';</script>";
        //exit();
    } else {
        $sqlin_bas = " INSERT INTO basket (oid, proid, bnum , bprice, btotal, bcus, bdate) VALUES ('','$proid','$num','$sale','$pay','$cname','$date')";
        $resultin_bas = mysqli_query($conn, $sqlin_bas);

        $sqlup_pro = "UPDATE product SET num = (num - '$num') WHERE proid = '$proid'";
        $resultup_pro = mysqli_query($conn, $sqlup_pro);

        if ($resultin_bas == TRUE && $resultup_pro == TRUE) {
            echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=cart.php';</script>";
        } else {
            echo "บันทึกไม่สำเร็จ";
        }
        $conn->close();
    }
}


?>
<div class="row">
    <div class="col-sm-4 d-flex flex-wrap justify-content-start gap-3">
        <div class="card" style="width: 18rem;">
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

                <input type="submit" class="btn btn-success" name="ok" value="เพิ่มสินค้าลงตะกร้า">
                <a href="index.php?page=productall.php">
                    <button type="button" class="btn btn-danger">ยกเลิก</button>
                </a>

                <input type="hidden" name="cname" value="<?php echo ($_SESSION["fname"]); ?>" />
                <input type="hidden" name="sale" value="<?php echo ($rowm["sale"]); ?>" />
                <input type="hidden" name="proid" value="<?php echo ($rowm["proid"]); ?>" />
            </form>
        </div>
    </div>

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




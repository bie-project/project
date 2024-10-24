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

if (isset($_POST["ok"])) {

    $pay   = $_POST['priceend'];  //ราคาสินค้ารวมทั้งหมดรวม
    $oname = $_POST['oname'];
    $oaddr = $_POST['oaddr'];
    $otel  = $_POST['otel'];

    $ndate = date("Y-m-d H:i:s");
    $shipping_fee = 60;
    $newpay = $pay + $shipping_fee;

    $sqlin_order = "INSERT INTO orders (odate, ocusname, oname, oaddr, otel, ototal, ofile, oems, ostatus, odateup) VALUES ('$ndate', '$cname', '$oname', '$oaddr', '$otel', '$newpay', '', '', '1', '$ndate')";
    $resul_oder = mysqli_query($conn, $sqlin_order);

    // ดึงค่า ID ของคำสั่งซื้อที่เพิ่งเพิ่ม
    $oid = $conn->insert_id;

    // เลือกสินค้าที่อยู่ในตะกร้าและดึง proid จาก product
    $sqlsel = "SELECT basket.bid, product.proid 
           FROM basket 
           LEFT JOIN product ON basket.proid = product.proid 
           WHERE basket.bcus='$cname' AND basket.bstatus = '0'";
    $resultsel = mysqli_query($conn, $sqlsel);
    $num_rows = mysqli_num_rows($resultsel);

    for ($i = 0; $i < $num_rows; $i++) {
        $row = mysqli_fetch_array($resultsel);
        $bid = $row['bid'];
        $proid = $row['proid'];

        // อัปเดตสถานะของสินค้าในตะกร้าโดยเพิ่ม oid และ proid
        $sqlup_ostatus = "UPDATE basket SET bstatus = '1', oid='$oid', proid='$proid' WHERE bid='$bid'";
        $resultup_ostatus = mysqli_query($conn, $sqlup_ostatus);
    }


    if ($resultsel == TRUE) {
        echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=order_history.php';</script>";
    } else {
        echo "บันทึกไม่สำเร็จ";
    }
    $conn->close();
}


?>

<div style="display: flex; justify-content: center;">
    <h3>ตะกร้าสินค้า</h3>
</div><br>
<table width="90%" class="table table-striped table-bordered">
    <tr>
        <th>ลำดับ</th>
        <th>ชื่อสินค้า</th>
        <th>จำนวน</th>
        <th>ราคา</th>
        <th>ราคารวม</th>
        <th>จัดการ</th>
    </tr>
    <?php
    $sqlsel = "SELECT * FROM basket LEFT JOIN product ON basket.proid = product.proid WHERE basket.bstatus='0' AND basket.bcus='$cname'";
    $resultsel = mysqli_query($conn, $sqlsel);
    $numl = mysqli_num_rows($resultsel);

    if ($numl < 1) {
        echo "<script>alert('คุณยังไม่มีรายการสั่งซื้อ');window.location='index.php?page=order.php';</script>";
    } else {
        for ($i = 1; $i <= $numl; $i++) {
            $rowl = mysqli_fetch_array($resultsel);
    ?>

            <tr>
                <td align="center"><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars($rowl["proname"]); ?></td>
                <td><?php echo htmlspecialchars($rowl["bnum"]); ?> ชิ้น</td>
                <td><?php echo htmlspecialchars($rowl["sale"]); ?> บาท</td>
                <td><?php echo htmlspecialchars($rowl["bnum"] * $rowl["sale"]); ?> บาท</td>
                <td>
                    <a href="index.php?page=cart_del.php&id=<?php echo $rowl["bid"]; ?>
                    &bnum=<?php echo $rowl["bnum"]; ?>&pid=<?php echo $rowl["proid"]; ?>"
                        onclick="return confirm('คุณแน่ใจที่จะลบหรือไม่')">
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>&nbsp;ลบ
                        </button>
                    </a>
                </td>
            </tr>
        <?php
        }


        $query = "SELECT SUM(btotal) AS total FROM basket WHERE bstatus = '0' AND bcus = '$cname'";
        $re = mysqli_query($conn, $query);
        $ro = mysqli_fetch_array($re);

        // ตรวจสอบค่า $ro
        if ($ro !== null && isset($ro["total"])) {
            $sum = $ro["total"];
        } else {
            $sum = 0; // หากไม่มีสินค้าในตะกร้า ให้ $sum เป็น 0
        }

        ?>

        <tr>
            <td align="right" colspan="3">รายการสินค้าสินค้า</td>
            <td align="center" colspan="3"><?php echo $numl; ?> รายการ</td>
        </tr>
        <tr>
            <td align="right" colspan="3">ราคาสินค้าทั้งหมด</td>
            <td align="center" colspan="3"><?php echo $sum; ?> บาท</td>
        </tr>
        <tr>
            <td align="right" colspan="3">ค่าบริการจัดส่ง EMS</td>
            <td align="center" colspan="3">60 บาท</td>
        </tr>
        <tr>
            <td align="right" colspan="3">ราคารวมสินค้าทั้งหมด+ค่าบริการจัดส่ง</td>
            <td align="center" colspan="3"><?php $sumall = $sum + 60;
                                            echo $sumall; ?> บาท</td>
        </tr>
        <tr>
            <td align="center" colspan="6" style="padding: 10px;">
                <?php if ($cname != '') {
                    // ดึงข้อมูลสมาชิกที่ล็อกอินอยู่
                    $sql_member = "SELECT * FROM member WHERE fname='$cname'";
                    $result_member = mysqli_query($conn, $sql_member);
                    $member_data = mysqli_fetch_array($result_member);

                    // ตรวจสอบว่ามีข้อมูลสมาชิกหรือไม่
                    $fname = isset($member_data['fname']) ? $member_data['fname'] : '';
                    $address = isset($member_data['address']) ? $member_data['address'] : '';
                    $tel = isset($member_data['tel']) ? $member_data['tel'] : '';
                ?>

                    <form action="#" method="post">
                    <div class="form-group" style="text-align: left;">
                                    <b>ข้อมูลการจัดส่งสินค้า:</b>
                                    <br><label for="oname">ชื่อผู้รับ</label>
                                    <input type="text" class="form-control" id="oname" name="oname" value="<?php echo htmlspecialchars($fname); ?>" required>
                                    <br><label for="oaddr">ที่อยู่ที่ให้จัดส่ง</label>
                                    <input type="text" class="form-control" id="oaddr" name="oaddr" value="<?php echo htmlspecialchars($address); ?>" required>
                                    <br><label for="otel">เบอร์โทรที่ติดต่อได้</label>
                                    <input type="text" class="form-control" id="otel" name="otel" value="<?php echo htmlspecialchars($tel); ?>" required>
                                </div>
                        <br>
                        <input type="button" class="btn btn-info" value="เลือกสินค้าเพิ่ม" onclick="window.location='index.php?page=productall.php'" />
                        <?php if ($numl > 0) { ?>
                            <input type="submit" class="btn btn-success" name="ok" value="ยืนยันการสั่งซื้อ">
                        <?php } ?>
                        <input type="hidden" name="add" value="add" />
                        <input type="hidden" name="priceend" value="<?php echo $sum; ?>" />
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
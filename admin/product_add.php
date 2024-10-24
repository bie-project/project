<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connect.php");

if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

if (isset($_POST["ok"]) && $_POST["ok"] != "") {

    $proname = $_POST["proname"];        // ชื่อสินค้า
    $prodetail = $_POST["prodetail"];    // รายละเอียดสินค้า
    $price = $_POST["price"];           //ราคาสินค้า
    $sale = $_POST["sale"];             //ราคาขาย
    $pic = $_FILES["pic"];              //รูปสินค้า
    $num = $_POST["num"];               //จำนวนสินค้าคงเหลือ

    $date = date("YmdHis");           // วันที่ทำรายการ
    $numrand = (mt_rand());           // สุ่มตัวเลขสุ่ม

    $path_link = "";
    if ($pic["name"] != "") { // ตรวจสอบว่ามีการอัปโหลดรูป
        $path = "../image/";
        $type = strrchr($pic["name"], ".");
        $newname = $date . $numrand . $type;
        $path_copy = $path . $newname;
        $path_link = $path . $newname;
        move_uploaded_file($pic["tmp_name"], $path_copy);
    }

   
    $stmt = $connect->prepare("INSERT INTO product (proname, prodetail, price, sale, pic, num) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $proname, $prodetail, $price, $sale, $newname, $num);
    $resultin = $stmt->execute();

    if ($resultin) {
        echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php?page=product.php';</script>";
    } else {
        echo "<script>alert('บันทึกไม่สำเร็จ'); window.location='index.php?page=product.php';</script>";
    }
    $stmt->close();
}

?>

<h5>กรุณากรอกข้อมูลสินค้า</h5>

<form action="#" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="proname">ชื่อสินค้า:</label>
        <input type="text" class="form-control" id="proname" name="proname" required>
    </div>

    <div class="form-group">
        <label for="prodetail">รายละเอียดสินค้า:</label>
        <textarea class="form-control" rows="3" id="prodetail" name="prodetail" required></textarea>
    </div>

    <div class="form-group">
        <label for="price">ราคาสินค้า:</label>
        <input type="text" class="form-control" id="price" name="price" required>
    </div>

    <div class="form-group">
        <label for="sale">ราคาขาย:</label>
        <input type="text" class="form-control" id="sale" name="sale" required>
    </div><br>

    <div class="form-group">
        <label for="pic">รูปสินค้า:</label>
        <input type="file" class="form-control-file" id="pic" name="pic">
    </div><br>

    <div class="form-group">
        <label for="num">จำนวนคงเหลือ:</label>
        <input type="text" class="form-control" id="num" name="num" required>
    </div>

    <br>
    <button type="submit" class="btn btn-success" name="ok" value="add">ยืนยัน</button>
    <a href="index.php?page=product.php"><button type="button" class="btn btn-warning">ยกเลิก</button></a>
</form>

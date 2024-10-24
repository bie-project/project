<?php
session_start();
if ($_SESSION["session"] == '') {
  echo "<script>alert('กรุณาเข้าสู้ระบบ');window.location='../index.php?page=form_login.php';</script>";
}
?>
<?php
include("../layout.php");
?>
<div class="container-fluid" style="min-height: 100vh; padding: 2; margin: 2;">
  <div class="row">
    <div class="col-sm-12">
      <img src="../image/header1.jpg" width="100%">
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php"><i class="bi bi-house-fill"></i>หน้าหลัก</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item"><a class="nav-link" href="index.php?page=order.php"><i class="bi bi-cart-fill"></i>&nbsp;วิธีการสั่งซื้อ</a></li>
              <li class="nav-item">
                <a class="nav-link" href="index.php?page=contact.php">
                  <i class="bi bi-telephone-fill"></i>&nbsp;เกี่ยวกับ
                </a>
              </li>
             
            </ul>
            <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../logout.php" style="text-decoration:none;"><i class="bi bi-box-arrow-right">&nbsp;ออกจากระบบ</i></a></li>
          </ul> 
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      <ul class="list-group">
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=productall.php" style="text-decoration:none;"><i class="bi bi-bag-fill"></i>&nbsp;สินค้าของเรา</a></li>
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=cart.php" style="text-decoration:none;"><i class="bi bi-cart"></i>&nbsp;ตะกร้าสินค้า</a></li> <!-- ใช้ bi-cart สำหรับตะกร้าสินค้า -->
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=order_history.php" style="text-decoration:none;"><i class="bi bi-clock-history"></i>&nbsp;ประวัติการสั่งซื้อ</a></li> <!-- ใช้ bi-clock-history สำหรับประวัติการสั่งซื้อ -->
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=pay.php" style="text-decoration:none;"><i class="bi bi-credit-card"></i>&nbsp;การชำระเงิน</a></li> <!-- ใช้ bi-credit-card สำหรับการชำระเงิน -->
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=tracking.php" style="text-decoration:none;"><i class="bi bi-truck"></i>&nbsp;ติดตามการจัดส่งสินค้า</a></li>
        <li class="list-group-item list-group-item-warning">
          <a href="index.php?page=member_edit.php" style="text-decoration:none;">
            <i class="bi bi-person-circle"></i>&nbsp;แก้ไขข้อมูลส่วนตัว
          </a>
        </li>
        <li class="list-group-item list-group-item-warning"><a href="../logout.php" style="text-decoration:none;"><i class="bi bi-box-arrow-right"></i>&nbsp;ออกจากระบบ</a></li>
      </ul>
    </div>
    <div class="col-sm-10">
      <?php
      

      if (isset($_GET["page"])) {
        $page = $_GET["page"];

        // ตรวจสอบความปลอดภัยของไฟล์ที่รวม
        if (file_exists($page) && strpos($page, "..") === false && strpos($page, "/") === false) {
          include($page);
        } else {
          echo "<br>ไม่พบหน้าเพจที่คุณต้องการ";
        }
      } else {
        include("productall.php");
      }
      ?>
    </div>
  </div>

  <?php include('../footer.php'); ?>

</div>
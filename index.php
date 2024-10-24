<?php
include("layout.php");
?>

<div class="container-fluid" style="min-height: 100vh; padding: 2; margin: 2;">
  <?php include('nav.php'); ?>
  <!-- nav start -->
  
  <div class="row">
    <div class="col-sm-12">
      <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">Home</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-fill"></i>&nbsp;หน้าหลัก</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
             <li class="nav-item"><a class="nav-link" href="index.php?page=form_login.php"><i class="bi bi-box-arrow-in-right"></i>&nbsp;เข้าสู่ระบบ</a></li>
             <li class="nav-item"><a class="nav-link" href="index.php?page=form_register.php"><i class="bi bi-person-plus-fill"></i>&nbsp;สมัครสมาชิก</a></li>
            </ul> 
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      <ul class="list-group">
        <li class="list-group-item list-group-item-dark"><a href="index.php" style="text-decoration:none;"><i class="bi bi-house-fill"></i>&nbsp;หน้าหลัก</a></li>
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=form_register.php" style="text-decoration: none;"><i class="bi bi-person-plus-fill"></i>&nbsp;สมัครสมาชิก</a></li>
        <li class="list-group-item list-group-item-warning"><a href="index.php?page=form_login.php" style="text-decoration: none;"><i class="bi bi-box-arrow-in-right"></i>&nbsp;เข้าสู่ระบบ</a></li>
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
        echo "<br>";
        include("productall.php");
      }
      ?>
    </div>
  </div>
  <!-- nav end -->

  <!-- footer start -->

  <?php include('footer.php'); ?>

  <!-- footer end -->
</div>

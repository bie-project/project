<?php
session_start();

include("../layout.php");
?>
<div class="container-fluid" style="min-height: 100vh; padding: 2; margin: 2;">
<div class="row">
  <div class="col-sm-12">
    <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="mynavbar">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="index.php?page=member.php"><i class="bi bi-person-fill-gear">&nbsp;จัดการข้อมูลสมาชิก</i></a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=product.php"><i class="bi bi-person-lines-fill">&nbsp;จัดการข้อมูลสินค้า</i></a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=order_manage.php"><i class="bi bi-list-ul">&nbsp;จัดการรายการสั่งซื้อสินค้า</i></a></li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../logout.php" style="text-decoration:none;"><i class="bi bi-box-arrow-right">&nbsp;ออกจากระบบ</i></a></li>
          </ul> 
        </div>
      </div>
    </nav>
  </div>
</div>

  <?php
      if (isset($_GET["page"])) {
        $page = $_GET["page"];

        // ตรวจสอบความปลอดภัยของไฟล์ที่รวม
        if (file_exists($page) && strpos($page, "..") === false && strpos($page, "/") === false) {
          include($page);
        } else {
          echo "<br>ไม่พบหน้าเพจที่คุณต้องการ";
        }
      } else {"<br>";

        include("order_manage.php");
      }
      ?>
</div>

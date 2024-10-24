<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

include("../connection/connect.php");

// ตรวจสอบค่าของเซสชันให้ถูกต้อง
if (empty($_SESSION["session"])) {
  echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
  exit();
}
?>

<div>
  <h2 class="container mt-3">จัดการสมาชิก</h2>
  <p>สำหรับจัดการสมาชิกสมาชิก</p>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>ลำดับ</th>
        <th>ชื่อ-สกุล</th>
        <th>ที่อยู่</th>
        <th>เบอร์โทร</th>
        <th>อีเมล์</th>
        <th>พาสเวิร์ด</th>
        <th>สถานะ</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $memid =  $_GET["memid"];


      $sql_member = "SELECT * FROM member WHERE memid=$memid";
      $result_member = mysqli_query($connect, $sql_member);
      $i = 1;

      while ($row_member = mysqli_fetch_array($result_member)) {
      ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?php echo $row_member["prefix"] . " " . $row_member["fname"] . " " . $row_member["lname"]; ?></td>
          <td><?php echo $row_member["address"]; ?></td>
          <td><?php echo $row_member["tel"]; ?></td>
          <td><?php echo $row_member["email"]; ?></td>
          <td><?php echo $row_member["pass"]; ?></td>
          <td>
            <?php echo $row_member["status"] == 1 ? 'admin' : 'ลูกค้า'; ?>
          </td>
          <td>

            <a href="index.php?page=member_edit.php&memid=<?php echo htmlspecialchars($row_member["memid"]); ?>&edit=ok"><button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button></a>
            <a href="index.php?page=member.php&memid=<?php echo htmlspecialchars($row_member["memid"]); ?>&del=ok"><button type="button" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ');"><i class="bi bi-trash-fill"></i></button></a>
          </td>
        </tr>
      <?php
        $i++;
      }
      ?>

    </tbody>
  </table>
</div>
<div><a href="index.php?page=member.php"><button type="button" class="btn btn-secondary"><i class="bi bi-arrow-bar-left"></i>&nbsp;กลับหน้าที่แล้ว</button></a></div>
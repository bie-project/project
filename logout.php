
<?php
session_start();
session_unset();  // ลบตัวแปรเซสชันทั้งหมด
session_destroy();  // ทำลายเซสชัน

echo "<script>alert('ออกจากระบบเรียบร้อยแล้ว'); window.location='index.php';</script>";
?>

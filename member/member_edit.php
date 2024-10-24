<div>
    <h2 class="container mt-3">แก้ไขข้อมูลส่วนตัว</h2>
    <p>สำหรับจัดการข้อมูลสมาชิก</p>
</div>

<?php
// ตรวจสอบสถานะของเซสชัน
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

// ดึงข้อมูลสมาชิกจากฐานข้อมูล
$memid = $_SESSION["memid"]; // ดึง memid จาก session
$sql = "SELECT * FROM member WHERE memid = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $memid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!-- ฟอร์มแสดงข้อมูลสมาชิกเพื่อแก้ไข -->
<form action="member_update.php" method="post">
    <div class="form-group">
        <label for="prefix">คำนำหน้า:</label>
        <select class="form-control" id="prefix" name="prefix">
            <option value="นาย" <?= $row["prefix"] == "นาย" ? "selected" : "" ?>>นาย</option>
            <option value="นาง" <?= $row["prefix"] == "นาง" ? "selected" : "" ?>>นาง</option>
            <option value="นางสาว" <?= $row["prefix"] == "นางสาว" ? "selected" : "" ?>>นางสาว</option>    
        </select>
    </div>
    <div class="form-group">
        <label for="fname">ชื่อ:</label>
        <input type="text" class="form-control" id="fname" name="fname" value="<?= htmlspecialchars($row["fname"]) ?>" required>
    </div>
    <div class="form-group">
        <label for="lname">นามสกุล:</label>
        <input type="text" class="form-control" id="lname" name="lname" value="<?= htmlspecialchars($row["lname"]) ?>" required>
    </div>
    <div class="form-group">
        <label for="address">ที่อยู่:</label>
        <textarea class="form-control" id="address" name="address" required><?= htmlspecialchars($row["address"]) ?></textarea>
    </div>
    <div class="form-group">
        <label for="tel">เบอร์โทรศัพท์:</label>
        <input type="tel" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($row["tel"]) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">อีเมล:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row["email"]) ?>" required>
    </div>
    <div class="form-group">
        <label for="pass">รหัสผ่าน:</label>
        <input type="password" class="form-control" id="pass" name="pass" value="<?= htmlspecialchars($row["pass"]) ?>" required>
    </div>
    <br>
    <button type="submit" class="btn btn-success" name="ok" value="edit">บันทึกการแก้ไข</button>
    <button type="button" class="btn btn-warning" onclick="window.location.href='index.php'">ยกเลิก</button>
</form>

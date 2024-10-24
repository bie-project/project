<?php

    include("../connection/connect.php");

    // ตรวจสอบค่าของเซสชันให้ถูกต้อง
    if (empty($_SESSION["session"])) {
        echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
        exit();
    }

  
    if (isset($_GET["edit"]) && $_GET["edit"] == "ok" && isset($_GET["memid"])) {
        $memid = $_GET["memid"];
        $stmt = $connect->prepare("SELECT * FROM member WHERE memid = ?");
        $stmt->bind_param("i", $memid);
        $stmt->execute();
        $result_email = $stmt->get_result();
        $row_edit = $result_email->fetch_assoc();
        $stmt->close();
?>

<h5>กรุณากรอกข้อมูลเพื่อสมัครสมาชิก</h5>

<form action="member_update.php" method="post">
    <div class="form-group">
        <label for="prefix">Prefix:</label>
        <select class="form-control" id="prefix" name="prefix">
            <option value="นาย" <?= $row_edit["prefix"] == "นาย" ? "selected" : "" ?>>นาย</option>
            <option value="นาง" <?= $row_edit["prefix"] == "นาง" ? "selected" : "" ?>>นาง</option>
            <option value="นางสาว" <?= $row_edit["prefix"] == "นางสาว" ? "selected" : "" ?>>นางสาว</option>    
        </select>
    </div>
    <div class="form-group">
        <label for="fname">FirstName:</label>
        <input type="text" class="form-control" id="fname" name="fname" value="<?= htmlspecialchars($row_edit["fname"]) ?>">
    </div>
    <div class="form-group">
        <label for="lname">LastName:</label>
        <input type="text" class="form-control" id="lname" name="lname" value="<?= htmlspecialchars($row_edit["lname"]) ?>">
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control" rows="3" id="address" name="address"><?= htmlspecialchars($row_edit["address"]) ?></textarea>
    </div>
    <div class="form-group">
        <label for="tel">Phone:</label>
        <input type="tel" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($row_edit["tel"]) ?>">
    </div>
    <div class="form-group">
        <label for="email">Email address:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row_edit["email"]) ?>">
    </div>
    <div class="form-group">
        <label for="pass">Password:</label>
        <input type="password" class="form-control" id="pass" name="pass" value="<?= htmlspecialchars($row_edit["pass"]) ?>">
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <select class="form-select" id="status" name="status">
            <option value="1" <?= $row_edit["status"] == "1" ? "selected" : "" ?>>Admin</option>
            <option value="0" <?= $row_edit["status"] == "0" ? "selected" : "" ?>>Member</option>
        </select>
    </div><br>
    
    <button type="submit" class="btn btn-success" name="ok" value="add">แก้ไขข้อมูล</button>
    <a href="index.php?page=member.php"><button type="button" class="btn btn-warning" name="cancel">ยกเลิก</button></a>
    <input type="hidden" class="form-control" id="pass" name="memid" value="<?= htmlspecialchars($row_edit["memid"]) ?>">
</form>

<?php
    }
?>




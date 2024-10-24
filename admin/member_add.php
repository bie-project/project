<?php declare(strict_types=1); 
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
        $prefix = $_POST["prefix"];
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $address = $_POST["address"];
        $tel = $_POST["tel"];
        $email = $_POST["email"];
        $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT); // เข้ารหัสพาสเวิร์ด
        $status = $_POST["status"];

        // ตรวจสอบว่าอีเมลนี้มีการใช้งานไปแล้วหรือไม่
        $stmt = $connect->prepare("SELECT * FROM member WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result_email = $stmt->get_result();

        if ($result_email->num_rows > 0) {
            echo "<script>alert('อีเมล์นี้ถูกใช้งานไปแล้วกรุณาทำรายการใหม่อีกครั้ง'); window.location='index.php?page=member_add.php';</script>";
        } else {
            $stmt = $connect->prepare("INSERT INTO member (prefix, fname, lname, address, tel, email, pass, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $prefix, $fname, $lname, $address, $tel, $email, $pass, $status);
            $resultin = $stmt->execute();

            if ($resultin) {
                echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php?page=member.php';</script>";
            } else {
                echo "<script>alert('บันทึกไม่สำเร็จ'); window.location='index.php?page=member.php';</script>";
            }
            $stmt->close();
        }
    }
?>

<h5>กรุณากรอกข้อมูลเพื่อสมัครสมาชิก</h5>

<form action="#" method="post">
    <div class="form-group">
        <label for="prefix">Prefix:</label>
        <select class="form-select" id="prefix" name="prefix" required>
            <option value="นาย">นาย</option>
            <option value="นาง">นาง</option>
            <option value="นางสาว">นางสาว</option>    
        </select>
    </div>
    <div class="form-group">
        <label for="fname">FirstName:</label>
        <input type="text" class="form-control" id="fname" name="fname" required>
    </div>
    <div class="form-group">
        <label for="lname">LastName:</label>
        <input type="text" class="form-control" id="lname" name="lname" required>
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control" rows="3" id="address" name="address" required></textarea>
    </div>
    <div class="form-group">
        <label for="tel">Phone:</label>
        <input type="tel" class="form-control" id="tel" name="tel" required>
    </div>
    <div class="form-group">
        <label for="email">Email address:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="pass">Password:</label>
        <input type="password" class="form-control" id="pass" name="pass" required>
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <select class="form-select" id="status" name="status" required>
            <option value="1">Admin</option>
            <option value="0">Member</option>
        </select>
    </div><br>
    <button type="submit" class="btn btn-success" name="ok" value="add">ยืนยัน</button>
    <a href="index.php?page=member.php"><button type="button" class="btn btn-warning" type="boto" name="cancel">ยกเลิก</button></a>
</form>

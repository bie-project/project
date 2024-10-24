<?php

declare(strict_types=1);
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../connection/connect.php");
if (empty($_SESSION["session"])) {
    echo "<script>alert('กรุณาเข้าสู้ระบบ');window.location='../index.php?page=form_login.php';</script>";
    exit();
}

if (isset($_GET["del"]) && $_GET["del"] == "ok" && isset($_GET["memid"])) {
    $memid = $_GET["memid"];
    $stmt = $connect->prepare("DELETE FROM member WHERE memid = ?");
    $stmt->bind_param("i", $memid);
    $result_delete = $stmt->execute();

    if ($result_delete) {
        echo "<script>alert('ลบสำเร็จ'); window.location='index.php?page=member.php';</script>";
    } else {
        echo "<script>alert('ลบไม่สำเร็จ'); window.location='index.php?page=member.php';</script>";
    }
    $stmt->close();
}
?>
<div>
<br><br><br>
    <h2 class="text-center">จัดการข้อมูลสมาชิก</h2>
    <div><a href="index.php?page=member_add.php"><button type="button" class="btn btn-success"><i class="bi bi-folder-plus"></i>&nbsp;เพิ่มข้อมูลสมาชิก</button></a></div>
    <br>
    
    <!-- กำหนด div สำหรับตารางข้อมูลที่สามารถเลื่อนได้ -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover">
            <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อ-สกุล</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทร</th>
                    <th>อีเมล์</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_member = "SELECT * FROM member ORDER BY status DESC";
                $result_member = mysqli_query($connect, $sql_member);
                $i = 1;
                while ($row_member = mysqli_fetch_array($result_member)) {
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo htmlspecialchars($row_member["prefix"]); ?>&nbsp;<?php echo htmlspecialchars($row_member["fname"]); ?>&nbsp;<?php echo htmlspecialchars($row_member["lname"]); ?></td>
                        <td><?php echo htmlspecialchars($row_member["address"]); ?></td>
                        <td><?php echo htmlspecialchars($row_member["tel"]); ?></td>
                        <td><?php echo htmlspecialchars($row_member["email"]); ?></td>
                        <td><?php echo $row_member["status"] == 1 ? 'ผู้ดูแลระบบ' : 'ลูกค้า'; ?></td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="index.php?page=member_review.php&memid=<?php echo htmlspecialchars($row_member["memid"]); ?>">
                                    <button type="button" class="btn btn-info"><i class="bi bi-eye-fill"></i></button>
                                </a>
                                <a href="index.php?page=member_edit.php&memid=<?php echo htmlspecialchars($row_member["memid"]); ?>&edit=ok">
                                    <button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
                                </a>
                                <a href="index.php?page=member.php&memid=<?php echo htmlspecialchars($row_member["memid"]); ?>&del=ok">
                                    <button type="button" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ');"><i class="bi bi-trash-fill"></i></button>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

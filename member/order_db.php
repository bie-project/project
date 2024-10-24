<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");

if (isset($_POST["ok"])) {
    $cname = $_POST["cname"];
    $proid = $_POST["proid"];
    $num = $_POST["num"];
    $sale = $_POST["sale"];
    $pay = $num * $sale;
    $date = date("Y-m-d H:i:s");

    if ($num <= '0') {
        echo "<script>alert('ไม่สามารถบันทึกได้');window.location='index.php?page=order.php';</script>";
        //exit();
    } else {
        $sqlin_bas = " INSERT INTO basket (oid, proid, bnum , bprice, btotal, bcus, bdate) VALUES ('','$proid','$num','$sale','$pay','$cname','$date')";
        $resultin_bas = mysqli_query($conn, $sqlin_bas);

        $sqlup_pro = "UPDATE product SET num = (num - '$num') WHERE proid = '$proid'";
        $resultup_pro = mysqli_query($conn, $sqlup_pro);

        if ($resultin_bas == TRUE && $resultup_pro == TRUE) {
            echo "<script>alert('บันทึกสำเร็จ');window.location='index.php?page=cart.php';</script>";
        } else {
            echo "บันทึกไม่สำเร็จ";
        }
        
    }
			$sqlseo = " SELECT * FROM orders WHERE oid = '$id'";
            $resultseo = mysqli_query($conn, $sqlseo);
}

?>
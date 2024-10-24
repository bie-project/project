<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../connection/connect.php");
date_default_timezone_set('Asia/Bangkok');

        $id      = $_POST['id'];
        $ofile   = $_FILES['ofile'];
        $ok      = $_POST['ok'];
        $date_up = date("Y-m-d H:i:s");

        if ($ok != ""){

                $date2 = date("Y-m-d H:i:s");
                $numrand =(mt_rand());

                if (($_FILES['ofile']['name']) <> "") {
                    $path = "../payment/";
                    $type = strrchr($_FILES['ofile']['name'], '.');
                    $newname = $date2.$numrand.$type;
                    $path_copy= $path.$newname;

                    $path_link = "../payment/".$newname;
                    move_uploaded_file($_FILES['ofile']['tmp_name'], $path_copy);

                        $sql = "UPDATE orders SET ofile='$newname', odateup='$date_up' WHERE oid='$id'";
                        $result = mysqli_query($conn, $sql);
                }
                if ($resylt == TRUE ) {
                        echo
                        "<script>alert('บันทึกสำเสร็จ');window.location='index.php?page=order_manage.php';</script>";
                }else{
                         echo
                        "<script>alert('ไม่สมารถแก้ไขได้');window.location='index.php?page=order_manage.php';</script>"; 
                }
               
        } $conn->close();
?>
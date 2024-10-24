<div class="container mt-5">
    <h2>ค้นหาเลขที่ใบสั่งซื้อเพื่ออัพโหลดสลิปโอนเงิน</h2>
    <form action="#" method="post" class="form-inline" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group" style="text-align: left;">
                    <label for="order_id" class="sr-only">Order ID</label>
                    <input type="text" name="order_id" id="order_id" class="form-control mr-2" placeholder="กรอกเลขที่ใบสั่งซื้อ" required><br>
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>

            </div>
            <div class="col-sm-4"">
            <h4>เลขที่บัญชี</h4>
            <td>ธนาคารกุรงไทย : 310-0-96952-9</td>
            </div>
            <div class=" col-sm-4" style="margin: auto;">
                <img class="img-thumbnail" src="../payment/prompay.jpg" width="250" height="250">
            </div>
        </div>

    </form>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order_id"])) {
        include("../connection/connect.php");

        $order_id = $_POST["order_id"];

        $sql = "SELECT * FROM orders WHERE oid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();
            echo "<div class='mt-4'>
                    <h4>รายละเอียด</h4>
                    <p><strong>เลขที่ใบสั่งซื้อ:</strong> " . $order["oid"] . "</p>
                    <p><strong>วันที่สั่งซื้อ:</strong> " . $order["odateup"] . "</p>
                    <p><strong>ค่าจัดส่ง ems :</strong>  60 บาท </p>
                    <p><strong>ราคารวม:</strong> " . $order["ototal"] . " บาท</p>
                    <p><strong>ชื่อลูกค้า:</strong> " . $order["ocusname"] . "</p>
                  </div>";

            // ดึงข้อมูลสินค้าในตะกร้าที่เกี่ยวข้องกับ Order นี้
            $sqlProducts = "
             SELECT product.proname, product.sale, basket.bnum
             FROM basket 
            LEFT JOIN product ON basket.proid = product.proid 
            WHERE basket.oid = ?";

            $stmtProducts = $conn->prepare($sqlProducts);
            $stmtProducts->bind_param("i", $order_id);
            $stmtProducts->execute();
            $resultProducts = $stmtProducts->get_result();

            if ($resultProducts->num_rows > 0) {
                echo "<div class='mt-4'>
                        <h4>รายละเอียดสินค้า</h4>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>ราคารวม</th>
                                </tr>
                            </thead>
                            <tbody>";

                while ($product = $resultProducts->fetch_assoc()) {
                    $totalPrice = $product['sale'] * $product['bnum'];
                    echo "<tr>
                                        <td>" . htmlspecialchars($product['proname']) . "</td>
                                        <td>" . number_format($product['sale'], 2) . "</td>
                                        <td>" . $product['bnum'] . "</td>
                                        <td>" . number_format($totalPrice, 2) . "</td>
                                      </tr>";
                }


                echo "</tbody>
                      </table>
                      </div>";
            } else {
                echo "<div class='mt-4 alert alert-info'>ไม่มีสินค้าที่เกี่ยวข้องกับคำสั่งซื้อนี้</div>";
            }

            $stmtProducts->close();

            // Check if a file has already been uploaded
            if (!empty($order["ofile"])) {
                echo "<div class='mt-4'>
                        <h5>สลิปการชำระเงินที่อัพโหลดแล้ว:</h5>
                        <img src='../payment/" . htmlspecialchars($order["ofile"]) . "' class='img-thumbnail' width='200' height='200'><br><br>
                        <form action='#' method='post'>
                            <input type='hidden' name='order_id' value='" . $order["oid"] . "'>
                            <button type='submit' name='delete' class='btn btn-danger'>ลบสลิปที่อัพโหลด</button>
                        </form>
                      </div>";
            } else {
                // File upload form if no file exists
                echo "<form action='#' method='post' enctype='multipart/form-data'>
                        <input type='hidden' name='order_id' value='" . $order["oid"] . "'>
                        <label for='payment_slip'>อัพโหลดสลิปการชำระเงิน:</label>
                        <input type='file' name='payment_slip' class='form-control' required><br>
                        <button type='submit' name='upload' class='btn btn-success'>อัพโหลดสลิป</button>
                      </form>";
            }

            // Handle file upload
            if (isset($_POST["upload"]) && isset($_FILES['payment_slip'])) {
                $file = $_FILES['payment_slip'];
                $uploadDir = "../payment/";
                $fileName = basename($file["name"]);
                $uploadFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($file["tmp_name"], $uploadFilePath)) {
                    $sqlUpdate = "UPDATE orders SET ofile = ?, ostatus = 2 WHERE oid = ?";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    $stmtUpdate->bind_param("si", $fileName, $order_id);
                    $stmtUpdate->execute();
                    echo "<script>alert('อัพโหลดสลิปสำเร็จ');window.location='index.php?page=order_history.php';</script>";
                    $stmtUpdate->close();
                } else {
                    echo "<div class='mt-3 alert alert-danger'>การอัพโหลดล้มเหลว</div>";
                }
            } else {
                // ถ้าไม่มีการอัพโหลดไฟล์ ให้ตั้งค่า status เป็น 1 (รอการชำระเงิน)
                if (empty($order["ofile"])) {
                    $sqlUpdateStatus = "UPDATE orders SET ostatus = 1 WHERE oid = ?";
                    $stmtUpdateStatus = $conn->prepare($sqlUpdateStatus);
                    $stmtUpdateStatus->bind_param("i", $order_id);
                    $stmtUpdateStatus->execute();
                    $stmtUpdateStatus->close();
                }
            }

            // Handle file deletion
            if (isset($_POST["delete"])) {
                $filePath = "../payment/" . $order["ofile"]; // Path to the file

                // ตรวจสอบว่า ofile มีชื่อไฟล์ที่ไม่ว่างเปล่า
                if (!empty($order["ofile"]) && file_exists($filePath)) {
                    if (unlink($filePath)) { // Delete the file
                        echo "<script>alert('สลิปถูกลบเรียบร้อยแล้ว กรุณาอัพโหลดสลิปใหม่');window.location='index.php?page=order_history.php';</script>";
                    } else {
                        echo "<div class='mt-3 alert alert-danger'>เกิดข้อผิดพลาดในการลบไฟล์</div>";
                    }
                } else {
                    echo "<div class='mt-3 alert alert-info'>ไม่พบไฟล์ที่จะลบ</div>";
                }

                // Remove the file reference from the database and set ostatus back to 1
                $sqlDelete = "UPDATE orders SET ofile = '', ostatus = 1 WHERE oid = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bind_param("i", $order_id);
                $stmtDelete->execute();
                $stmtDelete->close();
            }
        } else {
            echo "<div class='mt-4 alert alert-danger'>ไม่พบข้อมูล Order นี้</div>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>
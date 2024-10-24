<?php


include("../connection/connect.php");

// ตรวจสอบค่าของเซสชันให้ถูกต้อง
if (empty($_SESSION["session"])) {
  echo "<script>alert('กรุณาเข้าสู่ระบบ');window.location='../index.php?page=form_login.php';</script>";
  exit();
}
?>

<div>
  <h2 class="container mt-3">จัดการข้อมูลสินค้า</h2>
  <p>สำหรับจัดการข้อมูลสินค้า</p>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>ลำดับ</th>
        <th>ชื่อสินค้า</th>
        <th>รายละเอียดสินค้า</th>
        <th>ราคาสินค้า</th>
        <th>ราคาขาย</th>
        <th>รูปสินค้า</th>
        <th>จำนวนคงเหลือ</th>
        <th>จัดการ</th>
      </tr>s
    </thead>
    <tbody>


      <?php
      // รับค่า proid จาก URL
      $proid = (int)$_GET["proid"];


      // ใช้ prepared statement เพื่อความปลอดภัย
      $sql_product = "SELECT * FROM product WHERE proid = ?";
      $stmt_product = $connect->prepare($sql_product);
      $stmt_product->bind_param("i", $proid); // ใช้ bind_param เพื่อ bind ค่าตัวเลข
      $stmt_product->execute();
      $result_product = $stmt_product->get_result(); // ดึงผลลัพธ์

      // นับลำดับสินค้า
      $i = 1;

      while ($row_product = $result_product->fetch_assoc()) { // ใช้ fetch_assoc เพื่อดึงข้อมูลแต่ละแถว
      ?>

        <tr>
          <td><?php echo $i ?></td>
          <td><?php echo ($row_product["proname"]); ?></td>
          <td><?php echo ($row_product["prodetail"]); ?></td>
          <td><?php echo ((string)$row_product["price"]); ?> บาท</td>
          <td><?php echo ((string)$row_product["sale"]); ?> บาท</td>
          <td><img src="../image/<?php echo ($row_product["pic"]); ?>" width="250" height="250"></td>
          <td><?php echo ((string)$row_product["num"]); ?> ชิ้น</td>

          <td>
            <div style="display: flex; gap: 10px;"> <!-- ใช้ flexbox เพื่อจัดเรียงปุ่มและ gap เพื่อกำหนดระยะห่าง -->
            
              <a href="index.php?page=product_edit.php&proid=<?php echo ($row_product["proid"]); ?>&edit=ok">
                <button type="button" class="btn btn-warning"><i class="bi bi-pencil-square"></i></button>
              </a>

              <a href="index.php?page=product.php&proid=<?php echo ($row_product["proid"]); ?>&del=ok">
                <button type="button" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ');">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </a>

              <a href="index.php?page=order.php&id=<?php echo $row_product["proid"] ?>">
                <button type="button" class="btn btn-success" name="ok">
                  <i class="fas fa-shopping-cart"></i>หยิบใส่ตะกร้า
                </button>
              </a>
            </div>
          </td>

        <?php
        $i++;
      }
      $stmt_product->close();
        ?>

    </tbody>
  </table>
  <div><a href="index.php?page=product.php"><button type="button" class="btn btn-secondary"><i class="bi bi-arrow-bar-left"></i>&nbsp;กลับหน้าที่แล้ว</button></a></div>
</div>
<br><br>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../connection/connect.php');
date_default_timezone_set('Asia/Bangkok');

$sqlm = "SELECT * FROM product";
$resultm = mysqli_query($conn, $sqlm);
$i = 1;
?>

<div class="container">
    <div class="row">
        <?php
        while ($rowm = mysqli_fetch_assoc($resultm)) {
        ?>
            <div class="col-3 d-flex">
                <div class="card" style="flex: 1; box-shadow: -1px -2px 8px #000000; border-radius: 15px; padding: 10px; display: flex; flex-direction: column; justify-content: space-between; margin: 10px;"> <!-- Add margin here -->
                    <div>
                        <img src="../image/<?php echo ($rowm["pic"]); ?>" class="img-fluid" alt="<?php echo ($rowm['proname']); ?>" style="height: 150px; object-fit: cover;">
                    </div>
                    <div style="flex-grow: 1;">
                        <p style="margin-bottom: 10px;">
                            <label class="col-form-label" for="proname"><b>ชื่อสินค้า :</b></label>
                            <span><?php echo ($rowm['proname']); ?></span>
                        </p>
                        <p style="margin-bottom: 10px;">
                            <label class="col-form-label" for="prodetail"><b>รายละเอียดสินค้า:</b></label>
                            <span><?php echo ($rowm['prodetail']); ?></span>
                        </p>
                        <p style="margin-bottom: 10px;">
                            <label class="col-form-label" for="price"><b>ราคาสินค้า :</b></label>
                            <span><del style="color:gray;"><?php echo ($rowm['price']); ?></del> บาท/ชิ้น</span>
                        </p>
                        <p style="margin-bottom: 10px;">
                            <label class="col-form-label" for="sale">ราคาขาย :</label>
                            <span><b style="color:red;">&nbsp;<?php echo ($rowm['sale']); ?></b> บาท/ชิ้น</span>
                        </p>
                        <p style="margin-bottom: 10px;">
                            <label class="col-form-label" for="sale">สินค้าคงเหลือ :</label>
                            <span><b>&nbsp;<?php echo ($rowm['num']); ?></b> ชิ้น</span>
                        </p>
                    </div>
                    <p>
                        <a href="index.php?page=order.php&id=<?php echo $rowm["proid"] ?>">
                            <button type="button" class="btn btn-warning" name="ok">
                                <i class="fas fa-shopping-cart"></i>หยิบใส่ตะกร้า
                            </button>
                        </a>
                    </p>
                </div>
            </div>
        <?php
            }
        ?>
    </div>
</div>


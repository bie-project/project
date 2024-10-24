<br><br>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('connection/connect.php');
date_default_timezone_set('Asia/Bangkok');

$sqlm = "SELECT * FROM product";
$resultm = mysqli_query($conn, $sqlm);
$i = 1;
?>

<div class="row">
    <div class="col-sm-12 row" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php
        while ($rowm = mysqli_fetch_assoc($resultm)) {
        ?>
            <div style="flex: 1 1 250px; max-width: 330px; box-shadow: -1px -2px 8px #000000; padding: 10px; border-radius: 0px 0px 15px 15px;">
                <div>
                <img src="image/<?php echo ($rowm["pic"]); ?>" style="width: 100%; height: auto; max-width: 250px;">
                </div>
                <br><br>
                <p><label for="proname"><b>ชื่อสินค้า :</b></label>&nbsp;<?php echo ($rowm['proname']); ?></p>
                <p><label for="prodetail"><b>รายละเอียดสินค้า:</b></label><?php echo ($rowm['prodetail']); ?></p>
                <p><label for="price"><b>ราคาสินค้า :</b></label>&nbsp;<del style="color:gray;"><?php echo ($rowm['price']); ?></del> บาท/ชิ้น</p>
                <p><label for="sale">ราคาขาย :</label><b style="color:red;">&nbsp;<?php echo ($rowm['sale']); ?></b> บาท/ชิ้น</p>
                <p><label for="sale">สินค้าคงเหลือ :</label><b>&nbsp;<?php echo ($rowm['num']); ?></b> ชิ้น</p>

                <p><a href="index.php?page=order.php&id=<?php echo $rowm["proid"] ?>">
                        <button type="button" class="btn btn-warning" name="ok">
                            <i class="fas fa-shopping-cart"></i>หยิบใส่ตะกร้า
                        </button>
                    </a></p>
            </div>
        <?php
            $i++;
        }
        ?>
    </div>
</div>
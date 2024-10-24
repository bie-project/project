<?php 

include('db_login.php');

?>

<br>
<h5>กรุณาเข้าสู่ระบบ</h5>
<br>
<form action="#" method="post">
  <div class="form-group">
    <label for="email">อีเมล์:</label>
    <input type="email" class="form-control" id="email" name="email"  >
  </div>
  <div class="form-group">
    <label for="pass">รหัสผ่าน:</label>
    <input type="password" class="form-control" id="pass" name="pass" >
  </div>
  <br>
  <button type="submit" class="btn btn-success">เข้าสู่ระบบ</button>
  <a href="index.php"><button type="button"  class="btn btn-warning">ยกเลิก</button></a>
  <input type="hidden" id="ok" name="ok" value="login">
</form>
<div>
<a href="index.php?page=register.php">สมัครสมาชิกใหม่</a>
</div>
<br>

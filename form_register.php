<?php 
include("db_register.php");
?>


<h5>กรุณากรอกข้อมูลเพื่อสมัครสมาชิก</h5>

<form action="#" method="post">
    <div class="form-group" >
        <label for="prefix">คำนำหน้า:</label>
        <select class="form-select" id="prefix" name="prefix" required>
            <option value="นาย">นาย</option>
            <option value="นาง">นาง</option>
            <option value="นางสาว">นางสาว</option>    
        </select>
    </div>
    <br>
    <div class="form-group">
        <label for="fname">ชื่อ:</label>
        <input type="text" class="form-control" id="fname" name="fname" >
    </div>
    <br>
    <div class="form-group">
        <label for="lname">นามสกุล:</label>
        <input type="text" class="form-control" id="lname" name="lname" >
    </div>
    <br>
    <div class="form-group">
        <label for="address">ที่อยู่:</label>
        <textarea class="form-control" rows="3" id="address" name="address" ></textarea>
    </div>
    <br>
    <div class="form-group">
        <label for="tel">เบอร์โทรศัพท์:</label>
        <input type="tel" class="form-control" id="tel" name="tel" >
    </div>
    <br>

    <div class="form-group">
        <h6 style="color: red;">*ต้องกรอกข้อมูล*</h6>
        <label for="email"> *อีเมล:</label>
        <input type="email" class="form-control" id="email"  name="email" autocomplete="off" required>
    </div>
    <br>
    <div class="form-group">
   <div style="display:inline;">
     <h6 style="color: red; display:inline;">*ต้องกรอกข้อมูล*</h6><label for="pass" style=display:inline;>*รหัสผ่าน:</label>
   </div>     
        <input type="password" class="form-control" id="pass" name="pass" autocomplete="new-password" required> 
    </div>
    <div  class="form-group" style="display: none;">
        <label  for="status">*ไม่ต้องเลือก*</label>
        <select  class="form-select" id="status" name="status" >
            <option  value="0"></option>
        </select>
    </div>
    <br>
    <button type="submit" class="btn btn-success" name="ok" value="add">ยืนยัน</button>
    <a href="index.php"><button type="button"  class="btn btn-warning">ยกเลิก</button></a>
</form>
<br>
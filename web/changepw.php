<?php
if (!isset($config)) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="changepw">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> เปลี่ยนรหัสผ่าน </h4>
            </div>
            <div class="col-xs-12">
                <form class="panel-form text-left" method="get">
                    <div id="return_changepw" style="display: none"></div>
                    <div class="form-group">
                        <label for="pass_old">Password old</label>
                        <input type="text" class="form-control" name="pass_old" id="pass_old" placeholder="รหัสผ่านเก่า" required="">
                    </div>
                    <div class="form-group">
                        <label for="pass_new">Password new</label>
                        <input type="password" class="form-control" name="pass_new" id="pass_new" placeholder="ตั้งรหัสผ่านใหม่" required="">
                    </div>
                    <div class="form-group">
                        <label for="pass_new_c">Password new confirm</label>
                        <input type="password" class="form-control" name="pass_new_c" id="pass_new_c" placeholder="กรอกรหัสผ่านใหม่อีกครั้ง" required="">
                    </div>
                    <div class="form-group">
                        <center><button type="submit" class="btn btn-block btn-success" style="max-width:20%;" onclick="javascript:changepw();return false;">  <i class="glyphicon glyphicon-ok-sign"></i> เปลี่ยนเลย!</button></center></div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    function changepw()
    {
        if ($("#pass_old").val() == "" || $("#pass_new").val() == "" || $("#pass_new_c").val() == "") {
            swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
        } else {
            $.get("index.php", {change_pwold: $('#pass_old').val(), change_pw: $('#pass_new').val(), change_pwc: $('#pass_new_c').val()}, function (data) {
                $("#return_changepw").html(data);
            });
        }
    }
</script>
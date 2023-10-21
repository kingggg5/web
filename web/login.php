<?php
if (!isset($config)) {
    exit;
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> ระบบสมาชิก</h3>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="panel-form text-left" method="get">
                            <div id="return_login" style="display: none;"></div>
                            <div class="form-group">
                                Email<small> อีเมล์เข้าสู่ระบบ</small>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required="">
                            </div>
                            <div class="form-group">
                                Password<small> รหัสผ่าน</small>
                                <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter password" required="">
                            </div>
                            <?php if ($config['captcha_login']) { ?>
                                <div class = "form-group">
                                    <label for = "thaicode">Captcha: </label>
                                    <br><img border = "0" id = "captcha" src = "thaicaptcha/captcha.php" alt = ""><hr>
                                    <input type = "text" id = "thaicode" name = "thaicode" class = "form-control" placeholder = "กรอกตัวเลขที่พบตามตัวอย่าง" tabindex = "4" title = "กรอกตัวเลขที่พบตามตัวอย่าง" required autocomplete = off maxlength = "6" pattern = ".{6,}">
                                </div>
                                <?php
                            }
                            ?>
                            <div class="form-group">
                                <center><button type="submit" class="btn btn-block btn-success" style="max-width:20%;" onclick="javascript:login();return false;">  <i class="glyphicon glyphicon-ok-sign"></i> เข้าสู่ระบบ</button></center>
                            </div>
                        </form>
                        <?php if ($config['fb']['regis']) : ?>
                            <center>
                                <h4 style="color: #00ffff">สมัครด้วยบัญชี Facebook.com</h4>
                                <fb:login-button 
                                    scope="public_profile,email"
                                    onlogin="checkLoginState();">
                                </fb:login-button>
                                <form action="check_fb.php" method="post" name="frmMainFB" id="frmMainFB">
                                    <input type="hidden" id="hdnFbID" name="hdnFbID">
                                    <input type="hidden" id="hdnName" name="hdnName">
                                    <input type="hidden" id="hdnEmail" name="hdnEmail">
                                </form>
                            </center>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($config['captcha_login']) { ?>
    <script>
        function login()
        {
            if ($("#email").val() == "" || $("#pass").val() == "" || $("#thaicode").val() == "") {
                swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
            } else {
                $.get("index.php", {em: $('#email').val(), pw: $('#pass').val(), tc: $('#thaicode').val()}, function (data) {
                    $("#return_login").html(data);
                });
            }
        }
    </script>
<?php } else { ?>
    <script>
        function login()
        {
            if ($("#email").val() == "" || $("#pass").val() == "") {
                swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
            } else {
                $.get("index.php", {em: $('#email').val(), pw: $('#pass').val()}, function (data) {
                    $("#return_login").html(data);
                });
            }
        }
    </script>
<?php } ?>

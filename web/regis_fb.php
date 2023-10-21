<?php
if (!isset($config)) {
    exit;
}
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
} else {
    $ip = $_SERVER["REMOTE_ADDR"];
}
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
if (!$config['fb']['regis']) {
    exit;
}
if (isset($_SESSION['CustomerID'])) {
    exit(rdr("index.php"));
}
if (isset($_GET['INPUT_FBNAME']) || isset($_GET['INPUT_FBEMAIL']) || isset($_GET['INPUT_FBID']) || isset($_GET['INPUT_PW']) || isset($_GET['INPUT_FBCONFIRM'])) {
    if (empty($_GET['INPUT_FBNAME']) || empty($_GET['INPUT_FBEMAIL']) || empty($_GET['INPUT_FBID']) || empty($_GET['INPUT_PW']) || empty($_GET['INPUT_FBCONFIRM'])) {
        exit('swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")');
    }
    if (!filter_var($_GET['INPUT_FBEMAIL'], FILTER_VALIDATE_EMAIL)) {
        exit('<script>sweetAlert( "แจ้งเตือน", "รูปแบบการกรอก E-Mail ไม่ถูกต้อง ex@mailme.com", "warning" )</script>');
    }
    if ($_GET['INPUT_PW'] != $_GET['INPUT_FBCONFIRM']) {
        exit('<script>sweetAlert( "แจ้งเตือน", "รหัสไม่ตรงกัน", "warning" )</script>');
    }
    if (!is_str($_GET['INPUT_PW'])) {
        exit('<script>swal( "แจ้งเตือน", "การตั้งรหัสผ่านต้องเป็น a-zA-Z0-9_ เท่านั้น", "warning" )</script>');
    }
    if (strlen($_GET['INPUT_PW']) > 32 || strlen($_GET['INPUT_FBCONFIRM']) > 32) {
        exit('<script>sweetAlert( "แจ้งเตือน", "ตั้งรหัสได้ไม่เกิน 32 หลัก", "warning" )</script>');
    }
    $find_acfb = query($conn, "SELECT * FROM Accounts WHERE email = ?;", array($_GET['INPUT_FBEMAIL']));
    $count_acfb = sqlsrv_num_rows($find_acfb);

    $find_wzfb = query($conn, "SELECT * FROM WZ_FACEBOOK WHERE fb_id = ?;", array($_GET['INPUT_FBID']));
    $count_wzfb = sqlsrv_num_rows($find_wzfb);
    if (!isset($_SESSION["hdnCountFriends"])) {
        exit('<script>swal( "แจ้งเตือน", "ระบบไม่สามารถรับข้อมูลจำนวนเพื่อนของคุณได้ >> จำนวนเพื่อนของคุณต้อง ' . $config['registerfb_friends'] . ' ขึ้นไป", "error" )</script>');
    }
    if ($count_wzfb >= 1 || $count_acfb >= 1) {
        exit('<script>sweetAlert( "ล้มเหลว", "ไอดีเฟสบุคหรืออีเมลนี้ถูกสมัครใช้งานในระบบของเราแล้ว", "error" ).then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
    } else {
        if ($config['login_md5']) {
            $ADDD_AC = query($conn, "INSERT INTO Accounts (email, UserID, MD5Password, dateregistered, ReferralID, AccountStatus, lastlogindate, lastloginIP) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array($_GET['INPUT_FBEMAIL'], NULL, md5($config['login_md5_salt'] . $_GET['INPUT_PW']), GetTime(), 0, $config['registerfb_accounstatus'], GetTime(), $ip));
            $ADDD_FB = query($conn, "INSERT INTO WZ_FACEBOOK (email, name, ip, fb_id, timer) VALUES (?, ?, ?, ?, ?)", array($ip, NULL, $_GET['INPUT_FBEMAIL'], $_GET['INPUT_FBID'], GetTime()));
            if ($ADDD_AC && $ADDD_FB) {
                $find_data_accoutAllWhenReg1 = query($conn, "SELECT * FROM Accounts WHERE email = ?;", array($_GET['INPUT_FBEMAIL']));
                if ($find_data_accoutAllWhenReg1) {
                    $output_data_accountAllWhenReg1 = sqlsrv_fetch_array($find_data_accoutAllWhenReg1, SQLSRV_FETCH_ASSOC);
                }
            }
            if (query($conn, "INSERT INTO UsersData (CustomerID, AccountType, dateregistered) VALUES (?, ?, ?);", array($output_data_accountAllWhenReg1['CustomerID'], $config['registerfb_AccountType'], GetTime()))) {
                if (sqlsrv_query($conn, "EXEC WZ_ACCOUNT_CREATEFB_BONUT ?, ?, ?;", array(1, $output_data_accountAllWhenReg1['CustomerID'], $_GET['INPUT_FBEMAIL']))) {
                    exit('<script>swal("แจ้งเตือน", "สมัครสำเร็จยินดีต้อนครับคุณ: ' . $_GET['INPUT_FBNAME'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php?do=logout_fb");
                                  });</script>');
                }
            }
        } else {
            $ADDD_AC = query($conn, "INSERT INTO Accounts (email, UserID, MD5Password, dateregistered, ReferralID, AccountStatus, lastlogindate, lastloginIP) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", array($_GET['INPUT_FBEMAIL'], NULL, $_GET['INPUT_PW'], GetTime(), 0, $config['registerfb_accounstatus'], GetTime(), $ip));
            $ADDD_FB = query($conn, "INSERT INTO WZ_FACEBOOK (email, name, ip, fb_id, timer) VALUES (?, ?, ?, ?, ?)", array($ip, NULL, $_GET['INPUT_FBEMAIL'], $_GET['INPUT_FBID'], GetTime()));
            if ($ADDD_AC && $ADDD_FB) {
                $find_data_accoutAllWhenReg1 = query($conn, "SELECT * FROM Accounts WHERE email = ?;", array($_GET['INPUT_FBEMAIL']));
                if ($find_data_accoutAllWhenReg1) {
                    $output_data_accountAllWhenReg1 = sqlsrv_fetch_array($find_data_accoutAllWhenReg1, SQLSRV_FETCH_ASSOC);
                }
            }
            if (query($conn, "INSERT INTO UsersData (CustomerID, AccountType, dateregistered) VALUES (?, ?, ?);", array($output_data_accountAllWhenReg1['CustomerID'], $config['registerfb_AccountType'], GetTime()))) {
                if (sqlsrv_query($conn, "EXEC WZ_ACCOUNT_CREATEFB_BONUT ?, ?, ?;", array(1, $output_data_accountAllWhenReg1['CustomerID'], $_GET['INPUT_FBEMAIL']))) {
                    exit('<script>swal("แจ้งเตือน", "สมัครสำเร็จยินดีต้อนครับคุณ: ' . $_GET['INPUT_FBNAME'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php?do=logout_fb");
                                  });</script>');
                }
            }
        }
    }
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
                        <form method="get" autocomplete="off">
                            <div style="display: none;" id="return_regis"></div>
                            <input type="hidden" name="FB_ID" id="FB_ID" value="<?php echo $_SESSION['fb_id']; ?>">
                            <center>
                                <h4><img src="https://graph.facebook.com/<?php echo $_SESSION['fb_id']; ?>/picture?type=large"></h4>
                                <a href="https://www.facebook.com/app_scoped_user_id/<?php echo $_SESSION['fb_id']; ?>" target="_blank" role="button" class="btn btn-default">เข้าสู่หน้าโปรไฟล์</a>    
                                <a href="index.php?do=logout_fb" class="btn btn-danger" role="button">ยกเลิก</a><br>
                                <div class="form-group">
                                    <label for="FB_NAME">ชื่อผู้ใช้ Facebook ของคุณ</label>
                                    <input style="color: #00ffff;" type="text" name="FB_NAME" id="FB_NAME" value="<?php echo $_SESSION['fb_name']; ?>" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="FB_EMAIL">Email Facebook ของคุณใช้เวลาล็อกเข้าเว็บและในเกม</label>
                                    <input style="color: <?php
                                    if (empty($_SESSION["fb_email"])) {
                                        echo "white";
                                    } else {
                                        echo "#00ffff";
                                    }
                                    ?>;" type="email" name="FB_EMAIL" id="FB_EMAIL" value="<?php
                                           if (empty($_SESSION["fb_email"])) {
                                               echo "กรุณายืนยัน Email กับทาง Facebook.com";
                                           } else {
                                               echo $_SESSION["fb_email"];
                                           }
                                           ?>" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="pw">Password</label>
                                    <input style="color: #330099; background-color: #ccccff;" type='password' class="form-control" name="pw" id="pw" class="form-control" maxlength="32" required>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_pw">Confirm Password</label>
                                    <input style="color: #003366; background-color: #ccccff;" type='password' class="form-control" name="confirm_pw" id="confirm_pw" class="form-control" maxlength="32" required>
                                </div>
                                <div class="select">
                                    <div><button type="submit" class="btn btn-<?php
                                        if (empty($_SESSION["fb_email"])) {
                                            echo "danger" . " disabled";
                                        } else {
                                            echo "success";
                                        }
                                        ?>" onclick="javascript:input_fb();return false;"><i class="fa fa-check"></i> <?php
                                            if (empty($_SESSION["fb_email"])) {
                                                echo "ไม่สามารถสมัครสมาชิกได้";
                                            } else {
                                                echo "สมัครไอดี";
                                            }
                                            ?></button>
                                    </div>
                                </div>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function input_fb()
    {
        if ($("#confirm_pw").val() == "" || $("#pw").val() == "" || $("#FB_NAME").val() == "" || $("#FB_EMAIL").val() == "" || $("#FB_ID").val() == "") {
            swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
        } else {
            swal({
                title: "คุณแน่ใจไหม?",
                text: "ที่จะยืนยันข้อมูลพวกนี้!",
                icon: "info",
                buttons: true,
                dangerMode: true,
            })
                    .then((inputs) => {
                        if (inputs) {
                            $.get("index.php?form=regis_fb", {INPUT_FBNAME: $('#FB_NAME').val(), INPUT_FBEMAIL: $('#FB_EMAIL').val(), INPUT_FBID: $('#FB_ID').val(), INPUT_PW: $('#pw').val(), INPUT_FBCONFIRM: $('#confirm_pw').val()}, function (data) {
                                $("#return_regis").html(data);
                            });

                        }
                    });
        }
    }
</script>
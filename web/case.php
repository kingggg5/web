<?php
include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/system/db.php';
include_once dirname(__FILE__) . '/system/main.php';
include_once dirname(__FILE__) . '/system/class.truewallet.php';

if (!isset($config)) {
    exit;
}
if (!$config['case_random']) {
    exit;
}
if ($config['auto_ssl']) {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off') {
        exit(header("Location: " . $config['auto_ssl_url'] . ""));
    }
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
} else {
    $ip = $_SERVER["REMOTE_ADDR"];
}
if (!isset($_SESSION['CustomerID'])) {
    exit(rdr("index.php"));
}
include_once dirname(__FILE__) . '/system/aukarapol.php';
?>
<DOCTYPE html>
    <html>
        <?php include_once dirname(__FILE__) . '/head.php'; ?>
        <body>
            <?php include_once dirname(__FILE__) . '/header.php'; ?>
            <div class="container">
                <?php
                if (!isset($_GET['buy'])) {
                    ?>
                    <!--store-->
                    <?php if (isset($_SESSION['CustomerID'])) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> สุ่มไอเท็ม</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div style="background-color: #000000;color: white;">
                                                    <p>
                                                        ใช้ <?php echo $config['case_random_price']; ?> ตั่วต่อการสุ่ม 1 ครั้ง
                                                    </p>
                                                    <p style="color: #66ffff;">
                                                        <?php
                                                        if (empty($_SESSION['case_num']) || $_SESSION['case_num'] == 0) {
                                                            echo 'ตอนนี้คุณไม่มีตั่ว';
                                                        } else {
                                                            echo 'ตอนนี้คุณมีตั่ว ' . $_SESSION['case_num'] . ' ใบ';
                                                        }
                                                        ?>
                                                    </p>
                                                </div>
                                                <hr>
                                                <?php echo $config['case_random_pack']; ?>
                                                <div class="form-group">
                                                    <center>
                                                        <button type="submit" class="btn btn-block btn-primary" style="max-width:20%;" onclick="var r = confirm('ยืนยันการสุ่มด้วยตั่ว <?php echo $config['case_random_price'] . ' ใบ'; ?>');
                                                                if (r == true) {
                                                                    window.location.assign('case.php?buy=yes');
                                                                }" <?php
                                                                if ($_SESSION['case_num'] <= 0 || empty($_SESSION['case_num'])) {
                                                                    echo 'disabled';
                                                                }
                                                                ?>>  <i class="glyphicon glyphicon-random"></i> สุ่มเลย!</button>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        exit(rdr("index.php"));
                    }
                    ?>
                </div>
                <?php
            } elseif (empty($_GET['buy'])) {
                exit(rdr("case.php"));
            } else {
                $random = randdy(1, $config['case_random_amount']);
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> ผลการสุ่มไอเท็มออกมาแล้ว</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <hr>
                                        <div style="background-color: #000000;color: white;">
                                            <p>
                                                ใช้ <?php echo $config['case_random_price']; ?> ตั่วต่อการสุ่ม 1 ครั้ง
                                            </p>
                                            <p style="color: #66ffff;">
                                                <?php
                                                if (empty($_SESSION['case_num']) || $_SESSION['case_num'] == 0) {
                                                    echo 'คุณไม่มีตั่ว';
                                                } else {
                                                    echo 'ตอนนี้คุณมีตั่ว ' . $_SESSION['case_num'] . ' ใบ';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <h5><!-- color: yellow; -->
                                            <?php
                                            if ($_SESSION['case_num'] < $config['case_random_price']) {
                                                echo '<font style="color: red;">ตั่วของคุณไม่เพียงพอในการสุ่ม!</font>';
                                            } elseif ($_SESSION['case_num'] >= $config['case_random_price']) {
                                                $dcn = $_SESSION['case_num'] - $config['case_random_price'];
                                                if (query($conn, "UPDATE Accounts SET case_num = ? WHERE CustomerID = ?;", array($dcn, $_SESSION['CustomerID']))) {
                                                    echo '<font style="color: yellow;">คุณสุ่มได้ไอเท็มแพคที่: ' . $random . ' ตอนนี้ตั่วของคุณคงเหลือ ' . $dcn . '</font>';
                                                }
                                                if (!sqlsrv_query($conn, "EXEC WZ_CASE2018 ?, ?, ?;", array($random, $_SESSION['CustomerID'], $_SESSION['email']))) {
                                                    exit('ไม่สามารถส่งไอเท็มผ่านฟังชั่น WZ_CASE2018 ได้');
                                                }
                                                if (query($conn, "INSERT INTO [WZ_CASE]([email],[pack],[price],[timer]) VALUES (?, ?, ?, ?);", array($_SESSION['email'], $random, $config['case_random_price'], GetTime()))) {
                                                    if ($_SESSION['case_num'] - $config['case_random_price'] > 0) {
                                                        echo('<script>sweetAlert( "แจ้งเตือน", "คุณสุ่มได้แพ็คที่: ' . $random . '", "success" )</script>');
                                                    } elseif ($_SESSION['case_num'] - $config['case_random_price'] == 0) {
                                                        echo('<script>sweetAlert( "แจ้งเตือน", "คุณสุ่มได้แพ็คที่: ' . $random . '", "success" ).then((value) => {
  $(location).attr("href","case.php?buy=yes");
});</script>');
                                                    }
                                                } else {
                                                    exit('ไม่สามารถบันทึกประวัติการสุ่มลง dbo.WZ_CASE ได้');
                                                }
                                                if ($config['case_random_sound'] && $config['case_random']) {
                                                    echo'<audio controls autoplay hidden>
                                                    <source src = "sounds/case/' . rand(1, $config['case_random_sound_amount']) . '.ogg" type = "audio/ogg">
                                                    Your browser does not support the audio element.
                                                    </audio>';
                                                }
                                            }
                                            ?>
                                        </h5>
                                        <?php echo $config['case_random_pack']; ?>
                                        <br>
                                        <div class="form-group">
                                            <center>
                                                <button type="submit" class="btn btn-block btn-primary" style="max-width:20%;" onclick="var r = confirm('ยืนยันการสุ่มด้วยตั่ว <?php echo $config['case_random_price'] . ' ใบ'; ?>');
                                                        if (r == true) {
                                                            window.location.assign('case.php?buy=yes');
                                                        } else {
                                                            window.location.assign('case.php');
                                                        }" <?php
                                                        if ($_SESSION['case_num'] <= 0 || empty($_SESSION['case_num'])) {
                                                            echo 'disabled';
                                                        }
                                                        ?>>  <i class="glyphicon glyphicon-random"></i> สุ่มอีกครั้ง!</button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        <?php } ?>
        <footer>
            <div class="jumbotron">
                <div class="container text-center">
                    <small class="text-muted">Copyright © <?php echo date("Y"); ?> <a href="https://www.facebook.com/aukarapol.sangnoi" target="_blank">DEV AUKARAPOL.</a> WZ-Login v2.3 Product ALL Right Reserved.</small>
                </div>
            </div>
        </footer>
        <!-- Modal -->
        <?php
        if ($config['map_tp']) {
            include_once dirname(__FILE__) . '/map.php';
        }
        if ($config['topup_rename']) {
            include_once dirname(__FILE__) . '/buy_char.php';
        }
        include_once dirname(__FILE__) . '/log.php';
        include_once dirname(__FILE__) . '/promotion.php';
        include_once dirname(__FILE__) . '/download.php';
        include_once dirname(__FILE__) . '/changepw.php';
        if ($config['reward_system']) {
            include_once dirname(__FILE__) . '/log_reward.php';
        }
        if ($config['case_random']) {
            include_once dirname(__FILE__) . '/log_item.php';
        }
        if ($config['Merchants_List']) {
            include_once dirname(__FILE__) . '/Merchants_List.php';
        }
        if ($config['reputation_system']) {
            include_once dirname(__FILE__) . '/reputation.php';
        }
        ?>
        </body>
    </html>
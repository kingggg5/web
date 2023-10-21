<?php
include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/system/db.php';
include_once dirname(__FILE__) . '/system/main.php';
include_once dirname(__FILE__) . '/system/class.truewallet.php';

if (!isset($config)) {
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
?>
<DOCTYPE html>
    <html>
        <?php include_once dirname(__FILE__) . '/head.php'; ?>
        <body>
            <?php include_once dirname(__FILE__) . '/header.php'; ?>
            <div class="container">
                <?php
                include_once dirname(__FILE__) . '/system/aukarapol.php'; //System
                if (isset($_GET['do'])) {
                    if ($_GET['do'] == "logout") {
                        include_once dirname(__FILE__) . '/login.php';
                        include_once dirname(__FILE__) . '/logout.php';
                    }
                    if ($_GET['do'] == "logout_fb") {
                        include_once dirname(__FILE__) . '/login.php';
                        include_once dirname(__FILE__) . '/logout_fb.php';
                    }
                }
                ?>
                <!--breadcrumb-->
                <?php
                if ($config['Promotion']) {
                    foreach ($config['true_topup_promotion_day'] as $day => $var) {
                        if (checkDayofTime() == $day) {
                            if (isset($var) && is_numeric($var) && $var > 1) {
                                ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <ol class="breadcrumb">
                                            <h4>
                                                <li>
                                                <center>
                                                    <img src="img/gcx<?php
                                                    echo $var;
                                                    ?>.png">
                                                </center>
                                                </li>
                                            </h4>
                                        </ol>
                                    </div>
                                </div> 
                                <?php
                            }
                        }
                        continue;
                    }
                }
                ?>
                <!--Pages-->
                <?php
                if (isset($_SESSION['CustomerID'])) {
                    include_once dirname(__FILE__) . '/user.php';
                } elseif (isset($_GET['form']) && $config['fb']['regis']) {
                    if (isset($_SESSION["fb_id"]) || isset($_SESSION["fb_email"]) || isset($_SESSION["fb_name"]) || isset($_SESSION["fb_link"]) || isset($_SESSION["fb_img"])) {
                        if (isset($_GET['form']) == "regis_fb") {
                            include_once dirname(__FILE__) . '/regis_fb.php';
                        }
                    } else {
                        exit(rdr("index.php"));
                    }
                } else {
                    include_once dirname(__FILE__) . '/login.php';
                }
                ?>
            </div>
            <footer>
                <div class="jumbotron">
                    <div class="container text-center">
                        <small class="text-muted">Copyright © <?php echo date("Y"); ?> <a href="https://www.facebook.com/aukarapol.sangnoi" target="_blank">DEV AUKARAPOL.</a> WZ-Login v2.3 Product ALL Right Reserved.</small>
                    </div>
                </div>
            </footer>
            <!-- Modal -->
            <?php
            include_once dirname(__FILE__) . '/promotion.php';
            include_once dirname(__FILE__) . '/download.php';
            if (isset($_SESSION['CustomerID'])) {
                if ($config['map_tp']) {
                    include_once dirname(__FILE__) . '/map.php';
                }
                if ($config['topup_rename']) {
                    include_once dirname(__FILE__) . '/buy_char.php';
                }
                include_once dirname(__FILE__) . '/log.php';
                include_once dirname(__FILE__) . '/changepw.php';
                if ($config['reward_system']) {
                    include_once dirname(__FILE__) . '/log_reward.php';
                }
                if ($config['case_random']) {
                    include_once dirname(__FILE__) . '/log_item.php';
                }
                if ($config['reputation_system']) {
                    include_once dirname(__FILE__) . '/reputation.php';
                }
            }
            if ($config['Merchants_List']) {
                include_once dirname(__FILE__) . '/Merchants_List.php';
            }
            ?>
        </body>
    </html>
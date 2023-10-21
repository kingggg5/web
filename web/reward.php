<?php
include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/system/db.php';
include_once dirname(__FILE__) . '/system/main.php';
include_once dirname(__FILE__) . '/system/class.truewallet.php';

if (!isset($config)) {
    exit;
}
if (!$config['reward_system']) {
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
            <?php
            include_once dirname(__FILE__) . '/header.php';
            if (isset($_GET['buy'])) {
                if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
                    exit(rdr("reward.php"));
                } else {
                    $list_shop = query($conn, "SELECT * FROM WZ_AUKARAPOL_SHOP WHERE id = ?;", array($_GET['id']));
                    if (!$list_shop) {
                        exit(rdr("reward.php"));
                    } else {
                        $product_f = sqlsrv_fetch_array($list_shop, SQLSRV_FETCH_ASSOC);
                    }
                    include_once dirname(__FILE__) . '/reward_buy.php';
                }
            } else {
                ?>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Reward System</h3>
                        </div>
                        <div class="container" style="color: #d9d9d9;">
                            <div class="ainbow center" style="height: 2.5%;text-align: center;background-color: #003333;">
                                <h5 class="rainbow text-center">Reward Online เมื่อแลกไปแล้วของจะไปอยู่ในคลัง</h5>
                            </div>
                            <hr>
                            <?php
                            $list_q = query($conn, "SELECT * FROM WZ_AUKARAPOL_SHOP");
                            $row_item = sqlsrv_num_rows($list_q);
                            ?>
                            <div class="panel panel-heading">
                                <span><i class="glyphicon glyphicon-gift"></i>เวลาออนไลน์ของคุณ: <?php echo $_SESSION['TimePlayed'] . ' วินาทีหรือ ' . $_SESSION['TimePlayed'] / 3600 . ' ชั่วโมงหรือ ' . $_SESSION['TimePlayed'] / 24 . ' วัน'; ?></span>
                            </div>
                            <?php
                            if ($row_item == 0) {
                                echo '
                    <hr>
                    <div class="col-sm-6 col-md-4">
                        <div class="alert alert-danger"><i class="glyphicon glyphicon-gift"></i>ไม่มีรางวัลในการออนไลน์</div>
                    </div>
                    ';
                            }
                            ?>
                            <?php while ($product = sqlsrv_fetch_array($list_q, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                <div class="thumbnail" style="color: #e6e6e6;">
                                    <img src="<?php
                                    if (empty($product['picture'])) {
                                        echo 'img/error.png';
                                    } else {
                                        echo $product['picture'];
                                    };
                                    ?>" style="height: 15%; width: 30%; display: block;">
                                    <div class="caption" style="color: #d9d9d9;">
                                        <h3><?php echo $product['name']; ?></h3>
                                        <p style="color: #ffffff;">ใช้เวลาออนไลน์ : <?php echo $product['price']; ?> วินาที.</p>
                                        <p>จำนวน : <?php echo $product['item_num']; ?> <?php
                                            if ($product['item_id'] == $config['reward_gc']['item_id']) {
                                                echo "GC";
                                            } else {
                                                echo "ชิ้น.";
                                            }
                                            ?></p>
                                        <p>
                                            <a class="btn btn-<?php
                                            if ($_SESSION['TimePlayed'] < $product['price']) {
                                                echo "warning disabled";
                                            } else {
                                                echo "success";
                                            }
                                            ?>" <?php if ($_SESSION['TimePlayed'] >= $product['price']) { ?> onclick="var txt;
                                                                var r = confirm('ยืนยันการแลก <?php echo $product['name']; ?> x<?php echo $product['item_num']; ?>');
                                                                if (r == true) {
                                                                    window.location.assign('reward.php?buy=yes&id=<?php echo $product['id']; ?>');
                                                                }" <?php } ?>><i class="glyphicon glyphicon-shopping-cart"></i><?php
                                               if ($_SESSION['TimePlayed'] < $product['price']) {
                                                   $money_ = $_SESSION['TimePlayed'] - $product['price'];
                                                   echo "เวลาออนไลน์ไม่เพียงพอ!<br>ขาดอีก " . $money_ . " วินาที";
                                               } else {
                                                   echo "แลก";
                                               }
                                               ?></a>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <br>
            <?php } ?>
            <div class="col-xs-12">
                <div class="jumbotron">
                    <div class="container text-center">
                        <small class="text-muted">Copyright © <?php echo date("Y"); ?> <a href="https://www.facebook.com/aukarapol.sangnoi" target="_blank">DEV AUKARAPOL.</a> WZ-Login v2.3 Product ALL Right Reserved.</small>
                    </div>
                </div>
            </div>
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
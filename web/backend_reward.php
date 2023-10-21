<?php
include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/system/db.php';
include_once dirname(__FILE__) . '/system/main.php';
include_once dirname(__FILE__) . '/system/class.truewallet.php';

if (!isset($config)) {
    exit;
}
if (!isset($_SESSION['email']) || !isset($_SESSION['CustomerID']) || !isset($_SESSION['TimePlayed']) || !isset($_SESSION['GamePoints']) || !isset($_SESSION['GameDollars']) || !isset($_SESSION['lastgamedate']) || !isset($_SESSION['IsDeveloper'])) {
    exit(rdr("index.php"));
}
if ($_SESSION['IsDeveloper'] != $config['reward_backend_isDev']) {
    exit(rdr("index.php"));
}
if (!$config['reward_system']) {
    exit;
}
if (isset($_GET['action'])) {
    echo '<meta charset="utf-8">';

    if (isset($_POST)) {
        if ($_POST['action'] == "item-create") {
            $item_qc = query($conn, "SELECT * FROM WZ_AUKARAPOL_SHOP WHERE name = ?;", array($_POST['name']));
            $row_itemgc = sqlsrv_num_rows($item_qc);
            if ($row_itemgc >= 1) {
                exit('มีอยู่ละ ' . $_POST['name'] . ' <a href="backend_reward.php">backend_reward.php</a>');
            } else {
                if (query($conn, "INSERT INTO WZ_AUKARAPOL_SHOP (name, price, item_id, item_num, picture) VALUES (?, ?, ?, ?, ?);", array($_POST['name'], $_POST['price'], $_POST['item_id'], $_POST['item_num'], $_POST['url_items']))) {
                    exit('เพิ่มไอเท็มสำเร็จ: ' . $_POST['name'] . ' <a href="backend_reward.php">backend_reward.php</a>');
                }
            }
        }
        if ($_POST['action'] == "item-edit") {
            $item_qe = query($conn, "SELECT * FROM WZ_AUKARAPOL_SHOP WHERE name = ?;", array($_POST['name_e']));
            $row_itemqe = sqlsrv_num_rows($item_qe);
            if ($row_itemqe == 0) {
                exit('ไม่พบ item name นี้: ' . $_POST['name_e'] . ' <a href="backend_reward.php">backend_reward.php</a>');
            } else {
                if (empty($_POST['new_name_e']) && empty($_POST['url_items_e']) && empty($_POST['price_e']) && empty($_POST['item_num_e']) && empty($_POST['item_id_e'])) {
                    exit('ตรวจสำเร็จพบชื่อไอเท็มนี้: ' . $_POST['name_e'] . ' <a href="backend_reward.php">backend_reward.php</a>');
                } else {
                    if (!empty($_POST['new_name_e']) && !empty($_POST['url_items_e']) && !empty($_POST['price_e']) && !empty($_POST['item_num_e']) && !empty($_POST['item_id_e'])) {
                        exit('ใจเย็นระบบอัพเดทข้อมูลไม่ได้ถ้าจะเปลี่ยนทั้งหมดก็เปลี่ยนชื่อที่จะเปลี่ยนอย่างอื่นทั้งหมด เข้าใจ? <a href="backend_reward.php">backend_reward.php</a>');
                    }
                    if (!empty($_POST['new_name_e'])) {
                        query($conn, "UPDATE WZ_AUKARAPOL_SHOP SET name =? WHERE name =?;", array($_POST['new_name_e'], $_POST['name_e']));
                    }
                    if (!empty($_POST['url_items_e'])) {
                        query($conn, "UPDATE WZ_AUKARAPOL_SHOP SET picture =? WHERE name =?;", array($_POST['url_items_e'], $_POST['name_e']));
                    }
                    if (!empty($_POST['price_e'])) {
                        query($conn, "UPDATE WZ_AUKARAPOL_SHOP SET price =? WHERE name =?;", array($_POST['price_e'], $_POST['name_e']));
                    }
                    if (!empty($_POST['item_num_e'])) {
                        query($conn, "UPDATE WZ_AUKARAPOL_SHOP SET item_num =? WHERE name =?;", array($_POST['item_num_e'], $_POST['name_e']));
                    }
                    if (!empty($_POST['item_id_e'])) {
                        query($conn, "UPDATE WZ_AUKARAPOL_SHOP SET item_id =? WHERE name =?;", array($_POST['item_id_e'], $_POST['name_e']));
                    }
                    exit('แก้ไขไอเท็มสำเร็จตามที่รับข้อมูล <a href="backend_reward.php">backend_reward.php</a>');
                }
            }
        }
        if ($_POST['action'] == "del-item") {
            $item_qr = query($conn, "SELECT * FROM WZ_AUKARAPOL_SHOP WHERE name = ?;", array($_POST['remove-name']));
            $row_qr = sqlsrv_num_rows($item_qr);
            if ($row_qr == 0) {
                exit('ไม่พบ item name นี้: ' . $_POST['remove-name'] . '');
            } else {
                if (query($conn, "DELETE FROM WZ_AUKARAPOL_SHOP WHERE name=?;", array($_POST['remove-name']))) {
                    exit('ลบไอเท็มสำเร็จ: ' . $_POST['remove-name'] . ' <a href="backend_reward.php">backend_reward.php</a>');
                }
            }
        } {
            exit;
        }
    } else {
        exit;
    }
}
include_once dirname(__FILE__) . '/system/aukarapol.php';
?>
<DOCTYPE html>
    <html>
        <?php include_once dirname(__FILE__) . '/head.php'; ?>
        <body>
            <?php include_once dirname(__FILE__) . '/header.php'; ?>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Reward System: Manager</h3>
                    </div>
                    <div class="ainbow center" style="height: 2.5%;text-align: center;background-color: #003333;">
                        <h5 class="rainbow text-center">Admin Panel Reward Online ขณะนี้ผู้เล่นออนไลน์: <?php echo getPlayerOnline(); ?></h5>
                    </div>
                    <hr>
                    <div class="container">
                        <form action="backend_reward.php?action=yes" method="post" autocomplete="off">
                            <input type="hidden" name="action" value="item-create">
                            <div class="thumbnail">
                                <div class="header"><h5 style="color: white;">Add items reward: ถ้าจะแลก GC ให้ใส่เลขไอเท็มเป็น <?php echo $config['reward_gc']['item_id']; ?></h5></div>
                                <img src="img/add.png" data-holder-rendered="true" style="height: 15%; width: 15%;">
                                <div class="caption">
                                    <input class="form-control" name="name" type="text" placeholder="ชื่อสินค้า.." required>
                                    <input class="form-control" name="url_items" type="text" placeholder="ลิงก์รูปภาพ.. (หากไม่ใส่จะเป็นรูปบร็อคหญ้า)">
                                    <input class="form-control" name="price" type="number" placeholder="ราคา.." required>
                                    <input class="form-control" name="item_num" type="number" placeholder="จำนวน.." value="1" required>
                                    <input class="form-control" name="item_id" type="text" placeholder="เลขไอเท็ม" required><br>
                                    <button class="btn btn-success" type="submit">สร้าง</button>
                                </div>
                            </div>
                        </form>
                        <form action="backend_reward.php?action=yes" method="post" autocomplete="off">
                            <input type="hidden" name="action" value="item-edit">
                            <div class="thumbnail">
                                <div class="header"><h5 style="color: white;">Edit items reward:</h5></div>
                                <img src="img/edit.png" data-holder-rendered="true" style="height: 15%; width: 15%;">
                                <div class="caption">
                                    <input class="form-control" name="name_e" type="text" placeholder="ชื่อสินค้า.." required><br>
                                    <input class="form-control" name="new_name_e" type="text" placeholder="ชื่อสินค้าที่จะเปลี่ยนชื่อ (ไม่เปลี่ยนไม่ต้องใส่)..">
                                    <input class="form-control" name="url_items_e" type="text" placeholder="ลิงก์รูปภาพที่จะเปลี่ยน (ไม่เปลี่ยนไม่ต้องใส่)..">
                                    <input class="form-control" name="price_e" type="number" placeholder="ราคาที่จะเปลี่ยน (ไม่เปลี่ยนไม่ต้องใส่)..">
                                    <input class="form-control" name="item_num_e" type="number" placeholder="จำนวนที่จะเปลี่ยน (ไม่เปลี่ยนไม่ต้องใส่)..">
                                    <input class="form-control" name="item_id_e" type="text" placeholder="เลขไอเท็ม"><br>
                                    <button class="btn btn-info" type="submit">แก้ไข</button>
                                </div>
                            </div>
                        </form>
                        <form action="backend_reward.php?action=yes" method="post" autocomplete="off">
                            <input type="hidden" name="action" value="del-item">
                            <div class="thumbnail">
                                <div class="header"><h5 style="color: white;">Remove items reward:</h5></div>
                                <img src="img/remove.png" data-holder-rendered="true" style="height: 15%; width: 15%;">
                                <div class="caption">
                                    <input class="form-control" name="remove-name" type="text" placeholder="ชื่อสินค้า.." required><br>
                                    <button class="btn btn-danger" type="submit">ลบ</button>
                                </div>
                            </div>
                        </form>
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
                            <div class="thumbnail">
                                <img src="<?php
                                if (empty($product['picture'])) {
                                    echo 'img/error.png';
                                } else {
                                    echo $product['picture'];
                                };
                                ?>" style="height: 15%; width: 30%; display: block;">
                                <div class="caption">
                                    <h3><?php echo $product['name']; ?></h3>
                                    <p style="color: red;">เลขไอเท็ม : <?php echo $product['item_id']; ?></p>
                                    <p style="color: white;">ใช้เวลาออนไลน์ : <?php echo $product['price']; ?> วินาที.</p>
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
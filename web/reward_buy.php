<?php
if (!isset($config)) {
    exit;
}
?>
<div style="background-color: black;color: white;">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Reward System: succeed</h3>
            </div>
            <div class="panel panel-default">
                <div class="ainbow center" style="text-align: center;background-color: #003333;">
                    <h5 class="rainbow text-center">รายการ</h5>
                </div>
                <div class="container">
                    <div class="panel panel-heading">
                        <img src="<?php
                        if (empty($product_f['picture'])) {
                            echo $config['url'] . 'img/error.png';
                        } else {
                            echo $product_f['picture'];
                        };
                        ?>" width="15%" height="15%">
                        <hr>
                        <p>
                            <span><i class="glyphicon glyphicon-user"></i> ข้อมูลบัญชีในการทำรายการ</span>
                        </p>
                        <p>
                            รหัสประจำไอดี: <?php echo $_SESSION['CustomerID']; ?>
                        </p>
                        <p>
                            อีเมล: <?php echo $_SESSION['email']; ?>
                        </p>
                        <p>
                            <small>เงินในเกม: <?php echo $_SESSION['GameDollars'] . ' GameDollars'; ?></small>
                        </p>
                        <p>
                            <small>ทองในเกม: <?php echo $_SESSION['GamePoints'] . ' GameCoin'; ?></small>
                        </p>
                        <p>
                            <small>เวลาในการออนไลน์ในเกม: <?php echo $_SESSION['TimePlayed'] . ' วินาทีหรือ ' . $_SESSION['TimePlayed'] / 3600 . ' ชั่วโมงหรือ ' . $_SESSION['TimePlayed'] / 24 . ' วัน'; ?></small>
                        </p>
                        <hr>
                        <p>
                            <span><i class="glyphicon glyphicon-send"></i> ข้อมูลการแลก</span>
                        </p>
                        <p style="color: #0000ff;">
                            ชื่อรายการ: <?php echo $product_f['name']; ?>
                        </p>
                        <p style="color: red;">
                            ราคา: <?php echo $product_f['price']; ?> วินาที
                        </p>
                        <p>
                            รหัสไอเท็ม: <?php echo $product_f['item_id']; ?>
                        </p>
                        <p>
                            จำนวน: <?php echo $product_f['item_num']; ?>
                        </p>
                        <hr>
                        <p><span><i class="glyphicon glyphicon-star"></i>สถานะ: 
                                <?php
                                $money_b = $_SESSION['TimePlayed'] - $product_f['price'];
                                if ($_SESSION['TimePlayed'] >= $product_f['price']) {
                                    if ($product_f['item_id'] == $config['reward_gc']['item_id']) {
                                        if (query($conn, "UPDATE UsersData SET TimePlayed = ? WHERE CustomerID = ?;", array($money_b, $_SESSION['CustomerID']))) {
                                            $gc_adding = $_SESSION['GamePoints'] + $product_f['item_num'];
                                            if (query($conn, "UPDATE UsersData SET GamePoints = (GamePoints + ?) WHERE CustomerID = ?;", array($product_f['item_num'], $_SESSION['CustomerID']))) {
                                                echo '<font style="color: green;">ทำรายการสำเร็จ ตอนนี้เวลาออนไลน์คงเหลือ ' . $money_b . ' วินาทีและทองคงเหลือ ' . $gc_adding . ' GC</font>';
                                                if (!query($conn, "INSERT INTO [WZ_REWARD_LOG]([email],[name],[amount],[price],[timer]) VALUES (?, ?, ?, ?, ?);", array($_SESSION['email'], $product_f['name'], $product_f['item_num'], $product_f['price'], GetTime()))) {
                                                    echo '<br><font style="color: red;">ไม่สามารถบันทึกประวัติการแลกได้ โปรดลง WZ_REWARD_LOG.sql</font>';
                                                }
                                            }
                                        }
                                    } else {
                                        if (query($conn, "UPDATE UsersData SET TimePlayed = ? WHERE CustomerID = ?;", array($money_b, $_SESSION['CustomerID']))) {
                                            if (query($conn, "INSERT INTO UsersInventory (CustomerID, CharID, ItemID, LeasedUntil, Quantity, Var1, Var2, Var3) VALUES (?, ?, ?, DATEADD(day, 2000, GETDATE()), ?, ?, ?, ?)", array($_SESSION['CustomerID'], 0, $product_f['item_id'], $product_f['item_num'], -1, -1, 10000))) {
                                                echo '<font style="color: green;">ทำรายการสำเร็จ ตอนนี้เวลาออนไลน์คงเหลือ ' . $money_b . ' วินาที</font>';
                                                if (!query($conn, "INSERT INTO [WZ_REWARD_LOG]([email],[name],[amount],[price],[timer]) VALUES (?, ?, ?, ?, ?);", array($_SESSION['email'], $product_f['name'], $product_f['item_num'], $product_f['price'], GetTime()))) {
                                                    echo '<br><font style="color: red;">ไม่สามารถบันทึกประวัติการแลกได้ โปรดลง WZ_REWARD_LOG.sql</font>';
                                                }
                                            }
                                        }
                                    }
                                } elseif ($_SESSION['TimePlayed'] < $product_f['price']) {
                                    echo '<font style="color: red;">ทำรายการไม่สำเร็จ ขาดเวลาออนไลน์อีก ' . $money_b . ' วินาที</font>';
                                }
                                ?>
                        </p>
                        <button class="btn btn-mini" onclick="window.location.assign('reward.php');" style="color: black;">เสร็จสิ้น</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
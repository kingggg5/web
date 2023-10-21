<?php
if (!isset($config)) {
    exit;
}
if (!$config['reward_system']) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="log_reward">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> ประวติการแลก ไอเท็ม/GC 20 รายการล่าสุด </h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:center" width="8%">#</th>
                            <th style="text-align:center" width="32%">ชื่อ</th>
                            <th style="text-align:center" width="28%">จำนวน</th>
                            <th style="text-align:center" width="20%">ราคา(วินาที)</th>
                            <th style="text-align:center" width="12%">วันที่และเวลา</th>
                        </tr>
                        <?php
                        $find_data_logsit = query($conn, "SELECT TOP 20 * FROM WZ_REWARD_LOG WHERE email = ? ORDER BY id DESC;", array($_SESSION['email']));
                        //$find_data_logsi = query($conn, "SELECT * FROM WZ_REWARD_LOG WHERE email = ?;", array($_SESSION['email']));
                        $count_logit = sqlsrv_num_rows($find_data_logsit);

                        if ($count_logit != 0) {
                            $iit = 0;
                            while ($logit = sqlsrv_fetch_array($find_data_logsit, SQLSRV_FETCH_ASSOC)) {
                                $iit++;
                                ?>
                                <tr style="text-align:center" class="info>">
                                    <td><?php echo $iit; ?></td>
                                    <td><?php echo $logit['name']; ?></td>
                                    <td><?php echo $logit['amount']; ?></td>
                                    <td><?php echo $logit['price']; ?></td>
                                    <td><span class="label label-success"><?php echo $logit['timer']; ?></span></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr style="text-align:center"class="warning">
                                <td colspan="5">ไม่พบประวัติของคุณ: <?php
                                    if (empty($_SESSION['email'])) {
                                        echo "โปรดเข้าสู่ระบบ";
                                    } else {
                                        echo $_SESSION['email'];
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                    </thead>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
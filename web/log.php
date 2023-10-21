<?php
if (!isset($config)) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="log">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> ประวติการเติมเงิน 20 รายการล่าสุด </h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:center" width="8%">#</th>
                            <th style="text-align:center" width="32%">รหัสบัตรทรูมันนี่</th>
                            <th style="text-align:center" width="28%">จำนวนเงิน</th>
                            <th style="text-align:center" width="20%">วันที่และเวลา</th>
                            <th style="text-align:center" width="12%">ประเภท</th>
                        </tr>
                        <?php
                        $find_data_logs = query($conn, "SELECT TOP 20 * FROM wz_TruemoneyTopup_TBL WHERE email = ? ORDER BY card_id DESC;", array($_SESSION['email']));
                        //$find_data_logs = query($conn, "SELECT * FROM wz_TruemoneyTopup_TBL WHERE email = ?;", array($_SESSION['email']));
                        $count_log = sqlsrv_num_rows($find_data_logs);

                        if ($count_log != 0) {
                            $i = 0;
                            while ($log = sqlsrv_fetch_array($find_data_logs, SQLSRV_FETCH_ASSOC)) {
                                $i++;
                                ?>
                                <tr style="text-align:center" class="info>">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $log['cardserial']; ?></td>
                                    <td><?php echo $log['amount']; ?></td>
                                    <td><?php echo $log['timer']; ?></td>
                                    <td><span class="label label-success">บัตรทรูมันนี่</span></td>
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
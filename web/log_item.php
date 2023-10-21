<?php
if (!isset($config)) {
    exit;
}
if (!$config['case_random']) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="log_item">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> ประวติการสุ่มไอเท็ม 20 รายการล่าสุด </h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:center" width="8%">#</th>
                            <th style="text-align:center" width="32%">แพ็คที่สุ่มได้</th>
                            <th style="text-align:center" width="28%">ราคา</th>
                            <th style="text-align:center" width="20%">วันที่และเวลา</th>
                            <th style="text-align:center" width="12%">ประเภท</th>
                        </tr>
                        <?php
                        $find_data_logsi = query($conn, "SELECT TOP 20 * FROM WZ_CASE WHERE email = ? ORDER BY id DESC;", array($_SESSION['email']));
                        //$find_data_logsi = query($conn, "SELECT * FROM WZ_CASE WHERE email = ?;", array($_SESSION['email']));
                        $count_logi = sqlsrv_num_rows($find_data_logsi);

                        if ($count_logi != 0) {
                            $ii = 0;
                            while ($logi = sqlsrv_fetch_array($find_data_logsi, SQLSRV_FETCH_ASSOC)) {
                                $ii++;
                                ?>
                                <tr style="text-align:center" class="info>">
                                    <td><?php echo $ii; ?></td>
                                    <td><?php echo $logi['pack']; ?></td>
                                    <td><?php echo $logi['price'] . ' ตั่ว'; ?></td>
                                    <td><?php echo $logi['timer']; ?></td>
                                    <td><span class="label label-success">สุ่มไอเท็ม</span></td>
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
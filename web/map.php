<?php
if (!isset($config)) {
    exit;
}
if (!$config['map_tp']) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="map_tp">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> ย้ายแมพตัวละคเสีย <?php echo $config['map_tp_price']; ?> GameCoin(GC) ต่อการย้าย 1 ตัวละคร</h4>
            </div>
            <div class="col-xs-12">
                <form class="panel-form text-left" method="get">
                    <div id="return_map_tp" style="display: none;"></div>
                    <div class="form-group">
                        <?php
                        $find_data_all_uchar2 = query($conn, "SELECT * FROM UsersChars WHERE CustomerID = ?;", array($_SESSION['CustomerID']));
                        $count_chars2 = sqlsrv_num_rows($find_data_all_uchar2);
                        if ($count_chars2 > 0) {
                            ?>

                            <?php
                            echo '
                            <label>เลือกตัวละคร : </label>
                            <select name="var_char_id2" id="var_char_id2" style="background-color: #0000ff;">
                            ';
                            while ($row_char2 = sqlsrv_fetch_array($find_data_all_uchar2, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value="' . $row_char2['CharID'] . '">' . $row_char2['Gamertag'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<small style="color: red;">ไม่พบข้อมูลตัวละครของคุณ</small>';
                        }
                        ?>  
                    </div>
                    <?php include_once dirname(__FILE__) . '/map_city.setting.php'; ?>

                    <div class="form-group">
                        <center>
                            <button type="submit" class="btn btn-block <?php
                            if ($_SESSION['GamePoints'] < $config['map_tp_price']) {
                                echo 'btn-danger';
                            } else {
                                echo 'btn-info';
                            }
                            ?>" style="max-width:20%;" onclick="javascript:onNowMap();return false;" <?php
                                    if ($count_chars2 == 0 || $_SESSION['GamePoints'] < $config['map_tp_price']) {
                                        echo 'disabled' . ' style="color: white;"';
                                    }
                                    ?>>  <i class="glyphicon glyphicon-ok-sign"></i> <?php
                                    if ($_SESSION['GamePoints'] < $config['map_tp_price']) {
                                        echo "ขาด GC อีก ";
                                        echo $_SESSION['GamePoints'] - $config['map_tp_price'];
                                    } else {
                                        echo 'ย้ายเลย!';
                                    }
                                    ?></button>
                        </center>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    function onNowMap()
    {
        if ($("#var_char_id2").val() == "" || $("#pos").val() == "") {
            swal("แจ้งเตือน", "เลือกข้อมูลให้ครบชื่อตัวละครและชื่อแมพ!", "info")
        } else {
            $.get("index.php", {char_id_map: $('#var_char_id2').val(), map_pos: $('#pos').val()}, function (data) {
                $("#return_map_tp").html(data);
            });
        }
    }
</script>
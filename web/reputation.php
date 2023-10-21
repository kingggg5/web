<?php
if (!isset($config)) {
    exit;
}
if (!$config['reputation_system']) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="reputation">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> รียศเสีย <?php echo $config['reputation_price']; ?> GameCoin(GC) ต่อการรี 1 ตัวละคร</h4>
            </div>
            <div class="col-xs-12">
                <form class="panel-form text-left" method="get">
                    <div id="return_map_tp" style="display: none;"></div>
                    <div class="form-group">
                        <?php
                        $find_data_all_uchar22 = query($conn, "SELECT * FROM UsersChars WHERE CustomerID = ?;", array($_SESSION['CustomerID']));
                        $count_chars22 = sqlsrv_num_rows($find_data_all_uchar22);
                        if ($count_chars22 > 0) {
                            ?>

                            <?php
                            echo '
                            <label>เลือกตัวละคร : </label>
                            <select name="var_char_id3" id="var_char_id3" style="background-color: #0000ff;">
                            ';
                            while ($list_char2 = sqlsrv_fetch_array($find_data_all_uchar22, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value="' . $list_char2['CharID'] . '">' . $list_char2['Gamertag'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<small style="color: red;">ไม่พบข้อมูลตัวละครของคุณ</small>';
                        }
                        ?>  
                    </div>
                    <div class="form-group">
                        <center>
                            <button type="submit" class="btn btn-primary btn-block <?php
                            if ($_SESSION['GamePoints'] < $config['reputation_price']) {
                                echo 'btn-danger';
                            } else {
                                echo 'btn-info';
                            }
                            ?>" style="max-width:20%;" onclick="javascript:onReputation();return false;" <?php
                                    if ($count_chars22 == 0 || $_SESSION['GamePoints'] < $config['reputation_price']) {
                                        echo 'disabled' . ' style="color: white;"';
                                    }
                                    ?>>  <i class="glyphicon glyphicon-ok-sign"></i> <?php
                                    if ($_SESSION['GamePoints'] < $config['reputation_price']) {
                                        echo "ขาด GC อีก ";
                                        echo $_SESSION['GamePoints'] - $config['reputation_price'];
                                    } else {
                                        echo 'รีเลย!';
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
    function onReputation()
    {
        if ($("#var_char_id3").val() == "") {
            swal("แจ้งเตือน", "โปลดเลือกตัวละครที่ต้องการรียศเป็น 0!", "info")
        } else {
            $.get("index.php", {char_id_reputation: $('#var_char_id3').val()}, function (data) {
                $("#return_map_tp").html(data);
            });
        }
    }
</script>
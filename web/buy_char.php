<?php
if (!isset($config)) {
    exit;
}
if (!$config['topup_rename']) {
    exit;
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="char_rename">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: #ff090c;"><i class="glyphicon glyphicon-off"></i></span></button>
                <h4 class="modal-title style1" id="myModalLabel"> <?php
                    if ($config['topup_rename_price'] == 0) {
                        echo 'เปลี่ยนชื่อกำหนดเองได้ฟรี';
                    } else {
                        echo 'เติมเงินเปลี่ยนชื่อกำหนดเองได้';
                    }
                    ?> </h4>
            </div>
            <div class="col-xs-12">
                <form class="panel-form text-left" method="get">
                    <div id="return_varchar" style="display: none;"></div>
                    <div class="form-group">
                        <?php
                        $find_data_all_uchar = query($conn, "SELECT * FROM UsersChars WHERE CustomerID = ?;", array($_SESSION['CustomerID']));
                        $count_chars = sqlsrv_num_rows($find_data_all_uchar);
                        if ($count_chars > 0) {
                            ?>

                            <?php
                            echo '
                            <label>เลือกตัวละคร : </label>
                            <select name="var_char_id" id="var_char_id" style="background-color: #0000ff;">
                            ';
                            while ($row_char = sqlsrv_fetch_array($find_data_all_uchar, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value="' . $row_char['CharID'] . '">' . $row_char['Gamertag'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<small style="color: red;">ไม่พบข้อมูลตัวละครของคุณ</small>';
                        }
                        ?>  
                    </div>
                    <div class="form-group">
                        <!-- (ถ้าจะเอาสี ใส่ $ ไว้ข้างหน้าชื่อที่จะเปลี่ยน จะขึ้นสีตามแคลน) -->
                        <label for="new_char_rename">ชื่อตัวละครที่จะเปลี่ยน ไม่เกิน <?php
                            echo $config['char_rename_maxleng'] . ' ตัวอักษร';
                            if ($config['char_rename_language'] == 1) {
                                echo ' English (a-zA-Z0-9_)';
                            } elseif ($config['char_rename_language'] == 2) {
                                echo ' TH + ENG (0-9A-Zก-ฮ _)';
                            }
                            ?></label>
                        <input type="text" class="form-control" name="new_char_rename" id="new_char_rename" placeholder="ชื่อตัวละครที่จะเปลี่ยน" maxlength="<?php echo $config['char_rename_maxleng']; ?>" required="" <?php
                        if ($count_chars == 0) {
                            echo 'disabled' . ' value="โปรดสร้างตัวละครในเกม"' . ' style="color: white;"';
                        }
                        ?>>
                    </div>
                    <?php if ($config['topup_rename_price'] > 0) { ?>
                        <div class = "form-group">
                            <label for = "truemoney_pin">บัตรทรูมันนี่ ราคา <?php echo $config['topup_rename_price'];
                        ?> บาทเท่านั้น</label>
                            <input type="text" class="form-control" name="truemoney_pin" id="truemoney_pin" placeholder="รหัสบัตรทรูมันนี่ <?php echo $config['maxleng']['truemoney'] . ' หลัก'; ?>" maxlength="<?php echo $config['maxleng']['truemoney']; ?>" required="">
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <center><button type="submit" class="btn btn-block btn-success" style="max-width:20%;" onclick="javascript:onInputChar();return false;">  <i class="glyphicon glyphicon-ok-sign"></i> เปลี่ยนเลย!</button></center>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php if ($config['topup_rename_price'] > 0) { ?>
    <script>
        function onInputChar()
        {
            if ($("#var_char_id").val() == "" || $("#new_char_rename").val() == "" || $("#truemoney_pin").val() == "") {
                swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
            } else {
                swal({
                    title: "คุณแน่ใจไหม?",
                    text: "ที่จะยืนยันข้อมูลพวกนี้!",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
                })
                        .then((inputs) => {
                            if (inputs) {
                                $.get("index.php", {char_id: $('#var_char_id').val(), char_rename: $('#new_char_rename').val(), truemoney_pin_code: $('#truemoney_pin').val()}, function (data) {
                                    $("#return_varchar").html(data);
                                });

                            }
                        });

            }
        }
    </script>
<?php } else { ?>
    <script>
        function onInputChar()
        {
            if ($("#var_char_id").val() == "" || $("#new_char_rename").val() == "") {
                swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")
            } else {
                $.get("index.php", {char_id: $('#var_char_id').val(), char_rename: $('#new_char_rename').val()}, function (data) {
                    $("#return_varchar").html(data);
                });
            }
        }
    </script>
<?php } ?>

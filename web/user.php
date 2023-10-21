<?php
if (!isset($config)) {
    exit;
}
$hours = $_SESSION['TimePlayed'] / 3600;
$day_us = $hours / 24;
?>
<font>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> [ระบบสมาชิก] เวลาที่คุณเล่นทั้งหมด <?php echo $hours . ' ชั่วโมง'; ?> เล่นครั้งล่าสุดเมื่อ <?php echo date_format($_SESSION['lastgamedate'], 'Y-m-d H:i:s'); ?></h3>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table width = "100%" border = "0">
                            <tbody>
                                <tr>
                                    <td width = "50%">
                                        <table width = "100%" class = "table table-sm table-bordered col-md-7">
                                            <tbody>
                                                <tr>
                                                    <td colspan = "3">
                                                        <div align = "center">
                                                            <h4 class = "section-title"><i class = "fa fa-credit-card"></i> ยินดีต้อนรับ <small><?php echo $_SESSION['CustomerID'] . ' : ' . $_SESSION['email'];
?></small></h4>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center">Gamecoin (GC)</div>
                                                    </td>
                                                    <td>
                                                        <div align="center">Dollar (DL)</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="left">
                                                            <img src="img/gc.png" width="64" height="64"><?php echo $_SESSION['GamePoints']; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div align="left">
                                                            <img src="img/dr.png" width="64" height="64"><?php echo $_SESSION['GameDollars']; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="6" class="">Truemoney PIN <small>(รหัสเติมเงิน)</small> :  
                                                        <div id="return_fill" style="display: none;"></div>
                                                        <input name="pin" id="pin" type="text" maxlength="<?php echo $config['maxleng']['truemoney']; ?>" class="form-control form-control-lg">
                                                        <input name="email" id="email" type="hidden" value="<?php echo $_SESSION['email']; ?>">
                                                        <button class="btn btn-block btn-success" type="submit" onclick="javascript:doTruewallet();"> <i class="glyphicon glyphicon-shopping-cart"></i> Topup <small>ยืนยันการเติมเงิน</small></button></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p>&nbsp;</p>
                                        <br>
                                    </td>
                                    <?php
                                    foreach ($config['true_topup_promotion_day'] as $day_u => $var_u) {
                                        if (checkDayofTime() == $day_u) {
                                            if (isset($var_u)) {
                                                ?>
                                                <td width="50%">
                                                    <table width="100%" class="table table-sm table-bordered col-md-7">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3" class="text-center"><h4 class="section-title"><i class="glyphicon glyphicon-tasks"></i>  Refill information <small>รายละเอียดการเติมเงิน</small></h4>        </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="30%" class="text-center"><h5 class="section-title"><span style="color:#F00">True</span><span style="color:#F60">money</span></h5></th>
                                                                <th width="35%" class="text-center"><h5 class="section-title"><span style="color:#FFF">GC<?php
                                                                            if (isset($var_u) && $var_u > 1) {
                                                                                echo ' X' . $var_u;
                                                                            }
                                                                            ?></span></h5></th>
                                                                <?php if ($config['case_random']) { ?><th width="35%" class="text-center"><h5 class="section-title"><span style="color:#FFF">ตั่วสุ่มไอเท็ม</span></h5></th><?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 50฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['50'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['50']; ?></td><?php } ?>

                                                            </tr>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 90฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['90'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['90']; ?></td><?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 150฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['150'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['150']; ?></td><?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 300฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['300'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['300']; ?></td><?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 500฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['500'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['500']; ?></td><?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-bold font13"><span style="color:#F00">True</span><span style="color:#F60">money</span> 1,000฿</td>
                                                                <td class="text-center"><?php echo $config['true_topup_gc_amount']['1000'] * $var_u; ?> GC</td>
                                                                <?php if ($config['case_random']) { ?><td class="text-center"><?php echo $config['case_random_amount_topup']['1000']; ?></td><?php } ?>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <?php
                                            }
                                        }
                                        continue;
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                        <p>
                            <img src="img/Promotion11.png" width="100%">
                        </p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function doTruewallet()
    {
        if ($("#email").val() == "" || $("#pin").val() == "") {
            swal("แจ้งเตือน", "กรอกข้อมูลให้ครบ ", "info")
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
                            $.get("index.php", {g_email: $('#email').val(), truemoney_card: $('#pin').val()}, function (data) {
                                $("#return_fill").html(data);
                            });

                        }
                    });
        }
    }
</script>
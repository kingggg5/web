<header>
    <br>
    <div class="container">
        <center>
            <img src="img/edit.png" width="50%" height="30%"> 
        </center>
    </div>
    <br>
    <!--navbar-->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php"><i class="glyphicon glyphicon-home"></i>  หน้าหลัก</a></li>
                    <li><a href="https://www.facebook.com/groups/2017z.warz/" target="_blank"><i class="glyphicon glyphicon-hand-right"></i>  กลุ่มเฟสบุ้ค</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#promotion"><i class="glyphicon glyphicon-check"></i>  โปรโมชั่นล่าสุด</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#download"><i class="glyphicon glyphicon-cloud-download"></i>  ดาวน์โหลดเกมส์ </a></li>
                    <?php if ($config['Merchants_List']) { ?>
                        <li><a href="#" data-toggle="modal" data-target="#Merchants_List"><i class="glyphicon glyphicon-tower"></i> รายชื่อพ่อค้า </a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['CustomerID'])) { ?>
                        <?php if ($config['reward_system']) { ?>
                            <li><a href="reward.php"><i class="glyphicon glyphicon-check"></i>  แลกไอเท็มด้วยเวลาเล่น </a></li>
                        <?php } ?>
                        <?php if ($config['case_random']) { ?>
                            <li><a href="case.php"><i class="glyphicon glyphicon-certificate"></i>  สุ่มไอเท็ม </a></li>
                        <?php } ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><span class="glyphicon glyphicon-user"></span> ผู้ใช้<span class="caret"></span></a>
                            <ul class="dropdown-menu" aria-labelledby="download">
                                <?php if ($config['reputation_system']) { ?>
                                    <li><a href="#" data-toggle="modal" data-target="#reputation"><span class="glyphicon glyphicon-repeat"></span> รียศ</a></li>
                                <?php } ?>
                                <?php if ($config['map_tp']) { ?>
                                    <li><a href="#" data-toggle="modal" data-target="#map_tp"><span class="glyphicon glyphicon-eject"></span> ย้ายแมพตัวละคร</a></li>
                                <?php } ?>
                                <?php if ($config['topup_rename']) { ?>
                                    <li><a href="#" data-toggle="modal" data-target="#char_rename"><span class="glyphicon glyphicon-apple"></span> เปลี่ยนชื่อตัวละคร</a></li>
                                <?php } ?>
                                <li><a href="#" data-toggle="modal" data-target="#changepw"><span class="glyphicon glyphicon-user"></span> เปลี่ยนรหัสผ่าน</a></li>
                                <?php if ($config['reward_system']) { ?>
                                    <li><a href="#" data-toggle="modal" data-target="#log_reward"><span class="glyphicon glyphicon-usd"></span> ประวัติการแลก ไอเท็ม/GC</a></li>
                                <?php } ?>
                                <li><a href="#" data-toggle="modal" data-target="#log"><span class="glyphicon glyphicon-usd"></span> ประวัติการเติมเงิน</a></li>
                                <?php if ($config['case_random']) { ?>
                                    <li><a href="#" data-toggle="modal" data-target="#log_item"><span class="glyphicon glyphicon-random"></span> ประวัติการสุ่มไอเท็ม</a></li>
                                <?php } ?>
                                <?php
                                if ($config['reward_system']) {
                                    if ($_SESSION['IsDeveloper'] == $config['reward_backend_isDev']) {
                                        ?>
                                        <li><a href="backend_reward.php"><i class="glyphicon glyphicon-chevron-up"></i>  รับไอเท็มออนไลน์สำหรับแอดมิน </a></li>
                                        <?php
                                    }
                                }
                                ?>
                                <li><a href="index.php?do=logout"><span class="glyphicon glyphicon-off"></span> ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><i class="glyphicon glyphicon-globe"></i> คนออนไลน์ <?php echo getPlayerOnline(); ?> คน</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header><!--content-->
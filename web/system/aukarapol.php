<?php

if (!isset($config)) {
    exit;
}
if (isset($_GET['g_email']) || isset($_GET['truemoney_card'])) {

    $em = $_GET['g_email'];
    $tm = $_GET['truemoney_card'];
    if (empty($tm) || empty($em)) {
        exit('<script>sweetAlert( "แจ้งเตือน", "กรอกข้อมูลให้ครบ ", "info" )</script>');
    }
    /* if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      exit('<script>sweetAlert( "แจ้งเตือน", "รูปแบบการกรอก E-Mail ไม่ถูกต้อง ex@mailme.com", "warning" )</script>');
      } */
    if (strlen($tm) < $config['maxleng']['truemoney']) {
        exit('<script>sweetAlert( "แจ้งเตือน", "กรอกรหัสบัตรทรูมันนี่ 14 หลัก", "warning" )</script>');
    } elseif (strlen($tm) < $config['maxleng']['truemoney'] || !is_numeric($tm)) {
        exit('<script>sweetAlert( "แจ้งเตือน", "กรอกรหัสบัตรทรูมันนี่ 14 หลักและเป็นตัวเลขเท่านั้น", "warning" )</script>');
    } {
        if ($_SESSION['block_time'] >= (time() - 30)) {
            if (isset($_SESSION["wallet_check"])) {
                if ($_SESSION["wallet_check"] >= 5) {
                    exit('<script>sweetAlert( "แจ้งเตือน", "ระบบระงับการเติมเงิน, เนื่องจากคุณป้อนรหัสบัตรผิดเกิน 5 ครั้ง", "danger" )</script>');
                }
            } else {
                $_SESSION["wallet_check"] = 0;
            }
            exit('<script>sweetAlert( "แจ้งเตือน", "โปรดรอ 30 วินาทีแล้วลองใหม่อีกครั้งเนื่องจากคุณป้อนรหัสบัตรผิด 1 ครั้ง", "info" )</script>');
        } else {

            $wallet = new TrueWallet();

            $find_data_account_email = query($conn, "SELECT * FROM Accounts WHERE email = ?;", array($em));
            $count_email = sqlsrv_num_rows($find_data_account_email);

            $find_data_card = query($conn, "SELECT * FROM wz_TruemoneyTopup_TBL WHERE cardserial = ?;", array($_GET['truemoney_card']));
            $count_card = sqlsrv_num_rows($find_data_card);

            $row_cd = sqlsrv_fetch_array($find_data_account_email, SQLSRV_FETCH_ASSOC);

            if ($count_email <= 0) {
                exit('<script>sweetAlert( "แจ้งเตือน", "ไม่พบ email นี้: ' . $em . '", "error" )</script>');
            } else {
                if ($count_card >= 1) {
                    exit('<script>sweetAlert( "ล้มเหลว", "บัตรทรูมันนี่นี้ได้อยู่ในระบบของเราแล้ว", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                } else {
                    $chk_topup = $wallet->Topup($tm);
                    if ($chk_topup['isField']) {
                        exit('<script>sweetAlert( "ล้มเหลว", "ไม่สามารถเชื่อมต่อบัญชีทรูวอแลตที่จะตัดบัตรเข้าได้ หรือ ทรูวอแลตปิด API!", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                    } else {
                        if ($chk_topup['amount'] <= 0) {
                            $_SESSION['block_time'] = time();
                            $_SESSION["wallet_check"] ++;
                            exit('<script>sweetAlert( "ล้มเหลว", "ไม่พบรหัสบัตรทรูมันนี่นี้หรือถูกใช้งานแล้ว", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                        }
                        if ($chk_topup['amount'] > 0) {
                            if ($config['true_topup_item_more']) {
                                if (!sqlsrv_query($conn, "EXEC WZ_TOPUP2017 ?, ?, ?;", array($chk_topup['amount'], $row_cd["CustomerID"], $em))) {
                                    echo('<script>sweetAlert( "แจ้งเตือน", "ไม่สามารถส่งไอเท็มผ่านฟังชั่น WZ_TOPUP2017 ได้", "error" )');
                                }
                            }
                            if ($config['case_random']) {
                                if (isset($config['case_random_amount_topup'][$chk_topup['amount']])) {
                                    if (!query($conn, "UPDATE Accounts SET case_num = (case_num + ?) WHERE CustomerID = ?;", array($config['case_random_amount_topup'][$chk_topup['amount']], $row_cd["CustomerID"]))) {
                                        echo('<script>sweetAlert( "แจ้งเตือน", "ไม่สามารถให้ตั่วได้ โปลดลง case_num.sql", "error" )');
                                    }
                                }
                            }
                            if ($config['true_topup_gc']) {
                                foreach ($config['true_topup_promotion_day'] as $day_t => $var_t) {
                                    if (checkDayofTime() == $day_t) {
                                        if (isset($var_t)) {
                                            if (isset($config['true_topup_gc_amount'][$chk_topup['amount']])) {
                                                $get_gc = $config['true_topup_gc_amount'][$chk_topup['amount']];
                                                $static_gc = returnVar(number_format($get_gc));
                                                $static_var_t = returnVar($var_t);
                                                $get_gc_bonut = returnVar($static_gc * $static_var_t);
                                                $adding_gc_topup = query($conn, "UPDATE UsersData SET GamePoints = (GamePoints + ?) WHERE CustomerID = ?;", array($get_gc_bonut, $_SESSION['CustomerID']));
                                                if (!$adding_gc_topup) {
                                                    echo('<script>sweetAlert( "แจ้งเตือน", "ระบบไม่สามารถส่ง GC ได้", "error" )');
                                                }
                                                $insert_logs_topup = query($conn, "INSERT INTO [wz_TruemoneyTopup_TBL]([email],[cardserial],[amount],[timer]) VALUES (?, ?, ?, ?);", array($em, $tm, $chk_topup['amount'], GetTime()));
                                                if ($adding_gc_topup && $insert_logs_topup) {
                                                    exit('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จ: ' . $chk_topup['amount'] . ' บาท ได้รับ ' . $get_gc_bonut . ' GC และไอเทมโปรโมชั่น", "success" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                                                    continue;
                                                } else {
                                                    echo('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จแต่ ระบบไม่สามารถใช้งานได้ส่งเพียงไอเท็มได้อย่างเดียว", "error" )');
                                                    continue;
                                                }
                                            } else {
                                                echo('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จแต่ ระบบไม่สามารถใช้งานได้ส่งเพียงไอเท็มได้อย่างเดียว", "error" )');
                                                continue;
                                            }
                                        } else {
                                            echo('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จแต่ ระบบไม่สามารถใช้งานได้ส่งเพียงไอเท็มได้อย่างเดียว โปรดตั้งค่า config true_topup_promotion_day ให้ถูกต้อง", "error" )');
                                            continue;
                                        }
                                    } {
                                        continue;
                                    }
                                }
                            } else {
                                if (query($conn, "INSERT INTO [wz_TruemoneyTopup_TBL]([email],[cardserial],[amount],[timer]) VALUES (?, ?, ?, ?);", array($em, $tm, $chk_topup['amount'], GetTime()))) {
                                    exit('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จ: ' . $chk_topup['amount'] . ' บาท ได้รับ ' . $get_gc_bonut . ' GC และไอเทมโปรโมชั่น", "success" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
if ($config['reputation_system']) {
    if (isset($_GET['char_id_reputation'])) {
        if (empty($_GET['char_id_reputation'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "ป้อนข้อมูลให้ครบ!", "info")</script>');
        }
        $find_data_account = query($conn, "SELECT * FROM UsersData WHERE CustomerID = ?;", array($_SESSION['CustomerID']));
        $output_data_account = sqlsrv_fetch_array($find_data_account, SQLSRV_FETCH_ASSOC);
        if ($output_data_account['GamePoints'] < $config['reputation_price']) {
            exit('<script>sweetAlert("แจ้งเตือน", "คุณมีจำนวน Gamecoin(GC) ไม่เพียงพอ ขาดอีก ' . $output_data_account['GamePoints'] - $config['reputation_price'] . ' GC!", "info")</script>');
        } else {
            $UDPC_R = query($conn, "UPDATE UsersData SET GamePoints = ? WHERE CustomerID = ?;", array($output_data_account['GamePoints'] - $config['reputation_price'], $_SESSION['CustomerID']));
            $UCPP_R = query($conn, "UPDATE UsersChars SET Reputation = ? WHERE CharID = ?;", array($config['reputation_set'], $_GET['char_id_reputation']));
            if ($UDPC_R && $UCPP_R) {
                exit('<script>sweetAlert("แจ้งเตือน", "รียศสำเร็จ: ' . $config['reputation_set'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            }
        }
    }
}
if ($config['map_tp']) {
    if (isset($_GET['char_id_map']) || isset($_GET['map_pos'])) {
        if (empty($_GET['char_id_map']) || empty($_GET['map_pos'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "ป้อนข้อมูลให้ครบ!", "info")</script>');
        }
        $find_data_account = query($conn, "SELECT * FROM UsersData WHERE CustomerID = ?;", array($_SESSION['CustomerID']));
        $output_data_account = sqlsrv_fetch_array($find_data_account, SQLSRV_FETCH_ASSOC);
        if ($output_data_account['GamePoints'] < $config['map_tp_price']) {
            exit('<script>sweetAlert("แจ้งเตือน", "คุณมีจำนวน Gamecoin(GC) ไม่เพียงพอ ขาดอีก ' . $output_data_account['GamePoints'] - $config['map_tp_price'] . ' GC!", "info")</script>');
        } else {
            $UDPC = query($conn, "UPDATE UsersData SET GamePoints = ? WHERE CustomerID = ?;", array($output_data_account['GamePoints'] - $config['map_tp_price'], $_SESSION['CustomerID']));
            $UCPP = query($conn, "UPDATE UsersChars SET GamePos = ? WHERE CharID = ?;", array($_GET['map_pos'], $_GET['char_id_map']));
            if ($UDPC && $UCPP) {
                exit('<script>sweetAlert("แจ้งเตือน", "ย้ายสำเร็จตอนนี้ตัวละครนี้อยู่ที่ ' . $_GET['map_pos'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            }
        }
    }
}
if ($config['topup_rename_price'] > 0) {
    if (isset($_GET['char_id']) || isset($_GET['char_rename']) || isset($_GET['truemoney_pin_code'])) {
        if (empty($_GET['char_id']) || empty($_GET['char_rename']) || empty($_GET['truemoney_pin_code'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")</script>');
        }
        if (strlen($_GET['char_rename']) >= $config['char_rename_maxleng'] || strlen($_GET['truemoney_pin_code']) < $config['maxleng']['truemoney']) {
            exit('<script>sweetAlert( "แจ้งเตือน", "ป้อนข้อมูลให้ครบถ้วนและถูกต้อง ชื่อตัวละครใหม่ไม่เกิน ' . $config['char_rename_maxleng'] . ' และรหัสบัตรทรูมันนี่ ' . $config['maxleng']['truemoney'] . '", "warning" )</script>');
        }
        if (strlen($_GET['truemoney_pin_code']) < $config['maxleng']['truemoney'] || !is_numeric($_GET['truemoney_pin_code'])) {
            exit('<script>sweetAlert( "ล้มเหลว", "ป้อนรหัสบัตรทรูมันนี่ ' . $config['maxleng']['truemoney'] . ' หลักและต้องเป็นตัวเลขเท่านั้น", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
        }
        if ($config['char_rename_language'] == 1) {
            if (!is_str($_GET['char_rename'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "การตั้งชื่อตัวละครต้องเป็น a-zA-Z0-9_ เท่านั้น!", "warning")</script>');
            }
        } elseif ($config['char_rename_language'] == 2) {
            if (!is_str_th($_GET['char_rename'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "การตั้งชื่อตัวละครต้องเป็น 0-9A-Zก-ฮ เท่านั้น!", "warning")</script>');
            }
        } {

            if ($_SESSION['block_time'] >= (time() - 30)) {
                if (isset($_SESSION["wallet_check"])) {
                    if ($_SESSION["wallet_check"] >= 5) {
                        exit('<script>sweetAlert( "แจ้งเตือน", "ระบบระงับการเติมเงิน, เนื่องจากคุณป้อนรหัสบัตรผิดเกิน 5 ครั้ง", "danger" )</script>');
                    }
                } else {
                    $_SESSION["wallet_check"] = 0;
                }
                exit('<script>sweetAlert( "แจ้งเตือน", "โปรดรอ 30 วินาทีแล้วลองใหม่อีกครั้งเนื่องจากคุณป้อนรหัสบัตรผิด 1 ครั้ง", "info" )</script>');
            } else {

                $wallet = new TrueWallet();

                $find_data_char = query($conn, "SELECT * FROM UsersChars WHERE CharID = ? AND Gamertag = ?;", array($_GET['char_id'], $_GET['char_rename']));
                $output_data_char = sqlsrv_fetch_array($find_data_char, SQLSRV_FETCH_ASSOC);

                $find_data_card = query($conn, "SELECT * FROM wz_TruemoneyTopup_TBL WHERE cardserial = ?;", array($_GET['truemoney_pin_code']));
                $count_card = sqlsrv_num_rows($find_data_card);

                if ($output_data_char['Gamertag'] == $_GET['char_rename']) {
                    exit('<script>sweetAlert( "แจ้งเตือน", "ไม่สามารถตั้งชื่อตัวละครเดิมได้: ' . $_GET['char_rename'] . '", "warning" )</script>');
                } else {

                    if ($count_card >= 1) {
                        exit('<script>sweetAlert( "ล้มเหลว", "บัตรทรูมันนี่นี้ได้อยู่ในระบบของเราแล้ว", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                    } else {
                        $chk_topup = $wallet->Topup($_GET['truemoney_pin_code']);
                        if ($chk_topup['isField']) {
                            exit('<script>sweetAlert( "ล้มเหลว", "ไม่สามารถเชื่อมต่อบัญชีทรูวอแลตที่จะตัดบัตรเข้าได้ หรือ ทรูวอแลตปิด API!", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                        } else {
                            if ($chk_topup['amount'] <= 0) {
                                $_SESSION['block_time'] = time();
                                $_SESSION["wallet_check"] ++;
                                exit('<script>sweetAlert( "ล้มเหลว", "ไม่พบรหัสบัตรทรูมันนี่นี้หรือถูกใช้งานแล้ว", "error" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                            }
                            if ($chk_topup['amount'] >= $config['topup_rename_price']) {
                                $UDCS = query($conn, "UPDATE UsersChars SET Gamertag = ? WHERE CharID = ?;", array($_GET['char_rename'], $_GET['char_id']));
                                $TMADD = query($conn, "INSERT INTO [wz_TruemoneyTopup_TBL]([email],[cardserial],[amount],[timer]) VALUES (?, ?, ?, ?);", array($em, $tm, $chk_topup['amount'], GetTime()));
                                if ($UDCS && $TMADD) {
                                    exit('<script>sweetAlert( "แจ้งเตือน", "เติมเงินสำเร็จ: ' . $chk_topup['amount'] . ' บาท ชื่อใหม่ของตัวละครคุณคือ, ' . $_GET['char_rename'] . '", "success" ).then((value) => {
  $(location).attr("href","index.php");
});</script>');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    if (isset($_GET['char_id']) || isset($_GET['char_rename'])) {
        if (empty($_GET['char_id']) || empty($_GET['char_rename'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")</script>');
        }
        if (strlen($_GET['char_rename']) >= $config['char_rename_maxleng']) {
            exit('<script>sweetAlert( "แจ้งเตือน", "ป้อนชื่อตัวละครใหม่ไม่เกิน ' . $config['char_rename_maxleng'] . '", "warning" )</script>');
        }
        if ($config['char_rename_language'] == 1) {
            if (!is_str($_GET['char_rename'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "การตั้งชื่อตัวละครต้องเป็น a-zA-Z0-9_ เท่านั้น!", "warning")</script>');
            }
        } elseif ($config['char_rename_language'] == 2) {
            if (!is_str_th($_GET['char_rename'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "การตั้งชื่อตัวละครต้องเป็น 0-9A-Zก-ฮ เท่านั้น!", "warning")</script>');
            }
        } {
            $find_data_char = query($conn, "SELECT * FROM UsersChars WHERE CharID = ? AND Gamertag = ?;", array($_GET['char_id'], $_GET['char_rename']));
            $output_data_char = sqlsrv_fetch_array($find_data_char, SQLSRV_FETCH_ASSOC);
            if ($output_data_char['Gamertag'] == $_GET['char_rename']) {
                exit('<script>sweetAlert( "แจ้งเตือน", "ไม่สามารถตั้งชื่อตัวละครเดิมได้: ' . $_GET['char_rename'] . '", "warning" )</script>');
            } else {
                if (query($conn, "UPDATE UsersChars SET Gamertag = ? WHERE CharID = ?;", array($_GET['char_rename'], $_GET['char_id']))) {
                    exit('<script>sweetAlert("แจ้งเตือน", "ชื่อใหม่ของตัวละครคุณคือ: ' . $_GET['char_rename'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
                }
            }
        }
    }
}

if (isset($_GET['em']) || isset($_GET['pw']) || isset($_GET['tc'])) {
    if ($config['captcha_login']) {
        if (empty($_GET['em']) || empty($_GET['pw']) || empty($_GET['tc'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")</script>');
        }
        if ($_SESSION['thaicaptcha_md5'] != md5($_GET['tc'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "คุณไม่ใช่มนุษย์?", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
        }
    } else {
        if (empty($_GET['em']) || empty($_GET['pw'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "กรอกข้อมูลให้ครบ!", "info")</script>');
        }
    }
    if ($config['format_email_login']) {
        if (!filter_var($_GET['em'], FILTER_VALIDATE_EMAIL)) {
            exit('<script>sweetAlert( "แจ้งเตือน", "รูปแบบการกรอก E-Mail ไม่ถูกต้อง ex@mailme.com", "warning" )</script>');
        }
    }
    if (!is_str($_GET['pw'])) {
        exit('<script>sweetAlert( "แจ้งเตือน", "การกรอกรหัสผ่านต้องเป็น a-zA-Z0-9_ เท่านั้น", "warning" )</script>');
    } {
        if (Login($_GET['em'], $_GET['pw'])) {
            exit('<script>sweetAlert("แจ้งเตือน", "ยินดีต้อนรับ: ' . $_SESSION['email'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
        } else {
            exit('<script>sweetAlert("แจ้งเตือน", "อีเมลหรือรหัสผ่านผิด", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
        }
    }
}
if (isset($_GET['change_pwold']) || isset($_GET['change_pw']) || isset($_GET['change_pwc'])) {

    if ($config['login_md5']) {
        $find_data_all_account_email = query($conn, "SELECT * FROM Accounts WHERE CustomerID = ? AND MD5Password = ?;", array($_SESSION['CustomerID'], md5($config['login_md5_salt'] . $_GET['change_pwold'])));
    } else {
        $find_data_all_account_email = query($conn, "SELECT * FROM Accounts WHERE CustomerID = ? AND MD5Password = ?;", array($_SESSION['CustomerID'], $_GET['change_pwold']));
    }
    $row = sqlsrv_fetch_array($find_data_all_account_email, SQLSRV_FETCH_ASSOC);
    if ($config['login_md5']) {
        if ($row['MD5Password'] == md5($config['login_md5_salt'] . $_GET['change_pwold'])) {
            if ($_GET['change_pwold'] == $_GET['change_pw'] || $_GET['change_pwold'] == $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "ไม่สามารถป้อนข้อมูลซ้ำได้", "warning")</script>');
            }
            if (!is_str($_GET['change_pwold']) || !is_str($_GET['change_pw']) || !is_str($_GET['change_pwc'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "ป้อนข้อมูลเป็น a-zA-Z0-9_ เท่านั้น", "warning")</script>');
            }
            if ($_GET['change_pw'] != $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "รหัสผ่านไม่ตรงกัน", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            }
            if ($row['MD5Password'] == $_GET['change_pw'] || $row['MD5Password'] == $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "ไม่สามารถตั้งรหัสซ้ำได้", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            } else {
                if (query($conn, "UPDATE Accounts SET MD5Password = ? WHERE CustomerID = ?;", array(md5($config['login_md5_salt'] . $_GET['change_pw']), $_SESSION['CustomerID']))) {
                    exit('<script>sweetAlert("แจ้งเตือน", "เปลี่ยนรหัสผ่านสำเร็จ: ' . $_GET['change_pw'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php?do=logout");
                                  });</script>');
                } else {
                    exit(rdr("index.php"));
                }
            }
        } else {
            exit('<script>sweetAlert("แจ้งเตือน", "รหัสผ่านเก่าผิด", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
        }
    } else {
        if ($row['MD5Password'] == $_GET['change_pwold']) {
            if ($_GET['change_pwold'] == $_GET['change_pw'] || $_GET['change_pwold'] == $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "ไม่สามารถป้อนข้อมูลซ้ำได้", "warning")</script>');
            }
            if (!is_str($_GET['change_pwold']) || !is_str($_GET['change_pw']) || !is_str($_GET['change_pwc'])) {
                exit('<script>sweetAlert("แจ้งเตือน", "ป้อนข้อมูลเป็น a-zA-Z0-9_ เท่านั้น", "warning")</script>');
            }
            if ($_GET['change_pw'] != $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "รหัสผ่านไม่ตรงกัน", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            }
            if ($row['MD5Password'] == $_GET['change_pw'] || $row['MD5Password'] == $_GET['change_pwc']) {
                exit('<script>sweetAlert("แจ้งเตือน", "ไม่สามารถตั้งรหัสซ้ำได้", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
            } else {
                if (query($conn, "UPDATE Accounts SET MD5Password = ? WHERE CustomerID = ?;", array($_GET['change_pw'], $_SESSION['CustomerID']))) {
                    exit('<script>sweetAlert("แจ้งเตือน", "เปลี่ยนรหัสผ่านสำเร็จ: ' . $_GET['change_pw'] . '", "success").then((value) => {
                                  $(location).attr("href","index.php?do=logout");
                                  });</script>');
                } else {
                    exit(rdr("index.php"));
                }
            }
        } else {
            exit('<script>sweetAlert("แจ้งเตือน", "รหัสผ่านเก่าผิด", "error").then((value) => {
                                  $(location).attr("href","index.php");
                                  });</script>');
        }
    }
}
<?php

session_start();
include_once dirname(__FILE__) . '/../config.php';
include_once dirname(__FILE__) . '/db.php';
CheckUserUpdate();
checkSQLServerConnect();

function checkSQLServerConnect() {
    global $conn;
    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }
}

function array_random_assoc($arr, $num = 1) {
    $keys = array_keys($arr);
    shuffle($keys);

    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[$keys[$i]] = $arr[$keys[$i]];
    }
    return $r;
}

function randdy($var1, $var2) {
    $r = rand($var1, $var2);
    return $r;
}

function RandomGrade_20() {
    $min = 1;
    $max = 20;
    $r = rand($min, $max);
    if ($r >= 10) {
        $r = rand(5, $max);
    }
    if ($r >= 15) {
        $r = rand($min, $max);
    }
    return $r;
}

function returnVar($var) {
    return $var;
}

function checkDayofTime() {
    return date("D", strtotime(date("Y-M-d H:i:s", time())));
}

function CheckUserUpdate() {
    global $conn;
    $ac = query($conn, "SELECT * FROM Accounts WHERE email = ?;", array($_SESSION['email']));
    if ($ac) {
        $out_ac = sqlsrv_fetch_array($ac, SQLSRV_FETCH_ASSOC);
    }
    $ud = query($conn, "SELECT * FROM UsersData WHERE CustomerID = ?;", array($out_ac['CustomerID']));
    if ($ud) {
        $out_ud = sqlsrv_fetch_array($ud, SQLSRV_FETCH_ASSOC);
    }
    if (isset($_SESSION['email']) || isset($_SESSION['CustomerID']) || isset($_SESSION['TimePlayed']) || isset($_SESSION['GamePoints']) || isset($_SESSION['GameDollars']) || isset($_SESSION['lastgamedate']) || isset($_SESSION['IsDeveloper']) || isset($_SESSION['case_num'])) {
        $_SESSION['email'] = $out_ac['email'];
        $_SESSION['CustomerID'] = $out_ac['CustomerID'];
        $_SESSION['TimePlayed'] = $out_ud['TimePlayed'];
        $_SESSION['GamePoints'] = $out_ud['GamePoints'];
        $_SESSION['GameDollars'] = $out_ud['GameDollars'];
        $_SESSION['lastgamedate'] = $out_ud['lastgamedate'];
        $_SESSION['IsDeveloper'] = $out_ud['IsDeveloper'];
        $_SESSION['case_num'] = $out_ac['case_num'];
    } else {
        unset($_SESSION['email']);
        unset($_SESSION['CustomerID']);
        unset($_SESSION['TimePlayed']);
        unset($_SESSION['GamePoints']);
        unset($_SESSION['GameDollars']);
        unset($_SESSION['lastgamedate']);
        unset($_SESSION['IsDeveloper']);
        unset($_SESSION['case_num']);
    }
}

function Login($email, $pass) {
    global $config;
    global $conn;
    $email = clean($email);
    $passwd = clean($pass);

    if ($config['login_md5']) {
        $acl = query($conn, "SELECT * FROM Accounts WHERE email = ? AND MD5Password = ?;", array($email, md5($config['login_md5_salt'] . $passwd)));
        if ($acl) {
            $out_acl = sqlsrv_fetch_array($acl, SQLSRV_FETCH_ASSOC);
        }
    } else {
        $acl = query($conn, "SELECT * FROM Accounts WHERE email = ? AND MD5Password = ?;", array($email, $passwd));
        if ($acl) {
            $out_acl = sqlsrv_fetch_array($acl, SQLSRV_FETCH_ASSOC);
        }
    }
    $udl = query($conn, "SELECT * FROM UsersData WHERE CustomerID = ?;", array($out_acl['CustomerID']));
    if ($udl) {
        $out_udl = sqlsrv_fetch_array($udl, SQLSRV_FETCH_ASSOC);
    }

    if ($config['login_md5']) {
        if ($out_acl['MD5Password'] == md5($config['login_md5_salt'] . $passwd)) {
            $_SESSION['email'] = $out_acl['email'];
            $_SESSION['CustomerID'] = $out_acl['CustomerID'];
            $_SESSION['TimePlayed'] = $out_udl['TimePlayed'];
            $_SESSION['GamePoints'] = $out_udl['GamePoints'];
            $_SESSION['GameDollars'] = $out_udl['GameDollars'];
            $_SESSION['lastgamedate'] = $out_udl['lastgamedate'];
            $_SESSION['IsDeveloper'] = $out_udl['IsDeveloper'];
            $_SESSION['case_num'] = $out_acl['case_num'];
            return true;
        } else {
            return false;
        }
    } else {
        if ($out_acl['MD5Password'] == $passwd) {
            $_SESSION['email'] = $out_acl['email'];
            $_SESSION['CustomerID'] = $out_acl['CustomerID'];
            $_SESSION['TimePlayed'] = $out_udl['TimePlayed'];
            $_SESSION['GamePoints'] = $out_udl['GamePoints'];
            $_SESSION['GameDollars'] = $out_udl['GameDollars'];
            $_SESSION['lastgamedate'] = $out_udl['lastgamedate'];
            $_SESSION['IsDeveloper'] = $out_udl['IsDeveloper'];
            $_SESSION['case_num'] = $out_acl['case_num'];
            return true;
        } else {
            return false;
        }
    }
}

function getPlayerOnline() {
    global $conn;
    $find_online = query($conn, "SELECT uc.LastUpdateDate, uc.Gamertag, uc.CustomerID, ud.CustomerID, ud.IsDeveloper, ud.AccountType From UsersChars as uc JOIN UsersData as ud ON uc.CustomerID = ud.CustomerID      
WHERE DATEDIFF(MINUTE, uc.LastUpdateDate, GETDATE()) <= 1");
    $count_p = sqlsrv_num_rows($find_online);
    if ($find_online) {
        return $count_p;
    } else {
        return null;
    }
}

function rdr($url) {
    echo '<meta http-equiv="refresh" content="0;URL=' . $url . '">';
    echo '<script>location.replace("' . $url . '")</script>';
}

function is_str($txt) {
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $txt)) {
        return false;
    } else {
        return true;
    }
}

function is_str_th($txt) {
    if (!preg_match('/\p{Thai}/u', $txt)) { //preg_match("/^[a-zA-Z0-9 \s]+$/", $name));
        return false;
    } else {
        return true;
    }
}

function clean($value) {
    $value = htmlspecialchars(strip_tags($value));
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    $value = str_replace("'", "''", $value);
    $value = str_replace(";", "", $value);
    $value = str_replace("=", "", $value);
    $value = str_replace(",", "", $value);
    $value = str_replace(" ", "", $value);
    return $value;
}

function GetTime() {
    return date("Y-m-d H:i:s", time());
}

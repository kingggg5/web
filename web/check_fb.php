<?php

include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/system/main.php';
include_once dirname(__FILE__) . '/system/db.php';

if ($config['fb']['regis']) {
    if (isset($_POST)) {
        $strPicture = "https://graph.facebook.com/" . $_POST["hdnFbID"] . "/picture?type=large";
        $strLink = "https://www.facebook.com/app_scoped_user_id/" . $_POST["hdnFbID"] . "/";
        $_SESSION["fb_img"] = $strPicture;
        $_SESSION["fb_link"] = $strLink;
        $_SESSION["fb_name"] = $_POST["hdnName"];
        $_SESSION["fb_email"] = $_POST["hdnEmail"];
        $_SESSION["fb_id"] = $_POST["hdnFbID"];
        exit(rdr("index.php?form=regis_fb"));
    } else {
        exit(rdr("index.php"));
    }
} else {
    exit(rdr("index.php"));
}
?>

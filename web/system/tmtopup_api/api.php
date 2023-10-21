<?php

include_once dirname(__FILE__) . '/../config.php';
include_once dirname(__FILE__) . '/../system/db.php';
include_once dirname(__FILE__) . '/../system/main.php';

require_once dirname(__FILE__) . '/AES.php';
define('API_PASSKEY', $config['tmtopup_api_passkey']);

if ($_SERVER['REMOTE_ADDR'] == '203.146.127.115' && isset($_GET['request'])) {
    $aes = new Crypt_AES();
    $aes->setKey(API_PASSKEY);
    
    $_GET['request'] = base64_decode(strtr($_GET['request'], '-_,', '+/='));
    $_GET['request'] = $aes->decrypt($_GET['request']);
    
    if ($_GET['request']) {
        parse_str($_GET['request'], $request);
        $request['Ref1'] = base64_decode($request['Ref1']);

        $email = trim($request['Ref1']);
        $card_amount = $request['cashcard_amount'];

        /* Code | Begin $request['cashcard_amount'] = amount, $email = Ref1 */
        //onclick="submit_tmnc()"

        echo 'SUCCEED';
    } else {
        echo 'ERROR|INVALID_PASSKEY';
    }
} else {
    echo 'ERROR|ACCESS_DENIED';
}
?>
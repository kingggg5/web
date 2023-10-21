<?php

if (!isset($config)) {
    exit;
}

$connectionInfo = array("Database" => "" . $config['db_name'] . "", "UID" => "" . $config['mssql_user'] . "", "PWD" => "" . $config['mssql_password'] . "", "CharacterSet" => 'UTF-8');
$conn = sqlsrv_connect($config['mssql_host'], $connectionInfo);
return $conn;

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

function query($conn, $tsql, $q = array()) {
    $stmt = sqlsrv_query($conn, $tsql, $q, array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if (!$stmt) {
        echo "exec failed.\n";
        die(print_r(sqlsrv_errors(), true));
    }
    return $stmt;
}

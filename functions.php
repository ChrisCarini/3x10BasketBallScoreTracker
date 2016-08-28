<?php
require_once("config.php");

function getMySqlPDOObject($host = "", $user = "", $pass = "", $dbName = "") {
    if($host == "") {
        global $mySqlHost;
        $host = $mySqlHost;
    }
    if($user == "") {
        global $mySqlUser;
        $user = $mySqlUser;
    }
    if($pass == "") {
        global $mySqlPass;
        $pass = $mySqlPass;
    }
    if($dbName == "") {
        global $mySqlDatabaseName;
        $dbName = $mySqlDatabaseName;
    }
    /* Connect to MySQL */
    
    $db = new PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8mb4', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    return $db;
}
?>
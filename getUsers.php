<?php
require_once("functions.php");

function fetchUsers() {
    $mysqlPDO = getMySqlPDOObject();
    $returnVal = "";
    foreach($mysqlPDO->query('SELECT DISTINCT `name` FROM `users`;') as $row) {
        $returnVal .= $row['name'].",";
    }
    return rtrim($returnVal, ",");
}

echo fetchUsers();

?>
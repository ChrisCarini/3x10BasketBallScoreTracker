<?php
require_once("functions.php");

function fetchLifetimeStats() {
    $mysqlPDO = getMySqlPDOObject();
    
    $returnVal = "";
    foreach($mysqlPDO->query('SELECT DISTINCT *, SUM(totalPoints) AS total, 30*COUNT(*) AS totalAttempts, SUM(totalPoints)/(30*COUNT(*)) as percent FROM `stats` GROUP BY `shooterName` ORDER BY `percent` DESC;') as $row) {
        //$statResult = mysql_query('SELECT *, COUNT(*) AS "count" FROM `stats` WHERE `shooterName`="'.$row['name'].'" GROUP BY `shooterName`', $mysqlLink);
        // $statResult = mysql_query('SELECT * FROM `stats` WHERE `shooterName`="'.$row['name'].'"', $mysqlLink);
        // $num_rows = (30*mysql_num_rows($statResult));
        // $userTotalPoints = 0;
        // while($statsRow = mysql_fetch_array($statResult))
        // {
            // $userTotalPoints += $statsRow['totalPoints'];
        // }
        $returnVal .= $row['shooterName']."|".$row['total']."|".$row['totalAttempts'].",";
    }
    return rtrim($returnVal, ",");
}

echo fetchLifetimeStats();

?>
<?php
$databaseName = "this_is_your_mysql_database_name";

function connectToMySQL($dbName = "")
{
    /* Connect to MySQL */
    $user = 'this_is_your_mysql_username';
    $pass = 'this_is_your_mysql_password';
    // Dreamhost Database
    $host = 'this_is_a_long_host_name_com';
    if($dbName == "")
    {
        $db = 'this_is_your_mysql_database_name';
    }
    else
    {
        $db = $dbName;
    }
    $mysqlLink = mysql_connect($host,$user,$pass) or die ('Error connecting to mysql');
    @mysql_select_db($db);
    return $mysqlLink;
}

function fetchLifetimeStats()
{
    $mysqlLink = connectToMySQL($databaseName);
    $result = mysql_query('SELECT DISTINCT *, SUM(totalPoints) AS total, 30*COUNT(*) AS totalAttempts, SUM(totalPoints)/(30*COUNT(*)) as percent FROM `stats` GROUP BY `shooterName` ORDER BY `percent` DESC', $mysqlLink);
	$returnVal = "";
	while($row = mysql_fetch_array($result))
	{
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
    mysql_close($mysqlLink);
	return rtrim($returnVal, ",");
}

echo fetchLifetimeStats();

?>
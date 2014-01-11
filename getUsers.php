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

function fetchUsers()
{
    $mysqlLink = connectToMySQL($databaseName);
    $result = mysql_query('SELECT DISTINCT `name` FROM `users`', $mysqlLink);
    mysql_close($mysqlLink);
	$returnVal = "";
	while($row = mysql_fetch_array($result))
	{
		$returnVal .= $row['name'].",";
    }
	return rtrim($returnVal, ",");
}

echo fetchUsers();

?>
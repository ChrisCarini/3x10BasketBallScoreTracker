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

function userExists($name,$mysqlLink)
{
    $result = mysql_query('SELECT * FROM  `users` WHERE  `name` =  "'.$name.'"', $mysqlLink);
    $num_rows = mysql_num_rows($result);
    if($num_rows > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function addUser($name,$mysqlLink)
{
	$sql = "INSERT INTO `users` (`id`, `name`, `dateadded`) VALUES ('', '".$name."', NOW())";
	if(mysql_query($sql,$mysqlLink))
	{
		return;
	}
	else
	{
		$err = "Error Occured Inserting User(".$name.") into Database; exiting";
		echo $err;
		exit($err);
	}
}
function submitScore($shooterName,$scorerName,$round1Score,$round2Score,$round3Score,$mysqlLink)
{
	$sql = "INSERT INTO `stats` (`id`, `shooterName`, `scorerName`, `round1Points`, `round2Points`, `round3Points`, `totalPoints`, `dateadded`) VALUES ('', '".$shooterName."', '".$scorerName."', '".$round1Score."', '".$round2Score."', '".$round3Score."', '".($round1Score+$round2Score+$round3Score)."', NOW())";
	if(mysql_query($sql,$mysqlLink))
	{
		return "Score Submitted Successfully!!";
	}
	else
	{
		$err = "Error Occured Inserting Score into Database; exiting";
		echo $err;
		exit($err);
	}
}

$shooterName = preg_replace('/\s+/', '', $_POST['shooterSelect']); // Remove all whitespace from POST var
$scorerName  = preg_replace('/\s+/', '', $_POST['scorerSelect']);  // Remove all whitespace from POST var
$round1Score = intval($_POST['round1Select']);
$round2Score = intval($_POST['round2Select']);
$round3Score = intval($_POST['round3Select']);


$mysqlLink = connectToMySQL($databaseName);

// Check Inputs, Respond w/ Error if appropiate
foreach(array($shooterName,$scorerName) as $key=>$personName)
{
	if(!userExists($personName,$mysqlLink))
	{
		addUser($personName,$mysqlLink);
	}
}
foreach(array($round1Score,$round2Score,$round3Score) as $key=>$roundScore)
{
	if (!is_numeric($roundScore) || ($roundScore < 0 || $roundScore > 10))
	{
		$err = "Invalid Score: ".$roundScore."; please enter a number between 0 and 10";
		echo $err;
		exit($err);
	}
}

// Inputs Good, Insert into Database
echo $shooterName."-".$scorerName."-".$round1Score.":".$round2Score.":".$round3Score;
echo submitScore($shooterName,$scorerName,$round1Score,$round2Score,$round3Score,$mysqlLink);

?>
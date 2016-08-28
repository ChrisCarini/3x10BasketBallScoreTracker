<?php
require_once("functions.php");

function userExists($name,$mysqlPDO) {
    $stmt = $mysqlPDO->prepare('SELECT * FROM employees WHERE name = :name;');
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    
    $num_rows = $stmt->rowCount();
    
    if($num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function addUser($name,$mysqlPDO) {    
    $stmt = $mysqlPDO->prepare("INSERT INTO `users` (`id`, `name`, `dateadded`) VALUES ('', ':name', NOW());");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $insert_success = $stmt->execute();
    if($insert_success) {
        return;
    } else {
        $err = "Error Occured Inserting User(".$name.") into Database; exiting";
        echo $err;
        exit($err);
    }
}

function submitScore($shooterName,$scorerName,$round1Score,$round2Score,$round3Score,$mysqlPDO) {
    $stmt = $mysqlPDO->prepare("INSERT INTO `stats` (`id`, `shooterName`, `scorerName`, `round1Points`, `round2Points`, `round3Points`, `totalPoints`, `dateadded`) VALUES ('', ':shooterName', ':scorerName', ':round1Score', ':round2Score', ':round3Score', ':totalPoints', NOW());");
    $stmt->bindValue(':shooterName', $shooterName, PDO::PARAM_STR);
    $stmt->bindValue(':scorerName', $scorerName, PDO::PARAM_STR);
    $stmt->bindValue(':round1Score', $round1Score, PDO::PARAM_INT);
    $stmt->bindValue(':round2Score', $round2Score, PDO::PARAM_INT);
    $stmt->bindValue(':round3Score', $round3Score, PDO::PARAM_INT);
    $totalPoints = ($round1Score+$round2Score+$round3Score);
    $stmt->bindValue(':totalPoints', $totalPoints, PDO::PARAM_INT);
    $insert_success = $stmt->execute();
    
    if($insert_success) {
        return "Score Submitted Successfully!!";
    } else {
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


$mysqlPDO = getMySqlPDOObject();

// Check Inputs, Respond w/ Error if appropiate
foreach(array($shooterName,$scorerName) as $key=>$personName)
{
    if(!userExists($personName,$mysqlPDO))
    {
        addUser($personName,$mysqlPDO);
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
echo submitScore($shooterName,$scorerName,$round1Score,$round2Score,$round3Score,$mysqlPDO);

?>
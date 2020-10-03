<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$mysqli->set_charset("utf8");
$StartDateInterval=date_create("2015-11-01");
$EndDateInterval=date_create("2015-12-31");
date_add($StartDateInterval,date_interval_create_from_date_string("2 months"));
echo date_format($StartDateInterval,"Y-m-d");
date_add($EndDateInterval,date_interval_create_from_date_string("2 months - 1 day"));
echo date_format($EndDateInterval,"Y-m-d");
$StartDateInterval= $StartDateInterval->format('Y-m-d');
$EndDateInterval= $EndDateInterval->format('Y-m-d');
$sql = "SELECT Distinct Fullname FROM players join match_results WHERE (players.Player_Namecode = match_results.Player1_Namecode OR players.Player_Namecode = match_results.Player2_Namecode) AND match_results.RoundDate >= '$StartDateInterval' and match_results.RoundDate <= '$EndDateInterval' Order by players.Surname";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($name);
    $i = 0;
    while ($row = $stmt->fetch()) {
        $i=$i+1;
        $getname = $name;
    }
    echo $i;
}




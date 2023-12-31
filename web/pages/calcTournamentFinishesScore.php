<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
include_once "web/include/navbar.htm";
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$mysqli->set_charset("utf8");

$firstcount = 0; $secondcount = 0; $thirdcount = 0;  //used in tournamentfinishweighting.php
// remove previous data
if (!($stmt = $mysqli->prepare("DELETE from tournamentfinishesscore" ))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
$stmt->execute();
$stmt->close();
// get list of players with opponents
$sql3 = "SELECT Player1_Namecode FROM player_ratings";
if ($stmt3 = $mysqli->prepare($sql3)) {
    $stmt3->execute();
    $stmt3->bind_result( $pnc);
    while ($row = $stmt3->fetch()) {
        $tournamentfinishscore = 0;
        $passplayercode = $pnc;
        include "tournamentfinishweighting.php";
        $playerscore[$pnc][0]=$pnc;
        $playerscore[$pnc][1] = round($tournamentfinishscore, 1);
    }
}
$stmt3->close();
// sort list in order of number of opponents
uasort($playerscore, function ($a, $b) {
    return $a[1] < $b[1];
});
$topten = 0;
// write list of top 15 to table
$topten = 0;
foreach ($playerscore as $top15score) {
    $key = $top15score[0];
    $topplayerscore = $top15score[1];
    $topten += 1;
    if (!($stmt = $mysqli->prepare("INSERT INTO tournamentfinishesscore (PlayerNameCode, TournamentFinishScore) VALUES (?,?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $stmt->bind_param("si", $key, $topplayerscore);
    /* set parameters and execute */
    $stmt->execute();
    $stmt->close();
    if ($topten == 15) {break;}
}
$mysqli->close();
?>
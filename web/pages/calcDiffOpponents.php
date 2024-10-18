<?php
ini_set('max_execution_time', 2500);
header('Content-type: text/plain; charset=utf-8');
// database connection
//local
include("web/pages/connection.php");
//remote
//include("connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// remove previous data
if (!($stmt = $mysqli->prepare("DELETE from differentopponents" ))) {
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
        $playerOpps[$pnc][0] = $pnc;
    }
}
$stmt3->close();
// calculate number of opponents for each player
foreach ($playerOpps as $topopps) {
    $passplayercode = $topopps[0];
    $sql2 = "SELECT p, COUNT(*) AS c FROM (SELECT m.Player2_Namecode AS p FROM match_results m WHERE m.Player1_Namecode=? UNION ALL
                    SELECT m.Player1_Namecode AS p FROM match_results m WHERE m.Player2_Namecode=?) AS tp GROUP BY p";
    if ($stmt3 = $mysqli->prepare($sql2)) {
        $stmt3->bind_param("ss", $passplayercode, $passplayercode);
        $stmt3->execute();
        $stmt3->store_result();
        $playerOpps[$passplayercode][1] = $stmt3->num_rows;
    } else {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}
$stmt3->close();
// sort list in order of number of opponents
uasort($playerOpps, function ($a, $b) {
    return $a[1] < $b[1];
});
// write list of top 15 to table
$topten = 0;
foreach ($playerOpps as $top15opps) {
    $key = $top15opps[0];
    $diffopp = $top15opps[1];
    $topten += 1;
    if (!($stmt = $mysqli->prepare("INSERT INTO differentopponents (PlayerNameCode, NumberOfOpponents) VALUES (?,?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $stmt->bind_param("si", $key, $diffopp);
    /* set parameters and execute */
    $stmt->execute();
    $stmt->close();
    if ($topten == 15) {break;}
}
$mysqli->close();
?>

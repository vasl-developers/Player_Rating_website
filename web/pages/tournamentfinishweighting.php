<?php
include "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$tourtype = "PBEM";
$sql = "select t.Winner1, t.Winner2, t.Winner3, t.Tournament_id from tournaments t where (t.Winner1=? OR t.Winner2=? OR t.Winner3=?) and t.Tour_type<>?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssss", $passplayercode, $passplayercode, $passplayercode, $tourtype);
    $stmt->execute();
    $stmt -> store_result();
    $stmt -> bind_result($winner, $second, $third, $tourcode);
}
while ($row = $stmt->fetch()) {
    $newtourcode=$tourcode;
    $sql2 = "SELECT DISTINCT from (m.Player1_Namecode from match_results m where m.Tournament_ID=? 
        UNION 
        SELECT DISTINCT m.Player2_NameCode FROM match_results m where m.Tournament_ID=?) AS bestresult";
    if ($stmt2 = $mysqli->prepare($sql2)) {
        $stmt2->bind_param("ss", $tourtype, $newtourcode);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->bind_result($tourplayer);
        $count = $stmt2->num_rows;
    }
    While ($row2 = $stmt2->fetch()){
        $newcount = $count;
    }
//    $sql2 = "select SELECT DISTINCT m.Player1_Namecode, m.Player2_Namecode from match_results m
    //           FROM (SELECT DISTINCT a AS value FROM my_table
//    UNION SELECT DISTINCT b AS value FROM my_table
//    UNION SELECT DISTINCT c AS value FROM my_table
//) AS derived";
}
$stmt->close();
$numofopponents = 7;
?>

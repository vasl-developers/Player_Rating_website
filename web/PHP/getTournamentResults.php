<?php


include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO match_results (Tournament_ID, Round_No, Round_Date, Scenario_ID, Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result) VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
/* bind the parameters*/
$stmt->bind_param("sissssssssss", $tourn_id, $round_no, $round_date, $scenario_id, $player1_namecode, $player1_attdef,
    $player1_alliesaxis, $player1_result, $player2_namecode, $player2_attdef, $player2_alliesxaxis, $player2_result);
/* set parameters and execute */
$filename = "../Area_Data_2020_Ap/tournament-results.json";
$data = file_get_contents($filename);
$array = json_decode($data, true);
foreach ($array as $row)
{
    $tourn_id = $row["tourn_id"];
    $rounds = $row["rounds"];
    foreach ($rounds as $round) {
        $round_no = $round["round_no"];
        $round_date = $round["round_date"];
        $results = $round["results"];
        foreach ($results as $res) {
            $scenario_id = $res["scenario"];
            $play1 = $res["player1"];
            $player1_namecode = $play1[0];
            $player1_attdef = $play1[1];
            $player1_alliesaxis = $play1[2];
            $player1_result = $play1[3];
            $play2 = $res["player2"];
            $player2_namecode = $play2[0];
            $player2_attdef = $play2[1];
            $player2_alliesaxis = $play2[2];
            $player2_result = $play2[3];
            $stmt->execute();
        }
    }
}
$stmt->close();
$mysqli->close();
echo "Tournament Results Inserted";

?>
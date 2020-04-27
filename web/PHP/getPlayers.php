<?php

include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO players (Surname, First_Name, Country, Player_Namecode, url) VALUES
    (?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
/* bind the parameters*/
$stmt->bind_param("sssss", $surname, $first_name, $country, $player_namecode, $url);
/* set parameters and execute */
$filename = "../Area_Data_2020_Ap/players.json";
$data = file_get_contents($filename);
$array = json_decode($data, true);
foreach ($array as $row)
{
    $surname = $row["surname"];
    $first_name = $row["first_name"];
    $country = $row["country"];
    $player_namecode = $row["player_id"];
    $url = $row["player_url"];
    $stmt->execute();

}
$stmt->close();
$mysqli->close();
echo "Players Data Inserted";

?>
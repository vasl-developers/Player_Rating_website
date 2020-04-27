<?php

include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO tournaments (Base_Name, Date_Held, Tournament_id, Location_CityOrRegion, Location_Country, Tour_Type, Iteration_Name, Winner1, Winner2, Winner3, url) VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
/* bind the parameters*/
$stmt->bind_param("sssssssssss", $base_name, $date_held, $tournament_id, $location_cityorregion, $location_country, $tour_type,
    $iteration_name, $winner1, $winner2, $winner3, $url);
/* set parameters and execute */
$filename = "../Area_Data_2020_Ap/tournaments.json";
$data = file_get_contents($filename);
$array = json_decode($data, true);
foreach ($array as $row)
{
    $tournament_id = $row["tourn_id"];
    $base_name = $row["tourn_name"];
    $date_held = $row["tourn_date"];
    $loc = $row["tourn_location"];
    $location_cityorregion = $loc[0];
    $location_country = $loc[1];
    $tour_type = $row["tourn_type"];
    $iteration_name = $row["tourn_name2"];
    $win = $row["tourn_winners"];
    $winner1 = $win[0];
    $winner2 = $win[1];
    $winner3 = $win[2];
    $url = $row["tourn_url"];
    $stmt->execute();

}
$stmt->close();
$mysqli->close();
echo "Tournament Data Inserted";

?>
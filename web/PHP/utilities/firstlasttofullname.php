<?php
include("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("Select * FROM players"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
/* execute */
$stmt->execute();
$newnames = [];
$result=$stmt->get_result(); // get the mysqli result
foreach ($result as $row) {
    $newnames[] = $row;
}
$stmt->close();
$n=0;
foreach ($newnames as $rowdata) {
    /* Prepared statement, stage 1: prepare */
    if (!($stmt2 = $mysqli->prepare("UPDATE players SET Fullname=? WHERE First_name=? AND Surname=?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $firstname = $rowdata["First_Name"];
    $surname = $rowdata["Surname"];
    $fullname = $firstname . " " . $surname;
    $stmt2->bind_param("sss",  $fullname, $firstname, $surname);
    /* set parameters and execute */
    if ($stmt2->execute()) {
    } else {
        echo "Insert Failed";
    }
    $stmt2->close();
    $n= $n +1;
    echo "name updated" . $n;
}

$mysqli->close();
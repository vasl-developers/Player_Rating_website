<?php
include("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("Select * FROM match_results"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
/* execute */
$stmt->execute();
$result=$stmt->get_result(); // get the mysqli result
$newdates = [];
$previousdate="";
foreach ($result as $row){
    if (trim($row["Round_Date"])!=trim($previousdate)){
        $newdates[] = $row["Round_Date"];
        $previousdate = $row["Round_Date"];
    }
}
$stmt->close();
$n=0;
foreach ($newdates as $datetoupdate) {
    /* Prepared statement, stage 1: prepare */
    if (!($stmt2 = $mysqli->prepare("UPDATE match_results SET RoundDate=? WHERE Round_Date=?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $round_date = $datetoupdate;
    $datetoset = date("Y-m-d", strtotime($datetoupdate));
    $stmt2->bind_param("ss", $datetoset, $round_date);
    /* set parameters and execute */
    if ($stmt2->execute()) {
    } else {
        echo "Insert Failed";
    };
    $stmt2->close();
    $n= $n +1;
    echo "date updated" . $n;
}
$mysqli->close();
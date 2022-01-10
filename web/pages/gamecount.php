<?php
include_once "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$mysqli->set_charset("utf8");
$date = date('Y-M-d');
$result = mysqli_query($mysqli, "SELECT count(*) FROM match_results");
$gamestotal = mysqli_fetch_row($result)[0];
?>
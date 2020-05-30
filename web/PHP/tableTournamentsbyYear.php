<?php
include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<html lang="en">
<head>
  <meta charset="utf-8" />
</head>
<body>
<div id="content">
  <h1>List of All Tournaments Included in ASL Player Ratings</h1>
  <p>This list includes all Tournaments submitted to ASL Player Ratings . . . . It includes results added as of {a date}</p>
  <p>To view Game-by-Game results for a particular Tournament, click on the link.</p>
  <?php
    $tournament_id="";
    $sql = "select * from tournaments where Date_Held IS NOT NULL order by DATE(Date_Held) ASC";
    $result = mysqli_query($mysqli, $sql);

    $firstarray = [];
    $secondarray = [];

    include("./showtournamentstable.php");

    $mysqli->close();
  ?>
</div>
</body>
</html>

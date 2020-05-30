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
  <h1>List of Tournaments Added To ASL Player Ratings in the past 3 months</h1>
  <p>To view Game-by-Game results for a particular Tournament, click on the link.</p>
<?php
$tournament_id="";
if (!($stmt = $mysqli->prepare("Select * FROM tournaments WHERE Date_Added BETWEEN date_sub(current_date(), interval 15 month) AND current_date() ORDER BY DATE(Date_Held) ASC"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
$stmt->execute();
$result=$stmt->get_result();
$firstarray = [];
$secondarray=[];
include("../PHP/showtournamentstable.php");
$stmt->close();
$mysqli->close();
?>
</div>
</body>
</html>

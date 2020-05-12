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
    <h2>List of All Games Played in a Tournament included in ASL Player Ratings</h2>
    <p>To view Game-by-Game results for a particular Player, click on the link.</p>

    <?php
    $tournamenttoshow=$_GET['tournamentid']; //$tournamentid is passed from showtournamentstable.php
    include("../PHP/showgameresultstable.php");

    ?>

</div>

</body>
</html>
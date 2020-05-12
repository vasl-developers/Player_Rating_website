<?php
include("connection.php");
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
<body>
<h2>Ranked List of Active ASL Players</h2>
<p>This list includes all active players, meaning they have . . . . It includes results added as of {a date}</p>
<P>During site development this table shows an alphabetical listing of players</P>
<table class="table table-condensed table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Country</th>
        <th>Namecode</th>
        <th>Current Rating</th>
        <th>Highest Rating</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $sql = "select * from players";
    $res = $mysqli->query($sql);

    while ($row = $res->fetch_assoc()) {
        $name = trim($row["Fullname"]);
        $country  = trim($row["Country"]);
        $player_namecode = $row["Player_Namecode"];
        echo "<tr><td>$name</td><td>$country</td><td>$player_namecode</td></tr>";
    }
    $mysqli->close();
    ?>
    </tbody>
</table>
</body>
</html>

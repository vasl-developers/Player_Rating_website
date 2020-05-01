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
<h2>Ranked List of All ASL Players</h2>
<P>During site development this table shows an alphabetical listing of players</P>
<table cellPadding=3 border=1 style="border:black 2px outset;xwidth:100%;">
    <thead>
    <tr>
        <th>Surname</th>
        <th>First Name</th>
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
        $surname = trim($row["Surname"]);
        $first_name = trim($row["First_Name"]);
        $country  = trim($row["Country"]);
        $player_namecode = $row["Player_Namecode"];
        echo "<tr><td>$surname</td><td>$first_name</td><td>$country</td><td>$player_namecode</td></tr>";
    }
    $mysqli->close();
    ?>
    </tbody>
</table>
</body>
</html>

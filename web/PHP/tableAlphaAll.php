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
<h2>Alphabetical List of All ASL Players</h2>
<p>This list includes all ASL Players who have . . . .It includes results added as of {a date}</p>

<table class="table table-condensed table-striped">
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

    $sql = "select * from players order by Surname, First_Name";
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

<?php

include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
<html>

<body>
<h2>Alphabetical Listing of Currently Active ASL Players</h2>
<p>This list includes all active players, meaning they have . . . . It includes results added as of adddate</p>


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
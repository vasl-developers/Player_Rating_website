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
<p>This list includes all active players, meaning they have played in a tournament within 800 days before the last update to the database. It includes results added as of August, 2017.</p>

<table class="table table-condensed table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Country</th>
        <th>Namecode</th>
        <th>Current Rating</th>
        <th>Highest Rating</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark, player_ratings.Active, player_ratings.Provisional from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY player_ratings.ELO DESC";
    // if (!($stmt = $mysqli->prepare($sql))) {
    //     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
    // $stmt->execute();
    // $result=$stmt->get_result(); // get the mysqli result
    // $stmt->close();
    $i=0;
    $result_sc = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_assoc($result_sc)) {
    // while ($row = $result->fetch_assoc()) {
        $active = $row["Active"];
        $provisional = $row["Provisional"];
        if($active ==1 and $row["Hidden"] == 0) {
            $i++;
            $name = trim($row["Fullname"]);
            $country = trim($row["Country"]);
            $player_namecode = $row["Player_Namecode"];
            $ELO = $row["ELO"];
            $HWM = $row["HighWaterMark"];
            echo "<tr><td>$i</td>";
            echo "<td>$name</td>";
            echo "<td>$country</td>";
            ?>
            <td class="top">
                <p><a class="content" href="/web/PHP/tablePlayerGameResults.php?playercode=<?php echo $player_namecode?>"><?php echo $player_namecode?></a></p>
            </td>
            <?PHP
            echo "<td>$ELO</td><td>$HWM</td></tr>";
        }
    }
    $mysqli->close();
    ?>
    </tbody>
</table>
</body>
</html>

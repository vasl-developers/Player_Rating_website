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
<p>This list includes ASL Players who have played in a submitted tournament. It includes results added as of August, 2017</p>
<p></p>
<p>To view game-by-game results for a player, click on the link under ID</p>
<table class="table table-condensed table-striped">
  <thead>
  <tr>
    <th>Name</th>
    <th>Country</th>
    <th>ID</th>
    <th>Current Rating</th>
    <th>Highest Rating</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      if ($row["Hidden"] == 0 ) {
        $name = ucwords(strtolower(trim($row["Fullname"])), " .-\t\r\n\f\v");
        $country = trim($row["Country"]);
        $player_namecode = $row["Player_Namecode"];
        $ELO = $row["ELO"];
        $HWM = $row["HighWaterMark"];
        echo "<tr><td>$name</td><td>$country</td>";
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

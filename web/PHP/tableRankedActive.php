<?php
include("connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
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
    <th>Id</th>
    <th>Current Rating</th>
    <th>Highest Rating</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark, player_ratings.Active, player_ratings.Provisional from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY player_ratings.ELO DESC";

    if ($stmt = $mysqli->prepare($sql)) {
      $stmt->execute();
      $stmt->bind_result($name, $country, $nameCode, $hidden, $elo, $highWaterMark, $active, $provisional);

      $i=0;
      while ($row = $stmt->fetch()) {
        if($active == 1 and $hidden == 0) {
          $i++;
          $name = ucwords(strtolower(trim($name)), " .-\t\r\n\f\v");
          $country = trim($country);
          echo "<tr><td>$i</td><td>$name</td><td>$country</td>";
          ?>
          <td class="top">
            <p><a class="content" href="/web/PHP/tablePlayerGameResults.php?playercode=<?php echo $nameCode?>"><?php echo $nameCode?></a></p>
          </td>
          <?php
          echo "<td>$elo</td><td>$highWaterMark</td></tr>";
        }
      }
    }
    $stmt->close();
    $mysqli->close();
    ?>
  </tbody>
</table>
</body>
</html>

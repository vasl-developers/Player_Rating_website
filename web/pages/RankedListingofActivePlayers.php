<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<?php include_once "web/include/navbar.htm";?>
<div class="home container-fluid">
  <div class="row">
    <div class="main-content col-md-10 offset-md-1">
    <?php
include "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");
?>
<h2>Ranked List of Active ASL Players</h2>
<p>This list includes all active players, meaning they have played in a tournament within 800 days before the last update to the database. To see which tournaments have been added in the last three months, see <a href="tableTournamentsRecentlyAdded.php">Tournaments Recently Added</a>.</p>
<p>To view game-by-game results for a player, click on the link under ID.</p>
<p>New players added since the last Rating recalcuation will have a rating of 0 until ratings are recalculated on the first of the month.</p>
<div class="tableFixHead">
<table class="table table-sm table-striped table-hover">
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
	$i = 0;
	while ($row = $stmt->fetch()) {
		if ($active == 1 and $hidden == 0) {
			$i++;
			$name = ucwords(strtolower(trim($name)), " .-\t\r\n\f\v");
			$country = trim($country);
			echo "<tr><td>$i</td><td>$name</td><td>$country</td>";
			?>
          <td>
            <a class="content" href="<?php echo $ROOT; ?>web/pages/tablePlayerGameResults.php?playercode=<?php echo $nameCode ?>"><?php echo $nameCode ?></a>
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
</div>
    </div>
  </div>
</div>
<?php include_once "web/include/footer.php";?>
</body>
</html>

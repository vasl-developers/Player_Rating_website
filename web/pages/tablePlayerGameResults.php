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
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
?>
  <h2>List of Games by Player included in ASL Player Ratings</h2>
  <?php
$passplayercode = $_GET['playercode']; //playercode is passed from tableGameResultsforTournaments.php and tableRankedActive.php

$sql = "select Fullname from players where Player_Namecode = ?";

if ($getPlayer = $mysqli->prepare($sql)) {
	$getPlayer->bind_param("s", $passplayercode);
	$getPlayer->execute();
	$getPlayer->bind_result($name);
	$row = $getPlayer->fetch();
	$name = ucwords(strtolower(trim($name)), " .-\t\r\n\f\v");
}
$getPlayer->close();

$sql = "select m.Player1_Namecode, m.Player1_AttDef, m.Player1_AlliesAxis, m.Player1_Result, m.Player2_Namecode, m.Player2_AttDef, m.Player2_AlliesAxis, m.Round_Date, m.Scenario_ID, m.Tournament_ID, p1.Fullname, p1.Player_Namecode, p2.Fullname, p2.Player_Namecode, s.name from match_results m INNER JOIN players p1 ON p1.Player_Namecode=m.Player1_Namecode INNER JOIN players p2 ON p2.Player_Namecode=m.Player2_Namecode LEFT JOIN scenarios s ON m.Scenario_ID=s.scenario_id WHERE m.Player1_Namecode=? OR m.Player2_Namecode=? ORDER BY m.Round_Date desc";

if ($stmt = $mysqli->prepare($sql)) {
	$stmt->bind_param("ss", $passplayercode, $passplayercode);
	$stmt->execute();
	$stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $roundDate, $scenario, $tourId, $player1, $player1code, $player2, $player2code, $scenarioName);
	?>
  <h3>Player: <?php echo $name . ' (' . $passplayercode . ')' ?><a class="content" href="<?php echo $ROOT; ?>web/pages/createplayerstatistics.php?playercode=<?php echo $passplayercode ?>" style="float:right;">See Statistical Summary</a></h3>
  <div class="tableFixHead">
  <table class="table table-sm table-striped table-hover">
    <thead>
      <tr>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th>Result</th>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th>Scenario</th>
        <th>Date</th>
        <th>Tourney</th>
      </tr>
    </thead>
    <tbody>
  <?php
while ($row = $stmt->fetch()) {
		if (trim(strtolower($p1Result)) == "draw") {
			$p1Result = "draws";
		} elseif (trim(strtolower($p1Result)) == "lost") {
			$p1Result = "loses to";
		} else {
			$p1Result = "beats";
		}
		?>
      <tr>
        <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p1Code ?>"><?php echo prettyName($player1) ?></a></td>
        <td><?php echo $p1AttDef ?></td>
        <td><?php echo $p1AlliAxis ?></td>
        <td><?php echo $p1Result ?></td>
        <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p2Code ?>"><?php echo prettyName($player2) ?></a></td>
        <td><?php echo $p2AttDef ?></td>
        <td><?php echo $p2AlliAxis ?></td>
        <td>
          <a class="content" href="tableScenarioresults.php?scenarioid=<?php echo $scenario ?>"><?php echo $scenario . ' ' . $scenarioName ?></a>
        </td>
        <td class="date"><?php echo $roundDate ?></td>
        <td>
          <a class="content" href="<?php echo $ROOT; ?>web/pages/tableGameResultsforTournament.php?tournamentid=<?php echo $tourId ?>"><?php echo $tourId ?></a>
        </td>
      </tr>
    <?php
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

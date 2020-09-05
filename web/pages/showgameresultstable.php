<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");

$sql = "select m.Player1_Namecode, m.Player1_AttDef, m.Player1_AlliesAxis, m.Player1_Result, m.Player2_Namecode, m.Player2_AttDef, m.Player2_AlliesAxis, m.Round_No, m.Scenario_ID, p1.Fullname, p1.Hidden, p2.Fullname, p2.Hidden from match_results m
    INNER JOIN players p1 ON p1.Player_Namecode=m.Player1_Namecode
    INNER JOIN players p2 ON p2.Player_Namecode=m.Player2_Namecode
    where m.Tournament_ID = '" . $_GET["tournamentid"] . "' order by m.Round_No, p1.Fullname";

if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $roundNo, $scenario, $player1, $play1hide, $player2, $play2hide);

	$previousRoundNo = "";
	?>
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
      </tr>
      </thead>
      <tbody>
        <?php
while ($row = $stmt->fetch()) {
		if ($roundNo != $previousRoundNo) {
			$previousRoundNo = $roundNo;
			?>
        <tr>
          <td colspan=9 class="headline">Round <?php echo $roundNo ?></th>
        </tr>
          <?php
}
		?>
        <tr>
          <td>
            <?php
if ($play1hide == 1) {$player1 = "Hidden";}
		if ($play2hide == 1) {$player2 = "Hidden";}
		if ($player1 == "Hidden") {
			?>
                  <?php echo prettyName($player1) ?>
                <?php
} else {
			?>
                  <a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p1Code ?>"><?php echo prettyName($player1) ?></a>
                <?php
}
		?>
          </td>
          <td><?php echo $p1AttDef ?></td>
          <td><?php echo $p1AlliAxis ?></td>
          <?php
if (trim(strtolower($p1Result)) == "draw") {
			$p1Result = "draws";
		} elseif (trim(strtolower($p1Result)) == "lost") {
			$p1Result = "loses to";
		} else {
			$p1Result = "beats";
		}
		?>
          <td><?php echo $p1Result ?></td>
          <td>
            <?php
if ($player2 == "Hidden") {
			?>
                  <?php echo prettyName($player2) ?>
                <?php
} else {
			?>
                  <a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p2Code ?>"><?php echo prettyName($player2) ?></a>
                <?php
}
		?>
          </td>
          <td><?php echo $p2AttDef ?></td>
          <td><?php echo $p2AlliAxis ?></td>
          <td><a class="content" href="tableScenarioresults.php?scenarioid=<?php echo $scenario ?>"><?php echo $scenario ?></a></td>
        </tr>
        <?php
}
	?>
      </tbody>
    </table>

<?php
$mysqli->close();
} else {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
?>

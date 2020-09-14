<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");
// get game results for tournament
// and Scenario_ID like 'CH%'
$sql = "select Scenario_ID, SUBSTRING_INDEX(Scenario_ID, ' ', 1), count(*) ct from match_results WHERE Length(Scenario_ID) > 10
  group by Scenario_ID
  ORDER BY ct desc, Scenario_ID limit 50";
if (!($stmt = $mysqli->prepare($sql))) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	$stmt->execute();
	$stmt->bind_result($scenarioId, $split, $count);
	while ($row = $stmt->fetch()) {
		?>
      update match_results set scenario_id = '<?PHP echo $split; ?>' where scenario_id = '<?PHP echo $scenarioId; ?>'; // <?PHP echo $count; ?> <br/>
<?php
}
}
$mysqli->close();
?>

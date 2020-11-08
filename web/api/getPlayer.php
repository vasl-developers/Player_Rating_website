<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
?>
<body>
<?php
include_once "web/pages/connection.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

if (isset($_GET["code"])) {
	$playerCode = trim($_GET["code"]);

	if (strlen($playerCode) > 4) {
		die('Invalid player code.');
	}

	$sql = "select players.Surname lname, players.First_Name fname, player_ratings.ELO elo, player_ratings.HighWaterMark hwm from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode where players.Player_Namecode = '" . $playerCode . "'";

	$result = mysqli_query($mysqli, $sql);

	$arr = [];
	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_assoc($result);
		$name = ucwords(strtolower(trim($row["fname"]) . " " . trim($row["lname"])), " .-\t\r\n\f\v");
		$elo = $row["elo"];
		$hwm = $row["hwm"];

		$arr["name"] = $name;
		$arr["elo"] = $elo;
		$arr["hwm"] = $hwm;
	} else {
		$arr["name"] = "Not Found";
		$arr["elo"] = 0;
		$arr["hwm"] = 0;
	}

	echo json_encode($arr);
}
$mysqli->close();
?>
</body>
</html>

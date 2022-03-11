<?php
header("Content-type: application/json; charset=utf-8");
set_include_path($_SERVER['DOCUMENT_ROOT']);

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

	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_assoc($result);
		$name = ucwords(strtolower(trim($row["fname"]) . " " . trim($row["lname"])), " .-\t\r\n\f\v");
		$elo = $row["elo"];
		$hwm = $row["hwm"];

		$obj->name = $name;
		$obj->elo = number_format($elo, 2);
		$obj->hwm = number_format($hwm, 2);
	} else {
		$obj->name = "Not Found";
		$obj->elo = 0;
		$obj->hwm = 0;
	}

	echo json_encode($obj, JSON_NUMERIC_CHECK);
}

if (isset($_GET["name"])) {
  $playerName = trim($_GET["name"]);

  $sql = "select players.Surname lname, players.First_Name fname, players.Player_Namecode code, player_ratings.ELO elo, player_ratings.HighWaterMark hwm from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode where players.Fullname = '" . $playerName . "'";

  $result = mysqli_query($mysqli, $sql);

  if (mysqli_num_rows($result)) {
    $row = mysqli_fetch_assoc($result);
    $name = ucwords(strtolower(trim($row["fname"]) . " " . trim($row["lname"])), " .-\t\r\n\f\v");
    $elo = $row["elo"];
    $hwm = $row["hwm"];
    $code = $row["code"];

    $obj->name = $name;
    $obj->code = $code;
    $obj->elo = number_format($elo, 2);
    $obj->hwm = number_format($hwm, 2);
  } else {
    $obj->name = "Not Found";
    $obj->elo = 0;
    $obj->hwm = 0;
  }

  echo json_encode($obj, JSON_NUMERIC_CHECK);
}
$mysqli->close();
?>

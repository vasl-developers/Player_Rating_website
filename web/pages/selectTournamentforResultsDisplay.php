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
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
?>
    <h3>List of Tournament Games Played included in ASL Player Ratings</h3>
      <?php $tournamenttoshow = trim($_GET["tournamentid"]);?>
      <div class="tableFixHead">
      <?php
if (isset($_GET['tournamentid']) && $_GET['tournamentid'] != 'Choose...') {
	?>
      <?php
    $mysqli->set_charset("utf8");
    $sql = "select Winner1, Winner2, Winner3 from tournaments where Tournament_id like '" . $_GET["tournamentid"] ."%'";
    if ($stmt = $mysqli->prepare($sql)) {
        $result = mysqli_query($mysqli, $sql);
        $row = mysqli_fetch_assoc($result);
        $w1 = $row["Winner1"] . '';
        $w2 = $row["Winner2"] . '';
        $w3 = $row["Winner3"] . '';
        $winner1 = '';
        $winner2 = '';
        $winner3 = '';
        if ($w1 > '') $winner1 = '1st Place: ' . getPlayerName($row["Winner1"]);
        if ($w2 > '') $winner2 = '<br>2nd Place: ' . getPlayerName($row["Winner2"]);
        if ($w3 > '') $winner3 = '<br>3rd Place: ' . getPlayerName($row["Winner3"]);
    }
    ?>
    <h4 class="mt-3">Tournament: <?php echo $tournamenttoshow ?><a class="content" href="<?php echo $ROOT; ?>web/pages/Tournament Statistical Summary.php?tournamentcode=<?php echo $tournamenttoshow ?>" style="float:right;">See Statistical Summary</a></h4>
    <h5><?php echo $winner1 ?> <?php echo $winner2 ?> <?php echo $winner3 ?></h5>
    <?php
    include_once "web/pages/showgameresultstable.php";
} else {
	$sql = "select Base_Name, Year_Held, Tournament_id from tournaments order by Base_Name";
	$result = mysqli_query($mysqli, $sql);
	$tournamentlist = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$tournamentlist[] = $row;
	}
	$mysqli->close();
	?>
        <h4 class="mt-3">Select the Tournament: <?php echo $tournamenttoshow ?></h4>
        <form class="form-inline col-5" method="get" action="selectTournamentforResultsDisplay.php">
          <div class="input-group">
            <select class="form-select" id="tournamentid" name="tournamentid" autocomplete="on">
              <option selected>Choose...</option>
              <?php
foreach ($tournamentlist as $tournament) {
		?>
              <option value="<?php echo $tournament["Tournament_id"]; ?>">
                <?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?>
              </option>
              <?php
}
	?>
            </select>
            <button class="btn btn-primary" name="submit" type="submit" value="Select">Select</button>
          </div>
        </form>
      <?php }?>
      </div>
    </div>
  </div>
</div>
<?php
function getPlayerName($playernamecode) {
global $mysqli;
$firstname =""; $surname = "";
$sql2 = "select players.Surname, players.First_Name from players where Player_Namecode=?";
if ($stmt2 = $mysqli->prepare($sql2)) {
$stmt2->bind_param("s", $playernamecode);
$stmt2->execute();
$stmt2->bind_result($surname, $firstname);
while ($row = $stmt2->fetch()) {
$name = ucwords(strtolower(trim($firstname) . " " . trim($surname)), " .-\t\r\n\f\v");
$stmt2->close();
return $name;
}
} else {
echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
return null;
}
?>
<?php include_once "web/include/footer.php";?>
</body>
</html>

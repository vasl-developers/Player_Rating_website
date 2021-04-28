<html lang="en">
<?php
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
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");

if (isset($_GET['tournamentid'])) {
	$tournamenttoshow = trim($_GET["tournamentid"]);
	include_once "gamecorrection.php";
} else {
	$sql = "select Base_Name, Year_Held, Tournament_id from tournaments ORDER BY Base_Name";
	$result = mysqli_query($mysqli, $sql);
	$tournamentlist = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$tournamentlist[] = $row;
	}
	$mysqli->close();
	?>
        <h2>Submit a Tournament Game Correction</h2>
        <p>Use this page to submit a correction to a Tournament Result</p>

        <ol>
          <li>Select the Tournament from the Tournaments dropdown list</li>
          <li>Select a game from the Tournament Games dropdown list OR choose Add Missing Game</li>
          <li>Enter revised or new information</li>
          <li>Save</li>
        </ol>

        <div class="card p-2 pb-0 mb-3 bg-light">
          <p>NOTE: If you are changing a game from one player to another (to merge duplicate entries for the same person, for example), do it here.</p>
          <p>If you want to change a player's name (i.e. from Mike Brown to Michael Brown) for all games for that player, don't do it here, go to <a href="toolUpdatePlayers.php">Add or Update Players</a>.</p>
        </div>

        <h4>Select the Tournament:</h4>
        <form class="form-inline col-5" method="get" action="toolSubmitGameCorrection.php">
          <div class="input-group">
            <select class="form-select" id="tournamentid" name="tournamentid" autocomplete="on">
              <option selected>Choose...</option>
              <?php foreach ($tournamentlist as $tournament) {?>
              <option value="<?php echo $tournament["Tournament_id"]; ?>">
                <?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?>
              </option>
              <?php }?>
            </select>
            <button class="btn btn-primary" name="submit" type="submit" value="Select">Select</button>
          </div>
        </form>
        <?php }?>
      </div>
    </div>
  </div>
  <?php include_once "web/include/footer.php";?>
</body>

</html>

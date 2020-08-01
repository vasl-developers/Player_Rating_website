<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <div class="main-content col-md-10 offset-md-1">
    <?php
    include_once("web/pages/connection.php");
    $mysqli = mysqli_connect($host, $username, $password, $database);
    $mysqli->set_charset("utf8");
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      exit();
    }
    ?>
    <h3>List of All Games Played in a Tournament included in ASL Player Ratings</h3>
      <?php $tournamenttoshow = trim($_GET["tournamentid"]); ?>
      <div class="tableFixHead">
      <?php
      if (isset($_GET['tournamentid']) && $_GET['tournamentid'] != 'Choose...') {
      ?>
        <h2>Tournament: <?php echo $tournamenttoshow ?></h2>
      <?php
        include_once("web/pages/showgameresultstable.php");
      } else {
        $sql = "select Base_Name, Year_Held, Tournament_id from tournaments order by Base_Name";
        $result = mysqli_query($mysqli, $sql);
        $tournamentlist = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $tournamentlist[] = $row;
        }
        $mysqli->close();
        ?>
        <h4 class="mt-3">Select the Tournament:</h4>
        <form class="form-inline col-5" method="get" action="selectTournamentforResultsDisplay.php">
          <div class="input-group">
            <select class="form-select" id="tournamentid" name="tournamentid" autocomplete="on">
              <option selected>Choose...</option>
              <?php
                foreach ($tournamentlist as $tournament) {
              ?>
              <option value="<?php echo $tournament["Tournament_id"];?>">
                <?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?>
              </option>
              <?php
                }
              ?>
            </select>
            <button class="btn btn-primary" name="submit" type="submit" value="Select">Select</button>
          </div>
        </form>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

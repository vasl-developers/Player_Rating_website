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
    <h2>List of All Games Played in a Tournament included in ASL Player Ratings</h2>
    <p>To select a tournament, select from the List. You can scroll or type the Name, including the Year </p>
      <?php
        $tournamenttoshow = trim($_GET["tournamentid"]);
      ?>
      <h2>Tournament: <?php echo $tournamenttoshow ?></h2>
      <div class="tableFixHead">
      <?php
      if (isset($_GET['tournamentid'])) {
        include_once("web/pages/showgameresultstable.php");
      } else {
        $sql = "select Base_Name, Year_Held, Tournament_id from tournaments";
        $result = mysqli_query($mysqli, $sql);
        $tournamentlist = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $tournamentlist[] = $row;
        }
        $mysqli->close();
        ?>
        <p>Type or Select Tournament to View Game Results:</p>
        <form method="get" action="selectTournamentforResultsDisplay.php">
          <input type="text" list="tournaments" name="tournamentid">
          <datalist id="tournaments" autocomplete="on">
            <?php
            foreach ($tournamentlist as $tournament) {
            ?>
            <option value="<?php echo $tournament["Tournament_id"];?>"><?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?></option>
            <?php
            }
            ?>
          </datalist>
          <input type="submit" value="Select" />
        </form>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

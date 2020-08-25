<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <div class="main-content col-md-10 offset-md-1">
      <?php
      include("web/pages/connection.php");
      $mysqli = mysqli_connect($host, $username, $password, $database);
      $mysqli->set_charset("utf8");
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
      }
      ?>
      <h2>Alphabetical Listing of Currently Active ASL Players</h2>
      <p>This list includes all active players, meaning they have played in a tournament within 800 days before the last update to the database. It includes results added as of July, 2020.</p>
      <p>To view game-by-game results for a player, click on the player's name.</p>
      <p>New players added since the last Rating recalcuation will have a rating of 0 until ratings are recalculated on the first of the month.</p>
      <div class="tableFixHead">
      <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
          <th>Name</th>
          <th>Country</th>
          <th>Id</th>
          <th>Current Rating</th>
          <th>Highest Rating</th>
        </tr>
        </thead>
        <tbody>
        <?php
          $sql = "select players.Surname, players.First_Name, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark, player_ratings.Active, player_ratings.Provisional from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
          $result = mysqli_query($mysqli, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $active = $row["Active"];
            $provisional = $row["Provisional"];
            if($active ==1 and $row["Hidden"] == 0) {
              $name = ucwords(strtolower(trim($row["Surname"]) . ", " . trim($row["First_Name"])), " .-\t\r\n\f\v");
              $country = trim($row["Country"]);
              $player_namecode = $row["Player_Namecode"];
              $ELO = $row["ELO"];
              $HWM = $row["HighWaterMark"];
              ?>
              <tr>
                <td><a class="content" href="<?php echo $ROOT; ?>web/pages/tablePlayerGameResults.php?playercode=<?php echo $player_namecode?>"><?php echo $name ?></a></td>
                <td><?php echo $country ?></td>
                <td><?php echo $player_namecode?></td>
                <td><?php echo $ELO ?></td>
                <td><?php echo $HWM ?></td>
              </tr>
            <?php
            }
          }
          $mysqli->close();
          ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

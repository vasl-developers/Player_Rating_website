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
    <?php include_once("web/include/left-sidebar.php"); ?>
    <div class="main-content col-md-8">

<?php
include_once("web/pages/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
?>
<h2>Alphabetical List of All ASL Players</h2>
<p>This list includes ASL Players who have played in a submitted tournament. It includes results added as of August, 2017</p>
<p></p>
<p>To view game-by-game results for a player, click on the link under ID</p>
<table class="table table-condensed table-striped">
  <thead>
  <tr>
    <th>Name</th>
    <th>Country</th>
    <th>ID</th>
    <th>Current Rating</th>
    <th>Highest Rating</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
    $result = mysqli_query($mysqli, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      if ($row["Hidden"] == 0 ) {
        $name = ucwords(strtolower(trim($row["Fullname"])), " .-\t\r\n\f\v");
        $country = trim($row["Country"]);
        $player_namecode = $row["Player_Namecode"];
        $ELO = $row["ELO"];
        $HWM = $row["HighWaterMark"];
        echo "<tr><td>$name</td><td>$country</td>";
        ?>
        <td class="top">
          <p><a class="content" href="<?php echo $ROOT; ?>web/pages/tablePlayerGameResults.php?playercode=<?php echo $player_namecode?>"><?php echo $player_namecode?></a></p>
        </td>
        <?PHP
        echo "<td>$ELO</td><td>$HWM</td></tr>";
      }
    }
    $mysqli->close();
    ?>
  </tbody>
</table>


    </div>
    <?php include_once("web/include/right-sidebar.php"); ?>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
</body>
</html>

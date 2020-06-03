<html lang="en">
<?php set_include_path($_SERVER['DOCUMENT_ROOT']); ?>
<?php include_once("web/include/header.php"); ?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <?php include_once("web/include/left-sidebar.php"); ?>
    <div class="main-content col-md-8">

<?php
include("web/PHP/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
?>
<h2>Ranked List of All ASL Players</h2>
<p></p>
<p>To view game-by-game results for a player, click on the link under ID</p>
<table class="table table-condensed table-striped">
  <thead>
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Country</th>
    <th>Id</th>
    <th>Current Rating</th>
    <th>Highest Rating</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY player_ratings.ELO DESC";

    if ($stmt = $mysqli->prepare($sql)) {
      $stmt->execute();
      $stmt->bind_result($name, $country, $nameCode, $hidden, $elo, $highWaterMark);

      $i=0;
      while ($row = $stmt->fetch()) {
        if($hidden == 0) {
          $i++;
          $name = ucwords(strtolower(trim($name)), " .-\t\r\n\f\v");
          $country = trim($country);
          echo "<tr><td>$i</td><td>$name</td><td>$country</td>";
          ?>
          <td class="top">
            <p><a class="content" href="/web/PHP/tablePlayerGameResults.php?playercode=<?php echo $nameCode ?>"><?php echo $nameCode ?></a></p>
          </td>
          <?php
          echo "<td>$elo</td><td>$highWaterMark</td></tr>";
        }
      }
    }
    $stmt->close();
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
<script type="text/javascript" src="web/include/ready.js"></script>
</body>
</html>


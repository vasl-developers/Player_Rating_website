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
include_once("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
$sql = "select Winner1, Winner2 from tournaments where Tournament_id like '" . $_GET["tournamentid"] ."%'";
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
?>
      <h2>Tournament Games included in ASL Player Ratings</h2>
      <p>To view Game-by-Game results for a particular player, click on the player's name.</p>
      <?php
        $tournamenttoshow=$_GET['tournamentid'];
      ?>
      <h3>Tournament: <?php echo $_GET["tournamentid"]?><a class="content" href="<?php echo $ROOT; ?>web/pages/Tournament Statistical Summary.php?tournamentcode=<?php echo $tournamenttoshow?>" style="float:right;">See Statistical Summary</a></h3>
      <h5><?php echo $winner1 ?> <?php echo $winner2 ?> <?php echo $winner3 ?>
      <div class="tableFixHead">
      <?php
        include_once("web/pages/showgameresultstable.php");
      ?>
      <div/>
    </div>
    <?php
}
$mysqli->close();

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
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

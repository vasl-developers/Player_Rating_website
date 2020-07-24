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
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
?>
      <h2>Tournament Games included in ASL Player Ratings</h2>
      <p>To view Game-by-Game results for a particular player, click on the player's name.</p>
      <?php
        $tournamenttoshow=$_GET['tournamentid']; //$tournamentid is passed from showtournamentstable.php
      ?>
      <h2>Tournament: <?php echo $_GET["tournamentid"]?></h2>
      <div class="tableFixHead">
      <?php
        include_once("web/pages/showgameresultstable.php");
      ?>
      <div/>
    </div>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

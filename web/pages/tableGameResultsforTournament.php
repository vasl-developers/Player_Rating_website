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
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
?>
  <h1>List of All Games Played in a Tournament included in ASL Player Ratings</h1>
  <p>To view Game-by-Game results for a particular Player, click on the link.</p>
  <div class="tableFixHead">
  <?php
    $tournamenttoshow=$_GET['tournamentid']; //$tournamentid is passed from showtournamentstable.php
    include_once("web/pages/showgameresultstable.php");
  ?>
  <div/>
    </div>

  </div>

</div>
    <?php include_once("web/include/right-sidebar.php"); ?>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
</body>
</html>

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
include_once("web/PHP/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
?>
  <h2>List of All Games Played in a Tournament included in ASL Player Ratings</h2>
  <p>To view Game-by-Game results for a particular Player, click on the link.</p>

  <?php
    $tournamenttoshow=$_GET['tournamentid']; //$tournamentid is passed from showtournamentstable.php
    include_once("web/pages/showgameresultstable.php");
  ?>

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


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

    <h1>List of All Games Played in a Tournament included in ASL Player Ratings</h1>
    <p>To select a tournament, select from the List. You can scroll or type the Name, including the Year </p>
    <div class="tableFixHead">
    <?php
    if (isset($_GET['tournamentid'])) {
        $tournamenttoshow = trim($_GET["tournamentid"]);
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
    <?php
    }
    ?>
    </div>
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

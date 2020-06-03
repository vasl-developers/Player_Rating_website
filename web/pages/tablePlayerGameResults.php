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
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
?>
  <h2>List of All Games Played by Player included in ASL Player Ratings</h2>
  <?php
    // if (!($stmt = $mysqli->prepare("Select * FROM match_results WHERE Player1_Namecode=? OR Player2_Namecode=? ORDER BY RoundDate"))) {
    //   echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    // }

    // $sql = "select * FROM match_results WHERE Player1_Namecode=? OR Player2_Namecode=? ORDER BY RoundDate";
    $sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Round_No, Scenario_ID, player1.Fullname, player2.Fullname from match_results INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode WHERE Player1_Namecode=? OR Player2_Namecode=? ORDER BY RoundDate";

    $passplayercode = $_GET['playercode'];  //playercode is passed from tableGameResultsforTournaments.php and tableRankedActive.php

    if ($stmt = $mysqli->prepare($sql)) {
      $stmt->bind_param("ss", $passplayercode, $passplayercode);
      $stmt->execute();
      $stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p2Code, $p2AttDef, $p2AlliAxis, $roundNo, $scenario, $player1, $player2);

  ?>
  <h2>Player: <?PHP echo getplayername($passplayercode)?></h2>
  <table class="table table-condensed table-striped">
    <thead>
      <tr>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th>Result</th>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th></th>
        <th>Scenario</th>
        <th>Date</th>
        <th>Tour. Id</th>
      </tr>
    </thead>
    <tbody>
  <?php
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

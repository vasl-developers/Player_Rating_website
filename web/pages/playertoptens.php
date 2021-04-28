<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<?php
include_once "web/include/navbar.htm";
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = new mysqli($host, $username, $password, $database);
$mysqli2 = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");
?>
  <div class="home container-fluid">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <h2>Top Ten Lists by Player</h2>
        <p>To view Game-by-Game results for a particular player, click on the link.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 offset-md-1">
        <?php
$sql = "SELECT Fullname, Games, Player1_Namecode FROM player_ratings ORDER BY Games DESC LIMIT 10";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($fullname, $gamesplayed, $pnc);
	?>
        <div class="tableFixHead autoHeight">
          <table class="table table-sm table-striped table-hover">
            <thead>
              <tr>
                <th>Player</th>
                <th>Games Played</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $stmt->fetch()) {
		$name = trim($fullname);
		?>
              <tr>
                <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $pnc ?>">
                    <?php echo prettyname($name) ?></a></td>
                <td>
                  <?php echo $gamesplayed ?>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
        <?php
}
$stmt->close();
?>
      </div>
      <div class="col-md-3">
        <?php
$sql = "SELECT Fullname, Wins, Player1_Namecode FROM player_ratings ORDER BY Wins DESC LIMIT 10";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($fullname, $gameswon, $pnc);
	?>
        <div class="tableFixHead autoHeight">
          <table class="table table-sm table-striped table-hover">
            <thead>
              <tr>
                <th>Player</th>
                <th>Wins</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $stmt->fetch()) {
		$name = trim($fullname);
		?>
              <tr>
                <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $pnc ?>">
                  <?php echo prettyname($name) ?></a></td>
                <td>
                  <?php echo $gameswon ?>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
        <?php
}
$stmt->close();
?>
      </div>
      <div class="col-md-3">
        <?php
$sql = "SELECT Fullname, Games, Wins, (Wins * 100/Games), Player1_Namecode FROM player_ratings WHERE Games >=50 ORDER BY (Wins * 100/Games) DESC LIMIT 10 ";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($fullname, $games, $gameswon, $winpct, $pnc);
	?>
        <div class="tableFixHead autoHeight">
          <table class="table table-sm table-striped table-hover">
            <thead>
              <tr>
                <th>Player</th>
                <th>Win Pct</th>
                <th>Games</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $stmt->fetch()) {
		$name = trim($fullname);
		?>
              <tr>
                <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $pnc ?>">
                  <?php echo prettyname($name) ?></a></td>
                <td>
                  <?php echo round($winpct, 1) ?>
                </td>
                <td>
                  <?php echo $games ?>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
        <?php
}
$stmt->close();
?>
      </div>
    </div>

      <div class="row">
          <div class="col-md-3 offset-md-1">
              <?php
$sql = "SELECT Fullname, HighestStreak, Player1_Namecode FROM player_ratings ORDER BY HighestStreak DESC LIMIT 10";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($fullname, $streak, $pnc);
	?>
                  <div class="tableFixHead autoHeight">
                      <table class="table table-sm table-striped table-hover">
                          <thead>
                          <tr>
                              <th>Player</th>
                              <th>Win Streak</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php while ($row = $stmt->fetch()) {
		$name = trim($fullname);
		?>
                              <tr>
                                  <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $pnc ?>"><?php echo prettyname($name) ?></a></td>
                                  <td><?php echo $streak ?></td>
                              </tr>
                              <?php
}
	?>
                          </tbody>
                      </table>
                  </div>
                  <?php
}
$stmt->close();
?>
          </div>
          <div class="col-md-3">
              <?php
              $firstcount=0; $secondcount=0; $thirdcount=0;
              $sql3 = "SELECT Fullname, Player1_Namecode FROM player_ratings";
              if ($stmt3 = $mysqli->prepare($sql3)) {
                  $stmt3->execute();
                  $stmt3->bind_result($fullname, $pnc);
                  while ($row = $stmt3->fetch()) {
                      $tournamentfinishscore = 0;
                      $passplayercode = $pnc;
                      include "web/pages/tournamentfinishweighting.php";
                      $playername[$pnc] = trim($fullname);
                      $playerscore[$pnc] = round($tournamentfinishscore,1);
                  }
              }
              arsort($playerscore);
              $topten =0;
              ?>
              <div class="tableFixHead autoHeight">
                  <table class="table table-sm table-striped table-hover">
                      <thead>
                      <tr>
                          <th>Player</th>
                          <th>Tournament Wins Score</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                      foreach ($playerscore as $topscore) {
                          $key = array_search (strval($topscore), $playerscore);
                          if ($key == false) {
                              $name = "Missing";
                          } else {
                              $name = trim($playername[$key]);
                          }
                          $topten +=1;
                          ?>
                          <tr>
                              <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $key ?>"><?php echo prettyname($name) ?></a></td>
                              <td><?php echo round($topscore,0) ?></td>
                          </tr>
                          <?php
                          if($topten ==10){break;}
                      }
	                      ?>
                      </tbody>
                  </table>
              </div>
              <?php
$stmt3->close();
              ?>
          </div>
          <div class="col-md-3">
              <?php
              $sql3 = "SELECT Fullname, Player1_Namecode FROM player_ratings";
              if ($stmt3 = $mysqli->prepare($sql3)) {
                  $stmt3->execute();
                  $stmt3->bind_result($fullname, $pnc);
                  while ($row = $stmt3->fetch()) {
                      $NumberOpponents = 0;
                      $passplayercode = $pnc;
                      $playerOpps[$pnc][0] = $pnc;
                      $playerOpps[$pnc][1] = trim($fullname);
                  }
              }
              $stmt3->close();
              foreach ($playerOpps as $topopps){
                  $passplayercode=$topopps[0];
                  $sql2 = "SELECT p, COUNT(*) AS c FROM (SELECT m.Player2_Namecode AS p FROM match_results m WHERE m.Player1_Namecode=? UNION ALL
                    SELECT m.Player1_Namecode AS p FROM match_results m WHERE m.Player2_Namecode=?) AS tp GROUP BY p";
                  if ($stmt3 = $mysqli->prepare($sql2)) {
                      $stmt3->bind_param("ss", $passplayercode, $passplayercode);
                      $stmt3->execute();
                      $stmt3->store_result();
                      $playerOpps[$passplayercode][2] = $stmt3->num_rows;
                  } else {

                  }
              }
              uasort($playerOpps, function($a, $b) {
                  return $a[2] < $b[2];
              });
              $topten =0;
              ?>
                  <div class="tableFixHead autoHeight">
                      <table class="table table-sm table-striped table-hover">
                          <thead>
                          <tr>
                              <th>Player</th>
                              <th>Different Opponents</th>
                          </tr>
                          </thead>
                          <tbody>

                          <?php
                          foreach ($playerOpps as $topopps) {
                              $name = trim($topopps[1]);
                              $key = $topopps[0];
                              $topten +=1;
                          ?>
                              <tr>
                                  <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $key ?>"><?php echo prettyname($name) ?></a></td>
                                  <td><?php echo $topopps[2] ?></td>
                              </tr>
                          <?php
                              if($topten ==10){break;}
                          }
                          ?>
                          </tbody>
                      </table>
                  </div>
              <?php
              $stmt3->close();

              ?>
          </div>

      </div>
  </div>
  <?php
$mysqli->close();
include_once "web/include/footer.php";
?>
</body>
</html>

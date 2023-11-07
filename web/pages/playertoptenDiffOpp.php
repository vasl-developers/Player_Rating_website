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
        <h2>Top Ten Different Opponents by Player</h2>
        <p>To view Game-by-Game results for a particular player, click on the link.</p>
      </div>
    </div>
      <div class="row">
          <div class="col-md-4 offset-md-1">
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
                foreach ($playerOpps as $topopps) {
	                $passplayercode = $topopps[0];
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
                uasort($playerOpps, function ($a, $b) {
                    return $a[2] < $b[2];
                });
                $topten = 0;
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
	                        $topten += 1;
	                        ?>
                            <tr>
                                <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $key ?>"><?php echo prettyname($name) ?></a></td>
                                <td><?php echo $topopps[2] ?></td>
                                </tr>
                            <?php
                            if ($topten == 10) {break;}
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

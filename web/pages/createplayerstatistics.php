<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
</head>
<body>
<?php
include_once "web/include/navbar.htm";
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$passplayercode = $_GET['playercode']; //playercode is passed from tableGameResultsforTournaments.php and RankedListingofActivePlayers.php
$sql = "select Fullname from players where Player_Namecode = ?";
if ($getPlayer = $mysqli->prepare($sql)) {
	$getPlayer->bind_param("s", $passplayercode);
	$getPlayer->execute();
	$getPlayer->bind_result($name);
	$row = $getPlayer->fetch();
}
$getPlayer->close();
$firstcount = 0; $secondcount=0; $thirdcount=0;
$tournamentfinishscore = 0;
$numofopponents=0;
include_once "web/pages/tournamentfinishweighting.php";

$sql = " select Fullname, Country, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker, GamesAsDefender, WinsAsDefender, GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak, HighestStreak from player_ratings where Player1_Namecode=?";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->bind_param("s", $passplayercode);
	$stmt->execute();
	$stmt->bind_result($fullname, $country, $hwm, $elo, $games, $wins, $gamesasatt, $winsasatt, $gamesasdef, $winsasdef, $gamesasax, $winsasax, $gamesasal, $winsasal, $currentstreak, $higheststreak);
}
while ($row = $stmt->fetch()) {
	if ($games == 0) {
		$winpct = 0;
	} else {
		$winpct = ($wins * 100) / $games;
	}
	if ($gamesasatt == 0) {
		$winpctasatt = 0;
	} else {
		$winpctasatt = ($winsasatt * 100) / $gamesasatt;
	}
	if ($gamesasdef == 0) {
		$winpctasdef = 0;
	} else {
		$winpctasdef = ($winsasdef * 100) / $gamesasdef;
	}
	if ($gamesasax == 0) {
		$winpctasax = 0;
	} else {
		$winpctasax = ($winsasax * 100) / $gamesasax;
	}
	if ($gamesasal == 0) {
		$winpctasal = 0;
	} else {
		$winpctasal = ($winsasal * 100) / $gamesasal;
	}

	?>
    <div class="container">
        <h3 class="mt-3">Statistical Summary for <?php echo prettyName($name) ?></h3>
        <p>Updated on the 1st of every month. Games added during a month will appear in the Summary next month.</p>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col"><?php echo prettyName($fullname) ?></div>
            <div class="col"><?php echo $country ?></div>
            <div class="col">Highest Rating:</div>
            <div class="col"><?php echo $hwm ?></div>
            <div class="col">Current Rating:</div>
            <div class="col"><?php echo $elo ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">All Games:</div>
            <div class="col"><?php echo $games ?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $wins ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpct, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black" >
            <div class="col">Games as Attacker:</div>
            <div class="col"><?php echo $gamesasatt ?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasatt ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasatt, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Games as Defender:</div>
            <div class="col"><?php echo $gamesasdef ?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasdef ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasdef, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Games as Axis:</div>
            <div class="col"><?php echo $gamesasax ?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasax ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasax, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Games as Allies:</div>
            <div class="col"><?php echo $gamesasal ?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasal ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasal, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Longest Win Streak:</div>
            <div class="col"><?php echo $higheststreak ?></div>
            <div class="col">Current Win Streak:</div>
            <div class="col"><?php echo $currentstreak ?></div>

        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Tournaments Won:</div>
            <div class="col"><?php echo $firstcount ?></div>
            <div class="col">Tournaments 2nd:</div>
            <div class="col"><?php echo $secondcount ?></div>
            <div class="col">Tournaments 3rd:</div>
            <div class="col"><?php echo $thirdcount ?></div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Tournament Finishes Score:</div>
            <div class="col"><?php echo Round($tournamentfinishscore,1) ?></div>
        </div>

        <div class="row mt-4">
          <div class="col-3">
            <h3 class="text-center">as Attacker</h3>
            <canvas id="attack"></canvas>
          </div>
          <div class="col-3">
            <h3 class="text-center">as Defender</h3>
            <canvas id="defend"></canvas>
          </div>
          <div class="col-3">
            <h3 class="text-center">as Allies</h3>
            <canvas id="allied"></canvas>
          </div>
          <div class="col-3">
            <h3 class="text-center">as Axis</h3>
            <canvas id="axis"></canvas>
          </div>
        </div>
    </div>
<?php
}
include_once "web/include/footer.php";
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
<script type="text/javascript">
  var attPie = document.getElementById("attack").getContext('2d');
  var attPieChart = new Chart(attPie, {
    type: 'pie',
    data: {
      labels: ["Wins", "Losses"],
      datasets: [{
      data: [<?php echo $winsasatt ?>, <?php echo $gamesasatt - $winsasatt ?>],
      backgroundColor: ["#F7464A", "#949FB1"],
      hoverBackgroundColor: ["#FF5A5E", "#A8B3C5"]
    }]
  },
    options: { responsive: true }
  });

  var defPie = document.getElementById("defend").getContext('2d');
  var defPieChart = new Chart(defPie, {
    type: 'pie',
    data: {
      labels: ["Wins", "Losses"],
      datasets: [{
      data: [<?php echo $winsasdef ?>, <?php echo $gamesasdef - $winsasdef ?>],
      backgroundColor: ["#46BFBD", "#949FB1"],
      hoverBackgroundColor: ["#5AD3D1", "#A8B3C5"]
    }]
  },
    options: { responsive: true }
  });

  var allPie = document.getElementById("allied").getContext('2d');
  var allPieChart = new Chart(allPie, {
    type: 'pie',
    data: {
      labels: ["Wins", "Losses"],
      datasets: [{
      data: [<?php echo $winsasal ?>, <?php echo $gamesasal - $winsasal ?>],
      backgroundColor: ["#FDB45C", "#949FB1"],
      hoverBackgroundColor: ["#FFC870", "#A8B3C5"]
    }]
  },
    options: { responsive: true }
  });

  var axPie = document.getElementById("axis").getContext('2d');
  var axPieChart = new Chart(axPie, {
    type: 'pie',
    data: {
      labels: ["Wins", "Losses"],
      datasets: [{
      data: [<?php echo $winsasax ?>, <?php echo $gamesasax - $winsasax ?>],
      backgroundColor: ["#4D5360", "#949FB1"],
      hoverBackgroundColor: ["#616774", "#A8B3C5"]
    }]
  },
    options: { responsive: true }
  });
</script>
</body>
</html>

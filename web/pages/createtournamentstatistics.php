<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<style>
tr.border { border-bottom: 1pt solid black !important; }
</style>
<?php
include_once "web/include/navbar.htm";
include_once "web/pages/connection.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$passtournamentcode = $_GET['tournamentcode']; //tournamentcode is passed from tableGameResultsforTournaments.php
$sql = "select Base_Name, Year_Held from tournaments where Tournament_id = ?";
if ($getTourney = $mysqli->prepare($sql)) {
	$getTourney->bind_param("s", $passtournamentcode);
	$getTourney->execute();
	$getTourney->bind_result($tourname, $touryear);
	$row = $getTourney->fetch();
}
$getTourney->close();

$sql = "select m.Scenario_ID, m.Player1_Namecode, m.Player1_AttDef, m.Player1_AlliesAxis, m.Player1_Result, m.Player2_Namecode, m.Player2_AttDef, m.Player2_AlliesAxis, m.Player2_Result, s.name from match_results m LEFT JOIN scenarios s ON m.Scenario_ID=s.scenario_id where Tournament_ID=?";

if ($stmt = $mysqli->prepare($sql)) {
	$stmt->bind_param("s", $passtournamentcode);
	$stmt->execute();
	$stmt->bind_result($scenarioid, $player1nc, $player1attdef, $player1alax, $player1res, $player2nc, $player2attdef, $player2alax, $player2res, $name);
}

// initialize scenario array
$scenario["totalgames"] = "totalgames";
$gp["totalgames"] = 0;
$attwin["totalgames"] = 0;
$defwin["totalgames"] = 0;
$alwin["totalgames"] = 0;
$axwin["totalgames"] = 0;
$attloss["totalgames"] = 0;
$defloss["totalgames"] = 0;
$alloss["totalgames"] = 0;
$axloss["totalgames"] = 0;
$attdraw["totalgames"] = 0;
$defdraw["totalgames"] = 0;
$aldraw["totalgames"] = 0;
$axdraw["totalgames"] = 0;
while ($row = $stmt->fetch()) {
	if (strtolower($player1res) == "won") {
		$player1res = "win";
	} else if (strtolower($player1res) == "lost") {
		$player1res = "loss";
	}

	// create array item for each new scenario played
	if ($scenarioid == "" || $scenarioid == null) {
		$scenarioid = "noname";
	}

	if ($scenarioid == "totalgames") {
		continue;
	}

	$id = $scenarioid;
	$scenarioid = $scenarioid . ' ';

	if (!in_array($scenarioid, $scenario)) {
		$scenario[$scenarioid] = $id;
		$s_name[$scenarioid] = $name;
		$gp[$scenarioid] = 0;
		$attwin[$scenarioid] = 0;
		$defwin[$scenarioid] = 0;
		$alwin[$scenarioid] = 0;
		$axwin[$scenarioid] = 0;
		$attloss[$scenarioid] = 0;
		$defloss[$scenarioid] = 0;
		$alloss[$scenarioid] = 0;
		$axloss[$scenarioid] = 0;
		$attdraw[$scenarioid] = 0;
		$defdraw[$scenarioid] = 0;
		$aldraw[$scenarioid] = 0;
		$axdraw[$scenarioid] = 0;
	}
	// set array values
	$gp[$scenarioid]++;
	$gp["totalgames"]++;
	if (strtolower($player1res) == "win") {
		if (strtolower($player1attdef) == "attacker") {
			$attwin[$scenarioid]++;
			$defloss[$scenarioid]++;
			$attwin["totalgames"]++;
			$defloss["totalgames"]++;
		} else if (strtolower($player1attdef) == "defender") {
			$defwin[$scenarioid]++;
			$attloss[$scenarioid]++;
			$defwin["totalgames"]++;
			$attloss["totalgames"]++;
		}
		if (strtolower($player1alax) == "axis") {
			$axwin[$scenarioid]++;
			$alloss[$scenarioid]++;
			$axwin["totalgames"]++;
			$alloss["totalgames"]++;
		} else if (strtolower($player1alax) == "allies") {
			$alwin[$scenarioid]++;
			$axloss[$scenarioid]++;
			$alwin["totalgames"]++;
			$axloss["totalgames"]++;
		}
	} else if (strtolower($player1res) == "loss") {
		if (strtolower($player1attdef) == "attacker") {
			$attloss[$scenarioid]++;
			$defwin[$scenarioid]++;
			$attloss["totalgames"]++;
			$defwin["totalgames"]++;
		} else if (strtolower($player1attdef) == "defender") {
			$defloss[$scenarioid]++;
			$attwin[$scenarioid]++;
			$defloss["totalgames"]++;
			$attwin["totalgames"]++;
		}
		if (strtolower($player1alax) == "axis") {
			$axloss[$scenarioid]++;
			$alwin[$scenarioid]++;
			$axloss["totalgames"]++;
			$alwin["totalgames"]++;

		} else if (strtolower($player1alax) == "allies") {
			$alloss[$scenarioid]++;
			$axwin[$scenarioid]++;
			$alloss["totalgames"]++;
			$axwin["totalgames"]++;
		}
	} else {
		//draw
		if (strtolower($player1attdef) == "attacker") {
			$attdraw[$scenarioid]++;
			$defdraw[$scenarioid]++;
			$attdraw["totalgames"]++;
			$defdraw["totalgames"]++;
		} else if (strtolower($player1attdef) == "defender") {
			$defdraw[$scenarioid]++;
			$attdraw[$scenarioid]++;
			$attdraw["totalgames"]++;
			$defdraw["totalgames"]++;
		}
		if (strtolower($player1alax) == "axis") {
			$axdraw[$scenarioid]++;
			$aldraw[$scenarioid]++;
			$axdraw["totalgames"]++;
			$aldraw["totalgames"]++;
		} else if (strtolower($player1alax) == "allies") {
			$aldraw[$scenarioid]++;
			$axdraw[$scenarioid]++;
			$aldraw["totalgames"]++;
			$axdraw["totalgames"]++;
		}
	}
}
?>
<div class="container">
  <h2>Statistical Summary for <?php echo $tourname . ' ' . $passtournamentcode ?></h2>
  <div class="row">
    <table class="table table-sm table-striped table-hover mb-1">
      <thead>
        <tr>
          <?php $scen = array_shift($scenario);?>
          <th colspan=2 style="width:33%;"><b>All Scenarios</b></th>
          <th>Games Played:</th>
          <th colspan=3><?php echo $gp[$scen] ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
ksort($scenario);
foreach (array_keys($scenario) as $scen) {
	?>
        <tr>
          <th colspan=2><br><a class="content" href="tableScenarioresults.php?scenarioid=<?php echo $scenario[$scen] ?>"><?php echo $scenario[$scen] . ' ' . $s_name[$scen] ?></a></th>
          <th><br>Games Played:</th>
          <th colspan=3><br><?php echo $gp[$scen] ?></th>
        </tr>
        <tr>
          <td>Attacker won:</td>
          <td><?php echo $attwin[$scen] ?></td>
          <td>Attacker lost:</td>
          <td><?php echo $attloss[$scen] ?></td>
          <td>Attacker drew:</td>
          <td><?php echo $attdraw[$scen] ?></td>
        </tr>
        <tr>
          <td>Attacker won:</td>
          <td><?php echo $attwin[$scen] ?></td>
          <td>Attacker lost:</td>
          <td><?php echo $attloss[$scen] ?></td>
          <td>Attacker drew:</td>
          <td><?php echo $attdraw[$scen] ?></td>
        </tr>
        <tr>
          <td>Attacker won:</td>
          <td><?php echo $attwin[$scen] ?></td>
          <td>Attacker lost:</td>
          <td><?php echo $attloss[$scen] ?></td>
          <td>Attacker drew:</td>
          <td><?php echo $attdraw[$scen] ?></td>
        </tr>
        <tr>
          <td>Defender won:</td>
          <td><?php echo $defwin[$scen] ?></td>
          <td>Defender lost:</td>
          <td><?php echo $defloss[$scen] ?></td>
          <td>Defender drew:</td>
          <td><?php echo $defdraw[$scen] ?></td>
        </tr>
        <tr>
          <td>Axis won:</td>
          <td><?php echo $axwin[$scen] ?></td>
          <td>Axis lost:</td>
          <td><?php echo $axloss[$scen] ?></td>
          <td>Axis drew:</td>
          <td><?php echo $axdraw[$scen] ?></td>
        </tr>
        <tr class="border">
          <td>Allies won:</td>
          <td><?php echo $alwin[$scen] ?></td>
          <td>Allies lost:</td>
          <td><?php echo $alloss[$scen] ?></td>
          <td>Allies drew:</td>
          <td><?php echo $aldraw[$scen] ?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>

  </div>
<?php include_once "web/include/footer.php";?>
</body>
</html>

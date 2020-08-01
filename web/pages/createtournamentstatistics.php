<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm");
include_once("web/pages/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$passtournamentcode = $_GET['tournamentcode'];  //tournamentcode is passed from tableGameResultsforTournaments.php
$sql = "select Base_Name, Year_Held from tournaments where Tournament_id = ?";
if ($getTourney = $mysqli->prepare($sql)) {
    $getTourney->bind_param("s", $passtournamentcode);
    $getTourney->execute();
    $getTourney->bind_result($tourname, $touryear);
    $row = $getTourney->fetch();
}
$getTourney->close();

$sql = " select Scenario_ID, Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result from match_results where Tournament_ID=?" ;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $passtournamentcode);
    $stmt->execute();
    $stmt->bind_result($scenarioid, $player1nc, $player1attdef, $player1alax, $player1res, $player2nc, $player2attdef, $player2alax, $player2res);
}

// initialize scenario array
$scenario["firstarrayitem"]="";
while ($row = $stmt->fetch()) {
    if (strtolower($player1res) == "won") {
        $player1res = "win";
    } else if (strtolower($player1res) == "lost") {
        $player1res = "loss";
    }
    // create array item for each new scenario played
    if (!in_array($scenarioid, $scenario)) {
        $scenario[$scenarioid] = $scenarioid;
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
    if (strtolower($player1res) == "win") {
        if (strtolower($player1attdef) == "attacker") {
            $attwin[$scenarioid]++;
            $defloss[$scenarioid]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defwin[$scenarioid]++;
            $attloss[$scenarioid]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axwin[$scenarioid]++;
            $alloss[$scenarioid]++;
        } else if (strtolower($player1alax) == "allies") {
            $alwin[$scenarioid]++;
            $axloss[$scenarioid]++;
        }
    } else if (strtolower($player1res) == "loss") {
        if (strtolower($player1attdef) == "attacker") {
            $attloss[$scenarioid]++;
            $defwin[$scenarioid]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defloss[$scenarioid]++;
            $attwin[$scenarioid]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axloss[$scenarioid]++;
            $alwin[$scenarioid]++;
        } else if (strtolower($player1alax) == "allies") {
            $alloss[$scenarioid]++;
            $axwin[$scenarioid]++;
        }
    } else {  //draw
        if (strtolower($player1attdef) == "attacker") {
            $attdraw[$scenarioid]++;
            $defdraw[$scenarioid]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defdraw[$scenarioid]++;
            $attdraw[$scenarioid]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axdraw[$scenarioid]++;
            $aldraw[$scenarioid]++;
        } else if (strtolower($player1alax) == "allies") {
            $aldraw[$scenarioid]++;
            $axdraw[$scenarioid]++;
        }
    }
}
?>
<div class="container">
<h2>Statistical Summary for <?php echo $tourname?></h2>
</div>
<br>
<?php
foreach (array_keys($scenario) as $scen){
    if ($scen == "firstarrayitem") {continue;}
?>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Scenario Played:</div>
            <div class="col"><?php echo $scenario[$scen]?></div>
            <div class="col">Games Played:</div>
            <div class="col"><?php echo $gp[$scen]?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Attacker won:</div>
            <div class="col"><?php echo $attwin[$scen]?></div>
            <div class="col">Attacker lost:</div>
            <div class="col"><?php echo $attloss[$scen] ?></div>
            <div class="col">Attacker drew:</div>
            <div class="col"><?php echo $attdraw[$scen] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Defender won:</div>
            <div class="col"><?php echo $defwin[$scen]?></div>
            <div class="col">Defender lost:</div>
            <div class="col"><?php echo $defloss[$scen] ?></div>
            <div class="col">Defender drew:</div>
            <div class="col"><?php echo $defdraw[$scen] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Axis won:</div>
            <div class="col"><?php echo $axwin[$scen]?></div>
            <div class="col">Axis lost:</div>
            <div class="col"><?php echo $axloss[$scen] ?></div>
            <div class="col">Axis drew:</div>
            <div class="col"><?php echo $axdraw[$scen] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Allies won:</div>
            <div class="col"><?php echo $alwin[$scen]?></div>
            <div class="col">Allies lost:</div>
            <div class="col"><?php echo $alloss[$scen] ?></div>
            <div class="col">Allies drew:</div>
            <div class="col"><?php echo $aldraw[$scen] ?></div>
        </div>
        <br>

    </div>
    <?php

}
include_once("web/include/footer.php");
?>
</body>
</html>

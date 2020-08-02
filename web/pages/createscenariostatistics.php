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
$passscenarioid = $_GET['scenarioid'];  //scenarioid is passed from tableScenarioresults.php

/*  Replace this with query to scenarios table once link with ROAR established
$sql = "select Base_Name, Year_Held from tournaments where Tournament_id = ?";
if ($getTourney = $mysqli->prepare($sql)) {
    $getTourney->bind_param("s", $passtournamentcode);
    $getTourney->execute();
    $getTourney->bind_result($tourname, $touryear);
    $row = $getTourney->fetch();
}
$getTourney->close();
*/
$sql = " select Tournament_ID, Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result from match_results where Scenario_ID=?" ;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $passscenarioid);
    $stmt->execute();
    $stmt->bind_result($tournamentid, $player1nc, $player1attdef, $player1alax, $player1res, $player2nc, $player2attdef, $player2alax, $player2res);
}

// initialize scenario array
$tournaments["totalgames"]="totalgames";
$gp["totalgames"] = 0; $attwin["totalgames"] = 0; $defwin["totalgames"] = 0; $alwin["totalgames"] = 0;
$axwin["totalgames"] = 0; $attloss["totalgames"] = 0; $defloss["totalgames"] = 0; $alloss["totalgames"] = 0;
$axloss["totalgames"] = 0; $attdraw["totalgames"] = 0; $defdraw["totalgames"] = 0; $aldraw["totalgames"] = 0;
$axdraw["totalgames"] = 0;
while ($row = $stmt->fetch()) {
    if (strtolower($player1res) == "won") {
        $player1res = "win";
    } else if (strtolower($player1res) == "lost") {
        $player1res = "loss";
    }
    // create array item for each new scenario played
    if (!in_array($tournamentid, $tournaments)) {
        $tournaments[$tournamentid] = $tournamentid;
        $gp[$tournamentid] = 0;
        $attwin[$tournamentid] = 0;
        $defwin[$tournamentid] = 0;
        $alwin[$tournamentid] = 0;
        $axwin[$tournamentid] = 0;
        $attloss[$tournamentid] = 0;
        $defloss[$tournamentid] = 0;
        $alloss[$tournamentid] = 0;
        $axloss[$tournamentid] = 0;
        $attdraw[$tournamentid] = 0;
        $defdraw[$tournamentid] = 0;
        $aldraw[$tournamentid] = 0;
        $axdraw[$tournamentid] = 0;
    }
    // set array values
    $gp[$tournamentid]++;
    $gp["totalgames"]++;
    if (strtolower($player1res) == "win") {
        if (strtolower($player1attdef) == "attacker") {
            $attwin[$tournamentid]++;
            $defloss[$tournamentid]++;
            $attwin["totalgames"]++;
            $defloss["totalgames"]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defwin[$tournamentid]++;
            $attloss[$tournamentid]++;
            $defwin["totalgames"]++;
            $attloss["totalgames"]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axwin[$tournamentid]++;
            $alloss[$tournamentid]++;
            $axwin["totalgames"]++;
            $alloss["totalgames"]++;
        } else if (strtolower($player1alax) == "allies") {
            $alwin[$tournamentid]++;
            $axloss[$tournamentid]++;
            $alwin["totalgames"]++;
            $axloss["totalgames"]++;
        }
    } else if (strtolower($player1res) == "loss") {
        if (strtolower($player1attdef) == "attacker") {
            $attloss[$tournamentid]++;
            $defwin[$tournamentid]++;
            $attloss["totalgames"]++;
            $defwin["totalgames"]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defloss[$tournamentid]++;
            $attwin[$tournamentid]++;
            $defloss["totalgames"]++;
            $attwin["totalgames"]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axloss[$tournamentid]++;
            $alwin[$tournamentid]++;
            $axloss["totalgames"]++;
            $alwin["totalgames"]++;

        } else if (strtolower($player1alax) == "allies") {
            $alloss[$tournamentid]++;
            $axwin[$tournamentid]++;
            $alloss["totalgames"]++;
            $axwin["totalgames"]++;
        }
    } else {  //draw
        if (strtolower($player1attdef) == "attacker") {
            $attdraw[$tournamentid]++;
            $defdraw[$tournamentid]++;
            $attdraw["totalgames"]++;
            $defdraw["totalgames"]++;
        } else if (strtolower($player1attdef) == "defender") {
            $defdraw[$tournamentid]++;
            $attdraw[$tournamentid]++;
            $attdraw["totalgames"]++;
            $defdraw["totalgames"]++;
        }
        if (strtolower($player1alax) == "axis") {
            $axdraw[$tournamentid]++;
            $aldraw[$tournamentid]++;
            $axdraw["totalgames"]++;
            $aldraw["totalgames"]++;
        } else if (strtolower($player1alax) == "allies") {
            $aldraw[$tournamentid]++;
            $axdraw[$tournamentid]++;
            $aldraw["totalgames"]++;
            $axdraw["totalgames"]++;
        }
    }
}
?>
<div class="container">
    <h2>Scenario Statistical Summary for <?php echo $passscenarioid ?></h2>
</div>
<br>
<?php
foreach (array_keys($tournaments) as $tour){
    ?>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Tournament:</div>
            <div class="col">
                <?php
                if ($tournaments[$tour]=="totalgames") {
                ?>
                    All Tournaments
                <?php } else { ?>
                    <a class="content" href="<?php echo $ROOT; ?>web/pages/tableGameResultsforTournament.php?tournamentid=<?php echo $tournaments[$tour]?>"><?php echo $tournaments[$tour] ?></a>
                <?php } ?>
            </div>
            <div class="col">Games Played:</div>
            <div class="col"><?php echo $gp[$tour]?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Attacker won:</div>
            <div class="col"><?php echo $attwin[$tour]?></div>
            <div class="col">Attacker lost:</div>
            <div class="col"><?php echo $attloss[$tour] ?></div>
            <div class="col">Attacker drew:</div>
            <div class="col"><?php echo $attdraw[$tour] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Defender won:</div>
            <div class="col"><?php echo $defwin[$tour]?></div>
            <div class="col">Defender lost:</div>
            <div class="col"><?php echo $defloss[$tour] ?></div>
            <div class="col">Defender drew:</div>
            <div class="col"><?php echo $defdraw[$tour] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Axis won:</div>
            <div class="col"><?php echo $axwin[$tour]?></div>
            <div class="col">Axis lost:</div>
            <div class="col"><?php echo $axloss[$tour] ?></div>
            <div class="col">Axis drew:</div>
            <div class="col"><?php echo $axdraw[$tour] ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Allies won:</div>
            <div class="col"><?php echo $alwin[$tour]?></div>
            <div class="col">Allies lost:</div>
            <div class="col"><?php echo $alloss[$tour] ?></div>
            <div class="col">Allies drew:</div>
            <div class="col"><?php echo $aldraw[$tour] ?></div>
        </div>
        <br>

    </div>
    <?php

}
include_once("web/include/footer.php");
?>
</body>
</html>


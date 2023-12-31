<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
// database connection
include_once("web/pages/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// players
//set filename
$playersfilename = "../Data/ASL Player Rating data files/area_schema_players.csv";
// delete existing data file
$test = unlink($playersfilename);
// get player data
$sql = "SELECT Surname, First_Name, Fullname, Country, Player_Namecode, Hidden FROM players";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($surname, $firstname, $name, $country, $namecode, $hidden);
    $playerarray = array();
    while ($row = $stmt->fetch()) {
        // put data into array
        $arrayitem = array("Surname"=>$surname, "First_Name"=>$firstname, "Fullname" => $name,"Country"=>$country, "Player_Namecode"=>$namecode, "Hidden"=>$hidden);
        array_push($playerarray, $arrayitem);
    }
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
// now format array - csv
$f = fopen($playersfilename, 'a');
$csv = "Surname, First_Name, Fullname, Country, Player_Namecode, Hidden\n"; //column headers
foreach ($playerarray as $line) {
    $csv.= $line["Surname"].','.$line["First_Name"].','.$line["Fullname"].','.$line["Country"].','.$line["Player_Namecode"].','.$line["Hidden"]."\n"; //Append data to csv
}
fwrite ($f,$csv);
fclose($f);

// tournaments
//set filename
$tournamentsfilename = "../Data/ASL Player Rating data files/area_schema_tournaments.csv";
// delete existing data file
$test = unlink($tournamentsfilename);
// get tounament data
$sql = "SELECT Base_Name, Month_Held, Year_Held, Date_Held, Tournament_id, Location_CityOrRegion, Location_Country, Tour_type, Iteration_Name, Winner1, Winner2, Winner3, Date_Added FROM tournaments";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($basename, $monthheld, $yearheld, $dateheld, $tourid, $loccityorregion, $loccountry, $tourtype, $iterationname, $winner1, $winner2, $winner3, $dateadded);
    $tournamentarray = array();
    $csv = array("Base_Name"=>"Base Name", "Month_Held"=>"Month Held", "Year_Held"=>"Year Held", "Date_Held"=>"Date Held", "Tournament_id"=>"Tournament ID", "Location_CityOrRegion"=>"City/Region", "Location_Country"=>"Country", "Tour_type"=>"Type", "Iteration_Name"=>"Iteration", "Winner1"=>"First", "Winner2"=>"Second", "Winner3"=>"Third", "Date_Added"=>"Date Added"); //column headers
    array_push($tournamentarray, $csv);
    while ($row = $stmt->fetch()) {
        // put data into array
        $arrayitem = array("Base_Name"=>$basename, "Month_Held"=>$monthheld, "Year_Held" => $yearheld, "Date_Held"=>$dateheld, "Tournament_id"=>$tourid, "Location_CityOrRegion"=>$loccityorregion, "Location_Country"=>$loccountry,
            "Tour_type"=>$tourtype, "Iteration_Name"=>$iterationname, "Winner1"=>$winner1, "Winner2"=>$winner2, "Winner3"=>$winner3, "Date_Added"=>$dateadded);
        array_push($tournamentarray, $arrayitem);
    }
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
// now format array - csv
$g = fopen($tournamentsfilename, 'w');
foreach ($tournamentarray as $line) {
    fputcsv($g, $line);
}
fclose($g);

// match results
//set filename
$gamesfilename = "../Data/ASL Player Rating data files/area_schema_match_results.csv";
// delete existing data file
$test = unlink($gamesfilename);
// get games data
$sql = "SELECT Tournament_ID, Round_No, Round_Date, Scenario_ID, Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, RoundDate, Match_ID FROM match_results";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($tournamentid, $roundno, $round_date, $scenarioid, $player1nc, $player1ad, $player1aa, $player1res, $player2nc, $player2ad, $player2aa, $player2res, $rounddate, $gameid);
    $gamearray = array();
    $csv = array("Tournament_ID"=>"Tournament ID", "Round_No"=>"Round", "Round_Date" => "Round Date", "Scenario_ID"=>"Scenario", "Player1_Namecode"=>"Player 1", "Player1_AttDef"=>"P1AttDef", "Player1_AlliesAxis"=>"P1AlorAx", "Player1_Result"=>"P1 Result",
        "Player2_Namecode"=>"Player 2", "Player2_AttDef"=>"P2AttDef", "Player2_AlliesAxis"=>"P2AlorAx", "Player2_Result"=>"P2 Result", "RoundDate"=>"Date", "id"=>"game id"); //column headers
    array_push($gamearray, $csv);
    while ($row = $stmt->fetch()) {
        // put data into array
        $arrayitem = array("Tournament_ID"=>$tournamentid, "Round_No"=>$roundno, "Round_Date" => $round_date, "Scenario_ID"=>$scenarioid, "Player1_Namecode"=>$player1nc, "Player1_AttDef"=>$player1ad, "Player1_AlliesAxis"=>$player1aa, "Player1_Result"=>$player1res,
            "Player2_Namecode"=>$player2nc, "Player2_AttDef"=>$player2ad, "Player2_AlliesAxis"=>$player2aa, "Player2_Result"=>$player2res, "RoundDate"=>$rounddate, "id"=>$gameid);
        array_push($gamearray, $arrayitem);
    }
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
// now format array - csv
$h = fopen($gamesfilename, 'a');
foreach ($gamearray as $line) {
    fputcsv($h, $line);
}
fclose($h);

// player_ratings
//set filename
$ratingsfilename = "../Data/ASL Player Rating data files/area_schema_player_ratings.csv";
// delete existing data file
$test = unlink($ratingsfilename);
// get ratings data
$sql = "SELECT Player1_Namecode, Fullname, Country, Active, Provisional, FirstDate, LastDate, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker, GamesAsDefender, WinsAsDefender, GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak, HighestStreak, maxdecay, decaytodate FROM player_ratings";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($playernc, $fullname, $country, $active, $provisional, $firstdate, $lastdate, $highwatermark, $elo, $games, $wins, $gamesasattacker, $winsasattacker, $gamesasdefender, $winsasdefender, $gamesasaxis, $winsasaxis, $gamesasallies, $winsasallies, $currentstreak, $higheststreak, $maxdecay, $decaytodate);
    $ratingarray = array();
    $csv = array("Player1_Namecode"=>"Player Code", "Fullname"=>"Name", "Country"=>"Country", "Active"=>"Active", "Provisional"=>"Provisional", "FirstDate"=>"First Game", "LastDate"=>"Last Game", "HighWaterMark"=>"Highest", "ELO"=>"Rating", "Games"=>"GP", "Wins"=>"Won", "GamesAsAttacker"=>"GP Att", "WinsAsAttacker"=>"Attack Wins", "GamesAsDefender"=>"GP Def", "WinsAsDefender"=>"Def Wins", "GamesAsAxis"=>"GP Axis", "WinsAsAxis"=>"Axis Wins", "GamesAsAllies"=>"GP Allies", "WinsAsAllies"=>"Allies Wins", "CurrentStreak"=>"Cur Streak", "HighestStreak"=>"Longest Strk", "maxdecay"=>"Max. Decay", "decaytodate"=>"Decay to date"); 
    // column headers
    array_push($ratingarray, $csv);
    while ($row = $stmt->fetch()) {
        // put data into array
        $arrayitem = array("Player1_Namecode"=>$playernc, "Fullname"=>$fullname, "Country"=>$country, "Active"=>$active, "Provisional"=>$provisional,
            "FirstDate"=>$firstdate, "LastDate"=>$lastdate, "HighWaterMark"=>$highwatermark, "ELO"=>$elo, "Games"=>$games, "Wins"=>$wins, "GamesAsAttacker"=>$gamesasattacker,
            "WinsAsAttacker"=>$winsasattacker, "GamesAsDefender"=>$gamesasdefender, "WinsAsDefender"=>$winsasdefender, "GamesAsAxis"=>$gamesasaxis, "WinsAsAxis"=>$winsasaxis,
            "GamesAsAllies"=>$gamesasallies, "WinsAsAllies"=>$winsasallies, "CurrentStreak"=>$currentstreak, "HighestStreak"=>$higheststreak, "maxdecay"=>$maxdecay, "decaytodate"=>$decaytodate);
        array_push($ratingarray, $arrayitem);
    }
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
// now format array - csv
$j = fopen($ratingsfilename, 'a');
foreach ($ratingarray as $line) {
    fputcsv($j, $line);
}
fclose($j);

// scenarios
//set filename
$scenariosfilename = "../Data/ASL Player Rating data files/area_schema_scenarios.csv";
// delete existing data file
$test = unlink($scenariosfilename);
// get scenario data
$sql = "SELECT scenario_id, name, publication, firstplayer_sidename, firstplayer_sideresult, secondplayer_sidename, secondplayer_sideresult, url FROM scenarios";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($scenid, $name, $pub, $fplayerside, $fplayersideres, $splayerside, $splayersideres, $url);
    $scenarioarray = array();
    $csv = array("scenario_id"=>"scenario id", "name"=>"scen name", "publication"=>"pub name",  "firstplayer_sideresult"=>"first side result", "secondplayer_sidename"=>"second side name", "secondplayer_sideresult"=>"second side result", "url"=>"url link"); //column headers
    array_push($scenarioarray, $csv);
    while ($row = $stmt->fetch()) {
        // put data into array
        $arrayitem = array("scenario_id"=>$scenid, "name"=>$name, "publication"=>$pub, "firstplayer_sidename"=>$fplayerside, "firstplayer_sideresult"=>$fplayersideres, "secondplayer_sidename"=>$splayerside, "secondplayer_sideresult"=>$splayersideres, "url"=>$url);
        array_push($scenarioarray, $arrayitem);
    }
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
// now format array - csv
$k = fopen($scenariosfilename, 'w');
foreach ($scenarioarray as $line) {
    fputcsv($k, $line);
}
fclose($k);



$mysqli->close();
$txt= date("Y-m-d"). " Monthly data export to csv files completed" . "\n";
include("web/pages/storetransactionstofile.php");
?>

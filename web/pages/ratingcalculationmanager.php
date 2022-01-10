<?php
ini_set('max_execution_time', 500);
header('Content-type: text/plain; charset=utf-8');
// database connection
include("connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// 1. set date variables used in calculations
$date = date('Y-m-d');
$date1 = new DateTime('0001-1-1');
$date2 = new DateTime($date);

// 1.1 store decay data and tournament type data
$gamesplayedforprogress = array();
$decaytodate = array();
$maxdecay = array();
$playerprogress = [];
$sql = "select player_ratings.Games, player_ratings.Player1_Namecode from player_ratings";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result( $getgames, $playernamecode);
    while ($row = $stmt->fetch()) {
        $gamesplayedforprogress[$playernamecode] = $getgames;
    }
    $stmt->close();
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}

$sql = "select tournaments.Tournament_ID, tournaments.Tour_type from tournaments where tournaments.Tour_type='PBEM'";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($gettourid, $gettourtype);
    while ($row = $stmt->fetch()) {
        //create tourtype array
        $tourtype[$gettourid] = $gettourtype;
    }
    $stmt->close();
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}

// 1.2 remove previous data
if (!($stmt = $mysqli->prepare("DELETE from player_ratings" ))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
$stmt->execute();
$stmt->close();
if (!($stmt1 = $mysqli->prepare("DELETE from player_progress" ))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}
$stmt1->execute();
$stmt1->close();
/*-----------------------------------------------------
# 2. Initialization of players
-----------------------------------------------------*/
$sql = "select players.Fullname, players.Country, players.Player_Namecode from players";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($getplayername, $getcountry, $playernamecode);
    while ($row = $stmt->fetch()) {
        //create empty arrays for each player
        $playername[$playernamecode] = $getplayername;
        $country[$playernamecode] = $getcountry;
        $active[$playernamecode] = "no";
        $provisional[$playernamecode] = "yes";
        $hwm[$playernamecode] = 0;
        $elo[$playernamecode] = 1500;
        $delta[$playernamecode] = 0;
        $games[$playernamecode] = 0;
        $wins[$playernamecode] = 0;
        $gamesAtt[$playernamecode] = 0;
        $winsAtt[$playernamecode] = 0;
        $gamesDef[$playernamecode] = 0;
        $winsDef[$playernamecode] = 0;
        $gamesAxis[$playernamecode] = 0;
        $winsAxis[$playernamecode] = 0;
        $gamesAllies[$playernamecode] = 0;
        $winsAllies[$playernamecode] = 0;
        $streak[$playernamecode] = 0;
        $highestStreak[$playernamecode] = 0;
        $maxdecay[$playernamecode] = 0;
        $decaytodate[$playernamecode] = 0;
        if ($gamesplayedforprogress == null){
            $calcprogressperiod=0;
        } elseif (!array_key_exists($playernamecode, $gamesplayedforprogress)){
            $calcprogressperiod =0;
        } else {
            $calcprogressperiod = floor($gamesplayedforprogress[$playernamecode] / 10);
        }
        $gamesplayedperiod[$playernamecode]= $calcprogressperiod;
        $nextprogressperiodreached[$playernamecode] = false;
        $nextprogressperiod[$playernamecode] = 1;

    }
    $stmt->close();
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}

// July 2021, removed this loop so that everyone starts at 1500
// parse and assign starting values to above arrays $elo and $hwm from init_elo.csv
//if (($handle = fopen("../Data/init_elo.csv", "r")) !== FALSE) {
//    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//        $pnc = $data[0];
//        $rating = $data[1];
//        $elo[$pnc] = $rating;
//        $hwm[$pnc] = $rating;
//    }
//    fclose($handle);
//}

#pour zoomer sur un gars donne - test code
//$gars="CE3";
//$paselo=$elo[$gars];

/*-----------------------------------------------------
 a. Ordered list of dates when a game was played
-----------------------------------------------------*/
$sql = "SELECT RoundDate,count(*) FROM match_results GROUP BY RoundDate ORDER BY RoundDate";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($rounddate, $getcount);
    $b=0;
    while ($row = $stmt->fetch()) {
        $gamedays[$b++]= $rounddate;
    }
    $stmt->close();
} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    exit();
}

// create loop for each day since 1998-04-03
$begin = new DateTime( "1998-04-03" );
$end   =new DateTime($date);

for($i = $begin; $i <= $end;$i->modify('+1 day')) {

    /*-----------------------------------------------------
     b. For each date on which a game was played ....
    -----------------------------------------------------*/

    $gamedate = $i->format('Y-m-d');

    if (in_array($gamedate, $gamedays, false) != false) {
        $sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, Tournament_ID, Round_No, Scenario_ID, RoundDate from match_results WHERE Rounddate=? ORDER BY Round_No";
        if ($stmt = $mysqli->prepare($sql)) {
            /* bind the parameters and execute*/
            $stmt->bind_param("s", $gamedate);
            $stmt->execute();
            $stmt->bind_result($f_pnc, $f_role, $f_side, $f_res, $s_pnc, $s_role, $s_side, $s_res, $t_id, $roundno, $scen_id, $date);

            while ($row = $stmt->fetch()) {
                //put results data into an array for each player
                if(!array_key_exists($t_id, $tourtype)) {  // if tourtype is PBEM, don't use
                    $fplayer = array("fpnc" => $f_pnc, "spnc" => $s_pnc, "fres" => $f_res,
                        "fside" => $f_side, "frole" => $f_role, "tid" => $t_id, "roundno" => $roundno,
                        "date" => $date, "scenid" => $scen_id, "upf" => 0);
                    $splayer = array("spnc" => $s_pnc, "fpnc" => $f_pnc, "sres" => $s_res,
                        "sside" => $s_side, "srole" => $f_role, "tid" => $t_id, "roundno" => $roundno,
                        "date" => $date, "scenid" => $scen_id, "ups" => 0);
                    // set first and last dates played for each player
                    if (empty($first[$f_pnc])) {
                        $first[$f_pnc] = $date;
                    }
                    if (empty($first[$s_pnc])) {
                        $first[$s_pnc] = $date;
                    }
                    $last[$f_pnc] = $date;
                    $last[$s_pnc] = $date;
                    // assign results values to arrays
                    if (isset($games[$f_pnc])) {
                        $games[$f_pnc]++;
                    }
                    if (isset($games[$s_pnc])) {
                        $games[$s_pnc]++;
                    }

                    if ($games[$f_pnc] > 10) {
                        $provisional[$f_pnc] = 0; //0=no
                    } else {
                        $provisional[$f_pnc] = 1;
                    }

                    if ($games[$s_pnc] > 10) {
                        $provisional[$s_pnc] = 0; //0=no
                    } else {
                        $provisional[$s_pnc] = 1;
                    }
                    if (strtolower($f_role) == "attacker") {
                        $gamesAtt[$f_pnc]++;
                    }
                    if (strtolower($f_role) == "defender") {
                        $gamesDef[$f_pnc]++;
                    }
                    if (strtolower($f_side) == "axis") {
                        $gamesAxis[$f_pnc]++;
                    }
                    if (strtolower($f_side) == "allies") {
                        $gamesAllies[$f_pnc]++;
                    }
                    if (strtolower($s_role) == "attacker") {
                        $gamesAtt[$s_pnc]++;
                    }
                    if (strtolower($s_role) == "defender") {
                        $gamesDef[$s_pnc]++;
                    }
                    if (strtolower($s_side) == "axis") {
                        $gamesAxis[$s_pnc]++;
                    }
                    if (strtolower($s_side) == "allies") {
                        $gamesAllies[$s_pnc]++;
                    }
                    if (strtolower($f_res) == "won") {
                        $f_res = "win";
                    }
                    if (strtolower($f_res) == "loss") {
                        $f_res = "lost";
                    }
                    if (strtolower($f_res) == "win") {
                        $fw = 1;
                        $sw = 0;
                        $wins[$f_pnc]++;
                        if (strtolower($f_role) == "attacker") {
                            $winsAtt[$f_pnc]++;
                        }
                        if (strtolower($f_role) == "defender") {
                            $winsDef[$f_pnc]++;
                        }
                        if (strtolower($f_side) == "axis") {
                            $winsAxis[$f_pnc]++;
                        }
                        if (strtolower($f_side) == "allies") {
                            $winsAllies[$f_pnc]++;
                        }
                        $streak[$f_pnc]++;
                        if ($highestStreak[$f_pnc] < $streak[$f_pnc]) {
                            $highestStreak[$f_pnc] = $streak[$f_pnc];
                        }
                        if ($highestStreak[$s_pnc] < $streak[$s_pnc]) {
                            $highestStreak[$s_pnc] = $streak[$s_pnc];
                        }
                        $streak[$s_pnc] = 0;
                    } elseif ($f_res == "lost") {
                        $fw = 0;
                        $sw = 1;
                        $wins[$s_pnc]++;
                        if (strtolower($s_role) == "attacker") {
                            $winsAtt[$s_pnc]++;
                        }
                        if (strtolower($s_role) == "defender") {
                            $winsDef[$s_pnc]++;
                        }
                        if (strtolower($s_side) == "axis") {
                            $winsAxis[$s_pnc]++;
                        }
                        if (strtolower($s_side) == "allies") {
                            $winsAllies[$s_pnc]++;
                        }
                        $streak[$s_pnc]++;
                        if ($highestStreak[$f_pnc] < $streak[$f_pnc]) {
                            $highestStreak[$f_pnc] = $streak[$f_pnc];
                        }
                        if ($highestStreak[$s_pnc] < $streak[$s_pnc]) {
                            $highestStreak[$s_pnc] = $streak[$s_pnc];
                        }
                        $streak[$f_pnc] = 0;
                    } elseif ($f_res == "draw") {
                        $fw = 0.5;
                        $sw = 0.5;
                        $streak[$s_pnc]++;
                        $streak[$f_pnc]++;
                        if ($highestStreak[$f_pnc] < $streak[$f_pnc]) {
                            $highestStreak[$f_pnc] = $streak[$f_pnc];
                        }
                        if ($highestStreak[$s_pnc] < $streak[$s_pnc]) {
                            $highestStreak[$s_pnc] = $streak[$s_pnc];
                        }
                    }
                    // calculate the rating impact of result
                    $dfs = ($elo[$f_pnc] - $elo[$s_pnc]) / 400;
                    $dsf = ($elo[$s_pnc] - $elo[$f_pnc]) / 400;
                    $fwe = 1 / (1 + 10 ** $dsf);
                    $swe = 1 / (1 + 10 ** $dfs);

                    $FactorK = factor_k($elo[$f_pnc], $games[$f_pnc]);
                    $upf = $FactorK * ($fw - $fwe);

                    $FactorK = factor_k($elo[$s_pnc], $games[$s_pnc]);
                    $ups = $FactorK * ($sw - $swe);

                    // stick updated information back in array
                    $fplayer["upf"] = intval($upf * 10) / 10;
                    $splayer["ups"] = intval($ups * 10) / 10;
                    // What do these lines do?
                    $boutabout_f = "\"" . join("\",\"", $fplayer) . "\"";
                    $boutabout_s = "\"" . join("\",\"", $splayer) . "\"";
                    /*  test code which echoes to screen the rating change per game for $gars
                    if ($f_pnc == $gars) {
                        $paselo+=$fplayer["upf"];echo "game vs ",$fplayer["spnc"]," points lost/won : ", $fplayer["upf"]," (total : $paselo)\n";
                    }
                    if ($s_pnc == $gars) {
                        $paselo+=$splayer["ups"];echo "game vs ",$splayer["fpnc"]," points lost/won : ", $splayer["ups"]," (total : $paselo)\n";
                    }
                    */
                    // this is used to test the calculation process - to use, first create empty db table with this name and structure
                    /*if (!($stmt = $mysqli->prepare("INSERT INTO progress (Player1_Namecode, Player2_Namecode,
                        Result,Side,Role,Tournament_ID,Round_No,RoundDate,Scenario_ID, Elo_Change) VALUES (?,?,
                        ?,?,?,?,?,?,?,?)"))) {
                        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    }
                     bind the parameters
                    $stmt->bind_param("ssssssissd", $s_pnc, $f_pnc, $s_res, $s_side, $s_role,
                        $t_id, $roundno, $date, $scen_id, $splayer{"ups"});
                    set parameters and execute
                    $stmt->execute();
                    $stmt->close();
                    */
                    if (empty($delta[$f_pnc])) {
                        $delta[$f_pnc] = $upf;
                    } else {
                        $delta[$f_pnc] += $upf;
                    }
                    if (empty($delta[$s_pnc])) {
                        $delta[$s_pnc] = $ups;
                    } else {
                        $delta[$s_pnc] += $ups;
                    }

                    //test for progress period
                    $period = $gamesplayedperiod[$f_pnc] * $nextprogressperiod[$f_pnc];
                    $testforperiodmatach = fmod($games[$f_pnc], $period);
                    if ($testforperiodmatach ==0) {
                        $playerprogress[$f_pnc][$nextprogressperiod[$f_pnc]] = intval(($elo[$f_pnc]+ $delta[$f_pnc])*10)/10;
                        $nextprogressperiod[$f_pnc]+=1;
                    }
                    $period = $gamesplayedperiod[$s_pnc] * $nextprogressperiod[$s_pnc];
                    $testforperiodmatach = fmod($games[$s_pnc], $period);
                    if ($testforperiodmatach ==0) {
                        $playerprogress[$s_pnc][$nextprogressperiod[$s_pnc]] = intval(($elo[$s_pnc]+ $delta[$s_pnc])*10)/10;
                        $nextprogressperiod[$s_pnc]+=1;
                    }
                }
            }
            /*?>
            </html><p><?PHP echo "on a fini le jour ($date) : ",$nbjour++,"\n"?></p></html>
            <?PhP */
            // at the end of each day, update the ratings
            foreach (array_keys($delta) as $t) {
                $elo[$t] += $delta[$t];
                if ($hwm[$t] < $elo[$t]) {
                    $hwm[$t] = $elo[$t];
                }

                unset($delta[$t]);
            }
        }
    } // end of game date loop

    // decay calc after ratingcalc (if any) for this day
    $date2 = date_create($gamedate);
    foreach (array_keys($last) as $t) {
        if (!empty($last[$t])) {
            //$date1 = $last[$t];
            $date3 = date_create($last[$t]);
            $depuis = date_diff($date2, $date3);
            $sincelastgame = $depuis->format('%a');
            if ($sincelastgame > 1100) {
                $sincelastgame -= 1100;
                if($sincelastgame==1){  // set maximum rating decay
                    if($maxdecay[$t]==0){$maxdecay[$t]= $elo[$t] * 0.15;}
                }
                if(fmod($sincelastgame, 30)== 0) {
                    $todaysdecay=3;
                    if(!array_key_exists($t, $decaytodate)){
                        $decaytodate[$t]=0;
                    }
                    if ($decaytodate[$t] + $todaysdecay >=$maxdecay[$t]){$todaysdecay= $todaysdecay - ($decaytodate[$t] + $todaysdecay - $maxdecay[$t]);}  // maxdecay already applied so no further decay
                    if ($todaysdecay < 0){$todaysdecay = 0;}
                    $elo[$t] = $elo[$t] - $todaysdecay; //decayfactor applied to elo every 30 days;
                    $decaytodate[$t] = $decaytodate[$t] + $todaysdecay; // add today's decay to cumulative decay
                }
            } else {  // reset maxdecay to zero as player has resumed play
                $maxdecay[$t]=0;
            }
        }
    }
} // end of date loop

// at the end of the final day, update elo/hwm in database
foreach (array_keys($last) as $t) {
    $finalelo=intval($elo[$t]*10)/10;
    $finalhwm=intval($hwm[$t]*10)/10;
    $date = date('Y-M-d h:i:s');
    $date1 = $last[$t];
    $date2=date_create($date);
    $date3=date_create($date1);
    $depuis  = date_diff($date2,$date3);
    $sincelastgame = $depuis->format('%a');
	if ($sincelastgame < 800) {
        $active=1; // 1=yes
    } else {
        $active=0; // 0=no
    }
    if (!($stmt = $mysqli->prepare("INSERT INTO player_ratings (Player1_Namecode, Fullname, Country,
            Active, Provisional, FirstDate, LastDate, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker,
            GamesAsDefender, WinsAsDefender, GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak,
            HighestStreak, maxdecay, decaytodate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $stmt->bind_param("sssiissddiiiiiiiiiiiiii", $t, $playername[$t], $country[$t], $active, $provisional[$t],
            $first[$t], $last[$t], $finalhwm, $finalelo, $games[$t], $wins[$t], $gamesAtt[$t], $winsAtt[$t], $gamesDef[$t],
            $winsDef[$t], $gamesAxis[$t], $winsAxis[$t], $gamesAllies[$t], $winsAllies[$t], $streak[$t],
            $highestStreak[$t], $maxdecay[$t], $decaytodate[$t]);
        /* set parameters and execute */
    $stmt->execute();
    $stmt->close();
    // store ratings progress data
    $firstperiod = $secondperiod=$thirdperiod=$fourthperiod=$fifthperiod=$sixthperiod=$seventhperiod=$eighthperiod=$ninthperiod =$tenthperiod =0;
    if (1< $nextprogressperiod[$t] ) {$firstperiod = $playerprogress[$t][1];}
    if (2< $nextprogressperiod[$t] ) {$secondperiod = $playerprogress[$t][2];}
    if (3< $nextprogressperiod[$t] ) {$thirdperiod = $playerprogress[$t][3];}
    if (4< $nextprogressperiod[$t] ) {$fourthperiod = $playerprogress[$t][4];}
    if (5< $nextprogressperiod[$t] ) {$fifthperiod = $playerprogress[$t][5];}
    if (6< $nextprogressperiod[$t] ) {$sixthperiod = $playerprogress[$t][6];}
    if (7< $nextprogressperiod[$t] ) {$seventhperiod = $playerprogress[$t][7];}
    if (8< $nextprogressperiod[$t] ) {$eighthperiod = $playerprogress[$t][8];}
    if (9< $nextprogressperiod[$t] ) {$ninthperiod = $playerprogress[$t][9];}
    if (10< $nextprogressperiod[$t] ) {$tenthperiod = $playerprogress[$t][10];}
    if (!($stmt1 = $mysqli->prepare("INSERT INTO player_progress (PlayerName, PlayerNameCode, 1period, 2period, 3period,
        4period, 5period, 6period, 7period, 8period, 9period, 10period) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* bind the parameters*/
    $stmt1->bind_param("ssdddddddddd",  $playername[$t], $t, $firstperiod, $secondperiod, $thirdperiod,
    $fourthperiod, $fifthperiod, $sixthperiod, $seventhperiod, $eighthperiod, $ninthperiod, $tenthperiod);
    /* set parameters and execute */
    $stmt1->execute();
    $stmt1->close();

}
$txt= date("Y-m-d"). " Rating recalculation completed" . "\n";
include("storetransactionstofile.php");
/*#==========function===============================
elo : r+=k*(w-we)
16 to 32
k v from 40 to 10 par pas de 10 selon le rating
0<1800 40, 1800<2000 30, 2000<2200 20 , et 10 au-dela
w vaut 1 pour gain, 0,5 pour tie et 0 pour loose
we vaut 1/(10**((R2-R1)/400) + 1)
*/

function factor_k($passelo, $passgames) {

    $k0 = 50; $k1 = 40; $k2 = 30; $k3 = 20; $k4 = 10;
    if($passgames < 10) {
        return ($k0);
    } elseif ($passelo < 1800){
        return ($k1);
    } elseif ($passelo < 2000) {
        return ($k2);
    } elseif ($passelo < 2200) {
        return ($k3);
    }
    return ($k4);
}

?>

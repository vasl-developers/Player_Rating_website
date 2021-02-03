<?php
include "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$tourtype = "PBEM";
//$totaltourscore=0;
$sql = "select t.Winner1, t.Winner2, t.Winner3, t.Tournament_id from tournaments t where (t.Winner1=? OR t.Winner2=? OR t.Winner3=?) and t.Tour_type<>?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssss", $passplayercode, $passplayercode, $passplayercode, $tourtype);
    $stmt->execute();
    $stmt -> store_result();
    $stmt -> bind_result($winner, $second, $third, $tourcode);
    $finishcount = $stmt->num_rows;
}
while ($row = $stmt->fetch()) {
    $newtourcode=$tourcode;
    $sql2= "SELECT p, COUNT(*) AS c FROM (SELECT m.Player1_Namecode AS p FROM match_results m where m.Tournament_ID=? UNION ALL
              SELECT m.Player2_Namecode AS p FROM match_results m where m.Tournament_ID=?) AS tp GROUP BY p";
    if ($stmt2 = $mysqli->prepare($sql2)) {
        $stmt2->bind_param("ss", $newtourcode, $newtourcode);
        $stmt2->execute();
        $stmt2->store_result();
        //$stmt2->bind_result($tourplayer, $count);
        $recount = $stmt2->num_rows;
    }
    $singletourscore=0;
    if($winner==$passplayercode){
        $rankingfactor = 1;
        $firstcount = $firstcount+1;
    } elseif($second==$passplayercode){
        $rankingfactor = 0.5;
        $secondcount = $secondcount+1;
    } elseif($third==$passplayercode){
        $rankingfactor = 0.33;
        $thirdcount=$thirdcount+1;
    }
    if($recount<8){
        $sizefactor = 0;
    } elseif($recount>7 and $recount <17){
        if($winner==$passplayercode){
            $sizefactor = 0.12;
        } elseif($second==$passplayercode){
            $sizefactor = 0.06;
        } elseif($third==$passplayercode){
            $sizefactor = 0.04;
        }
    } elseif($recount>16 and $recount <25){
        if($winner==$passplayercode){
            $sizefactor = 0.21;
        } elseif($second==$passplayercode){
            $sizefactor = 0.11;
        } elseif($third==$passplayercode){
            $sizefactor = 0.07;
        }
    } elseif($recount>24 and $recount <33){
        if($winner==$passplayercode){
            $sizefactor = 0.30;
        } elseif($second==$passplayercode){
            $sizefactor = 0.15;
        } elseif($third==$passplayercode){
            $sizefactor = 0.10;
        }
    } elseif($recount>32 and $recount <49){
        if($winner==$passplayercode){
            $sizefactor = 0.42;
        } elseif($second==$passplayercode){
            $sizefactor = 0.21;
        } elseif($third==$passplayercode){
            $sizefactor = 0.14;
        }
    } elseif($recount>48 and $recount <65){
        if($winner==$passplayercode){
            $sizefactor = 0.59;
        } elseif($second==$passplayercode){
            $sizefactor = 0.29;
        } elseif($third==$passplayercode){
            $sizefactor = 0.20;
        }
    } elseif($recount>64){
        if($winner==$passplayercode){
            $sizefactor = 1;
        } elseif($second==$passplayercode){
            $sizefactor = 0.5;
        } elseif($third==$passplayercode){
            $sizefactor = 0.33;
        }
    }
    $singletourscore = ($rankingfactor * $sizefactor) * 100;
    $tournamentfinishscore = $tournamentfinishscore + $singletourscore;
}
$stmt->close();
$numofopponents = 7;
?>

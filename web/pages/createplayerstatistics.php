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
$passplayercode = $_GET['playercode'];  //playercode is passed from tableGameResultsforTournaments.php and tableRankedActive.php
$sql = "select Fullname from players where Player_Namecode = ?";
if ($getPlayer = $mysqli->prepare($sql)) {
    $getPlayer->bind_param("s", $passplayercode);
    $getPlayer->execute();
    $getPlayer->bind_result($name);
    $row = $getPlayer->fetch();
    $name = ucwords(strtolower(trim($name)), " .-\t\r\n\f\v");
}
$getPlayer->close();

$sql = " select Fullname, Country, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker, GamesAsDefender, WinsAsDefender, GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak, HighestStreak from player_ratings where Player1_Namecode=?" ;
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $passplayercode);
    $stmt->execute();
    $stmt->bind_result($fullname, $country, $hwm, $elo, $games, $wins, $gamesasatt, $winsasatt, $gamesasdef, $winsasdef, $gamesasax, $winsasax, $gamesasal, $winsasal, $currentstreak, $higheststreak);
}
while ($row = $stmt->fetch()) {
    if ($games ==0) {
        $winpct = 0;
    } else {
        $winpct = ($wins * 100) / $games;
    }
    if ($gamesasatt ==0) {
        $winpctasatt = 0;
    } else {
        $winpctasatt = ($winsasatt * 100) / $gamesasatt;
    }
    if ($gamesasdef==0) {
        $winpctasdef = 0;
    } else {
        $winpctasdef = ($winsasdef * 100) / $gamesasdef;
    }
    if ($gamesasax ==0) {
        $winpctasax = 0;
    } else {
        $winpctasax = ($winsasax * 100) / $gamesasax;
    }
    if ($gamesasal ==0) {
        $winpctasal = 0;
    } else {
        $winpctasal = ($winsasal * 100) / $gamesasal;
    }
?>
    <div class="container">
        <h2>Statistical Summary for <?php echo $name?></h2>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col"><?php echo $fullname?></div>
            <div class="col"><?php echo $country?></div>
            <div class="col">Highest Rating:</div>
            <div class="col"><?php echo $hwm?></div>
            <div class="col">Current Rating:</div>
            <div class="col"><?php echo $elo?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">All Games:</div>
            <div class="col"><?php echo $games?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $wins ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpct, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black" >
            <div class="col">Games as Attacker:</div>
            <div class="col"><?php echo $gamesasatt?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasatt ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasatt, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Games as Defender:</div>
            <div class="col"><?php echo $gamesasdef?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasdef ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasdef, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6 bg-light text-black">
            <div class="col">Games as Axis:</div>
            <div class="col"><?php echo $gamesasax?></div>
            <div class="col">Wins:</div>
            <div class="col"><?php echo $winsasax ?></div>
            <div class="col">Win %:</div>
            <div class="col"><?php echo number_format($winpctasax, 2, '.', '') ?></div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-6">
            <div class="col">Games as Allies:</div>
            <div class="col"><?php echo $gamesasal?></div>
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
    </div>
<?php
}
include_once("web/include/footer.php");
?>
</body>
</html>
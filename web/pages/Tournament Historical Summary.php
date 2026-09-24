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
//$passtournamentcode = $_GET['tournamentcode']; //tournamentcode is passed from tableGameResultsforTournaments.php
//testcode
$passtournamentcode = "caslo_26";
$nameKeyword = "_";
// Find position of the keyword
$endPos = strpos($passtournamentcode, $nameKeyword);
$mastertourname = substr($passtournamentcode, 0, $endPos);
//$mastertourcode = $mastertourcode . '%';
$sql = "select Base_Name, Year_Held, Tournament_id, Winner1, Winner2, Winner3 from tournaments where Tournament_id LIKE CONCAT(?, '%') order by Year_Held desc;";
if ($getTourney = $mysqli->prepare($sql)) {
    $getTourney->bind_param("s", $mastertourname);
    $getTourney->execute();
    $getTourney->bind_result($tourname, $touryear, $tournamentcode, $winner1, $winner2, $winner3);

    while ($row = $getTourney->fetch()) {
        $tourneys[] = $tournamentcode;
        $tourneyyear[] = $touryear;
        $winners[] = $winner1;
        $seconds[] = $winner2;
        $thirds[] = $winner3;
    }
?>
<div class="container">
    <h2>Historical Summary for <?php echo $tourname ?></h2>
    <?php
}
$getTourney->close();
    foreach ($tourneys as $t) {
        $sql3 = "SELECT count(*) FROM match_results where Tournament_ID=?";
        if ($stmt3 = $mysqli->prepare($sql3)) {
            $stmt3->bind_param("s", $t);
            $stmt3->execute();
            $stmt3->bind_result($gcount);
            $row = $stmt3->fetch();
            $gamecount[] = $gcount;
        }
        $stmt3->close();
        $playercodes = [];
        $sql2 = "select Player1_Namecode, Player2_Namecode from match_results where Tournament_ID=?";
        if ($stmt = $mysqli->prepare($sql2)) {
            $stmt->bind_param("s", $t);
            $stmt->execute();
            $stmt->bind_result($player1nc, $player2nc);
            while ($row = $stmt->fetch()) {
                // Check if the item is NOT in the array
                if (!in_array($player1nc, $playercodes)) {
                    $playercodes[] = $player1nc; // Append the item to the array
                }
                if (!in_array($player2nc, $playercodes)) {
                    $playercodes[] = $player2nc; // Append the item to the array
                }
            }
            $playercount[] = count($playercodes);
        }
    }
    $count = count($gamecount);
    for ($i = 0; $i < $count; $i++) {
         $plcount = $playercount[$i];
         $gacount = $gamecount[$i];
         $tyear = $tourneyyear[$i];
         $first = getPlayerName($winners[$i]);
         $second = getPlayerName($seconds[$i]);
         $third = getPlayerName($thirds[$i]);

    ?>
    <div class="container">
        <div class="row">
            <br>
        </div>
    <div class="row">
        <table class="table table-sm border-bottom table-hover mb-1">
            <thead>
            </thead>
            <tbody>

            <tr style="color: red;">
                <td colspan="3">&nbsp;</td>
                <th>Year:</th>
                <th colspan=3><?php echo $tyear ?></th>
            </tr>
            <tr>
                <th>Winner:</th>
                <th colspan=3><?php echo $first ?></th>
                <th>Second:</th>
                <th colspan=3><?php echo $second ?></th>
                <th>Third:</th>
                <th colspan=3><?php echo $third ?></th>
            </tr>
            <tr>
                <th>Games Played:</th>
                <th colspan=3><?php echo $gacount ?></th>
                <th>Players:</th>
                <th colspan=3><?php echo $plcount ?></th>
            </tr>


            </tbody>
        </table>
    </div>

    </div>

    <?php
    }

    function getPlayerName($playernamecode)
    {
        global $mysqli;
        $firstname = "";
        $surname = "";
        $sql2 = "select players.Surname, players.First_Name from players where Player_Namecode=?";
        if ($stmt2 = $mysqli->prepare($sql2)) {
            $stmt2->bind_param("s", $playernamecode);
            $stmt2->execute();
            $stmt2->bind_result($surname, $firstname);
            while ($row = $stmt2->fetch()) {
                $name = ucwords(strtolower(trim($firstname) . " " . trim($surname)), " .-\t\r\n\f\v");
                $stmt2->close();
                return $name;
            }
        } else {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        return null;
    }

    include_once "web/include/footer.php";?>
</body>
</html>


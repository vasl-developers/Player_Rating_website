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
            <h2>Top Ten Tournament Finishes Score by Player</h2>
            <p>To view Game-by-Game results for a particular player, click on the link.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 offset-md-1">
            <?php
            $firstcount = 0;
            $secondcount = 0;
            $thirdcount = 0;
            $sql3 = "SELECT Fullname, Player1_Namecode FROM player_ratings";
            if ($stmt3 = $mysqli->prepare($sql3)) {
                $stmt3->execute();
                $stmt3->bind_result($fullname, $pnc);
                while ($row = $stmt3->fetch()) {
                    $tournamentfinishscore = 0;
                    $passplayercode = $pnc;
                    include "web/pages/tournamentfinishweighting.php";
                    $playername[$pnc] = trim($fullname);
                    $playerscore[$pnc] = round($tournamentfinishscore, 1);
                }
            }
            arsort($playerscore);
            $topten = 0;
            ?>
            <div class="tableFixHead autoHeight">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Player</th>
                        <th>Tournament Finishes Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($playerscore as $topscore) {
                        $key = array_search(strval($topscore), $playerscore);
                        if ($key == false) {
                            $name = "Missing";
                        } else {
                            $name = trim($playername[$key]);
                        }
                        $topten += 1;
                        ?>
                        <tr>

                            <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $key ?>"><?php echo prettyname($name) ?></a></td>
                            <td><?php echo round($topscore,0) ?></td>

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


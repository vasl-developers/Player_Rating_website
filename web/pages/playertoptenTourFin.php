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
            $sql3 = "SELECT TournamentFinishScore, PlayerNameCode FROM tournamentfinishesscore ORDER BY TournamentFinishScore desc";
            if ($stmt3 = $mysqli->prepare($sql3)) {
                $stmt3->execute();
                $stmt3->bind_result($tourfinishscore, $pnc);
                while ($row = $stmt3->fetch()) {
                    $playerScore[$pnc][0] = $pnc;
                    $playerScore[$pnc][1] = $tourfinishscore;
                }
            }
            $stmt3->close();
            $topten=0;
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
                    foreach ($playerScore as $topscore) {
                        $pnc = $topscore[0];
                        $topten += 1;
                        $sql4 = "Select Fullname FROM players where Player_Namecode =?";
                        if ($stmt4 = $mysqli->prepare($sql4)) {
                            $stmt4->bind_param("s", $pnc);
                            $stmt4->execute();
                            $stmt4->store_result();
                            $stmt4->bind_result($Fname);
                            while ($row = $stmt4->fetch()) {
                                $name = $Fname;
                            }
                        }
                        $stmt4->close();
                        ?>
                        <tr>
                            <td><a class="content" href="../pages/tablePlayerGameResults.php?playercode=<?php echo $pnc ?>"><?php echo prettyname($name) ?></a></td>
                            <td><?php echo round($topscore[1],0) ?></td>
                        </tr>
                        <?php
                        if ($topten == 15) {break;}
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$mysqli->close();
include_once "web/include/footer.php";
?>
</body>
</html>


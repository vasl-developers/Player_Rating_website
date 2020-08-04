<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php
include_once("web/include/navbar.htm");
include_once("web/pages/connection.php");
include_once("web/pages/functions.php");
?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">
            <h2>Player versus Player Matchups</h2>
            <br>
            <?php
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            if (isset($_POST['submit'])) {
                $player1pnc = $_POST['player1id'];
                $player2pnc = $_POST['player2id'];
                $player1name=""; $player2name=""; $totalgames =0; $player1win=0; $player2win=0; $player1loss=0; $player2loss=0; $player1draw=0; $player2draw=0;
                $sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Round_No, Scenario_ID, Tournament_ID, player1.Fullname, player1.Hidden, player2.Fullname, player2.Hidden from match_results
                    INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode
                    INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode
                    where (Player1_Namecode=? and Player2_Namecode=?) or (Player2_Namecode=? and Player1_Namecode=?) order by RoundDate";

                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("ssss", $player1pnc, $player2pnc, $player1pnc, $player2pnc);
                    $stmt->execute();
                    $stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $roundNo, $scenario, $tournament, $player1, $play1hide, $player2,  $play2hide);

                    ?>

                    <table class="table table-sm table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Player</th>
                            <th>Att/Def</th>
                            <th>Al/Ax</th>
                            <th>Result</th>
                            <th>Player</th>
                            <th>Att/Def</th>
                            <th>Al/Ax</th>
                            <th></th>
                            <th>Scenario</th>
                            <th>Tournament</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = $stmt->fetch()) {
                            $totalgames++;
                            if ($p1Code == $player1pnc){
                                $player1name= prettyName($player1);
                                $player2name= prettyName($player2);

                            } else {

                            }
                        ?>
                            <tr>
                                <td>
                                    <?php
                                    if($play1hide==1){$player1="Hidden";}
                                    if($play2hide==1){$player2="Hidden";}
                                    if($player1=="Hidden") {
                                    ?>
                                        <?php echo prettyName($player1) ?>
                                    <?php
                                    } else {
                                    ?>
                                        <a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p1Code ?>"><?php echo prettyName($player1) ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?php echo $p1AttDef ?></td>
                                <td><?php echo $p1AlliAxis ?></td>
                                <?php
                                if(trim(strtolower($p1Result))=="draw"){
                                    $p1Result = "draws";
                                    $player1draw++; $player2draw++;
                                } elseif(trim(strtolower($p1Result))=="lost" || trim(strtolower($p1Result))=="loss" ) {
                                    $p1Result = "loses to";
                                    if ($p1Code == $player1pnc) {
                                        $player1loss++;
                                        $player2win++;
                                    } else {
                                        $player1win++;
                                        $player2loss++;
                                    }
                                } else {
                                    $p1Result = "beats";
                                    if ($p1Code == $player1pnc) {
                                        $player1win++;
                                        $player2loss++;
                                    }else {
                                        $player1loss++;
                                        $player2win++;
                                    }
                                }
                                ?>
                                <td><?php echo $p1Result ?></td>
                                <td>
                                    <?php
                                    if($player2=="Hidden") {
                                    ?>
                                        <?php echo prettyName($player2) ?>
                                    <?php
                                    } else {
                                    ?>
                                        <a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p2Code ?>"><?php echo prettyName($player2) ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?php echo $p2AttDef ?></td>
                                <td><?php echo $p2AlliAxis ?></td>
                                <td><?php if($scenario > '') echo 'in'; ?></td>
                                <td>
                                    <a class="content" href="tableScenarioresults.php?scenarioid=<?php echo $scenario ?>"><?php echo $scenario ?></a>
                                </td>
                                <td>
                                    <a class="content" href="tableGameResultsforTournament.php?tournamentid=<?php echo $tournament ?>"><?php echo $tournament ?></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php

                } else {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">

        <h2>Statistical Summary</h2>
        <br>

                <div class="row col-2 col-1">
                    <div class="col-sm">All Games:</div>
                    <div class="col-sm"><?php echo $totalgames?></div>
                </div>

                <div class="row bg-light text-black" >
                    <div class="col-sm"><?php echo $player1name ?></div>
                    <div class="col-sm">Wins:</div>
                    <div class="col-sm"><?php echo $player1win?></div>
                    <div class="col-sm">Losses:</div>
                    <div class="col-sm"><?php echo $player1loss ?></div>
                    <div class="col-sm">Win %:</div>
                    <div class="col-sm"><?php echo number_format(($player1win/($player1win + $player1loss)), 2, '.', '') ?></div>
                    <div class="col-sm">Draws:</div>
                    <div class="col-sm"><?php echo $player1draw?></div>
                </div>
                <br>
                <div class="row bg-light text-black" >
                    <div class="col-sm"><?php echo $player2name ?></div>
                    <div class="col-sm">Wins:</div>
                    <div class="col-sm"><?php echo $player2win?></div>
                    <div class="col-sm">Losses:</div>
                    <div class="col-sm"><?php echo $player2loss ?></div>
                    <div class="col-sm">Win %:</div>
                    <div class="col-sm"><?php echo number_format(($player2win/($player2win + $player2loss)), 2, '.', '') ?></div>
                    <div class="col-sm">Draws:</div>
                    <div class="col-sm"><?php echo $player2draw?></div>
                </div>


        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<?php
$mysqli->close();
?>
</body>
</html>
<?PHP



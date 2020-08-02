<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">

            <?php
            include_once("web/pages/connection.php");
            include_once("web/pages/functions.php");
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            ?>
            <h2>List of Games by Scenario included in ASL Player Ratings</h2>
            <?php
            $passscenarioid = $_GET['scenarioid'];  //scenario is passed from showgameresultstable.php and XX.php

            $sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Round_Date, Scenario_ID, Tournament_ID, player1.Fullname, player1.Player_Namecode, player2.Fullname, player2.Player_Namecode from match_results INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode WHERE Scenario_ID=? ORDER BY Round_Date";

            if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $passscenarioid);
            $stmt->execute();
            $stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $roundDate, $scenario, $tourId, $player1, $player1code, $player2, $player2code);
            ?>
            <h3>Scenario: <?php echo $passscenarioid ?><a class="content" href="<?php echo $ROOT; ?>web/pages/createscenariostatistics.php?scenarioid=<?php echo $passscenarioid?>" style="float:right;">See Statistical Summary</a></h3>
            <div class="tableFixHead">
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
                        <th>Date</th>
                        <th>Tourney</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = $stmt->fetch()) {
                        $player1 = ucwords(strtolower(trim($player1)), " .-\t\r\n\f\v");
                        $player2 = ucwords(strtolower(trim($player2)), " .-\t\r\n\f\v");
                        if(trim(strtolower($p1Result))=="draw"){
                            $p1Result = "draws";
                        } elseif(trim(strtolower($p1Result))=="lost") {
                            $p1Result = "loses to";
                        } else {
                            $p1Result = "beats";
                        }
                        $linktext="";
                        if ($scenario != null){$linktext = "in";}
                        ?>
                        <tr>
                            <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p1Code ?>"><?php echo prettyName($player1) ?></a></td>
                            <td><?php echo $p1AttDef ?></td>
                            <td><?php echo $p1AlliAxis ?></td>
                            <td><?php echo $p1Result ?></td>
                            <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p2Code ?>"><?php echo prettyName($player2) ?></a></td>
                            <td><?php echo $p2AttDef ?></td>
                            <td><?php echo $p2AlliAxis ?></td>
                            <td class="date"><?php echo $roundDate ?></td>
                            <td>
                                <a class="content" href="<?php echo $ROOT; ?>web/pages/tableGameResultsforTournament.php?tournamentid=<?php echo $tourId ?>"><?php echo $tourId ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                    }
                    $stmt->close();
                    $mysqli->close();
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>


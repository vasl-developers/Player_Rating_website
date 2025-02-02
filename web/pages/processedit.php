<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">
            <h2>Submit a Correction</h2>
            <br>
            <h3>4. Save</h3>
            <br>
            <?php
            include("web/pages/connection.php");
            $mysqli = new mysqli($host, $username, $password, $database);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            $mysqli->set_charset("utf8");

            if (isset($_POST['tour_name'])) {
                if (isset($_POST['submit'])) {
                $nameerror = false;
                $player1namecode = getnamecode($_POST['fpnc']);
                if ($player1namecode == null) {
                    echo "<li> No Player Name Match found for " . $_POST['fpnc'] . "</li>";
                    $nameerror = true;
                }
                $player2namecode = getnamecode($_POST['spnc']);
                if ($player2namecode == null) {
                    echo "<li> No Player Name Match found for " . $_POST['spnc'] . "</li>";
                    $nameerror = true;
                }
                if ($nameerror){
                    echo "<li> To correct, edit player name and resubmit</li>";
                    echo "<li> To use new player, quit correction, add new player, then retry correction</li>";
                } else {
                    $tourname = $_POST['tour_name'];
                    $roundno = $_POST['roundno'];
                    $rounddate = $_POST['round_date'];
                    $scenid = $_POST['scenid'];
                    if($_POST['fattdef'] == "Choose...") {$player1attdef = null;} else {$player1attdef=$_POST['fattdef'];}
                    if($_POST['falax']== "Choose...") {$player1AlliesAxis = null;} else {$player1AlliesAxis=$_POST['falax'];}
                    $player1result = $_POST['fresult'];
                    if($_POST['sattdef'] == "Choose...") {$player2attdef = null;} else {$player2attdef=$_POST['sattdef'];}
                    if($_POST['salax']== "Choose...") {$player2AlliesAxis = null;} else {$player2AlliesAxis=$_POST['salax'];}
                    $player2result = $_POST['sresult'];
                    $roundrealdate = date("Y-m-d", strtotime($rounddate));
                    $gameid = $_POST['gameid'];
                    if($gameid==0){  // insert new game
                        if ($stmt = $mysqli->prepare("INSERT INTO match_results (Tournament_ID, Round_No, Round_Date, Scenario_ID, Player1_Namecode,
                            Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, RoundDate) VALUES
                            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                            /* bind the parameters*/
                            $stmt->bind_param("sisssssssssss", $tourname, $roundno, $rounddate, $scenid, $player1namecode, $player1attdef,
                                $player1AlliesAxis, $player1result, $player2namecode, $player2attdef, $player2AlliesAxis, $player2result, $roundrealdate);
                            $stmt->execute();
                            echo "<br>";
                            echo "<li><strong>New Game Added to Database</strong></li>";
                            $txt= date("Y-m-d"). " New game (" . $_POST['fpnc'] . " vs " . $_POST['spnc'] . ") added to match_results" . "\n";
                            include("web/pages/storetransactionstofile.php");
                        } else {
                            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                    } else {   // update existing game
                        if ($stmt = $mysqli->prepare("Update match_results SET Tournament_ID=?, Round_No=?, Round_Date=?, Scenario_ID=?, Player1_Namecode=?,
                            Player1_AttDef=?, Player1_AlliesAxis=?, Player1_Result=?, Player2_Namecode=?, Player2_AttDef=?, Player2_AlliesAxis=?, Player2_Result=?, RoundDate=? WHERE Match_ID=?")) {
                                $stmt->bind_param("sisssssssssssi", $tourname, $roundno, $rounddate, $scenid, $player1namecode, $player1attdef,
                                    $player1AlliesAxis, $player1result, $player2namecode, $player2attdef, $player2AlliesAxis, $player2result, $roundrealdate, $gameid);
                                $stmt->execute();
                                echo "<br>";
                                echo "<li><strong>Game Correction Added to Database</strong></li>";
                                $txt= date("Y-m-d"). " Existing game (" . $_POST['fpnc'] . " vs " . $_POST['spnc'] . ") updated in match_results" . "\n";
                                include("web/pages/storetransactionstofile.php");
                        } else {
                                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                    }

                    }
                } elseif (isset($_POST['delete'])) {
                    $gameid = $_POST['gameid'];
                    $sql = "DELETE from match_results Where Match_ID=?";
                    if (!($stmt = $mysqli->prepare($sql ))) {
                        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        exit();
                    }
                    $stmt->bind_param("i", $gameid);
                    $stmt->execute();
                    echo "<br>";
                    echo "<li><strong>Game Instance Deleted from Database</strong></li>";
                    $txt= date("Y-m-d"). " Existing game (" . $_POST['fpnc'] . " vs " . $_POST['spnc'] . ") deleted from match_results" . "\n";
                    include("web/pages/storetransactionstofile.php");
                }
            } else {

            }
            $mysqli->close();
            ?>
        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>
<?php
function getnamecode($playername){
    global $mysqli;

    $playername=trim($playername);

    if($stmt6= $mysqli->prepare("SELECT players.Fullname, players.Player_Namecode FROM players")) {
        $stmt6->execute();
        $stmt6->bind_result($testplayername, $testpnc);
        while ($row6 = $stmt6->fetch()) {
            if (strcasecmp(trim($testplayername), trim($playername)) == 0) {
                $stmt6->close();
                return $testpnc;
            }
        }
    }
    $stmt6->close();
    return null;
}
?>

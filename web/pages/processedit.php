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
        <?php include_once("web/include/left-sidebar.php"); ?>
        <div class="main-content col-md-8">
            <h1>Submit a Correction</h1>
            <br>
            <p><strong>4. Save</p></strong></p>
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
                            echo "<li><strong>Game Correction Added to Database</strong></li>";
                        } else {
                            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                    } else {   // update existing game
                        if ($stmt = $mysqli->prepare("Update match_results SET Tournament_ID=?, Round_No=?, Round_Date=?, Scenario_ID=?, Player1_Namecode=?,
                            Player1_AttDef=?, Player1_AlliesAxis=?, Player1_Result=?, Player2_Namecode=?, Player2_AttDef=?, Player2_AlliesAxis=?, Player2_Result=?, RoundDate=? WHERE id=?")) {
                                $stmt->bind_param("sisssssssssssi", $tourname, $roundno, $rounddate, $scenid, $player1namecode, $player1attdef,
                                    $player1AlliesAxis, $player1result, $player2namecode, $player2attdef, $player2AlliesAxis, $player2result, $roundrealdate, $gameid);
                                $stmt->execute();
                                echo "<br>";
                                echo "<li><strong>Game Correction Added to Database</strong></li>";
                        } else {
                                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                    }

                }
            } else {

            }
            $mysqli->close();
            ?>
        </div>
        <?php include_once("web/include/right-sidebar.php"); ?>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
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
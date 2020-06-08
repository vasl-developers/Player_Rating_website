<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
if (isset($_POST['tournamentgame'])) {
    $gametoedit = trim($_POST["tournamentgame"]);
    $tournamenttouse = trim($_POST["showtour"]) ;
    include_once("web/pages/editgame.php");
} else {
    $mysqli->set_charset("utf8");
    // get game results for tournament
    $sql = "select Scenario_ID, Tournament_ID, match_results.id, player1.Fullname, player2.Fullname from match_results INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode WHERE Tournament_ID=? ORDER BY player1.Fullname";
    if (!($stmt = $mysqli->prepare($sql))) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $tournamenttoshow);
        $stmt->execute();
        $stmt->bind_result($scenario, $tourId, $gameid, $player1, $player2);
        ?>
        <h1>Submit a Correction</h1>
        <h2>Selected Tournament: <?php echo $tournamenttoshow; ?></h2>
        <br>
        <p><strong>2. Select a game from the Tournament Games dropdown list to edit OR choose Add Missing Game</strong></p>
        <form class="form-inline" method="post" action="gamecorrection.php" >

            <select name="tournamentgame">
                <option selected>Choose...</option>
                <option value="0">Add Missing Game</option>
            <?php
            while ($row = $stmt->fetch()) {
                $player1 = ucwords(strtolower(trim($player1)), " .-\t\r\n\f\v");
                $player2 = ucwords(strtolower(trim($player2)), " .-\t\r\n\f\v");
                ?>
                <option value="<?PHP echo $gameid; ?>"><?PHP echo $player1." vs ".$player2." - ".$scenario;?></option>
            <?php
            }
            ?>
            </select>
            <?php
            echo "<input class='input' type='hidden' name='showtour' value='{$tournamenttoshow}' />";
            ?>
            <button class="btn btn-primary pl-5" name="submit" type="submit" value="Select">Select</button>

        </form>
        <?php
    }
}
$mysqli->close();

/*


} else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

//FROM GAMECORRECTION

if (isset($_POST["tournamentgame"])) {
    $gamedata = explode(" ", ($_POST["tournamentgame"]));
    $selected_val = $gamedata[0];
    $tournamenttoshow = $gamedata[1];
}


if (isset($_POST["tournamentgame"])) {
    $gamedata = explode(" ", ($_POST["tournamentgame"]));
    $selected_val = $gamelist[$gamedata[0]];
    ?>
    <form action="" target="" method='post'>
        <?PHP
        echo "<h2>Update Game</h2>";
        echo "<hr/>";
        echo "<label>" . "Tournament Name:" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='tour_name' value='{$selected_val['Tournament_ID']}' />";
        echo "<br />";
        echo "<label>" . "Round No:" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='roundno' value='{$selected_val['Round_No']}' />";
        echo "<br />";
        echo "<label>" . "Round Date (yyyy-mm-dd):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='round_date' value='{$selected_val['Round_Date']}' />";
        echo "<br />";
        echo "<label>" . "Scenario ID:" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='scenid' value='{$selected_val['Scenario_ID']}' />";
        echo "<br />";
        echo "<label>" . "Player 1 Name:" . "</label>" . "<br />";
        $player1name = getplayername($selected_val["Player1_Namecode"]);
        echo"<input class='input' type='text' name='fpnc' value='{$player1name}' />";
        echo "<br />";
        echo "<label>" . "Player 1 Att or Def (Enter attacker or defender only):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='fattdef' value='{$selected_val["Player1_AttDef"]}' />";
        echo "<br />";
        echo "<label>" . "Player 1 Allies or Axis (Enter Allies or Axis only):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='falax' value='{$selected_val["Player1_AlliesAxis"]}' />";
        echo "<br />";
        echo "<label>" . "Player 1 Result (win/lost/draw):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='fresult' value='{$selected_val["Player1_Result"]}' />";
        echo "<br />";
        echo "<label>" . "Player 2 Name:" . "</label>" . "<br />";
        $player2name = getplayername($selected_val["Player2_Namecode"]);
        echo"<input class='input' type='text' name='spnc' value='{$player2name}' />";
        echo "<br />";
        echo "<label>" . "Player 2 Att or Def (Enter attacker or defender only):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='sattdef' value='{$selected_val["Player2_AttDef"]}' />";
        echo "<br />";
        echo "<label>" . "Player 2 Allies or Axis (Enter Allies or Axis only):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='salax' value='{$selected_val["Player2_AlliesAxis"]}' />";
        echo "<br />";
        echo "<label>" . "Player 2 Result (win/lost/draw):" . "</label>" . "<br />";
        echo"<input class='input' type='text' name='sresult' value='{$selected_val["Player2_Result"]}' />";
        echo "<br />";
        echo"<input class='input' type='hidden' name='gameid' value='{$selected_val['id']}' />";
        echo "<br />";
        echo "<input class='submit' type='submit' name='input_submit' value='Update Game' />";
    echo "</form>";
} elseif (isset($_POST['input_submit'])) {
    $nameerror = false;
    $player1namecode = getnamecode($_POST['fpnc']);
    if ($player1namecode == null) {
        echo "<li>" . "No Player Name Match found for " . $_POST['fpnc'] . "</li>";
        $nameerror = true;
    }
    $player2namecode = getnamecode($_POST['spnc']);
    if ($player2namecode == null) {
        echo "<li>" . "No Player Name Match found for " . $_POST['spnc'] . "</li>";
        $nameerror = true;
    }
    if ($nameerror){
        echo "<li>" . "To correct, edit player name and resubmit" . "</li>";
        echo "<li>" . "To use new player, quit correction, add new player, then retry correction" . "</li>";
    } else {
        $tourname = $_POST['tour_name'];
        $roundno = $_POST['roundno'];
        $rounddate = $_POST['round_date'];
        $scenid = $_POST['scenid'];
        $player1attdef = $_POST['fattdef'];
        $player1AlliesAxis = $_POST['falax'];
        $player1result = $_POST['fresult'];
        $player2attdef = $_POST['sattdef'];
        $player2AlliesAxis = $_POST['salax'];
        $player2result = $_POST['sresult'];
        $roundrealdate = date("Y-m-d", strtotime($rounddate));
        //$roundrealdate = $rounddate;
        $gameid = $_POST['gameid'];

        if (!($stmt = $mysqli->prepare("Update match_results SET Tournament_ID=?, Round_No=?, Round_Date=?, Scenario_ID=?, Player1_Namecode=?,
            Player1_AttDef=?, Player1_AlliesAxis=?, Player1_Result=?, Player2_Namecode=?, Player2_AttDef=?, Player2_AlliesAxis=?, Player2_Result=?, RoundDate=? WHERE id=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $stmt->bind_param("sisssssssssssi", $tourname, $roundno, $rounddate, $scenid, $player1namecode, $player1attdef,
            $player1AlliesAxis, $player1result, $player2namecode, $player2attdef, $player2AlliesAxis, $player2AlliesAxis, $roundrealdate, $gameid);
                $stmt->execute();
        echo "<li>" . "Game Correction Added to Database" . "</li>";
    }
} else {
?>

<?PHP
}
$mysqli->close();

// Defining function
function getplayername($playercode){
global $mysqli;

$playercode=trim($playercode);
if($stmt5=$mysqli->prepare("SELECT players.Fullname FROM players WHERE Player_Namecode=?")) {
    $stmt5->bind_param("s", $playercode);
    $stmt5->execute();
    $stmt5->bind_result($pname);
    while ($row = $stmt5->fetch()) {
        $stmt5->close();
        return $pname;
    }
}
$stmt5->close();
return null;
}
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
}*/
?>

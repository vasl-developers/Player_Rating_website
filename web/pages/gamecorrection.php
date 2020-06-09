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
?>

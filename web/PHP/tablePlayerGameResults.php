<?php
include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
</head>
<body>
<div id="content">
    <h1>List of All Games Played by Player included in ASL Player Ratings</h1>

    <?php
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("Select * FROM match_results WHERE Player1_Namecode=? OR Player2_Namecode=? ORDER BY Round_Date"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
    /* bind the parameters*/
    $passplayercode = $_GET['playercode'];  //playercode is passed from tableGameResultsforTournaments.php
    $stmt->bind_param("ss", $passplayercode, $passplayercode);
    // execute
    $stmt->execute();
    $result=$stmt->get_result(); // get the mysqli result
    ?>
    <h2>Player: </h2>
    <table class="table table-condensed table-striped">
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
            </tr>
        </thead>
        <tbody>
        <?php
        while($newrow = $result->fetch_assoc()) {
            $playercode1 = trim($newrow["Player1_Namecode"]);
            $player1 = getplayername(trim($newrow["Player1_Namecode"]));
            $p1attdef = trim($newrow["Player1_AttDef"]);
            $p1alax = trim($newrow["Player1_AlliesAxis"]);
            $playercode2 = trim($newrow["Player2_Namecode"]);
            $player2 = getplayername(trim($newrow["Player2_Namecode"]));
            $p2attdef = trim($newrow["Player2_AttDef"]);
            $p2alax = trim($newrow["Player2_AlliesAxis"]);
            $scenario = trim($newrow["Scenario_ID"]);

            ?>
            <tr>
                <td><?php echo $player1 ?></td>
                <td><?php echo $p1attdef ?></td>
                <td><?php echo $p1alax ?></td>
                <td>beats</td>
                <td><?php echo $player2 ?></td>
                <td><?php echo $p2attdef ?></td>
                <td><?php echo $p2alax ?></td>
                <td>in</td>
                <td><?php echo $scenario ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php

    $stmt->close();
    $mysqli->close();
    ?>

</div>
<?php
// Defining function
function getplayername($playercode){
    global $mysqli;
    $stmt5 = $mysqli->prepare("SELECT * FROM players WHERE Player_Namecode=?");
    $stmt5->bind_param("s", $playercode);
    // execute
    $stmt5->execute();
    $result5 = $stmt5->get_result(); // get the mysqli result
    while ($row5 = $result5->fetch_assoc()) {
        if ($row5["Hidden"] == true) {
            $pname = "Hidden";
        } else {
            $pname = $row5["Fullname"];
        }
    }
    return $pname;
}

?>
</body>
</html>

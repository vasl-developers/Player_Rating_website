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
    <h1>List of All Games Played in a Tournament included in ASL Player Ratings</h1>
    <p>To view Game-by-Game results for a particular Player, click on the link.</p>

    <?php
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("Select * FROM match_results WHERE Tournament_ID=? ORDER BY Round_No"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
    /* bind the parameters*/
    $passtournamentid=$_GET['tournamentid']; //$tournamentid is passed from tableTournamentsbyYear.php
    $stmt->bind_param("s", $passtournamentid);
    // execute
    $stmt->execute();
    $result=$stmt->get_result(); // get the mysqli result
    $firstarray = [];
    $secondarray=[];
    while($newrow = $result->fetch_assoc()){
        $firstarray[]=$newrow;
        $secondarray[]=$newrow;
    }
    $previousroundno="";
    foreach ($firstarray as $row) {
        if ($row["Round_No"] != $previousroundno) {
            $previousroundno = $row["Round_No"];

            ?>
            <h1>Round <?php echo $row["Round_No"]?></h1>
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
                foreach ($secondarray as $row1) {
                    if ($row1["Round_No"]==$previousroundno) {
                        $playercode=trim($row1["Player1_Namecode"]);
                        $stmt3=$mysqli->prepare("SELECT * FROM players WHERE Player_Namecode=?");
                        $stmt3->bind_param("s", $playercode);
                        // execute
                        $stmt3->execute();
                        $result3=$stmt3->get_result(); // get the mysqli result
                        while ($row3 = $result3->fetch_assoc()) {
                            if ($row3["Hidden"]==true){
                                $pname = "Hidden";
                            } else {
                                $pname = $row3["First_Name"] . " " . $row3["Surname"];
                            }
                        }
                        $player1 = $pname; //getplayername(trim($row1["Player1_Namecode"]));
                        $p1attdef = trim($row1["Player1_AttDef"]);
                        $p1alax = trim($row1["Player1_AlliesAxis"]);
                        $playercode=trim($row1["Player2_Namecode"]);
                        $stmt4=$mysqli->prepare("SELECT * FROM players WHERE Player_Namecode=?");
                        $stmt4->bind_param("s", $playercode);
                        // execute
                        $stmt4->execute();
                        $result4=$stmt4->get_result(); // get the mysqli result
                        while ($row4 = $result4->fetch_assoc()) {
                            if ($row4["Hidden"]==true){
                                $pname = "Hidden";
                            } else {
                                $pname = $row4["First_Name"] . " " . $row4["Surname"];
                            }
                        }
                        $player2 =$pname; //getplayername(trim($row1["Player2_Namecode"]));
                        $p2attdef = trim($row1["Player2_AttDef"]);
                        $p2alax  = trim($row1["Player2_AlliesAxis"]);
                        $scenario = trim($row1["Scenario_ID"])
                        ?>
                        <tr>
                            <td><?php echo $player1?></td>
                            <td><?php echo $p1attdef?></td>
                            <td><?php echo $p1alax?></td>
                            <td>beats</td>
                            <td><?php echo $player2?></td>
                            <td><?php echo $p2attdef?></td>
                            <td><?php echo $p2alax?></td>
                            <td>in</td>
                            <td><?php echo $scenario?></td>
                        </tr>
                        <?PHP
                    }
                }
                ?>
                </tbody>
            </table>
            <?php
        }
    }
    $stmt->close();
    $mysqli->close();
    ?>

</div>
<?php
// Defining function
function getplayername($playercode){
    global $host, $username, $password, $database;
    $mysqli2 = mysqli_connect($host, $username, $password, $database);
    $mysqli2->set_charset("utf8");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $playercode=trim($playercode);
    $querySQL = "SELECT * FROM players WHERE Player_Namecode=$playercode";
    $queryResult=$mysqli2->query($querySQL);
    while ($row2 = $queryResult->fetch_assoc()) {
        /* Prepared statement, stage 1: prepare
        $stmt2 = $mysqli2->prepare("SELECT * FROM players WHERE Player_Namecode=?") {
            echo "Prepare failed: (" . $mysqli2->errno . ") " . $mysqli2->error;        }
        /* bind the parameters
        $stmt2->bind_param("s", $playercode);
        // execute
        $stmt2->execute();
        $result1=$stmt2->get_result(); // get the mysqli result
        $row2 = $result1->fetch_assoc(); // fetch data
           */
        $pname = $row2["First_Name"] . " " . $row2["Surname"];
        return $pname;
    }
}

?>
</body>
</html>
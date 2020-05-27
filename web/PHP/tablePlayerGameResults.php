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
    <meta http-equiv="Cache-Control" content="max-age=86400"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="web/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="web/favicon.ico" type="image/x-icon" />
    <link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="web/css/vasl_styles.css" rel="stylesheet" />
    <script type="text/javascript">

    </script>
</head>
<body>
<div id="content">
    <h1>List of All Games Played by Player included in ASL Player Ratings</h1>

    <?php
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("Select * FROM match_results WHERE Player1_Namecode=? OR Player2_Namecode=? ORDER BY RoundDate"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
    /* bind the parameters*/
    $passplayercode = $_GET['playercode'];  //playercode is passed from tableGameResultsforTournaments.php and tableRankedActive.php
    $stmt->bind_param("ss", $passplayercode, $passplayercode);
    // execute
    $stmt->execute();
    $result=$stmt->get_result(); // get the mysqli result
    ?>
    <h2>Player: <?PHP echo getplayername($passplayercode)?></h2>
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
                <th>Date</th>
                <th>Tour. ID</th>
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
            $rounddate = trim($newrow["Round_Date"]);
            $tourid= trim($newrow["Tournament_ID"]);

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
                <td><?php echo $rounddate ?></td>
                <td><?php echo $tourid ?></td>
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

<footer></footer>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#navbar").load("web/include/navbar.htm", function() {
            $("ul.navbar-nav li.homepage").addClass("active");
        });

        $("footer").load("web/include/copyright2.html");

        $("#link2").load("web/include/link2.html", function() {
            $("a.content").click(function(e) {
                e.stopPropagation();
                $("div.main-content").load($(this).data("href"));
            });
        });
    });

</body>
</html>

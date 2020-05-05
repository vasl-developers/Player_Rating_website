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
    <h1>List of All Tournaments Included in ASL Player Ratings</h1>
    <p>This list includes all Tournaments submitted to ASL Player Ratings . . . . It includes results added as of {a date}</p>
    <p>To view Game-by-Game results for a particular Tournament, click on the link.</p>

    <?php
    $tournament_id="";
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("Select * FROM tournaments ORDER BY Year_Held"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
   /* execute - no binding needed as no params */
    $stmt->execute();
    $result=$stmt->get_result(); // get the mysqli result
    $firstarray = [];
    $secondarray=[];
    while($newrow = $result->fetch_assoc()){
        $firstarray[]=$newrow;
        $secondarray[]=$newrow;
    }
    $previousyear=0;
    foreach ($firstarray as $row) {
        if ($row["Year_Held"] > $previousyear) {
            $previousyear = $row["Year_Held"];

    ?>
            <h1><?php echo $row["Year_Held"]?></h1>
            <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Tournament</th>
                    <th>Location</th>
                    <th>Game Results</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($secondarray as $row1) {
                if ($row1["Year_Held"]==$previousyear) {
                    $month = trim($row1["Month_Held"]);
                    $tournamentname = trim($row1["Base_Name"]);
                    $location = trim($row1["Location_CityOrRegion"]) . ', ' . trim($row1["Location_Country"]);
                    $tournament_id  = trim($row1["Tournament_id"]);
                    ?>
                    <tr>
                        <td><?php echo $month?></td>
                        <td><?php echo $tournamentname?></td>
                        <td><?php echo $location?></td>
                        <td class="top">
                            <p><a class="content" href="web/PHP/tableGameResultsforTournament.php?tournamentid=<?php echo $tournament_id?>"><?php echo $tournament_id?></a></p>
                        </td>
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
</body>
</html>

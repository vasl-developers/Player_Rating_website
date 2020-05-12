<?php
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

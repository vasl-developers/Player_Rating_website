<?php

include("connection.php");
$link = mysqli_connect($host, $username, $password, $database);

$query = "select count(*) from usn";
$result = mysqli_query($link, $query);

$owners = mysqli_num_rows($result);
?>
<html>
<body>
    <h2>This is a test</h2>

    <p>This is a test display of the Person table from AREA, showing all of the table or a Query Result</p>

    <p> There are <b><?php echo $owners ?></b> players in the table </p>
</body>
</html>

/*
    <table cellPadding=3 border=1 style="border:gold 2px outset;width:100%;">
        <thead>
        <tr>
            <th>Owner</th>
            <th>Date Won</th>
            <th>Scenario</th>
            <th>Opponent</th>
            <th>Side Played</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $query = "select * from rock order by date_won desc, id desc";
        $result = mysqli_query($link, $query);
        mysqli_close($link);

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {

            $owner = trim($row["owner"]) . "&nbsp;";
            $date  = trim($row["date_won"]) . "&nbsp;";
            $scen  = trim($row["scenario"]) . "&nbsp;";
            $opp   = trim($row["opponent"]) . "&nbsp;";
            $side  = trim($row["side_played"]) . "&nbsp;";

            if ($i++ == 0) {
                echo "<tr style='background-color:gold'>";
            } else {
                echo "<tr>";
            }

            echo "  <td>" . $owner . "</td>";
            echo "  <td>" . $date  . "</td>";
            echo "  <td>" . $scen  . "</td>";
            echo "  <td>" . $opp   . "</td>";
            echo "  <td>" . $side  . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    The connection.php file that gets include has the database information:
*/
<?php
$username="<user>";
$password="<password>";
$host="localhost";
$database="area_schema";
?>
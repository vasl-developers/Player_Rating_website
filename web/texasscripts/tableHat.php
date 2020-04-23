<?php

include("connection.php");
$link = mysqli_connect($host, $username, $password, $database);

$query = "select count(*) from rock group by owner";
$result = mysqli_query($link, $query);

$owners = mysqli_num_rows($result);
?>
    <h2>The Texas Hat</h2>
    <img src="http://www.texas-asl.com/images/hat.bmp" class="photo" style="float:left;margin:1em;">

    <p>The Hat is a somewhat mythical object being passed around and around among Texas ASL Club members. The current Hat Owner has won
        the most recent Hat game, thereby claiming the right to don the proverbial Hat.</p>

    <p>Jay Harms has had the longest streak, with 16 Hat wins. Zeb Doyle has worn the Hat twice, though both times were less than a full
        day. Rob Burton has won the Hat three times, but has never been able to hang on to the dang thing. There have been <b><?php echo
            $owners ?></b> Hat wearers. Roy was the original Hat guy. See the chart to answer "Why?".</p>

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

<?php
$username="<user>";
$password="<password>";
$host="";
$database="";
?>
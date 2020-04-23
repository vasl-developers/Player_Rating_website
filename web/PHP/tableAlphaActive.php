<?php
include("connection.php");
$link = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "select count(*) as players from usn";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
?>
<html>

<body>
<h2>Alphabetical Listing of Currently Active ASL Players</h2>
<p>This list includes all active players, meaning they have . . . . It includes results added as of adddate</p>


<table cellPadding=3 border=1 style="border:black 2px outset;xwidth:100%;">
    <thead>
    <tr>
        <th>Name</th>
        <th>Country</th>
        <th>Current Rating</th>
        <th>Highest Rating</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $query = "select * from usn";
    $result = mysqli_query($link, $query);
    mysqli_close($link);

    while ($row = mysqli_fetch_assoc($result)) {
        $player = trim($row['Last Name'] . ', ' . $row['First Name']);
        $country  = trim($row['Country']) . "&nbsp;";

        echo "<tr><td>$player</td><td>$country</td></tr>";
    }
    ?>
    </tbody>
</table>
</body>

</html>
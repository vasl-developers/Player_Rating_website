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
<h2>Ranked List of All ASL Players</h2>

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
    $query = "select * from usn order by `Last Name` ASC";
    $result = mysqli_query($link, $query);
    mysqli_close($link);

    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $player = trim($row['Last Name'] . ', ' . $row['First Name']);
        $country  = trim($row['Country']) . "&nbsp;";
        $id = $row['Id'] - 0;

        echo "<tr><td>$player</td><td>$country</td><td>$id</td><td>$i</td></tr>";

        $i++;
    }
    ?>
    </tbody>
</table>
</body>
</html>

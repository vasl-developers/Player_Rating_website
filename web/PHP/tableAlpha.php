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
  <h2>This is a test</h2>
  <p>This is a test display of the Person table from AREA, showing all of the table or a Query Result</p>
  <p> There are <b><?php echo $row['players'] ?></b> players in the table.</p>

  <table cellPadding=3 border=1 style="border:black 2px outset;xwidth:100%;">
    <thead>
      <tr>
        <th>Name</th>
        <th>Country</th>
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
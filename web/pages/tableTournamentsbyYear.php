<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<?php include_once "web/include/navbar.htm";?>
<div class="home container-fluid">
  <div class="row">
    <div class="main-content col-md-10 offset-md-1">
<?php
include_once "web/pages/connection.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
?>
  <h2>List of Tournaments included in ASL Player Ratings</h2>
  <br>
  <p>This list includes Tournaments submitted to ASL Player Ratings.</p>
  <p>To view Game-by-Game results for a particular Tournament, click on the link.</p>
  <div class="tableFixHead">
  <?php
$sql = "select Year_Held,Month_Held,Date_Held,Base_Name,Location_CityOrRegion,Location_Country,Tournament_id from tournaments order by Year_Held desc, Date_Held asc";

if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($year, $month, $date, $name, $location, $country, $tournament);
	?>
    <table class="table table-sm table-striped table-hover">
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
$prevYear = '';
	while ($row = $stmt->fetch()) {
		$month = trim($month);
		$name = trim($name);
		$location = trim($location) . ', ' . trim($country);
		$tournament = trim($tournament);
		if ($year != $prevYear) {
			$prevYear = $year;
			?>
          <tr>
            <td colspan=4 class="headline">Year <?php echo $year ?></td>
          </tr>
        <?php
}
		?>
          <tr>
            <td><?php echo $month ?></td>
            <td><?php echo $name ?></td>
            <td><?php echo $location ?></td>
            <td>
              <a class="content" href="<?php echo $ROOT; ?>web/pages/tableGameResultsforTournament.php?tournamentid=<?php echo $tournament ?>" title="<?php echo $date ?>"><?php echo $tournament ?></a>
            </td>
          </tr>
        <?php
}
	?>
      </tbody>
    </table>
      <?php
}
$stmt->close();
$mysqli->close();
?>
      </div>
    </div>
  </div>
</div>
<?php include_once "web/include/footer.php";?>
</body>
</html>

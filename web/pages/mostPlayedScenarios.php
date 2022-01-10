<html lang="en">
<?php
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
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");
?>
        <h2>List of scenarios by number of times played in tournaments</h2>
        <p>To view Game-by-Game results for a particular scenario, click on the link.</p>
        <?php
$sql = "select m.Scenario_ID, count(*) ct, s.name from match_results m
        LEFT OUTER JOIN scenarios s ON m.Scenario_ID=s.scenario_id
        where m.Scenario_ID > ''
        group by m.Scenario_ID order by ct desc, m.Scenario_ID";
if ($stmt = $mysqli->prepare($sql)) {
	$stmt->execute();
	$stmt->bind_result($scenarioId, $count, $name);
	?>
            <div class="tableFixHead">
              <table class="table table-sm table-striped table-hover">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Id</th>
                    <th>Games Played</th>
                  </tr>
                </thead>
                <tbody>
    <?php
      $i = 1;
      while ($row = $stmt->fetch()) {
		    $name = trim($name);
		?>
                      <tr>
                        <td><?php echo $i++ . '. ' . $name ?></td>
                        <td><a class="content" href="tableScenarioresults.php?scenarioid=<?php echo $scenarioId ?>"><?php echo $scenarioId ?></a></td>
                        <td><?php echo $count ?></td>
                      </tr>
    <?php
}
	?>
                </tbody>
              </table>
            </div>
<?php
}
$stmt->close();
$mysqli->close();
?>
    </div>
  </div>
</div>
<?php include_once "web/include/footer.php";?>
</body>
</html>

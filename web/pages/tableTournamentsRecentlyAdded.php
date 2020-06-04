<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <?php include_once("web/include/left-sidebar.php"); ?>
    <div class="main-content col-md-8">

<?php
include_once("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$mysqli->set_charset("utf8");
?>
<h2>List of Tournaments Added To ASL Player Ratings in the past 3 months</h2>
<p>To view Game-by-Game results for a particular Tournament, click on the link.</p>
<?php

// $sql = "select Year_Held,Month_Held,Base_Name,Location_CityOrRegion,Location_Country,Tournament_id from tournaments where Date_Added between date_sub(current_date(), interval 15 month) and current_date() order by date(Date_Held) asc";

// temp - show all tournaments since none are in last 3 months
$sql = "select Year_Held,Month_Held,Date_Held,Base_Name,Location_CityOrRegion,Location_Country,Tournament_id from tournaments order by Year_Held, Month_Held asc";

if ($stmt = $mysqli->prepare($sql)) {
  $stmt->execute();
  $stmt->bind_result($year,$month,$date,$name,$location,$country,$tournament);
?>
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
            <td class="top">
              <p><a href="tableGameResultsforTournament.php?tournamentid=<?php echo $tournament ?>" title="<?php echo $date ?>">
                <?php echo $tournament ?></a>
              </p>
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
    <?php include_once("web/include/right-sidebar.php"); ?>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
</body>
</html>

<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
  <div class="row">
    <div class="main-content col-md-10 offset-md-1">
        <?php
        include_once("web/pages/connection.php");
        $mysqli = new mysqli($host, $username, $password, $database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        $mysqli->set_charset("utf8");
        ?>
        <h2>List of Tournaments Added To ASL Player Ratings in the past 3 months</h2>
        <p>To view Game-by-Game results for a particular Tournament, click on the link.</p>
        <?php
        $sql = "select Year_Held,Month_Held,Date_Held,Base_Name,Location_CityOrRegion,Location_Country,Tournament_id from tournaments where Date_Added between date_sub(current_date(), interval 3 month) and current_date() order by Year_Held desc, date(Date_Held) asc";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($year,$month,$date,$name,$location,$country,$tournament);
            ?>
            <div class="tableFixHead">
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
                                <p><a href="tableGameResultsforTournament.php?tournamentid=<?php echo $tournament ?>" title="<?php echo $date ?>"><?php echo $tournament ?></a></p>
                            </td>
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
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

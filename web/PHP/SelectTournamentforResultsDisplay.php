<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title></title>
</head>
<body>
<div class="maindiv">
  <div class="divA">
    <div class="title">
      <h2>List of All Games Played in a Tournament included in ASL Player Ratings</h2>
      <p>To select a tournament, select from the List. You can scroll or type the Name, including the Year </p>
    </div>
    <div class="divB">
      <div class="divD">
        <?php
        if (isset($_GET['tournamentid'])) {
            $tournamenttoshow = trim($_GET["tournamentid"]);
            include("../PHP/showgameresultstable.php");
        } else {
            include("../PHP/connection.php");
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            $sql = "select Base_Name, Year_Held, Tournament_id from tournaments";
            $result = mysqli_query($mysqli, $sql);

            $tournamentlist = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $tournamentlist[] = $row;
            }
            $mysqli->close();
            ?>
            <p>Type or Select Tournament to View Game Results:</p>
            <form method="get" action="web/PHP/SelectTournamentforResultsDisplay.php">
                <input type="text" list="tournaments" name="tournamentid">
                <datalist id="tournaments" autocomplete="on">
                    <?php
                    foreach ($tournamentlist as $tournament) {
                    ?>
                    <option value="<?php echo $tournament["Tournament_id"];?>"><?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?></option>
                    <?php
                    }
                    ?>
                </datalist>
                <input type="submit" value="Select" />
            </form>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>

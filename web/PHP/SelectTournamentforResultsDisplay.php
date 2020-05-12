<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title></title>
    <link href="style.css" rel="stylesheet" type="text/css">
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
                <?PHP
                include ("../PHP/connection.php");
                $mysqli = mysqli_connect($host, $username, $password, $database);
                $mysqli->set_charset("utf8");
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
                /* Prepared statement, stage 1: prepare */
                if (!($stmt = $mysqli->prepare("Select Base_Name, Year_Held, Tournament_id FROM tournaments"))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                // execute - no parameters
                $stmt->execute();
                $result=$stmt->get_result(); // get the mysqli result
                $tournamentlist = [];
                while($newrow = $result->fetch_assoc()) {
                    $tournamentlist[] = $newrow;
                }
                $stmt->close();
                ?>
                <p>Type or Select Tournament to View Game Results:</p>
                <form method="post" action="web/PHP/SelectTournamentforResultsDisplay.php">
                    <input type="text" list="tournaments" name="tournament">
                    <datalist id="tournaments" autocomplete="on">
                        <?php
                        foreach ($tournamentlist as $tournament) {
                            ?>
                            <option value="<?PHP echo $tournament["Tournament_id"];?>"><?PHP echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?></option>
                        <?PHP
                        }
                        ?>
                    </datalist>
                    <input type="submit" value="Select">
                </form>
                <?php
                if (isset($_POST['tournament'])) {
                    $tournamenttoshow=trim($_POST["tournament"]);
                }
                include("../PHP/showgameresultstable.php");
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>

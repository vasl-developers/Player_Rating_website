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
        <div class="main-content col-md-10 offset-md-1">
            <?php
            include_once("web/pages/connection.php");
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            ?>
            <h2>Player versus Player Matchups</h2>
            <br>
            <?php
            $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden, player_ratings.ELO, player_ratings.HighWaterMark from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
            $result1 = mysqli_query($mysqli, $sql);
            $result2 = mysqli_query($mysqli, $sql);
            ?>
            <form class="form-inline col-5" method="post" action="viewmatchupresults.php">
                <div class="input-group">

                    <select class="form-select" id="player1id" name="player1id" autocomplete="on">
                        <option selected>Choose First Player...</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result1)) {
                            if ($row["Hidden"] == 0 ) {
                                $name = ucwords(strtolower(trim($row["Fullname"])), " .-\t\r\n\f\v");
                                $player_namecode = $row["Player_Namecode"];
                        ?>
                                <option value="<?php echo $player_namecode ?>"><?php echo $name ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>

                <p>Vs. </p>

                <div class="input-group">
                    <select class="form-select" id="player2id" name="player2id" autocomplete="on">
                        <option selected>Choose Second Player...</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result2)) {
                            if ($row["Hidden"] == 0 ) {
                                $name = ucwords(strtolower(trim($row["Fullname"])), " .-\t\r\n\f\v");
                                $player_namecode = $row["Player_Namecode"];
                        ?>
                                <option value="<?php echo $player_namecode ?>"><?php echo $name ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>

                </div>
                <br>

                <button class="btn btn-primary" name="submit" type="submit" value="Select">Show Matchup Results</button>
            </form>

            <?php $mysqli->close(); ?>
        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" >
    $(document).ready(function() {
        $('#playername').focus();
    });
</script>
</body>
</html>

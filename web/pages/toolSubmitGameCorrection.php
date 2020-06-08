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

            if (isset($_GET['tournamentid'])) {
                $tournamenttoshow = trim($_GET["tournamentid"]);
                include_once("gamecorrection.php");
            } else {
                $sql = "select Base_Name, Year_Held, Tournament_id from tournaments";
                $result = mysqli_query($mysqli, $sql);
                $tournamentlist = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $tournamentlist[] = $row;
                }
                $mysqli->close();
                ?>
                <h1>Submit a Correction</h1>
                <p>Use this page to submit a correction to a Tournament Result</p>
                <p>1. Select the Tournament from the Tournaments dropdown list</p>
                <p>2. Select a game from the Tournament Games dropdown list OR choose Add Missing Game</p>
                <p>3. Enter revised or new information</p>
                <p>4. Save</p>
                <br>
                <br>
                <p><strong>1. Select the Tournament from the Tournaments dropdown list</strong></p>
                <form class="form-inline" method="get" action="toolSubmitGameCorrection.php">
                    <div class="input-group-lg">
                        <select class="select " id="tournamentid" name="tournamentid" autocomplete="on" >
                            <option selected>Choose...</option>
                            <?php
                            foreach ($tournamentlist as $tournament) {
                                ?>
                                <option value="<?php echo $tournament["Tournament_id"];?>"><?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <button class="btn btn-primary pl-5" name="submit" type="submit" value="Select">Select</button>
                    </div>
                </form>
            <?php
            }
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


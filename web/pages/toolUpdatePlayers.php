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
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            if (isset($_POST['inputsearch'])) {
                $pname = $_POST['playername'];
                $sql = "select players.Fullname, players.Country, players.Player_Namecode, players.Hidden from players WHERE Fullname=?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $pname);
                    $stmt->execute();
                    $stmt->bind_result($name, $country, $nameCode, $hidden);
                    $row = $stmt->fetch();
                    if(!($row==null)){
                          ?>
                          <form action="" target="" method='post'>
                          <?PHP
                            echo "<h1>Update Player</h1>";
                            echo "<br>";
                            echo"<input class='input' type='hidden' name='pnc' value='$nameCode'>";
                            echo "<label><strong>" . "Name:" . "</strong></label>";
                            echo "<br>";
                            echo"<input class='input' type='text' name='fname' value='$name'>";
                            echo "<br>";
                            echo "<label><strong>" . "Country:" . "</strong></label>";
                            echo "<br>";
                            echo"<input class='input' type='text' name='country' value='$country'>";
                            echo "<br>";
                            echo "<label><strong>" . "Hidden (true/false)" . ":" . "</strong></label>";
                            echo "<br>";
                            if($hidden==1){
                                $hidstatus = "true";
                            } else {
                                $hidstatus = "false";
                            }
                            echo"<input class='input' type='text' name='hid' value='$hidstatus'>";
                            echo "<br>";
                            echo "<br>";
                            echo "<button class='btn btn-primary pl-5' name='inputsubmit' type='submit' value='Update'>Update</button>";
                          echo "</form>";
                    } else {
                        ?>
                        <h1>Update Player or Add new Player</h1>
                        <br>
                        <form action="toolUpdatePlayers.php" method="post" target="">
                          <div class="col-xs-4">
                            <label for="playertohide">Player to Update:</label>
                            <input tyclass="form-control" pe="text" id="playername" name="playername" /><br />
                            <button class="btn btn-primary pl-5" name="inputsearch" type="submit" value="Search">Search</button>
                          </div>
                        </form>
                        <br>
                        <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
                        <br>
                        <br>
                        <?php
                        echo "No match found. Re-enter name or Add A New Player";
                    }


                    $stmt->close();
                } else {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
            } elseif (isset($_POST['inputsubmit'])) {
                        $fullname = $_POST['fname'];
                        // need to parse and update surname and firstname
                        $last_name = (strpos($fullname, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $fullname);
                        $first_name = trim( preg_replace('#'.$last_name.'#', '', $fullname ) );
                        $country = $_POST['country'];
                        $playernamecode = $_POST['pnc'];
                        $hidden = $_POST['hid'];
                        if (trim($hidden=="true")){
                            $hide=1;
                        } else {
                            $hide=0;
                        }
                        /* Prepared statement, stage 1: prepare */
                        if (!($stmt = $mysqli->prepare("UPDATE players SET Surname=?, First_Name=?, Fullname=?, Country=?, Hidden=? WHERE Player_Namecode=?"))) {
                            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                        /* bind the parameters*/
                        $stmt->bind_param("ssssis", $last_name, $first_name, $fullname,  $country, $hide, $playernamecode);
                        /* set parameters and execute */
                        $stmt->execute();
                        $stmt->close();
                        ?>
                        <h1>Update Player or Add new Player</h1>
                        <br>
                        <form action="toolUpdatePlayers.php" method="post" target="">
                          <div class="col-xs-4">
                            <label for="playertohide">Player to Update:</label>
                            <input type="text" name="playername" class="form-control" id="playername" /><br />
                            <button class="btn btn-primary pl-5" name="inputsearch" type="submit" value="Search">Search</button>
                          </div>
                        </form>
                        <br>
                        <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
                        <br>
                        <?php
                        echo $fullname . ' ' . "updated in Database";
            } else {
                ?>
                <h1>Update Player or Add new Player</h1>
                <br>
                <form action="toolUpdatePlayers.php" method="post" target="">
                  <div class="col-xs-4">
                    <label for="playertohide">Player to Update:</label>
                    <input type="text" name="playername" class="form-control" id="playername" />
                    <br>
                    <button class="btn btn-primary pl-5" name="inputsearch" type="submit" value="Search">Search</button>
                    <br>
                    <br>
                    <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
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
<?php
$mysqli->close();
?>
</body>
</html>

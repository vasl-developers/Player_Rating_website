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
            $mysqli = mysqli_connect($host, $username, $password, $database);
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            if (isset($_POST['inputsearch'])) {
                $pname = $_POST['playername'];
                $sql = "select players.Fullname, players.First_Name, players.Surname, players.Country, players.Player_Namecode, players.Hidden from players WHERE Fullname=?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("s", $pname);
                    $stmt->execute();
                    $stmt->bind_result($name, $first_name, $surname, $country, $nameCode, $hidden);
                    $row = $stmt->fetch();
                    if(!($row==null)){
                          ?>
                          <form action="" target="" method='post'>
                          <?PHP
                            echo "<h2>Update Player</h2>";
                            echo "<br>";
                            echo"<input class='input' type='hidden' name='pnc' value='$nameCode'>";
                            echo "<label><strong>" . "First Name:" . "</strong></label>";
                            echo "<br>";
                            echo"<input class='input' type='text' name='firstname' value='$first_name'>";
                            echo "<br>";
                            echo "<label><strong>" . "Surname:" . "</strong></label>";
                            echo "<br>";
                            echo"<input class='input' type='text' name='surname' value='$surname'>";
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
                            echo "<button class='btn btn-primary' name='inputsubmit' type='submit' value='Update'>Update</button>";
                            echo "<br>";
                            echo "<br>";
                            echo "<button class='btn btn-primary' name='inputdelete' type='submit' value='Delete'>Delete</button>";
                          echo "</form>";
                    } else {
                        ?>
                        <h2>Update or Add Player</h2>
                        <br>
                        <form action="toolUpdatePlayers.php" method="post" target="">
                          <div class="col-xs-4">
                            <label for="playername">Player to Update:</label>
                            <input tyclass="form-control" pe="text" id="playername" name="playername" /><br />
                            <button class="btn btn-primary" name="inputsearch" type="submit" value="Search">Search1</button>
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
            } elseif (isset($_POST['inputdelete'])) {
                $deletepnc = $_POST['pnc'];
                $fullname= $_POST['firstname'] . " " . $_POST['surname'];
                $sql = "DELETE from players Where Player_Namecode=?";
                if (!($stmt = $mysqli->prepare($sql ))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    exit();
                }
                $stmt->bind_param("s", $deletepnc);
                $stmt->execute();
                $stmt->close();
                $sql = "DELETE from player_ratings Where Player1_Namecode=?";
                if (!($stmt = $mysqli->prepare($sql ))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;                                 exit();
                }
                $stmt->bind_param("s", $deletepnc);
                $stmt->execute();
                $stmt->close();
                echo "<br>";
                echo "<li><strong>" . $fullname . " Deleted from Database</strong></li>";
            } elseif (isset($_POST['inputsubmit'])) {
                        $first_name = $_POST['firstname'];
                        $surname=$_POST['surname'];
                        $fullname= $first_name . " " . $surname;
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
                        $stmt->bind_param("ssssis", $surname, $first_name, $fullname,  $country, $hide, $playernamecode);
                        /* set parameters and execute */
                        $stmt->execute();
                        $stmt->close();
                        ?>
                        <h2>Update or Add Player</h2>
                        <br>
                        <form action="toolUpdatePlayers.php" method="post" target="">
                          <div class="col-md-6">
                            <div class="input-group mb-5">
                              <input type="text" class="form-control" name="playername" class="form-control" id="playername" placeholder="Player's Name" aria-label="Player to Update" aria-describedby="inputsearch">
                              <button class="btn btn-primary" type="search" name="inputsearch" id="inputsearch">Search</button>
                            </div>

                            <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
                          </div>


                          <div class="col-xs-4">
                            <input type="text" name="playername" class="form-control" id="playername" /><br />
                            <button class="btn btn-primary" name="inputsearch" type="submit" value="Search">Search2</button>
                          </div>
                        </form>
                        <br>
                        <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
                        <br>
                        <?php
                        echo $fullname . ' ' . "updated in Database";
            } else {
                ?>
                <h2>Update or Add Player</h2>
                <br>
                <form action="toolUpdatePlayers.php" method="post" target="">
                  <div class="col-md-6">
                    <div class="input-group mb-5">
                      <input type="text" class="form-control" name="playername" class="form-control" id="playername" placeholder="Player's Name" aria-label="Player to Update" aria-describedby="inputsearch">
                      <button class="btn btn-primary" type="search" name="inputsearch" id="inputsearch">Search</button>
                    </div>

                    <a id="addplayer" class="track btn btn-large btn-primary" target="" href="addnewplayers.php">Add a new Player</a>
                  </div>
                </form>
                <?php
            }
            ?>

        </div>
    </div>
</div>
<?php $mysqli->close(); ?>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" >
$(document).ready(function() {
  $('#playername').focus();
});
</script>
</body>
</html>

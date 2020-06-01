<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="maindiv">
    <div class="divA">
        <div class="title">
            <h2>Update Player</h2>
        </div>
        <div class="divB">
            <div class="divD">
                <form action="web/PHP/UpdateTables/updateplayers.php" method="post">
                    Name: <input type="text" name="playername" /><br />
                    <input type="submit" name="input_search" value="Search" />
                </form>
                <a id="addplayer" class="track btn btn-large btn-primary" target="" href="web/PHP/UPdateTables/addnewplayers.php">Add a New Player</a>

                <?php
                include ("../connection.php");
                $mysqli = mysqli_connect($host, $username, $password, $database);
                $mysqli->set_charset("utf8");
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
                if (isset($_POST['input_search'])) {
                    $pname = $_POST['playername'];
                     /* Prepared statement, stage 1: prepare */
                    $stmt = $mysqli->prepare("SELECT * FROM players WHERE Fullname=?");
                    /* bind the parameters*/
                    $stmt->bind_param("s", $pname);
                    $stmt->execute();
                    $result = $stmt->get_result(); // get the mysqli result
                    $row = $result->fetch_assoc(); // fetch data
                    /* Prepared statement, stage 1: prepare */
                    $stmt1 = $mysqli->prepare("SELECT * FROM players WHERE Player_Namecode=?");
                    /* bind the parameters*/
                    $stmt1->bind_param("s", $row['Player_Namecode']);
                    $stmt1->execute();
                    $result = $stmt1->get_result(); // get the mysqli result
                    $row1 = $result->fetch_assoc(); // fetch data
                    ?>
                    <form action="" target="" method='post'>
                    <?PHP
                    echo "<h2>Update Player</h2>";
                    echo "<hr/>";
                    echo"<input class='input' type='hidden' name='pnc' value='{$row1['Player_Namecode']}' />";
                    echo "<br />";
                    echo "<label>" . "Name:" . "</label>" . "<br />";
                    echo"<input class='input' type='text' name='fname' value='{$row1['Fullname']}' />";
                    echo "<br />";
                    echo "<label>" . "Country:" . "</label>" . "<br />";
                    echo"<input class='input' type='text' name='country' value='{$row1['Country']}' />";
                    echo "<br />";
                    echo "<label>" . "Hidden (true/false)" . ":" . "</label>" . "<br />";
                    if($row1['Hidden']==1){
                        $hidstatus = "true";
                    } else {
                        $hidstatus = "false";
                    }
                    echo"<input class='input' type='text' name='hid' value='{$hidstatus}' />";
                    echo "<br />";
                    echo "<input class='submit' type='submit' name='input_submit' value='update' />";
                    echo "</form>";
                    $stmt1->close();
                    $stmt->close();

                }
                if (isset($_POST['input_submit'])) {
                        $fullname = $_POST['fname'];
                        $country = $_POST['country'];
                        $playernamecode = $_POST['pnc'];
                        $hidden = $_POST['hid'];
                        if (trim($hidden=="true")){
                            $hide=1;
                        } else {
                            $hide=0;
                        }
                        /* Prepared statement, stage 1: prepare */
                        if (!($stmt = $mysqli->prepare("UPDATE players SET Fullname=?, Country=?, Hidden=? WHERE Player_Namecode=?"))) {
                            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                        }
                        /* bind the parameters*/
                        $stmt->bind_param("ssis", $fullname,  $country, $hide, $playernamecode);
                        /* set parameters and execute */
                        $stmt->execute();
                        $stmt->close();
                        echo $fullname . ' ' . "updated in Database";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php
$mysqli->close();
?>
</body>
</html>


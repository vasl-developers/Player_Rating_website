<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<?php
include("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<div class="maindiv">
    <div class="divA">
        <?PHP
        if (isset($_POST['input_submit'])) {
            // first get playernamecodes in case we need to generate new ones
            if (!($stmt = $mysqli->prepare("SELECT Player_Namecode FROM players"))) {
                $stmt->execute();
                $stmt->bind_result($namecode);
                // put in array
                $i = 0;
                while ($row = $stmt->fetch()) {
                    $playernamecodelist{$i} = $namecode;
                    $i++;
                }
                $firstname = $_POST['fname'];
                $surname = $_POST['surname'];
                $fullname = $firstname . " " . $surname;
                $country = $_POST['country'];
                $playernamecode = getnewnamecode($surname, $firstname, $playernamecodelist);
                $hidden = $_POST['hid'];
                if (trim($hidden == "true")) {
                    $hide = 1;
                } else {
                    $hide = 0;
                }
                $stmt->close();
            }
            /* Prepared statement, stage 1: prepare */
            if (!($stmt = $mysqli->prepare("INSERT INTO players (Surname, First_Name, Fullname, Country,
                     Player_Namecode, Hidden) VALUES (?, ?, ?, ?, ?, ?)"))) {
                        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            /* bind the parameters*/
            $stmt->bind_param("sssssi", $surname, $firstname, $fullname, $country, $playernamecode, $hide);
            /* execute */
            $stmt->execute();
            $stmt->close();
            echo $fullname . ' ' . "added to Database";
        } else {
            ?>
            <form action="" target="" method='post'>
            <?PHP
            echo "<h2>Add New Player</h2>";
            echo "<hr/>";
            echo"<input class='input' type='hidden' name='pnc' value='' />";
            echo "<br />";
            echo "<label>" . "First Name:" . "</label>" . "<br />";
            echo"<input class='input' type='text' name='fname' value='' />";
            echo "<br />";
            echo "<label>" . "Surname:" . "</label>" . "<br />";
            echo"<input class='input' type='text' name='surname' value='' />";
            echo "<br />";
            echo "<label>" . "Country:" . "</label>" . "<br />";
            echo"<input class='input' type='text' name='country' value='' />";
            echo "<br />";
            echo "<label>" . "Hidden (true/false)" . ":" . "</label>" . "<br />";

            echo"<input class='input' type='text' name='hid' value='false' />";
            echo "<br />";
            echo "<input class='submit' type='submit' name='input_submit' value='update' />";
        }
        ?>
        </form>
    </div>
    <?PHP
    function getnewnamecode($last_name, $first_name, $playernamecode){
        //Get the length of the string.
        $length = strlen($last_name);
        //Get the array index of the last character.
        $index = $length - 1;
        //create new name code
        $numval=1;
        $newnc = $last_name[0] . $last_name[$index] . $first_name[0] ;
        $newncfirst = $newnc.$numval;
        // compare with existing playernamecodes
        do {
            $nomatch=true;
            foreach ($playernamecode as $pnc) {
                if ($newncfirst == $pnc){
                    $nomatch=false;
                    $numval+=1;
                    $newncfirst = $newnc.$numval;
                    break;
                }
            }
        } while ($nomatch==false);
        return $newncfirst;
    }
    ?>
</body>
</html>

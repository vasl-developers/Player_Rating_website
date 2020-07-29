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

    if (isset($_POST['inputsubmit'])) {
        // first get playernamecodes in case we need to generate new ones
        if ($stmt = $mysqli->prepare("SELECT Player_Namecode FROM players")) {
            $stmt->execute();
            $stmt->bind_result($namecode);
            // put in array
            while ($row = $stmt->fetch()) {
                $playernamecodelist[] = $namecode;
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
        } else {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
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
        // add to player_ratings table; set initial values
        $country=""; $active=1; $provisional=0; $firstdate=null; $lastdate=null; $hwm=0; $elo=0; $games=0; $wins=0; $gaa=0; $waa=0; $gad=0; $wad=0; $gaax=0; $waax=0;$gaal=0; $waal=0; $currentstreak=0; $higheststreak=0;
        if ($stmt6 = $mysqli->prepare("INSERT INTO player_ratings (Player1_Namecode, Fullname, Country, Active, Provisional, FirstDate, LastDate, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker, GamesAsDefender, WinsAsDefender,GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak, HighestStreak)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            /* bind the parameters*/
            $stmt6->bind_param("sssiissddiiiiiiiiiiii", $playernamecode, $fullname, $country, $active, $provisional, $firstdate, $lastdate, $hwm, $elo, $games, $wins, $gaa, $waa, $gad, $wad, $gaax, $waax, $gaal, $waal, $currentstreak, $higheststreak);
            /* execute */
            $stmt6->execute();
            $stmt6->close();
        } else {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        echo $fullname . ' ' . "added to Database";
    } else {
        ?>
        <form action="" target="" method='post'>
        <?PHP
        echo "<h2>Add New Player</h2>";
        echo "<br>";
        echo "<input class='input' type='hidden' name='pnc' value=''>";
        echo "<label><strong>" . "First Name:" . "</strong></label>";
        echo "<br>";
        echo "<input class='input' type='text' name='fname' value=''>";
        echo "<br>";
        echo "<label><strong>" . "Surname:" . "</strong></label>";
        echo "<br>";
        echo "<input class='input' type='text' name='surname' value=''>";
        echo "<br>";
        echo "<label><strong>" . "Country:" . "</strong></label>";
        echo "<br>";
        echo"<input class='input' type='text' name='country' value=''>";
        echo "<br>";
        echo "<label><strong>" . "Hidden (true/false)" . ":" . "</strong></label>";
        echo "<br>";
        echo"<input class='input' type='text' name='hid' value='false' />";
        echo "<br>";
        echo "<br>";
        echo "<button class='btn btn-primary pl-5' name='inputsubmit' type='submit' value='Update'>Add New</button>";
        echo "</form>";
    }
    ?>
    </div>
  </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<?php
$mysqli->close();
?>
</body>
</html>
<?PHP
function getnewnamecode($surname, $firstname, $playernamecodelist){
  //Get the length of the string.
  $length = strlen($surname);
  //Get the array index of the last character.
  $index = $length - 1;
  //create new name code
  $numval=1;
  $newnc = $surname[0] . $surname[$index] . $firstname[0] ;
  $newncfirst = $newnc.$numval;
  // compare with existing playernamecodes
  do {
    $nomatch=true;
    foreach ($playernamecodelist as $pnc) {
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

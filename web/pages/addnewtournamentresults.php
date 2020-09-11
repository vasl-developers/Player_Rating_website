<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$mysqli->set_charset("utf8");

$target_dir = "../Data/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //$fileToUpload passed from toolUploadTournamentResults
$uploadOK = 1;

if (isset($_FILES["fileToUpload"]["error"])) {
    if ($_FILES["fileToUpload"]["error"] > 0) {
        echo "Error: No File Selected";
        $uploadOK = 0;
    } else {
        $allowed = array("xls", "xlsx", "csv");
        $filename = $_FILES["fileToUpload"]["name"];
        $filetype = $_FILES["fileToUpload"]["type"];
        $filesize = $_FILES["fileToUpload"]["size"];

        $ext = trim(pathinfo($filename, PATHINFO_EXTENSION));
        if($ext != "xls" && $ext != "xlsx" && $ext != "csv"){
            //if (!(array_key_exists(trim($ext), $allowed))) {
            $uploadOK = 0;
            echo "Error: This file is not an accepted file type.";

        } else {
            $maxsize = 200000 * 60;
            if ($filesize > $maxsize) {
                echo "Error: File size is larger than the allowed 10MB limit." . "</br></br>";
                $uploadOK=0;
            } elseif (isset($allowed) && in_array($ext, $allowed)) {
                // Check whether file exists before uploading it
                if (file_exists($target_dir . $_FILES["fileToUpload"]["name"])) {
                    $uploadOK = 0;
                    echo $_FILES["fileToUpload"]["name"] . " already exists. Go back and choose another file or rename the original.</br></br>";
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $uploadinfo = $_FILES["fileToUpload"]["name"] . " was uploaded successfully.</br>";
                        $txt= date("Y-m-d") . " " . "Game Results file uploaded: " . $_FILES["fileToUpload"]["name"] . "\n";
                        include("web/pages/storetransactionstofile.php");
                    } else {
                        echo "Error: There was a problem uploading the file - please try again.";
                        $uploadOK=0;
                    }
                }
            }
        }
    }
} else {
    echo "Error: Invalid parameters - something is very very very wrong with this upload.";
    $uploadOK = 0;
}


if ($uploadOK==1) {
    // first get playernamecodes in case we need to generate new ones
    $playernamecodelist[] = getplayernamecodelist();
    // if csv format
    $row = 1;
    $newtournamentid ="";
    echo "<h2>Upload New Tournament Results</h2>";
    echo "<br>";
    echo $uploadinfo;
    if (($handle = fopen($target_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (!($row == 1)) { //first row is header; ignore
                $newtournamentid = $tournid = $data[0];
                $roundno = $data[1];
                $rounddatetext = $data[2];
                $scenid = $data[3];
                $play1name = $data[4];
                $play1nc = getnamecode($play1name); // get name code
                $play1attdef = strtolower($data[5]);
                $play1alax = strtolower($data[6]);
                $play1res = strtolower($data[7]);
                $play2name = $data[8];
                $play2nc = getnamecode($play2name); // get name code
                $play2attdef = strtolower($data[9]);
                $play2alax = strtolower($data[10]);
                $play2res = strtolower($data[11]);
                $rounddatereal = $data[12];
                if ($rounddatetext == null or $rounddatetext == ""){$rounddatetext = $rounddatereal;}
                //need to check for essential data and correct format
                if ($tournid != null and $roundno != null and $rounddatereal != null
                    and $play1res != null and $play2res != null) {
                    // add more format checks
                    $rebuildpnclist=false;
                    if ($play1nc == null ){$play1nc = getnewnamecode($play1name, $playernamecodelist);}
                    if($rebuildpnclist){
                        //update pnc list to contain new players just created
                        $playernamecodelist = getplayernamecodelist();
                    }
                    if ($play2nc == null ){$play2nc = getnewnamecode($play2name, $playernamecodelist);}
                    if($rebuildpnclist){
                        //update pnc list to contain new players just created
                        $playernamecodelist = getplayernamecodelist();
                    }
                    if($play1res=="won") {
                        $play1res = "win";
                    } elseif($play1res=="loss"){
                        $play1res="lost";
                    }
                    if($play2res=="won") {
                        $play2res = "win";
                    } elseif($play2res=="loss"){
                        $play2res="lost";
                    }
                    /* Prepared statement, stage 1: prepare */
                    if (!($stmt = $mysqli->prepare("INSERT INTO match_results (Tournament_ID, Round_No, Round_Date, Scenario_ID, Player1_Namecode,
                           Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, RoundDate) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
                        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    }
                    /* bind the parameters*/
                    $stmt->bind_param("sisssssssssss", $tournid, $roundno, $rounddatetext, $scenid, $play1nc, $play1attdef,
                        $play1alax, $play1res, $play2nc, $play2attdef, $play2alax, $play2res, $rounddatereal);
                    /* execute */
                    $stmt->execute();
                    echo "New Results Added to Database. " . $play1name . " vs " . $play2name . "<br>";
                    $txt= date("Y-m-d"). " New Game Result added to match_results: " . $play1name . " vs " . $play2name . "\n";
                    include("web/pages/storetransactionstofile.php");
                } else {
                    echo "Missing Mandatory Fields. Record not uploaded to db: " . $play1name . " vs " . $play2name . "<br>";
                }
            }
            $row++;
        }
        fclose($handle);
        // new new tournament to Tournaments table
        if ($newtournamentid !=""){
            $sql = "INSERT INTO tournaments (Tournament_id, Date_Added) VALUES (?, ?)";
            if ($stmt8 = $mysqli->prepare($sql)) {
                /* bind the parameters*/
                $stmt8->bind_param("ss", $tournament_id, $date_added);
                /* set parameters and execute */
                $tournament_id = $newtournamentid;
                $date_added = date("Y-m-d");
                $stmt8->execute();
                $stmt8->close();
                echo "<br>Tournament " . $tournament_id . " created in Tournaments in Database. Go to Update Tournament Info to add additional tournament data.<br>";
                $txt= date("Y-m-d"). " Tournament " . $tournament_id . " created in tournaments" . "\n";
                include("web/pages/storetransactionstofile.php");
            } else {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
        }
        $mysqli->close();
    }
}

function getnamecode($playername){
    global $mysqli;
    $playername=trim($playername);
    if($stmt5= $mysqli->prepare("SELECT Fullname, Player_Namecode FROM players")){
        $stmt5->execute();
        $stmt5->bind_result($playerfullname, $namecode);
        while ($row = $stmt5->fetch()) {
            if (strcasecmp(trim($playerfullname), trim($playername))==0) {
                $pnc = $namecode;
                $stmt5->close();
                return $pnc;
            }
        }
    } else {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    echo "No Player Name Match found for " . $playername ."<br>";
    return null;
}
function getnewnamecode($playername, $playernamecode){

    global $rebuildpnclist;
    $names = explode(" ", $playername);
    $last_name=end($names);
    $first_name = $names[0];
    $namecount = count($names);
    for ($i = 1; $i < (count($names)-1); $i++)  {
        $first_name = $first_name . " ". $names[$i];
    }
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
    if (createnewplayer($playername, $last_name, $first_name, $newncfirst)){
     $rebuildpnclist=true;
    }
    return $newncfirst;
}
function createnewplayer($playername, $lastname, $firstname, $newnamecode){
    global $mysqli;
    $hide = 0;
    /* Prepared statement, stage 1: prepare */
    if ($stmt6 = $mysqli->prepare("INSERT INTO players (Surname, First_Name, Fullname, Player_Namecode, Hidden) VALUES (?, ?, ?, ?, ?)")) {
        /* bind the parameters*/
        $stmt6->bind_param("ssssi", $lastname, $firstname, $playername, $newnamecode, $hide);
        /* execute */
        $stmt6->execute();
        $stmt6->close();
        // add to player_ratings table; set initial values
        $country=""; $active=1; $provisional=0; $firstdate=null; $lastdate=null; $hwm=0; $elo=0; $games=0; $wins=0; $gaa=0; $waa=0; $gad=0; $wad=0; $gaax=0; $waax=0;$gaal=0; $waal=0; $currentstreak=0; $higheststreak=0;
        if ($stmt6 = $mysqli->prepare("INSERT INTO player_ratings (Player1_Namecode, Fullname, Country, Active, Provisional, FirstDate, LastDate, HighWaterMark, ELO, Games, Wins, GamesAsAttacker, WinsAsAttacker, GamesAsDefender, WinsAsDefender,GamesAsAxis, WinsAsAxis, GamesAsAllies, WinsAsAllies, CurrentStreak, HighestStreak)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            /* bind the parameters*/
            $stmt6->bind_param("sssiissddiiiiiiiiiiii", $newnamecode, $playername, $country, $active, $provisional, $firstdate, $lastdate, $hwm, $elo, $games, $wins, $gaa, $waa, $gad, $wad, $gaax, $waax, $gaal, $waal, $currentstreak, $higheststreak);
            /* execute */
            $stmt6->execute();
            $stmt6->close();

        } else {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }

        echo $playername . ' ' . " added to Players in Database<br>";
        $txt= date("Y-m-d"). $playername . " added to players" . "\n";
        include("web/pages/storetransactionstofile.php");
        return true;
    } else {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        return false;
    }
}
function getplayernamecodelist(){
    global $mysqli;
    if ($stmt7 = $mysqli->prepare("SELECT Player_Namecode FROM players")) {
        $stmt7->execute();
        $stmt7->bind_result($namecode);
        // put in array
        $i = 0;
        while ($row = $stmt7->fetch()) {
            $pnclist[] = $namecode;
            $i++;
        }
        $stmt7->close();
    } else {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    return $pnclist;
}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" >
$( document ).ready(function() {
  $('#playername').focus();
  $('#playertohide').focus();
});
</script>
</html>


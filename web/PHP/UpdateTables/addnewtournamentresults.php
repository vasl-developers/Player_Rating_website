<?php
include("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
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
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $_FILES["fileToUpload"]["name"])) {
                            echo "The file was uploaded successfully.</br></br>";
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
    }


if ($uploadOK==1) {
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
        $stmt->close();
    }
    // if csv format
    $row = 1;
    if (($handle = fopen($target_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (!($row == 1)) { //first row is header; ignore
                $tournid = $data[0];
                $roundno = $data[1];
                $rounddatetext = $data[2];
                $scenid = $data[3];
                $play1name = $data[4];
                $play1nc = getnamecode($play1name); // get name code
                $play1attdef = $data[5];
                $play1alax = $data[6];
                $play1res = $data[7];
                $play2name = $data[8];
                $play2nc = getnamecode($play2name); // get name code
                $play2attdef = $data[9];
                $play2alax = $data[10];
                $play2res = $data[11];
                $rounddatereal = $data[12];
                //need to check for essential data and correct format
                if ($tournid != null and $roundno != null and $rounddatereal != null and $scenid != null
                    and $play1res != null and $play2res != null) {
                    // add more format checks
                    if ($play1nc == null ){$play1nc = getnewnamecode($play1name, $playernamecode);}
                    if ($play2nc == null ){$play2nc = getnewnamecode($play2name, $playernamecode);}
                    /* Prepared statement, stage 1: prepare */
                    if (!($stmt = $mysqli->prepare("INSERT INTO match_results (Tournament_ID, Round_No, Round_Date, Scenario_ID, Player1_Namecode, 
                           Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, RoundDate) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
                        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                    }
                    /* bind the parameters*/
                    $stmt->bind_param("sisssssssssss", $tournid, $roundno, $rounddate, $scenid, $play1nc, $play1attdef,
                        $play1alax, $play1res, $play2nc, $play2attdef, $play2alax, $play2res, $rounddatereal);
                    /* execute */
                    $stmt->execute();
                    echo "New Results Added to Database.";
                } else {
                    echo "Missing Mandatory Fields. Record not uploaded to db.";
                }
            }
            $row++;
        }
        fclose($handle);
    }
}

function getnamecode($playername){
    global $mysqli;

    $playername=trim($playername);

    $stmt5= $mysqli->prepare("SELECT * FROM players");
    $stmt5->execute();
    $result5 = $stmt5->get_result(); // get the mysqli result
    while ($row5 = $result5->fetch_assoc()) {
        $testplayername = $row5["Fullname"];
        if (strcasecmp(trim($testplayername), trim($playername))==0) {
            $pnc = $row5["Player_Namecode"];
            return $pnc;
        }
    }
    echo "No Player Name Match found for " . $playername;
    return null;
}
function getnewnamecode($playername, $playernamecode){
    $last_name = (strpos($playername, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $playername);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $playername ) );
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

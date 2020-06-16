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
            if (mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            ?>
            <h1>Download list of all players included in ASL Player Ratings</h1>
            <p></p>
            <?php
            //set filenames
            $activefilename = "../Data/downloads/activeplayers.csv";
            $allfilename= "../Data/downloads/allplayers.csv";
            // delete existing data files
            $test = unlink ($activefilename);
            $nexttest = unlink ($allfilename);
            // get player data
            $sql = "select players.Fullname, players.Player_Namecode, players.Hidden, player_ratings.Active from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->execute();
                $stmt->bind_result($name, $nameCode, $hidden, $active);
                $i=0;
                $activearray = array();
                $allarray = array();
                while ($row = $stmt->fetch()) {
                    // put data into arrays: active/all
                    if ($active == 1) {
                        if ($hidden == 1) {
                            $activename = "Hidden";
                        } else {
                            $activename = trim($name);
                        }
                        $activenamecode = $nameCode;
                        $arrayitem = array("name" => $activename, "namecode" => $activenamecode);
                        array_push($activearray, $arrayitem);
                    }
                    if ($hidden == 1) {
                        $allname = "Hidden";
                    } else {
                        $allname = trim($name);
                    }
                    $allnamecode = $nameCode;
                    $arrayitem = array("name" => $allname, "namecode" => $allnamecode);
                    array_push($allarray, $arrayitem);
                }
            } else {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                exit();
            }

            // now format arrays - csv
            array_to_csv_download($activearray, $activefilename, ",");
            array_to_csv_download($allarray, $allfilename, ",");

            $mysqli->close();

            ?>
            <h2>Download Player List</h2>
            <p></p>
            <p>This page will allow TD's and others to download a list of all active ASL players or all ASL players.</p>
            <p>Use of the names from this list (and the accompanying namecodes) in tournament recording will enable easier, faster, and more accurate uploading of results to ASL PLayer Ratings.</p>
            <p></p>
            <p>Choose which Player List to Download</p>
            <a id="downloadactivecsv" class="track btn btn-large btn-primary" href="<?php echo $activefilename ?>">Active Players, csv format</a>
            <a id="downloadallcsv" class="track btn btn-large btn-primary" href="<?php echo $allfilename ?>">All Players, csv format</a>
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


<?php
function array_to_csv_download($array, $filename, $delimiter) {
    //header('Content-Type: application/csv');
    //header('Content-Type: application/csv; charset=UTF-8');
    $f = fopen($filename, 'a');
    $csv = "Name,Namecode\n"; //column headers
    foreach ($array as $line) {
        //fputcsv($f, $line, $delimiter);
        $csv.= $line["name"].','.$line["namecode"]."\n"; //Append data to csv
    }
    fwrite ($f,$csv);
    fclose($f);

}
?>


<?php
include("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// delete existing data files
unlink ("activeplayers.csv");
unlink ("allplayers.csv");
// get player data
$sql = "select players.Fullname, players.Player_Namecode, players.Hidden, player_ratings.Active from players INNER JOIN player_ratings ON players.Player_Namecode=player_ratings.Player1_Namecode ORDER BY players.Surname, players.First_Name";
if (!($stmt = $mysqli->prepare($sql))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;        }
$stmt->execute();
$result=$stmt->get_result(); // get the mysqli result
$stmt->close();
// put data into arrays: active/all
$i=0;
$activearray = array();
$allarray = array();
while ($row = $result->fetch_assoc()) {
    $active = $row["Active"];
    if($active ==1) { // and $row["Hidden"] == 0) {
        if ($row["Hidden"] ==1) {
            $activename = "Hidden";
        } else {
            $activename = trim($row["Fullname"]);
        }
        $activenamecode = $row["Player_Namecode"];
        $arrayitem= array("name"=>$activename, "namecode"=>$activenamecode);
        array_push ($activearray, $arrayitem);
    }
    if ($row["Hidden"] ==1) {
        $allname = "Hidden";
    } else {
        $allname = trim($row["Fullname"]);
    }
    $allnamecode = $row["Player_Namecode"];
    $arrayitem= array("name"=>$allname, "namecode"=>$allnamecode);
    array_push ($allarray, $arrayitem);
}
// now format arrays - csv
array_to_csv_download($activearray, "activeplayers.csv", ",");
array_to_csv_download($allarray, "allplayers.csv", ",");

$mysqli->close();
include ("../../Tools_Support/DownloadPlayerList.html");



function array_to_csv_download($array, $filename, $delimiter) {
    header('Content-Type: application/csv');
    header('Content-Type: application/csv; charset=UTF-8');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen($filename, 'w');
    $csv = "Name,Namecode\n"; //column headers
    foreach ($array as $line) {
        //fputcsv($f, $line, $delimiter);
        $csv.= $line["name"].','.$line["namecode"]."\n"; //Append data to csv
    }
    fwrite ($f,$csv);
    fclose($f);

}


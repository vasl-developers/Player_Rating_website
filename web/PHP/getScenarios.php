<?PHP

include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO scenarios (scenario_id, name, publication, firstplayer_sidename, 
        firstplayer_sideresult, secondplayer_sidename, secondplayer_sideresult, url) VALUES
    (?, ?, ?, ?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
/* bind the parameters*/
$stmt->bind_param("ssssssss", $scenario_id, $name, $publication, $firstp, $firstr,
    $secondp, $secondr, $url);
/* set parameters and execute */
$filename = "../Area_Data_2020_Ap/scenario-index.json";
$data = file_get_contents($filename);
$array = json_decode($data, true);
foreach ($array as $row)
{
    $scenario_id = $row["scenario_id"];
    $name = $row["name"];
    $publication = $row["publication"];
    $test = $row["results"];
    $firstp = $test[0][0];
    $firstr = $test[0][1];
    $secondp = $test[1][0];
    $secondr = $test[1][1];
    $url = $row["url"];
    $stmt->execute();

    /*
    $sql = "INSERT INTO scenarios (scenario_id, name, publication, firstplayer_sidename, 
        firstplayer_sideresult, secondplayer_sidename, secondplayer_sideresult, url) VALUES
    ('".$row["scenario_id"]."', '".$row["name"]."', '".$row["publication"]."', '$firstp',
     '$firstr', '$secondp', '$secondr', '".$row["url"]."')";
    mysqli_query($mysqli, $sql);
    */
}
$stmt->close();
$mysqli->close();
echo "Scenario Data Inserted";

?>
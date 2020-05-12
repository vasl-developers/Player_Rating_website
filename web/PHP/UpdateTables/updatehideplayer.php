<?PHP
include ("../connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if(isset($_POST['formHYD']) && $_POST['formHYD'] == 'Yes') {
    $pname = trim($_POST['pname']);
    // validate player name
    /* Prepared statement, prepare */
    $stmt = $mysqli->prepare("SELECT * FROM players WHERE Fullname=?");
    /* bind the parameters*/
    $stmt->bind_param("s", $pname);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $row = $result->fetch_assoc(); // fetch data
    if ($row == null){
        echo "Invalid Player Name. Try again.";
        $stmt->close();
    } else {
        $stmt->close();
        // process hide
        /* Prepared statement, prepare */
        if (!($stmt1 = $mysqli->prepare("UPDATE players SET Hidden=? WHERE Fullname=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* bind the parameters*/
        $hide=1;
        $stmt1->bind_param("ss", $hide,  $pname);
        /* execute */
        if($stmt1->execute()) {
            $stmt1->close();
            echo $pname . ' ' . "updated in Database";
        }
    }
}


?>


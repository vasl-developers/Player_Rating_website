<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
include_once "web/pages/functions.php";
?>
<body>
<?php include_once "web/include/navbar.htm";?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">
            <h2>Update a Tournament</h2>
            <br>
            <h3>3. Save</h3>
            <br>
            <?php
include "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");
$tourname = $_POST['tour_name'];
$monthheld = $_POST['MonthHeld'];
$yearheld = $_POST['YearHeld'];
$dateheld = $_POST['DateHeld'];
$locationcorr = $_POST['LocationCorR'];
$locationcountry = $_POST['LocationCountry'];
$tourtype = $_POST['TourType'];
$iterationname = $_POST['IterationName'];
$winner = $_POST['Winner'];
$winnernamecode = getnamecode($winner);
$secondplace = $_POST['SecondPlace'];
$secondnamecode = getnamecode($secondplace);
$thirdplace = $_POST['ThirdPlace'];
$thirdnamecode = getnamecode($thirdplace);
$tourid = $_POST['TourID'];

if (isset($_POST['submit'])) {
	// update existing tournament
	if ($stmt = $mysqli->prepare("Update tournaments SET Base_Name=?, Month_Held=?, Year_Held=?, Date_Held=?, Tournament_ID=?,
                        Location_CityOrRegion=?, Location_Country=?, Tour_Type=?, Iteration_Name=?, Winner1=?, Winner2=?, Winner3=? WHERE Tournament_ID=?")) {
		$stmt->bind_param("ssissssssssss", $tourname, $monthheld, $yearheld, $dateheld, $tourid, $locationcorr, $locationcountry,
			$tourtype, $iterationname, $winnernamecode, $secondnamecode, $thirdnamecode, $tourid);
		$stmt->execute();
		echo "<br>";
		echo $tourname . " " . "<li><strong>Tournament Update Added to Database</strong></li>";
		$txt = date("Y-m-d") . " " . $tourname . "(" . $tourid . ") updated in tournaments" . "\n";
		include "web/pages/storetransactionstofile.php";
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

} elseif (isset($_POST['AddNew'])) {
	$dateadded = date("Y/m/d");
	// add new tournament to db
	if ($stmt = $mysqli->prepare("INSERT INTO tournaments (Base_Name, Month_Held, Year_Held, Date_Held, Tournament_ID,
                        Location_CityOrRegion, Location_Country, Tour_Type, Iteration_Name, Winner1, Winner2, Winner3, Date_Added)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		/* bind the parameters*/
		$stmt->bind_param("ssissssssssss", $tourname, $monthheld, $yearheld, $dateheld, $tourid, $locationcorr, $locationcountry,
			$tourtype, $iterationname, $winnernamecode, $secondnamecode, $thirdnamecode, $dateadded);
		/* execute */
		$stmt->execute();
		echo $tourname . " " . "<li><strong>Tournament Added to Database</strong></li>";
		$txt = date("Y-m-d") . " " . $tourname . " added to tournaments" . "\n";
		include "web/pages/storetransactionstofile.php";
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		return false;
	}
}
$mysqli->close();
?>
        </div>
    </div>
</div>
<?php include_once "web/include/footer.php";?>
</body>
</html>
<?php

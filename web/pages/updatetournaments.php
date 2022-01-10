<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
if (isset($_POST['submit']) || isset($_POST['AddNew'])) {
	include_once "web/pages/processtournament.php";
} else if (isset($_POST['Delete'])) {
	$deletetour = $_POST['TourID'];
	$tourname = $_POST['tour_name'];
	$sql = "DELETE from tournaments Where Tournament_id=?";
	if (!($stmt = $mysqli->prepare($sql))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		exit();
	}
	$stmt->bind_param("s", $deletetour);
	$stmt->execute();
	$stmt->close();
	echo "<br>";
	echo "<li><strong>" . $tourname . " Deleted from Database</strong></li>";
	$txt = date("Y-m-d") . " " . $tourname . " deleted from players" . "\n";
	include "web/pages/storetransactionstofile.php";
} else {
	$mysqli->set_charset("utf8");
	if ($tournamenttoshow == "AddNew") {
		$basename = "";
		$monthheld = "";
		$yearheld = "";
		$dateheld = "";
		$tourId = "";
		$locationCorR = "";
		$locationcountry = "";
		$tourtype = "";
		$iterationname = "";
		$winner1 = "";
		$winner2 = "";
		$winner3 = "";
		$dateadded = date("Y/m/d");
	} else {
		// get tournament
		$sql = "select Base_Name, Month_Held, Year_Held, Date_Held, Tournament_ID, Location_CityOrRegion, Location_Country, Tour_type, Iteration_Name, Winner1, Winner2, Winner3, Date_Added from tournaments WHERE Tournament_ID=?";
		if (!($stmt = $mysqli->prepare($sql))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;

		} else {
			$stmt->bind_param("s", $tournamenttoshow);
			$stmt->execute();
			$stmt->bind_result($bname, $mheld, $yheld, $dheld, $tId, $lCorR, $lcountry, $ttype, $iname, $win1, $win2, $win3, $dadded);
			while ($row = $stmt->fetch()) {
				$basename = $bname;
				$monthheld = $mheld;
				$yearheld = $yheld;
				$dateheld = $dheld;
				$tourId = $tId;
				$locationCorR = $lCorR;
				$locationcountry = $lcountry;
				$tourtype = $ttype;
				$iterationname = $iname;
				$win1get = $win1;
				$win2get = $win2;
				$dateadded = $dadded;
				$win3get = $win3;

			}
			$winner1 = getPlayername($win1get);
            $winner1 = str_replace("'", "", $winner1);
			$winner2 = prettyName(getPlayername($win2get));
			$winner3 = prettyName(getPlayername($win3get));
		}
	}
	?>
          <h2>Update a Tournament</h2>
          <br>
          <h2>Selected Tournament: <?php echo $tournamenttoshow; ?></h2>
          <br>
          <h3>2. Enter revised or new information</h3>
          <form method="post" action="updatetournaments.php" id="content" role="form">
            <?php
echo "<div class='form-row'>";
	echo "   <div class='form-group col-md-5'>";
	echo "        <label for='tour_name'>Tournament Name:</label>";
	echo "        <input type='text' class='form-control' name='tour_name' value='$basename' required>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='MonthHeld'>Month Held (one month only):</label>";
	echo "        <input type='text' class='form-control' name='MonthHeld' value='$monthheld' required>";
	echo "   </div>";
	echo "   <div class='form-group col-md-2'>";
	echo "        <label for='YearHeld'>Year (YYYY):</label>";
	echo "        <input type='text' class='form-control' name='YearHeld' value='$yearheld' required>";
	echo "   </div>";
	echo "   <div class='form-group col-md-2'>";
	echo "        <label for='DateHeld'>Date Held (1st day y-m-d):</label>";
	echo "        <input type='text' class='form-control' name='DateHeld' value='$dateheld' required>";
	echo "   </div>";
	echo "</div>";

	echo "<div class='form-row'>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='TourID'>Tournament ID:</label>";
	echo "        <input type='text' class='form-control' name='TourID' value='$tourId' required>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='LocationCorR'>Location, City or Region:</label>";
	echo "        <input type='text' class='form-control' name='LocationCorR' value='$locationCorR'>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='LocationCountry'>Location, Country:</label>";
	echo "        <input type='text' class='form-control' name='LocationCountry' value='$locationcountry'>";
	echo "   </div>";
	echo "    <div class='form-group col-md-3'>";
	echo "        <label for='TourType'>Tour. Type:</label>";
	echo "        <select class='form-control' name='TourType'>";
	if ($tourtype == null) {
		echo "<option value=''>Choose...</option>";
	} else {
		echo "<option selected>$tourtype</option>";
	}
	echo "          <option value='EU'>EU</option>";
	echo "          <option value='hidden'>hidden</option>";
	echo "          <option value='other'>other</option>";
	echo "          <option value='US/Ca'>US/Ca</option>";
	echo "          <option value='VASL'>VASL</option>";
	echo "          <option value='PBEM'>PBEM</option>";
	echo "        </select>";
	echo "    </div>";
	echo "</div>";

	echo "<div class='form-row'>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='IterationName'>Iteration Name (eg. 20th Edition):</label>";
	echo "        <input type='text' class='form-control' name='IterationName' value='$iterationname'>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='Winner'>Winner:</label>";
	echo "        <input type='text' class='form-control' name='Winner' value='$winner1'>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='SecondPlace'>Second Place:</label>";
	echo "        <input type='text' class='form-control' name='SecondPlace' value='$winner2'>";
	echo "   </div>";
	echo "   <div class='form-group col-md-3'>";
	echo "        <label for='ThirdPlace'>Third Place:</label>";
	echo "        <input type='text' class='form-control' name='ThirdPlace' value='$winner3'>";
	echo "   </div>";
	echo "</div>";

	echo "<div class='form-row'>";
	echo "   <div class='form-group col-md-12'>";
	echo "        <label for='DateAdded'>Date Added to ASL Player Ratings:</label>";
	echo "        <input type='text' class='form-control' name='DateAdded' value='$dateadded' disabled>";
	echo "   </div>";
	echo "</div>";
	echo "<br>";
	echo "<br>";
	echo "<h3>3. Save Changes</h3>";
	echo "<br>";
	echo "<div class='form-row col-md-3'>";
	if ($tournamenttoshow == "AddNew") {
		$savetype = "AddNew";
	} else {
		$savetype = "submit";
	}
	echo "<button type='submit' class='btn btn-primary' name='$savetype' >Save</button>";
	echo "</div>";
	echo "<br>";
	echo "<div class='form-row col-md-3'>";
	echo "<button type='submit' class='btn btn-primary' name='Delete' >Delete</button>";
	echo "</div>";
	echo "</form>";
	$mysqli->close();

}
function getPlayerName($playernamecode) {
	global $mysqli;
	// $fullname = "";
    $firstname =""; $surname = "";
	//$sql2 = "select Fullname from players where Player_Namecode=?";
    $sql2 = "select players.Surname, players.First_Name from players where Player_Namecode=?";
	if ($stmt2 = $mysqli->prepare($sql2)) {
		$stmt2->bind_param("s", $playernamecode);
		$stmt2->execute();
		$stmt2->bind_result($surname, $firstname);
	while ($row = $stmt2->fetch()) {
	    $name = ucwords(strtolower(trim($firstname) . " " . trim($surname)), " .-\t\r\n\f\v");

            //$pn = $fullname;
			$stmt2->close();
			return $name;
		}
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//echo "No Player Name Match found for " . $playernamecode . "<br>";
	return null;

}
?>

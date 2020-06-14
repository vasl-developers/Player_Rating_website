<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/pages/connection.php");
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
if (isset($_POST['submit'])) {
    //$gametoedit = trim($_POST["tournamentgame"]);
    //$tournamenttouse = trim($_POST["showtour"]) ;
    include_once("web/pages/processtournament.php");
} else {
    $mysqli->set_charset("utf8");
    // get tournament
    $sql = "select Base_Name, Month_Held, Year_Held, Date_Held, Tournament_ID, Location_CityOrRegion, Location_Country, Tour_type, Iteration_Name, Winner1, Winner2, Winner3, Date_Added from tournaments WHERE Tournament_ID=?";
    if (!($stmt = $mysqli->prepare($sql))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $tournamenttoshow);
        $stmt->execute();
        $stmt->bind_result($basename, $monthheld, $yearheld, $dateheld, $tourId, $locationCorR, $locationcountry, $tourtype, $iterationname, $winner1, $winner2, $winner3, $dateadded);
        while ($row = $stmt->fetch()) {
          ?>
          <h1>Update A Tournament</h1>
          <br>
          <h2>Selected Tournament: <?php echo $tournamenttoshow; ?></h2>
          <br>
          <p><strong>2. Enter revised or new information</p></strong></p>
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
            echo "        <input type='text' class='form-control' name='TourID' value='$tourId'>";
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
            if($tourtype == null) {
                echo "<option value=''>Choose...</option>";
            } else {
                echo "<option selected>$tourtype</option>";
            }
            echo "          <option value='EU'>EU</option>";
            echo "          <option value='hidden'>hidden</option>";
            echo "          <option value='other'>other</option>";
            echo "          <option value='US/Ca'>US/Ca</option>";
            echo "          <option value='VASL'>VASL</option>";
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
            echo "<br>";
            echo "<br>";
            echo "<p><strong>3. Save Changes</p></strong></p>";
            echo "<br>";
            echo "<div class='form-row col-md-3'>";
            echo "<button type='submit' class='btn btn-primary' name='submit'>Save</button>";
            echo "</div>";
          echo "</form>";
          $mysqli->close();
        }
    }
}

?>

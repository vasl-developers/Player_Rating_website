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
            <h1>Update a Tournament</h1>
            <br>
            <p><strong>3. Save</p></strong></p>
            <br>
            <?php
            include("web/pages/connection.php");
            $mysqli = new mysqli($host, $username, $password, $database);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            $mysqli->set_charset("utf8");

            if (isset($_POST['submit'])) {
                $tourname = $_POST['tour_name'];
                $monthheld = $_POST['MonthHeld'];
                $yearheld = $_POST['YearHeld'];
                $dateheld = $_POST['DateHeld'];
                $locationcorr = $_POST['LocationCorR'];
                $locationcountry = $_POST['LocationCountry'];
                $tourtype = $_POST['TourType'];
                $iterationname = $_POST['IterationName'];
                $winner = $_POST['Winner'];
                $secondplace = $_POST['SecondPlace'];
                $thirdplace = $_POST['ThirdPlace'];
                $tourid = $_POST['TourID'];
                // update existing tournament
                if ($stmt = $mysqli->prepare("Update tournaments SET Base_Name=?, Month_Held=?, Year_Held=?, Date_Held=?, Tournament_ID=?,
                        Location_CityOrRegion=?, Location_Country=?, Tour_Type=?, Iteration_Name=?, Winner1=?, Winner2=?, Winner3=? WHERE Tournament_ID=?")) {
                    $stmt->bind_param("ssissssssssss", $tourname, $monthheld, $yearheld, $dateheld, $tourid, $locationcorr, $locationcountry,
                         $tourtype, $iterationname, $winner, $secondplace, $thirdplace, $tourid);
                    $stmt->execute();
                    echo "<br>";
                    echo "<li><strong>Tournament Update Added to Database</strong></li>";
                } else {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                $mysqli->close();
            }

            ?>
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


<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<?php include_once "web/include/navbar.htm";?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">

            <?php
include_once "web/pages/connection.php";
$mysqli = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
$mysqli->set_charset("utf8");

if (isset($_GET['tournamentid'])) {
	$tournamenttoshow = trim($_GET["tournamentid"]);
	include_once "updatetournaments.php";
} else {
	$sql = "select Base_Name, Year_Held, Tournament_id from tournaments order by Base_Name, Year_Held";
	$result = mysqli_query($mysqli, $sql);
	$tournamentlist = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$tournamentlist[] = $row;
	}
	$mysqli->close();
	?>
                <h2>Add or Update a Tournament</h2>
                <br>
                <p>Use this page to create a new Tournament or update information about an existing Tournament</p>
                <p>To upload Tournament Results, use the Upload Tournament Data option first</p>
                <p>1. Select the Tournament from the Tournaments dropdown list; use AddNew for a new Tournament</p>
                <p>2. Enter revised or new information</p>
                <p>3. Save</p>
                <br>
                <br>
                <h3>1. Select the Tournament from the Tournaments dropdown list</h3>
                <form class="form-inline" method="get" action="toolUpdateTournaments.php">
                    <div class="input-group-lg">
                        <select class="select " id="tournamentid" name="tournamentid" autocomplete="on" >
                            <option selected>Choose...</option>
                            <option value = "AddNew">Add New</option>
                            <?php
foreach ($tournamentlist as $tournament) {
		?>
                                <option value="<?php echo $tournament["Tournament_id"]; ?>"><?php echo $tournament["Base_Name"] . " " . $tournament["Year_Held"] . " " . $tournament["Tournament_id"] ?></option>
                                <?php
}
	?>
                        </select>
                        <button class="btn btn-primary pl-5" name="submit" type="submit" value="Select">Select</button>
                    </div>
                </form>
                <?php
}
?>
        </div>
    </div>
</div>
<?php include_once "web/include/footer.php";?>
</body>
</html>


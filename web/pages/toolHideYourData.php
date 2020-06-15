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
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            ?>
<h1>Hide Your Data</h1>

<p>The only personal data that is stored and/or appears on this site related to individual ASL players is their name, as used by them to play in a tournament and as provided to ASL Player Ratings by TDs, past, present and future.</p>

<p>Individual players have the right to request that their names not be displayed anywhere on the site. These requests will be honoured.</p>
<p>Players who request that their names not be used will not appear in any of the Player Listings, either Alphabetical or Ranked. Nor will their
    personal playing history be displayed. Game results involving such Players will show "Hidden" in place of their name in the display.</p>

<p>Players who request that they be Hidden will still be included in the database and ranked so that their oppoents can be ranked using the most complete information.</p>

<p>For those who do not wish their data to be included in the ASL Player Rating site whatsoever, there are two courses of action available.</p>

<p>1. For data submitted by TDs prior to the launch of the ASL Player Rating site, they should use this page to request that their data be removed.</p>
<p>2. For data submitted by TDs after the launch of the ASL Player Rating site, they should contact the TD of any and all tournaments in which they choose to participate. Any information provided to this site
    by TDs following its launch will be incorporated into the database and used to generate rankings. It may still be Hidden from view at the Player's choice as described previously. </p>
<br>
<p><strong>Hide Your Data in ASL Player Ratings</strong></p>
<?php
if(isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
    $pname = trim($_POST['playertohide']);
    // validate player name
    /* Prepared statement, prepare */

    $sql = "select Hidden from players where Fullname=?";
    if (!($stmt = $mysqli->prepare($sql))) {
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $pname);
        $stmt->execute();
        $stmt->bind_result($hide);
        $row = $stmt->fetch();
        if($row==null){
            echo "Invalid Player Name. Try again.";
            $stmt->close();
        } else {
            $stmt->close();
            // process hide
            /* Prepared statement, prepare */
            if (!($stmt1 = $mysqli->prepare("UPDATE players SET Hidden=? WHERE Fullname=?"))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                exit();
            }
            /* bind the parameters*/
            $hide = 1;
            $stmt1->bind_param("ss", $hide, $pname);
            /* execute */
            if ($stmt1->execute()) {
                $stmt1->close();
                echo $pname . ' ' . "updated as Hidden in Database";
            }
        }
    }
} elseif(isset($_POST['remove_submit']) && $_POST['remove_submit'] == 'Submit') {
    $administrator = "Doug Rimmer gm";
    $adminemail = "dougerimmer@gmail.com";
    $headers = "From: dougerimmer@gmail.com";
    $person = trim($_POST["playertoremove"]);
    $emailtosend = trim($_POST["playeremail"]);

    $msg = "Hi" . " " . $person . " " . "You have asked for your data to be removed from the ASL Player Rating System. Please contact " . $administrator . " at " . $adminemail . " to confirm your identity and provide additional information.";
    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);
    // send email
    if(!(mail($emailtosend,"Remove Data Request",$msg, $headers))){
        echo "some kind of error. Come on!";
    };
    echo "Email has been sent to " . $administrator . ".";
} else {
?>

<form action="toolHideYourData.php" method="post">
    <div class="checkbox">
        <label><input type="checkbox" name="formHYD" value="">Hide My Data</label>
    </div>
    <div class="form-group row">
        <div class="col-xs-4">
        <label for="playertohide">Player Name:</label>
        <input type="text" class="form-control" name="playertohide" id="playertohide" />
        </div>
    </div>
    <button class="btn btn-primary pl-5" type="submit">Submit</button>
    <input type="hidden" name="submit" value="Submit" />
</form>
<br>
<p><strong>Remove My Data (2017 and earlier)</strong></p>
<form action="toolHideYourData.php" method="post">
    <div class="checkbox">
        <label><input type="checkbox" name="formRMD" value="">Remove My Data</label>
    </div>
    <div class="form-group row">
        <div class="col-xs-4">
        <label for="playertoremove">Player Name:</label>
        <input type="text" class="form-control" name="playertoremove">
        </div>
        <div class="col-xs-4">
        <label for="playeremail">Player Email:</label>
        <input type="text" class="form-control" name="playeremail">
        </div>
    </div>
    <button class="btn btn-primary pt-25" type="submit">Submit</button>
    <input type="hidden" name="remove_submit" value="Submit" />
</form>
<?php } ?>
        </div>
        <?php include_once("web/include/right-sidebar.php"); ?>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
<?php
$mysqli->close();
?>
</body>
</html>

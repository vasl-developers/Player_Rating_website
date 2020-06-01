<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Submit A Correction</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>Submit A Correction</h2>
<p></p>
<p>Use this page to submit a correction to a Tournament Result</p>
<p></p>
<p>1. Select the Tournament from the Tournaments dropdown list</p>
<p>2. Select a game from the Tournament Games dropdown list OR choose Missing Game</p>
<p>3. Enter revised or new information</p>
<p>4. Click Save</p>
<p></p>
<div class="maindiv">
    <div class="divD">
        <?PHP
        include ("../connection.php");
        $mysqli = mysqli_connect($host, $username, $password, $database);
        $mysqli->set_charset("utf8");
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("Select Base_Name, Year_Held, Tournament_id FROM tournaments"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->execute();
        $stmt->bind_result($basename, $yearheld, $tournamentid);

        ?>
        <p>Type or Select Tournament:</p>
        <form method="post" action="web/PHP/Tools/SubmitCorrection.php">
            <input type="text" list="tournaments" name="tournament">
            <datalist id="tournaments" autocomplete="on">
                <?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <option value="<?PHP echo $tournamentid;?>"><?PHP echo $basename . " " . $yearheld . " " . $tournamentid ?></option>
                    <?PHP
                }
                    $stmt->close();
                    ?>
            </datalist>
            <input type="submit" value="Select">
        </form>
        <?php
        if (isset($_POST['tournament'])) {
            $tournamenttoshow=trim($_POST["tournament"]);
            include("../Tools/gamecorrection.php");

        }

        ?>
    </div>

</div>
</body>
</html>



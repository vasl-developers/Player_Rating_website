 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update existing players</title>
    <meta http-equiv="expires" content="0">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../css/style.css?counter=<?php echo time(); ?>" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row-fluid">
        <div id="homeContainer" class="span12">
            <?php
include ("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$Firstname="";
if (isset($_POST['mode'])) {
    $FirstName = $_POST['First_Name'];
    $Surname = $_POST['Surname'];
    $Country = $_POST['Country'];

    if($_POST['mode']== "added") {
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("INSERT INTO players (First_Name, Surname, Country) VALUES (?,?,?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* bind the parameters*/
        $stmt->bind_param("sss", $Firstname, $Surname, $Country);
        /* set parameters and execute */
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        echo "New Player Added to Database";
    }
    if($_POST['mode']== "edited") {
        $NameCode = $_POST['PNameCode'];
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli->prepare("UPDATE players SET First_Name=?, Surname=?, Country=? WHERE Player_Namecode=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        /* bind the parameters*/
        $stmt->bind_param("sssS", $Firstname, $Surname, $Country, $NameCode);
        /* set parameters and execute */
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        echo "Existing Player Updated in Database";
    }
    if($_POST['mode']== "find") {
        // parameters set in html form
        /* Prepared statement, stage 1: prepare */
        $stmt = $mysqli->prepare("SELECT * FROM players WHERE First_Name=? AND Surname=?");
        /* bind the parameters*/
        $stmt->bind_param("ss", $FirstName, $Surname);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data
        // set data for html form
        $FirstName = $user['First_Name'];
        $Surname = $user['Surname'];
        $Country = $user['Country'];
        $NameCode = $user['Player_NameCode'];
        echo "Existing Player Found in Database; Data Retrieved";
    }
}
?>
<!--add option buttons-->
  <h3>Select Action</h3>
  <a class="btn btn-primary" role="button">Add New Player</a>
  <button class="btn btn-primary" type="button">Edit Existing Player</button>

    <!--<div class="collapse" id="collapseAddPlayer">
        <div class="card card-body">-->
        <div>
            <h2>Add Player</h2>
            <p>
            <form action="" method="POST">
                <table>
                    <tr><td>First Name:</td><td><input type="text" name="First_Name" /></td></tr>
                    <tr><td>Surname:</td><td><input type="text" name="Surname" /></td></tr>
                    <tr><td>Country:</td><td><input type="text" name="Country" /></td></tr>
                    <tr><td>Inactive:</td><td><input type="text" name="Inactive" /></td></tr>
                    <tr><td align="left"><input type="submit" /></td></tr>
                    <input type=hidden name=mode value=added>
                </table>
            </form> </p>
        </div>
    <!--</div>

    <div class="collapse" id="collapseEditPlayer">
        <div class="card card-body">-->
        <div>
            <h2>Edit Existing Player</h2>
            <h4>Enter Player to edit:</h4>
            <p>
            <form action="" method="POST">
                <table>
                        <tr><td>First Name:</td><td><input type="text"  name="First_Name"  /></td></tr>
                        <tr><td>Surname:</td><td><input type="text" name="Surname" /></td></tr>
                        <tr><td align="left"><input type="submit" /></td></tr>
                        <input type=hidden name=mode value=find>
                </table>
            </form><p></p>
        </div>

            <!--<div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="">First and last name</span>
                </div>
                <input type="text" class="form-control" placeholder="First name" name="First_Name">
                <input type="text" class="form-control" placeholder="Last name" name="Surname">
                <div class="input-group-append">
                    <button class="btn btn-primary btn-sm" <input type="submit" value=find>Find</button>
                    <tr><td align="left"><input type="submit" /></td></tr>
                    <input type=hidden name=mode value=find>
                </div>
            </div>-->
        <div>

            <h4>Edit Data:</h4>
            <p>
            <form action="" method="POST">
                <table>
                    <tr><td>First Name:</td><td><input type="text"  name="First_Name"  /><?PHP Print $Firstname; ?></td></tr>
                    <tr><td>Surname:</td><td><input type="text" name="Surname" /></td></tr>
                    <tr><td>Country:</td><td><input type="text" name="Country" /></td></tr>
                    <tr><td>Inactive:</td><td><input type="text" name="Inactive" /></td></tr>
                    <tr><td>Player_NameCode:</td><td><input type ="text" name="PNameCode" /></td></tr>
                    <tr><td align="left"><input type="submit" /></td></tr>
                    <input type=hidden name=mode value=edited>
                </table>
            </form> </p>
        </div>

       <!-- </div>
    </div>

        //edit mode adds a pre-populated form
            if ($mode == "edit") {
            Print '--> <h2>Edit Player</h2>
            <p>
            <form action="" method="post">
                <table>
                    <tr><td>First Name:</td><td><input type="text" value="';
                        Print $Firstname;
                        print '" name="First_Name" /></td></tr>
                    <tr><td>Last Name:</td><td><input type="text" value="';
                        Print $Surname;
                        print '" name="Surname" /></td></tr>
                    <tr><td>Country:</td><td><input type="text" value="';
                        Print $Country;
                        print '" name="Country" /></td></tr>
                    <tr><td>Player NameCode:</td><td><input type="text" value="';
                        Print $Namecode;
                        print '" name="Player_NameCode" /></td></tr>
                    <tr><td align="center"><input type="submit" /></td></tr>
                    <input type=hidden name=mode value=edited>
                    <input type=hidden name=playerName value="';
                        Print $FirstName;
                        print '">
                </table>
            </form> <p>';
            }
<!--
            //edited mode updates the data for a given playerName
            if ($mode == "edited") {
            if ($first > "") {
            $query = "update Player set Name = '$first $last', first_name = '$first', last_name = '$last', phone1 = '$phone1', phone2 = '$phone2', phone3 = '$phone3', email = '$email', city = '$city', inactive = 0 where Name = '$playerName'";
            $result = mysqli_query($link, $query);
            Print "Data Updated!<p>";
            } else {
            $query = "delete from Player where Name == ''";
            mysqli_query($mysqli, $query);
            Print "Record Deleted!<p>";
            }
            }

            //This pulls the data and puts it into an array, then prints in alphabetical order based on name
            $data = mysqli_query($mysqli, "SELECT * FROM players ORDER BY first_name ASC") or die(mysqli_error());
            Print "<h2>Player List</h2><p>";
            Print "<table border cellpadding=3>";
            Print "
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone1</th>
                <th>Phone2</th>
                <th>Phone3</th>
                <th>Email</th>
                <th>City</th>
                <th>Inactive</th>
                <th>Admin</th>
            </tr>";
            Print "<td colspan=9 align=right><a href=" . $_SERVER['PHP_SELF'] . "?mode=add>Add Player</a></td>";
            while ($info = mysqli_fetch_array($data)) {
            $nm = ckNullEmpty($info['Name']);
            $fn = ckNullEmpty($info['first_name']);
            $ln = ckNullEmpty($info['last_name']);
            $p1 = ckNullEmpty($info['phone1']);
            $p2 = ckNullEmpty($info['phone2']);
            $p3 = ckNullEmpty($info['phone3']);
            $em = ckNullEmpty($info['email']);
            $ct = ckNullEmpty($info['city']);
            $ia = ckNullEmpty($info['inactive']);
            if ($ia == 1) {
            $iaText = "Inactive";
            }

            if ($ia == 0) {
            $iaText = "";
            }

            Print "<tr>\n";
                Print "<td>" . $fn . "</td>";
                Print "<td>" . $ln . "</td>";
                Print "<td>" . $p1 . "</td>";
                Print "<td>" . $p2 . "</td>";
                Print "<td>" . $p3 . "</td>";
                Print "<td>" . $em . "</td>";
                Print "<td>" . $ct . "</td>";
                Print "<td>" . $iaText . "</td>";
                Print "<td><a href=" . $_SERVER['PHP_SELF'] . "?playerName=" . urlencode($nm) . "&first=" . urlencode($fn) . "&last=" .
urlencode($ln) . "&phone1=" . urlencode($p1) . "&phone2=" . urlencode($p2) . "&phone3=" . urlencode($p3) . "&email=" . $em . "&city=" . urlencode($ct) . "&inactive=" . urlencode($ia) .
"&mode=edit>Edit</a></td>";
                Print "</tr>\n";
            }
            Print "</table>";

            ?>
        </div>-->
        </div>
        </div>
    </div>
</div>
</body>
</html>
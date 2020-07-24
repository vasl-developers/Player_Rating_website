<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
    <div class="row">
        <div class="main-content col-md-10 offset-md-1">
            <?php
            include("web/pages/connection.php");
            $mysqli = new mysqli($host, $username, $password, $database);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }
            $mysqli->set_charset("utf8");
            $p1NameCode=""; $p2NameCode=null; $initroundNo=null; $initrounddate=null; $initscenario=null; $initplayer1=null; $initplayer1code=null; $initplayer2=null; $initplayer2code=null;
            $p1attdefsel = "Choose..."; $p2attdefsel = "Choose..."; $p1AlliAxissel = "Choose..."; $p2AlliAxissel = "Choose..."; $p1resultsel = null; $p2resultsel = null;

            // get game results for tournament
            if ($gametoedit ==0){   //$gametoedit is set in gamecorrection.php which includes editgame.php
                $inittourId=$tournamenttouse; // adding new game so all variables are blank except tournament_ID
            }else {
                $sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Player2_Result, Round_No, Round_Date, Scenario_ID, Tournament_ID, player1.Fullname, player1.Player_Namecode, player2.Fullname, player2.Player_Namecode from match_results INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode WHERE match_results.id=? ";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("i", $gametoedit);
                    $stmt->execute();
                    $stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $p2Result, $roundNo, $rounddate, $scenario, $tourId, $player1, $player1code, $player2, $player2code);
                    // display all game results fields in input boxes for editing
                    while ($row = $stmt->fetch()) {
                        if ($p1AttDef != null) {
                            $p1attdefsel = $p1AttDef;
                        } else {
                            $p1attdefsel = "Choose...";
                        }
                        if ($p2AttDef != null) {
                            $p2attdefsel = $p2AttDef;
                        } else {
                            $p2attdefsel = "Choose...";
                        }
                        if ($p1AlliAxis != null) {
                            $p1AlliAxissel = $p1AlliAxis;
                        } else {
                            $p1AlliAxissel = "Choose...";
                        }
                        if ($p2AlliAxis != null) {
                            $p2AlliAxissel = $p2AlliAxis;
                        } else {
                            $p2AlliAxissel = "Choose...";
                        }
                        if ($p1Result != null) {
                            $p1resultsel = $p1Result;
                        } else {
                            $p1resultsel = "Choose...";
                        }
                        if ($p2Result != null) {
                            $p2resultsel = $p2Result;
                        } else {
                            $p2resultsel = "Choose...";
                        }
                        $initplayer1 = ucwords(strtolower(trim($player1)), " .-\t\r\n\f\v");
                        $initplayer2 = ucwords(strtolower(trim($player2)), " .-\t\r\n\f\v");
                        $p1NameCode = $p1Code;
                        $p2NameCode = $p2Code;
                        $initroundNo = $roundNo;
                        $initrounddate = $rounddate;
                        $initscenario = $scenario;
                        $inittourId = $tourId;
                    }
                } else {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
            }
            ?>
            <h2>Submit a Correction</h2>
            <br>
            <h3>3. Enter revised or new information</h3>

            <form method="post" action="processedit.php" id="content" role="form">
                    <?php
                    echo "<div class='form-row'>";
                    echo "   <div class='form-group col-md-3'>";
                    echo "        <label for='tour_name'>Tournament Name:</label>";
                    echo "        <input type='text' class='form-control' name='tour_name' value='{$inittourId}' required>";
                    echo "   </div>";
                    echo "   <div class='form-group col-md-1'>";
                    echo "        <label for='roundno'>Round No:</label>";
                    echo "        <input type='text' class='form-control' name='roundno' value='{$initroundNo}'>";
                    echo "   </div>";
                    echo "   <div class='form-group col-md-3'>";
                    echo "        <label for='round_date'>Round Date (yyyy-mm-dd):</label>";
                    echo "        <input type='text' class='form-control' name='round_date' value='{$initrounddate}' required>";
                    echo "   </div>";
                    echo "   <div class='form-group col-md-5'>";
                    echo "        <label for='scenid'>Scenario ID:</label>";
                    echo "        <input type='text' class='form-control' name='scenid' value='{$initscenario}'>";
                    echo "   </div>";
                    echo "</div>";

                    echo "<div class='form-row'>";
                    echo "   <div class='form-group col-md-4'>";
                    echo "        <label for='fpnc'>Player 1 Name:</label>";
                    echo "        <input type='text' class='form-control' name='fpnc' value='{$initplayer1}' required>";
                    echo "   </div>";
                    echo "   <div class='form-group col-md-3'>";
                    echo "        <label for='fattdef'>Player 1 Att or Def:</label>";
                    echo "        <select class='form-control' name='fattdef'>";
                                    if($p1attdefsel == null) {
                                        echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p1attdefsel}</option>";
                                    }
                    echo "          <option value='attacker'>Attacker</option>";
                    echo "          <option value='defender'>Defender</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "   <div class='form-group col-md-2'>";
                    echo "        <label for='falax'>Player 1 Allies or Axis:</label>";
                    echo "        <select class='form-control' name='falax'>";
                                    if($p1AlliAxissel == null) {
                                        echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p1AlliAxissel}</option>";
                                    }
                    echo "          <option value='allies'>Allies</option>";
                    echo "          <option value='axis'>Axis</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "    <div class='form-group col-md-3'>";
                    echo "        <label for='fresult'>Player 1 Result:</label>";
                    echo "        <select class='form-control' name='fresult' required>";
                                    if($p1resultsel == null) {
                                       echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p1resultsel}</option>";
                                    }
                    echo "          <option value='win'>Win</option>";
                    echo "          <option value='lost'>Lost</option>";
                    echo "          <option value='draw'>Draw</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "</div>";

                    echo "<div class='form-row'>";
                    echo "   <div class='form-group col-md-4'>";
                    echo "        <label for='spnc'>Player 2 Name:</label>";
                    echo "        <input type='text' class='form-control' name='spnc' value='{$initplayer2}' required>";
                    echo "   </div>";
                    echo "   <div class='form-group col-md-3'>";
                    echo "        <label for='sattdef'>Player 2 Att or Def:</label>";
                    echo "        <select class='form-control' name='sattdef'>";
                                    if($p2attdefsel == null) {
                                        echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p2attdefsel}</option>";
                                    }
                    echo "          <option value='attacker'>Attacker</option>";
                    echo "          <option value='defender'>Defender</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "   <div class='form-group col-md-2'>";
                    echo "        <label for='salax'>Player 2 Allies or Axis:</label>";
                    echo "        <select class='form-control' name='salax'>";
                                    if($p2AlliAxissel == null) {
                                        echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p2AlliAxissel}</option>";
                                    }
                    echo "          <option value='allies'>Allies</option>";
                    echo "          <option value='axis'>Axis</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "    <div class='form-group col-md-3'>";
                    echo "        <label for='sresult'>Player 2 Result:</label>";
                    echo "        <select class='form-control' name='sresult' required>";
                                    if($p2resultsel == null) {
                                       echo "<option value=''>Choose...</option>";
                                    } else {
                                        echo "<option selected>{$p2resultsel}</option>";
                                    }
                    echo "          <option value='win'>Win</option>";
                    echo "          <option value='lost'>Lost</option>";
                    echo "          <option value='draw'>Draw</option>";
                    echo "        </select>";
                    echo "    </div>";
                    echo "</div>";
                    echo "<input class='input' type='hidden' name='gameid' value='{$gametoedit}' />";
                    ?>
                    <br>
                    <h3>4. Save Changes</h3>
                    <br>
                    <button type='submit' class='btn btn-primary' name='submit'>Save</button>
            </form>
        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

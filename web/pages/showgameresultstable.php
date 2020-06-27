<?php
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include("web/pages/connection.php");
  $mysqli = new mysqli($host, $username, $password, $database);
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }
  $mysqli->set_charset("utf8");

$sql = "select Player1_Namecode, Player1_AttDef, Player1_AlliesAxis, Player1_Result, Player2_Namecode, Player2_AttDef, Player2_AlliesAxis, Round_No, Scenario_ID, player1.Fullname, player1.Hidden, player2.Fullname, player2.Hidden from match_results 
        INNER JOIN players player1 ON player1.Player_Namecode=match_results.Player1_Namecode 
        INNER JOIN players player2 ON player2.Player_Namecode=match_results.Player2_Namecode 
        where Tournament_ID = '" . $_GET["tournamentid"] . "' order by Round_No";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($p1Code, $p1AttDef, $p1AlliAxis, $p1Result, $p2Code, $p2AttDef, $p2AlliAxis, $roundNo, $scenario, $player1, $play1hide, $player2,  $play2hide);

    $previousRoundNo="";
    //$play1hide=0;
    //$play2hide=0;
?>
    <br>
    <h2>Tournament: <?php echo $_GET["tournamentid"]?></h2>
    <br>
    <table class="table table-condensed table-striped">
      <thead>
      <tr>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th>Result</th>
        <th>Player</th>
        <th>Att/Def</th>
        <th>Al/Ax</th>
        <th></th>
        <th>Scenario</th>
      </tr>
      </thead>
      <tbody>
        <?php
        while ($row = $stmt->fetch()) {
          if ($roundNo != $previousRoundNo) {
            $previousRoundNo = $roundNo;
        ?>
        <tr>
          <td colspan=9 class="headline">Round <?php echo $roundNo ?></th>
        </tr>
          <?php
          }
          ?>
        <tr>
          <td class="top">
              <?php
              if($play1hide==1){$player1="Hidden";}
              if($play2hide==1){$player2="Hidden";}
              if($player1=="Hidden") {
                  ?>
                  <?php echo $player1 ?>
                  <?php
              } else {
                  ?>
                  <p><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p1Code ?>"><?php echo $player1 ?></a></p>
                  <?php
              }
              ?>
          </td>
          <td><?php echo $p1AttDef ?></td>
          <td><?php echo $p1AlliAxis ?></td>
          <?php
          if(trim(strtolower($p1Result))=="draw"){
            $p1Result = "draws";
          } elseif(trim(strtolower($p1Result))=="lost") {
            $p1Result = "loses to";
          } else {
            $p1Result = "beats";
          }
          ?>
          <td><?php echo $p1Result ?></td>
          <td class="top">
              <?php
              if($player2=="Hidden") {
                  ?>
                  <?php echo $player2 ?>
                  <?php
              } else {
                  ?>
                  <p><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $p2Code ?>"><?php echo $player2 ?></a></p>
                  <?php
              }
              ?>
          </td>
          <td><?php echo $p2AttDef ?></td>
          <td><?php echo $p2AlliAxis ?></td>
          <td><?php if($scenario > '') echo 'in'; ?></td>
          <td><?php echo $scenario ?></td>
        </tr>
        <?php
          }
        ?>
      </tbody>
    </table>

<?php
    $mysqli->close();
  } else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
?>

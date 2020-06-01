<?php
  include("PHP/connection.php");
  $mysqli = mysqli_connect($host, $username, $password, $database);
  $sql = "select * from match_results WHERE Tournament_ID = " . $tournamenttoshow . " ORDER BY Round_No";
  $result = mysqli_query($mysqli, $sql);

  $firstarray = [];
  $secondarray = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $firstarray[] = $row;
    $secondarray[] = $row;
  }
  $previousroundno="";
  foreach ($firstarray as $row) {
    if ($row["Round_No"] != $previousroundno) {
      $previousroundno = $row["Round_No"];
?>
<html>
<h1>Round <?php echo $row["Round_No"]?></h1>
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
  foreach ($secondarray as $row1) {
    if ($row1["Round_No"]==$previousroundno) {
      $playercode1 = trim($row1["Player1_Namecode"]);
      $player1     = getplayername(trim($row1["Player1_Namecode"]));
      $p1attdef    = trim($row1["Player1_AttDef"]);
      $p1alax      = trim($row1["Player1_AlliesAxis"]);

      $playercode2 = trim($row1["Player2_Namecode"]);
      $player2     = getplayername(trim($row1["Player2_Namecode"]));
      $p2attdef    = trim($row1["Player2_AttDef"]);
      $p2alax      = trim($row1["Player2_AlliesAxis"]);

      $scenario    = trim($row1["Scenario_ID"])
  ?>
      <tr>
        <td class="top">
          <p><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $playercode1?>"><?php echo $player1?></a></p>
        </td>
        <td><?php echo $p1attdef?></td>
        <td><?php echo $p1alax?></td>
        <td>beats</td>
        <td class="top">
          <p><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $playercode2?>"><?php echo $player2?></a></p>
        </td>
        <td><?php echo $p2attdef?></td>
        <td><?php echo $p2alax?></td>
        <td>in</td>
        <td><?php echo $scenario?></td>
    </tr>
  <?php
    }
  }
  ?>
  </tbody>
</table>
</html>
<?php
  }
}
$mysqli->close();

// Defining function
function getplayername($playercode){
  global $mysqli;
  $playercode=trim($playercode);
  $stmt5=$mysqli->prepare("SELECT * FROM players WHERE Player_Namecode=?");
  $stmt5->bind_param("s", $playercode);
  $stmt5->execute();
  $result5=$stmt5->get_result(); // get the mysqli result
  while ($row5 = $result5->fetch_assoc()) {
    $pname = $row5["Fullname"];
    return $pname;
  }
}

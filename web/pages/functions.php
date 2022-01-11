<?php
function prettyName($player){
  return ucwords(strtolower(trim($player)), " .-\t\r\n\f\v");
}
function getnamecode($playername) {
  global $mysqli;
  $playername = trim($playername);
  if ($stmt5 = $mysqli->prepare("SELECT Fullname, Player_Namecode FROM players")) {
    $stmt5->execute();
    $stmt5->bind_result($playerfullname, $namecode);
    while ($row = $stmt5->fetch()) {
      if (strcasecmp(trim($playerfullname), trim($playername)) == 0) {
        $pnc = $namecode;
        $stmt5->close();
        return $pnc;
      }
    }
  } else {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if ($playername !="") {
    echo "No Player Name Match found for " . $playername . "<br>";
  }
  return null;
}
?>

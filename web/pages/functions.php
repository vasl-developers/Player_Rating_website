<?php
function prettyName($player){
  return ucwords(strtolower(trim($player)), " .-\t\r\n\f\v");
}

?>

<?php
// this file is "included" in other scripts to write transaction details to a log file
$transactionsfile = fopen("../Data/ASL Player Ratings transactions log.txt", "a") or die("Unable to open file!");
fwrite($transactionsfile, $txt);  // $txt set in parent file
fclose($transactionsfile);
?>

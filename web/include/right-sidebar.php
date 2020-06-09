<?php
$ROOT = '/';
set_include_path($_SERVER['DOCUMENT_ROOT']);
?>
<div class="col-md-2">
  <div class="list-group">
    <a href="#" class="list-group-item active">
      <h4 class="list-group-item-heading">Tools and Support</h4>
    </a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolUploadTournamentResults.php">Upload Tournament Data</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/files/ASL Player Ratings Game Results Data Form.xlsx">Download Data Entry Form</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/PHP/Tools/DownloadPlayerList.php">Download Player List</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/Tools_Support/DownloadScenarioList.html">Download Scenario List</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/Tools_Support/GetHelp.html">Get Help</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/Tools_Support/GetTheData.html">Get The Data!</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolSubmitGameCorrection.php">Submit A Correction</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/PHP/UpdateTables/updateplayers.php">Add or Update Players</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/Tools_Support/HideYourData.html">Hide Your Data</a>
  </div>
</div>

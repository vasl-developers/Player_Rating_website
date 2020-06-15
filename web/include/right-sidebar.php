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
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolSubmitGameCorrection.php">Submit A Correction</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolUpdatePlayers.php">Add or Update Players</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolUpdateTournaments.php">Update A Tournament</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/Data/downloads/ASL Player Ratings Game Results Data Form.xlsx">Download Data Entry Form</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolDownloadPlayerList.php">Download Player List</a>
    <a class="list-group-item">Download Scenario List</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolGetTheData.php">Get The Data!</a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolHideYourData.php">Hide Your Data</a>
    <a href="#" class="list-group-item active"><h4 class="list-group-item-heading">Help</h4></a>
    <a class="list-group-item" href="<?php echo $ROOT; ?>web/pages/toolGetHelp.php">Get Help</a>
  </div>
</div>

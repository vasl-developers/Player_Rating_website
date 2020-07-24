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
            if (isset($_POST['submit'])) {
                include_once("addnewtournamentresults.php");
            } else {
                ?>
                <h2>Upload New Tournament Results</h2>
                <br>
                <p>Use this page to submit game results for a Tournament</p>
                <br>
                <p>For information on required file format and structure, see Get Help!</p>
                <br>
                <p>If you wish to add to an existing tournament or modify existing games, go to Submit A Correction</p>
                <br>
                <br>
                <h3>Select file to upload (.csv format only at present):</h3>
                <form action="toolUploadTournamentResults.php" target ="_self" method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
                        <br>
                        <button class="btn btn-primary pt-5" name="submit" type="submit" value="Select">Upload & Add to Database</button>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>


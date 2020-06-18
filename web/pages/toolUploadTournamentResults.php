<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once("web/include/header.php");
?>
<body>
<?php include_once("web/include/navbar.htm"); ?>
<div class="home container-fluid">
    <div class="row">
        <?php include_once("web/include/left-sidebar.php"); ?>
        <div class="main-content col-md-8">
            <?php
            if (isset($_POST['submit'])) {
                include_once("addnewtournamentresults.php");
            } else {
                ?>
                <h1>Upload New Tournament Results</h1>
                <br>
                <p>Use this page to submit game results for a Tournament</p>
                <br>
                <p>For information on required file format and structure, see Get Help!</p>
                <br>
                <p>If you wish to add to an existing tournament or modify existing games, go to Submit A Correction</p>
                <br>
                <br>
                <p><strong>Select file to upload (.csv format only at present):</strong></p>
                <form action="toolUploadTournamentResults.php" target ="_self" method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
                        <br>
                        <button class="btn btn-primary pt-5" name="submit" type="submit" value="Select">Upload & Add to Database</button>
                    </div>
                </form>
            <?php } ?>
        </div>
        <?php include_once("web/include/right-sidebar.php"); ?>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $ROOT; ?>web/include/ready.js"></script>
</body>
</html>


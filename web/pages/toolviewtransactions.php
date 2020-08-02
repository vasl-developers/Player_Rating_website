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
            <div class="main-content col-md-10 offset-md-1">
                <h2>View Transactions Log</h2>
                <p>To download a copy of the transactions log:</p>
                <a id="downloadtranslog" class="track btn btn-large btn-primary" href="../Data/ASL Player Ratings transactions log.txt ">Download Log</a>
                <br>
                <br>
                <br>
                <?php
                $contents = file("../Data/ASL Player Ratings transactions log.txt");
                $string = implode("<br>", $contents);
                echo $string;
?>
            </div>
        </div>
    </div>
<?php
include_once("web/include/footer.php");
?>
</body>
</html>


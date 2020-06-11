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
            <h1>Get The Data</h1>
            <br>
            <p>This page allows people to download a copy of the database to ensure it remains accessible to the entire community to whom it belongs</p>
            <br>
            <p>The database itself is comprised of a number of tables. The files below contain the data for each table separately.</p>
            <p>Also available is a visualization of the data tables and a script file to create the necessary tables. These two files apply to the MySQL database used in this application but can be modified as required to work with other SQL databases.</p>
            <br>
            <p>Choose files to download:</p>
            <p>Table data</p>
            <a id="downloadplayerscsv" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating data files/area_schema_players.csv">Players, csv format</a>
            <a id="downloadtournamentscsv" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating data files/area_schema_tournaments.csv">Tournaments, csv format</a>
            <a id="downloadmatchresultscsv" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating data files/area_schema_match_results.csv">Game Results, csv format</a>
            <a id="downloadplayerratingscsv" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating data files/area_schema_player_ratings.csv">Player Ratings, csv format</a>
            <br>
            <br>
            <p>Database files, view and save:</p>
            <a id="downloadcreatetables" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating database create scripts/createdbtables.txt">Create Tables scripts, php format</a>
            <a id="downloadvisualization" class="track btn btn-large btn-primary" href="../Data/ASL Player Rating data files/area_schema visualization_latest.png">Database Visualization</a>
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

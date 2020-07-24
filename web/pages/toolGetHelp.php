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

            <h2>Help</h2>
            <br>
            <br>
            <h2>Viewing Ratings, Tournaments, Game Results and Player Histories</h2>
            <br>
            <h3>1. View Ratings</h3>
            <p>Click on one of the four links in the middle of the page, which offer Ranked or Alphabetical lists of All Players in ASL Player Ratings or Active Players (those who have a Game Result dated within 800 days of the date when the ratings were last compiled.</p>
            <br>
            <h3>2. View Tournaments</h3>
            <p>Click on the Tournaments Included link to see a list of all Tournaments included in ASL Player Ratings</p>
            <br>
            <h3>3. View Game Results for a Tournament</h3>
            <p>To view the results of all games played at a particular tournament, do one of:</p>
                    <p>(a) Click on the Tournaments Included link and then click on the link for the tournament you wish to view</p>
                    <p>(b) Click on the Show Tournament Results link then select the tournament you wish to view from the dropdown list</p>
            <br>
            <h3>4. View Player Game by Game Results</h3>
            <p>To view the results of all games played by a particular player, do one of:</p>
                    <p>(a) From any of the Ratings listings, click on the link for the particular player</p>
                    <p>(b) From the Game Results for a Tournament view, click on a player name</p>
            <br>
            <br>
            <h2>How are Ratings Calculated?</h2>
            <br>
            <p>Currently, ASL Playing Ratings use's the ELO methodology used by the AREA site. Click on About Area in the Navigation Bar to view the methodology in detail</p>
            <p>It is expected that overtime the methodology may be adjusted or replaced entirely with an alternate approach such as Glicko; any changes will be described on this site</p>
            <p>To view the code that performs the ratings calculations go to the ASL Playing Ratings site on Github</p>
            <br>
            <br>
            <h2>Tools And Support</h2>
            <br>
            <p>The Tools and Support sidebar provides a range of functions to support the Ratings</p>
            <br>
            <h3>1. Data Management</h3>
            <p>Adding or amending Tournament Game results:</p>
                    <p>(a) To add an entire tournament's results, use Upload Tournament Data, ensuring that the file you upload is properly formatted as per the instructions</p>
                    <p>(b) To add partial results (a game or two) or to correct an existing Game Result, use Submit A Correction and then select either a specific game or Add Missing Game from the dropdown</p>
            <p>Amending information for an Existing Tournament</p>
                    <p>(a) To enter new or correct information about a Tournament that already exists in ASL Playing Ratings, select Update A Tournament</p>
            <p>Adding a New Player or Amending an Existing One</p>
                    <p>(a) To add a new player to the database, select Add or Update Players then Add A New Player</p>
                    <p>(b) To update and existing player in the database, select Add or Update Players then select the player from the dropdown list</p>
            <br>
            <h3>2. Obtain Documents or Data</h3>
            <p>Various links (Download Data Entry Form, Download Player File and Get the Data!) allow retrieval of data and forms for use with the system and are a key part of meeting the site's goal of transparency and community access to the underlying data and tools</p>
            <p>The Download Scenario List link is currently disabled</p>
            <p>Hide Your Data offers tournament participants the opportunity to hide or remove their data from the system</p>
            <p>In the case of hiding, the data remains in the system and is used to calculate ratings; the user's name is replaced by Hidden in all displays</p>
            <p>In the case of remivng, the data will be deleted from the ASL Player Rating system and no longer used in ratings calculations</p>
            <p>It is important to remember that removal will effect the ratings of all opponents of the player whose data is removed</p>
        </div>
    </div>
</div>
<?php include_once("web/include/footer.php"); ?>
</body>
</html>

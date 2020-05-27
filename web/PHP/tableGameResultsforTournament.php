<?php
include("../PHP/connection.php");
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="max-age=86400"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="web/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="web/favicon.ico" type="image/x-icon" />
    <link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="web/css/vasl_styles.css" rel="stylesheet" />
    <script type="text/javascript">

    </script>
</head>
<body>
<div id="navbar"></div>
<div id="content">
<div id="main-content col-md-8">
    <h2>List of All Games Played in a Tournament included in ASL Player Ratings</h2>
    <p>To view Game-by-Game results for a particular Player, click on the link.</p>

    <?php
    $tournamenttoshow=$_GET['tournamentid']; //$tournamentid is passed from showtournamentstable.php
    include("../PHP/showgameresultstable.php");

    ?>
    <div id = "info">
        <div class="portlet-body">

        </div>
    </div>
    <div id="link2" class="col-md-2"></div>
</div>
</div>
<footer></footer>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#navbar").load("web/include/navbar.htm", function() {
            $("ul.navbar-nav li.homepage").addClass("active");
        });

        $("footer").load("web/include/copyright2.html");

        $("#link2").load("web/include/link2.html", function() {
            $("a.content").click(function(e) {
                e.stopPropagation();
                $("div.main-content").load($(this).data("href"));
            });
        });
    });
</script>
</body>
</html>
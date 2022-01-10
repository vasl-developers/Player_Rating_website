<html lang="en">
<?php
$ROOT = '../../';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
include_once "web/include/navbar.htm";
include_once "web/pages/functions.php";
include "web/pages/connection.php";
$mysqli = mysqli_connect($host, $username, $password, $database);
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        <?php

        $passplayercode = $_GET['playercode']; //playercode is passed from
        $sql = "select PlayerName, PlayerNameCode, 1period, 2period, 3period, 4period, 5period, 6period, 7period, 8period, 9period, 10period from player_progress where PlayerNameCode = ?";
        if ($getPlayer = $mysqli->prepare($sql)) {
            $getPlayer->bind_param("s", $passplayercode);
            $getPlayer->execute();
            $getPlayer->bind_result($name, $pnc, $firstperiod, $secondperiod, $thirdperiod, $fourthperiod,
                $fifthperiod, $sixthperiod, $seventhperiod, $eighthperiod, $ninthperiod, $tenthperiod);
        }
        $row = $getPlayer->fetch();
        ?>

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['GP', 'Rating'],
                ['10%',  <?php echo $firstperiod ?>],
                ['20%',  <?php echo $secondperiod ?>],
                ['30%',  <?php echo $thirdperiod ?>],
                ['40%',  <?php echo $fourthperiod ?>],
                ['50%',  <?php echo $fifthperiod ?>],
                ['60%',  <?php echo $sixthperiod ?>],
                ['70%',  <?php echo $seventhperiod ?>],
                ['80%',  <?php echo $eighthperiod ?>],
                ['90%',  <?php echo $ninthperiod ?>],
                ['100%',  <?php echo $tenthperiod ?>]

            ]);

            var options = {
                title: 'Player Rating Progress',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>
<body>

<div class="container">
    <h3 class="mt-3">Progress of Player Rating for <?php echo prettyName($name) ?></h3>
    <p>This chart shows how a player's rating has changed over time. Each data point represents 10% of the player's games played. Displays ratings when hovering over line.</p>
    <p>Players with less than 10 games will show as 0 rating.</p>
    <p>For players who stop playing for a long period of time, their decay is not displayed unless they resume playing. </p>
    <p>Updated on the 1st of every month. Games added during a month will be included next month.</p>
    <br>
</div>
<div class="home container-fluid">

    <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
    </body>

</div>
<?php

include_once "web/include/footer.php";
?>
</body>
</html>

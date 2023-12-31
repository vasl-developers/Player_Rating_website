<html lang="en">
<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once "web/include/header.php";
?>
<body>
<?php
include_once "web/include/navbar.htm";
include_once "web/pages/connection.php";
include_once "web/pages/functions.php";
$mysqli = new mysqli($host, $username, $password, $database);
$mysqli2 = new mysqli($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
$mysqli->set_charset("utf8");
?>
<div class="home container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h2>Top Ten Games Played by Player</h2>
            <p>To view Game-by-Game results for a particular player, click on the link.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 offset-md-1">
            <?php
            $sql = "SELECT Fullname, Games, Player1_Namecode FROM player_ratings ORDER BY Games DESC LIMIT 15";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->execute();
                $stmt->bind_result($fullname, $gamesplayed, $pnc);
                ?>
                <div class="tableFixHead autoHeight">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Player</th>
                            <th>Games Played</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $stmt->fetch()) {
                            $name = trim($fullname);
                            ?>
                            <tr>
                                <td><a class="content" href="tablePlayerGameResults.php?playercode=<?php echo $pnc ?>">
                                        <?php echo prettyname($name) ?></a></td>
                                <td>
                                    <?php echo $gamesplayed ?>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            $stmt->close();
            ?>
        </div>
    </div>
</div>
<?php
$mysqli->close();
include_once "web/include/footer.php";
?>
</body>
</html>


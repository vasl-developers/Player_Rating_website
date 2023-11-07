<?php include_once "web/pages/gamecount.php"; ?>

<h2>ASL Player Rating System</h2>

<p>View player rankings, ratings, and game-by-game results. See tournament listings and game-by-game results.</p>

<p>Statistical reports by player, tournament and scenario. Check player vs player match-ups.</p>

<div class="row mt-3">
  <div class="col-md-6">
    <h3>ASL Player Rating Information</h3>
    <h5>Rankings and Alphabetical Display</h5>
    <div class="list-group">
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/AlphabeticalListingofActivePlayers.php">Alphabetical listing of Active Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/RankedListingofActivePlayers.php">Ranked listing of Active Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/AlphabeticalListingofAllPlayers.php">Alphabetical listing of All Players</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/RankedListingofAllPlayers.php">Ranked listing of All Players</a>
    </div>
  </div>

  <div class="col-md-6">
    <h3>ASL Tournament Information</h3>
    <h5>Tournament List and Game Results</h5>
    <div class="list-group">
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/tableTournamentsRecentlyAdded.php">Tournaments Recently Added</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/tableTournamentsbyYear.php">Tournaments Included</a>
      <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/selectTournamentforResultsDisplay.php">Show Tournament Results</a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <p>As of <?php echo date("F j, Y"); ?>, the ASL Player Rating System contains the results of <b><?php echo number_format($gamestotal) ?> games</b>.</p>
  <p>This tool replaces the <a href="http://asl-area.org" target="_blank">AREA rating tool</a> managed by Bruno Nitrosso until 2017. It uses the same tournament-based information for determining ratings and rankings as did Bruno's AREA. It is a player rating system whose goal is to support ranking/seeding players during tournament play.</p>
  <p>The ASL Player Rating system incorporates the rules algorithm used by AREA to determine ratings. As of October 1, 2021, player ratings include several improvements to the rating calculation method. See "Rating Methodology" for more information.</p>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <h3>ASL Top Ten Information</h3>
    <div class="col-md-12 mt-3">
          <p>Which players have won the most tournament games, who has the best winning percentage? Find out this and more in these links which are based on recorded tournament games. Find the rankings behind the ratings!</p>
    </div>
  </div>
  <div class="col-md-6">
    <h3>ASL Scenario Information</h3>
    <div class="list-group">
          <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/mostPlayedScenarios.php">Most Played Scenarios at Tournaments</a>
    </div>
  </div>

</div>
<div class="row mt-3">
    <div class="col-md-3">
      <div class="list-group">
        <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenMostGames.php">Most Games Played</a>
      </div>
      <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenWins.php">Most Games Won</a>
      </div>
      <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenWinPct.php">Winning Percentage</a>
      </div>
      <br>
    </div>
  <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenWinStreak.php">Win Streak</a>
        </div>
        <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenDiffOpp.php">Different Opponents (slow to load) </a>
        </div>
        <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/playertoptenTourFin.php">Tournament Finishes Score (slow to load)</a>
        </div>
  </div>

</div>
<div class="row mt-3">
  <div class="col-md-6">
        <h3>ASL Player Matchups</h3>
        <div class="col-md-12 mt-3">
            <p>How do you fare head-to-head against other players? Check out other players' one-on-one results. All results are based on recorded tournament games.</p>
        </div>
  </div>
</div>
<div class="row mt-3>"
  <div class="col-md-6">
        <div class="list-group">
            <a class="list-group-item list-group-item-action list-group-item-primary flex-fill" href="web/pages/toolPlayervPlayer.php">Player Matchups</a>
        </div>
  </div>
</div>
